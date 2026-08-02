<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasPerkuliahan;


class KelasPerkuliahanController extends Controller
{

    public function index()
    {
        $kelasPerkuliahan = KelasPerkuliahan::with([
            'mataKuliah',
            'prodi',
            'semesterAkademik',
            'dosen'
        ])->get();

        return response()->json($kelasPerkuliahan);
    }


    public function store(Request $request)
    {

        $kelas = KelasPerkuliahan::create([

            'mata_kuliah_id'=>$request->mata_kuliah_id,
            'prodi_id'=>$request->prodi_id,
            'semester_akademik_id'=>$request->semester_akademik_id,
            'angkatan'=>$request->angkatan,
            'nama_kelas'=>$request->nama_kelas,
            'jumlah_mahasiswa'=>$request->jumlah_mahasiswa

        ]);


        $kelas->dosen()->sync(
            $request->dosen_ids ?? []
        );

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas->load([
                'mataKuliah',
                'prodi',
                'semesterAkademik',
                'dosen'
            ])
        ]);

    }


    public function show(string $id)
    {
        $kelasPerkuliahan = KelasPerkuliahan::with([
            'mataKuliah',
            'prodi',
            'semesterAkademik',
            'dosen'
        ])->findOrFail($id);


        return response()->json($kelasPerkuliahan);
    }



    public function update(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::findOrFail($id);

        $kelas->update([
            'mata_kuliah_id'=>$request->mata_kuliah_id,
            'prodi_id'=>$request->prodi_id,
            'semester_akademik_id'=>$request->semester_akademik_id,
            'nama_kelas'=>$request->nama_kelas,
            'jumlah_mahasiswa'=>$request->jumlah_mahasiswa,
            'angkatan'=>$request->angkatan,
        ]);

        $kelas->dosen()->sync(
            $request->dosen_ids ?? []
        );

        return response()->json([
            'message' => 'Update berhasil',
            'data' => $kelas->load([
                'mataKuliah',
                'prodi',
                'semesterAkademik',
                'dosen'
            ])
        ]);
    }



    public function destroy(string $id)
    {
        $kelasPerkuliahan = KelasPerkuliahan::findOrFail($id);


        $kelasPerkuliahan->delete();


        return response()->json([
            'message'=>'Data kelas perkuliahan berhasil dihapus'
        ]);
    }

}