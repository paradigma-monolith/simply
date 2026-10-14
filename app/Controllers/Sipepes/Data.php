<?php

namespace App\Controllers\Sipepes;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function sop_utama()
  {
    $data['title'] = "Sipepes | SOP Utama";
    $data['activeMenu'] = "utama";

    $data['totalSopUtama'] = $this->sipepesModel
      ->selectCount('jenis_dokumen', 'jenis_dokumen')
      ->where('jenis_dokumen', 'SOP Utama')
      ->first();

    $data['totalPkse'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'PKSE')
      ->where('unit_kerja', 'PKSE')
      ->first();

    $data['totalPrl'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'PRL')
      ->where('unit_kerja', 'PRL')
      ->first();

    $data['totalUklw'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'UKLW')
      ->where('unit_kerja', 'UKLW')
      ->first();
      
    $data['tim1'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'Timker 1')
      ->where('unit_kerja', 'Timker 1')
      ->first();
    $data['tim2'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'Timker 2')
      ->where('unit_kerja', 'Timker 2')
      ->first();
    $data['tim3'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'Timker 3')
      ->where('unit_kerja', 'Timker 3')
      ->first();
    $data['tim4'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'Timker 4')
      ->where('unit_kerja', 'Timker 4')
      ->first();
    $data['tim5'] = $this->sipepesModel
      ->selectCount('unit_kerja', 'Timker 5')
      ->where('unit_kerja', 'Timker 5')
      ->first();

    return view('sipepes/sop_utama', $data);
  }
  
  public function sop()
  {
    $data['title'] = "Sipepes | Daftar SOP";
    $data['activeMenu'] = "daftar_sop";
    
    $data['divisi'] = $this->sipepesModel->select('unit_kerja')->where(['unit_kerja !=' => '', 'unit_kerja !=' => 'Lainnya'])->groupBy("unit_kerja")->findAll();
    
    
    return view('sipepes/sop', $data);
  }

  public function sop_pendukung()
  {
    $data['title'] = "Sipepes | SOP Pendukung";
    $data['activeMenu'] = "pendukung";

    $data['totalSopPendukung'] = $this->sipepesModel
      ->where('jenis_dokumen', 'SOP Pendukung')
      ->findAll();

    $data['dataSopPendukung'] = $this->sipepesModel
      ->select('user.nama as nama_pegawai, sipepes_doksop.*')
      ->join('user', 'user.id_user=sipepes_doksop.diubah_oleh')
      ->where('jenis_dokumen', 'SOP Pendukung')
      ->orderBy('created_at', 'DESC')
      ->findAll();

    return view('sipepes/sop_pendukung', $data);
  }

  public function sop_lainnya()
  {
    $data['title'] = "Sipepes | SOP Lainnya";
    $data['activeMenu'] = "lainnya";

    $data['totalSopLainnya'] = $this->sipepesModel
      ->where('jenis_dokumen', 'SOP Lainnya')
      ->findAll();

    $data['dataSopLainnya'] = $this->sipepesModel
      ->select('user.nama as nama_pegawai, sipepes_doksop.*')
      ->join('user', 'user.id_user=sipepes_doksop.diubah_oleh')
      ->where('jenis_dokumen', 'SOP Lainnya')
      ->orderBy('created_at', 'DESC')
      ->findAll();

    return view('sipepes/sop_lainnya', $data);
  }

  public function tambah_dokumen_sop()
  {
    $data['title'] = "Sipepes | Tambah Dokumen";
    $data['activeMenu'] = "tambah_dokumen";
    
    $data['divisi'] = $this->timKerjaModel->findAll();

    return view('crud/dokumen_sop_tambah_data', $data);
  }

  public function ubah_dokumen_sop($id = null)
  {
    $data['title'] = "Sipepes | Ubah Dokumen";
    $data['activeMenu'] = "utama";

    $data['ubah_dokumen_sop'] = $this->sipepesModel->where('dokumen_id', $id)->first();
    $data['divisi'] = $this->timKerjaModel->findAll();

    if (isset($data['ubah_dokumen_sop'])) :
      return view('crud/dokumen_sop_ubah_data', $data);
    else :
      return redirect()->to('sipepes/sop_utama');
    endif;
  }

  public function lihat_dokumen_sop($id = null)
  {
    $data['title'] = "Sipepes | Dokumen SOP";

    $cek = $this->sipepesModel->select('jenis_dokumen')->where('dokumen_id', $id)->first()['jenis_dokumen'];
    if ($cek == "SOP Utama" || $cek == "SOP Pendukung") {
      $data['activeMenu'] = "daftar_sop";
    } elseif ($cek == "SOP Lainnya") {
      $data['activeMenu'] = "lainnya";
    }

    $data['lihat_dokumen_sop'] = $this->sipepesModel
      ->select('user.nama as nama_pegawai, sipepes_doksop.*')
      ->join('user', 'user.id_user=sipepes_doksop.diubah_oleh', 'left')
      ->where('dokumen_id', $id)->first();

    if (isset($data['lihat_dokumen_sop'])) :
      return view('sipepes/lihat_dokumen_sop', $data);
    else :
      return redirect()->to('sipepes/utama');
    endif;
  }

  public function view_data_sop($id = '')
  {
    if ($this->request->isAJAX()) {
      if ($id == '') {
        $data['dataSopUtama'] = $this->sipepesModel
          ->where('jenis_dokumen', 'SOP Utama')
          ->orWhere('jenis_dokumen', 'SOP Pendukung')
          ->orderBy('updated_at', 'DESC')
          ->findAll();
      } else {
        $data['dataSopUtama'] = $this->sipepesModel
          ->where('sipepes_doksop.unit_kerja', $id)
          ->orderBy('updated_at', 'DESC')
          ->findAll();
      }
      $msg = [
        'data' => view("sipepes/list_sop_utama", $data)
      ];
      echo json_encode($msg);
    }
  }
}
