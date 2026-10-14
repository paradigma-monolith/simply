<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Admin extends BaseController
{

  private function konversiHari($jumlahHari)
  {
    $tahun = floor($jumlahHari / 365); // Hitung jumlah tahun
    $sisaHari = $jumlahHari % 365; // Hitung sisa hari setelah diambil tahun

    $bulan = floor($sisaHari / 30); // Hitung jumlah bulan
    $sisaHari = $sisaHari % 30; // Hitung sisa hari setelah diambil bulan

    return array("tahun" => $tahun, "bulan" => $bulan, "hari" => $sisaHari);
  }

  public function index()
  {
    return redirect()->to('home');
  }

  public function user()
  {
    $data['title'] = "Admin | Data Pengguna"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "user";
    $data['users'] =  $this->userModel->join('role_access', 'role_access.id_role_access=user.id_role_access')->findAll();

    return view('users/pengguna', $data);
  }

  public function tambah_user()
  {
    $data['title'] = "Admin | Tambah Data Pengguna";
    $data['activeMenu'] = "user";
    $data['validation'] = \Config\Services::validation();

    $data['role_access'] = $this->roleAccessModel->orderBy('role', 'asc')->findAll();

    return view('crud/tambah_pengguna', $data);
  }

  public function editPengguna($id = null)
  {
    $data['title'] = "Admin | Ubah Data Pengguna";
    $data['activeMenu'] = "user";
    $data['validation'] = \Config\Services::validation();

    $data['user'] = $this->userModel->join('role_access', 'role_access.id_role_access=user.id_role_access')->where('id_user', $id)->first();

    $data['role_access'] = $this->roleAccessModel->orderBy('role', 'asc')->findAll();

    return view('crud/edit_pengguna', $data);
  }

  public function unit_kerja()
  {
    $data['title'] = "Admin | Unit Kerja"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "unit_kerja";

    $data['unit_kerja'] = $this->unitKerjaModel->joinAll(
      $select   = 'user.nama, unit_kerja.*, unit_kerja.updated_at AS time',
      $with     = 'user',
      $value    = 'user.id_user=unit_kerja.updated_by',
      $where    = '',
      $orderBy  = 'id_user',
      $order    = 'desc',
      $group    = '',
      $limit    = ''
    )->findAll();

    return view('users/unit_kerja', $data);
  }

  public function tambah_unit_kerja()
  {
    $data['title'] = "Admin | Tambah Unit Kerja";
    $data['activeMenu'] = "unit_kerja";
    $data['validation'] = \Config\Services::validation();

    return view('crud/tambah_unit_kerja', $data);
  }

  public function editUnitKerja($id = null)
  {
    $data['title'] = "Admin | Ubah Unit Kerja";
    $data['activeMenu'] = "unit_kerja";
    $data['validation'] = \Config\Services::validation();

    $data['unit_kerja'] = $this->unitKerjaModel->where('id_unit_kerja', $id)->first();

    return view('crud/edit_unit_kerja', $data);
  }

  public function pegawai()
  {
    $data['title'] = "Admin | Data Pegawai"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "pegawai";

    $data['pegawai'] = $this->dataPegawaiModel->joinData();

    $tanggal_sekarang = date("Y-m-d"); // Hitung tanggal sekarang sekali di luar loop

    foreach ($data['pegawai'] as $key => $value) {
      $tanggal_masuk = $value['tgl_awal_masa'];
      $selisih_hari = (strtotime($tanggal_sekarang) - strtotime($tanggal_masuk)) / (60 * 60 * 24);

      // Periksa apakah masa kerja berbeda sebelum menyimpan ke database
      if ($selisih_hari != $value['masa_kerja']) {
        $update = [
          'id_data_pegawai' => $value['id_data_pegawai'],
          'masa_kerja' => $selisih_hari,
        ];
        $data['pegawai'][$key]['masa_kerja'] = $selisih_hari;
        $this->dataPegawaiModel->save($update);
      }

      // Tambahkan lama_kerja untuk setiap pegawai
      $data['pegawai'][$key]['lama_kerja'] = $this->konversiHari($selisih_hari);
    }

    return view('users/pegawai', $data);
  }

  public function tambah_pegawai()
  {
    $data['title'] = "Admin | Tambah Data Pegawai"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "pegawai";
    $data['validation'] = \Config\Services::validation();

    $data['role_access'] = $this->roleAccessModel->orderBy('role', 'asc')->findAll();
    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();

    $data['jabatan'] = $this->jabatanModel->findAll();
    $data['golongan'] = $this->golonganModel->findAll();
    $data['status_pegawai'] = $this->statusPegawaiModel->findAll();
    $data['substansi'] = $this->substansiModel->findAll();
    $data['tim_kerja'] = $this->timKerjaModel->findAll();

    return view('crud/tambah_pegawai', $data);
  }

  public function editPegawai($id = null)
  {
    $data['title'] = "Admin | Ubah Data Pegawai";
    $data['activeMenu'] = "user";
    $data['validation'] = \Config\Services::validation();

    $data['user'] = $this->dataPegawaiModel
      ->select('data_pegawai.*, unit_kerja.*, jabatan.*, golongan.*, status_pegawai.*, substansi.*, tim_kerja.*')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
      ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi')
      ->join('tim_kerja', 'tim_kerja.id_tim_kerja=data_pegawai.id_tim_kerja')
      ->where('data_pegawai.id_data_pegawai', $id)
      ->first();

    $data['jabatan'] = $this->jabatanModel->findAll();
    $data['golongan'] = $this->golonganModel->findAll();
    $data['status_pegawai'] = $this->statusPegawaiModel->findAll();
    $data['substansi'] = $this->substansiModel->findAll();
    $data['tim_kerja'] = $this->timKerjaModel->findAll();

    $data['role_access'] = $this->roleAccessModel->orderBy('role', 'asc')->findAll();
    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->where('status_unit_kerja', 'Aktif')->findAll();
    

    return view('crud/edit_pegawai', $data);
  }

  public function skema_titimangsa()
  {
    $data['title'] = "Admin | Skema Titimangsa"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "skema_titimangsa";

    $data['skema'] = $this->skemaTitimangsaModel
      ->select('skema_titimangsa.jenis_surat, unit_kerja.unit_kerja, unit_kerja.lokasi,
    jabatan_kepegawaian.jabatan AS jabatan_kepegawaian, kepegawaian.nama AS nama_kepegawaian, kepegawaian.nip AS nip_kepegawaian,
    jabatan_pejabat.jabatan AS jabatan_pejabat, pejabat.nama AS nama_pejabat, pejabat.nip AS nip_pejabat,
    jabatan_atasan.jabatan AS jabatan_atasan, atasan.nama AS nama_atasan, atasan.nip AS nip_atasan, 
    skema_titimangsa.updated_at AS time, user.nama AS updated
    ')
      ->join('data_pegawai AS kepegawaian', 'kepegawaian.id_data_pegawai=skema_titimangsa.kepegawaian', 'LEFT')
      ->join('data_pegawai AS pejabat', 'pejabat.id_data_pegawai=skema_titimangsa.pejabat', 'LEFT')
      ->join('data_pegawai AS atasan', 'atasan.id_data_pegawai=skema_titimangsa.atasan', 'LEFT')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->join('user', 'user.id_user=skema_titimangsa.updated_by')
      ->join('jabatan AS jabatan_kepegawaian', 'jabatan_kepegawaian.id_jabatan=kepegawaian.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_pejabat', 'jabatan_pejabat.id_jabatan=pejabat.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_atasan', 'jabatan_atasan.id_jabatan=atasan.id_jabatan', 'LEFT')
      ->orderBy('skema_titimangsa.updated_at', 'desc')
      ->findAll();

    return view('users/skema_titimangsa', $data);
  }

  public function tambah_titimangsa()
  {
    $data['title'] = "Admin | Tambah Skema Titimangsa"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "skema_titimangsa";

    $data['unit_kerja'] = $this->unitKerjaModel->findAll();

    return view('crud/tambah_titimangsa', $data);
  }

  public function jenis()
  {
    $data['title'] = "Admin | Jenis Cuti"; //'user, unit_kerja, pegawai, skema_titimangsa, jenis'
    $data['activeMenu'] = "jenis";

    return view('users/jenis', $data);
  }
}
