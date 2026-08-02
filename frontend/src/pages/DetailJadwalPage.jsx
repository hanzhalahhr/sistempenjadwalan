import React, { useEffect, useState } from 'react';

import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import axios from 'axios';


const DetailJadwalPage = ({ onNavigate, prodiId }) => {


  const [jadwal, setJadwal] = useState([]);

  const [generate, setGenerate] = useState(null);



  useEffect(() => {


    axios.get(
      `http://127.0.0.1:8000/api/jadwal/prodi/${prodiId}`
    )

    .then(response => {


      console.log(
        response.data
      );


      setGenerate(
        response.data.generate
      );


      setJadwal(
        response.data.jadwal
      );


    })


    .catch(error => {

      console.log(
        error
      );

    });


  }, [prodiId]);



  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="jadwal"

      pageTitle="Detail Jadwal"

      pageSubtitle="Detail Jadwal Perkuliahan"

    >


      {generate && (

        <div>

          <h3>
            Generate : {generate.kode_generate}
          </h3>


          <p>
            Tanggal : {generate.tanggal}
          </p>


        </div>

      )}




      <Table

        headers={[
          "No",
          "Hari",
          "Jam",
          "Mata Kuliah",
          "Dosen",
          "Ruangan"
        ]}

      >


        {jadwal.map((item,index)=>(


          <tr key={item.id}>


            <td>
              {index+1}
            </td>


            <td>
              {item.hari}
            </td>


            <td>
              {item.jam_mulai}
              -
              {item.jam_selesai}
            </td>


            <td>
              {item.mata_kuliah}
            </td>


            <td>

              {
                item.dosen.join(", ")
              }

            </td>


            <td>
              {item.ruangan}
            </td>


          </tr>


        ))}



      </Table>



    </DashboardLayout>

  );


};


export default DetailJadwalPage;