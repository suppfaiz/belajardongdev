<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\Village;
use App\Services\Booking\BookingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherSearchController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Search teachers with geospatial radius and multi-criteria filters.
     */
    public function index(Request $request): View
    {
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $cities = City::with('province')->orderBy('name')->get();

        $selectedCityId = $request->filled('city_id') ? (int) $request->query('city_id') : null;
        $selectedDistrictId = $request->filled('district_id') ? (int) $request->query('district_id') : null;
        $selectedVillageId = $request->filled('village_id') ? (int) $request->query('village_id') : null;

        $districts = collect();
        $villages = collect();

        $locationName = $request->query('location', 'Jakarta Selatan');

        // Determine coordinates from Village -> District -> City or direct lat/lon parameters
        $lat = $request->filled('lat') ? (float) $request->query('lat') : null;
        $lon = $request->filled('lon') ? (float) $request->query('lon') : null;

        if ($selectedVillageId) {
            $village = Village::with('district.city')->find($selectedVillageId);
            if ($village) {
                $selectedDistrictId = $village->district_id;
                $selectedCityId = $village->district->city_id;
                $lat = $lat ?? $village->latitude;
                $lon = $lon ?? $village->longitude;
                $locationName = "{$village->name}, {$village->district->name}";
            }
        }

        if ($selectedDistrictId) {
            $district = District::with('city')->find($selectedDistrictId);
            if ($district) {
                $selectedCityId = $district->city_id;
                $lat = $lat ?? $district->latitude;
                $lon = $lon ?? $district->longitude;
                if (! $selectedVillageId) {
                    $locationName = "{$district->name}, {$district->city->name}";
                }
            }
            $villages = Village::where('district_id', $selectedDistrictId)->orderBy('name')->get();
        }

        if ($selectedCityId) {
            $city = City::find($selectedCityId);
            if ($city) {
                $lat = $lat ?? $city->latitude;
                $lon = $lon ?? $city->longitude;
                if (! $selectedDistrictId && ! $selectedVillageId) {
                    $locationName = $city->name;
                }
            }
            $districts = District::where('city_id', $selectedCityId)->orderBy('name')->get();
        }

        // Fallback default coordinates (Jakarta Selatan) if nothing selected
        if ($lat === null || $lon === null) {
            $defaultCity = $cities->firstWhere('name', 'Jakarta Selatan') ?? $cities->first();
            $lat = $defaultCity?->latitude ?? -6.2615;
            $lon = $defaultCity?->longitude ?? 106.8106;
            $selectedCityId = $selectedCityId ?? $defaultCity?->id;
            if ($selectedCityId) {
                $districts = District::where('city_id', $selectedCityId)->orderBy('name')->get();
            }
        }

        $radius = (float) $request->query('radius', 10); // default 10 km
        $selectedSubject = $request->query('subject');
        $selectedGrade = $request->query('grade'); // sd, smp, sma, ujian
        $minPrice = $request->filled('min_price') ? (int) $request->query('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (int) $request->query('max_price') : null;
        $minRating = $request->filled('rating') ? (float) $request->query('rating') : null;
        $selectedDay = $request->filled('day') ? (int) $request->query('day') : null;
        $sortBy = $request->query('sort', 'distance'); // distance, rating, price_low, price_high

        // Start Query with Verified Teachers only
        $query = TeacherProfile::with(['user', 'subjects', 'availabilitySlots', 'city', 'district', 'village'])
            ->verified();

        // Spatial radius search (filters by both parent radius and teacher max travel distance)
        if ($lat !== null && $lon !== null) {
            $query->withinRadius($lat, $lon, $radius);
        }

        // Subject filter
        if ($selectedSubject) {
            $query->whereHas('subjects', function ($q) use ($selectedSubject): void {
                if (is_numeric($selectedSubject)) {
                    $q->where('subjects.id', (int) $selectedSubject);
                } else {
                    $q->where('subjects.slug', $selectedSubject);
                }
            });
        }

        // Grade level filter
        if ($selectedGrade) {
            $query->whereHas('subjects', function ($q) use ($selectedGrade): void {
                $q->where('subjects.category', $selectedGrade)
                    ->orWhereJsonContains('teacher_subjects.grade_levels', $selectedGrade);
            });
        }

        // Price filter
        if ($minPrice !== null) {
            $query->where('hourly_rate', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('hourly_rate', '<=', $maxPrice);
        }

        // Rating filter
        if ($minRating !== null) {
            $query->where('rating_cache', '>=', $minRating);
        }

        // Availability day filter
        if ($selectedDay !== null) {
            $query->whereHas('availabilitySlots', function ($q) use ($selectedDay): void {
                $q->where('day_of_week', $selectedDay)
                    ->where('is_active', true);
            });
        }

        // Sorting
        switch ($sortBy) {
            case 'rating':
                $query->orderByDesc('rating_cache')->orderByDesc('reviews_count_cache');
                break;
            case 'price_low':
                $query->orderBy('hourly_rate');
                break;
            case 'price_high':
                $query->orderByDesc('hourly_rate');
                break;
            case 'distance':
            default:
                if ($lat !== null && $lon !== null) {
                    $query->orderBy('distance_km');
                } else {
                    $query->orderByDesc('rating_cache');
                }
                break;
        }

        $teachers = $query->paginate(8)->withQueryString();

        $isDashboard = $request->routeIs('parent.*')
            || $request->is('parent*')
            || $request->query('from') === 'dashboard'
            || (auth()->check() && (
                ($request->headers->get('referer') && str_contains((string) $request->headers->get('referer'), '/parent'))
                || str_contains(url()->previous(), '/parent')
                || auth()->user()->role === 'parent'
            ));

        return view('teachers.search', compact(
            'teachers',
            'subjects',
            'cities',
            'districts',
            'villages',
            'selectedCityId',
            'selectedDistrictId',
            'selectedVillageId',
            'locationName',
            'lat',
            'lon',
            'radius',
            'selectedSubject',
            'selectedGrade',
            'minPrice',
            'maxPrice',
            'minRating',
            'selectedDay',
            'sortBy',
            'isDashboard'
        ));
    }

    /**
     * Show teacher detail page.
     */
    public function show(TeacherProfile $teacherProfile, Request $request): View
    {
        $teacherProfile->load([
            'user',
            'subjects',
            'availabilitySlots',
            'reviews.parent',
        ]);

        if (! $teacherProfile->is_verified) {
            abort(404, 'Profil guru ini belum terverifikasi atau tidak tersedia untuk publik.');
        }

        // Calculate distance if student lat/lon provided
        $distance = null;
        $userLat = $request->query('lat');
        $userLon = $request->query('lon');
        if ($userLat && $userLon && $teacherProfile->latitude && $teacherProfile->longitude) {
            $distance = $this->bookingService->calculateDistance(
                $teacherProfile->latitude,
                $teacherProfile->longitude,
                (float) $userLat,
                (float) $userLon
            );
        }

        $isDashboard = $request->routeIs('parent.*')
            || $request->is('parent*')
            || $request->query('from') === 'dashboard'
            || (auth()->check() && (
                ($request->headers->get('referer') && str_contains((string) $request->headers->get('referer'), '/parent'))
                || str_contains(url()->previous(), '/parent')
                || auth()->user()->role === 'parent'
            ));

        return view('teachers.show', compact('teacherProfile', 'distance', 'isDashboard'));
    }
}
