<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTeacherSearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent;

    protected User $teacher;

    protected TeacherProfile $teacherProfile;

    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parent = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@test.com',
            'role' => 'parent',
        ]);

        $this->teacher = User::factory()->create([
            'name' => 'Siti Rahmawati, S.Pd',
            'email' => 'siti@test.com',
            'role' => 'teacher',
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Guru Matematika SMP & SMA Berpengalaman',
            'bio' => 'Lulusan Pendidikan Matematika UNJ',
            'education' => 'S1 Pendidikan Matematika UNJ',
            'experience_years' => 4,
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'is_verified' => true,
            'verification_status' => 'verified',
            'rating_cache' => 4.90,
            'reviews_count_cache' => 25,
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika',
            'slug' => 'matematika',
            'category' => 'smp',
            'icon' => 'calculator',
            'is_active' => true,
        ]);

        $this->teacherProfile->subjects()->attach($this->subject->id, [
            'grade_levels' => json_encode(['smp', 'sma']),
            'custom_hourly_rate' => 100000,
        ]);
    }

    public function test_guest_accesses_cari_guru_with_public_app_layout(): void
    {
        $response = $this->get(route('teachers.search'));

        $response->assertStatus(200);
        $response->assertSee('Temukan Guru Les Terbaik');
        // Guest does not have the dashboard sidebar
        $response->assertDontSee('<!-- Desktop Persistent Left Sidebar -->', false);
    }

    public function test_parent_accesses_parent_cari_guru_with_dashboard_layout(): void
    {
        $response = $this->actingAs($this->parent)
            ->get(route('parent.teachers.search'));

        $response->assertStatus(200);
        $response->assertSee('Temukan Guru Les Terbaik');
        // Parent in dashboard has the persistent dashboard sidebar
        $response->assertSee('<!-- Desktop Persistent Left Sidebar -->', false);
        $response->assertSee('Keluar');
        $response->assertSee('Cari Guru');
    }

    public function test_parent_accesses_generic_cari_guru_with_dashboard_layout(): void
    {
        $response = $this->actingAs($this->parent)
            ->get(route('teachers.search'));

        $response->assertStatus(200);
        // Authenticated parent is kept within the dashboard shell
        $response->assertSee('<!-- Desktop Persistent Left Sidebar -->', false);
        $response->assertSee('Keluar');
    }

    public function test_parent_accesses_teacher_profile_from_dashboard_with_dashboard_layout(): void
    {
        $response = $this->actingAs($this->parent)
            ->get(route('parent.teachers.show', $this->teacherProfile));

        $response->assertStatus(200);
        $response->assertSee($this->teacher->name);
        $response->assertSee('Kembali ke Pencarian Guru');
        // Kept within dashboard shell
        $response->assertSee('<!-- Desktop Persistent Left Sidebar -->', false);
    }

    public function test_parent_dashboard_contains_quick_search_and_popular_subjects(): void
    {
        $response = $this->actingAs($this->parent)
            ->get(route('parent.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Cari guru atau mata pelajaran...');
        $response->assertSee(route('parent.teachers.search'));
        $response->assertSee('Matematika');
    }

    public function test_search_teachers_by_keyword_matches_teacher_name(): void
    {
        $response = $this->get(route('teachers.search', ['q' => 'Siti']));

        $response->assertStatus(200);
        $response->assertSee($this->teacher->name);
    }

    public function test_search_teachers_by_keyword_matches_subject_name(): void
    {
        $response = $this->get(route('teachers.search', ['q' => 'Matematika']));

        $response->assertStatus(200);
        $response->assertSee($this->teacher->name);
    }

    public function test_search_teachers_by_keyword_excludes_non_matching_teachers(): void
    {
        $response = $this->get(route('teachers.search', ['q' => 'BiologiMolekulerSuperUnikXYZ']));

        $response->assertStatus(200);
        $response->assertDontSee($this->teacher->name);
    }
}
