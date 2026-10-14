<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Pemindahan Inventaris</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="<?= session()->get('role') == 'Admin' ? "#custom-tabs-one-profile" : ""; ?>" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pemeliharaan Inventaris <?= session()->get('role') == 'Admin' ? '' : '<i class="fas fa-ban text-danger"></i>'; ?></a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-one-tabContent">
          <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
            <!-- ============================================================================================================= -->
            <div class="timeline">
              <?php foreach ($pemindahan as $data) : ?>
                <div class="time-label">
                  <span class="bg-info"><?= $data['created_at']; ?></span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                  <?php if ($data['status'] == 'Ditolak') : ?>
                    <i class="fas fa-ban bg-danger"></i>
                    <div class="timeline-item">
                      <span class="time"><i class="fas fa-clock"></i> <?= selisi_waktu($data['created_at']); ?> lalu</span>
                      <h3 class="timeline-header"><a href="#">Seseorang</a> mencoba proses permintaan pemindahan barang inventaris</h3>
                    </div>
                  <?php else : ?>
                    <i class="fas fa-vote-yea bg-success"></i>
                    <div class="timeline-item">
                      <span class="time"><i class="fas fa-clock"></i> <?= selisi_waktu($data['created_at']); ?> lalu</span>
                      <h3 class="timeline-header"><a href="#"><?= name_capitalize($data['nama']); ?></a> melakukan pemindahan barang inventaris</h3>

                      <div class="timeline-body">
                        <?php
                        $array = unserialize($data['data_barang']);
                        ?>
                        <?php
                        $validationNumber = 0;
                        $dataLabel = ['Kode Satker', 'Kode Barang', 'NUP', 'Nama Barang', 'Merek'];
                        ?>
                        <?php foreach ($array as $key => $value) : ?>
                          <?php if ($validationNumber == 5) : ?>
                            <hr style="border-top: 3px dotted rgba(0, 0, 0, 0.5);" />
                          <?php break;
                          endif; ?>
                          <?php if (in_array($key, $dataLabel)) :
                            $validationNumber++;
                          ?>
                            <span><?= $key; ?></span> : <strong><?= $value; ?></strong><br />
                          <?php endif; ?>
                        <?php endforeach; ?>
                        <table class="table table-sm table-bordered table-stripped">
                          <tr>
                            <th>Tempat Asal</th>
                            <th>Tempat Dituju</th>
                          </tr>
                          <?php
                          $data_asal = unserialize($data['data_asal']);
                          $data_tujuan = unserialize($data['data_tujuan']);
                          ?>
                          <?php foreach ($data_asal as $key => $value) : ?>
                            <tr>
                              <td><?= $value; ?></td>
                              <td><?= $data_tujuan[$key]; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </table>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
            <!-- ============================================================================================================= -->
          </div>
          <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
            <table id="tableWithoutExporting" class="table table-sm table-bordered table-stripped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Data Inventaris</th>
                  <th>Status</th>
                  <th>Validasi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (session()->get('role') == 'Admin') : ?>
                  <?php $nomor = 1;
                  foreach ($pemeliharaan as $data) : ?>
                    <tr>
                      <td><?= $nomor++; ?></td>
                      <td>
                        <?php
                        $array = unserialize($data['data_barang']);
                        ?>
                        <?php
                        $validationNumber = 0;
                        $dataLabel = ['Kode Satker', 'Kode Barang', 'NUP', 'Nama Barang', 'Merek'];
                        ?>
                        <?php foreach ($array as $key => $value) : ?>
                          <?php if ($validationNumber == 5) : ?>
                            <!-- <hr style="border-top: 3px dotted rgba(0, 0, 0, 0.5);" /> -->
                          <?php break;
                          endif; ?>
                          <?php if (in_array($key, $dataLabel)) :
                            $validationNumber++;
                          ?>
                            <span><?= $key; ?></span> : <strong><?= $value; ?></strong><br />
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </td>
                      <td><?= $data['status']; ?></td>
                      <td><?= $data['validasi']; ?></td>
                      <td><a href="/abs/pengajuan_pemeliharaan/<?= $data['id_abs_pengajuan_pemeliharaan']; ?>" class="btn btn-sm btn-info"><i class="fas fa-angle-double-right"></i></a></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
</section>

<?= $this->endSection(); ?>