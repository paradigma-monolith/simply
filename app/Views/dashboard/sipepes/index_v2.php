<div class="container-fluid">
  <div class="card">
    <!-- /.card-header -->
    <div class="card-header">
        <h4><strong>SOP Terbaru</strong></h4>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-9">
          <p class="float-right pr-4"><strong><?= $substansi['Timker 1']; ?></strong></p>
          <p class="mb-0 mt-2">Timker 1 (Tim Kerja Surveilans dan Penindakan Pelanggaran Kekarantinaan Kesehatan)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Timker 1']; ?>%"><?= $persentase['Timker 1']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['Timker 2']; ?></strong></p>
          <p class="mb-0 mt-2">Timker 2 (Tim Kerja Pengawasan Faktor Risiko Kesehatan Alat Angkut dan Barang)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Timker 2']; ?>%"><?= $persentase['Timker 2']; ?>%
            </div>
          </div>

          <p class="float-right pr-4"><strong><?= $substansi['Timker 3']; ?></strong></p>
          <p class="mb-0 mt-2">Timker 3 (Tim Kerja Pengawasan Faktor Risiko Kesehatan Lingkungan)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Timker 3']; ?>%"><?= $persentase['Timker 3']; ?>%
            </div>
          </div>
          
          <p class="float-right pr-4"><strong><?= $substansi['Timker 4']; ?></strong></p>
          <p class="mb-0 mt-2">Timker 4 (Tim Kerja Pengawasan Faktor Risiko Kesehatan Orang)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Timker 4']; ?>%"><?= $persentase['Timker 4']; ?>%
            </div>
          </div>
          
          <p class="float-right pr-4"><strong><?= $substansi['Timker 5']; ?></strong></p>
          <p class="mb-0 mt-2">Timker 5 (Tim Kerja Layanan Publik dan Zona Intergritas)</p>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persentaseBar['Timker 5']; ?>%"><?= $persentase['Timker 5']; ?>%
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
              <h5>Total SOP Utama</h5>
            </div>
            <div class="card-footer">
              <h2><strong><?= $substansi['totalSopUtama']; ?></strong></h2>
            </div>
          </div>
          <div class="card text-center">
            <div class="card-body p-2">
              <h5>Total Semua SOP</h5>
            </div>
            <div class="card-footer">
              <h2><strong><?= $substansi['totalSop']; ?></strong></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>

</div><!-- /.container-fluid -->