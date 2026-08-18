import React, { useEffect, useState } from 'react';

import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Button from '../components/Button';
import Badge from '../components/Badge';

import {
  Eye
} from '@phosphor-icons/react';

import axios from 'axios';

import '../styles/Dashboard.css';
import '../styles/JadwalPage.css';


const JadwalPage = ({ onNavigate }) => {

  const [dataJadwal, setDataJadwal] = useState([]);
  const [generate, setGenerate] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');


  /*
  |--------------------------------------------------------------------------
  | AMBIL DATA JADWAL
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    setLoading(true);
    setError('');

    axios
      .get('http://127.0.0.1:8000/api/jadwal')

      .then((response) => {

        console.log(
          'DATA JADWAL:',
          response.data
        );

        setGenerate(
          response.data.generate || null
        );

        setDataJadwal(
          response.data.data || []
        );

      })

      .catch((error) => {

        console.error(
          'Gagal mengambil jadwal:',
          error
        );

        setError(
          'Gagal mengambil data jadwal.'
        );

      })

      .finally(() => {

        setLoading(false);

      });

  }, []);


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

    return date.toLocaleString(
      'id-ID',
      {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }
    );

  };


  /*
  |--------------------------------------------------------------------------
  | LIHAT JADWAL
  |--------------------------------------------------------------------------
  */

  const handleLihatJadwal = (prodiId) => {

    console.log(
      'Lihat jadwal prodi:',
      prodiId
    );

    onNavigate(
      'detail-jadwal',
      prodiId
    );

  };


  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="jadwal"

      pageTitle="Jadwal Perkuliahan"

      pageSubtitle="Semua Program Studi - Jadwal Fix"

    >


      {/* ============================================================
          GENERATE TERAKHIR
          ============================================================ */}

      {generate && (

        <div className="jadwal-generate-card">

          <div className="jadwal-generate-content">


            {/* ========================================================
                HEADER
                ======================================================== */}

            <div className="jadwal-generate-heading">

              <h3>
                Generate Terakhir
              </h3>

              <p>
                Informasi jadwal yang terakhir berhasil di-generate
              </p>

            </div>


            {/* ========================================================
                INFORMASI
                ======================================================== */}

            <div className="jadwal-generate-details">


              {/* ======================================================
                  KODE
                  ====================================================== */}

              <div className="jadwal-info-block">

                <span>
                  Kode Generate
                </span>

                <strong>
                  {generate.kode_generate}
                </strong>

              </div>


              {/* ======================================================
                  TANGGAL
                  ====================================================== */}

              <div className="jadwal-info-block">

                <span>
                  Tanggal Generate
                </span>

                <strong>
                  {formatTanggal(
                    generate.tanggal
                  )}
                </strong>

                <Badge
                  type="status"
                  status={generate.status}
                >
                  {generate.status}
                </Badge>

              </div>


            </div>

          </div>

        </div>

      )}


      {/* ============================================================
          ERROR
          ============================================================ */}

      {error && (

        <div className="jadwal-error-message">

          {error}

        </div>

      )}


      {/* ============================================================
          DAFTAR JADWAL
          ============================================================ */}

      <div className="jadwal-list-header">

        <h3>
          Daftar Jadwal
        </h3>

        <p>
          Pilih program studi untuk melihat jadwal perkuliahan.
        </p>

      </div>


      {/* ============================================================
          LOADING
          ============================================================ */}

      {loading && (

        <div className="jadwal-loading">

          <div className="spinner"></div>

          <p>
            Memuat jadwal...
          </p>

        </div>

      )}


      {/* ============================================================
          DATA KOSONG
          ============================================================ */}

      {!loading &&
       !error &&
       dataJadwal.length === 0 && (

        <div className="jadwal-empty">

          <p>
            Belum ada data jadwal.
          </p>

        </div>

      )}


      {/* ============================================================
          TABLE MENU JADWAL
          ============================================================ */}

      {!loading &&
       dataJadwal.length > 0 && (

        <div className="jadwal-menu-table">

          <Table

            headers={[
              'No',
              'Prodi',
              'Jumlah Jadwal',
              'Status',
              'Aksi'
            ]}

          >

            {dataJadwal.map(
              (item, index) => (

                <tr
                  key={item.id}
                >


                  {/* ==================================================
                      NO
                      ================================================== */}

                  <td className="jadwal-menu-no">

                    {index + 1}

                  </td>


                  {/* ==================================================
                      PRODI
                      ================================================== */}

                  <td className="jadwal-menu-prodi">

                    {item.prodi}

                  </td>


                  {/* ==================================================
                      JUMLAH JADWAL
                      ================================================== */}

                  <td className="jadwal-jumlah-cell">

                    <span className="jadwal-jumlah-value">

                      {item.jumlah_jadwal}

                    </span>

                  </td>


                  {/* ==================================================
                      STATUS
                      ================================================== */}

                  <td className="jadwal-menu-status">

                    <Badge

                      type="status"

                      status={item.status}

                    >

                      {item.status}

                    </Badge>

                  </td>


                  {/* ==================================================
                      AKSI
                      ================================================== */}

                  <td className="jadwal-menu-action">

                    <div className="jadwal-action-buttons">

                      <Button

                        variant="table-icon eye"

                        icon={Eye}

                        title="Lihat Jadwal"

                        onClick={() =>
                          handleLihatJadwal(
                            item.id
                          )
                        }

                      />

                    </div>

                  </td>


                </tr>

              )
            )}

          </Table>

        </div>

      )}


    </DashboardLayout>

  );

};


export default JadwalPage;