<?php

namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;

  public function getUnitKerja()
  {
    if ($this->request->isAJAX()) {
      $unit_kerja = $this->request->getVar('unit_kerja');

      $data['pegawai'] = $this->dataPegawaiModel->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')->where('unit_kerja', $unit_kerja)->findAll();

      $msg = [
        'sukses' => $data

      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses'); //redirect aja ganti
    }
  }
  public function getPegawai()
  {
    if ($this->request->isAJAX()) {
      $nip = $this->request->getVar('nip');

      $row = $this->dataPegawaiModel
        ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
        ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
        ->where('nip', $nip)->first();

      $data = [
        'jabatan'     => $row['jabatan'],
        'nip'         => $row['nip'],
        'nama'        => $row['nama'],
        'golongan'    => $row['golongan'],
        'masa_kerja'  => $row['masa_kerja'],
        'unit_kerja'  => $row['unit_kerja'],
      ];

      $msg = [
        'sukses' => $data
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses'); //redirect aja ganti
    }
  }
}
