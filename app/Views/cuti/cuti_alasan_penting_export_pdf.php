<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <style>
    * {
      top: 0;
      left: 0;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 8.5pt;
    }

    table,
    th,
    td {
      height: 15px;
      font-weight: normal;
      border: 1px solid black;
      border-collapse: collapse;
      /* width: 100%; */
    }

    body {
      margin: 56px;
    }

    table {
      border-collapse: collapse;
    }

    table th {
      margin-bottom: 10.6px;
      padding-left: 6px;
      /* font-style: italic; */
      text-align: left;
    }

    table td {
      padding-left: 6px;
    }

    table td.title {
      font-size: left;
      /* font-style: italic; */
    }

    .judul {
      text-align: center;
      /* font-style: italic; */
      font-weight: bold;
    }

    .center {
      text-align: center;
    }

    div.bag-1 {
      margin-bottom: 25px;
      right: 0;
      width: 55%;
      margin-left: 45%;
      text-align: left;
      /* font-style: italic; */
    }

    div.bag-2 {
      margin-bottom: 0px;
      width: 100%;
      text-align: center;
    }

    div.bag-seterusnya {
      margin-bottom: 0px;
      width: 100%;
    }

    @font-face {
      font-family: "CustomFont";
      src: url("/Font/Wingdings 2") format("truetype");
    }

    .ceklis {
      /* font-family: "WINGDNG2"; */
      font-family: 'Wingdings 2';
      font-size: 8.5pt;
    }
  </style>
</head>

<body>
  <?php 
  $finalTTD = "_pejabat"; 
  if($dataCuti['status_pegawai'] == "PPNPN")
    $finalTTD = "_atasan";
  ?>
  <div class="bag-1">
    <div style="margin:5px 0 0 0;">
      <table style="border: none; margin: 0; padding: 0;">
        <tr style="border: none;">
            <td style="border: none; height: 0; font-size : 8pt;">ANAK LAMPIRAN 1.b</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; height: 0; font-size : 8pt;">PERATURAN BADAN KEPEGAWAIAN NEGARA</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; height: 0; font-size : 8pt;">REPUBLIK INDONESIA</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; height: 0; font-size : 8pt;">NOMOR 24 TAHUN 2017</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; height: 0; font-size : 8pt;">TENTANG TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL</td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0;">
            <td style="border: none; height: 0; font-size : 8pt;"><?= $dataCuti['lokasi_skema']; ?>, <?= $dataCuti['tgl_diajukan']; ?></td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0;">
            <td style="border: none; height: 0; font-size : 8pt;">Kepada</td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0;">
            <td style="border: none; height: 0; font-size : 8pt;">Kepala Biro OSDM Kemenkes RI</td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0;">
            <td style="border: none; height: 0; font-size : 8pt;">di</td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0;">
            <td style="border: none; height: 0; font-size : 8pt;">Tempat</td>
        </tr>
      </table>

    </div>
  </div>
  <div class="bag-2">
    <span class="judul">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</span>
  </div>
  <br />
  <div class="bag-seterusnya">
    <table>
  <!-- ================================================================================== -->
  <tr>
    <th colspan="8"><strong>I.<span style="letter-spacing: 15px;">&nbsp;</span> DATA PEGAWAI</strong></th>
  </tr>
  <tr>
    <td class="title">Nama</td>
    <td colspan="3">

      <?php
      $str = $dataCuti['nama_pegawai'];
      $jabatan_akhirNama = $str;
      $jabatan_awalNama = $str;
      $jabatanAkhir = '';
      $jabatanAwal = '';

      $pos = strpos($jabatan_akhirNama, ",");
      if ($pos != false)
        $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

      $pos = strpos($jabatan_awalNama, ",");
      if ($pos != false)
        $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

      $pos = strpos($jabatan_awalNama, ".");
      if ($pos != false)
        $nama = substr($jabatan_awalNama, $pos + 2);
      else
        $nama = $jabatan_awalNama;

      $pos = strpos($jabatan_awalNama, ".");
      if ($pos != false)
        $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

      $array = explode(",", $jabatan_akhirNama);
      for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] != $nama)
          $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
      }

      $nama = ucwords(strtolower($nama));
      echo $jabatanAwal . ' ' . $nama . $jabatanAkhir;
      ?>
    </td>
    <td class="title">NIP</td>
    <td colspan="3"><?= $dataCuti['nip_pegawai']; ?></td>
  </tr>
  <tr>
    <td class="title">Jabatan</td>
    <td colspan="3"><?= $dataCuti['jabatan_pegawai']; ?></td>
    <td class="title">Masa Kerja</td>
    <td colspan="3">
      <?php
      if (isset($dataCuti['masa_kerja_pegawai'])) {
        $tanggal_sekarang = $dataCuti['tanggal_pengajuan'];
        $tanggal_masuk = $dataCuti['tgl_awal_masa'];
        $selisih_hari = (strtotime($tanggal_sekarang) - strtotime($tanggal_masuk)) / (60 * 60 * 24);

        $waktuSelisih = $selisih_hari;
        $waktu[0] =  floor($waktuSelisih / 365);
        $sisa_hari = $waktuSelisih % 365;
        $waktu[1] = floor($sisa_hari / 30);
        $sisa_hari = $waktuSelisih % 30;
        $waktu[2] = $sisa_hari;

        echo $waktu[0] != 0 ? $waktu[0] . " Tahun " : '';
        echo $waktu[1] != 0 ? $waktu[1] . " Bulan " : '';
        echo $waktu[2] != 0 ? $waktu[2] . " Hari " : '';
        if ($waktu[0] == 0 && $waktu[1] == 0 && $waktu[2] == 0) {
          echo 'Tidak diketahui';
        }
      } else {
        echo '-';
      }
      ?>
    </td>
  </tr>
  <tr>
    <td class="title">Unit Kerja</td>
    <td colspan="7"><?= $dataCuti['unit_kerja_pegawai']; ?></td>
  </tr>
  <!-- ================================================================================== -->

  <tr>
    <th colspan="8"><strong>II.<span style="letter-spacing: 15px;">&nbsp;</span> JENIS CUTI YANG DIAMBIL</strong></th>
  </tr>
  <tr>
    <td colspan="3" class="title">1.&ensp; Cuti Tahunan</td>
    <td style="width: 150px;" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Tahunan' ? $dataCuti['ceklis'] : ''; ?></td>
    <td style="width: 100px;" class="title">2.&ensp; Cuti Besar</td>
    <td colspan="3" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Besar' ? $dataCuti['ceklis'] : ''; ?></td>
  </tr>
  <tr>
    <td colspan="3" class="title">3.&ensp; Cuti Sakit</td>
    <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Sakit' ? $dataCuti['ceklis'] : ''; ?></td>
    <td class="title">4.&ensp; Cuti Melahirkan</td>
    <td colspan="3" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Melahirkan' ? $dataCuti['ceklis'] : ''; ?></td>
  </tr>
  <tr>
    <td colspan="3" class="title">5.&ensp; Cuti Karena Alasan Penting</td>
    <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Karena Alasan Penting' ? $dataCuti['ceklis'] : ''; ?></td>
    <td class="title">6.&ensp; Cuti CTLN</td>
    <td colspan="3" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti di Luar Tanggungan Negara' ? $dataCuti['ceklis'] : ''; ?></td>
  </tr>
  <!-- ================================================================================== -->

  <tr>
    <th colspan="8"><strong>III.<span style="letter-spacing: 15px;">&nbsp;</span> ALASAN CUTI</strong></th>
  </tr>
  <tr>
    <td colspan="8"><?= $dataCuti['alasan_cuti']; ?></td>
  </tr>
  <!-- ================================================================================== -->

  <tr>
    <th colspan="8"><strong>IV.<span style="letter-spacing: 15px;">&nbsp;</span> LAMANYA CUTI</strong></th>
  </tr>

  <?php
  $tanggal = explode("|", $dataCuti['total_hari']);
  $tanggal1 = explode("|", $dataCuti['periode1']);
  $tanggal2 = explode("|", $dataCuti['periode2']);
  for ($i = 0; $i < count($tanggal); $i++) : ?>
    <tr>
      <td class="title">Selama</td>
      <td colspan="3">
        <?php if ($tanggal[$i] < 30) : ?>
          <span class="d-inline-block"><?= $tanggal[$i]; ?> (hari/<strike>bulan/tahun</strike>)</span>
        <?php elseif ($tanggal[$i] >= 30 && $tanggal[$i] < 365) : ?>
          <span class="d-inline-block"><?= $tanggal[$i]; ?> (<strike>hari</strike>/bulan/<strike>tahun</strike>)</span>
        <?php else : ?>
          <span class="d-inline-block"><?= $tanggal[$i]; ?> (<strike>hari/bulan</strike>/tahun)</span>
        <?php endif; ?>
      </td>
      <td class="title">Mulai tanggal</td>
      <td><?= $tanggal1[$i]; ?></td>
      <td class="title">s/d</td>
      <td><?= $tanggal2[$i]; ?></td>
    </tr>
  <?php endfor; ?>
  <!-- ================================================================================== -->

  <tr>
    <th colspan="8"><strong>V.<span style="letter-spacing: 15px;">&nbsp;</span> CATATAN CUTI</strong></th>
  </tr>
  <tr>
    <td colspan="3" class="title" colspan="3">1.&ensp; Cuti Tahunan</td>
    <td colspan="2" class="title">2.&ensp; Cuti Besar</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td class="title">Tahun</td>
    <td class="title center">Sisa</td>
    <td class="title center">Keterangan</td>
    <td colspan="2" class="title">3.&ensp; Cuti Sakit</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td class="title">N-2</td>
    <td></td>
    <td></td>
    <td colspan="2" class="title">4.&ensp; Cuti Melahirkan</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td class="title">N-1</td>
    <td></td>
    <td></td>
    <td colspan="2" class="title">5.&ensp; Cuti Karena Alasan Penting</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td class="title">N</td>
    <td></td>
    <td></td>
    <td colspan="2" class="title">6.&ensp; Cuti diluar Tanggungan Negara</td>
    <td colspan="3"></td>
  </tr>
  <!-- ================================================================================== -->

  <tr>
    <th colspan="8"><strong>VI.<span style="letter-spacing: 15px;">&nbsp;</span> ALAMAT SELAMA MENJALANKAN CUTI</strong></th>
  </tr>
  <tr>
    <td colspan="5"></td>
    <td class="title center">Telp</td>
    <td colspan="2"><?= $dataCuti['no_telp']; ?></td>
  </tr>
  <tr>
    <td colspan="5" rowspan="2" style=" vertical-align: top;"><?= $dataCuti['alamat_cuti']; ?></td>
    <td colspan="3">
      <div class="title">Hormat saya</div>
      <div style="top:0; height: 55px; margin-left:20px;"> <img style="width: 130px;" src="<?= $dataCuti['ttd_data_pegawai']; ?>" alt="TTD"></div>
      <div class="title"><strong>

          <?php
          $str = $dataCuti['nama_pegawai'];
          $jabatan_akhirNama = $str;
          $jabatan_awalNama = $str;
          $jabatanAkhir = '';
          $jabatanAwal = '';

          $pos = strpos($jabatan_akhirNama, ",");
          if ($pos != false)
            $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

          $pos = strpos($jabatan_awalNama, ",");
          if ($pos != false)
            $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

          $pos = strpos($jabatan_awalNama, ".");
          if ($pos != false)
            $nama = substr($jabatan_awalNama, $pos + 2);
          else
            $nama = $jabatan_awalNama;

          $pos = strpos($jabatan_awalNama, ".");
          if ($pos != false)
            $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

          $array = explode(",", $jabatan_akhirNama);
          for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] != $nama)
              $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
          }

          $nama = ucwords(strtolower($nama));
          echo $jabatanAwal . ' ' . $nama . $jabatanAkhir;
          ?>
        </strong></div>
    </td>
  </tr>
  <tr>
    <td colspan="3">NIP. <?= $dataCuti['nip_pegawai']; ?></td>
  </tr>

  <!-- ================================================================================== -->
  <?php if ($dataCuti['jenis_cuti'] == "Cuti Karena Alasan Penting") : ?>
    <tr>
      <th colspan="8"><strong>VII.<span style="letter-spacing: 15px;">&nbsp;</span> PERTIMBANGAN ATASAN LANGSUNG</strong></th>
    </tr>
    <tr>
      <td colspan="3" class="">DISETUJUI</td>
      <td class="">PERUBAHAN</td>
      <td class="">DITANGGUHKAN</td>
      <td colspan="3" class="">TIDAK DISETUJUI</td>
    </tr>
    <tr>
      <td colspan="3" class="center"><?= $dataCuti['id_cuti_status' . $finalTTD] == 2 ? $dataCuti['ceklis'] : ''; ?></td>
      <td class="center"><?= $dataCuti['id_cuti_status' . $finalTTD] == 1 ? $dataCuti['ceklis'] : ''; ?></td>
      <td class="center"><?= $dataCuti['id_cuti_status' . $finalTTD] == 3 ? $dataCuti['ceklis'] : ''; ?></td>
      <td colspan="3" class="center"><?= $dataCuti['id_cuti_status' . $finalTTD] == 4 ? $dataCuti['ceklis'] : ''; ?></td>
    </tr>
    <tr>
      <td colspan="5" style="border-style: none;"></td>
      <td colspan="3">
        <div class="title"><?= $dataCuti['jabatan' . $finalTTD]; ?></div>
        <div style="top:0; height: 55px; margin-left:20px;"> <?= $dataCuti['id_cuti_status' . $finalTTD] == 2 ? '<img style="width: 130px;" src="' . $dataCuti['ttd' . $finalTTD] . '" alt="TTD">' : ''; ?></div>
        <div class="title"><strong>
            <?php
            $str = $dataCuti['nama' . $finalTTD];
            $jabatan_akhirNama = $str;
            $jabatan_awalNama = $str;
            $jabatanAkhir = '';
            $jabatanAwal = '';

            $pos = strpos($jabatan_akhirNama, ",");
            if ($pos != false)
              $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

            $pos = strpos($jabatan_awalNama, ",");
            if ($pos != false)
              $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

            $pos = strpos($jabatan_awalNama, ".");
            if ($pos != false)
              $nama = substr($jabatan_awalNama, $pos + 2);
            else
              $nama = $jabatan_awalNama;

            $pos = strpos($jabatan_awalNama, ".");
            if ($pos != false)
              $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

            $array = explode(",", $jabatan_akhirNama);
            for ($i = 0; $i < count($array); $i++) {
              if ($array[$i] != $nama)
                $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
            }

            $nama = ucwords(strtolower($nama));
            echo $jabatanAwal . ' ' . $nama . $jabatanAkhir;
            ?>
          </strong></div>
        <div class="title">NIP. <?= $dataCuti['nip' . $finalTTD]; ?></div>
      </td>
    </tr>
  <?php else : ?>
    
  <?php endif; ?>

  <!-- ================================================================================== -->
  <?php if ($dataCuti['id_status_pegawai'] != 4) : ?>
    <tr>
      <th colspan="8"><strong>VIII.<span style="letter-spacing: 15px;">&nbsp;</span> KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</strong></th>
    </tr>
    <tr>
      <td colspan="3" class="">DISETUJUI</td>
      <td class="">PERUBAHAN</td>
      <td class="">DITANGGUHKAN</td>
      <td colspan="3" class="">TIDAK DISETUJUI</td>
    </tr>
    <tr>
      <td colspan="3" class="center"><?= $dataCuti['id_cuti_status_atasan'] == 2 ? '' : ''; ?></td>
      <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 1 ? '' : ''; ?></td>
      <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 3 ? '' : ''; ?></td>
      <td colspan="3" class="center"><?= $dataCuti['id_cuti_status_atasan'] == 4 ? '' : ''; ?></td>
    </tr>
    <tr>
      <td colspan="5" style="border-style: none;"></td>
      <td colspan="3">
        <div class="title">Kepala Biro OSDM</div>
        <div style="top:0; height: 55px; margin-left:20px;"> </div>
        <div class="title"><strong>

            <?php
            $str = $dataCuti['osdm_nama'];
            $jabatan_akhirNama = $str;
            $jabatan_awalNama = $str;
            $jabatanAkhir = '';
            $jabatanAwal = '';

            $pos = strpos($jabatan_akhirNama, ",");
            if ($pos != false)
              $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

            $pos = strpos($jabatan_awalNama, ",");
            if ($pos != false)
              $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

            $pos = strpos($jabatan_awalNama, ".");
            if ($pos != false)
              $nama = substr($jabatan_awalNama, $pos + 2);
            else
              $nama = $jabatan_awalNama;

            $pos = strpos($jabatan_awalNama, ".");
            if ($pos != false)
              $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

            $array = explode(",", $jabatan_akhirNama);
            for ($i = 0; $i < count($array); $i++) {
              if ($array[$i] != $nama)
                $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
            }

            $nama = ucwords(strtolower($nama));
            if($nama == "") {
                echo "(__________________________)";
            } else {
                echo $jabatanAwal . ' ' . $nama . $jabatanAkhir;
            }
            ?>
          </strong></div>
        <div class="title">NIP. <?= $dataCuti['osdm_nip']; ?></div>
      </td>
    </tr>
  <?php endif; ?>
</table>
  </div>
</body>

</html>