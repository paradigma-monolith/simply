<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$isEdit = ($mode === 'edit');
$idRecord = (int) ($record['id_tracing_tb'] ?? 0);
$nonRadiografer = !empty($record['non_radiografer']);
$totalDokumenAll = 0;
$uploadedCountInit = 0;
foreach ($accounts as $ak) {
    foreach ($ak['dokumen'] as $d) {
        $totalDokumenAll++;
        if (!empty($dokumenMap[$d['kode']]['nama_file'])) {
            $uploadedCountInit++;
        }
    }
}
?>

<style>
  .tracing-budget-input {
    min-width: 68px;
    text-align: center;
  }
  .tracing-harga-input {
    min-width: 110px;
    text-align: right;
  }
  .doc-row-radiografer.is-muted {
    opacity: 0.55;
    background-color: #f8f9fa;
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
    <div class="mb-3"><i class="fas fa-cloud-upload-alt fa-3x text-primary fa-bounce"></i></div>
    <h5 id="tracingUploadTitle" class="font-weight-bold mb-1">Menyimpan Data Tracing TB...</h5>
    <p id="tracingUploadStatus" class="text-muted small mb-3">Mohon tunggu, jangan tutup halaman.</p>
    <div class="progress">
      <div id="tracingUploadBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 15%"></div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
      <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
        <a href="/sinadi/tracing-tb" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Tracing TB
        </a>
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTemplateTracingTb">
          <i class="fas fa-file-download mr-1"></i> Download Template Dokumen
          <span class="badge badge-light ml-1"><?= count($templateFiles ?? []); ?></span>
        </button>
        <?php if ($isEdit) : ?>
          <a href="/sinadi/tracing-tb/detail/<?= $idRecord; ?>" class="btn btn-outline-info btn-sm">
            <i class="fas fa-clipboard-check mr-1"></i> Lihat Detail / Verifikasi
          </a>
        <?php endif; ?>
      </div>
      <div class="text-right">
        <span class="badge badge-info p-2">
          <i class="fas fa-file-upload mr-1"></i>
          Dokumen Terunggah: <span id="counterUploadedDocs"><?= $uploadedCountInit; ?></span> / <?= $totalDokumenAll; ?>
        </span>
      </div>
    </div>

    <form id="formTracingTb" action="/sinadi/tracing-tb/save" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_tracing_tb" id="id_tracing_tb" value="<?= $idRecord; ?>">
      <input type="hidden" name="submit_action" id="submit_action" value="draft">

      <!-- 1. IDENTITAS KEGIATAN -->
      <div class="card card-primary card-outline shadow-sm">
        <div class="card-header bg-light">
          <h3 class="card-title font-weight-bold">
            <i class="fas fa-info-circle text-primary mr-2"></i>
            Identitas Kegiatan — Penemuan Kasus TBC Terintegrasi CKG
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokus Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="lokus" id="lokus" class="form-control" required
                  placeholder="Contoh: Puskesmas / Pelabuhan / Bandara / Kecamatan ..."
                  value="<?= esc(old('lokus', $record['lokus'] ?? '')); ?>">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Balai / Besar / Loka Kekarantinaan Kesehatan <span class="text-danger">*</span></label>
                <input type="text" name="instansi" id="instansi" class="form-control" required
                  value="<?= esc(old('instansi', $record['instansi'] ?? 'Balai Kekarantinaan Kesehatan Kelas I Bandung')); ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" class="form-control" required
                  value="<?= esc(old('tanggal_kegiatan', $record['tanggal_kegiatan'] ?? date('Y-m-d'))); ?>">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Kode Kegiatan / Output</label>
                <input type="text" name="kode_kegiatan" class="form-control"
                  value="<?= esc(old('kode_kegiatan', $record['kode_kegiatan'] ?? '7956.QAA.061.051')); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Output / Komponen Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-control"
                  value="<?= esc(old('nama_kegiatan', $record['nama_kegiatan'] ?? 'Masyarakat Mendapatkan Layanan Skrining TBC - Penemuan Kasus TBC Terintegrasi CKG')); ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Total Realisasi (Auto)</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text font-weight-bold">Rp</span></div>
                  <input type="text" id="grandTotalDisplay" class="form-control font-weight-bold text-right text-success bg-light" readonly
                    value="<?= number_format((int) $rincian['grand_total'], 0, ',', '.'); ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="row align-items-center">
            <div class="col-md-8">
              <div class="form-group mb-0">
                <label>Catatan Tambahan Pengaju <small class="text-muted">(opsional)</small></label>
                <input type="text" name="catatan_pengaju" class="form-control"
                  placeholder="Contoh: Keterangan lokus / catatan khusus untuk verifikator..."
                  value="<?= esc(old('catatan_pengaju', $record['catatan_pengaju'] ?? '')); ?>">
              </div>
            </div>
            <div class="col-md-4 pt-md-4">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="toggleNonRadiografer" name="non_radiografer" value="1" <?= $nonRadiografer ? 'checked' : ''; ?>>
                <label class="custom-control-label font-weight-bold text-secondary" for="toggleNonRadiografer">
                  Non Radiografer (kosongkan volume &amp; dokumen radiografer)
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. TIGA AKUN BELANJA + UPLOAD DOKUMEN SESUAI EXCEL -->
      <?php foreach ($accounts as $kodeAkun => $akun) : ?>
        <div class="card card-<?= esc($akun['badge_color']); ?> card-outline shadow-sm">
          <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
            <div>
              <h3 class="card-title float-none d-block font-weight-bold mb-0">
                <span class="badge badge-<?= esc($akun['badge_color']); ?> mr-2"><?= esc($kodeAkun); ?></span>
                <?= esc($akun['nama']); ?>
              </h3>
              <div class="small text-muted mt-1"><?= esc($akun['catatan_anggaran']); ?></div>
            </div>
            <div class="text-right">
              <small class="text-muted d-block">Subtotal Akun <?= esc($kodeAkun); ?></small>
              <strong class="h6 text-<?= esc($akun['badge_color']); ?> mb-0">
                Rp <span class="akun-subtotal" data-akun="<?= esc($kodeAkun); ?>"><?= number_format((int) ($rincian['subtotals'][$kodeAkun] ?? 0), 0, ',', '.'); ?></span>
              </strong>
            </div>
          </div>

          <div class="card-body">
            <!-- Rincian Realisasi Volume & Biaya -->
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-calculator mr-1 text-<?= esc($akun['badge_color']); ?>"></i>
              Rincian Volume &amp; Realisasi Biaya
            </h6>
            <div class="table-responsive mb-4">
              <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light text-center">
                  <tr>
                    <th width="24%">Uraian</th>
                    <th width="13%">Vol 1</th>
                    <th width="13%">Vol 2</th>
                    <th width="13%">Vol 3</th>
                    <th width="10%">Total Vol</th>
                    <th width="14%">Harga Satuan (Rp)</th>
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
                      <td class="align-middle">
                        <strong><?= esc($item['nama']); ?></strong>
                        <div class="small text-muted"><?= esc($item['catatan']); ?></div>
                      </td>
                      <td class="align-middle">
                        <div class="input-group input-group-sm">
                          <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_or]"
                            class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                            data-default="<?= esc($item['vol_or']); ?>"
                            value="<?= esc($val['vol_or']); ?>">
                          <div class="input-group-append"><span class="input-group-text"><?= esc($item['satuan_or']); ?></span></div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <div class="input-group input-group-sm">
                          <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_waktu]"
                            class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                            data-default="<?= esc($item['vol_waktu']); ?>"
                            value="<?= esc($val['vol_waktu']); ?>">
                          <div class="input-group-append"><span class="input-group-text"><?= esc($item['satuan_waktu']); ?></span></div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <div class="input-group input-group-sm">
                          <input type="number" step="any" min="0" name="anggaran[<?= esc($k); ?>][vol_lokus]"
                            class="form-control tracing-budget-input calc-trigger <?= $isRadioRow ? 'radio-vol-input' : ''; ?>"
                            data-default="<?= esc($item['vol_lokus']); ?>"
                            value="<?= esc($val['vol_lokus']); ?>">
                          <div class="input-group-append"><span class="input-group-text"><?= esc($item['satuan_lokus']); ?></span></div>
                        </div>
                      </td>
                      <td class="text-center align-middle text-nowrap">
                        <strong class="row-total-vol"><?= esc($val['total_vol']); ?></strong> <?= esc($item['satuan_total']); ?>
                      </td>
                      <td class="align-middle">
                        <input type="number" min="0" step="100" name="anggaran[<?= esc($k); ?>][harga_satuan]"
                          class="form-control form-control-sm tracing-harga-input calc-trigger"
                          value="<?= (int) $val['harga_satuan']; ?>">
                      </td>
                      <td class="text-right align-middle text-nowrap font-weight-bold">
                        Rp <span class="row-subtotal"><?= number_format((int) $val['subtotal'], 0, ',', '.'); ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Upload Dokumen Klaim dan Pertanggungjawaban -->
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-file-upload mr-1 text-<?= esc($akun['badge_color']); ?>"></i>
              Kebutuhan Dokumen Klaim dan Pertanggungjawaban (Akun <?= esc($kodeAkun); ?>)
              <small class="text-muted font-weight-normal ml-1">— Format: PDF, JPG, PNG, XLSX, DOCX (maks 15 MB/file)</small>
            </h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th width="4%" class="text-center">No</th>
                    <th width="15%">Sifat</th>
                    <th width="39%">Nama Dokumen yang Diminta</th>
                    <th width="27%">Upload File Dokumen</th>
                    <th width="15%" class="text-center">Status File</th>
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
                    <tr class="doc-row <?= $isRadioDoc ? 'doc-row-radiografer' . ($nonRadiografer ? ' is-muted' : '') : ''; ?>" data-kode="<?= esc($kodeDok); ?>">
                      <td class="text-center align-middle"><?= $docNo++; ?></td>
                      <td class="align-middle">
                        <span class="badge <?= $katBadge; ?>"><?= esc($kat); ?></span>
                      </td>
                      <td class="align-middle">
                        <?= esc($doc['nama']); ?>
                      </td>
                      <td class="align-middle">
                        <div class="custom-file custom-file-sm">
                          <input type="file"
                            class="custom-file-input tracing-doc-input"
                            id="file_<?= esc($kodeDok); ?>"
                            name="dokumen[<?= esc($kodeDok); ?>]"
                            data-kode="<?= esc($kodeDok); ?>"
                            accept=".pdf,.jpg,.jpeg,.png,.webp,.xls,.xlsx,.doc,.docx">
                          <label class="custom-file-label text-truncate" for="file_<?= esc($kodeDok); ?>" data-browse="Pilih">
                            <?= $hasFile ? esc($savedDoc['nama_file_asli']) : 'Pilih file...'; ?>
                          </label>
                        </div>
                      </td>
                      <td class="text-center align-middle doc-status-cell" id="status_<?= esc($kodeDok); ?>">
                        <?php if ($hasFile) : ?>
                          <div class="btn-group btn-group-xs">
                            <button type="button"
                              class="btn btn-info btn-xs btn-preview-doc"
                              data-url="<?= esc(base_url('sinadi/tracing-tb/preview-dokumen/' . (int) $savedDoc['id_dokumen'])); ?>"
                              data-name="<?= esc($savedDoc['nama_file_asli']); ?>"
                              data-mime="<?= esc($savedDoc['mime_type'] ?? ''); ?>"
                              title="Pratinjau File">
                              <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button type="button"
                              class="btn btn-danger btn-xs btn-delete-doc"
                              data-id-doc="<?= (int) $savedDoc['id_dokumen']; ?>"
                              data-kode="<?= esc($kodeDok); ?>"
                              title="Hapus File">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                          <div class="small text-success mt-1"><i class="fas fa-check-circle"></i> Terunggah</div>
                        <?php else : ?>
                          <span class="badge badge-light border text-muted">Belum ada</span>
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

      <!-- Tombol Simpan -->
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
          <div class="text-muted small">
            <i class="fas fa-info-circle text-info mr-1"></i>
            Anda dapat menyimpan sebagai <strong>Draft</strong> terlebih dahulu atau langsung <strong>Simpan &amp; Ajukan ke Verifikator BKK</strong>.
          </div>
          <div class="d-flex flex-wrap" style="gap: 8px;">
            <button type="button" class="btn btn-secondary btn-submit-tracing" data-action="draft">
              <i class="fas fa-save mr-1"></i> Simpan Draft
            </button>
            <button type="button" class="btn btn-success btn-submit-tracing" data-action="ajukan">
              <i class="fas fa-paper-plane mr-1"></i> Simpan &amp; Ajukan Verifikasi
            </button>
          </div>
        </div>
      </div>
    </form>

  </div>
</section>

<?= $this->include('sinadi/tracing_tb/_template_modal'); ?>

<script>
  $(function() {
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
      $('#grandTotalDisplay').val(formatRupiah(grandTotal));
    }

    function updateUploadedCounter() {
      var count = $('.doc-status-cell .btn-preview-doc').length;
      $('#counterUploadedDocs').text(count);
    }

    $(document).on('input change', '.calc-trigger', recalcAll);

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

    function renderDocUploadedCell($cell, kode, dok, previewUrl) {
      var html = '<div class="btn-group btn-group-xs">' +
        '<button type="button" class="btn btn-info btn-xs btn-preview-doc" data-url="' + escHtml(previewUrl) + '" data-name="' + escHtml(dok.nama_file_asli) + '" data-mime="' + escHtml(dok.mime_type || '') + '" title="Pratinjau File">' +
        '<i class="fas fa-eye"></i> Lihat</button>' +
        '<button type="button" class="btn btn-danger btn-xs btn-delete-doc" data-id-doc="' + dok.id_dokumen + '" data-kode="' + escHtml(kode) + '" title="Hapus File">' +
        '<i class="fas fa-trash"></i></button>' +
        '</div>' +
        '<div class="small text-success mt-1"><i class="fas fa-check-circle"></i> Terunggah</div>';
      $cell.html(html);
      updateUploadedCounter();
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

    // Jika mode edit (id_tracing_tb > 0), langsung upload instan per file lewat AJAX
    $(document).on('change', '.tracing-doc-input', function() {
      var input = this;
      var kode = $(this).data('kode');
      var file = input.files && input.files[0] ? input.files[0] : null;
      var $label = $(this).next('.custom-file-label');
      var $cell = $('#status_' + kode);

      if (!file) {
        return;
      }

      if (file.size > 15 * 1024 * 1024) {
        Swal.fire('Ukuran Terlalu Besar', 'Maksimal ukuran file adalah 15 MB.', 'warning');
        input.value = '';
        return;
      }

      $label.text(file.name);

      var currentId = parseInt($('#id_tracing_tb').val(), 10) || 0;
      if (currentId > 0) {
        $cell.html('<span class="text-primary small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</span>');
        uploadSingleFile(currentId, kode, file)
          .done(function(res) {
            if (res && res.status === 'success') {
              renderDocUploadedCell($cell, kode, res.dokumen, res.preview_url);
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
        $cell.html('<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Siap diunggah</span>');
      }
    });

    // Hapus file dokumen
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
            $cell.html('<span class="badge badge-light border text-muted">Belum ada</span>');
            $('#file_' + kode).val('').next('.custom-file-label').text('Pilih file...');
            updateUploadedCounter();
          } else {
            Swal.fire('Gagal', (res && res.message) || 'Gagal menghapus file.', 'error');
          }
        }, 'json').fail(function() {
          Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus file.', 'error');
        });
      });
    });

    // Modal Preview File Dokumen
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

    // Submit Form: simpan data teks/anggaran via AJAX dulu, lalu unggah file terpilih satu-per-satu (bebas batas max_file_uploads)
    $('.btn-submit-tracing').on('click', function() {
      var actionType = $(this).data('action');
      $('#submit_action').val(actionType);

      var formEl = document.getElementById('formTracingTb');
      if (!formEl.checkValidity()) {
        formEl.reportValidity();
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

      // Buat FormData teks/anggaran saja (tanpa file agar aman dari limit)
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
  });
</script>

<?= $this->endSection(); ?>
