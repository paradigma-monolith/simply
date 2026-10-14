<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="row">
      <div class="col-md-12">

        <div class="card card-default">
          <!-- form start -->
          <form id="tambahKapal" action="/infografis/update_kapal" method="post">
            <div class="card-header bg-primary">
              <input type="hidden" name="id_infografis_kapal" value="<?= $infografis['id_infografis_kapal']; ?>">
              <h3 class="card-title">Kedatangan Kapal</h3>

              <div class="card-tools">
                <label class="has-float-label col-12">
                  <div class="input-group date" id="date" data-target-input="nearest">
                    <input value="<?= $infografis['created_at']; ?>" type="text" name="tanggal" readonly disabled class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </label>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="form-group col-12">
                  <label for="">Luar Negeri</label>
                  <!-- <hr> -->
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kapal_datang_luar" class="form-control" id="kapal_datang_luar" placeholder="Jumlah Kapal" value="<?= $infografis['kapal_datang_luar']; ?>">
                    <span>Kapal Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_datang_luar" class="form-control" id="abk_datang_luar" placeholder="Jumlah ABK" value="<?= $infografis['abk_datang_luar']; ?>">
                    <span>ABK Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_datang_luar" class="form-control" id="penumpang_datang_luar" placeholder="Jumlah Penumpang" value="<?= $infografis['penumpang_datang_luar']; ?>">
                    <span>Penumpang Dari Luar Negeri</span>
                  </label>
                </div>
                <!-- =========================================================================== -->
                <div class="form-group col-12"></div>
                <div class="form-group col-12">
                  <label for="">Dalam Negeri</label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kapal_datang_negeri" class="form-control" id="kapal_datang_negeri" placeholder="Jumlah Kapal" value="<?= $infografis['kapal_datang_negeri']; ?>">
                    <span>Kapal Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_datang_negeri" class="form-control" id="abk_datang_negeri" placeholder="Jumlah ABK" value="<?= $infografis['abk_datang_negeri']; ?>">
                    <span>ABK Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_datang_negeri" class="form-control" id="penumpang_datang_negeri" placeholder="Jumlah Penumpang" value="<?= $infografis['penumpang_datang_negeri']; ?>">
                    <span>Penumpang Dari Dalam Negeri</span>
                  </label>
                </div>
                <!-- =========================================================================== -->
              </div>
            </div>
            <div class="card-header bg-primary">
              <h3 class="card-title">Keberangkatan Kapal</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="form-group col-12">
                  <label for="">Luar Negeri</label>
                </div>

                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kapal_berangkat_luar" class="form-control" id="kapal_berangkat_luar" placeholder="Jumlah Kapal" value="<?= $infografis['kapal_berangkat_luar']; ?>">
                    <span>Kapal ke Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_berangkat_luar" class="form-control" id="abk_berangkat_luar" placeholder="Jumlah ABK" value="<?= $infografis['abk_berangkat_luar']; ?>">
                    <span>ABK ke Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_luar" class="form-control" id="penumpang_berangkat_luar" placeholder="Jumlah Penumpang" value="<?= $infografis['penumpang_berangkat_luar']; ?>">
                    <span>Penumpang ke Luar Negeri</span>
                  </label>
                </div>
                <!-- =========================================================================== -->
                <div class="form-group col-12"></div>
                <div class="form-group col-12">
                  <label for="">Dalam Negeri</label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kapal_berangkat_negeri" class="form-control" id="kapal_berangkat_negeri" placeholder="Jumlah Kapal" value="<?= $infografis['kapal_berangkat_negeri']; ?>">
                    <span>Kapal ke Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_berangkat_negeri" class="form-control" id="abk_berangkat_negeri" placeholder="Jumlah ABK" value="<?= $infografis['abk_berangkat_negeri']; ?>">
                    <span>ABK ke Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_negeri" class="form-control" id="penumpang_berangkat_negeri" placeholder="Jumlah Penumpang" value="<?= $infografis['penumpang_berangkat_negeri']; ?>">
                    <span>Kapal ke Dalam Negeri</span>
                  </label>
                </div>
                <!-- =========================================================================== -->


              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection(); ?>