<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR PEMAKAIAN OBAT KOTAK P3K DAN EMERGENSI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <style>
        body { font-size: 14px; background-color: #fff; }
        .kop-surat { text-align: center; margin-bottom: 16px; }
        .kop-surat img { max-width: 100%; height: auto; }
        .table th, .table td { vertical-align: middle; padding: 0.4rem; }
        .borderless td, .borderless th { border: none; padding: 0.2rem; }
        .table-items input.form-control { min-width: 0; width: 100%; text-align: center; }
        .sig-img { max-width: 100%; width: 240px; height: 130px; object-fit: contain; border: 1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto; }
        
        <?php if(!empty($is_print)): ?>
        @media print {
            .form-control { border: none !important; box-shadow: none !important; background: transparent !important; }
            .d-print-none { display: none !important; }
        }
        .form-control { border: none !important; background: transparent !important; pointer-events: none; height: auto; padding: 0.1rem; }
        <?php endif; ?>

        @media screen and (max-width: 767.98px) {
            body { font-size: 12px; }
            .doc-container { padding-left: 8px !important; padding-right: 8px !important; margin-top: 10px !important; margin-bottom: 20px !important; }
            .kop-surat { margin-bottom: 10px; }
            h5 { font-size: 13.5px; margin-bottom: 2px; }
            h6 { font-size: 12.5px; margin-top: 12px !important; margin-bottom: 10px !important; }
            .table th, .table td { padding: 0.25rem 0.2rem; font-size: 11px; word-break: break-word; }
            .table-items input.form-control { padding: 0.1rem !important; font-size: 11px !important; height: auto !important; }
            .table-meta td { padding: 2px 4px !important; font-size: 11.5px; }
            .table-meta input.form-control { font-size: 11.5px; height: auto; padding: 2px 4px; }
            .sig-img { width: 135px; height: 80px; }
            .sig-name-input { width: 85% !important; font-size: 11px !important; }
        }
    </style>
</head>
<body>
<div class="container mt-4 mb-5 doc-container">
    <?php if(!empty($is_print)): ?>
    <div class="text-right mb-3 d-print-none">
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print mr-1"></i> Cetak / Simpan PDF
        </button>
    </div>
    <?php endif; ?>
    <div class="kop-surat">
        <img src="/dist/img/Kop_BKK_bandung.png" alt="Kop Surat" onerror="this.onerror=null;this.src='/dist/img/Kop_BKK_bandung.jpg';">
    </div>
    
    <h5 class="text-center font-weight-bold">DAFTAR PEMAKAIAN OBAT</h5>
    <h5 class="text-center font-weight-bold">KOTAK P3K DAN EMERGENSI</h5>
    <h6 class="text-center font-weight-bold mb-4">
        PENERBANGAN HAJI 
        <input type="text" name="penerbangan_haji_h" class="form-control d-inline text-center" style="width: 50px;" value="<?= $obat['penerbangan_haji_h'] ?? '' ?>"> H /
        <input type="text" name="penerbangan_haji_m" class="form-control d-inline text-center" style="width: 50px;" value="<?= $obat['penerbangan_haji_m'] ?? '' ?>"> M
    </h6>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="/travelconsent/update_obat/<?= $obat['id'] ?>" method="post" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col-md-8 offset-md-2">
                <table class="table table-sm table-borderless table-meta mb-0">
                    <tr>
                        <td width="35%"><strong>Embarkasi / Debarkasi</strong></td>
                        <td width="3%">:</td>
                        <td><input type="text" name="embarkasi" class="form-control" value="<?= $obat['embarkasi'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Kloter</strong></td>
                        <td>:</td>
                        <td><input type="text" name="nomor_kloter" class="form-control" value="<?= $obat['nomor_kloter'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td><strong>No. Kontak</strong></td>
                        <td>:</td>
                        <td><input type="text" name="no_kontak" class="form-control" value="<?= $obat['no_kontak'] ?? '' ?>"></td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="text-center font-weight-bold mt-4 mb-3">PEMAKAIAN OBAT KOTAK P3K</h6>
        <table class="table table-bordered table-items text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2" width="6%">NO.</th>
                    <th rowspan="2" width="38%">NAMA BARANG</th>
                    <th rowspan="2" width="12%">SATUAN</th>
                    <th colspan="2" width="26%">JUMLAH</th>
                    <th rowspan="2" width="18%">KETERANGAN</th>
                </tr>
                <tr>
                    <th width="13%">ISI</th>
                    <th width="13%">TERPAKAI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $p3k = [
                    'Mild to moderate analgesic' => 'Tablet',
                    'Antiemetic' => 'Ampul / Tablet',
                    'Nasal decongestant' => 'Tablet / Spray',
                    'Antacid (gastric)' => 'Tablet',
                    'Antidiarrheal' => 'Tablet',
                    'Antihistamine' => 'Tablet'
                ];
                $p3k_data = isset($obat['items_p3k']) && is_string($obat['items_p3k']) ? json_decode($obat['items_p3k'], true) : ($obat['items_p3k'] ?? []);
                $i = 1;
                foreach($p3k as $nama => $satuan): 
                    $item = $p3k_data[$i] ?? [];
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td class="text-left">
                        <?= $nama ?>
                        <input type="hidden" name="items_p3k[<?= $i ?>][nama]" value="<?= $nama ?>">
                    </td>
                    <td>
                        <?= $satuan ?>
                        <input type="hidden" name="items_p3k[<?= $i ?>][satuan]" value="<?= $satuan ?>">
                    </td>
                    <td><input type="text" name="items_p3k[<?= $i ?>][isi]" class="form-control form-control-sm" value="<?= $item['isi'] ?? '' ?>"></td>
                    <td><input type="text" name="items_p3k[<?= $i ?>][terpakai]" class="form-control form-control-sm" value="<?= $item['terpakai'] ?? '' ?>"></td>
                    <td><input type="text" name="items_p3k[<?= $i ?>][keterangan]" class="form-control form-control-sm" value="<?= $item['keterangan'] ?? '' ?>"></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>

        <h6 class="text-center font-weight-bold mt-4 mb-3">PEMAKAIAN OBAT KOTAK EMERGENSI</h6>
        <table class="table table-bordered table-items text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2" width="6%">NO.</th>
                    <th rowspan="2" width="38%">NAMA BARANG</th>
                    <th rowspan="2" width="12%">SATUAN</th>
                    <th colspan="2" width="26%">JUMLAH</th>
                    <th rowspan="2" width="18%">KETERANGAN</th>
                </tr>
                <tr>
                    <th width="13%">ISI</th>
                    <th width="13%">TERPAKAI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $emergensi = [
                    'Epinephrine 1:1000, 1 cc, injectable, (single dose ampule or equivalent)' => 'Ampul',
                    'Antihistamine injectable, 50 mg, (single dose ampule or equivalent)' => 'Ampul',
                    'Dextrose, 50%/50 cc injectable, (single dose ampule or equivalent)' => 'Ampul',
                    'Nitroglycerin tablets, 0.4 mg, or spray' => 'Tablet / Spray',
                    'Major analgesic' => 'Tablet',
                    'Sedative Anticonvulsant' => 'Ampul',
                    'Antiemetic injectable' => 'Ampul',
                    'Bronchial dilator, inhaled (metered dose inhale or equivalent)' => 'Tabung',
                    'Atropine, 0.5 mg, 5 cc(single dose ampule or equivalent)' => 'Ampul',
                    'Adrenocortical steroid' => 'Ampul',
                    'Diuretic' => 'Ampul',
                    'Medication for postpartum bleeding' => 'Pcs',
                    'Sodium Chloride 0.9% (minimum 250 ml)' => 'Pcs',
                    'Acetyl salicylic acid (aspirin) for oral use' => 'Tablet',
                    'Oral beta blocker' => 'Tablet'
                ];
                $emergensi_data = isset($obat['items_emergensi']) && is_string($obat['items_emergensi']) ? json_decode($obat['items_emergensi'], true) : ($obat['items_emergensi'] ?? []);
                $j = 1;
                foreach($emergensi as $nama => $satuan): 
                    $item = $emergensi_data[$j] ?? [];
                ?>
                <tr>
                    <td><?= $j ?></td>
                    <td class="text-left">
                        <?= $nama ?>
                        <input type="hidden" name="items_emergensi[<?= $j ?>][nama]" value="<?= $nama ?>">
                    </td>
                    <td>
                        <?= $satuan ?>
                        <input type="hidden" name="items_emergensi[<?= $j ?>][satuan]" value="<?= $satuan ?>">
                    </td>
                    <td><input type="text" name="items_emergensi[<?= $j ?>][isi]" class="form-control form-control-sm" value="<?= $item['isi'] ?? '' ?>"></td>
                    <td><input type="text" name="items_emergensi[<?= $j ?>][terpakai]" class="form-control form-control-sm" value="<?= $item['terpakai'] ?? '' ?>"></td>
                    <td><input type="text" name="items_emergensi[<?= $j ?>][keterangan]" class="form-control form-control-sm" value="<?= $item['keterangan'] ?? '' ?>"></td>
                </tr>
                <?php $j++; endforeach; ?>
            </tbody>
        </table>
        
        <p class="mt-3">*Berdasarkan peraturan dari ICAO, Annex 6, 2018</p>

        <!-- Foto Geotagging -->
        <div class="card mb-4 border-info shadow-sm <?= !empty($is_print) ? 'border-0 shadow-none' : '' ?>">
            <div class="card-header bg-light py-2">
                <strong class="text-primary"><i class="fas fa-camera mr-1"></i> Foto Geotagging / Dokumentasi Pemeriksaan Obat-obatan</strong><br>
                <small class="text-muted">Geotagged First Aid Medicine Inspection Photo Documentation</small>
            </div>
            <div class="card-body">
                <?php if(!empty($obat['foto_geotagging'])): ?>
                    <div class="mb-3 text-center" style="page-break-inside: avoid;">
                        <p class="mb-1 text-muted small font-weight-bold">Foto Geotagging Tersimpan:</p>
                        <a href="<?= base_url($obat['foto_geotagging']) ?>" target="_blank" class="d-inline-block">
                            <img src="<?= base_url($obat['foto_geotagging']) ?>" alt="Foto Geotagging" class="img-thumbnail shadow-sm" style="max-height: <?= !empty($is_print) ? '550px' : '450px' ?>; max-width: 100%;">
                        </a>
                        <div class="mt-1 d-print-none">
                            <a href="<?= base_url($obat['foto_geotagging']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-expand mr-1"></i> Buka Ukuran Penuh
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if(!empty($is_print)): ?>
                        <p class="text-muted text-center"><em>Tidak ada foto geotagging terlampir.</em></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(empty($is_print)): ?>
                <div class="form-group mb-2">
                    <label for="foto_geotagging" class="font-weight-bold">
                        <?= !empty($obat['foto_geotagging']) ? 'Ganti Foto Geotagging:' : 'Upload Foto Geotagging:' ?>
                    </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_geotagging" id="foto_geotagging" accept="image/*" onchange="previewGeotag(this, 'geotag_preview')">
                        <label class="custom-file-label" for="foto_geotagging">Pilih file foto baru...</label>
                    </div>
                    <small class="form-text text-muted">Format: JPG, JPEG, PNG (Maks 10MB). Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>
                <div id="geotag_preview_wrapper" class="mt-3 text-center" style="display:none;">
                    <p class="mb-1 text-muted small font-weight-bold">Preview Foto Baru:</p>
                    <img id="geotag_preview" src="" alt="Preview Geotagging" class="img-thumbnail shadow-sm" style="max-height: 450px; max-width: 100%;">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <table class="table borderless mb-3 mt-4 text-center" width="100%">
            <tr>
                <td width="50%">
                    <p>Mengetahui,<br>Crew Saudi Airlines</p>
                    <?php if(!empty($obat['crew_ttd'])): ?>
                        <img src="<?= $obat['crew_ttd'] ?>" class="sig-img"><br>
                        <?php if(empty($is_pdf) && empty($is_print)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_crew').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_crew\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padCrew.clear()\'>Clear Tanda Tangan</button>'; padCrew = new SignaturePad(document.getElementById('pad_crew'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf) && empty($is_print)): ?>
                        <canvas id="pad_crew" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px; max-width: 100%;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padCrew.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mt-2">
                        (<input type="text" name="crew_nama" class="form-control d-inline w-50 text-center sig-name-input" placeholder="Nama" required value="<?= $obat['crew_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    </div>
                    <input type="hidden" name="crew_ttd" id="ttd_crew" value="<?= $obat['crew_ttd'] ?? '' ?>">
                </td>
                <td width="50%">
                    <p><br>Pelapor,</p>
                    <?php if(!empty($obat['pelapor_ttd'])): ?>
                        <img src="<?= $obat['pelapor_ttd'] ?>" class="sig-img"><br>
                        <?php if(empty($is_pdf) && empty($is_print)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_pelapor').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_pelapor\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padPelapor.clear()\'>Clear Tanda Tangan</button>'; padPelapor = new SignaturePad(document.getElementById('pad_pelapor'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf) && empty($is_print)): ?>
                        <canvas id="pad_pelapor" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px; max-width: 100%;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padPelapor.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mt-2">
                        (<input type="text" name="pelapor_nama" class="form-control d-inline w-50 text-center sig-name-input" placeholder="Nama" required value="<?= $obat['pelapor_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    </div>
                    <input type="hidden" name="pelapor_ttd" id="ttd_pelapor" value="<?= $obat['pelapor_ttd'] ?? '' ?>">
                </td>
            </tr>
        </table>

        <?php if(empty($is_pdf) && empty($is_print)): ?>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg d-print-none">Submit Form</button>
        </div>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    if(document.getElementById('pad_crew')) {
        var padCrew = new SignaturePad(document.getElementById('pad_crew'));
    }
    if(document.getElementById('pad_pelapor')) {
        var padPelapor = new SignaturePad(document.getElementById('pad_pelapor'));
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
<?php if(!empty($is_print) && empty($is_public_doc)): ?>
<script>
    window.onload = function() { window.print(); }
</script>
<?php endif; ?>
</body>
</html>