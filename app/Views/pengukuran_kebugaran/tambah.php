<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header py-2">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-running text-success"></i> Tambah Pengukuran Kebugaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/pengukuran_kebugaran">Pengukuran Kebugaran</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
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
                <div class="card card-success card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i> Form Input Data Pengukuran Kebugaran
                        </h3>
                    </div>
                    <form action="<?= base_url('/pengukuran_kebugaran/simpan') ?>" method="POST" id="formPengukuran">
                        <?= csrf_field() ?>
                        <div class="card-body py-2">
                            <!-- Tanggal dan Tekanan Darah -->
                            <div class="form-group mb-2">
                                <label class="text-success font-weight-bold border-bottom w-100 pb-1">DATA PENGUKURAN</label>
                            </div>
                            
                            <div class="row">
                                <?php if(isset($isAdmin) && $isAdmin): ?>
                                <!-- Admin: Pilih Pegawai -->
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="id_user" class="mb-1">
                                            <i class="fas fa-user text-info"></i> Pilih Pegawai <span class="text-danger">*</span>
                                        </label>
                                        <div class="dropdown-search-container">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="pegawai_search" 
                                                       placeholder="Ketik nama atau NIP pegawai..." autocomplete="off">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                                            id="pegawai_dropdown_btn" data-toggle="dropdown">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="dropdown-menu w-100" id="pegawai_dropdown" style="max-height: 200px; overflow-y: auto;">
                                                <?php if(isset($pegawaiList) && !empty($pegawaiList)): ?>
                                                    <?php foreach($pegawaiList as $pegawai): ?>
                                                        <a class="dropdown-item pegawai-option" href="#" 
                                                           data-id="<?= $pegawai['id_user'] ?>" 
                                                           data-nip="<?= $pegawai['nip'] ?? '' ?>"
                                                           data-nama="<?= $pegawai['nama'] ?>">
                                                            <i class="fas fa-user text-primary mr-2"></i>
                                                            <?= $pegawai['nama'] ?> 
                                                            <?= !empty($pegawai['nip']) ? '<span class="text-muted">(' . $pegawai['nip'] . ')</span>' : '' ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="dropdown-item-text text-muted">Tidak ada data pegawai</span>
                                                <?php endif; ?>
                                            </div>
                                            <input type="hidden" id="id_user" name="id_user" required>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            Pilih pegawai yang akan dibuatkan data pengukuran kebugaran
                                        </small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="<?= (isset($isAdmin) && $isAdmin) ? 'col-md-6' : 'col-md-6' ?>">
                                    <div class="form-group mb-2">
                                        <label for="tanggal_pengukuran" class="mb-1">
                                            <i class="fas fa-calendar-alt text-primary"></i> Tanggal Pengukuran <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm" id="tanggal_pengukuran" name="tanggal_pengukuran" 
                                               value="<?= date('Y-m-d') ?>" required>
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
                                                   name="tekanan_darah_sistolik" placeholder="120" required>
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
                                                   name="tekanan_darah_diastolik" placeholder="80" required>
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
                                                   name="gula_darah_sewaktu" placeholder="120">
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
                                                   name="kolesterol_total" placeholder="180">
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
                                                   name="asam_urat" placeholder="6.5">
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
                                                   name="berat_badan" placeholder="70.50" step="0.01" required>
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
                                                   name="tinggi_badan" placeholder="170.50" step="0.01" required>
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
                                                   name="lingkar_pinggang" placeholder="85" required>
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
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan
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

<style>
.dropdown-search-container {
    position: relative;
}

.dropdown-search-container .dropdown-menu {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    z-index: 1000;
}

.dropdown-search-container .dropdown-item {
    padding: 0.5rem 1rem;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.dropdown-search-container .dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-search-container .dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-search-container .dropdown-item.active {
    background-color: #007bff;
    color: white;
}

.dropdown-search-container .dropdown-item-text {
    padding: 0.5rem 1rem;
    color: #6c757d;
}

.dropdown-search-container .input-group-append .btn {
    border-left: none;
}

.dropdown-search-container #pegawai_search {
    border-right: none;
}

.dropdown-search-container #pegawai_search:focus {
    box-shadow: none;
    border-color: #ced4da;
}
</style>

<script>
$(document).ready(function() {
    // Pegawai dropdown search functionality
    <?php if(isset($isAdmin) && $isAdmin): ?>
    initPegawaiDropdown();
    <?php endif; ?>
    
    // Calculate IMT when weight or height changes
    $('#berat_badan, #tinggi_badan').on('input', function() {
        calculateIMT();
    });
    
    // Calculate lingkar pinggang category when value changes
    $('#lingkar_pinggang').on('input', function() {
        calculateKategoriLingkarPinggang();
    });
});

<?php if(isset($isAdmin) && $isAdmin): ?>
function initPegawaiDropdown() {
    const searchInput = document.getElementById('pegawai_search');
    const dropdownMenu = document.getElementById('pegawai_dropdown');
    const hiddenInput = document.getElementById('id_user');
    const dropdownBtn = document.getElementById('pegawai_dropdown_btn');
    
    let allOptions = [];
    let filteredOptions = [];
    let selectedIndex = -1;
    
    // Store all options
    document.querySelectorAll('.pegawai-option').forEach(option => {
        allOptions.push({
            id: option.dataset.id,
            nip: option.dataset.nip,
            nama: option.dataset.nama,
            element: option,
            searchText: (option.dataset.nama + ' ' + option.dataset.nip).toLowerCase()
        });
    });
    
    filteredOptions = [...allOptions];
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterOptions(searchTerm);
        selectedIndex = -1;
        updateDropdownDisplay();
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, filteredOptions.length - 1);
            updateDropdownDisplay();
            scrollToSelected();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateDropdownDisplay();
            scrollToSelected();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && filteredOptions[selectedIndex]) {
                selectOption(filteredOptions[selectedIndex]);
            }
        } else if (e.key === 'Escape') {
            dropdownMenu.classList.remove('show');
        }
    });
    
    // Show dropdown on focus
    searchInput.addEventListener('focus', function() {
        dropdownMenu.classList.add('show');
        updateDropdownDisplay();
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-search-container')) {
            dropdownMenu.classList.remove('show');
        }
    });
    
    // Dropdown button click
    dropdownBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
        if (dropdownMenu.classList.contains('show')) {
            searchInput.focus();
        }
    });
    
    // Option click
    dropdownMenu.addEventListener('click', function(e) {
        const option = e.target.closest('.pegawai-option');
        if (option) {
            e.preventDefault();
            const optionData = allOptions.find(opt => opt.element === option);
            if (optionData) {
                selectOption(optionData);
            }
        }
    });
    
    function filterOptions(searchTerm) {
        if (!searchTerm) {
            filteredOptions = [...allOptions];
        } else {
            filteredOptions = allOptions.filter(option => 
                option.searchText.includes(searchTerm)
            );
        }
    }
    
    function updateDropdownDisplay() {
        // Hide all options first
        allOptions.forEach(option => {
            option.element.style.display = 'none';
            option.element.classList.remove('active');
        });
        
        // Show filtered options
        filteredOptions.forEach((option, index) => {
            option.element.style.display = 'block';
            if (index === selectedIndex) {
                option.element.classList.add('active');
            }
        });
        
        // Show "no results" message if needed
        if (filteredOptions.length === 0) {
            let noResultsItem = dropdownMenu.querySelector('.no-results');
            if (!noResultsItem) {
                noResultsItem = document.createElement('span');
                noResultsItem.className = 'dropdown-item-text text-muted no-results';
                noResultsItem.innerHTML = '<i class="fas fa-search mr-2"></i>Tidak ada hasil yang ditemukan';
                dropdownMenu.appendChild(noResultsItem);
            }
            noResultsItem.style.display = 'block';
        } else {
            const noResultsItem = dropdownMenu.querySelector('.no-results');
            if (noResultsItem) {
                noResultsItem.style.display = 'none';
            }
        }
    }
    
    function scrollToSelected() {
        if (selectedIndex >= 0 && filteredOptions[selectedIndex]) {
            const selectedElement = filteredOptions[selectedIndex].element;
            const dropdownRect = dropdownMenu.getBoundingClientRect();
            const elementRect = selectedElement.getBoundingClientRect();
            
            if (elementRect.bottom > dropdownRect.bottom) {
                dropdownMenu.scrollTop += elementRect.bottom - dropdownRect.bottom;
            } else if (elementRect.top < dropdownRect.top) {
                dropdownMenu.scrollTop -= dropdownRect.top - elementRect.top;
            }
        }
    }
    
    function selectOption(option) {
        searchInput.value = option.nama + (option.nip ? ' (' + option.nip + ')' : '');
        hiddenInput.value = option.id;
        dropdownMenu.classList.remove('show');
        
        // Remove validation error if exists
        searchInput.classList.remove('is-invalid');
        hiddenInput.classList.remove('is-invalid');
        
        // Trigger change event for other calculations
        calculateKategoriLingkarPinggang();
    }
}
<?php endif; ?>

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
        let jenisKelamin = 'L'; // Default laki-laki
        
        // Check if admin is selecting a different user
        <?php if(isset($isAdmin) && $isAdmin): ?>
        const hiddenInput = document.getElementById('id_user');
        if (hiddenInput && hiddenInput.value) {
            const selectedOption = allOptions?.find(opt => opt.id === hiddenInput.value);
            if (selectedOption && selectedOption.nip && selectedOption.nip.length >= 4) {
                const digit = selectedOption.nip.toString().slice(-4, -3);
                jenisKelamin = (digit === '1') ? 'L' : 'P';
            }
        }
        <?php else: ?>
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
        jenisKelamin = '<?= $jenisKelamin ?>';
        <?php endif; ?>
        
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
    $(this).find('input[required], select[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    <?php if(isset($isAdmin) && $isAdmin): ?>
    // Additional check for admin: must select a user
    if (!$('#id_user').val()) {
        isValid = false;
        $('#pegawai_search').addClass('is-invalid');
        $('#id_user').addClass('is-invalid');
        Swal.fire({
            title: 'Pegawai Belum Dipilih',
            text: 'Silakan pilih pegawai terlebih dahulu',
            icon: 'warning'
        });
        e.preventDefault();
        return;
    }
    <?php endif; ?>
    
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
