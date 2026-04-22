<?php

use App\Http\Controllers\AuditController;
use App\Http\Middleware\MfaMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', MfaMiddleware::class])
    ->prefix('audit')
    ->controller(AuditController::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD AUDIT
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', 'dashboard')
            ->name('audit.dashboard');

        /*
        |--------------------------------------------------------------------------
        | LOGS
        |--------------------------------------------------------------------------
        */

        Route::get('/logs', 'logs')
            ->name('audit.logs');

        /*
        |--------------------------------------------------------------------------
        | EXPORT LOG
        |--------------------------------------------------------------------------
        */

        Route::get('/export', 'export')
            ->name('audit.export');

    });
