<?php

namespace Tests\Feature;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Booking\BookingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingAndScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent;

    protected User $teacher;

    protected TeacherProfile $teacherProfile;

    protected Student $student;

    protected Subject $subject;

    protected BookingService $bookingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = app(BookingService::class);

        // Create Parent
        $this->parent = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'parent',
        ]);

        // Create Student for Parent
        $this->student = Student::create([
            'parent_id' => $this->parent->id,
            'name' => 'Ananda Putri',
            'grade_level' => 'smp',
            'school_name' => 'SMP Negeri 1 Jakarta',
            'notes' => 'Butuh penguatan materi Aljabar',
            'address' => 'Jl. Tebet Barat No. 10, Jakarta Selatan',
            'latitude' => -6.2372,
            'longitude' => 106.8524,
            'is_active' => true,
        ]);

        // Create Teacher
        $this->teacher = User::factory()->create([
            'name' => 'Siti Rahmawati, S.Pd',
            'role' => 'teacher',
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Guru Matematika Teladan',
            'bio' => 'Lulusan Pendidikan Matematika UNJ',
            'education' => 'S1 UNJ',
            'experience_years' => 4,
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'latitude' => -6.2372,
            'longitude' => 106.8524,
            'service_city' => 'Jakarta Selatan',
            'service_address' => 'Tebet',
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        // Add availability slot for Tuesday (day 2) 14:00 - 18:00
        AvailabilitySlot::create([
            'teacher_profile_id' => $this->teacherProfile->id,
            'day_of_week' => 2, // Tuesday
            'start_time' => '14:00:00',
            'end_time' => '18:00:00',
            'is_active' => true,
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika SMP',
            'slug' => 'matematika-smp',
            'category' => 'smp',
            'icon' => 'calculator',
            'is_active' => true,
        ]);
    }

    public function test_parent_can_create_booking_successfully(): void
    {
        // Pick next Tuesday
        $nextTuesday = Carbon::now()->next(Carbon::TUESDAY)->format('Y-m-d');

        $response = $this->actingAs($this->parent)->post(route('bookings.store', $this->teacherProfile), [
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => $nextTuesday,
            'start_time' => '15:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat No. 10, Jakarta Selatan',
            'lesson_latitude' => -6.2372,
            'lesson_longitude' => 106.8524,
            'notes' => 'Tatap muka pertama',
        ]);

        $response->assertSessionHasNoErrors();

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals($this->parent->id, $booking->parent_id);
        $this->assertEquals($this->teacher->id, $booking->teacher_id);
        $this->assertEquals('pending_teacher', $booking->status);
        $this->assertEquals(90, $booking->session_duration_minutes);
        $this->assertEquals(150000, $booking->session_fee); // 100,000 * 1.5 = 150,000
        $this->assertEquals(15000, $booking->platform_commission_amount); // 10% = 15,000
        $this->assertEquals(135000, $booking->teacher_net_amount); // 90% = 135,000

        $response->assertRedirect(route('parent.bookings.show', $booking->id));
    }

    public function test_double_booking_prevention_rejects_overlapping_booking(): void
    {
        $nextTuesday = Carbon::now()->next(Carbon::TUESDAY)->format('Y-m-d');

        // First booking: 15:00 - 16:30
        $this->bookingService->createBooking($this->parent, [
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => $nextTuesday,
            'start_time' => '15:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat No. 10',
        ]);

        // Second booking attempt on overlapping time: 15:30 - 17:00
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Jadwal yang dipilih sudah terisi oleh pemesanan lain atau di luar jam kerja guru.');

        $this->bookingService->createBooking($this->parent, [
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => $nextTuesday,
            'start_time' => '15:30',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat No. 10',
        ]);
    }

    public function test_teacher_can_accept_and_reject_booking(): void
    {
        $nextTuesday = Carbon::now()->next(Carbon::TUESDAY)->format('Y-m-d');

        $booking = $this->bookingService->createBooking($this->parent, [
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => $nextTuesday,
            'start_time' => '15:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat No. 10',
        ]);

        $this->assertEquals('pending_teacher', $booking->status);

        // Teacher accepts
        $this->bookingService->acceptBooking($booking, $this->teacher);
        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);

        // Create another booking and teacher rejects
        $booking2 = $this->bookingService->createBooking($this->parent, [
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => $nextTuesday,
            'start_time' => '16:30',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat No. 10',
        ]);

        $this->bookingService->rejectBooking($booking2, $this->teacher, 'Jadwal berhalangan karena ada pelatihan sertifikasi');
        $booking2->refresh();
        $this->assertEquals('rejected', $booking2->status);
        $this->assertEquals('Jadwal berhalangan karena ada pelatihan sertifikasi', $booking2->cancellation_reason);
    }
}
