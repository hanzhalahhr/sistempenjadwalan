import React, { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import {
  faChalkboard,
  faChalkboardUser,
  faBookOpen,
  faBuildingColumns,
  faCircleCheck
} from '@fortawesome/free-solid-svg-icons';

import axios from 'axios';


const DashboardContent = () => {

  /*
  |--------------------------------------------------------------------------
  | STATE DASHBOARD
  |--------------------------------------------------------------------------
  */

  const [dashboardData, setDashboardData] = useState(null);

  const [loading, setLoading] = useState(true);

  const [error, setError] = useState('');


  /*
  |--------------------------------------------------------------------------
  | AMBIL DATA DASHBOARD
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    const fetchDashboard = async () => {

      try {

        setLoading(true);

        setError('');


        /*
        |--------------------------------------------------------------------------
        | AMBIL TOKEN LOGIN
        |--------------------------------------------------------------------------
        */

        const token = localStorage.getItem('token');


        /*
        |--------------------------------------------------------------------------
        | CEK TOKEN
        |--------------------------------------------------------------------------
        */

        if (!token) {

          console.error('Token tidak ditemukan di localStorage.');

          setError(
            'Sesi login tidak ditemukan. Silakan login kembali.'
          );

          return;

        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST DASHBOARD
        |--------------------------------------------------------------------------
        |
        | Token dikirim melalui:
        |
        | Authorization: Bearer TOKEN
        |
        */

        const response = await axios.get(
          'http://127.0.0.1:8000/api/dashboard',
          {
            headers: {

              Authorization: `Bearer ${token}`,

              Accept: 'application/json'

            }

          }
        );


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA DASHBOARD
        |--------------------------------------------------------------------------
        */

        setDashboardData(response.data);

      } catch (err) {

        console.error(
          'Gagal mengambil data dashboard:',
          err
        );


        /*
        |--------------------------------------------------------------------------
        | JIKA TOKEN TIDAK VALID / EXPIRED
        |--------------------------------------------------------------------------
        */

        if (err.response?.status === 401) {

          console.error(
            'Token tidak valid atau sudah expired.'
          );

          setError(
            'Sesi login sudah tidak valid. Silakan login kembali.'
          );

        } else {

          setError(
            'Gagal mengambil data dashboard dari server.'
          );

        }

      } finally {

        setLoading(false);

      }

    };


    fetchDashboard();

  }, []);


  /*
  |--------------------------------------------------------------------------
  | LOADING
  |--------------------------------------------------------------------------
  */

  if (loading) {

    return (

      <div className="dashboard-home">

        <div className="dashboard-home-header">

          <div>

            <h1>
              Dashboard
            </h1>

            <p>
              Ringkasan data sistem penjadwalan perkuliahan
            </p>

          </div>

        </div>


        <div
          style={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            minHeight: '300px'
          }}
        >

          <div className="spinner"></div>

        </div>

      </div>

    );

  }


  /*
  |--------------------------------------------------------------------------
  | ERROR
  |--------------------------------------------------------------------------
  */

  if (error) {

    return (

      <div className="dashboard-home">

        <div className="dashboard-home-header">

          <div>

            <h1>
              Dashboard
            </h1>

            <p>
              Ringkasan data sistem penjadwalan perkuliahan
            </p>

          </div>

        </div>


        <div className="jadwal-detail-error">

          {error}

        </div>

      </div>

    );

  }


  /*
  |--------------------------------------------------------------------------
  | DATA STATISTIK
  |--------------------------------------------------------------------------
  */

  const statistik =
    dashboardData?.statistik || {};


  const dataCards = [

    {
      id: 1,
      title: 'Total Kelas',
      count: statistik.total_kelas ?? 0,
      desc: 'Kelas aktif',
      icon: faChalkboard,
      theme: 'green'
    },

    {
      id: 2,
      title: 'Total Dosen',
      count: statistik.total_dosen ?? 0,
      desc: 'Dosen',
      icon: faChalkboardUser,
      theme: 'blue'
    },

    {
      id: 3,
      title: 'Mata Kuliah',
      count: statistik.total_mata_kuliah ?? 0,
      desc: 'Kurikulum aktif',
      icon: faBookOpen,
      theme: 'red'
    },

    {
      id: 4,
      title: 'Ruangan',
      count: statistik.total_ruangan ?? 0,
      desc: 'Fasilitas tersedia',
      icon: faBuildingColumns,
      theme: 'yellow'
    }

  ];


  /*
  |--------------------------------------------------------------------------
  | DISTRIBUSI SEMESTER
  |--------------------------------------------------------------------------
  */

  const semesterData =
    dashboardData?.distribusi_semester || [];


  /*
  |--------------------------------------------------------------------------
  | PROGRAM STUDI
  |--------------------------------------------------------------------------
  */

  const programStudi =
    dashboardData?.program_studi || [];


  /*
  |--------------------------------------------------------------------------
  | TOTAL MATA KULIAH
  |--------------------------------------------------------------------------
  */

  const totalMataKuliah =
    semesterData.reduce(
      (total, item) =>
        total + Number(item.total || 0),
      0
    );


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <div className="dashboard-home">


      {/* =====================================================
          HEADER DASHBOARD
      ===================================================== */}

      <div className="dashboard-home-header">

        <div>

          <h1>
            Dashboard
          </h1>

          <p>
            Ringkasan data sistem penjadwalan perkuliahan
          </p>

        </div>

      </div>


      {/* =====================================================
          STATISTIC CARDS
      ===================================================== */}

      <div className="cards-grid">

        {dataCards.map((card) => (

          <div
            key={card.id}
            className="data-card"
          >

            <div
              className={`icon-wrapper ${card.theme}`}
            >

              <FontAwesomeIcon
                icon={card.icon}
                className="card-icon"
              />

            </div>


            <div className="card-info">

              <h3>
                {card.title}
              </h3>

              <h2>
                {card.count}
              </h2>

              <p>
                {card.desc}
              </p>

            </div>

          </div>

        ))}

      </div>


      {/* =====================================================
          DASHBOARD LOWER SECTION
      ===================================================== */}

      <div className="dashboard-lower-section">


        {/* ===================================================
            DISTRIBUSI MATA KULIAH
        =================================================== */}

        <div className="semester-distribution-card">

          <div className="dashboard-section-header">

            <div>

              <h2>
                Distribusi Mata Kuliah per Semester
              </h2>

            </div>

          </div>


          <div className="semester-list">

            {semesterData.map((item) => (

              <div
                key={item.semester}
                className="semester-item"
              >

                <div className="semester-item-header">

                  <span className="semester-name">

                    {item.semester}

                  </span>


                  <span className="semester-total">

                    {item.total} Mata Kuliah

                  </span>

                </div>


                <div className="semester-progress">

                  <div
                    className={`semester-progress-bar ${item.type}`}
                    style={{
                      width:
                        totalMataKuliah > 0
                          ? `${Math.min(
                              (item.total /
                                totalMataKuliah) *
                                100,
                              100
                            )}%`
                          : '100%'
                    }}
                  />

                </div>

              </div>

            ))}

          </div>


          <div className="semester-total-section">

            <div className="semester-total-label">

              Total Mata Kuliah

            </div>


            <div className="semester-total-number">

              {totalMataKuliah}

            </div>

          </div>

        </div>


        {/* ===================================================
            VALIDASI PROGRAM STUDI
        =================================================== */}

        <div className="program-validation-card">

          <div className="dashboard-section-header">

            <div>

              <h2>
                Status Validasi Program Studi
              </h2>

            </div>

          </div>


          <div className="program-validation-list">

            {programStudi.length > 0 ? (

              programStudi.map((prodi) => (

                <div
                  key={prodi.id}
                  className="program-validation-item"
                >

                  <div className="program-validation-info">

                    <span className="program-validation-name">

                      {prodi.nama}

                    </span>


                    <span className="program-validation-level">

                      {prodi.jenjang}

                    </span>

                  </div>


                  <div className="program-validation-status">

                    <div className="validation-icon">

                      <FontAwesomeIcon
                        icon={faCircleCheck}
                      />

                    </div>


                    <div className="validation-text">

                      {prodi.status}

                    </div>

                  </div>

                </div>

              ))

            ) : (

              <div className="jadwal-detail-empty">

                Belum ada program studi.

              </div>

            )}

          </div>

        </div>

      </div>

    </div>

  );

};


export default DashboardContent;