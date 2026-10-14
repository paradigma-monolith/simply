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
          <form id="sinadiTambahPerjalananDinas" action="/sinadi/edit_sinadi" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_perjalanan" value="<?= $perjalanan['id_perjalanan']; ?>">
            <input type="hidden" name="is_dalam_kota" value="1">
            <div>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td><label><span class="d-none">Kategori</span></label></td>
                          <th colspan="3">
                            <input type="hidden" class="form-control" name="kategori" readonly id="sinadi-kategori" value="<?= count($peserta) > 1 ? 'Kelompok' : 'Perseorangan'; ?>">
                            </td>
                        </tr>
                        <tr>
                          <td><label><span>Nama Kegiatan Perjalanan Dinas</span></label></td>
                          <td colspan="3">
                            <input class="form-control" type="text" name="nama_kegiatan" value="<?= $perjalanan['nama_kegiatan']; ?>" placeholder="contoh: Pengumpulan Data Surveilans Epidemiologi Penyakit di Pintu Masuk wilker Indramayu Balongan" required>
                          </td>
                        </tr>
                        <tr>
                          <td valign="middle"><label><span>Tanggal Pelaksanaan Perjalanan Dinas</span></label></td>
                          <td>
                            <input class="form-control" type="date" name="tgl_pelaksanaan" value="<?= $perjalanan['tgl_pelaksanaan']; ?>" required>
                            <center>
                              <label><span>s/d </span></label>
                            </center>
                            <input class="form-control" type="date" name="tgl_pelaksanaan_akhir" value="<?= $perjalanan['tgl_pelaksanaan_akhir']; ?>" required>
                          </td>
                          <td valign="middle"><label><span>Tempat Perjalanan Dinas</span></label></td>
                          <td valign="middle">
                            <input class="form-control" type="text" name="tempat_perjalanan" value="<?= $perjalanan['tempat_perjalanan']; ?>" placeholder="contoh: Puskesmas Pesisir Kota Cirebon" required>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Mata Anggaran</span></label></td>
                          <td colspan="3">
                            <input class="form-control" type="text" name="mata_anggaran" value="<?= $perjalanan['mata_anggaran']; ?>" placeholder="Sumber Pembiayaan berdasarkan Dipa dan Non-Dipa" required>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Sumber Anggaran</span></label></td>
                          <td colspan="3">
                            <?php $sumber = isset($perjalanan['sumber_anggaran']) ? $perjalanan['sumber_anggaran'] : 'APBN'; ?>
                            <select class="form-control" name="sumber_anggaran" required>
                              <option value="APBN" <?= $sumber == 'APBN' ? 'selected' : '' ?>>APBN</option>
                              <option value="RPL" <?= $sumber == 'RPL' ? 'selected' : '' ?>>RPL</option>
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
                                  <input type="file" class="custom-file-input" name="berkas" id="inputGroupFile11" aria-describedby="inputGroupFileAddon11">
                                  <label class="custom-file-label" for="inputGroupFile11" data-browse="File laporan">Cari...</label>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="d-none">
                          <td><label><span>Hasil Kegiatan</span></label></td>
                          <td colspan="3">
                            <div class="row">
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios0" value="99" <?= $perjalanan['file'] == '' && $perjalanan['catatan_kegiatan'] == '' ? 'checked' : ''; ?>>
                                  <label class="form-check-label" for="exampleRadios0">
                                    Default
                                  </label>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios1" value="1" <?= $perjalanan['file'] != '' ? 'checked' : ''; ?>>
                                  <label class="form-check-label" for="exampleRadios1">
                                    Upload File
                                  </label>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="selectHasilKegiatan" id="exampleRadios2" value="2" <?= $perjalanan['file'] == '' && $perjalanan['catatan_kegiatan'] != '' ? 'checked' : ''; ?>>
                                  <label class="form-check-label" for="exampleRadios2">
                                    Input File
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="file-block mt-3 <?= $perjalanan['file'] != '' ? '' : 'd-none'; ?>">
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
                            <div class="summernote-block mt-3 <?= $perjalanan['file'] == '' && $perjalanan['catatan_kegiatan'] != '' ? '' : 'd-none'; ?>">
                              <textarea name="hasil_kegiatan" class="summernote" rows="5"><?= $perjalanan['catatan_kegiatan']; ?></textarea>
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
                        <button type="button" class="btn btn-secondary btn-lg btn-block btn-info-pegawai" data-peserta=""><?= count($peserta) > 1 ? 'PEGAWAI NO 1' : 'PERSEORANGAN'; ?></button>
                        <table class="table table-bordered" style="width: 100%">
                          <tbody>
                            <tr class="autoInput">
                              <td colspan="2" style="width: 50%;">
                                <label><span>NIP/NOPEG</span></label>
                                <select class="form-control select2" type="text" name="nip[]" data-pegawai="nip[]" required="">
                                  <option value="">--NIP/NOPEG | NAMA--</option>
                                  <?php
                                  $findPegawai = false;
                                  foreach ($pegawai as $data) : ?>
                                    <?php
                                    if ($peserta[0]['nip_pegawai'] == $data['nip']) {
                                      $findPegawai = true;
                                    }
                                    ?>
                                    <option value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>" <?= $peserta[0]['nip_pegawai'] == $data['nip'] ? 'selected' : ''; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                                  <?php endforeach; ?>
                                  <?php if ($findPegawai == false) : ?>
                                    <option value="<?= $peserta[0]['nip_pegawai']; ?>" data-nama="<?= $peserta[0]['nama_pegawai']; ?>" data-jabatan="<?= $peserta[0]['jabatan_pegawai']; ?>" data-golongan="<?= $peserta[0]['golongan']; ?>" selected><?= $peserta[0]['nip_pegawai']; ?> | <?= $peserta[0]['nama_pegawai']; ?></option>
                                  <?php endif; ?>
                                </select>
                              </td>
                              <td style="width: 40%;">
                                <input class="nama" readonly name="nama[]" type="hidden" required="" value="<?= $peserta[0]['nama_pegawai']; ?>">
                                <label><span>Jabatan</span></label>
                                <input class="form-control jabatan" readonly name="jabatan[]" type="text" required="" value="<?= $peserta[0]['jabatan_pegawai'] != '' ? $peserta[0]['jabatan_pegawai'] : '-'; ?>">
                              </td>
                              <td>
                                <label><span>Golongan</span></label>
                                <input class="form-control golongan" readonly name="golongan[]" type="text" required="" value="<?= $peserta[0]['golongan'] != '' ? $peserta[0]['golongan'] : '-'; ?>">
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
                                <button type="button" class="btn btn-sm btn-block btn-light m-0 p-0" onclick="sinadiToggleHideDataPegawai(this)">Tambah daftar data pegawai</button>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label><span>Tanggal Berangkat</span></label>
                                <input class="form-control" type="date" name="tgl_pergi[]" required="" value="<?= $peserta[0]['tgl_pergi']; ?>">
                              </td>
                              <td>
                                <label><span>Tanggal Kembali</span></label>
                                <input class="form-control" type="date" name="tgl_pulang[]" required="" value="<?= $peserta[0]['tgl_pulang']; ?>">
                              </td>
                              <td>
                                <label><span>Alamat Asal</span></label>
                                <input class="form-control" type="text" name="kota_asal[]" placeholder="Isi alamat asal" required value="<?= $peserta[0]['kota_asal']; ?>">
                              </td>
                              <td>
                                <label><span>Alamat Tujuan</span></label>
                                <input type="hidden" value="<?= $peserta[0]['kota']; ?>" name="kota_tujuan[]">
                                <input class="form-control" type="text" name="kota[]" placeholder="Isi alamat tujuan" required value="<?= $peserta[0]['kota']; ?>">
                              </td>
                            </tr>
                            <tr>
                              <td colspan="1">
                                <label><span>No. Surat Tugas</span></label>
                                <input class="form-control" type="text" name="no_surat_tugas[]" required="" value="<?= $peserta[0]['no_surat_tugas']; ?>">
                              </td>
                              <td colspan="1">
                                <label><span>Tgl. Surat Tugas</span></label>
                                <input class="form-control" type="date" name="tgl_surat_tugas[]" required="" value="<?= $peserta[0]['tgl_surat_tugas']; ?>">
                                <br>
                              </td>
                              <td colspan="1">
                                <label><span>No. SPPD</span></label>
                                <input class="form-control" type="text" name="no_sppd[]" required value="<?= $peserta[0]['no_sppd']; ?>">
                              </td>
                              <td colspan="1">
                                <label><span>Tgl. SPPD</span></label>
                                <input class="form-control" type="date" name="tgl_sppd[]" required value="<?= $peserta[0]['tgl_sppd']; ?>">
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4">
                                <label><span>Alat angkutan yang dipergunakan</span></label>
                                <select class="form-control select2" type="text" name="transportasi[]" data-pegawai="transportasi[]" required>
                                  <option value="">--Pilih jenis Transportasi--</option>
                                  <?php if ($peserta[0]['transportasi'] == "Kendaraan umum"): ?>
                                    <option value="Kendaraan umum" selected disabled="disabled">Kendaraan umum</option>
                                  <?php endif; ?>
                                  <option value="Transportasi udara" <?= ($peserta[0]['transportasi'] == "Transportasi udara") ? 'selected' : ''; ?>>Transportasi udara</option>
                                  <option value="Transportasi darat" <?= ($peserta[0]['transportasi'] == "Transportasi darat") ? 'selected' : ''; ?>>Transportasi darat</option>
                                  <option value="Transportasi laut" <?= ($peserta[0]['transportasi'] == "Transportasi laut") ? 'selected' : ''; ?>>Transportasi laut</option>
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
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran transport_hari" type="text" name="transport_hari[]" placeholder="0" value="<?= $peserta[0]['transport_hari']; ?>">
                              </td>
                              <td class="text-center">
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran transport_transport" type="text" name="transport_nominal[]" placeholder="0" value="<?= $peserta[0]['transport_nominal']; ?>">
                              </td>
                              <td class="text-center">
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran harian_hari" type="text" name="harian_hari[]" placeholder="0" value="<?= $peserta[0]['harian_hari']; ?>">
                              </td>
                              <td class="text-center">
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran harian_unit_cost" type="text" name="harian_nominal[]" placeholder="0" value="<?= $peserta[0]['harian_nominal']; ?>">
                              </td>
                              <td class="text-center">
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran penginapan_kali" type="text" name="penginapan_hari[]" placeholder="0" value="<?= $peserta[0]['penginapan_hari']; ?>">
                              </td>
                              <td class="text-center">
                                <input onchange="sinadiPerhitunganAnggaran(this)" class="form-control anggaran penginapan_unit_cost" type="text" name="penginapan_nominal[]" placeholder="0" value="<?= $peserta[0]['penginapan_nominal']; ?>">
                              </td>
                              <td class="text-center align-bottom" colspan="3" rowspan="2">
                                <input class="form-control jumlah_transfer" type="text" name="jumlah_transfer[]" placeholder="Jumlah" readonly required>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-center" colspan="2">
                                <input class="form-control transport_jumlah" type="text" name="transport_jumlah[]" placeholder="Jumlah" readonly required>
                              </td>
                              <td class="text-center" colspan="2">
                                <input class="form-control harian_jumlah" type="text" name="harian_jumlah[]" placeholder="Jumlah" readonly required>
                              </td>
                              <td class="text-center" colspan="2">
                                <input class="form-control penginapan_jumlah" type="text" name="penginapan_jumlah[]" placeholder="Jumlah" readonly required>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="data-pegawai-tambahan">
                      <?php foreach ($peserta as $value => $p) : ?>
                        <?php if ($value != 0) : ?>
                          <div>
                            <button type="button" class="btn btn-secondary btn-lg btn-block btn-info-pegawai btn-danger" data-peserta="<?= $peserta[$value]['id_peserta']; ?>"><i class="fas fa-trash-alt"></i> PEGAWAI NO <?= $value + 1; ?></button>
                            <table class="table table-bordered" style="width: 100%">
                              <tbody>
                                <tr class="autoInput">
                                  <td colspan="2" style="width: 50%;">
                                    <label><span>NIP/NOPEG</span></label>
                                    <select class="form-control select2" type="text" name="nip[]" data-pegawai="nip[]" required="">
                                      <option value="">--NIP/NOPEG | NAMA--</option>
                                      <?php
                                      $findPegawai = false;
                                      foreach ($pegawai as $data) : ?>
                                        <?php
                                        if ($peserta[$value]['nip_pegawai'] == $data['nip']) {
                                          $findPegawai = true;
                                        }
                                        ?>
                                        <option value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>" <?= $peserta[$value]['nip_pegawai'] == $data['nip'] ? 'selected' : ''; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                                      <?php endforeach; ?>
                                      <?php if ($findPegawai == false) : ?>
                                        <option value="<?= $peserta[$value]['nip_pegawai']; ?>" data-nama="<?= $peserta[$value]['nama_pegawai']; ?>" data-jabatan="<?= $peserta[$value]['jabatan_pegawai']; ?>" data-golongan="<?= $peserta[$value]['golongan']; ?>" selected><?= $peserta[$value]['nip_pegawai']; ?> | <?= $peserta[$value]['nama_pegawai']; ?></option>
                                      <?php endif; ?>
                                    </select>
                                  </td>
                                  <td style="width: 40%;">
                                    <input class="nama" readonly name="nama[]" type="hidden" required="" value="<?= $peserta[$value]['nama_pegawai']; ?>">
                                    <label><span>Jabatan</span></label>
                                    <input class="form-control jabatan" readonly name="jabatan[]" type="text" required="" value="<?= $peserta[$value]['jabatan_pegawai'] != '' ? $peserta[$value]['jabatan_pegawai'] : '-'; ?>">
                                  </td>
                                  <td>
                                    <label><span>Golongan</span></label>
                                    <input class="form-control golongan" readonly name="golongan[]" type="text" required="" value="<?= $peserta[$value]['golongan'] != '' ? $peserta[$value]['golongan'] : '-'; ?>">
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
                                    <button type="button" class="btn btn-sm btn-block btn-light m-0 p-0" onclick="sinadiToggleHideDataPegawai(this)">Tambah daftar data pegawai</button>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <label><span>Tanggal Berangkat</span></label>
                                    <input class="form-control" type="date" name="tgl_pergi[]" required="" value="<?= $peserta[$value]['tgl_pergi']; ?>">
                                  </td>
                                  <td>
                                    <label><span>Tanggal Kembali</span></label>
                                    <input class="form-control" type="date" name="tgl_pulang[]" required="" value="<?= $peserta[$value]['tgl_pulang']; ?>">
                                  </td>
                                  <td>
                                    <label><span>Kota/ Kabupaten Asal</span></label>
                                    <input class="form-control" type="text" name="kota_asal[]" placeholder="Isi kota asal" required value="<?= $peserta[$value]['kota_asal']; ?>">
                                  </td>
                                  <td>
                                    <label><span>Kota/ Kabupaten Tujuan</span></label>
                                    <input type="hidden" value="<?= $peserta[$value]['kota']; ?>" name="kota_tujuan[]">
                                    <input class="form-control" type="text" name="kota[]" placeholder="Isi kota tujuan" required value="<?= $peserta[$value]['kota']; ?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="1">
                                    <label><span>No. Surat Tugas</span></label>
                                    <input class="form-control" type="text" name="no_surat_tugas[]" required="" value="<?= $peserta[$value]['no_surat_tugas']; ?>">
                                  </td>
                                  <td colspan="1">
                                    <label><span>Tgl. Surat Tugas</span></label>
                                    <input class="form-control" type="date" name="tgl_surat_tugas[]" required="" value="<?= $peserta[$value]['tgl_surat_tugas']; ?>">
                                    <br>
                                  </td>
                                  <td colspan="1">
                                    <label><span>No. SPPD</span></label>
                                    <input class="form-control" type="text" name="no_sppd[]" required value="<?= $peserta[$value]['no_sppd']; ?>">
                                  </td>
                                  <td colspan="1">
                                    <label><span>Tgl. SPPD</span></label>
                                    <input class="form-control" type="date" name="tgl_sppd[]" required value="<?= $peserta[$value]['tgl_sppd']; ?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="4">
                                    <label><span>Alat angkutan yang dipergunakan</span></label>
                                    <select class="form-control select2" type="text" name="transportasi[]" data-pegawai="transportasi[]" required>
                                      <option value="">--Pilih jenis Transportasi--</option>
                                      <?php if ($peserta[$value]['transportasi'] == "Kendaraan umum"): ?>
                                        <option value="Kendaraan umum" selected disabled="disabled">Kendaraan umum</option>
                                      <?php endif; ?>
                                      <option value="Transportasi udara" <?= ($peserta[$value]['transportasi'] == "Transportasi udara") ? 'selected' : ''; ?>>Transportasi udara</option>
                                      <option value="Transportasi darat" <?= ($peserta[$value]['transportasi'] == "Transportasi darat") ? 'selected' : ''; ?>>Transportasi darat</option>
                                      <option value="Transportasi laut" <?= ($peserta[$value]['transportasi'] == "Transportasi laut") ? 'selected' : ''; ?>>Transportasi laut</option>
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
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran transport_hari" type="text" name="transport_hari[]" placeholder="0" value="<?= $peserta[$value]['transport_hari']; ?>">
                                  </td>
                                  <td class="text-center">
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran transport_transport" type="text" name="transport_nominal[]" placeholder="0" value="<?= $peserta[$value]['transport_nominal']; ?>">
                                  </td>
                                  <td class="text-center">
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran harian_hari" type="text" name="harian_hari[]" placeholder="0" value="<?= $peserta[$value]['harian_hari']; ?>">
                                  </td>
                                  <td class="text-center">
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran harian_unit_cost" type="text" name="harian_nominal[]" placeholder="0" value="<?= $peserta[$value]['harian_nominal']; ?>">
                                  </td>
                                  <td class="text-center">
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran penginapan_kali" type="text" name="penginapan_hari[]" placeholder="0" value="<?= $peserta[$value]['penginapan_hari']; ?>">
                                  </td>
                                  <td class="text-center">
                                    <input onload="sinadiPerhitunganAnggaran(this)" class="form-control anggaran penginapan_unit_cost" type="text" name="penginapan_nominal[]" placeholder="0" value="<?= $peserta[$value]['penginapan_nominal']; ?>">
                                  </td>
                                  <td class="text-center align-bottom" colspan="3" rowspan="2">
                                    <input class="form-control jumlah_transfer" type="text" name="jumlah_transfer[]" placeholder="Jumlah" readonly required value="<?= $peserta[$value]['jumlah_transfer']; ?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td class="text-center" colspan="2">
                                    <input class="form-control transport_jumlah" type="text" name="transport_jumlah[]" placeholder="Jumlah" readonly required value="<?= $peserta[$value]['transport_jumlah']; ?>">
                                  </td>
                                  <td class="text-center" colspan="2">
                                    <input class="form-control harian_jumlah" type="text" name="harian_jumlah[]" placeholder="Jumlah" readonly required value="<?= $peserta[$value]['harian_jumlah']; ?>">
                                  </td>
                                  <td class="text-center" colspan="2">
                                    <input class="form-control penginapan_jumlah" type="text" name="penginapan_jumlah[]" placeholder="Jumlah" readonly required value="<?= $peserta[$value]['penginapan_jumlah']; ?>">
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
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
                          <td><input type="text" disabled class="form-control" name="" value="<?= $peserta[0]['alias_kantor']; ?>" /></td>
                          <td><input type="text" disabled class="form-control" name="" value="<?= $peserta[0]['nama_kantor']; ?>" /></td>
                        </tr>
                        <tr>
                          <td><label><span>Kepala Kantor</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required="" style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $peserta[0]['kepala_kantor_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Pejabat Pembuat Komitmen</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required="" style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $peserta[0]['ppk_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>Kepala Sub Bagian Adm Umum</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required="" style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $peserta[0]['kasubag_adum_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>BENDAHARA PENGELUARAN</span></label></td>
                          <td colspan="2">
                            <select disabled class="form-control select2" type="text" name="" required="" style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php foreach ($pegawai as $data) : ?>
                                <option <?= $data['nip'] == preg_replace('/\D/', '', $peserta[0]['bendahara_nip']) ? 'selected' : ''; ?> value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>"><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><label><span>PIC</span></label></td>
                          <td colspan="2">
                            <select class="form-control select2 pic" type="text" name="sinadi_pic" required="" style="width: 100%;">
                              <option value="">--NIP/NOPEG | NAMA--</option>
                              <?php
                              foreach ($pegawai as $data) : ?>
                                <option value="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-jabatan="<?= $data['jabatan']; ?>" data-golongan="<?= $data['golongan']; ?>" <?= $perjalanan['pic_nip'] == $data['nip'] ? 'selected' : ''; ?>><?= $perjalanan['pic_nip'] != $data['nip'] ? $data['nip'] . ' | ' . $data['nama'] : $perjalanan['pic_nip'] . ' | ' . $perjalanan['pic']; ?></option>
                              <?php endforeach; ?>
                              <?php if ($findPegawai == false) : ?>
                                <option value="<?= $peserta[0]['nip_pegawai']; ?>" data-nama="<?= $peserta[0]['nama_pegawai']; ?>" data-jabatan="<?= $peserta[0]['jabatan_pegawai']; ?>" data-golongan="<?= $peserta[0]['golongan']; ?>" selected><?= $peserta[0]['nip_pegawai']; ?> | <?= $peserta[0]['nama_pegawai']; ?></option>
                              <?php endif; ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><input type="hidden" name="pic_nama" value="<?= $perjalanan['pic']; ?>"></td>
                          <td colspan="2"><input type="hidden" name="pic_nip" value="<?= $perjalanan['pic_nip']; ?>"></td>
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
<?= $this->endSection(); ?>