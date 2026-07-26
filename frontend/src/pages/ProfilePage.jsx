import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import InputField from '../components/InputField';
import { LockKey, SignOut } from '@phosphor-icons/react';
import '../styles/Profile.css';

const ProfilePage = ({ onNavigate }) => {
  const handleLogout = () => {

    localStorage.removeItem("token");
    localStorage.removeItem("user");
    localStorage.removeItem("currentPage");

    onNavigate("login");
  };
  
  const renderHeaderAction = (
    <Button 
      variant="danger" 
      icon={SignOut} 
      onClick={handleLogout}
    >
      Logout
    </Button>
  );

  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="profile"
      pageTitle="Profile"
      pageSubtitle="Kelola akun Anda"
      headerAction={renderHeaderAction}
    >
      <div className="password-card">
        <div className="lock-icon" style={{ color: '#f3a45a' }}>
           <LockKey size={48} weight="fill" />
        </div>
        
        <h3>Ganti Password</h3>
        <p>Ubah password Anda secara berkala untuk menjaga keamanan akun</p>

        <InputField 
          label="Password Saat Ini"
          type="password"
          placeholder="Masukkan password saat ini"
          className="profile-input-wrapper"
        />

        <InputField 
          label="Password Baru"
          type="password"
          placeholder="Masukkan password baru"
          className="profile-input-wrapper"
        />

        <InputField 
          label="Konfirmasi Password Baru"
          type="password"
          placeholder="Masukkan ulang password baru"
          className="profile-input-wrapper"
        />

        <div className="action-buttons">
          <Button variant="secondary" onClick={() => console.log('Batal ditekan')}>
            Batal
          </Button>
          <Button variant="primary" onClick={() => console.log('Simpan ditekan')}>
            Simpan
          </Button>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default ProfilePage;