<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->get(thisGet(), function() { return "";});
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);
// ROUTES FOR INFOGRAFIS
$routes->post('/informasi_infografis/dashboard_kedatangan_kapal/rekapitulasi', 'Infografis::dashboard_kedatangan_kapal_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_kedatangan_kapal/per_wilker', 'Infografis::dashboard_kedatangan_kapal_per_wilker');
$routes->post('/informasi_infografis/dashboard_kedatangan_kapal/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_kedatangan_kapal_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_kedatangan_kapal/hari_kemarin', 'Infografis::dashboard_kedatangan_kapal_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_keberangkatan_kapal/rekapitulasi', 'Infografis::dashboard_keberangkatan_kapal_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_keberangkatan_kapal/per_wilker', 'Infografis::dashboard_keberangkatan_kapal_per_wilker');
$routes->post('/informasi_infografis/dashboard_keberangkatan_kapal/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_keberangkatan_kapal_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_keberangkatan_kapal/hari_kemarin', 'Infografis::dashboard_keberangkatan_kapal_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_kedatangan_pesawat/rekapitulasi', 'Infografis::dashboard_kedatangan_pesawat_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_kedatangan_pesawat/per_wilker', 'Infografis::dashboard_kedatangan_pesawat_per_wilker');
$routes->post('/informasi_infografis/dashboard_kedatangan_pesawat/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_kedatangan_pesawat_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_kedatangan_pesawat/hari_kemarin', 'Infografis::dashboard_kedatangan_pesawat_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_keberangkatan_pesawat/rekapitulasi', 'Infografis::dashboard_keberangkatan_pesawat_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_keberangkatan_pesawat/per_wilker', 'Infografis::dashboard_keberangkatan_pesawat_per_wilker');
$routes->post('/informasi_infografis/dashboard_keberangkatan_pesawat/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_keberangkatan_pesawat_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_keberangkatan_pesawat/hari_kemarin', 'Infografis::dashboard_keberangkatan_pesawat_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_dokumen/rekapitulasi', 'Infografis::dashboard_dokumen_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_dokumen/per_wilker', 'Infografis::dashboard_dokumen_per_wilker');
$routes->post('/informasi_infografis/dashboard_dokumen/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_dokumen_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_dokumen/hari_kemarin', 'Infografis::dashboard_dokumen_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_sertifikat/rekapitulasi', 'Infografis::dashboard_sertifikat_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_sertifikat/per_wilker', 'Infografis::dashboard_sertifikat_per_wilker');
$routes->post('/informasi_infografis/dashboard_sertifikat/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_sertifikat_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_sertifikat/hari_kemarin', 'Infografis::dashboard_sertifikat_hari_kemarin');

$routes->post('/informasi_infografis/dashboard_vaksinasi/rekapitulasi', 'Infografis::dashboard_vaksinasi_rekapitulasi');
$routes->post('/informasi_infografis/dashboard_vaksinasi/per_wilker', 'Infografis::dashboard_vaksinasi_per_wilker');
$routes->post('/informasi_infografis/dashboard_vaksinasi/bulan_ini_dan_sebelumnya', 'Infografis::dashboard_vaksinasi_bulan_ini_dan_sebelumnya');
$routes->post('/informasi_infografis/dashboard_vaksinasi/hari_kemarin', 'Infografis::dashboard_vaksinasi_hari_kemarin');

$routes->get('/monitoring/kendaraan', 'KendaraanKantor\Data::monitoring_kendaraan');
$routes->post('/monitoring/kendaraan_kantor_get_riwayat', 'KendaraanKantor\Ajax::monitoring_get_riwayat');

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/home/get/tutorialGantiPasswordModal', 'Home::tutorialGantiPasswordModal');
$routes->get('/home/viewProfile/(:num)', 'Home::viewProfile/$1');
$routes->post('/home/simpanCatatanPribadi', 'Home::simpanCatatanPribadi');

$routes->get('/forbidden', 'Home::forbidden');
$routes->get('/absen/zona_absen', 'Absen\Data::zonaAbsen');

// Motivasi Absen CRUD
$routes->get('/absen/motivasi/get', 'Absen\Crud::getMotivasi');
$routes->post('/absen/motivasi/save', 'Absen\Crud::saveMotivasi');
$routes->post('/absen/motivasi/delete', 'Absen\Crud::deleteMotivasi');
$routes->get('/maintenance', 'Home::maintenance/May-04, 2025-14:10:00');
if(session()->get('role') != "Admin") {
    // $routes->get('/sinadi/notaDinas', 'Home::maintenance/May-27, 2025-10:10:00');
    // $routes->get('/sinadi/verifikasiSurat', 'Home::maintenance/May-09, 2025-15:10:00');
}
$routes->post('/sinadi/hapusNotaDinas', 'Sinadi\Crud::hapusNotaDinas');
$routes->get('tes', function() {
    return view('tes');
});
$routes->post('/send/email', 'Ajax::testSendMail');
$routes->get('/format_angka/(:num)', 'Home::kategori_angka/$1');
$routes->get('/auth/login', 'Auth::view_login');
$routes->get('/auth/login-v2', 'Auth::view_login_v2');
$routes->get('/', 'Auth::index');
$routes->post('/', 'Auth::newIndex');
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/v2', 'Auth::login_v2');
$routes->post('/auth', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/home', 'Home::index');
$routes->get('/home/ekahadiandila', 'Home::linimasa');
$routes->get('/home/history/(:segment)', 'Home::linimasa/$1');
$routes->get('/home/dashboard', 'Home::dashboard');
$routes->get('/home/infografis', 'Home::dashboard');
$routes->get('/home/infografis/(:segment)/(:segment)/(:segment)', 'Home::getInfografis/$1/$2/$3');
$routes->get('/home/sipedit/(:segment)/(:segment)', 'Home::getSipedit/$1/$2');
$routes->get('/home/sipepes', 'Home::getSipepes');
$routes->get('/home/sicute', 'Home::getSicute');
$routes->get('/home/sinadi', 'Home::getSinadi');
$routes->post('/home/getSinadi', 'Home::getDataSinadi');
$routes->get('/home/getListDataCuti/(:num)', 'Home::getListDataCuti/$1');
$routes->get('/home/relaxtime', 'Home::getRelaxtime');
$routes->get('/home/relaxtime/getRealisasiBulanan/(:num)', 'Home::getRelaxtimeDetail/$1');
$routes->get('/home/profil', 'Home::profile');
$routes->post('/home/ajax/read-notif/(:num)', 'Ajax::readNotif/$1');
$routes->get('/setLogout', 'Auth::setLogout');
$routes->get('/ajax/notifikasi', 'Ajax::getNotifikasi');
$routes->post('/ajax/feedback', 'Ajax::feedback');
$routes->get('/home/ajax/feedback-detail/(:num)', 'Ajax::feedbackDetail/$1');

// User Feedback Routes
$routes->get('/home/feedback', 'Home::feedback');

// Admin Feedback Management Routes
$routes->get('/admin/feedback', 'Admin\Feedback::index');
$routes->get('/admin/ajax/feedback-stats', 'Admin\Feedback::getFeedbackStats');
$routes->get('/admin/ajax/feedback-list', 'Admin\Feedback::getFeedbackList');
$routes->get('/admin/ajax/feedback-detail/(:num)', 'Admin\Feedback::getFeedbackDetail/$1');
$routes->post('/admin/ajax/feedback-update-status/(:num)', 'Admin\Feedback::updateFeedbackStatus/$1');
$routes->delete('/admin/ajax/feedback-delete/(:num)', 'Admin\Feedback::deleteFeedback/$1');
$routes->get('/admin/feedback/export', 'Admin\Feedback::exportFeedback');
$routes->post('/auth/update_profile_pegawai', 'Auth::updateProfilePegawai');
$routes->post('/auth/update_user', 'Auth::updateUser');
$routes->post('/auth/update_kontak', 'Auth::updateKontak');
$routes->post('/auth/update_photo', 'Auth::updatePhoto');

// Public Routes for UKLW Form
$routes->get('/public/uklw/landing', 'Public\UklwPublic::landing');
$routes->get('/public/uklw', 'Public\UklwPublic::index');
$routes->post('/public/uklw/submit', 'Public\UklwPublic::submitForm');
$routes->get('/public/uklw/success', 'Public\UklwPublic::success');

// Public Route for Form Layanan Kesehatan Kapal
$routes->get('/public/layanan_kesehatan_kapal', 'Public\LayananKesehatanKapalPublic::index');
$routes->post('/public/layanan_kesehatan_kapal/submit', 'Public\LayananKesehatanKapalPublic::submitForm');
$routes->get('/public/layanan_kesehatan_kapal/success', 'Public\LayananKesehatanKapalPublic::success');

$routes->get('/home/ajax/getUser', 'Ajax::getUser');
$routes->post('/home/ajax/openBoxEmail', 'Ajax::openBoxEmail');
$routes->post('/home/ajax/uploadFile', 'Ajax::autoUploadFile');
$routes->post('/home/ajax/removeFile', 'Ajax::autoRemoveFile');
$routes->post('/home/ajax/sendMessagesToEmail', 'Ajax::sendMessagesToEmail');

$routes->get('/home/agenda', 'Home::agenda');
$routes->get('/home/getCallendarAgenda', 'Ajax::getCallendarAgenda');
$routes->post('/home/ajax/agenda', 'Ajax::agenda');
$routes->get('/home/agenda/ajax/agenda_saya', 'Ajax::agenda_saya');
$routes->get('/home/agenda/ajax/get_agenda_saya/(:num)', 'Ajax::get_agenda_saya/$1');
$routes->delete('/home/dlt_agenda/(:num)', 'Ajax::delete_agenda/$1');
$routes->get('/home/ajax/getAgenda', 'Ajax::getAgenda/$1');
$routes->get('/home/new-fitur', 'Ajax::newFitur');
$routes->get('/manualBooks', 'Home::manualBooks');
$routes->post('/home/manualBooks', 'Home::tambahManualBooks');
$routes->post('/home/manualBooks/setPriority', 'Home::setPriority');

$routes->get('/admin', 'Admin\Admin::index');
$routes->get('/admin/user', 'Admin\Admin::user');
$routes->get('/admin/unit_kerja', 'Admin\Admin::unit_kerja');
$routes->get('/admin/pegawai', 'Admin\Admin::pegawai');
$routes->get('/admin/skema_titimangsa', 'Admin\Admin::skema_titimangsa');
$routes->get('/admin/jenis', 'Admin\Admin::jenis');

$routes->get('/admin/editPengguna/(:num)', 'Admin\Admin::editPengguna/$1');
$routes->post('/admin/editPengguna/(:num)', 'Admin\Crud::editPengguna/$1');
$routes->get('/admin/editPegawai/(:num)', 'Admin\Admin::editPegawai/$1');
$routes->post('/admin/editPegawai/(:num)', 'Admin\Crud::editPegawai/$1');
$routes->get('/admin/editUnitKerja/(:num)', 'Admin\Admin::editUnitKerja/$1');
$routes->post('/admin/editUnitKerja/(:num)', 'Admin\Crud::editUnitKerja/$1');

$routes->delete('/admin/dlt_Pengguna/(:num)', 'Admin\Crud::deletePengguna/$1');
$routes->delete('/admin/dlt_UnitKerja/(:num)', 'Admin\Crud::deleteUnitKerja/$1');

$routes->post('/admin/ubah_statusPegawai', 'Admin\Crud::ubahStatusPegawai');
$routes->post('/admin/ubah_statusBekerja', 'Admin\Crud::ubahStatusBekerja');
$routes->post('/admin/ubah_statusUnitKerja', 'Admin\Crud::ubahStatusUnitKerja');

// $routes->get('/admin/ajax_getAtasan', 'Admin\Ajax::getAtasan');
// $routes->get('/admin/ajax_getPejabat', 'Admin\Ajax::getPejabat');
$routes->get('/admin/ajax_getUnitKerja', 'Admin\Ajax::getUnitKerja');
$routes->get('/admin/ajax_getPegawai', 'Admin\Ajax::getPegawai');

$routes->get('/admin/upload-file', 'Admin\Import::importing');
$routes->post('/admin/upload-file', 'Admin\Import::import');

$routes->get('/infografis/kapal', 'Infografis\Data::kapal');
$routes->get('/infografis/pesawat', 'Infografis\Data::pesawat');
$routes->get('/infografis/vaksin', 'Infografis\Data::vaksin');
$routes->get('/infografis/laporan', 'Infografis\Data::laporan');

$routes->get('/infografis/kapal_detail/(:num)', 'Infografis\Data::kapal_detail/$1');
$routes->get('/infografis/pesawat_detail/(:num)', 'Infografis\Data::pesawat_detail/$1');
$routes->get('/infografis/vaksin_detail/(:num)', 'Infografis\Data::vaksin_detail/$1');

$routes->get('/infografis/validation_kapal', 'Infografis\Data::validation_kapal');
$routes->get('/infografis/validation_pesawat', 'Infografis\Data::validation_pesawat');

$routes->get('/infografis/excel', 'Export\Excel::infografis');
$routes->post('/infografis/laporan', 'Export\Excel::infografis_laporan');
$routes->post('/infografis/excel', 'Export\Excel::infografis');
$routes->post('/infografis/pdf', 'Export\Pdf::infografisPDFPreview');
$routes->post('/infografis/laporan', 'Export\Excel::laporanInfografis');
$routes->post('/cuti/pdf', 'Export\Pdf::cuti');
$routes->delete('/cuti/pdf/(:num)', 'Export\Pdf::cuti/$1');
$routes->delete('/cuti/pdf/permohonan/(:num)', 'Export\Pdf::permohonan/$1');
$routes->delete('/cuti/pdf/permohonan/(:num)', 'Export\Pdf::permohonanPreview/$1');
$routes->get('/cuti/pdfView/(:num)', 'Export\Pdf::cutiPreview/$1');
$routes->get('/cuti/pdfView/permohonan/(:num)', 'Export\Pdf::permohonanPreview/$1');

$routes->get('/infografis/dashboard_kapal', 'Infografis\Data::dashboard_kapal');
$routes->get('/infografis/dashboard_pesawat', 'Infografis\Data::dashboard_pesawat');
$routes->get('/infografis/dashboard_vaksin', 'Infografis\Data::dashboard_vaksin');

// Pengawasan Kapal - Laporan PDF
$routes->get('/pengawasan_kapal/laporan/pdf', 'Export\\Pdf::pengawasan_kapal_laporan');

$routes->delete('/cuti/dlt_cuti/(:num)', 'Cuti\Crud::hapus_cuti/$1');
$routes->delete('/cuti/permohonan/dlt_cuti/(:num)', 'Cuti\Crud::hapus_permohonan/$1');


$routes->get('/cuti/validation_detail_cuti/koordinator/(:num)', 'Cuti\Data::validation_detail_cuti_koordinator/$1');
$routes->post('/cuti/validation_detail_cuti/koordinator', 'Cuti\Crud::validation_detail_cuti_koordinator');
$routes->get('/cuti/data_cuti', 'Cuti\Data::data_cuti');
$routes->get('/cuti/tambah_cuti', 'Cuti\Data::tambah_cuti');
$routes->post('/cuti/tambah_cuti', 'Cuti\Crud::tambah_cuti');
$routes->post('/cuti/update_cuti', 'Cuti\Crud::update_cuti');
$routes->get('/cuti/update_cuti/(:num)', 'Cuti\Data::update_cuti/$1');
$routes->post('/cuti/update_permohonan', 'Cuti\Crud::update_permohonan');
$routes->get('/cuti/update_permohonan/(:num)', 'Cuti\Data::update_permohonan/$1');

$routes->get('/cuti/data_permohonan', 'Cuti\Data::data_permohonan');
$routes->get('/cuti/tambah_permohonan', 'Cuti\Data::tambah_permohonan');
$routes->post('/cuti/tambah_permohonan', 'Cuti\Crud::tambah_permohonan');
$routes->get('/cuti/detail_cuti/(:num)', 'Cuti\Data::detail_cuti/$1');
$routes->get('/cuti/detail_permohonan/(:num)', 'Cuti\Data::detail_permohonan/$1');

$routes->get('/cuti/validation_cuti', 'Cuti\Data::validation_cuti');
$routes->get('/cuti/validation_permohonan', 'Cuti\Data::validation_permohonan');
$routes->get('/cuti/validation_detail_cuti/(:num)', 'Cuti\Data::validation_detail_cuti/$1');
$routes->get('/cuti/validation_detail_permohonan/(:num)', 'Cuti\Data::validation_detail_permohonan/$1');
$routes->post('/cuti/validation_detail_cuti/', 'Cuti\Crud::validation_detail_cuti');
$routes->post('/cuti/validation_detail_permohonan/', 'Cuti\Crud::validation_detail_permohonan');
$routes->get('/cuti/ambil_data_cuti', 'Cuti\Ajax::ambil_data_cuti');
$routes->get('/cuti/ambil_data_permohonan', 'Cuti\Ajax::ambil_data_permohonan');

$routes->get('/cuti/sisa_cuti', 'Cuti\Data::sisa_cuti');
$routes->get('/cuti/get_sisa_cuti', 'Cuti\Ajax::sisa_cuti');
$routes->post('/cuti/ajax_cek_sisa_cuti', 'Cuti\Ajax::ajax_cek_sisa_cuti');
$routes->post('/cuti/update_sisa_cuti', 'Cuti\Crud::sisa_cuti');
$routes->get('/cuti/previewBuktiCuti/(:any)', 'Cuti\Data::previewBuktiCuti/$1');
$routes->post('/cuti/export/excel', 'Export\Excel::cuti');

// CRUD
$routes->get('/admin/tambah_user', 'Admin\Admin::tambah_user');
$routes->post('/admin/tambah_user', 'Admin\Crud::tambah_user');
$routes->get('/admin/tambah_unit_kerja', 'Admin\Admin::tambah_unit_kerja');
$routes->post('/admin/tambah_unit_kerja', 'Admin\Crud::tambah_unit_kerja');
$routes->get('/admin/tambah_pegawai', 'Admin\Admin::tambah_pegawai');
$routes->post('/admin/tambah_pegawai', 'Admin\Crud::tambah_pegawai');
$routes->get('/admin/tambah_titimangsa', 'Admin\Admin::tambah_titimangsa');
$routes->post('/admin/tambah_titimangsa', 'Admin\Crud::tambah_titimangsa');

$routes->get('/infografis/tambah_kapal', 'Infografis\Data::tambah_kapal');
$routes->post('/infografis/tambah_kapal', 'Infografis\Crud::tambah_kapal');
$routes->get('/infografis/update_kapal/(:num)', 'Infografis\Data::update_kapal/$1');
$routes->post('/infografis/update_kapal', 'Infografis\Crud::update_kapal');
$routes->get('/infografis/tambah_pesawat', 'Infografis\Data::tambah_pesawat');
$routes->post('/infografis/tambah_pesawat', 'Infografis\Crud::tambah_pesawat');
$routes->get('/infografis/update_pesawat/(:num)', 'Infografis\Data::update_pesawat/$1');
$routes->post('/infografis/update_pesawat', 'Infografis\Crud::update_pesawat');
$routes->get('/infografis/tambah_vaksin', 'Infografis\Data::tambah_vaksin');
$routes->post('/infografis/tambah_vaksin', 'Infografis\Crud::tambah_vaksin');
$routes->get('/infografis/update_vaksin/(:num)', 'Infografis\Data::update_vaksin/$1');
$routes->post('/infografis/update_vaksin', 'Infografis\Crud::update_vaksin');

$routes->delete('/infografis/dlt_kapal/(:num)', 'Infografis\Crud::delete_kapal/$1');
$routes->delete('/infografis/dlt_pesawat/(:num)', 'Infografis\Crud::delete_pesawat/$1');

$routes->get('/auth/ajax', 'Infografis\Crud::ajax');
$routes->get('/infografis/ajax', 'Infografis\Crud::ajax');


// ROUTES FOR DOKUMEN SOP
$routes->get('/sipepes/dashboard', 'Sipepes\Data::dashboard');
$routes->get('/sipepes/utama', 'Sipepes\Data::sop_utama');
$routes->get('/sipepes/sop', 'Sipepes\Data::sop');
$routes->get('/sipepes/sop/change_status/(:num)', 'Sipepes\Crud::change_status/$1');
$routes->get('/sipepes/countDaftarSOP', 'Sipepes\Ajax::countDaftarSOP');
$routes->get('/sipepes/pendukung', 'Sipepes\Data::sop_pendukung');
$routes->get('/sipepes/lainnya', 'Sipepes\Data::sop_lainnya');

$routes->get('/sipepes/tambah_dokumen_sop', 'Sipepes\Data::tambah_dokumen_sop');
$routes->post('/sipepes/tambah_dokumen_sop', 'Sipepes\Crud::tambah_dokumen_sop');

$routes->get('/sipepes/ubah_dokumen_sop/(:num)', 'Sipepes\Data::ubah_dokumen_sop/$1');
$routes->post('/sipepes/ubah_dokumen_sop/(:num)', 'Sipepes\Crud::ubah_dokumen_sop/$1');

$routes->delete('/sipepes/hapus_dokumen_sop/(:num)', 'Sipepes\Crud::hapus_dokumen_sop/$1');

$routes->get('/sipepes/lihat_dokumen_sop/(:num)', 'Sipepes\Data::lihat_dokumen_sop/$1');

$routes->post('/sipepes/export/(:segment)', 'Export\Excel::export_data_sop/$1');
$routes->post('/sipepes/export', 'Export\Excel::export_data_sop');
$routes->get('/sipepes/view_data_sop/(:segment)', 'Sipepes\Data::view_data_sop/$1');
$routes->get('/sipepes/view_data_sop', 'Sipepes\Data::view_data_sop');


// ROUTES FOR SIPEDIT
$routes->get('/sipedit/unggah_pelatihan', 'Sipedit\Data::unggah_pelatihan');
$routes->get('/sipedit/unggah_pelatihan/(:num)', 'Sipedit\Data::unggah_pelatihan/$1');
$routes->post('/sipedit/simpan_data/(:num)', 'Sipedit\Crud::tambah_data_arsip/$1');
$routes->post('/sipedit/simpan_data', 'Sipedit\Crud::tambah_data_arsip');
$routes->get('/sipedit/arsip_saya', 'Sipedit\Data::arsip_saya');
$routes->post('/sipedit/view_data_arsip', 'Sipedit\Ajax::view_data_arsip');
$routes->post('/sipedit/update_sub_kategori', 'Sipedit\Ajax::update_sub_kategori');
$routes->get('/sipedit/unduh_arsip/(:num)', 'Sipedit\Data::sipedit/unduh_arsip/$1');
$routes->post('/sipedit/export', 'Export\Excel::export_data_arsip');
$routes->post('/dashboard/sipedit/export', 'Export\Excel::export_dashboard_data_arsip');
$routes->delete('/sipedit/hapus_arsip/(:num)', 'Sipedit\Crud::del_sipedit/$1');
$routes->post('/sipedit/simpan_pengaturan', 'Sipedit\Crud::simpan_pengaturan');


// ROUTES FOR SINADI
$routes->get('/sinadi/export/lembar_verifikator/(:num)', 'Export\Excel::sinadiLembarVerifikasi/$1');
$routes->get('/sinadi/export/lembar_verifikator/pdf/(:num)', 'Export\Pdf::sinadiLembarVerifikasi/$1');
$routes->post('/sinadi/setUploadBuktiPembayaran', 'Sinadi\Crud::setUploadBuktiPembayaran');
$routes->post('/sinadi/set/verifikator', 'Sinadi\Crud::set_verifikator');
$routes->get('/sinadi/verifikasiSurat', 'Sinadi\Data::verifikasiSurat');
$routes->get('/sinadi/validasi_data/view/(:num)', 'Sinadi\Data::validasiSuratView/$1');
$routes->get('/sinadi/validasi_data/(:num)', 'Sinadi\Data::validasiSurat/$1');
$routes->get('/sinadi/previewBuktiBerkas/(:any)', 'Sinadi\Data::previewBuktiBerkas/$1');
$routes->post('/sinadi/setTTDPeserta', 'Sinadi\Crud::setTTDPeserta');
$routes->post('/sinadi/setTTDPIC', 'Sinadi\\Crud::setTTDPIC');
$routes->post('/sinadi/sendToVerifikator', 'Sinadi\Crud::sendToVerifikator');
$routes->get('/sinadi/verifikasi_data/(:num)/(:num)', 'Sinadi\Data::verifikasiDataSurat/$1/$2');
$routes->post('sinadi/konfirmasiDataSurat', 'Sinadi\Crud::konfirmasiDataSurat');
$routes->post('/sinadi/export_laporan', 'Sinadi\Data::export_laporan');

$routes->get('/sinadi/pengaturan', 'Sinadi\Data::pengaturan');
$routes->post('/sinadi/insertAkses', 'Sinadi\Crud::insertAkses');
$routes->post('/sinadi/removeAkses', 'Sinadi\Crud::removeAkses');
$routes->post('/sinadi/insertAksesTim', 'Sinadi\Crud::insertAksesTim');
$routes->post('/sinadi/removeAksesTim', 'Sinadi\Crud::removeAksesTim');
$routes->get('/sinadi/nominatif/(:num)', 'Export\Pdf::sinadi_nominatif/$1');
$routes->get('/sinadi/kuitansi/(:num)', 'Export\Pdf::sinadi_kuitansi/$1');
$routes->get('/sinadi/riil/(:num)', 'Export\Pdf::sinadi_riil/$1');
$routes->get('/sinadi/kuitansi-riil/(:num)', 'Export\Pdf::sinadi_kuitansi_riil/$1');
$routes->get('/sinadi/sppd/(:num)/(:any)', 'Export\Pdf::sinadi_sppd/$1/$2');
$routes->get('/sinadi/sppd/(:num)', 'Export\Pdf::sinadi_sppd/$1');
$routes->get('/sinadi/getPeserta/(:num)', 'Sinadi\Ajax::getPeserta/$1');
$routes->get('/sinadi/perjalananSaya', 'Sinadi\Data::perjalananSaya');
$routes->get('/sinadi/dinasLuarKota', 'Sinadi\Data::dinasLuarKota');
$routes->get('/sinadi/dinasDalamKota', 'Sinadi\Data::dinasDalamKota');
$routes->delete('/sinadi/dlt_sinadi/(:num)', 'Sinadi\Crud::hapusSinadi/$1');
$routes->get('/sinadi/formulirPerjalananDinas', 'Sinadi\Data::formulirPerjalananDinas');
$routes->get('/sinadi/formulirPerjalananDinas/(:num)', 'Sinadi\Data::formulirPerjalananDinas/$1');
$routes->get('/sinadi/formulirPerjalananDinasAntarKota', 'Sinadi\Data::formulirPerjalananDinasAntarKota');
$routes->get('/sinadi/formulirPerjalananDinasAntarKota/(:num)', 'Sinadi\Data::formulirPerjalananDinasAntarKota/$1');
$routes->post('/sinadi/tambah_sinadi', 'Sinadi\Crud::tambah_sinadi');
$routes->post('/sinadi/edit_sinadi', 'Sinadi\Crud::edit_sinadi');

$routes->delete('/sinadi/dlt/sinadiHapusPeserta/(:num)', 'Sinadi\Crud::sinadiHapusPeserta/$1');
$routes->post('/sinadi/export/excel', 'Export\Excel::sinadi');

$routes->post('/sinadi/dinasDalamKotaView', 'Sinadi\Ajax::dinasDalamKotaView');
$routes->post('/sinadi/dinasLuarKotaView', 'Sinadi\Ajax::dinasLuarKotaView');

$routes->get('/sinadi/notaDinas', 'Sinadi\Data::notaDinas');
$routes->get('/sinadi/download_nota_dinas_zip/(:num)', 'Sinadi\Data::download_nota_dinas_zip/$1');
$routes->post('/sinadi/getNotaDinas', 'Sinadi\Ajax::getNotaDinas');
$routes->post('/sinadi/saveNotaDinas', 'Sinadi\Crud::saveNotaDinas');

// ROUTES FOR WBK
$routes->get('/wbk/rencana_kerja', 'Wbk\Data::rencana_kerja');
$routes->get('/wbk/rencana_kerja_list', 'Wbk\Ajax::rencana_kerja_list');
$routes->get('/wbk/rencana_kerja_list/(:segment)', 'Wbk\Ajax::rencana_kerja_list/$1');
$routes->get('/wbk/tambah_rencana_kerja', 'Wbk\Data::tambah_rencana_kerja');
$routes->get('/wbk/tambah_rencana_kerja/(:num)', 'Wbk\Data::tambah_rencana_kerja/$1');
$routes->post('/wbk/simpan_rencana_kerja', 'Wbk\Crud::simpan_rencana_kerja');
$routes->post('/wbk/simpan_rencana_kerja/(:num)', 'Wbk\Crud::simpan_rencana_kerja/$1');
$routes->post('/wbk/export_rencana_kerja/', 'Export\Excel::export_rencana_kerja');

$routes->get('/wbk/upload_file', 'Wbk\Data::upload_file');
$routes->get('/wbk/upload_file/(:num)', 'Wbk\Data::upload_file/$1');
$routes->post('/wbk/simpan_file', 'Wbk\Crud::simpan_file');

$routes->get('/wbk/daftar_dokumen', 'Wbk\Data::daftar_dokumen');
$routes->get('/wbk/arsip_list', 'Wbk\Ajax::arsip_list');
$routes->get('/wbk/arsip_list/(:segment)', 'Wbk\Ajax::arsip_list/$1');

$routes->get('/get/kategori', 'Wbk\Ajax::getKategori');
$routes->get('/get/pokja', 'Wbk\Ajax::getPokja');

// ROUTES FOR ARKAB
$routes->get('/arkab/arsip_surat', 'Arkab\Data::arsip_surat');
$routes->get('/arkab/arsip_surat_list', 'Arkab\Ajax::arsip_surat_list');
$routes->get('/arkab/arsip_surat_list/(:segment)', 'Arkab\Ajax::arsip_surat_list/$1');
$routes->get('/get/substansi', 'Arkab\Ajax::getSubstansi');
$routes->get('/arkab/tambah_surat', 'Arkab\Data::tambah_surat');
$routes->get('/arkab/tambah_surat/(:num)', 'Arkab\Data::tambah_surat/$1');
$routes->post('/arkab/simpan_surat', 'Arkab\Crud::simpan_surat');
$routes->post('/arkab/simpan_surat/(:num)', 'Arkab\Crud::simpan_surat/$1');
$routes->delete('/arkab/hapus_surat/(:num)', 'Arkab\Crud::hapus_surat/$1');

$routes->get('/relaxtime/relaxtimeRealisasiSP2D', 'Relaxtime\Data::relaxtimeRealisasiSP2D');
$routes->get('/relaxtime/relaxtimeRealisasiTransaksi', 'Relaxtime\Data::relaxtimeRealisasiTransaksi');
$routes->get('/relaxtime/relaxtimeRealisasiAkrual', 'Relaxtime\Data::relaxtimeRealisasiAkrual');
$routes->get('/relaxtime/realisasiUpload/(:any)', 'Relaxtime\Data::importFileRealisasi/$1');
$routes->post('/relaxtime/upload_file', 'Relaxtime\Crud::upload_file');
$routes->post('/relaxtime/upload_file/(:num)', 'Relaxtime\Crud::upload_file/$1');
$routes->get('/relaxtime/relaxtime_realisasi_sp2d', 'Relaxtime\Ajax::realisasi_sp2d');
$routes->get('/relaxtime/relaxtime_realisasi_transaksi', 'Relaxtime\Ajax::realisasi_transaksi');
$routes->get('/relaxtime/relaxtime_realisasi_akrual', 'Relaxtime\Ajax::realisasi_akrual');
$routes->get('/relaxtime/download_template_naskah/(:any)', 'Relaxtime\Data::download_file/$1');
$routes->delete('/relaxtime/file/hapus_naskah/(:num)', 'Relaxtime\Crud::hapus_naskah/$1');

$routes->get('/tata_naskah_dinas/template_naskah', 'Arkab\Data::tata_naskah_dinas');
$routes->get('/tata_naskah_dinas/download_template_naskah/(:any)', 'Arkab\Data::download_template_naskah/$1');
$routes->get('/tata_naskah_dinas/template_naskah/tambah_naskah', 'Arkab\Data::tambah_naskah');
$routes->get('/tata_naskah_dinas/template_naskah/tambah_naskah/(:num)', 'Arkab\Data::tambah_naskah/$1');
$routes->post('/tata_naskah_dinas/template_naskah/simpan_naskah', 'Arkab\Crud::simpan_template_naskah');
$routes->post('/tata_naskah_dinas/template_naskah/simpan_naskah/(:num)', 'Arkab\Crud::simpan_template_naskah/$1');
$routes->get('/tata_naskah_dinas/template_naskah_list', 'Arkab\Ajax::template_naskah_list');
$routes->delete('/tata_naskah_dinas/template_naskah/hapus_naskah/(:num)', 'Arkab\Crud::hapus_naskah/$1');


// ROUTES FOR RELAXTIME
$routes->get('/relaxtime/hasil_rapat_monev', 'Relaxtime\Data::hasil_rapat_monev');
$routes->post('/relaxtime/simpan_hasil_rapat', 'Relaxtime\Crud::simpan_hasil_rapat');
$routes->get('/relaxtime/hapus_hasil_rapat/(:num)', 'Relaxtime\Crud::hapus_hasil_rapat/$1');
$routes->get('/relaxtime/pengaturan', 'Relaxtime\Data::pengaturan');
$routes->get('/relaxtime/pagu', 'Relaxtime\Data::pagu');
$routes->get('/relaxtime/realisasiAnggaran', 'Relaxtime\Data::realisasiAnggaran');
$routes->get('/relaxtime/getDataPagu/(:segment)/(:num)', 'Relaxtime\Ajax::getDataPagu/$1/$2');

$routes->get('/relaxtime/ajax/getPagu/(:num)', 'Relaxtime\Ajax::getPagu/$1');
$routes->get('/relaxtime/ajax/getRealisasi/(:num)', 'Relaxtime\Ajax::getRealisasi/$1');
$routes->post('/relaxtime/importPagu', 'Relaxtime\Import::pagu');
$routes->post('/relaxtime/realisasi', 'Relaxtime\Crud::realisasi');
$routes->post('/relaxtime/realisasiUpdate', 'Relaxtime\Crud::realisasiUpdate');
$routes->post('/relaxtime/insertAkses', 'Relaxtime\Crud::insertAkses');
$routes->post('/relaxtime/removeAkses', 'Relaxtime\Crud::removeAkses');
$routes->post('/relaxtime/updateSetting', 'Relaxtime\Crud::updateSetting');
$routes->get('/relaxtime/excel/realisasiRelaxtime', 'Export\Excel::realisasiRelaxtime');
$routes->post('/relaxtime/excel/realisasiRelaxtime', 'Export\Excel::realisasiRelaxtime');
$routes->get('/relaxtime/edit/(:segment)/(:num)', 'Relaxtime\Data::editPagu/$1/$2');
$routes->post('/relaxtime/update-pagu', 'Relaxtime\Crud::editPagu');
$routes->get('/relaxtime/getSisaAnggaran/(:num)', 'Relaxtime\Ajax::getSisaAnggaran/$1');
$routes->get('/relaxtime/getSisaAnggaranEdit/(:num)', 'Relaxtime\Ajax::getSisaAnggaranEdit/$1');
$routes->delete('/relaxtime/deleteDetailUraian/(:num)', 'Relaxtime\Crud::dltDetailUraian/$1');
$routes->delete('/relaxtime/deleteRO/(:num)', 'Relaxtime\Crud::dltRO/$1');

// SIMANDJA
$routes->get('/simandja/simandjaCapaian', 'Simandja\Data::simandjaCapaian');
$routes->get('/simandja/simandjaRencana', 'Simandja\Data::simandjaRencana');
$routes->get('/simandja/simandjaRevisi', 'Simandja\Data::simandjaRevisi');
$routes->post('/simandja/simandjaRevisi/setAkses', 'Simandja\Crud::setAkses');
$routes->post('/crud/simandja', 'Simandja\Crud::simandja');
$routes->post('/crud/simandja/update', 'Simandja\Crud::simandja');
$routes->delete('/simandja/dlt_sinadi/(:num)', 'Simandja\Crud::hapus_simandja/$1');
$routes->get('/simandja/simandjaMonevPK', 'Simandja\Data::simandjaMonevPK');

$routes->get('/simandja/tambahDataTahun', 'Simandja\Data::tambahDataTahun');
$routes->get('/simandja/tambahDataTahun/(:any)', 'Simandja\Data::tambahDataTahun/$1');
$routes->get('/simandja/editDataTahun/(:num)', 'Simandja\Data::editDataTahun/$1');
$routes->get('/simandja/editDataTahun/(:num)/(:any)', 'Simandja\Data::editDataTahun/$1/$2');
$routes->get('/simandja/simandjarevisi/status/(:num)', 'Simandja\Crud::setActiveLinkSemulmen/$1');
$routes->get('/simandja/simandjaMonevPK/status/(:num)', 'Simandja\Crud::setActiveLinksimandjaMonevPK/$1');
$routes->delete('/simandja/dlt_simulmen/(:num)', 'Simandja\Crud::delete_simulmen/$1');
$routes->post('/crud/tambahDataTahun', 'Simandja\Crud::tambahDataTahun');
$routes->post('/crud/editDataTahun', 'Simandja\Crud::editDataTahun');

$routes->get('/relaxtime/target_bulanan', 'Relaxtime\Data::targetBulanan');
$routes->get('/relaxtime/target_bulanan/(:segment)', 'Relaxtime\Data::targetBulanan/$1');
$routes->get('/relaxtime/target_bulanan_edit/(:segment)', 'Relaxtime\Data::perbaharuiTargetBulanan/$1');
$routes->post('/relaxtime/target_bulanan_update', 'Relaxtime\Crud::perbaharuiTargetBulanan');
$routes->post('/relaxtime/importRealisasi', 'Relaxtime\Import::realisasi');


// UKLW
$routes->get('/uklw/pasien', 'Uklw\Data::pasien');
$routes->get('/uklw/pemkes', 'Uklw\Data::getPemkes');
$routes->get('/uklw/skriningTBC', 'Uklw\Data::getSkriningTBC');
$routes->get('/uklw/skriningHIV', 'Uklw\Data::getSkriningHIV');
$routes->get('/uklw/pasien/view/(:num)', 'Uklw\Ajax::pasien/$1');
$routes->get('/uklw/tambahPasien', 'Uklw\Data::tambahPasien');
$routes->get('/uklw/editPasien/(:num)', 'Uklw\Data::editPasien/$1');
$routes->post('/uklw/tambah_pasien', 'Uklw\Crud::tambahPasien');
$routes->post('/uklw/tambah_pemkes', 'Uklw\Crud::tambahPemkes');
$routes->post('/uklw/tambah_skriningTBC', 'Uklw\Crud::tambahSkriningTBC');
$routes->post('/uklw/tambah_skriningHIV', 'Uklw\Crud::tambahSkriningHIV');
$routes->post('/uklw/tambah_faktorRisikoPTM', 'Uklw\Crud::tambahFaktorRisikoPTM');
$routes->post('/uklw/tambah_laboratorium', 'Uklw\Crud::tambahLaboratorium');
$routes->post('/uklw/tambah_pemkes/ajax', 'Uklw\Crud::tambahPemkesAjax');
$routes->post('/uklw/edit_pasien', 'Uklw\Crud::editPasien');
$routes->get('/uklw/getDataPegawai', 'Uklw\Ajax::getDataPegawai');
$routes->post('/uklw/directToFormPasien', 'Uklw\Data::directToFormPasien');
$routes->get('/uklw/pemkes/(:num)', 'Uklw\Data::pemkes/$1');
$routes->get('/uklw/skriningTBC/(:num)', 'Uklw\Data::skriningTBC/$1');
$routes->get('/uklw/skriningHIV/(:num)', 'Uklw\Data::skriningHIV/$1');
$routes->get('/uklw/faktorRisikoPTM/(:num)', 'Uklw\Data::faktorRisikoPTM/$1');
$routes->get('/uklw/pemkes/edit/(:num)', 'Uklw\Data::editPemkes/$1');
$routes->get('/uklw/skrining_tbc/edit/(:num)', 'Uklw\Data::editSkriningTBC/$1');
$routes->get('/uklw/skrining_hiv/edit/(:num)', 'Uklw\Data::editSkriningHIV/$1');
$routes->get('/uklw/catatan_pemkes/(:num)', 'Uklw\Data::catatanPemeriksaan/$1');
$routes->post('uklw/tambah_catata_pemeriksaan', 'Uklw\Crud::catatanPemeriksaan');
$routes->get('/uklw/detailPasien/(:num)', 'Uklw\Data::detailPasien/$1');
$routes->get('/uklw/laboratorium/edit/(:num)', 'Uklw\Data::editLaboratorium/$1');
$routes->get('/uklw/laboratorium/(:num)', 'Uklw\Data::laboratorium/$1');
$routes->get('/uklw/laboratorium', 'Uklw\Data::getLaboratorium');
$routes->get('/uklw/ajax/searchData', 'Uklw\Ajax::searchData');
$routes->post('uklw/export_excel/penjamah_makanan', 'Export\Excel::uklw_penjamah_makanan');
$routes->post('uklw/export_pdf', 'Export\Pdf::uklwSkriningTerintegrasi');
$routes->get('/uklw/laporan', 'Uklw\Data::laporan');
$routes->get('/uklw/farmasi', 'Uklw\Data::farmasi');
$routes->get('/uklw/data_obat', 'Uklw\Data::dataObat');
$routes->post('/uklw/cekFarmasi', 'Uklw\Ajax::cekFarmasi');
$routes->post('/uklw/getDataPasien', 'Uklw\Ajax::getDataPasien');
$routes->get('/uklw/getCatatanPemeriksaan/pasien/(:num)', 'Uklw\Data::getFarmasi/$1');
$routes->get('/uklw/setPengambilanObat/pasien/(:num)', 'Uklw\Data::setFarmasi/$1');
$routes->post('/uklw/pengambilan_obat', 'Uklw\Crud::pengambilan_obat');
$routes->get('/uklw/editPengambilanObat/(:num)', 'Uklw\Data::editPengambilanObat/$1');
$routes->delete('/uklw/delete/uklw_pengambilan_obat/(:num)', 'Uklw\Crud::hapus_uklw_pengambilan_obat/$1');
$routes->delete('/uklw/delete/(:segment)/(:num)', 'Uklw\Crud::deleteData/$1/$2');
$routes->post('/uklw/ajax/pemeriksaanView', 'Uklw\Ajax::pemeriksaanView');
$routes->post('/uklw/excel/export_pemkes', 'Export\Excel::uklw_pemkes');
$routes->get('/uklw/penjamah_makanan', 'Uklw\Data::penjamahMakanan');
$routes->get('/uklw/tambah_penjamah_makanan', 'Uklw\Data::tambahPenjamahMaknan');
$routes->post('/uklw/tambah_pemkes_penjamah_makanan', 'Uklw\Crud::tambahPemkesPenjamahMakanan');
$routes->post('/uklw/add/riwayat_penyakit', 'Uklw\Crud::addRiwayatPenyakit');
$routes->post('/uklw/update_obat', 'Uklw\Crud::updateDataObat');

// ROUTES FOR PRL KESLING
// TPP =========================================================================================
$routes->get('/prl/tpp', 'Prl\Data::tpp');
$routes->get('/prl/tpp_daftar', 'Prl\Data::tpp_daftar');
$routes->get('/prl/tambah_tpp', 'Prl\Data::tambah_tpp');
$routes->get('/prl/tambah_tpp/(:num)', 'Prl\Data::tambah_tpp/$1');
$routes->post('/prl/simpan_tpp', 'Prl\Crud::simpan_tpp');
$routes->post('/prl/simpan_tpp/(:num)', 'Prl\Crud::simpan_tpp/$1');
$routes->delete('/prl/hapus_tpp/(:num)', 'Prl\Crud::hapus_tpp/$1');

$routes->get('/prl/tpp_daftar_inspeksi', 'Prl\Data::tpp_daftar_inspeksi');
$routes->get('/prl/tambah_inspeksi_tpp', 'Prl\Data::tambah_inspeksi_tpp');
$routes->get('/prl/tambah_inspeksi_tpp/(:num)', 'Prl\Data::tambah_inspeksi_tpp/$1');
$routes->post('/prl/simpan_inspeksi_tpp', 'Prl\Crud::simpan_inspeksi_tpp');
$routes->post('/prl/simpan_inspeksi_tpp/(:num)', 'Prl\Crud::simpan_inspeksi_tpp/$1');
$routes->delete('/prl/hapus_inspeksi_tpp/(:num)', 'Prl\Crud::hapus_inspeksi_tpp/$1');

$routes->get('/prl/get/tpp', 'Prl\Ajax::getTpp');
$routes->get('/prl/prl_print_inspeksi_tpp/(:num)', 'Export\Pdf::prl_print_inspeksi_tpp/$1');

$routes->post('prl/excel_inspeksi_tpp', 'Export\Excel::exportPrlInspeksiTpp');

// SAB =========================================================================================
$routes->get('/prl/sab', 'Prl\Data::sab');
$routes->get('/prl/sab_daftar', 'Prl\Data::sab_daftar');
$routes->get('/prl/tambah_sab', 'Prl\Data::tambah_sab');
$routes->get('/prl/tambah_sab/(:num)', 'Prl\Data::tambah_sab/$1');
$routes->post('/prl/simpan_sab', 'Prl\Crud::simpan_sab');
$routes->post('/prl/simpan_sab/(:num)', 'Prl\Crud::simpan_sab/$1');
$routes->delete('/prl/hapus_sab/(:num)', 'Prl\Crud::hapus_sab/$1');

$routes->get('/prl/sab_daftar_inspeksi', 'Prl\Data::sab_daftar_inspeksi');
$routes->get('/prl/tambah_inspeksi_sab', 'Prl\Data::tambah_inspeksi_sab');
$routes->get('/prl/tambah_inspeksi_sab/(:num)', 'Prl\Data::tambah_inspeksi_sab/$1');
$routes->post('/prl/simpan_inspeksi_sab', 'Prl\Crud::simpan_inspeksi_sab');
$routes->post('/prl/simpan_inspeksi_sab/(:num)', 'Prl\Crud::simpan_inspeksi_sab/$1');
$routes->delete('/prl/hapus_inspeksi_sab/(:num)', 'Prl\Crud::hapus_inspeksi_sab/$1');

$routes->get('/prl/get/sab', 'Prl\Ajax::getSab');
$routes->get('/prl/prl_print_inspeksi_sab/(:num)', 'Export\Pdf::prl_print_inspeksi_sab/$1');

$routes->post('prl/excel_inspeksi_sab', 'Export\Excel::exportPrlInspeksiSab');

// HSBU =========================================================================================
$routes->get('/prl/hsbu', 'Prl\Data::hsbu');
$routes->get('/prl/hsbu_daftar', 'Prl\Data::hsbu_daftar');
$routes->get('/prl/tambah_hsbu', 'Prl\Data::tambah_hsbu');
$routes->get('/prl/tambah_hsbu/(:num)', 'Prl\Data::tambah_hsbu/$1');
$routes->post('/prl/simpan_hsbu', 'Prl\Crud::simpan_hsbu');
$routes->post('/prl/simpan_hsbu/(:num)', 'Prl\Crud::simpan_hsbu/$1');
$routes->delete('/prl/hapus_hsbu/(:num)', 'Prl\Crud::hapus_hsbu/$1');

$routes->get('/prl/hsbu_daftar_inspeksi', 'Prl\Data::hsbu_daftar_inspeksi');

// SIVERA
$routes->get('/prl/sivera', 'Prl\SiveraData::index');
$routes->get('/prl/sivera_jentik', 'Prl\SiveraData::jentik_daftar');
$routes->get('/prl/sivera_dashboard', 'Prl\SiveraData::dashboard');
$routes->post('/prl/simpan_sivera_jentik', 'Prl\SiveraCrud::simpan_jentik');
$routes->post('/prl/simpan_pengendalian_jentik', 'Prl\SiveraCrud::simpan_pengendalian_jentik');
$routes->delete('/prl/hapus_sivera_jentik/(:num)', 'Prl\SiveraCrud::hapus_jentik/$1');

$routes->get('/prl/sivera_lalat', 'Prl\SiveraData::lalat_daftar');
$routes->post('/prl/simpan_sivera_lalat', 'Prl\SiveraCrud::simpan_lalat');
$routes->post('/prl/simpan_pengendalian_lalat', 'Prl\SiveraCrud::simpan_pengendalian_lalat');
$routes->delete('/prl/hapus_sivera_lalat/(:num)', 'Prl\SiveraCrud::hapus_lalat/$1');

$routes->get('/prl/sivera_kecoa', 'Prl\SiveraData::kecoa_daftar');
$routes->post('/prl/simpan_sivera_kecoa', 'Prl\SiveraCrud::simpan_kecoa');
$routes->post('/prl/simpan_pengendalian_kecoa', 'Prl\SiveraCrud::simpan_pengendalian_kecoa');
$routes->delete('/prl/hapus_sivera_kecoa/(:num)', 'Prl\SiveraCrud::hapus_kecoa/$1');

$routes->get('/prl/sivera_tikus', 'Prl\SiveraData::tikus_daftar');
$routes->post('/prl/simpan_sivera_tikus', 'Prl\SiveraCrud::simpan_tikus');
$routes->delete('/prl/hapus_sivera_tikus/(:num)', 'Prl\SiveraCrud::hapus_tikus/$1');

$routes->get('/prl/sivera_pinjal', 'Prl\SiveraData::pinjal_daftar');
$routes->post('/prl/simpan_sivera_pinjal', 'Prl\SiveraCrud::simpan_pinjal');
$routes->delete('/prl/hapus_sivera_pinjal/(:num)', 'Prl\SiveraCrud::hapus_pinjal/$1');

$routes->get('/prl/tambah_inspeksi_hsbu', 'Prl\Data::tambah_inspeksi_hsbu');
$routes->get('/prl/tambah_inspeksi_hsbu/(:num)', 'Prl\Data::tambah_inspeksi_hsbu/$1');
$routes->post('/prl/simpan_inspeksi_hsbu', 'Prl\Crud::simpan_inspeksi_hsbu');
$routes->post('/prl/simpan_inspeksi_hsbu/(:num)', 'Prl\Crud::simpan_inspeksi_hsbu/$1');
$routes->delete('/prl/hapus_inspeksi_hsbu/(:num)', 'Prl\Crud::hapus_inspeksi_hsbu/$1');

$routes->get('/prl/get/hsbu', 'Prl\Ajax::getHsbu');
$routes->get('/prl/prl_print_inspeksi_hsbu/(:num)', 'Export\Pdf::prl_print_inspeksi_hsbu/$1');

$routes->post('prl/excel_inspeksi_hsbu', 'Export\Excel::exportPrlInspeksiHsbu');


// ABS
$routes->get('/abs/bangunan', 'Abs\Data::bangunan');
$routes->post('/abs/simpan_data_lantai', 'Abs\Crud::simpan_data_lantai');
$routes->post('/abs/simpan_data_ruang', 'Abs\Crud::simpan_data_ruang');
$routes->post('/abs/simpan_data_jenis', 'Abs\Crud::simpan_data_jenis');
$routes->get('/abs/lokasi', 'Abs\Data::lokasi');
$routes->post('/abs/simpan_lokasi', 'Abs\Crud::simpan_lokasi');
$routes->get('/abs/editDataBarang/(:num)', 'Abs\Data::edit_lokasi/$1');
$routes->get('/abs/data_barang', 'Abs\Data::dataBarang');
$routes->get('/abs/upload-file', 'Abs\Import::importing');
$routes->post('/abs/upload-file', 'Abs\Import::import');
$routes->get('/abs/download-template', 'Abs\Import::downloadTemplate');

$routes->get('/abs/getLantai', 'Abs\Ajax::getLantai');
$routes->get('/abs/getRuang', 'Abs\Ajax::getRuang');
$routes->get('/abs/getJenisBarang', 'Abs\Ajax::getJenisBarang');
$routes->post('/abs/getDataBarang', 'Abs\Ajax::getDataBarang');
$routes->post('/abs/updateLokasi', 'Abs\Crud::updateLokasi');
$routes->get('/abs/getImage', 'Abs\Ajax::getImageAbs');
$routes->get('/abs/loadLabelABS', 'Abs\Ajax::loadLabelABS');
$routes->get('/abs/verifikasi_update_data', 'Abs\Data::verifikasiUpdateData');
$routes->post('/abs/search/dataABS', 'Abs\Ajax::dataABS');
$routes->get('/abs/manajemen_qr_code', 'Abs\Data::absQRCode');
$routes->get('abs/generate_file_qr_code/(:any)', 'Export\Pdf::absQRCode/$1');
$routes->post('/abs/ajax/countLabel', 'Abs\Ajax::countLabel');
$routes->post('/abs/ajax/getFile', 'Abs\Ajax::getFile');
$routes->get('/abs/download/(:any)', 'Abs\Crud::getFile/$1');
$routes->delete('/abs/dlt_file_abs/(:any)', 'Abs\Crud::delete_file_abs/$1');

$routes->delete('/abs/dlt_lantai/(:num)', 'Abs\Crud::delete_lantai/$1');
$routes->delete('/abs/dlt_ruang/(:num)', 'Abs\Crud::delete_ruang/$1');
$routes->delete('/abs/dlt_jenis/(:num)', 'Abs\Crud::delete_jenis/$1');
$routes->delete('/abs/dlt_lokasiABS/(:num)', 'Abs\Crud::delete_lokasi/$1');
$routes->get('/abs/data_gambar', 'Abs\Data::abs_gambar/$1');
$routes->post('/abs/saveImage', 'Abs\Crud::abs_gambar');
$routes->get('/abs/pengajuan', 'Abs\Data::pengajuan');
$routes->get('/abs/pengajuan_pemeliharaan/(:num)', 'Abs\Data::pengajuan_pemeliharaan/$1');
$routes->post('/abs/validasiPengajuanPemeliharaan', 'Abs\Crud::validasiPengajuanPemeliharaan');

$routes->post('/abs/konfirmasiDataInventaris', 'Abs\Ajax::konfirmasiDataInventaris');
$routes->post('/abs/export_file_qr_code', 'Export\Pdf::absGetQRCode');

$routes->get('/data_abs/(:any)', 'Abs\Data::data_abs/$1');
$routes->post('/data_abs', 'Abs\Ajax::data_abs');
$routes->post('/data_abs/pengajuan_pemindahan', 'Abs\Ajax::pengajuan_pemindahan');
$routes->post('/data_abs/pengajuan_pemeliharaan', 'Abs\Ajax::pengajuan_pemeliharaan');
$routes->post('/data_abs/abs_image/uploader', 'Abs\Ajax::uploader');

$routes->get('/abs/rawat_kendaraan', 'Abs\RawatKendaraan::index');
$routes->get('/abs/rawat_kendaraan/pengajuan', 'Abs\RawatKendaraan::pengajuan');
$routes->post('/abs/rawat_kendaraan/insert', 'Abs\RawatKendaraanCrud::insertPengajuan');
$routes->get('/abs/rawat_kendaraan/detail/(:num)', 'Abs\RawatKendaraan::detail/$1');
$routes->get('/abs/rawat_kendaraan/verifikasi_list', 'Abs\RawatKendaraan::verifikasi');
$routes->post('/abs/rawat_kendaraan/verifikasi', 'Abs\RawatKendaraanCrud::verifikasi');
$routes->get('/abs/rawat_kendaraan/kendaraan', 'Abs\RawatKendaraan::daftar_kendaraan');
$routes->post('/abs/rawat_kendaraan/kendaraan/simpan', 'Abs\RawatKendaraanCrud::daftar_tambah');
$routes->post('/abs/rawat_kendaraan/kendaraan/update', 'Abs\RawatKendaraanCrud::daftar_update');
$routes->delete('/abs/rawat_kendaraan/kendaraan/hapus/(:num)', 'Abs\RawatKendaraanCrud::daftar_delete/$1');
$routes->get('/abs/rawat_kendaraan/pengaturan', 'Abs\RawatKendaraan::pengaturan');
$routes->post('/abs/rawat_kendaraan/insert_verifikator', 'Abs\RawatKendaraanCrud::insertVerifikator');
$routes->get('/abs/rawat_kendaraan/delete_verifikator/(:num)', 'Abs\RawatKendaraanCrud::deleteVerifikator/$1');
$routes->post('/abs/rawat_kendaraan/update_pengajuan', 'Abs\RawatKendaraanCrud::updatePengajuan');
$routes->delete('/abs/rawat_kendaraan/delete_pengajuan/(:num)', 'Abs\RawatKendaraanCrud::deletePengajuan/$1');

// LHKPN SPT
$routes->get('/lhkpn_spt/daftar_dokumen', 'LhkpnSpt\Data::daftar_dokumen');
$routes->get('/lhkpn_spt/unggah_dokumen', 'LhkpnSpt\Data::unggah_dokumen');
$routes->get('/lhkpn_spt/unggah_dokumen/(:num)', 'LhkpnSpt\Data::unggah_dokumen/$1');
$routes->post('/lhkpn_spt/simpan_dokumen/(:num)', 'LhkpnSpt\Crud::simpan_dokumen/$1');
$routes->post('/lhkpn_spt/simpan_dokumen', 'LhkpnSpt\Crud::simpan_dokumen');
$routes->get('/lhkpn_spt/dokumen_lhkpn_spt', 'LhkpnSpt\Ajax::daftar_dokumen_list');
$routes->get('/lhkpn_spt/dokumen_lhkpn_spt/(:segment)/(:segment)', 'LhkpnSpt\Ajax::daftar_dokumen_list/$1/$2');
$routes->get('/lhkpn_spt/dokumen_lhkpn_spt_by_year/(:num)', 'LhkpnSpt\Ajax::daftar_dokumen_list_by_year/$1');
$routes->get('/lhkpn_spt/download_zip/(:num)', 'LhkpnSpt\Crud::download_zip/$1');
$routes->get('/lhkpn_spt/export_excel/(:num)', 'Export\Excel::export_lhkpn_spt_year/$1');
$routes->delete('/lhkpn_spt/hapus_dokumen/(:num)', 'LhkpnSpt\Crud::hapus_dokumen/$1');
$routes->post('/lhkpn_spt/export', 'Export\Excel::export_lhkpn_spt');

//
$routes->post('/getLinimasa/absensi', 'Absen\Ajax::getLinimasaData');
$routes->post('/absensi/getLatestDLPhoto', 'Absen\Ajax::getLatestDLPhoto');
$routes->get('/absen_get/users', 'Absen\Ajax::getDataAbsen');
$routes->get('/absen_get/users_tanggal', 'Absen\Ajax::getDataAbsenTanggal');
$routes->get('/absensi', 'Absen\Data::index');
$routes->get('/absensi-wfo', 'Absen\Data::directWFO');
$routes->get('/absensi-wfa', 'Absen\Data::wfa');
$routes->post('/absensi/clockIn', 'Absen\Crud::clockIn');
$routes->post('/absensi/clockOut', 'Absen\Crud::clockOut');

$routes->post('/absen/addJamAbsen', 'Absen\Crud::addJamAbsen');
$routes->get('/absen/zona_absen', 'Absen\Data::zonaAbsen');
$routes->post('/absen/getZonaAbsen', 'Absen\Data::getZonaAbsen');
$routes->post('/absen/saveZonaAbsen', 'Absen\Crud::saveZonaAbsen');
$routes->delete('/absen/terkonfirmasi_zona/(:num)', 'Absen\Crud::terkonfirmasiZona/$1');
$routes->delete('/absen/tolak_zona/(:num)', 'Absen\Crud::tolakZona/$1');
$routes->delete('/absen/dlt_zona/(:num)', 'Absen\Crud::deleteZona/$1');
$routes->get('/absen/absen_harian', 'Absen\Data::dataAbsen');
$routes->post('/absen/get_absen_harian', 'Absen\Ajax::dataAbsen');
$routes->post('/absen/get_distance', 'Absen\Ajax::getDistance');
$routes->get('/absen/get_mark_location', 'Absen\Ajax::getMarkLocation');
$routes->post('/absen/validasiMasuk/(:num)/(:num)', 'Absen\Crud::validasiMasuk/$1/$2');
$routes->post('/absen/validasiPulang/(:num)/(:num)', 'Absen\Crud::validasiPulang/$1/$2');
$routes->get('/absen/getSecondImage/(:num)', 'Absen\Ajax::getSecondImage/$1');
$routes->post('/absen/getImage', 'Absen\Ajax::getImage');

$routes->post('/absen/addKeterangan', 'Absen\Crud::addKeterangan');
$routes->get('/absen/getPegawai', 'Absen\Ajax::getPegawai');
$routes->get('/absen/getAbsenById/(:num)', 'Absen\Ajax::getAbsenById/$1');
$routes->post('/absen/updateDataAbsenAll', 'Absen\Crud::updateDataAbsenAll');
$routes->post('/absen/absenCekSinadi', 'Absen\Ajax::absenCekSinadi');
$routes->post('/absen/absenCekCuti', 'Absen\Ajax::absenCekCuti');
$routes->post('/absen/absenCekPerizinan', 'Absen\Ajax::absenCekPerizinan');
$routes->get('/absen/getRekamAbsen/(:num)', 'Absen\Ajax::getRekamAbsen/$1');

$routes->post('/absen/export/rekapAbsen', 'Export\Excel::rekapAbsen');
$routes->post('/absen/export/rekamAbsen', 'Export\Excel::rekamAbsen');
$routes->post('/absen/export/rekamAbsenDL', 'Export\Excel::rekamAbsenDL');
$routes->post('/absen/export/rekamAbsenDLPdf', 'Export\Pdf::rekamAbsenDL');
$routes->post('/absen/export/arsipAbsen', 'Export\Excel::arsipAbsen');

$routes->get('/getTargetAreaAbsen', 'Absen\Ajax::getTargetAreaAbsen');
$routes->get('/getTargetAreaAbsen/(:num)', 'Absen\Ajax::getTargetAreaAbsen/$1');
$routes->get('/getTargetAreaAbsen/wfa', 'Absen\Ajax::getTargetAreaAbsenWFA');
$routes->get('/getTargetAreaAbsen/wfa/(:num)', 'Absen\Ajax::getTargetAreaAbsenWFA/$1');

$routes->get('/absen/pengaturan_absen', 'Absen\Data::pengaturan');
$routes->post('/absen/insertAkses', 'Absen\Crud::insertAkses');
$routes->post('/absen/removeAkses', 'Absen\Crud::removeAkses');
$routes->post('/absen/addDataAbsen', 'Absen\Crud::addDataAbsen');
$routes->delete('/absen/deleteAbsen/(:num)', 'Absen\Crud::deleteAbsen/$1');
$routes->post('/absen/setNewAbsen', 'Absen\Crud::setNewAbsen');
$routes->post('/absen/updateTargetAkses', 'Absen\Crud::updateTargetAkses');
$routes->post('/absen/updateTargetPolygon', 'Absen\Crud::updateTargetPolygon');
$routes->post('/absen/updateAksesWFA', 'Absen\Crud::updateAksesWFA');
$routes->post('/absen/updateAksesEditDel', 'Absen\Crud::updateAksesEditDel');
$routes->post('/absen/updateAksesEditDelBatch', 'Absen\Crud::updateAksesEditDelBatch');
$routes->post('/absen/updateTombolAbsen', 'Absen\Crud::updateTombolAbsen');


$routes->get('/kendaraan_kantor', 'KendaraanKantor\Data::index');
$routes->get('/kendaraan_kantor/daftar_kendaraan', 'KendaraanKantor\Data::daftar_kendaraan');
$routes->get('/kendaraan_kantor/riwayat_peminjaman', 'KendaraanKantor\Data::riwayat_peminjaman');
$routes->post('/kendaraan_kantor/get_penumpang', 'KendaraanKantor\Ajax::get_penumpang');
$routes->get('/kendaraan_kantor/update_riwayat/(:num)', 'KendaraanKantor\Data::update_riwayat/$1');
$routes->post('/kendaraan_kantor/update/riwayat', 'KendaraanKantor\Crud::riwayat');
$routes->delete('/kendaraan_kantor/dlt_riwayat/(:num)', 'KendaraanKantor\Crud::delete_riwayat/$1');
$routes->get('/kendaraan_kantor/tambah_daftar', 'KendaraanKantor\Data::tambah_daftar');
$routes->get('/kendaraan_kantor/update_daftar/(:num)', 'KendaraanKantor\Data::update_daftar/$1');
$routes->post('/kendaraan_kantor/update/daftar', 'KendaraanKantor\Crud::daftar');
$routes->post('/kendaraan_kantor/tambah/daftar', 'KendaraanKantor\Crud::daftar_tambah');
$routes->delete('/kendaraan_kantor/dlt_daftar/(:num)', 'KendaraanKantor\Crud::delete_daftar/$1');
$routes->get('/kendaraan_kantor/pengaturan', 'KendaraanKantor\Data::pengaturan');
$routes->post('/kendaraan_kantor/verifikasi/pin', 'KendaraanKantor\Ajax::pin_verifikator');
$routes->post('/kendaraan_kantor/pengaturan/update_pin', 'KendaraanKantor\Ajax::update_pin');
$routes->get('/kendaraan_kantor/pengaturan/delete/(:num)', 'KendaraanKantor\Ajax::pengaturan_delete/$1');
$routes->get('/kendaraan_kantor/pengaturan/insert/(:num)', 'KendaraanKantor\Ajax::pengaturan_insert/$1');

$routes->get('/kendaraan_kantor_verifikator', 'KendaraanKantor\Data::verifikator');
$routes->post('/kendaraan_kantor_get_kendaraan', 'KendaraanKantor\Ajax::get_kendaraan');
$routes->post('/kendaraan_kantor_set_form', 'KendaraanKantor\Ajax::set_form');
$routes->post('/kendaraan_kantor_get_the_car', 'KendaraanKantor\Ajax::get_the_car');
$routes->post('/kendaraan_kantor_cek_tanggal', 'KendaraanKantor\Ajax::cek_tanggal');
$routes->post('/kendaraan_kantor_get_riwayat', 'KendaraanKantor\Ajax::get_riwayat');
$routes->post('/kendaraan_kantor_batalkan_peminjaman_kendaraan', 'KendaraanKantor\Ajax::batalkan_peminjaman_kendaraan');
$routes->post('/kendaraan_kantor_get_pengajuan', 'KendaraanKantor\Ajax::get_pengajuan');
$routes->post('kendaraan_kantor_verifikator_kendaraan_umum', 'KendaraanKantor\Ajax::verifikator_kendaraan_umum');

$routes->get('/pengawasan_kapal', 'PengawasanKapal\Data::pengawasan_kapal');
$routes->get('/detail_pengawasan_kapal/(:num)', 'PengawasanKapal\Data::detail_pengawasan_kapal/$1');
$routes->get('/pengawasan_kapal/data_pengawasan_kapal', 'PengawasanKapal\Data::data_pengawasan_kapal');
$routes->get('/pengawasan_kapal/export_excel', 'PengawasanKapal\Data::export_excel');
$routes->post('/pengawasan_kapal/verify_status', 'PengawasanKapal\Data::verify_status');
$routes->post('/pengawasan_kapal/delete_data', 'PengawasanKapal\Data::delete_data');
$routes->get('/pengawasan_kapal_akses', 'PengawasanKapal\Data::pengawasan_kapal_akses');
$routes->post('/pengawasan_kapal_akses/insertAkses', 'PengawasanKapal\Crud::insertAkses');
$routes->post('/pengawasan_kapal_akses/removeAkses', 'PengawasanKapal\Crud::removeAkses');
$routes->post('/pengawasan_kapal_akses/updateTargetAkses', 'PengawasanKapal\Crud::updateTargetAkses');
$routes->post('/pengawasan_kapal/update_gross_tonnage', 'PengawasanKapal\Crud::update_gross_tonnage');
$routes->get('/laporan_pengawasan_kapal', 'PengawasanKapal\Data::laporan_pengawasan_kapal');

// Layanan Kesehatan Kapal Routes
$routes->get('/layanan_kesehatan_kapal', 'PengawasanKapal\Data::layanan_kesehatan_kapal');
$routes->get('/layanan_kesehatan_kapal/export_excel', 'PengawasanKapal\Data::export_excel_layanan_kesehatan_kapal');
$routes->get('/layanan_kesehatan_kapal/export_pdf/(:num)', 'Export\Pdf::layanan_kesehatan_kapal_pdf/$1');
$routes->get('/layanan_kesehatan_kapal/edit/(:num)', 'PengawasanKapal\Data::edit_layanan_kesehatan_kapal/$1');
$routes->post('/layanan_kesehatan_kapal/update/(:num)', 'PengawasanKapal\Data::update_layanan_kesehatan_kapal/$1');
$routes->get('/layanan_kesehatan_kapal/delete/(:num)', 'PengawasanKapal\Data::delete_layanan_kesehatan_kapal/$1');

// Monitoring Sanitasi Kapal Routes
$routes->get('/monitoring_sanitasi_kapal', 'PengawasanKapal\Data::monitoring_sanitasi_kapal');
$routes->get('/monitoring_sanitasi_kapal/data', 'PengawasanKapal\Data::data_monitoring_sanitasi_kapal');
$routes->get('/monitoring_sanitasi_kapal/wilker_options', 'PengawasanKapal\Data::get_wilker_options');
$routes->get('/monitoring_sanitasi_kapal/user_data', 'PengawasanKapal\Data::user_data');
$routes->post('/monitoring_sanitasi_kapal/save', 'PengawasanKapal\Data::save_monitoring_sanitasi_kapal');
$routes->post('/monitoring_sanitasi_kapal/update', 'PengawasanKapal\Data::update_monitoring_sanitasi_kapal');
$routes->get('/monitoring_sanitasi_kapal/edit/(:num)', 'PengawasanKapal\Data::edit_monitoring_sanitasi_kapal/$1');
$routes->get('/monitoring_sanitasi_kapal/detail/(:num)', 'PengawasanKapal\Data::detail_monitoring_sanitasi_kapal/$1');
$routes->delete('/monitoring_sanitasi_kapal/delete/(:num)', 'PengawasanKapal\Data::delete_monitoring_sanitasi_kapal/$1');
$routes->get('/monitoring_sanitasi_kapal/export_excel', 'PengawasanKapal\Data::export_monitoring_sanitasi_kapal');

$routes->get('/surat_sk', 'Sk\Data::index');
$routes->get('/surat_sk/(:num)', 'Sk\Data::index/$1');
$routes->get('/surat_sk/(:num)/(:segment)', 'Sk\Data::index/$1/$2');
$routes->get('/sk/data/view/(:any)/(:any)', 'Sk\Data::view/$1/$2');

$routes->post('/sisovi/setPengalihan', 'Sisovi\Ajax::setPengalihan');

// ROUTES FOR IFRAME
$routes->get('/iframe/get/menu', 'Iframe\Data::menu');
$routes->post('/iframe/setLink', 'Iframe\Crud::setLink');
$routes->get('/iframe/setLink', 'Iframe\Data::setLink');
$routes->get('iframe/set/(:any)', 'Iframe\Data::iframe/$1');
$routes->delete('/iframe/delete/(:num)', 'Iframe\Crud::dlt_iframe/$1');

// ROUTES FOR PORTAL LINK
$routes->get('/portal_link/get/menu', 'PortalLink\Data::menu');
$routes->post('/portal_link/setLink', 'PortalLink\Crud::setLink');
$routes->get('/portal_link/setLink', 'PortalLink\Data::setLink');
$routes->delete('/portal_link/delete/(:num)', 'PortalLink\Crud::delete/$1');

// PENGUKURAN KEBUGARAN
$routes->get('/pengukuran_kebugaran', 'PengukuranKebugaran\Data::index');
$routes->get('/pengukuran_kebugaran/tambah', 'PengukuranKebugaran\Data::tambah');
$routes->get('/pengukuran_kebugaran/edit/(:num)', 'PengukuranKebugaran\Data::edit/$1');
$routes->get('/pengukuran_kebugaran/detail/(:num)', 'PengukuranKebugaran\Data::detail/$1');
$routes->get('/pengukuran_kebugaran/pengaturan', 'PengukuranKebugaran\Data::pengaturan');
$routes->get('/pengukuran_kebugaran/import', 'PengukuranKebugaran\Data::import');
$routes->get('/pengukuran_kebugaran/download_template', 'PengukuranKebugaran\Data::downloadTemplate');
$routes->post('/pengukuran_kebugaran/process_import', 'PengukuranKebugaran\Data::processImport');
$routes->post('/pengukuran_kebugaran/simpan', 'PengukuranKebugaran\Crud::simpan');
$routes->post('/pengukuran_kebugaran/update/(:num)', 'PengukuranKebugaran\Crud::update/$1');
$routes->post('/pengukuran_kebugaran/grant_access', 'PengukuranKebugaran\Crud::grantAccess');
$routes->post('/pengukuran_kebugaran/revoke_access', 'PengukuranKebugaran\Crud::revokeAccess');
$routes->delete('/pengukuran_kebugaran/hapus/(:num)', 'PengukuranKebugaran\Crud::hapus/$1');
$routes->post('/pengukuran_kebugaran/export', 'PengukuranKebugaran\Data::export');


// ANGGARAN (Budget Management)
$routes->get('/anggaran/dashboard', 'Anggaran\Data::dashboard');
$routes->get('/anggaran/data', 'Anggaran\Data::data');
$routes->get('/anggaran/input', 'Anggaran\Data::input');
$routes->post('/anggaran/store', 'Anggaran\Data::store');
$routes->get('/anggaran/edit/(:num)', 'Anggaran\Data::edit/$1');
$routes->put('/anggaran/update/(:num)', 'Anggaran\Data::update/$1');
$routes->delete('/anggaran/delete/(:num)', 'Anggaran\Data::delete/$1');
$routes->get('/anggaran/detail/(:num)', 'Anggaran\Data::detail/$1');
$routes->get('/anggaran/getDetail/(:num)', 'Anggaran\Data::getDetail/$1');
$routes->get('/anggaran/getEdit/(:num)', 'Anggaran\Data::getEdit/$1');
$routes->get('/anggaran/export', 'Anggaran\Data::export');

$routes->get('/menu', 'Auth::menu');
$routes->get('/menu-v2', 'Auth::menuV2');

// ROUTES FOR E-KIP
$routes->get('/ekip/kinerja', 'Ekip\Data::kinerja');
$routes->get('/ekip/perilaku', 'Ekip\Data::perilaku');
$routes->get('/ekip/kalibrasi', 'Ekip\Data::kalibrasi');
$routes->get('/ekip/kalibrasi/get_data', 'Ekip\Data::getKalibrasiData');
$routes->post('/ekip/kalibrasi/simpan', 'Ekip\Data::simpanKalibrasi');
$routes->post('/ekip/penilaian_360_final/simpan', 'Ekip\Data::simpanPenilaian360Final');
$routes->get('/ekip/hasil', 'Ekip\Data::hasil');
$routes->get('/ekip/pengaturan', 'Ekip\Data::pengaturan');

$routes->get('/ekip/penilaian_360', 'Ekip\Data::penilaian_360');
$routes->post('/ekip/penilaian_360/simpan', 'Ekip\Crud::simpanPenilaian360');
$routes->post('/ekip/penilaian_360/simpan_justifikasi', 'Ekip\Crud::simpanJustifikasi360');
$routes->delete('/ekip/penilaian_360/hapus_justifikasi', 'Ekip\Crud::hapusJustifikasi360');
$routes->get('/ekip/penilaian_360/get_justifikasi_by_dinilai/(:num)/(:segment)', 'Ekip\Data::getJustifikasiByDinilai/$1/$2');

// CRUD E-KIP
$routes->post('/ekip/master/simpan', 'Ekip\Crud::simpanMasterKinerja');
$routes->delete('/ekip/master/hapus/(:num)', 'Ekip\Crud::hapusMasterKinerja/$1');
$routes->get('/ekip/mingguan/get/(:num)', 'Ekip\Crud::getEvaluasiMingguan/$1');
$routes->post('/ekip/mingguan/sinkron_global', 'Ekip\Crud::sinkronisasiGlobalPenilai');
$routes->get('/ekip/mingguan/get_sebelumnya', 'Ekip\Crud::getEvaluasiMingguanSebelumnya');
$routes->get('/ekip/mingguan/get_sebelumnya_penilai', 'Ekip\Crud::getEvaluasiMingguanSebelumnyaPenilai');
$routes->get('/ekip/mingguan/get/(:num)/(:num)', 'Ekip\Crud::getEvaluasiMingguanPegawai/$1/$2');
$routes->post('/ekip/kinerja/simpan_catatan', 'Ekip\Crud::simpanCatatanKinerja');
$routes->get('/ekip/perilaku/get/(:num)/(:num)', 'Ekip\Crud::getPerilakuMingguanPegawai/$1/$2');
$routes->get('/ekip/perilaku/get_sebelumnya', 'Ekip\Crud::getPerilakuMingguanSebelumnya');
$routes->get('/ekip/perilaku/get_sebelumnya_penilai', 'Ekip\Crud::getPerilakuMingguanSebelumnyaPenilai');
$routes->post('/ekip/mingguan/simpan', 'Ekip\Crud::simpanEvaluasiMingguan');
$routes->delete('/ekip/mingguan/hapus/(:num)', 'Ekip\Crud::hapusEvaluasiMingguan/$1');
$routes->delete('/ekip/mingguan/hapus-minggu/(:num)', 'Ekip\Crud::hapusMinggu/$1');
$routes->post('/ekip/perilaku/simpan', 'Ekip\Crud::simpanPerilakuBerakhlak');
$routes->get('/ekip/perilaku/get/(:num)', 'Ekip\Crud::getPerilakuMingguan/$1');
$routes->delete('/ekip/perilaku/hapus-minggu/(:num)', 'Ekip\Crud::hapusPerilakuMingguan/$1');
$routes->post('/ekip/ekspektasi/simpan', 'Ekip\Crud::simpanEkspektasi');
$routes->post('/ekip/ekspektasi/rollback', 'Ekip\Crud::rollbackEkspektasi');
$routes->post('/ekip/feedback/ajukan', 'Ekip\Crud::ajukanFeedback');
$routes->post('/ekip/feedback/tanggapi', 'Ekip\Crud::tanggapiFeedback');
$routes->get('/ekip/observasi/get/(:num)/(:num)/(:num)/(:num)', 'Ekip\Crud::getObservasiPerilaku/$1/$2/$3/$4');
$routes->get('/ekip/observasi/cek_ekspektasi/(:num)/(:num)/(:num)', 'Ekip\Crud::cekEkspektasiPimpinan/$1/$2/$3');
$routes->post('/ekip/observasi/simpan', 'Ekip\Crud::simpanObservasiPerilaku');
$routes->post('/ekip/rekomendasi/simpan', 'Ekip\Crud::simpanRekomendasiKebijakan');
$routes->get('/ekip/rekomendasi/get/(:num)/(:num)/(:num)/(:num)', 'Ekip\Crud::getRekomendasiKebijakan/$1/$2/$3/$4');
$routes->delete('/ekip/rekomendasi/hapus/(:num)', 'Ekip\Crud::hapusRekomendasiKebijakan/$1');
$routes->post('/ekip/pengaturan/simpan', 'Ekip\Crud::simpanPengaturan');
$routes->post('/ekip/penilai/tambah', 'Ekip\Crud::tambahPenilai');
$routes->delete('/ekip/penilai/hapus/(:num)', 'Ekip\Crud::hapusPenilai/$1');
$routes->post('/ekip/pic_tim/tambah', 'Ekip\Crud::tambahPicTim');
$routes->delete('/ekip/pic_tim/hapus/(:num)', 'Ekip\Crud::hapusPicTim/$1');
$routes->post('/ekip/super_admin/tambah', 'Ekip\Crud::simpanSuperAdmin');
$routes->post('/ekip/super_admin/hapus/(:num)', 'Ekip\Crud::hapusSuperAdmin/$1');

$routes->group('api', function ($routes) {
  $routes->get('pegawai', 'Api\DataPegawaiApi::index');
  $routes->get('pegawai/(:num)', 'Api\DataPegawaiApi::show/$1');
  $routes->get('unit_kerja', 'Api\UnitKerjaApi::index');
  $routes->get('unit_kerja/(:num)', 'Api\UnitKerjaApi::show/$1');
  $routes->get('user', 'Api\UserApi::index');
  $routes->get('user/(:num)', 'Api\UserApi::show/$1');
});

$routes->get('/get-perjalanan-dinas', 'Sinadi\Ajax::getPerjalananDinas');
$routes->group('travelconsent', function ($routes) {
    // Public routes
    $routes->get('home', 'TravelConsent::public_home');
    $routes->get('public_aircraft', 'TravelConsent::public_aircraft');
    $routes->post('submit_aircraft', 'TravelConsent::submit_aircraft');
    $routes->get('public_alat', 'TravelConsent::public_alat');
    $routes->post('submit_alat', 'TravelConsent::submit_alat');
    $routes->get('public_obat', 'TravelConsent::public_obat');
    $routes->post('submit_obat', 'TravelConsent::submit_obat');
    
    $routes->get('public_sanitasi', 'TravelConsent::public_sanitasi');
    $routes->post('submit_sanitasi', 'TravelConsent::submit_sanitasi');

    // Admin routes
    $routes->get('index', 'TravelConsent::index', ['filter' => 'auth']);
    $routes->get('delete_aircraft/(:num)', 'TravelConsent::delete_aircraft/$1', ['filter' => 'auth']);
    $routes->get('delete_alat/(:num)', 'TravelConsent::delete_alat/$1', ['filter' => 'auth']);
    $routes->get('delete_obat/(:num)', 'TravelConsent::delete_obat/$1', ['filter' => 'auth']);

    $routes->get('edit_aircraft/(:num)', 'TravelConsent::edit_aircraft/$1', ['filter' => 'auth']);
    $routes->post('update_aircraft/(:num)', 'TravelConsent::update_aircraft/$1', ['filter' => 'auth']);
    $routes->get('edit_alat/(:num)', 'TravelConsent::edit_alat/$1', ['filter' => 'auth']);
    $routes->post('update_alat/(:num)', 'TravelConsent::update_alat/$1', ['filter' => 'auth']);
    $routes->get('edit_obat/(:num)', 'TravelConsent::edit_obat/$1', ['filter' => 'auth']);
    $routes->post('update_obat/(:num)', 'TravelConsent::update_obat/$1', ['filter' => 'auth']);

    $routes->get('excel_aircraft/(:num)', 'TravelConsent::excel_aircraft/$1', ['filter' => 'auth']);
    $routes->get('excel_alat/(:num)', 'TravelConsent::excel_alat/$1', ['filter' => 'auth']);
    $routes->get('excel_obat/(:num)', 'TravelConsent::excel_obat/$1', ['filter' => 'auth']);

    $routes->get('print_aircraft/(:num)', 'TravelConsent::print_aircraft/$1', ['filter' => 'auth']);
    $routes->get('print_alat/(:num)', 'TravelConsent::print_alat/$1', ['filter' => 'auth']);
    $routes->get('print_obat/(:num)', 'TravelConsent::print_obat/$1', ['filter' => 'auth']);
    
    $routes->get('edit_sanitasi/(:num)', 'TravelConsent::edit_sanitasi/$1', ['filter' => 'auth']);
    $routes->get('delete_sanitasi/(:num)', 'TravelConsent::delete_sanitasi/$1', ['filter' => 'auth']);
    $routes->get('print_sanitasi/(:num)', 'TravelConsent::print_sanitasi/$1', ['filter' => 'auth']);
    $routes->post('update_sanitasi/(:num)', 'TravelConsent::update_sanitasi/$1', ['filter' => 'auth']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
$routes->post('/absensi/simpanTandaTangan', 'Absen\Crud::simpanTandaTangan');
$routes->get('/absen/absen_dinas_luar', 'Absen\Data::absenDinasLuar');
$routes->post('/absen/get_absen_dinas_luar', 'Absen\Ajax::dataAbsenDinasLuar');


if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
  require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
