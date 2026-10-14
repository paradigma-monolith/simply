<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="totalFeedback">0</h3>
                        <p>Total Feedback</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="newFeedback">0</h3>
                        <p>Feedback Baru</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="processingFeedback">0</h3>
                        <p>Sedang Diproses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="completedFeedback">0</h3>
                        <p>Selesai</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Feedback List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>Daftar Saran & Masukan
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary filter-btn active" data-status="semua">
                                    Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning filter-btn" data-status="Baru">
                                    Baru
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-status="Diproses">
                                    Diproses
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success filter-btn" data-status="Selesai">
                                    Selesai
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger filter-btn" data-status="Ditolak">
                                    Ditolak
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="feedbackTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="15%">Kategori</th>
                                    <th width="8%">Prioritas</th>
                                    <th width="25%">Judul</th>
                                    <th width="12%">Pengirim</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="feedbackTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Feedback -->
<div class="modal fade" id="feedbackDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye mr-2"></i>Detail Saran & Masukan
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

<script>
$(document).ready(function() {
    // Load statistics and feedback list
    loadFeedbackStats();
    loadFeedbackList();
    
    // Filter buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        const status = $(this).data('status');
        loadFeedbackList(status);
    });
});

function loadFeedbackStats() {
    $.ajax({
        url: '/admin/ajax/feedback-stats',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#totalFeedback').text(stats.total || 0);
                $('#newFeedback').text(stats.baru || 0);
                $('#processingFeedback').text(stats.diproses || 0);
                $('#completedFeedback').text(stats.selesai || 0);
            }
        },
        error: function() {
            console.error('Failed to load feedback statistics');
        }
    });
}

function loadFeedbackList(status = 'semua') {
    $.ajax({
        url: '/admin/ajax/feedback-list',
        method: 'GET',
        data: { status: status },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                let html = '';
                const feedbacks = response.data;
                
                if (feedbacks.length === 0) {
                    html = '<tr><td colspan="8" class="text-center">Tidak ada data feedback</td></tr>';
                } else {
                    feedbacks.forEach((feedback, index) => {
                        const statusClass = getStatusClass(feedback.status);
                        const priorityClass = getPriorityClass(feedback.prioritas);
                        const pengirim = feedback.anonymous == 1 ? 'Anonim' : (feedback.nama_user || 'Unknown');
                        
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${formatDate(feedback.created_at)}</td>
                                <td><span class="badge badge-info">${feedback.kategori}</span></td>
                                <td><span class="badge ${priorityClass}">${feedback.prioritas}</span></td>
                                <td><strong>${feedback.judul}</strong></td>
                                <td>${pengirim}</td>
                                <td><span class="badge ${statusClass}">${feedback.status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewFeedback(${feedback.id_feedback})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="updateStatus(${feedback.id_feedback}, 'Diproses')">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="updateStatus(${feedback.id_feedback}, 'Selesai')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                $('#feedbackTableBody').html(html);
            }
        },
        error: function() {
            console.error('Failed to load feedback list');
            $('#feedbackTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
        }
    });
}

function getStatusClass(status) {
    switch(status) {
        case 'Baru': return 'badge-warning';
        case 'Diproses': return 'badge-primary';
        case 'Selesai': return 'badge-success';
        case 'Ditolak': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

function getPriorityClass(priority) {
    switch(priority) {
        case 'Rendah': return 'badge-secondary';
        case 'Sedang': return 'badge-info';
        case 'Tinggi': return 'badge-warning';
        case 'Kritis': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric'
    });
}

function viewFeedback(id) {
    $.ajax({
        url: `/admin/ajax/feedback-detail/${id}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#feedbackDetailContent').html(response.data);
                $('#feedbackDetailModal').modal('show');
            }
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat detail feedback', 'error');
        }
    });
}

function updateStatus(id, newStatus) {
    Swal.fire({
        title: `Ubah Status ke "${newStatus}"?`,
        text: 'Apakah Anda yakin ingin mengubah status feedback ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/ajax/feedback-update-status/${id}`,
                method: 'POST',
                data: { status: newStatus },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil', 'Status feedback berhasil diubah', 'success');
                        loadFeedbackStats();
                        loadFeedbackList();
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal mengubah status', 'error');
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
