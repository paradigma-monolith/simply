<?php

namespace App\Controllers\LhkpnSpt;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;

  public function daftar_dokumen_list($startDate = null, $endDate = null)
  {
    if ($this->request->isAJAX()) {
      $this->lhkpnSptModel
        ->select('data_pegawai.nama as nama_pegawai, lhkpn_spt.*')
        ->join('data_pegawai', 'id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user');

      if (session()->get('role') != "Admin") {
        $this->lhkpnSptModel->where('user.username', session()->get('username'));
      }

      if ($startDate && $endDate) {
        // Tambahkan waktu pada tanggal
        $startDate .= " 00:00:00";
        $endDate .= " 23:59:59";

        $this->lhkpnSptModel
          ->where('lhkpn_spt.created_at >=', $startDate)
          ->where('lhkpn_spt.created_at <=', $endDate);
      }

      $data['dokumenLhkpnSpt'] = $this->lhkpnSptModel->findAll();
      $data['selectedYear'] = null; // Default value

      $msg = [
        'data' => view("lhkpn_spt/daftar_dokumen_list", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function daftar_dokumen_list_by_year($year)
  {
    if ($this->request->isAJAX()) {
      $this->lhkpnSptModel
        ->select('data_pegawai.nama as nama_pegawai, lhkpn_spt.*')
        ->join('data_pegawai', 'id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user');

      if (session()->get('role') != "Admin") {
        $this->lhkpnSptModel->where('user.username', session()->get('username'));
      }

      $this->lhkpnSptModel->where('YEAR(tanggal_dokumen)', $year);

      $data['dokumenLhkpnSpt'] = $this->lhkpnSptModel->findAll();
      $data['selectedYear'] = $year;

      $msg = [
        'data' => view("lhkpn_spt/daftar_dokumen_list", $data)
      ];
      echo json_encode($msg);
    }
  }
}
