<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
      <div class="card">
          <div class="card-header"></div>
          <div class="card-body">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <h3 class="d-inline-block d-sm-none">Formulir Update Data Kendaraan</h3>
                  <div class="col-12">
                    <img src="/dist/img/<?= $getData['gambar']; ?>" class="product-image" alt="Product Image">
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                    <form action="/kendaraan_kantor/update/daftar" method="post" enctype="multipart/form-data">
                      <h3 class="my-3">Formulir Update Data Kendaraan</h3>
                      <p>Pembaharuan secara menyeluruh hanya bisa dilakukan oleh admin!</p>
        
                      <hr>
                      <!--Formulir disini-->
                      <div class="custom-file mb-3">
                        <input type="file" name="file" class="custom-file-input" id="validatedCustomFile" accept="image/*">
                        <label class="custom-file-label" for="validatedCustomFile">Pilih untuk merubah gambar kendaraan...</label>
                      </div>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nama Kendaraan</label>
                        <div class="col-sm-10">
                          <input type="hidden" class="form-control" name="id_kendaraan_kantor_daftar" value="<?= $getData['id_kendaraan_kantor_daftar']; ?>">
                          <input type="text" class="<?= session()->get('role') == "Admin" ? 'form-control' : 'form-control-plaintext' ?>" <?= session()->get('role') == "Admin" ? 'required' : 'readonly' ?> name="nama_kendaraan" value="<?= $getData['nama_kendaraan']; ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Plat Nomor</label>
                        <div class="col-sm-10">
                          <textarea class="<?= session()->get('role') == "Admin" ? 'form-control' : 'form-control-plaintext' ?>" <?= session()->get('role') == "Admin" ? 'required' : 'readonly' ?> rows="3" name="plat_nomor"><?= $getData['plat_nomor']; ?></textarea>
                        </div>
                      </div>
                      
                      <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg btn-flat float-right">
                          <i class="fas fa-save fa-lg mr-2"></i>
                          Simpan
                        </button>
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
        var myImage = "";
        var checkImage = true;
        
        $('#validatedCustomFile').on('change', function(e) {
            // Check if a file was selected
            if (checkImage) { checkImage = true; myImage = "/dist/img/<?= $getData['gambar']; ?>"}
            
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                
                // When the file is loaded, set it as the src for the img with class 'product-image'
                reader.onload = function(e) {
                    $('.product-image').attr('src', e.target.result).show(); // Show the image if hidden
                }
                
                // Read the file as a data URL
                reader.readAsDataURL(this.files[0]);
            } else {
                // If no file is selected, clear the img src and hide the image
                $('.product-image').attr('src', myImage);
            }
        });
    });
</script>
<!-- /.content -->
<?= $this->endSection(); ?>