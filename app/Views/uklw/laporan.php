<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <!-- Default box -->
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header row">
          <div class="col"></div>
          <div class="col-6 text-right">
            <!-- <a href="/uklw/tambah_penjamah_makanan" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a> -->
          </div>
        </div>
        <div class="card-body">
          <form action="/uklw/excel/export_pemkes" method="post" target="_blank">
            <input type="hidden" name="activeMenu" value="<?= $activeMenu; ?>">
            <div class="row mt-4">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="has-float-label">
                    <div class="input-group">
                      <input type="text" name="filter_tanggal" id="filterTanggal" autocomplete="off" class="form-control date-dashboard">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                    </div>
                    <span>Filter data berdasarkan tanggal</span>
                  </label>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Pemeriksaan</label>
                  <select required multiple="multiple" name="pemeriksaan[]" style="width: 100%;" class="select2">
                    <option selected value="Pemeriksaan Fisik & Riwayat Penyakit">Pemeriksaan Fisik & Riwayat Penyakit</option>
                    <option selected value="Skrining TBC">Skrining TBC</option>
                    <option selected value="Skrining HIV">Skrining HIV</option>
                    <option selected value="Skrining HIV">Skrining PTM</option>
                    <option selected value="Laboratorium">Laboratorium</option>
                    <option selected value="Pemeriksaan Dokter">Pemeriksaan Dokter</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Status Pemeriksaan (PEMKES)</label>
                  <select name="status_pemeriksaan" style="width: 100%;" class="select2">
                    <option value="">--Semua Status Pemeriksaan--</option>
                    <option value="Penumpang">Penumpang</option>
                    <option value="Awak/Kru">Awak/Kru</option>
                    <option value="Petugas">Petugas</option>
                    <option value="Penjamah Makanan">Penjamah Makanan</option>
                    <option value="Masyarakat Umum">Masyarakat Umum</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Kota</label>
                  <select name="kota" class="form-control select2">
                    <option value="">--Semua Kota/Kab.--</option>
                    <?php foreach ($kota as $data) : ?>
                      <option value="<?= $data['kota_kabupaten']; ?>"><?= $data['kota_kabupaten']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-sm text-right">
                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.card -->
</section>
<?= $this->endSection(); ?>