<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\KelasPerkuliahan;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'semester',
        'sks'
    ];

    public function dosen()
    {
        return $this->belongsToMany(
            Dosen::class,
            'dosen_mata_kuliah',
            'mata_kuliah_id',
            'dosen_id'
        );
    }

    public function kelasPerkuliahan()
    {
        return $this->hasMany(
            KelasPerkuliahan::class,
            'mata_kuliah_id'
        );
    }
}   