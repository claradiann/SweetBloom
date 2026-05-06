<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ── HALAMAN UTAMA ─────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── AUTH PAGES (GET - tampilkan view) ─────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::get('/reset-password', function () {
    return view('auth.reset-password');
})->name('reset-password');

// ── AUTH API (POST) ───────────────────────────
Route::post('/api/auth/register', [AuthController::class, 'register']);
Route::post('/api/auth/login',    [AuthController::class, 'login']);
Route::post('/api/auth/logout',   [AuthController::class, 'logout']);
Route::post('/api/auth/forgot-password',   [AuthController::class, 'forgotPassword']);
Route::post('/api/auth/reset-password',    [AuthController::class, 'resetPassword']);
Route::post('/api/auth/resend-confirmation', [AuthController::class, 'resendConfirmation']);

// ── KONFIRMASI EMAIL (GET) ─────────────────────
Route::get('/api/auth/confirm/{token}', [AuthController::class, 'confirmEmail']);

// ── DASHBOARD ─────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');