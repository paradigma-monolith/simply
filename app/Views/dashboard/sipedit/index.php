<?php
$pelatihan_jafung_sudah = $pelatihan_jafung_sudah ?? [];
$pelatihan_jafung_belum = $pelatihan_jafung_belum ?? [];
$jafung_pns_sudah = $jafung_pns_sudah ?? [];
$jafung_pns_belum = $jafung_pns_belum ?? [];
$jafung_pppk_sudah = $jafung_pppk_sudah ?? [];
$jafung_pppk_belum = $jafung_pppk_belum ?? [];
$jafung_nakes_sudah = $jafung_nakes_sudah ?? [];
$jafung_nakes_belum = $jafung_nakes_belum ?? [];
$jafung_nonnakes_sudah = $jafung_nonnakes_sudah ?? [];
$jafung_nonnakes_belum = $jafung_nonnakes_belum ?? [];

$total_pegawai_pelatihan = count($memenuhi) + count($belum_memenuhi);
$total_jafung = count($pelatihan_jafung_sudah) + count($pelatihan_jafung_belum);
$periode_tahun = $periode_tahun ?? date('Y');
$bulan = $bulan ?? date('n');
$daftarBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
];

// Perhitungan Anggaran Pelatihan JaFung
$nakes_prices = [
    'Perawat' => 1700000,
    'Dokter' => 4000000,
    'Epidemiolog' => 4000000,
    'Sanitarian' => 2300000,
    'Pembimbing Kesehatan Kerja' => 1800000,
    'Administrator Kesehatan' => 3800000,
    'Laboratorium Kesehatan' => 4000000,
];

$non_nakes_prices = [
    'Keuangan' => 4500000,
    'Perencana' => 4500000,
    'Anggaran' => 4000000,
    'Sumber Daya Manusia' => 5199000,
    'Arsiparis' => 6790000,
    'Hubungan Masyarakat' => 3000000,
    'Pranata Komputer' => 4000000,
];

$anggaran_nakes_pns = [];
$anggaran_non_nakes_pns = [];
$anggaran_nakes_pppk = [];
$anggaran_non_nakes_pppk = [];
$total_anggaran_pns = 0;
$total_anggaran_pppk = 0;
$total_anggaran = 0; // Tetap ada untuk compatibility jika diperlukan

foreach ($nakes_prices as $jabatan => $price) {
    $anggaran_nakes_pns[$jabatan] = ['price' => $price, 'count' => 0, 'total' => 0];
    $anggaran_nakes_pppk[$jabatan] = ['price' => $price, 'count' => 0, 'total' => 0];
}
foreach ($non_nakes_prices as $jabatan => $price) {
    $anggaran_non_nakes_pns[$jabatan] = ['price' => $price, 'count' => 0, 'total' => 0];
    $anggaran_non_nakes_pppk[$jabatan] = ['price' => $price, 'count' => 0, 'total' => 0];
}

foreach ($pelatihan_jafung_belum as $pegawai) {
    $jabatan_str = strtolower($pegawai['nama_jabatan'] ?? '');
    $is_pns = ($pegawai['id_status_pegawai'] == 2);
    $matched = false;
    
    // Check Nakes
    foreach ($nakes_prices as $k => $v) {
        if (strpos($jabatan_str, strtolower($k)) !== false) {
            if ($is_pns) {
                $anggaran_nakes_pns[$k]['count']++;
                $anggaran_nakes_pns[$k]['total'] += $v;
                $total_anggaran_pns += $v;
            } else {
                $anggaran_nakes_pppk[$k]['count']++;
                $anggaran_nakes_pppk[$k]['total'] += $v;
                $total_anggaran_pppk += $v;
            }
            $total_anggaran += $v;
            $matched = true;
            break;
        }
    }
    
    if (!$matched) {
        // Check Non Nakes
        foreach ($non_nakes_prices as $k => $v) {
            if (strpos($jabatan_str, strtolower($k)) !== false) {
                if ($is_pns) {
                    $anggaran_non_nakes_pns[$k]['count']++;
                    $anggaran_non_nakes_pns[$k]['total'] += $v;
                    $total_anggaran_pns += $v;
                } else {
                    $anggaran_non_nakes_pppk[$k]['count']++;
                    $anggaran_non_nakes_pppk[$k]['total'] += $v;
                    $total_anggaran_pppk += $v;
                }
                $total_anggaran += $v;
                $matched = true;
                break;
            }
        }
    }
}
?>

<style>
  .sipedit-dashboard .nav-pills {
    gap: 8px;
  }

  .sipedit-dashboard .nav-pills .nav-link {
    border-radius: 999px;
    padding: 0.55rem 1rem;
    font-weight: 600;
    color: #495057;
    background: #f4f6f9;
  }

  .sipedit-dashboard .nav-pills .nav-link.active {
    background: #007bff;
    box-shadow: 0 8px 20px rgba(0, 123, 255, 0.18);
  }

  .sipedit-dashboard .section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1rem;
  }

  .sipedit-dashboard .section-title h4,
  .sipedit-dashboard .section-title h5 {
    margin: 0;
    font-weight: 700;
  }

  .sipedit-dashboard .compact-card {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 10px 24px rgba(31, 45, 61, 0.08);
  }

  .sipedit-dashboard .compact-card .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 0.85rem 1rem;
  }

  .sipedit-dashboard .compact-card .card-title {
    font-size: 0.98rem;
    font-weight: 700;
  }

  .sipedit-dashboard .metric-card {
    border-radius: 14px;
    padding: 1rem;
    height: 100%;
    color: #1f2d3d;
    background: linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
    box-shadow: 0 10px 24px rgba(31, 45, 61, 0.08);
  }

  .sipedit-dashboard .metric-card .metric-label {
    display: block;
    margin-bottom: 0.35rem;
    color: #6c757d;
    font-size: 0.82rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .sipedit-dashboard .metric-card .metric-value {
    display: block;
    font-size: 1.7rem;
    line-height: 1.1;
    font-weight: 700;
  }

  .sipedit-dashboard .metric-card .metric-note {
    display: block;
    margin-top: 0.35rem;
    font-size: 0.82rem;
    color: #6c757d;
  }

  .sipedit-dashboard .category-list {
    display: grid;
    gap: 10px;
  }

  .sipedit-dashboard .category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 0.75rem 0.9rem;
    border-radius: 12px;
    background: #f8f9fb;
  }

  .sipedit-dashboard .category-item strong {
    font-size: 0.95rem;
  }

  .sipedit-dashboard .chart-card canvas {
    min-height: 215px !important;
    height: 215px !important;
    max-height: 215px !important;
  }

  .sipedit-dashboard .table-card .card-body {
    padding: 0.5rem 0.75rem 0.75rem;
  }

  .sipedit-dashboard .table-responsive {
    border-radius: 12px;
    max-height: 360px;
  }

  .sipedit-dashboard .table {
    margin-bottom: 0;
  }

  .sipedit-dashboard .table thead th {
    position: sticky;
    top: 0;
    z-index: 1;
    background: #f8f9fb;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    border-bottom: 1px solid #dee2e6;
  }

  .sipedit-dashboard .table td,
  .sipedit-dashboard .table th {
    padding: 0.55rem 0.65rem;
    vertical-align: middle;
  }

  .sipedit-dashboard .table .name-cell {
    min-width: 260px;
    font-weight: 600;
  }

  .sipedit-dashboard .table .number-cell {
    white-space: nowrap;
    text-align: right;
    font-weight: 700;
  }

  .sipedit-dashboard .card-subtitle {
    display: block;
    margin-top: 0.2rem;
    color: #6c757d;
    font-size: 0.82rem;
  }

  .sipedit-dashboard .pelatihan-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    margin-bottom: 1rem;
    border-radius: 14px;
    background: linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
    border: 1px solid rgba(0, 123, 255, 0.12);
    box-shadow: 0 10px 24px rgba(31, 45, 61, 0.06);
  }

  .sipedit-dashboard .pelatihan-toolbar .field {
    flex: 1 1 180px;
    min-width: 0;
  }

  .sipedit-dashboard .pelatihan-toolbar label {
    display: block;
    margin-bottom: 0.35rem;
    color: #495057;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .sipedit-dashboard .pelatihan-toolbar .select2-container {
    width: 100% !important;
  }

  .sipedit-dashboard .pelatihan-toolbar .toolbar-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  /* Menghilangkan batasan tinggi maksimal (scroll) HANYA pada tabel anggaran */
  .table-anggaran-container {
    max-height: none !important;
    overflow-y: visible !important;
  }

  .dashboard-filter#sipedit-filter,
  form:has(#sipedit-filter) {
    display: none !important;
  }
</style>

<div class="card card-outline card-primary sipedit-dashboard">
  <div class="card-header p-2">
    <ul class="nav nav-pills">
      <li class="nav-item"><a class="nav-link active" href="#dashboard-pelatihan" data-toggle="tab">Dashboard Pelatihan</a></li>
      <li class="nav-item"><a class="nav-link" href="#dashboard-jafung" data-toggle="tab">Dashboard JaFung</a></li>
    </ul>
  </div><!-- /.card-header -->
  <div class="card-body">
    <div class="tab-content">
      <div class="active tab-pane" id="dashboard-pelatihan">
        <div class="section-title">
          <div>
            <h4>Dashboard Pelatihan</h4>
            <small class="text-muted">Pilih periode dan bulan untuk menampilkan data pemenuhan JPL.</small>
          </div>
          <span class="badge badge-light px-3 py-2">Periode <?= $tahun; ?></span>
        </div>

        <form action="/dashboard/sipedit/export" method="post" class="pelatihan-toolbar" id="sipedit-pelatihan-toolbar">
          <div class="field">
            <label for="periodeSipedit-dashboard-inner">Periode</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <select id="periodeSipedit-dashboard-inner" name="sipedit_tanggal" class="form-control select2">
                <option value="all">--Semua Periode--</option>
                <?php for ($i = (int)date('Y'); $i > 2021; $i--) : ?>
                  <option value="<?= $i; ?>" <?= (string)$periode_tahun === (string)$i ? 'selected' : ''; ?>><?= $i; ?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="bulanSipedit-dashboard-inner">Sampai Bulan</label>
            <select id="bulanSipedit-dashboard-inner" name="sipedit_bulan" class="form-control select2" <?= $periode_tahun === 'all' ? 'disabled' : ''; ?>>
              <?php foreach ($daftarBulan as $noBulan => $namaBulan) : ?>
                <option value="<?= $noBulan; ?>" <?= (int)$bulan === (int)$noBulan ? 'selected' : ''; ?>><?= $namaBulan; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="toolbar-actions">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
          </div>
        </form>

        <div class="row">
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Total Arsip Terunggah</span>
              <span class="metric-value"><?= $total_jpl['total_jpl']; ?></span>
              <span class="metric-note">Akumulasi data pelatihan periode <?= $tahun; ?></span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Pegawai Dinilai</span>
              <span class="metric-value"><?= $total_pegawai_pelatihan; ?></span>
              <span class="metric-note">Populasi pegawai aktif yang masuk penilaian</span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Sudah Memenuhi</span>
              <span class="metric-value text-success"><?= count($memenuhi); ?></span>
              <span class="metric-note"><?= $total_pegawai_pelatihan > 0 ? round((count($memenuhi) / $total_pegawai_pelatihan) * 100, 2) : 0; ?>% dari total pegawai</span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Belum Memenuhi</span>
              <span class="metric-value text-danger"><?= count($belum_memenuhi); ?></span>
              <span class="metric-note"><?= $total_pegawai_pelatihan > 0 ? round((count($belum_memenuhi) / $total_pegawai_pelatihan) * 100, 2) : 0; ?>% dari total pegawai</span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title mb-0">Komposisi Jenis Pelatihan</h3>
                <span class="card-subtitle">Distribusi arsip berdasarkan kategori</span>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="max-width: 100%;"></canvas>
                <div class="category-list mt-3">
                  <?php foreach ($total_jpl_per_kategori as $data) : ?>
                    <div class="category-item">
                      <div>
                        <small class="text-muted d-block">Kategori</small>
                        <strong><?= $data['kategori_nama']; ?></strong>
                      </div>
                      <div class="text-right">
                        <strong><?= $data['total_jpl']; ?></strong>
                        <small class="d-block text-muted"><?= $total_jpl['total_jpl'] > 0 ? round((($data['total_jpl'] / $total_jpl['total_jpl']) * 100), 2) : 0; ?>%</small>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8 mb-3">
            <div class="card compact-card h-100">
              <div class="card-header">
                <h3 class="card-title mb-0">Pemenuhan Minimal 20 JPL</h3>
                <span class="card-subtitle">Target organisasi 80% pegawai memenuhi JPL tahunan</span>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 text-center mb-3 mb-md-0">
                    <input type="text" disabled class="knob" value="<?= $hitunganPemenuhan = $total_pegawai_pelatihan > 0 ? round(((count($memenuhi) / $total_pegawai_pelatihan) * 100), 0) : 0; ?>" data-width="250" data-skin="tron" data-thickness="0.18" data-height="250" data-fgColor="<?= $hitunganPemenuhan >= 80 ? '#28a745' : '#f56954'; ?>">
                    <div class="knob-label font-weight-bold mt-2">Sudah memenuhi</div>
                    <small class="d-block text-muted"><?= count($memenuhi); ?> pegawai • <?= $total_pegawai_pelatihan > 0 ? round(((count($memenuhi) / $total_pegawai_pelatihan) * 100), 2) : 0; ?>%</small>
                  </div>
                  <div class="col-md-6 text-center">
                    <input type="text" disabled class="knob" value="<?= $hitunganPemenuhan = $total_pegawai_pelatihan > 0 ? round(((count($belum_memenuhi) / $total_pegawai_pelatihan) * 100), 0) : 0; ?>" data-width="250" data-skin="tron" data-thickness="0.18" data-height="250" data-fgColor="<?= $hitunganPemenuhan == 0 ? '#28a745' : '#f56954'; ?>">
                    <div class="knob-label font-weight-bold mt-2">Belum memenuhi</div>
                    <small class="d-block text-muted"><?= count($belum_memenuhi); ?> pegawai • <?= $total_pegawai_pelatihan > 0 ? round(((count($belum_memenuhi) / $total_pegawai_pelatihan) * 100), 2) : 0; ?>%</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<!-- Main row -->
<div class="row">
  <!-- Left col -->
  <div class="col-xl-5 col-lg-6 mb-3">

    <!-- TABLE: LATEST ORDERS -->
    <div class="card compact-card table-card h-100">
      <div class="card-header border-transparent">
        <h3 class="card-title">Pegawai Sudah Memenuhi</h3>
        <span class="card-subtitle"><?= count($memenuhi); ?> pegawai dengan minimal 20 JPL pada <?= $tahun; ?></span>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>NIP | Nama Pegawai</th>
                <th>Total JPL</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($memenuhi as $data) : ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                  <td class="number-cell"><?= $data['total_arsip_jpl']; ?></td>
                  <?php if ($data['total_arsip_jpl'] >= 20) : ?>
                    <td><span class="badge badge-success">Sudah memenuhi</span></td>
                  <?php else : ?>
                    <td><span class="badge badge-danger">Belum memenuhi</span></td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- /.table-responsive -->
      </div>
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-xl-5 col-lg-6 mb-3">

    <!-- TABLE: LATEST ORDERS -->
    <div class="card compact-card table-card h-100">
      <div class="card-header border-transparent">
        <h3 class="card-title">Pegawai Belum Memenuhi</h3>
        <span class="card-subtitle"><?= count($belum_memenuhi); ?> pegawai masih di bawah 20 JPL pada <?= $tahun; ?></span>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>NIP | Nama Pegawai</th>
                <th>Total JPL</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($belum_memenuhi as $data) : ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                  <td class="number-cell"><?= $data['total_arsip_jpl']; ?></td>
                  <?php if ($data['total_arsip_jpl'] >= 20) : ?>
                    <td><span class="badge badge-success">Sudah memenuhi</span></td>
                  <?php else : ?>
                    <td><span class="badge badge-danger">Belum memenuhi</span></td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- /.table-responsive -->
      </div>
    </div>
    <!-- /.card -->
  </div>

  <div class="col-xl-2 col-lg-12 mb-3">
    <?php if (isset($arsip_saya)) : ?>
      <!-- PRODUCT LIST -->
      <div class="card compact-card bg-info h-100">
        <div class="card-header">
          <h3 class="card-title">Total JPL Anda per Tahun</h3>
          <span class="card-subtitle text-white-50">Ringkasan progres pribadi</span>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <ul class="products-list product-list-in-card pl-2 pr-2">
            <?php foreach ($arsip_saya as $data) : ?>
              <li class="item">
                <div class="product-info">
                  <a href="javascript:void(0)" class="product-title"><?= $data['year']; ?>
                    <?php if ($data['total_arsip_jpl'] >= 20) : ?>
                      <span class="badge badge-success float-right mr-4">Memenuhi</span>
                    <?php else : ?>
                      <span class="badge badge-danger float-right mr-4">Belum memenuhi</span>
                    <?php endif; ?>
                  </a>
                  <span class="product-description">
                    <?= $data['total_arsip_jpl']; ?> JPL
                  </span>
                </div>
              </li>
            <?php endforeach; ?>
            <!-- /.item -->
          </ul>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
          <a href="javascript:void(0)" class="uppercase text-white">Minimal 20 JPL per Tahun</a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    <?php endif; ?>
  </div>
  <!-- /.col -->
</div>
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="dashboard-jafung">
        <div class="section-title mt-2">
          <h4>Dashboard JaFung</h4>
          <span class="badge badge-light px-3 py-2"><?= $total_jafung; ?> pegawai terpetakan</span>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Sudah Pelatihan</span>
              <span class="metric-value text-success"><?= count($pelatihan_jafung_sudah); ?></span>
              <span class="metric-note"><?= $total_jafung > 0 ? round((count($pelatihan_jafung_sudah) / $total_jafung) * 100, 2) : 0; ?>% dari total pegawai</span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">Belum Pelatihan</span>
              <span class="metric-value text-danger"><?= count($pelatihan_jafung_belum); ?></span>
              <span class="metric-note"><?= $total_jafung > 0 ? round((count($pelatihan_jafung_belum) / $total_jafung) * 100, 2) : 0; ?>% dari total pegawai</span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">PNS</span>
              <span class="metric-value"><?= count($jafung_pns_sudah) + count($jafung_pns_belum); ?></span>
              <span class="metric-note">Total pegawai PNS pada pemetaan JaFung</span>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="metric-card">
              <span class="metric-label">PPPK</span>
              <span class="metric-value"><?= count($jafung_pppk_sudah) + count($jafung_pppk_belum); ?></span>
              <span class="metric-note">Total pegawai PPPK pada pemetaan JaFung</span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-4 col-md-6 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title">Keseluruhan</h3>
                <span class="card-subtitle">Sudah vs belum pelatihan JaFung</span>
              </div>
              <div class="card-body">
                <canvas id="pieChartJafung" style="max-width: 100%;"></canvas>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-md-6 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title">PNS</h3>
                <span class="card-subtitle">Komposisi pegawai PNS</span>
              </div>
              <div class="card-body">
                <canvas id="pieChartPns" style="max-width: 100%;"></canvas>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-md-6 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title">PPPK</h3>
                <span class="card-subtitle">Komposisi pegawai PPPK</span>
              </div>
              <div class="card-body">
                <canvas id="pieChartPppk" style="max-width: 100%;"></canvas>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-md-6 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title">Tenaga Kesehatan</h3>
                <span class="card-subtitle">Kelompok Nakes</span>
              </div>
              <div class="card-body">
                <canvas id="pieChartNakes" style="max-width: 100%;"></canvas>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-md-6 mb-3">
            <div class="card compact-card chart-card h-100">
              <div class="card-header">
                <h3 class="card-title">Non Nakes</h3>
                <span class="card-subtitle">Kelompok substansi ADUM</span>
              </div>
              <div class="card-body">
                <canvas id="pieChartNonNakes" style="max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-7 mb-3">
            <div class="card compact-card table-card h-100">
              <div class="card-header border-transparent">
                <h3 class="card-title">Pegawai Sudah Pelatihan JaFung</h3>
                <span class="card-subtitle"><?= count($pelatihan_jafung_sudah); ?> pegawai telah mengisi detail pelatihan</span>
                <div class="card-tools">
                  <ul class="nav nav-pills" style="display:inline-flex; margin-right: 15px;">
                    <li class="nav-item"><a class="nav-link active" href="#sudah-pns" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sudah-pppk" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PPPK</a></li>
                  </ul>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="sudah-pns">
                    <div class="table-responsive">
                      <table class="table table-sm table-hover">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NIP | Nama Pegawai</th>
                            <th>Nama Pelatihan</th>
                            <th>Tahun</th>
                            <th>No Sertifikat</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1;
                          foreach ($jafung_pns_sudah as $data) : ?>
                            <tr>
                              <td><?= $no++; ?></td>
                              <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                              <td><?= $data['pelatihan_jafung_nama'] ?: '-'; ?></td>
                              <td class="number-cell"><?= $data['pelatihan_jafung_tahun'] ?: '-'; ?></td>
                              <td><?= $data['pelatihan_jafung_sertifikat'] ?: '-'; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="sudah-pppk">
                    <div class="table-responsive">
                      <table class="table table-sm table-hover">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NIP | Nama Pegawai</th>
                            <th>Nama Pelatihan</th>
                            <th>Tahun</th>
                            <th>No Sertifikat</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1;
                          foreach ($jafung_pppk_sudah as $data) : ?>
                            <tr>
                              <td><?= $no++; ?></td>
                              <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                              <td><?= $data['pelatihan_jafung_nama'] ?: '-'; ?></td>
                              <td class="number-cell"><?= $data['pelatihan_jafung_tahun'] ?: '-'; ?></td>
                              <td><?= $data['pelatihan_jafung_sertifikat'] ?: '-'; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-5 mb-3">
            <div class="card compact-card table-card h-100">
              <div class="card-header border-transparent">
                <h3 class="card-title">Pegawai Belum Pelatihan JaFung</h3>
                <span class="card-subtitle"><?= count($pelatihan_jafung_belum); ?> pegawai masih belum memiliki data pelatihan</span>
                <div class="card-tools">
                  <ul class="nav nav-pills" style="display:inline-flex; margin-right: 15px;">
                    <li class="nav-item"><a class="nav-link active" href="#belum-pns" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="#belum-pppk" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PPPK</a></li>
                  </ul>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="belum-pns">
                    <div class="table-responsive">
                      <table class="table table-sm table-hover">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NIP | Nama Pegawai</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1;
                          foreach ($jafung_pns_belum as $data) : ?>
                            <tr>
                              <td><?= $no++; ?></td>
                              <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="belum-pppk">
                    <div class="table-responsive">
                      <table class="table table-sm table-hover">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NIP | Nama Pegawai</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1;
                          foreach ($jafung_pppk_belum as $data) : ?>
                            <tr>
                              <td><?= $no++; ?></td>
                              <td class="name-cell"><?= $data['nip']; ?> | <?= $data['nama']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-12">
            <div class="card compact-card table-card">
              <div class="card-header border-transparent bg-light">
                <h3 class="card-title"><strong>Perhitungan Kebutuhan Anggaran Pelatihan</strong></h3>
                <span class="card-subtitle">Berdasarkan data Pegawai Belum Pelatihan JaFung</span>
                <div class="card-tools">
                  <?php if (session()->get('role') == 'Admin') : ?>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalPengaturanAnggaran" style="margin-right: 15px;">
                      <i class="fas fa-cog"></i> Pengaturan Anggaran
                    </button>
                  <?php endif; ?>
                  <ul class="nav nav-pills" style="display:inline-flex; margin-right: 15px;">
                    <li class="nav-item"><a class="nav-link active" href="#anggaran-pns" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PNS</a></li>
                    <li class="nav-item"><a class="nav-link" href="#anggaran-pppk" data-toggle="tab" style="padding: 0.2rem 0.75rem;">PPPK</a></li>
                  </ul>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="anggaran-pns">
                    <div class="mb-3">
                      <span class="badge badge-primary" style="font-size: 14px;">Total Anggaran PNS: Rp <?= number_format($total_anggaran_pns, 0, ',', '.'); ?></span>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <h5 class="text-success border-bottom pb-2">Tenaga Kesehatan (PNS)</h5>
                        <div class="table-responsive table-anggaran-container">
                          <table class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                              <tr>
                                <th>Klasifikasi Jabatan</th>
                                <th class="text-center">Link Ref</th>
                                <th>Harga Satuan</th>
                                <th>Jml Pegawai</th>
                                <th>Prakiraan Anggaran</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($anggaran_nakes_pns as $jabatan => $data) : ?>
                                <tr>
                                  <td><?= $jabatan; ?></td>
                                  <td class="text-center">
                                    <?php if (!empty($nakes_links[$jabatan]) && $nakes_links[$jabatan] != '#') : ?>
                                      <a href="<?= $nakes_links[$jabatan]; ?>" target="_blank" class="btn btn-xs btn-outline-info" title="Lihat Referensi">
                                        <i class="fas fa-link"></i>
                                      </a>
                                    <?php else : ?>
                                      <span class="text-muted">-</span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="text-right">Rp <?= number_format($data['price'], 0, ',', '.'); ?></td>
                                  <td class="text-center"><?= $data['count']; ?></td>
                                  <td class="text-right">Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                              <tr>
                                <th colspan="4" class="text-right">Subtotal:</th>
                                <th class="text-right text-success">
                                  Rp <?= number_format(array_sum(array_column($anggaran_nakes_pns, 'total')), 0, ',', '.'); ?>
                                </th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <h5 class="text-info border-bottom pb-2">Non Kesehatan (PNS)</h5>
                        <div class="table-responsive table-anggaran-container">
                          <table class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                              <tr>
                                <th>Klasifikasi Jabatan</th>
                                <th class="text-center">Link Ref</th>
                                <th>Harga Satuan</th>
                                <th>Jml Pegawai</th>
                                <th>Prakiraan Anggaran</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($anggaran_non_nakes_pns as $jabatan => $data) : ?>
                                <tr>
                                  <td><?= $jabatan; ?></td>
                                  <td class="text-center">
                                    <?php if (!empty($non_nakes_links[$jabatan]) && $non_nakes_links[$jabatan] != '#') : ?>
                                      <a href="<?= $non_nakes_links[$jabatan]; ?>" target="_blank" class="btn btn-xs btn-outline-info" title="Lihat Referensi">
                                        <i class="fas fa-link"></i>
                                      </a>
                                    <?php else : ?>
                                      <span class="text-muted">-</span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="text-right">Rp <?= number_format($data['price'], 0, ',', '.'); ?></td>
                                  <td class="text-center"><?= $data['count']; ?></td>
                                  <td class="text-right">Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                              <tr>
                                <th colspan="4" class="text-right">Subtotal:</th>
                                <th class="text-right text-info">
                                  Rp <?= number_format(array_sum(array_column($anggaran_non_nakes_pns, 'total')), 0, ',', '.'); ?>
                                </th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="anggaran-pppk">
                    <div class="mb-3">
                      <span class="badge badge-primary" style="font-size: 14px;">Total Anggaran PPPK: Rp <?= number_format($total_anggaran_pppk, 0, ',', '.'); ?></span>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <h5 class="text-success border-bottom pb-2">Tenaga Kesehatan (PPPK)</h5>
                        <div class="table-responsive table-anggaran-container">
                          <table class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                              <tr>
                                <th>Klasifikasi Jabatan</th>
                                <th class="text-center">Link Ref</th>
                                <th>Harga Satuan</th>
                                <th>Jml Pegawai</th>
                                <th>Prakiraan Anggaran</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($anggaran_nakes_pppk as $jabatan => $data) : ?>
                                <tr>
                                  <td><?= $jabatan; ?></td>
                                  <td class="text-center">
                                    <?php if (!empty($nakes_links[$jabatan]) && $nakes_links[$jabatan] != '#') : ?>
                                      <a href="<?= $nakes_links[$jabatan]; ?>" target="_blank" class="btn btn-xs btn-outline-info" title="Lihat Referensi">
                                        <i class="fas fa-link"></i>
                                      </a>
                                    <?php else : ?>
                                      <span class="text-muted">-</span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="text-right">Rp <?= number_format($data['price'], 0, ',', '.'); ?></td>
                                  <td class="text-center"><?= $data['count']; ?></td>
                                  <td class="text-right">Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                              <tr>
                                <th colspan="4" class="text-right">Subtotal:</th>
                                <th class="text-right text-success">
                                  Rp <?= number_format(array_sum(array_column($anggaran_nakes_pppk, 'total')), 0, ',', '.'); ?>
                                </th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <h5 class="text-info border-bottom pb-2">Non Kesehatan (PPPK)</h5>
                        <div class="table-responsive table-anggaran-container">
                          <table class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                              <tr>
                                <th>Klasifikasi Jabatan</th>
                                <th class="text-center">Link Ref</th>
                                <th>Harga Satuan</th>
                                <th>Jml Pegawai</th>
                                <th>Prakiraan Anggaran</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($anggaran_non_nakes_pppk as $jabatan => $data) : ?>
                                <tr>
                                  <td><?= $jabatan; ?></td>
                                  <td class="text-center">
                                    <?php if (!empty($non_nakes_links[$jabatan]) && $non_nakes_links[$jabatan] != '#') : ?>
                                      <a href="<?= $non_nakes_links[$jabatan]; ?>" target="_blank" class="btn btn-xs btn-outline-info" title="Lihat Referensi">
                                        <i class="fas fa-link"></i>
                                      </a>
                                    <?php else : ?>
                                      <span class="text-muted">-</span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="text-right">Rp <?= number_format($data['price'], 0, ',', '.'); ?></td>
                                  <td class="text-center"><?= $data['count']; ?></td>
                                  <td class="text-right">Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                              <tr>
                                <th colspan="4" class="text-right">Subtotal:</th>
                                <th class="text-right text-info">
                                  Rp <?= number_format(array_sum(array_column($anggaran_non_nakes_pppk, 'total')), 0, ',', '.'); ?>
                                </th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
  </div><!-- /.card-body -->
</div>

<script>
  $(function() {
    $('.knob').knob({
      draw: function() {

        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv) // Angle
            ,
            sa = this.startAngle // Previous start angle
            ,
            sat = this.startAngle // Start angle
            ,
            ea // Previous end angle
            ,
            eat = sat + a // End angle
            ,
            r = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor &&
            (sat = eat - 0.3) &&
            (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor &&
              (sa = ea - 0.3) &&
              (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    })
    /* END JQUERY KNOB */
  })
</script>
<script>
  $(function() {
    var labelData = [];
    var valueData = [];
    var colorData = ['#f39c12', '#00c0ef'];
    <?php foreach ($total_jpl_per_kategori as $key => $data) { ?>
      labelData[<?= $key; ?>] = '<?= $data['kategori_nama']; ?>'
      valueData[<?= $key; ?>] = <?= $data['total_jpl']; ?>

      // var color = "#" + Math.floor(Math.random() * 16777215).toString(16); // Menghasilkan kode warna acak dalam format heksadesimal
      // colorData[<?= $key; ?>] = color
    <?php } ?>

    var donutData = {
      labels: labelData,
      datasets: [{
        data: valueData,
        backgroundColor: colorData,
      }]
    }
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = donutData;
    var pieOptions = {
      maintainAspectRatio: false,
      responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    //-------------
    //- PIE CHART JAFUNG KESELURUHAN -
    //-------------
    <?php 
      $total_jafung = count($pelatihan_jafung_sudah) + count($pelatihan_jafung_belum);
      $persen_sudah = $total_jafung > 0 ? round((count($pelatihan_jafung_sudah) / $total_jafung) * 100, 2) : 0;
      $persen_belum = $total_jafung > 0 ? round((count($pelatihan_jafung_belum) / $total_jafung) * 100, 2) : 0;
    ?>
    var pieChartJafungCanvas = $('#pieChartJafung').get(0).getContext('2d')
    var pieDataJafung = {
      labels: [
        'Sudah (<?= count($pelatihan_jafung_sudah); ?> - <?= $persen_sudah; ?>%)', 
        'Belum (<?= count($pelatihan_jafung_belum); ?> - <?= $persen_belum; ?>%)'
      ],
      datasets: [{
        data: [<?= count($pelatihan_jafung_sudah); ?>, <?= count($pelatihan_jafung_belum); ?>],
        backgroundColor: ['#00a65a', '#f56954'],
      }]
    }
    new Chart(pieChartJafungCanvas, {
      type: 'pie',
      data: pieDataJafung,
      options: pieOptions
    })

    //-------------
    //- PIE CHART PNS -
    //-------------
    <?php 
      $total_pns = count($jafung_pns_sudah) + count($jafung_pns_belum);
      $persen_pns_sudah = $total_pns > 0 ? round((count($jafung_pns_sudah) / $total_pns) * 100, 2) : 0;
      $persen_pns_belum = $total_pns > 0 ? round((count($jafung_pns_belum) / $total_pns) * 100, 2) : 0;
    ?>
    var pieChartPnsCanvas = $('#pieChartPns').get(0).getContext('2d')
    var pieDataPns = {
      labels: [
        'Sudah (<?= count($jafung_pns_sudah); ?> - <?= $persen_pns_sudah; ?>%)', 
        'Belum (<?= count($jafung_pns_belum); ?> - <?= $persen_pns_belum; ?>%)'
      ],
      datasets: [{
        data: [<?= count($jafung_pns_sudah); ?>, <?= count($jafung_pns_belum); ?>],
        backgroundColor: ['#00a65a', '#f56954'],
      }]
    }
    new Chart(pieChartPnsCanvas, {
      type: 'pie',
      data: pieDataPns,
      options: pieOptions
    })

    //-------------
    //- PIE CHART PPPK -
    //-------------
    <?php 
      $total_pppk = count($jafung_pppk_sudah) + count($jafung_pppk_belum);
      $persen_pppk_sudah = $total_pppk > 0 ? round((count($jafung_pppk_sudah) / $total_pppk) * 100, 2) : 0;
      $persen_pppk_belum = $total_pppk > 0 ? round((count($jafung_pppk_belum) / $total_pppk) * 100, 2) : 0;
    ?>
    var pieChartPppkCanvas = $('#pieChartPppk').get(0).getContext('2d')
    var pieDataPppk = {
      labels: [
        'Sudah (<?= count($jafung_pppk_sudah); ?> - <?= $persen_pppk_sudah; ?>%)', 
        'Belum (<?= count($jafung_pppk_belum); ?> - <?= $persen_pppk_belum; ?>%)'
      ],
      datasets: [{
        data: [<?= count($jafung_pppk_sudah); ?>, <?= count($jafung_pppk_belum); ?>],
        backgroundColor: ['#00a65a', '#f56954'],
      }]
    }
    new Chart(pieChartPppkCanvas, {
      type: 'pie',
      data: pieDataPppk,
      options: pieOptions
    })

    //-------------
    //- PIE CHART NAKES -
    //-------------
    <?php 
      $total_nakes = count($jafung_nakes_sudah) + count($jafung_nakes_belum);
      $persen_nakes_sudah = $total_nakes > 0 ? round((count($jafung_nakes_sudah) / $total_nakes) * 100, 2) : 0;
      $persen_nakes_belum = $total_nakes > 0 ? round((count($jafung_nakes_belum) / $total_nakes) * 100, 2) : 0;
    ?>
    var pieChartNakesCanvas = $('#pieChartNakes').get(0).getContext('2d')
    var pieDataNakes = {
      labels: [
        'Sudah (<?= count($jafung_nakes_sudah); ?> - <?= $persen_nakes_sudah; ?>%)', 
        'Belum (<?= count($jafung_nakes_belum); ?> - <?= $persen_nakes_belum; ?>%)'
      ],
      datasets: [{
        data: [<?= count($jafung_nakes_sudah); ?>, <?= count($jafung_nakes_belum); ?>],
        backgroundColor: ['#00a65a', '#f56954'],
      }]
    }
    new Chart(pieChartNakesCanvas, {
      type: 'pie',
      data: pieDataNakes,
      options: pieOptions
    })

    //-------------
    //- PIE CHART NON NAKES -
    //-------------
    <?php 
      $total_nonnakes = count($jafung_nonnakes_sudah) + count($jafung_nonnakes_belum);
      $persen_nonnakes_sudah = $total_nonnakes > 0 ? round((count($jafung_nonnakes_sudah) / $total_nonnakes) * 100, 2) : 0;
      $persen_nonnakes_belum = $total_nonnakes > 0 ? round((count($jafung_nonnakes_belum) / $total_nonnakes) * 100, 2) : 0;
    ?>
    var pieChartNonNakesCanvas = $('#pieChartNonNakes').get(0).getContext('2d')
    var pieDataNonNakes = {
      labels: [
        'Sudah (<?= count($jafung_nonnakes_sudah); ?> - <?= $persen_nonnakes_sudah; ?>%)', 
        'Belum (<?= count($jafung_nonnakes_belum); ?> - <?= $persen_nonnakes_belum; ?>%)'
      ],
      datasets: [{
        data: [<?= count($jafung_nonnakes_sudah); ?>, <?= count($jafung_nonnakes_belum); ?>],
        backgroundColor: ['#00a65a', '#f56954'],
      }]
    }
    new Chart(pieChartNonNakesCanvas, {
      type: 'pie',
      data: pieDataNonNakes,
      options: pieOptions
    })

  })
</script>

<?php if (session()->get('role') == 'Admin') : ?>
<!-- Modal Pengaturan Anggaran -->
<div class="modal fade" id="modalPengaturanAnggaran" tabindex="-1" role="dialog" aria-labelledby="modalPengaturanAnggaranLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="<?= base_url('sipedit/simpan_pengaturan'); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPengaturanAnggaranLabel">Pengaturan Referensi Anggaran</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <h6 class="font-weight-bold mb-3">Harga Satuan & Link Referensi (Tenaga Kesehatan)</h6>
          <?php foreach ($nakes_prices as $jabatan => $harga) : ?>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label"><?= $jabatan; ?></label>
              <div class="col-sm-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                  </div>
                  <input type="text" class="form-control" name="harga_nakes[<?= $jabatan; ?>]" value="<?= $harga; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
              </div>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="link_nakes[<?= $jabatan; ?>]" value="<?= $nakes_links[$jabatan] ?? '#'; ?>" placeholder="Link referensi...">
              </div>
            </div>
          <?php endforeach; ?>

          <hr>
          
          <h6 class="font-weight-bold mb-3">Harga Satuan & Link Referensi (Non Kesehatan)</h6>
          <?php foreach ($non_nakes_prices as $jabatan => $harga) : ?>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label"><?= $jabatan; ?></label>
              <div class="col-sm-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                  </div>
                  <input type="text" class="form-control" name="harga_non_nakes[<?= $jabatan; ?>]" value="<?= $harga; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
              </div>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="link_non_nakes[<?= $jabatan; ?>]" value="<?= $non_nakes_links[$jabatan] ?? '#'; ?>" placeholder="Link referensi...">
              </div>
            </div>
          <?php endforeach; ?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
