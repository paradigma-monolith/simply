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
      font-size: 8.5pt;
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
      height: 55px;
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
      font-size: 8.5pt;
      height: 40px;
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
      margin-top: 20px;
    }

    .mt-10 {
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <?php foreach ($perjalanan as $value => $data) : ?>
    <?php $isDalamKota = (isset($data['jenis_perjalanan_dinas']) && $data['jenis_perjalanan_dinas'] == 'dalam kota') || $data['kota'] == $data['kota_asal']; ?>
    <div>
      <div class="float-left">
      </div>
      <div class="float-right uppercase" style="margin-right: 25px;">
        <div style="font-size: 7.5pt;">LAMPIRAN II</div>
        <div style="font-size: 7.5pt;">PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA</div>
        <div style="font-size: 7.5pt;"><?= date('Y') >= 2024 ? 'NOMOR 119 TAHUN 2023' : 'NOMOR 113/PMK.05/'; ?><?= date('Y') >= 2024 ? '' : '2012'; ?></div>
        <div style="font-size: 7.5pt;">TENTANG</div>
        <div style="font-size: 7.5pt;">PERJALANAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT</div>
        <div style="font-size: 7.5pt;">NEGARA, PEGAWAI NEGERI, DAN PEGAWAI TIDAK TETAP</div>
      </div>
    </div>
    <div class="clear"></div>
    <div style="margin-top: 20px;">
      <div class="float-left">
        <div style="width: 100px; height: 100px; margin:0; padding:0;"> <?= $logo_kkp; ?> </div>
      </div>
      <div class="center" style="width: auto; padding: 17px 20px;">
        <div><strong style="font-size: 10.5pt;">KEMENTERIAN KESEHATAN R.I.</strong></div>
        <div><strong>DIREKTORAT JENDERAL PENCEGAHAN DAN PENGENDALIAN PENYAKIT</strong></div>
        <div><strong class="uppercase"><?= $data['nama_kantor']; ?></strong></div>
        <div>Jalan Cikapayang No. 5 Bandung 40116</div>
        <div>Telepon (022) 4219305/ Fax (022) 4219305 email : kkpbandung@yahoo.com</div>
      </div>
      <div class="clear"></div>
      <hr>
    </div>
    <div>
      <div class="uppercase center mt-20"><strong style="font-size: 12pt;">SURAT PENGELUARAN RIIL</strong></div>
      <div class="mt-20">Yang bertanda tangan di bawah ini</div>
      <div class="mt-10">
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
      <div class="mt-20"></div>
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
          <td style="border-top: unset; border-bottom: unset; padding: 20px 0; text-align:center;">1 </td>
          <td style="border-top: unset; border-bottom: unset; padding: 20px 0; border-right: unset">Transport <?= $isDalamKota ? 'Dalam Kota' : 'Luar Kota'; ?> </td>
          <td style="border: unset; text-align: center; padding: 20px 0;">(</td>
          <td style="border: unset; text-align: center; padding: 20px 0;"><?= $data['transport_hari'] ?></td>
          <td style="border: unset; text-align: center; padding: 20px 0;">kl</td>
          <td style="border: unset; text-align: center; padding: 20px 0;">x</td>
          <td style="border: unset; text-align: center; padding: 20px 0;">Rp</td>
          <td style="border: unset; text-align: right; padding: 20px 0;"><?= number_format($data['transport_nominal'], 0, ",", ".") ?></td>
          <td style="border: unset; text-align: left; padding: 20px 0;">)</td>
          <td style="border-top: unset; border-bottom: unset; padding: 20px 0; text-align: right; padding-right: 20px;"><?= number_format($data['transport_jumlah'], 0, ",", ".") ?> </td>
          <td style="border-top: unset; border-bottom: unset; padding: 20px 0;"></td>
        </tr>
        <tr>
          <td style="border-bottom: unset;"> </td>
          <td colspan="8">JUMLAH </td>
          <td style="text-align: right;"><?= number_format($data['transport_jumlah'], 0, ",", ".") ?> </td>
          <td></td>
        </tr>
        <tr>
          <td style="border-top: unset;"> </td>
          <td style="border-right: unset; padding: 10px 0 10px 0;"><strong>TERBILANG</strong></td>
          <?php $jml = intval($data['transport_jumlah']); ?>
          <td colspan="9" style="border-left: unset; padding: 10px 0 10px 0;"> <strong>: <?= terbilang($jml); ?></strong> </td>
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
      <tr style="text-align:center;font-size: 13px;">
        <td style="width: 200px;">
          Mengetahui/menyetujui :<br>
          <span>Pejabat Pembuat Komitmen</span>
        </td>
        <td></td>
        <td style="width:10%"></td>
        <td>Pegawai <br> yang melakukan perjalanan </td>
      </tr>

      <tr>
        <td colspan="4" style="height: 60px;"></td>
      </tr>

      <tr style="text-align:center;font-size: 13px;">
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
    <?php if (isset($perjalanan[1 + $value])) : ?>
      <div class="page-break"></div>
    <?php endif; ?>
  <?php endforeach; ?>
</body>

</html>