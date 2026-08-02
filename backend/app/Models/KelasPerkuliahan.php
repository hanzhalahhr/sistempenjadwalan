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
    | Saat ini belum dipakai oleh input manual.
    | Akan digunakan nanti saat proses generate jadwal.
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
    */

    public function prodi()
    {
        return $this->belongsTo(
            Prodi::class,
            'prodi_id'
        );
    }


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
    */

    public function semesterAkademik()
    {
        return $this->belongsTo(
            SemesterAkademik::class,
            'semester_akademik_id'
        );
    }

}