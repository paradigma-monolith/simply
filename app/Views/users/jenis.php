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
                        <button class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Updated By</th>
                                    <th>Last Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>Username</td>
                                    <td>Password</td>
                                    <td>Updated By</td>
                                    <td>Last Update</td>
                                    <td>
                                        <button type="button" class="btn btn-xs bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-xs bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-xs bg-gradient-danger" for="Hapus Data"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Password</th>
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