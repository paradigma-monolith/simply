  <?= $this->extend('layouts/adminLTE-3.2.0'); ?>

  <?= $this->section('content'); ?>

  <section class="content">
    <!-- Default box -->
    <div class="card">
      <div class="card-footer">
        <form action="/uklw/excel/export_pemkes" method="post" target="_blank">
          <input type="hidden" name="activeMenu" value="<?= $activeMenu; ?>">
          <div class="row mt-4">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="has-float-label">
                  <div class="input-group">
                    <input type="text" name="filter_tanggal" id="filterTanggal" autocomplete="off" class="form-control">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                  </div>
                  <span>Filter data berdasarkan tanggal</span>
                </label>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <select name="status_pemeriksaan" style="width: 100%;" class="select2">
                  <option value="" selected>--Semua Status Pemeriksaan--</option>
                  <option value="Penumpang">Penjamah Makanan</option>
                  <option value="Jemaah Haji">Penjamah Makanan</option>
                  <option value="Petugas">Penjamah Makanan</option>
                  <option value="Penjamah Makanan">Penjamah Makanan</option>
                  <option value="Masyarakat Umum">Penjamah Makanan</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <select name="kota" class="form-control select2" required>
                  <option value="">--Semua Kota/Kab.--</option>
                  <?php foreach ($kota as $data) : ?>
                    <option value="<?= $data['kota_kabupaten']; ?>"><?= $data['kota_kabupaten']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm">
              <button type="submit" class="btn btn-sm btn-block btn-success"><i class="fas fa-file-excel"></i> Export</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-header">
        <h3 class="card-title">
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body px-0">

        <div class="data_pemeriksaan_view">
          <div class="text-center"><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </section>
  <?= $this->endSection(); ?>