<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambahkan Penyematan Link Disini</h3>
          </div>
          <div class="card-body">
            <form method="post" action="/iframe/setLink">
              <input type="hidden" class="form-control" name="id_iframe" readonly value="">
              <div class="form-group">
                <label>Path Segment</label>
                <input type="text" class="form-control" name="segment" required readonly value="<?= generateRandomText(); ?>">
              </div>
              <div class="form-group">
                <label>Nama Menu</label>
                <input value="" type="text" class="form-control" name="sidebar" required placeholder="Usahakan tidak terlalu panjang">
              </div>
              <div class="form-group">
                <label>Title link</label>
                <input value="" type="text" class="form-control" name="title" required>
              </div>
              <div class="form-group">
                <label>link</label>
                <input value="" type="url" class="form-control" name="link" required>
              </div>
              <button type="submit" class="btn btn-success float-right"><i class="fas fa-save mr-1"></i> Simpan</button>
              <button type="button" class="btn btn-secondary float-right iframe-button-batal mx-2"><i class="fas fa-redo-alt mr-1"></i></i> Bersihkan</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">List Daftar Link</h3>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Menu</th>
                  <th>Link</th>
                  <th>Title link</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($getData as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['sidebar']; ?></td>
                    <td><?= $data['link']; ?></td>
                    <td>Konten Tersematkan | <?= $data['title']; ?></td>
                    <td>

                      <button class="btn btn-warning btn-sm iframe-edit-button"
                        data-id="<?= $data['id_iframe']; ?>"
                        data-segment="<?= $data['segment']; ?>"
                        data-menu="<?= $data['sidebar']; ?>"
                        data-title="<?= $data['title']; ?>"
                        data-link="<?= $data['link']; ?>">
                        <i class="fas fa-pencil-alt"> </i>
                      </button>
                      <form action="/iframe/delete/<?= $data['id_iframe']; ?>" method="post" class="d-inline formHapusData">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i> </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    // Tombol Edit ditekan
    $('.iframe-edit-button').on('click', function() {
      const id = $(this).data('id');
      const segment = $(this).data('segment');
      const menu = $(this).data('menu');
      const title = $(this).data('title');
      const link = $(this).data('link');

      // Isi form dengan data dari tombol
      $('input[name="id_iframe"]').val(id);
      $('input[name="segment"]').val(segment);
      $('input[name="sidebar"]').val(menu);
      $('input[name="title"]').val(title);
      $('input[name="link"]').val(link);
    });

    // Tombol Batal ditekan
    $('.iframe-button-batal').on('click', function() {
      // Kosongkan semua input kecuali segment
      $('input[name="id_iframe"]').val('');
      $('input[name="sidebar"]').val('');
      $('input[name="title"]').val('');
      $('input[name="link"]').val('');

      // Reset segment dengan nilai baru dari PHP
      <?php $newSegment = generateRandomText(); ?>
      $('input[name="segment"]').val("<?= $newSegment; ?>");
    });
  });
</script>
<?= $this->endSection(); ?>