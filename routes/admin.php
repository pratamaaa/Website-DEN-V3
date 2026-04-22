<?php

use App\Http\Controllers\dapur\BeritaController;
use App\Http\Controllers\dapur\BeritakategoriController;
use App\Http\Controllers\dapur\DashboardController;
use App\Http\Controllers\dapur\DipController;
use App\Http\Controllers\dapur\GalerivideoController;
use App\Http\Controllers\dapur\GratifikasiController;
use App\Http\Controllers\dapur\IdentitasorganisasiController;
use App\Http\Controllers\dapur\ImagesliderController;
use App\Http\Controllers\dapur\InfografisController;
use App\Http\Controllers\dapur\InfopublikGroupingController;
use App\Http\Controllers\dapur\KuesionerController;
use App\Http\Controllers\dapur\LayananpublikController;
use App\Http\Controllers\dapur\MenuutamaController;
use App\Http\Controllers\dapur\OrganisasidenController;
use App\Http\Controllers\dapur\PenggunaController;
use App\Http\Controllers\dapur\PenggunalevelController;
use App\Http\Controllers\dapur\ProfildenController;
use App\Http\Controllers\dapur\PublikasiController;
use App\Http\Controllers\dapur\PublikasikategoriController;
use App\Http\Controllers\dapur\RbController;
use App\Http\Controllers\dapur\RbkategoriController;
use App\Http\Controllers\dapur\RuedpController;
use App\Http\Controllers\dapur\RuedpProvinsiController;
use App\Http\Controllers\dapur\RuedpStatusController;
use App\Http\Controllers\dapur\StrukturOrganisasiController;
use App\Http\Controllers\dapur\TugasfungsiController;
use App\Http\Controllers\StrukturOrganisasiPublicController;
use App\Http\Middleware\MfaMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', MfaMiddleware::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | HELPER CRUD
    |--------------------------------------------------------------------------
    */

    function crud($prefix, $controller, $name)
    {
        Route::prefix($prefix)->controller($controller)->group(function () use ($name) {
            Route::get('/', 'index')->name($name.'.index');
            Route::get('/getlist', 'getList')->name($name.'.list');
            Route::get('/add', 'add')->name($name.'.add');
            Route::post('/save', 'save')->name($name.'.save');
            Route::get('/edit', 'edit')->name($name.'.edit');
            Route::post('/saveupdate', 'saveupdate')->name($name.'.update');
            Route::post('/delete', 'delete')->name($name.'.delete');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA & PUBLIKASI
    |--------------------------------------------------------------------------
    */

    crud('kategoriberita', BeritakategoriController::class, 'kategoriberita');
    crud('dap/berita', BeritaController::class, 'berita');
    crud('dap/infopublik-grouping', InfopublikGroupingController::class, 'infopublik');
    crud('dap/dip', DipController::class, 'dip');
    crud('kategoripublikasi', PublikasikategoriController::class, 'kategoripublikasi');
    crud('dap/publikasi', PublikasiController::class, 'publikasi');
    crud('dap/imageslider', ImagesliderController::class, 'imageslider');
    crud('dap/ruedp', RuedpController::class, 'ruedp');
    crud('dap/infografis', InfografisController::class, 'infografis');
    crud('dap/galerivideo', GalerivideoController::class, 'galerivideo');
    crud('dap/layananpublik', LayananpublikController::class, 'layananpublik');

    // di bagian MEDIA & PUBLIKASI atau buat section baru RUED
    crud('dap/ruedp-status', RuedpStatusController::class, 'ruedpstatus');
    crud('dap/ruedp-provinsi', RuedpProvinsiController::class, 'ruedpprovinsi');
    /*
    |--------------------------------------------------------------------------
    | REFORMASI BIROKRASI
    |--------------------------------------------------------------------------
    */

    crud('dap/kategorirb', RbkategoriController::class, 'rbkategori');
    crud('dap/rb', RbController::class, 'rb');

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    crud('dap/identitasorganisasi', IdentitasorganisasiController::class, 'identitasorganisasi');
    crud('dap/profilden', ProfildenController::class, 'profilden');
    crud('dap/organisasiden', OrganisasidenController::class, 'organisasiden');
    crud('dap/strukturorganisasi', StrukturOrganisasiController::class, 'strukturorganisasi');

    Route::prefix('dap/tugasfungsi')
        ->controller(TugasfungsiController::class)
        ->group(function () {
            Route::get('/', 'index')->name('tugasfungsi.index');
            Route::post('/save', 'save')->name('tugasfungsi.save');
        });

    /*
    |--------------------------------------------------------------------------
    | MENU
    |--------------------------------------------------------------------------
    */

    crud('dap/menuutama', MenuutamaController::class, 'menuutama');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    crud('dap/levelpengguna', PenggunalevelController::class, 'levelpengguna');

    crud('dap/pengguna', PenggunaController::class, 'pengguna');

    Route::prefix('dap/pengguna')
        ->controller(PenggunaController::class)
        ->group(function () {
            Route::get('/', 'index')->name('pengguna.index');
            Route::get('/getlist', 'getlist')->name('pengguna.list');
            Route::get('/add', 'add')->name('pengguna.add');
            Route::post('/save', 'save')->name('pengguna.save');
            Route::get('/edit', 'edit')->name('pengguna.edit');
            Route::post('/saveupdate', 'saveupdate')->name('pengguna.update');
            Route::post('/delete', 'delete')->name('pengguna.delete');
            Route::get('/gantipassword', 'gantipassword')->name('pengguna.password');
            Route::post('/passwordupdate', 'passwordupdate')->name('pengguna.password.update');
        });

    /*
    |--------------------------------------------------------------------------
    | KUESIONER
    |--------------------------------------------------------------------------
    */

    Route::prefix('kuesioner')
        ->controller(KuesionerController::class)
        ->group(function () {

            Route::get('/overview', 'overview')->name('kuesioner.overview');

            // Template Jawaban
            Route::get('/manajemen-template-jawaban', 'manajemen_template_jawaban')->name('kuesioner.template');
            Route::get('/manajemen-template-jawaban-list', 'manajemen_template_jawaban_list')->name('kuesioner.template.list');
            Route::get('/manajemen-template-jawaban-create', 'manajemen_template_jawaban_create')->name('kuesioner.template.create');
            Route::post('/manajemen-template-jawaban-save', 'manajemen_template_jawaban_save')->name('kuesioner.template.save');
            Route::get('/manajemen-template-jawaban-edit/{uuid}', 'manajemen_template_jawaban_edit')->name('kuesioner.template.edit');
            Route::post('/manajemen-template-jawaban-update/{uuid}', 'manajemen_template_jawaban_update')->name('kuesioner.template.update');
            Route::post('/manajemen-template-jawaban-delete', 'manajemen_template_jawaban_delete')->name('kuesioner.template.delete');
            Route::post('/manajemen-layanan-delete/{uuid}', [KuesionerController::class, 'manajemen_layanan_delete']);
            // Manajemen Layanan
            Route::get('/manajemen-layanan', 'manajemen_layanan')->name('kuesioner.layanan');
            Route::get('/manajemen-layanan-list', 'manajemen_layanan_list')->name('kuesioner.layanan.list');
            Route::get('/manajemen-layanan-create', 'manajemen_layanan_create')->name('kuesioner.layanan.create');
            Route::post('/manajemen-layanan-create', 'manajemen_layanan_save')->name('kuesioner.layanan.save');
            Route::get('/manajemen-layanan-edit/{uuid}', 'manajemen_layanan_edit')->name('kuesioner.layanan.edit');
            Route::post('/manajemen-layanan-update/{uuid}', 'manajemen_layanan_update')->name('kuesioner.layanan.update');
            Route::get('/manajemen-layanan-getjawaban/{groupUuid}', 'gettemplateoptions')->name('kuesioner.layanan.template');

            // Responden
            Route::get('/data-responden', 'data_responden')->name('kuesioner.responden');
            Route::get('/data-responden-list', 'data_responden_list')->name('kuesioner.responden.list');
            Route::get('/data-responden-detail/{uuid}', 'data_responden_detail')->name('kuesioner.responden.detail');

            // Hasil Analisa
            Route::get('/hasil-analisa', 'hasil_analisa')->name('kuesioner.analisa');
            Route::get('/hasil-analisa-list', 'hasil_analisa_list')->name('kuesioner.analisa.list');
            Route::get('/hasil-analisa-list-ikl', 'hasil_analisa_list_ikl')->name('kuesioner.analisa.ikl');
            Route::get('/hasil-analisa-list-matriks', 'hasil_analisa_list_matriks')->name('kuesioner.analisa.matriks');
        });
});

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/struktur-organisasi', [StrukturOrganisasiPublicController::class, 'index'])
    ->name('strukturorganisasi');

/*
|----------------------------------------------------------------------
| GRATIFIKASI
|----------------------------------------------------------------------
*/

Route::prefix('dap/gratifikasi')
    ->name('admin.gratifikasi.')
    ->controller(GratifikasiController::class)
    ->middleware(['auth', MfaMiddleware::class]) // tambahkan jika perlu auth
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/getlist', 'getList')->name('list'); // ← pindah SEBELUM /{id}
        Route::get('/{id}', 'show')->name('show');       // ← dynamic SETELAH static
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
