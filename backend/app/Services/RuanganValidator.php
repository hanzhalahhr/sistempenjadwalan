<?php

namespace App\Services;

use App\Models\Ruangan;

class RuanganValidator
{

    public function cariRuangan(
        $jumlahMahasiswa,
        $tipeRuangan = 'Kelas'
    )
    {

        return Ruangan::where(
            'kapasitas',
            '>=',
            $jumlahMahasiswa
        )

        ->where(
            'tipe_ruangan',
            $tipeRuangan
        )

        ->orderBy('kapasitas','asc')

        ->get();

    }

}