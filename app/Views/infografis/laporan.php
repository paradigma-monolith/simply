<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>


<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-5">
        <form id="infografisForm" action="" method="post">
          <div class="card mb-0">
            <div class="card-header pt-1 pb-1 bg-primary">
            </div>
            <div class="card-body pb-0">
              <div class="row">
                <!-- ===========================Infografis================================= -->
                  <div class="form-group select-dashboard-infografis col-lg-6 col-md-6 col-6 d-none">
                    <label class="has-float-label">
                      <div>
                        <select class="form-control select2" name="wilayah" style="width:100%;">
                          <option value=""> --Semua Wilayah Kerja-- </option>
                          <?php foreach ($unitKerja as $data) : ?>
                            <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <span>Wilayah Kerja</span>
                    </label>
                  </div>
                  <!-- ===================================================================== -->
                  <div class="form-group select-dashboard-infografis col-lg-6 col-md-6 col-6 d-none">
                    <label class="has-float-label">
                      <div>
                        <select class="form-control select2" name="jenis_infografis" style="width:100%;">
                          <option value=""> --Semua Infografis-- </option>
                          <option value="kapal"> Infografis Kapal </option>
                          <option value="pesawat"> Infografis Pesawat </option>
                          <option value="dokumen"> Infografis Dokumen </option>
                          <option value="vaksin"> Infografis Vaksin </option>
                        </select>
                      </div>
                      <span>Jenis Infografis</span>
                    </label>
                  </div>
                <!-- ===================================================================== -->
              </div>
              <div class="form-group select-dashboard-infografis">
                <label class="has-float-label">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input id="periodeInfografis-dashboard" type="text" name="tanggal-infografis" class="form-control float-right date-dashboard-infografis date-dashboard">
                  </div>
                  <span>Periode</span>
                </label>
              </div>
              <div class="block"><button type="submit" name="excel" class="btn btn-lg btn-success btn-block mb-3"><i class="far fa-file-excel"></i> Rekapitulasi Data Karantina</button></div>
              <div class="block"><button type="submit" name="laporan" class="btn btn-lg btn-success btn-block mb-3"><i class="far fa-file-excel"></i> Laporan Infografis (dan Rekap Kapal)</button></div>
            </div>
          </div>
        </form>  
      </div>
      <div class="col-5">
        <form id="" action="/infografis/pdf" method="post">
          <div class="card mb-0">
            <div class="card-header pt-1 pb-1 bg-primary">
            </div>
            <div class="card-body pb-0">
              <div class="row">
                <!-- ===========================Infografis================================= -->
                  <div class="form-group select-dashboard-infografis col-lg-12 col-md-12 col-12">
                    <label class="has-float-label">
                      <div>
                        <select class="form-control select2" name="wilayah" style="width:100%;">
                          <option value=""> --Semua Wilayah Kerja-- </option>
                          <?php foreach ($unitKerja as $data) : ?>
                            <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <span>Wilayah Kerja</span>
                    </label>
                  </div>
                  <!-- ===================================================================== -->
                  <div class="form-group select-dashboard-infografis col-lg-12 col-md-12 col-12 d-none">
                    <label class="has-float-label">
                      <div>
                        <select class="form-control select2" name="jenis_infografis" style="width:100%;">
                          <option value=""> --Semua Infografis-- </option>
                          <option value="kapal"> Infografis Kapal </option>
                          <option value="pesawat"> Infografis Pesawat </option>
                          <option value="dokumen"> Infografis Dokumen </option>
                          <option value="vaksin"> Infografis Vaksin </option>
                        </select>
                      </div>
                      <span>Jenis Infografis</span>
                    </label>
                  </div>
                <!-- ===================================================================== -->
              </div>
              <div class="form-group select-dashboard-infografis">
                <label class="has-float-label">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input id="periodeInfografis-dashboard" type="text" name="tanggal-infografis" class="form-control float-right date-dashboard-infografis date-dashboard">
                  </div>
                  <span>Periode</span>
                </label>
              </div>
              <div class="block"><button type="submit" class="btn btn-lg btn-danger btn-block mb-3"><i class="far fa-file-pdf"></i> Infografis</button></div>
            </div>
          </div>
        </form>  
      </div>
    </div>
  </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('infografisForm');

    // Menangani klik tombol "pdf"
    form.querySelector('button[name="pdf"]').addEventListener('click', function () {
        form.action = '/infografis/pdf';
    });

    // Menangani klik tombol "excel"
    form.querySelector('button[name="excel"]').addEventListener('click', function () {
        form.action = '/infografis/excel';
    });
    
    // Menangani klik tombol "excel"
    form.querySelector('button[name="laporan"]').addEventListener('click', function () {
        form.action = '/infografis/laporan';
    });
});
</script>

<?= $this->endSection(); ?>