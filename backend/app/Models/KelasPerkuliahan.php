<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahan extends Model
{
    protected $table = 'kelas_perkuliahan';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'prodi_id',
        'semester_akademik_id',
        'nama_kelas',
        'jumlah_mahasiswa'
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(
            MataKuliah::class,
            'mata_kuliah_id'
        );
    }
    
    public function prodi()
    {
        return $this->belongsTo(
            Prodi::class,
            'prodi_id'
        );
    }

}