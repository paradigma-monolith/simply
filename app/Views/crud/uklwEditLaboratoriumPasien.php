<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_laboratorium" method="post">
        <input type="hidden" name="id_uklw_pemkes" value="<?= $getData['id_uklw_laboratorium']; ?>">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <div class="card card-default bg-transparent">
          <div class="card-body p-0">
            <div class="bs-stepper">
              <div class="bs-stepper-header" role="tablist">
                <!-- your steps here -->
                <div class="step" data-target="#part3">
                  <button type="button" class="step-trigger" role="tab" aria-controls="part3" id="part3-trigger">
                    <span class="bs-stepper-circle"><i class="fas fa-vial"></i></span>
                    <span class="bs-stepper-label">Skrining HIV & Skrining Penyakit Tidak Menular</span>
                  </button>
                </div>
              </div>
              <div class="bs-stepper-content">
                <div id="part3" class="content" role="tabpanel" aria-labelledby="part3-trigger">
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
                        <th class="pl-4" style="width: 30%;">KIMIA KLINIK</th>
                        <th class="text-center" style="width: 20%;">HASIL</th>
                        <th class="text-center" style="width: 25%;">NILAI RUJUKAN</th>
                        <th class="text-center" style="width: 25%;">KESIMPULAN</th>
                      </tr>
                      <?php
                      $array = unserialize($getData['laboratorium_kimia_klinik']);
                      foreach ($array as $key => $data) : ?>
                        <tr>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['laboratorium_kimia_klinik']; ?>" name="laboratorium_kimia_klinik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                          </th>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['hasil_laboratorium_kimia_klinik']; ?>" name="hasil_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                            </td>
                          <td class="p-0">
                            <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);"><?= $data['nilai_rujukan_laboratorium_kimia_klinik']; ?></textarea>
                          </td>
                          <td class="p-0 align-middle">
                            <textarea rows="2" name="kesimpulan_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"><?= $data['kesimpulan_laboratorium_kimia_klinik']; ?></textarea>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">HEMATOLOGI</th>
                        <th class="text-center" style="width: 20%;">HASIL</th>
                        <th class="text-center" style="width: 25%;">NILAI RUJUKAN</th>
                        <th class="text-center" style="width: 25%;">KESIMPULAN</th>
                      </tr>
                      <?php
                      $array = unserialize($getData['laboratorium_hematologi']);
                      foreach ($array as $key => $data) : ?>
                        <tr>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['laboratorium_hematologi']; ?>" name="laboratorium_hematologi[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                          </th>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['hasil_laboratorium_hematologi']; ?>" name="hasil_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                            </td>
                          <td class="p-0">
                            <textarea rows="2" readonly name="nilai_rujukan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);"><?= $data['nilai_rujukan_laboratorium_hematologi']; ?></textarea>
                          </td>
                          <td class="p-0 align-middle">
                            <textarea rows="2" name="kesimpulan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"><?= $data['kesimpulan_laboratorium_hematologi']; ?></textarea>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">KUALITATIF</th>
                        <th class="text-center" style="width: 20%;">HASIL</th>
                        <th class="text-center" style="width: 25%;">NILAI RUJUKAN</th>
                        <th class="text-center" style="width: 25%;">KESIMPULAN</th>
                      </tr>
                      <?php
                      $array = unserialize($getData['laboratorium_kualitatif']);
                      foreach ($array as $key => $data) : ?>
                        <tr>
                          <th class="p-0 align-middle">
                            <input type="text" value="<?= $data['laboratorium_kualitatif']; ?>" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                          </th>
                          <td class="p-0 align-middle">
                            <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                                <option value="Positif" <?= $data['hasil_laboratorium_kualitatif'] == "Positif" ? "selected" : ''; ?>>Positif</option>
                                <option value="Negatif" <?= $data['hasil_laboratorium_kualitatif'] == "Negatif" ? "selected" : ''; ?>>Negatif</option>
                            </select>
                          </td>
                          <td class="p-0">
                            <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);"><?= $data['nilai_rujukan_laboratorium_kualitatif']; ?></textarea>
                          </td>
                          <td class="p-0 align-middle">
                            <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"><?= $data['kesimpulan_laboratorium_kualitatif']; ?></textarea>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <!-- ================================================================================================================ -->

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