<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // =========================
        // VALIDASI FORM
        // =========================
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Captcha wajib diisi',
        ]);

        // =========================
        // VALIDASI CAPTCHA GOOGLE
        // =========================
        $captcha = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (! $captcha->json('success')) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'Verifikasi captcha gagal'])
                ->withInput($request->except('password'));
        }

        // =========================
        // LOGIN ATTEMPT
        // =========================
        if (! Auth::attempt($request->only('username', 'password'))) {

            // LOG LOGIN GAGAL
            DB::table('audit_logs')->insert([
                'user_id' => null,
                'activity' => 'LOGIN_FAILED',
                'module' => 'auth',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return back()->withErrors([
                'username' => 'Username atau password salah',
            ])->withInput($request->except('password'));
        }

        // Regenerate session (standard Laravel)
        $request->session()->regenerate();

        // Reset MFA status tiap login
        session(['mfa_verified' => false]);

        $user = Auth::user();

        // =========================
        // 🔒 CEK PASSWORD EXPIRED (SEBELUM MFA)
        // =========================
        if ($user->password_changed_at) {

            $expiredDays = 1;

            if (Carbon::parse($user->password_changed_at)->addDays($expiredDays)->isPast()) {

                Auth::logout();

                $request->session()->regenerate();

                session([
                    'force_password_reset_user' => $user->id,
                ]);

                return redirect('/force-change-password');
            }

        } else {
            // belum pernah set password
            Auth::logout();

            $request->session()->regenerate(); // pindahin ke atas

            session([
                'force_password_reset_user' => $user->id,
            ]);

            $request->session()->regenerate(); // ini aman

            return redirect('/force-change-password')
                ->with('warning', 'Silakan set password Anda.');
        }

        // =========================
        // AUDIT LOG LOGIN
        // =========================
        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'activity' => 'LOGIN',
            'module' => 'auth',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        // =========================
        // AMBIL DATA Auth::logout();ROLE
        // =========================
        $pengguna = DB::table('users as us')
            ->join('users_level as lv', 'us.id_user_level', '=', 'lv.id_user_level')
            ->where('us.id', $user->id)
            ->select('us.name', 'lv.level', 'lv.keterangan')
            ->first();

        if (! $pengguna) {
            Auth::logout();

            session([
                'force_password_reset_user' => $user->id,
            ]);

            $request->session()->regenerate();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'User tidak ditemukan atau tidak memiliki akses',
                ]);
        }

        // Simpan info user ke session
        Session::put('sesNamalengkap', $pengguna->name);
        Session::put('sesLevel', $pengguna->level);
        Session::put('sesLeveldesc', $pengguna->keterangan);

        // =========================
        // 🔥 SEMUA ROLE WAJIB MFA
        // =========================
        return redirect()->route('mfa.verify');
    }

    public function logout(Request $request)
    {
        audit_log('LOGOUT', 'auth');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
