import React, { useEffect, useState } from 'react';
import axios from 'axios';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  CheckCircle,
  FileXls,
  FilePdf,
  Hash,
  CalendarBlank,
  SealCheck,
  Clock
} from '@phosphor-icons/react';

import '../styles/Dashboard.css';


const HasilGeneratePageBerhasil = ({ onNavigate }) => {


  const [jadwal, setJadwal] = useState([]);

  const [loading, setLoading] = useState(true);

  const [generateInfo, setGenerateInfo] = useState({});

  const [totalJadwal, setTotalJadwal] = useState(0);



  const getGenerateId = () => {

    const id = localStorage.getItem("generate_id");

    console.log(
      "GENERATE ID LOCAL STORAGE : ",
      id
    );

    return id;

  };



  const handleExportExcel = () => {

    const generateId = getGenerateId();


    if(!generateId){

      alert("Generate ID tidak ditemukan");

      return;

    }


    window.open(
      `http://127.0.0.1:8000/api/jadwal/export/${generateId}`,
      "_blank"
    );

  };



  const handleExportPdf = () => {

    const generateId = getGenerateId();


    if(!generateId){

      alert("Generate ID tidak ditemukan");

      return;

    }


    window.open(
      `http://127.0.0.1:8000/api/jadwal/pdf/${generateId}`,
      "_blank"
    );

  };



  

  useEffect(() => {

    const generateId = localStorage.getItem("generate_id");

    console.log(
        "AMBIL ID TERBARU:",
        generateId
    );


    if(!generateId){
        setLoading(false);
        return;
    }


    axios.get(
       `http://127.0.0.1:8000/api/jadwal/hasil/${generateId}`
    )

    .then(response=>{

        console.log(
            "HASIL GENERATE:",
            response.data
        );

        setJadwal(response.data.data);
        setGenerateInfo(response.data.generate);
        setTotalJadwal(response.data.data.length);

    })

    .finally(()=>{
        setLoading(false);
    });


}, []);








  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="riwayat-generate"

      pageTitle="Detail Hasil Generate Jadwal"

    >

      <div className="hasil-generate-card">

        <div className="success-status-container">

          <div className="icon-success-wrapper">

            <CheckCircle
              size={72}
              weight="fill"
            />

          </div>

          <h2 className="status-title-success">

            Jadwal Berhasil Dibuat

          </h2>

          <p className="status-subtitle">

            Sistem berhasil membuat jadwal perkuliahan tanpa bentrok.

          </p>

        </div>



        <div className="generate-summary">


          {/* GENERATE ID */}

          <div className="summary-card">

            <div className="summary-icon">

              <Hash
                size={24}
                weight="bold"
              />

            </div>

            <div className="summary-content generate-code">

              <span>
                Generate ID
              </span>

              <h3>
                {generateInfo.kode_generate}
              </h3>

            </div>

          </div>

          {/* TOTAL JADWAL */}

          <div className="summary-card">

            <div className="summary-icon">

              <CalendarBlank
                size={24}
                weight="fill"
              />

            </div>

            <div className="summary-content">
              <span>
                Total Jadwal
              </span>

              <h3>
                {totalJadwal}
              </h3>

            </div>

          </div>



          {/* TANGGAL */}

          <div className="summary-card">

            <div className="summary-icon">

              <CalendarBlank
                size={24}
                weight="fill"
              />

            </div>

            <div className="summary-content">

              <span>
                Tanggal Generate
              </span>

              <h3>

              {
                generateInfo.tanggal_generate &&
                new Date(
                  generateInfo.tanggal_generate
                )
                .toLocaleDateString(
                  "id-ID",
                  {
                    day:"numeric",
                    month:"long",
                    year:"numeric"
                  }
                )
              }

              </h3>

            </div>

          </div>


          {/* JAM */}

          <div className="summary-card">

            <div className="summary-icon">

              <Clock
                size={24}
                weight="fill"
              />

            </div>

            <div className="summary-content">

              <span>
                Waktu Generate
              </span>

              <h3>
              {
                generateInfo.tanggal_generate &&
                new Date(
                  generateInfo.tanggal_generate
                )
                .toLocaleTimeString(
                  "id-ID",
                  {
                    hour:"2-digit",
                    minute:"2-digit"
                  }
                )
              }

              {" WIB"}

              </h3>

            </div>

          </div>


          {/* STATUS */}

          <div className="summary-card">

            <div className="summary-icon success">

              <SealCheck
                size={24}
                weight="fill"
              />

            </div>

            <div className="summary-content">

              <span>
                Status
              </span>

              <div
                className={
                  generateInfo.status === "Berhasil"
                  ? "status-result success"
                  : "status-result failed"
                }
              >
                {generateInfo.status}

              </div>

            </div>

          </div>

        </div>


        {/* EXPORT */}

        <div className="hasil-action-button">

          <Button

            variant="secondary"

            onClick={handleExportExcel}
          >
            <FileXls size={20}/>

            Excel

          </Button>


          <Button

            variant="secondary"

            onClick={handleExportPdf}

          >

            <FilePdf size={20}/>

            PDF

          </Button>

        </div>


        {/* TABLE */}

        {

        loading ? (

          <h3>
            Mengambil data jadwal...
          </h3>

        ) : (

        <table className="table-preview-jadwal">

          <thead>

            <tr>

              <th>No</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Mata Kuliah</th>
              <th>Kelas</th>
              <th>Prodi</th>
              <th>Ruangan</th>
              <th>Dosen</th>

            </tr>

          </thead>

          <tbody>

          {

            jadwal.map((item,index)=>(

              <tr key={item.id}>

                <td>
                  {index+1}
                </td>

                <td>
                  {item.hari}
                </td>

                <td>
                  {item.jam_mulai}
                  {" - "}
                  {item.jam_selesai}
                </td>

                <td>
                  {item.kelas_perkuliahan?.mata_kuliah?.nama_mk}
                </td>

                <td>
                  {item.kelas_perkuliahan?.nama_kelas}
                </td>

                <td>
                  {item.kelas_perkuliahan?.prodi?.nama_prodi}
                </td>

                <td>
                  {item.ruangan?.nama_ruangan}
                </td>

                <td>
                {
                  item.dosen?.map(
                    dosen => dosen.nama_dosen
                  )
                  .join(", ")
                }

                </td>

              </tr>

            ))

          }

          </tbody>

        </table>

        )

        }

      </div>


      <div className="footer-action-area">

        <Button

          variant="secondary"

          onClick={() => onNavigate("riwayat-generate")}
        >
          Kembali

        </Button>


        <Button
          variant="primary"
          onClick={() => onNavigate("dashboard")}
        >
          Selesai

        </Button>

      </div>

    </DashboardLayout>

  );

};


export default HasilGeneratePageBerhasil;