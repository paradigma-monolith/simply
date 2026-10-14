<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<section class="content">
    <div class="container-fluid">

        <!-- Feedback List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>Daftar Feedback Saya
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="$('#feedbackBtn').click()">
                                <i class="fas fa-plus mr-1"></i>Kirim Feedback Baru
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($feedbacks)): ?>
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-comment-slash text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="text-muted">Belum Ada Feedback</h4>
                                <p class="text-muted mb-4">
                                    Anda belum pernah mengirim feedback. Mulai berikan saran dan masukan untuk pengembangan aplikasi!
                                </p>
                                <button type="button" class="btn btn-primary btn-lg" onclick="$('#feedbackBtn').click()">
                                    <i class="fas fa-comment-dots mr-2"></i>Kirim Feedback Pertama
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Filter Options -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Filter Status">
                                        <button type="button" class="btn btn-outline-secondary active" data-status="all">
                                            <i class="fas fa-list mr-1"></i>Semua (<?= $stats['total']; ?>)
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" data-status="Baru">
                                            <i class="fas fa-clock mr-1"></i>Baru (<?= $stats['baru']; ?>)
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-status="Diproses">
                                            <i class="fas fa-cogs mr-1"></i>Diproses (<?= $stats['diproses']; ?>)
                                        </button>
                                        <button type="button" class="btn btn-outline-success" data-status="Selesai">
                                            <i class="fas fa-check-circle mr-1"></i>Selesai (<?= $stats['selesai']; ?>)
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" data-status="Ditolak">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak (<?= $stats['ditolak']; ?>)
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Klik status untuk memfilter data
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Feedback Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="feedbackTable" style="width: 100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Judul</th>
                                            <th width="15%">Kategori</th>
                                            <th width="10%">Prioritas</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Modul</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($feedbacks as $index => $feedback): ?>
                                            <tr class="feedback-row">
                                                <td>
                                                    <span class="badge badge-light"><?= $index + 1; ?></span>
                                                </td>
                                                <td>
                                                    <div class="feedback-title">
                                                        <strong><?= esc($feedback['judul']); ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?= strlen($feedback['deskripsi']) > 80 ? substr(esc($feedback['deskripsi']), 0, 80) . '...' : esc($feedback['deskripsi']); ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?= $feedback['kategori']; ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $priorityClass = 'badge-secondary';
                                                    $priorityIcon = 'fas fa-circle';
                                                    switch($feedback['prioritas']) {
                                                        case 'Rendah': 
                                                            $priorityClass = 'badge-secondary'; 
                                                            $priorityIcon = 'fas fa-arrow-down';
                                                            break;
                                                        case 'Sedang': 
                                                            $priorityClass = 'badge-info'; 
                                                            $priorityIcon = 'fas fa-minus';
                                                            break;
                                                        case 'Tinggi': 
                                                            $priorityClass = 'badge-warning'; 
                                                            $priorityIcon = 'fas fa-arrow-up';
                                                            break;
                                                        case 'Kritis': 
                                                            $priorityClass = 'badge-danger'; 
                                                            $priorityIcon = 'fas fa-exclamation-triangle';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?= $priorityClass; ?>">
                                                        <i class="<?= $priorityIcon; ?> mr-1"></i><?= $feedback['prioritas']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statusClass = 'badge-secondary';
                                                    $statusIcon = 'fas fa-circle';
                                                    switch($feedback['status']) {
                                                        case 'Baru': 
                                                            $statusClass = 'badge-warning'; 
                                                            $statusIcon = 'fas fa-clock';
                                                            break;
                                                        case 'Diproses': 
                                                            $statusClass = 'badge-primary'; 
                                                            $statusIcon = 'fas fa-cogs';
                                                            break;
                                                        case 'Selesai': 
                                                            $statusClass = 'badge-success'; 
                                                            $statusIcon = 'fas fa-check-circle';
                                                            break;
                                                        case 'Ditolak': 
                                                            $statusClass = 'badge-danger'; 
                                                            $statusIcon = 'fas fa-times-circle';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?= $statusClass; ?>">
                                                        <i class="<?= $statusIcon; ?> mr-1"></i><?= $feedback['status']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($feedback['modul']): ?>
                                                        <span class="text-muted">
                                                            <i class="fas fa-tag mr-1"></i><?= esc($feedback['modul']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="feedback-date">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        <?= date('d/m/Y', strtotime($feedback['created_at'])); ?>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            <?= date('H:i', strtotime($feedback['created_at'])); ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                onclick="viewFeedbackDetail(<?= $feedback['id_feedback']; ?>)"
                                                                title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($feedback['status'] === 'Baru'): ?>
                                                            <button class="btn btn-sm btn-outline-secondary" 
                                                                    onclick="editFeedback(<?= $feedback['id_feedback']; ?>)"
                                                                    title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Feedback -->
<div class="modal fade" id="feedbackDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye mr-2"></i>Detail Feedback
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="feedbackDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Table Styling */
#feedbackTable {
    font-size: 14px;
}

#feedbackTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
    vertical-align: middle;
}

#feedbackTable tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f4;
}

#feedbackTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#feedbackTable tbody td {
    padding: 12px 8px;
    vertical-align: middle;
    border-top: none;
}

.feedback-title {
    line-height: 1.4;
}

.feedback-title strong {
    color: #2c3e50;
    font-size: 14px;
}

.feedback-title small {
    color: #6c757d;
    font-size: 12px;
    line-height: 1.3;
}

.feedback-date {
    font-size: 12px;
    line-height: 1.3;
}

.feedback-date i {
    color: #6c757d;
}

/* Badge Styling */
.badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 500;
}

.badge-light {
    background-color: #e9ecef;
    color: #495057;
    border: 1px solid #dee2e6;
}

/* Priority Badge Colors */
.badge-secondary { background-color: #6c757d; }
.badge-info { background-color: #17a2b8; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-danger { background-color: #dc3545; }
.badge-success { background-color: #28a745; }
.badge-primary { background-color: #007bff; }

/* Button Group Styling */
.btn-group .btn {
    border-radius: 4px;
    margin: 0 1px;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}


.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

/* Empty state styling */
.text-center.py-5 {
    padding: 3rem 1rem;
}

/* Table Row Animation */
.feedback-row {
    animation: fadeInUp 0.3s ease-out;
}

.feedback-row:nth-child(even) {
    animation-delay: 0.1s;
}

.feedback-row:nth-child(odd) {
    animation-delay: 0.05s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 12px;
    }
    
    #feedbackTable thead th,
    #feedbackTable tbody td {
        padding: 8px 4px;
    }
    
    .feedback-title strong {
        font-size: 13px;
    }
    
    .feedback-title small {
        font-size: 11px;
    }
    
    .btn-group .btn {
        padding: 4px 6px;
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .table-responsive {
        border: none;
    }
    
    #feedbackTable {
        font-size: 11px;
    }
    
    .badge {
        font-size: 9px;
        padding: 2px 6px;
    }
    
    .feedback-title small {
        display: none;
    }
}

/* Loading State */
.table-loading {
    position: relative;
}

.table-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

/* Status Indicators */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-indicator i {
    font-size: 10px;
}

/* Priority Indicators */
.priority-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.priority-indicator i {
    font-size: 10px;
}

/* Filter Buttons */
.btn-group [data-status] {
    border-radius: 20px;
    margin-right: 5px;
    font-size: 12px;
    padding: 6px 12px;
    transition: all 0.3s ease;
}

.btn-group [data-status]:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-group [data-status].active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-group [data-status="all"].active {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-group [data-status="Baru"].active {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-group [data-status="Diproses"].active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-group [data-status="Selesai"].active {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-group [data-status="Ditolak"].active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Filter Info Alert */
.filter-info {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive Filter Buttons */
@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .btn-group [data-status] {
        flex: 1;
        min-width: calc(50% - 5px);
        margin-right: 0;
        font-size: 11px;
        padding: 4px 8px;
    }
}

@media (max-width: 576px) {
    .btn-group [data-status] {
        min-width: 100%;
        margin-bottom: 5px;
    }
}
</style>

<script>
function viewFeedbackDetail(id) {
    $.ajax({
        url: `/home/ajax/feedback-detail/${id}`,
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#feedbackDetailContent').html(`
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2">Memuat detail feedback...</p>
                </div>
            `);
            $('#feedbackDetailModal').modal('show');
        },
        success: function(response) {
            if (response.success) {
                $('#feedbackDetailContent').html(response.data);
            } else {
                $('#feedbackDetailContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Gagal memuat detail feedback: ${response.message}
                    </div>
                `);
            }
        },
        error: function() {
            $('#feedbackDetailContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Terjadi kesalahan saat memuat detail feedback.
                </div>
            `);
        }
    });
}

function editFeedback(id) {
    Swal.fire({
        title: 'Edit Feedback',
        text: 'Fitur edit feedback akan segera tersedia. Untuk saat ini, Anda dapat mengirim feedback baru.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

// Initialize DataTable
var feedbackTable;
$(document).ready(function() {
    if ($.fn.DataTable) {
        feedbackTable = $('#feedbackTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [7] }, // Disable sorting on action column
                { "searchable": false, "targets": [0, 7] } // Disable search on number and action columns
            ],
            "order": [[6, "desc"]], // Sort by date descending by default
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "initComplete": function() {
                // Add custom styling after table initialization
                $('.dataTables_length select').addClass('form-control-sm');
                $('.dataTables_filter input').addClass('form-control-sm');
            }
        });
        
        // Initialize filter buttons
        initStatusFilter();
    }
});

// Status Filter Function
function initStatusFilter() {
    $('[data-status]').on('click', function() {
        var status = $(this).data('status');
        
        // Update button states
        $('[data-status]').removeClass('active');
        $(this).addClass('active');
        
        // Filter table
        if (status === 'all') {
            feedbackTable.column(4).search('').draw();
        } else {
            feedbackTable.column(4).search('^' + status + '$', true, false).draw();
        }
        
        // Show filter info
        if (status !== 'all') {
            showFilterInfo('Memfilter berdasarkan status: ' + status);
        } else {
            showFilterInfo('Menampilkan semua feedback');
        }
    });
}

// Show filter info
function showFilterInfo(message) {
    // Remove existing info
    $('.filter-info').remove();
    
    // Add new info
    var info = $('<div class="filter-info alert alert-info alert-dismissible fade show" style="margin-bottom: 15px;">' +
        '<i class="fas fa-filter mr-2"></i>' + message +
        '<button type="button" class="close" data-dismiss="alert">' +
        '<span>&times;</span></button></div>');
    
    $('#feedbackTable').closest('.table-responsive').before(info);
    
    // Auto hide after 3 seconds
    setTimeout(function() {
        $('.filter-info').fadeOut();
    }, 3000);
}

// Refresh page when feedback is successfully submitted
$(document).on('feedbackSubmitted', function() {
    setTimeout(function() {
        location.reload();
    }, 2000);
});
</script>

<?= $this->endSection(); ?>
