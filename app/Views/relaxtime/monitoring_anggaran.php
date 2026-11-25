<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<?php
$summary = $stats['summary'] ?? [];
$byAkun = $stats['by_akun'] ?? [];
$byPic = $stats['by_pic'] ?? [];
$byMonth = $stats['by_month'] ?? [];

$bulanMulai = $filters['bulan_mulai'] ?? '';
$bulanSelesai = $filters['bulan_selesai'] ?? '';
$akunFilter = $filters['akun'] ?? '';
$picFilter = $filters['pic'] ?? '';
$allMonths = ($filters['all_months'] ?? '0') === '1';

$exportQuery = http_build_query([
    'bulan_mulai' => $bulanMulai,
    'bulan_selesai' => $bulanSelesai,
    'akun' => $akunFilter,
    'pic' => $picFilter,
    'all_months' => $allMonths ? '1' : '0',
]);

$allRowsJson = [];
foreach ($allDateRows as $r) {
    $allRowsJson[] = [
        'id' => (int) ($r['id_monitoring'] ?? 0),
        'tanggal' => (string) ($r['tanggal'] ?? ''),
        'ro' => (string) ($r['ro'] ?? ''),
        'akun' => (string) ($r['akun'] ?? ''),
        'kegiatan' => (string) ($r['kegiatan'] ?? ''),
        'rencana' => (int) ($r['rencana'] ?? 0),
        'pic' => (string) ($r['pic'] ?? ''),
        'realisasi' => (int) ($r['realisasi'] ?? 0),
        'sumber_input' => (string) ($r['sumber_input'] ?? 'manual'),
    ];
}
?>

<style>
  .rma-page {
    --rma-teal: #0f766e;
    --rma-teal-dark: #115e59;
    --rma-teal-soft: #f0fdfa;
    --rma-border: #e2e8f0;
    --rma-text: #0f172a;
    --rma-muted: #64748b;
  }
  .rma-hero {
    background: linear-gradient(135deg, #0f766e 0%, #0d9488 55%, #14b8a6 100%);
    color: #fff;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 16px;
    box-shadow: 0 10px 24px -10px rgba(15, 118, 110, 0.45);
  }
  .rma-hero-title {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.01em;
  }
  .rma-hero-sub {
    font-size: 0.85rem;
    opacity: 0.92;
    margin: 4px 0 0;
  }
  .rma-hero .btn-hero-light {
    background: #fff;
    color: #0f766e;
    font-weight: 600;
    border: none;
    border-radius: 8px;
  }
  .rma-hero .btn-hero-light:hover {
    background: #f0fdfa;
    color: #115e59;
  }
  .rma-hero .btn-hero-outline {
    background: rgba(255, 255, 255, 0.14);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.35);
    font-weight: 600;
    border-radius: 8px;
  }
  .rma-hero .btn-hero-outline:hover {
    background: rgba(255, 255, 255, 0.24);
    color: #fff;
  }
  .rma-card {
    background: #fff;
    border: 1px solid var(--rma-border);
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    margin-bottom: 16px;
  }
  .rma-filter-label {
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--rma-muted);
    margin-bottom: 4px;
  }
  .rma-kpi-card {
    background: #fff;
    border: 1px solid var(--rma-border);
    border-radius: 12px;
    padding: 16px 18px;
    height: 100%;
    position: relative;
    overflow: hidden;
  }
  .rma-kpi-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--kpi-accent, #0f766e);
  }
  .rma-kpi-label {
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--rma-muted);
    margin-bottom: 6px;
  }
  .rma-kpi-value {
    font-size: 1.32rem;
    font-weight: 700;
    color: var(--rma-text);
    line-height: 1.2;
  }
  .rma-kpi-sub {
    font-size: 0.78rem;
    color: var(--rma-muted);
    margin-top: 6px;
  }
  .rma-akun-card {
    background: #fff;
    border: 1px solid var(--rma-border);
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: all 0.16s ease;
    height: 100%;
  }
  .rma-akun-card:hover,
  .rma-akun-card.is-selected {
    border-color: var(--rma-teal);
    box-shadow: 0 6px 16px -6px rgba(15, 118, 110, 0.28);
  }
  .rma-akun-card.is-selected {
    background: var(--rma-teal-soft);
  }
  .rma-badge-akun {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 700;
  }
  .rma-akun-51 { background: #dbeafe; color: #1d4ed8; }
  .rma-akun-52 { background: #ccfbf1; color: #0f766e; }
  .rma-akun-53 { background: #fef3c7; color: #b45309; }
  .rma-chart-wrap {
    position: relative;
    height: 280px;
  }
  .rma-table th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #334155;
    background: #f8fafc;
    white-space: nowrap;
  }
  .rma-table td {
    font-size: 0.86rem;
    vertical-align: middle;
  }
  .rma-sisa-neg {
    color: #dc2626;
    font-weight: 700;
  }
  .rma-sisa-pos {
    color: #0f766e;
    font-weight: 600;
  }
  .rma-pic-row-filter {
    cursor: pointer;
  }
  .rma-pic-row-filter:hover {
    background-color: #f0fdfa !important;
  }
</style>

<section class="content rma-page">
  <div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <!-- Hero -->
    <div class="rma-hero d-flex flex-wrap justify-content-between align-items-center" style="gap: 12px;">
      <div>
        <h1 class="rma-hero-title">
          <i class="fas fa-chart-line mr-2"></i>Monitoring Anggaran
        </h1>
        <p class="rma-hero-sub">
          Pantau Rencana Pencairan &amp; Realisasi Anggaran — fokus kolom <strong>A (RO)</strong>, <strong>B (Akun)</strong>, <strong>C (Kegiatan)</strong>, <strong>D (Rencana)</strong>, <strong>PIC</strong>, dan <strong>K (Realisasi)</strong>.
        </p>
      </div>
      <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
        <button type="button" class="btn btn-hero-light btn-sm px-3 py-2" id="btnInputManual">
          <i class="fas fa-plus mr-1"></i> Input Manual
        </button>
        <button type="button" class="btn btn-hero-outline btn-sm px-3 py-2" id="btnImportExcel">
          <i class="fas fa-file-import mr-1"></i> Import Excel
        </button>
        <a href="/relaxtime/monitoring-anggaran/export?<?= $exportQuery ?>" class="btn btn-hero-outline btn-sm px-3 py-2" id="btnExportExcel">
          <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
      </div>
    </div>

    <!-- Filter Bar -->
    <div class="rma-card p-3">
      <form id="filterForm" method="GET" action="/relaxtime/monitoring-anggaran" class="row align-items-end">
        <div class="col-6 col-md-2 mb-2 mb-md-0">
          <label for="filterBulanMulai" class="rma-filter-label">Bulan Mulai</label>
          <input type="month" class="form-control form-control-sm rma-month-input" id="filterBulanMulai" name="bulan_mulai" value="<?= esc($bulanMulai) ?>">
        </div>
        <div class="col-6 col-md-2 mb-2 mb-md-0">
          <label for="filterBulanSelesai" class="rma-filter-label">Bulan Selesai</label>
          <input type="month" class="form-control form-control-sm rma-month-input" id="filterBulanSelesai" name="bulan_selesai" value="<?= esc($bulanSelesai) ?>">
        </div>
        <div class="col-6 col-md-2 mb-2 mb-md-0">
          <label for="filterAkun" class="rma-filter-label">Akun</label>
          <select class="form-control form-control-sm rma-client-filter" id="filterAkun" name="akun">
            <option value="">Semua Akun</option>
            <option value="51" <?= $akunFilter === '51' ? 'selected' : '' ?>>51 - Belanja Pegawai</option>
            <option value="52" <?= $akunFilter === '52' ? 'selected' : '' ?>>52 - Belanja Barang</option>
            <option value="53" <?= $akunFilter === '53' ? 'selected' : '' ?>>53 - Belanja Modal</option>
          </select>
        </div>
        <div class="col-6 col-md-3 mb-2 mb-md-0">
          <label for="filterPic" class="rma-filter-label">PIC</label>
          <select class="form-control form-control-sm rma-client-filter" id="filterPic" name="pic">
            <option value="">Semua PIC</option>
            <?php foreach ($picOptions as $p): ?>
              <option value="<?= esc($p) ?>" <?= strcasecmp($picFilter, $p) === 0 ? 'selected' : '' ?>><?= esc($p) ?></option>
            <?php endforeach; ?>
            <option value="Belum Ditentukan" <?= strcasecmp($picFilter, 'Belum Ditentukan') === 0 ? 'selected' : '' ?>>Belum Ditentukan</option>
          </select>
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center justify-content-md-end" style="gap: 8px;">
          <input type="hidden" name="all_months" id="filterAllMonths" value="<?= $allMonths ? '1' : '0' ?>">
          <button type="button" class="btn btn-sm <?= $allMonths ? 'btn-teal bg-teal text-white' : 'btn-outline-secondary' ?>" id="btnToggleAllMonths" title="Tampilkan seluruh bulan">
            <i class="far fa-calendar-alt mr-1"></i> Semua Bulan
          </button>
          <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetFilter">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>
        </div>
      </form>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-3">
      <div class="col-12 col-sm-6 col-lg-3 mb-2 mb-lg-0">
        <div class="rma-kpi-card" style="--kpi-accent: #0284c7;">
          <div class="rma-kpi-label">Total Rencana (Kolom D)</div>
          <div class="rma-kpi-value" id="kpiTotalRencana">Rp <?= number_format((int) ($summary['total_rencana'] ?? 0), 0, ',', '.') ?></div>
          <div class="rma-kpi-sub" id="kpiTotalKegiatan"><?= (int) ($summary['total_kegiatan'] ?? 0) ?> item kegiatan</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3 mb-2 mb-lg-0">
        <div class="rma-kpi-card" style="--kpi-accent: #0f766e;">
          <div class="rma-kpi-label">Total Realisasi (Kolom K)</div>
          <div class="rma-kpi-value text-teal" id="kpiTotalRealisasi">Rp <?= number_format((int) ($summary['total_realisasi'] ?? 0), 0, ',', '.') ?></div>
          <div class="rma-kpi-sub" id="kpiTerealisasiCount"><?= (int) ($summary['terealisasi_count'] ?? 0) ?> kegiatan sudah terealisasi</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3 mb-2 mb-lg-0">
        <div class="rma-kpi-card" style="--kpi-accent: #d97706;">
          <div class="rma-kpi-label">Sisa Anggaran</div>
          <?php $sisaVal = (int) ($summary['total_sisa'] ?? 0); ?>
          <div class="rma-kpi-value <?= $sisaVal < 0 ? 'text-danger' : '' ?>" id="kpiTotalSisa">
            <?= $sisaVal < 0 ? '-' : '' ?>Rp <?= number_format(abs($sisaVal), 0, ',', '.') ?>
          </div>
          <div class="rma-kpi-sub" id="kpiOverBudgetCount"><?= (int) ($summary['over_budget_count'] ?? 0) ?> kegiatan melebihi rencana</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="rma-kpi-card" style="--kpi-accent: #16a34a;">
          <div class="rma-kpi-label">Capaian Serapan</div>
          <div class="rma-kpi-value" id="kpiPersenRealisasi"><?= number_format((float) ($summary['persen_realisasi'] ?? 0), 2, ',', '.') ?>%</div>
          <div class="progress mt-2" style="height: 6px;">
            <div class="progress-bar bg-success" id="kpiPersenBar" style="width: <?= min(100, (float) ($summary['persen_realisasi'] ?? 0)) ?>%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan per Akun (51, 52, 53) -->
    <div class="row mb-3">
      <?php
      $akunStyles = [
          '51' => ['badge' => 'rma-akun-51', 'bar' => 'bg-primary', 'title' => 'Akun 51 — Belanja Pegawai'],
          '52' => ['badge' => 'rma-akun-52', 'bar' => 'bg-teal', 'title' => 'Akun 52 — Belanja Barang'],
          '53' => ['badge' => 'rma-akun-53', 'bar' => 'bg-warning', 'title' => 'Akun 53 — Belanja Modal'],
      ];
      foreach ($akunStyles as $code => $cfg):
          $ak = $byAkun[$code] ?? ['rencana' => 0, 'realisasi' => 0, 'sisa' => 0, 'persen' => 0, 'count' => 0];
      ?>
        <div class="col-12 col-md-4 mb-2 mb-md-0">
          <div class="rma-akun-card <?= $akunFilter === $code ? 'is-selected' : '' ?>" data-akun-card="<?= $code ?>" title="Klik untuk filter Akun <?= $code ?>">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="rma-badge-akun <?= $cfg['badge'] ?>"><?= $cfg['title'] ?></span>
              <small class="text-muted font-weight-bold" id="akun<?= $code ?>Count"><?= (int) $ak['count'] ?> keg</small>
            </div>
            <div class="row text-sm">
              <div class="col-6">
                <div class="text-muted" style="font-size: 0.74rem;">Rencana</div>
                <div class="font-weight-bold" id="akun<?= $code ?>Rencana">Rp <?= number_format((int) $ak['rencana'], 0, ',', '.') ?></div>
              </div>
              <div class="col-6 text-right">
                <div class="text-muted" style="font-size: 0.74rem;">Realisasi</div>
                <div class="font-weight-bold text-teal" id="akun<?= $code ?>Realisasi">Rp <?= number_format((int) $ak['realisasi'], 0, ',', '.') ?></div>
              </div>
            </div>
            <div class="progress mt-2" style="height: 6px;">
              <div class="progress-bar <?= $cfg['bar'] ?>" id="akun<?= $code ?>Bar" style="width: <?= min(100, (float) $ak['persen']) ?>%"></div>
            </div>
            <div class="d-flex justify-content-between mt-1" style="font-size: 0.76rem;">
              <span class="text-muted" id="akun<?= $code ?>Sisa">Sisa: Rp <?= number_format((int) $ak['sisa'], 0, ',', '.') ?></span>
              <span class="font-weight-bold" id="akun<?= $code ?>Persen"><?= number_format((float) $ak['persen'], 1, ',', '.') ?>%</span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Charts -->
    <div class="row mb-3">
      <div class="col-12 col-lg-7 mb-3 mb-lg-0">
        <div class="rma-card p-3 h-100 mb-0">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold mb-0"><i class="fas fa-chart-bar text-teal mr-1"></i> Tren Rencana vs Realisasi per Bulan</h6>
            <small class="text-muted" id="chartMonthlySubtitle">Berdasarkan bulan anggaran</small>
          </div>
          <div class="rma-chart-wrap">
            <canvas id="chartMonthly"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-5">
        <div class="rma-card p-3 h-100 mb-0">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="font-weight-bold mb-0"><i class="fas fa-users text-teal mr-1"></i> Rencana vs Realisasi per PIC</h6>
            <small class="text-muted">Klik tabel PIC di bawah untuk filter</small>
          </div>
          <div class="rma-chart-wrap">
            <canvas id="chartPic"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan per PIC (Collapsible, Default Collapsed) -->
    <div class="rma-card">
      <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="cursor: pointer;" data-toggle="collapse" data-target="#collapsePicSummary" aria-expanded="false">
        <div>
          <span class="font-weight-bold"><i class="fas fa-layer-group text-teal mr-1"></i> Ringkasan Anggaran per PIC</span>
          <span class="badge badge-light border ml-2" id="badgePicCount"><?= count($byPic) ?> PIC</span>
        </div>
        <button type="button" class="btn btn-tool btn-sm"><i class="fas fa-chevron-down"></i></button>
      </div>
      <div id="collapsePicSummary" class="collapse border-top">
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0 rma-table">
            <thead>
              <tr>
                <th>PIC</th>
                <th class="text-center">Jumlah Kegiatan</th>
                <th class="text-right">Total Rencana</th>
                <th class="text-right">Total Realisasi</th>
                <th class="text-right">Sisa</th>
                <th class="text-right">Serapan</th>
              </tr>
            </thead>
            <tbody id="picSummaryBody">
              <?php if (empty($byPic)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data.</td></tr>
              <?php else: ?>
                <?php foreach ($byPic as $p): ?>
                  <tr class="rma-pic-row-filter" data-pic-name="<?= esc($p['pic']) ?>">
                    <td class="font-weight-bold"><?= esc($p['pic']) ?></td>
                    <td class="text-center"><?= (int) $p['count'] ?></td>
                    <td class="text-right">Rp <?= number_format((int) $p['rencana'], 0, ',', '.') ?></td>
                    <td class="text-right text-teal font-weight-bold">Rp <?= number_format((int) $p['realisasi'], 0, ',', '.') ?></td>
                    <td class="text-right <?= $p['sisa'] < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos' ?>">Rp <?= number_format((int) $p['sisa'], 0, ',', '.') ?></td>
                    <td class="text-right font-weight-bold"><?= number_format((float) $p['persen'], 1, ',', '.') ?>%</td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tabel Detail Kegiatan -->
    <div class="rma-card">
      <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
        <div>
          <h5 class="mb-0 font-weight-bold"><i class="fas fa-table text-teal mr-1"></i> Rincian Kegiatan Anggaran</h5>
          <small class="text-muted">Menampilkan kolom inti: RO (A), Akun (B), Kegiatan (C), Rencana (D), PIC, dan Realisasi (K)</small>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <button type="button" class="btn btn-outline-danger btn-sm d-none" id="btnBulkDelete">
            <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih (<span id="bulkDeleteCount">0</span>)
          </button>
        </div>
      </div>
      <div class="p-3">
        <div class="table-responsive">
          <table id="monitoringTable" class="table table-bordered table-hover table-sm rma-table w-100">
            <thead>
              <tr>
                <th style="width: 28px;" class="text-center"><input type="checkbox" id="checkAllRows"></th>
                <th style="width: 40px;" class="text-center">No</th>
                <th style="width: 118px;">Bulan</th>
                <th style="width: 120px;">RO (A)</th>
                <th style="width: 68px;" class="text-center">Akun (B)</th>
                <th>Kegiatan (C)</th>
                <th style="width: 110px;">PIC</th>
                <th class="text-right" style="width: 140px;">Rencana (D)</th>
                <th class="text-right" style="width: 140px;">Realisasi (K)</th>
                <th class="text-right" style="width: 135px;">Sisa</th>
                <th class="text-center" style="width: 72px;">%</th>
                <th class="text-center" style="width: 84px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="monitoringTableBody"></tbody>
            <tfoot>
              <tr class="bg-light font-weight-bold">
                <th colspan="7" class="text-right">TOTAL:</th>
                <th class="text-right" id="footTotalRencana">Rp 0</th>
                <th class="text-right text-teal" id="footTotalRealisasi">Rp 0</th>
                <th class="text-right" id="footTotalSisa">Rp 0</th>
                <th class="text-center" id="footTotalPersen">0%</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Input Manual / Edit -->
<div class="modal fade" id="modalManualAnggaran" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white">
        <h5 class="modal-title font-weight-bold" id="modalManualTitle">
          <i class="fas fa-plus-circle mr-1"></i> Input Manual Monitoring Anggaran
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formManualAnggaran">
        <div class="modal-body">
          <input type="hidden" id="formIdMonitoring" value="">
          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Bulan <span class="text-danger">*</span></label>
              <input type="month" class="form-control" id="formBulan" name="bulan" value="<?= date('Y-m') ?>" required>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">RO (Kolom A) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="formRo" name="ro" list="roDatalist" placeholder="mis. 7960.PEA.001" required>
              <datalist id="roDatalist">
                <?php foreach ($roOptions as $roOpt): ?>
                  <option value="<?= esc($roOpt) ?>">
                <?php endforeach; ?>
              </datalist>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Akun (Kolom B) <span class="text-danger">*</span></label>
              <select class="form-control" id="formAkun" name="akun" required>
                <option value="51">51 - Belanja Pegawai</option>
                <option value="52" selected>52 - Belanja Barang</option>
                <option value="53">53 - Belanja Modal</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Kegiatan (Kolom C) <span class="text-danger">*</span></label>
            <textarea class="form-control" id="formKegiatan" name="kegiatan" rows="2" placeholder="Uraian kegiatan anggaran..." required></textarea>
          </div>

          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">PIC <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="formPic" name="pic" list="picDatalist" placeholder="mis. Timker 1 / ADUM" required>
              <datalist id="picDatalist">
                <?php foreach ($picOptions as $picOpt): ?>
                  <option value="<?= esc($picOpt) ?>">
                <?php endforeach; ?>
              </datalist>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Rencana Pencairan (Kolom D) <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                <input type="text" class="form-control rma-rupiah-input" id="formRencana" name="rencana" placeholder="0" required>
              </div>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Realisasi (Kolom K)</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                <input type="text" class="form-control rma-rupiah-input" id="formRealisasi" name="realisasi" placeholder="0" value="0">
              </div>
            </div>
          </div>

          <div class="alert alert-light border mb-0 py-2 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Pratinjau Sisa &amp; Serapan:</span>
            <span class="font-weight-bold" id="formPreviewSisa">Sisa: Rp 0 (0%)</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold" id="btnSubmitManual">
            <i class="fas fa-save mr-1"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-file-excel mr-1"></i> Import Excel Monitoring Anggaran
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formImportExcel" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="alert alert-info py-2 px-3 small">
            <strong>Fokus Kolom:</strong> <code>A (RO)</code>, <code>B (Akun)</code>, <code>C (Kegiatan)</code>, <code>D (Rencana)</code>, <code>PIC</code>, dan <code>K (Realisasi)</code>. Mendukung langsung workbook rekap bulanan (<code>JAN</code>–<code>DESEMBER</code>) maupun format template sederhana.
          </div>
          <div class="form-group">
            <label class="font-weight-bold">File Excel (.xlsx / .xls / .csv) <span class="text-danger">*</span></label>
            <input type="file" class="form-control-file border rounded p-2 w-100" id="importFileExcel" name="file_excel" accept=".xlsx,.xls,.csv" required>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Tahun Anggaran</label>
              <input type="number" class="form-control form-control-sm" id="importTahun" name="tahun_anggaran" value="<?= date('Y') ?>" min="2020" max="2099">
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Bulan Default</label>
              <input type="month" class="form-control form-control-sm" id="importBulanDefault" name="bulan_default" value="<?= date('Y-m') ?>">
              <small class="text-muted">Dipakai jika sheet/baris tidak mencantumkan bulan.</small>
            </div>
          </div>
          <div class="form-group mb-2">
            <label class="font-weight-bold">Mode Import</label>
            <select class="form-control form-control-sm" id="importMode" name="mode_import">
              <option value="replace_period" selected>Timpa data pada bulan yang sama di file (hindari duplikat)</option>
              <option value="append">Tambahkan ke data yang sudah ada (Append)</option>
            </select>
          </div>
          <div class="mt-2">
            <a href="/relaxtime/monitoring-anggaran/template" class="btn btn-outline-teal btn-sm text-teal" style="border-color:#0f766e;">
              <i class="fas fa-download mr-1"></i> Download Template Excel (.xlsx)
            </a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold" id="btnSubmitImport">
            <i class="fas fa-upload mr-1"></i> Proses Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ALL_ROWS = <?= json_encode($allRowsJson, JSON_UNESCAPED_UNICODE) ?>;
  const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const MONTHS_FULL = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  let dataTableInstance = null;
  let monthlyChartInstance = null;
  let picChartInstance = null;

  function escapeHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function formatRupiah(num) {
    const n = Math.round(Number(num) || 0);
    const absStr = Math.abs(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return (n < 0 ? '-Rp ' : 'Rp ') + absStr;
  }

  function formatAxisShort(val) {
    const n = Number(val) || 0;
    if (Math.abs(n) >= 1e9) return 'Rp ' + (n / 1e9).toFixed(1).replace('.0', '') + ' M';
    if (Math.abs(n) >= 1e6) return 'Rp ' + (n / 1e6).toFixed(0) + ' jt';
    if (Math.abs(n) >= 1e3) return 'Rp ' + (n / 1e3).toFixed(0) + ' rb';
    return 'Rp ' + n;
  }

  function parseNumericInput(str) {
    const clean = String(str || '').replace(/[^\d]/g, '');
    return clean === '' ? 0 : parseInt(clean, 10);
  }

  function formatBulanIndo(isoDate) {
    if (!isoDate || !/^\d{4}-\d{2}/.test(isoDate)) return isoDate || '-';
    const parts = isoDate.split('-');
    const m = parseInt(parts[1], 10) - 1;
    return (MONTHS_FULL[m] || parts[1]) + ' ' + parts[0];
  }

  function computeStats(rows) {
    let totalRencana = 0, totalRealisasi = 0, terealisasi = 0, overBudget = 0;
    const byAkun = {
      '51': { rencana: 0, realisasi: 0, sisa: 0, persen: 0, count: 0 },
      '52': { rencana: 0, realisasi: 0, sisa: 0, persen: 0, count: 0 },
      '53': { rencana: 0, realisasi: 0, sisa: 0, persen: 0, count: 0 }
    };
    const byPicMap = {};
    const byMonthMap = {};

    rows.forEach(function (r) {
      const rencana = Number(r.rencana) || 0;
      const realisasi = Number(r.realisasi) || 0;
      const akun = ['51', '52', '53'].includes(r.akun) ? r.akun : '52';
      const pic = r.pic && r.pic.trim() !== '' ? r.pic.trim() : 'Belum Ditentukan';
      const monthKey = (r.tanggal || '').slice(0, 7);

      totalRencana += rencana;
      totalRealisasi += realisasi;
      if (realisasi > 0) terealisasi++;
      if (rencana > 0 && realisasi > rencana) overBudget++;

      byAkun[akun].rencana += rencana;
      byAkun[akun].realisasi += realisasi;
      byAkun[akun].count++;

      if (!byPicMap[pic]) {
        byPicMap[pic] = { pic: pic, rencana: 0, realisasi: 0, sisa: 0, persen: 0, count: 0 };
      }
      byPicMap[pic].rencana += rencana;
      byPicMap[pic].realisasi += realisasi;
      byPicMap[pic].count++;

      if (/^\d{4}-\d{2}$/.test(monthKey)) {
        if (!byMonthMap[monthKey]) {
          const mp = monthKey.split('-');
          const mIdx = parseInt(mp[1], 10) - 1;
          byMonthMap[monthKey] = {
            key: monthKey,
            label: (MONTHS_SHORT[mIdx] || mp[1]) + ' ' + mp[0],
            rencana: 0,
            realisasi: 0
          };
        }
        byMonthMap[monthKey].rencana += rencana;
        byMonthMap[monthKey].realisasi += realisasi;
      }
    });

    ['51', '52', '53'].forEach(function (k) {
      const a = byAkun[k];
      a.sisa = a.rencana - a.realisasi;
      a.persen = a.rencana > 0 ? (a.realisasi / a.rencana) * 100 : 0;
    });

    const byPic = Object.values(byPicMap).map(function (p) {
      p.sisa = p.rencana - p.realisasi;
      p.persen = p.rencana > 0 ? (p.realisasi / p.rencana) * 100 : 0;
      return p;
    }).sort(function (a, b) { return b.rencana - a.rencana; });

    const byMonth = Object.keys(byMonthMap).sort().map(function (k) { return byMonthMap[k]; });
    const totalSisa = totalRencana - totalRealisasi;
    const persen = totalRencana > 0 ? (totalRealisasi / totalRencana) * 100 : 0;

    return {
      totalRencana, totalRealisasi, totalSisa, persen,
      totalKegiatan: rows.length, terealisasi, overBudget,
      byAkun, byPic, byMonth
    };
  }

  function renderCharts(stats) {
    if (typeof Chart === 'undefined') return;

    const mLabels = stats.byMonth.map(m => m.label);
    const mRencana = stats.byMonth.map(m => m.rencana);
    const mRealisasi = stats.byMonth.map(m => m.realisasi);

    const ctxMonthly = document.getElementById('chartMonthly').getContext('2d');
    if (monthlyChartInstance) monthlyChartInstance.destroy();
    monthlyChartInstance = new Chart(ctxMonthly, {
      type: 'bar',
      data: {
        labels: mLabels,
        datasets: [
          {
            label: 'Rencana',
            data: mRencana,
            backgroundColor: 'rgba(2, 132, 199, 0.78)',
            borderColor: '#0284c7',
            borderWidth: 1
          },
          {
            label: 'Realisasi',
            data: mRealisasi,
            backgroundColor: 'rgba(15, 118, 110, 0.85)',
            borderColor: '#0f766e',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function (v) { return formatAxisShort(v); }
            }
          }],
          xAxes: [{
            gridLines: { display: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function (tooltipItem, data) {
              const dsLabel = data.datasets[tooltipItem.datasetIndex].label || '';
              return dsLabel + ': ' + formatRupiah(tooltipItem.yLabel);
            }
          }
        }
      }
    });

    const pLabels = stats.byPic.map(p => p.pic);
    const pRencana = stats.byPic.map(p => p.rencana);
    const pRealisasi = stats.byPic.map(p => p.realisasi);

    const ctxPic = document.getElementById('chartPic').getContext('2d');
    if (picChartInstance) picChartInstance.destroy();
    picChartInstance = new Chart(ctxPic, {
      type: 'horizontalBar',
      data: {
        labels: pLabels,
        datasets: [
          {
            label: 'Rencana',
            data: pRencana,
            backgroundColor: 'rgba(2, 132, 199, 0.72)'
          },
          {
            label: 'Realisasi',
            data: pRealisasi,
            backgroundColor: 'rgba(15, 118, 110, 0.88)'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function (v) { return formatAxisShort(v); }
            }
          }],
          yAxes: [{
            gridLines: { display: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function (tooltipItem, data) {
              const dsLabel = data.datasets[tooltipItem.datasetIndex].label || '';
              return dsLabel + ': ' + formatRupiah(tooltipItem.xLabel);
            }
          }
        }
      }
    });
  }

  function renderDashboardAndTable() {
    const akunVal = document.getElementById('filterAkun').value;
    const picVal = document.getElementById('filterPic').value;

    const filtered = ALL_ROWS.filter(function (r) {
      if (akunVal && r.akun !== akunVal) return false;
      const rowPic = r.pic && r.pic.trim() !== '' ? r.pic.trim() : 'Belum Ditentukan';
      if (picVal && rowPic.toLowerCase() !== picVal.toLowerCase()) return false;
      return true;
    });

    const stats = computeStats(filtered);

    // KPI Cards
    document.getElementById('kpiTotalRencana').textContent = formatRupiah(stats.totalRencana);
    document.getElementById('kpiTotalKegiatan').textContent = stats.totalKegiatan + ' item kegiatan';
    document.getElementById('kpiTotalRealisasi').textContent = formatRupiah(stats.totalRealisasi);
    document.getElementById('kpiTerealisasiCount').textContent = stats.terealisasi + ' kegiatan sudah terealisasi';

    const sisaEl = document.getElementById('kpiTotalSisa');
    sisaEl.textContent = formatRupiah(stats.totalSisa);
    sisaEl.classList.toggle('text-danger', stats.totalSisa < 0);
    document.getElementById('kpiOverBudgetCount').textContent = stats.overBudget + ' kegiatan melebihi rencana';

    document.getElementById('kpiPersenRealisasi').textContent = stats.persen.toFixed(2).replace('.', ',') + '%';
    document.getElementById('kpiPersenBar').style.width = Math.min(100, stats.persen).toFixed(1) + '%';

    // Akun Cards
    ['51', '52', '53'].forEach(function (k) {
      const a = stats.byAkun[k];
      document.getElementById('akun' + k + 'Count').textContent = a.count + ' keg';
      document.getElementById('akun' + k + 'Rencana').textContent = formatRupiah(a.rencana);
      document.getElementById('akun' + k + 'Realisasi').textContent = formatRupiah(a.realisasi);
      document.getElementById('akun' + k + 'Bar').style.width = Math.min(100, a.persen).toFixed(1) + '%';
      document.getElementById('akun' + k + 'Sisa').textContent = 'Sisa: ' + formatRupiah(a.sisa);
      document.getElementById('akun' + k + 'Persen').textContent = a.persen.toFixed(1).replace('.', ',') + '%';

      const cardEl = document.querySelector('[data-akun-card="' + k + '"]');
      if (cardEl) cardEl.classList.toggle('is-selected', akunVal === k);
    });

    // PIC Summary Table
    document.getElementById('badgePicCount').textContent = stats.byPic.length + ' PIC';
    const picBody = document.getElementById('picSummaryBody');
    if (stats.byPic.length === 0) {
      picBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data pada filter terpilih.</td></tr>';
    } else {
      picBody.innerHTML = stats.byPic.map(function (p) {
        const sisaClass = p.sisa < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos';
        return '<tr class="rma-pic-row-filter" data-pic-name="' + escapeHtml(p.pic) + '">' +
          '<td class="font-weight-bold">' + escapeHtml(p.pic) + '</td>' +
          '<td class="text-center">' + p.count + '</td>' +
          '<td class="text-right">' + formatRupiah(p.rencana) + '</td>' +
          '<td class="text-right text-teal font-weight-bold">' + formatRupiah(p.realisasi) + '</td>' +
          '<td class="text-right ' + sisaClass + '">' + formatRupiah(p.sisa) + '</td>' +
          '<td class="text-right font-weight-bold">' + p.persen.toFixed(1).replace('.', ',') + '%</td>' +
        '</tr>';
      }).join('');
    }

    // Charts
    renderCharts(stats);

    // Table Footer
    document.getElementById('footTotalRencana').textContent = formatRupiah(stats.totalRencana);
    document.getElementById('footTotalRealisasi').textContent = formatRupiah(stats.totalRealisasi);
    const footSisa = document.getElementById('footTotalSisa');
    footSisa.textContent = formatRupiah(stats.totalSisa);
    footSisa.classList.toggle('rma-sisa-neg', stats.totalSisa < 0);
    document.getElementById('footTotalPersen').textContent = stats.persen.toFixed(1).replace('.', ',') + '%';

    // DataTable rebuild
    if (dataTableInstance) {
      dataTableInstance.destroy();
      dataTableInstance = null;
    }

    const tbody = document.getElementById('monitoringTableBody');
    tbody.innerHTML = filtered.map(function (r, idx) {
      const sisa = r.rencana - r.realisasi;
      const pct = r.rencana > 0 ? ((r.realisasi / r.rencana) * 100) : 0;
      const badgeAkun = r.akun === '51' ? 'rma-akun-51' : (r.akun === '53' ? 'rma-akun-53' : 'rma-akun-52');
      const sisaClass = sisa < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos';
      return '<tr data-id="' + r.id + '">' +
        '<td class="text-center"><input type="checkbox" class="row-check" value="' + r.id + '"></td>' +
        '<td class="text-center">' + (idx + 1) + '</td>' +
        '<td data-order="' + escapeHtml((r.tanggal || '').slice(0, 7)) + '">' + escapeHtml(formatBulanIndo(r.tanggal)) + '</td>' +
        '<td class="font-weight-bold">' + escapeHtml(r.ro || '-') + '</td>' +
        '<td class="text-center"><span class="rma-badge-akun ' + badgeAkun + '">' + escapeHtml(r.akun) + '</span></td>' +
        '<td>' + escapeHtml(r.kegiatan) + '</td>' +
        '<td>' + escapeHtml(r.pic || '-') + '</td>' +
        '<td class="text-right font-weight-bold" data-order="' + r.rencana + '">' + formatRupiah(r.rencana) + '</td>' +
        '<td class="text-right text-teal font-weight-bold" data-order="' + r.realisasi + '">' + formatRupiah(r.realisasi) + '</td>' +
        '<td class="text-right ' + sisaClass + '" data-order="' + sisa + '">' + formatRupiah(sisa) + '</td>' +
        '<td class="text-center font-weight-bold" data-order="' + pct.toFixed(2) + '">' + pct.toFixed(1) + '%</td>' +
        '<td class="text-center text-nowrap">' +
          '<button type="button" class="btn btn-xs btn-outline-warning btn-edit-row mr-1" data-id="' + r.id + '" title="Edit"><i class="fas fa-edit"></i></button>' +
          '<button type="button" class="btn btn-xs btn-outline-danger btn-delete-row" data-id="' + r.id + '" title="Hapus"><i class="fas fa-trash"></i></button>' +
        '</td>' +
      '</tr>';
    }).join('');

    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      dataTableInstance = $('#monitoringTable').DataTable({
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order: [],
        columnDefs: [{ orderable: false, targets: [0, 11] }],
        language: {
          search: 'Cari:',
          lengthMenu: 'Tampilkan _MENU_ baris',
          zeroRecords: 'Tidak ada data kegiatan ditemukan',
          info: 'Menampilkan _START_–_END_ dari _TOTAL_ baris',
          infoEmpty: 'Tidak ada data',
          paginate: { next: 'Berikutnya', previous: 'Sebelumnya' }
        }
      });
    }

    // Update URL & Export Link
    const params = new URLSearchParams();
    const bMulai = document.getElementById('filterBulanMulai').value;
    const bSelesai = document.getElementById('filterBulanSelesai').value;
    const allMonthsFlag = document.getElementById('filterAllMonths').value;
    if (bMulai) params.set('bulan_mulai', bMulai);
    if (bSelesai) params.set('bulan_selesai', bSelesai);
    if (akunVal) params.set('akun', akunVal);
    if (picVal) params.set('pic', picVal);
    if (allMonthsFlag === '1') params.set('all_months', '1');
    const qs = params.toString();
    document.getElementById('btnExportExcel').setAttribute('href', '/relaxtime/monitoring-anggaran/export' + (qs ? '?' + qs : ''));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, '', '/relaxtime/monitoring-anggaran' + (qs ? '?' + qs : ''));
    }

    updateBulkDeleteBar();
  }

  function updateBulkDeleteBar() {
    const checked = document.querySelectorAll('.row-check:checked').length;
    const btn = document.getElementById('btnBulkDelete');
    document.getElementById('bulkDeleteCount').textContent = checked;
    btn.classList.toggle('d-none', checked === 0);
  }

  function updateManualPreview() {
    const renc = parseNumericInput(document.getElementById('formRencana').value);
    const real = parseNumericInput(document.getElementById('formRealisasi').value);
    const sisa = renc - real;
    const pct = renc > 0 ? ((real / renc) * 100).toFixed(1) : '0.0';
    const preview = document.getElementById('formPreviewSisa');
    preview.textContent = 'Sisa: ' + formatRupiah(sisa) + ' (' + pct + '%)';
    preview.classList.toggle('text-danger', sisa < 0);
  }

  // Event listeners filter
  document.querySelectorAll('.rma-client-filter').forEach(function (el) {
    el.addEventListener('change', renderDashboardAndTable);
  });

  document.querySelectorAll('.rma-month-input').forEach(function (el) {
    el.addEventListener('change', function () {
      document.getElementById('filterAllMonths').value = '0';
      document.getElementById('filterForm').submit();
    });
  });

  document.getElementById('btnToggleAllMonths').addEventListener('click', function () {
    const inp = document.getElementById('filterAllMonths');
    inp.value = inp.value === '1' ? '0' : '1';
    if (inp.value === '1') {
      document.getElementById('filterBulanMulai').value = '';
      document.getElementById('filterBulanSelesai').value = '';
    }
    document.getElementById('filterForm').submit();
  });

  document.getElementById('btnResetFilter').addEventListener('click', function () {
    window.location.href = '/relaxtime/monitoring-anggaran';
  });

  // Click Akun card to filter
  document.querySelectorAll('[data-akun-card]').forEach(function (card) {
    card.addEventListener('click', function () {
      const code = card.getAttribute('data-akun-card');
      const sel = document.getElementById('filterAkun');
      sel.value = sel.value === code ? '' : code;
      renderDashboardAndTable();
    });
  });

  // Click PIC summary row to filter
  document.getElementById('picSummaryBody').addEventListener('click', function (e) {
    const tr = e.target.closest('[data-pic-name]');
    if (!tr) return;
    const pName = tr.getAttribute('data-pic-name');
    const sel = document.getElementById('filterPic');
    sel.value = sel.value.toLowerCase() === pName.toLowerCase() ? '' : pName;
    renderDashboardAndTable();
  });

  // Rupiah input formatting
  document.querySelectorAll('.rma-rupiah-input').forEach(function (inp) {
    inp.addEventListener('input', function () {
      const digits = this.value.replace(/[^\d]/g, '');
      this.value = digits === '' ? '' : digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      updateManualPreview();
    });
  });

  // Open Input Manual Modal
  document.getElementById('btnInputManual').addEventListener('click', function () {
    document.getElementById('formIdMonitoring').value = '';
    document.getElementById('modalManualTitle').innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Input Manual Monitoring Anggaran';
    document.getElementById('formManualAnggaran').reset();
    document.getElementById('formBulan').value = '<?= date('Y-m') ?>';
    document.getElementById('formAkun').value = '52';
    document.getElementById('formRealisasi').value = '0';
    updateManualPreview();
    $('#modalManualAnggaran').modal('show');
  });

  // Open Import Excel Modal
  document.getElementById('btnImportExcel').addEventListener('click', function () {
    document.getElementById('formImportExcel').reset();
    $('#modalImportExcel').modal('show');
  });

  // Submit Manual Create / Update
  document.getElementById('formManualAnggaran').addEventListener('submit', async function (e) {
    e.preventDefault();
    const id = document.getElementById('formIdMonitoring').value;
    const url = id ? '/relaxtime/monitoring-anggaran/update/' + id : '/relaxtime/monitoring-anggaran/store';
    const formData = new FormData(this);
    formData.set('rencana', String(parseNumericInput(document.getElementById('formRencana').value)));
    formData.set('realisasi', String(parseNumericInput(document.getElementById('formRealisasi').value)));

    const btn = document.getElementById('btnSubmitManual');
    btn.disabled = true;
    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      });
      const json = await res.json();
      if (!res.ok || !json.success) {
        Swal.fire('Gagal', json.message || 'Gagal menyimpan data.', 'error');
        return;
      }
      $('#modalManualAnggaran').modal('hide');
      await Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 1400, showConfirmButton: false });
      window.location.reload();
    } catch (err) {
      Swal.fire('Error', 'Terjadi kesalahan koneksi saat menyimpan.', 'error');
    } finally {
      btn.disabled = false;
    }
  });

  // Table Actions: Edit & Delete & Checkboxes
  document.getElementById('monitoringTable').addEventListener('click', async function (e) {
    const editBtn = e.target.closest('.btn-edit-row');
    if (editBtn) {
      const id = parseInt(editBtn.getAttribute('data-id'), 10);
      const item = ALL_ROWS.find(r => r.id === id);
      if (!item) return;

      document.getElementById('formIdMonitoring').value = item.id;
      document.getElementById('modalManualTitle').innerHTML = '<i class="fas fa-edit mr-1"></i> Edit Data Monitoring Anggaran';
      document.getElementById('formBulan').value = (item.tanggal || '').slice(0, 7);
      document.getElementById('formRo').value = item.ro;
      document.getElementById('formAkun').value = item.akun;
      document.getElementById('formKegiatan').value = item.kegiatan;
      document.getElementById('formPic').value = item.pic;
      document.getElementById('formRencana').value = String(item.rencana).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      document.getElementById('formRealisasi').value = String(item.realisasi).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      updateManualPreview();
      $('#modalManualAnggaran').modal('show');
      return;
    }

    const delBtn = e.target.closest('.btn-delete-row');
    if (delBtn) {
      const id = delBtn.getAttribute('data-id');
      const conf = await Swal.fire({
        icon: 'warning',
        title: 'Hapus data ini?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      });
      if (!conf.isConfirmed) return;

      const res = await fetch('/relaxtime/monitoring-anggaran/delete/' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();
      if (res.ok && json.success) {
        await Swal.fire({ icon: 'success', title: 'Terhapus', text: json.message, timer: 1200, showConfirmButton: false });
        window.location.reload();
      } else {
        Swal.fire('Gagal', json.message || 'Gagal menghapus data.', 'error');
      }
      return;
    }

    if (e.target.classList.contains('row-check')) {
      updateBulkDeleteBar();
    }
  });

  document.getElementById('checkAllRows').addEventListener('change', function () {
    const checked = this.checked;
    document.querySelectorAll('.row-check').forEach(function (cb) {
      cb.checked = checked;
    });
    updateBulkDeleteBar();
  });

  document.getElementById('btnBulkDelete').addEventListener('click', async function () {
    const ids = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
    if (!ids.length) return;
    const conf = await Swal.fire({
      icon: 'warning',
      title: 'Hapus ' + ids.length + ' data terpilih?',
      text: 'Data terpilih akan dihapus permanen.',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      confirmButtonText: 'Hapus Semua',
      cancelButtonText: 'Batal'
    });
    if (!conf.isConfirmed) return;

    const fd = new FormData();
    ids.forEach(id => fd.append('ids[]', id));
    const res = await fetch('/relaxtime/monitoring-anggaran/bulk-delete', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    });
    const json = await res.json();
    if (res.ok && json.success) {
      await Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 1400, showConfirmButton: false });
      window.location.reload();
    } else {
      Swal.fire('Gagal', json.message || 'Gagal menghapus data terpilih.', 'error');
    }
  });

  // Submit Import Excel
  document.getElementById('formImportExcel').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = document.getElementById('btnSubmitImport');
    btn.disabled = true;

    Swal.fire({
      title: 'Memproses Import Excel...',
      text: 'Membaca sheet & kolom RO, Akun, Kegiatan, Rencana, PIC, dan Realisasi.',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); }
    });

    try {
      const res = await fetch('/relaxtime/monitoring-anggaran/import', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      });
      const json = await res.json();
      if (!res.ok || !json.success) {
        Swal.fire('Import Gagal', json.message || 'File tidak valid.', 'error');
        return;
      }

      $('#modalImportExcel').modal('hide');
      const sheetSummary = (json.sheets_processed || [])
        .map(s => '<li><strong>' + escapeHtml(s.sheet) + '</strong>: ' + s.count + ' baris</li>')
        .join('');
      await Swal.fire({
        icon: 'success',
        title: 'Import Berhasil!',
        html: '<p>' + escapeHtml(json.message) + '</p>' +
              (sheetSummary ? '<ul class="text-left small mb-0" style="max-height:180px;overflow:auto;">' + sheetSummary + '</ul>' : ''),
        confirmButtonText: 'Muat Ulang Halaman'
      });
      window.location.reload();
    } catch (err) {
      Swal.fire('Error', 'Terjadi kesalahan saat mengupload file Excel.', 'error');
    } finally {
      btn.disabled = false;
    }
  });

  // Initial render
  renderDashboardAndTable();
});
</script>
<?= $this->endSection() ?>
