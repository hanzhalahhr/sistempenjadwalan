<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Jadwal;
use App\Models\GenerateJadwal;
use App\Models\Prodi;


class JadwalController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Menampilkan daftar jadwal terakhir
    |--------------------------------------------------------------------------
    */

    public function index()
{
    $generate = GenerateJadwal::latest()->first();

    if (!$generate) {
        return response()->json([
            "message" => "Belum ada generate jadwal",
            "data" => []
        ]);
    }

    $totalJadwal = Jadwal::where(
        'generate_jadwal_id',
        $generate->id
    )->count();


    $data = [];

    // Tambahkan semua prodi
    $data[] = [
        "id" => 0,
        "prodi" => "Semua Prodi",
        "jumlah_jadwal" => $totalJadwal,
        "status" => $generate->status
    ];


    // Ambil prodi yang memiliki jadwal
    $prodi = Prodi::whereHas(
        'kelasPerkuliahan.jadwal',
        function ($query) use ($generate) {

            $query->where(
                'generate_jadwal_id',
                $generate->id
            );

        }
    )->get();



    foreach ($prodi as $item) {

        $jumlah = Jadwal::where(
            'generate_jadwal_id',
            $generate->id
        )
        ->whereHas(
            'kelasPerkuliahan',
            function ($query) use ($item) {

                $query->where(
                    'prodi_id',
                    $item->id
                );

            }
        )
        ->count();


        $data[] = [

            "id" => $item->id,

            "prodi" =>
                $item->nama_prodi
                ." "
                .$item->jenjang,

            "jumlah_jadwal" => $jumlah,

            "status" => $generate->status

        ];

    }

    return response()->json([

        "generate" => [

            "id" => $generate->id,

            "kode_generate" =>
                $generate->kode_generate,

            "tanggal" =>
                $generate->tanggal_generate,

            "status" =>
                $generate->status

        ],


        "data" => $data

    ]);

}



    public function detailProdi($id)
    {
        $generate = GenerateJadwal::latest()->first();

        if(!$generate){
            return response()->json([
                "message"=>"Belum ada generate jadwal"
            ],404);
        }


        $jadwal = Jadwal::with([

            'kelasPerkuliahan.mataKuliah',

            'kelasPerkuliahan.prodi',

            'ruangan',

            'dosen'

        ])
        ->where(
            'generate_jadwal_id',
            $generate->id
        )
        ->when($id != 0, function($query) use ($id){

            $query->whereHas(
                'kelasPerkuliahan',
                function($q) use ($id){

                    $q->where(
                        'prodi_id',
                        $id
                    );

                }
            );

        })
        ->get();



        return response()->json([

            "generate"=>[
                "id"=>$generate->id,
                "kode_generate"=>$generate->kode_generate,
                "tanggal"=>$generate->tanggal_generate
            ],


            "jadwal"=>$jadwal->map(function($item){

                return [

                    "id"=>$item->id,

                    "hari"=>$item->hari,

                    "jam_mulai"=>$item->jam_mulai,

                    "jam_selesai"=>$item->jam_selesai,


                    "mata_kuliah" =>
                        $item->kelasPerkuliahan
                        ->mataKuliah
                        ->nama_mk,


                    "prodi" =>
                        $item->kelasPerkuliahan
                        ->prodi
                        ->nama_prodi
                        ." "
                        .
                        $item->kelasPerkuliahan
                        ->prodi
                        ->jenjang,


                    "ruangan" =>
                        $item->ruangan
                        ->nama_ruangan,


                    "dosen" =>
                        $item->dosen
                        ->pluck('nama_dosen')

                ];

            })

        ]);

    }


    public function hasil($id)
    {

        $generate = GenerateJadwal::find($id);


        if(!$generate){

            return response()->json([
                "message"=>"Generate tidak ditemukan"
            ],404);

        }


        $jadwal = Jadwal::with([
            'kelasPerkuliahan.mataKuliah',
            'kelasPerkuliahan.prodi',
            'ruangan',
            'dosen'
        ])
        ->where(
            'generate_jadwal_id',
            $id
        )
        ->get();



        return response()->json([

            "generate"=>[
                "id"=>$generate->id,

                "kode_generate"=>
                    $generate->kode_generate,

                "tanggal_generate"=>
                    $generate->tanggal_generate,

                "status"=>
                    $generate->status
            ],


            "data"=>$jadwal

        ]);

    }




}