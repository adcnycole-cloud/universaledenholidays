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
                'name' => 'Tanjung Aru Sunset Beach Escape',
                'category' => 'package',
                'location' => 'Kota Kinabalu',
                'summary' => 'A classic KK beach package built around sunset views, relaxed seaside time, and city convenience.',
                'description' => 'Tanjung Aru Beach is one of Kota Kinabalu\'s most loved seaside spots, famous for glowing sunset skies and an easy local atmosphere. This package gives travelers a smooth coastal stay with beach time, food spots, and a relaxed urban-holiday pace.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Karambunai Nexus Beach Resort Package',
                'category' => 'package',
                'location' => 'Karambunai, Kota Kinabalu',
                'summary' => 'A resort-style KK beach holiday with sea views, soft sands, and easy waterfront downtime.',
                'description' => 'Karambunai is known for wide beaches, tropical scenery, and a more secluded resort feel north of the city. This package is ideal for guests wanting a polished beach stay with resort comfort, pool access, and coastal leisure.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Manukan Island Sea Breeze Package',
                'category' => 'package',
                'location' => 'Kota Kinabalu, Manukan Island',
                'summary' => 'A lively KK beach-and-island package with clear water, snorkeling, and easy jetty access.',
                'description' => 'Manukan Island is one of the most popular sea escapes near Kota Kinabalu, known for calm water, sandy shores, and marine-park scenery. This package suits guests who want beach lounging, short boat rides, and a simple tropical extension from the city.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Sapi Island Beach Leisure Package',
                'category' => 'package',
                'location' => 'Kota Kinabalu, Sapi Island',
                'summary' => 'A breezy island-beach package with swim-friendly waters and easy marine-park fun.',
                'description' => 'Sapi Island is a favorite for quick island getaways from Kota Kinabalu, with bright waters, snorkeling spots, and a fun daytime beach atmosphere. This package blends city convenience with a light beach escape for travelers who want an easy coastal break.',
                'duration' => '3 days 2 nights',
                'price_myr' => 880.00,
                'malaysia_adult_price_myr' => 880.00,
                'malaysia_child_price_myr' => 704.00,
                'international_adult_price_myr' => 1144.00,
                'international_child_price_myr' => 836.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Shangri-La Rasa Ria Coastal Getaway',
                'category' => 'package',
                'location' => 'Tuaran Coast, Kota Kinabalu',
                'summary' => 'A premium KK coastal package with long beaches, resort comfort, and relaxed sea views.',
                'description' => 'This package focuses on a polished north-coast beach experience with wide sands, resort ambience, and a calm holiday rhythm. It works well for couples and families who want a more comfortable Kota Kinabalu beach break with resort-style convenience.',
                'duration' => '3 days 2 nights',
                'price_myr' => 980.00,
                'malaysia_adult_price_myr' => 980.00,
                'malaysia_child_price_myr' => 784.00,
                'international_adult_price_myr' => 1274.00,
                'international_child_price_myr' => 931.00,
                'capacity' => 14,
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
            'Tanjung Aru Sunset Beach Escape',
            'Karambunai Nexus Beach Resort Package',
            'Manukan Island Sea Breeze Package',
            'Sapi Island Beach Leisure Package',
            'Shangri-La Rasa Ria Coastal Getaway',
        ])->delete();
    }
};
