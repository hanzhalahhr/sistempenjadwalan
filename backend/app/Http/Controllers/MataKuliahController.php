<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;

class MataKuliahController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MataKuliah::with(['kelasPerkuliahan.prodi'])->get();

        return response()->json($data);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mataKuliah = MataKuliah::create([

            'kode_mk' => $request->kode_mk,

            'nama_mk' => $request->nama_mk,

            'sks' => $request->sks,

            'semester' => $request->semester

        ]);


        return response()->json([

            'message'=>'Data berhasil disimpan'

        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $mataKuliah = MataKuliah::findOrFail($id);


        $mataKuliah->update([

            'nama_mk'=>$request->nama_mk,

            'sks'=>$request->sks

        ]);


        return response()->json([

            'message'=>'Data mata kuliah berhasil diperbarui'

        ]);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}