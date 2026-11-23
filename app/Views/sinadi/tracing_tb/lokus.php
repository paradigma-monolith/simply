<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .tb-flex-header::before,
  .tb-flex-header::after {
    display: none !important;
    content: none !important;
  }
</style>

<section class="content">
  <div class="container-fluid">

    <div class="mb-2 d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
      <div class="d-flex flex-wrap" style="gap: 6px;">
        <a href="/sinadi/tracing-tb" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Tracing TB
        </a>
        <a href="/sinadi/tracing-tb/verifikator" class="btn btn-outline-dark btn-sm">
          <i class="fas fa-user-shield mr-1"></i> Manajemen Verifikator
        </a>
      </div>
      <span class="badge badge-primary p-2">
        <i class="fas fa-map-marker-alt mr-1"></i> Pengaturan Lokus Tracing TB (<?= (int) ($activeCount ?? 0); ?> Aktif / <?= count($lokusRows); ?> Total)
      </span>
    </div>

    <div class="callout callout-info py-2 px-3 mb-3 small">
      <strong>Informasi Pengaturan Lokus:</strong>
      Daftar lokus berstatus <strong>Aktif</strong> akan otomatis tampil pada dropdown <em>Lokus Kegiatan</em> di form input &amp; edit Tracing TB sesuai nomor urutan. Menghapus lokus dari pengaturan tidak akan menghapus riwayat pengajuan yang sudah ada.
    </div>

    <div class="row">
      <!-- Form Tambah Lokus -->
      <div class="col-lg-4 mb-3">
        <div class="card card-primary card-outline shadow-sm mb-0">
          <div class="card-header bg-light py-2">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.92rem;">
              <i class="fas fa-plus-circle text-primary mr-1"></i> Tambah Lokus Baru
            </h3>
          </div>
          <div class="card-body">
            <form action="/sinadi/tracing-tb/lokus/tambah" method="post">
              <div class="form-group mb-2">
                <label class="small font-weight-bold mb-1" for="add_nama_lokus">Nama Lokus Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="nama_lokus" id="add_nama_lokus" class="form-control form-control-sm"
                  placeholder="Contoh: Puskesmas Palabuhanratu I" maxlength="255" required
                  value="<?= esc(old('nama_lokus', '')); ?>">
              </div>
              <div class="form-row">
                <div class="col-6 form-group mb-3">
                  <label class="small font-weight-bold mb-1" for="add_urutan">Urutan Tampil</label>
                  <input type="number" name="urutan" id="add_urutan" class="form-control form-control-sm" min="1"
                    value="<?= (int) old('urutan', $nextUrutan ?? 1); ?>">
                </div>
                <div class="col-6 form-group mb-3">
                  <label class="small font-weight-bold mb-1" for="add_is_active">Status</label>
                  <select name="is_active" id="add_is_active" class="form-control form-control-sm">
                    <option value="1" selected>Aktif</option>
                    <option value="0">Nonaktif</option>
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                <i class="fas fa-save mr-1"></i> Simpan Lokus
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Daftar Master Lokus -->
      <div class="col-lg-8 mb-3">
        <div class="card card-teal card-outline shadow-sm mb-0">
          <div class="card-header bg-light py-2 tb-flex-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.92rem;">
              <i class="fas fa-list-ol text-teal mr-1"></i> Daftar Lokus Tracing TB
            </h3>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <input type="text" id="filterLokusInput" class="form-control form-control-sm" style="width: 210px;" placeholder="Cari nama lokus...">
              <span class="badge badge-info" id="badgeLokusCount"><?= count($lokusRows); ?> Lokus</span>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 70vh;">
              <table class="table table-sm table-bordered table-hover mb-0" id="tblLokusTracingTb">
                <thead class="thead-light" style="position: sticky; top: 0; z-index: 2;">
                  <tr>
                    <th width="8%" class="text-center">Urut</th>
                    <th>Nama Lokus</th>
                    <th width="14%" class="text-center">Pengajuan</th>
                    <th width="12%" class="text-center">Status</th>
                    <th width="16%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($lokusRows)) : ?>
                    <?php foreach ($lokusRows as $row) : ?>
                      <?php
                        $idLok = (int) $row['id_lokus'];
                        $isAktif = ((int) ($row['is_active'] ?? 1) === 1);
                        $usage = (int) ($row['usage_count'] ?? 0);
                      ?>
                      <tr class="lokus-row" data-name="<?= esc(mb_strtolower((string) $row['nama_lokus']), 'attr'); ?>">
                        <td class="text-center align-middle font-weight-bold text-muted"><?= (int) $row['urutan']; ?></td>
                        <td class="align-middle font-weight-bold text-dark"><?= esc($row['nama_lokus']); ?></td>
                        <td class="text-center align-middle">
                          <?php if ($usage > 0) : ?>
                            <span class="badge badge-info"><?= $usage; ?> data</span>
                          <?php else : ?>
                            <span class="text-muted small">0</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                          <?php if ($isAktif) : ?>
                            <span class="badge badge-success">Aktif</span>
                          <?php else : ?>
                            <span class="badge badge-secondary">Nonaktif</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-center align-middle text-nowrap">
                          <button type="button"
                            class="btn btn-warning btn-xs btn-edit-lokus"
                            data-id="<?= $idLok; ?>"
                            data-nama="<?= esc($row['nama_lokus'], 'attr'); ?>"
                            data-urutan="<?= (int) $row['urutan']; ?>"
                            data-active="<?= $isAktif ? '1' : '0'; ?>"
                            data-usage="<?= $usage; ?>"
                            title="Edit Lokus">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="/sinadi/tracing-tb/lokus/hapus/<?= $idLok; ?>" method="post" class="d-inline"
                            onsubmit="return confirm('Hapus lokus &quot;<?= esc($row['nama_lokus'], 'js'); ?>&quot;?<?= $usage > 0 ? ' Lokus ini tercatat pada ' . $usage . ' pengajuan lama (data pengajuan lama tetap aman).' : ''; ?>');">
                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus Lokus">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">Belum ada lokus terdaftar. Tambahkan lokus baru di formulir sebelah kiri.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Modal Edit Lokus -->
<div class="modal fade" id="modalEditLokus" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="formEditLokus" action="" method="post" class="modal-content">
      <div class="modal-header bg-warning py-2">
        <h5 class="modal-title font-weight-bold text-dark" style="font-size: 0.95rem;">
          <i class="fas fa-edit mr-1"></i> Edit Lokus Tracing TB
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-2">
          <label class="small font-weight-bold mb-1" for="edit_nama_lokus">Nama Lokus <span class="text-danger">*</span></label>
          <input type="text" name="nama_lokus" id="edit_nama_lokus" class="form-control form-control-sm" maxlength="255" required>
        </div>
        <div class="form-row">
          <div class="col-6 form-group mb-2">
            <label class="small font-weight-bold mb-1" for="edit_urutan">Urutan Tampil</label>
            <input type="number" name="urutan" id="edit_urutan" class="form-control form-control-sm" min="1" required>
          </div>
          <div class="col-6 form-group mb-2">
            <label class="small font-weight-bold mb-1" for="edit_is_active">Status</label>
            <select name="is_active" id="edit_is_active" class="form-control form-control-sm">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="custom-control custom-checkbox mt-2" id="editSyncWrap" style="display: none;">
          <input type="checkbox" class="custom-control-input" id="edit_sync_records" name="sync_records" value="1" checked>
          <label class="custom-control-label small" for="edit_sync_records">
            Perbarui juga nama lokus pada <strong id="editUsageCount">0</strong> pengajuan Tracing TB yang sudah tersimpan
          </label>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning btn-sm font-weight-bold">
          <i class="fas fa-save mr-1"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  $(function() {
    $('#filterLokusInput').on('input', function() {
      var q = $.trim($(this).val() || '').toLowerCase();
      var visible = 0;
      $('#tblLokusTracingTb tbody tr.lokus-row').each(function() {
        var name = String($(this).data('name') || '');
        var match = (q === '' || name.indexOf(q) !== -1);
        $(this).toggle(match);
        if (match) visible++;
      });
      $('#badgeLokusCount').text(visible + ' Lokus');
    });

    $(document).on('click', '.btn-edit-lokus', function() {
      var $btn = $(this);
      var id = $btn.data('id');
      var nama = $btn.data('nama');
      var urutan = $btn.data('urutan');
      var active = String($btn.data('active'));
      var usage = parseInt($btn.data('usage') || 0, 10);

      $('#formEditLokus').attr('action', '/sinadi/tracing-tb/lokus/update/' + id);
      $('#edit_nama_lokus').val(nama);
      $('#edit_urutan').val(urutan);
      $('#edit_is_active').val(active);

      if (usage > 0) {
        $('#editUsageCount').text(usage);
        $('#edit_sync_records').prop('checked', true);
        $('#editSyncWrap').show();
      } else {
        $('#edit_sync_records').prop('checked', false);
        $('#editSyncWrap').hide();
      }

      $('#modalEditLokus').modal('show');
    });
  });
</script>

<?= $this->endSection(); ?>
