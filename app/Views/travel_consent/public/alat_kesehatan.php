<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DAFTAR PEMAKAIAN ALAT KESEHATAN KOTAK P3K DAN EMERGENSI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <style>
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat img { max-width: 100%; height: auto; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
<?php $is_pdf = $is_pdf ?? false; ?>
<div class="container mt-5 mb-5">
    <div class="kop-surat">
        <img src="/dist/img/Kop_BKK_bandung.png" alt="Kop Surat" onerror="this.onerror=null;this.src='/dist/img/Kop_BKK_bandung.jpg';">
    </div>
    
    <h5 class="text-center font-weight-bold">DAFTAR PEMAKAIAN ALAT KESEHATAN</h5>
    <h5 class="text-center font-weight-bold">KOTAK P3K DAN EMERGENSI</h5>
    <h6 class="text-center font-weight-bold mb-4">
        PENERBANGAN HAJI 
        <?php if($is_pdf): ?> <?= $alat['penerbangan_haji_h'] ?? '' ?> <?php else: ?><input type="text" name="penerbangan_haji_h" class="form-control d-inline text-center" style="width: 50px;" value="<?= $alat['penerbangan_haji_h'] ?? '' ?>"><?php endif; ?> H /
        <?php if($is_pdf): ?> <?= $alat['penerbangan_haji_m'] ?? '' ?> <?php else: ?><input type="text" name="penerbangan_haji_m" class="form-control d-inline text-center" style="width: 50px;" value="<?= $alat['penerbangan_haji_m'] ?? '' ?>"><?php endif; ?> M
    </h6>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-check-circle mr-1"></i> <strong><?= session()->getFlashdata('success') ?></strong>
            </div>
            <?php if(session()->getFlashdata('doc_link')): ?>
                <button type="button" class="btn btn-sm btn-light font-weight-bold" onclick="$('#modalDocLink').modal('show')">
                    <i class="fas fa-link mr-1 text-success"></i> Lihat / Copy Link Dokumen
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(!$is_pdf): ?><form action="/travelconsent/submit_alat" method="post" enctype="multipart/form-data"><?php endif; ?>
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="30%">Embarkasi / Debarkasi</td>
                        <td width="5%">:</td>
                        <td><?php if($is_pdf): ?> <?= $alat['embarkasi'] ?? '' ?> <?php else: ?><input type="text" name="embarkasi" class="form-control" value="<?= $alat['embarkasi'] ?? '' ?>"><?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Kloter</td>
                        <td>:</td>
                        <td><?php if($is_pdf): ?> <?= $alat['nomor_kloter'] ?? '' ?> <?php else: ?><input type="text" name="nomor_kloter" class="form-control" value="<?= $alat['nomor_kloter'] ?? '' ?>"><?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>No. Kontak</td>
                        <td>:</td>
                        <td><?php if($is_pdf): ?> <?= $alat['no_kontak'] ?? '' ?> <?php else: ?><input type="text" name="no_kontak" class="form-control" value="<?= $alat['no_kontak'] ?? '' ?>"><?php endif; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="text-center font-weight-bold mt-4 mb-3">ALAT KESEHATAN KOTAK P3K</h6>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">NO.</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th colspan="2">JUMLAH</th>
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th>ISI</th>
                    <th>TERPAKAI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $p3k = [
                    'Adhesive bandage compresses, 1-inch' => 'Pcs',
                    'Antiseptic swab' => 'Pcs',
                    'Ammonia inhalants' => 'Pcs',
                    'Bandage compresses, 4-inch' => 'Pcs',
                    'Triangular bandage compresses, 40-inch' => 'Pcs',
                    'Arm splint, noninflatable' => 'Pcs',
                    'Leg splint, noninflatable' => 'Pcs',
                    'Roller bandage, 4-inch' => 'Pcs',
                    'Adhesive tape, 1-inch standard roll' => 'Pcs',
                    'Bandage scissors' => 'Pcs',
                    'Skin closure strips' => 'Pcs',
                    'Disinfectant wipes' => 'Pcs',
                    'Burn dressings' => 'Pcs',
                    'Disposable gloves (several pairs)' => 'Pcs',
                    'Thermometer (non-mercury)' => 'Pcs',
                    'Resuscitation mask with one-way valve' => 'Pcs',
                    'Gauze / eye protection pad' => 'Pcs'
                ];
                $p3k_data = isset($alat['items_p3k']) && is_string($alat['items_p3k']) ? json_decode($alat['items_p3k'], true) : ($alat['items_p3k'] ?? []);
                $i = 1;
                foreach($p3k as $nama => $satuan): 
                    $item = $p3k_data[$i] ?? [];
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td class="text-left">
                        <?= $nama ?>
                        <?php if(!$is_pdf): ?><input type="hidden" name="items_p3k[<?= $i ?>][nama]" value="<?= $nama ?>"><?php endif; ?>
                    </td>
                    <td>
                        <?= $satuan ?>
                        <?php if(!$is_pdf): ?><input type="hidden" name="items_p3k[<?= $i ?>][satuan]" value="<?= $satuan ?>"><?php endif; ?>
                    </td>
                    <td><?php if($is_pdf): ?> <?= $item['isi'] ?? '' ?> <?php else: ?><input type="text" name="items_p3k[<?= $i ?>][isi]" class="form-control form-control-sm" value="<?= $item['isi'] ?? '' ?>"><?php endif; ?></td>
                    <td><?php if($is_pdf): ?> <?= $item['terpakai'] ?? '' ?> <?php else: ?><input type="text" name="items_p3k[<?= $i ?>][terpakai]" class="form-control form-control-sm" value="<?= $item['terpakai'] ?? '' ?>"><?php endif; ?></td>
                    <td><?php if($is_pdf): ?> <?= $item['keterangan'] ?? '' ?> <?php else: ?><input type="text" name="items_p3k[<?= $i ?>][keterangan]" class="form-control form-control-sm" value="<?= $item['keterangan'] ?? '' ?>"><?php endif; ?></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>

        <h6 class="text-center font-weight-bold mt-5 mb-3">ALAT KESEHATAN KOTAK EMERGENSI</h6>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">NO.</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th colspan="2">JUMLAH</th>
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th>ISI</th>
                    <th>TERPAKAI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $emergensi = [
                    'Stethoscope' => 'Pcs',
                    'Sphygmomanometer (electronic preferred)' => 'Pcs',
                    'Airways, oropharyngeal (three sizes)' => 'Pcs',
                    'Syringes (appropriate range of sizes)' => 'Pcs',
                    'Needles (appropriate range of sizes)' => 'Pcs',
                    'Intravenous catheters (appropriate range of sizes)' => 'Pcs',
                    'Antiseptic wipes' => 'Pcs',
                    'Gloves (disposable)' => 'Pcs',
                    'Needle disposal box' => 'Pcs',
                    'Urinary catheter' => 'Pcs',
                    'System for delivering intravenous fluids' => 'Pcs',
                    'Venous tourniquet' => 'Pcs',
                    'Sponge gauze' => 'Pcs',
                    'Tape-adhesive' => 'Pcs',
                    'Surgical mask' => 'Pcs',
                    'Emergency tracheal catheter (or large gauge intravenous cannula)' => 'Pcs',
                    'Umbilical cord clamp' => 'Pcs',
                    'Thermometers (non-mercury)' => 'Pcs',
                    'Basic life support card' => 'Pcs',
                    'Bag-valve mask' => 'Pcs',
                    'Flashlight and batteries' => 'Pcs'
                ];
                $emergensi_data = isset($alat['items_emergensi']) && is_string($alat['items_emergensi']) ? json_decode($alat['items_emergensi'], true) : ($alat['items_emergensi'] ?? []);
                $j = 1;
                foreach($emergensi as $nama => $satuan): 
                    $item = $emergensi_data[$j] ?? [];
                ?>
                <tr>
                    <td><?= $j ?></td>
                    <td class="text-left">
                        <?= $nama ?>
                        <?php if(!$is_pdf): ?><input type="hidden" name="items_emergensi[<?= $j ?>][nama]" value="<?= $nama ?>"><?php endif; ?>
                    </td>
                    <td>
                        <?= $satuan ?>
                        <?php if(!$is_pdf): ?><input type="hidden" name="items_emergensi[<?= $j ?>][satuan]" value="<?= $satuan ?>"><?php endif; ?>
                    </td>
                    <td><?php if($is_pdf): ?> <?= $item['isi'] ?? '' ?> <?php else: ?><input type="text" name="items_emergensi[<?= $j ?>][isi]" class="form-control form-control-sm" value="<?= $item['isi'] ?? '' ?>"><?php endif; ?></td>
                    <td><?php if($is_pdf): ?> <?= $item['terpakai'] ?? '' ?> <?php else: ?><input type="text" name="items_emergensi[<?= $j ?>][terpakai]" class="form-control form-control-sm" value="<?= $item['terpakai'] ?? '' ?>"><?php endif; ?></td>
                    <td><?php if($is_pdf): ?> <?= $item['keterangan'] ?? '' ?> <?php else: ?><input type="text" name="items_emergensi[<?= $j ?>][keterangan]" class="form-control form-control-sm" value="<?= $item['keterangan'] ?? '' ?>"><?php endif; ?></td>
                </tr>
                <?php $j++; endforeach; ?>
            </tbody>
        </table>
        
        <p class="mt-3">*Berdasarkan peraturan dari ICAO, Annex 6, 2018</p>

        <!-- Upload Foto Geotagging -->
        <?php if(!$is_pdf): ?>
        <div class="card mb-4 border-info shadow-sm">
            <div class="card-header bg-light py-2">
                <strong class="text-primary"><i class="fas fa-camera mr-1"></i> Foto Geotagging / Dokumentasi Pemeriksaan Alat Kesehatan</strong><br>
                <small class="text-muted">Geotagged First Aid Inspection Photo Documentation</small>
            </div>
            <div class="card-body">
                <div class="form-group mb-2">
                    <label for="foto_geotagging" class="font-weight-bold">Upload Foto Geotagging:</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_geotagging" id="foto_geotagging" accept="image/*" onchange="previewGeotag(this, 'geotag_preview')">
                        <label class="custom-file-label" for="foto_geotagging">Pilih file foto geotagging...</label>
                    </div>
                    <small class="form-text text-muted">Format: JPG, JPEG, PNG (Maks 10MB). Pastikan foto memuat stempel geotag/koordinat lokasi & waktu.</small>
                </div>
                <div id="geotag_preview_wrapper" class="mt-3 text-center" style="display:none;">
                    <p class="mb-1 text-muted small font-weight-bold">Preview Foto Geotagging:</p>
                    <img id="geotag_preview" src="" alt="Preview Geotagging" class="img-thumbnail shadow-sm" style="max-height: 450px; max-width: 100%;">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-5">
            <div class="col-6 text-center">
                <p>Mengetahui,<br>Crew Saudi Airlines</p>
                <?php if(!empty($alat['crew_ttd'])): ?>
                    <img src="<?= $alat['crew_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px;"><br>
                <?php else: ?>
                    <?php if(!$is_pdf): ?>
                    <canvas id="pad_crew" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padCrew.clear()">Clear Tanda Tangan</button>
                    <?php endif; ?>
                <?php endif; ?>
                <br><br>
                (<?php if($is_pdf): ?> <?= $alat['crew_nama'] ?? '' ?> <?php else: ?><input type="text" name="crew_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $alat['crew_nama'] ?? '' ?>"><?php endif; ?>)
                <?php if(!$is_pdf): ?><input type="hidden" name="crew_ttd" id="ttd_crew" value="<?= $alat['crew_ttd'] ?? '' ?>"><?php endif; ?>
            </div>
            <div class="col-6 text-center">
                <p><br>Pelapor,</p>
                <?php if(!empty($alat['pelapor_ttd'])): ?>
                    <img src="<?= $alat['pelapor_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px;"><br>
                <?php else: ?>
                    <?php if(!$is_pdf): ?>
                    <canvas id="pad_pelapor" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padPelapor.clear()">Clear Tanda Tangan</button>
                    <?php endif; ?>
                <?php endif; ?>
                <br><br>
                (<?php if($is_pdf): ?> <?= $alat['pelapor_nama'] ?? '' ?> <?php else: ?><input type="text" name="pelapor_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $alat['pelapor_nama'] ?? '' ?>"><?php endif; ?>)
                <?php if(!$is_pdf): ?><input type="hidden" name="pelapor_ttd" id="ttd_pelapor" value="<?= $alat['pelapor_ttd'] ?? '' ?>"><?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-5">
            <?php if(!$is_pdf): ?><button type="submit" class="btn btn-primary btn-lg d-print-none">Submit Form</button><?php endif; ?>
        </div>
    <?php if(!$is_pdf): ?></form><?php endif; ?>
</div>

<?php if(!$is_pdf && session()->getFlashdata('doc_link')): ?>
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

<?php if(!$is_pdf): ?>
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    <?php if(session()->getFlashdata('doc_link')): ?>
    $(document).ready(function() {
        $('#modalDocLink').modal('show');
    });
    <?php endif; ?>
    if(document.getElementById('pad_crew')) {
        var padCrew = new SignaturePad(document.getElementById('pad_crew'));
    }
    if(document.getElementById('pad_pelapor')) {
        var padPelapor = new SignaturePad(document.getElementById('pad_pelapor'));
    }

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

    document.querySelector('form').addEventListener('submit', function(e) {
        if (typeof padCrew !== 'undefined' && !padCrew.isEmpty()) {
            document.getElementById('ttd_crew').value = padCrew.toDataURL();
        }
        if (typeof padPelapor !== 'undefined' && !padPelapor.isEmpty()) {
            document.getElementById('ttd_pelapor').value = padPelapor.toDataURL();
        }
    });
</script>
<?php endif; ?>
</body>
</html>