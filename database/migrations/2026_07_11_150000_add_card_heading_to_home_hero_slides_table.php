<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_hero_slides', function (Blueprint $table) {
            $table->string('card_heading')->nullable()->after('image_path');
        });

        DB::table('home_hero_slides')
            ->whereNull('card_heading')
            ->update(['card_heading' => 'Sabah Escape']);
    }

    public function down(): void
    {
        Schema::table('home_hero_slides', function (Blueprint $table) {
            $table->dropColumn('card_heading');
        });
    }
};
