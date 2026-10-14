<?php

namespace App\Controllers\KendaraanKantor;

use App\Controllers\BaseController;
use Config\Services;

class Ajax extends BaseController
{
    public function get_kendaraan()
    {
        if ($this->request->isAJAX()) {

            $startDate = new \DateTime($this->request->getVar('start'));
            $endDate = new \DateTime($this->request->getVar('end'));

            if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                $tanggalArray = [$startDate->format('Y-m-d')];
            } else {
                $endDate->modify('+1 day');

                $interval = new \DateInterval('P1D');
                $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                $tanggalArray = [];
                foreach ($dateRange as $date) {
                    $tanggalArray[] = $date->format('Y-m-d');
                }
            }

            $this->kendaraanKantorRiwayatModel->select('id_kendaraan_kantor_daftar')->like('tanggal', $tanggalArray[0]);
            for ($i = 1; $i < count($tanggalArray); $i++) {
                $this->kendaraanKantorRiwayatModel->orLike('tanggal', $tanggalArray[$i]);
            }
            $cekKendaraan = $this->kendaraanKantorRiwayatModel->find();

            if ($cekKendaraan) {
                foreach ($cekKendaraan as $get) {
                    $this->kendaraanKantorDaftarModel->orWhere('id_kendaraan_kantor_daftar', $get['id_kendaraan_kantor_daftar']);
                }
                $data['notData'] = $this->kendaraanKantorDaftarModel->find();
            } else {
                $data['notData'] = array();
            }


            foreach ($cekKendaraan as $get) {
                $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar !=', $get['id_kendaraan_kantor_daftar']);
            }
            $data['getData'] = $this->kendaraanKantorDaftarModel->find();


            $msg = [
                'data' => view("kendaraan_kantor/kendaraan_tersedia", $data)
            ];

            echo json_encode($msg);
        }
    }
    public function set_form()
    {
        if ($this->request->isAJAX()) {
            $msg = [];

            $tanggal = $this->request->getVar("tanggal");
            $tgl = explode(" - ", $tanggal);

            try {
                // Membuat objek DateTime untuk tanggal mulai dan akhir
                $startDate = new \DateTime($tgl[0]);  // Tanggal mulai
                $endDate = new \DateTime($tgl[1]);    // Tanggal akhir

                // Jika tanggal mulai dan akhir sama, simpan hanya satu tanggal
                if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                    $tanggalArray = [$startDate->format('Y-m-d')];
                } else {
                    // Tambahkan satu hari pada endDate agar rentangnya inklusif
                    $endDate->modify('+1 day');

                    // Menggunakan DateInterval untuk menghasilkan rentang tanggal
                    $interval = new \DateInterval('P1D');  // Interval 1 hari
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    // Buat array dari rentang tanggal
                    $tanggalArray = [];
                    foreach ($dateRange as $date) {
                        $tanggalArray[] = $date->format('Y-m-d');
                    }
                }

                // Menyiapkan data untuk response
                $penumpang = $this->request->getVar("penumpang"); // cek bisajadi null

                if ($penumpang) {
                } else {
                    $penumpang = [];
                }

                $cache = Services::cache();
                $cacheCek = true;
                $cek = array();

                $persistentToken = $this->request->getCookie('persistent_token');
                if ($persistentToken) {
                    $cacheKey = 'user_nip_token_' . $persistentToken;
                    $nip = $cache->get($cacheKey);

                    if ($nip) {
                        $cek = $this->dataPegawaiModel->where("nip", $nip)->first();
                        array_unshift($penumpang, $cek['id_data_pegawai']);
                    } else {
                        $cacheCek = false;
                    }
                } else {
                    $cacheCek = false;
                }

                if ($cacheCek) {
                    // Buat query
                    $this->kendaraanKantorRiwayatModel
                        ->select("kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_riwayat.*")
                        ->join("kendaraan_kantor_daftar", "kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar = kendaraan_kantor_daftar.id_kendaraan_kantor_daftar", 'left');
                    foreach ($tanggalArray as $tanggal) {
                        foreach ($penumpang as $p) {
                            $this->kendaraanKantorRiwayatModel->groupStart()
                                ->Like('kendaraan_kantor_riwayat.penumpang', $p)
                                ->Like('kendaraan_kantor_riwayat.tanggal', $tanggal)
                                ->groupEnd();
                        }
                    }

                    // Ambil data pertama
                    $cekData = $this->kendaraanKantorRiwayatModel->first();

                    if ($cekData) {
                        $msg = [
                            "status" => "sold",
                            "data" => $cekData,
                            'cek' => $cekData
                        ];
                    } else {
                        $data = [
                            "tanggal" => json_encode($tanggalArray),
                            "penumpang" => json_encode($penumpang),
                            "tujuan" => $this->request->getVar("tujuan"),
                            "keterangan" => $this->request->getVar("keterangan"),
                            "id_kendaraan_kantor_daftar" => 0,
                            'created_by' => $cek['id_user'],
                            'updated_by' => $cek['id_user'],
                        ];

                        if ($this->kendaraanKantorRiwayatModel->save($data)) {
                            $msg = [
                                "status" => "success",
                                "data" => $data,
                            ];
                        }
                    }
                }

                echo json_encode($msg);
            } catch (\Exception $e) {
                // Tangani error yang terjadi saat membuat objek DateTime
                $msg = [
                    "status" => "error",
                    "data" => $e->getMessage()
                ];

                echo json_encode($msg);
            }
        }
    }
    public function get_the_car()
    {
        if ($this->request->isAJAX()) {
            $msg = [];

            $tanggal = $this->request->getVar("tanggal");
            $tgl = explode(" - ", $tanggal);

            try {
                // Membuat objek DateTime untuk tanggal mulai dan akhir
                $startDate = new \DateTime($tgl[0]);  // Tanggal mulai
                $endDate = new \DateTime($tgl[1]);    // Tanggal akhir

                // Jika tanggal mulai dan akhir sama, simpan hanya satu tanggal
                if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                    $tanggalArray = [$startDate->format('Y-m-d')];
                } else {
                    // Tambahkan satu hari pada endDate agar rentangnya inklusif
                    $endDate->modify('+1 day');

                    // Menggunakan DateInterval untuk menghasilkan rentang tanggal
                    $interval = new \DateInterval('P1D');  // Interval 1 hari
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    // Buat array dari rentang tanggal
                    $tanggalArray = [];
                    foreach ($dateRange as $date) {
                        $tanggalArray[] = $date->format('Y-m-d');
                    }
                }

                // Menyiapkan data untuk response
                $penumpang = explode(",", $this->request->getVar("penumpang")); // cek bisajadi null

                if ($penumpang) {
                } else {
                    $penumpang = [];
                }

                $cache = Services::cache();
                $cacheCek = true;

                $persistentToken = $this->request->getCookie('persistent_token');
                if ($persistentToken) {
                    $cacheKey = 'user_nip_token_' . $persistentToken;
                    $nip = $cache->get($cacheKey);

                    if ($nip) {
                        $cek = $this->dataPegawaiModel->where("nip", $nip)->first();
                        array_unshift($penumpang, $cek['id_data_pegawai']);
                    } else {
                        $cacheCek = false;
                    }
                } else {
                    $cacheCek = false;
                }

                if ($cacheCek) {
                    $id = $this->request->getVar("id_kendaraan_kantor_daftar");
                    $this->kendaraanKantorRiwayatModel->like('tanggal', $tanggalArray[0])
                        ->where('id_kendaraan_kantor_daftar', $id);
                    for ($i = 1; $i < count($tanggalArray); $i++) {
                        $this->kendaraanKantorRiwayatModel->orLike('tanggal', $tanggalArray[$i])
                            ->where('id_kendaraan_kantor_daftar', $id);
                    }
                    $cekData = $this->kendaraanKantorRiwayatModel->find();

                    if ($cekData) {
                        $msg = [
                            "status" => "sold",
                            "data" => $cekData
                        ];
                    } else {
                        $data = [
                            "id_kendaraan_kantor_riwayat" => $this->request->getVar("id_kendaraan_kantor_riwayat"),
                            "id_kendaraan_kantor_daftar" => $id,
                            'updated_by' => $cek['id_user'],
                        ];

                        if ($this->kendaraanKantorRiwayatModel->save($data)) {
                            $msg = [
                                "status" => "success",
                                "data" => $data
                            ];
                        }
                    }
                }

                echo json_encode($msg);
            } catch (\Exception $e) {
                // Tangani error yang terjadi saat membuat objek DateTime
                $msg = [
                    "status" => "error",
                    "data" => $e->getMessage()
                ];

                echo json_encode($msg);
            }
        }
    }
    public function cek_tanggal()
    {
        if ($this->request->isAJAX()) {
            $msg = [];
            $cache = Services::cache();
            $cacheCek = true;

            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);

                if ($nip) {
                    $cek = $this->dataPegawaiModel->where("nip", $nip)->first();

                    $start = $this->request->getVar("start");
                    $end = $this->request->getVar("end");
                } else {
                    $cacheCek = false;
                }
            } else {
                $cacheCek = false;
            }

            if ($cacheCek) {
                $startDate = new \DateTime($start);  // Tanggal mulai
                $endDate = new \DateTime($end);    // Tanggal akhir

                // Jika tanggal mulai dan akhir sama, simpan hanya satu tanggal
                if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                    $tanggalArray = [$startDate->format('Y-m-d')];
                } else {
                    // Tambahkan satu hari pada endDate agar rentangnya inklusif
                    $endDate->modify('+1 day');

                    // Menggunakan DateInterval untuk menghasilkan rentang tanggal
                    $interval = new \DateInterval('P1D');  // Interval 1 hari
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    // Buat array dari rentang tanggal
                    $tanggalArray = [];
                    foreach ($dateRange as $date) {
                        $tanggalArray[] = $date->format('Y-m-d');
                    }
                }
                $this->kendaraanKantorRiwayatModel->join("kendaraan_kantor_daftar", "id_kendaraan_kantor_daftar")
                    ->like('tanggal', $tanggalArray[0]);
                for ($i = 1; $i < count($tanggalArray); $i++) {
                    $this->kendaraanKantorRiwayatModel->orLike('tanggal', $tanggalArray[$i]);
                }
                $cekData = $this->kendaraanKantorRiwayatModel->find();
                $cekDataTanggal = false;

                $id_data_pegawai = [];
                $cekMyData = true;
                foreach ($cekData as $get) {
                    $penumpang = json_decode($get['penumpang'], true);
                    $id_data_pegawai = array_merge($id_data_pegawai, $penumpang);

                    if ($cekMyData && in_array($cek['id_data_pegawai'], $penumpang)) {
                        $cekDataTanggal = $get;
                        $cekMyData = false;
                    }
                }
                $msg = [];
                if ($cekDataTanggal) {
                    $msg += [
                        'data' => $cekDataTanggal,
                    ];
                }
                $msg += [
                    'cek' => $cekData,
                    'dataDisabled' => $id_data_pegawai
                ];
            }
            echo json_encode($msg);
        }
    }
    public function get_riwayat()
    {
        if ($this->request->isAJAX()) {
            $cache = Services::cache();
            $data['nip'] = "";

            // Cek token persisten di cookie
            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);

                if ($nip) {
                    $data['nip'] = $nip;
                }
            }

            $data['getData'] = $this->kendaraanKantorRiwayatModel
                ->select('kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat, data_pegawai.nip, kendaraan_kantor_daftar.gambar, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_riwayat.penumpang, kendaraan_kantor_riwayat.tujuan, kendaraan_kantor_riwayat.keterangan, data_pegawai.nama, kendaraan_kantor_riwayat.tanggal, DATE(kendaraan_kantor_riwayat.created_at) as date')
                ->where("JSON_UNQUOTE(JSON_EXTRACT(tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) >= CURDATE()")
                ->join('data_pegawai', 'data_pegawai.id_user=kendaraan_kantor_riwayat.created_by')
                ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar=kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar')
                ->orderBy('tanggal', 'ASC')
                ->findAll();

            $msg = ['data' => view('kendaraan_kantor/get_riwayat', $data)];

            echo json_encode($msg);
        }
    }
    public function monitoring_get_riwayat()
    {
        if ($this->request->isAJAX()) {
            $cache = Services::cache();
            $data['nip'] = "";

            // Cek token persisten di cookie
            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);

                if ($nip) {
                    $data['nip'] = $nip;
                }
            }

            $data['getData'] = $this->kendaraanKantorRiwayatModel
                ->select('kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat, data_pegawai.nip, kendaraan_kantor_daftar.gambar, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_riwayat.penumpang, kendaraan_kantor_riwayat.tujuan, kendaraan_kantor_riwayat.keterangan, data_pegawai.nama, kendaraan_kantor_riwayat.tanggal, DATE(kendaraan_kantor_riwayat.created_at) as date')
                ->where("JSON_UNQUOTE(JSON_EXTRACT(tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) >= CURDATE()")
                ->join('data_pegawai', 'data_pegawai.id_user=kendaraan_kantor_riwayat.created_by')
                ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar=kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar')
                ->orderBy('tanggal', 'ASC')
                ->findAll();
            $variable = $this->kendaraanKantorRiwayatModel->orderBy('updated_at', 'desc')->first();

            $msg = [
                'data' => view('monitoring/monitoring_kendaraan_kantor_get_riwayat', $data),
                'variable' => $variable['updated_at']
            ];

            echo json_encode($msg);
        }
    }
    public function batalkan_peminjaman_kendaraan()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar("id");

            $msg = [];
            if ($this->kendaraanKantorRiwayatModel->delete($id)) {
                $msg = ['data' => 'sukses'];
            }

            echo json_encode($msg);
        }
    }
    public function get_penumpang()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar("id");

            $data_pegawai = $this->dataPegawaiModel->where('id_data_pegawai', $id)->first();
            $msg = [];
            if ($data_pegawai) {
                $msg = ['data' => name_capitalize($data_pegawai['nama'])];
            }
            echo json_encode($msg);
        }
    }
    public function get_pengajuan()
    {
        if ($this->request->isAJAX()) {
            $cache = Services::cache();
            $data['nip'] = "";

            // Cek token persisten di cookie
            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);

                if ($nip) {
                    $data['nip'] = $nip;
                }
            }

            $cek = $this->dataPegawaiModel->where("nip", $nip)->first();

            $data['getData'] = $this->kendaraanKantorRiwayatModel
                ->select('kendaraan_kantor_riwayat.kendaraan, kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat, data_pegawai.nip, kendaraan_kantor_daftar.gambar, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_riwayat.penumpang, kendaraan_kantor_riwayat.tujuan, kendaraan_kantor_riwayat.keterangan, data_pegawai.nama, kendaraan_kantor_riwayat.tanggal, DATE(kendaraan_kantor_riwayat.created_at) as date')
                ->groupStart()
                ->where("JSON_UNQUOTE(JSON_EXTRACT(tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) >= CURDATE()")
                ->like("kendaraan_kantor_riwayat.penumpang", $cek['id_data_pegawai'])
                ->groupEnd()
                ->join('data_pegawai', 'data_pegawai.id_user=kendaraan_kantor_riwayat.created_by')
                ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar=kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar', 'left')
                ->orderBy('tanggal', 'ASC')
                ->findAll();

            $msg = ['data' => view('kendaraan_kantor/get_riwayat_pengajuan', $data)];

            echo json_encode($msg);
        }
    }
    public function pin_verifikator()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => "gagal"
            ];

            $pin = $this->request->getVar("pin");

            // ====================================
            $cache = Services::cache();
            $nip = "";

            // Cek token persisten di cookie
            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);
            }
            // ====================================

            $cekData = $this->dataPegawaiModel->join('kendaraan_kantor_verifikator', 'id_data_pegawai')
                ->where(['data_pegawai.nip' => $nip, 'kendaraan_kantor_verifikator.pin' => $pin])
                ->first();

            if ($cekData) {
                $msg = [
                    'data' => 'sukses'
                ];
            }

            echo json_encode($msg);
        }
    }
    public function update_pin()
    {
        if ($this->request->isAJAX()) {
            $pin = $this->request->getVar("pin");

            $msg = [];
            $cek = $this->userModel
                ->where('username', session()->get('username'))
                ->join('data_pegawai', 'id_user')
                ->join('kendaraan_kantor_verifikator', 'id_data_pegawai')
                ->first();

            if ($cek) {
                $by = $this->userModel
                    ->where('username', session()->get('username'))
                    ->first();
                $data = [
                    'id_kendaraan_kantor_verifikator' => $cek['id_kendaraan_kantor_verifikator'],
                    'pin' => $pin,
                    'updated_by' => $by['id_user']

                ];
                if ($this->kendaraankantorVerifikatorModel->save($data)) {
                    $msg = [
                        'data' => 'sukses'
                    ];
                }
            }

            echo json_encode($msg);
        }
    }

    public function pengaturan_insert($id)
    {
        if ($this->request->isAJAX()) {
            $msg = [];
            $by = $this->userModel->where('username', session()->get('username'))->first();
            $cek = $this->kendaraankantorVerifikatorModel->where('id_data_pegawai', $id)->first();
            if (!$cek) {
                $data = [
                    'id_data_pegawai' => $id,
                    'created_at' => $by['id_user'],
                    'updated_at' => $by['id_user'],
                ];
                if ($this->kendaraankantorVerifikatorModel->save($data)) {
                    $msg = [
                        'data' => "sukses"
                    ];
                }
            }
            echo json_encode($msg);
        }
    }
    public function pengaturan_delete($id)
    {
        if ($this->request->isAJAX()) {
            $msg = [];
            $cek = $this->kendaraankantorVerifikatorModel->where('id_data_pegawai', $id)->first();
            if ($cek) {
                if ($this->kendaraankantorVerifikatorModel->delete($cek['id_kendaraan_kantor_verifikator'])) {
                    $msg = [
                        'data' => "sukses"
                    ];
                }
            }
            echo json_encode($msg);
        }
    }
    public function verifikator_kendaraan_umum()
    {
        if ($this->request->isAJAX()) {
            $msg = [];
            $cache = Services::cache();

            $persistentToken = $this->request->getCookie('persistent_token');
            if ($persistentToken) {
                $cacheKey = 'user_nip_token_' . $persistentToken;
                $nip = $cache->get($cacheKey);
                $by = $this->dataPegawaiModel
                    ->where('nip', $nip)
                    ->first();
                $id = $this->request->getVar("id_kendaraan_kantor_riwayat");

                $data = [
                    'id_kendaraan_kantor_riwayat' => $id,
                    'id_kendaraan_kantor_daftar' => -1,
                    'keterangan_verifikator' => $this->request->getVar("keterangan_verifikator"),
                    'kendaraan' => 'Kendaraan Umum',
                    'updated_by' => $by['id_user']
                ];

                if ($this->kendaraanKantorRiwayatModel->save($data)) {
                    $cek = $this->kendaraanKantorRiwayatModel->where('id_kendaraan_kantor_riwayat', $id)->first();
                    if ($cek) {
                        $data = [
                            'nama_pengirim'   => $by['id_user'],
                            'nama_penerima'   => $cek['created_by'],
                            'pesan'           => '<strong>Peminjaman Kendaraan Kantor tidak disetujui. Disarankan untuk menggunakan kendaraan umum dengan alasan sebagai berikut:</strong> <br />"' . $this->request->getVar("keterangan_verifikator") . '"',
                        ];
                        if ($this->notifikasiModel->save($data)) {
                            $msg = ['pesan' => 'sukses'];
                        }
                    }
                    $msg += ['data' => 'sukses'];
                }
            }

            echo json_encode($msg);
        }
    }
}
