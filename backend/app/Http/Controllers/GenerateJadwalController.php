<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\GenerateJadwal;
use App\Models\Jadwal;

use App\Services\JadwalGenerator;

use App\Exports\JadwalExport;
use Maatwebsite\Excel\Facades\Excel;

use Barryvdh\DomPDF\Facade\Pdf;


class GenerateJadwalController extends Controller
{


    /**
     * Menampilkan semua history generate
     */
    public function index()
    {
        $data = GenerateJadwal::latest()->get();

        return response()->json([
            "data" => $data
        ]);
    }



    /** * Generate jadwal */

    public function run(JadwalGenerator $generator)
    {

        $generateKe = (GenerateJadwal::max('generate_ke') ?? 0) + 1;

        $generate = GenerateJadwal::create([

            'semester_akademik_id' => 1,

            'generate_ke' => $generateKe,

            'kode_generate' =>
                'GEN-' . date('Y') . '-' . str_pad($generateKe, 3, '0', STR_PAD_LEFT),

            'tanggal_generate' => now(),

            'status' => 'Berhasil',

        ]);


        // Jalankan algoritma
        $hasil = $generator->generate(
            $generate->id
        );


        // Update status generate
        if ($hasil) {

            $generate->status = "Berhasil";

            $message = "Generate berhasil";

        } else {

            $generate->status = "Gagal";

            $message = "Generate gagal";

        }

        $generate->save();


        return response()->json([

            "message" => $message,

            "status" => $generate->status,

            "generate_id" => $generate->id,

            "data" => $hasil

        ]);

    }



    /**
     * Detail generate
     */
    public function show($id)
    {

        $generate = GenerateJadwal::find($id);

        if(!$generate){

            return response()->json([
                "message"=>"Data generate tidak ditemukan"
            ],404);

        }

        $jumlahJadwal = Jadwal::where(
            'generate_jadwal_id',
            $id
        )->count();



        return response()->json([

            "data"=>[

                "id"=>$generate->id,

                "kode_generate" => $generate->kode_generate,

                "tanggal_generate"=>$generate->tanggal_generate,

                "semester"=>$generate->semester_akademik_id,

                "status"=>$generate->status,

                "jumlah_jadwal"=>$jumlahJadwal

            ]

        ]);

    }



    /**
     * Hapus history + hasil jadwal
     */
    public function destroy($id)
    {

        $generate = GenerateJadwal::find($id);



        if(!$generate){

            return response()->json([
                "message"=>"Data tidak ditemukan"
            ],404);

        }



        DB::transaction(function() use ($id,$generate){


            $jadwal = Jadwal::where(
                'generate_jadwal_id',
                $id
            )->get();



            foreach($jadwal as $item){

                $item->dosen()->detach();

            }



            Jadwal::where(
                'generate_jadwal_id',
                $id
            )->delete();



            $generate->delete();


        });



        return response()->json([

            "message"=>"Riwayat generate berhasil dihapus"

        ]);

    }




    /**
     * Export Excel
     */
    public function exportExcel($id)
    {

        return Excel::download(

            new JadwalExport($id),

            'jadwal_generate_'.$id.'.xlsx'

        );

    }




    /**
     * Export PDF
     */
    public function exportPdf($id)
    {


        $jadwal = Jadwal::with([

            'kelasPerkuliahan.mataKuliah',

            'kelasPerkuliahan.prodi',

            'ruangan',

            'dosen'

        ])

        ->where(

            'generate_jadwal_id',

            $id

        )

        ->get();




        $pdf = Pdf::loadView(

            'exports.jadwal',

            [

                'jadwal'=>$jadwal,

                'generateId'=>$id

            ]

        );



        return $pdf->download(

            'jadwal_generate_'.$id.'.pdf'

        );


    }


}