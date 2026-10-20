<?php

namespace Tests\Feature;

use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherDashboardViewTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;

    protected TeacherProfile $teacherProfile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'name' => 'Ahmad Fauzi, M.Pd',
            'email' => 'ahmad.fauzi@test.com',
            'role' => 'teacher',
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Pakar Fisika & Matematika SMA',
            'bio' => 'Lulusan FMIPA ITB dengan pengalaman 6 tahun mengajar les privat',
            'education' => 'S1 Fisika ITB',
            'experience_years' => 6,
            'hourly_rate' => 150000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'is_verified' => true,
            'verification_status' => 'verified',
            'rating_cache' => 4.95,
            'reviews_count_cache' => 38,
        ]);
    }

    public function test_teacher_can_access_dashboard_and_see_greeting_and_metrics(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Halo, Ahmad Fauzi, M.Pd');
        $response->assertSee('Pakar Fisika & Matematika SMA');
        $response->assertSee('Saldo Tersedia');
        $response->assertSee('Escrow Aktif');
        $response->assertSee('Total Pendapatan');
        $response->assertSee('Sesi Selesai');
        $response->assertSee('Pintasan Menu Guru');
    }

    public function test_teacher_sees_teacher_specific_mobile_bottom_nav(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        // Teacher sees teacher-specific bottom tabs
        $response->assertSee(route('teacher.dashboard'));
        $response->assertSee(route('teacher.schedules'));
        $response->assertSee(route('teacher.messages'));
        $response->assertSee(route('teacher.bookings'));
        $response->assertSee(route('teacher.profile'));
    }

    public function test_unverified_teacher_sees_milestone_verification_tracker(): void
    {
        $unverifiedTeacher = User::factory()->create([
            'name' => 'Budi Santoso, S.Si',
            'email' => 'budi.santoso@test.com',
            'role' => 'teacher',
        ]);

        TeacherProfile::create([
            'user_id' => $unverifiedTeacher->id,
            'headline' => 'Guru Biologi Baru',
            'is_verified' => false,
            'verification_status' => 'pending',
            'hourly_rate' => 100000,
        ]);

        $response = $this->actingAs($unverifiedTeacher)
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Progres Verifikasi Kredensial Guru');
        $response->assertSee('1. KTP WNI');
        $response->assertSee('2. Ijazah S1');
        $response->assertSee('3. SKCK Polisi');
        $response->assertSee('4. Pakta Integritas');
    }

    public function test_teacher_can_access_and_view_profile_settings_page(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.profile'));

        $response->assertStatus(200);
        $response->assertSee('Kredensial Mengajar');
        $response->assertSee('Data Pribadi');
        $response->assertSee('Kualifikasi Akademik');
        $response->assertSee('Area Layanan Offline');
        $response->assertSee('Rekening Bank untuk Pencairan Saldo');
        $response->assertSee('Simpan Seluruh Perubahan');
        $response->assertSee('Pakar Fisika &amp; Matematika SMA', false);
        $response->assertSee('Ahmad Fauzi, M.Pd');
    }
}
