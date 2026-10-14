<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-primary"><i class="fas fa-chart-pie"></i> Evaluasi Hasil Penilaian</h2>
            <p class="text-muted">Lihat ringkasan skor evaluasi kinerja dan perilaku.</p>
        </div>
    </div>

    <!-- Toggle Role View (For Demo Purpose) -->
    <ul class="nav nav-pills mb-4" id="roleTab" role="tablist">
      <?php if ($isPenilai || $isPicTim): ?>
      <li class="nav-item">
        <a class="nav-link active" id="penilai-tab" data-toggle="pill" href="#penilai-view" role="tab" aria-controls="penilai-view" aria-selected="true">
            <i class="fas fa-user-tie"></i> Penilai & PIC
        </a>
      </li>
      <?php endif; ?>
      <?php if($isSuperAdmin): ?>
        <li class="nav-item">
          <a class="nav-link <?= (!$isPenilai && !$isPicTim) ? 'active' : '' ?>" id="user-tab" data-toggle="pill" href="#user-view" role="tab" aria-controls="user-view" aria-selected="<?= (!$isPenilai && !$isPicTim) ? 'true' : 'false' ?>">
              <i class="fas fa-user"></i> Super Admin
          </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content" id="roleTabContent">
        <!-- 👨‍💼 VIEW PENILAI -->
        <div class="tab-pane fade <?= ($isPenilai || $isPicTim) ? 'show active' : '' ?>" id="penilai-view" role="tabpanel" aria-labelledby="penilai-tab">
            <ul class="nav nav-tabs mb-3" id="penilaiSubTab" role="tablist">
                <?php if ($isPenilai): ?>
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold text-primary" id="kinerja-perilaku-tab" data-toggle="tab" href="#kinerja-perilaku" role="tab" aria-controls="kinerja-perilaku" aria-selected="true">
                        <i class="fas fa-clipboard-check"></i> Penilaian Kinerja dan Perilaku
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($isPicTim): ?>
                <li class="nav-item">
                    <a class="nav-link <?= !$isPenilai ? 'active' : '' ?> font-weight-bold text-primary" id="hasil-360-tab" data-toggle="tab" href="#hasil-360" role="tab" aria-controls="hasil-360" aria-selected="false">
                        <i class="fas fa-users"></i> Hasil Penilaian 360
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content" id="penilaiSubTabContent">
                <!-- Tab Penilaian Kinerja dan Perilaku -->
                <?php if ($isPenilai): ?>
                <div class="tab-pane fade show active kinerja-perilaku-wrapper" id="kinerja-perilaku" role="tabpanel" aria-labelledby="kinerja-perilaku-tab">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title text-primary font-weight-bold">Daftar Pegawai yang Dinilai (Berdasarkan PIC Tim Kerja)</h3>
                        </div>
        <div class="card-body bg-light">
                            <!-- Filter Minimalis -->
                            <div class="d-flex flex-wrap align-items-center mb-3">
                                <div class="form-group mr-3 mb-2" style="width: auto;">
                                    <input type="text" class="form-control form-control-sm border-primary searchPegawaiPic" placeholder="Cari nama pegawai..." style="min-width: 200px; border-radius: 4px;">
                                </div>

                                <div class="form-group mr-3 mb-2" style="width: auto;">
                                    <select class="form-control form-control-sm border-primary filterTahunKinerja" style="min-width: 100px; border-radius: 4px;">
                                        <option value="" disabled>Tahun</option>
                                        <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                                            <option value="<?= $y ?>" <?= $y == $tahun_aktif ? 'selected' : '' ?>><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-3 mb-2" style="width: auto;">
                                    <select class="form-control form-control-sm border-primary filterBulanKinerja" style="min-width: 120px; border-radius: 4px;">
                                        <option value="" disabled>Bulan</option>
                                        <?php 
                                        $bulan_indo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                        foreach($bulan_indo as $num => $nama): 
                                        ?>
                                            <option value="<?= $num ?>" <?= $num == $bulan_aktif ? 'selected' : '' ?>><?= $nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group mr-3 mb-2" style="width: auto;">
                                    <select class="form-control form-control-sm border-primary filterMingguKinerja" style="min-width: 140px; border-radius: 4px;">
                                        <option value="all" <?= $minggu_aktif === 'all' ? 'selected' : '' ?>>Semua Minggu</option>
                                        <?php 
                                        $reversed_minggu = array_reverse($list_minggu, true);
                                        foreach($reversed_minggu as $i => $minggu): 
                                        ?>
                                            <option value="<?= $i ?>" <?= (string)$i === (string)$minggu_aktif ? 'selected' : '' ?>>Minggu <?= $i ?> (<?= esc($minggu['label']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group mb-2 ml-auto d-flex align-items-center" style="width: auto;">
                                    <button type="button" class="btn btn-sm btn-outline-info mr-2" id="btnSinkronisasiGlobalPenilai" title="Sinkronisasi seluruh hasil penilaian dari minggu sebelumnya" style="white-space: nowrap; padding-left: 15px; padding-right: 15px; border-radius: 4px;">
                                        <i class="fas fa-sync-alt"></i> Sinkronisasi Global
                                    </button>
                                    <select class="form-control form-control-sm border-primary filterStatusKinerja" style="min-width: 170px; border-radius: 4px;">
                                        <option value="all" <?= (!isset($status_aktif) || $status_aktif == 'all') ? 'selected' : '' ?>>Semua Status</option>
                                        <option value="pending" <?= (isset($status_aktif) && $status_aktif == 'pending') ? 'selected' : '' ?>>Menunggu Penilaian</option>
                                        <option value="done" <?= (isset($status_aktif) && $status_aktif == 'done') ? 'selected' : '' ?>>Sudah Dinilai</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive bg-white rounded shadow-sm">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Pegawai</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Hasil Penilaian</th>
                                            <th>Predikat</th>
                                            <th class="text-center">Lihat & Beri Penilaian</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyKinerjaPerilaku_penilai" class="tbodyKinerjaPerilaku">
                                        <?php if(empty($penilaiData)): ?>
                                        <tr><td colspan="7" class="text-center">Belum ada pegawai yang Anda nilai.</td></tr>
                                        <?php else: ?>
                                        <?php 
                                        $no=1; 
                                        foreach($penilaiData as $pd): 
                                            foreach($reversed_minggu as $m => $minggu):
                                                $nama_bulan_en = DateTime::createFromFormat('!m', $bulan_aktif)->format('F');
                                                $bulan_indo = [
                                                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                                                ];
                                                $nama_bulan = $bulan_indo[$nama_bulan_en];

                                                $status = isset($statusPenilaian[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]) ? 'done' : 'pending';
                                                $kinerjaIsi = isset($statusKinerjaPegawai[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                $perilakuIsi = isset($statusPerilakuPegawai[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                $observasiIsi = isset($statusObservasiKhusus[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                $kalibrasiNilai = isset($statusKalibrasi[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]) ? $statusKalibrasi[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m] : null;

                                                $p_kinerja = '-';
                                                        $p_perilaku = '-';
                                                        $hasil_akhir = '-';
                                                        $bg_hasil = 'bg-light text-muted';
                                                        $status_feedback_kinerja = 'none';
                                                        $feedback_kinerja = '';
                                                        $tanggapan_kinerja = '';
                                                        $status_feedback_perilaku = 'none';
                                                        $feedback_perilaku = '';
                                                        $tanggapan_perilaku = '';

                                                        if (isset($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m])) {
                                                            $p_kinerja = !empty($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_kinerja']) ? $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_kinerja'] : '-';
                                                            $p_perilaku = !empty($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_perilaku']) ? $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_perilaku'] : '-';
                                                            
                                                            // Fallback otomatis jika predikat_perilaku masih kosong/'-' tapi nilai kalibrasi sudah ada
                                                            if ($p_perilaku === '-' && $kalibrasiNilai !== null) {
                                                                $gTotal = (float)$kalibrasiNilai;
                                                                if ($gTotal < 70) {
                                                                    $p_perilaku = 'Kurang dan Sangat Kurang';
                                                                } elseif ($gTotal >= 70 && $gTotal < 90) {
                                                                    $p_perilaku = 'Butuh Perbaikan';
                                                                } elseif ($gTotal >= 90 && $gTotal < 110) {
                                                                    $p_perilaku = 'Baik';
                                                                } elseif ($gTotal >= 110) {
                                                                    $p_perilaku = 'Baik Sekali';
                                                                }
                                                            }
                                                            
                                                            $status_feedback_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['status_feedback_kinerja'];
                                                            $feedback_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['feedback_kinerja'];
                                                            $tanggapan_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['tanggapan_kinerja'];
                                                            $status_feedback_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['status_feedback_perilaku'];
                                                            $feedback_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['feedback_perilaku'];
                                                            $tanggapan_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['tanggapan_perilaku'];

                                                    // Normalize Perilaku for Matrix Calculation
                                                    $norm_perilaku = $p_perilaku;
                                                    if (in_array($p_perilaku, ['Baik Sekali'])) {
                                                        $norm_perilaku = 'Di Atas Ekspektasi';
                                                    } elseif (in_array($p_perilaku, ['Baik'])) {
                                                        $norm_perilaku = 'Sesuai Ekspektasi';
                                                    } elseif (in_array($p_perilaku, ['Butuh Perbaikan', 'Kurang dan Sangat Kurang'])) {
                                                        $norm_perilaku = 'Di Bawah Ekspektasi';
                                                    }

                                                    // Calculate Hasil Akhir based on Matriks
                                                    if ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                        $hasil_akhir = 'Sangat Baik'; $bg_hasil = 'bg-success';
                                                    } elseif ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                        $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                    } elseif ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                        $hasil_akhir = 'Kurang'; $bg_hasil = 'bg-danger';
                                                    } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                        $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                    } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                        $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                    } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                        $hasil_akhir = 'Kurang'; $bg_hasil = 'bg-danger';
                                                    } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                        $hasil_akhir = 'Butuh Perbaikan'; $bg_hasil = 'bg-warning text-dark';
                                                    } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                        $hasil_akhir = 'Butuh Perbaikan'; $bg_hasil = 'bg-warning text-dark';
                                                    } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                        $hasil_akhir = 'Sangat Kurang'; $bg_hasil = 'bg-danger';
                                                    }
                                                }

                                                // Update status: hanya "done" jika kedua predikat sudah diisi
                                                if ($p_kinerja === '-' || $p_perilaku === '-') {
                                                    $status = 'pending';
                                                } else {
                                                    $status = 'done';
                                                }
                                                
                                                $isReady = ($kinerjaIsi || $perilakuIsi) ? '1' : '0';
                                        ?>
                                        <tr class="row-kinerja-perilaku" data-minggu="<?= $m ?>" data-status="<?= $status ?>" data-ready="<?= $isReady ?>">
                                            <td class="row-no"><?= $no++ ?></td>
                                            <td>
                                                <span class="font-weight-bold"><?= esc($pd['nama']) ?></span><br>
                                                <small class="text-muted">NIP. <?= esc($pd['nip']) ?></small>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">Minggu <?= $m ?></span><br>
                                                <small class="text-muted"><?= esc($minggu['label']) ?></small>
                                            </td>
                                            <td>
                                                <?php if($status == 'done'): ?>
                                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                                                    <?php if($status_feedback_kinerja == 'diajukan'): ?>
                                                        <br><span class="badge badge-warning mt-1"><i class="fas fa-exclamation-circle"></i> Feedback Kinerja!</span>
                                                    <?php elseif($status_feedback_kinerja == 'ditanggapi' || $status_feedback_kinerja == 'selesai'): ?>
                                                        <br><span class="badge badge-info mt-1"><i class="fas fa-reply"></i> Kinerja Ditanggapi</span>
                                                    <?php endif; ?>
                                                    <?php if($status_feedback_perilaku == 'diajukan'): ?>
                                                        <br><span class="badge badge-warning mt-1"><i class="fas fa-exclamation-circle"></i> Feedback Perilaku!</span>
                                                    <?php elseif($status_feedback_perilaku == 'ditanggapi' || $status_feedback_perilaku == 'selesai'): ?>
                                                        <br><span class="badge badge-info mt-1"><i class="fas fa-reply"></i> Perilaku Ditanggapi</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Penilaian</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column align-items-start">
                                                    <span class="badge badge-light border font-weight-normal text-muted mb-1">Kinerja: <span class="font-weight-bold text-dark"><?= $p_kinerja ?></span></span>
                                                    <span class="badge badge-light border font-weight-normal text-muted mb-1">Perilaku: <span class="font-weight-bold text-dark"><?= $p_perilaku ?></span></span>
                                                    <?php if($kalibrasiNilai !== null): ?>
                                                    <span class="badge badge-success font-weight-normal mb-1">Kalibrasi: <span class="font-weight-bold"><?= $kalibrasiNilai ?></span></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge <?= $bg_hasil ?> px-2 py-1"><?= $hasil_akhir ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                    // Pastikan variabel ada di scope Penilai/PIC Tim
                                                    $observasiIsi = isset($statusObservasiKhusus[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                    $rekomendasiIsi = isset($statusRekomendasi[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                ?>
                                                <?php if($observasiIsi): ?>
                                                            <button class="btn btn-sm btn-warning btn-observasi-perilaku mb-1 text-dark" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Ekspektasi Khusus Pimpinan Telah Diisi" id="btn-observasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-landmark"></i></button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-warning btn-observasi-perilaku mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Observasi Perilaku" id="btn-observasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-landmark"></i></button>
                                                        <?php endif; ?>

                                                        <?php if($rekomendasiIsi): ?>
                                                            <button class="btn btn-sm btn-success btn-rekomendasi-kebijakan mb-1 text-white" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rekomendasi Kebijakan (Telah Diisi)" id="btn-rekomendasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-tasks"></i></button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-success btn-rekomendasi-kebijakan mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rekomendasi Kebijakan (Tugas Tambahan)" id="btn-rekomendasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-tasks"></i></button>
                                                        <?php endif; ?>
                                                
                                                <?php if($kinerjaIsi): ?>
                                                    <button class="btn btn-sm btn-outline-info btn-lihat-kinerja mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" title="Lihat Kinerja"><i class="fas fa-clipboard-list"></i> Kinerja</button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary disabled mb-1" title="Pegawai belum mengisi Kinerja" style="cursor: not-allowed;"><i class="fas fa-times-circle"></i> Kinerja (Belum Isi)</button>
                                                <?php endif; ?>

                                                <button type="button" class="btn btn-sm btn-outline-success btn-kalibrasi-modal mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-nip="<?= esc($pd['nip']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Buka Form Kalibrasi Perilaku"><i class="fas fa-balance-scale"></i> Kalibrasi</button>
                                                
                                                <button class="btn btn-sm btn-outline-dark btn-sinkron-per-orang mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Sinkronisasi seluruh hasil penilaian untuk pegawai ini dari minggu sebelumnya"><i class="fas fa-sync-alt"></i></button>

                                                <?php if($status == 'done'): ?>
                                                    <button class="btn btn-sm btn-outline-danger btn-rollback-penilaian mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rollback Penilaian"><i class="fas fa-undo"></i></button>
                                                    <?php 
                                                        if($status_feedback_kinerja != 'none' || $status_feedback_perilaku != 'none'): 
                                                            $isDiajukan = ($status_feedback_kinerja == 'diajukan' || $status_feedback_perilaku == 'diajukan');
                                                            $btnClass = $isDiajukan ? 'btn-warning btn-pulse-warning text-dark' : 'btn-info';
                                                    ?>
                                                    <button class="btn btn-sm <?= $btnClass ?> btn-tanggapi-feedback mb-1" 
                                                        data-id="<?= $pd['dinilai_id'] ?>" 
                                                        data-nama="<?= esc($pd['nama']) ?>" 
                                                        data-minggu="<?= $m ?>" 
                                                        data-bulan="<?= $bulan_aktif ?>" 
                                                        data-tahun="<?= $tahun_aktif ?>"
                                                        data-fb-kinerja="<?= esc($feedback_kinerja) ?>"
                                                        data-tg-kinerja="<?= esc($tanggapan_kinerja) ?>"
                                                        data-st-kinerja="<?= esc($status_feedback_kinerja) ?>"
                                                        data-fb-perilaku="<?= esc($feedback_perilaku) ?>"
                                                        data-tg-perilaku="<?= esc($tanggapan_perilaku) ?>"
                                                        data-st-perilaku="<?= esc($status_feedback_perilaku) ?>"
                                                        title="Tanggapi Feedback">
                                                        <i class="fas fa-comment-dots fa-lg"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        endforeach; 
                                        ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tab Hasil 360 -->
                <?php if ($isPicTim): ?>
                <div class="tab-pane fade <?= !$isPenilai ? 'show active' : '' ?> hasil-360-wrapper" id="hasil-360" role="tabpanel" aria-labelledby="hasil-360-tab">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title text-primary font-weight-bold">Akumulasi Penilaian 360 (Berdasarkan PIC Tim Kerja)</h3>
                            <div class="card-tools d-flex align-items-center">
                                <select class="form-control form-control-sm border-primary mr-2 filterTahunHasil360" id="filterTahunHasil360" style="width: 100px;">
                                    <?php for($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                                        <option value="<?= $y ?>" <?= $tahun_aktif == $y ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                                <select class="form-control form-control-sm border-primary mr-2 filterBulanHasil360" id="filterBulanHasil360" style="width: 120px;">
                                    <?php 
                                    $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                    foreach($bulanIndo as $num => $namaBulan): ?>
                                        <option value="<?= $num ?>" <?= $bulan_aktif == $num ? 'selected' : '' ?>><?= $namaBulan ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control form-control-sm border-primary filterMingguHasil360" id="filterMingguHasil360" style="width: 250px;">
                                    <?php foreach($list_minggu as $num => $minggu): ?>
                                        <option value="<?= $num ?>" <?= $minggu_aktif == $num ? 'selected' : '' ?>>Minggu <?= $num ?> (<?= $minggu['label'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-body bg-light p-0">
                            <div class="table-responsive bg-white rounded shadow-sm">
                                <table class="table table-sm table-hover table-striped mb-0 text-sm table-bordered">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th rowspan="2" class="align-middle">No</th>
                                            <th rowspan="2" class="align-middle">Pegawai</th>
                                            <th colspan="4">Akumulasi Aspek Penugasan</th>
                                            <th colspan="3">Akumulasi Kompetensi & Perilaku</th>
                                            <th rowspan="2" class="align-middle">Rata-rata Skor</th>
                                            <th rowspan="2" class="align-middle">Peringkat</th>
                                            <th rowspan="2" class="align-middle">Justifikasi</th>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-normal">Hasil</th>
                                            <th class="font-weight-normal">Kualitas</th>
                                            <th class="font-weight-normal">Waktu</th>
                                            <th class="font-weight-normal">Efektivitas</th>
                                            <th class="font-weight-normal">Tanggung Jawab</th>
                                            <th class="font-weight-normal">Kerjasama</th>
                                            <th class="font-weight-normal">Presensi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyHasil360_penilai" class="tbodyHasil360">
                                        <?php if(empty($dataPegawaiTim)): ?>
                                        <tr><td colspan="10" class="text-center">Belum ada pegawai di tim Anda.</td></tr>
                                        <?php else: ?>
                                        <?php foreach($dataPegawaiTim as $index => $pd): ?>
                                            <?php 
                                            // Pre-render data into data attributes for filtering
                                            $json360 = htmlspecialchars(json_encode($akumulasi360[$pd['dinilai_id']] ?? (object)[]), ENT_QUOTES, 'UTF-8');
                                            ?>
                                            <tr class="row-hasil-360" data-360="<?= $json360 ?>">
                                                <td class="text-center row-no"><?= $index + 1 ?></td>
                                                <td>
                                                    <span class="font-weight-bold"><?= esc($pd['nama']) ?></span><br>
                                                    <small class="text-muted">NIP. <?= esc($pd['nip']) ?></small>
                                                </td>
                                                <?php if($isPenilai): ?>
                                                <td class="p-1 val-hasil"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="hasil_pekerjaan" min="0" max="5"></td>
                                                <td class="p-1 val-kualitas"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="kualitas_pekerjaan" min="0" max="5"></td>
                                                <td class="p-1 val-waktu"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="ketepatan_waktu" min="0" max="5"></td>
                                                <td class="p-1 val-efektivitas"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="efektivitas_efesiensi" min="0" max="5"></td>
                                                <td class="p-1 val-tanggung"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="tanggung_jawab" min="0" max="5"></td>
                                                <td class="p-1 val-kerjasama"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="kerjasama" min="0" max="5"></td>
                                                <td class="p-1 val-presensi"><input type="number" step="0.01" class="form-control form-control-sm text-center input-360-final" data-field="presensi" min="0" max="5"></td>
                                                <td class="text-center font-weight-bold text-primary val-total align-middle">-</td>
                                                <td class="text-center font-weight-bold text-success val-ranking align-middle">-</td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-success btn-simpan-360-final mr-1" data-id="<?= $pd['dinilai_id'] ?>" data-tahun="<?= $tahun_aktif ?>" title="Simpan Nilai Final 360">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-lihat-justifikasi" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" title="Lihat Justifikasi">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </td>
                                                <?php else: ?>
                                                <td class="text-center val-hasil">-</td>
                                                <td class="text-center val-kualitas">-</td>
                                                <td class="text-center val-waktu">-</td>
                                                <td class="text-center val-efektivitas">-</td>
                                                <td class="text-center val-tanggung">-</td>
                                                <td class="text-center val-kerjasama">-</td>
                                                <td class="text-center val-presensi">-</td>
                                                <td class="text-center font-weight-bold text-primary val-total">-</td>
                                                <td class="text-center font-weight-bold text-success val-ranking">-</td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-lihat-justifikasi" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" title="Lihat Justifikasi">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 👑 SUPER ADMIN VIEW -->
        <?php if($isSuperAdmin): ?>
        <div class="tab-pane fade <?= (!$isPenilai && !$isPicTim) ? 'show active' : '' ?>" id="user-view" role="tabpanel" aria-labelledby="user-tab">
            <div class="card card-outline card-success shadow-sm mb-4 border-top-0 border-left-0 border-right-0" style="border-radius: 8px;">
                <div class="card-body bg-light p-4 rounded">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <h4 class="text-success mb-0 font-weight-bold"><i class="fas fa-users-cog mr-2"></i> Manajemen Evaluasi Semua Tim Kerja</h4>
                            <p class="text-muted mb-0 mt-1 small">Silakan pilih tim kerja untuk melihat data evaluasi kinerja dan penilaian 360.</p>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group input-group-lg shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-success text-white border-success"><i class="fas fa-filter"></i></span>
                                </div>
                                <select class="form-control border-success text-dark font-weight-bold" id="filterTimKerjaSA" style="height: 48px; font-size: 1.1rem; cursor: pointer;">
                                    <?php foreach($semuaTimKerja as $idx => $tim): ?>
                                        <option value="sa-tim-<?= $tim['id_tim_kerja'] ?>"><?= esc($tim['nama_tim_kerja']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="saTeamTabContent">
                <?php foreach($semuaTimKerja as $idx => $tim): ?>
                <div class="tab-pane fade sa-tim-pane <?= $idx == 0 ? 'show active' : '' ?>" id="sa-tim-<?= $tim['id_tim_kerja'] ?>" role="tabpanel" aria-labelledby="sa-tim-<?= $tim['id_tim_kerja'] ?>-tab">
                    
                    <ul class="nav nav-tabs mb-3" id="saSubTab-<?= $tim['id_tim_kerja'] ?>" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold text-success" id="sa-kp-<?= $tim['id_tim_kerja'] ?>-tab" data-toggle="tab" href="#sa-kp-<?= $tim['id_tim_kerja'] ?>" role="tab" aria-controls="sa-kp-<?= $tim['id_tim_kerja'] ?>" aria-selected="true">
                                <i class="fas fa-clipboard-check"></i> Penilaian Kinerja dan Perilaku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-success" id="sa-360-<?= $tim['id_tim_kerja'] ?>-tab" data-toggle="tab" href="#sa-360-<?= $tim['id_tim_kerja'] ?>" role="tab" aria-controls="sa-360-<?= $tim['id_tim_kerja'] ?>" aria-selected="false">
                                <i class="fas fa-users"></i> Hasil Penilaian 360
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="saSubTabContent-<?= $tim['id_tim_kerja'] ?>">
                        <!-- Tab Kinerja & Perilaku -->
                        <div class="tab-pane fade show active kinerja-perilaku-wrapper" id="sa-kp-<?= $tim['id_tim_kerja'] ?>" role="tabpanel" aria-labelledby="sa-kp-<?= $tim['id_tim_kerja'] ?>-tab">
                            <div class="card card-outline card-success shadow-sm">
                                <div class="card-header bg-white">
                                    <h3 class="card-title text-success font-weight-bold">Daftar Pegawai (Tim: <?= esc($tim['nama_tim_kerja']) ?>)</h3>
                                </div>
                                <div class="card-body bg-light">
                                    <!-- Filter Minimalis -->
                                    <div class="d-flex flex-wrap align-items-center mb-3">
                                        <div class="form-group mr-3 mb-2" style="width: auto;">
                                            <input type="text" class="form-control form-control-sm border-success searchPegawaiSA" data-target="#tbodyKinerjaPerilaku_sa_<?= $tim['id_tim_kerja'] ?>" placeholder="Cari nama pegawai..." style="min-width: 200px; border-radius: 4px;">
                                        </div>

                                        <div class="form-group mr-3 mb-2" style="width: auto;">
                                            <select class="form-control form-control-sm border-success filterTahunKinerja" style="min-width: 100px; border-radius: 4px;">
                                                <option value="" disabled>Tahun</option>
                                                <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                                                    <option value="<?= $y ?>" <?= $y == $tahun_aktif ? 'selected' : '' ?>><?= $y ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group mr-3 mb-2" style="width: auto;">
                                            <select class="form-control form-control-sm border-success filterBulanKinerja" style="min-width: 120px; border-radius: 4px;">
                                                <option value="" disabled>Bulan</option>
                                                <?php 
                                                $bulan_indo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                                foreach($bulan_indo as $num => $nama): 
                                                ?>
                                                    <option value="<?= $num ?>" <?= $num == $bulan_aktif ? 'selected' : '' ?>><?= $nama ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group mr-3 mb-2" style="width: auto;">
                                            <select class="form-control form-control-sm border-success filterMingguKinerja" style="min-width: 140px; border-radius: 4px;">
                                                <option value="all" <?= $minggu_aktif === 'all' ? 'selected' : '' ?>>Semua Minggu</option>
                                                <?php 
                                                $reversed_minggu = array_reverse($list_minggu, true);
                                                foreach($reversed_minggu as $i => $minggu): 
                                                ?>
                                                    <option value="<?= $i ?>" <?= (string)$i === (string)$minggu_aktif ? 'selected' : '' ?>>Minggu <?= $i ?> (<?= esc($minggu['label']) ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group mb-2 ml-auto d-flex align-items-center" style="width: auto;">
                                    <button type="button" class="btn btn-sm btn-outline-info mr-2" id="btnSinkronisasiGlobalAdmin" title="Sinkronisasi seluruh hasil penilaian dari minggu sebelumnya" style="white-space: nowrap; padding-left: 15px; padding-right: 15px; border-radius: 4px;">
                                        <i class="fas fa-sync-alt"></i> Sinkronisasi Global
                                    </button>
                                    <select class="form-control form-control-sm border-primary filterStatusKinerja" style="min-width: 170px; border-radius: 4px;">
                                                <option value="all" <?= (!isset($status_aktif) || $status_aktif == 'all') ? 'selected' : '' ?>>Semua Status</option>
                                                <option value="pending" <?= (isset($status_aktif) && $status_aktif == 'pending') ? 'selected' : '' ?>>Menunggu Penilaian</option>
                                                <option value="done" <?= (isset($status_aktif) && $status_aktif == 'done') ? 'selected' : '' ?>>Sudah Dinilai</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive bg-white rounded shadow-sm">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="bg-success text-white">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pegawai</th>
                                                    <th>Periode</th>
                                                    <th>Status</th>
                                                    <th>Hasil Penilaian</th>
                                                    <th>Predikat</th>
                                                    <th class="text-center">Lihat & Beri Penilaian</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbodyKinerjaPerilaku" id="tbodyKinerjaPerilaku_sa_<?= $tim['id_tim_kerja'] ?>">
                                                <?php $timPegawai = $superAdminDataPegawai[$tim['id_tim_kerja']] ?? []; ?>
                                                <?php if(empty($timPegawai)): ?>
                                                <tr><td colspan="7" class="text-center">Belum ada pegawai di tim ini.</td></tr>
                                                <?php else: ?>
                                                <?php 
                                                $no=1; 
                                                foreach($timPegawai as $pd): 
                                                    foreach($reversed_minggu as $m => $minggu):
                                                        $nama_bulan_en = DateTime::createFromFormat('!m', $bulan_aktif)->format('F');
                                                        $bulan_indo = [
                                                            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                                            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                                            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                                            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                                                        ];
                                                        $nama_bulan = $bulan_indo[$nama_bulan_en];

                                                        $status = isset($statusPenilaian[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]) ? 'done' : 'pending';
                                                        $kinerjaIsi = isset($statusKinerjaPegawai[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                        $perilakuIsi = isset($statusPerilakuPegawai[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);

                                                        $p_kinerja = '-';
                                                        $p_perilaku = '-';
                                                        $hasil_akhir = '-';
                                                        $bg_hasil = 'bg-light text-muted';
                                                        $status_feedback_kinerja = 'none';
                                                        $feedback_kinerja = '';
                                                        $tanggapan_kinerja = '';
                                                        $status_feedback_perilaku = 'none';
                                                        $feedback_perilaku = '';
                                                        $tanggapan_perilaku = '';

                                                        if (isset($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m])) {
                                                            $p_kinerja = !empty($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_kinerja']) ? $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_kinerja'] : '-';
                                                            $p_perilaku = !empty($statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_perilaku']) ? $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['predikat_perilaku'] : '-';
                                                            
                                                            // Fallback otomatis jika predikat_perilaku masih kosong/'-' tapi nilai kalibrasi sudah ada
                                                            if ($p_perilaku === '-' && $kalibrasiNilai !== null) {
                                                                $gTotal = (float)$kalibrasiNilai;
                                                                if ($gTotal < 70) {
                                                                    $p_perilaku = 'Kurang dan Sangat Kurang';
                                                                } elseif ($gTotal >= 70 && $gTotal < 90) {
                                                                    $p_perilaku = 'Butuh Perbaikan';
                                                                } elseif ($gTotal >= 90 && $gTotal < 110) {
                                                                    $p_perilaku = 'Baik';
                                                                } elseif ($gTotal >= 110) {
                                                                    $p_perilaku = 'Baik Sekali';
                                                                }
                                                            }

                                                            $status_feedback_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['status_feedback_kinerja'];
                                                            $feedback_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['feedback_kinerja'];
                                                            $tanggapan_kinerja = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['tanggapan_kinerja'];
                                                            $status_feedback_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['status_feedback_perilaku'];
                                                            $feedback_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['feedback_perilaku'];
                                                            $tanggapan_perilaku = $statusPenilaianData[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]['tanggapan_perilaku'];

                                                            // Normalize Perilaku for Matrix Calculation
                                                            $norm_perilaku = $p_perilaku;
                                                            if (in_array($p_perilaku, ['Baik Sekali'])) {
                                                                $norm_perilaku = 'Di Atas Ekspektasi';
                                                            } elseif (in_array($p_perilaku, ['Baik'])) {
                                                                $norm_perilaku = 'Sesuai Ekspektasi';
                                                            } elseif (in_array($p_perilaku, ['Butuh Perbaikan', 'Kurang dan Sangat Kurang'])) {
                                                                $norm_perilaku = 'Di Bawah Ekspektasi';
                                                            }

                                                            // Calculate Hasil Akhir based on Matriks
                                                            if ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                                $hasil_akhir = 'Sangat Baik'; $bg_hasil = 'bg-success';
                                                            } elseif ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                                $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                            } elseif ($p_kinerja == 'Di Atas Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                                $hasil_akhir = 'Kurang'; $bg_hasil = 'bg-danger';
                                                            } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                                $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                            } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                                $hasil_akhir = 'Baik'; $bg_hasil = 'bg-info';
                                                            } elseif ($p_kinerja == 'Sesuai Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                                $hasil_akhir = 'Kurang'; $bg_hasil = 'bg-danger';
                                                            } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Di Atas Ekspektasi') {
                                                                $hasil_akhir = 'Butuh Perbaikan'; $bg_hasil = 'bg-warning text-dark';
                                                            } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Sesuai Ekspektasi') {
                                                                $hasil_akhir = 'Butuh Perbaikan'; $bg_hasil = 'bg-warning text-dark';
                                                            } elseif ($p_kinerja == 'Di Bawah Ekspektasi' && $norm_perilaku == 'Di Bawah Ekspektasi') {
                                                                $hasil_akhir = 'Sangat Kurang'; $bg_hasil = 'bg-danger';
                                                            }
                                                        }

                                                        // Update status
                                                        if ($p_kinerja === '-' || $p_perilaku === '-') {
                                                            $status = 'pending';
                                                        } else {
                                                            $status = 'done';
                                                        }
                                                        
                                                        $isReady = ($kinerjaIsi || $perilakuIsi) ? '1' : '0';
                                                ?>
                                                <tr class="row-kinerja-perilaku" data-minggu="<?= $m ?>" data-status="<?= $status ?>" data-ready="<?= $isReady ?>">
                                                    <td class="row-no"><?= $no++ ?></td>
                                                    <td>
                                                        <span class="font-weight-bold"><?= esc($pd['nama']) ?></span><br>
                                                        <small class="text-muted">NIP. <?= esc($pd['nip']) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="font-weight-bold">Minggu <?= $m ?></span><br>
                                                        <small class="text-muted"><?= esc($minggu['label']) ?></small>
                                                    </td>
                                                    <td>
                                                    <?php if($status == 'done'): ?>
                                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                                                    <?php if($status_feedback_kinerja == 'diajukan'): ?>
                                                        <br><span class="badge badge-warning mt-1"><i class="fas fa-exclamation-circle"></i> Feedback Kinerja!</span>
                                                    <?php elseif($status_feedback_kinerja == 'ditanggapi' || $status_feedback_kinerja == 'selesai'): ?>
                                                        <br><span class="badge badge-info mt-1"><i class="fas fa-reply"></i> Kinerja Ditanggapi</span>
                                                    <?php endif; ?>
                                                    <?php if($status_feedback_perilaku == 'diajukan'): ?>
                                                        <br><span class="badge badge-warning mt-1"><i class="fas fa-exclamation-circle"></i> Feedback Perilaku!</span>
                                                    <?php elseif($status_feedback_perilaku == 'ditanggapi' || $status_feedback_perilaku == 'selesai'): ?>
                                                        <br><span class="badge badge-info mt-1"><i class="fas fa-reply"></i> Perilaku Ditanggapi</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Penilaian</span>
                                                <?php endif; ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex flex-column align-items-start">
                                                            <span class="badge badge-light border font-weight-normal text-muted mb-1">Kinerja: <span class="font-weight-bold text-dark"><?= $p_kinerja ?></span></span>
                                                            <span class="badge badge-light border font-weight-normal text-muted mb-1">Perilaku: <span class="font-weight-bold text-dark"><?= $p_perilaku ?></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge <?= $bg_hasil ?> px-2 py-1"><?= $hasil_akhir ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php 
                                                            // Deklarasikan variabel jika belum ada di scope ini (untuk tampilan card / mobile mode jika ada)
                                                            $observasiIsi = isset($statusObservasiKhusus[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                            $rekomendasiIsi = isset($statusRekomendasi[$pd['dinilai_id']][$tahun_aktif][$bulan_aktif][$m]);
                                                        ?>
                                                        <?php if($observasiIsi): ?>
                                                            <button class="btn btn-sm btn-warning btn-observasi-perilaku mb-1 text-dark" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Ekspektasi Khusus Pimpinan Telah Diisi" id="btn-observasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-landmark"></i></button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-warning btn-observasi-perilaku mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Observasi Perilaku" id="btn-observasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-landmark"></i></button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($rekomendasiIsi): ?>
                                                            <button class="btn btn-sm btn-success btn-rekomendasi-kebijakan mb-1 text-white" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rekomendasi Kebijakan (Telah Diisi)" id="btn-rekomendasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-tasks"></i></button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-success btn-rekomendasi-kebijakan mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rekomendasi Kebijakan (Tugas Tambahan)" id="btn-rekomendasi-<?= $pd['dinilai_id'] ?>-<?= $m ?>"><i class="fas fa-tasks"></i></button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($kinerjaIsi): ?>
                                                            <button class="btn btn-sm btn-outline-info btn-lihat-kinerja mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" data-minggu="<?= $m ?>" title="Lihat Kinerja"><i class="fas fa-clipboard-list"></i> Kinerja</button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-secondary disabled mb-1" title="Pegawai belum mengisi Kinerja" style="cursor: not-allowed;"><i class="fas fa-times-circle"></i> Kinerja (Belum Isi)</button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($status == 'done'): ?>
                                                    <button class="btn btn-sm btn-outline-danger btn-rollback-penilaian mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Rollback Penilaian"><i class="fas fa-undo"></i></button>
                                                    <?php 
                                                        if($status_feedback_kinerja != 'none' || $status_feedback_perilaku != 'none'): 
                                                            $isDiajukan = ($status_feedback_kinerja == 'diajukan' || $status_feedback_perilaku == 'diajukan');
                                                            $btnClass = $isDiajukan ? 'btn-warning btn-pulse-warning text-dark' : 'btn-info';
                                                    ?>
                                                    <button class="btn btn-sm <?= $btnClass ?> btn-tanggapi-feedback mb-1" 
                                                        data-id="<?= $pd['dinilai_id'] ?>" 
                                                        data-nama="<?= esc($pd['nama']) ?>" 
                                                        data-minggu="<?= $m ?>" 
                                                        data-bulan="<?= $bulan_aktif ?>" 
                                                        data-tahun="<?= $tahun_aktif ?>"
                                                        data-fb-kinerja="<?= esc($feedback_kinerja) ?>"
                                                        data-tg-kinerja="<?= esc($tanggapan_kinerja) ?>"
                                                        data-st-kinerja="<?= esc($status_feedback_kinerja) ?>"
                                                        data-fb-perilaku="<?= esc($feedback_perilaku) ?>"
                                                        data-tg-perilaku="<?= esc($tanggapan_perilaku) ?>"
                                                        data-st-perilaku="<?= esc($status_feedback_perilaku) ?>"
                                                        title="Tanggapi Feedback">
                                                        <i class="fas fa-comment-dots fa-lg"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-sm btn-outline-dark btn-sinkron-per-orang-admin mb-1" data-id="<?= $pd['dinilai_id'] ?>" data-minggu="<?= $m ?>" data-tahun="<?= $tahun_aktif ?>" data-bulan="<?= $bulan_aktif ?>" title="Sinkronisasi seluruh hasil penilaian untuk pegawai ini dari minggu sebelumnya"><i class="fas fa-sync-alt"></i></button>
                                                
                                                    </td>
                                                </tr>
                                                <?php 
                                                    endforeach;
                                                endforeach; 
                                                ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Hasil 360 -->
                        <div class="tab-pane fade hasil-360-wrapper" id="sa-360-<?= $tim['id_tim_kerja'] ?>" role="tabpanel" aria-labelledby="sa-360-<?= $tim['id_tim_kerja'] ?>-tab">
                            <div class="card card-outline card-success shadow-sm">
                                <div class="card-header bg-white">
                                    <h3 class="card-title text-success font-weight-bold">Akumulasi Penilaian 360 (Tim: <?= esc($tim['nama_tim_kerja']) ?>)</h3>
                                    <div class="card-tools d-flex align-items-center">
                                        <select class="form-control form-control-sm border-success mr-2 filterTahunHasil360" style="width: 100px;">
                                            <?php for($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                                                <option value="<?= $y ?>" <?= $tahun_aktif == $y ? 'selected' : '' ?>><?= $y ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select class="form-control form-control-sm border-success mr-2 filterBulanHasil360" style="width: 120px;">
                                            <?php foreach($bulanIndo as $num => $namaBulan): ?>
                                                <option value="<?= $num ?>" <?= $bulan_aktif == $num ? 'selected' : '' ?>><?= $namaBulan ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <select class="form-control form-control-sm border-success filterMingguHasil360" style="width: 250px;">
                                            <?php foreach($list_minggu as $num => $minggu): ?>
                                                <option value="<?= $num ?>" <?= $minggu_aktif == $num ? 'selected' : '' ?>>Minggu <?= $num ?> (<?= $minggu['label'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body bg-light p-0">
                                    <div class="table-responsive bg-white rounded shadow-sm">
                                        <table class="table table-sm table-hover table-striped mb-0 text-sm table-bordered">
                                            <thead class="bg-success text-white text-center">
                                                <tr>
                                                    <th rowspan="2" class="align-middle">No</th>
                                                    <th rowspan="2" class="align-middle">Pegawai</th>
                                                    <th colspan="4">Akumulasi Aspek Penugasan</th>
                                                    <th colspan="3">Akumulasi Kompetensi & Perilaku</th>
                                                    <th rowspan="2" class="align-middle">Rata-rata Skor</th>
                                                    <th rowspan="2" class="align-middle">Peringkat</th>
                                                    <th rowspan="2" class="align-middle">Justifikasi</th>
                                                </tr>
                                                <tr>
                                                    <th class="font-weight-normal">Hasil</th>
                                                    <th class="font-weight-normal">Kualitas</th>
                                                    <th class="font-weight-normal">Waktu</th>
                                                    <th class="font-weight-normal">Efektivitas</th>
                                                    <th class="font-weight-normal">Tanggung Jawab</th>
                                                    <th class="font-weight-normal">Kerjasama</th>
                                                    <th class="font-weight-normal">Presensi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbodyHasil360" id="tbodyHasil360_sa_<?= $tim['id_tim_kerja'] ?>">
                                                <?php if(empty($timPegawai)): ?>
                                                <tr><td colspan="10" class="text-center">Belum ada pegawai di tim ini.</td></tr>
                                                <?php else: ?>
                                                <?php foreach($timPegawai as $index => $pd): ?>
                                                    <?php 
                                                    $json360 = htmlspecialchars(json_encode($superAdminAkumulasi360[$pd['dinilai_id']] ?? (object)[]), ENT_QUOTES, 'UTF-8');
                                                    ?>
                                                    <tr class="row-hasil-360" data-360="<?= $json360 ?>">
                                                        <td class="text-center row-no"><?= $index + 1 ?></td>
                                                        <td>
                                                            <span class="font-weight-bold"><?= esc($pd['nama']) ?></span><br>
                                                            <small class="text-muted">NIP. <?= esc($pd['nip']) ?></small>
                                                        </td>
                                                        <td class="text-center val-hasil">-</td>
                                                        <td class="text-center val-kualitas">-</td>
                                                        <td class="text-center val-waktu">-</td>
                                                        <td class="text-center val-efektivitas">-</td>
                                                        <td class="text-center val-tanggung">-</td>
                                                        <td class="text-center val-kerjasama">-</td>
                                                        <td class="text-center val-presensi">-</td>
                                                        <td class="text-center font-weight-bold text-success val-total">-</td>
                                                        <td class="text-center font-weight-bold text-success val-ranking">-</td>
                                                        <td class="text-center align-middle">
                                                            <button type="button" class="btn btn-sm btn-outline-info btn-lihat-justifikasi" data-id="<?= $pd['dinilai_id'] ?>" data-nama="<?= esc($pd['nama']) ?>" title="Lihat Justifikasi">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal Detail Kinerja Mingguan (Penilai) -->
<!-- Modal Rekomendasi Kebijakan -->
<div class="modal fade" id="modalRekomendasiKebijakan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-tasks"></i> Rekomendasi Kebijakan (Tugas Tambahan) <span id="titleRekomendasiKebijakan"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <p class="text-muted mb-3">Tugas tambahan ini akan muncul di form evaluasi kinerja mingguan pegawai yang bersangkutan pada minggu yang dipilih.</p>
          <form id="formRekomendasiKebijakan">
              <input type="hidden" name="dinilai_id" id="rek_id_user">
              <input type="hidden" name="minggu_ke" id="rek_minggu_ke">
              <input type="hidden" name="tahun" id="rek_tahun">
              <input type="hidden" name="bulan" id="rek_bulan">
              
              <div class="form-group">
                  <label class="text-success">Rencana Hasil Kerja</label>
                  <textarea class="form-control border-success" name="rencana_hasil_kerja" id="rek_rencana" rows="2" placeholder="Masukkan rencana hasil kerja tambahan..." required></textarea>
              </div>
              <div class="form-group">
                  <label class="text-success">Indikator Kinerja Individu (IKI)</label>
                  <textarea class="form-control border-success" name="iki" id="rek_iki" rows="2" placeholder="Masukkan IKI..." required></textarea>
              </div>
              <div class="form-group">
                  <label class="text-success">Target</label>
                  <input type="text" class="form-control border-success" name="target" id="rek_target" placeholder="Contoh: 1 Dokumen / 100%" required>
              </div>
              
              <div class="text-right">
                  <button type="button" class="btn btn-success" id="btnTambahRekomendasi"><i class="fas fa-plus"></i> Tambah Tugas</button>
              </div>
          </form>

          <hr>
          
          <h6 class="font-weight-bold text-success mb-3">Daftar Tugas Tambahan:</h6>
          <div class="table-responsive">
              <table class="table table-sm table-bordered bg-white">
                  <thead class="bg-light text-success">
                      <tr>
                          <th width="5%" class="text-center">No</th>
                          <th width="35%">Rencana Hasil Kerja</th>
                          <th width="35%">IKI</th>
                          <th width="15%">Target</th>
                          <th width="10%" class="text-center">Aksi</th>
                      </tr>
                  </thead>
                  <tbody id="tbodyRekomendasiKebijakan">
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

<!-- Modal Observasi Perilaku -->
<div class="modal fade" id="modalObservasiPerilaku" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fas fa-landmark"></i> Observasi Perilaku: Ekspektasi Khusus Pimpinan <span id="titleObservasiPerilaku"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <p class="text-muted mb-3">Tentukan Ekspektasi Khusus Pimpinan sesuai 7 Core Value ASN BerAKHLAK pada pegawai ini untuk minggu yang dipilih. Ekspektasi ini akan menjadi acuan pegawai dalam mengisi evaluasi perilakunya.</p>
          <form id="formObservasiPerilaku">
              <input type="hidden" name="dinilai_id" id="obs_id_user">
              <input type="hidden" name="minggu_ke" id="obs_minggu_ke">
              <input type="hidden" name="tahun" id="obs_tahun">
              <input type="hidden" name="bulan" id="obs_bulan">
              
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-hands-helping"></i> Berorientasi Pelayanan</label>
                  <textarea class="form-control" name="berorientasi_pelayanan" id="obs_pelayanan" rows="2" placeholder="Ekspektasi khusus terkait Berorientasi Pelayanan..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-balance-scale"></i> Akuntabel</label>
                  <textarea class="form-control" name="akuntabel" id="obs_akuntabel" rows="2" placeholder="Ekspektasi khusus terkait Akuntabel..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-graduation-cap"></i> Kompeten</label>
                  <textarea class="form-control" name="kompeten" id="obs_kompeten" rows="2" placeholder="Ekspektasi khusus terkait Kompeten..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-users"></i> Harmonis</label>
                  <textarea class="form-control" name="harmonis" id="obs_harmonis" rows="2" placeholder="Ekspektasi khusus terkait Harmonis..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-shield-alt"></i> Loyal</label>
                  <textarea class="form-control" name="loyal" id="obs_loyal" rows="2" placeholder="Ekspektasi khusus terkait Loyal..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-sync-alt"></i> Adaptif</label>
                  <textarea class="form-control" name="adaptif" id="obs_adaptif" rows="2" placeholder="Ekspektasi khusus terkait Adaptif..."></textarea>
              </div>
              <div class="form-group">
                  <label class="text-primary"><i class="fas fa-handshake"></i> Kolaboratif</label>
                  <textarea class="form-control" name="kolaboratif" id="obs_kolaboratif" rows="2" placeholder="Ekspektasi khusus terkait Kolaboratif..."></textarea>
              </div>
          </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" id="btnKosongkanObservasi"><i class="fas fa-eraser"></i> Kosongkan</button>
        <div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-warning" id="btnSimpanObservasi"><i class="fas fa-save"></i> Simpan Ekspektasi</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Lihat Justifikasi 360 -->
<div class="modal fade" id="modalLihatJustifikasi" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-comment-dots"></i> Daftar Justifikasi Penilaian 360 - <span id="titleNamaJustifikasi"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <p class="text-muted mb-3">Berikut adalah justifikasi atau catatan penilaian dari rekan kerja untuk pegawai ini pada <span id="titleTriwulanJustifikasi"></span>.</p>
          
          <div id="containerListJustifikasi">
              <div class="text-center py-4">
                  <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                  <p class="mt-2">Memuat data justifikasi...</p>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetailKinerja" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-clipboard-list"></i> Detail Kinerja <span id="titleDetailKinerja"></span></h5>
        <div class="ml-auto d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-outline-light mr-3" id="btnSinkronisasiKinerjaPenilai">
                <i class="fas fa-sync-alt"></i> Sinkronisasi
            </button>
            <button type="button" class="close text-white m-0 p-0" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
      </div>
      <div class="modal-body p-0">
          <form id="formPenilaianKinerja">
              <input type="hidden" name="id_user" id="penilaian_kinerja_id_user">
              <input type="hidden" name="minggu_ke" id="penilaian_kinerja_minggu_ke">
              <div class="table-responsive">
                  <table class="table table-sm table-hover table-striped mb-0 text-sm">
                      <thead class="bg-light text-info">
                          <tr>
                              <th width="3%" class="text-center">No</th>
                              <th width="35%">Rencana Hasil Kerja, IKI & Target</th>
                              <th width="22%">Realisasi & Bukti</th>
                              <th width="40%">Catatan Penilai</th>
                          </tr>
                      </thead>
                      <tbody id="detailKinerjaBody">
                          <!-- Loaded via AJAX -->
                      </tbody>
                  </table>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" id="btnSimpanPenilaianKinerja"><i class="fas fa-save"></i> Simpan Penilaian</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Predikat Kinerja -->
<div class="modal fade" id="modalPredikatKinerja" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-award"></i> Tentukan Predikat Hasil Kerja</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <p class="text-muted text-center mb-3">Silakan pilih predikat hasil kerja untuk pegawai minggu ini berdasarkan panduan Matriks Kuadran Kinerja ASN.</p>
          
          <!-- Tabel Guideline -->
          <div class="table-responsive mb-4">
              <table class="table table-bordered table-sm text-center mb-0" style="font-size: 0.9rem;">
                  <thead class="bg-primary text-white">
                      <tr>
                          <th width="25%" class="align-middle" rowspan="2">HASIL KERJA</th>
                          <th colspan="3">PERILAKU</th>
                      </tr>
                      <tr>
                          <th width="25%">Di Bawah</th>
                          <th width="25%">Sesuai</th>
                          <th width="25%">Di Atas</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <th class="bg-primary text-white align-middle">DI ATAS EKSPEKTASI</th>
                          <td class="bg-light text-danger align-middle">Kurang</td>
                          <td class="bg-light align-middle">Baik</td>
                          <td class="align-middle font-weight-bold" style="background-color: #e8f5e9; color: #28a745;">Sangat Baik</td>
                      </tr>
                      <tr>
                          <th class="bg-primary text-white align-middle">SESUAI EKSPEKTASI</th>
                          <td class="text-danger align-middle">Kurang</td>
                          <td class="align-middle">Baik</td>
                          <td class="align-middle">Baik</td>
                      </tr>
                      <tr>
                          <th class="bg-primary text-white align-middle">DI BAWAH EKSPEKTASI</th>
                          <td class="align-middle font-weight-bold" style="background-color: #f8d7da; color: #dc3545;">Sangat Kurang</td>
                          <td class="bg-light text-warning align-middle" style="color: #d39e00 !important;">Butuh Perbaikan</td>
                          <td class="bg-light text-warning align-middle" style="color: #d39e00 !important;">Butuh Perbaikan</td>
                      </tr>
                  </tbody>
              </table>
              <small class="d-block text-muted mt-2 font-italic text-center">*Panduan: Tentukan "Hasil Kerja" pegawai berdasarkan realisasi vs target mingguan.</small>
          </div>

          <div class="form-group text-center">
              <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                  <label class="btn btn-outline-success mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_kinerja_radio" id="predikat_atas" autocomplete="off" value="Di Atas Ekspektasi"> 
                      <i class="fas fa-arrow-up"></i> Di Atas Ekspektasi
                  </label>
                  <label class="btn btn-outline-primary mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_kinerja_radio" id="predikat_sesuai" autocomplete="off" value="Sesuai Ekspektasi"> 
                      <i class="fas fa-equals"></i> Sesuai Ekspektasi
                  </label>
                  <label class="btn btn-outline-danger mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_kinerja_radio" id="predikat_bawah" autocomplete="off" value="Di Bawah Ekspektasi"> 
                      <i class="fas fa-arrow-down"></i> Di Bawah Ekspektasi
                  </label>
              </div>
          </div>
      </div>
      <div class="modal-footer justify-content-center bg-white">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-success px-4 font-weight-bold" id="btnSimpanFinalKinerja"><i class="fas fa-check-circle"></i> Simpan Penilaian & Predikat</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Predikat Perilaku -->
<div class="modal fade" id="modalPredikatPerilaku" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-brain"></i> Tentukan Predikat Perilaku</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <p class="text-muted text-center mb-3">Silakan pilih predikat perilaku untuk pegawai minggu ini berdasarkan panduan Matriks Kuadran Kinerja ASN.</p>
          
          <!-- Tabel Guideline -->
          <div class="table-responsive mb-4">
              <table class="table table-bordered table-sm text-center mb-0" style="font-size: 0.9rem;">
                  <thead class="bg-primary text-white">
                      <tr>
                          <th width="25%" class="align-middle" rowspan="2">HASIL KERJA</th>
                          <th colspan="3">PERILAKU</th>
                      </tr>
                      <tr>
                          <th width="25%">Di Bawah</th>
                          <th width="25%">Sesuai</th>
                          <th width="25%">Di Atas</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <th class="bg-primary text-white align-middle">DI ATAS EKSPEKTASI</th>
                          <td class="bg-light text-danger align-middle">Kurang</td>
                          <td class="bg-light align-middle">Baik</td>
                          <td class="align-middle font-weight-bold" style="background-color: #e8f5e9; color: #28a745;">Sangat Baik</td>
                      </tr>
                      <tr>
                          <th class="bg-primary text-white align-middle">SESUAI EKSPEKTASI</th>
                          <td class="text-danger align-middle">Kurang</td>
                          <td class="align-middle">Baik</td>
                          <td class="align-middle">Baik</td>
                      </tr>
                      <tr>
                          <th class="bg-primary text-white align-middle">DI BAWAH EKSPEKTASI</th>
                          <td class="align-middle font-weight-bold" style="background-color: #f8d7da; color: #dc3545;">Sangat Kurang</td>
                          <td class="bg-light text-warning align-middle" style="color: #d39e00 !important;">Butuh Perbaikan</td>
                          <td class="bg-light text-warning align-middle" style="color: #d39e00 !important;">Butuh Perbaikan</td>
                      </tr>
                  </tbody>
              </table>
              <small class="d-block text-muted mt-2 font-italic text-center">*Panduan: Tentukan "Perilaku" pegawai berdasarkan Core Values BerAKHLAK mingguan.</small>
          </div>

          <div class="form-group text-center">
              <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                  <label class="btn btn-outline-success mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_perilaku_radio" autocomplete="off" value="Di Atas Ekspektasi"> 
                      <i class="fas fa-arrow-up"></i> Di Atas Ekspektasi
                  </label>
                  <label class="btn btn-outline-primary mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_perilaku_radio" autocomplete="off" value="Sesuai Ekspektasi"> 
                      <i class="fas fa-equals"></i> Sesuai Ekspektasi
                  </label>
                  <label class="btn btn-outline-danger mx-1 mb-2 rounded flex-fill" style="flex: 1 1 30%;">
                      <input type="radio" name="predikat_perilaku_radio" autocomplete="off" value="Di Bawah Ekspektasi"> 
                      <i class="fas fa-arrow-down"></i> Di Bawah Ekspektasi
                  </label>
              </div>
          </div>
      </div>
      <div class="modal-footer justify-content-center bg-white">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-primary px-4 font-weight-bold" id="btnSimpanFinalPerilaku"><i class="fas fa-check-circle"></i> Simpan Penilaian & Predikat</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Perilaku -->
<div class="modal fade" id="modalDetailPerilaku" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detail & Penilaian Perilaku <span id="titleDetailPerilaku"></span></h5>
        <div class="ml-auto d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-outline-light mr-3" id="btnSinkronisasiPerilakuPenilai">
                <i class="fas fa-sync-alt"></i> Sinkronisasi
            </button>
            <button type="button" class="close text-white m-0 p-0" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
      </div>
      <div class="modal-body bg-light">
          <form id="formPenilaianPerilaku">
              <input type="hidden" name="id_user" id="penilaian_perilaku_id_user">
              <input type="hidden" name="minggu_ke" id="penilaian_perilaku_minggu_ke">
              <div class="card card-outline card-primary shadow-none mb-0">
                  <div class="card-body p-0">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover mb-0 text-sm">
                              <thead class="bg-light text-primary text-center">
                                <tr>
                                    <th width="25%" class="align-middle">Core Value</th>
                                    <th width="30%" class="align-middle">Ekspektasi Khusus Pimpinan (Dari Pegawai)</th>
                                    <th width="25%" class="align-middle">Catatan Penilai</th>
                                    <th width="20%" class="align-middle">Skala Penilaian Ekspektasi Khusus</th>
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
                                      <td class="bg-light align-middle">
                                          <h6 class="text-primary font-weight-bold mb-1"><?= $value['title'] ?></h6>
                                          <?= $value['desc'] ?>
                                      </td>
                                      <td class="align-middle">
                                          <div class="p-2 text-dark bg-white border rounded" id="detail_perilaku_<?= $value['id'] ?>" style="min-height: 80px;">-</div>
                                      </td>
                                      <td class="align-middle">
                                          <textarea class="form-control form-control-sm border-info" name="<?= $value['id'] ?>_catatan" id="catatan_<?= $value['id'] ?>" rows="3" placeholder="Tambahkan catatan..."></textarea>
                                      </td>
                                      <td class="align-middle">
                                          <select class="form-control form-control-sm border-success" name="<?= $value['id'] ?>_nilai" id="nilai_<?= $value['id'] ?>">
                                              <option value="">-- Pilih Skala --</option>
                                              <option value="1">1 - Jarang</option>
                                              <option value="2">2 - Kadang-Kadang</option>
                                              <option value="3">3 - Sering (Sesuai)</option>
                                              <option value="4">4 - Selalu (Teladan/Melampaui)</option>
                                          </select>
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
        <button type="button" class="btn btn-success" id="btnSimpanPenilaianPerilaku"><i class="fas fa-save"></i> Simpan Penilaian</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tanggapi Feedback -->
<div class="modal fade" id="modalTanggapiFeedback" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="fas fa-reply"></i> Tanggapi Feedback Pegawai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <form id="formTanggapiFeedback">
              <input type="hidden" name="id_user" id="tanggapi_id_user">
              <input type="hidden" name="minggu_ke" id="tanggapi_minggu_ke">
              <input type="hidden" name="bulan" id="tanggapi_bulan">
              <input type="hidden" name="tahun" id="tanggapi_tahun">
              
              <div id="section-tanggapan-kinerja" class="mb-4">
                  <h6 class="font-weight-bold text-primary border-bottom pb-2">Aspek Kinerja</h6>
                  <div class="form-group mb-3">
                      <label class="text-danger"><i class="fas fa-exclamation-circle"></i> Ketidakpuasan Kinerja:</label>
                      <div class="p-3 bg-white border border-danger rounded text-dark" id="text_feedback_kinerja"></div>
                  </div>
                  <div class="form-group">
                      <label class="text-success"><i class="fas fa-reply"></i> Balasan Kinerja:</label>
                      <textarea class="form-control border-success" id="tanggapan_kinerja" name="tanggapan_kinerja" rows="3" placeholder="Tuliskan tanggapan kinerja..."></textarea>
                  </div>
              </div>

              <div id="section-tanggapan-perilaku">
                  <h6 class="font-weight-bold text-primary border-bottom pb-2">Aspek Perilaku</h6>
                  <div class="form-group mb-3">
                      <label class="text-danger"><i class="fas fa-exclamation-circle"></i> Ketidakpuasan Perilaku:</label>
                      <div class="p-3 bg-white border border-danger rounded text-dark" id="text_feedback_perilaku"></div>
                  </div>
                  <div class="form-group">
                      <label class="text-success"><i class="fas fa-reply"></i> Balasan Perilaku:</label>
                      <textarea class="form-control border-success" id="tanggapan_perilaku" name="tanggapan_perilaku" rows="3" placeholder="Tuliskan tanggapan perilaku..."></textarea>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success font-weight-bold" id="btnSimpanTanggapan"><i class="fas fa-save"></i> Simpan Tanggapan</button>
      </div>
    </div>
  </div>
</div>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .card-outline.card-success { border-top: 3px solid #28a745; }
    .nav-pills .nav-link.active { background-color: #007bff; }

    @keyframes pulse-warning {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.8); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    .btn-pulse-warning {
        animation: pulse-warning 1.5s infinite;
    }
</style>
<!-- Modal Kalibrasi -->
<div class="modal fade" id="modalKalibrasi" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-balance-scale"></i> Form Kalibrasi Perilaku: <span id="namaPegawaiKalibrasi"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <style>
            #modalKalibrasi .table-responsive {
                max-height: 70vh; /* Batasi tinggi tabel agar bisa di-scroll */
            }
            #modalKalibrasi thead th {
                position: sticky;
                top: 0;
                z-index: 2; /* Pastikan header selalu di atas teks */
                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); /* Efek shadow untuk pemisah */
                background-color: #f8f9fa; /* Warna solid agar tidak transparan */
            }
            /* Karena ada 2 baris thead, baris kedua butuh penyesuaian top */
            #modalKalibrasi thead tr:nth-child(2) th {
                top: 45px; /* Sesuaikan dengan tinggi baris pertama */
            }
            /* Timpa background color khusus untuk header baris kedua agar tidak kembali putih */
            #modalKalibrasi thead tr:nth-child(2) th.bg-info { background-color: #17a2b8 !important; }
            #modalKalibrasi thead tr:nth-child(2) th.bg-primary { background-color: #007bff !important; }
        </style>
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center text-sm" style="min-width: 1200px;">
                <thead class="bg-light">
                    <tr>
                        <th rowspan="2" class="align-middle" width="12%">Aspek Penilaian</th>
                        <th rowspan="2" class="align-middle" width="4%">Bobot</th>
                        <th colspan="4">Indikator Nilai</th>
                        <th rowspan="2" class="align-middle" width="6%">Nilai</th>
                        <th rowspan="2" class="align-middle" width="8%">Total Nilai<br><small>(Nilai * bobot)</small></th>
                    </tr>
                    <tr>
                        <th class="bg-info text-white" width="17.5%">&lt; 70</th>
                        <th class="bg-primary text-white" width="17.5%">70 - 89</th>
                        <th class="bg-info text-white" width="17.5%">90 - 109</th>
                        <th class="bg-primary text-white" width="17.5%">110 - 120</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loyalitas 30% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Loyalitas</td></tr>
                    <tr>
                        <td class="text-left">1.a Absensi</td>
                        <td rowspan="3" class="align-middle">30%</td>
                        <td>70 (>60 menit/ tidak absen datang/ tidak absen pulang/ tidak hadir)</td>
                        <td>89 (31-60 menit)</td>
                        <td>109 (0-30 menit)</td>
                        <td>120 (0)</td>
                        <td rowspan="3" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="loyalitas" data-bobot="0.30" min="0" max="120"></td>
                        <td rowspan="3" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="loyalitas">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">1.b Sikap Peraturan Peraturan</td>
                        <td>selalu menentang peraturan</td>
                        <td>Jarang patuh terhadap peraturan yang berlaku</td>
                        <td>kadang patuh terhadap peraturan yang berlaku</td>
                        <td>Patuh terhadap peraturan yang berlaku</td>
                    </tr>
                    <tr>
                        <td class="text-left">1.c Sikap terhadap jam kerja</td>
                        <td>Menggunakan jam kerja untuk hal hal lain/ sangat santai</td>
                        <td>Selama jam kerja lebih sering terlihat santai</td>
                        <td>masih menggunakan jam kerja untuk hal lain tapi masih lebih fokus bekerja</td>
                        <td>Tidak menggunakan jam kerja untuk hal - hal lain</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/3)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_loyalitas">0</span></td></tr>

                    <!-- Cara Kerja 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Cara Kerja</td></tr>
                    <tr>
                        <td class="text-left">2.a Metode Kerja</td>
                        <td rowspan="3" class="align-middle">15%</td>
                        <td>Tidak mau menyumbang ide - ide/ metode kerja</td>
                        <td>Jarang mau menyumbangkan ide - ide/ metode kerja</td>
                        <td>Mau menyumbangkan ide - ide/ metode kerja</td>
                        <td>Selalu mau menyumbnagkan ide - ide/ metode kerja</td>
                        <td rowspan="3" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="cara_kerja" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="3" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="cara_kerja">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">2.b Kerja sama</td>
                        <td>Tidak saling membantu</td>
                        <td>Jarang membantu</td>
                        <td>Kadang - kadang membantu</td>
                        <td>Saling membantu</td>
                    </tr>
                    <tr>
                        <td class="text-left">2.c Komunikasi</td>
                        <td>Tidak pernah berkomunikasi</td>
                        <td>Jarang berkomunikasi</td>
                        <td>Cukup berkomunikasi</td>
                        <td>Berkomunikasi efektif</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/3)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_cara_kerja">0</span></td></tr>

                    <!-- Target Kinerja 25% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Target Kinerja</td></tr>
                    <tr>
                        <td class="text-left">3.a Sikap terhadap target</td>
                        <td rowspan="2" class="align-middle">25%</td>
                        <td>Tidak melaksanakan tugas/ pekerjaan sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan tidak sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan hampir sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan sesuai dengan target kualitas dan waktu</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="target_kinerja" data-bobot="0.25" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="target_kinerja">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">3.b Inisiatif</td>
                        <td>Tidak melaksanakan apa yang pernah dikatakan tanpa disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan dengan disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan tanpa/ dengan disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan tanpa disuruh/ diminta</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_target_kinerja">0</span></td></tr>

                    <!-- Tata Kelola 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Tata Kelola</td></tr>
                    <tr>
                        <td class="text-left">4.a SOP</td>
                        <td rowspan="2" class="align-middle">15%</td>
                        <td>Tidak melaksanakan tugas sesuai SOP yang ada</td>
                        <td>Sering melaksanakan tugas tidak seusai dengan SOP yang ada</td>
                        <td>Kadang - kadang melaksanakan tugas tidak sesuai dengan SOP</td>
                        <td>Melaksanakan tugas seusai SOP yang ada</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="tata_kelola" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="tata_kelola">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">4.b Perintah</td>
                        <td>Melawan terhadap perintah atasan</td>
                        <td>sering melawan terhadap perintah atasan</td>
                        <td>Kadang - kadang melawan terhadap perintah atasan</td>
                        <td>Melaksanakan apa yang diperintahkan atasan</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_tata_kelola">0</span></td></tr>

                    <!-- Lingkungan 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Lingkungan</td></tr>
                    <tr>
                        <td class="text-left">5.a Atribut</td>
                        <td rowspan="2" class="align-middle">15%</td>
                        <td>Tidak menggunakan atribut lengkap</td>
                        <td>Menggunakan atribut lengkap jika dibutuhkan</td>
                        <td>Menggunakan atribut tetapi tidak lengkap</td>
                        <td>Selalu menggunakan atribut lengkap</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="lingkungan" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="lingkungan">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">5.b Lingkungan Kerja</td>
                        <td>Meja kerja selalu berantakan</td>
                        <td>Terlihat rapih kalau ada instruksi</td>
                        <td>Tidak terlalu rapih sudah bisa dikatakan rapih</td>
                        <td>Meja kerja tertata rapih</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_lingkungan">0</span></td></tr>
                    
                    <!-- Grand Total -->
                    <tr class="bg-info text-white"><td colspan="7" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai)</td><td class="text-center font-weight-bold"><span id="grand_total_kalibrasi">0</span></td></tr>
                    <tr class="bg-primary text-white"><td colspan="7" class="text-right font-weight-bold">Konversi Nilai Kalibrasi menjadi Isian Perilaku</td><td class="text-center font-weight-bold"><span id="konversi_perilaku_kalibrasi">-</span></td></tr>
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" id="btnSimpanKalibrasi"><i class="fas fa-save"></i> Simpan Kalibrasi</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Modal Kalibrasi
    let currentDinilaiId = null;
    let currentKalibrasiData = {};

    $('.btn-kalibrasi-modal').click(function() {
        currentDinilaiId = $(this).data('id');
        let nama = $(this).data('nama');
        let nip = $(this).data('nip') || '';
        
        currentKalibrasiData = {
            id_dinilai: currentDinilaiId,
            minggu: $(this).data('minggu'),
            bulan: $(this).data('bulan'),
            tahun: $(this).data('tahun')
        };
        
        $('#namaPegawaiKalibrasi').text(nama + (nip ? ' (' + nip + ')' : ''));
        
        // Reset form
        $('.input-nilai').val('');
        $('.total-nilai').text('0');
        $('[id^="jumlah_"]').text('0');
        $('#grand_total_kalibrasi').text('0');

        // Fetch existing data
        $.ajax({
            url: '/ekip/kalibrasi/get_data',
            type: 'GET',
            data: currentKalibrasiData,
            success: function(res) {
                if (res.status === 'success' && res.data) {
                    $('.input-nilai[data-kategori="loyalitas"]').val(res.data.loyalitas);
                    $('.input-nilai[data-kategori="cara_kerja"]').val(res.data.cara_kerja);
                    $('.input-nilai[data-kategori="target_kinerja"]').val(res.data.target_kinerja);
                    $('.input-nilai[data-kategori="tata_kelola"]').val(res.data.tata_kelola);
                    $('.input-nilai[data-kategori="lingkungan"]').val(res.data.lingkungan);
                    hitungNilaiKalibrasi();
                }
            }
        });
        
        $('#modalKalibrasi').modal('show');
    });

    function hitungNilaiKalibrasi() {
        let grandTotal = 0;
        
        $('.input-nilai').each(function() {
            let val = parseFloat($(this).val()) || 0;
            let bobot = parseFloat($(this).data('bobot')) || 0;
            let kategori = $(this).data('kategori');
            
            let total = val * bobot;
            $('.total-nilai[data-kategori="'+kategori+'"]').text(total.toFixed(2));
            
            grandTotal += total;
            $('#jumlah_' + kategori).text(total.toFixed(2));
        });
        
        $('#grand_total_kalibrasi').text(grandTotal.toFixed(2));
        
        let konversi = '-';
        if (grandTotal < 70) {
            konversi = 'Kurang dan Sangat Kurang';
        } else if (grandTotal >= 70 && grandTotal < 90) {
            konversi = 'Butuh Perbaikan';
        } else if (grandTotal >= 90 && grandTotal < 110) {
            konversi = 'Baik';
        } else if (grandTotal >= 110) {
            konversi = 'Baik Sekali';
        }
        $('#konversi_perilaku_kalibrasi').text(konversi);
    }

    $('.input-nilai').on('input', function() {
        hitungNilaiKalibrasi();
    });

    $('#btnSimpanKalibrasi').click(function() {
        if (!currentDinilaiId) {
            Swal.fire('Peringatan', 'Terjadi kesalahan, ID Pegawai tidak ditemukan.', 'warning');
            return;
        }
        
        let data = {
            id_dinilai: currentDinilaiId,
            minggu: currentKalibrasiData.minggu,
            bulan: currentKalibrasiData.bulan,
            tahun: currentKalibrasiData.tahun,
            nilai_loyalitas: $('.input-nilai[data-kategori="loyalitas"]').val(),
            nilai_cara_kerja: $('.input-nilai[data-kategori="cara_kerja"]').val(),
            nilai_target_kinerja: $('.input-nilai[data-kategori="target_kinerja"]').val(),
            nilai_tata_kelola: $('.input-nilai[data-kategori="tata_kelola"]').val(),
            nilai_lingkungan: $('.input-nilai[data-kategori="lingkungan"]').val(),
            grand_total: $('#grand_total_kalibrasi').text()
        };
        
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/kalibrasi/simpan',
            type: 'POST',
            data: data,
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.status == 'success') {
                    $('#modalKalibrasi').modal('hide');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            }
        });
    });

    function formatNumber(num) {
        if (!num) return '-';
        // Tampilkan 2 angka di belakang koma untuk rata-rata
        return parseFloat(num).toFixed(2);
    }

    $(document).on('change', '#filterTimKerjaSA', function() {
        var targetId = $(this).val();
        $('#saTeamTabContent .sa-tim-pane').removeClass('show active');
        $('#' + targetId).addClass('show active');
    });

    $(document).on('click', '.btn-lihat-justifikasi', function() {
        var dinilai_id = $(this).data('id');
        var nama = $(this).data('nama');
        var wrapper = $(this).closest('.hasil-360-wrapper');
        var minggu_ke = wrapper.find('.filterMingguHasil360').val() || '1';
        var bulan = wrapper.find('.filterBulanHasil360').val();
        var tahun = wrapper.find('.filterTahunHasil360').val();
        
        $('#titleNamaJustifikasi').text(nama);
        $('#titleTriwulanJustifikasi').text('Minggu ke-' + minggu_ke);
        $('#containerListJustifikasi').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><p class="mt-2">Memuat data justifikasi...</p></div>');
        
        $('#modalLihatJustifikasi').modal('show');

        $.ajax({
            url: '/ekip/penilaian_360/get_justifikasi_by_dinilai/' + dinilai_id + '/' + encodeURIComponent(minggu_ke) + '?bulan=' + bulan + '&tahun=' + tahun,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success') {
                    var html = '';
                    if(response.data && response.data.length > 0) {
                        $.each(response.data, function(index, item) {
                            html += '<div class="card card-outline card-info mb-3">';
                            html += '<div class="card-header"><h3 class="card-title text-sm font-weight-bold"><i class="fas fa-user-circle"></i> ' + item.nama_penilai + '</h3></div>';
                            html += '<div class="card-body py-2">';
                            if(item.justifikasi_deskripsi) {
                                html += '<p class="mb-2 text-sm">' + item.justifikasi_deskripsi.replace(/\n/g, "<br>") + '</p>';
                            }
                            if(item.justifikasi_file) {
                                html += '<a href="/uploads/ekip_360/' + item.justifikasi_file + '" target="_blank" class="badge badge-info p-2"><i class="fas fa-file-download"></i> Unduh File Lampiran</a>';
                            }
                            html += '</div></div>';
                        });
                    } else {
                        html = '<div class="alert alert-light text-center border"><i class="fas fa-info-circle text-muted"></i> Tidak ada justifikasi atau catatan pada minggu ini.</div>';
                    }
                    $('#containerListJustifikasi').html(html);
                } else {
                    $('#containerListJustifikasi').html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data justifikasi.</div>');
                }
            },
            error: function() {
                $('#containerListJustifikasi').html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan jaringan.</div>');
            }
        });
    });

    function renderHasil360() {
        // Iterate over each 360 table wrapper
        $('.hasil-360-wrapper').each(function() {
            var wrapper = $(this);
            var selectedMinggu = wrapper.find('.filterMingguHasil360').val();
            
            var rowsData = [];

            wrapper.find('.row-hasil-360').each(function() {
                var row = $(this);
                var data360 = row.data('360');
                if (typeof data360 === 'string') {
                    try { data360 = JSON.parse(data360); } catch(e) { data360 = {}; }
                }
                if (typeof data360 !== 'object' || Array.isArray(data360)) {
                    data360 = {};
                }
                
                var total = 0;
                
                if (data360 && data360[selectedMinggu]) {
                    var d = data360[selectedMinggu];
                    if (row.find('.val-hasil input').length > 0) {
                        row.find('.val-hasil input').val(d.avg_hasil || 0);
                        row.find('.val-kualitas input').val(d.avg_kualitas || 0);
                        row.find('.val-waktu input').val(d.avg_waktu || 0);
                        row.find('.val-efektivitas input').val(d.avg_efektivitas || 0);
                        row.find('.val-tanggung input').val(d.avg_tanggung_jawab || 0);
                        row.find('.val-kerjasama input').val(d.avg_kerjasama || 0);
                        row.find('.val-presensi input').val(d.avg_presensi || 0);
                    } else {
                        row.find('.val-hasil').text(formatNumber(d.avg_hasil));
                        row.find('.val-kualitas').text(formatNumber(d.avg_kualitas));
                        row.find('.val-waktu').text(formatNumber(d.avg_waktu));
                        row.find('.val-efektivitas').text(formatNumber(d.avg_efektivitas));
                        row.find('.val-tanggung').text(formatNumber(d.avg_tanggung_jawab));
                        row.find('.val-kerjasama').text(formatNumber(d.avg_kerjasama));
                        row.find('.val-presensi').text(formatNumber(d.avg_presensi));
                    }
                    row.find('.val-total').text(formatNumber(d.total_jumlah));
                    total = parseFloat(d.total_jumlah) || 0;
                    
                    // Update tombol justifikasi
                    var btnJustifikasi = row.find('.btn-lihat-justifikasi');
                    if (d.has_justifikasi == 1) {
                        btnJustifikasi.removeClass('btn-outline-info').addClass('btn-info font-weight-bold text-white').html('<i class="fas fa-search"></i> Lihat');
                    } else {
                        btnJustifikasi.removeClass('btn-info font-weight-bold text-white').addClass('btn-outline-info').html('<i class="fas fa-search"></i>');
                    }
                } else {
                    if (row.find('.val-hasil input').length > 0) {
                        row.find('.val-hasil input').val('');
                        row.find('.val-kualitas input').val('');
                        row.find('.val-waktu input').val('');
                        row.find('.val-efektivitas input').val('');
                        row.find('.val-tanggung input').val('');
                        row.find('.val-kerjasama input').val('');
                        row.find('.val-presensi input').val('');
                        row.find('.val-total').text('-');
                    } else {
                        row.find('.val-hasil, .val-kualitas, .val-waktu, .val-efektivitas, .val-tanggung, .val-kerjasama, .val-presensi, .val-total').text('-');
                    }
                    row.find('.btn-lihat-justifikasi').removeClass('btn-info font-weight-bold text-white').addClass('btn-outline-info').html('<i class="fas fa-search"></i>');
                }
                
                rowsData.push({
                    element: row,
                    total: total
                });
            });

            // Sort descending
            rowsData.sort(function(a, b) {
                return b.total - a.total;
            });

            var tbody = wrapper.find('.tbodyHasil360');
            var currentRank = 1;
            var prevTotal = null;
            var displayRank = 1;

            for (var i = 0; i < rowsData.length; i++) {
                var item = rowsData[i];
                
                // Re-append for sorting
                tbody.append(item.element);
                
                // Update Nomor Urut
                item.element.find('.row-no').text(i + 1);

                // Assign ranking
                if (item.total > 0) {
                    if (item.total !== prevTotal) {
                        displayRank = currentRank;
                    }
                    item.element.find('.val-ranking').text(displayRank);
                    prevTotal = item.total;
                } else {
                    item.element.find('.val-ranking').text('-');
                }
                
                currentRank++;
            }
        });
    }

    // Initialize 360 table
    renderHasil360();
    
    $(document).on('change', '.filterMingguHasil360', function() {
        renderHasil360();
    });

    $(document).on('change', '.filterTahunHasil360, .filterBulanHasil360', function() {
        var wrapper = $(this).closest('.hasil-360-wrapper');
        var tahun = wrapper.find('.filterTahunHasil360').val();
        var bulan = wrapper.find('.filterBulanHasil360').val();
        window.location.href = '/ekip/hasil?tahun=' + tahun + '&bulan=' + bulan;
    });

    $(document).on('click', '.btn-simpan-360-final', function() {
        var btn = $(this);
        var dinilai_id = btn.data('id');
        var tr = btn.closest('tr');
        var wrapper = btn.closest('.hasil-360-wrapper');
        var minggu_ke = wrapper.find('.filterMingguHasil360').val();
        var bulan = wrapper.find('.filterBulanHasil360').val();
        var tahun = wrapper.find('.filterTahunHasil360').val();
        
        var hasil_pekerjaan = tr.find('.val-hasil input').val();
        var kualitas_pekerjaan = tr.find('.val-kualitas input').val();
        var ketepatan_waktu = tr.find('.val-waktu input').val();
        var efektivitas_efesiensi = tr.find('.val-efektivitas input').val();
        var tanggung_jawab = tr.find('.val-tanggung input').val();
        var kerjasama = tr.find('.val-kerjasama input').val();
        var presensi = tr.find('.val-presensi input').val();

        if (!hasil_pekerjaan || !kualitas_pekerjaan || !ketepatan_waktu || !efektivitas_efesiensi || !tanggung_jawab || !kerjasama || !presensi) {
            Swal.fire('Peringatan', 'Harap isi semua nilai terlebih dahulu.', 'warning');
            return;
        }

        var total = (parseFloat(hasil_pekerjaan) + parseFloat(kualitas_pekerjaan) + parseFloat(ketepatan_waktu) + parseFloat(efektivitas_efesiensi) + parseFloat(tanggung_jawab) + parseFloat(kerjasama) + parseFloat(presensi)) / 7;
        
        var btnHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '/ekip/penilaian_360_final/simpan',
            type: 'POST',
            data: {
                id_dinilai: dinilai_id,
                minggu_ke: minggu_ke,
                bulan: bulan,
                tahun: tahun,
                hasil_pekerjaan: hasil_pekerjaan,
                kualitas_pekerjaan: kualitas_pekerjaan,
                ketepatan_waktu: ketepatan_waktu,
                efektivitas_efesiensi: efektivitas_efesiensi,
                tanggung_jawab: tanggung_jawab,
                kerjasama: kerjasama,
                presensi: presensi,
                jumlah: total.toFixed(2)
            },
            dataType: 'json',
            success: function(response) {
                btn.html(btnHtml).prop('disabled', false);
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil', 
                        text: response.message, 
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    var data360 = tr.data('360');
                    if (typeof data360 !== 'object' || Array.isArray(data360)) {
                        data360 = {};
                    }
                    if (!data360[minggu_ke]) data360[minggu_ke] = {};
                    
                    data360[minggu_ke].avg_hasil = hasil_pekerjaan;
                    data360[minggu_ke].avg_kualitas = kualitas_pekerjaan;
                    data360[minggu_ke].avg_waktu = ketepatan_waktu;
                    data360[minggu_ke].avg_efektivitas = efektivitas_efesiensi;
                    data360[minggu_ke].avg_tanggung_jawab = tanggung_jawab;
                    data360[minggu_ke].avg_kerjasama = kerjasama;
                    data360[minggu_ke].avg_presensi = presensi;
                    data360[minggu_ke].total_jumlah = total.toFixed(2);
                    
                    tr.attr('data-360', JSON.stringify(data360));
                    tr.data('360', data360);
                    renderHasil360();
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function() {
                btn.html(btnHtml).prop('disabled', false);
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            }
        });
    });

    // Filter Kinerja & Perilaku
    function applyFilterKinerja() {
        $('.kinerja-perilaku-wrapper').each(function() {
            var wrapper = $(this);
            var tahun = wrapper.find('.filterTahunKinerja').val();
            var bulan = wrapper.find('.filterBulanKinerja').val();
            var minggu = wrapper.find('.filterMingguKinerja').val();
            var status = wrapper.find('.filterStatusKinerja').val();
            
            wrapper.find('.tbodyKinerjaPerilaku').each(function() {
                var tbody = $(this);
                var no = 1;
                var rows = tbody.find('.row-kinerja-perilaku').get();

                // Sort rows: ready=1 (Sudah dinilai/Menunggu penilaian) first, then ready=0 (Belum isi)
                rows.sort(function(a, b) {
                    var readyA = $(a).data('ready');
                    var readyB = $(b).data('ready');
                    return readyB - readyA; // Descending, so 1 comes before 0
                });

                // Re-append sorted rows
                $.each(rows, function(index, row) {
                    tbody.append(row);
                });
                
                tbody.find('.row-kinerja-perilaku').each(function() {
                    var row = $(this);
                    var rowMinggu = row.data('minggu').toString();
                    var rowStatus = row.data('status');
                    
                    var matchMinggu = (minggu === 'all' || rowMinggu === minggu);
                    var matchStatus = (status === 'all' || rowStatus === status);
                    
                    if (matchMinggu && matchStatus) {
                        row.show();
                        row.find('.row-no').text(no++);
                    } else {
                        row.hide();
                    }
                });
            });
        });
    }

    applyFilterKinerja();
    
    $(document).on('change', '.filterTahunKinerja, .filterBulanKinerja', function() {
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        var t = wrapper.find('.filterTahunKinerja').val();
        var b = wrapper.find('.filterBulanKinerja').val();
        var m = wrapper.find('.filterMingguKinerja').val();
        var s = wrapper.find('.filterStatusKinerja').val();
        var url = '/ekip/hasil?tahun=' + t + '&bulan=' + b + '&minggu=' + m + '&status=' + s;
        
        var tbodys = wrapper.find('.tbodyKinerjaPerilaku');
        tbodys.html('<tr><td colspan="7" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Memuat data tabel...</p></td></tr>');
        
        $.get(url, function(data) {
            var parsed = $(data);
            tbodys.each(function() {
                var tid = $(this).attr('id');
                var newTbody = parsed.find('#' + tid).html();
                if (newTbody) $(this).html(newTbody);
            });
            applyFilterKinerja();
            window.history.pushState({path: url}, '', url);
        }).fail(function() {
            tbodys.html('<tr><td colspan="7" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>Gagal memuat data.</td></tr>');
        });
    });

    $(document).on('change', '.filterMingguKinerja, .filterStatusKinerja', function() {
        applyFilterKinerja();
    });

    $(document).on('click', '.btn-lihat-kinerja', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var minggu = $(this).data('minggu');
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        
        $('#titleDetailKinerja').text('(' + nama + ' - Minggu ' + minggu + ')');
        
        $('#penilaian_kinerja_id_user').val(id);
        $('#penilaian_kinerja_minggu_ke').val(minggu);
        
        var tahun = wrapper.find('.filterTahunKinerja').val();
        var bulan = wrapper.find('.filterBulanKinerja').val();

        // Simpan wrapper referensi untuk simpan nanti
        $('#formPenilaianKinerja').data('wrapper', wrapper);

        $.ajax({
            url: '/ekip/mingguan/get/' + minggu + '/' + id + '?tahun=' + tahun + '&bulan=' + bulan,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success') {
                    var list = response.list;
                    var html = '';
                    if(list.length > 0) {
                        for(var i=0; i<list.length; i++) {
                            var item = list[i];
                            var linkHTML = item.link_bukti ? '<a href="'+item.link_bukti+'" target="_blank" class="btn btn-sm btn-outline-info" title="Buka Bukti"><i class="fas fa-external-link-alt"></i> Lihat Bukti</a>' : '<span class="text-muted text-sm font-italic">Tidak ada bukti</span>';
                            var badgeClass = item.jenis == 'Core' ? 'primary' : (item.jenis == 'Tambahan' ? 'success' : 'warning');
                            
                            html += '<tr>';
                            html += '<td class="text-center align-middle">'+(i+1)+'</td>';
                            html += '<td class="align-middle">';
                            var targetText = item.target ? item.target.replace(/\n/g, ' | ') : '-';
                            var displayTarget = targetText;
                            var titleAttr = '';
                            if (targetText.length > 30) {
                                displayTarget = targetText.substring(0, 30) + '...';
                                titleAttr = ' title="' + targetText.replace(/"/g, '&quot;') + '" data-toggle="tooltip" style="cursor: pointer;"';
                            }
                            html += '  <div class="mb-1"><span class="badge badge-'+badgeClass+'">'+item.jenis+'</span> <span class="badge badge-secondary">Bobot: '+item.bobot+'</span> <span class="badge badge-info"'+titleAttr+'>Target: '+displayTarget+'</span></div>';
                            html += '  <div class="text-sm mb-1"><span class="text-muted">Rencana:</span> <span class="font-weight-bold">'+item.rencana_hasil_kerja+'</span></div>';
                            html += '  <div class="text-sm"><span class="text-muted">IKI:</span> <span class="text-primary">'+item.iki+'</span></div>';
                            html += '</td>';
                            html += '<td class="align-middle text-sm">';
                            html += '  <div class="mb-2"><span class="text-success font-weight-bold">'+(item.realisasi ? item.realisasi : '-')+'</span></div>';
                            html += '  <div>'+linkHTML+'</div>';
                            html += '</td>';
                            html += '<td class="align-middle"><textarea class="form-control form-control-sm border-info" style="min-height: 80px;" name="catatan_penilai['+item.id+']" placeholder="Tulis catatan evaluasi di sini...">'+(item.catatan_penilai ? item.catatan_penilai : '')+'</textarea></td>';
                            html += '</tr>';
                        }
                    } else {
                        html = '<tr><td colspan="4" class="text-center">Belum ada evaluasi kinerja untuk minggu ini.</td></tr>';
                    }
                    $('#detailKinerjaBody').html(html);
                    
                    // Inisialisasi tooltip untuk elemen yang baru ditambahkan
                    $('[data-toggle="tooltip"]').tooltip();
                    
                    // Reset radio predikat
                    $('input[name="predikat_kinerja_radio"]').prop('checked', false).parent().removeClass('active');
                    if (response.ekspektasi && response.ekspektasi.predikat_kinerja) {
                        $('input[name="predikat_kinerja_radio"][value="'+response.ekspektasi.predikat_kinerja+'"]').prop('checked', true).parent().addClass('active');
                    }

                    $('#modalDetailKinerja').modal('show');
                }
            }
        });
    });

    $('#btnSimpanPenilaianKinerja').click(function() {
        // Show Predikat Modal instead of direct save
        $('#modalDetailKinerja').modal('hide');
        setTimeout(function() {
            $('#modalPredikatKinerja').modal('show');
        }, 400); // wait for previous modal to close
    });

    // ==========================================
    // SINKRONISASI PENILAIAN (KINERJA)
    // ==========================================
    $('#btnSinkronisasiKinerjaPenilai').click(function() {
        var id_user = $('#penilaian_kinerja_id_user').val();
        var minggu = $('#penilaian_kinerja_minggu_ke').val();
        var bulan = $('.filterBulanKinerja').val() || new Date().getMonth() + 1;
        var tahun = $('.filterTahunKinerja').val() || new Date().getFullYear();
        
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyinkronkan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/mingguan/get_sebelumnya_penilai',
            type: 'GET',
            data: { id_user: id_user, minggu: minggu, bulan: bulan, tahun: tahun },
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.status == 'success') {
                    var data = response.data;
                    var data_rek = response.data_rek;
                    
                    // Populate catatan master
                    for(var master_id in data) {
                        if(data[master_id].catatan_penilai) {
                            $('#catatan_' + master_id).val(data[master_id].catatan_penilai);
                        }
                    }

                    // Populate catatan rekomendasi
                    if (data_rek) {
                        for(var rek_id in data_rek) {
                            if(data_rek[rek_id].catatan_penilai) {
                                $('#catatan_rek_' + rek_id).val(data_rek[rek_id].catatan_penilai);
                            }
                        }
                    }
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Catatan kinerja minggu sebelumnya berhasil disalin',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else if(response.status == 'empty') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Tidak ada catatan di minggu sebelumnya',
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

    // ==========================================
    // SINKRONISASI PENILAIAN (PERILAKU)
    // ==========================================
    $('#btnSinkronisasiPerilakuPenilai').click(function() {
        var id_user = $('#penilaian_perilaku_id_user').val();
        var minggu = $('#penilaian_perilaku_minggu_ke').val();
        var bulan = $('.filterBulanKinerja').val() || new Date().getMonth() + 1;
        var tahun = $('.filterTahunKinerja').val() || new Date().getFullYear();
        
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyinkronkan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/perilaku/get_sebelumnya_penilai',
            type: 'GET',
            data: { id_user: id_user, minggu: minggu, bulan: bulan, tahun: tahun },
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.status == 'success') {
                    var ekspektasi = response.ekspektasi;
                    
                    if (ekspektasi) {
                        var core_values = ['pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'];
                        
                        core_values.forEach(function(cv) {
                            if (ekspektasi[cv + '_catatan']) {
                                $('#catatan_' + cv).val(ekspektasi[cv + '_catatan']);
                            }
                            if (ekspektasi[cv + '_nilai']) {
                                $('#nilai_' + cv).val(ekspektasi[cv + '_nilai']);
                            }
                        });
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Penilaian perilaku minggu sebelumnya disalin',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                } else if(response.status == 'empty') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Tidak ada data penilaian di minggu sebelumnya',
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

    // ==========================================
    // SINKRONISASI GLOBAL (SEMUA PEGAWAI)
    // ==========================================
    $('#btnSinkronisasiGlobalPenilai').click(function() {
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        var minggu = wrapper.find('.filterMingguKinerja').val();
        var bulan = wrapper.find('.filterBulanKinerja').val() || new Date().getMonth() + 1;
        var tahun = wrapper.find('.filterTahunKinerja').val() || new Date().getFullYear();
        
        if (minggu === 'all') {
            Swal.fire('Perhatian', 'Pilih minggu tertentu terlebih dahulu untuk melakukan sinkronisasi.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Sinkronisasi Global',
            text: "Ini akan menyalin seluruh penilaian (Kinerja & Perilaku) minggu sebelumnya untuk semua pegawai di tabel ini ke minggu terpilih. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Sinkronkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn = $(this);
                var originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

                $.ajax({
                    url: '/ekip/mingguan/sinkron_global',
                    type: 'POST',
                    data: { minggu: minggu, bulan: bulan, tahun: tahun },
                    success: function(response) {
                        btn.html(originalText).prop('disabled', false);
                        if(response.status == 'success') {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Informasi', response.message, 'info');
                        }
                    },
                    error: function() {
                        btn.html(originalText).prop('disabled', false);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    });

    $('#btnSinkronisasiGlobalAdmin').click(function() {
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        var minggu = wrapper.find('.filterMingguKinerja').val();
        var bulan = wrapper.find('.filterBulanKinerja').val() || new Date().getMonth() + 1;
        var tahun = wrapper.find('.filterTahunKinerja').val() || new Date().getFullYear();
        
        if (minggu === 'all') {
            Swal.fire('Perhatian', 'Pilih minggu tertentu terlebih dahulu untuk melakukan sinkronisasi.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Sinkronisasi Global (Super Admin)',
            text: "Ini akan menyalin seluruh penilaian (Kinerja & Perilaku) minggu sebelumnya untuk SEMUA PEGAWAI ke minggu terpilih. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Sinkronkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn = $(this);
                var originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

                $.ajax({
                    url: '/ekip/mingguan/sinkron_global',
                    type: 'POST',
                    data: { minggu: minggu, bulan: bulan, tahun: tahun, is_admin: 'true' },
                    success: function(response) {
                        btn.html(originalText).prop('disabled', false);
                        if(response.status == 'success') {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Informasi', response.message, 'info');
                        }
                    },
                    error: function() {
                        btn.html(originalText).prop('disabled', false);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    });

    $('.btn-sinkron-per-orang-admin').click(function() {
        var id_user = $(this).data('id');
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan');
        var tahun = $(this).data('tahun');
        
        var btn = $(this);
        var originalHtml = btn.html();

        Swal.fire({
            title: 'Sinkronisasi Pegawai (Admin)',
            text: "Salin penilaian Kinerja & Perilaku minggu sebelumnya untuk pegawai ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Salin'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: '/ekip/mingguan/sinkron_global',
                    type: 'POST',
                    data: { id_user: id_user, minggu: minggu, bulan: bulan, tahun: tahun, is_admin: 'true' },
                    success: function(response) {
                        btn.html(originalHtml).prop('disabled', false);
                        if(response.status == 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Penilaian disalin',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Informasi', response.message, 'info');
                        }
                    },
                    error: function() {
                        btn.html(originalHtml).prop('disabled', false);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    });

    // ==========================================
    // SINKRONISASI PER PEGAWAI (TABEL PENILAI)
    // ==========================================
    $('.btn-sinkron-per-orang').click(function() {
        var id_user = $(this).data('id');
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan');
        var tahun = $(this).data('tahun');
        
        var btn = $(this);
        var originalHtml = btn.html();

        Swal.fire({
            title: 'Sinkronisasi Pegawai',
            text: "Salin penilaian Kinerja & Perilaku minggu sebelumnya untuk pegawai ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Salin'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: '/ekip/mingguan/sinkron_global',
                    type: 'POST',
                    data: { id_user: id_user, minggu: minggu, bulan: bulan, tahun: tahun },
                    success: function(response) {
                        btn.html(originalHtml).prop('disabled', false);
                        if(response.status == 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Penilaian disalin',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Informasi', response.message, 'info');
                        }
                    },
                    error: function() {
                        btn.html(originalHtml).prop('disabled', false);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    });

    $('#btnSimpanFinalKinerja').click(function() {
        var selectedPredikat = $('input[name="predikat_kinerja_radio"]:checked').val();
        if (!selectedPredikat) {
            Swal.fire('Perhatian!', 'Silakan pilih Predikat Hasil Kerja terlebih dahulu.', 'warning');
            return;
        }

        var wrapper = $('#formPenilaianKinerja').data('wrapper');
        var formData = $('#formPenilaianKinerja').serializeArray();
        formData.push({name: 'predikat_kinerja', value: selectedPredikat});
        
        var tahun = wrapper ? wrapper.find('.filterTahunKinerja').val() : new Date().getFullYear();
        var bulan = wrapper ? wrapper.find('.filterBulanKinerja').val() : (new Date().getMonth() + 1);
        formData.push({name: 'tahun', value: tahun});
        formData.push({name: 'bulan', value: bulan});

        $.ajax({
            url: '/ekip/kinerja/simpan_catatan',
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        $('#modalPredikatKinerja').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    // When modalPredikatKinerja is closed without saving, optionally show modalDetailKinerja again
    $('#modalPredikatKinerja').on('hidden.bs.modal', function (e) {
        // Only if we didn't save (optional UI enhancement)
    });

    $(document).on('click', '.btn-lihat-perilaku', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var minggu = $(this).data('minggu');
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        
        $('#titleDetailPerilaku').text('(' + nama + ' - Minggu ' + minggu + ')');
        
        $('#penilaian_perilaku_id_user').val(id);
        $('#penilaian_perilaku_minggu_ke').val(minggu);

        var tahun = wrapper.find('.filterTahunKinerja').val();
        var bulan = wrapper.find('.filterBulanKinerja').val();

        // Simpan wrapper referensi
        $('#formPenilaianPerilaku').data('wrapper', wrapper);

        $.ajax({
            url: '/ekip/perilaku/get/' + minggu + '/' + id + '?tahun=' + tahun + '&bulan=' + bulan,
            type: 'GET',
            success: function(response) {
                // Reset fields
                $('div[id^="detail_perilaku_"]').html('<span class="text-muted font-italic">Belum diisi...</span>');
                $('#formPenilaianPerilaku')[0].reset();
                
                if(response.status == 'success') {
                    if (response.data) {
                        var data = response.data;
                        $('#detail_perilaku_pelayanan').html(data.pelayanan ? data.pelayanan.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_akuntabel').html(data.akuntabel ? data.akuntabel.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_kompeten').html(data.kompeten ? data.kompeten.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_harmonis').html(data.harmonis ? data.harmonis.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_loyal').html(data.loyal ? data.loyal.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_adaptif').html(data.adaptif ? data.adaptif.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                        $('#detail_perilaku_kolaboratif').html(data.kolaboratif ? data.kolaboratif.replace(/\n/g, '<br>') : '<span class="text-muted font-italic">Belum diisi...</span>');
                    }
                    
                    if (response.ekspektasi) {
                        var eks = response.ekspektasi;
                        $('#catatan_pelayanan').val(eks.pelayanan_catatan);
                        $('#nilai_pelayanan').val(eks.pelayanan_nilai);
                        $('#catatan_akuntabel').val(eks.akuntabel_catatan);
                        $('#nilai_akuntabel').val(eks.akuntabel_nilai);
                        $('#catatan_kompeten').val(eks.kompeten_catatan);
                        $('#nilai_kompeten').val(eks.kompeten_nilai);
                        $('#catatan_harmonis').val(eks.harmonis_catatan);
                        $('#nilai_harmonis').val(eks.harmonis_nilai);
                        $('#catatan_loyal').val(eks.loyal_catatan);
                        $('#nilai_loyal').val(eks.loyal_nilai);
                        $('#catatan_adaptif').val(eks.adaptif_catatan);
                        $('#nilai_adaptif').val(eks.adaptif_nilai);
                        $('#catatan_kolaboratif').val(eks.kolaboratif_catatan);
                        $('#nilai_kolaboratif').val(eks.kolaboratif_nilai);

                        // Reset radio predikat perilaku
                        $('input[name="predikat_perilaku_radio"]').prop('checked', false).parent().removeClass('active');
                        if (eks.predikat_perilaku) {
                            $('input[name="predikat_perilaku_radio"][value="'+eks.predikat_perilaku+'"]').prop('checked', true).parent().addClass('active');
                        }
                    }
                }
                $('#modalDetailPerilaku').modal('show');
            }
        });
    });

    $(document).on('click', '.btn-observasi-perilaku', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var minggu = $(this).data('minggu');
        var tahun = $(this).data('tahun');
        var bulan = $(this).data('bulan');

        $('#titleObservasiPerilaku').text('- ' + nama + ' (Minggu ' + minggu + ')');
        $('#obs_id_user').val(id);
        $('#obs_minggu_ke').val(minggu);
        $('#obs_tahun').val(tahun);
        $('#obs_bulan').val(bulan);
        $('#formObservasiPerilaku')[0].reset();

        $.ajax({
            url: '/ekip/observasi/get/' + id + '/' + tahun + '/' + bulan + '/' + minggu,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success' && response.data) {
                    $('#obs_pelayanan').val(response.data.berorientasi_pelayanan);
                    $('#obs_akuntabel').val(response.data.akuntabel);
                    $('#obs_kompeten').val(response.data.kompeten);
                    $('#obs_harmonis').val(response.data.harmonis);
                    $('#obs_loyal').val(response.data.loyal);
                    $('#obs_adaptif').val(response.data.adaptif);
                    $('#obs_kolaboratif').val(response.data.kolaboratif);
                }
                $('#modalObservasiPerilaku').modal('show');
            }
        });
    });

    $('#btnSimpanObservasi').click(function() {
        var formData = $('#formObservasiPerilaku').serialize();
        var id_user = $('#obs_id_user').val();
        var minggu = $('#obs_minggu_ke').val();
        var btn_id = '#btn-observasi-' + id_user + '-' + minggu;
        
        $.ajax({
            url: '/ekip/observasi/simpan',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    $('#modalObservasiPerilaku').modal('hide');
                    Swal.fire('Berhasil!', response.message, 'success');
                    
                    // Cek apakah ada data yang diisi
                    var isAdaIsi = false;
                    $('#formObservasiPerilaku textarea').each(function() {
                        if ($(this).val().trim() !== '') {
                            isAdaIsi = true;
                            return false; // break the loop
                        }
                    });

                    // Update UI tombol tanpa refresh halaman
                    if (isAdaIsi) {
                        $(btn_id).removeClass('btn-outline-warning')
                                 .addClass('btn-warning text-dark')
                                 .attr('title', 'Ekspektasi Khusus Pimpinan Telah Diisi');
                    } else {
                        $(btn_id).removeClass('btn-warning text-dark')
                                 .addClass('btn-outline-warning')
                                 .attr('title', 'Observasi Perilaku');
                    }
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('#btnKosongkanObservasi').click(function() {
        $('#formObservasiPerilaku textarea').val('');
    });

    function updateStatusRekomendasiButton(id_user, minggu, dataLength) {
        var btn_id = '#btn-rekomendasi-' + id_user + '-' + minggu;
        if (dataLength > 0) {
            $(btn_id).removeClass('btn-outline-success')
                     .addClass('btn-success text-white')
                     .attr('title', 'Rekomendasi Kebijakan (Telah Diisi)');
        } else {
            $(btn_id).removeClass('btn-success text-white')
                     .addClass('btn-outline-success')
                     .attr('title', 'Rekomendasi Kebijakan (Tugas Tambahan)');
        }
    }

    function loadRekomendasi(id, tahun, bulan, minggu) {
        $('#tbodyRekomendasiKebijakan').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');
        $.ajax({
            url: '/ekip/rekomendasi/get/' + id + '/' + tahun + '/' + bulan + '/' + minggu,
            type: 'GET',
            success: function(response) {
                if (response.status == 'success') {
                    var html = '';
                    if (response.data.length > 0) {
                        $.each(response.data, function(i, v) {
                            html += '<tr>';
                            html += '<td class="text-center">' + (i+1) + '</td>';
                            html += '<td>' + v.rencana_hasil_kerja.replace(/\n/g, '<br>') + '</td>';
                            html += '<td>' + v.iki.replace(/\n/g, '<br>') + '</td>';
                            html += '<td>' + v.target + '</td>';
                            html += '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-hapus-rekomendasi" data-id="'+v.id+'"><i class="fas fa-trash"></i></button></td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-center">Belum ada tugas tambahan.</td></tr>';
                    }
                    $('#tbodyRekomendasiKebijakan').html(html);
                    updateStatusRekomendasiButton(id, minggu, response.data.length);
                }
            }
        });
    }

    $(document).on('click', '.btn-rekomendasi-kebijakan', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var minggu = $(this).data('minggu');
        var tahun = $(this).data('tahun');
        var bulan = $(this).data('bulan');

        $('#titleRekomendasiKebijakan').text('- ' + nama + ' (Minggu ' + minggu + ')');
        $('#rek_id_user').val(id);
        $('#rek_minggu_ke').val(minggu);
        $('#rek_tahun').val(tahun);
        $('#rek_bulan').val(bulan);
        
        $('#rek_rencana').val('');
        $('#rek_iki').val('');
        $('#rek_target').val('');

        loadRekomendasi(id, tahun, bulan, minggu);
        $('#modalRekomendasiKebijakan').modal('show');
    });

    $('#btnTambahRekomendasi').click(function() {
        var form = $('#formRekomendasiKebijakan');
        if(!$('#rek_rencana').val() || !$('#rek_iki').val() || !$('#rek_target').val()) {
            Swal.fire('Perhatian!', 'Mohon lengkapi semua field.', 'warning');
            return;
        }
        
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $.ajax({
            url: '/ekip/rekomendasi/simpan',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if(response.status == 'success') {
                    $('#rek_rencana').val('');
                    $('#rek_iki').val('');
                    $('#rek_target').val('');
                    loadRekomendasi($('#rek_id_user').val(), $('#rek_tahun').val(), $('#rek_bulan').val(), $('#rek_minggu_ke').val());
                    Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: 'Berhasil ditambahkan'});
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Tambah Tugas');
            }
        });
    });

    $(document).on('click', '.btn-hapus-rekomendasi', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Tugas?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/rekomendasi/hapus/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success') {
                            loadRekomendasi($('#rek_id_user').val(), $('#rek_tahun').val(), $('#rek_bulan').val(), $('#rek_minggu_ke').val());
                            Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: 'Berhasil dihapus'});
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    $('#btnSimpanPenilaianPerilaku').click(function() {
        // Hide detail perilaku modal and show predikat perilaku modal
        $('#modalDetailPerilaku').modal('hide');
        setTimeout(function() {
            $('#modalPredikatPerilaku').modal('show');
        }, 400); // wait for previous modal to close
    });

    $('#btnSimpanFinalPerilaku').click(function() {
        var selectedPredikat = $('input[name="predikat_perilaku_radio"]:checked').val();
        if (!selectedPredikat) {
            Swal.fire('Perhatian!', 'Silakan pilih Predikat Perilaku terlebih dahulu.', 'warning');
            return;
        }

        var wrapper = $('#formPenilaianPerilaku').data('wrapper');
        var formData = $('#formPenilaianPerilaku').serializeArray();
        formData.push({name: 'predikat_perilaku', value: selectedPredikat});
        
        var tahun = wrapper ? wrapper.find('.filterTahunKinerja').val() : new Date().getFullYear();
        var bulan = wrapper ? wrapper.find('.filterBulanKinerja').val() : (new Date().getMonth() + 1);
        formData.push({name: 'tahun', value: tahun});
        formData.push({name: 'bulan', value: bulan});

        $.ajax({
            url: '/ekip/ekspektasi/simpan',
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        $('#modalPredikatPerilaku').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $(document).on('click', '.btn-rollback-penilaian', function() {
        var id_user = $(this).data('id');
        var minggu_ke = $(this).data('minggu');
        var tahun = $(this).data('tahun');
        var bulan = $(this).data('bulan');

        Swal.fire({
            title: 'Rollback Penilaian?',
            text: "Penilaian untuk minggu ini akan dihapus dan dikembalikan ke status 'Menunggu Penilaian'!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Rollback!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/ekspektasi/rollback',
                    type: 'POST',
                    data: {
                        id_user: id_user,
                        minggu_ke: minggu_ke,
                        tahun: tahun,
                        bulan: bulan
                    },
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
            }
        });
    });

    // Handler untuk Penilai menanggapi feedback
    $(document).on('click', '.btn-tanggapi-feedback', function() {
        var id = $(this).data('id');
        var minggu = $(this).data('minggu');
        var bulan = $(this).data('bulan');
        var tahun = $(this).data('tahun');
        
        var fb_kinerja = $(this).data('fb-kinerja');
        var tg_kinerja = $(this).data('tg-kinerja');
        var st_kinerja = $(this).data('st-kinerja');
        
        var fb_perilaku = $(this).data('fb-perilaku');
        var tg_perilaku = $(this).data('tg-perilaku');
        var st_perilaku = $(this).data('st-perilaku');

        $('#tanggapi_id_user').val(id);
        $('#tanggapi_minggu_ke').val(minggu);
        $('#tanggapi_bulan').val(bulan);
        $('#tanggapi_tahun').val(tahun);

        // Kinerja Section
        if (st_kinerja !== 'none' && st_kinerja !== '') {
            $('#section-tanggapan-kinerja').show();
            $('#text_feedback_kinerja').html(fb_kinerja ? fb_kinerja.replace(/\n/g, '<br>') : '-');
            $('#tanggapan_kinerja').val(tg_kinerja);
            if (st_kinerja === 'ditanggapi' || st_kinerja === 'selesai') {
                $('#tanggapan_kinerja').prop('readonly', true);
            } else {
                $('#tanggapan_kinerja').prop('readonly', false);
            }
        } else {
            $('#section-tanggapan-kinerja').hide();
            $('#tanggapan_kinerja').val('');
        }

        // Perilaku Section
        if (st_perilaku !== 'none' && st_perilaku !== '') {
            $('#section-tanggapan-perilaku').show();
            $('#text_feedback_perilaku').html(fb_perilaku ? fb_perilaku.replace(/\n/g, '<br>') : '-');
            $('#tanggapan_perilaku').val(tg_perilaku);
            if (st_perilaku === 'ditanggapi' || st_perilaku === 'selesai') {
                $('#tanggapan_perilaku').prop('readonly', true);
            } else {
                $('#tanggapan_perilaku').prop('readonly', false);
            }
        } else {
            $('#section-tanggapan-perilaku').hide();
            $('#tanggapan_perilaku').val('');
        }

        $('#modalTanggapiFeedback .modal-title').html('<i class="fas fa-reply"></i> Tanggapi Feedback Penilaian');

        $('#modalTanggapiFeedback').modal('show');
    });

    $('#btnSimpanTanggapan').click(function() {
        var formData = $('#formTanggapiFeedback').serialize();
        $.ajax({
            url: '/ekip/feedback/tanggapi',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        $('#modalTanggapiFeedback').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    // Fungsi untuk menerapkan semua filter (Search + Filter Minggu + Filter Status) secara bersamaan
    function applyFilters(targetBody, searchInput, mingguFilter, statusFilter) {
        var searchValue = $(searchInput).val().toLowerCase();
        var mingguValue = $(mingguFilter).val();
        var statusValue = $(statusFilter).val();

        $(targetBody + " tr").each(function() {
            var rowText = $(this).text().toLowerCase();
            
            // Cek text search
            var matchSearch = rowText.indexOf(searchValue) > -1;
            
            // Cek minggu (berdasarkan atribut data atau teks kolom)
            // Karena kita menggunakan rowspan per minggu di dalam loop backend, setiap baris mewakili 1 minggu spesifik
            // Teks minggunya ada di dalam row (misal: "Minggu 1")
            var matchMinggu = true;
            if (mingguValue !== 'all') {
                matchMinggu = $(this).find('td:eq(2)').text().indexOf('Minggu ' + mingguValue) > -1;
            }

            // Cek status
            var matchStatus = true;
            if (statusValue !== 'all') {
                var statusText = $(this).find('td:eq(3)').text().toLowerCase();
                if (statusValue === 'done') {
                    matchStatus = statusText.indexOf('sudah dinilai') > -1;
                } else if (statusValue === 'pending') {
                    matchStatus = statusText.indexOf('menunggu penilaian') > -1;
                }
            }

            // Tampilkan baris HANYA jika ketiga kondisi filter terpenuhi
            $(this).toggle(matchSearch && matchMinggu && matchStatus);
        });
    }

    // Event listener untuk PIC Tim
    $('.searchPegawaiPic, .kinerja-perilaku-wrapper .filterMingguKinerja, .kinerja-perilaku-wrapper .filterStatusKinerja').on('keyup change', function() {
        // Karena filter Minggu dan Status juga punya event listener change di atas yang me-reload halaman (mengubah URL parameters),
        // fitur search sinkron ini akan lebih terasa efeknya di sisi client sebelum halaman ter-reload, atau jika URL parameters dihapus.
        // Untuk sinkronisasi penuh di client side:
        applyFilters('#tbodyKinerjaPerilaku_penilai', '.searchPegawaiPic', '.kinerja-perilaku-wrapper .filterMingguKinerja', '.kinerja-perilaku-wrapper .filterStatusKinerja');
    });

    // Event listener untuk Super Admin (per Tim)
    $('.searchPegawaiSA, .kinerja-perilaku-wrapper .filterMingguKinerja, .kinerja-perilaku-wrapper .filterStatusKinerja').on('keyup change', function() {
        var wrapper = $(this).closest('.kinerja-perilaku-wrapper');
        var searchInput = wrapper.find('.searchPegawaiSA');
        var targetBody = searchInput.data('target');
        
        // Cek apakah ini di tab SA (punya targetBody) atau di tab PIC
        if (targetBody) {
            applyFilters(targetBody, searchInput, wrapper.find('.filterMingguKinerja'), wrapper.find('.filterStatusKinerja'));
        }
    });
});
</script>
<?= $this->endSection(); ?>
