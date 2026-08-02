<?php

namespace App\Http\Controllers;


use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    
    public function index()
    {
        return response()->json(
            Ruangan::all()
        );
    }



    
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan'=>'required',
            'lantai'=>'required',
            'gedung'=>'required',
            'kapasitas'=>'required',
            'tipe_ruangan'=>'required'
        ]);


        $ruangan = Ruangan::create([

            'nama_ruangan'=>$request->nama_ruangan,

            'lantai'=>$request->lantai,

            'gedung'=>$request->gedung,

            'kapasitas'=>$request->kapasitas,

            'tipe_ruangan'=>$request->tipe_ruangan

        ]);


        return response()->json([
            'message'=>'Data ruangan berhasil ditambahkan',
            'data'=>$ruangan
        ]);
    }



    
    public function show(string $id)
    {
        //
    }



    
    public function update(Request $request, string $id)
    {

        $request->validate([

            'nama_ruangan' => 'required',

            'lantai' => 'required|integer',

            'gedung' => 'required',

            'kapasitas' => 'required|integer',

            'tipe_ruangan' => 'required'

        ]);

        $ruangan = Ruangan::findOrFail($id);

        $ruangan->update([

            'nama_ruangan' => $request->nama_ruangan,

            'lantai' => $request->lantai,

            'gedung' => $request->gedung,

            'kapasitas' => $request->kapasitas,

            'tipe_ruangan' => $request->tipe_ruangan

        ]);

        return response()->json([

            'message' => 'Data ruangan berhasil diperbarui',

            'data' => $ruangan

        ]);

    }

    

    public function destroy(string $id)
    {

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        return response()->json([
            'message'=>'Data ruangan berhasil dihapus'
        ]);

    }

}
