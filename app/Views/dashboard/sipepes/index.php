<div class="container-fluid" style="filter: grayscale(100%);">
  <div class="card bg-secondary">
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-sm-9">
          <p class="float-right pr-4"><strong><?= $substansi['PKSE']; ?></strong></p>
          <p class="mb-0 mt-2">PKSE (Pengendalian Karantina dan Surveilans Epidemologi)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['PKSE']; ?>%"><?= $persentase['PKSE']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['PRL']; ?></strong></p>
          <p class="mb-0 mt-2">PRL (Pengendalian Resiko Lingkungan)</p>
          <div class="progress">
            <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['PRL']; ?>%"><?= $persentase['PRL']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['UKLW']; ?></strong></p>
          <p class="mb-0 mt-2">UKLW (Unit Kerja Lintas Wilayah)</p>
          <div class="progress">
            <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['UKLW']; ?>%"><?= $persentase['UKLW']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['Adum']; ?></strong></p>
          <p class="mb-0 mt-2">Adum (Administrasi Umum)</p>
          <div class="progress">
            <div class="progress-bar bg-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Adum']; ?>%"><?= $persentase['Adum']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['Lainnya']; ?></strong></p>
          <p class="mb-0 mt-2">Lainnya</p>
          <div class="progress">
            <div class="progress-bar bg-secondary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Lainnya']; ?>%"><?= $persentase['Lainnya']; ?>%
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="card text-center">
            <div class="card-body p-2">
              <h5 class="text-secondary">Total SOP Utama</h5>
            </div>
            <div class="card-footer">
              <h2><strong class="text-secondary"><?= $substansi['totalSopUtama']; ?></strong></h2>
            </div>
          </div>
          <div class="card text-center">
            <div class="card-body p-2">
              <h5 class="text-secondary">Total Semua SOP</h5>
            </div>
            <div class="card-footer">
              <h2><strong class="text-secondary"><?= $substansi['totalSop']; ?></strong></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>

</div><!-- /.container-fluid -->