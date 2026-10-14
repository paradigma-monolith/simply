<form id="perbaharuiKontak" action="/auth/update_kontak" method="post">
  <div class="card-body">
    <div class="form-group">
      <label for="exampleInputEmail1">Email</label>
      <input type="email" name="email" class="form-control" aria-describedby="emailHelp" value="<?= $pengguna['email']; ?>">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Number telp./ Whatsapp</label>
      <input type="text" name="no_telp" class="form-control number" value="<?= $pengguna['no_telp']; ?>" maxlength="15">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Instagram</label>
      <div class="row">
        <input type="text" name="instagram" class="form-control col-4" placeholder="@hadianmuttaqin" value="<?= $pengguna['instagram']; ?>">
        <input type="text" name="link_instagram" class="form-control col-8" placeholder="https://instagram.com/hadianmuttaqin" value="<?= $pengguna['link_instagram']; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Facebook</label>
      <div class="row">
        <input type="text" name="facebook" class="form-control col-4" placeholder="Hadian Zainal Muttaqin" value="<?= $pengguna['facebook']; ?>">
        <input type="text" name="link_facebook" class="form-control col-8" placeholder="https://facebook.com" value="<?= $pengguna['link_facebook']; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Sosial Media lainnya</label>
      <div class="row">
        <input type="text" name="lainnya" class="form-control col-4" placeholder="@sosial" value="<?= $pengguna['lainnya']; ?>">
        <input type="text" name="link_lainnya" class="form-control col-8" placeholder="link lainnya" value="<?= $pengguna['link_lainnya']; ?>">
      </div>
    </div>
  </div>
  <div class="card-footer">
    <button type="submit" class="btn btn-block btn-success btn-simpan"><i class="fas fa-share"></i> Simpan</button>
  </div>
</form>