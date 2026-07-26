import React from 'react';
import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Button from '../components/Button';
import Badge from '../components/Badge';
import { Eye, DownloadSimple } from '@phosphor-icons/react';
import '../styles/RiwayatGenerate.css';

const RiwayatGenerate = ({ onNavigate }) => {
  const riwayatData = [
    { id: 1, waktu: "3 Mei 2026, 10:45 WIB", status: "Gagal - Terdapat Bentrok" },
    { id: 2, waktu: "3 Mei 2026, 11:05 WIB", status: "Berhasil" },
  ];

  return (
    <DashboardLayout
      onNavigate={onNavigate}
      currentPage="riwayat-generate"
      pageTitle="Riwayat Generate Jadwal"
      pageSubtitle="Berikut adalah riwayat proses generate jadwal yang telah dilakukan"
    >
      <Table headers={["No", "Waktu Generate", "Status", "Aksi"]}>
        {riwayatData.map((item, index) => (
          <tr key={item.id}>
            <td>{index + 1}</td>
            <td>{item.waktu}</td>
            <td><Badge type="status" status={item.status}>{item.status}</Badge></td>
            <td>
              <div style={{ display: 'flex', justifyContent: 'center', gap: '8px' }}>
                <Button variant="table-icon eye" icon={Eye} title="Lihat Riwayat" />
                {item.status === "Berhasil" && (
                  <Button variant="table-icon download" icon={DownloadSimple} title="Unduh Hasil" />
                )}
              </div>
            </td>
          </tr>
        ))}
      </Table>
    </DashboardLayout>
  );
};

export default RiwayatGenerate;