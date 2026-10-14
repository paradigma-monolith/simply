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
                    <div class="card card-primary">
                      <div class="card-header">
                        <!-- <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3> -->
                      </div>
                      <form id="updateValidationCuti" action="/cuti/validation_detail_permohonan" method="post">
                        <div class="card-body">
                          <?php
                          $updateAs = '';
                          if ($dataCuti['kepegawaian']) :
                            $updateAs = 'kepegawaian';
                          elseif ($dataCuti['atasan']) :
                            $updateAs = 'atasan';
                          else : $updateAs = '';
                          endif;
                          ?>
                          <input type="hidden" name="updateAs" value="<?= $updateAs; ?>">
                          <div class="card-body pb-0">
                            <input type="hidden" name="id_cuti_data_permohonan" value="<?= $dataCuti['id_cuti_data_permohonan']; ?>">
                            <div class="form-group">
                              <label>Status Validation</label>
                              <select class="form-control select2" name="status" id="status" style="width: 100%;">
                                <option value="1"> Pending </option>
                                <option value="2"> Disetujui </option>
                                <option value="3"> Dikembalikan </option>
                                <option value="4"> Ditolak </option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="keterangan">Keterangan</label>
                              <textarea rows="3" name="keterangan" class="form-control" id="keterangan"><?= old('keterangan'); ?></textarea>
                            </div>
                          </div>

                          <div class="card-body pt-0">
                            <label class="">TTD</label>
                            <div class="">
                              <!-- canvas tanda tangan  -->
                              <canvas id="signature-pad" style="border: 1px solid #ccc; border-radius: 0.5rem;" class="signature-pad"></canvas>
                              <input type="hidden" id="signature" name="signature">
                            </div>

                            <!-- tombol ganti warna  -->
                            <button type="button" class="btn btn-success" id="change-color">
                              Ubah Warna
                            </button>

                            <!-- tombol undo  -->
                            <!-- <button type="button" class="btn btn-dark" id="undo">
                              <span class="fas fa-undo"></span>
                              Kembalikan
                            </button> -->

                            <!-- tombol hapus tanda tangan  -->
                            <button type="button" class="btn btn-danger" id="clear">
                              <span class="fas fa-eraser"></span>
                              Bersihkan
                            </button>
                          </div>
                          <!-- /.card-body -->
                          <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
                          </div>
                      </form>
                    </div>
                    <!-- /.card -->
                  </div>

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
                  <iframe class="previewPDF" src="/cuti/pdfView/permohonan/<?= $dataCuti['id_cuti_data_permohonan']; ?>" width="100%" height="400"></iframe>
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