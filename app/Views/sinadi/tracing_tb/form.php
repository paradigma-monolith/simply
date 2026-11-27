<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$isEdit = ($mode === 'edit');
$idRecord = (int) ($record['id_tracing_tb'] ?? 0);
$nonRadiografer = !empty($record['non_radiografer']);
$hasSavedTtd = !empty($record['ttd_pengaju']);
$namaPengajuTtdVal = (string) ($record['nama_pengaju_ttd'] ?? ($ctx['nama'] ?? ''));
$tglTtdPengajuVal = (string) ($record['tgl_ttd_pengaju'] ?? date('Y-m-d'));
$totalDokumenAll = 0;
$uploadedCountInit = 0;
$akunDocCounts = [];
foreach ($accounts as $kodeAk => $ak) {
    $akunTotal = count($ak['dokumen']);
    $akunUp = 0;
    foreach ($ak['dokumen'] as $d) {
        $totalDokumenAll++;
        if (!empty($dokumenMap[$d['kode']]['nama_file'])) {
            $uploadedCountInit++;
            $akunUp++;
        }
    }
    $akunDocCounts[$kodeAk] = ['uploaded' => $akunUp, 'total' => $akunTotal];
}
$accountKeys = array_keys($accounts);
$tabShortNames = [
    '522151' => 'Jasa Profesi',
    '524111' => 'Perjadin Dalam Kota',
    '521211' => 'Belanja Bahan',
];
?>

<style>
  .tb-form-card .card-header {
    padding: 0.45rem 0.85rem;
  }
  .tb-form-card .card-header::before,
  .tb-form-card .card-header::after {
    display: none !important;
    content: none !important;
  }
  .tb-form-card .card-body {
    padding: 0.7rem 0.85rem;
  }
  .tb-vol-group {
    max-width: 116px;
    margin: 0 auto;
  }
  .tb-form-card .select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.8125rem + 2px) !important;
    min-height: calc(1.8125rem + 2px) !important;
  }
  .tb-form-card .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: 1.8125rem !important;
    font-size: 0.875rem !important;
    padding-left: 0.5rem !important;
    padding-right: 1.75rem !important;
  }
  .tb-form-card .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
    top: 0 !important;
    margin-top: 0 !important;
    height: 100% !important;
  }
  .tb-form-card .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
    top: 50% !important;
    margin-top: -2px !important;
  }
  .tb-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 2px;
    display: block;
  }
  .tb-compact-table {
    font-size: 0.8rem;
    margin-bottom: 0;
  }
  .tb-compact-table th {
    padding: 0.35rem 0.45rem !important;
    font-size: 0.74rem;
    white-space: nowrap;
  }
  .tb-compact-table td {
    padding: 0.3rem 0.45rem !important;
    vertical-align: middle !important;
  }
  .tracing-budget-input {
    min-width: 54px;
    max-width: 68px;
    height: 27px !important;
    padding: 2px 5px !important;
    text-align: center;
    font-size: 0.78rem !important;
  }
  .tb-unit-addon {
    height: 27px;
    padding: 2px 6px !important;
    font-size: 0.72rem !important;
  }
  .tracing-harga-input {
    min-width: 96px;
    height: 27px !important;
    padding: 2px 6px !important;
    text-align: right;
    font-size: 0.78rem !important;
  }
  .tb-file-picker {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
  }
  .tb-file-picker input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    overflow: hidden;
    z-index: -1;
  }
  .tb-file-btn {
    flex-shrink: 0;
    padding: 2px 8px;
    font-size: 0.74rem;
    line-height: 1.4;
    border-radius: 4px;
    margin: 0;
    cursor: pointer;
  }
  .tb-file-name {
    font-size: 0.75rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1 1 auto;
    min-width: 0;
    display: block;
  }
  .tb-file-name.has-file {
    color: #0f172a;
    font-weight: 500;
  }
  .doc-row-radiografer.is-muted {
    opacity: 0.5;
    background-color: #f8fafc;
  }
  .tb-akun-tabs .nav-link {
    padding: 0.5rem 0.85rem;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }
  .tb-akun-tabs .nav-link.active {
    font-weight: 700;
  }
  .tb-sticky-footer {
    position: sticky;
    bottom: 10px;
    z-index: 20;
    background: rgba(255, 255, 255, 0.97);
    backdrop-filter: blur(6px);
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.12);
    padding: 8px 14px;
    margin-right: 88px;
  }
  @media (max-width: 575.98px) {
    .tb-sticky-footer {
      position: static;
      margin-right: 0;
    }
  }
  .tb-sig-pad {
    border: 1px dashed #94a3b8;
    border-radius: 6px;
    background: #ffffff;
    position: relative;
    height: 118px;
  }
  .tb-sig-pad canvas {
    width: 100%;
    height: 118px;
    display: block;
    touch-action: none;
    cursor: crosshair;
  }
  .tb-sig-preview-img {
    max-height: 62px;
    max-width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    background: #ffffff;
    padding: 3px 6px;
  }
  .upload-progress-banner {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    z-index: 10050;
    align-items: center;
    justify-content: center;
  }
  .upload-progress-banner.show {
    display: flex;
  }
</style>

<div id="tracingUploadOverlay" class="upload-progress-banner">
  <div class="card shadow-lg text-center p-4" style="max-width: 420px; width: 92%;">
    <div class="mb-3"><i class="fas fa-cloud-upload-alt fa-3x text-primary"></i></div>
    <h5 id="tracingUploadTitle" class="font-weight-bold mb-1">Menyimpan Data Tracing TB...</h5>
    <p id="tracingUploadStatus" class="text-muted small mb-3">Mohon tunggu, jangan tutup halaman.</p>
    <div class="progress">
      <div id="tracingUploadBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 15%"></div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <!-- Compact Top Action Bar -->
    <div class="mb-2 d-flex flex-wrap justify-content-between align-items-center" style="gap: 6px;">
      <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
        <a href="/sinadi/tracing-tb" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTemplateTracingTb">
          <i class="fas fa-file-download mr-1"></i> Download Template Dokumen
          <span class="badge badge-light ml-1"><?= count($templateFiles ?? []); ?></span>
        </button>
        <?php if ($isEdit) : ?>
          <a href="/sinadi/tracing-tb/detail/<?= $idRecord; ?>" class="btn btn-outline-info btn-sm">
            <i class="fas fa-clipboard-check mr-1"></i> Detail / Verifikasi
          </a>
        <?php endif; ?>
      </div>
      <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
        <span class="badge badge-light border text-dark py-1 px-2" style="font-size: 0.78rem;">
          Total: <strong class="text-success">Rp <span class="grand-total-text"><?= number_format((int) $rincian['grand_total'], 0, ',', '.'); ?></span></strong>
        </span>
        <span class="badge badge-info py-1 px-2" style="font-size: 0.78rem;">
          <i class="fas fa-file-upload mr-1"></i>
          Dokumen: <span id="counterUploadedDocs"><?= $uploadedCountInit; ?></span>/<?= $totalDokumenAll; ?>
        </span>
      </div>
    </div>

    <form id="formTracingTb" action="/sinadi/tracing-tb/save" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_tracing_tb" id="id_tracing_tb" value="<?= $idRecord; ?>">
      <input type="hidden" name="submit_action" id="submit_action" value="draft">
      <input type="hidden" id="grandTotalDisplay" value="<?= number_format((int) $rincian['grand_total'], 0, ',', '.'); ?>">

      <!-- 1. IDENTITAS KEGIATAN (Compact 2-Row Grid) -->
      <div class="card card-primary card-outline tb-form-card shadow-sm mb-2">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
          <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.88rem;">
            <i class="fas fa-info-circle text-primary mr-1"></i>
            Identitas Kegiatan — Penemuan Kasus TBC Terintegrasi CKG
          </h3>
          <div class="custom-control custom-switch mb-0">
            <input type="checkbox" class="custom-control-input" id="toggleNonRadiografer" name="non_radiografer" value="1" <?= $nonRadiografer ? 'checked' : ''; ?>>
            <label class="custom-control-label small font-weight-bold text-secondary" for="toggleNonRadiografer" style="cursor: pointer;">
              Non Radiografer
            </label>
          </div>
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="tb-label" for="lokus">Lokus Kegiatan <span class="text-danger">*</span></label>
              <?php
                $selectedLokus = trim((string) old('lokus', $record['lokus'] ?? ''));
                $lokusCatalog = $lokusList ?? [];
                $isCustomSavedLokus = ($selectedLokus !== '' && !in_array($selectedLokus, $lokusCatalog, true));
              ?>
              <select name="lokus" id="lokus" class="form-control form-control-sm select2bs4" style="width: 100%;" required>
                <option value="">-- Pilih Lokus Kegiatan (<?= count($lokusCatalog); ?> Lokus) --</option>
                <?php if ($isCustomSavedLokus) : ?>
                  <option value="<?= esc($selectedLokus); ?>" selected><?= esc($selectedLokus); ?> (Lokus tersimpan)</option>
                <?php endif; ?>
                <?php foreach ($lokusCatalog as $namaLokus) : ?>
                  <option value="<?= esc($namaLokus); ?>" <?= $selectedLokus === $namaLokus ? 'selected' : ''; ?>>
                    <?= esc($namaLokus); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-5 mb-2">
              <label class="tb-label" for="instansi">Balai / Besar / Loka Kekarantinaan Kesehatan <span class="text-danger">*</span></label>
              <input type="text" name="instansi" id="instansi" class="form-control form-control-sm" required
                value="<?= esc(old('instansi', $record['instansi'] ?? 'Balai Kekarantinaan Kesehatan Kelas I Bandung')); ?>">
            </div>
            <div class="col-md-3 mb-2">
              <label class="tb-label" for="tanggal_kegiatan">Tanggal Kegiatan <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" class="form-control form-control-sm" required
                value="<?= esc(old('tanggal_kegiatan', $record['tanggal_kegiatan'] ?? date('Y-m-d'))); ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-2 mb-1 mb-md-0">
              <label class="tb-label">Kode Output</label>
              <input type="text" name="kode_kegiatan" class="form-control form-control-sm"
                value="<?= esc(old('kode_kegiatan', $record['kode_kegiatan'] ?? '7956.QAA.061.051')); ?>">
            </div>
            <div class="col-md-6 mb-1 mb-md-0">
              <label class="tb-label">Nama Output / Komponen</label>
              <input type="text" name="nama_kegiatan" class="form-control form-control-sm"
                value="<?= esc(old('nama_kegiatan', $record['nama_kegiatan'] ?? 'Masyarakat Mendapatkan Layanan Skrining TBC - Penemuan Kasus TBC Terintegrasi CKG')); ?>">
            </div>
            <div class="col-md-4">
              <label class="tb-label">Catatan Tambahan Pengaju <span class="font-weight-normal text-muted">(opsional)</span></label>
              <input type="text" name="catatan_pengaju" class="form-control form-control-sm"
                placeholder="Catatan khusus untuk verifikator..."
                value="<?= esc(old('catatan_pengaju', $record['catatan_pengaju'] ?? '')); ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- 2. TABBED 3 AKUN BELANJA + UPLOAD DOKUMEN -->
      <div class="card card-teal card-outline card-outline-tabs tb-form-card shadow-sm mb-2">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs tb-akun-tabs" id="tracingAkunTabs" role="tablist">
            <?php foreach ($accounts as $kodeAkun => $akun) :
              $isFirst = ($kodeAkun === $accountKeys[0]);
              $cnt = $akunDocCounts[$kodeAkun];
            ?>
              <li class="nav-item">
                <a class="nav-link <?= $isFirst ? 'active' : ''; ?>"
                   id="tab-link-<?= esc($kodeAkun); ?>"
                   data-toggle="pill"
                   href="#tab-pane-<?= esc($kodeAkun); ?>"
                   role="tab"
                   aria-controls="tab-pane-<?= esc($kodeAkun); ?>"
                   aria-selected="<?= $isFirst ? 'true' : 'false'; ?>">
                  <span class="badge badge-<?= esc($akun['badge_color']); ?>"><?= esc($kodeAkun); ?></span>
                  <span><?= esc($tabShortNames[$kodeAkun] ?? $akun['nama']); ?></span>
                  <span class="badge badge-light border text-dark">
                    Rp <span class="akun-subtotal" data-akun="<?= esc($kodeAkun); ?>"><?= number_format((int) ($rincian['subtotals'][$kodeAkun] ?? 0), 0, ',', '.'); ?></span>
                  </span>
                  <span class="badge badge-secondary akun-doc-badge" data-akun="<?= esc($kodeAkun); ?>">
                    <span class="akun-doc-uploaded"><?= $cnt['uploaded']; ?></span>/<?= $cnt['total']; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content" id="tracingAkunTabContent">
            <?php foreach ($accounts as $kodeAkun => $akun) :
              $tabIdx = array_search($kodeAkun, $accountKeys, true);
              $isFirst = ($tabIdx === 0);
              $prevAkun = ($tabIdx > 0) ? $accountKeys[$tabIdx - 1] : null;
              $nextAkun = ($tabIdx < count($accountKeys) - 1) ? $accountKeys[$tabIdx + 1] : null;
            ?>
              <div class="tab-pane <?= $isFirst ? 'active' : ''; ?>"
                   id="tab-pane-<?= esc($kodeAkun); ?>"
                   role="tabpanel"
                   aria-labelledby="tab-link-<?= esc($kodeAkun); ?>">

                <!-- Sub-header Akun & Rincian Volume -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1" style="gap: 6px;">
                  <div class="small font-weight-bold text-dark">
                    <i class="fas fa-calculator text-<?= esc($akun['badge_color']); ?> mr-1"></i>
                    Rincian Volume &amp; Realisasi — <?= esc($akun['nama']); ?>
                    <span class="text-muted font-weight-normal ml-1">(<?= esc($akun['catatan_anggaran']); ?>)</span>
                  </div>
                  <div class="small font-weight-bold text-<?= esc($akun['badge_color']); ?>">
                    Subtotal: Rp <span class="akun-subtotal" data-akun="<?= esc($kodeAkun); ?>"><?= number_format((int) ($rincian['subtotals'][$kodeAkun] ?? 0), 0, ',', '.'); ?></span>
                  </div>
                </div>

                <div class="table-responsive mb-3">
                  <table class="table table-sm table-bordered tb-compact-table">
                    <thead class="thead-light text-center">
                      <tr>
                        <th width="32%" class="text-left">Uraian</th>
                        <th width="11%">Vol 1</th>
                        <th width="11%">Vol 2</th>
                        <th width="11%">Vol 3</th>
                        <th width="9%">Total</th>
                        <th width="13%">Satuan (Rp)</th>
                        <th width="13%">Jumlah (Rp)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($akun['anggaran'] as $item) :
                        $k = $item['kode'];
                        $val = $rincian['items'][$k];
                        $isRadioRow = !empty($item['opsional_non_radiografer']);
                      ?>
                        <tr class="budget-row <?= $isRadioRow ? 'budget-row-radiografer' : ''; ?>" data-akun="<?= esc($kodeAkun); ?>" data-item="<?= esc($k); ?>">
                          <td>
                            <strong><?= esc($item['nama']); ?></strong>
                            <span class="text-muted small d-block" style="line-height: 1.15;"><?= esc($item['catatan']); ?></span>
                          </td>
                          <td>
                            <div class="input-group input-group-sm flex-nowrap tb-vol-group">
                              <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_or]"
                                class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                                data-default="<?= esc($item['vol_or']); ?>"
                                value="<?= esc($val['vol_or']); ?>">
                              <div class="input-group-append"><span class="input-group-text tb-unit-addon"><?= esc($item['satuan_or']); ?></span></div>
                            </div>
                          </td>
                          <td>
                            <div class="input-group input-group-sm flex-nowrap tb-vol-group">
                              <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_waktu]"
                                class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                                data-default="<?= esc($item['vol_waktu']); ?>"
                                value="<?= esc($val['vol_waktu']); ?>">
                              <div class="input-group-append"><span class="input-group-text tb-unit-addon"><?= esc($item['satuan_waktu']); ?></span></div>
                            </div>
                          </td>
                          <td>
                            <div class="input-group input-group-sm flex-nowrap tb-vol-group">
                              <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_lokus]"
                                class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                                data-default="<?= esc($item['vol_lokus']); ?>"
                                value="<?= esc($val['vol_lokus']); ?>">
                              <div class="input-group-append"><span class="input-group-text tb-unit-addon"><?= esc($item['satuan_lokus']); ?></span></div>
                            </div>
                          </td>
                          <td class="text-center text-nowrap">
                            <strong class="row-total-vol"><?= esc($val['total_vol']); ?></strong> <small><?= esc($item['satuan_total']); ?></small>
                          </td>
                          <td>
                            <input type="number" min="0" step="1" name="anggaran[<?= esc($k); ?>][harga_satuan]"
                              class="form-control tracing-harga-input calc-trigger"
                              value="<?= (int) $val['harga_satuan']; ?>">
                          </td>
                          <td class="text-right text-nowrap font-weight-bold">
                            Rp <span class="row-subtotal"><?= number_format((int) $val['subtotal'], 0, ',', '.'); ?></span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Upload Dokumen Klaim -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1" style="gap: 6px;">
                  <div class="small font-weight-bold text-dark">
                    <i class="fas fa-file-upload text-<?= esc($akun['badge_color']); ?> mr-1"></i>
                    Upload Dokumen Klaim &amp; Pertanggungjawaban (<?= count($akun['dokumen']); ?> Item)
                  </div>
                  <small class="text-muted">Format: PDF, Word (.doc/.docx), Excel (.xls/.xlsx), PNG/JPEG (maks 15 MB)</small>
                </div>

                <div class="table-responsive">
                  <table class="table table-sm table-bordered table-hover tb-compact-table">
                    <thead class="thead-light">
                      <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="11%">Sifat</th>
                        <th width="44%">Dokumen yang Diminta</th>
                        <th width="30%">Upload File</th>
                        <th width="11%" class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $docNo = 1; foreach ($akun['dokumen'] as $doc) :
                        $kodeDok = $doc['kode'];
                        $savedDoc = $dokumenMap[$kodeDok] ?? null;
                        $hasFile = !empty($savedDoc['nama_file']);
                        $isRadioDoc = !empty($doc['radiografer_only']);
                        $kat = $doc['kategori'];
                        $katBadge = 'badge-secondary';
                        if ($kat === 'Wajib') $katBadge = 'badge-danger';
                        elseif (stripos($kat, 'Wajib') !== false) $katBadge = 'badge-warning';
                        elseif (stripos($kat, 'PIC') !== false) $katBadge = 'badge-info';
                      ?>
                        <tr class="doc-row <?= $isRadioDoc ? 'doc-row-radiografer' . ($nonRadiografer ? ' is-muted' : '') : ''; ?>"
                            data-kode="<?= esc($kodeDok); ?>"
                            data-akun="<?= esc($kodeAkun); ?>">
                          <td class="text-center text-muted"><?= $docNo++; ?></td>
                          <td><span class="badge <?= $katBadge; ?>"><?= esc($kat); ?></span></td>
                          <td><?= esc($doc['nama']); ?></td>
                          <td>
                            <div class="tb-file-picker">
                              <input type="file"
                                class="tracing-doc-input"
                                id="file_<?= esc($kodeDok); ?>"
                                name="dokumen[<?= esc($kodeDok); ?>]"
                                data-kode="<?= esc($kodeDok); ?>"
                                data-akun="<?= esc($kodeAkun); ?>"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/png,image/jpeg">
                              <label for="file_<?= esc($kodeDok); ?>" class="btn btn-outline-primary tb-file-btn">
                                <i class="fas fa-upload mr-1"></i><?= $hasFile ? 'Ganti' : 'Pilih'; ?>
                              </label>
                              <span class="tb-file-name <?= $hasFile ? 'has-file' : ''; ?>"
                                    id="fname_<?= esc($kodeDok); ?>"
                                    title="<?= $hasFile ? esc($savedDoc['nama_file_asli']) : 'Belum ada file dipilih'; ?>">
                                <?= $hasFile ? esc($savedDoc['nama_file_asli']) : 'Belum ada file'; ?>
                              </span>
                            </div>
                          </td>
                          <td class="text-center doc-status-cell" id="status_<?= esc($kodeDok); ?>" data-akun="<?= esc($kodeAkun); ?>">
                            <?php if ($hasFile) : ?>
                              <div class="btn-group btn-group-xs">
                                <button type="button"
                                  class="btn btn-info btn-xs btn-preview-doc"
                                  data-url="<?= esc(base_url('sinadi/tracing-tb/preview-dokumen/' . (int) $savedDoc['id_dokumen'])); ?>"
                                  data-name="<?= esc($savedDoc['nama_file_asli']); ?>"
                                  data-mime="<?= esc($savedDoc['mime_type'] ?? ''); ?>"
                                  title="Pratinjau">
                                  <i class="fas fa-eye"></i>
                                </button>
                                <button type="button"
                                  class="btn btn-danger btn-xs btn-delete-doc"
                                  data-id-doc="<?= (int) $savedDoc['id_dokumen']; ?>"
                                  data-kode="<?= esc($kodeDok); ?>"
                                  data-akun="<?= esc($kodeAkun); ?>"
                                  title="Hapus File">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </div>
                            <?php else : ?>
                              <span class="text-muted small">&mdash;</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Navigasi Cepat Antar Tab Akun -->
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                  <div>
                    <?php if ($prevAkun !== null) : ?>
                      <button type="button" class="btn btn-outline-secondary btn-xs btn-switch-akun" data-target="#tab-link-<?= esc($prevAkun); ?>">
                        <i class="fas fa-chevron-left mr-1"></i> Akun <?= esc($prevAkun); ?>
                      </button>
                    <?php endif; ?>
                  </div>
                  <div>
                    <?php if ($nextAkun !== null) : ?>
                      <button type="button" class="btn btn-outline-primary btn-xs btn-switch-akun" data-target="#tab-link-<?= esc($nextAkun); ?>">
                        Lanjut Akun <?= esc($nextAkun); ?> <i class="fas fa-chevron-right ml-1"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>

              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- 3. TANDA TANGAN PENGAJU (Compact 2-Column Card) -->
      <div class="card card-secondary card-outline tb-form-card shadow-sm mb-2" id="cardTtdPengaju">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
          <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.88rem;">
            <i class="fas fa-signature text-primary mr-1"></i>
            Tanda Tangan Pengaju Form <span class="text-danger">*</span>
          </h3>
          <span id="badgeTtdStatus" class="badge <?= $hasSavedTtd ? 'badge-success' : 'badge-warning'; ?>">
            <?= $hasSavedTtd ? '<i class="fas fa-check mr-1"></i>Sudah Ditandatangani' : '<i class="fas fa-pen-nib mr-1"></i>Belum Ditandatangani'; ?>
          </span>
        </div>
        <div class="card-body">
          <div class="row align-items-start">
            <div class="col-md-5 mb-2 mb-md-0">
              <div class="form-group mb-2">
                <label class="tb-label" for="nama_pengaju_ttd">Nama Pengaju <span class="text-danger">*</span></label>
                <input type="text" name="nama_pengaju_ttd" id="nama_pengaju_ttd" class="form-control form-control-sm" required
                  value="<?= esc(old('nama_pengaju_ttd', $namaPengajuTtdVal)); ?>"
                  placeholder="Nama lengkap pengaju...">
              </div>
              <div class="form-group mb-<?= $hasSavedTtd ? '2' : '0'; ?>">
                <label class="tb-label" for="tgl_ttd_pengaju">Tanggal Tanda Tangan</label>
                <input type="date" name="tgl_ttd_pengaju" id="tgl_ttd_pengaju" class="form-control form-control-sm"
                  value="<?= esc(old('tgl_ttd_pengaju', $tglTtdPengajuVal)); ?>">
              </div>
              <?php if ($hasSavedTtd) : ?>
                <div id="savedTtdPreviewBox" class="mt-2 p-2 rounded bg-light border d-flex align-items-center justify-content-between" style="gap: 8px;">
                  <div>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">TTD tersimpan (gambar ulang di kanan untuk mengganti):</small>
                    <img src="<?= esc($record['ttd_pengaju']); ?>" alt="TTD Pengaju Tersimpan" class="tb-sig-preview-img mt-1">
                  </div>
                  <button type="button" class="btn btn-outline-danger btn-xs flex-shrink-0" id="btnHapusSavedTtd" title="Hapus tanda tangan tersimpan">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              <?php endif; ?>
            </div>

            <div class="col-md-7">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-1" style="gap: 6px;">
                <span class="tb-label mb-0">
                  Area Tanda Tangan <span class="font-weight-normal text-muted">(goreskan dengan mouse / sentuh layar)</span>
                </span>
                <div class="btn-group btn-group-xs">
                  <button type="button" class="btn btn-dark btn-xs btn-pen-color active" data-color="#0f172a" title="Tinta Hitam">Hitam</button>
                  <button type="button" class="btn btn-outline-primary btn-xs btn-pen-color" data-color="#1d4ed8" title="Tinta Biru">Biru</button>
                  <button type="button" class="btn btn-outline-secondary btn-xs" id="btnClearTtdPengaju" title="Bersihkan kanvas">
                    <i class="fas fa-eraser mr-1"></i>Bersihkan
                  </button>
                </div>
              </div>
              <div class="tb-sig-pad">
                <canvas id="canvasTtdPengaju"></canvas>
              </div>
              <input type="hidden" name="ttd_pengaju" id="ttd_pengaju" value="">
              <input type="hidden" name="hapus_ttd_pengaju" id="hapus_ttd_pengaju" value="0">
            </div>
          </div>
        </div>
      </div>

      <!-- Sticky Compact Footer Action Bar -->
      <div class="tb-sticky-footer d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 8px;">
        <div class="small text-muted d-flex flex-wrap align-items-center" style="gap: 10px;">
          <span>Total Realisasi: <strong class="text-success">Rp <span class="grand-total-text"><?= number_format((int) $rincian['grand_total'], 0, ',', '.'); ?></span></strong></span>
          <span class="d-none d-sm-inline">&bull;</span>
          <span>Terunggah: <strong class="text-info"><span class="counter-uploaded-copy"><?= $uploadedCountInit; ?></span>/<?= $totalDokumenAll; ?></strong></span>
          <span class="d-none d-sm-inline">&bull;</span>
          <span>TTD: <strong id="footerTtdStatus" class="<?= $hasSavedTtd ? 'text-success' : 'text-warning'; ?>"><?= $hasSavedTtd ? 'Siap' : 'Belum'; ?></strong></span>
        </div>
        <div class="d-flex flex-wrap" style="gap: 6px;">
          <button type="button" class="btn btn-secondary btn-sm btn-submit-tracing" data-action="draft">
            <i class="fas fa-save mr-1"></i> Simpan Draft
          </button>
          <button type="button" class="btn btn-success btn-sm btn-submit-tracing" data-action="ajukan">
            <i class="fas fa-paper-plane mr-1"></i> Simpan &amp; Ajukan Verifikasi
          </button>
        </div>
      </div>
    </form>

  </div>
</section>

<?= $this->include('sinadi/tracing_tb/_template_modal'); ?>

<script src="/dist/js/signature_pad2.3.2.min.js"></script>
<script>
  $(function() {
    var hasSavedTtd = <?= $hasSavedTtd ? 'true' : 'false'; ?>;
    var canvasTtd = document.getElementById('canvasTtdPengaju');
    var sigPadPengaju = null;

    function updateTtdStatusBadge() {
      var hasDrawn = !!(sigPadPengaju && !sigPadPengaju.isEmpty());
      var ready = hasDrawn || (hasSavedTtd && $('#hapus_ttd_pengaju').val() !== '1');
      if (ready) {
        $('#badgeTtdStatus')
          .removeClass('badge-warning').addClass('badge-success')
          .html('<i class="fas fa-check mr-1"></i>' + (hasDrawn ? 'TTD Baru Siap Disimpan' : 'Sudah Ditandatangani'));
        $('#footerTtdStatus').removeClass('text-warning').addClass('text-success').text('Siap');
      } else {
        $('#badgeTtdStatus')
          .removeClass('badge-success').addClass('badge-warning')
          .html('<i class="fas fa-pen-nib mr-1"></i>Belum Ditandatangani');
        $('#footerTtdStatus').removeClass('text-success').addClass('text-warning').text('Belum');
      }
    }

    if (canvasTtd && typeof SignaturePad !== 'undefined') {
      function resizeTtdCanvas() {
        var data = sigPadPengaju ? sigPadPengaju.toData() : null;
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvasTtd.width = canvasTtd.offsetWidth * ratio;
        canvasTtd.height = canvasTtd.offsetHeight * ratio;
        canvasTtd.getContext('2d').scale(ratio, ratio);
        if (sigPadPengaju) {
          sigPadPengaju.clear();
          if (data && data.length) {
            sigPadPengaju.fromData(data);
          }
        }
      }

      sigPadPengaju = new SignaturePad(canvasTtd, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: '#0f172a',
        onEnd: updateTtdStatusBadge
      });
      resizeTtdCanvas();
      $(window).on('resize', resizeTtdCanvas);

      $('.btn-pen-color').on('click', function() {
        var color = $(this).data('color') || '#0f172a';
        sigPadPengaju.penColor = color;
        $('.btn-pen-color').removeClass('active btn-dark btn-primary').addClass('btn-outline-secondary');
        if (color === '#1d4ed8') {
          $(this).removeClass('btn-outline-secondary btn-outline-primary').addClass('btn-primary active');
        } else {
          $(this).removeClass('btn-outline-secondary').addClass('btn-dark active');
        }
      });

      $('#btnClearTtdPengaju').on('click', function() {
        sigPadPengaju.clear();
        $('#ttd_pengaju').val('');
        updateTtdStatusBadge();
      });
    }

    $('#btnHapusSavedTtd').on('click', function() {
      $('#hapus_ttd_pengaju').val('1');
      $('#savedTtdPreviewBox').slideUp(150);
      updateTtdStatusBadge();
    });

    function formatRupiah(num) {
      return Math.round(num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function escHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function recalcAll() {
      var akunTotals = { '522151': 0, '524111': 0, '521211': 0 };
      var grandTotal = 0;

      $('.budget-row').each(function() {
        var $row = $(this);
        var akun = $row.data('akun');
        var v1 = parseFloat($row.find('input[name*="[vol_or]"]').val()) || 0;
        var v2 = parseFloat($row.find('input[name*="[vol_waktu]"]').val()) || 0;
        var v3 = parseFloat($row.find('input[name*="[vol_lokus]"]').val()) || 0;
        var harga = parseFloat($row.find('input[name*="[harga_satuan]"]').val()) || 0;

        var totalVol = v1 * v2 * v3;
        var subtotal = Math.round(totalVol * harga);

        $row.find('.row-total-vol').text(Number.isInteger(totalVol) ? totalVol : totalVol.toFixed(2));
        $row.find('.row-subtotal').text(formatRupiah(subtotal));

        akunTotals[akun] = (akunTotals[akun] || 0) + subtotal;
        grandTotal += subtotal;
      });

      $.each(akunTotals, function(kode, val) {
        $('.akun-subtotal[data-akun="' + kode + '"]').text(formatRupiah(val));
      });
      var formattedGrand = formatRupiah(grandTotal);
      $('#grandTotalDisplay').val(formattedGrand);
      $('.grand-total-text').text(formattedGrand);
    }

    function updateUploadedCounters() {
      var totalCount = 0;
      $('.akun-doc-badge').each(function() {
        var akun = $(this).data('akun');
        var countAkun = $('.doc-status-cell[data-akun="' + akun + '"]').filter(function() {
          return $(this).find('.btn-preview-doc, .badge-doc-ready').length > 0;
        }).length;
        $(this).find('.akun-doc-uploaded').text(countAkun);
        $(this).toggleClass('badge-success', countAkun > 0).toggleClass('badge-secondary', countAkun === 0);
        totalCount += countAkun;
      });
      $('#counterUploadedDocs, .counter-uploaded-copy').text(totalCount);
    }

    $(document).on('input change', '.calc-trigger', recalcAll);

    $('.btn-switch-akun').on('click', function() {
      var target = $(this).data('target');
      $(target).tab('show');
      $('html, body').animate({ scrollTop: $('#tracingAkunTabs').offset().top - 70 }, 180);
    });

    $('#toggleNonRadiografer').on('change', function() {
      var isNon = $(this).is(':checked');
      $('.radio-vol-input').each(function() {
        if (isNon) {
          $(this).val(0);
        } else {
          $(this).val($(this).data('default') || 1);
        }
      });
      $('.doc-row-radiografer').toggleClass('is-muted', isNon);
      recalcAll();
    });

    function renderDocUploadedCell($cell, kode, akun, dok, previewUrl) {
      var html = '<div class="btn-group btn-group-xs">' +
        '<button type="button" class="btn btn-info btn-xs btn-preview-doc" data-url="' + escHtml(previewUrl) + '" data-name="' + escHtml(dok.nama_file_asli) + '" data-mime="' + escHtml(dok.mime_type || '') + '" title="Pratinjau">' +
        '<i class="fas fa-eye"></i></button>' +
        '<button type="button" class="btn btn-danger btn-xs btn-delete-doc" data-id-doc="' + dok.id_dokumen + '" data-kode="' + escHtml(kode) + '" data-akun="' + escHtml(akun) + '" title="Hapus File">' +
        '<i class="fas fa-trash"></i></button>' +
        '</div>';
      $cell.html(html);
      $('label[for="file_' + kode + '"]').html('<i class="fas fa-upload mr-1"></i>Ganti');
      $('#fname_' + kode).text(dok.nama_file_asli).attr('title', dok.nama_file_asli).addClass('has-file');
      updateUploadedCounters();
    }

    function uploadSingleFile(idTracingTb, kode, fileObj) {
      var fd = new FormData();
      fd.append('id_tracing_tb', idTracingTb);
      fd.append('kode_dokumen', kode);
      fd.append('file', fileObj);

      return $.ajax({
        url: '/sinadi/tracing-tb/upload-dokumen',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json'
      });
    }

    $(document).on('change', '.tracing-doc-input', function() {
      var input = this;
      var kode = $(this).data('kode');
      var akun = $(this).data('akun');
      var file = input.files && input.files[0] ? input.files[0] : null;
      var $fname = $('#fname_' + kode);
      var $cell = $('#status_' + kode);

      if (!file) {
        return;
      }

      var isAllowedExt = /\.(pdf|doc|docx|xls|xlsx|png|jpe?g)$/i.test(file.name || '');
      if (!isAllowedExt) {
        Swal.fire('Format Tidak Didukung', 'Format file yang diperbolehkan: PDF (.pdf), Word (.doc, .docx), Excel (.xls, .xlsx), dan Gambar (.png, .jpg, .jpeg).', 'warning');
        input.value = '';
        return;
      }

      if (file.size > 15 * 1024 * 1024) {
        Swal.fire('Ukuran Terlalu Besar', 'Maksimal ukuran file adalah 15 MB.', 'warning');
        input.value = '';
        return;
      }

      $fname.text(file.name).attr('title', file.name).addClass('has-file');

      var currentId = parseInt($('#id_tracing_tb').val(), 10) || 0;
      if (currentId > 0) {
        $cell.html('<span class="text-primary small"><i class="fas fa-spinner fa-spin"></i></span>');
        uploadSingleFile(currentId, kode, file)
          .done(function(res) {
            if (res && res.status === 'success') {
              renderDocUploadedCell($cell, kode, akun, res.dokumen, res.preview_url);
              input.value = '';
            } else {
              $cell.html('<span class="badge badge-danger">Gagal</span>');
              Swal.fire('Gagal', (res && res.message) || 'Gagal mengunggah file.', 'error');
            }
          })
          .fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal mengunggah file.';
            $cell.html('<span class="badge badge-danger">Gagal</span>');
            Swal.fire('Gagal', msg, 'error');
          });
      } else {
        $cell.html('<span class="badge badge-warning badge-doc-ready"><i class="fas fa-check mr-1"></i>Siap</span>');
        $('label[for="file_' + kode + '"]').html('<i class="fas fa-upload mr-1"></i>Ganti');
        updateUploadedCounters();
      }
    });

    $(document).on('click', '.btn-delete-doc', function() {
      var $btn = $(this);
      var idDoc = $btn.data('id-doc');
      var kode = $btn.data('kode');
      var $cell = $('#status_' + kode);

      Swal.fire({
        title: 'Hapus file dokumen ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then(function(result) {
        if (!result.isConfirmed) return;
        $.post('/sinadi/tracing-tb/hapus-dokumen/' + idDoc, {}, function(res) {
          if (res && res.status === 'success') {
            $cell.html('<span class="text-muted small">&mdash;</span>');
            $('#file_' + kode).val('');
            $('label[for="file_' + kode + '"]').html('<i class="fas fa-upload mr-1"></i>Pilih');
            $('#fname_' + kode).text('Belum ada file').attr('title', 'Belum ada file dipilih').removeClass('has-file');
            updateUploadedCounters();
          } else {
            Swal.fire('Gagal', (res && res.message) || 'Gagal menghapus file.', 'error');
          }
        }, 'json').fail(function() {
          Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus file.', 'error');
        });
      });
    });

    $(document).on('click', '.btn-preview-doc', function() {
      var url = $(this).data('url');
      var name = $(this).data('name') || 'Pratinjau Dokumen';
      var mime = String($(this).data('mime') || '').toLowerCase();
      var lowerName = String(name).toLowerCase();
      var isImg = mime.indexOf('image/') === 0 || /\.(jpg|jpeg|png|webp|gif)$/.test(lowerName);
      var isPdf = mime === 'application/pdf' || /\.pdf$/.test(lowerName);

      var bodyHtml = '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<strong>' + escHtml(name) + '</strong>' +
        '<a href="' + escHtml(url + '?download=1') + '" class="btn btn-sm btn-primary"><i class="fas fa-download mr-1"></i> Download</a>' +
        '</div>';

      if (isImg) {
        bodyHtml += '<div class="text-center bg-light p-2 border rounded"><img src="' + escHtml(url) + '" class="img-fluid" style="max-height: 75vh;" alt="Preview"></div>';
      } else if (isPdf) {
        bodyHtml += '<iframe class="tracing-modal-iframe" src="' + escHtml(url) + '" style="width: 100%; height: 78vh; border: 1px solid #dee2e6;"></iframe>';
      } else {
        bodyHtml += '<div class="alert alert-info mb-0"><i class="fas fa-file mr-1"></i> File format ini tidak dapat ditampilkan langsung di browser. Silakan klik tombol <strong>Download</strong> di atas.</div>';
      }

      $('#modal-xl .modal-body').html(bodyHtml);
      $('#modal-xl').modal('show');
    });

    $('#modal-xl').on('hidden.bs.modal', function() {
      if ($(this).find('.tracing-modal-iframe').length) {
        $(this).find('.modal-body').empty();
      }
    });

    $('.btn-submit-tracing').on('click', function() {
      var actionType = $(this).data('action');
      $('#submit_action').val(actionType);

      if (!$.trim($('#lokus').val() || '')) {
        Swal.fire({
          icon: 'warning',
          title: 'Lokus Kegiatan Belum Dipilih',
          text: 'Silakan pilih Lokus Kegiatan dari daftar lokus terlebih dahulu.'
        }).then(function() {
          if ($.fn.select2 && $('#lokus').data('select2')) {
            $('#lokus').select2('open');
          } else {
            $('#lokus').focus();
          }
        });
        return;
      }

      var formEl = document.getElementById('formTracingTb');
      if (!formEl.checkValidity()) {
        var firstInvalid = formEl.querySelector(':invalid');
        if (firstInvalid) {
          var pane = firstInvalid.closest('.tab-pane');
          if (pane && !pane.classList.contains('active')) {
            var tabLink = document.querySelector('#tracingAkunTabs a[href="#' + pane.id + '"]');
            if (tabLink) $(tabLink).tab('show');
          }
        }
        formEl.reportValidity();
        return;
      }

      var hasDrawnTtd = !!(sigPadPengaju && !sigPadPengaju.isEmpty());
      if (hasDrawnTtd) {
        $('#ttd_pengaju').val(sigPadPengaju.toDataURL('image/png'));
        $('#hapus_ttd_pengaju').val('0');
      } else {
        $('#ttd_pengaju').val('');
      }

      var hasValidTtd = hasDrawnTtd || (hasSavedTtd && $('#hapus_ttd_pengaju').val() !== '1');
      if (actionType === 'ajukan' && !hasValidTtd) {
        Swal.fire({
          icon: 'warning',
          title: 'Tanda Tangan Belum Diisi',
          text: 'Silakan bubuhkan tanda tangan pengaju terlebih dahulu sebelum mengajukan verifikasi.'
        }).then(function() {
          $('html, body').animate({ scrollTop: $('#cardTtdPengaju').offset().top - 70 }, 220);
        });
        return;
      }

      var pendingFiles = [];
      $('.tracing-doc-input').each(function() {
        if (this.files && this.files.length > 0) {
          pendingFiles.push({
            kode: $(this).data('kode'),
            file: this.files[0]
          });
        }
      });

      var metaFormData = new FormData();
      $(formEl).serializeArray().forEach(function(pair) {
        metaFormData.append(pair.name, pair.value);
      });

      $('#tracingUploadTitle').text('Menyimpan Data Tracing TB...');
      $('#tracingUploadStatus').text('Menyimpan identitas kegiatan & rincian anggaran...');
      $('#tracingUploadBar').css('width', '20%');
      $('#tracingUploadOverlay').addClass('show');

      $.ajax({
        url: '/sinadi/tracing-tb/save',
        type: 'POST',
        data: metaFormData,
        processData: false,
        contentType: false,
        dataType: 'json'
      }).done(async function(res) {
        if (!res || res.status !== 'success') {
          $('#tracingUploadOverlay').removeClass('show');
          Swal.fire('Gagal', (res && res.message) || 'Gagal menyimpan data.', 'error');
          return;
        }

        var savedId = res.id_tracing_tb;
        $('#id_tracing_tb').val(savedId);

        var uploadErrors = [];
        if (pendingFiles.length > 0) {
          $('#tracingUploadTitle').text('Mengunggah Dokumen...');
          for (var i = 0; i < pendingFiles.length; i++) {
            var item = pendingFiles[i];
            var pct = Math.round(25 + ((i + 1) / pendingFiles.length) * 70);
            $('#tracingUploadStatus').text('Mengunggah file ' + (i + 1) + ' dari ' + pendingFiles.length + ': ' + item.file.name);
            $('#tracingUploadBar').css('width', pct + '%');
            try {
              var upRes = await uploadSingleFile(savedId, item.kode, item.file);
              if (!upRes || upRes.status !== 'success') {
                uploadErrors.push(item.file.name + ': ' + ((upRes && upRes.message) || 'Gagal'));
              }
            } catch (err) {
              var errMsg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Gagal unggah';
              uploadErrors.push(item.file.name + ': ' + errMsg);
            }
          }
        }

        $('#tracingUploadBar').css('width', '100%');
        $('#tracingUploadOverlay').removeClass('show');

        if (uploadErrors.length > 0) {
          Swal.fire({
            icon: 'warning',
            title: 'Data Tersimpan dengan Catatan',
            html: 'Data berhasil disimpan, namun sebagian file gagal diunggah:<br><small>' + escHtml(uploadErrors.join(' | ')) + '</small>'
          }).then(function() {
            window.location.href = res.edit_url;
          });
        } else {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: res.message,
            timer: 1400,
            showConfirmButton: false
          }).then(function() {
            window.location.href = res.redirect_url;
          });
        }
      }).fail(function(xhr) {
        $('#tracingUploadOverlay').removeClass('show');
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan data.';
        Swal.fire('Gagal', msg, 'error');
      });
    });

    recalcAll();
    updateUploadedCounters();
  });
</script>

<?= $this->endSection(); ?>
