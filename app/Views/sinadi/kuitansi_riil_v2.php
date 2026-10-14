<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    @page {
      margin: 0;
    }

    * {
      padding: 0;
      margin: 0;
    }

    @font-face {
      font-family: "source_sans_proregular";
      src: local("Arial"), url("/fonts/sourcesans/arial.ttf") format("truetype");
      font-weight: normal;
      font-style: normal;

    }

    * {
      margin: 0;
      /* font-family: 'Arial Narrow', Arial, Helvetica, sans-serif; */
      font-family: 'Arial', 'sans-serif';
      font-size: 7pt;
    }

    body {
      border: unset;
      margin: 40px;
      margin-top: 15px;
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
      height: 45px;
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
      padding: 0px 10px;
      text-align: left;
    }

    th {
      /* background-color: #f0f0f2; */
      padding-top: 2px;
      padding-bottom: 2px;
    }

    td {
      font-size: 7pt;
      height: 25px;
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

    .heade-table-kop {
      margin: 25px 10px 0 0;
      border: unset;
    }

    .unset-table td {
      height: unset;
      border: unset;
    }

    .unset-hight td {
      padding: 2px;
      height: unset;
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
      /* Mengatur garis putus-putus dengan ketebalan 1 piksel */
      text-decoration: none;
      /* Menghapus garis bawah bawaan dari elemen <u> */
      border-color: black;
      color: transparent;
    }

    .mt-20 {
      margin-top: 10px;
    }

    .mt-10 {
      margin-top: 4px;
    }

    table {
      margin-top: 0;
      margin-bottom: 4px;
    }
  </style>
</head>

<body>
  <?php foreach ($perjalanan as $value => $data) : ?>
    <?php $isDalamKota = (isset($data['jenis_perjalanan_dinas']) && $data['jenis_perjalanan_dinas'] == 'dalam kota') || $data['kota'] == $data['kota_asal']; ?>
    <div> style="padding-left: 40px; padding-right: 40px; padding-top: 15px;">
      <div>
        <div class="float-left">
          <div style="width: 75px; height: 75px; margin:0; padding:0;"> <?= $logo_kkp; ?> </div>
        </div>
        <div class="float-right uppercase" style="margin-right: 55px;">
          <div style="font-size: 6pt;">LAMPIRAN II</div>
          <div style="font-size: 6pt;">PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA</div>
          <div style="font-size: 6pt;"><?= date('Y') >= 2024 ? 'NOMOR 119 TAHUN 2023' : 'NOMOR 113/PMK.05/'; ?><?= date('Y') >= 2024 ? '' : '2012'; ?></div>
          <div style="font-size: 6pt;">TENTANG</div>
          <div style="font-size: 6pt;">PERJALANAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT</div>
          <div style="font-size: 6pt;">NEGARA, PEGAWAI NEGERI, DAN PEGAWAI TIDAK TETAP</div>
        </div>
      </div>
      <div class="clear"></div>
      <div>
        <div class="float-left center" style="padding: 10px; padding-left: 38px;">
          <div>KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</div>
          <div>DIREKTORAT JENDERAL</div>
          <div>PENANGGULANGAN PENYAKIT</div>
          <div class="uppercase"><?= $data['nama_kantor']; ?></div>
          <div style="font-size: 1px;">&nbsp;</div>
        </div>
        <div class="float-right">
          <table class="heade-table-kop unset-table">
            <tr>
              <td>Tanggal</td>
              <td style="padding-left: 20px;">: <u class="dotted-underline">…………………………</u> <?= date('Y') ?></td>
            </tr>
            <tr>
              <td>Beban MAK</td>
              <td style="padding-left: 20px;">: <?= substr($data['mata_anggaran'], strrpos($data['mata_anggaran'], '.') + 1); ?></td>
            </tr>
            <tr>
              <td>Bukti Kas No.</td>
              <td style="padding-left: 20px;">: <u class="dotted-underline">…………………</u> / <?= romawi(date('m')) ?> /<?= date('Y') ?></td>
            </tr>
            <tr>
              <td>Tahun Anggaran</td>
              <td style="padding-left: 20px;">: <?= date('Y') ?></td>
            </tr>
            <tr>
              <td>Mata Anggaran</td>
              <td style="padding-left: 20px;">: <?= $data['mata_anggaran']; ?></td>
            </tr>

          </table>
        </div>
      </div>
      <div class="clear"></div>
      <div class="uppercase center" style=" margin-top: 8px;">
        <?php if ($isDalamKota) : ?>
          <u><strong style="font-size: 10.5pt;">KUITANSI</strong></u>
        <?php else : ?>
          <u><strong style="font-size: 10.5pt;">RINCIAN BIAYA PERJALANAN DINAS</strong></u>
        <?php endif; ?>
      </div>
      <div>
        <table class="unset-table" style="width: 90%; margin-top: 10px;">

          <?php if ($isDalamKota) : ?>
            <tr>
              <td style="width: 27%;">Lampiran ST Nomor</td>
              <td>: &nbsp;&nbsp;&nbsp; <?= $data['no_surat_tugas'] ?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>: &nbsp;&nbsp;&nbsp; <?= shortdate_indo2($data['tgl_surat_tugas']); ?> </td>
            </tr>

          <?php else : ?>

            <tr>
              <td style="width: 27%;">Lampiran SPD Nomor</td>
              <td>: &nbsp;&nbsp;&nbsp; <?= $data['no_sppd'] ?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>: &nbsp;&nbsp;&nbsp; <?= shortdate_indo2($data['tgl_sppd']); ?> </td>
            </tr>
          <?php endif; ?>
      </div>
      <div>
        <table class="unset-hight" style="width:100%;" cellpadding="2" cellspacing="0">

          <tr>
            <th style="text-align:center; width: 22px;">NO</th>
            <th colspan="8" style="text-align:center;">RINCIAN BIAYA</th>
            <th style="text-align:center;">JUMLAH</th>
            <th style="text-align:center;">KETERANGAN</th>
          </tr>
          <tr>
            <td style="border-top: unset; border-bottom: unset; padding-top: 20px; text-align:center;">1 </td>
            <td style="border-top: unset; border-bottom: unset; padding-top: 20px; border-right: unset">Transport <?= $isDalamKota ? 'Dalam Kota' : 'Luar Kota'; ?> </td>
            <td style="border: unset; text-align: center; padding-top: 20px;">(</td>
            <td style="border: unset; text-align: center; padding-top: 20px;"><?= $data['transport_hari'] ?></td>
            <td style="border: unset; text-align: center; padding-top: 20px;">kl</td>
            <td style="border: unset; text-align: center; padding-top: 20px;">x</td>
            <td style="border: unset; text-align: center; padding-top: 20px;">Rp</td>
            <td style="border: unset; text-align: right; padding-top: 20px;"><?= number_format($data['transport_nominal'], 0, ",", ".") ?></td>
            <td style="border: unset; text-align: left; padding-top: 20px;">)</td>
            <td style="border-top: unset; border-bottom: unset; padding-top: 20px; text-align: right; padding-right: 20px;"><?= number_format($data['transport_jumlah'], 0, ",", ".") ?> </td>
            <td style="border-top: unset; border-bottom: unset; padding-top: 20px;"></td>
          </tr>
          <tr>
            <td style="border-top: unset; border-bottom: unset; text-align:center;">2 </td>
            <td style="border-top: unset; border-bottom: unset; border-right: unset">Uang Harian </td>
            <td style="border: unset; text-align: center;">(</td>
            <td style="border: unset; text-align: center;"><?= $data['harian_hari'] ?></td>
            <td style="border: unset; text-align: center;">hr</td>
            <td style="border: unset; text-align: center;">x</td>
            <td style="border: unset; text-align: center;">Rp</td>
            <td style="border: unset; text-align: right;"><?= number_format($data['harian_nominal'], 0, ",", ".") ?></td>
            <td style="border: unset; text-align: left;">)</td>
            <td style="border-top: unset; border-bottom: unset; text-align: right; padding-right: 20px;"><?= number_format($data['harian_jumlah'], 0, ",", ".") ?> </td>
            <td style="border-top: unset; border-bottom: unset; "></td>
          </tr>
          <tr>
            <td style="border-top: unset; border-bottom: unset; padding-bottom: 20px; text-align:center;">3 </td>
            <td style="border-top: unset; border-bottom: unset; padding-bottom: 20px; border-right: unset">Penginapan </td>
            <td style="border: unset; text-align: center; padding-bottom: 20px;">(</td>
            <td style="border: unset; text-align: center; padding-bottom: 20px;"><?= $data['penginapan_hari'] ?></td>
            <td style="border: unset; text-align: center; padding-bottom: 20px;">hr</td>
            <td style="border: unset; text-align: center; padding-bottom: 20px;">x</td>
            <td style="border: unset; text-align: center; padding-bottom: 20px;">Rp</td>
            <td style="border: unset; text-align: right; padding-bottom: 20px;"><?= number_format($data['penginapan_nominal'], 0, ",", ".") ?></td>
            <td style="border: unset; text-align: left; padding-bottom: 20px;">)</td>
            <td style="border-top: unset; border-bottom: unset; padding-bottom: 20px; text-align: right; padding-right: 20px;"><?= number_format($data['penginapan_jumlah'], 0, ",", ".") ?> </td>
            <td style="border-top: unset; border-bottom: unset; padding-bottom: 20px;"></td>
          </tr>
          <tr>
            <td style="border-bottom: unset;"> </td>
            <td colspan="8">JUMLAH </td>
            <td style="text-align: right;"><?= number_format($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah'], 0, ",", ".") ?> </td>
            <td></td>
          </tr>
          <tr>
            <td style="border-top: unset;"> </td>
            <td style="border-right: unset; padding: 5px 0 5px 0;"><strong>TERBILANG</strong></td>
            <?php $jml = intval($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah']); ?>
            <td colspan="9" style="border-left: unset; padding: 5px 0 5px 0;"> <strong>: <?= terbilang($jml); ?></strong> </td>
          </tr>
        </table>
      </div>

      <div style="font-size: 1px;">&nbsp;</div>
      <table class="unset-table" style="width:100%">
        <tr>
          <td style="width:65%">&nbsp;</td>
          <td>
            <div style="display: inline-block;">Bandung,</div>
            <div style="width: 140px; display: inline-block; text-align: right;"> <?= date('Y') ?></div>
          </td>
        </tr>
      </table>
      <div style="font-size: 1px;">&nbsp;</div>
      <table class="unset-table" style="width:100%">
        <tr>
          <td>Telah dibayar sejumlah</td>
          <td style="width:36%"></td>
          <td style="width:10%"></td>
          <td style="width:34%">Telah menerima jumlah uang sebesar </td>
        </tr>
        <tr>
          <td>Rp.</td>
          <td><?= number_format($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah'], 0, ",", ".") ?></td>
          <td style="text-align: right; padding-right: 40px;">Rp. </td>
          <td style="padding-left: 40px;"><?= number_format($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah'], 0, ",", ".") ?> </td>
        </tr>
      </table>
      <div style="font-size: 1px;">&nbsp;</div>
      <table class="unset-table" style="width:100%">
        <tr style="text-align:center;font-size: 11px;">
          <td style="width: 200px;">
            BENDAHARA PENGELUARAN <br>
            <span class="uppercase"><?= $data['nama_kantor']; ?></span>
          </td>
          <td></td>
          <td style="width:10%"></td>
          <td>YANG MENERIMA</td>
        </tr>

        <tr>
          <td style="height: 40px;">
            <?php if ($ttd_verifikator_2): ?>
                <img src="<?= $ttd_verifikator_2 ?>" style="height: 35px;" />
            <?php else: ?>
                <p style="color: red; line-height: 40px;">Data belum disetujui oleh verifikator ke-2</p>
            <?php endif; ?>
          </td>
          <td></td>
          <td></td>
          <td>
            <?php if ($data['ttd_peserta']): ?>
                <img src="<?= $data['ttd_peserta'] ?>" style="height: 35px;" />
            <?php else: ?>
                <p style="color: red; line-height: 40px;">Belum ada TTD</p>
            <?php endif; ?>
          </td>
        </tr>

        <tr style="text-align:center;font-size: 11px;">
          <td colspan="2">
            <strong>
              <?= name_capitalize($data['bendahara']); ?> <br>
              <?= $data['bendahara_nip']; ?>
            </strong>
          </td>
          <td></td>
          <td>
            <strong>
              <?= name_capitalize($data['nama_pegawai']); ?> <br>
              NIP. <?= $data['nip_pegawai']; ?>
            </strong>
          </td>
        </tr>
      </table>
      <div style="border: solid; border-bottom: unset;"></div>

      <table class="unset-hight unset-table" style="width:100%">
        <tr style="text-align:left ;font-size: 11px;">
          <td style="width: 42%;">Ditetapkan sejumlah</td>
          <td style="width: 6%;"> : Rp. </td>
          <td><?= number_format($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah'], 0, ",", ".") ?> </td>
        </tr>
        <tr>
          <td>Yang telah dibayar semula</td>
          <td> : Rp. </td>
          <td><?= number_format($data['transport_jumlah'] + $data['harian_jumlah'] + $data['penginapan_jumlah'], 0, ",", ".") ?> </td>
        </tr>
        <tr>
          <td>Sisa kurang/lebih</td>
          <td> : Rp. </td>
          <td></td>
        </tr>
      </table>

      <table class="unset-table" style="width:100%">
        <tr style="text-align:left;;font-size: 11px;">
          <td>&nbsp;</td>
          <td style="width:30%">&nbsp;</td>
          <td colspan="2" style="width: 5%;">Setuju dibayar</td>
        </tr>
        <tr style="text-align:left;;font-size: 11px;">
          <td>&nbsp;</td>
          <td style="width:30%">&nbsp;</td>
          <td style="width: 5%;">An</td>
          <td>
            Kuasa Pengguna Anggaran/Pengg. Barang
          </td>
        </tr>
        <tr style="text-align:left;;font-size: 11px;">
          <td>&nbsp;</td>
          <td style="width:30%">&nbsp;</td>
          <td style="width: 5%;"></td>
          <td>
            Pejabat Pembuat Komitmen
          </td>
        </tr>
        <tr style="text-align:center;font-size: 11px;">
          <td></td>
          <td></td>
          <td></td>
          <td style="height: 40px;">
            <?php if ($ttd_verifikator_3): ?>
                <img src="<?= $ttd_verifikator_3 ?>" style="height: 35px;" />
            <?php else: ?>
                <p style="color: red; line-height: 40px;">Data belum disetujui oleh verifikator ke-3</p>
            <?php endif; ?>
          </td>
        </tr>
        <tr style="text-align:left;;font-size: 11px;">
          <td></td>
          <td></td>
          <td></td>
          <td>
            <strong>
              <?= name_capitalize($data['ppk']); ?> <br> <?= $data['ppk_nip']; ?>
            </strong>
          </td>
        </tr>
      </table>

      <div class="mt-20"></div>
      <div class="mt-20" style="border-top: dashed 1px;"></div>
      <!--<div class="mt-20"></div>-->
      <!--<div class="mt-20">-->
        <!--<div class="float-left">-->
        <!--</div>-->
        <!--<div class="float-right uppercase" style="margin-right: 25px;">-->
        <!--  <div style="font-size: 6pt;">LAMPIRAN II</div>-->
        <!--  <div style="font-size: 6pt;">PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA</div>-->
        <!--  <div style="font-size: 6pt;"><?//= date('Y') >= 2024 ? 'NOMOR 119 TAHUN 2023' : 'NOMOR 113/PMK.05/'; ?><?//= date('Y') >= 2024 ? '' : '2012'; ?></div>-->
        <!--  <div style="font-size: 6pt;">TENTANG</div>-->
        <!--  <div style="font-size: 6pt;">PERJALANAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT</div>-->
        <!--  <div style="font-size: 6pt;">NEGARA, PEGAWAI NEGERI, DAN PEGAWAI TIDAK TETAP</div>-->
        <!--</div>-->
      <!--</div>-->
      <div class="clear"></div>
      <div style="margin-top: 20px;">
        <div class="float-left">
          <div style="width: 75px; height: 75px; margin:0; padding:0;"> <?= $logo_kkp; ?> </div>
        </div>
        <div class="center" style="width: auto; padding: 8px 20px;">
          <div><strong style="font-size: 9pt;">KEMENTERIAN KESEHATAN R.I.</strong></div>
          <div><strong>DIREKTORAT JENDERAL PENANGGULANGAN PENYAKIT</strong></div>
          <div><strong class="uppercase"><?= $data['nama_kantor']; ?></strong></div>
          <div>Jalan Cikapayang No. 5 Bandung 40116</div>
          <div>Telepon (022) 4219305/ Fax (022) 4219305 email : kkpbandung@yahoo.com</div>
        </div>
        <div class="clear"></div>
        <hr>
      </div>
      <div>
        <div class="uppercase center mt-20"><strong style="font-size: 10.5pt;">SURAT PENGELUARAN RIIL</strong></div>
        <div class="mt-10">Yang bertanda tangan di bawah ini</div>
        <div class="">
          <table class="unset-table" style="width: 100%;">
            <tr>
              <td style="width: 20%;">Nama</td>
              <td>: <?= name_capitalize($data['nama_pegawai']) ?> </td>
            </tr>
            <tr>
              <td>NIP</td>
              <td>: <?= $data['nip_pegawai'] ?> </td>
            </tr>
            <tr>
              <td>Jabatan</td>
              <td>: <?= $data['jabatan_pegawai']; ?> </td>
            </tr>
          </table>
        </div>
        <!-- <div class="mt-20"></div> -->
        <div class="mt-10">
          <table class="unset-table" style="width:100%">
            <tr>
              <?php if ($isDalamKota) : ?>
                <td>Berdasarkan Surat Tugas Nomor : <?= $data['no_surat_tugas'] ?></td>
                <td style="text-align: center;">Tanggal : <?= shortdate_indo2($data['tgl_surat_tugas']); ?></td>
              <?php else : ?>
                <td>Berdasarkan Surat Perjalanan Dinas Nomor : <?= $data['no_sppd'] ?></td>
                <td style="text-align: center;">Tanggal : <?= shortdate_indo2($data['tgl_sppd']); ?></td>
              <?php endif; ?>
            </tr>
            <tr>
              <td colspan="2">dengan ini kami menyatakan dengan sesungguhnya, bahwa :</td>
            </tr>
            <tr>
              <td colspan="2">1. Biaya transport pegawai dan/atau biaya penginapan di bawah ini yang tidak dapat diperoleh bukti-bukti pengeluaran</td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left: 22px;"> meliputi :</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="clear"></div>

      <div style="margin-left: 9px;">
        <table class="unset-hight" style="width:100%;" cellpadding="2" cellspacing="0">

          <tr>
            <th style="text-align:center; width: 22px;">NO</th>
            <th colspan="8" style="text-align:center;">RINCIAN BIAYA</th>
            <th style="text-align:center;">JUMLAH</th>
            <th style="text-align:center;">KETERANGAN</th>
          </tr>
          <tr>
            <td style="border-top: unset; border-bottom: unset; padding: 10px 0; text-align:center;">1 </td>
            <td style="border-top: unset; border-bottom: unset; padding: 10px 0; border-right: unset">Transport <?= $isDalamKota ? 'Dalam Kota' : 'Luar Kota'; ?> </td>
            <td style="border: unset; text-align: center; padding: 10px 0;">(</td>
            <td style="border: unset; text-align: center; padding: 10px 0;"><?= $data['transport_hari'] ?></td>
            <td style="border: unset; text-align: center; padding: 10px 0;">kl</td>
            <td style="border: unset; text-align: center; padding: 10px 0;">x</td>
            <td style="border: unset; text-align: center; padding: 10px 0;">Rp</td>
            <td style="border: unset; text-align: right; padding: 10px 0;"><?= number_format($data['transport_nominal'], 0, ",", ".") ?></td>
            <td style="border: unset; text-align: left; padding: 10px 0;">)</td>
            <td style="border-top: unset; border-bottom: unset; padding: 10px 0; text-align: right; padding-right: 20px;"><?= number_format($data['transport_jumlah'], 0, ",", ".") ?> </td>
            <td style="border-top: unset; border-bottom: unset; padding: 10px 0;"></td>
          </tr>
          <tr>
            <td style="border-bottom: unset;"> </td>
            <td colspan="8">JUMLAH </td>
            <td style="text-align: right;"><?= number_format($data['transport_jumlah'], 0, ",", ".") ?> </td>
            <td></td>
          </tr>
          <tr>
            <td style="border-top: unset;"> </td>
            <td style="border-right: unset; padding: 5px 0 5px 0;"><strong>TERBILANG</strong></td>
            <?php $jml = intval($data['transport_jumlah']); ?>
            <td colspan="9" style="border-left: unset; padding: 5px 0 5px 0;"> <strong>: <?= terbilang($jml); ?></strong> </td>
          </tr>
        </table>
      </div>

      <div>
        <table class="unset-table" style="width:100%">
          <tr>
            <td colspan="2">
              2. Jumlah uang tersebut pada angka 1 di atas benar-benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding-left: 23px;">
              dan apabila dikemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding-left: 23px;">
              tersebut ke Kas Negara.
            </td>
          </tr>
        </table>
        <div class="mt-10" style="margin-left: 9px;">
          Demikian pernyataan ini kami buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.
        </div>
      </div>

      <div class="mt-20">&nbsp;</div>
      <table class="unset-table mt-10" style="width:100%">
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td>
            <div style="display: inline-block;">Bandung,</div>
            <div style="width: 140px; display: inline-block; text-align: right;"> <?= date('Y') ?></div>
          </td>
        </tr>
        <tr style="text-align:center;font-size: 11px;">
          <td style="width: 200px;">
            Mengetahui/menyetujui :<br>
            <span>Pejabat Pembuat Komitmen</span>
          </td>
          <td></td>
          <td style="width:10%"></td>
          <td>Pegawai <br> yang melakukan perjalanan </td>
        </tr>

        <tr>
          <td>
            <?php if ($ttd_verifikator_3): ?>
                <img src="<?= $ttd_verifikator_3 ?>" style="height: 35px;" />
            <?php else: ?>
                <p style="color: red; line-height: 40px;">Data belum disetujui oleh verifikator ke-3</p>
            <?php endif; ?>
          </td>
          <td></td>
          <td></td>
          <td style="height: 40px;">
            <?php if ($data['ttd_peserta']): ?>
                <img src="<?= $data['ttd_peserta'] ?>" style="height: 35px;" />
            <?php else: ?>
                <p style="color: red; line-height: 40px;">Belum ada TTD</p>
            <?php endif; ?>
          </td>
        </tr>

        <tr style="text-align:center;font-size: 11px;">
          <td colspan="2">
            <strong>
              <?= name_capitalize($data['ppk']); ?> <br>
              <?= $data['ppk_nip']; ?>
            </strong>
          </td>
          <td></td>
          <td>
            <strong>
              <?= name_capitalize($data['nama_pegawai']); ?> <br>
              NIP. <?= $data['nip_pegawai']; ?>
            </strong>
          </td>
        </tr>
      </table>
    </div>
    <?php if (isset($perjalanan[1 + $value])) : ?>
      <div class="page-break"></div>
    <?php endif; ?>
  <?php endforeach; ?>
</body>

</html>