<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\SemesterAkademikController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\KelasPerkuliahanController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\GenerateJadwalController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/


Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::post(
    '/login',
    [
        AuthController::class,
        'login'
    ]
);



/*
|--------------------------------------------------------------------------
| MASTER DATA
|--------------------------------------------------------------------------
*/


Route::apiResource(
    'prodi',
    ProdiController::class
);


Route::apiResource(
    'dosen',
    DosenController::class
);


Route::apiResource(
    'semester-akademik',
    SemesterAkademikController::class
);


Route::apiResource(
    'ruangan',
    RuanganController::class
);


Route::apiResource(
    'mata-kuliah',
    MataKuliahController::class
);


Route::apiResource(
    'kelasperkuliahan',
    KelasPerkuliahanController::class
);



/*
|--------------------------------------------------------------------------
| JADWAL
|--------------------------------------------------------------------------
*/


// HASIL DETAIL JADWAL
// taruh sebelum apiResource supaya tidak bentrok
Route::get(
    '/jadwal/hasil/{id}',
    [
        JadwalController::class,
        'hasil'
    ]
);


Route::get(
    '/jadwal/prodi/{id}',
    [
        JadwalController::class,
        'detailProdi'
    ]
);


// DATA UNTUK HALAMAN JADWAL
Route::apiResource(
    'jadwal',
    JadwalController::class
);


/*
|--------------------------------------------------------------------------
| GENERATE JADWAL
|--------------------------------------------------------------------------
*/


Route::apiResource(
    'generatejadwal',
    GenerateJadwalController::class
);


Route::post(
    '/generatejadwal/run',
    [
        GenerateJadwalController::class,
        'run'
    ]
);



/*
|--------------------------------------------------------------------------
| EXPORT
|--------------------------------------------------------------------------
*/


Route::get(
    '/jadwal/export/{id}',
    [
        GenerateJadwalController::class,
        'exportExcel'
    ]
);


Route::get(
    '/jadwal/pdf/{id}',
    [
        GenerateJadwalController::class,
        'exportPdf'
    ]
);