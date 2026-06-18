<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'pickup_location') || ! Schema::hasColumn('products', 'dropoff_location')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'pickup_location')) {
                    $table->string('pickup_location')->nullable()->after('location');
                }

                if (! Schema::hasColumn('products', 'dropoff_location')) {
                    $table->string('dropoff_location')->nullable()->after('pickup_location');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'pickup_location') || Schema::hasColumn('products', 'dropoff_location')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = array_values(array_filter([
                    Schema::hasColumn('products', 'pickup_location') ? 'pickup_location' : null,
                    Schema::hasColumn('products', 'dropoff_location') ? 'dropoff_location' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
