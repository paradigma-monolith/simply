<?= $this->extend('layouts/adminLTE-3.2.0') ?>
<?= $this->section('content') ?>

<?php
$aircraftRows = $aircrafts ?? [];
$sanitasiRows = $sanitasis ?? [];
$alatRows     = $alats ?? [];
$obatRows     = $obats ?? [];
?>

<style>
  .tc-portal-bar {
    background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
    border-radius: 14px;
    padding: 14px 18px;
    color: #ffffff;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
    box-shadow: 0 4px 14px rgba(13, 148, 136, 0.16);
  }
  .tc-portal-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .tc-portal-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: rgba(255, 255, 255, 0.16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
  }
  .tc-portal-title {
    font-size: 0.96rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.25;
  }
  .tc-portal-sub {
    font-size: 0.74rem;
    color: rgba(255, 255, 255, 0.86);
    margin: 2px 0 0;
  }
  .tc-portal-links {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
  }
  .tc-form-btn {
    border: 1px solid rgba(255, 255, 255, 0.25);
    background: rgba(255, 255, 255, 0.14);
    color: #ffffff !important;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 6px 11px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    transition: all 0.15s ease;
  }
  .tc-form-btn:hover {
    background: rgba(255, 255, 255, 0.26);
    border-color: rgba(255, 255, 255, 0.45);
  }
  .tc-form-btn-main {
    background: #ffffff;
    color: #0f766e !important;
    border-color: #ffffff;
    font-weight: 800;
  }
  .tc-form-btn-main:hover {
    background: #f0fdfa;
  }

  /* Main Card & Tabs */
  .tc-admin-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .tc-admin-head {
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .tc-tab-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    border: 0;
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .tc-tab-link {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 6px 13px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    transition: all 0.15s ease;
  }
  .tc-tab-link:hover {
    border-color: #0d9488;
    color: #0f766e;
  }
  .tc-tab-link.active {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff !important;
  }
  .tc-tab-cnt {
    background: rgba(15, 23, 42, 0.08);
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 0.69rem;
  }
  .tc-tab-link.active .tc-tab-cnt {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
  }
  .tc-search-wrap {
    position: relative;
    min-width: 230px;
    max-width: 300px;
    flex: 1 1 230px;
  }
  .tc-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.76rem;
    pointer-events: none;
  }
  .tc-search-input {
    width: 100%;
    height: 31px;
    padding: 4px 12px 4px 30px;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    font-size: 0.77rem;
    color: #1e293b;
    background: #ffffff;
    outline: none;
    transition: all 0.15s ease;
  }
  .tc-search-input:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }

  .tc-datatable thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e2e8f0;
    vertical-align: middle;
    white-space: nowrap;
  }
  .tc-datatable tbody td {
    font-size: 0.81rem;
    vertical-align: middle;
    color: #1e293b;
  }
  .tc-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 0.71rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .tc-badge-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .tc-badge-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
  .tc-badge-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
  .tc-badge-warn    { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .tc-act-group {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .tc-act-btn {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.76rem;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fas fa-check-circle mr-1"></i> <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <!-- Portal Form Publik & Quick Links -->
    <div class="tc-portal-bar">
      <div class="tc-portal-info">
        <div class="tc-portal-icon">
          <i class="fas fa-globe"></i>
        </div>
        <div>
          <h2 class="tc-portal-title">Portal Publik Travel Consent Form</h2>
          <p class="tc-portal-sub">Akses cepat ke formulir publik inspeksi pesawat, sanitasi kapal, alat kesehatan &amp; obat P3K</p>
        </div>
      </div>
      <div class="tc-portal-links">
        <a href="<?= base_url('travelconsent/home') ?>" target="_blank" class="tc-form-btn tc-form-btn-main">
          <i class="fas fa-external-link-alt"></i> Portal Form Publik
        </a>
        <a href="<?= base_url('travelconsent/public_aircraft') ?>" target="_blank" class="tc-form-btn" title="Buka Form Aircraft Sanitation">
          <i class="fas fa-plane-departure"></i> Form Aircraft
        </a>
        <a href="<?= base_url('travelconsent/public_sanitasi') ?>" target="_blank" class="tc-form-btn" title="Buka Form Sanitasi Kapal">
          <i class="fas fa-ship"></i> Form Sanitasi Kapal
        </a>
        <a href="<?= base_url('travelconsent/public_alat') ?>" target="_blank" class="tc-form-btn" title="Buka Form Alat Kesehatan P3K">
          <i class="fas fa-medkit"></i> Form Alat P3K
        </a>
        <a href="<?= base_url('travelconsent/public_obat') ?>" target="_blank" class="tc-form-btn" title="Buka Form Obat P3K">
          <i class="fas fa-pills"></i> Form Obat P3K
        </a>
      </div>
    </div>

    <!-- Main Admin Table Card -->
    <div class="tc-admin-card">
      <div class="tc-admin-head">
        <ul class="tc-tab-list nav" id="tcAdminTabs" role="tablist">
          <li class="nav-item">
            <a class="tc-tab-link active" id="aircraft-tab" data-toggle="tab" href="#aircraft" role="tab" data-table-target="#tcTableAircraft">
              <i class="fas fa-plane-departure"></i> Aircraft Sanitation
              <span class="tc-tab-cnt"><?= count($aircraftRows); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="tc-tab-link" id="sanitasi-tab" data-toggle="tab" href="#sanitasi" role="tab" data-table-target="#tcTableSanitasi">
              <i class="fas fa-ship"></i> Sanitasi Kapal
              <span class="tc-tab-cnt"><?= count($sanitasiRows); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="tc-tab-link" id="alat-tab" data-toggle="tab" href="#alat" role="tab" data-table-target="#tcTableAlat">
              <i class="fas fa-medkit"></i> Alat Kesehatan P3K
              <span class="tc-tab-cnt"><?= count($alatRows); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="tc-tab-link" id="obat-tab" data-toggle="tab" href="#obat" role="tab" data-table-target="#tcTableObat">
              <i class="fas fa-pills"></i> Obat P3K
              <span class="tc-tab-cnt"><?= count($obatRows); ?></span>
            </a>
          </li>
        </ul>

        <div class="tc-search-wrap ml-auto">
          <i class="fas fa-search tc-search-icon"></i>
          <input type="search" id="tcAdminSearch" class="tc-search-input" placeholder="Cari data pada tab aktif..." autocomplete="off">
        </div>
      </div>

      <div class="tab-content" id="myTabContent">
        <!-- Tab 1: Aircraft Sanitation -->
        <div class="tab-pane fade show active" id="aircraft" role="tabpanel">
          <div class="table-responsive">
            <table id="tcTableAircraft" class="table table-bordered table-hover tc-datatable mb-0" style="width:100%;">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Pemilik / Operator &amp; Registrasi</th>
                  <th width="14%">Tanggal &amp; Waktu</th>
                  <th width="15%" class="text-center">Kesimpulan &amp; Tindakan</th>
                  <th width="18%">Petugas Pemeriksa</th>
                  <th width="11%" class="text-center">Foto Geotag</th>
                  <th width="15%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($aircraftRows as $i => $row) : ?>
                  <?php
                  $kesimpulan = strtolower(trim((string) ($row['kesimpulan'] ?? '')));
                  $tindakan   = trim((string) ($row['tindakan'] ?? ''));
                  ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $i + 1 ?></td>
                    <td>
                      <div class="font-weight-bold text-dark"><?= esc((string) ($row['pemilik'] ?? '-')) ?></div>
                      <?php if (!empty($row['tanda_kebangsaan'])) : ?>
                        <small class="text-muted"><i class="fas fa-plane mr-1"></i>Reg: <strong><?= esc((string) $row['tanda_kebangsaan']) ?></strong></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-nowrap">
                      <div class="font-weight-bold"><?= esc((string) ($row['tanggal'] ?? '-')) ?></div>
                      <small class="text-muted"><i class="far fa-clock mr-1"></i><?= esc((string) ($row['waktu'] ?? '-')) ?></small>
                    </td>
                    <td class="text-center">
                      <?php if ($kesimpulan === 'layak') : ?>
                        <span class="tc-badge tc-badge-success"><i class="fas fa-check-circle"></i> Layak</span>
                      <?php elseif ($kesimpulan === 'tidak_layak') : ?>
                        <span class="tc-badge tc-badge-danger"><i class="fas fa-times-circle"></i> Tidak Layak</span>
                      <?php else : ?>
                        <span class="tc-badge tc-badge-warn"><?= esc((string) ($row['kesimpulan'] ?: '-')) ?></span>
                      <?php endif; ?>
                      <?php if ($tindakan !== '') : ?>
                        <div class="small text-muted mt-1"><?= esc(ucwords(str_replace('_', ' ', $tindakan))) ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="small"><strong>BKK:</strong> <?= esc((string) ($row['petugas_bkk_nama'] ?? '-')) ?></div>
                      <div class="small text-muted"><strong>Crew:</strong> <?= esc((string) ($row['petugas_kabin_nama'] ?? '-')) ?></div>
                    </td>
                    <td class="text-center">
                      <?php if (!empty($row['foto_geotagging'])) : ?>
                        <button type="button"
                                class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1"
                                onclick="viewGeotagImage('<?= esc(base_url($row['foto_geotagging']), 'js') ?>', 'Aircraft Sanitation: <?= esc((string) ($row['pemilik'] ?? ''), 'js') ?>')">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted"><i class="fas fa-minus-circle mr-1"></i>Tidak Ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="tc-act-group">
                        <a href="/travelconsent/print_aircraft/<?= (int) $row['id'] ?>" target="_blank" class="btn btn-xs btn-info tc-act-btn" title="Cetak / Pratinjau Dokumen">
                          <i class="fas fa-print"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-success tc-act-btn"
                                title="Salin Link Dokumen Publik"
                                onclick="showPublicLinkModal('<?= esc(base_url('travelconsent/doc_aircraft/' . $row['id']), 'js') ?>', 'Aircraft Sanitation: <?= esc((string) ($row['pemilik'] ?? ''), 'js') ?>')">
                          <i class="fas fa-link"></i>
                        </button>
                        <a href="/travelconsent/excel_aircraft/<?= (int) $row['id'] ?>" class="btn btn-xs btn-outline-success tc-act-btn" title="Unduh Excel">
                          <i class="fas fa-file-excel"></i>
                        </a>
                        <a href="/travelconsent/edit_aircraft/<?= (int) $row['id'] ?>" class="btn btn-xs btn-warning tc-act-btn" title="Edit Data">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-danger tc-act-btn"
                                title="Hapus Data"
                                onclick="confirmTcDelete('/travelconsent/delete_aircraft/<?= (int) $row['id'] ?>', 'Aircraft Sanitation - <?= esc((string) ($row['pemilik'] ?? ''), 'js') ?>')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 2: Sanitasi Kapal -->
        <div class="tab-pane fade" id="sanitasi" role="tabpanel">
          <div class="table-responsive">
            <table id="tcTableSanitasi" class="table table-bordered table-hover tc-datatable mb-0" style="width:100%;">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Nama Kapal &amp; IMO / Bendera</th>
                  <th>Pelabuhan</th>
                  <th width="11%">Tanggal</th>
                  <th width="14%">Jenis &amp; Sertifikat</th>
                  <th>Pemeriksa</th>
                  <th width="11%" class="text-center">Foto Geotag</th>
                  <th width="14%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sanitasiRows as $i => $row) : ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $i + 1 ?></td>
                    <td>
                      <div class="font-weight-bold text-dark"><?= esc((string) ($row['nama_kapal'] ?? '-')) ?></div>
                      <small class="text-muted">
                        IMO: <strong><?= esc((string) ($row['imo_no'] ?? '-')) ?></strong>
                        <?php if (!empty($row['bendera'])) : ?>
                          &bull; <?= esc((string) $row['bendera']) ?>
                        <?php endif; ?>
                        <?php if (!empty($row['berat_gt'])) : ?>
                          &bull; <?= number_format((float) $row['berat_gt'], 0, ',', '.') ?> GT
                        <?php endif; ?>
                      </small>
                    </td>
                    <td><?= esc((string) ($row['pelabuhan'] ?? '-')) ?></td>
                    <td class="text-nowrap font-weight-bold">
                      <?= !empty($row['tanggal']) ? esc(date('d-m-Y', strtotime($row['tanggal']))) : '-' ?>
                    </td>
                    <td>
                      <span class="tc-badge tc-badge-info"><?= esc((string) ($row['jenis_kapal'] ?? '-')) ?></span>
                      <?php if (!empty($row['sertifikat_mencatat'])) : ?>
                        <div class="small text-muted mt-1"><?= esc((string) $row['sertifikat_mencatat']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="font-weight-bold" style="font-size:0.79rem;"><?= esc((string) ($row['nama_pemeriksa'] ?? '-')) ?></div>
                      <?php if (!empty($row['pejabat_penerbit'])) : ?>
                        <small class="text-muted">Pejabat: <?= esc((string) $row['pejabat_penerbit']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php if (!empty($row['foto_geotagging'])) : ?>
                        <button type="button"
                                class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1"
                                onclick="viewGeotagImage('<?= esc(base_url($row['foto_geotagging']), 'js') ?>', 'Sanitasi Kapal: <?= esc((string) ($row['nama_kapal'] ?? ''), 'js') ?>')">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted"><i class="fas fa-minus-circle mr-1"></i>Tidak Ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="tc-act-group">
                        <a href="/travelconsent/print_sanitasi/<?= (int) $row['id'] ?>" target="_blank" class="btn btn-xs btn-info tc-act-btn" title="Cetak / Pratinjau Sertifikat">
                          <i class="fas fa-print"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-success tc-act-btn"
                                title="Salin Link Dokumen Publik"
                                onclick="showPublicLinkModal('<?= esc(base_url('travelconsent/doc_sanitasi/' . $row['id']), 'js') ?>', 'Sanitasi Kapal: <?= esc((string) ($row['nama_kapal'] ?? ''), 'js') ?>')">
                          <i class="fas fa-link"></i>
                        </button>
                        <a href="/travelconsent/edit_sanitasi/<?= (int) $row['id'] ?>" class="btn btn-xs btn-warning tc-act-btn" title="Edit Data">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-danger tc-act-btn"
                                title="Hapus Data"
                                onclick="confirmTcDelete('/travelconsent/delete_sanitasi/<?= (int) $row['id'] ?>', 'Sanitasi Kapal - <?= esc((string) ($row['nama_kapal'] ?? ''), 'js') ?>')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 3: Alat Kesehatan P3K -->
        <div class="tab-pane fade" id="alat" role="tabpanel">
          <div class="table-responsive">
            <table id="tcTableAlat" class="table table-bordered table-hover tc-datatable mb-0" style="width:100%;">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Penerbangan Haji</th>
                  <th>Embarkasi / Debarkasi</th>
                  <th width="14%" class="text-center">Nomor Kloter</th>
                  <th>Crew &amp; Pelapor</th>
                  <th width="11%" class="text-center">Foto Geotag</th>
                  <th width="15%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($alatRows as $i => $row) : ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $i + 1 ?></td>
                    <td class="font-weight-bold">
                      <?= esc((string) ($row['penerbangan_haji_h'] ?? '-')) ?> H / <?= esc((string) ($row['penerbangan_haji_m'] ?? '-')) ?> M
                    </td>
                    <td>
                      <div class="font-weight-bold text-dark"><?= esc((string) ($row['embarkasi'] ?? '-')) ?></div>
                      <?php if (!empty($row['no_kontak'])) : ?>
                        <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= esc((string) $row['no_kontak']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <span class="tc-badge tc-badge-info">Kloter <?= esc((string) ($row['nomor_kloter'] ?? '-')) ?></span>
                    </td>
                    <td>
                      <div class="small"><strong>Crew:</strong> <?= esc((string) ($row['crew_nama'] ?? '-')) ?></div>
                      <?php if (!empty($row['pelapor_nama'])) : ?>
                        <div class="small text-muted"><strong>Pelapor:</strong> <?= esc((string) $row['pelapor_nama']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php if (!empty($row['foto_geotagging'])) : ?>
                        <button type="button"
                                class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1"
                                onclick="viewGeotagImage('<?= esc(base_url($row['foto_geotagging']), 'js') ?>', 'Alat Kesehatan: Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted"><i class="fas fa-minus-circle mr-1"></i>Tidak Ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="tc-act-group">
                        <a href="/travelconsent/print_alat/<?= (int) $row['id'] ?>" target="_blank" class="btn btn-xs btn-info tc-act-btn" title="Cetak / Pratinjau Dokumen">
                          <i class="fas fa-print"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-success tc-act-btn"
                                title="Salin Link Dokumen Publik"
                                onclick="showPublicLinkModal('<?= esc(base_url('travelconsent/doc_alat/' . $row['id']), 'js') ?>', 'Alat Kesehatan: Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-link"></i>
                        </button>
                        <a href="/travelconsent/excel_alat/<?= (int) $row['id'] ?>" class="btn btn-xs btn-outline-success tc-act-btn" title="Unduh Excel">
                          <i class="fas fa-file-excel"></i>
                        </a>
                        <a href="/travelconsent/edit_alat/<?= (int) $row['id'] ?>" class="btn btn-xs btn-warning tc-act-btn" title="Edit Data">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-danger tc-act-btn"
                                title="Hapus Data"
                                onclick="confirmTcDelete('/travelconsent/delete_alat/<?= (int) $row['id'] ?>', 'Alat Kesehatan Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 4: Obat P3K -->
        <div class="tab-pane fade" id="obat" role="tabpanel">
          <div class="table-responsive">
            <table id="tcTableObat" class="table table-bordered table-hover tc-datatable mb-0" style="width:100%;">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>Penerbangan Haji</th>
                  <th>Embarkasi / Debarkasi</th>
                  <th width="14%" class="text-center">Nomor Kloter</th>
                  <th>Crew &amp; Pelapor</th>
                  <th width="11%" class="text-center">Foto Geotag</th>
                  <th width="15%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($obatRows as $i => $row) : ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $i + 1 ?></td>
                    <td class="font-weight-bold">
                      <?= esc((string) ($row['penerbangan_haji_h'] ?? '-')) ?> H / <?= esc((string) ($row['penerbangan_haji_m'] ?? '-')) ?> M
                    </td>
                    <td>
                      <div class="font-weight-bold text-dark"><?= esc((string) ($row['embarkasi'] ?? '-')) ?></div>
                      <?php if (!empty($row['no_kontak'])) : ?>
                        <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= esc((string) $row['no_kontak']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <span class="tc-badge tc-badge-info">Kloter <?= esc((string) ($row['nomor_kloter'] ?? '-')) ?></span>
                    </td>
                    <td>
                      <div class="small"><strong>Crew:</strong> <?= esc((string) ($row['crew_nama'] ?? '-')) ?></div>
                      <?php if (!empty($row['pelapor_nama'])) : ?>
                        <div class="small text-muted"><strong>Pelapor:</strong> <?= esc((string) $row['pelapor_nama']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php if (!empty($row['foto_geotagging'])) : ?>
                        <button type="button"
                                class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1"
                                onclick="viewGeotagImage('<?= esc(base_url($row['foto_geotagging']), 'js') ?>', 'Obat P3K: Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted"><i class="fas fa-minus-circle mr-1"></i>Tidak Ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="tc-act-group">
                        <a href="/travelconsent/print_obat/<?= (int) $row['id'] ?>" target="_blank" class="btn btn-xs btn-info tc-act-btn" title="Cetak / Pratinjau Dokumen">
                          <i class="fas fa-print"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-success tc-act-btn"
                                title="Salin Link Dokumen Publik"
                                onclick="showPublicLinkModal('<?= esc(base_url('travelconsent/doc_obat/' . $row['id']), 'js') ?>', 'Obat P3K: Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-link"></i>
                        </button>
                        <a href="/travelconsent/excel_obat/<?= (int) $row['id'] ?>" class="btn btn-xs btn-outline-success tc-act-btn" title="Unduh Excel">
                          <i class="fas fa-file-excel"></i>
                        </a>
                        <a href="/travelconsent/edit_obat/<?= (int) $row['id'] ?>" class="btn btn-xs btn-warning tc-act-btn" title="Edit Data">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-xs btn-danger tc-act-btn"
                                title="Hapus Data"
                                onclick="confirmTcDelete('/travelconsent/delete_obat/<?= (int) $row['id'] ?>', 'Obat P3K Kloter <?= esc((string) ($row['nomor_kloter'] ?? ''), 'js') ?>')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Preview Foto Geotagging -->
  <div class="modal fade" id="modalGeotagImage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGeotagTitle"><i class="fas fa-camera mr-2"></i>Foto Geotagging</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center p-3 bg-light">
          <img id="modalGeotagImg" src="" alt="Foto Geotagging" class="img-fluid rounded shadow" style="max-height: 70vh;">
        </div>
        <div class="modal-footer justify-content-between">
          <a href="" id="modalGeotagDownload" target="_blank" class="btn btn-primary btn-sm">
            <i class="fas fa-external-link-alt mr-1"></i> Buka Ukuran Penuh
          </a>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Popup Link Publik Dokumen -->
  <div class="modal fade" id="modalPublicLink" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title font-weight-bold" id="modalPublicLinkTitle"><i class="fas fa-link mr-2"></i>Link Publik Dokumen</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4 text-center">
          <div class="mb-3 text-success">
            <i class="fas fa-file-alt fa-3x"></i>
          </div>
          <p class="text-muted small mb-3">Salin link publik di bawah ini untuk melihat atau membagikan dokumen:</p>
          <div class="input-group">
            <input type="text" id="modalPublicLinkInput" class="form-control bg-light text-dark font-weight-bold" value="" readonly>
            <div class="input-group-append">
              <button type="button" class="btn btn-dark font-weight-bold" id="btnCopyModalPublicLink" onclick="copyModalPublicLink(this)">
                <i class="fas fa-copy mr-1"></i> Copy Link
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between bg-light">
          <a href="" id="modalPublicLinkOpen" target="_blank" class="btn btn-primary font-weight-bold">
            <i class="fas fa-external-link-alt mr-1"></i> Lihat Dokumen
          </a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function showPublicLinkModal(url, title) {
    document.getElementById('modalPublicLinkInput').value = url;
    document.getElementById('modalPublicLinkOpen').href = url;
    if (title) {
      document.getElementById('modalPublicLinkTitle').innerHTML = '<i class="fas fa-link mr-2"></i>Link Publik - ' + title;
    }
    document.getElementById('btnCopyModalPublicLink').innerHTML = '<i class="fas fa-copy mr-1"></i> Copy Link';
    $('#modalPublicLink').modal('show');
  }

  function copyModalPublicLink(btn) {
    var input = document.getElementById('modalPublicLinkInput');
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(input.value);
    } else {
      document.execCommand('copy');
    }
    var originalHtml = '<i class="fas fa-copy mr-1"></i> Copy Link';
    btn.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin!';
    setTimeout(function() {
      btn.innerHTML = originalHtml;
    }, 2000);
  }

  function viewGeotagImage(url, title) {
    document.getElementById('modalGeotagImg').src = url;
    document.getElementById('modalGeotagDownload').href = url;
    if (title) {
      document.getElementById('modalGeotagTitle').innerHTML = '<i class="fas fa-camera mr-2"></i>Foto Geotagging - ' + title;
    }
    $('#modalGeotagImage').modal('show');
  }

  function confirmTcDelete(deleteUrl, label) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Dokumen?',
        html: 'Anda yakin ingin menghapus <b>' + label + '</b> secara permanen?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then(function(result) {
        if (result.isConfirmed) {
          window.location.href = deleteUrl;
        }
      });
    } else if (confirm('Yakin hapus ' + label + '?')) {
      window.location.href = deleteUrl;
    }
  }

  $(function() {
    if (typeof $.fn.DataTable === 'undefined') return;

    var dtInstances = {};
    var dtConfig = {
      dom: "t<'row mt-2 px-3 pb-2 align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      paging: true,
      pageLength: 20,
      lengthChange: false,
      searching: true,
      autoWidth: false,
      deferRender: true,
      order: [],
      language: {
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ dokumen',
        infoEmpty: 'Belum ada dokumen terekam',
        infoFiltered: '(disaring dari _MAX_ total dokumen)',
        zeroRecords: 'Tidak ada dokumen yang cocok',
        paginate: { previous: '&laquo;', next: '&raquo;' }
      }
    };

    ['#tcTableAircraft', '#tcTableSanitasi', '#tcTableAlat', '#tcTableObat'].forEach(function(sel) {
      if ($(sel).length) {
        dtInstances[sel] = $(sel).DataTable(dtConfig);
      }
    });

    $('#tcAdminSearch').on('input keyup search', function() {
      var q = this.value;
      Object.keys(dtInstances).forEach(function(sel) {
        dtInstances[sel].search(q).draw();
      });
    });

    $('#tcAdminTabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
      var targetSel = $(this).attr('data-table-target');
      if (targetSel && dtInstances[targetSel]) {
        dtInstances[targetSel].columns.adjust();
      }
    });
  });
</script>

<?= $this->endSection() ?>
