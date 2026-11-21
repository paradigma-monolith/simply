<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .dash-modern .dm-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
  }
  .dash-modern .dm-filter-panel {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.07);
    border-radius: 12px;
    padding: 16px 16px 4px;
    margin: 10px 0 0;
  }
  .dash-modern .dm-welcome-banner {
    background: linear-gradient(135deg, #0b7285 0%, #0d9488 55%, #14b8a6 100%);
    color: #ffffff;
    border-radius: 14px;
    padding: 22px 26px;
    margin-bottom: 18px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }
  .dash-modern .dm-quick-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    padding: 18px;
    height: 100%;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    display: flex;
    align-items: flex-start;
    gap: 14px;
  }
  .dash-modern .dm-quick-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    border-color: rgba(13, 148, 136, 0.45);
  }
  .dash-modern .dm-quick-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
  }
  .dash-modern .dm-skel-wrap {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    padding: 24px;
  }
  .dash-modern .dm-skel {
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
    background-size: 200% 100%;
    animation: dmShimmer 1.2s ease-in-out infinite;
    border-radius: 8px;
  }
  @keyframes dmShimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
</style>

<!-- Main content -->
<section class="content dash-modern">
  <div class="container-fluid">
    <div class="card dm-card mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="form-group col-lg-4 col-md-5 col-12 mb-0">
            <label class="has-float-label mb-0">
              <div>
                <select id="data-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Pilih Modul Dashboard-- </option>
                  <option value="infografis"> Infografis </option>
                  <option value="cuti"> Sicute </option>
                  <option value="sinadi"> Sinadi </option>
                  <option value="relaxtime"> Relaxtime </option>
                  <option value="sipepes"> Sipepes </option>
                  <option value="sipedit"> Sipedit </option>
                </select>
              </div>
              <span>Modul Dashboard</span>
            </label>
          </div>
          <div class="col-lg-8 col-md-7 col-12 mt-2 mt-md-0 text-md-right text-muted small">
            <i class="fas fa-chart-line text-teal mr-1" style="color: #0d9488;"></i>
            Pilih modul di samping atau klik salah satu kartu analitik di bawah untuk memuat data secara langsung.
          </div>
        </div>

        <div id="infografis-filter" class="dashboard-filter row dm-filter-panel">
          <div class="form-group select-dashboard-infografis col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select id="wilkerInfografis-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value="all"> --Semua Wilayah Kerja-- </option>
                  <?php foreach ($unitKerja as $data) : ?>
                    <option value="<?= esc($data['unit_kerja'], 'attr'); ?>"><?= esc($data['unit_kerja']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <span>Wilayah Kerja</span>
            </label>
          </div>
          <div class="form-group select-dashboard-infografis col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select id="jenisInfografis-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value="all"> --Semua Infografis-- </option>
                  <option value="kapal"> Infografis Kapal </option>
                  <option value="pesawat"> Infografis Pesawat </option>
                  <option value="dokumen"> Infografis Dokumen </option>
                  <option value="vaksin"> Infografis Vaksin </option>
                </select>
              </div>
              <span>Jenis Infografis</span>
            </label>
          </div>
          <div class="col-lg-6 col-md-4 col-12 mb-3">
            <form id="infografisForm" action="" method="post">
              <div class="d-flex align-items-center" style="gap: 8px;">
                <div class="flex-grow-1 select-dashboard-infografis">
                  <label class="has-float-label mb-0">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input id="periodeInfografis-dashboard" type="text" name="tanggal-infografis" class="form-control float-right date-dashboard-infografis date-dashboard">
                    </div>
                    <span>Periode</span>
                  </label>
                </div>
                <button type="button" class="btn btn-outline-secondary" title="Informasi lokasi Laporan Infografis" onclick="showNotificationWhereInfografisLaporan()">
                  <i class="fas fa-question-circle"></i>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div id="cuti-filter" class="dashboard-filter row dm-filter-panel">
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Semua Pengguna-- </option>
                </select>
              </div>
              <span>Pengguna</span>
            </label>
          </div>
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Data Cuti/Perizinan-- </option>
                </select>
              </div>
              <span>Data</span>
            </label>
          </div>
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Semua Kategori-- </option>
                </select>
              </div>
              <span>Jenis Cuti/Perizinan</span>
            </label>
          </div>
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" name="tanggal" class="form-control float-right date-dashboard-cuti date-dashboard">
              </div>
              <span>Periode</span>
            </label>
          </div>
        </div>

        <form action="/dashboard/sipedit/export" method="post">
          <div id="sipedit-filter" class="dashboard-filter row dm-filter-panel align-items-center">
            <div class="form-group select-dashboard-infografis col-md-4 col-6">
              <label class="has-float-label">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <select id="periodeSipedit-dashboard" name="sipedit_tanggal" class="form-control select2 float-right" style="width:80%">
                    <option value="all">--Semua Periode--</option>
                    <?php for ($i = (int) date('Y'); $i > 2021; $i--) : ?>
                      <option value="<?= $i; ?>" <?= (int) date('Y') === $i ? 'selected' : ''; ?>><?= $i; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <span>Periode</span>
              </label>
            </div>
            <div class="form-group select-dashboard-infografis col-md-4 col-6">
              <label class="has-float-label">
                <div class="input-group">
                  <select id="bulanSipedit-dashboard" name="sipedit_bulan" class="form-control select2 float-right" style="width:100%">
                    <?php
                    $namaBulanList = [
                      1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                    $bulanNow = (int) date('n');
                    foreach ($namaBulanList as $numBulan => $labelBulan) :
                    ?>
                      <option value="<?= $numBulan; ?>" <?= $numBulan === $bulanNow ? 'selected' : ''; ?>><?= $labelBulan; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <span>Sampai Bulan</span>
              </label>
            </div>
            <div class="col-md-4 col-12 mb-3 text-md-right">
              <button type="submit" class="btn btn-success" style="border-radius: 9px;">
                <i class="fas fa-file-excel mr-1"></i> Export ke Excel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="content dash-modern dashboard pb-4">
  <div class="container-fluid">
    <div class="dm-welcome-banner">
      <div>
        <h4 class="font-weight-bold mb-1">Pusat Analitik &amp; Monitoring SIMPLY</h4>
        <p class="mb-0" style="opacity: 0.92; font-size: 0.9rem;">
          Pantau kinerja operasional, realisasi anggaran, pelatihan pegawai, dan dokumen SOP secara visual dan terstruktur.
        </p>
      </div>
      <a href="/home" class="btn btn-light btn-sm font-weight-bold px-3" style="border-radius: 9px; color: #0f766e;">
        <i class="fas fa-home mr-1"></i> Kembali ke Beranda
      </a>
    </div>

    <div class="row">
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="infografis">
          <div class="dm-quick-icon" style="background: #f0f9ff; color: #0284c7;">
            <i class="fas fa-ship"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">Infografis</h6>
            <p class="text-muted small mb-0">Statistik kedatangan &amp; keberangkatan kapal, pesawat, dokumen, serta vaksinasi.</p>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="sipedit">
          <div class="dm-quick-icon" style="background: #eef2ff; color: #4f46e5;">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">SIPEDIT (Pelatihan)</h6>
            <p class="text-muted small mb-0">Capaian 20 Jam Pelajaran (JPL) tahunan serta status pelatihan jabatan fungsional.</p>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="relaxtime">
          <div class="dm-quick-icon" style="background: #ecfdf5; color: #059669;">
            <i class="fas fa-money-check-alt"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">RELAXTIME (Anggaran)</h6>
            <p class="text-muted small mb-0">Pemantauan rencana dan realisasi anggaran realtime per target bulanan.</p>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="sinadi">
          <div class="dm-quick-icon" style="background: #f0fdfa; color: #0d9488;">
            <i class="fas fa-shuttle-van"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">SINADI (Perjalanan Dinas)</h6>
            <p class="text-muted small mb-0">Ringkasan perjalanan dinas dalam kota dan luar kota beserta status verifikasi.</p>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="sipepes">
          <div class="dm-quick-icon" style="background: #ecfeff; color: #0891b2;">
            <i class="fas fa-copy"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">SIPEPES (Dokumen SOP)</h6>
            <p class="text-muted small mb-0">Distribusi dokumen Standar Operasional Prosedur per tim kerja dan substansi.</p>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="dm-quick-card dashboard-quick-card" data-module="cuti">
          <div class="dm-quick-icon" style="background: #f5f3ff; color: #7c3aed;">
            <i class="fas fa-laptop-house"></i>
          </div>
          <div>
            <h6 class="font-weight-bold mb-1" style="color: #0f172a;">SICUTE (Cuti Pegawai)</h6>
            <p class="text-muted small mb-0">Rekapitulasi pengajuan, persetujuan, dan riwayat cuti kepegawaian.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function showNotificationWhereInfografisLaporan() {
    $('body')
      .removeClass('sidebar-collapse sidebar-closed')
      .addClass('sidebar-open');
    $("#infografis").addClass("menu-open");
    $("#infografis").find("ul").css('display', 'block');
    setTimeout(function() {
      introJs().setOptions({
        steps: [
          {
            element: document.querySelector('.laporan-infografis-menu'),
            intro: 'Laporan infografis dipindahkan menjadi menu "Laporan Infografis"',
            position: 'right'
          }
        ],
        doneLabel: 'Ok'
      }).start();
    }, 200);
  }
</script>
<?= $this->endSection(); ?>
