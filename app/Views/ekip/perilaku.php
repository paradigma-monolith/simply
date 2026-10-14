<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-primary"><i class="fas fa-clipboard-check"></i> Penilaian Kinerja, Perilaku & 360</h2>
            <p class="text-muted">Kelola data evaluasi kinerja, perilaku, dan penilaian 360 Anda.</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4" id="penilaianTab" role="tablist">
        <li class="nav-item">
            <?php if (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1): ?>
                <a class="nav-link" href="/ekip/kinerja"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja</a>
            <?php else: ?>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <li class="nav-item">
            <a class="nav-link active disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-brain"></i> Penilaian Perilaku (Dinonaktifkan)</a>
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
        <h3 class="card-title text-success font-weight-bold mt-1">Daftar Penilaian Perilaku Mingguan</h3>
        <div class="card-tools d-flex align-items-center">
            <form action="/ekip/perilaku" method="GET" class="form-inline mr-3 form-filter-evaluasi">
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
            <button type="button" class="btn btn-primary btn-sm btn-buat-perilaku" <?= !in_array(false, $locked_weeks) ? 'disabled title="Batas waktu input untuk semua minggu telah ditutup."' : '' ?>>
              <i class="fas fa-calendar-plus"></i> Buat Evaluasi Perilaku
            </button>
          </div>
      </div>
      <div class="card-body p-0">
        <?php if(empty($summaryPerilakuMingguan)): ?>
         <div class="text-center p-5 text-muted">
             <i class="fas fa-calendar-check fa-4x mb-3 text-success opacity-50"></i>
             <h5>Belum ada penilaian perilaku yang dibuat.</h5>
             <p>Silakan klik tombol "Buat Evaluasi Perilaku" untuk mulai mengisi.</p>
           </div>
         <?php else: ?>
         <div class="table-responsive">
             <table class="table table-hover table-striped mb-0">
                 <thead class="bg-light text-success">
                     <tr>
                         <th width="5%">No</th>
                         <th width="15%">Bulan & Tahun</th>
                         <th width="20%">Periode Evaluasi</th>
                         <th width="20%">Status Penilaian</th>
                         <th width="20%">Hasil Penilaian</th>
                         <th width="20%" class="text-center">Aksi</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php 
                     $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                     foreach($summaryPerilakuMingguan as $idx => $sum): 
                         $bulanNum = $sum['tgl_dibuat'] ? date('n', strtotime($sum['tgl_dibuat'])) : date('n');
                         $namaBulan = $bulanIndo[$bulanNum];
                         $predikatPerilaku = !empty($sum['predikat_perilaku']) ? $sum['predikat_perilaku'] : '-';
                     ?>
                     <tr>
                         <td><?= $idx + 1 ?></td>
                         <td>
                             <span class="badge badge-info mb-1"><?= esc($namaBulan) ?></span>
                             <span class="badge badge-secondary mb-1"><?= esc($tahun_aktif) ?></span>
                         </td>
                         <td>Minggu <?= esc($sum['minggu_ke']) ?></td>
                         <td>
                            <?php if($sum['sudah_dinilai'] && $predikatPerilaku != '-'): ?>
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Penilaian</span>
                            <?php endif; ?>
                         </td>
                         <td class="align-middle">
                            <span class="font-weight-bold text-dark"><?= $predikatPerilaku ?></span>
                         </td>
                         <td class="text-center">
                             <button class="btn btn-sm btn-outline-info btn-view-perilaku mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Detail"><i class="fas fa-eye"></i></button>
                             <?php if(!($sum['sudah_dinilai'] && $predikatPerilaku != '-')): ?>
                                 <button class="btn btn-sm btn-outline-primary btn-edit-perilaku mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Edit" <?= $locked_weeks[$sum['minggu_ke']] ? 'disabled' : '' ?>><i class="fas fa-edit"></i></button>
                                  <button class="btn btn-sm btn-outline-danger btn-delete-perilaku mb-1" data-minggu="<?= $sum['minggu_ke'] ?>" data-bulan="<?= $sum['bulan'] ?>" title="Hapus" <?= $locked_weeks[$sum['minggu_ke']] ? 'disabled' : '' ?>><i class="fas fa-trash"></i></button>
                             <?php else: ?>
                                 <button class="btn btn-sm <?= $sum['status_feedback_perilaku'] == 'diajukan' ? 'btn-warning' : ($sum['status_feedback_perilaku'] != 'none' ? 'btn-success' : 'btn-outline-warning') ?> btn-feedback mb-1" 
                                    data-minggu="<?= $sum['minggu_ke'] ?>" 
                                    data-bulan="<?= $sum['bulan'] ?>" 
                                    data-status-perilaku="<?= $sum['status_feedback_perilaku'] ?>" 
                                    data-feedback-perilaku="<?= esc($sum['feedback_perilaku'] ?? '') ?>"
                                    data-tanggapan-perilaku="<?= esc($sum['tanggapan_perilaku'] ?? '') ?>"
                                    title="Feedback Perilaku">
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
              
              <div id="section-feedback-perilaku">
                  <h6 class="font-weight-bold text-primary border-bottom pb-2">Aspek Perilaku</h6>
                  <div class="form-group">
                      <label class="text-dark">Tanggapan / Ketidakpuasan Penilaian Perilaku Anda:</label>
                      <textarea class="form-control border-warning" id="feedback_perilaku" name="feedback_perilaku" rows="3" placeholder="Tuliskan alasan jika Anda merasa penilaian perilaku tidak sesuai..."></textarea>
                  </div>
                  <div id="areaTanggapanPerilaku" style="display: none;">
                      <label class="text-success mt-2"><i class="fas fa-reply"></i> Balasan Penilai:</label>
                      <div class="p-3 bg-white border border-success rounded text-dark" id="text_tanggapan_perilaku"></div>
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

<!-- Modal Evaluasi Perilaku -->
  <div class="modal fade" id="modalEvaluasiPerilaku" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Buat Evaluasi Perilaku Mingguan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <div class="alert alert-danger" id="warningMingguPerilaku" style="display:none;">
              <i class="fas fa-exclamation-triangle"></i> Penilaian perilaku untuk minggu ini sudah dibuat. Silakan gunakan tombol Edit pada tabel jika ingin mengubahnya.
          </div>
          <div class="alert alert-info" id="notifEkspektasiPimpinan" style="display:none;">
              <i class="fas fa-info-circle"></i> <strong>Ekspektasi Khusus Pimpinan telah diisi!</strong> Penilai/PIC Anda telah menentukan observasi perilaku untuk minggu ini. Silakan baca, setujui, dan simpan penilaian Anda.
          </div>
        <form id="formPerilaku">
            <input type="hidden" name="mode" id="evaluasi_mode_perilaku" value="create">
            <input type="hidden" name="bulan" id="evaluasi_bulan_perilaku" value="<?= $bulan_aktif ?>">
            <input type="hidden" name="tahun" id="evaluasi_tahun_perilaku" value="<?= $tahun_aktif ?>">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <label class="text-primary mb-0 mr-3">Pilih Minggu Ke-</label>
                    <select class="form-control form-control-sm border-success w-auto" name="minggu_ke" id="selectMingguForm">
                        <?php foreach($list_minggu as $i => $minggu): ?>
                          <option value="<?= $i ?>" <?= $i == $minggu_aktif ? 'selected' : '' ?> <?= $locked_weeks[$i] ? 'disabled' : '' ?>>Minggu <?= $i ?> (<?= esc($minggu['label']) ?>) <?= $i == $minggu_aktif ? '- Berjalan' : '' ?> <?= $locked_weeks[$i] ? '[Terkunci]' : '' ?></option>
                          <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-outline-info" id="btnSinkronisasiPerilaku">
                    <i class="fas fa-sync-alt"></i> Sinkronisasi
                </button>
            </div>
            
            <div class="card card-outline card-primary shadow-sm mb-4">
              <div class="card-header bg-white">
                <h3 class="card-title text-primary font-weight-bold">Perilaku Kerja — Core Value ASN BerAKHLAK</h3>
              </div>
              <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table table-bordered table-hover mb-0 text-sm">
                          <thead class="bg-light text-primary">
                              <tr>
                                  <th width="30%">Core Value</th>
                                  <th width="30%">Ekspektasi Khusus Pimpinan</th>
                                  <th width="25%" class="eval-catatan-mode d-none">Catatan Penilai</th>
                                  <th width="15%" class="text-center eval-catatan-mode d-none">Skala Penilaian Ekspektasi Khusus</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php 
                              $core_values = [
                                  [
                                      'id' => 'pelayanan', 
                                      'title' => '1. Berorientasi Pelayanan', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Memahami dan memenuhi kebutuhan masyarakat</li><li>Ramah, cekatan, solutif, dan dapat diandalkan</li><li>Melakukan perbaikan tiada henti</li></ul>'
                                  ],
                                  [
                                      'id' => 'akuntabel', 
                                      'title' => '2. Akuntabel', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Melaksanakan tugas dengan jujur, bertanggung jawab, cermat, disiplin, dan berintegritas tinggi</li><li>Menggunakan kekayaan dan barang milik negara secara bertanggung jawab, efektif, dan efisien</li><li>Tidak menyalahgunakan kewenangan jabatan</li></ul>'
                                  ],
                                  [
                                      'id' => 'kompeten', 
                                      'title' => '3. Kompeten', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah</li><li>Membantu orang lain belajar</li><li>Melaksanakan tugas dengan kualitas terbaik</li></ul>'
                                  ],
                                  [
                                      'id' => 'harmonis', 
                                      'title' => '4. Harmonis', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Menghargai setiap orang apapun latar belakangnya</li><li>Suka menolong orang lain</li><li>Membangun lingkungan kerja yang kondusif</li></ul>'
                                  ],
                                  [
                                      'id' => 'loyal', 
                                      'title' => '5. Loyal', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Memegang teguh ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945, setia pada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah</li><li>Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara</li><li>Menjaga rahasia jabatan dan negara</li></ul>'
                                  ],
                                  [
                                      'id' => 'adaptif', 
                                      'title' => '6. Adaptif', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Cepat menyesuaikan diri menghadapi perubahan</li><li>Terus berinovasi dan mengembangkan kreativitas</li><li>Bertindak proaktif</li></ul>'
                                  ],
                                  [
                                      'id' => 'kolaboratif', 
                                      'title' => '7. Kolaboratif', 
                                      'desc' => '<ul class="mb-0 pl-3 text-muted small"><li>Memberi kesempatan kepada berbagai pihak untuk berkontribusi</li><li>Terbuka dalam bekerja sama untuk menghasilkan nilai tambah</li><li>Menggerakkan pemanfaatan berbagai sumberdaya untuk tujuan bersama</li></ul>'
                                  ]
                              ];
                              
                              foreach($core_values as $index => $value): 
                              ?>
                              <tr>
                                  <td class="align-middle bg-light">
                                      <span class="font-weight-bold d-block mb-1"><?= $value['title'] ?></span>
                                      <?= $value['desc'] ?>
                                  </td>
                                  <td class="align-middle">
                                      <div class="eval-input-mode">
                                          <textarea class="form-control form-control-sm border-success mb-2" name="<?= $value['id'] ?>" id="input_<?= $value['id'] ?>" rows="4" placeholder="Tuliskan realisasi/bukti perilaku minggu ini..."></textarea>
                                      </div>
                                      <div class="eval-view-mode d-none">
                                          <div class="p-2 bg-light border rounded text-sm text-dark font-italic" id="view_<?= $value['id'] ?>" style="min-height: 60px;"></div>
                                      </div>
                                  </td>
                                  <td class="align-middle eval-catatan-mode d-none">
                                      <div class="p-2 bg-light border rounded text-sm text-dark font-italic" id="catatan_<?= $value['id'] ?>" style="min-height: 80px;"></div>
                                  </td>
                                  <td class="align-middle text-center eval-catatan-mode d-none">
                                      <span class="badge badge-primary px-3 py-2" id="skala_<?= $value['id'] ?>" style="font-size: 14px;">-</span>
                                  </td>
                              </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" id="btnSimpanPerilaku" class="btn btn-success"><i class="fas fa-save"></i> Simpan Penilaian</button>
      </div>
    </div>
  </div>
</div>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .card-outline.card-success { border-top: 3px solid #28a745; }
</style>

<?php
$filled_weeks_perilaku = [];
if (!empty($summaryPerilakuMingguan)) {
    foreach($summaryPerilakuMingguan as $sum) {
        if($sum['bulan'] == $bulan_aktif) {
            $filled_weeks_perilaku[] = $sum['minggu_ke'];
        }
    }
}
?>

<script>
$(document).ready(function() {
    var filledWeeksPerilaku = <?= json_encode($filled_weeks_perilaku) ?>.map(Number);
    var modalModePerilaku = 'create';

    function checkEkspektasiPimpinan(minggu) {
        var bulan = $('#evaluasi_bulan_perilaku').val();
        var tahun = $('#evaluasi_tahun_perilaku').val();
        
        // Cek ke server apakah ada ekspektasi khusus pimpinan
        $.ajax({
            url: '/ekip/observasi/cek_ekspektasi/' + tahun + '/' + bulan + '/' + minggu,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success' && response.data) {
                    $('#notifEkspektasiPimpinan').show();
                    // Set isi textarea
                    $('#input_pelayanan').val(response.data.berorientasi_pelayanan || '');
                    $('#input_akuntabel').val(response.data.akuntabel || '');
                    $('#input_kompeten').val(response.data.kompeten || '');
                    $('#input_harmonis').val(response.data.harmonis || '');
                    $('#input_loyal').val(response.data.loyal || '');
                    $('#input_adaptif').val(response.data.adaptif || '');
                    $('#input_kolaboratif').val(response.data.kolaboratif || '');
                } else {
                    $('#notifEkspektasiPimpinan').hide();
                }
            }
        });
    }

    function checkMingguPerilaku(minggu) {
        if (modalModePerilaku === 'create' && filledWeeksPerilaku.includes(parseInt(minggu))) {
            $('#warningMingguPerilaku').show();
            $('#btnSimpanPerilaku').prop('disabled', true);
        } else {
            $('#warningMingguPerilaku').hide();
            $('#btnSimpanPerilaku').prop('disabled', false);
        }
        
        if (modalModePerilaku === 'create') {
            checkEkspektasiPimpinan(minggu);
        }
    }
    function loadPerilakuMingguan(minggu, mode = 'edit', bulan = null) {
        if (!bulan) bulan = new Date().getMonth() + 1;
        
        // Reset fields
        $('#formPerilaku textarea').val('');

        if (mode === 'create') {
            $('.eval-input-mode').removeClass('d-none');
            $('.eval-view-mode').addClass('d-none');
            $('.eval-catatan-mode').addClass('d-none');
            checkEkspektasiPimpinan(minggu);
            return;
        }

        $('#notifEkspektasiPimpinan').hide();

        $.ajax({
            url: '/ekip/perilaku/get/' + minggu + '?bulan=' + bulan,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success') {
                    if (response.data) {
                        var data = response.data;
                        
                        if (mode === 'view') {
                            $('.eval-input-mode').addClass('d-none');
                            $('.eval-view-mode').removeClass('d-none');
                            $('.eval-catatan-mode').removeClass('d-none');
                            
                            $('#view_pelayanan').html(data.pelayanan ? data.pelayanan.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_akuntabel').html(data.akuntabel ? data.akuntabel.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_kompeten').html(data.kompeten ? data.kompeten.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_harmonis').html(data.harmonis ? data.harmonis.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_loyal').html(data.loyal ? data.loyal.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_adaptif').html(data.adaptif ? data.adaptif.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            $('#view_kolaboratif').html(data.kolaboratif ? data.kolaboratif.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada realisasi.</span>');
                            
                            // Populate Catatan & Skala Penilai
                            var cats = ['pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'];
                            var ekspektasi = response.ekspektasi || {};
                            
                            var skalaLabels = {
                                '1': '1 - Jarang',
                                '2': '2 - Kadang-Kadang',
                                '3': '3 - Sering (Sesuai)',
                                '4': '4 - Selalu (Teladan/Melampaui)'
                            };

                            $.each(cats, function(i, cat) {
                                var nilai = ekspektasi[cat + '_nilai'];
                                var catatan = ekspektasi[cat + '_catatan'];
                                
                                var displayNilai = nilai && skalaLabels[nilai] ? skalaLabels[nilai] : (nilai ? nilai : '-');
                                $('#skala_' + cat).text(displayNilai);
                                $('#catatan_' + cat).html(catatan ? catatan.replace(/\n/g, '<br>') : '<span class="text-muted">Belum ada catatan.</span>');
                            });
                            
                            $('#btnSimpanPerilaku').hide();
                        } else {
                            $('.eval-view-mode').addClass('d-none');
                            $('.eval-catatan-mode').addClass('d-none');
                            $('.eval-input-mode').removeClass('d-none');
                            $('#btnSimpanPerilaku').show();
                            
                            $('#input_pelayanan').val(data.pelayanan);
                            $('#input_akuntabel').val(data.akuntabel);
                            $('#input_kompeten').val(data.kompeten);
                            $('#input_harmonis').val(data.harmonis);
                            $('#input_loyal').val(data.loyal);
                            $('#input_adaptif').val(data.adaptif);
                            $('#input_kolaboratif').val(data.kolaboratif);
                        }
                    } else {
                         if (mode === 'view') {
                             $('.eval-input-mode').addClass('d-none');
                             $('.eval-view-mode').removeClass('d-none');
                             $('.eval-catatan-mode').removeClass('d-none');
                             $('div[id^="view_"]').html('<span class="text-muted">Belum ada realisasi.</span>');
                             $('div[id^="catatan_"]').html('<span class="text-muted">Belum ada catatan.</span>');
                             $('span[id^="skala_"]').text('-');
                             $('#btnSimpanPerilaku').hide();
                         } else {
                             $('.eval-view-mode').addClass('d-none');
                             $('.eval-catatan-mode').addClass('d-none');
                             $('.eval-input-mode').removeClass('d-none');
                             $('#btnSimpanPerilaku').show();
                         }
                    }
                }
            }
        });
    }

    $('.btn-buat-perilaku').click(function() {
        modalModePerilaku = 'create';
        $('#evaluasi_mode_perilaku').val('create');
        $('#formPerilaku')[0].reset();
        $('#evaluasi_bulan_perilaku').val(<?= $bulan_aktif ?>);
        $('#selectMingguForm').val('<?= $minggu_aktif ?>');
        loadPerilakuMingguan(<?= $minggu_aktif ?>, 'create', <?= $bulan_aktif ?>);
        checkMingguPerilaku(<?= $minggu_aktif ?>);
        $('.modal-title').text('Buat Evaluasi Perilaku Mingguan');
        $('#modalEvaluasiPerilaku').modal('show');
    });

    $('.btn-edit-perilaku').click(function() {
        modalModePerilaku = 'edit';
        $('#evaluasi_mode_perilaku').val('edit');
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan') || new Date().getMonth() + 1;
        $('#selectMingguForm').val(minggu);
        $('#evaluasi_bulan_perilaku').val(bulan);
        loadPerilakuMingguan(minggu, 'edit', bulan);
        checkMingguPerilaku(minggu);
        $('.modal-title').text('Edit Penilaian Perilaku Mingguan');
        $('#modalEvaluasiPerilaku').modal('show');
    });

    $('.btn-view-perilaku').click(function() {
        modalModePerilaku = 'view';
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan') || new Date().getMonth() + 1;
        $('#selectMingguForm').val(minggu);
        $('#evaluasi_bulan_perilaku').val(bulan);
        loadPerilakuMingguan(minggu, 'view', bulan);
        $('.modal-title').text('Detail Penilaian Perilaku (Minggu ' + minggu + ')');
        $('#modalEvaluasiPerilaku').modal('show');
    });

    $('#selectMingguForm').change(function() {
        var mode = modalModePerilaku === 'create' ? 'create' : ($('#btnSimpanPerilaku').is(':visible') ? 'edit' : 'view');
        loadPerilakuMingguan($(this).val(), mode);
        checkMingguPerilaku($(this).val());
    });

    $('#btnSinkronisasiPerilaku').click(function() {
        var minggu = $('#selectMingguForm').val();
        var bulan = $('#evaluasi_bulan_perilaku').val();
        var tahun = $('#evaluasi_tahun_perilaku').val();
        
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyinkronkan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/perilaku/get_sebelumnya',
            type: 'GET',
            data: { minggu: minggu, bulan: bulan, tahun: tahun },
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.status == 'success') {
                    var data = response.data;
                    
                    var core_values = ['pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'];
                    
                    core_values.forEach(function(cv) {
                        if (data[cv]) {
                            $('#input_' + cv).val(data[cv]);
                        }
                    });
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data perilaku minggu sebelumnya berhasil disalin',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else if(response.status == 'empty') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Tidak ada data perilaku di minggu sebelumnya',
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
        
        var st_perilaku = $(this).data('status-perilaku');
        var fb_perilaku = $(this).data('feedback-perilaku');
        var tg_perilaku = $(this).data('tanggapan-perilaku');

        $('#feedback_minggu_ke').val(minggu);
        $('#feedback_bulan').val(bulan);

        // Perilaku
        $('#feedback_perilaku').val(fb_perilaku);
        if (st_perilaku === 'ditanggapi' || st_perilaku === 'selesai') {
            $('#areaTanggapanPerilaku').show();
            $('#text_tanggapan_perilaku').html(tg_perilaku ? tg_perilaku.replace(/\n/g, '<br>') : '-');
            $('#feedback_perilaku').prop('readonly', true);
        } else if (st_perilaku === 'diajukan') {
            $('#areaTanggapanPerilaku').hide();
            $('#feedback_perilaku').prop('readonly', true);
        } else {
            $('#areaTanggapanPerilaku').hide();
            $('#feedback_perilaku').prop('readonly', false);
        }

        if (st_perilaku === 'ditanggapi' || st_perilaku === 'selesai' || st_perilaku === 'diajukan') {
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

    $('#btnSimpanPerilaku').click(function() {
        var formData = $('#formPerilaku').serialize();
        $.ajax({
            url: '/ekip/perilaku/simpan',
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

    $('.btn-delete-perilaku').click(function() {
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
                    url: '/ekip/perilaku/hapus-minggu/' + minggu + '?bulan=' + bulan,
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
