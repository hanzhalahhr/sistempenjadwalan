<?php

namespace App\Services;

use App\Models\GenerateJadwal;
use App\Models\Jadwal;
use App\Models\JadwalDosen;
use App\Models\KelasPerkuliahan;
use App\Models\Ruangan;
use App\Models\SlotWaktuKuliah;
use Carbon\Carbon;

class GenerateJadwalService
{
    public function generate()
    {

        $generate = GenerateJadwal::create([
        'semester_akademik_id' => 1,
        'generate_ke' => GenerateJadwal::count() + 1,
        'tanggal_generate' => Carbon::now(),
        'status' => 'Berhasil'
        ]);


        $kelas = KelasPerkuliahan::with([
            'mataKuliah',
            'dosen',
            'prodi'
        ])->first();



        $slotWaktu = SlotWaktuKuliah::orderBy(
            'hari'
        )
        ->orderBy(
            'jam_mulai'
        )
        ->get();

        $jumlahSlot = $kelas->mataKuliah->sks;

        $slotDipilih = $this->cariSlotBerurutan(
            $jumlahSlot,
            $slotWaktu
        );

        $ruangan = Ruangan::first();




        $jadwal = Jadwal::create([
            'generate_jadwal_id'   => $generate->id,
            'kelas_perkuliahan_id' => $kelas->id,
            'ruangan_id'           => $ruangan->id,
            'hari'                 => $slotDipilih->first()->hari,
            'jam_mulai'            => $slotDipilih->first()->jam_mulai,
            'jam_selesai'          => $slotDipilih->last()->jam_selesai
        ]);


        foreach ($kelas->dosen as $dosen) {

            JadwalDosen::create([
                'jadwal_id' => $jadwal->id,
                'dosen_id'  => $dosen->id
            ]);

        }

        echo "Generate berhasil\n";


        echo "Mata Kuliah : ".$kelas->mataKuliah->nama_mk."\n";

        echo "SKS : ".$kelas->mataKuliah->sks."\n";

        
        echo "Slot : ".
        $slotDipilih->first()->hari." ".
        $slotDipilih->first()->jam_mulai.
        " - ".
        $slotDipilih->last()->jam_selesai.
        "\n";


        echo "Ruangan : ".$ruangan->nama_ruangan."\n";



        echo "\n";

        echo "Slot yang dipakai\n";

        foreach ($slotDipilih as $slot) {

            echo $slot->hari." ";

            echo $slot->jam_mulai;

            echo " - ";

            echo $slot->jam_selesai;

            echo "\n";
        }


        foreach($kelas->dosen as $dosen){

            echo "Dosen : ".$dosen->nama_dosen."\n";

        }

    }


    /*
|--------------------------------------------------------------------------
| Cari slot sesuai jumlah SKS
|--------------------------------------------------------------------------
*/

private function cariSlotBerurutan(
    $jumlahSlot,
    $slotWaktu
)
{

    $slotWaktu = $slotWaktu->values();

    for ($i = 0; $i <= $slotWaktu->count() - $jumlahSlot; $i++) {

        $potongan = $slotWaktu->slice(
            $i,
            $jumlahSlot
        );

        if ($potongan->count() == $jumlahSlot) {

            return $potongan;

        }

    }

    return null;

}









}