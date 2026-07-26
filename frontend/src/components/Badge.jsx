import React from 'react';
import './Badge.css';

const Badge = ({ children, type = 'prodi', status = '' }) => {
  const getStatusClass = () => {
    if (!status) return '';
    if (status.toLowerCase().includes('gagal')) return 'gagal';
    if (status.toLowerCase().includes('berhasil')) return 'berhasil';
    return '';
  };

  const getBuildingClass = () => {
    if (children.includes('Dewi Sartika')) return 'dewi-sartika';
    if (children.includes('Ki Hajar')) return 'ki-hajar';
    return '';
  };

  return (
    <span className={`badge-base badge-${type} ${getStatusClass()} ${getBuildingClass()}`}>
      {children}
    </span>
  );
};

export default Badge;