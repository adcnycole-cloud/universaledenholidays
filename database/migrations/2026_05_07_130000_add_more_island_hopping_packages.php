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
                'name' => 'Mantanani Island Escape',
                'category' => 'package',
                'location' => 'Kota Belud, Sabah',
                'summary' => 'A tropical island package with white sand, snorkeling, and turquoise-water scenery.',
                'description' => 'Mantanani Islands are known for clear waters, white sandy beaches, and a laid-back island atmosphere. This package combines boat transfers, beach time, snorkeling opportunities, and a scenic overnight escape for travelers who want Sabah\'s island side in a relaxed format.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Bohey Dulang Island Adventure',
                'category' => 'package',
                'location' => 'Semporna Marine Park',
                'summary' => 'A scenic marine-park stay with panoramic viewpoints, island hopping, and reef-blue waters.',
                'description' => 'Bohey Dulang is famous for its breathtaking lagoon and elevated viewpoint trail inside Tun Sakaran Marine Park. This package is designed for guests who want classic Semporna views, boat adventures, island scenery, and a balanced island-hopping itinerary.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 16,
                'is_featured' => true,
                'is_top_choice' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Sipadan Island Dive Escape',
                'category' => 'package',
                'location' => 'Semporna, Sabah',
                'summary' => 'A premium island package centered on world-famous marine life and clear-water diving scenery.',
                'description' => 'Sipadan is one of Malaysia\'s most iconic island destinations, known for dramatic drop-offs, rich marine biodiversity, and unforgettable underwater encounters. This package suits travelers looking for a polished Semporna stay with island access, scenic boat rides, and a premium ocean setting.',
                'duration' => '4 days 3 nights',
                'price_myr' => 1000.00,
                'malaysia_adult_price_myr' => 1000.00,
                'malaysia_child_price_myr' => 800.00,
                'international_adult_price_myr' => 1300.00,
                'international_child_price_myr' => 950.00,
                'capacity' => 14,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Mabul and Kapalai Water Villa Package',
                'category' => 'package',
                'location' => 'Semporna Islands',
                'summary' => 'A dreamy water-villa island package with snorkeling, resort views, and easy tropical downtime.',
                'description' => 'This package blends the postcard beauty of Mabul and Kapalai with island boat transfers, reef scenery, and laid-back sea time. It is a strong fit for couples and leisure travelers who want a softer island-hopping pace with memorable views.',
                'duration' => '3 days 2 nights',
                'price_myr' => 920.00,
                'malaysia_adult_price_myr' => 920.00,
                'malaysia_child_price_myr' => 736.00,
                'international_adult_price_myr' => 1196.00,
                'international_child_price_myr' => 874.00,
                'capacity' => 14,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Tunku Abdul Rahman Island Leisure Package',
                'category' => 'package',
                'location' => 'Tunku Abdul Rahman Marine Park',
                'summary' => 'An easy island-hopping package from Kota Kinabalu with beaches, boat rides, and snorkel stops.',
                'description' => 'Built around Sabah\'s most accessible island cluster, this package includes marine-park scenery, beach relaxation, simple snorkeling, and smooth transfers from Kota Kinabalu. It works especially well for first-time visitors and family travelers.',
                'duration' => '3 days 2 nights',
                'price_myr' => 780.00,
                'malaysia_adult_price_myr' => 780.00,
                'malaysia_child_price_myr' => 624.00,
                'international_adult_price_myr' => 1014.00,
                'international_child_price_myr' => 741.00,
                'capacity' => 18,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80',
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
            'Mantanani Island Escape',
            'Bohey Dulang Island Adventure',
            'Sipadan Island Dive Escape',
            'Mabul and Kapalai Water Villa Package',
            'Tunku Abdul Rahman Island Leisure Package',
        ])->delete();
    }
};
