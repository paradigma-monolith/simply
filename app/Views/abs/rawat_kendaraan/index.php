<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Pengajuan</span>
            <span class="info-box-number"><?= $total_pengajuan; ?></span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pending</span>
            <span class="info-box-number"><?= $pending; ?></span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-sync-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Proses Verifikasi</span>
            <span class="info-box-number"><?= $proses; ?></span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Selesai</span>
            <span class="info-box-number"><?= $selesai; ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pemeliharaan Kendaraan</h3>
            <div class="card-tools">
                <a href="/abs/rawat_kendaraan/pengajuan" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Pengajuan</a>
            </div>
        </div>
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Unit Kerja</th>
                        <th>Kendaraan</th>
                        <th>Plat Nomor</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($riwayat as $row): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['tgl_pengajuan']; ?></td>
                        <td><?= $row['pemohon']; ?></td>
                        <td><?= $row['unit_kerja'] ?? '-'; ?></td>
                        <td>
                            <?php if($row['nama_kendaraan']): ?>
                                <?= $row['nama_kendaraan']; ?>
                            <?php else: ?>
                                <span class="text-danger font-italic">Kendaraan Terhapus</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['plat_nomor']): ?>
                                <?= $row['plat_nomor']; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= $row['keluhan']; ?></td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php elseif($row['status'] == 'verifikasi_1'): ?>
                                <span class="badge badge-info">Verifikasi 1</span>
                            <?php elseif($row['status'] == 'verifikasi_2'): ?>
                                <span class="badge badge-primary">Verifikasi 2</span>
                            <?php elseif($row['status'] == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif($row['status'] == 'ditolak'): ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/abs/rawat_kendaraan/detail/<?= $row['id_rawat']; ?>" class="btn btn-info btn-xs"><i class="fas fa-eye"></i> Detail</a>
                            <?php if(session()->get('role') == 'Admin'): ?>
                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-edit-<?= $row['id_rawat']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                <form action="/abs/rawat_kendaraan/delete_pengajuan/<?= $row['id_rawat']; ?>" method="post" class="d-inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus pengajuan ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</section>

<!-- Modal Edit (Hanya untuk Admin) -->
<?php if(session()->get('role') == 'Admin'): ?>
    <?php foreach($riwayat as $row): ?>
    <div class="modal fade" id="modal-edit-<?= $row['id_rawat']; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Edit Pengajuan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="/abs/rawat_kendaraan/update_pengajuan" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_rawat" value="<?= $row['id_rawat']; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kendaraan</label>
                            <select name="id_kendaraan" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                <?php foreach($kendaraan as $k): ?>
                                    <option value="<?= $k['id_kendaraan_kantor_daftar']; ?>" <?= ($k['id_kendaraan_kantor_daftar'] == $row['id_kendaraan_kantor_daftar']) ? 'selected' : ''; ?>>
                                        <?= $k['nama_kendaraan']; ?> - <?= $k['plat_nomor']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pengajuan</label>
                            <input type="date" name="tgl_pengajuan" class="form-control" value="<?= $row['tgl_pengajuan']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Keluhan</label>
                            <textarea name="keluhan" class="form-control" rows="3" required><?= $row['keluhan']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Estimasi Biaya</label>
                            <input type="number" name="estimasi_biaya" class="form-control" value="<?= $row['estimasi_biaya']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Bukti Foto</label>
                            <div class="custom-file">
                                <input type="file" name="bukti_foto" class="custom-file-input" id="customFile<?= $row['id_rawat']; ?>" required>
                                <label class="custom-file-label" for="customFile<?= $row['id_rawat']; ?>">Pilih file baru</label>
                            </div>
                            <?php if($row['bukti_foto']): ?>
                                <small class="text-info">File saat ini: <a href="/assets/abs/rawat_kendaraan/<?= $row['bukti_foto']; ?>" target="_blank">Lihat</a> (Wajib upload ulang jika ingin mengubah)</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection(); ?>
