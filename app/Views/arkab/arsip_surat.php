<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <?php if ($by != null) : ?>
            <div class="card-header">
              <div class="text-right">
                <a href="/arkab/tambah_surat" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
              </div>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <?php if (session()->get('role') == 'Admin') : ?>
              <div class="form-check">
                <?php if ($by != null) : ?>
                  <input class="form-check-input" type="checkbox" id="arkabCheckAll">
                  <label class="form-check-label" for="arkabCheckAll">
                    Tampilkan semua data
                  </label>
                <?php endif; ?>
              </div>
              <hr>
            <?php endif; ?>
            <div class="arkab_arsip_surat"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>