<?php

namespace App\Controllers\KendaraanKantor;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function riwayat()
  {
    $id_kendaraan_kantor_riwayat = $this->request->getVar("id_kendaraan_kantor_riwayat");
    $tujuan = $this->request->getVar("tujuan");
    $keterangan = $this->request->getVar("keterangan");
    $penumpang = $this->request->getVar("penumpang");
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $data = [
      'id_kendaraan_kantor_riwayat' => $id_kendaraan_kantor_riwayat,
      'tujuan' => $tujuan,
      'keterangan' => $keterangan,
      'penumpang' => json_encode($penumpang),
      'updated_by' => $by['id_user'],
    ];
    if ($this->kendaraanKantorRiwayatModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/kendaraan_kantor/riwayat_peminjaman');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/kendaraan_kantor/update_riwayat/' . $id_kendaraan_kantor_riwayat);
    }
  }
  public function delete_riwayat($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->kendaraanKantorRiwayatModel->delete($id)) {
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Data gagal dihapus.');
      }
    }
  }
  public function daftar()
  {

    $by = $this->userModel->where('username', session()->get('username'))->first();
    $id = $this->request->getVar('id_kendaraan_kantor_daftar');
    $data = [
      'id_kendaraan_kantor_daftar' => $id
    ];
    $cekData = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', $id)->first();

    $file = $this->request->getFile('file');
    if ($file) {
      if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff', 'svg'])) {
        $maxFileSize = 1024 * 1024 * 5;
        if ($file->getSize() <= $maxFileSize) {
          $newName = uniqid() . '_' . $file->getRandomName();
          if ($file->move(FCPATH . '/dist/img/', $newName)) {
            $data += [
              'gambar' => $newName
            ];

            $jumlahData = $this->kendaraanKantorDaftarModel->selectCount('gambar', 'jumlah')
              ->where('gambar', $cekData['gambar'])
              ->first();

            if ($jumlahData['jumlah'] == 1) {
              if (!empty($cekData['gambar'])) {
                $imagePath = FCPATH . '/dist/img/' . $cekData['gambar'];

                if (file_exists($imagePath)) {
                  unlink($imagePath);
                }
              }
            }
          }
        }
      }
    }

    $data += [
      'nama_kendaraan' => $this->request->getVar('nama_kendaraan'),
      'plat_nomor' => $this->request->getVar('plat_nomor'),
      'updated_by' => $by['id_user'],
    ];

    if ($this->kendaraanKantorDaftarModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/kendaraan_kantor/daftar_kendaraan');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/kendaraan_kantor/update_daftar/' . $id);
    }
  }
  public function daftar_tambah()
  {

    $by = $this->userModel->where('username', session()->get('username'))->first();
    $data = [];

    $file = $this->request->getFile('file');
    if ($file) {
      if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff', 'svg'])) {
        $maxFileSize = 1024 * 1024 * 5;
        if ($file->getSize() <= $maxFileSize) {
          $newName = uniqid() . '_' . $file->getRandomName();
          if ($file->move(FCPATH . '/dist/img/', $newName)) {
            $data += [
              'gambar' => $newName
            ];
          } else {
            session()->setFlashdata('error', 'Gagal menyimpan gambar mobil.');
            return redirect()->to('/kendaraan_kantor/tambah_daftar');
          }
        }
      }
    }

    $data += [
      'nama_kendaraan' => $this->request->getVar('nama_kendaraan'),
      'plat_nomor' => $this->request->getVar('plat_nomor'),
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];

    if ($this->kendaraanKantorDaftarModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('/kendaraan_kantor/daftar_kendaraan');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('/kendaraan_kantor/tambah_daftar');
    }
  }
  public function delete_daftar($id)
  {
    // Cari data terkait berdasarkan ID
    $cekData = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', $id)->first();

    // Hitung jumlah data yang memiliki gambar yang sama
    $jumlahData = $this->kendaraanKantorDaftarModel->selectCount('gambar', 'jumlah')
      ->where('gambar', $cekData['gambar'])
      ->first();

    if ($this->request->isAJAX()) {
      // Hapus data dari model
      if ($this->kendaraanKantorDaftarModel->delete($id)) {

        // Jika hanya satu data yang menggunakan gambar tersebut, hapus gambar
        if ($jumlahData['jumlah'] == 1) {
          if (!empty($cekData['gambar'])) {
            $imagePath = FCPATH . '/dist/img/' . $cekData['gambar'];

            // Hapus file gambar jika ada
            if (file_exists($imagePath)) {
              unlink($imagePath);
            }
          }
        }

        // Set flashdata untuk pesan berhasil
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        // Set flashdata untuk pesan gagal
        session()->setFlashdata('error', 'Data gagal dihapus.');
      }
    }
  }
}
