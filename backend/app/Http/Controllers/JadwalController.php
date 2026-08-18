<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Jadwal;
use App\Models\GenerateJadwal;
use App\Models\Prodi;
use App\Exports\JadwalExport;
use Maatwebsite\Excel\Facades\Excel;


class JadwalController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN DAFTAR JADWAL GENERATE TERBARU
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


        /*
        |--------------------------------------------------------------------------
        | TOTAL SELURUH JADWAL
        |--------------------------------------------------------------------------
        */

        $totalJadwal = Jadwal::where(
            'generate_jadwal_id',
            $generate->id
        )->count();


        $data = [];


        /*
        |--------------------------------------------------------------------------
        | SEMUA PRODI
        |--------------------------------------------------------------------------
        */

        $data[] = [

            "id" => 0,

            "prodi" => "Semua Prodi",

            "jumlah_jadwal" => $totalJadwal,

            "status" => $generate->status

        ];


        /*
        |--------------------------------------------------------------------------
        | AMBIL PRODI YANG MEMILIKI JADWAL
        |--------------------------------------------------------------------------
        */

        $prodi = Prodi::whereHas(
            'kelasPerkuliahan.jadwal',
            function ($query) use ($generate) {

                $query->where(
                    'generate_jadwal_id',
                    $generate->id
                );

            }
        )->get();


        /*
        |--------------------------------------------------------------------------
        | DATA PER PRODI
        |--------------------------------------------------------------------------
        */

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
                    . " "
                    . $item->jenjang,

                "jumlah_jadwal" => $jumlah,

                "status" => $generate->status

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

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



    /*
    |--------------------------------------------------------------------------
    | DETAIL JADWAL BERDASARKAN PRODI
    |--------------------------------------------------------------------------
    */

    public function detailProdi($id)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL GENERATE TERBARU
        |--------------------------------------------------------------------------
        */

        $generate = GenerateJadwal::latest()->first();


        if (!$generate) {

            return response()->json([

                "message" =>
                    "Belum ada generate jadwal"

            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK PRODI
        |--------------------------------------------------------------------------
        */

        if ((int) $id === 0) {

            $namaProdi = "Semua Prodi";

        } else {

            $prodi = Prodi::find($id);


            if (!$prodi) {

                return response()->json([

                    "message" =>
                        "Program studi tidak ditemukan",

                    "prodi_id" =>
                        $id

                ], 404);
            }


            $namaProdi =
                $prodi->nama_prodi
                . " "
                . $prodi->jenjang;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL
        |--------------------------------------------------------------------------
        |
        | DOSEN DIAMBIL MELALUI:
        |
        | jadwal
        |   ↓
        | kelasPerkuliahan
        |   ↓
        | dosen
        |
        */

        $jadwal = Jadwal::with([

            'kelasPerkuliahan.mataKuliah',

            'kelasPerkuliahan.prodi',

            'kelasPerkuliahan.dosen',

            'ruangan'

        ])

        ->where(
            'generate_jadwal_id',
            $generate->id
        )

        ->when(
            (int) $id !== 0,
            function ($query) use ($id) {

                $query->whereHas(

                    'kelasPerkuliahan',

                    function ($q) use ($id) {

                        $q->where(
                            'prodi_id',
                            $id
                        );
                    }
                );
            }
        )

        ->orderByRaw("
            FIELD(
                hari,
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            )
        ")

        ->orderBy('jam_mulai')

        ->get();


        /*
        |--------------------------------------------------------------------------
        | FORMAT DATA JADWAL
        |--------------------------------------------------------------------------
        */

        $dataJadwal = $jadwal->map(function ($item) {

            return [

                "id" =>
                    $item->id,

                "hari" =>
                    $item->hari,

                "jam_mulai" =>
                    $item->jam_mulai,

                "jam_selesai" =>
                    $item->jam_selesai,

                "mata_kuliah" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->mataKuliah
                    )->nama_mk,

                "kelas" =>
                    optional(
                        $item->kelasPerkuliahan
                    )->nama_kelas,

                "prodi" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->prodi
                    )->nama_prodi,

                "jenjang" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->prodi
                    )->jenjang,

                "ruangan" =>
                    optional(
                        $item->ruangan
                    )->nama_ruangan,

                "dosen" =>
                    optional(
                        $item->kelasPerkuliahan
                    )
                    ?->dosen
                    ?->pluck('nama_dosen')
                    ?->values()
                    ?->toArray()
                    ?? []

            ];

        });


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            "generate" => [

                "id" =>
                    $generate->id,

                "kode_generate" =>
                    $generate->kode_generate,

                "tanggal" =>
                    $generate->tanggal_generate,

                "status" =>
                    $generate->status

            ],

            "prodi" =>
                $namaProdi,

            "total_jadwal" =>
                $dataJadwal->count(),

            "jadwal" =>
                $dataJadwal

        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | HASIL GENERATE BERDASARKAN ID GENERATE
    |--------------------------------------------------------------------------
    */

    public function hasil($id)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL GENERATE
        |--------------------------------------------------------------------------
        */

        $generate =
            GenerateJadwal::find($id);


        if (!$generate) {

            return response()->json([

                "message" =>
                    "Generate tidak ditemukan"

            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwal = Jadwal::with([

            'kelasPerkuliahan.mataKuliah',

            'kelasPerkuliahan.prodi',

            'kelasPerkuliahan.dosen',

            'ruangan'

        ])

        ->where(
            'generate_jadwal_id',
            $id
        )

        ->orderByRaw("
            FIELD(
                hari,
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            )
        ")

        ->orderBy('jam_mulai')

        ->get();


        /*
        |--------------------------------------------------------------------------
        | FORMAT DATA
        |--------------------------------------------------------------------------
        */

        $dataJadwal = $jadwal->map(function ($item) {

            return [

                "id" =>
                    $item->id,

                "hari" =>
                    $item->hari,

                "jam_mulai" =>
                    $item->jam_mulai,

                "jam_selesai" =>
                    $item->jam_selesai,

                "mata_kuliah" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->mataKuliah
                    )->nama_mk,

                "kelas" =>
                    optional(
                        $item->kelasPerkuliahan
                    )->nama_kelas,

                "prodi" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->prodi
                    )->nama_prodi,

                "jenjang" =>
                    optional(
                        optional(
                            $item->kelasPerkuliahan
                        )->prodi
                    )->jenjang,

                "ruangan" =>
                    optional(
                        $item->ruangan
                    )->nama_ruangan,

                "dosen" =>
                    optional(
                        $item->kelasPerkuliahan
                    )
                    ?->dosen
                    ?->pluck('nama_dosen')
                    ?->values()
                    ?->toArray()
                    ?? []

            ];

        });


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            "generate" => [

                "id" =>
                    $generate->id,

                "kode_generate" =>
                    $generate->kode_generate,

                "tanggal_generate" =>
                    $generate->tanggal_generate,

                "status" =>
                    $generate->status

            ],

            "data" =>
                $dataJadwal

        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL BERDASARKAN PRODI
    |--------------------------------------------------------------------------
    */

    public function exportProdiExcel($id)
    {
        /*
        |--------------------------------------------------------------------------
        | GENERATE TERBARU
        |--------------------------------------------------------------------------
        */

        $generate = GenerateJadwal::latest()->first();


        if (!$generate) {

            return response()->json([

                "message" =>
                    "Belum ada generate jadwal"

            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | NAMA PRODI
        |--------------------------------------------------------------------------
        */

        if ((int) $id === 0) {

            $namaProdi = "Semua Prodi";

        } else {

            $prodi = Prodi::find($id);


            if (!$prodi) {

                return response()->json([

                    "message" =>
                        "Program studi tidak ditemukan"

                ], 404);
            }


            $namaProdi =
                $prodi->nama_prodi
                . " "
                . $prodi->jenjang;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwal = Jadwal::with([

            'kelasPerkuliahan.mataKuliah',

            'kelasPerkuliahan.prodi',

            'kelasPerkuliahan.dosen',

            'ruangan'

        ])

        ->where(
            'generate_jadwal_id',
            $generate->id
        )

        ->when(

            (int) $id !== 0,

            function ($query) use ($id) {

                $query->whereHas(

                    'kelasPerkuliahan',

                    function ($q) use ($id) {

                        $q->where(
                            'prodi_id',
                            $id
                        );
                    }
                );
            }
        )

        ->orderByRaw("
            FIELD(
                hari,
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            )
        ")

        ->orderBy('jam_mulai')

        ->get();


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaFile = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            $namaProdi
        );


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD EXCEL
        |--------------------------------------------------------------------------
        */

        return Excel::download(

            new JadwalExport(
                $jadwal,
                $namaProdi
            ),

            'Jadwal_' . $namaFile . '.xlsx'
        );
    }
}