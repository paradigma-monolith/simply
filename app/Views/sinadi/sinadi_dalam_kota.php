<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-footer">
            <div class="form-group mt-4">
              <label class="has-float-label">
                <div class="input-group">
                  <input type="text" name="tanggal" id="sinadiAdmin" autocomplete="off" class="form-control">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                </div>
                <span>Tanggal Perjalanan Dinas</span>
              </label>
            </div>
          </div>
          <div class="card-header row">
            <div class="col-6">
              <!-- <h3>Kedatangan Kapal</h3> -->
            </div>
            <div class="col-6 text-right">
              <a href="/sinadi/<?= $activeMenu == 'dinasDalamKota' ? 'formulirPerjalananDinas' : 'formulirPerjalananDinasAntarKota'; ?>" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <input type="hidden" id="activeMenuInput" value="<?= $activeMenu; ?>">
            <div class="sinadi_dalam_kota_view"></div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
</section>
<?= $this->endSection(); ?>