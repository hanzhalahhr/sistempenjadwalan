<?php

namespace App\Services\GeneticAlgorithm;

class Chromosome
{
    /**
     * Kumpulan gene dalam chromosome.
     *
     * @var array
     */
    protected array $genes = [];


    /*
    |--------------------------------------------------------------------------
    | MENAMBAHKAN GENE
    |--------------------------------------------------------------------------
    */

    /**
     * Menambahkan satu gene ke chromosome.
     */
    public function addGene(array $gene): void
    {
        $this->genes[] = $gene;
    }


    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL SELURUH GENE
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil seluruh gene.
     */
    public function getGenes(): array
    {
        return $this->genes;
    }


    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL GENE BERDASARKAN INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil satu gene berdasarkan index.
     *
     * Return null jika index tidak ditemukan.
     */
    public function getGene(int $index): ?array
    {
        return $this->genes[$index] ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | MENGUBAH GENE BERDASARKAN INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Mengubah gene berdasarkan index.
     */
    public function setGene(int $index, array $gene): void
    {
        if (!isset($this->genes[$index])) {

            throw new \OutOfBoundsException(
                "Gene dengan index {$index} tidak ditemukan."
            );
        }

        $this->genes[$index] = $gene;
    }


    /*
    |--------------------------------------------------------------------------
    | CEK GENE
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah gene pada index tertentu tersedia.
     */
    public function hasGene(int $index): bool
    {
        return isset($this->genes[$index]);
    }


    /*
    |--------------------------------------------------------------------------
    | JUMLAH GENE
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil jumlah gene.
     */
    public function count(): int
    {
        return count($this->genes);
    }


    /*
    |--------------------------------------------------------------------------
    | MENGUBAH CHROMOSOME MENJADI ARRAY
    |--------------------------------------------------------------------------
    */

    /**
     * Mengubah chromosome menjadi array.
     */
    public function toArray(): array
    {
        return $this->genes;
    }
}