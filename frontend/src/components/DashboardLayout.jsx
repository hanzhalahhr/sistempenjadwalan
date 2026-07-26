import React from 'react';
import Header from './Header';
import Sidebar from './Sidebar';
import './DashboardLayout.css';

const DashboardLayout = ({ children, onNavigate, currentPage, pageTitle, pageSubtitle, headerAction }) => {
  return (
    <div className="dashboard-container">
      <Header />
      <div className="dashboard-main">
        <Sidebar onNavigate={onNavigate} currentPage={currentPage} />
        <div className="dashboard-content">
          
          {(pageTitle || headerAction) && (
            <div className="page-header-container">
              <div className="page-title-group">
                {pageTitle && <h2 className="page-title">{pageTitle}</h2>}
                {pageSubtitle && <p className="page-subtitle">{pageSubtitle}</p>}
              </div>
              
              {headerAction && (
                <div className="page-header-action">
                  {headerAction}
                </div>
              )}
            </div>
          )}
          
          {children}
        </div>
      </div>
    </div>
  );
};

export default DashboardLayout;