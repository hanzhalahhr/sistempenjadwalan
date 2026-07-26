import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { XCircle, ArrowCounterClockwise } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const HasilGeneratePageGagal = ({ onNavigate }) => {
  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="generate"
      pageTitle="Generate Jadwal - Hasil Jadwal"
    >
      <div className="hasil-generate-card">
        <div className="error-status-container">
          <div className="icon-error-wrapper">
            <XCircle size={80} weight="fill" />
          </div>
          <h3 className="status-title-error">Jadwal Belum Berhasil Dibuat</h3>
          <p className="status-subtitle">Terdapat bentrok dalam hasil generate</p>
        </div>
      </div>

      <div className="footer-action-area">
        <Button variant="primary" icon={ArrowCounterClockwise} onClick={() => onNavigate('proses-generate')}>
          Generate Ulang
        </Button>
      </div>
    </DashboardLayout>
  );
};

export default HasilGeneratePageGagal;