<?php

namespace App\Controllers\Arkab;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;
  
  public function template_naskah_list()
  {
    if ($this->request->isAJAX()) {
      $data['getTemplateNaskah'] = $this->arsipTemplateNaskahModel
        ->orderBy('arsip_template_naskah.updated_at', 'DESC')
        ->findAll();

      $msg = [
        'cek' => "berhasil",
        'data' => view("arkab/list_template_naskah", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function arsip_surat_list()
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $getChecked = $this->request->getVar("cekAll");

      if ($by == null) {
        $data['getArsipSurat'] = $this->arkabSuratModel
          ->select('arkab_surat.*, substansi.*, data_pegawai.*, arkab_jenis_surat.*, arkab_kode_klasifikasi.*, arkab_surat.updated_at as diubah_pada')
          ->join('substansi', 'substansi.id_substansi = arkab_surat.id_substansi')
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
          ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
          ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
          ->orderBy('arkab_surat.updated_at', 'DESC')
          ->findAll();
      } else {
        if ($getChecked == 'true') {
          $data['getArsipSurat'] = $this->arkabSuratModel
            ->select('arkab_surat.*, substansi.*, data_pegawai.*, arkab_jenis_surat.*, arkab_kode_klasifikasi.*, arkab_surat.updated_at as diubah_pada')
            ->join('substansi', 'substansi.id_substansi = arkab_surat.id_substansi')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
            ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
            ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
            ->orderBy('arkab_surat.updated_at', 'DESC')
            ->findAll();
        } elseif ($by) {
          $data['cek'] = "Berhasil";
          $data['getArsipSurat'] = $this->arkabSuratModel
            ->select('arkab_surat.*, substansi.*, data_pegawai.*, arkab_jenis_surat.*, arkab_kode_klasifikasi.*, arkab_surat.updated_at as diubah_pada')
            ->join('substansi', 'substansi.id_substansi = arkab_surat.id_substansi')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
            ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
            ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
            ->where('arkab_surat.id_data_pegawai', $by)
            ->orderBy('arkab_surat.updated_at', 'DESC')
            ->findAll();
        }
      }


      $msg = [
        'cek' => $data['getArsipSurat'],
        'data' => view("arkab/list_data_surat", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function getSubstansi()
  {
    if ($this->request->isAJAX()) {
      $msg = [
        'dataPegawai' => $this->dataPegawaiModel->findAll(),
        'dataSubstansi' => $this->substansiModel->findAll()
      ];
      echo json_encode($msg);
    }
  }
}
