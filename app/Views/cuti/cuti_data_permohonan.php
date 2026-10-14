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
              <a href="/cuti/tambah_permohonan" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Permohonan</th>
                  <th>Tanggal Izin</th>
                  <th>Updated By</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($dataCuti as $data) : ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="d-block"><?= $data['nip_pegawai']; ?>/</span><span class="d-block"><?= $data['nama_pegawai']; ?></span></td>
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
                    <td><?= $data['tanggal']; ?></td>
                    <td><?= $data['updated']; ?></td>
                    <td><?= $data['time']; ?></td>
                    <?php if ($data['status_kepegawaian'] == 'Disetujui' && $data['status_atasan'] == 'Disetujui') : ?>
                      <td>
                        <a href="/cuti/detail_permohonan/<?= $data['id_cuti_data_permohonan']; ?>" class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <form action="/cuti/pdf/permohonan/<?= $data['id_cuti_data_permohonan']; ?>" method="post" class="d-inline">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-sm bg-gradient-success"><i class="fas fa-file-alt"></i></button>
                        </form>
                      </td>
                    <?php elseif ($data['status_kepegawaian'] == 'Ditolak' || $data['status_atasan'] == 'Ditolak') : ?>
                      <td>
                        <span class="badge badge-pill badge-secondary">Tidak ada aksi</span>
                      </td>
                    <?php elseif ($data['status_kepegawaian'] == 'Dikembalikan' || $data['status_atasan'] == 'Dikembalikan') : ?>
                      <td>
                        <a href="/cuti/detail_permohonan/<?= $data['id_cuti_data_permohonan']; ?>" class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <a href="/cuti/update_permohonan/<?= $data['id_cuti_data_permohonan']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      </td>
                    <?php else : ?>
                      <td>
                        <a href="/cuti/detail_permohonan/<?= $data['id_cuti_data_permohonan']; ?>" class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <a href="/cuti/update_permohonan/<?= $data['id_cuti_data_permohonan']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                        <form action="/cuti/permohonan/dlt_cuti/<?= $data['id_cuti_data_permohonan']; ?>" method="post" class="d-inline formHapusData">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Permohonan</th>
                  <th>Tanggal Izin</th>
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