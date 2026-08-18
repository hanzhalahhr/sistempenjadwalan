<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN PROFILE USER YANG SEDANG LOGIN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER DARI TOKEN SANCTUM
        |--------------------------------------------------------------------------
        */

        $mahasiswa = $request->user();


        /*
        |--------------------------------------------------------------------------
        | CEK USER
        |--------------------------------------------------------------------------
        */

        if (!$mahasiswa) {

            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);

        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE PROFILE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'message' => 'Data profile berhasil diambil.',

            'user' => [

                'id' => $mahasiswa->id,

                'nim' => $mahasiswa->nim,

                'username' => $mahasiswa->username,

                'nama' =>
                    $mahasiswa->nama
                    ?? $mahasiswa->nama_mahasiswa
                    ?? $mahasiswa->nama_lengkap
                    ?? '-',

                'role' =>
                    $mahasiswa->role
                    ?? $mahasiswa->jabatan
                    ?? 'Administrator Akademik',

                'status' => 'Aktif'

            ]

        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER DARI TOKEN SANCTUM
        |--------------------------------------------------------------------------
        */

        $mahasiswa = $request->user();


        /*
        |--------------------------------------------------------------------------
        | CEK USER
        |--------------------------------------------------------------------------
        */

        if (!$mahasiswa) {

            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'current_password' => [
                'required'
            ],

            'new_password' => [
                'required',
                'min:6'
            ],

            'new_password_confirmation' => [
                'required',
                'same:new_password'
            ]

        ], [

            'current_password.required' =>
                'Password saat ini wajib diisi.',

            'new_password.required' =>
                'Password baru wajib diisi.',

            'new_password.min' =>
                'Password baru minimal 6 karakter.',

            'new_password_confirmation.required' =>
                'Konfirmasi password baru wajib diisi.',

            'new_password_confirmation.same' =>
                'Konfirmasi password baru tidak cocok.'

        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK PASSWORD LAMA
        |--------------------------------------------------------------------------
        |
        | Password sistem saat ini masih menggunakan user_password
        | secara langsung, jadi kita pertahankan mekanisme tersebut.
        |
        */

        if (
            $request->current_password
            !== $mahasiswa->user_password
        ) {

            return response()->json([

                'message' =>
                    'Password saat ini salah.'

            ], 422);

        }


        /*
        |--------------------------------------------------------------------------
        | CEK PASSWORD BARU
        |--------------------------------------------------------------------------
        */

        if (
            $request->new_password
            === $request->current_password
        ) {

            return response()->json([

                'message' =>
                    'Password baru harus berbeda dengan password saat ini.'

            ], 422);

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        */

        $mahasiswa->user_password =
            $request->new_password;


        $mahasiswa->save();


        /*
        |--------------------------------------------------------------------------
        | RESPONSE BERHASIL
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'message' =>
                'Password berhasil diubah.'

        ], 200);
    }
}