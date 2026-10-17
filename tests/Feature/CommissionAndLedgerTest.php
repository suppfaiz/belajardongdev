<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\PlatformSetting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Booking\BookingService;
use App\Services\Ledger\LedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommissionAndLedgerTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent;

    protected User $teacher;

    protected TeacherProfile $teacherProfile;

    protected Student $student;

    protected Subject $subject;

    protected BookingService $bookingService;

    protected LedgerService $ledgerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = app(BookingService::class);
        $this->ledgerService = app(LedgerService::class);

        $this->parent = User::factory()->create([
            'role' => 'parent',
        ]);

        $this->student = Student::create([
            'parent_id' => $this->parent->id,
            'name' => 'Bintang Pratama',
            'grade_level' => 'sd',
            'is_active' => true,
        ]);

        $this->teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Guru SD Berpengalaman',
            'hourly_rate' => 100000,
            'session_duration_minutes' => 60,
            'is_verified' => true,
            'verification_status' => 'verified',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Guru Test',
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika SD',
            'slug' => 'matematika-sd',
            'category' => 'sd',
            'icon' => 'book',
            'is_active' => true,
        ]);
    }

    public function test_pricing_and_platform_commission_snapshot(): void
    {
        // 60 minutes @ 100,000 / hour = 100,000
        $pricing = $this->bookingService->calculatePricing($this->teacherProfile, 60);

        $this->assertEquals(100000, $pricing['session_fee']);
        $this->assertEquals(10.0, $pricing['commission_rate']);
        $this->assertEquals(10000, $pricing['commission_amount']); // 10% of 100k
        $this->assertEquals(90000, $pricing['teacher_net_amount']); // 90% of 100k

        // Change platform setting to 15%
        PlatformSetting::set('commission_rate_percent', '15.00');

        $pricing15 = $this->bookingService->calculatePricing($this->teacherProfile, 60);
        $this->assertEquals(15000, $pricing15['commission_amount']);
        $this->assertEquals(85000, $pricing15['teacher_net_amount']);
    }

    public function test_ledger_records_payment_and_session_completion_accurately(): void
    {
        $booking = Booking::create([
            'uuid' => (string) Str::uuid(),
            'booking_code' => 'BY-TEST-001',
            'parent_id' => $this->parent->id,
            'teacher_id' => $this->teacher->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:30:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Kemang Raya No. 5',
            'status' => 'paid',
            'hourly_rate_snapshot' => 100000,
            'session_fee' => 150000,
            'platform_commission_rate' => 10.0,
            'platform_commission_amount' => 15000,
            'teacher_net_amount' => 135000,
            'total_amount' => 150000,
        ]);

        $payment = Payment::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $booking->id,
            'payment_code' => 'PAY-TEST-001',
            'payment_gateway' => 'mock_pg',
            'payment_method' => 'virtual_account',
            'amount' => 150000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // 1. Record payment received into ledger
        $this->ledgerService->recordPaymentReceived($payment);

        $this->assertDatabaseHas('ledger_entries', [
            'transaction_type' => 'payment_received',
            'direction' => 'credit',
            'amount' => 150000,
            'booking_id' => $booking->id,
        ]);

        $this->assertDatabaseHas('ledger_entries', [
            'transaction_type' => 'platform_commission',
            'direction' => 'credit',
            'amount' => 15000,
            'booking_id' => $booking->id,
        ]);

        // Prior to session completion, teacher balance is 0 (money is held in escrow)
        $this->assertEquals(0, $this->ledgerService->getTeacherBalance($this->teacher->id));

        // 2. Lesson completed: record teacher earnings
        $this->ledgerService->recordSessionCompleted($booking);

        // Now teacher balance must reflect net amount (135,000)
        $this->assertEquals(135000, $this->ledgerService->getTeacherBalance($this->teacher->id));

        // 3. Teacher requests payout of 100,000
        $payout = Payout::create([
            'uuid' => (string) Str::uuid(),
            'payout_code' => 'WD-TEST-001',
            'teacher_profile_id' => $this->teacherProfile->id,
            'amount' => 100000,
            'status' => 'completed',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Guru Test',
            'processed_at' => now(),
        ]);

        $this->ledgerService->recordPayoutDisbursed($payout);

        // Balance after payout: 135,000 - 100,000 = 35,000
        $this->assertEquals(35000, $this->ledgerService->getTeacherBalance($this->teacher->id));
    }
}
