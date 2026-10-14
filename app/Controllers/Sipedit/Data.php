<?php

namespace App\Controllers\Sipedit;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function unggah_pelatihan($id = null)
  {
    $data['activeMenu'] = "sipeditArsipSaya";
    $data['kategori'] = $this->sipeditArsipKategoriModel->findAll();
    $data['pegawai'] = $this->dataPegawaiModel->findAll();
    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    if ($id != null) {
      $data['title'] = "Sipedit | Ubah Pelatihan";
      $data['getArsip'] = $this->sipeditArsipModel
        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai')
        ->where('id_sipedit_arsip', $id)->first();
    } else {
      $data['title'] = "Sipedit | Unggah Pelatihan";
    }

    return view('crud/sipedit_unggah_pelatihan', $data);
  }

  public function arsip_saya()
  {
    $data['title'] = "Sipedit | Arsip Saya (Arsip Pelatihan)";
    $data['activeMenu'] = "sipeditArsipSaya";
    
    $data['jpl_per_tahun'] = $this->sipeditArsipModel->select("SUM(sipedit_arsip.arsip_jpl) as arsip_jpl")
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->where('YEAR(sipedit_arsip.arsip_tanggal)', date("Y"))
      ->first();
      

    return view('sipedit/arsip_saya', $data);
  }
  
  public function view_data_arsipc()
  {
      $by = $this->dataPegawaiModel
        ->select('id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $periode = "2020-01-01 - 2025-01-01";

      $getChecked = "true";
      $startDate = '';
      $endDate = '';

      if ($periode == '') {
        if ($getChecked == 'true') {
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
          $startDate = $this->sipeditArsipModel
            ->orderBy('arsip_tanggal', 'ASC')->first()['arsip_tanggal'];
          $endDate = $this->sipeditArsipModel
            ->orderBy('arsip_tanggal', 'DESC')->first()['arsip_tanggal'];
        } elseif ($by) {
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
          $startDate = $this->sipeditArsipModel
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('arsip_tanggal', 'ASC')->first()['arsip_tanggal'];
          $endDate = $this->sipeditArsipModel
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('arsip_tanggal', 'DESC')->first()['arsip_tanggal'];
        } else {
          $data['dataArsipSaya'] = array();
        }
      } else {
        $startDate = substr($periode, 0, 10);
        $endDate = substr($periode, 13, 10);

        if ($getChecked == 'true') {
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('arsip_tanggal >=', $startDate)
            ->where('arsip_tanggal <=', $endDate)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
        } elseif ($by) {
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->where('arsip_tanggal >=', $startDate)
            ->where('arsip_tanggal <=', $endDate)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
        } else {
          $data['dataArsipSaya'] = array();
        }
      }
      return view("sipedit/list_arsip_saya", $data);
  }
  public function view_data_arsip()
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
        
      $periode = $this->request->getVar("periode");

      $getChecked = $this->request->getVar("cekAll");
      $startDate = '';
      $endDate = '';

      if ($periode == '') {
        if ($getChecked == 'true') {
        //   $cek = 'Periodenya kosong, tapi checklisnya ada';
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
          $startDate = $this->sipeditArsipModel
            ->orderBy('arsip_tanggal', 'ASC')->first()['arsip_tanggal'];
          $endDate = $this->sipeditArsipModel
            ->orderBy('arsip_tanggal', 'DESC')->first()['arsip_tanggal'];
        } elseif ($by) {
        //   $cek = 'Periodenya kosong, tapi checklisnya engga ada, tapi data pegawai ada';
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
          $startDate = $this->sipeditArsipModel
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('arsip_tanggal', 'ASC')->first()['arsip_tanggal'];
          $endDate = $this->sipeditArsipModel
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('arsip_tanggal', 'DESC')->first()['arsip_tanggal'];
        } else {
        //   $cek = 'Periodenya kosong, tapi checklisnya engga ada, tapi data pegawai engga ada';
          $data['dataArsipSaya'] = array();
        }
      } else {
        $startDate = substr($periode, 0, 10);
        $endDate = substr($periode, 13, 10);

        if ($getChecked == 'true') {
        //   $cek = 'Periodenya ada, checklisnya ada';
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('arsip_tanggal >=', $startDate)
            ->where('arsip_tanggal <=', $endDate)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
        } elseif ($by) {
        //   $cek = 'Periodenya kosong, tapi checklisnya engga ada, tapi data pegawai ada';
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->where('arsip_tanggal >=', $startDate)
            ->where('arsip_tanggal <=', $endDate)
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
        } else {
        //   $cek = 'Periodenya kosong, tapi checklisnya engga ada, tapi data pegawai engga ada';
          $data['dataArsipSaya'] = array();
        }
      }

      $msg = [
        // 'cek' => $cek,
        'cek' => $data['dataArsipSaya'],
        'startDate' => $startDate,
        'endDate' => $endDate,
        'data' => view("sipedit/list_arsip_saya", $data)
      ];
      echo json_encode($msg);
    }
  }
}
