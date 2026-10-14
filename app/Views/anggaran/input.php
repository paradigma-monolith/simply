<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<section class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Input Data Anggaran</h3>
                        </div>
                        <form action="/anggaran/store" method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="no_kegiatan">No Kegiatan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="no_kegiatan" name="no_kegiatan" 
                                                   value="<?= old('no_kegiatan') ?>" placeholder="Contoh: 4249.QAK.012" required>
                                            <small class="form-text text-muted">Format: XXXX.XXX.XXX</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="akun">Akun <span class="text-danger">*</span></label>
                                            <select class="form-control" id="akun" name="akun" required>
                                                <option value="">Pilih Akun</option>
                                                <option value="51" <?= old('akun') == '51' ? 'selected' : '' ?>>Akun 51</option>
                                                <option value="52" <?= old('akun') == '52' ? 'selected' : '' ?>>Akun 52</option>
                                                <option value="53" <?= old('akun') == '53' ? 'selected' : '' ?>>Akun 53</option>
                                            </select>
                                            <small class="form-text text-muted">Pilih akun 51, 52, atau 53</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pic">PIC (Person In Charge) <span class="text-danger">*</span></label>
                                            <select class="form-control" id="pic" name="pic" required>
                                                <option value="">Pilih PIC</option>
                                                <option value="Timker 1" <?= old('pic') == 'Timker 1' ? 'selected' : '' ?>>Timker 1</option>
                                                <option value="Timker 2" <?= old('pic') == 'Timker 2' ? 'selected' : '' ?>>Timker 2</option>
                                                <option value="Timker 3" <?= old('pic') == 'Timker 3' ? 'selected' : '' ?>>Timker 3</option>
                                                <option value="Timker 4" <?= old('pic') == 'Timker 4' ? 'selected' : '' ?>>Timker 4</option>
                                                <option value="Timker 5" <?= old('pic') == 'Timker 5' ? 'selected' : '' ?>>Timker 5</option>
                                                <option value="ADUM" <?= old('pic') == 'ADUM' ? 'selected' : '' ?>>ADUM</option>
                                                <option value="Gabungan" <?= old('pic') == 'Gabungan' ? 'selected' : '' ?>>Gabungan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kegiatan">Kegiatan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" 
                                              placeholder="Masukkan deskripsi kegiatan" required><?= old('kegiatan') ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rencana_pencairan_anggaran">Rencana Pencairan Anggaran <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="rencana_pencairan_anggaran" 
                                                       name="rencana_pencairan_anggaran" value="<?= old('rencana_pencairan_anggaran') ?>" 
                                                       placeholder="0" required>
                                            </div>
                                            <small class="form-text text-muted">Masukkan angka (akan diformat otomatis)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="realisasi">Realisasi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="realisasi" name="realisasi" 
                                                       value="<?= old('realisasi') ?>" placeholder="0">
                                            </div>
                                            <small class="form-text text-muted">Opsional - minimal 0 (akan diformat otomatis)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tahun_anggaran">Tahun Anggaran <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tahun_anggaran" name="tahun_anggaran" required>
                                                <?php for ($year = date('Y') + 1; $year >= 2020; $year--): ?>
                                                    <option value="<?= $year ?>" <?= (old('tahun_anggaran') ?: date('Y')) == $year ? 'selected' : '' ?>>
                                                        <?= $year ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bulan">Bulan <span class="text-danger">*</span></label>
                                            <select class="form-control" id="bulan" name="bulan" required>
                                                <option value="">Pilih Bulan</option>
                                                <option value="1" <?= old('bulan') == '1' ? 'selected' : '' ?>>Januari</option>
                                                <option value="2" <?= old('bulan') == '2' ? 'selected' : '' ?>>Februari</option>
                                                <option value="3" <?= old('bulan') == '3' ? 'selected' : '' ?>>Maret</option>
                                                <option value="4" <?= old('bulan') == '4' ? 'selected' : '' ?>>April</option>
                                                <option value="5" <?= old('bulan') == '5' ? 'selected' : '' ?>>Mei</option>
                                                <option value="6" <?= old('bulan') == '6' ? 'selected' : '' ?>>Juni</option>
                                                <option value="7" <?= old('bulan') == '7' ? 'selected' : '' ?>>Juli</option>
                                                <option value="8" <?= old('bulan') == '8' ? 'selected' : '' ?>>Agustus</option>
                                                <option value="9" <?= old('bulan') == '9' ? 'selected' : '' ?>>September</option>
                                                <option value="10" <?= old('bulan') == '10' ? 'selected' : '' ?>>Oktober</option>
                                                <option value="11" <?= old('bulan') == '11' ? 'selected' : '' ?>>November</option>
                                                <option value="12" <?= old('bulan') == '12' ? 'selected' : '' ?>>Desember</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Field deviasi dihapus karena akan dihitung otomatis oleh sistem -->
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Data
                                </button>
                                <a href="/anggaran/data" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>

<script>
$(document).ready(function() {
    // Format input angka dengan pemisah ribuan dan validasi
    $('#rencana_pencairan_anggaran, #realisasi').on('input', function() {
        // Hapus semua karakter non-digit dan hilangkan angka desimal
        let value = this.value.replace(/[^\d]/g, '');
        if (value && !isNaN(value)) {
            // Limit to reasonable budget amounts (max 999 billion)
            if (parseInt(value) > 999999999999) {
                value = '999999999999';
            }
            this.value = formatNumber(value);
        } else {
            this.value = '';
        }
    });
    
    // Real-time validation
    $('#tahun_anggaran').on('change', function() {
        const year = parseInt(this.value);
        if (year && (year < 2000 || year > 2099)) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Tahun harus antara 2000-2099</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });
    
    // Form validation before submission
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Validate required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate currency fields
        // Hapus semua karakter non-digit dan pastikan tidak ada angka desimal
        const rencanaValue = $('#rencana_pencairan_anggaran').val().replace(/[^\d]/g, '');
        const realisasiValue = $('#realisasi').val().replace(/[^\d]/g, '') || '0';
        
        if (!rencanaValue || isNaN(rencanaValue) || parseInt(rencanaValue) <= 0) {
            $('#rencana_pencairan_anggaran').addClass('is-invalid');
            alert('Rencana pencairan anggaran harus diisi dengan nilai yang valid');
            isValid = false;
        }
        
        if (isNaN(realisasiValue) || parseInt(realisasiValue) < 0) {
            $('#realisasi').addClass('is-invalid');
            alert('Realisasi harus berupa angka yang valid (minimal 0)');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Remove formatting before submission
        $('#rencana_pencairan_anggaran').val(rencanaValue);
        $('#realisasi').val(realisasiValue);
    });
});

function formatNumber(num) {
    // Pastikan tidak ada angka desimal (2 angka di belakang koma)
    return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>
<?= $this->endSection() ?>