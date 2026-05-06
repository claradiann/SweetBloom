<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ── HALAMAN UTAMA ─────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── AUTH PAGES (GET) ──────────────────────────
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('forgot-password');
Route::get('/reset-password', fn() => view('auth.reset-password'))->name('reset-password');
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/products', fn() => view('product'))->name('products');

// ── AUTH API (POST - tanpa CSRF) ──────────────
$noCsrf = [\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class];

Route::post('/api/auth/login', [AuthController::class, 'login'])->withoutMiddleware($noCsrf);
Route::post('/api/auth/register', [AuthController::class, 'register'])->withoutMiddleware($noCsrf);
Route::post('/api/auth/forgot-password', [AuthController::class, 'forgotPassword'])->withoutMiddleware($noCsrf);
Route::post('/api/auth/reset-password', [AuthController::class, 'resetPassword'])->withoutMiddleware($noCsrf);
Route::post('/api/auth/resend-confirmation', [AuthController::class, 'resendConfirmation'])->withoutMiddleware($noCsrf);
Route::post('/api/auth/logout', [AuthController::class, 'logout'])->withoutMiddleware($noCsrf);

// ── AUTH API (butuh login) ────────────────────
Route::middleware('auth:sanctum')->withoutMiddleware($noCsrf)->group(function () {
    Route::get('/api/auth/me', [AuthController::class, 'me']);
    Route::put('/api/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/api/auth/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/api/auth/account', [AuthController::class, 'deleteAccount']);
});

// ── PRODUK & KONFIRMASI ───────────────────────
Route::get('/api/products', [AuthController::class, 'getProducts']);
Route::get('/api/auth/confirm/{token}', [AuthController::class, 'confirmEmail']);