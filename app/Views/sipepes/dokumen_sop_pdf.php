<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOP</title>
  <style>
    @page {
      margin: 0cm 0cm;
    }

    * {
      /* top: 0;
      left: 0;
      margin: 0; */
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10pt;
    }

    body {
      margin: 20px;
    }

    header {
      position: fixed;
      top: 0cm;
      left: 0cm;
      right: 0cm;
      padding: 20px;
    }

    main {
      margin-top: 180px;
      margin-left: 20px;
      margin-right: 20px;
      margin-bottom: 20px;
      height: 60%;
    }

    footer {
      position: fixed;
      bottom: 0cm;
      left: 0cm;
      right: 0cm;
      padding: 20px;
    }

    th,
    td {
      border: 1px solid black;
      margin: 0;
      padding: 0;
      text-decoration: none;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th {
      text-align: left;
      padding-left: 7px;
    }

    td {
      padding-left: 7px;
    }

    .border-left {
      border-left: none;
    }

    .border-right {
      border-right: none;
    }

    .center {
      text-align: center;
    }

    .bg-column {
      background-color: lightgray;
    }
  </style>
</head>

<body>
  <!-- <header> -->
  <table>
    <tr>
      <td class="center" rowspan="6">
        <strong>Kantor Kesehatan Pelabuhan Kelas II<br>Bandung</strong><br>
        <strong>Substansi Pengendalian Karantina dan<br>Surveilans Epidemiologi</strong><br>
        Jl. Cikapayang No. 5 Bandung – Jawa Barat<br>Telp. 022-4219305
      </td>
      <td>Nomor SOP</td>
      <td><?= $dokumenSop['no_dokumen']; ?></td>
    <tr>
      <td>Tanggal Pembuatan</td>
      <td><?= date('d M Y', strtotime($dokumenSop['tanggal_pembuatan'])); ?></td>
    </tr>
    <tr>
      <td>Tanggal Revisi</td>
      <td><?= date('d M Y', strtotime($dokumenSop['tanggal_revisi'])) . " (Rev. " . $dokumenSop['revisi_ke'] . ")"; ?></td>
    </tr>
    <tr>
      <td>Tanggal Efektif</td>
      <td><?= date('d M Y', strtotime($dokumenSop['tanggal_efektif'])); ?></td>
    </tr>
    <tr>
      <td>Disahkan Oleh</td>
      <td></td>
    </tr>
    <tr>
      <td>Judul SOP</td>
      <td><?= $dokumenSop['judul']; ?></td>
    </tr>
    <tr class="border-left border-right">
      <td class="border-left border-right" colspan="3" height="5"></td>
    </tr>
    <tr>
      <td class="bg-column">DASAR HUKUM</td>
      <td class="bg-column" colspan="2">KUALIFIKASI PELAKSANA</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <tr style="page-break-before: always;">
      <td class="bg-column">KETERKAITAN</td>
      <td class="bg-column" colspan="2">PERALATAN/PERLENGKAPAN</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td class="bg-column">PERINGATAN</td>
      <td class="bg-column" colspan="2">PENCATATAN DAN PENDATAAN</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <!-- <tr>
        <th class="border-right">Halaman</td>
        <th class="border-right border-left">:</td>
        <th class="border-left"><?= $halaman; ?>/<?= $jml_halaman; ?></td>
      </tr> -->
  </table>
  <!-- </header> -->

  <!-- <footer>
    <table>
      <tr>
        <th class="center">DISPOSISI</td>
        <th class="center">NAMA</td>
        <th class="center">JABATAN</td>
        <th class="center">PARAF</td>
      </tr>
      <tr>
        <td>Dibuat Oleh</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Diperiksa Oleh</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Disetujui Oleh</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </footer> -->

  <!-- <main> -->
  <!-- </main> -->
</body>

</html>