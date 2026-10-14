<!-- Floating Feedback Button -->
<div class="floating-feedback-container">
    <!-- Floating Button -->
    <button id="feedbackBtn" class="floating-feedback-btn feedback-float-highlight" data-toggle="modal" data-target="#feedbackModal">
        <i class="fas fa-comment-dots"></i>
        <span class="feedback-tooltip">Saran & Masukan</span>
    </button>
</div>

<!-- Modal Form Saran dan Masukan -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="feedbackModalLabel">
                    <i class="fas fa-lightbulb mr-2"></i>Saran & Masukan Pengembangan Aplikasi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="feedbackForm" action="/ajax/feedback" method="post">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Bantuan Anda sangat berharga untuk pengembangan aplikasi SIMPLY. Silakan berikan saran dan masukan untuk perbaikan sistem.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="feedback_kategori">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control" id="feedback_kategori" name="kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="UI/UX">UI/UX (Tampilan & Pengalaman Pengguna)</option>
                                    <option value="Fitur">Fitur Baru</option>
                                    <option value="Bug">Laporan Bug/Error</option>
                                    <option value="Performa">Performa Sistem</option>
                                    <option value="Keamanan">Keamanan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="feedback_prioritas">Prioritas</label>
                                <select class="form-control" id="feedback_prioritas" name="prioritas">
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang" selected>Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Kritis">Kritis</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback_judul">Judul Saran/Masukan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="feedback_judul" name="judul" placeholder="Berikan judul singkat untuk saran/masukan Anda" required maxlength="100">
                        <small class="form-text text-muted">Maksimal 100 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback_deskripsi">Deskripsi Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="feedback_deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan saran/masukan Anda secara detail. Sertakan langkah-langkah jika diperlukan." required></textarea>
                        <small class="form-text text-muted">Berikan penjelasan yang detail agar mudah dipahami</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback_modul">Modul/Halaman Terkait</label>
                        <input type="text" class="form-control" id="feedback_modul" name="modul" placeholder="Contoh: Dashboard, Agenda, Laporan, dll.">
                        <small class="form-text text-muted">Sebutkan modul/halaman yang berkaitan dengan saran Anda</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="feedback_anonymous" name="anonymous" value="1">
                            <label class="custom-control-label" for="feedback_anonymous">
                                Kirim sebagai anonim
                            </label>
                        </div>
                        <small class="form-text text-muted">Jika dicentang, identitas Anda tidak akan dicantumkan dalam laporan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitFeedback">
                        <i class="fas fa-paper-plane mr-1"></i>Kirim Saran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Floating Feedback Button Styles */
.floating-feedback-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1050;
}

.floating-feedback-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.floating-feedback-btn:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0, 123, 255, 0.6);
    animation: none;
}

.floating-feedback-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 15px rgba(0, 123, 255, 0.4);
}

.feedback-tooltip {
    position: absolute;
    right: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
}

.feedback-tooltip::after {
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-left-color: rgba(0, 0, 0, 0.8);
}

.floating-feedback-btn:hover .feedback-tooltip {
    opacity: 1;
    visibility: visible;
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
    }
    50% {
        box-shadow: 0 4px 20px rgba(0, 123, 255, 0.8);
    }
    100% {
        box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .floating-feedback-container {
        bottom: 20px;
        right: 20px;
    }
    
    .floating-feedback-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .feedback-tooltip {
        display: none;
    }
}

/* Modal Enhancements */
#feedbackModal .modal-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-bottom: none;
}

#feedbackModal .modal-content {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

#feedbackModal .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

#feedbackModal .btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

#feedbackModal .btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

/* Loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
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
    // Handle form submission
    $('#feedbackForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = $('#submitFeedback');
        var originalText = submitBtn.html();
        
        // Validation
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        // Show loading state
        submitBtn.addClass('btn-loading').prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Mengirim...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Terima Kasih!',
                        text: 'Saran dan masukan Anda telah berhasil dikirim. Tim pengembang akan meninjau dan mempertimbangkan saran Anda.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#007bff'
                    });
                    
                    // Reset form and close modal
                    form[0].reset();
                    $('#feedbackModal').modal('hide');
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim',
                        text: response.message || 'Terjadi kesalahan saat mengirim saran. Silakan coba lagi.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            },
            complete: function() {
                // Reset button state
                submitBtn.removeClass('btn-loading').prop('disabled', false);
                submitBtn.html(originalText);
            }
        });
    });
    
    // Reset form when modal is closed
    $('#feedbackModal').on('hidden.bs.modal', function() {
        $('#feedbackForm')[0].reset();
        $('#feedbackForm').removeClass('was-validated');
    });
    
    // Character counter for title
    $('#feedback_judul').on('input', function() {
        var maxLength = 100;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        var counter = $(this).siblings('.form-text');
        if (remaining < 20) {
            counter.removeClass('text-muted').addClass('text-warning');
        } else {
            counter.removeClass('text-warning').addClass('text-muted');
        }
        counter.text('Sisa ' + remaining + ' karakter');
    });
    
    // Auto-resize textarea
    $('#feedback_deskripsi').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
