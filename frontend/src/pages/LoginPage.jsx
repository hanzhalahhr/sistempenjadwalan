// src/components/LoginPage.jsx
import React from 'react';
import LeftSection from '../components/LeftSection';
import LoginForm from '../components/LoginForm';
import '../styles/LoginPage.css'; 

const LoginPage = ({ onLoginSuccess }) => {
  return (
    <div className="container">
      <div className="overlay"></div>
      <div className="content">
        <LeftSection />
        <LoginForm onLoginSuccess={onLoginSuccess} />
      </div>
    </div>
  );
};

export default LoginPage;