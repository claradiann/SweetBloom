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
Route::post('/api/auth/login', [AuthController::class, 'login'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/auth/register', [AuthController::class, 'register'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/auth/forgot-password', [AuthController::class, 'forgotPassword'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/auth/reset-password', [AuthController::class, 'resetPassword'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/auth/resend-confirmation', [AuthController::class, 'resendConfirmation'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/auth/logout', [AuthController::class, 'logout']);

// ── AUTH API (PROTECTED) ──────────────────────
Route::middleware('auth:sanctum')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::get('/api/auth/me', [AuthController::class, 'me']);
    Route::put('/api/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/api/auth/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/api/auth/account', [AuthController::class, 'deleteAccount']);
});

// ── PRODUCTS API (PUBLIC) ─────────────────────
Route::get('/api/products', [AuthController::class, 'getProducts']);

// ── KONFIRMASI EMAIL ──────────────────────────
Route::get('/api/auth/confirm/{token}', [AuthController::class, 'confirmEmail']);

// ── DASHBOARD ─────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ── PRODUCT PAGE ──────────────────────────────
Route::get('/product', function () {
    return view('product');
})->name('product');