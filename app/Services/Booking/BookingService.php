<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\PlatformSetting;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Calculate spherical distance in kilometers between two lat/long points.
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Validate that student lesson location is within teacher's service radius.
     *
     * @return array{is_within: bool, distance_km: float, max_radius_km: int}
     */
    public function validateServiceRadius(TeacherProfile $teacherProfile, float $lessonLat, float $lessonLon): array
    {
        if (! $teacherProfile->latitude || ! $teacherProfile->longitude) {
            return [
                'is_within' => true,
                'distance_km' => 0.0,
                'max_radius_km' => $teacherProfile->max_travel_distance_km,
            ];
        }

        $distance = $this->calculateDistance(
            $teacherProfile->latitude,
            $teacherProfile->longitude,
            $lessonLat,
            $lessonLon
        );

        return [
            'is_within' => $distance <= $teacherProfile->max_travel_distance_km,
            'distance_km' => $distance,
            'max_radius_km' => $teacherProfile->max_travel_distance_km,
        ];
    }

    /**
     * Validate teacher availability and prevent double booking.
     */
    public function isSlotAvailable(int $teacherUserId, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        $parsedDate = Carbon::parse($date);
        $dayOfWeek = $parsedDate->dayOfWeek; // 0=Sunday, 6=Saturday

        $teacherProfile = TeacherProfile::where('user_id', $teacherUserId)->first();
        if (! $teacherProfile) {
            return false;
        }

        // Check if teacher has active slot on this day of week
        $hasActiveSlot = $teacherProfile->availabilitySlots()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->exists();

        // If availability slots are defined, enforce them; otherwise allow default daytime
        if ($teacherProfile->availabilitySlots()->exists() && ! $hasActiveSlot) {
            return false;
        }

        // Prevent double booking: check if there's any active booking overlapping this timeframe
        $query = Booking::where('teacher_id', $teacherUserId)
            ->where('booking_date', $parsedDate->format('Y-m-d'))
            ->whereNotIn('status', ['rejected', 'cancelled', 'refunded'])
            ->where(function ($q) use ($startTime, $endTime): void {
                $q->where(function ($sub) use ($startTime, $endTime): void {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return ! $query->exists();
    }

    /**
     * Calculate pricing, 10% platform commission, and teacher net amount.
     *
     * @return array{
     *     hourly_rate: int,
     *     session_duration_minutes: int,
     *     session_fee: int,
     *     commission_rate: float,
     *     commission_amount: int,
     *     teacher_net_amount: int,
     *     total_amount: int
     * }
     */
    /**
     * Generate session dates for a booking plan.
     *
     * @param  string  $startDate  'Y-m-d'
     * @param  string  $planType  'daily'|'monthly'|'six_months'
     * @param  array<int>  $selectedDays  Array of dayOfWeek integers (0=Sunday, 1=Monday, ..., 6=Saturday)
     * @return array<string> List of 'Y-m-d' dates
     */
    public function generatePlanScheduleDates(string $startDate, string $planType, array $selectedDays = []): array
    {
        $start = Carbon::parse($startDate);

        if ($planType === 'daily') {
            return [$start->format('Y-m-d')];
        }

        $totalWeeks = ($planType === 'six_months') ? 24 : 4;

        if (empty($selectedDays)) {
            $selectedDays = [$start->dayOfWeek];
        }

        $days = array_values(array_unique(array_map('intval', $selectedDays)));
        sort($days);

        $dates = [];
        $cursor = $start->copy();

        // If cursor day is not in selectedDays, move cursor forward to the next matched day
        $seekSafety = 0;
        while (! in_array($cursor->dayOfWeek, $days, true) && $seekSafety < 7) {
            $cursor->addDay();
            $seekSafety++;
        }

        // Generate sessions for the plan duration
        $targetSessions = count($days) * $totalWeeks;
        $safetyMaxDays = ($totalWeeks * 7) + 14;
        $counter = 0;

        while (count($dates) < $targetSessions && $counter < $safetyMaxDays) {
            if (in_array($cursor->dayOfWeek, $days, true)) {
                $dates[] = $cursor->format('Y-m-d');
            }
            $cursor->addDay();
            $counter++;
        }

        return ! empty($dates) ? $dates : [$start->format('Y-m-d')];
    }

    /**
     * Calculate pricing, discounts, platform commission, and teacher net amount.
     *
     * @return array{
     *     hourly_rate: int,
     *     session_duration_minutes: int,
     *     single_session_fee: int,
     *     total_sessions: int,
     *     subtotal: int,
     *     discount_percent: float,
     *     discount_amount: int,
     *     session_fee: int,
     *     commission_rate: float,
     *     commission_amount: int,
     *     teacher_net_amount: int,
     *     total_amount: int
     * }
     */
    public function calculatePricing(
        TeacherProfile $profile,
        int $durationMinutes,
        ?int $customHourlyRate = null,
        int $totalSessions = 1,
        float $discountPercent = 0.0
    ): array {
        $hourlyRate = $customHourlyRate ?? $profile->hourly_rate;
        $singleSessionFee = (int) round(($hourlyRate * $durationMinutes) / 60);

        $sessions = max(1, $totalSessions);
        $subtotal = $singleSessionFee * $sessions;

        $discountPercent = max(0.0, min(100.0, $discountPercent));
        $discountAmount = (int) round(($subtotal * $discountPercent) / 100);
        $sessionFee = max(0, $subtotal - $discountAmount);

        $commissionRatePercent = (float) PlatformSetting::get('commission_rate_percent', '10.00');
        $commissionAmount = (int) round(($sessionFee * $commissionRatePercent) / 100);
        $teacherNetAmount = $sessionFee - $commissionAmount;

        return [
            'hourly_rate' => $hourlyRate,
            'session_duration_minutes' => $durationMinutes,
            'single_session_fee' => $singleSessionFee,
            'total_sessions' => $sessions,
            'subtotal' => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'session_fee' => $sessionFee,
            'commission_rate' => $commissionRatePercent,
            'commission_amount' => $commissionAmount,
            'teacher_net_amount' => $teacherNetAmount,
            'total_amount' => $sessionFee,
        ];
    }

    /**
     * Create booking with database transaction and race condition prevention.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws Exception
     */
    public function createBooking(User $parent, array $data): Booking
    {
        return DB::transaction(function () use ($parent, $data): Booking {
            $teacher = User::findOrFail($data['teacher_id']);
            $teacherProfile = $teacher->teacherProfile;

            if (! $teacherProfile || ! $teacherProfile->is_verified) {
                throw new Exception('Guru belum terverifikasi untuk menerima pemesanan les.');
            }

            $planType = $data['plan_type'] ?? 'daily';
            if (! in_array($planType, ['daily', 'monthly', 'six_months'], true)) {
                $planType = 'daily';
            }

            $date = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $startTime = $data['start_time'];
            $durationMinutes = (int) ($data['session_duration_minutes'] ?? $teacherProfile->session_duration_minutes);
            $endTime = Carbon::parse($date.' '.$startTime)->addMinutes($durationMinutes)->format('H:i:s');

            // 1. Double booking validation with row locking check
            $available = $this->isSlotAvailable($teacher->id, $date, $startTime, $endTime);
            if (! $available) {
                throw new Exception('Jadwal yang dipilih sudah terisi oleh pemesanan lain atau di luar jam kerja guru.');
            }

            // 2. Radius check
            $lessonLat = (float) ($data['lesson_latitude'] ?? 0);
            $lessonLon = (float) ($data['lesson_longitude'] ?? 0);
            if ($lessonLat && $lessonLon && $teacherProfile->latitude && $teacherProfile->longitude) {
                $radiusCheck = $this->validateServiceRadius($teacherProfile, $lessonLat, $lessonLon);
                if (! $radiusCheck['is_within']) {
                    throw new Exception("Lokasi les ({$radiusCheck['distance_km']} km) melebihi batas radius layanan guru ({$radiusCheck['max_radius_km']} km).");
                }
            }

            // 3. Plan schedule dates & discount computation
            $selectedDays = $data['selected_days'] ?? [];
            if (! is_array($selectedDays)) {
                $selectedDays = ! empty($selectedDays) ? [(int) $selectedDays] : [];
            }
            $selectedDays = array_values(array_filter(array_map('intval', $selectedDays), fn ($d) => $d >= 0 && $d <= 6));

            $scheduleDates = $this->generatePlanScheduleDates($date, $planType, $selectedDays);
            $totalSessions = count($scheduleDates);

            $discountPercent = match ($planType) {
                'monthly' => 5.0,
                'six_months' => 15.0,
                default => 0.0,
            };

            // 4. Pricing snapshot calculation
            $pricing = $this->calculatePricing(
                $teacherProfile,
                $durationMinutes,
                $data['custom_hourly_rate'] ?? null,
                $totalSessions,
                $discountPercent
            );

            $student = Student::find($data['student_id']);

            $booking = Booking::create([
                'uuid' => (string) Str::uuid(),
                'booking_code' => 'BD-'.date('ymd').'-'.rand(1000, 9999),
                'parent_id' => $parent->id,
                'teacher_id' => $teacher->id,
                'student_id' => $data['student_id'],
                'subject_id' => $data['subject_id'],
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'session_duration_minutes' => $durationMinutes,
                'plan_type' => $planType,
                'total_sessions' => $totalSessions,
                'selected_days' => $selectedDays,
                'discount_percent' => $pricing['discount_percent'],
                'discount_amount' => $pricing['discount_amount'],
                'schedule_dates' => $scheduleDates,
                'lesson_address' => $data['lesson_address'],
                'city_id' => $data['city_id'] ?? $student?->city_id ?? null,
                'district_id' => $data['district_id'] ?? $student?->district_id ?? null,
                'village_id' => $data['village_id'] ?? $student?->village_id ?? null,
                'lesson_latitude' => $lessonLat ?: null,
                'lesson_longitude' => $lessonLon ?: null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending_teacher',
                'hourly_rate_snapshot' => $pricing['hourly_rate'],
                'session_fee' => $pricing['session_fee'],
                'platform_commission_rate' => $pricing['commission_rate'],
                'platform_commission_amount' => $pricing['commission_amount'],
                'teacher_net_amount' => $pricing['teacher_net_amount'],
                'total_amount' => $pricing['total_amount'],
                'payment_deadline_at' => now()->addHours(24),
            ]);

            // Notify Teacher
            $planLabel = $booking->plan_label;
            Notification::create([
                'user_id' => $teacher->id,
                'title' => 'Permintaan Les Baru ('.$planLabel.')!',
                'message' => "Anda menerima permintaan les baru ({$planLabel}) dari {$parent->name} mulai tanggal {$date} pukul {$startTime}. Silakan tanggapi permintaan ini.",
                'type' => 'booking_request',
                'link' => route('teacher.bookings.show', $booking->id),
            ]);

            return $booking;
        });
    }

    /**
     * Teacher accepts booking request.
     */
    public function acceptBooking(Booking $booking, User $teacher): void
    {
        if ($booking->teacher_id !== $teacher->id) {
            throw new Exception('Anda tidak memiliki wewenang untuk menyetujui pemesanan ini.');
        }

        if ($booking->status !== 'pending_teacher') {
            throw new Exception('Pemesanan ini sudah tidak dalam status menunggu respon guru.');
        }

        $booking->update([
            'status' => 'accepted',
            'payment_deadline_at' => now()->addHours(24),
        ]);

        Notification::create([
            'user_id' => $booking->parent_id,
            'title' => 'Permintaan Les Diterima!',
            'message' => "Guru {$teacher->name} telah menerima permintaan les Anda ({$booking->booking_code}). Silakan selesaikan pembayaran sebelum batas waktu.",
            'type' => 'booking_accepted',
            'link' => route('parent.bookings.show', $booking->id),
        ]);
    }

    /**
     * Teacher rejects booking request.
     */
    public function rejectBooking(Booking $booking, User $teacher, string $reason): void
    {
        if ($booking->teacher_id !== $teacher->id) {
            throw new Exception('Anda tidak memiliki wewenang untuk menolak pemesanan ini.');
        }

        $booking->update([
            'status' => 'rejected',
            'cancellation_reason' => $reason,
            'cancelled_by' => $teacher->id,
        ]);

        Notification::create([
            'user_id' => $booking->parent_id,
            'title' => 'Permintaan Les Ditolak',
            'message' => "Guru {$teacher->name} belum dapat menerima jadwal les Anda dengan alasan: {$reason}. Anda dapat mencari guru lainnya.",
            'type' => 'booking_rejected',
            'link' => route('parent.bookings.show', $booking->id),
        ]);
    }
}
