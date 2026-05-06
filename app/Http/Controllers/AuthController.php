<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeMail;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    private int $maxAttempts;
    private int $lockMinutes;

    public function __construct()
    {
        $this->maxAttempts = (int) env('MAX_LOGIN_ATTEMPTS', 5);
        $this->lockMinutes = (int) env('LOCK_TIME_MINUTES', 15);
    }

    // ── Register ──────────────────────────────────────────────
    public function register(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $name            = trim($data['name'] ?? '');
        $email           = strtolower(trim($data['email'] ?? ''));
        $password        = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? null;

        if (!$name || !$email || !$password) {
            return response()->json(['success' => false, 'message' => 'Nama, email, dan password wajib diisi.'], 400);
        }
        if (strlen($name) < 2 || strlen($name) > 100) {
            return response()->json(['success' => false, 'message' => 'Nama harus antara 2 sampai 100 karakter.'], 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Format email tidak valid.'], 400);
        }
        if (strlen($password) < 8) {
            return response()->json(['success' => false, 'message' => 'Password minimal 8 karakter.'], 400);
        }
        if (!preg_match('/(?=.*[A-Za-z])(?=.*\d)/', $password)) {
            return response()->json(['success' => false, 'message' => 'Password harus mengandung huruf dan angka.'], 400);
        }
        if ($confirmPassword !== null && $confirmPassword !== $password) {
            return response()->json(['success' => false, 'message' => 'Konfirmasi password tidak cocok.'], 400);
        }

        if (User::where('email', $email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Email ini sudah terdaftar. Silakan login.'], 409);
        }

       $user = User::create([
            'name'         => $name,
            'email'        => $email,
            'password'     => Hash::make($password),
            'role'         => 'customer',
            'is_confirmed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan login.',
        ], 201);
    }

    // ── Confirm Email ─────────────────────────────────────────
    public function confirmEmail(string $token)
    {
        if (!$token || strlen($token) !== 64) {
            return redirect('/login?error=invalid_token');
        }

        $user = User::where('confirm_token', $token)->first();

        if (!$user) return redirect('/login?error=invalid_token');
        if ($user->is_confirmed) return redirect('/login?info=already_confirmed');
        if (Carbon::now()->gt($user->confirm_expires)) return redirect('/login?error=token_expired');

        $user->update([
            'is_confirmed'    => true,
            'confirm_token'   => null,
            'confirm_expires' => null,
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user->name, url('/login')));
        } catch (\Exception $e) {}

        return redirect('/login?success=confirmed');
    }

    // ── Login ─────────────────────────────────────────────────
    public function login(Request $request): JsonResponse
    {
        $data     = $request->json()->all();
        $email    = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return response()->json(['success' => false, 'message' => 'Email dan password wajib diisi.'], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->logLogin(null, $email, $request, 'failed');
            Hash::make('dummy'); // cegah user enumeration
            return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 401);
        }

        // Cek dikunci
        if ($user->locked_until && Carbon::now()->lt($user->locked_until)) {
            $remaining = Carbon::now()->diffInMinutes($user->locked_until, false) * -1;
            $remaining = (int) ceil(abs($remaining));
            $this->logLogin($user->id, $email, $request, 'locked');
            return response()->json([
                'success' => false,
                'message' => "Akun dikunci sementara. Coba lagi dalam $remaining menit.",
                'code'    => 'ACCOUNT_LOCKED',
            ], 429);
        }

        // Cek konfirmasi
        if (!$user->is_confirmed) {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum dikonfirmasi. Cek email kamu untuk link konfirmasi.',
                'code'    => 'NOT_CONFIRMED',
            ], 403);
        }

        // Verifikasi password
        if (!Hash::check($password, $user->password)) {
            $newAttempts = $user->login_attempts + 1;

            if ($newAttempts >= $this->maxAttempts) {
                $user->update([
                    'login_attempts' => $newAttempts,
                    'locked_until'   => Carbon::now()->addMinutes($this->lockMinutes),
                ]);
                $this->logLogin($user->id, $email, $request, 'locked');
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan gagal. Akun dikunci selama {$this->lockMinutes} menit.",
                    'code'    => 'ACCOUNT_LOCKED',
                ], 429);
            }

            $user->update(['login_attempts' => $newAttempts]);
            $this->logLogin($user->id, $email, $request, 'failed');
            $remaining = $this->maxAttempts - $newAttempts;
            return response()->json([
                'success' => false,
                'message' => "Email atau password salah. $remaining percobaan tersisa sebelum akun dikunci.",
            ], 401);
        }

        // Login berhasil
        $user->update(['login_attempts' => 0, 'locked_until' => null]);
        $this->logLogin($user->id, $email, $request, 'success');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => "Selamat datang kembali, {$user->name}! 🌸",
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
            'token'   => $token,
        ])->cookie('token', $token, 60 * 24 * 7, '/', null, false, true);
    }

    // ── Logout ────────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['success' => true, 'message' => 'Berhasil logout. Sampai jumpa! 👋'])
            ->withoutCookie('token');
    }

    // ── Resend Confirmation ───────────────────────────────────
    public function resendConfirmation(Request $request): JsonResponse
    {
        $email = strtolower(trim($request->json('email', '')));
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email wajib diisi.'], 400);
        }

        $successMsg = 'Jika email terdaftar dan belum dikonfirmasi, link baru telah dikirim.';
        $user = User::where('email', $email)->first();

        if (!$user || $user->is_confirmed) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        $confirmToken = bin2hex(random_bytes(32));
        $user->update([
            'confirm_token'   => $confirmToken,
            'confirm_expires' => Carbon::now()->addDay(),
        ]);

        $confirmUrl = url('/api/auth/confirm/' . $confirmToken);
        Mail::to($email)->send(new ConfirmationMail($user->name, $confirmUrl));

        return response()->json(['success' => true, 'message' => $successMsg]);
    }

    // ── Forgot Password ───────────────────────────────────────
    public function forgotPassword(Request $request): JsonResponse
    {
        $email = strtolower(trim($request->json('email', '')));
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email wajib diisi.'], 400);
        }

        $successMsg = "Jika email $email terdaftar, link reset password telah dikirim. Cek inbox kamu.";
        $user = User::where('email', $email)->where('is_confirmed', true)->first();

        if (!$user) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        $resetToken = bin2hex(random_bytes(32));
        $user->update([
            'reset_token'   => $resetToken,
            'reset_expires' => Carbon::now()->addHour(),
        ]);

        $resetUrl = url('/reset-password?token=' . $resetToken);
        Mail::to($email)->send(new ResetPasswordMail($user->name, $resetUrl));

        return response()->json(['success' => true, 'message' => $successMsg]);
    }

    // ── Reset Password ────────────────────────────────────────
    public function resetPassword(Request $request): JsonResponse
    {
        $data            = $request->json()->all();
        $token           = $data['token'] ?? '';
        $password        = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? null;

        if (!$token || !$password) {
            return response()->json(['success' => false, 'message' => 'Token dan password baru wajib diisi.'], 400);
        }
        if (strlen($password) < 8) {
            return response()->json(['success' => false, 'message' => 'Password minimal 8 karakter.'], 400);
        }
        if (!preg_match('/(?=.*[A-Za-z])(?=.*\d)/', $password)) {
            return response()->json(['success' => false, 'message' => 'Password harus mengandung huruf dan angka.'], 400);
        }
        if ($confirmPassword !== null && $confirmPassword !== $password) {
            return response()->json(['success' => false, 'message' => 'Konfirmasi password tidak cocok.'], 400);
        }

        $user = User::where('reset_token', $token)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Link reset password tidak valid atau sudah digunakan.'], 400);
        }
        if (Carbon::now()->gt($user->reset_expires)) {
            return response()->json([
                'success' => false,
                'message' => 'Link reset password sudah kadaluarsa. Silakan minta link baru.',
                'code'    => 'TOKEN_EXPIRED',
            ], 400);
        }

        $user->update([
            'password'       => Hash::make($password),
            'reset_token'    => null,
            'reset_expires'  => null,
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah! Silakan login dengan password baru kamu. 🌸',
        ]);
    }

    // ── Get Me ────────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'user' => $request->user()]);
    }

    // ── Helper: log login ─────────────────────────────────────
    private function logLogin(?int $userId, string $email, Request $request, string $status): void
    {
        try {
            LoginLog::create([
                'user_id'    => $userId,
                'email'      => $email,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 512),
                'status'     => $status,
            ]);
        } catch (\Exception $e) {}
    }

    // ── Update Profile ────────────────────────────────────────
public function updateProfile(Request $request): JsonResponse
{
    $name = trim($request->json('name', ''));

    if (!$name || strlen($name) < 2 || strlen($name) > 100) {
        return response()->json(['success' => false, 'message' => 'Nama harus antara 2 sampai 100 karakter.'], 400);
    }

    $request->user()->update(['name' => $name]);

    return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
}

// ── Change Password ───────────────────────────────────────
public function changePassword(Request $request): JsonResponse
{
    $data            = $request->json()->all();
    $currentPassword = $data['currentPassword'] ?? '';
    $newPassword     = $data['newPassword'] ?? '';
    $confirmPassword = $data['confirmPassword'] ?? '';

    if (!$currentPassword || !$newPassword || !$confirmPassword) {
        return response()->json(['success' => false, 'message' => 'Semua field wajib diisi.'], 400);
    }
    if (!Hash::check($currentPassword, $request->user()->password)) {
        return response()->json(['success' => false, 'message' => 'Password saat ini tidak sesuai.'], 401);
    }
    if (strlen($newPassword) < 8) {
        return response()->json(['success' => false, 'message' => 'Password baru minimal 8 karakter.'], 400);
    }
    if (!preg_match('/(?=.*[A-Za-z])(?=.*\d)/', $newPassword)) {
        return response()->json(['success' => false, 'message' => 'Password harus mengandung huruf dan angka.'], 400);
    }
    if ($newPassword !== $confirmPassword) {
        return response()->json(['success' => false, 'message' => 'Konfirmasi password tidak cocok.'], 400);
    }

    $request->user()->update(['password' => Hash::make($newPassword)]);

    return response()->json(['success' => true, 'message' => 'Password berhasil diubah!']);
}

// ── Delete Account ────────────────────────────────────────
public function deleteAccount(Request $request): JsonResponse
{
    $user = $request->user();
    $user->tokens()->delete();
    $user->delete();

    return response()->json(['success' => true, 'message' => 'Akun berhasil dihapus.'])
        ->withoutCookie('token');
}
}