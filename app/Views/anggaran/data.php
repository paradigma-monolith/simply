<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
<section class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Anggaran</h3>
                            <p class="text-muted mb-2"><small><i class="fas fa-info-circle"></i> Deviasi dihitung otomatis berdasarkan selisih antara rencana pencairan dan realisasi anggaran</small></p>
                            <div class="card-tools d-flex align-items-center flex-wrap">
                                <!-- Filter Section -->
                                <div class="form-group mr-3 mb-2">
                                    <label for="filter_bulan_dari" class="form-label">Bulan Dari:</label>
                                    <select class="form-control form-control-sm" id="filter_bulan_dari" style="min-width: 120px;">
                                        <option value="">Semua</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-3 mb-2">
                                    <label for="filter_bulan_sampai" class="form-label">Bulan Sampai:</label>
                                    <select class="form-control form-control-sm" id="filter_bulan_sampai" style="min-width: 120px;">
                                        <option value="">Semua</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-3 mb-2">
                                    <label for="filter_akun" class="form-label">Akun:</label>
                                    <select class="form-control form-control-sm" id="filter_akun" style="min-width: 100px;">
                                        <option value="">Semua</option>
                                        <option value="51">Akun 51</option>
                                        <option value="52">Akun 52</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-3 mb-2">
                                    <label for="filter_pic" class="form-label">PIC:</label>
                                    <select class="form-control form-control-sm" id="filter_pic" style="min-width: 120px;">
                                        <option value="">Semua</option>
                                        <?php if (!empty($anggaranData)): ?>
                                            <?php 
                                                $picList = array_unique(array_column($anggaranData, 'pic'));
                                                sort($picList);
                                                foreach ($picList as $pic): 
                                            ?>
                                                <option value="<?= htmlspecialchars($pic) ?>"><?= htmlspecialchars($pic) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-3 mb-2">
                                    <button type="button" class="btn btn-secondary btn-sm" id="resetFilter" data-toggle="tooltip" title="Reset semua filter">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="btn-group" role="group">
                                    <a href="/anggaran/export?year=<?= $currentYear ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Export data ke CSV">
                                        <i class="fas fa-download"></i> Export CSV
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal" title="Tambah data anggaran baru">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="anggaranTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Kegiatan</th>
                                            <th>Akun</th>
                                            <th>Kegiatan</th>
                                            <th>Rencana Pencairan</th>
                                            <th>PIC</th>
                                            <th>Realisasi</th>
                                            <th>Bulan</th>
                                            <th style="min-width: 180px;">Deviasi</th>
                                            <th>Progress</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($anggaranData)): ?>
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data anggaran untuk tahun <?= $currentYear ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($anggaranData as $index => $item): ?>
                                                <?php $progress = $item['rencana_pencairan_anggaran'] > 0 ? round(($item['realisasi'] / $item['rencana_pencairan_anggaran']) * 100, 2) : 0; ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= $item['no_kegiatan'] ?></td>
                                                    <td><?= $item['akun'] ?? '-' ?></td>
                                                    <td>
                                                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                                            <?= $item['kegiatan'] ?>
                                                        </div>
                                                    </td>
                                                    <td>Rp <?= number_format((float)$item['rencana_pencairan_anggaran'], 0, ',', '.') ?></td>
                                    <td><?= $item['pic'] ?></td>
                                    <td>Rp <?= number_format((float)($item['realisasi'] ?? 0), 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php 
                                                            $namaBulan = [
                                                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                                            ];
                                                            if (is_numeric($item['bulan'])) {
                                                                $bulanIndex = (int)$item['bulan'] - 1;
                                                                echo ($bulanIndex >= 0 && $bulanIndex < 12) ? $namaBulan[$bulanIndex] : 'Tidak Valid';
                                                            } else {
                                                                echo htmlspecialchars($item['bulan'] ?? 'Tidak diketahui');
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($item['deviasi'])): ?>
                                                            <?php 
                                                                // Tentukan warna badge berdasarkan isi deviasi
                                                                $badgeClass = 'badge-info';
                                                                if (strpos($item['deviasi'], 'Kelebihan') !== false) {
                                                                    $badgeClass = 'badge-danger';
                                                                } elseif (strpos($item['deviasi'], 'Kekurangan') !== false) {
                                                                    $badgeClass = 'badge-warning';
                                                                } elseif (strpos($item['deviasi'], 'Tidak ada deviasi') !== false) {
                                                                    $badgeClass = 'badge-success';
                                                                }
                                                            ?>
                                                            <div style="min-width: 150px;">
                                                                <span class="badge <?= $badgeClass ?>" data-toggle="tooltip" title="<?= $item['deviasi'] ?>" style="font-size: 0.8em; white-space: normal; word-wrap: break-word; display: inline-block; max-width: 100%;">
                                                                    <?= $item['deviasi'] ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Belum dihitung</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar <?= $progress >= 80 ? 'bg-success' : ($progress >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                                 style="width: <?= $progress ?>%"></div>
                                                        </div>
                                                        <span class="badge <?= $progress >= 80 ? 'badge-success' : ($progress >= 50 ? 'badge-warning' : 'badge-danger') ?>">
                                                            <?= $progress ?>%
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-info btn-sm" onclick="showDetail(<?= $item['id_anggaran'] ?>)" data-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" onclick="showEdit(<?= $item['id_anggaran'] ?>)" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm" 
                                                                    data-toggle="tooltip" title="Hapus"
                                                                    onclick="confirmDelete(<?= $item['id_anggaran'] ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data anggaran ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styling for filter section */
.card-tools {
    gap: 15px;
}

.card-tools .form-group {
    margin-bottom: 0.5rem;
}

.card-tools .form-group label {
    font-weight: 500;
    margin-bottom: 4px;
    font-size: 0.875rem;
    color: #495057;
}

.card-tools .form-control {
    transition: all 0.2s ease-in-out;
    font-size: 0.875rem;
}

.card-tools .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group {
    width: 100%;
    max-width: 250px;
}

/* Filter specific styling */
.card-tools .form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.8125rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.card-tools .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8125rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .card-tools {
        flex-wrap: wrap;
    }
    
    .form-group {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 992px) {
    .card-tools .form-group {
        margin-right: 0.75rem !important;
    }
    
    .card-tools .form-group:nth-child(3),
    .card-tools .form-group:nth-child(6) {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .card-tools {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }
    
    .card-tools .form-group {
        margin-bottom: 0.75rem !important;
        margin-right: 0 !important;
        width: 100%;
    }
    
    .card-tools .input-group {
        width: 100% !important;
        max-width: none;
        min-width: 200px;
    }
    
    .card-tools .btn-group {
        width: 100%;
        margin-top: 0.5rem;
        align-self: center;
    }
    
    .card-tools .btn-group .btn {
        flex: 1;
    }
}

@media (max-width: 576px) {
    .card-tools .form-group .input-group {
        min-width: 150px;
    }
    
    .card-tools .btn-group {
        flex-direction: column;
    }
    
    .card-tools .btn-group .btn {
        margin-bottom: 5px;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .card-tools .btn-group .btn:last-child {
        margin-bottom: 0;
    }
}

/* Form validation styles */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-valid {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

/* Loading state for forms */
.form-loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn-loading {
    position: relative;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
$(document).ready(function() {
    var table = $('#anggaranTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "pageLength": 25,
        "searching": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [10] } // Disable sorting for action column
        ]
    });
    
    // Custom filter function for DataTable
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var bulanDari = $('#filter_bulan_dari').val();
            var bulanSampai = $('#filter_bulan_sampai').val();
            var akun = $('#filter_akun').val();
            var pic = $('#filter_pic').val();
            
            // Get data from table columns
            var rowAkun = data[2]; // Column index for Akun
            var rowPic = data[5];  // Column index for PIC
            var rowBulan = data[7]; // Column index for Bulan
            
            // Convert month name to number for comparison
            var monthNames = {
                'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
            };
            var rowBulanNum = monthNames[rowBulan] || 0;
            
            // Filter by month range
            if (bulanDari && bulanSampai) {
                var dari = parseInt(bulanDari);
                var sampai = parseInt(bulanSampai);
                if (rowBulanNum < dari || rowBulanNum > sampai) {
                    return false;
                }
            } else if (bulanDari) {
                var dari = parseInt(bulanDari);
                if (rowBulanNum < dari) {
                    return false;
                }
            } else if (bulanSampai) {
                var sampai = parseInt(bulanSampai);
                if (rowBulanNum > sampai) {
                    return false;
                }
            }
            
            // Filter by account
            if (akun && rowAkun !== akun) {
                return false;
            }
            
            // Filter by PIC
            if (pic && rowPic !== pic) {
                return false;
            }
            
            return true;
        }
    );
    
    // Event listeners for filter controls
    $('#filter_bulan_dari, #filter_bulan_sampai, #filter_akun, #filter_pic').on('change', function() {
        table.draw();
    });
    
    // Reset filter button
    $('#resetFilter').on('click', function() {
        $('#filter_bulan_dari').val('');
        $('#filter_bulan_sampai').val('');
        $('#filter_akun').val('');
        $('#filter_pic').val('');
        table.draw();
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    

});



function confirmDelete(id) {
    document.getElementById('deleteForm').action = '/anggaran/delete/' + id;
    $('#deleteModal').modal('show');
}

// Function to show detail modal
function showDetail(id) {
    $.ajax({
        url: '/anggaran/getDetail/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                populateDetailModal(response.data);
                $('#detailModal').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengambil data detail.');
        }
    });
}

// Function to show edit modal
function showEdit(id) {
    $.ajax({
        url: '/anggaran/getEdit/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                populateEditModal(response.data);
                $('#editModal').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengambil data untuk edit.');
        }
    });
}

// Function to populate detail modal
function populateDetailModal(data) {
    $('#detail_no_kegiatan').text(data.no_kegiatan);
    $('#detail_akun').html('<span class="badge badge-' + (data.akun == '51' ? 'info' : (data.akun == '52' ? 'warning' : 'success')) + '">Akun ' + data.akun + '</span>');
    $('#detail_kegiatan').html(data.kegiatan.replace(/\n/g, '<br>'));
    $('#detail_pic').html('<span class="badge badge-primary">' + data.pic + '</span>');
    $('#detail_rencana').text('Rp ' + formatNumber(data.rencana_pencairan_anggaran));
    $('#detail_realisasi').html(data.realisasi ? '<strong class="text-info">Rp ' + formatNumber(data.realisasi) + '</strong>' : '<span class="text-muted">Belum ada realisasi</span>');
    $('#detail_tahun').text(data.tahun_anggaran);
    
    const namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $('#detail_bulan').text(namaBulan[data.bulan] || 'Tidak diketahui');
    
    const progress = data.rencana_pencairan_anggaran > 0 ? Math.round((data.realisasi / data.rencana_pencairan_anggaran) * 100 * 100) / 100 : 0;
    const progressColor = progress >= 80 ? 'success' : (progress >= 50 ? 'warning' : 'danger');
    $('#detail_progress').html('<div class="progress"><div class="progress-bar bg-' + progressColor + '" style="width: ' + progress + '%"></div></div><span class="badge badge-' + progressColor + ' mt-2">' + progress + '% Terealisasi</span>');
    
    const sisa = data.rencana_pencairan_anggaran - (data.realisasi || 0);
    $('#detail_sisa').html('<h5 class="text-' + (sisa >= 0 ? 'success' : 'danger') + '">Rp ' + formatNumber(sisa) + '</h5>');
}

// Function to populate edit modal
function populateEditModal(data) {
    $('#edit_id').val(data.id_anggaran);
    $('#edit_no_kegiatan').val(data.no_kegiatan);
    $('#edit_akun').val(data.akun);
    $('#edit_kegiatan').val(data.kegiatan);
    
    // Handle PIC field - add option if not exists
    const picSelect = $('#edit_pic');
    const picValue = data.pic;
    
    // Reset validation classes first
    $('#editForm .form-control').removeClass('is-valid is-invalid');
    
    // Check if the PIC value exists in the dropdown options
    if (picSelect.find('option[value="' + picValue + '"]').length === 0 && picValue) {
        // Add the option if it doesn't exist
        picSelect.append('<option value="' + picValue + '">' + picValue + '</option>');
    }
    picSelect.val(picValue);
    
    // Validate PIC field after setting value
    if (picValue && picValue.trim() !== '') {
        picSelect.addClass('is-valid');
    }
    
    $('#edit_rencana_pencairan_anggaran').val(formatNumber(data.rencana_pencairan_anggaran));
    $('#edit_realisasi').val(data.realisasi ? formatNumber(data.realisasi) : '');
    $('#edit_tahun_anggaran').val(data.tahun_anggaran);
    $('#edit_bulan').val(data.bulan);
    
    // Add validation classes for pre-filled fields
    if (data.no_kegiatan) $('#edit_no_kegiatan').addClass('is-valid');
    if (data.akun) $('#edit_akun').addClass('is-valid');
    if (data.kegiatan) $('#edit_kegiatan').addClass('is-valid');
    if (data.rencana_pencairan_anggaran) $('#edit_rencana_pencairan_anggaran').addClass('is-valid');
    if (data.tahun_anggaran) $('#edit_tahun_anggaran').addClass('is-valid');
    if (data.bulan) $('#edit_bulan').addClass('is-valid');
    
    $('#editForm').attr('action', '/anggaran/update/' + data.id_anggaran);
}

// Format number with thousand separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Format input angka dengan pemisah ribuan untuk modal dengan validasi
$(document).on('input', '#add_rencana_pencairan_anggaran, #add_realisasi, #edit_rencana_pencairan_anggaran, #edit_realisasi', function() {
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

// Handle form submission with AJAX
$(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    
    // Validate required fields before submission
    let isValid = true;
    let errorMessages = [];
    
    // Check required fields
    const requiredFields = {
        'no_kegiatan': 'Nomor kegiatan',
        'akun': 'Akun',
        'kegiatan': 'Kegiatan',
        'pic': 'PIC',
        'rencana_pencairan_anggaran': 'Rencana pencairan anggaran',
        'tahun_anggaran': 'Tahun anggaran',
        'bulan': 'Bulan'
    };
    
    for (let fieldName in requiredFields) {
        const field = $(this).find('[name="' + fieldName + '"]');
        const value = field.val();
        
        if (!value || value.trim() === '') {
            isValid = false;
            errorMessages.push(requiredFields[fieldName] + ' harus diisi');
            field.addClass('is-invalid');
        } else {
            field.removeClass('is-invalid');
        }
    }
    
    if (!isValid) {
        alert('Validasi gagal:\n- ' + errorMessages.join('\n- '));
        return false;
    }
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.addClass('btn-loading').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    $(this).addClass('form-loading');
    
    // Create a copy of form data for submission
    const formData = new FormData(this);
    
    // Remove formatting from currency fields with validation
    const rencanaField = $(this).find('[name="rencana_pencairan_anggaran"]');
    const realisasiField = $(this).find('[name="realisasi"]');
    
    if (rencanaField.length) {
        const rencanaValue = rencanaField.val().replace(/[^\d]/g, '');
        if (!rencanaValue || isNaN(rencanaValue) || parseInt(rencanaValue) <= 0) {
            alert('Rencana pencairan anggaran harus diisi dengan nilai yang valid');
            return false;
        }
        formData.set('rencana_pencairan_anggaran', rencanaValue);
    }
    
    if (realisasiField.length) {
        const realisasiValue = realisasiField.val().replace(/[^\d]/g, '') || '0';
        if (isNaN(realisasiValue) || parseInt(realisasiValue) < 0) {
            alert('Realisasi harus berupa angka yang valid (minimal 0)');
            return false;
        }
        formData.set('realisasi', realisasiValue);
    }
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#addModal').modal('hide');
                alert('Data berhasil ditambahkan!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errorMsg = 'Validasi gagal:\n';
                for (let field in xhr.responseJSON.errors) {
                    errorMsg += '- ' + xhr.responseJSON.errors[field] + '\n';
                }
                alert(errorMsg);
            } else {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        },
        complete: function() {
            // Reset loading state
            submitBtn.removeClass('btn-loading').prop('disabled', false).html(originalText);
            $('#addForm').removeClass('form-loading');
        }
    });
});

$(document).on('submit', '#editForm', function(e) {
    e.preventDefault();
    
    // Validate required fields before submission
    let isValid = true;
    let errorMessages = [];
    
    // Check required fields
    const requiredFields = {
        'no_kegiatan': 'Nomor kegiatan',
        'akun': 'Akun',
        'kegiatan': 'Kegiatan',
        'pic': 'PIC',
        'rencana_pencairan_anggaran': 'Rencana pencairan anggaran',
        'tahun_anggaran': 'Tahun anggaran',
        'bulan': 'Bulan'
    };
    
    for (let fieldName in requiredFields) {
        const field = $(this).find('[name="' + fieldName + '"]');
        const value = field.val();
        
        if (!value || value.trim() === '') {
            isValid = false;
            errorMessages.push(requiredFields[fieldName] + ' harus diisi');
            field.addClass('is-invalid');
        } else {
            field.removeClass('is-invalid').addClass('is-valid');
        }
    }
    
    if (!isValid) {
        alert('Validasi gagal:\n- ' + errorMessages.join('\n- '));
        return false;
    }
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.addClass('btn-loading').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memperbarui...');
    $(this).addClass('form-loading');
    
    // Create a copy of form data for submission
    const formData = new FormData(this);
    
    // Add _method field for PUT request
    formData.append('_method', 'PUT');
    
    // Remove formatting from currency fields with validation
    const rencanaField = $(this).find('[name="rencana_pencairan_anggaran"]');
    const realisasiField = $(this).find('[name="realisasi"]');
    
    if (rencanaField.length) {
        const rencanaValue = rencanaField.val().replace(/[^\d]/g, '');
        if (!rencanaValue || isNaN(rencanaValue) || parseInt(rencanaValue) <= 0) {
            alert('Rencana pencairan anggaran harus diisi dengan nilai yang valid');
            return false;
        }
        formData.set('rencana_pencairan_anggaran', rencanaValue);
    }
    
    if (realisasiField.length) {
        const realisasiValue = realisasiField.val().replace(/[^\d]/g, '') || '0';
        if (isNaN(realisasiValue) || parseInt(realisasiValue) < 0) {
            alert('Realisasi harus berupa angka yang valid (minimal 0)');
            return false;
        }
        formData.set('realisasi', realisasiValue);
    }
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#editModal').modal('hide');
                alert('Data berhasil diperbarui!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            console.log('Error response:', xhr);
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errorMsg = 'Validasi gagal:\n';
                for (let field in xhr.responseJSON.errors) {
                    errorMsg += '- ' + xhr.responseJSON.errors[field] + '\n';
                }
                alert(errorMsg);
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                alert('Error: ' + xhr.responseJSON.message);
            } else {
                alert('Terjadi kesalahan saat memperbarui data. Status: ' + xhr.status);
            }
        },
        complete: function() {
            // Reset loading state
            submitBtn.removeClass('btn-loading').prop('disabled', false).html(originalText);
            $('#editForm').removeClass('form-loading');
        }
    });
});

// Real-time validation for required fields with enhanced checks
$(document).on('input change', '#addForm input, #addForm select, #addForm textarea, #editForm input, #editForm select, #editForm textarea', function() {
    const field = $(this);
    const value = field.val();
    
    if (field.prop('required')) {
        if (!value || value.trim() === '') {
            field.addClass('is-invalid').removeClass('is-valid');
        } else {
            field.addClass('is-valid').removeClass('is-invalid');
        }
    }
    
    // Additional validation for specific fields
    if (field.attr('name') === 'tahun_anggaran') {
        const year = parseInt(value);
        if (year && (year < 2000 || year > 2099)) {
            field.addClass('is-invalid').removeClass('is-valid');
        }
    }
});

// Reset form when modal is closed
$('#addModal').on('hidden.bs.modal', function() {
    $('#addForm')[0].reset();
    // Remove validation classes
    $('#addForm .form-control').removeClass('is-valid is-invalid');
});

$('#editModal').on('hidden.bs.modal', function() {
    $('#editForm')[0].reset();
    // Remove validation classes
    $('#editForm .form-control').removeClass('is-valid is-invalid');
    
    // Reset PIC dropdown to original options
    const picSelect = $('#edit_pic');
    const originalOptions = [
        '<option value="">Pilih PIC</option>',
        '<option value="Timker 1">Timker 1</option>',
        '<option value="Timker 2">Timker 2</option>',
        '<option value="Timker 3">Timker 3</option>',
        '<option value="Timker 4">Timker 4</option>',
        '<option value="Timker 5">Timker 5</option>',
        '<option value="ADUM">ADUM</option>',
        '<option value="Gabungan">Gabungan</option>'
    ];
    picSelect.html(originalOptions.join(''));
});
</script>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="addModalLabel">
                    <i class="fas fa-plus"></i> Form Input Data Anggaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addForm" action="/anggaran/store" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_no_kegiatan">No Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_no_kegiatan" name="no_kegiatan" placeholder="Contoh: 4249.QAK.012" required>
                                <small class="form-text text-muted">Format: XXXX.XXX.XXX</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_akun">Akun <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_akun" name="akun" required>
                                    <option value="">Pilih Akun</option>
                                    <option value="51">Akun 51</option>
                                    <option value="52">Akun 52</option>
                                    <option value="53">Akun 53</option>
                                </select>
                                <small class="form-text text-muted">Pilih akun 51, 52, atau 53</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add_pic">PIC (Person In Charge) <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_pic" name="pic" required>
                                    <option value="">Pilih PIC</option>
                                    <option value="Timker 1">Timker 1</option>
                                    <option value="Timker 2">Timker 2</option>
                                    <option value="Timker 3">Timker 3</option>
                                    <option value="Timker 4">Timker 4</option>
                                    <option value="Timker 5">Timker 5</option>
                                    <option value="ADUM">ADUM</option>
                                    <option value="Gabungan">Gabungan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_kegiatan">Kegiatan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="add_kegiatan" name="kegiatan" rows="3" placeholder="Masukkan deskripsi kegiatan" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_rencana_pencairan_anggaran">Rencana Pencairan Anggaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="add_rencana_pencairan_anggaran" name="rencana_pencairan_anggaran" placeholder="0" required>
                                </div>
                                <small class="form-text text-muted">Masukkan angka tanpa titik atau koma</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_realisasi">Realisasi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="add_realisasi" name="realisasi" placeholder="0">
                                </div>
                                <small class="form-text text-muted">Opsional - dapat diisi nanti</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_tahun_anggaran">Tahun Anggaran <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_tahun_anggaran" name="tahun_anggaran" required>
                                    <?php for ($year = date('Y') + 1; $year >= 2020; $year--): ?>
                                        <option value="<?= $year ?>" <?= $year == date('Y') ? 'selected' : '' ?>><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_bulan">Bulan <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white" id="editModalLabel">
                    <i class="fas fa-edit"></i> Form Edit Data Anggaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_no_kegiatan">No Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_no_kegiatan" name="no_kegiatan" placeholder="Contoh: 4249.QAK.012" required>
                                <small class="form-text text-muted">Format: XXXX.XXX.XXX</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_akun">Akun <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_akun" name="akun" required>
                                    <option value="">Pilih Akun</option>
                                    <option value="51">Akun 51</option>
                                    <option value="52">Akun 52</option>
                                    <option value="53">Akun 53</option>
                                </select>
                                <small class="form-text text-muted">Pilih akun 51, 52, atau 53</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_pic">PIC (Person In Charge) <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_pic" name="pic" required>
                                    <option value="">Pilih PIC</option>
                                    <option value="Timker 1">Timker 1</option>
                                    <option value="Timker 2">Timker 2</option>
                                    <option value="Timker 3">Timker 3</option>
                                    <option value="Timker 4">Timker 4</option>
                                    <option value="Timker 5">Timker 5</option>
                                    <option value="ADUM">ADUM</option>
                                    <option value="Gabungan">Gabungan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_kegiatan">Kegiatan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_kegiatan" name="kegiatan" rows="3" placeholder="Masukkan deskripsi kegiatan" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_rencana_pencairan_anggaran">Rencana Pencairan Anggaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="edit_rencana_pencairan_anggaran" name="rencana_pencairan_anggaran" placeholder="0" required>
                                </div>
                                <small class="form-text text-muted">Masukkan angka tanpa titik atau koma</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_realisasi">Realisasi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="edit_realisasi" name="realisasi" placeholder="0">
                                </div>
                                <small class="form-text text-muted">Opsional - dapat diisi nanti</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tahun_anggaran">Tahun Anggaran <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_tahun_anggaran" name="tahun_anggaran" required>
                                    <?php for ($year = date('Y') + 1; $year >= 2020; $year--): ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_bulan">Bulan <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Data -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="detailModalLabel">
                    <i class="fas fa-eye"></i> Detail Data Anggaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">No Kegiatan</th>
                                <td id="detail_no_kegiatan"></td>
                            </tr>
                            <tr>
                                <th>Akun</th>
                                <td id="detail_akun"></td>
                            </tr>
                            <tr>
                                <th>Kegiatan</th>
                                <td id="detail_kegiatan"></td>
                            </tr>
                            <tr>
                                <th>PIC (Person In Charge)</th>
                                <td id="detail_pic"></td>
                            </tr>
                            <tr>
                                <th>Rencana Pencairan Anggaran</th>
                                <td><strong class="text-success" id="detail_rencana"></strong></td>
                            </tr>
                            <tr>
                                <th>Realisasi</th>
                                <td id="detail_realisasi"></td>
                            </tr>
                            <tr>
                                <th>Tahun Anggaran</th>
                                <td id="detail_tahun"></td>
                            </tr>
                            <tr>
                                <th>Bulan</th>
                                <td id="detail_bulan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Progress Realisasi</h3>
                            </div>
                            <div class="card-body">
                                <div id="detail_progress"></div>
                                <hr>
                                <h6>Sisa Anggaran:</h6>
                                <div id="detail_sisa"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>