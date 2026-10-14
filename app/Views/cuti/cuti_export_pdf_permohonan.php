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
      font-family: 'Time New Roman';
      font-size: 12pt;
    }

    table,
    th,
    td {
      text-align: left;
      font-weight: normal;
      /* border: 1px solid black; */
      border-collapse: collapse;
      /* width: 100%; */
    }

    body {
      margin: 113px;
    }

    table {
      border-collapse: collapse;
    }


    table td.title {
      font-style: italic;
    }

    .judul {
      text-align: center;
    }

    .center {
      text-align: center;
    }

    div.bag-1 {
      margin-bottom: 35px;
      width: 90%;
      margin-left: 5%;
      text-align: center;
    }

    div.bag-seterusnya {
      margin-bottom: 18px;
      width: 100%;
    }

    .upExpand {
      /* letter-spacing: 1px; */
      border-top: 1px dotted #000;
      text-decoration: none;
    }

    .expand {
      /* letter-spacing: 1px; */
      border-bottom: 1px dotted #000;
      text-decoration: none;

      /* text-decoration:underline;
      text-decoration-style: dotted; */
    }

    .justify {
      width: 530px;
      text-align: justify;
      text-justify: inter-word;
    }

    u.dotted {
      text-align: justify;
      text-justify: inter-word;
      border-bottom: 1px dotted #000;
      text-decoration: none;
    }

    .u-pertama {
      border: none;
      border-bottom: 1px solid;
      padding-bottom: 2px;
    }

    .u-kedua {
      border: none;
      border-top: 1px solid;
      padding-top: 1.3px;
      /* margin-top: -2px; */
      /* memindahkan elemen ke atas sehingga kedua garis border menyatu */
    }

    .u-kedua.no-strong {
      padding-top: 2.1px;
    }
  </style>
</head>

<body>
  <div class="bag-1">
    <span class="judul">SURAT PERMOHONAN IZIN / PEMBERITAHUAN</span><br />
    <div class="expand" style="width: 370px; margin: auto;"><span class="judul">&nbsp;</span></div>
    <span class="judul">(TIDAK MASUK KERJA/TERLAMBAT MASUK KERJA/PULANG SEBELUM WAKTUNYA/TIDAK BERADA DI TEMPAT TUGAS/TIDAK MELAKUKAN REKAM KEHADIRAN)</span>
    DI LINGKUNGAN KEMENTERIAN KESEHATAN
  </div>
  <div class="bag-seterusnya">
    <table border="0">
      <tr>
        <th colspan="3" style="width: 450px;">Yang bertanda tangan di bawah ini:</th>
      </tr>
      <tr>
        <td style="width: 145px;">Nama</td>
        <td style="width: 40px;">:</td>
        <td style="width: 280px;" class="expand">
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
      </tr>
      <tr>
        <td style="width: 145px;">NIP</td>
        <td style="width: 40px;">:</td>
        <td class="expand"><?= $dataCuti['nip_pegawai']; ?></td>
      </tr>
      <tr>
        <td style="width: 145px;">Pangkat/Gol.Ruang</td>
        <td style="width: 40px;">:</td>
        <td class="expand"><?= $dataCuti['golongan']; ?></td>
      </tr>
      <tr>
        <td style="width: 145px;">Jabatan</td>
        <td style="width: 40px;">:</td>
        <td class="expand"><?= $dataCuti['jabatan']; ?></td>
      </tr>
    </table>
  </div>
  <div class="bag-seterusnya">
    <div class="justify">Dengan ini menerangkan bahwa pada hari
      <p class="expand" style="text-align: center; width: 90px; height: 16px; display:inline-block;"><?= $dataCuti['tgl_diajukan']; ?></p> tanggal <div class="expand" style="display:inline-block; text-align:center; width: 140px; height: 16px;"><?= $dataCuti['periode']; ?></div style="display:inline-block;">
      <?= $dataCuti['jenis_permohonan_cek'] == 'saya tidak masuk kerja' ? 'saya tidak masuk kerja' : '<strike>saya tidak masuk kerja</strike>'; ?>/
      <?= $dataCuti['jenis_permohonan_cek'] == 'terlambat masuk kerja' ? 'terlambat masuk kerja' : '<strike>terlambat masuk kerja</strike>'; ?>/
      <?= $dataCuti['jenis_permohonan_cek'] == 'pulang sebelum waktunya' ? 'pulang sebelum waktunya' : '<strike>pulang sebelum waktunya</strike>'; ?>/
      <?= $dataCuti['jenis_permohonan_cek'] == 'tidak berada di tempat tugas' ? 'tidak berada di tempat tugas' : '<strike>tidak berada di tempat tugas</strike>'; ?>/
      <?= $dataCuti['jenis_permohonan_cek'] == 'tidak melakukan rekam kehadiran' ? 'tidak melakukan rekam kehadiran' : '<strike>tidak melakukan rekam kehadiran</strike>'; ?>
      *) karena
    </div>
    <div class="expand justify" style="margin-top: 5px;">
      <u class="dotted">
        <?= $dataCuti['alasan_permohonan']; ?>
      </u>
    </div>
    <div class="expand justify">&nbsp;</div>
  </div>
  <div class="bag-seterusnya" style="margin-top: 40px;">
    <table style="margin-left: -15px;">
      <tr>
        <td style="width: 278px; text-align: left;">
          Persetujuan Atasan Langsung,
        </td>
        <!-- <td style="width: auto;"></td> -->
        <td style="width: 278px; text-align: right;"><u><?= $dataCuti['lokasi_skema']; ?>, <?= $dataCuti['periode']; ?></u></td>
      </tr>
      <tr>
        <td style="height: 50px;">
            <?= $dataCuti['id_cuti_status_atasan'] == 2 ? '<img style="width:50%;" src="' . $dataCuti['ttd_atasan'] . '" alt="TTD">' : $dataCuti['ttd_blank'] ; ?>
        </td>
        <!-- <td></td> -->
        <td>
          <img style="width:50%;" src="<?= $dataCuti['ttd_data_pegawai']; ?>" alt="TTD" align="right">
        </td>
      </tr>
      <tr>
        <td class=""><strong>
            <span class="u-pertama">
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
              ?></span>
          </strong></td>
        <!-- <td></td> -->
        <td class="" style="text-align: right;">
          <span class="u-pertama">
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
          </span>
        </td>
      </tr>
      <tr>
        <td> <span class="u-kedua"><strong>NIP.<?= $dataCuti['nip_atasan']; ?></strong></span></td>
        <!-- <td></td> -->
        <td style="text-align: right;"> <span class="u-kedua no-strong">NIP/NOPEG.<?= $dataCuti['nip'];  ?></span></td>
      </tr>
      <tr>
        <td style="padding-top:7px;">*) coret yang tidak perlu</td>
        <!-- <td></td> -->
        <td></td>
      </tr>
    </table>
  </div>
  <!-- ================================================================================== -->

</body>

</html>