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
            <!-- <button class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</button> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>Nama</th>
                  <th>Jabatan</th>
                  <th>NIP</th>
                  <th>Golongan</th>
                  <th>Tim Kerja</th>
                  <th>Unit Kerja</th>
                  <th>Updated By</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $nomor = 1;
                foreach ($pegawai as $p) : ?>
                  <?php
                  //$waktu = explode("|", $p['masa_kerja']);
                  ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $p['unit_kerja']; ?></td>
                    <td><?= $p['nama']; ?></td>
                    <td><?= $p['jabatan']; ?></td>
                    <td><?= $p['nip']; ?></td>
                    <td><?= $p['golongan']; ?></td>
                    <td><?= isset($p['tim_kerja']) ? $p['tim_kerja'] : '-'; ?></td>
                    <td><?= $p['unit_kerja']; ?></td>
                    <td><?= $p['updated']; ?></td>
                    <td><?= $p['updated_at']; ?></td>
                    <td>
                      <button type="button" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></button>
                      <a href="/admin/editPegawai/<?= $p['id_data_pegawai']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <!--<button type="button" class="btn btn-xs bg-gradient-danger" for="Hapus Data"><i class="fas fa-trash-alt"></i></button>-->
                      <form action="/admin/ubah_statusBekerja" method="post" class="d-inline statusBekerja">
                        <?= csrf_field(); ?>
                        <input type="hidden" value="<?= $p['id_data_pegawai']; ?>" name="id_data_pegawai">
                        <button type="submit" class="btn btn-xs <?= $p['status_bekerja'] == "Aktif" ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?>" data-status="<?= $p['status_bekerja']; ?>">
                          <i class="fas fa-sync-alt"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>Nama</th>
                  <th>Jabatan</th>
                  <th>NIP</th>
                  <th>Golongan</th>
                  <th>Tim Kerja</th>
                  <th>Unit Kerja</th>
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