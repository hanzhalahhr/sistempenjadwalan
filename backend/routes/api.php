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
use App\Http\Controllers\SlotWaktuKuliahController;
use App\Http\Controllers\KonfigurasiJadwalController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\GenerateJadwalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
| Tidak membutuhkan token.
*/

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| USER DARI TOKEN
|--------------------------------------------------------------------------
| Untuk testing token.
*/

Route::middleware('auth:sanctum')->get(
    '/user',
    function (Request $request) {

        return response()->json([
            'user' => $request->user()
        ]);

    }
);


/*
|--------------------------------------------------------------------------
| PUBLIC / MASTER DATA
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
| SLOT WAKTU KULIAH
|--------------------------------------------------------------------------
*/

Route::apiResource(
    'slot-waktu-kuliah',
    SlotWaktuKuliahController::class
);


/*
|--------------------------------------------------------------------------
| KONFIGURASI JADWAL
|--------------------------------------------------------------------------
*/

Route::get(
    '/konfigurasi-jadwal/{id}',
    [
        KonfigurasiJadwalController::class,
        'show'
    ]
);

Route::post(
    '/konfigurasi-jadwal',
    [
        KonfigurasiJadwalController::class,
        'store'
    ]
);


/*
|--------------------------------------------------------------------------
| JADWAL
|--------------------------------------------------------------------------
*/

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
    '/jadwal/export/prodi/{id}',
    [
        JadwalController::class,
        'exportProdiExcel'
    ]
);

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


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
|
| Semua endpoint di bawah ini membutuhkan token Sanctum.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | UBAH PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    );

});