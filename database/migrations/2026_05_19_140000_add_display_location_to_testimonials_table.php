<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('display_location')->default('landing')->after('is_featured');
            $table->foreignId('product_id')->nullable()->after('display_location')->constrained()->nullOnDelete();
        });

        DB::table('testimonials')->update([
            'display_location' => 'landing',
            'product_id' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn('display_location');
        });
    }
};
