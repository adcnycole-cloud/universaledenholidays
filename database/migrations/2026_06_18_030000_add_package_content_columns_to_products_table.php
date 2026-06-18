<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'tour_highlights')) {
                $table->json('tour_highlights')->nullable()->after('service_inclusions');
            }

            if (! Schema::hasColumn('products', 'package_details')) {
                $table->json('package_details')->nullable()->after('tour_highlights');
            }

            if (! Schema::hasColumn('products', 'recommended_attire')) {
                $table->json('recommended_attire')->nullable()->after('package_details');
            }

            if (! Schema::hasColumn('products', 'things_to_know')) {
                $table->json('things_to_know')->nullable()->after('recommended_attire');
            }

            if (! Schema::hasColumn('products', 'travel_tips')) {
                $table->json('travel_tips')->nullable()->after('things_to_know');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('products', 'tour_highlights') ? 'tour_highlights' : null,
                Schema::hasColumn('products', 'package_details') ? 'package_details' : null,
                Schema::hasColumn('products', 'recommended_attire') ? 'recommended_attire' : null,
                Schema::hasColumn('products', 'things_to_know') ? 'things_to_know' : null,
                Schema::hasColumn('products', 'travel_tips') ? 'travel_tips' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
