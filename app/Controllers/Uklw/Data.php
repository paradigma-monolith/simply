<?php

namespace App\Controllers\Uklw;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function __construct()
  {
    $this->startMicrotime = microtime(true);

    $substansi = session()->get('substansi');

    if (session()->get('substansi')) {
      if ($substansi == 'UKLW' || session()->get('role') == 'Admin') {
      } else {
        session()->setFlashdata('error', 'Anda tidak mempunyai akses ke halaman tersebut!.');
        header("Location: /home");
        die;
      }
    } else {
      session()->setFlashdata('error', 'Tolong lakukan login ulang pada sistem!.');
      header("Location: /home");
      die;
    }
  }
  public function laporan()
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Laporan";
    $data['activeMenu'] = 'uklwReport';

    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/laporan', $data);
  }

  public function pasien()
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Daftar Pasien';
    $data['activeMenu'] = 'uklwPasien';

    $data['getData'] = $this->uklwPasienModel
      ->select('uklw_pasien.*')
      ->where('uklw_pasien.identitas !=', 'Tanpa Identitas')
      ->orderBy('updated_at', 'desc')->findAll();
    $uklwPemkes = $this->uklwPemkesModel
      ->select('id_uklw_pasien, count(id_uklw_pemkes) as uklw_pemkes')
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $uklwSkriningHIV = $this->uklwSkriningHIVModel
      ->select('id_uklw_pasien, count(id_uklw_skrining_hiv) as uklw_pemkes')
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $uklwFaktorRisikoPTM = $this->uklwFaktorRisikoPTMModel
      ->select('id_uklw_pasien, count(id_uklw_faktor_risiko_ptm) as uklw_pemkes')
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $uklwSkriningTBC = $this->uklwSkriningTBCModel
      ->select('id_uklw_pasien, count(id_uklw_skrining_tbc) as uklw_pemkes')
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $uklwLaboratorium = $this->uklwLaboratoriumModel
      ->select('id_uklw_pasien, count(id_uklw_laboratorium) as uklw_pemkes')
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $pemkesFisik = $this->uklwPemkesModel
      ->select('id_uklw_pasien, id_uklw_pemkes as uklw_pemkes, MAX(created_at) as latest_created_at')
      ->where('DATE(created_at)', date('Y-m-d'))
      ->groupBy('id_uklw_pasien')
      ->findAll();
    $catatanPemeriksaan = $this->uklwCatatanPemeriksaanModel
      ->select('id_uklw_pasien, id_uklw_pemkes, id_uklw_catatan_pemeriksaan, id_uklw_catatan_pemeriksaan as uklw_pemkes, MAX(uklw_catatan_pemeriksaan.created_at) as latest_created_at')
      ->where('DATE(uklw_catatan_pemeriksaan.created_at)', date('Y-m-d'))
      ->join('uklw_pemkes', 'id_uklw_pemkes')
      ->groupBy('uklw_catatan_pemeriksaan.id_uklw_pemkes')
      ->findAll();
    $uklwCatatanPemeriksaan = $this->uklwCatatanPemeriksaanModel
      ->select('uklw_pemkes.id_uklw_pasien, count(id_uklw_catatan_pemeriksaan) as uklw_pemkes')
      ->join('uklw_pemkes', 'id_uklw_pemkes')
      ->groupBy('uklw_pemkes.id_uklw_pasien')
      ->findAll();

    $uklwPemkesMap = [];
    foreach ($uklwPemkes as $pemkes) {
      $uklwPemkesMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $uklwSkriningHIVMap = [];
    foreach ($uklwSkriningHIV as $pemkes) {
      $uklwSkriningHIVMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $uklwFaktorRisikoPTMMap = [];
    foreach ($uklwFaktorRisikoPTM as $pemkes) {
      $uklwFaktorRisikoPTMMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $uklwSkriningTBCMap = [];
    foreach ($uklwSkriningTBC as $pemkes) {
      $uklwSkriningTBCMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $uklwLaboratoriumMap = [];
    foreach ($uklwLaboratorium as $pemkes) {
      $uklwLaboratoriumMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $uklwCatatanPemeriksaanMap = [];
    foreach ($uklwCatatanPemeriksaan as $pemkes) {
      $uklwCatatanPemeriksaanMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $pemkesMap = [];
    foreach ($pemkesFisik as $pemkes) {
      $pemkesMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    $catatanPemeriksaanMap = [];
    foreach ($catatanPemeriksaan as $pemkes) {
      $catatanPemeriksaanMap[$pemkes['id_uklw_pasien']] = $pemkes['uklw_pemkes'];
    }
    foreach ($data['getData'] as &$pasien) {
      $pasien['uklw_pemkes_count'] = $uklwPemkesMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['uklw_skrining_hiv_count'] = $uklwSkriningHIVMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['uklw_skrining_ptm_count'] = $uklwFaktorRisikoPTMMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['uklw_skrining_tbc_count'] = $uklwSkriningTBCMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['uklw_laboratorium_count'] = $uklwLaboratoriumMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['uklw_catatan_pemeriksaan_count'] = $uklwCatatanPemeriksaanMap[$pasien['id_uklw_pasien']] ?? 0;
      $pasien['id_uklw_pemkes'] = $pemkesMap[$pasien['id_uklw_pasien']] ?? 0;
    }
    foreach ($data['getData'] as &$pasien) {
      $isDataAda = true;
      foreach ($catatanPemeriksaan as $cct) {
        if ($cct['id_uklw_pemkes'] == $pasien['id_uklw_pemkes']) {
          $pasien['id_uklw_catatan_pemeriksaan'] = $pasien['id_uklw_pemkes'] != 0 ? $cct['id_uklw_catatan_pemeriksaan'] : 0;
          $isDataAda = false;
          break;
        }
      }
      if ($isDataAda) {
        $pasien['id_uklw_catatan_pemeriksaan'] = 0;
      }
    }

    // Ambil semua data pemeriksaan tanpa filter tanggal agar tombol tetap berwarna kuning
    $data['checkTanggalPemkes'] = $this->uklwPemkesModel->select('id_uklw_pasien')->findAll();
    $data['checkTanggalPemkes'] = array_column($data['checkTanggalPemkes'], 'id_uklw_pasien');
    $data['checkTanggalSkriningHIV'] = $this->uklwSkriningHIVModel->select('id_uklw_pasien')->findAll();
    $data['checkTanggalSkriningHIV'] = array_column($data['checkTanggalSkriningHIV'], 'id_uklw_pasien');
    $data['checkTanggalSkriningTBC'] = $this->uklwSkriningTBCModel->select('id_uklw_pasien')->findAll();
    $data['checkTanggalSkriningTBC'] = array_column($data['checkTanggalSkriningTBC'], 'id_uklw_pasien');
    $data['checkFaktorRisikoPTM'] = $this->uklwFaktorRisikoPTMModel->select('id_uklw_pasien')->findAll();
    $data['checkFaktorRisikoPTM'] = array_column($data['checkFaktorRisikoPTM'], 'id_uklw_pasien');
    $data['checkLaboratorium'] = $this->uklwLaboratoriumModel->select('id_uklw_pasien')->findAll();
    $data['checkLaboratorium'] = array_column($data['checkLaboratorium'], 'id_uklw_pasien');

    return $this->renderView('uklw/pasien', $data);
  }

  public function tambahPasien()
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Pasien';
    $data['activeMenu'] = 'uklwPasien';

    return $this->renderView('crud/uklwTambahPasien', $data);
  }
  public function editPasien($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Perbaharui Data Pasien';
    $data['activeMenu'] = 'uklwPasien';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    return $this->renderView('crud/uklwEditPasien', $data);
  }
  public function directToFormPasien()
  {
    $this->request->getVar("nama");
    $this->request->getVar("tgl_lahir");
    $this->request->getVar("jk");
    $this->request->getVar("no_hp");


    session()->setFlashdata('info', 'beberapa data terisi, silahkan lengkapi bagian yang kosong!');
    return redirect()->to('/uklw/tambahPasien')->withInput();
  }
  public function pemkes($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Pemeriksaan Kesehatan Pasien';
    $data['activeMenu'] = 'uklwPemkes';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();
    $data['getRiwayatPenyakit'] = $this->uklwRiwayatPenyakitModel->findAll();

    return $this->renderView('crud/uklwTambahPemkesPasien', $data);
  }
  public function skriningTBC($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Skrining TBC';
    $data['activeMenu'] = 'uklwSkriningTBC';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    return $this->renderView('crud/uklwTambahSkriningTBCPasien', $data);
  }
  public function skriningHIV($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Skrining HIV';
    $data['activeMenu'] = 'uklwSkriningHIV';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    return $this->renderView('crud/uklwTambahSkriningHIVPasien', $data);
  }
  public function faktorRisikoPTM($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Faktor Risiko PTM';
    $data['activeMenu'] = 'uklwSkriningHIV';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    return $this->renderView('crud/uklwTambahFaktorRisikoPTMPasien', $data);
  }
  public function editPemkes($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Edit Data Pemeriksaan Fisik dan Riwayat Penyakit';
    $data['activeMenu'] = 'uklwPemkes';

    $data['getData'] = $this->uklwPasienModel
      ->join("uklw_pemkes", "id_uklw_pasien")
      ->where('id_uklw_pemkes', $id)->first();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    $data['getRiwayatPenyakit'] = $this->uklwRiwayatPenyakitModel->findAll();

    // d($data['getData']['riwayat_penyakit']);
    // dd(unserialize($data['getData']['riwayat_penyakit']));

    return $this->renderView('crud/uklwEditPemkesPasien', $data);
  }
  public function editSkriningTBC($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Edit Data Pemeriksaan Skrining TBC';
    $data['activeMenu'] = 'uklwSkriningTBC';

    $data['getData'] = $this->uklwPasienModel
      ->join("uklw_skrining_tbc", "id_uklw_pasien")
      ->where('id_uklw_skrining_tbc', $id)->first();

    return $this->renderView('crud/uklwEditSkriningTBCPasien', $data);
  }
  public function editSkriningHIV($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Edit Data Pemeriksaan Skrining HIV';
    $data['activeMenu'] = 'uklwSkriningHIV';

    $data['getData'] = $this->uklwPasienModel
      ->join("uklw_skrining_hiv", "id_uklw_pasien")
      ->where('id_uklw_skrining_hiv', $id)->first();

    return $this->renderView('crud/uklwEditSkriningHIVPasien', $data);
  }
  public function getPemkes()
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Data Pemeriksaan Fisik dan Riwayat Penyakit';
    $data['activeMenu'] = 'uklwPemkes';

    // $data['getData'] = $this->uklwPasienModel
    //   ->select("*, 
    //     uklw_pasien.nama, 
    //     DATE_FORMAT(uklw_pemkes.created_at, '%Y-%m-%d') as created_pemkes,
    //     DATE_FORMAT(uklw_catatan_pemeriksaan.created_at, '%Y-%m-%d') as created_catatan,
    //     created.nama AS created, 
    //     updated.nama as updated
    //   ")
    //   ->join('uklw_pemkes', 'id_uklw_pasien')
    //   ->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes', 'LEFT')
    //   ->join('user AS created', 'created.id_user=uklw_pemkes.created_by')
    //   ->join('user AS updated', 'updated.id_user=uklw_pemkes.created_by')
    //   ->orderBy('uklw_pemkes.created_at', 'desc')
    //   ->findAll();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/pemkes', $data);
  }
  public function getSkriningTBC()
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Data Pemeriksaan Skrining TBC';
    $data['activeMenu'] = 'uklwSkriningTBC';

    // $data['getData'] = $this->uklwPasienModel
    //   ->select("*, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
    //   ->join('uklw_skrining_tbc', 'id_uklw_pasien')
    //   ->join('user AS created', 'created.id_user=uklw_skrining_tbc.created_by')
    //   ->join('user AS updated', 'updated.id_user=uklw_skrining_tbc.created_by')
    //   ->orderBy('uklw_skrining_tbc.created_at')
    //   ->findAll();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/skriningTBC', $data);
  }
  public function getSkriningHIV()
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Data Pemeriksaan Skrining HIV';
    $data['activeMenu'] = 'uklwSkriningHIV';

    // $data['getData'] = $this->uklwPasienModel
    //   ->select("*, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
    //   ->join('uklw_skrining_hiv', 'id_uklw_pasien')
    //   ->join('user AS created', 'created.id_user=uklw_skrining_hiv.created_by')
    //   ->join('user AS updated', 'updated.id_user=uklw_skrining_hiv.created_by')
    //   ->orderBy('uklw_skrining_hiv.created_at')
    //   ->findAll();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/skriningHIV', $data);
  }
  public function catatanPemeriksaan($id)
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Tambah Data Pemeriksaan Kesehatan Pasien';
    $data['activeMenu'] = 'uklwPemkes';

    $data['getData'] = $this->uklwPasienModel
      ->select("*, uklw_pasien.nama")
      ->join('uklw_pemkes', 'id_uklw_pasien')
      ->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes', 'LEFT')
      ->where('id_uklw_pemkes', $id)
      ->first();
      
    $id_pasien = $data['getData']['id_uklw_pasien'];
    
    $date = date("Y-m-d");
    $data['getTBC'] = $this->uklwSkriningTBCModel->where(['id_uklw_pasien' => $id_pasien, 'DATE(created_at)' => $date])->first();
    $data['getHIV'] = $this->uklwSkriningHIVModel->where(['id_uklw_pasien' => $id_pasien, 'DATE(created_at)' => $date])->first();
    $data['getPTM'] = $this->uklwFaktorRisikoPTMModel->where(['id_uklw_pasien' => $id_pasien, 'DATE(created_at)' => $date])->first();
    $data['getLaboratorium'] = $this->uklwLaboratoriumModel->where(['id_uklw_pasien' => $id_pasien, 'DATE(created_at)' => $date])->first();
    
    // dd($data['getPTM']);

    $array = unserialize($data['getData']['assesment']);

    $getDataICDS = $this->uklwICDSModel;
    $dataICDS = array();
    if ($array) {
      $dataICDS = explode(",", $array['assesment_diagnosa_medis']);
    }

    for ($i = 0; $i < count($dataICDS); $i++) :
      $getDataICDS->orWhere('code', $dataICDS[$i]);
    endfor;
    $result = array();
    if ($array)
      $result = $getDataICDS->findAll();

    $data['dataICDS'] = $result;
    $data['obat'] = $this->uklwObatModel->findAll();

    $data['dokter'] = $this->dataPegawaiModel->join('user', 'id_user')->join('golongan', 'id_golongan')->orderBy('golongan.urutan', 'desc')->where('status_bekerja', 'aktif')->findAll();

    return $this->renderView('crud/uklw_catatan_pemeriksaan', $data);
  }
  public function dataObat() 
  {
    $data['title'] = 'Pelayanan Terpadu Upaya Kesehatan | Pengelolaan Data Obat';
    $data['activeMenu'] = 'uklwObat';
    
    $data['obat'] = $this->uklwObatModel->findAll();
    
    
    return $this->renderView('uklw/uklw_data_obat', $data);
  }
  public function detailPasien($id)
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Data Pasien";
    $data['activeMenu'] = 'uklwPasien';

    $data['pasien'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    $pemkes = $this->uklwPemkesModel
      ->select('uklw_pemkes.created_at')
      ->join('uklw_pasien', 'id_uklw_pasien')
      ->where('id_uklw_pasien', $id)
      ->findAll();

    $i = 0;
    $data['record'][$i++] = [
      'icon' => "fa-user-plus",
      'kategori' => 'Daftar Pasien',
      'created_at' => $data['pasien']['created_at'],
      'ket' => "Menambahkan data pasien untuk pertama kalinya kesistem."
    ];

    foreach ($pemkes as $urutan => $key) {
      $data['record'][$i++] = [
        'icon' => "fa-hand-holding-medical",
        'kategori' => 'Pemeriksaan Pasien',
        'created_at' => $key['created_at'],
        'ket' => "Menambahkan data pemeriksaan ke-" . ($urutan + 1) . " untuk pemeriksaan fisik & riwayat penyakit ke sistem."
      ];
    }

    $skriningTBC = $this->uklwSkriningTBCModel
      ->select('uklw_skrining_tbc.created_at')
      ->join('uklw_pasien', 'id_uklw_pasien')
      ->where('id_uklw_pasien', $id)
      ->findAll();

    foreach ($skriningTBC as $urutan => $key) {
      $data['record'][$i++] = [
        'icon' => "fa-lungs-virus",
        'kategori' => 'Skrining TBC terhadap Pasien',
        'created_at' => $key['created_at'],
        'ket' => "Menambahkan data skrining TBC ke-" . ($urutan + 1) . " ke sistem."
      ];
    }

    $skriningHIV = $this->uklwSkriningHIVModel
      ->select('uklw_skrining_hiv.created_at')
      ->join('uklw_pasien', 'id_uklw_pasien')
      ->where('id_uklw_pasien', $id)
      ->findAll();

    foreach ($skriningHIV as $urutan => $key) {
      $data['record'][$i++] = [
        'icon' => "fa-first-aid",
        'kategori' => 'Skrining HIV & Penyakit Menular terhadap pasien',
        'created_at' => $key['created_at'],
        'ket' => "Menambahkan data skrining HIV & Penyakit Menular ke-" . ($urutan + 1) . " ke sistem."
      ];
    }

    $catatanPemeriksaan = $this->uklwCatatanPemeriksaanModel
      ->select('uklw_catatan_pemeriksaan.created_at')
      ->join('uklw_pemkes', 'id_uklw_pemkes')
      ->join('uklw_pasien', 'id_uklw_pasien')
      ->where('id_uklw_pasien', $id)
      ->findAll();

    foreach ($catatanPemeriksaan as $urutan => $key) {
      $data['record'][$i++] = [
        'icon' => "fa-notes-medical",
        'kategori' => 'Catatan Pemeriksaan',
        'created_at' => $key['created_at'],
        'ket' => "Menambahkan data catatan pemeriksaan ke-" . ($urutan + 1) . " ke sistem."
      ];
    }


    usort($data['record'], function ($a, $b) {
      return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    $data['penjamah_makanan'] = $this->uklwPemkesModel
      ->where('status_pemeriksaan', 'Penjamah Makanan')
      ->where('id_uklw_pasien', $id)
      ->orderBy('created_by', 'desc')->findAll();

    $cekPemkes = $this->uklwPemkesModel
      ->where('id_uklw_pasien', $id)
      ->first();
    $cekSkringTBC = $this->uklwSkriningTBCModel
      ->where('id_uklw_pasien', $id)
      ->first();
    $cekSkringHIV = $this->uklwSkriningHIVModel
      ->where('id_uklw_pasien', $id)
      ->first();

    if ($cekPemkes) {
      if ($cekSkringTBC && $cekSkringHIV) {
        $data['pemkes'] = "Data Skrining Terbaru";
      } elseif ($cekSkringTBC) {
        $data['pemkes'] = "Belum Melakukan Skrining HIV";
      } elseif ($cekSkringHIV) {
        $data['pemkes'] = "Belum Melakukan Skrining TBC";
      } else {
        $data['pemkes'] = "Belum Melakukan Skrining TBC dan HIV";
      }
    } else {
      if ($cekSkringTBC && $cekSkringHIV) {
        $data['pemkes'] = "Belum Melakukan Pemeriksaan Fisik";
      } elseif ($cekSkringTBC) {
        $data['pemkes'] = "Belum Melakukan Pemeriksaan Fisik dan HIV";
      } elseif ($cekSkringHIV) {
        $data['pemkes'] = "Belum Melakukan Pemeriksaan Fisik dan TBC";
      } else {
        $data['pemkes'] = "Belum Melakukan Pemeriksaan Kesehatan";
      }
    }
    $data['id'] = $id;

    $data['pemkes'] = $this->uklwPemkesModel->where('id_uklw_pasien', $id)->orderBy('created_at', 'DESC')->findAll();
    $data['skriningTBC'] = $this->uklwSkriningTBCModel->where('id_uklw_pasien', $id)->orderBy('created_at', 'DESC')->findAll();
    $data['skriningHIV'] = $this->uklwSkriningHIVModel->where('id_uklw_pasien', $id)->orderBy('created_at', 'DESC')->findAll();

    // dd($data['record']);

    return $this->renderView('uklw/detail_pasien', $data);
  }

  public function laboratorium($id)
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Tambah Data Laboratorium";
    $data['activeMenu'] = 'uklwLaboratorium';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    return $this->renderView('crud/uklwTambahLaboratorium', $data);
  }
  public function editLaboratorium($id)
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Edit Data Laboratorium";
    $data['activeMenu'] = 'uklwLaboratorium';

    $data['getData'] = $this->uklwPasienModel
      ->join("uklw_laboratorium", "id_uklw_pasien")
      ->where('id_uklw_laboratorium', $id)->first();

    return $this->renderView('crud/uklwEditLaboratoriumPasien', $data);
  }

  public function getLaboratorium()
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Laboratorium";
    $data['activeMenu'] = 'uklwLaboratorium';

    // $data['getData'] = $this->uklwPasienModel
    //   ->select("*, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
    //   ->join('uklw_laboratorium', 'id_uklw_pasien')
    //   ->join('user AS created', 'created.id_user=uklw_laboratorium.created_by')
    //   ->join('user AS updated', 'updated.id_user=uklw_laboratorium.created_by')
    //   ->orderBy('uklw_laboratorium.created_at')
    //   ->findAll();
    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/laboratorium', $data);
  }
  public function penjamahMakanan()
  {
    $data['title'] = "Pemeriksaan Kesetahan | Penjamah Makanan";
    $data['activeMenu'] = 'uklwPenjamahMakanan';

    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();

    return $this->renderView('uklw/penjamah_makanan', $data);
  }
  public function tambahPenjamahMaknan()
  {
    $data['title'] = "Tambah Pemeriksaan Kesetahan | Penjamah Makanan";
    $data['activeMenu'] = 'uklwPenjamahMakanan';

    $data['kota'] = $this->kotaKabupatenModel->groupBy('kota_kabupaten')->findAll();
    $data['getRiwayatPenyakit'] = $this->uklwRiwayatPenyakitModel->findAll();

    return $this->renderView('crud/uklwTambahPenjamahMakanan', $data);
  }
  public function farmasi()
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Farmasi";
    $data['activeMenu'] = 'uklwFarmasi';

    $data['getData'] = $this->uklwPengambilanObatModel
      ->join('uklw_pasien', 'id_uklw_pasien')
      ->join('uklw_catatan_pemeriksaan', 'id_uklw_catatan_pemeriksaan', 'left')
      ->orderBy('uklw_pengambilan_obat.created_at', 'desc')
      ->findAll();

    return $this->renderView('uklw/farmasi', $data);
  }

  public function getFarmasi($id)
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Farmasi";
    $data['activeMenu'] = 'uklwFarmasi';

    $data['getData'] = $this->uklwCatatanPemeriksaanModel
      ->select("*, uklw_catatan_pemeriksaan.updated_at as date")
      ->where('id_uklw_catatan_pemeriksaan', $id)
      ->join('uklw_pemkes', 'id_uklw_pemkes')
      ->join('uklw_pasien', 'id_uklw_pasien')->first();

    $data['obat'] = $this->uklwObatModel->orderBy('satuan', 'asc')->findAll();

    return view('uklw/kelolaFarmasi', $data);
  }
  public function setFarmasi($id)
  {
    $data['title'] = "Pelayanan Terpadu Upaya Kesehatan | Farmasi";
    $data['activeMenu'] = 'uklwFarmasi';

    $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

    $data['obat'] = $this->uklwObatModel->orderBy('satuan', 'asc')->findAll();

    return view('uklw/kelolaFarmasi', $data);
  }
}
