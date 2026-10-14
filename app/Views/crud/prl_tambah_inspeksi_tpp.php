<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormInspeksiTpp" action="/prl/simpan_inspeksi_tpp<?= isset($getInspeksiTpp['id_prl_inspeksi_tpp']) ? '/' . $getInspeksiTpp['id_prl_inspeksi_tpp'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Tambah Data</h3>
            </div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_tpp" id="wilayah_kerja_tpp" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getInspeksiTpp['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getInspeksiTpp['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
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
                    <input type="text" name="tanggal_inspeksi" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" value="<?= isset($getInspeksiTpp['tanggal_inspeksi']) ? $getInspeksiTpp['tanggal_inspeksi'] : old("tanggal_inspeksi_form"); ?>" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama TPP</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="nama_tpp" id="nama_tpp" style="width:100%;">
                    <option value="">-- Pilih Nama TPP --</option>
                    <?php foreach ($siTPP as $data): ?>
                      <option value="<?= $data['id_prl_tpp']; ?>" <?= $data['id_prl_tpp'] == $getInspeksiTpp['id_prl_tpp'] ? 'selected' : ''; ?>><?= $data['nama_tpp']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Petugas Pemeriksa</label>
                <div class="col-sm-10">
                  <select class="form-control select2" multiple="" data-placeholder="Pilih nama petugas" name="pegawai_tpp[]" id="pegawai_tpp" style="width: 100%;">
                    <?php
                    if (isset($getInspeksiTpp))
                      $array = explode(' | ', $getInspeksiTpp['id_data_pegawai']);
                    ?>

                    <?php foreach ($pegawai as $data_pegawai) : ?>
                      <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($getInspeksiTpp) ? (in_array($data_pegawai['id_data_pegawai'], $array) ? 'selected' : '') : ''; ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pemilik/Penanggung Jawab</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['penanggung_jawab'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah Karyawan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="jml_karyawan" id="jml_karyawan" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['jml_karyawan'] : ''; ?>">
                </div>
              </div>

              <hr>

              <!-- SECTION A ----------------------------------------------------------------------------->
              <h5 class="text-primary">A. Penilaian Skor Pangan</h5>
              <p>Penilaian skor risiko profil pangan digambarkan melalui pohon keputusan yang dapat dilihat <a class="button-show-tpp-sec-a" href="#">di sini.</a></p>

              <div class="form-group">
                <label>1. Apakah pangan memiliki pH < 4,6 atau Aw < 0,85? Ataukan pangan mengandung gula tinggi?</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sec_a1" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_satu'] == 'Ya' ? 'checked' : ''; ?>>
                      <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sec_a1" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_satu'] == 'Tidak' ? 'checked' : ''; ?>>
                      <label class="form-check-label">Tidak</label>
                    </div>
              </div>

              <div class="form-group" id="sec_a2" style="<?= empty($getInspeksiTpp['sec_a_dua']) ? 'display: none;' : ''; ?>">
                <label>2. Apakah pangan termasuk dalam kategori pangan PHF?</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_a2" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_dua'] == 'Ya' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_a2" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_dua'] == 'Tidak' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Tidak</label>
                </div>
              </div>

              <div class="form-group" id="sec_a3" style="<?= empty($getInspeksiTpp['sec_a_tiga']) ? 'display: none;' : ''; ?>">
                <label>3. Apakah pH produk merupakan kombinasi pH > 4,6 dan Aw > 0,85?</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_a3" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_tiga'] == 'Ya' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_a3" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_a_tiga'] == 'Tidak' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Tidak</label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 keterangan-label-sec-a">
                  Skor Risiko
                  <?php
                  if (isset($getInspeksiTpp['sec_a_skor'])) {
                    $skor = $getInspeksiTpp['sec_a_skor'];
                    if ($skor == 5) {
                      echo '<span class="badge bg-success">RENDAH</span>';
                    } elseif ($skor == 10) {
                      echo '<span class="badge bg-warning">SEDANG</span>';
                    } elseif ($skor == 15) {
                      echo '<span class="badge bg-danger">TINGGI</span>';
                    }
                  } else {
                    echo "(Keterangan: tinggi/sedang/rendah)";
                  }
                  ?>
                </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="skor_risiko_sec_a" id="skor_risiko_sec_a" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_a_skor'] : ''; ?>">
                </div>
              </div>

              <hr>

              <!-- SECTION B ----------------------------------------------------------------------------->
              <h5 class="text-primary">B. Penilaian Skor Bahaya Pangan</h5>
              <p>Penilaian skor risiko mitigasi bahaya pangan dilakukan melalui pohon keputusan yang dapat dilihat <a class="button-show-tpp-sec-b" href="#">di sini.</a></p>

              <div class="form-group" id="sec_b1">
                <label>1. Apakah pangan dikonsumsi dalam kondisi mentah atau sebelum dikonsumsi pangan tidak mengalami proses yang menghilangkan bahaya?</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b1" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_satu'] == 'Ya' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b1" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_satu'] == 'Tidak' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Tidak</label>
                </div>
              </div>

              <div class="form-group" id="sec_b2" style="<?= empty($getInspeksiTpp['sec_b_dua']) ? 'display: none;' : ''; ?>">
                <label>2. Apakah pangan langsung dikonsumsi setelah dimasak/diolah? Atau disimpan pada suhu <i>danger zone</i> maksimal 4 jam setelah dimasak? Atau apakah pangan dilakukan mitigasi yang signifikan untuk menghilangkan bahaya (contoh: sterilisasi, pengawetan).</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b2" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_dua'] == 'Ya' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b2" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_dua'] == 'Tidak' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Tidak</label>
                </div>
              </div>

              <div class="form-group" id="sec_b3" style="<?= empty($getInspeksiTpp['sec_b_tiga']) ? 'display: none;' : ''; ?>">
                <label>3. Apakah pangan dikemas tertutup rapat dan disimpan dengan baik untuk menghindari kontaminasi atau timbulnya bahaya?</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b3" value="Ya" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_tiga'] == 'Ya' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sec_b3" value="Tidak" <?= isset($getInspeksiTpp) && $getInspeksiTpp['sec_b_tiga'] == 'Tidak' ? 'checked' : ''; ?>>
                  <label class="form-check-label">Tidak</label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 keterangan-label-sec-b">
                  Skor Risiko
                  <?php
                  if (isset($getInspeksiTpp['sec_b_skor'])) {
                    $skor = $getInspeksiTpp['sec_b_skor'];
                    if ($skor == 5) {
                      echo '<span class="badge bg-success">RENDAH</span>';
                    } elseif ($skor == 10) {
                      echo '<span class="badge bg-warning">SEDANG</span>';
                    } elseif ($skor == 15) {
                      echo '<span class="badge bg-danger">TINGGI</span>';
                    }
                  } else {
                    echo "(Keterangan: tinggi/sedang/rendah)";
                  }
                  ?>
                </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="skor_risiko_sec_b" id="skor_risiko_sec_b" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_b_skor'] : ''; ?>">
                </div>
              </div>

              <hr>

              <!-- SECTION C ----------------------------------------------------------------------------->
              <h5 class="text-primary">C. Penilaian Skor Ukuran Bisnis</h5>
              <p>Penilaian skor ukuran bisnis dapat dilihat <a class="button-show-tpp-sec-c" href="#">di sini.</a></p>
              <br>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah Karyawan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="jml_karyawan1" id="jml_karyawan1" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['jml_karyawan_tpp'] : '' ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kapasitas Produksi</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="kapasitas_produksi" id="kapasitas_produksi" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['kapasitas_produksi_tpp'] : '' ?>">
                </div>
              </div>

              <label>Hasil:</label>
              <div class="form-group row">
                <label class="col-sm-2">Keterangan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="keterangan_risiko_sec_c" id="keterangan_risiko_sec_c" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_c_keterangan'] : '' ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 keterangan-label-sec-c">Skor Risiko</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="skor_risiko_sec_c" id="skor_risiko_sec_c" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_c_skor'] : '' ?>">
                </div>
              </div>

              <hr>

              <!-- SECTION D ----------------------------------------------------------------------------->
              <h5 class="text-primary">D. Riwayat Ketidaksesuaian</h5>
              <br>

              <div class="form-group row">
                <label class="col-sm-2">Nilai inspeksi sebelumnya</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nilai_inspeksi_sebelumnya" id="nilai_inspeksi_sebelumnya" value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_d_nilai_inspeksi_sebelumnya'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 keterangan-label-sec-d">Skor Risiko (Keterangan: tinggi/sedang/rendah)</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="skor_risiko_sec_d" id="skor_risiko_sec_d" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['sec_d_skor'] : '' ?>">
                </div>
              </div>

              <hr>

              <div class="form-group row">
                <label class="col-sm-2">Kesimpulan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="kesimpulan" id="kesimpulan" value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['kesimpulan'] : '' ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Makanan yang diproses</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="makanan" id="makanan" value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['makanan'] : '' ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Hasil</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="hasil" id="hasil" readonly value="<?= isset($getInspeksiTpp) ? $getInspeksiTpp['hasil'] : '' ?>">
                </div>
              </div>

              <div class="form-group row" id="kategori-tpp" style="display: none;">
                <label class="col-sm-2">Kategori TPP</label>
                <div class="col-sm-10" id="kategoriTppBadgeContainer">
                  <!-- Tempat badge dan risiko kontaminasi ditampilkan -->
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Saran dan Rencana Tindakan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="saran_rencana_tindakan" id="saran_rencana_tindakan" rows="5" placeholder="Masukkan saran dan rencana tindakan ..." style="height: 63px;"><?= isset($getInspeksiTpp) ? $getInspeksiTpp['saran_rencana_tindakan'] : '' ?></textarea>
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
</section>

<?= $this->endSection(); ?>