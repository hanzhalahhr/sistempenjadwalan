import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { Plus, MicrosoftExcelLogo } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const GenerateJadwalPage = ({ onNavigate }) => {
  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="generate"
      pageTitle="Generate Jadwal"
      pageSubtitle="Buat jadwal perkuliahan secara otomatis"
    >
      <div style={{ textAlign: 'left', paddingLeft: '20px', marginTop: '30px' }}>
        <h3 style={{ color: '#010101', fontSize: '18px', marginBottom: '8px' }}>Pilih Sumber Data</h3>
        <p style={{ color: '#777', fontSize: '14px', marginBottom: '30px' }}>
          Pilih cara untuk mengisi data yang akan digunakan dalam proses penjadwalan
        </p>
        
        <div className="cards-grid">
          {/* Card Input Manual */}
          <div 
            className="data-card" 
            style={{ flexDirection: 'column', gap: '20px', cursor: 'pointer' }} 
            onClick={() => onNavigate('input-manual')}
          >
            <div className="icon-wrapper" style={{ backgroundColor: '#fef4eb', color: '#e67e22', width: '80px', height: '80px' }}>
              <Plus size={36} weight="bold" />
            </div>
            <div style={{ textAlign: 'center' }}>
              <h3 style={{ color: '#010101', marginBottom: '15px', fontSize: '16px'}}>Input Data Manual</h3>
              <Button variant="primary" style={{ pointerEvents: 'none' }}>
                Input Data Manual
              </Button>
            </div>
          </div>

          {/* Card Impor Excel */}
          <div 
            className="data-card" 
            style={{ flexDirection: 'column', gap: '20px', cursor: 'pointer' }} 
            onClick={() => onNavigate('import-excel')}
          >
            <div className="icon-wrapper" style={{ backgroundColor: '#e6f6ec', color: '#2ecc71', width: '80px', height: '80px' }}>
              <MicrosoftExcelLogo size={36} weight="bold" />
            </div>
            <div style={{ textAlign: 'center' }}>
              <h3 style={{ color: '#010101', marginBottom: '15px', fontSize: '16px' }}>Impor dari Excel</h3>
              <Button style={{ background: '#2ecc71', color: 'white', pointerEvents: 'none' }}>
                Impor Excel
              </Button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default GenerateJadwalPage;