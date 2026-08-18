<?php

namespace App\Services\GeneticAlgorithm;

class FitnessCalculator
{
    /**
     * Fitness maksimum.
     *
     * Fitness sempurna = 1000.
     */
    protected float $maxFitness = 1000;


    /**
     * Menghitung fitness sebuah chromosome.
     *
     * Constraint:
     *
     * 1. Dosen tidak boleh mengajar dua kelas
     *    pada hari dan slot yang sama.
     *
     * 2. Ruangan tidak boleh digunakan dua kelas
     *    pada hari dan slot yang sama.
     *
     * 3. Kelas tidak boleh memiliki jadwal bentrok.
     *
     * 4. Gene wajib memiliki data utama.
     */
    public function calculate(
        Chromosome $chromosome
    ): float {

        /*
        |--------------------------------------------------------------------------
        | 1. AMBIL GENES
        |--------------------------------------------------------------------------
        */

        $genes =
            $chromosome->toArray();

        /*
        |--------------------------------------------------------------------------
        | 2. CHROMOSOME KOSONG
        |--------------------------------------------------------------------------
        */

        if (
            empty($genes)
        ) {

            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. TOTAL PENALTY
        |--------------------------------------------------------------------------
        */

        $penalty = 0;

        /*
        |--------------------------------------------------------------------------
        | 4. TRACKING
        |--------------------------------------------------------------------------
        */

        $dosenTerpakai = [];

        $ruanganTerpakai = [];

        $kelasTerpakai = [];

        /*
        |--------------------------------------------------------------------------
        | 5. PERIKSA SETIAP GENE
        |--------------------------------------------------------------------------
        */

        foreach (
            $genes
            as $index => $gene
        ) {

            /*
            |--------------------------------------------------------------------------
            | DATA GENE
            |--------------------------------------------------------------------------
            */

            $kelasId =
                $gene['kelas_id']
                ?? null;

            $dosenId =
                $gene['dosen_id']
                ?? null;

            $ruanganId =
                $gene['ruangan_id']
                ?? null;

            $hari =
                $gene['hari']
                ?? null;

            /*
            |--------------------------------------------------------------------------
            | SLOT IDS
            |--------------------------------------------------------------------------
            */

            $slotIds =
                $gene['slot_ids']
                ?? [];

            /*
            |--------------------------------------------------------------------------
            | FALLBACK SLOT ID
            |--------------------------------------------------------------------------
            */

            if (
                empty($slotIds)
                &&
                !empty(
                    $gene['slot_id']
                    ?? null
                )
            ) {

                $slotIds = [
                    $gene['slot_id']
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI GENE
            |--------------------------------------------------------------------------
            */

            if (
                empty($kelasId)
                ||
                empty($dosenId)
                ||
                empty($ruanganId)
                ||
                empty($hari)
                ||
                empty($slotIds)
            ) {

                /*
                | Gene tidak valid mendapatkan penalty besar.
                */

                $penalty += 200;

                logger(
                    "FITNESS INVALID GENE | INDEX: {$index}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK SETIAP SLOT
            |--------------------------------------------------------------------------
            */

            foreach (
                $slotIds
                as $slotId
            ) {

                /*
                |--------------------------------------------------------------------------
                | DOSEN
                |--------------------------------------------------------------------------
                */

                $keyDosen =
                    $dosenId
                    . '|'
                    . $hari
                    . '|'
                    . $slotId;

                if (
                    isset(
                        $dosenTerpakai[
                            $keyDosen
                        ]
                    )
                ) {

                    /*
                    | Dosen bentrok.
                    */

                    $penalty += 50;

                    logger(
                        "FITNESS CONFLICT | DOSEN | " .
                        "KELAS {$kelasId} vs KELAS " .
                        $dosenTerpakai[
                            $keyDosen
                        ]['kelas_id'] .
                        " | DOSEN: {$dosenId}"
                    );

                } else {

                    $dosenTerpakai[
                        $keyDosen
                    ] = [
                        'kelas_id' =>
                            $kelasId
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | RUANGAN
                |--------------------------------------------------------------------------
                */

                $keyRuangan =
                    $ruanganId
                    . '|'
                    . $hari
                    . '|'
                    . $slotId;

                if (
                    isset(
                        $ruanganTerpakai[
                            $keyRuangan
                        ]
                    )
                ) {

                    /*
                    | Ruangan bentrok.
                    */

                    $penalty += 50;

                    logger(
                        "FITNESS CONFLICT | RUANGAN | " .
                        "KELAS {$kelasId} vs KELAS " .
                        $ruanganTerpakai[
                            $keyRuangan
                        ]['kelas_id'] .
                        " | RUANGAN: {$ruanganId}"
                    );

                } else {

                    $ruanganTerpakai[
                        $keyRuangan
                    ] = [
                        'kelas_id' =>
                            $kelasId
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | KELAS
                |--------------------------------------------------------------------------
                */

                $keyKelas =
                    $kelasId
                    . '|'
                    . $hari
                    . '|'
                    . $slotId;

                if (
                    isset(
                        $kelasTerpakai[
                            $keyKelas
                        ]
                    )
                ) {

                    /*
                    | Kelas bentrok.
                    */

                    $penalty += 100;

                    logger(
                        "FITNESS CONFLICT | KELAS | " .
                        "KELAS: {$kelasId}"
                    );

                } else {

                    $kelasTerpakai[
                        $keyKelas
                    ] = true;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. HITUNG FITNESS
        |--------------------------------------------------------------------------
        */

        $fitness =
            $this->maxFitness
            -
            $penalty;

        /*
        |--------------------------------------------------------------------------
        | 7. BATASI MINIMUM
        |--------------------------------------------------------------------------
        */

        if (
            $fitness < 0
        ) {

            $fitness = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | 8. DEBUG
        |--------------------------------------------------------------------------
        */

        logger(
            "FITNESS CHECK | " .
            "GENES: " . count($genes) .
            " | PENALTY: {$penalty}" .
            " | FITNESS: {$fitness}"
        );

        /*
        |--------------------------------------------------------------------------
        | 9. RETURN
        |--------------------------------------------------------------------------
        */

        return (float) $fitness;
    }
}