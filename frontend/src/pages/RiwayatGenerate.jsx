import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Eye, FileArrowDown, FilePdf, Trash } from '@phosphor-icons/react';
import DashboardLayout from '../components/DashboardLayout';
import Table from '../components/Table';
import Badge from '../components/Badge';

import '../styles/RiwayatGenerate.css';

const RiwayatGenerate = ({ onNavigate }) => {

    const [riwayatData, setRiwayatData] = useState([]);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [selectedId, setSelectedId] = useState(null);

    useEffect(() => {

        axios
            .get("http://127.0.0.1:8000/api/generatejadwal")
            .then((response) => {

                console.log("FULL RESPONSE RIWAYAT:", response.data);

                console.log("DATA RIWAYAT:",response.data.data);

                setRiwayatData(response.data.data);

            })
            .catch((error) => {

                console.log(error);

            });

    }, []);

    const openDeleteModal = (id) => {

        setSelectedId(id);
        setShowDeleteModal(true);

    };

    const closeDeleteModal = () => {

        setShowDeleteModal(false);
        setSelectedId(null);

    };

    const handleDelete = (id) => {

        axios
            .delete(`http://127.0.0.1:8000/api/generatejadwal/${id}`)

            .then(() => {

                closeDeleteModal();

                axios
                .get("http://127.0.0.1:8000/api/generatejadwal")
                .then(response => {

                    setRiwayatData(response.data.data);

                });

            })

            .catch((error) => {

                console.log(error);

                closeDeleteModal();

                alert(
                    "Riwayat generate jadwal gagal dihapus. Silakan coba kembali dalam beberapa saat."
                );

            });

    };

    return (

        <DashboardLayout
            onNavigate={onNavigate}
            currentPage="riwayat-generate"
            pageTitle="Riwayat Generate Jadwal"
            pageSubtitle="Riwayat proses generate jadwal perkuliahan"
        >

            <Table
                headers={[
                "No",
                "Kode Generate",
                "Periode Akademik",
                "Waktu Generate",
                "Status",
                "Aksi"
            ]}
            >

                {riwayatData.map((item, index) => (

                    <tr key={item.id}>

                        <td>{index + 1}</td>

                        <td>
                            {item.kode_generate}
                        </td>

                        <td>
                            Semester {item.semester_akademik_id}
                        </td>

                        <td>
                        {new Date(item.tanggal_generate).toLocaleDateString("id-ID", {
                            day: "numeric",
                            month: "long",
                            year: "numeric"
                        })}

                        <br />

                        <div className="waktu-generate">
                            Pukul{" "}
                            {new Date(item.tanggal_generate).toLocaleTimeString("id-ID", {
                                hour: "2-digit",
                                minute: "2-digit"
                            })} WIB
                        </div>

                    </td>

                        <td className="status-cell">
                            <Badge
                                type="status"
                                status={item.status}
                            >
                                {item.status}
                            </Badge>
                        </td>

                        <td className="action-cell">

                            <div className="aksi-container">

                                <button
                                    className="view-button"
                                    title="Lihat Hasil Generate"
                                    onClick={() => {

                                    console.log(
                                        "BUKA GENERATE:",
                                        item.id
                                    );


                                    localStorage.setItem(
                                        "generate_id",
                                        item.id.toString()
                                    );


                                    onNavigate(
                                        "hasil-generate"
                                    );

                                }}
                                >
                                    <Eye size={18} weight="bold" />
                                </button>

                                <button
                                    className="excel-button"
                                    title="Download Excel"
                                    onClick={() => {

                                        window.open(
                                            `http://127.0.0.1:8000/api/jadwal/export/${item.id}`,
                                            "_blank"
                                        );

                                    }}
                                >
                                    <FileArrowDown
                                        size={18}
                                        weight="bold"
                                    />
                                </button>

                                <button
                                    className="pdf-button"
                                    title="Download PDF"
                                    onClick={() => {

                                        window.open(
                                            `http://127.0.0.1:8000/api/jadwal/pdf/${item.id}`,
                                            "_blank"
                                        );

                                    }}
                                >
                                    <FilePdf
                                        size={18}
                                        weight="bold"
                                    />
                                </button>

                                <button
                                    className="delete-button"
                                    title="Hapus Riwayat"
                                    onClick={() => openDeleteModal(item.id)}
                                >
                                    <Trash
                                        size={18}
                                        weight="bold"
                                    />
                                </button>

                            </div>

                        </td>

                    </tr>

                ))}

            </Table>

            {showDeleteModal && (

                <div className="modal-overlay">

                    <div className="delete-modal">

                        <h2>
                            Hapus Riwayat Generate
                        </h2>

                        <p>
                            Apakah Anda yakin ingin menghapus
                            riwayat generate jadwal ini?
                        </p>

                        <p className="warning-text">
                            Seluruh data hasil generate yang terkait
                            akan dihapus secara permanen dan tidak
                            dapat dikembalikan.
                        </p>

                        <div className="modal-button">

                            <button
                                className="btn-cancel"
                                onClick={closeDeleteModal}
                            >
                                Batal
                            </button>

                            <button
                                className="btn-delete"
                                onClick={() => handleDelete(selectedId)}
                            >
                                Ya, Hapus
                            </button>

                        </div>

                    </div>

                </div>

            )}

        </DashboardLayout>

    );

};

export default RiwayatGenerate;