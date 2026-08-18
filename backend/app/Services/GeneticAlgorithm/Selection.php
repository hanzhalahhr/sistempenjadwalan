<?php

namespace App\Services\GeneticAlgorithm;

class Selection
{
    /**
     * Memilih chromosome terbaik dari population.
     *
     * Selection menggunakan tournament selection.
     *
     * Alur:
     *
     * population
     *     ↓
     * pilih beberapa kandidat random
     *     ↓
     * bandingkan fitness
     *     ↓
     * ambil kandidat terbaik
     *
     * @param array $population
     * @param FitnessEvaluator $fitnessEvaluator
     * @param int $selectionSize
     *
     * @return array
     */
    public function select(
        array $population,
        FitnessEvaluator $fitnessEvaluator,
        int $selectionSize
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI POPULATION
        |--------------------------------------------------------------------------
        */

        if (empty($population)) {

            throw new \InvalidArgumentException(
                'Population tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SELECTION SIZE
        |--------------------------------------------------------------------------
        */

        if ($selectionSize <= 0) {

            throw new \InvalidArgumentException(
                'Selection size harus lebih besar dari 0.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI SELECTION SIZE
        |--------------------------------------------------------------------------
        */

        $selectionSize =
            min(
                $selectionSize,
                count($population)
            );


        /*
        |--------------------------------------------------------------------------
        | EVALUASI POPULATION
        |--------------------------------------------------------------------------
        */

        $evaluated =
            $fitnessEvaluator->evaluatePopulation(
                $population
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI HASIL EVALUASI
        |--------------------------------------------------------------------------
        */

        if (empty($evaluated)) {

            throw new \RuntimeException(
                'Population gagal dievaluasi saat selection.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SORT FITNESS
        |--------------------------------------------------------------------------
        */

        $evaluated =
            $fitnessEvaluator->sortByFitness(
                $evaluated
            );


        /*
        |--------------------------------------------------------------------------
        | HASIL SELECTION
        |--------------------------------------------------------------------------
        */

        $selected = [];


        /*
        |--------------------------------------------------------------------------
        | TOURNAMENT SELECTION
        |--------------------------------------------------------------------------
        |
        | Kita menggunakan tournament kecil.
        |
        | Setiap tournament mengambil maksimal 3 kandidat.
        |
        */

        $tournamentSize =
            min(
                3,
                count($evaluated)
            );


        for (
            $i = 0;
            $i < $selectionSize;
            $i++
        ) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL INDEX RANDOM
            |--------------------------------------------------------------------------
            */

            $candidateIndexes =
                array_rand(
                    $evaluated,
                    $tournamentSize
                );


            /*
            |--------------------------------------------------------------------------
            | ARRAY_RAND MENGEMBALIKAN INT JIKA HANYA 1
            |--------------------------------------------------------------------------
            */

            if (
                !is_array(
                    $candidateIndexes
                )
            ) {

                $candidateIndexes = [
                    $candidateIndexes
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | CARI CANDIDATE TERBAIK
            |--------------------------------------------------------------------------
            */

            $bestCandidate = null;


            foreach (
                $candidateIndexes as $candidateIndex
            ) {

                if (
                    !isset(
                        $evaluated[$candidateIndex]
                    )
                ) {

                    continue;
                }


                $candidate =
                    $evaluated[$candidateIndex];


                if (
                    !isset(
                        $candidate['chromosome']
                    )
                    ||
                    !isset(
                        $candidate['fitness']
                    )
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | BANDINKAN FITNESS
                |--------------------------------------------------------------------------
                */

                if (
                    $bestCandidate === null
                    ||
                    $candidate['fitness']
                    >
                    $bestCandidate['fitness']
                ) {

                    $bestCandidate =
                        $candidate;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI CANDIDATE
            |--------------------------------------------------------------------------
            */

            if (
                $bestCandidate === null
                ||
                !isset(
                    $bestCandidate['chromosome']
                )
            ) {

                throw new \RuntimeException(
                    "Selection gagal memilih parent ke-" .
                    ($i + 1) .
                    '.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN CHROMOSOME
            |--------------------------------------------------------------------------
            */

            $selected[] =
                $bestCandidate['chromosome'];
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI HASIL
        |--------------------------------------------------------------------------
        */

        if (
            count($selected)
            !==
            $selectionSize
        ) {

            throw new \RuntimeException(
                'Jumlah chromosome hasil selection tidak sesuai. ' .
                "Expected: {$selectionSize} | " .
                'Actual: ' . count($selected)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        logger(
            'SELECTION | ' .
            'POPULATION: ' . count($population) .
            ' | ' .
            'TERPILIH: ' . count($selected) .
            ' | ' .
            'TOURNAMENT: ' . $tournamentSize
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $selected;
    }


    /**
     * Tournament selection untuk kompatibilitas
     * dengan implementasi GeneticAlgorithm lama.
     *
     * Method ini tidak digunakan oleh
     * GeneticAlgorithmEngine saat ini,
     * tetapi tetap disediakan agar tidak
     * merusak kode lama.
     */
    public function tournament(
        array $ranked,
        int $tournamentSize = 3
    ): Chromosome {

        if (empty($ranked)) {

            throw new \InvalidArgumentException(
                'Ranked population tidak boleh kosong.'
            );
        }


        $tournamentSize =
            max(
                1,
                min(
                    $tournamentSize,
                    count($ranked)
                )
            );


        $indexes =
            array_rand(
                $ranked,
                $tournamentSize
            );


        if (
            !is_array($indexes)
        ) {

            $indexes = [
                $indexes
            ];
        }


        $winner = null;


        foreach (
            $indexes as $index
        ) {

            if (
                !isset(
                    $ranked[$index]
                )
            ) {

                continue;
            }


            $candidate =
                $ranked[$index];


            if (
                !isset(
                    $candidate['chromosome']
                )
            ) {

                continue;
            }


            if (
                $winner === null
                ||
                (
                    ($candidate['fitness'] ?? -INF)
                    >
                    ($winner['fitness'] ?? -INF)
                )
            ) {

                $winner =
                    $candidate;
            }
        }


        if (
            $winner === null
            ||
            !(
                $winner['chromosome']
                instanceof
                Chromosome
            )
        ) {

            throw new \RuntimeException(
                'Tournament selection gagal mendapatkan chromosome.'
            );
        }


        return $winner['chromosome'];
    }
}