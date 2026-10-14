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
          <form id="tambahPegawai" action="/admin/tambah_pegawai" method="post">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body">
                  <div class="form-group">
                    <label>Akses Peran</label>
                    <select class="form-control select2" name="role_access" id="role_access" style="width: 100%;">
                      <option value=""> --Pilih Akses Sebagai-- </option>
                      <?php foreach ($role_access as $role) : ?>
                        <option value="<?= $role['role']; ?>"><?= $role['role']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="nama">Nama Pengguna</label>
                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Contoh: Hadian Zainal Muttaqin" value="<?= old('nama'); ?>">
                  </div>
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Contoh: hadianzm">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="password">Konfirmasi Password</label>
                    <input type="password" name="password2" class="form-control" id="password2" placeholder="Konfirmasi Password">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="col-md-8">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nama_pegawai">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="form-control" id="nama_pegawai" placeholder="--read only--" value="<?= old('nama_pegawai'); ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Wilayah Kerja</label>
                    <select class="form-control select2" name="unit_kerja" id="role_access" style="width: 100%;">
                      <option value=""> --Pilih Unit Kerja-- </option>
                      <?php foreach ($unit_kerja as $unit) : ?>
                        <option value="<?= $unit['unit_kerja']; ?>"><?= $unit['unit_kerja']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" name="nip" class="form-control" id="nip" placeholder="Contoh: 197306292002121003" value="<?= old('nip'); ?>">
                  </div>
                  <div class="row">
                    <div class="form-group col-9">
                      <label>Jabatan</label>
                      <select class="form-control select2" name="jabatan" id="jabatan" style="width: 100%;">
                        <option value=""> --Pilih Jabatan-- </option>
                        <?php foreach ($jabatan as $j) : ?>
                          <option value="<?= $j['id_jabatan']; ?>"><?= $j['jabatan']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-3">
                      <label>Golongan</label>
                      <select class="form-control select2" name="golongan" id="golongan" style="width: 100%;">
                        <option value=""> --Pilih Golongan-- </option>
                        <?php foreach ($golongan as $g) : ?>
                          <option value="<?= $g['id_golongan']; ?>"><?= $g['golongan']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="form-group col-4">
                      <label>Status Pegawai</label>
                      <select class="form-control select2" name="status_pegawai" id="status_pegawai" style="width: 100%;">
                        <option value=""> --Pilih Status Pegawai-- </option>
                        <?php foreach ($status_pegawai as $sp) : ?>
                          <option value="<?= $sp['id_status_pegawai']; ?>"><?= $sp['status_pegawai']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-4">
                      <label>Substansi</label>
                      <select class="form-control select2" name="substansi" id="substansi" style="width: 100%;">
                        <option value=""> --Pilih Substansi-- </option>
                        <?php foreach ($substansi as $s) : ?>
                          <option value="<?= $s['id_substansi']; ?>"><?= $s['nama_substansi']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-4">
                      <label>Tim Kerja</label>
                      <select class="form-control select2" name="tim_kerja" id="tim_kerja" style="width: 100%;">
                        <option value=""> --Pilih Tim Kerja-- </option>
                        <?php foreach ($tim_kerja as $tk) : ?>
                          <option value="<?= $tk['id_tim_kerja']; ?>"><?= $tk['nama_tim_kerja']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="tgl_masuk">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk" placeholder="Opsional" data-toggle="tooltip" data-placement="bottom" title="Opsional">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card-body">
                  <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-share"></i> Simpan</button>
                  </div>
                </div>
              </div>
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