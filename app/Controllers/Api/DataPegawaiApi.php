<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class DataPegawaiApi extends BaseController
{
  public function index()
  {
    $data = $this->dataPegawaiModel->findAll();
    return $this->response->setJSON([
      'status' => 'success',
      'data' => $data
    ]);
  }

  public function show($id)
  {
    $data = $this->dataPegawaiModel->find($id);
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
