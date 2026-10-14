<?php

namespace App\Controllers\Sipedit;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;

  public function view_data_arsip()
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $data['subKategori'] = $this->sipeditArsipSubKategoriModel
        ->join("sipedit_kategori", "id_sipedit_kategori")->findAll();    
      $periode = $this->request->getVar("periode");

      $getChecked = $this->request->getVar("cekAll");
      $startDate = '';
      $endDate = '';

      if ($periode == '') {
        if ($getChecked == 'true') {
          $data['dataArsipSaya'] = $this->sipeditArsipModel
            ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*, sipedit_sub_kategori.*')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai', 'left')
            ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori', 'left')
            ->join('sipedit_sub_kategori', 'sipedit_sub_kategori.id_sipedit_sub_kategori = sipedit_arsip.id_sipedit_sub_kategori', 'left')
            ->where('data_pegawai.status_bekerja', 'Aktif')
            ->orderBy('sipedit_arsip.created_at', 'desc')
            ->findAll();
          $startDate = $this->sipeditArsipModel->orderBy('arsip_tanggal', 'ASC')->first();
          $startDate != null ? $startDate = $startDate['arsip_tanggal'] : '';
          $endDate = $this->sipeditArsipModel->orderBy('arsip_tanggal', 'DESC')->first();
          $endDate != null ? $endDate = $endDate['arsip_tanggal'] : '';
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
            ->orderBy('arsip_tanggal', 'ASC')->first();
        $startDate != null ? $startDate = $startDate['arsip_tanggal'] : '';
          $endDate = $this->sipeditArsipModel
            ->where('sipedit_arsip.id_data_pegawai', $by)
            ->orderBy('arsip_tanggal', 'DESC')->first();
        $endDate != null ? $endDate = $endDate['arsip_tanggal'] : '';
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

      $msg = [
        'cek' => $data['dataArsipSaya'],
        'startDate' => $startDate,
        'endDate' => $endDate,
        'data' => view("sipedit/list_arsip_saya", $data)
      ];
      echo json_encode($msg);
    }
  }
  
  public function update_sub_kategori()
  {
    if ($this->request->isAjax()) {
      $subKategoriId = $this->request->getVar("subKategoriId");
      $arsipId = $this->request->getVar("arsipId");

      $data = [
        'id_sipedit_arsip' => $arsipId,
        'id_sipedit_sub_kategori' => $subKategoriId
      ];

      $msg = [];
      if ($this->sipeditArsipModel->save($data)) {
        $msg = [
          'status' => 'success',
          'message' => 'Sub Kategori berhasil diperbarui.'
        ];
      } else {
        $msg = [
          'status' => 'error',
          'message' => 'Gagal memperbarui sub kategori.'
        ];
      }

      echo json_encode($msg);
    }
  }
}
