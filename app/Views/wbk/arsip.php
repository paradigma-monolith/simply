<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <label>Pilih tahun:</label>
        <select class="form-control select2" name="tahunArsip" id="tahunArsip" style="width:100%;">
          <option value="<?= $currentYear; ?>"><?= $currentYear; ?></option>
          <?php foreach ($tahunData as $tahun) : ?>
            <option value="<?= $tahun['tahun']; ?>"><?= $tahun['tahun']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-12">
        <div class="text-right">
          <?php if ($getRole == 'Admin') : ?>
            <a href="/wbk/upload_file" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Upload File</a>
          <?php elseif ($akses['keterangan'] == 'Koordinator' || $akses['keterangan'] == 'Anggota1') : ?>
            <a href="/wbk/upload_file" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Upload File</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<br>

<section class="content">
  <div class="arsip"></div>
</section>

<?= $this->endSection(); ?>