<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="simpanTemplateNaskah" action="/relaxtime/upload_file<?= isset($getData['id_relaxtime_realisasi_file']) ? '/' . $getData['id_relaxtime_realisasi_file'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getData['id_arsip_template_naskah']) ? 'Ubah Template Naskah' : 'Tambah Template Naskah'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Jenis Naskah</label>
                <div class="col-sm-10">
                  <input type="text" name="jenis_naskah" class="form-control" id="jenis_naskah" value="<?= $activeMenu == "relaxtimeRealisasiAkrual" ? "Akrual" : ($activeMenu == "relaxtimeRealisasiTransaksi" ? "Transaksi" : "SP2D"); ?>" readonly>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama File</label>
                <div class="col-sm-10">
                  <input type="text" name="nama_file" class="form-control" id="nama_file" value="<?= isset($getData['nama_file']) ? $getData['nama_file'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Dokumen</label>
                <div class="col-sm-10">
                  <?php if (isset($getData)) : ?>
                    <p class="text-danger">Aktifkan switch berikut jika akan mengubah file. Abaikan jika tidak akan mengubah file.</p>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-1">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input inputFile" for="unggah_naskah" id="customSwitch1" <?= isset($getData) ? '' : 'checked disabled'; ?>>
                        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                      </div>
                    </div>
                    <div class="col-sm-11">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="unggah_naskah" id="unggah_naskah">
                        <label class="custom-file-label" for="unggah_naskah">Pilih File</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
    
$(document).on('submit', '#simpanTemplateNaskah', function (e) {
  e.preventDefault();
  
  // Ambil tombol submit
  var $submitButton = $(this).find('button[type="submit"]');

  // Ubah tombol jadi disabled dan tampilkan ikon loading
  $submitButton.prop('disabled', true);
  var originalButtonHtml = $submitButton.html(); // Simpan HTML asli tombol
  $submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/relaxtime/<?= $activeMenu; ?>?";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }, complete: function() {
      // Kembalikan tombol ke keadaan semula
      $submitButton.prop('disabled', false);
      $submitButton.html(originalButtonHtml);
    }
  })
});
</script>

<?= $this->endSection(); ?>