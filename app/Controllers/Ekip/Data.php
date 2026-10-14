<?php

namespace App\Controllers\Ekip;

use App\Controllers\BaseController;
use App\Models\Ekip\EkipMasterKinerjaModel;
use App\Models\Ekip\EkipEvaluasiMingguanModel;
use App\Models\Ekip\EkipPerilakuBerakhlakModel;
use App\Models\Ekip\EkipEkspektasiPimpinanModel;
use App\Models\Ekip\EkipPenilaiModel;
use App\Models\Ekip\EkipPengaturanModel;
use App\Models\Master\UserModel;

class Data extends BaseController
{
    protected $masterModel;
    protected $evaluasiModel;
    protected $perilakuModel;
    protected $ekspektasiModel;
    protected $penilaiModel;
    protected $pengaturanModel;
    protected $userModel;

    public function __construct()
    {
        $this->masterModel = new EkipMasterKinerjaModel();
        $this->evaluasiModel = new EkipEvaluasiMingguanModel();
        $this->perilakuModel = new EkipPerilakuBerakhlakModel();
        $this->ekspektasiModel = new EkipEkspektasiPimpinanModel();
        $this->penilaiModel = new EkipPenilaiModel();
        $this->pengaturanModel = new EkipPengaturanModel();
        $this->userModel = new UserModel();
    }

    private function checkIsLocked($pengaturan)
    {
        if (isset($pengaturan['akses_pegawai']) && $pengaturan['akses_pegawai'] == 1) {
            return false; // Bypass aktif
        }

        $hari_ini = (int)date('N'); // 1 (Senin) - 7 (Minggu)
        $jam_ini = date('H:i:s');
        $hari_buka = (int)($pengaturan['jadwal_buka_hari'] ?? 1);
        $hari_tutup = (int)($pengaturan['jadwal_tutup_hari'] ?? 5);
        $jam_tutup = $pengaturan['jadwal_tutup_jam'] ?? '16:00:00';

        $isLocked = true;

        if ($hari_buka <= $hari_tutup) {
            // Normal week (e.g. Senin - Jumat)
            if ($hari_ini >= $hari_buka && $hari_ini <= $hari_tutup) {
                $isLocked = false;
                if ($hari_ini == $hari_tutup && $jam_ini > $jam_tutup) {
                    $isLocked = true;
                }
            }
        } else {
            // Cross week (e.g. Jumat - Selasa)
            if ($hari_ini >= $hari_buka || $hari_ini <= $hari_tutup) {
                $isLocked = false;
                if ($hari_ini == $hari_tutup && $jam_ini > $jam_tutup) {
                    $isLocked = true;
                }
            }
        }

        return $isLocked;
    }

    public function getLockedWeeksArray($pengaturan, $tahun, $bulan)
    {
        $locked_weeks = [];
        $bypass = (isset($pengaturan['akses_pegawai']) && $pengaturan['akses_pegawai'] == 1);
        $hari_buka = (int)($pengaturan['jadwal_buka_hari'] ?? 1);
        $hari_tutup = (int)($pengaturan['jadwal_tutup_hari'] ?? 5);
        $jam_tutup = $pengaturan['jadwal_tutup_jam'] ?? '16:00:00';

        $list_minggu = $this->generateMingguList($tahun, $bulan);

        foreach ($list_minggu as $weekNum => $minggu) {
            if ($bypass) {
                $locked_weeks[$weekNum] = false;
            } else {
                $sunday_date = $minggu['end_date'];
                $sunday_ts = strtotime($sunday_date . ' 00:00:00');

                if ($hari_buka <= $hari_tutup) {
                    $open_date = date('Y-m-d', strtotime("-" . (7 - $hari_buka) . " days", $sunday_ts));
                    $deadline_date = date('Y-m-d', strtotime("-" . (7 - $hari_tutup) . " days", $sunday_ts));
                } else {
                    $open_date = date('Y-m-d', strtotime("-" . (7 - $hari_buka) . " days", $sunday_ts));
                    $deadline_date = date('Y-m-d', strtotime("+" . $hari_tutup . " days", $sunday_ts));
                }

                $open_ts = strtotime($open_date . ' 00:00:00');
                $deadline_ts = strtotime($deadline_date . ' ' . $jam_tutup);

                $locked_weeks[$weekNum] = (time() < $open_ts || time() > $deadline_ts);
            }
        }
        
        // Fill non-existent weeks (e.g. week 5) with locked status
        for ($i=1; $i<=5; $i++) {
            if (!isset($locked_weeks[$i])) {
                $locked_weeks[$i] = true;
            }
        }

        return $locked_weeks;
    }

    public function generateMingguList($tahun, $bulan) {
        $bulanIndo = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $weeks = [];
        
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $weekNum = 1;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateTs = mktime(0,0,0, $bulan, $day, $tahun);
            if (date('N', $dateTs) == 4) { // It's a Thursday
                $mondayTs = strtotime('-3 days', $dateTs);
                $sundayTs = strtotime('+3 days', $dateTs);
                
                $startBulan = $bulanIndo[(int)date('n', $mondayTs)];
                $endBulan = $bulanIndo[(int)date('n', $sundayTs)];
                
                $weeks[$weekNum] = [
                    'label' => date('d', $mondayTs) . ' ' . $startBulan . ' - ' . date('d', $sundayTs) . ' ' . $endBulan,
                    'start_date' => date('Y-m-d', $mondayTs),
                    'end_date' => date('Y-m-d', $sundayTs)
                ];
                $weekNum++;
            }
        }
        return $weeks;
    }

    public function kinerja()
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        
        // Cek jika tab kinerja dinonaktifkan, redirect ke perilaku atau penilaian 360
        if (isset($pengaturan['tab_kinerja_aktif']) && $pengaturan['tab_kinerja_aktif'] == 0) {
            if (!isset($pengaturan['tab_perilaku_aktif']) || $pengaturan['tab_perilaku_aktif'] == 1) {
                return redirect()->to('/ekip/perilaku');
            } else if (!isset($pengaturan['tab_360_aktif']) || $pengaturan['tab_360_aktif'] == 1) {
                return redirect()->to('/ekip/penilaian_360');
            }
        }

        $minggu_aktif = date('W'); // Fallback
        $filter_minggu = $this->request->getGet('minggu') ?? 'all';
        $status_aktif = $this->request->getGet('status') ?? 'all';
        $tahun_aktif = $this->request->getGet('tahun') ?? date('Y');
        $bulan_aktif = $this->request->getGet('bulan') ?? date('n');

        $isLocked = $this->checkIsLocked($pengaturan); // Global lock as fallback if needed
        $locked_weeks = $this->getLockedWeeksArray($pengaturan, $tahun_aktif, $bulan_aktif);

        // Generate list minggu with date ranges
        $list_minggu = $this->generateMingguList($tahun_aktif, $bulan_aktif);
        
        // Auto-detect current week if today is in the active month and year
        $today = date('Y-m-d');
        if (date('Y') == $tahun_aktif && date('n') == $bulan_aktif) {
            foreach($list_minggu as $num => $w) {
                if ($today >= $w['start_date'] && $today <= $w['end_date']) {
                    $minggu_aktif = $num;
                    break;
                }
            }
        }

        $filter_master_tahun = $this->request->getGet('master_tahun') ?? $tahun_aktif;

        // Terapkan filter bulan dan minggu pada query jika user memilih filter tersebut (jika ada param get, atau default ke waktu berjalan)
        $where_evaluasi = [
            'ekip_evaluasi_mingguan.id_user' => $id_user, 
            'ekip_evaluasi_mingguan.tahun' => $tahun_aktif,
            'ekip_evaluasi_mingguan.bulan' => $bulan_aktif
        ];
        if ($filter_minggu != 'all') {
            $where_evaluasi['ekip_evaluasi_mingguan.minggu_ke'] = $filter_minggu;
        }

        $masterDataAll = $this->masterModel->where('id_user', $id_user)->orderBy('tahun', 'DESC')->findAll();
        
        // Filter master data only for the active year for evaluation form
        $masterDataAktif = array_filter($masterDataAll, function($md) use ($tahun_aktif) {
            return $md['tahun'] == $tahun_aktif;
        });

        // Filter master data for display in the modal based on selected year
        $masterDataFiltered = array_filter($masterDataAll, function($md) use ($filter_master_tahun) {
            return $md['tahun'] == $filter_master_tahun;
        });
        
        // Summary evaluasi per minggu
        $summaryMingguan = $this->evaluasiModel->select('ekip_evaluasi_mingguan.bulan, ekip_evaluasi_mingguan.minggu_ke, SUM(CASE WHEN ekip_evaluasi_mingguan.realisasi != \'\' THEN 1 ELSE 0 END) as jumlah_realisasi, MAX(ekip_evaluasi_mingguan.created_at) as tgl_dibuat, MAX(ekip_ekspektasi_pimpinan.id) as sudah_dinilai, MAX(ekip_ekspektasi_pimpinan.predikat_kinerja) as predikat_kinerja, MAX(ekip_ekspektasi_pimpinan.status_feedback_kinerja) as status_feedback_kinerja, MAX(ekip_ekspektasi_pimpinan.feedback_kinerja) as feedback_kinerja, MAX(ekip_ekspektasi_pimpinan.tanggapan_kinerja) as tanggapan_kinerja, MAX(ekip_ekspektasi_pimpinan.status_feedback_perilaku) as status_feedback_perilaku, MAX(ekip_ekspektasi_pimpinan.feedback_perilaku) as feedback_perilaku, MAX(ekip_ekspektasi_pimpinan.tanggapan_perilaku) as tanggapan_perilaku')
                                               ->join('ekip_ekspektasi_pimpinan', 'ekip_ekspektasi_pimpinan.id_user = ekip_evaluasi_mingguan.id_user AND ekip_ekspektasi_pimpinan.minggu_ke = ekip_evaluasi_mingguan.minggu_ke AND ekip_ekspektasi_pimpinan.tahun = ekip_evaluasi_mingguan.tahun AND ekip_ekspektasi_pimpinan.bulan = ekip_evaluasi_mingguan.bulan', 'left')
                                               ->where($where_evaluasi)
                                               ->groupBy('ekip_evaluasi_mingguan.bulan, ekip_evaluasi_mingguan.minggu_ke')
                                               ->orderBy('ekip_evaluasi_mingguan.bulan', 'ASC')
                                               ->orderBy('ekip_evaluasi_mingguan.minggu_ke', 'ASC')
                                               ->findAll();

        foreach ($summaryMingguan as &$sum) {
            $sum['status_feedback_kinerja'] = $sum['status_feedback_kinerja'] ?? 'none';
            $sum['status_feedback_perilaku'] = $sum['status_feedback_perilaku'] ?? 'none';
        }
        unset($sum);

        // Cek apakah user adalah penilai
        $isPenilai = $this->penilaiModel->where('penilai_id', $id_user)->first();

        $data = [
            'title' => 'E-KIP - Evaluasi Penilaian Kinerja',
            'activeMenu' => 'ekip_kinerja',
            'masterDataFiltered' => $masterDataFiltered,
            'masterDataAktif' => $masterDataAktif,
            'summaryMingguan' => $summaryMingguan,
            'minggu_aktif' => $minggu_aktif,
            'bulan_aktif' => $bulan_aktif,
            'tahun_aktif' => $tahun_aktif,
            'filter_master_tahun' => $filter_master_tahun,
            'pengaturan' => $pengaturan,
            'isLocked' => $isLocked,
            'list_minggu' => $list_minggu,
            'locked_weeks' => $locked_weeks,
            'isPenilai' => $isPenilai ? true : false
        ];
        return view('ekip/kinerja', $data);
    }

    public function perilaku()
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        
        // Cek jika tab perilaku dinonaktifkan, redirect ke kinerja atau penilaian 360
        if (isset($pengaturan['tab_perilaku_aktif']) && $pengaturan['tab_perilaku_aktif'] == 0) {
            if (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1) {
                return redirect()->to('/ekip/kinerja');
            } else if (!isset($pengaturan['tab_360_aktif']) || $pengaturan['tab_360_aktif'] == 1) {
                return redirect()->to('/ekip/penilaian_360');
            }
        }

        $minggu_aktif = date('W'); // Fallback to current week of year if not detected
        $filter_minggu = $this->request->getGet('minggu') ?? 'all';
        $status_aktif = $this->request->getGet('status') ?? 'all';
        $tahun_aktif = $this->request->getGet('tahun') ?? date('Y');
        $bulan_aktif = $this->request->getGet('bulan') ?? date('n');

        $isLocked = $this->checkIsLocked($pengaturan);
        $locked_weeks = $this->getLockedWeeksArray($pengaturan, $tahun_aktif, $bulan_aktif);

        $list_minggu = $this->generateMingguList($tahun_aktif, $bulan_aktif);
        
        // Auto-detect current week if today is in the active month and year
        $today = date('Y-m-d');
        if (date('Y') == $tahun_aktif && date('n') == $bulan_aktif) {
            foreach($list_minggu as $num => $w) {
                if ($today >= $w['start_date'] && $today <= $w['end_date']) {
                    $minggu_aktif = $num;
                    break;
                }
            }
        }

        // Summary perilaku per minggu
        $where_perilaku = [
            'ekip_perilaku_berakhlak.id_user' => $id_user, 
            'ekip_perilaku_berakhlak.tahun' => $tahun_aktif,
            'ekip_perilaku_berakhlak.bulan' => $bulan_aktif
        ];
        if ($filter_minggu != 'all') {
            $where_perilaku['ekip_perilaku_berakhlak.minggu_ke'] = $filter_minggu;
        }

        $summaryPerilakuMingguan = $this->perilakuModel->select('ekip_perilaku_berakhlak.bulan, ekip_perilaku_berakhlak.minggu_ke, MAX(ekip_perilaku_berakhlak.created_at) as tgl_dibuat, MAX(ekip_ekspektasi_pimpinan.id) as sudah_dinilai, MAX(ekip_ekspektasi_pimpinan.predikat_perilaku) as predikat_perilaku, MAX(ekip_ekspektasi_pimpinan.status_feedback_perilaku) as status_feedback_perilaku, MAX(ekip_ekspektasi_pimpinan.feedback_perilaku) as feedback_perilaku, MAX(ekip_ekspektasi_pimpinan.tanggapan_perilaku) as tanggapan_perilaku, MAX(ekip_ekspektasi_pimpinan.status_feedback_kinerja) as status_feedback_kinerja, MAX(ekip_ekspektasi_pimpinan.feedback_kinerja) as feedback_kinerja, MAX(ekip_ekspektasi_pimpinan.tanggapan_kinerja) as tanggapan_kinerja')
                                                       ->join('ekip_ekspektasi_pimpinan', 'ekip_ekspektasi_pimpinan.id_user = ekip_perilaku_berakhlak.id_user AND ekip_ekspektasi_pimpinan.minggu_ke = ekip_perilaku_berakhlak.minggu_ke AND ekip_ekspektasi_pimpinan.tahun = ekip_perilaku_berakhlak.tahun AND ekip_ekspektasi_pimpinan.bulan = ekip_perilaku_berakhlak.bulan', 'left')
                                                       ->where($where_perilaku)
                                                       ->groupBy('ekip_perilaku_berakhlak.bulan, ekip_perilaku_berakhlak.minggu_ke')
                                                       ->orderBy('ekip_perilaku_berakhlak.bulan', 'ASC')
                                                       ->orderBy('ekip_perilaku_berakhlak.minggu_ke', 'ASC')
                                                       ->findAll();

        foreach ($summaryPerilakuMingguan as &$sum) {
            $sum['status_feedback_perilaku'] = $sum['status_feedback_perilaku'] ?? 'none';
            $sum['status_feedback_kinerja'] = $sum['status_feedback_kinerja'] ?? 'none';
        }
        unset($sum);

        // Cek apakah user adalah penilai
        $isPenilai = $this->penilaiModel->where('penilai_id', $id_user)->first();

        $data = [
            'title' => 'E-KIP - Evaluasi Penilaian Perilaku',
            'activeMenu' => 'ekip_perilaku',
            'summaryPerilakuMingguan' => $summaryPerilakuMingguan,
            'minggu_aktif' => $minggu_aktif,
            'bulan_aktif' => $bulan_aktif,
            'tahun_aktif' => $tahun_aktif,
            'isPenilai' => $isPenilai ? true : false,
            'pengaturan' => $pengaturan,
            'isLocked' => $isLocked,
            'list_minggu' => $list_minggu,
            'locked_weeks' => $locked_weeks
        ];
        return view('ekip/perilaku', $data);
    }

    public function getJustifikasiByDinilai($dinilai_id, $minggu_ke)
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $db = \Config\Database::connect();
        $builder = $db->table('ekip_penilaian_360');
        $builder->select('ekip_penilaian_360.justifikasi_deskripsi, ekip_penilaian_360.justifikasi_file, user.nama as nama_penilai')
                ->join('user', 'user.id_user = ekip_penilaian_360.penilai_id')
                ->where('ekip_penilaian_360.dinilai_id', $dinilai_id)
                ->where('ekip_penilaian_360.minggu_ke', $minggu_ke)
                ->where('ekip_penilaian_360.bulan', $bulan)
                ->where('ekip_penilaian_360.tahun', $tahun)
                ->where('(ekip_penilaian_360.justifikasi_deskripsi IS NOT NULL OR ekip_penilaian_360.justifikasi_file IS NOT NULL)')
                ->where('ekip_penilaian_360.justifikasi_deskripsi !=', '')
                ->orderBy('user.nama', 'ASC');
                
        $justifikasi = $builder->get()->getResultArray();
        
        return $this->response->setJSON(['status' => 'success', 'data' => $justifikasi]);
    }

    public function hasil()
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();

        // Cek apakah user adalah Super Admin
        $db = \Config\Database::connect();
        $isSuperAdmin = $db->table('ekip_super_admin')->where('id_user', $id_user)->countAllResults() > 0;

        // Ambil data tim_kerja di mana user ini menjadi PIC
        $picTim = $db->table('ekip_pic_tim_kerja')->where('id_user', $id_user)->get()->getResultArray();
        $isPicTim = count($picTim) > 0;

        $akumulasi360 = [];
        $dataPegawaiTim = [];
        $penilaiData = [];
        $isPenilai = false;
        
        $superAdminDataPegawai = [];
        $superAdminAkumulasi360 = [];
        $semuaTimKerja = [];

        if ($isSuperAdmin) {
            // Ambil semua tim kerja
            $semuaTimKerja = $db->table('tim_kerja')->get()->getResultArray();
            
            // Ambil semua pegawai beserta timnya
            $semuaPegawai = $db->table('data_pegawai')
                               ->select('data_pegawai.id_user as dinilai_id, data_pegawai.id_tim_kerja, user.nama, user.username as nip')
                               ->join('user', 'user.id_user = data_pegawai.id_user')
                               ->get()->getResultArray();
                               
            foreach($semuaPegawai as $sp) {
                $superAdminDataPegawai[$sp['id_tim_kerja']][] = $sp;
            }

            // Ambil semua data 360
            $builder360 = $db->table('ekip_penilaian_360');
            $hasil360All = $builder360->select('dinilai_id, tahun, bulan, minggu_ke, AVG(hasil_pekerjaan) as avg_hasil, AVG(kualitas_pekerjaan) as avg_kualitas, AVG(ketepatan_waktu) as avg_waktu, AVG(efektivitas_efesiensi) as avg_efektivitas, AVG(tanggung_jawab) as avg_tanggung_jawab, AVG(kerjasama) as avg_kerjasama, AVG(presensi) as avg_presensi, AVG(jumlah) as total_jumlah, MAX(CASE WHEN (justifikasi_deskripsi IS NOT NULL AND justifikasi_deskripsi != \'\') OR (justifikasi_file IS NOT NULL AND justifikasi_file != \'\') THEN 1 ELSE 0 END) as has_justifikasi')
                                   ->groupBy('dinilai_id, tahun, bulan, minggu_ke')
                                   ->get()->getResultArray();

            foreach ($hasil360All as $h) {
                $superAdminAkumulasi360[$h['dinilai_id']][$h['minggu_ke']] = $h;
            }
        }

        if ($isPicTim) {
            $id_tims = array_column($picTim, 'id_tim_kerja');
            
            // Ambil semua pegawai yang ada di tim-tim tersebut
            $pegawaiTim = $db->table('data_pegawai')
                             ->select('data_pegawai.id_user as dinilai_id, user.nama, user.username as nip')
                             ->join('user', 'user.id_user = data_pegawai.id_user')
                             ->whereIn('data_pegawai.id_tim_kerja', $id_tims)
                             ->get()->getResultArray();
                             
            // Pastikan unique (jika ada pegawai yang rangkap jabatan di beberapa tim, meski jarang)
            $temp = [];
            foreach ($pegawaiTim as $pt) {
                $temp[$pt['dinilai_id']] = $pt;
            }
            $dataPegawaiTim = array_values($temp);
            
            // Set penilaiData sama dengan dataPegawaiTim jika dia PIC tim
            $penilaiData = $dataPegawaiTim;
            $isPenilai = count($penilaiData) > 0;
        } else {
            // Data Pegawai yang dinilai (jika bukan PIC Tim, cek tabel penilai)
            $penilaiData = $this->penilaiModel->select('ekip_penilai.id as id_penilai, ekip_penilai.dinilai_id, user.nama, user.username as nip')
                                              ->join('user', 'user.id_user = ekip_penilai.dinilai_id')
                                              ->where('ekip_penilai.penilai_id', $id_user)
                                              ->findAll();
            $isPenilai = count($penilaiData) > 0;
            $dataPegawaiTim = $penilaiData; // Assign for Tab Hasil 360 compatibility
        }

        if ($isPicTim || $isPenilai) {
            $dinilai_ids = array_column($dataPegawaiTim, 'dinilai_id');
            if (!empty($dinilai_ids)) {
                $builder360 = $db->table('ekip_penilaian_360');
                $hasil360 = $builder360->select('dinilai_id, tahun, bulan, minggu_ke, AVG(hasil_pekerjaan) as avg_hasil, AVG(kualitas_pekerjaan) as avg_kualitas, AVG(ketepatan_waktu) as avg_waktu, AVG(efektivitas_efesiensi) as avg_efektivitas, AVG(tanggung_jawab) as avg_tanggung_jawab, AVG(kerjasama) as avg_kerjasama, AVG(presensi) as avg_presensi, AVG(jumlah) as total_jumlah, MAX(CASE WHEN (justifikasi_deskripsi IS NOT NULL AND justifikasi_deskripsi != \'\') OR (justifikasi_file IS NOT NULL AND justifikasi_file != \'\') THEN 1 ELSE 0 END) as has_justifikasi')
                                       ->whereIn('dinilai_id', $dinilai_ids)
                                       ->groupBy('dinilai_id, tahun, bulan, minggu_ke')
                                       ->get()->getResultArray();
                                       
                foreach ($hasil360 as $h) {
                    $akumulasi360[$h['dinilai_id']][$h['minggu_ke']] = $h;
                }

                // Ambil data override final 360
                $final360 = $db->table('ekip_penilaian_360_final')
                               ->whereIn('dinilai_id', $dinilai_ids)
                               ->get()->getResultArray();
                foreach ($final360 as $f) {
                    // Jika ada nilai final (override), timpa nilai rata-rata yang di array akumulasi
                    // Kita menimpa properti yang sama agar UI membaca data ini alih-alih rata-rata sistem
                    $akumulasi360[$f['dinilai_id']][$f['minggu_ke']] = [
                        'dinilai_id' => $f['dinilai_id'],
                        'minggu_ke' => $f['minggu_ke'],
                        'bulan' => $f['bulan'],
                        'tahun' => $f['tahun'],
                        'avg_hasil' => $f['hasil_pekerjaan'],
                        'avg_kualitas' => $f['kualitas_pekerjaan'],
                        'avg_waktu' => $f['ketepatan_waktu'],
                        'avg_efektivitas' => $f['efektivitas_efesiensi'],
                        'avg_tanggung_jawab' => $f['tanggung_jawab'],
                        'avg_kerjasama' => $f['kerjasama'],
                        'avg_presensi' => $f['presensi'],
                        'total_jumlah' => $f['jumlah'],
                        'has_justifikasi' => $akumulasi360[$f['dinilai_id']][$f['minggu_ke']]['has_justifikasi'] ?? 0 // Pertahankan status justifikasi dari rata-rata jika ada
                    ];
                }
            }
        }



        $minggu_aktif = date('W'); // Fallback
        $status_aktif = $this->request->getGet('status') ?? 'all';
        $tahun_aktif = $this->request->getGet('tahun') ?? date('Y');
        $bulan_aktif = $this->request->getGet('bulan') ?? date('n');

        $statusPenilaian = [];
        $statusPenilaianData = [];
        $statusKinerjaPegawai = [];
        $statusPerilakuPegawai = [];
        $statusObservasiKhusus = [];
        
        $list_minggu = $this->generateMingguList($tahun_aktif, $bulan_aktif);
        
        // Auto-detect current week if today is in the active month and year
        $today = date('Y-m-d');
        if (date('Y') == $tahun_aktif && date('n') == $bulan_aktif) {
            foreach($list_minggu as $num => $w) {
                if ($today >= $w['start_date'] && $today <= $w['end_date']) {
                    $minggu_aktif = $num;
                    break;
                }
            }
        }
        
        // Filter logic for minggu_aktif in controller directly from request
        $minggu_aktif_filter = $this->request->getGet('minggu') ?? $minggu_aktif;
        $minggu_aktif = $minggu_aktif_filter;

        if ($isSuperAdmin) {
            $dinilai_ids = array_column($semuaPegawai, 'dinilai_id');
        } elseif ($isPenilai) {
            $dinilai_ids = array_column($penilaiData, 'dinilai_id');
        } else {
            $dinilai_ids = [];
        }

        if (!empty($dinilai_ids)) {
            // Cek status penilaian ekspektasi pimpinan
            $eks = $db->table('ekip_ekspektasi_pimpinan')
                      ->whereIn('id_user', $dinilai_ids)
                      ->where('tahun', $tahun_aktif)
                      ->where('bulan', $bulan_aktif)
                      ->get()->getResultArray();
            foreach ($eks as $e) {
                $statusPenilaian[$e['id_user']][$e['tahun']][$e['bulan']][$e['minggu_ke']] = 'done';
                // Save the predikat data
                $statusPenilaianData[$e['id_user']][$e['tahun']][$e['bulan']][$e['minggu_ke']] = [
                    'predikat_kinerja' => $e['predikat_kinerja'],
                    'predikat_perilaku' => $e['predikat_perilaku'],
                    'status_feedback_kinerja' => $e['status_feedback_kinerja'] ?? 'none',
                    'feedback_kinerja' => $e['feedback_kinerja'],
                    'tanggapan_kinerja' => $e['tanggapan_kinerja'],
                    'status_feedback_perilaku' => $e['status_feedback_perilaku'] ?? 'none',
                    'feedback_perilaku' => $e['feedback_perilaku'],
                    'tanggapan_perilaku' => $e['tanggapan_perilaku']
                ];
            }

            // Cek apakah pegawai sudah mengisi Kinerja
            $kinerja = $db->table('ekip_evaluasi_mingguan')
                          ->select('id_user, tahun, bulan, minggu_ke')
                          ->whereIn('id_user', $dinilai_ids)
                          ->where('tahun', $tahun_aktif)
                          ->where('bulan', $bulan_aktif)
                          ->groupBy('id_user, tahun, bulan, minggu_ke')
                          ->get()->getResultArray();
            foreach ($kinerja as $k) {
                $statusKinerjaPegawai[$k['id_user']][$k['tahun']][$k['bulan']][$k['minggu_ke']] = true;
            }

            // Cek apakah pegawai sudah mengisi Perilaku
            $perilaku = $db->table('ekip_perilaku_berakhlak')
                           ->select('id_user, tahun, bulan, minggu_ke')
                           ->whereIn('id_user', $dinilai_ids)
                           ->where('tahun', $tahun_aktif)
                           ->where('bulan', $bulan_aktif)
                           ->groupBy('id_user, tahun, bulan, minggu_ke')
                           ->get()->getResultArray();
            foreach ($perilaku as $p) {
                $statusPerilakuPegawai[$p['id_user']][$p['tahun']][$p['bulan']][$p['minggu_ke']] = true;
            }
            
            // Cek status observasi ekspektasi khusus (hanya jika ada nilainya)
            $observasi = $db->table('ekip_ekspektasi_khusus_pimpinan')
                           ->select('dinilai_id as id_user, tahun, bulan, minggu_ke')
                           ->whereIn('dinilai_id', $dinilai_ids)
                           ->where('tahun', $tahun_aktif)
                           ->where('bulan', $bulan_aktif)
                           ->where('(TRIM(berorientasi_pelayanan) != "" OR TRIM(akuntabel) != "" OR TRIM(kompeten) != "" OR TRIM(harmonis) != "" OR TRIM(loyal) != "" OR TRIM(adaptif) != "" OR TRIM(kolaboratif) != "")')
                           ->groupBy('dinilai_id, tahun, bulan, minggu_ke')
                           ->get()->getResultArray();
            foreach ($observasi as $o) {
                $statusObservasiKhusus[$o['id_user']][$o['tahun']][$o['bulan']][$o['minggu_ke']] = true;
            }

            // Cek status rekomendasi (tugas tambahan)
            $rekomendasi = $db->table('ekip_rekomendasi_kebijakan')
                           ->select('dinilai_id as id_user, tahun, bulan, minggu_ke')
                           ->whereIn('dinilai_id', $dinilai_ids)
                           ->where('tahun', $tahun_aktif)
                           ->where('bulan', $bulan_aktif)
                           ->groupBy('dinilai_id, tahun, bulan, minggu_ke')
                           ->get()->getResultArray();
            foreach ($rekomendasi as $r) {
                $statusRekomendasi[$r['id_user']][$r['tahun']][$r['bulan']][$r['minggu_ke']] = true;
            }

            // Ambil data Kalibrasi
            $kalibrasi = $db->table('ekip_kalibrasi_perilaku')
                           ->select('dinilai_id as id_user, tahun, bulan, minggu_ke, grand_total')
                           ->whereIn('dinilai_id', $dinilai_ids)
                           ->where('tahun', $tahun_aktif)
                           ->where('bulan', $bulan_aktif)
                           ->get()->getResultArray();
            foreach ($kalibrasi as $k) {
                $statusKalibrasi[$k['id_user']][$k['tahun']][$k['bulan']][$k['minggu_ke']] = $k['grand_total'];
            }
        }

        $data = [
            'title' => 'E-KIP - Evaluasi Hasil Penilaian',
            'activeMenu' => 'ekip_hasil',
            'isPenilai' => $isPenilai ? true : false,
            'isPicTim' => $isPicTim,
            'isSuperAdmin' => $isSuperAdmin,
            'penilaiData' => $penilaiData,
            'dataPegawaiTim' => $dataPegawaiTim,
            'akumulasi360' => $akumulasi360,
            'superAdminDataPegawai' => $superAdminDataPegawai,
            'superAdminAkumulasi360' => $superAdminAkumulasi360,
            'semuaTimKerja' => $semuaTimKerja,
            'pengaturan' => $pengaturan,
            'minggu_aktif' => $minggu_aktif,
            'status_aktif' => $status_aktif,
            'bulan_aktif' => $bulan_aktif,
            'tahun_aktif' => $tahun_aktif,
            'list_minggu' => $list_minggu,
            'statusPenilaian' => $statusPenilaian,
            'statusPenilaianData' => $statusPenilaianData,
            'statusKinerjaPegawai' => $statusKinerjaPegawai,
            'statusPerilakuPegawai' => $statusPerilakuPegawai,
            'statusObservasiKhusus' => $statusObservasiKhusus,
            'statusRekomendasi' => $statusRekomendasi ?? [],
            'statusKalibrasi' => $statusKalibrasi ?? []
        ];
        return view('ekip/hasil', $data);
    }

    public function pengaturan()
    {
        $pengaturan = $this->pengaturanModel->first();
        $users = $this->userModel->findAll();
        
        $penugasan = $this->penilaiModel->select('ekip_penilai.id, penilai.nama as nama_penilai, dinilai.nama as nama_dinilai')
                                        ->join('user as penilai', 'penilai.id_user = ekip_penilai.penilai_id')
                                        ->join('user as dinilai', 'dinilai.id_user = ekip_penilai.dinilai_id')
                                        ->findAll();

        $db = \Config\Database::connect();
        $timKerja = $db->table('tim_kerja')->get()->getResultArray();
        $picTimKerja = $db->table('ekip_pic_tim_kerja')
                          ->select('ekip_pic_tim_kerja.id, tim_kerja.nama_tim_kerja, user.nama as nama_pic')
                          ->join('tim_kerja', 'tim_kerja.id_tim_kerja = ekip_pic_tim_kerja.id_tim_kerja')
                          ->join('user', 'user.id_user = ekip_pic_tim_kerja.id_user')
                          ->get()->getResultArray();

        $superAdmins = $db->table('ekip_super_admin')
                          ->select('ekip_super_admin.id, user.nama as nama_admin')
                          ->join('user', 'user.id_user = ekip_super_admin.id_user')
                          ->get()->getResultArray();

        $data = [
            'title' => 'E-KIP - Pengaturan',
            'activeMenu' => 'ekip_pengaturan',
            'pengaturan' => $pengaturan,
            'users' => $users,
            'penugasan' => $penugasan,
            'timKerja' => $timKerja,
            'picTimKerja' => $picTimKerja,
            'superAdmins' => $superAdmins
        ];
        return view('ekip/pengaturan', $data);
    }

    public function penilaian_360()
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        
        // Cek jika tab 360 dinonaktifkan, redirect ke kinerja atau perilaku
        if (isset($pengaturan['tab_360_aktif']) && $pengaturan['tab_360_aktif'] == 0) {
            if (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1) {
                return redirect()->to('/ekip/kinerja');
            } else if (!isset($pengaturan['tab_perilaku_aktif']) || $pengaturan['tab_perilaku_aktif'] == 1) {
                return redirect()->to('/ekip/perilaku');
            }
        }
        
        $tahun_aktif = $this->request->getGet('tahun') ?? date('Y');
        $bulan_aktif = $this->request->getGet('bulan') ?? date('n');
        
        $list_minggu = $this->generateMingguList($tahun_aktif, $bulan_aktif);
        $minggu_aktif = date('W'); // Fallback
        
        $today = date('Y-m-d');
        if (date('Y') == $tahun_aktif && date('n') == $bulan_aktif) {
            foreach($list_minggu as $num => $w) {
                if ($today >= $w['start_date'] && $today <= $w['end_date']) {
                    $minggu_aktif = $num;
                    break;
                }
            }
        }
        
        // Ambil data tim_kerja user yang login
        $db = \Config\Database::connect();
        $builder = $db->table('data_pegawai');
        $pegawaiSaya = $builder->where('id_user', $id_user)->get()->getRowArray();
        
        $temanTim = [];
        if ($pegawaiSaya && $pegawaiSaya['id_tim_kerja']) {
            // Ambil semua pegawai dengan id_tim_kerja yang sama, kecuali user sendiri (atau termasuk user sendiri, sesuai kebutuhan. Di sini kita tampilkan semua termasuk dirinya sendiri jika mau, atau kecualikan)
            // Asumsi: Menilai diri sendiri tidak perlu, tapi kita kecualikan saja. Atau sertakan.
            $temanTim = $builder->select('data_pegawai.*, user.nama as nama_user, user.username as nip')
                                ->join('user', 'user.id_user = data_pegawai.id_user')
                                ->where('data_pegawai.id_tim_kerja', $pegawaiSaya['id_tim_kerja'])
                                ->where('data_pegawai.id_user !=', $id_user) // Exclude self
                                ->get()->getResultArray();
        }

        // Ambil penilaian 360 yang sudah dilakukan user ini di tahun berjalan
        $dataPenilaian = [];
        if (!empty($temanTim)) {
            $dinilai_ids = array_column($temanTim, 'id_user');
            $penilaianSaya = $db->table('ekip_penilaian_360')
                                ->where('penilai_id', $id_user)
                                ->whereIn('dinilai_id', $dinilai_ids)
                                ->where('tahun', $tahun_aktif)
                                ->where('bulan', $bulan_aktif)
                                ->get()->getResultArray();
            
            foreach ($penilaianSaya as $p) {
                // Determine if this specific entry has justification
                $p['has_justifikasi'] = ((isset($p['justifikasi_deskripsi']) && trim($p['justifikasi_deskripsi']) !== '') || (isset($p['justifikasi_file']) && trim($p['justifikasi_file']) !== '')) ? 1 : 0;
                $dataPenilaian[$p['dinilai_id']][$p['minggu_ke']] = $p;
            }
        }

        $pengaturan = $this->pengaturanModel->first();

        // Cek apakah user adalah penilai
        $isPenilai = $this->penilaiModel->where('penilai_id', $id_user)->first();

        $data = [
            'title' => 'E-KIP - Penilaian 360',
            'activeMenu' => 'ekip_penilaian_360',
            'tahun_aktif' => $tahun_aktif,
            'bulan_aktif' => $bulan_aktif,
            'minggu_aktif' => $minggu_aktif,
            'list_minggu' => $list_minggu,
            'temanTim' => $temanTim,
            'dataPenilaian' => $dataPenilaian,
            'pengaturan' => $pengaturan,
            'isPenilai' => $isPenilai ? true : false
        ];
        return view('ekip/penilaian_360', $data);
    }

    public function kalibrasi()
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        
        // Cek apakah user adalah penilai
        $isPenilai = $this->penilaiModel->where('penilai_id', $id_user)->first();
        
        if (!$isPenilai) {
            return redirect()->to('/ekip/kinerja')->with('error', 'Anda tidak memiliki akses ke halaman Kalibrasi.');
        }

        $tahun_aktif = $pengaturan['tahun_aktif'] ?? date('Y');

        $db = \Config\Database::connect();
        $dinilai_list = $db->table('ekip_penilai')
                           ->select('ekip_penilai.dinilai_id, user.nama, user.username as nip')
                           ->join('user', 'user.id_user = ekip_penilai.dinilai_id')
                           ->where('ekip_penilai.penilai_id', $id_user)
                           ->get()->getResultArray();

        $data = [
            'title' => 'E-KIP - Kalibrasi',
            'activeMenu' => 'ekip_hasil', // Set active menu to hasil so the sidebar highlights the right section
            'pengaturan' => $pengaturan,
            'isPenilai' => true,
            'dinilai_list' => $dinilai_list,
            'selected_id' => $this->request->getGet('id'), // Pass selected ID from URL if any
        ];
        return view('ekip/kalibrasi', $data);
    }

    public function getKalibrasiData()
    {
        $id_user = session()->get('id_user');
        $id_dinilai = $this->request->getGet('id_dinilai');
        $minggu = $this->request->getGet('minggu');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $db = \Config\Database::connect();
        $existing = $db->table('ekip_kalibrasi_perilaku')
                       ->where('penilai_id', $id_user)
                       ->where('dinilai_id', $id_dinilai)
                       ->where('minggu_ke', $minggu)
                       ->where('bulan', $bulan)
                       ->where('tahun', $tahun)
                       ->get()->getRowArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $existing]);
    }

    public function simpanKalibrasi()
    {
        $id_user = session()->get('id_user');
        $id_dinilai = $this->request->getPost('id_dinilai');
        $minggu = $this->request->getPost('minggu');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        $data = [
            'penilai_id' => $id_user,
            'dinilai_id' => $id_dinilai,
            'minggu_ke' => $minggu,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'loyalitas' => $this->request->getPost('nilai_loyalitas'),
            'cara_kerja' => $this->request->getPost('nilai_cara_kerja'),
            'target_kinerja' => $this->request->getPost('nilai_target_kinerja'),
            'tata_kelola' => $this->request->getPost('nilai_tata_kelola'),
            'lingkungan' => $this->request->getPost('nilai_lingkungan'),
            'grand_total' => $this->request->getPost('grand_total'),
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('ekip_kalibrasi_perilaku');
        
        // Cek apakah sudah ada
        $existing = $builder->where('penilai_id', $id_user)
                            ->where('dinilai_id', $id_dinilai)
                            ->where('minggu_ke', $minggu)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->get()->getRow();

        if ($existing) {
            $builder->where('id', $existing->id)->update($data);
        } else {
            $builder->insert($data);
        }

        // --- Konversi Nilai Kalibrasi ke Isian Perilaku ---
        $grandTotal = (float)$this->request->getPost('grand_total');
        $konversi = '-';
        if ($grandTotal < 70) {
            $konversi = 'Kurang dan Sangat Kurang';
        } else if ($grandTotal >= 70 && $grandTotal < 90) {
            $konversi = 'Butuh Perbaikan';
        } else if ($grandTotal >= 90 && $grandTotal < 110) {
            $konversi = 'Baik';
        } else if ($grandTotal >= 110) {
            $konversi = 'Baik Sekali';
        }

        $builderEks = $db->table('ekip_ekspektasi_pimpinan');
        $existingEks = $builderEks->where('id_user', $id_dinilai)
                                  ->where('minggu_ke', $minggu)
                                  ->where('bulan', $bulan)
                                  ->where('tahun', $tahun)
                                  ->get()->getRow();

        if ($existingEks) {
            $builderEks->where('id', $existingEks->id)->update([
                'predikat_perilaku' => $konversi,
                'penilai_id' => $id_user
            ]);
        } else {
            $builderEks->insert([
                'id_user' => $id_dinilai,
                'minggu_ke' => $minggu,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'predikat_perilaku' => $konversi,
                'penilai_id' => $id_user
            ]);
        }
        // --------------------------------------------------

        return $this->response->setJSON(['status' => 'success', 'message' => 'Data kalibrasi berhasil disimpan.']);
    }

    public function simpanPenilaian360Final()
    {
        $id_user = session()->get('id_user');
        $id_dinilai = $this->request->getPost('id_dinilai');
        $minggu_ke = $this->request->getPost('minggu_ke');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        $data = [
            'penilai_id' => $id_user,
            'dinilai_id' => $id_dinilai,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hasil_pekerjaan' => $this->request->getPost('hasil_pekerjaan'),
            'kualitas_pekerjaan' => $this->request->getPost('kualitas_pekerjaan'),
            'ketepatan_waktu' => $this->request->getPost('ketepatan_waktu'),
            'efektivitas_efesiensi' => $this->request->getPost('efektivitas_efesiensi'),
            'tanggung_jawab' => $this->request->getPost('tanggung_jawab'),
            'kerjasama' => $this->request->getPost('kerjasama'),
            'presensi' => $this->request->getPost('presensi'),
            'jumlah' => $this->request->getPost('jumlah')
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('ekip_penilaian_360_final');
        
        $existing = $builder->where('dinilai_id', $id_dinilai)
                            ->where('minggu_ke', $minggu_ke)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->get()->getRow();

        if ($existing) {
            $builder->where('id', $existing->id)->update($data);
        } else {
            $builder->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Nilai final 360 berhasil disimpan.']);
    }
}
