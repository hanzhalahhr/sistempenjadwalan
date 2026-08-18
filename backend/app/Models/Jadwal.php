<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'generate_jadwal_id',
        'kelas_perkuliahan_id',
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI GENERATE JADWAL
    |--------------------------------------------------------------------------
    */

    public function generateJadwal()
    {
        return $this->belongsTo(
            GenerateJadwal::class,
            'generate_jadwal_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KELAS PERKULIAHAN
    |--------------------------------------------------------------------------
    */

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(
            KelasPerkuliahan::class,
            'kelas_perkuliahan_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI RUANGAN
    |--------------------------------------------------------------------------
    */

    public function ruangan()
    {
        return $this->belongsTo(
            Ruangan::class,
            'ruangan_id'
        );
    }
}