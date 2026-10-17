<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeospatialSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_geospatial_search_returns_nearby_verified_teacher(): void
    {
        $subject = Subject::create([
            'name' => 'Matematika',
            'slug' => 'matematika',
            'category' => 'smp',
            'icon' => 'calculator',
            'is_active' => true,
        ]);

        // Teacher 1: Jakarta Selatan (Tebet: -6.2372, 106.8524)
        $teacherUser1 = User::factory()->create(['name' => 'Guru Tebet', 'role' => 'teacher']);
        $profile1 = TeacherProfile::create([
            'user_id' => $teacherUser1->id,
            'headline' => 'Guru Matematika SMP',
            'bio' => 'Pengalaman mengajar 5 tahun',
            'education' => 'S1 Pendidikan Matematika UNJ',
            'experience_years' => 5,
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'latitude' => -6.2372,
            'longitude' => 106.8524,
            'service_city' => 'Jakarta Selatan',
            'service_address' => 'Tebet Timur Dalam',
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
            'rating_cache' => 5.0,
            'reviews_count_cache' => 1,
        ]);
        $profile1->subjects()->attach($subject->id, [
            'grade_levels' => json_encode(['smp']),
            'custom_hourly_rate' => 100000,
        ]);

        // Teacher 2: Bandung (-6.9175, 107.6191) - over 100 km away
        $teacherUser2 = User::factory()->create(['name' => 'Guru Bandung', 'role' => 'teacher']);
        $profile2 = TeacherProfile::create([
            'user_id' => $teacherUser2->id,
            'headline' => 'Guru Bandung',
            'bio' => 'Pengajar Bandung',
            'education' => 'S1 ITB',
            'experience_years' => 3,
            'hourly_rate' => 120000,
            'session_duration_minutes' => 90,
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'service_city' => 'Bandung',
            'service_address' => 'Dago',
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
            'rating_cache' => 4.8,
            'reviews_count_cache' => 1,
        ]);
        $profile2->subjects()->attach($subject->id, [
            'grade_levels' => json_encode(['smp']),
            'custom_hourly_rate' => 120000,
        ]);

        // Search near Tebet (Jakarta Selatan: -6.2372, 106.8524) with 10 km radius
        $response = $this->get(route('teachers.search', [
            'lat' => -6.2372,
            'lon' => 106.8524,
            'radius' => 10,
        ]));

        $response->assertOk();
        $response->assertSee('Guru Tebet');
        $response->assertDontSee('Guru Bandung');
    }

    public function test_unverified_teacher_is_excluded_from_search(): void
    {
        $teacherUser = User::factory()->create(['name' => 'Guru Belum Verifikasi', 'role' => 'teacher']);
        $profile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'headline' => 'Guru Fisika',
            'bio' => 'Belum verifikasi KTP',
            'education' => 'S1 UI',
            'experience_years' => 2,
            'hourly_rate' => 90000,
            'session_duration_minutes' => 90,
            'latitude' => -6.2615,
            'longitude' => 106.8106,
            'service_city' => 'Jakarta Selatan',
            'service_address' => 'Fatmawati',
            'max_travel_distance_km' => 15,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->get(route('teachers.search', [
            'lat' => -6.2615,
            'lon' => 106.8106,
            'radius' => 10,
        ]));

        $response->assertOk();
        $response->assertDontSee('Guru Belum Verifikasi');

        // Detail page of unverified teacher must return 404
        $detailResponse = $this->get(route('teachers.show', $profile));
        $detailResponse->assertNotFound();
    }

    public function test_teacher_max_travel_distance_is_respected(): void
    {
        // Teacher located in Kebayoran Baru (-6.2442, 106.8006), but max travel distance is only 2 km
        $teacherUser = User::factory()->create(['name' => 'Guru Dekat Saja', 'role' => 'teacher']);
        TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'headline' => 'Guru Biologi',
            'bio' => 'Hanya radius 2 km',
            'education' => 'S1 UNJ',
            'experience_years' => 4,
            'hourly_rate' => 95000,
            'session_duration_minutes' => 90,
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'service_city' => 'Jakarta Selatan',
            'service_address' => 'Gandaria',
            'max_travel_distance_km' => 2, // only 2 km travel radius
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        // Parent location is in Tebet (-6.2372, 106.8524), which is ~5.8 km away
        // Parent searches with 10 km radius, so parent's radius includes teacher,
        // but teacher's max_travel_distance_km (2 km) does not cover the parent.
        $response = $this->get(route('teachers.search', [
            'lat' => -6.2372,
            'lon' => 106.8524,
            'radius' => 10,
        ]));

        $response->assertOk();
        $response->assertDontSee('Guru Dekat Saja');
    }

    public function test_search_with_subject_slug_and_grade_filter_works_on_postgresql(): void
    {
        $subject = Subject::create([
            'name' => 'IPA Terpadu',
            'slug' => 'ipa-terpadu',
            'category' => 'smp',
            'icon' => 'atom',
            'is_active' => true,
        ]);

        $teacherUser = User::factory()->create(['name' => 'Guru IPA Teladan', 'role' => 'teacher']);
        $profile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'headline' => 'Guru IPA Berprestasi',
            'hourly_rate' => 110000,
            'latitude' => -6.2615,
            'longitude' => 106.8106,
            'service_city' => 'Jakarta Selatan',
            'max_travel_distance_km' => 20,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $profile->subjects()->attach($subject->id, [
            'grade_levels' => json_encode(['smp']),
            'custom_hourly_rate' => 110000,
        ]);

        // Query with string slug 'ipa-terpadu' and grade 'smp'
        $response = $this->get(route('teachers.search', [
            'lat' => -6.2615,
            'lon' => 106.8106,
            'radius' => 20,
            'subject' => 'ipa-terpadu',
            'grade' => 'smp',
        ]));

        $response->assertOk();
        $response->assertSee('Guru IPA Teladan');
    }
}
