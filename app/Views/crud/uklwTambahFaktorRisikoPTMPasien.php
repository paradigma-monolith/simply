<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_faktorRisikoPTM" method="post">
        <input type="hidden" name="id_uklw_pemkes" value="">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <div class="card card-default bg-transparent">
          <div class="card-body p-0">
            <div class="bs-stepper">
              <div class="bs-stepper-header" role="tablist">
                <!-- your steps here -->
                <div class="step" data-target="#part3">
                  <button type="button" class="step-trigger" role="tab" aria-controls="part3" id="part3-trigger">
                    <span class="bs-stepper-circle"><i class="fas fa-user-shield"></i></span>
                    <span class="bs-stepper-label">Faktor Risiko Penyakit Tidak Menular</span>
                  </button>
                </div>
              </div>
              <div class="bs-stepper-content">
                <!-- your steps content here -->
                <div id="part3" class="content" role="tabpanel" aria-labelledby="part3-trigger">
                  <table class="table table-info table-hover table-striped table-bordered table-sm align-middle">
                    <thead>
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 35%;">DATA PASIEN</th>
                        <th colspan="3"></th>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle"><?= $getData['identitas']; ?></th>
                        <td class="p-0 px-3" colspan="3">
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
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 70%;">FAKTOR RISIKO PTM (wajib diisi)</th>
                        <th class="text-center" style="width: 15%;">YA</th>
                        <th class="text-center" style="width: 15%;">TIDAK</th>
                        <th class="text-center" style="width: 0%;"></th>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Merokok aktif" name="skrining_penyakit_tidak_menular[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="radio" value="Ya" name="hasil_skrining_penyakit_tidak_menular0" class="bg-success d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td>
                          <input type="radio" value="Tidak" name="hasil_skrining_penyakit_tidak_menular0" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td class="p-0 align-middle">
                          <input type="hidden" value="" name="ket_skrining_penyakit_tidak_menular[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Olahraga kurang dari 150 menit/minggu" name="skrining_penyakit_tidak_menular[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="radio" value="Ya" name="hasil_skrining_penyakit_tidak_menular1" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td>
                          <input type="radio" value="Tidak" name="hasil_skrining_penyakit_tidak_menular1" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td class="p-0 align-middle">
                          <input type="hidden" value="" name="ket_skrining_penyakit_tidak_menular[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Tidak setiap hari konsumsi sayur dan buah" name="skrining_penyakit_tidak_menular[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="radio" value="Ya" name="hasil_skrining_penyakit_tidak_menular2" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td>
                          <input type="radio" value="Tidak" name="hasil_skrining_penyakit_tidak_menular2" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td class="p-0 align-middle">
                          <input type="hidden" value="" name="ket_skrining_penyakit_tidak_menular[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Tidur kurang dari 7 jam/hari" name="skrining_penyakit_tidak_menular[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="radio" value="Ya" name="hasil_skrining_penyakit_tidak_menular3" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td>
                          <input type="radio" value="Tidak" name="hasil_skrining_penyakit_tidak_menular3" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td class="p-0 align-middle">
                          <input type="hidden" value="" name="ket_skrining_penyakit_tidak_menular[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Konsumsi alkohol" name="skrining_penyakit_tidak_menular[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="radio" value="Ya" name="hasil_skrining_penyakit_tidak_menular4" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td>
                          <input type="radio" value="Tidak" name="hasil_skrining_penyakit_tidak_menular4" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                        <td class="p-0 align-middle">
                          <input type="hidden" value="" name="ket_skrining_penyakit_tidak_menular[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                        </td>
                      </tr>

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