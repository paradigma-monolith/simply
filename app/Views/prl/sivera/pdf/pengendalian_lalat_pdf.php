<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Laporan Kegiatan Pengendalian Vektor Lalat'); ?></title>
  <style>
    @page {
      margin: 1.2cm 2cm 1.5cm 2cm;
      size: A4 portrait;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 11pt;
      line-height: 1.45;
      color: #000;
      margin: 0;
      padding: 0;
    }
    .kop-container {
      width: 100%;
      text-align: center;
      margin-bottom: 20px;
    }
    .kop-img {
      width: 100%;
      max-height: 115px;
    }
    .title-block {
      text-align: center;
      margin-top: 15px;
      margin-bottom: 25px;
    }
    .title-block .line-1 {
      font-size: 11pt;
      letter-spacing: 0.5px;
      margin: 0 0 5px 0;
    }
    .title-block .line-2 {
      font-size: 11pt;
      font-weight: bold;
      letter-spacing: 0.5px;
      margin: 0 0 5px 0;
    }
    .title-block .line-3 {
      font-size: 11pt;
      letter-spacing: 0.5px;
      margin: 0;
    }
    .table-info {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }
    .table-info td {
      padding: 3px 0;
      vertical-align: top;
      font-size: 11pt;
    }
    .col-label {
      width: 165px;
      white-space: nowrap;
    }
    .col-colon {
      width: 20px;
      text-align: left;
    }
    .col-val {
      text-align: left;
    }
    .section-title {
      font-size: 11pt;
      font-weight: bold;
      margin-top: 14px;
      margin-bottom: 6px;
    }
    .bullet-list {
      margin: 0 0 14px 0;
      padding-left: 20px;
    }
    .bullet-list li {
      margin-bottom: 3px;
      font-size: 11pt;
    }
    .table-pelaksana {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #000;
      margin-top: 35px;
    }
    .table-pelaksana td {
      border: 1px solid #000;
      width: 50%;
      vertical-align: top;
      padding: 10px 14px;
    }
    .box-left {
      height: 130px;
    }
    .box-right-header {
      font-size: 11pt;
      margin-bottom: 80px;
    }
    .box-right-name {
      font-size: 11pt;
    }
  </style>
</head>
<body>

  <!-- Kop Surat -->
  <div class="kop-container">
    <img src="<?= $kop; ?>" class="kop-img" alt="Kop Surat">
  </div>

  <!-- Judul Laporan -->
  <div class="title-block">
    <div class="line-1">LAPORAN KEGIATAN</div>
    <div class="line-2">PENGENDALIAN VEKTOR LALAT</div>
    <div class="line-3">BALAI KEKARANTINAAN KESEHATAN KELAS I BANDUNG</div>
  </div>

  <!-- Info Kegiatan -->
  <table class="table-info">
    <tr>
      <td class="col-label">Wilayah Kerja</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($wilker ?? '-'); ?></td>
    </tr>
    <tr>
      <td class="col-label">Hari, Tanggal</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($hari_tanggal ?? '-'); ?></td>
    </tr>
    <tr>
      <td class="col-label">Lokasi</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($lokasi ?? '-'); ?></td>
    </tr>
    <tr>
      <td class="col-label">Persiapan</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($persiapan ?? ''); ?></td>
    </tr>
  </table>

  <!-- Alat dan Bahan -->
  <div class="section-title">Alat dan Bahan</div>
  <ul class="bullet-list">
    <li>Spray Can</li>
    <li>Insektisida Lalat</li>
    <li>Masker</li>
    <li>Handscoon</li>
    <li>Sendok Plastik</li>
    <li>Alat Tulis</li>
    <li>Dokumentasi</li>
  </ul>

  <!-- Pelaksanaan -->
  <div class="section-title">Pelaksanaan</div>
  <table class="table-info">
    <tr>
      <td class="col-label">Hasil Survei Lalat</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($hasil_survei ?? '-'); ?></td>
    </tr>
    <tr>
      <td class="col-label">Pengendalian</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($metode_pengendalian ?? 'Kimia/ Fisika/ Biologi'); ?></td>
    </tr>
    <tr>
      <td class="col-label">Jumlah insektisida</td>
      <td class="col-colon">:</td>
      <td class="col-val"><?= esc($jumlah_insektisida ?? '-'); ?></td>
    </tr>
  </table>

  <!-- Pelaksana Kegiatan Box -->
  <table class="table-pelaksana">
    <tr>
      <td class="box-left">
        <!-- Area catatan / foto / dokumentasi -->
      </td>
      <td>
        <div class="box-right-header">Pelaksana Kegiatan</div>
        <div class="box-right-name">Nama: <?= esc($nama_pegawai ?? '-'); ?></div>
      </td>
    </tr>
  </table>

</body>
</html>
