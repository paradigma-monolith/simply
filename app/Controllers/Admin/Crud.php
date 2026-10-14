<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function tambah_user()
  {
    if (!$this->validate([
      'username' => [
        'is_unique[user.username]',
        'errors' => [
          'is_unique' => '{field} sudah ada.'
        ]
      ]
    ])) {
      session()->setFlashdata('error', 'Username "' . $this->request->getVar('username') . '" telah tersedia.');
      return redirect()->to('/admin/tambah_user')->withInput();
    }

    $role = $this->request->getVar(('role_access'));
    if ($role) {
      $getIdRole = $this->roleAccessModel->where('role', $role)->first();
    }

    $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);

    $data = [
      "id_role_access"    => $getIdRole['id_role_access'],
      "nama"              => $this->request->getVar('nama'),
      "username"          => $this->request->getVar('username'),
      "password"          => $password
    ];

    if ($this->userModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('admin/user');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('admin/tambah_user');
    }
  }

  public function editPengguna($id = null)
  {
    $dataPegawai = false;

    if ($get = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')->where('user.id_user', $id)->first()) {
      $dataPegawai = true;
    } else {
      $get = $this->userModel->where('user.id_user', $id)->first();
    }
    
    $username = $this->request->getVar("username");

    if ($username != $get['username']) {
      if (!$this->validate([
        'username' => [
          'is_unique[user.username]',
          'errors' => [
            'is_unique' => '{field} sudah ada.'
          ]
        ]
      ])) {
        session()->setFlashdata('error', 'Username "' . $this->request->getVar('username') . '" telah tersedia.');
        return redirect()->to('/admin/editPengguna/' . $id)->withInput();
      }
    }

    $role = $this->request->getVar(('role_access'));
    if ($role) {
      $getIdRole = $this->roleAccessModel->where('role', $role)->first();
    }

    $data = [
      "id_user"           => $id,
      "id_role_access"    => $getIdRole['id_role_access'],
      "nama"              => $this->request->getVar('nama'),
      "username"          => $this->request->getVar('username'),
    ];

    if ($get['nama'] != $data['nama'] && $dataPegawai == true) {
      $by = session()->get('username');
      $by = $this->userModel->where('username', $by)->first();
      $update = [
        "id_data_pegawai" => $get['id_data_pegawai'],
        "nama"            => $data['nama'],
        'updated_by'      => $by['id_user']
      ];
      if ($dataPegawai == true) {
        $this->dataPegawaiModel->save($update);
      }
    }
    
    $getUsername = $this->userModel->where('username', session()->get('username'))->first();
    
    $changeSession = false;
    if($getUsername['id_user'] == $id) {
      $changeSession = true;
    }

    if ($this->userModel->save($data)) {
      if ($changeSession) {
        session()->set(['nama' => $data['nama']]);
        session()->set(['username' => $data['username']]);
        session()->set(['role' => $role]);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
      }
      return redirect()->to('admin/user');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbarui.');
      return redirect()->to('admin/editPengguna/' . $id);
    }
  }
  
  public function ubahStatusPegawai()
  {
    if ($this->request->isAjax()) {
      $id_user = $this->request->getVar("id_user");

      $getData = $this->userModel->where('id_user', $id_user)->first();

      $status = 'Tidak Aktif';
      if ($getData['status_pegawai_aktif'] != "Aktif") {
        $status = 'Aktif';
      }

      $data = [
        'id_user' => $id_user,
        'status_pegawai_aktif' => $status
      ];

      $msg = [];
      if ($this->userModel->save($data)) {
        $msg = [
          'data' => $status
        ];
      }

      echo json_encode($msg);
    }
  }
  
  public function ubahStatusBekerja()
  {
    if ($this->request->isAjax()) {
      $id_data_pegawai = $this->request->getVar("id_data_pegawai");

      $getData = $this->dataPegawaiModel->where('id_data_pegawai', $id_data_pegawai)->first();

      $status = 'Tidak Aktif';
      if ($getData['status_bekerja'] != "Aktif") {
        $status = 'Aktif';
      }

      $data = [
        'id_data_pegawai' => $id_data_pegawai,
        'status_bekerja' => $status
      ];

      $msg = [];
      if ($this->dataPegawaiModel->save($data)) {
        $msg = [
          'data' => $status
        ];
      }

      echo json_encode($msg);
    }
  }

  public function deletePengguna($id = null)
  {
    if ($this->request->isAJAX()) {

      $get = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')->where('user.id_user', $id)->first();

      if ($this->userModel->delete($id) && $this->dataPegawaiModel->delete($get['id_data_pegawai'])) {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Data gagal dihapuskan.');
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function tambah_unit_kerja()
  {
    if (!$this->validate([
      'unit_kerja' => [
        'is_unique[unit_kerja.unit_kerja]',
        'errors' => [
          'is_unique' => '{field} sudah ada.'
        ]
      ]
    ])) {
      session()->setFlashdata('error', 'Unit kerja "' . $this->request->getVar('unit_kerja') . '" telah tersedia.');
      return redirect()->to('/admin/tambah_unit_kerja')->withInput();
    }

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $data = [
      "unit_kerja"        => $this->request->getVar('unit_kerja'),
      "lokasi"            => $this->request->getVar("lokasi"),
      "created_by"        => $by['id_user'],
      "updated_by"        => $by['id_user']
    ];

    if ($this->unitKerjaModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('admin/unit_kerja');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('admin/tambah_unit_kerja')->withInput();
    }
  }

  public function editUnitKerja($id = null)
  {
    $get = $this->unitKerjaModel->where('id_unit_kerja', $id)->first();
    $unit_kerja = $this->request->getVar("unit_kerja");
    if ($get['unit_kerja'] != $unit_kerja) {
      if (!$this->validate([
        'unit_kerja' => [
          'is_unique[unit_kerja.unit_kerja]',
          'errors' => [
            'is_unique' => '{field} sudah ada.'
          ]
        ]
      ])) {
        session()->setFlashdata('error', 'Unit kerja "' . $this->request->getVar('unit_kerja') . '" telah tersedia.');
        return redirect()->to('/admin/editUnitKerja/' . $id);
      }
    }

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $data = [
      "id_unit_kerja"     => $id,
      "unit_kerja"        => $unit_kerja,
      "lokasi"            => $this->request->getVar("lokasi"),
      "updated_by"        => $by['id_user']
    ];

    if ($this->unitKerjaModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
      return redirect()->to('admin/unit_kerja');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbarui.');
      return redirect()->to('admin/editUnitKerja/' . $id);
    }
  }

  public function deleteUnitKerja($id = null)
  {
    if ($this->request->isAJAX()) {

      if ($this->unitKerjaModel->delete($id)) {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Data gagal dihapuskan.');
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  
  public function ubahStatusUnitKerja()
  {
    if ($this->request->isAjax()) {
      $id_unit_kerja = $this->request->getVar("id_unit_kerja");

      $getData = $this->unitKerjaModel->where('id_unit_kerja', $id_unit_kerja)->first();

      $status = 'Tidak Aktif';
      if ($getData['status_unit_kerja'] != "Aktif") {
        $status = 'Aktif';
      }

      $data = [
        'id_unit_kerja' => $id_unit_kerja,
        'status_unit_kerja' => $status
      ];

      $msg = [];
      if ($this->unitKerjaModel->save($data)) {
        $msg = [
          'data' => $status
        ];
      }

      echo json_encode($msg);
    }
  }

  public function tambah_pegawai()
  {
    if (!$this->validate([
      'username' => [
        'is_unique[user.username]',
        'errors' => [
          'is_unique' => '{field} sudah ada.'
        ]
      ]
    ])) {
      session()->setFlashdata('error', 'Username "' . $this->request->getVar('username') . '" telah tersedia.');
      return redirect()->to('/admin/tambah_pegawai')->withInput();
    }
    if (!$this->validate([
      'nip' => [
        'is_unique[data_pegawai.nip]',
        'errors' => [
          'is_unique' => '{field} sudah ada.'
        ]
      ]
    ])) {
      session()->setFlashdata('error', 'NIP "' . $this->request->getVar('nip') . '" telah tersedia.');
      return redirect()->to('/admin/tambah_pegawai')->withInput();
    }

    $role = $this->request->getVar(('role_access'));
    if ($role) {
      $getIdRole = $this->roleAccessModel->where('role', $role)->first();
    }

    $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);

    $username = $this->request->getVar('username');
    $data = [
      "id_role_access"    => $getIdRole['id_role_access'],
      "nama"              => $this->request->getVar('nama'),
      "username"          => $username,
      "password"          => $password
    ];

    if ($this->userModel->save($data)) {
      $newId = $this->userModel->getInsertID();
      $getUnitKerja = $this->unitKerjaModel->where('unit_kerja', $this->request->getVar('unit_kerja'))->first();
      $tahun      = $this->request->getVar('tahun');
      $bulan      = $this->request->getVar('bulan');
      $hari       = $this->request->getVar('hari');
      $by = session()->get('username');
      $by = $this->userModel->where('username', $by)->first();

      $data = [
        'id_user'           => $newId,
        'id_unit_kerja'     => $getUnitKerja['id_unit_kerja'],
        'id_substansi'      => $this->request->getVar('substansi'),
        'id_tim_kerja'      => $this->request->getVar('tim_kerja'),
        'id_jabatan'        => $this->request->getVar('jabatan'),
        'id_golongan'       => $this->request->getVar('golongan'),
        'id_status_pegawai' => $this->request->getVar('status_pegawai'),
        'nama'              => $this->request->getVar('nama_pegawai'),
        'nip'               => $this->request->getVar('nip'),
        'masa_kerja'        => 0,
        'tgl_awal_masa'     => $this->request->getVar('tgl_masuk'),
        'created_by'        => $by['id_user'],
        'updated_by'        => $by['id_user']
      ];
      
      if ($this->dataPegawaiModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to('admin/user');
      } else {
        session()->setFlashdata('error', 'Data gagal ditambahkan.');
        return redirect()->to('admin/tambah_pegawai')->withInput();
      }
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('admin/tambah_pegawai')->withInput();
    }
  }

  public function editPegawai($id = null)
  {
    $getUnitKerja = $this->unitKerjaModel->where('unit_kerja', $this->request->getVar('unit_kerja'))->first();
    // $tahun        = $this->request->getVar('tahun');
    // $bulan        = $this->request->getVar('bulan');
    // $hari         = $this->request->getVar('hari');
    // $masa_kerja = $tahun . '|' . $bulan . '|' . $hari;

    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();

    $data = [
      'id_data_pegawai'   => $id,
      'id_unit_kerja'     => $getUnitKerja['id_unit_kerja'],
      'id_golongan'       => $this->request->getVar('golongan'),
      'id_jabatan'        => $this->request->getVar('jabatan'),
      'id_status_pegawai' => $this->request->getVar('status_pegawai'),
      'id_substansi'      => $this->request->getVar('substansi'),
      'id_tim_kerja'      => $this->request->getVar('tim_kerja'),
      'nama'              => $this->request->getVar('nama'),
      'nip'               => $this->request->getVar('nip'),
      'tgl_awal_masa'     => $this->request->getVar('tgl_masuk'),
      'updated_by'        => $by['id_user']
    ];

    $get = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')->where('data_pegawai.id_data_pegawai', $id)->first();
    if ($get['nama'] != $data['nama']) {
      $update = [
        'id_user'     => $get['id_user'],
        'nama'        => $data['nama'],
      ];
      $this->userModel->save($update);
    }

    if ($this->dataPegawaiModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
      return redirect()->to('admin/pegawai');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbarui.');
      return redirect()->to('admin/editPegawai')->withInput();
    }
  }

  public function tambah_titimangsa()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first()['id_user'];


    $surat        = $this->request->getVar("surat");
    $unit_kerja   = $this->request->getVar("unit_kerja");
    $atasan       = $this->request->getVar("atasan");
    $pejabat      = $this->request->getVar("pejabat");
    $kepegawaian  = $this->request->getVar("kepegawaian");

    $unit_kerja   = $this->unitKerjaModel->select('id_unit_kerja')->where('unit_kerja', $unit_kerja)->first()['id_unit_kerja'];

    if ($atasan != '') {
      $atasan       = $this->dataPegawaiModel->select('id_data_pegawai')->where('nip', $atasan)->first()['id_data_pegawai'];
    } else {
      $atasan = 0;
    }

    if ($pejabat != '') {
      $pejabat      = $this->dataPegawaiModel->select('id_data_pegawai')->where('nip', $pejabat)->first()['id_data_pegawai'];
    } else {
      $pejabat = 0;
    }
    if ($kepegawaian != '') {
      $kepegawaian      = $this->dataPegawaiModel->select('id_data_pegawai')->where('nip', $kepegawaian)->first()['id_data_pegawai'];
    } else {
      $kepegawaian = 0;
    }

    $data = [
      'id_unit_kerja' => $unit_kerja,
      'jenis_surat'   => $surat,
      'kepegawaian'   => $kepegawaian,
      'pejabat'       => $pejabat,
      'atasan'        => $atasan,
      'created_by'    => $by,
      'updated_by'    => $by
    ];

    $cek = $this->skemaTitimangsaModel
      ->where('id_unit_kerja', $data['id_unit_kerja'])
      ->where('jenis_surat', $data['jenis_surat'])
      ->where('kepegawaian', $data['kepegawaian'])
      ->where('pejabat', $data['pejabat'])
      ->where('atasan', $data['atasan'])
      ->first();

    if ($cek) {
      session()->setFlashdata('error', 'Data skema sudah ada.');
      return redirect()->to('admin/tambah_titimangsa');
    }

    if ($this->skemaTitimangsaModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('admin/skema_titimangsa');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('admin/tambah_titimangsa')->withInput();
    }
  }
}
