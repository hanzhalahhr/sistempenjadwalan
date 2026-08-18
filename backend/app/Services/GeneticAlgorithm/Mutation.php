<?php

namespace App\Services\GeneticAlgorithm;

class Mutation
{
    /**
     * Melakukan mutation terhadap chromosome.
     *
     * Mutation akan memilih beberapa gene secara random
     * kemudian mengganti:
     *
     * - hari
     * - slot
     * - jam
     * - ruangan
     * - dosen
     *
     * Mutation tidak langsung menentukan fitness.
     * FitnessEvaluator yang akan mengevaluasi hasil mutation.
     */
    public function mutate(
        Chromosome $chromosome,
        array $hariAktif,
        array $slotPerHari,
        array $dataKelas,
        float $mutationRate
    ): Chromosome {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI MUTATION RATE
        |--------------------------------------------------------------------------
        */

        if (
            $mutationRate < 0
            ||
            $mutationRate > 1
        ) {

            throw new \InvalidArgumentException(
                'Mutation rate harus antara 0 dan 1.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI HARI
        |--------------------------------------------------------------------------
        */

        if (empty($hariAktif)) {

            throw new \InvalidArgumentException(
                'Hari aktif tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SLOT
        |--------------------------------------------------------------------------
        */

        if (empty($slotPerHari)) {

            throw new \InvalidArgumentException(
                'Slot waktu tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA KELAS
        |--------------------------------------------------------------------------
        */

        if (empty($dataKelas)) {

            throw new \InvalidArgumentException(
                'Data kelas tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL GENE
        |--------------------------------------------------------------------------
        */

        $genes =
            $chromosome->getGenes();


        if (empty($genes)) {

            throw new \RuntimeException(
                'Chromosome tidak memiliki gene untuk mutation.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOOP GENE
        |--------------------------------------------------------------------------
        */

        foreach (
            $genes as $index => &$gene
        ) {

            /*
            |--------------------------------------------------------------------------
            | RANDOM MUTATION CHECK
            |--------------------------------------------------------------------------
            */

            $random =
                mt_rand(
                    0,
                    1000000
                )
                /
                1000000;


            /*
            |--------------------------------------------------------------------------
            | TIDAK MUTASI
            |--------------------------------------------------------------------------
            */

            if (
                $random
                >
                $mutationRate
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI GENE
            |--------------------------------------------------------------------------
            */

            if (
                !is_array($gene)
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | CARI DATA KELAS
            |--------------------------------------------------------------------------
            */

            $kelas =
                $this->findKelasForGene(
                    $gene,
                    $dataKelas
                );


            /*
            |--------------------------------------------------------------------------
            | JIKA DATA KELAS TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

            if (
                $kelas === null
            ) {

                logger(
                    "MUTATION DILEWATI | " .
                    "GENE INDEX: {$index} | " .
                    "DATA KELAS TIDAK DITEMUKAN"
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | AMBIL DOSEN
            |--------------------------------------------------------------------------
            */

            $dosenList =
                $kelas['dosen']
                ?? [];


            /*
            |--------------------------------------------------------------------------
            | AMBIL RUANGAN
            |--------------------------------------------------------------------------
            */

            $ruanganList =
                $kelas['ruangan']
                ?? [];


            /*
            |--------------------------------------------------------------------------
            | CARI BLOK SLOT SESUAI SKS
            |--------------------------------------------------------------------------
            */

            $sks =
                (int) (
                    $kelas['sks']
                    ?? $gene['sks']
                    ?? 1
                );


            if (
                $sks <= 0
            ) {

                $sks = 1;
            }


            $blokSlot =
                $this->findValidSlotBlocks(
                    $hariAktif,
                    $slotPerHari,
                    $sks
                );


            /*
            |--------------------------------------------------------------------------
            | JIKA TIDAK ADA SLOT VALID
            |--------------------------------------------------------------------------
            */

            if (empty($blokSlot)) {

                logger(
                    "MUTATION DILEWATI | " .
                    "KELAS: " .
                    ($gene['kelas_id'] ?? '-') .
                    " | TIDAK ADA BLOK SLOT VALID"
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | PILIH BLOK RANDOM
            |--------------------------------------------------------------------------
            */

            $selectedBlock =
                $blokSlot[
                    array_rand(
                        $blokSlot
                    )
                ];


            /*
            |--------------------------------------------------------------------------
            | SLOT PERTAMA
            |--------------------------------------------------------------------------
            */

            $firstSlot =
                $selectedBlock[0];


            /*
            |--------------------------------------------------------------------------
            | SLOT TERAKHIR
            |--------------------------------------------------------------------------
            */

            $lastSlot =
                $selectedBlock[
                    count($selectedBlock) - 1
                ];


            /*
            |--------------------------------------------------------------------------
            | UPDATE HARI
            |--------------------------------------------------------------------------
            */

            $gene['hari'] =
                $firstSlot['hari']
                ??
                $gene['hari']
                ??
                null;


            /*
            |--------------------------------------------------------------------------
            | UPDATE SLOT UTAMA
            |--------------------------------------------------------------------------
            */

            $gene['slot_id'] =
                isset(
                    $firstSlot['id']
                )
                    ? (int) $firstSlot['id']
                    : null;


            /*
            |--------------------------------------------------------------------------
            | UPDATE SEMUA SLOT
            |--------------------------------------------------------------------------
            */

            $slotIds = [];


            foreach (
                $selectedBlock as $slot
            ) {

                if (
                    isset(
                        $slot['id']
                    )
                ) {

                    $slotIds[] =
                        (int) $slot['id'];
                }
            }


            $gene['slot_ids'] =
                $slotIds;


            /*
            |--------------------------------------------------------------------------
            | UPDATE JAM MULAI
            |--------------------------------------------------------------------------
            */

            $gene['jam_mulai'] =
                $firstSlot['jam_mulai']
                ??
                null;


            /*
            |--------------------------------------------------------------------------
            | UPDATE JAM SELESAI
            |--------------------------------------------------------------------------
            */

            $gene['jam_selesai'] =
                $lastSlot['jam_selesai']
                ??
                null;


            /*
            |--------------------------------------------------------------------------
            | UPDATE RUANGAN
            |--------------------------------------------------------------------------
            */

            if (
                !empty($ruanganList)
            ) {

                $selectedRoom =
                    $ruanganList[
                        array_rand(
                            $ruanganList
                        )
                    ];


                if (
                    isset(
                        $selectedRoom['id']
                    )
                ) {

                    $gene['ruangan_id'] =
                        $selectedRoom['id'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE DOSEN
            |--------------------------------------------------------------------------
            */

            if (
                !empty($dosenList)
            ) {

                $selectedDosen =
                    $dosenList[
                        array_rand(
                            $dosenList
                        )
                    ];


                if (
                    isset(
                        $selectedDosen['id']
                    )
                ) {

                    $gene['dosen_id'] =
                        $selectedDosen['id'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | LOG MUTATION
            |--------------------------------------------------------------------------
            */

            logger(
                "MUTATION | " .
                "GENE INDEX: {$index} | " .
                "KELAS: " .
                ($gene['kelas_id'] ?? '-') .
                " | " .
                "HARI: " .
                ($gene['hari'] ?? '-') .
                " | " .
                "SLOT: " .
                implode(
                    ',',
                    $slotIds
                ) .
                " | " .
                "RUANGAN: " .
                ($gene['ruangan_id'] ?? '-') .
                " | " .
                "DOSEN: " .
                ($gene['dosen_id'] ?? '-')
            );
        }

        unset($gene);


        /*
        |--------------------------------------------------------------------------
        | BUAT CHROMOSOME BARU
        |--------------------------------------------------------------------------
        |
        | Kita tidak mengubah object chromosome lama.
        | Hasil mutation dibuat menjadi chromosome baru.
        |
        */

        $mutatedChromosome =
            new Chromosome();


        foreach (
            $genes as $gene
        ) {

            if (
                !is_array($gene)
            ) {

                throw new \RuntimeException(
                    'Gene hasil mutation tidak valid.'
                );
            }


            $mutatedChromosome->addGene(
                $gene
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH GENE
        |--------------------------------------------------------------------------
        */

        if (
            $mutatedChromosome->count()
            !==
            $chromosome->count()
        ) {

            throw new \RuntimeException(
                'Mutation mengubah jumlah gene chromosome.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $mutatedChromosome;
    }


    /**
     * Mencari data kelas berdasarkan kelas_id.
     */
    protected function findKelasForGene(
        array $gene,
        array $dataKelas
    ): ?array {

        $kelasId =
            $gene['kelas_id']
            ?? null;


        if (
            $kelasId === null
        ) {

            return null;
        }


        foreach (
            $dataKelas as $kelas
        ) {

            if (
                !is_array($kelas)
            ) {

                continue;
            }


            $dataKelasId =
                $kelas['kelas_id']
                ?? $kelas['id']
                ?? null;


            if (
                $dataKelasId === null
            ) {

                continue;
            }


            if (
                (int) $dataKelasId
                ===
                (int) $kelasId
            ) {

                return $kelas;
            }
        }


        return null;
    }


    /**
     * Mencari semua blok slot berurutan
     * sesuai kebutuhan SKS.
     */
    protected function findValidSlotBlocks(
        array $hariAktif,
        array $slotPerHari,
        int $sks
    ): array {

        $hasil = [];


        if (
            $sks <= 0
        ) {

            $sks = 1;
        }


        foreach (
            $hariAktif as $hari
        ) {

            if (
                !isset(
                    $slotPerHari[$hari]
                )
                ||
                !is_array(
                    $slotPerHari[$hari]
                )
            ) {

                continue;
            }


            $slots =
                $slotPerHari[$hari];


            /*
            |--------------------------------------------------------------------------
            | SORT SLOT
            |--------------------------------------------------------------------------
            */

            usort(
                $slots,
                function (
                    $a,
                    $b
                ) {

                    return
                        (
                            (int) (
                                $a['urutan']
                                ?? 0
                            )
                        )
                        <=>
                        (
                            (int) (
                                $b['urutan']
                                ?? 0
                            )
                        );
                }
            );


            $jumlahSlot =
                count($slots);


            if (
                $jumlahSlot < $sks
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | SLIDING WINDOW
            |--------------------------------------------------------------------------
            */

            for (
                $i = 0;
                $i <= $jumlahSlot - $sks;
                $i++
            ) {

                $block = [];

                $valid = true;


                for (
                    $j = 0;
                    $j < $sks;
                    $j++
                ) {

                    $current =
                        $slots[
                            $i + $j
                        ];


                    $current['hari'] =
                        $hari;


                    /*
                    |--------------------------------------------------------------------------
                    | CEK URUTAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $j > 0
                    ) {

                        $previous =
                            $slots[
                                $i + $j - 1
                            ];


                        $previousOrder =
                            (int) (
                                $previous['urutan']
                                ?? 0
                            );


                        $currentOrder =
                            (int) (
                                $current['urutan']
                                ?? 0
                            );


                        if (
                            $currentOrder
                            !==
                            $previousOrder + 1
                        ) {

                            $valid = false;

                            break;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CEK JEDA WAKTU
                        |--------------------------------------------------------------------------
                        */

                        $previousEnd =
                            $this->convertTimeToMinutes(
                                $previous['jam_selesai']
                                ?? ''
                            );


                        $currentStart =
                            $this->convertTimeToMinutes(
                                $current['jam_mulai']
                                ?? ''
                            );


                        if (
                            $previousEnd === null
                            ||
                            $currentStart === null
                        ) {

                            $valid = false;

                            break;
                        }


                        $gap =
                            $currentStart
                            -
                            $previousEnd;


                        /*
                        |--------------------------------------------------------------------------
                        | JEDA MAKSIMAL 15 MENIT
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $gap < 0
                            ||
                            $gap > 15
                        ) {

                            $valid = false;

                            break;
                        }
                    }


                    $block[] =
                        $current;
                }


                if (
                    $valid
                ) {

                    $hasil[] =
                        $block;
                }
            }
        }


        return $hasil;
    }


    /**
     * Konversi HH:MM / HH:MM:SS menjadi menit.
     */
    protected function convertTimeToMinutes(
        string $time
    ): ?int {

        $time =
            trim($time);


        if (
            $time === ''
        ) {

            return null;
        }


        $parts =
            explode(
                ':',
                $time
            );


        if (
            count($parts) < 2
        ) {

            return null;
        }


        $hour =
            (int) $parts[0];


        $minute =
            (int) $parts[1];


        if (
            $hour < 0
            ||
            $hour > 23
            ||
            $minute < 0
            ||
            $minute > 59
        ) {

            return null;
        }


        return (
            $hour * 60
        )
        +
        $minute;
    }
}