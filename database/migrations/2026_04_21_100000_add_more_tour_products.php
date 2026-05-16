<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        $tours = [
            [
                'name' => 'Tunku Abdul Rahman Island Hopping Tour',
                'category' => 'tour',
                'location' => 'Kota Kinabalu',
                'summary' => 'A sunny island-hopping trip with snorkeling, beaches, and easy boat transfers.',
                'description' => 'A relaxed marine park day tour covering clear-water islands, snorkeling time, beach leisure, and smooth transfers from Kota Kinabalu.',
                'duration' => 'Full day',
                'price_myr' => 245.00,
                'malaysia_adult_price_myr' => 245.00,
                'malaysia_child_price_myr' => 196.00,
                'international_adult_price_myr' => 318.50,
                'international_child_price_myr' => 232.75,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Klias Wetland River Cruise',
                'category' => 'tour',
                'location' => 'Beaufort, Sabah',
                'summary' => 'Cruise for proboscis monkeys, mangroves, and a firefly-lit evening river experience.',
                'description' => 'A popular west coast wildlife outing with river cruising, sunset views, local dinner, and a calm firefly session after dusk.',
                'duration' => 'Evening tour',
                'price_myr' => 230.00,
                'malaysia_adult_price_myr' => 230.00,
                'malaysia_child_price_myr' => 184.00,
                'international_adult_price_myr' => 299.00,
                'international_child_price_myr' => 218.50,
                'capacity' => 20,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Sepilok Orangutan and Sun Bear Discovery Tour',
                'category' => 'tour',
                'location' => 'Sandakan',
                'summary' => 'A conservation-focused day out with orangutans, sun bears, and rainforest education.',
                'description' => 'Ideal for nature lovers who want a guided visit to Sepilok highlights with wildlife interpretation and an easy Sandakan-based itinerary.',
                'duration' => 'Full day',
                'price_myr' => 280.00,
                'malaysia_adult_price_myr' => 280.00,
                'malaysia_child_price_myr' => 224.00,
                'international_adult_price_myr' => 364.00,
                'international_child_price_myr' => 266.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1549366021-9f761d040a94?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Mantanani Island Snorkeling Escape',
                'category' => 'tour',
                'location' => 'Kota Belud, Sabah',
                'summary' => 'Offshore island adventure with turquoise water, beach time, and snorkeling stops.',
                'description' => 'A scenic island tour for travelers seeking a quieter sea escape with boat rides, reef time, and postcard-style coastal views.',
                'duration' => 'Full day',
                'price_myr' => 355.00,
                'malaysia_adult_price_myr' => 355.00,
                'malaysia_child_price_myr' => 284.00,
                'international_adult_price_myr' => 461.50,
                'international_child_price_myr' => 337.25,
                'capacity' => 14,
                'is_featured' => false,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Semporna Islands Day Adventure',
                'category' => 'tour',
                'location' => 'Semporna',
                'summary' => 'A boat-based island day tour with clear water views and classic east coast scenery.',
                'description' => 'Built for travelers chasing Sabah’s tropical side with island landings, scenic boat transfers, and laid-back marine park time.',
                'duration' => 'Full day',
                'price_myr' => 390.00,
                'malaysia_adult_price_myr' => 390.00,
                'malaysia_child_price_myr' => 312.00,
                'international_adult_price_myr' => 507.00,
                'international_child_price_myr' => 370.50,
                'capacity' => 15,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        foreach ($tours as $tour) {
            DB::table('products')->updateOrInsert(
                ['name' => $tour['name']],
                $tour
            );
        }
    }

    public function down(): void
    {
        DB::table('products')->whereIn('name', [
            'Tunku Abdul Rahman Island Hopping Tour',
            'Klias Wetland River Cruise',
            'Sepilok Orangutan and Sun Bear Discovery Tour',
            'Mantanani Island Snorkeling Escape',
            'Semporna Islands Day Adventure',
        ])->delete();
    }
};
