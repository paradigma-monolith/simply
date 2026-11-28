<?php
if (!function_exists('name_capitalize')) {
    helper('tanggal');
}
$first = $perjalanan[0] ?? [];
$namaKantor = trim((string) ($first['nama_kantor'] ?? '')) !== ''
    ? (string) $first['nama_kantor']
    : 'Balai Kekarantinaan Kesehatan Kelas I Bandung';
$ppkNama = name_capitalize((string) ($first['ppk'] ?? ''));
$ppkNipLabel = \App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($first['ppk_nip'] ?? '');
$kepalaNama = name_capitalize((string) ($first['kepala_kantor'] ?? ''));
$kepalaNipLabel = \App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($first['kepala_kantor_nip'] ?? '');
$stopRows = \App\Libraries\SinadiSppdSrikandiHelper::buildRekapBackPageStops($srikandi_config ?? []);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <style>
    @page {
      margin: 0;
    }

    * {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 8pt;
    }

    body {
      margin: 18px 36px 0 36px;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .uppercase {
      text-transform: uppercase;
    }

    .bold {
      font-weight: bold;
    }

    .page-break {
      page-break-after: always;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    .spd-table td,
    .spd-table th {
      border: 1px solid #000;
      padding: 5px 8px;
      vertical-align: top;
      font-size: 8.5pt;
    }

    .spd-table ol {
      margin: 0;
      padding-left: 15px;
    }

    .spd-table ol li {
      font-size: 8.5pt;
      margin-bottom: 1px;
    }

    .unset-table td {
      border: none;
      padding: 1px 2px;
      font-size: 8pt;
      vertical-align: top;
    }

    .rekap-back {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
    }

    .rekap-back td {
      border: 1px solid #000;
      padding: 3px 6px;
      vertical-align: top;
      font-size: 8pt;
      line-height: 1.25;
    }

    .stop-line {
      height: 13px;
      line-height: 13px;
      overflow: hidden;
      white-space: nowrap;
      font-size: 8pt;
    }

    .stop-label {
      display: inline-block;
      width: 88px;
      margin-left: 18px;
    }

    .stop-label-right {
      display: inline-block;
      width: 88px;
    }

    .roman-badge {
      position: absolute;
      font-weight: bold;
      font-size: 8pt;
    }

    .pejabat-box {
      height: 88px;
      overflow: hidden;
      text-align: center;
      margin-top: 2px;
    }

    .pejabat-jabatan {
      height: 18px;
      line-height: 9px;
      overflow: hidden;
      font-weight: bold;
      font-size: 7.5pt;
    }

    .pejabat-ttd {
      height: 46px;
      line-height: 46px;
      font-size: 8pt;
    }

    .pejabat-identity {
      height: 22px;
      line-height: 11px;
      overflow: hidden;
      font-size: 7.5pt;
    }

    .blank-stop-box {
      height: 88px;
    }

    .row5-blank-box {
      height: 62px;
    }
  </style>
</head>

<body>
  <!-- HALAMAN 1: SURAT PERJALANAN DINAS (SPD) KOLEKTIF -->
  <div>
    <div class="right uppercase" style="margin-top: 12px; margin-bottom: 12px; font-size: 8pt;">
      Lampiran 1
    </div>

    <table class="unset-table" style="margin-bottom: 10px;">
      <tr>
        <td style="width: 55%; text-align: center;">
          <div class="uppercase" style="font-size: 8.5pt;">KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</div>
          <?php if ((int) date('Y') >= 2025) : ?>
            <div class="uppercase" style="font-size: 8.5pt;">DIREKTORAT JENDERAL PENANGGULANGAN PENYAKIT</div>
          <?php else : ?>
            <div class="uppercase" style="font-size: 8.5pt;">DIREKTORAT JENDERAL PENCEGAHAN DAN PENGENDALIAN PENYAKIT</div>
          <?php endif; ?>
          <div class="uppercase" style="font-size: 8.5pt; width: 250px; margin: 0 auto;"><?= esc($namaKantor); ?></div>
        </td>
        <td style="width: 45%; padding-left: 16px;">
          <div style="font-size: 7pt;">Keputusan Menteri Keuangan Republik Indonesia</div>
          <div style="font-size: 7pt;"><?= (int) date('Y') >= 2024 ? 'Nomor 119 Tahun 2023' : 'Nomor 113/PMK.05/2012'; ?></div>
          <div style="font-size: 7pt;">Tentang Perjalanan dinas jabatan dalam negeri</div>
          <div style="font-size: 7pt;">Bagi Pejabat Negara, Pegawai Negeri dan Pegawai Tidak Tetap</div>
        </td>
      </tr>
    </table>

    <table class="unset-table" style="margin-bottom: 10px;">
      <tr>
        <td style="width: 55%;">&nbsp;</td>
        <td style="width: 45%; padding-left: 16px;">
          <table class="unset-table">
            <tr>
              <td style="width: 72px; font-size: 8pt;">Lembar ke</td>
              <td style="width: 10px; font-size: 8pt;">:</td>
              <td></td>
            </tr>
            <tr>
              <td style="font-size: 8pt;">Kode No</td>
              <td style="font-size: 8pt;">:</td>
              <td></td>
            </tr>
            <tr>
              <td style="font-size: 8pt;">Nomor</td>
              <td style="font-size: 8pt;">:</td>
              <td style="font-size: 8pt;">${nomor_naskah}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <div class="uppercase center" style="margin-top: 8px; margin-bottom: 12px;">
      <strong><u style="font-size: 9.5pt;">SURAT PERJALANAN DINAS (SPD)</u></strong>
    </div>

    <table class="spd-table">
      <tr>
        <td class="center" style="width: 5%;">1.</td>
        <td style="width: 46%;">Pejabat Pembuat Komitmen</td>
        <td colspan="2" style="width: 49%;"><?= esc($ppkNama); ?></td>
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
        <td>
          <ol type="a">
            <li>Pangkat dan golongan menurut</li>
            <li>Jabatan</li>
            <li>Tingkat Biaya perjalanan dinas</li>
          </ol>
        </td>
        <td colspan="2">
          <ol type="a">
            <li>Terlampir</li>
            <li>Terlampir</li>
            <li>Terlampir</li>
          </ol>
        </td>
      </tr>
      <tr>
        <td class="center">4.</td>
        <td>Maksud Perjalanan Dinas</td>
        <td colspan="2"><?= esc(name_capitalize((string) ($first['nama_kegiatan'] ?? ''))); ?></td>
      </tr>
      <tr>
        <td class="center">5.</td>
        <td>Alat angkutan yang dipergunakan</td>
        <td colspan="2">Terlampir</td>
      </tr>
      <tr>
        <td class="center">6.</td>
        <td>
          <ol type="a">
            <li>Tempat berangkat</li>
            <li>Tempat tujuan</li>
          </ol>
        </td>
        <td colspan="2">
          <ol type="a">
            <li>Terlampir</li>
            <li>Terlampir</li>
          </ol>
        </td>
      </tr>
      <tr>
        <td class="center">7.</td>
        <td>
          <ol type="a">
            <li>Lamanya Perjalanan Dinas</li>
            <li>Tanggal berangkat</li>
            <li>Tanggal harus kembali / tiba di tempat baru</li>
          </ol>
        </td>
        <td colspan="2">
          <ol type="a">
            <li>Terlampir</li>
            <li>Terlampir</li>
            <li>Terlampir</li>
          </ol>
        </td>
      </tr>
      <tr>
        <td class="center" style="padding: 4px 8px;">8.</td>
        <td style="padding: 4px 8px;">
          <span style="display: inline-block; width: 70px;">Pengikut</span>
          <span>Nama</span>
        </td>
        <td class="center" style="width: 17%; padding: 4px 6px;">Tanggal Lahir</td>
        <td style="width: 32%; padding: 4px 8px;">Keterangan</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="padding-top: 4px; padding-bottom: 6px;">
          <div>1.</div>
          <div>2.</div>
          <div>3.</div>
          <div>4.</div>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="center">9.</td>
        <td>
          <div>Pembebanan Anggaran</div>
          <ol type="a" style="padding-left: 24px;">
            <li>Instansi</li>
            <li>Akun</li>
          </ol>
        </td>
        <td colspan="2">
          <div>&nbsp;</div>
          <ol type="a">
            <li><?= esc($namaKantor); ?></li>
            <li><?= esc((string) ($first['mata_anggaran'] ?? '')); ?></li>
          </ol>
        </td>
      </tr>
      <tr>
        <td class="center">10.</td>
        <td>Keterangan lain-lain</td>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>

    <table class="unset-table" style="margin-top: 16px;">
      <tr>
        <td style="width: 56%;">&nbsp;</td>
        <td style="width: 15%; font-size: 8.5pt;">Dikeluarkan di</td>
        <td style="width: 29%; font-size: 8.5pt;">: Bandung</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="border-bottom: 1px solid #000; padding-bottom: 3px; font-size: 8.5pt;">Pada tanggal</td>
        <td style="border-bottom: 1px solid #000; padding-bottom: 3px; font-size: 8.5pt;">: ${tanggal_naskah}</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" style="padding-top: 5px; font-size: 8.5pt;">Pejabat Pembuat Komitmen</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" style="height: 82px; vertical-align: middle; padding-left: 24px; font-size: 8.5pt;">
          ${ttd_pengirim1}
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" style="font-size: 8.5pt;">
          <div><strong><?= esc($ppkNama); ?></strong></div>
          <div><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
      </tr>
    </table>
  </div>

  <div class="page-break"></div>

  <!-- HALAMAN 2: HALAMAN BELAKANG / VISUM KOLEKTIF -->
  <div style="margin-top: 18px;">
    <table class="rekap-back">
      <!-- BARIS I -->
      <tr>
        <td style="width: 50%;">&nbsp;</td>
        <td style="width: 50%;">
          <div class="stop-line"><span class="stop-label-right">Berangkat dari</span><span>: Terlampir</span></div>
          <div style="font-size: 7.5pt; margin-bottom: 1px;">(Tempat kedudukan)</div>
          <div class="stop-line"><span class="stop-label-right">Pada Tanggal</span><span>: Terlampir</span></div>
          <div class="stop-line"><span class="stop-label-right">Ke</span><span>: Terlampir</span></div>
          <div style="margin-top: 3px; font-size: 8pt;">Kepala <?= esc($namaKantor); ?></div>
          <div style="height: 54px; line-height: 54px; padding-left: 24px; font-size: 8pt;">
            ${ttd_pengirim2}
          </div>
          <div style="font-size: 8pt;"><strong><?= esc($kepalaNama); ?></strong></div>
          <div style="font-size: 8pt;"><strong><?= esc($kepalaNipLabel); ?></strong></div>
        </td>
      </tr>

      <!-- BARIS II, III, IV -->
      <?php foreach ($stopRows as $stop) :
        $pejabat = $stop['pejabat'];
      ?>
        <tr>
          <td style="width: 50%;">
            <div class="stop-line">
              <span class="roman-badge"><?= esc($stop['roman']); ?></span>
              <span class="stop-label">Tiba di</span>
              <span>: <?= esc($stop['tiba_di']); ?></span>
            </div>
            <div class="stop-line">
              <span class="stop-label">Pada tanggal</span>
              <span>: <?= esc($stop['tgl_tiba_label']); ?></span>
            </div>
            <?php if ($pejabat !== null) : ?>
              <div class="pejabat-box">
                <div class="pejabat-jabatan"><?= esc($pejabat['jabatan']); ?></div>
                <div class="pejabat-ttd"><?= $pejabat['placeholder']; ?></div>
                <div class="pejabat-identity">
                  <div><strong><u><?= esc($pejabat['nama']); ?></u></strong></div>
                  <div><?= $pejabat['nip'] !== '' ? esc(\App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($pejabat['nip'])) : '&nbsp;'; ?></div>
                </div>
              </div>
            <?php else : ?>
              <div class="blank-stop-box">
                <?php if ($stop['roman'] === 'IV') : ?>
                  <div class="stop-line"><span class="stop-label">Kepala</span><span>:</span></div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </td>
          <td style="width: 50%;">
            <div class="stop-line">
              <span class="stop-label-right">Berangkat dari</span>
              <span>: <?= esc($stop['berangkat_dari']); ?></span>
            </div>
            <div class="stop-line">
              <span class="stop-label-right">Ke</span>
              <span>: <?= esc($stop['ke']); ?></span>
            </div>
            <div class="stop-line">
              <span class="stop-label-right">Pada Tanggal</span>
              <span>: <?= esc($stop['tgl_berangkat_label']); ?></span>
            </div>
            <?php if ($pejabat !== null) : ?>
              <div class="pejabat-box" style="margin-top: -11px;">
                <div class="pejabat-jabatan"><?= esc($pejabat['jabatan']); ?></div>
                <div class="pejabat-ttd"><?= $pejabat['placeholder']; ?></div>
                <div class="pejabat-identity">
                  <div><strong><u><?= esc($pejabat['nama']); ?></u></strong></div>
                  <div><?= $pejabat['nip'] !== '' ? esc(\App\Libraries\SinadiSppdSrikandiHelper::formatNipLabel($pejabat['nip'])) : '&nbsp;'; ?></div>
                </div>
              </div>
            <?php else : ?>
              <div class="blank-stop-box" style="margin-top: -11px;">
                <?php if ($stop['roman'] === 'IV') : ?>
                  <div class="stop-line" style="margin-top: 13px;"><span class="stop-label-right">Kepala</span><span>:</span></div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>

      <!-- BARIS V -->
      <tr>
        <td style="width: 50%;">
          <div class="stop-line">
            <span class="roman-badge">V</span>
            <span class="stop-label">Tiba di</span>
            <span>:</span>
          </div>
          <div class="stop-line"><span class="stop-label">Pada tanggal</span><span>:</span></div>
          <div class="stop-line"><span class="stop-label">Kepala</span><span>:</span></div>
          <div class="row5-blank-box">&nbsp;</div>
        </td>
        <td style="width: 50%;">
          <div class="stop-line"><span class="stop-label-right">Berangkat dari</span><span>:</span></div>
          <div class="stop-line"><span class="stop-label-right">Ke</span><span>:</span></div>
          <div class="stop-line"><span class="stop-label-right">Pada Tanggal</span><span>:</span></div>
          <div class="stop-line"><span class="stop-label-right">Kepala</span><span>:</span></div>
          <div class="row5-blank-box" style="height: 49px;">&nbsp;</div>
        </td>
      </tr>

      <!-- BARIS VI -->
      <tr>
        <td style="width: 50%;">
          <div class="stop-line">
            <span class="roman-badge">VI</span>
            <span class="stop-label">Tiba di</span>
            <span>: Terlampir</span>
          </div>
          <div class="stop-line">
            <span class="stop-label">Pada tanggal</span>
            <span>: Terlampir</span>
          </div>
          <div style="margin-left: 18px; margin-top: 4px; font-size: 8pt;">Pejabat Pembuat Komitmen</div>
          <div style="height: 50px; line-height: 50px; margin-left: 18px; padding-left: 20px; font-size: 8pt;">
            ${ttd_pengirim1}
          </div>
          <div style="margin-left: 18px; font-size: 8pt;"><strong><?= esc($ppkNama); ?></strong></div>
          <div style="margin-left: 18px; font-size: 8pt;"><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
        <td style="width: 50%;">
          <div style="font-size: 7.5pt; line-height: 1.25;">
            Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya
          </div>
          <div style="margin-top: 4px; font-size: 8pt;">Pejabat Pembuat Komitmen</div>
          <div style="height: 44px; line-height: 44px; padding-left: 20px; font-size: 8pt;">
            ${ttd_pengirim1}
          </div>
          <div style="font-size: 8pt;"><strong><?= esc($ppkNama); ?></strong></div>
          <div style="font-size: 8pt;"><strong><?= esc($ppkNipLabel); ?></strong></div>
        </td>
      </tr>

      <!-- BARIS VII -->
      <tr>
        <td colspan="2" style="padding: 4px 6px;">
          <span class="roman-badge">VII</span>
          <span style="margin-left: 18px; font-weight: bold;">CATATAN LAIN-LAIN</span>
        </td>
      </tr>

      <!-- BARIS VIII -->
      <tr>
        <td colspan="2" style="border: none; padding: 4px 6px 0 6px;">
          <div style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">VIII. PERHATIAN</div>
          <div style="padding-left: 14px; font-size: 7.5pt; line-height: 1.25;">
            PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat / tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara apabila negara menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.
          </div>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
