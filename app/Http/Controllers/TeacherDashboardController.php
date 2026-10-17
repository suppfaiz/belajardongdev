<?php

namespace App\Http\Controllers;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\City;
use App\Models\District;
use App\Models\LedgerEntry;
use App\Models\Payout;
use App\Models\Subject;
use App\Models\TeacherDocument;
use App\Models\Village;
use App\Services\Booking\BookingService;
use App\Services\Ledger\LedgerService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected LedgerService $ledgerService
    ) {}

    /**
     * Teacher Dashboard Home.
     */
    public function index(): View
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        // Financial balances
        $grossEarnings = (int) Booking::where('teacher_id', $user->id)
            ->where('status', 'completed')
            ->sum('session_fee');

        $inProcessAmount = (int) Booking::where('teacher_id', $user->id)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->sum('teacher_net_amount');

        $availableBalance = $this->ledgerService->getTeacherBalance($user->id);

        // Pending booking requests needing teacher response
        $pendingRequests = Booking::with(['parent', 'student', 'subject'])
            ->where('teacher_id', $user->id)
            ->where('status', 'pending_teacher')
            ->orderBy('created_at')
            ->get();

        // Today's lessons
        $todayLessons = Booking::with(['parent', 'student', 'subject', 'session'])
            ->where('teacher_id', $user->id)
            ->where('booking_date', today()->toDateString())
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->orderBy('start_time')
            ->get();

        $completedLessonsCount = Booking::where('teacher_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Upcoming confirmed lessons
        $upcomingLessons = Booking::with(['parent', 'student', 'subject', 'session'])
            ->where('teacher_id', $user->id)
            ->where('booking_date', '>=', today()->toDateString())
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'profile',
            'grossEarnings',
            'inProcessAmount',
            'availableBalance',
            'pendingRequests',
            'todayLessons',
            'completedLessonsCount',
            'upcomingLessons'
        ));
    }

    /**
     * Teacher Profile page.
     */
    /**
     * Teacher Profile page.
     */
    public function profile(): View
    {
        $profile = Auth::user()->teacherProfile;
        $profile->load(['city', 'district', 'village']);

        $cities = City::orderBy('name')->get();
        $districts = $profile->city_id ? District::where('city_id', $profile->city_id)->orderBy('name')->get() : collect();
        $villages = $profile->district_id ? Village::where('district_id', $profile->district_id)->orderBy('name')->get() : collect();

        return view('teacher.profile', compact('profile', 'cities', 'districts', 'villages'));
    }

    /**
     * Update teacher profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'headline' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:2000'],
            'education' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['required', 'integer', 'min:30000', 'max:1000000'],
            'session_duration_minutes' => ['required', 'integer', 'in:60,90,120'],
            'service_city' => ['nullable', 'string', 'max:100'],
            'service_district' => ['nullable', 'string', 'max:100'],
            'service_village' => ['nullable', 'string', 'max:100'],
            'city_id' => ['nullable', 'exists:indonesia_cities,id'],
            'district_id' => ['nullable', 'exists:indonesia_districts,id'],
            'village_id' => ['nullable', 'exists:indonesia_villages,id'],
            'service_address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'max_travel_distance_km' => ['required', 'integer', 'min:1', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if (! empty($validated['village_id'])) {
            $vill = Village::with('district.city')->find($validated['village_id']);
            if ($vill) {
                $validated['district_id'] = $vill->district_id;
                $validated['city_id'] = $vill->district->city_id;
                $validated['service_village'] = $vill->name;
                $validated['service_district'] = $vill->district->name;
                $validated['service_city'] = $vill->district->city->name;
            }
        } elseif (! empty($validated['district_id'])) {
            $dist = District::with('city')->find($validated['district_id']);
            if ($dist) {
                $validated['city_id'] = $dist->city_id;
                $validated['service_district'] = $dist->name;
                $validated['service_city'] = $dist->city->name;
            }
        } elseif (! empty($validated['city_id'])) {
            $cit = City::find($validated['city_id']);
            if ($cit) {
                $validated['service_city'] = $cit->name;
            }
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/'.$avatarPath;
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->save();

        unset($validated['name'], $validated['phone'], $validated['avatar']);
        $profile->update($validated);

        return back()->with('success', 'Profil guru dan area jangkauan mengajar berhasil diperbarui!');
    }

    /**
     * Teacher documents & milestone verification page.
     */
    public function documents(Request $request): View
    {
        $profile = Auth::user()->teacherProfile;
        $documents = $profile->documents()->latest()->get();

        $defaultMilestone = 1;
        if ($profile->isMilestone1Complete()) {
            $defaultMilestone = 2;
        }
        if ($profile->isMilestone2Complete()) {
            $defaultMilestone = 3;
        }
        if ($profile->isMilestone3Complete()) {
            $defaultMilestone = 4;
        }
        if ($profile->isMilestone4Complete()) {
            $defaultMilestone = 5;
        }

        $activeMilestone = (int) $request->query('milestone', $defaultMilestone);
        $progressPercent = $profile->getMilestoneProgressPercent();

        return view('teacher.documents', compact('profile', 'documents', 'activeMilestone', 'progressPercent'));
    }

    /**
     * Save Milestone 1: KTP WNI & Data Kependudukan (UU No. 24/2013).
     */
    public function saveMilestone1(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $validated = $request->validate([
            'nik' => ['required', 'string', 'digits:16'],
            'full_name_ktp' => ['required', 'string', 'max:150'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'ktp_address' => ['required', 'string', 'max:500'],
            'ktp_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'selfie_ktp_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('ktp_file')) {
            $file = $request->file('ktp_file');
            $path = $file->store('teacher_documents/'.$profile->id, 'local');
            TeacherDocument::create([
                'teacher_profile_id' => $profile->id,
                'document_type' => 'ktp',
                'document_number' => $validated['nik'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'verification_status' => 'pending',
            ]);
        }

        if ($request->hasFile('selfie_ktp_file')) {
            $file = $request->file('selfie_ktp_file');
            $path = $file->store('teacher_documents/'.$profile->id, 'local');
            TeacherDocument::create([
                'teacher_profile_id' => $profile->id,
                'document_type' => 'selfie_ktp',
                'document_number' => $validated['nik'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'verification_status' => 'pending',
            ]);
        }

        unset($validated['ktp_file'], $validated['selfie_ktp_file']);
        $profile->update($validated);

        return redirect()->route('teacher.documents', ['milestone' => 2])
            ->with('success', 'Milestone 1 (Identitas KTP WNI) berhasil disimpan! Silakan lanjutkan ke Milestone 2.');
    }

    /**
     * Save Milestone 2: Kualifikasi Akademik & Status Guru (UU No. 14/2005).
     */
    public function saveMilestone2(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $validated = $request->validate([
            'degree_level' => ['required', 'in:D4,S1,S2,S3'],
            'university_name' => ['required', 'string', 'max:150'],
            'major' => ['required', 'string', 'max:100'],
            'diploma_number' => ['required', 'string', 'max:100'],
            'graduation_year' => ['required', 'integer', 'min:1970', 'max:'.(date('Y') + 1)],
            'gpa' => ['nullable', 'numeric', 'between:0.00,4.00'],
            'teaching_status' => ['required', 'string', 'max:50'],
            'school_origin' => ['nullable', 'string', 'max:150'],
            'sk_guru_number' => ['nullable', 'string', 'max:100'],
            'ijazah_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'sk_guru_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('ijazah_file')) {
            $file = $request->file('ijazah_file');
            $path = $file->store('teacher_documents/'.$profile->id, 'local');
            TeacherDocument::create([
                'teacher_profile_id' => $profile->id,
                'document_type' => 'ijazah',
                'document_number' => $validated['diploma_number'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'verification_status' => 'pending',
            ]);
        }

        if ($request->hasFile('sk_guru_file')) {
            $file = $request->file('sk_guru_file');
            $path = $file->store('teacher_documents/'.$profile->id, 'local');
            TeacherDocument::create([
                'teacher_profile_id' => $profile->id,
                'document_type' => 'sk_guru',
                'document_number' => $validated['sk_guru_number'] ?? null,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'verification_status' => 'pending',
            ]);
        }

        $validated['education'] = "{$validated['degree_level']} {$validated['major']}, {$validated['university_name']}";

        unset($validated['ijazah_file'], $validated['sk_guru_file']);
        $profile->update($validated);

        return redirect()->route('teacher.documents', ['milestone' => 3])
            ->with('success', 'Milestone 2 (Kualifikasi Akademik & Kepegawaian) berhasil disimpan! Silakan lanjutkan ke Milestone 3.');
    }

    /**
     * Save Milestone 3: SKCK Kepolisian (UU No. 35/2014 & Perkapolri 18/2014).
     */
    public function saveMilestone3(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $validated = $request->validate([
            'skck_number' => ['required', 'string', 'max:100'],
            'skck_issuer' => ['required', 'string', 'max:100'],
            'skck_valid_until' => ['required', 'date', 'after:today'],
            'skck_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('skck_file')) {
            $file = $request->file('skck_file');
            $path = $file->store('teacher_documents/'.$profile->id, 'local');
            TeacherDocument::create([
                'teacher_profile_id' => $profile->id,
                'document_type' => 'skck',
                'document_number' => $validated['skck_number'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'verification_status' => 'pending',
            ]);
        }

        unset($validated['skck_file']);
        $profile->update($validated);

        return redirect()->route('teacher.documents', ['milestone' => 4])
            ->with('success', 'Milestone 3 (SKCK Kepolisian) berhasil disimpan! Silakan lanjutkan ke Milestone 4.');
    }

    /**
     * Save Milestone 4: Pakta Integritas Perlindungan Anak.
     */
    public function saveMilestone4(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $validated = $request->validate([
            'agree_child_protection' => ['required', 'accepted'],
            'child_protection_signature_name' => ['required', 'string', 'max:150'],
        ], [
            'agree_child_protection.accepted' => 'Anda wajib menyetujui seluruh butir Pakta Integritas Perlindungan Anak & Kode Etik Mengajar Tatap Muka.',
        ]);

        $profile->update([
            'child_protection_pledged_at' => now(),
            'child_protection_signature_name' => $validated['child_protection_signature_name'],
            'verification_status' => 'pending',
        ]);

        return redirect()->route('teacher.documents', ['milestone' => 5])
            ->with('success', 'Seluruh Milestone Verifikasi Berhasil Diajukan! Tim Verifikator Belajar Dong akan meninjau berkas Anda dalam 1x24 jam.');
    }

    /**
     * Upload verification document (Stored securely in private storage - generic fallback).
     */
    public function uploadDocument(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $request->validate([
            'document_type' => ['required', 'in:ktp,selfie_ktp,ijazah,skck,sk_guru,sertifikat,transkrip'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'document_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $file = $request->file('document_file');
        // Store in private disk
        $path = $file->store('teacher_documents/'.$profile->id, 'local');

        TeacherDocument::create([
            'teacher_profile_id' => $profile->id,
            'document_type' => $request->input('document_type'),
            'document_number' => $request->input('document_number'),
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'verification_status' => 'pending',
        ]);

        // If previously rejected, set status back to pending
        if ($profile->verification_status === 'rejected') {
            $profile->update(['verification_status' => 'pending']);
        }

        return back()->with('success', 'Dokumen berhasil diunggah secara aman. Tim admin Belajar Dong akan segera memverifikasinya.');
    }

    /**
     * Teacher signs digital Child Protection Pact & Code of Conduct (UU No. 35/2014 & UU No. 23/2002).
     */
    public function pledgeChildProtection(Request $request): RedirectResponse
    {
        $request->validate([
            'agree_child_protection' => ['required', 'accepted'],
            'child_protection_signature_name' => ['nullable', 'string', 'max:150'],
        ], [
            'agree_child_protection.accepted' => 'Anda wajib menyetujui seluruh butir Pakta Integritas Perlindungan Anak & Kode Etik Mengajar Tatap Muka.',
        ]);

        $profile = Auth::user()->teacherProfile;
        $profile->update([
            'child_protection_pledged_at' => now(),
            'child_protection_signature_name' => $request->input('child_protection_signature_name') ?? Auth::user()->name,
        ]);

        return back()->with('success', 'Pakta Integritas Perlindungan Anak & Kode Etik Mengajar berhasil ditandatangani secara digital.');
    }

    /**
     * Manage taught subjects and grade levels.
     */
    public function subjects(): View
    {
        $profile = Auth::user()->teacherProfile->load('subjects');
        $allSubjects = Subject::where('is_active', true)->orderBy('name')->get();

        return view('teacher.subjects', compact('profile', 'allSubjects'));
    }

    /**
     * Update taught subjects.
     */
    public function updateSubjects(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $selectedSubjects = $request->input('subjects', []);

        $syncData = [];
        foreach ($selectedSubjects as $subjectId => $data) {
            if (! empty($data['selected'])) {
                $syncData[$subjectId] = [
                    'grade_levels' => json_encode($data['grades'] ?? ['sd', 'smp']),
                    'custom_hourly_rate' => ! empty($data['rate']) ? (int) $data['rate'] : null,
                ];
            }
        }

        $profile->subjects()->sync($syncData);

        return back()->with('success', 'Mata pelajaran dan jenjang yang Anda ajar berhasil diperbarui!');
    }

    /**
     * Manage availability schedule slots.
     */
    public function schedules(): View
    {
        $profile = Auth::user()->teacherProfile;
        $slots = $profile->availabilitySlots()->orderBy('day_of_week')->orderBy('start_time')->get();

        return view('teacher.schedules', compact('profile', 'slots'));
    }

    /**
     * Store new availability slot.
     */
    public function storeSchedule(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;

        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $profile->availabilitySlots()->create([
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Jadwal ketersediaan berhasil ditambahkan!');
    }

    /**
     * Delete availability slot.
     */
    public function deleteSchedule(AvailabilitySlot $slot): RedirectResponse
    {
        if ($slot->teacher_profile_id !== Auth::user()->teacherProfile->id) {
            abort(403);
        }

        $slot->delete();

        return back()->with('success', 'Slot jadwal berhasil dihapus.');
    }

    /**
     * Teacher bookings list.
     */
    public function bookings(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::with(['parent', 'student', 'subject', 'session', 'payment'])
            ->where('teacher_id', Auth::id());

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(10);

        return view('teacher.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show booking detail for teacher.
     */
    public function showBooking(Booking $booking): View
    {
        if ($booking->teacher_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['parent', 'student', 'subject', 'payment', 'session', 'review']);

        return view('teacher.bookings.show', compact('booking'));
    }

    /**
     * Accept booking request.
     */
    public function acceptBooking(Booking $booking): RedirectResponse
    {
        try {
            $this->bookingService->acceptBooking($booking, Auth::user());

            return back()->with('success', 'Permintaan les berhasil diterima! Menunggu pembayaran dari orang tua murid.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject booking request.
     */
    public function rejectBooking(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->bookingService->rejectBooking($booking, Auth::user(), $request->input('rejection_reason'));

            return back()->with('success', 'Permintaan les telah ditolak.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Teacher confirms arrival / session complete.
     */
    public function confirmSession(Booking $booking): RedirectResponse
    {
        if ($booking->teacher_id !== Auth::id()) {
            abort(403);
        }

        $session = $booking->session;
        if (! $session) {
            return back()->with('error', 'Sesi les belum dibuat.');
        }

        $session->update([
            'teacher_confirmed_at' => now(),
            'actual_start' => $session->actual_start ?? now(),
        ]);

        return back()->with('success', 'Konfirmasi kehadiran/sesi dari Anda telah tercatat.');
    }

    /**
     * Teacher earnings & financial ledger entries.
     */
    public function earnings(): View
    {
        $user = Auth::user();

        $grossEarnings = (int) Booking::where('teacher_id', $user->id)
            ->where('status', 'completed')
            ->sum('session_fee');

        $totalCommissionPaid = (int) Booking::where('teacher_id', $user->id)
            ->where('status', 'completed')
            ->sum('platform_commission_amount');

        $availableBalance = $this->ledgerService->getTeacherBalance($user->id);

        $ledgerEntries = LedgerEntry::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('teacher.earnings', compact(
            'grossEarnings',
            'totalCommissionPaid',
            'availableBalance',
            'ledgerEntries'
        ));
    }

    /**
     * Teacher payout requests.
     */
    public function payouts(): View
    {
        $profile = Auth::user()->teacherProfile;
        $availableBalance = $this->ledgerService->getTeacherBalance(Auth::id());

        $payouts = $profile->payouts()->latest()->paginate(10);

        return view('teacher.payouts', compact('profile', 'availableBalance', 'payouts'));
    }

    /**
     * Request a new payout.
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        $profile = Auth::user()->teacherProfile;
        $availableBalance = $this->ledgerService->getTeacherBalance(Auth::id());

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:50000', 'max:'.$availableBalance],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_name' => ['required', 'string', 'max:100'],
        ], [
            'amount.max' => 'Nominal pencairan melebihi saldo tersedia Anda.',
            'amount.min' => 'Batas minimum pencairan dana adalah Rp 50.000.',
        ]);

        Payout::create([
            'teacher_profile_id' => $profile->id,
            'amount' => $validated['amount'],
            'status' => 'requested',
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name' => $validated['bank_account_name'],
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Permintaan pencairan dana berhasil diajukan! Tim kami akan memverifikasi dan mentransfer ke rekening Anda dalam 1x24 jam.');
    }

    /**
     * Reviews received by teacher.
     */
    public function reviews(): View
    {
        $profile = Auth::user()->teacherProfile;
        $reviews = $profile->reviews()->with('parent', 'booking.subject')->latest()->paginate(10);

        return view('teacher.reviews', compact('profile', 'reviews'));
    }
}
