<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_laboratorium" method="post">
        <input type="hidden" name="id_uklw_pemkes" value="">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <div class="card card-default bg-transparent">
          <div class="card-body p-0">
            <div class="bs-stepper">
              <div class="bs-stepper-header" role="tablist">
                <!-- your steps here -->
                <div class="step" data-target="#part3">
                  <button type="button" class="step-trigger" role="tab" aria-controls="part3" id="part3-trigger">
                    <span class="bs-stepper-circle"><i class="fas fa-vial"></i></span>
                    <span class="bs-stepper-label">Laboratorium</span>
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
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 35%;">KIMIA KLINIK</th>
                        <th class="text-center" style="width: 30%;">HASIL</th>
                        <th class="text-center" style="width: 35%;">NILAI RUJUKAN</th>
                        <th class="text-center" style="width: 0%;"></th>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Gula Darah" name="laboratorium_kimia_klinik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_kimia_klinik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mg/dL" readonly name="satuan_laboratorium_kimia_klinik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly rows="2" name="nilai_rujukan_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">
< 200</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" value="" name="kesimpulan_laboratorium_kimia_klinik[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Asam Urat" name="laboratorium_kimia_klinik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_kimia_klinik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mg/dL" readonly name="satuan_laboratorium_kimia_klinik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly rows="2" name="nilai_rujukan_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">
L: 3.1 - 7.0
P: 2.5 - 5.6</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kimia_klinik[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Kolesterol" name="laboratorium_kimia_klinik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_kimia_klinik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mg/dL" readonly name="satuan_laboratorium_kimia_klinik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly name="nilai_rujukan_laboratorium_kimia_klinik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">< 200</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea name="kesimpulan_laboratorium_kimia_klinik[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4">HEMATOLOGI</th>
                        <th class="text-center">HASIL</th>
                        <th class="text-center">NILAI RUJUKAN</th>
                        <th class="text-center"></th>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Leukosit" name="laboratorium_hematologi[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_hematologi[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mm3" readonly name="satuan_laboratorium_hematologi[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">3540 - 9060</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_hematologi[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Eritrosit" name="laboratorium_hematologi[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_hematologi[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mm3" readonly name="satuan_laboratorium_hematologi[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly rows="2" name="nilai_rujukan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">
L: 4300000000000 - 5600000000000
P: 4000000000000 - 5200000000000</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_hematologi[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Hemoglobin" name="laboratorium_hematologi[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="" value="" name="hasil_laboratorium_hematologi[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="" readonly name="satuan_laboratorium_hematologi[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly rows="2" name="nilai_rujukan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">-</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_hematologi[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Trombosit" name="laboratorium_hematologi[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control input-number" placeholder="0" value="" name="hasil_laboratorium_hematologi[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mm3" readonly name="satuan_laboratorium_hematologi[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea readonly rows="2" name="nilai_rujukan_laboratorium_hematologi[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">165000 - 415000</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_hematologi[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4">KUALITATIF</th>
                        <th class="text-center">HASIL</th>
                        <th class="text-center">NILAI RUJUKAN</th>
                        <th class="text-center"></th>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Rapid Test Antibody HIV" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                            <option value="non reaktif">non reaktif</option>
                            <option value="rekatif">rekatif</option>
                          </select>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">non reaktif</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Rapid Test Antigen Covid-19" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                            <option value="non reaktif">non reaktif</option>
                            <option value="rekatif">rekatif</option>
                          </select>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">non reaktif</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Rapid Test Thypoid" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                            <option value="non reaktif">non reaktif</option>
                            <option value="rekatif">rekatif</option>
                          </select>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">non reaktif</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Rapid Test Leptospirosis" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                            <option value="non reaktif">non reaktif</option>
                            <option value="rekatif">rekatif</option>
                          </select>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">non reaktif</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-0 align-middle">
                          <input type="text" value="Pemeriksaan HCG Kualitatif" name="laboratorium_kualitatif[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </td>
                        <td class="p-0 align-middle">
                          <select class="d-block m-0 form-control" name="hasil_laboratorium_kualitatif[]" style="background-color: rgba(255,255,255,.5);">
                            <option value="Negatif">Negatif</option>
                            <option value="Positif">Positif</option>
                          </select>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" readonly name="nilai_rujukan_laboratorium_kualitatif[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.0);">-</textarea>
                        </td>
                        <td class="p-0 align-middle">
                          <textarea rows="2" name="kesimpulan_laboratorium_kualitatif[]" class="d-none m-0 form-control" style="background-color: rgba(255,255,255,.5);"></textarea>
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
<script>
  document.querySelectorAll('input[name="hasil_laboratorium_kimia_klinik[]"]').forEach((input, index, inputs) => {
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Tab') {
        if (index < inputs.length - 1) {
          event.preventDefault();
          inputs[index + 1].focus();
        } else {
          event.preventDefault();
          const firstSelect = document.querySelector('input[name="hasil_laboratorium_hematologi[]"]');
          if (firstSelect) firstSelect.focus();
        }
      }
    });
  });
  document.querySelectorAll('input[name="hasil_laboratorium_hematologi[]"]').forEach((input, index, inputs) => {
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Tab') {
        if (index < inputs.length - 1) {
          event.preventDefault();
          inputs[index + 1].focus();
        } else {
          event.preventDefault();
        }
      }
    });
  });
</script>
<!-- /.content -->
<?= $this->endSection(); ?>