<?php

namespace App\Controllers\Prl;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;

  public function getTpp()
  {
    if ($this->request->isAJAX()) {
      $selectedTpp = $this->request->getVar('selectedTpp');

      // Mengambil data penanggung jawab berdasarkan nama_tpp
      $tpp = $this->prlTppModel->where('id_prl_tpp', $selectedTpp)->first();
      $msg = [
        'dataTpp' => $this->prlTppModel->findAll(),
        'penanggungJawab' => $tpp['penanggung_jawab'] ?? null,
        'jumlahKaryawan' => $tpp['jml_karyawan'] ?? null,
        'kapasitasProduksi' => $tpp['kapasitas_produksi'] ?? null,
      ];
      echo json_encode($msg);
    }
  }

  public function getSab()
  {
    if ($this->request->isAJAX()) {
      $selectedSab = $this->request->getVar('selectedSab');

      // Mengambil data penanggung jawab berdasarkan nama_sab
      $sab = $this->prlSabModel->where('id_prl_sab', $selectedSab)->first();
      $msg = [
        'dataSab' => $this->prlSabModel->findAll(),
        'penanggungJawab' => $sab['penanggung_jawab'] ?? null,
        'penanggungJawabNohp' => $sab['penanggung_jawab_no_hp'] ?? null,
        'sumberAir' => $sab['sumber_air'] ?? null,
        'kapasitasPenampungan' => $sab['kapasitas_penampungan'] ?? null,
      ];
      echo json_encode($msg);
    }
  }

  public function getHsbu()
  {
    if ($this->request->isAJAX()) {
      $selectedHsbu = $this->request->getVar('selectedHsbu');

      // Mengambil data penanggung jawab berdasarkan nama_hsbu
      $sab = $this->prlHsbuModel->where('id_prl_hsbu', $selectedHsbu)->first();
      $msg = [
        'dataHsbu' => $this->prlHsbuModel->findAll(),
        'penanggungJawab' => $sab['penanggung_jawab'] ?? null,
        'jumlahKaryawan' => $sab['jml_karyawan'] ?? null,
      ];
      echo json_encode($msg);
    }
  }
}
