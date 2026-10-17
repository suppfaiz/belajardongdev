<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->timestamp('child_protection_pledged_at')->nullable()->after('rejection_reason');
            $table->boolean('ktp_verified')->default(false)->after('child_protection_pledged_at');
            $table->boolean('pddikti_verified')->default(false)->after('ktp_verified');
            $table->boolean('skck_verified')->default(false)->after('pddikti_verified');
            $table->boolean('sk_guru_verified')->default(false)->after('skck_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'child_protection_pledged_at',
                'ktp_verified',
                'pddikti_verified',
                'skck_verified',
                'sk_guru_verified',
            ]);
        });
    }
};
