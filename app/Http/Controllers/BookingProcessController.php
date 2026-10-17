<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Services\Booking\BookingService;
use App\Services\Payment\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingProcessController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Show booking wizard for a selected teacher.
     */
    public function create(TeacherProfile $teacherProfile, Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isParent()) {
            return redirect()->route('teachers.show', $teacherProfile->id)
                ->with('error', 'Pemesanan les hanya dapat dilakukan melalui akun Orang Tua.');
        }

        $teacherProfile->load(['user', 'subjects', 'availabilitySlots']);

        $students = $user->students()->where('is_active', true)->get();

        return view('bookings.create', compact('teacherProfile', 'students'));
    }

    /**
     * Store new booking request from parent.
     */
    public function store(Request $request, TeacherProfile $teacherProfile): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'plan_type' => ['nullable', 'string', 'in:daily,monthly,six_months'],
            'selected_days' => ['nullable', 'array'],
            'selected_days.*' => ['integer', 'between:0,6'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'session_duration_minutes' => ['required', 'integer', 'in:60,90,120'],
            'lesson_address' => ['required', 'string', 'max:500'],
            'city_id' => ['nullable', 'exists:indonesia_cities,id'],
            'district_id' => ['nullable', 'exists:indonesia_districts,id'],
            'village_id' => ['nullable', 'exists:indonesia_villages,id'],
            'lesson_latitude' => ['nullable', 'numeric'],
            'lesson_longitude' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'student_id.required' => 'Pilih profil anak yang akan mengikuti les.',
            'subject_id.required' => 'Pilih mata pelajaran yang diinginkan.',
            'booking_date.required' => 'Tentukan tanggal les.',
            'booking_date.after_or_equal' => 'Tanggal les tidak boleh di masa lampau.',
            'start_time.required' => 'Pilih jam mulai les.',
            'lesson_address.required' => 'Alamat lengkap lokasi les di rumah wajib diisi.',
        ]);

        // Ensure student belongs to this parent
        $student = Student::where('id', $validated['student_id'])
            ->where('parent_id', $user->id)
            ->firstOrFail();

        try {
            $booking = $this->bookingService->createBooking($user, [
                'teacher_id' => $teacherProfile->user_id,
                'student_id' => $student->id,
                'subject_id' => $validated['subject_id'],
                'plan_type' => $validated['plan_type'] ?? 'daily',
                'selected_days' => $validated['selected_days'] ?? [],
                'booking_date' => $validated['booking_date'],
                'start_time' => $validated['start_time'],
                'session_duration_minutes' => (int) $validated['session_duration_minutes'],
                'lesson_address' => $validated['lesson_address'],
                'city_id' => $validated['city_id'] ?? $student->city_id,
                'district_id' => $validated['district_id'] ?? $student->district_id,
                'village_id' => $validated['village_id'] ?? $student->village_id,
                'lesson_latitude' => $validated['lesson_latitude'] ?? $student->latitude,
                'lesson_longitude' => $validated['lesson_longitude'] ?? $student->longitude,
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('parent.bookings.show', $booking->id)
                ->with('success', 'Permintaan les berhasil dikirimkan ke guru! Menunggu konfirmasi ketersediaan dari guru.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show checkout payment page for an accepted booking.
     */
    public function showCheckout(Booking $booking): View|RedirectResponse
    {
        $user = Auth::user();

        if ($booking->parent_id !== $user->id) {
            abort(403);
        }

        if (! in_array($booking->status, ['accepted', 'pending_teacher'], true)) {
            return redirect()->route('parent.bookings.show', $booking->id)
                ->with('info', "Status pemesanan ini adalah {$booking->status}.");
        }

        $booking->load(['teacher.teacherProfile', 'student', 'subject', 'payment']);

        return view('bookings.checkout', compact('booking'));
    }

    /**
     * Process checkout payment method selection.
     */
    public function processCheckout(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->parent_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:virtual_account,gopay,dana,qris,bank_transfer'],
        ]);

        $payment = $this->paymentService->initiatePayment($booking, $validated['payment_method']);

        return redirect()->route('bookings.payment-instruction', $booking->id)
            ->with('success', 'Tagihan pembayaran berhasil dibuat.');
    }

    /**
     * Show payment instruction page with simulated interactive controls.
     */
    public function showPaymentInstruction(Booking $booking): View|RedirectResponse
    {
        $user = Auth::user();

        if ($booking->parent_id !== $user->id) {
            abort(403);
        }

        $booking->load(['teacher.teacherProfile', 'student', 'subject', 'payment']);

        if (! $booking->payment) {
            return redirect()->route('bookings.checkout', $booking->id);
        }

        return view('bookings.payment-instruction', compact('booking'));
    }

    /**
     * Interactive sandbox simulation button: Simulates payment confirmation from payment provider.
     */
    public function simulatePay(Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->parent_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $payment = $booking->payment;

        if (! $payment) {
            return back()->with('error', 'Tagihan pembayaran belum dibuat.');
        }

        if ($payment->status === 'paid') {
            return redirect()->route('parent.bookings.show', $booking->id)
                ->with('info', 'Pembayaran ini sudah lunas sebelumnya.');
        }

        $this->paymentService->markAsPaid($payment, 'SIM-TRX-'.rand(100000, 999999));

        return redirect()->route('parent.bookings.show', $booking->id)
            ->with('success', 'Simulasi pembayaran sukses! Status pemesanan kini Terkonfirmasi dan saldo guru tercatat di ledger sistem.');
    }
}
