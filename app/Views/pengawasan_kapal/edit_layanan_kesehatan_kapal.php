<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Permohonan Layanan Kesehatan Kapal</h3>
                    </div>
                    
                    <form action="/layanan_kesehatan_kapal/update/<?= $layanan['id'] ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($errors)) : ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error) : ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- Bagian I -->
                            <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">I. Informasi Kedatangan Kapal</h5>
                            
                            <div class="form-group row">
                                <label for="nama_kapal" class="col-sm-4 col-form-label">Nama Kapal</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nama_kapal" name="nama_kapal" value="<?= old('nama_kapal', $layanan['nama_kapal']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="waktu_kedatangan" class="col-sm-4 col-form-label">Waktu Kedatangan</label>
                                <div class="col-sm-8">
                                    <input type="datetime-local" class="form-control" id="waktu_kedatangan" name="waktu_kedatangan" value="<?= old('waktu_kedatangan', date('Y-m-d\TH:i', strtotime($layanan['waktu_kedatangan']))) ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pelabuhan_asal" class="col-sm-4 col-form-label">Pelabuhan Asal</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="pelabuhan_asal" name="pelabuhan_asal" value="<?= old('pelabuhan_asal', $layanan['pelabuhan_asal']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pelabuhan_tujuan" class="col-sm-4 col-form-label">Pelabuhan Tujuan</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="pelabuhan_tujuan" name="pelabuhan_tujuan" required>
                                        <option value="">-- Pilih Pelabuhan Tujuan --</option>
                                        <option value="patimban" <?= old('pelabuhan_tujuan', $layanan['pelabuhan_tujuan']) == 'patimban' ? 'selected' : '' ?>>Patimban</option>
                                        <option value="cirebon" <?= old('pelabuhan_tujuan', $layanan['pelabuhan_tujuan']) == 'cirebon' ? 'selected' : '' ?>>Cirebon</option>
                                        <option value="pelabuhan_ratu" <?= old('pelabuhan_tujuan', $layanan['pelabuhan_tujuan']) == 'pelabuhan_ratu' ? 'selected' : '' ?>>Pelabuhan Ratu</option>
                                        <option value="Indramayu" <?= old('pelabuhan_tujuan', $layanan['pelabuhan_tujuan']) == 'Indramayu' ? 'selected' : '' ?>>Indramayu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jumlah_crew" class="col-sm-4 col-form-label">Jumlah Crew</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="jumlah_crew" name="jumlah_crew" value="<?= old('jumlah_crew', $layanan['jumlah_crew']) ?>" required>
                                </div>
                            </div>

                            <!-- Bagian II -->
                            <h5 class="text-primary font-weight-bold mb-3 mt-4 border-bottom pb-2">II. Kelengkapan Dokumen Kesehatan Kapal</h5>
                            
                            <div class="form-group row align-items-center">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="dok_buku_kesehatan_kapal" name="dok_buku_kesehatan_kapal" value="1" <?= old('dok_buku_kesehatan_kapal', $layanan['dok_buku_kesehatan_kapal']) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="dok_buku_kesehatan_kapal">Buku Kesehatan Kapal</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="dok_sscec" name="dok_sscec" value="1" <?= old('dok_sscec', $layanan['dok_sscec']) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="dok_sscec">SSCEC</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Tgl Kadaluarsa</span>
                                        </div>
                                        <input type="date" class="form-control" name="dok_sscec_tgl_kadaluarsa" value="<?= old('dok_sscec_tgl_kadaluarsa', $layanan['dok_sscec_tgl_kadaluarsa']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="dok_p3k" name="dok_p3k" value="1" <?= old('dok_p3k', $layanan['dok_p3k']) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="dok_p3k">P3K</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Tgl Kadaluarsa</span>
                                        </div>
                                        <input type="date" class="form-control" name="dok_p3k_tgl_kadaluarsa" value="<?= old('dok_p3k_tgl_kadaluarsa', $layanan['dok_p3k_tgl_kadaluarsa']) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian III -->
                            <h5 class="text-primary font-weight-bold mb-3 mt-4 border-bottom pb-2">III. Permohonan Layanan Kesehatan Kapal</h5>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="layanan_pemeriksaan_kesehatan" name="layanan_pemeriksaan_kesehatan" value="1" <?= old('layanan_pemeriksaan_kesehatan', $layanan['layanan_pemeriksaan_kesehatan']) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="layanan_pemeriksaan_kesehatan">Pemeriksaan kesehatan sanitasi dan crew kapal</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="layanan_penerbitan_sscec" name="layanan_penerbitan_sscec" value="1" <?= old('layanan_penerbitan_sscec', $layanan['layanan_penerbitan_sscec']) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="layanan_penerbitan_sscec">Penerbitan SSCEC</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="layanan_penerbitan_p3k" name="layanan_penerbitan_p3k" value="1" <?= old('layanan_penerbitan_p3k', $layanan['layanan_penerbitan_p3k']) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="layanan_penerbitan_p3k">Penerbitan P3K</label>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <div class="col-sm-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="layanan_lainnya" name="layanan_lainnya" value="1" <?= old('layanan_lainnya', $layanan['layanan_lainnya']) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="layanan_lainnya">Lainnya</label>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="layanan_lainnya_sebutkan" value="<?= old('layanan_lainnya_sebutkan', $layanan['layanan_lainnya_sebutkan']) ?>" placeholder="sebutkan jenis layanan">
                                </div>
                            </div>

                            <!-- Fasilitasi Transportasi -->
                            <div class="form-group mt-4">
                                <label class="font-weight-bold d-block">Fasilitasi Transportasi Petugas Pemeriksa</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="custom-control-input" type="radio" name="fasilitasi_transportasi" id="fasilitasi_ya" value="Ya" <?= old('fasilitasi_transportasi', $layanan['fasilitasi_transportasi']) == 'Ya' ? 'checked' : '' ?> required>
                                    <label class="custom-control-label" for="fasilitasi_ya">Ya</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input class="custom-control-input" type="radio" name="fasilitasi_transportasi" id="fasilitasi_tidak" value="Tidak" <?= old('fasilitasi_transportasi', $layanan['fasilitasi_transportasi']) == 'Tidak' ? 'checked' : '' ?> required>
                                    <label class="custom-control-label" for="fasilitasi_tidak">Tidak</label>
                                </div>
                            </div>

                            <!-- Upload -->
                            <div class="form-group mt-4">
                                <label for="surat_permohonan">Surat Permohonan (Biarkan kosong jika tidak ingin mengubah file)</label>
                                <?php if($layanan['surat_permohonan']): ?>
                                    <div class="mb-2">
                                        <a href="/<?= $layanan['surat_permohonan'] ?>" target="_blank" class="badge badge-info"><i class="fas fa-file mr-1"></i> File Surat Saat Ini</a>
                                    </div>
                                <?php endif; ?>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="surat_permohonan" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="surat_permohonan">Pilih file baru...</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-right">
                            <a href="/layanan_kesehatan_kapal" class="btn btn-default mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
$(function () {
    // Custom file input
    bsCustomFileInput.init();
});
</script>
<?= $this->endSection(); ?>