<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-12 col-md-3"></div>

      <div class="col-12 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-secondary elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP PKSE</span>
            <span class="info-box-number">
              <?= $totalPkse['PKSE']; ?>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-secondary elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP PRL</span>
            <span class="info-box-number">
              <?= $totalPrl['PRL']; ?>
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-secondary elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP UKLW</span>
            <span class="info-box-number">
              <?= $totalUklw['UKLW']; ?>
            </span>
          </div>
        </div>
      </div>
      
      
      
      <div class="col-12 col-md-2">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-info elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">SOP Utama</span>
            <span class="info-box-number">
              <?= $totalSopUtama['jenis_dokumen']; ?>
            </span>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-md-10">
          <div class="row">
              <div class="col-md">
                  
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Timker 1</span>
                    <span class="info-box-number">
                      <?= $tim1['Timker 1']; ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Timker 2</span>
                    <span class="info-box-number">
                      <?= $tim2['Timker 2']; ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Timker 3</span>
                    <span class="info-box-number">
                      <?= $tim3['Timker 3']; ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Timker 4</span>
                    <span class="info-box-number">
                      <?= $tim4['Timker 4']; ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-info-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Timker 5</span>
                    <span class="info-box-number">
                      <?= $tim5['Timker 5']; ?>
                    </span>
                  </div>
                </div>
                  
                  
                  
              </div>
          </div>
      </div>
      
    </div>

    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body">
              <form action="/sipepes/export" method="post">
              <label>Filter data:</label>
              <select class="form-control select2" id="view_sop" name="pilih_divisi" style="width:100%;">
                <option value=""> -- Pilih Divisi -- </option>
                <option value="Timker 1">Timker 1</option>
                <option value="Timker 2">Timker 2</option>
                <option value="Timker 3">Timker 3</option>
                <option value="Timker 4">Timker 4</option>
                <option value="Timker 5">Timker 5</option>
              </select>
              <br>
              <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Export ke Excel</button>
              </div>
            </form>
            <hr>
            <div class="view_data_sop"></div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>

</section>

<?= $this->endSection(); ?>