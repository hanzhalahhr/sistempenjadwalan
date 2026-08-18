import React, { useEffect, useState } from 'react';

import axios from 'axios';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import InputField from '../components/InputField';

import {
    LockKey,
    SignOut,
    UserCircle,
    ShieldCheck,
    GraduationCap,
    X
} from '@phosphor-icons/react';

import '../styles/Profile.css';


const ProfilePage = ({ onNavigate }) => {

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    const [showPasswordModal, setShowPasswordModal] =
        useState(false);

    const [user, setUser] =
        useState(null);

    const [loading, setLoading] =
        useState(true);

    const [error, setError] =
        useState('');


    /*
    |--------------------------------------------------------------------------
    | STATE PASSWORD
    |--------------------------------------------------------------------------
    */

    const [currentPassword, setCurrentPassword] =
        useState('');

    const [newPassword, setNewPassword] =
        useState('');

    const [confirmPassword, setConfirmPassword] =
        useState('');


    /*
    |--------------------------------------------------------------------------
    | STATE PASSWORD PROCESS
    |--------------------------------------------------------------------------
    */

    const [passwordLoading, setPasswordLoading] =
        useState(false);


    /*
    |--------------------------------------------------------------------------
    | AMBIL PROFILE DARI BACKEND
    |--------------------------------------------------------------------------
    */

    useEffect(() => {

        const fetchProfile = async () => {

            try {

                setLoading(true);

                setError('');


                /*
                |--------------------------------------------------------------------------
                | AMBIL TOKEN
                |--------------------------------------------------------------------------
                */

                const token =
                    localStorage.getItem('token');


                console.log(
                    'TOKEN PROFILE:',
                    token
                );


                /*
                |--------------------------------------------------------------------------
                | CEK TOKEN
                |--------------------------------------------------------------------------
                */

                if (!token) {

                    console.error(
                        'Token tidak ditemukan.'
                    );

                    setError(
                        'Token login tidak ditemukan. Silakan login kembali.'
                    );

                    setLoading(false);

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | REQUEST PROFILE
                |--------------------------------------------------------------------------
                */

                const response =
                    await axios.get(
                        'http://127.0.0.1:8000/api/profile',
                        {
                            headers: {
                                Authorization:
                                    `Bearer ${token}`,

                                Accept:
                                    'application/json'
                            }
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | DEBUG RESPONSE
                |--------------------------------------------------------------------------
                */

                console.log(
                    'PROFILE RESPONSE:',
                    response.data
                );


                /*
                |--------------------------------------------------------------------------
                | SIMPAN DATA USER
                |--------------------------------------------------------------------------
                */

                if (
                    response.data?.user
                ) {

                    setUser(
                        response.data.user
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE LOCAL STORAGE
                    |--------------------------------------------------------------------------
                    */

                    localStorage.setItem(
                        'user',
                        JSON.stringify(
                            response.data.user
                        )
                    );

                }

            }

            catch (error) {

                console.error(
                    'PROFILE ERROR:',
                    error
                );

                console.error(
                    'PROFILE ERROR RESPONSE:',
                    error.response?.data
                );


                /*
                |--------------------------------------------------------------------------
                | TOKEN INVALID
                |--------------------------------------------------------------------------
                */

                if (
                    error.response?.status === 401
                ) {

                    setError(
                        'Sesi login sudah tidak valid. Silakan login kembali.'
                    );

                }

                else {

                    setError(
                        'Gagal mengambil data profile dari server.'
                    );

                }

            }

            finally {

                setLoading(false);

            }

        };


        fetchProfile();

    }, []);


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    const handleLogout = () => {

        localStorage.removeItem(
            'token'
        );

        localStorage.removeItem(
            'user'
        );


        localStorage.setItem(
            'konfigurasi_jadwal_session',
            'false'
        );


        localStorage.setItem(
            'currentPage',
            'login'
        );


        onNavigate(
            'login'
        );

    };


    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL PASSWORD
    |--------------------------------------------------------------------------
    */

    const openPasswordModal = () => {

        /*
        |--------------------------------------------------------------------------
        | RESET FORM
        |--------------------------------------------------------------------------
        */

        setCurrentPassword('');

        setNewPassword('');

        setConfirmPassword('');


        setShowPasswordModal(
            true
        );

    };


    /*
    |--------------------------------------------------------------------------
    | TUTUP MODAL PASSWORD
    |--------------------------------------------------------------------------
    */

    const closePasswordModal = () => {

        /*
        |--------------------------------------------------------------------------
        | JANGAN TUTUP SAAT SEDANG PROSES
        |--------------------------------------------------------------------------
        */

        if (passwordLoading) {

            return;

        }


        setShowPasswordModal(
            false
        );


        /*
        |--------------------------------------------------------------------------
        | RESET FORM
        |--------------------------------------------------------------------------
        */

        setCurrentPassword('');

        setNewPassword('');

        setConfirmPassword('');

    };


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PASSWORD
    |--------------------------------------------------------------------------
    */

    const handleSavePassword = async () => {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FRONTEND
        |--------------------------------------------------------------------------
        */

        if (!currentPassword) {

            alert(
                'Password saat ini wajib diisi.'
            );

            return;

        }


        if (!newPassword) {

            alert(
                'Password baru wajib diisi.'
            );

            return;

        }


        if (newPassword.length < 6) {

            alert(
                'Password baru minimal 6 karakter.'
            );

            return;

        }


        if (!confirmPassword) {

            alert(
                'Konfirmasi password baru wajib diisi.'
            );

            return;

        }


        if (
            newPassword !==
            confirmPassword
        ) {

            alert(
                'Konfirmasi password baru tidak cocok.'
            );

            return;

        }


        if (
            currentPassword ===
            newPassword
        ) {

            alert(
                'Password baru harus berbeda dengan password saat ini.'
            );

            return;

        }


        try {

            setPasswordLoading(
                true
            );


            /*
            |--------------------------------------------------------------------------
            | AMBIL TOKEN
            |--------------------------------------------------------------------------
            */

            const token =
                localStorage.getItem(
                    'token'
                );


            if (!token) {

                alert(
                    'Token login tidak ditemukan. Silakan login kembali.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | REQUEST UPDATE PASSWORD
            |--------------------------------------------------------------------------
            */

            const response =
                await axios.put(
                    'http://127.0.0.1:8000/api/profile/password',
                    {
                        current_password:
                            currentPassword,

                        new_password:
                            newPassword,

                        new_password_confirmation:
                            confirmPassword
                    },
                    {
                        headers: {
                            Authorization:
                                `Bearer ${token}`,

                            Accept:
                                'application/json',

                            'Content-Type':
                                'application/json'
                        }
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | DEBUG
            |--------------------------------------------------------------------------
            */

            console.log(
                'PASSWORD RESPONSE:',
                response.data
            );


            /*
            |--------------------------------------------------------------------------
            | BERHASIL
            |--------------------------------------------------------------------------
            */

            alert(
                response.data?.message ||
                'Password berhasil diubah.'
            );


            /*
            |--------------------------------------------------------------------------
            | TUTUP MODAL
            |--------------------------------------------------------------------------
            */

            setShowPasswordModal(
                false
            );


            /*
            |--------------------------------------------------------------------------
            | RESET FORM
            |--------------------------------------------------------------------------
            */

            setCurrentPassword('');

            setNewPassword('');

            setConfirmPassword('');

        }

        catch (error) {

            console.error(
                'PASSWORD ERROR:',
                error
            );

            console.error(
                'PASSWORD ERROR RESPONSE:',
                error.response?.data
            );


            /*
            |--------------------------------------------------------------------------
            | VALIDATION ERROR
            |--------------------------------------------------------------------------
            */

            if (
                error.response?.status === 422
            ) {

                const message =
                    error.response?.data?.message;


                if (message) {

                    alert(
                        message
                    );

                }

                else {

                    alert(
                        'Data password tidak valid.'
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | UNAUTHORIZED
            |--------------------------------------------------------------------------
            */

            else if (
                error.response?.status === 401
            ) {

                alert(
                    'Sesi login sudah tidak valid. Silakan login kembali.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ERROR LAIN
            |--------------------------------------------------------------------------
            */

            else {

                alert(
                    'Gagal mengubah password. Silakan coba lagi.'
                );

            }

        }

        finally {

            setPasswordLoading(
                false
            );

        }

    };


    /*
    |--------------------------------------------------------------------------
    | HEADER ACTION
    |--------------------------------------------------------------------------
    */

    const renderHeaderAction = (

        <Button
            variant="danger"
            icon={SignOut}
            onClick={handleLogout}
        >
            Logout
        </Button>

    );


    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    if (loading) {

        return (

            <DashboardLayout
                onNavigate={onNavigate}
                currentPage="profile"
                pageTitle="Profile"
                pageSubtitle="Kelola informasi akun dan keamanan Anda"
                headerAction={renderHeaderAction}
            >

                <div className="profile-page">

                    <div className="jadwal-detail-empty">

                        Memuat data profile...

                    </div>

                </div>

            </DashboardLayout>

        );

    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if (error) {

        return (

            <DashboardLayout
                onNavigate={onNavigate}
                currentPage="profile"
                pageTitle="Profile"
                pageSubtitle="Kelola informasi akun dan keamanan Anda"
                headerAction={renderHeaderAction}
            >

                <div className="profile-page">

                    <div className="jadwal-detail-error">

                        {error}

                    </div>

                </div>

            </DashboardLayout>

        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATA USER
    |--------------------------------------------------------------------------
    */

    const namaUser =
        user?.nama ||
        user?.nama_mahasiswa ||
        user?.nama_lengkap ||
        user?.name ||
        'Administrator';


    const username =
        user?.username ||
        '-';


    const role =
        user?.role ||
        user?.jabatan ||
        'Administrator Akademik';


    const status =
        user?.status ||
        'Aktif';


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    return (

        <DashboardLayout

            onNavigate={onNavigate}

            currentPage="profile"

            pageTitle="Profile"

            pageSubtitle="Kelola informasi akun dan keamanan Anda"

            headerAction={renderHeaderAction}

        >

            <div className="profile-page">


                {/* ============================================================
                    PROFILE HERO
                ============================================================ */}

                <div className="profile-hero">

                    <div className="profile-hero-decoration decoration-one"></div>

                    <div className="profile-hero-decoration decoration-two"></div>


                    <div className="profile-logo">

                        <GraduationCap
                            size={38}
                            weight="fill"
                        />

                    </div>


                    <div className="profile-hero-content">

                        <span className="profile-hero-label">

                            SISTEM AKADEMIK

                        </span>


                        <h2>

                            Profil Pengguna

                        </h2>


                        <p>

                            Kelola informasi akun dan keamanan sistem Anda

                        </p>

                    </div>

                </div>


                {/* ============================================================
                    INFORMASI AKUN
                ============================================================ */}

                <div className="profile-card account-card">

                    <div className="card-accent"></div>


                    <div className="card-heading">

                        <div className="section-icon account-icon">

                            <UserCircle
                                size={25}
                                weight="fill"
                            />

                        </div>


                        <div>

                            <h3>

                                Informasi Akun

                            </h3>


                            <p>

                                Informasi pengguna yang sedang login

                            </p>

                        </div>

                    </div>


                    <div className="account-main">

                        <div className="avatar">

                            <UserCircle
                                size={54}
                                weight="fill"
                            />

                        </div>


                        <div className="account-name">

                            <h2>

                                {namaUser}

                            </h2>


                            <p>

                                {role}

                            </p>

                        </div>


                        <div className="account-status">

                            <span className="status-dot"></span>

                            {status}

                        </div>

                    </div>


                    <div className="account-divider"></div>


                    <div className="account-details">

                        <div className="detail-item">

                            <span>

                                Username

                            </span>


                            <strong>

                                {username}

                            </strong>

                        </div>


                        <div className="detail-item">

                            <span>

                                Role

                            </span>


                            <strong>

                                {role}

                            </strong>

                        </div>

                    </div>

                </div>


                {/* ============================================================
                    KEAMANAN AKUN
                ============================================================ */}

                <div className="profile-card security-card">

                    <div className="card-accent"></div>


                    <div className="card-heading">

                        <div className="section-icon security-icon">

                            <ShieldCheck
                                size={25}
                                weight="fill"
                            />

                        </div>


                        <div>

                            <h3>

                                Keamanan Akun

                            </h3>


                            <p>

                                Kelola keamanan dan password akun Anda

                            </p>

                        </div>

                    </div>


                    <div className="security-content">

                        <div className="security-description">

                            <div className="security-lock">

                                <LockKey
                                    size={22}
                                    weight="fill"
                                />

                            </div>


                            <div>

                                <strong>

                                    Password Akun

                                </strong>


                                <p>

                                    Pastikan password Anda selalu aman
                                    dan diperbarui secara berkala.

                                </p>

                            </div>

                        </div>


                        <Button
                            variant="primary"
                            onClick={openPasswordModal}
                        >

                            Ubah Password

                        </Button>

                    </div>

                </div>


                {/* ============================================================
                    MODAL UBAH PASSWORD
                ============================================================ */}

                {showPasswordModal && (

                    <div
                        className="password-modal-overlay"
                        onClick={closePasswordModal}
                    >

                        <div
                            className="password-modal"
                            onClick={(event) =>
                                event.stopPropagation()
                            }
                        >


                            {/* HEADER MODAL */}

                            <div className="password-modal-header">

                                <div className="password-modal-title">

                                    <div className="modal-lock-icon">

                                        <LockKey
                                            size={22}
                                            weight="fill"
                                        />

                                    </div>


                                    <div>

                                        <h3>

                                            Ubah Password

                                        </h3>


                                        <p>

                                            Perbarui password akun Anda

                                        </p>

                                    </div>

                                </div>


                                <button
                                    type="button"
                                    className="modal-close-button"
                                    onClick={closePasswordModal}
                                    title="Tutup"
                                >

                                    <X
                                        size={20}
                                        weight="bold"
                                    />

                                </button>

                            </div>


                            {/* BODY MODAL */}

                            <div className="password-modal-body">

                                <InputField

                                    label="Password Saat Ini"

                                    type="password"

                                    placeholder="Masukkan password saat ini"

                                    value={
                                        currentPassword
                                    }

                                    onChange={(event) =>
                                        setCurrentPassword(
                                            event.target.value
                                        )
                                    }

                                    className="profile-input-wrapper"

                                />


                                <InputField

                                    label="Password Baru"

                                    type="password"

                                    placeholder="Masukkan password baru"

                                    value={
                                        newPassword
                                    }

                                    onChange={(event) =>
                                        setNewPassword(
                                            event.target.value
                                        )
                                    }

                                    className="profile-input-wrapper"

                                />


                                <InputField

                                    label="Konfirmasi Password Baru"

                                    type="password"

                                    placeholder="Masukkan ulang password baru"

                                    value={
                                        confirmPassword
                                    }

                                    onChange={(event) =>
                                        setConfirmPassword(
                                            event.target.value
                                        )
                                    }

                                    className="profile-input-wrapper"

                                />

                            </div>


                            {/* FOOTER MODAL */}

                            <div className="password-modal-footer">

                                <Button
                                    variant="secondary"
                                    onClick={closePasswordModal}
                                    disabled={passwordLoading}
                                >

                                    Batal

                                </Button>


                                <Button
                                    variant="primary"
                                    onClick={handleSavePassword}
                                    disabled={passwordLoading}
                                >

                                    {passwordLoading
                                        ? 'Menyimpan...'
                                        : 'Simpan Perubahan'
                                    }

                                </Button>

                            </div>

                        </div>

                    </div>

                )}

            </div>

        </DashboardLayout>

    );

};


export default ProfilePage;