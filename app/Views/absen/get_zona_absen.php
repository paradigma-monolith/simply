<form action="/absen/saveZonaAbsen" method="post">
  <div class="form-group">
    <label for="">Keterangan Tempat</label>
    <input type="text" name="keterangan_tempat" required class="input-group">
  </div>
  <div class="form-group">
    <label for="">Alamat yang Terdeteksi</label>
    <textarea name="alamat" rows="4" class="input-group bg-secondary" required readonly><?= $alamat; ?></textarea>
  </div>
  <div class="form-group row">
    <label for="" class="col-4">Lintang</label>
    <div class="col-8">
      <input type="text" name="latitude" value="<?= $latitude; ?>" required readonly class="input-group bg-secondary">
    </div>
  </div>
  <div class="form-group row">
    <label for="" class="col-4">Bujur</label>
    <div class="col-8">
      <input type="text" name="longitude" value="<?= $longitude; ?>" required readonly class="input-group bg-secondary">
    </div>
  </div>
  <button class="btn btn-sm btn-secondary my-2" onclick="$('#modal-sm').modal('hide')">Batal</button>
  <button class="btn btn-sm btn-success float-right my-2"><i class="fas fa-save"></i> Ajukan Permintaan</button>
</form>