<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="main-body">
      <div class="row">
        <div class="col-sm-6">
          <div class="card mt-3">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-secondary">
                <h6 class="mb-0">Data diinput oleh</h6>
                <span class="text-light"><?= $infografis['created_name']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-secondary">
                <h6 class="mb-0">Data terakhir diupdate oleh</h6>
                <span class="text-light"><?= $infografis['updated_name']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-secondary">
                <h6 class="mb-0">Data terakhir diupdate</h6>
                <span class="text-light"><?= $infografis['updated_at']; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="card mt-3">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-black">
                <h6 class="mb-0">Kedatangan Pesawat</h6>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Pesawat Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['pesawat_datang_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Pesawat Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['pesawat_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['pesawat_datang_luar'] + $infografis['pesawat_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Crew Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['crew_datang_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Crew Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['crew_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['crew_datang_luar'] + $infografis['crew_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Penumpang Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['penumpang_datang_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Penumpang Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['penumpang_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['penumpang_datang_luar'] + $infografis['penumpang_datang_negeri']; ?></span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card mt-3">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-black">
                <h6 class="mb-0">Keberangkatan Pesawat</h6>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Pesawat Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['pesawat_berangkat_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Pesawat Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['pesawat_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['pesawat_berangkat_luar'] + $infografis['pesawat_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Crew Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['crew_berangkat_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Crew Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['crew_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['crew_berangkat_luar'] + $infografis['crew_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Penumpang Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['penumpang_berangkat_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Penumpang Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['penumpang_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['penumpang_berangkat_luar'] + $infografis['penumpang_berangkat_negeri']; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection(); ?>