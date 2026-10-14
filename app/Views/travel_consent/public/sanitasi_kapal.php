<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sanitasi Kapal</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
    <style>
        body { background-color: #f4f6f9; }
        .kop-surat { text-align: center; margin-bottom: 30px; }
        .kop-surat img { max-height: 100px; }
        .sticky-footer {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .card-area {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12 text-center mt-3">
                        <img src="<?= base_url('dist/img/Kop_BKK_bandung.png') ?>" alt="Kop Surat" style="max-width: 100%; height: auto;">
                        <h3 class="mt-4 font-weight-bold">SERTIFIKAT PENGENDALIAN SANITASI KAPAL</h3>
                        <p class="text-muted">Ship Sanitation Control Certificate</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content pb-5">
            <div class="container">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('travelconsent/submit_sanitasi') ?>" method="POST" id="formSanitasi">
                    
                    <!-- BAGIAN 1: INFORMASI UMUM -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Bagian 1: Informasi Umum</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Balai Karkes</label>
                                    <input type="text" class="form-control" value="Bandung / Bandung" disabled readonly>
                                    <input type="hidden" name="pelabuhan" value="Bandung / Bandung">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama Pelabuhan <span class="text-danger">*</span></label>
                                    <input type="text" name="pelabuhan" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Sertifikat ini mencatat pemeriksaan dan: <span class="text-danger">*</span></label>
                                    <select name="sertifikat_mencatat" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Pembebasan">Pembebasan (Exemption)</option>
                                        <option value="Pengendalian">Pengendalian (Control)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nomor Registrasi/IMO No <span class="text-danger">*</span></label>
                                    <input type="text" name="imo_no" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Jenis Kapal <span class="text-danger">*</span></label>
                                    <select name="jenis_kapal" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Penumpang">Penumpang</option>
                                        <option value="Kargo">Kargo</option>
                                        <option value="Tanker">Tangki/Tanker</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Detail Jenis Kapal <span class="text-danger">*</span></label>
                                    <select name="detail_jenis_kapal" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Domestik">Domestik</option>
                                        <option value="Internasional">Internasional</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama kapal atau kapal pelayaran antar pulau <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_kapal" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Bendera</label>
                                    <select name="bendera" class="form-control select2">
                                        <option value="">-- Pilih Bendera --</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Singapura">Singapura</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Berat(GT) (tanpa titik/koma) <span class="text-danger">*</span></label>
                                    <input type="number" name="berat_gt" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Pada waktu diperiksa, palka-palka <span class="text-danger">*</span></label>
                                    <select name="kondisi_palka" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Kosong">Kosong</option>
                                        <option value="Isi">Bersi/Isi muatan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama petugas pemeriksa <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_pemeriksa" class="form-control" required>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Alamat pemeriksa <span class="text-danger">*</span></label>
                                    <textarea name="alamat_pemeriksa" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: AREA / FASILITAS (DYNAMIC) -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Bagian 2: Area / Fasilitas yang Diperiksa</h3>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-8">
                                    <label>Pilih Area/Fasilitas yang Diperiksa</label>
                                    <select id="areaSelector" class="form-control select2">
                                        <option value="">-- Pilih Area --</option>
                                        <option value="Dapur">Dapur</option>
                                        <option value="Ruang rakit makanan">Ruang rakit makanan</option>
                                        <option value="Gudang">Gudang</option>
                                        <option value="Palka">Palka</option>
                                        <option value="Ruang tidur">Ruang tidur</option>
                                        <option value="ABK">ABK</option>
                                        <option value="Perwira">Perwira</option>
                                        <option value="Penumpang">Penumpang</option>
                                        <option value="Geladak">Geladak</option>
                                        <option value="Air bersih">Air bersih</option>
                                        <option value="Limbah cair">Limbah cair</option>
                                        <option value="Tangki air Ballast">Tangki air Ballast</option>
                                        <option value="Sampah medik dan sampah padat">Sampah medik dan sampah padat</option>
                                        <option value="Air permukaan">Air permukaan</option>
                                        <option value="Kamar mesin">Kamar mesin</option>
                                        <option value="Fasilitas medik">Fasilitas medik</option>
                                        <option value="Area lainnya">Area lainnya</option>
                                        <option value="Sumber Makanan">Sumber Makanan</option>
                                        <option value="Penyimpanan Makanan">Penyimpanan Makanan</option>
                                        <option value="Penyiapan Makanan">Penyiapan Makanan</option>
                                        <option value="Penyajian Makanan">Penyajian Makanan</option>
                                        <option value="Sumber Air">Sumber Air</option>
                                        <option value="Penyimpanan Air">Penyimpanan Air</option>
                                        <option value="Distribusi Air">Distribusi Air</option>
                                        <option value="Penanganan Sampah">Penanganan Sampah</option>
                                        <option value="Perlakuan Sampah">Perlakuan Sampah</option>
                                        <option value="Pembuangan Sampah">Pembuangan Sampah</option>
                                        <option value="Pengoperasian Kolam Renang">Pengoperasian Kolam Renang</option>
                                        <option value="Perlengkapan dan alat-alat medis">Perlengkapan dan alat-alat medis</option>
                                        <option value="Pengoperasian Fasilitas Medik">Pengoperasian Fasilitas Medik</option>
                                        <option value="Obat-obatan">Obat-obatan</option>
                                        <option value="Bagian lainnya yang diperiksa">Bagian lainnya yang diperiksa</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="btnAddArea" class="btn btn-primary btn-block"><i class="fas fa-plus mr-2"></i>Tambah Area</button>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Container for Dynamic Cards -->
                            <div id="dynamicAreaContainer">
                                <!-- Cards will be appended here via jQuery -->
                            </div>
                            
                        </div>
                    </div>

                    <!-- BAGIAN 3: KETERANGAN & SUBMIT -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Bagian 3: Keterangan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Nama pejabat dan instansi yang menerbitkan</label>
                                    <input type="text" name="pejabat_penerbit" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Tanda Tangan Pejabat Penerbit <span class="text-danger">*</span></label>
                                    <div class="border rounded bg-white" style="position: relative; touch-action: none;">
                                        <canvas id="signatureCanvas" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
                                    </div>
                                    <input type="hidden" name="tanda_tangan" id="tanda_tangan" required>
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="btnClearSignature">
                                        <i class="fas fa-eraser mr-1"></i> Clear Tanda Tangan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STICKY FOOTER -->
                    <div class="sticky-footer d-flex justify-content-between align-items-center rounded mt-4">
                        <a href="<?= base_url('travelconsent/home') ?>" class="btn btn-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold">Simpan Sertifikat</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- Select2 -->
<script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        let areaCount = 0;

        // Function to add new Area Card
        $('#btnAddArea').click(function() {
            let areaName = $('#areaSelector').val();
            
            if(!areaName) {
                alert('Silakan pilih Area/Fasilitas terlebih dahulu!');
                return;
            }

            areaCount++;
            let index = areaCount; // Unique index for array

            let cardHtml = `
                <div class="card card-outline card-info card-area" id="area-card-${index}">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">${areaName}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-danger btn-remove-area" data-id="${index}">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="areas[${index}][nama]" value="${areaName}">
                        
                        <div class="form-group mb-4 p-3 bg-light rounded border">
                            <label class="d-block mb-2">Status Pemeriksaan: <span class="text-danger">*</span></label>
                            <div class="icheck-primary d-inline mr-4">
                                <input type="radio" id="status_ya_${index}" name="areas[${index}][status]" value="Ya" required>
                                <label for="status_ya_${index}">Ya</label>
                            </div>
                            <div class="icheck-primary d-inline mr-4">
                                <input type="radio" id="status_tidak_${index}" name="areas[${index}][status]" value="Tidak Ditemukan">
                                <label for="status_tidak_${index}">Tidak Ditemukan</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="status_na_${index}" name="areas[${index}][status]" value="N/A">
                                <label for="status_na_${index}">Tidak ada (N/A)</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Bukti yang ditemukan</label>
                                <input type="text" name="areas[${index}][bukti]" class="form-control" placeholder="Tuliskan bukti...">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Hasil sampel</label>
                                <input type="text" name="areas[${index}][sampel]" class="form-control" placeholder="Tuliskan hasil...">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Dokumen yang diperiksa</label>
                                <input type="text" name="areas[${index}][dokumen]" class="form-control" placeholder="Nama dokumen...">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tindakan Pengendalian yang dilakukan</label>
                                <input type="text" name="areas[${index}][tindakan]" class="form-control" placeholder="Tindakan...">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tanggal pemeriksaan ulang</label>
                                <input type="date" name="areas[${index}][tanggal_ulang]" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Komentar terhadap kondisi yang ditemukan</label>
                                <input type="text" name="areas[${index}][komentar]" class="form-control" placeholder="Komentar...">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#dynamicAreaContainer').append(cardHtml);
            
            // Reset dropdown
            $('#areaSelector').val('').trigger('change');
        });

        // Delegate event for dynamic remove buttons
        $('#dynamicAreaContainer').on('click', '.btn-remove-area', function() {
            let id = $(this).data('id');
            if(confirm('Yakin ingin menghapus area ini dari form?')) {
                $('#area-card-' + id).fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });

        // --- SIGNATURE CANVAS SCRIPT ---
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        const inputSignature = document.getElementById('tanda_tangan');
        const btnClear = document.getElementById('btnClearSignature');
        
        let isDrawing = false;

        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function getPointerPos(e) {
            const rect = canvas.getBoundingClientRect();
            let clientX, clientY;
            
            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }
            
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            const pos = getPointerPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPointerPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.stroke();
            e.preventDefault();
        }

        function stopDrawing(e) {
            if (isDrawing) {
                isDrawing = false;
                inputSignature.value = canvas.toDataURL('image/png');
            }
        }

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Touch events
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        // Clear button
        btnClear.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            inputSignature.value = '';
        });

        // Form Submit Validation
        $('#formSanitasi').on('submit', function(e) {
            if (!inputSignature.value) {
                e.preventDefault();
                alert('Silakan isi tanda tangan terlebih dahulu!');
                canvas.focus();
            }
        });
    });
</script>
</body>
</html>