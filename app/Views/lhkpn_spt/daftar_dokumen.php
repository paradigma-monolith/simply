<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <div class="card card-default">

          <div class="card-header">
            <div class="text-right">
              <a href="/lhkpn_spt/unggah_dokumen" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Unggah Dokumen</a>
              <?php if (session()->get('role') == 'Admin') : ?>
                <!-- <a href="/lhkpn_spt/unduh_dokumen" class="btn bg-gradient-warning"><i class="fas fa-download"></i> Unduh Dokumen</a> -->
                <form action="/lhkpn_spt/export" class="d-inline" method="post">
                  <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export ke Excel</button>
                </form>
              <?php else : ?>
                <!-- <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export ke Excel</button> -->
              <?php endif; ?>
            </div>
          </div>

          <div class="card-body">
            <div class="dokumen_lhkpn_spt"></div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>

<?= $this->endSection(); ?>