<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$idRecord = (int) $record['id_tracing_tb'];
$statusPengajuan = (string) ($record['status_pengajuan'] ?? 'Draft');
$nonRadiografer = !empty($record['non_radiografer']);
$canVerifyBkk = !empty($canVerifyBkk);
$badgeMap = [
    'Draft'         => 'badge-secondary',
    'Diajukan'      => 'badge-primary',
    'Perbaikan BKK' => 'badge-warning',
    'ACC BKK'       => 'badge-success',
];
$badgeCls = $badgeMap[$statusPengajuan] ?? 'badge-secondary';
$uploadedDocsCount = 0;
foreach ($dokumenMap as $dmRow) {
    if (!empty($dmRow['nama_file'])) {
        $uploadedDocsCount++;
    }
}
?>

<style>
  .tb-detail-card .card-header::before,
  .tb-detail-card .card-header::after {
    display: none !important;
    content: none !important;
  }
  .sig-pad-box {
    border: 1px dashed #6c757d;
    border-radius: 6px;
    background: #ffffff;
    position: relative;
    height: 140px;
  }
  .sig-pad-box canvas {
    width: 100%;
    height: 140px;
    display: block;
    touch-action: none;
  }
</style>

<section class="content">
  <div class="container-fluid">

    <!-- Top Action Toolbar -->
    <div class="mb-2 d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
      <div class="d-flex flex-wrap" style="gap: 6px;">
        <a href="/sinadi/tracing-tb" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <?php if ($canEdit) : ?>
          <a href="/sinadi/tracing-tb/edit/<?= $idRecord; ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit mr-1"></i> Edit &amp; Upload Dokumen
          </a>
        <?php endif; ?>
        <?php if ($canEdit && in_array($statusPengajuan, ['Draft', 'Perbaikan BKK'], true)) : ?>
          <form action="/sinadi/tracing-tb/ajukan/<?= $idRecord; ?>" method="post" class="d-inline">
            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Ajukan data ini untuk diverifikasi oleh Verifikator BKK?')">
              <i class="fas fa-paper-plane mr-1"></i> Ajukan Verifikasi
            </button>
          </form>
        <?php endif; ?>
        <a href="/sinadi/tracing-tb/pdf/<?= $idRecord; ?>" class="btn btn-danger btn-sm">
          <i class="fas fa-file-pdf mr-1"></i> Download PDF
        </a>
        <?php if ($uploadedDocsCount > 0) : ?>
          <a href="/sinadi/tracing-tb/download-zip/<?= $idRecord; ?>" class="btn btn-info btn-sm">
            <i class="fas fa-file-archive mr-1"></i> Download Semua File (.ZIP)
            <span class="badge badge-light ml-1"><?= $uploadedDocsCount; ?></span>
          </a>
        <?php else : ?>
          <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Belum ada file dokumen yang diunggah">
            <i class="fas fa-file-archive mr-1"></i> Download Semua File (.ZIP)
            <span class="badge badge-secondary ml-1">0</span>
          </button>
        <?php endif; ?>
        <a href="/sinadi/tracing-tb/cetak/<?= $idRecord; ?>" target="_blank" class="btn btn-dark btn-sm">
          <i class="fas fa-print mr-1"></i> Cetak Form Checklist
        </a>
      </div>
      <div>
        <span class="badge <?= $badgeCls; ?> py-1 px-2" style="font-size: 0.84rem;">
          Status: <?= esc($statusPengajuan); ?>
        </span>
      </div>
    </div>

    <!-- Identitas & Ringkasan Biaya -->
    <div class="card card-primary card-outline tb-detail-card shadow-sm mb-3">
      <div class="card-header bg-light py-2">
        <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.92rem;">
          <i class="fas fa-file-medical-alt text-primary mr-1"></i>
          Form Checklist Verifikasi Kebenaran &amp; Kelengkapan Dokumen — Tracing TB
        </h3>
      </div>
      <div class="card-body py-2 px-3">
        <div class="row">
          <div class="col-md-7">
            <table class="table table-sm table-borderless mb-0" style="font-size: 0.86rem;">
              <tr>
                <th width="32%">Lokus Kegiatan</th>
                <td>: <strong><?= esc($record['lokus']); ?></strong></td>
              </tr>
              <tr>
                <th>Instansi / BKK</th>
                <td>: <?= esc($record['instansi']); ?></td>
              </tr>
              <tr>
                <th>Tanggal Kegiatan</th>
                <td>: <?= !empty($record['tanggal_kegiatan']) ? esc(mediumdate_indo($record['tanggal_kegiatan'])) : '-'; ?></td>
              </tr>
              <tr>
                <th>Kode &amp; Output</th>
                <td>: <strong><?= esc($record['kode_kegiatan']); ?></strong> — <?= esc($record['nama_kegiatan']); ?></td>
              </tr>
              <tr>
                <th>Pengaju</th>
                <td>: <?= esc($record['nama_pengaju_ttd'] ?: ($record['nama_pengaju'] ?: '-')); ?>
                  <?php if ($nonRadiografer) : ?>
                    <span class="badge badge-secondary ml-1">Non Radiografer</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php if (!empty($record['catatan_pengaju'])) : ?>
                <tr>
                  <th>Catatan Pengaju</th>
                  <td>: <?= esc($record['catatan_pengaju']); ?></td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
          <div class="col-md-5">
            <div class="border rounded p-2 px-3 bg-light" style="font-size: 0.85rem;">
              <div class="small text-muted text-uppercase font-weight-bold mb-2">Ringkasan Realisasi Anggaran</div>
              <div class="d-flex justify-content-between mb-1">
                <span>522151 — Belanja Jasa Profesi</span>
                <strong>Rp <?= number_format((int) $record['total_jasa_profesi'], 0, ',', '.'); ?></strong>
              </div>
              <div class="d-flex justify-content-between mb-1">
                <span>524111 — Perjalanan Dinas Dalam Kota</span>
                <strong>Rp <?= number_format((int) $record['total_perjalanan_dinas'], 0, ',', '.'); ?></strong>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>521211 — Belanja Bahan</span>
                <strong>Rp <?= number_format((int) $record['total_belanja_bahan'], 0, ',', '.'); ?></strong>
              </div>
              <div class="d-flex justify-content-between border-top pt-2">
                <span class="font-weight-bold">Total Realisasi</span>
                <strong class="text-success" style="font-size: 1rem;">Rp <?= number_format((int) $record['total_anggaran'], 0, ',', '.'); ?></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FORM VERIFIKASI BKK (membungkus tabel checklist + blok keputusan jika user Verifikator BKK) -->
    <?php if ($canVerifyBkk) : ?>
      <form id="formVerifikasiTracingTb" action="/sinadi/tracing-tb/verifikasi/<?= $idRecord; ?>" method="post">
    <?php endif; ?>

    <!-- 3 Akun Belanja & Checklist Dokumen -->
    <?php foreach ($accounts as $kodeAkun => $akun) : ?>
      <div class="card card-<?= esc($akun['badge_color']); ?> card-outline tb-detail-card shadow-sm mb-3">
        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
          <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.9rem;">
            <span class="badge badge-<?= esc($akun['badge_color']); ?> mr-1"><?= esc($kodeAkun); ?></span>
            <?= esc($akun['nama']); ?>
          </h3>
          <strong class="text-<?= esc($akun['badge_color']); ?> small">
            Rp <?= number_format((int) ($rincian['subtotals'][$kodeAkun] ?? 0), 0, ',', '.'); ?>
          </strong>
        </div>
        <div class="card-body py-2 px-3">
          <!-- Rincian Realisasi -->
          <div class="table-responsive mb-2">
            <table class="table table-sm table-bordered mb-0" style="font-size: 0.82rem;">
              <thead class="thead-light text-center">
                <tr>
                  <th class="text-left">Uraian</th>
                  <th width="26%">Volume</th>
                  <th width="12%">Total</th>
                  <th width="16%">Harga Satuan</th>
                  <th width="16%">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($akun['anggaran'] as $item) :
                  $val = $rincian['items'][$item['kode']];
                ?>
                  <tr>
                    <td><?= esc($item['nama']); ?></td>
                    <td class="text-center">
                      <?= esc($val['vol_or']); ?> <?= esc($item['satuan_or']); ?> &times;
                      <?= esc($val['vol_waktu']); ?> <?= esc($item['satuan_waktu']); ?> &times;
                      <?= esc($val['vol_lokus']); ?> <?= esc($item['satuan_lokus']); ?>
                    </td>
                    <td class="text-center font-weight-bold"><?= esc($val['total_vol']); ?> <?= esc($item['satuan_total']); ?></td>
                    <td class="text-right">Rp <?= number_format((int) $val['harga_satuan'], 0, ',', '.'); ?></td>
                    <td class="text-right font-weight-bold">Rp <?= number_format((int) $val['subtotal'], 0, ',', '.'); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Tabel Dokumen + Checklist BKK -->
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small font-weight-bold text-dark">Checklist Dokumen Klaim &amp; Pertanggungjawaban</span>
            <?php if ($canVerifyBkk) : ?>
              <button type="button" class="btn btn-xs btn-outline-primary btn-check-all-akun" data-akun="<?= esc($kodeAkun); ?>">
                <i class="fas fa-check-double mr-1"></i> Centang Semua Akun <?= esc($kodeAkun); ?>
              </button>
            <?php endif; ?>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover mb-0" style="font-size: 0.82rem;">
              <thead class="thead-light">
                <tr>
                  <th width="4%" class="text-center">No</th>
                  <th width="12%">Sifat</th>
                  <th width="36%">Dokumen</th>
                  <th width="15%" class="text-center">File Lampiran</th>
                  <th width="9%" class="text-center">Cek BKK</th>
                  <th width="24%">Catatan Verifikator BKK</th>
                </tr>
              </thead>
              <tbody>
                <?php $dn = 1; foreach ($akun['dokumen'] as $doc) :
                  $kodeDok = $doc['kode'];
                  $saved = $dokumenMap[$kodeDok] ?? null;
                  $hasFile = !empty($saved['nama_file']);
                  $chkBkk = !empty($saved['checklist_bkk']);
                  $noteBkk = (string) ($saved['catatan_bkk'] ?? '');
                  $kat = $doc['kategori'];
                  $katBadge = $kat === 'Wajib' ? 'badge-danger' : (stripos($kat, 'Wajib') !== false ? 'badge-warning' : (stripos($kat, 'PIC') !== false ? 'badge-info' : 'badge-secondary'));
                ?>
                  <tr class="<?= (!empty($doc['radiografer_only']) && $nonRadiografer) ? 'table-secondary text-muted' : ''; ?>">
                    <td class="text-center align-middle"><?= $dn++; ?></td>
                    <td class="align-middle"><span class="badge <?= $katBadge; ?>"><?= esc($kat); ?></span></td>
                    <td class="align-middle"><?= esc($doc['nama']); ?></td>
                    <td class="text-center align-middle">
                      <?php if ($hasFile) : ?>
                        <div class="btn-group btn-group-xs">
                          <button type="button"
                            class="btn btn-info btn-xs btn-preview-doc"
                            data-url="<?= esc(base_url('sinadi/tracing-tb/preview-dokumen/' . (int) $saved['id_dokumen'])); ?>"
                            data-name="<?= esc($saved['nama_file_asli']); ?>"
                            data-mime="<?= esc($saved['mime_type'] ?? ''); ?>">
                            <i class="fas fa-eye"></i> Lihat
                          </button>
                          <a href="<?= esc(base_url('sinadi/tracing-tb/preview-dokumen/' . (int) $saved['id_dokumen'] . '?download=1')); ?>" class="btn btn-outline-secondary btn-xs" title="Download">
                            <i class="fas fa-download"></i>
                          </a>
                        </div>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted">Kosong</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center align-middle">
                      <?php if ($canVerifyBkk) : ?>
                        <input type="checkbox" class="chk-verif-akun-<?= esc($kodeAkun); ?>" name="checklist[<?= esc($kodeDok); ?>]" value="1" <?= $chkBkk ? 'checked' : ''; ?> style="width: 17px; height: 17px; cursor: pointer;">
                      <?php else : ?>
                        <?= $chkBkk ? '<i class="fas fa-check-square text-success fa-lg"></i>' : '<i class="far fa-square text-muted"></i>'; ?>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <?php if ($canVerifyBkk) : ?>
                        <input type="text" name="catatan_dokumen[<?= esc($kodeDok); ?>]" class="form-control form-control-sm" placeholder="Catatan BKK..." value="<?= esc($noteBkk); ?>">
                      <?php else : ?>
                        <small><?= $noteBkk !== '' ? esc($noteBkk) : '<span class="text-muted">-</span>'; ?></small>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- BLOK TANDA TANGAN PENGAJU & PEMERIKSAAN VERIFIKATOR BKK -->
    <div class="row">
      <!-- Diajukan oleh (Pengaju) -->
      <div class="col-lg-5 mb-3">
        <div class="card card-secondary card-outline tb-detail-card shadow-sm h-100 mb-0">
          <div class="card-header bg-light py-2">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.9rem;">
              <i class="fas fa-signature text-secondary mr-1"></i> Diajukan oleh (Pengaju)
            </h3>
          </div>
          <div class="card-body">
            <table class="table table-sm table-borderless mb-0" style="font-size: 0.86rem;">
              <tr>
                <th width="34%">Nama Pengaju</th>
                <td>: <strong><?= esc($record['nama_pengaju_ttd'] ?: ($record['nama_pengaju'] ?: '-')); ?></strong></td>
              </tr>
              <tr>
                <th>Tanggal TTD</th>
                <td>: <?= !empty($record['tgl_ttd_pengaju']) ? esc(mediumdate_indo($record['tgl_ttd_pengaju'])) : (!empty($record['created_at']) ? esc(mediumdate_indo(substr((string) $record['created_at'], 0, 10))) : '-'); ?></td>
              </tr>
              <tr>
                <th>Catatan</th>
                <td>: <?= esc($record['catatan_pengaju'] ?: '-'); ?></td>
              </tr>
              <tr>
                <th>Tanda Tangan</th>
                <td>
                  <?php if (!empty($record['ttd_pengaju'])) : ?>
                    <div class="mt-1 p-2 border rounded bg-white d-inline-block">
                      <img src="<?= esc($record['ttd_pengaju']); ?>" alt="Tanda Tangan Pengaju" style="max-height: 95px; max-width: 100%;">
                    </div>
                  <?php else : ?>
                    <span class="badge badge-light border text-muted">Belum ditandatangani</span>
                  <?php endif; ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <!-- Diperiksa oleh Verifikator BKK -->
      <div class="col-lg-7 mb-3">
        <div class="card card-info card-outline tb-detail-card shadow-sm h-100 mb-0">
          <div class="card-header bg-light py-2">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.9rem;">
              <i class="fas fa-user-check text-info mr-1"></i> Diperiksa oleh Verifikator BKK
            </h3>
          </div>
          <div class="card-body">
            <?php if ($canVerifyBkk) : ?>
              <div class="form-group mb-2">
                <label class="small font-weight-bold mb-1">Keputusan Verifikasi BKK <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap" style="gap: 14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="bkkAcc" name="keputusan" value="ACC" <?= ($record['status_verif_bkk'] ?? '') === 'ACC' ? 'checked' : ''; ?> required>
                    <label for="bkkAcc" class="custom-control-label text-success font-weight-bold small">LENGKAP &amp; SESUAI (ACC)</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="bkkRev" name="keputusan" value="PERBAIKAN" <?= ($record['status_verif_bkk'] ?? '') === 'PERBAIKAN' ? 'checked' : ''; ?> required>
                    <label for="bkkRev" class="custom-control-label text-warning font-weight-bold small">TIDAK LENGKAP / TIDAK SESUAI (PERBAIKAN)</label>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4 mb-2">
                  <label class="small font-weight-bold mb-1">Tanggal</label>
                  <input type="date" name="tgl_verif" class="form-control form-control-sm" value="<?= esc($record['tgl_verif_bkk'] ?: date('Y-m-d')); ?>" required>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="small font-weight-bold mb-1">Nama Verifikator</label>
                  <input type="text" name="nama_verif" class="form-control form-control-sm" value="<?= esc($record['nama_verif_bkk'] ?: $ctx['nama']); ?>" required>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="small font-weight-bold mb-1">No HP</label>
                  <input type="text" name="no_hp_verif" class="form-control form-control-sm" placeholder="08xxxxxxxxxx" value="<?= esc($record['no_hp_verif_bkk'] ?: $ctx['no_hp']); ?>">
                </div>
              </div>
              <div class="form-group mb-2">
                <label class="small font-weight-bold mb-1">Catatan Umum Verifikator BKK</label>
                <textarea name="catatan_umum" rows="2" class="form-control form-control-sm" placeholder="Catatan ringkas hasil pemeriksaan..."><?= esc($record['catatan_verif_bkk'] ?? ''); ?></textarea>
              </div>
              <div class="form-group mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label class="small font-weight-bold mb-0">Paraf Verifikator BKK</label>
                  <button type="button" class="btn btn-xs btn-outline-secondary" id="btnClearParaf">
                    <i class="fas fa-eraser mr-1"></i> Bersihkan Paraf
                  </button>
                </div>
                <?php if (!empty($record['paraf_verif_bkk'])) : ?>
                  <div class="mb-2">
                    <small class="text-muted d-block">Paraf tersimpan (gambar ulang di bawah jika ingin mengganti):</small>
                    <img src="<?= esc($record['paraf_verif_bkk']); ?>" alt="Paraf BKK" style="max-height: 70px; border: 1px solid #dee2e6; background: #fff; padding: 4px;">
                  </div>
                <?php endif; ?>
                <div class="sig-pad-box">
                  <canvas id="canvasParafVerif"></canvas>
                </div>
                <input type="hidden" name="paraf_data" id="paraf_data">
              </div>
              <button type="submit" class="btn btn-info btn-sm btn-block font-weight-bold">
                <i class="fas fa-save mr-1"></i> Simpan Hasil Verifikasi BKK
              </button>
            <?php else : ?>
              <table class="table table-sm table-borderless mb-0" style="font-size: 0.86rem;">
                <tr>
                  <th width="34%">Hasil Pemeriksaan</th>
                  <td>:
                    <?php if (($record['status_verif_bkk'] ?? '') === 'ACC') : ?>
                      <span class="badge badge-success">LENGKAP &amp; SESUAI (ACC)</span>
                    <?php elseif (($record['status_verif_bkk'] ?? '') === 'PERBAIKAN') : ?>
                      <span class="badge badge-warning">TIDAK LENGKAP / TIDAK SESUAI (PERBAIKAN)</span>
                    <?php else : ?>
                      <span class="text-muted">Belum diperiksa</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr><th>Tanggal</th><td>: <?= !empty($record['tgl_verif_bkk']) ? esc(mediumdate_indo($record['tgl_verif_bkk'])) : '-'; ?></td></tr>
                <tr><th>Nama</th><td>: <?= esc($record['nama_verif_bkk'] ?: '-'); ?></td></tr>
                <tr><th>No HP</th><td>: <?= esc($record['no_hp_verif_bkk'] ?: '-'); ?></td></tr>
                <tr><th>Catatan</th><td>: <?= esc($record['catatan_verif_bkk'] ?: '-'); ?></td></tr>
                <tr>
                  <th>Paraf</th>
                  <td>
                    <?php if (!empty($record['paraf_verif_bkk'])) : ?>
                      <div class="mt-1 p-2 border rounded bg-white d-inline-block">
                        <img src="<?= esc($record['paraf_verif_bkk']); ?>" alt="Paraf BKK" style="max-height: 80px; max-width: 100%;">
                      </div>
                    <?php else : ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <?php if ($canVerifyBkk) : ?>
      </form>
    <?php endif; ?>

  </div>
</section>

<script src="/dist/js/signature_pad2.3.2.min.js"></script>
<script>
  $(function() {
    function escHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    // Centang semua dokumen per akun
    $('.btn-check-all-akun').on('click', function() {
      var akun = $(this).data('akun');
      var $checks = $('.chk-verif-akun-' + akun);
      var allChecked = $checks.length > 0 && $checks.filter(':checked').length === $checks.length;
      $checks.prop('checked', !allChecked);
    });

    // Preview Modal Dokumen
    $(document).on('click', '.btn-preview-doc', function() {
      var url = $(this).data('url');
      var name = $(this).data('name') || 'Pratinjau Dokumen';
      var mime = String($(this).data('mime') || '').toLowerCase();
      var lowerName = String(name).toLowerCase();
      var isImg = mime.indexOf('image/') === 0 || /\.(jpg|jpeg|png|webp|gif)$/.test(lowerName);
      var isPdf = mime === 'application/pdf' || /\.pdf$/.test(lowerName);

      var html = '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<strong>' + escHtml(name) + '</strong>' +
        '<a href="' + escHtml(url + '?download=1') + '" class="btn btn-sm btn-primary"><i class="fas fa-download mr-1"></i> Download</a>' +
        '</div>';

      if (isImg) {
        html += '<div class="text-center bg-light p-2 border rounded"><img src="' + escHtml(url) + '" class="img-fluid" style="max-height: 75vh;" alt="Preview"></div>';
      } else if (isPdf) {
        html += '<iframe class="tracing-detail-iframe" src="' + escHtml(url) + '" style="width: 100%; height: 78vh; border: 1px solid #dee2e6;"></iframe>';
      } else {
        html += '<div class="alert alert-info mb-0"><i class="fas fa-file mr-1"></i> Format file ini dapat dibuka dengan menekan tombol <strong>Download</strong>.</div>';
      }

      $('#modal-xl .modal-body').html(html);
      $('#modal-xl').modal('show');
    });

    $('#modal-xl').on('hidden.bs.modal', function() {
      if ($(this).find('.tracing-detail-iframe').length) {
        $(this).find('.modal-body').empty();
      }
    });

    // Signature Pad untuk Paraf Verifikator BKK
    var canvas = document.getElementById('canvasParafVerif');
    var sigPad = null;
    if (canvas && typeof SignaturePad !== 'undefined') {
      function resizeSigCanvas() {
        var data = sigPad ? sigPad.toData() : null;
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        if (sigPad) {
          sigPad.clear();
          if (data && data.length) {
            sigPad.fromData(data);
          }
        }
      }
      sigPad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: '#0f172a'
      });
      resizeSigCanvas();
      $(window).on('resize', resizeSigCanvas);

      $('#btnClearParaf').on('click', function() {
        sigPad.clear();
        $('#paraf_data').val('');
      });

      $('#formVerifikasiTracingTb').on('submit', function() {
        if (sigPad && !sigPad.isEmpty()) {
          $('#paraf_data').val(sigPad.toDataURL('image/png'));
        }
      });
    }
  });
</script>

<?= $this->endSection(); ?>
