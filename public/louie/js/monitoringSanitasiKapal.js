// Global variables
let tableSanitasiKapal;
let isEditMode = false;
let currentEditId = null;

$(document).ready(function () {
    // Initialize date range picker for filter
    if (typeof $.fn.daterangepicker === 'function') {
        $('#dateFilterSanitasi').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: function() {
                const today = new Date();
                const pastDate = new Date(today);
                pastDate.setMonth(today.getMonth() - 2);
                return pastDate;
            }(),
            endDate: function() {
                const today = new Date();
                const futureDate = new Date(today);
                futureDate.setMonth(today.getMonth() + 1);
                return futureDate;
            }()
        });
    } else {
        console.error('DateRangePicker plugin is not loaded');
        // Fallback: set default date values
        const today = new Date();
        const pastDate = new Date(today);
        pastDate.setMonth(today.getMonth() - 2);
        
        const futureDate = new Date(today);
        futureDate.setMonth(today.getMonth() + 1);
        
        $('#dateFilterSanitasi').val(pastDate.toISOString().split('T')[0] + ' - ' + futureDate.toISOString().split('T')[0]);
    }

    // Initialize form
    initializeForm();
    
    // Load initial data
    loadSanitasiData();
    
    // Load wilker options for filter
    loadWilkerOptions();

    // Event handlers
    setupEventHandlers();
});

// Get user data from server
function getUserData() {
    $.ajax({
        url: '/simply/monitoring_sanitasi_kapal/user_data',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#wilker').val(response.data.nama || 'User Name');
            }
        },
        error: function() {
            $('#wilker').val('User Name');
        }
    });
}

function initializeForm() {
    // Set tanggal to today
    const today = new Date().toISOString().split('T')[0];
    $('#tanggal').val(today);
    
    // Initialize file input labels
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        
        // Handle image preview for bukti geotagging
        if (this.id === 'buktiGeotagging' && this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreviewBukti').attr('src', e.target.result);
                $('#previewBukti').show();
            };
            reader.readAsDataURL(this.files[0]);
        }
        
        // Handle document preview for laporan tindakan
        if (this.id === 'laporanTindakan' && this.files && this.files[0]) {
            $('#namaFileLaporan').text(this.files[0].name);
            $('#previewLaporan').show();
        }
    });
}

function setupEventHandlers() {
    // Form submission
    $('#formSanitasiKapal').on('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    // Modal events
    $('#modalSanitasiKapal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Filter events
    $('#dateFilterSanitasi').on('change', function() {
        loadSanitasiData();
    });

    $('#filterWilker').on('change', function() {
        loadSanitasiData();
    });

    // Button events
    $('#btnRefreshData').on('click', function() {
        loadSanitasiData();
    });

    $('#btnExportSanitasi').on('click', function() {
        exportToExcel();
    });

    // File validation
    $('#buktiGeotagging').on('change', function() {
        validateImageFile(this);
    });

    $('#laporanTindakan').on('change', function() {
        validateDocumentFile(this);
    });
}

function validateImageFile(input) {
    const file = input.files[0];
    if (file) {
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!allowedTypes.includes(file.type)) {
            showToast('error', 'Format file tidak valid! Gunakan PNG, JPG, atau JPEG.');
            $(input).val('');
            $(input).siblings('.custom-file-label').removeClass('selected').html('Pilih file gambar...');
            $('#previewBukti').hide();
            return false;
        }

        if (file.size > maxSize) {
            showToast('error', 'Ukuran file terlalu besar! Maksimal 5MB.');
            $(input).val('');
            $(input).siblings('.custom-file-label').removeClass('selected').html('Pilih file gambar...');
            $('#previewBukti').hide();
            return false;
        }
    }
    return true;
}

function validateDocumentFile(input) {
    const file = input.files[0];
    if (file) {
        const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            showToast('error', 'Format file tidak valid! Gunakan PDF atau DOCX.');
            $(input).val('');
            $(input).siblings('.custom-file-label').removeClass('selected').html('Pilih file dokumen...');
            $('#previewLaporan').hide();
            return false;
        }

        if (file.size > maxSize) {
            showToast('error', 'Ukuran file terlalu besar! Maksimal 10MB.');
            $(input).val('');
            $(input).siblings('.custom-file-label').removeClass('selected').html('Pilih file dokumen...');
            $('#previewLaporan').hide();
            return false;
        }
    }
    return true;
}

function submitForm() {
    // Validate required fields
    if (!validateForm()) {
        return;
    }

    const formData = new FormData($('#formSanitasiKapal')[0]);
    const url = isEditMode ? '/monitoring_sanitasi_kapal/update' : '/monitoring_sanitasi_kapal/save';
    
    if (isEditMode) {
        formData.append('id', currentEditId);
    }

    // Show loading state
    $('#btnSimpan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Data berhasil disimpan!');
                $('#modalSanitasiKapal').modal('hide');
                loadSanitasiData();
            } else {
                showToast('error', response.message || 'Gagal menyimpan data!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan saat menyimpan data!');
        },
        complete: function() {
            $('#btnSimpan').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Data');
        }
    });
}

function validateForm() {
    let isValid = true;
    let errorMessages = [];
    const requiredFields = ['tanggal', 'wilker', 'namaKapal'];
    
    // Basic field validation
    requiredFields.forEach(function(fieldId) {
        const field = $('#' + fieldId);
        if (!field.val()) {
            field.addClass('is-invalid');
            isValid = false;
            
            // Add specific error message
            const fieldName = field.prev('label').text().replace('*', '').trim();
            errorMessages.push(`${fieldName} harus diisi`);
        } else {
            field.removeClass('is-invalid');
        }
    });

    // File validation for bukti geotagging
    const buktiField = $('#buktiGeotagging');
    const buktiLabel = buktiField.siblings('.custom-file-label');
    const hasExistingBukti = buktiField.data('existing-file');
    
    if (!isEditMode) {
        // For new entries, bukti geotagging is required
        if (!buktiField[0].files.length) {
            buktiField.addClass('is-invalid');
            isValid = false;
            errorMessages.push('Bukti Geotagging harus diupload');
        } else {
            buktiField.removeClass('is-invalid');
            // Validate the uploaded file
            if (!validateImageFile(buktiField[0])) {
                isValid = false;
            }
        }
    } else {
        // For edit mode, bukti geotagging is optional if already exists
        if (!buktiField[0].files.length && !hasExistingBukti) {
            // No new file and no existing file
            buktiField.addClass('is-invalid');
            isValid = false;
            errorMessages.push('Bukti Geotagging harus diupload');
        } else {
            buktiField.removeClass('is-invalid');
            // Only validate if user is trying to upload a new file
            if (buktiField[0].files.length > 0) {
                // Validate the new file if uploaded
                if (!validateImageFile(buktiField[0])) {
                    isValid = false;
                }
            }
        }
    }

    // Laporan tindakan is always optional, but validate if uploaded
    const laporanField = $('#laporanTindakan');
    if (laporanField[0].files.length > 0) {
        if (!validateDocumentFile(laporanField[0])) {
            isValid = false;
        }
    }
    laporanField.removeClass('is-invalid');

    if (!isValid) {
        if (errorMessages.length > 0) {
            showToast('error', errorMessages.join('<br>'));
        } else {
            showToast('error', 'Mohon periksa kembali data yang diisi!');
        }
    }

    return isValid;
}

function loadSanitasiData() {
    const dateRange = $('#dateFilterSanitasi').val();
    const wilker = $('#filterWilker').val();

    // Show loading state
    $('#dataSanitasiKapal').html(`
        <tr>
            <td colspan="7" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data sanitasi kapal...</p>
            </td>
        </tr>
    `);

    $.ajax({
        url: '/monitoring_sanitasi_kapal/data',
        method: 'GET',
        data: {
            dateRange: dateRange,
            wilker: wilker
        },
        success: function(response) {
            if (response.success) {
                renderTableData(response.data);
            } else {
                $('#dataSanitasiKapal').html(`
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${response.message || 'Gagal memuat data'}
                        </td>
                    </tr>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', error);
            $('#dataSanitasiKapal').html(`
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        <i class="fas fa-times-circle"></i>
                        Terjadi kesalahan saat memuat data
                    </td>
                </tr>
            `);
        }
    });
}

function renderTableData(data) {
    let html = '';
    
    if (data && data.length > 0) {
        data.forEach(function(item, index) {
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${formatDate(item.tanggal)}</td>
                    <td class="d-none">${item.wilker}</td>
                    <td>${item.unit_kerja || '-'}</td>
                    <td>${item.nama_kapal}</td>
                    <td class="text-center">
                        ${item.bukti_geotagging ? 
                            `<button class="btn btn-info btn-action" onclick="viewImage('${item.bukti_geotagging}')">
                                <i class="fas fa-image"></i> Lihat
                            </button>` : 
                            '<span class="text-muted">-</span>'
                        }
                    </td>
                    <td class="text-center">
                        ${item.laporan_tindakan ? 
                            `<button class="btn btn-success btn-action" onclick="downloadFile('${item.laporan_tindakan}')">
                                <i class="fas fa-download"></i> Download
                            </button>` : 
                            '<span class="text-muted">-</span>'
                        }
                    </td>
                    <td>
                        ${item.jenis_dokumen ? item.jenis_dokumen.split(',').map(d => `<span class="badge badge-info mr-1">${d}</span>`).join('') : '<span class="text-muted">-</span>'}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-action" onclick="editData(${item.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-action" onclick="deleteData(${item.id})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    <i class="fas fa-inbox"></i>
                    <p class="mt-2">Tidak ada data sanitasi kapal</p>
                </td>
            </tr>
        `;
    }
    
    $('#dataSanitasiKapal').html(html);
}

function loadWilkerOptions() {
    $.ajax({
        url: '/monitoring_sanitasi_kapal/wilker_options',
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                let options = '<option value="">Semua Unit Kerja</option>';
                response.data.forEach(function(wilkerData) {
                    options += `<option value="${wilkerData.unit_kerja}">${wilkerData.unit_kerja}</option>`;
                });
                $('#filterWilker').html(options);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading wilker options:', error);
        }
    });
}

function resetForm() {
    $('#formSanitasiKapal')[0].reset();
    $('.custom-file-label').removeClass('selected').css('color', '').html('Pilih file...');
    $('#previewBukti, #previewLaporan').hide();
    $('.is-invalid').removeClass('is-invalid');
    
    // Clear stored file data
    $('#buktiGeotagging').removeData('existing-file');
    $('#laporanTindakan').removeData('existing-file');
    
    // Reset file labels to default
    $('#buktiGeotagging').siblings('.custom-file-label').html('Pilih file gambar...');
    $('#laporanTindakan').siblings('.custom-file-label').html('Pilih file dokumen...');
    
    // Reset to add mode
    isEditMode = false;
    currentEditId = null;
    $('#modalTitle').text('Tambah Data Sanitasi Kapal');
    
    // Reset checkboxes
    $('input[name="jenis_dokumen[]"]').prop('checked', false);

    // Reset tanggal to today
    const today = new Date().toISOString().split('T')[0];
    $('#tanggal').val(today);
}

function editData(id) {
    isEditMode = true;
    currentEditId = id;
    $('#modalTitle').text('Edit Data Sanitasi Kapal');
    
    // Load data for editing
    $.ajax({
        url: `/monitoring_sanitasi_kapal/edit/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#tanggal').val(data.tanggal);
                $('#wilker').val(data.wilker);
                $('#unit_kerja').val(data.unit_kerja);
                $('#namaKapal').val(data.nama_kapal);
                
                // Reset file inputs and previews
                $('#buktiGeotagging').val('');
                $('#laporanTindakan').val('');
                $('#previewBukti, #previewLaporan').hide();

                // Reset Checkboxes
                $('input[name="jenis_dokumen[]"]').prop('checked', false);
                if (data.jenis_dokumen) {
                    const jenisDokumen = data.jenis_dokumen.split(',');
                    jenisDokumen.forEach(val => {
                        $(`input[name="jenis_dokumen[]"][value="${val}"]`).prop('checked', true);
                    });
                }
                
                // Show existing files info with better labels
                if (data.bukti_geotagging) {
                    const buktiFileName = data.bukti_geotagging.split('/').pop() || data.bukti_geotagging;
                    $('#buktiGeotagging').siblings('.custom-file-label')
                        .addClass('selected')
                        .css('color', '#28a745')
                        .html(`<i class="fas fa-check-circle"></i> File saat ini: ${buktiFileName} <small>(Pilih file baru untuk mengganti)</small>`);
                    
                    // Store existing file info for validation
                    $('#buktiGeotagging').data('existing-file', data.bukti_geotagging);
                } else {
                    $('#buktiGeotagging').siblings('.custom-file-label')
                        .removeClass('selected')
                        .css('color', '')
                        .html('Pilih file gambar...');
                    
                    $('#buktiGeotagging').removeData('existing-file');
                }
                
                if (data.laporan_tindakan) {
                    const laporanFileName = data.laporan_tindakan.split('/').pop() || data.laporan_tindakan;
                    $('#laporanTindakan').siblings('.custom-file-label')
                        .addClass('selected')
                        .css('color', '#28a745')
                        .html(`<i class="fas fa-check-circle"></i> File saat ini: ${laporanFileName} <small>(Pilih file baru untuk mengganti)</small>`);
                    
                    // Store existing file info for validation
                    $('#laporanTindakan').data('existing-file', data.laporan_tindakan);
                } else {
                    $('#laporanTindakan').siblings('.custom-file-label')
                        .removeClass('selected')
                        .css('color', '')
                        .html('Pilih file dokumen...');
                    
                    $('#laporanTindakan').removeData('existing-file');
                }
                
                $('#modalSanitasiKapal').modal('show');
            } else {
                showToast('error', 'Gagal memuat data untuk edit!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading edit data:', error);
            showToast('error', 'Terjadi kesalahan saat memuat data!');
        }
    });
}

function deleteData(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/monitoring_sanitasi_kapal/delete/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Data berhasil dihapus!');
                        loadSanitasiData();
                    } else {
                        showToast('error', response.message || 'Gagal menghapus data!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting data:', error);
                    showToast('error', 'Terjadi kesalahan saat menghapus data!');
                }
            });
        }
    });
}



function viewImage(filename) {
    const imageUrl = `/${filename}`;
    Swal.fire({
        title: 'Bukti Geotagging',
        imageUrl: imageUrl,
        imageWidth: 600,
        imageHeight: 400,
        imageAlt: 'Bukti Geotagging',
        showCloseButton: true,
        showConfirmButton: false
    });
}

function downloadFile(filename) {
    const fileUrl = `/${filename}`;
    window.open(fileUrl, '_blank');
}

function exportToExcel() {
    const dateRange = $('#dateFilterSanitasi').val();
    const wilker = $('#filterWilker').val();
    
    let url = '/monitoring_sanitasi_kapal/export_excel';
    const params = [];
    
    if (dateRange) params.push(`dateRange=${encodeURIComponent(dateRange)}`);
    if (wilker) params.push(`wilker=${encodeURIComponent(wilker)}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.open(url, '_blank');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function showToast(type, message) {
    // Using SweetAlert2 toast
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}