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
          <form id="validationKapal" action="#" method="post">
            <!-- <form id="tambahKapal" action="/infografis/tambah_kapal" method="post"> -->
            <div class="card-header bg-primary">
              <h3 class="card-title">Kedatangan Kapal</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <?php foreach ($rekapitulasi_kapal as $value => $kapal) : ?>
                    <input type="hidden" readonly id="kapal_datang_negeri<?= $value; ?>" value="<?= $kapal['kapal_datang_negeri']; ?>">
                    <input type="hidden" readonly id="kapal_berangkat_negeri<?= $value; ?>" value="<?= $kapal['kapal_berangkat_negeri']; ?>">
                    <input type="hidden" readonly id="abk_datang_negeri<?= $value; ?>" value="<?= $kapal['abk_datang_negeri']; ?>">
                    <input type="hidden" readonly id="abk_berangkat_negeri<?= $value; ?>" value="<?= $kapal['abk_berangkat_negeri']; ?>">
                    <input type="hidden" readonly id="penumpang_datang_negeri<?= $value; ?>" value="<?= $kapal['penumpang_datang_negeri']; ?>">
                    <input type="hidden" readonly id="penumpang_berangkat_negeri<?= $value; ?>" value="<?= $kapal['penumpang_berangkat_negeri']; ?>">
                    <input type="hidden" readonly id="kapal_datang_luar<?= $value; ?>" value="<?= $kapal['kapal_datang_luar']; ?>">
                    <input type="hidden" readonly id="kapal_berangkat_luar<?= $value; ?>" value="<?= $kapal['kapal_berangkat_luar']; ?>">
                    <input type="hidden" readonly id="abk_datang_luar<?= $value; ?>" value="<?= $kapal['abk_datang_luar']; ?>">
                    <input type="hidden" readonly id="abk_berangkat_luar<?= $value; ?>" value="<?= $kapal['abk_berangkat_luar']; ?>">
                    <input type="hidden" readonly id="penumpang_datang_luar<?= $value; ?>" value="<?= $kapal['penumpang_datang_luar']; ?>">
                    <input type="hidden" readonly id="penumpang_berangkat_luar<?= $value; ?>" value="<?= $kapal['penumpang_berangkat_luar']; ?>">
                    <input type="hidden" readonly id="created_at<?= $value; ?>" value="<?= substr($kapal['created_at'], 0, 10); ?>">
                  <?php endforeach; ?>
                </div>
                <div class="form-group col-12">
                  <label for="">Luar Negeri</label>
                  <!-- <hr> -->
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kapal_datang_luar" class="form-control" id="kapal_datang_luar" placeholder="Jumlah Kapal" value="<?= old('kapal_datang_luar'); ?>">
                    <span>Kapal Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_datang_luar" class="form-control" id="abk_datang_luar" placeholder="Jumlah ABK" value="<?= old('abk_datang_luar'); ?>">
                    <span>ABK Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_datang_luar" class="form-control" id="penumpang_datang_luar" placeholder="Jumlah Penumpang" value="<?= old('penumpang_datang_luar'); ?>">
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
                    <input type="text" name="kapal_datang_negeri" class="form-control" id="kapal_datang_negeri" placeholder="Jumlah Kapal" value="<?= old('kapal_datang_negeri'); ?>">
                    <span>Kapal Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_datang_negeri" class="form-control" id="abk_datang_negeri" placeholder="Jumlah ABK" value="<?= old('abk_datang_negeri'); ?>">
                    <span>ABK Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_datang_negeri" class="form-control" id="penumpang_datang_negeri" placeholder="Jumlah Penumpang" value="<?= old('penumpang_datang_negeri'); ?>">
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
                    <input type="text" name="kapal_berangkat_luar" class="form-control" id="kapal_berangkat_luar" placeholder="Jumlah Kapal" value="<?= old('kapal_berangkat_luar'); ?>">
                    <span>Kapal Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_berangkat_luar" class="form-control" id="abk_berangkat_luar" placeholder="Jumlah ABK" value="<?= old('abk_berangkat_luar'); ?>">
                    <span>ABK Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_luar" class="form-control" id="penumpang_berangkat_luar" placeholder="Jumlah Penumpang" value="<?= old('penumpang_berangkat_luar'); ?>">
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
                    <input type="text" name="kapal_berangkat_negeri" class="form-control" id="kapal_berangkat_negeri" placeholder="Jumlah Kapal" value="<?= old('kapal_berangkat_negeri'); ?>">
                    <span>Kapal Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="abk_berangkat_negeri" class="form-control" id="abk_berangkat_negeri" placeholder="Jumlah ABK" value="<?= old('abk_berangkat_negeri'); ?>">
                    <span>ABK Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_negeri" class="form-control" id="penumpang_berangkat_negeri" placeholder="Jumlah Penumpang" value="<?= old('penumpang_berangkat_negeri'); ?>">
                    <span>Penumpang Dari Dalam Negeri</span>
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