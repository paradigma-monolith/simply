
text/x-generic edit_pengguna.php ( exported SGML document, ASCII text, with CRLF line terminators )
<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <!-- jquery validation -->
        <div class="card card-primary">
          <div class="card-header">
            <!-- <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3> -->
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form id="tambahPengguna" action="/admin/editPengguna/<?= $user['id_user']; ?>" method="post">
            <div class="card-body">
              <div class="form-group">
                <label>Akses Peran</label>
                <select class="form-control select2" name="role_access" id="role_access" style="width: 100%;">
                  <option value=""> --Pilih Akses Sebagai-- </option>
                  <?php foreach ($role_access as $role) : ?>
                    <option value="<?= $role['role']; ?>" <?= $role['role'] == $user['role'] ? 'selected' : ''; ?>><?= $role['role']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="nama">Nama Pengguna</label>
                <input type="text" name="nama" class="form-control" id="nama" placeholder="Contoh: Hadian Zainal Muttaqin" value="<?= $user['nama']; ?>">
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Contoh: hadianzm" value="<?= $user['username']; ?>">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" disabled class="form-control" id="password" placeholder="***************************">
              </div>
              <div class="form-group">
                <label for="password">Konfirmasi Password</label>
                <input type="password" name="password2" disabled class="form-control" id="password2" placeholder="***************************">
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>