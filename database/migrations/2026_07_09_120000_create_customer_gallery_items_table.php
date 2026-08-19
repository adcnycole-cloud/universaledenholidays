<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_path');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('testimonials')) {
            $existingItems = DB::table('testimonials')
                ->where('show_in_customer_gallery', true)
                ->whereNotNull('gallery_image_path')
                ->where('gallery_image_path', '!=', '')
                ->orderByDesc('id')
                ->get([
                    'gallery_title',
                    'gallery_description',
                    'gallery_image_path',
                    'trip_name',
                    'name',
                    'quote',
                    'created_at',
                    'updated_at',
                ]);

            foreach ($existingItems as $index => $item) {
                DB::table('customer_gallery_items')->insert([
                    'title' => trim((string) ($item->gallery_title ?: $item->trip_name ?: $item->name)),
                    'description' => trim((string) ($item->gallery_description ?: $item->quote)),
                    'image_path' => (string) $item->gallery_image_path,
                    'is_active' => true,
                    'sort_order' => $index,
                    'created_at' => $item->created_at ?? now(),
                    'updated_at' => $item->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_gallery_items');
    }
};
