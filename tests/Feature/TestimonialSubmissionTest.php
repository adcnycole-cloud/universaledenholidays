<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonialSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_landing_page_review_for_admin_approval(): void
    {
        Storage::fake('public');
        $quote = 'Everything felt easy and well coordinated from pickup to the last stop.';
        $photo = UploadedFile::fake()->image('landing-review.jpg');

        $response = $this->post('/testimonials', [
            'name' => 'Past Customer',
            'email' => 'pastcustomer@gmail.com',
            'location' => 'Kuala Lumpur, Malaysia',
            'trip_name' => 'Island Escape Package',
            'rating' => 5,
            'quote' => $quote,
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect(route('home').'#testimonials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Past Customer',
            'email' => 'pastcustomer@gmail.com',
            'display_location' => 'landing',
            'is_featured' => false,
            'quote' => $quote,
        ]);

        $testimonial = \App\Models\Testimonial::where('email', 'pastcustomer@gmail.com')->firstOrFail();
        $this->assertNotNull($testimonial->profile_photo_path);
        Storage::disk('public')->assertExists($testimonial->profile_photo_path);

        $this->get('/')
            ->assertOk()
            ->assertDontSee($quote);
    }

    public function test_customer_can_submit_package_review_for_admin_approval(): void
    {
        Storage::fake('public');
        $product = Product::where('category', 'package')->firstOrFail();
        $quote = 'We loved the pacing, the planning, and how easy the whole package felt.';
        $photo = UploadedFile::fake()->image('package-review.jpg');

        $response = $this->post(route('products.testimonials.store', $product), [
            'name' => 'Package Guest',
            'email' => 'packageguest@gmail.com',
            'location' => 'Singapore',
            'trip_name' => $product->name,
            'rating' => 4,
            'quote' => $quote,
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect(route('products.show', $product).'#reviews');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Package Guest',
            'email' => 'packageguest@gmail.com',
            'display_location' => 'package',
            'product_id' => $product->id,
            'is_featured' => false,
            'quote' => $quote,
        ]);

        $testimonial = \App\Models\Testimonial::where('email', 'packageguest@gmail.com')->firstOrFail();
        $this->assertNotNull($testimonial->profile_photo_path);
        Storage::disk('public')->assertExists($testimonial->profile_photo_path);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee($quote);
    }
}
