<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="alert alert-info" role="alert">
      Daftar Jumlah dari SOP yang masih Berlaku
    </div>
    <div class="container-total-sop"></div>
      
    
    <div class="row">
      <div class="col-lg"></div>
      <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-success">
            </div>
            <div class="card-body">
                <form action="/sipepes/export" method="post">
                  <div class="form-group">
                    <label>Filter data Divisi:</label>
                    <select class="form-control select2" name="pilih_divisi" style="width:100%;">
                      <option value=""> -- Semua Divisi -- </option>
                      <?php foreach($divisi as $data): ?>
                          <?php if($data['unit_kerja'] != ""): ?>
                          <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                          <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Status (Berlaku/Tidak Berlaku):</label>
                    <select class="form-control select2" name="status" style="width:100%;">
                      <option value=""> -- Semua Status -- </option>
                      <option value="Berlaku"> Berlaku </option>
                      <option value="Tidak Berlaku"> Tidak Berlaku </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tahun efektif (dari/sampai dengan):</label>
                    <div class="row">
                        <div class="col">
                            <select class="form-control d-inline-block select2" name="tahun_1" style="width:100%;">
                                <?php for($i=2026; $i>= 2011; $i--): ?>
                                    <option value="<?= $i; ?>"> <?= $i; ?> </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control d-inline-block select2" name="tahun_2" style="width:100%;">
                                <?php for($i=2026; $i>= 2011; $i--): ?>
                                    <option value="<?= $i; ?>"> <?= $i; ?> </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="text-right">
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
                  </div>
                </form>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-info">
            List Daftar SOP
          </div>
          <div class="card-body">
              <label>Filter data Divisi:</label>
              <select class="form-control select2" id="view_sop" name="pilih_divisi" style="width:100%;">
                <option value=""> -- Semua Divisi -- </option>
                <?php foreach($divisi as $data): ?>
                    <?php if($data['unit_kerja'] != ""): ?>
                    <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
              </select>
            <hr>
            <div class="view_data_sop"></div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>

</section>

<script>
$(document).ready(function () {
  countDaftarSOP();
});
function countDaftarSOP() {
  $.ajax({
    url: '/sipepes/countDaftarSOP',
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $(".container-total-sop").html(`<i class='fas fa-sync-alt fa-spin mb-3'></i> <span class='text-bold pt-2'>Loading...</span>`);
      $("#overlay").show();
    },
    success: function(response) {
      if (response.data) {
        $(".container-total-sop").html(response.data);
      }
    },
    complete: function() {
      $("#overlay").hide();
    }
  });        
}
</script>

<?= $this->endSection(); ?>