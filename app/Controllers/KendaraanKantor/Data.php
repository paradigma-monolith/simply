<?php

namespace App\Controllers\KendaraanKantor;

use App\Controllers\BaseController;

class Data extends BaseController
{
  /**
   * Ambil peta [id_data_pegawai => nama] dalam 1 query ringan agar view tidak perlu N request AJAX get_penumpang.
   *
   * @return array<int, string>
   */
  private function getPegawaiNameMap(): array
  {
    $rows = $this->dataPegawaiModel
      ->select('id_data_pegawai, nama')
      ->findAll();
    $map = [];
    foreach ($rows as $row) {
      $map[(int) $row['id_data_pegawai']] = name_capitalize((string) ($row['nama'] ?? ''));
    }
    return $map;
  }

  public function index()
  {
    $nip = \App\Libraries\AbsenNipPersistence::resolveNip($this->request, $this->response);
    $data['nip'] = $nip;

    $data['getPegawai'] = $this->dataPegawaiModel
      ->select('data_pegawai.id_data_pegawai, data_pegawai.nip, data_pegawai.nama, golongan.urutan')
      ->join('golongan', 'golongan.id_golongan = data_pegawai.id_golongan', 'left')
      ->where('data_pegawai.status_bekerja', 'Aktif')
      ->orderBy('golongan.urutan', 'desc')
      ->orderBy('data_pegawai.nama', 'asc')
      ->findAll();

    return view('kendaraan_kantor/index', $data);
  }

  public function verifikator()
  {
    $nip = \App\Libraries\AbsenNipPersistence::resolveNip($this->request, $this->response);
    $data['nip'] = $nip;

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

    $data['pegawaiMap'] = $this->getPegawaiNameMap();

    return view('kendaraan_kantor/index_verifikator', $data);
  }

  public function daftar_kendaraan()
  {
    $data['title'] = "Peminjaman Kendaraan | Daftar Kendaraan";
    $data['activeMenu'] = "daftar_kendaraan";

    $data['getData'] = $this->kendaraanKantorDaftarModel->getBorrowable();

    return view("kendaraan_kantor/daftar_kendaraan", $data);
  }

  public function riwayat_peminjaman()
  {
    $data['title'] = "Peminjaman Kendaraan | Riwayat Peminjaman";
    $data['activeMenu'] = "riwayat_peminjaman";

    $data['getData'] = $this->kendaraanKantorRiwayatModel
      ->select("kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_daftar.gambar, kendaraan_kantor_riwayat.*, user.nama as peminjam, user.username, JSON_UNQUOTE(JSON_EXTRACT(kendaraan_kantor_riwayat.tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) as tanggal_akhir")
      ->join('kendaraan_kantor_daftar', 'id_kendaraan_kantor_daftar', 'LEFT')
      ->join('user', 'kendaraan_kantor_riwayat.created_by=user.id_user', 'LEFT')
      ->orderBy('tanggal_akhir', 'desc')
      ->findAll();

    $data['pegawaiMap'] = $this->getPegawaiNameMap();

    return view("kendaraan_kantor/riwayat_peminjaman", $data);
  }

  public function update_riwayat($id)
  {
    $data['title'] = "Peminjaman Kendaraan | Riwayat Peminjaman";
    $data['activeMenu'] = "riwayat_peminjaman";

    $row = $this->kendaraanKantorRiwayatModel
      ->select("kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, kendaraan_kantor_daftar.gambar, kendaraan_kantor_riwayat.*, user.nama as peminjam, user.username, JSON_UNQUOTE(JSON_EXTRACT(kendaraan_kantor_riwayat.tanggal, CONCAT('$[', JSON_LENGTH(tanggal) - 1, ']'))) as tanggal_akhir")
      ->join('kendaraan_kantor_daftar', 'id_kendaraan_kantor_daftar', 'LEFT')
      ->join('user', 'kendaraan_kantor_riwayat.created_by=user.id_user', 'LEFT')
      ->where('kendaraan_kantor_riwayat.id_kendaraan_kantor_riwayat', (int) $id)
      ->orderBy('tanggal_akhir', 'desc')
      ->first();

    if (!$row) {
      session()->setFlashdata('error', 'Data riwayat peminjaman tidak ditemukan.');
      return redirect()->to('/kendaraan_kantor/riwayat_peminjaman');
    }

    $data['getData'] = $row;
    $tanggalArray = json_decode((string) ($row['tanggal'] ?? '[]'), true);
    if (!is_array($tanggalArray)) {
      $tanggalArray = [];
    }

    $arrayDataPenumpang = [];
    if (!empty($tanggalArray)) {
      $this->kendaraanKantorRiwayatModel->select('penumpang')->groupStart()->like('tanggal', $tanggalArray[0]);
      for ($i = 1; $i < count($tanggalArray); $i++) {
        $this->kendaraanKantorRiwayatModel->orLike('tanggal', $tanggalArray[$i]);
      }
      $this->kendaraanKantorRiwayatModel->groupEnd();
      $getDataPenumpang = $this->kendaraanKantorRiwayatModel->find();

      foreach ($getDataPenumpang as $get) {
        $decoded = json_decode((string) ($get['penumpang'] ?? '[]'), true);
        if (is_array($decoded)) {
          $arrayDataPenumpang = array_merge($arrayDataPenumpang, $decoded);
        }
      }
    }

    $penumpangDisertai = json_decode((string) ($row['penumpang'] ?? '[]'), true);
    if (!is_array($penumpangDisertai)) {
      $penumpangDisertai = [];
    }

    $penumpangDikecualikan = array_values(array_unique(array_diff($arrayDataPenumpang, $penumpangDisertai)));

    if (!empty($penumpangDikecualikan)) {
      $this->dataPegawaiModel->whereNotIn('id_data_pegawai', $penumpangDikecualikan);
    }

    $data['getPegawai'] = $this->dataPegawaiModel
      ->select('data_pegawai.id_data_pegawai, data_pegawai.nama, golongan.urutan')
      ->where('status_bekerja', 'Aktif')
      ->join('golongan', 'id_golongan', 'left')
      ->orderBy('urutan', 'desc')
      ->orderBy('data_pegawai.nama', 'asc')
      ->findAll();

    return view("kendaraan_kantor/riwayat_peminjaman_form_update", $data);
  }

  public function update_daftar($id)
  {
    $data['title'] = "Peminjaman Kendaraan | Daftar Kendaraan";
    $data['activeMenu'] = "daftar_kendaraan";

    $data['getData'] = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', (int) $id)->first();
    if (!$data['getData']) {
      session()->setFlashdata('error', 'Data kendaraan tidak ditemukan.');
      return redirect()->to('/kendaraan_kantor/daftar_kendaraan');
    }

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

    return view("kendaraan_kantor/setting", $data);
  }

  public function monitoring_kendaraan()
  {
    return view('monitoring/monitoring_kendaraan_kantor');
  }
}
