<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $role = Auth::user()->userLevel?->level ?? '';
                $view->with('role', $role);
            }
        });
    }
}
