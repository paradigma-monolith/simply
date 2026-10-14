<?php

namespace App\Controllers\Arkab;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function tata_naskah_dinas()
  {
    $data['title'] = "Tata Naskah Dinas | Daftar Template Naskah";
    $data['activeMenu'] = "template_naskah";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('arkab/template_naskah', $data);
  }

  public function download_template_naskah($fileName)
  {
    $filePath = './arkab_arsip/template_naskah/' . $fileName;

    if (file_exists($filePath)) {
      return $this->response->download($filePath, null);
    } else {
      return "File tidak ditemukan.";
    }
  }

  public function tambah_naskah($id = null)
  {
    $data['activeMenu'] = "tambah_naskah";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
    $data['pegawai'] = $this->dataPegawaiModel->findAll();

    if ($id != null) {
      $data['title'] = "Template Naskah | Ubah Template Naskah";
      $data['getTemplateNaskah'] = $this->arsipTemplateNaskahModel
        ->where('id_arsip_template_naskah', $id)->first();
    } else {
      $data['title'] = "Template Naskah | Tambah Template Naskah";
    }

    return view('crud/arkab_tambah_template_naskah', $data);
  }
  
  public function arsip_surat()
  {
    $data['title'] = "Arkab | Arsip Surat";
    $data['activeMenu'] = "arsip_surat";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('arkab/arsip_surat', $data);
  }

  public function tambah_surat($id = null)
  {
    $data['activeMenu'] = "arsip_surat";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
    $data['pegawai'] = $this->dataPegawaiModel->findAll();

    $data['getSubstansi'] = $this->dataPegawaiModel
      ->where('id_user', $data['by']['id_user'])
      ->first();
    $data['substansi'] = $this->substansiModel->findAll();

    $data['jenisSurat'] = $this->arkabJenisSuratModel->findAll();
    $data['kodeKlasifikasi'] = $this->arkabKodeKlasifikasiModel->findAll();
    // dd($data);

    if ($id != null) {
      $data['title'] = "Arkab | Ubah Arsip Surat";
      $data['getArsipSurat'] = $this->arkabSuratModel
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
        ->join('substansi', 'substansi.id_substansi = data_pegawai.id_substansi')
        ->where('id_arkab_surat', $id)->first();
    } else {
      $data['title'] = "Arkab | Tambah Arsip Surat";
    }

    return view('crud/arkab_tambah_surat', $data);
  }
}
