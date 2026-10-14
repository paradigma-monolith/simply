<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<style>
/* Filter styling */
.form-label-sm {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #495057;
}

.form-control-sm {
    font-size: 0.875rem;
    border-radius: 0.25rem;
    transition: all 0.15s ease-in-out;
}

.form-control-sm:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card-body.bg-light {
    background-color: #f8f9fa !important;
}

@media (max-width: 768px) {
    .form-group.mb-2 {
        margin-bottom: 1rem !important;
    }
}
</style>
<section class="content">
        <div class="container-fluid">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0 font-weight-bold">
                                <i class="fas fa-filter mr-2"></i>
                                Filter Dashboard Anggaran
                            </h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="filter_bulan_dari" class="form-label-sm">Bulan Dari:</label>
                                        <select class="form-control form-control-sm" id="filter_bulan_dari">
                                            <option value="">Semua Bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="filter_bulan_sampai" class="form-label-sm">Bulan Sampai:</label>
                                        <select class="form-control form-control-sm" id="filter_bulan_sampai">
                                            <option value="">Semua Bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="filter_akun" class="form-label-sm">Akun:</label>
                                        <select class="form-control form-control-sm" id="filter_akun">
                                            <option value="">Semua Akun</option>
                                            <option value="51">Akun 51</option>
                                            <option value="52">Akun 52</option>
                                            <option value="53">Akun 53</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="filter_pic" class="form-label-sm">PIC:</label>
                                        <select class="form-control form-control-sm" id="filter_pic">
                                            <option value="">Semua PIC</option>
                                            <option value="Timker 1">Timker 1</option>
                                            <option value="Timker 2">Timker 2</option>
                                            <option value="Timker 3">Timker 3</option>
                                            <option value="Timker 4">Timker 4</option>
                                            <option value="Timker 5">Timker 5</option>
                                            <option value="ADUM">ADUM</option>
                                            <option value="Gabungan">Gabungan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary btn-sm" id="resetFilter">
                                        <i class="fas fa-undo"></i> Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info boxes -->
            <div class="row mb-4" id="infoBoxes">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="info-box mb-3 h-100 shadow-sm">
                        <span class="info-box-icon bg-info elevation-2"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Total Rencana</span>
                            <span class="info-box-number text-info" id="totalRencanaDisplay">
                                Rp <?= number_format($totalRencana, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="info-box mb-3 h-100 shadow-sm">
                        <span class="info-box-icon bg-success elevation-2"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Total Realisasi</span>
                            <span class="info-box-number text-success" id="totalRealisasiDisplay">
                                Rp <?= number_format($totalRealisasi, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="info-box mb-3 h-100 shadow-sm">
                        <span class="info-box-icon bg-warning elevation-2"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Persentase Realisasi</span>
                            <span class="info-box-number text-warning" id="persentaseRealisasiDisplay"><?= $persentaseRealisasi ?>%</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="info-box mb-3 h-100 shadow-sm">
                        <span class="info-box-icon <?php 
                            if ($totalDeviasi > 0) {
                                echo 'bg-danger';
                            } elseif ($totalDeviasi < 0) {
                                echo 'bg-success';
                            } else {
                                echo 'bg-warning';
                            }
                        ?> elevation-2" id="deviasiIcon">
                            <i class="fas <?php 
                                if ($totalDeviasi > 0) {
                                    echo 'fa-arrow-up';
                                } elseif ($totalDeviasi < 0) {
                                    echo 'fa-arrow-down';
                                } else {
                                    echo 'fa-exclamation-triangle';
                                }
                            ?>"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">Total Deviasi</span>
                            <span class="info-box-number text-danger" id="totalDeviasiDisplay">
                                <?= $totalDeviasi >= 0 ? '+' : '' ?>Rp <?= number_format($totalDeviasi, 0, ',', '.') ?>
                            </span>
                            <small class="text-muted font-weight-bold" id="persentaseDeviasiDisplay"><?= $persentaseDeviasi >= 0 ? '+' : '' ?><?= $persentaseDeviasi ?>%</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Akun 51, 52 & 53 Summary -->
            <div class="row mb-3" id="akunSummary">
                <div class="col-lg-4 mb-2">
                    <div class="card shadow-sm border-0" id="akun51Card">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary rounded-circle p-2 mr-2">
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 font-weight-bold text-primary">Akun 51</h6>
                                    <small class="text-muted">Belanja Pegawai</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Rencana</small>
                                        <h6 class="font-weight-bold text-primary mb-0" id="akun51Rencana">
                                            Rp <?= number_format($akun51['total_rencana'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Realisasi</small>
                                        <h6 class="font-weight-bold text-success mb-0" id="akun51Realisasi">
                                            Rp <?= number_format($akun51['total_realisasi'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="font-weight-bold text-primary" id="akun51Progress"><?= $akun51['persentase'] ?? 0 ?>%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" id="akun51ProgressBar" style="width: <?= $akun51['persentase'] ?? 0 ?>%"></div>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Deviasi:</small>
                                    <small class="font-weight-bold <?php 
                                        $deviasi51 = ($akun51['total_realisasi'] ?? 0) - ($akun51['total_rencana'] ?? 0);
                                        echo $deviasi51 >= 0 ? 'text-danger' : 'text-warning';
                                    ?>" id="akun51Deviasi">
                                        <?= $deviasi51 >= 0 ? '+' : '' ?>Rp <?= number_format($deviasi51, 0, ',', '.') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-2">
                    <div class="card shadow-sm border-0" id="akun52Card">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-secondary rounded-circle p-2 mr-2">
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 font-weight-bold text-secondary">Akun 52</h6>
                                    <small class="text-muted">Belanja Barang</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Rencana</small>
                                        <h6 class="font-weight-bold text-secondary mb-0" id="akun52Rencana">
                                            Rp <?= number_format($akun52['total_rencana'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Realisasi</small>
                                        <h6 class="font-weight-bold text-success mb-0" id="akun52Realisasi">
                                            Rp <?= number_format($akun52['total_realisasi'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="font-weight-bold text-secondary" id="akun52Progress"><?= $akun52['persentase'] ?? 0 ?>%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-secondary" id="akun52ProgressBar" style="width: <?= $akun52['persentase'] ?? 0 ?>%"></div>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Deviasi:</small>
                                    <small class="font-weight-bold <?php 
                                        $deviasi52 = ($akun52['total_realisasi'] ?? 0) - ($akun52['total_rencana'] ?? 0);
                                        echo $deviasi52 >= 0 ? 'text-danger' : 'text-warning';
                                    ?>" id="akun52Deviasi">
                                        <?= $deviasi52 >= 0 ? '+' : '' ?>Rp <?= number_format($deviasi52, 0, ',', '.') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-2">
                    <div class="card shadow-sm border-0" id="akun53Card">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info rounded-circle p-2 mr-2">
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0 font-weight-bold text-info">Akun 53</h6>
                                    <small class="text-muted">Belanja Modal</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Rencana</small>
                                        <h6 class="font-weight-bold text-info mb-0" id="akun53Rencana">
                                            Rp <?= number_format($akun53['total_rencana'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Realisasi</small>
                                        <h6 class="font-weight-bold text-success mb-0" id="akun53Realisasi">
                                            Rp <?= number_format($akun53['total_realisasi'] ?? 0, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="font-weight-bold text-info" id="akun53Progress"><?= $akun53['persentase'] ?? 0 ?>%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" id="akun53ProgressBar" style="width: <?= $akun53['persentase'] ?? 0 ?>%"></div>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Deviasi:</small>
                                    <small class="font-weight-bold <?php 
                                        $deviasi53 = ($akun53['total_realisasi'] ?? 0) - ($akun53['total_rencana'] ?? 0);
                                        echo $deviasi53 >= 0 ? 'text-danger' : 'text-warning';
                                    ?>" id="akun53Deviasi">
                                        <?= $deviasi53 >= 0 ? '+' : '' ?>Rp <?= number_format($deviasi53, 0, ',', '.') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Chart Realisasi per PIC -->
                <div class="col-lg-6 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 font-weight-bold text-dark">
                                <i class="fas fa-chart-pie text-primary mr-2"></i>
                                Realisasi Anggaran per PIC
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="picChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar per PIC -->
                <div class="col-lg-6 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 font-weight-bold text-dark">
                                <i class="fas fa-chart-bar text-success mr-2"></i>
                                Progress Realisasi per PIC
                            </h5>
                        </div>
                        <div class="card-body" id="picProgressContainer">
                            <?php foreach ($picSummary as $pic => $data): ?>
                                <?php $percentage = $data['rencana'] > 0 ? round(($data['realisasi'] / $data['rencana']) * 100, 2) : 0; ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="font-weight-bold"><?= $pic ?></small>
                                        <small class="badge <?= $percentage >= 80 ? 'badge-success' : ($percentage >= 50 ? 'badge-warning' : 'badge-danger') ?>"><?= $percentage ?>%</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar <?= $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                             role="progressbar" style="width: <?= $percentage ?>%" 
                                             aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted" style="font-size: 10px;">Rp <?= number_format($data['realisasi'], 0, ',', '.') ?></small>
                                        <small class="text-muted" style="font-size: 10px;">Rp <?= number_format($data['rencana'], 0, ',', '.') ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Anggaran -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 font-weight-bold text-dark">
                                    <i class="fas fa-table text-info mr-2"></i>
                                    Data Anggaran 
                                </h5>
                                <div class="card-tools">
                                    <a href="/anggaran/data" class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-eye"></i> Lihat Semua Data
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover" id="dashboardTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Kegiatan</th>
                                        <th>Akun</th>
                                        <th>Kegiatan</th>
                                        <th>Rencana Pencairan</th>
                                        <th>PIC</th>
                                        <th>Realisasi</th>
                                        <th>Bulan</th>
                                        <th style="min-width: 180px;">Deviasi</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($anggaranData)): ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data anggaran</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($anggaranData as $index => $item): ?>
                                            <?php 
                                                $progress = $item['rencana_pencairan_anggaran'] > 0 ? round(($item['realisasi'] / $item['rencana_pencairan_anggaran']) * 100, 2) : 0;
                                                $rencana = (float) $item['rencana_pencairan_anggaran'];
                                                $realisasi = (float) ($item['realisasi'] ?? 0);
                                                $selisih = $realisasi - $rencana;
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $item['no_kegiatan'] ?></td>
                                                <td>
                                                    <?php 
                                                        $akunClass = '';
                                                        $akunText = '';
                                                        switch($item['akun'] ?? '') {
                                                            case '51':
                                                                $akunClass = 'badge-primary';
                                                                $akunText = 'Akun 51';
                                                                break;
                                                            case '52':
                                                                $akunClass = 'badge-secondary';
                                                                $akunText = 'Akun 52';
                                                                break;
                                                            case '53':
                                                                $akunClass = 'badge-info';
                                                                $akunText = 'Akun 53';
                                                                break;
                                                            default:
                                                                $akunClass = 'badge-light';
                                                                $akunText = 'N/A';
                                                        }
                                                    ?>
                                                    <span class="badge <?= $akunClass ?>"><?= $akunText ?></span>
                                                </td>
                                                <td>
                                                    <div style="max-width: 300px; word-wrap: break-word;" 
                                                         data-toggle="tooltip" 
                                                         title="<?= htmlspecialchars($item['kegiatan']) ?>">
                                                        <?= $item['kegiatan'] ?>
                                                    </div>
                                                </td>
                                                <td>Rp <?= number_format((float)$item['rencana_pencairan_anggaran'], 0, ',', '.') ?></td>
                                <td><?= $item['pic'] ?></td>
                                <td>Rp <?= number_format((float)($item['realisasi'] ?? 0), 0, ',', '.') ?></td>
                                                <td>
                                    <?php 
                                        $namaBulan = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        if (is_numeric($item['bulan'])) {
                                            echo $namaBulan[(int)$item['bulan']] ?? 'Tidak Valid';
                                        } else {
                                            echo htmlspecialchars($item['bulan'] ?? '-');
                                        }
                                    ?>
                                </td>
                                                <td>
                                                    <?php 
                                                    $deviasi = (float)($item['realisasi'] ?? 0) - (float)($item['rencana_pencairan_anggaran'] ?? 0);
                                                    $class = $deviasi >= 0 ? 'text-success' : 'text-danger';
                                                    ?>
                                                    <span class="<?= $class ?>">
                                                        <?= $deviasi >= 0 ? '+' : '' ?>Rp <?= number_format($deviasi, 0, ',', '.') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar <?= $progress >= 80 ? 'bg-success' : ($progress >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                             style="width: <?= $progress ?>%"></div>
                                                    </div>
                                                    <span class="badge <?= $progress >= 80 ? 'badge-success' : ($progress >= 50 ? 'badge-warning' : 'badge-danger') ?>">
                                                        <?= $progress ?>%
                                                    </span>
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
</section>

<!-- Add Data Modal -->
<!-- Modal Tambah Data -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="addModalLabel">
                    <i class="fas fa-plus"></i> Form Tambah Data Anggaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_no_kegiatan">No Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_no_kegiatan" name="no_kegiatan" placeholder="Contoh: 4249.QAK.012" required>
                                <small class="form-text text-muted">Format: XXXX.XXX.XXX</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_akun">Akun <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_akun" name="akun" required>
                                    <option value="">Pilih Akun</option>
                                    <option value="51">Akun 51</option>
                                    <option value="52">Akun 52</option>
                                    <option value="53">Akun 53</option>
                                </select>
                                <small class="form-text text-muted">Pilih akun 51, 52, atau 53</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_pic">PIC (Person In Charge) <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_pic" name="pic" required>
                                    <option value="">Pilih PIC</option>
                                    <option value="Timker 1">Timker 1</option>
                                    <option value="Timker 2">Timker 2</option>
                                    <option value="Timker 3">Timker 3</option>
                                    <option value="Timker 4">Timker 4</option>
                                    <option value="Timker 5">Timker 5</option>
                                    <option value="ADUM">ADUM</option>
                                    <option value="Gabungan">Gabungan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_kegiatan">Kegiatan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="add_kegiatan" name="kegiatan" rows="3" placeholder="Masukkan deskripsi kegiatan" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_rencana_pencairan_anggaran">Rencana Pencairan Anggaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="add_rencana_pencairan_anggaran" name="rencana_pencairan_anggaran" placeholder="0" required>
                                </div>
                                <small class="form-text text-muted">Masukkan angka tanpa titik atau koma</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_realisasi">Realisasi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="add_realisasi" name="realisasi" placeholder="0">
                                </div>
                                <small class="form-text text-muted">Opsional - dapat diisi nanti</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_tahun_anggaran">Tahun Anggaran <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_tahun_anggaran" name="tahun_anggaran" required>
                                    <?php for ($year = date('Y') + 1; $year >= 2020; $year--): ?>
                                        <option value="<?= $year ?>" <?= $year == date('Y') ? 'selected' : '' ?>><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_bulan">Bulan <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Store original data for filtering
    const originalData = <?= json_encode($anggaranData) ?>;
    const originalPicSummary = <?= json_encode($picSummary) ?>;
    
    // Function to format currency
    function formatCurrency(value) {
        if (value >= 1000000000) {
            return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
        } else if (value >= 1000000) {
            return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
        } else if (value >= 1000) {
            return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
        } else {
            return 'Rp ' + value.toLocaleString('id-ID');
        }
    }
    
    // Function to get filtered data
    function getFilteredData() {
        const bulanDari = $('#filter_bulan_dari').val();
        const bulanSampai = $('#filter_bulan_sampai').val();
        const akun = $('#filter_akun').val();
        const pic = $('#filter_pic').val();
        
        return originalData.filter(function(item) {
            let include = true;
            
            // Filter by month range
            if (bulanDari && parseInt(item.bulan) < parseInt(bulanDari)) {
                include = false;
            }
            if (bulanSampai && parseInt(item.bulan) > parseInt(bulanSampai)) {
                include = false;
            }
            
            // Filter by account
            if (akun && item.akun !== akun) {
                include = false;
            }
            
            // Filter by PIC
            if (pic && item.pic !== pic) {
                include = false;
            }
            
            return include;
        });
    }
    
    // Function to update info boxes
    function updateInfoBoxes(filteredData) {
        let totalRencana = 0;
        let totalRealisasi = 0;
        
        filteredData.forEach(function(item) {
             totalRencana += parseFloat(item.rencana_pencairan_anggaran || 0);
             totalRealisasi += parseFloat(item.realisasi || 0);
         });
        
        const persentaseRealisasi = totalRencana > 0 ? (totalRealisasi / totalRencana * 100) : 0;
        const totalDeviasi = totalRencana - totalRealisasi;
        const persentaseDeviasi = totalRencana > 0 ? (totalDeviasi / totalRencana * 100) : 0;
        
        // Update displays
        $('#totalRencanaDisplay').text(formatCurrency(totalRencana));
        $('#totalRealisasiDisplay').text(formatCurrency(totalRealisasi));
        $('#persentaseRealisasiDisplay').text(persentaseRealisasi.toFixed(1) + '%');
        $('#totalDeviasiDisplay').text(formatCurrency(Math.abs(totalDeviasi)));
        $('#persentaseDeviasiDisplay').text(persentaseDeviasi.toFixed(1) + '%');
        
        // Update deviation icon
        const deviasiIcon = $('#deviasiIcon');
        const deviasiIconElement = deviasiIcon.find('i');
        
        if (totalDeviasi > 0) {
            // Positive deviation (over budget) - show red up arrow
            deviasiIcon.removeClass('bg-success').addClass('bg-danger');
            deviasiIconElement.removeClass('fa-exclamation-triangle fa-arrow-down').addClass('fa-arrow-up');
        } else if (totalDeviasi < 0) {
            // Negative deviation (under budget) - show green down arrow
            deviasiIcon.removeClass('bg-danger').addClass('bg-success');
            deviasiIconElement.removeClass('fa-exclamation-triangle fa-arrow-up').addClass('fa-arrow-down');
        } else {
            // No deviation - show warning triangle
            deviasiIcon.removeClass('bg-danger bg-success').addClass('bg-warning');
            deviasiIconElement.removeClass('fa-arrow-up fa-arrow-down').addClass('fa-exclamation-triangle');
        }
    }
    
    // Function to update account summary cards
    function updateAkunSummary(filteredData) {
        const akunSummary = {
            '51': { rencana: 0, realisasi: 0 },
            '52': { rencana: 0, realisasi: 0 },
            '53': { rencana: 0, realisasi: 0 }
        };
        
        filteredData.forEach(function(item) {
             const akun = item.akun;
             if (akunSummary[akun]) {
                 akunSummary[akun].rencana += parseFloat(item.rencana_pencairan_anggaran || 0);
                 akunSummary[akun].realisasi += parseFloat(item.realisasi || 0);
             }
         });
        
        // Update each account card using specific IDs
        Object.keys(akunSummary).forEach(function(akun) {
            const data = akunSummary[akun];
            const persentase = data.rencana > 0 ? (data.realisasi / data.rencana * 100) : 0;
            const deviasi = data.rencana - data.realisasi;
            
            // Update specific elements by ID
            $(`#akun${akun}Rencana`).text(formatCurrency(data.rencana));
            $(`#akun${akun}Realisasi`).text(formatCurrency(data.realisasi));
            $(`#akun${akun}Progress`).text(persentase.toFixed(1) + '%');
            $(`#akun${akun}ProgressBar`).css('width', persentase.toFixed(1) + '%');
            
            // Update deviasi with proper formatting and color
            const deviasiElement = $(`#akun${akun}Deviasi`);
            deviasiElement.text((deviasi >= 0 ? '+' : '') + formatCurrency(Math.abs(deviasi)));
            deviasiElement.removeClass('text-danger text-warning');
            deviasiElement.addClass(deviasi >= 0 ? 'text-danger' : 'text-warning');
        });
    }
    
    // Function to filter table
    function filterTable() {
        const bulanDari = $('#filter_bulan_dari').val();
        const bulanSampai = $('#filter_bulan_sampai').val();
        const akun = $('#filter_akun').val();
        const pic = $('#filter_pic').val();
        
        $('#dashboardTable tbody tr').each(function() {
            const row = $(this);
            let showRow = true;
            
            // Skip if this is the "no data" row
            if (row.find('td').length === 1 && row.find('td').attr('colspan')) {
                return;
            }
            
            // Get row data
            const rowBulan = row.find('td:eq(7)').text().trim(); // Bulan column
            const rowAkun = row.find('td:eq(2) .badge').text().trim(); // Akun column
            const rowPic = row.find('td:eq(5)').text().trim(); // PIC column
            
            // Convert month name to number for comparison
            const monthMap = {
                'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
            };
            const rowBulanNum = monthMap[rowBulan] || 0;
            
            // Filter by month range
            if (bulanDari && rowBulanNum < parseInt(bulanDari)) {
                showRow = false;
            }
            if (bulanSampai && rowBulanNum > parseInt(bulanSampai)) {
                showRow = false;
            }
            
            // Filter by account
            if (akun && !rowAkun.includes(akun)) {
                showRow = false;
            }
            
            // Filter by PIC
            if (pic && rowPic !== pic) {
                showRow = false;
            }
            
            // Show/hide row
            if (showRow) {
                row.show();
            } else {
                row.hide();
            }
        });
    }
    
    // Function to update PIC progress bars
    function updatePicProgress(filteredData) {
        // Calculate PIC summary from filtered data
        const picSummary = {};
        
        filteredData.forEach(function(item) {
            if (!picSummary[item.pic]) {
                picSummary[item.pic] = { rencana: 0, realisasi: 0 };
            }
            picSummary[item.pic].rencana += parseFloat(item.rencana_pencairan_anggaran || 0);
            picSummary[item.pic].realisasi += parseFloat(item.realisasi || 0);
        });
        
        // Update PIC progress container
        const container = $('#picProgressContainer');
        container.empty();
        
        Object.keys(picSummary).forEach(function(pic) {
            const data = picSummary[pic];
            const percentage = data.rencana > 0 ? (data.realisasi / data.rencana * 100) : 0;
            const badgeClass = percentage >= 80 ? 'badge-success' : (percentage >= 50 ? 'badge-warning' : 'badge-danger');
            const progressClass = percentage >= 80 ? 'bg-success' : (percentage >= 50 ? 'bg-warning' : 'bg-danger');
            
            const progressHtml = `
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="font-weight-bold">${pic}</small>
                        <small class="badge ${badgeClass}">${percentage.toFixed(1)}%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar ${progressClass}" 
                             role="progressbar" style="width: ${percentage.toFixed(1)}%" 
                             aria-valuenow="${percentage.toFixed(1)}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" style="font-size: 10px;">Rp ${data.realisasi.toLocaleString('id-ID')}</small>
                        <small class="text-muted" style="font-size: 10px;">Rp ${data.rencana.toLocaleString('id-ID')}</small>
                    </div>
                </div>
            `;
            container.append(progressHtml);
        });
    }
    
    // Function to update chart
    function updateChart(filteredData) {
        // Calculate PIC summary from filtered data
        const picSummary = {};
        
        filteredData.forEach(function(item) {
            if (!picSummary[item.pic]) {
                picSummary[item.pic] = { rencana: 0, realisasi: 0 };
            }
            picSummary[item.pic].rencana += parseFloat(item.rencana_pencairan_anggaran || 0);
             picSummary[item.pic].realisasi += parseFloat(item.realisasi || 0);
        });
        
        // Update chart data
        const labels = Object.keys(picSummary);
        const rencanaData = labels.map(function(pic) {
            return picSummary[pic].rencana;
        });
        const realisasiData = labels.map(function(pic) {
            return picSummary[pic].realisasi;
        });
        
        // Update chart
        if (window.picChart) {
            window.picChart.data.labels = labels;
            window.picChart.data.datasets[0].data = rencanaData;
            window.picChart.data.datasets[1].data = realisasiData;
            window.picChart.update();
        }
    }
    
    // Main filter function
    function applyFilters() {
        const filteredData = getFilteredData();
        
        updateInfoBoxes(filteredData);
        updateAkunSummary(filteredData);
        updatePicProgress(filteredData);
        updateChart(filteredData);
        filterTable();
    }
    
    // Bind filter events with proper event handling
    $(document).on('change', '#filter_bulan_dari, #filter_bulan_sampai, #filter_akun, #filter_pic', function() {
        applyFilters();
    });
    
    // Reset filter
    $(document).on('click', '#resetFilter', function() {
        $('#filter_bulan_dari, #filter_bulan_sampai, #filter_akun, #filter_pic').val('');
        applyFilters();
    });
    
    // Initialize tooltips
     $('[data-toggle="tooltip"]').tooltip();
    
    // Chart untuk realisasi per PIC
    var ctx = document.getElementById('picChart').getContext('2d');
    var picData = <?= json_encode($picSummary) ?>;
    
    var labels = Object.keys(picData);
    var rencanaData = labels.map(function(pic) {
        return picData[pic].rencana;
    });
    var realisasiData = labels.map(function(pic) {
        return picData[pic].realisasi;
    });
    
    // Function to format currency in millions/billions
    function formatCurrency(value) {
        if (value >= 1000000000) {
            return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
        } else if (value >= 1000000) {
            return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
        } else if (value >= 1000) {
            return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
        } else {
            return 'Rp ' + value.toLocaleString('id-ID');
        }
    }
    
    window.picChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rencana',
                data: rencanaData,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 4,
                borderSkipped: false
            }, {
                label: 'Realisasi',
                data: realisasiData,
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value, index, values) {
                            return formatCurrency(value);
                        },
                        font: {
                            size: 11
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.2)',
                    borderWidth: 1,
                    cornerRadius: 6,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return 'PIC: ' + context[0].label;
                        },
                        label: function(context) {
                            var value = context.parsed.y;
                            var formattedValue = 'Rp ' + value.toLocaleString('id-ID');
                            return context.dataset.label + ': ' + formattedValue;
                        },
                        afterBody: function(context) {
                            var pic = context[0].label;
                            var rencana = context[0].chart.data.datasets[0].data[context[0].dataIndex];
                            var realisasi = context[0].chart.data.datasets[1].data[context[0].dataIndex];
                            var percentage = rencana > 0 ? ((realisasi / rencana) * 100).toFixed(1) : 0;
                            return 'Progress: ' + percentage + '%';
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
    
    // Handle Add Form Submission
    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        
        $.ajax({
            url: '/anggaran/store',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Close modal and reload page
                        $('#addModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(field) {
                        const input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        // Create invalid-feedback if it doesn't exist
                        if (input.siblings('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback"></div>');
                        }
                        input.siblings('.invalid-feedback').text(errors[field]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server'
                    });
                }
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Reset form when modal is closed
    $('#addModal').on('hidden.bs.modal', function() {
        $('#addForm')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
});
</script>
<?= $this->endSection() ?>