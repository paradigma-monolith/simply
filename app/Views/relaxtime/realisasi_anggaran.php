<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>


<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5">

        <div class="card">
          <form action="/relaxtime/importRealisasi" class="form-relaxtime-import-pagu" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header">
              <h6>Import data Realisasi</h6>
              Pastikan file sesuai dengan <a href="/relaxtime/format_import_pagu.xlsx">format pagu</a> yang terlampir pada sistem!
            </div>

            <div class="card-body">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="file" id="customFileRelaxtime" required>
                <label class="custom-file-label" for="customFileRelaxtime">Lampirkan file realisasi (Sesuaikan format file)</label>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group mt-2">
                    <?php
                    $bulan = [
                      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
                    ];
                    ?>
                    <select class="form-control select2" name="bulan" required style="width: 100%;">
                      <option value=""> --Pilih Bulan-- </option>
                      <?php for ($i = 0; $i < 12; $i++) : ?>
                        <option value="<?= ($i + 1); ?>"><?= $bulan[$i]; ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group mt-2">
                    <input type="number" name="tahun" class="form-control" placeholder="2023" required>
                    <small class="form-text text-muted">Pastikan tahun diisi dengan benar</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-success float-right btn-relaxtime-pagu" disabled data-toggle="tooltip" title="Pastkan format adalah excel dan sesuai dengan format yang di sematkan pada link di atas"><i class="fas fa-upload"></i> Import</button>
            </div>
          </form>

        </div>
      </div>
    </div>
    <div class="relaxtime-pagu d-none"></div>
    <!-- /.card -->
  </div>
</section>

<section class="content">
  <div class="card">
    <!-- /.card-header -->
    <div class="card-body">
      <div id="jsGridRealisasiRelaxtime"></div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>
<?= $this->endSection(); ?>