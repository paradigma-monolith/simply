<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<div class="content-header py-2">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-users-cog text-primary"></i> Pengaturan Akses Pengukuran Kebugaran
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/pengukuran_kebugaran">Pengukuran Kebugaran</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
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

        <div class="row">
            <!-- Form Berikan Akses -->
            <div class="col-md-6">
                <div class="card card-primary card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-user-plus"></i> Berikan Akses Khusus
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="formGrantAccess">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="user_id">Pilih Pegawai:</label>
                                <div class="dropdown-search-container">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="user_search" 
                                               placeholder="Ketik nama, NIP, atau username pegawai..." autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                                    id="user_dropdown_btn" data-toggle="dropdown">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu w-100" id="user_dropdown" style="max-height: 250px; overflow-y: auto;">
                                        <?php foreach($allUsers as $user): ?>
                                            <a class="dropdown-item user-option" href="#" 
                                               data-id="<?= $user['id_user'] ?>" 
                                               data-nama="<?= $user['nama'] ?>"
                                               data-nip="<?= $user['nip'] ?? '' ?>"
                                               data-username="<?= $user['username'] ?>">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-primary mr-2"></i>
                                                    <div class="flex-grow-1">
                                                        <div class="font-weight-bold"><?= $user['nama'] ?></div>
                                                        <small class="text-muted">
                                                            <?= !empty($user['nip']) ? 'NIP: ' . $user['nip'] . ' | ' : '' ?>
                                                            Username: <?= $user['username'] ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" id="user_id" name="user_id" required>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Pegawai yang dipilih akan dapat menambahkan data untuk semua pegawai seperti admin
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Berikan Akses
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card card-info card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Informasi Akses
                        </h3>
                    </div>
                    <div class="card-body p-2">
                        <h6 class="text-bold text-primary border-bottom pb-1">
                            <i class="fas fa-shield-alt"></i> Hak Akses Khusus
                        </h6>
                        <p class="text-sm mb-2">
                            Pegawai dengan akses khusus dapat:
                        </p>
                        <ul class="text-sm">
                            <li><i class="fas fa-check text-success"></i> Melihat data pengukuran kebugaran semua pegawai</li>
                            <li><i class="fas fa-check text-success"></i> Menambahkan data untuk pegawai lain</li>
                            <li><i class="fas fa-check text-success"></i> Mengedit data pengukuran kebugaran semua pegawai</li>
                            <li><i class="fas fa-check text-success"></i> Menghapus data pengukuran kebugaran semua pegawai</li>
                        </ul>
                        
                        <div class="alert alert-warning p-2 mt-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian:</strong> Berikan akses ini hanya kepada pegawai yang dipercaya.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pegawai dengan Akses -->
            <div class="col-md-6">
                <div class="card card-success card-outline elevation-2">
                    <div class="card-header py-2">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i> Pegawai dengan Akses Khusus
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if(!empty($usersWithAccess)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            <th>Username</th>
                                            <th>Diberikan Oleh</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($usersWithAccess as $userAccess): ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-user text-primary"></i>
                                                    <?= $userAccess['nama'] ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?= $userAccess['username'] ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= $userAccess['admin_nama'] ?? 'Admin' ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-xs" 
                                                            onclick="revokeAccess(<?= $userAccess['id_user'] ?>, '<?= $userAccess['nama'] ?>')">
                                                        <i class="fas fa-user-times"></i> Cabut
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-users fa-3x mb-2"></i>
                                <p>Belum ada pegawai yang diberikan akses khusus</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-3">
            <div class="col-12">
                <a href="<?= base_url('/pengukuran_kebugaran') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Data
                </a>
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
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
    white-space: normal;
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

.dropdown-search-container .dropdown-item.active .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.dropdown-search-container .dropdown-item.active .text-primary {
    color: white !important;
}

.dropdown-search-container .dropdown-item-text {
    padding: 0.75rem 1rem;
    color: #6c757d;
}

.dropdown-search-container .input-group-append .btn {
    border-left: none;
}

.dropdown-search-container #user_search {
    border-right: none;
}

.dropdown-search-container #user_search:focus {
    box-shadow: none;
    border-color: #ced4da;
}
</style>

<script>
$(document).ready(function() {
    // Initialize user dropdown search functionality
    initUserDropdown();
    
    // Handle form submission for granting access
    $('#formGrantAccess').on('submit', function(e) {
        e.preventDefault();
        
        const userId = $('#user_id').val();
        if (!userId) {
            Swal.fire('Error', 'Silakan pilih pegawai terlebih dahulu', 'error');
            return;
        }

        // Show confirmation
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin memberikan akses khusus kepada pegawai ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Berikan Akses',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                grantAccess(userId);
            }
        });
    });
});

function initUserDropdown() {
    const searchInput = document.getElementById('user_search');
    const dropdownMenu = document.getElementById('user_dropdown');
    const hiddenInput = document.getElementById('user_id');
    const dropdownBtn = document.getElementById('user_dropdown_btn');
    
    let allOptions = [];
    let filteredOptions = [];
    let selectedIndex = -1;
    
    // Store all options
    document.querySelectorAll('.user-option').forEach(option => {
        allOptions.push({
            id: option.dataset.id,
            nama: option.dataset.nama,
            nip: option.dataset.nip,
            username: option.dataset.username,
            element: option,
            searchText: (option.dataset.nama + ' ' + option.dataset.nip + ' ' + option.dataset.username).toLowerCase()
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
        const option = e.target.closest('.user-option');
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
        const displayText = option.nama + (option.nip ? ' (' + option.nip + ')' : '') + ' - ' + option.username;
        searchInput.value = displayText;
        hiddenInput.value = option.id;
        dropdownMenu.classList.remove('show');
        
        // Remove validation error if exists
        searchInput.classList.remove('is-invalid');
        hiddenInput.classList.remove('is-invalid');
    }
}

function grantAccess(userId) {
    $.ajax({
        url: '<?= base_url('/pengukuran_kebugaran/grant_access') ?>',
        type: 'POST',
        data: {
            user_id: userId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire('Berhasil', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    });
}

function revokeAccess(userId, userName) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin mencabut akses khusus dari ${userName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Cabut Akses',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('/pengukuran_kebugaran/revoke_access') ?>',
                type: 'POST',
                data: {
                    user_id: userId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                }
            });
        }
    });
}
</script>

<?= $this->endSection(); ?> 