<?php

namespace App\Controllers\Infografis;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Crud extends BaseController
{
  use ResponseTrait;
  public function tambah_kapal()
  {
    // Pengecekan tanggal
    // Mendefinisikan tanggal yang akan diperiksa
    $tanggal = $this->request->getVar("tanggal");

    // Membuat objek DateTime dari tanggal yang diperiksa
    $tanggal_objek = new \DateTime($tanggal);

    // Membuat objek DateTime dari tanggal yang dibandingkan (12 April 2023)
    $tanggal_banding = new \DateTime("2023-04-13");

    // Memeriksa apakah tanggal yang diperiksa sebelum 12 April 2023
    // if ($tanggal_objek < $tanggal_banding) {
    //   session()->setFlashdata('error', 'Data gagal ditambahkan. <br/> <span>Penginputan awal dimulai dari tanggal 13 April 2023</span>');
    //   return redirect()->to('infografis/tambah_kapal')->withInput();
    // }

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
      ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($getData)) {
      session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
      return redirect()->to('infografis/kapal');
    }

    $date = $this->request->getVar("tanggal");
    $getData = $this->infografisKapalModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

    if ($getData) {
      session()->setFlashdata('info', 'Data pada tanggal ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      return redirect()->to('infografis/update_kapal/' . $getData['id_infografis_kapal']);
    }


    $kapal_datang_luar           = $this->request->getVar('kapal_datang_luar') == '' ? 0 : $this->request->getVar('kapal_datang_luar');
    $abk_datang_luar             = $this->request->getVar('abk_datang_luar') == '' ? 0 : $this->request->getVar('abk_datang_luar');
    $penumpang_datang_luar       = $this->request->getVar('penumpang_datang_luar') == '' ? 0 : $this->request->getVar('penumpang_datang_luar');
    $kapal_datang_negeri         = $this->request->getVar('kapal_datang_negeri') == '' ? 0 : $this->request->getVar('kapal_datang_negeri');
    $abk_datang_negeri           = $this->request->getVar('abk_datang_negeri') == '' ? 0 : $this->request->getVar('abk_datang_negeri');
    $penumpang_datang_negeri     = $this->request->getVar('penumpang_datang_negeri') == '' ? 0 : $this->request->getVar('penumpang_datang_negeri');
    $kapal_berangkat_luar           = $this->request->getVar('kapal_berangkat_luar') == '' ? 0 : $this->request->getVar('kapal_berangkat_luar');
    $abk_berangkat_luar             = $this->request->getVar('abk_berangkat_luar') == '' ? 0 : $this->request->getVar('abk_berangkat_luar');
    $penumpang_berangkat_luar       = $this->request->getVar('penumpang_berangkat_luar') == '' ? 0 : $this->request->getVar('penumpang_berangkat_luar');
    $kapal_berangkat_negeri         = $this->request->getVar('kapal_berangkat_negeri') == '' ? 0 : $this->request->getVar('kapal_berangkat_negeri');
    $abk_berangkat_negeri           = $this->request->getVar('abk_berangkat_negeri') == '' ? 0 : $this->request->getVar('abk_berangkat_negeri');
    $penumpang_berangkat_negeri     = $this->request->getVar('penumpang_berangkat_negeri') == '' ? 0 : $this->request->getVar('penumpang_berangkat_negeri');

    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();

    $createTanggal = $this->request->getVar("tanggal");
    if ($createTanggal == date("Y-m-d")) {
      // Mendefinisikan tanggal yang akan diubah
      $tanggal = $this->request->getVar("tanggal");

      // Mendapatkan waktu saat ini
      $waktu_sekarang = date("H:i:s");

      // Menggabungkan tanggal dan waktu saat ini
      $createTanggal = $tanggal . " " . $waktu_sekarang;
    }
    $data = [
      'unit_kerja'                => $unit_kerja['unit_kerja'],
      'kapal_datang_luar'         => $kapal_datang_luar,
      'abk_datang_luar'           => $abk_datang_luar,
      'penumpang_datang_luar'     => $penumpang_datang_luar,
      'kapal_datang_negeri'       => $kapal_datang_negeri,
      'abk_datang_negeri'         => $abk_datang_negeri,
      'penumpang_datang_negeri'   => $penumpang_datang_negeri,
      'kapal_berangkat_luar'         => $kapal_berangkat_luar,
      'abk_berangkat_luar'           => $abk_berangkat_luar,
      'penumpang_berangkat_luar'     => $penumpang_berangkat_luar,
      'kapal_berangkat_negeri'       => $kapal_berangkat_negeri,
      'abk_berangkat_negeri'         => $abk_berangkat_negeri,
      'penumpang_berangkat_negeri'   => $penumpang_berangkat_negeri,
      'status'                    => 0,
      'created_by'                => $by['id_user'],
      'updated_by'                => $by['id_user'],
      'updated_pnbp'              => '',
      'created_at'                => $createTanggal
    ];

    if ($this->infografisKapalModel->save($data)) {
      $newId = $this->infografisKapalModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-save bg-primary',
        'id_table'    => $newId,
        'table'       => 'infografis_kapal',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' menambahkan data Infografis Kapal',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('infografis/kapal');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('infografis/tambah_kapal')->withInput();
    }
  }
  public function update_kapal()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();


    $kapal_datang_luar           = $this->request->getVar('kapal_datang_luar') == '' ? 0 : $this->request->getVar('kapal_datang_luar');
    $abk_datang_luar             = $this->request->getVar('abk_datang_luar') == '' ? 0 : $this->request->getVar('abk_datang_luar');
    $penumpang_datang_luar       = $this->request->getVar('penumpang_datang_luar') == '' ? 0 : $this->request->getVar('penumpang_datang_luar');
    $kapal_datang_negeri         = $this->request->getVar('kapal_datang_negeri') == '' ? 0 : $this->request->getVar('kapal_datang_negeri');
    $abk_datang_negeri           = $this->request->getVar('abk_datang_negeri') == '' ? 0 : $this->request->getVar('abk_datang_negeri');
    $penumpang_datang_negeri     = $this->request->getVar('penumpang_datang_negeri') == '' ? 0 : $this->request->getVar('penumpang_datang_negeri');
    $kapal_berangkat_luar           = $this->request->getVar('kapal_berangkat_luar') == '' ? 0 : $this->request->getVar('kapal_berangkat_luar');
    $abk_berangkat_luar             = $this->request->getVar('abk_berangkat_luar') == '' ? 0 : $this->request->getVar('abk_berangkat_luar');
    $penumpang_berangkat_luar       = $this->request->getVar('penumpang_berangkat_luar') == '' ? 0 : $this->request->getVar('penumpang_berangkat_luar');
    $kapal_berangkat_negeri         = $this->request->getVar('kapal_berangkat_negeri') == '' ? 0 : $this->request->getVar('kapal_berangkat_negeri');
    $abk_berangkat_negeri           = $this->request->getVar('abk_berangkat_negeri') == '' ? 0 : $this->request->getVar('abk_berangkat_negeri');
    $penumpang_berangkat_negeri     = $this->request->getVar('penumpang_berangkat_negeri') == '' ? 0 : $this->request->getVar('penumpang_berangkat_negeri');

    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();
    $data = [
      'id_infografis_kapal'       => $this->request->getVar('id_infografis_kapal'),
      'unit_kerja'                => $unit_kerja['unit_kerja'],
      'kapal_datang_luar'         => $kapal_datang_luar,
      'abk_datang_luar'           => $abk_datang_luar,
      'penumpang_datang_luar'     => $penumpang_datang_luar,
      'kapal_datang_negeri'       => $kapal_datang_negeri,
      'abk_datang_negeri'         => $abk_datang_negeri,
      'penumpang_datang_negeri'   => $penumpang_datang_negeri,
      'kapal_berangkat_luar'         => $kapal_berangkat_luar,
      'abk_berangkat_luar'           => $abk_berangkat_luar,
      'penumpang_berangkat_luar'     => $penumpang_berangkat_luar,
      'kapal_berangkat_negeri'       => $kapal_berangkat_negeri,
      'abk_berangkat_negeri'         => $abk_berangkat_negeri,
      'penumpang_berangkat_negeri'   => $penumpang_berangkat_negeri,
      'status'                    => 0,
      'updated_by'                => $by['id_user'],
      'updated_pnbp'              => ''
    ];

    if ($this->infografisKapalModel->save($data)) {
      $newId = $this->infografisKapalModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-edit bg-warning',
        'id_table'    => $newId,
        'table'       => 'infografis_kapal',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' memperbaharui data Infografis Kapal',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('infografis/kapal');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('infografis/update_kapal')->withInput();
    }
  }
  public function delete_kapal($id = null)
  {
    if ($this->infografisKapalModel->delete($id)) :
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      return redirect()->to('infografis/kapal');
    else :
      session()->setFlashdata('error', 'Data gagal dihapus.');
      return redirect()->to('infografis/kapal');
    endif;
  }
  public function tambah_pesawat()
  {

    // Pengecekan tanggal
    // Mendefinisikan tanggal yang akan diperiksa
    $tanggal = $this->request->getVar("tanggal");

    // Membuat objek DateTime dari tanggal yang diperiksa
    $tanggal_objek = new \DateTime($tanggal);

    // Membuat objek DateTime dari tanggal yang dibandingkan (12 April 2023)
    $tanggal_banding = new \DateTime("2023-04-13");

    // Memeriksa apakah tanggal yang diperiksa sebelum 12 April 2023
    // if ($tanggal_objek < $tanggal_banding) {
    //   session()->setFlashdata('error', 'Data gagal ditambahkan. <br/> <span>Penginputan awal dimulai dari tanggal 13 April 2023</span>');
    //   return redirect()->to('infografis/tambah_pesawat')->withInput();
    // }

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
      ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($getData)) {
      session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
      return redirect()->to('infografis/pesawat');
    }

    $date = $this->request->getVar("tanggal");
    $getData = $this->infografisPesawatModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

    if ($getData) {
      session()->setFlashdata('info', 'Data pada tanggal ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      return redirect()->to('infografis/update_pesawat/' . $getData['id_infografis_pesawat']);
    }

    $pesawat_datang_luar         = $this->request->getVar('pesawat_datang_luar') == '' ? 0 : $this->request->getVar('pesawat_datang_luar');
    $crew_datang_luar            = $this->request->getVar('crew_datang_luar') == '' ? 0 : $this->request->getVar('crew_datang_luar');
    $penumpang_datang_luar       = $this->request->getVar('penumpang_datang_luar') == '' ? 0 : $this->request->getVar('penumpang_datang_luar');
    $pesawat_datang_negeri       = $this->request->getVar('pesawat_datang_negeri') == '' ? 0 : $this->request->getVar('pesawat_datang_negeri');
    $crew_datang_negeri          = $this->request->getVar('crew_datang_negeri') == '' ? 0 : $this->request->getVar('crew_datang_negeri');
    $penumpang_datang_negeri     = $this->request->getVar('penumpang_datang_negeri') == '' ? 0 : $this->request->getVar('penumpang_datang_negeri');
    $pesawat_berangkat_luar         = $this->request->getVar('pesawat_berangkat_luar') == '' ? 0 : $this->request->getVar('pesawat_berangkat_luar');
    $crew_berangkat_luar            = $this->request->getVar('crew_berangkat_luar') == '' ? 0 : $this->request->getVar('crew_berangkat_luar');
    $penumpang_berangkat_luar       = $this->request->getVar('penumpang_berangkat_luar') == '' ? 0 : $this->request->getVar('penumpang_berangkat_luar');
    $pesawat_berangkat_negeri       = $this->request->getVar('pesawat_berangkat_negeri') == '' ? 0 : $this->request->getVar('pesawat_berangkat_negeri');
    $crew_berangkat_negeri          = $this->request->getVar('crew_berangkat_negeri') == '' ? 0 : $this->request->getVar('crew_berangkat_negeri');
    $penumpang_berangkat_negeri     = $this->request->getVar('penumpang_berangkat_negeri') == '' ? 0 : $this->request->getVar('penumpang_berangkat_negeri');

    $createTanggal = $this->request->getVar("tanggal");
    if ($createTanggal == date("Y-m-d")) {
      // Mendefinisikan tanggal yang akan diubah
      $tanggal = $this->request->getVar("tanggal");

      // Mendapatkan waktu saat ini
      $waktu_sekarang = date("H:i:s");

      // Menggabungkan tanggal dan waktu saat ini
      $createTanggal = $tanggal . " " . $waktu_sekarang;
    }
    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();
    $data = [
      'unit_kerja'                => $unit_kerja['unit_kerja'],
      'pesawat_datang_luar'       => $pesawat_datang_luar,
      'crew_datang_luar'          => $crew_datang_luar,
      'penumpang_datang_luar'     => $penumpang_datang_luar,
      'pesawat_datang_negeri'     => $pesawat_datang_negeri,
      'crew_datang_negeri'        => $crew_datang_negeri,
      'penumpang_datang_negeri'   => $penumpang_datang_negeri,
      'pesawat_berangkat_luar'       => $pesawat_berangkat_luar,
      'crew_berangkat_luar'          => $crew_berangkat_luar,
      'penumpang_berangkat_luar'     => $penumpang_berangkat_luar,
      'pesawat_berangkat_negeri'     => $pesawat_berangkat_negeri,
      'crew_berangkat_negeri'        => $crew_berangkat_negeri,
      'penumpang_berangkat_negeri'   => $penumpang_berangkat_negeri,
      'status'                    => 0,
      'created_by'                => $by['id_user'],
      'updated_by'                => $by['id_user'],
      'updated_pnbp'              => '',
      'created_at'                => $createTanggal
    ];

    if ($this->infografisPesawatModel->save($data)) {
      $newId = $this->infografisPesawatModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-save bg-primary',
        'id_table'    => $newId,
        'table'       => 'infografis_pesawat',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' menambahkan data Infografis Pesawat',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('infografis/pesawat');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('infografis/tambah_pesawat')->withInput();
    }
  }
  public function update_pesawat()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();


    $pesawat_datang_luar         = $this->request->getVar('pesawat_datang_luar') == '' ? 0 : $this->request->getVar('pesawat_datang_luar');
    $crew_datang_luar            = $this->request->getVar('crew_datang_luar') == '' ? 0 : $this->request->getVar('crew_datang_luar');
    $penumpang_datang_luar       = $this->request->getVar('penumpang_datang_luar') == '' ? 0 : $this->request->getVar('penumpang_datang_luar');
    $pesawat_datang_negeri       = $this->request->getVar('pesawat_datang_negeri') == '' ? 0 : $this->request->getVar('pesawat_datang_negeri');
    $crew_datang_negeri          = $this->request->getVar('crew_datang_negeri') == '' ? 0 : $this->request->getVar('crew_datang_negeri');
    $penumpang_datang_negeri     = $this->request->getVar('penumpang_datang_negeri') == '' ? 0 : $this->request->getVar('penumpang_datang_negeri');
    $pesawat_berangkat_luar         = $this->request->getVar('pesawat_berangkat_luar') == '' ? 0 : $this->request->getVar('pesawat_berangkat_luar');
    $crew_berangkat_luar            = $this->request->getVar('crew_berangkat_luar') == '' ? 0 : $this->request->getVar('crew_berangkat_luar');
    $penumpang_berangkat_luar       = $this->request->getVar('penumpang_berangkat_luar') == '' ? 0 : $this->request->getVar('penumpang_berangkat_luar');
    $pesawat_berangkat_negeri       = $this->request->getVar('pesawat_berangkat_negeri') == '' ? 0 : $this->request->getVar('pesawat_berangkat_negeri');
    $crew_berangkat_negeri          = $this->request->getVar('crew_berangkat_negeri') == '' ? 0 : $this->request->getVar('crew_berangkat_negeri');
    $penumpang_berangkat_negeri     = $this->request->getVar('penumpang_berangkat_negeri') == '' ? 0 : $this->request->getVar('penumpang_berangkat_negeri');

    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();
    $data = [
      'id_infografis_pesawat'     => $this->request->getVar('id_infografis_pesawat'),
      'unit_kerja'                => $unit_kerja['unit_kerja'],
      'pesawat_datang_luar'       => $pesawat_datang_luar,
      'crew_datang_luar'          => $crew_datang_luar,
      'penumpang_datang_luar'     => $penumpang_datang_luar,
      'pesawat_datang_negeri'     => $pesawat_datang_negeri,
      'crew_datang_negeri'        => $crew_datang_negeri,
      'penumpang_datang_negeri'   => $penumpang_datang_negeri,
      'pesawat_berangkat_luar'       => $pesawat_berangkat_luar,
      'crew_berangkat_luar'          => $crew_berangkat_luar,
      'penumpang_berangkat_luar'     => $penumpang_berangkat_luar,
      'pesawat_berangkat_negeri'     => $pesawat_berangkat_negeri,
      'crew_berangkat_negeri'        => $crew_berangkat_negeri,
      'penumpang_berangkat_negeri'   => $penumpang_berangkat_negeri,
      'status'                    => 0,
      'updated_by'                => $by['id_user'],
      'updated_pnbp'              => ''
    ];
    if ($this->infografisPesawatModel->save($data)) {
      $newId = $this->infografisPesawatModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-edit bg-warning',
        'id_table'    => $newId,
        'table'       => 'infografis_pesawat',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' memperbaharui data Infografis Pesawat',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('infografis/pesawat');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('infografis/update_pesawat')->withInput();
    }
  }
  public function delete_pesawat($id = null)
  {
    if ($this->infografisPesawatModel->delete($id)) :
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      return redirect()->to('infografis/pesawat');
    else :
      session()->setFlashdata('error', 'Data gagal dihapus.');
      return redirect()->to('infografis/pesawat');
    endif;
  }

  public function tambah_vaksin()
  {

    // Pengecekan tanggal
    // Mendefinisikan tanggal yang akan diperiksa
    $tanggal = $this->request->getVar("tanggal");

    // Membuat objek DateTime dari tanggal yang diperiksa
    $tanggal_objek = new \DateTime($tanggal);

    // Membuat objek DateTime dari tanggal yang dibandingkan (12 April 2023)
    $tanggal_banding = new \DateTime("2023-04-13");

    // Memeriksa apakah tanggal yang diperiksa sebelum 12 April 2023
    // if ($tanggal_objek < $tanggal_banding) {
    //   session()->setFlashdata('error', 'Data gagal ditambahkan. <br/> <span>Penginputan awal dimulai dari tanggal 13 April 2023</span>');
    //   return redirect()->to('infografis/tambah_vaksin')->withInput();
    // }

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
      ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($getData)) {
      session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
      return redirect()->to('infografis/vaksin');
    }

    $date = $this->request->getVar("tanggal");
    $getData = $this->infografisVaksinModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

    if ($getData) {
      session()->setFlashdata('info', 'Data pada tanggal ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      return redirect()->to('infografis/update_vaksin/' . $getData['id_infografis_vaksin']);
    }

    $phqc         = $this->request->getVar('phqc') == '' ? 0 : $this->request->getVar('phqc');
    $cop          = $this->request->getVar('cop') == '' ? 0 : $this->request->getVar('cop');
    $sscec        = $this->request->getVar('sscec') == '' ? 0 : $this->request->getVar('sscec');
    $sscc         = $this->request->getVar('sscc') == '' ? 0 : $this->request->getVar('sscc');
    $p3k          = $this->request->getVar('p3k') == '' ? 0 : $this->request->getVar('p3k');
    $icv          = $this->request->getVar('icv') == '' ? 0 : $this->request->getVar('icv');
    $sklt         = $this->request->getVar('sklt') == '' ? 0 : $this->request->getVar('sklt');
    $siaos        = $this->request->getVar('siaos') == '' ? 0 : $this->request->getVar('siaos');
    $siaj         = $this->request->getVar('siaj') == '' ? 0 : $this->request->getVar('siaj');
    $kir          = $this->request->getVar('kir') == '' ? 0 : $this->request->getVar('kir');
    $bukes        = $this->request->getVar('bukes') == '' ? 0 : $this->request->getVar('bukes');
    $vaksin_mm    = $this->request->getVar('vaksin_mm') == '' ? 0 : $this->request->getVar('vaksin_mm');
    $vaksin_yf    = $this->request->getVar('vaksin_yf') == '' ? 0 : $this->request->getVar('vaksin_yf');
    $kontraindikasi    = $this->request->getVar('kontraindikasi') == '' ? 0 : $this->request->getVar('kontraindikasi');
    $e_icv    = $this->request->getVar('e_icv') == '' ? 0 : $this->request->getVar('e_icv');
    $vaksin_covid = $this->request->getVar('vaksin_covid') == '' ? 0 : $this->request->getVar('vaksin_covid');
    $vaksin_influenza   = $this->request->getVar('vaksin_influenza') == '' ? 0 : $this->request->getVar('vaksin_influenza');
    $vaksin_tifoid      = $this->request->getVar('vaksin_tifoid') == '' ? 0 : $this->request->getVar('vaksin_tifoid');
    $vaksin_polio      = $this->request->getVar('vaksin_polio') == '' ? 0 : $this->request->getVar('vaksin_polio');

    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();

    $createTanggal = $this->request->getVar("tanggal");
    if ($createTanggal == date("Y-m-d")) {
      // Mendefinisikan tanggal yang akan diubah
      $tanggal = $this->request->getVar("tanggal");

      // Mendapatkan waktu saat ini
      $waktu_sekarang = date("H:i:s");

      // Menggabungkan tanggal dan waktu saat ini
      $createTanggal = $tanggal . " " . $waktu_sekarang;
    }
    $data = [
      'unit_kerja'       => $unit_kerja['unit_kerja'],
      'phqc'             => $phqc,
      'cop'              => $cop,
      'sscec'            => $sscec,
      'sscc'             => $sscc,
      'p3k'              => $p3k,
      'icv'              => $icv,
      'sklt'             => $sklt,
      'siaos'            => $siaos,
      'siaj'             => $siaj,
      'kir'              => $kir,
      'vaksin_mm'        => $vaksin_mm,
      'vaksin_yf'        => $vaksin_yf,
      'kontraindikasi'   => $kontraindikasi,
      'e_icv'            => $e_icv,
      'vaksin_covid'     => $vaksin_covid,
      'vaksin_influenza' => $vaksin_influenza,
      'vaksin_tifoid'    => $vaksin_tifoid,
      'vaksin_polio'     => $vaksin_polio,
      'bukes'            => $bukes,
      'status'           => 0,
      'created_by'       => $by['id_user'],
      'updated_by'       => $by['id_user'],
      'updated_pnbp'     => '',
      'created_at'       => $createTanggal
    ];

    if ($this->infografisVaksinModel->save($data)) {
      $newId = $this->infografisVaksinModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-save bg-primary',
        'id_table'    => $newId,
        'table'       => 'infografis_vaksin',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' menambahkan data Infografis Vaksin dan Dokumen',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('infografis/vaksin');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('infografis/tambah_vaksin')->withInput();
    }
  }
  public function update_vaksin()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();


    $phqc         = $this->request->getVar('phqc') == '' ? 0 : $this->request->getVar('phqc');
    $cop          = $this->request->getVar('cop') == '' ? 0 : $this->request->getVar('cop');
    $sscec        = $this->request->getVar('sscec') == '' ? 0 : $this->request->getVar('sscec');
    $sscc         = $this->request->getVar('sscc') == '' ? 0 : $this->request->getVar('sscc');
    $p3k          = $this->request->getVar('p3k') == '' ? 0 : $this->request->getVar('p3k');
    $icv          = $this->request->getVar('icv') == '' ? 0 : $this->request->getVar('icv');
    $sklt         = $this->request->getVar('sklt') == '' ? 0 : $this->request->getVar('sklt');
    $siaos        = $this->request->getVar('siaos') == '' ? 0 : $this->request->getVar('siaos');
    $siaj         = $this->request->getVar('siaj') == '' ? 0 : $this->request->getVar('siaj');
    $kir          = $this->request->getVar('kir') == '' ? 0 : $this->request->getVar('kir');
    $bukes        = $this->request->getVar('bukes') == '' ? 0 : $this->request->getVar('bukes');
    $vaksin_mm    = $this->request->getVar('vaksin_mm') == '' ? 0 : $this->request->getVar('vaksin_mm');
    $vaksin_yf    = $this->request->getVar('vaksin_yf') == '' ? 0 : $this->request->getVar('vaksin_yf');
    $kontraindikasi    = $this->request->getVar('kontraindikasi') == '' ? 0 : $this->request->getVar('kontraindikasi');
    $e_icv        = $this->request->getVar('e_icv') == '' ? 0 : $this->request->getVar('e_icv');
    $vaksin_covid = $this->request->getVar('vaksin_covid') == '' ? 0 : $this->request->getVar('vaksin_covid');
    $vaksin_influenza   = $this->request->getVar('vaksin_influenza') == '' ? 0 : $this->request->getVar('vaksin_influenza');
    $vaksin_tifoid      = $this->request->getVar('vaksin_tifoid') == '' ? 0 : $this->request->getVar('vaksin_tifoid');
    $vaksin_polio      = $this->request->getVar('vaksin_polio') == '' ? 0 : $this->request->getVar('vaksin_polio');

    $unit_kerja = $this->dataPegawaiModel->select('unit_kerja.unit_kerja')->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('id_user', $by['id_user'])->first();
    $data = [
      'id_infografis_vaksin'  => $this->request->getVar('id_infografis_vaksin'),
      'unit_kerja'            => $unit_kerja['unit_kerja'],
      'phqc'                  => $phqc,
      'cop'                   => $cop,
      'sscec'                 => $sscec,
      'sscc'                  => $sscc,
      'p3k'                   => $p3k,
      'icv'                   => $icv,
      'sklt'                  => $sklt,
      'siaos'                 => $siaos,
      'siaj'                  => $siaj,
      'kir'                   => $kir,
      'vaksin_mm'             => $vaksin_mm,
      'vaksin_yf'             => $vaksin_yf,
      'kontraindikasi'        => $kontraindikasi,
      'e_icv'                 => $e_icv,
      'vaksin_covid'          => $vaksin_covid,
      'vaksin_influenza'      => $vaksin_influenza,
      'vaksin_tifoid'         => $vaksin_tifoid,
      'vaksin_polio'         => $vaksin_polio,
      'bukes'                 => $bukes,
      'status'                => 0,
      'updated_by'            => $by['id_user'],
      'updated_pnbp'          => ''
    ];
    if ($this->infografisVaksinModel->save($data)) {
      $newId = $this->infografisVaksinModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-edit bg-warning',
        'id_table'    => $newId,
        'table'       => 'infografis_vaksin',
        'id_user'     => $by['id_user'],
        'aktivitas'   => $by['nama'] . ' memperbaharui data Infografis Vaksin dan Dokumen',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('infografis/vaksin');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('infografis/update_vaksin')->withInput();
    }
  }
  public function delete_vaksin($id = null)
  {
    if ($this->infografisVaksinModel->delete($id)) :
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      return redirect()->to('infografis/vaksin');
    else :
      session()->setFlashdata('error', 'Data gagal dihapus.');
      return redirect()->to('infografis/vaksin');
    endif;
  }
  public function ajax()
  {
    if ($this->request->isAJAX()) {
      $data = [
        'kapal' => $this->infografisKapalModel->select('
        SUM(kapal_datang_negeri) AS kapal_datang_negeri_kapal,
                  SUM(kapal_datang_luar) AS kapal_datang_luar_kapal,
                  SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri_kapal,
                  SUM(kapal_berangkat_luar) AS kapal_berangkat_luar_kapal,
                  SUM(abk_datang_negeri) AS abk_datang_negeri_kapal,
                  SUM(abk_datang_luar) AS abk_datang_luar_kapal,
                  SUM(abk_berangkat_negeri) AS abk_berangkat_negeri_kapal,
                  SUM(abk_berangkat_luar) AS abk_berangkat_luar_kapal,
                  SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_kapal,
                  SUM(penumpang_datang_luar) AS penumpang_datang_luar_kapal,
                  SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_kapal,
                  SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_kapal,
                  date(created_at) AS created_at_kapal,
                  date(updated_at) AS updated_at
                  ')->groupBy('date(created_at)', 'DESC')->limit(5)->findAll(),
        'pesawat' => $this->infografisPesawatModel->select('
        SUM(pesawat_datang_negeri) AS pesawat_datang_negeri_pesawat,
                  SUM(pesawat_datang_luar) AS pesawat_datang_luar_pesawat,
                  SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri_pesawat,
                  SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar_pesawat,
                  SUM(crew_datang_negeri) AS crew_datang_negeri_pesawat,
                  SUM(crew_datang_luar) AS crew_datang_luar_pesawat,
                  SUM(crew_berangkat_negeri) AS crew_berangkat_negeri_pesawat,
                  SUM(crew_berangkat_luar) AS crew_berangkat_luar_pesawat,
                  SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_pesawat,
                  SUM(penumpang_datang_luar) AS penumpang_datang_luar_pesawat,
                  SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_pesawat,
                  SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_pesawat,
                  date(created_at) AS created_at_pesawat,
                  date(updated_at) AS updated_at
                  ')->groupBy('date(created_at)', 'DESC')->limit(5)->findAll(),
        'vaksin' => $this->infografisVaksinModel->select('
                  unit_kerja AS unit_kerja,
                  SUM(phqc) AS phqc,
                  SUM(cop) AS cop,
                  SUM(sscec) AS sscec,
                  SUM(sscc) AS sscc,
                  SUM(p3k) AS p3k,
                  SUM(icv) AS icv,
                  SUM(sklt) AS sklt,
                  SUM(siaos) AS siaos,
                  SUM(siaj) AS siaj,
                  SUM(kir) AS kir,
                  SUM(bukes) AS bukes,
                  SUM(vaksin_mm) AS vaksin_mm,
                  SUM(vaksin_yf) AS vaksin_yf,
                  SUM(e_icv) AS e_icv,
                  SUM(kontraindikasi) AS kontraindikasi,
                  SUM(vaksin_covid) AS vaksin_covid,
                  date(created_at) AS created_at,
                  date(updated_at) AS updated_at
                  ')
          ->groupBy('date(created_at)', 'DESC')->limit(1)->findAll(),
        'total_kapal' => $this->infografisKapalModel->select('
        SUM(kapal_datang_negeri) AS kapal_datang_negeri_kapal_total,
                  SUM(kapal_datang_luar) AS kapal_datang_luar_kapal_total,
                  SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri_kapal_total,
                  SUM(kapal_berangkat_luar) AS kapal_berangkat_luar_kapal_total,
                  SUM(abk_datang_negeri) AS abk_datang_negeri_kapal_total,
                  SUM(abk_datang_luar) AS abk_datang_luar_kapal_total,
                  SUM(abk_berangkat_negeri) AS abk_berangkat_negeri_kapal_total,
                  SUM(abk_berangkat_luar) AS abk_berangkat_luar_kapal_total,
                  SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_kapal_total,
                  SUM(penumpang_datang_luar) AS penumpang_datang_luar_kapal_total,
                  SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_kapal_total,
                  SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_kapal_total,
                  date(created_at) AS created_at_kapal_total,
                  date(updated_at) AS updated_at
                  ')->findAll(),
        'total_pesawat' => $this->infografisPesawatModel->select('
        SUM(pesawat_datang_negeri) AS pesawat_datang_negeri_pesawat_total,
                  SUM(pesawat_datang_luar) AS pesawat_datang_luar_pesawat_total,
                  SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri_pesawat_total,
                  SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar_pesawat_total,
                  SUM(crew_datang_negeri) AS crew_datang_negeri_pesawat_total,
                  SUM(crew_datang_luar) AS crew_datang_luar_pesawat_total,
                  SUM(crew_berangkat_negeri) AS crew_berangkat_negeri_pesawat_total,
                  SUM(crew_berangkat_luar) AS crew_berangkat_luar_pesawat_total,
                  SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_pesawat_total,
                  SUM(penumpang_datang_luar) AS penumpang_datang_luar_pesawat_total,
                  SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_pesawat_total,
                  SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_pesawat_total,
                  date(created_at) AS created_at_pesawat_total,
                  date(updated_at) AS updated_at
                  ')->findAll(),
        'total_vaksin' => $this->infografisVaksinModel->select('
        SUM(phqc) AS phqc,
                  SUM(cop) AS cop,
                  SUM(sscec) AS sscec,
                  SUM(sscc) AS sscc,
                  SUM(p3k) AS p3k,
                  SUM(icv) AS icv,
                  SUM(sklt) AS sklt,
                  SUM(siaos) AS siaos,
                  SUM(siaj) AS siaj,
                  SUM(kir) AS kir,
                  SUM(bukes) AS bukes,
                  SUM(vaksin_mm) AS vaksin_mm,
                  SUM(vaksin_yf) AS vaksin_yf,
                  SUM(kontraindikasi) AS kontraindikasi,
                  SUM(e_icv) AS e_icv,
                  SUM(vaksin_covid) AS vaksin_covid,
                  date(created_at) AS created_at,
                  date(updated_at) AS updated_at
                  ')->findAll()
      ];
      return $this->respond($data);
    }
    return view('ajax_view');
  }
}
