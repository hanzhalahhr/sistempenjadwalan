import React, { useState } from 'react';

import axios from 'axios';

import {
  User,
  LockKey
} from '@phosphor-icons/react';

import Button from './Button';
import InputField from './InputField';

import gedungFik from '../assets/GedungFIK.jpg';

import '../styles/LoginForm.css';


const LoginForm = ({
  onLoginSuccess
}) => {

  const [username, setUsername] =
    useState('');

  const [password, setPassword] =
    useState('');

  const [loading, setLoading] =
    useState(false);


  /*
  |--------------------------------------------------------------------------
  | LOGIN
  |--------------------------------------------------------------------------
  */

  const handleLogin = async (e) => {

    e.preventDefault();


    /*
    |--------------------------------------------------------------------------
    | CEK INPUT
    |--------------------------------------------------------------------------
    */

    if (
      !username ||
      !password
    ) {

      alert(
        "Silahkan masukkan username dan password"
      );

      return;

    }


    try {

      setLoading(true);


      /*
      |--------------------------------------------------------------------------
      | LOGIN KE BACKEND
      |--------------------------------------------------------------------------
      */

      const response =
        await axios.post(
          'http://127.0.0.1:8000/api/login',
          {
            username: username,
            password: password
          }
        );


      /*
      |--------------------------------------------------------------------------
      | LOGIN BERHASIL
      |--------------------------------------------------------------------------
      */

      if (
        response.data.token
      ) {

        /*
        |----------------------------------------------------------------------
        | SIMPAN TOKEN
        |----------------------------------------------------------------------
        */

        localStorage.setItem(
          "token",
          response.data.token
        );


        /*
        |----------------------------------------------------------------------
        | SIMPAN DATA MAHASISWA
        |----------------------------------------------------------------------
        */

        localStorage.setItem(
          "user",
          JSON.stringify(
            response.data.mahasiswa
          )
        );


        /*
        |--------------------------------------------------------------------------
        | RESET KONFIGURASI SESSION
        |--------------------------------------------------------------------------
        |
        | LOGIN BARU = BELUM KONFIGURASI.
        |
        | Walaupun database mempunyai konfigurasi lama,
        | user tetap harus melakukan konfigurasi lagi.
        |
        */

        localStorage.setItem(
          "konfigurasi_jadwal_session",
          "false"
        );


        /*
        |--------------------------------------------------------------------------
        | RESET CURRENT PAGE
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
          "currentPage",
          "dashboard"
        );


        /*
        |--------------------------------------------------------------------------
        | PINDAH KE DASHBOARD
        |--------------------------------------------------------------------------
        */

        if (
          onLoginSuccess
        ) {

          onLoginSuccess();

        }

      }

      else {

        alert(
          response.data.message ||
          "Login gagal"
        );

      }

    }

    catch (error) {

      console.error(
        "Login error:",
        error
      );


      if (
        error.response
      ) {

        alert(
          error.response.data.message ||
          "Username atau password salah"
        );

      }

      else {

        alert(
          "Terjadi error koneksi ke server"
        );

      }

    }

    finally {

      setLoading(false);

    }

  };


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <div className="right">

      <div className="top-image">

        <img
          src={gedungFik}
          alt="Fakultas Ilmu Komputer"
        />

      </div>


      <form
        className="login-box"
        onSubmit={handleLogin}
      >

        <h2>
          Masuk Akun
        </h2>


        <InputField

          type="text"

          placeholder="Masukkan Nama Pengguna"

          value={username}

          onChange={(e) =>
            setUsername(
              e.target.value
            )
          }

          leftIcon={
            <User
              size={22}
              weight="bold"
            />
          }

          className="login-input-field"

        />


        <InputField

          type="password"

          placeholder="Masukan Kata Sandi"

          value={password}

          onChange={(e) =>
            setPassword(
              e.target.value
            )
          }

          leftIcon={
            <LockKey
              size={22}
              weight="bold"
            />
          }

          className="login-input-field"

        />


        <a
          href="#forgot"
          className="forgot-password"
        >
          Lupa kata sandi?
        </a>


        <Button
          variant="primary"
          type="submit"
          style={{
            width: '100%',
            height: '52px',
            borderRadius: '14px',
            fontSize: '18px'
          }}
        >

          {loading
            ? "Memproses..."
            : "Masuk"
          }

        </Button>

      </form>

    </div>

  );

};


export default LoginForm;