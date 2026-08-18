<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfigurasiJadwal extends Model
{
    protected $table = 'konfigurasi_jadwal';

    protected $fillable = [
        'semester_akademik_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}