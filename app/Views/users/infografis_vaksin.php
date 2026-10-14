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
              <a href="/infografis/tambah_vaksin" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tableWithoutExporting" class="table table-bordered table-striped text-center">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Unit Kerja</th>
                  <th>PHQC</th>
                  <th>COP</th>
                  <th>SSCEC</th>
                  <th>SSCC</th>
                  <th>P3K</th>
                  <th>ICV</th>
                  <th>SKLT</th>
                  <th>SIAOS</th>
                  <th>Surat IZIN<br />ANGKUT<br />JENAZAH</th>
                  <th>KIR</th>
                  <th>Vaksin<br />MM</th>
                  <th>Vaksin<br />YF</th>
                  <th>Vaksin<br />Polio</th>
                  <th>Tanggal Data</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $nomor = 1;
                foreach ($data_vaksin as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['unit_kerja']; ?></td>
                    <td><?= $data['phqc']; ?></td>
                    <td><?= $data['cop']; ?></td>
                    <td><?= $data['sscec']; ?></td>
                    <td><?= $data['sscc']; ?></td>
                    <td><?= $data['p3k']; ?></td>
                    <td><?= $data['icv']; ?></td>
                    <td><?= $data['sklt']; ?></td>
                    <td><?= $data['siaos']; ?></td>
                    <td><?= $data['siaj']; ?></td>
                    <td><?= $data['kir']; ?></td>
                    <td><?= $data['vaksin_mm']; ?></td>
                    <td><?= $data['vaksin_yf']; ?></td>
                    <td><?= $data['vaksin_polio']; ?></td>
                    <td><?= $data['time']; ?></td>
                    <td>
                      <a href="/infografis/vaksin_detail/<?= $data['id_infografis_vaksin']; ?>" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                      <a href="/infografis/update_vaksin/<?= $data['id_infografis_vaksin']; ?>" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <!-- <form action="/infografis/dlt_vaksin/<? //= $data['id_infografis_vaksin']; 
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
                  <th>PHQC</th>
                  <th>COP</th>
                  <th>SSCEC</th>
                  <th>SSCC</th>
                  <th>P3K</th>
                  <th>ICV</th>
                  <th>SKLT</th>
                  <th>SIAOS</th>
                  <th>Surat IZIN<br />ANGKUT<br />JENAZAH</th>
                  <th>KIR</th>
                  <th>Vaksin<br />MM</th>
                  <th>Vaksin<br />YF</th>
                  <th>Vaksin<br />Polio</th>
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