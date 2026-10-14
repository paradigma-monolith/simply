<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="wbkSimpanRencanaKerja" action="/wbk/simpan_rencana_kerja<?= isset($getRencanaKerja['id_wbk_rencana_kerja']) ? '/' . $getRencanaKerja['id_wbk_rencana_kerja'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getRencanaKerja['id_wbk_rencana_kerja']) ? 'Tambah Unit Rencana Kerja' : 'Tambah Rencana Kerja'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <select class="form-control select2" name="pegawai" id="pegawai" style="width:100%;" readonly>
                        <option value="">-- Pilih Data Pegawai --</option>
                        <?php foreach ($anggota as $data) : ?>
                          <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($by) ? ($data['nama_pegawai'] == $by['nama'] ? 'selected="true"' : '') : '' ?>> <?= $data['nama_pegawai']; ?> </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pokja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="pokja" id="pokja" style="width:100%;" readonly>
                    <option value="">-- Pilih Pokja --</option>
                    <?php foreach ($pokja as $data) : ?>
                      <option value="<?= $data['id_wbk_pokja']; ?>" <?= isset($getAnggota) ? ($data['id_wbk_pokja'] == $getAnggota['id_wbk_pokja'] ? 'selected="true"' : '') : '' ?>> <?= $data['nama_pokja']; ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Poin</label>
                <div class="col-sm-2">
                  <?php if (isset($getRencanaKerja['poin1'])) : ?>
                    <input type="text" name="poin1" class="form-control" id="poin1" onfocus="this.blur()" value="<?= $getRencanaKerja['poin1']; ?>">
                  <?php else : ?>
                    <input type="text" name="poin1" class="form-control" id="poin1" onfocus="this.blur()" value="<?= isset($lastPoinRencanaKerja) ? $nextPoinRencanaKerja[$getAnggota['id_wbk_pokja']] : '1'; ?>">
                  <?php endif; ?>
                </div>
              </div>

              <?php if (!isset($getRencanaKerja['id_wbk_rencana_kerja'])) { ?>
                <div class="form-group row">
                  <label class="col-sm-2">Kegiatan</label>
                  <div class="col-sm-10">
                    <input type="text" name="kegiatan" class="form-control" id="kegiatan" value="<?= isset($getRencanaKerja['kegiatan']) ? '' : old("kegiatan"); ?>">
                  </div>
                </div>
              <?php } ?>

              <?php if (isset($getRencanaKerja['id_wbk_rencana_kerja'])) { ?>
                <div class="form-group row">
                  <label class="col-sm-2">Sub Poin</label>
                  <div class="col-sm-2">
                    <input type="text" name="poin2" class="form-control" id="poin2" onfocus="this.blur()" value="<?= isset($lastPoinUnit) ? $nextPoinUnit[$getRencanaKerja['poin1']] : 'a'; ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Kegiatan</label>
                  <div class="col-sm-10">
                    <input type="text" name="kegiatan" class="form-control" id="kegiatan" value="<?= isset($getRencanaKerja['kegiatan']) ? '' : old("kegiatan"); ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Indikator</label>
                  <div class="col-sm-10">
                    <input type="text" name="indikator" class="form-control" id="indikator" value="<?= isset($getRencanaKerja['indikator']) ? '' : old("indikator"); ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Bukti Fisik</label>
                  <div class="col-sm-10">
                    <input type="text" name="bukti_fisik" class="form-control" id="bukti_fisik" value="<?= isset($getRencanaKerja['bukti_fisik']) ? '' : old("bukti_fisik"); ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Target Waktu</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" name="target_waktu[]" multiple="multiple" data-placeholder="Pilih target waktu" style="width: 100%;">
                      <option value="1|2|3|4|5|6|7|8|9|10|11|12">Pilih Semua Bulan</option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2">Prioritas</label>
                  <div class="col-sm-10">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" name="prioritas" id="prioritas">
                      <label class="custom-control-label" for="prioritas">Tetapkan sebagai kegiatan prioritas</label>
                    </div>
                  </div>
                </div>
              <?php } else { ?>

              <?php } ?>
            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success simpan-rencana-kerja"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>