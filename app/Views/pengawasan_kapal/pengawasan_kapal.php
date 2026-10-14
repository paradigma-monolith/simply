<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group mb-0">
                  <label class="mb-1">Filter tanggal:</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="datePengawasanKapal">
                  </div>
                </div>
              </div>
              <div class="col-md-9 d-flex align-items-end justify-content-end">
                <div class="btn-group mr-2" role="group">
                  <a href="<?= base_url('monitoring_sanitasi_kapal') ?>" class="btn btn-info btn-sm">
                    <i class="fas fa-clipboard-check"></i> Monitoring Sanitasi Kapal
                  </a>
                  <a href="<?= base_url('laporan_pengawasan_kapal') ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-file-alt"></i> Laporan Pengawasan Kapal
                  </a>
                </div>
                <a href="https://selfassessment.bkkbandung.net/" target="_blank" class="btn btn-primary btn-sm">
                  <i class="fas fa-ship"></i> Self Assessment
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="data-pengawasan-kapal"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>



<?= $this->endSection(); ?>