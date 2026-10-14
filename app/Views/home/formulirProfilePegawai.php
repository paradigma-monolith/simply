<style>
  /*Select2 ReadOnly Start*/
  select[readonly].select2-hidden-accessible+.select2-container {
    pointer-events: none;
    touch-action: none;
  }

  select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
    background: #eee;
    box-shadow: none;
  }

  select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
  select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
    display: none;
  }
</style>
<form id="perbaharuiProfilePegawai" action="/auth/update_profile_pegawai" method="post">
  <div class="row">
    <!-- /.card-body -->
    <div class="col-md-12">
      <div class="card-body">
        <div class="form-group">
          <label for="nama_pegawai">Nama Pegawai</label>
          <input type="text" name="nama_pegawai" class="form-control" placeholder="Nama Pengguna" value="<?= $profile['nama']; ?>" required>
        </div>
        <div class="form-group">
          <label>Wilayah Kerja</label>
          <select class="form-control select2" name="unit_kerja" style="width: 100%;" required>
            <option value=""> --Pilih Unit Kerja-- </option>
            <?php foreach ($unit_kerja as $unit) : ?>
              <option value="<?= $unit['id_unit_kerja']; ?>" <?= $unit['id_unit_kerja'] == $profile['id_unit_kerja'] ? 'selected' : ''; ?>><?= $unit['unit_kerja']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="nip">NIP/ NOPEG</label>
          <input type="text" name="nip" class="form-control" placeholder="Contoh: 197306292002121003" value="<?= $profile['nip']; ?>" required>
        </div>
        <div class="row">
          <div class="form-group col-9">
            <label>Jabatan</label>
            <button type="button" class="btn btn-sm btn-primary p-0 px-1">
              <!--<small><i class="fas fa-plus"></i></small>-->
            </button>
            <select class="form-control select2" name="jabatan" style="width: 100%;" required>
              <option value=""> --Pilih Jabatan-- </option>
              <?php foreach ($jabatan as $j) : ?>
                <option value="<?= $j['id_jabatan']; ?>" <?= $j['id_jabatan'] == $profile['id_jabatan'] ? 'selected' : ''; ?>><?= $j['jabatan']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-3">
            <label>Golongan</label>
            <select class="form-control select2" name="golongan" style="width: 100%;" <?= $profile['status_pegawai'] == 'PPNPN' ? 'readonly' : ''; ?> required>
              <option value=""> --Pilih Golongan-- </option>
              <?php foreach ($golongan as $g) : ?>
                <option value="<?= $g['id_golongan']; ?>" <?= $g['id_golongan'] == $profile['id_golongan'] ? 'selected' : ''; ?>><?= $g['golongan']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-4">
            <label>Status Pegawai</label>
            <select class="form-control select2" name="status_pegawai" style="width: 100%;" <?= $profile['status_pegawai'] == 'PPNPN' ? 'readonly' : ''; ?> required>
              <option value=""> --Pilih Status Pegawai-- </option>
              <?php foreach ($status_pegawai as $sp) : ?>
                <option value="<?= $sp['id_status_pegawai']; ?>" <?= $sp['id_status_pegawai'] == $profile['id_status_pegawai'] ? 'selected' : ''; ?>><?= $sp['status_pegawai']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-4">
            <label>Substansi</label>
            <select class="form-control select2" name="substansi" style="width: 100%;" required>
              <option value=""> --Pilih Substansi-- </option>
              <?php foreach ($substansi as $s) : ?>
                <option value="<?= $s['id_substansi']; ?>" <?= $s['id_substansi'] == $profile['id_substansi'] ? 'selected' : ''; ?> <?= $s['nama_substansi'] == "Kepala Kantor" ? 'disabled' : ''; ?>><?= $s['nama_substansi']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-4">
            <label>Tim Kerja</label>
            <select class="form-control select2" name="tim_kerja" style="width: 100%;" required>
              <option value=""> --Pilih Tim Kerja-- </option>
              <?php foreach ($tim_kerja as $tk) : ?>
                <option value="<?= $tk['id_tim_kerja']; ?>" <?= $tk['id_tim_kerja'] == $profile['id_tim_kerja'] ? 'selected' : ''; ?>><?= $tk['nama_tim_kerja']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl_masuk">Tanggal Masuk</label>
          <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk" placeholder="Opsional" value="<?= $profile['tgl_awal_masa']; ?>" required>
        </div>
        <div class="form-group">
          <label>Pelatihan Jabatan Fungsional</label>
          <select class="form-control select2" name="pelatihan_jafung_status" id="pelatihan_jafung_status" style="width: 100%;" required>
            <option value="Belum" <?= (isset($profile['pelatihan_jafung_status']) && $profile['pelatihan_jafung_status'] == 'Belum') ? 'selected' : ''; ?>>Belum</option>
            <option value="Sudah" <?= (isset($profile['pelatihan_jafung_status']) && $profile['pelatihan_jafung_status'] == 'Sudah') ? 'selected' : ''; ?>>Sudah</option>
          </select>
        </div>
        <div id="pelatihan_jafung_details" style="display: <?= (isset($profile['pelatihan_jafung_status']) && $profile['pelatihan_jafung_status'] == 'Sudah') ? 'block' : 'none'; ?>;">
          <div class="form-group">
            <label for="pelatihan_jafung_tahun">Tahun</label>
            <input type="number" name="pelatihan_jafung_tahun" id="pelatihan_jafung_tahun" class="form-control" placeholder="Contoh: 2023" value="<?= $profile['pelatihan_jafung_tahun'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label for="pelatihan_jafung_nama">Nama Pelatihan</label>
            <input type="text" name="pelatihan_jafung_nama" id="pelatihan_jafung_nama" class="form-control" placeholder="Nama Pelatihan" value="<?= $profile['pelatihan_jafung_nama'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label for="pelatihan_jafung_sertifikat">No Sertifikat</label>
            <input type="text" name="pelatihan_jafung_sertifikat" id="pelatihan_jafung_sertifikat" class="form-control" placeholder="Nomor Sertifikat" value="<?= $profile['pelatihan_jafung_sertifikat'] ?? ''; ?>">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card-footer">
        <button type="submit" class="btn btn-block btn-success btn-simpan"><i class="fas fa-share"></i> Simpan</button>
      </div>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#pelatihan_jafung_status').on('change', function() {
      if ($(this).val() === 'Sudah') {
        $('#pelatihan_jafung_details').show();
        $('#pelatihan_jafung_tahun').prop('required', true);
        $('#pelatihan_jafung_nama').prop('required', true);
        $('#pelatihan_jafung_sertifikat').prop('required', true);
      } else {
        $('#pelatihan_jafung_details').hide();
        $('#pelatihan_jafung_tahun').prop('required', false);
        $('#pelatihan_jafung_nama').prop('required', false);
        $('#pelatihan_jafung_sertifikat').prop('required', false);
      }
    });

    // Trigger change on load to set the initial required state
    $('#pelatihan_jafung_status').trigger('change');
  });
</script>