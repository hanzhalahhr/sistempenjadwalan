<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateJadwal extends Model
{
    protected $table = 'generate_jadwal';

    protected $fillable = [
        'semester_akademik_id',
        'generate_ke',
        'kode_generate',
        'tanggal_generate',
        'status',
    ];

    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'generate_jadwal_id'
        );
    }

    public function semesterAkademik()
    {
        return $this->belongsTo(
            SemesterAkademik::class,
            'semester_akademik_id'
        );
    }
}