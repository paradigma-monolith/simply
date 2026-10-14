<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pengajuan</h3>
                    </div>
                    <div class="card-body">
                        <dl>
                            <dt>Kendaraan</dt>
                            <dd>
                                <?php if($detail['nama_kendaraan']): ?>
                                    <?= $detail['nama_kendaraan']; ?> (<?= $detail['plat_nomor']; ?>)
                                <?php else: ?>
                                    <span class="text-danger font-italic">Kendaraan Terhapus</span>
                                <?php endif; ?>
                            </dd>
                            <dt>Tanggal Pengajuan</dt>
                            <dd><?= $detail['tgl_pengajuan']; ?></dd>
                            <dt>Pemohon</dt>
                            <dd><?= $detail['pemohon']; ?></dd>
                            <dt>Unit Kerja</dt>
                            <dd><?= $detail['unit_kerja'] ?? '-'; ?></dd>
                            <dt>Keluhan</dt>
                            <dd><?= $detail['keluhan']; ?></dd>
                            <dt>Estimasi Biaya</dt>
                            <dd>Rp <?= number_format($detail['estimasi_biaya'], 2, ',', '.'); ?></dd>
                            <dt>Status</dt>
                            <dd><?= strtoupper($detail['status']); ?></dd>
                            <?php if($detail['bukti_foto']): ?>
                                <dt>Bukti Foto</dt>
                                <dd><img src="/assets/abs/rawat_kendaraan/<?= $detail['bukti_foto']; ?>" class="img-fluid" style="max-height: 200px;"></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Timeline Verifikasi -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Verifikasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-inverse">
                            <!-- Verifikasi 1 -->
                            <div>
                                <i class="fas fa-user bg-primary"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Verifikator 1</h3>
                                    <div class="timeline-body">
                                        <?php if($detail['verifikator_1_id']): ?>
                                            Status: <b><?= ($detail['status'] == 'ditolak' && !$detail['verifikator_2_id']) ? 'Ditolak' : 'Disetujui'; ?></b><br>
                                            Tanggal: <?= $detail['verifikator_1_tgl']; ?><br>
                                            Catatan: <?= $detail['verifikator_1_catatan']; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Menunggu verifikasi...</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($is_verifikator_1 && $detail['status'] == 'pending'): ?>
                                        <div class="timeline-footer">
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-verif-1-setuju">Setuju</button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-verif-1-tolak">Tolak</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Verifikasi 2 -->
                            <div>
                                <i class="fas fa-user-tie bg-success"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Verifikator 2</h3>
                                    <div class="timeline-body">
                                        <?php if($detail['verifikator_2_id']): ?>
                                            Status: <b><?= ($detail['status'] == 'ditolak') ? 'Ditolak' : 'Disetujui'; ?></b><br>
                                            Tanggal: <?= $detail['verifikator_2_tgl']; ?><br>
                                            Catatan: <?= $detail['verifikator_2_catatan']; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Menunggu verifikasi...</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($is_verifikator_2 && $detail['status'] == 'verifikasi_2'): ?>
                                        <div class="timeline-footer">
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-verif-2-setuju">Setuju</button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-verif-2-tolak">Tolak</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals for Verification -->
<?php foreach(['1', '2'] as $v): ?>
    <!-- Setuju -->
    <div class="modal fade" id="modal-verif-<?= $v; ?>-setuju">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/abs/rawat_kendaraan/verifikasi" method="post">
                    <input type="hidden" name="id_rawat" value="<?= $detail['id_rawat']; ?>">
                    <input type="hidden" name="role" value="verifikator_<?= $v; ?>">
                    <input type="hidden" name="aksi" value="setuju">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title">Setujui Pengajuan (Verifikator <?= $v; ?>)</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tolak -->
    <div class="modal fade" id="modal-verif-<?= $v; ?>-tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/abs/rawat_kendaraan/verifikasi" method="post">
                    <input type="hidden" name="id_rawat" value="<?= $detail['id_rawat']; ?>">
                    <input type="hidden" name="role" value="verifikator_<?= $v; ?>">
                    <input type="hidden" name="aksi" value="tolak">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">Tolak Pengajuan (Verifikator <?= $v; ?>)</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan Penolakan</label>
                            <textarea name="catatan" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection(); ?>
