<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    table {
      border-collapse: collapse;
    }

    @page {
      margin: 0;
    }

    * {
      padding: 0;
      margin: 0;
      font-size: 8pt;
      font-family: "source_sans_proregular, sans-serif";
    }

    @font-face {
      font-family: "source_sans_proregular, sans-serif";
      src: local("Arial"), url("/fonts/sourcesans/arial.ttf") format("truetype");
      font-weight: normal;
      font-style: normal;

    }

    body {
      border: unset;
      margin: 45px;
    }

    .text-center {
      text-align: center;
    }

    .uppercase {
      text-transform: uppercase;
    }

    .bold {
      font-weight: bold;
    }

    .container {
      width: 100%;
      overflow: hidden;
      /* Untuk mengatasi float */
    }

    .float-left {
      float: left;
    }

    .float-right {
      float: right;
    }

    .clear {
      clear: both;
      /* Untuk mengatasi float dan menjaga konten di bawahnya */
    }

    .page-break {
      page-break-after: always;
    }

    .dotted-underline {
      border-bottom: 1px dotted;
      text-decoration: none;
      border-color: black;
      text-align: justify;
    }

    .mt-50 {
      margin-top: 50px;
    }

    .mx-50 {
      margin-right: 50px;
      margin-left: 50px;
    }

    .mx-40 {
      margin-right: 40px;
      margin-left: 40px;
    }

    .mt-20 {
      margin-top: 20px;
    }

    .mt-10 {
      margin-top: 10px;
    }

    .mt-5 {
      padding-top: 5px;
    }

    .px-50 {
      padding-right: 50px;
      padding-left: 50px;
    }

    .px-40 {
      padding-right: 40px;
      padding-left: 40px;
    }

    .pt-20 {
      padding-top: 20px;
    }

    .pt-10 {
      padding-top: 10px;
    }

    .pt-5 {
      padding-top: 5px;
    }

    hr {
      border: none;
      height: 3px;
      background-color: #000;
      /* box-shadow: 0 6px 12px rgba(0, 0, 0, 0.8); */
    }

    hr.child {
      margin-top: 1.8px;
      border: none;
      height: 1px;
      background-color: rgba(0, 0, 0, 0.8);
    }

    .checkbox {
      position: relative;
      border: #000 2px solid;
      display: inline-block;
      width: 10px;
      height: 10px;
      margin: 0 10px;
      margin-top: 5px;
      top: 0;
      display: inline-flex;
    }

    .label-checkbox {
      display: inline-flex;
      font-size: 13pt;
      vertical-align: middle;
    }

    .image-checklist img {
      width: 15px !important;
    }

    .dotted-underline {
      border-bottom: dotted 1px #000;
    }

    .strong {
      font-weight: 700;
    }

    table td {
      vertical-align: bottom;
      height: 18px;
    }

    .text-red {
      color: red;
    }

    .bordered {
      border-width: 1px;
      border-color: #000;
      border-collapse: solid;
      margin-left: auto;
      margin-right: auto;
    }

    .text-right {
      text-align: right;
    }

    /* Untuk elemen <sup> (superscript) */
    sup {
      font-size: 0.7em;
      vertical-align: super;
      color: #555;
    }

    small {
      font-size: 0.8em;
      color: #888;
      font-weight: normal;
    }

    .pl-150 {
      padding-left: 150px;
    }

    .expand-line {
      height: 32px;
    }

    .text-center.dotted-underline {
      text-align: center;
    }

    table td {
      /* border: solid !important; */
    }
  </style>
</head>

<body>
  <div style="padding: 0; width: 100%; margin-top: -40px;">
    <img src="<?= $kop; ?>" style="width: 100%;">
  </div>
  <div>
      <!--$pemkes || $faktorRisikoPTM || $skriningTBC || $skriningHIV || $laboratorium-->
    <?php if (isset($pemkes) || isset($pasien)): ?>
      <!-- <div> -->
      <div class="uppercase text-center"><strong style="font-size: 11pt;">FORMULIR PEMERIKSAAN KESEHATAN TERINTEGRASI</strong></div>
      <div class="uppercase text-center"><strong style="font-size: 11pt;"><?= isset($profil_kantor['nama_kantor']) ? $profil_kantor['nama_kantor'] : 'KEMENTERIAN KESEHATAN RI'; ?></strong></div>


      <div class="mt-20 mx-40">
        <table style="width: 100%;" border="0">
          <tr>
            <td colspan="3">Tempat</td>
            <td style="width: 1%;">:</td>
            <td style="width: 59%;" class="dotted-underline"><?= isset($pemkes['kota']) ? $pemkes['kota'] : (isset($pasien['kota']) ? $pasien['kota'] : '-'); ?></td>
          </tr>
          <!-- <tr>
          <td colspan="3">Tanggal Periksa</td>
          <td>:</td>
          <td class="dotted-underline"></td>
        </tr> -->
          <tr>
            <td style="width: 3%;" class="strong pt-10">I.</td>
            <td class="strong pt-10" colspan="2">DATA UMUM</td>
            <td></td>
            <td class="text-right"><sup><small><?= isset($pemkes['pasien_updated_at']) ? $pemkes['pasien_updated_at'] : (isset($pasien['updated_at']) ? $pasien['updated_at'] : '-'); ?></small></sup></td>
          </tr>
          <?= $this->include('uklw/pdf/child_data_umum') ?>
          <tr>
            <td class="strong pt-10">II.</td>
            <td class="strong pt-10" colspan="2">ANAMNESA DAN PEMERIKSAAN</td>
            <td></td>
            <td class="text-right"><sup><small><?= isset($pemkes['pemkes_updated_at']) ? $pemkes['pemkes_updated_at'] : (isset($pasien['updated_at']) ? $pasien['updated_at'] : '-'); ?></small></sup></td>
          </tr>
          <?= $this->include('uklw/pdf/child_anamnesa_dan_pemeriksaan') ?>
        </table>
      </div>
      <?php
      $penomoran_abjad = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
      $abjad = 0;
      ?>
      <div class="mt-5 mx-40">
        <table style="width: 100%;" border="0">
          <?php if ($pemkes['status_pemeriksaan'] == 'Penjamah Makanan'): ?>
            <tr>
              <td colspan="9" class="text-right">
                <sup><small><?= $pemkes['pemkes_updated_at']; ?></small></sup>
              </td>
              <td></td>
            </tr>
            <tr>
              <td style="width: 3%;" class="strong"><?= $penomoran_abjad[$abjad++]; ?>.</td>
              <td class="strong" style="width: 75%" colspan="6">Penjamah Makanan (tidak perlu diisi bila bukan penjamah makanan)</td>
              <td class="bordered text-center strong" style="width: 7%">Ya</td>
              <td class="bordered text-center strong" style="width: 7%">Tidak</td>
              <td style="width: 3%;"></td>
            </tr>
            <?= $this->include('uklw/pdf/child_penjamah_makanan'); ?>
            <!-- <tr>
            <td colspan="9" style="line-height: 6px; height: 6px;">&nbsp;</td>
          </tr> -->
          <?php endif; ?>
          <?php if ($faktorRisikoPTM): ?>
            <tr>
              <td colspan="9" class="text-right">
                <sup><small><?= $faktorRisikoPTM['updated_at']; ?></small></sup>
              </td>
              <td></td>
            </tr>
            <tr>
              <td style="width: 3%;" class="strong"><?= $penomoran_abjad[$abjad++]; ?>.</td>
              <td class="strong" style="width: 75%" colspan="6">Faktor Risiko PTM (wajib diisi)</td>
              <td class="bordered text-center strong" style="width: 7%">Ya</td>
              <td class="bordered text-center strong" style="width: 7%">Tidak</td>
              <td style="width: 3%;"></td>
            </tr>
            <?= $this->include('uklw/pdf/child_faktor_risiko_ptm'); ?>
            <!-- <tr>
            <td colspan="9" style="line-height: 6px; height: 6px;">&nbsp;</td>
          </tr> -->
          <?php endif; ?>
          <?php if ($skriningTBC): ?>
            <tr>
              <td colspan="9" class="text-right">
                <sup><small><?= $skriningTBC['updated_at']; ?></small></sup>
              </td>
              <td></td>
            </tr>
            <tr>
              <td style="width: 3%;" class="strong"><?= $penomoran_abjad[$abjad++]; ?>.</td>
              <td class="strong" style="width: 75%" colspan="6">Skrining TB (wajib diisi)</td>
              <td class="bordered text-center strong" style="width: 7%">Ya</td>
              <td class="bordered text-center strong" style="width: 7%">Tidak</td>
              <td style="width: 3%;"></td>
            </tr>
            <?= $this->include('uklw/pdf/child_skrining_tb'); ?>
            <!-- <tr>
          <td colspan="9" style="line-height: 6px; height: 6px;">&nbsp;</td>
        </tr> -->
          <?php endif; ?>
          <?php if ($skriningHIV): ?>
            <tr>
              <td colspan="9" class="text-right">
                <sup><small><?= $skriningHIV['updated_at']; ?></small></sup>
              </td>
              <td></td>
            </tr>
            <tr>
              <td style="width: 3%;" class="strong"><?= $penomoran_abjad[$abjad++]; ?>.</td>
              <td class="strong" style="width: 75%" colspan="6">Skrining HIV-AIDS (wajib diisi)</td>
              <td class="bordered text-center strong" style="width: 7%">Ya</td>
              <td class="bordered text-center strong" style="width: 7%">Tidak</td>
              <td style="width: 3%;"></td>
            </tr>
            <?= $this->include('uklw/pdf/child_skrining_hiv'); ?>
          <?php endif; ?>
        </table>
        <?php if ($skriningHIV || $skriningTBC): ?>
          <!-- <div class="page-break"></div> -->
        <?php endif; ?>
        &nbsp;
        <table style="width: 100%;" border="0">
          <tr>
            <td class="strong" style="width: 3%"><?= $penomoran_abjad[$abjad++]; ?>.</td>
            <td colspan="3" class="strong">Pemeriksaan Fisik</td>
            <td></td>
            <td colspan="2" class="text-right">
              <sup><small><?= $pemkes['pemkes_updated_at']; ?></small></sup>
            </td>
          </tr>
          <?= $this->include('uklw/pdf/child_pemeriksaan_fisik'); ?>
          <?php if ($laboratorium): ?>
            <tr>
              <td class="strong" style="width: 3%"><?= $penomoran_abjad[$abjad++]; ?>.</td>
              <td colspan="3" class="strong">Laboratorium</td>
              <td></td>
              <td colspan="2" class="text-right">
                <sup><small><?= $laboratorium['laboratorium_updated_at']; ?></small></sup>
              </td>
            </tr>
            <?= $this->include('uklw/pdf/child_laboratorium'); ?>
          <?php endif; ?>
          <?php if ($pemkes['catatan_pemeriksaan_updated_at']): ?>
            <?= $this->include('uklw/pdf/child_catatan_dokter'); ?>
          <?php else: ?>
            <tr>
              <td class="strong expand-line text-red" colspan="7">BELUM ADA CATATAN DOKTER UNTUK PEMERIKSAAN INI</td>
            </tr>
          <?php endif; ?>
        </table>
      </div>

      <!-- </div> -->
    <?php else: ?>
      <h3 style="text-align: center;">Belum terdapat data pemeriksaan untuk pasien</h3>
    <?php endif; ?>
  </div>
</body>

</html>