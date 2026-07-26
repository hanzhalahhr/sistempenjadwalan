import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Button from '../components/Button';
import Badge from '../components/Badge';
import { Eye, DownloadSimple } from '@phosphor-icons/react';
import '../styles/Dashboard.css';

const JadwalPage = ({ onNavigate }) => {
  const dataJadwal = [
    { id: 1, prodi: "Semua Prodi" },
    { id: 2, prodi: "S1 Informatika" },
    { id: 3, prodi: "S1 Sistem Informasi" },
    { id: 4, prodi: "S1 Sains Data" },
    { id: 5, prodi: "D3 Sistem Informasi" },
  ];

  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="jadwal"
      pageTitle="Jadwal Perkuliahan"
      pageSubtitle="Semua Program Studi - Jadwal Fix"
    >
      <Table headers={["No", "Prodi", "Status", "Aksi"]}>
        {dataJadwal.map((item, i) => (
          <tr key={item.id}>
            <td>{i + 1}</td>
            <td>{item.prodi}</td>
            <td><Badge type="status" status="berhasil">Berhasil</Badge></td>
            <td>
              <div style={{ display: 'flex', justifyContent: 'center', gap: '8px' }}>
                <Button variant="table-icon eye" icon={Eye} title="Lihat Jadwal" />
                <Button variant="table-icon download" icon={DownloadSimple} title="Unduh Jadwal" />
              </div>
            </td>
          </tr>
        ))}
      </Table>
    </DashboardLayout>
  );
};

export default JadwalPage;