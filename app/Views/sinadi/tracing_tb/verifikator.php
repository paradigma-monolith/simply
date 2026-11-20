<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
      <a href="/sinadi/tracing-tb" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Tracing TB
      </a>
      <span class="badge badge-dark p-2">
        <i class="fas fa-user-shield mr-1"></i> Pengaturan Verifikator Khusus Tracing TB
      </span>
    </div>

    <div class="callout callout-info">
      <strong>Alur Verifikasi Tracing TB:</strong>
      <ol class="mb-0 pl-3 mt-1">
        <li><strong>Verifikator BKK</strong> memeriksa kebenaran &amp; kelengkapan dokumen (checklist, catatan, tanggal, nama, no HP, dan paraf) lalu menentukan <em>ACC</em> atau <em>Perbaikan</em>.</li>
        <li><strong>Verifikator Pusat</strong> memeriksa lanjutan dokumen (checklist, catatan, tanggal, nama, dan paraf) lalu menentukan <em>ACC</em> atau <em>Tidak Lengkap/Tidak Sesuai</em>.</li>
      </ol>
    </div>

    <div class="row">
      <!-- Verifikator BKK -->
      <div class="col-lg-6">
        <div class="card card-info card-outline shadow-sm">
          <div class="card-header bg-light">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-user-check text-info mr-2"></i> Verifikator BKK
            </h3>
          </div>
          <div class="card-body">
            <form action="/sinadi/tracing-tb/verifikator/tambah" method="post" class="mb-3">
              <input type="hidden" name="peran" value="verifikator_bkk">
              <div class="form-group">
                <label>Pilih Pegawai</label>
                <select name="id_user" class="form-control select2" style="width: 100%;" required>
                  <option value="">-- Pilih Pegawai --</option>
                  <?php foreach ($users as $u) : ?>
                    <option value="<?= (int) $u['id_user']; ?>">
                      <?= esc($u['nama']); ?><?= !empty($u['nip']) ? ' (' . esc($u['nip']) . ')' : ''; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>No HP Verifikator BKK <small class="text-muted">(opsional, terisi otomatis saat verifikasi)</small></label>
                <input type="text" name="no_hp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
              </div>
              <button type="submit" class="btn btn-info btn-sm btn-block">
                <i class="fas fa-user-plus mr-1"></i> Tambah Verifikator BKK
              </button>
            </form>

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
                      <td colspan="4" class="text-center text-muted py-3">Belum ada Verifikator BKK terdaftar.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Verifikator Pusat -->
      <div class="col-lg-6">
        <div class="card card-success card-outline shadow-sm">
          <div class="card-header bg-light">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-user-tie text-success mr-2"></i> Verifikator Pusat
            </h3>
          </div>
          <div class="card-body">
            <form action="/sinadi/tracing-tb/verifikator/tambah" method="post" class="mb-3">
              <input type="hidden" name="peran" value="verifikator_pusat">
              <div class="form-group">
                <label>Pilih Pegawai / Akun Verifikator Pusat</label>
                <select name="id_user" class="form-control select2" style="width: 100%;" required>
                  <option value="">-- Pilih Pegawai --</option>
                  <?php foreach ($users as $u) : ?>
                    <option value="<?= (int) $u['id_user']; ?>">
                      <?= esc($u['nama']); ?><?= !empty($u['nip']) ? ' (' . esc($u['nip']) . ')' : ''; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>No HP <small class="text-muted">(opsional)</small></label>
                <input type="text" name="no_hp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
              </div>
              <button type="submit" class="btn btn-success btn-sm btn-block">
                <i class="fas fa-user-plus mr-1"></i> Tambah Verifikator Pusat
              </button>
            </form>

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
                  <?php if (!empty($verifPusat)) : ?>
                    <?php $n = 1; foreach ($verifPusat as $v) : ?>
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
                          <form action="/sinadi/tracing-tb/verifikator/hapus/<?= (int) $v['id_verifikator']; ?>" method="post" onsubmit="return confirm('Hapus pegawai ini dari Verifikator Pusat?')">
                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted py-3">Belum ada Verifikator Pusat terdaftar.</td>
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
