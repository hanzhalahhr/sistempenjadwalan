<?php

namespace App\Http\Controllers;

use App\Models\SlotWaktuKuliah;
use Illuminate\Http\Request;

class SlotWaktuKuliahController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN SEMUA SLOT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SlotWaktuKuliah::query();

        /*
        |--------------------------------------------------------------------------
        | FILTER HARI
        |--------------------------------------------------------------------------
        */

        if ($request->has('hari') && $request->hari !== '') {
            $query->where('hari', $request->hari);
        }

        /*
        |--------------------------------------------------------------------------
        | URUTKAN
        |--------------------------------------------------------------------------
        */

        $data = $query
            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu'
                )
            ")
            ->orderBy('urutan')
            ->get();

        return response()->json($data);
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH SLOT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => [
                'required',
                'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'
            ],

            'jam_mulai' => [
                'required',
                'date_format:H:i'
            ],

            'jam_selesai' => [
                'required',
                'date_format:H:i'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JAM
        |--------------------------------------------------------------------------
        */

        if ($validated['jam_mulai'] >= $validated['jam_selesai']) {

            return response()->json([
                'message' =>
                    'Jam mulai harus lebih kecil dari jam selesai.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN URUTAN
        |--------------------------------------------------------------------------
        */

        $urutan = SlotWaktuKuliah::where(
            'hari',
            $validated['hari']
        )->max('urutan');


        $urutan = ($urutan ?? 0) + 1;


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $slot = SlotWaktuKuliah::create([
            'hari' =>
                $validated['hari'],

            'urutan' =>
                $urutan,

            'jam_mulai' =>
                $validated['jam_mulai'],

            'jam_selesai' =>
                $validated['jam_selesai'],
        ]);


        return response()->json([
            'message' =>
                'Slot waktu berhasil ditambahkan.',

            'data' =>
                $slot
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN SATU SLOT
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $slot = SlotWaktuKuliah::find($id);

        if (!$slot) {

            return response()->json([
                'message' =>
                    'Slot waktu tidak ditemukan.'
            ], 404);
        }


        return response()->json($slot);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SLOT
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $slot = SlotWaktuKuliah::find($id);

        if (!$slot) {

            return response()->json([
                'message' =>
                    'Slot waktu tidak ditemukan.'
            ], 404);
        }


        $validated = $request->validate([
            'hari' => [
                'required',
                'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'
            ],

            'jam_mulai' => [
                'required',
                'date_format:H:i'
            ],

            'jam_selesai' => [
                'required',
                'date_format:H:i'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JAM
        |--------------------------------------------------------------------------
        */

        if ($validated['jam_mulai'] >= $validated['jam_selesai']) {

            return response()->json([
                'message' =>
                    'Jam mulai harus lebih kecil dari jam selesai.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA HARI BERUBAH
        |--------------------------------------------------------------------------
        */

        if ($slot->hari !== $validated['hari']) {

            $urutan = SlotWaktuKuliah::where(
                'hari',
                $validated['hari']
            )->max('urutan');


            $slot->urutan =
                ($urutan ?? 0) + 1;
        }


        $slot->hari =
            $validated['hari'];

        $slot->jam_mulai =
            $validated['jam_mulai'];

        $slot->jam_selesai =
            $validated['jam_selesai'];


        $slot->save();


        /*
        |--------------------------------------------------------------------------
        | RAPATKAN URUTAN HARI LAMA
        |--------------------------------------------------------------------------
        */

        // Kalau hari berubah, urutan di hari sebelumnya dirapikan.
        // Kita ambil berdasarkan data terbaru dari database.

        $this->rapikanUrutan(
            $slot->hari
        );


        return response()->json([
            'message' =>
                'Slot waktu berhasil diperbarui.',

            'data' =>
                $slot
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS SLOT
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $slot = SlotWaktuKuliah::find($id);

        if (!$slot) {

            return response()->json([
                'message' =>
                    'Slot waktu tidak ditemukan.'
            ], 404);
        }


        $hari = $slot->hari;


        $slot->delete();


        /*
        |--------------------------------------------------------------------------
        | RAPATKAN URUTAN
        |--------------------------------------------------------------------------
        */

        $this->rapikanUrutan($hari);


        return response()->json([
            'message' =>
                'Slot waktu berhasil dihapus.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RAPATKAN URUTAN SLOT
    |--------------------------------------------------------------------------
    */

    private function rapikanUrutan($hari)
    {
        $slots = SlotWaktuKuliah::where(
            'hari',
            $hari
        )
        ->orderBy('jam_mulai')
        ->get();


        foreach ($slots as $index => $item) {

            $item->urutan =
                $index + 1;

            $item->save();
        }
    }
}