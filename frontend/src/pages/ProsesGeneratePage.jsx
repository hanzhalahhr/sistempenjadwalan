import React, { useState, useEffect } from 'react';
import axios from 'axios';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  XCircle,
  CheckCircle,
  CircleNotch
} from '@phosphor-icons/react';

import '../styles/Dashboard.css';


const ProsesGeneratePage = ({ onNavigate }) => {


  const [progress, setProgress] = useState(0);

  const [currentStep, setCurrentStep] = useState(0);



  const steps = [

    "Memeriksa kelengkapan data input",

    "Membaca konfigurasi jadwal",

    "Membuat kombinasi jadwal",

    "Melakukan validasi bentrok jadwal",

    "Menyimpan hasil generate"

  ];




  useEffect(() => {


    const interval = setInterval(() => {


      setProgress(prev => {


        if(prev >= 100){


          clearInterval(interval);



          console.log(
            "POST GENERATE DIKIRIM"
          );



          axios.post(
            "http://127.0.0.1:8000/api/generatejadwal/run"
          )

          .then(response => {


            console.log(
              "HASIL GENERATE:",
              response.data
            );




            // =========================
            // CEK STATUS GENERATE
            // =========================


            if (response.data.generate_id) {

              localStorage.setItem(
                "generate_id",
                response.data.generate_id
              );

            }

            if (response.data.status === "Berhasil") {

              onNavigate("hasil-generate");

            } else {

              onNavigate("hasil-generate-gagal");

            }


          })


          .catch(error => {

            console.log(
              "ERROR GENERATE:",
              error
            );

            onNavigate(
              "hasil-generate-gagal"
            );


          });

          return 100;


        }

        return prev + 2;


      });

    },100);


    return () => clearInterval(interval);

  }, [onNavigate]);



  useEffect(() => {


    if(progress < 25){

      setCurrentStep(0);

    }

    else if(progress < 50){

      setCurrentStep(1);

    }

    else if(progress < 75){

      setCurrentStep(2);

    }

    else if(progress < 95){

      setCurrentStep(3);

    }

    else{

      setCurrentStep(4);

    }


  },[progress]);



  return (

    <DashboardLayout

      onNavigate={onNavigate}

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

          Sistem sedang membuat jadwal perkuliahan otomatis.

        </p>



        <div className="progress-container">


          <div

            className="progress-bar"

            style={{

              width:`${progress}%`

            }}

          >

          </div>


        </div>


        <p className="progress-text">

          {progress}%

        </p>


        <div className="step-container">

        {

          steps.map((step,index)=>(

            <div

              key={index}

              className={

                index <= currentStep

                ?

                "step active"

                :

                "step"

              }

            >

            {

              index < currentStep ?


              <CheckCircle

                size={22}

              />


              :


              index === currentStep ?


              <CircleNotch

                size={22}

              />



              :


              <span>

                ○

              </span>


            }


              {step}



            </div>



          ))


        }


        </div>


      </div>



      <div

        style={{

          display:'flex',

          justifyContent:'flex-end',

          marginTop:'40px'

        }}

      >



        <Button

          variant="danger"

          icon={XCircle}

          onClick={() => onNavigate('generate')}

        >

          Batalkan

        </Button>


      </div>



    </DashboardLayout>


  );


};



export default ProsesGeneratePage;