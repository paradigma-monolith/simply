<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengajuan Perlu Verifikasi</h3>
            </div>
            <div class="card-body">
                <?php if(empty($pengajuan)): ?>
                    <div class="alert alert-info">
                        Tidak ada pengajuan yang perlu diverifikasi saat ini.
                    </div>
                <?php else: ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pemohon</th>
                                <th>Unit Kerja</th>
                                <th>Kendaraan</th>
                                <th>Keluhan</th>
                                <th>Estimasi Biaya</th>
                                <th>Peran Anda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($pengajuan as $p): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $p['tgl_pengajuan']; ?></td>
                                <td><?= $p['pemohon']; ?></td>
                                <td><?= $p['unit_kerja'] ?? '-'; ?></td>
                                <td>
                                    <?php if($p['nama_kendaraan']): ?>
                                        <?= $p['nama_kendaraan']; ?> (<?= $p['plat_nomor']; ?>)
                                    <?php else: ?>
                                        <span class="text-danger font-italic">Kendaraan Terhapus</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $p['keluhan']; ?></td>
                                <td>Rp <?= number_format($p['estimasi_biaya'], 2, ',', '.'); ?></td>
                                <td><span class="badge badge-info"><?= $p['role_verifikasi']; ?></span></td>
                                <td>
                                    <a href="/abs/rawat_kendaraan/detail/<?= $p['id_rawat']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-check-double"></i> Verifikasi
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
