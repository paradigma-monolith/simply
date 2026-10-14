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
                    <div class="col-sm-3"><strong>Tanggal Kirim:</strong></div>
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
                    <div class="col-sm-3"><strong>Status:</strong></div>
                    <div class="col-sm-9">
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
                    </div>
                </div>
                
                <?php if ($feedback['updated_at'] && $feedback['updated_at'] != $feedback['created_at']): ?>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Terakhir Diupdate:</strong></div>
                    <div class="col-sm-9"><?= date('d/m/Y H:i', strtotime($feedback['updated_at'])); ?></div>
                </div>
                <?php endif; ?>
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
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tie text-success mr-2"></i>
                            <small class="text-success font-weight-bold">Tanggapan dari Tim Admin</small>
                        </div>
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
                    <i class="fas fa-info-circle mr-2"></i>Status Progress
                </h5>
            </div>
            <div class="card-body">
                <div class="progress-status">
                    <?php
                    $steps = ['Baru', 'Diproses', 'Selesai'];
                    $currentStep = array_search($feedback['status'], $steps);
                    if ($feedback['status'] === 'Ditolak') {
                        $currentStep = -1; // Special handling for rejected
                    }
                    ?>
                    
                    <?php if ($feedback['status'] === 'Ditolak'): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle mr-2"></i>
                            <strong>Feedback Ditolak</strong>
                            <p class="mb-0 mt-2 small">Feedback Anda telah ditinjau namun tidak dapat diproses lebih lanjut.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($steps as $index => $step): ?>
                            <div class="progress-step <?= $index <= $currentStep ? 'completed' : ''; ?> <?= $index == $currentStep ? 'current' : ''; ?>">
                                <div class="step-indicator">
                                    <?php if ($index < $currentStep): ?>
                                        <i class="fas fa-check"></i>
                                    <?php elseif ($index == $currentStep): ?>
                                        <?php
                                        switch($step) {
                                            case 'Baru': echo '<i class="fas fa-clock"></i>'; break;
                                            case 'Diproses': echo '<i class="fas fa-cogs"></i>'; break;
                                            case 'Selesai': echo '<i class="fas fa-check-circle"></i>'; break;
                                        }
                                        ?>
                                    <?php else: ?>
                                        <i class="fas fa-circle"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="step-content">
                                    <h6 class="step-title"><?= $step; ?></h6>
                                    <small class="step-description text-muted">
                                        <?php
                                        switch($step) {
                                            case 'Baru': echo 'Feedback telah diterima sistem'; break;
                                            case 'Diproses': echo 'Tim sedang meninjau feedback'; break;
                                            case 'Selesai': echo 'Feedback telah selesai ditangani'; break;
                                        }
                                        ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle mr-2"></i>Bantuan
                </h5>
            </div>
            <div class="card-body">
                <div class="help-content">
                    <h6>Status Feedback:</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <span class="badge badge-warning badge-sm mr-2">Baru</span>
                            Feedback baru diterima
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-primary badge-sm mr-2">Diproses</span>
                            Sedang ditinjau tim
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-success badge-sm mr-2">Selesai</span>
                            Telah selesai ditangani
                        </li>
                        <li class="mb-2">
                            <span class="badge badge-danger badge-sm mr-2">Ditolak</span>
                            Tidak dapat diproses
                        </li>
                    </ul>
                    
                    <hr>
                    
                    <p class="small text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Feedback Anda akan ditinjau oleh tim dalam 1-3 hari kerja.
                    </p>
                    
                    <p class="small text-muted mb-0">
                        <i class="fas fa-envelope mr-1"></i>
                        Anda akan mendapat notifikasi jika ada update status.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-status {
    position: relative;
}

.progress-step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
    position: relative;
}

.progress-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 17px;
    top: 35px;
    bottom: -25px;
    width: 2px;
    background: #dee2e6;
    z-index: 1;
}

.progress-step.completed:not(:last-child)::after {
    background: #28a745;
}

.step-indicator {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 14px;
    margin-right: 15px;
    position: relative;
    z-index: 2;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.progress-step.completed .step-indicator {
    background: #28a745;
    color: white;
}

.progress-step.current .step-indicator {
    background: #007bff;
    color: white;
    animation: pulse 2s infinite;
}

.step-content {
    flex: 1;
    padding-top: 5px;
}

.step-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 3px;
    color: #495057;
}

.progress-step.completed .step-title {
    color: #28a745;
}

.progress-step.current .step-title {
    color: #007bff;
}

.step-description {
    font-size: 12px;
    line-height: 1.4;
}

@keyframes pulse {
    0% {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    50% {
        box-shadow: 0 2px 15px rgba(0, 123, 255, 0.4);
    }
    100% {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
}

.bg-success-light {
    background-color: #d4edda !important;
}

.badge-sm {
    font-size: 10px;
    padding: 3px 6px;
}

.help-content ul li {
    display: flex;
    align-items: center;
}

/* Responsive */
@media (max-width: 768px) {
    .progress-step {
        margin-bottom: 20px;
    }
    
    .step-indicator {
        width: 30px;
        height: 30px;
        font-size: 12px;
        margin-right: 12px;
    }
    
    .progress-step:not(:last-child)::after {
        left: 14px;
        bottom: -20px;
    }
}
</style>
