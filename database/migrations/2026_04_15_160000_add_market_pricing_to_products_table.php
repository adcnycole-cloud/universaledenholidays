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
            $table->decimal('malaysia_adult_price_myr', 10, 2)->default(0)->after('price_myr');
            $table->decimal('malaysia_child_price_myr', 10, 2)->default(0)->after('malaysia_adult_price_myr');
            $table->decimal('international_adult_price_myr', 10, 2)->default(0)->after('malaysia_child_price_myr');
            $table->decimal('international_child_price_myr', 10, 2)->default(0)->after('international_adult_price_myr');
        });

        DB::statement('
            UPDATE products
            SET
                malaysia_adult_price_myr = price_myr,
                malaysia_child_price_myr = ROUND(price_myr * 0.8, 2),
                international_adult_price_myr = ROUND(price_myr * 1.3, 2),
                international_child_price_myr = ROUND(price_myr * 0.95, 2)
        ');
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'malaysia_adult_price_myr',
                'malaysia_child_price_myr',
                'international_adult_price_myr',
                'international_child_price_myr',
            ]);
        });
    }
};
