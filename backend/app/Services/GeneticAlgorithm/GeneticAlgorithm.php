<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithm
{
    protected PopulationInitializer $populationInitializer;

    protected FitnessCalculator $fitnessCalculator;

    protected Selection $selection;

    protected Crossover $crossover;

    protected Mutation $mutation;


    public function __construct(
        PopulationInitializer $populationInitializer,
        FitnessCalculator $fitnessCalculator,
        Selection $selection,
        Crossover $crossover,
        Mutation $mutation
    ) {

        $this->populationInitializer =
            $populationInitializer;

        $this->fitnessCalculator =
            $fitnessCalculator;

        $this->selection =
            $selection;

        $this->crossover =
            $crossover;

        $this->mutation =
            $mutation;
    }


    /**
     * Menjalankan Genetic Algorithm.
     *
     * Fitness:
     *
     * 1000 = tidak ada konflik
     * <1000 = masih terdapat konflik
     */
    public function run(
        array $dataKelas,
        array $hariAktif,
        array $slotPerHari,
        int $populationSize = 50,
        int $maxGeneration = 100,
        float $mutationRate = 0.05,
        float $targetFitness = 1000.0
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */

        if (empty($dataKelas)) {

            throw new \InvalidArgumentException(
                'Data kelas tidak boleh kosong.'
            );
        }


        if (empty($hariAktif)) {

            throw new \InvalidArgumentException(
                'Hari aktif tidak boleh kosong.'
            );
        }


        if (empty($slotPerHari)) {

            throw new \InvalidArgumentException(
                'Slot waktu tidak boleh kosong.'
            );
        }


        if ($populationSize <= 0) {

            throw new \InvalidArgumentException(
                'Population size harus lebih besar dari 0.'
            );
        }


        if ($maxGeneration <= 0) {

            throw new \InvalidArgumentException(
                'Max generation harus lebih besar dari 0.'
            );
        }


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
        | TARGET FITNESS
        |--------------------------------------------------------------------------
        |
        | FitnessCalculator menggunakan skala:
        |
        | 1000 = sempurna
        |
        */

        if (
            $targetFitness < 0
            ||
            $targetFitness > 1000
        ) {

            throw new \InvalidArgumentException(
                'Target fitness harus antara 0 dan 1000.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG START
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'GENETIC ALGORITHM DIMULAI'
        );

        logger(
            "POPULATION SIZE: {$populationSize}"
        );

        logger(
            "MAX GENERATION: {$maxGeneration}"
        );

        logger(
            "MUTATION RATE: {$mutationRate}"
        );

        logger(
            "TARGET FITNESS: {$targetFitness}"
        );

        logger(
            '========================================'
        );


        /*
        |--------------------------------------------------------------------------
        | INITIAL POPULATION
        |--------------------------------------------------------------------------
        */

        $population =
            $this->populationInitializer->initialize(
                $dataKelas,
                $hariAktif,
                $slotPerHari,
                $populationSize
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI POPULATION
        |--------------------------------------------------------------------------
        */

        if (empty($population)) {

            throw new \RuntimeException(
                'Populasi awal gagal dibuat.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BEST GLOBAL
        |--------------------------------------------------------------------------
        */

        $bestChromosome = null;

        $bestFitness = -INF;

        $bestGeneration = 0;

        $generationTerakhir = 0;


        /*
        |--------------------------------------------------------------------------
        | EVOLUTION
        |--------------------------------------------------------------------------
        */

        for (
            $generation = 1;
            $generation <= $maxGeneration;
            $generation++
        ) {

            $generationTerakhir =
                $generation;


            /*
            |--------------------------------------------------------------------------
            | EVALUASI POPULATION
            |--------------------------------------------------------------------------
            */

            $ranked = [];


            foreach (
                $population
                as $chromosome
            ) {

                if (
                    !($chromosome instanceof Chromosome)
                ) {

                    continue;
                }


                $fitness =
                    $this->fitnessCalculator->calculate(
                        $chromosome
                    );


                $ranked[] = [

                    'chromosome' =>
                        $chromosome,

                    'fitness' =>
                        $fitness,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI HASIL EVALUASI
            |--------------------------------------------------------------------------
            */

            if (empty($ranked)) {

                throw new \RuntimeException(
                    "Evaluasi population gagal pada generation {$generation}."
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SORT FITNESS TERBESAR
            |--------------------------------------------------------------------------
            */

            usort(
                $ranked,
                function ($a, $b) {

                    return
                        $b['fitness']
                        <=>
                        $a['fitness'];
                }
            );


            /*
            |--------------------------------------------------------------------------
            | BEST GENERATION
            |--------------------------------------------------------------------------
            */

            $currentBest =
                $ranked[0];


            /*
            |--------------------------------------------------------------------------
            | UPDATE BEST GLOBAL
            |--------------------------------------------------------------------------
            */

            if (
                $currentBest['fitness']
                >
                $bestFitness
            ) {

                $bestFitness =
                    $currentBest['fitness'];


                $bestChromosome =
                    $currentBest['chromosome'];


                $bestGeneration =
                    $generation;
            }


            /*
            |--------------------------------------------------------------------------
            | LOG GENERATION
            |--------------------------------------------------------------------------
            */

            logger(
                "GENERASI {$generation} | " .
                "FITNESS GENERASI: {$currentBest['fitness']} | " .
                "BEST GLOBAL: {$bestFitness}"
            );


            /*
            |--------------------------------------------------------------------------
            | CEK FITNESS SEMPURNA
            |--------------------------------------------------------------------------
            */

            if (
                $bestFitness
                >=
                $targetFitness
            ) {

                logger(
                    "FITNESS SEMPURNA TERCAPAI | " .
                    "GENERATION: {$generation} | " .
                    "FITNESS: {$bestFitness}"
                );

                break;
            }


            /*
            |--------------------------------------------------------------------------
            | ELITISM
            |--------------------------------------------------------------------------
            |
            | Simpan 2 chromosome terbaik.
            |
            */

            $eliteCount =
                min(
                    2,
                    $populationSize
                );


            $newPopulation = [];


            for (
                $i = 0;
                $i < $eliteCount;
                $i++
            ) {

                if (
                    isset($ranked[$i])
                ) {

                    $newPopulation[] =
                        $ranked[$i]['chromosome'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | GENERATE CHILDREN
            |--------------------------------------------------------------------------
            */

            while (
                count($newPopulation)
                <
                $populationSize
            ) {

                /*
                |--------------------------------------------------------------------------
                | SELECTION
                |--------------------------------------------------------------------------
                */

                $parentA =
                    $this->selection->tournament(
                        $ranked,
                        3
                    );


                $parentB =
                    $this->selection->tournament(
                        $ranked,
                        3
                    );


                /*
                |--------------------------------------------------------------------------
                | CROSSOVER
                |--------------------------------------------------------------------------
                */

                $child =
                    $this->crossover->crossover(
                        $parentA,
                        $parentB
                    );


                /*
                |--------------------------------------------------------------------------
                | MUTATION
                |--------------------------------------------------------------------------
                */

                $child =
                    $this->mutation->mutate(
                        $child,
                        $hariAktif,
                        $slotPerHari,
                        $dataKelas,
                        $mutationRate
                    );


                /*
                |--------------------------------------------------------------------------
                | MASUKKAN CHILD
                |--------------------------------------------------------------------------
                */

                $newPopulation[] =
                    $child;
            }


            /*
            |--------------------------------------------------------------------------
            | POPULATION BARU
            |--------------------------------------------------------------------------
            */

            $population =
                $newPopulation;


            /*
            |--------------------------------------------------------------------------
            | LOG POPULATION
            |--------------------------------------------------------------------------
            */

            logger(
                "GENERASI {$generation} SELESAI | " .
                "POPULATION: " .
                count($population)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI BEST CHROMOSOME
        |--------------------------------------------------------------------------
        */

        if (
            $bestChromosome === null
        ) {

            throw new \RuntimeException(
                'Genetic Algorithm tidak menghasilkan chromosome terbaik.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FINAL FITNESS
        |--------------------------------------------------------------------------
        */

        $finalFitness =
            $this->fitnessCalculator->calculate(
                $bestChromosome
            );


        /*
        |--------------------------------------------------------------------------
        | LOG SELESAI
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'GENETIC ALGORITHM SELESAI'
        );

        logger(
            "BEST FITNESS: {$finalFitness}"
        );

        logger(
            "BEST GENERATION: {$bestGeneration}"
        );

        logger(
            "TOTAL GENE: " .
            $bestChromosome->count()
        );

        logger(
            "TOTAL GENERATION: {$generationTerakhir}"
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

            'chromosome' =>
                $bestChromosome,

            'fitness' =>
                $finalFitness,

            'generation' =>
                $generationTerakhir,

            'best_generation' =>
                $bestGeneration,

            'total_population' =>
                $populationSize,

            'max_generation' =>
                $maxGeneration,

            'mutation_rate' =>
                $mutationRate,

            'target_fitness' =>
                $targetFitness,

            'success' =>
                $finalFitness >= $targetFitness,

        ];
    }
}