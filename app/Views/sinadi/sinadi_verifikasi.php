<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->

<section class="content">
  <div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-5">
            <div class="card card-primary card-outline shadow-sm">
              <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-user-check mr-2 text-primary"></i>Selamat Datang, <?= name_capitalize(session()->get('nama')); ?></h3>
              </div>
              <div class="card-body">
                <p class="mb-2 text-muted"><i class="fas fa-info-circle mr-1"></i> Anda memiliki akses sebagai:</p>
                <ul class="pl-3 list-group list-group-flush">
                  <?php foreach ($verifikator as $data): ?>
                    <li class="list-group-item border-0 pl-2 py-1"><i class="fas fa-check-circle text-success mr-2"></i><strong><?= $data['status']; ?></strong></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card card-info card-outline shadow-sm">
              <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-users mr-2 text-info"></i>Daftar Verifikator <?= ($tipe=='APBN' ? 'APBN' : ($tipe=='RPL' ? 'RPL' : '')); ?></h3>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                  <?php
                  $i = 1;
                  foreach ($daftarVerifikator as $item): ?>
                      <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2"><i class="fas fa-user-shield mr-1 text-secondary"></i> <?= esc($item['verifikator']) ?> <?= $item['verifikator'] == "Verifikator 1" || $item['verifikator'] == "Verifikator Upload Bukti Pembayaran" ? "<small class='text-muted'>(salah satunya)</small>" : "" ; ?></h6>
                        <?php if ($item['anggota']): ?>
                            <ul class="mb-3 list-unstyled pl-3">
                                <?php foreach ($item['anggota'] as $p): ?>
                                    <li class="mb-1"><i class="fas fa-angle-right text-secondary mr-1"></i> <?= esc($p['nama']) ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-3 pl-3"><i class="fas fa-exclamation-circle mr-1"></i> Belum ditentukan</p>
                        <?php endif ?>
                    <?php if($i++ == 3): ?>
                    </div>
                    <div class="col-md-6">
                    <?php endif; ?>
                <?php endforeach ?>
                </div>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="d-flex align-items-center mb-3 justify-content-between">
      <div class="btn-group" role="group" aria-label="Submenu Validasi">
        <a href="/sinadi/verifikasiSurat?tipe=APBN" class="btn btn-sm <?= ($tipe=='APBN'?'btn-primary':'btn-outline-primary'); ?>">
          <i class="fas fa-coins mr-1"></i> APBN
          <span class="badge badge-light ml-1"><?= isset($getDataAPBN) ? count($getDataAPBN) : 0; ?></span>
        </a>
        <a href="/sinadi/verifikasiSurat?tipe=RPL" class="btn btn-sm <?= ($tipe=='RPL'?'btn-success':'btn-outline-success'); ?>">
          <i class="fas fa-hand-holding-usd mr-1"></i> RPL
          <span class="badge badge-light ml-1"><?= isset($getDataRPL) ? count($getDataRPL) : 0; ?></span>
        </a>
      </div>
      <small class="text-muted">Status yang ditampilkan: Diajukan, Dalam Verifikasi, Perlu Revisi.</small>
    </div>
    <?php if($tipe=='APBN'): ?>
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-primary card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-check-circle mr-2 text-primary"></i>Verifikator Pertama</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-primary">Tahap 1</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataAPBN as $data): ?>
                  <?php if ($data['status_surat'] == "Diajukan"): 
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("1", $urut_verif) ? "/sinadi/verifikasi_data/1/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("1", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("1", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-info card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-check-double mr-2 text-info"></i>Verifikator Kedua</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-info">Tahap 2</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $time = "2022-01-01 01:01:01";
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataAPBN as $data):
                  $time_1 = $time;
                  $time_2 = $time;
                  if ($data['verifikator_1'] != null) {
                    $array1 = unserialize($data['verifikator_1']);
                    $time_1 = $array1[count($array1) - 1]['time'];
                  }
                  if ($data['verifikator_2'] != null) {
                    $array2 = unserialize($data['verifikator_2']);
                    $time_2 = $array2[count($array2) - 1]['time'];
                  }
                ?>
                  <?php if ($data['status_surat'] == "Dalam Verifikasi" && ($time_1 >= $time_2)):
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("2", $urut_verif) ? "/sinadi/verifikasi_data/2/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("2", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("2", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-success card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-clipboard-check mr-2 text-success"></i>Verifikator Ketiga</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-success">Tahap 3</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $array1 = null;
                $array2 = null;
                $array3 = null;
                $ada_data = false;
                foreach ($getDataAPBN as $data):
                  $time_1 = $time;
                  $time_2 = $time;
                  if ($data['verifikator_1'] != null) {
                    $array1 = unserialize($data['verifikator_1']);
                    $time_1 = $array1[count($array1) - 1]['time'];
                  }
                  if ($data['verifikator_2'] != null) {
                    $array2 = unserialize($data['verifikator_2']);
                    $time_2 = $array2[count($array2) - 1]['time'];
                  }
                ?>
                  <?php if ($data['status_surat'] == "Dalam Verifikasi" && ($time_1 < $time_2)):
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("3", $urut_verif) ? "/sinadi/verifikasi_data/3/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("3", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("3", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-warning card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-money-check-alt mr-2 text-warning"></i>Verifikator Upload Bukti Pembayaran</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-warning text-white">Bukti Pembayaran</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="25%">Kegiatan</th>
                  <th width="20%">Tanggal</th>
                  <th width="20%">Tujuan</th>
                  <th width="10%">Status</th>
                  <th width="10%">Bukti</th>
                  <th width="10%">Lembar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataAllAPBN as $data): 
                  $ada_data = true;
                ?>
                <tr>
                  <td class="text-center align-middle"><?= $nomor++; ?></td>
                  <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                  <td class="align-middle">
                    <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                  </td>
                  <td class="align-middle">
                    <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                  </td>
                  <td class="text-center align-middle">
                      <?php if ($data['status_surat'] == "Draft"): ?>
                        <span class="badge badge-secondary"><i class="fas fa-pencil-alt mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Diajukan"): ?>
                        <span class="badge badge-primary"><i class="fas fa-paper-plane mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Dalam Verifikasi"): ?>
                        <span class="badge badge-info"><i class="fas fa-spinner mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Perlu Revisi"): ?>
                        <span class="badge badge-warning"><i class="fas fa-edit mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Disetujui"): ?>
                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Ditolak"): ?>
                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group">
                      <a data-id="<?= $data['id_perjalanan']; ?>" href="javascript:void(0)" class="btn btn-xs <?= $data['bukti_pembayaran'] == null ? 'btn-warning' : 'btn-success' ; ?> <?= in_array("6", $urut_verif) ? "sinadi-set-pembayaran" : "disabled"; ?>" style="<?= in_array("6", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="<?= $data['bukti_pembayaran'] == null ? 'Upload Bukti' : 'Bukti Terupload' ; ?>" data-toggle="tooltip">
                      <?php if($data['bukti_pembayaran'] == null): ?>
                        <i class="fas fa-upload"></i>
                      <?php else: ?>
                        <i class="fas fa-check"></i>
                      <?php endif; ?>
                      </a>
                      <?php if($data['bukti_pembayaran'] != null): ?>
                      <a href="javascript:void(0)" data-file="<?= '/sinadi/previewBuktiBerkas/' . $data['bukti_pembayaran']; ?>" class="fileSinadi btn btn-xs btn-info" title="Lihat Bukti" data-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group">
                      <a href="/sinadi/export/lembar_verifikator/<?= $data['id_perjalanan']; ?>" class="btn btn-xs btn-success <?= $data['status_surat'] == "Diajukan" ? "disabled" : ""; ?>" title="Download Excel" data-toggle="tooltip">
                        <i class="far fa-file-excel"></i>
                      </a>
                      <a href="/sinadi/export/lembar_verifikator/pdf/<?= $data['id_perjalanan']; ?>" class="btn btn-xs btn-danger <?= $data['status_surat'] == "Diajukan" ? "disabled" : ""; ?>" title="Download PDF" data-toggle="tooltip">
                        <i class="far fa-file-pdf"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php if($tipe=='RPL'): ?>
    <div class="row mt-4">
      <div class="col-lg-6">
        <div class="card card-primary card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-check-circle mr-2 text-primary"></i>Verifikator Pertama</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-primary">Tahap 1</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataRPL as $data): ?>
                  <?php if ($data['status_surat'] == "Diajukan"): 
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("1", $urut_verif) ? "/sinadi/verifikasi_data/1/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("1", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("1", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-info card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-check-double mr-2 text-info"></i>Verifikator Kedua</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-info">Tahap 2</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $time = "2022-01-01 01:01:01";
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataRPL as $data):
                  $time_1 = $time;
                  $time_2 = $time;
                  if ($data['verifikator_1'] != null) {
                    $array1 = unserialize($data['verifikator_1']);
                    $time_1 = $array1[count($array1) - 1]['time'];
                  }
                  if ($data['verifikator_2'] != null) {
                    $array2 = unserialize($data['verifikator_2']);
                    $time_2 = $array2[count($array2) - 1]['time'];
                  }
                ?>
                  <?php if ($data['status_surat'] == "Dalam Verifikasi" && ($time_1 >= $time_2)):
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("2", $urut_verif) ? "/sinadi/verifikasi_data/2/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("2", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("2", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-success card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-clipboard-check mr-2 text-success"></i>Verifikator Ketiga</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-success">Tahap 3</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $array1 = null;
                $array2 = null;
                $array3 = null;
                $ada_data = false;
                foreach ($getDataRPL as $data):
                  $time_1 = $time;
                  $time_2 = $time;
                  if ($data['verifikator_1'] != null) {
                    $array1 = unserialize($data['verifikator_1']);
                    $time_1 = $array1[count($array1) - 1]['time'];
                  }
                  if ($data['verifikator_2'] != null) {
                    $array2 = unserialize($data['verifikator_2']);
                    $time_2 = $array2[count($array2) - 1]['time'];
                  }
                ?>
                  <?php if ($data['status_surat'] == "Dalam Verifikasi" && ($time_1 < $time_2)):
                    $ada_data = true;
                  ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= in_array("3", $urut_verif) ? "/sinadi/verifikasi_data/3/" . $data['id_perjalanan'] : "javascript:void(0)"; ?>" class="btn btn-xs btn-warning <?= in_array("3", $urut_verif) ? "" : "disabled"; ?>" style="<?= in_array("3", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-warning card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-money-check-alt mr-2 text-warning"></i>Verifikator Upload Bukti Pembayaran</h3>
            <div class="d-flex align-items-center"><input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;"><span class="badge badge-warning text-white">Bukti Pembayaran</span></div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="25%">Kegiatan</th>
                  <th width="20%">Tanggal</th>
                  <th width="20%">Tujuan</th>
                  <th width="10%">Status</th>
                  <th width="10%">Bukti</th>
                  <th width="10%">Lembar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                $ada_data = false;
                foreach ($getDataAllRPL as $data): 
                  $ada_data = true;
                ?>
                <tr>
                  <td class="text-center align-middle"><?= $nomor++; ?></td>
                  <td class="align-middle">
                        <?= $data['nama_kegiatan']; ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? $data['mata_anggaran'] : '-'; ?></small>
                      </td>
                  <td class="align-middle">
                    <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan'])  . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                  </td>
                  <td class="align-middle">
                    <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= $data['tempat_perjalanan']; ?>
                  </td>
                  <td class="text-center align-middle">
                      <?php if ($data['status_surat'] == "Draft"): ?>
                        <span class="badge badge-secondary"><i class="fas fa-pencil-alt mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Diajukan"): ?>
                        <span class="badge badge-primary"><i class="fas fa-paper-plane mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Dalam Verifikasi"): ?>
                        <span class="badge badge-info"><i class="fas fa-spinner mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Perlu Revisi"): ?>
                        <span class="badge badge-warning"><i class="fas fa-edit mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Disetujui"): ?>
                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php elseif ($data['status_surat'] == "Ditolak"): ?>
                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i> <?= $data['status_surat']; ?></span>
                      <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group">
                      <a data-id="<?= $data['id_perjalanan']; ?>" href="javascript:void(0)" class="btn btn-xs <?= $data['bukti_pembayaran'] == null ? 'btn-warning' : 'btn-success' ; ?> <?= in_array("6", $urut_verif) ? "sinadi-set-pembayaran" : "disabled"; ?>" style="<?= in_array("6", $urut_verif) ? "" : "cursor: not-allowed; pointer-events: all;"; ?>" title="<?= $data['bukti_pembayaran'] == null ? 'Upload Bukti' : 'Bukti Terupload' ; ?>" data-toggle="tooltip">
                      <?php if($data['bukti_pembayaran'] == null): ?>
                        <i class="fas fa-upload"></i>
                      <?php else: ?>
                        <i class="fas fa-check"></i>
                      <?php endif; ?>
                      </a>
                      <?php if($data['bukti_pembayaran'] != null): ?>
                      <a href="javascript:void(0)" data-file="<?= '/sinadi/previewBuktiBerkas/' . $data['bukti_pembayaran']; ?>" class="fileSinadi btn btn-xs btn-info" title="Lihat Bukti" data-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group">
                      <a href="/sinadi/export/lembar_verifikator/<?= $data['id_perjalanan']; ?>" class="btn btn-xs btn-success <?= $data['status_surat'] == "Diajukan" ? "disabled" : ""; ?>" title="Download Excel" data-toggle="tooltip">
                        <i class="far fa-file-excel"></i>
                      </a>
                      <a href="/sinadi/export/lembar_verifikator/pdf/<?= $data['id_perjalanan']; ?>" class="btn btn-xs btn-danger <?= $data['status_surat'] == "Diajukan" ? "disabled" : ""; ?>" title="Download PDF" data-toggle="tooltip">
                        <i class="far fa-file-pdf"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$ada_data): ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php if ($activeMenu != "sinadiBlockir"): ?>
  <script>
      $('.sinadi-set-pembayaran').click(function () {
          let elementId = $(this).data('id');
        
          // Kosongkan modal-body terlebih dahulu
          $('.modal-body').empty();
        
          // Buat form
          let form = $('<form>', {
            action: '/sinadi/setUploadBuktiPembayaran',
            method: 'POST',
            enctype: 'multipart/form-data'
          });
        
          // Input hidden untuk id
          let inputId = $('<input>', {
            type: 'hidden',
            name: 'id',
            value: elementId
          });
        
          // Input file upload
          let inputFile = $('<div>').append(
            $('<label>', {
              class: 'font-weight-bold mb-2'
            }).text('Unggah Bukti Pembayaran'),
            $('<input>', {
              type: 'file',
              name: 'bukti_pembayaran',
              required: true,
              class: 'form-control'
            })
          );
        
          // Tombol submit
          let submitButton = $('<button>', {
            type: 'submit',
            class: 'btn btn-success mt-3 float-right'
          });
            
          // Tambahkan ikon dan teks ke dalam tombol
           submitButton.html('<i class="fas fa-save"></i> Simpan');
        
          // Tambahkan elemen-elemen ke form
          form.append(inputId, inputFile, submitButton);
        
          // Masukkan form ke modal-body
          $('.modal-body').append(form);
        
          // Tampilkan modal
          $('#modal-md').modal('show');
        });
  </script>
  <script>
    $(".fileSinadi").on('click', function() {

      var file = $(this).data('file');

      if (file !== "#") {
        var fullUrl = '<?= base_url(); ?>/' + file;
        // var iframeSrc = 'https://drive.google.com/viewerng/viewer?embedded=true&url=' + encodeURIComponent(fullUrl);

        $("#modal-xl").find('.modal-body').html(
          '<iframe src="' + fullUrl + '" frameborder="0" style="width: 100%; height: 80vh;"></iframe>'
        );
        $("#modal-xl").modal('show');
      }
    });
  </script>
  <script>
    // Aktifkan tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  <script>
    $(document).ready(function(){
      $('.search-table').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $(this).closest('.card').find('tbody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>