<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$array = \App\Models\AbsLokasiModel::safeUnserialize($getData['data_barang'] ?? '');
$imgFile = !empty($getData['image']) ? $getData['image'] : 'default.png';
?>

<style>
  .abs-form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .abs-form-head {
    padding: 13px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .abs-form-title {
    font-size: 0.96rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
  }
  .abs-form-body {
    padding: 18px;
  }
  .abs-form-foot {
    padding: 12px 18px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }
  .plus, .min {
    cursor: pointer;
    top: 6px;
    right: 6px;
    height: 22px;
    width: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    color: #ffffff;
    border-radius: 50%;
    font-weight: 800;
    font-size: 0.85rem;
    line-height: 1;
    user-select: none;
  }
  .plus { background-color: #0d9488; }
  .min { background-color: #dc2626; }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="abs-form-card">
      <form id="absSimpanLokasi" action="/abs/simpan_lokasi" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="id_abs_bangunan" value="<?= (int) ($getData['id_abs_bangunan'] ?? 0); ?>" readonly>
        <div class="abs-form-head">
          <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="/abs/data_barang" class="btn btn-sm btn-light border font-weight-bold">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <h3 class="abs-form-title">Formulir Edit Data Inventaris ABS BMN</h3>
          </div>
          <span class="badge badge-info px-2 py-1">ID #<?= (int) ($getData['id_abs_bangunan'] ?? 0); ?></span>
        </div>

        <div class="abs-form-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold small">Wilayah Kerja</label>
                <select class="form-control select2" name="wilayah_kerja" id="wilayah_kerja" style="width:100%;">
                  <option value="">-- Pilih Wilayah Kerja --</option>
                  <?php foreach (($wilayahKerja ?? []) as $dataWilker) : ?>
                    <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= !empty($getData['id_unit_kerja']) && $getData['id_unit_kerja'] == $dataWilker['id_unit_kerja'] ? 'selected' : ''; ?>>
                      <?= esc($dataWilker['unit_kerja']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold small">Lantai</label>
                <select class="form-control select2" name="lantai" id="lantai" style="width:100%;">
                  <option value="">-- Pilih Lantai --</option>
                  <?php foreach (($lantai ?? []) as $dataLantai) : ?>
                    <option value="<?= $dataLantai['id_abs_lantai']; ?>" <?= !empty($getData['id_abs_lantai']) && $getData['id_abs_lantai'] == $dataLantai['id_abs_lantai'] ? 'selected' : ''; ?>>
                      <?= esc($dataLantai['lantai']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold small">Ruang</label>
                <select class="form-control select2" name="ruang" id="ruang" style="width:100%;">
                  <option value="">-- Pilih Ruang --</option>
                  <?php foreach (($ruang ?? []) as $dataRuang) : ?>
                    <option value="<?= $dataRuang['id_abs_ruang']; ?>" <?= !empty($getData['id_abs_ruang']) && $getData['id_abs_ruang'] == $dataRuang['id_abs_ruang'] ? 'selected' : ''; ?>>
                      <?= esc($dataRuang['ruang']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold small">Kategori Kelompok <span class="text-danger">*</span></label>
                <select class="form-control select2" name="jenis_barang" id="jenis_barang" style="width:100%;" required>
                  <option value="">-- Pilih Kategori --</option>
                  <?php foreach (($jenisBarang ?? []) as $dataJenisBarang) : ?>
                    <option value="<?= $dataJenisBarang['id_abs_jenis']; ?>" <?= !empty($getData['id_abs_jenis']) && $getData['id_abs_jenis'] == $dataJenisBarang['id_abs_jenis'] ? 'selected' : ''; ?>>
                      <?= esc($dataJenisBarang['jenis']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <hr class="my-3">

          <div id="labelRange">
            <?php $firstData = false; ?>
            <?php foreach ($array as $label => $valueLabel) : ?>
              <div class="form-group row mb-2">
                <label class="col-sm-2 font-weight-bold small pt-2">
                  <?php if ($firstData === false) : $firstData = true; ?>
                    <span>Atribut Barang</span><span class="plus" title="Tambah Baris Atribut">+</span>
                  <?php else : ?>
                    <span></span><span class="min" title="Hapus Baris Atribut">-</span>
                  <?php endif; ?>
                </label>
                <div class="col-sm-5">
                  <input type="text" name="label[]" placeholder="Entri Informasi Barang" value="<?= esc((string) $label, 'attr'); ?>" class="form-control" required>
                </div>
                <div class="col-sm-5">
                  <input type="text" name="isi_label[]" placeholder="...." value="<?= esc((string) $valueLabel, 'attr'); ?>" class="form-control" required>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div id="newInputs"></div>

          <hr class="my-3">

          <div class="form-group row align-items-center">
            <label class="col-sm-2 font-weight-bold small mb-0">Status Tersedia <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-check form-check-inline">
                <input <?= ($getData['status_tersedia'] ?? '') === 'Tersedia' ? 'checked' : ''; ?> class="form-check-input" type="radio" name="status_tersedia" id="tersedia" value="Tersedia" required>
                <label class="form-check-label font-weight-bold text-success" for="tersedia">Barang berada di lokasi (Tersedia)</label>
              </div>
              <div class="form-check form-check-inline">
                <input <?= ($getData['status_tersedia'] ?? '') === 'Tidak Tersedia' ? 'checked' : ''; ?> class="form-check-input" type="radio" name="status_tersedia" id="tidak_tersedia" value="Tidak Tersedia" required>
                <label class="form-check-label font-weight-bold text-danger" for="tidak_tersedia">Barang tidak ada di lokasi (Tidak Tersedia)</label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 font-weight-bold small">QR Code &amp; Gambar</label>
            <div class="col-sm-10 d-flex flex-wrap" style="gap: 16px;">
              <div class="p-2 border rounded bg-light text-center" style="width: 210px;">
                <p class="small font-weight-bold mb-1">QR Code Internal ABS</p>
                <img src="<?= generate_qr((base_url() . '/data_abs/' . ($getData['kode_qr_code_internal'] ?? '')), 'QR Code ABS', 'Teal'); ?>" class="img-thumbnail mb-1" style="width: 170px; height: 170px; object-fit: contain;">
                <input type="text" class="form-control form-control-sm" value="<?= esc((string) ($getData['kode_qr_code_internal'] ?? ''), 'attr'); ?>" disabled>
              </div>
              <div class="p-2 border rounded bg-light text-center" style="width: 210px;">
                <p class="small font-weight-bold mb-1">QR Code SIMAN</p>
                <img src="<?= generate_qr((string) ($getData['kode_qr_code_pusat'] ?? ''), 'QR Code SIMAN'); ?>" class="img-thumbnail mb-1" style="width: 170px; height: 170px; object-fit: contain;">
                <input type="text" class="form-control form-control-sm" name="kode_qr_code_pusat" value="<?= esc((string) ($getData['kode_qr_code_pusat'] ?? ''), 'attr'); ?>">
              </div>
              <div class="p-2 border rounded bg-light text-center" style="width: 210px;">
                <p class="small font-weight-bold mb-1">Gambar Barang</p>
                <img src="/dist/img/abs/<?= esc($imgFile, 'attr'); ?>"
                     onerror="this.onerror=null;this.src='/dist/img/abs/default.png';"
                     class="img-thumbnail mb-1"
                     style="width: 170px; height: 170px; object-fit: cover;"
                     id="preview">
                <div class="btn-group btn-group-sm btn-block" role="group">
                  <label class="btn btn-secondary mb-0" for="absImage" style="cursor:pointer;"><i class="fas fa-upload mr-1"></i>Upload</label>
                  <button type="button" class="btn btn-info" onclick="viewGaleryABS()"><i class="fas fa-images mr-1"></i>Galeri</button>
                </div>
                <input type="file" onchange="previewImage(event)" class="d-none custom-file-input form-control" id="absImage" name="image" accept="image/*">
                <input type="hidden" name="id_abs_image" value="<?= esc((string) ($getData['id_abs_image'] ?? '1'), 'attr'); ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="abs-form-foot">
          <a href="/abs/data_barang" class="btn btn-light border font-weight-bold">Batal</a>
          <button type="submit" class="btn btn-success font-weight-bold px-4" style="background:#0d9488;border-color:#0d9488;">
            <i class="fas fa-save mr-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
  function previewImage(event) {
    if (!event.target.files || !event.target.files[0]) return;
    $('input[name="id_abs_image"]').val('');

    var reader = new FileReader();
    reader.onload = function() {
      var imgElement = document.getElementById('preview');
      if (imgElement) imgElement.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  function viewGaleryABS() {
    $.ajax({
      url: '/abs/getImage',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show();
      },
      success: function(response) {
        if (response && response.data) {
          $("#absImageGalery").find(".modal-body").html(response.data);
          $("#absImageGalery").modal('show');
        }
        $('#overlay').hide();
      },
      error: function() {
        $('#overlay').hide();
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Gagal memuat galeri',
          text: 'Terjadi kesalahan saat mengambil data gambar.'
        });
      }
    });
  }
</script>

<?= $this->endSection(); ?>
