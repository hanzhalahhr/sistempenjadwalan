import React, {
  useEffect,
  useState
} from 'react';

import axios from 'axios';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  XCircle,
  CheckCircle,
  CircleNotch
} from '@phosphor-icons/react';

import '../styles/Dashboard.css';


const API =
  "http://127.0.0.1:8000/api";


const ProsesGeneratePage = ({
  onNavigate
}) => {

  const [progress, setProgress] =
    useState(0);

  const [currentStep, setCurrentStep] =
    useState(0);

  const [semesterId, setSemesterId] =
    useState(null);

  const [errorMessage, setErrorMessage] =
    useState("");

  const [isGenerating, setIsGenerating] =
    useState(false);


  const steps = [

    "Memeriksa kelengkapan data input",

    "Membaca konfigurasi jadwal",

    "Membuat kombinasi jadwal",

    "Melakukan validasi bentrok jadwal",

    "Menyimpan hasil generate"

  ];


  /*
  |--------------------------------------------------------------------------
  | AMBIL SEMESTER YANG DIPILIH
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    const savedSemesterId =
      localStorage.getItem(
        "semester_akademik_id"
      );


    console.log(
      "SEMESTER DARI LOCALSTORAGE:",
      savedSemesterId
    );


    if (savedSemesterId) {

      const id =
        Number(savedSemesterId);


      if (id > 0) {

        setSemesterId(id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS ERROR LAMA
        |--------------------------------------------------------------------------
        |
        | Ini penting.
        | Sebelumnya saat render pertama semesterId masih null,
        | error "Semester akademik belum dipilih" muncul.
        |
        | Setelah semester berhasil dibaca, error tersebut
        | tidak pernah dihapus.
        |
        */

        setErrorMessage("");

      }

    } else {

      setErrorMessage(
        "Semester akademik belum dipilih."
      );

    }

  }, []);


  /*
  |--------------------------------------------------------------------------
  | PROSES GENERATE
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    /*
    |--------------------------------------------------------------------------
    | JANGAN MULAI KALAU SEMESTER BELUM ADA
    |--------------------------------------------------------------------------
    */

    if (!semesterId) {

      return;

    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS ERROR SEBELUM MULAI
    |--------------------------------------------------------------------------
    */

    setErrorMessage("");


    /*
    |--------------------------------------------------------------------------
    | CEGAH GENERATE DOUBLE
    |--------------------------------------------------------------------------
    */

    if (isGenerating) {

      return;

    }


    setIsGenerating(true);


    let interval;


    /*
    |--------------------------------------------------------------------------
    | SIMULASI PROGRESS
    |--------------------------------------------------------------------------
    |
    | Progress hanya sampai 90%.
    | Setelah backend selesai baru kita pindah halaman.
    |
    */

    interval = setInterval(() => {

      setProgress(prev => {

        if (prev >= 90) {

          clearInterval(interval);

          return 90;

        }


        return prev + 2;

      });

    }, 100);


    /*
    |--------------------------------------------------------------------------
    | REQUEST GENERATE KE BACKEND
    |--------------------------------------------------------------------------
    |
    | Tidak lagi menunggu progress 100%.
    | Backend langsung dipanggil setelah semester tersedia.
    |
    */

    console.log(
      "POST GENERATE DIKIRIM"
    );


    console.log(
      "SEMESTER ID:",
      semesterId
    );


    axios
      .post(
        `${API}/generatejadwal/run`,
        {
          semester_akademik_id:
            semesterId
        }
      )

      .then(response => {

        console.log(
          "HASIL GENERATE:",
          response.data
        );


        /*
        |--------------------------------------------------------------------------
        | STOP PROGRESS
        |--------------------------------------------------------------------------
        */

        if (interval) {

          clearInterval(interval);

        }


        /*
        |--------------------------------------------------------------------------
        | SET PROGRESS SELESAI
        |--------------------------------------------------------------------------
        */

        setProgress(100);

        setCurrentStep(4);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN GENERATE ID
        |--------------------------------------------------------------------------
        */

        if (
          response.data.generate_id
        ) {

          localStorage.setItem(
            "generate_id",
            response.data.generate_id
          );

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN SEMESTER
        |--------------------------------------------------------------------------
        */

        if (
          response.data.semester_akademik_id
        ) {

          localStorage.setItem(
            "semester_akademik_id",
            response.data.semester_akademik_id
          );

        } else {

          /*
          |--------------------------------------------------------------------------
          | Backend saat ini belum mengembalikan
          | semester_akademik_id.
          |
          | Jadi tetap gunakan semester yang sedang dipilih.
          |--------------------------------------------------------------------------
          */

          localStorage.setItem(
            "semester_akademik_id",
            semesterId
          );

        }


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS GENERATE
        |--------------------------------------------------------------------------
        */

        if (
          response.data.status ===
          "Berhasil"
        ) {

          /*
          |--------------------------------------------------------------------------
          | PINDAH KE HASIL BERHASIL
          |--------------------------------------------------------------------------
          */

          setTimeout(() => {

            onNavigate(
              "hasil-generate"
            );

          }, 300);

        } else {

          /*
          |--------------------------------------------------------------------------
          | PINDAH KE HASIL GAGAL
          |--------------------------------------------------------------------------
          */

          setTimeout(() => {

            onNavigate(
              "hasil-generate-gagal"
            );

          }, 300);

        }

      })

      .catch(error => {

        console.error(
          "ERROR GENERATE:",
          error
        );


        console.error(
          "RESPONSE ERROR:",
          error.response?.data
        );


        if (interval) {

          clearInterval(interval);

        }


        setIsGenerating(false);


        setErrorMessage(

          error.response?.data?.message ||

          "Gagal melakukan generate jadwal."

        );

      });


    /*
    |--------------------------------------------------------------------------
    | CLEANUP
    |--------------------------------------------------------------------------
    */

    return () => {

      if (interval) {

        clearInterval(interval);

      }

    };

  }, [
    semesterId
  ]);


  /*
  |--------------------------------------------------------------------------
  | UPDATE STEP
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    if (progress < 20) {

      setCurrentStep(0);

    }

    else if (progress < 40) {

      setCurrentStep(1);

    }

    else if (progress < 60) {

      setCurrentStep(2);

    }

    else if (progress < 85) {

      setCurrentStep(3);

    }

    else {

      setCurrentStep(4);

    }

  }, [
    progress
  ]);


  /*
  |--------------------------------------------------------------------------
  | BATAL
  |--------------------------------------------------------------------------
  */

  const handleCancel = () => {

    onNavigate(
      'generate'
    );

  };


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <DashboardLayout

      onNavigate={
        onNavigate
      }

      currentPage="generate"

      pageTitle="Generate Jadwal - Proses"

      pageSubtitle="Proses pembuatan jadwal otomatis"

    >

      <div className="process-card">


        <CircleNotch

          size={55}

          className="process-spinner"

        />


        <h2>

          Proses Generate Jadwal

        </h2>


        <p className="process-description">

          Sistem sedang membuat jadwal
          perkuliahan otomatis.

        </p>


        {errorMessage && (

          <div
            style={{
              marginTop: "20px",
              padding: "12px 16px",
              background: "#fee2e2",
              color: "#b91c1c",
              borderRadius: "8px",
              fontSize: "14px"
            }}
          >

            {errorMessage}

          </div>

        )}


        <div className="progress-container">

          <div

            className="progress-bar"

            style={{
              width: `${progress}%`
            }}

          />

        </div>


        <p className="progress-text">

          {progress}%

        </p>


        <div className="step-container">

          {steps.map(
            (step, index) => (

              <div

                key={index}

                className={
                  index <= currentStep
                    ? "step active"
                    : "step"
                }

              >

                {index < currentStep ? (

                  <CheckCircle
                    size={22}
                  />

                ) : index === currentStep ? (

                  <CircleNotch
                    size={22}
                  />

                ) : (

                  <span>
                    ○
                  </span>

                )}


                {step}

              </div>

            )
          )}

        </div>


      </div>


      <div

        style={{

          display: 'flex',

          justifyContent: 'flex-end',

          marginTop: '40px'

        }}

      >

        <Button

          variant="danger"

          icon={XCircle}

          onClick={
            handleCancel
          }

          disabled={
            progress === 100
          }

        >

          Batalkan

        </Button>

      </div>


    </DashboardLayout>

  );

};


export default ProsesGeneratePage;