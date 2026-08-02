import { useState } from "react";
import LoginPage from "./pages/LoginPage";
import DashboardPage from "./pages/DashboardPage";
import JadwalPage from "./pages/JadwalPage";
import DetailJadwalPage from './pages/DetailJadwalPage';
import GenerateJadwalPage from "./pages/GenerateJadwalPage";
import ImportExcelPage from "./pages/ImportExcelPage";
import ProsesGeneratePage from "./pages/ProsesGeneratePage"; 
import HasilGeneratePageBerhasil from "./pages/HasilGeneratePageBerhasil";
import HasilGeneratePageGagal from "./pages/HasilGeneratePageGagal";
import InputManualPage from "./pages/InputManualPage";
import KonfigurasiJadwalPage from "./pages/KonfigurasiJadwalPage";
import KelasPerkuliahanPage from "./pages/KelasPerkuliahanPage";
// import TambahDataMK from "./pages/TambahDataMK";
// import InputManualDosen from "./pages/InputManualDosen";
// import TambahDataDosen from "./pages/TambahDataDosen";
// import InputManualRuang from "./pages/InputManualRuang";
// import TambahDataRuang from "./pages/TambahDataRuang";
import RiwayatGenerate from "./pages/RiwayatGenerate";
import ProfilePage from "./pages/ProfilePage";

function App() {
  const [currentPage, setCurrentPage] = useState(
    localStorage.getItem("currentPage") || "login"
    );
  
  const [selectedProdi,setSelectedProdi] = useState(null);
  const isLoggedIn = !!localStorage.getItem("token");
  
  const navigate = (page, data = null) => {

    console.log("NAVIGATE KE:", page);

    if(page === "hasil-generate"){
        console.log(
            "GENERATE ID SAAT PINDAH:",
            localStorage.getItem("generate_id")
        );
    }

    localStorage.setItem("currentPage", page);

    setCurrentPage(page);

 };




  return (
    <>
      {currentPage === "login" && (
        <LoginPage onLoginSuccess={() => navigate("dashboard")} />
      )}
      {currentPage === "dashboard" && isLoggedIn && (
        <DashboardPage onNavigate={navigate} />
      )}

      {currentPage === "dashboard" && !isLoggedIn && (
        <LoginPage onLoginSuccess={() => navigate("dashboard")} />
      )}
      {currentPage === "jadwal" && (
        <JadwalPage onNavigate={navigate} />
      )}
      {currentPage === "detail-jadwal" && (
        <DetailJadwalPage onNavigate={navigate}
          prodiId={selectedProdi}
        />
      )}
      {currentPage === "generate" && (
        <GenerateJadwalPage onNavigate={navigate} />
      )}
      {currentPage === "konfigurasi-jadwal" && (
        <KonfigurasiJadwalPage onNavigate={navigate} />
      )}
      {currentPage === "import-excel" && (
        <ImportExcelPage onNavigate={navigate} />
      )}
      {currentPage === "proses-generate" && (
        <ProsesGeneratePage onNavigate={navigate} />
      )}
      {currentPage === "hasil-generate" && (
        <HasilGeneratePageBerhasil onNavigate={navigate}/>
      )}
      {currentPage === "hasil-generate-gagal" && (
        <HasilGeneratePageGagal onNavigate={navigate}/>
      )}
      {currentPage === "input-manual" && (
        <InputManualPage onNavigate={navigate} />
      )}
      {currentPage === "kelas-perkuliahan" && (
        <KelasPerkuliahanPage onNavigate={navigate} />
      )}
      

      

      {/* {currentPage === "input-manual" && <InputManualPage onNavigate={setCurrentPage} currentPage="input-manual" />}
      {/* {currentPage === "input-manual-dosen" && <InputManualDosen onNavigate={setCurrentPage} />}
      {currentPage === "input-manual-ruang" && <InputManualRuang onNavigate={setCurrentPage} />} */}
      {/* {currentPage === "tambah-data-mk" && (
        <TambahDataMK onNavigate={setCurrentPage} />
      )} */}
      {/* {currentPage === "tambah-data-dosen" && (
        <TambahDataDosen onNavigate={setCurrentPage} />
      )}
      {currentPage === "tambah-data-ruang" && (
        <TambahDataRuang onNavigate={setCurrentPage} />
      )} */}
      {currentPage === "riwayat-generate" && (
        <RiwayatGenerate onNavigate={navigate} />
      )}
      {currentPage === "profile" && (
        <ProfilePage onNavigate={navigate} />
      )}
    </>
  );
}

export default App