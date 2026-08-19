import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
  faHouse,
  faCalendar,
  faSquarePlus,
  faClock,
  faUser
} from '@fortawesome/free-solid-svg-icons';

const Sidebar = ({ onNavigate, currentPage }) => {

  const generatePages = [
    'generate',
    'input-manual',
    'input-manual-dosen',
    'input-manual-ruang',
    'tambah-data-ruang',
    'tambah-data-mk',
    'tambah-data-dosen',
    'proses-generate',
    'hasil-generate',
    'hasil-generate-gagal'
  ];

  return (
    <div className="sidebar">

      <div className="menu-group">

        {/* Menu Dashboard */}
        <div
          className={`menu-item ${
            currentPage === 'dashboard' ? 'active' : ''
          }`}
          onClick={() => onNavigate('dashboard')}
        >
          <FontAwesomeIcon
            icon={faHouse}
            className="sidebar-icon"
          />

          <span>Dashboard</span>
        </div>


        {/* Menu Jadwal */}
        <div
          className={`menu-item ${
            currentPage === 'jadwal' ? 'active' : ''
          }`}
          onClick={() => onNavigate('jadwal')}
        >
          <FontAwesomeIcon
            icon={faCalendar}
            className="sidebar-icon"
          />

          <span>Jadwal</span>
        </div>


        {/* Menu Generate Jadwal */}
        <div
          className={`menu-item ${
            generatePages.includes(currentPage)
              ? 'active'
              : ''
          }`}
          onClick={() => onNavigate('generate')}
        >
          <FontAwesomeIcon
            icon={faSquarePlus}
            className="sidebar-icon"
          />

          <span>Generate Jadwal</span>
        </div>


        {/* Menu Riwayat */}
        <div
          className={`menu-item ${
            currentPage === 'riwayat-generate'
              ? 'active'
              : ''
          }`}
          onClick={() => onNavigate('riwayat-generate')}
        >
          <FontAwesomeIcon
            icon={faClock}
            className="sidebar-icon"
          />

          <span>Riwayat</span>
        </div>

      </div>


      <div className="sidebar-footer">

        {/* Menu Profile */}
        <div
          className={`menu-item profile-item ${
            currentPage === 'profile'
              ? 'active'
              : ''
          }`}
          onClick={() => onNavigate('profile')}
        >

          <div className="profile-circle">

            <FontAwesomeIcon
              icon={faUser}
              className="sidebar-icon-profile"
            />

          </div>

          <span>Profile</span>

        </div>

      </div>

    </div>
  );
};

export default Sidebar;