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
            <a href="/admin/tambah_unit_kerja" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Unit Kerja</th>
                  <th>Lokasi</th>
                  <th>Updated By</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($unit_kerja as $unit) : ?>
                  <tr>
                    <td><?= $unit['unit_kerja']; ?></td>
                    <td><?= $unit['lokasi']; ?></td>
                    <td><?= $unit['nama']; ?></td>
                    <td><?= $unit['time']; ?></td>
                    <td>
                      <button type="button" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></button>
                      <a href="/admin/editUnitKerja/<?= $unit['id_unit_kerja']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <form action="/admin/dlt_UnitKerja/<?= $unit['id_unit_kerja']; ?>" method="post" class="d-inline formHapusData">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-xs bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                      </form>
                      <form action="/admin/ubah_statusUnitKerja" method="post" class="d-inline statusUnitKerja">
                        <?= csrf_field(); ?>
                        <input type="hidden" value="<?= $unit['id_unit_kerja']; ?>" name="id_unit_kerja">
                        <button type="submit" class="btn btn-xs <?= $unit['status_unit_kerja'] == "Aktif" ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?>" data-status="<?= $unit['status_unit_kerja']; ?>">
                          <i class="fas fa-sync-alt"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Unit Kerja</th>
                  <th>Lokasi</th>
                  <th>Updated By</th>
                  <th>Last Update</th>
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