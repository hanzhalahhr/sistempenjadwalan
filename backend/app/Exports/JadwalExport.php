<?php

namespace App\Exports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;


class JadwalExport implements 
    FromCollection, 
    WithHeadings,
    WithStyles,
    WithColumnWidths
{


    protected $generateId;



    public function __construct($generateId)
    {

        $this->generateId = $generateId;

    }




    public function collection()
    {

        return Jadwal::with([

            'kelasPerkuliahan.mataKuliah',
            'kelasPerkuliahan.prodi',
            'ruangan',
            'dosen'

        ])
        ->where(
            'generate_jadwal_id',
            $this->generateId
        )
        ->get()
        ->map(function($jadwal){


            return [

                'Hari' => $jadwal->hari,


                'Jam' => 
                    $jadwal->jam_mulai .
                    ' - ' .
                    $jadwal->jam_selesai,


                'Mata Kuliah' =>
                    $jadwal
                    ->kelasPerkuliahan
                    ->mataKuliah
                    ->nama_mk,


                'Kelas' =>
                    $jadwal
                    ->kelasPerkuliahan
                    ->nama_kelas,


                'Prodi' =>
                    $jadwal
                    ->kelasPerkuliahan
                    ->prodi
                    ->nama_prodi,


                'Ruangan' =>
                    $jadwal
                    ->ruangan
                    ->nama_ruangan,


                'Dosen' =>
                    $jadwal
                    ->dosen
                    ->pluck('nama_dosen')
                    ->join(', ')

            ];


        });


    }




    public function headings(): array
    {

        return [

            'Hari',
            'Jam',
            'Mata Kuliah',
            'Kelas',
            'Prodi',
            'Ruangan',
            'Dosen'

        ];

    }





    public function styles(Worksheet $sheet)
    {


        // Border semua tabel
        $sheet->getStyle(
            'A1:G'.$sheet->getHighestRow()
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );



        // Style Header
        return [

            1 => [

                'font'=>[

                    'bold'=>true,

                    'color'=>[

                        'rgb'=>'FFFFFF'

                    ]

                ],


                'fill'=>[

                    'fillType'=>'solid',

                    'startColor'=>[

                        'rgb'=>'2563EB'

                    ]

                ],


                'alignment'=>[

                    'horizontal'=>'center',

                    'vertical'=>'center'

                ]

            ]

        ];


    }





    public function columnWidths(): array
    {

        return [

            'A'=>15, // Hari

            'B'=>18, // Jam

            'C'=>35, // Mata Kuliah

            'D'=>15, // Kelas

            'E'=>25, // Prodi

            'F'=>18, // Ruangan

            'G'=>35, // Dosen


        ];

    }


}