<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';


    protected $fillable = [
        'nidn',
        'nama_dosen',
        'jabatan'
    ];


    public function mataKuliah()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'dosen_mata_kuliah',
            'dosen_id',
            'mata_kuliah_id'
        );
    }


    public function kelasPerkuliahan()
    {
        return $this->belongsToMany(
            KelasPerkuliahan::class,
            'kelas_perkuliahan_dosen',
            'dosen_id',
            'kelas_perkuliahan_id'
        );
    }


    public function ketersediaan()
    {
        return $this->hasMany(
            DosenKetersediaan::class,
            'dosen_id'
        );
    }

    public function jadwal()
    {
        return $this->belongsToMany(
            Jadwal::class,
            'jadwal_dosen',
            'dosen_id',
            'jadwal_id'
        );
    }
}