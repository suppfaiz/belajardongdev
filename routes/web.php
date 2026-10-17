<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingProcessController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherSearchController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/untuk-guru', [HomeController::class, 'forTeachers'])->name('for-teachers');
Route::get('/cari-guru', [TeacherSearchController::class, 'index'])->name('teachers.search');
Route::get('/guru/{teacherProfile}', [TeacherSearchController::class, 'show'])->name('teachers.show');

// Regional Cascade API (Indonesia)
Route::prefix('api/regions')->name('regions.')->group(function (): void {
    Route::get('/provinces', [RegionController::class, 'provinces'])->name('provinces');
    Route::get('/cities', [RegionController::class, 'cities'])->name('cities');
    Route::get('/districts', [RegionController::class, 'districts'])->name('districts');
    Route::get('/villages', [RegionController::class, 'villages'])->name('villages');
});

// Authentication
Route::middleware('guest')->group(function (): void {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1');

    // Standard English path aliases so /login & /register never 404
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Private Document Download (Protected access)
Route::get('/dokumen/{document}/download', [DocumentDownloadController::class, 'download'])
    ->name('documents.download')
    ->middleware('auth');

// Booking Flow (Parent Only)
Route::middleware(['auth', 'role:parent'])->group(function (): void {
    Route::get('/guru/{teacherProfile}/booking', [BookingProcessController::class, 'create'])->name('bookings.create');
    Route::post('/guru/{teacherProfile}/booking', [BookingProcessController::class, 'store'])->name('bookings.store');
    Route::get('/booking/{booking}/checkout', [BookingProcessController::class, 'showCheckout'])->name('bookings.checkout');
    Route::post('/booking/{booking}/checkout', [BookingProcessController::class, 'processCheckout'])->name('bookings.process-checkout');
    Route::get('/booking/{booking}/instruksi-pembayaran', [BookingProcessController::class, 'showPaymentInstruction'])->name('bookings.payment-instruction');
    Route::post('/booking/{booking}/simulasi-bayar', [BookingProcessController::class, 'simulatePay'])->name('bookings.simulate-pay');
});

// Parent Dashboard
Route::prefix('parent')->name('parent.')->middleware(['auth', 'role:parent'])->group(function (): void {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/cari-guru', [TeacherSearchController::class, 'index'])->name('teachers.search');
    Route::get('/guru/{teacherProfile}', [TeacherSearchController::class, 'show'])->name('teachers.show');
    Route::get('/anak', [ParentDashboardController::class, 'students'])->name('students');
    Route::post('/anak', [ParentDashboardController::class, 'storeStudent'])->name('students.store');
    Route::put('/anak/{student}', [ParentDashboardController::class, 'updateStudent'])->name('students.update');
    Route::delete('/anak/{student}', [ParentDashboardController::class, 'deleteStudent'])->name('students.delete');

    Route::get('/pemesanan', [ParentDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/pemesanan/{booking}', [ParentDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/pemesanan/{booking}/confirm', [ParentDashboardController::class, 'confirmSessionCompleted'])->name('bookings.confirm');
    Route::post('/pemesanan/{booking}/review', [ParentDashboardController::class, 'storeReview'])->name('bookings.review');

    Route::get('/pembayaran', [ParentDashboardController::class, 'payments'])->name('payments');
    Route::get('/jadwal', [ParentDashboardController::class, 'schedule'])->name('schedule');
    Route::get('/progres', [ParentDashboardController::class, 'progress'])->name('progress');
    Route::get('/pesan', [ParentDashboardController::class, 'messages'])->name('messages');
    Route::get('/ulasan', [ParentDashboardController::class, 'reviews'])->name('reviews');
    Route::get('/profil', [ParentDashboardController::class, 'profile'])->name('profile');
    Route::put('/profil', [ParentDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Teacher Dashboard
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function (): void {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [TeacherDashboardController::class, 'profile'])->name('profile');
    Route::put('/profil', [TeacherDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dokumen', [TeacherDashboardController::class, 'documents'])->name('documents');
    Route::post('/dokumen', [TeacherDashboardController::class, 'uploadDocument'])->name('documents.upload');
    Route::post('/dokumen/pakta-integritas', [TeacherDashboardController::class, 'pledgeChildProtection'])->name('documents.pledge');
    Route::post('/dokumen/milestone-1', [TeacherDashboardController::class, 'saveMilestone1'])->name('documents.milestone1');
    Route::post('/dokumen/milestone-2', [TeacherDashboardController::class, 'saveMilestone2'])->name('documents.milestone2');
    Route::post('/dokumen/milestone-3', [TeacherDashboardController::class, 'saveMilestone3'])->name('documents.milestone3');
    Route::post('/dokumen/milestone-4', [TeacherDashboardController::class, 'saveMilestone4'])->name('documents.milestone4');

    Route::get('/mata-pelajaran', [TeacherDashboardController::class, 'subjects'])->name('subjects');
    Route::post('/mata-pelajaran', [TeacherDashboardController::class, 'updateSubjects'])->name('subjects.update');
    Route::get('/jadwal', [TeacherDashboardController::class, 'schedules'])->name('schedules');
    Route::post('/jadwal', [TeacherDashboardController::class, 'storeSchedule'])->name('schedules.store');
    Route::delete('/jadwal/{slot}', [TeacherDashboardController::class, 'deleteSchedule'])->name('schedules.delete');

    Route::get('/pemesanan', [TeacherDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/pemesanan/{booking}', [TeacherDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/pemesanan/{booking}/accept', [TeacherDashboardController::class, 'acceptBooking'])->name('bookings.accept');
    Route::post('/pemesanan/{booking}/reject', [TeacherDashboardController::class, 'rejectBooking'])->name('bookings.reject');
    Route::post('/pemesanan/{booking}/confirm-session', [TeacherDashboardController::class, 'confirmSession'])->name('bookings.confirm-session');

    Route::get('/penghasilan', [TeacherDashboardController::class, 'earnings'])->name('earnings');
    Route::get('/pencairan', [TeacherDashboardController::class, 'payouts'])->name('payouts');
    Route::post('/pencairan', [TeacherDashboardController::class, 'requestPayout'])->name('payouts.request');
    Route::get('/ulasan', [TeacherDashboardController::class, 'reviews'])->name('reviews');
});

// Admin Dashboard
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/guru', [AdminDashboardController::class, 'teachers'])->name('teachers');
    Route::get('/guru/{teacherProfile}', [AdminDashboardController::class, 'showTeacher'])->name('teachers.show');
    Route::post('/guru/{teacherProfile}/approve', [AdminDashboardController::class, 'approveTeacher'])->name('teachers.approve');
    Route::post('/guru/{teacherProfile}/reject', [AdminDashboardController::class, 'rejectTeacher'])->name('teachers.reject');

    Route::get('/pengguna', [AdminDashboardController::class, 'users'])->name('users');
    Route::post('/pengguna/{user}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggle');

    Route::get('/pemesanan', [AdminDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/pembayaran', [AdminDashboardController::class, 'payments'])->name('payments');
    Route::get('/pencairan', [AdminDashboardController::class, 'payouts'])->name('payouts');
    Route::post('/pencairan/{payout}/process', [AdminDashboardController::class, 'processPayout'])->name('payouts.process');

    Route::get('/mata-pelajaran', [AdminDashboardController::class, 'subjects'])->name('subjects');
    Route::post('/mata-pelajaran', [AdminDashboardController::class, 'storeSubject'])->name('subjects.store');

    Route::get('/pengaturan', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::post('/pengaturan', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');

    Route::get('/pengaduan', [AdminDashboardController::class, 'tickets'])->name('tickets');
    Route::post('/pengaduan/{ticket}/resolve', [AdminDashboardController::class, 'resolveTicket'])->name('tickets.resolve');

    Route::get('/audit-log', [AdminDashboardController::class, 'auditLogs'])->name('audit-logs');
});

// Webhook endpoints
Route::post('/api/webhooks/payment', [WebhookController::class, 'payment'])->name('webhooks.payment');
