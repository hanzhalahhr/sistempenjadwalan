<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\KelasPerkuliahan;
use App\Models\Ruangan;

class FitnessEvaluator
{
    /**
     * Cache jumlah mahasiswa berdasarkan kelas_id.
     *
     * Contoh:
     *
     * [
     *     38 => 41,
     *     9  => 41,
     * ]
     */
    protected array $kelasMahasiswaCache = [];


    /**
     * Cache kapasitas berdasarkan ruangan_id.
     *
     * Contoh:
     *
     * [
     *     3 => 40,
     *     6 => 40,
     * ]
     */
    protected array $ruanganKapasitasCache = [];


    /**
     * Menghitung fitness satu chromosome.
     *
     * Fitness:
     *
     * 1.0 = tidak ada conflict
     *
     * 0.5 = 1 conflict
     *
     * 0.333... = 2 conflict
     *
     * Rumus:
     *
     * 1 / (1 + jumlah conflict)
     */
    public function evaluate(
        Chromosome $chromosome
    ): float {

        $genes =
            $chromosome->toArray();


        /*
        |--------------------------------------------------------------------------
        | VALIDASI CHROMOSOME
        |--------------------------------------------------------------------------
        */

        if (
            empty($genes)
        ) {

            return 0.0;
        }


        /*
        |--------------------------------------------------------------------------
        | JUMLAH CONFLICT
        |--------------------------------------------------------------------------
        */

        $conflictCount =
            0;


        $jumlahGene =
            count($genes);


        /*
        |--------------------------------------------------------------------------
        | PRELOAD DATA KELAS & RUANGAN
        |--------------------------------------------------------------------------
        |
        | Kita ambil data database satu kali sebelum melakukan
        | pengecekan pasangan gene.
        |
        */

        $this->preloadCapacityData(
            $genes
        );


        /*
        |--------------------------------------------------------------------------
        | CEK SETIAP PASANG GENE
        |--------------------------------------------------------------------------
        */

        for (
            $i = 0;
            $i < $jumlahGene;
            $i++
        ) {

            for (
                $j = $i + 1;
                $j < $jumlahGene;
                $j++
            ) {

                $geneA =
                    $genes[$i];


                $geneB =
                    $genes[$j];


                /*
                |--------------------------------------------------------------------------
                | CEK BENTROK WAKTU
                |--------------------------------------------------------------------------
                |
                | Conflict ruangan, dosen, dan kelas hanya dihitung
                | apabila kedua gene berada pada waktu yang bentrok.
                |
                */

                if (
                    !$this->isTimeConflict(
                        $geneA,
                        $geneB
                    )
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | BENTROK RUANGAN
                |--------------------------------------------------------------------------
                */

                if (
                    $this->sameValue(
                        $geneA['ruangan_id'] ?? null,
                        $geneB['ruangan_id'] ?? null
                    )
                ) {

                    $conflictCount++;

                    logger(
                        "FITNESS CONFLICT | " .
                        "RUANGAN | " .
                        "KELAS {$geneA['kelas_id']} vs " .
                        "KELAS {$geneB['kelas_id']} | " .
                        "RUANGAN: {$geneA['ruangan_id']}"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | BENTROK DOSEN
                |--------------------------------------------------------------------------
                */

                if (
                    $this->sameValue(
                        $geneA['dosen_id'] ?? null,
                        $geneB['dosen_id'] ?? null
                    )
                ) {

                    $conflictCount++;

                    logger(
                        "FITNESS CONFLICT | " .
                        "DOSEN | " .
                        "KELAS {$geneA['kelas_id']} vs " .
                        "KELAS {$geneB['kelas_id']} | " .
                        "DOSEN: {$geneA['dosen_id']}"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | BENTROK KELAS
                |--------------------------------------------------------------------------
                |
                | Dua gene dengan kelas_id sama tidak boleh
                | menggunakan waktu yang sama.
                |
                */

                if (
                    $this->sameValue(
                        $geneA['kelas_id'] ?? null,
                        $geneB['kelas_id'] ?? null
                    )
                ) {

                    $conflictCount++;

                    logger(
                        "FITNESS CONFLICT | " .
                        "KELAS | " .
                        "KELAS ID: {$geneA['kelas_id']}"
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CEK KAPASITAS RUANGAN
        |--------------------------------------------------------------------------
        |
        | Ini adalah constraint tambahan.
        |
        | Contoh:
        |
        | Mahasiswa = 41
        | Kapasitas = 40
        |
        | Maka:
        |
        | 41 > 40
        |
        | = 1 conflict
        |
        */

        foreach (
            $genes as $gene
        ) {

            $kelasId =
                $gene['kelas_id'] ?? null;


            $ruanganId =
                $gene['ruangan_id'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | VALIDASI ID
            |--------------------------------------------------------------------------
            */

            if (
                $kelasId === null
                ||
                $ruanganId === null
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | AMBIL JUMLAH MAHASISWA
            |--------------------------------------------------------------------------
            */

            $jumlahMahasiswa =
                $this->getJumlahMahasiswa(
                    $kelasId
                );


            /*
            |--------------------------------------------------------------------------
            | AMBIL KAPASITAS RUANGAN
            |--------------------------------------------------------------------------
            */

            $kapasitasRuangan =
                $this->getKapasitasRuangan(
                    $ruanganId
                );


            /*
            |--------------------------------------------------------------------------
            | DATA TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            |
            | Jangan langsung dianggap conflict.
            |
            | Ini supaya data lama yang belum lengkap tidak
            | menyebabkan seluruh chromosome otomatis rusak.
            |
            */

            if (
                $jumlahMahasiswa === null
                ||
                $kapasitasRuangan === null
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | CEK KAPASITAS
            |--------------------------------------------------------------------------
            */

            if (
                $jumlahMahasiswa
                >
                $kapasitasRuangan
            ) {

                $conflictCount++;

                logger(
                    "FITNESS CONFLICT | " .
                    "KAPASITAS RUANGAN | " .
                    "KELAS: {$kelasId} | " .
                    "MAHASISWA: {$jumlahMahasiswa} | " .
                    "RUANGAN: {$ruanganId} | " .
                    "KAPASITAS: {$kapasitasRuangan}"
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG FITNESS
        |--------------------------------------------------------------------------
        */

        $fitness =
            1 /
            (
                1 +
                $conflictCount
            );


        return $fitness;
    }


    /**
     * Preload data kapasitas kelas dan ruangan.
     *
     * Tujuannya supaya selama evaluate()
     * kita tidak melakukan query database berulang.
     */
    protected function preloadCapacityData(
        array $genes
    ): void {

        $kelasIds = [];


        $ruanganIds = [];


        /*
        |--------------------------------------------------------------------------
        | KUMPULKAN ID
        |--------------------------------------------------------------------------
        */

        foreach (
            $genes as $gene
        ) {

            if (
                isset(
                    $gene['kelas_id']
                )
            ) {

                $kelasId =
                    (int) $gene['kelas_id'];


                if (
                    $kelasId > 0
                ) {

                    $kelasIds[] =
                        $kelasId;
                }
            }


            if (
                isset(
                    $gene['ruangan_id']
                )
            ) {

                $ruanganId =
                    (int) $gene['ruangan_id'];


                if (
                    $ruanganId > 0
                ) {

                    $ruanganIds[] =
                        $ruanganId;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $kelasIds =
            array_values(
                array_unique(
                    $kelasIds
                )
            );


        $ruanganIds =
            array_values(
                array_unique(
                    $ruanganIds
                )
            );


        /*
        |--------------------------------------------------------------------------
        | LOAD KELAS
        |--------------------------------------------------------------------------
        */

        if (
            !empty($kelasIds)
        ) {

            $kelasList =
                KelasPerkuliahan::query()
                    ->whereIn(
                        'id',
                        $kelasIds
                    )
                    ->get(
                        [
                            'id',
                            'jumlah_mahasiswa',
                        ]
                    );


            foreach (
                $kelasList as $kelas
            ) {

                $this->kelasMahasiswaCache[
                    (int) $kelas->id
                ] =
                    (int) $kelas->jumlah_mahasiswa;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RUANGAN
        |--------------------------------------------------------------------------
        */

        if (
            !empty($ruanganIds)
        ) {

            $ruanganList =
                Ruangan::query()
                    ->whereIn(
                        'id',
                        $ruanganIds
                    )
                    ->get(
                        [
                            'id',
                            'kapasitas',
                        ]
                    );


            foreach (
                $ruanganList as $ruangan
            ) {

                $this->ruanganKapasitasCache[
                    (int) $ruangan->id
                ] =
                    (int) $ruangan->kapasitas;
            }
        }
    }


    /**
     * Mengambil jumlah mahasiswa suatu kelas.
     */
    protected function getJumlahMahasiswa(
        $kelasId
    ): ?int {

        $kelasId =
            (int) $kelasId;


        if (
            $kelasId <= 0
        ) {

            return null;
        }


        if (
            array_key_exists(
                $kelasId,
                $this->kelasMahasiswaCache
            )
        ) {

            return
                $this->kelasMahasiswaCache[
                    $kelasId
                ];
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK QUERY
        |--------------------------------------------------------------------------
        */

        $kelas =
            KelasPerkuliahan::query()
                ->select(
                    [
                        'id',
                        'jumlah_mahasiswa',
                    ]
                )
                ->find(
                    $kelasId
                );


        if (
            !$kelas
        ) {

            return null;
        }


        $jumlahMahasiswa =
            (int) $kelas->jumlah_mahasiswa;


        $this->kelasMahasiswaCache[
            $kelasId
        ] =
            $jumlahMahasiswa;


        return
            $jumlahMahasiswa;
    }


    /**
     * Mengambil kapasitas ruangan.
     */
    protected function getKapasitasRuangan(
        $ruanganId
    ): ?int {

        $ruanganId =
            (int) $ruanganId;


        if (
            $ruanganId <= 0
        ) {

            return null;
        }


        if (
            array_key_exists(
                $ruanganId,
                $this->ruanganKapasitasCache
            )
        ) {

            return
                $this->ruanganKapasitasCache[
                    $ruanganId
                ];
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK QUERY
        |--------------------------------------------------------------------------
        */

        $ruangan =
            Ruangan::query()
                ->select(
                    [
                        'id',
                        'kapasitas',
                    ]
                )
                ->find(
                    $ruanganId
                );


        if (
            !$ruangan
        ) {

            return null;
        }


        $kapasitas =
            (int) $ruangan->kapasitas;


        $this->ruanganKapasitasCache[
            $ruanganId
        ] =
            $kapasitas;


        return
            $kapasitas;
    }


    /**
     * Mengecek bentrok waktu dua gene.
     *
     * Bentrok apabila:
     *
     * 1. Hari sama
     * 2. Minimal ada satu slot yang sama
     */
    protected function isTimeConflict(
        array $geneA,
        array $geneB
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | HARI HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (
            empty($geneA['hari'])
            ||
            empty($geneB['hari'])
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | HARI HARUS SAMA
        |--------------------------------------------------------------------------
        */

        if (
            $geneA['hari']
            !==
            $geneB['hari']
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SLOT GENE A
        |--------------------------------------------------------------------------
        */

        $slotIdsA =
            $this->getSlotIds(
                $geneA
            );


        /*
        |--------------------------------------------------------------------------
        | AMBIL SLOT GENE B
        |--------------------------------------------------------------------------
        */

        $slotIdsB =
            $this->getSlotIds(
                $geneB
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SLOT
        |--------------------------------------------------------------------------
        */

        if (
            empty($slotIdsA)
            ||
            empty($slotIdsB)
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI SLOT
        |--------------------------------------------------------------------------
        */

        $slotIdsA =
            array_map(
                'intval',
                $slotIdsA
            );


        $slotIdsB =
            array_map(
                'intval',
                $slotIdsB
            );


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $slotIdsA =
            array_values(
                array_unique(
                    $slotIdsA
                )
            );


        $slotIdsB =
            array_values(
                array_unique(
                    $slotIdsB
                )
            );


        /*
        |--------------------------------------------------------------------------
        | CEK INTERSECTION
        |--------------------------------------------------------------------------
        */

        $intersection =
            array_intersect(
                $slotIdsA,
                $slotIdsB
            );


        return !empty(
            $intersection
        );
    }


    /**
     * Mengambil seluruh slot
     * yang digunakan oleh gene.
     */
    protected function getSlotIds(
        array $gene
    ): array {

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS SLOT_IDS
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $gene['slot_ids']
            )
            &&
            is_array(
                $gene['slot_ids']
            )
            &&
            !empty(
                $gene['slot_ids']
            )
        ) {

            return $gene['slot_ids'];
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK SLOT_ID
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $gene['slot_id']
            )
            &&
            $gene['slot_id'] !== null
        ) {

            return [
                $gene['slot_id']
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA SLOT
        |--------------------------------------------------------------------------
        */

        return [];
    }


    /**
     * Membandingkan dua value secara aman.
     *
     * Contoh:
     *
     * "10" == 10
     *
     * dianggap sama.
     */
    protected function sameValue(
        $valueA,
        $valueB
    ): bool {

        if (
            $valueA === null
            ||
            $valueB === null
        ) {

            return false;
        }


        return
            (int) $valueA
            ===
            (int) $valueB;
    }


    /**
     * Menghitung fitness seluruh populasi.
     */
    public function evaluatePopulation(
        array $population
    ): array {

        $hasil =
            [];


        foreach (
            $population
            as $chromosome
        ) {

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN CHROMOSOME VALID
            |--------------------------------------------------------------------------
            */

            if (
                !(
                    $chromosome
                    instanceof
                    Chromosome
                )
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | HITUNG FITNESS
            |--------------------------------------------------------------------------
            */

            $fitness =
                $this->evaluate(
                    $chromosome
                );


            /*
            |--------------------------------------------------------------------------
            | SIMPAN HASIL
            |--------------------------------------------------------------------------
            */

            $hasil[] = [

                'chromosome' =>
                    $chromosome,

                'fitness' =>
                    $fitness,

            ];
        }


        return $hasil;
    }


    /**
     * Mengurutkan populasi berdasarkan fitness.
     *
     * Fitness terbesar berada di atas.
     */
    public function sortByFitness(
        array $population
    ): array {

        usort(
            $population,
            function (
                $a,
                $b
            ) {

                return
                    $b['fitness']
                    <=>
                    $a['fitness'];
            }
        );


        return $population;
    }
}