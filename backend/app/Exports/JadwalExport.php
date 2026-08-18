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
    protected $jadwal;
    protected $namaProdi;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    |
    | Mendukung:
    |
    | new JadwalExport($generateId)
    |
    | dan:
    |
    | new JadwalExport($jadwal, $namaProdi)
    |
    */

    public function __construct($data, $namaProdi = null)
    {

        if ($data instanceof \Illuminate\Support\Collection) {

            $this->jadwal = $data;

            $this->namaProdi = $namaProdi;

            $this->generateId = null;

        } else {

            $this->generateId = $data;

            $this->jadwal = null;

            $this->namaProdi = null;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    public function collection()
    {

        /*
        |--------------------------------------------------------------------------
        | JIKA EXPORT BERDASARKAN GENERATE ID
        |--------------------------------------------------------------------------
        */

        if ($this->jadwal === null) {

            $this->jadwal = Jadwal::with([

                'kelasPerkuliahan.mataKuliah',

                'kelasPerkuliahan.prodi',

                'kelasPerkuliahan.dosen',

                'ruangan'

            ])

            ->where(
                'generate_jadwal_id',
                $this->generateId
            )

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

            ->orderBy('jam_mulai')

            ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT DATA EXCEL
        |--------------------------------------------------------------------------
        */

        return $this->jadwal
            ->values()
            ->map(
                function ($jadwal, $index) {

                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL DOSEN DARI KELAS PERKULIAHAN
                    |--------------------------------------------------------------------------
                    */

                    $dosen = optional(
                        $jadwal->kelasPerkuliahan
                    )
                    ?->dosen
                    ?->pluck('nama_dosen')
                    ?->join(', ');


                    return [

                        'No' =>
                            $index + 1,


                        'Hari' =>
                            $jadwal->hari ?? '-',


                        'Jam' =>
                            ($jadwal->jam_mulai ?? '-') .
                            ' - ' .
                            ($jadwal->jam_selesai ?? '-'),


                        'Mata Kuliah' =>
                            optional(
                                optional(
                                    $jadwal->kelasPerkuliahan
                                )->mataKuliah
                            )->nama_mk ?? '-',


                        'Kelas' =>
                            optional(
                                $jadwal->kelasPerkuliahan
                            )->nama_kelas ?? '-',


                        'Ruangan' =>
                            optional(
                                $jadwal->ruangan
                            )->nama_ruangan ?? '-',


                        'Dosen' =>
                            $dosen ?: '-',

                    ];

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {

        return [

            'No',

            'Hari',

            'Jam',

            'Mata Kuliah',

            'Kelas',

            'Ruangan',

            'Dosen'

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | STYLE
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {

        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle(
                'A1:G' . $sheet->getHighestRow()
            )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        return [

            1 => [

                'font' => [

                    'bold' => true,

                    'color' => [

                        'rgb' => 'FFFFFF'

                    ]

                ],

                'fill' => [

                    'fillType' => 'solid',

                    'startColor' => [

                        'rgb' => '2563EB'

                    ]

                ],

                'alignment' => [

                    'horizontal' => 'center',

                    'vertical' => 'center'

                ]

            ]

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | LEBAR KOLOM
    |--------------------------------------------------------------------------
    */

    public function columnWidths(): array
    {

        return [

            'A' => 8,

            'B' => 15,

            'C' => 18,

            'D' => 35,

            'E' => 15,

            'F' => 18,

            'G' => 40,

        ];

    }

}