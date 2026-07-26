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
}