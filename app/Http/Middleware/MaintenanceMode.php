<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (env('MAINTENANCE_MODE', false)) {

            // biar admin tetap bisa akses
            if ($request->is('admin*')) {
                return $next($request);
            }

            return response()->view('maintenance');
        }

        return $next($request);
    }
}
