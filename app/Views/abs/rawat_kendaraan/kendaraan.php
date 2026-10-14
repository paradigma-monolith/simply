<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kendaraan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Kendaraan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kendaraan</th>
                            <th>Plat Nomor</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($kendaraan as $k): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $k['nama_kendaraan']; ?></td>
                            <td><?= $k['plat_nomor']; ?></td>
                            <td>
                                <?php if($k['gambar']): ?>
                                    <img src="/dist/img/<?= $k['gambar']; ?>" width="100">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-<?= $k['id_kendaraan_kantor_daftar']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="/abs/rawat_kendaraan/kendaraan/hapus/<?= $k['id_kendaraan_kantor_daftar']; ?>" method="post" class="d-inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Tambah Kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="/abs/rawat_kendaraan/kendaraan/simpan" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kendaraan</label>
                        <input type="text" name="nama_kendaraan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Pilih file</label>
                        </div>
                        <small class="text-muted">Format: jpg, jpeg, png. Max: 5MB</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<?php foreach($kendaraan as $k): ?>
<div class="modal fade" id="modal-edit-<?= $k['id_kendaraan_kantor_daftar']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Edit Kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="/abs/rawat_kendaraan/kendaraan/update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_kendaraan_kantor_daftar" value="<?= $k['id_kendaraan_kantor_daftar']; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kendaraan</label>
                        <input type="text" name="nama_kendaraan" class="form-control" value="<?= $k['nama_kendaraan']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" value="<?= $k['plat_nomor']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="customFileEdit<?= $k['id_kendaraan_kantor_daftar']; ?>" required>
                            <label class="custom-file-label" for="customFileEdit<?= $k['id_kendaraan_kantor_daftar']; ?>">Pilih file</label>
                        </div>
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

<?= $this->endSection(); ?>
