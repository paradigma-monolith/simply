<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php if (session()->get('role') == 'Admin'): ?>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            <a data-toggle="collapse" href="#collapseExample" class="btn btn-sm btn-primary" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-eye"></i> Akses Khusus
            </a>
          </h5>
        </div>
        <div id="collapseExample" class="collapse">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="form-group">
                  <label>User Akses</label>
                  <select name="akses" style="width: 100%;" class="select2" multiple="multiple">

                  </select>
                </div>
              </div>
              <div class="col-7">
                <div class="row">
                  <div class="col"></div>
                  <div class="col-sm-8">
                    <div class="card">
                      <div class="card-header">Formulir Penambahan Data</div>
                      <div class="card-body">
                        <?= $this->include('/simandja/tambahDataTahun'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <table class="table-sm table-striped table-bordered" style="width: 100%" id="tableWithoutExporting">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Tombol/<br />Keterangan</th>
                          <th>Tahun</th>
                          <th>Status Tombol</th>
                          <?php if(session()->get('role') == 'Admin'): ?>
                          <th>Aksi</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $nomor = 1;
                        // Mengurutkan array $getData dari belakang
                        $getDataReversed = array_reverse($getData);
                        foreach ($getDataReversed as $data): ?>
                          <tr>
                            <td><?= $nomor++; ?></td>
                            <td><i class="fas fa-link"></i> <?= $data['keterangan']; ?></td>
                            <td><?= $data['tahun']; ?></td>
                            <td class="text-center">
                              <a href="/simandja/simandjarevisi/status/<?= $data['id_simandja_tahun_anggaran']; ?>" class="<?= $data['status'] == 'non-aktif' ? "text-danger" : 'text-success'; ?>"><?= $data['status']; ?></a>
                            </td>
                          <?php if(session()->get('role') == 'Admin'): ?>
                          <td>
                            <form action="/simandja/dlt_simulmen/<?= $data['id_simandja_tahun_anggaran']; ?>" method="post" class="d-inline formHapusData">
                              <?= csrf_field(); ?>
                              <input type="hidden" name="_method" value="DELETE">
                              <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
                            </form>
                          </td>
                          <?php endif; ?>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!--<div class="float-right">-->
                <!--<button class="btn btn-primary tambahDataTahun"><i class="fas fa-plus"></i> Tambah Data</button>-->
                <!--</div>-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <a target="_blank" href="" class="disabled btn btn-info m-1">
          <i class="fas fa-link"></i> Semulmen Jan
        </a>
        <a target="_blank" href="https://docs.google.com/spreadsheets/d/1_szYteNRdyYNKuqXG4J5hgAxMPQCOFYj/edit?usp=drive_link&ouid=103327788879510797240&rtpof=true&sd=true" class="btn btn-info m-1">
          <i class="fas fa-link"></i> Semulmen Feb
        </a>
      </div>

      <div class="card-footer">
        <iframe class="lazyIframe" src="" data-src="https://docs.google.com/spreadsheets/d/1_szYteNRdyYNKuqXG4J5hgAxMPQCOFYj/edit?usp=drive_link&ouid=103327788879510797240&rtpof=true&sd=true" title="Simulmen Juli" style="width:100%; height:1200px;" loading="lazy"></iframe>
        <!--<div id="excel-preview"><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></div>-->
      </div>

</section>

<script>
  $(document).on("click", ".tambahDataTahun", function() {
    var $modal = $("#modal-md");
    var activeMenu = "<?= $activeMenu ?>";

    $.ajax({
      url: `/simandja/tambahDataTahun`,
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
          $modal.find(".modal-body").html(response.data);
          $modal.find("#kategori").val(activeMenu);
          $modal.modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          'text': xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    var totalIframes = $('.lazyIframe').length;
    var loadedIframes = 0;

    function loadIframe(iframe) {
      var lazySrcIframe = $(iframe).data('src');
      $(iframe).attr('src', lazySrcIframe);

      loadedIframes++;

      if (loadedIframes === totalIframes) {
        console.log('Semua iframe telah dimuat.');
      }
    }

    var observer = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          loadIframe(entry.target);
          observer.unobserve(entry.target);
        }
      });
    });

    $('.lazyIframe').each(function() {
      observer.observe(this);
    });
  });
</script>


<?= $this->endSection(); ?>