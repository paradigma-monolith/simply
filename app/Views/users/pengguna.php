<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header text-right">
            <a href="/admin/tambah_user" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Data Pengguna Only</a>
            <a href="/admin/tambah_pegawai" class="btn bg-gradient-success"><i class="fas fa-plus"></i> Data Pegawai</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Username</th>
                  <th>User Role</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $key) : ?>
                  <tr>
                    <td id="hidden"><?= $key['nama']; ?></td>
                    <td><?= $key['username']; ?></td>
                    <td><?= $key['role']; ?></td>
                    <td><?= $key['updated_at']; ?></td>
                    <td>
                      <button type="button" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></button>
                      <a href="/admin/editPengguna/<?= $key['id_user']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <form action="/admin/dlt_Pengguna/<?= $key['id_user']; ?>" method="post" class="d-inline formHapusData">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-xs bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                      </form>
                      <form action="/admin/ubah_statusPegawai" method="post" class="d-inline statusPegawai">
                        <?= csrf_field(); ?>
                        <input type="hidden" value="<?= $key['id_user']; ?>" name="id_user">
                        <button type="submit" class="btn btn-xs <?= $key['status_pegawai_aktif'] == "Aktif" ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?>" data-status="<?= $key['status_pegawai_aktif']; ?>">
                          <i class="fas fa-sync-alt"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Nama</th>
                  <th>Username</th>
                  <th>User Role</th>
                  <th>Updated By</th>
                  <th>Aksi</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>