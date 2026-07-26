<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateJadwal extends Model
{
    protected $table = 'generate_jadwal';

    protected $fillable = [
        'semester_akademik_id',
        'generate_ke',
        'tanggal_generate',
        'status',
        'created_by'
    ];
}