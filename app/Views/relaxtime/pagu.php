<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>


<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-md-7">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-stripped table-sm">
              <thead>
                <tr>
                  <th>Beralih ke halaman edit pagu</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">RKT</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="rkt">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none; display: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">KRO</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="kro">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none; display: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">RO</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="ro">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">Komponen</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="komponen">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none; display: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">Sub Komponen</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="sub-komponen">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none; display: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">Akun</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="akun">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none; display: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="row m-0">
                      <div class="col-5">Detil</div>
                      <div class="col-4">
                        <input type="hidden" name="kode" value="detil">
                        <select name="pagu-list-relaxtime-edit-tahun" style="border: none;">
                          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= $i == date("Y") ? 'selected' : ""; ?>><?= $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3"><button class="btn btn-primary btn-sm edit-relaxtime-page-pagu"><i class="fas fa-edit"></i></button></div>
                    </div>
                  </td>
                </tr>
                <tr>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <!-- /.card-header -->
        <div class="card">
          <form action="/relaxtime/importPagu" class="form-relaxtime-import-pagu" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header">
              <h6>Import data Pagu</h6>
              Pastikan file sesuai dengan <a href="/relaxtime/format_import_pagu.xlsx">format pagu</a> yang terlampir pada sistem!
            </div>

            <div class="card-body">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="file" id="customFileRelaxtime" required>
                <label class="custom-file-label" for="customFileRelaxtime">Lampirkan file pagu (Sesuaikan format file)</label>
              </div>
              <div class="form-group mt-2">
                <input type="number" name="tahun" class="form-control" placeholder="2023" required>
                <small class="form-text text-muted">Pastikan tahun diisi dengan benar</small>
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



<?= $this->endSection(); ?>