<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormInspeksiSab" action="/prl/simpan_inspeksi_sab<?= isset($getInspeksiSab['id_prl_inspeksi_sab']) ? '/' . $getInspeksiSab['id_prl_inspeksi_sab'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Tambah Data</h3>
            </div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_sab" id="wilayah_kerja_sab" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getInspeksiSab['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getInspeksiSab['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>"><?= $dataWilker['unit_kerja']; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Pemeriksaan</label>
                <div class="col-sm-10">
                  <div class="input-group date" id="date" data-target-input="nearest">
                    <input type="text" name="tanggal_inspeksi" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" value="<?= isset($getInspeksiSab['tanggal_inspeksi']) ? $getInspeksiSab['tanggal_inspeksi'] : old("tanggal_inspeksi_form"); ?>" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama SAB</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="nama_sab" id="nama_sab" style="width:100%;">
                    <option value="">-- Pilih Nama SAB --</option>
                    <?php foreach ($siSAB as $data): ?>
                      <option value="<?= $data['id_prl_sab']; ?>" <?= $data['id_prl_sab'] == $getInspeksiSab['id_prl_sab'] ? 'selected' : ''; ?>><?= $data['nama_sab']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Petugas Pemeriksa</label>
                <div class="col-sm-10">
                  <select class="form-control select2" multiple="" data-placeholder="Pilih nama petugas" name="pegawai_sab[]" id="pegawai_sab" style="width: 100%;">
                    <?php
                    if (isset($getInspeksiSab))
                      $array = explode(' | ', $getInspeksiSab['id_data_pegawai']);
                    ?>

                    <?php foreach ($pegawai as $data_pegawai) : ?>
                      <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($getInspeksiSab) ? (in_array($data_pegawai['id_data_pegawai'], $array) ? 'selected' : '') : ''; ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pemilik/Penanggung Jawab</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['penanggung_jawab_sab'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Sumber Air</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="sumber_air" id="sumber_air" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['sumber_air_sab'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kapasitas Penampungan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="kapasitas_penampungan" id="kapasitas_penampungan" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['kapasitas_penampungan_sab'] : ''; ?>">
                </div>
              </div>

              <hr>

              <h5>Variabel Pemeriksaan</h5>
              <br>
              <div>
                <h5 class="text-primary">A. Data Umum</h6>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">1. Penanggung Jawab</label>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama_pj" id="nama_pj" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['penanggung_jawab_sab'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">No. HP</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nomor_hp_pj" id="nomor_hp_pj" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['penanggung_jawab_no_hp_sab'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">2. Sumber Air</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="sumber_air1" id="sumber_air1" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['sumber_air_sab'] : ''; ?>">
                </div>
              </div>

              <br>
              <div>
                <h5 class="text-primary">B. Kualitas Fisik</h6>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">1. Keruh</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b1" id="b1_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_satu'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b1_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b1" id="b1_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_satu'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b1_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">2. Bau</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b2" id="b2_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_dua'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b2_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b2" id="b2_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_dua'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b2_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">3. Rasa</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b3" id="b3_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_tiga'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b3_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b3" id="b3_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_tiga'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b3_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">4. Warna</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b4" id="b4_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_empat'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b4_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="b4" id="b4_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_b_empat'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="b4_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <br>
              <div>
                <h5 class="text-primary">C. Penilaian Risiko</h6>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">1. Apakah penampungan air ada kebocoran?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c1" id="c1_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_satu'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c1_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c1" id="c1_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_satu'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c1_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">2. Apakah penampungan air terbuka?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c2" id="c2_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_dua'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c2_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c2" id="c2_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_dua'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c2_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">3. Apakah Penampungan terbuat dari bahan mudah korosi, tidak kuat, dan sulit dibersihkan?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c3" id="c3_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_tiga'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c3_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c3" id="c3_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_tiga'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c3_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">4. Apakah tutup manhole-nya kotor?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c4" id="c4_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_empat'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c4_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c4" id="c4_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_empat'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c4_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">5. Apakah lokasi sekitar dalam keadaan kotor?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c5" id="c5_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_lima'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c5_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c5" id="c5_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_lima'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c5_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">6. Apakah terdapat sumber pencemaran (kotoran manusia / hewan, sampah, dsb)?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c6" id="c6_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_enam'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c6_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c6" id="c6_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_enam'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c6_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">7. Apakah menjadi sarang vektor?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c7" id="c7_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_tujuh'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c7_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c7" id="c7_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_tujuh'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c7_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">8. Apakah hewan atau tikus dapat masuk ke dalam penampungan air?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c8" id="c8_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_delapan'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c8_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c8" id="c8_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_delapan'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c8_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">9. Apakah ada kebocoran pada sistim distribusi?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c9" id="c9_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_sembilan'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c9_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c9" id="c9_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_sembilan'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c9_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-sm-12">10. Apakah ada keran yang bocor?</label>
                </div>
                <div class="row col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c10" id="c10_ya" value="Ya" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_sepuluh'] == 'Ya' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c10_ya">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="c10" id="c10_tidak" value="Tidak" <?= isset($getInspeksiSab) && $getInspeksiSab['sec_c_sepuluh'] == 'Tidak' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="c10_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <hr>

              <input type="text" id="totalYa" style="display: none;">

              <div class="form-group row">
                <label class="col-sm-2">Hasil</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="hasil" id="hasil" readonly value="<?= isset($getInspeksiSab) ? $getInspeksiSab['hasil'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row" id="risiko-kontaminasi" style="<?= isset($getInspeksiSab) ? '' : 'display: none;'; ?>">
                <label class="col-sm-2">Risiko Kontaminasi</label>
                <div class="col-sm-10" id="risikoBadgeContainer">
                  <!-- Tempat badge dan risiko kontaminasi ditampilkan -->
                  <?php
                  if (isset($getInspeksiSab['hasil'])) {
                    $skor = $getInspeksiSab['hasil'];
                    if ($skor < 25) {
                      echo '<span class="badge bg-success">Rendah (R)</span>';
                    } elseif ($skor >= 25 && $skor <= 50) {
                      echo '<span class="badge bg-warning">Sedang (S)</span>';
                    } elseif ($skor > 50 && $skor <= 75) {
                      echo '<span class="badge bg-danger">Tinggi (S)</span>';
                    } else {
                      echo '<span class="badge bg-dark">Amat Tinggi (AT)</span>';
                    }
                  }
                  ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Saran dan Rencana Tindak Lanjut</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="saran_rencana_tindak_lanjut" id="saran_rencana_tindak_lanjut" rows="5" placeholder="Masukkan saran dan rencana tindakan ..." style="height: 63px;"><?= isset($getInspeksiSab) ? $getInspeksiSab['saran_rencana_tindakan'] : ''; ?></textarea>
                </div>
              </div>

            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>