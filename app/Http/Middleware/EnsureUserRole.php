<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'))
                ->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman ini.');
        }

        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda sedang ditangguhkan. Silakan hubungi dukungan Belajar Dong.');
        }

        return $next($request);
    }
}
