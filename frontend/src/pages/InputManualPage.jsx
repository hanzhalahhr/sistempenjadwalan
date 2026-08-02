import React, { useState, useEffect } from 'react';
import axios from 'axios';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { Trash, Plus, ArrowRight, NotePencil, CheckCircle, XCircle, SpinnerGap } from '@phosphor-icons/react';
import '../styles/InputManual.css';

const InputManualPage = ({ onNavigate }) => {
  const [activeTab, setActiveTab] = useState('mk'); 
  const [mataKuliah, setMataKuliah] = useState([]);
  const [originalRow, setOriginalRow] = useState(null);
  const [prodi,setProdi] = useState([]);
  const [ruangan, setRuangan] = useState([]);
  const [kelasPerkuliahan, setKelasPerkuliahan] = useState([]);
  const [showGenerateModal, setShowGenerateModal] = useState(false);
  const [loadingGenerate, setLoadingGenerate] = useState(false);
  const [dataSaved, setDataSaved] = useState(false);
  const [loadingButton, setLoadingButton] = useState(false);

  const [tableData, setTableData] = useState({
    mk: [
      {
        id: Date.now(),
        col1:'',
        col2:'',
        col3:'',
        col4:'',
        col5:'',
        isEditing:true,
        isNew:true
      }
    ],
    dosen: [],
    ruangan: [
      {
        id: Date.now(),
        col1:'',
        col2:'',
        col3:'',
        isEditing:true,
        isNew:true
      }
    ],
    kelas: []
  });

 const [semesterAkademik, setSemesterAkademik] = useState([]);

  
 const getDataDosen = () => {

    axios.get('http://127.0.0.1:8000/api/dosen')

    .then((response)=>{

      const dataDosen = response.data.map((item)=>({ id:item.id, 
      col1:item.nidn || '',col2:item.nama_dosen, col3:item.mata_kuliah?
      item.mata_kuliah.map(mk=>mk.nama_mk).join(', '): '',
      col4:item.jabatan, mata_kuliah_ids:item.mata_kuliah ?
      item.mata_kuliah.map(mk=>mk.id) :[],

    isEditing:false, isNew:false

  }));


        setTableData(prev=>({
            ...prev,
            dosen:dataDosen
        }));

    })

    .catch(error=>{

        console.log(
        "Gagal mengambil data dosen",
        error
        );

    });

};

    const getDataMataKuliah = () => {

    

    axios.get(
      'http://127.0.0.1:8000/api/mata-kuliah'
    )

    .then((response)=>{


      const dataMK = response.data.map((item)=>({

        id:item.id,

        col1:item.kode_mk,

        col2:item.nama_mk,

        col3:item.sks,

        col4:item.semester,

        col5:item.kelas_perkuliahan.length > 0 ? `${item.kelas_perkuliahan[0].prodi.nama_prodi} - ${item.kelas_perkuliahan[0].prodi.jenjang}`: "",

        isEditing:false,
        isNew:false

      }));


      setTableData(prev=>({

        ...prev,

        mk:dataMK

      }));


      setMataKuliah(response.data);


    })

    .catch(error=>{

      console.log(
        "Gagal mengambil data mata kuliah",
        error
      );

    });

};

  const getDataSemesterAkademik = () => {

    axios.get(
      'http://127.0.0.1:8000/api/semester-akademik'
    )

    .then((response)=>{

        setSemesterAkademik(response.data);

    })

    .catch(error=>{

        console.log(
          "Gagal mengambil data semester akademik",
          error
        );

    });

  };















  const getDataProdi = () => {

    axios.get(
      'http://127.0.0.1:8000/api/prodi'
    )

    .then((response)=>{

      setProdi(response.data);

    })

    .catch(error=>{

      console.log(
        "Gagal mengambil data prodi",
        error
      );

    });

  };

  useEffect(()=>{

    getDataDosen();
    getDataProdi();
    getDataRuangan();
    getDataKelas();
    getDataMataKuliah();
    getDataSemesterAkademik();

  },[]);


  const getDataRuangan = () => {

  axios.get('http://127.0.0.1:8000/api/ruangan')

  .then((response)=>{

    const dataRuangan = response.data.map((item)=>({

      id:item.id,

      col1:item.nama_ruangan,

      col2:item.lantai,

      col3:item.gedung,

      col4:item.kapasitas,

      col5:item.tipe_ruangan,

      isEditing:false,

      isNew:false

    }));

    setTableData(prev=>({

      ...prev,

      ruangan:dataRuangan

    }));

    setRuangan(response.data);

  })

    .catch(error=>{

      console.log(
        "Gagal mengambil data ruangan",
        error
      );

    });

  };


  const getDataKelas = () => {

    axios.get(
      "http://127.0.0.1:8000/api/kelasperkuliahan"
    )

    .then((response)=>{

      const data = response.data.map(item=>({

        id:item.id,

        col1:item.mata_kuliah?.nama_mk || "",

        col2:item.prodi
        ? `${item.prodi.nama_prodi} - ${item.prodi.jenjang}`
        : "",

        col3:item.nama_kelas || "",

        col4:item.angkatan || "",

        col5:item.jumlah_mahasiswa || "",

        col6:item.semester_akademik
        ? `${item.semester_akademik.tahun_akademik} - ${item.semester_akademik.periode}`
        : "",


        mata_kuliah_id:item.mata_kuliah_id,

        prodi_id:item.prodi_id,

        semester_akademik_id:item.semester_akademik_id,
        semester_label:item.semester_akademik
        ? `${item.semester_akademik.tahun_akademik} - ${item.semester_akademik.periode}`
        : "",

        dosen_ids: item.dosen
        ? item.dosen.map(d => d.id)
        : [],

        dosen_label: item.dosen
        ? item.dosen.map(d => d.nama_dosen).join(", ")
        : "",


        isEditing:false,
        isNew:false
      }));


      setTableData(prev=>({

        ...prev,

        kelas:data

      }));

    })

    .catch(error=>{

      console.log(
        "Gagal mengambil data kelas",
        error
      );

    });

  };



  const tabConfig = {
    mk: {
      subtitle: "Tambahkan data mata kuliah secara manual",
      gridTemplate: "250px 2fr 120px 200px 2fr 100px",
      headers: ["Kode MK", "Nama Mata Kuliah", "SKS", "Semester", "Program Studi", "Aksi"],
      inputs: [
        { key: 'col1', type: 'text', placeholder: 'Kode MK' },
        { key: 'col2', type: 'text', placeholder: 'Nama Mata Kuliah' },
        { key: 'col3', type: 'text', placeholder: 'SKS' },
        { key: 'col4', type: 'text', placeholder: 'Semester' },
        { key: 'col5', type: 'select', options: ['Pilih Program Studi', ...prodi.map(item=>`${item.nama_prodi} - ${item.jenjang}`)]},
      ]
    },

    
    dosen: {
      subtitle: "Tambahkan data dosen secara manual",
      gridTemplate:"180px 300px 300px 180px 100px",
      headers: ["NIDN", "Nama Dosen", "Pengampu MK", "Jabatan", "Aksi"],
      inputs: [
        { key:'col1', type:'text', placeholder:'Masukkan NIDN' },
        { key:'col2', type:'text', placeholder:'Masukkan Nama Dosen' },
        {key:'col3', type:'select', options:['Pilih Mata Kuliah',...mataKuliah.map(item=>`${item.nama_mk} - ${item.prodi?.nama_prodi || ''}`)]},
        { key:'col4', type:'select', options:['Pilih Jabatan', 'Lektor', 'Asisten Ahli', 'S2', 'S3', 'Guru Besar'] }
      ]
    },


    ruangan: {
      subtitle: "Tambahkan data ruangan secara manual",
      gridTemplate: "2fr 80px 2fr 120px 150px 100px",
      headers: ["Nama Ruangan", "Lantai" , "Gedung", "Kapasitas", "Tipe Ruangan", "Aksi"],
      inputs: [
        { key: 'col1', type: 'text', placeholder: 'Contoh: FIK-202' },
        { key: 'col2', type: 'text', placeholder: 'Lantai' },
        { key: 'col3', type: 'text', placeholder: 'Nama Gedung' },
        { key: 'col4', type: 'text', placeholder: 'Kapasitas' },
        { key: 'col5', type: 'select', options: ['Pilih Tipe Ruangan', 'Kelas', 'Lab']}
      ]
    },


    kelas: {
      subtitle: "Kelola kelas perkuliahan",
      gridTemplate: "2fr 1.5fr 180px 150px 150px 175px 150px 100px", 
      headers: ["Mata Kuliah", "Program Studi" , "Kelas", "Angkatan", "Jumlah Mhs", "Dosen",  "Semester", "Aksi"],
      inputs: [
        { key: 'col1', type: 'select', options:["Pilih Mata Kuliah",...mataKuliah.map(item=>`${item.nama_mk} - ${item.prodi?.nama_prodi || ''}`)] },
        { key: 'col2', type: 'select', options:["Pilih Program Studi",...prodi.map(item=>`${item.nama_prodi} - ${item.jenjang}`)]},
        { key: 'col3', type: 'text', placeholder: "Contoh A" },
        { key: 'col4', type: 'text', placeholder: "Angkatan" },
        { key: 'col5', type: 'text', placeholder: "Jumlah Mahasiswa" },
        { key:'dosen_ids', type:'select', options:["Pilih Dosen",...tableData.dosen.map(item=>item.col2)]},
        { key:'semester_akademik_id', type:'select', options:["Pilih Semester",...semesterAkademik.map(item=>`${item.tahun_akademik} - ${item.periode}`)]}
      ]
    },



  };

  const currentConfig = tabConfig[activeTab];
  const currentData = tableData[activeTab];

  const hasEditingRows = Object.values(tableData).flat().some(row => row.isEditing);

  const handleTopAction = () => {

    // kondisi data belum disimpan
    if(!dataSaved){

      setLoadingButton(true);

      setTimeout(()=>{

      setLoadingButton(false);

      setDataSaved(true);

      },1000);

      return;

    }

    // kondisi data sudah siap
    setShowGenerateModal(true);

  };


  // Handler untuk menambah baris kosong dan masuk mode edit
  const handleAddRow = () => {

    let newRow;


    // =========================
    // TAMBAH DATA MATA KULIAH
    // =========================
    if (activeTab === "mk") {

      newRow = {
        id: Date.now(),

        col1: '', // Kode MK
        col2: '', // Nama Mata Kuliah
        col3: '', // SKS
        col4: '', // Semester
        col5: '', // Program Studi

        isEditing: true,
        isNew: true
      };

    }


    // =========================
    // TAMBAH DATA RUANGAN
    // =========================
    else if (activeTab === "ruangan") {

      newRow = {
        id: Date.now(),

        col1: '', // Nama Ruangan
        col2: '', // Lantai
        col3: '', // Gedung
        col4: '', // Kapasitas
        col5: '', // Tipe Ruangan

        isEditing: true,
        isNew: true
      };

    }

    else if(activeTab==="kelas"){

      newRow={

        id:Date.now(),

        col1:"",
        col2:"",
        col3:"",
        col4:"",
        col5:"",
        dosen_ids: [],
        dosen_label: "",

        semester_akademik_id:"",
        semester_label:"",

        isEditing:true,
        isNew:true
      };

    }


    // =========================
    // TAMBAH DATA DOSEN
    // =========================
    else if (activeTab === "dosen") {

      newRow = {
        id: Date.now(),

        col1: '', // NIDN
        col2: '', // Nama Dosen
        col3: '', // Pengampu MK
        col4: '', // Jabatan

        mata_kuliah_ids: [],

        isEditing: true,
        isNew: true
      };

    }


    // Masukkan row baru ke tabel aktif
    setTableData(prev => ({
      ...prev,
      [activeTab]: [
        ...prev[activeTab],
        newRow
      ]
    }));

  };



  // Handler untuk toggle edit/view spesifik per baris
  const handleToggleEdit = (id) => {

    const row = tableData[activeTab].find(item => item.id === id);

    setOriginalRow({ ...row });

    setTableData(prev => ({
    ...prev,
    [activeTab]: prev[activeTab].map(item =>
        item.id === id
            ? {
                ...item,
                isEditing: true
            }
            : item
      )
    }));
  };



  const handleDeleteRow = async (idToRemove) => {

  const yakin = window.confirm(
    "Apakah Anda yakin ingin menghapus data?"
  );

  if (!yakin) {
    return;
  }


  const row = tableData[activeTab].find(
  item=>item.id===idToRemove
  );

  if(row?.isNew){

  setTableData(prev=>({
  ...prev,
  [activeTab]:prev[activeTab].filter(
  item=>item.id!==idToRemove
  )
  }));

  return;

  }
  


  try {

    let endpoint = "";


    if(activeTab === "dosen"){

      endpoint =
      `http://127.0.0.1:8000/api/dosen/${idToRemove}`;

    }

    else if(activeTab === "mk"){

      endpoint =
      `http://127.0.0.1:8000/api/mata-kuliah/${idToRemove}`;

    }

    else if(activeTab === "ruangan"){

      endpoint =
      `http://127.0.0.1:8000/api/ruangan/${idToRemove}`;

    }


    else if(activeTab==="kelas"){

      endpoint=
      `http://127.0.0.1:8000/api/kelasperkuliahan/${idToRemove}`;

    }


    await axios.delete(endpoint);


    alert("Data berhasil dihapus");


    // refresh data sesuai menu

    if(activeTab === "dosen"){

      getDataDosen();

    }


    else if(activeTab === "mk"){

      getDataMataKuliah();

    }


    else if(activeTab === "ruangan"){

      getDataRuangan();

    }

    else if(activeTab==="kelas"){

      getDataKelas();

    }


  } catch(error){


    console.log(error);

    console.log(error.response?.data);

    alert("Gagal menghapus data");

  }

};



  const handleInputChange = (id, fieldKey, value) => {

  console.log(
    "FIELD:",
    fieldKey,
    "VALUE:",
    value
  );


  setTableData(prev => ({
    ...prev,

    [activeTab]: prev[activeTab].map(row => {

      if(row.id !== id){
        return row;
      }


      // ==========================
      // DOSEN - PILIH MATA KULIAH
      // ==========================
      if(activeTab === "dosen" && fieldKey === "col3"){

        const selectedMK = mataKuliah.find(
          mk =>
          `${mk.nama_mk} - ${mk.prodi?.nama_prodi || ''}` === value
        );


        return {
          ...row,
          col3:value,

          mata_kuliah_ids:selectedMK
          ?
          [
            ...(row.mata_kuliah_ids || []),
            selectedMK.id
          ]
          :
          row.mata_kuliah_ids
        };

      }


      // ==========================
      // KELAS - PILIH MATA KULIAH
      // ==========================
      if(activeTab === "kelas" && fieldKey === "col1"){

        const selectedMK = mataKuliah.find(
        item =>
        `${item.nama_mk} - ${item.prodi?.nama_prodi || ''}`
        === value
        );

        console.log("MK DIPILIH:", selectedMK);

        return {
          ...row,
          col1:value,
          mata_kuliah_id:selectedMK?.id
        };

      }



      // ==========================
      // KELAS - PILIH PRODI
      // ==========================
      if(activeTab === "kelas" && fieldKey === "col2"){
        
        console.log("PRODI VALUE:",value);

        const selectedProdi = prodi.find(
          item =>
          `${item.nama_prodi} - ${item.jenjang}` === value
        );

        console.log("PRODI DIPILIH:",selectedProdi);

        return {
          ...row,

          col2:value,

          prodi_id:selectedProdi?.id

        };

      }


      // ==========================
      // KELAS - PILIH SEMESTER
      // ==========================
      if(
        activeTab === "kelas" 
        && fieldKey === "semester_akademik_id"
        ){

        const selectedSemester = semesterAkademik.find(
          item =>
          `${item.tahun_akademik} - ${item.periode}`.trim()
          === value.trim()
        );

        console.log(
        "SEMESTER DIPILIH:",
        selectedSemester
        );

        return {
          ...row,
          semester_akademik_id:selectedSemester?.id,
          semester_label:value
        };

      }



      // ==========================
      // KELAS - PILIH DOSEN
      // ==========================
      if (
          activeTab === "kelas" &&
          fieldKey === "dosen_ids"
      ){

          const selectedDosen = tableData.dosen.find(
              item => item.col2 === value
          );

          console.log("DOSEN DIPILIH:", selectedDosen);

          return {
              ...row,

              dosen_ids: selectedDosen
                  ? [selectedDosen.id]
                  : [],

              dosen_label: value
          };

      }

















      // ==========================
      // DEFAULT INPUT
      // ==========================
      return {
        ...row,
        [fieldKey]:value
      };


    })

  }));

};



const handleSaveRow = async (row) => {

  console.log("ROW YANG MAU DISAVE:", row);

  const yakin = window.confirm(
    "Apakah Anda yakin ingin menyimpan data ini?"
  );

  if (!yakin) {
    return;
  }


  try {


    // ==========================
    // DATA BARU (POST)
    // ==========================
    if(row.isNew){


      // ==========================
      // MATA KULIAH
      // ==========================
      if(activeTab === "mk"){


        const selectedProdi = prodi.find(
          item =>
          `${item.nama_prodi} - ${item.jenjang}` === row.col5
        );


        await axios.post(
          "http://127.0.0.1:8000/api/mata-kuliah",
          {
            kode_mk: row.col1,
            nama_mk: row.col2,
            sks: row.col3,
            semester: row.col4,
            jenis:"Teori",
            prodi_id:selectedProdi?.id
          }
        );

      }


      // ==========================
      // DOSEN
      // ==========================
      else if(activeTab === "dosen"){


        await axios.post(
          "http://127.0.0.1:8000/api/dosen",
          {
            nidn:row.col1,
            nama_dosen:row.col2,
            jabatan:row.col4,
            mata_kuliah_ids:row.mata_kuliah_ids || []
          }
        );


      }


      // ==========================
      // RUANGAN
      // ==========================
      else if(activeTab === "ruangan"){

        await axios.post(
          "http://127.0.0.1:8000/api/ruangan",
          {
            nama_ruangan:row.col1,
            lantai:row.col2,
            gedung:row.col3,
            kapasitas:row.col4,
            tipe_ruangan:row.col5
          }
        );


      }


      // ==========================
      // KELAS PERKULIAHAN
      // ==========================
      else if(activeTab === "kelas"){

        console.log(
        "ID SEBELUM SAVE",
        {
        mata_kuliah_id:row.mata_kuliah_id,
        prodi_id:row.prodi_id
        }
        );


        console.log(
          "DATA KELAS KIRIM:",
          {
            mata_kuliah_id: row.mata_kuliah_id,
            prodi_id: row.prodi_id,
            semester_akademik_id: row.semester_akademik_id,
            nama_kelas: row.col3,
            angkatan: row.col4,
            jumlah_mahasiswa: row.col5,
            dosen_ids: row.dosen_ids
          }
        );


        if(
          !row.mata_kuliah_id ||
          !row.prodi_id ||
          !row.semester_akademik_id
          ){

          alert(
          "Mata kuliah, program studi, dan semester wajib dipilih"
          );

          return;

        }

        await axios.post(
          "http://127.0.0.1:8000/api/kelasperkuliahan",
          {
            mata_kuliah_id:row.mata_kuliah_id,
            prodi_id:row.prodi_id,
            semester_akademik_id:row.semester_akademik_id,
            nama_kelas:row.col3,
            angkatan:row.col4,
            jumlah_mahasiswa:row.col5,
            dosen_ids: row.dosen_ids || []
          }
        );


      }

      
      alert("Data berhasil ditambahkan");

    }



    // ==========================
    // EDIT DATA (PUT)
    // ==========================
    else {


      if(activeTab === "dosen"){


        await axios.put(
          `http://127.0.0.1:8000/api/dosen/${row.id}`,
          {
            nidn:row.col1,
            nama_dosen:row.col2,
            jabatan:row.col4,
            mata_kuliah_ids:row.mata_kuliah_ids || []
          }
        );


      }


      else if(activeTab === "mk"){

        const selectedProdi = prodi.find(
          item =>
          `${item.nama_prodi} - ${item.jenjang}` === row.col5
        );


        await axios.put(
          `http://127.0.0.1:8000/api/mata-kuliah/${row.id}`,
          {
            kode_mk:row.col1,
            nama_mk:row.col2,
            sks:row.col3,
            semester:row.col4,
            prodi_id:selectedProdi?.id
          }
        );

      }


      else if(activeTab === "ruangan"){


        await axios.put(
          `http://127.0.0.1:8000/api/ruangan/${row.id}`,
          {
            nama_ruangan:row.col1,
            lantai:row.col2,
            gedung:row.col3,
            kapasitas:row.col4,
            tipe_ruangan:row.col5
          }
        );


      }

      else if(activeTab === "kelas"){


        await axios.put(
          `http://127.0.0.1:8000/api/kelasperkuliahan/${row.id}`,
          {
            mata_kuliah_id:row.mata_kuliah_id,
            prodi_id:row.prodi_id,
            semester_akademik_id:row.semester_akademik_id,
            nama_kelas:row.col3,
            angkatan:row.col4,
            jumlah_mahasiswa:row.col5,
            dosen_ids:row.dosen_ids || []
          }
        );


      }

      alert("Data berhasil diperbarui");

    }


    // ==========================
    // REFRESH DATA
    // ==========================

    if(activeTab === "dosen"){
      getDataDosen();
    }


    if(activeTab === "mk"){
      getDataMataKuliah();
    }


    if(activeTab === "ruangan"){
      getDataRuangan();
    }


    if(activeTab === "kelas"){
      getDataKelas();
    }

  }

  catch(error){

    console.log(error);

    console.log(error.response?.data);

    alert("Gagal menyimpan data");

  }

};




  const handleCancelEdit = (id) => {

    const yakin = window.confirm(
        "Apakah Anda yakin ingin membatalkan perubahan?\n\nData yang belum disimpan akan hilang."
    );

    if (!yakin) {
        return;
    }

    const row = tableData[activeTab].find(item => item.id === id);

   if (row && row.isNew) {setTableData(prev => ({...prev,
      [activeTab]: prev[activeTab].filter(item => item.id !== id)
    }));

    setOriginalRow(null);
    return;
  }

    setTableData(prev => ({
    ...prev,
    [activeTab]: prev[activeTab].map(item =>
        item.id === id
            ? {
                ...originalRow,
                isEditing:false
            }
            : item
      )
    }));

    setOriginalRow(null);
  };


  const renderHeaderAction = (
    <Button 
      variant="primary" 
      onClick={handleTopAction} 
      icon={hasEditingRows ? null : ArrowRight}
      style={hasEditingRows ? { minWidth: '160px' } : { flexDirection: 'row-reverse', minWidth: '200px' }}
    >
      {loadingButton ? (
        <>
          <SpinnerGap
            size={18}
            className="spin-icon"
          />
          Menyimpan...

        </>

        )
        :
        !dataSaved
        ?

        "Simpan Data"
        :
        "Lanjut ke Generate"
      }
    </Button>
  );

  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="generate"
      pageTitle="Generate Jadwal - Input Manual"
      pageSubtitle={currentConfig.subtitle}
      headerAction={renderHeaderAction}
    >
      <div className="tabs-container">
        <button className={`tab-btn ${activeTab === 'mk' ? 'active' : ''}`} onClick={() => setActiveTab('mk')}>
          Data Mata Kuliah
        </button>
        <button className={`tab-btn ${activeTab === 'dosen' ? 'active' : ''}`} onClick={() => setActiveTab('dosen')}>
          Data Dosen
        </button>
        <button className={`tab-btn ${activeTab === 'ruangan' ? 'active' : ''}`} onClick={() => setActiveTab('ruangan')}>
          Data Ruangan
        </button>
        <button className={`tab-btn ${activeTab === 'kelas' ? 'active' : ''}`} onClick={() => setActiveTab('kelas')}>
          Kelas Perkuliahan
        </button>
      </div>

      <div className="dynamic-table-wrapper">
        <div className="dynamic-table-header" style={{ gridTemplateColumns: currentConfig.gridTemplate }}>
          {currentConfig.headers.map((header, idx) => (
            <div key={idx} style={{ textAlign: header === 'Aksi' ? 'center' : 'left' }}>
              {header}
            </div>
          ))}
        </div>

        {currentData.map((row) => (
          <div key={row.id} className="dynamic-table-row" style={{ gridTemplateColumns: currentConfig.gridTemplate }}>
            
            {currentConfig.inputs.map((inputDef) => (
              <div key={inputDef.key}>
                {row.isEditing ? (
                  // ----- MODE EDIT -----
                    inputDef.type === 'select' ? (
                    <select 
                      className="dynamic-input"
                      value={
                          inputDef.key === "semester_akademik_id"
                              ? row.semester_label || ""
                              : inputDef.key === "dosen_ids"
                              ? row.dosen_label || ""
                              : row[inputDef.key] || ""
                      }
                      onChange={(e) => handleInputChange(row.id, inputDef.key, e.target.value)}
                    >
                      {inputDef.options.map((opt, idx) => (
                        <option key={idx} value={opt.includes('Pilih') ? '' : opt} disabled={opt.includes('Pilih')}>
                          {opt}
                        </option>
                      ))}
                    </select>
                  ) : (
                    <input 
                      type="text" 
                      className="dynamic-input"
                      placeholder={inputDef.placeholder}
                      value={row[inputDef.key]}
                      onChange={(e) => handleInputChange(row.id, inputDef.key, e.target.value)}
                    />
                  )
                ) : (
                  // ----- MODE VIEW ONLY -----
                  <div className="dynamic-text">
                    {
                      inputDef.key === "semester_akademik_id"
                        ? row.semester_label || ""
                        : inputDef.key === "dosen_ids"
                        ? row.dosen_label || ""
                        : row[inputDef.key]
                    }
                  </div>











                )}
              </div>
            ))}

            <div className="action-cell">
              {row.isEditing ? (
                <button className="btn-icon-action save" onClick={() => handleSaveRow(row)} title="Simpan Baris">
                  <CheckCircle size={22} weight="fill" />
                </button>
              ) : (
                <button className="btn-icon-action edit" onClick={() => handleToggleEdit(row.id)} title="Edit Baris">
                  <NotePencil size={22} weight="bold" />
                </button>
              )}

              

              {row.isEditing ? (

                <button
                    className="btn-icon-action cancel"
                    onClick={() => handleCancelEdit(row.id)}
                    title="Batalkan Edit"
                >
                    <XCircle size={22} weight="fill" />
                </button>

            ) : (

                <button
                    className="btn-icon-action delete"
                    onClick={() => handleDeleteRow(row.id)}
                    title="Hapus Baris"
                >
                    <Trash size={22} weight="bold" />
                </button>

            )}
              
              

              
            </div>
          </div>
        ))}

        <div className="dynamic-table-footer">
          <Button variant="primary" icon={Plus} onClick={handleAddRow}>
            Tambah Data
          </Button>
        </div>
      </div>

        {loadingGenerate && (

      <div className="loading-overlay">

          <div className="loading-box">

              <div className="loading-spinner"></div>

              <h3>Menyiapkan Generate Jadwal...</h3>

              <p>Mohon tunggu sebentar.</p>

          </div>
      </div>
      )}

      {showGenerateModal && (

<div className="generate-modal-overlay">

    <div className="generate-modal">

        <h3>Lanjut ke Generate Jadwal</h3>

        <p>
            Pastikan seluruh data mata kuliah, dosen, dan ruangan 
            telah tersimpan sebelum melanjutkan ke proses generate jadwal.  
        </p>

        <div className="generate-modal-action">

            <Button
                variant="secondary"
                onClick={() => setShowGenerateModal(false)}
            >
                Batal
            </Button>

            <Button
              variant="primary"
              onClick={()=>{
              setShowGenerateModal(false);

              localStorage.setItem(
                "input_ready",
                "true"
              );

              onNavigate("generate");

              }}
              >
              Lanjut
            </Button>

        </div>

    </div>

  </div>

)}
    </DashboardLayout>
  );
};

export default InputManualPage;