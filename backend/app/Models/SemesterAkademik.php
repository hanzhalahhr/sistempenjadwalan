<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterAkademik extends Model
{
    protected $table = 'semester_akademik';

    protected $fillable = [
        'tahun_akademik',
        'periode',
        'is_active'
    ];
}