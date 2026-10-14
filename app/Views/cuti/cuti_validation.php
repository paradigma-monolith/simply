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
              <h3>Data cuti yang belum diproses (<?= $role; ?>)</h3>
            </div>
            <div class="col-6 text-right">
              <h3><span class="badge badge-pill badge-warning view-jumlah-data-cuti-baru">-</span></h3>
            </div>
          </div>
          <div class="card-body view-data-cuti-baru">

          </div>
        </div>
      </div>
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <h3>Data cuti yang sudah diproses (<?= $role; ?>)</h3>
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
                  <th>Updated By</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($dataCuti as $data) : ?>
                  <?php if ($data['koordinator_status'] == 'Terkirim') : ?>
                    <tr>
                      <td><?= $nomor++; ?></td>
                      <td><span class="d-block"><?= $data['nip_pegawai']; ?>/</span><span class="d-block"><?= $data['nama_pegawai']; ?></span></td>
                      <td>
                        <span class="d-block">Kepegawaian:
                          <?= $data['cuti_status_kepegawaian'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_kepegawaian'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_kepegawaian'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_kepegawaian'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                        </span>
                        <span class="d-block">Atasan:

                          <?php if ($data['cuti_status_kepegawaian'] == 'Ditolak') : ?>
                            -
                          <?php else : ?>
                            <?= $data['cuti_status_atasan'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_atasan'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_atasan'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_atasan'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                          <?php endif; ?>
                        </span>
                        <span class="d-block">Pejabat:

                          <?php if ($data['cuti_status_kepegawaian'] == 'Ditolak' || $data['cuti_status_atasan'] == 'Ditolak') : ?>
                            -
                          <?php else : ?>
                            <?= $data['cuti_status_pejabat'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_pejabat'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_pejabat'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                            <?= $data['cuti_status_pejabat'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                          <?php endif; ?>
                        </span>
                      </td>
                      <td><?= $data['jenis_cuti']; ?></td>
                      <td><?= $data['updated']; ?></td>
                      <td><?= $data['time']; ?></td>
                      <td>
                        <form action="/cuti/pdf/<?= $data['id_cuti_data_cuti']; ?>" method="post" class="d-inline">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-sm bg-gradient-success"><i class="fas fa-file-alt"></i></button>
                        </form>
                        <?php if ($data['kepegawaian']) : ?>
                          <form action="/cuti/dlt_cuti/<?= $data['id_cuti_data_cuti']; ?>" method="post" class="d-inline formHapusData">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-calendar-minus"></i></button>
                          </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Cuti</th>
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