<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_posts', 'description')) {
                $table->longText('description')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('blog_posts', 'credits')) {
                $table->text('credits')->nullable()->after('description');
            }
        });

        DB::table('blog_posts')
            ->whereNull('description')
            ->update([
                'description' => DB::raw('COALESCE(content, excerpt)'),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            if (Schema::hasColumn('blog_posts', 'credits')) {
                $table->dropColumn('credits');
            }

            if (Schema::hasColumn('blog_posts', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
