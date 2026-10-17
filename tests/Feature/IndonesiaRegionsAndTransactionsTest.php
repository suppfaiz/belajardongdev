<?php

namespace Tests\Feature;

use App\Models\AvailabilitySlot;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndonesiaRegionsAndTransactionsTest extends TestCase
{
    use RefreshDatabase;

    protected Province $province;

    protected City $city;

    protected District $district;

    protected Village $village;

    protected function setUp(): void
    {
        parent::setUp();

        $this->province = Province::create([
            'code' => '31',
            'name' => 'DKI Jakarta',
        ]);

        $this->city = City::create([
            'province_id' => $this->province->id,
            'type' => 'Kota',
            'name' => 'Jakarta Selatan',
            'latitude' => -6.2615,
            'longitude' => 106.8106,
        ]);

        $this->district = District::create([
            'city_id' => $this->city->id,
            'name' => 'Kebayoran Baru',
            'latitude' => -6.2442,
            'longitude' => 106.8006,
        ]);

        $this->village = Village::create([
            'district_id' => $this->district->id,
            'name' => 'Melawai',
            'postal_code' => '12160',
            'latitude' => -6.2440,
            'longitude' => 106.7990,
        ]);
    }

    public function test_api_can_fetch_cities_districts_and_villages(): void
    {
        // 1. Fetch cities
        $response = $this->getJson('/api/regions/cities');
        $response->assertOk()
            ->assertJsonFragment(['name' => 'Jakarta Selatan']);

        // 2. Fetch districts for city
        $response = $this->getJson('/api/regions/districts?city_id='.$this->city->id);
        $response->assertOk()
            ->assertJsonFragment(['name' => 'Kebayoran Baru']);

        // 3. Fetch villages for district
        $response = $this->getJson('/api/regions/villages?district_id='.$this->district->id);
        $response->assertOk()
            ->assertJsonFragment(['name' => 'Melawai', 'postal_code' => '12160']);
    }

    public function test_teacher_can_update_profile_with_region_hierarchy(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $profile = TeacherProfile::create([
            'user_id' => $teacher->id,
            'headline' => 'Guru Fisika',
            'bio' => 'Bio pengajar berpengalaman mengajar siswa.',
            'education' => 'S1 Fisika',
            'experience_years' => 3,
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Tebet',
            'service_address' => 'Jl. Tebet No. 1',
            'latitude' => -6.23,
            'longitude' => 106.85,
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($teacher)->put(route('teacher.profile.update'), [
            'name' => 'Budi Guru',
            'phone' => '081234567890',
            'headline' => 'Guru Matematika & Fisika Unggulan',
            'bio' => 'Bio pengajar diperbarui dengan lengkap.',
            'education' => 'S1 Pendidikan Matematika UNJ',
            'experience_years' => 4,
            'hourly_rate' => 110000,
            'session_duration_minutes' => 90,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'service_address' => 'Jl. Panglima Polim No. 99',
            'latitude' => -6.2440,
            'longitude' => 106.7990,
            'max_travel_distance_km' => 15,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('teacher_profiles', [
            'id' => $profile->id,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Kebayoran Baru',
            'service_village' => 'Melawai',
        ]);
    }

    public function test_parent_can_store_student_with_hierarchical_regions(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);

        $response = $this->actingAs($parent)->post(route('parent.students.store'), [
            'name' => 'Citra Kirana',
            'grade_level' => 'smp',
            'class_grade' => 'Kelas 8',
            'school_name' => 'SMP Negeri 19 Jakarta',
            'notes' => 'Perlu pendampingan belajar geometri',
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'address' => 'Jl. Wijaya No. 10',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'parent_id' => $parent->id,
            'name' => 'Citra Kirana',
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'village' => 'Melawai',
        ]);
    }

    public function test_booking_stores_hierarchical_regions_and_persists_transactionally(): void
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);
        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'headline' => 'Guru Kimia',
            'bio' => 'Bio pengajar kimia SMA.',
            'education' => 'S1 Kimia UI',
            'experience_years' => 5,
            'hourly_rate' => 120000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Kebayoran Baru',
            'service_village' => 'Melawai',
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'service_address' => 'Jl. Melawai No. 5',
            'latitude' => -6.2440,
            'longitude' => 106.7990,
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $subject = Subject::create([
            'name' => 'Kimia SMA',
            'slug' => 'kimia-sma',
            'category' => 'sma',
            'icon' => 'flask',
            'is_active' => true,
        ]);

        $targetDate = Carbon::now()->next(Carbon::TUESDAY)->toDateString();

        AvailabilitySlot::create([
            'teacher_profile_id' => $teacherProfile->id,
            'day_of_week' => 2, // Tuesday
            'start_time' => '14:00:00',
            'end_time' => '19:00:00',
            'is_active' => true,
        ]);

        $teacherProfile->subjects()->attach($subject->id, [
            'grade_levels' => json_encode(['sma']),
            'custom_hourly_rate' => 120000,
        ]);

        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::create([
            'parent_id' => $parent->id,
            'name' => 'Rian Pratama',
            'grade_level' => 'sma',
            'class_grade' => 'Kelas 11',
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'latitude' => -6.2445,
            'longitude' => 106.7995,
            'address' => 'Jl. Iskandarsyah No. 12',
            'is_active' => true,
        ]);

        $response = $this->actingAs($parent)->post(route('bookings.store', $teacherProfile->id), [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'booking_date' => $targetDate,
            'start_time' => '16:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Iskandarsyah No. 12, Kebayoran Baru',
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'lesson_latitude' => -6.2445,
            'lesson_longitude' => 106.7995,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'parent_id' => $parent->id,
            'teacher_id' => $teacherUser->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
            'village_id' => $this->village->id,
            'status' => 'pending_teacher',
            'hourly_rate_snapshot' => 120000,
            'session_fee' => 180000, // 90 min = 1.5 * 120k
            'teacher_net_amount' => 162000, // 90%
            'platform_commission_amount' => 18000, // 10%
        ]);
    }
}
