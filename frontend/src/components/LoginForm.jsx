import React, { useState } from 'react';
import axios from 'axios';
import { User, LockKey } from '@phosphor-icons/react'; // Ikon Phosphor
import Button from './Button';
import InputField from './InputField';
import gedungFik from '../assets/GedungFIK.jpg'; 
import '../styles/LoginForm.css'; 

  const LoginForm = ({ onLoginSuccess }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async (e) => {
  e.preventDefault();

  // cek input kosong
  if (!username || !password) {
    alert("Silahkan masukkan username dan password");
    return;
  }

  try {
    setLoading(true);

  const response = await axios.post(
  'http://127.0.0.1:8000/api/login',
  {
    username: username,
    password: password
  }
);

  if (response.data.token) {

  // simpan token login
  localStorage.setItem(
    "token",
    response.data.token
  );

  // simpan data mahasiswa
  localStorage.setItem(
    "user",
    JSON.stringify(response.data.mahasiswa)
  );

  if (onLoginSuccess) {
    onLoginSuccess();
  }

} else {

  alert(response.data.message);

}

} catch (error) {

  console.error(error);

  if (error.response) {

    alert(error.response.data.message);

  } else {

    alert("Terjadi error koneksi ke server");

    }

  } finally {

    setLoading(false);

  }
};



  return (
    <div className="right">
      <div className="top-image">
        <img src={gedungFik} alt="Fakultas Ilmu Komputer" />
      </div>

      <form className="login-box" onSubmit={handleLogin}>
        <h2>Masuk Akun</h2>
        
        <InputField 
          type="text"
          placeholder="Masukkan Nama Pengguna"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          leftIcon={<User size={22} weight="bold" />}
          className="login-input-field"
        />

        <InputField 
          type="password" 
          placeholder="Masukan Kata Sandi"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          leftIcon={<LockKey size={22} weight="bold" />}
          className="login-input-field"
        />

        <a href="#forgot" className="forgot-password">Lupa kata sandi?</a>

        <Button variant="primary" type="submit" style={{ width: '100%', height: '52px', borderRadius: '14px', fontSize: '18px' }}>
          {loading ? "Memproses..." : "Masuk"}
        </Button>
      </form>
    </div>
  );
};

export default LoginForm;
