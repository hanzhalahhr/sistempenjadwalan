<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);


        $mahasiswa = Mahasiswa::where(
            'username',
            $request->username
        )->first();


        if(!$mahasiswa)
        {
            return response()->json([
                'message'=>'Username tidak ditemukan'
            ],401);
        }


        if($request->password != $mahasiswa->user_password)
        {
            return response()->json([
                'message'=>'Password salah'
            ],401);
        }


        $token = $mahasiswa
            ->createToken('auth_token')
            ->plainTextToken;


        return response()->json([

            'message'=>'Login berhasil',

            'token'=>$token,

            'mahasiswa'=>$mahasiswa

        ]);

    }

}