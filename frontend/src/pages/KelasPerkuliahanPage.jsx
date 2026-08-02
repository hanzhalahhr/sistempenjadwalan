import DashboardLayout from "../components/DashboardLayout";

const KelasPerkuliahanPage = ({ onNavigate }) => {

    return (

        <DashboardLayout
            onNavigate={onNavigate}
            currentPage="kelas"
            pageTitle="Data Kelas Perkuliahan"
            pageSubtitle="Kelola kelas setiap mata kuliah"
        >

            <h2>Halaman Kelas Perkuliahan</h2>

        </DashboardLayout>

    );

};

export default KelasPerkuliahanPage;