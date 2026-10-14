<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class UnitKerjaApi extends BaseController
{
  public function index()
  {
    $data = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();
    return $this->response->setJSON([
      'status' => 'success',
      'data' => $data
    ]);
  }

  public function show($id)
  {
    $data = $this->unitKerjaModel->find($id);
    if (!$data) {
      return $this->response->setStatusCode(404)->setJSON([
        'status' => 'error',
        'message' => 'Data tidak ditemukan'
      ]);
    }

    return $this->response->setJSON([
      'status' => 'success',
      'data' => $data
    ]);
  }
}
