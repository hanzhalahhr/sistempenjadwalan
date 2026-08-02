import React from 'react';

import DashboardLayout from '../components/DashboardLayout';
import Button from '../components/Button';

import {
  XCircle,
  ArrowCounterClockwise,
  ArrowLeft
} from '@phosphor-icons/react';

import '../styles/Dashboard.css';



const HasilGeneratePageGagal = ({onNavigate}) => {


return (

<DashboardLayout

onNavigate={onNavigate}

currentPage="generate"

pageTitle="Generate Jadwal - Gagal"

pageSubtitle="Hasil proses generate jadwal"



>


<div className="hasil-generate-card">


<div className="error-status-container">


<div className="icon-error-wrapper">


<XCircle

size={80}

weight="fill"

/>


</div>



<h2 className="status-title-error">

Generate Jadwal Gagal

</h2>



<p className="status-subtitle">

Sistem tidak berhasil membuat jadwal perkuliahan.

</p>



<div className="failed-info-box">


<h4>

Kemungkinan penyebab:

</h4>


<ul>

<li>
Terjadi bentrok jadwal dosen
</li>


<li>
Ruangan tidak mencukupi
</li>


<li>
Slot waktu kuliah kurang
</li>


<li>
Data input belum lengkap
</li>


</ul>


</div>



</div>


</div>




<div className="footer-action-area">


<Button

variant="secondary"

icon={ArrowLeft}

onClick={()=>onNavigate("generate")}

>

Kembali

</Button>




<Button

variant="primary"

icon={ArrowCounterClockwise}

onClick={()=>onNavigate("proses-generate")}

>

Generate Ulang

</Button>



</div>



</DashboardLayout>


);


};


export default HasilGeneratePageGagal;