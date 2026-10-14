<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
      <div class="card">
          <div class="card-header"></div>
          <div class="card-body">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <h3 class="d-inline-block d-sm-none">Formulir Update data Peminjaman Kendaraan</h3>
                  <div class="col-12">
                    <img src="/dist/img/<?= $getData['gambar']; ?>" class="product-image" alt="Product Image">
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                    <form action="/kendaraan_kantor/update/riwayat" method="post">
                      <h3 class="my-3">Formulir Update data Peminjaman Kendaraan</h3>
                      <p>Pembaharuan data hanya bisa dilakukan untuk data "Tujuan", "Kegiatan" dan "Peserta".<br />Jika nama penumpang tidak ada pada pilihan, berarti pegawai tersebut sudah terdata pada tanggal lainnya!</p>
        
                      <hr>
                      <!--Formulir disini-->
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Tujuan</label>
                        <div class="col-sm-10">
                          <input type="hidden" class="form-control" name="id_kendaraan_kantor_riwayat" value="<?= $getData['id_kendaraan_kantor_riwayat']; ?>">
                          <input type="text" class="form-control" required name="tujuan" value="<?= $getData['tujuan']; ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Kegiatan</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" required rows="3" name="keterangan"><?= $getData['keterangan']; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Penumpang</label>
                        <div class="col-sm-10">
                          <select multiple="multiple" required class="select2" style="width: 100%;" name="penumpang[]">
                          <?php foreach($getPegawai as $data): ?>
                            <option value="<?= $data["id_data_pegawai"]; ?>" <?= in_array($data['id_data_pegawai'], json_decode($getData['penumpang'], true)) ? 'selected' : ''; ?>><?= name_capitalize($data["nama"]); ?></option>
                          <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
        
                      <div class="bg-gray py-2 px-3 mt-4">
                        <h2 class="mb-0">
                            <?php 
                            $tanggal = json_decode($getData['tanggal'], true);
                            if(count($tanggal) > 1): ?>
                                <?= shortdate_indo3($tanggal[0]); ?> - <?= shortdate_indo3($tanggal[count($tanggal)-1]); ?>
                            <?php else: ?>
                                <?= shortdate_indo3($tanggal[0]); ?>
                            <?php endif; ?>
                        </h2>
                        <h4 class="mt-0">
                          <small><?= count($tanggal); ?> hari peminjaman </small>
                        </h4>
                      </div>
        
                      <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg btn-flat float-right">
                          <i class="fas fa-save fa-lg mr-2"></i>
                          Simpan
                        </button>
                      </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>