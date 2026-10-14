<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="main-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Infografis Vaksin</li>
  </ol>
</nav>
<div class="container-fluid">
  <div class="row">
    <!-- /.col-md-6 -->
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-success">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Miningitis</a>
          <br />
          <h3><?= $infografis['vaksin_mm']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-danger">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Yelow Fever</a>
          <br />
          <h3><?= $infografis['vaksin_yf']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-primary">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Covid</a>
          <br />
          <h3><?= $infografis['vaksin_covid']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-warning">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Influenza</a>
          <br />
          <h3><?= $infografis['vaksin_influenza']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-info">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Tifoid</a>
          <br />
          <h3><?= $infografis['vaksin_tifoid']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->
    <div class="col-xl-3 col-md-4 col-sm-6">
      <!-- small box -->
      <div class="small-box-custom bg-secondary">
        <div class="inner-custom">
          <a href="#" class="small-box-header">Vaksin Polio</a>
          <br />
          <h3><?= $infografis['vaksin_polio']; ?></h3>

          <div class="row">
            <p>
              &nbsp; <br />&nbsp;
            </p>
          </div>
        </div>
        <div class="icon-custom">
          <!-- <i class="fas fa-plane-arrival mt-5"></i> -->
          <i class="fas fa-disease mt-5"></i>
        </div>
      </div>
    </div>
    <!-- ================================================================= -->

    <!-- ================================================================= -->

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title "> Vaksinasi </h3>
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
            <canvas id="vaksin-chart" height="200"></canvas>
          </div>
          <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
              <i class="fas fa-square" style="color:#24963e;"></i><small> Vaksin Miningitis</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#c6303e;"></i><small> Vaksin Yelow Fever</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#006ee5;"></i><small> Vaksin Covid-19</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#ffc105;"></i><small> Vaksin Influenza</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#17a2b8;"></i><small> Vaksin Tifoid</small>
            </span>
            <span class="mr-2">
              <i class="fas fa-square" style="color:#6c757d;"></i><small> Vaksin Polio</small>
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
  var serti_vaksin = []
  var vaksin_mm_vaksin = []
  var vaksin_yf_vaksin = []
  var vaksin_covid_vaksin = []
  var vaksin_influenza_vaksin = []
  var vaksin_tifoid_vaksin = []
  var vaksin_polio_vaksin = []


  <?php foreach ($dataVaksin as $data) : ?>
    <?php if ($data['vaksin_mm'] == 0 && $data['vaksin_yf'] == 0 && $data['vaksin_covid'] == 0 && $data['vaksin_influenza'] == 0 && $data['vaksin_tifoid'] == 0 && $data['vaksin_polio'] == 0) : ?>
    <?php else : ?>
      serti_vaksin.push('<?= $data['tanggal']; ?>'.substring(0, 10))
      vaksin_mm_vaksin.push(parseInt(<?= $data['vaksin_mm']; ?>))
      vaksin_yf_vaksin.push(parseInt(<?= $data['vaksin_yf']; ?>))
      vaksin_covid_vaksin.push(parseInt(<?= $data['vaksin_covid']; ?>))
      vaksin_influenza_vaksin.push(parseInt(<?= $data['vaksin_influenza']; ?>))
      vaksin_tifoid_vaksin.push(parseInt(<?= $data['vaksin_tifoid']; ?>))
      vaksin_polio_vaksin.push(parseInt(<?= $data['vaksin_polio']; ?>))
    <?php endif; ?>
  <?php endforeach; ?>


  var $salesChart = $('#vaksin-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: serti_vaksin,
      datasets: [
        // =============Total Kedatangan================
        {
          backgroundColor: '#24963e',
          borderColor: '#24963e',
          data: vaksin_mm_vaksin
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#c6303e',
          borderColor: '#c6303e',
          data: vaksin_yf_vaksin
        },
        // =============Total Kedatangan================
        {
          backgroundColor: '#006ee5',
          borderColor: '#006ee5',
          data: vaksin_covid_vaksin
        },
        // =============Total Kedatangan=================  
        {
          backgroundColor: '#ffc105',
          borderColor: '#ffc105',
          data: vaksin_influenza_vaksin
        },
        // =============Total Kedatangan=================  
        {
          backgroundColor: '#17a2b8',
          borderColor: '#17a2b8',
          data: vaksin_tifoid_vaksin
        },
        // =============Total Kedatangan=================  
        {
          backgroundColor: '#6c757d',
          borderColor: '#6c757d',
          data: vaksin_polio_vaksin
        },
        // =============Total Kedatangan=================        
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