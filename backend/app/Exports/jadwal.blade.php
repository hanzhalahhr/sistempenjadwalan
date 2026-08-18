<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Jadwal Perkuliahan</title>

    <style>

        @page {
            margin: 25px 25px 30px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111;
        }

        .header {
            width: 100%;
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header h2 {
            margin: 4px 0 0 0;
            font-size: 12px;
            font-weight: normal;
        }

        .header .periode {
            margin-top: 4px;
            font-size: 10px;
        }

        .info {
            width: 100%;
            margin-bottom: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }

        .info-label {
            width: 100px;
            font-weight: bold;
        }

        .jadwal-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .jadwal-table th,
        .jadwal-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .jadwal-table th {
            text-align: center;
            font-weight: bold;
            background: #eeeeee;
        }

        .jadwal-table td {
            text-align: left;
        }

        .center {
            text-align: center !important;
        }

        .no {
            width: 4%;
        }

        .hari {
            width: 9%;
        }

        .jam {
            width: 11%;
        }

        .mata-kuliah {
            width: 20%;
        }

        .kelas {
            width: 9%;
        }

        .prodi {
            width: 15%;
        }

        .dosen {
            width: 18%;
        }

        .ruangan {
            width: 10%;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
        }

        .dosen-item {
            margin-bottom: 2px;
        }

        .empty {
            text-align: center;
            padding: 15px;
        }

    </style>

</head>

<body>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="header">

        <h1>
            JADWAL PERKULIAHAN
        </h1>

        <h2>
            Sistem Informasi
        </h2>

        @if(!empty($tahunAkademik) || !empty($periode))

            <div class="periode">

                Tahun Akademik:

                {{ $tahunAkademik ?? '-' }}

                -

                {{ $periode ?? '-' }}

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMASI GENERATE --}}
    {{-- ========================================================= --}}

    <div class="info">

        <table class="info-table">

            <tr>

                <td class="info-label">
                    Kode Generate
                </td>

                <td>
                    :
                    {{ $generateId ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="info-label">
                    Tanggal Generate
                </td>

                <td>
                    :
                    {{ $tanggalGenerate ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="info-label">
                    Status
                </td>

                <td>
                    :
                    {{ $status ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- ========================================================= --}}
    {{-- TABEL JADWAL --}}
    {{-- ========================================================= --}}

    <table class="jadwal-table">

        <thead>

            <tr>

                <th class="no">
                    No
                </th>

                <th class="hari">
                    Hari
                </th>

                <th class="jam">
                    Jam
                </th>

                <th class="mata-kuliah">
                    Mata Kuliah
                </th>

                <th class="kelas">
                    Kelas
                </th>

                <th class="prodi">
                    Program Studi
                </th>

                <th class="dosen">
                    Dosen
                </th>

                <th class="ruangan">
                    Ruangan
                </th>

            </tr>

        </thead>

        <tbody>

            @if(empty($jadwal))

                <tr>

                    <td
                        colspan="8"
                        class="empty"
                    >
                        Tidak ada data jadwal.
                    </td>

                </tr>

            @else

                @foreach($jadwal as $index => $item)

                    <tr>

                        {{-- NO --}}

                        <td class="center">

                            {{ $index + 1 }}

                        </td>


                        {{-- HARI --}}

                        <td class="center">

                            {{ $item['hari'] ?? '-' }}

                        </td>


                        {{-- JAM --}}

                        <td class="center">

                            @if(
                                !empty($item['jam_mulai']) &&
                                !empty($item['jam_selesai'])
                            )

                                {{ $item['jam_mulai'] }}

                                -

                                {{ $item['jam_selesai'] }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- MATA KULIAH --}}

                        <td>

                            {{ $item['mata_kuliah'] ?? '-' }}

                        </td>


                        {{-- KELAS --}}

                        <td class="center">

                            {{ $item['kelas'] ?? '-' }}

                        </td>


                        {{-- PROGRAM STUDI --}}

                        <td>

                            {{ $item['prodi'] ?? '-' }}

                            @if(!empty($item['jenjang']))

                                ({{ $item['jenjang'] }})

                            @endif

                        </td>


                        {{-- DOSEN --}}

                        <td>

                            @php

                                $dosenData = $item['dosen'] ?? [];

                            @endphp

                            @if(is_array($dosenData) && count($dosenData) > 0)

                                @foreach($dosenData as $namaDosen)

                                    <div class="dosen-item">

                                        {{ $namaDosen }}

                                    </div>

                                @endforeach

                            @elseif(
                                is_string($dosenData) &&
                                trim($dosenData) !== ''
                            )

                                {{ $dosenData }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- RUANGAN --}}

                        <td class="center">

                            {{ $item['ruangan'] ?? '-' }}

                        </td>

                    </tr>

                @endforeach

            @endif

        </tbody>

    </table>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <div class="footer">

        Dicetak dari Sistem Penjadwalan Perkuliahan.

    </div>

</body>

</html>