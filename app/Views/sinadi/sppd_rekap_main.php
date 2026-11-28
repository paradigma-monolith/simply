<?php
if (!function_exists('name_capitalize')) {
    helper('tanggal');
}
$data = $perjalanan[0] ?? [];
$namaKantor = trim((string) ($data['nama_kantor'] ?? '')) !== ''
    ? (string) $data['nama_kantor']
    : 'Balai Kekarantinaan Kesehatan Kelas I Bandung';
$ppkNama = name_capitalize((string) ($data['ppk'] ?? ''));
$ppkNipLabel = \App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($data['ppk_nip'] ?? '');
$kepalaNama = name_capitalize((string) ($data['kepala_kantor'] ?? ''));
$kepalaNipLabel = \App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($data['kepala_kantor_nip'] ?? '');
$stopRows = \App\Libraries\SinadiSppdSrikandiHelper::buildRekapBackPageStops($srikandi_config ?? []);
?>
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

    table {
      max-width: 7.5in;
      border-collapse: collapse;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: 8px;
      margin-top: 8px;
    }

    td,
    th {
      border: 1px solid #000;
      padding: 0px 10px;
      text-align: left;
    }

    th {
      padding-top: 2px;
      padding-bottom: 2px;
    }

    td {
      font-size: 8.5pt;
      height: 40px;
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

    .page-break {
      page-break-after: always;
    }

    .right {
      text-align: right;
    }

    .fz8 {
      font-size: 8.5pt;
    }

    table div,
    li,
    ul,
    ol {
      font-size: 8.5pt;
    }

    table td {
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .sppd-back {
      width: 100%;
      table-layout: fixed;
    }

    .sppd-back td {
      padding-top: 5px;
      padding-bottom: 5px;
      vertical-align: top;
    }

    .sppd-back td div,
    .sppd-back td span,
    .sppd-back td div strong,
    .sppd-back td div u {
      font-size: 9pt;
    }

    .sppd-stop-line {
      height: 14px;
      line-height: 14px;
      overflow: hidden;
      white-space: nowrap;
    }

    .sppd-pejabat-box {
      height: 128px;
      text-align: center;
      overflow: hidden;
    }

    .sppd-pejabat-jabatan {
      height: 22px;
      line-height: 11px;
      overflow: hidden;
      font-weight: bold;
    }

    .sppd-pejabat-ttd {
      height: 80px;
      text-align: center;
    }

    .sppd-pejabat-identity {
      height: 26px;
      line-height: 12px;
      overflow: hidden;
    }
  </style>
</head>

<body>
  <!-- HALAMAN 1: SURAT PERJALANAN DINAS (SPD) KOLEKTIF -->
  <div>
    <div class="right uppercase" style="margin-top: 35px; margin-bottom: 20px; margin-right: 25px;">
      Lampiran 1
    </div>
    <div>
      <div style="width: 350px; display: inline-block;">
        <div class="uppercase center fz8">KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</div>
        <?php if (date('Y') >= 2025) : ?>
          <div class="uppercase center fz8">DIREKTORAT JENDERAL</div>
          <div class="uppercase center fz8">PENANGGULANGAN PENYAKIT</div>
        <?php else : ?>
          <div class="uppercase center fz8">direktorat jenderal pencegahan</div>
          <div class="uppercase center fz8">dan pengendalian penyakit</div>
        <?php endif; ?>
        <div class="uppercase center fz8" style="width: 230px; margin: auto;"><?= esc($namaKantor); ?></div>
      </div>
      <div style="display: inline-block; padding-left: 110px;">
        <div style="font-size: 7pt;">Keputusan Menteri Keuangan Republik Indonesia</div>
        <div style="font-size: 7pt;"><?= date('Y') >= 2024 ? 'Nomor 119 Tahun 2023' : 'Nomor 113/PMK.05/2012'; ?></div>
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
          <td style="padding: 0 10px; height: unset; width: 48%;" colspan="2"><?= esc($ppkNama); ?></td>
        </tr>
        <tr>
          <td class="center">2.</td>
          <td>
            <div>Nama Pegawai yang melaksanakan Perjalanan Dinas</div>
            <div>NIP</div>
          </td>
          <td colspan="2">
            <div>: Terlampir</div>
            <div>: Terlampir</div>
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
                <li>Terlampir</li>
                <li>Terlampir</li>
                <li>Terlampir</li>
              </ol>
            </div>
          </td>
        </tr>
        <tr>
          <td class="center">4.</td>
          <td>
            <div>Maksud Perjalanan Dinas</div>
          </td>
          <td colspan="2"><?= esc(name_capitalize((string) ($data['nama_kegiatan'] ?? ''))); ?></td>
        </tr>
        <tr>
          <td class="center">5.</td>
          <td>
            <div>Alat angkutan yang dipergunakan</div>
          </td>
          <td colspan="2">
            <div>Terlampir</div>
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
                <li>Terlampir</li>
                <li>Terlampir</li>
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
                <li>Terlampir</li>
                <li>Terlampir</li>
                <li>Terlampir</li>
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
              <?php for ($i = 1; $i <= 5; $i++) : ?>
                <div><?= $i; ?>.</div>
              <?php endfor; ?>
            </div>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="center">9.</td>
          <td>
            <div>Pembebanan Anggaran</div>
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
                <li><?= esc($namaKantor); ?></li>
                <li><?= esc((string) ($data['mata_anggaran'] ?? '')); ?></li>
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
          <td style="border: unset; width: 60%;">&nbsp;</td>
          <td style="border: unset; width: 15%;">Dikeluarkan di</td>
          <td style="border: unset; width: 25%;">Bandung</td>
        </tr>
        <tr>
          <td style="border: unset;">&nbsp;</td>
          <td style="border: unset; border-bottom: solid 1px;">Pada tanggal</td>
          <td style="border: unset; border-bottom: solid 1px;">${tanggal_naskah}</td>
        </tr>
        <tr>
          <td style="border: unset;">&nbsp;</td>
          <td style="border: unset;" colspan="2">Pejabat Pembuat Komitmen</td>
        </tr>
        <tr>
          <td style="border: unset; height: 120px;">&nbsp;</td>
          <td style="border: unset; text-align: left; vertical-align: middle; padding-left: 30px;" colspan="2">
            ${ttd_pengirim1}
          </td>
        </tr>
        <tr>
          <td style="border: unset;">&nbsp;</td>
          <td style="border: unset;" colspan="2"><strong><?= esc($ppkNama); ?></strong><br /><strong><?= esc($ppkNipLabel); ?></strong></td>
        </tr>
      </table>
    </div>
  </div>

  <div class="page-break"></div>

  <!-- HALAMAN 2: HALAMAN BELAKANG / VISUM KOLEKTIF (IDENTIK DENGAN SPPD SRIKANDI) -->
  <div style="margin-top: 35px;">
    <table class="no-hight sppd-back" style="width: 100%; table-layout: fixed;">
      <tr>
        <td style="width: 50%;">&nbsp;</td>
        <td style="width: 50%;">
          <div style="display: inline; padding-right: 25px;">Berangkat dari</div>
          <div style="display: inline;">: Terlampir</div>
          <div>(Tempat kedudukan)</div>
          <div style="display: inline; padding-right: 30px;">Pada Tanggal</div>
          <div style="display: inline;">: Terlampir</div>
          <div></div>
          <div style="display: inline; padding-right: 90px;">Ke</div>
          <div style="display: inline;">: Terlampir</div>
          <div style="width: 200px;">Kepala <?= esc($namaKantor); ?></div>
          <div style="height: 110px; text-align: left; vertical-align: middle; padding-left: 30px;">
            <br><br><br><br>${ttd_pengirim2}
          </div>
          <div><strong><?= esc($kepalaNama); ?></strong></div>
          <div><strong><?= esc($kepalaNipLabel); ?></strong></div>
        </td>
      </tr>
      <?php foreach ($stopRows as $stop) :
        $pejabat = $stop['pejabat'];
      ?>
        <tr>
          <td style="width: 50%;">
            <div class="sppd-stop-line">
              <span style="margin-right: 8px; position: absolute"><?= esc($stop['roman']); ?></span>
              <span style="margin-left: 25px; display: inline-block; width: 102px;">Tiba di</span>
              <span>: <?= esc($stop['tiba_di']); ?></span>
            </div>
            <div class="sppd-stop-line">
              <span style="margin-left: 25px; display: inline-block; width: 102px;">Pada Tanggal</span>
              <span>: <?= esc($stop['tgl_tiba_label']); ?></span>
            </div>
            <div class="sppd-stop-line">&nbsp;</div>
            <?php if ($pejabat !== null) : ?>
              <div class="sppd-pejabat-box">
                <div class="sppd-pejabat-jabatan"><?= esc($pejabat['jabatan']); ?></div>
                <div class="sppd-pejabat-ttd"><br><br><?= $pejabat['placeholder']; ?></div>
                <div class="sppd-pejabat-identity">
                  <div><strong><u><?= esc($pejabat['nama']); ?></u></strong></div>
                  <div><?= $pejabat['nip'] !== '' ? esc(\App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($pejabat['nip'])) : '&nbsp;'; ?></div>
                </div>
              </div>
            <?php else : ?>
              <div class="sppd-pejabat-box">&nbsp;</div>
            <?php endif; ?>
          </td>
          <td style="width: 50%;">
            <div class="sppd-stop-line">
              <span style="display: inline-block; width: 102px;">Berangkat dari</span>
              <span>: <?= esc($stop['berangkat_dari']); ?></span>
            </div>
            <div class="sppd-stop-line">
              <span style="display: inline-block; width: 102px;">Ke</span>
              <span>: <?= esc($stop['ke']); ?></span>
            </div>
            <div class="sppd-stop-line">
              <span style="display: inline-block; width: 102px;">Pada Tanggal</span>
              <span>: <?= esc($stop['tgl_berangkat_label']); ?></span>
            </div>
            <?php if ($pejabat !== null) : ?>
              <div class="sppd-pejabat-box">
                <div class="sppd-pejabat-jabatan"><?= esc($pejabat['jabatan']); ?></div>
                <div class="sppd-pejabat-ttd"><br><br><?= $pejabat['placeholder']; ?></div>
                <div class="sppd-pejabat-identity">
                  <div><strong><u><?= esc($pejabat['nama']); ?></u></strong></div>
                  <div><?= $pejabat['nip'] !== '' ? esc(\App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($pejabat['nip'])) : '&nbsp;'; ?></div>
                </div>
              </div>
            <?php else : ?>
              <div class="sppd-pejabat-box">&nbsp;</div>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td style="width: 50%;">
          <span style="margin-right: 8px; position: absolute;">V</span>
          <div style="display: inline; margin-left: 25px; padding-right: 69px;">Tiba di</div>
          <div style="display: inline;">: Terlampir</div>
          <div></div>
          <div style="display: inline; padding-right: 30px; margin-left: 26px;">Pada Tanggal</div>
          <div style="display: inline;">: Terlampir</div>
          <div>&nbsp;</div>
          <div>&nbsp;</div>
          <div style="width: 200px; margin-left: 18px; height: 40px;">Pejabat Pembuat Komitmen</div>
          <div style="height: 80px; text-align: left; vertical-align: middle; margin-left: 18px; padding-left: 30px;">
            <br><br>${ttd_pengirim1}
          </div>
          <div style="margin-left: 18px;"><strong><?= esc($ppkNama); ?></strong></div>
          <div style="margin-left: 18px;"><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
        <td>
          <div>Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya</div>
          <div>&nbsp;</div>
          <div style="width: 200px; height: 40px;">Pejabat Pembuat Komitmen</div>
          <div style="height: 80px; text-align: left; vertical-align: middle; padding-left: 30px;">
            <br><br>${ttd_pengirim1}
          </div>
          <div><strong><?= esc($ppkNama); ?></strong></div>
          <div><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="padding-top: 5px; padding-bottom: 5px;">
          <span style="margin-right: 8px; position: absolute;">VI</span>
          <div style="display: inline; margin-left: 25px; padding-right: 69px;">CATATAN LAIN-LAIN</div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="padding-top: 5px; padding-bottom: 5px; border: unset;">
          <span style="margin-right: 8px; position: absolute;">VII</span>
          <div style="margin-left: 25px; padding-right: 69px;"><u>PERHATIAN</u></div>
          <div style="padding-left: 15px; padding-top: 5px;">PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat / tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara apabila negara menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.</div>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
