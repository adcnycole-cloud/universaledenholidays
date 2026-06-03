<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_posts', 'social_media_url')) {
                $table->string('social_media_url')->nullable()->after('cover_image_path');
            }

            if (! Schema::hasColumn('blog_posts', 'video_url')) {
                $table->string('video_url')->nullable()->after('social_media_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            if (Schema::hasColumn('blog_posts', 'video_url')) {
                $table->dropColumn('video_url');
            }

            if (Schema::hasColumn('blog_posts', 'social_media_url')) {
                $table->dropColumn('social_media_url');
            }
        });
    }
};
