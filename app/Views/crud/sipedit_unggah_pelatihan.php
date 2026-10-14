<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card card-default">
          <form id="tambahSipeditArsip" action="/sipedit/simpan_data<?= isset($getArsip['id_sipedit_arsip']) ? '/' . $getArsip['id_sipedit_arsip'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getArsip['id_sipedit_arsip']) ? "Ubah Data Pelatihan" : 'Unggah Pelatihan Baru'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-10">
                      <select class="form-control select2" name="pegawai" id="pegawai" style="width:100%;" readonly>
                        <option value="">-- Pilih Data Pegawai --</option>
                        <?php if (isset($getArsip['nama'])) : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= $data_pegawai['nip'] == $getArsip['nip'] ? 'selected="true"' : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($by) ? ($data_pegawai['nip'] == $by['nip'] ? 'selected="true"' : '') : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </select>
                    </div>
                    <div class="col-2">
                      <div class="form-check text-center">
                        <input class="form-check-input" type="checkbox" id="selectPegawai" <?= session()->get('role') == "Admin" ? '' : 'disabled'; ?>>
                        <label class="form-check-label" for="selectPegawai">
                          Administrator
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-2">

                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama Pelatihan</label>
                <div class="col-sm-10">
                  <input type="text" name="nama_pelatihan" class="form-control" id="nama_pelatihan" value="<?= isset($getArsip['arsip_kode']) ? $getArsip['arsip_kode'] : old("nama_pelatihan"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Lembaga Pelatihan</label>
                <div class="col-sm-10">
                  <input type="text" name="lembaga_pelatihan" class="form-control" id="lembaga_pelatihan" value="<?= isset($getArsip['arsip_nama']) ? $getArsip['arsip_nama'] : old("lembaga_pelatihan"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kategori</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="kategori" id="kategori" style="width:100%;">
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    if (isset($getArsip['id_sipedit_kategori'])) {
                      foreach ($kategori as $data) :
                    ?>
                        <option value="<?= $data['id_sipedit_kategori']; ?>" <?= $getArsip['id_sipedit_kategori'] == $data['id_sipedit_kategori'] ? 'selected' : ''; ?>><?= $data['kategori_nama']; ?></option>
                      <?php
                      endforeach;
                    } else {
                      foreach ($kategori as $data) :
                      ?>
                        <option value="<?= $data['id_sipedit_kategori']; ?>"><?= $data['kategori_nama']; ?></option>
                    <?php
                      endforeach;
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Pelaksanaan</label>
                <div class="col-sm-10">
                  <label class="has-float-label">
                    <div class="input-group">
                      <input type="text" name="tanggal" id="tanggal" class="form-control <?= isset($getArsip) ? 'range-date-data' : 'range-date'; ?>" data-start-date="<?= isset($getArsip) ? $getArsip['arsip_tanggal'] : ''; ?>" data-end-date="<?= isset($getArsip) ? $getArsip['arsip_selesai'] : ''; ?>">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                    </div>
                    <span>Periode</span>
                  </label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jumlah JPL/45 menit</label>
                <div class="col-sm-10">
                  <input type="text" name="jumlah_jpl" class="form-control" id="jumlah_jpl" value="<?= isset($getArsip['arsip_jpl']) ? $getArsip['arsip_jpl'] : old("jumlah_jpl"); ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Berkas Pelatihan</label>
                <div class="col-1 text-center">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input inputFile" for="unggah_berkas_pelatihan" id="customSwitch1" <?= isset($getArsip) ? '' : 'checked disabled'; ?>>
                    <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                  </div>
                </div>
                <div class="col-sm">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="unggah_berkas_pelatihan" id="unggah_berkas_pelatihan" onclick="handleInputFileChange(this)">
                    <label class="custom-file-label" for="unggah_berkas_pelatihan">Pilih File PDF</label>
                  </div>
                </div>
              </div>

            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="border p-2">
            <h6><strong>Keterangan</strong></h6>
            <ol>
<li><strong>Bentuk Pengembangan Kompetensi ASN</strong>
<ol type="a">
<li>Pengembangan kompetensi dilaksanakan dalam bentuk pendidikan dan/pelatihan.</li>
<li>Pengembangan kompetensi dalam bentuk pendidikan dilakukan melalui pemberian tugas belajar yang mekanismenya diatur dalam ketentuan tersendiri mengenai tugas belajar di lingkungan Kementerian Kesehatan.</li>
<li>Pengembangan kompetensi dalam bentuk pelatihan dilakukan melalui jalur pelatihan klasikal dan nonklasikal.</li>
<li>Pelatihan klasikal merupakan kegiatan pengembangan kompetensi yang dilakukan melalui kegiatan yang menekankan pada proses pembelajaran tatap muka di dalam kelas.</li>
<li>Bentuk pengembangan kompetensi melalui pelatihan <u>klasikal</u> dapat dilakukan melalui jalur:
<ul>
<li>pelatihan struktural kepemimpinan</li>
<li>pelatihan manajerial</li>
<li>pelatihan teknis</li>
<li>pelatihan fungsional</li>
<li>pelatihan sosial kultural</li>
<li>seminar/konferensi/sarasehan</li>
<li>workshop atau lokakarya</li>
<li>kursus</li>
<li>penataran</li>
<li>bimbingan teknis</li>
<li>sosialisasi dan/atau</li>
<li>jalur Pengembangan Kompetensi dalam bentuk pelatihan klasikal lainnya.</li>
</ul>
</li>
<li>Pelatihan non klasikal merupakan kegiatan pengembangan kompetensi yang dilakukan melalui kegiatan yang menekankan pada proses pembelajaran praktek kerja dan/atau pembelajaran di luar kelas.</li>
<li>Bentuk pengembangan kompetensi melalui pelatihan <u>nonklasikal</u> dapat dilakukan melalui jalur:
<ul>
<li>coaching</li>
<li>mentoring</li>
<li>e-learning</li>
<li>pelatihan jarak jauh</li>
<li>detasering (secondment)</li>
<li>pembelajaran alam terbuka (outbond)</li>
<li>patok banding (benchmarking)</li>
<li>pertukaran antara ASN dengan pegawai swasta/BUMN/BUMD</li>
<li>belajar mandiri (self development)</li>
<li>komunitas belajar (community of practices)</li>
<li>bimbingan di tempat kerja</li>
<li>magang/praktik kerja dan</li>
<li>jalur Pengembangan Kompetensi dalam bentuk pelatihan nonklasikal lainnya.</li>
</ul>
</li>
</ol>
</li>
<li><strong>Pelaksanaan Pengembangan Kompetensi ASN</strong>
<ol type="a">
<li>Setiap ASN berhak untuk mengikuti pengembangan kompetensi minimal sebanyak 20 (dua<br /> puluh) Jam Pelatihan setiap tahunnya.</li>
<li>Penghitungan pengembangan kompetensi melalui jalur pelatihan menggunakan satuan ukuran<br /> Jam Pelatihan JP yaitu 45 (empat puluh lima) menit setara dengan 1 (satu) JP.</li>
<li>Dalam hal tidak tercantum jumlah JP dalam kegiatan pengembangan kompetensi maka<br /> perhitungan JP mengacu pada tabel konversi sesuai dengan ketentuan Peraturan Lembaga<br /> Administrasi Negara Republik Indonesia Nomor 10 Tahun 2018.</li>
<li>Keikutsertaan dalam pelaksanaan pengembangan kompetensi dibuktikan dengan Sertifikat atau<br /> Surat Tanda Tamat Pelatihan (STTPL) atau sebutan lain sejenisnya.</li>
<li>Setiap ASN yang telah selesai mengikuti pengembangan kompetensi wajib memasukkan<br /> kegiatan pengembangan kompetensi yang diikutinya kedalam aplikasi Sistem Informasi<br /> Pendidikan dan Pelatihan (SIPEDIT).</li>
</ol>
</li>
</ol>
        </div>
    </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>