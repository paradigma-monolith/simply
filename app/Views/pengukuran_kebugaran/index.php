<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header py-2">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-running text-danger"></i> Pengukuran Kebugaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Pengukuran Kebugaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($isAdmin): ?>
            <!-- Admin Info -->
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info-circle"></i> Informasi Admin</h5>
                Sebagai admin, Anda dapat menambahkan data pengukuran kebugaran untuk semua pegawai. 
                Pada form tambah data, Anda dapat memilih pegawai yang akan dibuatkan data pengukuran.
            </div>
            <?php endif; ?>

            <!-- Main Card -->
            <div class="card card-primary card-outline elevation-2 mt-2">
                <div class="card-header py-2">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Data Pengukuran Kebugaran
                        <?php if($isAdmin): ?>
                            <small class="text-muted">(Semua Pegawai)</small>
                        <?php endif; ?>
                    </h3>
                    <div class="card-tools">
                        <?php if($isAdmin): ?>
                            <a href="/pengukuran_kebugaran/tambah" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Data Pegawai
                            </a>
                            <a href="/pengukuran_kebugaran/import" class="btn btn-success btn-sm ml-1">
                                <i class="fas fa-file-excel"></i> Import Excel
                            </a>
                            <button type="button" class="btn btn-info btn-sm ml-1" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export Excel
                            </button>
                            <a href="/pengukuran_kebugaran/pengaturan" class="btn btn-warning btn-sm ml-1">
                                <i class="fas fa-users-cog"></i> Pengaturan Akses
                            </a>
                        <?php elseif(isset($hasAllAccess) && $hasAllAccess): ?>
                            <a href="/pengukuran_kebugaran/tambah" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Data Pegawai
                            </a>
                            <a href="/pengukuran_kebugaran/import" class="btn btn-success btn-sm ml-1">
                                <i class="fas fa-file-excel"></i> Import Excel
                            </a>
                            <button type="button" class="btn btn-info btn-sm ml-1" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export Excel
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="pengukuranTable" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%" class="text-center">No</th>
                                    <th class="text-center">Tanggal</th>
                                    <?php if($isAdmin || (isset($hasAllAccess) && $hasAllAccess)): ?>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                    <?php else: ?>
                                        <th>Nama</th>
                                    <?php endif; ?>
                                    <th class="text-center">Tekanan Darah</th>
                                    <th class="text-center">Gula Darah</th>
                                    <th class="text-center">Kolesterol</th>
                                    <th class="text-center">Asam Urat</th>
                                    <th class="text-center">Lingkar Pinggang</th>
                                    <th class="text-center">BB/TB</th>
                                    <th class="text-center">IMT</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($pengukuranData)): ?>
                                    <tr>
                                        <td colspan="<?= ($isAdmin || (isset($hasAllAccess) && $hasAllAccess)) ? '12' : '11' ?>" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data pengukuran</h5>
                                                <?php if($isAdmin || (isset($hasAllAccess) && $hasAllAccess)): ?>
                                                    <p class="text-muted">Klik tombol "Tambah Data Pegawai" untuk menambah pengukuran baru</p>
                                                <?php else: ?>
                                                    <p class="text-muted">Data pengukuran kebugaran Anda belum tersedia</p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; foreach($pengukuranData as $data): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-info">
                                                    <?= date('d/m/Y', strtotime($data['tanggal_pengukuran'])) ?>
                                                </span>
                                            </td>
                                            <?php if($isAdmin || (isset($hasAllAccess) && $hasAllAccess)): ?>
                                                <td><?= $data['nama'] ?></td>
                                                <td><?= $data['nip'] ?? '-' ?></td>
                                            <?php else: ?>
                                                <td><?= $data['nama'] ?></td>
                                            <?php endif; ?>
                                            <td class="text-center">
                                                <?php
                                                // Menentukan status tekanan darah
                                                $sistolik = $data['tekanan_darah_sistolik'];
                                                $diastolik = $data['tekanan_darah_diastolik'];
                                                $tekananDarahClass = 'success';
                                                $tekananDarahStatus = 'Normal';
                                                
                                                if (($sistolik >= 120 && $sistolik <= 139) || ($diastolik >= 85 && $diastolik <= 89)) {
                                                    $tekananDarahClass = 'warning';
                                                    $tekananDarahStatus = 'Prahipertensi';
                                                } else if (($sistolik >= 140 && $sistolik <= 159) || ($diastolik >= 90 && $diastolik <= 99)) {
                                                    $tekananDarahClass = 'warning';
                                                    $tekananDarahStatus = 'Hipertensi 1';
                                                } else if ($sistolik >= 160 || $diastolik >= 100) {
                                                    $tekananDarahClass = 'danger';
                                                    $tekananDarahStatus = 'Hipertensi 2';
                                                }
                                                ?>
                                                <div>
                                                    <span class="text-bold">
                                                        <?= $sistolik ?>/<?= $diastolik ?>
                                                    </span>
                                                    <small class="text-muted">mmHg</small>
                                                </div>
                                                <span class="badge badge-<?= $tekananDarahClass ?> badge-pill mt-1">
                                                    <?= $tekananDarahStatus ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                // Menentukan status gula darah
                                                $gulaDarah = $data['gula_darah_sewaktu'] ?? null;
                                                if ($gulaDarah === null || $gulaDarah === '' || $gulaDarah == 0) {
                                                    echo '<span class="text-muted">-</span>';
                                                } else {
                                                    $gulaDarahClass = 'success';
                                                    $gulaDarahStatus = 'Normal';
                                                    
                                                    if ($gulaDarah >= 140 && $gulaDarah <= 199) {
                                                        $gulaDarahClass = 'warning';
                                                        $gulaDarahStatus = 'Prediabetes';
                                                    } else if ($gulaDarah >= 200) {
                                                        $gulaDarahClass = 'danger';
                                                        $gulaDarahStatus = 'Diabetes';
                                                    }
                                                    echo '<div><span class="text-bold">' . $gulaDarah . '</span> <small class="text-muted">mg/dL</small></div>';
                                                    echo '<span class="badge badge-' . $gulaDarahClass . ' badge-pill mt-1">' . $gulaDarahStatus . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                // Menentukan status kolesterol
                                                $kolesterol = $data['kolesterol_total'] ?? null;
                                                if ($kolesterol === null || $kolesterol === '' || $kolesterol == 0) {
                                                    echo '<span class="text-muted">-</span>';
                                                } else {
                                                    $kolesterolClass = 'success';
                                                    $kolesterolStatus = 'Normal';
                                                    
                                                    if ($kolesterol >= 200 && $kolesterol <= 239) {
                                                        $kolesterolClass = 'warning';
                                                        $kolesterolStatus = 'Agak Tinggi';
                                                    } else if ($kolesterol >= 240) {
                                                        $kolesterolClass = 'danger';
                                                        $kolesterolStatus = 'Tinggi';
                                                    }
                                                    echo '<div><span class="text-bold">' . $kolesterol . '</span> <small class="text-muted">mg/dL</small></div>';
                                                    echo '<span class="badge badge-' . $kolesterolClass . ' badge-pill mt-1">' . $kolesterolStatus . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                // Menentukan status asam urat (opsional)
                                                $asamUrat = $data['asam_urat'] ?? null;
                                                if ($asamUrat === null || $asamUrat === '') {
                                                    echo '<span class="text-muted">-</span>';
                                                } else {
                                                    $asamUratClass = 'success';
                                                    $asamUratStatus = 'Normal';
                                                    if ($asamUrat >= 7) {
                                                        $asamUratClass = 'danger';
                                                        $asamUratStatus = 'Tinggi';
                                                    }
                                                    echo '<div><span class="text-bold">' . $asamUrat . '</span> <small class="text-muted">mg/dL</small></div>';
                                                    echo '<span class="badge badge-' . $asamUratClass . ' badge-pill mt-1">' . $asamUratStatus . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                // Menentukan status lingkar pinggang berdasarkan jenis kelamin dari NIP
                                                $lingkarPinggang = $data['lingkar_pinggang'] ?? 0;
                                                $nip = $data['nip'] ?? '';
                                                $jenisKelamin = 'L'; // Default laki-laki
                                                
                                                if (strlen($nip) >= 4) {
                                                    $digit = substr($nip, -4, 1);
                                                    $jenisKelamin = ($digit == '1') ? 'L' : 'P';
                                                }
                                                
                                                $lingkarPinggangClass = 'success';
                                                $lingkarPinggangStatus = 'Normal';
                                                
                                                if ($jenisKelamin == 'L' && $lingkarPinggang > 90) {
                                                    $lingkarPinggangClass = 'danger';
                                                    $lingkarPinggangStatus = 'Obesitas Sentral';
                                                } else if ($jenisKelamin == 'P' && $lingkarPinggang > 80) {
                                                    $lingkarPinggangClass = 'danger';
                                                    $lingkarPinggangStatus = 'Obesitas Sentral';
                                                }
                                                ?>
                                                <div>
                                                    <span class="text-bold"><?= $lingkarPinggang ?></span>
                                                    <small class="text-muted">cm</small>
                                                </div>
                                                <span class="badge badge-<?= $lingkarPinggangClass ?> badge-pill mt-1">
                                                    <?= $lingkarPinggangStatus ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-bold"><?= $data['berat_badan'] ?></span>kg /
                                                <span class="text-bold"><?= $data['tinggi_badan'] ?></span>cm
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $badgeClass = 'secondary';
                                                switch($data['kategori_imt']) {
                                                    case 'Normal': $badgeClass = 'success'; break;
                                                    case 'Overweight': $badgeClass = 'warning'; break;
                                                    case 'Obesitas': $badgeClass = 'danger'; break;
                                                    case 'Underweight': $badgeClass = 'info'; break;
                                                }
                                                ?>
                                                <div>
                                                    <span class="badge badge-secondary"><?= $data['imt'] ?></span>
                                                </div>
                                                <span class="badge badge-<?= $badgeClass ?> badge-pill mt-1">
                                                    <?= $data['kategori_imt'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="/pengukuran_kebugaran/detail/<?= $data['id_pengukuran_kebugaran'] ?>" 
                                                       class="btn btn-info btn-xs" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($isAdmin || (isset($hasAllAccess) && $hasAllAccess) || $data['id_user'] == session()->get('id_user')): ?>
                                                        <a href="/pengukuran_kebugaran/edit/<?= $data['id_pengukuran_kebugaran'] ?>" 
                                                           class="btn btn-warning btn-xs" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="hapusData(<?= $data['id_pengukuran_kebugaran'] ?>)" 
                                                                class="btn btn-danger btn-xs" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
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

        <!-- Export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="/pengukuran_kebugaran/export" method="POST">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="exportModalLabel"><i class="fas fa-file-export"></i> Export Data Pengukuran</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="text-white">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="form-group">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <div class="form-group">
                                <label for="id_tim_kerja">Tim Kerja</label>
                                <select class="form-control" id="id_tim_kerja" name="id_tim_kerja">
                                    <option value="">-- Semua Tim Kerja --</option>
                                    <?php foreach($timKerja as $tk): ?>
                                        <option value="<?= $tk['id_tim_kerja'] ?>"><?= $tk['nama_tim_kerja'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info"><i class="fas fa-download"></i> Export Excel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

<script>
$(document).ready(function() {
    $('#pengukuranTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[ 1, "desc" ]]
    });
});

function hapusData(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/pengukuran_kebugaran/hapus/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        icon: 'error'
                    });
                }
            });
        }
    });
}
</script>

<?= $this->endSection(); ?>