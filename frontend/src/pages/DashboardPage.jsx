import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import DashboardContent from '../components/DashboardContent';

const DashboardPage = ({ onNavigate }) => {
  return (
    <DashboardLayout 
      onNavigate={onNavigate} 
      currentPage="dashboard"
    >
      <DashboardContent />
    </DashboardLayout>
  );
};

export default DashboardPage;