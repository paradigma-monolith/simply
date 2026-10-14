<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <h3>Data cuti yang belum diproses</h3>
            </div>
            <div class="col-6 text-right">
              <h3><span class="badge badge-pill badge-warning view-jumlah-data-permohonan-baru">-</span></h3>
            </div>
          </div>
          <div class="card-body view-data-permohonan-baru">

          </div>
        </div>
      </div>
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <h3>Data cuti yang sudah diproses</h3>
            </div>
            <!-- <div class="col-6 text-right">
                            <a href="/cuti/tambah_cuti" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Cuti</th>
                  <th>Periode</th>
                  <th>Updated By</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($dataCuti as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><span class="d-inline-block"><?= $data['nip_pegawai']; ?></span>/<span class="d-inline-block"><?= $data['nama_pegawai']; ?></span></td>
                    <td>
                      <span class="d-block">Kepegawaian:
                        <?= $data['status_kepegawaian'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['status_kepegawaian'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['status_kepegawaian'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['status_kepegawaian'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
                      </span>
                      <span class="d-block">Atasan:
                        <?php if ($data['status_kepegawaian'] == 'Ditolak') : ?>
                          -
                        <?php else : ?>

                          <?= $data['status_atasan'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['status_atasan'] . '</span>' : ''; ?>
                          <?= $data['status_atasan'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['status_atasan'] . '</span>' : ''; ?>
                          <?= $data['status_atasan'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['status_atasan'] . '</span>' : ''; ?>
                          <?= $data['status_atasan'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['status_atasan'] . '</span>' : ''; ?>
                        <?php endif; ?>
                      </span>
                    </td>
                    <td><?= $data['jenis_permohonan']; ?></td>
                    <td><span class="d-inline-block"><?= $data['tanggal']; ?></span></td>
                    <td><?= $data['updated']; ?></td>
                    <td><?= $data['time']; ?></td>
                    <td>
                      <form action="/cuti/pdf/permohonan/<?= $data['id_cuti_data_permohonan']; ?>" method="post" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm bg-gradient-success"><i class="fas fa-file-alt"></i></button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Cuti</th>
                  <th>Periode</th>
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
</section>
<?= $this->endSection(); ?>