<?php

namespace App\Http\Controllers;

use App\Models\KonfigurasiJadwal;
use App\Models\KonfigurasiHariKuliah;
use App\Models\SlotWaktuKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonfigurasiJadwalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN KONFIGURASI BERDASARKAN SEMESTER
    |--------------------------------------------------------------------------
    */

    public function show($semesterId)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL KONFIGURASI UTAMA
        |--------------------------------------------------------------------------
        */

        $konfigurasi = KonfigurasiJadwal::where(
            'semester_akademik_id',
            $semesterId
        )->first();


        /*
        |--------------------------------------------------------------------------
        | AMBIL KONFIGURASI HARI
        |--------------------------------------------------------------------------
        */

        $hari = KonfigurasiHariKuliah::where(
            'semester_akademik_id',
            $semesterId
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
        ->get();


        /*
        |--------------------------------------------------------------------------
        | AMBIL TOTAL SLOT
        |--------------------------------------------------------------------------
        |
        | Slot waktu berlaku secara global.
        |
        */

        $totalSlot = SlotWaktuKuliah::count();


        /*
        |--------------------------------------------------------------------------
        | HITUNG HARI AKTIF
        |--------------------------------------------------------------------------
        */

        $hariAktif = $hari
            ->where(
                'is_active',
                true
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS KONFIGURASI
        |--------------------------------------------------------------------------
        |
        | Konfigurasi dianggap READY jika:
        |
        | 1. Konfigurasi utama tersedia
        | 2. Konfigurasi tersebut aktif
        | 3. Minimal ada 1 hari aktif
        | 4. Minimal ada 1 slot waktu
        |
        */

        $configReady =
            $konfigurasi !== null &&
            (int) $konfigurasi->is_active === 1 &&
            $hariAktif > 0 &&
            $totalSlot > 0;


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'konfigurasi' =>
                $konfigurasi,

            'hari' =>
                $hari,

            'total_slot' =>
                $totalSlot,

            'hari_aktif' =>
                $hariAktif,

            'config_ready' =>
                $configReady

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN KONFIGURASI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DEBUG REQUEST
        |--------------------------------------------------------------------------
        */

        logger(
            'KONFIGURASI JADWAL REQUEST:',
            $request->all()
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'semester_akademik_id' => [
                'required',
                'integer',
                'exists:semester_akademik,id'
            ],

            'hari' => [
                'required',
                'array',
                'min:1'
            ],

            'hari.*.nama' => [
                'required',
                'string',
                'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'
            ],

            'hari.*.aktif' => [
                'required',
                'boolean'
            ]

        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK MINIMAL SATU HARI AKTIF
        |--------------------------------------------------------------------------
        */

        $adaHariAktif =
            collect(
                $validated['hari']
            )->contains(
                function ($item) {

                    return $item['aktif'] === true;

                }
            );


        if (!$adaHariAktif) {

            return response()->json([

                'message' =>
                    'Minimal satu hari kuliah harus aktif.'

            ], 422);

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | NONAKTIFKAN KONFIGURASI SEMESTER LAIN
            |--------------------------------------------------------------------------
            |
            | Hanya semester yang sedang disimpan yang boleh aktif.
            |
            */

            KonfigurasiJadwal::where(
                'semester_akademik_id',
                '!=',
                $validated['semester_akademik_id']
            )->update([

                'is_active' =>
                    false

            ]);


            /*
            |--------------------------------------------------------------------------
            | SIMPAN / UPDATE KONFIGURASI UTAMA
            |--------------------------------------------------------------------------
            */

            $konfigurasi =
                KonfigurasiJadwal::updateOrCreate(

                    [
                        'semester_akademik_id' =>
                            $validated[
                                'semester_akademik_id'
                            ]
                    ],

                    [
                        'is_active' =>
                            true
                    ]

                );


            /*
            |--------------------------------------------------------------------------
            | HAPUS KONFIGURASI HARI LAMA
            |--------------------------------------------------------------------------
            */

            KonfigurasiHariKuliah::where(
                'semester_akademik_id',
                $validated[
                    'semester_akademik_id'
                ]
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | SIMPAN KONFIGURASI HARI BARU
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['hari']
                as $item
            ) {

                KonfigurasiHariKuliah::create([

                    'semester_akademik_id' =>
                        $validated[
                            'semester_akademik_id'
                        ],

                    'hari' =>
                        $item['nama'],

                    'is_active' =>
                        $item['aktif']

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA TERBARU
            |--------------------------------------------------------------------------
            */

            $hari =
                KonfigurasiHariKuliah::where(
                    'semester_akademik_id',
                    $validated[
                        'semester_akademik_id'
                    ]
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
                ->get();


            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL SLOT
            |--------------------------------------------------------------------------
            */

            $totalSlot =
                SlotWaktuKuliah::count();


            /*
            |--------------------------------------------------------------------------
            | HITUNG HARI AKTIF
            |--------------------------------------------------------------------------
            */

            $hariAktif =
                $hari
                    ->where(
                        'is_active',
                        true
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | CEK CONFIG READY
            |--------------------------------------------------------------------------
            */

            $configReady =
                $konfigurasi !== null &&
                (int) $konfigurasi->is_active === 1 &&
                $hariAktif > 0 &&
                $totalSlot > 0;


            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'message' =>
                    'Konfigurasi jadwal berhasil disimpan.',

                'data' => [

                    'konfigurasi' =>
                        $konfigurasi,

                    'hari' =>
                        $hari,

                    'total_slot' =>
                        $totalSlot,

                    'hari_aktif' =>
                        $hariAktif,

                    'config_ready' =>
                        $configReady

                ]

            ]);

        }

        catch (\Throwable $e) {

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | LOG ERROR
            |--------------------------------------------------------------------------
            */

            logger(
                'ERROR KONFIGURASI JADWAL: ' .
                $e->getMessage()
            );


            /*
            |--------------------------------------------------------------------------
            | RESPONSE ERROR
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'message' =>
                    'Gagal menyimpan konfigurasi jadwal.',

                'error' =>
                    $e->getMessage()

            ], 500);

        }
    }
}