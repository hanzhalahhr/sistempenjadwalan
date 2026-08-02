import React, { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  Plus,
  MicrosoftExcelLogo,
  CalendarCheck
} from "@phosphor-icons/react";


import '../styles/Dashboard.css';



const GenerateJadwalPage = ({ onNavigate }) => {

  const [inputReady, setInputReady] = useState(false);

  const [configReady, setConfigReady] = useState(false);


  useEffect(()=>{

    const inputStatus = localStorage.getItem(
      "input_ready"
    );

    const configStatus = localStorage.getItem(
      "config_ready"
    );

    setInputReady(
      inputStatus === "true"
    );

    setConfigReady(
      configStatus === "true"
    );

  },[]);

  const allReady = inputReady && configReady;


  return (
    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="generate"

      pageTitle="Generate Jadwal"

      pageSubtitle="Buat jadwal perkuliahan"

    >

      <div 
        style={{ 
          textAlign:'left',
          paddingLeft:'20px',
          marginTop:'30px'
        }}
      >

       <h3
        style={{
          color:'#010101',
          fontSize:'20px',
          fontWeight:'700',
          marginBottom:'10px'
        }}
      >
        Langkah 1 - Pilih Sumber Data
      </h3>

        <p
          style={{
            color:'#777',
            fontSize:'14px',
            marginBottom:'30px',
            lineHeight:'22px'
          }}
        >
          Pilih salah satu metode untuk mengisi data yang akan digunakan dalam proses generate jadwal.
        </p>

        <div className="cards-grid">


          {/* Input Manual */}

          <div

            className="data-card"

            style={{
              flexDirection:'column',
              gap:'20px',
              cursor:'pointer'
            }}

            onClick={() => onNavigate('input-manual')}

          >

            <div
              className="icon-wrapper"
              style={{
                backgroundColor:"#fff4e6",
                color:"#f39c12",
                width:"80px",
                height:"80px"
              }}
            >

              <CalendarCheck 
                size={36} 
                weight="fill" 
              />

            </div>

            <div style={{textAlign:'center'}}>

              <h3
                style={{
                  color:'#010101',
                  marginBottom:'15px',
                  fontSize:'16px'
                }}
              >

                Input Data Manual

              </h3>

              <Button variant="primary">

                Input Data Manual

              </Button>

            </div>

          </div>

          {/* Import Excel */}


          <div

            className="data-card"

            style={{
              flexDirection:'column',
              gap:'20px',
              cursor:'pointer'
            }}

            onClick={() => onNavigate('import-excel')}

          >

            <div

              className="icon-wrapper"

              style={{
                backgroundColor:'#e6f6ec',
                color:'#2ecc71',
                width:'80px',
                height:'80px'
              }}

            >

              <MicrosoftExcelLogo size={36} weight="bold"/>

            </div>

            <div style={{textAlign:'center'}}>

              <h3

                style={{
                  color:'#010101',
                  marginBottom:'15px',
                  fontSize:'16px'
                }}

              >

                Impor dari Excel

              </h3>

              <Button
                style={{
                  background:'#2ecc71',
                  color:'white'
                }}
              >

                Impor Excel

              </Button>

            </div>

          </div>


          {/* Konfigurasi Jadwal */}

          <div
            className="data-card"
            style={{
              flexDirection: "column",
              gap: "20px",
              cursor: "pointer"
            }}
            onClick={() => onNavigate("konfigurasi-jadwal")}
          >

            <div
              className="icon-wrapper"
              style={{
                backgroundColor:"#e8f0ff",
                color:"#3498db",
                width:"80px",
                height:"80px"
              }}
            >

              <CalendarCheck 
                size={36} 
                weight="fill"
              />

            </div>

            <div style={{textAlign:"center"}}>

              <h3
                style={{
                  color:'#010101',
                  marginBottom:'15px',
                  fontSize:'16px',
                  fontWeight:'700'
                }}
              >
                Konfigurasi Jadwal
              </h3>

              <Button
                style={{
                  background:'#3498db',
                  color:'white'
                }}
              >
                Konfigurasi Jadwal
              </Button>

            </div>

          </div>

        </div>
          <h3
            style={{
              color:"#010101",
              fontSize:"20px",
              fontWeight:"700",
              marginTop:"50px",
              marginBottom:"10px"
            }}
          >
          Langkah 2 - Generate Jadwal
            </h3>

        <p
          style={{
            color:"#777",
            fontSize:"14px",
            marginBottom:"25px"
          }}
        >
          Pastikan seluruh data dan konfigurasi sudah lengkap sebelum melakukan proses generate.
        </p>

      <div className="config-card">

        <table className="status-table">

          <tbody>

            <tr>
              <td className="status-label">Input Data</td>
              <td>:</td>
              <td style={{color: inputReady ? "#22c55e" : "#ef4444", fontWeight:"700"}}>
                {inputReady ? "Siap" :"Belum Siap"}
              </td>
            </tr>

            <tr>
              <td className="status-label">Konfigurasi Jadwal</td>
              <td>:</td>
              <td style={{color: configReady ? "#22c55e" : "#ef4444", fontWeight:"700"}}>
                {configReady ? "Sudah Simpan" :"Belum Disimpan"}
              </td>
            </tr>

            <tr>
              <td className="status-label">Status</td>
              <td>:</td>
              <td
                style={{
                  color: allReady ? "#22c55e" : "#ef4444",
                  fontWeight:"700"
                }}
              >
                {
                  allReady 
                  ? "Siap Generate" 
                  : "Belum Siap Generate"
                }
              </td>
            </tr>

          </tbody>

        </table>

      </div>

        <div
          style={{
            display:"flex",
            justifyContent:"flex-end",
            marginTop:"20px"
          }}
        >

          <Button
            disabled={!allReady}
            onClick={()=>{

              if(!allReady){
                return;
              }

              onNavigate("proses-generate");

            }}
            style={{
              opacity: allReady ? 1 : 0.5,
              cursor: allReady ? "pointer" : "not-allowed"
            }}
          >
            Generate Jadwal
          </Button>

        </div>

      </div>
                
    </DashboardLayout>
  );

};


export default GenerateJadwalPage;