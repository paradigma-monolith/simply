<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="row">
      <div class="col-md-12">

        <div class="card card-default">
          <!-- form start -->
          <form id="tambahDataPermohonan" action="/cuti/update_permohonan" method="post">
            <input type="hidden" name="id_cuti_data_permohonan" value="<?= $dataCuti['id_cuti_data_permohonan']; ?>">
            <div class="card-header bg-primary">
              <h3 class="card-title">Buat Data Permohonan Baru</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>

                <div class="col-sm-10">
                  <select class="form-control select2" disabled name="pegawai" id="pegawai" style="width: 100%;">
                    <option value=""> --Pilih NIP/NOPEG/NAMA-- </option>
                    <?php foreach ($pegawai as $data) : ?>
                      <option value="<?= $data['nip']; ?>" <?= $dataCuti['id_data_pegawai'] == $data['id_data_pegawai'] ? 'selected' : ''; ?>><?= $data['nip']; ?>/<?= $data['nama']; ?></option>
                    <?php endforeach; ?>
                    <option value=""></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <div class="col"></div>
                <label class="has-float-label col-sm-3">
                  <input type="text" name="jabatan" class="form-control" id="jabatanPegawai" placeholder="...." readonly value="<?= old('jabatan'); ?>">
                  <span>Jabatan</span>
                </label>
                <label class="has-float-label col-sm-2">
                  <input type="text" name="golongan" class="form-control" id="golonganPegawai" placeholder="...." readonly value="<?= old('golongan'); ?>">
                  <span>Golongan</span>
                </label>
                <label class="has-float-label col-sm-5">
                  <input type="text" name="masa_kerja" class="form-control" id="masaKerjaPegawai" placeholder="...." readonly value="<?= old('masa_kerja'); ?>">
                  <span>Masa Kerja</span>
                </label>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <label class="has-float-label col-sm-10">
                  <input type="text" name="unit_kerja" class="form-control" id="unitKerjaPegawai" placeholder="...." readonly value="<?= old('unit_kerja'); ?>">
                  <span>Unit Kerja</span>
                </label>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Permohonan</label>

                <div class="col-sm-10">
                  <select class="form-control select2" name="jenis_permohonan" id="jenis_permohonan" style="width:100%;">
                    <option value=""> --Pilih Jenis Permohonan-- </option>
                    <option value="Tidak Masuk Kerja" <?= $dataCuti['jenis_permohonan'] == "Tidak Masuk Kerja" ? 'selected' : ''; ?>>Tidak Masuk Kerja</option>
                    <option value="Terlambat Masuk Kerja" <?= $dataCuti['jenis_permohonan'] == "Terlambat Masuk Kerja" ? 'selected' : ''; ?>>Terlambat Masuk Kerja</option>
                    <option value="Pulang Sebelum Waktunya" <?= $dataCuti['jenis_permohonan'] == "Pulang Sebelum Waktunya" ? 'selected' : ''; ?>>Pulang Sebelum Waktunya</option>
                    <option value="Tidak Berada di Tempat Tugas" <?= $dataCuti['jenis_permohonan'] == "Tidak Berada di Tempat Tugas" ? 'selected' : ''; ?>>Tidak Berada di Tempat Tugas</option>
                    <option value="Tidak Melakukan Rekam Kehadiran" <?= $dataCuti['jenis_permohonan'] == "Tidak Melakukan Rekam Kehadiran" ? 'selected' : ''; ?>>Tidak Melakukan Rekam Kehadiran</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <label class="has-float-label col-sm-10">
                  <textarea name="alasan_permohonan" class="form-control" rows="3" id="alasan_permohonan" placeholder="Tuliskan alasan anda" value="<?= old('alasan_permohonan'); ?>"><?= $dataCuti['alasan_permohonan']; ?></textarea>
                  <span>Alasan Permohonan</span>
                </label>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Permohonan</label>
                <div class="col-sm-10 row">
                  <label class="has-float-label col-5">
                    <div class="input-group date" id="date" data-target-input="nearest">
                      <input type="text" name="tanggal" id="tanggal" class="form-control datetimepicker-input" data-target="#date" value="<?= $dataCuti['tanggal']; ?>" />
                      <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label class="col-sm-2">Titimangsa</label>

                <div class="col-sm-10">
                  <select class="form-control select2" disabled name="skema_titimangsa" id="skema_titimangsa" style="width: 100%;">
                    <option value=""> --KEPEGAWAIAN/ATASAN-- </option>
                    <?php foreach ($skema as $data) : ?>
                      <?php if ($data['jenis_surat'] == 'surat_sakti') : ?>
                        <option value="<?= $data['id_skema_titimangsa']; ?>" <?= $dataCuti['getSkema'] == $data['id_skema_titimangsa'] ? 'selected' : ''; ?> data-jabatan-kepegawaian="<?= $data['jabatan_kepegawaian']; ?>" data-nama-kepegawaian="<?= $data['nama_kepegawaian']; ?>" data-nip-kepegawaian="<?= $data['nip_kepegawaian']; ?>" data-jabatan-atasan="<?= $data['jabatan_atasan']; ?>" data-nama-atasan="<?= $data['nama_atasan']; ?>" data-nip-atasan="<?= $data['nip_atasan']; ?>" data-unit-kerja="<?= $data['unit_kerja']; ?>" data-lokasi=<?= $data['lokasi']; ?>><?= $data['nama_atasan']; ?></option>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <div class="col-5">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_kepegawaian" class="form-control" readonly id="jabatan_kepegawaian" placeholder="Jabatan" value="<?= $dataCuti['jabatan_kepegawaian']; ?>">
                    <span>Bagian Kepegawaian</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_kepegawaian" class="form-control" readonly id="nama_kepegawaian" placeholder="Nama" value="<?= $dataCuti['nama_kepegawaian']; ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_kepegawaian" class="form-control" readonly id="nip_kepegawaian" placeholder="NIP" value="<?= $dataCuti['nip_kepegawaian']; ?>">
                    <span>NIP</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_atasan" class="form-control" readonly id="jabatan_atasan" placeholder="Jabatan" value="<?= $dataCuti['jabatan_kepegawaian']; ?>">
                    <span>A.n Kasubbag ADUM</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_atasan" class="form-control" readonly id="nama_atasan" placeholder="Nama" value="<?= $dataCuti['nama_kepegawaian']; ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_atasan" class="form-control" readonly id="nip_atasan" placeholder="NIP" value="<?= $dataCuti['nip_kepegawaian']; ?>">
                    <span>NIP</span>
                  </label>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <div class="col-7">
                  <label class="has-float-label">
                    <input type="text" name="unit_kerja_skema" class="form-control" readonly id="unit_kerja_skema" placeholder="Jabatan" value="<?= $dataCuti['unit_kerja_skema']; ?>">
                    <span>Unit Kerja</span>
                  </label>
                </div>
                <div class="col-3">
                  <label class="has-float-label">
                    <input type="text" name="lokasi_skema" class="form-control" readonly id="lokasi_skema" placeholder="Jabatan" value="<?= $dataCuti['lokasi_skema']; ?>">
                    <span>Lokasi</span>
                  </label>
                </div>
              </div>
              <!-- =========================================================================== -->
            </div>

            <div class="card-body row">
              <label class="col-sm-2">TTD</label>

              <div>
                <img src="<?= $dataCuti['ttd_data_pegawai']; ?>" alt="TTD">
              </div>
              <div>
                <!-- canvas tanda tangan  -->
                <canvas id="signature-pad-disabled" style="border: 1px solid #ccc; border-radius: 0.5rem; background-color:#ccc;"></canvas>
                <canvas id="signature-pad" style="display:none; border: 1px solid #ccc; border-radius: 0.5rem;" class="signature-pad"></canvas>
                <br />
                <div class="form-group form-check">
                  <input type="checkbox" name="cekTTD" class="form-check-input" id="cekSignature">
                  <label class="form-check-label" for="cekSignature">Ganti TTD</label>
                </div>
                <input type="hidden" id="signature" name="signature">
              </div>

              <!-- tombol ganti warna  -->
              <div class="d-block p-2">
                <button type="button" class="btn btn-success" id="change-color">
                  Ubah Warna
                </button>
              </div>

              <!-- tombol undo  -->
              <!-- <div class="d-block p-2">
                <button type="button" class="btn btn-dark" id="undo">
                  <span class="fas fa-undo"></span>
                  Kembalikan
                </button>
              </div> -->

              <!-- tombol hapus tanda tangan  -->
              <div class="d-block p-2">
                <button type="button" class="btn btn-danger" id="clear">
                  <span class="fas fa-eraser"></span>
                  Bersihkan
                </button>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<script>
  $(function() {
    // Date range picker
    $('#date').datetimepicker({
      format: 'L',
      format: 'DD MMMM YYYY'
    });
  })
</script>
<?= $this->endSection(); ?>