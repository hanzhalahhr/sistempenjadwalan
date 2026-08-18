<?php

namespace App\Services\GeneticAlgorithm;

class PopulationInitializer
{
    protected ChromosomeBuilder $chromosomeBuilder;

    public function __construct(
        ChromosomeBuilder $chromosomeBuilder
    ) {
        $this->chromosomeBuilder = $chromosomeBuilder;
    }

    /**
     * Membuat population awal.
     *
     * PENTING:
     * Hanya hari yang terdapat di $hariAktif yang boleh digunakan.
     */
    public function initialize(
        array $dataKelas,
        array $hariAktif,
        array $slotPerHari,
        int $populationSize
    ): array {

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
                'Ukuran populasi harus lebih besar dari 0.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI HARI AKTIF
        |--------------------------------------------------------------------------
        |
        | Kita bersihkan hari aktif terlebih dahulu.
        |
        */

        $hariAktif = array_values(
            array_unique(
                array_filter(
                    array_map(
                        fn ($hari) => trim((string) $hari),
                        $hariAktif
                    )
                )
            )
        );

        if (empty($hariAktif)) {
            throw new \RuntimeException(
                'Tidak ada hari aktif yang valid.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER SLOT BERDASARKAN HARI AKTIF
        |--------------------------------------------------------------------------
        |
        | INI PENTING.
        |
        | Walaupun $slotPerHari secara tidak sengaja mengandung Sabtu,
        | Sabtu tidak akan pernah diteruskan ke ChromosomeBuilder.
        |
        */

        $slotAktif = [];

        foreach ($hariAktif as $hari) {

            if (
                !isset($slotPerHari[$hari])
                ||
                !is_array($slotPerHari[$hari])
            ) {
                continue;
            }

            if (empty($slotPerHari[$hari])) {
                continue;
            }

            $slotAktif[$hari] = array_values(
                array_filter(
                    $slotPerHari[$hari],
                    fn ($slot) => is_array($slot)
                )
            );
        }

        if (empty($slotAktif)) {
            throw new \RuntimeException(
                'Tidak ditemukan slot pada hari aktif.'
            );
        }

        logger(
            '========================================'
        );

        logger(
            'MEMBUAT POPULASI AWAL'
        );

        logger(
            'TOTAL KELAS: ' .
            count($dataKelas)
        );

        logger(
            'HARI AKTIF: ' .
            implode(', ', $hariAktif)
        );

        logger(
            'TOTAL HARI AKTIF: ' .
            count($hariAktif)
        );

        logger(
            'TOTAL SLOT AKTIF: ' .
            array_sum(
                array_map(
                    'count',
                    $slotAktif
                )
            )
        );

        logger(
            'POPULATION SIZE: ' .
            $populationSize
        );

        logger(
            '========================================'
        );

        $population = [];

        for (
            $i = 0;
            $i < $populationSize;
            $i++
        ) {

            $chromosome =
                $this->chromosomeBuilder->build(
                    $dataKelas,
                    $hariAktif,
                    $slotAktif
                );

            if (
                !($chromosome instanceof Chromosome)
            ) {
                throw new \RuntimeException(
                    "Chromosome ke-" .
                    ($i + 1) .
                    " gagal dibuat."
                );
            }

            $jumlahGene =
                $chromosome->count();

            $jumlahKelas =
                count($dataKelas);

            if (
                $jumlahGene !== $jumlahKelas
            ) {
                throw new \RuntimeException(
                    "Jumlah gene chromosome ke-" .
                    ($i + 1) .
                    " tidak sesuai. " .
                    "Expected: {$jumlahKelas}, " .
                    "Actual: {$jumlahGene}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI HARI
            |--------------------------------------------------------------------------
            |
            | Pastikan tidak ada gene yang keluar dari hari aktif.
            |
            */

            foreach (
                $chromosome->getGenes() as $geneIndex => $gene
            ) {

                if (!is_array($gene)) {
                    throw new \RuntimeException(
                        "Gene {$geneIndex} tidak valid."
                    );
                }

                $hariGene =
                    $gene['hari'] ?? null;

                if (
                    $hariGene === null
                    ||
                    !in_array(
                        $hariGene,
                        $hariAktif,
                        true
                    )
                ) {
                    throw new \RuntimeException(
                        "Gene {$geneIndex} menghasilkan hari " .
                        "'{$hariGene}' yang tidak termasuk hari aktif."
                    );
                }
            }

            $population[] =
                $chromosome;
        }

        if (
            count($population)
            !==
            $populationSize
        ) {
            throw new \RuntimeException(
                'Jumlah population awal tidak sesuai. ' .
                'Expected: ' .
                $populationSize .
                ', Actual: ' .
                count($population)
            );
        }

        logger(
            'POPULASI AWAL BERHASIL DIBUAT'
        );

        logger(
            'TOTAL CHROMOSOME: ' .
            count($population)
        );

        logger(
            'GENE PER CHROMOSOME: ' .
            count($dataKelas)
        );

        logger(
            '========================================'
        );

        return $population;
    }
}