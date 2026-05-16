<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedTinyInteger('malaysian_adults')->default(0)->after('package_name');
            $table->unsignedTinyInteger('malaysian_kids')->default(0)->after('malaysian_adults');
            $table->unsignedTinyInteger('international_adults')->default(0)->after('malaysian_kids');
            $table->unsignedTinyInteger('international_kids')->default(0)->after('international_adults');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'malaysian_adults',
                'malaysian_kids',
                'international_adults',
                'international_kids',
            ]);
        });
    }
};
