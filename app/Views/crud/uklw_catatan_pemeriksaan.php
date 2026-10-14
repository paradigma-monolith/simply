<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<style>
  .plus {
    cursor: pointer;
    top: -10px;
    left: -10px;
    height: 1.25em;
    width: 1.25em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1.25em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1.25em;
    content: "+";
    background-color: #0275d8;
  }

  .min {
    cursor: pointer;
    top: -10px;
    left: -10px;
    height: 1.25em;
    width: 1.25em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1.25em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1.25em;
    content: "-";
    background-color: #d33333;
  }

  .custome-label-h4 {
    position: absolute;
    cursor: text;
    font-weight: 700;
    opacity: 1;
    -webkit-transition: all .2s;
    transition: all .2s;
    top: -.5em;
    left: .75rem;
    z-index: 3;
    line-height: 1;
    padding: 0 1px;
  }
  .uklw-objective-catatan {
      cursor: pointer; 
      background-color: rgba(134, 207, 218, .5); 
      height: 265px; overflow: hidden;
  }
  .uklw-objective-catatan:hover {
      box-shadow: 2px 4px 6px rgb(0 0 0 / 78%), inset 1px 2px 2px rgb(0 0 0 / 78%);
  }
  .uklw-objective-catatan:active {
      background-color: #dfc395;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_catata_pemeriksaan" method="post">
        <input type="hidden" name="id_uklw_catatan_pemeriksaan" value="<?= $getData['id_uklw_catatan_pemeriksaan']; ?>">
        <input type="hidden" name="id_uklw_pemkes" value="<?= $getData['id_uklw_pemkes']; ?>">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <table class="table table-info table-hover table-striped table-bordered table-sm align-middle">
          <thead>
            <tr class="bg-primary">
              <td style="width: 65%;">DATA PASIEN</td>
              <td></td>
            </tr>
            <tr>
              <td class="pl-4 align-middle"><?= $getData['identitas']; ?></td>
              <td class="p-0 px-3">
                <?= $getData['nomor_identitas']; ?>
              </td>
            </tr>
            <tr>
              <td class="pl-4 align-middle">NAMA SESUAI IDENTITAS</td>
              <td class="p-0 px-3">
                <?= $getData['nama']; ?>
              </td>
            </tr>
            <tr>
              <td class="pl-4 align-middle">TANGGAL LAHIR</td>
              <td class="p-0 px-3">
                <?= mediumdate_indo($getData['tgl_lahir']); ?>
              </td>
            </tr>
            </thead>
        </table>

        <h4><span class="badge badge-danger">LIHAT CATATAN PEMERIKSAAN KESEHATAN</span></h4>

        <div class="row">
          <div class="col-6 mt-3">
            <div class="form-group">
              <label>
                <h4 class="custome-label-h4">Subjective</h4 class="custome-label-h4">
              </label>
              <textarea placeholder="Catatan anamnesa pasien" name="subjective" rows="9" class="p-4 d-block form-control" style="background-color: rgba(134, 207, 218, .5);"><?= $getData['subjective']; ?></textarea>
            </div>
          </div>
          <div class="col-6 mt-3">
            <div class="form-group">
              <label>
                <h4 class="custome-label-h4">Objective</h4 class="custome-label-h4">
              </label>
              <div  class="p-1 px-4 d-block form-control uklw-objective-catatan">
                <table class="table-sm table-transparent" style="width: 100%;">
                  <tr class="bg-primary">
                    <td class="pl-4" style="width: 30%;">PEMERIKSAAN FISIK</td>
                    <td></td>
                  </tr>
                  <?php
                  $array = unserialize($getData['pemeriksaan_fisik']);
                  foreach ($array as $data) : ?>
                    <tr>
                      <td class="p-0 align-middle">
                        <?= $data['pemeriksaan_fisik']; ?>
                        <!-- <input type="text" value="" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;"> -->
                      </td>
                      <td class="p-0">
                        <?= $data['hasil_pemeriksaan_fisik']; ?> <?= isset($data['satuan_pemeriksaan_fisik']) && $data['satuan_pemeriksaan_fisik'] != "" ? $data['satuan_pemeriksaan_fisik'] : ""; ?>
                        <!-- <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"> -->
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="bg-primary">
                    <td style="width: 65%;">RIWAYAT PENYAKIT</td>
                    <td></td>
                  </tr>
                  <?php
                  $array = unserialize($getData['riwayat_penyakit']);
                  foreach ($array as $data) :
                    $arrayICDS = explode(",", $data['hasil_riwayat_penyakit']);
                  ?>
                    <tr>
                      <td class="p-0 align-middle">
                        <?= $data['riwayat_penyakit']; ?>
                        <!-- <input type="text" value="" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;"> -->
                      </td>
                      <td class="p-0">
                        <?php for ($i = 0; $i < count($arrayICDS); $i++) : ?>
                          <span class="badge badge-info px-2 mb-1 mr-1"> <?= $arrayICDS[$i]; ?> </span>
                        <?php endfor; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
              <!-- <textarea placeholder="Disalin dari hasil pemeriksaan fisik dan penunjang" readonly name="objective" rows="9" class="p-4 d-block form-control" style="background-color: rgba(134, 207, 218, .5);"></textarea> -->
            </div>
          </div>
          <div class="col-6 mt-3">
            <div class="form-group">
              <label>
                <h4 class="custome-label-h4">Assesment</h4 class="custome-label-h4">
              </label>
              <?php
              $array = unserialize($getData['assesment']);
              ?>
              <div class="card bg-info">
                <div class="card-body">
                  <div class="form-group">
                    <label for="">Diagnosa Medis</label>
                    <div class="p-4 d-block form-control assesment_value" style="background-color: rgba(134, 207, 218, .5); height: 80px; overflow-x: hidden; overflow-y: scroll;">
                      <?php foreach ($dataICDS as $data) : ?>
                        <span class="badge badge-info pr-2 mb-1 mr-1"><button type="button" class="btn btn-sm py-0 btn-remove-value" data-code="<?= $data['code']; ?>"><i class="fas text-white fa-times"></i></button> <?= $data['name_id']; ?> / <?= $data['name_en']; ?> </span>
                      <?php endforeach; ?>
                    </div>
                    <select multiple="multiple" name="hasil_riwayat_penyakit[]" class="select2 form-control" style="width: 100%;"></select>
                    <input type="hidden" name="assesment_diagnosa_medis" value="<?= $array['assesment_diagnosa_medis'] ?? ''; ?>">
                  </div>

                  <div class="form-group">
                    <label for="">Kesimpulan</label>
                    <input required type="text" class="form-control" placeholder="FIT / FIT WITH NOTE / UNFIT" name="assesment_kesimpulan" value="<?= $array['assesment_kesimpulan'] ?? ''; ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class=" col-6 mt-3">
            <div class="form-group">
              <label>
                <h4 class="custome-label-h4">Planning</h4 class="custome-label-h4">
              </label>
              <div class="card bg-info">
                <div class="card-body">
                  <div class="input-group mb-3 row container">
                    <div class="col-12">
                      <label for="">Tindakan</label>
                      <?php
                      $array = unserialize($getData['planning']);
                      ?>
                    </div>
                    <input required name="planning_tindakan" value="<?= $array['planning_tindakan'] ?? ''; ?>" placeholder="....." type="text" class="form-control" aria-label="Text input with checkbox">
                  </div>
                  <div class="mb-3 row container">
                    <div class="col-12">
                      <label for="">Obat</label>
                    </div>
                    <?php
                    ?>
                    <div class="border col-12 px-4 pt-3">
                      <span class="plus">+</span>
                      <div class="list-obat">
                        <div class="row">
                          <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                            <label class="has-float-label">
                              <div>
                                <select name="planning_obat[]" class="select2-obat" style="width: 100%;">
                                  <option value="">--Tidak ada pilihan--</option>
                                  <?php foreach ($obat as $data): ?>
                                    <option <?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat'] == ($data['golongan'] . ' - ' . $data['nama']) ? 'selected' : ''; ?> value="<?= $data['golongan'] . ' - ' . $data['nama']; ?>"><?= $data['golongan'] . ' - ' . $data['nama']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                              <span class="text-dark">Obat</span>
                            </label>
                          </div>

                          <div class="form-group mb-1 col-sm-3 px-0 pr-1">
                            <label class="has-float-label">
                              <input type="number" class="form-control" value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_jumlah'] ? $array['planning_obat'][0]['planning_obat_jumlah'] : ""; ?>" name="planning_obat_jumlah[]">
                              <span class="text-dark">Jumlah</span>
                            </label>
                          </div>
                          <div class="form-group mb-1 col-sm-3 px-0 pr-1">
                            <label class="has-float-label">
                              <input type="text" class="form-control" value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_sehari'] ? $array['planning_obat'][0]['planning_obat_sehari'] : ""; ?>" name="planning_obat_sehari[]">
                              <span class="text-dark">Sehari</span>
                            </label>
                          </div>
                          <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                            <label class="has-float-label">
                              <input type="text" class="form-control" value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_waktu'] ? $array['planning_obat'][0]['planning_obat_waktu'] : ""; ?>" name="planning_obat_waktu[]">
                              <span class="text-dark">Waktu</span>
                            </label>
                          </div>
                          <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                            <label class="has-float-label">
                              <input type="text" class="form-control" value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_keterangan'] ? $array['planning_obat'][0]['planning_obat_keterangan'] : ""; ?>" name="planning_obat_keterangan[]">
                              <span class="text-dark">Keterangan</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- border col-12 px-4 pt-3 -->
                    <div class="more-obat">
                      <?php if ($array): ?>
                        <?php foreach ($array['planning_obat'] as $row => $dataObat): ?>
                          <?php if ($row > 0): ?>
                            <div class="border col-12 px-4 pt-3"><span class="min">-</span>
                              <div class="row">
                                <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                                  <label class="has-float-label">
                                    <div>
                                      <select name="planning_obat[]" class="select2-obat" style="width: 100%;">
                                        <option value="">--Tidak ada pilihan--</option>
                                        <?php foreach ($obat as $data): ?>
                                          <option <?= $dataObat['planning_obat'] == ($data['golongan'] . ' - ' . $data['nama']) ? 'selected' : ''; ?> value="<?= $data['golongan'] . ' - ' . $data['nama']; ?>"><?= $data['golongan'] . ' - ' . $data['nama']; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>
                                    <span class="text-dark">Obat</span>
                                  </label>
                                </div>

                                <div class="form-group mb-1 col-sm-3 px-0 pr-1">
                                  <label class="has-float-label">
                                    <input type="number" class="form-control" value="<?= $dataObat['planning_obat_jumlah']; ?>" name="planning_obat_jumlah[]">
                                    <span class="text-dark">Jumlah</span>
                                  </label>
                                </div>
                                <div class="form-group mb-1 col-sm-3 px-0 pr-1">
                                  <label class="has-float-label">
                                    <input type="text" class="form-control" value="<?= $dataObat['planning_obat_sehari']; ?>" name="planning_obat_sehari[]">
                                    <span class="text-dark">Sehari</span>
                                  </label>
                                </div>
                                <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                                  <label class="has-float-label">
                                    <input type="text" class="form-control" value="<?= $dataObat['planning_obat_waktu']; ?>" name="planning_obat_waktu[]">
                                    <span class="text-dark">Waktu</span>
                                  </label>
                                </div>
                                <div class="form-group mb-1 col-sm-6 px-0 pr-1">
                                  <label class="has-float-label">
                                    <input type="text" class="form-control" value="<?= $dataObat['planning_obat_keterangan']; ?>" name="planning_obat_keterangan[]">
                                    <span class="text-dark">Keterangan</span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php endif; ?>

                    </div>
                  </div>
                  <div class="input-group mb-3 row container">
                    <div class="col-12">
                      <label for="">Rujuk</label>
                    </div>
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <input <?= $array && $array['planning_rujuk'] ? 'checked' : ''; ?> type="checkbox" name="planning_rujuk" value="Ya" aria-label="Checkbox for following text input">
                      </div>
                    </div>
                    <input <?= $array && $array['planning_rujuk'] != "" ? 'value="' . $array['planning_tempat_rujuk'] . '"' : 'readonly'; ?> name="planning_tempat_rujuk" placeholder="centang pada bagian kiri (box) dan isi tujuan rujukan" type="text" class="form-control" aria-label="Text input with checkbox">
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col-12 mt-3">
            <div class="form-group">
              <label for="">Dokter Pemeriksa</label>
              <select name="dokter" name="dokter" style="width: 100%;" class="select2" required>
                <option value="">--Pilih Dokter Pemeriksa--</option>
                <?php foreach ($dokter as $data): ?>
                  <option value="<?= $data['id_data_pegawai']; ?>" <?= $getData['id_data_pegawai_dokter'] == $data['id_data_pegawai'] ? 'selected' : ($data['username'] == session()->get('username') ? 'selected' : ''); ?>><?= name_capitalize($data['nama']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-success float-right mx-2"><i class="fas fa-share"></i> Simpan</button>
      </form>
    </div>
  </div>
</section>

<section class="content-modal d-none">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <table class="table-sm table-transparent" style="width: 100%;">
          <tr class="bg-primary">
            <td class="pl-4" style="width: 30%;">PEMERIKSAAN FISIK</td>
            <td></td>
          </tr>
          <?php
          $array = unserialize($getData['pemeriksaan_fisik']);
          foreach ($array as $data) : ?>
            <tr class="border-bottom">
              <td class="p-0 align-middle">
                <?= $data['pemeriksaan_fisik']; ?>
                <!-- <input type="text" value="" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;"> -->
              </td>
              <td class="p-0">
                <?= $data['hasil_pemeriksaan_fisik']; ?> <?= isset($data['satuan_pemeriksaan_fisik']) && $data['satuan_pemeriksaan_fisik'] != "" ? $data['satuan_pemeriksaan_fisik'] : ""; ?>
                <!-- <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"> -->
              </td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="bg-primary">
            <td class="pl-4" style="width: 65%;">RIWAYAT PENYAKIT</td>
            <td></td>
          </tr>
          <?php
          $array = unserialize($getData['riwayat_penyakit']);
          foreach ($array as $data) :
            $arrayICDS = explode(",", $data['hasil_riwayat_penyakit']);
          ?>
            <tr>
              <td class="p-0 align-middle">
                <?= $data['riwayat_penyakit']; ?>
                <!-- <input type="text" value="" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;"> -->
              </td>
              <td class="p-0">
                <?php for ($i = 0; $i < count($arrayICDS); $i++) : ?>
                  <span class="badge badge-info px-2 mb-1 mr-1"> <?= $arrayICDS[$i]; ?> </span>
                <?php endfor; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
      <?php if($getTBC || $getHIV || $getPTM): ?>
      <div class="col-md-8 mb-4">
        <div class="col-md-12 text-center">
            <h4><strong>Skrining</strong></h4>
        </div>
        <div class="container-fluid">
          <div class="row">
            <?php if($getTBC): ?>
              <div class="col-md-6 mb-2">
                <table class="table-sm table-transparent" style="width: 100%;">
                  <tr class="bg-primary">
                    <td colspan="2" class="pl-4" style="width: 30%;">SKRINING TBC (GEJALA)</td>
                  </tr>
                  <?php
                  $array = unserialize($getTBC['skrining_tbc_gejala']);
                  foreach ($array as $data) : ?>
                    <tr class="border-bottom">
                      <td class="p-0 align-middle">
                        <?= $data['skrining_tbc_gejala']; ?>
                      </td>
                      <td class="p-0">
                        <?= $data['hasil_skrining_tbc_gejala']; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            <?php endif; ?>
            <?php if($getHIV): ?>
              <div class="col-md-6 mb-2">
                <table class="table-sm table-transparent" style="width: 100%;">
                  <tr class="bg-primary">
                    <td colspan="2" class="pl-4" style="width: 30%;">SKRINING HIV</td>
                  </tr>
                  <?php
                  $array = unserialize($getHIV['skrining_hiv']);
                  foreach ($array as $data) : ?>
                    <tr class="border-bottom">
                      <td class="p-0 align-middle">
                        <?= $data['skrining_hiv']; ?>
                      </td>
                      <td class="p-0">
                        <?= $data['hasil_skrining_hiv']; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            <?php endif; ?>
            <?php if($getPTM): ?>
              <div class="col-md-6 mb-2">
                <table class="table-sm table-transparent" style="width: 100%;">
                  <tr class="bg-primary">
                    <td colspan="2" class="pl-4" style="width: 30%;">FAKTOR RISIKO PTM</td>
                  </tr>
                  <?php
                  $array = unserialize($getPTM['skrining_penyakit_tidak_menular']);
                  foreach ($array as $data) : ?>
                    <tr class="border-bottom">
                      <td class="p-0 align-middle">
                        <?= $data['skrining_penyakit_tidak_menular']; ?>
                      </td>
                      <td class="p-0">
                        <?= $data['hasil_skrining_penyakit_tidak_menular']; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if($getLaboratorium): ?>
        <div class="col-md-12 text-center">
            <h4><strong>Pemeriksaan Laboratorium</strong></h4>
        </div>
        <div class="col-md-6">
            <table class="table-sm table-transparent" style="width: 100%;">
              <tr class="bg-primary">
                <td colspan="2" class="pl-4" style="width: 30%;">KIMIA KLINIK</td>
              </tr>
              <?php
              $array = unserialize($getLaboratorium['laboratorium_kimia_klinik']);
              foreach ($array as $key => $data) : ?>
                <tr class="border-bottom">
                  <td class="p-0 align-middle">
                    <?= $data['laboratorium_kimia_klinik']; ?>
                  </td>
                  <td class="p-0">
                    <?= $data['hasil_laboratorium_kimia_klinik']; ?> 
                    <?= isset($data['satuan_laboratorium_kimia_klinik']) ? replaceStarWithDegree(convertToSuperscript($data['satuan_laboratorium_kimia_klinik'])) : ''; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr class="mb-2">
              </tr>
              <!--=======================================================-->
              <tr class="bg-primary">
                <td colspan="2" class="pl-4" style="width: 30%;">HEMATOLOGI</td>
              </tr>
              <?php
              $array = unserialize($getLaboratorium['laboratorium_hematologi']);
              foreach ($array as $key => $data) : ?>
                <tr class="border-bottom">
                  <td class="p-0 align-middle">
                    <?= $data['laboratorium_hematologi']; ?>
                  </td>
                  <td class="p-0">
                    <?= $data['hasil_laboratorium_hematologi']; ?> 
                    <?= isset($data['satuan_laboratorium_hematologi']) ? replaceStarWithDegree(convertToSuperscript($data['satuan_laboratorium_hematologi'])) : ''; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr class="mb-2">
              </tr>
              <!--=======================================================-->
            </table>
        </div>
        <div class="col-md-6">
            <table>
              <tr class="bg-primary">
                <td colspan="2" class="pl-4" style="width: 30%;">KUALITATIF</td>
              </tr>
              <?php
              $array = unserialize($getLaboratorium['laboratorium_kualitatif']);
              foreach ($array as $key => $data) : ?>
                <tr class="border-bottom">
                  <td class="p-0 align-middle">
                    <?= $data['laboratorium_kualitatif']; ?>
                  </td>
                  <td class="p-0">
                    <?= $data['hasil_laboratorium_kualitatif']; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr class="mb-2">
              </tr>
            </table>
        </div>
      <?php endif; ?>
      <?php if (!$getTBC || !$getHIV || !$getPTM || !$getLaboratorium): ?>
        <div class="col-md-12 text-danger">
          <h4><strong>Pemeriksaan yang tidak dilakukan</strong></h4>
          <ul>
            <?= !$getTBC ? "<li>Skrining TBC</li>" : ""; ?>
            <?= !$getHIV ? "<li>Skrining HIV</li>" : ""; ?>
            <?= !$getPTM ? "<li>Skrining Penyakit Tidak Menular</li>" : ""; ?>
            <?= !$getLaboratorium ? "<li>Pemeriksaan Laboratorium</li>" : ""; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<script>
  $(document).on('click', '.uklw-objective-catatan', function() {
    $("#modal-xl").find(".modal-body").html($(".content-modal").html());
    $("#modal-xl").modal('show');
  });

  let timeoutId;
  var index = 0;
  var text = '';
  var typingInterval;
  var nilaiSebelumnya = '';
  var nilaiAssesment = [
    <?php foreach ($dataICDS as $data) : ?> '<?= $data['code']; ?>',
    <?php endforeach; ?>
  ];

  $(document).on('input', '.select2-search__field', function() {
      if ($(this).is('.select2-search__field:first')) {
        let $inputField = $(this);
    
        clearTimeout(timeoutId);
    
        timeoutId = setTimeout(function() {
          var $selectElement = $('select[name="hasil_riwayat_penyakit[]"]');
          var selectedOptions = $selectElement.find('option:selected');
          var selectedOptionsArray = selectedOptions.toArray();
          console.log(selectedOptionsArray);
    
          $selectElement.val('');
    
          getDataICDS($inputField.val(), $inputField, selectedOptionsArray);
        }, 1000);
      }
  });

  $(document).on('change', 'select[name="hasil_riwayat_penyakit[]"]', function() {
    var selectedOption = $(this).find('option:selected');
    var value = selectedOption.val();
    var text = selectedOption.text();
    var elemen = $(this)

    $(this).select2('destroy')
    elemen.html('');

    // Menghapus nilai dari elemen select
    $(this).val('');

    // Menghapus teks dari setiap opsi
    $(this).find('option').each(function() {
      $(this).text('');
    });

    elemen.select2()

    nilaiSebelumnya = ''
    if (nilaiAssesment.indexOf(value) !== -1) {
      console.log("Nilai ditemukan dalam array.");
    } else {
      nilaiAssesment.push(value);
      $('.assesment_value').append('<span class="badge badge-info pr-2 mb-1 mr-1"><button type="button" class="btn btn-sm py-0 btn-remove-value" data-code="' + value + '"><i class="fas text-white fa-times"></i></button> ' + text + ' </span>')

      var assesment = nilaiAssesment.join(',');
      $('input[name="assesment_diagnosa_medis"]').val(assesment)
    }
  });

  $(document).on('click', '.btn-remove-value', function() {
    var code = $(this).data('code')
    $(this).parent().remove();

    nilaiAssesment = nilaiAssesment.filter(function(nilai) {
      return nilai !== code;
    });

    var assesment = nilaiAssesment.join(',');
    $('input[name="assesment_diagnosa_medis"]').val(assesment)
  });


  function getDataICDS(nilai, $inputField, selectedValue) {
    if (nilai.length > 1 && nilaiSebelumnya !== nilai) {
      nilaiSebelumnya = nilai;
      $.ajax({
        url: '/uklw/ajax/searchData',
        data: {
          nilai: nilai
        },
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data) {
            index = 0;
            text = nilai;
            $inputField.val("");
            typingInterval = setInterval(function() {
              typeText($inputField);
            }, 10);

            var $selectElement = $('select[name="hasil_riwayat_penyakit[]"]');
            $selectElement.html('');

            $.each(response.data, function(index, item) {
              $selectElement.append(selectedValue);
              $selectElement.append('<option value="' + item.value + '">' + item.label + '</option>');
            });
          }
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      });
    }
  }

  function typeText($inputField) {
    if (index < text.length) {
      var currentText = $inputField.val();
      var newText = currentText + text.charAt(index);
      $inputField.val(newText);
      $inputField.trigger('input');
      index++;
    } else {
      clearInterval(typingInterval);
    }
  }

  $(document).on('change', 'select[name="hasil_riwayat_penyakit[]"]', function() {
    var value = $(this).val();

    $(".assesment_value").append()
  })

  $(document).on('click', '.assesment_value', function() {
    $('.select2-search__field').focus();
  })
  $(document).on('click', 'input[name="planning_rujuk"]', function() {
    if ($(this).is(':checked')) {
      $('input[name="planning_tempat_rujuk"]').prop('readonly', false);
    } else {
      $('input[name="planning_tempat_rujuk"]').prop('readonly', true);
    }
  });
</script>

<script>
  $(document).ready(function() {
    setTimeout(function() {
      $(".select2-obat").select2();
    }, 200);
  });

  $(document).on('click', '.plus', function() {
    $("#overlay").show();
    $(".select2-obat").select2("destroy");
    let listObat = $(".list-obat").clone();

    $(".more-obat").append(
      '<div class="border col-12 px-4 pt-3"><span class="min">-</span>' +
      listObat.html() +
      '</div>'
    );

    setTimeout(function() {
      $("#overlay").hide();
      $(".select2-obat").select2();
    }, 200);
  });

  // Fungsi untuk memeriksa nilai select2
  function checkSelectValuesObat(activeSelect) {
    let selectedValues = [];
    $(".select2-obat").each(function() {
      let value = $(this).val();
      if (value) {
        if (selectedValues.includes(value)) {
          Swal.fire({
            icon: 'warning',
            title: 'Obat sudah terpilih!'
          });
          $(activeSelect).val("").trigger('change');
        } else {
          selectedValues.push(value);
        }
      }
    });
  }

  $(document).on('change', '.select2-obat', function() {
    checkSelectValuesObat(this);
  });

  $(document).on('click', '.min', function() {
    $(this).closest('.border').remove();
  });
</script>

<!-- /.content -->
<?= $this->endSection(); ?>