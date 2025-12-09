<?php

use Illuminate\Support\Facades\Route;

// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\VerifikasiKegiatanController;
use App\Http\Controllers\Admin\OrmawaUserController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;

// PUBLIC
use App\Http\Controllers\PublicKalenderController;
use App\Http\Controllers\PublicKegiatanController;

// ORMAWA
use App\Http\Controllers\Ormawa\DashboardController as OrmawaDashboardController;
use App\Http\Controllers\Ormawa\KegiatanController as OrmawaKegiatanController;
use App\Http\Controllers\Ormawa\DokumenController as OrmawaDokumenController;
use App\Http\Controllers\Ormawa\LaporanController as OrmawaLaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route auth dari Breeze (login, register, dll)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Kalender publik kegiatan yang disetujui
Route::get('/kalender', [PublicKalenderController::class, 'index'])
    ->name('kalender.public');

// Detail kegiatan publik (disetujui saja)
Route::get('/kegiatan/{id_kegiatan}', [PublicKegiatanController::class, 'show'])
    ->name('kegiatan.public.show');



/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
|
| Prefix: /admin
| Middleware: auth, role:admin
|
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Listing semua kegiatan
        Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])
            ->name('kegiatan.index');

        // Detail kegiatan
        Route::get('/kegiatan/{id_kegiatan}', [AdminKegiatanController::class, 'show'])
            ->name('kegiatan.show');

        // Verifikasi kegiatan (setuju/ditolak + catatan)
        Route::post('/kegiatan/{id_kegiatan}/verifikasi', [VerifikasiKegiatanController::class, 'store'])
            ->name('kegiatan.verifikasi.store');
        
        // halaman daftar akun ormawa
        Route::get('/ormawa-akun', [OrmawaUserController::class, 'index'])
            ->name('ormawa-akun.index');

        // form tambah akun
        Route::get('/ormawa-akun/create', [OrmawaUserController::class, 'create'])
            ->name('ormawa-akun.create');

        // simpan akun
        Route::post('/ormawa-akun', [OrmawaUserController::class, 'store'])
            ->name('ormawa-akun.store');

        // hapus akun
        Route::delete('/ormawa-akun/{user}', [OrmawaUserController::class, 'destroy'])
            ->name('ormawa-akun.destroy');

        Route::get('/kalender', [PublicKalenderController::class, 'index'])
            ->name('kalender.public');

        // Laporan kegiatan & statistik
        Route::get('/laporan', [AdminLaporanController::class, 'index'])
            ->name('laporan.index');

        // Atau kalau mau pakai PUT/PATCH:
        // Route::put('/kegiatan/{id_kegiatan}/verifikasi', [VerifikasiKegiatanController::class, 'update'])
        //     ->name('kegiatan.verifikasi.update');
    });



Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOrmawa()) {
            return redirect()->route('ormawa.dashboard');
        }
    }

    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ORMAWA ROUTES
|--------------------------------------------------------------------------
|
| Prefix: /ormawa
| Middleware: auth, role:ormawa
|
*/

Route::middleware(['auth', 'role:ormawa'])
    ->prefix('ormawa')
    ->name('ormawa.')
    ->group(function () {

        // Dashboard ORMAWA
        Route::get('/dashboard', [OrmawaDashboardController::class, 'index'])
            ->name('dashboard');

        // Laporan kegiatan & statistik
        Route::get('/laporan', [OrmawaLaporanController::class, 'index'])
            ->name('laporan.index');

        /*
         * KEGIATAN ORMAWA
         */

        // List kegiatan milik ormawa yang login
        Route::get('/kegiatan', [OrmawaKegiatanController::class, 'index'])
            ->name('kegiatan.index');

        // Form pengajuan kegiatan baru
        Route::get('/kegiatan/create', [OrmawaKegiatanController::class, 'create'])
            ->name('kegiatan.create');

        // Simpan pengajuan kegiatan
        Route::post('/kegiatan', [OrmawaKegiatanController::class, 'store'])
            ->name('kegiatan.store');

        // Detail satu kegiatan (boleh lihat selama milik dia atau publik)
        Route::get('/kegiatan/{id_kegiatan}', [OrmawaKegiatanController::class, 'show'])
            ->name('kegiatan.show');

        // Edit / Update / Delete – HARUS pemilik
        Route::middleware('owner.ormawa')->group(function () {

            // Form edit kegiatan
            Route::get('/kegiatan/{id_kegiatan}/edit', [OrmawaKegiatanController::class, 'edit'])
                ->name('kegiatan.edit');

            // Update kegiatan
            Route::put('/kegiatan/{id_kegiatan}', [OrmawaKegiatanController::class, 'update'])
                ->name('kegiatan.update');

            // Hapus kegiatan
            Route::delete('/kegiatan/{id_kegiatan}', [OrmawaKegiatanController::class, 'destroy'])
                ->name('kegiatan.destroy');

            /*
             * DOKUMEN KEGIATAN (only owner)
             */

            // List dokumen kegiatan
            Route::get('/kegiatan/{id_kegiatan}/dokumen', [OrmawaDokumenController::class, 'index'])
                ->name('dokumen.index');

            // Form upload dokumen
            Route::get('/kegiatan/{id_kegiatan}/dokumen/create', [OrmawaDokumenController::class, 'create'])
                ->name('dokumen.create');

            // Simpan dokumen
            Route::post('/kegiatan/{id_kegiatan}/dokumen', [OrmawaDokumenController::class, 'store'])
                ->name('dokumen.store');

            // Hapus dokumen
            Route::delete('/dokumen/{id_dokumen}', [OrmawaDokumenController::class, 'destroy'])
                ->name('dokumen.destroy');
        });
    });
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isOrmawa()) {
        return redirect()->route('ormawa.dashboard');
    }

    // fallback kalau nanti ada role lain
    return redirect('/');
})->name('dashboard');
