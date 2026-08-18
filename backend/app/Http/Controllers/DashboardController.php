<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\KelasPerkuliahan;
use App\Models\Ruangan;
use App\Models\Prodi;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Mengambil seluruh data statistik yang diperlukan
    | oleh Dashboard React.
    |
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        $totalKelas = KelasPerkuliahan::count();

        $totalDosen = Dosen::count();

        $totalMataKuliah = MataKuliah::count();

        $totalRuangan = Ruangan::count();


        /*
        |--------------------------------------------------------------------------
        | DISTRIBUSI MATA KULIAH PER SEMESTER
        |--------------------------------------------------------------------------
        |
        | Semester Ganjil:
        | 1, 3, 5, 7
        |
        | Semester Genap:
        | 2, 4, 6, 8
        |
        */

        $semesterGanjil = MataKuliah::whereIn(
            'semester',
            [1, 3, 5, 7]
        )->count();


        $semesterGenap = MataKuliah::whereIn(
            'semester',
            [2, 4, 6, 8]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | PROGRAM STUDI
        |--------------------------------------------------------------------------
        |
        | Untuk sementara status validasi mengikuti data
        | program studi yang tersedia di database.
        |
        */

        $programStudi = Prodi::select(
            'id',
            'nama_prodi',
            'jenjang'
        )
        ->orderBy('nama_prodi')
        ->get()
        ->map(function ($prodi) {

            return [
                'id' => $prodi->id,
                'nama' => $prodi->nama_prodi,
                'jenjang' => $prodi->jenjang,
                'status' => 'Terverifikasi'
            ];

        });


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'statistik' => [

                'total_kelas' => $totalKelas,

                'total_dosen' => $totalDosen,

                'total_mata_kuliah' => $totalMataKuliah,

                'total_ruangan' => $totalRuangan,

            ],


            'distribusi_semester' => [

                [
                    'semester' => 'Semester Ganjil',
                    'total' => $semesterGanjil,
                    'type' => $semesterGanjil > 0
                        ? 'active'
                        : 'empty'
                ],

                [
                    'semester' => 'Semester Genap',
                    'total' => $semesterGenap,
                    'type' => $semesterGenap > 0
                        ? 'active'
                        : 'empty'
                ]

            ],


            'program_studi' => $programStudi

        ]);
    }
}