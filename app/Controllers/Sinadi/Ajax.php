<?php

namespace App\Controllers\Sinadi;

use App\Controllers\BaseController;

class Ajax extends BaseController
{
  public function getPeserta($id = 148)
  {
    // if ($this->request->isAJAX()) {

    $data['peserta'] = $this->sinadiPerjalananDinasModel->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan = sinadi_perjalanan_dinas.id_perjalanan')
      ->where("sinadi_perjalanan_dinas.id_perjalanan", $id)
      ->findAll();
    $msg = [
      'cek' => $data['peserta'],
      'data' => view('sinadi/peserta_table', $data)
    ];
    // echo json_encode($data);
    echo json_encode($msg);
    // return view('sinadi/peserta_table', $data);
    // $msg = [
    //   'tes' => $data,
    //   'data' => view('sinadi/peserta_table', $data),
    //   'count' => count($data['dataCuti']),
    //   'skema' => $skema
    // ];
  }
  // }
  public function getPeserta1()
  {
    // if ($this->request->isAJAX()) {
    //   $msg = [
    //     'tes' => $data,
    //     'data' => view('sinadi/peserta_table', $data),
    //     'count' => count($data['dataCuti']),
    //     'skema' => $skema
    //   ];

    //   echo json_encode($msg);
    // }
  }

  public function dinasDalamKotaView($id = '')
  {
    if ($this->request->isAJAX()) {
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      $data['akses'] = false;
      $aksesTim = false;
      if ($akses) {
          if ($akses['status'] == 'Akses Diterima') {
            $data['akses'] = true;
          }
          if (isset($akses['akses_tim']) && $akses['akses_tim'] == 1) {
            $aksesTim = true;
          }
      }

      $start = $this->request->getVar('start');
      $end = $this->request->getVar('end');

      // Memastikan bahwa rentang tanggal yang dipilih valid
      if ($start && $end) {
        $userSaya = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('username', session()->get('username'))->first();
        // Mengambil data dari tabel sinadi_perjalanan_dinas dengan filter rentang tanggal yang dipilih
        if ($userSaya || $data['akses'] == true) {

          // Pindahkan join ini ke atas agar bisa digunakan di dalam blok if ($data['akses'] == false)
          $this->sinadiPerjalananDinasModel->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan');

          if ($data['akses'] == false) {
            // Pilih hanya kolom dari master dan identitas user untuk menghindari only_full_group_by
            $this->sinadiPerjalananDinasModel->select("sinadi_perjalanan_dinas.*, user.nama, user.username");
            $this->sinadiPerjalananDinasModel->join('data_pegawai', 'data_pegawai.nip=sinadi_perjalanan_dinas.pic_nip', 'LEFT');
            
            if ($aksesTim) {
                // Sekarang aman karena sinadi_perjalanan_dinas_peserta sudah di-join sebelumnya
                $this->sinadiPerjalananDinasModel->join('data_pegawai as dp_peserta', 'dp_peserta.nip = sinadi_perjalanan_dinas_peserta.nip_pegawai', 'LEFT');
            }
          } else {
            $this->sinadiPerjalananDinasModel->select("sinadi_perjalanan_dinas.*, user.nama, user.username");
          }
          
          $this->sinadiPerjalananDinasModel->join('user', 'sinadi_perjalanan_dinas.updated_by=user.id_user');
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.jenis_perjalanan_dinas', 'dalam kota');
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.tgl_pelaksanaan >=', $start);
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.tgl_pelaksanaan <=', $end);
          if ($data['akses'] == false) {
            // Tampilkan data jika user adalah peserta ATAU pembuat dokumen
            $this->sinadiPerjalananDinasModel
              ->groupStart()
                ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai', $userSaya['nip'])
                ->orWhere('sinadi_perjalanan_dinas.created_by', $userSaya['id_user']);
            
            if ($aksesTim && isset($userSaya['id_tim_kerja'])) {
                $this->sinadiPerjalananDinasModel->orWhere('dp_peserta.id_tim_kerja', $userSaya['id_tim_kerja']);
            }
                
            $this->sinadiPerjalananDinasModel->groupEnd();
          }
          $this->sinadiPerjalananDinasModel->groupBy('sinadi_perjalanan_dinas.id_perjalanan, user.nama, user.username');
          $this->sinadiPerjalananDinasModel->orderBy('sinadi_perjalanan_dinas.created_at', 'DESC');
          $perjalanan = $this->sinadiPerjalananDinasModel->findAll();
        } else {
          $perjalanan = array();
        }
      } else {
        $start = date('Y-m-d', strtotime('-2 months'));
        $end = date('Y-m-d', strtotime('+1 months'));
        $perjalanan = array();
      }

      // Menghitung jumlah peserta untuk setiap entri perjalanan dan menyimpannya dalam array baru
      $data['perjalanan'] = [];
      foreach ($perjalanan as $perjalananItem) {
        $jumlahPeserta = $this->sinadiPerjalananDinasPesertaModel
          ->where('id_perjalanan', $perjalananItem['id_perjalanan'])
          ->countAllResults();

        $perjalananItem['jumlah_peserta'] = $jumlahPeserta;
        $data['perjalanan'][] = $perjalananItem;
      }
      $data['activeMenu'] = $this->request->getVar('activeMenu');

      $msg = [
        'start' => $start,
        'end' => $end,
        'data' => view("sinadi/sinadi_dalam_kota_view", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function dinasLuarKotaView($id = '')
  {
    if ($this->request->isAJAX()) {
      $data['activeMenu'] = $this->request->getVar('activeMenu');
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      $data['akses'] = false;
      $aksesTim = false;
      if ($akses) {
          if ($akses['status'] == 'Akses Diterima') {
            $data['akses'] = true;
          }
          if (isset($akses['akses_tim']) && $akses['akses_tim'] == 1) {
            $aksesTim = true;
          }
      }

      $start = $this->request->getVar('start');
      $end = $this->request->getVar('end');

      if ($start && $end) {
        $userSaya = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('username', session()->get('username'))->first();
          
        if ($userSaya || $data['akses'] == true) {
          
          // Pindahkan join ini ke atas agar bisa digunakan di dalam blok if ($data['akses'] == false)
          $this->sinadiPerjalananDinasModel->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan');

          if ($data['akses'] == false) {
            // Pilih hanya kolom dari master dan identitas user untuk menghindari only_full_group_by
            $this->sinadiPerjalananDinasModel->select("sinadi_perjalanan_dinas.*, user.nama, user.username");
            $this->sinadiPerjalananDinasModel->join('data_pegawai', 'data_pegawai.nip=sinadi_perjalanan_dinas.pic_nip', 'LEFT');
            
            if ($aksesTim) {
                // Sekarang aman karena sinadi_perjalanan_dinas_peserta sudah di-join sebelumnya
                $this->sinadiPerjalananDinasModel->join('data_pegawai as dp_peserta', 'dp_peserta.nip = sinadi_perjalanan_dinas_peserta.nip_pegawai', 'LEFT');
            }
          } else {
            $this->sinadiPerjalananDinasModel->select("sinadi_perjalanan_dinas.*, user.nama, user.username");
          }

          $this->sinadiPerjalananDinasModel->join('user', 'sinadi_perjalanan_dinas.updated_by=user.id_user');
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.jenis_perjalanan_dinas', 'luar kota');
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.tgl_pelaksanaan >=', $start);
          $this->sinadiPerjalananDinasModel->where('sinadi_perjalanan_dinas.tgl_pelaksanaan <=', $end);
          if ($data['akses'] == false) {
            // Tampilkan data jika user adalah peserta ATAU pembuat dokumen
            $this->sinadiPerjalananDinasModel
              ->groupStart()
                ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai', $userSaya['nip'])
                ->orWhere('sinadi_perjalanan_dinas.created_by', $userSaya['id_user']);
            
            if ($aksesTim && isset($userSaya['id_tim_kerja'])) {
                $this->sinadiPerjalananDinasModel->orWhere('dp_peserta.id_tim_kerja', $userSaya['id_tim_kerja']);
            }
            
            $this->sinadiPerjalananDinasModel->groupEnd();
          }
          $this->sinadiPerjalananDinasModel->groupBy('sinadi_perjalanan_dinas.id_perjalanan, user.nama, user.username');
          $this->sinadiPerjalananDinasModel->orderBy('sinadi_perjalanan_dinas.created_at', 'DESC');
          $perjalanan = $this->sinadiPerjalananDinasModel->findAll();
        } else {
          $perjalanan = array();
        }
      } else {
        $start = date('Y-m-d', strtotime('-2 months'));
        $end = date('Y-m-d', strtotime('+1 months'));
        $perjalanan = array();
      }

      // Menghitung jumlah peserta untuk setiap entri perjalanan dan menyimpannya dalam array baru
      $data['perjalanan'] = [];
      foreach ($perjalanan as $perjalananItem) {
        $jumlahPeserta = $this->sinadiPerjalananDinasPesertaModel
          ->where('id_perjalanan', $perjalananItem['id_perjalanan'])
          ->countAllResults();

        $perjalananItem['jumlah_peserta'] = $jumlahPeserta;
        $data['perjalanan'][] = $perjalananItem;
      }

      $msg = [
        'start' => $start,
        'end' => $end,
        'data' => view("sinadi/sinadi_dalam_kota_view", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function getNotaDinas()
  {
    if ($this->request->isAJAX()) {
      $data['id_peserta'] = $this->request->getVar("id");
      $data['file'] = $this->request->getVar("file");

      $msg = [
        'data' => view('sinadi/get_nota_dinas', $data)
      ];

      echo json_encode($msg);
    }
  }
  
    public function getPerjalananDinas()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Content-Type: application/json; charset=UTF-8");
    
        $from = $this->request->getVar('from') ?? '';
        $to   = $this->request->getVar('to') ?? '';
    
        $this->sinadiPerjalananDinasModel
            ->select('
                sinadi_perjalanan_dinas.id_perjalanan,
                sinadi_perjalanan_dinas.nama_kegiatan,
                sinadi_perjalanan_dinas.tgl_pelaksanaan,
                sinadi_perjalanan_dinas.tgl_pelaksanaan_akhir,
                sinadi_perjalanan_dinas.tempat_perjalanan,
                sinadi_perjalanan_dinas.pic,
                sinadi_perjalanan_dinas.pic_nip,
                sinadi_perjalanan_dinas.jenis_perjalanan_dinas,
                sinadi_perjalanan_dinas_peserta.no_surat_tugas,
                sinadi_perjalanan_dinas_peserta.no_sppd,
                sinadi_perjalanan_dinas_peserta.nama_pegawai,
                sinadi_perjalanan_dinas_peserta.nip_pegawai,
                sinadi_perjalanan_dinas_peserta.golongan,
                sinadi_perjalanan_dinas_peserta.jabatan_pegawai,
                sinadi_perjalanan_dinas_peserta.transportasi,
                sinadi_perjalanan_dinas_peserta.kota_asal,
                sinadi_perjalanan_dinas_peserta.kota
                
            ')
            ->join(
                'sinadi_perjalanan_dinas_peserta',
                'sinadi_perjalanan_dinas_peserta.id_perjalanan = sinadi_perjalanan_dinas.id_perjalanan',
                'left'
            );
    
        if ($from !== '' && $to !== '') {
            $this->sinadiPerjalananDinasModel
                ->where('DATE(sinadi_perjalanan_dinas.tgl_pelaksanaan) >=', $from)
                ->where('DATE(sinadi_perjalanan_dinas.tgl_pelaksanaan_akhir) <=', $to);
        }
    
        $rows = $this->sinadiPerjalananDinasModel
            ->orderBy('sinadi_perjalanan_dinas.created_at', 'DESC')
            ->findAll();
    
        $result = [];
    
        foreach ($rows as $row) {
            $id = $row['id_perjalanan'];
    
            if (!isset($result[$id])) {
                $result[$id] = [
                    'nama_kegiatan'           => $row['nama_kegiatan'],
                    'tgl_pelaksanaan'         => $row['tgl_pelaksanaan'],
                    'tgl_pelaksanaan_akhir'   => $row['tgl_pelaksanaan_akhir'],
                    'tempat_perjalanan'       => $row['tempat_perjalanan'],
                    'pic'                     => $row['pic'],
                    'pic_nip'                 => $row['pic_nip'],
                    'jenis_perjalanan_dinas'  => $row['jenis_perjalanan_dinas'],
                    'no_surat_tugas'          => $row['no_surat_tugas'],
                    'no_sppd'                 => $row['no_sppd'],
                    'peserta'                 => []
                ];
            }
    
            if (!empty($row['nama_pegawai'])) {
                $result[$id]['peserta'][] = [
                    'nama'            => $row['nama_pegawai'],
                    'nip'             => $row['nip_pegawai'],
                    'golongan'        => $row['golongan'],
                    'jabatan_pegawai' => $row['jabatan_pegawai'],
                    'transportasi'    => $row['transportasi'],
                    'kota_asal'       => $row['kota_asal'],
                    'kota_tujuan'     => $row['kota']
                ];
            }
        }
    
        // reset index array
        return $this->response->setJSON(array_values($result));
    }

  /**
   * Endpoint DataTables server-side untuk tabel Upload Bukti Pembayaran pada SINADI Validasi Dokumen.
   */
  public function verifikasiBuktiPembayaran()
  {
    $username = session()->get('username');
    if (empty($username)) {
      return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
    }

    $draw = (int) ($this->request->getPost('draw') ?? 1);
    $start = max(0, (int) ($this->request->getPost('start') ?? 0));
    $length = (int) ($this->request->getPost('length') ?? 25);
    $length = ($length > 0 && $length <= 100) ? $length : 25;

    $searchParam = $this->request->getPost('search');
    $search = is_array($searchParam) ? trim((string) ($searchParam['value'] ?? '')) : '';

    $tipe = strtoupper((string) ($this->request->getPost('tipe') ?? 'APBN'));
    if (!in_array($tipe, ['APBN', 'RPL'], true)) {
      return $this->response->setJSON([
        'draw'            => $draw,
        'recordsTotal'    => 0,
        'recordsFiltered' => 0,
        'data'            => [],
      ]);
    }

    $allowedStatuses = ['Diajukan', 'Dalam Verifikasi', 'Perlu Revisi', 'Disetujui'];

    $cekVerifikator = \App\Libraries\SinadiVerifikatorHelper::getConfigByTipe(
      $tipe,
      $this->sinadiVerifikatorApbnModel,
      $this->sinadiVerifikatorRplModel,
      $this->sinadiVerifikatorModel
    );
    $myPegawaiIds = \App\Libraries\SinadiVerifikatorHelper::getUserPegawaiIds($this->dataPegawaiModel, $username);
    $verifikator6Ids = \App\Libraries\SinadiVerifikatorHelper::normalizeIds($cekVerifikator['verifikator_6'] ?? null, true);
    $canUploadBukti = !empty($verifikator6Ids) && !empty(array_intersect($myPegawaiIds, $verifikator6Ids));

    $recordsTotal = $this->sinadiPerjalananDinasModel->builder()
      ->where('sumber_anggaran', $tipe)
      ->whereIn('status_surat', $allowedStatuses)
      ->countAllResults();

    $builder = $this->sinadiPerjalananDinasModel->builder();
    $builder->where('sumber_anggaran', $tipe)
      ->whereIn('status_surat', $allowedStatuses);

    if ($search !== '') {
      $builder->groupStart()
        ->like('nama_kegiatan', $search)
        ->orLike('mata_anggaran', $search)
        ->orLike('tempat_perjalanan', $search)
        ->orLike('status_surat', $search)
        ->orLike('tgl_pelaksanaan', $search)
        ->orLike('tgl_pelaksanaan_akhir', $search)
        ->groupEnd();
      $recordsFiltered = $builder->countAllResults(false);
    } else {
      $recordsFiltered = $recordsTotal;
    }

    $rows = $builder
      ->select('id_perjalanan, nama_kegiatan, mata_anggaran, tgl_pelaksanaan, tgl_pelaksanaan_akhir, tempat_perjalanan, status_surat, bukti_pembayaran')
      ->orderBy('created_at', 'DESC')
      ->orderBy('id_perjalanan', 'DESC')
      ->limit($length, $start)
      ->get()
      ->getResultArray();

    $data = [];
    foreach ($rows as $idx => $row) {
      $tglMulai = $row['tgl_pelaksanaan'] ?? '';
      $tglAkhir = $row['tgl_pelaksanaan_akhir'] ?? '';
      if ($tglMulai !== '') {
        $tanggalFormatted = ($tglMulai === $tglAkhir || $tglAkhir === '')
          ? mediumdate_indo($tglMulai)
          : mediumdate_indo($tglMulai) . ' s.d. ' . mediumdate_indo($tglAkhir);
      } else {
        $tanggalFormatted = '-';
      }

      $data[] = [
        'no'               => $start + $idx + 1,
        'id_perjalanan'    => (int) $row['id_perjalanan'],
        'nama_kegiatan'    => (string) ($row['nama_kegiatan'] ?? ''),
        'mata_anggaran'    => (string) ($row['mata_anggaran'] ?? ''),
        'tanggal'          => $tanggalFormatted,
        'tempat_perjalanan'=> (string) ($row['tempat_perjalanan'] ?? ''),
        'status_surat'     => (string) ($row['status_surat'] ?? ''),
        'bukti_pembayaran' => $row['bukti_pembayaran'] ?: null,
        'can_upload'       => $canUploadBukti,
      ];
    }

    return $this->response->setJSON([
      'draw'            => $draw,
      'recordsTotal'    => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data'            => $data,
    ]);
  }
}
