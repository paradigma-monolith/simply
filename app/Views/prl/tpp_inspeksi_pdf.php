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
  <h3 class="center">PENGAWASAN HIGIENE SANITASI PANGAN BERBASIS RISIKO</h3>

  <table class="table-borderless">
    <tr>
      <td>Wilayah Kerja</td>
      <td>:</td>
      <td><?= $tpp['wilayah_kerja']; ?></td>
    </tr>
    <tr>
      <td>Tanggal Pemeriksaan</td>
      <td>:</td>
      <td><?= date('d M Y', strtotime($tpp['tanggal_inspeksi'])); ?></td>
    </tr>
    <tr>
      <td>Nama TPP</td>
      <td>:</td>
      <td><?= $tpp['nama_tpp']; ?></td>
    </tr>
    <tr>
      <td>Petugas Pemeriksa</td>
      <td>:</td>
      <td><?= $tpp['nama_pemeriksa']; ?></td>
    </tr>
    <tr>
      <td>Pemilik/Penanggung Jawab</td>
      <td>:</td>
      <td><?= $tpp['penanggung_jawab_tpp']; ?></td>
    </tr>
    <tr>
      <td>Jumlah Karyawan</td>
      <td>:</td>
      <td><?= $tpp['jml_karyawan_tpp']; ?></td>
    </tr>
  </table>

  <br>

  <table class="table-bordered">
    <tr>
      <th class="center" rowspan="2">No</th>
      <th class="center" rowspan="2">Yang Diawasi</th>
      <th class="center" colspan="3">Penilaian</th>
      <th class="center" rowspan="2">Skor</th>
      <th class="center" rowspan="2">Keterangan</th>
    </tr>
    <tr class="center">
      <th class="center">Keterangan</th>
      <th class="center" style="width: 50px;">Ya</th>
      <th class="center" style="width: 50px;">Tidak</th>
    </tr>
    <tr>
      <td class="center" rowspan="3">A</td>
      <td rowspan="3">Profil Pangan</td>
      <td>1. Apakah pangan memiliki pH < 4,6 atau Aw < 0,85? Ataukan pangan mengandung gula tinggi?</td>
      <td class="center"><?= $tpp['sec_a_satu'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_a_satu'] == 'Tidak' ? '✓' : ''; ?></td>
      <td class="center" rowspan="3"><?= $tpp['sec_a_skor']; ?></td>
      <td class="center" rowspan="3">
        <?php
        $skor = $tpp['sec_a_skor'];
        if ($skor = 5) {
          echo $kategori = 'Risiko Rendah';
        } else if ($skor = 10) {
          echo $kategori =  'Risiko Sedang';
        } else if ($skor = 15) {
          echo $kategori =  'Risiko Tinggi';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>2. Apakah pangan termasuk dalam kategori pangan PHF?</td>
      <td class="center"><?= $tpp['sec_a_dua'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_a_dua'] == 'Tidak' ? '✓' : ''; ?></td>
    </tr>
    <tr>
      <td>3. Apakah pH produk merupakan kombinasi pH > 4,6 dan Aw > 0,85?</td>
      <td class="center"><?= $tpp['sec_a_tiga'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_a_tiga'] == 'Tidak' ? '✓' : ''; ?></td>
    </tr>
    <tr>
      <td class="center" rowspan="3">B</td>
      <td rowspan="3">Bahaya Pangan</td>
      <td>1. Apakah pangan dikonsumsi dalam kondisi mentah atau sebelum dikonsumsi pangan tidak mengalami proses yang menghilangkan bahaya?</td>
      <td class="center"><?= $tpp['sec_b_satu'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_b_satu'] == 'Tidak' ? '✓' : ''; ?></td>
      <td class="center" rowspan="3"><?= $tpp['sec_b_skor']; ?></td>
      <td class="center" rowspan="3">
        <?php
        $skor = $tpp['sec_b_skor'];
        if ($skor = 5) {
          echo $kategori = 'Risiko Rendah';
        } else if ($skor = 10) {
          echo $kategori =  'Risiko Sedang';
        } else if ($skor = 15) {
          echo $kategori =  'Risiko Tinggi';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>2. Apakah pangan langsung dikonsumsi setelah dimasak/diolah? Atau disimpan pada suhu danger zone maksimal 4 jam setelah dimasak? Atau apakah pangan dilakukan mitigasi yang signifikan untuk menghilangkan bahaya (contoh: sterilisasi, pengawetan).</td>
      <td class="center"><?= $tpp['sec_b_dua'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_b_dua'] == 'Tidak' ? '✓' : ''; ?></td>
    </tr>
    <tr>
      <td>3. Apakah pangan dikemas tertutup rapat dan disimpan dengan baik untuk menghindari kontaminasi atau timbulnya bahaya?</td>
      <td class="center"><?= $tpp['sec_b_tiga'] == 'Ya' ? '✓' : ''; ?></td>
      <td class="center"><?= $tpp['sec_b_tiga'] == 'Tidak' ? '✓' : ''; ?></td>
    </tr>
    <tr>
      <td class="center">C</td>
      <td>Penilaian Skor Ukuran Bisnis</td>
      <td colspan="3">
        Jumlah pekerja: <?= $tpp['jml_karyawan_tpp']; ?><br>
        Kapasitas produksi: <?= $tpp['kapasitas_produksi_tpp']; ?><br>
      </td>
      <td class="center"><?= $tpp['sec_c_skor']; ?></td>
      <td class="center"><?= $tpp['sec_c_keterangan']; ?></td>
    </tr>
    <tr>
      <td class="center">D</td>
      <td>Riwayat Ketidaksesuaian</td>
      <td colspan="3">Nilai inspeksi sebelumnya: <?= $tpp['sec_d_nilai_inspeksi_sebelumnya']; ?></td>
      <td class="center"><?= $tpp['sec_d_skor']; ?></td>
      <td class="center">
        <?php
        $skor = $tpp['sec_d_skor'];
        if ($skor = 5) {
          echo $kategori = 'Risiko Rendah';
        } else if ($skor = 10) {
          echo $kategori =  'Risiko Sedang';
        } else if ($skor = 15) {
          echo $kategori =  'Risiko Tinggi';
        }
        ?>
      </td>
    </tr>
  </table>

  <br>

  <table class="table-borderless">
    <tr>
      <td>Kesimpulan</td>
      <td>:</td>
      <td><?= $tpp['kesimpulan']; ?></td>
    </tr>
    <tr>
      <td>Makanan yang diproses</td>
      <td>:</td>
      <td><?= $tpp['makanan']; ?></td>
    </tr>
    <tr>
      <td>Hasil</td>
      <td>:</td>
      <td><?= $tpp['hasil']; ?></td>
    </tr>
    <tr>
      <td>Kategori TPP</td>
      <td>:</td>
      <td>
        <?php
        $hasil = $tpp['hasil'];
        if ($hasil < 35) {
          echo $kategori_risiko = 'Risiko Rendah';
        } else if ($hasil >= 35 && $hasil <= 50) {
          echo $kategori_risiko =  'Risiko Sedang';
        } else if ($hasil >= 55) {
          echo $kategori_risiko =  'Risiko Tinggi';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Saran dan Rencana Tindakan</td>
      <td>:</td>
      <td><?= $tpp['saran_rencana_tindakan']; ?></td>
    </tr>
  </table>
</body>

</html>