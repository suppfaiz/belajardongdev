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
        // 1. Provinces
        Schema::create('indonesia_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Cities / Regencies (Kota & Kabupaten)
        Schema::create('indonesia_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('indonesia_provinces')->cascadeOnDelete();
            $table->string('type', 20)->default('Kota'); // Kota / Kabupaten
            $table->string('name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->index(['province_id', 'name']);
        });

        // 3. Districts (Kecamatan)
        Schema::create('indonesia_districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('indonesia_cities')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->index(['city_id', 'name']);
        });

        // 4. Villages (Kelurahan / Desa)
        Schema::create('indonesia_villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('indonesia_districts')->cascadeOnDelete();
            $table->string('name');
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->index(['district_id', 'name']);
        });

        // Add linkage columns to teacher_profiles
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('service_village')->nullable()->after('service_district');
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
        });

        // Add linkage columns to students
        Schema::table('students', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('village')->nullable()->after('district');
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
        });

        // Add linkage columns to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['city_id', 'district_id', 'village_id']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['city', 'district', 'village', 'city_id', 'district_id', 'village_id']);
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['service_village', 'city_id', 'district_id', 'village_id']);
        });

        Schema::dropIfExists('indonesia_villages');
        Schema::dropIfExists('indonesia_districts');
        Schema::dropIfExists('indonesia_cities');
        Schema::dropIfExists('indonesia_provinces');
    }
};
