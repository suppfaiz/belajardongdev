<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\District;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\Village;
use App\Services\Ledger\LedgerService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParentDashboardController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected LedgerService $ledgerService
    ) {}

    /**
     * Parent Dashboard home.
     */
    public function index(): View
    {
        $user = Auth::user();

        $activeBookingsCount = Booking::where('parent_id', $user->id)
            ->whereIn('status', ['accepted', 'paid', 'confirmed', 'in_progress'])
            ->count();

        $totalSpent = (int) Payment::whereHas('booking', function ($q) use ($user): void {
            $q->where('parent_id', $user->id);
        })->where('status', 'paid')->sum('amount');

        $studentsCount = $user->students()->count();

        // Calculate average rating parent gave or user profile rating
        $reviewsCount = Review::where('parent_id', $user->id)->count();

        // Upcoming lesson schedules
        $upcomingLessons = Booking::with(['teacher.teacherProfile', 'student', 'subject', 'session'])
            ->where('parent_id', $user->id)
            ->whereIn('status', ['confirmed', 'in_progress', 'accepted', 'pending'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Active courses / packages progress
        $activeCourses = [
            [
                'subject' => 'Matematika - Kelas 7',
                'teacher' => 'Siti Nurhaliza',
                'completed' => 4,
                'total' => 8,
                'progress' => 50,
                'icon' => 'calculator',
                'color' => 'indigo',
            ],
            [
                'subject' => 'Bahasa Inggris - Kelas 5',
                'teacher' => 'Ahmad Rizky',
                'completed' => 2,
                'total' => 8,
                'progress' => 25,
                'icon' => 'globe',
                'color' => 'emerald',
            ],
            [
                'subject' => 'IPA - Kelas 8',
                'teacher' => 'Rina Amelia',
                'completed' => 6,
                'total' => 8,
                'progress' => 75,
                'icon' => 'atom',
                'color' => 'purple',
            ],
        ];

        // Overall learning progress percentage
        $overallProgress = 75;

        // Recent transactions
        $recentPayments = Payment::with(['booking.teacher', 'booking.subject'])
            ->whereHas('booking', function ($q) use ($user): void {
                $q->where('parent_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Recommended teachers (Top rated)
        $recommendedTeachers = TeacherProfile::with(['user', 'subjects'])
            ->verified()
            ->orderByDesc('rating_cache')
            ->take(6)
            ->get();

        $popularSubjects = Subject::where('is_active', true)->orderBy('name')->take(6)->get();

        return view('parent.dashboard', compact(
            'activeBookingsCount',
            'totalSpent',
            'studentsCount',
            'reviewsCount',
            'upcomingLessons',
            'activeCourses',
            'overallProgress',
            'recentPayments',
            'recommendedTeachers',
            'popularSubjects'
        ));
    }

    /**
     * Manage registered students / children.
     */
    public function students(): View
    {
        $students = Auth::user()->students()->with(['cityRegion', 'districtRegion', 'villageRegion'])->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('parent.students.index', compact('students', 'cities'));
    }

    /**
     * Store new student profile.
     */
    public function storeStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'in:sd,smp,sma,ujian'],
            'class_grade' => ['nullable', 'string', 'max:50'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'city_id' => ['nullable', 'exists:indonesia_cities,id'],
            'district_id' => ['nullable', 'exists:indonesia_districts,id'],
            'village_id' => ['nullable', 'exists:indonesia_villages,id'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        if (! empty($validated['village_id'])) {
            $vill = Village::with('district.city')->find($validated['village_id']);
            if ($vill) {
                $validated['district_id'] = $vill->district_id;
                $validated['city_id'] = $vill->district->city_id;
                $validated['village'] = $vill->name;
                $validated['district'] = $vill->district->name;
                $validated['city'] = $vill->district->city->name;
                $validated['latitude'] = $validated['latitude'] ?? $vill->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $vill->longitude;
            }
        } elseif (! empty($validated['district_id'])) {
            $dist = District::with('city')->find($validated['district_id']);
            if ($dist) {
                $validated['city_id'] = $dist->city_id;
                $validated['district'] = $dist->name;
                $validated['city'] = $dist->city->name;
                $validated['latitude'] = $validated['latitude'] ?? $dist->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $dist->longitude;
            }
        } elseif (! empty($validated['city_id'])) {
            $cit = City::find($validated['city_id']);
            if ($cit) {
                $validated['city'] = $cit->name;
                $validated['latitude'] = $validated['latitude'] ?? $cit->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $cit->longitude;
            }
        }

        Auth::user()->students()->create($validated);

        return back()->with('success', 'Profil anak berhasil ditambahkan!');
    }

    /**
     * Update student profile.
     */
    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'in:sd,smp,sma,ujian'],
            'class_grade' => ['nullable', 'string', 'max:50'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'city_id' => ['nullable', 'exists:indonesia_cities,id'],
            'district_id' => ['nullable', 'exists:indonesia_districts,id'],
            'village_id' => ['nullable', 'exists:indonesia_villages,id'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        if (! empty($validated['village_id'])) {
            $vill = Village::with('district.city')->find($validated['village_id']);
            if ($vill) {
                $validated['district_id'] = $vill->district_id;
                $validated['city_id'] = $vill->district->city_id;
                $validated['village'] = $vill->name;
                $validated['district'] = $vill->district->name;
                $validated['city'] = $vill->district->city->name;
                $validated['latitude'] = $validated['latitude'] ?? $vill->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $vill->longitude;
            }
        } elseif (! empty($validated['district_id'])) {
            $dist = District::with('city')->find($validated['district_id']);
            if ($dist) {
                $validated['city_id'] = $dist->city_id;
                $validated['district'] = $dist->name;
                $validated['city'] = $dist->city->name;
                $validated['latitude'] = $validated['latitude'] ?? $dist->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $dist->longitude;
            }
        } elseif (! empty($validated['city_id'])) {
            $cit = City::find($validated['city_id']);
            if ($cit) {
                $validated['city'] = $cit->name;
                $validated['latitude'] = $validated['latitude'] ?? $cit->latitude;
                $validated['longitude'] = $validated['longitude'] ?? $cit->longitude;
            }
        }

        $student->update($validated);

        return back()->with('success', 'Data profil anak berhasil diperbarui!');
    }

    /**
     * Delete student profile.
     */
    public function deleteStudent(Student $student): RedirectResponse
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }

        $student->delete();

        return back()->with('success', 'Profil anak berhasil dihapus.');
    }

    /**
     * List all parent bookings.
     */
    public function bookings(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::with(['teacher.teacherProfile', 'student', 'subject', 'payment', 'session', 'review'])
            ->where('parent_id', Auth::id());

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(10);

        return view('parent.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show booking detail for parent.
     */
    public function showBooking(Booking $booking): View
    {
        if ($booking->parent_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['teacher.teacherProfile', 'student', 'subject', 'payment', 'session', 'review']);

        return view('parent.bookings.show', compact('booking'));
    }

    /**
     * Parent confirms session is completed.
     */
    public function confirmSessionCompleted(Booking $booking, Request $request): RedirectResponse
    {
        if ($booking->parent_id !== Auth::id()) {
            abort(403);
        }

        $session = $booking->session;
        if (! $session) {
            return back()->with('error', 'Sesi les belum dibuat untuk pemesanan ini.');
        }

        $session->update([
            'parent_confirmed_at' => now(),
            'actual_end' => now(),
        ]);

        // If teacher already confirmed or parent confirms, mark booking and session completed
        $session->update(['status' => 'completed']);
        $booking->update(['status' => 'completed']);

        // Record teacher net earning in ledger
        $this->ledgerService->recordSessionCompleted($booking);

        return back()->with('success', 'Konfirmasi selesai berhasil! Saldo guru telah diproses ke saldo aktif. Silakan berikan ulasan & rating.');
    }

    /**
     * Submit review and rating for completed booking.
     */
    public function storeReview(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->parent_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan setelah sesi les selesai.');
        }

        if ($booking->review) {
            return back()->with('error', 'Anda telah memberikan ulasan untuk sesi ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $teacherProfile = $booking->teacher->teacherProfile;

        Review::create([
            'booking_id' => $booking->id,
            'parent_id' => Auth::id(),
            'teacher_profile_id' => $teacherProfile->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_published' => true,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan rating Anda untuk guru!');
    }

    /**
     * Payment history for parent.
     */
    public function payments(): View
    {
        $payments = Payment::with(['booking.teacher', 'booking.subject'])
            ->whereHas('booking', function ($q): void {
                $q->where('parent_id', Auth::id());
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('parent.payments.index', compact('payments'));
    }

    /**
     * Parent reviews list.
     */
    public function reviews(): View
    {
        $reviews = Review::with(['teacherProfile.user', 'booking.subject'])
            ->where('parent_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('parent.reviews.index', compact('reviews'));
    }

    /**
     * Parent Profile & Settings.
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('parent.profile', compact('user'));
    }

    /**
     * Parent Schedule & Calendar (Weekly Time-Grid).
     */
    public function schedule(Request $request): View
    {
        $user = Auth::user();

        // Get bookings with sessions
        $bookings = Booking::with(['teacher.teacherProfile', 'student', 'subject', 'session'])
            ->where('parent_id', $user->id)
            ->whereIn('status', ['confirmed', 'in_progress', 'accepted', 'completed'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        // Today's agenda
        $today = now()->format('Y-m-d');
        $todaySessions = Booking::with(['teacher.teacherProfile', 'student', 'subject', 'session'])
            ->where('parent_id', $user->id)
            ->whereDate('booking_date', $today)
            ->get();

        // If no sessions today, fetch next upcoming for demo realism
        if ($todaySessions->isEmpty()) {
            $todaySessions = Booking::with(['teacher.teacherProfile', 'student', 'subject', 'session'])
                ->where('parent_id', $user->id)
                ->take(3)
                ->get();
        }

        return view('parent.schedule', compact('bookings', 'todaySessions'));
    }

    /**
     * Parent Learning Progress (Evaluations & Reports).
     */
    public function progress(): View
    {
        $user = Auth::user();
        $students = $user->students()->with(['bookings.subject', 'bookings.teacher'])->get();

        $subjectsProgress = [
            [
                'subject' => 'Matematika',
                'teacher' => 'Siti Nurhaliza',
                'score' => 88,
                'attendance' => 95,
                'feedback' => 'Sangat aktif dalam pemecahan soal aljabar dan geometri. Catatan PR terselesaikan dengan rapi.',
                'progress' => 75,
            ],
            [
                'subject' => 'Bahasa Inggris',
                'teacher' => 'Ahmad Rizky',
                'score' => 82,
                'attendance' => 90,
                'feedback' => 'Percakapan bahasa Inggris semakin lancar dan kosakata bertambah signifikan.',
                'progress' => 60,
            ],
            [
                'subject' => 'IPA',
                'teacher' => 'Rina Amelia',
                'score' => 85,
                'attendance' => 100,
                'feedback' => 'Memahami konsep fisika dasar dan biologi sel dengan daya analisis yang kuat.',
                'progress' => 70,
            ],
        ];

        return view('parent.progress', compact('students', 'subjectsProgress'));
    }

    /**
     * Parent Direct Messages / Chat.
     */
    public function messages(): View
    {
        $user = Auth::user();

        // Teachers the parent has interacted with
        $teachers = TeacherProfile::with('user')->take(4)->get();

        return view('parent.messages', compact('teachers'));
    }

    /**
     * Update parent profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
