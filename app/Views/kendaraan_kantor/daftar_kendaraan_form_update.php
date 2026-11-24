<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$isAdminRole = session()->get('role') === 'Admin';
$imgFile = trim((string) ($getData['gambar'] ?? ''));
$currentImgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
?>

<style>
  .kk-form-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
  }
  .kk-preview-frame {
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    padding: 18px;
    text-align: center;
    min-height: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .kk-preview-frame img.product-image {
    max-width: 100%;
    max-height: 230px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 10px;
  }
  @media (max-width: 575.98px) {
    .kk-form-card .card-header {
      padding: 12px 14px !important;
      flex-wrap: wrap;
      gap: 8px;
    }
    .kk-form-card .card-header h5 {
      font-size: 0.95rem;
    }
    .kk-form-card .card-body {
      padding: 14px !important;
    }
    .kk-preview-frame {
      min-height: 150px;
      padding: 12px;
    }
    .kk-preview-frame img.product-image {
      max-height: 130px;
    }
  }
</style>

<section class="content pb-4">
  <div class="container-fluid">
    <div class="card kk-form-card">
      <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <h5 class="mb-0 font-weight-bold" style="color: #0f172a;">
          <i class="fas fa-edit mr-2" style="color: #0d9488;"></i>Update Data Kendaraan
        </h5>
        <a href="/kendaraan_kantor/daftar_kendaraan" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
      </div>
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-5 mb-4 mb-md-0">
            <div class="kk-preview-frame">
              <img src="<?= $currentImgUrl; ?>"
                   class="product-image mb-2"
                   alt="Preview Kendaraan"
                   onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';">
              <small class="text-muted d-block mt-2">Foto unit kendaraan saat ini</small>
            </div>
          </div>
          <div class="col-md-7">
            <form action="/kendaraan_kantor/update/daftar" method="post" enctype="multipart/form-data">
              <div class="alert alert-light border small text-muted mb-3" style="border-radius: 10px;">
                <i class="fas fa-info-circle text-info mr-1"></i>
                Pembaruan data unit kendaraan kantor hanya dapat dilakukan oleh <strong>Administrator</strong>.
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Ganti Foto Kendaraan (Opsional)</label>
                <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="validatedCustomFile" accept="image/*">
                  <label class="custom-file-label" for="validatedCustomFile">Pilih untuk mengganti gambar kendaraan...</label>
                </div>
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Nama Kendaraan</label>
                <input type="hidden" name="id_kendaraan_kantor_daftar" value="<?= (int) ($getData['id_kendaraan_kantor_daftar'] ?? 0); ?>">
                <input type="text"
                       class="<?= $isAdminRole ? 'form-control' : 'form-control-plaintext'; ?>"
                       <?= $isAdminRole ? 'required' : 'readonly'; ?>
                       name="nama_kendaraan"
                       value="<?= esc($getData['nama_kendaraan'] ?? '', 'attr'); ?>">
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Plat Nomor</label>
                <input type="text"
                       class="<?= $isAdminRole ? 'form-control' : 'form-control-plaintext'; ?>"
                       <?= $isAdminRole ? 'required' : 'readonly'; ?>
                       name="plat_nomor"
                       value="<?= esc($getData['plat_nomor'] ?? '', 'attr'); ?>">
              </div>

              <?php if ($isAdminRole): ?>
                <div class="form-group">
                  <input type="hidden" name="bisa_dipinjam" value="0">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bisaDipinjamKkUpdate" name="bisa_dipinjam" value="1" <?= ((int) ($getData['bisa_dipinjam'] ?? 1) === 1) ? 'checked' : ''; ?>>
                    <label class="custom-control-label font-weight-bold" for="bisaDipinjamKkUpdate">Tersedia di Daftar Peminjaman Kendaraan</label>
                  </div>
                </div>
              <?php endif; ?>

              <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                <a href="/kendaraan_kantor/daftar_kendaraan" class="btn btn-light border px-4" style="border-radius: 9px;">Batal</a>
                <?php if ($isAdminRole): ?>
                  <button type="submit" class="btn btn-success font-weight-bold px-4" style="border-radius: 9px;">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                  </button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    var defaultImg = "<?= $currentImgUrl; ?>";
    $('#validatedCustomFile').on('change', function() {
      var fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').text(fileName || 'Pilih untuk mengganti gambar kendaraan...');
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('.product-image').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(this.files[0]);
      } else {
        $('.product-image').attr('src', defaultImg);
      }
    });
  });
</script>

<?= $this->endSection(); ?>
