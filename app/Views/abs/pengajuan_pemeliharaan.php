<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$array = \App\Models\AbsLokasiModel::safeUnserialize($pemeliharaan['data_barang'] ?? '');
$curStatus = (string) ($pemeliharaan['status'] ?? 'pending');
?>

<style>
  .abs-maint-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    margin-bottom: 14px;
  }
  .abs-maint-head {
    padding: 13px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .abs-maint-title {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
  }
  .abs-maint-body {
    padding: 16px;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="mb-2">
      <a href="/abs/pengajuan" class="btn btn-sm btn-light border font-weight-bold">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pengajuan
      </a>
    </div>

    <div class="row">
      <div class="col-lg-7">
        <div class="abs-maint-card">
          <div class="abs-maint-head">
            <h3 class="abs-maint-title"><i class="fas fa-box text-info mr-2"></i>Rincian Barang Inventaris</h3>
          </div>
          <div class="abs-maint-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped mb-0">
                <tbody>
                  <?php foreach ($array as $key => $value) : ?>
                    <tr>
                      <th width="36%" style="font-size:0.78rem;"><?= esc((string) $key); ?></th>
                      <td style="font-size:0.8rem;" class="font-weight-bold"><?= esc((string) $value); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="abs-maint-card">
          <div class="abs-maint-head">
            <h3 class="abs-maint-title"><i class="fas fa-file-alt text-warning mr-2"></i>Keterangan &amp; Bukti Pengajuan</h3>
          </div>
          <div class="abs-maint-body">
            <?= $pemeliharaan['keterangan'] ?? '<span class="text-muted">Tidak ada keterangan</span>'; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="abs-maint-card">
          <div class="abs-maint-head">
            <h3 class="abs-maint-title"><i class="fas fa-clipboard-check text-success mr-2"></i>Formulir Tindak Lanjut &amp; Validasi</h3>
          </div>
          <div class="abs-maint-body">
            <form action="/abs/validasiPengajuanPemeliharaan" method="post">
              <?= csrf_field(); ?>
              <input type="hidden" name="id_abs_pengajuan_pemeliharaan" value="<?= (int) ($pemeliharaan['id_abs_pengajuan_pemeliharaan'] ?? 0); ?>">

              <div class="form-group">
                <label class="font-weight-bold" style="font-size:0.8rem;">Status Tindak Lanjut</label>
                <select name="status" class="form-control">
                  <option <?= $curStatus === 'pending' ? 'selected' : ''; ?> value="pending">Pending</option>
                  <option <?= $curStatus === 'proses pemeliharaan' ? 'selected' : ''; ?> value="proses pemeliharaan">Diproses untuk dilakukan pemeliharaan</option>
                  <option <?= $curStatus === 'rusak' ? 'selected' : ''; ?> value="rusak">Barang Rusak Total</option>
                  <option <?= $curStatus === 'selesai diperbaiki' ? 'selected' : ''; ?> value="selesai diperbaiki">Barang selesai diperbaiki</option>
                </select>
              </div>

              <div class="form-group">
                <label class="font-weight-bold" style="font-size:0.8rem;">Catatan / Balasan Validasi</label>
                <textarea name="validasi" class="summernote" required><?= old('validasi') ? old('validasi') : ($pemeliharaan['validasi'] ?? ''); ?></textarea>
              </div>

              <button type="submit" class="btn btn-success btn-block font-weight-bold">
                <i class="fas fa-save mr-1"></i> Simpan Validasi Pemeliharaan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>
