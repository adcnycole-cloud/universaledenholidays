<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('location');
            $table->string('summary');
            $table->text('description');
            $table->string('duration');
            $table->decimal('price_myr', 10, 2);
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_top_choice')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name' => 'Kota Kinabalu Airport Transfer',
                'category' => 'transport',
                'location' => 'Kota Kinabalu',
                'summary' => 'Private transfer between airport, hotel, and city hotspots.',
                'description' => 'Comfortable point-to-point transport for families, couples, and business travelers arriving in Sabah.',
                'duration' => 'One-way service',
                'price_myr' => 85,
                'capacity' => 4,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'West Coast Shuttle Pass',
                'category' => 'transport',
                'location' => 'Kota Kinabalu to Kundasang',
                'summary' => 'Shared shuttle for scenic rides across Sabah west coast.',
                'description' => 'Ideal for travelers who want dependable transport between Kota Kinabalu, Kundasang, and surrounding attractions.',
                'duration' => 'Full day',
                'price_myr' => 120,
                'capacity' => 10,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sabah Nature Highlights',
                'category' => 'package',
                'location' => 'Kota Kinabalu and Kundasang',
                'summary' => 'A multi-day package for first-time visitors to Sabah.',
                'description' => 'Includes hotel, guided sightseeing, transport, and visits to signature nature spots around the west coast.',
                'duration' => '3 days 2 nights',
                'price_myr' => 890,
                'capacity' => 20,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Island Escape Package',
                'category' => 'package',
                'location' => 'Tunku Abdul Rahman Marine Park',
                'summary' => 'Sea, snorkeling, and easy island-hopping from Kota Kinabalu.',
                'description' => 'A relaxed package with boat transfers, guided snorkeling, lunch, and resort transfer support.',
                'duration' => '2 days 1 night',
                'price_myr' => 640,
                'capacity' => 12,
                'is_featured' => true,
                'is_top_choice' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
