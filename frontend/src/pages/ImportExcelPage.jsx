import React, { useRef, useState } from 'react';
import Sidebar from '../components/Sidebar';
import logoUpnvj from '../assets/Logo_UPNVJ.png';
import { UploadSimple, MicrosoftExcelLogo, DownloadSimple, CheckCircle, ArrowRight } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const ImportExcelPage = ({ onNavigate }) => {
  const fileInputRef = useRef(null);
  const [selectedFile, setSelectedFile] = useState(null);
  const [showModal, setShowModal] = useState(false);

  const handleUploadClick = () => {
    fileInputRef.current.click();
  };

  const handleFileChange = (event) => {
    if (event.target.files && event.target.files[0]) {
      setSelectedFile(event.target.files[0].name);
    }
  };

  const buttonStyle = {
    padding: '14px 0',
    borderRadius: '10px',
    cursor: 'pointer',
    fontWeight: '600',
    fontSize: '14px',
    border: 'none',
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: '260px'
  };

  return (
      <div className="dashboard-container">
        <header className="dashboard-header">
          <div className="header-left">
            <img src={logoUpnvj} alt="Logo UPNVJ" className="univ-logo" />
            <div className="header-text">
              <div className="title-main">Sistem Penjadwalan Otomatis Perkuliahan</div>
              <div className="title-sub">Fakultas Ilmu Komputer</div>
              <div className="title-univ">Universitas Pembangunan Nasional Veteran Jakarta</div>
            </div>
          </div>
          <div className="header-right">
            <select className="semester-select">
              <option>Semester Ganjil 2025/2026</option>
              <option>Semester Genap 2025/2026</option>
            </select>
          </div>
        </header>

      <div className="dashboard-main">
        <Sidebar onNavigate={onNavigate} currentPage="generate" />
        
        <div className="dashboard-content">
          <div className="page-title-group">
            <h2 className="page-title">Generate Jadwal - {selectedFile ? 'Konfirmasi Data' : 'Impor Excel'}</h2>
            <p className="page-subtitle">
              {selectedFile ? 'Periksa kembali data yang telah di impor sebelum melanjutkan ke proses generate.' : 'Impor data menggunakan file Excel'}
            </p>
          </div>

          {selectedFile ? (
            <div style={{ border: '1px solid #f3a45a', borderRadius: '20px', padding: '40px', marginTop: '20px', textAlign: 'center', backgroundColor: '#fffbf7' }}>
              <CheckCircle size={40} weight="fill" style={{ color: '#28a745', marginBottom: '15px' }} />
              <h3 style={{ color: '#28a745', fontSize: '20px', marginBottom: '10px' }}>File berhasil diimpor!</h3>
              <p style={{ color: '#28a745', fontSize: '16px', fontWeight: 'bold' }}>{selectedFile}</p>
            </div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '25px', marginTop: '20px' }}>
              <div style={{ border: '2px dashed #f3a45a', borderRadius: '20px', padding: '40px', backgroundColor: '#fffbf7', textAlign: 'center', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <UploadSimple size={36} weight="bold" style={{ color: '#f3a45a', marginBottom: '15px' }} />
                <h3 style={{ marginBottom: '10px', color: '#333', fontSize: '18px', fontWeight: 'bold' }}>Unggah File Excel</h3>
                <p style={{ color: '#666', fontSize: '14px', marginBottom: '25px' }}>Drag & drop file Excel di sini atau klik untuk memilih file<br/>Format yang didukung .xlsx, .xls</p>
                <input type="file" ref={fileInputRef} style={{ display: 'none' }} accept=".xlsx, .xls" onChange={handleFileChange} />
                <button onClick={handleUploadClick} style={{ ...buttonStyle, background: '#f3a45a', color: 'white' }}>Pilih File Excel</button>
              </div>

              <div style={{ border: '1px solid #e0e0e0', borderRadius: '20px', padding: '40px', backgroundColor: '#fffcf9', textAlign: 'center', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <MicrosoftExcelLogo size={36} weight="bold" style={{ color: '#f3a45a', marginBottom: '15px' }} />
                <h3 style={{ marginBottom: '10px', color: '#333', fontSize: '18px', fontWeight: 'bold' }}>Template Excel</h3>
                <p style={{ color: '#666', fontSize: '14px', marginBottom: '25px', maxWidth: '300px' }}>Gunakan template berikut agar proses impor lebih mudah dan sesuai format.</p>
                <a href="/template_jadwal.xlsx" download style={{ textDecoration: 'none' }}>
                  <button style={{ ...buttonStyle, border: '1px solid #f3a45a', color: '#f3a45a', background: 'transparent' }}>
                    <DownloadSimple size={20} style={{ marginRight: '8px' }} /> Download Template
                  </button>
                </a>
              </div>
            </div>
          )}

          <div style={{ marginTop: '40px', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <button 
              onClick={() => onNavigate('generate')} 
              style={{ width: '200px', padding: '14px 0', borderRadius: '10px', border: '1px solid #ccc', background: 'white', cursor: 'pointer', fontWeight: '600', color: '#333' }}
            >
              Kembali
            </button>
            
            <button 
              onClick={() => selectedFile ? setShowModal(true) : onNavigate('proses-generate')}
              style={{ width: '250px', padding: '14px 0', borderRadius: '10px', border: 'none', background: '#f3a45a', color: 'white', cursor: 'pointer', fontWeight: '600', display: 'flex', justifyContent: 'center', alignItems: 'center' }}
            >
              {selectedFile ? 'Data sudah benar, next' : 'Selanjutnya'} <ArrowRight size={20} weight="bold" style={{ marginLeft: '8px' }} />
            </button>
          </div>
        </div>
      </div>

      {showModal && (
        <div style={{ position: 'fixed', top: 0, left: 0, width: '100%', height: '100%', backgroundColor: 'rgba(0,0,0,0.5)', display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 1000 }}>
          <div style={{ backgroundColor: 'white', padding: '40px', borderRadius: '20px', textAlign: 'center', width: '400px' }}>
            <p style={{ fontSize: '18px', marginBottom: '30px', color: '#101010' }}>Apakah kamu yakin ingin melanjutkan ke generate jadwal dengan file tersebut?</p>
            <div style={{ display: 'flex', justifyContent: 'center', gap: '20px' }}>
              <button onClick={() => setShowModal(false)} style={{ padding: '10px 30px', borderRadius: '10px', border: '1px solid #ccc', background: 'white', color: '#5d5a5abd', cursor: 'pointer' }}>Kembali</button>
              <button onClick={() => onNavigate('proses-generate')} style={{ padding: '10px 30px', borderRadius: '10px', border: 'none', background: '#f3a45a', color: 'white', cursor: 'pointer' }}>Ya, lanjut</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ImportExcelPage;