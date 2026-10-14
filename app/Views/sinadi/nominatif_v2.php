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
      max-width: 7.5in;
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
      DAFTAR NOMINATIF PERJALANAN DINAS
    </div>
    <div class="center uppercase">
      <?= $perjalanan['0']['nama_kegiatan']; ?>
    </div>
    <div class="center uppercase">
      <?= $perjalanan['0']['tempat_perjalanan']; ?>
    </div>
    <div class="center uppercase">
      <?php
      $originalDate = $perjalanan['0']['tgl_pelaksanaan'];
      $newDate = date("d", strtotime($originalDate));

      $originalDate2 = $perjalanan['0']['tgl_pelaksanaan_akhir'];
      $lastDate = mediumdate_indo($originalDate2);
      // bandingan
      $perbandingan = date("d", strtotime($originalDate2));

      $newsDate = $newDate . ' - ' . $lastDate;
      if ($newDate == $perbandingan) {
        $newsDate = $lastDate;
      } else {
          $perbandingan1 = date("m", strtotime($originalDate));
          $perbandingan2 = date("m", strtotime($originalDate2));
          if ($perbandingan1 != $perbandingan2) {
              $date = "29 APRIL 2024";
              $parts = explode(" ", mediumdate_indo($originalDate));
              $month = $parts[1];
              $day = $parts[0];
              $newsDate = $day . ' ' . $month . ' - ' . mediumdate_indo($originalDate2);;
          }
      }
      echo $newsDate;
      ?>
    </div>
    <div class="center uppercase">
      <?= $perjalanan['0']['mata_anggaran']; ?>
    </div>
  </div>
  <table>

    <?php $i = 0;
    $isDalamKota = ($perjalanan[0]['kota_asal'] == $perjalanan[0]['kota']);
    foreach ($perjalanan as $key) :
      if ($i == 0) :
    ?>
        <thead>
          <tr>
            <th style="text-align: center;" valign="middle" rowspan="2">No</th>
            <th style="text-align: center; width: 80px;" valign="middle" rowspan="2">Nama</th>
            <th style="text-align: center; width: 80px;" valign="middle" rowspan="2">Jabatan</th>
            <th style="text-align: center;" valign="middle" rowspan="2">GOL</th>
            <th style="text-align: center;" valign="middle" rowspan="2">NIP</th>
            <th style="text-align: center;" valign="middle" rowspan="2">NOMOR & TGL. ST</th>
            <?php if ($isDalamKota) : ?>

            <?php else : ?>
              <th style="text-align: center;" valign="middle" rowspan="2">NOMOR & TGL SPPD</th>
            <?php endif; ?>
            <th style="text-align: center;" valign="middle" rowspan="2">ASAL</th>
            <th style="text-align: center;" valign="middle" rowspan="2">TUJUAN</th>
            <th style="text-align: center;" valign="middle" colspan="3">TRANSPORT</th>
            <th style="text-align: center;" valign="middle" colspan="3">UANG HARIAN</th>
            <th style="text-align: center;" valign="middle" colspan="3">PENGINAPAN</th>
            <th style="text-align: center; width: 70px;" valign="middle" rowspan="2">JUMLAH TRANSFER</th>
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
        <?php
      endif;
      $i++;
        ?>
        </thead>
        <?php if (in_array($i, $pageBreak)) : ?>
          <tr style="height: 0; padding: 0; margin: 0;">
            <td colspan="19" style="height: 0; padding: 0; margin: 0;"></td>
          </tr>
          <div class="page-break"></div>
        <?php endif; ?>
        <tbody>
          <tr style="<?= in_array($i, []) ? 'border-bottom: solid 1px' : ''; ?>">
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
            <?php if ($isDalamKota) : ?>
            <?php else : ?>
              <td style="text-align: center;" valign="middle">
                <?= $key['no_sppd'] ?>
                <br>
                <?= date("d-m-Y", strtotime($key['tgl_sppd'])) ?>
              </td>
            <?php endif; ?>
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
          </tr>
        <?php endforeach ?>
        </tbody>

        <tfoot>
          <tr>
            <th colspan="<?= $isDalamKota ? 8 : 9; ?>"></th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($key['total_transport'], 0, ",", ".") ?>
            </th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($key['total_harian'], 0, ",", ".") ?>
            </th>
            <th colspan="2"></th>
            <th>
              <?= 'Rp. ' . number_format($key['total_penginapan'], 0, ",", ".") ?>
            </th>
            <th>
              <?= 'Rp. ' . number_format($key['total_perjalanan'], 0, ",", ".") ?>
            </th>

          </tr>
        </tfoot>
  </table>
  <?php if ($pageBreakTTD1 == true) : ?>
    <div class="page-break"></div>
  <?php endif; ?>
  <div class="bold" style="width: 30%; float: left;">
    <div class="center">
      Menyetujui
    </div>
    <div class="center">
      Pejabat Pembuat Komitmen
    </div>
    <div class="center">
      <?= $perjalanan['0']['alias_kantor']; ?>
    </div>
    <div class="box-ttd center">
        <?php if ($ttd_verifikator_3): ?>
            <img src="<?= $ttd_verifikator_3 ?>" style="height: 100%;" />
        <?php else: ?>
            <p style="color: red; line-height: 40px;">Data belum disetujui oleh verifikator ke-3</p>
        <?php endif; ?>
    </div>
    <div class="center">
      <?= name_capitalize($perjalanan['0']['ppk']); ?>
    </div>
    <div class="center">
      <?= $perjalanan['0']['ppk_nip']; ?>
    </div>
  </div>
  <div class="bold" style="width: 30%; float: right;">
    <div class="center">
      &nbsp;
    </div>
    <div class="center">
      PIC
    </div>
    <div class="center">
      <?= $perjalanan['0']['alias_kantor']; ?>
    </div>
    <div class="box-ttd center">
        <?php if ($ttd_pic['ttd_pic']): ?>
            <img src="<?= $ttd_pic['ttd_pic'] ?>" style="height: 100%;" />
        <?php else: ?>
            <p style="color: red; line-height: 40px;">Belum ada TTD</p>
        <?php endif; ?>
    </div>
    <div class="center">
      <?= name_capitalize($perjalanan['0']['pic']); ?>
    </div>
    <div class="center">
      <?= $perjalanan['0']['pic_nip']; ?>
    </div>
  </div>
  <?php if ($pageBreakTTD2 == true) : ?>
    <div class="page-break"></div>
  <?php endif; ?>
  <!--<div class="center bold" style="width: 100%; clear: both;">-->
  <!--  <div class="center">-->
  <!--    Menyetujui-->
  <!--  </div>-->
  <!--  <div class="center">-->
  <!--    Kepala Subbagian Administrasi Umum-->
  <!--  </div>-->
  <!--  <div class="center">-->
  <!--    <?//= $perjalanan['0']['alias_kantor']; ?>-->
  <!--  </div>-->
  <!--  <div class="box-ttd"></div>-->
  <!--  <div class="center">-->
  <!--    <?//= name_capitalize($perjalanan['0']['kasubag_adum']); ?>-->
  <!--  </div>-->
  <!--  <div class="center">-->
  <!--    <?//= $perjalanan['0']['kasubag_adum_nip']; ?>-->
  <!--  </div>-->
  <!--</div>-->

</body>

</html>