<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <!-- <h3>Kedatangan Kapal</h3> -->
            </div>
            <div class="col-6 text-right">
              <a href="/infografis/tambah_kapal" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tableWithoutExporting" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>Total Kedatangan <br />Kapal</th>
                  <th>Total Kedatangan <br />ABK</th>
                  <th>Total Kedatangan <br />Penumpang</th>
                  <th>Total Keberangkatan <br />Kapal</th>
                  <th>Total Keberangkatan <br />ABK</th>
                  <th>Total Keberangkatan <br />Penumpang</th>
                  <th>Tanggal Data</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $nomor = 1;
                foreach ($data_kapal as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['unit_kerja']; ?></td>
                    <td><?= $data['kapal_datang_luar'] + $data['kapal_datang_negeri']; ?></td>
                    <td><?= $data['abk_datang_luar'] + $data['abk_datang_negeri']; ?></td>
                    <td><?= $data['penumpang_datang_luar'] + $data['penumpang_datang_negeri']; ?></td>
                    <td><?= $data['kapal_berangkat_luar'] + $data['kapal_berangkat_negeri']; ?></td>
                    <td><?= $data['abk_berangkat_luar'] + $data['abk_berangkat_negeri']; ?></td>
                    <td><?= $data['penumpang_berangkat_luar'] + $data['penumpang_berangkat_negeri']; ?></td>
                    <td><?= $data['time']; ?></td>
                    <td>
                      <a href="/infografis/kapal_detail/<?= $data['id_infografis_kapal']; ?>" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                      <a href="/infografis/update_kapal/<?= $data['id_infografis_kapal']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <!-- <form action="/infografis/dlt_kapal/<? //= $data['id_infografis_kapal']; 
                                                                ?>" method="post" class="d-inline">
                        <? //= csrf_field(); 
                        ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-xs bg-gradient-danger" onclick="return confirm('apakah anda yakin?');"><i class="fas fa-trash-alt"></i></button>
                      </form> -->
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>Total Kedatangan <br />Kapal</th>
                  <th>Total Kedatangan <br />ABK</th>
                  <th>Total Kedatangan <br />Penumpang</th>
                  <th>Total Keberangkatan <br />Kapal</th>
                  <th>Total Keberangkatan <br />ABK</th>
                  <th>Total Keberangkatan <br />Penumpang</th>
                  <th>Tanggal Data</th>
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