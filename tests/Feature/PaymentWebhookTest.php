<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\LessonSession;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent;

    protected User $teacher;

    protected Booking $booking;

    protected Payment $payment;

    protected PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);

        $this->parent = User::factory()->create([
            'role' => 'parent',
        ]);

        $this->teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacher->id,
            'headline' => 'Guru IPA',
            'hourly_rate' => 100000,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $student = Student::create([
            'parent_id' => $this->parent->id,
            'name' => 'Rian',
            'grade_level' => 'smp',
            'is_active' => true,
        ]);

        $subject = Subject::create([
            'name' => 'IPA Terpadu',
            'slug' => 'ipa-terpadu',
            'category' => 'smp',
            'icon' => 'atom',
            'is_active' => true,
        ]);

        $this->booking = Booking::create([
            'uuid' => (string) Str::uuid(),
            'booking_code' => 'BY-WH-001',
            'parent_id' => $this->parent->id,
            'teacher_id' => $this->teacher->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Tebet Barat',
            'status' => 'accepted',
            'hourly_rate_snapshot' => 100000,
            'session_fee' => 150000,
            'platform_commission_rate' => 10.0,
            'platform_commission_amount' => 15000,
            'teacher_net_amount' => 135000,
            'total_amount' => 150000,
        ]);

        // Initiate payment
        $this->payment = $this->paymentService->initiatePayment($this->booking, 'virtual_account');
    }

    public function test_valid_webhook_marks_payment_paid_confirms_booking_and_creates_lesson_session(): void
    {
        $secretKey = config('services.payment_gateway.mock_secret', 'belajar_yuk_mock_secret_key');
        $signature = hash_hmac('sha256', "{$this->payment->payment_code}|paid|150000", $secretKey);

        $response = $this->postJson(route('webhooks.payment'), [
            'payment_code' => $this->payment->payment_code,
            'status' => 'paid',
            'amount' => 150000,
            'transaction_reference' => 'GATEWAY-REF-9988',
        ], [
            'X-BelajarYuk-Signature' => $signature,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Payment successfully recorded',
        ]);

        $this->payment->refresh();
        $this->assertEquals('paid', $this->payment->status);
        $this->assertEquals('GATEWAY-REF-9988', $this->payment->transaction_reference);

        $this->booking->refresh();
        $this->assertEquals('confirmed', $this->booking->status);

        // Lesson session automatically created
        $session = LessonSession::where('booking_id', $this->booking->id)->first();
        $this->assertNotNull($session);
        $this->assertEquals('scheduled', $session->status);
    }

    public function test_webhook_idempotency_prevents_duplicate_processing(): void
    {
        $secretKey = config('services.payment_gateway.mock_secret', 'belajar_yuk_mock_secret_key');
        $signature = hash_hmac('sha256', "{$this->payment->payment_code}|paid|150000", $secretKey);

        $payload = [
            'payment_code' => $this->payment->payment_code,
            'status' => 'paid',
            'amount' => 150000,
            'transaction_reference' => 'GATEWAY-REF-9988',
        ];

        // First call
        $firstResponse = $this->postJson(route('webhooks.payment'), $payload, [
            'X-BelajarYuk-Signature' => $signature,
        ]);
        $firstResponse->assertOk();

        $sessionCount = LessonSession::where('booking_id', $this->booking->id)->count();
        $this->assertEquals(1, $sessionCount);

        // Duplicate second call (e.g. gateway retry)
        $secondResponse = $this->postJson(route('webhooks.payment'), $payload, [
            'X-BelajarYuk-Signature' => $signature,
        ]);
        $secondResponse->assertOk();
        $secondResponse->assertJson([
            'success' => true,
            'message' => 'Payment already processed previously (Idempotent)',
        ]);

        // Still only 1 session and 1 payment record
        $this->assertEquals(1, LessonSession::where('booking_id', $this->booking->id)->count());
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $response = $this->postJson(route('webhooks.payment'), [
            'payment_code' => $this->payment->payment_code,
            'status' => 'paid',
            'amount' => 150000,
        ], [
            'X-BelajarYuk-Signature' => 'invalid_forged_signature_hex_value',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid webhook signature',
        ]);

        // Payment should still be pending
        $this->payment->refresh();
        $this->assertEquals('pending', $this->payment->status);
    }
}
