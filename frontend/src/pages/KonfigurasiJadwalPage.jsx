import React, {
  useEffect,
  useState
} from "react";

import DashboardLayout from "../components/DashboardLayout";
import Button from "../components/Button";

import {
  Plus,
  Trash,
  Pencil,
  CheckCircle
} from "@phosphor-icons/react";

import axios from "axios";

import "../styles/Dashboard.css";
import "../styles/KonfigurasiJadwal.css";


const API =
  "http://127.0.0.1:8000/api";


const KonfigurasiJadwalPage = ({
  onNavigate
}) => {

  const [
    semesterList,
    setSemesterList
  ] = useState([]);

  const [
    semesterId,
    setSemesterId
  ] = useState("");

  const [
    hari,
    setHari
  ] = useState([

    {
      nama: "Senin",
      aktif: true
    },

    {
      nama: "Selasa",
      aktif: true
    },

    {
      nama: "Rabu",
      aktif: true
    },

    {
      nama: "Kamis",
      aktif: true
    },

    {
      nama: "Jumat",
      aktif: true
    },

    {
      nama: "Sabtu",
      aktif: false
    }

  ]);

  const [
    slotJam,
    setSlotJam
  ] = useState([]);

  const [
    loading,
    setLoading
  ] = useState(true);

  const [
    savingConfig,
    setSavingConfig
  ] = useState(false);

  const [
    showSuccessPopup,
    setShowSuccessPopup
  ] = useState(false);

  const [
    error,
    setError
  ] = useState("");

  const [
    editingId,
    setEditingId
  ] = useState(null);


  /*
  |--------------------------------------------------------------------------
  | AMBIL SEMESTER
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    const loadSemester =
      async () => {

        try {

          const response =
            await axios.get(
              `${API}/semester-akademik`
            );


          const data =
            Array.isArray(
              response.data
            )
              ? response.data
              : Array.isArray(
                  response.data?.data
                )
                ? response.data.data
                : [];


          setSemesterList(
            data
          );


          if (
            data.length === 0
          ) {

            return;

          }


          /*
          |--------------------------------------------------------------------------
          | PRIORITAS SEMESTER
          |--------------------------------------------------------------------------
          */

          const savedSemesterId =
            Number(
              localStorage.getItem(
                "semester_akademik_id"
              )
            );


          let selected =
            data.find(
              item =>
                Number(item.id) ===
                savedSemesterId
            );


          if (!selected) {

            selected =
              data.find(
                item =>
                  Number(item.is_active) === 1
              ) ||
              data[0];

          }


          const selectedId =
            Number(
              selected.id
            );


          setSemesterId(
            selectedId
          );


          /*
          |--------------------------------------------------------------------------
          | SEMESTER BOLEH DISIMPAN
          |--------------------------------------------------------------------------
          */

          localStorage.setItem(
            "semester_akademik_id",
            String(selectedId)
          );

        }

        catch (err) {

          console.error(
            "Gagal mengambil semester:",
            err
          );


          setError(
            "Gagal mengambil data semester akademik."
          );

        }

      };


    loadSemester();

  }, []);


  /*
  |--------------------------------------------------------------------------
  | AMBIL SLOT + KONFIGURASI LAMA
  |--------------------------------------------------------------------------
  */

  useEffect(() => {

    if (
      !semesterId
    ) {

      return;

    }


    const loadKonfigurasi =
      async () => {

        try {

          setLoading(true);

          setError("");


          const [
            slotResponse,
            configResponse
          ] = await Promise.all([

            axios.get(
              `${API}/slot-waktu-kuliah`
            ),

            axios.get(
              `${API}/konfigurasi-jadwal/${semesterId}`
            )

          ]);


          /*
          |--------------------------------------------------------------------------
          | SLOT WAKTU
          |--------------------------------------------------------------------------
          */

          setSlotJam(

            Array.isArray(
              slotResponse.data
            )
              ? slotResponse.data
              : Array.isArray(
                  slotResponse.data?.data
                )
                ? slotResponse.data.data
                : []

          );


          /*
          |--------------------------------------------------------------------------
          | KONFIGURASI HARI
          |--------------------------------------------------------------------------
          */

          const configHari =
            Array.isArray(
              configResponse.data?.hari
            )
              ? configResponse.data.hari
              : [];


          const daftarHari = [

            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu"

          ];


          /*
          |--------------------------------------------------------------------------
          | JIKA DATABASE SUDAH PUNYA KONFIGURASI
          |--------------------------------------------------------------------------
          */

          if (
            configHari.length > 0
          ) {

            setHari(

              daftarHari.map(
                nama => {

                  const existing =
                    configHari.find(
                      item =>
                        item.hari === nama
                    );


                  return {

                    nama,

                    aktif:
                      existing
                        ? Boolean(
                            Number(
                              existing.is_active
                            )
                          )
                        : false

                  };

                }
              )

            );

          }

          else {

            /*
            |--------------------------------------------------------------------------
            | DEFAULT
            |--------------------------------------------------------------------------
            */

            setHari([

              {
                nama: "Senin",
                aktif: true
              },

              {
                nama: "Selasa",
                aktif: true
              },

              {
                nama: "Rabu",
                aktif: true
              },

              {
                nama: "Kamis",
                aktif: true
              },

              {
                nama: "Jumat",
                aktif: true
              },

              {
                nama: "Sabtu",
                aktif: false
              }

            ]);

          }

        }

        catch (err) {

          console.error(
            "Gagal mengambil konfigurasi:",
            err
          );


          setError(
            "Gagal mengambil data konfigurasi jadwal."
          );

        }

        finally {

          setLoading(false);

        }

      };


    loadKonfigurasi();

  }, [semesterId]);


  /*
  |--------------------------------------------------------------------------
  | GANTI SEMESTER
  |--------------------------------------------------------------------------
  */

  const handleSemesterChange =
    (e) => {

      const id =
        Number(
          e.target.value
        );


      setSemesterId(
        id
      );


      localStorage.setItem(
        "semester_akademik_id",
        String(id)
      );

    };


  /*
  |--------------------------------------------------------------------------
  | TOGGLE HARI
  |--------------------------------------------------------------------------
  */

  const toggleHari =
    (index) => {

      setHari(prev => {

        const data = [
          ...prev
        ];


        data[index] = {

          ...data[index],

          aktif:
            !data[index].aktif

        };


        return data;

      });

    };


  /*
  |--------------------------------------------------------------------------
  | TAMBAH SLOT
  |--------------------------------------------------------------------------
  */

  const tambahSlot =
    () => {

      const newSlot = {

        id:
          `temp-${Date.now()}`,

        hari:
          "Senin",

        urutan:
          slotJam.length + 1,

        jam_mulai:
          "15:00",

        jam_selesai:
          "16:40"

      };


      setSlotJam(prev => [

        ...prev,

        newSlot

      ]);

    };


  /*
  |--------------------------------------------------------------------------
  | UPDATE SLOT
  |--------------------------------------------------------------------------
  */

  const handleSlotChange =
    (
      id,
      field,
      value
    ) => {

      setSlotJam(prev =>

        prev.map(item =>

          item.id === id

            ? {
                ...item,
                [field]: value
              }

            : item

        )

      );

    };


  /*
  |--------------------------------------------------------------------------
  | SIMPAN SLOT
  |--------------------------------------------------------------------------
  */

  const simpanSlot =
    async (slot) => {

      try {

        if (
          slot.jam_mulai >=
          slot.jam_selesai
        ) {

          alert(
            "Jam selesai harus lebih besar dari jam mulai."
          );

          return;

        }


        const payload = {

          hari:
            slot.hari,

          urutan:
            Number(
              slot.urutan
            ),

          jam_mulai:
            slot.jam_mulai.substring(
              0,
              5
            ),

          jam_selesai:
            slot.jam_selesai.substring(
              0,
              5
            )

        };


        /*
        |--------------------------------------------------------------------------
        | SLOT BARU
        |--------------------------------------------------------------------------
        */

        if (
          typeof slot.id === "string" &&
          slot.id.startsWith("temp-")
        ) {

          const response =
            await axios.post(
              `${API}/slot-waktu-kuliah`,
              payload
            );


          setSlotJam(prev =>

            prev.map(item =>

              item.id === slot.id

                ? response.data.data

                : item

            )

          );

        }

        else {

          /*
          |--------------------------------------------------------------------------
          | SLOT LAMA
          |--------------------------------------------------------------------------
          */

          const response =
            await axios.put(
              `${API}/slot-waktu-kuliah/${slot.id}`,
              payload
            );


          setSlotJam(prev =>

            prev.map(item =>

              item.id === slot.id

                ? response.data.data

                : item

            )

          );

        }


        setEditingId(
          null
        );

      }

      catch (err) {

        console.error(
          "Gagal menyimpan slot:",
          err
        );


        alert(
          err.response?.data?.message ||
          "Gagal menyimpan slot waktu."
        );

      }

    };


  /*
  |--------------------------------------------------------------------------
  | HAPUS SLOT
  |--------------------------------------------------------------------------
  */

  const hapusSlot =
    async (slot) => {

      const yakin =
        window.confirm(
          "Yakin ingin menghapus slot waktu ini?"
        );


      if (!yakin) {

        return;

      }


      try {

        if (
          typeof slot.id === "string" &&
          slot.id.startsWith("temp-")
        ) {

          setSlotJam(prev =>

            prev.filter(
              item =>
                item.id !== slot.id
            )

          );

          return;

        }


        await axios.delete(
          `${API}/slot-waktu-kuliah/${slot.id}`
        );


        setSlotJam(prev =>

          prev.filter(
            item =>
              item.id !== slot.id
          )

        );

      }

      catch (err) {

        console.error(
          "Gagal menghapus slot:",
          err
        );


        alert(
          err.response?.data?.message ||
          "Gagal menghapus slot."
        );

      }

    };


  /*
  |--------------------------------------------------------------------------
  | SIMPAN KONFIGURASI
  |--------------------------------------------------------------------------
  */

  const handleSave =
    async () => {

      if (!semesterId) {

        alert(
          "Semester akademik belum dipilih."
        );

        return;

      }


      const adaHariAktif =
        hari.some(
          item =>
            item.aktif === true
        );


      if (!adaHariAktif) {

        alert(
          "Minimal pilih satu hari kuliah."
        );

        return;

      }


      try {

        setSavingConfig(
          true
        );

        setError("");


        const payload = {

          semester_akademik_id:
            Number(
              semesterId
            ),

          hari:
            hari.map(
              item => ({

                nama:
                  String(
                    item.nama
                  ),

                aktif:
                  Boolean(
                    item.aktif
                  )

              })
            )

        };


        console.log(
          "KONFIGURASI DIKIRIM:",
          JSON.stringify(
            payload,
            null,
            2
          )
        );


        const response =
          await axios.post(

            `${API}/konfigurasi-jadwal`,

            payload,

            {
              headers: {

                "Content-Type":
                  "application/json",

                "Accept":
                  "application/json"

              }

            }

          );


        console.log(
          "KONFIGURASI BERHASIL:",
          response.data
        );


        /*
        |--------------------------------------------------------------------------
        | CEK RESPONSE BACKEND
        |--------------------------------------------------------------------------
        */

        const configReady =
          response.data?.data?.config_ready === true;


        if (!configReady) {

          setError(
            "Konfigurasi berhasil disimpan, tetapi belum memenuhi syarat untuk generate."
          );

          /*
          |----------------------------------------------------------------------
          | JANGAN SET SESSION TRUE
          |----------------------------------------------------------------------
          */

          localStorage.setItem(
            "konfigurasi_jadwal_session",
            "false"
          );

          return;

        }


        /*
        |--------------------------------------------------------------------------
        | SEMESTER TETAP DISIMPAN
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
          "semester_akademik_id",
          String(
            semesterId
          )
        );


        /*
        |--------------------------------------------------------------------------
        | INI BAGIAN PALING PENTING
        |--------------------------------------------------------------------------
        |
        | Konfigurasi berhasil.
        |
        | Maka SESSION SAAT INI dianggap sudah melakukan konfigurasi.
        |
        */

        localStorage.setItem(
          "konfigurasi_jadwal_session",
          "true"
        );


        console.log(
          "KONFIGURASI SESSION = TRUE"
        );


        /*
        |--------------------------------------------------------------------------
        | POPUP BERHASIL
        |--------------------------------------------------------------------------
        */

        setShowSuccessPopup(
          true
        );

      }

      catch (err) {

        console.error(
          "Gagal menyimpan konfigurasi:",
          err
        );


        console.error(
          "Response Laravel:",
          err.response?.data
        );


        /*
        |--------------------------------------------------------------------------
        | JIKA GAGAL
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
          "konfigurasi_jadwal_session",
          "false"
        );


        setError(

          err.response?.data?.message ||

          "Gagal menyimpan konfigurasi jadwal."

        );

      }

      finally {

        setSavingConfig(
          false
        );

      }

    };


  /*
  |--------------------------------------------------------------------------
  | FORMAT JAM
  |--------------------------------------------------------------------------
  */

  const formatJam =
    (value) => {

      if (!value) {

        return "";

      }


      return value.substring(
        0,
        5
      );

    };


  /*
  |--------------------------------------------------------------------------
  | RENDER
  |--------------------------------------------------------------------------
  */

  return (

    <DashboardLayout

      onNavigate={onNavigate}

      currentPage="generate"

      pageTitle="Konfigurasi Jadwal"

      pageSubtitle="Atur parameter sebelum proses generate jadwal"

    >

      <div
        style={{
          padding: "25px"
        }}
      >


        {/* ==========================================================
            ERROR
        ========================================================== */}

        {error && (

          <div
            className="jadwal-detail-error"
          >

            {error}

          </div>

        )}


        {/* ==========================================================
            PERIODE AKADEMIK
        ========================================================== */}

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

                value={
                  semesterId
                }

                onChange={
                  handleSemesterChange
                }

                disabled={
                  semesterList.length === 0
                }

              >

                {semesterList.length === 0 ? (

                  <option value="">
                    Memuat...
                  </option>

                ) : (

                  semesterList.map(
                    item => (

                      <option
                        key={item.id}
                        value={item.id}
                      >

                        {item.periode}

                      </option>

                    )
                  )

                )}

              </select>

            </div>


            <div>

              <label>
                Tahun Akademik
              </label>


              <select

                value={
                  semesterId
                }

                onChange={
                  handleSemesterChange
                }

                disabled={
                  semesterList.length === 0
                }

              >

                {semesterList.length === 0 ? (

                  <option value="">
                    Memuat...
                  </option>

                ) : (

                  semesterList.map(
                    item => (

                      <option
                        key={item.id}
                        value={item.id}
                      >

                        {item.tahun_akademik}
                        {" - "}
                        {item.periode}

                      </option>

                    )
                  )

                )}

              </select>

            </div>

          </div>

        </div>


        {/* ==========================================================
            HARI KULIAH
        ========================================================== */}

        <div className="config-card">

          <h3>
            Hari Kuliah
          </h3>


          <div className="hari-container">

            {hari.map(
              (item, index) => (

                <label
                  key={item.nama}
                  className="hari-item"
                >

                  <input

                    type="checkbox"

                    checked={
                      item.aktif
                    }

                    onChange={() =>
                      toggleHari(
                        index
                      )
                    }

                  />

                  {item.nama}

                </label>

              )
            )}

          </div>

        </div>


        {/* ==========================================================
            SLOT JAM
        ========================================================== */}

        <div className="config-card">

          <div className="card-header">

            <h3>
              Slot Jam Kuliah
            </h3>


            <Button
              onClick={
                tambahSlot
              }
            >

              <Plus
                size={18}
              />

              Tambah Slot

            </Button>

          </div>


          {loading ? (

            <div
              style={{
                textAlign: "center",
                padding: "30px"
              }}
            >

              <div
                className="spinner"
              ></div>


              <p>
                Memuat slot waktu...
              </p>

            </div>

          ) : (

            <table
              className="config-table"
            >

              <thead>

                <tr>

                  <th>
                    No
                  </th>

                  <th>
                    Hari
                  </th>

                  <th>
                    Jam Mulai
                  </th>

                  <th>
                    Jam Selesai
                  </th>

                  <th>
                    Aksi
                  </th>

                </tr>

              </thead>


              <tbody>

                {slotJam.map(
                  (item, index) => {

                    const sedangEdit =
                      editingId ===
                      item.id;


                    return (

                      <tr
                        key={
                          item.id
                        }
                      >

                        <td>
                          {index + 1}
                        </td>


                        <td>

                          {sedangEdit ? (

                            <select

                              className="time-input"

                              value={
                                item.hari
                              }

                              onChange={(e) =>
                                handleSlotChange(
                                  item.id,
                                  "hari",
                                  e.target.value
                                )
                              }

                            >

                              <option value="Senin">
                                Senin
                              </option>

                              <option value="Selasa">
                                Selasa
                              </option>

                              <option value="Rabu">
                                Rabu
                              </option>

                              <option value="Kamis">
                                Kamis
                              </option>

                              <option value="Jumat">
                                Jumat
                              </option>

                              <option value="Sabtu">
                                Sabtu
                              </option>

                            </select>

                          ) : (

                            item.hari

                          )}

                        </td>


                        <td>

                          {sedangEdit ? (

                            <input

                              type="time"

                              className="time-input"

                              value={
                                formatJam(
                                  item.jam_mulai
                                )
                              }

                              onChange={(e) =>
                                handleSlotChange(
                                  item.id,
                                  "jam_mulai",
                                  e.target.value
                                )
                              }

                            />

                          ) : (

                            formatJam(
                              item.jam_mulai
                            )

                          )}

                        </td>


                        <td>

                          {sedangEdit ? (

                            <input

                              type="time"

                              className="time-input"

                              value={
                                formatJam(
                                  item.jam_selesai
                                )
                              }

                              onChange={(e) =>
                                handleSlotChange(
                                  item.id,
                                  "jam_selesai",
                                  e.target.value
                                )
                              }

                            />

                          ) : (

                            formatJam(
                              item.jam_selesai
                            )

                          )}

                        </td>


                        <td
                          className="action-column"
                        >

                          <div
                            className="action-buttons"
                          >

                            {sedangEdit ? (

                              <button

                                className="edit-btn"

                                title="Simpan"

                                onClick={() =>
                                  simpanSlot(
                                    item
                                  )
                                }

                              >

                                <CheckCircle
                                  size={17}
                                />

                              </button>

                            ) : (

                              <button

                                className="edit-btn"

                                title="Edit"

                                onClick={() =>
                                  setEditingId(
                                    item.id
                                  )
                                }

                              >

                                <Pencil
                                  size={15}
                                />

                              </button>

                            )}


                            <button

                              className="delete-btn"

                              title="Hapus"

                              onClick={() =>
                                hapusSlot(
                                  item
                                )
                              }

                            >

                              <Trash
                                size={15}
                              />

                            </button>

                          </div>

                        </td>

                      </tr>

                    );

                  }
                )}

              </tbody>

            </table>

          )}

        </div>


        {/* ==========================================================
            SIMPAN KONFIGURASI
        ========================================================== */}

        <Button

          onClick={
            handleSave
          }

          disabled={
            savingConfig
          }

        >

          {savingConfig

            ? "Menyimpan..."

            : "Simpan Konfigurasi"

          }

        </Button>


      </div>


      {/* ==========================================================
          POPUP BERHASIL
      ========================================================== */}

      {showSuccessPopup && (

        <div
          className="popup-overlay"
        >

          <div
            className="popup-card"
          >

            <div
              className="popup-icon"
            >

              <CheckCircle
                size={50}
                weight="fill"
              />

            </div>


            <h2>
              Berhasil
            </h2>


            <p>

              Konfigurasi jadwal berhasil
              disimpan.

              <br />

              Sekarang Anda dapat melakukan
              proses generate jadwal.

            </p>


            <div
              className="popup-button-group"
            >

              <Button

                style={{
                  background: "#e5e7eb",
                  color: "#111827"
                }}

                onClick={() =>
                  setShowSuccessPopup(
                    false
                  )
                }

              >

                Tetap di Halaman

              </Button>


              <Button

                onClick={() =>
                  onNavigate(
                    "generate"
                  )
                }

              >

                Ke Generate Jadwal

              </Button>

            </div>

          </div>

        </div>

      )}

    </DashboardLayout>

  );

};


export default KonfigurasiJadwalPage;