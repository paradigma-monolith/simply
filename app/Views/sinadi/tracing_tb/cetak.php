<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Checklist Verifikasi Tracing TB — <?= esc($record['lokus']); ?></title>
  <style>
    @page { size: A4 portrait; margin: 10mm; }
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 9pt;
      color: #111;
      margin: 0;
      padding: 12px;
    }
    .no-print {
      margin-bottom: 12px;
      text-align: right;
    }
    .no-print button {
      padding: 6px 14px;
      background: #0d6efd;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 10pt;
    }
    @media print {
      .no-print { display: none; }
      body { padding: 0; }
    }
    h2 {
      font-size: 11pt;
      text-align: center;
      margin: 0 0 4px 0;
    }
    .sub-header {
      text-align: center;
      font-size: 9.5pt;
      margin-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    th, td {
      border: 1px solid #333;
      padding: 3px 5px;
      vertical-align: top;
    }
    th {
      background: #eef3f7;
      font-weight: bold;
    }
    .bg-akun {
      background: #dcebf7;
      font-weight: bold;
    }
    .bg-sub {
      background: #f5f5f5;
      font-weight: bold;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .sig-table {
      table-layout: fixed;
      page-break-inside: avoid;
    }
    .sig-table > tbody > tr > td {
      width: 50%;
      padding: 7px 10px;
      font-size: 8.5pt;
      vertical-align: top;
    }
    .sig-title {
      display: block;
      text-align: center;
      font-weight: bold;
      font-size: 9pt;
      padding-bottom: 4px;
      margin-bottom: 5px;
      border-bottom: 1px solid #cbd5e1;
    }
    .sig-inner {
      width: 100%;
      border: none;
      margin: 0;
    }
    .sig-inner td {
      border: none;
      padding: 2px 3px;
      vertical-align: top;
    }
    .check-box {
      display: inline-block;
      width: 12px;
      height: 12px;
      border: 1px solid #333;
      text-align: center;
      line-height: 12px;
      font-size: 8.5pt;
      font-weight: bold;
      margin-right: 4px;
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <div class="no-print">
    <button onclick="window.print()">Cetak / Simpan PDF</button>
  </div>

  <h2>Form Checklist Verifikasi Kebenaran dan Kelengkapan Dokumen Pertanggungjawaban<br>Penemuan Kasus TBC Terintegrasi CKG</h2>
  <div class="sub-header">
    di Lokus <strong><?= esc($record['lokus']); ?></strong> &mdash;
    <strong><?= esc($record['instansi']); ?></strong> &mdash;
    Tanggal <strong><?= !empty($record['tanggal_kegiatan']) ? esc(mediumdate_indo($record['tanggal_kegiatan'])) : '....................'; ?></strong>
  </div>

  <table>
    <tr class="bg-akun">
      <td width="16%"><?= esc($record['kode_kegiatan']); ?></td>
      <td colspan="4"><?= esc($record['nama_kegiatan']); ?></td>
      <td width="16%" class="text-right">Rp <?= number_format((int) $record['total_anggaran'], 0, ',', '.'); ?></td>
    </tr>

    <?php foreach ($accounts as $kodeAkun => $akun) : ?>
      <tr class="bg-sub">
        <td><?= esc($kodeAkun); ?></td>
        <td colspan="4"><?= esc($akun['nama']); ?></td>
        <td class="text-right">Rp <?= number_format((int) ($rincian['subtotals'][$kodeAkun] ?? 0), 0, ',', '.'); ?></td>
      </tr>
      <?php foreach ($akun['anggaran'] as $item) :
        $val = $rincian['items'][$item['kode']];
      ?>
        <tr>
          <td><small><?= esc($item['catatan']); ?></small></td>
          <td width="28%">&ndash; <?= esc($item['nama']); ?></td>
          <td width="22%" class="text-center">
            <?= esc($val['vol_or']); ?> <?= esc($item['satuan_or']); ?> &times;
            <?= esc($val['vol_waktu']); ?> <?= esc($item['satuan_waktu']); ?> &times;
            <?= esc($val['vol_lokus']); ?> <?= esc($item['satuan_lokus']); ?>
          </td>
          <td width="8%" class="text-center"><?= esc($val['total_vol']); ?> <?= esc($item['satuan_total']); ?></td>
          <td width="10%" class="text-right"><?= number_format((int) $val['harga_satuan'], 0, ',', '.'); ?></td>
          <td class="text-right"><?= number_format((int) $val['subtotal'], 0, ',', '.'); ?></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <th colspan="2">Kebutuhan Dokumen Klaim dan Pertanggungjawaban</th>
        <th class="text-center">Status File</th>
        <th class="text-center">Cek BKK</th>
        <th colspan="2">Catatan Verifikator BKK</th>
      </tr>
      <?php foreach ($akun['dokumen'] as $doc) :
        $saved = $dokumenMap[$doc['kode']] ?? null;
        $hasFile = !empty($saved['nama_file']);
        $chkBkk = !empty($saved['checklist_bkk']);
        $noteBkk = trim((string) ($saved['catatan_bkk'] ?? ''));
      ?>
        <tr>
          <td><small><?= esc($doc['kategori']); ?></small></td>
          <td><?= esc($doc['nama']); ?></td>
          <td class="text-center"><?= $hasFile ? 'Terlampir' : '-'; ?></td>
          <td class="text-center"><?= $chkBkk ? '&#10003;' : ''; ?></td>
          <td colspan="2"><small><?= esc($noteBkk); ?></small></td>
        </tr>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </table>

  <table class="sig-table">
    <tr>
      <td>
        <span class="sig-title">Diajukan oleh (Pengaju)</span>
        <table class="sig-inner">
          <tr>
            <td width="30%">Tanggal</td>
            <td>: <?= !empty($record['tgl_ttd_pengaju']) ? esc(mediumdate_indo($record['tgl_ttd_pengaju'])) : (!empty($record['tanggal_kegiatan']) ? esc(mediumdate_indo($record['tanggal_kegiatan'])) : '............................'); ?></td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>: <?= esc($record['nama_pengaju_ttd'] ?: ($record['nama_pengaju'] ?: '............................')); ?></td>
          </tr>
          <tr>
            <td>Tanda Tangan</td>
            <td>:
              <?php if (!empty($record['ttd_pengaju'])) : ?>
                <br><img src="<?= esc($record['ttd_pengaju']); ?>" alt="TTD Pengaju" style="max-height: 60px; max-width: 100%; margin-top: 4px;">
              <?php else : ?>
                <div style="height: 54px;"></div>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </td>
      <td>
        <span class="sig-title">Diperiksa oleh Verifikator BKK</span>
        <table class="sig-inner">
          <tr>
            <td colspan="2">
              <span class="check-box"><?= ($record['status_verif_bkk'] ?? '') === 'ACC' ? '&#10003;' : '&nbsp;'; ?></span> LENGKAP &amp; SESUAI (ACC)
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <span class="check-box"><?= ($record['status_verif_bkk'] ?? '') === 'PERBAIKAN' ? '&#10003;' : '&nbsp;'; ?></span> TIDAK LENGKAP/TIDAK SESUAI (PERBAIKAN)
            </td>
          </tr>
          <tr>
            <td width="28%">Tanggal</td>
            <td>: <?= !empty($record['tgl_verif_bkk']) ? esc(mediumdate_indo($record['tgl_verif_bkk'])) : '............................'; ?></td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>: <?= esc($record['nama_verif_bkk'] ?: '............................'); ?></td>
          </tr>
          <tr>
            <td>No HP</td>
            <td>: <?= esc($record['no_hp_verif_bkk'] ?: '............................'); ?></td>
          </tr>
          <tr>
            <td>Paraf</td>
            <td>:
              <?php if (!empty($record['paraf_verif_bkk'])) : ?>
                <br><img src="<?= esc($record['paraf_verif_bkk']); ?>" alt="Paraf BKK" style="max-height: 52px; max-width: 100%; margin-top: 4px;">
              <?php else : ?>
                <div style="height: 44px;"></div>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <span class="sig-title">Diperiksa oleh verifikator pusat</span>
        <table class="sig-inner">
          <tr>
            <td width="38%">LENGKAP &amp; SESUAI (ACC)</td>
            <td width="2%">:</td>
            <td><span class="check-box">&nbsp;</span></td>
          </tr>
          <tr>
            <td>TIDAK LENGKAP/TIDAK SESUAI</td>
            <td>:</td>
            <td><span class="check-box">&nbsp;</span></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>................................................................</td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>:</td>
            <td>................................................................</td>
          </tr>
          <tr>
            <td>Paraf</td>
            <td>:</td>
            <td><div style="height: 48px;"></div></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
