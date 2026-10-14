<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header py-2">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-running text-warning"></i> Edit Pengukuran Kebugaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/pengukuran_kebugaran">Pengukuran Kebugaran</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <!-- Form Card -->
                <div class="card card-warning card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i> Form Edit Data Pengukuran Kebugaran
                        </h3>
                    </div>
                    <form action="<?= base_url('/pengukuran_kebugaran/update/' . $pengukuran['id_pengukuran_kebugaran']) ?>" method="POST" id="formPengukuran">
                        <div class="card-body py-2">
                            <!-- Tanggal dan Tekanan Darah -->
                            <div class="form-group mb-2">
                                <label class="text-warning font-weight-bold border-bottom w-100 pb-1">DATA PENGUKURAN</label>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="tanggal_pengukuran" class="mb-1">
                                            <i class="fas fa-calendar-alt text-primary"></i> Tanggal Pengukuran <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm" id="tanggal_pengukuran" name="tanggal_pengukuran" 
                                               value="<?= $pengukuran['tanggal_pengukuran'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-2 mt-2">
                                <label class="text-primary font-weight-bold border-bottom w-100 pb-1">
                                    <i class="fas fa-heartbeat"></i> TEKANAN DARAH
                                </label>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="tekanan_darah_sistolik" class="mb-1">Tekanan Darah Sistolik <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="tekanan_darah_sistolik" 
                                                   name="tekanan_darah_sistolik" placeholder="120" 
                                                   value="<?= $pengukuran['tekanan_darah_sistolik'] ?>" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="tekanan_darah_diastolik" class="mb-1">Tekanan Darah Diastolik <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="tekanan_darah_diastolik" 
                                                   name="tekanan_darah_diastolik" placeholder="80" 
                                                   value="<?= $pengukuran['tekanan_darah_diastolik'] ?>" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2 mt-2">
                                <label class="text-primary font-weight-bold border-bottom w-100 pb-1">
                                    <i class="fas fa-tint"></i> GULA DARAH, KOLESTEROL & ASAM URAT
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="gula_darah_sewaktu" class="mb-1">Gula Darah Sewaktu</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="gula_darah_sewaktu" 
                                                   name="gula_darah_sewaktu" placeholder="120" 
                                                   value="<?= $pengukuran['gula_darah_sewaktu'] ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text">mg/dL</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="kolesterol_total" class="mb-1">Kolesterol Total</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="kolesterol_total" 
                                                   name="kolesterol_total" placeholder="180" 
                                                   value="<?= $pengukuran['kolesterol_total'] ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text">mg/dL</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="asam_urat" class="mb-1">Asam Urat</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.1" class="form-control" id="asam_urat" 
                                                   name="asam_urat" placeholder="6.5"
                                                   value="<?= $pengukuran['asam_urat'] ?? '' ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text">mg/dL</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">Opsional</small>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group mb-2 mt-2">
                                <label class="text-primary font-weight-bold border-bottom w-100 pb-1">
                                    <i class="fas fa-weight"></i> ANTROPOMETRI
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label for="berat_badan" class="mb-1">Berat Badan <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="berat_badan" 
                                                   name="berat_badan" placeholder="70" step="0.1" 
                                                   value="<?= $pengukuran['berat_badan'] ?>" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label for="tinggi_badan" class="mb-1">Tinggi Badan <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="tinggi_badan" 
                                                   name="tinggi_badan" placeholder="170" 
                                                   value="<?= $pengukuran['tinggi_badan'] ?>" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label for="lingkar_pinggang" class="mb-1">Lingkar Pinggang <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="lingkar_pinggang" 
                                                   name="lingkar_pinggang" placeholder="85"
                                                   value="<?= $pengukuran['lingkar_pinggang'] ?? '' ?>" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <span id="kategori_lingkar_pinggang_display"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="imt_display" class="mb-1">IMT (Indeks Massa Tubuh)</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" id="imt_display" 
                                                   placeholder="Akan dihitung otomatis" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">kg/m²</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="kategori_imt_display" class="mb-1">Kategori IMT</label>
                                        <input type="text" class="form-control form-control-sm" id="kategori_imt_display" 
                                               placeholder="Akan ditentukan otomatis" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="<?= base_url('/pengukuran_kebugaran') ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Info Card -->
                <div class="card card-info card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Kategori Pengukuran
                        </h3>
                    </div>
                    <div class="card-body p-2">
                        <!-- Tekanan Darah -->
                        <div class="mb-2">
                            <h6 class="text-bold text-primary border-bottom pb-1"><i class="fas fa-heartbeat"></i> Tekanan Darah</h6>
                            <div class="row">
                                <div class="col-6">
                                    <span class="badge badge-success">Normal</span> &lt;120/85
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-warning">Prahipertensi</span> 120-139/85-89
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <span class="badge badge-warning">Tingkat 1</span> 140-159/90-99
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-danger">Tingkat 2</span> ≥160/100
                                </div>
                            </div>
                        </div>

                        <!-- Gula Darah -->
                        <div class="mb-2">
                            <h6 class="text-bold text-primary border-bottom pb-1"><i class="fas fa-tint"></i> Gula Darah</h6>
                            <div class="row">
                                <div class="col-6">
                                    <span class="badge badge-success">Normal</span> &lt;140 mg/dL
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-warning">Prediabetes</span> 140-199
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <span class="badge badge-danger">Diabetes</span> ≥200 mg/dL
                                </div>
                            </div>
                        </div>

                        <!-- Kolesterol -->
                        <div class="mb-2">
                            <h6 class="text-bold text-primary border-bottom pb-1"><i class="fas fa-chart-line"></i> Kolesterol</h6>
                            <div class="row">
                                <div class="col-6">
                                    <span class="badge badge-success">Normal</span> &lt;200
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-warning">Agak Tinggi</span> 200-239
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <span class="badge badge-danger">Tinggi</span> ≥240 mg/dL
                                </div>
                            </div>
                        </div>

                        <!-- IMT & Lingkar Pinggang -->
                        <div class="mb-2">
                            <h6 class="text-bold text-primary border-bottom pb-1"><i class="fas fa-weight"></i> IMT</h6>
                            <div class="row">
                                <div class="col-6">
                                    <span class="badge badge-info">Underweight</span> &lt;18.5
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-success">Normal</span> 18.5-24.9
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <span class="badge badge-warning">Overweight</span> 25-26.9
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-danger">Obesitas</span> ≥27
                                </div>
                            </div>
                        </div>

                        <!-- Lingkar Pinggang -->
                        <div class="mb-2">
                            <h6 class="text-bold text-primary border-bottom pb-1"><i class="fas fa-circle"></i> Lingkar Pinggang</h6>
                            <div class="row">
                                <div class="col-6">
                                    <span class="badge badge-info">Laki-laki</span> &gt;90 cm
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-pink">Perempuan</span> &gt;80 cm
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Jenis kelamin dari digit ke-4 dari belakang NIP
                            </small>
                        </div>

                        <div class="alert alert-info py-1 px-2 mt-2 mb-0 small">
                            <i class="fas fa-info-circle"></i> 
                            IMT dihitung otomatis berdasarkan berat dan tinggi badan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Calculate IMT when weight or height changes
    $('#berat_badan, #tinggi_badan').on('input', function() {
        calculateIMT();
    });
    
    // Calculate lingkar pinggang category when value changes
    $('#lingkar_pinggang').on('input', function() {
        calculateKategoriLingkarPinggang();
    });
    
    // Calculate IMT and lingkar pinggang on page load
    calculateIMT();
    calculateKategoriLingkarPinggang();
});

function calculateIMT() {
    const beratBadan = parseFloat($('#berat_badan').val());
    const tinggiBadan = parseFloat($('#tinggi_badan').val());
    
    if (beratBadan && tinggiBadan) {
        const tinggiBadanMeter = tinggiBadan / 100;
        const imt = beratBadan / (tinggiBadanMeter * tinggiBadanMeter);
        const imtRounded = Math.round(imt * 100) / 100;
        
        $('#imt_display').val(imtRounded);
        
        // Determine category
        let kategori = '';
        let badgeClass = '';
        
        if (imt < 18.5) {
            kategori = 'Underweight';
            badgeClass = 'info';
        } else if (imt >= 18.5 && imt < 25) {
            kategori = 'Normal';
            badgeClass = 'success';
        } else if (imt >= 25 && imt < 30) {
            kategori = 'Overweight';
            badgeClass = 'warning';
        } else {
            kategori = 'Obesitas';
            badgeClass = 'danger';
        }
        
        $('#kategori_imt_display').val(kategori);
        $('#kategori_imt_display').removeClass('bg-info bg-success bg-warning bg-danger')
                                  .addClass('bg-' + badgeClass + ' text-white');
    } else {
        $('#imt_display').val('');
        $('#kategori_imt_display').val('').removeClass('bg-info bg-success bg-warning bg-danger');
    }
}

function calculateKategoriLingkarPinggang() {
    const lingkarPinggang = parseFloat($('#lingkar_pinggang').val());
    
    if (lingkarPinggang) {
        // Get NIP from current user data to determine gender
        <?php 
        $userModel = new \App\Models\Master\UserModel();
        $dataPegawaiModel = new \App\Models\Master\DataPegawaiModel();
        $currentUser = $userModel->where('username', session()->get('username'))->first();
        $pegawai = null;
        $jenisKelamin = 'L';
        if ($currentUser) {
            $pegawai = $dataPegawaiModel->where('id_user', $currentUser['id_user'])->first();
            if ($pegawai && !empty($pegawai['nip'])) {
                $nip = $pegawai['nip'];
                if (strlen($nip) >= 4) {
                    $digit = substr($nip, -4, 1);
                    $jenisKelamin = ($digit == '1') ? 'L' : 'P';
                }
            }
        }
        ?>
        
        const jenisKelamin = '<?= $jenisKelamin ?>';
        let kategori = '';
        let badgeClass = '';
        
        if (jenisKelamin === 'L') {
            // Laki-laki: > 90 cm = Obesitas Sentral
            if (lingkarPinggang > 90) {
                kategori = 'Obesitas Sentral';
                badgeClass = 'danger';
            } else {
                kategori = 'Normal';
                badgeClass = 'success';
            }
        } else {
            // Perempuan: > 80 cm = Obesitas Sentral
            if (lingkarPinggang > 80) {
                kategori = 'Obesitas Sentral';
                badgeClass = 'danger';
            } else {
                kategori = 'Normal';
                badgeClass = 'success';
            }
        }
        
        $('#kategori_lingkar_pinggang_display').html(
            '<span class="badge badge-' + badgeClass + '">' + kategori + '</span>'
        );
    } else {
        $('#kategori_lingkar_pinggang_display').html('');
    }
}

// Form validation
$('#formPengukuran').on('submit', function(e) {
    let isValid = true;
    
    // Check required fields
    $(this).find('input[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        Swal.fire({
            title: 'Form Tidak Lengkap',
            text: 'Mohon lengkapi semua field yang wajib diisi',
            icon: 'warning'
        });
    }
});
</script>

<?= $this->endSection(); ?>
