<?php

namespace App\Controllers\Sinadi;

use App\Controllers\BaseController;
use App\Libraries\SinadiVerifikatorHelper;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Data extends BaseController
{
  function tgl_indo($tanggal)
  {
    $bulan = array(
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $split = explode('/', $tanggal);
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun
    return $split[0] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[2];
  }

  public function validasiSurat($id)
  {
    $data['title'] = "Sinadi | Validasi Surat Perjalanan Dinas";
    $data['multiSignature'] = true;

    $data['getData'] = $this->sinadiPerjalananDinasModel
    ->select('user.nama as akses, sinadi_perjalanan_dinas.*')
    ->join('user','user.id_user=sinadi_perjalanan_dinas.created_by')
    ->where('id_perjalanan', $id)->first();
    $data['getPeserta'] = $this->sinadiPerjalananDinasPesertaModel
      ->where('id_perjalanan', $id)->findAll();

    $data['all_pegawai'] = $this->dataPegawaiModel->select('nama, nip')->orderBy('nama', 'ASC')->findAll();
    $data['pegawai'] = array_column($data['all_pegawai'], 'nip');

    if ($data['getPeserta'][0]['kota'] == $data['getPeserta'][0]['kota_asal']) {
      $data['activeMenu'] = "dinasDalamKota";
    } else {
      $data['activeMenu'] = "dinasLuarKota";
    }
    
    $data['getUser'] = $this->dataPegawaiModel->join('user', 'id_user')->where('username', session()->get('username'))->first();
    
    $data['getDataVerifikator'] = $this->sinadiVerifikatorModel->orderBy('created_at', 'desc')->first();
    
    return view('sinadi/sinadi_validasi', $data);
  }
  public function validasiSuratView($id)
  {
    $data['title'] = "Sinadi | Validasi Surat Perjalanan Dinas";
    $data['multiSignature'] = true;

    $data['getData'] = $this->sinadiPerjalananDinasModel
    ->select('user.nama as akses, sinadi_perjalanan_dinas.*')
    ->join('user','user.id_user=sinadi_perjalanan_dinas.created_by')
    ->where('id_perjalanan', $id)->first();
    $data['getPeserta'] = $this->sinadiPerjalananDinasPesertaModel
      ->where('id_perjalanan', $id)->findAll();

    $data['all_pegawai'] = $this->dataPegawaiModel->select('nama, nip')->orderBy('nama', 'ASC')->findAll();
    $data['pegawai'] = array_column($data['all_pegawai'], 'nip');

    if ($data['getPeserta'][0]['kota'] == $data['getPeserta'][0]['kota_asal']) {
      $data['activeMenu'] = "dinasDalamKota";
    } else {
      $data['activeMenu'] = "dinasLuarKota";
    }
    
    $data['getUser'] = $this->dataPegawaiModel->join('user', 'id_user')->where('username', session()->get('username'))->first();

    // echo "Bentar diganggu";
    // dd($data['getData']);
    
    $data['getDataVerifikator'] = $this->sinadiVerifikatorModel->orderBy('created_at', 'desc')->first();

    return view('sinadi/sinadi_validasi_view', $data);
  }
  public function verifikasiDataSurat($verifikatorKe, $id)
  {
    $data['title'] = "Sinadi | Verifikasi Surat Perjalanan Dinas";
    $data['signature'] = true;
    $data['activeMenu'] = "sinadiVerifikator";
    $data['verifikatorKe'] = $verifikatorKe;

    $data['myData'] = $this->dataPegawaiModel
      ->join('user', 'id_user')
      ->where('username', session()->get('username'))
      ->first();

    $data['getData'] = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id)->first();
    $data['getPeserta'] = $this->sinadiPerjalananDinasPesertaModel
      ->where('id_perjalanan', $id)->findAll();

    $data['all_pegawai'] = $this->dataPegawaiModel->select('nama, nip')->orderBy('nama', 'ASC')->findAll();
    $data['pegawai'] = array_column($data['all_pegawai'], 'nip');

    $data['lembarVerif'] = $this->sinadiLembarVerifikasiModel->where('id_perjalanan', $id)->first();

    $data['getDataVerifikator'] = $this->sinadiVerifikatorModel->orderBy('created_at', 'desc')->first();

    return view('sinadi/sinadi_verifikasi_data', $data);
  }


  public function previewBuktiBerkas($any)
  {
    $filename = basename($any);
    helper("filesystem");

    $path = WRITEPATH . 'file-sinadi/';
    $fullpath = $path . $filename;

    if (!is_file($fullpath)) {
      return $this->response
        ->setStatusCode(404)
        ->setBody('File not found');
    }

    $file = new \CodeIgniter\Files\File($fullpath, true);

    return $this->response
      ->setHeader('Content-Type', $file->getMimeType())
      ->setHeader('Content-Disposition', 'inline; filename="' . $file->getBasename() . '"')
      ->setStatusCode(200)
      ->setBody(file_get_contents($fullpath));
  }

  public function verifikasiSurat()
  {
    $data['title'] = "Sinadi | Validasi Dokumen Perjalanan Dinas";
    $data['activeMenu'] = "sinadiBlockir";
    // Default submenu to APBN when no 'tipe' is provided
    $tipe = strtoupper((string) ($this->request->getVar('tipe') ?? 'APBN'));
    $data['tipe'] = $tipe;

    $username = session()->get('username');
    $superUser = $this->userModel
      ->select('sinadi_akses.status')
      ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
      ->where('user.username', $username)
      ->first();

    $isSuperUser = $superUser && ($superUser['status'] ?? '') === 'Akses Diterima';

    // Pilih konfigurasi verifikator berdasarkan submenu (APBN/RPL)
    $cekVerifikator = SinadiVerifikatorHelper::getConfigByTipe(
      $tipe,
      $this->sinadiVerifikatorApbnModel,
      $this->sinadiVerifikatorRplModel,
      $this->sinadiVerifikatorModel
    );

    // Ambil ID pegawai milik user aktif dalam 1 query, lalu cocokan role 1..6 tanpa query berulang
    $myPegawaiIds = SinadiVerifikatorHelper::getUserPegawaiIds($this->dataPegawaiModel, $username);
    $roles = SinadiVerifikatorHelper::resolveUserRoles($cekVerifikator, $myPegawaiIds, $isSuperUser);

    $data['urut_verif'] = $roles['urut_verif'];
    $data['verifikator'] = $roles['keterangan'];
    if (!empty($data['verifikator'])) {
      $data['activeMenu'] = "sinadiVerifikator";
    }

    // Hitung jumlah dokumen aktif APBN & RPL via 1 query agregasi ringan untuk badge tab
    $data['countAPBN'] = 0;
    $data['countRPL'] = 0;
    $countsByTipe = $this->sinadiPerjalananDinasModel
      ->select('sumber_anggaran, COUNT(*) AS total')
      ->whereIn('status_surat', ['Diajukan', 'Dalam Verifikasi', 'Perlu Revisi'])
      ->whereIn('sumber_anggaran', ['APBN', 'RPL'])
      ->groupBy('sumber_anggaran')
      ->findAll();
    foreach ($countsByTipe as $cntRow) {
      if (($cntRow['sumber_anggaran'] ?? '') === 'APBN') {
        $data['countAPBN'] = (int) $cntRow['total'];
      } elseif (($cntRow['sumber_anggaran'] ?? '') === 'RPL') {
        $data['countRPL'] = (int) $cntRow['total'];
      }
    }

    // Ambil hanya baris dokumen aktif pada tab terpilih & proyeksi kolom yang diperlukan
    $data['tahap1Rows'] = [];
    $data['tahap2Rows'] = [];
    $data['tahap3Rows'] = [];
    if (in_array($tipe, ['APBN', 'RPL'], true)) {
      $activeRows = $this->sinadiPerjalananDinasModel
        ->select('id_perjalanan, nama_kegiatan, mata_anggaran, tgl_pelaksanaan, tgl_pelaksanaan_akhir, tempat_perjalanan, status_surat, verifikator_1, verifikator_2')
        ->where('sumber_anggaran', $tipe)
        ->whereIn('status_surat', ['Diajukan', 'Dalam Verifikasi'])
        ->findAll();

      foreach ($activeRows as $row) {
        $status = $row['status_surat'] ?? '';
        $verif1Raw = $row['verifikator_1'] ?? null;
        $verif2Raw = $row['verifikator_2'] ?? null;
        unset($row['verifikator_1'], $row['verifikator_2']);

        if ($status === 'Diajukan') {
          $data['tahap1Rows'][] = $row;
        } elseif ($status === 'Dalam Verifikasi') {
          $time1 = SinadiVerifikatorHelper::extractLatestTime($verif1Raw);
          $time2 = SinadiVerifikatorHelper::extractLatestTime($verif2Raw);
          if ($time1 >= $time2) {
            $data['tahap2Rows'][] = $row;
          } else {
            $data['tahap3Rows'][] = $row;
          }
        }
      }
    }

    // Kumpulkan semua id_data_pegawai verifikator lalu fetch nama sekaligus (hindari N+1)
    $verifikatorIdsPerRole = [];
    $allVerifikatorIds = [];
    for ($i = 1; $i <= 6; $i++) {
      $ids = SinadiVerifikatorHelper::normalizeIds($cekVerifikator["verifikator_{$i}"] ?? null, $i === 1 || $i === 6);
      $verifikatorIdsPerRole[$i] = $ids;
      foreach ($ids as $idPegawai) {
        $allVerifikatorIds[] = $idPegawai;
      }
    }

    $pegawaiById = [];
    $uniqueIds = array_values(array_unique($allVerifikatorIds));
    if (!empty($uniqueIds)) {
      $pegawaiRows = $this->dataPegawaiModel
        ->select('id_data_pegawai, nama')
        ->whereIn('id_data_pegawai', $uniqueIds)
        ->findAll();
      foreach ($pegawaiRows as $peg) {
        $pegawaiById[(string) $peg['id_data_pegawai']] = $peg['nama'];
      }
    }

    $data['daftarVerifikator'] = [];
    for ($i = 1; $i <= 6; $i++) {
      $label = ($i === 6) ? 'Verifikator Upload Bukti Pembayaran' : "Verifikator {$i}";
      $anggota = [];
      foreach ($verifikatorIdsPerRole[$i] as $idPegawai) {
        if (isset($pegawaiById[$idPegawai])) {
          $anggota[] = ['nama' => $pegawaiById[$idPegawai]];
        }
      }
      $data['daftarVerifikator'][] = [
        'verifikator' => $label,
        'anggota'     => !empty($anggota) ? $anggota : null,
      ];
    }

    return view('sinadi/sinadi_verifikasi', $data);
  }

  public function dinasDalamKota()
  {
    $data['title'] = "Sinadi | Perjalanan Dinas Dalam Kota"; // kelola_data_cuti
    $data['activeMenu'] = "dinasDalamKota";

    return view('sinadi/sinadi_dalam_kota', $data);
  }

  public function dinasLuarKota()
  {
    $data['title'] = "Sinadi | Perjalanan Dinas Luar Kota"; // kelola_data_cuti
    $data['activeMenu'] = "dinasLuarKota";

    return view('sinadi/sinadi_dalam_kota', $data);
  }

  public function formulirPerjalananDinas($id = null)
  {
    $blockNip = [
      '000000000000000000',
      '000000000000000001',
      '000000000000000002',
      '000000000000000003',
      '000000000000000004',
      '000000000000000005',
      '000000000000000006',
      '000000000000000007',
      '000000000000000008',
      '000000000000000009',
      '000000000000000010',
      '000000000000000011',
      '000000000000000012',
      '000000000000000013',
      '000000000000000014',
      '000000000000000015',
      '000000000000000016'
    ];
    $cekAkun = $this->userModel->where('username', session()->get('username'))
      ->join('data_pegawai', 'user.id_user=data_pegawai.id_user')
      ->first();
    if ($cekAkun) {
      if (in_array($cekAkun['nip'], $blockNip)) {
        session()->setFlashdata('error', 'Akun anda dibatasi untuk penggunaan SINADI, coba untuk menggunakan akun pribadi!');
        return redirect()->to('/sinadi/dinasDalamKota');
      }
    }
    $data['title'] = "Sinadi | Formulir Perjalanan Dinas <br/><small>(<small>Dalam Kota</small>)</small>"; // kelola_data_cuti
    $data['activeMenu'] = "dinasDalamKota";

    $data['setting'] = $this->sinadiSettingModel->where('status', 'Aktif')->first();
    $data['kota'] = $this->kotaKabupatenModel->findAll();
    $data['pegawai'] = $this->dataPegawaiModel
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
      ->where('status_bekerja', 'aktif')
      ->orderBy("golongan.golongan", "desc")
      ->findAll();

    if ($id == null)
      return view('crud/sinadi_tambah_perjalanan_dinas_dalam_kota', $data);
    else {
      $data['perjalanan'] = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id)->first();
      $data['peserta'] = $this->sinadiPerjalananDinasPesertaModel
        ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_perjalanan_dinas_peserta.id_perjalanan')
        ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
        ->where('sinadi_perjalanan_dinas.id_perjalanan', $id)->findAll();
      return view('crud/sinadi_ubah_perjalanan_dinas_dalam_kota', $data);
    }
  }
  public function formulirPerjalananDinasAntarKota($id = null)
  {
    $blockNip = [
      '000000000000000000',
      '000000000000000001',
      '000000000000000002',
      '000000000000000003',
      '000000000000000004',
      '000000000000000005',
      '000000000000000006',
      '000000000000000007',
      '000000000000000008',
      '000000000000000009',
      '000000000000000010',
      '000000000000000011',
      '000000000000000012',
      '000000000000000013',
      '000000000000000014',
      '000000000000000015',
      '000000000000000016'
    ];
    $cekAkun = $this->userModel->where('username', session()->get('username'))
      ->join('data_pegawai', 'user.id_user=data_pegawai.id_user')
      ->first();
    if ($cekAkun) {
      if (in_array($cekAkun['nip'], $blockNip)) {
        session()->setFlashdata('error', 'Akun anda dibatasi untuk penggunaan SINADI, coba untuk menggunakan akun pribadi!');
        return redirect()->to('/sinadi/dinasLuarKota');
      }
    }

    $data['title'] = "Sinadi | Formulir Perjalanan Dinas <br/><small>(<small>Antar Kota</small>)</small>"; // kelola_data_cuti
    $data['activeMenu'] = "dinasLuarKota";

    $data['setting'] = $this->sinadiSettingModel->where('status', 'Aktif')->first();
    $data['kota'] = $this->kotaKabupatenModel->findAll();
    $data['pegawai'] = $this->dataPegawaiModel
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
      ->where('status_bekerja', 'aktif')
      ->orderBy("golongan.golongan", "desc")
      ->findAll();

    if ($id == null)
      return view('crud/sinadi_tambah_perjalanan_dinas_antar_kota', $data);
    else {
      $data['perjalanan'] = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id)->first();
      $data['peserta'] = $this->sinadiPerjalananDinasPesertaModel
        ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_perjalanan_dinas_peserta.id_perjalanan')
        ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
        ->where('sinadi_perjalanan_dinas.id_perjalanan', $id)->findAll();
      return view('crud/sinadi_ubah_perjalanan_dinas_antar_kota', $data);
    }
  }
  public function perjalananSaya()
  {
    $data['title'] = "Sinadi | Perjalanan Saya"; // kelola_data_cuti
    $data['activeMenu'] = "sinadiSaya";

    // Mengambil data dari tabel sinadi_perjalanan_dinas beserta jumlah baris terkait dari tabel sinadi_perjalanan_dinas_peserta
    // Di bagian kode controller atau tempat Anda menggunakan model

    // Mengambil data dari tabel sinadi_perjalanan_dinas

    $userSaya = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('username', session()->get('username'))->first();

    if ($userSaya) {
      $perjalanan = $this->sinadiPerjalananDinasModel
        ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
        ->join('user', 'sinadi_perjalanan_dinas.updated_by=user.id_user')
        ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai', $userSaya['nip'])
        ->groupBy('sinadi_perjalanan_dinas.id_perjalanan')
        ->orderBy('sinadi_perjalanan_dinas_peserta.tgl_pulang', 'DESC')
        ->findAll();

      // Menghitung jumlah peserta untuk setiap entri perjalanan dan menyimpannya dalam array baru
      $data['perjalanan'] = [];
      $totalPendapatan = 0;
      foreach ($perjalanan as $perjalananItem) {
        $jumlahPeserta = $this->sinadiPerjalananDinasPesertaModel
          ->where('id_perjalanan', $perjalananItem['id_perjalanan'])
          ->countAllResults();
        $totalPendapatan += $perjalananItem['jumlah_transfer'];
        $perjalananItem['jumlah_peserta'] = $jumlahPeserta;
        $data['perjalanan'][] = $perjalananItem;
      }
      $data['totalPendapatan'] = $totalPendapatan;
    } else {
      $perjalanan = $this->sinadiPerjalananDinasModel
        ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
        ->join('user', 'sinadi_perjalanan_dinas.updated_by=user.id_user')
        ->where('sinadi_perjalanan_dinas_peserta.nama_pegawai', session()->get('nama'))
        ->orderBy('sinadi_perjalanan_dinas_peserta.tgl_pulang', 'DESC')
        ->findAll();

      // Menghitung jumlah peserta untuk setiap entri perjalanan dan menyimpannya dalam array baru
      $data['perjalanan'] = [];
      $totalPendapatan = 0;
      foreach ($perjalanan as $perjalananItem) {
        $jumlahPeserta = $this->sinadiPerjalananDinasPesertaModel
          ->where('id_perjalanan', $perjalananItem['id_perjalanan'])
          ->countAllResults();
        $totalPendapatan += $perjalananItem['jumlah_transfer'];
        $perjalananItem['jumlah_peserta'] = $jumlahPeserta;
        $data['perjalanan'][] = $perjalananItem;
      }
      $data['totalPendapatan'] = $totalPendapatan;
    }

    return view('sinadi/sinadi_perjalanan_saya', $data);
  }
  public function pengaturan()
  {
    $data['title'] = "Sinadi | Pengaturan Akses"; // kelola_data_cuti
    $data['activeMenu'] = "sinadiPengaturan";
    $data['akses'] = $this->userModel
      ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user', 'LEFT')
      ->where("user.username", session()->get('username'))
      ->first();

    if ($data['akses']['id_sinadi_akses']) {
      if ($data['akses']['status'] == 'Akses Diblokir') {
        $data['activeMenu'] = "sinadiBlockir";
      }
    } else {
      $data['activeMenu'] = "sinadiBlockir";
    }


    $data['user'] = $this->userModel
      ->select('user.id_user,user.nama, user.id_user, sinadi_akses.id_sinadi_akses, sinadi_akses.status, sinadi_akses.akses_tim')
      ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user', 'LEFT')
      ->orderBy("user.nama", "ASC")
      ->findAll();

    $data['setting'] = $this->sinadiSettingModel->first();

    $data['pegawai'] = $this->dataPegawaiModel
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
      ->where('status_bekerja', 'aktif')
      ->orderBy("golongan.urutan", "desc")
      ->findAll();
      
    $data['verifikator'] = $this->sinadiVerifikatorModel->orderBy('created_at','desc')->first();
    try {
      $data['verifikatorApbn'] = $this->sinadiVerifikatorApbnModel->orderBy('created_at','desc')->first();
    } catch (\Throwable $e) {
      $data['verifikatorApbn'] = null; // Tabel belum ada
    }
    try {
      $data['verifikatorRpl'] = $this->sinadiVerifikatorRplModel->orderBy('created_at','desc')->first();
    } catch (\Throwable $e) {
      $data['verifikatorRpl'] = null; // Tabel belum ada
    }
    
    // $get = $data['verifikator'];
    // $array = unserialize($get['verifikator_1']);
    // in_array("42", $array) ? "checked" : "";
    
    // dd($data['pegawai']);

    return view("sinadi/setting", $data);
  }
  public function notaDinas()
  {
    $data['title'] = "Sinadi | Nota Dinas"; // kelola_data_cuti
    $data['activeMenu'] = "notaDinas";

    $akses = $this->sinadiAksesModel->join('user', 'id_user')->where('sinadi_akses.status', 'Akses Diterima')->where('user.username', session()->get('username'))->findAll();

    if ($akses) {
      $data['notaDinas'] = $this->sinadiPerjalananDinasPesertaModel
        ->select('sinadi_perjalanan_dinas_peserta.*, sinadi_perjalanan_dinas.nama_kegiatan, sinadi_perjalanan_dinas.tempat_perjalanan')
        ->join('sinadi_perjalanan_dinas', 'id_perjalanan')
        ->orderBy('tgl_pulang', 'desc')
        ->findAll();
    } else {
      // Ambil data user yang login untuk cek NIP (sebagai PIC)
      $currentUser = $this->dataPegawaiModel
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $currentNip = $currentUser['nip'] ?? '';
      $currentUserId = $currentUser['id_user'] ?? '';

      $data['notaDinas'] = $this->sinadiPerjalananDinasPesertaModel
        ->select('sinadi_perjalanan_dinas_peserta.*, sinadi_perjalanan_dinas.nama_kegiatan, sinadi_perjalanan_dinas.tempat_perjalanan')
        ->join('sinadi_perjalanan_dinas', 'id_perjalanan')
        ->join('data_pegawai', 'data_pegawai.nip=sinadi_perjalanan_dinas_peserta.nip_pegawai', 'left')
        ->join('user', 'user.id_user=data_pegawai.id_user', 'left')
        ->groupStart()
            ->where('user.username', session()->get('username'))
            ->orWhere('sinadi_perjalanan_dinas.pic_nip', $currentNip)
            ->orWhere('sinadi_perjalanan_dinas.created_by', $currentUserId)
        ->groupEnd()
        ->orderBy('tgl_pulang', 'desc')
        ->findAll();
    }

    return view('sinadi/nota_dinas', $data);
  }

  public function download_nota_dinas_zip($id_perjalanan)
  {
    $peserta = $this->sinadiPerjalananDinasPesertaModel->where('id_perjalanan', $id_perjalanan)->where('file IS NOT NULL')->findAll();
    $kegiatan = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id_perjalanan)->first();

    if (empty($peserta)) {
        return redirect()->back()->with('error', 'Tidak ada file Nota Dinas untuk kegiatan ini.');
    }

    if (class_exists('ZipArchive')) {
        $zip = new \ZipArchive();
        
        // Membersihkan nama kegiatan dari karakter ilegal Windows (\ / : * ? " < > |)
        // dan mengganti karakter non-alphanumeric dengan underscore, menjaga panjang penuh
        $nama_kegiatan = preg_replace('/[\\\\\/:\*\?"<>\|]/', '', $kegiatan['nama_kegiatan']);
        $nama_kegiatan = preg_replace('/[^A-Za-z0-9]+/', '_', $nama_kegiatan);
        $nama_kegiatan = trim($nama_kegiatan, '_');
        $zipName = 'Nota_Dinas_' . $nama_kegiatan . '_' . time() . '.zip';
        
        $tempFile = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($peserta as $data) {
                $filePath = './nota-dinas/' . $data['file'];
                if (file_exists($filePath) && is_file($filePath)) {
                    // Membersihkan nama pegawai dari karakter ilegal Windows
                    $nama_peserta = preg_replace('/[\\\\\/:\*\?"<>\|]/', '', $data['nama_pegawai']);
                    $nama_peserta = preg_replace('/[^A-Za-z0-9]+/', '_', $nama_peserta);
                    $nama_peserta = trim($nama_peserta, '_');
                    
                    // Gunakan nama peserta dan nama kegiatan utuh dengan ekstensi .pdf
                    $zip->addFile($filePath, $nama_peserta . '_' . $nama_kegiatan . '.pdf');
                }
            }
            $zip->close();

            if (file_exists($tempFile) && filesize($tempFile) > 0) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipName . '"');
                header('Content-Length: ' . filesize($tempFile));
                readfile($tempFile);
                unlink($tempFile);
                exit;
            } else {
                if(file_exists($tempFile)) unlink($tempFile);
                return redirect()->back()->with('error', 'Gagal membuat file ZIP atau file Nota Dinas tidak ditemukan di server.');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal membuka file ZIP.');
        }
    } else {
        return redirect()->back()->with('error', 'Ekstensi ZipArchive tidak tersedia di server.');
    }
  }

  public function export_laporan()
  {
    $date_range = $this->request->getVar('date_range');
    $sumber_anggaran = $this->request->getVar('sumber_anggaran');
    $jenis_perjalanan = $this->request->getVar('jenis_perjalanan');
    $format = $this->request->getVar('format');

    // Parse Date Range
    $start_date = '';
    $end_date = '';
    if ($date_range) {
      $dates = explode(' - ', $date_range);
      if (count($dates) == 2) {
        $start_date = date('Y-m-d', strtotime($dates[0]));
        $end_date = date('Y-m-d', strtotime($dates[1]));
      }
    }

    // Builder
    $builder = $this->sinadiPerjalananDinasPesertaModel->builder();
    $builder->select('sinadi_perjalanan_dinas_peserta.*, sinadi_perjalanan_dinas.nama_kegiatan, sinadi_perjalanan_dinas.sumber_anggaran as sumber_dana');
    $builder->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan = sinadi_perjalanan_dinas_peserta.id_perjalanan');

    // Filter Date (using tgl_pergi)
    if ($start_date && $end_date) {
      $builder->where('sinadi_perjalanan_dinas_peserta.tgl_pergi >=', $start_date);
      $builder->where('sinadi_perjalanan_dinas_peserta.tgl_pergi <=', $end_date);
    }
    
    // Filter Status Surat
    $builder->where('sinadi_perjalanan_dinas.status_surat', 'Disetujui');

    // Filter Sumber Anggaran
    if ($sumber_anggaran && $sumber_anggaran != 'Semua') {
      $builder->where('sinadi_perjalanan_dinas.sumber_anggaran', $sumber_anggaran);
    }

    // Filter Jenis Perjalanan
    if ($jenis_perjalanan && $jenis_perjalanan != 'Semua') {
      if ($jenis_perjalanan == 'Dalam Kota') {
        $builder->where('sinadi_perjalanan_dinas_peserta.kota = sinadi_perjalanan_dinas_peserta.kota_asal');
      } else {
        $builder->where('sinadi_perjalanan_dinas_peserta.kota != sinadi_perjalanan_dinas_peserta.kota_asal');
      }
    }

    $laporan = $builder->get()->getResultArray();
    $data['laporan'] = $laporan;
    $data['title_pdf'] = 'Laporan Perjalanan Dinas';
    $data['date_range'] = $date_range;

    // Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->mergeCells('A1:A2'); $sheet->setCellValue('A1', 'No');
    $sheet->mergeCells('B1:B2'); $sheet->setCellValue('B1', 'Nama Pegawai');
    $sheet->mergeCells('C1:C2'); $sheet->setCellValue('C1', 'NIP');
    $sheet->mergeCells('D1:D2'); $sheet->setCellValue('D1', 'Jabatan');
    $sheet->mergeCells('E1:E2'); $sheet->setCellValue('E1', 'Golongan');
    $sheet->mergeCells('F1:F2'); $sheet->setCellValue('F1', 'Nomor ST');
    $sheet->mergeCells('G1:G2'); $sheet->setCellValue('G1', 'Tanggal ST');
    $sheet->mergeCells('H1:H2'); $sheet->setCellValue('H1', 'Nomor SPPD');
    $sheet->mergeCells('I1:I2'); $sheet->setCellValue('I1', 'Tanggal SPPD');
    $sheet->mergeCells('J1:J2'); $sheet->setCellValue('J1', 'Kota Asal');
    $sheet->mergeCells('K1:K2'); $sheet->setCellValue('K1', 'Kota Tujuan');

    $sheet->mergeCells('L1:N1'); $sheet->setCellValue('L1', 'Transport');
    $sheet->setCellValue('L2', 'Hari');
    $sheet->setCellValue('M2', 'Nilai');
    $sheet->setCellValue('N2', 'Jumlah');

    $sheet->mergeCells('O1:Q1'); $sheet->setCellValue('O1', 'Uang Harian');
    $sheet->setCellValue('O2', 'Hari');
    $sheet->setCellValue('P2', 'Nilai');
    $sheet->setCellValue('Q2', 'Jumlah');

    $sheet->mergeCells('R1:T1'); $sheet->setCellValue('R1', 'Penginapan');
    $sheet->setCellValue('R2', 'Hari');
    $sheet->setCellValue('S2', 'Nilai');
    $sheet->setCellValue('T2', 'Jumlah');

    $sheet->mergeCells('U1:U2'); $sheet->setCellValue('U1', 'Jumlah Transfer');
    $sheet->mergeCells('V1:V2'); $sheet->setCellValue('V1', 'Keterangan');

    // Styling Headers
    $sheet->getStyle('A1:V2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:V2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A1:V2')->getFont()->setBold(true);
    $sheet->getStyle('A1:V2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $row = 3;
    $no = 1;
    
    $total_transport = 0;
    $total_harian = 0;
    $total_penginapan = 0;
    $total_transfer = 0;

    foreach ($laporan as $item) {
      $sheet->setCellValue('A' . $row, $no++);
      $sheet->setCellValue('B' . $row, $item['nama_pegawai']);
      $sheet->setCellValueExplicit('C' . $row, $item['nip_pegawai'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->setCellValue('D' . $row, $item['jabatan_pegawai']);
      $sheet->setCellValue('E' . $row, $item['golongan']);
      $sheet->setCellValue('F' . $row, $item['no_surat_tugas']);
      $sheet->setCellValue('G' . $row, $item['tgl_surat_tugas']);
      $sheet->setCellValue('H' . $row, $item['no_sppd']);
      $sheet->setCellValue('I' . $row, $item['tgl_sppd']);
      $sheet->setCellValue('J' . $row, $item['kota_asal']);
      $sheet->setCellValue('K' . $row, $item['kota']);

      // Transport
      $sheet->setCellValue('L' . $row, $item['transport_hari']);
      $sheet->setCellValue('M' . $row, $item['transport_nominal']);
      $sheet->setCellValue('N' . $row, $item['transport_jumlah']);
      $total_transport += $item['transport_jumlah'];

      // Uang Harian
      $sheet->setCellValue('O' . $row, $item['harian_hari']);
      $sheet->setCellValue('P' . $row, $item['harian_nominal']);
      $sheet->setCellValue('Q' . $row, $item['harian_jumlah']);
      $total_harian += $item['harian_jumlah'];

      // Penginapan
      $sheet->setCellValue('R' . $row, $item['penginapan_hari']);
      $sheet->setCellValue('S' . $row, $item['penginapan_nominal']);
      $sheet->setCellValue('T' . $row, $item['penginapan_jumlah']);
      $total_penginapan += $item['penginapan_jumlah'];

      $sheet->setCellValue('U' . $row, $item['jumlah_transfer']);
      $total_transfer += $item['jumlah_transfer'];

      $sheet->setCellValue('V' . $row, $item['nama_kegiatan']);

      $sheet->getStyle('A' . $row . ':V' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      
      // Format Currency
      $sheet->getStyle('M' . $row . ':N' . $row)->getNumberFormat()->setFormatCode('#,##0');
      $sheet->getStyle('P' . $row . ':Q' . $row)->getNumberFormat()->setFormatCode('#,##0');
      $sheet->getStyle('S' . $row . ':U' . $row)->getNumberFormat()->setFormatCode('#,##0');

      $row++;
    }
    
    // Footer Total
    $sheet->mergeCells('A' . $row . ':M' . $row);
    $sheet->setCellValue('A' . $row, 'TOTAL');
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);

    $sheet->setCellValue('N' . $row, $total_transport);
    $sheet->setCellValue('Q' . $row, $total_harian);
    $sheet->setCellValue('T' . $row, $total_penginapan);
    $sheet->setCellValue('U' . $row, $total_transfer);
    
    $sheet->getStyle('A' . $row . ':V' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A' . $row . ':V' . $row)->getFont()->setBold(true);
    
    // Format Currency for Total Row
    $sheet->getStyle('N' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('Q' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('T' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('U' . $row)->getNumberFormat()->setFormatCode('#,##0');

    // Auto size columns
    foreach (range('A', 'V') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'Laporan_Sinadi_' . date('YmdHis') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
  }
}
