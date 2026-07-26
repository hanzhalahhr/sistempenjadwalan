import React, { useState, useEffect } from 'react';
import axios from 'axios';
import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';
import { Trash, Plus, ArrowRight, NotePencil, CheckCircle, XCircle, } from '@phosphor-icons/react';
import '../styles/InputManual.css';

const InputManualPage = ({ onNavigate }) => {
  const [activeTab, setActiveTab] = useState('mk'); 
  const [mataKuliah, setMataKuliah] = useState([]);
  const [originalRow, setOriginalRow] = useState(null);
  const [prodi,setProdi] = useState([]);

  const [tableData, setTableData] = useState({
    mk: [
      {
        id: Date.now(),
        col1:'',
        col2:'',
        col3:'',
        isEditing:true,
        isNew:true
      }
    ],
    dosen: [],
    ruang: [
      {
        id: Date.now(),
        col1:'',
        col2:'',
        col3:'',
        isEditing:true,
        isNew:true
      }
    ]
  });

  
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

        col1:item.nama_mk,

        col2:item.kelas_perkuliahan && item.kelas_perkuliahan.length > 0 && item.kelas_perkuliahan[0].prodi? 
        item.kelas_perkuliahan[0].prodi.nama_prodi:'',

        col3:item.sks,

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

  },[]);


  useEffect(() => {

    getDataMataKuliah();

  }, []);



  const tabConfig = {
    mk: {
      subtitle: "Tambahkan data mata kuliah secara manual",
      gridTemplate: "2fr 2fr 1fr 100px", 
      headers: ["Nama Mata Kuliah", "Program Studi", "SKS", "Aksi"],
      inputs: [
        { key: 'col1', type: 'text', placeholder: 'Masukkan Nama MK' },
        { key: 'col2', type: 'select', options: ['Pilih Program Studi', ...prodi.map(item=>item.nama_prodi)]},
        { key: 'col3', type: 'text', placeholder: 'SKS' },
      ]
    },





    
    dosen: {
      subtitle: "Tambahkan data dosen secara manual",
      gridTemplate:"180px 300px 300px 180px 100px",
      headers: ["NIDN", "Nama Dosen", "Pengampu MK", "Jabatan", "Aksi"],
      inputs: [
        { key:'col1', type:'text', placeholder:'Masukkan NIDN' },
        { key:'col2', type:'text', placeholder:'Masukkan Nama Dosen' },
        { key:'col3', type:'select', options:['Pilih Mata Kuliah',...mataKuliah.map(item => item.nama_mk)] },
        { key:'col4', type:'select', options:['', 'Lektor', 'Asisten Ahli', 'S2', 'S3', 'Guru Besar'] }
      ]
    },
    ruang: {
      subtitle: "Tambahkan data ruang secara manual",
      gridTemplate: "2fr 2fr 1fr 100px",
      headers: ["Nama Ruang", "Nama Gedung", "Kapasitas", "Aksi"],
      inputs: [
        { key: 'col1', type: 'text', placeholder: 'Contoh: FIK-202' },
        { key: 'col2', type: 'text', placeholder: 'Nama Gedung' },
        { key: 'col3', type: 'text', placeholder: 'Kapasitas' },
      ]
    }
  };

  const currentConfig = tabConfig[activeTab];
  const currentData = tableData[activeTab];

  const hasEditingRows = Object.values(tableData).flat().some(row => row.isEditing);

  const handleTopAction = () => {
    if (hasEditingRows) {
      const savedData = {};
      for (const key in tableData) {
        savedData[key] = tableData[key].map(row => ({ ...row, isEditing: false }));
      }
      setTableData(savedData);
    } else {
      onNavigate('proses-generate');
    }
  };

  // Handler untuk menambah baris kosong dan masuk mode edit
  const handleAddRow = () => {
    const newRow = { id: Date.now(), col1:'', col2:'', col3:'', col4:'', mata_kuliah_ids:[], isEditing:true, isNew:true };
    setTableData(prev => ({
      ...prev,
      [activeTab]: [...prev[activeTab], newRow]
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

    try {

      await axios.delete(
        `http://127.0.0.1:8000/api/dosen/${idToRemove}`
      );

      alert("Data berhasil dihapus");

      // Ambil ulang data dari database
      getDataDosen();

    } catch (error) {

      console.log(error);

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

        // khusus dropdown mata kuliah
        if(fieldKey === "col3"){

          const selectedMK = mataKuliah.find(
            mk => mk.nama_mk === value
          );

          console.log("MK DIPILIH:", selectedMK);

          return {
            ...row,

            col3:value,

            mata_kuliah_ids: selectedMK
              ? [selectedMK.id]
              : []
          };
        }

        return {
          ...row,
          [fieldKey]: value
        };

      })
    }));

  };




  const handleSaveRow = async (row) => {

    console.log("DATA ROW:", JSON.stringify(row,null,2));

    const yakin = window.confirm(
    "Apakah Anda yakin ingin menyimpan data ini?"
    );

    if (!yakin) {
      return;
    }

    try {

  if (row.isNew) {
    // ============================
    // SIMPAN DATA MATA KULIAH
    // ============================
    if(activeTab === "mk") {

      await axios.post(
        "http://127.0.0.1:8000/api/matakuliah",
        {
          nama_mk: row.col1,
          program_studi: row.col2,
          sks: row.col3,
          kode_mk: "AUTO-"+ Date.now(),
          semester: 1
        }
      );

    }
    // ============================
    // SIMPAN DATA DOSEN
    // ============================
    else if(activeTab === "dosen") {

      await axios.post(
        "http://127.0.0.1:8000/api/dosen",
        {
          nidn: row.col1,
          nama_dosen: row.col2,
          jabatan: row.col4,
          mata_kuliah_ids: row.mata_kuliah_ids
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
          nidn: row.col1,
          nama_dosen: row.col2,
          jabatan: row.col4,
          mata_kuliah_ids: row.mata_kuliah_ids
        }
      );

    }

    else if(activeTab === "mk"){

      await axios.put(
        `http://127.0.0.1:8000/api/mata-kuliah/${row.id}`,
        {
          nama_mk: row.col1,
          sks: row.col3
        }
      );

    }

    alert("Data berhasil diperbarui");

  }


    if(activeTab === "dosen"){getDataDosen(); }

    if(activeTab === "mk"){

      getDataMataKuliah();

    }

    }
    catch (error) {

      console.log(error); alert("Gagal menyimpan data");
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
      {hasEditingRows ? 'Simpan Data' : 'Mulai Generate'}
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
        <button className={`tab-btn ${activeTab === 'ruang' ? 'active' : ''}`} onClick={() => setActiveTab('ruang')}>
          Data Ruang
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
                      value={row[inputDef.key]}
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
                    {row[inputDef.key] || <span style={{color: '#cbd5e1'}}>Tidak ada data</span>}
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
            Tambah Baris
          </Button>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default InputManualPage;