<?php

namespace App\Controllers\Ekip;

use App\Controllers\BaseController;
use App\Models\Ekip\EkipMasterKinerjaModel;
use App\Models\Ekip\EkipEvaluasiMingguanModel;
use App\Models\Ekip\EkipPerilakuBerakhlakModel;
use App\Models\Ekip\EkipEkspektasiPimpinanModel;
use App\Models\Ekip\EkipEkspektasiKhususPimpinanModel;
use App\Models\Ekip\EkipRekomendasiKebijakanModel;
use App\Models\Ekip\EkipPenilaiModel;
use App\Models\Ekip\EkipPengaturanModel;

class Crud extends BaseController
{
    protected $masterModel;
    protected $evaluasiModel;
    protected $perilakuModel;
    protected $ekspektasiModel;
    protected $ekspektasiKhususModel;
    protected $rekomendasiModel;
    protected $penilaiModel;
    protected $pengaturanModel;

    public function __construct()
    {
        $this->masterModel = new EkipMasterKinerjaModel();
        $this->evaluasiModel = new EkipEvaluasiMingguanModel();
        $this->perilakuModel = new EkipPerilakuBerakhlakModel();
        $this->ekspektasiModel = new EkipEkspektasiPimpinanModel();
        $this->ekspektasiKhususModel = new EkipEkspektasiKhususPimpinanModel();
        $this->rekomendasiModel = new EkipRekomendasiKebijakanModel();
        $this->penilaiModel = new EkipPenilaiModel();
        $this->pengaturanModel = new EkipPengaturanModel();
    }

    private function isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)
    {
        if (isset($pengaturan['akses_pegawai']) && $pengaturan['akses_pegawai'] == 1) {
            return false;
        }

        $hari_buka = (int)($pengaturan['jadwal_buka_hari'] ?? 1);
        $hari_tutup = (int)($pengaturan['jadwal_tutup_hari'] ?? 5);
        $jam_tutup = $pengaturan['jadwal_tutup_jam'] ?? '16:00:00';

        $dataController = new \App\Controllers\Ekip\Data();
        $list_minggu = $dataController->generateMingguList($tahun, $bulan);

        if (!isset($list_minggu[$minggu_ke])) return false;

        $sunday_date = $list_minggu[$minggu_ke]['end_date'];
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

        return (time() < $open_ts || time() > $deadline_ts);
    }

    public function simpanMasterKinerja()
    {
        $id_user = session()->get('id_user');
        $id = $this->request->getPost('id');
        
        // Use provided year, or fallback to current year
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        
        $data = [
            'id_user' => $id_user,
            'tahun' => $tahun,
            'jenis' => $this->request->getPost('jenis'),
            'bobot' => $this->request->getPost('bobot'),
            'rencana_hasil_kerja' => $this->request->getPost('rencana_hasil_kerja'),
            'iki' => $this->request->getPost('iki'),
            'target' => $this->request->getPost('target'),
        ];

        if ($id) {
            $this->masterModel->update($id, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Master berhasil diubah']);
        } else {
            $this->masterModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Master berhasil ditambahkan']);
        }
    }

    public function hapusMasterKinerja($id)
    {
        $this->masterModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data Master berhasil dihapus']);
    }

    public function simpanEvaluasiMingguan()
    {
        $pengaturan = $this->pengaturanModel->first();
        
        $id_user = session()->get('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke');
        
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');

        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input evaluasi untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $realisasi_data = $this->request->getPost('realisasi') ?? []; // Array: master_id => text
        $link_bukti_data = $this->request->getPost('link_bukti') ?? []; // Array: master_id => text

        $realisasi_rek_data = $this->request->getPost('realisasi_rek') ?? []; // Array: rek_id => text
        $link_bukti_rek_data = $this->request->getPost('link_bukti_rek') ?? []; // Array: rek_id => text

        $mode = $this->request->getPost('mode');

        if (empty($realisasi_data) && empty($realisasi_rek_data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang disimpan']);
        }

        // Validate mode create
        if ($mode == 'create') {
            $existing_count = $this->evaluasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->countAllResults();

            if ($existing_count > 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Evaluasi untuk minggu ini sudah dibuat. Silakan gunakan tombol Edit.']);
            }
        }

        foreach ($realisasi_data as $master_id => $realisasi) {
            // Kita tetap simpan meskipun kosong, agar record minggu tersebut terbentuk dan muncul di tabel Evaluasi Mingguan
            // if (empty(trim($realisasi))) continue; 

            $link_bukti = isset($link_bukti_data[$master_id]) ? $link_bukti_data[$master_id] : null;

            $existing = $this->evaluasiModel->where([
                'id_user' => $id_user,
                'master_kinerja_id' => $master_id,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            $data = [
                'id_user' => $id_user,
                'master_kinerja_id' => $master_id,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'realisasi' => $realisasi,
                'link_bukti' => $link_bukti
            ];

            if ($existing) {
                $this->evaluasiModel->update($existing['id'], $data);
            } else {
                $this->evaluasiModel->insert($data);
            }
        }

        foreach ($realisasi_rek_data as $rek_id => $realisasi) {
            $link_bukti = isset($link_bukti_rek_data[$rek_id]) ? $link_bukti_rek_data[$rek_id] : null;

            $existing = $this->evaluasiModel->where([
                'id_user' => $id_user,
                'rekomendasi_id' => $rek_id,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            $data = [
                'id_user' => $id_user,
                'rekomendasi_id' => $rek_id,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'realisasi' => $realisasi,
                'link_bukti' => $link_bukti
            ];

            if ($existing) {
                $this->evaluasiModel->update($existing['id'], $data);
            } else {
                $this->evaluasiModel->insert($data);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Evaluasi Mingguan berhasil disimpan']);
    }

    public function hapusEvaluasiMingguan($id)
    {
        $eval = $this->evaluasiModel->find($id);
        if ($eval) {
            $pengaturan = $this->pengaturanModel->first();
            if ($this->isWeekLocked($pengaturan, $eval['tahun'], $eval['bulan'], $eval['minggu_ke'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input evaluasi telah habis.']);
            }
            $this->evaluasiModel->delete($id);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Evaluasi mingguan berhasil dihapus']);
    }

    public function getEvaluasiMingguan($minggu_ke)
    {
        $id_user = session()->get('id_user');
        
        return $this->getEvaluasiMingguanPegawai($minggu_ke, $id_user);
    }

    public function getEvaluasiMingguanPegawai($minggu_ke, $id_user)
    {
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('n');

        // Get Evaluasi from Master Kinerja
        $evaluasiMaster = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*, ekip_master_kinerja.jenis, ekip_master_kinerja.bobot, ekip_master_kinerja.rencana_hasil_kerja, ekip_master_kinerja.iki, ekip_master_kinerja.target')
            ->join('ekip_master_kinerja', 'ekip_master_kinerja.id = ekip_evaluasi_mingguan.master_kinerja_id')
            ->where([
                'ekip_evaluasi_mingguan.id_user' => $id_user,
                'ekip_evaluasi_mingguan.minggu_ke' => $minggu_ke,
                'ekip_evaluasi_mingguan.bulan' => $bulan,
                'ekip_evaluasi_mingguan.tahun' => $tahun
            ])->findAll();

        // Get Evaluasi from Rekomendasi Kebijakan
        $evaluasiRekomendasi = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*, "Tambahan" as jenis, "-" as bobot, ekip_rekomendasi_kebijakan.rencana_hasil_kerja, ekip_rekomendasi_kebijakan.iki, ekip_rekomendasi_kebijakan.target')
            ->join('ekip_rekomendasi_kebijakan', 'ekip_rekomendasi_kebijakan.id = ekip_evaluasi_mingguan.rekomendasi_id')
            ->where([
                'ekip_evaluasi_mingguan.id_user' => $id_user,
                'ekip_evaluasi_mingguan.minggu_ke' => $minggu_ke,
                'ekip_evaluasi_mingguan.bulan' => $bulan,
                'ekip_evaluasi_mingguan.tahun' => $tahun
            ])->findAll();

        $evaluasi = array_merge($evaluasiMaster, $evaluasiRekomendasi);

        $evaluasiByMaster = [];
        $evaluasiByRekomendasi = [];
        foreach ($evaluasiMaster as $ev) {
            $evaluasiByMaster[$ev['master_kinerja_id']] = $ev;
        }
        foreach ($evaluasiRekomendasi as $ev) {
            $evaluasiByRekomendasi[$ev['rekomendasi_id']] = $ev;
        }

        $ekspektasi = $this->ekspektasiModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        return $this->response->setJSON(['status' => 'success', 'data' => $evaluasiByMaster, 'data_rek' => $evaluasiByRekomendasi, 'list' => $evaluasi, 'ekspektasi' => $ekspektasi]);
    }

    public function getEvaluasiMingguanSebelumnya()
    {
        $id_user = session()->get('id_user');
        $minggu_ke = $this->request->getGet('minggu');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        // Determine previous week
        $prev_minggu = $minggu_ke - 1;
        $prev_bulan = $bulan;
        $prev_tahun = $tahun;

        if ($prev_minggu < 1) {
            $prev_bulan--;
            if ($prev_bulan < 1) {
                $prev_bulan = 12;
                $prev_tahun--;
            }
            // Asumsikan bulan sebelumnya punya 5 minggu sebagai batas atas pengecekan (nanti kita ambil yang ada isinya)
            $prev_minggu = 5; 
        }

        // Cari evaluasi terakhir yang disubmit sebelum atau sama dengan prev_minggu/bulan/tahun
        $evaluasiMaster = [];
        $evaluasiRekomendasi = [];
        
        // Kita loop mundur maksimum 5 minggu ke belakang untuk cari data terdekat jika prev_minggu kosong
        for ($i = 0; $i < 5; $i++) {
            $evaluasiMaster = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*')
                ->where([
                    'id_user' => $id_user,
                    'minggu_ke' => $prev_minggu,
                    'bulan' => $prev_bulan,
                    'tahun' => $prev_tahun,
                    'master_kinerja_id !=' => null
                ])->findAll();

            $evaluasiRekomendasi = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*')
                ->where([
                    'id_user' => $id_user,
                    'minggu_ke' => $prev_minggu,
                    'bulan' => $prev_bulan,
                    'tahun' => $prev_tahun,
                    'rekomendasi_id !=' => null
                ])->findAll();

            if (count($evaluasiMaster) > 0 || count($evaluasiRekomendasi) > 0) {
                break; // Ketemu data!
            }

            // Mundur 1 minggu lagi
            $prev_minggu--;
            if ($prev_minggu < 1) {
                $prev_bulan--;
                if ($prev_bulan < 1) {
                    $prev_bulan = 12;
                    $prev_tahun--;
                }
                $prev_minggu = 5;
            }
        }

        $evaluasiByMaster = [];
        $evaluasiByRekomendasi = [];
        foreach ($evaluasiMaster as $ev) {
            $evaluasiByMaster[$ev['master_kinerja_id']] = $ev;
        }
        foreach ($evaluasiRekomendasi as $ev) {
            $evaluasiByRekomendasi[$ev['rekomendasi_id']] = $ev;
        }

        if (count($evaluasiByMaster) == 0 && count($evaluasiByRekomendasi) == 0) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'Tidak ada data di minggu sebelumnya']);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $evaluasiByMaster, 'data_rek' => $evaluasiByRekomendasi]);
    }

    public function hapusMinggu($minggu_ke)
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('n');

        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu hapus evaluasi untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $this->evaluasiModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Seluruh evaluasi pada minggu tersebut berhasil dihapus']);
    }

    public function simpanPerilakuBerakhlak()
    {
        $pengaturan = $this->pengaturanModel->first();
        
        $id_user = session()->get('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke'); // Dapatkan dari form
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');

        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input evaluasi perilaku untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        if (!$minggu_ke) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Minggu belum dipilih']);
        }

        $mode = $this->request->getPost('mode');
        if ($mode == 'create') {
            $existing_count = $this->perilakuModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->countAllResults();

            if ($existing_count > 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Penilaian perilaku untuk minggu ini sudah dibuat. Silakan gunakan tombol Edit.']);
            }
        }

        $existing = $this->perilakuModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        $data = [
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pelayanan' => $this->request->getPost('pelayanan'),
            'akuntabel' => $this->request->getPost('akuntabel'),
            'kompeten' => $this->request->getPost('kompeten'),
            'harmonis' => $this->request->getPost('harmonis'),
            'loyal' => $this->request->getPost('loyal'),
            'adaptif' => $this->request->getPost('adaptif'),
            'kolaboratif' => $this->request->getPost('kolaboratif'),
        ];

        if ($existing) {
            $this->perilakuModel->update($existing['id'], $data);
        } else {
            $this->perilakuModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Perilaku BerAKHLAK berhasil disimpan']);
    }

    public function getPerilakuMingguan($minggu_ke)
    {
        $id_user = session()->get('id_user');
        return $this->getPerilakuMingguanPegawai($minggu_ke, $id_user);
    }

    public function getPerilakuMingguanPegawai($minggu_ke, $id_user)
    {
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $penilai_id = session()->get('id_user');

        $perilaku = $this->perilakuModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        // Ambil data ekspektasi (penilaian) jika ada
        $ekspektasi = $this->ekspektasiModel->where([
            'id_user' => $id_user,
            // 'penilai_id' => $penilai_id, // Kita ambil berdasarkan pegawai dan minggu saja agar pegawai juga bisa lihat
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        return $this->response->setJSON(['status' => 'success', 'data' => $perilaku, 'ekspektasi' => $ekspektasi]);
    }

    public function getPerilakuMingguanSebelumnya()
    {
        $id_user = session()->get('id_user');
        $minggu_ke = $this->request->getGet('minggu');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        // Determine previous week
        $prev_minggu = $minggu_ke - 1;
        $prev_bulan = $bulan;
        $prev_tahun = $tahun;

        if ($prev_minggu < 1) {
            $prev_bulan--;
            if ($prev_bulan < 1) {
                $prev_bulan = 12;
                $prev_tahun--;
            }
            $prev_minggu = 5; 
        }

        // Cari evaluasi terakhir yang disubmit sebelum atau sama dengan prev_minggu/bulan/tahun
        $perilaku = null;
        
        for ($i = 0; $i < 5; $i++) {
            $perilaku = $this->perilakuModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $prev_minggu,
                'bulan' => $prev_bulan,
                'tahun' => $prev_tahun
            ])->first();

            if ($perilaku) {
                break; // Ketemu data!
            }

            // Mundur 1 minggu lagi
            $prev_minggu--;
            if ($prev_minggu < 1) {
                $prev_bulan--;
                if ($prev_bulan < 1) {
                    $prev_bulan = 12;
                    $prev_tahun--;
                }
                $prev_minggu = 5;
            }
        }

        if (!$perilaku) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'Tidak ada data di minggu sebelumnya']);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $perilaku]);
    }

    public function getEvaluasiMingguanSebelumnyaPenilai()
    {
        $id_user = $this->request->getGet('id_user');
        $minggu_ke = $this->request->getGet('minggu');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $prev_minggu = $minggu_ke - 1;
        $prev_bulan = $bulan;
        $prev_tahun = $tahun;

        if ($prev_minggu < 1) {
            $prev_bulan--;
            if ($prev_bulan < 1) {
                $prev_bulan = 12;
                $prev_tahun--;
            }
            $prev_minggu = 5; 
        }

        $evaluasiMaster = [];
        $evaluasiRekomendasi = [];
        
        for ($i = 0; $i < 5; $i++) {
            $evaluasiMaster = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*')
                ->where([
                    'id_user' => $id_user,
                    'minggu_ke' => $prev_minggu,
                    'bulan' => $prev_bulan,
                    'tahun' => $prev_tahun,
                    'master_kinerja_id !=' => null
                ])->findAll();

            $evaluasiRekomendasi = $this->evaluasiModel->select('ekip_evaluasi_mingguan.*')
                ->where([
                    'id_user' => $id_user,
                    'minggu_ke' => $prev_minggu,
                    'bulan' => $prev_bulan,
                    'tahun' => $prev_tahun,
                    'rekomendasi_id !=' => null
                ])->findAll();

            if (count($evaluasiMaster) > 0 || count($evaluasiRekomendasi) > 0) {
                break;
            }

            $prev_minggu--;
            if ($prev_minggu < 1) {
                $prev_bulan--;
                if ($prev_bulan < 1) {
                    $prev_bulan = 12;
                    $prev_tahun--;
                }
                $prev_minggu = 5;
            }
        }

        $evaluasiByMaster = [];
        $evaluasiByRekomendasi = [];
        foreach ($evaluasiMaster as $ev) {
            $evaluasiByMaster[$ev['master_kinerja_id']] = $ev;
        }
        foreach ($evaluasiRekomendasi as $ev) {
            $evaluasiByRekomendasi[$ev['rekomendasi_id']] = $ev;
        }

        if (count($evaluasiByMaster) == 0 && count($evaluasiByRekomendasi) == 0) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'Tidak ada data di minggu sebelumnya']);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $evaluasiByMaster, 'data_rek' => $evaluasiByRekomendasi]);
    }

    public function getPerilakuMingguanSebelumnyaPenilai()
    {
        $id_user = $this->request->getGet('id_user');
        $minggu_ke = $this->request->getGet('minggu');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $prev_minggu = $minggu_ke - 1;
        $prev_bulan = $bulan;
        $prev_tahun = $tahun;

        if ($prev_minggu < 1) {
            $prev_bulan--;
            if ($prev_bulan < 1) {
                $prev_bulan = 12;
                $prev_tahun--;
            }
            $prev_minggu = 5; 
        }

        $perilaku = null;
        $ekspektasi = null;
        
        for ($i = 0; $i < 5; $i++) {
            $perilaku = $this->perilakuModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $prev_minggu,
                'bulan' => $prev_bulan,
                'tahun' => $prev_tahun
            ])->first();

            $ekspektasi = $this->ekspektasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $prev_minggu,
                'bulan' => $prev_bulan,
                'tahun' => $prev_tahun
            ])->first();

            if ($perilaku || $ekspektasi) {
                break;
            }

            $prev_minggu--;
            if ($prev_minggu < 1) {
                $prev_bulan--;
                if ($prev_bulan < 1) {
                    $prev_bulan = 12;
                    $prev_tahun--;
                }
                $prev_minggu = 5;
            }
        }

        if (!$perilaku && !$ekspektasi) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'Tidak ada data di minggu sebelumnya']);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $perilaku, 'ekspektasi' => $ekspektasi]);
    }

    public function sinkronisasiGlobalPenilai()
    {
        $minggu_ke = $this->request->getPost('minggu');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $id_user = $this->request->getPost('id_user'); // Jika sinkron per-user
        $is_admin = $this->request->getPost('is_admin') === 'true';
        
        $penilai_id = session()->get('id_user'); // Ambil ID penilai yang sedang login

        $db = \Config\Database::connect();
        
        // Cek apakah user benar-benar super admin
        $isSuperAdmin = $db->table('ekip_super_admin')->where('id_user', $penilai_id)->countAllResults() > 0;
        
        $user_filter = [];

        if ($isSuperAdmin && $is_admin) {
            if ($id_user) {
                $user_filter = [$id_user];
            } else {
                // Jika tidak ada id_user, artinya global admin. Biarkan $user_filter kosong untuk tidak me-limit whereIn
            }
        } else {
            // Logika PIC Penilai biasa
            $picTim = $db->table('ekip_pic_tim_kerja')->where('id_user', $penilai_id)->get()->getResultArray();
            $id_tims = array_column($picTim, 'id_tim_kerja');
            
            $allowed_users = [];
            if (!empty($id_tims)) {
                $pegawaiTim = $db->table('data_pegawai')
                                 ->select('id_user')
                                 ->whereIn('id_tim_kerja', $id_tims)
                                 ->get()->getResultArray();
                $allowed_users = array_column($pegawaiTim, 'id_user');
            }

            // Ambil juga pegawai yang ditugaskan manual ke penilai ini (jika ada sistem penugasan manual)
            $penugasan = $this->penilaiModel->where('penilai_id', $penilai_id)->findAll();
            $assigned_users = array_column($penugasan, 'dinilai_id');
            
            // Gabungkan semua ID user yang boleh dinilai oleh penilai ini
            $valid_user_ids = array_unique(array_merge($allowed_users, $assigned_users));

            if (empty($valid_user_ids)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki pegawai untuk dinilai.']);
            }

            // Jika sinkron per user, pastikan user tersebut ada di daftar yang diizinkan
            if ($id_user && !in_array($id_user, $valid_user_ids)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke pegawai ini.']);
            }

            $user_filter = $id_user ? [$id_user] : $valid_user_ids;
        }

        $prev_minggu = $minggu_ke - 1;
        $prev_bulan = $bulan;
        $prev_tahun = $tahun;

        if ($prev_minggu < 1) {
            $prev_bulan--;
            if ($prev_bulan < 1) {
                $prev_bulan = 12;
                $prev_tahun--;
            }
            $prev_minggu = 5; 
        }

        $whereMaster = [
            'minggu_ke' => $prev_minggu,
            'bulan' => $prev_bulan,
            'tahun' => $prev_tahun,
            'master_kinerja_id !=' => null
        ];

        $whereRek = [
            'minggu_ke' => $prev_minggu,
            'bulan' => $prev_bulan,
            'tahun' => $prev_tahun,
            'rekomendasi_id !=' => null
        ];

        $wherePerilaku = [
            'minggu_ke' => $prev_minggu,
            'bulan' => $prev_bulan,
            'tahun' => $prev_tahun
        ];

        $found = false;
        
        for ($i = 0; $i < 5; $i++) {
            if (empty($user_filter)) {
                $countMaster = $this->evaluasiModel->where($whereMaster)->countAllResults();
                $countRek = $this->evaluasiModel->where($whereRek)->countAllResults();
                $countEkspektasi = $this->ekspektasiModel->where($wherePerilaku)->countAllResults();
            } else {
                $countMaster = $this->evaluasiModel->where($whereMaster)->whereIn('id_user', $user_filter)->countAllResults();
                $countRek = $this->evaluasiModel->where($whereRek)->whereIn('id_user', $user_filter)->countAllResults();
                $countEkspektasi = $this->ekspektasiModel->where($wherePerilaku)->whereIn('id_user', $user_filter)->countAllResults();
            }

            if ($countMaster > 0 || $countRek > 0 || $countEkspektasi > 0) {
                $found = true;
                break;
            }

            $prev_minggu--;
            if ($prev_minggu < 1) {
                $prev_bulan--;
                if ($prev_bulan < 1) {
                    $prev_bulan = 12;
                    $prev_tahun--;
                }
                $prev_minggu = 5;
            }
            $whereMaster['minggu_ke'] = $prev_minggu;
            $whereMaster['bulan'] = $prev_bulan;
            $whereMaster['tahun'] = $prev_tahun;

            $whereRek['minggu_ke'] = $prev_minggu;
            $whereRek['bulan'] = $prev_bulan;
            $whereRek['tahun'] = $prev_tahun;

            $wherePerilaku['minggu_ke'] = $prev_minggu;
            $wherePerilaku['bulan'] = $prev_bulan;
            $wherePerilaku['tahun'] = $prev_tahun;
        }

        if (!$found) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'Tidak ada data penilaian di minggu sebelumnya']);
        }

        // Copy Data Master Kinerja
        if (empty($user_filter)) {
            $evaluasiMaster = $this->evaluasiModel->where($whereMaster)->findAll();
        } else {
            $evaluasiMaster = $this->evaluasiModel->where($whereMaster)->whereIn('id_user', $user_filter)->findAll();
        }
        
        foreach ($evaluasiMaster as $ev) {
            if ($ev['catatan_penilai']) {
                $currentEv = $this->evaluasiModel->where([
                    'id_user' => $ev['id_user'],
                    'minggu_ke' => $minggu_ke,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'master_kinerja_id' => $ev['master_kinerja_id']
                ])->first();

                if ($currentEv) {
                    $this->evaluasiModel->update($currentEv['id'], ['catatan_penilai' => $ev['catatan_penilai']]);
                }
            }
        }

        // Copy Data Rekomendasi
        if (empty($user_filter)) {
            $evaluasiRekomendasi = $this->evaluasiModel->where($whereRek)->findAll();
        } else {
            $evaluasiRekomendasi = $this->evaluasiModel->where($whereRek)->whereIn('id_user', $user_filter)->findAll();
        }
        
        foreach ($evaluasiRekomendasi as $ev) {
            if ($ev['catatan_penilai']) {
                $currentEv = $this->evaluasiModel->where([
                    'id_user' => $ev['id_user'],
                    'minggu_ke' => $minggu_ke,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'rekomendasi_id' => $ev['rekomendasi_id']
                ])->first();

                if ($currentEv) {
                    $this->evaluasiModel->update($currentEv['id'], ['catatan_penilai' => $ev['catatan_penilai']]);
                }
            }
        }

        // Copy Ekspektasi / Perilaku
        if (empty($user_filter)) {
            $ekspektasi = $this->ekspektasiModel->where($wherePerilaku)->findAll();
        } else {
            $ekspektasi = $this->ekspektasiModel->where($wherePerilaku)->whereIn('id_user', $user_filter)->findAll();
        }
        
        foreach ($ekspektasi as $eks) {
            $currentEks = $this->ekspektasiModel->where([
                'id_user' => $eks['id_user'],
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            $updateData = [];
            $core_values = ['pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'];
            foreach ($core_values as $cv) {
                if ($eks[$cv . '_catatan']) $updateData[$cv . '_catatan'] = $eks[$cv . '_catatan'];
                if ($eks[$cv . '_nilai']) $updateData[$cv . '_nilai'] = $eks[$cv . '_nilai'];
            }
            if ($eks['predikat_perilaku']) $updateData['predikat_perilaku'] = $eks['predikat_perilaku'];
            
            // Juga ambil predikat kinerja
            if ($eks['predikat_kinerja']) $updateData['predikat_kinerja'] = $eks['predikat_kinerja'];

            if (!empty($updateData)) {
                if ($currentEks) {
                    $this->ekspektasiModel->update($currentEks['id'], $updateData);
                } else {
                    $updateData['id_user'] = $eks['id_user'];
                    $updateData['minggu_ke'] = $minggu_ke;
                    $updateData['bulan'] = $bulan;
                    $updateData['tahun'] = $tahun;
                    $this->ekspektasiModel->insert($updateData);
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Penilaian minggu sebelumnya berhasil disalin']);
    }

    public function hapusPerilakuMingguan($minggu_ke)
    {
        $id_user = session()->get('id_user');
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('n');

        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu hapus evaluasi perilaku untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $this->perilakuModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Penilaian perilaku minggu tersebut berhasil dihapus']);
    }

    public function simpanEkspektasi()
    {
        $penilai_id = session()->get('id_user');
        $id_user = $this->request->getPost('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke');
        
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');
        
        $predikat_perilaku = $this->request->getPost('predikat_perilaku');

        if (!empty($id_user) && !empty($minggu_ke) && !empty($predikat_perilaku)) {
            $eks = $this->ekspektasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            $data = [
                'penilai_id' => $penilai_id,
                'predikat_perilaku' => $predikat_perilaku,
                'pelayanan_nilai' => $this->request->getPost('pelayanan_nilai'),
                'pelayanan_catatan' => $this->request->getPost('pelayanan_catatan'),
                'akuntabel_nilai' => $this->request->getPost('akuntabel_nilai'),
                'akuntabel_catatan' => $this->request->getPost('akuntabel_catatan'),
                'kompeten_nilai' => $this->request->getPost('kompeten_nilai'),
                'kompeten_catatan' => $this->request->getPost('kompeten_catatan'),
                'harmonis_nilai' => $this->request->getPost('harmonis_nilai'),
                'harmonis_catatan' => $this->request->getPost('harmonis_catatan'),
                'loyal_nilai' => $this->request->getPost('loyal_nilai'),
                'loyal_catatan' => $this->request->getPost('loyal_catatan'),
                'adaptif_nilai' => $this->request->getPost('adaptif_nilai'),
                'adaptif_catatan' => $this->request->getPost('adaptif_catatan'),
                'kolaboratif_nilai' => $this->request->getPost('kolaboratif_nilai'),
                'kolaboratif_catatan' => $this->request->getPost('kolaboratif_catatan'),
            ];

            // Ensure empty strings are saved as NULL for integers
            foreach (['pelayanan_nilai', 'akuntabel_nilai', 'kompeten_nilai', 'harmonis_nilai', 'loyal_nilai', 'adaptif_nilai', 'kolaboratif_nilai'] as $field) {
                if ($data[$field] === '') {
                    $data[$field] = null;
                }
            }

            if ($eks) {
                $this->ekspektasiModel->update($eks['id'], $data);
            } else {
                $data['id_user'] = $id_user;
                $data['minggu_ke'] = $minggu_ke;
                $data['bulan'] = $bulan;
                $data['tahun'] = $tahun;
                $data['nilai'] = 0;
                $this->ekspektasiModel->insert($data);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Penilaian Perilaku dan Predikat berhasil disimpan']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap (id_user: '.$id_user.', minggu: '.$minggu_ke.', predikat: '.$predikat_perilaku.')']);
    }

    public function simpanSuperAdmin()
    {
        $id_user = $this->request->getPost('id_user');
        if ($id_user) {
            $db = \Config\Database::connect();
            $cek = $db->table('ekip_super_admin')->where('id_user', $id_user)->get()->getRowArray();
            if ($cek) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Pegawai ini sudah menjadi Super Admin']);
            }
            $db->table('ekip_super_admin')->insert(['id_user' => $id_user]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Super Admin berhasil ditambahkan']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih pegawai terlebih dahulu']);
    }

    public function hapusSuperAdmin($id)
    {
        $db = \Config\Database::connect();
        $db->table('ekip_super_admin')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => 'success', 'message' => 'Super Admin berhasil dihapus']);
    }

    public function rollbackEkspektasi()
    {
        $penilai_id = session()->get('id_user');
        $id_user = $this->request->getPost('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke');
        
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');

        if (!empty($id_user) && !empty($minggu_ke)) {
            $this->ekspektasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->delete();

            // Clear catatan kinerja from evaluasi mingguan
            $this->evaluasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->set(['catatan_penilai' => null])->update();

            return $this->response->setJSON(['status' => 'success', 'message' => 'Penilaian berhasil di-rollback']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
    }

    public function simpanCatatanKinerja()
    {
        $penilai_id = session()->get('id_user');
        $id_user = $this->request->getPost('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke');
        $catatan_penilai = $this->request->getPost('catatan_penilai'); // Array of master_kinerja_id => catatan
        $predikat_kinerja = $this->request->getPost('predikat_kinerja');
        
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');

        if (!empty($id_user) && !empty($minggu_ke) && !empty($predikat_kinerja)) {
            // 1. Simpan catatan di setiap item evaluasi mingguan
            if (!empty($catatan_penilai)) {
                foreach ($catatan_penilai as $evaluasi_id => $catatan) {
                    $this->evaluasiModel->update($evaluasi_id, ['catatan_penilai' => $catatan]);
                }
            }

            // 2. Simpan predikat kinerja di tabel ekspektasi
            $eks = $this->ekspektasiModel->where([
                'id_user' => $id_user,
                'minggu_ke' => $minggu_ke,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            if ($eks) {
                $this->ekspektasiModel->update($eks['id'], [
                    'penilai_id' => $penilai_id,
                    'predikat_kinerja' => $predikat_kinerja
                ]);
            } else {
                $this->ekspektasiModel->insert([
                    'id_user' => $id_user,
                    'penilai_id' => $penilai_id,
                    'minggu_ke' => $minggu_ke,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nilai' => 0,
                    'predikat_kinerja' => $predikat_kinerja
                ]);
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Catatan dan Predikat Kinerja berhasil disimpan']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap (id_user: '.$id_user.', minggu: '.$minggu_ke.', predikat: '.$predikat_kinerja.')']);
    }

    public function simpanPengaturan()
    {
        $pengaturan = $this->pengaturanModel->first();
        $data = [
            'akses_pegawai' => $this->request->getPost('akses_pegawai') ? 1 : 0,
            'notifikasi_aktif' => $this->request->getPost('notifikasi_aktif') ? 1 : 0,
            'jadwal_buka_hari' => $this->request->getPost('jadwal_buka_hari'),
            'jadwal_tutup_hari' => $this->request->getPost('jadwal_tutup_hari'),
            'jadwal_tutup_jam' => $this->request->getPost('jadwal_tutup_jam'),
            't360_q1' => $this->request->getPost('t360_q1') ? 1 : 0,
            't360_q2' => $this->request->getPost('t360_q2') ? 1 : 0,
            't360_q3' => $this->request->getPost('t360_q3') ? 1 : 0,
            't360_q4' => $this->request->getPost('t360_q4') ? 1 : 0,
            'tab_kinerja_aktif' => $this->request->getPost('tab_kinerja_aktif') !== null ? 1 : 0,
            'tab_perilaku_aktif' => $this->request->getPost('tab_perilaku_aktif') !== null ? 1 : 0,
            'tab_360_aktif' => $this->request->getPost('tab_360_aktif') !== null ? 1 : 0,
        ];

        if ($pengaturan) {
            $this->pengaturanModel->update($pengaturan['id'], $data);
        } else {
            $this->pengaturanModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Pengaturan berhasil disimpan']);
    }

    public function tambahPenilai()
    {
        $penilai_id = $this->request->getPost('penilai_id');
        $dinilai_ids = $this->request->getPost('dinilai_id');

        if (empty($penilai_id) || empty($dinilai_ids)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Lengkapi data penilai dan pegawai yang dinilai.']);
        }

        foreach ($dinilai_ids as $dinilai_id) {
            // Cek duplikasi
            $cek = $this->penilaiModel->where(['penilai_id' => $penilai_id, 'dinilai_id' => $dinilai_id])->first();
            if (!$cek) {
                $this->penilaiModel->insert([
                    'penilai_id' => $penilai_id,
                    'dinilai_id' => $dinilai_id
                ]);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan penilai berhasil ditambahkan.']);
    }

    public function hapusPenilai($id)
    {
        $this->penilaiModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan berhasil dihapus.']);
    }
    
    public function tambahPicTim()
    {
        $id_tim_kerja = $this->request->getPost('id_tim_kerja');
        $id_user = $this->request->getPost('id_user');

        if (empty($id_tim_kerja) || empty($id_user)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih Tim Kerja dan PIC terlebih dahulu.']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('ekip_pic_tim_kerja');
        
        // Cek duplikasi
        $cek = $builder->where(['id_tim_kerja' => $id_tim_kerja, 'id_user' => $id_user])->get()->getRowArray();
        if (!$cek) {
            $builder->insert([
                'id_tim_kerja' => $id_tim_kerja,
                'id_user' => $id_user,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'PIC Tim Kerja berhasil ditambahkan.']);
    }

    public function hapusPicTim($id)
    {
        $db = \Config\Database::connect();
        $db->table('ekip_pic_tim_kerja')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => 'success', 'message' => 'PIC Tim Kerja berhasil dihapus.']);
    }

    public function simpanRekomendasiKebijakan()
    {
        $penilai_id = session()->get('id_user');
        $dinilai_id = $this->request->getPost('dinilai_id');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');
        $minggu_ke = $this->request->getPost('minggu_ke');

        $data = [
            'dinilai_id' => $dinilai_id,
            'penilai_id' => $penilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke,
            'rencana_hasil_kerja' => $this->request->getPost('rencana_hasil_kerja'),
            'iki' => $this->request->getPost('iki'),
            'target' => $this->request->getPost('target')
        ];

        $this->rekomendasiModel->insert($data);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Tugas Tambahan (Rekomendasi) berhasil ditambahkan.']);
    }

    public function getRekomendasiKebijakan($dinilai_id, $tahun, $bulan, $minggu_ke)
    {
        $data = $this->rekomendasiModel->where([
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke
        ])->findAll();

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function hapusRekomendasiKebijakan($id)
    {
        $this->rekomendasiModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Tugas Tambahan berhasil dihapus.']);
    }

    public function simpanPenilaian360()
    {
        $penilai_id = session()->get('id_user');
        $dinilai_id = $this->request->getPost('dinilai_id');
        $minggu_ke = $this->request->getPost('minggu_ke');
        
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');
        
        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input penilaian 360 untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $penilaian360Model = new \App\Models\Ekip\EkipPenilaian360Model();

        $existing = $penilaian360Model->where([
            'penilai_id' => $penilai_id,
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke
        ])->first();

        $data = [
            'penilai_id' => $penilai_id,
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke,
            'hasil_pekerjaan' => $this->request->getPost('hasil_pekerjaan'),
            'kualitas_pekerjaan' => $this->request->getPost('kualitas_pekerjaan'),
            'ketepatan_waktu' => $this->request->getPost('ketepatan_waktu'),
            'efektivitas_efesiensi' => $this->request->getPost('efektivitas_efesiensi'),
            'tanggung_jawab' => $this->request->getPost('tanggung_jawab'),
            'kerjasama' => $this->request->getPost('kerjasama'),
            'presensi' => $this->request->getPost('presensi'),
            'jumlah' => $this->request->getPost('jumlah')
        ];

        // Ensure empty strings are saved as NULL
        foreach (['hasil_pekerjaan', 'kualitas_pekerjaan', 'ketepatan_waktu', 'efektivitas_efesiensi', 'tanggung_jawab', 'kerjasama', 'presensi', 'jumlah'] as $field) {
            if ($data[$field] === '') {
                $data[$field] = null;
            }
        }

        if ($existing) {
            $penilaian360Model->update($existing['id'], $data);
        } else {
            $penilaian360Model->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Penilaian 360 berhasil disimpan']);
    }

    public function simpanJustifikasi360()
    {
        $penilai_id = session()->get('id_user');
        $dinilai_id = $this->request->getPost('dinilai_id');
        $minggu_ke = $this->request->getPost('minggu_ke');
        $deskripsi = $this->request->getPost('justifikasi_deskripsi');
        
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');
        
        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input penilaian 360 untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $penilaian360Model = new \App\Models\Ekip\EkipPenilaian360Model();

        $existing = $penilaian360Model->where([
            'penilai_id' => $penilai_id,
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke
        ])->first();

        $data = [
            'penilai_id' => $penilai_id,
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke,
            'justifikasi_deskripsi' => $deskripsi
        ];

        // Handle File Upload
        $file = $this->request->getFile('justifikasi_file');
        $fileName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi file type dan size
            $ext = $file->getExtension();
            $allowedExt = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            if (!in_array(strtolower($ext), $allowedExt)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file tidak diizinkan.']);
            }
            if ($file->getSize() > 2048000) { // 2MB
                return $this->response->setJSON(['status' => 'error', 'message' => 'Ukuran file maksimal 2MB.']);
            }

            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/ekip_360/', $fileName);
            $data['justifikasi_file'] = $fileName;

            // Hapus file lama jika ada
            if ($existing && !empty($existing['justifikasi_file']) && file_exists(FCPATH . 'uploads/ekip_360/' . $existing['justifikasi_file'])) {
                unlink(FCPATH . 'uploads/ekip_360/' . $existing['justifikasi_file']);
            }
        }

        if ($existing) {
            $penilaian360Model->update($existing['id'], $data);
        } else {
            $penilaian360Model->insert($data);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Justifikasi berhasil disimpan',
            'file_name' => $fileName
        ]);
    }

    public function hapusJustifikasi360()
    {
        $penilai_id = session()->get('id_user');
        $dinilai_id = $this->request->getPost('dinilai_id');
        $minggu_ke = $this->request->getPost('minggu_ke');
        
        $pengaturan = $this->pengaturanModel->first();
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan') ?? date('n');
        
        if ($this->isWeekLocked($pengaturan, $tahun, $bulan, $minggu_ke)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Batas waktu input penilaian 360 untuk minggu tersebut telah habis atau belum dibuka.']);
        }

        $penilaian360Model = new \App\Models\Ekip\EkipPenilaian360Model();

        $existing = $penilaian360Model->where([
            'penilai_id' => $penilai_id,
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke
        ])->first();

        if ($existing) {
            // Hapus file lama jika ada
            if (!empty($existing['justifikasi_file']) && file_exists(FCPATH . 'uploads/ekip_360/' . $existing['justifikasi_file'])) {
                unlink(FCPATH . 'uploads/ekip_360/' . $existing['justifikasi_file']);
            }

            // Update database to clear justification
            $penilaian360Model->update($existing['id'], [
                'justifikasi_deskripsi' => null,
                'justifikasi_file' => null
            ]);

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Justifikasi berhasil dikosongkan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Data penilaian tidak ditemukan'
        ]);
    }

    public function ajukanFeedback()
    {
        $id_user = session()->get('id_user');
        $minggu_ke = $this->request->getPost('minggu_ke');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');
        $feedback_kinerja = $this->request->getPost('feedback_kinerja');
        $feedback_perilaku = $this->request->getPost('feedback_perilaku');

        if (empty($feedback_kinerja) && empty($feedback_perilaku)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Feedback tidak boleh kosong.']);
        }

        $eks = $this->ekspektasiModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        if ($eks) {
            $updateData = [];
            
            // Cek apakah kinerja diisi dan belum ditanggapi/selesai
            if ($feedback_kinerja !== null && !in_array($eks['status_feedback_kinerja'], ['ditanggapi', 'selesai'])) {
                $updateData['feedback_kinerja'] = $feedback_kinerja;
                $updateData['status_feedback_kinerja'] = empty($feedback_kinerja) ? 'none' : 'diajukan';
            }
            
            // Cek apakah perilaku diisi dan belum ditanggapi/selesai
            if ($feedback_perilaku !== null && !in_array($eks['status_feedback_perilaku'], ['ditanggapi', 'selesai'])) {
                $updateData['feedback_perilaku'] = $feedback_perilaku;
                $updateData['status_feedback_perilaku'] = empty($feedback_perilaku) ? 'none' : 'diajukan';
            }
            
            if (!empty($updateData)) {
                $this->ekspektasiModel->update($eks['id'], $updateData);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Feedback berhasil diajukan kepada penilai.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada feedback yang dapat diubah.']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Data penilaian tidak ditemukan.']);
    }

    public function tanggapiFeedback()
    {
        $penilai_id = session()->get('id_user');
        $id_user = $this->request->getPost('id_user'); // ID Pegawai
        $minggu_ke = $this->request->getPost('minggu_ke');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');
        $tanggapan_kinerja = $this->request->getPost('tanggapan_kinerja');
        $tanggapan_perilaku = $this->request->getPost('tanggapan_perilaku');

        if (empty($tanggapan_kinerja) && empty($tanggapan_perilaku)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggapan tidak boleh kosong.']);
        }

        $eks = $this->ekspektasiModel->where([
            'id_user' => $id_user,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        if ($eks) {
            $updateData = [];
            
            if ($tanggapan_kinerja !== null) {
                $updateData['tanggapan_kinerja'] = $tanggapan_kinerja;
                if (!empty($tanggapan_kinerja)) {
                    $updateData['status_feedback_kinerja'] = 'ditanggapi';
                }
            }
            
            if ($tanggapan_perilaku !== null) {
                $updateData['tanggapan_perilaku'] = $tanggapan_perilaku;
                if (!empty($tanggapan_perilaku)) {
                    $updateData['status_feedback_perilaku'] = 'ditanggapi';
                }
            }
            
            if (!empty($updateData)) {
                $this->ekspektasiModel->update($eks['id'], $updateData);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Tanggapan berhasil dikirim.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada tanggapan yang dikirim.']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Data penilaian tidak ditemukan.']);
    }

    public function getObservasiPerilaku($dinilai_id, $tahun, $bulan, $minggu)
    {
        $penilai_id = session()->get('id_user');
        
        $data = $this->ekspektasiKhususModel->where([
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu
        ])->first();

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function cekEkspektasiPimpinan($tahun, $bulan, $minggu)
    {
        $dinilai_id = session()->get('id_user');
        
        $data = $this->ekspektasiKhususModel->where([
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu
        ])->first();
        
        // Cek apakah ada data yang tidak kosong
        $hasData = false;
        if ($data) {
            foreach (['berorientasi_pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'] as $field) {
                if (!empty(trim($data[$field]))) {
                    $hasData = true;
                    break;
                }
            }
        }

        if ($hasData) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'success', 'data' => null]);
        }
    }

    public function simpanObservasiPerilaku()
    {
        $penilai_id = session()->get('id_user');
        $dinilai_id = $this->request->getPost('dinilai_id');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');
        $minggu_ke = $this->request->getPost('minggu_ke');

        $data = [
            'dinilai_id' => $dinilai_id,
            'penilai_id' => $penilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke,
            'berorientasi_pelayanan' => $this->request->getPost('berorientasi_pelayanan'),
            'akuntabel' => $this->request->getPost('akuntabel'),
            'kompeten' => $this->request->getPost('kompeten'),
            'harmonis' => $this->request->getPost('harmonis'),
            'loyal' => $this->request->getPost('loyal'),
            'adaptif' => $this->request->getPost('adaptif'),
            'kolaboratif' => $this->request->getPost('kolaboratif'),
        ];

        $existing = $this->ekspektasiKhususModel->where([
            'dinilai_id' => $dinilai_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'minggu_ke' => $minggu_ke
        ])->first();

        if ($existing) {
            $this->ekspektasiKhususModel->update($existing['id'], $data);
        } else {
            $this->ekspektasiKhususModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Ekspektasi Khusus Pimpinan berhasil disimpan.']);
    }
}
