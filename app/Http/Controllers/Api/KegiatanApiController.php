<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KegiatanApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Prefix default: /api
|
*/

Route::get('/kegiatan', [KegiatanApiController::class, 'index'])
    ->name('api.kegiatan.index');

// contoh: /api/kegiatan/disetujui
Route::get('/kegiatan/disetujui', [KegiatanApiController::class, 'approved'])
    ->name('api.kegiatan.approved');

// contoh: /api/kegiatan/ormawa/{id_ormawa}
Route::get('/kegiatan/ormawa/{id_ormawa}', [KegiatanApiController::class, 'byOrmawa'])
    ->name('api.kegiatan.byOrmawa');
