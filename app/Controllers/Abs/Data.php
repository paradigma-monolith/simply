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
    $data['title'] = "ABS | Peta Ruang dan Kategori";
    $data['activeMenu'] = "bangunan";

    $data['lantai'] = $this->absLantaiModel
      ->select('abs_lantai.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_lantai.created_by')
      ->findAll();
    $data['ruang'] = $this->absRuangModel
      ->select('abs_ruang.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_ruang.created_by')
      ->findAll();
    $data['jenis'] = $this->absJenisModel
      ->select('abs_jenis.*, user.nama as nama_pegawai')
      ->join('user', 'user.id_user = abs_jenis.created_by')
      ->findAll();

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

    $data['getData'] = $this->absLokasiModel->where('id_abs_bangunan', $id)->join('abs_image', 'id_abs_image')->first();

    // dd($data['getData']);

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
    $data['title'] = "ABS | Data Barang";
    $data['activeMenu'] = "lokasi";

    // $data['duplikat'] = $this->absLokasiModel->findDuplicateDataBarang();
    $data['duplikat'] = [];

    // dd($data['duplikat']);

    $data['getLokasiBarang'] = $this->absLokasiModel->select('unit_kerja, lantai, ruang, count(id_abs_bangunan) as count')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
      ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
      ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
      ->groupBy('unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang')
      ->orderBy('unit_kerja.unit_kerja, abs_lantai.lantai, abs_ruang.ruang', 'desc')
      ->findAll();

    $data['getLokasiCount'] = $this->absLokasiModel->select('unit_kerja, count(id_abs_bangunan) as count')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
      ->groupBy('unit_kerja.unit_kerja')
      ->orderBy('unit_kerja.unit_kerja', 'desc')
      ->findAll();

    $data['getKategoriBarang'] = $this->absLokasiModel->select('abs_jenis.jenis, count(id_abs_bangunan) as count')
      ->join('abs_jenis', 'id_abs_jenis', 'left')
      ->groupBy('abs_jenis.jenis')
      ->orderBy('abs_jenis.jenis', 'desc')
      ->findAll();

    $data['getBarang'] = $this->absLokasiModel->select('count(id_abs_bangunan) as count')->first();
    $data['getTersedia'] = $this->absLokasiModel->select('count(id_abs_bangunan) as count')->where('status_tersedia', 'Tersedia')->first();
    $data['getTidakTersedia'] = $this->absLokasiModel->select('count(id_abs_bangunan) as count')->where('status_tersedia', 'Tidak Tersedia')->first();
    // dd($data['getKategoriBarang']);
    return view('abs/lokasi', $data);
  }

  public function verifikasiUpdateData()
  {
    $data['title'] = "ABS | Verifikasi & Pembaharuan Data";
    $data['activeMenu'] = "abs_verifikasi";

    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
    $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['jenis'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();
    
    $data['qrCodeSiman'] = $this->absLokasiModel->select('kode_qr_code_pusat')->findAll();

    return view('abs/verifikasi_data', $data);
  }
  public function absQRCode()
  {
    $data['title'] = "ABS | Manajemen QR Code";
    $data['activeMenu'] = "managemen_qr_code";

    $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
    $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
    $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
    $data['jenis'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();

    return view('abs/stiker_qr', $data);
  }
  public function data_abs($qr)
  {
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
    $data['title'] = "ABS | Manajemen Gambar Berdasarkan Kode Barang";
    $data['activeMenu'] = "abs_gambar";

    $data['getData'] = $this->absLokasiModel
      ->select('id_abs_image,image,SUBSTRING_INDEX(kode_qr_code_pusat, "*", 2) AS kode_inventaris, COUNT(*) AS total, data_barang')
      ->join('abs_image', 'id_abs_image')
      ->groupBy('kode_inventaris, id_abs_image')
      ->findAll();

    return view('abs/gambar_abs', $data);
  }
  public function pengajuan()
  {
    $data['title'] = "ABS | Permintaan Pengajuan";
    $data['activeMenu'] = "abs_pengajuan";

    $data['pemindahan'] = $this->absPengajuanPemindahanModel
      ->select('*, abs_pengajuan_pemindahan.created_at')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan')
      ->orderBy('abs_pengajuan_pemindahan.created_at', 'desc')
      ->findAll();

    $data['pemeliharaan'] = $this->absPengajuanPemeliharaanModel
      ->select('*, abs_pengajuan_pemeliharaan.created_at')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan')
      ->orderBy('abs_pengajuan_pemeliharaan.created_at', 'desc')
      ->findAll();

    return view('abs/pengajuan', $data);
  }
  public function pengajuan_pemeliharaan($id)
  {
    $data['title'] = "ABS | Permintaan Pengajuan Pemeliharaan";
    $data['activeMenu'] = "abs_pengajuan";

    $data['pemeliharaan'] = $this->absPengajuanPemeliharaanModel
      ->select('*, abs_pengajuan_pemeliharaan.created_at')
      ->join('user', 'id_user', 'left')
      ->join('abs_lokasi', 'id_abs_bangunan')
      ->where('abs_pengajuan_pemeliharaan.id_abs_pengajuan_pemeliharaan', $id)
      ->first();
    return view('abs/pengajuan_pemeliharaan', $data);
  }
}
