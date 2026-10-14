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
              <h4>Hallo
                <?= $dataCuti['kepegawaian'] == true ? name_capitalize($dataCuti['nama_kepegawaian']) : ''; ?>
                <?= $dataCuti['atasan'] == true ? name_capitalize($dataCuti['nama_atasan']) : ''; ?>
                <?= $dataCuti['pejabat'] == true ? name_capitalize($dataCuti['nama_pejabat']) : ''; ?>
                <?= $dataCuti['pejabat'] == false && $dataCuti['atasan'] == false && $dataCuti['kepegawaian'] == false  ? name_capitalize($dataCuti['koordinator']) : ''; ?>
              </h4>
            </div>
            <div class="col-6 text-right">
              <h3><span class="badge badge-pill badge-warning"><?= $dataCuti['status']; ?></span></h3>
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
                            <?= $dataCuti['jenis_cuti']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Alasan Cuti</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $dataCuti['alasan_cuti']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Lamanya Cuti</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?php
                            $tanggal = explode("|", $dataCuti['total_hari']);
                            $tanggal1 = explode("|", $dataCuti['periode1']);
                            $tanggal2 = explode("|", $dataCuti['periode2']);
                            for ($i = 0; $i < count($tanggal); $i++) :
                            ?>
                              <?php if ($tanggal[$i] < 30) : ?>
                                <span class="d-block"><?= $tanggal[$i]; ?> (hari/<strike>bulan/tahun</strike>)</span>
                              <?php elseif ($tanggal[$i] >= 30 && $tanggal[$i] < 365) : ?>
                                <span class="d-block"><?= $tanggal[$i]; ?> (<strike>hari</strike>/bulan/<strike>tahun</strike>)</span>
                              <?php else : ?>
                                <span class="d-block"><?= $tanggal[$i]; ?> (<strike>hari/bulan</strike>/tahun)</span>
                              <?php endif; ?>
                              <strong class="d-block"><?= $tanggal1[$i]; ?> - <?= $tanggal2[$i]; ?></strong>
                            <?php endfor; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Nomor Telp.</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $dataCuti['no_telp']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Alamat Selama Cuti</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $dataCuti['alamat_cuti']; ?>
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
                        <div class="row">
                          <div class="col-sm-12">
                            <h6 class="mb-0">Keputusan <?= $dataCuti['jabatan_pejabat']; ?></h6>
                          </div>
                          <div class="col-sm-12 text-secondary">
                            <span class="d-block"><?= $dataCuti['nama_pejabat']; ?></span>
                            <span class="d-block">NIP. <?= $dataCuti['nip_pejabat']; ?></span>
                          </div>
                        </div>
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
                            <h6 class="mb-0">Status Verifikasi</h6>
                          </div>
                          <div class="col-sm-12 text-secondary">
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-12 text-secondary">
                            <span class="d-block">
                              <?= $dataCuti['nama_kepegawaian']; ?> :
                              <?= $dataCuti['id_cuti_status_kepegawaian'] == 1 ? '<span class="badge badge-pill badge-warning">Pending</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_kepegawaian'] == 2 ? '<span class="badge badge-pill badge-success">Disetujui</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_kepegawaian'] == 3 ? '<span class="badge badge-pill badge-info">Dikembalikan</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_kepegawaian'] == 4 ? '<span class="badge badge-pill badge-danger">Ditolak</span>' : ''; ?>
                            </span>
                            <span class="d-block">
                              <?= $dataCuti['nama_atasan']; ?> :
                              <?= $dataCuti['id_cuti_status_atasan'] == 1 ? '<span class="badge badge-pill badge-warning">Pending</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_atasan'] == 2 ? '<span class="badge badge-pill badge-success">Disetujui</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_atasan'] == 3 ? '<span class="badge badge-pill badge-info">Dikembalikan</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_atasan'] == 4 ? '<span class="badge badge-pill badge-danger">Ditolak</span>' : ''; ?>
                            </span>
                            <span class="d-block">
                              <?= $dataCuti['nama_pejabat']; ?> :
                              <?= $dataCuti['id_cuti_status_pejabat'] == 1 ? '<span class="badge badge-pill badge-warning">Pending</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_pejabat'] == 2 ? '<span class="badge badge-pill badge-success">Disetujui</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_pejabat'] == 3 ? '<span class="badge badge-pill badge-info">Dikembalikan</span>' : ''; ?>
                              <?= $dataCuti['id_cuti_status_pejabat'] == 4 ? '<span class="badge badge-pill badge-danger">Ditolak</span>' : ''; ?>
                            </span>
                          </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-header">
                        <!-- <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3> -->
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <form id="" action="/cuti/validation_detail_cuti/koordinator" method="post">
                        <div class="card-body pb-0">
                          <?php
                          $updateAs = '';
                          if ($dataCuti['kepegawaian']) :
                            $updateAs = 'kepegawaian';
                          elseif ($dataCuti['atasan']) :
                            $updateAs = 'atasan';
                          elseif ($dataCuti['pejabat']) :
                            $updateAs = 'pejabat';
                          else : $updateAs = '';
                          endif;
                          ?>
                          <input type="hidden" name="updateAs" value="<?= $updateAs; ?>">
                          <input type="hidden" name="id_cuti_data_cuti" value="<?= $dataCuti['id_cuti_data_cuti']; ?>">

                          <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck123" required>
                              <label class="custom-control-label" for="exampleCheck123">Koordinator Tempat Mengetahui Permohonan Pengajuan Surat Cuti <a href="#">Dengan Data Yang Ada Pada Halaman Ini...</a>.</label>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                          <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Kirim ke Verifikator</button>
                        </div>
                      </form>
                    </div>
                    <!-- /.card -->
                  </div>
                </div>
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
                    <!-- /cuti/pdfView/<? //= $dataCuti['id_cuti_data_cuti']; 
                                        ?> -->
                    <iframe class="previewPDF" src="" width="100%" height="400"></iframe>
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


<section class="content">
  <div class="container-fluid">

    <!-- Timelime example  -->
    <div class="row">
      <div class="col-md-12">
        <!-- The time line -->
        <div class="timeline">
          <!-- timeline time label -->
          <?php $tanggal = '';
          foreach ($linimasa as $data) : ?>
            <?php if ($tanggal != $data['tanggal']) : $tanggal = $data['tanggal']; ?>
              <div class="time-label">
                <span class="bg-info"><?= $data['tanggal']; ?></span>
              </div>
            <?php endif; ?>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="<?= $data['icon']; ?>"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?= $data['jam']; ?></span>
                <h3 class="timeline-header"><a href="#"><?= $data['nama']; ?></a> <?= $data['aktivitas']; ?></h3>

                <?php if ($data['keterangan'] != '') : ?>
                  <div class="timeline-body">
                    <?= $data['keterangan']; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
          <!-- END timeline item -->
        </div>
      </div>
    </div>
  </div>
</section>


<?= $this->endSection(); ?>