<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Testimonial;
use App\Services\GooglePlaceReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleReviewsDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_render_website_and_google_reviews_together(): void
    {
        Testimonial::create([
            'name' => 'Site Guest',
            'email' => 'siteguest@example.com',
            'location' => 'Kota Kinabalu',
            'trip_name' => 'Sabah Highlights',
            'quote' => 'The local team made everything feel smooth and easy.',
            'rating' => 5,
            'is_featured' => true,
            'display_location' => 'landing',
        ]);

        $this->app->instance(GooglePlaceReviewService::class, new class extends GooglePlaceReviewService
        {
            public function getPlaceReviews(): array
            {
                return [
                    'place_name' => 'Universal Eden Holidays',
                    'rating' => 4.8,
                    'reviews_count' => 24,
                    'place_url' => 'https://maps.google.com/example-place',
                    'reviews' => [
                        [
                            'source' => 'google',
                            'source_label' => 'Google review',
                            'name' => 'Google Traveler',
                            'location' => 'Google Maps',
                            'trip_name' => 'Universal Eden Holidays',
                            'quote' => 'Great communication and dependable support throughout the trip.',
                            'rating' => 5,
                            'profile_photo_url' => 'https://example.com/google-traveler.jpg',
                            'published_label' => '2 weeks ago',
                            'review_url' => 'https://maps.google.com/example-review',
                        ],
                    ],
                ];
            }
        });

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Site Guest')
            ->assertSee('Google Traveler')
            ->assertSee('View on Google');
    }

    public function test_product_page_can_render_google_reviews_alongside_package_reviews(): void
    {
        $product = Product::where('category', 'package')->firstOrFail();

        Testimonial::create([
            'name' => 'Package Explorer',
            'email' => 'packageexplorer@example.com',
            'location' => 'Singapore',
            'trip_name' => $product->name,
            'quote' => 'The itinerary was clear and the booking process felt very organized.',
            'rating' => 4,
            'is_featured' => true,
            'display_location' => 'package',
            'product_id' => $product->id,
        ]);

        $this->app->instance(GooglePlaceReviewService::class, new class extends GooglePlaceReviewService
        {
            public function getPlaceReviews(): array
            {
                return [
                    'place_name' => 'Universal Eden Holidays',
                    'rating' => 4.7,
                    'reviews_count' => 18,
                    'place_url' => 'https://maps.google.com/example-place',
                    'reviews' => [
                        [
                            'source' => 'google',
                            'source_label' => 'Google review',
                            'name' => 'Maps Reviewer',
                            'location' => 'Google Maps',
                            'trip_name' => 'Universal Eden Holidays',
                            'quote' => 'Fast replies and reliable travel coordination.',
                            'rating' => 5,
                            'profile_photo_url' => 'https://example.com/maps-reviewer.jpg',
                            'published_label' => '1 month ago',
                            'review_url' => 'https://maps.google.com/example-review',
                        ],
                    ],
                ];
            }
        });

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Package Explorer')
            ->assertSee('Maps Reviewer')
            ->assertSee('View on Google');
    }
}
