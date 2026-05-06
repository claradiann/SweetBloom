<?php

use Illuminate\Support\Facades\Route;

// ── HALAMAN UTAMA ─────────────────────────────
Route::get('/', function () {
    return redirect('/login');
});

// ── AUTH PAGES ───────────────────────────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// ── DASHBOARD (sementara tanpa auth dulu) ───
Route::get('/dashboard', function () {
    return view('dashboard');
});