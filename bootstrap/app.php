<?php

use App\Http\Middleware\EnsureUserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => EnsureUserRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'webhooks/*',
            'masuk',
            'daftar',
            'login',
            'register',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'Sesi kedaluwarsa atau token keamanan tidak valid. Silakan muat ulang halaman.',
                    ], 419);
                }

                $target = $request->header('Referer') ?: route('login');

                return redirect()->to($target)
                    ->withInput($request->except('password', 'password_confirmation', '_token'))
                    ->with('error', 'Sesi form Anda telah diperbarui untuk keamanan. Silakan kirim kembali data Anda.');
            }

            return null;
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
