<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<style>
  .floating-submit-btn {
    position: fixed;
    top: 55px;
    right: 50px;
    z-index: 1000;
    display: flex;
    align-items: center;
    background-color: #17a2b8;
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 2s ease;

    /* Efek Bayangan untuk kesan 3D */
    box-shadow: 0 10px 16px rgba(0, 0, 0, 0.25), inset 1px 2px 2px rgb(72 71 71 / 25%);

    /* Agar tombol sedikit "mengangkat" saat dihover */
  }

  .floating-submit-btn:hover {
    background-color: #138496;
    transform: translateY(-3px);
    /* Efek angkat ke atas */
    box-shadow: 0 10px 16px rgba(0, 0, 0, 0.25);
  }

  /* Tambahkan efek tekan saat diklik */
  .floating-submit-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .floating-submit-btn i {
    font-size: 18px;
    transition: margin 2s ease;
  }

  .floating-submit-btn span.text-kirim {
    display: none;
    margin-left: 8px;
    white-space: nowrap;
  }

  /* Tampilan desktop atau tablet */
  @media (min-width: 768px) {
    .floating-submit-btn:hover span.text-kirim {
      display: inline;
    }

    .floating-submit-btn:hover i {
      margin-right: 8px;
    }
  }

  /* Tampilan smartphone */
  @media (max-width: 767px) {
    .floating-submit-btn span.text-kirim {
      display: inline;
      margin-left: 8px;
    }
  }
</style>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Data Perjalanan Dinas <u><?= $getPeserta[0]['kota'] != $getPeserta[0]['kota_asal'] ? "Luar Kota" : "Dalam Kota"; ?></u></h3>
            <button type="button" class="btn btn-tool float-right" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-xl-5">
                <div class="card">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <!-- Akses Halaman -->
                      <li class="list-group-item list-group-item-disabled-hover">
                        Nama Kegiatan Perjalanan Dinas
                        <strong class="float-right"><?= $getData['nama_kegiatan']; ?></strong>
                      </li>
                      <li class="list-group-item list-group-item-disabled-hover">
                        Tanggal Pelaksanaan
                        <strong class="float-right"><?= $getData['tgl_pelaksanaan'] == $getData['tgl_pelaksanaan_akhir'] ? mediumdate_indo($getData['tgl_pelaksanaan']) : mediumdate_indo($getData['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($getData['tgl_pelaksanaan_akhir']); ?></strong>
                      </li>
                      <li class="list-group-item list-group-item-disabled-hover">
                        Tempat Perjalanan Dinas
                        <strong class="float-right"><?= $getData['tempat_perjalanan']; ?></strong>
                      </li>
                      <li class="list-group-item list-group-item-disabled-hover">
                        Mata Anggaran
                        <strong class="float-right"><?= $getData['mata_anggaran']; ?></strong>
                      </li>
                      <li class="list-group-item list-group-item-disabled-hover">
                        Sumber Anggaran
                        <strong class="float-right"><?= isset($getData['sumber_anggaran']) ? $getData['sumber_anggaran'] : 'APBN'; ?></strong>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><strong>Nota Dinas</strong></h3>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <!-- Akses Halaman -->
                      <?php foreach ($getPeserta as $data): ?>
                        <li class="list-group-item list-group-item-disabled-hover py-0">
                          <?= name_capitalize($data['nama_pegawai']); ?>
                          <a href="#" data-file="<?= $data['file'] == null ? '#' : 'nota-dinas/' . $data['file']; ?>" class="btn btn-xs float-right <?= $data['file'] == null ? 'btn-secondary disabled' : 'btn-outline-danger'; ?> fileNotaDinas"><i class="fas fa-file-pdf"></i></a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-xl-7">
                <h3 class="card-title mb-2"><strong><u>Berkas Perjalanan Dinas</u></strong></h3>
                <?php if($getData['berkas'] == NULL || $getData['berkas'] == ""): ?>
                    <h3 class="card-title mb-2"><strong class="text-danger"><u>Berkas Perjalanan Dinas Belum Terlampir/ Gagal dimuat! Silahkan melakukan pengeditan data dan tambahkan ulang berkas!</u></strong></h3>
                <?php endif ?>
                <a href="#" data-file="<?= '/sinadi/previewBuktiBerkas/' . $getData['berkas']; ?>" class="btn float-right btn-sm mb-1 btn-info fileNotaDinas"><i class="fas fa-eye"></i></a>
                <iframe
                  src="<?= base_url('sinadi/previewBuktiBerkas/' . $getData['berkas']); ?>"
                  width="100%"
                  height="600px"
                  title="Pratinjau File">
                </iframe>
                <div class="text-center">
                    <a href="/sinadi/nominatif/<?= $getData['id_perjalanan']; ?>" class="btn btn-danger btn-block my-2">Nominatif</a>
                    <a href="/sinadi/kuitansi-riil/<?= $getData['id_perjalanan']; ?>" class="btn btn-danger btn-block my-2">Kuitansi dan Riil</a>
                    <a href="/sinadi/sppd/<?= $getData['id_perjalanan']; ?>" class="btn btn-danger btn-block my-2">SPPD</a>
                    <a href="#" data-toggle="modal" data-target="#modalSppdSrikandi" class="btn btn-success btn-block my-2">SPPD Srikandi</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Data Peserta</h3>
        <button type="button" class="btn btn-tool float-right" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body">
        <div class="row">
          <?php foreach ($getPeserta as $data): ?>
            <div class="col-md-6 p-2 py-3 <?= ($data['ttd_peserta'] == null) ? 'table-danger' : ''; ?>">
              <div class="container-fluid border">
                <ul class="list-group list-group-flush">
                  <!-- Akses Halaman -->
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Nama Pegawai
                    <strong class="float-right"><?= $data['nama_pegawai']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    NIP.
                    <strong class="float-right"><?= $data['nip_pegawai']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Jabatan
                    <strong class="float-right"><?= $data['jabatan_pegawai']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Tanggal Perjalanan
                    <strong class="float-right"><?= $data['tgl_pergi'] == $data['tgl_pulang'] ? mediumdate_indo($data['tgl_pergi']) : mediumdate_indo($data['tgl_pergi'])  . ' s.d. ' . mediumdate_indo($data['tgl_pulang']); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Kota
                    <strong class="float-right"><?= $data['kota'] != $data['kota_asal'] ? $data['kota_asal'] . ' ke ' . $data['kota'] : $data['kota']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    No. Surat Tugas
                    <strong class="float-right"><?= $data['no_surat_tugas']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Tgl. Surat Tugas
                    <strong class="float-right"><?= mediumdate_indo($data['tgl_surat_tugas']); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    No. SPPD
                    <strong class="float-right"><?= $data['no_sppd']; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Tgl. SPPD
                    <strong class="float-right"><?= $data['tgl_sppd'] ? mediumdate_indo($data['tgl_sppd']) : ""; ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Transportasi
                    <strong class="float-right">Rp <?= number_format(($data['transport_jumlah']), '2', ',', '.'); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Uang Harian
                    <strong class="float-right">Rp <?= number_format(($data['harian_jumlah']), '2', ',', '.'); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Penginapan
                    <strong class="float-right">Rp <?= number_format(($data['penginapan_jumlah']), '2', ',', '.'); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    Total
                    <strong class="float-right">Rp <?= number_format(($data['jumlah_transfer']), '2', ',', '.'); ?></strong>
                  </li>
                  <li class="list-group-item list-group-item-disabled-hover py-0">
                    <div class="float-right" style="width: 30%;">
                      <?php if ($data['ttd_peserta'] == null): ?>
                        <p class="text-danger">Belum ada TTD</p>
                      <?php else: ?>
                        <img src="<?= $data['ttd_peserta']; ?>" class="border preview-image" alt="TTD" style="width: 100%;"
                          data-base64="<?= $data['ttd_peserta']; ?>">
                      <?php endif; ?>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Data Verifikator</h3>
        <button type="button" class="btn btn-tool float-right" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body">
      <?php if(true): ?>
        <div class="row">
          <?php if($getDataVerifikator['verifikator_1'] != null): ?>
          <div class="col-xl-4 border-bottom">
              <h6 class="text-center font-weight-bold border-bottom border-primary pb-2">Verifikator 1</h6>
              <?php if($getData['verifikator_1'] != null): ?>
              <?php $verifikator = unserialize($getData['verifikator_1']); ?>
                <div class="timeline">
                  <?php foreach ($verifikator as $entry): ?>
                    <?php if (isset($entry['time'])): // Langsung array valid ?>
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-blue"><?= date("j M Y", strtotime($entry['time'])); ?></span>
                      </div>
                
                      <!-- timeline item -->
                      <div>
                        <?php
                        $status = $entry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($entry['time'])); ?></span>
                          <h3 class="timeline-header"><?= htmlspecialchars($entry['status']); ?></h3>
                          <div class="timeline-body">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($entry['nama']); ?></p>
                            <p><strong>NIP:</strong> <?= htmlspecialchars($entry['nip']); ?></p>
                            <p>
                              <strong>Keterangan:</strong> 
                              <?= !empty($entry['keterangan']) ? htmlspecialchars($entry['keterangan']) : 'Tidak ada keterangan'; ?>
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                
                    <?php elseif (is_array($entry) && isset($entry[0]['time'])): // Jika nested array ?>
                      <?php foreach ($entry as $subEntry): ?>
                        <?php if (isset($subEntry['time'])): ?>
                          <!-- timeline time label -->
                          <div class="time-label">
                            <span class="bg-blue"><?= date("j M Y", strtotime($subEntry['time'])); ?></span>
                          </div>
                
                          <!-- timeline item -->
                          <div>
                            <?php
                        $status = $subEntry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                            <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($subEntry['time'])); ?></span>
                              <h3 class="timeline-header"><?= htmlspecialchars($subEntry['status']); ?></h3>
                              <div class="timeline-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($subEntry['nama']); ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($subEntry['nip']); ?></p>
                                <p>
                                  <strong>Keterangan:</strong> 
                                  <?= !empty($subEntry['keterangan']) ? htmlspecialchars($subEntry['keterangan']) : 'Tidak ada keterangan'; ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END timeline item -->
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline">
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if($getDataVerifikator['verifikator_2'] != null): ?>
          <div class="col-xl-4 border-bottom">
              <h6 class="text-center font-weight-bold border-bottom border-primary pb-2">Verifikator 2</h6>
              <?php if($getData['verifikator_2'] != null): ?>
              <?php $verifikator = unserialize($getData['verifikator_2']); ?>
              <div class="timeline">
                  <?php foreach ($verifikator as $entry): ?>
                    <?php if (isset($entry['time'])): // Langsung array valid ?>
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-blue"><?= date("j M Y", strtotime($entry['time'])); ?></span>
                      </div>
                
                      <!-- timeline item -->
                      <div>
                        <?php
                        $status = $entry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($entry['time'])); ?></span>
                          <h3 class="timeline-header"><?= htmlspecialchars($entry['status']); ?></h3>
                          <div class="timeline-body">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($entry['nama']); ?></p>
                            <p><strong>NIP:</strong> <?= htmlspecialchars($entry['nip']); ?></p>
                            <p>
                              <strong>Keterangan:</strong> 
                              <?= !empty($entry['keterangan']) ? htmlspecialchars($entry['keterangan']) : 'Tidak ada keterangan'; ?>
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                
                    <?php elseif (is_array($entry) && isset($entry[0]['time'])): // Jika nested array ?>
                      <?php foreach ($entry as $subEntry): ?>
                        <?php if (isset($subEntry['time'])): ?>
                          <!-- timeline time label -->
                          <div class="time-label">
                            <span class="bg-blue"><?= date("j M Y", strtotime($subEntry['time'])); ?></span>
                          </div>
                
                          <!-- timeline item -->
                          <div>
                            <?php
                        $status = $subEntry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                            <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($subEntry['time'])); ?></span>
                              <h3 class="timeline-header"><?= htmlspecialchars($subEntry['status']); ?></h3>
                              <div class="timeline-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($subEntry['nama']); ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($subEntry['nip']); ?></p>
                                <p>
                                  <strong>Keterangan:</strong> 
                                  <?= !empty($subEntry['keterangan']) ? htmlspecialchars($subEntry['keterangan']) : 'Tidak ada keterangan'; ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END timeline item -->
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline">
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if($getDataVerifikator['verifikator_3'] != null): ?>
          <div class="col-xl-4 border-bottom">
              <h6 class="text-center font-weight-bold border-bottom border-primary pb-2">Verifikator 3</h6>
              <?php if($getData['verifikator_3'] != null): ?>
              <?php $verifikator = unserialize($getData['verifikator_3']); ?>
              <div class="timeline">
                  <?php foreach ($verifikator as $entry): ?>
                    <?php if (isset($entry['time'])): // Langsung array valid ?>
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-blue"><?= date("j M Y", strtotime($entry['time'])); ?></span>
                      </div>
                
                      <!-- timeline item -->
                      <div>
                        <?php
                        $status = $entry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($entry['time'])); ?></span>
                          <h3 class="timeline-header"><?= htmlspecialchars($entry['status']); ?></h3>
                          <div class="timeline-body">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($entry['nama']); ?></p>
                            <p><strong>NIP:</strong> <?= htmlspecialchars($entry['nip']); ?></p>
                            <p>
                              <strong>Keterangan:</strong> 
                              <?= !empty($entry['keterangan']) ? htmlspecialchars($entry['keterangan']) : 'Tidak ada keterangan'; ?>
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                
                    <?php elseif (is_array($entry) && isset($entry[0]['time'])): // Jika nested array ?>
                      <?php foreach ($entry as $subEntry): ?>
                        <?php if (isset($subEntry['time'])): ?>
                          <!-- timeline time label -->
                          <div class="time-label">
                            <span class="bg-blue"><?= date("j M Y", strtotime($subEntry['time'])); ?></span>
                          </div>
                
                          <!-- timeline item -->
                          <div>
                            <?php
                            $status = $subEntry['status']; // pastikan $entry sudah terdefinisi
                            
                            if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                                $icon = 'fas fa-info-circle';
                                $color = 'info';
                            } elseif ($status == 'Perlu Revisi') {
                                $icon = 'fas fa-exclamation-triangle';
                                $color = 'warning';
                            } elseif ($status == 'Disetujui') {
                                $icon = 'fas fa-check-circle';
                                $color = 'success';
                            } elseif ($status == 'Ditolak') {
                                $icon = 'fas fa-times-circle';
                                $color = 'danger';
                            } else {
                                $icon = 'fas fa-question-circle';
                                $color = 'secondary';
                            }
                            ?>
                            
                            <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                            <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($subEntry['time'])); ?></span>
                              <h3 class="timeline-header"><?= htmlspecialchars($subEntry['status']); ?></h3>
                              <div class="timeline-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($subEntry['nama']); ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($subEntry['nip']); ?></p>
                                <p>
                                  <strong>Keterangan:</strong> 
                                  <?= !empty($subEntry['keterangan']) ? htmlspecialchars($subEntry['keterangan']) : 'Tidak ada keterangan'; ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END timeline item -->
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline">
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if($getDataVerifikator['verifikator_4'] != null): ?>
          <div class="col-xl-4 border-bottom">
              <h6 class="text-center font-weight-bold border-bottom border-primary pb-2">Verifikator 3</h6>
              <?php if($getData['verifikator_4'] != null): ?>
              <?php $verifikator = unserialize($getData['verifikator_4']); ?>
              <div class="timeline">
                  <?php foreach ($verifikator as $entry): ?>
                    <?php if (isset($entry['time'])): // Langsung array valid ?>
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-blue"><?= date("j M Y", strtotime($entry['time'])); ?></span>
                      </div>
                
                      <!-- timeline item -->
                      <div>
                        <?php
                        $status = $entry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($entry['time'])); ?></span>
                          <h3 class="timeline-header"><?= htmlspecialchars($entry['status']); ?></h3>
                          <div class="timeline-body">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($entry['nama']); ?></p>
                            <p><strong>NIP:</strong> <?= htmlspecialchars($entry['nip']); ?></p>
                            <p>
                              <strong>Keterangan:</strong> 
                              <?= !empty($entry['keterangan']) ? htmlspecialchars($entry['keterangan']) : 'Tidak ada keterangan'; ?>
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                
                    <?php elseif (is_array($entry) && isset($entry[0]['time'])): // Jika nested array ?>
                      <?php foreach ($entry as $subEntry): ?>
                        <?php if (isset($subEntry['time'])): ?>
                          <!-- timeline time label -->
                          <div class="time-label">
                            <span class="bg-blue"><?= date("j M Y", strtotime($subEntry['time'])); ?></span>
                          </div>
                
                          <!-- timeline item -->
                          <div>
                            <?php
                            $status = $subEntry['status']; // pastikan $entry sudah terdefinisi
                            
                            if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                                $icon = 'fas fa-info-circle';
                                $color = 'info';
                            } elseif ($status == 'Perlu Revisi') {
                                $icon = 'fas fa-exclamation-triangle';
                                $color = 'warning';
                            } elseif ($status == 'Disetujui') {
                                $icon = 'fas fa-check-circle';
                                $color = 'success';
                            } elseif ($status == 'Ditolak') {
                                $icon = 'fas fa-times-circle';
                                $color = 'danger';
                            } else {
                                $icon = 'fas fa-question-circle';
                                $color = 'secondary';
                            }
                            ?>
                            
                            <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                            <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($subEntry['time'])); ?></span>
                              <h3 class="timeline-header"><?= htmlspecialchars($subEntry['status']); ?></h3>
                              <div class="timeline-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($subEntry['nama']); ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($subEntry['nip']); ?></p>
                                <p>
                                  <strong>Keterangan:</strong> 
                                  <?= !empty($subEntry['keterangan']) ? htmlspecialchars($subEntry['keterangan']) : 'Tidak ada keterangan'; ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END timeline item -->
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline">
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if($getDataVerifikator['verifikator_5'] != null): ?>
          <div class="col-xl-4 border-bottom">
              <h6 class="text-center font-weight-bold border-bottom border-primary pb-2">Verifikator 3</h6>
              <?php if($getData['verifikator_5'] != null): ?>
              <?php $verifikator = unserialize($getData['verifikator_5']); ?>
              <div class="timeline">
                  <?php foreach ($verifikator as $entry): ?>
                    <?php if (isset($entry['time'])): // Langsung array valid ?>
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-blue"><?= date("j M Y", strtotime($entry['time'])); ?></span>
                      </div>
                
                      <!-- timeline item -->
                      <div>
                        <?php
                        $status = $entry['status']; // pastikan $entry sudah terdefinisi
                        
                        if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                            $icon = 'fas fa-info-circle';
                            $color = 'info';
                        } elseif ($status == 'Perlu Revisi') {
                            $icon = 'fas fa-exclamation-triangle';
                            $color = 'warning';
                        } elseif ($status == 'Disetujui') {
                            $icon = 'fas fa-check-circle';
                            $color = 'success';
                        } elseif ($status == 'Ditolak') {
                            $icon = 'fas fa-times-circle';
                            $color = 'danger';
                        } else {
                            $icon = 'fas fa-question-circle';
                            $color = 'secondary';
                        }
                        ?>
                        
                        <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($entry['time'])); ?></span>
                          <h3 class="timeline-header"><?= htmlspecialchars($entry['status']); ?></h3>
                          <div class="timeline-body">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($entry['nama']); ?></p>
                            <p><strong>NIP:</strong> <?= htmlspecialchars($entry['nip']); ?></p>
                            <p>
                              <strong>Keterangan:</strong> 
                              <?= !empty($entry['keterangan']) ? htmlspecialchars($entry['keterangan']) : 'Tidak ada keterangan'; ?>
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                
                    <?php elseif (is_array($entry) && isset($entry[0]['time'])): // Jika nested array ?>
                      <?php foreach ($entry as $subEntry): ?>
                        <?php if (isset($subEntry['time'])): ?>
                          <!-- timeline time label -->
                          <div class="time-label">
                            <span class="bg-blue"><?= date("j M Y", strtotime($subEntry['time'])); ?></span>
                          </div>
                
                          <!-- timeline item -->
                          <div>
                            <?php
                            $status = $subEntry['status']; // pastikan $entry sudah terdefinisi
                            
                            if (in_array($status, ['Diajukan', 'Dalam Verifikasi'])) {
                                $icon = 'fas fa-info-circle';
                                $color = 'info';
                            } elseif ($status == 'Perlu Revisi') {
                                $icon = 'fas fa-exclamation-triangle';
                                $color = 'warning';
                            } elseif ($status == 'Disetujui') {
                                $icon = 'fas fa-check-circle';
                                $color = 'success';
                            } elseif ($status == 'Ditolak') {
                                $icon = 'fas fa-times-circle';
                                $color = 'danger';
                            } else {
                                $icon = 'fas fa-question-circle';
                                $color = 'secondary';
                            }
                            ?>
                            
                            <i class="<?= $icon ?> bg-<?= $color ?>"></i>
                            <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> <?= date("H:i", strtotime($subEntry['time'])); ?></span>
                              <h3 class="timeline-header"><?= htmlspecialchars($subEntry['status']); ?></h3>
                              <div class="timeline-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($subEntry['nama']); ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($subEntry['nip']); ?></p>
                                <p>
                                  <strong>Keterangan:</strong> 
                                  <?= !empty($subEntry['keterangan']) ? htmlspecialchars($subEntry['keterangan']) : 'Tidak ada keterangan'; ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END timeline item -->
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline">
                  <!-- timeline end tag -->
                  <div>
                    <i class="fas fa-clock bg-gray"></i>
                  </div>
                </div>
              <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
      To be continued...
      <?php endif; ?>
      </div>
    </div>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Verifikator</h3>
        <button type="button" class="btn btn-tool float-right" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body">
        <form action="/sinadi/konfirmasiDataSurat" method="post">
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" require name="id_sinadi_lembar_verifikasi" class="form-control" value="<?= $lembarVerif ? $lembarVerif['id_sinadi_lembar_verifikasi'] : ""; ?>">

              <div class="form-group">
                <label>Tanggal</label>
                <input type="date" require name="tgl" class="form-control" value="<?= $lembarVerif ? $lembarVerif['tgl'] : date('Y-m-d'); ?>">
              </div>
              <div class="form-group">
                <label>NO. SPBY</label>
                <input type="text" require name="no_spby" class="form-control" value="<?= $lembarVerif ? $lembarVerif['no_spby'] : ""; ?>">
              </div>
              <div class="form-group">
                <label>NAMA SUPPLIER</label>
                <select name="suplier[]" required class="select2 form-control" multiple="multiple" style="width: 100%;" readonly>
                  <?php foreach ($getPeserta as $peserta): ?>
                    <option value="<?= $peserta['nama_pegawai']; ?>" selected><?= $peserta['nama_pegawai']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>JENIS TAGIHAN/SUMBER DANA</label>
                <input type="text" require name="tagihan" class="form-control" value="<?= $lembarVerif ? $lembarVerif['tagihan'] : ""; ?>">
              </div>
              <div class="form-group">
                <label>URAIAN KEGIATAN</label>
                <textarea class="form-control" name="uraian" rows="3" required><?= $lembarVerif ? $lembarVerif['uraian'] : ""; ?></textarea>
              </div>
              <div class="form-group">
                <label>NILAI TAGIHAN</label>
                <input type="number" require name="total_tagihan" class="form-control" value="<?= $getData['total_perjalanan']; ?>">
              </div>
              <?php if ($lembarVerif): ?>
                <input type="hidden" value="<?= $lembarVerif['jenis_daftar_periksa']; ?>" name="jenis_daftar_periksa">
                <div class="form-group mb-0 row">
                  <label class="col-sm-12 col-form-label"><?= $lembarVerif['jenis_daftar_periksa']; ?></label>
                </div>
                <?php
                $array = unserialize($lembarVerif['daftar_periksa']);
                foreach ($array as $getArray): ?>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="<?= $getArray['list']; ?>">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="<?= $getArray['list']; ?>" <?= $getArray['value']; ?> id="value_1">
                      <label class="form-check-label pl-3" for="value_1">
                        <?= $getArray['list']; ?>
                      </label>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <?php if ($getPeserta[0]['kota'] != $getPeserta[0]['kota_asal']): ?>
                  <!-- Luar Kota -->
                  <input type="hidden" value="PERJALANAN DINAS LUAR KOTA" name="jenis_daftar_periksa">
                  <div class="form-group mb-0 row">
                    <label class="col-sm-12 col-form-label">PERJALANAN DINAS LUAR KOTA</label>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Nominatif">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Nominatif" id="value_1">
                      <label class="form-check-label pl-3" for="value_1">
                        Nominatif
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Rincian Biaya Perjalanan Dinas">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Rincian Biaya Perjalanan Dinas" id="value_2">
                      <label class="form-check-label pl-3" for="value_2">
                        Rincian Biaya Perjalanan Dinas
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Surat Pengeluaran Riil">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Surat Pengeluaran Riil" id="value_3">
                      <label class="form-check-label pl-3" for="value_3">
                        Surat Pengeluaran Riil
                      </label>
                    </div>
                  </div>
                  <?php $sumber = isset($getData['sumber_anggaran']) ? $getData['sumber_anggaran'] : 'APBN'; ?>
                  <?php if ($sumber === 'APBN'): ?>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="SPPD yang telah di tandatangai Kepala Balai, Instansi Tujuan dan PPK">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="SPPD yang telah di tandatangai Kepala Balai, Instansi Tujuan dan PPK" id="value_4">
                      <label class="form-check-label pl-3" for="value_4">
                        SPPD yang telah di tandatangai Kepala Balai, Instansi Tujuan dan PPK
                      </label>
                    </div>
                  </div>
                  <?php endif; ?>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Bukti pembayaran penginapan">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Bukti pembayaran penginapan" id="value_5">
                      <label class="form-check-label pl-3" for="value_5">
                        Bukti pembayaran penginapan
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Bukti pengeluaran transportasi">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Bukti pengeluaran transportasi" id="value_6">
                      <label class="form-check-label pl-3" for="value_6">
                        Bukti pengeluaran transportasi
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Surat Tugas">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Surat Tugas" id="value_7">
                      <label class="form-check-label pl-3" for="value_7">
                        Surat Tugas
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Surat Undangan Kegiatan">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Surat Undangan Kegiatan" id="value_8">
                      <label class="form-check-label pl-3" for="value_8">
                        Surat Undangan Kegiatan
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Laporan dan Dokumentasi Kegiatan dilengkapi dengan Geotag">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Laporan dan Dokumentasi Kegiatan dilengkapi dengan Geotag" id="value_9">
                      <label class="form-check-label pl-3" for="value_9">
                        Laporan dan Dokumentasi Kegiatan dilengkapi dengan Geotag
                      </label>
                    </div>
                  </div>
                <?php else: ?>
                  <!-- Dalam Kota -->

                  <input type="hidden" value="PERJALANAN DINAS DALAM KOTA/TRANSLOK" name="jenis_daftar_periksa">
                  <div class="form-group mb-1 mb-0 row">
                    <label class="col-sm-12 col-form-label">PERJALANAN DINAS DALAM KOTA/TRANSLOK</label>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Nominatif">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Nominatif" id="value_1">
                      <label class="form-check-label pl-3" for="value_1">
                        Nominatif
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Rincian Biaya/Kuitansi">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Rincian Biaya/Kuitansi" id="value_2">
                      <label class="form-check-label pl-3" for="value_2">
                        Rincian Biaya/Kuitansi
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Surat Pengeluaran Riil">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Surat Pengeluaran Riil" id="value_3">
                      <label class="form-check-label pl-3" for="value_3">
                        Surat Pengeluaran Riil
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Surat Tugas yang telah di tandatangani dan stempel Instansi Tujuan">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Surat Tugas yang telah di tandatangani dan stempel Instansi Tujuan" id="value_4">
                      <label class="form-check-label pl-3" for="value_4">
                        Surat Tugas yang telah di tandatangani dan stempel Instansi Tujuan
                      </label>
                    </div>
                  </div>
                  <div class="form-group mb-1">
                    <input type="hidden" readonly name="list[]" class="form-control" value="Laporan dan Dokumentasi Kegiatan">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="value[]" value="Laporan dan Dokumentasi Kegiatan" id="value_5">
                      <label class="form-check-label pl-3" for="value_5">
                        Laporan dan Dokumentasi Kegiatan
                      </label>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  <!--  -->
                  <input type="hidden" name="id_perjalanan" readonly class="" value="<?= $getData['id_perjalanan']; ?>">
                  <input type="hidden" name="id_data_pegawai" readonly class="" value="<?= $myData['id_data_pegawai']; ?>">
                  <input type="hidden" name="verifikatorKe" readonly class="" value="<?= $verifikatorKe; ?>">
                  <div class="form-group mb-0 row">
                    <label class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                      <input type="text" readonly name="nama" class="form-control-plaintext" value="<?= session()->get('nama'); ?>">
                    </div>
                  </div>
                  <div class="form-group mb-0 row">
                    <label class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly name="nip" class="form-control-plaintext" value="<?= $myData['nip']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Status Validasi</label>
                    <div class="form-contro">
                      <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-warning text-dark">
                          <input type="radio" name="status" required value="Perlu Revisi"> Perlu Revisi
                        </label>
                        <label class="btn btn-outline-success">
                          <input type="radio" name="status" required value="Disetujui"> Disetujui
                        </label>
                        <label class="btn btn-outline-danger">
                          <input type="radio" name="status" required value="Ditolak"> Ditolak
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Keterangan Validasi</label>
                    <textarea class="form-control" value="keterangan" name="keterangan" rows="3" required></textarea>
                  </div>

                  <div class="form-group">
                    <label>Tanda Tangan</label>
                    <div class="signature-wrapper">
                      <!-- Canvas Tanda Tangan -->
                      <div class="row">
                        <div class="col">
                          <div class="">
                            <canvas id="signature-pad" style="border: 1px solid #ccc; border-radius: 0.5rem;" class="signature-pad"></canvas>
                            <input type="hidden" id="signature" name="signature">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-block p-2">
                            <button type="button" class="btn btn-success" id="change-color">
                              Ubah Warna
                            </button>
                          </div>
                          <div class="d-block p-2">
                            <button type="button" class="btn btn-danger" id="clear">
                              <span class="fas fa-eraser"></span>
                              Bersihkan
                            </button>
                          </div>
                        </div>
                      </div>


                    </div>
                  </div>
                  <button type="submit" class="btn m-1 btn-primary float-right"><i class="fas fa-save pr-2"></i> Simpan Formulir</button>
                  <!--  -->
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- Modal SPPD Srikandi -->
<div class="modal fade" id="modalSppdSrikandi" role="dialog" aria-labelledby="modalSppdSrikandiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="modalSppdSrikandiLabel">Cetak SPPD Srikandi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formSppdSrikandi" onsubmit="event.preventDefault();">
        <input type="hidden" id="id_perjalanan_srikandi" value="<?= $getData['id_perjalanan']; ?>">
        <div class="modal-body">
          <div class="alert alert-info" style="font-size: 14px;">
            <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Kosongkan form ini dan langsung klik <b>Cetak SPPD Srikandi</b> jika belum ada Pejabat Tujuan.
          </div>
          <div class="form-group">
            <label for="jabatan_tujuan">Jabatan Tujuan</label>
            <input type="text" class="form-control" id="jabatan_tujuan" name="jabatan_tujuan" placeholder="Contoh: Kepala Dinas Kesehatan...">
          </div>
          <div class="form-group">
            <label for="pegawai_tujuan">Pilih Pegawai dari Database (Nama & NIP)</label>
            <select class="form-control select2" id="pegawai_tujuan" name="pegawai_tujuan" style="width: 100%;" onchange="toggleManualInput(this)">
              <option value="">-- Pilih Pegawai --</option>
              <option value="manual">>> Ketik Manual <<</option>
              <?php if(isset($all_pegawai)): ?>
                <?php foreach($all_pegawai as $peg): ?>
                  <option value="<?= htmlspecialchars($peg['nama'], ENT_QUOTES, 'UTF-8') . '|' . htmlspecialchars($peg['nip'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?= htmlspecialchars($peg['nama'], ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($peg['nip'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <div id="manual_input_wrapper" style="display: none;">
            <div class="form-group">
              <label for="nama_tujuan_manual">Nama Pegawai (Manual)</label>
              <input type="text" class="form-control" id="nama_tujuan_manual" name="nama_tujuan_manual" placeholder="Contoh: Dr. Budi Santoso">
            </div>
            <div class="form-group">
              <label for="nip_tujuan_manual">NIP Pegawai (Manual)</label>
              <input type="text" class="form-control" id="nip_tujuan_manual" name="nip_tujuan_manual" placeholder="Contoh: 198001012005011001">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" onclick="cetakSppdSrikandi()">Cetak SPPD Srikandi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#pegawai_tujuan').select2({
    dropdownParent: $('#modalSppdSrikandi')
  });
  
  $('#pegawai_tujuan').on('change', function() {
    toggleManualInput(this);
  });
});

function toggleManualInput(selectObj) {
  var manualWrapper = document.getElementById('manual_input_wrapper');
  if (selectObj.value === 'manual') {
    manualWrapper.style.display = 'block';
  } else {
    manualWrapper.style.display = 'none';
  }
}

function cetakSppdSrikandi() {
  var id = $('#id_perjalanan_srikandi').val();
  var jabatan = $('#jabatan_tujuan').val();
  var pegawai = $('#pegawai_tujuan').val();
  var nama_manual = $('#nama_tujuan_manual').val();
  var nip_manual = $('#nip_tujuan_manual').val();
  
  var url = '<?= base_url('sinadi/sppd') ?>/' + id + '/borang?jabatan_tujuan=' + encodeURIComponent(jabatan) + '&pegawai_tujuan=' + encodeURIComponent(pegawai);
  
  if (pegawai === 'manual') {
      url += '&nama_tujuan_manual=' + encodeURIComponent(nama_manual) + '&nip_tujuan_manual=' + encodeURIComponent(nip_manual);
  }
  
  window.open(url, '_blank');
  $('#modalSppdSrikandi').modal('hide');
}
</script>

</section>
<?php if ($activeMenu != "sinadiBlockir"): ?>
  <script>
    $(".fileNotaDinas").on('click', function() {

      var file = $(this).data('file');

      if (file !== "#") {
        var fullUrl = '<?= base_url(); ?>/' + file;
        // var iframeSrc = 'https://drive.google.com/viewerng/viewer?embedded=true&url=' + encodeURIComponent(fullUrl);

        $("#modal-xl").find('.modal-body').html(
          '<iframe src="' + fullUrl + '" frameborder="1" style="width: 100%; height: 80vh;"></iframe>'
        );
        $("#modal-xl").modal('show');
      }
    });
  </script>
  <script></script>
<?php endif; ?>

<?= $this->endSection(); ?>