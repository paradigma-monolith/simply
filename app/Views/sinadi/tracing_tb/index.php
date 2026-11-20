<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$statusBadgeMap = [
    'Draft'         => 'badge-secondary',
    'Diajukan'      => 'badge-primary',
    'Perbaikan BKK' => 'badge-warning',
    'ACC BKK'       => 'badge-success',
];
?>

<style>
  .tb-stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left-width: 4px;
    border-radius: 8px;
    padding: 10px 14px;
    text-decoration: none !important;
    color: #1e293b;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    height: 100%;
  }
  .tb-stat-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
    color: #0f172a;
  }
  .tb-stat-card.is-active {
    background: #f8fafc;
    box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.08);
  }
  .tb-stat-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
  }
  .tb-stat-left > div {
    min-width: 0;
  }
  .tb-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
  }
  .tb-stat-value {
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1.1;
  }
  .tb-stat-label {
    font-size: 0.76rem;
    color: #64748b;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .tb-stat-arrow {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-left: 8px;
    flex-shrink: 0;
  }
  .tb-stat-info { border-left-color: #17a2b8; }
  .tb-stat-info .tb-stat-icon { background: rgba(23, 162, 184, 0.12); color: #138496; }
  .tb-stat-primary { border-left-color: #007bff; }
  .tb-stat-primary .tb-stat-icon { background: rgba(0, 123, 255, 0.12); color: #0069d9; }
  .tb-stat-warning { border-left-color: #f59e0b; }
  .tb-stat-warning .tb-stat-icon { background: rgba(245, 158, 11, 0.14); color: #b45309; }
  .tb-stat-success { border-left-color: #28a745; }
  .tb-stat-success .tb-stat-icon { background: rgba(40, 167, 69, 0.12); color: #1e7e34; }
  .tb-flex-header::before,
  .tb-flex-header::after {
    display: none !important;
    content: none !important;
  }
</style>

<section class="content">
  <div class="container-fluid">

    <!-- Header Action Bar -->
    <div class="card card-outline card-teal shadow-sm mb-2">
      <div class="card-body py-2 px-3 d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
        <div>
          <h6 class="mb-0 font-weight-bold text-dark">
            <i class="fas fa-lungs-virus text-teal mr-2"></i>
            Tracing TB — Penemuan Kasus TBC Terintegrasi CKG
          </h6>
          <small class="text-muted">
            Kode: <strong>7956.QAA.061.051</strong> &bull; Checklist Verifikasi Kebenaran &amp; Kelengkapan Dokumen Pertanggungjawaban
          </small>
        </div>
        <div class="d-flex flex-wrap" style="gap: 6px;">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTemplateTracingTb">
            <i class="fas fa-file-download mr-1"></i> Template Dokumen
            <span class="badge badge-light ml-1"><?= count($templateFiles ?? []); ?></span>
          </button>
          <a href="/sinadi/tracing-tb/create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus-circle mr-1"></i> Input Form Tracing TB
          </a>
          <?php if (!empty($ctx['can_manage_verif'])) : ?>
            <a href="/sinadi/tracing-tb/verifikator" class="btn btn-outline-dark btn-sm">
              <i class="fas fa-user-shield mr-1"></i> Manajemen Verifikator
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Compact Summary Strip -->
    <div class="row mb-2">
      <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <a href="/sinadi/tracing-tb" class="tb-stat-card tb-stat-info <?= $statusFilter === '' ? 'is-active' : ''; ?>">
          <div class="tb-stat-left">
            <div class="tb-stat-icon"><i class="fas fa-folder-open"></i></div>
            <div class="min-width-0">
              <div class="tb-stat-value"><?= (int) $summary['total']; ?></div>
              <div class="tb-stat-label">Total Pengajuan</div>
            </div>
          </div>
          <i class="fas fa-chevron-right tb-stat-arrow"></i>
        </a>
      </div>
      <div class="col-xl-3 col-sm-6 mb-2 mb-xl-0">
        <a href="/sinadi/tracing-tb?status=Diajukan" class="tb-stat-card tb-stat-primary <?= $statusFilter === 'Diajukan' ? 'is-active' : ''; ?>">
          <div class="tb-stat-left">
            <div class="tb-stat-icon"><i class="fas fa-clipboard-check"></i></div>
            <div class="min-width-0">
              <div class="tb-stat-value"><?= (int) $summary['menunggu_bkk']; ?></div>
              <div class="tb-stat-label">Menunggu Verif BKK</div>
            </div>
          </div>
          <i class="fas fa-chevron-right tb-stat-arrow"></i>
        </a>
      </div>
      <div class="col-xl-3 col-sm-6 mb-2 mb-sm-0">
        <a href="/sinadi/tracing-tb?status=Perbaikan+BKK" class="tb-stat-card tb-stat-warning <?= $statusFilter === 'Perbaikan BKK' ? 'is-active' : ''; ?>">
          <div class="tb-stat-left">
            <div class="tb-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="min-width-0">
              <div class="tb-stat-value"><?= (int) $summary['perbaikan']; ?></div>
              <div class="tb-stat-label">Perlu Perbaikan BKK</div>
            </div>
          </div>
          <i class="fas fa-chevron-right tb-stat-arrow"></i>
        </a>
      </div>
      <div class="col-xl-3 col-sm-6">
        <a href="/sinadi/tracing-tb?status=ACC+BKK" class="tb-stat-card tb-stat-success <?= $statusFilter === 'ACC BKK' ? 'is-active' : ''; ?>">
          <div class="tb-stat-left">
            <div class="tb-stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="min-width-0">
              <div class="tb-stat-value"><?= (int) $summary['acc_bkk']; ?></div>
              <div class="tb-stat-label">ACC BKK (Lengkap)</div>
            </div>
          </div>
          <i class="fas fa-chevron-right tb-stat-arrow"></i>
        </a>
      </div>
    </div>

    <!-- Info Verifikator BKK Aktif -->
    <div class="callout callout-info py-1 px-3 mb-2 small">
      <strong><i class="fas fa-user-check text-info mr-1"></i> Verifikator BKK Aktif:</strong>
      <?php if (!empty($verifBkkNames)) : ?>
        <?= esc(implode(', ', $verifBkkNames)); ?>
      <?php else : ?>
        <span class="text-muted">Belum diatur</span>
      <?php endif; ?>
    </div>

    <!-- Filter & Daftar Pengajuan -->
    <div class="card shadow-sm">
      <div class="card-header bg-light tb-flex-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
        <h3 class="card-title float-none mb-0"><i class="fas fa-list-alt mr-1 text-primary"></i> Daftar Dokumen Tracing TB</h3>
        <div class="btn-group btn-group-sm flex-wrap" role="group">
          <?php
          $filters = [
              ''              => 'Semua',
              'Draft'         => 'Draft',
              'Diajukan'      => 'Diajukan',
              'Perbaikan BKK' => 'Perbaikan BKK',
              'ACC BKK'       => 'ACC BKK',
          ];
          foreach ($filters as $val => $lbl) :
              $active = ($statusFilter === $val) ? 'btn-primary' : 'btn-outline-secondary';
          ?>
            <a href="<?= $val === '' ? '/sinadi/tracing-tb' : '/sinadi/tracing-tb?status=' . urlencode($val); ?>" class="btn <?= $active; ?>">
              <?= esc($lbl); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table id="tableTracingTb" class="table table-bordered table-striped table-hover w-100">
            <thead class="thead-light">
              <tr class="text-center">
                <th width="4%">No</th>
                <th>Lokus &amp; Instansi</th>
                <th width="12%">Tanggal</th>
                <th width="16%">Total Realisasi</th>
                <th width="14%">Dokumen</th>
                <th width="14%">Verifikator BKK</th>
                <th width="12%">Status</th>
                <th width="13%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($rows as $r) :
                $st = (string) ($r['status_pengajuan'] ?? 'Draft');
                $badgeCls = $statusBadgeMap[$st] ?? 'badge-secondary';
                $uploaded = (int) ($r['doc_stats']['uploaded'] ?? 0);
                $pct = $totalDocCatalog > 0 ? min(100, (int) round(($uploaded / $totalDocCatalog) * 100)) : 0;
              ?>
                <tr>
                  <td class="text-center align-middle"><?= $no++; ?></td>
                  <td class="align-middle">
                    <div class="font-weight-bold text-dark"><?= esc($r['lokus']); ?></div>
                    <small class="text-muted d-block"><?= esc($r['instansi']); ?></small>
                    <small class="text-muted">
                      <i class="fas fa-user mr-1"></i><?= esc($r['nama_pengaju_ttd'] ?: ($r['nama_pengaju'] ?: '-')); ?>
                      <?php if (!empty($r['ttd_pengaju'])) : ?>
                        <span class="badge badge-success ml-1" title="Sudah ditandatangani pengaju"><i class="fas fa-signature"></i> TTD</span>
                      <?php else : ?>
                        <span class="badge badge-light border text-muted ml-1" title="Belum ada tanda tangan pengaju">Belum TTD</span>
                      <?php endif; ?>
                      <?php if (!empty($r['non_radiografer'])) : ?>
                        <span class="badge badge-light border ml-1">Non Radiografer</span>
                      <?php endif; ?>
                    </small>
                  </td>
                  <td class="text-center align-middle text-nowrap">
                    <?= !empty($r['tanggal_kegiatan']) ? esc(mediumdate_indo($r['tanggal_kegiatan'])) : '-'; ?>
                  </td>
                  <td class="text-right align-middle text-nowrap">
                    <strong>Rp <?= number_format((int) $r['total_anggaran'], 0, ',', '.'); ?></strong>
                    <div class="small text-muted">
                      522151: <?= number_format((int) $r['total_jasa_profesi'], 0, ',', '.'); ?><br>
                      524111: <?= number_format((int) $r['total_perjalanan_dinas'], 0, ',', '.'); ?><br>
                      521211: <?= number_format((int) $r['total_belanja_bahan'], 0, ',', '.'); ?>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex justify-content-between small mb-1">
                      <span>Terunggah</span>
                      <strong><?= $uploaded; ?>/<?= $totalDocCatalog; ?></strong>
                    </div>
                    <div class="progress progress-xs">
                      <div class="progress-bar bg-teal" style="width: <?= $pct; ?>%"></div>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <?php if (($r['status_verif_bkk'] ?? '') === 'ACC') : ?>
                      <span class="badge badge-success"><i class="fas fa-check-double mr-1"></i>ACC</span>
                      <div class="small text-muted mt-1"><?= esc($r['nama_verif_bkk'] ?? ''); ?></div>
                    <?php elseif (($r['status_verif_bkk'] ?? '') === 'PERBAIKAN') : ?>
                      <span class="badge badge-warning"><i class="fas fa-exclamation-circle mr-1"></i>Perbaikan</span>
                      <div class="small text-muted mt-1"><?= esc($r['nama_verif_bkk'] ?? ''); ?></div>
                    <?php else : ?>
                      <span class="badge badge-light border text-muted">Belum</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge <?= $badgeCls; ?>"><?= esc($st); ?></span>
                  </td>
                  <td class="text-center align-middle text-nowrap">
                    <a href="/sinadi/tracing-tb/detail/<?= (int) $r['id_tracing_tb']; ?>" class="btn btn-info btn-xs" title="Detail &amp; Verifikasi">
                      <i class="fas fa-clipboard-list"></i>
                    </a>
                    <?php if (!empty($r['can_edit'])) : ?>
                      <a href="/sinadi/tracing-tb/edit/<?= (int) $r['id_tracing_tb']; ?>" class="btn btn-warning btn-xs" title="Edit &amp; Upload Dokumen">
                        <i class="fas fa-edit"></i>
                      </a>
                    <?php endif; ?>
                    <a href="/sinadi/tracing-tb/pdf/<?= (int) $r['id_tracing_tb']; ?>" class="btn btn-outline-danger btn-xs" title="Download PDF Form Checklist">
                      <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="/sinadi/tracing-tb/cetak/<?= (int) $r['id_tracing_tb']; ?>" target="_blank" class="btn btn-secondary btn-xs" title="Cetak Form Checklist">
                      <i class="fas fa-print"></i>
                    </a>
                    <?php if (!empty($r['can_edit'])) : ?>
                      <form action="/sinadi/tracing-tb/hapus/<?= (int) $r['id_tracing_tb']; ?>" method="post" class="d-inline form-hapus-tracing">
                        <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</section>

<?= $this->include('sinadi/tracing_tb/_template_modal'); ?>

<script>
  $(function() {
    $('#tableTracingTb').DataTable({
      autoWidth: false,
      pageLength: 25,
      order: [[0, 'asc']],
      language: {
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_ data',
        zeroRecords: 'Belum ada data pengajuan Tracing TB',
        emptyTable: 'Belum ada data pengajuan Tracing TB',
        info: 'Menampilkan _START_ s.d. _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 data',
        paginate: { previous: '&laquo;', next: '&raquo;' }
      }
    });

    $(document).on('submit', '.form-hapus-tracing', function(e) {
      e.preventDefault();
      var form = this;
      Swal.fire({
        title: 'Hapus data Tracing TB?',
        text: 'Seluruh file dokumen yang telah diunggah pada pengajuan ini juga akan ikut dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then(function(result) {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>

<?= $this->endSection(); ?>
