<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Infografis {apa gitu ya ini}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: sans-serif;
    }

    /* CSS untuk background */
    body {
      background-image: url(<?= $background; ?>);
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      /* Warna teks dan properti lainnya */
      color: #000000;
      /* Ganti dengan warna teks yang Anda inginkan */
      font-size: 11pt;
    }

    .uppercase {
      text-transform: uppercase;
    }

    .center {
      text-align: center;
    }

    .bold {
      font-weight: 700;
    }

    /* ================================================================= */

    .waktu-judul {
      font-size: 14pt;
      margin-top: 190px;
    }

    .jenis-infografis-judul {
      font-size: 14pt;
      margin-top: 10px;
    }

    .wilayah-judul {
      font-size: 14pt;
      margin-top: 10px;
    }

    .kapal {
      position: absolute;
      left: 315px;
    }

    .kapal:first-line {
      top: 315px;
    }

    .kapal:nth-of-type(2) {
      top: 330px;
    }

    .kapal:nth-of-type(3) {
      top: 376px;
    }

    .kapal:nth-of-type(4) {
      top: 391px;
    }

    .total-kapal {
      position: absolute;
    }

    .total-kapal:first-line {
      width: 54px;
      text-align: center;
      top: 314px;
      left: 410px;
    }

    .total-kapal:nth-of-type(2) {
      width: 54px;
      text-align: center;
      top: 375px;
      left: 410px;
    }

    .abk {
      position: absolute;
      left: 265px;
    }

    .abk:first-line {
      top: 449px;
    }

    .abk:nth-of-type(2) {
      top: 464px;
    }

    .abk:nth-of-type(3) {
      top: 510px;
    }

    .abk:nth-of-type(4) {
      top: 525px;
    }

    .total-abk {
      position: absolute;
    }

    .total-abk:first-line {
      width: 55px;
      text-align: center;
      top: 448px;
      left: 319px;
    }

    .total-abk:nth-of-type(2) {
      width: 55px;
      text-align: center;
      top: 510px;
      left: 319px;
    }

    .penumpang-kapal {
      position: absolute;
      left: 555px;
    }

    .penumpang-kapal:first-line {
      top: 449px;
    }

    .penumpang-kapal:nth-of-type(2) {
      top: 464px;
    }

    .penumpang-kapal:nth-of-type(3) {
      top: 510px;
    }

    .penumpang-kapal:nth-of-type(4) {
      top: 525px;
    }

    .total-penumpang-kapal {
      position: absolute;
    }

    .total-penumpang-kapal:first-line {
      width: 55px;
      text-align: center;
      top: 448px;
      left: 611px;
    }

    .total-penumpang-kapal:nth-of-type(2) {
      width: 55px;
      text-align: center;
      top: 510px;
      left: 611px;
    }

    .pesawat {
      position: absolute;
      left: 315px;
    }

    .pesawat:first-line {
      top: 584px;
    }

    .pesawat:nth-of-type(2) {
      top: 599px;
    }

    .pesawat:nth-of-type(3) {
      top: 644px;
    }

    .pesawat:nth-of-type(4) {
      top: 659px;
    }

    .total-pesawat {
      position: absolute;
    }

    .total-pesawat:first-line {
      width: 54px;
      text-align: center;
      top: 585px;
      left: 410px;
    }

    .total-pesawat:nth-of-type(2) {
      width: 54px;
      text-align: center;
      top: 646px;
      left: 410px;
    }

    .crew {
      position: absolute;
      left: 265px;
    }

    .crew:first-line {
      top: 721px;
    }

    .crew:nth-of-type(2) {
      top: 736px;
    }

    .crew:nth-of-type(3) {
      top: 782px;
    }

    .crew:nth-of-type(4) {
      top: 797px;
    }

    .total-crew {
      position: absolute;
    }

    .total-crew:first-line {
      width: 55px;
      text-align: center;
      top: 726px;
      left: 320px;
    }

    .total-crew:nth-of-type(2) {
      width: 55px;
      text-align: center;
      top: 785px;
      left: 320px;
    }

    .penumpang-pesawat {
      position: absolute;
      left: 555px;
    }

    .penumpang-pesawat:first-line {
      top: 721px;
    }

    .penumpang-pesawat:nth-of-type(2) {
      top: 736px;
    }

    .penumpang-pesawat:nth-of-type(3) {
      top: 782px;
    }

    .penumpang-pesawat:nth-of-type(4) {
      top: 797px;
    }

    .total-penumpang-pesawat {
      position: absolute;
    }

    .total-penumpang-pesawat:first-line {
      width: 55px;
      text-align: center;
      top: 726px;
      left: 611px;
    }

    .total-penumpang-pesawat:nth-of-type(2) {
      width: 55px;
      text-align: center;
      top: 785px;
      left: 611px;
    }

    .sertifikat {
      position: absolute;
      left: 307px;
    }

    .buku {
      position: absolute;
      left: 590px;
    }

    .sertifikat:first-line,
    .buku:first-line {
      top: 844px;
    }

    .sertifikat:nth-of-type(2),
    .buku:nth-of-type(2) {
      top: 859px;
    }

    .sertifikat:nth-of-type(3),
    .buku:nth-of-type(3) {
      top: 874px;
    }

    .sertifikat:nth-of-type(4),
    .buku:nth-of-type(4) {
      top: 889px;
    }

    .sertifikat:nth-of-type(5),
    .buku:nth-of-type(5) {
      top: 904px;
    }

    .sertifikat:nth-of-type(6),
    .buku:nth-of-type(6) {
      top: 919px;
    }

    .sertifikat:nth-of-type(7),
    .buku:nth-of-type(7) {
      top: 935px;
    }

    .vaksin {
      position: absolute;
      left: 352px;
    }

    .vaksin:first-line {
      top: 982px;
    }

    .vaksin:nth-of-type(2) {
      top: 997px;
    }

    .vaksin:nth-of-type(3) {
      top: 1012px;
    }

    .vaksin:nth-of-type(4) {
      left: 532px;
      top: 982px;
    }

    .vaksin:nth-of-type(5) {
      left: 532px;
      top: 997px;
    }

    .vaksin:nth-of-type(6) {
      left: 532px;
      top: 1012px;
    }


    /*Text influenza*/
    .vaksin:nth-of-type(7) {
      top: 994px;
      left: 162px;
      font-weight: 700;
      font-size: 8.5pt;
    }

    /*Text Tifoid*/
    .vaksin:nth-of-type(8) {
      top: 1010px;
      left: 162px;
      font-weight: 700;
      font-size: 8.5pt;
    }
  </style>
</head>

<body>
  <div class="bold center uppercase waktu-judul">TANGGAL <?= $tanggal; ?></div>
  <div class="bold center uppercase jenis-infografis-judul"><?= $jenis_infografis; ?></div>
  <?php if($wilayah == "WILAYAH KERJA KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG"): 
    $wilayah = "WILAYAH KERJA BKK KELAS I BANDUNG (INDUK)";
  endif; ?>
  <div class="bold center uppercase wilayah-judul"><?= $wilayah; ?></div>

  <div>
    <div class="kapal"><?= is_numeric($rekapitulasi_kapal['kapal_datang_luar']) ? number_format($rekapitulasi_kapal['kapal_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="kapal"><?= is_numeric($rekapitulasi_kapal['kapal_datang_negeri']) ? number_format($rekapitulasi_kapal['kapal_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="kapal"><?= is_numeric($rekapitulasi_kapal['kapal_berangkat_luar']) ? number_format($rekapitulasi_kapal['kapal_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="kapal"><?= is_numeric($rekapitulasi_kapal['kapal_berangkat_negeri']) ? number_format($rekapitulasi_kapal['kapal_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-kapal"><strong><i><?= is_numeric($rekapitulasi_kapal['kapal_datang_negeri']) && is_numeric($rekapitulasi_kapal['kapal_datang_luar']) ? (number_format($rekapitulasi_kapal['kapal_datang_negeri'] + $rekapitulasi_kapal['kapal_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-kapal"><strong><i><?= is_numeric($rekapitulasi_kapal['kapal_berangkat_negeri']) && is_numeric($rekapitulasi_kapal['kapal_berangkat_luar']) ? (number_format($rekapitulasi_kapal['kapal_berangkat_negeri'] + $rekapitulasi_kapal['kapal_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>
  <div>
    <div class="abk"><?= is_numeric($rekapitulasi_kapal['abk_datang_luar']) ? number_format($rekapitulasi_kapal['abk_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="abk"><?= is_numeric($rekapitulasi_kapal['abk_datang_negeri']) ? number_format($rekapitulasi_kapal['abk_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="abk"><?= is_numeric($rekapitulasi_kapal['abk_berangkat_luar']) ? number_format($rekapitulasi_kapal['abk_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="abk"><?= is_numeric($rekapitulasi_kapal['abk_berangkat_negeri']) ? number_format($rekapitulasi_kapal['abk_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-abk"><strong><i><?= is_numeric($rekapitulasi_kapal['abk_datang_negeri']) && is_numeric($rekapitulasi_kapal['abk_datang_luar']) ? (number_format($rekapitulasi_kapal['abk_datang_negeri'] + $rekapitulasi_kapal['abk_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-abk"><strong><i><?= is_numeric($rekapitulasi_kapal['abk_berangkat_negeri']) && is_numeric($rekapitulasi_kapal['abk_berangkat_luar']) ? (number_format($rekapitulasi_kapal['abk_berangkat_negeri'] + $rekapitulasi_kapal['abk_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>
  <div>
    <div class="penumpang-kapal"><?= is_numeric($rekapitulasi_kapal['penumpang_datang_luar']) ? number_format($rekapitulasi_kapal['penumpang_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-kapal"><?= is_numeric($rekapitulasi_kapal['penumpang_datang_negeri']) ? number_format($rekapitulasi_kapal['penumpang_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-kapal"><?= is_numeric($rekapitulasi_kapal['penumpang_berangkat_luar']) ? number_format($rekapitulasi_kapal['penumpang_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-kapal"><?= is_numeric($rekapitulasi_kapal['penumpang_berangkat_negeri']) ? number_format($rekapitulasi_kapal['penumpang_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-penumpang-kapal"><strong><i><?= is_numeric($rekapitulasi_kapal['penumpang_datang_negeri']) && is_numeric($rekapitulasi_kapal['penumpang_datang_luar']) ? (number_format($rekapitulasi_kapal['penumpang_datang_negeri'] + $rekapitulasi_kapal['penumpang_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-penumpang-kapal"><strong><i><?= is_numeric($rekapitulasi_kapal['penumpang_berangkat_negeri']) && is_numeric($rekapitulasi_kapal['penumpang_berangkat_luar']) ? (number_format($rekapitulasi_kapal['penumpang_berangkat_negeri'] + $rekapitulasi_kapal['penumpang_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>

  <div>
    <div class="pesawat"><?= is_numeric($rekapitulasi_pesawat['pesawat_datang_luar']) ? number_format($rekapitulasi_pesawat['pesawat_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="pesawat"><?= is_numeric($rekapitulasi_pesawat['pesawat_datang_negeri']) ? number_format($rekapitulasi_pesawat['pesawat_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="pesawat"><?= is_numeric($rekapitulasi_pesawat['pesawat_berangkat_luar']) ? number_format($rekapitulasi_pesawat['pesawat_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="pesawat"><?= is_numeric($rekapitulasi_pesawat['pesawat_berangkat_negeri']) ? number_format($rekapitulasi_pesawat['pesawat_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-pesawat"><strong><i><?= is_numeric($rekapitulasi_pesawat['pesawat_datang_negeri']) && is_numeric($rekapitulasi_pesawat['pesawat_datang_luar']) ? (number_format($rekapitulasi_pesawat['pesawat_datang_negeri'] + $rekapitulasi_pesawat['pesawat_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-pesawat"><strong><i><?= is_numeric($rekapitulasi_pesawat['pesawat_berangkat_negeri']) && is_numeric($rekapitulasi_pesawat['pesawat_berangkat_luar']) ? (number_format($rekapitulasi_pesawat['pesawat_berangkat_negeri'] + $rekapitulasi_pesawat['pesawat_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>
  <div>
    <div class="crew"><?= is_numeric($rekapitulasi_pesawat['crew_datang_luar']) ? number_format($rekapitulasi_pesawat['crew_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="crew"><?= is_numeric($rekapitulasi_pesawat['crew_datang_negeri']) ? number_format($rekapitulasi_pesawat['crew_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="crew"><?= is_numeric($rekapitulasi_pesawat['crew_berangkat_luar']) ? number_format($rekapitulasi_pesawat['crew_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="crew"><?= is_numeric($rekapitulasi_pesawat['crew_berangkat_negeri']) ? number_format($rekapitulasi_pesawat['crew_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-crew"><strong><i><?= is_numeric($rekapitulasi_pesawat['crew_datang_negeri']) && is_numeric($rekapitulasi_pesawat['crew_datang_luar']) ? (number_format($rekapitulasi_pesawat['crew_datang_negeri'] + $rekapitulasi_pesawat['crew_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-crew"><strong><i><?= is_numeric($rekapitulasi_pesawat['crew_berangkat_negeri']) && is_numeric($rekapitulasi_pesawat['crew_berangkat_luar']) ? (number_format($rekapitulasi_pesawat['crew_berangkat_negeri'] + $rekapitulasi_pesawat['crew_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>
  <div>
    <div class="penumpang-pesawat"><?= is_numeric($rekapitulasi_pesawat['penumpang_datang_luar']) ? number_format($rekapitulasi_pesawat['penumpang_datang_luar'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-pesawat"><?= is_numeric($rekapitulasi_pesawat['penumpang_datang_negeri']) ? number_format($rekapitulasi_pesawat['penumpang_datang_negeri'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-pesawat"><?= is_numeric($rekapitulasi_pesawat['penumpang_berangkat_luar']) ? number_format($rekapitulasi_pesawat['penumpang_berangkat_luar'], 0, '', '.') : '-'; ?></div>
    <div class="penumpang-pesawat"><?= is_numeric($rekapitulasi_pesawat['penumpang_berangkat_negeri']) ? number_format($rekapitulasi_pesawat['penumpang_berangkat_negeri'], 0, '', '.') : '-'; ?></div>
    <div>
      <div class="total-penumpang-pesawat"><strong><i><?= is_numeric($rekapitulasi_pesawat['penumpang_datang_negeri']) && is_numeric($rekapitulasi_pesawat['penumpang_datang_luar']) ? (number_format($rekapitulasi_pesawat['penumpang_datang_negeri'] + $rekapitulasi_pesawat['penumpang_datang_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
      <div class="total-penumpang-pesawat"><strong><i><?= is_numeric($rekapitulasi_pesawat['penumpang_berangkat_negeri']) && is_numeric($rekapitulasi_pesawat['penumpang_berangkat_luar']) ? (number_format($rekapitulasi_pesawat['penumpang_berangkat_negeri'] + $rekapitulasi_pesawat['penumpang_berangkat_luar'], 0, '', '.')) : "-"; ?></i></strong></div>
    </div>
  </div>

  <div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_phqc']) ? number_format($rekapitulasi_dokumen['total_phqc'], 0, '', '.') : '-'; ?></div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_cop']) ? number_format($rekapitulasi_dokumen['total_cop'], 0, '', '.') : '-'; ?></div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_sscec']) ? number_format($rekapitulasi_dokumen['total_sscec'], 0, '', '.') : '-'; ?></div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_sscc']) ? number_format($rekapitulasi_dokumen['total_sscc'], 0, '', '.') : '-'; ?></div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_p3k']) ? number_format($rekapitulasi_dokumen['total_p3k'], 0, '', '.') : '-'; ?></div>
    <div class="sertifikat"><?= is_numeric($rekapitulasi_dokumen['total_kontraindikasi']) ? number_format($rekapitulasi_dokumen['total_kontraindikasi'], 0, '', '.') : '-'; ?></div>
  </div>
  <div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_icv']) ? number_format($rekapitulasi_dokumen['total_icv'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_sklt']) ? number_format($rekapitulasi_dokumen['total_sklt'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_siaos']) ? number_format($rekapitulasi_dokumen['total_siaos'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_siaj']) ? number_format($rekapitulasi_dokumen['total_siaj'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_kir']) ? number_format($rekapitulasi_dokumen['total_kir'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_bukes']) ? number_format($rekapitulasi_dokumen['total_bukes'], 0, '', '.') : '-'; ?></div>
    <div class="buku"><?= is_numeric($rekapitulasi_dokumen['total_e_icv']) ? number_format($rekapitulasi_dokumen['total_e_icv'], 0, '', '.') : '-'; ?></div>
  </div>

  <div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['total_vaksin_mm']) ? number_format($rekapitulasi_dokumen['total_vaksin_mm'], 0, '', '.') : '-'; ?></div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['total_vaksin_yf']) ? number_format($rekapitulasi_dokumen['total_vaksin_yf'], 0, '', '.') : '-'; ?></div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['vaksin_tifoid']) ? number_format($rekapitulasi_dokumen['vaksin_tifoid'], 0, '', '.') : '-'; ?></div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['vaksin_influenza']) ? number_format($rekapitulasi_dokumen['vaksin_influenza'], 0, '', '.') : '-'; ?></div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['total_vaksin_covid']) ? number_format($rekapitulasi_dokumen['total_vaksin_covid'], 0, '', '.') : '-'; ?></div>
    <div class="vaksin"><?= is_numeric($rekapitulasi_dokumen['vaksin_polio']) ? number_format($rekapitulasi_dokumen['vaksin_polio'], 0, '', '.') : '-'; ?></div>
  </div>
</body>

</html>