<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('summary');
        });

        DB::table('products')->where('name', 'Kota Kinabalu Airport Transfer')->update([
            'image_url' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'West Coast Shuttle Pass')->update([
            'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'Sabah Nature Highlights')->update([
            'image_url' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'Island Escape Package')->update([
            'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'Kundasang Day Tour')->update([
            'image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'Mari Mari Cultural Village Tour')->update([
            'image_url' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1200&q=80',
        ]);
        DB::table('products')->where('name', 'Kinabatangan River Wildlife Cruise')->update([
            'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1200&q=80',
        ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};
