<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('booking_reference')->nullable()->after('product_id');
            $table->string('service_type')->default('package')->after('phone');
            $table->string('payment_method')->default('bank_transfer')->after('special_requests');
            $table->string('currency_code', 3)->default('MYR')->after('payment_method');
            $table->decimal('amount_myr', 10, 2)->default(0)->after('currency_code');
            $table->decimal('amount_display', 12, 2)->default(0)->after('amount_myr');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn([
                'booking_reference',
                'service_type',
                'payment_method',
                'currency_code',
                'amount_myr',
                'amount_display',
            ]);
        });
    }
};
