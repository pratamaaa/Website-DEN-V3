<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Logincheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userlevel): Response
    {
        if (!Auth::check()){
            return redirect('login');
        }
        $user = Auth::user();

        if ($user->level == $userlevel)
            return $next($request);

        // return $next($request);

        return redirect('login')->with('error', 'Anda tidak mempunyai akses');
    }
}
