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
              <!-- <h3>Kedatangan Pesawat</h3> -->
            </div>
            <div class="col-6 text-right">
              <a href="/infografis/tambah_pesawat" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tableWithoutExporting" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>Total Kedatangan <br />Pesawat</th>
                  <th>Total Kedatangan <br />Crew</th>
                  <th>Total Kedatangan <br />Penumpang</th>
                  <th>Total Keberangkatan <br />Pesawat</th>
                  <th>Total Keberangkatan <br />Crew</th>
                  <th>Total Keberangkatan <br />Penumpang</th>
                  <th>Tanggal Data</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $nomor = 1;
                foreach ($data_pesawat as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['unit_kerja']; ?></td>
                    <td><?= $data['pesawat_datang_luar'] + $data['pesawat_datang_negeri']; ?></td>
                    <td><?= $data['crew_datang_luar'] + $data['crew_datang_negeri']; ?></td>
                    <td><?= $data['penumpang_datang_luar'] + $data['penumpang_datang_negeri']; ?></td>
                    <td><?= $data['pesawat_berangkat_luar'] + $data['pesawat_berangkat_negeri']; ?></td>
                    <td><?= $data['crew_berangkat_luar'] + $data['crew_berangkat_negeri']; ?></td>
                    <td><?= $data['penumpang_berangkat_luar'] + $data['penumpang_berangkat_negeri']; ?></td>
                    <td><?= $data['time']; ?></td>
                    <td>
                      <a href="/infografis/pesawat_detail/<?= $data['id_infografis_pesawat']; ?>" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                      <a href="/infografis/update_pesawat/<?= $data['id_infografis_pesawat']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <!-- <form action="/infografis/dlt_pesawat/<? //= $data['id_infografis_pesawat']; 
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
                  <th>Total Kedatangan <br />Pesawat</th>
                  <th>Total Kedatangan <br />Crew</th>
                  <th>Total Kedatangan <br />Penumpang</th>
                  <th>Total Keberangkatan <br />Pesawat</th>
                  <th>Total Keberangkatan <br />Crew</th>
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