<?php

namespace App\Services\GeneticAlgorithm;

class Crossover
{
    /**
     * Generate children dari chromosome hasil selection.
     *
     * Crossover menggunakan single-point crossover.
     *
     * Karena satu chromosome berisi gene untuk seluruh kelas,
     * maka posisi gene tetap dipertahankan.
     */
    public function generateChildren(
        array $selected,
        int $jumlahChildren
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if (empty($selected)) {

            throw new \InvalidArgumentException(
                'Chromosome hasil selection tidak boleh kosong.'
            );
        }


        if ($jumlahChildren < 0) {

            throw new \InvalidArgumentException(
                'Jumlah children tidak boleh negatif.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PARENT
        |--------------------------------------------------------------------------
        */

        foreach (
            $selected as $index => $parent
        ) {

            if (
                !(
                    $parent
                    instanceof
                    Chromosome
                )
            ) {

                throw new \RuntimeException(
                    "Parent ke-" .
                    ($index + 1) .
                    " bukan instance Chromosome."
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA TIDAK PERLU CHILD
        |--------------------------------------------------------------------------
        */

        if ($jumlahChildren === 0) {

            return [];
        }


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PARENT
        |--------------------------------------------------------------------------
        */

        $jumlahParent =
            count($selected);


        /*
        |--------------------------------------------------------------------------
        | HASIL CHILDREN
        |--------------------------------------------------------------------------
        */

        $children = [];


        /*
        |--------------------------------------------------------------------------
        | GENERATE CHILD
        |--------------------------------------------------------------------------
        */

        while (
            count($children)
            <
            $jumlahChildren
        ) {

            /*
            |--------------------------------------------------------------------------
            | PILIH PARENT A
            |--------------------------------------------------------------------------
            */

            $indexA =
                random_int(
                    0,
                    $jumlahParent - 1
                );


            /*
            |--------------------------------------------------------------------------
            | PILIH PARENT B
            |--------------------------------------------------------------------------
            */

            $indexB =
                random_int(
                    0,
                    $jumlahParent - 1
                );


            /*
            |--------------------------------------------------------------------------
            | PARENT BOLEH SAMA
            |--------------------------------------------------------------------------
            |
            | Kalau cuma ada satu parent,
            | crossover tetap harus bisa jalan.
            |
            */

            $parentA =
                $selected[$indexA];


            $parentB =
                $selected[$indexB];


            /*
            |--------------------------------------------------------------------------
            | AMBIL GENE
            |--------------------------------------------------------------------------
            */

            $genesA =
                $parentA->getGenes();


            $genesB =
                $parentB->getGenes();


            /*
            |--------------------------------------------------------------------------
            | VALIDASI GENE
            |--------------------------------------------------------------------------
            */

            if (
                empty($genesA)
                ||
                empty($genesB)
            ) {

                throw new \RuntimeException(
                    'Parent tidak memiliki gene.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI PANJANG
            |--------------------------------------------------------------------------
            */

            if (
                count($genesA)
                !==
                count($genesB)
            ) {

                throw new \RuntimeException(
                    'Jumlah gene parent A dan parent B berbeda.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | BUAT CHILD
            |--------------------------------------------------------------------------
            */

            $child =
                new Chromosome();


            /*
            |--------------------------------------------------------------------------
            | JUMLAH GENE
            |--------------------------------------------------------------------------
            */

            $geneCount =
                count($genesA);


            /*
            |--------------------------------------------------------------------------
            | JIKA HANYA SATU GENE
            |--------------------------------------------------------------------------
            */

            if (
                $geneCount === 1
            ) {

                $child->addGene(
                    $genesA[0]
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | TITIK CROSSOVER
                |--------------------------------------------------------------------------
                |
                | Titik crossover berada di antara gene.
                |
                | Contoh:
                |
                | Parent A:
                | A A A A A
                |
                | Parent B:
                | B B B B B
                |
                | crossover = 2
                |
                | Child:
                | A A B B B
                |
                */

                $crossoverPoint =
                    random_int(
                        1,
                        $geneCount - 1
                    );


                /*
                |--------------------------------------------------------------------------
                | GABUNGKAN GENE
                |--------------------------------------------------------------------------
                */

                for (
                    $i = 0;
                    $i < $geneCount;
                    $i++
                ) {

                    if (
                        $i
                        <
                        $crossoverPoint
                    ) {

                        $gene =
                            $genesA[$i];

                    } else {

                        $gene =
                            $genesB[$i];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | GENE HARUS ARRAY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !is_array($gene)
                    ) {

                        throw new \RuntimeException(
                            "Gene ke-{$i} hasil crossover tidak valid."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | MASUKKAN GENE
                    |--------------------------------------------------------------------------
                    */

                    $child->addGene(
                        $gene
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI CHILD
            |--------------------------------------------------------------------------
            */

            if (
                $child->count()
                !==
                $geneCount
            ) {

                throw new \RuntimeException(
                    'Jumlah gene child tidak sesuai dengan parent.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN CHILD
            |--------------------------------------------------------------------------
            */

            $children[] =
                $child;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH CHILDREN
        |--------------------------------------------------------------------------
        */

        if (
            count($children)
            !==
            $jumlahChildren
        ) {

            throw new \RuntimeException(
                'Jumlah children hasil crossover tidak sesuai. ' .
                "Expected: {$jumlahChildren} | " .
                'Actual: ' . count($children)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        logger(
            'CROSSOVER BERHASIL | ' .
            'PARENT: ' . count($selected) .
            ' | ' .
            'CHILDREN: ' . count($children)
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $children;
    }


    /**
     * Crossover satu pasangan parent.
     *
     * Method kompatibilitas untuk implementasi
     * GeneticAlgorithm lama.
     */
    public function crossover(
        Chromosome $parentA,
        Chromosome $parentB
    ): Chromosome {

        $genesA =
            $parentA->getGenes();


        $genesB =
            $parentB->getGenes();


        if (empty($genesA)) {

            throw new \RuntimeException(
                'Parent A tidak memiliki gene.'
            );
        }


        if (empty($genesB)) {

            throw new \RuntimeException(
                'Parent B tidak memiliki gene.'
            );
        }


        if (
            count($genesA)
            !==
            count($genesB)
        ) {

            throw new \RuntimeException(
                'Jumlah gene kedua parent berbeda.'
            );
        }


        $geneCount =
            count($genesA);


        $child =
            new Chromosome();


        if (
            $geneCount === 1
        ) {

            $child->addGene(
                $genesA[0]
            );

            return $child;
        }


        $crossoverPoint =
            random_int(
                1,
                $geneCount - 1
            );


        for (
            $i = 0;
            $i < $geneCount;
            $i++
        ) {

            $gene =
                $i < $crossoverPoint
                    ? $genesA[$i]
                    : $genesB[$i];


            $child->addGene(
                $gene
            );
        }


        return $child;
    }
}