<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<?php
if (!function_exists('mediumdate_indo')) {
  helper('tanggal');
}
$rankingRows = is_array($rankingRows ?? null) ? $rankingRows : [];
$summary = is_array($summary ?? null) ? $summary : [];
$timKerjaList = is_array($timKerjaList ?? null) ? $timKerjaList : [];
$timKerjaSummary = is_array($timKerjaSummary ?? null) ? $timKerjaSummary : [];
$timKerjaGrandTotal = is_array($timKerjaGrandTotal ?? null) ? $timKerjaGrandTotal : [];
$accessUsers = is_array($accessUsers ?? null) ? $accessUsers : [];
$sortMode = ($sortMode ?? 'penghasilan') === 'frekuensi' ? 'frekuensi' : 'penghasilan';
$startDate = (string) ($startDate ?? date('Y-01-01'));
$endDate = (string) ($endDate ?? date('Y-m-d'));
$timKerjaFilter = (string) ($timKerjaFilter ?? 'all');
$selectedTimNama = (string) ($selectedTimNama ?? 'Semua Tim Kerja');
$canManageAccess = !empty($canManageAccess);
$costComponents = is_array($costComponents ?? null) ? $costComponents : ['transport' => true, 'harian' => true, 'penginapan' => false];
$incTransport = !empty($costComponents['transport']);
$incHarian = !empty($costComponents['harian']);
$incPenginapan = !empty($costComponents['penginapan']);

$buildQueryUrl = static function (array $overrides) use ($startDate, $endDate, $timKerjaFilter, $sortMode, $incTransport, $incHarian, $incPenginapan): string {
  $params = array_merge([
    'start_date'     => $startDate,
    'end_date'       => $endDate,
    'tim_kerja'      => $timKerjaFilter,
    'sort'           => $sortMode,
    'biaya_applied'  => '1',
    'inc_transport'  => $incTransport ? '1' : '0',
    'inc_harian'     => $incHarian ? '1' : '0',
    'inc_penginapan' => $incPenginapan ? '1' : '0',
  ], $overrides);
  return '/sinadi/perjalananSaya?' . http_build_query($params);
};
?>

<style>
  .mdl-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: 14px;
    padding: 18px 22px;
    color: #ffffff;
    box-shadow: 0 8px 22px rgba(4, 120, 121, 0.16);
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .mdl-hero-title {
    font-size: 1.22rem;
    font-weight: 800;
    margin: 0 0 4px;
    color: #ffffff;
  }
  .mdl-hero-sub {
    font-size: 0.84rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
  }
  .mdl-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    margin-bottom: 16px;
  }
  .mdl-stat-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 12px;
    padding: 14px 16px;
    height: 100%;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
    position: relative;
    overflow: hidden;
  }
  .mdl-stat-card.accent-gold {
    border-top: 4px solid #f59e0b;
  }
  .mdl-stat-card.accent-teal {
    border-top: 4px solid #0d9488;
  }
  .mdl-stat-card.accent-indigo {
    border-top: 4px solid #4f46e5;
  }
  .mdl-stat-card.accent-emerald {
    border-top: 4px solid #10b981;
  }
  .mdl-stat-label {
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b;
    margin-bottom: 6px;
  }
  .mdl-stat-value {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    margin-bottom: 4px;
  }
  .mdl-stat-sub {
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.35;
  }
  .mdl-rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 30px;
    padding: 0 8px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 0.84rem;
  }
  .mdl-rank-1 {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 1px solid #f59e0b;
  }
  .mdl-rank-2 {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #94a3b8;
  }
  .mdl-rank-3 {
    background: #ffedd5;
    color: #9a3412;
    border: 1px solid #fb923c;
  }
  .mdl-rank-other {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
  }
  .mdl-sort-pills {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 9px;
    gap: 4px;
  }
  .mdl-sort-pill {
    padding: 5px 12px;
    border-radius: 7px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #475569 !important;
    text-decoration: none !important;
    transition: all 0.15s ease;
  }
  .mdl-sort-pill.active {
    background: #0d9488;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(13, 148, 136, 0.25);
  }
  #tableMonitoringDl th {
    font-size: 0.78rem;
    white-space: nowrap;
    background: #f8fafc;
    color: #1e293b;
  }
  #tableMonitoringDl td {
    vertical-align: middle;
    font-size: 0.82rem;
  }
  .mdl-access-item {
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    padding: 8px 12px;
    margin-bottom: 8px;
    background: #ffffff;
  }
  .mdl-card-header-flex::after {
    display: none !important;
    content: none !important;
  }
  .mdl-cost-check-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
  }
  .mdl-cost-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    min-height: 31px;
    margin: 0;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    font-size: 0.78rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    user-select: none;
    white-space: nowrap;
    transition: all 0.15s ease;
  }
  .mdl-cost-chip:hover {
    border-color: #0d9488;
    background: #f0fdfa;
  }
  .mdl-cost-chip input[type="checkbox"] {
    accent-color: #0d9488;
    cursor: pointer;
  }
  .mdl-collapse-toggle {
    cursor: pointer;
  }
  .mdl-collapse-chevron {
    transition: transform 0.2s ease;
  }
  .mdl-collapse-toggle[aria-expanded="true"] .mdl-collapse-chevron {
    transform: rotate(180deg);
  }
</style>

<section class="content">
  <div class="container-fluid">
    <!-- Hero Header -->
    <div class="mdl-hero">
      <div>
        <h4 class="mdl-hero-title">
          <i class="fas fa-chart-bar mr-2"></i>Monitoring Dinas Luar
        </h4>
        <p class="mdl-hero-sub">
          Peringkat penghasilan &amp; frekuensi perjalanan Dinas Luar perorangan &bull;
          Status: <strong>Disetujui</strong> &bull;
          Periode: <strong><?= esc(mediumdate_indo($startDate)); ?></strong> s.d. <strong><?= esc(mediumdate_indo($endDate)); ?></strong> &bull;
          Tim: <strong><?= esc($selectedTimNama); ?></strong> &bull;
          Pegawai: <strong>Sesuai DUK (<?= (int) ($dukTotal ?? 0); ?> Pegawai &bull; <?= esc($dukPeriode ?? 'Keadaan Oktober 2026'); ?>)</strong>
        </p>
      </div>
      <?php if ($canManageAccess) : ?>
        <div>
          <button type="button" class="btn btn-light btn-sm font-weight-bold text-teal shadow-sm" data-toggle="modal" data-target="#modalAksesMonitoringDl">
            <i class="fas fa-user-shield mr-1"></i> Pengaturan Akses User
          </button>
        </div>
      <?php endif; ?>
    </div>

    <!-- Filter Card (Aktif Otomatis) -->
    <div class="mdl-card p-3">
      <form method="get" action="/sinadi/perjalananSaya" id="formFilterMonitoringDl">
        <input type="hidden" name="biaya_applied" value="1">
        <div class="d-flex flex-wrap align-items-end" style="gap: 10px;">
          <div style="flex: 1 1 200px; max-width: 250px;">
            <label for="filterTimKerja" class="small font-weight-bold text-muted mb-1">Tim Kerja</label>
            <select name="tim_kerja" id="filterTimKerja" class="form-control form-control-sm mdl-auto-filter">
              <option value="all" <?= $timKerjaFilter === 'all' ? 'selected' : ''; ?>>Semua Tim Kerja</option>
              <?php foreach ($timKerjaList as $tk) : ?>
                <?php $tkId = (string) ($tk['id_tim_kerja'] ?? ''); ?>
                <option value="<?= esc($tkId, 'attr'); ?>" <?= $timKerjaFilter === $tkId ? 'selected' : ''; ?>>
                  <?= esc($tk['nama_tim_kerja'] ?? ''); ?>
                </option>
              <?php endforeach; ?>
              <option value="none" <?= $timKerjaFilter === 'none' ? 'selected' : ''; ?>>Tanpa Tim Kerja</option>
            </select>
          </div>
          <div style="width: 145px;">
            <label for="filterStartDate" class="small font-weight-bold text-muted mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" id="filterStartDate" class="form-control form-control-sm mdl-auto-filter" value="<?= esc($startDate, 'attr'); ?>">
          </div>
          <div style="width: 145px;">
            <label for="filterEndDate" class="small font-weight-bold text-muted mb-1">Tanggal Selesai</label>
            <input type="date" name="end_date" id="filterEndDate" class="form-control form-control-sm mdl-auto-filter" value="<?= esc($endDate, 'attr'); ?>">
          </div>
          <div>
            <label class="small font-weight-bold text-muted mb-1 d-block">Perhitungan Biaya</label>
            <div class="mdl-cost-check-group">
              <label class="mdl-cost-chip">
                <input type="checkbox" name="inc_transport" value="1" class="mdl-auto-filter" <?= $incTransport ? 'checked' : ''; ?>>
                <span>Transport</span>
              </label>
              <label class="mdl-cost-chip">
                <input type="checkbox" name="inc_harian" value="1" class="mdl-auto-filter" <?= $incHarian ? 'checked' : ''; ?>>
                <span>Uang Harian</span>
              </label>
              <label class="mdl-cost-chip">
                <input type="checkbox" name="inc_penginapan" value="1" class="mdl-auto-filter" <?= $incPenginapan ? 'checked' : ''; ?>>
                <span>Penginapan</span>
              </label>
            </div>
          </div>
          <div class="ml-xl-auto d-flex flex-wrap align-items-end" style="gap: 8px;">
            <div style="min-width: 180px;">
              <label for="filterSortMode" class="small font-weight-bold text-muted mb-1">Urutkan Peringkat</label>
              <select name="sort" id="filterSortMode" class="form-control form-control-sm mdl-auto-filter">
                <option value="penghasilan" <?= $sortMode === 'penghasilan' ? 'selected' : ''; ?>>Penghasilan Terbanyak</option>
                <option value="frekuensi" <?= $sortMode === 'frekuensi' ? 'selected' : ''; ?>>Paling Sering DL</option>
              </select>
            </div>
            <a href="/sinadi/perjalananSaya" class="btn btn-sm btn-outline-secondary" style="height: 31px;" title="Reset filter ke default">
              <i class="fas fa-undo mr-1"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>

    <!-- 4 Kartu Ringkasan & Highlight #1 -->
    <div class="row mb-3">
      <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="mdl-stat-card accent-teal">
          <div class="mdl-stat-label"><i class="fas fa-coins text-teal mr-1"></i> #1 Penghasilan Terbanyak</div>
          <?php if (!empty($topPenghasilan)) : ?>
            <div class="mdl-stat-value"><?= esc($topPenghasilan['nama']); ?></div>
            <div class="mdl-stat-sub">
              <strong>Rp <?= number_format((float) $topPenghasilan['total_penghasilan'], 0, ',', '.'); ?></strong><br>
              Frekuensi: <strong><?= (int) $topPenghasilan['jumlah_dl']; ?> kali DL</strong> (<?= (int) $topPenghasilan['total_hari']; ?> hari)<br>
              <span class="badge badge-light border mt-1"><?= esc($topPenghasilan['nama_tim_kerja']); ?></span>
            </div>
          <?php else : ?>
            <div class="mdl-stat-value text-muted">-</div>
            <div class="mdl-stat-sub">Belum ada data DL pada filter ini.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <div class="mdl-stat-card accent-gold">
          <div class="mdl-stat-label"><i class="fas fa-trophy text-warning mr-1"></i> #1 Paling Sering DL</div>
          <?php if (!empty($topFrekuensi)) : ?>
            <div class="mdl-stat-value"><?= esc($topFrekuensi['nama']); ?></div>
            <div class="mdl-stat-sub">
              <strong><?= (int) $topFrekuensi['jumlah_dl']; ?> kali DL</strong> (<?= (int) $topFrekuensi['total_hari']; ?> hari)<br>
              Penghasilan: <strong>Rp <?= number_format((float) $topFrekuensi['total_penghasilan'], 0, ',', '.'); ?></strong><br>
              <span class="badge badge-light border mt-1"><?= esc($topFrekuensi['nama_tim_kerja']); ?></span>
            </div>
          <?php else : ?>
            <div class="mdl-stat-value text-muted">-</div>
            <div class="mdl-stat-sub">Belum ada data DL pada filter ini.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6 mb-2 mb-sm-0">
        <div class="mdl-stat-card accent-indigo">
          <div class="mdl-stat-label"><i class="fas fa-users text-indigo mr-1"></i> Total Pegawai &amp; Frekuensi</div>
          <div class="mdl-stat-value"><?= number_format((int) ($summary['total_pegawai'] ?? 0), 0, ',', '.'); ?> / <?= number_format((int) ($dukTotal ?? 0), 0, ',', '.'); ?> DUK</div>
          <div class="mdl-stat-sub">
            Penugasan: <strong><?= number_format((int) ($summary['total_penugasan'] ?? 0), 0, ',', '.'); ?>x</strong>
            (LK: <strong><?= number_format((int) ($summary['total_penugasan_luar_kota'] ?? 0), 0, ',', '.'); ?></strong> &bull;
            DK: <strong><?= number_format((int) ($summary['total_penugasan_dalam_kota'] ?? 0), 0, ',', '.'); ?></strong>)<br>
            Kegiatan: <strong><?= number_format((int) ($summary['total_dl_kegiatan'] ?? 0), 0, ',', '.'); ?></strong> &bull; <?= number_format((int) ($summary['total_hari'] ?? 0), 0, ',', '.'); ?> hari
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6">
        <div class="mdl-stat-card accent-emerald">
          <div class="mdl-stat-label"><i class="fas fa-wallet text-success mr-1"></i> Total Akumulasi Penghasilan DL</div>
          <div class="mdl-stat-value">Rp <?= number_format((float) ($summary['total_penghasilan'] ?? 0), 0, ',', '.'); ?></div>
          <div class="mdl-stat-sub">
            Luar Kota: <strong>Rp <?= number_format((float) ($summary['total_penghasilan_luar_kota'] ?? 0), 0, ',', '.'); ?></strong><br>
            Dalam Kota: <strong>Rp <?= number_format((float) ($summary['total_penghasilan_dalam_kota'] ?? 0), 0, ',', '.'); ?></strong><br>
            <span class="text-muted">T: Rp <?= number_format((float) ($summary['total_transport'] ?? 0), 0, ',', '.'); ?> &bull; H: Rp <?= number_format((float) ($summary['total_harian'] ?? 0), 0, ',', '.'); ?> &bull; P: Rp <?= number_format((float) ($summary['total_penginapan'] ?? 0), 0, ',', '.'); ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan Total Penghasilan per Tim Kerja (Default Collapsed) -->
    <div class="mdl-card">
      <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-2 px-3 mdl-card-header-flex mdl-collapse-toggle"
           style="gap: 8px;"
           data-toggle="collapse"
           data-target="#collapseTimKerjaSummary"
           aria-expanded="false"
           aria-controls="collapseTimKerjaSummary">
        <div>
          <h5 class="mb-0 font-weight-bold" style="font-size: 0.98rem;">
            <i class="fas fa-sitemap text-teal mr-1"></i> Total Penghasilan per Tim Kerja
          </h5>
          <small class="text-muted">
            Akumulasi penghasilan Dinas Luar berstatus <strong>Disetujui</strong> (Pegawai DUK) &bull; Klik untuk membuka rincian per tim.
          </small>
        </div>
        <div class="d-flex flex-wrap align-items-center ml-auto" style="gap: 6px;">
          <span class="badge badge-light border px-2 py-1">
            <?= count($timKerjaSummary); ?> Tim &bull; Rp <?= number_format((float) ($timKerjaGrandTotal['total_penghasilan'] ?? 0), 0, ',', '.'); ?>
          </span>
          <?php if ($timKerjaFilter !== 'all') : ?>
            <a href="<?= esc($buildQueryUrl(['tim_kerja' => 'all']), 'attr'); ?>" onclick="event.stopPropagation();" class="btn btn-xs btn-outline-teal font-weight-bold" style="border-color: #0d9488; color: #0d9488;">
              <i class="fas fa-layer-group mr-1"></i> Tampilkan Semua Tim
            </a>
          <?php endif; ?>
          <button type="button" class="btn btn-xs btn-outline-secondary">
            <span>Rincian Tim</span>
            <i class="fas fa-chevron-down ml-1 mdl-collapse-chevron"></i>
          </button>
        </div>
      </div>
      <div id="collapseTimKerjaSummary" class="collapse">
        <div class="card-body p-3 border-top">
          <?php if (!empty($timKerjaSummary)) : ?>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover mb-0" style="font-size: 0.82rem;">
                <thead style="background: #f8fafc;">
                  <tr>
                    <th class="text-center" style="width: 44px;">#</th>
                    <th>Tim Kerja</th>
                    <th class="text-center">Pegawai DUK</th>
                    <th class="text-center">Frekuensi DL</th>
                    <th class="text-right">Rincian Biaya</th>
                    <th class="text-right">Total Penghasilan (LK / DK)</th>
                    <th class="text-center" style="min-width: 120px;">Porsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($timKerjaSummary as $tkIdx => $tkRow) :
                    $isActiveTim = ($timKerjaFilter === (string) ($tkRow['filter_key'] ?? ''));
                    $porsi = (float) ($tkRow['persentase'] ?? 0);
                    $barWidth = max(0.0, min(100.0, $porsi));
                  ?>
                    <tr class="<?= $isActiveTim ? 'table-info' : ''; ?>">
                      <td class="text-center font-weight-bold"><?= $tkIdx + 1; ?></td>
                      <td>
                        <a href="<?= esc($buildQueryUrl(['tim_kerja' => (string) ($tkRow['filter_key'] ?? 'all')]), 'attr'); ?>" class="font-weight-bold text-dark">
                          <?= esc($tkRow['nama_tim_kerja'] ?? 'Tanpa Tim Kerja'); ?>
                        </a>
                        <?php if ($isActiveTim) : ?>
                          <span class="badge badge-teal ml-1" style="background: #0d9488; color: #fff;">Aktif</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center"><?= number_format((int) ($tkRow['total_pegawai'] ?? 0), 0, ',', '.'); ?> Orang</td>
                      <td class="text-center">
                        <strong><?= number_format((int) ($tkRow['total_penugasan'] ?? 0), 0, ',', '.'); ?> kali</strong>
                        <span class="text-muted">&bull; <?= number_format((int) ($tkRow['total_hari'] ?? 0), 0, ',', '.'); ?> hr</span>
                        <div class="small text-muted">
                          LK: <?= number_format((int) ($tkRow['total_penugasan_luar_kota'] ?? 0), 0, ',', '.'); ?> &bull;
                          DK: <?= number_format((int) ($tkRow['total_penugasan_dalam_kota'] ?? 0), 0, ',', '.'); ?>
                        </div>
                      </td>
                      <td class="text-right small text-muted">
                        Transport: Rp <?= number_format((float) ($tkRow['total_transport'] ?? 0), 0, ',', '.'); ?> &bull;
                        Harian: Rp <?= number_format((float) ($tkRow['total_harian'] ?? 0), 0, ',', '.'); ?> &bull;
                        Inap: Rp <?= number_format((float) ($tkRow['total_penginapan'] ?? 0), 0, ',', '.'); ?>
                      </td>
                      <td class="text-right text-nowrap">
                        <div class="font-weight-bold text-success">
                          Rp <?= number_format((float) ($tkRow['total_penghasilan'] ?? 0), 0, ',', '.'); ?>
                        </div>
                        <div class="small text-muted">
                          LK: Rp <?= number_format((float) ($tkRow['total_penghasilan_luar_kota'] ?? 0), 0, ',', '.'); ?> &bull;
                          DK: Rp <?= number_format((float) ($tkRow['total_penghasilan_dalam_kota'] ?? 0), 0, ',', '.'); ?>
                        </div>
                      </td>
                      <td class="text-center align-middle">
                        <div class="d-flex align-items-center" style="gap: 6px;">
                          <div class="progress flex-fill mb-0" style="height: 7px; background: #e2e8f0;">
                            <div class="progress-bar bg-teal" role="progressbar" style="width: <?= $barWidth; ?>%; background-color: #0d9488 !important;" aria-valuenow="<?= $barWidth; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="small font-weight-bold text-dark" style="min-width: 42px; text-align: right;"><?= number_format($porsi, 1, ',', '.'); ?>%</span>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot style="background: #f1f5f9;">
                  <tr class="font-weight-bold">
                    <td colspan="2" class="text-right">Total Keseluruhan</td>
                    <td class="text-center"><?= number_format((int) ($timKerjaGrandTotal['total_pegawai'] ?? 0), 0, ',', '.'); ?> Orang</td>
                    <td class="text-center">
                      <?= number_format((int) ($timKerjaGrandTotal['total_penugasan'] ?? 0), 0, ',', '.'); ?> kali
                      &bull; <?= number_format((int) ($timKerjaGrandTotal['total_hari'] ?? 0), 0, ',', '.'); ?> hr
                    </td>
                    <td class="text-right small">
                      Transport: Rp <?= number_format((float) ($timKerjaGrandTotal['total_transport'] ?? 0), 0, ',', '.'); ?> &bull;
                      Harian: Rp <?= number_format((float) ($timKerjaGrandTotal['total_harian'] ?? 0), 0, ',', '.'); ?> &bull;
                      Inap: Rp <?= number_format((float) ($timKerjaGrandTotal['total_penginapan'] ?? 0), 0, ',', '.'); ?>
                    </td>
                    <td class="text-right text-nowrap">
                      <div class="text-success">
                        Rp <?= number_format((float) ($timKerjaGrandTotal['total_penghasilan'] ?? 0), 0, ',', '.'); ?>
                      </div>
                      <div class="small text-muted font-weight-normal">
                        LK: Rp <?= number_format((float) ($timKerjaGrandTotal['total_penghasilan_luar_kota'] ?? 0), 0, ',', '.'); ?> &bull;
                        DK: Rp <?= number_format((float) ($timKerjaGrandTotal['total_penghasilan_dalam_kota'] ?? 0), 0, ',', '.'); ?>
                      </div>
                    </td>
                    <td class="text-center">100,0%</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          <?php else : ?>
            <div class="text-center text-muted small py-2">
              Belum ada data Dinas Luar berstatus Disetujui pada rentang tanggal yang dipilih.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Tabel Peringkat Perorangan -->
    <div class="mdl-card">
      <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-2 px-3 mdl-card-header-flex" style="gap: 8px;">
        <div>
          <h5 class="mb-0 font-weight-bold" style="font-size: 0.98rem;">
            Peringkat Dinas Luar Perorangan
          </h5>
          <small class="text-muted">
            Diurutkan mulai nomor 1 berdasarkan <?= $sortMode === 'penghasilan' ? 'total penghasilan terbanyak' : 'frekuensi Dinas Luar terbanyak'; ?>.
          </small>
        </div>
        <div class="mdl-sort-pills ml-auto">
          <a href="<?= esc($buildQueryUrl(['sort' => 'penghasilan']), 'attr'); ?>" class="mdl-sort-pill <?= $sortMode === 'penghasilan' ? 'active' : ''; ?>">
            <i class="fas fa-money-bill-wave mr-1"></i> #1 Penghasilan Terbanyak
          </a>
          <a href="<?= esc($buildQueryUrl(['sort' => 'frekuensi']), 'attr'); ?>" class="mdl-sort-pill <?= $sortMode === 'frekuensi' ? 'active' : ''; ?>">
            <i class="fas fa-sort-amount-down mr-1"></i> #1 Paling Sering DL
          </a>
        </div>
      </div>

      <div class="card-body p-3">
        <div class="table-responsive">
          <table id="tableMonitoringDl" class="table table-bordered table-striped table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-center">Peringkat</th>
                <th>Nama Pegawai</th>
                <th>Tim Kerja</th>
                <th class="text-center">Frekuensi DL</th>
                <th class="text-right">Rincian Biaya</th>
                <th class="text-right">Total Penghasilan</th>
                <th class="text-right">Rata-rata / DL</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rankingRows as $idx => $row) :
                $rank = (int) ($row['rank'] ?? ($idx + 1));
                $rankClass = $rank === 1 ? 'mdl-rank-1' : ($rank === 2 ? 'mdl-rank-2' : ($rank === 3 ? 'mdl-rank-3' : 'mdl-rank-other'));
              ?>
                <tr>
                  <td class="text-center" data-order="<?= $rank; ?>">
                    <span class="mdl-rank-badge <?= $rankClass; ?>">
                      <?php if ($rank === 1) : ?>
                        <i class="fas fa-crown mr-1"></i>
                      <?php endif; ?>
                      #<?= $rank; ?>
                    </span>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark"><?= esc($row['nama']); ?></div>
                    <div class="small text-muted">
                      <?= $row['nip'] !== '' ? ('NIP. ' . esc($row['nip'])) : '-'; ?>
                      <?php if (($row['jabatan'] ?? '') !== '' || ($row['golongan'] ?? '') !== '') : ?>
                        <br><?= esc($row['jabatan'] ?: '-'); ?><?= ($row['golongan'] ?? '') !== '' ? (' (' . esc($row['golongan']) . ')') : ''; ?>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-light border px-2 py-1" style="font-size: 0.76rem;">
                      <?= esc($row['nama_tim_kerja']); ?>
                    </span>
                  </td>
                  <td class="text-center" data-order="<?= (int) $row['jumlah_dl']; ?>">
                    <div class="font-weight-bold text-dark" style="font-size: 0.92rem;">
                      <?= (int) $row['jumlah_dl']; ?> Kali
                    </div>
                    <div class="small text-muted"><?= (int) $row['total_hari']; ?> Hari</div>
                    <div class="small text-muted" style="font-size: 0.72rem;">
                      LK: <?= (int) ($row['jumlah_dl_luar_kota'] ?? 0); ?> &bull; DK: <?= (int) ($row['jumlah_dl_dalam_kota'] ?? 0); ?>
                    </div>
                  </td>
                  <td class="text-right small">
                    <div>Transport: <strong>Rp <?= number_format((float) $row['total_transport'], 0, ',', '.'); ?></strong></div>
                    <div>Uang Harian: <strong>Rp <?= number_format((float) $row['total_harian'], 0, ',', '.'); ?></strong></div>
                    <div>Penginapan: <strong>Rp <?= number_format((float) $row['total_penginapan'], 0, ',', '.'); ?></strong></div>
                  </td>
                  <td class="text-right" data-order="<?= (float) $row['total_penghasilan']; ?>">
                    <div class="font-weight-bold text-success" style="font-size: 0.92rem;">
                      Rp <?= number_format((float) $row['total_penghasilan'], 0, ',', '.'); ?>
                    </div>
                    <div class="small text-muted" style="font-size: 0.72rem; line-height: 1.25;">
                      Luar Kota: <strong>Rp <?= number_format((float) ($row['penghasilan_luar_kota'] ?? 0), 0, ',', '.'); ?></strong><br>
                      Dalam Kota: <strong>Rp <?= number_format((float) ($row['penghasilan_dalam_kota'] ?? 0), 0, ',', '.'); ?></strong>
                    </div>
                  </td>
                  <td class="text-right small" data-order="<?= (float) $row['rata_rata_penghasilan']; ?>">
                    <div><strong>Rp <?= number_format((float) $row['rata_rata_penghasilan'], 0, ',', '.'); ?></strong></div>
                    <div class="text-muted">
                      Terakhir: <?= $row['dl_terakhir'] !== '' ? esc(mediumdate_indo($row['dl_terakhir'])) : '-'; ?>
                    </div>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-xs btn-outline-info btn-riwayat-dl" data-row-index="<?= (int) $idx; ?>">
                      <i class="fas fa-list-ul mr-1"></i> Riwayat
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Rincian Riwayat DL Perorangan -->
<div class="modal fade" id="modalRiwayatDl" tabindex="-1" role="dialog" aria-labelledby="modalRiwayatDlLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white py-2">
        <h5 class="modal-title" id="modalRiwayatDlLabel">
          <i class="fas fa-route mr-1"></i> Riwayat Dinas Luar Pegawai
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="riwayatDlHeaderInfo" class="alert alert-light border mb-3 py-2 px-3 small"></div>
        <div class="table-responsive">
          <table class="table table-sm table-bordered table-striped mb-0" style="font-size: 0.8rem;">
            <thead class="thead-light">
              <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Tanggal DL</th>
                <th>Nama Kegiatan</th>
                <th>Kota Tujuan</th>
                <th class="text-center">Hari</th>
                <th class="text-right">Transport</th>
                <th class="text-right">Uang Harian</th>
                <th class="text-right">Penginapan</th>
                <th class="text-right">Total Transfer</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody id="riwayatDlTableBody"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php if ($canManageAccess) : ?>
<!-- Modal Pengaturan Akses User Monitoring Dinas Luar -->
<div class="modal fade" id="modalAksesMonitoringDl" tabindex="-1" role="dialog" aria-labelledby="modalAksesMonitoringDlLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success py-2">
        <h5 class="modal-title" id="modalAksesMonitoringDlLabel">
          <i class="fas fa-user-shield mr-1"></i> Pengaturan Akses User — Monitoring Dinas Luar
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
        <div class="alert alert-info py-2 px-3 small mb-3">
          <i class="fas fa-info-circle mr-1"></i>
          Secara default hanya <strong>Super Admin Monitoring DL (Mohamad Dahlan Fazhry &bull; 199404082022031001)</strong> yang dapat mengakses dan mengatur halaman <strong>Monitoring Dinas Luar</strong>. Aktifkan switch di bawah untuk memberi izin kepada user lain.
        </div>
        <div class="input-group input-group-sm mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" id="searchUserAksesDl" class="form-control" placeholder="Cari nama atau username user...">
        </div>
        <div class="row" id="listUserAksesDl">
          <?php foreach ($accessUsers as $u) :
            $isUserSuperAdmin = \App\Libraries\SinadiMonitoringDlHelper::isSuperAdmin((string) ($u['username'] ?? ''));
            $hasMonitoringAccess = $isUserSuperAdmin || (!empty($u['id_sinadi_akses']) && (int) ($u['akses_monitoring_dl'] ?? 0) === 1);
            $searchAttr = strtolower(trim((string) ($u['nama'] ?? '') . ' ' . (string) ($u['username'] ?? '') . ' ' . (string) ($u['role'] ?? '')));
          ?>
            <div class="col-md-6 user-akses-dl-col" data-search="<?= esc($searchAttr, 'attr'); ?>">
              <div class="mdl-access-item d-flex justify-content-between align-items-center">
                <div style="min-width: 0; padding-right: 8px;">
                  <div class="font-weight-bold text-dark text-truncate" style="font-size: 0.82rem;">
                    <?= esc(name_capitalize($u['nama'] ?? '')); ?>
                  </div>
                  <div class="small text-muted">
                    <?= esc($u['username'] ?? ''); ?>
                    <?php if ($isUserSuperAdmin) : ?>
                      &bull; <span class="badge badge-danger">Super Admin (Default)</span>
                    <?php elseif (!empty($u['role'])) : ?>
                      &bull; <span class="badge badge-light border"><?= esc($u['role']); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="custom-control custom-switch flex-shrink-0">
                  <input
                    type="checkbox"
                    class="custom-control-input <?= $isUserSuperAdmin ? '' : 'aksesMonitoringDlCheckboxSinadi'; ?>"
                    id="modalSwitchMonitoringDl<?= (int) $u['id_user']; ?>"
                    value="<?= (int) $u['id_user']; ?>"
                    <?= $hasMonitoringAccess ? 'checked' : ''; ?>
                    <?= $isUserSuperAdmin ? 'disabled' : ''; ?>
                  >
                  <label class="custom-control-label" for="modalSwitchMonitoringDl<?= (int) $u['id_user']; ?>"></label>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  $(function() {
    var monitoringRows = <?= json_encode($rankingRows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

    function formatRupiah(val) {
      var num = Math.round(Number(val) || 0);
      return 'Rp ' + num.toLocaleString('id-ID');
    }

    function escapeHtml(str) {
      return String(str == null ? '' : str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    $('#modalRiwayatDl, #modalAksesMonitoringDl').appendTo('body');

    $(document).on('change', '.mdl-auto-filter', function() {
      var form = document.getElementById('formFilterMonitoringDl');
      if (!form) return;
      if (this.type === 'date' && this.value && !/^\d{4}-\d{2}-\d{2}$/.test(this.value)) {
        return;
      }
      $('#overlay').show();
      form.submit();
    });

    if ($.fn.DataTable) {
      $('#tableMonitoringDl').DataTable({
        responsive: true,
        autoWidth: false,
        order: [],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        dom: "<'row mb-2'<'col-md-6 d-flex align-items-center'lB><'col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
          {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel mr-1"></i> Excel',
            className: 'btn btn-xs btn-success ml-2',
            title: 'Monitoring Dinas Luar (<?= esc($startDate, 'js'); ?> sd <?= esc($endDate, 'js'); ?>)',
            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
          },
          {
            extend: 'print',
            text: '<i class="fas fa-print mr-1"></i> Cetak',
            className: 'btn btn-xs btn-secondary ml-1',
            title: 'Monitoring Dinas Luar',
            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
          }
        ],
        language: {
          search: 'Cari Pegawai / Tim:',
          lengthMenu: 'Tampilkan _MENU_',
          info: 'Menampilkan _START_ - _END_ dari _TOTAL_ pegawai',
          infoEmpty: 'Tidak ada data Dinas Luar pada filter ini',
          zeroRecords: 'Tidak ditemukan data yang sesuai',
          paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
        }
      });
    }

    $(document).on('click', '.btn-riwayat-dl', function() {
      var idx = parseInt($(this).data('row-index'), 10);
      var item = monitoringRows[idx];
      if (!item) return;

      $('#modalRiwayatDlLabel').html('<i class="fas fa-route mr-1"></i> Riwayat DL &mdash; ' + escapeHtml(item.nama));
      $('#riwayatDlHeaderInfo').html(
        '<strong>' + escapeHtml(item.nama) + '</strong>' +
        (item.nip ? ' (NIP. ' + escapeHtml(item.nip) + ')' : '') +
        ' &bull; Tim Kerja: <strong>' + escapeHtml(item.nama_tim_kerja || '-') + '</strong><br>' +
        'Total: <strong>' + (item.jumlah_dl || 0) + ' kali DL</strong> (' + (item.total_hari || 0) + ' hari) &bull; ' +
        'Total Penghasilan: <strong class="text-success">' + formatRupiah(item.total_penghasilan) + '</strong>'
      );

      var tbody = '';
      var riwayat = Array.isArray(item.riwayat) ? item.riwayat : [];
      if (riwayat.length === 0) {
        tbody = '<tr><td colspan="10" class="text-center text-muted">Tidak ada rincian perjalanan.</td></tr>';
      } else {
        riwayat.forEach(function(r, i) {
          var tglLabel = r.tgl_pergi || '-';
          if (r.tgl_pulang && r.tgl_pulang !== r.tgl_pergi) {
            tglLabel += ' s.d. ' + r.tgl_pulang;
          }
          var jenisBadge = r.jenis_perjalanan === 'dalam_kota'
            ? '<span class="badge badge-secondary ml-1">Dalam Kota</span>'
            : '<span class="badge badge-info ml-1">Luar Kota</span>';
          tbody += '<tr>' +
            '<td class="text-center">' + (i + 1) + '</td>' +
            '<td class="text-nowrap">' + escapeHtml(tglLabel) + '</td>' +
            '<td>' + escapeHtml(r.nama_kegiatan || '-') + ' ' + jenisBadge + '</td>' +
            '<td>' + escapeHtml(r.tempat_perjalanan || '-') + '</td>' +
            '<td class="text-center">' + (r.hari || 1) + ' hr</td>' +
            '<td class="text-right">' + formatRupiah(r.transport) + '</td>' +
            '<td class="text-right">' + formatRupiah(r.harian) + '</td>' +
            '<td class="text-right">' + formatRupiah(r.penginapan) + '</td>' +
            '<td class="text-right font-weight-bold text-success">' + formatRupiah(r.penghasilan) + '</td>' +
            '<td class="text-center"><span class="badge badge-light border">' + escapeHtml(r.status_surat || '-') + '</span></td>' +
          '</tr>';
        });
      }

      $('#riwayatDlTableBody').html(tbody);
      $('#modalRiwayatDl').modal('show');
    });

    $('#searchUserAksesDl').on('input keyup', function() {
      var q = String($(this).val() || '').toLowerCase().trim();
      $('.user-akses-dl-col').each(function() {
        var hay = String($(this).data('search') || '');
        $(this).toggle(q === '' || hay.indexOf(q) !== -1);
      });
    });

    $(document).on('change', '.aksesMonitoringDlCheckboxSinadi', function() {
      var elemen = $(this);
      var checked = elemen.prop('checked');
      var targetUrl = checked ? '/sinadi/insertAksesMonitoringDl' : '/sinadi/removeAksesMonitoringDl';

      $.ajax({
        url: targetUrl,
        data: { id: elemen.val() },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#overlay').show();
        },
        success: function(response) {
          if (response && response.data === 'sukses') {
            if (typeof Toast !== 'undefined') {
              Toast.fire({
                icon: 'success',
                text: checked ? 'Akses Monitoring Dinas Luar diberikan!' : 'Akses Monitoring Dinas Luar dicabut!'
              });
            }
          } else {
            elemen.prop('checked', !checked);
            if (typeof Toast !== 'undefined') {
              Toast.fire({ icon: 'warning', text: 'Gagal memperbarui akses!' });
            }
          }
          $('#overlay').hide();
        },
        error: function() {
          elemen.prop('checked', !checked);
          $('#overlay').hide();
          if (typeof ToastError !== 'undefined') {
            ToastError.fire({ icon: 'error', text: 'Kegagalan sistem!' });
          }
        }
      });
    });
  });
</script>

<?= $this->endSection(); ?>
