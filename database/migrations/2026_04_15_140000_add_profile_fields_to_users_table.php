<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('password');
            $table->string('phone')->nullable()->after('email');
            $table->string('preferred_currency', 3)->default('MYR')->after('role');
        });

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@universaledenholiday.com'],
            [
                'name' => 'Universal Eden Admin',
                'phone' => '+6088-000-000',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'preferred_currency' => 'MYR',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'preferred_currency']);
        });
    }
};
