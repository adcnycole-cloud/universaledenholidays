<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_discounted')->default(false)->after('is_top_choice');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('is_discounted');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_discounted', 'discount_percentage']);
        });
    }
};
