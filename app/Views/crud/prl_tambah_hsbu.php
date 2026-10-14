<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormHsbu" action="/prl/simpan_hsbu<?= isset($getHsbu['id_prl_hsbu']) ? '/' . $getHsbu['id_prl_hsbu'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getHsbu['id_prl_hsbu']) ? "Ubah Data Bangunan Umun" : 'Tambah Data Bangunan Umun'; ?></h3>
            </div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_form" id="wilayah_kerja_form" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getHsbu['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getHsbu['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
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
                <label class="col-sm-2">Nama Gedung/Bangunan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama_hsbu_form" id="nama_hsbu_form" value="<?= isset($getHsbu['nama_gedung']) ? $getHsbu['nama_gedung'] : old("nama_hsbu_form"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pemilik/Penanggung Jawab</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penanggung_jawab_form" id="penanggung_jawab_form" value="<?= isset($getHsbu['penanggung_jawab']) ? $getHsbu['penanggung_jawab'] : old("penanggung_jawab_form"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah Karyawan</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="jml_karyawan_form" id="jml_karyawan_form" style="width:100%;">
                    <option value="">-- Pilih Jumlah Karyawan --</option>
                    <?php if (isset($getHsbu['jml_karyawan'])) : ?>
                      <option value="1-2 Pekerja" <?= $getHsbu['jml_karyawan'] == '1-2 Pekerja' ? 'selected="true"' : '' ?>>1-2 Pekerja</option>
                      <option value="3-19 Pekerja" <?= $getHsbu['jml_karyawan'] == '3-19 Pekerja' ? 'selected="true"' : '' ?>>3-19 Pekerja</option>
                      <option value="20-100 Pekerja" <?= $getHsbu['jml_karyawan'] == '20-100 Pekerja' ? 'selected="true"' : '' ?>>20-100 Pekerja</option>
                      <option value=">100 Pekerja" <?= $getHsbu['jml_karyawan'] == '>100 Pekerja' ? 'selected="true"' : '' ?>>>100 Pekerja</option>
                    <?php else : ?>
                      <option value="1-2 Pekerja">1-2 Pekerja</option>
                      <option value="3-19 Pekerja">3-19 Pekerja</option>
                      <option value="20-100 Pekerja">20-100 Pekerja</option>
                      <option value=">100 Pekerja">>100 Pekerja</option>
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