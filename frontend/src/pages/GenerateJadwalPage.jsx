import React, {
  useState,
  useEffect
} from 'react';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  MicrosoftExcelLogo,
  CalendarCheck
} from '@phosphor-icons/react';

import axios from 'axios';

import '../styles/Dashboard.css';


const API =
  "http://127.0.0.1:8000/api";


const GenerateJadwalPage = ({
  onNavigate
}) => {


  /*
  |--------------------------------------------------------------------------
  | STATUS INPUT DATA
  |--------------------------------------------------------------------------
  */

  const [
    inputReady,
    setInputReady
  ] = useState(false);


  /*
  |--------------------------------------------------------------------------
  | STATUS KONFIGURASI SESSION
  |--------------------------------------------------------------------------
  |
  | INI BUKAN DARI DATABASE.
  |
  | Status hanya berdasarkan apakah user pada session aplikasi
  | ini sudah menyimpan konfigurasi.
  |
  */

  const [
    configReady,
    setConfigReady
  ] = useState(false);


  /*
  |--------------------------------------------------------------------------
  | LOADING
  |--------------------------------------------------------------------------
  */

  const [
    loadingStatus,
    setLoadingStatus
  ] = useState(true);


  /*
  |--------------------------------------------------------------------------
  | CEK STATUS
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    const cekStatus =
      async () => {

        try {

          setLoadingStatus(
            true
          );


          /*
          |--------------------------------------------------------------------------
          | 1. CEK INPUT DATA DARI DATABASE
          |--------------------------------------------------------------------------
          */

          const [
            prodiResponse,
            dosenResponse,
            mataKuliahResponse,
            kelasResponse,
            ruanganResponse
          ] = await Promise.all([

            axios.get(
              `${API}/prodi`
            ),

            axios.get(
              `${API}/dosen`
            ),

            axios.get(
              `${API}/mata-kuliah`
            ),

            axios.get(
              `${API}/kelasperkuliahan`
            ),

            axios.get(
              `${API}/ruangan`
            )

          ]);


          /*
          |--------------------------------------------------------------------------
          | 2. NORMALISASI DATA
          |--------------------------------------------------------------------------
          */

          const prodiData =
            Array.isArray(
              prodiResponse.data
            )
              ? prodiResponse.data
              : Array.isArray(
                  prodiResponse.data?.data
                )
                ? prodiResponse.data.data
                : [];


          const dosenData =
            Array.isArray(
              dosenResponse.data
            )
              ? dosenResponse.data
              : Array.isArray(
                  dosenResponse.data?.data
                )
                ? dosenResponse.data.data
                : [];


          const mataKuliahData =
            Array.isArray(
              mataKuliahResponse.data
            )
              ? mataKuliahResponse.data
              : Array.isArray(
                  mataKuliahResponse.data?.data
                )
                ? mataKuliahResponse.data.data
                : [];


          const kelasData =
            Array.isArray(
              kelasResponse.data
            )
              ? kelasResponse.data
              : Array.isArray(
                  kelasResponse.data?.data
                )
                ? kelasResponse.data.data
                : [];


          const ruanganData =
            Array.isArray(
              ruanganResponse.data
            )
              ? ruanganResponse.data
              : Array.isArray(
                  ruanganResponse.data?.data
                )
                ? ruanganResponse.data.data
                : [];


          /*
          |--------------------------------------------------------------------------
          | 3. TENTUKAN INPUT DATA
          |--------------------------------------------------------------------------
          */

          const inputSudahSiap =

            prodiData.length > 0 &&

            dosenData.length > 0 &&

            mataKuliahData.length > 0 &&

            kelasData.length > 0 &&

            ruanganData.length > 0;


          setInputReady(
            inputSudahSiap
          );


          /*
          |--------------------------------------------------------------------------
          | 4. CEK STATUS KONFIGURASI SESSION
          |--------------------------------------------------------------------------
          |
          | TIDAK ADA REQUEST KONFIGURASI DATABASE.
          |
          | Database boleh mempunyai konfigurasi lama.
          | Tetapi session baru tetap harus konfigurasi lagi.
          |
          */

          const sessionConfig =
            localStorage.getItem(
              "konfigurasi_jadwal_session"
            );


          const configSudahSiap =
            sessionConfig === "true";


          console.log(
            "STATUS KONFIGURASI SESSION:",
            sessionConfig
          );


          console.log(
            "HASIL CONFIG READY:",
            configSudahSiap
          );


          setConfigReady(
            configSudahSiap
          );

        }

        catch (error) {

          console.error(
            "Gagal mengecek status generate:",
            error
          );


          console.error(
            "Response error:",
            error.response?.data
          );


          setInputReady(
            false
          );


          setConfigReady(
            false
          );

        }

        finally {

          setLoadingStatus(
            false
          );

        }

      };


    cekStatus();

  }, []);


  /*
  |--------------------------------------------------------------------------
  | STATUS AKHIR
  |--------------------------------------------------------------------------
  */

  const allReady =
    inputReady &&
    configReady;


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="generate"

      pageTitle="Generate Jadwal"

      pageSubtitle="Buat jadwal perkuliahan"

    >

      <div
        style={{
          textAlign: 'left',
          paddingLeft: '20px',
          marginTop: '30px'
        }}
      >


        {/* ==========================================================
            LANGKAH 1
        ========================================================== */}

        <h3
          style={{
            color: '#010101',
            fontSize: '20px',
            fontWeight: '700',
            marginBottom: '10px'
          }}
        >

          Langkah 1 - Pilih Sumber Data

        </h3>


        <p
          style={{
            color: '#777',
            fontSize: '14px',
            marginBottom: '30px',
            lineHeight: '22px'
          }}
        >

          Pilih salah satu metode untuk mengisi data
          yang akan digunakan dalam proses generate jadwal.

        </p>


        <div className="generate-source-cards">


          {/* ======================================================
              INPUT MANUAL
          ====================================================== */}

          <div
            className="data-card"
            style={{
              flexDirection: 'column',
              gap: '20px',
              cursor: 'pointer'
            }}
            onClick={() =>
              onNavigate(
                'input-manual'
              )
            }
          >

            <div
              className="icon-wrapper"
              style={{
                backgroundColor: "#fff4e6",
                color: "#f39c12",
                width: "80px",
                height: "80px"
              }}
            >

              <CalendarCheck
                size={36}
                weight="fill"
              />

            </div>


            <div
              style={{
                textAlign: 'center'
              }}
            >

              <h3
                style={{
                  color: '#010101',
                  marginBottom: '15px',
                  fontSize: '16px'
                }}
              >

                Input Data Manual

              </h3>


              <Button
                variant="primary"
              >

                Input Data Manual

              </Button>

            </div>

          </div>


          {/* ======================================================
              IMPORT EXCEL
          ====================================================== */}

          <div
            className="data-card"
            style={{
              flexDirection: 'column',
              gap: '20px',
              cursor: 'pointer'
            }}
            onClick={() =>
              onNavigate(
                'import-excel'
              )
            }
          >

            <div
              className="icon-wrapper"
              style={{
                backgroundColor: '#e6f6ec',
                color: '#2ecc71',
                width: '80px',
                height: '80px'
              }}
            >

              <MicrosoftExcelLogo
                size={36}
                weight="bold"
              />

            </div>


            <div
              style={{
                textAlign: 'center'
              }}
            >

              <h3
                style={{
                  color: '#010101',
                  marginBottom: '15px',
                  fontSize: '16px'
                }}
              >

                Impor dari Excel

              </h3>


              <Button
                style={{
                  background: '#2ecc71',
                  color: 'white'
                }}
              >

                Impor Excel

              </Button>

            </div>

          </div>


          {/* ======================================================
              KONFIGURASI JADWAL
          ====================================================== */}

          <div
            className="data-card"
            style={{
              flexDirection: "column",
              gap: "20px",
              cursor: "pointer"
            }}
            onClick={() =>
              onNavigate(
                "konfigurasi-jadwal"
              )
            }
          >

            <div
              className="icon-wrapper"
              style={{
                backgroundColor: "#e8f0ff",
                color: "#3498db",
                width: "80px",
                height: "80px"
              }}
            >

              <CalendarCheck
                size={36}
                weight="fill"
              />

            </div>


            <div
              style={{
                textAlign: "center"
              }}
            >

              <h3
                style={{
                  color: '#010101',
                  marginBottom: '15px',
                  fontSize: '16px',
                  fontWeight: '700'
                }}
              >

                Konfigurasi Jadwal

              </h3>


              <Button
                style={{
                  background: '#3498db',
                  color: 'white'
                }}
              >

                Konfigurasi Jadwal

              </Button>

            </div>

          </div>

        </div>


        {/* ==========================================================
            LANGKAH 2
        ========================================================== */}

        <h3
          style={{
            color: "#010101",
            fontSize: "20px",
            fontWeight: "700",
            marginTop: "50px",
            marginBottom: "10px"
          }}
        >

          Langkah 2 - Generate Jadwal

        </h3>


        <p
          style={{
            color: "#777",
            fontSize: "14px",
            marginBottom: "25px"
          }}
        >

          Pastikan seluruh data dan konfigurasi sudah lengkap
          sebelum melakukan proses generate.

        </p>


        {/* ==========================================================
            STATUS CARD
        ========================================================== */}

        <div className="config-card">

          <table className="status-table">

            <tbody>


              {/* ====================================================
                  INPUT DATA
              ==================================================== */}

              <tr>

                <td className="status-label">
                  Input Data
                </td>

                <td>
                  :
                </td>

                <td
                  style={{
                    color:
                      loadingStatus
                        ? "#777"
                        : inputReady
                          ? "#22c55e"
                          : "#ef4444",

                    fontWeight: "700"
                  }}
                >

                  {loadingStatus

                    ? "Memeriksa..."

                    : inputReady

                      ? "Siap"

                      : "Belum Siap"

                  }

                </td>

              </tr>


              {/* ====================================================
                  KONFIGURASI
              ==================================================== */}

              <tr>

                <td className="status-label">
                  Konfigurasi Jadwal
                </td>

                <td>
                  :
                </td>

                <td
                  style={{
                    color:
                      loadingStatus
                        ? "#777"
                        : configReady
                          ? "#22c55e"
                          : "#ef4444",

                    fontWeight: "700"
                  }}
                >

                  {loadingStatus

                    ? "Memeriksa..."

                    : configReady

                      ? "Sudah Disimpan"

                      : "Belum Disimpan"

                  }

                </td>

              </tr>


              {/* ====================================================
                  STATUS AKHIR
              ==================================================== */}

              <tr>

                <td className="status-label">
                  Status
                </td>

                <td>
                  :
                </td>

                <td
                  style={{
                    color:
                      loadingStatus
                        ? "#777"
                        : allReady
                          ? "#22c55e"
                          : "#ef4444",

                    fontWeight: "700"
                  }}
                >

                  {loadingStatus

                    ? "Memeriksa..."

                    : allReady

                      ? "Siap Generate"

                      : "Belum Siap Generate"

                  }

                </td>

              </tr>

            </tbody>

          </table>

        </div>


        {/* ==========================================================
            BUTTON GENERATE
        ========================================================== */}

        <div
          style={{
            display: "flex",
            justifyContent: "flex-end",
            marginTop: "20px"
          }}
        >

          <Button

            disabled={
              loadingStatus ||
              !allReady
            }

            onClick={() => {

              if (
                loadingStatus ||
                !allReady
              ) {

                return;

              }


              onNavigate(
                "proses-generate"
              );

            }}

            style={{
              opacity:
                loadingStatus ||
                !allReady
                  ? 0.5
                  : 1,

              cursor:
                loadingStatus ||
                !allReady
                  ? "not-allowed"
                  : "pointer"
            }}

          >

            Generate Jadwal

          </Button>

        </div>

      </div>

    </DashboardLayout>

  );

};


export default GenerateJadwalPage;