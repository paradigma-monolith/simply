<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
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
    <div class="row">
      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title ">KAPAL DATANG</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Kedatangan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">KAPAL BERANGKAT</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Keberangkatan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart2" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">ABK DATANG</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Kedatangan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart3" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">ABK BERANGKAT</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Keberangkatan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart4" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">PENUMPANG DATANG</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Kedatangan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart5" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->

      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">PENUMPANG BERANGKAT</h3>
              <a href="javascript:void(0);">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Keberangkatan seiring waktu 10 hari terakhir</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="text-success">
                  <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
                </span>
                <span class="text-muted">Terakhir kali pengecekan</span>
              </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
              <canvas id="sales-chart6" height="200"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square text-primary"></i> Dari Luar Negeri
              </span>

              <span>
                <i class="fas fa-square text-red"></i> Dari Dalam Negeri
              </span>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>