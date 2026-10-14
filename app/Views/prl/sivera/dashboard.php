<?php
  $summary = $summary ?? [];
  $dashboard = $dashboard ?? [];
  $labels = $dashboard['labels'] ?? [];
  $kecoaChart = $dashboard['kecoa'] ?? [];
  $lalatStatusChart = $dashboard['lalat_status'] ?? [];
  $jentikKontainerChart = $dashboard['jentik_kontainer'] ?? [];
  $spesiesTikusChart = $dashboard['spesies_tikus'] ?? [];
  $wilkerDetail = $dashboard['wilker_detail'] ?? [];

  $topKontainer = '-';
  if (!empty($jentikKontainerChart)) {
    $topKontainer = array_key_first($jentikKontainerChart) . ' (' . reset($jentikKontainerChart) . ')';
  }

  // Pre-calculate totals for Kecoa
  $totalKecoaPerSpesies = [];
  foreach ($kecoaChart as $sp => $arr) {
    $totalKecoaPerSpesies[$sp] = array_sum($arr);
  }
  $grandTotalKecoa = array_sum($totalKecoaPerSpesies);

  // Pre-calculate totals for Lalat
  $totalLalatMS = array_sum($lalatStatusChart['MS'] ?? []);
  $totalLalatTMS = array_sum($lalatStatusChart['TMS'] ?? []);
  $grandTotalLalat = $totalLalatMS + $totalLalatTMS;

  // Pre-calculate totals for Jentik Kontainer
  $grandTotalJentikKontainer = array_sum($jentikKontainerChart);

  // Pre-calculate totals for Tikus
  $totalTikusPerSpesies = [];
  foreach ($spesiesTikusChart as $sp => $arr) {
    $totalTikusPerSpesies[$sp] = array_sum($arr);
  }
  $grandTotalTikus = array_sum($totalTikusPerSpesies);
?>

<style>
  .sivera-dashboard .dash-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
    border-radius: 1rem;
    color: #fff;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 16px 30px rgba(29, 78, 216, 0.18);
  }

  .sivera-dashboard .dash-hero-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: .25rem;
  }

  .sivera-dashboard .dash-hero-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: .9rem;
    margin-bottom: 0;
  }

  .sivera-dashboard .metric-card {
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: .95rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .sivera-dashboard .metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.09);
  }

  .sivera-dashboard .metric-label {
    color: #64748b;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .sivera-dashboard .metric-value {
    color: #0f172a;
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.1;
  }

  .sivera-dashboard .metric-note {
    color: #475569;
    font-size: .85rem;
  }

  .sivera-dashboard .chart-card {
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    height: 100%;
    background: #fff;
  }

  .sivera-dashboard .chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .75rem;
    flex-wrap: wrap;
  }

  .sivera-dashboard .chart-title {
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0;
  }

  .sivera-dashboard .chart-caption {
    color: #64748b;
    font-size: .82rem;
    margin-bottom: 0;
  }

  .sivera-dashboard .chart-badge {
    background: #eff6ff;
    color: #1d4ed8;
    font-size: .72rem;
    font-weight: 700;
    border-radius: 999px;
    padding: .35rem .6rem;
    white-space: nowrap;
  }

  .sivera-dashboard .chart-wrap {
    position: relative;
    height: 260px;
  }

  .sivera-dashboard .table-wrap {
    height: 260px;
    overflow-y: auto;
    border-radius: .5rem;
    border: 1px solid rgba(148, 163, 184, 0.2);
  }

  .sivera-dashboard .table-wrap table thead th {
    position: sticky;
    top: 0;
    background: #f8fafc;
    z-index: 1;
    font-size: 0.78rem;
    font-weight: 700;
    color: #1e293b;
    border-bottom: 2px solid #cbd5e1;
  }

  .sivera-dashboard .table-wrap table tbody td,
  .sivera-dashboard .table-wrap table tfoot td {
    font-size: 0.8rem;
  }

  .sivera-dashboard .table-wrap table tfoot {
    position: sticky;
    bottom: 0;
    background: #f1f5f9;
    font-weight: 700;
    z-index: 1;
  }

  .sivera-dashboard .view-switcher .btn {
    padding: 0.22rem 0.6rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.35rem;
  }

  /* Master Detail Table Styling */
  .sivera-dashboard .master-detail-card {
    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 1rem;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.07);
    background: #fff;
    margin-top: 1.25rem;
  }

  .sivera-dashboard .master-detail-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 1rem 1rem 0 0;
    padding: 1rem 1.25rem;
  }

  .sivera-dashboard .detail-nav .nav-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    border-radius: 0.5rem;
    padding: 0.4rem 0.85rem;
    margin-right: 0.35rem;
    border: 1px solid transparent;
  }

  .sivera-dashboard .detail-nav .nav-link.active {
    background: #1d4ed8;
    color: #fff;
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
  }

  .sivera-dashboard .master-table th {
    background: #f8fafc;
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    vertical-align: middle;
  }

  .sivera-dashboard .master-table td {
    font-size: 0.82rem;
    vertical-align: middle;
  }

  .sivera-dashboard .master-table tfoot {
    background: #f1f5f9;
    font-weight: 700;
  }

  @media (max-width: 767.98px) {
    .sivera-dashboard .chart-wrap,
    .sivera-dashboard .table-wrap {
      height: 240px;
    }
  }
</style>

<div class="sivera-dashboard">
  <div class="dash-hero">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="dash-hero-title">Dashboard SIVERA</div>
        <p class="dash-hero-subtitle">Ringkasan surveilans jentik, lalat, kecoa, tikus, dan pinjal dalam tampilan yang lebih padat, profesional, dan mudah dipantau.</p>
      </div>
      <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
        <span class="badge badge-light px-3 py-2">Wilker Terpantau: <?= count($labels); ?></span>
      </div>
    </div>
  </div>

  <!-- Metric Cards -->
  <div class="row">
    <div class="col-xl col-md-6 mb-3">
      <div class="card metric-card">
        <div class="card-body p-3">
          <div class="metric-label">Jentik</div>
          <div class="metric-value"><?= number_format((int) ($summary['jentik'] ?? 0)); ?></div>
          <div class="metric-note">Kontainer positif: <?= number_format((int) ($summary['kontainer_positif'] ?? 0)); ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl col-md-6 mb-3">
      <div class="card metric-card">
        <div class="card-body p-3">
          <div class="metric-label">Lalat</div>
          <div class="metric-value"><?= number_format((float) ($summary['avg_lalat'] ?? 0), 2); ?></div>
          <div class="metric-note">Rata-rata kepadatan lalat</div>
        </div>
      </div>
    </div>
    <div class="col-xl col-md-6 mb-3">
      <div class="card metric-card">
        <div class="card-body p-3">
          <div class="metric-label">Kecoa</div>
          <div class="metric-value"><?= number_format((int) ($summary['kecoa_tertangkap'] ?? 0)); ?></div>
          <div class="metric-note">Total kecoa tertangkap</div>
        </div>
      </div>
    </div>
    <div class="col-xl col-md-6 mb-3">
      <div class="card metric-card">
        <div class="card-body p-3">
          <div class="metric-label">Tikus</div>
          <div class="metric-value"><?= number_format((float) ($summary['avg_success_trap'] ?? 0), 2); ?>%</div>
          <div class="metric-note">Rata-rata success trap</div>
        </div>
      </div>
    </div>
    <div class="col-xl col-md-6 mb-3">
      <div class="card metric-card">
        <div class="card-body p-3">
          <div class="metric-label">Pinjal</div>
          <div class="metric-value"><?= number_format((int) ($summary['pinjal_umum'] ?? 0)); ?></div>
          <div class="metric-note">Total pinjal umum</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts & In-card Detail Tables Grid -->
  <div class="row">
    <!-- 1. Kecoa -->
    <div class="col-lg-6 mb-3">
      <div class="card chart-card">
        <div class="card-body p-3">
          <div class="chart-header">
            <div>
              <h6 class="chart-title">Spesies Kecoa per Wilker</h6>
              <p class="chart-caption">Perbandingan spesies tertangkap pada masing-masing wilayah kerja.</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="btn-group btn-group-sm view-switcher mr-2" role="group">
                <button type="button" class="btn btn-outline-primary active btn-toggle-card-view" data-type="chart" data-card="kecoa" title="Tampilkan Grafik"><i class="fas fa-chart-bar"></i> Grafik</button>
                <button type="button" class="btn btn-outline-primary btn-toggle-card-view" data-type="table" data-card="kecoa" title="Tampilkan Tabel Detail"><i class="fas fa-table"></i> Tabel</button>
              </div>
              <span class="chart-badge">Kecoa</span>
            </div>
          </div>
          
          <div class="chart-wrap" id="chart-wrap-kecoa">
            <canvas id="chart-sivera-kecoa"></canvas>
          </div>

          <div class="table-wrap table-responsive" id="table-wrap-kecoa" style="display: none;">
            <table class="table table-bordered table-striped table-sm text-center mb-0">
              <thead>
                <tr>
                  <th class="text-left">Wilayah Kerja</th>
                  <?php foreach(array_keys($kecoaChart) as $sp): ?>
                  <th><?= esc($sp); ?></th>
                  <?php endforeach; ?>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($labels)): ?>
                <tr><td colspan="<?= count($kecoaChart) + 2; ?>" class="text-muted">Belum ada data surveilans kecoa</td></tr>
                <?php else: ?>
                  <?php foreach($labels as $idx => $wilker): 
                    $rowTotal = 0;
                  ?>
                  <tr>
                    <td class="text-left font-weight-bold"><?= esc($wilker); ?></td>
                    <?php foreach($kecoaChart as $sp => $arr): 
                      $cnt = (int) ($arr[$idx] ?? 0);
                      $rowTotal += $cnt;
                    ?>
                    <td><?= $cnt > 0 ? "<strong>{$cnt}</strong>" : '<span class="text-muted">0</span>'; ?></td>
                    <?php endforeach; ?>
                    <td><span class="badge badge-primary px-2 py-1"><?= $rowTotal; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-left">Total Keseluruhan</td>
                  <?php foreach($kecoaChart as $sp => $arr): ?>
                  <td><?= array_sum($arr); ?></td>
                  <?php endforeach; ?>
                  <td><span class="badge badge-dark px-2 py-1"><?= $grandTotalKecoa; ?></span></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Lalat -->
    <div class="col-lg-6 mb-3">
      <div class="card chart-card">
        <div class="card-body p-3">
          <div class="chart-header">
            <div>
              <h6 class="chart-title">Status Kepadatan Lalat</h6>
              <p class="chart-caption">Perbandingan kategori MS dan TMS berdasarkan standar kepadatan lalat.</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="btn-group btn-group-sm view-switcher mr-2" role="group">
                <button type="button" class="btn btn-outline-primary active btn-toggle-card-view" data-type="chart" data-card="lalat" title="Tampilkan Grafik"><i class="fas fa-chart-bar"></i> Grafik</button>
                <button type="button" class="btn btn-outline-primary btn-toggle-card-view" data-type="table" data-card="lalat" title="Tampilkan Tabel Detail"><i class="fas fa-table"></i> Tabel</button>
              </div>
              <span class="chart-badge">Lalat</span>
            </div>
          </div>

          <div class="chart-wrap" id="chart-wrap-lalat">
            <canvas id="chart-sivera-lalat-status"></canvas>
          </div>

          <div class="table-wrap table-responsive" id="table-wrap-lalat" style="display: none;">
            <table class="table table-bordered table-striped table-sm text-center mb-0">
              <thead>
                <tr>
                  <th class="text-left">Wilayah Kerja</th>
                  <th>MS (&lt; 2)</th>
                  <th>TMS (&ge; 2)</th>
                  <th>Total Survei</th>
                  <th>Persentase MS</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($labels)): ?>
                <tr><td colspan="5" class="text-muted">Belum ada data surveilans lalat</td></tr>
                <?php else: ?>
                  <?php foreach($labels as $idx => $wilker): 
                    $ms = (int) ($lalatStatusChart['MS'][$idx] ?? 0);
                    $tms = (int) ($lalatStatusChart['TMS'][$idx] ?? 0);
                    $tot = $ms + $tms;
                    $persen = $tot > 0 ? round(($ms / $tot) * 100, 1) : 0;
                  ?>
                  <tr>
                    <td class="text-left font-weight-bold"><?= esc($wilker); ?></td>
                    <td><span class="badge badge-success px-2"><?= $ms; ?></span></td>
                    <td><span class="badge badge-danger px-2"><?= $tms; ?></span></td>
                    <td><strong><?= $tot; ?></strong></td>
                    <td><span class="badge <?= $persen >= 80 ? 'badge-info' : 'badge-warning'; ?>"><?= $persen; ?>%</span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-left">Total Keseluruhan</td>
                  <td><span class="badge badge-success px-2"><?= $totalLalatMS; ?></span></td>
                  <td><span class="badge badge-danger px-2"><?= $totalLalatTMS; ?></span></td>
                  <td><?= $grandTotalLalat; ?></td>
                  <td><span class="badge badge-dark"><?= $grandTotalLalat > 0 ? round(($totalLalatMS / $grandTotalLalat) * 100, 1) : 0; ?>%</span></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Jentik -->
    <div class="col-lg-6 mb-3">
      <div class="card chart-card">
        <div class="card-body p-3">
          <div class="chart-header">
            <div>
              <h6 class="chart-title">Kontainer Positif Jentik</h6>
              <p class="chart-caption">Jenis kontainer yang ditemukan positif jentik Aedes sp.</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="btn-group btn-group-sm view-switcher mr-2" role="group">
                <button type="button" class="btn btn-outline-primary active btn-toggle-card-view" data-type="chart" data-card="jentik" title="Tampilkan Grafik"><i class="fas fa-chart-bar"></i> Grafik</button>
                <button type="button" class="btn btn-outline-primary btn-toggle-card-view" data-type="table" data-card="jentik" title="Tampilkan Tabel Detail"><i class="fas fa-table"></i> Tabel</button>
              </div>
              <span class="chart-badge"><?= esc($topKontainer); ?></span>
            </div>
          </div>

          <div class="chart-wrap" id="chart-wrap-jentik">
            <canvas id="chart-sivera-jentik"></canvas>
          </div>

          <div class="table-wrap table-responsive" id="table-wrap-jentik" style="display: none;">
            <table class="table table-bordered table-striped table-sm text-center mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="text-left">Jenis Kontainer</th>
                  <th>Jumlah Temuan</th>
                  <th>Persentase (%)</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($jentikKontainerChart)): ?>
                <tr><td colspan="4" class="text-muted">Belum ada temuan kontainer positif</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($jentikKontainerChart as $jenis => $count): 
                    $pct = $grandTotalJentikKontainer > 0 ? round(($count / $grandTotalJentikKontainer) * 100, 1) : 0;
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($jenis); ?></td>
                    <td><span class="badge badge-primary px-2 py-1"><?= $count; ?></span></td>
                    <td><span class="badge badge-light px-2 py-1"><?= $pct; ?>%</span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" class="text-left">Total Temuan Positif</td>
                  <td><span class="badge badge-dark px-2 py-1"><?= $grandTotalJentikKontainer; ?></span></td>
                  <td>100%</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. Tikus -->
    <div class="col-lg-6 mb-3">
      <div class="card chart-card">
        <div class="card-body p-3">
          <div class="chart-header">
            <div>
              <h6 class="chart-title">Sebaran Spesies Tikus</h6>
              <p class="chart-caption">Distribusi spesies tikus tertangkap per wilayah kerja.</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="btn-group btn-group-sm view-switcher mr-2" role="group">
                <button type="button" class="btn btn-outline-primary active btn-toggle-card-view" data-type="chart" data-card="tikus" title="Tampilkan Grafik"><i class="fas fa-chart-bar"></i> Grafik</button>
                <button type="button" class="btn btn-outline-primary btn-toggle-card-view" data-type="table" data-card="tikus" title="Tampilkan Tabel Detail"><i class="fas fa-table"></i> Tabel</button>
              </div>
              <span class="chart-badge">Tikus & Pinjal</span>
            </div>
          </div>

          <div class="chart-wrap" id="chart-wrap-tikus">
            <canvas id="chart-sivera-tikus"></canvas>
          </div>

          <div class="table-wrap table-responsive" id="table-wrap-tikus" style="display: none;">
            <table class="table table-bordered table-striped table-sm text-center mb-0">
              <thead>
                <tr>
                  <th class="text-left">Wilayah Kerja</th>
                  <?php if (empty($spesiesTikusChart)): ?>
                  <th>Spesies Tikus</th>
                  <?php else: ?>
                    <?php foreach(array_keys($spesiesTikusChart) as $sp): ?>
                    <th><?= esc($sp); ?></th>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($labels) || empty($spesiesTikusChart)): ?>
                <tr><td colspan="<?= max(3, count($spesiesTikusChart) + 2); ?>" class="text-muted">Belum ada data spesies tikus tertangkap</td></tr>
                <?php else: ?>
                  <?php foreach($labels as $idx => $wilker): 
                    $rowTotal = 0;
                  ?>
                  <tr>
                    <td class="text-left font-weight-bold"><?= esc($wilker); ?></td>
                    <?php foreach($spesiesTikusChart as $sp => $arr): 
                      $cnt = (int) ($arr[$idx] ?? 0);
                      $rowTotal += $cnt;
                    ?>
                    <td><?= $cnt > 0 ? "<strong>{$cnt}</strong>" : '<span class="text-muted">0</span>'; ?></td>
                    <?php endforeach; ?>
                    <td><span class="badge badge-primary px-2 py-1"><?= $rowTotal; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-left">Total Keseluruhan</td>
                  <?php foreach($spesiesTikusChart as $sp => $arr): ?>
                  <td><?= array_sum($arr); ?></td>
                  <?php endforeach; ?>
                  <td><span class="badge badge-dark px-2 py-1"><?= $grandTotalTikus; ?></span></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Master Detail Table: Rekapitulasi Rinci per Wilayah Kerja -->
  <div class="card master-detail-card">
    <div class="master-detail-header d-flex flex-wrap align-items-center justify-content-between">
      <div>
        <h6 class="font-weight-bold mb-1 text-dark"><i class="fas fa-table text-primary mr-1"></i> Rekapitulasi Rinci Data Surveilans per Wilayah Kerja</h6>
        <p class="text-muted small mb-0">Tabel detail jumlah hasil surveilans lengkap untuk seluruh vektor dan reservoar penyakit.</p>
      </div>
      <ul class="nav nav-pills detail-nav mt-2 mt-md-0" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#tab-detail-semua"><i class="fas fa-layer-group"></i> Semua Vektor</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#tab-detail-jentik"><i class="fas fa-water"></i> Jentik</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#tab-detail-lalat"><i class="fas fa-bug"></i> Lalat</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#tab-detail-kecoa"><i class="fas fa-spider"></i> Kecoa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#tab-detail-tikus"><i class="fas fa-mouse"></i> Tikus & Pinjal</a>
        </li>
      </ul>
    </div>

    <div class="card-body p-3">
      <div class="tab-content">
        <!-- 1. Tab Semua Vektor -->
        <div class="tab-pane fade show active" id="tab-detail-semua">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm text-center master-table mb-0">
              <thead>
                <tr>
                  <th rowspan="2" style="width: 40px;">No</th>
                  <th rowspan="2" class="text-left" style="min-width: 200px;">Wilayah Kerja</th>
                  <th colspan="3" class="bg-light">Jentik Aedes</th>
                  <th colspan="3" class="bg-light">Lalat</th>
                  <th colspan="2" class="bg-light">Kecoa</th>
                  <th colspan="3" class="bg-light">Tikus</th>
                  <th colspan="2" class="bg-light">Pinjal</th>
                </tr>
                <tr>
                  <th>Survei</th>
                  <th>Kontainer (+)</th>
                  <th>ABJ (%)</th>
                  <th>Survei</th>
                  <th>Rata Kepadatan</th>
                  <th>Status</th>
                  <th>Survei</th>
                  <th>Tertangkap</th>
                  <th>Perangkap</th>
                  <th>Tertangkap</th>
                  <th>Trap (%)</th>
                  <th>Koleksi</th>
                  <th>Pinjal</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($wilkerDetail)): ?>
                <tr><td colspan="15" class="text-muted py-3">Belum ada data surveilans yang tercatat</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($wilkerDetail as $w => $dt): 
                    $statusLalat = $dt['lalat_avg'] < 2 ? '<span class="badge badge-success">MS</span>' : '<span class="badge badge-danger">TMS</span>';
                    $statusAbj = $dt['abj'] >= 95 ? '<span class="text-success font-weight-bold">'.$dt['abj'].'%</span>' : '<span class="text-danger font-weight-bold">'.$dt['abj'].'%</span>';
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($dt['wilker']); ?></td>
                    <td><?= $dt['jentik_survei']; ?></td>
                    <td><span class="badge <?= $dt['jentik_kontainer_positif'] > 0 ? 'badge-danger' : 'badge-light'; ?>"><?= $dt['jentik_kontainer_positif']; ?></span></td>
                    <td><?= $statusAbj; ?></td>
                    <td><?= $dt['lalat_survei']; ?></td>
                    <td><?= number_format($dt['lalat_avg'], 2); ?></td>
                    <td><?= $dt['lalat_survei'] > 0 ? $statusLalat : '-'; ?></td>
                    <td><?= $dt['kecoa_survei']; ?></td>
                    <td><span class="badge <?= $dt['kecoa_tertangkap'] > 0 ? 'badge-warning' : 'badge-light'; ?>"><?= $dt['kecoa_tertangkap']; ?></span></td>
                    <td><?= $dt['tikus_perangkap']; ?></td>
                    <td><span class="badge <?= $dt['tikus_tertangkap'] > 0 ? 'badge-info' : 'badge-light'; ?>"><?= $dt['tikus_tertangkap']; ?></span></td>
                    <td><strong><?= number_format($dt['tikus_success_trap_avg'], 2); ?>%</strong></td>
                    <td><?= $dt['pinjal_survei']; ?></td>
                    <td><span class="badge badge-light"><?= $dt['pinjal_umum'] + $dt['pinjal_khusus']; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" class="text-left">Total / Rata-Rata Keseluruhan</td>
                  <td><?= $summary['jentik'] ?? 0; ?></td>
                  <td><span class="badge badge-danger"><?= $summary['kontainer_positif'] ?? 0; ?></span></td>
                  <td>-</td>
                  <td><?= $summary['lalat'] ?? 0; ?></td>
                  <td><?= number_format((float) ($summary['avg_lalat'] ?? 0), 2); ?></td>
                  <td>-</td>
                  <td><?= $summary['kecoa'] ?? 0; ?></td>
                  <td><span class="badge badge-warning"><?= $summary['kecoa_tertangkap'] ?? 0; ?></span></td>
                  <td><?= array_sum(array_column($wilkerDetail, 'tikus_perangkap')); ?></td>
                  <td><span class="badge badge-info"><?= $summary['tikus_tertangkap'] ?? 0; ?></span></td>
                  <td><strong><?= number_format((float) ($summary['avg_success_trap'] ?? 0), 2); ?>%</strong></td>
                  <td><?= $summary['pinjal'] ?? 0; ?></td>
                  <td><span class="badge badge-dark"><?= $summary['pinjal_umum'] ?? 0; ?></span></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- 2. Tab Detail Jentik -->
        <div class="tab-pane fade" id="tab-detail-jentik">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm text-center master-table mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="text-left">Wilayah Kerja</th>
                  <th>Total Survei</th>
                  <th>Rumah Diperiksa</th>
                  <th>Rumah Positif</th>
                  <th>Kontainer Diperiksa</th>
                  <th>Kontainer Positif</th>
                  <th>ABJ (%)</th>
                  <th>Status Standar (Baku Mutu &ge; 95%)</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($wilkerDetail)): ?>
                <tr><td colspan="9" class="text-muted">Belum ada data surveilans jentik</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($wilkerDetail as $dt): 
                    $memenuhi = $dt['abj'] >= 95;
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($dt['wilker']); ?></td>
                    <td><strong><?= $dt['jentik_survei']; ?></strong></td>
                    <td><?= $dt['jentik_rumah_diperiksa']; ?></td>
                    <td><span class="badge badge-warning"><?= $dt['jentik_rumah_positif']; ?></span></td>
                    <td><?= $dt['jentik_kontainer_diperiksa']; ?></td>
                    <td><span class="badge badge-danger"><?= $dt['jentik_kontainer_positif']; ?></span></td>
                    <td><strong class="<?= $memenuhi ? 'text-success' : 'text-danger'; ?>"><?= $dt['abj']; ?>%</strong></td>
                    <td><span class="badge <?= $memenuhi ? 'badge-success' : 'badge-danger'; ?>"><?= $memenuhi ? 'MS' : 'TMS'; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3. Tab Detail Lalat -->
        <div class="tab-pane fade" id="tab-detail-lalat">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm text-center master-table mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="text-left">Wilayah Kerja</th>
                  <th>Total Survei</th>
                  <th>Rata-Rata Kepadatan (Fly Index)</th>
                  <th>Jumlah MS (&lt; 2)</th>
                  <th>Jumlah TMS (&ge; 2)</th>
                  <th>Status Keseluruhan</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($wilkerDetail)): ?>
                <tr><td colspan="7" class="text-muted">Belum ada data surveilans lalat</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($wilkerDetail as $dt): 
                    $status = $dt['lalat_avg'] < 2 ? '<span class="badge badge-success">MS</span>' : '<span class="badge badge-danger">TMS</span>';
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($dt['wilker']); ?></td>
                    <td><strong><?= $dt['lalat_survei']; ?></strong></td>
                    <td><strong class="h6 mb-0"><?= number_format($dt['lalat_avg'], 2); ?></strong></td>
                    <td><span class="badge badge-success px-2 py-1"><?= $dt['lalat_ms']; ?></span></td>
                    <td><span class="badge badge-danger px-2 py-1"><?= $dt['lalat_tms']; ?></span></td>
                    <td><?= $dt['lalat_survei'] > 0 ? $status : '-'; ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 4. Tab Detail Kecoa -->
        <div class="tab-pane fade" id="tab-detail-kecoa">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm text-center master-table mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="text-left">Wilayah Kerja</th>
                  <th>Total Survei</th>
                  <th>Perangkap Terpasang</th>
                  <th>Total Tertangkap</th>
                  <th>Rata-Rata Kepadatan / Perangkap</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($wilkerDetail)): ?>
                <tr><td colspan="6" class="text-muted">Belum ada data surveilans kecoa</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($wilkerDetail as $dt): 
                    $kepadatan = $dt['kecoa_perangkap'] > 0 ? round($dt['kecoa_tertangkap'] / $dt['kecoa_perangkap'], 2) : 0;
                  ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($dt['wilker']); ?></td>
                    <td><strong><?= $dt['kecoa_survei']; ?></strong></td>
                    <td><?= $dt['kecoa_perangkap']; ?></td>
                    <td><span class="badge badge-warning px-2 py-1"><?= $dt['kecoa_tertangkap']; ?></span></td>
                    <td><strong><?= number_format($kepadatan, 2); ?></strong></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 5. Tab Detail Tikus & Pinjal -->
        <div class="tab-pane fade" id="tab-detail-tikus">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm text-center master-table mb-0">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th class="text-left">Wilayah Kerja</th>
                  <th>Survei Tikus</th>
                  <th>Perangkap Dipasang</th>
                  <th>Tikus Tertangkap</th>
                  <th>Success Trap (%)</th>
                  <th>Koleksi Pinjal</th>
                  <th>Pinjal Umum</th>
                  <th>Pinjal Khusus</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($wilkerDetail)): ?>
                <tr><td colspan="9" class="text-muted">Belum ada data surveilans tikus dan pinjal</td></tr>
                <?php else: ?>
                  <?php $no = 1; foreach($wilkerDetail as $dt): ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="text-left font-weight-bold"><?= esc($dt['wilker']); ?></td>
                    <td><strong><?= $dt['tikus_survei']; ?></strong></td>
                    <td><?= $dt['tikus_perangkap']; ?></td>
                    <td><span class="badge badge-info px-2 py-1"><?= $dt['tikus_tertangkap']; ?></span></td>
                    <td><strong><?= number_format($dt['tikus_success_trap_avg'], 2); ?>%</strong></td>
                    <td><?= $dt['pinjal_survei']; ?></td>
                    <td><?= $dt['pinjal_umum']; ?></td>
                    <td><?= $dt['pinjal_khusus']; ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  (function() {
    // 1. In-card Toggle between Chart and Detail Table
    $(document).off('click', '.btn-toggle-card-view').on('click', '.btn-toggle-card-view', function() {
      var $btn = $(this);
      var type = $btn.data('type');
      var card = $btn.data('card');

      $btn.siblings().removeClass('active');
      $btn.addClass('active');

      var $chartWrap = $('#chart-wrap-' + card);
      var $tableWrap = $('#table-wrap-' + card);

      if (type === 'table') {
        $chartWrap.slideUp(180, function() {
          $tableWrap.slideDown(200);
        });
      } else {
        $tableWrap.slideUp(180, function() {
          $chartWrap.slideDown(200, function() {
            if (window.siveraDashboardCharts && window.siveraDashboardCharts[card]) {
              window.siveraDashboardCharts[card].resize();
            }
          });
        });
      }
    });

    // 2. Chart.js rendering
    if (typeof window.Chart === 'undefined') {
      return;
    }

    window.siveraDashboardCharts = window.siveraDashboardCharts || {};
    Object.keys(window.siveraDashboardCharts).forEach(function(key) {
      if (window.siveraDashboardCharts[key]) {
        window.siveraDashboardCharts[key].destroy();
      }
    });
    window.siveraDashboardCharts = {};

    const labels = <?= json_encode(array_values($labels)); ?>;
    const kecoaData = <?= json_encode($kecoaChart); ?>;
    const lalatStatusData = <?= json_encode($lalatStatusChart); ?>;
    const jentikKontainerData = <?= json_encode($jentikKontainerChart); ?>;
    const spesiesTikusData = <?= json_encode($spesiesTikusChart); ?>;

    const colors = ['#2563eb', '#f97316', '#94a3b8', '#facc15', '#10b981', '#8b5cf6', '#ef4444'];

    function makeBarDatasets(dataObj) {
      var ds = Object.keys(dataObj).map(function(key, index) {
        return {
          label: key,
          data: dataObj[key],
          backgroundColor: colors[index % colors.length],
          maxBarThickness: 26
        };
      });
      return ds;
    }

    function makeLegend(showLegend) {
      var baseLegend = {
        position: 'bottom',
        labels: { boxWidth: 12, padding: 14 }
      };
      if (showLegend === false) {
        baseLegend.display = false;
      }
      return baseLegend;
    }

    window.siveraDashboardCharts.kecoa = new Chart(document.getElementById('chart-sivera-kecoa'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: makeBarDatasets(kecoaData)
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: makeLegend(true),
        tooltips: { mode: 'index', intersect: false },
        scales: {
          yAxes: [{ beginAtZero: true, gridLines: { color: 'rgba(148, 163, 184, 0.2)' }, ticks: { beginAtZero: true } }],
          xAxes: [{ gridLines: { display: false } }]
        }
      }
    });

    window.siveraDashboardCharts.lalat = new Chart(document.getElementById('chart-sivera-lalat-status'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'MS',
            data: lalatStatusData.MS || [],
            backgroundColor: '#2563eb',
            maxBarThickness: 28
          },
          {
            label: 'TMS',
            data: lalatStatusData.TMS || [],
            backgroundColor: '#f97316',
            maxBarThickness: 28
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: makeLegend(true),
        tooltips: { mode: 'index', intersect: false },
        scales: {
          yAxes: [{ beginAtZero: true, gridLines: { color: 'rgba(148, 163, 184, 0.2)' }, ticks: { beginAtZero: true } }],
          xAxes: [{ gridLines: { display: false } }]
        }
      }
    });

    window.siveraDashboardCharts.jentik = new Chart(document.getElementById('chart-sivera-jentik'), {
      type: 'bar',
      data: {
        labels: Object.keys(jentikKontainerData),
        datasets: [
          {
            label: 'Jumlah Temuan',
            data: Object.values(jentikKontainerData),
            backgroundColor: '#2563eb',
            maxBarThickness: 34
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: makeLegend(false),
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel;
            }
          }
        },
        scales: {
          yAxes: [{ beginAtZero: true, gridLines: { color: 'rgba(148, 163, 184, 0.2)' }, ticks: { beginAtZero: true } }],
          xAxes: [{ gridLines: { display: false } }]
        }
      }
    });

    window.siveraDashboardCharts.tikus = new Chart(document.getElementById('chart-sivera-tikus'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: makeBarDatasets(spesiesTikusData)
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: makeLegend(true),
        tooltips: { mode: 'index', intersect: false },
        scales: {
          yAxes: [{ beginAtZero: true, gridLines: { color: 'rgba(148, 163, 184, 0.2)' }, ticks: { beginAtZero: true } }],
          xAxes: [{ gridLines: { display: false } }]
        }
      }
    });
  })();
</script>
