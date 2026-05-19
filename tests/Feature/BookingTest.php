<?php

namespace Tests\Feature;

use App\Mail\BookingPaymentReminderMail;
use App\Mail\BookingSubmittedMail;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Latest promos and limited-time offers');
    }

    public function test_booking_can_be_created(): void
    {
        Mail::fake();

        $product = Product::where('is_active', true)->firstOrFail();

        $response = $this->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Ava Tan',
            'email' => 'ava@example.com',
            'phone' => '+60123456789',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 2,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'special_requests' => 'Sea view room',
        ]);

        $booking = Booking::query()->latest('id')->firstOrFail();

        $response->assertRedirect(route('bookings.payment.show', ['reference' => $booking->booking_reference]));

        $this->assertDatabaseHas('bookings', [
            'full_name' => 'Ava Tan',
            'email' => 'ava@example.com',
            'status' => 'pending',
            'product_id' => $product->id,
            'payment_status' => 'awaiting_payment',
        ]);

        Mail::assertSent(BookingSubmittedMail::class, function (BookingSubmittedMail $mail) use ($booking) {
            return $mail->booking->is($booking);
        });
    }

    public function test_booking_can_be_accessed_by_reference_and_email(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();

        $booking = Booking::create([
            'product_id' => $product->id,
            'booking_reference' => 'UEH-ACCESS01',
            'service_type' => $product->category,
            'full_name' => 'Ava Tan',
            'email' => 'ava@example.com',
            'phone' => '+60123456789',
            'pickup_location' => 'KKIA',
            'destination' => $product->location,
            'package_name' => $product->name,
            'malaysian_adults' => 2,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'guest_count' => 2,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'amount_myr' => 500,
            'amount_display' => 500,
            'status' => 'pending',
            'payment_status' => 'awaiting_payment',
        ]);

        $lookupResponse = $this->post(route('bookings.lookup.submit'), [
            'booking_reference' => $booking->booking_reference,
            'email' => $booking->email,
        ]);

        $lookupResponse->assertRedirect(route('bookings.payment.show', ['reference' => $booking->booking_reference]));

        $this->get(route('bookings.payment.show', ['reference' => $booking->booking_reference]))
            ->assertOk()
            ->assertSee($booking->booking_reference);
    }

    public function test_payment_reminder_is_sent_three_days_before_check_in_for_unpaid_booking(): void
    {
        Mail::fake();

        $product = Product::where('is_active', true)->firstOrFail();

        $booking = Booking::create([
            'product_id' => $product->id,
            'booking_reference' => 'UEH-REMIND01',
            'service_type' => $product->category,
            'full_name' => 'Ava Tan',
            'email' => 'ava@example.com',
            'phone' => '+60123456789',
            'pickup_location' => 'KKIA',
            'destination' => $product->location,
            'package_name' => $product->name,
            'malaysian_adults' => 2,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'guest_count' => 2,
            'check_in_date' => now()->addDays(3)->toDateString(),
            'check_out_date' => now()->addDays(5)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'amount_myr' => 500,
            'amount_display' => 500,
            'status' => 'pending',
            'payment_status' => 'awaiting_payment',
        ]);

        Artisan::call('bookings:send-payment-reminders');

        Mail::assertSent(BookingPaymentReminderMail::class, function (BookingPaymentReminderMail $mail) use ($booking) {
            return $mail->booking->is($booking);
        });

        $this->assertNotNull($booking->fresh()->payment_reminder_sent_at);
    }
}
