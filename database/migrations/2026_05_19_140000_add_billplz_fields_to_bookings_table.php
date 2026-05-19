<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_gateway', 30)->nullable()->after('payment_status');
            $table->string('payment_gateway_bill_id')->nullable()->after('payment_gateway');
            $table->string('payment_gateway_url')->nullable()->after('payment_gateway_bill_id');
            $table->string('payment_gateway_status', 50)->nullable()->after('payment_gateway_url');
            $table->timestamp('payment_gateway_paid_at')->nullable()->after('payment_gateway_status');
            $table->json('payment_gateway_payload')->nullable()->after('payment_gateway_paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_gateway_bill_id',
                'payment_gateway_url',
                'payment_gateway_status',
                'payment_gateway_paid_at',
                'payment_gateway_payload',
            ]);
        });
    }
};
