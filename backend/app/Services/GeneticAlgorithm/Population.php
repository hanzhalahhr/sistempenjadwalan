<?php

namespace App\Services\GeneticAlgorithm;

class Population
{
    /**
     * Kumpulan chromosome.
     *
     * @var Chromosome[]
     */
    protected array $chromosomes = [];


    /**
     * Menambahkan chromosome ke population.
     */
    public function addChromosome(Chromosome $chromosome): void
    {
        $this->chromosomes[] = $chromosome;
    }


    /**
     * Mengambil seluruh chromosome.
     *
     * @return Chromosome[]
     */
    public function getChromosomes(): array
    {
        return $this->chromosomes;
    }


    /**
     * Mengambil jumlah chromosome.
     */
    public function count(): int
    {
        return count($this->chromosomes);
    }


    /**
     * Mengambil chromosome berdasarkan index.
     */
    public function get(int $index): ?Chromosome
    {
        return $this->chromosomes[$index] ?? null;
    }


    /**
     * Mengambil chromosome terbaik berdasarkan callback fitness.
     *
     * Callback harus mengembalikan nilai numerik.
     */
    public function getBest(callable $fitnessCallback): ?Chromosome
    {
        if (empty($this->chromosomes)) {
            return null;
        }

        $bestChromosome = null;
        $bestFitness = null;

        foreach ($this->chromosomes as $chromosome) {

            $fitness = $fitnessCallback($chromosome);

            if (
                $bestFitness === null ||
                $fitness > $bestFitness
            ) {
                $bestFitness = $fitness;
                $bestChromosome = $chromosome;
            }
        }

        return $bestChromosome;
    }


    /**
     * Mengubah population menjadi array.
     */
    public function toArray(): array
    {
        return array_map(
            fn (Chromosome $chromosome) => $chromosome->toArray(),
            $this->chromosomes
        );
    }
}