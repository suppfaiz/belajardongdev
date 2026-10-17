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
            // Milestone 1: Identitas KTP WNI
            $table->string('nik', 16)->nullable()->after('rejection_reason');
            $table->string('full_name_ktp', 150)->nullable()->after('nik');
            $table->string('birth_place', 100)->nullable()->after('full_name_ktp');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('gender', 20)->nullable()->after('birth_date');
            $table->text('ktp_address')->nullable()->after('gender');

            // Milestone 2: Kualifikasi Akademik & Status Guru
            $table->string('degree_level', 20)->nullable()->after('ktp_address');
            $table->string('university_name', 150)->nullable()->after('degree_level');
            $table->string('major', 100)->nullable()->after('university_name');
            $table->string('diploma_number', 100)->nullable()->after('major');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('diploma_number');
            $table->decimal('gpa', 3, 2)->nullable()->after('graduation_year');
            $table->string('teaching_status', 50)->nullable()->after('gpa');
            $table->string('school_origin', 150)->nullable()->after('teaching_status');
            $table->string('sk_guru_number', 100)->nullable()->after('school_origin');

            // Milestone 3: SKCK Kepolisian
            $table->string('skck_number', 100)->nullable()->after('sk_guru_number');
            $table->string('skck_issuer', 100)->nullable()->after('skck_number');
            $table->date('skck_valid_until')->nullable()->after('skck_issuer');

            // Milestone 4: Pakta Perlindungan Anak Signature Name
            $table->string('child_protection_signature_name', 150)->nullable()->after('child_protection_pledged_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'full_name_ktp',
                'birth_place',
                'birth_date',
                'gender',
                'ktp_address',
                'degree_level',
                'university_name',
                'major',
                'diploma_number',
                'graduation_year',
                'gpa',
                'teaching_status',
                'school_origin',
                'sk_guru_number',
                'skck_number',
                'skck_issuer',
                'skck_valid_until',
                'child_protection_signature_name',
            ]);
        });
    }
};
