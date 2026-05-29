<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'payment_reminder_sent_at')) {
                $table->timestamp('payment_reminder_sent_at')->nullable()->after('payment_submitted_at');
            }

            if (! Schema::hasColumn('bookings', 'trip_reminder_sent_at')) {
                $table->timestamp('trip_reminder_sent_at')->nullable()->after('payment_reminder_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('bookings', 'payment_reminder_sent_at')) {
                $columnsToDrop[] = 'payment_reminder_sent_at';
            }

            if (Schema::hasColumn('bookings', 'trip_reminder_sent_at')) {
                $columnsToDrop[] = 'trip_reminder_sent_at';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
