<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: "DejaVu Sans", sans-serif;
      font-size: 12px;
    }

    .table-borderless td {
      padding: 5px;
    }

    .table-bordered {
      width: 100%;
      border-collapse: collapse;
    }

    .table-bordered th {
      background-color: #dddddd;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid black;
      padding: 5px;
    }

    .center {
      text-align: center;
    }
  </style>
</head>

<body>
  <h3 class="center">PENGAWASAN SANITASI SARANA AIR BERSIH</h3>

  <table class="table-borderless">
    <tr>
      <td>Wilayah Kerja</td>
      <td>:</td>
      <td><?= $sab['wilayah_kerja']; ?></td>
    </tr>
    <tr>
      <td>Tanggal Pemeriksaan</td>
      <td>:</td>
      <td><?= date('d M Y', strtotime($sab['tanggal_inspeksi'])); ?></td>
    </tr>
    <tr>
      <td>Nama SAB</td>
      <td>:</td>
      <td><?= $sab['nama_sab']; ?></td>
    </tr>
    <tr>
      <td>Petugas Pemeriksa</td>
      <td>:</td>
      <td><?= $sab['nama_pemeriksa']; ?></td>
    </tr>
    <tr>
      <td>Pemilik/Penanggung Jawab</td>
      <td>:</td>
      <td><?= $sab['penanggung_jawab_sab']; ?></td>
    </tr>
    <tr>
      <td>Sumber AIR</td>
      <td>:</td>
      <td><?= $sab['sumber_air_sab']; ?></td>
    </tr>
    <tr>
      <td>Kapasitas Penampungan</td>
      <td>:</td>
      <td><?= $sab['kapasitas_penampungan_sab']; ?> L</td>
    </tr>
  </table>

  <br>

  <table class="table-bordered">
    <tr>
      <th>No</th>
      <th colspan="1">Variabel Pemeriksaan</th>
      <th>Isian</th>
    </tr>
    <tr>
      <td class="center">A</td>
      <td colspan="2">Data Umum</td>
    </tr>
    <tr>
      <td></td>
      <td>1. Penanggung Jawab</td>
      <td class="center"><?= $sab['penanggung_jawab_sab']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>2. Sumber Air</td>
      <td class="center"><?= $sab['sumber_air_sab']; ?></td>
    </tr>
    <tr>
      <td class="center">B</td>
      <td colspan="2">Kualitas Fisik</td>
    </tr>
    <tr>
      <td></td>
      <td>1. Keruh</td>
      <td class="center"><?= $sab['sec_b_satu']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>2. Bau</td>
      <td class="center"><?= $sab['sec_b_dua']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>3. Rasa</td>
      <td class="center"><?= $sab['sec_b_tiga']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>4. Warna</td>
      <td class="center"><?= $sab['sec_b_empat']; ?></td>
    </tr>
    <tr>
      <td class="center">C</td>
      <td colspan="2">Penilaian Risiko</td>
    </tr>
    <tr>
      <td></td>
      <td>1. Apakah penampungan air ada kebocoran?</td>
      <td class="center"><?= $sab['sec_c_satu']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>2. Apakah penampungan air terbuka?</td>
      <td class="center"><?= $sab['sec_c_dua']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>3. Apakah Penampungan terbuat dari bahan mudah korosi, tidak kuat, dan sulit dibersihkan?</td>
      <td class="center"><?= $sab['sec_c_tiga']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>4. Apakah tutup manhole-nya kotor?</td>
      <td class="center"><?= $sab['sec_c_empat']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>5. Apakah lokasi sekitar dalam keadaan kotor?</td>
      <td class="center"><?= $sab['sec_c_lima']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>6. Apakah terdapat sumber pencemaran (kotoran manusia / hewan, sampah, dsb)?</td>
      <td class="center"><?= $sab['sec_c_enam']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>7. Apakah menjadi sarang vektor?</td>
      <td class="center"><?= $sab['sec_c_tujuh']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>8. Apakah hewan atau tikus dapat masuk ke dalam penampungan air?</td>
      <td class="center"><?= $sab['sec_c_delapan']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>9. Apakah ada kebocoran pada sistim distribusi?</td>
      <td class="center"><?= $sab['sec_c_sembilan']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td>10. Apakah ada keran yang bocor?</td>
      <td class="center"><?= $sab['sec_c_sepuluh']; ?></td>
      </td>
    </tr>
  </table>

  <br>

  <table class="table-borderless">
    <tr>
    <tr>
      <td>Hasil</td>
      <td>:</td>
      <td><?= $sab['hasil']; ?></td>
    </tr>
    <tr>
      <td>Kategori TPP</td>
      <td>:</td>
      <td>
        <?php
        $hasil = $sab['hasil'];
        if ($hasil < 25) {
          $riskon = "Risiko Rendah";
        } else if ($hasil >= 25 && $hasil <= 50) {
          $riskon = "Risiko Sedang";
        } else if ($hasil >= 51 && $hasil <= 57) {
          $riskon = "Risiko Tinggi";
        } else {
          $riskon = "Risiko Tinggi";
        }
        echo $riskon;
        ?>
      </td>
    </tr>
    <tr>
      <td>Saran dan Rencana Tindakan</td>
      <td>:</td>
      <td><?= $sab['saran_rencana_tindakan']; ?></td>
    </tr>
  </table>
</body>

</html>