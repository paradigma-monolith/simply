<?php
$jmlData = count($perjalanan);
$cekPertama = true;
$pageBreak = [15];
$pageBreakTTD1 = false;
$pageBreakTTD2 = false;
$countNumber = 15;
for ($i = 0; $i < $jmlData; $i) {
  if ($cekPertama == true) {
    if ($jmlData >= 15) {
      $cekPertama = false;
    } else {
      if ($jmlData > 11) {
        $pageBreakTTD1 = true;
      } else {
        if ($jmlData > 8) {
          $pageBreakTTD2 = true;
        }
      }
    }
    $i += 15;
  } else {
    if ($jmlData >= ($countNumber + 16)) {
      $countNumber += 16;
      $pageBreak[] = $countNumber;
    }
    $i += 16;
  }
}

if (($jmlData - $countNumber) > 13) {
  $pageBreakTTD1 = true;
} else {
  if (($jmlData - $countNumber) > 10) {
    $countNumber += 10;
    $pageBreakTTD2 = true;
  }
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 7.5pt;
    }

    body {
      margin: 40px;
      margin-bottom: 0px;
    }

    .center {
      text-align: center;
    }

    .uppercase {
      text-transform: uppercase;
    }

    .bold {
      font-weight: bold;
    }

    .box-ttd {
      height: 55px;
    }

    .page-break {
      page-break-before: always;
    }

    /* ================Tabel================= */
    /* Mengatur gaya dasar untuk tabel */
    table {
      /* width: 100%; */
      /* Lebar maksimum yang diizinkan, sesuaikan sesuai kebutuhan */
      width: 100%;
      border-collapse: collapse;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: 8px;
      margin-top: 8px;
    }

    /* Mengatur gaya untuk sel tabel */
    td,
    th {
      border: 1px solid #000;
      padding: 0px 4px;
      text-align: left;
    }

    th {
      /* background-color: #f0f0f2; */
      padding-top: 2px;
      padding-bottom: 2px;
    }

    td {
      font-size: 6.5pt;
      height: 40px;
    }

    @media print {
      table {
        width: 100%;
      }

      th,
      td {
        width: auto;
        /* Mengatur lebar kolom menjadi otomatis saat mencetak */
      }
    }
  </style>
</head>

<body>
  <div class="bold">
    <div class="center">
      DAFTAR EXPORT PERJALANAN DINAS
    </div>
    <div class="center uppercase">
      PERIODE: <?= $periode ?? '-' ?>
    </div>
  </div>
  <table>

    <?php $i = 0;
    // Note: In export report, we might have mixed 'kota' and 'kota_asal' logic, 
    // but here we just list all data. The 'kota' variable logic from nominatif might be per-row in loop, 
    // but here it was outside. We'll stick to displaying all columns.
    
    // Header
    ?>
        <thead>
          <tr>
            <th style="text-align: center;" valign="middle" rowspan="2">No</th>
            <th style="text-align: center; width: 80px;" valign="middle" rowspan="2">Nama</th>
            <th style="text-align: center; width: 80px;" valign="middle" rowspan="2">Jabatan</th>
            <th style="text-align: center;" valign="middle" rowspan="2">GOL</th>
            <th style="text-align: center;" valign="middle" rowspan="2">NIP</th>
            <th style="text-align: center;" valign="middle" rowspan="2">NOMOR & TGL. ST</th>
            <th style="text-align: center;" valign="middle" rowspan="2">NOMOR & TGL SPPD</th>
            <th style="text-align: center;" valign="middle" rowspan="2">ASAL</th>
            <th style="text-align: center;" valign="middle" rowspan="2">TUJUAN</th>
            <th style="text-align: center;" valign="middle" colspan="3">TRANSPORT</th>
            <th style="text-align: center;" valign="middle" colspan="3">UANG HARIAN</th>
            <th style="text-align: center;" valign="middle" colspan="3">PENGINAPAN</th>
            <th style="text-align: center; width: 70px;" valign="middle" rowspan="2">JUMLAH TRANSFER</th>
            <th style="text-align: center;" valign="middle" rowspan="2">KETERANGAN</th>
          </tr>
          <tr>
            <th style="text-align: center;" valign="middle">HARI</th>
            <th style="text-align: center;" valign="middle">TRANSPORT</th>
            <th style="text-align: center;" valign="middle">JUMLAH</th>

            <th style="text-align: center;" valign="middle">HARI</th>
            <th style="text-align: center;" valign="middle">UNIT COST</th>
            <th style="text-align: center;" valign="middle">JUMLAH</th>

            <th style="text-align: center;" valign="middle">KALI</th>
            <th style="text-align: center;" valign="middle">UNIT COST</th>
            <th style="text-align: center;" valign="middle">JUMLAH</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        $total_transport = 0;
        $total_harian = 0;
        $total_penginapan = 0;
        $total_perjalanan = 0;
        
        foreach ($perjalanan as $key) :
            $i++;
            $total_transport += $key['transport_jumlah'];
            $total_harian += $key['harian_jumlah'];
            $total_penginapan += $key['penginapan_jumlah'];
            $total_perjalanan += $key['jumlah_transfer'];
        ?>
          <tr>
            <td style="text-align: center;" valign="middle"><?= $i ?></td>
            <td><?= name_capitalize($key['nama_pegawai']) ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['jabatan_pegawai'] == 'default' ? '-' : $key['jabatan_pegawai']; ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['golongan'] == 'default' ? '-' : $key['golongan']; ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['nip_pegawai'] ?></td>
            <td style="text-align: center;" valign="middle">
              <?= $key['no_surat_tugas'] ?>
              <br>
              <?= date("d-m-Y", strtotime($key['tgl_surat_tugas'])) ?>
            </td>
            <td style="text-align: center;" valign="middle">
                <?= $key['no_sppd'] ?>
                <br>
                <?= $key['tgl_sppd'] ? date("d-m-Y", strtotime($key['tgl_sppd'])) : '-' ?>
            </td>
            <td style="text-align: center;" valign="middle"><?= $key['kota_asal'] ?></td>
            <?php if (strpos($key['kota'], ',') !== false) : ?>
              <td style="text-align: center;" valign="middle">
                <?php
                $text_kota = explode(',', $key['kota']);
                for ($x = 0; $x < count($text_kota); $x++) : ?>
                  <?php echo $text_kota[$x] . "<br/>"; ?>
                <?php endfor ?>
              </td>
            <?php else : ?>
              <td style="text-align: center;" valign="middle"><?= $key['kota'] ?></td>
            <?php endif ?>
            <td style="text-align: center;" valign="middle"><?= $key['transport_hari'] ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['transport_nominal'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['transport_jumlah'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['harian_hari'] ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['harian_nominal'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['harian_jumlah'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['penginapan_hari'] ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['penginapan_nominal'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['penginapan_jumlah'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= 'Rp. ' . number_format($key['jumlah_transfer'], 0, ",", ".") ?></td>
            <td style="text-align: center;" valign="middle"><?= $key['nama_kegiatan'] ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>

        <tfoot>
          <tr>
            <th colspan="9"></th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($total_transport, 0, ",", ".") ?>
            </th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($total_harian, 0, ",", ".") ?>
            </th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($total_penginapan, 0, ",", ".") ?>
            </th>
            <th>
              <?= 'Rp. ' . number_format($total_perjalanan, 0, ",", ".") ?>
            </th>
            <th></th>

          </tr>
        </tfoot>
  </table>

</body>

</html>