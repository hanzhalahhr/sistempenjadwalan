import React, { useEffect, useState } from 'react';

import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Badge from '../components/Badge';
import Button from '../components/Button';

import {
  ArrowLeft,
  CalendarBlank,
  Clock,
  MapPin,
  User,
  DownloadSimple
} from '@phosphor-icons/react';

import axios from 'axios';

import '../styles/Dashboard.css';
import '../styles/JadwalPage.css';


const DetailJadwalPage = ({
  onNavigate,
  prodiId
}) => {

  const [jadwal, setJadwal] = useState([]);
  const [generate, setGenerate] = useState(null);
  const [prodi, setProdi] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');


  /*
  |--------------------------------------------------------------------------
  | AMBIL DETAIL JADWAL
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    if (
      prodiId === undefined ||
      prodiId === null
    ) {

      setError('Program studi tidak dipilih.');
      setLoading(false);

      return;

    }


    setLoading(true);
    setError('');


    console.log(
      'Ambil detail jadwal prodi:',
      prodiId
    );


    axios.get(
      `http://127.0.0.1:8000/api/jadwal/prodi/${prodiId}`
    )

    .then((response) => {

      console.log(
        'DETAIL JADWAL:',
        response.data
      );


      setGenerate(
        response.data.generate || null
      );


      setProdi(
        response.data.prodi || ''
      );


      setJadwal(
        response.data.jadwal || []
      );

    })

    .catch((error) => {

      console.error(
        'Gagal mengambil detail jadwal:',
        error
      );


      setError(
        error.response?.data?.message ||
        'Gagal mengambil detail jadwal.'
      );


      setJadwal([]);

    })

    .finally(() => {

      setLoading(false);

    });

  }, [prodiId]);


  /*
  |--------------------------------------------------------------------------
  | FORMAT JAM
  |--------------------------------------------------------------------------
  */

  const formatJam = (
    jamMulai,
    jamSelesai
  ) => {

    if (
      !jamMulai ||
      !jamSelesai
    ) {

      return '-';

    }


    return `${jamMulai.substring(0, 5)} - ${jamSelesai.substring(0, 5)}`;

  };


  /*
  |--------------------------------------------------------------------------
  | FORMAT TANGGAL
  |--------------------------------------------------------------------------
  */

  const formatTanggal = (tanggal) => {

    if (!tanggal) {

      return '-';

    }


    const date = new Date(tanggal);


    if (isNaN(date.getTime())) {

      return tanggal;

    }


    return date.toLocaleDateString(
      'id-ID',
      {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
      }
    );

  };


  /*
  |--------------------------------------------------------------------------
  | KEMBALI
  |--------------------------------------------------------------------------
  */

  const handleKembali = () => {

    onNavigate('jadwal');

  };


  /*
  |--------------------------------------------------------------------------
  | UNDUH EXCEL
  |--------------------------------------------------------------------------
  */

  const handleUnduhExcel = () => {

    if (!generate?.id) {

      console.error(
        'Generate ID tidak ditemukan.'
      );

      return;

    }


    window.open(
      `http://127.0.0.1:8000/api/jadwal/export/${generate.id}`,
      '_blank'
    );

  };


  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="jadwal"

      pageTitle="Jadwal Perkuliahan"

      pageSubtitle={
        prodi || 'Detail Jadwal'
      }

    >


      {/* ================================================================
          CARD INFORMASI DETAIL
          ================================================================ */}

      <div className="jadwal-detail-card">


        {/* ============================================================
            TOMBOL KEMBALI
            ============================================================ */}

        <div className="jadwal-detail-top">

          <Button

            variant="secondary"

            icon={ArrowLeft}

            onClick={handleKembali}

          >

            Kembali

          </Button>

        </div>



        {/* ============================================================
            JUDUL + STATUS
            ============================================================ */}

        <div className="jadwal-detail-title">

          <div className="jadwal-detail-title-content">

            <div className="jadwal-detail-title-main">

              <h2>
                {prodi || '-'}
              </h2>


              <span className="jadwal-detail-divider">
                |
              </span>


              {generate && (

                <Badge

                  type="status"

                  status={generate.status}

                >

                  {generate.status}

                </Badge>

              )}

            </div>

          </div>

        </div>



        {/* ================================================================
            INFORMASI GENERATE
            ================================================================ */}

        {generate && (

          <div className="jadwal-detail-info">


            {/* ========================================================
                KODE GENERATE
                ======================================================== */}

            <div className="jadwal-detail-info-item">

              <div className="jadwal-detail-info-icon">

                <CalendarBlank size={20} />

              </div>


              <div className="jadwal-detail-info-content">

                <span>
                  Kode Generate
                </span>


                <strong>
                  {generate.kode_generate || '-'}
                </strong>

              </div>

            </div>



            {/* ========================================================
                TOTAL JADWAL
                ======================================================== */}

            <div className="jadwal-detail-info-item">

              <div className="jadwal-detail-info-icon">

                <Clock size={20} />

              </div>


              <div className="jadwal-detail-info-content">

                <span>
                  Total Jadwal
                </span>


                <strong>
                  {jadwal.length} Jadwal
                </strong>

              </div>

            </div>



            {/* ========================================================
                TANGGAL GENERATE
                ======================================================== */}

            <div className="jadwal-detail-info-item">

              <div className="jadwal-detail-info-icon">

                <CalendarBlank size={20} />

              </div>


              <div className="jadwal-detail-info-content">

                <span>
                  Tanggal Generate
                </span>


                <strong>
                  {formatTanggal(
                    generate.tanggal
                  )}
                </strong>

              </div>

            </div>


          </div>

        )}

      </div>



      {/* ================================================================
          ERROR
          ================================================================ */}

      {error && (

        <div className="jadwal-detail-error">

          {error}

        </div>

      )}



      {/* ================================================================
          LOADING
          ================================================================ */}

      {loading && (

        <div className="jadwal-detail-loading">

          <div className="spinner"></div>

          <p>
            Memuat jadwal...
          </p>

        </div>

      )}



      {/* ================================================================
          DATA KOSONG
          ================================================================ */}

      {!loading &&
       !error &&
       jadwal.length === 0 && (

        <div className="jadwal-detail-empty">

          <p>
            Belum ada jadwal untuk program studi ini.
          </p>

        </div>

      )}



      {/* ================================================================
          TABEL JADWAL
          ================================================================ */}

      {!loading &&
       !error &&
       jadwal.length > 0 && (

        <div className="jadwal-detail-table-card">


          {/* ==========================================================
              HEADER DATA JADWAL
              ========================================================== */}

          <div className="jadwal-detail-table-header">


            <div>

              <span className="jadwal-table-label">
                DATA JADWAL
              </span>


              <h3>
                Daftar Jadwal Perkuliahan
              </h3>


              <p>
                Menampilkan seluruh jadwal untuk {prodi}.
              </p>

            </div>



            <div className="jadwal-table-header-actions">


              <div className="jadwal-total-badge">

                {jadwal.length} Jadwal

              </div>



              <Button

                variant="secondary"

                icon={DownloadSimple}

                onClick={handleUnduhExcel}

              >

                Unduh

              </Button>


            </div>

          </div>



          {/* ==========================================================
              TABLE PREVIEW
              ========================================================== */}

          <div className="jadwal-table-container">

            <div className="jadwal-preview-table">

              <Table

                headers={[
                  'No',
                  'Hari',
                  'Jam',
                  'Mata Kuliah',
                  'Kelas',
                  'Ruangan',
                  'Dosen'
                ]}

              >

                {jadwal.map(
                  (item, index) => (

                    <tr
                      key={item.id}
                      className="jadwal-table-row"
                    >


                      {/* ==================================================
                          NO
                          ================================================== */}

                      <td className="jadwal-col-no">

                        <span className="jadwal-no">

                          {index + 1}

                        </span>

                      </td>



                      {/* ==================================================
                          HARI
                          ================================================== */}

                      <td className="jadwal-col-hari">

                        <div className="jadwal-cell">

                          <CalendarBlank size={16} />

                          <span className="jadwal-hari">

                            {item.hari || '-'}

                          </span>

                        </div>

                      </td>



                      {/* ==================================================
                          JAM
                          ================================================== */}

                      <td className="jadwal-col-jam">

                        <div className="jadwal-cell">

                          <Clock size={16} />

                          <span className="jadwal-jam">

                            {formatJam(
                              item.jam_mulai,
                              item.jam_selesai
                            )}

                          </span>

                        </div>

                      </td>



                      {/* ==================================================
                          MATA KULIAH
                          ================================================== */}

                      <td className="jadwal-col-mata-kuliah">

                        <div className="jadwal-mata-kuliah">

                          <strong>

                            {item.mata_kuliah || '-'}

                          </strong>

                        </div>

                      </td>



                      {/* ==================================================
                          KELAS
                          ================================================== */}

                      <td className="jadwal-col-kelas">

                        <span className="jadwal-kelas-badge">

                          {item.kelas || '-'}

                        </span>

                      </td>



                      {/* ==================================================
                          RUANGAN
                          ================================================== */}

                      <td className="jadwal-col-ruangan">

                        <div className="jadwal-cell">

                          <MapPin size={16} />

                          <span className="jadwal-ruangan">

                            {item.ruangan || '-'}

                          </span>

                        </div>

                      </td>



                      {/* ==================================================
                          DOSEN
                          ================================================== */}

                      <td className="jadwal-col-dosen">

                        <div className="jadwal-cell jadwal-dosen">

                          <User size={16} />

                          <span>

                            {item.dosen?.length

                              ? item.dosen.join(', ')

                              : '-'

                            }

                          </span>

                        </div>

                      </td>


                    </tr>

                  )
                )}

              </Table>

            </div>

          </div>


        </div>

      )}


    </DashboardLayout>

  );

};


export default DetailJadwalPage;