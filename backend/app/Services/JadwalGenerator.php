<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\Ruangan;
use App\Models\SlotWaktuKuliah;
use App\Models\Jadwal;
use App\Models\KonfigurasiHariKuliah;

use App\Services\GeneticAlgorithm\GeneticAlgorithmEngine;

use Illuminate\Support\Facades\DB;

class JadwalGenerator
{
    protected GeneticAlgorithmEngine $engine;

    protected JadwalValidator $validator;


    public function __construct(
        GeneticAlgorithmEngine $engine,
        JadwalValidator $validator
    ) {
        $this->engine = $engine;
        $this->validator = $validator;
    }


    /**
     * Generate jadwal menggunakan Genetic Algorithm.
     *
     * @param int $generateJadwalId
     * @param int $semesterAkademikId
     *
     * @return array
     */
    public function generate(
        int $generateJadwalId,
        int $semesterAkademikId
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI GENERATE ID
        |--------------------------------------------------------------------------
        */

        if ($generateJadwalId <= 0) {

            throw new \InvalidArgumentException(
                'Generate jadwal ID tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SEMESTER ID
        |--------------------------------------------------------------------------
        */

        if ($semesterAkademikId <= 0) {

            throw new \InvalidArgumentException(
                'Semester akademik ID tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG START
        |--------------------------------------------------------------------------
        */

        logger('========================================');
        logger('JADWAL GENERATOR DIMULAI');
        logger("GENERATE ID: {$generateJadwalId}");
        logger("SEMESTER AKADEMIK ID: {$semesterAkademikId}");
        logger('========================================');


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA KELAS SESUAI SEMESTER
        |--------------------------------------------------------------------------
        */

        $kelasCollection = KelasPerkuliahan::with([
            'mataKuliah',
            'dosen',
            'prodi'
        ])
        ->where(
            'semester_akademik_id',
            $semesterAkademikId
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KELAS
        |--------------------------------------------------------------------------
        */

        if ($kelasCollection->isEmpty()) {

            throw new \RuntimeException(
                'Tidak ada data kelas perkuliahan untuk semester akademik ID ' .
                $semesterAkademikId .
                '.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG DATA KELAS
        |--------------------------------------------------------------------------
        */

        logger(
            "SEMESTER {$semesterAkademikId} | " .
            "TOTAL KELAS DITEMUKAN: " .
            $kelasCollection->count()
        );


        /*
        |--------------------------------------------------------------------------
        | AMBIL RUANGAN
        |--------------------------------------------------------------------------
        */

        $ruanganCollection = Ruangan::all();


        if ($ruanganCollection->isEmpty()) {

            throw new \RuntimeException(
                'Tidak ada data ruangan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BENTUK DATA RUANGAN
        |--------------------------------------------------------------------------
        */

        $dataRuangan = $ruanganCollection
            ->map(function ($ruangan) {

                return [

                    'id' =>
                        $ruangan->id,

                    'nama_ruangan' =>
                        $ruangan->nama_ruangan,

                    'kapasitas' =>
                        (int) ($ruangan->kapasitas ?? 0),

                ];

            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | AMBIL KONFIGURASI HARI KULIAH
        |--------------------------------------------------------------------------
        */

        $konfigurasiHari = KonfigurasiHariKuliah::where(
            'semester_akademik_id',
            $semesterAkademikId
        )
        ->where(
            'is_active',
            true
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KONFIGURASI HARI
        |--------------------------------------------------------------------------
        */

        if ($konfigurasiHari->isEmpty()) {

            throw new \RuntimeException(
                'Tidak ada hari kuliah aktif untuk semester akademik ID ' .
                $semesterAkademikId .
                '.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | URUTAN HARI
        |--------------------------------------------------------------------------
        */

        $urutanHari = [

            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'

        ];


        /*
        |--------------------------------------------------------------------------
        | BENTUK LIST HARI AKTIF
        |--------------------------------------------------------------------------
        */

        $hariAktif = [];


        foreach ($urutanHari as $hari) {

            $aktif = $konfigurasiHari->contains(
                function ($item) use ($hari) {

                    return strtolower(
                        trim($item->hari)
                    ) === strtolower(
                        trim($hari)
                    );

                }
            );


            if ($aktif) {

                $hariAktif[] = $hari;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI HARI AKTIF
        |--------------------------------------------------------------------------
        */

        if (empty($hariAktif)) {

            throw new \RuntimeException(
                'Tidak ditemukan hari kuliah aktif untuk semester akademik ID ' .
                $semesterAkademikId .
                '.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG HARI AKTIF
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'KONFIGURASI HARI KULIAH AKTIF'
        );

        logger(
            'SEMESTER AKADEMIK ID: ' .
            $semesterAkademikId
        );

        logger(
            'HARI AKTIF: ' .
            implode(
                ', ',
                $hariAktif
            )
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | AMBIL SLOT WAKTU
        |--------------------------------------------------------------------------
        */

        $slotCollection = SlotWaktuKuliah::whereIn(
            'hari',
            $hariAktif
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
            'urutan'
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SLOT
        |--------------------------------------------------------------------------
        */

        if ($slotCollection->isEmpty()) {

            throw new \RuntimeException(
                'Tidak ada slot waktu kuliah untuk hari aktif yang dipilih.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BENTUK SLOT PER HARI
        |--------------------------------------------------------------------------
        */

        $slotPerHari = [];


        foreach ($slotCollection as $slot) {

            if (empty($slot->hari)) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | DOUBLE CHECK HARI AKTIF
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $slot->hari,
                    $hariAktif,
                    true
                )
            ) {

                continue;
            }


            if (
                !isset(
                    $slotPerHari[$slot->hari]
                )
            ) {

                $slotPerHari[$slot->hari] = [];
            }


            $slotPerHari[$slot->hari][] = [

                'id' =>
                    $slot->id,

                'hari' =>
                    $slot->hari,

                'jam_mulai' =>
                    $slot->jam_mulai,

                'jam_selesai' =>
                    $slot->jam_selesai,

                'urutan' =>
                    $slot->urutan,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SLOT PER HARI
        |--------------------------------------------------------------------------
        */

        foreach ($hariAktif as $hari) {

            if (
                !isset(
                    $slotPerHari[$hari]
                )
                ||
                empty(
                    $slotPerHari[$hari]
                )
            ) {

                logger(
                    "PERINGATAN | HARI {$hari} AKTIF " .
                    "TETAPI TIDAK MEMILIKI SLOT."
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG TOTAL SLOT
        |--------------------------------------------------------------------------
        */

        $totalSlot = 0;


        foreach ($slotPerHari as $hari => $slots) {

            $jumlahSlotHari = count(
                $slots
            );

            $totalSlot +=
                $jumlahSlotHari;


            logger(
                "SLOT {$hari}: {$jumlahSlotHari}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG TOTAL SLOT
        |--------------------------------------------------------------------------
        */

        logger(
            'TOTAL SLOT AKTIF: ' .
            $totalSlot
        );

        logger(
            'TOTAL HARI AKTIF: ' .
            count($hariAktif)
        );


        /*
        |--------------------------------------------------------------------------
        | BENTUK DATA KELAS UNTUK GA
        |--------------------------------------------------------------------------
        */

        $dataKelas = [];


        foreach (
            $kelasCollection as $kelas
        ) {

            /*
            |--------------------------------------------------------------------------
            | DATA DOSEN
            |--------------------------------------------------------------------------
            */

            $dataDosen = [];


            foreach (
                $kelas->dosen as $dosen
            ) {

                $dataDosen[] = [

                    'id' =>
                        $dosen->id,

                    'nama_dosen' =>
                        $dosen->nama_dosen,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | JIKA TIDAK ADA DOSEN
            |--------------------------------------------------------------------------
            */

            if (
                empty($dataDosen)
            ) {

                logger(
                    "KELAS {$kelas->id} DIABAIKAN | " .
                    "TIDAK MEMILIKI DOSEN"
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | DATA MATA KULIAH
            |--------------------------------------------------------------------------
            */

            $sks =
                $kelas->mataKuliah
                    ? (int) $kelas->mataKuliah->sks
                    : 1;


            if (
                $sks <= 0
            ) {

                $sks = 1;
            }


            /*
            |--------------------------------------------------------------------------
            | JUMLAH MAHASISWA
            |--------------------------------------------------------------------------
            */

            $jumlahMahasiswa =
                (int) (
                    $kelas->jumlah_mahasiswa
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | DATA RUANGAN
            |--------------------------------------------------------------------------
            */

            $dataRuanganUntukKelas =
                $dataRuangan;


            /*
            |--------------------------------------------------------------------------
            | BENTUK DATA KELAS
            |--------------------------------------------------------------------------
            */

            $dataKelas[] = [

                'kelas_id' =>
                    $kelas->id,

                'mata_kuliah_id' =>
                    $kelas->mata_kuliah_id,

                'prodi_id' =>
                    $kelas->prodi_id,

                'nama_kelas' =>
                    $kelas->nama_kelas,

                'sks' =>
                    $sks,

                'jumlah_mahasiswa' =>
                    $jumlahMahasiswa,

                'dosen' =>
                    $dataDosen,

                'ruangan' =>
                    $dataRuanganUntukKelas,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA KELAS UNTUK GA
        |--------------------------------------------------------------------------
        */

        if (
            empty($dataKelas)
        ) {

            throw new \RuntimeException(
                'Tidak ada kelas yang dapat diproses oleh Genetic Algorithm.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG INPUT GA
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'INPUT GENETIC ALGORITHM'
        );

        logger(
            "SEMESTER AKADEMIK ID: {$semesterAkademikId}"
        );

        logger(
            'TOTAL KELAS: ' .
            count($dataKelas)
        );

        logger(
            'TOTAL RUANGAN: ' .
            count($dataRuangan)
        );

        logger(
            'TOTAL HARI AKTIF: ' .
            count($hariAktif)
        );

        logger(
            'HARI YANG DIGUNAKAN: ' .
            implode(
                ', ',
                $hariAktif
            )
        );

        logger(
            'TOTAL SLOT: ' .
            $totalSlot
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | KONFIGURASI GENETIC ALGORITHM
        |--------------------------------------------------------------------------
        */

        $populationSize =
            50;

        $generations =
            100;

        $selectionSize =
            10;

        $mutationRate =
            0.05;


        /*
        |--------------------------------------------------------------------------
        | LOG KONFIGURASI GA
        |--------------------------------------------------------------------------
        */

        logger(
            'KONFIGURASI GENETIC ALGORITHM'
        );

        logger(
            "POPULATION SIZE: {$populationSize}"
        );

        logger(
            "GENERATIONS: {$generations}"
        );

        logger(
            "SELECTION SIZE: {$selectionSize}"
        );

        logger(
            "MUTATION RATE: {$mutationRate}"
        );

        logger(
            "MUTATION RATE PERCENT: " .
            ($mutationRate * 100) .
            "%"
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | JALANKAN GENETIC ALGORITHM
        |--------------------------------------------------------------------------
        */

        $hasil =
            $this->engine->run(

                $dataKelas,

                $hariAktif,

                $slotPerHari,

                $populationSize,

                $generations,

                $selectionSize,

                $mutationRate

            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI HASIL GA
        |--------------------------------------------------------------------------
        */

        if (
            !is_array($hasil)
        ) {

            throw new \RuntimeException(
                'Genetic Algorithm tidak mengembalikan hasil yang valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG HASIL GA
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'HASIL GENETIC ALGORITHM'
        );

        logger(
            'FITNESS: ' .
            ($hasil['fitness'] ?? 0)
        );

        logger(
            'TOTAL GENE: ' .
            count(
                $hasil['chromosome']->getGenes()
                ?? []
            )
        );

        logger(
            'SUCCESS: ' .
            (
                ($hasil['success'] ?? false)
                    ? 'YES'
                    : 'NO'
            )
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | AMBIL CHROMOSOME TERBAIK
        |--------------------------------------------------------------------------
        */

        $chromosome =
            $hasil['chromosome']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI CHROMOSOME
        |--------------------------------------------------------------------------
        */

        if (
            $chromosome === null
        ) {

            throw new \RuntimeException(
                'Genetic Algorithm tidak menghasilkan chromosome.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL GENE
        |--------------------------------------------------------------------------
        */

        $genes =
            $chromosome->getGenes();


        if (
            empty($genes)
        ) {

            throw new \RuntimeException(
                'Chromosome tidak memiliki gene.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH GENE
        |--------------------------------------------------------------------------
        */

        if (
            count($genes)
            !==
            count($dataKelas)
        ) {

            throw new \RuntimeException(
                'Jumlah gene tidak sama dengan jumlah kelas.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FITNESS
        |--------------------------------------------------------------------------
        */

        $fitness =
            (float) (
                $hasil['fitness']
                ?? 0
            );


        if (
            $fitness < 1
        ) {

            logger(
                'WARNING: FITNESS CHROMOSOME TERBAIK < 1'
            );

            logger(
                "FITNESS AKHIR: {$fitness}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FINAL HARI
        |--------------------------------------------------------------------------
        */

        foreach (
            $genes as $index => $gene
        ) {

            $hariGene =
                $gene['hari']
                ?? null;


            if (
                !in_array(
                    $hariGene,
                    $hariAktif,
                    true
                )
            ) {

                throw new \RuntimeException(
                    "Gene index {$index} menghasilkan hari {$hariGene}, " .
                    "padahal hari tersebut tidak aktif pada konfigurasi."
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LOG GENE FINAL
        |--------------------------------------------------------------------------
        */

        foreach (
            $genes as $index => $gene
        ) {

            logger(
                'GENE FINAL | ' .
                'INDEX: ' .
                $index .
                ' | KELAS: ' .
                ($gene['kelas_id'] ?? '-') .
                ' | RUANGAN: ' .
                ($gene['ruangan_id'] ?? '-') .
                ' | HARI: ' .
                ($gene['hari'] ?? '-') .
                ' | JAM: ' .
                ($gene['jam_mulai'] ?? '-') .
                '-' .
                ($gene['jam_selesai'] ?? '-')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN HASIL KE DATABASE
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | JadwalValidator sekarang benar-benar digunakan.
        |
        | Setiap gene dicek TERLEBIH DAHULU sebelum disimpan.
        |
        | Karena gene sebelumnya dalam generate yang sama
        | sudah tersimpan, validator dapat mendeteksi:
        |
        | - bentrok ruangan
        | - bentrok dosen
        |
        | Generate lain tidak ikut dicek karena validator
        | menggunakan generate_jadwal_id.
        |
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (

                $generateJadwalId,
                $semesterAkademikId,
                $genes

            ) {

                /*
                |--------------------------------------------------------------------------
                | HAPUS JADWAL LAMA UNTUK GENERATE INI
                |--------------------------------------------------------------------------
                */

                Jadwal::where(
                    'generate_jadwal_id',
                    $generateJadwalId
                )
                ->delete();


                /*
                |--------------------------------------------------------------------------
                | INSERT JADWAL SATU PER SATU
                |--------------------------------------------------------------------------
                */

                foreach (
                    $genes as $index => $gene
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI KELAS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        empty(
                            $gene['kelas_id']
                        )
                    ) {

                        throw new \RuntimeException(
                            "Gene index {$index} tidak memiliki kelas_id."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI RUANGAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        empty(
                            $gene['ruangan_id']
                        )
                    ) {

                        throw new \RuntimeException(
                            "Gene index {$index} tidak memiliki ruangan_id."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI HARI
                    |--------------------------------------------------------------------------
                    */

                    if (
                        empty(
                            $gene['hari']
                        )
                    ) {

                        throw new \RuntimeException(
                            "Gene index {$index} tidak memiliki hari."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI JAM
                    |--------------------------------------------------------------------------
                    */

                    if (
                        empty(
                            $gene['jam_mulai']
                        )
                        ||
                        empty(
                            $gene['jam_selesai']
                        )
                    ) {

                        throw new \RuntimeException(
                            "Gene index {$index} tidak memiliki jam lengkap."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CEK BENTROK RUANGAN
                    |--------------------------------------------------------------------------
                    */

                    $bentrokRuangan =
                        $this->validator->cekBentrokRuangan(

                            $gene['ruangan_id'],

                            $gene['hari'],

                            $gene['jam_mulai'],

                            $gene['jam_selesai'],

                            $generateJadwalId

                        );


                    if (
                        $bentrokRuangan
                    ) {

                        throw new \RuntimeException(
                            "VALIDASI GAGAL | " .
                            "Gene index {$index} mengalami bentrok ruangan. " .
                            "Ruangan ID: {$gene['ruangan_id']} | " .
                            "Hari: {$gene['hari']} | " .
                            "Jam: {$gene['jam_mulai']}-{$gene['jam_selesai']}"
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL KELAS
                    |--------------------------------------------------------------------------
                    */

                    $kelas =
                        KelasPerkuliahan::with(
                            'dosen'
                        )->find(
                            $gene['kelas_id']
                        );


                    if (
                        !$kelas
                    ) {

                        throw new \RuntimeException(
                            "Kelas dengan ID {$gene['kelas_id']} tidak ditemukan."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CEK BENTROK DOSEN
                    |--------------------------------------------------------------------------
                    |
                    | Satu kelas bisa mempunyai lebih dari satu dosen.
                    | Semua dosen yang mengajar kelas tersebut harus
                    | bebas dari bentrok.
                    |
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $kelas->dosen as $dosen
                    ) {

                        $bentrokDosen =
                            $this->validator->cekBentrokDosen(

                                $dosen->id,

                                $gene['hari'],

                                $gene['jam_mulai'],

                                $gene['jam_selesai'],

                                $generateJadwalId

                            );


                        if (
                            $bentrokDosen
                        ) {

                            throw new \RuntimeException(
                                "VALIDASI GAGAL | " .
                                "Gene index {$index} mengalami bentrok dosen. " .
                                "Dosen: {$dosen->nama_dosen} | " .
                                "Dosen ID: {$dosen->id} | " .
                                "Hari: {$gene['hari']} | " .
                                "Jam: {$gene['jam_mulai']}-{$gene['jam_selesai']}"
                            );
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOG VALIDASI BERHASIL
                    |--------------------------------------------------------------------------
                    */

                    logger(
                        'VALIDASI JADWAL BERHASIL | ' .
                        'INDEX: ' .
                        $index .
                        ' | KELAS: ' .
                        $gene['kelas_id'] .
                        ' | RUANGAN: ' .
                        $gene['ruangan_id'] .
                        ' | HARI: ' .
                        $gene['hari'] .
                        ' | JAM: ' .
                        $gene['jam_mulai'] .
                        '-' .
                        $gene['jam_selesai']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN JADWAL
                    |--------------------------------------------------------------------------
                    */

                    $jadwal =
                        Jadwal::create([

                            'generate_jadwal_id' =>
                                $generateJadwalId,

                            'kelas_perkuliahan_id' =>
                                $gene['kelas_id'],

                            'ruangan_id' =>
                                $gene['ruangan_id'],

                            'hari' =>
                                $gene['hari'],

                            'jam_mulai' =>
                                $gene['jam_mulai'],

                            'jam_selesai' =>
                                $gene['jam_selesai'],

                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | LOG JADWAL DISIMPAN
                    |--------------------------------------------------------------------------
                    */

                    logger(
                        'JADWAL DISIMPAN | ' .
                        'INDEX: ' .
                        $index .
                        ' | KELAS: ' .
                        $gene['kelas_id'] .
                        ' | RUANGAN: ' .
                        $gene['ruangan_id'] .
                        ' | HARI: ' .
                        $gene['hari'] .
                        ' | JAM: ' .
                        $gene['jam_mulai'] .
                        '-' .
                        $gene['jam_selesai'] .
                        ' | JADWAL ID: ' .
                        $jadwal->id
                    );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | HITUNG HASIL
        |--------------------------------------------------------------------------
        */

        $jumlahJadwal =
            Jadwal::where(
                'generate_jadwal_id',
                $generateJadwalId
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH JADWAL
        |--------------------------------------------------------------------------
        */

        if (
            $jumlahJadwal
            !==
            count($dataKelas)
        ) {

            throw new \RuntimeException(
                'Jumlah jadwal tersimpan (' .
                $jumlahJadwal .
                ') tidak sama dengan jumlah kelas (' .
                count($dataKelas) .
                ').'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG SELESAI
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'JADWAL GENERATOR SELESAI'
        );

        logger(
            "GENERATE ID: {$generateJadwalId}"
        );

        logger(
            "SEMESTER AKADEMIK ID: {$semesterAkademikId}"
        );

        logger(
            "HARI AKTIF: " .
            implode(
                ', ',
                $hariAktif
            )
        );

        logger(
            "JUMLAH KELAS UNTUK GA: " .
            count($dataKelas)
        );

        logger(
            "JUMLAH JADWAL TERSIMPAN: " .
            $jumlahJadwal
        );

        logger(
            "FITNESS: " .
            ($hasil['fitness'] ?? 0)
        );

        logger(
            "GENERATION: " .
            ($hasil['generations'] ?? 0)
        );

        logger(
            "BEST GENERATION: " .
            ($hasil['best_generation'] ?? 0)
        );

        logger(
            "SUCCESS: " .
            (
                ($hasil['success'] ?? false)
                    ? 'YES'
                    : 'NO'
            )
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            'success' =>
                $hasil['success']
                ?? false,

            'generate_jadwal_id' =>
                $generateJadwalId,

            'semester_akademik_id' =>
                $semesterAkademikId,

            'jumlah_kelas' =>
                count($dataKelas),

            'jumlah_jadwal' =>
                $jumlahJadwal,

            'fitness' =>
                $hasil['fitness']
                ?? 0,

            'generations' =>
                $hasil['generations']
                ?? 0,

            'best_generation' =>
                $hasil['best_generation']
                ?? 0,

            'population_size' =>
                $hasil['population_size']
                ?? $populationSize,

            'selection_size' =>
                $hasil['selection_size']
                ?? $selectionSize,

            'elite_count' =>
                $hasil['elite_count']
                ?? 2,

            'mutation_rate' =>
                $hasil['mutation_rate']
                ?? $mutationRate,

            'chromosome' =>
                $chromosome,

        ];
    }
}