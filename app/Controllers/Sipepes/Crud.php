<?php

namespace App\Controllers\Sipepes;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function randomString($length)
  {
    $str        = "";
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $max        = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
      $rand = mt_rand(0, $max);
      $str .= $characters[$rand];
    }
    return $str;
  }

  public function tambah_dokumen_sop()
  {
    if (!$this->validate([
      'unggah_berkas_sop' => [
        'rules' => 'uploaded[unggah_berkas_sop]|max_size[unggah_berkas_sop,5120]|mime_in[unggah_berkas_sop,application/pdf]',
        'errors' => [
          'uploaded' => 'Dokumen belum diunggah!',
          'max_size' => 'Ukuran dokumen terlalu besar!',
          'mime_in' => 'Yang anda pilih bukan PDF!'
        ]
      ]
    ])) {
       $validation = \Config\Services::validation();
       session()->setFlashdata('error', $validation->getError('unggah_berkas_sop'));
      return redirect()->to('/sipepes/tambah_dokumen_sop')->withInput();
    }

    $file = $this->request->getFile("unggah_berkas_sop");
    $newName = $this->randomString(6) . '_' . $file->getName();
    $file->move('./dokumen_sop/', $newName);

    $by = session()->get('username');
    $byLinimasa = $this->userModel->where('username', $by)->first();
    $by = $this->userModel->where('username', $by)->first()['id_user'];

    $getNoDok = $this->request->getVar("no_dokumen");
    $noDok = $this->sipepesModel->where('no_dokumen', $getNoDok)->findAll();

    $getJenisDok = $this->request->getVar("jenis_dokumen");

    $data = [
      'no_dokumen'        => $this->request->getVar("no_dokumen"),
      'judul'             => $this->request->getVar("judul"),
      'unit_kerja'        => $this->request->getVar("unit_kerja"),
      'jenis_dokumen'     => $this->request->getVar("jenis_dokumen"),
      'file'              => $newName,
      'status_pengesahan' => $this->request->getVar("status"),
      'tanggal_pembuatan' => $this->request->getVar("tanggal_pembuatan"),
      'tanggal_revisi'    => $this->request->getVar("tanggal_revisi"),
      'revisi_ke'         => $this->request->getVar("revisi"),
      'tanggal_efektif'   => $this->request->getVar("tanggal_efektif"),
      'diubah_oleh'       => $by,
    ];

    // if (!isset($noDok)) {
    //   dd($data);
    // } else {
    //   echo "Data ada!";
    // }
    // dd($data);

    if ($this->sipepesModel->save($data)) {
      $newId = $this->sipepesModel->getInsertID();
      if ($getJenisDok == "SOP Utama") {
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Utama',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to('sipepes/utama');
      } else if ($getJenisDok == "SOP Pendukung") {
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . 'menambahkan data SOP Pendukung',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to('sipepes/pendukung');
      } else {
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Lainnya',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to('sipepes/lainnya');
      }
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('sipepes/tambah_dokumen_sop')->withInput();
    }
  }

  public function ubah_dokumen_sop($id)
  {
    $by = session()->get('username');
    $byLinimasa = $this->userModel->where('username', $by)->first();
    $by = $this->userModel->where('username', $by)->first()['id_user'];

    $no_dokumen = $this->request->getVar('no_dokumen') == '' ? 0 : $this->request->getVar('no_dokumen');
    $judul = $this->request->getVar('judul') == '' ? 0 : $this->request->getVar('judul');
    $unit_kerja = $this->request->getVar('unit_kerja') == '' ? 0 : $this->request->getVar('unit_kerja');
    $jenis_dokumen = $this->request->getVar('jenis_dokumen') == '' ? 0 : $this->request->getVar('jenis_dokumen');
    $file = $this->request->getFile("unggah_berkas_sop");
    $newName = $this->randomString(6) . '_' . $file->getName();
    $status_pengesahan = $this->request->getVar('status') == '' ? 0 : $this->request->getVar('status');
    $tanggal_pembuatan = $this->request->getVar('tanggal_pembuatan') == '' ? 0 : $this->request->getVar('tanggal_pembuatan');
    $tanggal_revisi = $this->request->getVar('tanggal_revisi') == '' ? 0 : $this->request->getVar('tanggal_revisi');
    $revisi_ke = $this->request->getVar('revisi') == '' ? 0 : $this->request->getVar('revisi');
    $tanggal_efektif = $this->request->getVar('tanggal_efektif') == '' ? 0 : $this->request->getVar('tanggal_efektif');

    $data = [
      'dokumen_id'        => $id,
      'no_dokumen'        => $no_dokumen,
      'judul'             => $judul,
      'unit_kerja'        => $unit_kerja,
      'jenis_dokumen'     => $jenis_dokumen,
      'status_pengesahan' => $status_pengesahan,
      'tanggal_pembuatan' => $tanggal_pembuatan,
      'tanggal_revisi'    => $tanggal_revisi,
      'revisi_ke'         => $revisi_ke,
      'tanggal_efektif'   => $tanggal_efektif,
      'diubah_oleh'       => $by
    ];

    $data1 = [
      'dokumen_id'        => $id,
      'no_dokumen'        => $no_dokumen,
      'judul'             => $judul,
      'unit_kerja'        => $unit_kerja,
      'jenis_dokumen'     => $jenis_dokumen,
      'file'              => $newName,
      'status_pengesahan' => $status_pengesahan,
      'tanggal_pembuatan' => $tanggal_pembuatan,
      'tanggal_revisi'    => $tanggal_revisi,
      'revisi_ke'         => $revisi_ke,
      'tanggal_efektif'   => $tanggal_efektif,
      'diubah_oleh'       => $by
    ];

    $getJenisDok = $this->request->getVar("jenis_dokumen");

    if ($file == '') {
      $this->sipepesModel->save($data);
      $newId = $this->sipepesModel->getInsertID();
      if ($getJenisDok == "SOP Utama") {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Utama',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/utama');
      } else if ($getJenisDok == "SOP Pendukung") {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . 'menambahkan data SOP Pendukung',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/pendukung');
      } else {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Lainnya',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/lainnya');
      }
    } else {
      $data = $this->sipepesModel->where('dokumen_id', $id)->first();
      $path = './dokumen_sop/' . $data['file'];
      unlink($path);

      $file->move('./dokumen_sop/', $newName);
      $this->sipepesModel->save($data1);
      $newId = $this->sipepesModel->getInsertID();

      if ($getJenisDok == "SOP Utama") {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Utama',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/utama');
      } else if ($getJenisDok == "SOP Pendukung") {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Pendukung',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/pendukung');
      } else {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'sipepes_doksop',
          'id_user'     => $byLinimasa['id_user'],
          'aktivitas'   => $byLinimasa['nama'] . ' menambahkan data SOP Lainnya',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to('sipepes/lainnya');
      }
    }
  }

  public function hapus_dokumen_sop($id)
  {
    $by = session()->get('username');
    $byLinimasa = $this->userModel->where('username', $by)->first();
    
    $getDok = $this->sipepesModel->where('dokumen_id', $id)->first();
    $getJenisDok = $this->sipepesModel->select('jenis_dokumen')->where('dokumen_id', $id)->first()['jenis_dokumen'];
    
    $timeline = [
      'icon'        => 'fas fa-trash-alt bg-danger',
      'id_table'    => $getDok['dokumen_id'],
      'table'       => 'sipepes_doksop',
      'id_user'     => $byLinimasa['id_user'],
      'aktivitas'   => $byLinimasa['nama'] . ' menghapus data ' . $getDok['jenis_dokumen'],
      'keterangan'  => "Data yang dihapus adalah data SOP dengan judul " . $getDok['judul'] . " dengan nomor dokumen : " . $getDok['no_dokumen'],
    ];
    $this->linimasaModel->save($timeline);
    if ($this->sipepesModel->delete($id)) {
      if ($getJenisDok == "SOP Utama") {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('sipepes/utama');
      } else if ($getJenisDok == "SOP Pendukung") {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('sipepes/pendukung');
      } else {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('sipepes/lainnya');
      }
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
      return redirect()->to('sipepes/utama');
    }
  }
  public function change_status($id) {
      if($this->request->isAJAX()) {
          $msg = [];
          $getData = $this->sipepesModel->where('dokumen_id', $id)->first();
          if($getData){
              $by = $this->userModel->where('username', session()->get('username'))->first();
              $status = "";
              $color = "";
              if($getData['status'] == "Berlaku") {
                  $status = "Tidak Berlaku";
                  $color = "danger";
              } else {
                  $status = "Berlaku";
                  $color = "success";
              }
              $data = [
                'dokumen_id' => $id,
                'status' => $status,
                'diubah_oleh' => $by['id_user']
              ];
              if($this->sipepesModel->save($data)) {
                  $msg = [
                    'data' => 'sukses'
                  ];
                //   linimasa
                $timeline = [
                    'icon'        => 'fas fa-file-alt bg-' . $color,
                    'id_table'    => $id,
                    'table'       => 'sipepes_doksop',
                    'id_user'     => $by['id_user'],
                    'aktivitas'   => 'mengubah status dokumen SOP (' . $getData['judul'] . '). Tanggal efektif dokumen: ' . $getData['tanggal_efektif'] . ' menjadi <span class="badge badge-' . $color .' ">' . $status . '</span>',
                    'keterangan'  => "",
                ];
                $this->linimasaModel->save($timeline);
              }
          }
          
          echo json_encode($msg);
      }
  }
}
