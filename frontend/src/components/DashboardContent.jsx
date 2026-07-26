import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGraduationCap, faUsers, faBookOpen, faBuildingColumns } from '@fortawesome/free-solid-svg-icons';

const DashboardContent = () => {
  const dataCards = [
    { id: 1, title: 'Total Prodi', count: '4', desc: 'Program Studi', icon: faGraduationCap, theme: 'green' },
    { id: 2, title: 'Total Dosen', count: '000', desc: 'Dosen', icon: faUsers, theme: 'blue' },
    { id: 3, title: 'Total Mata Kuliah', count: '000', desc: 'Mata Kuliah', icon: faBookOpen, theme: 'purple' },
    { id: 4, title: 'Total Ruangan', count: '00', desc: 'Ruangan', icon: faBuildingColumns, theme: 'orange' },
  ];

  return (
    <div className="cards-grid">
      {dataCards.map((card) => (
        <div key={card.id} className="data-card">
          <div className={`icon-wrapper ${card.theme}`}>
            <FontAwesomeIcon icon={card.icon} className="card-icon" />
          </div>
          <div className="card-info">
            <h3>{card.title}</h3>
            <h2>{card.count}</h2>
            <p>{card.desc}</p>
          </div>
        </div>
      ))}
    </div>
  );
};

export default DashboardContent;