<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<?php
$hasDokumen = !empty($activeDokumen);
$activeDokId = $hasDokumen ? (int) $activeDokumen['id_dokumen'] : 0;
$totalPagu = (int) ($summary['total_pagu'] ?? 0);
$byAkun = $summary['by_akun_group'] ?? [];
$bySd = $summary['by_sumber_dana'] ?? [];
$totalBlokir = (int) ($summary['total_blokir'] ?? 0);
$blokirCount = (int) ($summary['blokir_count'] ?? 0);

$itemsJson = [];
foreach ($items as $it) {
    $itemsJson[] = [
        'id' => (int) $it['id_item'],
        'parent_id' => $it['parent_id'] !== null ? (int) $it['parent_id'] : null,
        'level' => (string) $it['level_type'],
        'header_depth' => (int) ($it['header_depth'] ?? 0),
        'kode' => (string) ($it['kode'] ?? ''),
        'uraian' => (string) ($it['uraian'] ?? ''),
        'lokasi' => (string) ($it['lokasi'] ?? ''),
        'kppn' => (string) ($it['kppn'] ?? ''),
        'jenis_komponen' => (string) ($it['jenis_komponen'] ?? ''),
        'volume' => $it['volume'] !== null ? (float) $it['volume'] : null,
        'satuan' => (string) ($it['satuan'] ?? ''),
        'harga_satuan' => (int) ($it['harga_satuan'] ?? 0),
        'jumlah' => (int) ($it['jumlah'] ?? 0),
        'sumber_dana' => (string) ($it['sumber_dana'] ?? ''),
        'is_blokir' => !empty($it['is_blokir']) ? 1 : 0,
        'nilai_blokir' => (int) ($it['nilai_blokir'] ?? 0),
        'catatan' => (string) ($it['catatan'] ?? ''),
    ];
}
?>

<style>
  .rkks-page {
    --rkks-navy: #0f172a;
    --rkks-teal: #0f766e;
    --rkks-border: #cbd5e1;
  }
  .rkks-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f766e 100%);
    color: #fff;
    border-radius: 14px;
    padding: 18px 22px;
    margin-bottom: 14px;
    box-shadow: 0 10px 24px -10px rgba(15, 23, 42, 0.45);
  }
  .rkks-kpi {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    height: 100%;
    border-left: 4px solid var(--accent, #0f766e);
  }
  .rkks-kpi-lbl {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
  }
  .rkks-kpi-val {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2px;
  }
  .rkks-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
  }
  .rkks-tree-wrap {
    max-height: 70vh;
    overflow: auto;
    border-top: 1px solid #e2e8f0;
  }
  .rkks-tree-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.83rem;
  }
  .rkks-tree-table thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #1e293b;
    color: #fff;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 8px 10px;
    border: 1px solid #334155;
    white-space: nowrap;
  }
  .rkks-tree-table td {
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
  }
  .rkks-row-program { background: #dbeafe; font-weight: 700; color: #1e3a8a; }
  .rkks-row-kegiatan { background: #e2e8f0; font-weight: 700; color: #0f172a; }
  .rkks-row-kro { background: #e0e7ff; font-weight: 700; color: #312e81; }
  .rkks-row-ro { background: #e0f2fe; font-weight: 700; color: #0c4a6e; }
  .rkks-row-komponen { background: #ccfbf1; font-weight: 700; color: #115e59; }
  .rkks-row-subkomponen { background: #ecfdf5; font-weight: 700; color: #065f46; }
  .rkks-row-akun { background: #fef3c7; font-weight: 700; color: #92400e; }
  .rkks-row-header_detail { background: #f8fafc; font-weight: 600; font-style: italic; color: #334155; }
  .rkks-row-detail { background: #fff; color: #1e293b; }
  .rkks-row-detail:hover { background: #f8fafc; }
  .rkks-toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border: 1px solid rgba(15, 23, 42, 0.25);
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.75);
    color: #0f172a;
    cursor: pointer;
    font-size: 0.68rem;
    margin-right: 5px;
    padding: 0;
  }
  .rkks-lvl-tag {
    display: inline-block;
    font-size: 0.66rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 4px;
    text-transform: uppercase;
    margin-right: 5px;
    background: rgba(15, 23, 42, 0.08);
  }
  .rkks-meta-chip {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(15, 23, 42, 0.14);
    border-radius: 999px;
    padding: 0 7px;
    margin-left: 6px;
    color: #334155;
  }
  .rkks-btn-ruh {
    padding: 1px 5px;
    font-size: 0.72rem;
    line-height: 1.3;
  }
  .rkks-depth-btn.active {
    background: #0f766e !important;
    color: #fff !important;
    border-color: #0f766e !important;
  }
  @keyframes rkksFlash {
    0% { box-shadow: inset 0 0 0 999px rgba(20, 184, 166, 0.35); }
    100% { box-shadow: inset 0 0 0 999px rgba(20, 184, 166, 0); }
  }
  .rkks-row-flash {
    animation: rkksFlash 1.8s ease-out;
  }
</style>

<section class="content rkks-page">
  <div class="container-fluid">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <!-- Hero & Dokumen Switcher -->
    <div class="rkks-hero">
      <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 12px;">
        <div>
          <h1 class="h4 font-weight-bold mb-1">
            <i class="fas fa-sitemap mr-2"></i>RKKS — Manajemen Kertas Kerja Satker (Gaya SAKTI)
          </h1>
          <div class="small" style="opacity: 0.9;">
            Import langsung workbook SAKTI (<code>RKK_MULTIYEAR_SATKER</code>) dan lakukan RUH Belanja (Program → Kegiatan → KRO → RO → Komponen → Subkomponen → Akun → Detail).
          </div>
        </div>
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
          <button type="button" class="btn btn-light btn-sm font-weight-bold text-dark" id="btnOpenImportRkks">
            <i class="fas fa-file-import mr-1 text-teal"></i> Import RKKS Excel
          </button>
          <button type="button" class="btn btn-outline-light btn-sm font-weight-bold" id="btnNewDokumen">
            <i class="fas fa-folder-plus mr-1"></i> Dokumen Baru
          </button>
          <?php if ($hasDokumen): ?>
            <a href="/relaxtime/rkks/export?dokumen=<?= $activeDokId ?>" class="btn btn-outline-light btn-sm font-weight-bold">
              <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!empty($dokumenList)): ?>
        <div class="mt-3 pt-2 border-top d-flex flex-wrap align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.2) !important; gap: 10px;">
          <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <span class="small font-weight-bold">Dokumen / Revisi Aktif:</span>
            <select id="selectDokumen" class="form-control form-control-sm" style="min-width: 290px;">
              <?php foreach ($dokumenList as $dok): ?>
                <option value="<?= (int) $dok['id_dokumen'] ?>" <?= (int) $dok['id_dokumen'] === $activeDokId ? 'selected' : '' ?>>
                  [TA <?= esc($dok['tahun_anggaran']) ?>] <?= esc($dok['nama_dokumen']) ?> — Rp <?= number_format((int) $dok['total_pagu'], 0, ',', '.') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($hasDokumen): ?>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <span class="small" style="opacity: 0.85;">
                <?= esc($activeDokumen['nama_satker'] ?: 'Satker') ?>
              </span>
              <button type="button" class="btn btn-xs btn-outline-danger text-white border-danger" id="btnDeleteDokumen" data-id="<?= $activeDokId ?>">
                <i class="fas fa-trash-alt mr-1"></i> Hapus Dokumen
              </button>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!$hasDokumen): ?>
      <div class="rkks-card p-5 text-center">
        <i class="fas fa-file-invoice-dollar fa-3x text-teal mb-3"></i>
        <h5 class="font-weight-bold">Belum Ada Dokumen RKKS</h5>
        <p class="text-muted mb-3">Silakan import file Excel ekspor SAKTI (<code>RKK_MULTIYEAR_SATKER</code>) atau buat dokumen RKKS baru untuk mulai merekam data belanja.</p>
        <button type="button" class="btn bg-teal text-white btn-sm font-weight-bold mr-2" id="btnEmptyImport">
          <i class="fas fa-file-import mr-1"></i> Import RKKS Excel Sekarang
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" id="btnEmptyNewDok">
          <i class="fas fa-folder-plus mr-1"></i> Buat Dokumen Kosong
        </button>
      </div>
    <?php else: ?>
      <!-- KPI Strip -->
      <div class="row mb-3">
        <div class="col-12 col-sm-6 col-lg mb-2 mb-lg-0">
          <div class="rkks-kpi" style="--accent: #0f172a;">
            <div class="rkks-kpi-lbl">Total Pagu RKKS</div>
            <div class="rkks-kpi-val" id="kpiRkksTotal">Rp <?= number_format($totalPagu, 0, ',', '.') ?></div>
            <div class="small text-muted" id="kpiRkksTotalSub"><?= (int) ($summary['counts_by_level']['ro'] ?? 0) ?> RO • <?= (int) ($summary['counts_by_level']['detail'] ?? 0) ?> Detail</div>
          </div>
        </div>
        <div class="col-6 col-lg mb-2 mb-lg-0">
          <div class="rkks-kpi" style="--accent: #2563eb;">
            <div class="rkks-kpi-lbl">Akun 51 (Pegawai)</div>
            <div class="rkks-kpi-val text-primary" id="kpiAkun51">Rp <?= number_format((int) ($byAkun['51']['pagu'] ?? 0), 0, ',', '.') ?></div>
            <div class="small text-muted" id="kpiAkun51Pct"><?= number_format((float) ($byAkun['51']['persen'] ?? 0), 1, ',', '.') ?>% pagu</div>
          </div>
        </div>
        <div class="col-6 col-lg mb-2 mb-lg-0">
          <div class="rkks-kpi" style="--accent: #0f766e;">
            <div class="rkks-kpi-lbl">Akun 52 (Barang)</div>
            <div class="rkks-kpi-val text-teal" id="kpiAkun52">Rp <?= number_format((int) ($byAkun['52']['pagu'] ?? 0), 0, ',', '.') ?></div>
            <div class="small text-muted" id="kpiAkun52Pct"><?= number_format((float) ($byAkun['52']['persen'] ?? 0), 1, ',', '.') ?>% pagu</div>
          </div>
        </div>
        <div class="col-6 col-lg mb-2 mb-lg-0">
          <div class="rkks-kpi" style="--accent: #d97706;">
            <div class="rkks-kpi-lbl">Akun 53 (Modal)</div>
            <div class="rkks-kpi-val text-warning" id="kpiAkun53">Rp <?= number_format((int) ($byAkun['53']['pagu'] ?? 0), 0, ',', '.') ?></div>
            <div class="small text-muted" id="kpiAkun53Pct"><?= number_format((float) ($byAkun['53']['persen'] ?? 0), 1, ',', '.') ?>% pagu</div>
          </div>
        </div>
        <div class="col-6 col-lg">
          <div class="rkks-kpi" style="--accent: #dc2626;">
            <div class="rkks-kpi-lbl">Sumber Dana &amp; Blokir (*)</div>
            <div class="small font-weight-bold text-dark mt-1" id="kpiSdBadges">
              <?php if (empty($bySd)): ?>
                -
              <?php else: ?>
                <?php foreach ($bySd as $sd): ?>
                  <span class="badge badge-light border mr-1"><?= esc($sd['sumber_dana']) ?>: <?= number_format((float) $sd['persen'], 1, ',', '.') ?>%</span>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="small <?= $blokirCount > 0 ? 'text-danger font-weight-bold' : 'text-muted' ?> mt-1" id="kpiBlokirInfo">
              Blokir (*): <?= $blokirCount ?> item (Rp <?= number_format($totalBlokir, 0, ',', '.') ?>)
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs: RUH Belanja (Tree) vs Rekap RO/Subkomponen -->
      <div class="rkks-card">
        <div class="px-3 pt-2 border-bottom d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
          <ul class="nav nav-tabs border-bottom-0" role="tablist">
            <li class="nav-item">
              <a class="nav-link active font-weight-bold" data-toggle="tab" href="#tabRuhBelanja" role="tab">
                <i class="fas fa-network-wired mr-1"></i> RUH Belanja (Hierarki SAKTI)
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" data-toggle="tab" href="#tabRekapRo" role="tab">
                <i class="fas fa-list-alt mr-1"></i> Rekap RO &amp; Subkomponen (<span id="rekapTabCount"><?= count($rekapSubkomponen) ?></span>)
              </a>
            </li>
          </ul>
          <button type="button" class="btn bg-teal text-white btn-sm font-weight-bold mb-1" id="btnAddProgram">
            <i class="fas fa-plus mr-1"></i> Rekam Program
          </button>
        </div>

        <div class="tab-content">
          <!-- TAB 1: RUH BELANJA -->
          <div class="tab-pane fade show active" id="tabRuhBelanja" role="tabpanel">
            <div class="p-3 bg-light border-bottom">
              <div class="row align-items-center">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                    <input type="text" class="form-control" id="rkksSearch" placeholder="Cari kode, kegiatan, akun, atau rincian detail...">
                  </div>
                </div>
                <div class="col-6 col-md-2 mb-2 mb-md-0">
                  <select class="form-control form-control-sm" id="rkksFilterAkun">
                    <option value="">Semua Akun</option>
                    <option value="51">51 - Pegawai</option>
                    <option value="52">52 - Barang</option>
                    <option value="53">53 - Modal</option>
                  </select>
                </div>
                <div class="col-6 col-md-2 mb-2 mb-md-0">
                  <select class="form-control form-control-sm" id="rkksFilterSd">
                    <option value="">Semua Sumber Dana</option>
                    <?php foreach ($bySd as $sd): ?>
                      <option value="<?= esc($sd['sumber_dana']) ?>"><?= esc($sd['sumber_dana']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12 col-md-4 d-flex flex-wrap justify-content-md-end align-items-center" style="gap: 4px;">
                  <div class="btn-group btn-group-sm" role="group" title="Kedalaman tampilan hierarki">
                    <button type="button" class="btn btn-outline-secondary rkks-depth-btn" data-depth="program">Program</button>
                    <button type="button" class="btn btn-outline-secondary rkks-depth-btn" data-depth="ro">s.d RO</button>
                    <button type="button" class="btn btn-outline-secondary rkks-depth-btn active" data-depth="subkomponen">s.d Subkomp</button>
                    <button type="button" class="btn btn-outline-secondary rkks-depth-btn" data-depth="akun">s.d Akun</button>
                    <button type="button" class="btn btn-outline-secondary rkks-depth-btn" data-depth="all">Semua Detail</button>
                  </div>
                  <button type="button" class="btn btn-outline-danger btn-sm" id="btnFilterBlokir" title="Tampilkan hanya item berblokir (*)">
                    <i class="fas fa-ban"></i> *
                  </button>
                </div>
              </div>
            </div>

            <div class="rkks-tree-wrap">
              <table class="rkks-tree-table">
                <thead>
                  <tr>
                    <th style="width: 145px;">Kode</th>
                    <th>Uraian Program / Kegiatan / Output / Komponen / Akun / Detail</th>
                    <th class="text-right" style="width: 78px;">Volume</th>
                    <th style="width: 74px;">Satuan</th>
                    <th class="text-right" style="width: 115px;">Harga Satuan</th>
                    <th class="text-right" style="width: 135px;">Jumlah (Pagu)</th>
                    <th class="text-center" style="width: 55px;">SD</th>
                    <th class="text-center" style="width: 50px;">Blokir</th>
                    <th class="text-center" style="width: 132px;">Aksi (RUH)</th>
                  </tr>
                </thead>
                <tbody id="rkksTreeBody"></tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: REKAP RO & SUBKOMPONEN -->
          <div class="tab-pane fade p-3" id="tabRekapRo" role="tabpanel">
            <div class="table-responsive">
              <table id="rekapTable" class="table table-bordered table-hover table-sm w-100" style="font-size: 0.85rem;">
                <thead class="thead-dark">
                  <tr>
                    <th class="text-center" style="width: 44px;">No</th>
                    <th style="width: 165px;">Rincian Output</th>
                    <th class="text-center" style="width: 90px;">Sub Komp</th>
                    <th>Kegiatan / Uraian</th>
                    <th class="text-center" style="width: 70px;">Akun</th>
                    <th class="text-center" style="width: 85px;">SD</th>
                    <th class="text-right" style="width: 155px;">Pagu Anggaran</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sumRekap = 0;
                  foreach ($rekapSubkomponen as $i => $rk):
                      $sumRekap += (int) $rk['pagu'];
                  ?>
                    <tr>
                      <td class="text-center"><?= $i + 1 ?></td>
                      <td class="font-weight-bold"><?= esc($rk['ro_komponen']) ?></td>
                      <td class="text-center font-weight-bold"><?= esc($rk['subkomponen_kode']) ?></td>
                      <td><?= esc($rk['kegiatan']) ?></td>
                      <td class="text-center"><span class="badge badge-info"><?= esc($rk['akun']) ?></span></td>
                      <td class="text-center"><?= esc($rk['sumber_dana'] ?: '-') ?></td>
                      <td class="text-right font-weight-bold">Rp <?= number_format((int) $rk['pagu'], 0, ',', '.') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr class="bg-light font-weight-bold">
                    <th colspan="6" class="text-right">TOTAL REKAP:</th>
                    <th class="text-right text-teal" id="rekapTotalFoot">Rp <?= number_format($sumRekap, 0, ',', '.') ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Modal Import RKKS Excel -->
<div class="modal fade" id="modalImportRkks" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-file-import mr-1"></i> Import RKKS Excel (Format SAKTI)</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formImportRkks" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="alert alert-info py-2 px-3 small">
            Mendukung langsung file ekspor RKKS SAKTI Kemenkeu (sheet <code>RKK_MULTIYEAR_SATKER</code>) lengkap dengan Program, Kegiatan, KRO, RO, Komponen, Subkomponen, Akun, Header (<code>&gt;</code>/<code>&gt;&gt;</code>), dan Rincian Detail (<code>-</code>).
          </div>
          <div class="form-group">
            <label class="font-weight-bold">File Excel RKKS (.xlsx / .xls) <span class="text-danger">*</span></label>
            <input type="file" class="form-control-file border rounded p-2 w-100" name="file_excel" accept=".xlsx,.xls" required>
          </div>
          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Tahun Anggaran</label>
              <input type="number" class="form-control form-control-sm" name="tahun_anggaran" value="<?= $hasDokumen ? (int) $activeDokumen['tahun_anggaran'] : date('Y') ?>" min="2020" max="2099" required>
            </div>
            <div class="col-md-8 form-group">
              <label class="font-weight-bold">Nama Dokumen / Revisi <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" name="nama_dokumen" value="RKKS TA <?= date('Y') ?> (Impor SAKTI)" required>
            </div>
          </div>
          <div class="form-group mb-0">
            <label class="font-weight-bold">Mode Import</label>
            <select class="form-control form-control-sm" name="mode_import">
              <option value="new_version" selected>Buat Dokumen / Revisi Baru (Simpan Riwayat Sebelumnya)</option>
              <?php if ($hasDokumen): ?>
                <option value="replace_active">Timpa Isi Dokumen Aktif (<?= esc($activeDokumen['nama_dokumen']) ?>)</option>
              <?php endif; ?>
            </select>
            <input type="hidden" name="id_dokumen_target" value="<?= $activeDokId ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold">
            <i class="fas fa-upload mr-1"></i> Proses Import SAKTI
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Buat Dokumen Kosong -->
<div class="modal fade" id="modalDokumenBaru" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-teal text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-folder-plus mr-1"></i> Buat Dokumen RKKS Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formDokumenBaru">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Tahun <span class="text-danger">*</span></label>
              <input type="number" class="form-control form-control-sm" name="tahun_anggaran" value="<?= date('Y') ?>" min="2020" max="2099" required>
            </div>
            <div class="col-md-8 form-group">
              <label class="font-weight-bold">Nama Dokumen / Revisi <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" name="nama_dokumen" placeholder="mis. DIPA Awal / Revisi 1" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mb-0">
              <label class="font-weight-bold">Kode Satker</label>
              <input type="text" class="form-control form-control-sm" name="kode_satker" placeholder="opsional">
            </div>
            <div class="col-md-8 form-group mb-0">
              <label class="font-weight-bold">Nama Satker</label>
              <input type="text" class="form-control form-control-sm" name="nama_satker" value="Balai Kekarantinaan Kesehatan Kelas I Bandung">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold">Simpan Dokumen</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal RUH Belanja (Rekam / Ubah Node RKKS) -->
<div class="modal fade" id="modalRuhItem" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title font-weight-bold" id="ruhModalTitle"><i class="fas fa-edit mr-1"></i> RUH Belanja RKKS</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formRuhItem">
        <div class="modal-body">
          <input type="hidden" name="id_dokumen" value="<?= $activeDokId ?>">
          <input type="hidden" id="ruhIdItem" value="">
          <input type="hidden" id="ruhParentId" name="parent_id" value="">

          <div class="alert alert-light border py-2 px-3 small mb-3" id="ruhParentBreadcrumb">
            <strong>Induk:</strong> Root (Program)
          </div>

          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Level Hierarki</label>
              <select class="form-control form-control-sm" id="ruhLevelType" name="level_type"></select>
            </div>
            <div class="col-md-4 form-group" id="wrapRuhKode">
              <label class="font-weight-bold">Kode SAKTI <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" id="ruhKode" name="kode" placeholder="mis. 524111 / A / 051">
            </div>
            <div class="col-md-4 form-group d-none" id="wrapRuhJenisKomp">
              <label class="font-weight-bold">Jenis Komponen</label>
              <select class="form-control form-control-sm" id="ruhJenisKomponen" name="jenis_komponen">
                <option value="U">U - Komponen Utama</option>
                <option value="P">P - Komponen Penunjang</option>
              </select>
            </div>
            <div class="col-md-4 form-group d-none" id="wrapRuhSd">
              <label class="font-weight-bold">Sumber Dana</label>
              <select class="form-control form-control-sm" id="ruhSumberDana" name="sumber_dana">
                <option value="RM">RM (Rupiah Murni)</option>
                <option value="PNP">PNP (PNBP)</option>
                <option value="BLU">BLU</option>
                <option value="PHLN">PHLN</option>
                <option value="SBSN">SBSN</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Uraian / Deskripsi <span class="text-danger">*</span></label>
            <textarea class="form-control form-control-sm" id="ruhUraian" name="uraian" rows="2" required></textarea>
          </div>

          <div class="row d-none" id="wrapRuhMeta">
            <div class="col-md-6 form-group" id="wrapRuhLokasi">
              <label class="font-weight-bold">Lokasi</label>
              <input type="text" class="form-control form-control-sm" id="ruhLokasi" name="lokasi" placeholder="mis. KOTA BANDUNG">
            </div>
            <div class="col-md-6 form-group" id="wrapRuhKppn">
              <label class="font-weight-bold">KPPN</label>
              <input type="text" class="form-control form-control-sm" id="ruhKppn" name="kppn" placeholder="mis. 022-Bandung I">
            </div>
          </div>

          <div class="row d-none" id="wrapRuhQuantities">
            <div class="col-md-3 form-group">
              <label class="font-weight-bold">Volume</label>
              <input type="number" step="0.01" class="form-control form-control-sm" id="ruhVolume" name="volume" placeholder="1">
            </div>
            <div class="col-md-3 form-group">
              <label class="font-weight-bold">Satuan</label>
              <input type="text" class="form-control form-control-sm" id="ruhSatuan" name="satuan" placeholder="OK / OH / PKT">
            </div>
            <div class="col-md-3 form-group" id="wrapRuhHarga">
              <label class="font-weight-bold">Harga Satuan (Rp)</label>
              <input type="text" class="form-control form-control-sm rkks-rp" id="ruhHargaSatuan" name="harga_satuan" value="0">
            </div>
            <div class="col-md-3 form-group" id="wrapRuhJumlah">
              <label class="font-weight-bold">Jumlah / Pagu (Rp)</label>
              <input type="text" class="form-control form-control-sm rkks-rp" id="ruhJumlah" name="jumlah" value="0">
            </div>
          </div>

          <div class="row align-items-center" id="wrapRuhBlokir">
            <div class="col-md-4 form-group mb-0">
              <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" id="ruhIsBlokir" name="is_blokir" value="1">
                <label class="custom-control-label font-weight-bold text-danger" for="ruhIsBlokir">Tandai Blokir (*)</label>
              </div>
            </div>
            <div class="col-md-4 form-group mb-0">
              <label class="font-weight-bold small">Nilai Blokir (Rp)</label>
              <input type="text" class="form-control form-control-sm rkks-rp" id="ruhNilaiBlokir" name="nilai_blokir" value="0">
            </div>
            <div class="col-md-4 form-group mb-0">
              <label class="font-weight-bold small">Catatan</label>
              <input type="text" class="form-control form-control-sm" id="ruhCatatan" name="catatan" placeholder="opsional">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold" id="btnSubmitRuh">
            <i class="fas fa-save mr-1"></i> Simpan &amp; Hitung Ulang Pagu
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ACTIVE_DOK_ID = <?= $activeDokId ?>;
  let ITEMS = <?= json_encode($itemsJson, JSON_UNESCAPED_UNICODE) ?>;
  const LEVEL_ORDER = ['program', 'kegiatan', 'kro', 'ro', 'komponen', 'subkomponen', 'akun', 'header_detail', 'detail'];
  const LEVEL_LABELS = {
    program: 'Program',
    kegiatan: 'Kegiatan',
    kro: 'KRO',
    ro: 'RO',
    komponen: 'Komponen',
    subkomponen: 'Subkomponen',
    akun: 'Akun',
    header_detail: 'Header Detail (> / >>)',
    detail: 'Detail Belanja (-)'
  };
  const ALLOWED_CHILD = {
    program: ['kegiatan'],
    kegiatan: ['kro'],
    kro: ['ro'],
    ro: ['komponen'],
    komponen: ['subkomponen'],
    subkomponen: ['akun'],
    akun: ['detail', 'header_detail'],
    header_detail: ['detail', 'header_detail'],
    detail: []
  };

  let itemById = {};
  let childrenMap = {};
  let rekapDtInstance = null;
  const collapsedIds = new Set();
  let currentDepth = 'subkomponen';
  let onlyBlokir = false;

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
  });

  function rebuildIndexes() {
    itemById = {};
    childrenMap = {};
    ITEMS.forEach(it => {
      itemById[it.id] = it;
      const pid = it.parent_id || 0;
      if (!childrenMap[pid]) childrenMap[pid] = [];
      childrenMap[pid].push(it.id);
    });
  }
  rebuildIndexes();

  function escapeHtml(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function formatRp(n) {
    const num = Math.round(Number(n) || 0);
    return (num < 0 ? '-Rp ' : 'Rp ') + Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }
  function parseRp(str) {
    const d = String(str || '').replace(/[^\d]/g, '');
    return d === '' ? 0 : parseInt(d, 10);
  }

  function applyDepthCollapse(depthLevel) {
    collapsedIds.clear();
    if (depthLevel === 'all') return;
    const maxIdx = LEVEL_ORDER.indexOf(depthLevel);
    ITEMS.forEach(it => {
      if ((childrenMap[it.id] || []).length > 0 && LEVEL_ORDER.indexOf(it.level) >= maxIdx) {
        collapsedIds.add(it.id);
      }
    });
  }

  function getAncestorIds(item) {
    const res = [];
    let pid = item.parent_id;
    let guard = 0;
    while (pid && itemById[pid] && guard < 20) {
      res.push(pid);
      pid = itemById[pid].parent_id;
      guard++;
    }
    return res;
  }

  function getAkunGroup(item) {
    if (item.level === 'akun') return item.kode.slice(0, 2);
    let pid = item.parent_id;
    let guard = 0;
    while (pid && itemById[pid] && guard < 15) {
      if (itemById[pid].level === 'akun') return itemById[pid].kode.slice(0, 2);
      pid = itemById[pid].parent_id;
      guard++;
    }
    return '';
  }

  function getInheritedSd(item) {
    if (item.sumber_dana) return item.sumber_dana;
    let pid = item.parent_id;
    let guard = 0;
    while (pid && itemById[pid] && guard < 15) {
      if (itemById[pid].sumber_dana) return itemById[pid].sumber_dana;
      pid = itemById[pid].parent_id;
      guard++;
    }
    return '';
  }

  function renderTree() {
    const tbody = document.getElementById('rkksTreeBody');
    if (!tbody) return;

    const searchQ = (document.getElementById('rkksSearch').value || '').trim().toLowerCase();
    const akunFilter = document.getElementById('rkksFilterAkun').value;
    const sdFilter = document.getElementById('rkksFilterSd').value;
    const isFiltering = Boolean(searchQ || akunFilter || sdFilter || onlyBlokir);

    const visibleIds = new Set();
    if (isFiltering) {
      ITEMS.forEach(it => {
        if (searchQ) {
          const hay = (it.kode + ' ' + it.uraian + ' ' + it.lokasi + ' ' + it.kppn).toLowerCase();
          if (!hay.includes(searchQ)) return;
        }
        if (akunFilter && getAkunGroup(it) !== akunFilter) return;
        if (sdFilter && getInheritedSd(it) !== sdFilter) return;
        if (onlyBlokir && !it.is_blokir) return;
        visibleIds.add(it.id);
        getAncestorIds(it).forEach(aid => visibleIds.add(aid));
      });
    }

    const html = ITEMS.map(it => {
      if (isFiltering) {
        if (!visibleIds.has(it.id)) return '';
      } else {
        const ancestors = getAncestorIds(it);
        if (ancestors.some(aid => collapsedIds.has(aid))) return '';
      }

      const hasKids = (childrenMap[it.id] || []).length > 0;
      const isCollapsed = !isFiltering && collapsedIds.has(it.id);
      const depthIdx = LEVEL_ORDER.indexOf(it.level) + (it.level === 'header_detail' && it.header_depth === 2 ? 1 : 0);
      const padLeft = Math.max(0, depthIdx * 14);

      const toggleBtn = hasKids
        ? '<button type="button" class="rkks-toggle-btn btn-toggle-node" data-id="' + it.id + '"><i class="fas ' + (isCollapsed ? 'fa-plus' : 'fa-minus') + '"></i></button>'
        : '<span style="display:inline-block;width:23px;"></span>';

      const lvlBadge = '<span class="rkks-lvl-tag">' + escapeHtml(LEVEL_LABELS[it.level] || it.level) + '</span>';
      let metaHtml = '';
      if (it.lokasi) metaHtml += '<span class="rkks-meta-chip"><i class="fas fa-map-marker-alt mr-1"></i>' + escapeHtml(it.lokasi) + '</span>';
      if (it.kppn) metaHtml += '<span class="rkks-meta-chip">KPPN ' + escapeHtml(it.kppn) + '</span>';
      if (it.jenis_komponen) metaHtml += '<span class="rkks-meta-chip">' + escapeHtml(it.jenis_komponen) + '</span>';

      const childOpts = ALLOWED_CHILD[it.level] || [];
      const addChildBtn = childOpts.length > 0
        ? '<button type="button" class="btn btn-outline-success rkks-btn-ruh btn-add-child" data-id="' + it.id + '" title="Rekam Anak (' + escapeHtml(LEVEL_LABELS[childOpts[0]]) + ')"><i class="fas fa-plus"></i></button> '
        : '';

      const blokirBtn = (it.level === 'detail' || it.level === 'akun')
        ? '<button type="button" class="btn ' + (it.is_blokir ? 'btn-danger' : 'btn-outline-secondary') + ' rkks-btn-ruh btn-toggle-blokir" data-id="' + it.id + '" title="Toggle Blokir (*)">*</button> '
        : '';

      return '<tr class="rkks-row-' + escapeHtml(it.level) + '" data-id="' + it.id + '">' +
        '<td style="white-space:nowrap;">' + escapeHtml(it.kode) + '</td>' +
        '<td><div style="padding-left:' + padLeft + 'px;">' + toggleBtn + lvlBadge + escapeHtml(it.uraian) + metaHtml + '</div></td>' +
        '<td class="text-right">' + (it.volume !== null ? it.volume : '') + '</td>' +
        '<td>' + escapeHtml(it.satuan) + '</td>' +
        '<td class="text-right">' + (it.harga_satuan > 0 ? formatRp(it.harga_satuan) : '') + '</td>' +
        '<td class="text-right font-weight-bold">' + formatRp(it.jumlah) + '</td>' +
        '<td class="text-center">' + escapeHtml(it.sumber_dana) + '</td>' +
        '<td class="text-center">' + (it.is_blokir ? '<span class="badge badge-danger">*</span>' : '') + '</td>' +
        '<td class="text-center text-nowrap">' +
          addChildBtn +
          '<button type="button" class="btn btn-outline-primary rkks-btn-ruh btn-edit-item" data-id="' + it.id + '" title="Ubah"><i class="fas fa-edit"></i></button> ' +
          blokirBtn +
          '<button type="button" class="btn btn-outline-danger rkks-btn-ruh btn-delete-item" data-id="' + it.id + '" title="Hapus"><i class="fas fa-trash"></i></button>' +
        '</td>' +
      '</tr>';
    }).join('');

    tbody.innerHTML = html || '<tr><td colspan="9" class="text-center text-muted py-4">Tidak ada baris RKKS yang sesuai filter.</td></tr>';
  }

  function applyLiveState(state, opts = {}) {
    if (!state) return;
    const treeWrap = document.querySelector('.rkks-tree-wrap');
    const prevTreeTop = treeWrap ? treeWrap.scrollTop : 0;
    const prevTreeLeft = treeWrap ? treeWrap.scrollLeft : 0;
    const prevWinY = window.scrollY;

    ITEMS = state.items || [];
    rebuildIndexes();

    // Pertahankan state expand/collapse dan pastikan induk item baru terbuka
    Array.from(collapsedIds).forEach(cid => {
      if (!itemById[cid]) collapsedIds.delete(cid);
    });
    if (opts.expandParentId) {
      let pId = parseInt(opts.expandParentId, 10);
      let guard = 0;
      while (pId && itemById[pId] && guard < 20) {
        collapsedIds.delete(pId);
        pId = itemById[pId].parent_id;
        guard++;
      }
    }

    // Update KPI Cards
    const sum = state.summary || {};
    const totalPagu = Number(sum.total_pagu || 0);
    const counts = sum.counts_by_level || {};
    const byAkun = sum.by_akun_group || {};
    const bySd = sum.by_sumber_dana || [];

    const kpiTotal = document.getElementById('kpiRkksTotal');
    if (kpiTotal) kpiTotal.textContent = formatRp(totalPagu);
    const kpiSub = document.getElementById('kpiRkksTotalSub');
    if (kpiSub) kpiSub.textContent = (counts.ro || 0) + ' RO • ' + (counts.detail || 0) + ' Detail';

    ['51', '52', '53'].forEach(code => {
      const ak = byAkun[code] || {};
      const valEl = document.getElementById('kpiAkun' + code);
      const pctEl = document.getElementById('kpiAkun' + code + 'Pct');
      if (valEl) valEl.textContent = formatRp(ak.pagu || 0);
      if (pctEl) pctEl.textContent = Number(ak.persen || 0).toFixed(1).replace('.', ',') + '% pagu';
    });

    const sdEl = document.getElementById('kpiSdBadges');
    if (sdEl) {
      sdEl.innerHTML = bySd.length
        ? bySd.map(sd => '<span class="badge badge-light border mr-1">' + escapeHtml(sd.sumber_dana) + ': ' + Number(sd.persen || 0).toFixed(1).replace('.', ',') + '%</span>').join('')
        : '-';
    }

    const blkEl = document.getElementById('kpiBlokirInfo');
    if (blkEl) {
      const bCnt = Number(sum.blokir_count || 0);
      const bTot = Number(sum.total_blokir || 0);
      blkEl.className = 'small ' + (bCnt > 0 ? 'text-danger font-weight-bold' : 'text-muted') + ' mt-1';
      blkEl.textContent = 'Blokir (*): ' + bCnt + ' item (' + formatRp(bTot) + ')';
    }

    // Update pagu pada dropdown dokumen aktif
    const selDokEl = document.getElementById('selectDokumen');
    if (selDokEl && selDokEl.selectedIndex >= 0) {
      const opt = selDokEl.options[selDokEl.selectedIndex];
      opt.text = opt.text.replace(/—\s*Rp\s*[\d\.]+$/, '— ' + formatRp(totalPagu));
    }

    // Update Rekap tab & table
    const rekap = state.rekap || [];
    const tabCnt = document.getElementById('rekapTabCount');
    if (tabCnt) tabCnt.textContent = rekap.length;

    const rekapEl = document.getElementById('rekapTable');
    if (rekapEl) {
      let prevSearch = '', prevPage = 0, prevLen = 25;
      if (rekapDtInstance) {
        prevSearch = rekapDtInstance.search() || '';
        prevPage = rekapDtInstance.page() || 0;
        prevLen = rekapDtInstance.page.len() || 25;
        rekapDtInstance.destroy();
        rekapDtInstance = null;
      }
      let sumRekap = 0;
      const rekapTbody = rekapEl.querySelector('tbody');
      rekapTbody.innerHTML = rekap.map((rk, i) => {
        sumRekap += Number(rk.pagu || 0);
        return '<tr>' +
          '<td class="text-center">' + (i + 1) + '</td>' +
          '<td class="font-weight-bold">' + escapeHtml(rk.ro_komponen) + '</td>' +
          '<td class="text-center font-weight-bold">' + escapeHtml(rk.subkomponen_kode) + '</td>' +
          '<td>' + escapeHtml(rk.kegiatan) + '</td>' +
          '<td class="text-center"><span class="badge badge-info">' + escapeHtml(rk.akun) + '</span></td>' +
          '<td class="text-center">' + escapeHtml(rk.sumber_dana || '-') + '</td>' +
          '<td class="text-right font-weight-bold">' + formatRp(rk.pagu || 0) + '</td>' +
        '</tr>';
      }).join('');
      const rekapFoot = document.getElementById('rekapTotalFoot');
      if (rekapFoot) rekapFoot.textContent = formatRp(sumRekap);

      if (typeof $ !== 'undefined' && $.fn.DataTable) {
        rekapDtInstance = $('#rekapTable').DataTable({ pageLength: prevLen, order: [] });
        if (prevSearch) rekapDtInstance.search(prevSearch);
        rekapDtInstance.draw(false);
        const info = rekapDtInstance.page.info();
        const safeP = Math.max(0, Math.min(prevPage, Math.max(0, info.pages - 1)));
        if (safeP > 0) rekapDtInstance.page(safeP).draw('page');
      }
    }

    // Render tree & restore exact scroll position
    renderTree();
    if (treeWrap) {
      treeWrap.scrollTop = prevTreeTop;
      treeWrap.scrollLeft = prevTreeLeft;
    }
    window.scrollTo(window.scrollX, prevWinY);

    requestAnimationFrame(() => {
      if (treeWrap) {
        treeWrap.scrollTop = prevTreeTop;
        treeWrap.scrollLeft = prevTreeLeft;
      }
      window.scrollTo(window.scrollX, prevWinY);
      if (opts.highlightId) {
        const targetTr = document.querySelector('#rkksTreeBody tr[data-id="' + opts.highlightId + '"]');
        if (targetTr) {
          targetTr.classList.remove('rkks-row-flash');
          void targetTr.offsetWidth;
          targetTr.classList.add('rkks-row-flash');
        }
      }
    });
  }

  function configureRuhModalFields(levelType) {
    const isDetail = levelType === 'detail';
    const isHeader = levelType === 'header_detail';
    const isAkun = levelType === 'akun';
    const isKomp = levelType === 'komponen';
    const isKroRo = levelType === 'kro' || levelType === 'ro';

    document.getElementById('wrapRuhJenisKomp').classList.toggle('d-none', !isKomp);
    document.getElementById('wrapRuhSd').classList.toggle('d-none', !(isAkun || isDetail));
    document.getElementById('wrapRuhMeta').classList.toggle('d-none', !(isKroRo || isAkun));
    document.getElementById('wrapRuhLokasi').classList.toggle('d-none', !isKroRo);
    document.getElementById('wrapRuhKppn').classList.toggle('d-none', !isAkun);
    document.getElementById('wrapRuhQuantities').classList.toggle('d-none', !(isDetail || isKroRo || isHeader));
    document.getElementById('wrapRuhHarga').classList.toggle('d-none', !isDetail);
    document.getElementById('wrapRuhBlokir').classList.toggle('d-none', !(isDetail || isAkun));

    const kodeEl = document.getElementById('ruhKode');
    if (isDetail) {
      kodeEl.value = '-';
      kodeEl.readOnly = true;
    } else if (isHeader) {
      if (kodeEl.value !== '>' && kodeEl.value !== '>>') kodeEl.value = '>';
      kodeEl.readOnly = false;
    } else {
      if (kodeEl.value === '-' || kodeEl.value === '>' || kodeEl.value === '>>') kodeEl.value = '';
      kodeEl.readOnly = false;
    }
  }

  // Document Switcher
  const selDok = document.getElementById('selectDokumen');
  if (selDok) {
    selDok.addEventListener('change', () => {
      window.location.href = '/relaxtime/rkks?dokumen=' + encodeURIComponent(selDok.value);
    });
  }

  // Import & Dokumen Baru modal triggers
  ['btnOpenImportRkks', 'btnEmptyImport'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', () => $('#modalImportRkks').modal('show'));
  });
  ['btnNewDokumen', 'btnEmptyNewDok'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', () => $('#modalDokumenBaru').modal('show'));
  });

  // Delete Dokumen
  const btnDelDok = document.getElementById('btnDeleteDokumen');
  if (btnDelDok) {
    btnDelDok.addEventListener('click', async () => {
      const conf = await Swal.fire({
        icon: 'warning',
        title: 'Hapus Dokumen RKKS ini?',
        text: 'Seluruh struktur Program s.d. Detail di dokumen ini akan dihapus.',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Hapus'
      });
      if (!conf.isConfirmed) return;
      const res = await fetch('/relaxtime/rkks/dokumen/delete/' + ACTIVE_DOK_ID, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (res.ok && json.success) {
        window.location.href = '/relaxtime/rkks';
      } else {
        Swal.fire('Gagal', json.message || 'Gagal menghapus dokumen.', 'error');
      }
    });
  }

  // Submit Import RKKS
  document.getElementById('formImportRkks').addEventListener('submit', async function (e) {
    e.preventDefault();
    Swal.fire({ title: 'Mengimpor RKKS SAKTI...', text: 'Membaca hierarki Program → Detail.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
      const res = await fetch('/relaxtime/rkks/import', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
      });
      const json = await res.json();
      if (!res.ok || !json.success) {
        Swal.fire('Import Gagal', json.message || 'Gagal memproses file Excel.', 'error');
        return;
      }
      $('#modalImportRkks').modal('hide');
      await Swal.fire('Berhasil!', json.message + ' Total Pagu: ' + formatRp(json.total_pagu), 'success');
      window.location.href = '/relaxtime/rkks?dokumen=' + json.id_dokumen;
    } catch (err) {
      Swal.fire('Error', 'Terjadi kesalahan saat mengupload file.', 'error');
    }
  });

  // Submit Dokumen Baru
  document.getElementById('formDokumenBaru').addEventListener('submit', async function (e) {
    e.preventDefault();
    const res = await fetch('/relaxtime/rkks/dokumen/store', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(this)
    });
    const json = await res.json();
    if (res.ok && json.success) {
      window.location.href = '/relaxtime/rkks?dokumen=' + json.id_dokumen;
    } else {
      Swal.fire('Gagal', json.message || 'Gagal membuat dokumen.', 'error');
    }
  });

  // Rupiah formatting & detail auto calc
  document.querySelectorAll('.rkks-rp').forEach(inp => {
    inp.addEventListener('input', function () {
      const d = this.value.replace(/[^\d]/g, '');
      this.value = d === '' ? '' : d.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
  });

  function syncDetailJumlahPreview() {
    if (document.getElementById('ruhLevelType').value !== 'detail') return;
    const vol = parseFloat(document.getElementById('ruhVolume').value || '0') || 0;
    const hrg = parseRp(document.getElementById('ruhHargaSatuan').value);
    const total = Math.round(vol * hrg);
    document.getElementById('ruhJumlah').value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }
  document.getElementById('ruhVolume').addEventListener('input', syncDetailJumlahPreview);
  document.getElementById('ruhHargaSatuan').addEventListener('input', syncDetailJumlahPreview);
  document.getElementById('ruhLevelType').addEventListener('change', function () {
    configureRuhModalFields(this.value);
  });

  // Rekam Program (Root)
  const btnAddProg = document.getElementById('btnAddProgram');
  if (btnAddProg) {
    btnAddProg.addEventListener('click', () => {
      document.getElementById('ruhIdItem').value = '';
      document.getElementById('ruhParentId').value = '';
      document.getElementById('ruhModalTitle').innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Rekam Program Baru';
      document.getElementById('ruhParentBreadcrumb').innerHTML = '<strong>Induk:</strong> Root Dokumen';
      document.getElementById('formRuhItem').reset();
      const sel = document.getElementById('ruhLevelType');
      sel.innerHTML = '<option value="program">Program</option>';
      sel.disabled = true;
      configureRuhModalFields('program');
      $('#modalRuhItem').modal('show');
    });
  }

  // Tree Controls & Row Actions
  const treeBody = document.getElementById('rkksTreeBody');
  if (treeBody) {
    treeBody.addEventListener('click', async function (e) {
      const toggleBtn = e.target.closest('.btn-toggle-node');
      if (toggleBtn) {
        const id = parseInt(toggleBtn.getAttribute('data-id'), 10);
        if (collapsedIds.has(id)) collapsedIds.delete(id);
        else collapsedIds.add(id);
        renderTree();
        return;
      }

      const addBtn = e.target.closest('.btn-add-child');
      if (addBtn) {
        const pid = parseInt(addBtn.getAttribute('data-id'), 10);
        const parent = itemById[pid];
        if (!parent) return;
        const allowed = ALLOWED_CHILD[parent.level] || [];
        if (!allowed.length) return;

        document.getElementById('ruhIdItem').value = '';
        document.getElementById('ruhParentId').value = String(pid);
        document.getElementById('formRuhItem').reset();
        document.getElementById('ruhModalTitle').innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Rekam Data di bawah ' + escapeHtml(parent.kode);
        document.getElementById('ruhParentBreadcrumb').innerHTML = '<strong>Induk:</strong> [' + escapeHtml(parent.kode) + '] ' + escapeHtml(parent.uraian);

        const sel = document.getElementById('ruhLevelType');
        sel.innerHTML = allowed.map(lv => '<option value="' + lv + '">' + escapeHtml(LEVEL_LABELS[lv] || lv) + '</option>').join('');
        sel.disabled = allowed.length === 1;
        configureRuhModalFields(allowed[0]);
        $('#modalRuhItem').modal('show');
        return;
      }

      const editBtn = e.target.closest('.btn-edit-item');
      if (editBtn) {
        const id = parseInt(editBtn.getAttribute('data-id'), 10);
        const it = itemById[id];
        if (!it) return;

        document.getElementById('ruhIdItem').value = String(id);
        document.getElementById('ruhParentId').value = it.parent_id ? String(it.parent_id) : '';
        document.getElementById('ruhModalTitle').innerHTML = '<i class="fas fa-edit mr-1"></i> Ubah Item RKKS';
        const parent = it.parent_id ? itemById[it.parent_id] : null;
        document.getElementById('ruhParentBreadcrumb').innerHTML = parent
          ? '<strong>Induk:</strong> [' + escapeHtml(parent.kode) + '] ' + escapeHtml(parent.uraian)
          : '<strong>Induk:</strong> Root Dokumen';

        const sel = document.getElementById('ruhLevelType');
        sel.innerHTML = '<option value="' + escapeHtml(it.level) + '">' + escapeHtml(LEVEL_LABELS[it.level] || it.level) + '</option>';
        sel.disabled = true;

        document.getElementById('ruhKode').value = it.kode;
        document.getElementById('ruhUraian').value = it.uraian;
        document.getElementById('ruhLokasi').value = it.lokasi;
        document.getElementById('ruhKppn').value = it.kppn;
        document.getElementById('ruhJenisKomponen').value = it.jenis_komponen || 'U';
        document.getElementById('ruhSumberDana').value = it.sumber_dana || 'PNP';
        document.getElementById('ruhVolume').value = it.volume !== null ? it.volume : '';
        document.getElementById('ruhSatuan').value = it.satuan;
        document.getElementById('ruhHargaSatuan').value = String(it.harga_satuan).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        document.getElementById('ruhJumlah').value = String(it.jumlah).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        document.getElementById('ruhIsBlokir').checked = Boolean(it.is_blokir);
        document.getElementById('ruhNilaiBlokir').value = String(it.nilai_blokir).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        document.getElementById('ruhCatatan').value = it.catatan || '';

        configureRuhModalFields(it.level);
        $('#modalRuhItem').modal('show');
        return;
      }

      const blkBtn = e.target.closest('.btn-toggle-blokir');
      if (blkBtn) {
        const id = parseInt(blkBtn.getAttribute('data-id'), 10);
        blkBtn.disabled = true;
        try {
          const res = await fetch('/relaxtime/rkks/item/toggle-blokir/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const json = await res.json();
          if (res.ok && json.success) {
            applyLiveState(json.state, { highlightId: id });
            Toast.fire({ icon: 'success', title: json.message });
          } else {
            Swal.fire('Gagal', json.message || 'Gagal mengubah blokir.', 'error');
          }
        } finally {
          blkBtn.disabled = false;
        }
        return;
      }

      const delBtn = e.target.closest('.btn-delete-item');
      if (delBtn) {
        const id = parseInt(delBtn.getAttribute('data-id'), 10);
        const hasKids = (childrenMap[id] || []).length > 0;
        const conf = await Swal.fire({
          icon: 'warning',
          title: 'Hapus node RKKS ini?',
          text: hasKids ? 'Node ini memiliki sub-item di bawahnya dan semuanya akan ikut terhapus!' : 'Item ini akan dihapus dan pagu induk dihitung ulang.',
          showCancelButton: true,
          confirmButtonColor: '#dc2626',
          confirmButtonText: 'Ya, Hapus'
        });
        if (!conf.isConfirmed) return;
        const res = await fetch('/relaxtime/rkks/item/delete/' + id, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (res.ok && json.success) {
          applyLiveState(json.state);
          Toast.fire({ icon: 'success', title: json.message });
        } else {
          Swal.fire('Gagal', json.message || 'Gagal menghapus item.', 'error');
        }
      }
    });
  }

  // Submit RUH Form
  document.getElementById('formRuhItem').addEventListener('submit', async function (e) {
    e.preventDefault();
    const id = document.getElementById('ruhIdItem').value;
    const parentId = document.getElementById('ruhParentId').value;
    const sel = document.getElementById('ruhLevelType');
    const wasDisabled = sel.disabled;
    sel.disabled = false;
    const fd = new FormData(this);
    sel.disabled = wasDisabled;

    fd.set('harga_satuan', String(parseRp(document.getElementById('ruhHargaSatuan').value)));
    fd.set('jumlah', String(parseRp(document.getElementById('ruhJumlah').value)));
    fd.set('nilai_blokir', String(parseRp(document.getElementById('ruhNilaiBlokir').value)));

    const btn = document.getElementById('btnSubmitRuh');
    btn.disabled = true;
    try {
      const url = id ? '/relaxtime/rkks/item/update/' + id : '/relaxtime/rkks/item/store';
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
      });
      const json = await res.json();
      if (res.ok && json.success) {
        $('#modalRuhItem').modal('hide');
        applyLiveState(json.state, {
          highlightId: json.id_item || id,
          expandParentId: id ? null : parentId
        });
        Toast.fire({ icon: 'success', title: json.message });
      } else {
        Swal.fire('Gagal', json.message || 'Gagal menyimpan item RKKS.', 'error');
      }
    } finally {
      btn.disabled = false;
    }
  });

  // Search, Filters, Depth buttons
  ['rkksSearch', 'rkksFilterAkun', 'rkksFilterSd'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener(id === 'rkksSearch' ? 'input' : 'change', renderTree);
  });

  const btnBlkFilter = document.getElementById('btnFilterBlokir');
  if (btnBlkFilter) {
    btnBlkFilter.addEventListener('click', () => {
      onlyBlokir = !onlyBlokir;
      btnBlkFilter.classList.toggle('btn-danger', onlyBlokir);
      btnBlkFilter.classList.toggle('btn-outline-danger', !onlyBlokir);
      renderTree();
    });
  }

  document.querySelectorAll('.rkks-depth-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.rkks-depth-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentDepth = btn.getAttribute('data-depth');
      applyDepthCollapse(currentDepth);
      renderTree();
    });
  });

  if (typeof $ !== 'undefined' && $.fn.DataTable && document.getElementById('rekapTable')) {
    rekapDtInstance = $('#rekapTable').DataTable({ pageLength: 25, order: [] });
  }

  if (ITEMS.length > 0) {
    applyDepthCollapse(currentDepth);
    renderTree();
  }
});
</script>
<?= $this->endSection() ?>
