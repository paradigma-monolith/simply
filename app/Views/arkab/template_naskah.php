<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <?php if (session()->get('role') == 'Admin') : ?>
            <div class="card-header">
              <div class="text-right">
                <a href="/tata_naskah_dinas/template_naskah/tambah_naskah" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Naskah</a>
              </div>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <div class="template_naskah_list">
                <div class="text-center">
                  <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>