<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\GenerateJadwal;
use App\Models\Jadwal;
use App\Models\KonfigurasiJadwal;
use App\Models\KonfigurasiHariKuliah;
use App\Models\SemesterAkademik;

use App\Services\JadwalGenerator;

use App\Exports\JadwalExport;
use Maatwebsite\Excel\Facades\Excel;

use Barryvdh\DomPDF\Facade\Pdf;


class GenerateJadwalController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN HISTORY GENERATE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = GenerateJadwal::with('semesterAkademik')
            ->latest()
            ->get()
            ->map(function ($generate) {

                return [

                    'id' =>
                        $generate->id,

                    'generate_ke' =>
                        $generate->generate_ke,

                    'kode_generate' =>
                        $generate->kode_generate,

                    'tanggal_generate' =>
                        $generate->tanggal_generate,

                    'semester_akademik_id' =>
                        $generate->semester_akademik_id,

                    'tahun_akademik' =>
                        $generate->semesterAkademik
                            ? $generate->semesterAkademik->tahun_akademik
                            : null,

                    'periode' =>
                        $generate->semesterAkademik
                            ? $generate->semesterAkademik->periode_label
                            : null,

                    'periode_akademik' =>
                        $generate->semesterAkademik
                            ? $generate->semesterAkademik->tahun_akademik .
                                ' - ' .
                                $generate->semesterAkademik->periode_label
                            : null,

                    'status' =>
                        $generate->status,

                    'jumlah_jadwal' =>
                        Jadwal::where(
                            'generate_jadwal_id',
                            $generate->id
                        )->count(),

                ];
            });

        return response()->json([
            'data' => $data
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE JADWAL
    |--------------------------------------------------------------------------
    */

    public function run(
        Request $request,
        JadwalGenerator $generator
    ) {

        $request->validate([
            'semester_akademik_id' =>
                'required|exists:semester_akademik,id'
        ]);


        $semesterAkademikId =
            (int) $request->semester_akademik_id;


        $semester =
            SemesterAkademik::find(
                $semesterAkademikId
            );


        if (!$semester) {

            return response()->json([
                'message' =>
                    'Semester akademik tidak ditemukan.'
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK KONFIGURASI
        |--------------------------------------------------------------------------
        */

        $konfigurasi =
            KonfigurasiJadwal::where(
                'semester_akademik_id',
                $semesterAkademikId
            )
            ->latest('id')
            ->first();


        if (!$konfigurasi) {

            return response()->json([
                'message' =>
                    'Konfigurasi jadwal untuk periode akademik ' .
                    $semester->tahun_akademik .
                    ' - ' .
                    $semester->periode_label .
                    ' belum disimpan.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK HARI AKTIF
        |--------------------------------------------------------------------------
        */

        $hariAktif =
            KonfigurasiHariKuliah::where(
                'semester_akademik_id',
                $semesterAkademikId
            )
            ->where(
                'is_active',
                true
            )
            ->count();


        if ($hariAktif === 0) {

            return response()->json([
                'message' =>
                    'Belum ada hari kuliah yang aktif untuk periode akademik ' .
                    $semester->tahun_akademik .
                    ' - ' .
                    $semester->periode_label .
                    '.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | NOMOR GENERATE
        |--------------------------------------------------------------------------
        */

        $generateKe =
            (GenerateJadwal::max('generate_ke') ?? 0) + 1;


        /*
        |--------------------------------------------------------------------------
        | BUAT HISTORY GENERATE
        |--------------------------------------------------------------------------
        */

        $generate =
            GenerateJadwal::create([

                'semester_akademik_id' =>
                    $semesterAkademikId,

                'generate_ke' =>
                    $generateKe,

                'kode_generate' =>
                    'GEN-' .
                    date('Y') .
                    '-' .
                    str_pad(
                        $generateKe,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ),

                'tanggal_generate' =>
                    now(),

                /*
                |--------------------------------------------------------------------------
                | DEFAULT GAGAL
                |--------------------------------------------------------------------------
                |
                | Kalau proses berhenti/error, status tetap Gagal.
                |
                */

                'status' =>
                    'Gagal',

            ]);


        try {

            Log::info(
                "========================================"
            );

            Log::info(
                "CONTROLLER MULAI GENERATE"
            );

            Log::info(
                "GENERATE ID: {$generate->id}"
            );

            Log::info(
                "KODE GENERATE: {$generate->kode_generate}"
            );

            Log::info(
                "SEMESTER AKADEMIK ID: {$semesterAkademikId}"
            );

            Log::info(
                "TAHUN AKADEMIK: {$semester->tahun_akademik}"
            );

            Log::info(
                "PERIODE: {$semester->periode_label}"
            );

            Log::info(
                "========================================"
            );


            /*
            |--------------------------------------------------------------------------
            | JALANKAN GENERATOR
            |--------------------------------------------------------------------------
            */

            $hasil =
                $generator->generate(
                    $generate->id,
                    $semesterAkademikId
                );


            /*
            |--------------------------------------------------------------------------
            | CEK HASIL GENERATE
            |--------------------------------------------------------------------------
            */

            if (
                is_array($hasil) &&
                ($hasil['success'] ?? false) === true
            ) {

                $generate->update([
                    'status' =>
                        'Berhasil'
                ]);

                $message =
                    'Generate berhasil.';

            } else {

                $generate->update([
                    'status' =>
                        'Gagal'
                ]);

                $message =
                    'Generate gagal.';
            }


            $generate->refresh();


            /*
            |--------------------------------------------------------------------------
            | JUMLAH JADWAL
            |--------------------------------------------------------------------------
            */

            $jumlahJadwal =
                Jadwal::where(
                    'generate_jadwal_id',
                    $generate->id
                )
                ->count();


            Log::info(
                "HASIL GENERATE ID {$generate->id}"
            );

            Log::info(
                "STATUS: {$generate->status}"
            );

            Log::info(
                "JUMLAH JADWAL: {$jumlahJadwal}"
            );

            Log::info(
                "PERIODE AKADEMIK: " .
                $semester->tahun_akademik .
                " - " .
                $semester->periode_label
            );

            Log::info(
                "========================================"
            );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | JIKA GENERATE ERROR
            |--------------------------------------------------------------------------
            */

            $generate->update([
                'status' =>
                    'Gagal'
            ]);


            Log::error(
                'ERROR GENERATE JADWAL',
                [

                    'generate_id' =>
                        $generate->id,

                    'semester_akademik_id' =>
                        $semesterAkademikId,

                    'error' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString()

                ]
            );


            return response()->json([

                'message' =>
                    'Terjadi kesalahan saat generate jadwal.',

                'status' =>
                    'Gagal',

                'generate_id' =>
                    $generate->id

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'message' =>
                $message,

            'status' =>
                $generate->status,

            'generate_id' =>
                $generate->id,

            'kode_generate' =>
                $generate->kode_generate,

            'semester_akademik_id' =>
                $generate->semester_akademik_id,

            'tahun_akademik' =>
                $semester->tahun_akademik,

            'periode' =>
                $semester->periode_label,

            'periode_akademik' =>
                $semester->tahun_akademik .
                ' - ' .
                $semester->periode_label,

            'jumlah_jadwal' =>
                $jumlahJadwal,

            'data' =>
                $hasil

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL GENERATE
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $generate =
            GenerateJadwal::with(
                'semesterAkademik'
            )
            ->find($id);


        if (!$generate) {

            return response()->json([
                'message' =>
                    'Data generate tidak ditemukan.'
            ], 404);
        }


        $jumlahJadwal =
            Jadwal::where(
                'generate_jadwal_id',
                $id
            )
            ->count();


        return response()->json([

            'data' => [

                'id' =>
                    $generate->id,

                'generate_ke' =>
                    $generate->generate_ke,

                'kode_generate' =>
                    $generate->kode_generate,

                'tanggal_generate' =>
                    $generate->tanggal_generate,

                'semester_akademik_id' =>
                    $generate->semester_akademik_id,

                'tahun_akademik' =>
                    $generate->semesterAkademik
                        ? $generate->semesterAkademik->tahun_akademik
                        : null,

                'periode' =>
                    $generate->semesterAkademik
                        ? $generate->semesterAkademik->periode_label
                        : null,

                'periode_akademik' =>
                    $generate->semesterAkademik
                        ? $generate->semesterAkademik->tahun_akademik .
                            ' - ' .
                            $generate->semesterAkademik->periode_label
                        : null,

                'status' =>
                    $generate->status,

                'jumlah_jadwal' =>
                    $jumlahJadwal

            ]

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS HISTORY + HASIL JADWAL
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $generate =
            GenerateJadwal::find($id);


        if (!$generate) {

            return response()->json([
                'message' =>
                    'Data tidak ditemukan.'
            ], 404);
        }


        try {

            DB::transaction(
                function () use ($id, $generate) {

                    /*
                    |--------------------------------------------------------------------------
                    | HAPUS JADWAL
                    |--------------------------------------------------------------------------
                    |
                    | TIDAK MEMANGGIL:
                    |
                    | $item->dosen()->detach()
                    |
                    | karena Jadwal tidak memiliki relationship dosen.
                    |
                    */

                    Jadwal::where(
                        'generate_jadwal_id',
                        $id
                    )
                    ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | HAPUS HISTORY
                    |--------------------------------------------------------------------------
                    */

                    $generate->delete();
                }
            );


            return response()->json([
                'message' =>
                    'Riwayat generate berhasil dihapus.'
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'ERROR HAPUS GENERATE',
                [

                    'generate_id' =>
                        $id,

                    'error' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString()

                ]
            );


            return response()->json([
                'message' =>
                    'Gagal menghapus riwayat generate.'
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel($id)
    {
        $generate =
            GenerateJadwal::find($id);


        if (!$generate) {

            return response()->json([
                'message' =>
                    'Generate tidak ditemukan.'
            ], 404);
        }


        return Excel::download(

            new JadwalExport($id),

            'jadwal_generate_' .
            $generate->kode_generate .
            '.xlsx'

        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf($id)
    {

        /*
        |--------------------------------------------------------------------------
        | CEK GENERATE
        |--------------------------------------------------------------------------
        */

        $generate =
            GenerateJadwal::with(
                'semesterAkademik'
            )
            ->find($id);


        if (!$generate) {

            return response()->json([

                'message' =>
                    'Generate tidak ditemukan.'

            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA JADWAL
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Kita hanya memakai relationship yang memang ada.
        |
        | Jadwal
        |   ├── kelasPerkuliahan
        |   │      ├── mataKuliah
        |   │      ├── prodi
        |   │      └── dosen
        |   │
        |   └── ruangan
        |
        */

        $jadwal =
            Jadwal::with([

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

            ->orderBy(
                'jam_mulai'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | UBAH MODEL ELOQUENT MENJADI ARRAY SEDERHANA
        |--------------------------------------------------------------------------
        |
        | INI BAGIAN PALING PENTING.
        |
        | Blade nanti TIDAK BOLEH menggunakan:
        |
        | $item->hari
        | $item->nama_dosen
        |
        | Blade harus menggunakan:
        |
        | $item['hari']
        | $item['dosen']
        |
        */

        $dataPdf =
            $jadwal->map(
                function ($item) {

                    /*
                    |--------------------------------------------------------------------------
                    | KELAS
                    |--------------------------------------------------------------------------
                    */

                    $kelas =
                        $item->kelasPerkuliahan;


                    /*
                    |--------------------------------------------------------------------------
                    | MATA KULIAH
                    |--------------------------------------------------------------------------
                    */

                    $mataKuliah =
                        $kelas
                            ? $kelas->mataKuliah
                            : null;


                    /*
                    |--------------------------------------------------------------------------
                    | PRODI
                    |--------------------------------------------------------------------------
                    */

                    $prodi =
                        $kelas
                            ? $kelas->prodi
                            : null;


                    /*
                    |--------------------------------------------------------------------------
                    | DOSEN
                    |--------------------------------------------------------------------------
                    |
                    | Kita ubah menjadi ARRAY STRING.
                    |
                    | Contoh:
                    |
                    | [
                    |     "Budi Santoso",
                    |     "Andi Setiawan"
                    | ]
                    |
                    | BUKAN:
                    |
                    | [
                    |     Dosen object,
                    |     Dosen object
                    | ]
                    |
                    */

                    $dosen = [];


                    if ($kelas) {

                        $dosenRelation =
                            $kelas->dosen;


                        if ($dosenRelation) {

                            $dosen =
                                $dosenRelation
                                    ->pluck('nama_dosen')
                                    ->filter(function ($nama) {

                                        return !empty(
                                            trim((string) $nama)
                                        );

                                    })
                                    ->map(function ($nama) {

                                        return trim(
                                            (string) $nama
                                        );

                                    })
                                    ->values()
                                    ->toArray();

                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NAMA MATA KULIAH
                    |--------------------------------------------------------------------------
                    */

                    $namaMataKuliah =
                        '-';


                    if ($mataKuliah) {

                        $namaMataKuliah =
                            $mataKuliah->nama_mk
                            ?? $mataKuliah->nama_mata_kuliah
                            ?? '-';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NAMA KELAS
                    |--------------------------------------------------------------------------
                    */

                    $namaKelas =
                        $kelas
                            ? (
                                $kelas->nama_kelas
                                ?? '-'
                            )
                            : '-';


                    /*
                    |--------------------------------------------------------------------------
                    | NAMA PRODI
                    |--------------------------------------------------------------------------
                    */

                    $namaProdi =
                        $prodi
                            ? (
                                $prodi->nama_prodi
                                ?? '-'
                            )
                            : '-';


                    /*
                    |--------------------------------------------------------------------------
                    | JENJANG
                    |--------------------------------------------------------------------------
                    */

                    $jenjang =
                        $prodi
                            ? (
                                $prodi->jenjang
                                ?? null
                            )
                            : null;


                    /*
                    |--------------------------------------------------------------------------
                    | RUANGAN
                    |--------------------------------------------------------------------------
                    */

                    $namaRuangan =
                        $item->ruangan
                            ? (
                                $item->ruangan->nama_ruangan
                                ?? '-'
                            )
                            : '-';


                    /*
                    |--------------------------------------------------------------------------
                    | RETURN ARRAY
                    |--------------------------------------------------------------------------
                    */

                    return [

                        'id' =>
                            $item->id,

                        'hari' =>
                            $item->hari
                            ?? '-',

                        'jam_mulai' =>
                            $item->jam_mulai
                            ?? '-',

                        'jam_selesai' =>
                            $item->jam_selesai
                            ?? '-',

                        'mata_kuliah' =>
                            $namaMataKuliah,

                        'kelas' =>
                            $namaKelas,

                        'prodi' =>
                            $namaProdi,

                        'jenjang' =>
                            $jenjang,

                        'ruangan' =>
                            $namaRuangan,

                        'dosen' =>
                            $dosen

                    ];

                }
            )
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | LOG DATA PDF
        |--------------------------------------------------------------------------
        |
        | Untuk memastikan struktur yang masuk ke Blade.
        |
        */

        Log::info(
            'DATA PDF GENERATE',
            [
                'generate_id' =>
                    $id,

                'jumlah_data' =>
                    count($dataPdf),

                'sample' =>
                    $dataPdf[0] ?? null
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD BLADE PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(

                'exports.jadwal',

                [

                    'jadwal' =>
                        $dataPdf,

                    'generateId' =>
                        $generate->kode_generate,

                    'tanggalGenerate' =>
                        $generate->tanggal_generate,

                    'status' =>
                        $generate->status,

                    'tahunAkademik' =>
                        $generate->semesterAkademik
                            ? $generate->semesterAkademik->tahun_akademik
                            : null,

                    'periode' =>
                        $generate->semesterAkademik
                            ? $generate->semesterAkademik->periode_label
                            : null

                ]

            );


        /*
        |--------------------------------------------------------------------------
        | A4 LANDSCAPE
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'a4',
            'landscape'
        );


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->download(

            'jadwal_generate_' .
            $generate->kode_generate .
            '.pdf'

        );
    }
}