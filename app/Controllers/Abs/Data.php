<?php

namespace App\Controllers\Abs;

use App\Controllers\BaseController;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class Data extends BaseController
{
  public function bangunan()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Peta Ruang dan Kategori";
    $data['activeMenu'] = "bangunan";

    $lantaiCounts = [];
    foreach ($this->absLokasiModel->select('id_abs_lantai, COUNT(id_abs_bangunan) AS total')->groupBy('id_abs_lantai')->findAll() as $r) {
      $lantaiCounts[(int) ($r['id_abs_lantai'] ?? 0)] = (int) ($r['total'] ?? 0);
    }
    $ruangCounts = [];
    foreach ($this->absLokasiModel->select('id_abs_ruang, COUNT(id_abs_bangunan) AS total')->groupBy('id_abs_ruang')->findAll() as $r) {
      $ruangCounts[(int) ($r['id_abs_ruang'] ?? 0)] = (int) ($r['total'] ?? 0);
    }
    $jenisCounts = [];
    foreach ($this->absLokasiModel->select('id_abs_jenis, COUNT(id_abs_bangunan) AS total')->groupBy('id_abs_jenis')->findAll() as $r) {
      $jenisCounts[(int) ($r['id_abs_jenis'] ?? 0)] = (int) ($r['total'] ?? 0);
    }

    $lantaiRows = $this->absLantaiModel
      ->select('abs_lantai.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_lantai.created_by', 'left')
      ->orderBy('abs_lantai.lantai', 'ASC')
      ->findAll();
    foreach ($lantaiRows as &$row) {
      $row['jumlah_barang'] = $lantaiCounts[(int) ($row['id_abs_lantai'] ?? 0)] ?? 0;
    }
    unset($row);

    $ruangRows = $this->absRuangModel
      ->select('abs_ruang.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_ruang.created_by', 'left')
      ->orderBy('abs_ruang.ruang', 'ASC')
      ->findAll();
    foreach ($ruangRows as &$row) {
      $row['jumlah_barang'] = $ruangCounts[(int) ($row['id_abs_ruang'] ?? 0)] ?? 0;
    }
    unset($row);

    $jenisRows = $this->absJenisModel
      ->select('abs_jenis.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_jenis.created_by', 'left')
      ->orderBy('abs_jenis.jenis', 'ASC')
      ->findAll();
    foreach ($jenisRows as &$row) {
      $row['jumlah_barang'] = $jenisCounts[(int) ($row['id_abs_jenis'] ?? 0)] ?? 0;
    }
    unset($row);

    $data['lantai'] = $lantaiRows;
    $data['ruang'] = $ruangRows;
    $data['jenis'] = $jenisRows;

    return view('abs/bangunan', $data);
  }

  public function lokasi()
  {
    $data['title'] = "ABS | Formulir Tambah Data";
    $data['activeMenu'] = "lokasi";

    $data['wilayahKerja'] = $this->unitKerjaModel
      ->orderBy('unit_kerja', 'asc')
      ->findAll();
    $data['lantai'] = $this->absLantaiModel
      ->orderBy('lantai', 'asc')
      ->findAll();
    $data['ruang'] = $this->absRuangModel
      ->orderBy('ruang', 'asc')
      ->findAll();
    $data['jenisBarang'] = $this->absJenisModel
      ->orderBy('jenis', 'asc')
      ->findAll();

    return view('crud/abs_tambah_data', $data);
  }

  public function edit_lokasi($id)
  {
    $data['title'] = "ABS | Formulir Edit Data";
    $data['activeMenu'] = "lokasi";

    $data['getData'] = $this->absLokasiModel
      ->where('id_abs_bangunan', (int) $id)
      ->join('abs_image', 'id_abs_image', 'left')
      ->first();

    if (!$data['getData']) {
      session()->setFlashdata('error', 'Data inventaris tidak ditemukan.');
      return redirect()->to('/abs/data_barang');
    }

    $data['wilayahKerja'] = $this->unitKerjaModel
      ->orderBy('unit_kerja', 'asc')
      ->findAll();
    $data['lantai'] = $this->absLantaiModel
      ->orderBy('lantai', 'asc')
      ->findAll();
    $data['ruang'] = $this->absRuangModel
      ->orderBy('ruang', 'asc')
      ->findAll();
    $data['jenisBarang'] = $this->absJenisModel
      ->orderBy('jenis', 'asc')
      ->findAll();

    return view('crud/abs_edit_data', $data);
  }

  public function dataBarang()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Daftar Inventaris BMN";
    $data['activeMenu'] = "lokasi";
    $data['duplikat'] = [];

    $data['getLokasiBarang'] = $this->absLokasiModel
      ->select('unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang, COUNT(id_abs_bangunan) as count')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
      ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
      ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
      ->groupBy('unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang')
      ->orderBy('unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang', 'desc')
      ->findAll();

    // Hitung total per unit_kerja langsung di PHP dari hasil agregasi di atas (hemat 1 query SQL)
    $lokasiCountMap = [];
    foreach ($data['getLokasiBarang'] as $lok) {
      $ukKey = $lok['unit_kerja'] ?? '__NULL__';
      if (!isset($lokasiCountMap[$ukKey])) {
        $lokasiCountMap[$ukKey] = [
          'unit_kerja' => $lok['unit_kerja'],
          'count'      => 0,
        ];
      }
      $lokasiCountMap[$ukKey]['count'] += (int) ($lok['count'] ?? 0);
    }
    $data['getLokasiCount'] = array_values($lokasiCountMap);

    $data['getKategoriBarang'] = $this->absLokasiModel
      ->select('abs_jenis.jenis, COUNT(id_abs_bangunan) as count')
      ->join('abs_jenis', 'id_abs_jenis', 'left')
      ->groupBy('abs_jenis.jenis')
      ->orderBy('abs_jenis.jenis', 'desc')
      ->findAll();

    // 1 query agregasi tunggal untuk total, tersedia, dan tidak tersedia (hemat 2 query SQL)
    $statusAgg = $this->absLokasiModel
      ->select("COUNT(id_abs_bangunan) as total, SUM(CASE WHEN status_tersedia = 'Tersedia' THEN 1 ELSE 0 END) as tersedia, SUM(CASE WHEN status_tersedia = 'Tidak Tersedia' THEN 1 ELSE 0 END) as tidak_tersedia")
      ->first();

    $data['getBarang'] = ['count' => (int) ($statusAgg['total'] ?? 0)];
    $data['getTersedia'] = ['count' => (int) ($statusAgg['tersedia'] ?? 0)];
    $data['getTidakTersedia'] = ['count' => (int) ($statusAgg['tidak_tersedia'] ?? 0)];

    $data['unit_kerja_list'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['lantai_list'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
    $data['ruang_list'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['jenis_list'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();

    // Pre-load data awal untuk Wilayah Kerja pertama (atau 250 data terbaru) agar halaman langsung menampilkan inventaris
    $defaultWilker = null;
    foreach ($data['getLokasiCount'] as $lc) {
      if (!empty($lc['unit_kerja'])) {
        $defaultWilker = $lc['unit_kerja'];
        break;
      }
    }
    $data['defaultWilker'] = $defaultWilker;

    $initialBuilder = $this->absLokasiModel
      ->select('abs_lokasi.id_abs_bangunan, abs_lokasi.id_abs_image, abs_lokasi.data_barang, abs_lokasi.kode_qr_code_pusat, abs_lokasi.kode_qr_code_internal, abs_lokasi.status_tersedia, unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang, abs_jenis.jenis')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
      ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
      ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
      ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left');

    if ($defaultWilker !== null) {
      $initialBuilder->where('unit_kerja.unit_kerja', $defaultWilker);
    } else {
      $initialBuilder->limit(250);
    }
    $data['initialGetData'] = $initialBuilder
      ->orderBy('abs_lokasi.id_abs_bangunan', 'desc')
      ->findAll();

    return view('abs/lokasi', $data);
  }

  public function verifikasiUpdateData()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Verifikasi & Pembaharuan Data";
    $data['activeMenu'] = "abs_verifikasi";

    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
    $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['jenis'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();

    $data['qrCodeSiman'] = $this->absLokasiModel
      ->select('kode_qr_code_pusat')
      ->where('kode_qr_code_pusat IS NOT NULL')
      ->where('kode_qr_code_pusat !=', '')
      ->findAll();

    return view('abs/verifikasi_data', $data);
  }
  public function absQRCode()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Manajemen QR Code";
    $data['activeMenu'] = "managemen_qr_code";

    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
    $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['jenis'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();

    $data['getFile'] = $this->absExportLabelModel
      ->select('abs_export_label.*, user.nama')
      ->join('user', 'user.id_user=abs_export_label.created_by', 'left')
      ->orderBy('abs_export_label.created_at', 'desc')
      ->findAll();

    return view('abs/stiker_qr', $data);
  }
  public function data_abs($qr)
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['getData'] = $this->absLokasiModel
      ->join('unit_kerja', 'id_unit_kerja', 'left')
      ->join('abs_ruang', 'id_abs_ruang', 'left')
      ->join('abs_lantai', 'id_abs_lantai', 'left')
      ->join('abs_jenis', 'id_abs_jenis', 'left')
      ->join('abs_image', 'id_abs_image', 'left')
      ->where('kode_qr_code_internal', $qr)->first();

    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();

    return view('abs/data_abs', $data);
  }
  public function abs_gambar()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Manajemen Gambar Berdasarkan Kode Barang";
    $data['activeMenu'] = "abs_gambar";

    $data['getData'] = $this->absLokasiModel
      ->select('abs_lokasi.id_abs_image, MAX(abs_image.image) AS image, SUBSTRING_INDEX(kode_qr_code_pusat, "*", 2) AS kode_inventaris, COUNT(*) AS total, MAX(abs_lokasi.data_barang) AS data_barang')
      ->join('abs_image', 'id_abs_image', 'left')
      ->groupBy('kode_inventaris, abs_lokasi.id_abs_image')
      ->findAll();

    return view('abs/gambar_abs', $data);
  }
  public function pengajuan()
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Permintaan Pengajuan";
    $data['activeMenu'] = "abs_pengajuan";

    $data['pemindahan'] = $this->absPengajuanPemindahanModel
      ->select('abs_pengajuan_pemindahan.*, user.nama, abs_lokasi.data_barang, abs_lokasi.kode_qr_code_pusat, abs_lokasi.kode_qr_code_internal')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan', 'left')
      ->orderBy('abs_pengajuan_pemindahan.created_at', 'desc')
      ->findAll();

    $data['pemeliharaan'] = $this->absPengajuanPemeliharaanModel
      ->select('abs_pengajuan_pemeliharaan.*, user.nama, abs_lokasi.data_barang, abs_lokasi.kode_qr_code_pusat, abs_lokasi.kode_qr_code_internal')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan', 'left')
      ->orderBy('abs_pengajuan_pemeliharaan.created_at', 'desc')
      ->findAll();

    return view('abs/pengajuan', $data);
  }
  public function pengajuan_pemeliharaan($id)
  {
    \App\Models\AbsLokasiModel::ensureIndexes();

    $data['title'] = "ABS | Permintaan Pengajuan Pemeliharaan";
    $data['activeMenu'] = "abs_pengajuan";

    $data['pemeliharaan'] = $this->absPengajuanPemeliharaanModel
      ->select('abs_pengajuan_pemeliharaan.*, user.nama, abs_lokasi.data_barang, abs_lokasi.kode_qr_code_pusat, abs_lokasi.kode_qr_code_internal')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan', 'left')
      ->where('abs_pengajuan_pemeliharaan.id_abs_pengajuan_pemeliharaan', (int) $id)
      ->first();

    if (!$data['pemeliharaan']) {
      session()->setFlashdata('error', 'Data pengajuan pemeliharaan tidak ditemukan.');
      return redirect()->to('/abs/pengajuan');
    }

    return view('abs/pengajuan_pemeliharaan', $data);
  }
}
