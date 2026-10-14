<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body row">
        <div class="col-sm-8" style="border: 3px dotted rgba(0, 0, 0, 0.5);">
          <?php
          $array = unserialize($pemeliharaan['data_barang']);
          ?>
          <?php
          // $validationNumber = 0;
          // $dataLabel = ['Kode Satker', 'Kode Barang', 'NUP', 'Nama Barang', 'Merek'];
          ?>
          <?php foreach ($array as $key => $value) : ?>
            <span><?= $key; ?></span> : <strong><?= $value; ?></strong><br />
          <?php endforeach; ?>
        </div>
        <div class="col-sm 4">
          <form action="/abs/validasiPengajuanPemeliharaan" method="post">
            <input type="hidden" name="id_abs_pengajuan_pemeliharaan" value="<?= $pemeliharaan['id_abs_pengajuan_pemeliharaan']; ?>">
            <div class="form-group">
              <label for="">Status</label>
              <select name="status" class="form-control">
                <option <?= $pemeliharaan['status'] == 'pending' ? 'selected' : ''; ?> value="pending">Pending</option>
                <option <?= $pemeliharaan['status'] == 'proses pemeliharaan' ? 'selected' : ''; ?> value="proses pemeliharaan">Diproses untuk dilakukan pemeliharaan</option>
                <option <?= $pemeliharaan['status'] == 'rusak' ? 'selected' : ''; ?> value="rusak">Barang Rusak Total</option>
                <option <?= $pemeliharaan['status'] == 'selesai diperbaiki' ? 'selected' : ''; ?> value="selesai diperbaiki">Barang selesai diperbaiki</option>
              </select>
            </div>

            <div class="form-group">
              <label for="">Validasi</label>
              <textarea name="validasi" class="summernote" required><?= old('validasi') ? old('validasi') : $pemeliharaan['validasi']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-sm float-right"><i class="fas fa-share"></i> Simpan</button>
          </form>
        </div>
      </div>
      <div class="card-footer">
        <div class="card">
          <div class="card-header">
            <h3><strong>Keterangan Pengajuan</strong></h3>
          </div>
          <div class="card-body">
            <?= $pemeliharaan['keterangan']; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>