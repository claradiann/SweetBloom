<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ── HALAMAN UTAMA ─────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── AUTH PAGES (GET) ──────────────────────────
Route::get('/login',           fn() => view('auth.login'))->name('login');
Route::get('/register',        fn() => view('auth.register'))->name('register');
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('forgot-password');
Route::get('/reset-password',  fn() => view('auth.reset-password'))->name('reset-password');
Route::get('/dashboard',       fn() => view('dashboard'))->name('dashboard');
Route::get('/products',        fn() => view('product'))->name('products');

// ── API GROUP (no CSRF, no redirect) ──────────
Route::prefix('api')->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
])->group(function () {

    // Public endpoints
    Route::post('/auth/login',               [AuthController::class, 'login']);
    Route::post('/auth/register',            [AuthController::class, 'register']);
    Route::post('/auth/forgot-password',     [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password',      [AuthController::class, 'resetPassword']);
    Route::post('/auth/resend-confirmation', [AuthController::class, 'resendConfirmation']);
    Route::post('/auth/logout',              [AuthController::class, 'logout']);
    Route::get('/auth/confirm/{token}',      [AuthController::class, 'confirmEmail']);
    Route::get('/products',                  [AuthController::class, 'getProducts']);

    // Protected endpoints (butuh login)
        Route::middleware('auth:web')->group(function () {
        Route::get('/auth/me',               [AuthController::class, 'me']);
        Route::put('/auth/profile',          [AuthController::class, 'updateProfile']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
        Route::delete('/auth/account',       [AuthController::class, 'deleteAccount']);
    });
});