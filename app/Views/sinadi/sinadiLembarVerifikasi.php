<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lembar Verifikasi Keuangan</title>
  <style>
    @page {
      margin: 20mm 15mm 20mm 15mm; /* Atur sesuai kebutuhan: atas, kanan, bawah, kiri */
    }
    body {
      font-family: Arial, sans-serif;
      font-size: 10pt;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td, th {
      border: 1px solid #000;
      padding: 4px;
      vertical-align: top;
    }
    .center {
      text-align: center;
    }
    .right {
      text-align: right;
    }
    .bold {
      font-weight: bold;
    }
    .no-border {
      border: none;
    }
    .signature {
      height: 60px;
      width: auto;
    }
    .section-title {
      background-color: #f2f2f2;
    }
    .wrap-text {
      white-space: pre-line;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <table>
    <tr>
      <td colspan="9" class="center bold no-border" style="font-size: 14pt;">LEMBAR VERIFIKASI KEUANGAN</td>
    </tr>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
  </table>

  <!-- Info Tagihan -->
  <table>
    <tr>
      <td class="center" style="width: 3%;">1</td>
      <td style="width: 32%;">NO. SPBY/TANGGAL</td>
      <td style="width: 1%;">:</td>
      <td colspan="3"><?= $get['no_spby'] ?></td>
      <td class="center">tgl. <?= mediumdate_indo($get['tgl']) ?></td>
    </tr>
    <tr>
      <td class="center">2</td>
      <td>NAMA SUPPLIER</td>
      <td>:</td>
      <td colspan="4"><?= implode(', ', array_map('name_capitalize', unserialize($get['suplier']))) ?></td>
    </tr>
    <tr>
      <td class="center">3</td>
      <td>JENIS TAGIHAN/SUMBER DANA</td>
      <td>:</td>
      <td colspan="4"><?= $get['tagihan'] ?></td>
    </tr>
    <tr>
      <td class="center">4</td>
      <td>SUMBER ANGGARAN</td>
      <td>:</td>
      <td colspan="4"><?= isset($get['sumber_anggaran']) ? $get['sumber_anggaran'] : 'APBN' ?></td>
    </tr>
    <tr>
      <td class="center">5</td>
      <td>MAK</td>
      <td>:</td>
      <td colspan="4"><?= $get['mata_anggaran'] ?></td>
    </tr>
    <tr>
      <td class="center">6</td>
      <td>URAIAN KEGIATAN</td>
      <td>:</td>
      <td colspan="4"><?= $get['uraian'] ?></td>
    </tr>
    <tr>
      <td class="center">7</td>
      <td>NILAI TAGIHAN</td>
      <td>:</td>
      <td colspan="4">Rp <?= number_format($get['total_tagihan'], 2, ',', '.') ?></td>
    </tr>
  </table>

  <!-- Kelengkapan Dokumen -->
  <br>
  <table>
    <tr>
      <td colspan="2" class="center bold">KELENGKAPAN DATA DUKUNG</td>
    </tr>
    <tr>
      <td colspan="2" class="center bold">Daftar Periksa</td>
    </tr>

    <!-- Perjalanan Dinas -->
    <?php if ($get['kota_asal'] == $get['kota']): ?>
      <tr>
          <td colspan="2" class="bold section-title">PERJALANAN DINAS DALAM KOTA/TRANSLOK</td>
      </tr>
    <?php else: ?>
      <tr>
          <td colspan="2" class="bold section-title">PERJALANAN DINAS LUAR KOTA</td>
      </tr>
    <?php endif; ?>
    
    <?php foreach (unserialize($get['daftar_periksa']) as $d): ?>
        <tr>
            <td>- <?= $d['list'] ?></td>
            <td class="center"><?= $d['value'] == 'checked' ? $checklist : '' ?></td>
            </tr>
    <?php endforeach; ?>
  </table>

  <!-- Tanda Tangan -->
  <br>
  <table>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
    <tr>
      <td colspan="9" class="no-border"></td>
    </tr>
    <tr>
      <td colspan="7" class="no-border" style="width: 300px;"></td>
      <td colspan="2" class="center no-border">
        Bandung, <?= mediumdate_indo($get['tgl']) ?><br>
        Pemeriksa<br>
        <?php if (isset($ttd_verif)): ?>
          <img src="<?= $ttd_verif ?>" alt="Tanda Tangan" class="signature"><br>
        <?php else: ?>
        <br><br><br>
        <?php endif; ?>
        <?= name_capitalize($by['nama']) ?><br>
        NIP. <?= $by['nip'] ?>
      </td>
    </tr>
  </table>

</body>
</html>