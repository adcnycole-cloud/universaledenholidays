<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->boolean('show_in_customer_gallery')->default(false)->after('is_featured');
            $table->string('gallery_title')->nullable()->after('show_in_customer_gallery');
            $table->text('gallery_description')->nullable()->after('gallery_title');
            $table->string('gallery_image_path')->nullable()->after('gallery_description');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn([
                'show_in_customer_gallery',
                'gallery_title',
                'gallery_description',
                'gallery_image_path',
            ]);
        });
    }
};
