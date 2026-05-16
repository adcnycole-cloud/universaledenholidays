<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        $packages = [
            [
                'name' => 'Mount Kinabalu Adventure',
                'category' => 'package',
                'location' => 'Kundasang, Ranau',
                'summary' => 'A scenic Kundasang escape with mountain viewpoints, local markets, and cool highland air.',
                'description' => 'Mount Kinabalu is Borneo\'s highest peak at 4,095.2m, a UNESCO Global Geopark famous for its striking granite summit. This package pairs signature Kundasang viewpoints with market stops, local meals, and a relaxed highland itinerary for travelers who want nature, comfort, and memorable photo spots.',
                'duration' => '4 days 3 nights',
                'price_myr' => 850.00,
                'malaysia_adult_price_myr' => 850.00,
                'malaysia_child_price_myr' => 680.00,
                'international_adult_price_myr' => 1105.00,
                'international_child_price_myr' => 807.50,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Ranau-Poring Hot Spring',
                'category' => 'package',
                'location' => 'Kundasang, Ranau',
                'summary' => 'A restful package combining hot springs, canopy walks, and refreshing highland scenery.',
                'description' => 'Poring Hot Springs, located about 40km from Kinabalu Park, is known for its hot sulphur baths, lush rainforest, and elevated canopy walkway. This package mixes Kundasang cool-weather highlights with Ranau nature stops for a balanced Sabah getaway.',
                'duration' => '4 days 3 nights',
                'price_myr' => 850.00,
                'malaysia_adult_price_myr' => 850.00,
                'malaysia_child_price_myr' => 680.00,
                'international_adult_price_myr' => 1105.00,
                'international_child_price_myr' => 807.50,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1473773508845-188df298d2d1?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Explore to the Rabbit Farm',
                'category' => 'package',
                'location' => 'Kundasang, Ranau',
                'summary' => 'A family-friendly Kundasang package with fun attractions, gardens, and scenic farm visits.',
                'description' => 'Built for relaxed family travel, this package includes the popular Rabbit Farm experience, cool-weather attractions, farm landscapes, and fun stops around Kundasang. It is a cheerful option for guests who want sightseeing, activities, and easy pacing in one trip.',
                'duration' => '4 days 3 nights',
                'price_myr' => 850.00,
                'malaysia_adult_price_myr' => 850.00,
                'malaysia_child_price_myr' => 680.00,
                'international_adult_price_myr' => 1105.00,
                'international_child_price_myr' => 807.50,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Desa Dairy and Nabalu Highland Retreat',
                'category' => 'package',
                'location' => 'Kundasang',
                'summary' => 'A pastoral highland retreat with dairy farm scenery, craft stalls, and mountain-view stays.',
                'description' => 'This Kundasang package combines the famous Desa Dairy Farm, Nabalu artisan stalls, village scenery, and panoramic Mount Kinabalu views. It suits travelers looking for a gentler countryside experience with strong photo appeal and cool-air relaxation.',
                'duration' => '3 days 2 nights',
                'price_myr' => 720.00,
                'malaysia_adult_price_myr' => 720.00,
                'malaysia_child_price_myr' => 576.00,
                'international_adult_price_myr' => 936.00,
                'international_child_price_myr' => 684.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Kinabalu Park and Kundasang Leisure Escape',
                'category' => 'package',
                'location' => 'Kinabalu Park, Kundasang',
                'summary' => 'A cool-climate package focused on nature walks, park viewpoints, and easy Sabah highland travel.',
                'description' => 'Centered around Kinabalu Park and Kundasang, this itinerary offers mountain panoramas, garden-style stops, local food, and a restful pace. It works well for couples, families, and first-time visitors who want Sabah\'s highland side without a strenuous itinerary.',
                'duration' => '3 days 2 nights',
                'price_myr' => 780.00,
                'malaysia_adult_price_myr' => 780.00,
                'malaysia_child_price_myr' => 624.00,
                'international_adult_price_myr' => 1014.00,
                'international_child_price_myr' => 741.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        foreach ($packages as $package) {
            DB::table('products')->updateOrInsert(
                ['name' => $package['name']],
                $package
            );
        }
    }

    public function down(): void
    {
        DB::table('products')->whereIn('name', [
            'Mount Kinabalu Adventure',
            'Ranau-Poring Hot Spring',
            'Explore to the Rabbit Farm',
            'Desa Dairy and Nabalu Highland Retreat',
            'Kinabalu Park and Kundasang Leisure Escape',
        ])->delete();
    }
};
