<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormTpp" action="/prl/simpan_tpp<?= isset($getTpp['id_prl_tpp']) ? '/' . $getTpp['id_prl_tpp'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getTpp['id_prl_tpp']) ? "Ubah Data TPP (Tempat Pengelolaan Pangan)" : 'Tambah Data TPP (Tempat Pengelolaan Pangan)'; ?></h3>
            </div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_form" id="wilayah_kerja_form" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getTpp['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getTpp['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
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
                <label class="col-sm-2">Nama TPP</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama_tpp_form" id="nama_tpp_form" value="<?= isset($getTpp['nama_tpp']) ? $getTpp['nama_tpp'] : old("nama_tpp_form"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Pemilik/Penanggung Jawab</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penanggung_jawab_form" id="penanggung_jawab_form" value="<?= isset($getTpp['penanggung_jawab']) ? $getTpp['penanggung_jawab'] : old("penanggung_jawab_form"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah Karyawan</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="jml_karyawan_form" id="jml_karyawan_form" style="width:100%;">
                    <option value="">-- Pilih Jumlah Karyawan --</option>
                    <?php if (isset($getTpp['jml_karyawan'])) : ?>
                      <option value="1-2 Pekerja" <?= $getTpp['jml_karyawan'] == '1-2 Pekerja' ? 'selected="true"' : '' ?>>1-2 Pekerja</option>
                      <option value="3-19 Pekerja" <?= $getTpp['jml_karyawan'] == '3-19 Pekerja' ? 'selected="true"' : '' ?>>3-19 Pekerja</option>
                      <option value="20-100 Pekerja" <?= $getTpp['jml_karyawan'] == '20-100 Pekerja' ? 'selected="true"' : '' ?>>20-100 Pekerja</option>
                      <option value=">100 Pekerja" <?= $getTpp['jml_karyawan'] == '>100 Pekerja' ? 'selected="true"' : '' ?>>>100 Pekerja</option>
                    <?php else : ?>
                      <option value="1-2 Pekerja">1-2 Pekerja</option>
                      <option value="3-19 Pekerja">3-19 Pekerja</option>
                      <option value="20-100 Pekerja">20-100 Pekerja</option>
                      <option value=">100 Pekerja">>100 Pekerja</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kapasitas Produksi</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="kapasitas_produksi_form" id="kapasitas_produksi_form" style="width:100%;">
                    <option value="">-- Pilih Kapasitas Produksi --</option>
                    <?php if (isset($getTpp['kapasitas_produksi'])) : ?>
                      <option value="<100 kg/bulan atau <400 porsi/bulan" <?= $getTpp['kapasitas_produksi'] == '<100 kg/bulan atau <400 porsi/bulan' ? 'selected="true"' : '' ?>>&lt;100 kg/bulan atau &lt;400 porsi/bulan</option>
                      <option value="100-200 kg/bulan atau 400-800 porsi/bulan" <?= $getTpp['kapasitas_produksi'] == '100-200 kg/bulan atau 400-800 porsi/bulan' ? 'selected="true"' : '' ?>>100-200 kg/bulan atau 400-800 porsi/bulan</option>
                      <option value="200-500 kg/bulan atau 801-1000 porsi/bulan" <?= $getTpp['kapasitas_produksi'] == '200-500 kg/bulan atau 801-1000 porsi/bulan' ? 'selected="true"' : '' ?>>200-500 kg/bulan atau 801-1000 porsi/bulan</option>
                      <option value=">500 kg/bulan atau >1000 porsi/bulan" <?= $getTpp['kapasitas_produksi'] == '>500 kg/bulan atau >1000 porsi/bulan' ? 'selected="true"' : '' ?>>&gt;500 kg/bulan atau &gt;1000 porsi/bulan</option>
                    <?php else : ?>
                      <option value="<100 kg/bulan atau <400 porsi/bulan">&lt;100 kg/bulan atau &lt;400 porsi/bulan</option>
                      <option value="100-200 kg/bulan atau 400-800 porsi/bulan">100-200 kg/bulan atau 400-800 porsi/bulan</option>
                      <option value="200-500 kg/bulan atau 801-1000 porsi/bulan">200-500 kg/bulan atau 801-1000 porsi/bulan</option>
                      <option value=">500 kg/bulan atau >1000 porsi/bulan">&gt;500 kg/bulan atau &gt;1000 porsi/bulan</option>
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