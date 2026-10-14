<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Pengajuan Pemeliharaan</h3>
            </div>
            <form action="/abs/rawat_kendaraan/insert" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Kendaraan</label>
                        <select name="id_kendaraan" class="form-control select2" required>
                            <option value="">-- Pilih Kendaraan --</option>
                            <?php foreach($kendaraan as $k): ?>
                                <option value="<?= $k['id_kendaraan_kantor_daftar']; ?>"><?= $k['nama_kendaraan']; ?> - <?= $k['plat_nomor']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pengajuan</label>
                        <input type="date" name="tgl_pengajuan" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Keluhan / Jenis Pemeliharaan</label>
                        <textarea name="keluhan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Estimasi Biaya (Rp)</label>
                        <input type="number" name="estimasi_biaya" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Bukti Foto (Wajib menggunakan Geotagging Timemark)</label>
                        <div class="custom-file">
                            <input type="file" name="bukti_foto" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Pilih file</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    <a href="/abs/rawat_kendaraan" class="btn btn-default">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>
