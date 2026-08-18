<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\KelasPerkuliahan;

class MataKuliahController extends Controller
{
    public function index()
    {
        $data = MataKuliah::with([
            'kelasPerkuliahan.prodi'
        ])->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $mataKuliah = MataKuliah::create([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'sks'      => $request->sks,
            'semester' => $request->semester,
            'jenis_mk' => $request->jenis_mk ?? 'Teori'
        ]);

        // Buat kelas default jika prodi dipilih
        if ($request->prodi_id) {

            KelasPerkuliahan::create([
                'mata_kuliah_id'       => $mataKuliah->id,
                'prodi_id'             => $request->prodi_id,
                'semester_akademik_id' => 1,
                'angkatan'             => 2026,
                'nama_kelas'           => 'A',
                'jumlah_mahasiswa'     => 0
            ]);

        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $mataKuliah
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        $mataKuliah->update([
            'kode_mk'  => $request->kode_mk,
            'nama_mk'  => $request->nama_mk,
            'sks'      => $request->sks,
            'semester' => $request->semester,
            'jenis_mk' => $request->jenis_mk ?? $mataKuliah->jenis_mk
        ]);

        if ($request->prodi_id) {

            $kelas = $mataKuliah->kelasPerkuliahan()->first();

            if ($kelas) {

                $kelas->update([
                    'prodi_id' => $request->prodi_id
                ]);

            } else {

                KelasPerkuliahan::create([
                    'mata_kuliah_id'       => $mataKuliah->id,
                    'prodi_id'             => $request->prodi_id,
                    'semester_akademik_id' => 1,
                    'angkatan'             => 2026,
                    'nama_kelas'           => 'A',
                    'jumlah_mahasiswa'     => 0
                ]);

            }
        }

        return response()->json([
            'message' => 'Data mata kuliah berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        // hapus semua kelas yang memakai mata kuliah ini
        $mataKuliah->kelasPerkuliahan()->delete();

        // baru hapus mata kuliahnya
        $mataKuliah->delete();

        return response()->json([
            "message" => "Data berhasil dihapus"
        ]);
    }
}