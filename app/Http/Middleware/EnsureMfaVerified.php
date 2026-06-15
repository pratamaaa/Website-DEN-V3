<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMfaVerified
{
    public function handle(Request $request, Closure $next)
{
    if (Auth::check() && Auth::user()->mfa_enabled) {
        // User SSO skip MFA lokal
        if (session('sso_login')) {
            return $next($request);
        }

        if (! session('mfa_verified')) {
            return redirect()->route('mfa.verify');
        }
    }

    return $next($request);
}
}
