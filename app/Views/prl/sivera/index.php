<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .sivera-shell {
    border: 0;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    overflow: hidden;
  }

  .sivera-shell .card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
    border-bottom: 1px solid rgba(148, 163, 184, 0.15);
    padding: 1rem 1.25rem;
  }

  .sivera-tab-title {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: .75rem;
  }

  .sivera-nav {
    gap: .5rem;
    flex-wrap: wrap;
  }

  .sivera-nav .nav-link {
    border-radius: 999px;
    padding: .55rem 1rem;
    font-weight: 600;
    color: #475569;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(148, 163, 184, 0.2);
    transition: all .2s ease;
  }

  .sivera-nav .nav-link i {
    margin-right: .4rem;
  }

  .sivera-nav .nav-link.active {
    background: linear-gradient(135deg, #198754 0%, #0f766e 100%);
    color: #fff;
    box-shadow: 0 8px 18px rgba(25, 135, 84, 0.25);
  }

  .sivera-tab-body {
    min-height: 320px;
  }

  .sivera-filter-box {
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid rgba(148, 163, 184, 0.25);
    border-radius: 0.85rem;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    transition: all 0.2s ease;
  }

  .sivera-filter-box:hover {
    border-color: rgba(59, 130, 246, 0.35);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
  }

  .sivera-filter-box label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.25rem;
  }

  .sivera-filter-box .form-control {
    border-radius: 0.5rem;
    border: 1px solid #cbd5e1;
    font-size: 0.82rem;
    color: #1e293b;
    height: calc(1.8125rem + 4px);
    padding: 0.25rem 0.6rem;
  }

  .sivera-filter-box .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.18);
  }

  .filter-active-pill {
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-3px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card sivera-shell">
          <div class="card-header p-2">
            <div class="sivera-tab-title">Dashboard dan Data Surveilans Vektor & Reservoar</div>
            <ul class="nav nav-pills sivera-nav">
              <li class="nav-item"><a class="nav-link active" href="#sivera-dashboard" data-toggle="tab"><i class="fas fa-chart-line"></i> Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="#jentik" data-toggle="tab"><i class="fas fa-water"></i> Jentik Aedes sp.</a></li>
              <li class="nav-item"><a class="nav-link" href="#lalat" data-toggle="tab"><i class="fas fa-bug"></i> Lalat</a></li>
              <li class="nav-item"><a class="nav-link" href="#kecoa" data-toggle="tab"><i class="fas fa-spider"></i> Kecoa</a></li>
              <li class="nav-item"><a class="nav-link" href="#tikus" data-toggle="tab"><i class="fas fa-mouse"></i> Tikus</a></li>
              <li class="nav-item"><a class="nav-link" href="#pinjal" data-toggle="tab"><i class="fas fa-microscope"></i> Pinjal</a></li>
              <li class="nav-item"><a class="nav-link" href="#anopheles-jentik" data-toggle="tab"><i class="fas fa-bacteria"></i> Jentik Anopheles</a></li>
              <li class="nav-item"><a class="nav-link" href="#anopheles-nyamuk" data-toggle="tab"><i class="fas fa-mosquito"></i> Nyamuk Anopheles</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content sivera-tab-body">
              <!-- Dashboard -->
              <div class="active tab-pane" id="sivera-dashboard">
                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-dashboard">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="badge badge-primary px-2 py-1 filter-active-pill filter-badge-dashboard d-none">
                            <i class="fas fa-check-circle mr-1"></i> Filter Diterapkan
                          </span>
                          <span class="text-muted ml-2 small filter-loading-dashboard d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-dashboard shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-5 col-md-6 col-sm-12 mb-2 mb-lg-0">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-dashboard-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 mb-2 mb-lg-0">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-dashboard-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-6 mb-2 mb-lg-0">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-dashboard-tgl-selesai" class="form-control">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="sivera_dashboard_wrap"></div>
              </div>

              <!-- Jentik -->
              <div class="tab-pane" id="jentik">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-water text-primary mr-1"></i> Surveilans Jentik Aedes sp.</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-jentik d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-jentik shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Jentik
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-jentik">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-jentik d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-jentik shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-jentik-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-jentik-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-jentik-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-cloud-sun text-warning mr-1"></i> Musim</label>
                          <select name="kondisi_musim" id="filter-jentik-musim" class="form-control">
                            <option value="">-- Semua Musim --</option>
                            <option value="Hujan">Hujan</option>
                            <option value="Kemarau">Kemarau</option>
                          </select>
                        </div>
                        <div class="col-lg-1 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marked-alt text-secondary mr-1"></i> Wilayah</label>
                          <select name="wilayah" id="filter-jentik-wilayah" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="Perimeter">Perimeter</option>
                            <option value="Buffer">Buffer</option>
                          </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-alt text-success mr-1"></i> Status</label>
                          <select name="status" id="filter-jentik-status" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">Memenuhi Syarat (MS)</option>
                            <option value="TMS">Tidak Memenuhi Syarat (TMS)</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_jentik"></div>
              </div>

              <!-- Lalat -->
              <div class="tab-pane" id="lalat">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-bug text-danger mr-1"></i> Surveilans Lalat</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-lalat d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-lalat shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Lalat
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-lalat">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-lalat d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-lalat shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-lalat-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-lalat-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-lalat-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-cloud-sun text-warning mr-1"></i> Musim</label>
                          <select name="musim" id="filter-lalat-musim" class="form-control">
                            <option value="">-- Semua Musim --</option>
                            <option value="Hujan">Hujan</option>
                            <option value="Kemarau">Kemarau</option>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-alt text-success mr-1"></i> Status Kepadatan</label>
                          <select name="status" id="filter-lalat-status" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">Memenuhi Syarat (MS &lt; 2)</option>
                            <option value="TMS">Tidak Memenuhi Syarat (TMS &ge; 2)</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_lalat"></div>
              </div>

              <!-- Kecoa -->
              <div class="tab-pane" id="kecoa">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-spider text-warning mr-1"></i> Surveilans Kecoa</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-kecoa d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-kecoa shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Kecoa
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-kecoa">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-kecoa d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-kecoa shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-kecoa-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-kecoa-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-kecoa-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-alt text-success mr-1"></i> Status</label>
                          <select name="status" id="filter-kecoa-status" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">Memenuhi Syarat (MS &lt; 2)</option>
                            <option value="TMS">Tidak Memenuhi Syarat (TMS &ge; 2)</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_kecoa"></div>
              </div>

              <!-- Tikus -->
              <div class="tab-pane" id="tikus">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-mouse text-secondary mr-1"></i> Surveilans Tikus</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-tikus d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-tikus shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Tikus
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-tikus">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-tikus d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-tikus shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-tikus-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-tikus-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-tikus-tgl-selesai" class="form-control">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_tikus"></div>
              </div>

              <!-- Pinjal -->
              <div class="tab-pane" id="pinjal">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-microscope text-primary mr-1"></i> Surveilans Pinjal</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-pinjal d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-pinjal shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Pinjal
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-pinjal">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-pinjal d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-pinjal shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-pinjal-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-pinjal-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-pinjal-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-alt text-info mr-1"></i> Status Indeks Umum</label>
                          <select name="status_umum" id="filter-pinjal-status-umum" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">MS (&lt; 2)</option>
                            <option value="TMS">TMS (&ge; 2)</option>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-virus text-danger mr-1"></i> Status Indeks Khusus</label>
                          <select name="status_khusus" id="filter-pinjal-status-khusus" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">MS (&lt; 1)</option>
                            <option value="TMS">TMS (&ge; 1)</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_pinjal"></div>
              </div>

              <!-- Jentik Anopheles -->
              <div class="tab-pane" id="anopheles-jentik">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-bacteria text-success mr-1"></i> Surveilans Jentik Anopheles</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-anopheles-jentik d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-anopheles-jentik shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Jentik Anopheles
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-anopheles-jentik">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-anopheles-jentik d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-anopheles-jentik shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-anopheles-jentik-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-anopheles-jentik-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-anopheles-jentik-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-shield-alt text-success mr-1"></i> Status Indeks Habitat</label>
                          <select name="status" id="filter-anopheles-jentik-status" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="MS">Memenuhi Syarat (MS &lt; 1%)</option>
                            <option value="TMS">Tidak Memenuhi Syarat (TMS &ge; 1%)</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_anopheles_jentik"></div>
              </div>

              <!-- Nyamuk Anopheles -->
              <div class="tab-pane" id="anopheles-nyamuk">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="font-weight-bold text-dark"><i class="fas fa-mosquito text-danger mr-1"></i> Surveilans Nyamuk Anopheles</span>
                    <span class="badge badge-primary px-2 py-1 ml-2 filter-active-pill filter-badge-anopheles-nyamuk d-none"><i class="fas fa-check-circle mr-1"></i> Filter Diterapkan</span>
                  </div>
                  <button type="button" class="btn bg-gradient-success btn-sm btn-tambah-anopheles-nyamuk shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Nyamuk Anopheles
                  </button>
                </div>

                <div class="card sivera-filter-box mb-3">
                  <div class="card-body p-3">
                    <form id="form-filter-anopheles-nyamuk">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                          <span class="font-weight-bold text-dark small mr-2">
                            <i class="fas fa-bolt text-warning mr-1"></i> Filter Aktif Otomatis
                          </span>
                          <span class="text-muted ml-2 small filter-loading-anopheles-nyamuk d-none">
                            <i class="fas fa-spinner fa-spin text-primary mr-1"></i> Memuat...
                          </span>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-outline-danger btn-reset-anopheles-nyamuk shadow-sm py-1 px-2" title="Reset filter ke kondisi awal">
                            <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-end">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                          <select name="id_wilayah_kerja" id="filter-anopheles-nyamuk-wilker" class="form-control">
                            <option value="">-- Semua Wilayah Kerja --</option>
                            <?php foreach($wilayah_kerja as $wk): ?>
                            <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Dari Tanggal</label>
                          <input type="date" name="tanggal_mulai" id="filter-anopheles-nyamuk-tgl-mulai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="far fa-calendar-alt text-info mr-1"></i> Sampai Tanggal</label>
                          <input type="date" name="tanggal_selesai" id="filter-anopheles-nyamuk-tgl-selesai" class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                          <label><i class="fas fa-search text-warning mr-1"></i> Temuan Anopheles</label>
                          <select name="ada_anopheles" id="filter-anopheles-nyamuk-temuan" class="form-control">
                            <option value="">-- Semua Temuan --</option>
                            <option value="Ya">Ditemukan (Positif Anopheles)</option>
                            <option value="Tidak">Tidak Ditemukan</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="sivera_daftar_anopheles_nyamuk"></div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Modals will be loaded here or you can load them via ajax -->
<div id="modal-container"></div>

<?= $this->endSection(); ?>
