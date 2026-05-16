<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('trip_name');
            $table->text('quote');
            $table->unsignedTinyInteger('rating');
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        DB::table('testimonials')->insert([
            [
                'name' => 'Ji-eun Park',
                'location' => 'Seoul, South Korea',
                'trip_name' => 'Kundasang Day Tour',
                'quote' => 'The transport was smooth, the mountain scenery was beautiful, and the team made everything easy from booking to drop-off.',
                'rating' => 5,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amir Hakim',
                'location' => 'Kuala Lumpur, Malaysia',
                'trip_name' => 'Island Escape Package',
                'quote' => 'Perfect for a short getaway. The booking process felt clear, the staff responded quickly, and the island time was excellent.',
                'rating' => 5,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rachel Tan',
                'location' => 'Singapore',
                'trip_name' => 'Kinabatangan River Wildlife Cruise',
                'quote' => 'We saw wildlife, stayed on schedule, and appreciated having payment and transport options in one place.',
                'rating' => 4,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
