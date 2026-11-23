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
        <a href="/sinadi/tracing-tb/lokus" class="btn btn-outline-primary btn-sm">
          <i class="fas fa-map-marker-alt mr-1"></i> Pengaturan Lokus
        </a>
      </div>
      <span class="badge badge-dark p-2">
        <i class="fas fa-user-shield mr-1"></i> Manajemen Verifikator BKK Tracing TB
      </span>
    </div>

    <div class="callout callout-info py-2 px-3 mb-3 small">
      <strong>Alur Verifikasi Tracing TB:</strong>
      <strong>Verifikator BKK</strong> memeriksa kebenaran &amp; kelengkapan dokumen pengajuan (checklist dokumen, catatan perbaikan, tanggal, nama, no HP, dan paraf) lalu menetapkan keputusan <em>LENGKAP &amp; SESUAI (ACC BKK)</em> atau <em>PERBAIKAN BKK</em>.
    </div>

    <div class="row">
      <!-- Form Tambah Verifikator BKK -->
      <div class="col-lg-5 mb-3">
        <div class="card card-info card-outline shadow-sm mb-0">
          <div class="card-header bg-light py-2">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.92rem;">
              <i class="fas fa-user-plus text-info mr-1"></i> Tambah Verifikator BKK
            </h3>
          </div>
          <div class="card-body">
            <form action="/sinadi/tracing-tb/verifikator/tambah" method="post">
              <input type="hidden" name="peran" value="verifikator_bkk">
              <div class="form-group mb-2">
                <label class="small font-weight-bold mb-1">Pilih Pegawai <span class="text-danger">*</span></label>
                <select name="id_user" class="form-control select2" style="width: 100%;" required>
                  <option value="">-- Pilih Pegawai --</option>
                  <?php foreach ($users as $u) : ?>
                    <option value="<?= (int) $u['id_user']; ?>">
                      <?= esc($u['nama']); ?><?= !empty($u['nip']) ? ' (' . esc($u['nip']) . ')' : ''; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group mb-3">
                <label class="small font-weight-bold mb-1">No HP Verifikator BKK <small class="text-muted font-weight-normal">(opsional, otomatis terisi saat verifikasi)</small></label>
                <input type="text" name="no_hp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
              </div>
              <button type="submit" class="btn btn-info btn-sm btn-block font-weight-bold">
                <i class="fas fa-user-check mr-1"></i> Simpan Verifikator BKK
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Daftar Verifikator BKK Aktif -->
      <div class="col-lg-7 mb-3">
        <div class="card card-teal card-outline shadow-sm mb-0">
          <div class="card-header bg-light py-2 tb-flex-header d-flex justify-content-between align-items-center">
            <h3 class="card-title float-none font-weight-bold mb-0" style="font-size: 0.92rem;">
              <i class="fas fa-users text-teal mr-1"></i> Daftar Verifikator BKK Aktif
            </h3>
            <span class="badge badge-info"><?= count($verifBkk); ?> Pegawai</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th width="8%" class="text-center">No</th>
                    <th>Nama / NIP</th>
                    <th width="26%">No HP</th>
                    <th width="14%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($verifBkk)) : ?>
                    <?php $n = 1; foreach ($verifBkk as $v) : ?>
                      <tr>
                        <td class="text-center align-middle"><?= $n++; ?></td>
                        <td class="align-middle">
                          <strong><?= esc($v['nama'] ?: $v['username']); ?></strong>
                          <?php if (!empty($v['nip'])) : ?>
                            <div class="small text-muted">NIP. <?= esc($v['nip']); ?></div>
                          <?php endif; ?>
                        </td>
                        <td class="align-middle"><?= esc($v['no_hp'] ?: ($v['pegawai_no_hp'] ?: '-')); ?></td>
                        <td class="text-center align-middle">
                          <form action="/sinadi/tracing-tb/verifikator/hapus/<?= (int) $v['id_verifikator']; ?>" method="post" onsubmit="return confirm('Hapus pegawai ini dari Verifikator BKK?')">
                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted py-4">Belum ada Verifikator BKK terdaftar.</td>
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

<?= $this->endSection(); ?>
