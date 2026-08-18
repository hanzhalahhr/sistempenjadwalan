<?php

namespace App\Http\Controllers;

use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class SemesterAkademikController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR SEMESTER
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $semester = SemesterAkademik::orderBy(
            'tahun_akademik'
        )
        ->orderByRaw("
            FIELD(
                periode,
                'Ganjil',
                'Genap'
            )
        ")
        ->get();


        return response()->json($semester);
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH SEMESTER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_akademik' => [
                'required',
                'string',
                'max:20'
            ],

            'periode' => [
                'required',
                'in:Ganjil,Genap'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ]
        ]);


        $semester = SemesterAkademik::create(
            $validated
        );


        return response()->json([
            'message' => 'Semester akademik berhasil ditambahkan.',
            'data' => $semester
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $semester = SemesterAkademik::findOrFail($id);

        return response()->json($semester);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $semester = SemesterAkademik::findOrFail($id);


        $validated = $request->validate([
            'tahun_akademik' => [
                'required',
                'string',
                'max:20'
            ],

            'periode' => [
                'required',
                'in:Ganjil,Genap'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ]
        ]);


        $semester->update(
            $validated
        );


        return response()->json([
            'message' => 'Semester akademik berhasil diperbarui.',
            'data' => $semester
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $semester = SemesterAkademik::findOrFail($id);

        $semester->delete();


        return response()->json([
            'message' => 'Semester akademik berhasil dihapus.'
        ]);
    }
}