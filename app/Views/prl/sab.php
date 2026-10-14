<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <div class="text-right">
              <a href="/prl/tambah_sab" class="btn bg-gradient-success"><i class="fas fa-plus"></i> SAB Baru</a>
            </div>
          </div>
          <div class="card-body">
            <h4>DAFTAR SAB</h4>
            <div class="prl_daftar_sab"></div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="text-right">
              <a href="/prl/tambah_inspeksi_sab" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Inspeksi Baru</a>
            </div>
          </div>
          <div class="card-body">
            <form action="/prl/excel_inspeksi_sab" class="d-inline" method="post">
              <div class="form-group">
                <label>Filter tanggal:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control float-right" id="dateInspeksiSab" name="dateInspeksiSab">
                </div>
              </div>
              <div class=" text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export ke Excel</button>
              </div>
            </form>
            <hr>
            <h4>DAFTAR INSPEKSI SAB</h4>
            <div class="prl_daftar_inspeksi_sab"></div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>