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
}