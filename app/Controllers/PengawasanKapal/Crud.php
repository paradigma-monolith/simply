<?php

namespace App\Controllers\PengawasanKapal;

use App\Controllers\BaseController;
use Config\Services;

class Crud extends BaseController
{
  public function insertAkses()
  {
    if ($this->request->isAjax()) {
      $msg = [];
      $akses = $this->userModel
        ->join('pengawasan_kapal_akses', 'pengawasan_kapal_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $user = $this->request->getVar("id");
        $cek = $this->pengawasanKapalAksesModel->where('id_user', $user)->first();
        $data = [];
        if ($cek) {
          $data += [
            'id_pengawasan_kapal_akses' => $cek['id_pengawasan_kapal_akses'],
          ];
        }
        $data += [
          'id_user' => $user,
          'status' => 'Akses Diterima',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->pengawasanKapalAksesModel->save($data)) {
          $msg = [
            'data' => 'sukses'
          ];
        }
      }
      echo json_encode($msg);
    }
  }
  public function removeAkses()
  {
    if ($this->request->isAjax()) {
      $msg = [];
      $akses = $this->userModel
        ->join('pengawasan_kapal_akses', 'pengawasan_kapal_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $user = $this->request->getVar("id");
        $cek = $this->pengawasanKapalAksesModel->where('id_user', $user)->first();
        $data = [];
        if ($cek) {
          $data += [
            'id_pengawasan_kapal_akses' => $cek['id_pengawasan_kapal_akses'],
          ];
        }
        $data += [
          'id_user' => $user,
          'status' => 'Akses Diblokir',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->pengawasanKapalAksesModel->save($data)) {
          $msg = [
            'data' => 'sukses'
          ];
        }
      }
      echo json_encode($msg);
    }
  }

  public function updateTargetAkses()
  {
    log_message('info', 'Method updateTargetAkses dipanggil');
    if ($this->request->isAjax()) {
      $id_data_pegawai = $this->request->getVar('id_data_pegawai');
      $by = $this->userModel->where('username', session()->get('username'))->first();

      $data = [];

      $cek = $this->pengawasanKapalTargetAksesModel
        ->where('data_pegawai.id_data_pegawai', $id_data_pegawai)
        ->join('data_pegawai', 'pengawasan_kapal_target_akses.id_data_pegawai = data_pegawai.id_data_pegawai', "right")
        ->first();

      if ($cek['id_pengawasan_kapal_target_akses'] != null) {
        $data += [
          'id_pengawasan_kapal_target_akses' => $cek['id_pengawasan_kapal_target_akses']
        ];
      } else {
        $data += [
          'created_by' => $by['id_user']
        ];
      }
      $lokasi = $this->request->getVar('lokasi');
      $data += [
        'id_data_pegawai' => $id_data_pegawai,
        'lokasi' => json_encode($lokasi) == 'null' ? null : json_encode($lokasi),
        'updated_by' => $by['id_user']
      ];

      $msg = [];
      if ($this->pengawasanKapalTargetAksesModel->save($data)) {
        $msg = [
          'data' => "sukses"
        ];
      }

      echo json_encode($msg);
    }
  }

  public function update_gross_tonnage()
  {
    if ($this->request->isAjax()) {
      $id = $this->request->getVar('id');
      $gross_tonnage = $this->request->getVar('gross_tonnage');
      
      // Validasi input
      if (empty($id) || !is_numeric($gross_tonnage) || $gross_tonnage < 0) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak valid. Gross Tonnage harus berupa angka positif.'
        ]);
      }
      
      // Cek apakah user memiliki akses
      $akses = $this->userModel
        ->join('pengawasan_kapal_akses', 'pengawasan_kapal_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
        
      if (!$akses || $akses['status'] != 'Akses Diterima') {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Anda tidak memiliki akses untuk mengubah data.'
        ]);
      }
      
      // Ambil data existing untuk mempertahankan date_updated
      $existingData = $this->pengawasanKapalModel->find($id);
      if (!$existingData) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan.'
        ]);
      }
      
      // Update data dengan mempertahankan date_updated yang asli
      $data = [
        'gross_tonnage' => $gross_tonnage,
        'date_updated' => $existingData['date_updated'] // Pertahankan date_updated asli
      ];
      
      if ($this->pengawasanKapalModel->update($id, $data)) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Gross Tonnage berhasil diperbarui.'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal memperbarui data. Silakan coba lagi.'
        ]);
      }
    }
    
    return $this->response->setJSON([
      'success' => false,
      'message' => 'Request tidak valid.'
    ]);
  }
}
