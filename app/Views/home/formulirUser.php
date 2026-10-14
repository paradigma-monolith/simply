<form id="perbaharuiUser" action="/auth/update_user" method="post">
  <div class="card-body">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Contoh: hadianzm" value="<?= session()->get("username"); ?>">
    </div>
    <div class="d-none">
      <div class="form-group">
        <label for="password">Password Lama</label>
        <input type="password" name="password" class="form-control" placeholder="Password" value="">
      </div>
      <div class="form-group">
        <label for="password">Password Baru</label>
        <input type="password" name="password2" class="form-control" placeholder="Konfirmasi Password" value="">
      </div>
      <div class="form-group">
        <label for="password">Konfirmasi Password</label>
        <input type="password" name="password3" class="form-control" placeholder="Konfirmasi Password" value="">
      </div>
    </div>
  </div>
  <div class="card-footer">
    <button type="submit" class="btn btn-block btn-success btn-simpan"><i class="fas fa-share"></i> Simpan</button>
  </div>
</form>