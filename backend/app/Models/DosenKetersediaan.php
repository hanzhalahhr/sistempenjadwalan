<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenKetersediaan extends Model
{
    protected $table = 'dosen_ketersediaan';


    protected $fillable = [
        'dosen_id',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];


    public function dosen()
    {
        return $this->belongsTo(
            Dosen::class,
            'dosen_id'
        );
    }
}