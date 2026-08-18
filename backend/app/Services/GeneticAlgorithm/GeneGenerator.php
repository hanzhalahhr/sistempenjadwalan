<?php

namespace App\Services\GeneticAlgorithm;

class GeneGenerator
{
    /**
     * Maksimal jeda antar slot yang masih dianggap berurutan.
     */
    protected int $maksimalJedaMenit = 15;


    /**
     * Membuat satu gene untuk satu kelas.
     *
     * Gene yang dihasilkan:
     *
     * - mempunyai kelas_id
     * - mempunyai mata_kuliah_id
     * - mempunyai prodi_id
     * - mempunyai dosen_id
     * - mempunyai ruangan_id
     * - mempunyai hari
     * - mempunyai slot_id
     * - mempunyai slot_ids
     * - mempunyai SKS
     * - mempunyai jam_mulai
     * - mempunyai jam_selesai
     *
     * Slot harus berurutan sesuai kebutuhan SKS.
     */
    public function create(
        array $kelas,
        array $slot,
        array $ruangan,
        array $dosen
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA KELAS
        |--------------------------------------------------------------------------
        */

        if (empty($kelas)) {
            throw new \InvalidArgumentException(
                'Data kelas tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SLOT
        |--------------------------------------------------------------------------
        */

        if (empty($slot)) {
            throw new \InvalidArgumentException(
                'Slot waktu tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI RUANGAN
        |--------------------------------------------------------------------------
        */

        if (empty($ruangan)) {
            throw new \InvalidArgumentException(
                'Kandidat ruangan tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DOSEN
        |--------------------------------------------------------------------------
        */

        if (empty($dosen)) {
            throw new \InvalidArgumentException(
                'Kandidat dosen tidak boleh kosong.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SKS
        |--------------------------------------------------------------------------
        */

        $sks = (int) (
            $kelas['sks']
            ??
            $kelas['mata_kuliah']['sks']
            ??
            1
        );


        if ($sks <= 0) {
            $sks = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI IDENTITAS KELAS
        |--------------------------------------------------------------------------
        */

        $kelasId =
            $kelas['kelas_id']
            ??
            $kelas['id']
            ??
            null;


        if ($kelasId === null) {
            throw new \RuntimeException(
                'Kelas tidak memiliki kelas_id.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI MATA KULIAH
        |--------------------------------------------------------------------------
        */

        $mataKuliahId =
            $kelas['mata_kuliah_id']
            ??
            $kelas['mata_kuliah']['id']
            ??
            null;


        if ($mataKuliahId === null) {
            throw new \RuntimeException(
                "Kelas {$kelasId} tidak memiliki mata_kuliah_id."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PRODI
        |--------------------------------------------------------------------------
        */

        $prodiId =
            $kelas['prodi_id']
            ??
            $kelas['prodi']['id']
            ??
            null;


        if ($prodiId === null) {
            throw new \RuntimeException(
                "Kelas {$kelasId} tidak memiliki prodi_id."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI SLOT
        |--------------------------------------------------------------------------
        */

        $slotValid = [];


        foreach ($slot as $item) {

            if (!is_array($item)) {
                continue;
            }


            if (
                !isset($item['id'])
                ||
                !isset($item['hari'])
                ||
                !isset($item['jam_mulai'])
                ||
                !isset($item['jam_selesai'])
            ) {
                continue;
            }


            $item['id'] =
                (int) $item['id'];


            $item['hari'] =
                trim(
                    (string) $item['hari']
                );


            if (
                $item['id'] <= 0
                ||
                $item['hari'] === ''
            ) {
                continue;
            }


            $slotValid[] =
                $item;
        }


        if (empty($slotValid)) {
            throw new \RuntimeException(
                "Tidak ada slot valid untuk kelas {$kelasId}."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KELOMPOKKAN SLOT BERDASARKAN HARI
        |--------------------------------------------------------------------------
        */

        $slotPerHari = [];


        foreach ($slotValid as $item) {

            $hari =
                $item['hari'];


            if (!isset($slotPerHari[$hari])) {
                $slotPerHari[$hari] = [];
            }


            $slotPerHari[$hari][] =
                $item;
        }


        /*
        |--------------------------------------------------------------------------
        | CARI SELURUH BLOK SLOT VALID
        |--------------------------------------------------------------------------
        */

        $blokSlotTersedia = [];


        foreach (
            $slotPerHari as $hari => $daftarSlot
        ) {

            /*
            |--------------------------------------------------------------------------
            | SORT BERDASARKAN URUTAN
            |--------------------------------------------------------------------------
            */

            usort(
                $daftarSlot,
                function ($a, $b) {

                    $urutanA =
                        (int) (
                            $a['urutan']
                            ?? 0
                        );


                    $urutanB =
                        (int) (
                            $b['urutan']
                            ?? 0
                        );


                    return
                        $urutanA
                        <=>
                        $urutanB;
                }
            );


            $jumlahSlot =
                count($daftarSlot);


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

                $blok = [];

                $valid = true;


                for (
                    $j = 0;
                    $j < $sks;
                    $j++
                ) {

                    $slotSekarang =
                        $daftarSlot[
                            $i + $j
                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | PAKSA HARI
                    |--------------------------------------------------------------------------
                    */

                    $slotSekarang['hari'] =
                        $hari;


                    /*
                    |--------------------------------------------------------------------------
                    | SLOT PERTAMA
                    |--------------------------------------------------------------------------
                    */

                    if ($j === 0) {

                        $blok[] =
                            $slotSekarang;

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SLOT SEBELUMNYA
                    |--------------------------------------------------------------------------
                    */

                    $slotSebelumnya =
                        $daftarSlot[
                            $i + $j - 1
                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | CEK URUTAN SLOT
                    |--------------------------------------------------------------------------
                    */

                    $urutanSebelumnya =
                        (int) (
                            $slotSebelumnya['urutan']
                            ?? 0
                        );


                    $urutanSekarang =
                        (int) (
                            $slotSekarang['urutan']
                            ?? 0
                        );


                    if (
                        $urutanSekarang
                        !==
                        $urutanSebelumnya + 1
                    ) {

                        $valid = false;

                        break;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CEK JAM
                    |--------------------------------------------------------------------------
                    */

                    $waktuSelesai =
                        $this->convertTimeToMinutes(
                            $slotSebelumnya['jam_selesai']
                            ?? ''
                        );


                    $waktuMulai =
                        $this->convertTimeToMinutes(
                            $slotSekarang['jam_mulai']
                            ?? ''
                        );


                    if (
                        $waktuSelesai === null
                        ||
                        $waktuMulai === null
                    ) {

                        $valid = false;

                        break;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CEK JEDA
                    |--------------------------------------------------------------------------
                    */

                    $jeda =
                        $waktuMulai
                        -
                        $waktuSelesai;


                    if (
                        $jeda < 0
                        ||
                        $jeda > $this->maksimalJedaMenit
                    ) {

                        $valid = false;

                        break;
                    }


                    $blok[] =
                        $slotSekarang;
                }


                if ($valid) {

                    $blokSlotTersedia[] =
                        $blok;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SLOT TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (empty($blokSlotTersedia)) {

            throw new \RuntimeException(
                "Tidak ditemukan blok slot valid untuk " .
                "kelas {$kelasId} dengan {$sks} SKS."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PILIH BLOK RANDOM
        |--------------------------------------------------------------------------
        |
        | Randomisasi tetap dipertahankan supaya population
        | tidak semuanya identik.
        |
        */

        $blokTerpilih =
            $blokSlotTersedia[
                array_rand(
                    $blokSlotTersedia
                )
            ];


        /*
        |--------------------------------------------------------------------------
        | PILIH RUANGAN
        |--------------------------------------------------------------------------
        |
        | Untuk sementara kita mempertahankan kandidat ruangan
        | yang sudah diberikan oleh ChromosomeBuilder.
        |
        | Validasi kapasitas akan dilakukan oleh layer
        | constraint / fitness.
        |
        */

        $ruanganTerpilih =
            $ruangan[
                array_rand(
                    $ruangan
                )
            ];


        if (
            !is_array($ruanganTerpilih)
            ||
            !isset($ruanganTerpilih['id'])
        ) {

            throw new \RuntimeException(
                "Kandidat ruangan kelas {$kelasId} tidak valid."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PILIH DOSEN
        |--------------------------------------------------------------------------
        |
        | Dosen yang diterima di sini diasumsikan sudah merupakan
        | kandidat dosen untuk kelas tersebut.
        |
        */

        $dosenTerpilih =
            $dosen[
                array_rand(
                    $dosen
                )
            ];


        if (
            !is_array($dosenTerpilih)
            ||
            !isset($dosenTerpilih['id'])
        ) {

            throw new \RuntimeException(
                "Kandidat dosen kelas {$kelasId} tidak valid."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SLOT PERTAMA
        |--------------------------------------------------------------------------
        */

        $slotPertama =
            $blokTerpilih[0];


        /*
        |--------------------------------------------------------------------------
        | SLOT TERAKHIR
        |--------------------------------------------------------------------------
        */

        $slotTerakhir =
            $blokTerpilih[
                count($blokTerpilih) - 1
            ];


        /*
        |--------------------------------------------------------------------------
        | BENTUK SLOT IDS
        |--------------------------------------------------------------------------
        */

        $slotIds = [];


        foreach (
            $blokTerpilih as $slotItem
        ) {

            if (
                !isset(
                    $slotItem['id']
                )
            ) {

                throw new \RuntimeException(
                    "Blok slot kelas {$kelasId} memiliki slot tanpa ID."
                );
            }


            $slotIds[] =
                (int) $slotItem['id'];
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH SLOT
        |--------------------------------------------------------------------------
        */

        if (
            count($slotIds)
            !==
            $sks
        ) {

            throw new \RuntimeException(
                "Jumlah slot kelas {$kelasId} tidak sesuai SKS. " .
                "Expected: {$sks} | " .
                "Actual: " .
                count($slotIds)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        logger(
            "GENERATOR GENE | " .
            "KELAS: {$kelasId} | " .
            "MK: {$mataKuliahId} | " .
            "SKS: {$sks} | " .
            "HARI: {$slotPertama['hari']} | " .
            "SLOT: " .
            implode(',', $slotIds) .
            " | " .
            "RUANGAN: {$ruanganTerpilih['id']} | " .
            "DOSEN: {$dosenTerpilih['id']}"
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN GENE
        |--------------------------------------------------------------------------
        */

        return [

            'kelas_id' =>
                (int) $kelasId,

            'mata_kuliah_id' =>
                (int) $mataKuliahId,

            'prodi_id' =>
                (int) $prodiId,

            'dosen_id' =>
                (int) $dosenTerpilih['id'],

            'ruangan_id' =>
                (int) $ruanganTerpilih['id'],

            'hari' =>
                $slotPertama['hari'],

            'slot_id' =>
                (int) $slotPertama['id'],

            'slot_ids' =>
                $slotIds,

            'sks' =>
                $sks,

            'jam_mulai' =>
                $slotPertama['jam_mulai'],

            'jam_selesai' =>
                $slotTerakhir['jam_selesai'],
        ];
    }


    /**
     * Konversi waktu menjadi menit.
     */
    protected function convertTimeToMinutes(
        string $time
    ): ?int {

        $time =
            trim($time);


        if ($time === '') {
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


        return
            ($hour * 60)
            +
            $minute;
    }
}