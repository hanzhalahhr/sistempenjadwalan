import React, { useEffect, useState } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Button from '../components/Button';
import Badge from '../components/Badge';
import { Eye, DownloadSimple } from '@phosphor-icons/react';
import axios from 'axios';
import '../styles/Dashboard.css';

const JadwalPage = ({ onNavigate }) => {

  const [dataJadwal, setDataJadwal] = useState([]);
  const [generate, setGenerate] = useState(null);

  const [semester, setSemester] = useState("Ganjil 2026/2027");


  useEffect(() => {

    axios.get(
      'http://127.0.0.1:8000/api/jadwal'
    )
    .then(response => {

      console.log(response.data);

      setGenerate(response.data.generate);

      setDataJadwal(response.data.data);

    })
    .catch(error => {

      console.log(
        "Gagal mengambil jadwal",
        error
      );

    });

  }, []);



  return (

    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="jadwal"
      pageTitle="Jadwal Perkuliahan"
      pageSubtitle="Semua Program Studi - Jadwal Fix"
    >


      {/* FILTER SEMESTER */}

      <div style={{
        marginBottom:"20px"
      }}>

        <label>
          Semester :
        </label>

        <select
          value={semester}
          onChange={(e)=>setSemester(e.target.value)}
        >

          <option>
            Ganjil 2026/2027
          </option>

          <option>
            Genap 2026/2027
          </option>

        </select>


      </div>



      {/* INFO GENERATE TERAKHIR */}

      {generate && (

        <div style={{
          marginBottom:"20px"
        }}>


          <h3>
            Generate Terakhir
          </h3>


          <p>
            Kode Generate : {generate.kode_generate}
          </p>


          <p>
            Tanggal : {generate.tanggal}
          </p>


          <p>

            Status :

            <Badge
              type="status"
              status="berhasil"
            >
              {generate.status}
            </Badge>

          </p>


        </div>

      )}






      {/* TABLE PRODI */}

      <Table

        headers={[
          "No",
          "Prodi",
          "Jumlah Jadwal",
          "Status",
          "Aksi"
        ]}

      >

        {dataJadwal.map((item, i) => (

          <tr key={item.id}>


            <td>
              {i + 1}
            </td>


            <td>
              {item.prodi}
            </td>


            <td>
              {item.jumlah_jadwal}
            </td>


            <td>

              <Badge
                type="status"
                status="berhasil"
              >
                {item.status}
              </Badge>

            </td>


            <td>

              <div
                style={{
                  display:'flex',
                  justifyContent:'center',
                  gap:'8px'
                }}
              >

                <Button
                  variant="table-icon eye"
                  icon={Eye}
                  title="Lihat Jadwal"
                  onClick={()=>{
                    onNavigate(
                      "detail-jadwal",
                      item.id
                    )
                  }}
                />


                <Button
                  variant="table-icon download"
                  icon={DownloadSimple}
                  title="Unduh Jadwal"
                />

              </div>

            </td>


          </tr>

        ))}


      </Table>


    </DashboardLayout>

  );

};


export default JadwalPage;