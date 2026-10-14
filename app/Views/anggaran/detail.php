<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Detail Anggaran</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">No Kegiatan</th>
                                    <td><?= $anggaran['no_kegiatan'] ?></td>
                                </tr>
                                <tr>
                                    <th>Akun</th>
                                    <td>
                                        <span class="badge badge-<?= $anggaran['akun'] == '51' ? 'info' : 'warning' ?>">
                                            Akun <?= $anggaran['akun'] ?? '-' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kegiatan</th>
                                    <td><?= nl2br(htmlspecialchars($anggaran['kegiatan'])) ?></td>
                                </tr>
                                <tr>
                                    <th>PIC (Person In Charge)</th>
                                    <td>
                                        <span class="badge badge-primary"><?= $anggaran['pic'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rencana Pencairan Anggaran</th>
                                    <td>
                                        <strong class="text-success">Rp <?= number_format($anggaran['rencana_pencairan_anggaran'], 0, ',', '.') ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Realisasi</th>
                                    <td>
                                        <?php if ($anggaran['realisasi']): ?>
                                            <strong class="text-info">Rp <?= number_format($anggaran['realisasi'], 0, ',', '.') ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada realisasi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deviasi</th>
                                    <td>
                                        <?php if (!empty($anggaran['deviasi'])): ?>
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <?= nl2br(htmlspecialchars($anggaran['deviasi'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge badge-success">Tidak ada deviasi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tahun Anggaran</th>
                                    <td><?= $anggaran['tahun_anggaran'] ?></td>
                                </tr>
                                <tr>
                                    <th>Bulan</th>
                                    <td>
                                        <?php 
                                            $namaBulan = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                            echo $namaBulan[$anggaran['bulan']] ?? 'Tidak diketahui';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td><?= date('d/m/Y H:i:s', strtotime($anggaran['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td><?= date('d/m/Y H:i:s', strtotime($anggaran['updated_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="/anggaran/edit/<?= $anggaran['id_anggaran'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>
                            <a href="/anggaran/data" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Data
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $anggaran['id_anggaran'] ?>)">
                                <i class="fas fa-trash"></i> Hapus Data
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Progress Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Progress Realisasi</h3>
                        </div>
                        <div class="card-body">
                            <?php 
                                $progress = $anggaran['rencana_pencairan_anggaran'] > 0 ? 
                                    round(($anggaran['realisasi'] / $anggaran['rencana_pencairan_anggaran']) * 100, 2) : 0;
                                $progressColor = $progress >= 80 ? 'success' : ($progress >= 50 ? 'warning' : 'danger');
                            ?>
                            <div class="progress progress-lg mb-3">
                                <div class="progress-bar bg-<?= $progressColor ?>" 
                                     style="width: <?= $progress ?>%"></div>
                            </div>
                            <p class="text-center">
                                <span class="badge badge-<?= $progressColor ?> badge-lg">
                                    <?= $progress ?>% Terealisasi
                                </span>
                            </p>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-12">
                                    <h6>Sisa Anggaran:</h6>
                                    <?php $sisa = $anggaran['rencana_pencairan_anggaran'] - ($anggaran['realisasi'] ?? 0); ?>
                                    <h4 class="text-<?= $sisa >= 0 ? 'success' : 'danger' ?>">
                                        Rp <?= number_format($sisa, 0, ',', '.') ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Card -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Status Anggaran</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($progress == 0): ?>
                                <div class="alert alert-secondary">
                                    <i class="fas fa-clock"></i> Belum Direalisasi
                                </div>
                            <?php elseif ($progress < 50): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Realisasi Rendah
                                </div>
                            <?php elseif ($progress < 80): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i> Realisasi Sedang
                                </div>
                            <?php elseif ($progress <= 100): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Realisasi Baik
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Over Budget
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($anggaran['deviasi'])): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Ada Deviasi
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data anggaran ini?</p>
                <div class="alert alert-warning">
                    <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan!
                </div>
                <p><strong>No Kegiatan:</strong> <?= $anggaran['no_kegiatan'] ?></p>
                <p><strong>Kegiatan:</strong> <?= substr($anggaran['kegiatan'], 0, 100) ?>...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteForm').action = '/anggaran/delete/' + id;
    $('#deleteModal').modal('show');
}
</script>
<?= $this->endSection() ?>