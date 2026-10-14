<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;

class Ajax extends BaseController
{

  public function realisasi_akrual()
  {
    if ($this->request->isAJAX()) {
      $data['getTemplateNaskah'] = $this->relaxtimeRealisasiFileModel
        ->where('jenis_naskah', 'Akrual')
        ->orderBy('updated_at', 'DESC')
        ->findAll();

      $msg = [
        'cek' => "berhasil",
        'data' => view("relaxtime/list_file", $data)
      ];
      echo json_encode($msg);
    }
  }
  public function realisasi_sp2d()
  {
    if ($this->request->isAJAX()) {
      $data['getTemplateNaskah'] = $this->relaxtimeRealisasiFileModel
        ->where('jenis_naskah', 'SP2D')
        ->orderBy('updated_at', 'DESC')
        ->findAll();

      $msg = [
        'cek' => "berhasil",
        'data' => view("relaxtime/list_file", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function realisasi_transaksi()
  {
    if ($this->request->isAJAX()) {
      $data['getTemplateNaskah'] = $this->relaxtimeRealisasiFileModel
        ->where('jenis_naskah', 'Transaksi')
        ->orderBy('updated_at', 'DESC')
        ->findAll();

      $msg = [
        'cek' => "berhasil",
        'data' => view("relaxtime/list_file", $data)
      ];
      echo json_encode($msg);
    }
  }
    
  public function getRealisasi($tahun)
  {
    if ($this->request->isAJAX()) {
      $data = $this->relaxtimeDetailUraianModel
        ->select("*, 
          relaxtime_realisasi.updated_at,
          user.nama AS updated,
          user.username AS username")
        ->join("relaxtime_realisasi", 'id_relaxtime_detail_uraian')
        ->join("relaxtime_uraian", 'id_relaxtime_uraian')
        ->join("relaxtime_komponen", 'id_relaxtime_komponen')
        ->join("relaxtime_ro", 'id_relaxtime_ro')
        ->join("relaxtime_kro", 'id_relaxtime_kro')
        ->join("relaxtime_rkt", 'id_relaxtime_rkt')
        ->join("user", "user.id_user=relaxtime_realisasi.updated_by")
        ->where("relaxtime_realisasi.tahun", $tahun)
        ->orderBy('relaxtime_realisasi.bulan', 'DESC')
        ->findAll();

      $msg = [
        'data' => $data,
        'username' => session()->get('username')
      ];

      echo json_encode($msg);
    }
  }
  public function getPagu($tahun)
  {
    if ($this->request->isAJAX()) {
      $data["rkt"] = $this->relaxtimeKomponenModel
        ->select("
        relaxtime_rkt.kode_rkt,
        relaxtime_rkt.deskripsi_rkt
        ")
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_rkt.kode_rkt")
        ->findAll();
      $data["kro"] = $this->relaxtimeKomponenModel
        ->select("
        relaxtime_rkt.kode_rkt,
        relaxtime_kro.digit_kro,
        relaxtime_kro.kode_kro,
        ")
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_kro.digit_kro, relaxtime_kro.kode_kro")
        ->findAll();
      $data['komponen'] = $this->relaxtimeKomponenModel
        ->select("
          relaxtime_kro.digit_kro,
          relaxtime_kro.kode_kro,
          relaxtime_ro.id_relaxtime_ro,
          relaxtime_ro.kode_relaxtime_ro,
          relaxtime_ro.deskripsi_ro,
          relaxtime_ro.vol_ro,
          relaxtime_ro.sat_ro,
          relaxtime_komponen.id_relaxtime_komponen,
          relaxtime_komponen.kode_komponen,
          relaxtime_komponen.deskripsi_komponen,
          relaxtime_komponen.kode_sub_komponen,
          relaxtime_komponen.deskripsi_sub_komponen,
          SUM(jumlah_detail_uraian) AS jumlah,
        ")
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_komponen.id_relaxtime_komponen, relaxtime_komponen.id_relaxtime_ro, relaxtime_komponen.kode_komponen, relaxtime_komponen.kode_sub_komponen")
        ->findAll();

      $data['uraian'] = $this->relaxtimeKomponenModel
        ->select("
            relaxtime_kro.digit_kro,
            relaxtime_kro.kode_kro,
            relaxtime_ro.kode_relaxtime_ro,
            relaxtime_komponen.kode_komponen,
            relaxtime_komponen.kode_sub_komponen,
            relaxtime_uraian.kode_uraian,
            relaxtime_uraian.deskripsi_uraian,
            SUM(jumlah_detail_uraian) AS jumlah_uraian,
        ")
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_komponen.id_relaxtime_komponen, relaxtime_komponen.id_relaxtime_ro, relaxtime_komponen.kode_komponen, relaxtime_komponen.kode_sub_komponen, relaxtime_uraian.kode_uraian")
        ->findAll();

      $data['detail_uraian'] = $this->relaxtimeKomponenModel
        ->select("
            relaxtime_kro.digit_kro,
            relaxtime_kro.kode_kro,
            relaxtime_ro.kode_relaxtime_ro,
            relaxtime_komponen.kode_komponen,
            relaxtime_komponen.kode_sub_komponen,
            relaxtime_uraian.kode_uraian,
            relaxtime_detail_uraian.id_relaxtime_detail_uraian,
            relaxtime_detail_uraian.deskripsi_detail_uraian,
            relaxtime_detail_uraian.vol_detail_uraian,
            relaxtime_detail_uraian.sat_detail_uraian,
            relaxtime_detail_uraian.harga_detail_uraian,
            relaxtime_detail_uraian.jumlah_detail_uraian
        ")
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->findAll();


      $data['komponen'] = $this->groupAndSum($data['komponen'], ['digit_kro', 'kode_kro', 'kode_relaxtime_ro'], 'total_jumlah_komponen');
      $data['komponen'] = $this->groupAndSum($data['komponen'], ['digit_kro', 'kode_kro', 'kode_relaxtime_ro', 'kode_komponen'], 'total_jumlah');
      $data['totalPagu'] = $this->relaxtimeDetailUraianModel->select("sum(jumlah_detail_uraian) AS totalPagu")
        ->where('relaxtime_detail_uraian.tahun', $tahun)->first();
      $data['tahun'] = $tahun;
      $msg = [
        'data' => view('relaxtime/list_pagu', $data)
      ];

      echo json_encode($msg);
    }
  }

  public function getSisaAnggaranEdit($id)
  {
    if ($this->request->isAJAX()) {
      $dataS = $this->relaxtimeRealisasiModel->where('id_relaxtime_realisasi', $id)->first();
      $data = $this->relaxtimeRealisasiModel->select("SUM(realisasi) as jumlah")->where('id_relaxtime_detail_uraian', $dataS['id_relaxtime_detail_uraian'])->first();
      $dataPagu = $this->relaxtimeDetailUraianModel->where('id_relaxtime_detail_uraian', $dataS['id_relaxtime_detail_uraian'])->first();

      $data = $dataPagu['jumlah_detail_uraian'] - $data['jumlah'] + $dataS['realisasi'];

      $msg = $data;
      echo json_encode($msg);
    }
  }
  public function getSisaAnggaran($id)
  {
    if ($this->request->isAJAX()) {
      $data = $this->relaxtimeRealisasiModel->select("SUM(realisasi) as jumlah")->where('id_relaxtime_detail_uraian', $id)->first();
      $dataPagu = $this->relaxtimeDetailUraianModel->where('id_relaxtime_detail_uraian', $id)->first();

      $data = $dataPagu['jumlah_detail_uraian'] - $data['jumlah'];

      $msg = $data;
      echo json_encode($msg);
    }
  }


  // Fungsi untuk mengelompokkan dan menghitung total jumlah
  private function groupAndSum($data_array, $keys, $array_name)
  {
    $grouped_data = array();

    // Hitung total jumlah untuk setiap kombinasi
    foreach ($data_array as $row) {
      $key = implode('-', array_map(function ($key) use ($row) {
        return $row[$key];
      }, $keys));

      $grouped_data[$key] = ($grouped_data[$key] ?? 0) + $row['jumlah'];
    }

    // Tambahkan total jumlah ke setiap baris data
    foreach ($data_array as &$row) {
      $key = implode('-', array_map(function ($key) use ($row) {
        return $row[$key];
      }, $keys));

      $row[$array_name] = $grouped_data[$key] ?? 0;
    }

    return $data_array;
  }
  public function getDataPagu($segment, $id)
  {
    if ($this->request->isAJAX()) {

      if ($segment == 'rkt') {
        $data["rkt"] = $this->relaxtimeRKTModel
          ->where('id_relaxtime_rkt', $id)
          ->first();
      } else if ($segment == "kro") {
        $data["kro"] = $this->relaxtimeKROModel
          ->where('id_relaxtime_kro', $id)
          ->first();
      } else if ($segment == "ro") {
        $data["ro"] = $this->relaxtimeROModel
          ->join('relaxtime_kro', 'relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro')
          ->where('id_relaxtime_ro', $id)
          ->first();
      } else if ($segment == "komponen" || $segment == "sub-komponen") {
        $data["komponen"] = $this->relaxtimeKomponenModel
          ->join('relaxtime_ro', 'relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro')
          ->join('relaxtime_kro', 'relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro')
          ->where('id_relaxtime_komponen', $id)
          ->first();
      } else if ($segment == "uraian") {
        $data["uraian"] = $this->relaxtimeKomponenModel
          ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
          ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
          ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
          ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
          ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
          ->where('relaxtime_uraian.id_relaxtime_uraian', $id)
          ->first();
      } else if ($segment == "detil") {
        $data["uraian"] = $this->relaxtimeKomponenModel
          ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
          ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
          ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
          ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
          ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
          ->where('relaxtime_detail_uraian.id_relaxtime_detail_uraian', $id)
          ->first();
      }

      $msg = [
        'data' => 'sukses',
        'icon' => '',
        'title' => '',
        'text' => '',
        'value' => $data,
      ];

      echo json_encode($msg);
    }
  }
}
