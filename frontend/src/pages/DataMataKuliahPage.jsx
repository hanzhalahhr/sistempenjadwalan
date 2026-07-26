import React, { useState, useEffect } from 'react';
import axios from 'axios';

import DashboardLayout from '../components/DashboardLayout';


const DataMataKuliahPage = ({ onNavigate }) => {

    const [tableData, setTableData] = useState([]);

    const getDataMataKuliah = () => {

    useEffect(()=>{getDataMataKuliah();},[]);

    axios.get('http://127.0.0.1:8000/api/matakuliah')

  .then((response)=>{

    console.log(
      "DATA MATA KULIAH:",
      response.data
    );
  })

    .catch((error)=>{
        console.log(
        "Gagal mengambil data mata kuliah:",
        error
        );
    });
    };

};

export default DataMataKuliahPage;