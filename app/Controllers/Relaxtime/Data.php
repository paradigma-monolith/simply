<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function __construct()
  {
    session()->set(['halaman' => '']);
  }
  
  public function download_file($fileName)
  {
    $filePath = './relaxtime/realisasi/' . $fileName;

    if (file_exists($filePath)) {
      return $this->response->download($filePath, null);
    } else {
      return "File tidak ditemukan.";
    }
  }
  
  public function relaxtimeRealisasiAkrual()
  {
    $data['title'] = "Relaxtime | Realisasi Akrual";
    $data['activeMenu'] = "relaxtimeRealisasiAkrual";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('relaxtime/relaxtimeRealisasiAkrual', $data);
  }
  
  public function relaxtimeRealisasiSP2D()
  {
    $data['title'] = "Relaxtime Realisasi SP2D";
    $data['activeMenu'] = "relaxtimeRealisasiSP2D";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('relaxtime/relaxtimeRealisasiSP2D', $data);
  }
  
  public function relaxtimeRealisasiTransaksi()
  {
    $data['title'] = "Relaxtime Realisasi Transaksi";
    $data['activeMenu'] = "relaxtimeRealisasiTransaksi";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('relaxtime/relaxtimeRealisasiTransaksi', $data);
  }
  
  public function importFileRealisasi($file, $id = null)
  {
    $data['title'] = "Relaxtime | Formulir Penyisipan File Realisasi";
    $data['activeMenu'] = $file;

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
      
    $data['pegawai'] = $this->dataPegawaiModel->findAll();

    // arsipTemplateNaskahModel
    if ($id != null) {
      $data['getData'] = $this->relaxtimeRealisasiFileModel
        ->where('id_relaxtime_realisasi_file', $id)->first();
    }

    return view('crud/relaxtime_upload_file', $data);
  }
  
  public function pagu()
  {
    $data['title'] = "Realisasi Anggaran | Pagu"; // kelola_data_cuti
    $data['activeMenu'] = "relaxtimePagu";

    $data['akses'] = $this->userModel
      ->join('relaxtime_akses', 'relaxtime_akses.id_user=user.id_user', 'LEFT')
      ->where("user.username", session()->get('username'))
      ->first();

    if ($data['akses']['id_relaxtime_akses']) {
      if ($data['akses']['status'] == 'Akses Diblokir') {
        $data['activeMenu'] = "relaxtimeBlockir";
      }
    } else {
      $data['activeMenu'] = "relaxtimeBlockir";
    }


    // dd($data['uraian']);

    return view("relaxtime/pagu", $data);
  }
  public function realisasiAnggaran()
  {
    $data['title'] = "Realisasi Anggaran"; // kelola_data_cuti
    $data['activeMenu'] = "relaxtimeRealisasi";

    $data['realisasi'] = $this->relaxtimeKomponenModel
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
      ->join("relaxtime_realisasi", "relaxtime_realisasi.id_relaxtime_detail_uraian=relaxtime_detail_uraian.id_relaxtime_detail_uraian");

    return view("relaxtime/realisasi_anggaran", $data);
  }

  public function hasil_rapat_monev()
  {
    $data['title'] = "Relaxtime | Hasil Rapat Monev";
    $data['activeMenu'] = "hasilRapatMonev";

    $data['dokumen'] = $this->relaxtimeHasilRapatModel->orderBy('tanggal', 'DESC')->findAll();

    return view("relaxtime/hasil_rapat_monev", $data);
  }

  public function pengaturan()
  {
    $data['title'] = "Realisasi Anggaran | Pengaturan"; // kelola_data_cuti
    $data['activeMenu'] = "relaxtimePengaturan";
    $data['akses'] = $this->userModel
      ->join('relaxtime_akses', 'relaxtime_akses.id_user=user.id_user', 'LEFT')
      ->where("user.username", session()->get('username'))
      ->first();

    if ($data['akses']['id_relaxtime_akses']) {
      if ($data['akses']['status'] == 'Akses Diblokir') {
        $data['activeMenu'] = "relaxtimeBlockir";
      }
    } else {
      $data['activeMenu'] = "relaxtimeBlockir";
    }

    $data['user'] = $this->userModel
      ->select('user.id_user,user.nama, user.id_user, relaxtime_akses.id_relaxtime_akses, relaxtime_akses.status')
      ->join('relaxtime_akses', 'relaxtime_akses.id_user=user.id_user', 'LEFT')
      ->orderBy("user.nama", "ASC")
      ->findAll();

    $data['setting'] = $this->relaxtimeSettingModel->first();

    $data['pegawai'] = $this->userModel->where('email !=', '')->findAll();

    return view("relaxtime/setting", $data);
  }
  public function editPagu($kode, $tahun)
  {

    $data['activeMenu'] = "relaxtimePagu";
    if ($kode == "rkt") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit RKT)";

      $data["rkt"] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_rkt.kode_rkt")
        ->findAll();

      return view('crud/relaxtime_rkt', $data);
    } else if ($kode == "kro") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit KRO)";

      $data["kro"] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_kro.digit_kro, relaxtime_kro.kode_kro")
        ->findAll();

      return view('crud/relaxtime_kro', $data);
    } else if ($kode == "ro") {
      $data["ro"] = $this->relaxtimeROModel
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->where('relaxtime_ro.tahun', $tahun)
        ->findAll();

      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit RO)";
      return view('crud/relaxtime_ro', $data);
    } else if ($kode == "komponen") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit Komponen)";
      $data['komponen'] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_komponen.id_relaxtime_ro, relaxtime_komponen.kode_komponen")
        ->findAll();

      return view('crud/relaxtime_komponen', $data);
    } else if ($kode == "sub-komponen") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit Sub Komponen)";
      $data['komponen'] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_komponen.id_relaxtime_komponen, relaxtime_komponen.id_relaxtime_ro, relaxtime_komponen.kode_komponen, relaxtime_komponen.kode_sub_komponen")
        ->findAll();

      return view('crud/relaxtime_sub_komponen', $data);
    } else if ($kode == "akun") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit Akun)";
      $data['uraian'] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->groupBy("relaxtime_komponen.id_relaxtime_komponen, relaxtime_komponen.id_relaxtime_ro, relaxtime_komponen.kode_komponen, relaxtime_komponen.kode_sub_komponen, relaxtime_uraian.kode_uraian")
        ->findAll();

      return view('crud/relaxtime_uraian', $data);
    } else if ($kode == "detil") {
      $data['title'] = "Realisasi Anggaran | Pagu (Halaman Edit Detil)";
      $data['detail_uraian'] = $this->relaxtimeKomponenModel
        ->join("relaxtime_detail_uraian", "relaxtime_detail_uraian.id_relaxtime_komponen=relaxtime_komponen.id_relaxtime_komponen")
        ->join("relaxtime_ro", "relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro")
        ->join("relaxtime_kro", "relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro")
        ->join("relaxtime_rkt", "relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt")
        ->join("relaxtime_uraian", "relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian")
        ->where('relaxtime_ro.tahun', $tahun)
        ->where('relaxtime_detail_uraian.tahun', $tahun)
        ->findAll();

      $data['totalPagu'] = $this->relaxtimeDetailUraianModel->select("sum(jumlah_detail_uraian) AS totalPagu")
        ->where('relaxtime_detail_uraian.tahun', $tahun)->first();
      $data['tahun'] = $tahun;

      return view('crud/relaxtime_detail_uraian', $data);
    } else {
      return redirect()->to('/relaxtime/pagu');
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
  public function targetBulanan($tahun = null)
  {
    $data['activeMenu'] = "relaxtimeTargetBulanan";
    $data['title'] = "Realisasi Anggaran | Target Bulanan";

    if ($tahun == null) {
      $tahun = date("Y");
    }

    $data['tahun'] = $tahun;
    $data['bulan'] = ['Januari', 'February', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $data['targetBulanan'] = $this->relaxtimeTargetBulananModel->where('tahun', $tahun)->first();
    $data['pagu'] = $this->relaxtimeDetailUraianModel->selectSum('jumlah_detail_uraian', 'jumlah')->where('tahun', $tahun)->first();


    $data['jmlTarget'] = 0;
    if ($data['targetBulanan']) {
      for ($i = 0; $i < 12; $i++) {
        $data['jmlTarget'] += $data['targetBulanan']['bulan' . $i + 1];
      }
    }

    $data['realisasi'] = $this->relaxtimeRealisasiModel
      ->selectSum('realisasi', 'realisasi')
      ->select('relaxtime_realisasi.bulan') // Ekstraksi bulan dari tanggal
      ->join('relaxtime_detail_uraian', 'relaxtime_detail_uraian.id_relaxtime_detail_uraian=relaxtime_realisasi.id_relaxtime_detail_uraian')
      ->where("relaxtime_detail_uraian.tahun", $tahun)
      ->groupBy('bulan')
      ->findAll();
    $realisasiUraian = $this->relaxtimeRealisasiModel
      ->selectSum('realisasi', 'realisasi')
      ->select('relaxtime_realisasi.bulan, relaxtime_uraian.kode_uraian') // Ekstraksi bulan dari tanggal
      ->join('relaxtime_detail_uraian', 'relaxtime_detail_uraian.id_relaxtime_detail_uraian=relaxtime_realisasi.id_relaxtime_detail_uraian')
      ->join('relaxtime_uraian', 'relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian')
      ->where("relaxtime_detail_uraian.tahun", $tahun)
      ->groupBy('relaxtime_uraian.kode_uraian')
      ->findAll();

    $data['realisasiUraian'] = [];

    foreach ($realisasiUraian as $item) {
      $bulan = $item['bulan'];
      $kode_uraian = $item['kode_uraian'];
      $nilai = $item['realisasi'];

      // Mengubah kode_uraian menjadi '51', '52', atau '53'
      $kode_uraian_tersedia = ['51', '52', '53'];
      if (in_array(substr($kode_uraian, 0, 2), $kode_uraian_tersedia)) {
        // Tambahkan nilai ke hasil penjumlahan untuk bulan dan kode_uraian yang sesuai
        $kode_uraian_ditampilkan = substr($kode_uraian, 0, 2);

        // Tambahkan bulan jika belum ada dalam array
        if (!isset($data['realisasiUraian'][$bulan])) {
          $data['realisasiUraian'][$bulan] = [
            'bulan' => $bulan,
            'kode_uraian_' . $kode_uraian_ditampilkan => $nilai
          ];
        } else {
          // Tambahkan nilai ke bulan yang sudah ada dalam array
          $data['realisasiUraian'][$bulan]['kode_uraian_' . $kode_uraian_ditampilkan] = isset($data['realisasiUraian'][$bulan]['kode_uraian_' . $kode_uraian_ditampilkan])
            ? $data['realisasiUraian'][$bulan]['kode_uraian_' . $kode_uraian_ditampilkan] + $nilai
            : $nilai;
        }
      }
    }

    $data['batas'] = $this->relaxtimeSettingModel->first();

    return view('relaxtime/target_bulanan', $data);
  }
  public function perbaharuiTargetBulanan($tahun = null)
  {
    $data['activeMenu'] = "relaxtimeTargetBulanan";
    $data['title'] = "Realisasi Anggaran | Target Bulanan";

    if ($tahun == null) {
      return redirect()->to("/relaxtime/target_bulanan");
    }

    $data['tahun'] = $tahun;
    $data['bulan'] = ['Januari', 'February', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $data['targetBulanan'] = $this->relaxtimeTargetBulananModel->where('tahun', $tahun)->first();
    $data['pagu'] = $this->relaxtimeDetailUraianModel->selectSum('jumlah_detail_uraian', 'jumlah')->where('tahun', $tahun)->first();

    $data['jmlTarget'] = 0;
    if ($data['targetBulanan']) {
      for ($i = 0; $i < 12; $i++) {
        $data['jmlTarget'] += $data['targetBulanan']['bulan' . $i + 1];
      }
    }

    return view('crud/relaxtime_target_bulanan', $data);
  }
}
