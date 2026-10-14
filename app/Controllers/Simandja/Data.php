<?php

namespace App\Controllers\Simandja;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function simandjaCapaian()
  {
    $data['title'] = "Monitoring Kinerja dan Anggaran | Capaian Kinerja ";
    $data['activeMenu'] = "simandjaCapaian";

    $data['substansi'] = $this->substansiModel
    ->where('id_substansi >', 1)
    ->where('id_substansi !=', 6)
    ->findAll();
    
    $data['timKerja'] = $this->timKerjaModel
    ->where('id_tim_kerja !=', 12)
    ->findAll();
    
    $data['getData']  = $this->simandjaModel
      ->join('user', 'user.id_user=simandja.created_by')
      ->where("kategori", "simandjaCapaian")
      ->orderBy("tanggal", "DESC")
      ->findAll();

    return view('simandja/index', $data);
  }
  public function simandjaRencana()
  {
    $data['title'] = "Monitoring Kinerja dan Anggaran | Rencana Anggaran ";
    $data['activeMenu'] = "simandjaRencana";

    $data['substansi'] = $this->substansiModel
    ->where('id_substansi >', 1)
    ->where('id_substansi !=', 6)
    ->findAll();
    $data['getData']  = $this->simandjaModel
      ->join('user', 'user.id_user=simandja.created_by')
      ->where("kategori", "simandjaRencana")
      ->orderBy("tanggal", "DESC")
      ->findAll();

    return view('simandja/index', $data);
  }
  public function simandjaRevisi()
  {
    $data['title'] = "Monitoring Kinerja dan Anggaran | Revisi Anggaran";
    $data['activeMenu'] = "simandjaRevisi";
    
    $data['getData'] = $this->simandjaTahunAnggaranModel->where('kategori', 'revisi')->orderBy('tahun', 'asc')->findAll();
    $data['getUser'] = $this->userModel->where('status_pegawai_aktif','Aktif')->findAll();
    $data['akses'] = $this->simandjaAksesModel->orderBy('created_at', 'desc')->first();
    $data['by'] = $this->userModel->where('username', session()->get('username'))->first();
    $data['kategoriGet'] = 'simandjaRevisi';
    

    return view('simandja/revisiSemulmen', $data);
  }
  public function simandjaMonevPK()
  {
    $data['title'] = "Monitoring Kinerja dan Anggaran | Monev PK";
    $data['activeMenu'] = "simandjaMonevPK";
    
    $data['getData'] = $this->simandjaTahunAnggaranModel->where('kategori', 'monevpk')->orderBy('tahun', 'asc')->findAll();
    $data['getUser'] = $this->userModel->where('status_pegawai_aktif','Aktif')->findAll();
    $data['akses'] = $this->simandjaAksesModel->orderBy('created_at', 'desc')->first();
    $data['by'] = $this->userModel->where('username', session()->get('username'))->first();
    $data['kategoriGet'] = 'simandjaMonevPK';
    

    return view('simandja/monevPK', $data);
  }
  public function simandjaMonevPKbackup()
  {
    $data['title'] = "Monitoring Kinerja dan Anggaran | Monev PK";
    $data['activeMenu'] = "simandjaMonevPK";
    
    $data['getData'] = $this->simandjaTahunAnggaranModel->where('kategori', 'monevpk')->findAll();

    return view('simandja/monevPK', $data);
  }
  
  public function tambahDataTahun($any = null) {
      $data = null;
      if($any != null) {
        $data['kategoriGet'] = $any;
      }
      
      if($this->request->isAJAX()) {
          $msg = [
                'data' => view('simandja/tambahDataTahun', $data)   
              ];
          
          echo json_encode($msg);
      }
  }

  public function editDataTahun($id, $any = null) {
      $data = null;
      if($any != null) {
        $data['kategoriGet'] = $any;
      }
      
      $data['dataTahun'] = $this->simandjaTahunAnggaranModel->find($id);
      
      if($this->request->isAJAX()) {
          $msg = [
                'data' => view('simandja/editDataTahun', $data)   
              ];
          
          echo json_encode($msg);
      }
  }
}
