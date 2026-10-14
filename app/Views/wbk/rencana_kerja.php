<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="text-right">
              <?php if ($getRole == 'Admin') : ?>
              <?php elseif ($akses['keterangan'] == 'Koordinator' || $akses['keterangan'] == 'Anggota1') : ?>
                <a href="/wbk/tambah_rencana_kerja" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Rencana Kerja</a>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-body">
            <form action="/wbk/export_rencana_kerja" method="post">
              <label>Pilih tahun rencana kerja:</label>
              <select class="form-control select2" name="tahunRencanaKerja" id="tahunRencanaKerja" style="width:100%;">
                <option value="<?= $currentYear; ?>"><?= $currentYear; ?></option>
                <?php foreach ($tahunData as $tahun) : ?>
                  <option value="<?= $tahun['tahun']; ?>"><?= $tahun['tahun']; ?></option>
                <?php endforeach; ?>
              </select>
              <br>
              <div class="text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Export ke Excel</button>
              </div>
            </form>
            <hr>
            <div class="view_rencana_kerja"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>