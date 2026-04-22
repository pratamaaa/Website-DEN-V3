<?php

use App\Http\Controllers\AdminMfaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\MfaLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/kelola', [LoginController::class, 'index'])->name('login');
Route::post('/loginprocess', [LoginController::class, 'authenticate']);

Route::middleware('auth')->group(function () {

    Route::get('/mfa/setup', [MfaController::class, 'setup'])
        ->name('mfa.setup');

    Route::post('/mfa/enable', [MfaController::class, 'enable'])
        ->name('mfa.enable');

    Route::get('/mfa/verify', [MfaLoginController::class, 'showVerifyForm'])
        ->name('mfa.verify');

    Route::post('/mfa/verify', [MfaLoginController::class, 'verify'])
        ->name('mfa.verify.post');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/admin/mfa-reset', [AdminMfaController::class, 'index'])
        ->name('admin.mfa.index');

    Route::middleware(['auth'])->group(function () {

        Route::get('/admin/mfa-reset', [AdminMfaController::class, 'index'])
            ->name('admin.mfa.index');

        Route::post('/admin/mfa-reset/{id}', [AdminMfaController::class, 'reset'])
            ->name('admin.mfa.reset');

    });
});
