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


  /*
  |--------------------------------------------------------------------------
  | AMBIL GENERATE ID
  |--------------------------------------------------------------------------
  */

  const getGenerateId = () => {

    const id = localStorage.getItem('generate_id');

    console.log(
      'GENERATE ID LOCAL STORAGE:',
      id
    );

    return id;

  };


  /*
  |--------------------------------------------------------------------------
  | EXPORT EXCEL
  |--------------------------------------------------------------------------
  */

  const handleExportExcel = () => {

    const generateId = getGenerateId();

    if (!generateId) {

      alert('Generate ID tidak ditemukan');

      return;

    }

    window.open(
      `http://127.0.0.1:8000/api/jadwal/export/${generateId}`,
      '_blank'
    );

  };


  /*
  |--------------------------------------------------------------------------
  | EXPORT PDF
  |--------------------------------------------------------------------------
  */

  const handleExportPdf = () => {

    const generateId = getGenerateId();

    if (!generateId) {

      alert('Generate ID tidak ditemukan');

      return;

    }

    window.open(
      `http://127.0.0.1:8000/api/jadwal/pdf/${generateId}`,
      '_blank'
    );

  };


  /*
  |--------------------------------------------------------------------------
  | AMBIL HASIL GENERATE
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    const generateId =
      localStorage.getItem('generate_id');


    console.log(
      'AMBIL ID TERBARU:',
      generateId
    );


    if (!generateId) {

      console.warn(
        'Generate ID tidak ditemukan di localStorage'
      );

      setLoading(false);

      return;

    }


    axios
      .get(
        `http://127.0.0.1:8000/api/jadwal/hasil/${generateId}`
      )

      .then(response => {

        console.log(
          'HASIL GENERATE:',
          response.data
        );


        /*
        |--------------------------------------------------------------------------
        | DATA JADWAL
        |--------------------------------------------------------------------------
        */

        const dataJadwal =
          response.data.data || [];


        setJadwal(
          dataJadwal
        );


        /*
        |--------------------------------------------------------------------------
        | INFORMASI GENERATE
        |--------------------------------------------------------------------------
        */

        setGenerateInfo(
          response.data.generate || {}
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL JADWAL
        |--------------------------------------------------------------------------
        */

        setTotalJadwal(
          dataJadwal.length
        );

      })

      .catch(error => {

        console.error(
          'GAGAL MENGAMBIL HASIL GENERATE:',
          error
        );

        alert(
          'Gagal mengambil data hasil generate.'
        );

      })

      .finally(() => {

        setLoading(false);

      });

  }, []);


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="riwayat-generate"

      pageTitle="Detail Hasil Generate Jadwal"

    >

      <div className="hasil-generate-card">


        {/* ================================================================ */}
        {/* STATUS BERHASIL */}
        {/* ================================================================ */}

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



        {/* ================================================================ */}
        {/* SUMMARY GENERATE */}
        {/* ================================================================ */}

        <div className="generate-summary">


          {/* -------------------------------------------------------------- */}
          {/* GENERATE ID */}
          {/* -------------------------------------------------------------- */}

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

                {generateInfo.kode_generate || '-'}

              </h3>

            </div>

          </div>



          {/* -------------------------------------------------------------- */}
          {/* TOTAL JADWAL */}
          {/* -------------------------------------------------------------- */}

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



          {/* -------------------------------------------------------------- */}
          {/* TANGGAL GENERATE */}
          {/* -------------------------------------------------------------- */}

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
                  generateInfo.tanggal_generate
                    ? new Date(
                        generateInfo.tanggal_generate
                      ).toLocaleDateString(
                        'id-ID',
                        {
                          day: 'numeric',
                          month: 'long',
                          year: 'numeric'
                        }
                      )
                    : '-'
                }

              </h3>

            </div>

          </div>



          {/* -------------------------------------------------------------- */}
          {/* WAKTU GENERATE */}
          {/* -------------------------------------------------------------- */}

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
                  generateInfo.tanggal_generate
                    ? new Date(
                        generateInfo.tanggal_generate
                      ).toLocaleTimeString(
                        'id-ID',
                        {
                          hour: '2-digit',
                          minute: '2-digit'
                        }
                      )
                    : '-'
                }

                {' WIB'}

              </h3>

            </div>

          </div>



          {/* -------------------------------------------------------------- */}
          {/* STATUS */}
          {/* -------------------------------------------------------------- */}

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
                  generateInfo.status === 'Berhasil'
                    ? 'status-result success'
                    : 'status-result failed'
                }
              >

                {generateInfo.status || '-'}

              </div>

            </div>

          </div>


        </div>



        {/* ================================================================ */}
        {/* TOMBOL EXPORT */}
        {/* ================================================================ */}

        <div className="hasil-action-button">


          <Button

            variant="secondary"

            onClick={handleExportExcel}

          >

            <FileXls
              size={20}
            />

            Excel

          </Button>



          <Button

            variant="secondary"

            onClick={handleExportPdf}

          >

            <FilePdf
              size={20}
            />

            PDF

          </Button>


        </div>



        {/* ================================================================ */}
        {/* TABEL JADWAL */}
        {/* ================================================================ */}

        {

          loading ? (

            <div>

              <h3>
                Mengambil data jadwal...
              </h3>

            </div>

          ) : (

            <div
              style={{
                width: '100%',
                overflowX: 'auto'
              }}
            >

              <table className="table-preview-jadwal">

                <thead>

                  <tr>

                    <th>
                      No
                    </th>

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

                  {

                    jadwal.length > 0 ? (

                      jadwal.map(
                        (item, index) => (

                          <tr
                            key={item.id}
                          >


                            {/* NO */}

                            <td>

                              {index + 1}

                            </td>



                            {/* HARI */}

                            <td>

                              {item.hari || '-'}

                            </td>



                            {/* JAM */}

                            <td>

                              {item.jam_mulai || '-'}
                              {' - '}
                              {item.jam_selesai || '-'}

                            </td>



                            {/* MATA KULIAH */}

                            <td>

                              {item.mata_kuliah || '-'}

                            </td>



                            {/* KELAS */}

                            <td>

                              {item.kelas || '-'}

                            </td>



                            {/* PRODI */}

                            <td>

                              {item.prodi || '-'}

                              {
                                item.jenjang
                                  ? ` ${item.jenjang}`
                                  : ''
                              }

                            </td>



                            {/* RUANGAN */}

                            <td>

                              {item.ruangan || '-'}

                            </td>



                            {/* DOSEN */}

                            <td>

                              {

                                item.dosen &&
                                item.dosen.length > 0

                                  ? item.dosen.join(', ')

                                  : '-'

                              }

                            </td>


                          </tr>

                        )
                      )

                    ) : (

                      <tr>

                        <td
                          colSpan="8"
                          style={{
                            textAlign: 'center'
                          }}
                        >

                          Tidak ada data jadwal.

                        </td>

                      </tr>

                    )

                  }

                </tbody>

              </table>

            </div>

          )

        }


      </div>



      {/* ================================================================ */}
      {/* FOOTER BUTTON */}
      {/* ================================================================ */}

      <div className="footer-action-area">


        <Button

          variant="secondary"

          onClick={() =>
            onNavigate('riwayat-generate')
          }

        >

          Kembali

        </Button>



        <Button

          variant="primary"

          onClick={() =>
            onNavigate('dashboard')
          }

        >

          Selesai

        </Button>


      </div>


    </DashboardLayout>

  );

};


export default HasilGeneratePageBerhasil;