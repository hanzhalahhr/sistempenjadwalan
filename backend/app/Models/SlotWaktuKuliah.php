<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotWaktuKuliah extends Model
{
    protected $table = 'slot_waktu_kuliah';

    protected $fillable = [
        'hari',
        'urutan',
        'jam_mulai',
        'jam_selesai'
    ];
}