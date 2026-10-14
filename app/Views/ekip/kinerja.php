<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary"><i class="fas fa-clipboard-check"></i> Penilaian Kinerja, Perilaku & 360</h2>
                <p class="text-muted">Kelola data evaluasi kinerja, perilaku, dan penilaian 360 Anda.</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4" id="penilaianTab" role="tablist">
        <li class="nav-item">
            <?php if (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1): ?>
                <a class="nav-link active" href="/ekip/kinerja"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja</a>
            <?php else: ?>
                <a class="nav-link active disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-brain"></i> Penilaian Perilaku (Dinonaktifkan)</a>
        </li>
        <li class="nav-item">
            <?php if (!isset($pengaturan['tab_360_aktif']) || $pengaturan['tab_360_aktif'] == 1): ?>
                <a class="nav-link" href="/ekip/penilaian_360"><i class="fas fa-users"></i> Penilaian 360</a>
            <?php else: ?>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-users"></i> Penilaian 360 (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <?php if (isset($isPenilai) && $isPenilai): ?>
        <li class="nav-item">
            <a class="nav-link" href="/ekip/kalibrasi"><i class="fas fa-balance-scale"></i> Kalibrasi</a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Evaluasi Mingguan Section -->
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title text-success font-weight-bold mt-1">Evaluasi Kinerja Mingguan</h3>
        <div class="card-tools d-flex align-items-center">
            <form action="/ekip/kinerja" method="GET" class="form-inline mr-3 form-filter-evaluasi">
                <div class="input-group input-group-sm mr-2">
                    <div class="input-group-prepend"><span class="input-group-text bg-light border-success"><i class="fas fa-calendar-alt"></i></span></div>
                    <select class="form-control border-success" name="tahun">
                        <?php for($y=date('Y')-1; $y<=date('Y')+2; $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $tahun_aktif ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="input-group input-group-sm mr-2">
                    <select class="form-control border-success" name="bulan">
                        <?php 
                        $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        for($b=1; $b<=12; $b++): ?>
                            <option value="<?= $b ?>" <?= $b == $bulan_aktif ? 'selected' : '' ?>><?= $bulanIndo[$b] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="input-group input-group-sm">
                    <select class="form-control border-success filterMingguKinerja" name="minggu">
                        <?php $getMinggu = isset($_GET['minggu']) ? $_GET['minggu'] : 'all'; ?>
                        <option value="all" <?= ($getMinggu == 'all') ? 'selected' : '' ?>>Semua Minggu</option>
                        <?php foreach($list_minggu as $m => $minggu): ?>
                            <option value="<?= $m ?>" <?= ($getMinggu == $m) ? 'selected' : '' ?>>Minggu <?= $m ?> (<?= esc($minggu['label']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <button type="button" class="btn btn-outline-primary btn-sm mr-2" data-toggle="modal" data-target="#modalListMaster">
                <i class="fas fa-database"></i> Lihat Data Master Sesuai SKP
            </button>
            <button type="button" class="btn btn-primary btn-sm btn-buat-evaluasi" <?= !in_array(false, $locked_weeks) ? 'disabled title="Batas waktu input untuk semua minggu telah ditutup."' : '' ?>>
              <i class="fas fa-calendar-plus"></i> Buat Evaluasi Kinerja
            </button>
          </div>
      </div>
      <div class="card-body p-0">
        <?php if(empty($summaryMingguan)): ?>
         <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-check fa-4x mb-3 text-success opacity-50"></i>
            <h5>Belum ada evaluasi yang dibuat.</h5>
            <p>Silakan klik tombol "Buat Evaluasi Kinerja" untuk mulai mengisi.</p>
         </div>
         <?php else: ?>
         <div class="table-responsive">
             <table class="table table-hover table-striped mb-0">
                 <thead class="bg-light text-success">
                     <tr>
                         <th width="5%">No</th>
                         <th width="15%">Bulan & Tahun</th>
                         <th width="15%">Periode Evaluasi</th>
                         <th width="15%">Item Terealisasi</th>
                         <th width="15%">Status Penilaian</th>
                         <th width="15%">Hasil Penilaian</th>
                         <th width="20%" class="text-center">Aksi</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php 
                     $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                     foreach($summaryMingguan as $idx => $sum): 
                         $bulanNum = $sum['tgl_dibuat'] ? date('n', strtotime($sum['tgl_dibuat'])) : date('n');
                         $namaBulan = $bulanIndo[$bulanNum];
                         $predikatKinerja = !empty($sum['predikat_kinerja']) ? $sum['predikat_kinerja'] : '-';
                     ?>
                     <tr>
                         <td><?= $idx + 1 ?></td>
                         <td>
                             <span class="badge badge-info mb-1"><?= esc($namaBulan) ?></span>
                             <span class="badge badge-secondary mb-1"><?= esc($tahun_aktif) ?></span>
                         </td>
                         <td>Minggu <?= esc($sum['minggu_ke']) ?></td>
                         <td><span class="badge badge-success"><?= esc($sum['jumlah_realisasi']) ?> Item</span></td>
                         <td>
                            <?php if($sum['sudah_dinilai'] && $predikatKinerja != '-'): ?>
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Penilaian</span>
                            <?php endif; ?>
                         </td>
                         <td class="align-middle">
                            <span class="font-weight-bold text-dark"><?= $predikatKinerja ?></span>
                         </td>
                         <td class="text-center">
                             <button class="btn btn-sm btn-outline-info btn-view-minggu mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Detail"><i class="fas fa-eye"></i></button>
                             <?php if(!($sum['sudah_dinilai'] && $predikatKinerja != '-')): ?>
                                 <button class="btn btn-sm btn-outline-primary btn-edit-minggu mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Edit" <?= $locked_weeks[$sum['minggu_ke']] ? 'disabled' : '' ?>><i class="fas fa-edit"></i></button>
                                 <button class="btn btn-sm btn-outline-danger btn-delete-minggu mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Hapus" <?= $locked_weeks[$sum['minggu_ke']] ? 'disabled' : '' ?>><i class="fas fa-trash"></i></button>
                             <?php else: ?>
                                 <button class="btn btn-sm <?= $sum['status_feedback_kinerja'] == 'diajukan' ? 'btn-warning' : ($sum['status_feedback_kinerja'] != 'none' ? 'btn-success' : 'btn-outline-warning') ?> btn-feedback mb-1" 
                                    data-minggu="<?= $sum['minggu_ke'] ?>" 
                                    data-bulan="<?= $sum['bulan'] ?>" 
                                    data-status-kinerja="<?= $sum['status_feedback_kinerja'] ?>" 
                                    data-feedback-kinerja="<?= esc($sum['feedback_kinerja'] ?? '') ?>"
                                    data-tanggapan-kinerja="<?= esc($sum['tanggapan_kinerja'] ?? '') ?>"
                                    title="Feedback Kinerja">
                                    <i class="fas fa-comment-dots"></i> Feedback
                                 </button>
                             <?php endif; ?>
                         </td>
                     </tr>
                     <?php endforeach; ?>
                 </tbody>
             </table>
         </div>
         <?php endif; ?>
      </div>
    </div>
  </div>
<!-- Modal Detail Mingguan -->
<div class="modal fade" id="modalDetailMingguan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Evaluasi <span id="titleDetailMinggu"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
          <div class="table-responsive">
              <table class="table table-sm table-hover table-striped mb-0 text-sm">
                  <thead class="bg-light text-info">
                      <tr>
                          <th width="5%" class="text-center">No</th>
                          <th width="30%">Rencana Hasil Kerja, IKI & Target</th>
                          <th width="30%">Realisasi & Bukti</th>
                          <th width="35%">Catatan Penilai</th>
                      </tr>
                  </thead>
                  <tbody id="detailMingguanBody">
                      <!-- Loaded via AJAX -->
                  </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Feedback Pegawai -->
<div class="modal fade" id="modalFeedback" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="fas fa-comment-dots"></i> Feedback Penilaian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <form id="formFeedback">
              <input type="hidden" name="minggu_ke" id="feedback_minggu_ke">
              <input type="hidden" name="bulan" id="feedback_bulan">
              <input type="hidden" name="tahun" value="<?= $tahun_aktif ?>">
              
              <div id="section-feedback-kinerja">
                  <h6 class="font-weight-bold text-primary border-bottom pb-2">Aspek Kinerja</h6>
                  <div class="form-group">
                      <label class="text-dark">Tanggapan / Ketidakpuasan Penilaian Kinerja Anda:</label>
                      <textarea class="form-control border-warning" id="feedback_kinerja" name="feedback_kinerja" rows="3" placeholder="Tuliskan alasan jika Anda merasa penilaian kinerja tidak sesuai..."></textarea>
                  </div>
                  <div id="areaTanggapanKinerja" style="display: none;">
                      <label class="text-success mt-2"><i class="fas fa-reply"></i> Balasan Penilai:</label>
                      <div class="p-3 bg-white border border-success rounded text-dark" id="text_tanggapan_kinerja"></div>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-warning font-weight-bold" id="btnKirimFeedback"><i class="fas fa-paper-plane"></i> Kirim Feedback</button>
      </div>
    </div>
  </div>
</div>

</section>

<!-- Modal List Data Master -->
<div class="modal fade" id="modalListMaster" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">A. Data Master Sesuai SKP</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
          <div class="p-3 bg-light d-flex justify-content-between align-items-center border-bottom">
              <div>
                  <form method="GET" action="" id="formFilterMasterTahun" class="d-inline-block">
                      <select name="master_tahun" class="form-control form-control-sm border-primary" onchange="sessionStorage.setItem('open_master_modal', '1'); document.getElementById('formFilterMasterTahun').submit();">
                          <?php for($y=date('Y')-1; $y<=date('Y')+2; $y++): ?>
                              <option value="<?= $y ?>" <?= $y == $filter_master_tahun ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                          <?php endfor; ?>
                      </select>
                  </form>
              </div>
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambahMaster" onclick="$('#modalListMaster').modal('hide');">
                <i class="fas fa-plus"></i> Tambah Master Baru
              </button>
          </div>
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
              <table class="table table-hover table-striped mb-0">
                  <thead class="bg-white text-primary" style="position: sticky; top: 0; z-index: 1;">
                      <tr>
                          <th width="5%" class="text-center">No</th>
                          <th width="5%" class="text-center">Tahun</th>
                          <th width="75%">Rencana Hasil Kerja, IKI & Target</th>
                          <th width="15%" class="text-center">Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if(empty($masterDataFiltered)): ?>
                      <tr>
                          <td colspan="4" class="text-center py-4">Belum ada data master kinerja untuk tahun <?= $filter_master_tahun ?>.</td>
                      </tr>
                      <?php else: ?>
                      <?php $no = 1; foreach($masterDataFiltered as $md): ?>
                      <tr>
                          <td class="text-center align-middle"><?= $no++ ?></td>
                          <td class="text-center align-middle"><span class="badge badge-<?= $md['tahun'] == $tahun_aktif ? 'success' : 'secondary' ?>"><?= esc($md['tahun']) ?></span></td>
                          <td class="align-middle">
                              <div class="mb-1">
                                  <span class="badge badge-<?= $md['jenis'] == 'Core' ? 'primary' : 'warning' ?>"><?= esc($md['jenis']) ?></span> 
                                  <span class="badge badge-secondary">Bobot: <?= esc($md['bobot']) ?></span> 
                                  <span class="badge badge-info">Target: <?= esc($md['target']) ?></span>
                              </div>
                              <div class="text-sm mb-1">
                                  <span class="text-muted">Rencana:</span> <span class="font-weight-bold"><?= esc($md['rencana_hasil_kerja']) ?></span>
                              </div>
                              <div class="text-sm">
                                  <span class="text-muted">IKI:</span> <span class="text-primary"><?= esc($md['iki']) ?></span>
                              </div>
                          </td>
                          <td class="text-center align-middle">
                              <button class="btn btn-sm btn-outline-primary btn-edit-master" 
                                  data-id="<?= $md['id'] ?>"
                                  data-tahun="<?= esc($md['tahun']) ?>"
                                  data-jenis="<?= esc($md['jenis']) ?>"
                                  data-bobot="<?= esc($md['bobot']) ?>"
                                  data-rencana="<?= esc($md['rencana_hasil_kerja']) ?>"
                                  data-iki="<?= esc($md['iki']) ?>"
                                  data-target="<?= esc($md['target']) ?>"><i class="fas fa-edit"></i></button>
                              <button class="btn btn-sm btn-outline-danger btn-delete-master" data-id="<?= $md['id'] ?>"><i class="fas fa-trash"></i></button>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Master -->
<div class="modal fade" id="modalTambahMaster" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Data Master Sesuai SKP</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <form id="formMasterKinerja">
            <input type="hidden" name="id" id="master_id">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <td width="35%" class="bg-light font-weight-bold align-middle">Tahun Penilaian</td>
                            <td class="p-2">
                                <select class="form-control border-primary" name="tahun" id="tahun_master">
                                    <?php for($y=date('Y')-1; $y<=date('Y')+2; $y++): ?>
                                        <option value="<?= $y ?>" <?= $y == $tahun_aktif ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold align-middle">Jenis</td>
                            <td class="p-2">
                                <select class="form-control border-primary" name="jenis" id="jenis_master" required>
                                    <option value="Core">Core</option>
                                    <option value="Beyond">Beyond</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold align-middle">Bobot</td>
                            <td class="p-2">
                                <input type="number" class="form-control border-primary" name="bobot" id="bobot_master" placeholder="Contoh: 20" required>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="bg-light font-weight-bold align-middle">
                                Rencana Hasil Kerja <br><small class="text-muted font-weight-normal">(Sesuai SKP)</small>
                            </td>
                            <td class="p-2">
                                <textarea class="form-control border-primary" name="rencana_hasil_kerja" id="rencana_hasil_kerja" rows="2" placeholder="Masukkan rencana hasil kerja..." required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold align-middle">
                                Indikator Kinerja Individu (IKI)
                            </td>
                            <td class="p-2">
                                <input type="text" class="form-control border-primary" name="iki" id="iki" placeholder="Masukkan IKI..." required>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold align-middle">
                                Target
                            </td>
                            <td class="p-2">
                                <textarea class="form-control border-primary" name="target" id="target_master" rows="2" placeholder="Masukkan target..." required></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-batal-master" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="btnSimpanMaster"><i class="fas fa-save"></i> Simpan Data</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Evaluasi Mingguan -->
<div class="modal fade" id="modalEvaluasiMingguan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Buat Evaluasi Kinerja Mingguan</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <div class="alert alert-danger" id="warningMingguKinerja" style="display:none;">
              <i class="fas fa-exclamation-triangle"></i> Evaluasi untuk minggu ini sudah dibuat. Silakan gunakan tombol Edit pada tabel jika ingin mengubahnya.
          </div>
        <form id="formEvaluasiMingguan">
            <input type="hidden" name="mode" id="evaluasi_mode_kinerja" value="create">
            <input type="hidden" name="bulan" id="evaluasi_bulan" value="<?= $bulan_aktif ?>">
            <input type="hidden" name="tahun" id="evaluasi_tahun" value="<?= $tahun_aktif ?>">
            <div class="d-flex align-items-center mb-3">
                <label class="text-primary mb-0 mr-3">Pilih Minggu Ke-</label>
                <select class="form-control form-control-sm border-success w-auto" name="minggu_ke" id="selectMingguForm">
                    <?php foreach($list_minggu as $i => $minggu): ?>
                      <option value="<?= $i ?>" <?= $i == $minggu_aktif ? 'selected' : '' ?> <?= $locked_weeks[$i] ? 'disabled' : '' ?>>Minggu <?= $i ?> (<?= esc($minggu['label']) ?>) <?= $i == $minggu_aktif ? '- Berjalan' : '' ?> <?= $locked_weeks[$i] ? '[Terkunci]' : '' ?></option>
                      <?php endforeach; ?>
                </select>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary font-weight-bold mb-0">Form Realisasi Mingguan (Tahun <?= $tahun_aktif ?>)</h6>
                <button type="button" class="btn btn-sm btn-outline-info" id="btnSinkronisasi">
                    <i class="fas fa-sync-alt"></i> Sinkronisasi
                </button>
            </div>
            <!-- Dynamic Table based on Data Master -->
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover mb-0 text-sm">
                    <thead class="bg-light text-primary">
                        <tr>
                            <th width="5%" class="text-center align-middle">No</th>
                            <th width="35%" class="align-middle">Rencana Hasil Kerja, IKI & Target</th>
                            <th width="30%" class="align-middle">Realisasi Minggu Ini</th>
                            <th width="30%" class="align-middle">Link Bukti Dukung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($masterDataAktif)): ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada Data Master Kinerja untuk tahun <?= $tahun_aktif ?>.</td></tr>
                        <?php else: ?>
                            <?php $no=1; foreach($masterDataAktif as $md): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                      <div class="mb-1">
                                          <span class="badge badge-<?= $md['jenis'] == 'Core' ? 'primary' : 'warning' ?>"><?= esc($md['jenis']) ?></span> 
                                          <span class="badge badge-secondary">Bobot: <?= esc($md['bobot']) ?></span> 
                                          <span class="badge badge-info">Target: <?= nl2br(esc($md['target'])) ?></span>
                                      </div>
                                      <div class="text-sm mb-1">
                                          <span class="text-muted">Rencana:</span> <span class="font-weight-bold"><?= esc($md['rencana_hasil_kerja']) ?></span>
                                      </div>
                                      <div class="text-sm">
                                          <span class="text-muted">IKI:</span> <span class="text-primary"><?= esc($md['iki']) ?></span>
                                      </div>
                                 </td>
                                <td class="align-middle">
                                     <textarea class="form-control form-control-sm border-success" style="min-height: 80px;" name="realisasi[<?= $md['id'] ?>]" id="realisasi_<?= $md['id'] ?>" rows="3" placeholder="Isi realisasi untuk minggu ini..."></textarea>
                                 </td>
                                <td class="align-middle">
                                    <input type="url" class="form-control form-control-sm border-success" name="link_bukti[<?= $md['id'] ?>]" id="link_<?= $md['id'] ?>" placeholder="https://...">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tbody id="tbodyRekomendasiPegawai">
                        <!-- Rekomendasi/Tugas Tambahan dari Penilai akan diload via AJAX di sini -->
                    </tbody>
                </table>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="btnSimpanMingguan"><i class="fas fa-check"></i> Simpan Evaluasi</button>
      </div>
    </div>
  </div>
</div>

</section>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .card-outline.card-success { border-top: 3px solid #28a745; }
</style>

<?php
$filled_weeks_kinerja = [];
if (!empty($summaryMingguan)) {
    foreach($summaryMingguan as $sum) {
        if($sum['bulan'] == $bulan_aktif) {
            $filled_weeks_kinerja[] = $sum['minggu_ke'];
        }
    }
}
?>

<script>
$(document).ready(function() {
    var filledWeeksKinerja = <?= json_encode($filled_weeks_kinerja) ?>.map(Number);
    var modalModeKinerja = 'create';

    function checkMingguKinerja(minggu) {
        if (modalModeKinerja === 'create' && filledWeeksKinerja.includes(parseInt(minggu))) {
            $('#warningMingguKinerja').show();
            $('#btnSimpanMingguan').prop('disabled', true);
        } else {
            $('#warningMingguKinerja').hide();
            $('#btnSimpanMingguan').prop('disabled', false);
        }
    }
    // Reopen Data Master Modal if flag exists
    if(sessionStorage.getItem('open_master_modal') == '1') {
        sessionStorage.removeItem('open_master_modal');
        $('#modalListMaster').modal('show');
    }

    $('#modalTambahMaster').on('hidden.bs.modal', function () {
        $('#formMasterKinerja')[0].reset();
        $('#master_id').val('');
        $('.modal-title').text('Tambah Data Master Sesuai SKP');
    });

    $('.btn-batal-master').click(function() {
        $('#modalListMaster').modal('show');
    });

    $('.btn-edit-master').click(function() {
        var id = $(this).data('id');
        var tahun = $(this).data('tahun');
        var jenis = $(this).data('jenis');
        var bobot = $(this).data('bobot');
        var rencana = $(this).data('rencana');
        var iki = $(this).data('iki');
        var target = $(this).data('target');

        $('#master_id').val(id);
        $('#tahun_master').val(tahun);
        $('#jenis_master').val(jenis);
        $('#bobot_master').val(bobot);
        $('#rencana_hasil_kerja').val(rencana);
        $('#iki').val(iki);
        $('#target_master').val(target);
        
        $('#modalListMaster').modal('hide'); // Hide list modal
        $('.modal-title').text('Edit Data Master Sesuai SKP');
        $('#modalTambahMaster').modal('show');
    });

    $('#btnSimpanMaster').click(function() {
        var formData = $('#formMasterKinerja').serialize();
        $.ajax({
            url: '/ekip/master/simpan',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        sessionStorage.setItem('open_master_modal', '1');
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('.btn-delete-master').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/master/hapus/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire('Terhapus!', response.message, 'success').then(() => {
                                sessionStorage.setItem('open_master_modal', '1');
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });

    $('#btnSimpanMingguan').click(function() {
        var formData = $('#formEvaluasiMingguan').serialize();
        $.ajax({
            url: '/ekip/mingguan/simpan',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    // Dynamic Form based on week selection
    function loadEvaluasiMingguan(minggu, bulan) {
        // Reset fields first
        $('#formEvaluasiMingguan textarea[id^="realisasi_"]').val('');
        $('#formEvaluasiMingguan input[id^="link_"]').val('');
        $('#tbodyRekomendasiPegawai').empty(); // Kosongkan rekomendasi tambahan
        
        // Fetch Rekomendasi/Tugas Tambahan for the selected week
        var t_rek = $('#evaluasi_tahun').val();
        var b_rek = bulan || $('#evaluasi_bulan').val();
        var id_user_rek = '<?= session()->get('id_user') ?>';

        $.ajax({
            url: '/ekip/rekomendasi/get/' + id_user_rek + '/' + t_rek + '/' + b_rek + '/' + minggu,
            type: 'GET',
            success: function(response) {
                if (response.status == 'success' && response.data.length > 0) {
                    var html = '<tr><td colspan="4" class="bg-success text-white text-center font-weight-bold">Tugas Tambahan dari Penilai (Rekomendasi Kebijakan)</td></tr>';
                    $.each(response.data, function(i, v) {
                        var no = <?= count($masterDataAktif) ?> + i + 1;
                        html += '<tr>';
                        html += '<td class="text-center align-middle">' + no + '</td>';
                        html += '<td class="align-middle">';
                        html += '  <div class="mb-1"><span class="badge badge-success">Tambahan</span> <span class="badge badge-info">Target: ' + v.target + '</span></div>';
                        html += '  <div class="text-sm mb-1"><span class="text-muted">Rencana:</span> <span class="font-weight-bold">' + v.rencana_hasil_kerja + '</span></div>';
                        html += '  <div class="text-sm"><span class="text-muted">IKI:</span> <span class="text-primary">' + v.iki + '</span></div>';
                        html += '</td>';
                        html += '<td class="align-middle">';
                        html += '  <textarea class="form-control form-control-sm border-success" style="min-height: 80px;" name="realisasi_rek['+v.id+']" id="realisasi_rek_'+v.id+'" rows="3" placeholder="Isi realisasi untuk minggu ini..."></textarea>';
                        html += '</td>';
                        html += '<td class="align-middle">';
                        html += '  <input type="url" class="form-control form-control-sm border-success" name="link_bukti_rek['+v.id+']" id="link_rek_'+v.id+'" placeholder="https://...">';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#tbodyRekomendasiPegawai').html(html);
                }

                // If edit mode, load existing evaluations
                if (modalModeKinerja === 'edit') {
                    var url = '/ekip/mingguan/get/' + minggu;
                    if (bulan) {
                        url += '?bulan=' + bulan;
                    }
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            if(response.status == 'success') {
                                var data = response.data;
                                var data_rek = response.data_rek;
                                
                                // Populate fields for master
                                for(var master_id in data) {
                                    $('#realisasi_' + master_id).val(data[master_id].realisasi);
                                    $('#link_' + master_id).val(data[master_id].link_bukti);
                                }

                                // Populate fields for rekomendasi
                                if (data_rek) {
                                    for(var rek_id in data_rek) {
                                        $('#realisasi_rek_' + rek_id).val(data_rek[rek_id].realisasi);
                                        $('#link_rek_' + rek_id).val(data_rek[rek_id].link_bukti);
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    }

    $('.btn-buat-evaluasi').click(function() {
        modalModeKinerja = 'create';
        $('#evaluasi_mode_kinerja').val('create');
        $('#formEvaluasiMingguan')[0].reset();
        $('#evaluasi_bulan').val(<?= $bulan_aktif ?>);
        $('#selectMingguForm').val(<?= $minggu_aktif ?>);
        $('#modalEvaluasiMingguan').find('.modal-title').text('Buat Evaluasi Kinerja Mingguan');
        loadEvaluasiMingguan(<?= $minggu_aktif ?>, <?= $bulan_aktif ?>);
        checkMingguKinerja(<?= $minggu_aktif ?>);
        $('#modalEvaluasiMingguan').modal('show');
    });

    $('.btn-edit-minggu').click(function() {
        modalModeKinerja = 'edit';
        $('#evaluasi_mode_kinerja').val('edit');
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan') || new Date().getMonth() + 1;
        $('#selectMingguForm').val(minggu);
        $('#evaluasi_bulan').val(bulan);
        loadEvaluasiMingguan(minggu, bulan);
        checkMingguKinerja(minggu);
        $('#modalEvaluasiMingguan').modal('show');
    });

    $('#selectMingguForm').change(function() {
        loadEvaluasiMingguan($(this).val());
        checkMingguKinerja($(this).val());
    });

    $('#btnSinkronisasi').click(function() {
        var minggu = $('#selectMingguForm').val();
        var bulan = $('#evaluasi_bulan').val();
        var tahun = $('#evaluasi_tahun').val();
        
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyinkronkan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/mingguan/get_sebelumnya',
            type: 'GET',
            data: { minggu: minggu, bulan: bulan, tahun: tahun },
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.status == 'success') {
                    var data = response.data;
                    var data_rek = response.data_rek;
                    
                    // Populate fields for master
                    for(var master_id in data) {
                        $('#realisasi_' + master_id).val(data[master_id].realisasi);
                        $('#link_' + master_id).val(data[master_id].link_bukti);
                    }

                    // Populate fields for rekomendasi
                    if (data_rek) {
                        for(var rek_id in data_rek) {
                            $('#realisasi_rek_' + rek_id).val(data_rek[rek_id].realisasi);
                            $('#link_rek_' + rek_id).val(data_rek[rek_id].link_bukti);
                        }
                    }
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data minggu sebelumnya berhasil disalin',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else if(response.status == 'empty') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Tidak ada data di minggu sebelumnya',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                Swal.fire('Error', 'Terjadi kesalahan saat menyinkronkan data', 'error');
            }
        });
    });

    // Feedback Handler
    $('.btn-feedback').click(function() {
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan');
        
        var st_kinerja = $(this).data('status-kinerja');
        var fb_kinerja = $(this).data('feedback-kinerja');
        var tg_kinerja = $(this).data('tanggapan-kinerja');

        $('#feedback_minggu_ke').val(minggu);
        $('#feedback_bulan').val(bulan);
        
        // Kinerja
        $('#feedback_kinerja').val(fb_kinerja);
        if (st_kinerja === 'ditanggapi' || st_kinerja === 'selesai') {
            $('#areaTanggapanKinerja').show();
            $('#text_tanggapan_kinerja').html(tg_kinerja ? tg_kinerja.replace(/\n/g, '<br>') : '-');
            $('#feedback_kinerja').prop('readonly', true);
        } else if (st_kinerja === 'diajukan') {
            $('#areaTanggapanKinerja').hide();
            $('#feedback_kinerja').prop('readonly', true);
        } else {
            $('#areaTanggapanKinerja').hide();
            $('#feedback_kinerja').prop('readonly', false);
        }

        if (st_kinerja === 'ditanggapi' || st_kinerja === 'selesai' || st_kinerja === 'diajukan') {
            $('#btnKirimFeedback').hide();
        } else {
            $('#btnKirimFeedback').show();
        }

        $('#modalFeedback').modal('show');
    });

    $('#btnKirimFeedback').click(function() {
        var formData = $('#formFeedback').serialize();
        $.ajax({
            url: '/ekip/feedback/ajukan',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('.btn-view-minggu').click(function() {
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan') || new Date().getMonth() + 1;
        $('#titleDetailMinggu').text('Minggu ' + minggu);
        $.ajax({
            url: '/ekip/mingguan/get/' + minggu + '?bulan=' + bulan,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success') {
                    var list = response.list;
                    var html = '';
                    if(list.length > 0) {
                        for(var i=0; i<list.length; i++) {
                            var item = list[i];
                            var linkHTML = item.link_bukti ? '<a href="'+item.link_bukti+'" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-external-link-alt"></i> Buka Bukti Dukung</a>' : '<span class="text-muted">-</span>';
                            var badgeClass = item.jenis == 'Core' ? 'primary' : 'warning';
                            
                            html += '<tr>';
                            html += '<td class="text-center align-middle">'+(i+1)+'</td>';
                            html += '<td class="align-middle">';
                            html += '  <div class="mb-1"><span class="badge badge-'+badgeClass+'">'+item.jenis+'</span> <span class="badge badge-secondary">Bobot: '+item.bobot+'</span> <span class="badge badge-info">Target: '+(item.target ? item.target.replace(/\n/g, ' | ') : '-')+'</span></div>';
                            html += '  <div class="text-sm mb-1"><span class="text-muted">Rencana:</span> <span class="font-weight-bold">'+item.rencana_hasil_kerja+'</span></div>';
                            html += '  <div class="text-sm"><span class="text-muted">IKI:</span> <span class="text-primary">'+item.iki+'</span></div>';
                            html += '</td>';
                            html += '<td class="align-middle text-sm">';
                            html += '  <div class="mb-2"><span class="text-success font-weight-bold">'+(item.realisasi ? item.realisasi : '-')+'</span></div>';
                            html += '  <div>'+linkHTML+'</div>';
                            html += '</td>';
                            html += '<td class="align-middle"><div class="p-2 bg-light border rounded text-sm text-muted font-italic" style="min-height:60px;">'+(item.catatan_penilai ? item.catatan_penilai.replace(/\n/g, '<br>') : 'Belum ada catatan')+'</div></td>';
                            html += '</tr>';
                        }
                    } else {
                        html = '<tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>';
                    }
                    $('#detailMingguanBody').html(html);
                    $('#modalDetailMingguan').modal('show');
                }
            }
        });
    });

    $('.btn-delete-minggu').click(function() {
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan') || $('#filterBulan').val() || new Date().getMonth() + 1;
        Swal.fire({
            title: 'Hapus Evaluasi Minggu ' + minggu + '?',
            text: "Seluruh data evaluasi pada minggu ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus semua!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/mingguan/hapus-minggu/' + minggu + '?bulan=' + bulan,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire('Terhapus!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
