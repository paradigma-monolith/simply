<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <!-- ===================================================================== -->
          <div class="form-group col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select id="data-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Pilih Data-- </option>
                  <option value="infografis"> Infografis </option>
                  <option value="cuti"> Sicute </option>
                  <option value="sinadi"> Sinadi </option>
                  <option value="relaxtime"> Relaxtime </option>
                  <option value="sipepes"> Sipepes </option>
                  <option value="sipedit"> Sipedit </option>
                </select>
              </div>
              <span>Data</span>
            </label>
          </div>
        </div>
        <!-- ===================================================================== -->

        <div id="infografis-filter" class="dashboard-filter row jumbotron pt-4 pb-0">
          <!-- ===========================Infografis================================= -->
          <div class="form-group select-dashboard-infografis col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select id="wilkerInfografis-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value="all"> --Semua Wilayah Kerja-- </option>
                  <?php foreach ($unitKerja as $data) : ?>
                    <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <span>Wilayah Kerja</span>
            </label>
          </div>
          <!-- ===================================================================== -->
          <div class="form-group select-dashboard-infografis col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select id="jenisInfografis-dashboard" class="form-control select2" name="" style="width:100%;">
                  <option value="all"> --Semua Infografis-- </option>
                  <option value="kapal"> Infografis Kapal </option>
                  <option value="pesawat"> Infografis Pesawat </option>
                  <option value="dokumen"> Infografis Dokumen </option>
                  <option value="vaksin"> Infografis Vaksin </option>
                </select>
              </div>
              <span>Jenis Infografis</span>
            </label>
          </div>
          <!-- ===================================================================== -->
          <div class="col card">
            <form id="infografisForm" action="" method="post">
              <div class="row">
                <div class="col-10">
                  <div class="card mb-0">
                    <div class="card-body pb-0">
                      <div class="form-group select-dashboard-infografis">
                        <label class="has-float-label">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            <input id="periodeInfografis-dashboard" type="text" name="tanggal-infografis" class="form-control float-right date-dashboard-infografis date-dashboard">
                          </div>
                          <span>Periode</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-2 text-center mt-3">
                  <div class="d-block">
                  <button type="button" class="btn btn-block btn-lg btn-secondary" onclick="showNotificationWhereInfografisLaporan()">
                    <i class="fas fa-question-circle"></i>
                  </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <!-- =============================EndInfografis============================ -->
        </div>
        
        <div id="cuti-filter" class="dashboard-filter row jumbotron pt-4 pb-0">
          <!-- ===========================DataCuti================================= -->
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Semua Pengguna-- </option>
                </select>
              </div>
              <span>Pengguna</span>
            </label>
          </div>
          <!-- ===================================================================== -->
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Data Cuti/Perizinan-- </option>
                </select>
              </div>
              <span>Data</span>
            </label>
          </div>
          <!-- ===================================================================== -->
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div>
                <select class="form-control select2" name="" style="width:100%;">
                  <option value=""> --Semua Kategori-- </option>
                </select>
              </div>
              <span>Jenis Cuti/Perizinan</span>
            </label>
          </div>
          <!-- ===================================================================== -->
          <div class="form-group select-dashboard-cuti col-lg-3 col-md-4 col-6">
            <label class="has-float-label">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" name="tanggal" class="form-control float-right date-dashboard-cuti date-dashboard">
              </div>
              <span>Periode</span>
            </label>
          </div>
          <!-- =============================EndDataCuti============================ -->
        </div>

        <form action="/dashboard/sipedit/export" method="post">
          <div id="sipedit-filter" class="dashboard-filter row jumbotron pt-4 pb-0">
            <!-- ===================================================================== -->
            <div class="form-group select-dashboard-infografis col">
              <label class="has-float-label">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <select id="periodeSipedit-dashboard" type="text" name="sipedit_tanggal" class="form-control select2 float-right" style="width:80%">
                    <option value="all">--Semua Periode--</option>
                    <?php for ($i = date('Y'); $i > 2021; $i--) : ?>
                      <option value="<?= $i; ?>" <?= date('Y') == $i ? 'selected' :''; ?>><?= $i; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <span>Periode</span>
              </label>
            </div>
            <div class="float-right">
            <div class="form-group select-dashboard-infografis col p-0 m-0">
              <label class="has-float-label">
                <div class="input-group">
                  <!--<div class="input-group-prepend">-->
                  <!--  <span class="input-group-text">-->
                  <!--    <i class="far fa-calendar-alt"></i>-->
                  <!--  </span>-->
                  <!--</div>-->
                  <select type="text" id="bulanSipedit-dashboard" name="sipedit_bulan" class="form-control select2 float-right" style="width:100%">
                    <?php for ($i = 1; $i <= 12; $i++): 
                     $sipedit_nama_bulan;
                     switch ($i) {
                        case 1:
                            $sipedit_nama_bulan = "Januari";
                            break;
                        case 2:
                            $sipedit_nama_bulan = "Februari";
                            break;
                        case 3:
                            $sipedit_nama_bulan = "Maret";
                            break;
                        case 4:
                            $sipedit_nama_bulan = "April";
                            break;
                        case 5:
                            $sipedit_nama_bulan = "Mei";
                            break;
                        case 6:
                            $sipedit_nama_bulan = "Juni";
                            break;
                        case 7:
                            $sipedit_nama_bulan = "Juli";
                            break;
                        case 8:
                            $sipedit_nama_bulan = "Agustus";
                            break;
                        case 9:
                            $sipedit_nama_bulan = "September";
                            break;
                        case 10:
                            $sipedit_nama_bulan = "Oktober";
                            break;
                        case 11:
                            $sipedit_nama_bulan = "November";
                            break;
                        case 12:
                            $sipedit_nama_bulan = "Desember";
                            break;
                        default:
                            $sipedit_nama_bulan = "Bulan tidak valid";
                            break;
                    }
                    $sipedit_bulan = $i;
                    strlen($sipedit_bulan) == 1 ? "0".$sipedit_bulan : '';
                    
                     ?>
                      <option value="<?= $sipedit_bulan; ?>" <?= $sipedit_bulan==date("m") ? 'selected' : ''; ?>><?= $sipedit_nama_bulan; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <span>Sampai Bulan</span>
              </label>
            </div>
              <button type="submit" class="btn btn-success mb-3"><i class="fas fa-file-excel"></i> Export ke Excel</button>
            </div>
            <!-- =============================EndInfografis============================ -->
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<section class="content dashboard">
  <div class="container">
    <div class="card">
      <div class="card-body">
        <div class="jumbotron pl-5 pr-5">
          <h4 class="text-center">Dashboard adalah tampilan antarmuka yang menampilkan informasi secara visual tentang kinerja suatu sistem atau proses bisnis dalam bentuk grafik, tabel, atau elemen visual lainnya.</h4>
          <img style="display: block; margin:0 auto;" class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">
          <h6 class="text-center">
            Pada dashboard, pengguna dapat dengan mudah memantau kinerja sistem dan memperoleh informasi yang dibutuhkan dalam waktu singkat. Dashboard biasanya digunakan untuk mengorganisir dan menyajikan informasi secara terstruktur, sehingga memudahkan pengguna untuk memahami data dan mengambil keputusan berdasarkan informasi yang tersedia.
          </h6>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function showNotificationWhereInfografisLaporan() {
    $('body')
        .removeClass('sidebar-collapse sidebar-closed')
        .addClass('sidebar-open');
    $("#infografis").addClass("menu-open");
    $("#infografis").find("ul").css('display', 'block');
    setTimeout(function() {
      introJs().setOptions({
        steps: [
          {
            element: document.querySelector('.laporan-infografis-menu'),
            intro: 'Laporan infografis dipindahkan menjadi menu "Laporan Infografis"',
            position: 'right'
          }
        ],
        doneLabel: 'Ok'
      }).start();
    }, 200);
  }
</script>
<?= $this->endSection(); ?>