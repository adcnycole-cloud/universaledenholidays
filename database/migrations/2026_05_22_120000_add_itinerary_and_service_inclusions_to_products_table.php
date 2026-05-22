<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('itinerary_items')->nullable()->after('gallery_images');
            $table->json('service_inclusions')->nullable()->after('itinerary_items');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['itinerary_items', 'service_inclusions']);
        });
    }
};
