import React, { useState } from "react";

import DashboardLayout from "../components/DashboardLayout";
import Button from "../components/Button";
import '../styles/KonfigurasiJadwal.css';

import {
  Plus,
  Trash,
  Pencil,
  CheckCircle
} from "@phosphor-icons/react";

import "../styles/Dashboard.css";


  const KonfigurasiJadwalPage = ({ onNavigate }) => {

  const [semester, setSemester] = useState("Ganjil");

  const [tahunAkademik, setTahunAkademik] = useState("2026/2027");

  const [savingConfig,setSavingConfig] = useState(false);

  const [showSuccessPopup, setShowSuccessPopup] = useState(false);


  const [hari, setHari] = useState([
    { nama:"Senin", aktif:true },
    { nama:"Selasa", aktif:true },
    { nama:"Rabu", aktif:true },
    { nama:"Kamis", aktif:true },
    { nama:"Jumat", aktif:true },
    { nama:"Sabtu", aktif:false }
  ]);


  const [slotJam, setSlotJam] = useState([
    {
      id:1,
      mulai:"08:00",
      selesai:"09:40"
    },
    {
      id:2,
      mulai:"10:00",
      selesai:"11:40"
    },
    {
      id:3,
      mulai:"13:00",
      selesai:"14:40"
    }
  ]);



  const toggleHari = (index)=>{

    const data = [...hari];

    data[index].aktif = !data[index].aktif;

    setHari(data);

  };



  const tambahSlot = ()=>{

    setSlotJam([
      ...slotJam,
      {
        id:Date.now(),
        mulai:"15:00",
        selesai:"16:40"
      }
    ]);

  };



  const hapusSlot = (id)=>{

    setSlotJam(
      slotJam.filter(item=>item.id !== id)
    );

  };



  const editSlot = (id)=>{

    console.log("Edit slot", id);

  };



    const handleSave = () => {

        setSavingConfig(true);

        setTimeout(()=>{

            localStorage.setItem(
                "config_ready",
                "true"
            );
            setSavingConfig(false);

            setShowSuccessPopup(true);

        },1000);

    };



  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="generate"

      pageTitle="Konfigurasi Jadwal"

      pageSubtitle="Atur parameter sebelum proses generate jadwal"

    >

    <div style={{padding:"25px"}}>
      
      <div className="config-card">

        <h3>
          Periode Akademik
        </h3>


        <div className="form-row">


          <div>

            <label>
              Semester
            </label>


            <select
              value={semester}
              onChange={(e)=>setSemester(e.target.value)}
            >

              <option>Ganjil</option>
              <option>Genap</option>

            </select>


          </div>



          <div>

            <label>
              Tahun Akademik
            </label>


            <select
              value={tahunAkademik}
              onChange={(e)=>setTahunAkademik(e.target.value)}
            >

              <option>2026/2027</option>
              <option>2027/2028</option>

            </select>


          </div>


        </div>


      </div>





      {/* HARI */}

      <div className="config-card">


        <h3>
          Hari Kuliah
        </h3>



        <div className="hari-container">


          {
            hari.map((item,index)=>(

              <label
                key={index}
                className="hari-item"
              >

                <input
                  type="checkbox"
                  checked={item.aktif}
                  onChange={()=>toggleHari(index)}
                />


                {item.nama}


              </label>

            ))
          }


        </div>


      </div>






      {/* SLOT JAM */}

    <div className="config-card">

        <div className="card-header">

            <h3>
            Slot Jam Kuliah
            </h3>

            <Button onClick={tambahSlot}>
            <Plus size={18}/>
            Tambah Slot
            </Button>

        </div>


        <table className="config-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                {
                slotJam.map((item,index)=>(

                    <tr key={item.id}>

                        <td>
                            {index + 1}
                        </td>

                        <td>
                            <input
                            className="time-input"
                            value={item.mulai}
                            readOnly
                            />
                        </td>

                        <td>
                            <input
                            className="time-input"
                            value={item.selesai}
                            readOnly
                            />
                        </td>

                        <td className="action-column">

                            <div className="action-buttons">

                                <button
                                    className="edit-btn"
                                    onClick={()=>editSlot(item.id)}
                                >
                                    <Pencil size={15}/>
                                </button>

                                <button
                                    className="delete-btn"
                                    onClick={()=>hapusSlot(item.id)}
                                >
                                    <Trash size={15}/>
                                </button>

                            </div>

                        </td>

                    </tr>

                ))
                }

            </tbody>

        </table>

    </div>

    
    <Button onClick={handleSave} disabled={savingConfig}>

        { savingConfig ?

            "Menyimpan...":
            "Simpan Konfigurasi"

        }

    </Button>

    </div>
     
    {
        showSuccessPopup && (

            <div className="popup-overlay">

            <div className="popup-card">

                <div className="popup-icon">

                    <CheckCircle size={50}weight="fill"/>

                </div>

                <h2>
                Berhasil
                </h2>

                <p>
                Konfigurasi jadwal berhasil disimpan.
                <br />
                Sekarang Anda dapat melakukan proses generate jadwal.
                </p>

                <div className="popup-button-group">

                <Button
                    style={{
                    background:"#e5e7eb",
                    color:"#111827"
                    }}
                    onClick={()=>setShowSuccessPopup(false)}
                >
                    Tetap di Halaman
                </Button>

                <Button
                    onClick={()=>onNavigate("generate")}
                >
                    Ke Generate Jadwal
                </Button>

                </div>

            </div>

            </div>

        )
        }

    </DashboardLayout>

  );

};


export default KonfigurasiJadwalPage;