<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDosen extends Model
{
    protected $table = 'jadwal_dosen';


    protected $fillable = [
        'jadwal_id',
        'dosen_id'
    ];


    public function jadwal()
    {
        return $this->belongsTo(
            Jadwal::class,
            'jadwal_id'
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