<?php

namespace Tests\Feature;

use App\Models\Booking;
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

class BookingPlanTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent;

    protected User $teacher;

    protected TeacherProfile $teacherProfile;

    protected Student $student;

    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $province = Province::create(['code' => '31', 'name' => 'DKI Jakarta']);
        $city = City::create(['province_id' => $province->id, 'type' => 'Kota', 'name' => 'Jakarta Selatan', 'latitude' => -6.2615, 'longitude' => 106.8106]);
        $district = District::create(['city_id' => $city->id, 'name' => 'Kebayoran Baru', 'latitude' => -6.2442, 'longitude' => 106.8006]);
        $village = Village::create(['district_id' => $district->id, 'name' => 'Melawai', 'latitude' => -6.2442, 'longitude' => 106.8006]);

        $this->parent = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.parent@example.com',
            'password' => bcrypt('password'),
            'role' => 'parent',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $this->teacher = User::create([
            'name' => 'Siti Guru S.Pd',
            'email' => 'siti.guru@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'phone' => '081987654321',
            'is_active' => true,
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Guru Matematika Berpengalaman',
            'bio' => 'Mengajar matematika dengan pendekatan ramah.',
            'education' => 'S1 Pendidikan Matematika UNJ',
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'is_verified' => true,
            'verification_status' => 'verified',
            'service_city' => 'Jakarta Selatan',
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'max_travel_distance_km' => 15,
            'city_id' => $city->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
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

        $this->student = Student::create([
            'parent_id' => $this->parent->id,
            'name' => 'Dimas Pratama',
            'grade_level' => 'smp',
            'class_grade' => 'Kelas 7',
            'address' => 'Jl. Melati No. 10, Jakarta Selatan',
            'city_id' => $city->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'is_active' => true,
        ]);
    }

    public function test_parent_can_view_booking_wizard_with_plan_options(): void
    {
        $response = $this->actingAs($this->parent)
            ->get(route('bookings.create', $this->teacherProfile->id));

        $response->assertStatus(200);
        $response->assertSee('Pilih Paket Bimbingan Belajar');
        $response->assertSee('Harian (1 Sesi)');
        $response->assertSee('Paket 1 Bulan');
        $response->assertSee('Paket 6 Bulan (Semester)');
        $response->assertSee('Tentukan Hari Les Rutin di Setiap Minggu');
    }

    public function test_parent_can_create_daily_booking_plan(): void
    {
        $startDate = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($this->parent)
            ->post(route('bookings.store', $this->teacherProfile->id), [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'plan_type' => 'daily',
                'booking_date' => $startDate,
                'start_time' => '10:00',
                'session_duration_minutes' => 90,
                'lesson_address' => 'Jl. Melati No. 10, Jakarta Selatan',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'parent_id' => $this->parent->id,
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'plan_type' => 'daily',
            'total_sessions' => 1,
            'discount_amount' => 0,
            'status' => 'pending_teacher',
        ]);
    }

    public function test_parent_can_create_monthly_booking_plan_with_custom_days(): void
    {
        $startDate = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($this->parent)
            ->post(route('bookings.store', $this->teacherProfile->id), [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'plan_type' => 'monthly',
                'selected_days' => [1, 4], // Monday and Thursday
                'booking_date' => $startDate,
                'start_time' => '10:00',
                'session_duration_minutes' => 90,
                'lesson_address' => 'Jl. Melati No. 10, Jakarta Selatan',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'parent_id' => $this->parent->id,
            'teacher_id' => $this->teacher->id,
            'plan_type' => 'monthly',
            'total_sessions' => 8, // 2 days x 4 weeks
            'discount_percent' => 5.0,
            'status' => 'pending_teacher',
        ]);
    }

    public function test_parent_can_create_six_months_booking_plan_with_custom_days(): void
    {
        $startDate = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($this->parent)
            ->post(route('bookings.store', $this->teacherProfile->id), [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'plan_type' => 'six_months',
                'selected_days' => [2, 5], // Tuesday and Friday
                'booking_date' => $startDate,
                'start_time' => '14:00',
                'session_duration_minutes' => 90,
                'lesson_address' => 'Jl. Melati No. 10, Jakarta Selatan',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'parent_id' => $this->parent->id,
            'teacher_id' => $this->teacher->id,
            'plan_type' => 'six_months',
            'total_sessions' => 48, // 2 days x 24 weeks
            'discount_percent' => 15.0,
            'status' => 'pending_teacher',
        ]);
    }

    public function test_booking_detail_page_displays_plan_label_and_discount(): void
    {
        $startDate = Carbon::tomorrow()->format('Y-m-d');

        $this->actingAs($this->parent)
            ->post(route('bookings.store', $this->teacherProfile->id), [
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'plan_type' => 'monthly',
                'selected_days' => [1, 4],
                'booking_date' => $startDate,
                'start_time' => '10:00',
                'session_duration_minutes' => 90,
                'lesson_address' => 'Jl. Melati No. 10, Jakarta Selatan',
            ]);

        $booking = Booking::latest('id')->first();

        $response = $this->actingAs($this->parent)
            ->get(route('parent.bookings.show', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('Paket 1 Bulan');
        $response->assertSee('8 Pertemuan');
        $response->assertSee('Diskon Paket (5%)');
    }
}
