<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\Ruangan;
use App\Models\SlotWaktuKuliah;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

class JadwalGenerator
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new JadwalValidator();
    }

    public function generate($generateJadwalId)
    {
        // penanda apakah ada kelas yang gagal dijadwalkan
        $gagal = false;
        $jumlahGagal = 0;

        // hapus hasil generate sebelumnya
        DB::table('jadwal_dosen')->delete();
        Jadwal::query()->delete();

        // ambil semua kelas beserta relasinya
        $kelas = KelasPerkuliahan::with([
            'dosen',
            'mataKuliah'
        ])->get();

        // ambil semua slot waktu
        $slot = SlotWaktuKuliah::whereIn('hari', [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat'
        ])
        ->orderByRaw("
            FIELD(
                hari,
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat'
            )
        ")
        ->orderBy('urutan')
        ->get();

        foreach ($kelas as $itemKelas) {

            /*
            |--------------------------------------------------------------------------
            | CARI RUANGAN SESUAI JENIS MATA KULIAH
            |--------------------------------------------------------------------------
            */

            $jenisRuangan =
                $itemKelas->mataKuliah->jenis_mk == 'Praktikum'
                ? 'Lab'
                : 'Kelas';

            $ruangan = Ruangan::where(
                'tipe_ruangan',
                $jenisRuangan
            )
            ->where(
                'kapasitas',
                '>=',
                $itemKelas->jumlah_mahasiswa
            )
            ->get();

            if ($ruangan->count() == 0) {

                dump(
                    "RUANGAN TIDAK ADA : " . $itemKelas->nama_kelas
                );

                $gagal = true;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DURASI BERDASARKAN SKS
            |--------------------------------------------------------------------------
            */

            $sks = $itemKelas->mataKuliah->sks;

            $durasiSlot = $sks;

            $berhasil = false;

            foreach ($slot as $itemSlot) {

                /*
                |--------------------------------------------------------------------------
                | AMBIL SLOT SESUAI JUMLAH SKS
                |--------------------------------------------------------------------------
                */

                $slotDipakai = SlotWaktuKuliah::where(
                    'hari',
                    $itemSlot->hari
                )
                ->whereBetween(
                    'urutan',
                    [
                        $itemSlot->urutan,
                        $itemSlot->urutan + $durasiSlot - 1
                    ]
                )
                ->orderBy('urutan')
                ->get();

                if ($slotDipakai->count() < $durasiSlot) {
                    continue;
                }

                $jamMulai = $slotDipakai->first()->jam_mulai;

                $jamSelesai = $slotDipakai->last()->jam_selesai;

                foreach ($ruangan as $itemRuangan) {

                    /*
                    |--------------------------------------------------------------------------
                    | CEK BENTROK RUANGAN
                    |--------------------------------------------------------------------------
                    */

                    $cekRuangan =
                        $this->validator->cekBentrokRuangan(
                            $itemRuangan->id,
                            $itemSlot->hari,
                            $jamMulai,
                            $jamSelesai
                        );

                    if ($cekRuangan) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CARI DOSEN YANG TERSEDIA
                    |--------------------------------------------------------------------------
                    */

                    $dosenDipilih = [];

                    foreach ($itemKelas->dosen as $dosen) {

                        $tersedia =
                            $this->validator->cekKetersediaanDosen(
                                $dosen->id,
                                $itemSlot->hari,
                                $jamMulai,
                                $jamSelesai
                            );

                        if ($tersedia) {

                            $dosenDipilih[] = $dosen->id;

                            // satu kelas satu dosen
                            break;
                        }
                    }

                    if (count($dosenDipilih) == 0) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN JADWAL
                    |--------------------------------------------------------------------------
                    */

                    $jadwal = Jadwal::create([

                        'generate_jadwal_id' => $generateJadwalId,

                        'kelas_perkuliahan_id' => $itemKelas->id,

                        'ruangan_id' => $itemRuangan->id,

                        'hari' => $itemSlot->hari,

                        'jam_mulai' => $jamMulai,

                        'jam_selesai' => $jamSelesai,

                    ]);

                    // simpan dosen pengajar
                    $jadwal->dosen()->attach(
                        $dosenDipilih
                    );

                    $berhasil = true;

                    break 2;
                }
            }

            if (!$berhasil) {

                $gagal = true;

                $jumlahGagal++;

                logger("KELAS GAGAL: " . $itemKelas->nama_kelas);
            }
        }

        // true = semua kelas berhasil dijadwalkan
        // false = ada minimal satu kelas gagal
        logger("TOTAL GAGAL = " . $jumlahGagal);

        return !$gagal;
    }
}