<?php

namespace App\Controllers\Abs;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function simpan_data_lantai()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $cek = $this->absLantaiModel->where('lantai', $this->request->getVar("nama_lantai"))->first();
      $data = [
        'lantai'     => $this->request->getVar("nama_lantai"),
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user']
      ];
      if (!$cek) {
        if ($this->absLantaiModel->save($data)) {
          $msg = [
            'sukses' => $data
          ];
        }
      } else {
        $msg = [
          'error' => "Data yang dimasukkan sudah ada!"
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function simpan_data_ruang()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $cek = $this->absRuangModel->where('ruang', $this->request->getVar("nama_ruang"))->first();
      $data = [
        'ruang'      => $this->request->getVar("nama_ruang"),
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user']
      ];

      if (!$cek) {
        if ($this->absRuangModel->save($data)) {
          $msg = [
            'sukses' => $data
          ];
        }
      } else {
        $msg = [
          'error' => "Data yang dimasukkan sudah ada!"
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function simpan_data_jenis()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $cek = $this->absJenisModel->where('jenis', $this->request->getVar("nama_jenis"))->first();
      $data = [
        'jenis'      => $this->request->getVar("nama_jenis"),
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user']
      ];

      if (!$cek) {
        if ($this->absJenisModel->save($data)) {
          $msg = [
            'sukses' => $data
          ];
        }
      } else {
        $msg = [
          'error' => "Data yang dimasukkan sudah ada!"
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function simpan_lokasi()
  {
    $by = $this->userModel
      ->select('id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $data_label = [];
    $data_barang = [];
    $labels = $this->request->getVar('label');
    if (is_array($labels) && count($labels) > 0) {
      for ($i = 0; $i < count($labels); $i++) {
        $data_label[$i] = $this->request->getVar("label")[$i];
        $data_barang += [
          $this->request->getVar("label")[$i] => $this->request->getVar("isi_label")[$i]
        ];
      }
    }

    // foreach ($data_label as $label => $isi_label) {
    //   echo "Label: " . $label . "<br>";
    //   echo "Isi Label: " . $isi_label . "<br>";
    //   echo "<br>";
    // }

    $data = [];

    if ($this->request->getVar("id_abs_bangunan") != "") {
      $data += [
        'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan"),
      ];
    } else {
      $data += [
        'kode_qr_code_internal'  => uniqid('abs_', true),
        'created_by'             => $by['id_user'],
      ];
    }

    // Jika ada file yang diupload
    if ($this->request->getFile('image')->isValid()) {
      $file = $this->request->getFile('image');

      // Pastikan file adalah gambar
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      if (in_array($file->getClientMimeType(), $allowedTypes)) {
        // Custom nama file
        $customFileName = 'abs_image_' . date('YmdHis') . '.' . $file->guessExtension();

        // Pindahkan file ke folder yang ditentukan
        $file->move('./dist/img/abs/', $customFileName);

        $saveImage = [
          'image' => $customFileName
        ];
        if ($this->absImageModel->save($saveImage))
          $data['id_abs_image'] = $this->absImageModel->getInsertID();
      } else {
        $data['id_abs_image'] = '1';
      }
    } else {
      if ($this->request->getVar("id_abs_image") != "") {
        $data['id_abs_image'] = $this->request->getVar("id_abs_image");
      } else {
        $data['id_abs_image'] = '1';
      }
    }

    $data += [
      'id_unit_kerja'          => $this->request->getVar("wilayah_kerja"),
      'id_abs_lantai'          => $this->request->getVar("lantai"),
      'id_abs_ruang'           => $this->request->getVar("ruang"),
      'id_abs_jenis'           => $this->request->getVar("jenis_barang"),
      'data_label'             => serialize($data_label),
      'kode_qr_code_pusat'     => $this->request->getVar("kode_qr_code_pusat"),
      'data_barang'            => serialize($data_barang),
      'status_tersedia'        => $this->request->getVar("status_tersedia"),
      'updated_by'             => $by['id_user']
    ];
    
    $kode_qr_pusat = $this->request->getVar("kode_qr_code_pusat");
    $id_abs_bangunan = $this->request->getVar("id_abs_bangunan");

    $is_duplicate = false;
    if (!empty($kode_qr_pusat)) {
        $query = $this->absLokasiModel->where('kode_qr_code_pusat', $kode_qr_pusat);
        if (!empty($id_abs_bangunan)) {
            $query = $query->where('id_abs_bangunan !=', $id_abs_bangunan);
        }
        $cek = $query->first();
        if ($cek) {
            $is_duplicate = true;
        }
    }

    if (!$is_duplicate) {
      if ($this->absLokasiModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil disimpan.');
        return redirect()->to('abs/data_barang');
      } else {
        session()->setFlashdata('error', 'Data gagal disimpan!');
        return redirect()->to('abs/lokasi');
      }
    } else {
      session()->setFlashdata('error', 'Data dengan QR tersebut sudah tersedia!');
      return redirect()->to('abs/lokasi');
    }
  }
  public function delete_lantai($id)
  {
    if ($this->absLantaiModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
  public function delete_ruang($id)
  {
    if ($this->absRuangModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
  public function delete_jenis($id)
  {
    if ($this->absJenisModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
  public function delete_lokasi($id)
  {
    if ($this->absLokasiModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
  public function updateLokasi()
  {
    if ($this->request->isAJAX()) {
      $getData = count($this->request->getVar("id_abs_bangunan"));

      $statusSave = true;
      $data = [];
      if ($this->request->getVar("ruang")) {
        for ($i = 0; $i < $getData; $i++) {
          $data = [
            'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan")[$i],
            'id_abs_ruang' => $this->request->getVar("ruang")[$i],
            'id_abs_lantai' => $this->request->getVar("lantai")[$i],
            'id_unit_kerja' => $this->request->getVar("unit_kerja")[$i],
          ];
          if ($this->absLokasiModel->save($data)) {
          } else {
            $statusSave = false;
          }
        }
      } elseif ($this->request->getVar("lantai")) {
        for ($i = 0; $i < $getData; $i++) {
          $data = [
            'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan")[$i],
            'id_abs_lantai' => $this->request->getVar("lantai")[$i],
            'id_unit_kerja' => $this->request->getVar("unit_kerja")[$i],
          ];
          if ($this->absLokasiModel->save($data)) {
          } else {
            $statusSave = false;
          }
        }
      } elseif ($this->request->getVar("unit_kerja")) {
        for ($i = 0; $i < $getData; $i++) {
          $data = [
            'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan")[$i],
            'id_unit_kerja' => $this->request->getVar("unit_kerja")[$i],
          ];
          if ($this->absLokasiModel->save($data)) {
          } else {
            $statusSave = false;
          }
        }
      } elseif ($this->request->getVar("jenis")) {
        for ($i = 0; $i < $getData; $i++) {
          $data = [
            'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan")[$i],
            'id_abs_jenis' => $this->request->getVar("jenis")[$i],
          ];
          if ($this->absLokasiModel->save($data)) {
          } else {
            $statusSave = false;
          }
        }
      } elseif ($this->request->getVar("status_tersedia")) {
        for ($i = 0; $i < $getData; $i++) {
          $data = [
            'id_abs_bangunan' => $this->request->getVar("id_abs_bangunan")[$i],
            'status_tersedia' => $this->request->getVar("status_tersedia")[$i],
          ];
          if ($this->absLokasiModel->save($data)) {
          } else {
            $statusSave = false;
          }
        }
      } else {
        $data = [
          'error' => 'Data tidak diketahui'
        ];
      }

      if ($statusSave == true) {
        $msg = [
          'data' => 'Berhasil'
        ];
      } else {
        $msg = [
          'error' => 'gagal'
        ];
      }
      echo json_encode($msg);
    }
  }

  public function getFile($file)
  {
    $file_path = WRITEPATH . 'uploads/' . $file;

    if (file_exists($file_path)) {
      return response()->download($file_path, null);
      exit;
    } else {
      // return redirect('abs/generate_file_qr_code');
      return redirect()->to(site_url('abs/generate_file_qr_code/' . $file));
    }
  }
  public function delete_file_abs($id)
  {
    $file = $this->absExportLabelModel->where('id_abs_export_label', $id)->first()['file'];

    if ($this->absExportLabelModel->delete($id)) {
      $file_path = WRITEPATH . 'uploads/' . $file;

      if (file_exists($file_path)) {
        unlink($file_path); // Menghapus file
        echo "File berhasil dihapus.";
      } else {
        echo "File tidak ditemukan.";
      }
    }
  }
  public function abs_gambar()
  {
    if ($this->request->isAJAX()) {
      $kode = $this->request->getVar("kode");
      $id = $this->request->getVar("id");
      $id_abs_image = $id;

      $by = $this->userModel->where('username', session()->get('username'))->first();

      if ($id == "") {
        // Jika ada file yang diupload
        if ($this->request->getFile('image')->isValid()) {
          $file = $this->request->getFile('image');

          // Pastikan file adalah gambar
          $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
          if (in_array($file->getClientMimeType(), $allowedTypes)) {
            // Custom nama file
            $customFileName = 'abs_image_' . date('YmdHis') . '.' . $file->guessExtension();

            // Pindahkan file ke folder yang ditentukan
            $file->move('./dist/img/abs/', $customFileName);

            $saveImage = [
              'image' => $customFileName,
              'created_by' => $by['id_user'],
              'updated_by' => $by['id_user'],
            ];
            if ($this->absImageModel->save($saveImage))
              $id_abs_image = $this->absImageModel->getInsertID();
          } else {
            $id_abs_image = '1';
          }
        }
      }

      $getData = $this->absLokasiModel
        ->like('kode_qr_code_pusat', $kode)
        ->findAll();

      foreach ($getData as $data) {
        $data = [
          'id_abs_bangunan' => $data['id_abs_bangunan'],
          'id_abs_image' => $id_abs_image,
          'updated_by' => $by['id_user']
        ];
        if ($this->absLokasiModel->save($data)) {
          $msg = ['data' => 'sukses'];
        }
      }
      echo json_encode($msg);
    }
  }
  public function validasiPengajuanPemeliharaan()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $id_abs_pengajuan_pemeliharaan = $this->request->getVar("id_abs_pengajuan_pemeliharaan");
    $status = $this->request->getVar("status");
    $validasi = $this->request->getVar("validasi");

    $base64_string = $validasi;

    // Extract base64 images
    preg_match_all('/data:image\/(.*?);base64,(.*?)"/', $base64_string, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
      $image_extension = $match[1]; // Get the image extension
      $image_data = base64_decode($match[2]); // Get the image data
      $filename = uniqid() . '.' . $image_extension;
      $filepath = FCPATH . 'uploads_abs/validasi/' . $filename; // Save the image in the uploads_abs/validasi folder (adjust the path as needed)

      helper('filesystem');
      write_file($filepath, $image_data);
      // Update the base64 string with the file path
      $base64_string = str_replace('data:image/' . $image_extension . ';base64,' . $match[2], '/uploads_abs/validasi/' . $filename, $base64_string);
    }

    $data = [
      'id_abs_pengajuan_pemeliharaan' => $id_abs_pengajuan_pemeliharaan,
      'status' => $status,
      'validasi' => $base64_string,
      'updated_by' => $by['id_user']
    ];
    $dataOld = $this->absPengajuanPemeliharaanModel->where('id_abs_pengajuan_pemeliharaan', $id_abs_pengajuan_pemeliharaan)->first();

    $pesan = '';
    if ($status == 'pending') {
      $pesan = "Pending";
    }
    if ($status == 'proses pemeliharaan') {
      $pesan = "Diproses untuk dilakukan pemeliharaan";
    }
    if ($status == 'rusak') {
      $pesan = "Barang Rusak Total";
    }
    if ($status == 'selesai diperbaiki') {
      $pesan = "Barang selesai diperbaiki";
    }
    if ($validasi != '<p><br></p>') {
      if ($this->absPengajuanPemeliharaanModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil diupdate.');

        $data = [
          'nama_pengirim'   => $by['id_user'],
          'nama_penerima'   => $dataOld['id_user'],
          'pesan'           =>  "Telah membalas pengajuan anda sebelumnya <div class='card'><div class='card-body'>" . $dataOld['keterangan'] . "</div><div class='card-footer'> <i>Balasan : (" . $pesan . ")</i><br/>" . $base64_string . "</div></div>",
        ];
        $this->notifikasiModel->save($data);
        return redirect()->to('abs/pengajuan');
      } else {
        session()->setFlashdata('error', 'Data gagal diupdate.');
        return redirect()->to('abs/pengajuan_pemeliharaan/' . $id_abs_pengajuan_pemeliharaan)->withInput();
      }
    } else {
      session()->setFlashdata('error', 'Tolong isi bagian validasi.');
      return redirect()->to('abs/pengajuan_pemeliharaan/' . $id_abs_pengajuan_pemeliharaan);
    }
  }
}
