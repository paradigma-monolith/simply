<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header py-2">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-file-excel text-success"></i> Import Data Pengukuran Kebugaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/pengukuran_kebugaran">Pengukuran Kebugaran</a></li>
                    <li class="breadcrumb-item active">Import Excel</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <!-- Alert Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Terdapat kesalahan pada data:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Import -->
            <div class="col-md-8">
                <div class="card card-success card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-upload"></i> Upload File Excel
                        </h3>
                    </div>
                    <form action="<?= base_url('/pengukuran_kebugaran/process_import') ?>" method="POST" enctype="multipart/form-data" id="importForm">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="excel_file">
                                    <i class="fas fa-file-excel text-success"></i> Pilih File Excel <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="excel_file" name="excel_file" 
                                               accept=".xlsx,.xls,.csv" required>
                                        <label class="custom-file-label" for="excel_file">Pilih file...</label>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Format yang didukung: .xlsx, .xls, .csv (Maksimal 5MB)
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pengukuran">
                                    <i class="fas fa-calendar-alt text-primary"></i> Tanggal Pengukuran Default
                                </label>
                                <input type="date" class="form-control" id="tanggal_pengukuran" name="tanggal_pengukuran" 
                                       value="<?= date('Y-m-d') ?>">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Tanggal ini akan digunakan jika kolom tanggal di Excel kosong
                                </small>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
                                <label class="form-check-label" for="update_existing">
                                    <i class="fas fa-sync-alt text-warning"></i> Update data yang sudah ada
                                </label>
                                <small class="form-text text-muted">
                                    Jika dicentang, data yang sudah ada akan diperbarui berdasarkan NIP dan tanggal
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success" id="btnImport">
                                <i class="fas fa-upload"></i> Import Data
                            </button>
                            <a href="<?= base_url('/pengukuran_kebugaran') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info & Template -->
            <div class="col-md-4">
                <!-- Template Download -->
                <div class="card card-info card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-download"></i> Template Excel
                        </h3>
                    </div>
                    <div class="card-body p-2">
                        <p class="text-sm">
                            Download template Excel untuk memudahkan pengisian data:
                        </p>
                        <div class="text-center">
                            <a href="<?= base_url('/pengukuran_kebugaran/download_template') ?>" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                        <hr class="my-2">
                        <h6 class="text-bold text-primary">Format Kolom Excel:</h6>
                        <ol class="text-sm mb-0">
                            <li><strong>NIP</strong> - NIP Pegawai (18 digit)</li>
                            <li><strong>Nama</strong> - Nama Pegawai</li>
                            <li><strong>Tanggal</strong> - Format: dd/mm/yyyy</li>
                            <li><strong>Berat Badan (kg)</strong> - Contoh: 70.5</li>
                            <li><strong>Tinggi Badan (cm)</strong> - Contoh: 170.5</li>
                            <li><strong>Lingkar Pinggang (cm)</strong> - Contoh: 85</li>
                            <li><strong>Tekanan Darah Sistolik (mmHg)</strong> - Contoh: 120</li>
                            <li><strong>Tekanan Darah Diastolik (mmHg)</strong> - Contoh: 80</li>
                            <li><strong>Gula Darah (mg/dL)</strong> - Opsional, Contoh: 95</li>
                            <li><strong>Kolesterol (mg/dL)</strong> - Opsional, Contoh: 180</li>
                            <li><strong>Asam Urat (mg/dL)</strong> - Opsional, Contoh: 6.2</li>
                        </ol>
                    </div>
                </div>

                <!-- Panduan Import -->
                <div class="card card-warning card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i> Panduan Import
                        </h3>
                    </div>
                    <div class="card-body p-2">
                        <h6 class="text-bold text-warning">⚠️ Penting:</h6>
                        <ul class="text-sm">
                            <li>Pastikan <strong>NIP pegawai</strong> sudah terdaftar di sistem</li>
                            <li>Pastikan kolom <strong>NIP pegawai</strong> menggunakan format Text</li>
                            <li>Format tanggal harus <strong>dd/mm/yyyy</strong> atau kosong</li>
                            <li>Semua data kesehatan harus berupa <strong>angka</strong></li>
                            <li>Baris pertama harus berisi <strong>header kolom</strong></li>
                            <li>Data akan dihitung otomatis: <strong>IMT</strong> dan <strong>kategori kesehatan</strong></li>
                        </ul>
                        
                        <h6 class="text-bold text-primary mt-2">✅ Tips:</h6>
                        <ul class="text-sm mb-0">
                            <li>Gunakan template yang disediakan</li>
                            <li>Periksa data sebelum upload</li>
                            <li>Backup data sebelum import</li>
                            <li>Import bertahap jika data sangat banyak</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Form validation
    $('#importForm').on('submit', function(e) {
        const fileInput = $('#excel_file')[0];
        const file = fileInput.files[0];
        
        if (!file) {
            e.preventDefault();
            Swal.fire('Error', 'Silakan pilih file Excel terlebih dahulu', 'error');
            return;
        }
        
        // Check file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
            e.preventDefault();
            Swal.fire('Error', 'Ukuran file terlalu besar. Maksimal 5MB', 'error');
            return;
        }
        
        // Check file type
        const allowedTypes = ['.xlsx', '.xls', '.csv'];
        const fileName = file.name.toLowerCase();
        const isValidType = allowedTypes.some(type => fileName.endsWith(type));
        
        if (!isValidType) {
            e.preventDefault();
            Swal.fire('Error', 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv', 'error');
            return;
        }
        
        // Show loading
        $('#btnImport').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
        
        // Show confirmation
        Swal.fire({
            title: 'Konfirmasi Import',
            text: 'Apakah Anda yakin ingin mengimpor data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Import!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) {
                e.preventDefault();
                $('#btnImport').html('<i class="fas fa-upload"></i> Import Data').prop('disabled', false);
            }
        });
    });
});
</script>

<?= $this->endSection(); ?>