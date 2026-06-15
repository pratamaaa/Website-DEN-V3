<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MfaMiddleware
{
    public function handle(Request $request, Closure $next)
{
    if (! Auth::check()) {
        return $next($request);
    }

    if ($this->shouldBypass($request)) {
        return $next($request);
    }

    // User SSO skip MFA lokal
    if (session('sso_login')) {
        return $next($request);
    }

    $user = Auth::user();

    if (! $user->mfa_secret) {
        return redirect()->route('mfa.setup');
    }

    $verified = session('mfa_verified', false);
    $secretHash = session('mfa_secret_hash');
    $verifiedAt = session('mfa_verified_at');

    $secretValid = $secretHash === hash('sha256', $user->mfa_secret);
    $expired = ! $verifiedAt || (now()->timestamp - $verifiedAt) > 28800;

    if (! $verified || ! $secretValid || $expired) {
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
