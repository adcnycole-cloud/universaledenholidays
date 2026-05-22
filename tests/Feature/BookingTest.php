<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $product = Product::where('is_active', true)->firstOrFail();

        $response = $this->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Ava Tan',
            'email' => 'ava@gmail.com',
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

        $booking = \App\Models\Booking::where('email', 'ava@gmail.com')->firstOrFail();

        $response->assertRedirect(route('bookings.track.show', $booking->booking_reference));

        $this->assertDatabaseHas('bookings', [
            'full_name' => 'Ava Tan',
            'email' => 'ava@gmail.com',
            'status' => 'pending',
            'payment_status' => 'awaiting_confirmation',
            'product_id' => $product->id,
        ]);
    }

    public function test_booking_still_succeeds_when_confirmation_email_fails(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();

        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new \RuntimeException('SMTP failed'));

        $response = $this->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Noah Lim',
            'email' => 'noah@gmail.com',
            'phone' => '+60111222333',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 1,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'check_in_date' => now()->addDays(3)->toDateString(),
            'check_out_date' => now()->addDays(5)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
        ]);

        $booking = \App\Models\Booking::where('email', 'noah@gmail.com')->firstOrFail();

        $response->assertRedirect(route('bookings.track.show', $booking->booking_reference));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'email' => 'noah@gmail.com',
            'product_id' => $product->id,
        ]);
    }

    public function test_booking_uses_discounted_product_prices_when_discount_is_active(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();
        $product->update([
            'is_discounted' => true,
            'discount_percentage' => 25,
            'malaysia_adult_price_myr' => 400,
            'malaysia_child_price_myr' => 200,
            'international_adult_price_myr' => 500,
            'international_child_price_myr' => 250,
        ]);

        $response = $this->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Discount Guest',
            'email' => 'discount@gmail.com',
            'phone' => '+60123456789',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 1,
            'malaysian_kids' => 1,
            'international_adults' => 1,
            'international_kids' => 1,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('bookings', [
            'email' => 'discount@gmail.com',
            'amount_myr' => 1012.5,
            'amount_display' => 1012.5,
        ]);
    }

    public function test_booking_rejects_invalid_email_and_phone(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();

        $response = $this->from('/booking')->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Bad Contact',
            'email' => 'not-a-real-email',
            'phone' => 'abc123',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 1,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
        ]);

        $response->assertRedirect('/booking');
        $response->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_booking_rejects_repeated_digit_phone_numbers(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();

        $response = $this->from('/booking')->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Dummy Number',
            'email' => 'dummy@gmail.com',
            'phone' => '1111111111',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 1,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
        ]);

        $response->assertRedirect('/booking');
        $response->assertSessionHasErrors(['phone']);
    }

    public function test_booking_rejects_phone_without_supported_country_code_or_prefix(): void
    {
        $product = Product::where('is_active', true)->firstOrFail();

        $response = $this->from('/booking')->post('/bookings', [
            'product_id' => $product->id,
            'service_type' => $product->category,
            'full_name' => 'Wrong Prefix',
            'email' => 'prefix@gmail.com',
            'phone' => '+609999',
            'pickup_location' => 'KKIA',
            'malaysian_adults' => 1,
            'malaysian_kids' => 0,
            'international_adults' => 0,
            'international_kids' => 0,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(8)->toDateString(),
            'payment_method' => 'bank_transfer',
            'currency_code' => 'MYR',
        ]);

        $response->assertRedirect('/booking');
        $response->assertSessionHasErrors(['phone']);
    }
}
