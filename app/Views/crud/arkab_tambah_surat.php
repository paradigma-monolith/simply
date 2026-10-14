<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="arkabSimpanSurat" action="/arkab/simpan_surat<?= isset($getArsipSurat['id_arkab_surat']) ? '/' . $getArsipSurat['id_arkab_surat'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getArsipSurat['id_arkab_surat']) ? 'Ubah Arsip Surat' : 'Tambah Arsip Surat'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-10">
                      <select class="form-control select2" name="pegawai" id="pegawai" style="width:100%;" readonly>
                        <option value="">-- Pilih Data Pegawai --</option>
                        <?php if (isset($getArsipSurat['nama'])) : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= $data_pegawai['nip'] == $getArsipSurat['nip'] ? 'selected="true"' : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($by) ? ($data_pegawai['nama'] == $by['nama'] ? 'selected="true"' : '') : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <div class="form-check text-center">
                        <input class="form-check-input" type="checkbox" id="selectPegawai" <?= session()->get('role') == "Admin" ? '' : 'disabled'; ?>>
                        <label class="form-check-label" for="selectPegawai">
                          Administrator
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Substansi</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="substansi" id="substansi" style="width:100%;" readonly>
                    <option value="">-- Pilih Data Substansi --</option>
                    <?php if (isset($getArsipSurat['id_substansi'])) : ?>
                      <?php foreach ($substansi as $data_substansi) : ?>
                        <option value="<?= $data_substansi['id_substansi']; ?>" <?= $data_substansi['id_substansi'] == $getArsipSurat['id_substansi'] ? 'selected="true"' : '' ?>> <?= $data_substansi['nama_substansi']; ?> | <?= $data_substansi['keterangan_substansi']; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($substansi as $data_substansi) : ?>
                        <option value="<?= $data_substansi['id_substansi']; ?>" <?= isset($getSubstansi) ? ($data_substansi['id_substansi'] == $getSubstansi['id_substansi'] ? 'selected="true"' : '') : '' ?>> <?= $data_substansi['nama_substansi']; ?> | <?= $data_substansi['keterangan_substansi']; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nomor Surat</label>
                <div class="col-sm-10">
                  <input type="text" name="nomor_surat" class="form-control" id="nomor_surat" value="<?= isset($getArsipSurat['nomor_surat']) ? $getArsipSurat['nomor_surat'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Dokumen</label>
                <div class="col-sm-10">
                  <?php if (isset($getArsipSurat)) : ?>
                    <p class="text-danger">Aktifkan switch berikut jika akan mengubah file. Abaikan jika tidak akan mengubah file.</p>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-1">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input inputFile" for="unggah_dokumen" id="customSwitch1" <?= isset($getArsipSurat) ? '' : 'checked disabled'; ?>>
                        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                      </div>
                    </div>
                    <div class="col-sm-11">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="unggah_dokumen" id="unggah_dokumen">
                        <label class="custom-file-label" for="unggah_dokumen">Pilih File PDF</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Surat</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="jenis_surat" id="jenis_surat" style="width:100%;" readonly>
                    <option value="">-- Pilih Jenis Surat --</option>
                    <?php if (isset($getArsipSurat['id_jenis_surat'])) : ?>
                      <?php foreach ($jenisSurat as $dataJenisSurat) : ?>
                        <option value="<?= $dataJenisSurat['id_arkab_jenis_surat']; ?>" <?= $dataJenisSurat['id_arkab_jenis_surat'] == $getArsipSurat['id_jenis_surat'] ? 'selected="true"' : '' ?>> <?= $dataJenisSurat['jenis_surat']; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($jenisSurat as $dataJenisSurat) : ?>
                        <option value="<?= $dataJenisSurat['id_arkab_jenis_surat']; ?>"><?= $dataJenisSurat['jenis_surat']; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kode Klasifikasi</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="kode_klasifikasi" id="kode_klasifikasi" style="width:100%;" readonly>
                    <option value="">-- Pilih Kode Klasifikasi --</option>
                    <?php if (isset($getArsipSurat['id_kode_klasifikasi'])) : ?>
                      <?php foreach ($kodeKlasifikasi as $dataKodeKlasifikasi) : ?>
                        <option value="<?= $dataKodeKlasifikasi['id_arkab_kode_klasifikasi']; ?>" <?= $dataKodeKlasifikasi['id_arkab_kode_klasifikasi'] == $getArsipSurat['id_kode_klasifikasi'] ? 'selected="true"' : '' ?>><?= $dataKodeKlasifikasi['kode']; ?> <?= $dataKodeKlasifikasi['keterangan']; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($kodeKlasifikasi as $dataKodeKlasifikasi) : ?>
                        <option value="<?= $dataKodeKlasifikasi['id_arkab_kode_klasifikasi']; ?>"><?= $dataKodeKlasifikasi['kode']; ?> <?= $dataKodeKlasifikasi['keterangan']; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
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