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
          <form id="tambahPesawat" action="/infografis/tambah_pesawat" method="post">
            <div class="card-header bg-primary">
              <h3 class="card-title">Kedatangan Pesawat</h3>

              <div class="card-tools">
                <label class="has-float-label col-12">
                  <div class="input-group date" id="date" data-target-input="nearest">
                    <input type="text" name="tanggal" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" />
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
                    <input type="text" name="pesawat_datang_luar" class="form-control" id="pesawat_datang_luar" placeholder="Jumlah Pesawat" value="<?= old('pesawat_datang_luar'); ?>">
                    <span>Pesawat Dari Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="crew_datang_luar" class="form-control" id="crew_datang_luar" placeholder="Jumlah Crew" value="<?= old('crew_datang_luar'); ?>">
                    <span>Crew Dari Luar Negeri</span>
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
                    <input type="text" name="pesawat_datang_negeri" class="form-control" id="pesawat_datang_negeri" placeholder="Jumlah Pesawat" value="<?= old('pesawat_datang_negeri'); ?>">
                    <span>Pesawat Dari Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="crew_datang_negeri" class="form-control" id="crew_datang_negeri" placeholder="Jumlah Crew" value="<?= old('crew_datang_negeri'); ?>">
                    <span>Crew Dari Dalam Negeri</span>
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
              <h3 class="card-title">Keberangkatan Pesawat</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="form-group col-12">
                  <label for="">Luar Negeri</label>
                </div>

                <div class="form-group col-4">
                  <input type="text" name="pesawat_berangkat_luar" class="form-control" id="pesawat_berangkat_luar" placeholder="Jumlah Pesawat" value="<?= old('pesawat_berangkat_luar'); ?>">
                  <span>Pesawat ke Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="crew_berangkat_luar" class="form-control" id="crew_berangkat_luar" placeholder="Jumlah Crew" value="<?= old('crew_berangkat_luar'); ?>">
                    <span>Crew ke Luar Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_luar" class="form-control" id="penumpang_berangkat_luar" placeholder="Jumlah Penumpang" value="<?= old('penumpang_berangkat_luar'); ?>">
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
                    <input type="text" name="pesawat_berangkat_negeri" class="form-control" id="pesawat_berangkat_negeri" placeholder="Jumlah Pesawat" value="<?= old('pesawat_berangkat_negeri'); ?>">
                    <span>Pesawat ke Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="crew_berangkat_negeri" class="form-control" id="crew_berangkat_negeri" placeholder="Jumlah Crew" value="<?= old('crew_berangkat_negeri'); ?>">
                    <span>Crew ke Dalam Negeri</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="penumpang_berangkat_negeri" class="form-control" id="penumpang_berangkat_negeri" placeholder="Jumlah Penumpang" value="<?= old('penumpang_berangkat_negeri'); ?>">
                    <span>Penumpang ke Dalam Negeri</span>
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

<script>
  $(function() {
    // Date range picker
    $('#date').datetimepicker({
      format: 'L',
      format: 'YYYY-MM-DD',
      defaultDate: moment()
    });
  })
</script>
<?= $this->endSection(); ?>