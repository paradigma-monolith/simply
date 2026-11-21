<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<style>
  .table thead th {
    vertical-align: middle;
  }

  .table td,
  .table th {
    vertical-align: middle;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <form id="sinadiTambahPerjalananDinas" action="/sinadi/tambah_sinadi" method="post" enctype="multipart/form-data">
            <input type="hidden" name="jenis_perjalanan_dinas" value="luar kota">
            <div>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td><label><span class="d-none">Kategori</span></label></td>
                          <th colspan="3">
                            <input type="hidden" class="form-control" name="kategori" readonly id="sinadi-kategori" value="Perseorangan">
                            </td>
                        </tr>
                        <tr>
                          <td><label><span>Nama Kegiatan Perjalanan Dinas</span></label></td>
                          <td colspan="3">
                            <input class="form-control" type="text" name="nama_kegiatan" placeholder="contoh: Pengumpulan Data Surveilans Epidemiologi Penyakit di Pintu Masuk wilker Indramayu Balongan" required>
                          </td>
                        </tr>
                        <tr>
                          <td valign="middle"><label><span>Tanggal Pelaksanaan Perjalanan Dinas</span></label></td>
                          <td>
                            <input class="form-control" type="date" name="tgl_pelaksanaan" required>
                            <center>
                              <label><span>s/d </span></label>
                            </center>
                            <input class="form-control" type="date" name="tgl_pelaksanaan_akhir" required>
                          </td>
                          <td valign="middle"><label><span>Tempat Perjalanan Dinas</span></label></td>
                          <td valign="middle">
                            <input class="form-control" type="text" name="tempat_perjalanan" placeholder="contoh: Puskesmas Pesisir Kota Cirebon" required>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Mata Anggaran</span></label></td>
                          <td colspan="3">
                            <input class="form-control" type="text" name="mata_anggaran" placeholder="Sumber Pembiayaan berdasarkan Dipa dan Non-Dipa" required>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Sumber Anggaran</span></label></td>
                          <td colspan="3">
                            <select class="form-control" name="sumber_anggaran" required>
                              <option value="APBN" selected>APBN</option>
                              <option value="RPL">RPL</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label><span>Upload Berkas</span></label> <br />
                            <i>Note: Upload berkas perjalanan dinas</i>
                          </td>
                          <td colspan="3">
                            <div class="mt-3">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon11">Upload</span>
                                </div>
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="berkas" id="inputGroupFile11" aria-describedby="inputGroupFileAddon11" required>
                                  <label class="custom-file-label" for="inputGroupFile11" data-browse="File laporan">Cari...</label>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                        <!--  <td><label><span>Hasil Kegiatan</span></label></td>
                          <td colspan="3">
                            <div class="row">
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios0" value="99" checked>
                                  <label class="form-check-label" for="exampleRadios0">
                                    Default
                                  </label>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios1" value="1">
                                  <label class="form-check-label" for="exampleRadios1">
                                    Upload File
                                  </label>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios2" value="2">
                                  <label class="form-check-label" for="exampleRadios2">
                                    Input File
                                  </label>
                                </div>
                              </div>
                            </div>-->
                            <div class="file-block mt-3 d-none">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                </div>
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="file_hasil_kegiatan" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                  <label class="custom-file-label" for="inputGroupFile01" data-browse="File laporan">Cari...</label>
                                </div>
                              </div>
                            </div>
                            <div class="summernote-block mt-3 d-none">
                              <textarea name="hasil_kegiatan" class="summernote" rows="5"></textarea>
                            </div>
                          </td>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <hr>
              <br>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">

                    <div class="sinadi-data-pegawai">
                      <div>
                        <button type="button" class="btn btn-secondary btn-lg btn-block btn-info-pegawai" data-peserta="">PERSEORANGAN</button>
                        <table class="table table-bordered" style="width: 100%">
                          <tbody>
                            <tr class="autoInput">
                              <td colspan="2" style="width: 50%;">
                                <label><span>NIP/NOPEG</span></label>
                                <select class="form-control select2" type="text" name="nip[]" data-pegawai="nip[]" required>
                                  <option value="">--NIP/NOPEG | NAMA--</option>
                                  <?php foreach ($pegawai as $data) : ?>
                                    <option value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </td>
                              <td style="width: 40%;">
                                <input class="nama" readonly name="nama[]" type="hidden" required>
                                <label><span>Jabatan</span></label>
                                <input class="form-control jabatan" readonly name="jabatan[]" type="text" required>
                              </td>
                              <td>
                                <label><span>Golongan</span></label>
                                <input class="form-control golongan" readonly name="golongan[]" type="text" required>
                              </td>
                            </tr>
                            <tr class="manualInput">
                              <td style="width: 25%;">
                                <label><span>NIP</span></label>
                                <input class="form-control manualNip" required disabled="true" name="manualNip[]" type="text">
                              </td>
                              <td style="width: 25%;">
                                <label><span>Nama</span></label>
                                <input class="form-control manualNama" required disabled="true" name="manualNama[]" type="text">
                              </td>
                              <td style="width: 40%;">
                                <label><span>Jabatan</span></label>
                                <input class="form-control manualJabatan" required disabled="true" name="manualJabatan[]" type="text">
                              </td>
                              <td>
                                <label><span>Golongan</span></label>
                                <input class="form-control manualGolongan" required disabled="true" name="manualGolongan[]" type="text">
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4" class="p-0">
                                <button type="button" class="btn btn-sm btn-block btn-light m-0 p-0" onclick="sinadiToggleHideDataPegawai(this)">Tambah manual data pegawai</button>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label><span>Tanggal Berangkat</span></label>
                                <input class="form-control" type="date" name="tgl_pergi[]" required>
                              </td>
                              <td>
                                <label><span>Tanggal Kembali</span></label>
                                <input class="form-control" type="date" name="tgl_pulang[]" required>
                              </td>
                              <td>
                                <label><span>Kota/ Kabupaten Asal</span></label>
                                <select class="form-control select2" type="text" name="kota_asal[]" data-pegawai="kota_asal[]" required>
                                  <option value="">--Kota/ Kabupaten--</option>
                                  <?php foreach ($kota as $data) : ?>
                                    <option value="<?= $data['nama_kota_kabupaten']; ?>"><?= $data['nama_kota_kabupaten']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </td>
                              <td>
                                <label><span>Kota/ Kabupaten Tujuan</span></label>
                                <input type="hidden" class="kota_tujuan_input" value="" name="kota_tujuan[]">
                                <select class="select2 kota_tujuan_select" multiple="multiple" name="kota[]" data-placeholder="--Kota/ Kabupaten--" style="width: 100%;" required>
                                  <option value="">--Kota/ Kabupaten--</option>
                                  <?php foreach ($kota as $data) : ?>
                                    <option value="<?= $data['nama_kota_kabupaten']; ?>"><?= $data['nama_kota_kabupaten']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="1">
                                <label><span>No. Surat Tugas</span></label>
                                <input class="form-control" type="text" name="no_surat_tugas[]" required="">
                              </td>
                              <td colspan="1">
                                <label><span>Tgl. Surat Tugas</span></label>
                                <input class="form-control" type="date" name="tgl_surat_tugas[]" required="">
                                <br>
                              </td>
                              <td colspan="1">
                                <label><span>No. SPPD</span></label>
                                <input class="form-control" type="text" name="no_sppd[]" required>
                              </td>
                              <td colspan="1">
                                <label><span>Tgl. SPPD</span></label>
                                <input class="form-control" type="date" name="tgl_sppd[]" required>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4">
                                <label><span>Alat angkutan yang dipergunakan</span></label>
                                <select class="form-control select2" type="text" name="transportasi[]" data-pegawai="transportasi[]" required>
                                  <option value="">--Pilih jenis Transportasi--</option>
                                  <option value="Transportasi udara">Transportasi udara</option>
                                  <option value="Transportasi darat">Transportasi darat</option>
                                  <option value="Transportasi laut">Transportasi laut</option>
                                </select>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table table-bordered" style="width: 100%">
                          <tbody>
                            <tr>
                              <td colspan="2" class="text-center"><label><span>Transport</span></label></td>
                              <td colspan="2" class="text-center"><label><span>Uang Harian</span></label></td>
                              <td colspan="2" class="text-center"><label><span>Penginapan</span></label></td>
                              <td colspan="3" class="text-center" rowspan="2"><label><span>Jumlah Transfer</span></label></td>
                            </tr>
                            <tr>
                              <td class="text-center">
                                <span>Hari</span>
                              </td>
                              <td class="text-center">
                                <span>Transport</span>
                              </td>
                              <td class="text-center">
                                <span>Hari</span>
                              </td>
                              <td class="text-center">
                                <span>Unit Cost</span>
                              </td>
                              <td class="text-center">
                                <span>Kali</span>
                              </td>
                              <td class="text-center">
                                <span>Unit Cost</span>
                              </td>
                            </tr>
                            <tr class="perhitungan_anggaran">
                              <td class="text-center">
                                <input class="form-control anggaran transport_hari" type="text" name="transport_hari[]" placeholder="0">
                              </td>
                              <td class="text-center">
                                <input class="form-control anggaran transport_transport" type="text" name="transport_nominal[]" placeholder="0">
                              </td>
                              <td class="text-center">
                                <input class="form-control anggaran harian_hari" type="text" name="harian_hari[]" placeholder="0">
                              </td>
                              <td class="text-center">
                                <input class="form-control anggaran harian_unit_cost" type="text" name="harian_nominal[]" placeholder="0">
                              </td>
                              <td class="text-center">
                                <input class="form-control anggaran penginapan_kali" type="text" name="penginapan_hari[]" placeholder="0">
                              </td>
                              <td class="text-center">
                                <input class="form-control anggaran penginapan_unit_cost" type="text" name="penginapan_nominal[]" placeholder="0">
                              </td>
                              <td class="text-center align-bottom" colspan="3" rowspan="2">
                                <input class="form-control jumlah_transfer" type="text" name="jumlah_transfer[]" placeholder="Jumlah" readonly>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-center" colspan="2">
                                <input class="form-control transport_jumlah" type="text" name="transport_jumlah[]" placeholder="Jumlah" readonly>
                              </td>
                              <td class="text-center" colspan="2">
                                <input class="form-control harian_jumlah" type="text" name="harian_jumlah[]" placeholder="Jumlah" readonly>
                              </td>
                              <td class="text-center" colspan="2">
                                <input class="form-control penginapan_jumlah" type="text" name="penginapan_jumlah[]" placeholder="Jumlah" readonly>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="data-pegawai-tambahan"></div>
                    <div class="block" id="btnPlusPegawai">
                      <button type="button" class="btn btn-info btn-block btn-lg sinadi-btn-tambah-data"><i class="fas fa-plus"></i> <strong>Tambah Data Pegawai</strong></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td><label><span>Nama Kantor</span></label></td>
                          <td><input type="text" disabled class="form-control" name="" value="<?= $setting['alias_kantor']; ?>" /></td>
                          <td><input type="text" disabled class="form-control" name="" value="<?= $setting['nama_kantor']; ?>" /></td>
                        </tr>
                        <tr>
                          <td><label><span>Kepala Kantor</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $setting['kepala_kantor_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Pejabat Pembuat Komitmen</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $setting['ppk_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Kepala Sub Bagian Adm Umum</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $setting['kasubag_adum_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>BENDAHARA PENGELUARAN</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $setting['bendahara_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>PIC</span></label></td>
                          <td colspan="2">
                            <select class="form-control select2 pic" type="text" name="sinadi_pic" required style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><input type="hidden" name="pic_nama"></td>
                          <td colspan="2"><input type="hidden" name="pic_nip"></td>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div>
                <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fas fa-share"></i> <strong>Simpan</strong></button>
              </div>
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
  $(document).ready(function() {
    // Event listener untuk mengupdate input hidden saat select berubah
    $(document).on('change', '.kota_tujuan_select', function() {
      var selectedValues = $(this).val();
      var inputHidden = $(this).prev('.kota_tujuan_input');
      
      if (selectedValues) {
        // Jika selectedValues adalah array, join dengan koma
        if (Array.isArray(selectedValues)) {
            inputHidden.val(selectedValues.join(','));
        } else {
            inputHidden.val(selectedValues);
        }
      } else {
        inputHidden.val('');
      }
    });
  });
</script>
<?= $this->endSection(); ?>