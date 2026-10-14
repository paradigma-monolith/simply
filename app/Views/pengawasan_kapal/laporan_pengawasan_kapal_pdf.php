<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  /* Active Filter Styling */
  .filter-active {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    background-color: #f8f9ff !important;
  }

  .filter-badge {
    position: relative;
    top: -2px;
    font-size: 0.8em;
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0% {
      opacity: 1;
    }

    50% {
      opacity: 0.5;
    }

    100% {
      opacity: 1;
    }
  }

  #active-filter-count {
    animation: fadeIn 0.3s ease-in;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Enhanced filter section styling */
  .card-outline.card-secondary {
    border-top: 3px solid #6c757d;
  }

  .filter-section-active {
    border-top: 3px solid #007bff !important;
  }

  /* Style untuk tooltip agar tidak terlalu memanjang */
  .tooltip {
    max-width: 250px !important;
    width: auto !important;
  }

  .tooltip-inner {
    max-width: 250px !important;
    width: auto !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    text-align: left !important;
    padding: 8px 12px !important;
    line-height: 1.4 !important;
    font-size: 12px !important;
  }

  /* Khusus untuk Bootstrap tooltip */
  .bs-tooltip-bottom .tooltip-inner,
  .bs-tooltip-top .tooltip-inner,
  .bs-tooltip-left .tooltip-inner,
  .bs-tooltip-right .tooltip-inner {
    max-width: 250px !important;
    width: auto !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
  }
</style>

<script>
  // Inject raw data from PHP
  var rawData = <?= json_encode($pengawasanKapal); ?>;
</script>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-file-alt mr-2"></i>
              Laporan Pengawasan Kapal
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" onclick="window.print()">
                <i class="fas fa-print"></i> Print
              </button>
              <button type="button" class="btn btn-primary btn-sm" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
              </button>
              <button type="button" class="btn btn-danger btn-sm" onclick="exportToPdf()">
                <i class="fas fa-file-pdf"></i> Laporan PDF
              </button>
            </div>

          </div>

          <!-- Top Verifikator -->
          <div class="row mb-4">
            <div class="col-12">
              <div class="card collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-user-check mr-2"></i>
                    Verifikator Terbanyak
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                  $verCounts = [];
                  if (isset($pengawasanKapal) && is_array($pengawasanKapal)) {
                    foreach ($pengawasanKapal as $d) {
                      $statusVerifikasi = $d['status_verifikasi'] ?? 'pending';
                      $ver = $d['verified_by'] ?? '';
                      if ($statusVerifikasi !== 'pending' && !empty($ver)) {
                        $key = trim($ver);
                        $verCounts[$key] = ($verCounts[$key] ?? 0) + 1;
                      }
                    }
                    arsort($verCounts);
                  }
                  ?>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped">
                      <thead>
                        <tr>
                          <th>Verifikator</th>
                          <th class="text-right">Jumlah Verifikasi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($verCounts)): ?>
                          <?php foreach ($verCounts as $name => $count): ?>
                            <tr>
                              <td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
                              <td class="text-right"><?= $count; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="2" class="text-center">Belum ada data verifikasi</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-3" id="filter-section">
              <div class="col-12">
                <div class="card card-outline card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-filter mr-1"></i> Filter Data
                    </h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <!-- Rentang Waktu -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="filter-date-range">Rentang Waktu:</label>
                          <input type="text" class="form-control form-control-sm" id="filter-date-range"
                            placeholder="Pilih rentang tanggal">
                        </div>
                      </div>

                      <!-- Asal -->
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="filter-asal">Asal:</label>
                          <select class="form-control form-control-sm" id="filter-asal">
                            <option value="">Semua</option>
                            <option value="Dalam Negeri">Dalam Negeri</option>
                            <option value="Luar Negeri">Luar Negeri</option>
                          </select>
                        </div>
                      </div>

                      <!-- Pelabuhan Tujuan -->
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="filter-pelabuhan">Pelabuhan Tujuan:</label>
                          <select class="form-control form-control-sm" id="filter-pelabuhan">
                            <option value="">Semua</option>
                            <option value="Indramayu">Indramayu</option>
                            <option value="Patimban">Patimban</option>
                            <option value="Cirebon">Cirebon</option>
                          </select>
                        </div>
                      </div>

                      <!-- Kategori (Kresiko) -->
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="filter-kategori">Kategori:</label>
                          <select class="form-control form-control-sm" id="filter-kategori">
                            <option value="">Semua</option>
                            <option value="TINGGI">Tinggi</option>
                            <option value="SEDANG">Sedang</option>
                            <option value="RENDAH">Rendah</option>
                          </select>
                        </div>
                      </div>

                      <!-- Status -->
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="filter-status">Status:</label>
                          <select class="form-control form-control-sm" id="filter-status">
                            <option value="">Semua</option>
                            <option value="ZONA KARANTINA">Zona Karantina</option>
                            <option value="SANDAR">Sandar</option>
                            <option value="pending">Belum Verifikasi</option>
                          </select>
                        </div>
                      </div>

                      <!-- Reset Button -->
                      <div class="col-md-1">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <button type="button" class="btn btn-secondary btn-sm btn-block" id="reset-filters">
                            <i class="fas fa-undo"></i> Reset
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Infografis GT dan PNBP -->
            <div class="row mb-4" id="infografis-section">
              <!-- Statistik Umum -->
              <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3 id="total-kapal-gt">0</h3>
                    <p>Total Kapal</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-ship"></i>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3 id="kapal-dalam-negeri">0</h3>
                    <p>Kapal Dalam Negeri</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-flag"></i>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3 id="kapal-luar-negeri">0</h3>
                    <p>Kapal Luar Negeri</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-globe"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- PNBP Table and Charts Section - Aligned in Same Row -->
            <div class="row mb-4">
              <!-- PNBP Table -->
              <div class="col-lg-3 col-md-6 mb-3">
                <div class="card h-100">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-table mr-2"></i>
                      Estimasi PNBP
                      <i class="fas fa-info-circle ml-2" data-toggle="tooltip" data-placement="top"
                        data-container="body" data-html="true" title="<strong>Tarif PNBP Jasa Kekarantinaan Kesehatan</strong><br/>
                                <small>Pemeriksaan Kesehatan Kapal dalam Karantina (Per Pemeriksaan Per Kapal):</small><br/>
                                • Kapal ≤ 100 GT: <strong>Rp 50.000</strong><br/>
                                • Kapal > 100 - 200 GT: <strong>Rp 60.000</strong><br/>
                                • Kapal > 200 - 350 GT: <strong>Rp 70.000</strong><br/>
                                • Kapal > 350 - 1.000 GT: <strong>Rp 85.000</strong><br/>
                                • Kapal > 1.000 - 2.000 GT: <strong>Rp 120.000</strong><br/>
                                • Kapal > 2.000 - 3.500 GT: <strong>Rp 150.000</strong><br/>
                                • Kapal > 3.500 - 7.000 GT: <strong>Rp 175.000</strong><br/>
                                • Kapal > 7.000 - 10.000 GT: <strong>Rp 200.000</strong><br/>
                                • Kapal > 10.000 - 15.000 GT: <strong>Rp 250.000</strong><br/>
                                • Kapal > 15.000 - 20.000 GT: <strong>Rp 275.000</strong><br/>
                                • Kapal > 20.000 GT: <strong>Rp 300.000</strong>"
                        style="cursor: pointer; color: #007bff;"></i>
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                      <table class="table table-bordered table-striped table-sm" id="pnbpPelabuhanTable">
                        <thead class="thead-light sticky-top">
                          <tr>
                            <th>Pelabuhan</th>
                            <th>Jumlah Kapal</th>
                            <th>PNBP</th>
                            <th>Persentase</th>
                          </tr>
                        </thead>
                        <tbody id="pnbpPelabuhanTableBody">
                          <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                        <tfoot class="bg-light font-weight-bold sticky-bottom">
                          <tr>
                            <td>TOTAL</td>
                            <td id="total-kapal-footer">0</td>
                            <td id="total-pnbp-footer">Rp 0</td>
                            <td>100%</td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kresiko Table -->
              <div class="col-lg-3 col-md-6 mb-3">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-table mr-2"></i>
                      Kresiko
                    </h3>
                  </div>
                  <div class="card-body" style="height: 230px; padding: 15px; overflow-y: auto;">
                    <table class="table table-sm table-striped" id="kresikoTable">
                      <thead>
                        <tr>
                          <th>Kresiko</th>
                          <th class="text-right">Jumlah</th>
                          <th class="text-right">Persentase</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                      </tbody>
                      <tfoot class="bg-light font-weight-bold">
                        <tr>
                          <td>TOTAL</td>
                          <td class="text-right" id="total-kresiko-footer">0</td>
                          <td class="text-right">100%</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Status Table -->
              <div class="col-lg-3 col-md-6 mb-3">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-table mr-2"></i>
                      Status
                    </h3>
                  </div>
                  <div class="card-body" style="height: 230px; padding: 15px; overflow-y: auto;">
                    <table class="table table-sm table-striped" id="statusTable">
                      <thead>
                        <tr>
                          <th>Status</th>
                          <th class="text-right">Jumlah</th>
                          <th class="text-right">Persentase</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                      </tbody>
                      <tfoot class="bg-light font-weight-bold">
                        <tr>
                          <td>TOTAL</td>
                          <td class="text-right" id="total-status-footer">0</td>
                          <td class="text-right">100%</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

            </div>

            <!-- Row Chart KPI -->
            <div class="row mb-4">
              <!-- KPI Capaian Kapal LN - Target Bulanan -->
              <div class="col-lg-4 col-md-4 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-success">
                    <h3 class="card-title text-white">
                      <i class="fas fa-calendar mr-2"></i>
                      Capaian Kapal LN (Bulanan)
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group mb-2">
                      <select id="filter-bulan" class="form-control form-control-sm">
                        <?php
                        $months = [
                          1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                          5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                          9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        foreach ($months as $num => $name) {
                          $selected = (date('n') == $num) ? 'selected' : '';
                          echo "<option value='$num' $selected>$name</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="d-flex flex-column justify-content-center mt-3" style="height: 120px;">
                      <!-- Bar SAAT INI -->
                      <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">SAAT INI</span>
                          <span id="label-bulanan-sd" class="font-weight-bold">0</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-bulanan-sd" class="progress-bar bg-primary" role="progressbar" style="width: 0%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                      <!-- Bar TARGET -->
                      <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">TARGET</span>
                          <span id="label-bulanan-target" class="font-weight-bold">10</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-bulanan-target" class="progress-bar bg-secondary" role="progressbar" style="width: 100%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center mt-3">
                      <div class="display-4 font-weight-bold mb-0" id="kpi-bulanan-percent" style="font-size: 2.5rem; color: #28a745; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">0%</div>
                    </div>
                    <div class="text-center mt-2" style="font-size:12px;">
                      Bulan <span id="label-bulan-teks"><?= date('F'); ?></span> <span id="label-tahun-bulanan"><?= date('Y'); ?></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- KPI Capaian Kapal LN - Target Triwulan -->
              <div class="col-lg-4 col-md-4 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-warning">
                    <h3 class="card-title text-white">
                      <i class="fas fa-calendar-alt mr-2"></i>
                      Capaian Kapal LN (Triwulan)
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group mb-2">
                      <select id="filter-triwulan" class="form-control form-control-sm">
                        <?php
                        $currentQuarter = ceil(date('n') / 3);
                        for ($i = 1; $i <= 4; $i++) {
                          $roman = ['', 'I', 'II', 'III', 'IV'][$i];
                          $selected = ($currentQuarter == $i) ? 'selected' : '';
                          echo "<option value='$i' $selected>Triwulan $roman</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="d-flex flex-column justify-content-center mt-3" style="height: 120px;">
                      <!-- Bar SAAT INI -->
                      <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">SAAT INI</span>
                          <span id="label-triwulan-sd" class="font-weight-bold">0</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-triwulan-sd" class="progress-bar bg-warning" role="progressbar" style="width: 0%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                      <!-- Bar TARGET -->
                      <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">TARGET</span>
                          <span id="label-triwulan-target" class="font-weight-bold">30</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-triwulan-target" class="progress-bar bg-secondary" role="progressbar" style="width: 100%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center mt-3">
                      <div class="display-4 font-weight-bold mb-0" id="kpi-triwulan-percent" style="font-size: 2.5rem; color: #ffc107; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">0%</div>
                    </div>
                    <div class="text-center mt-2" style="font-size:12px;">
                      <span id="label-triwulan"></span> <span id="label-tahun-triwulan"><?= date('Y'); ?></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- KPI Capaian Kedatangan Kapal LN (Tahunan) -->
              <div class="col-lg-4 col-md-4 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-dark">
                    <h3 class="card-title text-white">
                      <i class="fas fa-chart-pie mr-2"></i>
                      Capaian Kapal LN (Tahunan)
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group mb-2">
                      <select id="filter-tahun" class="form-control form-control-sm">
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 4; $y--) {
                          $selected = ($y == $currentYear) ? 'selected' : '';
                          echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="d-flex flex-column justify-content-center mt-3" style="height: 120px;">
                      <!-- Bar SAAT INI -->
                      <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">SAAT INI</span>
                          <span id="label-ln-sd" class="font-weight-bold">0</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-ln-sd" class="progress-bar bg-dark" role="progressbar" style="width: 0%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                      <!-- Bar TARGET -->
                      <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                          <span class="font-weight-bold">TARGET</span>
                          <span id="label-ln-target" class="font-weight-bold">120</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                          <div id="bar-ln-target" class="progress-bar bg-secondary" role="progressbar" style="width: 100%; transition: width 0.5s;"></div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center mt-3">
                      <div class="display-4 font-weight-bold mb-0" id="kpi-ln-percent" style="font-size: 2.5rem; color: #1f4b79; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">0%</div>
                    </div>
                    <div class="text-center mt-2" style="font-size:12px;">
                      Perjanjian Kinerja <span id="label-tahun"><?= date('Y'); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Include the data table directly -->
            <?= $this->include('pengawasan_kapal/laporan_data_table'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initTooltips() {
      $('[data-toggle="tooltip"]').tooltip();
    }

    // Panggil inisialisasi tooltip saat halaman dimuat
    initTooltips();

    // Initialize DataTable if it exists
    if (typeof $.fn.DataTable !== 'undefined' && $('#laporanTable').length) {
      // Initialize DataTable with proper configuration
      $('#laporanTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "dom": 'Bfrtip',
        "buttons": [
          "copy", 
          {
            extend: 'excel',
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
              format: {
                body: function ( data, row, column, node ) {
                  if (column === 10 && node) {
                    var badge = $(node).find('.badge').first();
                    if (badge.length) {
                      return badge.text().trim();
                    }
                  }
                  return data;
                }
              }
            }
          }, 
          "pdf", "print", "colvis"
        ],
        "columnDefs": [
          {
            "targets": [0], // No column (first column)
            "orderable": false,
            "searchable": false
          },
          {
            "targets": [11], // Verifikasi column
            "visible": false,
            "searchable": true
          },
          {
            "targets": [1], // Waktu Submit column
            "type": "date",
            "render": function (data, type, row) {
              if (type === 'sort' || type === 'type') {
                // Convert DD/MM/YYYY HH:mm:ss to sortable format
                if (data && data !== '-') {
                  var parts = data.split(' ');
                  if (parts.length >= 2) {
                    var datePart = parts[0].split('/');
                    var timePart = parts[1];
                    if (datePart.length === 3) {
                      // Convert DD/MM/YYYY to YYYY-MM-DD for proper sorting
                      return datePart[2] + '-' + datePart[1] + '-' + datePart[0] + ' ' + timePart;
                    }
                  }
                }
                return data;
              }
              return data;
            }
          }
        ],
        "order": [[1, 'desc']], // Order by Waktu Submit descending (newest first)
        "stateSave": false, // Disable state saving to ensure fresh sort on page load
        "serverSide": false, // Ensure client-side sorting
        "drawCallback": function () {
          initTooltips();
          updateAllKpi();
        }
      }).buttons().container().appendTo('#laporanTable_wrapper .col-md-6:eq(0)');
      updateAllKpi();
      watchKpiSourceChanges();
    }
  });

  function exportToExcel() {
    // Trigger DataTables excel button
    $('.buttons-excel').click();
  }

  function exportToPdf() {
    var range = document.getElementById('filter-date-range')?.value || '';
    var url = '/pengawasan_kapal/laporan/pdf' + (range ? ('?dateRange=' + encodeURIComponent(range)) : '');
    window.open(url, '_blank');
  }

  // Print styles
  document.addEventListener('DOMContentLoaded', function () {
    // Add print styles
    var style = document.createElement('style');
    style.textContent = `
    @media print {
      .card-tools { display: none !important; }
      .btn { display: none !important; }
      .sidebar { display: none !important; }
      .main-header { display: none !important; }
      .main-footer { display: none !important; }
      .content-wrapper { margin: 0 !important; }
      .card { border: none !important; box-shadow: none !important; }
      .table { font-size: 12px !important; }
      .table th, .table td { padding: 4px !important; }
      .dataTables_wrapper .dataTables_paginate,
      .dataTables_wrapper .dataTables_info,
      .dataTables_wrapper .dataTables_filter,
      .dataTables_wrapper .dataTables_length,
      .dt-buttons { display: none !important; }
    }
  `;
    document.head.appendChild(style);
  });

  // Helper: Parse date from raw data
  function parseDate(row) {
    // Priority: date_updated -> created_at -> date_created -> created_date
    var dateStr = row.date_updated || row.created_at || row.date_created || row.created_date;
    if (!dateStr) return null;
    // Replace / with - if necessary (though usually YYYY-MM-DD in JSON)
    // If it's DD/MM/YYYY, we need to convert
    // Assuming standard SQL datetime YYYY-MM-DD HH:mm:ss or similar from PHP json_encode
    // Let's handle both
    var d = new Date(dateStr);
    if (isNaN(d.getTime())) {
      // Try DD/MM/YYYY format if parsing fails
      var parts = dateStr.split(' ')[0].split('/');
      if (parts.length === 3) {
         d = new Date(parts[2], parts[1]-1, parts[0]);
      }
    }
    return isNaN(d.getTime()) ? null : d;
  }

  // Helper: Check if Luar Negeri
  function isLuarNegeri(row) {
    return row.surat_pernyataan_nahkoda && row.surat_pernyataan_nahkoda.trim() !== '';
  }

  // KPI Bulanan LN (Target: 10)
  function updateKpiBulanan() {
    var target = 10;
    var selectedMonth = parseInt($('#filter-bulan').val());
    var selectedYear = parseInt($('#filter-tahun').val());
    var totalKapal = 0;

    if (rawData && rawData.length > 0) {
      totalKapal = rawData.filter(function(row) {
        if (!isLuarNegeri(row)) return false;
        var d = parseDate(row);
        if (!d) return false;
        return d.getFullYear() === selectedYear && (d.getMonth() + 1) === selectedMonth;
      }).length;
    }

    // Update Table Info
    // document.getElementById('kpi-bulanan-sd').textContent = totalKapal;
    // document.getElementById('kpi-bulanan-target').textContent = target;
    
    // Update Percentage
    var percent = target > 0 ? Math.round((totalKapal / target) * 100) : 0;
    
    // Animate counter
    var $el = $('#kpi-bulanan-percent');
    var currentVal = parseInt($el.text()) || 0;
    $({ Counter: currentVal }).animate({ Counter: percent }, {
      duration: 1000,
      easing: 'swing',
      step: function () {
        $el.text(Math.ceil(this.Counter) + '%');
      },
      complete: function() {
        $el.text(percent + '%');
      }
    });
    
    // Update Horizontal Bar Chart
    document.getElementById('label-bulanan-sd').textContent = totalKapal;
    document.getElementById('label-bulanan-target').textContent = target;
    
    // Calculate widths relative to the larger value
    var maxValue = Math.max(totalKapal, target);
    var sdWidth = 0;
    var targetWidth = 0;
    
    if (maxValue > 0) {
        sdWidth = (totalKapal / maxValue) * 100;
        targetWidth = (target / maxValue) * 100;
    }
    
    // Use style.width for horizontal bars
    $('#bar-bulanan-sd').css('width', sdWidth + '%');
    $('#bar-bulanan-target').css('width', targetWidth + '%');
    
    // Update label tahun di card bulanan
    $('#label-tahun-bulanan').text(selectedYear);
    // Update label bulan teks
    var months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $('#label-bulan-teks').text(months[selectedMonth]);
  }

  // KPI Triwulan LN (Target: 30)
  function updateKpiTriwulan() {
    var target = 30;
    var selectedQuarter = parseInt($('#filter-triwulan').val());
    var selectedYear = parseInt($('#filter-tahun').val());
    var totalKapal = 0;

    if (rawData && rawData.length > 0) {
      totalKapal = rawData.filter(function(row) {
        if (!isLuarNegeri(row)) return false;
        var d = parseDate(row);
        if (!d) return false;
        var q = Math.ceil((d.getMonth() + 1) / 3);
        return d.getFullYear() === selectedYear && q === selectedQuarter;
      }).length;
    }

    // Update Table Info
    // document.getElementById('kpi-triwulan-sd').textContent = totalKapal;
    // document.getElementById('kpi-triwulan-target').textContent = target;
    
    // Update Percentage
    var percent = target > 0 ? Math.round((totalKapal / target) * 100) : 0;
    
    // Animate counter
    var $el = $('#kpi-triwulan-percent');
    var currentVal = parseInt($el.text()) || 0;
    $({ Counter: currentVal }).animate({ Counter: percent }, {
      duration: 1000,
      easing: 'swing',
      step: function () {
        $el.text(Math.ceil(this.Counter) + '%');
      },
      complete: function() {
        $el.text(percent + '%');
      }
    });
    
    // Update Horizontal Bar Chart
    document.getElementById('label-triwulan-sd').textContent = totalKapal;
    document.getElementById('label-triwulan-target').textContent = target;
    
    // Calculate widths relative to the larger value
    var maxValue = Math.max(totalKapal, target);
    var sdWidth = 0;
    var targetWidth = 0;
    
    if (maxValue > 0) {
        sdWidth = (totalKapal / maxValue) * 100;
        targetWidth = (target / maxValue) * 100;
    }
    
    $('#bar-triwulan-sd').css('width', sdWidth + '%');
    $('#bar-triwulan-target').css('width', targetWidth + '%');
    
    // Update label tahun di card triwulan
    $('#label-tahun-triwulan').text(selectedYear);
    // Update label triwulan teks
    var triwulanText = "Triwulan " + ["", "I", "II", "III", "IV"][selectedQuarter];
    $('#label-triwulan').text(triwulanText);
  }
  
  // KPI Tahunan LN (Target: 120) - Uses Filter Year
  function updateKpiLN() {
    var target = 120;
    var selectedYear = parseInt($('#filter-tahun').val());
    var totalKapal = 0;

    if (rawData && rawData.length > 0) {
      totalKapal = rawData.filter(function(row) {
        if (!isLuarNegeri(row)) return false;
        var d = parseDate(row);
        if (!d) return false;
        return d.getFullYear() === selectedYear;
      }).length;
    }

    // Update Table Info
    // document.getElementById('kpi-ln-sd').textContent = totalKapal;
    // document.getElementById('kpi-ln-target').textContent = target;
    
    // Update Percentage
    var percent = target > 0 ? Math.round((totalKapal / target) * 100) : 0;
    
    // Animate counter
    var $el = $('#kpi-ln-percent');
    var currentVal = parseInt($el.text()) || 0;
    $({ Counter: currentVal }).animate({ Counter: percent }, {
      duration: 1000,
      easing: 'swing',
      step: function () {
        $el.text(Math.ceil(this.Counter) + '%');
      },
      complete: function() {
        $el.text(percent + '%');
      }
    });
    
    // Update Horizontal Bar Chart
    document.getElementById('label-ln-sd').textContent = totalKapal;
    document.getElementById('label-ln-target').textContent = target;
    
    // Calculate widths relative to the larger value
    var maxValue = Math.max(totalKapal, target);
    var sdWidth = 0;
    var targetWidth = 0;
    
    if (maxValue > 0) {
        sdWidth = (totalKapal / maxValue) * 100;
        targetWidth = (target / maxValue) * 100;
    }
    
    $('#bar-ln-sd').css('width', sdWidth + '%');
    $('#bar-ln-target').css('width', targetWidth + '%');
    
    // Update label tahun di card tahunan
    $('#label-tahun').text(selectedYear);
  }

  // Update semua KPI
  function updateAllKpi() {
    updateKpiLN();
    updateKpiBulanan();
    updateKpiTriwulan();
  }

  // Event Listeners for new filters
  $('#filter-bulan').change(function() {
    updateKpiBulanan();
  });

  $('#filter-triwulan').change(function() {
    updateKpiTriwulan();
  });
  
  $('#filter-tahun').change(function() {
    // Ketika tahun berubah, semua chart ikut berubah tahun referensinya
    updateAllKpi();
  });


  // Observe changes on PNBP table to refresh KPI automatically
  function watchKpiSourceChanges() {
    var bodyEl = document.getElementById('pnbpPelabuhanTableBody');
    var footerEl = document.getElementById('total-kapal-footer');
    var laporanBody = document.querySelector('#laporanTable tbody');
    if (window.MutationObserver) {
      if (bodyEl) {
        var obsBody = new MutationObserver(function () { updateAllKpi(); });
        obsBody.observe(bodyEl, { childList: true, subtree: true, characterData: true });
      }
      if (footerEl && footerEl.parentElement) {
        var obsFoot = new MutationObserver(function () { updateAllKpi(); });
        obsFoot.observe(footerEl.parentElement, { childList: true, subtree: true, characterData: true });
      }
      if (laporanBody) {
        var obsLaporan = new MutationObserver(function () { updateAllKpi(); });
        obsLaporan.observe(laporanBody, { childList: true, subtree: true });
      }
    }
    // Fallback: periodic check
    var tries = 0;
    var timer = setInterval(function () {
      tries++;
      updateAllKpi();
      var val = parseInt((document.getElementById('label-ln-sd').textContent || '').replace(/[^0-9]/g, '')) || 0;
      if (val > 0 || tries > 10) clearInterval(timer);
    }, 800);
  }
</script>

<?= $this->endSection(); ?>