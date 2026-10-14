<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->

<!-- Main content -->
<style>
  .plus {
    cursor: pointer;
    top: 0;
    right: 1px;
    height: 1em;
    width: 1em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1em;
    content: "+";
    background-color: #0275d8;
  }

  .min {
    cursor: pointer;
    top: 0;
    right: 1px;
    height: 1em;
    width: 1em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1em;
    content: "-";
    background-color: #d33333;
  }
</style>

<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="row">
      <div class="col-md-12">

        <div class="card card-default">
          <!-- form start -->
          <form id="tambahDataCuti" action="/cuti/tambah_cuti" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Buat Data Cuti Baru</h3>
              <div class="text-right">
                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Format penulisan tanggal 'Tahun/Bulan/Tanggal' Perhitungan lamanya cuti tidak disertakan dengan hari Sabtu dan Minggu, dan jika ada tanggal merah selain hari Sabtu dan Minggu tolong sertakan tombol (+) untuk menghindari kesalahan perhitungan"></i>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>

                <div class="col-sm-10">
                  <?php if ($getThisPegawai) : ?>
                    <input type="hidden" name="pegawai" value="<?= $getThisPegawai; ?>">
                  <?php endif; ?>
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
                    Masa kerja akan diakumulasikan dari tanggal pengajuan pada surat
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
                <div class="col"></div>
                <label class="has-float-label col-sm-10">
                  <input type="text" name="no_telp" class="form-control" id="no_telp" placeholder="Tuliskan nomor telepon aktif" value="<?= old('no_telp'); ?>">
                  <span>Nomor Telepon</span>
                </label>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Cuti</label>

                <div class="col-sm-5">
                  <select class="form-control select2" name="jenis_cuti" id="jenis_cuti" style="width:100%;">
                    <option value=""> --Pilih Jenis Cuti-- </option>
                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                    <option value="Cuti Besar">Cuti Besar</option>
                    <option value="Cuti Sakit">Cuti Sakit</option>
                    <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                    <option value="Cuti Karena Alasan Penting">Cuti Karena Alasan Penting</option>
                    <option value="Cuti di Luar Tanggungan Negara">Cuti di Luar Tanggungan Negara</option>
                  </select>
                </div>
                <div class="col-sm-5">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="bukti_lampiran" value="" id="validatedCustomFileCuti">
                    <label class="custom-file-label" for="validatedCustomFileCuti">Lampirkan bukti cuti (pastikan pdf/jpg/jpeg/png) max 10MB</label>
                    <small class="form-text text-muted">Upload bukti untuk jenis Cuti "Cuti Sakit" dan "Cuti Karena Alasan Penting".</small>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <label class="has-float-label col-sm-10">
                  <textarea name="alasan_cuti" class="form-control" rows="3" id="alasan_cuti" placeholder="Tuliskan alasan anda" value="<?= old('alasan_cuti'); ?>"></textarea>
                  <span>Alasan Cuti</span>
                </label>
              </div>
              <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <input type="hidden" name="" value="1" id="dateRange-value">
                </div>
              </div>
              <div class="form-group row" id="hitunganWaktuRange">
                <label class="col-sm-2"><span>Lamanya Cuti</span><span class="plus">+</span></label>
                <div class="col-sm-10 row">
                  <label class="has-float-label col-5">
                    <input type="text" name="rentan_waktu[]" data-val="1" value="" class="form-control dateRange" id="" placeholder="Selama" value="<?= old('rentan_waktu[]'); ?>">
                    <span>Rentan Waktu</span>
                  </label>
                  <label class="col-7">
                    <span class="form-control selamaView1" id="" readonly>1 (hari/<strike>bulan/tahun)</strike></span>
                    <input type="hidden" name="selama[]" class="form-control selama1" id="" readonly placeholder="(hari/bulan/tahun)" value="<?= old('selama[]') ? old('selama[]') : '1'; ?>">
                    <span></span>
                  </label>
                </div>
              </div>

              <div class="form-group row" id="hitunganWaktuCutiMelahirkan">
                <label class="col-sm-2">Lamanya Cuti</label>
                <div class="col-sm-10 row">
                  <label class="has-float-label col-5">
                    <div class="input-group dates" id="dates" data-target-input="nearest">
                      <input type="text" name="rentan_waktu[]" disabled="disabled" class="form-control datetimepicker-input" data-target="#dates" data-toggle="datetimepicker" />
                      <div class="input-group-append" data-target="#dates" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </label>
                  <label class=" col-7">
                    <span class="form-control selamaView1" id="" readonly>3 <strike>(hari</strike>/bulan/<strike>tahun)</strike></span>
                    <input type="hidden" name="selamaCutiMelahirkan" class="form-control selama1" id="" readonly placeholder="(hari/bulan/tahun)" value="90">
                    <span></span>
                  </label>
                </div>
              </div>

              <div class="multiple-column">

              </div>

              <div class="form-group row">
                <label class="col-sm-2">Alamat</label>

                <label class="has-float-label col-sm-10">
                  <textarea name="alamat_cuti" class="form-control" rows="3" id="alamat_cuti" placeholder="Tuliskan alamat selama menjalankan cuti" value="<?= old('alamat_cuti'); ?>"></textarea>
                  <span>Alamat selama menjalankan cuti</span>
                </label>
              </div>
              <div class="form-group row">
                <label class="col-sm-2">Tanggal Pengajuan</label>
                <div class="col-sm-10 row">
                  <label class="has-float-label col-5">
                    <div class="input-group date" id="date" data-target-input="nearest">
                      <input type="text" name="tanggal_pengajuan" class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" />
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
                    <option value=""> --KEPEGAWAIAN/ATASAN/PEJABAT-- </option>
                    <?php foreach ($skema as $data) : ?>
                      <?php if ($data['jenis_surat'] == 'cuti') : ?>
                        <option value="<?= $data['id_skema_titimangsa']; ?>" data-jabatan-kepegawaian="<?= $data['jabatan_kepegawaian']; ?>" data-nama-kepegawaian="<?= $data['nama_kepegawaian']; ?>" data-nip-kepegawaian="<?= $data['nip_kepegawaian']; ?>" data-jabatan-atasan="<?= $data['jabatan_atasan']; ?>" data-nama-atasan="<?= $data['nama_atasan']; ?>" data-nip-atasan="<?= $data['nip_atasan']; ?>" data-jabatan-pejabat="<?= $data['jabatan_pejabat']; ?>" data-nama-pejabat="<?= $data['nama_pejabat']; ?>" data-nip-pejabat="<?= $data['nip_pejabat']; ?>" data-unit-kerja="<?= $data['unit_kerja']; ?>" data-lokasi=<?= $data['lokasi']; ?>>
                          <div class="d-block"><?= $data['nama_kepegawaian']; ?>, <?= $data['nama_atasan']; ?> dan <?= $data['nama_pejabat']; ?>
                        </option>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <div class="col"></div>
                <div class="col-sm-3">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_kepegawaian" class="form-control" readonly required id="jabatan_kepegawaian" placeholder="Jabatan" value="<?= old('jabatan_kepegawaian'); ?>">
                    <span>Bagian Kepegawaian</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_kepegawaian" class="form-control" readonly required id="nama_kepegawaian" placeholder="Nama" value="<?= old('nama_kepegawaian'); ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_kepegawaian" class="form-control" readonly required id="nip_kepegawaian" placeholder="NIP" value="<?= old('nip_kepegawaian'); ?>">
                    <span>NIP</span>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_atasan" class="form-control" readonly required id="jabatan_atasan" placeholder="Jabatan" value="<?= old('jabatan_atasan'); ?>">
                    <span>A.n Kasubbag ADUM</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_atasan" class="form-control" readonly required id="nama_atasan" placeholder="Nama" value="<?= old('nama_atasan'); ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_atasan" class="form-control" readonly required id="nip_atasan" placeholder="NIP" value="<?= old('nip_atasan'); ?>">
                    <span>NIP</span>
                  </label>
                </div>
                <div class="col-sm-4">
                  <label class="has-float-label">
                    <input type="text" name="jabatan_pejabat" class="form-control" readonly required id="jabatan_pejabat" placeholder="Jabatan" value="<?= old('jabatan_pejabat'); ?>">
                    <span>Kepala Kantor</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nama_pejabat" class="form-control" readonly required id="nama_pejabat" placeholder="Nama" value="<?= old('nama_pejabat'); ?>">
                    <span>Nama</span>
                  </label>
                  <label class="has-float-label">
                    <input type="text" name="nip_pejabat" class="form-control" readonly required id="nip_pejabat" placeholder="NIP" value="<?= old('nip_pejabat'); ?>">
                    <span>NIP</span>
                  </label>
                </div>
              </div>
              <div class="form-group row">
                <div class="col"></div>
                <div class="col-sm-7">
                  <label class="has-float-label">
                    <input type="text" name="unit_kerja_skema" class="form-control" readonly required id="unit_kerja_skema" placeholder="Jabatan" value="<?= old('unit_kerja_skema'); ?>">
                    <span>Unit Kerja</span>
                  </label>
                </div>
                <div class="col-sm-3">
                  <label class="has-float-label">
                    <input type="text" name="lokasi_skema" class="form-control" readonly required id="lokasi_skema" placeholder="Jabatan" value="<?= old('lokasi_skema'); ?>">
                    <span>Lokasi</span>
                  </label>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-sm-2">
                  Kepala Biro OSDM
                  <sup class="text-warning"><br />*Cuti Karena Alasan Penting </sup>
                </label>
                <div class="col-sm-10">
                  <div class="row">
                    <input required disabled type="text" name="osdm_nama" id="osdm_nama" placeholder="Nama OSDM >> Pastikan tanda titik (.) dan koma (,) tepat pada nama <<" class="col-6 form-control" value="-">
                    <input required disabled type="text" name="osdm_nip" id="osdm_nip" placeholder="NIP OSDM" class="col-6 form-control" value="-">
                  </div>
                  <small class="form-text text-muted">Isi bagian ini jika memilih Cuti Alasan Penting. <br /></small>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2">
                  Koordinator Tempat Mengetahui
                  <sup class="text-red">*Dinonaktifkan</sup>
                </label>
                <div class="col-sm-10">
                  <select class="form-control select2" readonly name="koordinator_tempat" id="koordinator_tempat" style="width: 100%;" required>
                    <option value="0">Tanpa Koordinator</option>
                    <?php // foreach ($pegawai as $data) : 
                    ?>
                    <!--<option value="<? //= $data['id_data_pegawai']; 
                                        ?>"><? //= $data['nip']; 
                                            ?> | <? //= $data['nama']; 
                                                  ?></option>-->
                    <?php // endforeach; 
                    ?>
                  </select>
                  <small class="form-text text-muted">Pastikan koordinator yang terpilih tidak termasuk pada "Titimangsa". <br /> Anda dapat memilih opsi teratas "Tanpa Koordinator" jika nama ada pada skema Titimangsa.</small>
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
  $(document).on('change', '#jenis_cuti', function() {
    var jenisCutiValue = $(this).val();
    if (jenisCutiValue === 'Cuti Karena Alasan Penting' || jenisCutiValue === 'Cuti Sakit') {
      $('input[type="file"]').attr('required', true);
    } else {
      $('input[type="file"]').removeAttr('required');
    }

    if (jenisCutiValue === 'Cuti Karena Alasan Penting') {
      $('#osdm_nama').attr('required', true);
      $('#osdm_nip').attr('required', true);
      $('#osdm_nama').removeAttr('disabled');
      $('#osdm_nip').removeAttr('disabled');
    } else {
      $('#osdm_nama').removeAttr('required');
      $('#osdm_nip').removeAttr('required');
      $('#osdm_nama').attr('disabled', true);
      $('#osdm_nip').attr('disabled', true);
    }
  });
</script>
<!-- Load validasi cuti script -->
<script src="<?= base_url('js/validasi_cuti.js'); ?>"></script>
<?= $this->endSection(); ?>