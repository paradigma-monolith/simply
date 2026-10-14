<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_skriningTBC" method="post">
        <input type="hidden" name="id_uklw_pemkes" value="<?= $getData['id_uklw_skrining_tbc']; ?>">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <div class="card card-default bg-transparent">
          <div class="card-body p-0">
            <div class="bs-stepper">
              <div class="bs-stepper-header" role="tablist">
                <!-- your steps here -->
                <div class="step" data-target="#part2">
                  <button type="button" class="step-trigger" role="tab" aria-controls="part2" id="part2-trigger">
                    <span class="bs-stepper-circle"><i class="fas fa-lungs-virus"></i></span>
                    <span class="bs-stepper-label">Skrining TBC</span>
                  </button>
                </div>
              </div>
              <div class="bs-stepper-content">
                <!-- your steps content here -->
                <div id="part2" class="content" role="tabpanel" aria-labelledby="part2-trigger">
                  <table class="table table-info table-hover table-striped table-bordered table-sm align-middle">
                    <thead>
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 35%;">DATA PASIEN</th>
                        <th colspan="3"></th>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle"><?= $getData['identitas']; ?></th>
                        <td class="p-0 px-3">
                          <?= $getData['nomor_identitas']; ?>
                        </td>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle">NAMA SESUAI KTP</th>
                        <td class="p-0 px-3" colspan="3">
                          <?= $getData['nama']; ?>
                        </td>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle">TANGGAL LAHIR</th>
                        <td class="p-0 px-3" colspan="3">
                          <?= mediumdate_indo($getData['tgl_lahir']); ?>
                        </td>
                      </tr>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">SKRINING TBC (GEJALA)</th>
                        <th class="text-center" style="width: 20%;">YA</th>
                        <th class="text-center" style="width: 20%;">TIDAK</th>
                        <th class="text-center" style="width: 25%;">KET</th>
                      </tr>
                      <?php
                      $array = unserialize($getData['skrining_tbc_gejala']);
                      foreach ($array as $key => $data) : ?>
                        <tr>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['skrining_tbc_gejala']; ?>" name="skrining_tbc_gejala[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                          </th>
                          <td class="p-0">
                            <input type="radio" value="Ya" <?= $data['hasil_skrining_tbc_gejala'] == 'Ya' ? 'checked' : ''; ?> name="hasil_skrining_tbc_gejala<?= $key; ?>" class="bg-success d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                          </td>
                          <td>
                            <input type="radio" value="Tidak" <?= $data['hasil_skrining_tbc_gejala'] == 'Tidak' ? 'checked' : ''; ?> name="hasil_skrining_tbc_gejala<?= $key; ?>" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                          </td>
                          <td class="p-0 align-middle">
                            <input type="text" value="<?= $data['ket_skrining_tbc_gejala']; ?>" name="ket_skrining_tbc_gejala[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </thead>
                  </table>
                  <button type="submit" class="btn btn-success float-right mx-2"><i class="fas fa-share"></i> Simpan</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer"></div>
        </div>
      </form>
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>