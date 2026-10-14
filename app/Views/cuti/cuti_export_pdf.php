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
      font-size: 10pt;
    }

    table,
    th,
    td {
      height: 27px;
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
      width: 35%;
      margin-left: 55%;
      text-align: left;
      /* font-style: italic; */
    }

    div.bag-2 {
      margin-bottom: 28px;
      width: 100%;
      text-align: center;
    }

    div.bag-seterusnya {
      margin-bottom: 28px;
      width: 100%;
    }

    @font-face {
      font-family: "CustomFont";
      src: url("/Font/Wingdings 2") format("truetype");
    }

    .ceklis {
      /* font-family: "WINGDNG2"; */
      font-family: 'Wingdings 2';
      font-size: 10pt;
    }
  </style>
</head>

<body>
  <div class="bag-1">
    <div style="margin:22px 0 0 55px">
      <span class="title"><?= $dataCuti['lokasi_skema']; ?>, <?= $dataCuti['tgl_diajukan']; ?></span>
    </div>
    <div style="margin:20px 0 0 53px">
      <span class="title">Kepada</span>
    </div>
    <div style="margin:5px 0 0 0; line-height: 23px;">
      <table style="border: none;">
        <tr style="border: none;">
          <td class="title" style="border:none; height:0px; vertical-align: top;">Yth.</td>
          <td style="border: none;">&ensp;<span><?= $dataCuti['unit_kerja_skema']; ?></span></td>
        </tr>
      </table>
    </div>
    <div style="margin:5px 0 0 55px">
      <span class="title">Di-</span>
    </div>
    <div style="margin:15px 0 45px 100px">
      <span class="title">Tempat</span>
    </div>
  </div>
  <div class="bag-2">
    <span class="judul">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</span>
  </div>
  <br />
  <div class="bag-seterusnya">
    <table>
      <tr>
        <th colspan="4">I.<span style="letter-spacing: 15px;">&nbsp;</span> DATA PEGAWAI</th>
      </tr>
      <tr>
        <td class="title" style="width: 87px;">Nama</td>
        <td style="width: 237px;">

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
        <td class="title" style="width: 87px;">NIP</td>
        <td style="width: 237px;"><?= $dataCuti['nip_pegawai']; ?></td>
      </tr>
      <tr>
        <td class="title">Jabatan</td>
        <td><?= $dataCuti['jabatan_pegawai']; ?></td>
        <td class="title">Masa Kerja</td>
        <td>
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
        <td colspan="3"><?= $dataCuti['unit_kerja_pegawai']; ?></td>
      </tr>
    </table>
  </div>
  <!-- ================================================================================== -->

  <div class="bag-seterusnya">
    <table>
      <tr>
        <th colspan="4">II.<span style="letter-spacing: 15px;">&nbsp;</span> JENIS CUTI YANG DIAMBIL</th>
      </tr>
      <tr>
        <td class="title" style="width:220px;">1.&ensp; Cuti Tahunan</td>
        <td style="width:65px;" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Tahunan' ? $dataCuti['ceklis'] : ''; ?></td>
        <td class="title" style="width:276px;">2.&ensp; Cuti Besar</td>
        <td style="width:87px;" class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Besar' ? $dataCuti['ceklis'] : ''; ?></td>
      </tr>
      <tr>
        <td class="title">3.&ensp; Cuti Sakit</td>
        <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Sakit' ? $dataCuti['ceklis'] : ''; ?></td>
        <td class="title">4.&ensp; Cuti Melahirkan</td>
        <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Melahirkan' ? $dataCuti['ceklis'] : ''; ?></td>
      </tr>
      <tr>
        <td class="title">5.&ensp; Cuti Karena Alasan Penting</td>
        <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti Karena Alasan Penting' ? $dataCuti['ceklis'] : ''; ?></td>
        <td class="title">6.&ensp; Cuti di Luar Tanggungan Negara</td>
        <td class="center ceklis"><?= $dataCuti['jenis_cuti'] == 'Cuti di Luar Tanggungan Negara' ? $dataCuti['ceklis'] : ''; ?></td>
      </tr>
    </table>
  </div>
  <!-- ================================================================================== -->

  <div class="bag-seterusnya">
    <table style="width:681px;">
      <tr>
        <th>III.<span style="letter-spacing: 15px;">&nbsp;</span> ALASAN CUTI</th>
      </tr>
      <tr>
        <td><?= $dataCuti['alasan_cuti']; ?></td>
      </tr>
    </table>
  </div>
  <!-- ================================================================================== -->

  <div class="bag-seterusnya">
    <table>
      <tr>
        <th colspan="6">IV.<span style="letter-spacing: 15px;">&nbsp;</span> LAMANYA CUTI</th>
      </tr>

      <?php
      $tanggal = explode("|", $dataCuti['total_hari']);
      $tanggal1 = explode("|", $dataCuti['periode1']);
      $tanggal2 = explode("|", $dataCuti['periode2']);
      for ($i = 0; $i < count($tanggal); $i++) : ?>
        <tr>
          <td class="title" style="width: 76px;">Selama</td>
          <td style="width: 163px;">
            <?php if ($tanggal[$i] < 30) : ?>
              <span class="d-inline-block"><?= $tanggal[$i]; ?> (hari/<strike>bulan/tahun</strike>)</span>
            <?php elseif ($tanggal[$i] >= 30 && $tanggal[$i] < 365) : ?>
              <span class="d-inline-block"><?= $tanggal[$i]; ?> (<strike>hari</strike>/bulan/<strike>tahun</strike>)</span>
            <?php else : ?>
              <span class="d-inline-block"><?= $tanggal[$i]; ?> (<strike>hari/bulan</strike>/tahun)</span>
            <?php endif; ?>
          </td>
          <td class="title" style="width:109px;">Mulai tanggal</td>
          <td style="width: 128px;"><?= $tanggal1[$i]; ?></td>
          <td class="title" style="width: 28px;">s/d</td>
          <td style="width: 128px;"><?= $tanggal2[$i]; ?></td>
        </tr>
      <?php endfor; ?>
    </table>
  </div>
  <!-- ================================================================================== -->

  <div class="bag-seterusnya">
    <table>
      <tr>
        <th colspan="5">V.<span style="letter-spacing: 15px;">&nbsp;</span> CATATAN CUTI</th>
      </tr>
      <tr>
        <td class="title" colspan="3" style="width: 261px;">1.&ensp; CUTI TAHUNAN</td>
        <td class="title" style="width: 307px;">2.&ensp; CUTI BESAR</td>
        <td style="width: 71px;"></td>
      </tr>
      <tr>
        <td class="title" style="width: 65px;">Tahun</td>
        <td class="title center" style="width:92px;">Sisa</td>
        <td class="title center" style="width:104px;">Keterangan</td>
        <td class="title">3.&ensp; CUTI SAKIT</td>
        <td></td>
      </tr>
      <tr>
        <td class="title">N-2</td>
        <td></td>
        <td></td>
        <td class="title">4.&ensp; CUTI MELAHIRKAN</td>
        <td></td>
      </tr>
      <tr>
        <td class="title">N-1</td>
        <td></td>
        <td></td>
        <td class="title">5.&ensp; CUTI KARENA ALASAN PENTING</td>
        <td></td>
      </tr>
      <tr>
        <td class="title">N</td>
        <td></td>
        <td></td>
        <td class="title">6.&ensp; CUTI DILUAR TANGGUNGAN NEGARA</td>
        <td></td>
      </tr>
    </table>
  </div>
  <!-- ================================================================================== -->

  <div class="bag-seterusnya">
    <table>
      <tr>
        <th colspan="3">VI.<span style="letter-spacing: 15px;">&nbsp;</span> ALAMAT SELAMA MENJALANKAN CUTI</th>
      </tr>
      <tr>
        <td style="width: 307px;"></td>
        <td class="title center" style="width: 63px;">Telp</td>
        <td style="width: 286px;"><?= $dataCuti['no_telp']; ?></td>
      </tr>
      <tr>
        <td style="height: 170px; vertical-align: top;"><?= $dataCuti['alamat_cuti']; ?></td>
        <td colspan="2">
          <div class="title center">Hormat saya</div>
          <div style="top:0; height: 85px; text-align:center; margin-left:20px;"> <img style="width:50%;" src="<?= $dataCuti['ttd_data_pegawai']; ?>" alt="TTD"></div>
          <div class="title center">(

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
            )</div>
          <div class="title center">NIP. <?= $dataCuti['nip_pegawai']; ?></div>
        </td>
      </tr>
    </table>
  </div>

  <!-- ================================================================================== -->
    <?php if ($dataCuti['jenis_cuti'] == "Cuti Karena Alasan Penting") : ?>
      <div class="bag-seterusnya">
        <table style="border: none;">
          <tr>
            <th colspan="4">VII.<span style="letter-spacing: 15px;">&nbsp;</span> PERTIMBANGAN ATASAN LANGSUNG</th>
          </tr>
          <tr>
            <td class="center" style="width: 162px;">DISETUJUI</td>
            <td class="center" style="width: 162px;">PERUBAHAN</td>
            <td class="center" style="width: 162px;">DITANGGUHKAN</td>
            <td class="center" style="width: 162px;">TIDAK DISETUJUI</td>
          </tr>
          <tr>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 2 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 1 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 3 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 4 ? $dataCuti['ceklis'] : ''; ?></td>
          </tr>
          <tr>
            <td style="height: 170px; border-style: none;" colspan="2"></td>
            <td colspan="2">
              <div class="title center"><?= $dataCuti['jabatan_pejabat']; ?></div>
              <div style="top:0; height: 85px; text-align:center; margin-left:20px;"> <?= $dataCuti['id_cuti_status_pejabat'] == 2 ? '<img style="width:50%;" src="' . $dataCuti['ttd_pejabat'] . '" alt="TTD">' : ''; ?></div>
              <div class="title center"><strong>
                  <?php
                  $str = $dataCuti['nama_pejabat'];
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
              <div class="title center">NIP. <?= $dataCuti['nip_pejabat']; ?></div>
            </td>
          </tr>
        </table>
      </div>
    <?php else : ?>
      <div class="bag-seterusnya">
        <table style="border: none;">
          <tr>
            <th colspan="4">VII.<span style="letter-spacing: 15px;">&nbsp;</span> PERTIMBANGAN ATASAN LANGSUNG</th>
          </tr>
          <tr>
            <td class="center" style="width: 162px;">DISETUJUI</td>
            <td class="center" style="width: 162px;">PERUBAHAN</td>
            <td class="center" style="width: 162px;">DITANGGUHKAN</td>
            <td class="center" style="width: 162px;">TIDAK DISETUJUI</td>
          </tr>
          <tr>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 2 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 1 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 3 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 4 ? $dataCuti['ceklis'] : ''; ?></td>
          </tr>
          <tr>
            <td style="height: 170px; border-style: none;" colspan="2"></td>
            <td colspan="2">
              <div class="title center"><?= $dataCuti['jabatan_atasan']; ?></div>
              <div style="top:0; height: 85px; text-align:center; margin-left:20px;"> <?= $dataCuti['id_cuti_status_atasan'] == 2 ? '<img style="width:50%;" src="' . $dataCuti['ttd_atasan'] . '" alt="TTD">' : ''; ?></div>
              <div class="title center"><strong>
                  <?php
                  $str = $dataCuti['nama_atasan'];
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
              <div class="title center">NIP. <?= $dataCuti['nip_atasan']; ?></div>
            </td>
          </tr>
        </table>
      </div>
    <?php endif; ?>

  <!-- ================================================================================== -->
  <?php if($dataCuti['id_status_pegawai'] != 4): ?>
    <?php if ($dataCuti['jenis_cuti'] == "Cuti Karena Alasan Penting") : ?>
      <div class="bag-seterusnya">
        <table style="border: none;">
          <tr>
            <th colspan="4">VIII.<span style="letter-spacing: 15px;">&nbsp;</span> KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</th>
          </tr>
          <tr>
            <td class="center" style="width: 162px;">DISETUJUI</td>
            <td class="center" style="width: 162px;">PERUBAHAN</td>
            <td class="center" style="width: 162px;">DITANGGUHKAN</td>
            <td class="center" style="width: 162px;">TIDAK DISETUJUI</td>
          </tr>
          <tr>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 2 ? '' : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 1 ? '' : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 3 ? '' : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_atasan'] == 4 ? '' : ''; ?></td>
          </tr>
          <tr>
            <td style="height: 170px; border-style: none;" colspan="2"></td>
            <td colspan="2">
              <div class="title center">Kepala Biro OSDM</div>
              <div style="top:0; height: 85px; text-align:center; margin-left:20px;"> </div>
              <div class="title center"><strong>
    
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
                  echo $jabatanAwal . ' ' . $nama . $jabatanAkhir;
                  ?>
                </strong></div>
              <div class="title center">NIP. <?= $dataCuti['osdm_nip']; ?></div>
            </td>
          </tr>
        </table>
      </div>
    <?php else : ?>
      <div class="bag-seterusnya">
        <table style="border: none;">
          <tr>
            <th colspan="4">VIII.<span style="letter-spacing: 15px;">&nbsp;</span> KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</th>
          </tr>
          <tr>
            <td class="center" style="width: 162px;">DISETUJUI</td>
            <td class="center" style="width: 162px;">PERUBAHAN</td>
            <td class="center" style="width: 162px;">DITANGGUHKAN</td>
            <td class="center" style="width: 162px;">TIDAK DISETUJUI</td>
          </tr>
          <tr>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 2 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 1 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 3 ? $dataCuti['ceklis'] : ''; ?></td>
            <td class="center"><?= $dataCuti['id_cuti_status_pejabat'] == 4 ? $dataCuti['ceklis'] : ''; ?></td>
          </tr>
          <tr>
            <td style="height: 170px; border-style: none;" colspan="2"></td>
            <td colspan="2">
              <div class="title center"><?= $dataCuti['jabatan_pejabat']; ?></div>
              <div style="top:0; height: 85px; text-align:center; margin-left:20px;"> <?= $dataCuti['id_cuti_status_pejabat'] == 2 ? '<img style="width:50%;" src="' . $dataCuti['ttd_pejabat'] . '" alt="TTD">' : ''; ?></div>
              <div class="title center"><strong>
    
                  <?php
                  $str = $dataCuti['nama_pejabat'];
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
              <div class="title center">NIP. <?= $dataCuti['nip_pejabat']; ?></div>
            </td>
          </tr>
        </table>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</body>

</html>