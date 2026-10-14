<?php

namespace App\Controllers\Simandja;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function simandja()
  {

    $by = $this->userModel->where('username', session()->get("username"))->first();
    $id = $this->request->getVar("id");
    $link = $this->request->getVar("kategori");

    $data = [];

    if ($id != "") {
      $data += [
        'id_simandja' => $id
      ];
    }

    $file = $this->request->getFile('fileSimandja');

    if ($file->isValid()) {

      $cekFile = $this->simandjaModel->where("id_simandja", $id)->first();

      if ($cekFile) {
        $cekFilePath = FCPATH . '/file-simandja/' . $cekFile['file'];

        if (file_exists($cekFilePath)) {
          unlink($cekFilePath);
        }
      }

      // Periksa apakah file diunggah dan ukurannya valid
      if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['pdf', 'jpg', 'jpeg', 'png', 'xlsx', 'xls', 'doc', 'docx'])) {
        // Periksa ukuran file
        $maxFileSize = 1024 * 1024 * 10; // Misalnya, batas ukuran file adalah 10 MB

        if ($file->getSize() <= $maxFileSize) {
          // Ukuran file memenuhi syarat, lanjutkan dengan pemrosesan

          // Ubah nama file menjadi bilangan unik/acak
          $newName = uniqid() . '_' . $file->getRandomName();

          // Simpan file ke direktori '/file-simandja'
          $file->move(FCPATH . '/file-simandja', $newName);

          // Periksa apakah file telah tersimpan
          if (file_exists(FCPATH . '/file-simandja/' . $newName)) {
            $data += [
              'file' => $newName
            ];
          }
        } else {
          session()->setFlashdata('error', 'Ukuran file melebihi batas maksimum');
          return redirect()->to('simandja/' . $link);
        }
      } else {

        session()->setFlashdata('error', 'File tidak diunggah, tidak valid, atau tidak sesuai ekstensi');
        return redirect()->to('simandja/' . $link);
      }
    }

    $data += [
    //   'substansi' => $this->request->getVar("substansi"),
      'tim_kerja' => $this->request->getVar("tim_kerja"),
      'tanggal' => $this->request->getVar("tanggal"),
      'deskripsi' => $this->request->getVar("deskripsi"),
      'kategori' => $this->request->getVar("kategori"),
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];

    if ($this->simandjaModel->save($data)) {
      $newId = $this->simandjaModel->getInsertID();

      if ($this->request->getVar("id") == "") {
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => 'simandja',
          'id_user'     => $by['id_user'],
          'aktivitas'   => $by['nama'] . ' menambahkan file pada SIMANDJA dengan deskripsi ' . $this->request->getVar("deskripsi"),
          'keterangan'  => "",
        ];
      } else {
        $timeline = [
          'icon'        => 'fas fa-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'simandja',
          'id_user'     => $by['id_user'],
          'aktivitas'   => $by['nama'] . ' memperbaharui data pada SIMANDJA dengan deskripsi ' . $this->request->getVar("deskripsi"),
          'keterangan'  => "",
        ];
      }
      $this->linimasaModel->save($timeline);

      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('simandja/' . $link);
    } else {

      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('simandja/' . $link);
    }
  }
  public function hapus_simandja($id)
  {
    $by = $this->userModel->where('username', session()->get("username"))->first();

    $file = $this->simandjaModel->where("id_simandja", $id)->first();

    if ($file) {
      $filePath = FCPATH . '/file-simandja/' . $file['file'];

      if (file_exists($filePath)) {
        unlink($filePath);
      }

      if ($this->simandjaModel->delete($id)) {
        $timeline = [
          'icon'        => 'fas fa-trash bg-danger',
          'id_table'    => $file['id_simandja'],
          'table'       => 'simandja',
          'id_user'     => $by['id_user'],
          'aktivitas'   => $by['nama'] . ' menghapus data pada SIMANDJA dengan deskripsi ' . $file["deskripsi"],
          'keterangan'  => "",
        ];

        $this->linimasaModel->save($timeline);

        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        // return redirect()->to('simandja/' . $file['kategori']);
      } else {
        session()->setFlashdata('error', 'Data gagal dihapus.');
        // return redirect()->to('simandja/' . $file['kategori']);
      }
    }
  }
  
  public function tambahDataTahun()
  {
    $getKategori = $this->request->getVar("kategori");
    if ($getKategori == "simandjaRevisi") {
        $kategori = "revisi";
    } else {
        $kategori = "monevpk";
    }
    
    $data = [
      'tahun' => $this->request->getVar("tahun"),
      'link' => $this->request->getVar("link"),
      'keterangan' => $this->request->getVar("keterangan"),
      'kategori' => $kategori
    ];
    // dd($data);

    if ($this->simandjaTahunAnggaranModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      if ($getKategori == "simandjaRevisi") {
        return redirect()->to('simandja/simandjaRevisi');
      } else {
        return redirect()->to('simandja/simandjaMonevPK');
      }
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      if ($getKategori == "simandjaRevisi") {
        return redirect()->to('simandja/simandjaRevisi');
      } else {
        return redirect()->to('simandja/simandjaMonevPK');
      }
    }
  }
  public function editDataTahun()
  {
    $getKategori = $this->request->getVar("kategori");
    if ($getKategori == "simandjaRevisi") {
        $kategori = "revisi";
    } else {
        $kategori = "monevpk";
    }
    
    $data = [
      'id_simandja_tahun_anggaran' => $this->request->getVar("id_simandja_tahun_anggaran"),
      'tahun' => $this->request->getVar("tahun"),
      'link' => $this->request->getVar("link"),
      'keterangan' => $this->request->getVar("keterangan"),
      'kategori' => $kategori
    ];

    if ($this->simandjaTahunAnggaranModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diubah.');
    } else {
      session()->setFlashdata('error', 'Data gagal diubah.');
    }

    if ($getKategori == "simandjaRevisi") {
      return redirect()->to('simandja/simandjaRevisi');
    } else {
      return redirect()->to('simandja/simandjaMonevPK');
    }
  }
  
  public function setAkses() {
      $msg = [];
      $data = [
        'akses' => serialize($this->request->getVar('akses'))
      ];
      
      if($this->simandjaAksesModel->save($data)) {
          $msg = ['data'=>'sukses'];
      }
      
      echo json_encode($msg);
  }
  public function setActiveLinkSemulmen($id) {
    $cek = $this->simandjaTahunAnggaranModel->where('id_simandja_tahun_anggaran', $id)->first();
    $newStatus = '';
    
    if($cek['status'] == 'non-aktif') {
        $cariAktif = $this->simandjaTahunAnggaranModel->where(['status' => 'aktif', 'kategori' => 'revisi'])->findAll();
        foreach($cariAktif as $get) {
            $data = [
                'id_simandja_tahun_anggaran' => $get['id_simandja_tahun_anggaran'],
                'status' => 'non-aktif'
            ];
            $this->simandjaTahunAnggaranModel->save($data);
        }
        
        $data = [
            'id_simandja_tahun_anggaran' => $id,
            'status' => 'aktif'
        ];
        $this->simandjaTahunAnggaranModel->save($data);
        $newStatus = 'aktif';
    } else {
        $data = [
            'id_simandja_tahun_anggaran' => $id,
            'status' => 'non-aktif'
        ];
        $this->simandjaTahunAnggaranModel->save($data);
        $newStatus = 'non-aktif';
    }
    
    if ($this->request->isAJAX()) {
        return json_encode(['status' => 'success', 'message' => 'Status updated', 'new_status' => $newStatus]);
    }
    return redirect()->to('simandja/simandjaRevisi');
  }
  public function setActiveLinksimandjaMonevPK($id) {
    $cek = $this->simandjaTahunAnggaranModel->where('id_simandja_tahun_anggaran', $id)->first();
    $newStatus = '';
    
    if($cek['status'] == 'non-aktif') {
        $cariAktif = $this->simandjaTahunAnggaranModel->where(['status' => 'aktif', 'kategori' => 'monevpk'])->findAll();
        foreach($cariAktif as $get) {
            $data = [
                'id_simandja_tahun_anggaran' => $get['id_simandja_tahun_anggaran'],
                'status' => 'non-aktif'
            ];
            $this->simandjaTahunAnggaranModel->save($data);
        }
        
        $data = [
            'id_simandja_tahun_anggaran' => $id,
            'status' => 'aktif'
        ];
        $this->simandjaTahunAnggaranModel->save($data);
        $newStatus = 'aktif';
    } else {
        $data = [
            'id_simandja_tahun_anggaran' => $id,
            'status' => 'non-aktif'
        ];
        $this->simandjaTahunAnggaranModel->save($data);
        $newStatus = 'non-aktif';
    }
    
    if ($this->request->isAJAX()) {
        return json_encode(['status' => 'success', 'message' => 'Status updated', 'new_status' => $newStatus]);
    }
    return redirect()->to('simandja/simandjaMonevPK');
  }
  public function delete_simulmen($id) {
    if($this->simandjaTahunAnggaranModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
    
    return redirect()->to('simandja/simandjaRevisi');
  }
}
