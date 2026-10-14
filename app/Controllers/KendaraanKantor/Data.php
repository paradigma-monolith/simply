<?php

namespace App\Controllers\KendaraanKantor;

use App\Controllers\BaseController;
use Config\Services;

class Data extends BaseController
{
  public function index()
  {
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

    $data['getPegawai'] = $this->dataPegawaiModel->joinAll(
      $select   = '*',
      $with     = 'golongan',
      $value    = 'id_golongan',
      $where    = ['data_pegawai.status_bekerja' => 'Aktif'],
      $orderBy  = 'golongan.urutan',
      $order    = 'desc',
      $group    = '',
      $limit    = ''
    )->findAll();

    return view('kendaraan_kantor/index', $data);
  }
  public function verifikator()
  {
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
      ->select('kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat, data_pegawai.nip, kendaraan_kantor_daftar.gambar, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_riwayat.penumpang, kendaraan_kantor_riwayat.tujuan, kendaraan_kantor_riwayat.keterangan, data_pegawai.nama, kendaraan_kantor_riwayat.tanggal, DATE(kendaraan_kantor_riwayat.created_at) as date')
      ->groupStart()
      ->where("JSON_UNQUOTE(JSON_EXTRACT(tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) >= CURDATE()")
      ->where("kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar", "0")
      ->groupEnd()
      ->join('data_pegawai', 'data_pegawai.id_user=kendaraan_kantor_riwayat.created_by')
      ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar=kendaraan_kantor_riwayat.id_kendaraan_kantor_daftar', 'left')
      ->orderBy('tanggal', 'ASC')
      ->findAll();

    return view('kendaraan_kantor/index_verifikator', $data);
  }
  public function daftar_kendaraan()
  {
    $data['title'] = "Peminjaman Kendaraan | Daftar Kendaraan"; // kelola_data_cuti
    $data['activeMenu'] = "daftar_kendaraan";

    $data['getData'] = $this->kendaraanKantorDaftarModel->findAll();

    return view("kendaraan_kantor/daftar_kendaraan", $data);
  }
  public function riwayat_peminjaman()
  {
    $data['title'] = "Peminjaman Kendaraan | Riwayat Peminjaman"; // kelola_data_cuti
    $data['activeMenu'] = "riwayat_peminjaman";

    $data['getData'] = $this->kendaraanKantorRiwayatModel
      ->select("kendaraan_kantor_daftar.*, kendaraan_kantor_riwayat.*, user.nama as peminjam, user.username, JSON_UNQUOTE(JSON_EXTRACT(kendaraan_kantor_riwayat.tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) as tanggal_akhir")
      ->join('kendaraan_kantor_daftar', 'id_kendaraan_kantor_daftar', 'LEFT')
      ->join('user', 'kendaraan_kantor_riwayat.created_by=user.id_user')
      ->orderBy('tanggal_akhir', 'desc')->findAll();

    return view("kendaraan_kantor/riwayat_peminjaman", $data);
  }
  public function update_riwayat($id)
  {
    $data['title'] = "Peminjaman Kendaraan | Riwayat Peminjaman"; // kelola_data_cuti
    $data['activeMenu'] = "riwayat_peminjaman";

    $data['getData'] = $this->kendaraanKantorRiwayatModel
      ->select("kendaraan_kantor_daftar.*, kendaraan_kantor_riwayat.*, user.nama as peminjam, user.username, JSON_UNQUOTE(JSON_EXTRACT(kendaraan_kantor_riwayat.tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) as tanggal_akhir")
      ->join('kendaraan_kantor_daftar', 'id_kendaraan_kantor_daftar')
      ->join('user', 'kendaraan_kantor_riwayat.created_by=user.id_user')
      ->where('kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat', $id)
      ->orderBy('tanggal_akhir', 'desc')->first();

    $tanggalArray = json_decode($data['getData']['tanggal'], true);

    $this->kendaraanKantorRiwayatModel->like('tanggal', $tanggalArray[0]);
    for ($i = 1; $i < count($tanggalArray); $i++) {
      $this->kendaraanKantorRiwayatModel->orLike('tanggal', $tanggalArray[$i]);
    }
    $getDataPenumpang = $this->kendaraanKantorRiwayatModel->find();
    $arrayDataPenumpang = [];
    foreach ($getDataPenumpang as $get) {
      $arrayDataPenumpang += json_decode($get['penumpang'], true);
    }
    $penumpangDisertai = json_decode($data['getData']['penumpang'], true);
    $penumpangDikecualikan = array_values(array_diff($arrayDataPenumpang, $penumpangDisertai));

    if ($penumpangDikecualikan) {
      $this->dataPegawaiModel->where("id_data_pegawai !=", $penumpangDikecualikan[0]);
      for ($i = 1; $i < count($penumpangDikecualikan); $i++) {
        $this->dataPegawaiModel->where("id_data_pegawai !=", $penumpangDikecualikan[$i]);
      }
    }
    $data['getPegawai'] = $this->dataPegawaiModel
      ->where('status_bekerja', 'Aktif')
      ->join('golongan', 'id_golongan')
      ->orderBy('urutan', 'desc')->findAll();

    return view("kendaraan_kantor/riwayat_peminjaman_form_update", $data);
  }
  public function update_daftar($id)
  {
    $data['title'] = "Peminjaman Kendaraan | Daftar Kendaraan"; // kelola_data_cuti
    $data['activeMenu'] = "daftar_kendaraan";

    $data['getData'] = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', $id)->first();

    return view("kendaraan_kantor/daftar_kendaraan_form_update", $data);
  }
  public function tambah_daftar()
  {
    $data['title'] = "Peminjaman Kendaraan | Daftar Kendaraan";
    $data['activeMenu'] = "daftar_kendaraan";

    return view("kendaraan_kantor/daftar_kendaraan_form_tambah", $data);
  }
  public function pengaturan()
  {
    $data['title'] = "Kendaraan Kantor | Verifikator";
    $data['activeMenu'] = "kendaraanKantorPengaturan";
    $data['akses'] = $this->dataPegawaiModel
      ->select('user.username, data_pegawai.nama, data_pegawai.id_data_pegawai, kendaraan_kantor_verifikator.pin, kendaraan_kantor_verifikator.id_kendaraan_kantor_verifikator')
      ->join('kendaraan_kantor_verifikator', 'kendaraan_kantor_verifikator.id_data_pegawai=data_pegawai.id_data_pegawai', 'LEFT')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where("data_pegawai.status_bekerja", "Aktif")
      ->where("user.username", session()->get('username'))
      ->first();

    if (session()->get("role") != "Admin") {
      if ($data['akses'] && $data['akses']['id_data_pegawai']) {
        if ($data['akses']['id_kendaraan_kantor_verifikator'] == null) {
          $data['activeMenu'] = "kendaraanKantorBlockir";
        }
      } else {
        $data['activeMenu'] = "kendaraanKantorBlockir";
      }
    }


    $data['user'] = $this->dataPegawaiModel
      ->select('data_pegawai.nama, data_pegawai.id_data_pegawai, kendaraan_kantor_verifikator.pin, kendaraan_kantor_verifikator.id_kendaraan_kantor_verifikator')
      ->join('kendaraan_kantor_verifikator', 'kendaraan_kantor_verifikator.id_data_pegawai=data_pegawai.id_data_pegawai', 'LEFT')
      ->where("data_pegawai.status_bekerja", "Aktif")
      ->orderBy("data_pegawai.nama", "ASC")
      ->findAll();
    // d($data['akses']);
    // dd($data['user']);
    return view("kendaraan_kantor/setting", $data);
  }
  public function monitoring_kendaraan() 
  {
    return view('monitoring/monitoring_kendaraan_kantor');
  }
}
