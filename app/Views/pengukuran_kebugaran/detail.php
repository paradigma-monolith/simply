<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-eye text-info"></i> Detail Pengukuran Kebugaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/pengukuran_kebugaran">Pengukuran Kebugaran</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-heartbeat mr-2"></i>
                            Detail Pengukuran Kebugaran
                        </h3>
                        <div class="card-tools">
                            <a href="/pengukuran_kebugaran" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Informasi Umum -->
                            <div class="col-md-6">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-user mr-2"></i>
                                            Informasi Umum
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Nama</strong></td>
                                                <td>: <?= $pengukuran['nama'] ?></td>
                                            </tr>
                                            <?php if (!empty($pengukuran['nip'])): ?>
                                            <tr>
                                                <td><strong>NIP</strong></td>
                                                <td>: <?= $pengukuran['nip'] ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td><strong>Tanggal Pengukuran</strong></td>
                                                <td>: <?= date('d F Y', strtotime($pengukuran['tanggal_pengukuran'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Berat Badan</strong></td>
                                                <td>: <?= $pengukuran['berat_badan'] ?> kg</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tinggi Badan</strong></td>
                                                <td>: <?= $pengukuran['tinggi_badan'] ?> cm</td>
                                            </tr>
                                            <tr>
                                                <td><strong>IMT</strong></td>
                                                <td>: <?= number_format($pengukuran['imt'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kategori IMT</strong></td>
                                                <td>: 
                                                    <?php
                                                    $badgeClass = '';
                                                    switch($pengukuran['kategori_imt']) {
                                                        case 'Underweight':
                                                            $badgeClass = 'badge-info';
                                                            break;
                                                        case 'Normal':
                                                            $badgeClass = 'badge-success';
                                                            break;
                                                        case 'Overweight':
                                                            $badgeClass = 'badge-warning';
                                                            break;
                                                        case 'Obesitas':
                                                            $badgeClass = 'badge-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'badge-secondary';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>"><?= $pengukuran['kategori_imt'] ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Parameter Kesehatan -->
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-stethoscope mr-2"></i>
                                            Parameter Kesehatan
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Tekanan Darah</strong></td>
                                                <td>: <?= $pengukuran['tekanan_darah_sistolik'] ?>/<?= $pengukuran['tekanan_darah_diastolik'] ?> mmHg</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gula Darah Sewaktu</strong></td>
                                                <td>: <?= (isset($pengukuran['gula_darah_sewaktu']) && $pengukuran['gula_darah_sewaktu'] !== '' && $pengukuran['gula_darah_sewaktu'] != 0) ? $pengukuran['gula_darah_sewaktu'] . ' mg/dL' : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kolesterol Total</strong></td>
                                                <td>: <?= (isset($pengukuran['kolesterol_total']) && $pengukuran['kolesterol_total'] !== '' && $pengukuran['kolesterol_total'] != 0) ? $pengukuran['kolesterol_total'] . ' mg/dL' : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Asam Urat</strong></td>
                                                <td>: <?= (isset($pengukuran['asam_urat']) && $pengukuran['asam_urat'] !== '') ? $pengukuran['asam_urat'] . ' mg/dL' : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Lingkar Pinggang</strong></td>
                                                <td>: <?= $pengukuran['lingkar_pinggang'] ?? '-' ?> cm
                                                    <?php if (!empty($pengukuran['kategori_lingkar_pinggang'])): ?>
                                                    
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Status Indikator -->
                                        <div class="mt-3">
                                            <h6><strong>Status Kesehatan:</strong></h6>
                                            <div class="row">
                                                <div class="col-12">
                                                    <?php
                                                    // Evaluasi tekanan darah
                                                    $sistolik = $pengukuran['tekanan_darah_sistolik'];
                                                    $diastolik = $pengukuran['tekanan_darah_diastolik'];
                                                    if ($sistolik < 120 && $diastolik < 80) {
                                                        echo '<span class="badge badge-success mr-1">Tekanan Darah Normal</span>';
                                                    } elseif ($sistolik >= 140 || $diastolik >= 90) {
                                                        echo '<span class="badge badge-danger mr-1">Tekanan Darah Tinggi</span>';
                                                    } else {
                                                        echo '<span class="badge badge-warning mr-1">Tekanan Darah Prehipertensi</span>';
                                                    }
                                                    
                                                    // Evaluasi gula darah
                                                    $gulaDarah = $pengukuran['gula_darah_sewaktu'] ?? null;
                                                    if ($gulaDarah !== null && $gulaDarah !== '' && $gulaDarah != 0) {
                                                        if ($gulaDarah < 140) {
                                                            echo '<span class="badge badge-success mr-1">Gula Darah Normal</span>';
                                                        } elseif ($gulaDarah >= 200) {
                                                            echo '<span class="badge badge-danger mr-1">Gula Darah Tinggi</span>';
                                                        } else {
                                                            echo '<span class="badge badge-warning mr-1">Gula Darah Borderline</span>';
                                                        }
                                                    }
                                                    
                                                    // Evaluasi kolesterol
                                                    $kolesterol = $pengukuran['kolesterol_total'] ?? null;
                                                    if ($kolesterol !== null && $kolesterol !== '' && $kolesterol != 0) {
                                                        if ($kolesterol < 200) {
                                                            echo '<span class="badge badge-success mr-1">Kolesterol Normal</span>';
                                                        } elseif ($kolesterol >= 240) {
                                                            echo '<span class="badge badge-danger mr-1">Kolesterol Tinggi</span>';
                                                        } else {
                                                            echo '<span class="badge badge-warning mr-1">Kolesterol Borderline</span>';
                                                        }
                                                    }

                                                    // Evaluasi asam urat (opsional)
                                                    $asamUrat = $pengukuran['asam_urat'] ?? null;
                                                    if ($asamUrat !== null && $asamUrat !== '') {
                                                        if ($asamUrat < 7) {
                                                            echo '<span class="badge badge-success mr-1">Asam Urat Normal</span>';
                                                        } else {
                                                            echo '<span class="badge badge-danger mr-1">Asam Urat Tinggi</span>';
                                                        }
                                                    }
                                                    
                                                    // Evaluasi lingkar pinggang
                                                    if (!empty($pengukuran['kategori_lingkar_pinggang'])) {
                                                        if ($pengukuran['kategori_lingkar_pinggang'] == 'Obesitas Sentral') {
                                                            echo '<span class="badge badge-danger mr-1">Obesitas Sentral</span>';
                                                        } else {
                                                            echo '<span class="badge badge-success mr-1">Lingkar Pinggang Normal</span>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kategori Normal -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Kategori Normal (Referensi)
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h6><strong>Tekanan Darah</strong></h6>
                                                <p class="text-muted">Normal: < 120/80 mmHg</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6><strong>Gula Darah Sewaktu</strong></h6>
                                                <p class="text-muted">Normal: < 140 mg/dL</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6><strong>Kolesterol Total</strong></h6>
                                                <p class="text-muted">Normal: < 200 mg/dL</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6><strong>Asam Urat</strong></h6>
                                                <p class="text-muted">Normal: < 7 mg/dL</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6><strong>Lingkar Pinggang</strong></h6>
                                                <p class="text-muted">
                                                    <?php 
                                                    $nip = $pengukuran['nip'] ?? '';
                                                    $jenisKelamin = 'Laki-laki';
                                                    if (strlen($nip) >= 4) {
                                                        $digit = substr($nip, -4, 1);
                                                        $jenisKelamin = ($digit == '1') ? 'Laki-laki' : 'Perempuan';
                                                    }
                                                    echo $jenisKelamin . ': ';
                                                    echo ($jenisKelamin == 'Laki-laki') ? '≤ 90 cm' : '≤ 80 cm';
                                                    ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6><strong>IMT</strong></h6>
                                                <p class="text-muted">Normal: 18.5 - 24.9</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <a href="/pengukuran_kebugaran" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                </a>
                                <?php if (session()->get('role') == 'Admin' || $pengukuran['id_user'] == session()->get('id_user')): ?>
                                <a href="/pengukuran_kebugaran/edit/<?= $pengukuran['id_pengukuran_kebugaran'] ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Data
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>