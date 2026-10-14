<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Infografis Kapal</li>
  </ol>
</nav>
<div class="container-fluid">
  <div class="row">
    <!-- /.col-md-6 -->
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-info">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Kedatangan Kapal</a>
          <h3><?= $infografis['kapal_datang_negeri_kapal_total'] + $infografis['kapal_datang_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Kapal
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['kapal_datang_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['kapal_datang_negeri_kapal_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-ship mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-info">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Keberangkatan Kapal</a>
          <h3><?= $infografis['kapal_berangkat_negeri_kapal_total'] + $infografis['kapal_berangkat_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Kapal
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['kapal_berangkat_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['kapal_berangkat_negeri_kapal_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-departure mt-5"></i> -->
          <i class="fas fa-ship mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-warning">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Kedatangan ABK</a>
          <h3><?= $infografis['abk_datang_negeri_kapal_total'] + $infografis['abk_datang_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total ABK
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['abk_datang_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['abk_datang_negeri_kapal_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <i class="fas fa-anchor mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-warning">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Keberangkatan ABK</a>
          <h3><?= $infografis['abk_berangkat_negeri_kapal_total'] + $infografis['abk_berangkat_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total ABK
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['abk_berangkat_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['abk_berangkat_negeri_kapal_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <i class="fas fa-anchor mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-success">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Kedatangan Penumpang</a>
          <h3><?= $infografis['penumpang_datang_negeri_kapal_total'] + $infografis['penumpang_datang_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Penumpang
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['penumpang_datang_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['penumpang_datang_negeri_kapal_total']; ?></strong>
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
          <h3><?= $infografis['penumpang_berangkat_negeri_kapal_total'] + $infografis['penumpang_berangkat_luar_kapal_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Penumpang
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['penumpang_berangkat_luar_kapal_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['penumpang_berangkat_negeri_kapal_total']; ?></strong>
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
            <h3 class="card-title ">KAPAL</h3>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <p class="d-flex flex-column">
              <span class="text-bold text-lg">--Rekapitulasi--</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
              <span class="text-success">
                <!-- <i class="fas fa-arrow-up"></i> 33.1% -->
              </span>
            </p>
          </div>
          <!-- /.d-flex -->

          <div class="position-relative mb-4">
            <canvas id="kapal-chart" height="200"></canvas>
          </div>
          <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
              <i class="fas fa-square" style="color:#006a94;"></i><small> Totak Kedatangan Kapal</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#FFED45;"></i><small> Totak Kedatangan ABK</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#57FF5F;"></i><small> Totak Kedatangan Penumpang</small>
            </span>
          </div>
          <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
              <i class="fas fa-square" style="color:#003449;"></i><small> Totak Keberangkatan Kapal</small>
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
  'use strict'
  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }


  var mode = 'index'
  var intersect = true
  var tanggal_kapal = []
  var kapal_datang_kapal = []
  var kapal_berangkat_kapal = []
  var abk_datang_kapal = []
  var abk_berangkat_kapal = []
  var penumpang_datang_kapal = []
  var penumpang_berangkat_kapal = []


  <?php foreach ($dataKapal as $data) : ?>
    <?php if ($data['kapal_datang_negeri'] == 0 && $data['kapal_berangkat_negeri'] == 0 && $data['abk_datang_negeri'] == 0 && $data['abk_berangkat_negeri'] == 0 && $data['penumpang_datang_negeri'] == 0 && $data['penumpang_berangkat_negeri'] == 0) : ?>
    <?php else : ?>
      tanggal_kapal.push('<?= $data['tanggal']; ?>'.substring(0, 10))
      kapal_datang_kapal.push(parseInt(<?= $data['kapal_datang_negeri']; ?>) + parseInt(<?= $data['kapal_datang_luar']; ?>))
      kapal_berangkat_kapal.push(parseInt(<?= $data['kapal_berangkat_negeri']; ?>) + parseInt(<?= $data['kapal_berangkat_luar']; ?>))
      abk_datang_kapal.push(parseInt(<?= $data['abk_datang_negeri']; ?>) + parseInt(<?= $data['abk_datang_luar']; ?>))
      abk_berangkat_kapal.push(parseInt(<?= $data['abk_berangkat_negeri']; ?>) + parseInt(<?= $data['abk_berangkat_luar']; ?>))
      penumpang_datang_kapal.push(parseInt(<?= $data['penumpang_datang_negeri']; ?>) + parseInt(<?= $data['penumpang_datang_luar']; ?>))
      penumpang_berangkat_kapal.push(parseInt(<?= $data['penumpang_berangkat_negeri']; ?>) + parseInt(<?= $data['penumpang_berangkat_luar']; ?>))
    <?php endif; ?>
  <?php endforeach; ?>


  var $salesChart = $('#kapal-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: tanggal_kapal,
      datasets: [
        // =============Total Kedatangan================
        {
          backgroundColor: '#006a94',
          borderColor: '#006a94',
          data: kapal_datang_kapal
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#FFED45',
          borderColor: '#FFED45',
          data: abk_datang_kapal
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#57FF5F',
          borderColor: '#57FF5F',
          data: penumpang_datang_kapal
        },
        // =============Total Kedatangan=================
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#003449',
          borderColor: '#003449',
          data: kapal_berangkat_kapal
        },
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#796E00',
          borderColor: '#796E00',
          data: abk_berangkat_kapal
        },
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#008806',
          borderColor: '#008806',
          data: penumpang_berangkat_kapal
        },
        // ============Total Keberangkatan===============
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function(value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
  // ==============================================================================
</script>