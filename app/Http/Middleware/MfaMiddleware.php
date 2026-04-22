<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MfaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kalau belum login, biarkan auth middleware yang handle
        if (! Auth::check()) {
            return $next($request);
        }

        // Route MFA tidak boleh diblok
        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $user = Auth::user();

        // Kalau user belum punya MFA secret → arahkan setup
        if (! $user->mfa_secret) {
            return redirect()->route('mfa.setup');
        }

        $verified = session('mfa_verified', false);
        $secretHash = session('mfa_secret_hash');
        $verifiedAt = session('mfa_verified_at');

        // cek apakah secret berubah
        $secretValid = $secretHash === hash('sha256', $user->mfa_secret);

        // expire 8 jam
        $expired = ! $verifiedAt || (now()->timestamp - $verifiedAt) > 28800;

        if (! $verified || ! $secretValid || $expired) {

            // reset session MFA
            session()->forget([
                'mfa_verified',
                'mfa_secret_hash',
                'mfa_verified_at',
            ]);

            return redirect()->route('mfa.verify');
        }

        return $next($request);
    }

    private function shouldBypass(Request $request): bool
    {
        return $request->routeIs([
            'mfa.verify',
            'mfa.verify.post',
            'mfa.setup',
            'mfa.enable',
            'logout',
        ]);
    }
}
