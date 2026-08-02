<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SlotWaktuKuliah;

class SlotWaktuUrutanSeeder extends Seeder
{
    public function run(): void
    {
        $hariList = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];

        foreach ($hariList as $hari) {

            $slots = SlotWaktuKuliah::where('hari', $hari)
                ->orderBy('jam_mulai')
                ->get();

            $urutan = 1;

            foreach ($slots as $slot) {

                $slot->update([
                    'urutan' => $urutan
                ]);

                $urutan++;
            }
        }
    }
}