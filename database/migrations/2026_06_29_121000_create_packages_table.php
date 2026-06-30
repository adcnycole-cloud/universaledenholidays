<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('packages');

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tour_code')->nullable();
            $table->string('location')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->string('group_size_label')->nullable();
            $table->string('minimum_age')->nullable();
            $table->decimal('price_myr', 10, 2)->nullable();
            $table->decimal('malaysia_adult_price_myr', 10, 2)->nullable();
            $table->decimal('malaysia_child_price_myr', 10, 2)->nullable();
            $table->decimal('international_adult_price_myr', 10, 2)->nullable();
            $table->decimal('international_child_price_myr', 10, 2)->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->string('image_url')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('pricing_tiers')->nullable();
            $table->json('itinerary_items')->nullable();
            $table->json('service_inclusions')->nullable();
            $table->json('tour_highlights')->nullable();
            $table->json('package_details')->nullable();
            $table->json('recommended_attire')->nullable();
            $table->json('things_to_know')->nullable();
            $table->json('travel_tips')->nullable();
            $table->json('optional_activities')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_top_choice')->default(false);
            $table->boolean('is_discounted')->default(false);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('products')) {
            $packages = DB::table('products')
                ->where('category', 'package')
                ->get();

            foreach ($packages as $package) {
                DB::table('packages')->insert([
                    'name' => $package->name,
                    'tour_code' => $package->tour_code ?? null,
                    'location' => $package->location ?? null,
                    'summary' => $package->summary ?? null,
                    'description' => $package->description ?? null,
                    'duration' => $package->duration ?? null,
                    'departure_time' => $package->departure_time ?? null,
                    'pickup_location' => $package->pickup_location ?? null,
                    'dropoff_location' => $package->dropoff_location ?? null,
                    'group_size_label' => $package->group_size_label ?? null,
                    'minimum_age' => $package->minimum_age ?? null,
                    'price_myr' => $package->price_myr ?? null,
                    'malaysia_adult_price_myr' => $package->malaysia_adult_price_myr ?? null,
                    'malaysia_child_price_myr' => $package->malaysia_child_price_myr ?? null,
                    'international_adult_price_myr' => $package->international_adult_price_myr ?? null,
                    'international_child_price_myr' => $package->international_child_price_myr ?? null,
                    'capacity' => $package->capacity ?? null,
                    'image_url' => $package->image_url ?? null,
                    'gallery_images' => $package->gallery_images ?? null,
                    'pricing_tiers' => $package->pricing_tiers ?? null,
                    'itinerary_items' => $package->itinerary_items ?? null,
                    'service_inclusions' => $package->service_inclusions ?? null,
                    'tour_highlights' => $package->tour_highlights ?? null,
                    'package_details' => $package->package_details ?? null,
                    'recommended_attire' => $package->recommended_attire ?? null,
                    'things_to_know' => $package->things_to_know ?? null,
                    'travel_tips' => $package->travel_tips ?? null,
                    'optional_activities' => $package->optional_activities ?? null,
                    'is_featured' => (bool) ($package->is_featured ?? false),
                    'is_top_choice' => (bool) ($package->is_top_choice ?? false),
                    'is_discounted' => (bool) ($package->is_discounted ?? false),
                    'discount_percentage' => $package->discount_percentage ?? null,
                    'is_active' => (bool) ($package->is_active ?? true),
                    'created_at' => $package->created_at ?? now(),
                    'updated_at' => $package->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
