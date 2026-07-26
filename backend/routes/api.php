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


Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('prodi', ProdiController::class);
Route::apiResource('dosen', DosenController::class);
Route::apiResource('semesterakademik', SemesterAkademikController::class);
Route::apiResource('ruangan', RuanganController::class);
Route::apiResource('mata-kuliah', MataKuliahController::class);
Route::apiResource('kelasperkuliahan', KelasPerkuliahanController::class);
Route::apiResource('jadwal', JadwalController::class);
Route::apiResource('generatejadwal', GenerateJadwalController::class);




