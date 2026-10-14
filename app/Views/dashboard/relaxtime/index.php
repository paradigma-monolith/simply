<?php if ($getDataTargetBulanan) :
  $arrayTargetBulanan = unserialize($getDataTargetBulanan['uraian']);
endif; ?>
<?php
foreach ($getData515253 as $data) :
  ${"kode" . $data['kode_prefix']} = $data['total_nominal'];
endforeach;
$sisaTarget = [];
foreach ($getDataRealisasiBulanan as $data) :
  ${"kodeRealisasi" . $data['kode_prefix'] . 'bulan' . $data['bulan']} = $data['total_nominal'];

  if (isset($sisaTarget[$data['bulan']])) {
    $sisaTarget[$data['bulan']] += $data['total_nominal'];
  } else {
    $sisaTarget[$data['bulan']] = $data['total_nominal'];
  }

endforeach;
?>
<?php
$bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nombember', 'Desember'];
?>
<div class="container-fluid">
  <div class="container-fluid">
    <div class="card">
      <div class="card-footer">
        <div class="row">
          <div class="col-sm-7 graph1">
            <div class="row">
              <div class="col-12">
                <h4 class="text-center">Rencana Bulanan</h4>
                <canvas id="targetRealisasiChart" width="600" height="400"></canvas>
              </div>
            </div>
          </div>
          <div class="col-sm-6 graph2 d-none">
            <div class="row">
              <div class="col-12">
                <h4 class="text-center">Rencana Bulanan</h4>
                <canvas id="TargetheaderOilChart" height="20"></canvas>
              </div>
              <?php for ($i = 0; $i < count($bulan); $i++) : ?>
                <div class="col-4 canvasRealisasiBulanan canvas<?= $i; ?>">
                  <h5 class="text-center"><?= $bulan[$i]; ?></h5>
                  <canvas id="TargetoilChart<?= $i; ?>"></canvas>
                </div>
              <?php endfor; ?>
            </div>
          </div>
          <div class="col-sm-5 graph2">
            <div class="row">
              <div class="col-12">
                <h4 class="text-center">Realisasi Bulanan</h4>
                <canvas id="headerOilChart" height="20"></canvas>
              </div>
              <?php for ($i = 0; $i < count($bulan); $i++) : ?>
                <div class="col-4 canvasRealisasiBulanan canvas<?= $i; ?>">
                  <h5 class="text-center"><?= $bulan[$i]; ?></h5>
                  <canvas id="oilChart<?= $i; ?>"></canvas>
                </div>
              <?php endfor; ?>
            </div>
          </div>
          <div class="col-12">
            <div class="card mt-2">
              <div class="card-body">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th rowspan="2" style="width: 0px;"><button class="btn btn-success btn-sm d-block headCanvasRealisasiBulananButton" style="height: 100%;"><i class="fas fa-sm fa-eye"></i></button></th>
                      <th rowspan="2" class="align-middle text-center">Bulan</th>
                      <th colspan="3" class="text-center">Rencana Bulanan</th>
                      <th colspan="3" class="text-center">Realisasi Bulanan</th>
                      <th colspan="3" class="text-center">Persentase</th>
                    </tr>
                    <tr>
                      <th>51</th>
                      <th>52</th>
                      <th>53</th>
                      <th>51</th>
                      <th>52</th>
                      <th>53</th>
                      <th>51</th>
                      <th>52</th>
                      <th>53</th>
                    </tr>
                  </thead>
                  <?php
                  $jmlRencanaBulanan = 0;
                  $jmlRealisasiBulanan = 0;
                  for ($i = 0; $i < count($bulan); $i++) :
                    $jmlRencanaBulanan += $arrayTargetBulanan['bulan' . ($i + 1) . '51'];
                    $jmlRencanaBulanan += $arrayTargetBulanan['bulan' . ($i + 1) . '52'];
                    $jmlRencanaBulanan += $arrayTargetBulanan['bulan' . ($i + 1) . '53'];
                    $jmlRealisasiBulanan += isset(${"kodeRealisasi51bulan" . ($i + 1)}) ? ${"kodeRealisasi51bulan" . ($i + 1)} : 0;
                    $jmlRealisasiBulanan += isset(${"kodeRealisasi52bulan" . ($i + 1)}) ? ${"kodeRealisasi52bulan" . ($i + 1)} : 0;
                    $jmlRealisasiBulanan += isset(${"kodeRealisasi53bulan" . ($i + 1)}) ? ${"kodeRealisasi53bulan" . ($i + 1)} : 0;
                  ?>
                    <tr>
                      <td><button class="btn btn-success btn-sm d-block align-middle text-center canvasRealisasiBulananButton" data-canvas="<?= $i; ?>"><i class="fas fa-sm fa-eye"></i></button></td>
                      <td><?= $bulan[$i]; ?></td>
                      <td>Rp <?= number_format($arrayTargetBulanan['bulan' . ($i + 1) . '51'], '2', '.', ','); ?></td>
                      <td>Rp <?= number_format($arrayTargetBulanan['bulan' . ($i + 1) . '52'], '2', '.', ','); ?></td>
                      <td>Rp <?= number_format($arrayTargetBulanan['bulan' . ($i + 1) . '53'], '2', '.', ','); ?></td>
                      <td>Rp <?= number_format(isset(${"kodeRealisasi51bulan" . ($i + 1)}) ? ${"kodeRealisasi51bulan" . ($i + 1)} : '0', '2', '.', ','); ?></td>
                      <td>Rp <?= number_format(isset(${"kodeRealisasi52bulan" . ($i + 1)}) ? ${"kodeRealisasi52bulan" . ($i + 1)} : '0', '2', '.', ','); ?></td>
                      <td>Rp <?= number_format(isset(${"kodeRealisasi53bulan" . ($i + 1)}) ? ${"kodeRealisasi53bulan" . ($i + 1)} : '0', '2', '.', ','); ?></td>
                      <?php if ($arrayTargetBulanan['bulan' . ($i + 1) . '51'] == 0) : ?>
                        <?php if (isset(${"kodeRealisasi51bulan" . ($i + 1)}) && ${"kodeRealisasi51bulan" . ($i + 1)} != 0) : ?>
                          <td>
                            <i class="fas fa-infinity text-danger"></i>
                          </td>
                        <?php else : ?>
                          <td>-</td>
                        <?php endif; ?>
                      <?php else : ?>
                        <?php $persentase = ((isset(${"kodeRealisasi51bulan" . ($i + 1)}) ? ${"kodeRealisasi51bulan" . ($i + 1)} : 0) / $arrayTargetBulanan['bulan' . ($i + 1) . '51']) * 100; ?>
                        <td class="<?= $persentase > $getSetting['batas_nilai_atas'] ? 'table-danger' : ''; ?>">
                          <?= number_format($persentase, '2', '.', ','); ?><sup>%</sup>
                        </td>
                      <?php endif; ?>
                      <?php if ($arrayTargetBulanan['bulan' . ($i + 1) . '52'] == 0) : ?>
                        <?php if (isset(${"kodeRealisasi52bulan" . ($i + 1)}) && ${"kodeRealisasi52bulan" . ($i + 1)} != 0) : ?>
                          <td>
                            <i class="fas fa-infinity text-danger"></i>
                          </td>
                        <?php else : ?>
                          <td>-</td>
                        <?php endif; ?>
                      <?php else : ?>
                        <?php $persentase = ((isset(${"kodeRealisasi52bulan" . ($i + 1)}) ? ${"kodeRealisasi52bulan" . ($i + 1)} : 0) / $arrayTargetBulanan['bulan' . ($i + 1) . '52']) * 100; ?>
                        <td class="<?= $persentase > $getSetting['batas_nilai_atas'] ? 'table-danger' : ''; ?>">
                          <?= number_format($persentase, '2', '.', ','); ?><sup>%</sup>
                        </td>
                      <?php endif; ?>
                      <?php if ($arrayTargetBulanan['bulan' . ($i + 1) . '53'] == 0) : ?>
                        <?php if (isset(${"kodeRealisasi53bulan" . ($i + 1)}) && ${"kodeRealisasi53bulan" . ($i + 1)} != 0) : ?>
                          <td>
                            <i class="fas fa-infinity text-danger"></i>
                          </td>
                        <?php else : ?>
                          <td>-</td>
                        <?php endif; ?>
                      <?php else : ?>
                        <?php $persentase = ((isset(${"kodeRealisasi53bulan" . ($i + 1)}) ? ${"kodeRealisasi53bulan" . ($i + 1)} : 0) / $arrayTargetBulanan['bulan' . ($i + 1) . '53']) * 100; ?>
                        <td class="<?= $persentase > $getSetting['batas_nilai_atas'] ? 'table-danger' : ''; ?>">
                          <?= number_format($persentase, '2', '.', ','); ?><sup>%</sup>
                        </td>
                      <?php endif; ?>
                    </tr>
                  <?php endfor; ?>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>Total</th>
                      <th colspan="3" class="text-right">Rp <?= number_format($jmlRencanaBulanan, '2', '.', ','); ?></th>
                      <th colspan="3" class="text-right">Rp <?= number_format($jmlRealisasiBulanan, '2', '.', ','); ?></th>
                      <th colspan="3" class="text-right"><?= number_format(($jmlRealisasiBulanan / $jmlRencanaBulanan) * 100, '2', '.', ','); ?><sup>%</sup></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class=" row">
    <div class="col-sm-5">
      <div class="card card-primary">
        <div class="card-header">
          <h3>Data Keseluruhan</h3>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <strong>Total Pagu</strong> <a class="float-right keseluruhanPagu">...</a>
            </li>
            <li class="list-group-item">
              <strong>Total Realisasi</strong> <a class="float-right keseluruhanRealisasi">...</a>
            </li>
            <li class="list-group-item">
              <strong>Total presentase dari total pagu</strong> <a class="float-right keseluruhanPersentase">...%</a>
            </li>
          </ul>
        </div>
        <div class="card-footer"></div>
      </div>
      <div class="card card-light">
        <div class="card-header">
          <h3>Target Bulanan (Persentase)</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <?php for ($i = 0; $i < count($bulan); $i++) : ?>
              <?php if (isset($getDataBulanan[$i]['bulan']) == ($i + 1)) : ?>
                <div class="col-lg-4 col-6">
                  <!-- small box -->
                  <div class="small-box <?= (($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) >= $getSetting['batas_nilai_bawah'] && (($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) <= $getSetting['batas_nilai_atas'] ? "bg-success" : ((($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) > $getSetting['batas_nilai_atas'] ? 'bg-danger' : 'bg-light'); ?>">
                    <div class="inner">
                      <h3><span><?= number_format(($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100, 2, ".", ","); ?></span><sup style="font-size: 20px">%</sup></h3>

                      <p><?= $bulan[$i]; ?></p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <button onclick="getDataRealisasiDashboard(<?= ($i + 1); ?>)" data-bulanan="<?= $getDataTargetBulanan['bulan' . ($i + 1)]; ?>" data-realisasi="<?= $getDataBulanan[$i]['jmlRealisasi']; ?>" class="btn btn-block btn-sm small-box-footer <?= (($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) >= $getSetting['batas_nilai_bawah'] && (($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) <= $getSetting['batas_nilai_atas'] ? "text-light" : ((($getDataBulanan[$i]['jmlRealisasi'] / $getDataTargetBulanan['bulan' . ($i + 1)]) * 100) > $getSetting['batas_nilai_atas'] ? 'text-light' : 'text-danger'); ?>">Detail info <i class="fas fa-arrow-circle-right"></i></button>
                  </div>
                </div>
              <?php else : ?>
                <div class="col-lg-4 col-6">
                  <!-- small box -->
                  <div class="small-box bg-light">
                    <div class="inner">
                      <h3><span><?= number_format("0", 2, ".", ","); ?></span><sup style="font-size: 20px">%</sup></h3>

                      <p><?= $bulan[$i]; ?></p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <button onclick="getDataRealisasiDashboard(<?= ($i + 1); ?>)" data-bulanan="<?= $getDataTargetBulanan['bulan' . ($i + 1)]; ?>" data-realisasi="0" class="btn btn-block btn-sm text-dark small-box-footer">Detail info <i class="fas fa-arrow-circle-right"></i></button>
                  </div>
                </div>
              <?php endif; ?>
            <?php endfor; ?>
          </div>
        </div>
        <div class="card-footer getTableRealisasiDashboard">

        </div>
      </div>
      <div class="card card-light">
        <div class="card-header">
          <h3>Persentase Berdasarkan Program</h3>
        </div>
        <div class="card-body">
          <?php
          $bulan = ['Program Pencegahan dan Pengendalian Penyakit', 'Program Dukungan Manajemen'];
          ?>
          <?php for ($i = 0; $i < count($bulan); $i++) : ?>
            <div class="row">
              <div class="col"></div>
              <div class="col-8">
                <!-- small box -->
                <div class="small-box bg-light">
                  <div class="inner">
                    <h3><span class="keseluruhanPersentaseProgram<?= $i; ?>"></span></h3>

                    <p><?= $bulan[$i]; ?></p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                </div>
              </div>
              <div class="col"></div>
            </div>
          <?php endfor; ?>
        </div>
        <div class="card-footer">
        </div>
      </div>
    </div>
    <div class="col-sm-7">
      <div class="card card-light">
        <div class="card-header">
          <h3 class="float-left">Detail keseluruhan data</h3>
          <div class="float-right">
            <span>Tampilan berdasarkan </span>
            <select class="form-control-relaxtime-dashboard form-control d-inline">
              <option value="kode_rkt">Program</option>
              <option value="digit_kro">Kegiatan</option>
              <option value="kode_kro">Output</option>
              <option value="kode_relaxtime_ro">Sub Output</option>
              <option value="kode_komponen">Komponen</option>
              <option value="kode_sub_komponen">Sub Komponen</option>
              <option value="kode_uraian">Akun</option>
              <option value="detail_uraian" selected>Item</option>
            </select>
          </div>
        </div>
        <div class="card-body" style="max-height: 185vh; overflow: auto;">
          <?= $this->include("dashboard/relaxtime/tableDetail"); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function getDataRealisasiDashboard(bulan) {
    $.ajax({
      url: '/home/relaxtime/getRealisasiBulanan/' + bulan,
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $(".getTableRealisasiDashboard").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
      },
      success: function(response) {
        if (response.data) {
          $(".getTableRealisasiDashboard").html(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  }
  $(document).on("change", ".form-control-relaxtime-dashboard", function() {
    let nilai = $(this).val()

    $(".relaxtimeTableDetail").hide();
    if (nilai == "kode_rkt") {
      $(".relaxtimeProgram").show();
    } else if (nilai == "digit_kro") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
    } else if (nilai == "kode_kro") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
      $(".relaxtimeOutput").show();
    } else if (nilai == "kode_relaxtime_ro") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
      $(".relaxtimeOutput").show();
      $(".relaxtimeSubOutput").show();
    } else if (nilai == "kode_komponen") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
      $(".relaxtimeOutput").show();
      $(".relaxtimeSubOutput").show();
      $(".relaxtimeKomponen").show();
    } else if (nilai == "kode_sub_komponen") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
      $(".relaxtimeOutput").show();
      $(".relaxtimeSubOutput").show();
      $(".relaxtimeKomponen").show();
      $(".relaxtimeSubKomponen").show();
    } else if (nilai == "kode_uraian") {
      $(".relaxtimeProgram").show();
      $(".relaxtimeKegiatan").show();
      $(".relaxtimeOutput").show();
      $(".relaxtimeSubOutput").show();
      $(".relaxtimeKomponen").show();
      $(".relaxtimeSubKomponen").show();
      $(".relaxtimeUraian").show();
    } else if (nilai == "detail_uraian") {
      $(".relaxtimeTableDetail").show();
    }
  });

  setTimeout(() => {
    var marksCanvas = document.getElementById("targetRealisasiChart");
    let data51 = [];
    let data52 = [];
    let data53 = [];
    <?php for ($i = 1; $i <= 12; $i++) { ?>
      data51.push((<?= ($arrayTargetBulanan['bulan' . $i . '51']); ?>));
      data52.push((<?= ($arrayTargetBulanan['bulan' . $i . '52']); ?>));
      data53.push((<?= ($arrayTargetBulanan['bulan' . $i . '53']); ?>));
    <?php } ?>
    var marksData = {
      labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
      datasets: [
        //   {
        //   label: "",
        //   backgroundColor: "rgba(255,255,255,0)",
        //   borderColor: "rgba(255,255,255,0)",
        //   data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
        // },
        {
          label: "51",
          backgroundColor: "rgba(0,0,200,0.2)",
          borderColor: "rgba(0,0,200, .5)",
          data: data51
        }, {
          label: "52",
          backgroundColor: "rgba(0,200,0,0.2)",
          borderColor: "rgba(0,200,0, .5)",
          data: data52
        }, {
          label: "53",
          backgroundColor: "rgba(200,0,0,0.2)",
          borderColor: "rgba(200,0,0, .5)",
          data: data53
        }
      ]
    };

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    var radarChart = new Chart(marksCanvas, {
      type: 'radar',
      data: marksData,
      options: {
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var dataset = data.datasets[tooltipItem.datasetIndex];
              var value = dataset.data[tooltipItem.index];
              return data.labels[tooltipItem.index] + ': ' + numberWithCommas(value);
            }
          }
        }
      }
    });

    // ===========================================================
    var oilCanvas = document.getElementById("headerOilChart");

    Chart.defaults.global.defaultFontFamily = "Lato";
    Chart.defaults.global.defaultFontSize = 18;

    var oilData = {
      labels: [
        "51",
        "52",
        "53",
        "Sisa",
        "Melebihi Target",
      ],
      datasets: [{
        data: [10, 10, 10, 10, 10],
        backgroundColor: [
          "#8463FF",
          "#63FF84",
          "#FF6384",
          "#A4A4A4",
          "#000000"
        ],
        hidden: true
      }]
    };

    var pieChart = new Chart(oilCanvas, {
      type: 'pie',
      data: oilData
    });


    <?php for ($i = 0; $i < 12; $i++) { ?>
      <?php
      $target = ($arrayTargetBulanan['bulan' . ($i + 1) . '51'] + $arrayTargetBulanan['bulan' . ($i + 1) . '52'] + $arrayTargetBulanan['bulan' . ($i + 1) . '53']);
      ?>
      var oilCanvas = document.getElementById("oilChart" + <?= $i; ?>);

      Chart.defaults.global.defaultFontFamily = "Lato";
      Chart.defaults.global.defaultFontSize = 18;

      var oilData = {
        labels: [
          "51", // tidak usah ditampilkan
          "52", // tidak usah ditampilkan
          "53", // tidak usah ditampilkan
          "Sisa",
        ],
        datasets: [{
          // ${"kodeRealisasi" . $data['kode_prefix'] . 'bulan' . $data['bulan']}
          data: [<?= isset(${"kodeRealisasi51bulan" . ($i + 1)}) ? ${"kodeRealisasi51bulan" . ($i + 1)} : '0'; ?>, <?= isset(${"kodeRealisasi52bulan" . ($i + 1)}) ? ${"kodeRealisasi52bulan" . ($i + 1)} : '0'; ?>, <?= isset(${"kodeRealisasi53bulan" . ($i + 1)}) ? ${"kodeRealisasi53bulan" . ($i + 1)} : '0'; ?>, <?= isset($sisaTarget[($i + 1)]) ? ($target - $sisaTarget[($i + 1)]) : $target; ?>],
          backgroundColor: [
            "#8463FF",
            "#63FF84",
            "#FF6384",
            "<?= isset($sisaTarget[($i + 1)]) ? (($target - $sisaTarget[($i + 1)]) < 0 ? '#000000' : '#A4A4A4')  : '#A4A4A4'; ?>"
          ]
        }]
      };

      function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }

      var pieChart = new Chart(oilCanvas, {
        type: 'pie',
        data: oilData,
        options: {
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem, data) {
                var dataset = data.datasets[tooltipItem.datasetIndex];
                var value = dataset.data[tooltipItem.index];
                return data.labels[tooltipItem.index] + ': ' + numberWithCommas(value);
              }
            }
          }
        }
      });
    <?php } ?>

    // ===========================================================
    var oilCanvas = document.getElementById("TargetheaderOilChart");

    Chart.defaults.global.defaultFontFamily = "Lato";
    Chart.defaults.global.defaultFontSize = 18;

    var oilData = {
      labels: [
        "51",
        "52",
        "53",
      ],
      datasets: [{
        data: [10, 10, 10, 10, 10],
        backgroundColor: [
          "#8463FF",
          "#63FF84",
          "#FF6384"
        ],
        hidden: true
      }]
    };

    var pieChart = new Chart(oilCanvas, {
      type: 'pie',
      data: oilData
    });

    <?php for ($i = 0; $i < 12; $i++) { ?>
      <?php
      $target51 = ($arrayTargetBulanan['bulan' . ($i + 1) . '51']);
      $target52 = ($arrayTargetBulanan['bulan' . ($i + 1) . '52']);
      $target53 = ($arrayTargetBulanan['bulan' . ($i + 1) . '53']);
      ?>
      var oilCanvas = document.getElementById("TargetoilChart" + <?= $i; ?>);

      Chart.defaults.global.defaultFontFamily = "Lato";
      Chart.defaults.global.defaultFontSize = 18;

      var oilData = {
        labels: [
          "51",
          "52",
          "53",
        ],
        datasets: [{
          // ${"kodeRealisasi" . $data['kode_prefix'] . 'bulan' . $data['bulan']}
          data: [<?= $target51; ?>, <?= $target52; ?>, <?= $target53; ?>],
          backgroundColor: [
            "#8463FF",
            "#63FF84",
            "#FF6384",
          ]
        }]
      };

      function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }

      var pieChart = new Chart(oilCanvas, {
        type: 'pie',
        data: oilData,
        options: {
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem, data) {
                var dataset = data.datasets[tooltipItem.datasetIndex];
                var value = dataset.data[tooltipItem.index];
                return data.labels[tooltipItem.index] + ': ' + numberWithCommas(value);
              }
            }
          }
        }
      });
    <?php } ?>


  }, 1000);

  $(document).on('click', '.canvasRealisasiBulananButton', function() {
    $(".canvasRealisasiBulananButton").removeClass('btn-success')
    $(".canvasRealisasiBulananButton").addClass('btn-danger')
    $(".canvasRealisasiBulananButton").html('<i class="fas fa-eye-slash"></i>')
    $(".headCanvasRealisasiBulananButton").removeClass('btn-danger')
    $(".headCanvasRealisasiBulananButton").removeClass('btn-success')
    $(".headCanvasRealisasiBulananButton").addClass('btn-secondary')
    $(".headCanvasRealisasiBulananButton").html('<i class="fas fa-bullseye"></i>')
    $(this).removeClass('btn-danger')
    $(this).addClass('btn-success')
    $(this).html('<i class="fas fa-eye"></i>')
    $(".canvasRealisasiBulanan").hide();
    let i = $(this).data("canvas");
    $(".canvas" + i).fadeIn(300);
    $(".canvasRealisasiBulanan").removeClass('col-4');
    $(".canvasRealisasiBulanan").addClass('col-12');
    $(".graph2").eq(0).removeClass('d-none');
    $(".graph2").removeClass('col-sm-5');
    $(".graph2").addClass('col-sm-6');
    $(".graph1").addClass('d-none');
  })
  $(document).on('click', '.headCanvasRealisasiBulananButton', function() {
    $(".canvasRealisasiBulananButton").removeClass('btn-success')
    $(".canvasRealisasiBulananButton").removeClass('btn-danger')
    $(".canvasRealisasiBulananButton").addClass('btn-success')
    $(".canvasRealisasiBulananButton").html('<i class="fas fa-sm fa-eye"></i>')
    $(".headCanvasRealisasiBulananButton").removeClass('btn-danger')
    $(".headCanvasRealisasiBulananButton").removeClass('btn-success')
    $(".headCanvasRealisasiBulananButton").addClass('btn-success')
    $(".headCanvasRealisasiBulananButton").html('<i class="fas fa-sm fa-eye"></i>')
    $(".canvasRealisasiBulanan").removeClass('col-12');
    $(".canvasRealisasiBulanan").addClass('col-4');
    $(".canvasRealisasiBulanan").fadeIn(300);
    $(".graph2").eq(0).addClass('d-none');
    $(".graph1").removeClass('d-none');
    $(".graph2").removeClass('col-sm-6');
    $(".graph2").addClass('col-sm-5');
  })
</script>