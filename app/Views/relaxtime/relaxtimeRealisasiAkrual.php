<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <?php if (session()->get('role') == 'Admin') : ?>
            <div class="card-header">
              <div class="text-right">
                <a href="/relaxtime/realisasiUpload/relaxtimeRealisasiAkrual" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Realisasi Akrual</a>
              </div>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <div class="relaxtime_realisasi_akrual">
                <div class="text-center">
                  <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
    
$(function () {
    viewArsipTemplateNaskah();
})
function viewArsipTemplateNaskah() {
  $.ajax({
    url: '/relaxtime/relaxtime_realisasi_akrual',
    method: 'get',
    dataType: "json",
    success: function(response) {
      if (response.data) {
        console.log(response.cek);
        $(".relaxtime_realisasi_akrual").html(response.data);
      } else {
        $(".relaxtime_realisasi_akrual").html(`<div class="text-center">Tidak ada data ditemukan.</div>`);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}
</script>

<?= $this->endSection(); ?>