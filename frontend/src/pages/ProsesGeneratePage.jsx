import React, { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { XCircle } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const ProsesGeneratePage = ({ onNavigate }) => {
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    if (progress >= 100) {
      const isSuccess = Math.random() > 0.3;
      const timer = setTimeout(() => {
        onNavigate(isSuccess ? 'hasil-generate' : 'hasil-generate-gagal');
      }, 500);
      return () => clearTimeout(timer);
    }
    const interval = setInterval(() => setProgress(p => p < 100 ? p + 1 : 100), 50);
    return () => clearInterval(interval);
  }, [progress, onNavigate]);

  return (
    <DashboardLayout onNavigate={onNavigate} currentPage="generate" pageTitle="Generate Jadwal - Proses" pageSubtitle="Proses pembuatan jadwal otomatis">
      <div style={{ border: '1px solid #f3a45a', borderRadius: '20px', padding: '40px', marginTop: '20px', textAlign: 'center' }}>
        <div className="spinner"></div>
        <h3 style={{ color: '#f3a45a', margin: '20px 0' }}>Proses Generate Sedang Berjalan</h3>
        <div style={{ width: '100%', height: '25px', backgroundColor: '#eee', borderRadius: '15px', overflow: 'hidden' }}>
          <div style={{ width: `${progress}%`, height: '100%', backgroundColor: '#f3a45a', transition: 'width 0.3s' }}></div>
        </div>
        <p style={{ marginTop: '10px', fontWeight: 'bold' }}>{progress}%</p>
      </div>

      <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: '40px' }}>
        <Button variant="danger" icon={XCircle} onClick={() => onNavigate('generate')}>Batalkan</Button>
      </div>
    </DashboardLayout>
  );
};

export default ProsesGeneratePage;