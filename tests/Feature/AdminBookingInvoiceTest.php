<?php

namespace Tests\Feature;

use App\Mail\BookingInvoiceMail;
use App\Models\Booking;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminBookingInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_email_invoice_pdf_for_confirmed_booking(): void
    {
        Mail::fake();
        config()->set('mail.default', 'smtp');
        config()->set('mail.mailers.smtp.host', 'smtp.gmail.com');
        config()->set('mail.mailers.smtp.port', 587);
        config()->set('mail.mailers.smtp.username', 'mailer@example.com');
        config()->set('mail.mailers.smtp.password', 'secret-app-password');
        config()->set('mail.from.address', 'hello@example.com');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::where('is_active', true)->firstOrFail();

        $booking = Booking::create([
            'product_id' => $product->id,
            'booking_reference' => 'UEH12345',
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
            'check_in_date' => now()->addDays(7)->toDateString(),
            'check_out_date' => now()->addDays(10)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'amount_myr' => 250,
            'amount_display' => 250,
            'status' => 'confirmed',
            'payment_status' => 'awaiting_payment',
            'confirmed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.bookings.invoice.email', $booking));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();

        $this->assertNotNull($booking->invoice_number);
        $this->assertNotNull($booking->invoice_issued_at);

        Mail::assertSent(BookingInvoiceMail::class, function (BookingInvoiceMail $mail) use ($booking) {
            return $mail->hasTo($booking->email)
                && $mail->booking->is($booking);
        });
    }

    public function test_admin_gets_error_when_invoice_email_delivery_is_not_configured(): void
    {
        Mail::fake();
        config()->set('mail.default', 'log');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::where('is_active', true)->firstOrFail();

        $booking = Booking::create([
            'product_id' => $product->id,
            'booking_reference' => 'UEH54321',
            'service_type' => $product->category,
            'full_name' => 'Mia Lee',
            'email' => 'mia@example.com',
            'phone' => '+60199887766',
            'pickup_location' => 'KKIA',
            'destination' => $product->location,
            'package_name' => $product->name,
            'malaysian_adults' => 1,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'guest_count' => 1,
            'check_in_date' => now()->addDays(4)->toDateString(),
            'check_out_date' => now()->addDays(6)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'amount_myr' => 150,
            'amount_display' => 150,
            'status' => 'confirmed',
            'payment_status' => 'awaiting_payment',
            'confirmed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.bookings.invoice.email', $booking));

        $response->assertRedirect();
        $response->assertSessionHasErrors('invoice_email');

        Mail::assertNothingSent();
    }

    public function test_admin_gets_specific_gmail_app_password_error_when_smtp_login_is_rejected(): void
    {
        config()->set('mail.default', 'smtp');
        config()->set('mail.mailers.smtp.host', 'smtp.gmail.com');
        config()->set('mail.mailers.smtp.port', 587);
        config()->set('mail.mailers.smtp.username', 'goldenbluish@gmail.com');
        config()->set('mail.mailers.smtp.password', 'wrong-password');
        config()->set('mail.from.address', 'goldenbluish@gmail.com');

        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new \RuntimeException(
            '534-5.7.9 Application-specific password required. https://support.google.com/mail/?p=InvalidSecondFactor'
        ));

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::where('is_active', true)->firstOrFail();

        $booking = Booking::create([
            'product_id' => $product->id,
            'booking_reference' => 'UEH99999',
            'service_type' => $product->category,
            'full_name' => 'Kai Tan',
            'email' => 'kai@example.com',
            'phone' => '+60123456789',
            'pickup_location' => 'KKIA',
            'destination' => $product->location,
            'package_name' => $product->name,
            'malaysian_adults' => 2,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'guest_count' => 2,
            'check_in_date' => now()->addDays(7)->toDateString(),
            'check_out_date' => now()->addDays(10)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
            'amount_myr' => 250,
            'amount_display' => 250,
            'status' => 'confirmed',
            'payment_status' => 'awaiting_payment',
            'confirmed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.bookings.invoice.email', $booking));

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'invoice_email' => 'Gmail rejected the login. Please use a Google App Password in MAIL_PASSWORD instead of the normal Gmail password.',
        ]);
    }
}
