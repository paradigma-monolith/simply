<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Verifikator 1</h3>
                    </div>
                    <div class="card-body">
                        <form action="/abs/rawat_kendaraan/insert_verifikator" method="post" class="mb-3">
                            <input type="hidden" name="role" value="verifikator_1">
                            <div class="input-group">
                                <select name="id_user" class="form-control select2" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    <?php foreach($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= $u['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </span>
                            </div>
                        </form>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($verifikator1 as $v): ?>
                                <tr>
                                    <td><?= $v['nama']; ?></td>
                                    <td>
                                        <a href="/abs/rawat_kendaraan/delete_verifikator/<?= $v['id_verifikator']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Hapus verifikator?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Verifikator 2</h3>
                    </div>
                    <div class="card-body">
                        <form action="/abs/rawat_kendaraan/insert_verifikator" method="post" class="mb-3">
                            <input type="hidden" name="role" value="verifikator_2">
                            <div class="input-group">
                                <select name="id_user" class="form-control select2" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    <?php foreach($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= $u['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-success">Tambah</button>
                                </span>
                            </div>
                        </form>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($verifikator2 as $v): ?>
                                <tr>
                                    <td><?= $v['nama']; ?></td>
                                    <td>
                                        <a href="/abs/rawat_kendaraan/delete_verifikator/<?= $v['id_verifikator']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Hapus verifikator?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
