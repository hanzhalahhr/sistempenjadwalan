<?php

namespace App\Services;

use App\Models\Jadwal;

class JadwalValidator
{
    /*
    |--------------------------------------------------------------------------
    | CEK BENTROK RUANGAN
    |--------------------------------------------------------------------------
    |
    | Mengecek apakah ruangan sudah digunakan pada hari dan waktu
    | yang sama/beririsan.
    |
    | Jika $generateJadwalId diberikan, hanya jadwal pada generate
    | tersebut yang akan diperiksa.
    |
    | Jika $excludeJadwalId diberikan, jadwal tersebut tidak ikut
    | dihitung sebagai bentrok dengan dirinya sendiri.
    |
    */

    public function cekBentrokRuangan(
        $ruanganId,
        $hari,
        $jamMulai,
        $jamSelesai,
        $generateJadwalId = null,
        $excludeJadwalId = null
    ) {

        $query = Jadwal::where(
            'ruangan_id',
            $ruanganId
        )
        ->where(
            'hari',
            $hari
        )
        ->where(function ($query) use (
            $jamMulai,
            $jamSelesai
        ) {

            $query
                ->where(
                    'jam_mulai',
                    '<',
                    $jamSelesai
                )
                ->where(
                    'jam_selesai',
                    '>',
                    $jamMulai
                );

        });


        /*
        |--------------------------------------------------------------------------
        | BATASI KE GENERATE TERTENTU
        |--------------------------------------------------------------------------
        */

        if ($generateJadwalId !== null) {

            $query->where(
                'generate_jadwal_id',
                $generateJadwalId
            );

        }


        /*
        |--------------------------------------------------------------------------
        | JANGAN HITUNG DIRI SENDIRI
        |--------------------------------------------------------------------------
        */

        if ($excludeJadwalId !== null) {

            $query->where(
                'id',
                '!=',
                $excludeJadwalId
            );

        }


        return $query->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK BENTROK DOSEN
    |--------------------------------------------------------------------------
    */

    public function cekBentrokDosen(
        $dosenId,
        $hari,
        $jamMulai,
        $jamSelesai,
        $generateJadwalId = null,
        $excludeJadwalId = null
    ) {

        $query = Jadwal::where(
            'hari',
            $hari
        )
        ->where(function ($query) use (
            $jamMulai,
            $jamSelesai
        ) {

            $query
                ->where(
                    'jam_mulai',
                    '<',
                    $jamSelesai
                )
                ->where(
                    'jam_selesai',
                    '>',
                    $jamMulai
                );

        })
        ->whereHas(
            'kelasPerkuliahan.dosen',
            function ($query) use ($dosenId) {

                $query->where(
                    'dosen.id',
                    $dosenId
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BATASI KE GENERATE TERTENTU
        |--------------------------------------------------------------------------
        */

        if ($generateJadwalId !== null) {

            $query->where(
                'generate_jadwal_id',
                $generateJadwalId
            );

        }


        /*
        |--------------------------------------------------------------------------
        | JANGAN HITUNG DIRI SENDIRI
        |--------------------------------------------------------------------------
        */

        if ($excludeJadwalId !== null) {

            $query->where(
                'id',
                '!=',
                $excludeJadwalId
            );

        }


        return $query->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK KETERSEDIAAN DOSEN
    |--------------------------------------------------------------------------
    */

    public function cekKetersediaanDosen(
        $dosenId,
        $hari,
        $jamMulai,
        $jamSelesai,
        $generateJadwalId = null,
        $excludeJadwalId = null
    ) {

        return !$this->cekBentrokDosen(
            $dosenId,
            $hari,
            $jamMulai,
            $jamSelesai,
            $generateJadwalId,
            $excludeJadwalId
        );
    }
}