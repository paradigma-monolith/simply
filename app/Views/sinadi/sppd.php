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
      font-size: 7.5pt;
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

    .no-hight td {
      padding-top: unset;
      padding-bottom: unset;
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

    .right {
      text-align: right;
    }

    .fz8 {
      font-size: 8.5pt;
    }

    .border {
      border-style: solid;
    }

    table div,
    li,
    ul,
    ol {
      font-size: 8.5pt;
    }

    table td {
      padding-top: 10px;
    }

    .sppd-back td div,
    .sppd-back td span,
    .sppd-back td div strong,
    .sppd-back td div u {
      font-size: 9pt;
    }

    table td {
      padding-bottom: 10px;
    }
  </style>
</head>

<body>
  <?php foreach ($perjalanan as $value => $data) : ?>
    <div>
      <div class="right uppercase" style="margin-top: 35px; margin-bottom: 20px; margin-right: 25px;">
        Lampiran 1
      </div>
      <div>
        <div style="width: 350px; display: inline-block;">
          <div class="uppercase center fz8">kementrian kesehatan republik Indonesia</div>
            <?php if(date("Y") >= 2025): ?>
              <div class="uppercase center fz8">DIREKTORAT JENDERAL</div>
              <div class="uppercase center fz8">PENANGGULANGAN PENYAKIT</div>
            <?php else: ?>
              <div class="uppercase center fz8">direktorat jenderal pencegahan</div>
              <div class="uppercase center fz8">dan pengendalian penyakit</div>
            <?php endif; ?>
          
          <div class="uppercase center fz8" style="width: 230px; margin: auto;"><?= $data['nama_kantor']; ?></div>
        </div>
        <div style="display: inline-block; padding-left: 110px;">
          <div style="font-size: 7pt;">Keputusan Menteri Keuangan Republik Indonesia</div>
          <div style="font-size: 7pt;"><?= date('Y') >= 2024 ? 'Nomor 119 Tahun 2023' : 'Nomor 113/PMK.05/'; ?><?= date('Y') >= 2024 ? '' : '2012'; ?></div>
          <div style="font-size: 7pt;">Tentang Perjalanan dinas jabatan dalam negeri</div>
          <div style="font-size: 7pt;">Bagi Pejabat Negara Pegawai Negeri dan Pegawai</div>
          <div style="font-size: 7pt;">Tidak Tetap</div>
        </div>
      </div>
     <div style="margin-top: 15px;">
  <div style="width: 350px; display: inline-block;">
    <div style="width: 250px; margin: auto;"></div>
  </div>
  <div style="display: inline-block; padding-left: 110px;">
    <div>
      <table class="unset-hight unset-table">
        <tr>
          <td style="margin: 0; padding: 0; font-size: 7.5pt; width: 85px;">Lembar ke</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">:</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;"></td>
        </tr>
        <tr>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">Kode No</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">:</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;"></td>
        </tr>
        <tr>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">Nomor</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">: &nbsp;</td>
          <td style="margin: 0; padding: 0; font-size: 7.5pt;">${nomor_naskah}</td>
        </tr>
      </table>
    </div>
  </div>
</div>

      <div>
        <div class="uppercase center" style="margin-top: 15px; margin-bottom: 15px;">
          <strong><u style="font-size: 8.5pt; border-bottom: solid 1px;">surat perjalanan dinas (spd)</u></strong>
        </div>
        <table style="width: 100%;">
          <tr>
            <td style="padding: 0 10px; height: 20px; width: 4%;" class="center">1.</td>
            <td style="padding: 0 10px; height: unset; width: 48%;">Pejabat Pembuat Komitmen</td>
            <td style="padding: 0 10px; height: unset; width: 48%;" colspan="2"><?= name_capitalize($data['ppk']); ?></td>
          </tr>
          <tr>
            <td class="center">2.</td>
            <td>
              <div>Nama Pegawai yang melaksanakan Perjalanan Dinas</div>
              <div>NIP</div>
            </td>
            <td colspan="2">
              <div> : <?= name_capitalize($data['nama_pegawai']); ?></div>
              <div> : <?= name_capitalize($data['nip_pegawai']); ?></div>
            </td>
          </tr>
          <tr>
            <td class="center">3.</td>
            <td style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li>Pangkat dan golongan menurut</li>
                  <li>Jabatan</li>
                  <li>Tingkat Biaya perjalanan dinas</li>
                </ol>
              </div>
            </td>
            <td colspan="2" style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li><?= $data['golongan'] == 'default' ? '&nbsp;' : $data['golongan']; ?></li>
                  <li><?= $data['jabatan_pegawai'] == 'default' ? '&nbsp;' : $data['jabatan_pegawai']; ?></li>
                  <li>Tingkat C</li>
                </ol>
              </div>
            </td>
          </tr>
          <tr>
            <td class="center">4.</td>
            <td>
              <div>Maksud Perjalanan Dinas</div>
            </td>
            <td colspan="2"><?= name_capitalize($data['nama_kegiatan']); ?></td>
          </tr>
          <tr>
            <td class="center">5.</td>
            <td>
              <div>Alat angkutan yang dipergunakan</div>
            </td>
            <td colspan="2">
              <div><?= $data['transportasi']; ?></div>
            </td>
          </tr>
          <tr>
            <td class="center">6.</td>
            <td style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li>Tempat berangkat</li>
                  <li>Tempat tujuan</li>
                </ol>
              </div>
            </td>
            <td colspan="2" style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li><?= $data['kota_asal']; ?></li>
                  <li><?= $data['kota']; ?></li>
                </ol>
              </div>
            </td>
          </tr>
          <tr>
            <td class="center">7.</td>
            <td style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li>Lamanya Perjalanan Dinas</li>
                  <li>Tanggal berangkat</li>
                  <li>Tanggal harus kembali / tiba di tempat baru</li>
                </ol>
              </div>
            </td>
            <td colspan="2" style="padding-left: 24px;">
              <div>
                <ol type="a">
                  <li><?= selisih_hari($data['tgl_pergi'], $data['tgl_pulang']); ?> hari</li>
                  <li><?= mediumdate_indo($data['tgl_pergi']); ?></li>
                  <li><?= mediumdate_indo($data['tgl_pulang']); ?></li>
                </ol>
              </div>
            </td>
          </tr>
          <tr class="unset-hight">
            <td class="center">8.</td>
            <td>
              <div style="display: inline;">Pengikut</div>
              <div style="display: inline; margin-left: 30px;">Nama</div>
            </td>
            <td class="center" style="width: 15%;">
              <div>Tanggal Lahir</div>
            </td>
            <td style="width: 33%;">
              <div>Keterangan</div>
            </td>
          </tr>
          <tr>
            <td></td>
            <td style="padding-top: unset;">
              <div>
                <?php for ($i = 1; $i <= 5; $i++) {
                  echo '<div>' . $i . ".</div>";
                } ?>
              </div>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="center">9.</td>
            <td>
              <div>Pembebasan Anggaran</div>
              <div style="padding-left: 14px;">
                <ol type="a">
                  <li>Instansi</li>
                  <li>Akun</li>
                </ol>
              </div>
            </td>
            <td colspan="2" style="padding-left: 24px;">
              <div>&nbsp;</div>
              <div>
                <ol type="a">
                  <li><?= $data['nama_kantor']; ?></li>
                  <li><?= $data['mata_anggaran']; ?></li>
                </ol>
              </div>
            </td>
          </tr>
          <tr>
            <td class="center">10.</td>
            <td>
              <div>Keterangan lain-lain</div>
            </td>
            <td colspan="2">&nbsp;</td>
          </tr>
        </table>
      </div>
      <div>
        <table style="width: 100%;" class="unset-hight">
          <tr>
            <td style="border: unset; width: 60%">&nbsp;</td>
            <td style="border: unset; width: 15%">Dikeluarkan di</td>
            <td style="border: unset; width: 25%">Bandung</td>
          </tr>
          <tr>
            <td style="border: unset;">&nbsp;</td>
            <td style="border: unset; border-bottom: solid 1px;">Pada tanggal</td>
            <td style="border: unset; border-bottom: solid 1px;">
              <?php if (isset($borang) && $borang == 'borang') : ?>
                ${tanggal_naskah}
              <?php else: ?>
                <?= $data['tgl_sppd'] == null ? '' : mediumdate_indo($data['tgl_sppd']); ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td style="border: unset;">&nbsp;</td>
            <td style="border: unset;" colspan="2">Pejabat Pembuat Komitmen</td>
          </tr>
          <tr>
            <td style="border: unset; height: 120px;">&nbsp;</td>
            <td style="border: unset; text-align: left; vertical-align: middle; padding-left: 30px;" colspan="2">
              <?php if (isset($borang) && $borang == 'borang') : ?>
                ${ttd_pengirim1}
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td style="border: unset;">&nbsp;</td>
            <td style="border: unset;" colspan="2"><strong><?= name_capitalize($data['ppk']); ?></strong> <br /><strong><?= $data['ppk_nip']; ?></strong></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="page-break"></div>

    <div style="margin-top: 35px;">
      <table class="no-hight sppd-back" style="width: 100%;">
        <tr>
          <td style="width: 50%;">&nbsp;</td>
          <td style="width: 50%;">
            <div style="display: inline; padding-right: 25px;">Berangkat dari</div>
            <div style="display: inline;">: <?= $data['kota_asal']; ?></div>
            <div>(Tempat kedudukan)</div>
            <div style="display: inline; padding-right: 30px;">Pada Tanggal</div>
            <!--<div style="display: inline;">: <?//= mediumdate_indo($data['tgl_pelaksanaan']); ?></div>-->
            <div style="display: inline;">: <?= mediumdate_indo($data['tgl_pergi']); ?></div>
            <div></div>
            <div style="display: inline; padding-right: 90px;">Ke</div>
            <div style="display: inline;">: <?= $data['kota']; ?></div>
            <div style="width: 200px;">Kepala <?= $data['nama_kantor']; ?></div>
            <div style="height: 120px; text-align: left; vertical-align: middle; padding-left: 30px;">
              <?php if (isset($borang) && $borang == 'borang') : ?>
                <br><br><br><br><br>${ttd_pengirim2}
              <?php endif; ?>
            </div>

            <?php if(date('Y') > 2023): ?>
                <div><strong><?= name_capitalize($data['kepala_kantor']); ?></strong></div>
                <div><strong><?= $data['kepala_kantor_nip']; ?></strong></div>
            <?php else: ?>
                <?php if ($data['nip_pegawai'] == preg_replace('/[^0-9]/', '', $data['kepala_kantor_nip'])) : ?>
                  <div><strong><?= name_capitalize($data['kasubag_adum']); ?></strong></div>
                  <div><strong><?= $data['kasubag_adum_nip']; ?></strong></div>
                <?php else : ?>
                  <div><strong><?= name_capitalize($data['kepala_kantor']); ?></strong></div>
                  <div><strong><?= $data['kepala_kantor_nip']; ?></strong></div>
                <?php endif; ?>
            <?php endif; ?>
            

          </td>
        </tr>
        <?php
        $nomor = ['II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII'];
        $arrayKotaTujuan = explode(',', $data['kota']);
        $koteTerakhir = false;
        // $arrayKotaTujuan = []; // explode(',', $data['kota']);
        for ($i = 0; $i < 4; $i++) {
        ?>
          <tr>
            <td style="width: 50%;">
              <span style="margin-right: 8px; position: absolute"><?= $nomor[$i]; ?></span>
              <div style="display: inline; margin-left: 25px; padding-right: 69px;"> Tiba di</div>
              <div style="display: inline;">: <?php if (isset($arrayKotaTujuan[$i])) echo $arrayKotaTujuan[$i]; ?></div>
              <div></div>
              <div style="display: inline; padding-right: 30px; margin-left: 25px;">Pada Tanggal</div>
              <div style="display: inline;">: <?php if (isset($arrayKotaTujuan[$i])) echo mediumdate_indo($data['tgl_pergi']); ?></div>
              <?php if (isset($borang) && $borang == 'borang' && $i == 0 && isset($jabatan_tujuan) && $jabatan_tujuan != '' && isset($nama_tujuan) && $nama_tujuan != '') : ?>
                <div style="text-align: center;">
                  <div style="font-weight: bold; height: 25px;"><?= htmlspecialchars($jabatan_tujuan) ?></div>
                  <div style="height: 110px; margin-top: 15px; vertical-align: middle;">
                    <br><br><br>${ttd_pengirim3}
                  </div>
                  <div style="font-weight: bold;"><u><?= htmlspecialchars($nama_tujuan) ?></u></div>
                  <div>NIP. <?= htmlspecialchars($nip_tujuan) ?></div>
                </div>
              <?php else: ?>
                <div style="width: 200px; margin-left: 18px; height: 38px;"></div>
                <div style="height: 30px;"></div>
                <div style="margin-left: 18px;">&nbsp;</div>
                <div style="margin-left: 18px;">&nbsp;</div>
              <?php endif; ?>
            </td>
            <td style="width: 50%;">
              <div style="display: inline; padding-right: 25px;">Berangkat dari</div>
              <div style="display: inline;">: <?php if (isset($arrayKotaTujuan[$i])) echo $arrayKotaTujuan[$i]; ?></div>
              <div></div>
              <div style="display: inline; padding-right: 88.5px;">Ke</div>
              <div style="display: inline;">: <?php 
              if($koteTerakhir == false){
                if (isset($arrayKotaTujuan[($i + 1)])) {
                  echo $arrayKotaTujuan[($i + 1)]; 
                }
                else {
                  $koteTerakhir = true;
                  echo $data['kota_asal']; 
                }
              }
              ?></div>
              <div></div>
              <div style="display: inline; padding-right: 28.5px; ">Pada Tanggal</div>
              <div style="display: inline;">: <?php if (isset($arrayKotaTujuan[$i])) echo mediumdate_indo($data['tgl_pulang']); ?></div>
              <?php if (isset($borang) && $borang == 'borang' && $i == 0 && isset($jabatan_tujuan) && $jabatan_tujuan != '' && isset($nama_tujuan) && $nama_tujuan != '') : ?>
                <div style="text-align: center;">
                  <div style="font-weight: bold; height: 25px;"><?= htmlspecialchars($jabatan_tujuan) ?></div>
                  <div style="height: 110px; margin-top: 15px; vertical-align: middle;">
                    <br><br><br>${ttd_pengirim3}
                  </div>
                  <div style="font-weight: bold;"><u><?= htmlspecialchars($nama_tujuan) ?></u></div>
                  <div>NIP. <?= htmlspecialchars($nip_tujuan) ?></div>
                </div>
              <?php else: ?>
                <div style="width: 200px; height: 24px;">&nbsp;</div>
                <div style="height: 30px;"></div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
              <?php endif; ?>
            </td>
          </tr>
        <?php } ?>
        <tr>
          <td style="width: 50%;">
            <span style="margin-right: 8px; position: absolute;"><?= $nomor[$i++]; ?></span>
            <div style="display: inline; margin-left: 25px; padding-right: 69px;">Tiba di</div>
            <div style="display: inline;">: <?= $data['kota_asal']; ?></div>
            <div></div>
            <div style="display: inline; padding-right: 30px; margin-left: 26px;">Pada Tanggal</div>
            <!--<div style="display: inline;">: <?//= mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?></div>-->
            <div style="display: inline;">: <?= mediumdate_indo($data['tgl_pulang']); ?></div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div style="width: 200px; margin-left: 18px; height: 45px;">Pejabat Pembuat Komitmen</div>
            <div style="height: 90px; text-align: left; vertical-align: middle; margin-left: 18px; padding-left: 30px;">
              <?php if (isset($borang) && $borang == 'borang') : ?>
                <br><br>${ttd_pengirim1}
              <?php endif; ?>
            </div>
            <div style="margin-left: 18px;"><strong><?= name_capitalize($data['ppk']); ?></strong></div>
            <div style="margin-left: 18px;"><strong><?= $data['ppk_nip']; ?></strong></div>
          </td>
          <td>
            <div>Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya</div>
            <div>&nbsp;</div>
            <div style="width: 200px; height: 45px;">Pejabat Pembuat Komitmen</div>
            <div style="height: 90px; text-align: left; vertical-align: middle; padding-left: 30px;">
              <?php if (isset($borang) && $borang == 'borang') : ?>
                <br><br>${ttd_pengirim1}
              <?php endif; ?>
            </div>
            <div><strong><?= name_capitalize($data['ppk']); ?></strong></div>
            <div><strong><?= $data['ppk_nip']; ?></strong></div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top: 5px; padding-bottom: 5px;">
            <span style="margin-right: 8px; position: absolute;"><?= $nomor[$i++]; ?></span>
            <div style="display: inline; margin-left: 25px; padding-right: 69px;">CATATAN LAIN-LAIN</div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top: 5px; padding-bottom: 5px; border: unset;">
            <span style="margin-right: 8px; position: absolute;"><?= $nomor[$i++]; ?></span>
            <div style="margin-left: 25px; padding-right: 69px;"><u>PERHATIAN</u></div>
            <div style="padding-left: 15px; padding-top: 5px;">PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat / tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negawa apabila negara menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.</div>
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