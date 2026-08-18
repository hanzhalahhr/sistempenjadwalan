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

    /*
    |--------------------------------------------------------------------------
    | LABEL PERIODE
    |--------------------------------------------------------------------------
    |
    | Database:
    |
    | 1 = Ganjil
    | 2 = Genap
    |
    | Supaya frontend tidak menampilkan:
    |
    | Semester 1
    | Semester 2
    |
    | tetapi:
    |
    | Ganjil
    | Genap
    |
    */

    public function getPeriodeLabelAttribute()
    {
        return match ((string) $this->periode) {

            '1' => 'Ganjil',

            '2' => 'Genap',

            default => $this->periode

        };
    }
}