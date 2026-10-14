<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-info elevation-1"><i class="fa fa-info-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total SOP Pendukung</span>
            <span class="info-box-number">
              <?= count($totalSopPendukung) ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">

        <div class="card">
          <!-- <div class="card-header">
            <div class="text-right">
              <a href="javascript:void(0)" class="btn bg-gradient-primary" data-toggle="modal" data-target="#tambahDokumenSop">
                <i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div> -->
          <!-- <div class="card-header">
            <div class="col-12 text-right">
              <a href="/sipepes/tambah_dokumen_sop" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
          </div> -->
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor Dokumen</th>
                  <th>Judul</th>
                  <th>Divisi</th>
                  <th>Tanggal Efektif</th>
                  <th>Revisi</th>
                  <th>Pratinjau | Ubah | Hapus</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($dataSopPendukung as $data) :
                ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $data['no_dokumen']; ?></td>
                    <td><?= $data['judul']; ?></td>
                    <td><?= $data['unit_kerja']; ?></td>
                    <!-- <td>
                      <?= $data['status_pengesahan'] == 'Proses Pengesahan' ? '<span class="badge bg-warning">' . $data['status_pengesahan'] . '</span>' : ''; ?>
                      <?= $data['status_pengesahan'] == 'Sudah Pengesahan' ? '<span class="badge bg-success">' . $data['status_pengesahan'] . '</span>' : ''; ?>
                    </td> -->
                    <td>
                      <?php if ($data['tanggal_efektif'] == "0000-00-00" || $data['tanggal_efektif'] == "0000-01-01") {
                        echo "<span class='text-danger'>Belum ada data.</span>";
                      } else {
                        echo date('d M Y', strtotime($data['tanggal_efektif']));
                      }
                      ?>
                    </td>
                    <td>
                      <?php if ($data['tanggal_revisi'] == "0000-00-00" || $data['tanggal_revisi'] == "0000-01-01") {
                        if($data['revisi_ke'] > 0) {
                            echo "<span class='text-danger'>Tidak ada data tanggal</span> (Rev. " . $data['revisi_ke'] . ")";
                        } else {
                            echo "<span class='text-danger'>Belum ada data.</span>";
                        }
                      } else {
                        echo date('d M Y', strtotime($data['tanggal_revisi'])) . " (Rev. " . $data['revisi_ke'] . ")";
                      }
                      ?>
                    </td>
                    <td>
                      <a href="/sipepes/lihat_dokumen_sop/<?= $data['dokumen_id']; ?>" class="btn btn-xs bg-gradient-info" data-toggle="tooltip" data-placement="bottom" title="Pratinjau" for="Lihat Data"><i class="fas fa-eye"></i></a>
                      <a href="/sipepes/ubah_dokumen_sop/<?= $data['dokumen_id']; ?>" class="btn btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Dokumen" for="Edit Data"><i class="fas fa-edit"></i></a>
                      <!-- <form action="/sipepes/hapus_dokumen_sop/<?= $data['dokumen_id']; ?>" method="post" class="d-inline formHapusData">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
                      </form> -->
                      <form action="/sipepes/hapus_dokumen_sop/<?= $data['dokumen_id']; ?>" method="post" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen" onclick="return confirm('Dokumen akan dihapus. Anda yakin?');"><i class="fas fa-trash-alt"></i></button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Nomor Dokumen</th>
                  <th>Judul</th>
                  <th>Divisi</th>
                  <th>Tanggal Efektif</th>
                  <th>Revisi</th>
                  <th>Pratinjau | Ubah | Hapus</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>

</section>

<?= $this->endSection(); ?>