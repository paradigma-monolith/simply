<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<style>
  .abs-qr-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    margin-bottom: 14px;
  }
  .abs-qr-head {
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .abs-qr-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.92rem;
    flex-shrink: 0;
  }
  .abs-qr-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
  }
  .abs-qr-sub {
    font-size: 0.72rem;
    color: #64748b;
    margin: 1px 0 0;
  }
  .abs-qr-body {
    padding: 15px 16px;
  }
  .abs-qr-body .form-group label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
  }
  .abs-qr-notice {
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    border-radius: 10px;
    padding: 10px 13px;
    font-size: 0.77rem;
    line-height: 1.45;
    margin-bottom: 12px;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4">
        <!-- Generate Batch QR -->
        <div class="abs-qr-card">
          <div class="abs-qr-head">
            <div class="abs-qr-icon" style="background:#f0fdfa;color:#0d9488;"><i class="fas fa-qrcode"></i></div>
            <div>
              <h3 class="abs-qr-title">Generate Batch Stiker QR</h3>
              <p class="abs-qr-sub">Buat batch halaman stiker QR seluruh inventaris</p>
            </div>
          </div>
          <div class="abs-qr-body">
            <form action="/abs/manajemen_qr_code" id="formlulirManagemenQR" method="post">
              <div class="form-group">
                <label>Ukuran Kertas Cetak</label>
                <select name="kertas" class="form-control">
                  <option value="A4">Kertas A4</option>
                  <option value="1m">Kertas 1 Meter</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary btn-block font-weight-bold export_label_abs" style="background:#0d9488;border-color:#0d9488;">
                <i class="fas fa-qrcode mr-1"></i> Generate Batch File
              </button>
            </form>
          </div>
        </div>

        <!-- Export Filter QR -->
        <div class="abs-qr-card">
          <div class="abs-qr-head">
            <div class="abs-qr-icon" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-file-pdf"></i></div>
            <div>
              <h3 class="abs-qr-title">Export PDF QR Per Lokasi</h3>
              <p class="abs-qr-sub">Cetak stiker QR berdasarkan filter ruangan</p>
            </div>
          </div>
          <div class="abs-qr-body">
            <form action="/abs/export_file_qr_code" method="post" target="_blank">
              <div class="form-group">
                <label>Wilayah Kerja <span class="text-danger">*</span></label>
                <select required name="unit_kerja" class="form-control select2" style="width: 100%;">
                  <option value="">-- Pilih Wilayah Kerja --</option>
                  <?php foreach (($unit_kerja ?? []) as $data) : ?>
                    <option value="<?= $data['id_unit_kerja']; ?>"><?= esc($data['unit_kerja']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Lantai <span class="text-danger">*</span></label>
                <select required name="lantai" class="form-control select2" style="width: 100%;">
                  <option value="">-- Pilih Lantai --</option>
                  <?php foreach (($lantai ?? []) as $data) : ?>
                    <option value="<?= $data['id_abs_lantai']; ?>"><?= esc($data['lantai']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Ruang</label>
                <select name="ruang" class="form-control select2" style="width: 100%;">
                  <option value="">-- Semua Ruang --</option>
                  <?php foreach (($ruang ?? []) as $data) : ?>
                    <option value="<?= $data['id_abs_ruang']; ?>"><?= esc($data['ruang']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Kategori Barang</label>
                <select name="jenis" class="form-control select2" style="width: 100%;">
                  <option value="">-- Semua Kategori --</option>
                  <?php foreach (($jenis ?? []) as $data) : ?>
                    <option value="<?= $data['id_abs_jenis']; ?>"><?= esc($data['jenis']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Ukuran Kertas</label>
                <select required name="kertas" class="form-control">
                  <option value="A4">Kertas A4</option>
                  <option value="1m">Kertas 1 Meter</option>
                </select>
              </div>
              <button type="submit" class="btn btn-danger btn-block font-weight-bold">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF QR Code
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="abs-qr-card">
          <div class="abs-qr-head justify-content-between">
            <div class="d-flex align-items-center" style="gap: 10px;">
              <div class="abs-qr-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-archive"></i></div>
              <div>
                <h3 class="abs-qr-title">Daftar Batch File QR Code</h3>
                <p class="abs-qr-sub">Arsip file PDF label QR yang siap diunduh</p>
              </div>
            </div>
            <button type="button" class="btn btn-xs btn-outline-secondary font-weight-bold px-2 py-1" onclick="getExportFile()">
              <i class="fas fa-sync-alt mr-1"></i> Segarkan
            </button>
          </div>
          <div class="abs-qr-body">
            <div class="abs-qr-notice">
              <i class="fas fa-info-circle mr-1"></i>
              <strong>Catatan Performa:</strong> Proses pembuatan PDF QR Code dalam jumlah besar memerlukan waktu render. Hindari menekan tombol secara berulang dalam waktu singkat.
            </div>
            <div class="file_abs">
              <?= view('abs/getFile', ['getFile' => $getFile ?? []]); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(".export_label_abs").on("click", function(e) {
    e.preventDefault();
    var formData = $('#formlulirManagemenQR').serialize();

    $.ajax({
      url: '/abs/ajax/countLabel',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        $("#overlay").hide();
        if (response) {
          getExportFile();
          Toast.fire({
            icon: 'success',
            title: 'Batch QR Code berhasil diperbarui'
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $("#overlay").hide();
        Swal.fire({
          icon: 'error',
          title: 'Gagal memproses batch QR',
          text: xhr.status + ' ' + thrownError
        });
      }
    });
  });

  function getExportFile() {
    $.ajax({
      url: '/abs/ajax/getFile',
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".file_abs").html('<div class="p-4 text-center text-muted"><i class="fas fa-sync-alt fa-spin mr-2"></i>Memuat daftar file...</div>');
      },
      success: function(response) {
        if (response && response.data) {
          $(".file_abs").html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'error',
          text: xhr.status + ' ' + thrownError
        });
      }
    });
  }
</script>

<?= $this->endSection(); ?>
