<?php

namespace App\Services\GeneticAlgorithm;

class ChromosomeBuilder
{
    protected GeneGenerator $geneGenerator;

    public function __construct(
        GeneGenerator $geneGenerator
    ) {
        $this->geneGenerator = $geneGenerator;
    }

    /**
     * Membuat chromosome.
     *
     * Satu gene = satu kelas perkuliahan.
     */
    public function build(
        array $dataKelas,
        array $hariAktif,
        array $slotPerHari
    ): Chromosome {

        if (empty($dataKelas)) {
            throw new \RuntimeException(
                'Data kelas tidak tersedia.'
            );
        }

        if (empty($hariAktif)) {
            throw new \RuntimeException(
                'Hari aktif tidak tersedia.'
            );
        }

        if (empty($slotPerHari)) {
            throw new \RuntimeException(
                'Slot waktu tidak tersedia.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI HARI
        |--------------------------------------------------------------------------
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

        /*
        |--------------------------------------------------------------------------
        | FILTER SLOT
        |--------------------------------------------------------------------------
        |
        | Hanya slot yang berasal dari hari aktif yang boleh masuk.
        |
        */

        $semuaSlot = [];

        foreach ($hariAktif as $hari) {

            if (
                !isset($slotPerHari[$hari])
                ||
                !is_array($slotPerHari[$hari])
            ) {
                continue;
            }

            foreach (
                $slotPerHari[$hari] as $slot
            ) {

                if (!is_array($slot)) {
                    continue;
                }

                /*
                |------------------------------------------------------------------
                | PAKSA HARI SESUAI CONFIG
                |------------------------------------------------------------------
                */

                $slot['hari'] = $hari;

                $semuaSlot[] = $slot;
            }
        }

        if (empty($semuaSlot)) {
            throw new \RuntimeException(
                'Tidak ada slot waktu yang tersedia untuk hari aktif.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEBUG
        |--------------------------------------------------------------------------
        */

        logger(
            '========================================'
        );

        logger(
            'MEMBANGUN CHROMOSOME'
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
            'TOTAL SLOT AKTIF: ' .
            count($semuaSlot)
        );

        logger(
            '========================================'
        );

        $chromosome =
            new Chromosome();

        /*
        |--------------------------------------------------------------------------
        | BUAT GENE
        |--------------------------------------------------------------------------
        */

        foreach (
            $dataKelas as $index => $kelas
        ) {

            if (
                !is_array($kelas)
                ||
                empty($kelas)
            ) {
                throw new \RuntimeException(
                    'Data kelas tidak valid pada index ' .
                    $index .
                    '.'
                );
            }

            $kelasId =
                $kelas['kelas_id']
                ??
                $kelas['id']
                ??
                ($index + 1);

            if (
                !isset($kelas['dosen'])
                ||
                !is_array($kelas['dosen'])
                ||
                empty($kelas['dosen'])
            ) {
                throw new \RuntimeException(
                    "Kelas {$kelasId} tidak memiliki data dosen."
                );
            }

            if (
                !isset($kelas['ruangan'])
                ||
                !is_array($kelas['ruangan'])
                ||
                empty($kelas['ruangan'])
            ) {
                throw new \RuntimeException(
                    "Kelas {$kelasId} tidak memiliki data ruangan."
                );
            }

            logger(
                "BUAT GENE | " .
                "KELAS: {$kelasId} | " .
                "DOSEN: " . count($kelas['dosen']) . " | " .
                "RUANGAN: " . count($kelas['ruangan'])
            );

            try {

                $gene =
                    $this->geneGenerator->create(
                        $kelas,
                        $semuaSlot,
                        $kelas['ruangan'],
                        $kelas['dosen']
                    );

            } catch (\Throwable $e) {

                throw new \RuntimeException(
                    "Gagal membuat gene untuk kelas {$kelasId}. " .
                    $e->getMessage(),
                    0,
                    $e
                );
            }

            if (
                !is_array($gene)
                ||
                empty($gene)
            ) {
                throw new \RuntimeException(
                    "Gene untuk kelas {$kelasId} tidak valid."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI HARI GENE
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $gene['hari'] ?? null,
                    $hariAktif,
                    true
                )
            ) {
                throw new \RuntimeException(
                    "Gene kelas {$kelasId} menghasilkan hari " .
                    ($gene['hari'] ?? 'NULL') .
                    " yang tidak aktif."
                );
            }

            $chromosome->addGene($gene);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH GENE
        |--------------------------------------------------------------------------
        */

        $jumlahKelas =
            count($dataKelas);

        $jumlahGene =
            $chromosome->count();

        if (
            $jumlahGene !== $jumlahKelas
        ) {
            throw new \RuntimeException(
                'Jumlah gene tidak sesuai dengan jumlah kelas. ' .
                "Expected: {$jumlahKelas}, " .
                "Actual: {$jumlahGene}."
            );
        }

        logger(
            'CHROMOSOME BERHASIL DIBUAT | ' .
            'TOTAL GENE: ' .
            $chromosome->count()
        );

        return $chromosome;
    }
}