<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = (bool) $request->boolean('remember');

        // Check credentials directly
        $attemptSuccess = Auth::attempt($credentials, $remember);

        // Fallback for demo aliases if direct attempt failed
        if (! $attemptSuccess) {
            $normalizedEmail = strtolower(trim($credentials['email']));
            $aliases = [
                'andi@belajardong.id' => 'andi.pratama@belajardong.id',
                'andi.pratama@belajardong.id' => 'andi@belajardong.id',
                'admin@belajaryuk.id' => 'admin@belajardong.id',
                'admin@belajardong.id' => 'admin@belajaryuk.id',
            ];

            if (isset($aliases[$normalizedEmail])) {
                $aliasCredentials = [
                    'email' => $aliases[$normalizedEmail],
                    'password' => $credentials['password'],
                ];
                $attemptSuccess = Auth::attempt($aliasCredentials, $remember);
            }
        }

        if ($attemptSuccess) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi admin Belajar Dong.',
                ]);
            }

            return $this->redirectBasedOnRole($user)
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Show registration page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:parent,teacher'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'role.required' => 'Pilih peran Anda (Orang Tua atau Guru).',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        if ($user->isTeacher()) {
            // Default teacher profile
            TeacherProfile::create([
                'user_id' => $user->id,
                'headline' => 'Guru Les Privat Berpengalaman',
                'bio' => 'Saya berkomitmen memberikan bimbingan belajar terbaik dan mendalam untuk siswa.',
                'hourly_rate' => 100000,
                'session_duration_minutes' => 90,
                'service_city' => 'Jakarta Selatan',
                'service_district' => 'Kebayoran Baru',
                'service_address' => 'Jakarta Selatan',
                'latitude' => -6.2442,
                'longitude' => 106.8006,
                'max_travel_distance_km' => 10,
                'is_verified' => false,
                'verification_status' => 'pending',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user)
            ->with('success', 'Akun Belajar Dong berhasil dibuat! Selamat bergabung.');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Role-based redirection helper.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('parent.dashboard');
    }
}
