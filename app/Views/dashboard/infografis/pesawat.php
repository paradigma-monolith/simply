<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Infografis pesawat</li>
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
          <a href="#" class="small-box-header">Kedatangan Pesawat</a>
          <h3><?= $infografis['pesawat_datang_negeri_pesawat_total'] + $infografis['pesawat_datang_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Pesawat
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['pesawat_datang_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['pesawat_datang_negeri_pesawat_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <i class="fas fa-plane-arrival mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-info">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Keberangkatan Pesawat</a>
          <h3><?= $infografis['pesawat_berangkat_negeri_pesawat_total'] + $infografis['pesawat_berangkat_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Pesawat
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['pesawat_berangkat_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['pesawat_berangkat_negeri_pesawat_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom">
          <i class="fas fa-plane-departure mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-warning">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Kedatangan Crew</a>
          <h3><?= $infografis['crew_datang_negeri_pesawat_total'] + $infografis['crew_datang_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Crew
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['crew_datang_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['crew_datang_negeri_pesawat_total']; ?></strong>
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
          <h3><?= $infografis['crew_berangkat_negeri_pesawat_total'] + $infografis['crew_berangkat_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Crew
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['crew_berangkat_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['crew_berangkat_negeri_pesawat_total']; ?></strong>
              </p>
            </div>
          </div>
        </div>
        <div class="icon-custom"> <i class="fas fa-user-tie mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box-custom bg-success">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Kedatangan Penumpang</a>
          <h3><?= $infografis['penumpang_datang_negeri_pesawat_total'] + $infografis['penumpang_datang_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Penumpang
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['penumpang_datang_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['penumpang_datang_negeri_pesawat_total']; ?></strong>
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
          <h3><?= $infografis['penumpang_berangkat_negeri_pesawat_total'] + $infografis['penumpang_berangkat_luar_pesawat_total']; ?></h3>

          <div class="row">
            <div class="col-4">
              <p>Total Penumpang
              </p>
            </div>
            <div class="col-8">
              <p>
                Luar Negeri: <strong><?= $infografis['penumpang_berangkat_luar_pesawat_total']; ?></strong>
              </p>
              <p>
                Dalam Negeri: <strong><?= $infografis['penumpang_berangkat_negeri_pesawat_total']; ?></strong>
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
            <h3 class="card-title ">PESAWAT</h3>
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
            <canvas id="pesawat-chart" height="200"></canvas>
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
              <i class="fas fa-square" style="color:#796E00;"></i><small> Totak Keberangkatan Crew</small>
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
  var tanggal_pesawat = []
  var pesawat_datang_pesawat = []
  var pesawat_berangkat_pesawat = []
  var crew_datang_pesawat = []
  var crew_berangkat_pesawat = []
  var penumpang_datang_pesawat = []
  var penumpang_berangkat_pesawat = []


  <?php foreach ($dataPesawat as $data) : ?>

    <?php if ($data['pesawat_datang_negeri'] == 0 && $data['pesawat_berangkat_negeri'] == 0 && $data['crew_datang_negeri'] == 0 && $data['crew_berangkat_negeri'] == 0 && $data['penumpang_datang_negeri'] == 0 && $data['penumpang_berangkat_negeri'] == 0) : ?>
    <?php else : ?>
      tanggal_pesawat.push('<?= $data['tanggal']; ?>'.substring(0, 10))
      pesawat_datang_pesawat.push(parseInt(<?= $data['pesawat_datang_negeri']; ?>) + parseInt(<?= $data['pesawat_datang_luar']; ?>))
      pesawat_berangkat_pesawat.push(parseInt(<?= $data['pesawat_berangkat_negeri']; ?>) + parseInt(<?= $data['pesawat_berangkat_luar']; ?>))
      crew_datang_pesawat.push(parseInt(<?= $data['crew_datang_negeri']; ?>) + parseInt(<?= $data['crew_datang_luar']; ?>))
      crew_berangkat_pesawat.push(parseInt(<?= $data['crew_berangkat_negeri']; ?>) + parseInt(<?= $data['crew_berangkat_luar']; ?>))
      penumpang_datang_pesawat.push(parseInt(<?= $data['penumpang_datang_negeri']; ?>) + parseInt(<?= $data['penumpang_datang_luar']; ?>))
      penumpang_berangkat_pesawat.push(parseInt(<?= $data['penumpang_berangkat_negeri']; ?>) + parseInt(<?= $data['penumpang_berangkat_luar']; ?>))
    <?php endif; ?>
  <?php endforeach; ?>


  var $salesChart = $('#pesawat-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: tanggal_pesawat,
      datasets: [
        // =============Total Kedatangan================
        {
          backgroundColor: '#006a94',
          borderColor: '#006a94',
          data: pesawat_datang_pesawat
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#FFED45',
          borderColor: '#FFED45',
          data: crew_datang_pesawat
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#57FF5F',
          borderColor: '#57FF5F',
          data: penumpang_datang_pesawat
        },
        // =============Total Kedatangan=================
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#003449',
          borderColor: '#003449',
          data: pesawat_berangkat_pesawat
        },
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#796E00',
          borderColor: '#796E00',
          data: crew_berangkat_pesawat
        },
        // ============Total Keberangkatan===============
        {
          backgroundColor: '#008806',
          borderColor: '#008806',
          data: penumpang_berangkat_pesawat
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