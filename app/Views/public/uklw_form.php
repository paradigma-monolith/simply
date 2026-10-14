<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 1000px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 8px;
            min-width: 120px;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
            flex-shrink: 0;
        }
        .step.active .step-number {
            background: #667eea;
            color: white;
        }
        .step.completed .step-number {
            background: #28a745;
            color: white;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .btn-secondary {
            border-radius: 25px;
            padding: 12px 30px;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert {
            border-radius: 10px;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .navigation-buttons {
            padding: 30px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
        }
        .header .logo-section {
            margin-bottom: 20px;
        }
        .header .logo-section img {
            margin: 0 8px;
            max-height: 60px;
            width: auto;
        }
        @media (max-width: 768px) {
            .header .logo-section img {
                max-height: 45px;
                margin: 3px;
            }
            .header h2 {
                font-size: 1.5rem;
            }
            .step {
                margin: 5px;
                min-width: 100px;
            }
            .step-number {
                width: 35px;
                height: 35px;
                margin-right: 8px;
            }
            .step span {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .step-indicator {
                padding: 0;
            }
            .step {
                margin: 4px;
                min-width: 40%;
                justify-content: center;
                flex-basis: calc(50% - 8px);
            }
            .step-number {
                width: 32px;
                height: 32px;
                margin-right: 6px;
                font-size: 0.9rem;
            }
            .step span {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 380px) {
            .step {
                flex-basis: calc(50% - 8px);
                margin: 3px;
            }
            .step-number {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
                margin-right: 5px;
            }
            .step span {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header">
                <!-- Logo Section -->
                <div class="logo-section">
                    <img src="/dist/img/LOGO_KARANTINA.png" alt="Logo Karantina Kesehatan">
                    <img src="/dist/img/logo-bkk-no-margin.png" alt="Logo BKK Bandung">
                </div>
                <h2><i class="fas fa-heartbeat"></i> Formulir Pendaftaran Kesehatan</h2>
                <p class="mb-0">Balai Kekarantinaan Kesehatan Kelas I Bandung</p>
            </div>

            <!-- Step Indicator -->
            <div class="container px-2">
                <div class="step-indicator">
                    <div class="step active" id="step-indicator-1">
                        <div class="step-number">1</div>
                        <span>Data Pasien</span>
                    </div>
                    <div class="step" id="step-indicator-2">
                        <div class="step-number">2</div>
                        <span>Skrining TBC</span>
                    </div>
                    <div class="step" id="step-indicator-3">
                        <div class="step-number">3</div>
                        <span>Skrining HIV</span>
                    </div>
                    <div class="step" id="step-indicator-4">
                        <div class="step-number">4</div>
                        <span>Faktor Risiko PTM</span>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mx-4">
                    <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mx-4">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <form id="multiStepForm" action="/public/uklw/submit" method="post">
                <!-- Step 1: Data Pasien -->
                <div class="step-content active" id="step-1">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="fas fa-user"></i> Data Pasien</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Jenis Identitas <span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" name="identitas" id="nik" value="NIK" class="form-check-input" <?= old('identitas') == 'NIK' ? 'checked' : ''; ?>>
                                            <label for="nik">NIK</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="identitas" id="paspor" value="Paspor" class="form-check-input" <?= old('identitas') == 'Paspor' ? 'checked' : ''; ?>>
                                            <label for="paspor">Paspor</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="identitas" id="no_porsi" value="No Porsi" class="form-check-input" <?= old('identitas') == 'No Porsi' ? 'checked' : ''; ?>>
                                            <label for="no_porsi">No Porsi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nomor Identitas <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_identitas" class="form-control" value="<?= old('nomor_identitas'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Sesuai Identitas <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" value="<?= old('nama'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" name="jk" id="laki" value="Laki-laki" class="form-check-input" <?= old('jk') == 'Laki-laki' ? 'checked' : ''; ?>>
                                            <label for="laki">Laki-laki</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="jk" id="perempuan" value="Perempuan" class="form-check-input" <?= old('jk') == 'Perempuan' ? 'checked' : ''; ?>>
                                            <label for="perempuan">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_lahir" class="form-control" value="<?= old('tgl_lahir'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat (Pastikan disisi dengan lengkap Desa, Kec, Kel, RT RW, Kota, Prov, Kode POS) <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="3" class="form-control" required><?= old('alamat'); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Pendidikan <span class="text-danger">*</span></label>
                                    <select name="pendidikan" class="form-control" required>
                                        <option value="">--Pilih Pendidikan--</option>
                                        <option value="Tidak Tamat" <?= old('pendidikan') == "Tidak Tamat" ? 'selected' : ''; ?>>Tidak Tamat</option>
                                        <option value="SD" <?= old('pendidikan') == "SD" ? 'selected' : ''; ?>>SD</option>
                                        <option value="SMP" <?= old('pendidikan') == "SMP" ? 'selected' : ''; ?>>SMP</option>
                                        <option value="SMA" <?= old('pendidikan') == "SMA" ? 'selected' : ''; ?>>SMA</option>
                                        <option value="D1-D3" <?= old('pendidikan') == "D1-D3" ? 'selected' : ''; ?>>D1-D3</option>
                                        <option value="S1/D4" <?= old('pendidikan') == "S1/D4" ? 'selected' : ''; ?>>S1/D4</option>
                                        <option value="S2" <?= old('pendidikan') == "S2" ? 'selected' : ''; ?>>S2</option>
                                        <option value="S3" <?= old('pendidikan') == "S3" ? 'selected' : ''; ?>>S3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Pekerjaan</label>
                                    <select name="pekerjaan" class="form-control">
                                        <option value="">--Pilih Pekerjaan--</option>
                                        <option value="ASN" <?= old('pekerjaan') == "ASN" ? 'selected' : ''; ?>>ASN</option>
                                        <option value="TNI/POLRI" <?= old('pekerjaan') == "TNI/POLRI" ? 'selected' : ''; ?>>TNI/POLRI</option>
                                        <option value="BUMN" <?= old('pekerjaan') == "BUMN" ? 'selected' : ''; ?>>BUMN</option>
                                        <option value="SWASTA" <?= old('pekerjaan') == "SWASTA" ? 'selected' : ''; ?>>SWASTA</option>
                                        <option value="WIRASWASTA" <?= old('pekerjaan') == "WIRASWASTA" ? 'selected' : ''; ?>>WIRASWASTA</option>
                                        <option value="MENGURUS RUMAH TANGGA" <?= old('pekerjaan') == "MENGURUS RUMAH TANGGA" ? 'selected' : ''; ?>>MENGURUS RUMAH TANGGA</option>
                                        <option value="TIDAK BEKERJA" <?= old('pekerjaan') == "TIDAK BEKERJA" ? 'selected' : ''; ?>>TIDAK BEKERJA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status Perkawinan</label>
                                    <select name="status" class="form-control">
                                        <option value="">--Pilih Status--</option>
                                        <option value="BELUM MENIKAH" <?= old('status') == "BELUM MENIKAH" ? 'selected' : ''; ?>>BELUM MENIKAH</option>
                                        <option value="MENIKAH" <?= old('status') == "MENIKAH" ? 'selected' : ''; ?>>MENIKAH</option>
                                        <option value="JANDA/DUDA" <?= old('status') == "JANDA/DUDA" ? 'selected' : ''; ?>>JANDA/DUDA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Keluarga</label>
                                    <input type="text" name="nama_keluarga" class="form-control" value="<?= old('nama_keluarga'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Hubungan Keluarga</label>
                                    <select name="hubungan_keluarga" class="form-control">
                                        <option value="">--Pilih Hubungan--</option>
                                        <option value="PASANGAN" <?= old('hubungan_keluarga') == "PASANGAN" ? 'selected' : ''; ?>>PASANGAN</option>
                                        <option value="AYAH/IBU" <?= old('hubungan_keluarga') == "AYAH/IBU" ? 'selected' : ''; ?>>AYAH/IBU</option>
                                        <option value="SAUDARA KANDUNG" <?= old('hubungan_keluarga') == "SAUDARA KANDUNG" ? 'selected' : ''; ?>>SAUDARA KANDUNG</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">No. HP Keluarga</label>
                                    <input type="text" name="no_hp_keluarga" class="form-control" value="<?= old('no_hp_keluarga'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Skrining TBC -->
                <div class="step-content" id="step-2">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="fas fa-lungs"></i> Skrining TBC</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="60%">Gejala TBC</th>
                                        <th width="20%" class="text-center">Ya</th>
                                        <th width="20%" class="text-center">Tidak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $gejalaTBC = [
                                        'Batuk berdahak lebih dari 2 minggu',
                                        'Demam lebih dari 1 bulan',
                                        'Berkeringat malam tanpa aktivitas',
                                        'Berat badan turun tanpa sebab yang jelas',
                                        'Nafsu makan menurun',
                                        'Mudah lelah dan merasa tidak enak badan'
                                    ];
                                    foreach ($gejalaTBC as $index => $gejala): ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="skrining_tbc_gejala[]" value="<?= $gejala; ?>">
                                            <?= $gejala; ?>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_tbc_gejala<?= $index; ?>" value="Ya" class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_tbc_gejala<?= $index; ?>" value="Tidak" class="form-check-input">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Skrining HIV -->
                <div class="step-content" id="step-3">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="fas fa-shield-virus"></i> Skrining HIV</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-warning">
                                    <tr>
                                        <th width="60%">Faktor Risiko HIV</th>
                                        <th width="20%" class="text-center">Ya</th>
                                        <th width="20%" class="text-center">Tidak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $faktorRisikoHIV = [
                                        'Pernah melakukan hubungan seks tanpa kondom',
                                        'Memiliki lebih dari 1 pasangan seksual',
                                        'Pernah menggunakan jarum suntik bergantian',
                                        'Pernah menerima transfusi darah',
                                        'Memiliki riwayat IMS (Infeksi Menular Seksual)',
                                        'Pasangan memiliki risiko tinggi HIV'
                                    ];
                                    foreach ($faktorRisikoHIV as $index => $faktor): ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="skrining_hiv[]" value="<?= $faktor; ?>">
                                            <?= $faktor; ?>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_hiv<?= $index; ?>" value="Ya" class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_hiv<?= $index; ?>" value="Tidak" class="form-check-input">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Faktor Risiko PTM -->
                <div class="step-content" id="step-4">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="fas fa-heart"></i> Faktor Risiko Penyakit Tidak Menular</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-danger">
                                    <tr>
                                        <th width="60%">Faktor Risiko PTM</th>
                                        <th width="20%" class="text-center">Ya</th>
                                        <th width="20%" class="text-center">Tidak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $faktorRisikoPTM = [
                                        'Merokok aktif',
                                        'Olahraga kurang dari 150 menit/minggu',
                                        'Tidak setiap hari konsumsi sayur dan buah',
                                        'Tidur kurang dari 7 jam/hari',
                                        'Konsumsi alkohol'
                                    ];
                                    foreach ($faktorRisikoPTM as $index => $faktor): ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="skrining_penyakit_tidak_menular[]" value="<?= $faktor; ?>">
                                            <?= $faktor; ?>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_penyakit_tidak_menular<?= $index; ?>" value="Ya" class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="hasil_skrining_penyakit_tidak_menular<?= $index; ?>" value="Tidak" class="form-check-input">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="navigation-buttons">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Sebelumnya
                    </button>
                    <div class="ms-auto">
                        <button type="button" id="nextBtn" class="btn btn-primary">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">
                            <i class="fas fa-paper-plane"></i> Kirim Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            // Hide all steps
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById(`step-${i}`).classList.remove('active');
                document.getElementById(`step-indicator-${i}`).classList.remove('active', 'completed');
            }

            // Show current step
            document.getElementById(`step-${step}`).classList.add('active');
            document.getElementById(`step-indicator-${step}`).classList.add('active');

            // Mark previous steps as completed
            for (let i = 1; i < step; i++) {
                document.getElementById(`step-indicator-${i}`).classList.add('completed');
            }

            // Update navigation buttons
            document.getElementById('prevBtn').style.display = step > 1 ? 'inline-block' : 'none';
            document.getElementById('nextBtn').style.display = step < totalSteps ? 'inline-block' : 'none';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-block' : 'none';
        }

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const requiredInputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            
            for (let input of requiredInputs) {
                if (!input.value.trim()) {
                    input.focus();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return false;
                }
            }

            // Validate radio groups for step 1
            if (currentStep === 1) {
                const identitasRadios = document.querySelectorAll('input[name="identitas"]');
                const jkRadios = document.querySelectorAll('input[name="jk"]');
                
                if (!Array.from(identitasRadios).some(radio => radio.checked)) {
                    alert('Mohon pilih jenis identitas!');
                    return false;
                }
                
                if (!Array.from(jkRadios).some(radio => radio.checked)) {
                    alert('Mohon pilih jenis kelamin!');
                    return false;
                }
            }

            return true;
        }

        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        document.getElementById('multiStepForm').addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
            }
        });

        // Initialize
        showStep(1);
    </script>
</body>
</html>
