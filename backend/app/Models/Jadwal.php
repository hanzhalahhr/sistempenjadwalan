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


    public function generateJadwal()
    {
        return $this->belongsTo(
            GenerateJadwal::class,
            'generate_jadwal_id'
        );
    }


    public function kelasPerkuliahan()
    {
        return $this->belongsTo(
            KelasPerkuliahan::class,
            'kelas_perkuliahan_id'
        );
    }


    public function ruangan()
    {
        return $this->belongsTo(
            Ruangan::class,
            'ruangan_id'
        );
    }


    public function dosen()
    {
        return $this->belongsToMany(
            Dosen::class,
            'jadwal_dosen',
            'jadwal_id',
            'dosen_id'
        );
    }
}