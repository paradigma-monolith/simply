<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
      <div class="card">
          <div class="card-header">
              <?php if(session()->get('role') == 'Admin'): ?>
                <a href="/kendaraan_kantor/tambah_daftar" class="btn btn-sm bg-gradient-primary float-right" for="Edit Data"><i class="fas fa-plus"></i> Tambah Data</a>
              <?php endif; ?>
          </div>
          <div class="card-body">
              <table id="tableWithoutExporting" class="table table-sm table-bordered table-stripped">
                  <thead>
                      <tr class="text-center">
                          <th style="width: 25px;">No</th>
                          <th>Gambar</th>
                          <th>Nama Kendaraan</th>
                          <th>Plat Nomor</th>
                          <th style="width: 85px;">Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      $nomor = 1;
                      foreach($getData as $data): ?>
                      <tr>
                          <td class="text-center"><?= $nomor++; ?></td>
                          <td class="text-center"><img src="/dist/img/boxed-bg.jpg" data-src="/dist/img/<?= $data['gambar']; ?>" class="card-img-top" alt="Produk 1" style="width: 150px;"></td>
                          <td><?= $data['nama_kendaraan']; ?></td>
                          <td><?= $data['plat_nomor']; ?></td>
                          <td class='text-center'>
                              <a href="/kendaraan_kantor/update_daftar/<?= $data['id_kendaraan_kantor_daftar']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                              <?php if(session()->get('role') == 'Admin'): ?>
                                <form action="/kendaraan_kantor/dlt_daftar/<?= $data['id_kendaraan_kantor_daftar']; ?>" method="post" class="d-inline formHapusData">
                                  <?= csrf_field(); ?>
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                              <?php else: ?>
                              <?php endif; ?>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</section>
<!-- /.content -->
<script>
    $(document).ready(function() {
        const lazyImages = $('img[data-src]');
    
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        $(img).attr('src', $(img).data('src'));
                        $(img).removeAttr('data-src');
                        observer.unobserve(img);
                    }
                });
            });
    
            lazyImages.each(function() {
                observer.observe(this);
            });
        } else {
            lazyImages.each(function() {
                $(this).attr('src', $(this).data('src'));
                $(this).removeAttr('data-src');
            });
        }
    });
</script>
<?= $this->endSection(); ?>