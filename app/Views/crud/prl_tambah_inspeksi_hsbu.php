<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .custom-input[readonly] {
    border: none;
    background: transparent;
    font-weight: bold;
    color: black;
    overflow-y: hidden;
    resize: none;
    padding: 0;
    height: 25px;
  }
</style>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormInspeksiHsbu" action="/prl/simpan_inspeksi_hsbu<?= isset($getInspeksiHsbu['id_prl_inspeksi_hsbu']) ? '/' . $getInspeksiHsbu['id_prl_inspeksi_hsbu'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Tambah Data</h3>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_hsbu" id="wilayah_kerja_hsbu" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getInspeksiHsbu['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getInspeksiHsbu['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
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
                    <input type="text" name="tanggal_inspeksi" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" value="<?= isset($getInspeksiHsbu['tanggal_inspeksi']) ? $getInspeksiHsbu['tanggal_inspeksi'] : old("tanggal_inspeksi_form"); ?>" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama Gedung/Bangunan</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="nama_hsbu" id="nama_hsbu" style="width:100%;">
                    <option value="">-- Pilih Nama Gedung/Bangunan --</option>
                    <?php foreach ($siHSBU as $data): ?>
                      <option value="<?= $data['id_prl_hsbu']; ?>" <?= $data['id_prl_hsbu'] == $getInspeksiHsbu['id_prl_hsbu'] ? 'selected' : ''; ?>><?= $data['nama_gedung']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Petugas Pemeriksa</label>
                <div class="col-sm-10">
                  <select class="form-control select2" multiple="" data-placeholder="Pilih nama petugas" name="pegawai_hsbu[]" id="pegawai_hsbu" style="width: 100%;">
                    <?php
                    if (isset($getInspeksiHsbu))
                      $array = explode(' | ', $getInspeksiHsbu['id_data_pegawai']);
                    ?>

                    <?php foreach ($pegawai as $data_pegawai) : ?>
                      <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($getInspeksiHsbu) ? (in_array($data_pegawai['id_data_pegawai'], $array) ? 'selected' : '') : ''; ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pemilik/Penanggung Jawab</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" readonly value="<?= isset($getInspeksiHsbu) ? $getInspeksiHsbu['penanggung_jawab_hsbu'] : '' ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah Karyawan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="jml_karyawan" id="jml_karyawan" readonly value="<?= isset($getInspeksiHsbu) ? $getInspeksiHsbu['jml_karyawan_hsbu'] : '' ?>">
                </div>
              </div>

              <hr>

              <!--------------------------------------------------------------- BAGIAN SATU -->
              <h5 class="text-primary">1. Lingkungan Luar Halaman</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="lingkungan_luar_halaman[]" readonly>Bersih</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_lingkungan_luar_halaman[]" id="satu_a_nilai" placeholder="Nilai maks 2.5" value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][0]['nilai_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][0]['nilai_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_lingkungan_luar_halaman[]" id="satu_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][0]['skor_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][0]['skor_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_lingkungan_luar_halaman[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][0]['keterangan_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][0]['keterangan_lingkungan_luar_halaman']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="lingkungan_luar_halaman[]" readonly>Tertata rapi</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_lingkungan_luar_halaman[]" id="satu_b_nilai" placeholder="Nilai maks 2.5" value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][1]['nilai_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][1]['nilai_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_lingkungan_luar_halaman[]" id="satu_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][1]['skor_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][1]['skor_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_lingkungan_luar_halaman[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][1]['keterangan_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][1]['keterangan_lingkungan_luar_halaman']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="lingkungan_luar_halaman[]" readonly>Tidak ada genangan air/tidak becek</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_lingkungan_luar_halaman[]" id="satu_c_nilai" placeholder="Nilai maks 2.5" value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][2]['nilai_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][2]['nilai_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_lingkungan_luar_halaman[]" id="satu_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][2]['skor_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][2]['skor_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_lingkungan_luar_halaman[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][2]['keterangan_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][2]['keterangan_lingkungan_luar_halaman']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="lingkungan_luar_halaman[]" readonly>Tingkat kebisingan max. 70 dbA</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_lingkungan_luar_halaman[]" id="satu_d_nilai" placeholder="Nilai maks 2.5" value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][3]['nilai_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][3]['nilai_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_lingkungan_luar_halaman[]" id="satu_d_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][3]['skor_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][3]['skor_lingkungan_luar_halaman']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_lingkungan_luar_halaman[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['lingkungan_luar_halaman'][3]['keterangan_lingkungan_luar_halaman']) ? esc($getInspeksiHsbu['lingkungan_luar_halaman'][3]['keterangan_lingkungan_luar_halaman']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN DUA -->
              <h5 class="text-primary">2. Ruang Bangunan</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Bangunan kuat, terpelihara dan bersih</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_a_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][0]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][0]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][0]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][0]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][0]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][0]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Lantai kuat, kedap air, rata dan tidak licin</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_b_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][1]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][1]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][1]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][1]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][1]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][1]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Dinding rata, bersih dan berwarna terang</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_c_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][2]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][2]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][2]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][2]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][2]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][2]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Permukaan dinding yang selalu terkena air, terbuat dari bahan kedap air</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_d_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][3]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][3]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_d_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][3]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][3]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][3]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][3]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Langit-langit kuat, bersih, berwarna terang dan tinggi lantai miimal 2.5 meter</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_e_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][4]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][4]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_e_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][4]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][4]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][4]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][4]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Luas lubang ventilasi (jendela pintu + kisi-kisi) minimal1/6 kali luas lantai</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_f_nilai" placeholder="Nilai maks 1.5" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][5]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][5]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_f_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][5]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][5]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][5]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][5]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="ruang_bangunan[]" readonly>Setiap karyawan mendapat ruang udara minimal 10 m3/karyawan</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_ruang_bangunan[]" id="dua_g_nilai" placeholder="Nilai maks 1" value="<?= isset($getInspeksiHsbu['ruang_bangunan'][6]['nilai_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][6]['nilai_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_ruang_bangunan[]" id="dua_g_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['ruang_bangunan'][6]['skor_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][6]['skor_ruang_bangunan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_ruang_bangunan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['ruang_bangunan'][6]['keterangan_ruang_bangunan']) ? esc($getInspeksiHsbu['ruang_bangunan'][6]['keterangan_ruang_bangunan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN TIGA -->
              <h5 class="text-primary">3. Penyehatan Air</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_air[]" readonly>Tersedia air bersih untuk kebutuhan karyawan dengan kapasitas 40 liter/orang/hari</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_air[]" id="tiga_a_nilai" placeholder="Nilai maks 4" value="<?= isset($getInspeksiHsbu['penyehatan_air'][0]['nilai_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][0]['nilai_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_air[]" id="tiga_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_air'][0]['skor_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][0]['skor_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_air[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_air'][0]['keterangan_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][0]['keterangan_penyehatan_air']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_air[]" readonly>Kualitas air bersih memenuhi syarat fisik</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_air[]" id="tiga_b_nilai" placeholder="Nilai maks 4" value="<?= isset($getInspeksiHsbu['penyehatan_air'][1]['nilai_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][1]['nilai_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_air[]" id="tiga_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_air'][1]['skor_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][1]['skor_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_air[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_air'][1]['keterangan_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][1]['keterangan_penyehatan_air']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_air[]" readonly>Distribusi air dengan sistem perpipaan</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_air[]" id="tiga_c_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['penyehatan_air'][2]['nilai_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][2]['nilai_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_air[]" id="tiga_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_air'][2]['skor_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][2]['skor_penyehatan_air']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_air[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_air'][2]['keterangan_penyehatan_air']) ? esc($getInspeksiHsbu['penyehatan_air'][2]['keterangan_penyehatan_air']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN EMPAT -->
              <h5 class="text-primary">4. Penyehatan Ruang Udara</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_ruang_udara[]" readonly>Suhu : 18-26 C(ruang AC) dan suhu ruangan terasa nyaman (tanpa AC)</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_ruang_udara[]" id="empat_a_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][0]['nilai_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][0]['nilai_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_ruang_udara[]" id="empat_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][0]['skor_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][0]['skor_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_ruang_udara[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][0]['keterangan_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][0]['keterangan_penyehatan_ruang_udara']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_ruang_udara[]" readonly>Kelembaban 40-60 % ruang AC atau kelembaban udara ambient 70 % (tapa AC)</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_ruang_udara[]" id="empat_b_nilai" placeholder="Nilai maks 3" value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][1]['nilai_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][1]['nilai_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_ruang_udara[]" id="empat_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][1]['skor_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][1]['skor_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_ruang_udara[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][1]['keterangan_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][1]['keterangan_penyehatan_ruang_udara']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="penyehatan_ruang_udara[]" readonly>Kadar debu total <0,15 mg/m3 udara atau usap jari pada beberapa permukaan (bagian atas lemari/meja) tidak terdapat debu</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_penyehatan_ruang_udara[]" id="empat_c_nilai" placeholder="Nilai maks 3.5" value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][2]['nilai_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][2]['nilai_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_penyehatan_ruang_udara[]" id="empat_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][2]['skor_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][2]['skor_penyehatan_ruang_udara']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_penyehatan_ruang_udara[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['penyehatan_ruang_udara'][2]['keterangan_penyehatan_ruang_udara']) ? esc($getInspeksiHsbu['penyehatan_ruang_udara'][2]['keterangan_penyehatan_ruang_udara']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN LIMA -->
              <h5 class="text-primary">5. Pengelolaan Limbah</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengelolaan_limbah[]" readonly>Pengelolaan sampah dikumpulkan pada tempat yang telah tersedia</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengelolaan_limbah[]" id="lima_a_nilai" placeholder="Nilai maks 5" value="<?= isset($getInspeksiHsbu['pengelolaan_limbah'][0]['nilai_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][0]['nilai_pengelolaan_limbah']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengelolaan_limbah[]" id="lima_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengelolaan_limbah'][0]['skor_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][0]['skor_pengelolaan_limbah']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengelolaan_limbah[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengelolaan_limbah'][0]['keterangan_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][0]['keterangan_pengelolaan_limbah']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengelolaan_limbah[]" readonly>Limbah cair diolah dalam IPAL</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengelolaan_limbah[]" id="lima_b_nilai" placeholder="Nilai maks 5" value="<?= isset($getInspeksiHsbu['pengelolaan_limbah'][1]['nilai_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][1]['nilai_pengelolaan_limbah']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengelolaan_limbah[]" id="lima_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengelolaan_limbah'][1]['skor_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][1]['skor_pengelolaan_limbah']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengelolaan_limbah[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengelolaan_limbah'][1]['keterangan_pengelolaan_limbah']) ? esc($getInspeksiHsbu['pengelolaan_limbah'][1]['keterangan_pengelolaan_limbah']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN ENAM -->
              <h5 class="text-primary">6. Pencahayaan</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pencahayaan[]" readonly>Intensitas cahaya pada masing-masing ruang kerja minimal 100 lux</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pencahayaan[]" id="enam_a_nilai" placeholder="Nilai maks 6" value="<?= isset($getInspeksiHsbu['pencahayaan'][0]['nilai_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][0]['nilai_pencahayaan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pencahayaan[]" id="enam_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pencahayaan'][0]['skor_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][0]['skor_pencahayaan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pencahayaan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pencahayaan'][0]['keterangan_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][0]['keterangan_pencahayaan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pencahayaan[]" readonly>Pencahayaan ruang tidak menimbulkan bayangan</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pencahayaan[]" id="enam_b_nilai" placeholder="Nilai maks 4" value="<?= isset($getInspeksiHsbu['pencahayaan'][1]['nilai_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][1]['nilai_pencahayaan']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pencahayaan[]" id="enam_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pencahayaan'][1]['skor_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][1]['skor_pencahayaan']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pencahayaan[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pencahayaan'][1]['keterangan_pencahayaan']) ? esc($getInspeksiHsbu['pencahayaan'][1]['keterangan_pencahayaan']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN TUJUH -->
              <h5 class="text-primary">7. Kebisingan Pada Ruang</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="kebisingan_pada_ruang[]" readonly>Tingkat kebisingan diruang kerja maksimal 85 dBA</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_kebisingan_pada_ruang[]" id="tujuh_a_nilai" placeholder="Nilai maks 10" value="<?= isset($getInspeksiHsbu['kebisingan_pada_ruang'][0]['nilai_kebisingan_pada_ruang']) ? esc($getInspeksiHsbu['kebisingan_pada_ruang'][0]['nilai_kebisingan_pada_ruang']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_kebisingan_pada_ruang[]" id="tujuh_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['kebisingan_pada_ruang'][0]['skor_kebisingan_pada_ruang']) ? esc($getInspeksiHsbu['kebisingan_pada_ruang'][0]['skor_kebisingan_pada_ruang']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_kebisingan_pada_ruang[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['kebisingan_pada_ruang'][0]['keterangan_kebisingan_pada_ruang']) ? esc($getInspeksiHsbu['kebisingan_pada_ruang'][0]['keterangan_kebisingan_pada_ruang']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN DELAPAN -->
              <h5 class="text-primary">8. Getaran di Ruang Kerja</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="getaran_di_ruang_kerja[]" readonly>Getaran diruang kerja tidak mengganggu kenyamanan</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_getaran_di_ruang_kerja[]" id="delapan_a_nilai" placeholder="Nilai maks 10" value="<?= isset($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['nilai_getaran_di_ruang_kerja']) ? esc($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['nilai_getaran_di_ruang_kerja']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_getaran_di_ruang_kerja[]" id="delapan_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['skor_getaran_di_ruang_kerja']) ? esc($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['skor_getaran_di_ruang_kerja']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_getaran_di_ruang_kerja[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['keterangan_getaran_di_ruang_kerja']) ? esc($getInspeksiHsbu['getaran_di_ruang_kerja'][0]['keterangan_getaran_di_ruang_kerja']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN SEMBILAN -->
              <h5 class="text-primary">9. Pengendalian Vektor Penyakit</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengendalian_vektor_penyakit[]" readonly>Indeks lalat dalam pengukuran 30 menit maksimal 8 ekor/fly gnu (100x100 cm)</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengendalian_vektor_penyakit[]" id="sembilan_a_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['nilai_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['nilai_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengendalian_vektor_penyakit[]" id="sembilan_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['skor_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['skor_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengendalian_vektor_penyakit[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['keterangan_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][0]['keterangan_pengendalian_vektor_penyakit']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengendalian_vektor_penyakit[]" readonly>Indeks kecoa dalam pengukuran 24 jam maksimal 2 ekor/plate (20x20 cm)</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengendalian_vektor_penyakit[]" id="sembilan_b_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['nilai_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['nilai_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengendalian_vektor_penyakit[]" id="sembilan_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['skor_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['skor_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengendalian_vektor_penyakit[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['keterangan_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][1]['keterangan_pengendalian_vektor_penyakit']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengendalian_vektor_penyakit[]" readonly>Indeks jentik aedes aegypti perimeter area (house index aedes ) = 0</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengendalian_vektor_penyakit[]" id="sembilan_c_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['nilai_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['nilai_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengendalian_vektor_penyakit[]" id="sembilan_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['skor_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['skor_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengendalian_vektor_penyakit[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['keterangan_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][2]['keterangan_pengendalian_vektor_penyakit']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengendalian_vektor_penyakit[]" readonly>Indeks jentik aedes aegypti buffer area (house index aedes ) kurang dari 0,01</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengendalian_vektor_penyakit[]" id="sembilan_d_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['nilai_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['nilai_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengendalian_vektor_penyakit[]" id="sembilan_d_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['skor_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['skor_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengendalian_vektor_penyakit[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['keterangan_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][3]['keterangan_pengendalian_vektor_penyakit']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pengendalian_vektor_penyakit[]" readonly>Seluruh ruangan bebas tikus</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pengendalian_vektor_penyakit[]" id="sembilan_e_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['nilai_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['nilai_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pengendalian_vektor_penyakit[]" id="sembilan_e_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['skor_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['skor_pengendalian_vektor_penyakit']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pengendalian_vektor_penyakit[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['keterangan_pengendalian_vektor_penyakit']) ? esc($getInspeksiHsbu['pengendalian_vektor_penyakit'][4]['keterangan_pengendalian_vektor_penyakit']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN SEPULUH -->
              <h5 class="text-primary">10. Instalasi</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="instalasi[]" readonly>Instalasi listrik, pemadam kebakaran, air bersih, air kotor, air limbah dapat menjamin keamanan</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_instalasi[]" id="sepuluh_a_nilai" placeholder="Nilai maks 5" value="<?= isset($getInspeksiHsbu['instalasi'][0]['nilai_instalasi']) ? esc($getInspeksiHsbu['instalasi'][0]['nilai_instalasi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_instalasi[]" id="sepuluh_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['instalasi'][0]['skor_instalasi']) ? esc($getInspeksiHsbu['instalasi'][0]['skor_instalasi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_instalasi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['instalasi'][0]['keterangan_instalasi']) ? esc($getInspeksiHsbu['instalasi'][0]['keterangan_instalasi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="instalasi[]" readonly>Bangunan kantor yang mempunyai tinggi >10 meter atau lebih tinggi dari bangunan sekitarnya dilengkapi penangkal petir</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_instalasi[]" id="sepuluh_b_nilai" placeholder="Nilai maks 5" value="<?= isset($getInspeksiHsbu['instalasi'][1]['nilai_instalasi']) ? esc($getInspeksiHsbu['instalasi'][1]['nilai_instalasi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_instalasi[]" id="sepuluh_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['instalasi'][1]['skor_instalasi']) ? esc($getInspeksiHsbu['instalasi'][1]['skor_instalasi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_instalasi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['instalasi'][1]['keterangan_instalasi']) ? esc($getInspeksiHsbu['instalasi'][1]['keterangan_instalasi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <!--------------------------------------------------------------- BAGIAN SEBELAS -->
              <h5 class="text-primary">11. Pemeliharaan jamban dan kamar mandi</h5>
              <br>
              <h6>Komponen yang dinilai:</h6>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pemeliharaan_jamban_dan_kamar_mandi[]" readonly>Jamban dan kamar mandi bersih dan tidak bau</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_a_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_a_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['skor_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['skor_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pemeliharaan_jamban_dan_kamar_mandi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][0]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pemeliharaan_jamban_dan_kamar_mandi[]" readonly>Jamban dan kamar mandi untuk karyawan pria terpisah dengan karyawan wanita</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_b_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_b_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['skor_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['skor_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pemeliharaan_jamban_dan_kamar_mandi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][1]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pemeliharaan_jamban_dan_kamar_mandi[]" readonly>Lantai jamban dan kamar mandi kedap air dan tidak licin</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_c_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_c_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['skor_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['skor_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pemeliharaan_jamban_dan_kamar_mandi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][2]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4"><textarea class="form-control custom-input" name="pemeliharaan_jamban_dan_kamar_mandi[]" readonly>Tersedia air bersih yang mencukupi</textarea></div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control" name="nilai_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_d_nilai" placeholder="Nilai maks 2" value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['nilai_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-2" style="margin-bottom: 5px;">
                    <input type="text" class="form-control skor-input" name="skor_pemeliharaan_jamban_dan_kamar_mandi[]" id="sebelas_d_skor" placeholder="Skor" readonly value="<?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['skor_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['skor_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?>">
                  </div>
                  <div class="col-sm-4">
                    <textarea class="form-control" rows="3" name="keterangan_pemeliharaan_jamban_dan_kamar_mandi[]" placeholder="Keterangan"><?= isset($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) ? esc($getInspeksiHsbu['pemeliharaan_jamban_dan_kamar_mandi'][3]['keterangan_pemeliharaan_jamban_dan_kamar_mandi']) : '' ?></textarea>
                  </div>
                </div>
              </div>

              <hr>

              <div class="form-group row">
                <label class="col-sm-2">Total</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="total" id="total" readonly value="<?= isset($getInspeksiHsbu) ? $getInspeksiHsbu['total'] : '' ?>">
                </div>
              </div>

              <div class="form-group row" id="keterangan" style="<?= isset($getInspeksiHsbu) ? '' : 'display: none;'; ?>">
                <label class="col-sm-2">Kategori</label>
                <div class="col-sm-10" id="keteranganBadge">
                  <!-- Tempat badge dan keterangan ditampilkan -->
                  <?php
                  if (isset($getInspeksiHsbu['total'])) {
                    $skor = $getInspeksiHsbu['total'];
                    if ($skor > 712.5) {
                      echo '<span class="badge bg-success">Rendah (R)</span>';
                    } elseif ($skor > 475 && $skor < 712.5) {
                      echo '<span class="badge bg-warning">Sedang (S)</span>';
                    } else {
                      echo '<span class="badge bg-danger">Tinggi (S)</span>';
                    }
                  }
                  ?>
                </div>
              </div>

              <!-- <div class="form-group row">
                <label class="col-sm-2">Hasil</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="hasil" id="hasil" readonly>
                </div>
              </div> -->

              <div class="form-group row">
                <label class="col-sm-2">Saran dan Rencana Tindakan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="saran_rencana_tindakan" id="saran_rencana_tindakan" rows="5" placeholder="Masukkan saran dan rencana tindakan ..." style="height: 63px;"><?= isset($getInspeksiHsbu) ? $getInspeksiHsbu['saran_rencana_tindakan'] : '' ?></textarea>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Catatan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="catatan" id="catatan" rows="5" placeholder="Masukkan catatan ..." style="height: 63px;"><?= isset($getInspeksiHsbu) ? $getInspeksiHsbu['catatan'] : '' ?></textarea>
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