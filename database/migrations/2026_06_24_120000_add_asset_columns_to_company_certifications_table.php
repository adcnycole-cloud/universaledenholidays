<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_certifications', function (Blueprint $table) {
            if (! Schema::hasColumn('company_certifications', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('description');
            }

            if (! Schema::hasColumn('company_certifications', 'certificate_path')) {
                $table->string('certificate_path')->nullable()->after('logo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_certifications', function (Blueprint $table) {
            if (Schema::hasColumn('company_certifications', 'certificate_path')) {
                $table->dropColumn('certificate_path');
            }

            if (Schema::hasColumn('company_certifications', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
