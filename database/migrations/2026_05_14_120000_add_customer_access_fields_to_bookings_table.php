<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status')->default('not_required')->after('status');
            $table->string('account_setup_token')->nullable()->after('payment_status');
            $table->timestamp('account_setup_expires_at')->nullable()->after('account_setup_token');
            $table->timestamp('account_setup_completed_at')->nullable()->after('account_setup_expires_at');
            $table->timestamp('payment_submitted_at')->nullable()->after('account_setup_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'account_setup_token',
                'account_setup_expires_at',
                'account_setup_completed_at',
                'payment_submitted_at',
            ]);
        });
    }
};
