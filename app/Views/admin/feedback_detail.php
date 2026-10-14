<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Feedback
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>ID Feedback:</strong></div>
                    <div class="col-sm-9">#<?= $feedback['id_feedback']; ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Tanggal:</strong></div>
                    <div class="col-sm-9"><?= date('d/m/Y H:i', strtotime($feedback['created_at'])); ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Kategori:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge badge-info"><?= $feedback['kategori']; ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Prioritas:</strong></div>
                    <div class="col-sm-9">
                        <?php
                        $priorityClass = 'badge-secondary';
                        switch($feedback['prioritas']) {
                            case 'Rendah': $priorityClass = 'badge-secondary'; break;
                            case 'Sedang': $priorityClass = 'badge-info'; break;
                            case 'Tinggi': $priorityClass = 'badge-warning'; break;
                            case 'Kritis': $priorityClass = 'badge-danger'; break;
                        }
                        ?>
                        <span class="badge <?= $priorityClass; ?>"><?= $feedback['prioritas']; ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Modul:</strong></div>
                    <div class="col-sm-9"><?= $feedback['modul'] ?: '<em class="text-muted">Tidak disebutkan</em>'; ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Pengirim:</strong></div>
                    <div class="col-sm-9">
                        <?php if ($feedback['anonymous'] == 1): ?>
                            <span class="text-muted"><i class="fas fa-user-secret mr-1"></i>Anonim</span>
                        <?php else: ?>
                            <strong><?= $feedback['nama_user']; ?></strong>
                            <?php if ($feedback['email']): ?>
                                <br><small class="text-muted"><?= $feedback['email']; ?></small>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Status:</strong></div>
                    <div class="col-sm-9">
                        <?php
                        $statusClass = 'badge-secondary';
                        switch($feedback['status']) {
                            case 'Baru': $statusClass = 'badge-warning'; break;
                            case 'Diproses': $statusClass = 'badge-primary'; break;
                            case 'Selesai': $statusClass = 'badge-success'; break;
                            case 'Ditolak': $statusClass = 'badge-danger'; break;
                        }
                        ?>
                        <span class="badge <?= $statusClass; ?>"><?= $feedback['status']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comment-dots mr-2"></i>Detail Feedback
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><strong>Judul:</strong></h6>
                    <p class="mb-2"><?= esc($feedback['judul']); ?></p>
                </div>
                
                <div class="mb-3">
                    <h6><strong>Deskripsi:</strong></h6>
                    <div class="border rounded p-3 bg-light">
                        <?= nl2br(esc($feedback['deskripsi'])); ?>
                    </div>
                </div>
                
                <?php if ($feedback['tanggapan']): ?>
                <div class="mb-3">
                    <h6><strong>Tanggapan Admin:</strong></h6>
                    <div class="border rounded p-3 bg-success-light">
                        <?= nl2br(esc($feedback['tanggapan'])); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs mr-2"></i>Aksi
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="newStatus">Ubah Status:</label>
                    <select class="form-control" id="newStatus">
                        <option value="Baru" <?= $feedback['status'] == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                        <option value="Diproses" <?= $feedback['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                        <option value="Selesai" <?= $feedback['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                        <option value="Ditolak" <?= $feedback['status'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="adminResponse">Tanggapan Admin:</label>
                    <textarea class="form-control" id="adminResponse" rows="4" placeholder="Berikan tanggapan atau komentar..."><?= esc($feedback['tanggapan']); ?></textarea>
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-block" onclick="updateFeedbackStatus(<?= $feedback['id_feedback']; ?>)">
                        <i class="fas fa-save mr-1"></i>Simpan Perubahan
                    </button>
                    
                    <button class="btn btn-danger btn-block" onclick="deleteFeedback(<?= $feedback['id_feedback']; ?>)">
                        <i class="fas fa-trash mr-1"></i>Hapus Feedback
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="timeline-wrapper">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="timeline-title mb-1">Feedback Dibuat</h6>
                                <small class="timeline-time text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($feedback['created_at'])); ?>
                                </small>
                            </div>
                            <p class="timeline-description mb-0 text-muted">
                                Feedback baru telah dikirim ke sistem
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($feedback['updated_at'] && $feedback['updated_at'] != $feedback['created_at']): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="timeline-title mb-1">Status Diperbarui</h6>
                                <small class="timeline-time text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($feedback['updated_at'])); ?>
                                </small>
                            </div>
                            <p class="timeline-description mb-0 text-muted">
                                Status feedback diubah menjadi 
                                <span class="badge badge-sm <?php
                                    switch($feedback['status']) {
                                        case 'Baru': echo 'badge-warning'; break;
                                        case 'Diproses': echo 'badge-primary'; break;
                                        case 'Selesai': echo 'badge-success'; break;
                                        case 'Ditolak': echo 'badge-danger'; break;
                                        default: echo 'badge-secondary';
                                    }
                                ?>"><?= $feedback['status']; ?></span>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($feedback['tanggapan']): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="timeline-title mb-1">Tanggapan Ditambahkan</h6>
                                <small class="timeline-time text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($feedback['updated_at'])); ?>
                                </small>
                            </div>
                            <p class="timeline-description mb-0 text-muted">
                                Admin memberikan tanggapan pada feedback ini
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Timeline end marker -->
                    <div class="timeline-end">
                        <div class="timeline-marker-end bg-light">
                            <i class="fas fa-circle text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFeedbackStatus(id) {
    const newStatus = $('#newStatus').val();
    const tanggapan = $('#adminResponse').val();
    
    Swal.fire({
        title: 'Konfirmasi',
        text: `Ubah status feedback menjadi "${newStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/ajax/feedback-update-status/${id}`,
                method: 'POST',
                data: { 
                    status: newStatus,
                    tanggapan: tanggapan
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            $('#feedbackDetailModal').modal('hide');
                            loadFeedbackStats();
                            loadFeedbackList();
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                }
            });
        }
    });
}

function deleteFeedback(id) {
    Swal.fire({
        title: 'Hapus Feedback?',
        text: 'Data feedback akan dihapus permanen dan tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/ajax/feedback-delete/${id}`,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            $('#feedbackDetailModal').modal('hide');
                            loadFeedbackStats();
                            loadFeedbackList();
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
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

<style>
/* Timeline Styles */
.timeline-wrapper {
    position: relative;
    padding-left: 40px;
}

.timeline-wrapper::before {
    content: '';
    position: absolute;
    left: 18px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #dee2e6 0%, #dee2e6 90%, transparent 100%);
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
    padding-bottom: 15px;
}

.timeline-item:last-child {
    margin-bottom: 10px;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    top: 2px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 3px solid #fff;
    z-index: 2;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #dee2e6;
    transition: all 0.3s ease;
}

.timeline-item:hover .timeline-content {
    background: #ffffff;
    border-left-color: #007bff;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.timeline-header {
    margin-bottom: 8px;
}

.timeline-title {
    font-size: 15px;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 4px;
}

.timeline-time {
    font-size: 12px;
    display: inline-flex;
    align-items: center;
}

.timeline-time i {
    font-size: 11px;
}

.timeline-description {
    font-size: 13px;
    line-height: 1.5;
}

.timeline-end {
    position: relative;
    margin-top: 10px;
}

.timeline-marker-end {
    position: absolute;
    left: -40px;
    top: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
    z-index: 2;
}

.timeline-marker-end i {
    font-size: 8px;
}

/* Status Badge Styling */
.badge-sm {
    font-size: 10px;
    padding: 3px 6px;
}

/* Color variations for timeline markers */
.timeline-item:nth-child(1) .timeline-content {
    border-left-color: #28a745;
}

.timeline-item:nth-child(2) .timeline-content {
    border-left-color: #17a2b8;
}

.timeline-item:nth-child(3) .timeline-content {
    border-left-color: #ffc107;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .timeline-wrapper {
        padding-left: 35px;
    }
    
    .timeline-marker {
        left: -35px;
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .timeline-marker-end {
        left: -35px;
        width: 32px;
        height: 32px;
    }
    
    .timeline-wrapper::before {
        left: 16px;
    }
    
    .timeline-content {
        padding: 12px;
    }
    
    .timeline-title {
        font-size: 14px;
    }
}

/* Success light background */
.bg-success-light {
    background-color: #d4edda !important;
}

/* Animation for timeline items */
.timeline-item {
    animation: fadeInUp 0.5s ease-out;
}

.timeline-item:nth-child(2) {
    animation-delay: 0.1s;
}

.timeline-item:nth-child(3) {
    animation-delay: 0.2s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
