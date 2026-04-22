<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GudangfungsiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        require_once app_path() . '/Helpers/Gudangfungsi.php';
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
