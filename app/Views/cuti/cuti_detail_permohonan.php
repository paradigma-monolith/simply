<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header row">
            <div class="col-6">
              <!-- <h3>Data cuti yang belum diproses</h3> -->
            </div>
            <div class="col-6 text-right">
              <!-- <h3><span class="badge badge-pill badge-warning"></span></h3> -->
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">NIP/NOPEG</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?= $dataCuti['nip']; ?>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Nama Lengkap</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?= $dataCuti['nama']; ?>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Jabatan</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?= $dataCuti['jabatan']; ?>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Golongan</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?= $dataCuti['golongan']; ?>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Masa Kerja</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?php
                        if (isset($dataCuti['masa_kerja'])) {
                          // $waktu = explode("|", $dataCuti['masa_kerja']);
                          $waktuSelisih = $dataCuti['masa_kerja'];
                          $waktu[0] =  floor($waktuSelisih / 365);
                          $sisa_hari = $waktuSelisih % 365;
                          $waktu[1] = floor($sisa_hari / 30);
                          $sisa_hari = $waktuSelisih % 30;
                          $waktu[2] = $sisa_hari;

                          echo $waktu[0] != 0 ? $waktu[0] . " Tahun" : '';
                          echo $waktu[1] != 0 ? $waktu[1] . " Bulan" : '';
                          echo $waktu[2] != 0 ? $waktu[2] . " Hari" : '';
                          if ($waktu[0] == 0 && $waktu[1] == 0 && $waktu[2] == 0) {
                            echo 'Tidak diketahui';
                          }
                        } else {
                          echo '-';
                        }
                        ?>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Unit Kerja</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <?= $dataCuti['unit_kerja_pegawai']; ?>
                      </div>
                    </div>
                    <hr>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Jenis Cuti</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $dataCuti['jenis_permohonan']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Alasan Cuti</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $dataCuti['alasan_permohonan']; ?>
                          </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <h6 class="mb-0">Pertimbangan <?= $dataCuti['jabatan_atasan']; ?></h6>
                          </div>
                          <div class="col-sm-12 text-secondary">
                            <span class="d-block"><?= $dataCuti['nama_atasan']; ?></span>
                            <span class="d-block">NIP. <?= $dataCuti['nip_atasan']; ?></span>
                          </div>
                        </div>
                        <hr>
                        <hr>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">

                  <div class="col-md-12">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <h6 class="mb-0">Komentar</h6>
                          </div>
                          <div class="col-sm-12 text-secondary">
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-12 text-secondary">
                            <span class="d-block">
                              <?= $dataCuti['nama_kepegawaian']; ?> : <?= $dataCuti['ket_permohonan_validation_kepegawaian'] == '' ? 'Tidak ada komentar' : $dataCuti['ket_permohonan_validation_kepegawaian']; ?>
                            </span>
                            <span class="d-block">
                              <?= $dataCuti['nama_atasan']; ?> : <?= $dataCuti['ket_permohonan_validation_atasan'] == '' ? 'Tidak ada komentar' : $dataCuti['ket_permohonan_validation_atasan']; ?>
                            </span>
                          </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3">
                      <div class="card-body row">
                        <div class="col-10">
                          <?php if ($dataCuti['id_cuti_status_kepegawaian'] == 2 && $dataCuti['id_cuti_status_atasan'] == 2) : ?>
                            <h3>Download Dokumen: </h3>
                          <?php else : ?>
                            <h3><strike>Download</strike> Dokumen: </h3>
                          <?php endif; ?>
                        </div>
                        <div class="col-2">
                          <?php if ($dataCuti['id_cuti_status_kepegawaian'] == 2 && $dataCuti['id_cuti_status_atasan'] == 2) : ?>
                            <form action="/cuti/pdf/permohonan/<?= $dataCuti['id_cuti_data_permohonan']; ?>" method="post" class="d-inline">
                              <?= csrf_field(); ?>
                              <input type="hidden" name="_method" value="DELETE">
                              <button type="submit" class="btn btn-lg bg-gradient-success"><i class="fas fa-file-alt"></i></button>
                            </form>
                          <?php else : ?>
                            <span class="btn btn-lg bg-gradient-secondary"><i class="fas fa-file-alt"></i></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <!-- /.card -->
                  </div>

                  <div class="col-md-12">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="lds-ring">
                          <div></div>
                          <div></div>
                          <div></div>
                          <div></div>
                        </div>
                        <?php if ($dataCuti['id_cuti_status_kepegawaian'] == 2 && $dataCuti['id_cuti_status_atasan'] == 2) : ?>
                          <iframe class="previewPDF" src="/cuti/pdfView/permohonan/<?= $dataCuti['id_cuti_data_permohonan']; ?>" width="100%" height="400"></iframe>
                        <?php else : ?>
                          Tidak dapat menampilkan preview
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>