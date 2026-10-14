<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4">
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?= count($totalSopUtama) ?></h3>
            <p>Dokumen SOP Utama</p>
          </div>
          <div class="icon">
            <i class="fas fa-list-alt"></i>
          </div>
          <a href="/sipepes/utama" class="small-box-footer">Info selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?= count($totalSopPendukung) ?></h3>
            <p>Dokumen SOP Pendukung</p>
          </div>
          <div class="icon">
            <i class="far fa-list-alt"></i>
          </div>
          <a href="/sipepes/pendukung" class="small-box-footer">Info selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= count($totalSopLainnya) ?></h3>
            <p>Dokumen SOP Lainnya</p>
          </div>
          <div class="icon">
            <i class="fas fa-th-list"></i>
          </div>
          <a href="/sipepes/lainnya" class="small-box-footer">Info selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP PKSE</span>
            <span class="info-box-number">
              <?= count($totalPkse) ?>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP PRL</span>
            <span class="info-box-number">
              <?= count($totalPrl) ?>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP UKLW</span>
            <span class="info-box-number">
              <?= count($totalUklw) ?>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP ADUM/LAINYYA</span>
            <span class="info-box-number">
              <?= count($totalLainnya) ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-th-list mr-2"></i>
            Progres Pengesahan Standar Operasional Prosedur
          </h3>
        </div>
        <div class="card-body table-responsive p-0" style="height: 350px;">
          <table class="table table-head-fixed table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Nama Dokumen</th>
                <th>Unit Kerja</th>
                <th>Dibuat Oleh</th>
                <th style="width: 10%;">Penyusunan Draf</th>
                <th style="width: 10%;">Validasi</th>
                <th style="width: 10%;">Verifikasi Draf Final</th>
                <th style="width: 10%;">Permohonan Persetujuan</th>
                <th style="width: 10%;">Pengesahan</th>
                <th style="width: 10%;">Sosialisasi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td class="text-center"><span class="badge bg-success">Selesai</span></td>
                <td class="text-center"><span class="badge bg-warning">Progres</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>183</td>
                <td>John Doe</td>
                <td>11-7-2014</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> -->

  </div>
</section>

<?= $this->endSection(); ?>