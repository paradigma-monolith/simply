<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambahkan Portal Link Disini</h3>
          </div>
          <div class="card-body">
            <form method="post" action="/portal_link/setLink">
              <input type="hidden" class="form-control" name="id_portal" readonly value="">
              <div class="form-group">
                <label>Title link</label>
                <input value="" type="text" class="form-control" name="title" required placeholder="Usahakan tidak terlalu panjang">
              </div>
              <div class="form-group">
                <label>Link / URL</label>
                <input value="" type="url" class="form-control" name="link" required placeholder="https://example.com">
              </div>
              <button type="submit" class="btn btn-success float-right"><i class="fas fa-save mr-1"></i> Simpan</button>
              <button type="button" class="btn btn-secondary float-right portal-link-button-batal mx-2"><i class="fas fa-redo-alt mr-1"></i> Bersihkan</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Portal Link</h3>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Title link</th>
                  <th>Link</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($getData as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['title']; ?></td>
                    <td><a href="<?= $data['link']; ?>" target="_blank"><?= $data['link']; ?></a></td>
                    <td>
                      <button class="btn btn-warning btn-sm portal-link-edit-button"
                        data-id="<?= $data['id_portal']; ?>"
                        data-title="<?= $data['title']; ?>"
                        data-link="<?= $data['link']; ?>">
                        <i class="fas fa-pencil-alt"> </i>
                      </button>
                      <form action="/portal_link/delete/<?= $data['id_portal']; ?>" method="post" class="d-inline formHapusData">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus Data"><i class="fas fa-trash-alt"></i> </button>
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
    $('.portal-link-edit-button').on('click', function() {
      const id = $(this).data('id');
      const title = $(this).data('title');
      const link = $(this).data('link');

      // Isi form dengan data dari tombol
      $('input[name="id_portal"]').val(id);
      $('input[name="title"]').val(title);
      $('input[name="link"]').val(link);
    });

    // Tombol Batal ditekan
    $('.portal-link-button-batal').on('click', function() {
      $('input[name="id_portal"]').val('');
      $('input[name="title"]').val('');
      $('input[name="link"]').val('');
    });
  });
</script>
<?= $this->endSection(); ?>
