<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Subject;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the Belajar Dong landing page.
     */
    public function index(Request $request): View
    {
        // Load popular subjects
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();

        // Load 6 featured verified tutors matching the reference UI
        $targetEmails = [
            'siti.nurhaliza@belajardong.id',
            'ahmad.rizky@belajardong.id',
            'dewi.lestari@belajardong.id',
            'budi.santoso@belajardong.id',
            'rina.amelia@belajardong.id',
            'fajar.nugroho@belajardong.id',
        ];

        $recommendedTeachers = TeacherProfile::with(['user', 'subjects', 'city'])
            ->whereHas('user', function ($q) use ($targetEmails) {
                $q->whereIn('email', $targetEmails);
            })
            ->get()
            ->sortBy(function ($t) use ($targetEmails) {
                $idx = array_search($t->user->email, $targetEmails);

                return $idx === false ? 99 : $idx;
            })
            ->values();

        // Fallback to any 6 verified tutors if seeder emails not found
        if ($recommendedTeachers->count() < 6) {
            $recommendedTeachers = TeacherProfile::with(['user', 'subjects', 'city'])
                ->verified()
                ->orderByDesc('rating_cache')
                ->take(6)
                ->get();
        }

        // Load Indonesian cities from database
        $cities = City::with('province')->orderBy('name')->get();

        return view('home', compact('subjects', 'recommendedTeachers', 'cities'));
    }

    /**
     * Static About page.
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Teacher landing page / info.
     */
    public function forTeachers(): View
    {
        return view('pages.for-teachers');
    }
}
