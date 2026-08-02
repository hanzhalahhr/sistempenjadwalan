<!DOCTYPE html>
<html>
<head>

    <title>Jadwal Perkuliahan</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size:12px;
        }


        table{

            width:100%;
            border-collapse:collapse;

        }


        th{

            background:#2563eb;
            color:white;
            padding:8px;
            border:1px solid #ddd;
            text-align:center;

        }


        td{

            padding:8px;
            border:1px solid #ddd;

        }


        h2{

            text-align:center;

        }

    </style>

</head>


<body>


<h2>
Jadwal Perkuliahan
</h2>


<table>


<thead>

<tr>

<th>
Hari
</th>

<th>
Jam
</th>

<th>
Mata Kuliah
</th>

<th>
Kelas
</th>

<th>
Prodi
</th>

<th>
Ruangan
</th>

<th>
Dosen
</th>


</tr>


</thead>



<tbody>


@foreach($jadwal as $item)


<tr>


<td>
{{ $item->hari }}
</td>


<td>
{{ $item->jam_mulai }} - {{ $item->jam_selesai }}
</td>


<td>
{{ $item->kelasPerkuliahan->mataKuliah->nama_mk ?? '-' }}
</td>


<td>
{{ $item->kelasPerkuliahan->nama_kelas ?? '-' }}
</td>


<td>
{{ $item->kelasPerkuliahan->prodi->nama_prodi ?? '-' }}
</td>


<td>
{{ $item->ruangan->nama_ruangan ?? '-' }}
</td>


<td>

@foreach($item->dosen as $dosen)

{{ $dosen->nama_dosen }}

@endforeach

</td>



</tr>


@endforeach



</tbody>


</table>


</body>
</html>