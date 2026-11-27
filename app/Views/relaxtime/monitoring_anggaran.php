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
        'created_at' => (string) ($r['created_at'] ?? ''),
        'updated_at' => (string) ($r['updated_at'] ?? ''),
    ];
}

$yearRowsJson = [];
foreach (($yearRows ?? $allDateRows) as $yrRow) {
    $yearRowsJson[] = [
        'id' => (int) ($yrRow['id_monitoring'] ?? 0),
        'tanggal' => (string) ($yrRow['tanggal'] ?? ''),
        'akun' => (string) ($yrRow['akun'] ?? ''),
        'realisasi' => (int) ($yrRow['realisasi'] ?? 0),
    ];
}
$targetYearVal = (int) ($targetYear ?? date('Y'));
$targetConfigVal = $targetConfig ?? \App\Libraries\RelaxtimeMonitoringAnggaranHelper::getDefaultPaguTargetForYear($targetYearVal);
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
  .rma-rkks-picker {
    position: relative;
  }
  .rma-rkks-dropdown {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 2px);
    z-index: 1065;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.18);
    max-height: 260px;
    overflow-y: auto;
  }
  .rma-rkks-dropdown-header {
    position: sticky;
    top: 0;
    z-index: 1;
    padding: 5px 10px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #475569;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }
  .rma-rkks-item {
    display: block;
    width: 100%;
    text-align: left;
    border: 0;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
    padding: 7px 10px;
    font-size: 0.78rem;
    line-height: 1.35;
    color: #0f172a;
    cursor: pointer;
  }
  .rma-rkks-item:hover,
  .rma-rkks-item.is-active {
    background: #f0fdfa;
    color: #0f766e;
  }
  .rma-dipa-badge {
    display: inline-block;
    padding: 1px 6px;
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 700;
    line-height: 1.35;
    white-space: nowrap;
  }
  .rma-dipa-badge.is-optimal { background: #dcfce7; color: #166534; }
  .rma-dipa-badge.is-waspada { background: #fef3c7; color: #92400e; }
  .rma-dipa-badge.is-tinggi { background: #fee2e2; color: #b91c1c; }
  .rma-tw-btn.active {
    background: #0f766e !important;
    color: #fff !important;
    border-color: #0f766e !important;
    font-weight: 700;
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
      <div class="col-12 col-sm-6 col-xl mb-2 mb-xl-0">
        <div class="rma-kpi-card" style="--kpi-accent: #0284c7;">
          <div class="rma-kpi-label">Total Rencana (Kolom D)</div>
          <div class="rma-kpi-value" id="kpiTotalRencana">Rp <?= number_format((int) ($summary['total_rencana'] ?? 0), 0, ',', '.') ?></div>
          <div class="rma-kpi-sub" id="kpiTotalKegiatan"><?= (int) ($summary['total_kegiatan'] ?? 0) ?> item kegiatan</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl mb-2 mb-xl-0">
        <div class="rma-kpi-card" style="--kpi-accent: #0f766e;">
          <div class="rma-kpi-label">Total Realisasi (Kolom K)</div>
          <div class="rma-kpi-value text-teal" id="kpiTotalRealisasi">Rp <?= number_format((int) ($summary['total_realisasi'] ?? 0), 0, ',', '.') ?></div>
          <div class="rma-kpi-sub" id="kpiTerealisasiCount"><?= (int) ($summary['terealisasi_count'] ?? 0) ?> kegiatan sudah terealisasi</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl mb-2 mb-xl-0">
        <div class="rma-kpi-card" style="--kpi-accent: #d97706;">
          <div class="rma-kpi-label">Sisa Anggaran</div>
          <?php $sisaVal = (int) ($summary['total_sisa'] ?? 0); ?>
          <div class="rma-kpi-value <?= $sisaVal < 0 ? 'text-danger' : '' ?>" id="kpiTotalSisa">
            <?= $sisaVal < 0 ? '-' : '' ?>Rp <?= number_format(abs($sisaVal), 0, ',', '.') ?>
          </div>
          <div class="rma-kpi-sub" id="kpiOverBudgetCount"><?= (int) ($summary['over_budget_count'] ?? 0) ?> kegiatan melebihi rencana</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl mb-2 mb-xl-0">
        <?php
        $deviasiVal = (int) ($summary['total_deviasi'] ?? 0);
        $dipaTertimbang = (float) ($summary['deviasi_dipa_tertimbang'] ?? 0);
        $nilaiIkpaDev = (float) ($summary['nilai_ikpa_deviasi'] ?? 100);
        $dipaStatus = (string) ($summary['deviasi_dipa_status'] ?? 'optimal');
        $dipaLabel = (string) ($summary['deviasi_dipa_label'] ?? 'Optimal (≤5%)');
        $devAccent = $dipaStatus === 'optimal' ? '#16a34a' : ($dipaStatus === 'waspada' ? '#d97706' : '#dc2626');
        $devClass = $dipaStatus === 'optimal' ? 'text-success' : ($dipaStatus === 'waspada' ? 'text-warning' : 'text-danger');
        $devPrefix = $deviasiVal > 0 ? '+' : ($deviasiVal < 0 ? '-' : '');
        ?>
        <div class="rma-kpi-card" id="kpiDeviasiCard" style="--kpi-accent: <?= $devAccent ?>;" title="Aturan Hal. III DIPA (PER-5/PB/2024): Deviasi tertimbang antar jenis belanja, ambang optimal <= 5% = Nilai 100">
          <div class="rma-kpi-label">Deviasi Hal. III DIPA</div>
          <div class="rma-kpi-value <?= $devClass ?>" id="kpiTotalDeviasi">
            <?= number_format($dipaTertimbang, 2, ',', '.') ?>%
          </div>
          <div class="rma-kpi-sub" id="kpiPersenDeviasi">
            Nilai: <strong><?= number_format($nilaiIkpaDev, 2, ',', '.') ?></strong> &bull; <?= esc($dipaLabel) ?> &bull; Selisih <?= $devPrefix ?>Rp <?= number_format(abs($deviasiVal), 0, ',', '.') ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl">
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
          $ak = $byAkun[$code] ?? ['rencana' => 0, 'realisasi' => 0, 'sisa' => 0, 'deviasi' => 0, 'persen' => 0, 'persen_deviasi' => 0, 'deviasi_dipa_persen' => 0, 'nilai_deviasi_dipa' => 100, 'deviasi_dipa_status' => 'optimal', 'count' => 0];
          $akDev = (int) ($ak['deviasi'] ?? ($ak['realisasi'] - $ak['rencana']));
          $akDipaPct = (float) ($ak['deviasi_dipa_persen'] ?? 0);
          $akDipaNilai = (float) ($ak['nilai_deviasi_dipa'] ?? 100);
          $akDipaStatus = (string) ($ak['deviasi_dipa_status'] ?? 'optimal');
          $akDevClass = $akDipaStatus === 'optimal' ? 'text-success' : ($akDipaStatus === 'waspada' ? 'text-warning' : 'text-danger');
          $akDevSign = $akDev > 0 ? '+' : ($akDev < 0 ? '-' : '');
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
            <div class="d-flex justify-content-between mt-1" style="font-size: 0.74rem;">
              <span class="text-muted">Deviasi Hal. III</span>
              <span class="font-weight-bold <?= $akDevClass ?>" id="akun<?= $code ?>Deviasi"><?= number_format($akDipaPct, 1, ',', '.') ?>% (Nilai <?= number_format($akDipaNilai, 0, ',', '.') ?>) &bull; <?= $akDevSign ?>Rp <?= number_format(abs($akDev), 0, ',', '.') ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Target Realisasi vs Realisasi per Jenis Akun (PER-5/PB/2024) -->
    <div class="rma-card p-3 mb-3">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 10px;">
        <div>
          <h6 class="font-weight-bold mb-0">
            <i class="fas fa-bullseye text-teal mr-1"></i> Target Realisasi vs Realisasi per Jenis Akun
            <span class="badge badge-light border ml-1">TA <?= (int) $targetYearVal ?></span>
          </h6>
          <small class="text-muted">
            Perbandingan Target Penyerapan Anggaran (PER-5/PB/2024) terhadap Realisasi Kumulatif Satker per Akun 51, 52, dan 53 — <span id="targetPeriodSubtitle">Jan–Des <?= (int) $targetYearVal ?></span>.
          </small>
        </div>
        <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
          <div class="btn-group btn-group-sm" role="group" aria-label="Pilih Triwulan">
            <button type="button" class="btn btn-outline-teal rma-tw-btn" data-tw="1" style="border-color:#0f766e;">TW I</button>
            <button type="button" class="btn btn-outline-teal rma-tw-btn" data-tw="2" style="border-color:#0f766e;">TW II</button>
            <button type="button" class="btn btn-outline-teal rma-tw-btn" data-tw="3" style="border-color:#0f766e;">TW III</button>
            <button type="button" class="btn btn-outline-teal rma-tw-btn" data-tw="4" style="border-color:#0f766e;">TW IV</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#collapseTargetMatrix" aria-expanded="false">
            <i class="fas fa-th-list mr-1"></i> Matriks TW I–IV
          </button>
          <button type="button" class="btn btn-sm bg-teal text-white font-weight-bold" id="btnEditTargetTriwulan">
            <i class="fas fa-sliders-h mr-1"></i> Atur Pagu &amp; Target
          </button>
        </div>
      </div>

      <div class="row align-items-stretch">
        <div class="col-12 col-xl-7 mb-3 mb-xl-0">
          <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0 rma-table">
              <thead>
                <tr>
                  <th>Jenis Akun</th>
                  <th class="text-right">Pagu DIPA</th>
                  <th class="text-right">Target Realisasi</th>
                  <th class="text-right">Realisasi Kumulatif</th>
                  <th style="min-width: 120px;">Capaian vs Target</th>
                  <th class="text-center">Status IKPA</th>
                </tr>
              </thead>
              <tbody id="targetComparisonBody"></tbody>
              <tfoot id="targetComparisonFoot"></tfoot>
            </table>
          </div>
        </div>
        <div class="col-12 col-xl-5">
          <div class="border rounded p-2 h-100 bg-light d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-1 px-1">
              <small class="font-weight-bold text-dark"><i class="fas fa-chart-bar text-teal mr-1"></i> Grafik Pagu vs Target vs Realisasi (<span id="chartTargetTwLabel">TW IV</span>)</small>
              <small class="text-muted" id="targetIkpaScoreBadge"></small>
            </div>
            <div style="position: relative; flex: 1; min-height: 215px;">
              <canvas id="chartTargetVsRealisasi"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div id="collapseTargetMatrix" class="collapse mt-3 pt-3 border-top">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="font-weight-bold mb-0 small text-uppercase text-muted"><i class="fas fa-table mr-1"></i> Rekap Pagu, Target Realisasi &amp; Realisasi Kumulatif Triwulan I – IV (TA <?= (int) $targetYearVal ?>)</h6>
          <small class="text-muted">Pagu per Jenis Belanja dapat disesuaikan lewat tombol <strong>Atur Pagu &amp; Target</strong>.</small>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0 rma-table">
            <thead>
              <tr>
                <th rowspan="2" class="align-middle">Triwulan</th>
                <th colspan="3" class="text-center">51 — Belanja Pegawai</th>
                <th colspan="3" class="text-center">52 — Belanja Barang</th>
                <th colspan="3" class="text-center">53 — Belanja Modal</th>
                <th rowspan="2" class="text-right align-middle">Total Target</th>
                <th rowspan="2" class="text-right align-middle">Total Realisasi</th>
              </tr>
              <tr>
                <th class="text-right">Pagu</th>
                <th class="text-right">Target</th>
                <th class="text-right">Realisasi</th>
                <th class="text-right">Pagu</th>
                <th class="text-right">Target</th>
                <th class="text-right">Realisasi</th>
                <th class="text-right">Pagu</th>
                <th class="text-right">Target</th>
                <th class="text-right">Realisasi</th>
              </tr>
            </thead>
            <tbody id="targetMatrixBody"></tbody>
          </table>
        </div>
      </div>
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
                <th class="text-right">Deviasi Hal. III DIPA</th>
                <th class="text-right">Serapan</th>
              </tr>
            </thead>
            <tbody id="picSummaryBody">
              <?php if (empty($byPic)): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data.</td></tr>
              <?php else: ?>
                <?php foreach ($byPic as $p):
                    $pDev = (int) ($p['deviasi'] ?? ($p['realisasi'] - $p['rencana']));
                    $pDipaPct = (float) ($p['deviasi_dipa_persen'] ?? 0);
                    $pDipaNilai = (float) ($p['nilai_deviasi_dipa'] ?? 100);
                    $pDipaStatus = (string) ($p['deviasi_dipa_status'] ?? 'optimal');
                    $pDipaLabel = (string) ($p['deviasi_dipa_label'] ?? 'Optimal (≤5%)');
                    $pDevClass = $pDipaStatus === 'optimal' ? 'text-success font-weight-bold' : ($pDipaStatus === 'waspada' ? 'text-warning font-weight-bold' : 'text-danger font-weight-bold');
                    $pBadgeClass = $pDipaStatus === 'optimal' ? 'is-optimal' : ($pDipaStatus === 'waspada' ? 'is-waspada' : 'is-tinggi');
                    $pDevSign = $pDev > 0 ? '+' : ($pDev < 0 ? '-' : '');
                ?>
                  <tr class="rma-pic-row-filter" data-pic-name="<?= esc($p['pic']) ?>">
                    <td class="font-weight-bold"><?= esc($p['pic']) ?></td>
                    <td class="text-center"><?= (int) $p['count'] ?></td>
                    <td class="text-right">Rp <?= number_format((int) $p['rencana'], 0, ',', '.') ?></td>
                    <td class="text-right text-teal font-weight-bold">Rp <?= number_format((int) $p['realisasi'], 0, ',', '.') ?></td>
                    <td class="text-right <?= $p['sisa'] < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos' ?>">Rp <?= number_format((int) $p['sisa'], 0, ',', '.') ?></td>
                    <td class="text-right">
                      <span class="<?= $pDevClass ?>"><?= number_format($pDipaPct, 1, ',', '.') ?>%</span>
                      <span class="rma-dipa-badge <?= $pBadgeClass ?> ml-1"><?= esc($pDipaLabel) ?></span>
                      <div class="text-muted" style="font-size: 0.72rem;"><?= $pDevSign ?>Rp <?= number_format(abs($pDev), 0, ',', '.') ?> &bull; Nilai <?= number_format($pDipaNilai, 0, ',', '.') ?></div>
                    </td>
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
          <small class="text-muted">Aturan Deviasi Hal. III DIPA (PER-5/PB/2024): <code>|Realisasi - Rencana| / Rencana</code> (maks. 100%), ambang optimal <strong>&le; 5%</strong> = Nilai <strong>100</strong></small>
        </div>
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
          <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal" id="tableLastUpdatedInfo">
            <i class="far fa-clock text-teal mr-1"></i> Terakhir diperbarui: -
          </span>
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
                <th class="text-right" style="width: 130px;">Rencana (D)</th>
                <th class="text-right" style="width: 130px;">Realisasi (K)</th>
                <th class="text-right" style="width: 125px;">Sisa</th>
                <th class="text-right" style="width: 165px;" title="Rumus Hal. III DIPA PER-5/PB/2024: |Realisasi - RPD| / RPD (maks 100%). Ambang optimal <= 5% (Nilai 100).">Deviasi Hal. III DIPA</th>
                <th class="text-center" style="width: 68px;">%</th>
                <th style="width: 120px;">Waktu Update</th>
                <th class="text-center" style="width: 82px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="monitoringTableBody"></tbody>
            <tfoot>
              <tr class="bg-light font-weight-bold">
                <th colspan="7" class="text-right" id="footTotalLabel">TOTAL:</th>
                <th class="text-right" id="footTotalRencana">Rp 0</th>
                <th class="text-right text-teal" id="footTotalRealisasi">Rp 0</th>
                <th class="text-right" id="footTotalSisa">Rp 0</th>
                <th class="text-right" id="footTotalDeviasi">0,00%</th>
                <th class="text-center" id="footTotalPersen">0%</th>
                <th></th>
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
          <div class="p-2 mb-3 rounded border bg-light">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="formRkksRekapSearch" class="font-weight-bold small mb-0 text-teal">
                <i class="fas fa-sitemap mr-1"></i>
                Ambil dari Rekap RO &amp; Subkomponen RKKS (<?= count($rkksRekapOptions ?? []) ?>)
                <?php if (!empty($rkksDokumen)): ?>
                  <span class="text-muted font-weight-normal">— [TA <?= esc($rkksDokumen['tahun_anggaran']) ?>] <?= esc($rkksDokumen['nama_dokumen']) ?></span>
                <?php endif; ?>
              </label>
              <?php if (empty($rkksRekapOptions)): ?>
                <a href="/relaxtime/rkks" class="small text-teal font-weight-bold">Import RKKS &rarr;</a>
              <?php endif; ?>
            </div>
            <div class="rma-rkks-picker position-relative" id="rkksPickerWrap">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control" id="formRkksRekapSearch" autocomplete="off" placeholder="-- Klik atau ketik RO, Subkomponen, Akun, atau Kegiatan RKKS --">
                <div class="input-group-append">
                  <button type="button" class="btn btn-outline-secondary d-none" id="btnClearRkksPicker" title="Bersihkan pilihan RKKS"><i class="fas fa-times"></i></button>
                  <button type="button" class="btn btn-outline-secondary" id="btnToggleRkksPicker" title="Tampilkan daftar RKKS"><i class="fas fa-chevron-down"></i></button>
                </div>
              </div>
              <input type="hidden" id="formRkksRekapValue" value="">
              <div class="rma-rkks-dropdown d-none" id="rkksPickerDropdown" role="listbox"></div>
            </div>
            <div id="formRkksPaguInfo" class="small text-muted mt-1 d-none"></div>
          </div>
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

<!-- Modal Atur Pagu & Target Triwulanan -->
<div class="modal fade" id="modalTargetTriwulan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-sliders-h mr-1"></i> Atur Pagu Per Jenis Belanja &amp; Target Realisasi (TA <?= (int) $targetYearVal ?>)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formTargetTriwulan">
        <input type="hidden" name="tahun_anggaran" value="<?= (int) $targetYearVal ?>">
        <div class="modal-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-2" style="gap: 8px;">
            <small class="text-muted">Isi Pagu DIPA per jenis belanja (<strong>51</strong>, <strong>52</strong>, <strong>53</strong>) dan persentase target kumulatif untuk setiap Triwulan.</small>
            <button type="button" class="btn btn-xs btn-outline-info" id="btnResetDefaultPct">
              <i class="fas fa-magic mr-1"></i> Isi Target % Standar PER-5/PB/2024
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0 rma-table">
              <thead>
                <tr>
                  <th rowspan="2" class="align-middle" style="width: 120px;">Triwulan</th>
                  <th colspan="2" class="text-center">51 — Belanja Pegawai</th>
                  <th colspan="2" class="text-center">52 — Belanja Barang</th>
                  <th colspan="2" class="text-center">53 — Belanja Modal</th>
                </tr>
                <tr>
                  <th class="text-right">Pagu (Rp)</th>
                  <th class="text-center" style="width: 90px;">Target %</th>
                  <th class="text-right">Pagu (Rp)</th>
                  <th class="text-center" style="width: 90px;">Target %</th>
                  <th class="text-right">Pagu (Rp)</th>
                  <th class="text-center" style="width: 90px;">Target %</th>
                </tr>
              </thead>
              <tbody id="modalTargetTriwulanBody"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold" id="btnSaveTargetTriwulan">
            <i class="fas fa-save mr-1"></i> Simpan Pagu &amp; Target
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ALL_ROWS_INITIAL = <?= json_encode($allRowsJson, JSON_UNESCAPED_UNICODE) ?>;
  let ALL_ROWS = ALL_ROWS_INITIAL;
  const TARGET_YEAR = <?= (int) $targetYearVal ?>;
  let ALL_YEAR_ROWS = <?= json_encode($yearRowsJson, JSON_UNESCAPED_UNICODE) ?>;
  let TARGET_CONFIG = <?= json_encode($targetConfigVal, JSON_UNESCAPED_UNICODE) ?>;
  const DEFAULT_TARGET_PCT = <?= json_encode(\App\Libraries\RelaxtimeMonitoringAnggaranHelper::DEFAULT_TARGET_PENYERAPAN, JSON_UNESCAPED_UNICODE) ?>;
  let activeTargetTw = Math.min(4, Math.max(1, Math.ceil((new Date().getMonth() + 1) / 3)));
  let targetChartInstance = null;
  const RKKS_REKAP_OPTIONS = <?= json_encode(array_values($rkksRekapOptions ?? []), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?: '[]' ?>;
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
  });
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

  function formatSignedRupiah(num) {
    const n = Math.round(Number(num) || 0);
    const absStr = Math.abs(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    const sign = n > 0 ? '+' : (n < 0 ? '-' : '');
    return sign + 'Rp ' + absStr;
  }

  function formatSignedPercent(pct, decimals) {
    const val = Number(pct) || 0;
    const prefix = val > 0 ? '+' : '';
    return prefix + val.toFixed(decimals).replace('.', ',') + '%';
  }

  function deviasiColorClass(val) {
    if (val > 0) return 'text-danger';
    if (val < 0) return 'text-warning';
    return 'text-success';
  }

  function deviasiStatusLabel(val) {
    if (val > 0) return 'Over Budget';
    if (val < 0) return 'Under Budget';
    return 'Sesuai Rencana';
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

  function formatDateTimeIndo(dtStr) {
    const raw = String(dtStr || '').trim();
    const m = raw.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?/);
    if (!m) {
      return { dateStr: '-', timeStr: '', fullStr: '-', orderKey: '' };
    }
    const year = m[1];
    const monIdx = parseInt(m[2], 10) - 1;
    const day = parseInt(m[3], 10);
    const hh = m[4] || '00';
    const ii = m[5] || '00';
    const ss = m[6] || '00';
    const dateStr = day + ' ' + (MONTHS_SHORT[monIdx] || m[2]) + ' ' + year;
    const timeStr = m[4] ? (hh + ':' + ii + ' WIB') : '';
    const fullStr = timeStr ? (dateStr + ', ' + timeStr) : dateStr;
    return {
      dateStr: dateStr,
      timeStr: timeStr,
      fullStr: fullStr,
      orderKey: year + '-' + m[2] + '-' + m[3] + ' ' + hh + ':' + ii + ':' + ss
    };
  }

  function calcDeviasiDipa(rencana, realisasi) {
    const renc = Math.round(Number(rencana) || 0);
    const real = Math.round(Number(realisasi) || 0);
    const selisih = real - renc;
    const deviasiNominal = Math.abs(selisih);
    let deviasiPersen = 0;
    if (renc > 0) {
      deviasiPersen = Math.min(100, Math.round(((deviasiNominal / renc) * 100) * 100) / 100);
    } else if (real > 0) {
      deviasiPersen = 100;
    }

    const memenuhiAmbang = deviasiPersen <= 5;
    const nilaiKinerja = memenuhiAmbang ? 100 : Math.max(0, Math.round((100 - deviasiPersen) * 100) / 100);
    const status = deviasiPersen <= 5 ? 'optimal' : (deviasiPersen <= 15 ? 'waspada' : 'tinggi');

    let statusLabel = 'Optimal (≤5%)';
    if (!memenuhiAmbang) {
      statusLabel = selisih > 0 ? 'Over RPD (>5%)' : (selisih < 0 ? 'Under RPD (>5%)' : 'Melebihi (>5%)');
    }

    return { selisih, deviasiNominal, deviasiPersen, nilaiKinerja, memenuhiAmbang, status, statusLabel };
  }

  function dipaColorClass(status) {
    if (status === 'optimal') return 'text-success';
    if (status === 'waspada') return 'text-warning';
    return 'text-danger';
  }

  function dipaBadgeClass(status) {
    if (status === 'optimal') return 'is-optimal';
    if (status === 'waspada') return 'is-waspada';
    return 'is-tinggi';
  }

  function dipaAccentColor(status) {
    if (status === 'optimal') return '#16a34a';
    if (status === 'waspada') return '#d97706';
    return '#dc2626';
  }

  function computeStats(rows) {
    let totalRencana = 0, totalRealisasi = 0, terealisasi = 0, overBudget = 0;
    const byAkun = {
      '51': { rencana: 0, realisasi: 0, sisa: 0, deviasi: 0, persen: 0, persenDeviasi: 0, deviasiDipaPersen: 0, nilaiDeviasiDipa: 100, dipaStatus: 'optimal', dipaLabel: 'Optimal (≤5%)', count: 0 },
      '52': { rencana: 0, realisasi: 0, sisa: 0, deviasi: 0, persen: 0, persenDeviasi: 0, deviasiDipaPersen: 0, nilaiDeviasiDipa: 100, dipaStatus: 'optimal', dipaLabel: 'Optimal (≤5%)', count: 0 },
      '53': { rencana: 0, realisasi: 0, sisa: 0, deviasi: 0, persen: 0, persenDeviasi: 0, deviasiDipaPersen: 0, nilaiDeviasiDipa: 100, dipaStatus: 'optimal', dipaLabel: 'Optimal (≤5%)', count: 0 }
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
        byPicMap[pic] = { pic: pic, rencana: 0, realisasi: 0, sisa: 0, deviasi: 0, persen: 0, persenDeviasi: 0, deviasiDipaPersen: 0, nilaiDeviasiDipa: 100, dipaStatus: 'optimal', dipaLabel: 'Optimal (≤5%)', count: 0 };
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
      const dipa = calcDeviasiDipa(a.rencana, a.realisasi);
      a.sisa = a.rencana - a.realisasi;
      a.deviasi = dipa.selisih;
      a.persen = a.rencana > 0 ? (a.realisasi / a.rencana) * 100 : 0;
      a.persenDeviasi = a.rencana > 0 ? (a.deviasi / a.rencana) * 100 : 0;
      a.deviasiDipaPersen = dipa.deviasiPersen;
      a.nilaiDeviasiDipa = dipa.nilaiKinerja;
      a.dipaStatus = dipa.status;
      a.dipaLabel = dipa.statusLabel;
    });

    const byPic = Object.values(byPicMap).map(function (p) {
      const dipa = calcDeviasiDipa(p.rencana, p.realisasi);
      p.sisa = p.rencana - p.realisasi;
      p.deviasi = dipa.selisih;
      p.persen = p.rencana > 0 ? (p.realisasi / p.rencana) * 100 : 0;
      p.persenDeviasi = p.rencana > 0 ? (p.deviasi / p.rencana) * 100 : 0;
      p.deviasiDipaPersen = dipa.deviasiPersen;
      p.nilaiDeviasiDipa = dipa.nilaiKinerja;
      p.dipaStatus = dipa.status;
      p.dipaLabel = dipa.statusLabel;
      return p;
    }).sort(function (a, b) { return b.rencana - a.rencana; });

    const byMonth = Object.keys(byMonthMap).sort().map(function (k) { return byMonthMap[k]; });
    const totalSisa = totalRencana - totalRealisasi;
    const totalDeviasi = totalRealisasi - totalRencana;
    const persen = totalRencana > 0 ? (totalRealisasi / totalRencana) * 100 : 0;
    const persenDeviasi = totalRencana > 0 ? (totalDeviasi / totalRencana) * 100 : 0;

    let deviasiDipaTertimbang = 0;
    if (totalRencana > 0) {
      let weighted = 0;
      ['51', '52', '53'].forEach(function (k) {
        const ak = byAkun[k];
        if (ak.rencana > 0) {
          weighted += ak.deviasiDipaPersen * (ak.rencana / totalRencana);
        }
      });
      deviasiDipaTertimbang = Math.min(100, Math.round(weighted * 100) / 100);
    } else if (totalRealisasi > 0) {
      deviasiDipaTertimbang = 100;
    }

    const nilaiIkpaDeviasi = deviasiDipaTertimbang <= 5 ? 100 : Math.max(0, Math.round((100 - deviasiDipaTertimbang) * 100) / 100);
    const deviasiDipaStatus = deviasiDipaTertimbang <= 5 ? 'optimal' : (deviasiDipaTertimbang <= 15 ? 'waspada' : 'tinggi');
    const deviasiDipaLabel = deviasiDipaTertimbang <= 5
      ? 'Optimal (≤5%)'
      : (totalDeviasi > 0 ? 'Over RPD (>5%)' : (totalDeviasi < 0 ? 'Under RPD (>5%)' : 'Melebihi (>5%)'));

    return {
      totalRencana, totalRealisasi, totalSisa, totalDeviasi, persen, persenDeviasi,
      deviasiDipaTertimbang, nilaiIkpaDeviasi, deviasiDipaStatus, deviasiDipaLabel,
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

  function isRowInActiveMonthRange(tanggal) {
    const allMonthsFlag = document.getElementById('filterAllMonths').value === '1';
    if (allMonthsFlag) return true;
    const bMulai = document.getElementById('filterBulanMulai').value;
    const bSelesai = document.getElementById('filterBulanSelesai').value;
    const ym = (tanggal || '').slice(0, 7);
    if (bMulai && ym < bMulai) return false;
    if (bSelesai && ym > bSelesai) return false;
    return true;
  }

  function ensureSelectAndDatalistOption(pic, ro) {
    if (pic && pic.trim() !== '') {
      const pClean = pic.trim();
      const selPic = document.getElementById('filterPic');
      if (selPic && !Array.from(selPic.options).some(o => o.value.toLowerCase() === pClean.toLowerCase())) {
        const opt = document.createElement('option');
        opt.value = pClean;
        opt.textContent = pClean;
        selPic.appendChild(opt);
      }
      const dlPic = document.getElementById('picDatalist');
      if (dlPic && !Array.from(dlPic.options).some(o => o.value.toLowerCase() === pClean.toLowerCase())) {
        const opt = document.createElement('option');
        opt.value = pClean;
        dlPic.appendChild(opt);
      }
    }
    if (ro && ro.trim() !== '') {
      const rClean = ro.trim();
      const dlRo = document.getElementById('roDatalist');
      if (dlRo && !Array.from(dlRo.options).some(o => o.value === rClean)) {
        const opt = document.createElement('option');
        opt.value = rClean;
        dlRo.appendChild(opt);
      }
    }
  }

  function renderDashboardAndTable(opts = {}) {
    const prevScrollY = window.scrollY;
    let prevPage = 0, prevSearch = '', prevLen = 25;
    if (dataTableInstance && (opts.preserveTableState || opts.preserveLengthOnly)) {
      prevPage = dataTableInstance.page() || 0;
      prevSearch = dataTableInstance.search() || '';
      prevLen = dataTableInstance.page.len() || 25;
    }
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

    const devCard = document.getElementById('kpiDeviasiCard');
    const devEl = document.getElementById('kpiTotalDeviasi');
    const devPctEl = document.getElementById('kpiPersenDeviasi');
    if (devCard && devEl && devPctEl) {
      devCard.style.setProperty('--kpi-accent', dipaAccentColor(stats.deviasiDipaStatus));
      devEl.textContent = stats.deviasiDipaTertimbang.toFixed(2).replace('.', ',') + '%';
      devEl.classList.remove('text-danger', 'text-warning', 'text-success');
      devEl.classList.add(dipaColorClass(stats.deviasiDipaStatus));
      devPctEl.innerHTML = 'Nilai: <strong>' + stats.nilaiIkpaDeviasi.toFixed(2).replace('.', ',') + '</strong> &bull; ' + escapeHtml(stats.deviasiDipaLabel) + ' &bull; Selisih ' + formatSignedRupiah(stats.totalDeviasi);
    }

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
      const akunDevEl = document.getElementById('akun' + k + 'Deviasi');
      if (akunDevEl) {
        akunDevEl.innerHTML = a.deviasiDipaPersen.toFixed(1).replace('.', ',') + '% (Nilai ' + Math.round(a.nilaiDeviasiDipa) + ') &bull; ' + formatSignedRupiah(a.deviasi);
        akunDevEl.classList.remove('text-danger', 'text-warning', 'text-success');
        akunDevEl.classList.add(dipaColorClass(a.dipaStatus));
      }

      const cardEl = document.querySelector('[data-akun-card="' + k + '"]');
      if (cardEl) cardEl.classList.toggle('is-selected', akunVal === k);
    });

    // PIC Summary Table
    document.getElementById('badgePicCount').textContent = stats.byPic.length + ' PIC';
    const picBody = document.getElementById('picSummaryBody');
    if (stats.byPic.length === 0) {
      picBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data pada filter terpilih.</td></tr>';
    } else {
      picBody.innerHTML = stats.byPic.map(function (p) {
        const sisaClass = p.sisa < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos';
        const devCls = dipaColorClass(p.dipaStatus) + ' font-weight-bold';
        return '<tr class="rma-pic-row-filter" data-pic-name="' + escapeHtml(p.pic) + '">' +
          '<td class="font-weight-bold">' + escapeHtml(p.pic) + '</td>' +
          '<td class="text-center">' + p.count + '</td>' +
          '<td class="text-right">' + formatRupiah(p.rencana) + '</td>' +
          '<td class="text-right text-teal font-weight-bold">' + formatRupiah(p.realisasi) + '</td>' +
          '<td class="text-right ' + sisaClass + '">' + formatRupiah(p.sisa) + '</td>' +
          '<td class="text-right">' +
            '<span class="' + devCls + '">' + p.deviasiDipaPersen.toFixed(1).replace('.', ',') + '%</span>' +
            '<span class="rma-dipa-badge ' + dipaBadgeClass(p.dipaStatus) + ' ml-1">' + escapeHtml(p.dipaLabel) + '</span>' +
            '<div class="text-muted" style="font-size: 0.72rem;">' + formatSignedRupiah(p.deviasi) + ' &bull; Nilai ' + Math.round(p.nilaiDeviasiDipa) + '</div>' +
          '</td>' +
          '<td class="text-right font-weight-bold">' + p.persen.toFixed(1).replace('.', ',') + '%</td>' +
        '</tr>';
      }).join('');
    }

    // Charts
    renderCharts(stats);

    function updateTableFooter(rowsSubset, hasSearch) {
      const fStats = hasSearch ? computeStats(rowsSubset) : stats;
      const footLabel = document.getElementById('footTotalLabel');
      if (footLabel) {
        footLabel.textContent = hasSearch
          ? ('TOTAL (Hasil Cari \u00b7 ' + rowsSubset.length + ' baris):')
          : 'TOTAL:';
      }
      document.getElementById('footTotalRencana').textContent = formatRupiah(fStats.totalRencana);
      document.getElementById('footTotalRealisasi').textContent = formatRupiah(fStats.totalRealisasi);
      const footSisa = document.getElementById('footTotalSisa');
      footSisa.textContent = formatRupiah(fStats.totalSisa);
      footSisa.classList.toggle('rma-sisa-neg', fStats.totalSisa < 0);
      const footDeviasi = document.getElementById('footTotalDeviasi');
      if (footDeviasi) {
        footDeviasi.innerHTML = '<span class="' + dipaColorClass(fStats.deviasiDipaStatus) + '">' + fStats.deviasiDipaTertimbang.toFixed(2).replace('.', ',') + '%</span>'
          + ' <span class="rma-dipa-badge ' + dipaBadgeClass(fStats.deviasiDipaStatus) + ' ml-1">Nilai ' + fStats.nilaiIkpaDeviasi.toFixed(1).replace('.', ',') + '</span>';
      }
      document.getElementById('footTotalPersen').textContent = fStats.persen.toFixed(1).replace('.', ',') + '%';
    }

    // Table Footer (initial state before DataTable init)
    updateTableFooter(filtered, false);

    // Table Last Updated badge
    let latestTsRaw = '';
    filtered.forEach(function (r) {
      const ts = r.updated_at || r.created_at || '';
      if (ts && ts > latestTsRaw) latestTsRaw = ts;
    });
    const lastUpdEl = document.getElementById('tableLastUpdatedInfo');
    if (lastUpdEl) {
      const latestFmt = formatDateTimeIndo(latestTsRaw);
      lastUpdEl.innerHTML = '<i class="far fa-clock text-teal mr-1"></i> Terakhir diperbarui: <strong>' + escapeHtml(latestFmt.fullStr) + '</strong>';
    }

    // DataTable rebuild
    if (dataTableInstance) {
      dataTableInstance.destroy();
      dataTableInstance = null;
    }

    const tbody = document.getElementById('monitoringTableBody');
    tbody.innerHTML = filtered.map(function (r, idx) {
      const sisa = r.rencana - r.realisasi;
      const pct = r.rencana > 0 ? ((r.realisasi / r.rencana) * 100) : 0;
      const dipa = calcDeviasiDipa(r.rencana, r.realisasi);
      const isDec = (r.tanggal || '').slice(5, 7) === '12';
      const badgeAkun = r.akun === '51' ? 'rma-akun-51' : (r.akun === '53' ? 'rma-akun-53' : 'rma-akun-52');
      const sisaClass = sisa < 0 ? 'rma-sisa-neg' : 'rma-sisa-pos';
      const devColor = dipaColorClass(dipa.status);
      const updRaw = r.updated_at || r.created_at || '';
      const updFmt = formatDateTimeIndo(updRaw);
      const createdFmt = formatDateTimeIndo(r.created_at || '');
      const isEdited = Boolean(r.updated_at && r.created_at && r.updated_at !== r.created_at);
      const sourceLabel = r.sumber_input === 'import' ? 'Import' : 'Manual';
      const updTooltip = 'Dibuat: ' + createdFmt.fullStr + ' | Diperbarui: ' + updFmt.fullStr + ' (' + sourceLabel + ')';
      return '<tr data-id="' + r.id + '" data-row-idx="' + idx + '">' +
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
        '<td class="text-right" data-order="' + dipa.deviasiPersen.toFixed(2) + '">' +
          '<div><span class="font-weight-bold ' + devColor + '">' + dipa.deviasiPersen.toFixed(1).replace('.', ',') + '%</span> ' +
          '<span class="rma-dipa-badge ' + dipaBadgeClass(dipa.status) + '">' + escapeHtml(dipa.statusLabel) + '</span></div>' +
          '<div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">' + formatSignedRupiah(dipa.selisih) + ' &bull; Nilai ' + dipa.nilaiKinerja.toFixed(0) + (isDec ? ' &bull; <em>Des (non-IKPA)</em>' : '') + '</div>' +
        '</td>' +
        '<td class="text-center font-weight-bold" data-order="' + pct.toFixed(2) + '">' + pct.toFixed(1) + '%</td>' +
        '<td data-order="' + escapeHtml(updFmt.orderKey) + '" title="' + escapeHtml(updTooltip) + '">' +
          '<div style="font-size: 0.78rem; white-space: nowrap;">' + escapeHtml(updFmt.dateStr) + '</div>' +
          (updFmt.timeStr ? '<div class="text-muted" style="font-size: 0.7rem; line-height: 1.15; white-space: nowrap;">' + escapeHtml(updFmt.timeStr) + (isEdited ? ' &bull; Diedit' : '') + '</div>' : '') +
        '</td>' +
        '<td class="text-center text-nowrap">' +
          '<button type="button" class="btn btn-xs btn-outline-warning btn-edit-row mr-1" data-id="' + r.id + '" title="Edit"><i class="fas fa-edit"></i></button>' +
          '<button type="button" class="btn btn-xs btn-outline-danger btn-delete-row" data-id="' + r.id + '" title="Hapus"><i class="fas fa-trash"></i></button>' +
        '</td>' +
      '</tr>';
    }).join('');

    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      dataTableInstance = $('#monitoringTable').DataTable({
        pageLength: (opts.preserveTableState || opts.preserveLengthOnly) ? prevLen : 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order: [],
        columnDefs: [
          { orderable: false, targets: [0, 13] },
          { visible: false, searchable: false, targets: [12] }
        ],
        footerCallback: function () {
          const api = this.api();
          const hasSearch = String(api.search() || '').trim() !== '';
          if (!hasSearch) {
            updateTableFooter(filtered, false);
            return;
          }
          const matchedRows = [];
          const nodes = api.rows({ search: 'applied' }).nodes().toArray();
          for (let i = 0; i < nodes.length; i++) {
            const rIdx = parseInt(nodes[i].getAttribute('data-row-idx'), 10);
            if (!Number.isNaN(rIdx) && filtered[rIdx]) {
              matchedRows.push(filtered[rIdx]);
            }
          }
          updateTableFooter(matchedRows, true);
        },
        language: {
          search: 'Cari:',
          lengthMenu: 'Tampilkan _MENU_ baris',
          zeroRecords: 'Tidak ada data kegiatan ditemukan',
          info: 'Menampilkan _START_–_END_ dari _TOTAL_ baris',
          infoEmpty: 'Tidak ada data',
          paginate: { next: 'Berikutnya', previous: 'Sebelumnya' }
        }
      });

      if (opts.preserveTableState) {
        if (prevSearch) dataTableInstance.search(prevSearch);
        dataTableInstance.draw(false);
        const info = dataTableInstance.page.info();
        const safePage = Math.max(0, Math.min(prevPage, Math.max(0, info.pages - 1)));
        if (safePage > 0) dataTableInstance.page(safePage).draw('page');
      }
    }

    if (opts.preserveTableState) {
      window.scrollTo(window.scrollX, prevScrollY);
      requestAnimationFrame(function () {
        window.scrollTo(window.scrollX, prevScrollY);
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
    const dipa = calcDeviasiDipa(renc, real);
    const preview = document.getElementById('formPreviewSisa');
    preview.textContent = 'Sisa: ' + formatRupiah(sisa) + ' (' + pct + '%) | Deviasi Hal. III: ' + dipa.deviasiPersen.toFixed(1) + '% (' + dipa.statusLabel + ', Nilai ' + dipa.nilaiKinerja.toFixed(0) + ')';
    preview.classList.toggle('text-danger', !dipa.memenuhiAmbang);
    preview.classList.toggle('text-success', dipa.memenuhiAmbang && renc > 0);
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

  // Searchable Picker Rekap RO & Subkomponen RKKS (tanpa dependensi Select2)
  const rkksWrap = document.getElementById('rkksPickerWrap');
  const rkksSearch = document.getElementById('formRkksRekapSearch');
  const rkksValue = document.getElementById('formRkksRekapValue');
  const rkksDropdown = document.getElementById('rkksPickerDropdown');
  const rkksClearBtn = document.getElementById('btnClearRkksPicker');
  const rkksToggleBtn = document.getElementById('btnToggleRkksPicker');
  const rkksPaguInfo = document.getElementById('formRkksPaguInfo');
  let rkksActiveIndex = -1;

  const RKKS_PICKER_ITEMS = (Array.isArray(RKKS_REKAP_OPTIONS) ? RKKS_REKAP_OPTIONS : []).map(function (opt, idx) {
    const ro = String(opt.ro_komponen || '');
    const sub = String(opt.subkomponen_kode || '-');
    const keg = String(opt.kegiatan || '-');
    const akun = String(opt.akun || '');
    const sd = String(opt.sumber_dana || '');
    const label = '[' + ro + ' · Sub ' + sub + '] ' + keg;
    return {
      idx: idx,
      opt: opt,
      label: label,
      haystack: [ro, sub, keg, akun, sd].join(' ').toLowerCase()
    };
  });

  function closeRkksDropdown() {
    if (rkksDropdown) rkksDropdown.classList.add('d-none');
    rkksActiveIndex = -1;
  }

  function resetRkksPicker() {
    if (rkksValue) rkksValue.value = '';
    if (rkksSearch) rkksSearch.value = '';
    if (rkksClearBtn) rkksClearBtn.classList.add('d-none');
    if (rkksPaguInfo) rkksPaguInfo.classList.add('d-none');
    closeRkksDropdown();
  }

  function highlightRkksActiveItem() {
    if (!rkksDropdown) return;
    const btns = rkksDropdown.querySelectorAll('.rma-rkks-item');
    btns.forEach(function (btn, i) {
      btn.classList.toggle('is-active', i === rkksActiveIndex);
    });
    if (rkksActiveIndex >= 0 && btns[rkksActiveIndex]) {
      btns[rkksActiveIndex].scrollIntoView({ block: 'nearest' });
    }
  }

  function selectRkksOption(idx) {
    const entry = RKKS_PICKER_ITEMS[idx];
    if (!entry) return;
    const opt = entry.opt;

    if (rkksValue) rkksValue.value = String(idx);
    if (rkksSearch) rkksSearch.value = entry.label;
    if (rkksClearBtn) rkksClearBtn.classList.remove('d-none');
    closeRkksDropdown();

    document.getElementById('formRo').value = opt.ro_komponen || '';
    if (['51', '52', '53'].includes(String(opt.akun || ''))) {
      document.getElementById('formAkun').value = String(opt.akun);
    }
    document.getElementById('formKegiatan').value = opt.kegiatan || '';

    const isCreate = !document.getElementById('formIdMonitoring').value;
    if (isCreate && Number(opt.pagu || 0) > 0) {
      document.getElementById('formRencana').value = String(Math.round(Number(opt.pagu))).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    const picEl = document.getElementById('formPic');
    if (picEl && picEl.value.trim() === '' && String(opt.ro_komponen || '').indexOf('4815.EBA.994') === 0) {
      picEl.value = 'ADUM';
    }

    if (rkksPaguInfo) {
      rkksPaguInfo.innerHTML = 'Pagu RKKS Tahunan: <strong class="text-teal">' + formatRupiah(opt.pagu || 0) + '</strong>'
        + ' | Subkomponen: <strong>' + escapeHtml(opt.subkomponen_kode || '-') + '</strong>'
        + ' | SD: <strong>' + escapeHtml(opt.sumber_dana || '-') + '</strong>'
        + ' <span class="text-muted">(nilai rencana di bawah dapat disesuaikan)</span>';
      rkksPaguInfo.classList.remove('d-none');
    }
    updateManualPreview();
  }

  function openRkksDropdown(query) {
    if (!rkksDropdown) return;
    if (RKKS_PICKER_ITEMS.length === 0) {
      rkksDropdown.innerHTML = '<div class="p-3 text-center text-muted small">Belum ada data Rekap RO &amp; Subkomponen RKKS. Silakan import data di menu <strong>RKKS</strong> terlebih dahulu.</div>';
      rkksDropdown.classList.remove('d-none');
      rkksActiveIndex = -1;
      return;
    }

    const tokens = String(query || '').trim().toLowerCase().split(/\s+/).filter(Boolean);
    const matches = tokens.length === 0
      ? RKKS_PICKER_ITEMS
      : RKKS_PICKER_ITEMS.filter(function (entry) {
          return tokens.every(function (tok) { return entry.haystack.indexOf(tok) !== -1; });
        });

    if (matches.length === 0) {
      rkksDropdown.innerHTML = '<div class="rma-rkks-dropdown-header">0 dari ' + RKKS_PICKER_ITEMS.length + ' item RKKS</div>'
        + '<div class="p-3 text-center text-muted small">Tidak ada kegiatan RKKS yang cocok dengan "' + escapeHtml(query) + '".</div>';
      rkksDropdown.classList.remove('d-none');
      rkksActiveIndex = -1;
      return;
    }

    rkksActiveIndex = 0;
    rkksDropdown.innerHTML = '<div class="rma-rkks-dropdown-header">Menampilkan ' + matches.length + ' dari ' + RKKS_PICKER_ITEMS.length + ' item RKKS</div>'
      + matches.map(function (entry, i) {
          const o = entry.opt;
          return '<button type="button" class="rma-rkks-item' + (i === 0 ? ' is-active' : '') + '" role="option" data-rkks-idx="' + entry.idx + '">' +
            '<div class="d-flex justify-content-between align-items-center mb-1">' +
              '<span><strong class="text-teal">' + escapeHtml(o.ro_komponen || '-') + '</strong> <span class="badge badge-light border">Sub ' + escapeHtml(o.subkomponen_kode || '-') + '</span> <span class="badge badge-info">Akun ' + escapeHtml(o.akun || '-') + '</span></span>' +
              '<span class="font-weight-bold text-dark">' + formatRupiah(o.pagu || 0) + '</span>' +
            '</div>' +
            '<div class="text-secondary">' + escapeHtml(o.kegiatan || '-') + '</div>' +
          '</button>';
        }).join('');
    rkksDropdown.classList.remove('d-none');
  }

  if (rkksSearch && rkksDropdown) {
    rkksSearch.addEventListener('focus', function () {
      const q = (rkksValue && rkksValue.value !== '') ? '' : this.value;
      openRkksDropdown(q);
    });
    rkksSearch.addEventListener('click', function () {
      const q = (rkksValue && rkksValue.value !== '') ? '' : this.value;
      openRkksDropdown(q);
    });
    rkksSearch.addEventListener('input', function () {
      if (rkksValue) rkksValue.value = '';
      if (rkksPaguInfo) rkksPaguInfo.classList.add('d-none');
      if (rkksClearBtn) rkksClearBtn.classList.toggle('d-none', this.value === '');
      openRkksDropdown(this.value);
    });
    rkksSearch.addEventListener('keydown', function (e) {
      if (rkksDropdown.classList.contains('d-none')) {
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          openRkksDropdown(rkksValue && rkksValue.value !== '' ? '' : this.value);
        }
        return;
      }
      const btns = rkksDropdown.querySelectorAll('.rma-rkks-item');
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (btns.length > 0) {
          rkksActiveIndex = Math.min(btns.length - 1, rkksActiveIndex + 1);
          highlightRkksActiveItem();
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (btns.length > 0) {
          rkksActiveIndex = Math.max(0, rkksActiveIndex - 1);
          highlightRkksActiveItem();
        }
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (btns.length > 0) {
          const targetBtn = btns[Math.max(0, rkksActiveIndex)] || btns[0];
          const idx = parseInt(targetBtn.getAttribute('data-rkks-idx'), 10);
          if (!Number.isNaN(idx)) selectRkksOption(idx);
        }
      } else if (e.key === 'Escape') {
        e.preventDefault();
        closeRkksDropdown();
      }
    });

    rkksDropdown.addEventListener('mousedown', function (e) {
      const itemBtn = e.target.closest('.rma-rkks-item');
      if (!itemBtn) return;
      e.preventDefault();
      const idx = parseInt(itemBtn.getAttribute('data-rkks-idx'), 10);
      if (!Number.isNaN(idx)) selectRkksOption(idx);
    });
  }

  if (rkksToggleBtn) {
    rkksToggleBtn.addEventListener('click', function () {
      if (!rkksDropdown) return;
      if (rkksDropdown.classList.contains('d-none')) {
        openRkksDropdown('');
        if (rkksSearch) rkksSearch.focus();
      } else {
        closeRkksDropdown();
      }
    });
  }

  if (rkksClearBtn) {
    rkksClearBtn.addEventListener('click', function () {
      resetRkksPicker();
      openRkksDropdown('');
      if (rkksSearch) rkksSearch.focus();
    });
  }

  document.addEventListener('mousedown', function (e) {
    if (rkksWrap && !rkksWrap.contains(e.target)) {
      closeRkksDropdown();
    }
  });

  if (typeof $ !== 'undefined') {
    $('#modalManualAnggaran').on('hidden.bs.modal', closeRkksDropdown);
  }

  function renderTargetComparison() {
    const bodyEl = document.getElementById('targetComparisonBody');
    const footEl = document.getElementById('targetComparisonFoot');
    if (!bodyEl || !footEl) return;

    const akuns = [
      { code: '51', label: 'Belanja Pegawai', badge: 'rma-akun-51' },
      { code: '52', label: 'Belanja Barang', badge: 'rma-akun-52' },
      { code: '53', label: 'Belanja Modal', badge: 'rma-akun-53' }
    ];
    const twNames = { 1: 'Triwulan I', 2: 'Triwulan II', 3: 'Triwulan III', 4: 'Triwulan IV' };
    const twRanges = { 1: 'Jan–Mar', 2: 'Jan–Jun', 3: 'Jan–Sep', 4: 'Jan–Des' };

    const realByQAkun = {
      1: { '51': 0, '52': 0, '53': 0 },
      2: { '51': 0, '52': 0, '53': 0 },
      3: { '51': 0, '52': 0, '53': 0 },
      4: { '51': 0, '52': 0, '53': 0 }
    };
    const yearPrefix = String(TARGET_YEAR) + '-';
    (ALL_YEAR_ROWS || []).forEach(function (r) {
      const tgl = String(r.tanggal || '');
      if (tgl.indexOf(yearPrefix) !== 0) return;
      const mo = parseInt(tgl.slice(5, 7), 10);
      if (Number.isNaN(mo) || mo < 1 || mo > 12) return;
      const tw = Math.ceil(mo / 3);
      const akun = ['51', '52', '53'].includes(String(r.akun)) ? String(r.akun) : '52';
      realByQAkun[tw][akun] += Number(r.realisasi) || 0;
    });

    const cumReal = { '51': 0, '52': 0, '53': 0 };
    const quarters = {};
    for (let tw = 1; tw <= 4; tw++) {
      let totPagu = 0, totTarget = 0, totReal = 0, weightedSum = 0;
      const byAkun = {};

      akuns.forEach(function (aCfg) {
        const ak = aCfg.code;
        cumReal[ak] += realByQAkun[tw][ak];
        const rawCfg = (TARGET_CONFIG[tw] && TARGET_CONFIG[tw][ak]) ? TARGET_CONFIG[tw][ak] : { pagu: 0, target_persen: (DEFAULT_TARGET_PCT[ak] && DEFAULT_TARGET_PCT[ak][tw]) || 0 };
        const pagu = Math.max(0, Math.round(Number(rawCfg.pagu) || 0));
        const targetPct = Math.max(0, Math.min(100, Number(rawCfg.target_persen) || 0));
        const targetNominal = Math.round(pagu * (targetPct / 100));
        const realKum = cumReal[ak];
        const realPctPagu = pagu > 0 ? (realKum / pagu) * 100 : 0;
        const selisih = realKum - targetNominal;
        const capaianVsTarget = targetNominal > 0 ? (realKum / targetNominal) * 100 : (realKum > 0 ? 100 : 0);
        const nilaiIkpa = targetNominal > 0 ? Math.min(100, capaianVsTarget) : 100;
        const tercapai = pagu === 0 ? true : realKum >= targetNominal;

        byAkun[ak] = {
          pagu: pagu,
          targetPct: targetPct,
          targetNominal: targetNominal,
          realKum: realKum,
          realPctPagu: realPctPagu,
          selisih: selisih,
          capaianVsTarget: capaianVsTarget,
          nilaiIkpa: nilaiIkpa,
          tercapai: tercapai
        };
        totPagu += pagu;
        totTarget += targetNominal;
        totReal += realKum;
      });

      if (totPagu > 0) {
        akuns.forEach(function (aCfg) {
          const item = byAkun[aCfg.code];
          if (item.pagu > 0) weightedSum += item.nilaiIkpa * (item.pagu / totPagu);
        });
      } else {
        weightedSum = 100;
      }

      quarters[tw] = {
        tw: tw,
        nama: twNames[tw],
        periode: twRanges[tw] + ' ' + TARGET_YEAR,
        byAkun: byAkun,
        total: {
          pagu: totPagu,
          targetPct: totPagu > 0 ? (totTarget / totPagu) * 100 : 0,
          targetNominal: totTarget,
          realKum: totReal,
          realPctPagu: totPagu > 0 ? (totReal / totPagu) * 100 : 0,
          selisih: totReal - totTarget,
          nilaiIkpa: Math.min(100, weightedSum),
          tercapai: totPagu === 0 ? true : totReal >= totTarget
        }
      };
    }

    const q = quarters[activeTargetTw] || quarters[4];
    document.querySelectorAll('.rma-tw-btn').forEach(function (b) {
      b.classList.toggle('active', parseInt(b.getAttribute('data-tw'), 10) === activeTargetTw);
    });
    const subEl = document.getElementById('targetPeriodSubtitle');
    if (subEl) subEl.textContent = q.nama + ' (' + q.periode + ')';
    const chartLblEl = document.getElementById('chartTargetTwLabel');
    if (chartLblEl) chartLblEl.textContent = 'TW ' + (['', 'I', 'II', 'III', 'IV'][q.tw] || q.tw);
    const scoreBadgeEl = document.getElementById('targetIkpaScoreBadge');
    if (scoreBadgeEl) {
      const ikpaCls = q.total.nilaiIkpa >= 95 ? 'text-success' : (q.total.nilaiIkpa >= 80 ? 'text-warning' : 'text-danger');
      scoreBadgeEl.innerHTML = 'Nilai Penyerapan: <strong class="' + ikpaCls + '">' + q.total.nilaiIkpa.toFixed(2).replace('.', ',') + '</strong>';
    }

    bodyEl.innerHTML = akuns.map(function (aCfg) {
      const d = q.byAkun[aCfg.code];
      const barPct = Math.min(100, d.realPctPagu);
      const targetMarker = Math.min(100, d.targetPct);
      const statusBadge = d.pagu === 0
        ? '<span class="badge badge-light border">Pagu 0</span>'
        : (d.tercapai
          ? '<span class="rma-dipa-badge is-optimal">Tercapai &bull; Nilai ' + d.nilaiIkpa.toFixed(1).replace('.', ',') + '</span>'
          : '<span class="rma-dipa-badge ' + (d.nilaiIkpa >= 80 ? 'is-waspada' : 'is-tinggi') + '">Kurang &bull; Nilai ' + d.nilaiIkpa.toFixed(1).replace('.', ',') + '</span>');
      const selCls = d.selisih >= 0 ? 'text-success' : 'text-danger';

      return '<tr>' +
        '<td><span class="rma-badge-akun ' + aCfg.badge + '">' + aCfg.code + '</span> <span class="font-weight-bold ml-1">' + escapeHtml(aCfg.label) + '</span></td>' +
        '<td class="text-right font-weight-bold">' + formatRupiah(d.pagu) + '</td>' +
        '<td class="text-right">' +
          '<div class="font-weight-bold text-primary">' + formatRupiah(d.targetNominal) + '</div>' +
          '<div class="text-muted" style="font-size: 0.72rem;">Target: ' + d.targetPct.toFixed(1).replace('.', ',') + '%</div>' +
        '</td>' +
        '<td class="text-right">' +
          '<div class="font-weight-bold text-teal">' + formatRupiah(d.realKum) + '</div>' +
          '<div class="text-muted" style="font-size: 0.72rem;">' + d.realPctPagu.toFixed(2).replace('.', ',') + '% dari pagu</div>' +
        '</td>' +
        '<td>' +
          '<div class="position-relative bg-light border rounded" style="height: 10px; overflow: hidden;">' +
            '<div class="h-100 ' + (d.tercapai ? 'bg-success' : 'bg-warning') + '" style="width:' + barPct.toFixed(1) + '%;"></div>' +
            '<div title="Garis Target ' + d.targetPct.toFixed(1) + '%" style="position:absolute; top:0; bottom:0; left:' + targetMarker.toFixed(1) + '%; width:2px; background:#0f172a;"></div>' +
          '</div>' +
          '<div class="d-flex justify-content-between mt-1" style="font-size: 0.71rem;">' +
            '<span class="' + selCls + ' font-weight-bold">' + formatSignedRupiah(d.selisih) + '</span>' +
            '<span class="text-muted">' + d.capaianVsTarget.toFixed(1).replace('.', ',') + '% target</span>' +
          '</div>' +
        '</td>' +
        '<td class="text-center">' + statusBadge + '</td>' +
      '</tr>';
    }).join('');

    const tot = q.total;
    const totSelCls = tot.selisih >= 0 ? 'text-success' : 'text-danger';
    const totBadge = tot.pagu === 0
      ? '<span class="badge badge-light border">-</span>'
      : '<span class="rma-dipa-badge ' + (tot.nilaiIkpa >= 95 ? 'is-optimal' : (tot.nilaiIkpa >= 80 ? 'is-waspada' : 'is-tinggi')) + '">IKPA ' + tot.nilaiIkpa.toFixed(2).replace('.', ',') + '</span>';

    footEl.innerHTML = '<tr class="bg-light font-weight-bold">' +
      '<td class="text-right">TOTAL SATKER:</td>' +
      '<td class="text-right">' + formatRupiah(tot.pagu) + '</td>' +
      '<td class="text-right text-primary">' + formatRupiah(tot.targetNominal) + ' <div class="text-muted font-weight-normal" style="font-size: 0.71rem;">(' + tot.targetPct.toFixed(2).replace('.', ',') + '%)</div></td>' +
      '<td class="text-right text-teal">' + formatRupiah(tot.realKum) + ' <div class="text-muted font-weight-normal" style="font-size: 0.71rem;">(' + tot.realPctPagu.toFixed(2).replace('.', ',') + '%)</div></td>' +
      '<td class="text-right ' + totSelCls + '">' + formatSignedRupiah(tot.selisih) + '</td>' +
      '<td class="text-center">' + totBadge + '</td>' +
    '</tr>';

    // Matriks Lengkap TW I - IV
    const matrixEl = document.getElementById('targetMatrixBody');
    if (matrixEl) {
      matrixEl.innerHTML = [1, 2, 3, 4].map(function (twIdx) {
        const qRow = quarters[twIdx];
        const isCurrent = twIdx === activeTargetTw;
        let cells = '<td class="font-weight-bold' + (isCurrent ? ' text-teal' : '') + '">' + escapeHtml(qRow.nama) + '<div class="text-muted font-weight-normal" style="font-size:0.7rem;">' + escapeHtml(qRow.periode) + '</div></td>';
        akuns.forEach(function (aCfg) {
          const item = qRow.byAkun[aCfg.code];
          cells += '<td class="text-right">' + formatRupiah(item.pagu) + '</td>' +
            '<td class="text-right text-primary">' + formatRupiah(item.targetNominal) + '<div class="text-muted" style="font-size:0.7rem;">(' + item.targetPct.toFixed(0) + '%)</div></td>' +
            '<td class="text-right font-weight-bold ' + (item.tercapai ? 'text-success' : 'text-danger') + '">' + formatRupiah(item.realKum) + '<div class="text-muted font-weight-normal" style="font-size:0.7rem;">' + item.realPctPagu.toFixed(1).replace('.', ',') + '%</div></td>';
        });
        cells += '<td class="text-right font-weight-bold text-primary">' + formatRupiah(qRow.total.targetNominal) + '</td>' +
          '<td class="text-right font-weight-bold text-teal">' + formatRupiah(qRow.total.realKum) + '<div class="text-muted font-weight-normal" style="font-size:0.7rem;">IKPA ' + qRow.total.nilaiIkpa.toFixed(1).replace('.', ',') + '</div></td>';
        return '<tr class="' + (isCurrent ? 'table-info' : '') + '">' + cells + '</tr>';
      }).join('');
    }

    // Grouped Bar Chart: Pagu vs Target vs Realisasi per Akun
    if (typeof Chart !== 'undefined' && document.getElementById('chartTargetVsRealisasi')) {
      const ctxTarget = document.getElementById('chartTargetVsRealisasi').getContext('2d');
      if (targetChartInstance) targetChartInstance.destroy();
      targetChartInstance = new Chart(ctxTarget, {
        type: 'bar',
        data: {
          labels: ['51 Pegawai', '52 Barang', '53 Modal'],
          datasets: [
            {
              label: 'Pagu DIPA',
              data: akuns.map(a => q.byAkun[a.code].pagu),
              backgroundColor: 'rgba(148, 163, 184, 0.45)',
              borderColor: '#64748b',
              borderWidth: 1
            },
            {
              label: 'Target Realisasi',
              data: akuns.map(a => q.byAkun[a.code].targetNominal),
              backgroundColor: 'rgba(2, 132, 199, 0.78)',
              borderColor: '#0284c7',
              borderWidth: 1
            },
            {
              label: 'Realisasi Kumulatif',
              data: akuns.map(a => q.byAkun[a.code].realKum),
              backgroundColor: 'rgba(15, 118, 110, 0.88)',
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
    }
  }

  function populateTargetTriwulanModal() {
    const tbody = document.getElementById('modalTargetTriwulanBody');
    if (!tbody) return;
    const twNames = { 1: 'Triwulan I', 2: 'Triwulan II', 3: 'Triwulan III', 4: 'Triwulan IV' };
    tbody.innerHTML = [1, 2, 3, 4].map(function (tw) {
      let cols = '<td class="font-weight-bold align-middle">' + twNames[tw] + '</td>';
      ['51', '52', '53'].forEach(function (ak) {
        const cfg = (TARGET_CONFIG[tw] && TARGET_CONFIG[tw][ak]) ? TARGET_CONFIG[tw][ak] : { pagu: 0, target_persen: (DEFAULT_TARGET_PCT[ak] && DEFAULT_TARGET_PCT[ak][tw]) || 0 };
        const paguFmt = String(Math.round(Number(cfg.pagu) || 0)).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        cols += '<td><input type="text" class="form-control form-control-sm text-right rma-target-pagu-input" name="pagu[' + tw + '][' + ak + ']" value="' + paguFmt + '" required></td>' +
          '<td><input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm text-center rma-target-pct-input" data-tw="' + tw + '" data-akun="' + ak + '" name="target_persen[' + tw + '][' + ak + ']" value="' + Number(cfg.target_persen || 0) + '" required></td>';
      });
      return '<tr>' + cols + '</tr>';
    }).join('');
  }

  // Open Input Manual Modal
  document.getElementById('btnInputManual').addEventListener('click', function () {
    document.getElementById('formIdMonitoring').value = '';
    document.getElementById('modalManualTitle').innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Input Manual Monitoring Anggaran';
    document.getElementById('formManualAnggaran').reset();
    document.getElementById('formBulan').value = '<?= date('Y-m') ?>';
    document.getElementById('formAkun').value = '52';
    document.getElementById('formRealisasi').value = '0';
    resetRkksPicker();
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
      if (json.row) {
        ensureSelectAndDatalistOption(json.row.pic, json.row.ro);
        const idx = ALL_ROWS.findIndex(r => r.id === json.row.id);
        if (isRowInActiveMonthRange(json.row.tanggal)) {
          if (idx >= 0) {
            ALL_ROWS[idx] = json.row;
          } else {
            ALL_ROWS.unshift(json.row);
          }
        } else if (idx >= 0) {
          ALL_ROWS.splice(idx, 1);
        }

        const yrIdx = ALL_YEAR_ROWS.findIndex(r => r.id === json.row.id);
        if (String(json.row.tanggal || '').indexOf(String(TARGET_YEAR) + '-') === 0) {
          if (yrIdx >= 0) {
            ALL_YEAR_ROWS[yrIdx] = json.row;
          } else {
            ALL_YEAR_ROWS.unshift(json.row);
          }
        } else if (yrIdx >= 0) {
          ALL_YEAR_ROWS.splice(yrIdx, 1);
        }
      }
      renderDashboardAndTable({ preserveTableState: Boolean(id), preserveLengthOnly: !id });
      renderTargetComparison();
      Toast.fire({ icon: 'success', title: json.message });
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
      resetRkksPicker();
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
        const delId = parseInt(json.deleted_id || id, 10);
        ALL_ROWS = ALL_ROWS.filter(r => r.id !== delId);
        ALL_YEAR_ROWS = ALL_YEAR_ROWS.filter(r => r.id !== delId);
        renderDashboardAndTable({ preserveTableState: true });
        renderTargetComparison();
        Toast.fire({ icon: 'success', title: json.message });
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
      const delSet = new Set((json.deleted_ids || ids).map(v => parseInt(v, 10)));
      ALL_ROWS = ALL_ROWS.filter(r => !delSet.has(r.id));
      ALL_YEAR_ROWS = ALL_YEAR_ROWS.filter(r => !delSet.has(r.id));
      document.getElementById('checkAllRows').checked = false;
      renderDashboardAndTable({ preserveTableState: true });
      renderTargetComparison();
      Toast.fire({ icon: 'success', title: json.message });
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

  // Target Realisasi Triwulanan events
  document.querySelectorAll('.rma-tw-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      activeTargetTw = parseInt(this.getAttribute('data-tw'), 10) || 4;
      renderTargetComparison();
    });
  });

  const btnEditTarget = document.getElementById('btnEditTargetTriwulan');
  if (btnEditTarget) {
    btnEditTarget.addEventListener('click', function () {
      populateTargetTriwulanModal();
      $('#modalTargetTriwulan').modal('show');
    });
  }

  const modalTargetBody = document.getElementById('modalTargetTriwulanBody');
  if (modalTargetBody) {
    modalTargetBody.addEventListener('input', function (e) {
      if (e.target.classList.contains('rma-target-pagu-input')) {
        const digits = e.target.value.replace(/[^\d]/g, '');
        e.target.value = digits === '' ? '' : digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }
    });
  }

  const btnResetDefaultPct = document.getElementById('btnResetDefaultPct');
  if (btnResetDefaultPct) {
    btnResetDefaultPct.addEventListener('click', function () {
      document.querySelectorAll('.rma-target-pct-input').forEach(function (inp) {
        const tw = inp.getAttribute('data-tw');
        const ak = inp.getAttribute('data-akun');
        if (DEFAULT_TARGET_PCT[ak] && DEFAULT_TARGET_PCT[ak][tw] !== undefined) {
          inp.value = DEFAULT_TARGET_PCT[ak][tw];
        }
      });
    });
  }

  const formTargetTw = document.getElementById('formTargetTriwulan');
  if (formTargetTw) {
    formTargetTw.addEventListener('submit', async function (e) {
      e.preventDefault();
      const fd = new FormData(this);
      const btn = document.getElementById('btnSaveTargetTriwulan');
      btn.disabled = true;
      try {
        const res = await fetch('/relaxtime/monitoring-anggaran/save-target-triwulan', {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: fd
        });
        const json = await res.json();
        if (res.ok && json.success) {
          TARGET_CONFIG = json.config || TARGET_CONFIG;
          $('#modalTargetTriwulan').modal('hide');
          renderTargetComparison();
          Toast.fire({ icon: 'success', title: json.message });
        } else {
          Swal.fire('Gagal', json.message || 'Gagal menyimpan pagu & target triwulan.', 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'Terjadi kesalahan koneksi saat menyimpan target.', 'error');
      } finally {
        btn.disabled = false;
      }
    });
  }

  // Initial render
  renderDashboardAndTable();
  renderTargetComparison();
});
</script>
<?= $this->endSection() ?>
