<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response->assertRedirect('/');

        $this->assertDatabaseHas('bookings', [
            'full_name' => 'Ava Tan',
            'email' => 'ava@example.com',
            'status' => 'pending',
            'product_id' => $product->id,
        ]);
    }
}
