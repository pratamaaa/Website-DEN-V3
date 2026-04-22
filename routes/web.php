<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\dapur\KuesionerController;
use App\Http\Controllers\dapur\PenggunaController;
use App\Http\Controllers\GratifikasiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RuedpPublicController;
use App\Http\Controllers\TugasfungsiPublicController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {

    Route::get('/', 'index');
    Route::get('/survey/{uuid}', [KuesionerController::class, 'survey'])->name('survey');
    Route::get('/berita', 'berita');
    Route::get('/berita/{slug}', 'berita_baca');
    Route::get('/berita-kategori/{cat}', 'berita_kategori');
    Route::post('/berita-cari', 'berita_cari');

    Route::get('/video', 'video');
    Route::post('/video-cari', 'video_cari');

    Route::get('/infografis', 'infografis');
    Route::post('/infografis-cari', 'infografis_cari');

    Route::get('/profil/{slug}', 'profilden');
    Route::get('/profildetail', 'profilden_detail');

    Route::get('/bacadokumen', 'bacadokumen');
    Route::get('/modalruedp', 'modalruedp');

    Route::get('/kontak', 'kontak');

    Route::get('/reformasi-birokrasi/{slug}', 'reformasi_birokrasi');
    Route::post('/reformasi-birokrasi-cari', 'reformasi_birokrasi_cari');

    Route::get('/publikasi/{slug}', 'publikasi');
    Route::post('/publikasi-cari', 'publikasi_cari');

    Route::prefix('pelaporan-gratifikasi')->name('gratifikasi.')->group(function () {
        Route::get('/', [GratifikasiController::class, 'create'])->name('form');
        Route::post('/', [GratifikasiController::class, 'store'])->name('store');
    });

    Route::get('/download', 'download');
    Route::get('/pdfviewer', 'pdfviewer');

    Route::get('/daftar-informasi-publik', 'daftar_informasi_publik');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');

    Route::get('/tugasfungsi', [TugasfungsiPublicController::class, 'index'])
        ->name('tugasfungsi.publik');

    Route::get('/ruedp-provinsi', [RuedpPublicController::class, 'index'])->name('ruedp.publik');
    Route::get('/ruedp-provinsi/map-data', [RuedpPublicController::class, 'mapData'])->name('ruedp.mapdata');

    Route::get('/force-change-password', [PenggunaController::class, 'forcePasswordForm']);
    Route::post('/force-change-password', [PenggunaController::class, 'forcePasswordUpdate']);
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/audit.php';
