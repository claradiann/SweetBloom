<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ── Auth API ──────────────────────────────────────────────────
Route::prefix('api/auth')->group(function () {

    Route::post('/register',           [AuthController::class, 'register']);
    Route::get('/confirm/{token}',     [AuthController::class, 'confirmEmail']);
    Route::post('/login',              [AuthController::class, 'login']);
    Route::post('/logout',             [AuthController::class, 'logout']);
    Route::post('/resend-confirmation',[AuthController::class, 'resendConfirmation']);
    Route::post('/forgot-password',    [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',     [AuthController::class, 'resetPassword']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// ── SPA fallback — semua route lain → public/index.html ───────
Route::get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');