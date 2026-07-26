import React from 'react';
import logoUpnvj from '../assets/Logo_UPNVJ.png';
import './Header.css';

const Header = () => {
  return (
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
  );
};

export default Header;