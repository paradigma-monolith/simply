<?php

namespace App\Controllers\LhkpnSpt;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function daftar_dokumen($id = null)
  {
    $data['activeMenu'] = "lhkpnSpt";
    $data['title'] = "LHKPN dan SPT | Daftar Dokumen";

    return view('lhkpn_spt/daftar_dokumen', $data);
  }
  
  public function unggah_dokumen($id = null)
  {
    $data['activeMenu'] = "lhkpnSpt";
    if ($id != null) {
      $data['getLhkpnSpt'] = $this->lhkpnSptModel->where('id_lhkpn_spt', $id)->first();
    }
    $data['title'] = "LHKPN dan SPT | Unggah Dokumen";
    
    $data['pegawai'] = $this->dataPegawaiModel->findAll();
    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
    $tahunSekarang = date('Y');
    $tahunAwal = 2000;
    $data['tahun_range'] = range($tahunAwal, $tahunSekarang);

    return view('crud/lhkpn_spt_upload_dokumen', $data);
  }
}
