<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahan extends Model
{
    protected $table = 'kelas_perkuliahan';

    protected $fillable = [
        'mata_kuliah_id',
        'prodi_id',
        'semester_akademik_id',
        'angkatan',
        'nama_kelas',
        'jumlah_mahasiswa'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI DOSEN
    |--------------------------------------------------------------------------
    |
    | Satu kelas perkuliahan dapat memiliki satu atau lebih dosen.
    |
    */

    public function dosen()
    {
        return $this->belongsToMany(
            Dosen::class,
            'kelas_perkuliahan_dosen',
            'kelas_perkuliahan_id',
            'dosen_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI MATA KULIAH
    |--------------------------------------------------------------------------
    |
    | Setiap kelas perkuliahan memiliki satu mata kuliah.
    |
    */

    public function mataKuliah()
    {
        return $this->belongsTo(
            MataKuliah::class,
            'mata_kuliah_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI PROGRAM STUDI
    |--------------------------------------------------------------------------
    |
    | Setiap kelas perkuliahan berada pada satu program studi.
    |
    */

    public function prodi()
    {
        return $this->belongsTo(
            Prodi::class,
            'prodi_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI JADWAL
    |--------------------------------------------------------------------------
    |
    | Satu kelas perkuliahan dapat memiliki jadwal.
    |
    */

    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'kelas_perkuliahan_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI SEMESTER AKADEMIK
    |--------------------------------------------------------------------------
    |
    | Setiap kelas perkuliahan berada pada satu semester akademik.
    |
    */

    public function semesterAkademik()
    {
        return $this->belongsTo(
            SemesterAkademik::class,
            'semester_akademik_id'
        );
    }
}