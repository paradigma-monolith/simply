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
        <div class="col-sm-6">
          <div class="card mt-3">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap bg-black">
                <h6 class="mb-0">Data Dokumen</h6>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">PHQC</h6>
                <span class="text-secondary"><?= $infografis['phqc']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">COP</h6>
                <span class="text-secondary"><?= $infografis['cop']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">SSCEC</h6>
                <span class="text-secondary"><?= $infografis['sscec']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">SSCC</h6>
                <span class="text-secondary"><?= $infografis['sscc']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">P3K</h6>
                <span class="text-secondary"><?= $infografis['p3k']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">ICV</h6>
                <span class="text-secondary"><?= $infografis['icv']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">SKLT</h6>
                <span class="text-secondary"><?= $infografis['sklt']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">SIAOS</h6>
                <span class="text-secondary"><?= $infografis['siaos']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Surat Izin Angkut Jenazah</h6>
                <span class="text-secondary"><?= $infografis['siaj']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Data Pengujian Kesehatan (KIR)</h6>
                <span class="text-secondary"><?= $infografis['kir']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Buku Kesehatan</h6>
                <span class="text-secondary"><?= $infografis['bukes']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin MM</h6>
                <span class="text-secondary"><?= $infografis['vaksin_mm']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin YF</h6>
                <span class="text-secondary"><?= $infografis['vaksin_yf']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin Covid</h6>
                <span class="text-secondary"><?= $infografis['vaksin_covid']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin Influenza</h6>
                <span class="text-secondary"><?= $infografis['vaksin_influenza']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin Tifoid</h6>
                <span class="text-secondary"><?= $infografis['vaksin_tifoid']; ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0">Vaksin Polio</h6>
                <span class="text-secondary"><?= $infografis['vaksin_polio']; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>