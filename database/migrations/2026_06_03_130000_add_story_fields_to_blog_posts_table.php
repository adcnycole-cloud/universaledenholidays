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
            if (! Schema::hasColumn('blog_posts', 'destination')) {
                $table->string('destination')->nullable()->after('title');
            }

            if (! Schema::hasColumn('blog_posts', 'author_name')) {
                $table->string('author_name')->nullable()->after('destination');
            }

            if (! Schema::hasColumn('blog_posts', 'sections')) {
                $table->json('sections')->nullable()->after('credits');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            foreach (['sections', 'author_name', 'destination'] as $column) {
                if (Schema::hasColumn('blog_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
