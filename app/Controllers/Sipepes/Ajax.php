<?php

namespace App\Controllers\Sipepes;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;
  public function data_chart()
  {
    if ($this->request->isAJAX()) {
      return $this->respond([
        'dokumen_sop' => $this->sipepesModel
          ->select('unit_kerja', 'status_pengesahan', 'count(status_pengesahan) as jumlah')
          ->groupBy('unit_kerja', 'status_pengesahan')
          ->findAll(),
      ]);
    }
  }
  public function countDaftarSOP() {
      if ($this->request->isAJAX()) {
        $data['sop_utama'] = $this->sipepesModel->select('COUNT(unit_kerja) as sop_utama, unit_kerja')->where(['status' => 'Berlaku', 'jenis_dokumen' => 'SOP Utama'])->groupBy('unit_kerja')->findAll();
        $data['sop_pendukung'] = $this->sipepesModel->select('COUNT(unit_kerja) as sop_pendukung, unit_kerja')->where(['status' => 'Berlaku', 'jenis_dokumen' => 'SOP Pendukung'])->groupBy('unit_kerja')->findAll();
        
        $total_sop_utama = 0;
        $total_sop_pendukung = 0;
        foreach ($data['sop_utama'] as $item) {
            $total_sop_utama += $item['sop_utama'];
        }
        foreach ($data['sop_pendukung'] as $item) {
            $total_sop_pendukung += $item['sop_pendukung'];
        }
        $data['total_sop_utama'] = $total_sop_utama;
        $data['total_sop_pendukung'] = $total_sop_pendukung;
        
        $msg = [
            'data' => view('sipepes/container-total-sop', $data)
        ];
        
        echo json_encode($msg);
      }
  }

  // public function ambil_data_cuti()
  // {
  //   if ($this->request->isAJAX()) {
  //     $data['dataCuti'] = $this->cutiDataCutiModel
  //       ->select("cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
  //       cuti_status.status,
  //       user.nama AS updated,
  //       cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time")
  //       ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.id_cuti_status')
  //       ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
  //       ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
  //       ->where('cuti_status.status', 'Pending')
  //       ->findAll();
  //     $count = $this->cutiDataCutiModel->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.id_cuti_status')->where('cuti_status.status', 'Pending')->countAllResults();

  //     $msg = [
  //       'data' => view('cuti/cuti_tabel', $data),
  //       'count' => $count
  //     ];

  //     echo json_encode($msg);
  //   } else {
  //     exit('Maaf tidak bisa diproses!');
  //   }
  // }
}
