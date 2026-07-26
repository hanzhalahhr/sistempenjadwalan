<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Model
{
    use HasApiTokens;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'username',
        'user_password'
    ];
}