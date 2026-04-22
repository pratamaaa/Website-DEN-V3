<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class MfaLoginController extends Controller
{
    public function showVerifyForm()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (session()->has('force_password_reset_user')) {
            return redirect('/force-change-password');
        }

        return view('auth.mfa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // cek apakah akun terkunci
        if ($this->isLocked($user)) {

            $this->logMfa($user, $request, 'locked');

            $remaining = now()->diffInMinutes($user->mfa_locked_until);

            return back()->withErrors([
                'otp' => "Akun dikunci. Coba lagi dalam {$remaining} menit.",
            ]);
        }

        if (! $user->mfa_secret) {
            return redirect()->route('mfa.setup');
        }

        if (! $this->validateOtp($user, $request->otp)) {
            return $this->handleFailedAttempt($user, $request);
        }

        return $this->handleSuccessfulLogin($user, $request);
    }

    /*
    |--------------------------------------------------------------------------
    | OTP VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateOtp($user, $otp): bool
    {
        $google2fa = new Google2FA;

        return $google2fa->verifyKey(
            $user->mfa_secret,
            $otp,
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOCK CHECK
    |--------------------------------------------------------------------------
    */

    private function isLocked($user): bool
    {
        return $user->mfa_locked_until &&
               now()->lt($user->mfa_locked_until);
    }

    /*
    |--------------------------------------------------------------------------
    | FAILED ATTEMPT
    |--------------------------------------------------------------------------
    */

    private function handleFailedAttempt($user, $request)
    {
        $user->increment('mfa_attempts');

        $this->logMfa($user, $request, 'failed');

        if ($user->mfa_attempts >= 5) {

            $user->update([
                'mfa_attempts' => 0,
                'mfa_locked_until' => now()->addMinutes(5),
            ]);

            $this->logMfa($user, $request, 'locked');

            return back()->withErrors([
                'otp' => 'Terlalu banyak percobaan gagal. Akun dikunci selama 5 menit.',
            ]);
        }

        return back()->withErrors([
            'otp' => "Kode OTP salah. Percobaan ke-{$user->mfa_attempts} dari 5.",
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS LOGIN
    |--------------------------------------------------------------------------
    */

    private function handleSuccessfulLogin($user, $request)
    {
        $user->update([
            'mfa_attempts' => 0,
            'mfa_locked_until' => null,
        ]);

        $this->logMfa($user, $request, 'success');

        $request->session()->regenerate();

        // ambil data level
        $pengguna = DB::table('users_level')
            ->where('id_user_level', $user->id_user_level)
            ->first();

        session([
            'mfa_verified' => true,
            'mfa_secret_hash' => hash('sha256', $user->mfa_secret),
            'mfa_verified_at' => now()->timestamp,
            'sesNamalengkap' => $user->name,
            'sesLevel' => $pengguna->level,
            'sesLeveldesc' => $pengguna->keterangan,
        ]);

        return $this->redirectByRole($user);
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE REDIRECT
    |--------------------------------------------------------------------------
    */

    private function redirectByRole($user)
    {
        $role = DB::table('users_level')
            ->where('id_user_level', $user->id_user_level)
            ->value('level');

        return match ($role) {

            'operator-kuesioner' => redirect('/kuesioner/overview'),

            'admin-sistem',
            'manager-konten' => redirect()->route('dashboard'),

            'auditor' => redirect()->route('audit.dashboard'),

            default => abort(403, 'Role tidak dikenali'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | LOG MFA
    |--------------------------------------------------------------------------
    */

    private function logMfa($user, $request, $status)
    {
        DB::table('mfa_logs')->insert([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
