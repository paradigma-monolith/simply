<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col-md-6 -->
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-info">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Kedatangan Pesawat</a>
            <h3 id="datang_total_keseluruhan_pesawat">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total pesawat <br />
                  <strong id="datang_total_ln_pesawat">-</strong> LN,
                  <strong id="datang_total_dn_pesawat">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_datang_pesawat"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="datang_ln_pesawat">-</strong> LN,
                  <strong id="datang_dn_pesawat">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-plane-arrival mt-5"></i>
            <!-- <i class="fas fa-ship mt-5"></i> -->
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-info">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Keberangkatan Pesawat</a>
            <h3 id="berangkat_total_keseluruhan_pesawat">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total pesawat <br />
                  <strong id="berangkat_total_ln_pesawat">-</strong> LN,
                  <strong id="berangkat_total_dn_pesawat">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_berangkat_pesawat"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="berangkat_ln_pesawat">-</strong> LN,
                  <strong id="berangkat_dn_pesawat">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-plane-departure mt-5"></i>
            <!-- <i class="fas fa-ship mt-5"></i> -->
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-warning">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Kedatangan Crew</a>
            <h3 id="datang_total_keseluruhan_crew">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total Crew <br />
                  <strong id="datang_total_ln_crew">-</strong> LN,
                  <strong id="datang_total_dn_crew">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_datang_crew"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="datang_ln_crew">-</strong> LN,
                  <strong id="datang_dn_crew">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-user-tie mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-warning">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Keberangkatan Crew</a>
            <h3 id="berangkat_total_keseluruhan_crew">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total Crew <br />
                  <strong id="berangkat_total_ln_crew">-</strong> LN,
                  <strong id="berangkat_total_dn_crew">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_berangkat_crew"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="berangkat_ln_crew">-</strong> LN,
                  <strong id="berangkat_dn_crew">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-user-tie mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-success">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Kedatangan Penumpang</a>
            <h3 id="datang_total_keseluruhan_penumpang">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total Penumpang <br />
                  <strong id="datang_total_ln_penumpang">-</strong> LN,
                  <strong id="datang_total_dn_penumpang">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_datang_penumpang"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="datang_ln_penumpang">-</strong> LN,
                  <strong id="datang_dn_penumpang">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-users mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box-custom bg-success">
          <div class="inner-custom">
            <a href="#" class="small-box-header">Keberangkatan Penumpang</a>
            <h3 id="berangkat_total_keseluruhan_penumpang">-</h3>

            <div class="row">
              <div class="col-4">
                <p>Total Penumpang <br />
                  <strong id="berangkat_total_ln_penumpang">-</strong> LN,
                  <strong id="berangkat_total_dn_penumpang">-</strong> DN
                </p>
              </div>
              <div class="col-8">
                <p>(<span id="tgl_berangkat_penumpang"></span>)</small> <br />Terakhir kali diinput<br />
                  <strong id="berangkat_ln_penumpang">-</strong> LN,
                  <strong id="berangkat_dn_penumpang">-</strong> DN
                </p>
              </div>
            </div>
          </div>
          <div class="icon-custom">
            <i class="fas fa-users mt-5"></i>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title ">Pesawat</h3>
              <a href="/infografis/pesawat">View Report</a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">--Rekapitulasi--</span>
                <span>Kedatangan seiring waktu 5 hari terakhir</span>
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
                <i class="fas fa-square" style="color:#006a94;"></i><small> Totak Kedatangan Pesawat</small>
              </span>
              <span class="mr-2">
                <i class="fas fa-square" style="color:#FFED45;"></i><small> Totak Kedatangan Crew</small>
              </span>
              <span class="mr-2">
                <i class="fas fa-square" style="color:#57FF5F;"></i><small> Totak Kedatangan Penumpang</small>
              </span>
            </div>
            <div class="d-flex flex-row justify-content-end">
              <span class="mr-2">
                <i class="fas fa-square" style="color:#003449;"></i><small> Totak Keberangkatan Pesawat</small>
              </span>
              <span class="mr-2">
                <i class="fas fa-square" style="color:#796E00;"></i><small> Totak Keberangkatan ABK</small>
              </span>
              <span class="mr-2">
                <i class="fas fa-square" style="color:#008806;"></i><small> Totak Keberangkatan Penumpang</small>
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- ================================================================= -->
    </div>
  </div>

  <script>

  </script>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>