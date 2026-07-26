import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { CheckCircle, DownloadSimple } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const HasilGeneratePageBerhasil = ({ onNavigate }) => {
  
  const handleDownload = (prodi) => {
    const fileName = `Jadwal_${prodi.replace(/\s+/g, '_')}.xlsx`;
    const fileUrl = `/files/${fileName}`; 
    
    const link = document.createElement('a');
    link.href = fileUrl;
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
    link.parentNode.removeChild(link);
  };

  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="generate"
      pageTitle="Generate Jadwal - Hasil Jadwal"
    >
      <div className="hasil-generate-card">
        <div className="success-status-container">
          <div className="icon-success-wrapper">
            <CheckCircle size={80} weight="fill" />
          </div>
          <h3 className="status-title-success">Jadwal Berhasil Dibuat</h3>
          <p className="status-subtitle">Tidak ada bentrok yang ditemukan</p>
        </div>


        <div className="download-options-container">
          <Button variant="download-prodi" icon={DownloadSimple} onClick={() => handleDownload("Seluruh Jadwal")}>Seluruh Jadwal</Button>
          <Button variant="download-prodi" icon={DownloadSimple} onClick={() => handleDownload("S1 Informatika")}>S1 Informatika</Button>
          <Button variant="download-prodi" icon={DownloadSimple} onClick={() => handleDownload("S1 Sistem Informasi")}>S1 Sistem Informasi</Button>
          <Button variant="download-prodi" icon={DownloadSimple} onClick={() => handleDownload("S1 Sains Data")}>S1 Sains Data</Button>
          <Button variant="download-prodi" icon={DownloadSimple} onClick={() => handleDownload("D3 Sistem Informasi")}>D3 Sistem Informasi</Button>
        </div>
      </div>

      <div className="footer-action-area">
        <Button variant="primary" onClick={() => onNavigate('dashboard')}>
          Simpan Hasil & Selesai
        </Button>
      </div>
    </DashboardLayout>
  );
};

export default HasilGeneratePageBerhasil;