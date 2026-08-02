<?php

namespace App\Services;

use App\Models\Jadwal;

class JadwalValidator
{


    public function cekBentrokRuangan(
        $ruanganId,
        $hari,
        $jamMulai,
        $jamSelesai
    )
    {

        return Jadwal::where('ruangan_id',$ruanganId)

        ->where('hari',$hari)

        ->where(function($query) use ($jamMulai,$jamSelesai){

            $query->where('jam_mulai','<',$jamSelesai)
            ->where('jam_selesai','>',$jamMulai);

        })

        ->exists();

    }



    public function cekBentrokDosen(
        $dosenId,
        $hari,
        $jamMulai,
        $jamSelesai
    )
    {

        return Jadwal::where('hari',$hari)

        ->where(function($query) use ($jamMulai,$jamSelesai){

            $query->where('jam_mulai','<',$jamSelesai)
            ->where('jam_selesai','>',$jamMulai);

        })

        ->whereHas('dosen',function($query) use ($dosenId){

            $query->where('dosen.id',$dosenId);

        })

        ->exists();

    }



    public function cekKetersediaanDosen(
        $dosenId,
        $hari,
        $jamMulai,
        $jamSelesai
    )
    {

        return !$this->cekBentrokDosen(
            $dosenId,
            $hari,
            $jamMulai,
            $jamSelesai
        );

    }

}