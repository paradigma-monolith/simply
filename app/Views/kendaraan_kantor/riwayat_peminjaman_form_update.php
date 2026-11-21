<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$imgFile = trim((string) ($getData['gambar'] ?? ''));
$imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
$namaMobil = trim((string) ($getData['nama_kendaraan'] ?? ''));
$platNomor = trim((string) ($getData['plat_nomor'] ?? ''));
$tanggal = json_decode((string) ($getData['tanggal'] ?? '[]'), true);
if (!is_array($tanggal) || empty($tanggal)) {
  $tanggal = [date('Y-m-d')];
}
$selectedPenumpang = json_decode((string) ($getData['penumpang'] ?? '[]'), true);
if (!is_array($selectedPenumpang)) {
  $selectedPenumpang = [];
}
?>

<style>
  .kk-form-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
  }
  .kk-summary-panel {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 12px;
    padding: 18px;
    text-align: center;
  }
  .kk-summary-panel img {
    max-width: 100%;
    max-height: 210px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 10px;
  }
</style>

<section class="content pb-4">
  <div class="container-fluid">
    <div class="card kk-form-card">
      <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <h5 class="mb-0 font-weight-bold" style="color: #0f172a;">
          <i class="fas fa-edit mr-2" style="color: #0d9488;"></i>Update Data Peminjaman Kendaraan
        </h5>
        <a href="/kendaraan_kantor/riwayat_peminjaman" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
      </div>
      <div class="card-body p-4">
        <div class="row">
          <div class="col-md-5 mb-4 mb-md-0">
            <div class="kk-summary-panel">
              <img src="<?= $imgUrl; ?>"
                   class="product-image mb-3"
                   alt="Kendaraan"
                   onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';">
              <h5 class="font-weight-bold mb-1" style="color: #0f172a;">
                <?= $namaMobil !== '' ? esc($namaMobil) : 'Kendaraan Belum Ditentukan'; ?>
              </h5>
              <?php if ($platNomor !== ''): ?>
                <div class="mb-3">
                  <span class="badge badge-dark px-3 py-1" style="font-size: 0.85rem; letter-spacing: 0.8px;"><?= esc($platNomor); ?></span>
                </div>
              <?php endif; ?>

              <div class="p-3 bg-white border rounded text-left mt-2" style="border-radius: 10px !important;">
                <div class="small text-muted font-weight-bold text-uppercase">Jadwal Peminjaman</div>
                <div class="font-weight-bold text-dark mt-1" style="font-size: 0.96rem;">
                  <i class="far fa-calendar-alt text-info mr-1"></i>
                  <?php if (count($tanggal) > 1): ?>
                    <?= shortdate_indo3($tanggal[0]); ?> — <?= shortdate_indo3($tanggal[count($tanggal) - 1]); ?>
                  <?php else: ?>
                    <?= shortdate_indo3($tanggal[0]); ?>
                  <?php endif; ?>
                </div>
                <span class="badge badge-info mt-1"><?= count($tanggal); ?> hari peminjaman</span>
              </div>
            </div>
          </div>

          <div class="col-md-7">
            <form action="/kendaraan_kantor/update/riwayat" method="post">
              <div class="alert alert-light border small text-muted mb-3" style="border-radius: 10px;">
                <i class="fas fa-info-circle text-info mr-1"></i>
                Pembaruan dapat dilakukan untuk <strong>Tujuan</strong>, <strong>Kegiatan</strong>, dan <strong>Penumpang</strong>. Jika nama pegawai tidak muncul, pegawai tersebut sudah terjadwal pada peminjaman lain di tanggal yang sama.
              </div>

              <input type="hidden" name="id_kendaraan_kantor_riwayat" value="<?= (int) ($getData['id_kendaraan_kantor_riwayat'] ?? 0); ?>">

              <div class="form-group">
                <label class="font-weight-bold">Tujuan Perjalanan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required name="tujuan" value="<?= esc($getData['tujuan'] ?? '', 'attr'); ?>">
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Kegiatan / Keperluan <span class="text-danger">*</span></label>
                <textarea class="form-control" required rows="3" name="keterangan"><?= esc($getData['keterangan'] ?? ''); ?></textarea>
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Daftar Penumpang <span class="text-danger">*</span></label>
                <select multiple="multiple" required class="select2" style="width: 100%;" name="penumpang[]">
                  <?php foreach ($getPegawai as $data): ?>
                    <option value="<?= (int) $data['id_data_pegawai']; ?>" <?= in_array($data['id_data_pegawai'], $selectedPenumpang) ? 'selected' : ''; ?>>
                      <?= esc(name_capitalize((string) ($data['nama'] ?? ''))); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                <a href="/kendaraan_kantor/riwayat_peminjaman" class="btn btn-light border px-4" style="border-radius: 9px;">Batal</a>
                <button type="submit" class="btn btn-success font-weight-bold px-4" style="border-radius: 9px;">
                  <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>
