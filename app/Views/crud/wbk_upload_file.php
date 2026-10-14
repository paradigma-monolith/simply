<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="wbkSimpanFileWbk" action="/wbk/simpan_file<?= isset($getArsip) ? '/' . $getArsip['id_wbk_arsip'] : '' ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getArsip['id_wbk_arsip']) ? "Ubah Dokumen" : 'Unggah Dokumen'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <select class="form-control select2" name="pegawai" id="pegawai" style="width:100%;" readonly>
                        <option value="">-- Pilih Data Pegawai --</option>
                        <?php if (isset($getArsip['id_wbk_arsip'])) : ?>
                          <?php foreach ($anggota as $data) : ?>
                            <option value="<?= $data['id_data_pegawai']; ?>" <?= $data['nama_pegawai'] == $getArsip['nama_pegawai'] ? 'selected="true"' : '' ?>> <?= $data['nama_pegawai']; ?> </option>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <?php foreach ($anggota as $data) : ?>
                            <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($by) ? ($data['nama_pegawai'] == $by['nama'] ? 'selected="true"' : '') : '' ?>> <?= $data['nama_pegawai']; ?> </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                    <!-- <div class="col-2">
                      <div class="form-check text-center">
                        <input class="form-check-input" type="checkbox" id="selectPegawai" <?= session()->get('role') == "Admin" ? '' : 'disabled'; ?>>
                        <label class="form-check-label" for="selectPegawai">
                          Administrator
                        </label>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pokja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="pokja" id="pokja" style="width:100%;" readonly>
                    <option value="">-- Pilih Pokja --</option>
                    <?php if (isset($getArsip['id_wbk_arsip'])) : ?>
                      <?php foreach ($pokja as $data) : ?>
                        <option value="<?= $data['id_wbk_pokja']; ?>" <?= $data['id_wbk_pokja'] == $getArsip['id_wbk_pokja'] ? 'selected="true"' : '' ?>> <?= $data['nama_pokja']; ?> </option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($pokja as $data) : ?>
                        <option value="<?= $data['id_wbk_pokja']; ?>" <?= isset($getAnggota) ? ($data['id_wbk_pokja'] == $getAnggota['id_wbk_pokja'] ? 'selected="true"' : '') : '' ?>> <?= $data['nama_pokja']; ?> </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <?php if (isset($getArsip)) : ?>
                <?php if ($getArsip['id_wbk_rencana_kerja'] != '0') : ?>
                  <div class="form-group row">
                    <label class="col-sm-2">Kegiatan Rencana Kerja</label>
                    <div class="col-sm-10">
                      <input style="background-color: #ffffff;" onfocus="this.blur()" type="text" name="kegiatan" class="form-control" id="kegiatan" value="<?= $getArsip['kegiatan']; ?>">
                      <input type="hidden" name="id_wbk_rencana_kerja" value="<?= $getArsip['id_wbk_rencana_kerja']; ?>">
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
              <?php if (isset($getRencanaKerja['id_wbk_rencana_kerja'])) : ?>
                <div class="form-group row">
                  <label class="col-sm-2">Kegiatan Rencana Kerja</label>
                  <div class="col-sm-10">
                    <input style="background-color: #ffffff;" onfocus="this.blur()" type="text" name="kegiatan" class="form-control" id="kegiatan" value="<?= $getRencanaKerja['kegiatan']; ?>">
                    <input type="hidden" name="id_wbk_rencana_kerja" value="<?= $getRencanaKerja['id_wbk_rencana_kerja']; ?>">
                  </div>
                </div>
              <?php endif; ?>

              <div class="form-group row">
                <label class="col-sm-2">Nama File</label>
                <div class="col-sm-10">
                  <input type="text" name="nama_file" onfocus="<?= isset($getArsip['nama_file']) ? 'this.blur()' : '' ?>" class="form-control" id="nama_file" value="<?= isset($getArsip['nama_file']) ? $getArsip['nama_file'] : old("nama_file"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Dokumen</label>
                <div class="col-sm-10">
                  <?php if (isset($getArsip)) : ?>
                    <p class="text-danger">Aktifkan switch berikut jika akan mengubah file. Abaikan jika tidak akan mengubah file.</p>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-1">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input inputFile" for="unggah_dokumen" id="customSwitch1" <?= isset($getArsip) ? '' : 'checked disabled'; ?>>
                        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                      </div>
                    </div>
                    <div class="col-sm-11">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="unggah_dokumen" id="unggah_dokumen" onclick="handleInputFileChange(this)">
                        <label class="custom-file-label" for="unggah_dokumen">Pilih File PDF</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if (isset($getRencanaKerja['id_wbk_rencana_kerja'])) : ?>
                <div class="form-group row">
                  <label class="col-sm-2">Target Waktu</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" name="target_waktu" id="target_waktu" data-placeholder="Pilih target waktu" style="width: 100%;">
                      <option value=""></option>
                      <?php foreach ($months as $monthNumber => $monthName) : ?>
                        <?php if (in_array($monthNumber, $selectedMonths)) : ?>
                          <option value="<?= $monthNumber; ?>" <?= in_array($monthNumber, $selectedMonths); ?>><?= $monthName; ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                    <span id="warningLabel" class="error invalid-feedback">Target waktu tidak sama dengan bulan saat ini, mohon berikan alasan dengan mengisi kolom catatan di bawah.</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Catatan (opsional)</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="catatan" id="catatan" rows="5"></textarea>
                  </div>
                </div>
              <?php endif; ?>

              <hr>
              <label>Pilih Folder Penyimpanan Dokumen</label>
              <br><br>

              <div class="form-group row">
                <label class="col-sm-2">Kategori</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <select class="form-control select2" name="kategori" id="kategori" style="width:100%;">
                        <option value="">-- Pilih Kategori Dokumen --</option>
                        <?php
                        $groupedCategories = []; // Array untuk menyimpan kategori dikelompokkan
                        foreach ($kategori as $data) {
                          $group = ($data['jenis'] == 'Pemenuhan') ? 'Pemenuhan' : 'Reform';
                          $groupedCategories[$group][] = $data;
                        }
                        foreach ($groupedCategories as $groupName => $groupCategories) {
                          echo '<optgroup label="' . $groupName . '">';
                          foreach ($groupCategories as $data) {
                            echo '<option value="' . $data['id_wbk_kategori'] . '">' . $data['no'] . '. ' . $data['keterangan'] . '</option>';
                          }
                          echo '</optgroup>';
                        }
                        ?>
                      </select>
                    </div>
                    <!-- <div class="col-1">
                      <div class="form-check text-center">
                        <a class="btn btn-xs bg-gradient-primary" data-toggle="modal" data-target="#modal-tambahWbkKategori" data-toggle="tooltip" data-placement="bottom" title="Tambah Kategori" for="Tambah Kategori"><i class="fas fa-plus"></i></a>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Sub Kategori</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <select class="form-control select2" name="sub_kategori" id="sub_kategori" style="width:100%;">
                        <option value="">-- Pilih Sub Kategori Dokumen --</option>
                      </select>
                    </div>
                  </div>
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