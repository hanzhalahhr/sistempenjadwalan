<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithmEngine
{
    protected PopulationInitializer $populationInitializer;

    protected FitnessEvaluator $fitnessEvaluator;

    protected Selection $selection;

    protected Crossover $crossover;

    protected Mutation $mutation;

    public function __construct(
        PopulationInitializer $populationInitializer,
        FitnessEvaluator $fitnessEvaluator,
        Selection $selection,
        Crossover $crossover,
        Mutation $mutation
    ) {
        $this->populationInitializer = $populationInitializer;
        $this->fitnessEvaluator = $fitnessEvaluator;
        $this->selection = $selection;
        $this->crossover = $crossover;
        $this->mutation = $mutation;
    }

    /**
     * Menjalankan Genetic Algorithm.
     */
    public function run(
        array $dataKelas,
        array $hariAktif,
        array $slotPerHari,
        int $populationSize = 50,
        int $generations = 100,
        int $selectionSize = 10,
        float $mutationRate = 0.05
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

        if ($generations <= 0) {
            throw new \InvalidArgumentException(
                'Jumlah generasi harus lebih besar dari 0.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI SELECTION
        |--------------------------------------------------------------------------
        */

        if ($selectionSize <= 0) {
            $selectionSize = 10;
        }

        $selectionSize = min(
            $selectionSize,
            $populationSize
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI MUTATION RATE
        |--------------------------------------------------------------------------
        */

        if (
            $mutationRate > 1 &&
            $mutationRate <= 100
        ) {
            $mutationRate /= 100;
        }

        if (
            $mutationRate < 0 ||
            $mutationRate > 1
        ) {
            throw new \InvalidArgumentException(
                'Mutation rate harus antara 0 dan 1.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ELITISM
        |--------------------------------------------------------------------------
        */

        $eliteCount = min(
            2,
            $populationSize
        );

        /*
        |--------------------------------------------------------------------------
        | TARGET FITNESS
        |--------------------------------------------------------------------------
        */

        $targetFitness = 1.0;

        logger('========================================');
        logger('GENETIC ALGORITHM ENGINE DIMULAI');
        logger("POPULATION SIZE: {$populationSize}");
        logger("GENERATIONS: {$generations}");
        logger("SELECTION SIZE: {$selectionSize}");
        logger("ELITE COUNT: {$eliteCount}");
        logger("MUTATION RATE: {$mutationRate}");
        logger("TARGET FITNESS: {$targetFitness}");
        logger('========================================');

        /*
        |--------------------------------------------------------------------------
        | POPULASI AWAL
        |--------------------------------------------------------------------------
        */

        $population =
            $this->populationInitializer->initialize(
                $dataKelas,
                $hariAktif,
                $slotPerHari,
                $populationSize
            );

        if (
            count($population)
            !==
            $populationSize
        ) {
            throw new \RuntimeException(
                'Jumlah population awal tidak sesuai. ' .
                "Expected: {$populationSize}, " .
                'Actual: ' . count($population)
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
        | EVOLUSI
        |--------------------------------------------------------------------------
        */

        for (
            $generation = 1;
            $generation <= $generations;
            $generation++
        ) {

            $generationTerakhir = $generation;

            /*
            |--------------------------------------------------------------------------
            | EVALUASI
            |--------------------------------------------------------------------------
            */

            $evaluated =
                $this->fitnessEvaluator
                    ->evaluatePopulation(
                        $population
                    );

            if (empty($evaluated)) {
                throw new \RuntimeException(
                    "Evaluasi population gagal pada generation {$generation}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SORT
            |--------------------------------------------------------------------------
            */

            $evaluated =
                $this->fitnessEvaluator
                    ->sortByFitness(
                        $evaluated
                    );

            if (empty($evaluated)) {
                throw new \RuntimeException(
                    "Sorting fitness kosong pada generation {$generation}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | BEST GENERASI
            |--------------------------------------------------------------------------
            */

            $currentBest = $evaluated[0];

            if (
                !isset($currentBest['fitness']) ||
                !isset($currentBest['chromosome'])
            ) {
                throw new \RuntimeException(
                    "Best chromosome generation {$generation} tidak valid."
                );
            }

            $currentFitness =
                (float) $currentBest['fitness'];

            /*
            |--------------------------------------------------------------------------
            | UPDATE BEST GLOBAL
            |--------------------------------------------------------------------------
            */

            if (
                $currentFitness >
                $bestFitness
            ) {

                $bestFitness =
                    $currentFitness;

                $bestChromosome =
                    $currentBest['chromosome'];

                $bestGeneration =
                    $generation;
            }

            logger(
                "GENERASI {$generation} | " .
                "FITNESS: {$currentFitness} | " .
                "BEST GLOBAL: {$bestFitness}"
            );

            /*
            |--------------------------------------------------------------------------
            | CEK SOLUSI SEMPURNA
            |--------------------------------------------------------------------------
            */

            if (
                $bestFitness >=
                $targetFitness
            ) {

                logger(
                    "FITNESS SEMPURNA | " .
                    "GENERATION: {$generation} | " .
                    "FITNESS: {$bestFitness}"
                );

                break;
            }

            /*
            |--------------------------------------------------------------------------
            | ELITISM
            |--------------------------------------------------------------------------
            */

            $elite = [];

            for (
                $i = 0;
                $i < $eliteCount;
                $i++
            ) {

                if (
                    isset($evaluated[$i]['chromosome'])
                ) {
                    $elite[] =
                        $evaluated[$i]['chromosome'];
                }
            }

            if (
                count($elite)
                !==
                $eliteCount
            ) {
                throw new \RuntimeException(
                    "Jumlah elite tidak sesuai pada generation {$generation}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SELECTION
            |--------------------------------------------------------------------------
            */

            $selected =
                $this->selection->select(
                    $population,
                    $this->fitnessEvaluator,
                    $selectionSize
                );

            if (empty($selected)) {
                throw new \RuntimeException(
                    "Selection gagal pada generation {$generation}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | JUMLAH CHILD
            |--------------------------------------------------------------------------
            */

            $jumlahChildren =
                $populationSize -
                $eliteCount;

            /*
            |--------------------------------------------------------------------------
            | CROSSOVER
            |--------------------------------------------------------------------------
            */

            $children = [];

            if ($jumlahChildren > 0) {

                $children =
                    $this->crossover
                        ->generateChildren(
                            $selected,
                            $jumlahChildren
                        );
            }

            if (
                count($children)
                !==
                $jumlahChildren
            ) {
                throw new \RuntimeException(
                    "Jumlah child tidak sesuai pada generation {$generation}. " .
                    "Expected: {$jumlahChildren} | " .
                    "Actual: " . count($children)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MUTATION
            |--------------------------------------------------------------------------
            */

            $mutatedChildren = [];

            foreach ($children as $child) {

                if (
                    !($child instanceof Chromosome)
                ) {
                    throw new \RuntimeException(
                        "Child bukan instance Chromosome."
                    );
                }

                $mutatedChild =
                    $this->mutation->mutate(
                        $child,
                        $hariAktif,
                        $slotPerHari,
                        $dataKelas,
                        $mutationRate
                    );

                if (
                    !($mutatedChild instanceof Chromosome)
                ) {
                    throw new \RuntimeException(
                        "Hasil mutation bukan instance Chromosome."
                    );
                }

                $mutatedChildren[] =
                    $mutatedChild;
            }

            /*
            |--------------------------------------------------------------------------
            | POPULASI BARU
            |--------------------------------------------------------------------------
            */

            $population =
                array_merge(
                    $elite,
                    $mutatedChildren
                );

            if (
                count($population)
                !==
                $populationSize
            ) {
                throw new \RuntimeException(
                    "Population baru tidak sesuai pada generation {$generation}. " .
                    "Expected: {$populationSize} | " .
                    "Actual: " . count($population)
                );
            }

            logger(
                "GENERASI {$generation} SELESAI | " .
                "POPULATION: " . count($population)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI BEST CHROMOSOME
        |--------------------------------------------------------------------------
        */

        if (
            !($bestChromosome instanceof Chromosome)
        ) {
            throw new \RuntimeException(
                'Genetic Algorithm tidak menghasilkan chromosome terbaik.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FITNESS FINAL
        |--------------------------------------------------------------------------
        */

        $finalFitness =
            (float) $this->fitnessEvaluator
                ->evaluate(
                    $bestChromosome
                );

        /*
        |--------------------------------------------------------------------------
        | FINAL LOG
        |--------------------------------------------------------------------------
        */

        logger('========================================');
        logger('GENETIC ALGORITHM SELESAI');
        logger("BEST FITNESS: {$finalFitness}");
        logger("BEST GENERATION: {$bestGeneration}");
        logger(
            'TOTAL GENE: ' .
            $bestChromosome->count()
        );
        logger(
            "TOTAL GENERATION: {$generationTerakhir}"
        );
        logger(
            "SUCCESS: " .
            (
                $finalFitness >= $targetFitness
                    ? 'YES'
                    : 'NO'
            )
        );
        logger('========================================');

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

            'generations' =>
                $generationTerakhir,

            'best_generation' =>
                $bestGeneration,

            'population_size' =>
                $populationSize,

            'selection_size' =>
                $selectionSize,

            'elite_count' =>
                $eliteCount,

            'mutation_rate' =>
                $mutationRate,

            'success' =>
                $finalFitness >= $targetFitness,
        ];
    }
}