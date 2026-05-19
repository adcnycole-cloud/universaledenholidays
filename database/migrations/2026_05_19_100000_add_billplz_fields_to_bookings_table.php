<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('billplz_bill_id')->nullable()->after('payment_reminder_sent_at');
            $table->text('billplz_bill_url')->nullable()->after('billplz_bill_id');
            $table->string('billplz_state')->nullable()->after('billplz_bill_url');
            $table->boolean('billplz_paid')->default(false)->after('billplz_state');
            $table->timestamp('billplz_paid_at')->nullable()->after('billplz_paid');
            $table->string('billplz_transaction_id')->nullable()->after('billplz_paid_at');
            $table->string('billplz_transaction_status')->nullable()->after('billplz_transaction_id');
            $table->json('billplz_last_payload')->nullable()->after('billplz_transaction_status');

            $table->index('billplz_bill_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['billplz_bill_id']);
            $table->dropColumn([
                'billplz_bill_id',
                'billplz_bill_url',
                'billplz_state',
                'billplz_paid',
                'billplz_paid_at',
                'billplz_transaction_id',
                'billplz_transaction_status',
                'billplz_last_payload',
            ]);
        });
    }
};
