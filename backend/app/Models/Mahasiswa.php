<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Model
{
    use HasApiTokens;

    /*
    |--------------------------------------------------------------------------
    | NAMA TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'mahasiswa';


    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';


    /*
    |--------------------------------------------------------------------------
    | TIMESTAMPS
    |--------------------------------------------------------------------------
    |
    | Tabel mahasiswa tidak memiliki:
    | created_at
    | updated_at
    |
    */

    public $timestamps = false;


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'username',
        'user_password'
    ];
}