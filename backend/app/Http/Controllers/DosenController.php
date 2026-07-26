<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{

    public function index()
    {
        $dosen = Dosen::with('mataKuliah')->get();

        return response()->json($dosen);
    }



    public function store(Request $request)
    {

        $dosen = Dosen::create([

            'nidn' => $request->nidn,

            'nama_dosen' => $request->nama_dosen,

            'jabatan' => $request->jabatan

        ]);

        if($request->has('mata_kuliah_ids')){

            $dosen->mataKuliah()->sync(

                $request->mata_kuliah_ids

            );

        }

        return response()->json([

            'message' => 'Data dosen berhasil ditambahkan',

            'data' => $dosen

        ],201);
    }







    public function update(Request $request, string $id)
    {

        $dosen = Dosen::findOrFail($id);


        $dosen->update([

            'nidn' => $request->nidn,

            'nama_dosen' => $request->nama_dosen,

            'jabatan' => $request->jabatan

        ]);


        if($request->has('mata_kuliah_ids')){

            $dosen->mataKuliah()->sync(
                $request->mata_kuliah_ids
            );

        }


        return response()->json([
            'message'=>'Data dosen berhasil diperbarui'
        ]);

    }


    public function destroy(string $id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->delete();

        return response()->json([
            'message'=>'Data dosen berhasil dihapus'
        ]);
    }

}