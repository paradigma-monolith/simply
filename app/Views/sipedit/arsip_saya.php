<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form action="/sipedit/export" class="d-inline" method="post">
            <div class="card-body">
              <input type="hidden" name="sipeditAdministrator" id="sipeditAdministrator" value="0">
              <div class="form-group">
                <label class="has-float-label">
                  <div class="input-group">
                    <input type="text" name="tanggal" id="sipeditSort" class="form-control" disabled>
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                  </div>
                  <span>Tanggal Arsip</span>
                </label>
              </div>
            </div>
            <div class="card-footer">
                <?php if ($jpl_per_tahun['arsip_jpl'] < 20 && $jpl_per_tahun['arsip_jpl'] > 0) { ?>
                  <span class="text-danger">Anda Belum Memenuhi Minimal 20 JPL Tahun <?= date("Y"); ?></span>
                <?php } elseif ($jpl_per_tahun['arsip_jpl'] >= 20) { ?>
                  <span class="text-success">Anda Sudah Memenuhi Minimal 20 JPL Tahun <?= date("Y"); ?></span>
                <?php } else { ?>
                <span class="text-secondary">Belum ada data untuk tahun <?= date("Y"); ?></span>
                <?php } ?>
              <div class="float-right">
                <button type="submit" class="btn btn-success" id="sipeditSortButton" disabled><i class="fas fa-file-excel"></i> Export ke Excel</button>
              </div>
            </div>
          </form>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="text-right">
              <a href="/sipedit/unggah_pelatihan" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <div class="card-body">
            <?php if (session()->get('role') == 'Admin') : ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sipeditCheckAll">
                <label class="form-check-label" for="sipeditCheckAll">
                  Tampilkan semua data
                </label>
              </div>
            <?php else : ?>
              <!-- <div class="form-check">
                <input class="form-check-input" type="checkbox" disabled>
                <label class="form-check-label">
                  Tampilkan semua data
                </label>
              </div> -->
            <?php endif; ?>
            <hr>
            <div class="view_data_arsip">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>