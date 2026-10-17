<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\PlatformSetting;
use App\Models\Subject;
use App\Models\SupportTicket;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Ledger\LedgerService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected LedgerService $ledgerService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Admin Dashboard Overview.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $verifiedTeachers = TeacherProfile::where('is_verified', true)->count();
        $totalBookings = Booking::count();
        $totalCommission = $this->ledgerService->getTotalPlatformCommission();

        // Recent bookings
        $recentBookings = Booking::with(['parent', 'teacher', 'subject', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        // Teachers awaiting document verification
        $pendingTeachers = TeacherProfile::with(['user', 'documents'])
            ->where('verification_status', 'pending')
            ->take(5)
            ->get();

        // Recent support tickets
        $recentTickets = SupportTicket::with('user')
            ->where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'verifiedTeachers',
            'totalBookings',
            'totalCommission',
            'recentBookings',
            'pendingTeachers',
            'recentTickets'
        ));
    }

    /**
     * Teacher verification queue.
     */
    public function teachers(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $query = TeacherProfile::with(['user', 'documents', 'subjects']);

        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        $teachers = $query->latest()->paginate(10);

        return view('admin.teachers.index', compact('teachers', 'status'));
    }

    /**
     * Show teacher detail for verification review.
     */
    public function showTeacher(TeacherProfile $teacherProfile): View
    {
        $teacherProfile->load(['user', 'documents', 'subjects', 'availabilitySlots']);

        return view('admin.teachers.show', compact('teacherProfile'));
    }

    /**
     * Approve teacher documents & verify profile.
     */
    public function approveTeacher(TeacherProfile $teacherProfile, Request $request): RedirectResponse
    {
        $oldValues = [
            'is_verified' => $teacherProfile->is_verified,
            'verification_status' => $teacherProfile->verification_status,
        ];

        $hasKtp = $teacherProfile->documents()->where('document_type', 'ktp')->exists() || $request->boolean('ktp_verified');
        $hasIjazah = $teacherProfile->documents()->where('document_type', 'ijazah')->exists() || $request->boolean('pddikti_verified');
        $hasSkck = $teacherProfile->documents()->where('document_type', 'skck')->exists() || $request->boolean('skck_verified');
        $hasSkGuru = $teacherProfile->documents()->where('document_type', 'sk_guru')->exists() || $request->boolean('sk_guru_verified');

        $teacherProfile->update([
            'is_verified' => true,
            'verification_status' => 'verified',
            'rejection_reason' => null,
            'ktp_verified' => $hasKtp,
            'pddikti_verified' => $hasIjazah,
            'skck_verified' => $hasSkck,
            'sk_guru_verified' => $hasSkGuru,
        ]);

        // Mark pending documents as verified
        $teacherProfile->documents()->where('verification_status', 'pending')->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'verify_teacher',
            'auditable_type' => TeacherProfile::class,
            'auditable_id' => $teacherProfile->id,
            'old_values' => $oldValues,
            'new_values' => [
                'is_verified' => true,
                'verification_status' => 'verified',
                'ktp_verified' => $hasKtp,
                'pddikti_verified' => $hasIjazah,
                'skck_verified' => $hasSkck,
                'sk_guru_verified' => $hasSkGuru,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', "Guru {$teacherProfile->user->name} berhasil diverifikasi sesuai standar regulasi! Profil kini tampil pada hasil pencarian publik.");
    }

    /**
     * Reject teacher documents.
     */
    public function rejectTeacher(TeacherProfile $teacherProfile, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $teacherProfile->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'reject_teacher',
            'auditable_type' => TeacherProfile::class,
            'auditable_id' => $teacherProfile->id,
            'old_values' => ['verification_status' => 'pending'],
            'new_values' => ['verification_status' => 'rejected', 'reason' => $validated['rejection_reason']],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', "Verifikasi guru ditolak dengan catatan: {$validated['rejection_reason']}");
    }

    /**
     * User management.
     */
    public function users(Request $request): View
    {
        $role = $request->query('role');
        $search = $request->query('q');

        $query = User::with('teacherProfile');

        if ($role) {
            $query->where('role', $role);
        }
        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    /**
     * Toggle user suspension.
     */
    public function toggleUserStatus(User $user, Request $request): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun admin Anda sendiri.');
        }

        $newStatus = ! $user->is_active;
        $user->update(['is_active' => $newStatus]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $newStatus ? 'activate_user' : 'suspend_user',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'old_values' => ['is_active' => ! $newStatus],
            'new_values' => ['is_active' => $newStatus],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $statusText = $newStatus ? 'diaktifkan' : 'ditangguhkan (suspend)';

        return back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }

    /**
     * All marketplace bookings.
     */
    public function bookings(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::with(['parent', 'teacher', 'student', 'subject', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings', 'status'));
    }

    /**
     * All transactions and payments.
     */
    public function payments(Request $request): View
    {
        $payments = Payment::with(['booking.parent', 'booking.teacher', 'booking.subject'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Teacher payouts queue.
     */
    public function payouts(): View
    {
        $payouts = Payout::with('teacherProfile.user')->latest()->paginate(15);

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Approve and process payout disbursement.
     */
    public function processPayout(Payout $payout, Request $request): RedirectResponse
    {
        if ($payout->status !== 'requested') {
            return back()->with('error', 'Pencairan dana ini sudah diproses sebelumnya.');
        }

        $payout->update([
            'status' => 'completed',
            'processed_at' => now(),
            'admin_notes' => $request->input('admin_notes', 'Ditransfer oleh Admin Belajar Dong'),
        ]);

        // Record debit in ledger
        $this->ledgerService->recordPayoutDisbursed($payout);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'process_payout',
            'auditable_type' => Payout::class,
            'auditable_id' => $payout->id,
            'new_values' => ['status' => 'completed', 'amount' => $payout->amount],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Pencairan dana Rp '.number_format($payout->amount, 0, ',', '.')." ke {$payout->bank_account_name} berhasil diproses.");
    }

    /**
     * Subject management.
     */
    public function subjects(): View
    {
        $subjects = Subject::withCount('teachers')->orderBy('category')->orderBy('name')->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Store new subject.
     */
    public function storeSubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:sd,smp,sma,ujian,umum'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        Subject::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category' => $validated['category'],
            'icon' => $validated['icon'] ?? 'book-open',
            'is_active' => true,
        ]);

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Platform settings (e.g. commission percentage).
     */
    public function settings(): View
    {
        $commissionRate = PlatformSetting::get('commission_rate_percent', '10.00');

        return view('admin.settings', compact('commissionRate'));
    }

    /**
     * Update platform settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_rate_percent' => ['required', 'numeric', 'between:0,50'],
        ]);

        $old = PlatformSetting::get('commission_rate_percent', '10.00');
        PlatformSetting::set('commission_rate_percent', $validated['commission_rate_percent'], 'Persentase komisi platform');

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'change_commission_rate',
            'old_values' => ['commission_rate_percent' => $old],
            'new_values' => ['commission_rate_percent' => $validated['commission_rate_percent']],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', "Persentase komisi platform berhasil diperbarui menjadi {$validated['commission_rate_percent']}%.");
    }

    /**
     * Support tickets & dispute management.
     */
    public function tickets(): View
    {
        $tickets = SupportTicket::with(['user', 'booking'])->latest()->paginate(15);

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Resolve support ticket.
     */
    public function resolveTicket(SupportTicket $ticket, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_resolution' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:in_investigation,resolved,closed'],
        ]);

        $ticket->update([
            'admin_resolution' => $validated['admin_resolution'],
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : $ticket->resolved_at,
        ]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Audit logs.
     */
    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(20);

        return view('admin.audit-logs', compact('logs'));
    }
}
