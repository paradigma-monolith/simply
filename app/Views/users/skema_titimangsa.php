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
                        <a href="/admin/tambah_titimangsa" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Pejabat</th>
                                    <th>Atasan</th>
                                    <th>Keterangan</th>
                                    <th>Updated By</th>
                                    <th>Last Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($skema as $data) : ?>
                                    <tr>
                                        <?php if ($data['jabatan_pejabat']) : ?>
                                            <td style="width: 250px;" 2>
                                                <span class="d-inline-block">| <?= $data['jabatan_pejabat']; ?></span><span class="d-inline-block">| Nama: <?= $data['nama_pejabat']; ?></span><span class="d-inline-block">| NIP: <?= $data['nip_pejabat']; ?></span>
                                            </td>
                                        <?php else : ?>
                                            <td>-</td>
                                        <?php endif; ?>
                                        <?php if ($data['jabatan_atasan']) : ?>
                                            <td style=" width: 250px;">
                                                <span class="d-inline-block">| <?= $data['jabatan_atasan']; ?> </span><span class="d-inline-block">| Nama: <?= $data['nama_atasan']; ?> </span><span class="d-inline-block">| NIP: <?= $data['nip_atasan']; ?></span>
                                            </td>
                                        <?php else : ?>
                                            <td>-</td>
                                        <?php endif; ?>

                                        <td><span class="d-inline-block">| <?= $data['jenis_surat']; ?></span><span class="d-inline-block">| <?= $data['lokasi']; ?></span>
                                        </td>
                                        <td><?= $data['updated']; ?></td>
                                        <td><?= $data['time']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></button>
                                            <button type="button" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-xs bg-gradient-danger" for="Hapus Data"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Pejabat</th>
                                    <th>Atasan</th>
                                    <th>Keterangan</th>
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