import { useState, useEffect } from "react";

import LoginPage from "./pages/LoginPage";
import DashboardPage from "./pages/DashboardPage";
import JadwalPage from "./pages/JadwalPage";
import DetailJadwalPage from "./pages/DetailJadwalPage";

import GenerateJadwalPage from "./pages/GenerateJadwalPage";
import ImportExcelPage from "./pages/ImportExcelPage";
import ProsesGeneratePage from "./pages/ProsesGeneratePage";

import HasilGeneratePageBerhasil from "./pages/HasilGeneratePageBerhasil";
import HasilGeneratePageGagal from "./pages/HasilGeneratePageGagal";

import InputManualPage from "./pages/InputManualPage";
import KonfigurasiJadwalPage from "./pages/KonfigurasiJadwalPage";
import KelasPerkuliahanPage from "./pages/KelasPerkuliahanPage";

import RiwayatGenerate from "./pages/RiwayatGenerate";
import ProfilePage from "./pages/ProfilePage";


function App() {

  /*
  |--------------------------------------------------------------------------
  | CURRENT PAGE
  |--------------------------------------------------------------------------
  */

  const [currentPage, setCurrentPage] = useState(
    localStorage.getItem("currentPage") || "login"
  );


  /*
  |--------------------------------------------------------------------------
  | PRODI YANG DIPILIH
  |--------------------------------------------------------------------------
  */

  const [selectedProdi, setSelectedProdi] = useState(
    localStorage.getItem("selectedProdi")
      ? Number(localStorage.getItem("selectedProdi"))
      : null
  );


  /*
  |--------------------------------------------------------------------------
  | STATUS LOGIN
  |--------------------------------------------------------------------------
  */

  const [isLoggedIn, setIsLoggedIn] = useState(
    !!localStorage.getItem("token")
  );


  /*
  |--------------------------------------------------------------------------
  | RESET KONFIGURASI SETIAP APLIKASI DIBUKA
  |--------------------------------------------------------------------------
  |
  | INI BAGIAN PALING PENTING.
  |
  | Kalau browser ditutup lalu website dibuka lagi:
  |
  | - token masih ada
  | - user masih dianggap login
  | - TAPI konfigurasi session HARUS kembali belum siap
  |
  | Jadi kita TIDAK menghapus token.
  | Kita hanya reset status konfigurasi session.
  |
  */

  useEffect(() => {

    const token =
      localStorage.getItem("token");


    if (token) {

      /*
      |----------------------------------------------------------------------
      | User masih login
      |----------------------------------------------------------------------
      */

      setIsLoggedIn(true);


      /*
      |----------------------------------------------------------------------
      | Reset konfigurasi untuk session aplikasi baru
      |----------------------------------------------------------------------
      */

      localStorage.setItem(
        "konfigurasi_jadwal_session",
        "false"
      );


      /*
      |----------------------------------------------------------------------
      | Setelah website dibuka kembali,
      | masuk ke dashboard.
      |----------------------------------------------------------------------
      */

      localStorage.setItem(
        "currentPage",
        "dashboard"
      );


      setCurrentPage(
        "dashboard"
      );

    }

    else {

      /*
      |----------------------------------------------------------------------
      | Tidak ada token
      |----------------------------------------------------------------------
      */

      setIsLoggedIn(false);


      localStorage.setItem(
        "konfigurasi_jadwal_session",
        "false"
      );


      localStorage.setItem(
        "currentPage",
        "login"
      );


      setCurrentPage(
        "login"
      );

    }

  }, []);


  /*
  |--------------------------------------------------------------------------
  | PROSES LOGIN BERHASIL
  |--------------------------------------------------------------------------
  */

  const handleLoginSuccess = () => {

    /*
    |------------------------------------------------------------------------
    | Setelah login baru:
    | konfigurasi WAJIB dilakukan lagi.
    |------------------------------------------------------------------------
    */

    localStorage.setItem(
      "konfigurasi_jadwal_session",
      "false"
    );


    localStorage.setItem(
      "currentPage",
      "dashboard"
    );


    setIsLoggedIn(true);

    setCurrentPage(
      "dashboard"
    );

  };


  /*
  |--------------------------------------------------------------------------
  | NAVIGATE
  |--------------------------------------------------------------------------
  */

  const navigate = (
    page,
    data = null
  ) => {

    console.log(
      "NAVIGATE KE:",
      page
    );


    /*
    |--------------------------------------------------------------------------
    | DETAIL JADWAL
    |--------------------------------------------------------------------------
    */

    if (
      page === "detail-jadwal"
    ) {

      console.log(
        "PRODI YANG DIPILIH:",
        data
      );


      setSelectedProdi(
        data
      );


      if (
        data !== null &&
        data !== undefined
      ) {

        localStorage.setItem(
          "selectedProdi",
          data
        );

      }

    }


    /*
    |--------------------------------------------------------------------------
    | HASIL GENERATE
    |--------------------------------------------------------------------------
    */

    if (
      page === "hasil-generate"
    ) {

      console.log(
        "GENERATE ID SAAT PINDAH:",
        localStorage.getItem(
          "generate_id"
        )
      );

    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    if (
      page === "login"
    ) {

      setIsLoggedIn(false);

    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN CURRENT PAGE
    |--------------------------------------------------------------------------
    */

    localStorage.setItem(
      "currentPage",
      page
    );


    setCurrentPage(
      page
    );

  };


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <>

      {/* ================================================================
          LOGIN
      ================================================================ */}

      {currentPage === "login" && (

        <LoginPage
          onLoginSuccess={
            handleLoginSuccess
          }
        />

      )}


      {/* ================================================================
          DASHBOARD
      ================================================================ */}

      {currentPage === "dashboard" &&
        isLoggedIn && (

          <DashboardPage
            onNavigate={navigate}
          />

      )}


      {currentPage === "dashboard" &&
        !isLoggedIn && (

          <LoginPage
            onLoginSuccess={
              handleLoginSuccess
            }
          />

      )}


      {/* ================================================================
          JADWAL
      ================================================================ */}

      {currentPage === "jadwal" &&
        isLoggedIn && (

          <JadwalPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          DETAIL JADWAL
      ================================================================ */}

      {currentPage === "detail-jadwal" &&
        isLoggedIn && (

          <DetailJadwalPage
            onNavigate={navigate}
            prodiId={selectedProdi}
          />

      )}


      {/* ================================================================
          GENERATE JADWAL
      ================================================================ */}

      {currentPage === "generate" &&
        isLoggedIn && (

          <GenerateJadwalPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          KONFIGURASI JADWAL
      ================================================================ */}

      {currentPage === "konfigurasi-jadwal" &&
        isLoggedIn && (

          <KonfigurasiJadwalPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          IMPORT EXCEL
      ================================================================ */}

      {currentPage === "import-excel" &&
        isLoggedIn && (

          <ImportExcelPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          PROSES GENERATE
      ================================================================ */}

      {currentPage === "proses-generate" &&
        isLoggedIn && (

          <ProsesGeneratePage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          HASIL GENERATE BERHASIL
      ================================================================ */}

      {currentPage === "hasil-generate" &&
        isLoggedIn && (

          <HasilGeneratePageBerhasil
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          HASIL GENERATE GAGAL
      ================================================================ */}

      {currentPage === "hasil-generate-gagal" &&
        isLoggedIn && (

          <HasilGeneratePageGagal
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          INPUT MANUAL
      ================================================================ */}

      {currentPage === "input-manual" &&
        isLoggedIn && (

          <InputManualPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          KELAS PERKULIAHAN
      ================================================================ */}

      {currentPage === "kelas-perkuliahan" &&
        isLoggedIn && (

          <KelasPerkuliahanPage
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          RIWAYAT GENERATE
      ================================================================ */}

      {currentPage === "riwayat-generate" &&
        isLoggedIn && (

          <RiwayatGenerate
            onNavigate={navigate}
          />

      )}


      {/* ================================================================
          PROFILE
      ================================================================ */}

      {currentPage === "profile" &&
        isLoggedIn && (

          <ProfilePage
            onNavigate={navigate}
          />

      )}

    </>

  );

}


export default App;