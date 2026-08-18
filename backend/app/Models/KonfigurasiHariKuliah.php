<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfigurasiHariKuliah extends Model
{
    protected $table = 'konfigurasi_hari_kuliah';

    protected $fillable = [
        'semester_akademik_id',
        'hari',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}