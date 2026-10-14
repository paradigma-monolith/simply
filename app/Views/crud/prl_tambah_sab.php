<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="prlSimpanFormSab" action="/prl/simpan_sab<?= isset($getSab['id_prl_sab']) ? '/' . $getSab['id_prl_sab'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getSab['id_prl_sab']) ? "Ubah Data SAB (Sarana Air Bersih)" : 'Tambah Data SAB (Sarana Air Bersih)'; ?></h3>
            </div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja_form" id="wilayah_kerja_form" style="width:100%;" readonly>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php if (isset($getSab['id_wilayah_kerja'])) : ?>
                      <?php foreach ($wilayahKerja as $dataWilker) : ?>
                        <option value="<?= $dataWilker['id_unit_kerja']; ?>" <?= $dataWilker['id_unit_kerja'] == $getSab['id_wilayah_kerja'] ? 'selected="true"' : '' ?>><?= $dataWilker['unit_kerja']; ?></option>
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
                <label class="col-sm-2">Nama SAB</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama_sab_form" id="nama_sab_form" value="<?= isset($getSab['nama_sab']) ? $getSab['nama_sab'] : old("nama_sab_form"); ?>">
                </div>
              </div>

              <label>Pemilik/Penanggung Jawab</label>
              <div class="form-group row">
                <label class="col-sm-2">Nama</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama_pj_form" id="nama_pj_form" value="<?= isset($getSab['penanggung_jawab']) ? $getSab['penanggung_jawab'] : old("nama_pj_sab_form"); ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2">No. HP</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nomor_hp_pj_form" id="nomor_hp_pj_form" value="<?= isset($getSab['penanggung_jawab_no_hp']) ? $getSab['penanggung_jawab_no_hp'] : old("nomor_hp_pj_form"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Sumber Air</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="sumber_air_form" id="sumber_air_form" style="width:100%;">
                    <option value="">-- Pilih Sumber Air --</option>
                    <?php if (isset($getSab['sumber_air'])) : ?>
                      <option value="Mata Air" <?= $getSab['sumber_air'] == 'Mata Air' ? 'selected="true"' : '' ?>>Mata Air</option>
                      <option value="Air Tanah" <?= $getSab['sumber_air'] == 'Air Tanah' ? 'selected="true"' : '' ?>>Air Tanah</option>
                      <option value="Air Permukaan" <?= $getSab['sumber_air'] == 'Air Permukaan' ? 'selected="true"' : '' ?>>Air Permukaan</option>
                      <option value="Air Hujan" <?= $getSab['sumber_air'] == 'Air Hujan' ? 'selected="true"' : '' ?>>Air Hujan</option>
                    <?php else : ?>
                      <option value="Mata Air">Mata Air</option>
                      <option value="Air Tanah">Air Tanah</option>
                      <option value="Air Permukaan">Air Permukaan</option>
                      <option value="Air Hujan">Air Hujan</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kapasitas Penampungan</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="kapasitas_penampungan_form" id="kapasitas_penampungan_form" placeholder="Satuan kapasitas dalam L" value="<?= isset($getSab['kapasitas_penampungan']) ? $getSab['kapasitas_penampungan'] : old("kapasitas_penampungan_form"); ?>">
                  <!-- <select class="form-control select2" name="kapasitas_produksi" id="kapasitas_produksi" style="width:100%;">
                    <option value="">-- Pilih Kapasitas Produksi --</option>
                    <option value="<100 kg/bulan atau <400 porsi/bulan">&lt;100 kg/bulan atau &lt;400 porsi/bulan</option>
                    <option value="100-200 kg/bulan atau 400-800 porsi/bulan">100-200 kg/bulan atau 400-800 porsi/bulan</option>
                    <option value="200-500 kg/bulan atau 801-1000 porsi/bulan">200-500 kg/bulan atau 801-1000 porsi/bulan</option>
                    <option value=">500 kg/bulan atau >1000 porsi/bulan">&gt;500 kg/bulan atau &gt;1000 porsi/bulan</option>
                  </select> -->
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