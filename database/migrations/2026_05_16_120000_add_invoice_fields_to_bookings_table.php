<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('payment_submitted_at');
            $table->string('invoice_number')->nullable()->unique()->after('confirmed_at');
            $table->timestamp('invoice_issued_at')->nullable()->after('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['invoice_number']);
            $table->dropColumn([
                'confirmed_at',
                'invoice_number',
                'invoice_issued_at',
            ]);
        });
    }
};
