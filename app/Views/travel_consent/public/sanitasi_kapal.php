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
                    </div>
                </div>
            </div>
        </div>

        <div class="content pb-5">
            <div class="container-fluid" style="max-width: 1400px;">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle mr-1"></i> <strong><?= session()->getFlashdata('success') ?></strong>
                        </div>
                        <div>
                            <?php if(session()->getFlashdata('doc_link')): ?>
                                <button type="button" class="btn btn-sm btn-light font-weight-bold mr-3" onclick="$('#modalDocLink').modal('show')">
                                    <i class="fas fa-link mr-1 text-success"></i> Lihat / Copy Link Dokumen
                                </button>
                            <?php endif; ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('travelconsent/submit_sanitasi') ?>" method="POST" id="formSanitasi" enctype="multipart/form-data">
                    
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
                                        <option value="Pembebasan">Pembebasan</option>
                                        <option value="Pengendalian">Pengendalian</option>
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
                                    <?php
                                    $countries = [
                                        'Afganistan', 'Afrika Selatan', 'Afrika Tengah', 'Albania', 'Aljazair', 'Amerika Serikat', 'Andorra', 'Angola', 'Antigua dan Barbuda', 'Arab Saudi',
                                        'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahama', 'Bahrain', 'Bangladesh', 'Barbados', 'Belanda',
                                        'Belarus', 'Belgia', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia dan Herzegovina', 'Botswana', 'Brasil',
                                        'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Ceko', 'Chad', 'Chili', 'China', 'Cook Islands', 'Curacao',
                                        'Denmark', 'Djibouti', 'Dominika', 'Ekuador', 'El Salvador', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji',
                                        'Filipina', 'Finlandia', 'Gabon', 'Gambia', 'Georgia', 'Ghana', 'Gibraltar', 'Grenada', 'Guatemala', 'Guinea',
                                        'Guinea Khatulistiwa', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungaria', 'India', 'Indonesia', 'Inggris (Britania Raya)',
                                        'Irak', 'Iran', 'Irlandia', 'Islandia', 'Israel', 'Italia', 'Jamaika', 'Jepang', 'Jerman', 'Kamboja',
                                        'Kamerun', 'Kanada', 'Kazakhstan', 'Kenya', 'Kepulauan Cayman', 'Kepulauan Faroe', 'Kepulauan Marshall', 'Kepulauan Solomon', 'Kirgizstan', 'Kiribati',
                                        'Kolombia', 'Komoro', 'Kongo', 'Korea Selatan', 'Korea Utara', 'Kosta Rika', 'Kroasia', 'Kuba', 'Kuwait', 'Laos',
                                        'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lituania', 'Luksemburg', 'Madagaskar', 'Makau',
                                        'Makedonia Utara', 'Maladewa', 'Malawi', 'Malaysia', 'Mali', 'Malta', 'Maroko', 'Mauritania', 'Mauritius', 'Meksiko',
                                        'Mesir', 'Mikronesia', 'Moldova', 'Monako', 'Mongolia', 'Montenegro', 'Mozambik', 'Myanmar', 'Namibia', 'Nauru',
                                        'Nepal', 'Niger', 'Nigeria', 'Nikaragua', 'Norwegia', 'Oman', 'Pakistan', 'Palau', 'Palestina', 'Panama',
                                        'Pantai Gading', 'Papua Nugini', 'Paraguay', 'Prancis', 'Peru', 'Polandia', 'Portugal', 'Qatar', 'Republik Demokratik Kongo', 'Republik Dominika',
                                        'Rumania', 'Rusia', 'Rwanda', 'Saint Kitts dan Nevis', 'Saint Lucia', 'Saint Vincent dan Grenadines', 'Samoa', 'San Marino', 'Sao Tome dan Principe', 'Selandia Baru',
                                        'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapura', 'Siprus', 'Slovakia', 'Slovenia', 'Somalia', 'Spanyol',
                                        'Sri Lanka', 'Sudan', 'Sudan Selatan', 'Suriah', 'Suriname', 'Swedia', 'Swiss', 'Taiwan', 'Tajikistan', 'Tanjung Verde',
                                        'Tanzania', 'Thailand', 'Timor Leste', 'Togo', 'Tonga', 'Trinidad dan Tobago', 'Tunisia', 'Turki', 'Turkmenistan', 'Tuvalu',
                                        'Uganda', 'Ukraina', 'Uni Emirat Arab', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatikan', 'Venezuela', 'Vietnam', 'Yaman',
                                        'Yordania', 'Yunani', 'Zambia', 'Zimbabwe'
                                    ];
                                    ?>
                                    <select name="bendera" class="form-control select2">
                                        <option value="">-- Pilih Bendera --</option>
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?= $country ?>"><?= $country ?></option>
                                        <?php endforeach; ?>
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

                    <!-- BAGIAN 2: AREA / FASILITAS (TABEL LENGKAP) -->
                    <?php
                    $allAreas = [
                        'Dapur',
                        'Ruang rakit makanan',
                        'Gudang',
                        'Palka',
                        'Ruang tidur',
                        'ABK',
                        'Perwira',
                        'Penumpang',
                        'Geladak',
                        'Air bersih',
                        'Limbah cair',
                        'Tangki air Ballast',
                        'Sampah medik dan sampah padat',
                        'Air permukaan',
                        'Kamar mesin',
                        'Fasilitas medik',
                        'Area lainnya',
                        'Sumber Makanan',
                        'Penyimpanan Makanan',
                        'Penyiapan Makanan',
                        'Penyajian Makanan',
                        'Sumber Air',
                        'Penyimpanan Air',
                        'Distribusi Air',
                        'Penanganan Sampah',
                        'Perlakuan Sampah',
                        'Pembuangan Sampah',
                        'Pengoperasian Kolam Renang',
                        'Perlengkapan dan alat-alat medis',
                        'Pengoperasian Fasilitas Medik',
                        'Obat-obatan',
                        'Bagian lainnya yang diperiksa'
                    ];
                    ?>
                    <div class="card card-success">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <h3 class="card-title mb-1 mb-md-0"><i class="fas fa-tasks mr-2"></i>Bagian 2: Area / Fasilitas yang Diperiksa</h3>
                            <div class="card-tools ml-auto">
                                <span class="small mr-2">Atur Semua Status:</span>
                                <button type="button" class="btn btn-xs btn-light text-dark font-weight-bold mr-1" onclick="setAllAreaStatus('Tidak Ditemukan Faktor Risiko')">Tidak Ditemukan Faktor Risiko</button>
                                <button type="button" class="btn btn-xs btn-light text-dark font-weight-bold mr-1" onclick="setAllAreaStatus('Ditemukan Faktor Risiko')">Ditemukan Faktor Risiko</button>
                                <button type="button" class="btn btn-xs btn-light text-dark font-weight-bold mr-1" onclick="setAllAreaStatus('Tidak Ada')">Tidak Ada</button>
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="setAllAreaStatus('')">Kosongkan</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm mb-0" style="font-size: 13px;">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th class="align-middle" style="width: 40px;">No</th>
                                            <th class="align-middle text-left" style="min-width: 200px;">Area / Fasilitas yang Diperiksa</th>
                                            <th class="align-middle" style="min-width: 215px;">Status Pemeriksaan <span class="text-danger">*</span></th>
                                            <th class="align-middle" style="min-width: 145px;">Bukti yang Ditemukan</th>
                                            <th class="align-middle" style="min-width: 135px;">Hasil Sampel</th>
                                            <th class="align-middle" style="min-width: 145px;">Dokumen yang Diperiksa</th>
                                            <th class="align-middle" style="min-width: 150px;">Tindakan Pengendalian</th>
                                            <th class="align-middle" style="min-width: 135px;">Tgl Pemeriksaan Ulang</th>
                                            <th class="align-middle" style="min-width: 145px;">Komentar Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allAreas as $i => $areaName): $idx = $i + 1; ?>
                                        <tr>
                                            <td class="text-center align-middle font-weight-bold"><?= $idx ?></td>
                                            <td class="align-middle font-weight-bold">
                                                <?= esc($areaName) ?>
                                                <input type="hidden" name="areas[<?= $idx ?>][nama]" value="<?= esc($areaName) ?>">
                                            </td>
                                            <td class="align-middle">
                                                <select name="areas[<?= $idx ?>][status]" class="form-control form-control-sm area-status-select" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Tidak Ditemukan Faktor Risiko" selected>Tidak Ditemukan Faktor Risiko</option>
                                                    <option value="Ditemukan Faktor Risiko">Ditemukan Faktor Risiko</option>
                                                    <option value="Tidak Ada">Tidak Ada</option>
                                                </select>
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $idx ?>][bukti]" class="form-control form-control-sm" placeholder="Bukti...">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $idx ?>][sampel]" class="form-control form-control-sm" placeholder="Hasil sampel...">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $idx ?>][dokumen]" class="form-control form-control-sm" placeholder="Nama dokumen...">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $idx ?>][tindakan]" class="form-control form-control-sm" placeholder="Tindakan...">
                                            </td>
                                            <td class="align-middle">
                                                <input type="date" name="areas[<?= $idx ?>][tanggal_ulang]" class="form-control form-control-sm">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $idx ?>][komentar]" class="form-control form-control-sm" placeholder="Komentar...">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
                                        <i class="fas fa-eraser mr-1"></i> Hapus Tanda Tangan
                                    </button>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="font-weight-bold"><i class="fas fa-camera mr-1 text-primary"></i> Unggah Foto Geotagging</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="foto_geotagging" id="foto_geotagging" accept="image/*" onchange="previewGeotag(this, 'geotag_preview')">
                                        <label class="custom-file-label" for="foto_geotagging">Pilih file foto geotagging...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG (Maks 10MB). Pastikan foto memuat stempel geotag/koordinat lokasi & waktu.</small>
                                    <div id="geotag_preview_wrapper" class="mt-3 text-center" style="display:none;">
                                        <p class="mb-1 text-muted small font-weight-bold">Pratinjau Foto Geotagging:</p>
                                        <img id="geotag_preview" src="" alt="Pratinjau Geotagging" class="img-thumbnail shadow-sm" style="max-height: 450px; max-width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STICKY FOOTER -->
                    <div class="sticky-footer d-flex justify-content-between align-items-center flex-wrap rounded mt-4">
                        <a href="<?= base_url('travelconsent/home') ?>" class="btn btn-secondary px-4">Batal</a>
                        <div class="d-flex align-items-center mt-2 mt-sm-0">
                            <span id="statusProgressInfo" class="mr-3 small font-weight-bold text-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i>Status Pemeriksaan: <span id="statusFilledCount">0</span>/<?= count($allAreas) ?> terisi (Wajib diisi semua)
                            </span>
                            <div id="submitBtnWrapper" class="d-inline-block" style="cursor: not-allowed;">
                                <button type="submit" id="btnSubmitSanitasi" class="btn btn-primary px-5 font-weight-bold" disabled style="pointer-events: none;">Submit form</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('doc_link')): ?>
<!-- Modal Popup Link Dokumen Publik -->
<div class="modal fade" id="modalDocLink" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Form Berhasil Disubmit</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3 text-success">
                    <i class="fas fa-file-alt fa-3x"></i>
                </div>
                <h6 class="font-weight-bold mb-2"><?= session()->getFlashdata('success') ?></h6>
                <p class="text-muted small mb-3">Salin link publik di bawah ini untuk melihat atau mencetak dokumen yang telah disubmit:</p>
                <div class="input-group">
                    <input type="text" id="publicDocLinkInput" class="form-control bg-light text-dark font-weight-bold" value="<?= esc(session()->getFlashdata('doc_link')) ?>" readonly>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-dark font-weight-bold" onclick="copySubmittedDocLink(this)">
                            <i class="fas fa-copy mr-1"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-light">
                <a href="<?= esc(session()->getFlashdata('doc_link')) ?>" target="_blank" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Dokumen
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- Select2 -->
<script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>

<script>
<?php if(session()->getFlashdata('doc_link')): ?>
$(document).ready(function() {
    $('#modalDocLink').modal('show');
});
<?php endif; ?>

function copySubmittedDocLink(btn) {
    var input = document.getElementById('publicDocLinkInput');
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value);
    } else {
        document.execCommand('copy');
    }
    var originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin!';
    setTimeout(function() {
        btn.innerHTML = originalHtml;
    }, 2000);
}

function previewGeotag(input, targetId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById(targetId);
            if (img) {
                img.src = e.target.result;
                var wrapper = document.getElementById(targetId + '_wrapper');
                if (wrapper) wrapper.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
        var label = input.nextElementSibling;
        if (label && label.classList.contains('custom-file-label')) {
            label.textContent = input.files[0].name;
        }
    }
}

function setAllAreaStatus(statusVal) {
    $('.area-status-select').val(statusVal).trigger('change');
}

function updateStatusProgress() {
    var total = $('.area-status-select').length;
    var filled = 0;
    $('.area-status-select').each(function() {
        if ($(this).val() !== '') {
            filled++;
            $(this).removeClass('is-invalid');
        }
    });

    var infoEl = $('#statusProgressInfo');
    var btnEl = $('#btnSubmitSanitasi');
    var wrapperEl = $('#submitBtnWrapper');

    if (filled === total && total > 0) {
        btnEl.prop('disabled', false).css('pointer-events', 'auto');
        wrapperEl.css('cursor', 'pointer');
        infoEl.removeClass('text-danger').addClass('text-success')
            .html('<i class="fas fa-check-circle mr-1"></i>Semua Status Pemeriksaan telah terisi (' + filled + '/' + total + ')');
    } else {
        btnEl.prop('disabled', true).css('pointer-events', 'none');
        wrapperEl.css('cursor', 'not-allowed');
        infoEl.removeClass('text-success').addClass('text-danger')
            .html('<i class="fas fa-exclamation-circle mr-1"></i>Status Pemeriksaan: <span id="statusFilledCount">' + filled + '</span>/' + total + ' terisi (Wajib diisi semua)');
    }
    return filled === total;
}

function highlightUnfilledStatus() {
    var total = $('.area-status-select').length;
    var filled = 0;
    var firstEmpty = null;

    $('.area-status-select').each(function() {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            if (!firstEmpty) firstEmpty = $(this);
        } else {
            $(this).removeClass('is-invalid');
            filled++;
        }
    });

    if (firstEmpty) {
        alert('Tombol Submit form belum dapat diproses karena semua Status Pemeriksaan wajib diisi terlebih dahulu! (Baru terisi ' + filled + ' dari ' + total + ' area)');
        $('html, body').animate({
            scrollTop: firstEmpty.offset().top - 150
        }, 300);
        firstEmpty.focus();
        return false;
    }
    return true;
}

$(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Monitor perubahan Status Pemeriksaan
        $(document).on('change', '.area-status-select', function() {
            updateStatusProgress();
        });

        // Klik pada tombol submit yang masih terkunci (disabled)
        $('#submitBtnWrapper').on('click', function(e) {
            if ($('#btnSubmitSanitasi').prop('disabled')) {
                e.preventDefault();
                highlightUnfilledStatus();
            }
        });

        updateStatusProgress();

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
            if (!highlightUnfilledStatus()) {
                e.preventDefault();
                return false;
            }
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