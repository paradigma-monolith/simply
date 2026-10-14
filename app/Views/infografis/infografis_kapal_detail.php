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
                <h6 class="mb-0">Kedatangan Kapal</h6>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Kapal Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['kapal_datang_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Kapal Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['kapal_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['kapal_datang_luar'] + $infografis['kapal_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">ABK Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['abk_datang_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">ABK Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['abk_datang_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['abk_datang_luar'] + $infografis['abk_datang_negeri']; ?></span>
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
                <h6 class="mb-0">Keberangkatan Kapal</h6>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Kapal Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['kapal_berangkat_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Kapal Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['kapal_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['kapal_berangkat_luar'] + $infografis['kapal_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">ABK Luar Negeri</h6>
                <span class="text-secondary"><?= $infografis['abk_berangkat_luar']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">ABK Dalam Negeri</h6>
                <span class="text-secondary"><?= $infografis['abk_berangkat_negeri']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-dark">
                <h6 class="mb-0">Total</h6>
                <span class="text-light"><?= $infografis['abk_berangkat_luar'] + $infografis['abk_berangkat_negeri']; ?></span>
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