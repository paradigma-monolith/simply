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
  <h3 class="center">PENGAWASAN HYGIENE SANITASI BANGUNAN UMUM</h3>

  <table class="table-borderless">
    <tr>
      <td>Wilayah Kerja</td>
      <td>:</td>
      <td><?= $hsbu['wilayah_kerja']; ?></td>
    </tr>
    <tr>
      <td>Tanggal Pemeriksaan</td>
      <td>:</td>
      <td><?= date('d M Y', strtotime($hsbu['tanggal_inspeksi'])); ?></td>
    </tr>
    <tr>
      <td>Nama Gedung/Bangunan</td>
      <td>:</td>
      <td><?= $hsbu['nama_hsbu']; ?></td>
    </tr>
    <tr>
      <td>Petugas Pemeriksa</td>
      <td>:</td>
      <td><?= $hsbu['nama_pemeriksa']; ?></td>
    </tr>
    <tr>
      <td>Pemilik/Penanggung Jawab</td>
      <td>:</td>
      <td><?= $hsbu['penanggung_jawab_hsbu']; ?></td>
    </tr>
    <tr>
      <td>Jumlah Karyawan</td>
      <td>:</td>
      <td><?= $hsbu['jml_karyawan_hsbu']; ?></td>
    </tr>
  </table>

  <br>

  <table class="table-bordered">
    <tr>
      <th>No</th>
      <th>Variabel</th>
      <th>Bobot</th>
      <th>Komponen yang dinilai</th>
      <th>Nilai</th>
      <th>Skor</th>
      <th>Keterangan</th>
    </tr>
    <tr>
      <td class="center">1</td>
      <td class="center">2</td>
      <td class="center">3</td>
      <td class="center">4</td>
      <td class="center">5</td>
      <td class="center">6</td>
      <td class="center">7</td>
    </tr>
    <?php
    $total_nilai_lingkungan_luar_halaman = 0;
    $total_skor_lingkungan_luar_halaman = 0;
    foreach ($lingkungan_luar_halaman as $index => $item) :
      $total_nilai_lingkungan_luar_halaman += (float) $item['nilai_lingkungan_luar_halaman'];
      $total_skor_lingkungan_luar_halaman += (float) $item['skor_lingkungan_luar_halaman'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($lingkungan_luar_halaman) ?>">1</td>
          <td rowspan="<?= count($lingkungan_luar_halaman) ?>">Lingkungan Luar Halaman</td>
          <td class="center" rowspan="<?= count($lingkungan_luar_halaman) ?>">6</td>
        <?php endif; ?>
        <td><?= $item['lingkungan_luar_halaman'] ?></td>
        <td class="center"><?= $item['nilai_lingkungan_luar_halaman'] ?></td>
        <td class="center"><?= $item['skor_lingkungan_luar_halaman'] ?></td>
        <td class="center"><?= $item['keterangan_lingkungan_luar_halaman'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_ruang_bangunan = 0;
    $total_skor_ruang_bangunan = 0;
    foreach ($ruang_bangunan as $index => $item) :
      $total_nilai_ruang_bangunan += (float) $item['nilai_ruang_bangunan'];
      $total_skor_ruang_bangunan += (float) $item['skor_ruang_bangunan'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($ruang_bangunan) ?>">2</td>
          <td rowspan="<?= count($ruang_bangunan) ?>">Ruang Bangunan</td>
          <td class="center" rowspan="<?= count($ruang_bangunan) ?>">6</td>
        <?php endif; ?>
        <td><?= $item['ruang_bangunan'] ?></td>
        <td class="center"><?= $item['nilai_ruang_bangunan'] ?></td>
        <td class="center"><?= $item['skor_ruang_bangunan'] ?></td>
        <td class="center"><?= $item['keterangan_ruang_bangunan'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_penyehatan_air = 0;
    $total_skor_penyehatan_air = 0;
    foreach ($penyehatan_air as $index => $item) :
      $total_nilai_penyehatan_air += (float) $item['nilai_penyehatan_air'];
      $total_skor_penyehatan_air += (float) $item['skor_penyehatan_air'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($penyehatan_air) ?>">3</td>
          <td rowspan="<?= count($penyehatan_air) ?>">Penyehatan Air</td>
          <td class="center" rowspan="<?= count($penyehatan_air) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['penyehatan_air'] ?></td>
        <td class="center"><?= $item['nilai_penyehatan_air'] ?></td>
        <td class="center"><?= $item['skor_penyehatan_air'] ?></td>
        <td class="center"><?= $item['keterangan_penyehatan_air'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_penyehatan_ruang_udara = 0;
    $total_skor_penyehatan_ruang_udara = 0;
    foreach ($penyehatan_ruang_udara as $index => $item) :
      $total_nilai_penyehatan_ruang_udara += (float) $item['nilai_penyehatan_ruang_udara'];
      $total_skor_penyehatan_ruang_udara += (float) $item['skor_penyehatan_ruang_udara'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($penyehatan_ruang_udara) ?>">4</td>
          <td rowspan="<?= count($penyehatan_ruang_udara) ?>">Penyehatan Ruang Udara</td>
          <td class="center" rowspan="<?= count($penyehatan_ruang_udara) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['penyehatan_ruang_udara'] ?></td>
        <td class="center"><?= $item['nilai_penyehatan_ruang_udara'] ?></td>
        <td class="center"><?= $item['skor_penyehatan_ruang_udara'] ?></td>
        <td class="center"><?= $item['keterangan_penyehatan_ruang_udara'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_pengelolaan_limbah = 0;
    $total_skor_pengelolaan_limbah = 0;
    foreach ($pengelolaan_limbah as $index => $item) :
      $total_nilai_pengelolaan_limbah += (float) $item['nilai_pengelolaan_limbah'];
      $total_skor_pengelolaan_limbah += (float) $item['skor_pengelolaan_limbah'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($pengelolaan_limbah) ?>">5</td>
          <td rowspan="<?= count($pengelolaan_limbah) ?>">Pengelolaan Limbah</td>
          <td class="center" rowspan="<?= count($pengelolaan_limbah) ?>">15</td>
        <?php endif; ?>
        <td><?= $item['pengelolaan_limbah'] ?></td>
        <td class="center"><?= $item['nilai_pengelolaan_limbah'] ?></td>
        <td class="center"><?= $item['skor_pengelolaan_limbah'] ?></td>
        <td class="center"><?= $item['keterangan_pengelolaan_limbah'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_pencahayaan = 0;
    $total_skor_pencahayaan = 0;
    foreach ($pencahayaan as $index => $item) :
      $total_nilai_pencahayaan += (float) $item['nilai_pencahayaan'];
      $total_skor_pencahayaan += (float) $item['skor_pencahayaan'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($pencahayaan) ?>">6</td>
          <td rowspan="<?= count($pencahayaan) ?>">Pencahayaan</td>
          <td class="center" rowspan="<?= count($pencahayaan) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['pencahayaan'] ?></td>
        <td class="center"><?= $item['nilai_pencahayaan'] ?></td>
        <td class="center"><?= $item['skor_pencahayaan'] ?></td>
        <td class="center"><?= $item['keterangan_pencahayaan'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_kebisingan_pada_ruang = 0;
    $total_skor_kebisingan_pada_ruang = 0;
    foreach ($kebisingan_pada_ruang as $index => $item) :
      $total_nilai_kebisingan_pada_ruang += (float) $item['nilai_kebisingan_pada_ruang'];
      $total_skor_kebisingan_pada_ruang += (float) $item['skor_kebisingan_pada_ruang'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($kebisingan_pada_ruang) ?>">7</td>
          <td rowspan="<?= count($kebisingan_pada_ruang) ?>">Kebisingan Pada Ruang</td>
          <td class="center" rowspan="<?= count($kebisingan_pada_ruang) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['kebisingan_pada_ruang'] ?></td>
        <td class="center"><?= $item['nilai_kebisingan_pada_ruang'] ?></td>
        <td class="center"><?= $item['skor_kebisingan_pada_ruang'] ?></td>
        <td class="center"><?= $item['keterangan_kebisingan_pada_ruang'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_getaran_di_ruang_kerja = 0;
    $total_skor_getaran_di_ruang_kerja = 0;
    foreach ($getaran_di_ruang_kerja as $index => $item) :
      $total_nilai_getaran_di_ruang_kerja += (float) $item['nilai_getaran_di_ruang_kerja'];
      $total_skor_getaran_di_ruang_kerja += (float) $item['skor_getaran_di_ruang_kerja'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($getaran_di_ruang_kerja) ?>">8</td>
          <td rowspan="<?= count($getaran_di_ruang_kerja) ?>">Getaran di Ruang Kerja</td>
          <td class="center" rowspan="<?= count($getaran_di_ruang_kerja) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['getaran_di_ruang_kerja'] ?></td>
        <td class="center"><?= $item['nilai_getaran_di_ruang_kerja'] ?></td>
        <td class="center"><?= $item['skor_getaran_di_ruang_kerja'] ?></td>
        <td class="center"><?= $item['keterangan_getaran_di_ruang_kerja'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_pengendalian_vektor_penyakit = 0;
    $total_skor_pengendalian_vektor_penyakit = 0;
    foreach ($pengendalian_vektor_penyakit as $index => $item) :
      $total_nilai_pengendalian_vektor_penyakit += (float) $item['nilai_pengendalian_vektor_penyakit'];
      $total_skor_pengendalian_vektor_penyakit += (float) $item['skor_pengendalian_vektor_penyakit'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($pengendalian_vektor_penyakit) ?>">9</td>
          <td rowspan="<?= count($pengendalian_vektor_penyakit) ?>">Pengendalian Vektor Penyakit</td>
          <td class="center" rowspan="<?= count($pengendalian_vektor_penyakit) ?>">8</td>
        <?php endif; ?>
        <td><?= $item['pengendalian_vektor_penyakit'] ?></td>
        <td class="center"><?= $item['nilai_pengendalian_vektor_penyakit'] ?></td>
        <td class="center"><?= $item['skor_pengendalian_vektor_penyakit'] ?></td>
        <td class="center"><?= $item['keterangan_pengendalian_vektor_penyakit'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_instalasi = 0;
    $total_skor_instalasi = 0;
    foreach ($instalasi as $index => $item) :
      $total_nilai_instalasi += (float) $item['nilai_instalasi'];
      $total_skor_instalasi += (float) $item['skor_instalasi'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($instalasi) ?>">10</td>
          <td rowspan="<?= count($instalasi) ?>">Instalasi</td>
          <td class="center" rowspan="<?= count($instalasi) ?>">5</td>
        <?php endif; ?>
        <td><?= $item['instalasi'] ?></td>
        <td class="center"><?= $item['nilai_instalasi'] ?></td>
        <td class="center"><?= $item['skor_instalasi'] ?></td>
        <td class="center"><?= $item['keterangan_instalasi'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $total_nilai_pemeliharaan_jamban_dan_kamar_mandi = 0;
    $total_skor_pemeliharaan_jamban_dan_kamar_mandi = 0;
    foreach ($pemeliharaan_jamban_dan_kamar_mandi as $index => $item) :
      $total_nilai_pemeliharaan_jamban_dan_kamar_mandi += (float) $item['nilai_pemeliharaan_jamban_dan_kamar_mandi'];
      $total_skor_pemeliharaan_jamban_dan_kamar_mandi += (float) $item['skor_pemeliharaan_jamban_dan_kamar_mandi'];
    ?>
      <tr>
        <?php if ($index == 0) : ?>
          <td class="center" rowspan="<?= count($pemeliharaan_jamban_dan_kamar_mandi) ?>">11</td>
          <td rowspan="<?= count($pemeliharaan_jamban_dan_kamar_mandi) ?>">Pemeliharaan Jamban dan Kamar Mandi</td>
          <td class="center" rowspan="<?= count($pemeliharaan_jamban_dan_kamar_mandi) ?>">10</td>
        <?php endif; ?>
        <td><?= $item['pemeliharaan_jamban_dan_kamar_mandi'] ?></td>
        <td class="center"><?= $item['nilai_pemeliharaan_jamban_dan_kamar_mandi'] ?></td>
        <td class="center"><?= $item['skor_pemeliharaan_jamban_dan_kamar_mandi'] ?></td>
        <td class="center"><?= $item['keterangan_pemeliharaan_jamban_dan_kamar_mandi'] ?></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <?php
      $total_nilai = $total_nilai_lingkungan_luar_halaman
        + $total_nilai_ruang_bangunan
        + $total_nilai_penyehatan_air
        + $total_nilai_penyehatan_ruang_udara
        + $total_nilai_pengelolaan_limbah
        + $total_nilai_pencahayaan
        + $total_nilai_kebisingan_pada_ruang
        + $total_nilai_getaran_di_ruang_kerja
        + $total_nilai_pengendalian_vektor_penyakit
        + $total_nilai_instalasi
        + $total_nilai_pemeliharaan_jamban_dan_kamar_mandi;
      $total_skor = $total_skor_lingkungan_luar_halaman
        + $total_skor_ruang_bangunan
        + $total_skor_penyehatan_air
        + $total_skor_penyehatan_ruang_udara
        + $total_skor_pengelolaan_limbah
        + $total_skor_pencahayaan
        + $total_skor_kebisingan_pada_ruang
        + $total_skor_getaran_di_ruang_kerja
        + $total_skor_pengendalian_vektor_penyakit
        + $total_skor_instalasi
        + $total_skor_pemeliharaan_jamban_dan_kamar_mandi;
      ?>
      <td class="center" colspan="4">Total</td>
      <td class="center"><?= $total_nilai; ?></td>
      <td class="center"><?= $total_skor; ?></td>
      <td></td>
    </tr>
    <tr>
      <td class="center" colspan="4">Kategori</td>
      <td class="center" colspan="3">
        <?php
        $totalSkor = $hsbu['total'];
        if ($totalSkor > 712.5) {
          echo $keterangan = 'Rendah (R)';
        } else if ($totalSkor > 475 && $totalSkor < 712.5) {
          echo $keterangan = 'Sedang (S)';
        } else {
          echo $keterangan = 'Tinggi (T)';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">Hasil</td>
      <td colspan="5"><?= $hsbu['total']; ?></td>
    </tr>
    <tr>
      <td colspan="2">Saran dan Rencana Tindak Lanjut</td>
      <td colspan="5"><?= $hsbu['saran_rencana_tindakan']; ?></td>
    </tr>
  </table>
</body>

</html>