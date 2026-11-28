<?php
if (!function_exists('name_capitalize')) {
    helper('tanggal');
}
$first = $perjalanan[0] ?? [];
$namaKantor = trim((string) ($first['nama_kantor'] ?? '')) !== ''
    ? (string) $first['nama_kantor']
    : 'Balai Kekarantinaan Kesehatan Kelas I Bandung';
$lokasiKegiatan = trim((string) ($first['tempat_perjalanan'] ?? '')) !== ''
    ? (string) $first['tempat_perjalanan']
    : (string) ($first['kota'] ?? '');
$tglPenyelenggaraan = \App\Libraries\SinadiSppdSrikandiHelper::formatTanggalPenyelenggaraan($perjalanan);
$ppkNama = name_capitalize((string) ($first['ppk'] ?? ''));
$ppkNipLabel = \App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($first['ppk_nip'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <style>
    @page {
      margin: 22px 26px;
    }

    * {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      box-sizing: border-box;
    }

    body {
      font-size: 7.5pt;
      color: #000;
    }

    .right {
      text-align: right;
    }

    .center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }

    .meta-table td {
      border: none;
      padding: 1px 3px;
      font-size: 7.5pt;
      vertical-align: top;
    }

    .peserta-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      margin-top: 6px;
    }

    .peserta-table thead {
      display: table-header-group;
    }

    .peserta-table tr {
      page-break-inside: avoid;
    }

    .peserta-table th,
    .peserta-table td {
      border: 1px solid #000;
      padding: 3px 4px;
      font-size: 7pt;
      line-height: 1.25;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .peserta-table th {
      text-align: center;
      vertical-align: middle;
      font-weight: bold;
      background-color: #f4f4f4;
      font-size: 6.8pt;
    }

    .peserta-table td {
      vertical-align: middle;
    }

    .ttd-wrapper {
      page-break-inside: avoid;
      margin-top: 14px;
      width: 100%;
    }

    .ttd-table {
      width: 100%;
      border-collapse: collapse;
    }

    .ttd-table td {
      border: none;
      font-size: 8pt;
      vertical-align: top;
      padding: 1px 2px;
    }
  </style>
</head>

<body>
  <div class="right bold" style="font-size: 8pt; margin-bottom: 4px;">
    LAMPIRAN IV
  </div>

  <table class="meta-table">
    <tr>
      <td style="width: 42%;">
        <div class="bold">Lampiran SPD</div>
        <div>Nomor : ${nomor_naskah}</div>
      </td>
      <td style="width: 58%;">
        <div class="bold">PERATURAN DIREKTUR JENDERAL PERBENDAHARAAN NOMOR PER-22/PB/2013</div>
        <div>TENTANG KETENTUAN LEBIH LANJUT PELAKSANAAN PERJALANAN DINAS DALAM NEGERI BAGI PEJABAT NEGARA, PEGAWAI NEGERI DAN PEGAWAI TIDAK TETAP</div>
      </td>
    </tr>
  </table>

  <table class="meta-table" style="width: 75%; margin-bottom: 6px;">
    <tr>
      <td style="width: 28%;">Daftar Peserta Kegiatan</td>
      <td style="width: 2%;">:</td>
      <td style="width: 70%; font-weight: bold;"><?= esc(name_capitalize((string) ($first['nama_kegiatan'] ?? ''))); ?></td>
    </tr>
    <tr>
      <td>Tanggal Penyelenggaraan</td>
      <td>:</td>
      <td><?= esc($tglPenyelenggaraan); ?></td>
    </tr>
    <tr>
      <td>Kota Tempat Penyelenggaraan</td>
      <td>:</td>
      <td><?= esc($lokasiKegiatan); ?></td>
    </tr>
    <tr>
      <td>Satuan Kerja</td>
      <td>:</td>
      <td><?= esc($namaKantor); ?></td>
    </tr>
    <tr>
      <td>Kementerian Negara/Lembaga</td>
      <td>:</td>
      <td>Kementerian Kesehatan RI</td>
    </tr>
  </table>

  <table class="peserta-table">
    <thead>
      <tr>
        <th rowspan="2" style="width: 3%;">NO</th>
        <th rowspan="2" style="width: 14%;">NAMA</th>
        <th rowspan="2" style="width: 6%;">PANGKAT/<br>GOL</th>
        <th rowspan="2" style="width: 13%;">JABATAN</th>
        <th rowspan="2" style="width: 8%;">TEMPAT KEDUDUKAN ASAL</th>
        <th rowspan="2" style="width: 5%;">TINGKAT BIAYA<br>PERJADIN</th>
        <th rowspan="2" style="width: 9%;">ALAT ANGKUT YG DIGUNAKAN</th>
        <th colspan="2" style="width: 19%;">SURAT TUGAS</th>
        <th colspan="2" style="width: 14%;">TANGGAL</th>
        <th colspan="2" rowspan="2" style="width: 6%;">LAMANYA PERJALANAN DINAS</th>
        <th rowspan="2" style="width: 3%;">KET</th>
      </tr>
      <tr>
        <th style="width: 12%;">NOMOR</th>
        <th style="width: 7%;">TANGGAL</th>
        <th style="width: 7%;">KEBERANGKATAN</th>
        <th style="width: 7%;">KEMBALI</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($perjalanan as $idx => $row) :
        $gol = trim((string) ($row['golongan'] ?? ''));
        if (strtolower($gol) === 'default') {
            $gol = '';
        }
        $jabatan = trim((string) ($row['jabatan_pegawai'] ?? ''));
        if (strtolower($jabatan) === 'default') {
            $jabatan = '';
        }
        $nipRaw = trim((string) ($row['nip_pegawai'] ?? ''));
        $durasi = \App\Libraries\SinadiSppdSrikandiHelper::computeDurationDays(
            $row['tgl_pergi'] ?? '',
            $row['tgl_pulang'] ?? ''
        );
      ?>
        <tr>
          <td class="center"><?= $idx + 1; ?></td>
          <td>
            <div><?= esc(name_capitalize((string) ($row['nama_pegawai'] ?? ''))); ?></div>
            <?php if ($nipRaw !== '' && $nipRaw !== '-') : ?>
              <div style="font-size: 6.3pt; color: #333;">NIP. <?= esc($nipRaw); ?></div>
            <?php endif; ?>
          </td>
          <td class="center"><?= esc($gol); ?></td>
          <td><?= esc($jabatan); ?></td>
          <td class="center"><?= esc((string) ($row['kota_asal'] ?? '')); ?></td>
          <td class="center">C</td>
          <td class="center"><?= esc((string) ($row['transportasi'] ?? '')); ?></td>
          <td class="center"><?= esc((string) ($row['no_surat_tugas'] ?? '')); ?></td>
          <td class="center"><?= esc(\App\Libraries\SinadiSppdSrikandiHelper::formatDateIndoSafe($row['tgl_surat_tugas'] ?? '')); ?></td>
          <td class="center"><?= esc(\App\Libraries\SinadiSppdSrikandiHelper::formatDateIndoSafe($row['tgl_pergi'] ?? '')); ?></td>
          <td class="center"><?= esc(\App\Libraries\SinadiSppdSrikandiHelper::formatDateIndoSafe($row['tgl_pulang'] ?? '')); ?></td>
          <td class="center" style="width: 3%;"><?= esc($durasi); ?></td>
          <td class="center" style="width: 3%;">Hari</td>
          <td></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="ttd-wrapper">
    <table class="ttd-table">
      <tr>
        <td style="width: 68%;">&nbsp;</td>
        <td style="width: 32%;">
          <div>${tanggal_naskah}</div>
          <div>Pejabat Pembuat Komitmen</div>
          <div><?= esc($namaKantor); ?></div>
          <div style="height: 64px; line-height: 64px; padding-left: 22px;">
            ${ttd_pengirim1}
          </div>
          <div><strong><?= esc($ppkNama); ?></strong></div>
          <div><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
