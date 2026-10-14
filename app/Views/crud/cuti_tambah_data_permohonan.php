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
          <form id="tambahDataPermohonan" action="/cuti/tambah_permohonan" method="post" autocomplete="off">
            <div class="card-header bg-primary">
              <h3 class="card-title">Buat Data Permohonan Baru</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <?php if ($getThisPegawai) : ?>
                  <input type="hidden" name="pegawai" value="<?= $getThisPegawai; ?>">
                <?php endif; ?>
                <div class="col-sm-10">
                  <select class="form-control select2" name="pegawai" id="pegawai" style="width: 100%;" <?= $getThisPegawai ? 'disabled' : ''; ?>>
                    <option value=""> --Pilih NIP/NOPEG/NAMA-- </option>
                    <?php foreach ($pegawai as $data) : ?>
                      <option value="<?= $data['nip']; ?>" <?= $data['nip'] == $getThisPegawai ? 'selected' : ''; ?>><?= $data['nip']; ?>/<?= $data['nama']; ?></option>
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
                  <input style="border-color:transparent; background-image: none;" type="text" name="masa_kerja" class="form-control is-valid" id="masaKerjaPegawai" placeholder="...." readonly value="<?= old('masa_kerja'); ?>">
                  <span>Masa Kerja</span>
                  <div class="valid-feedback text-warning">
                    Masa kerja akan diakumulasikan dari tanggal permohonan pada surat
                  </div>
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
                    <option value="Tidak Masuk Kerja">Tidak Masuk Kerja</option>
                    <option value="Terlambat Masuk Kerja">Terlambat Masuk Kerja</option>
                    <option value="Pulang Sebelum Waktunya">Pulang Sebelum Waktunya</option>
                    <option value="Tidak Berada di Tempat Tugas">Tidak Berada di Tempat Tugas</option>
                    <option value="Tidak Melakukan Rekam Kehadiran">Tidak Melakukan Rekam Kehadiran</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <label class="has-float-label col-sm-10">
                  <textarea name="alasan_permohonan" class="form-control" rows="3" id="alasan_permohonan" placeholder="Tuliskan alasan anda" value="<?= old('alasan_permohonan'); ?>"></textarea>
                  <span>Alasan Permohonan</span>
                </label>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Permohonan</label>
                <div class="col-sm-10 row">
                  <label class="has-float-label col-5">
                    <div class="input-group date" id="date" data-target-input="nearest">
                      <input type="text" name="tanggal" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" />
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
                  <select class="form-control select2" name="skema_titimangsa" id="skema_titimangsa" style="width: 100%;">
                    <option value=""> --KEPEGAWAIAN/ATASAN-- </option>
                    <?php foreach ($skema as $data) : ?>
                      <?php if ($data['jenis_surat'] == 'surat_sakti') : ?>
                        <option value="<?= $data['id_skema_titimangsa']; ?>" data-jabatan-kepegawaian="<?= $data['jabatan_kepegawaian']; ?>" data-nama-kepegawaian="<?= $data['nama_kepegawaian']; ?>" data-nip-kepegawaian="<?= $data['nip_kepegawaian']; ?>" data-jabatan-atasan="<?= $data['jabatan_atasan']; ?>" data-nama-atasan="<?= $data['nama_atasan']; ?>" data-nip-atasan="<?= $data['nip_atasan']; ?>" data-unit-kerja="<?= $data['unit_kerja']; ?>" data-lokasi=<?= $data['lokasi']; ?>><?= $data['nama_atasan']; ?></option>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <div class="col-sm-5">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_kepegawaian" class="form-control" readonly id="jabatan_kepegawaian" placeholder="Jabatan" value="<?= old('jabatan_kepegawaian'); ?>">
                    <span>Bagian Kepegawaian</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_kepegawaian" class="form-control" readonly id="nama_kepegawaian" placeholder="Nama" value="<?= old('nama_kepegawaian'); ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_kepegawaian" class="form-control" readonly id="nip_kepegawaian" placeholder="NIP" value="<?= old('nip_kepegawaian'); ?>">
                    <span>NIP</span>
                  </label>
                </div>
                <div class="col-sm-5">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_atasan" class="form-control" readonly id="jabatan_atasan" placeholder="Jabatan" value="<?= old('jabatan_atasan'); ?>">
                    <span>A.n Kasubbag ADUM</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_atasan" class="form-control" readonly id="nama_atasan" placeholder="Nama" value="<?= old('nama_atasan'); ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_atasan" class="form-control" readonly id="nip_atasan" placeholder="NIP" value="<?= old('nip_atasan'); ?>">
                    <span>NIP</span>
                  </label>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <div class="col-sm-7">
                  <label class="has-float-label">
                    <input type="text" name="unit_kerja_skema" class="form-control" readonly id="unit_kerja_skema" placeholder="Jabatan" value="<?= old('unit_kerja_skema'); ?>">
                    <span>Unit Kerja</span>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label class="has-float-label">
                    <input type="text" name="lokasi_skema" class="form-control" readonly id="lokasi_skema" placeholder="Jabatan" value="<?= old('lokasi_skema'); ?>">
                    <span>Lokasi</span>
                  </label>
                </div>
              </div>
              <!-- =========================================================================== -->
            </div>

            <div class="card-body row">
              <label class="col-sm-2">TTD</label>
              <div class="">
                <!-- canvas tanda tangan  -->
                <canvas id="signature-pad" style="border: 1px solid #ccc; border-radius: 0.5rem;" class="signature-pad"></canvas>
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
      format: 'DD MMMM YYYY',
      defaultDate: moment()
    });
  })
</script>
<?= $this->endSection(); ?>