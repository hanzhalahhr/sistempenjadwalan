<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahanDosen extends Model
{
    protected $table = 'kelas_perkuliahan_dosen';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'dosen_id'
    ];


    public function kelasPerkuliahan()
    {
        return $this->belongsTo(
            KelasPerkuliahan::class,
            'kelas_perkuliahan_id'
        );
    }


    public function dosen()
    {
        return $this->belongsTo(
            Dosen::class,
            'dosen_id'
        );
    }
}