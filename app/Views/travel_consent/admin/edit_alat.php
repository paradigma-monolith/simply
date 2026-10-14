<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DAFTAR PEMAKAIAN ALAT KESEHATAN KOTAK P3K DAN EMERGENSI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-size: 14px; }
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat img { max-width: 100%; height: auto; }
        .table th, .table td { vertical-align: middle; padding: 0.4rem; }
        .borderless td, .borderless th { border: none; padding: 0.2rem; }
        
        <?php if(!empty($is_print)): ?>
        @media print {
            .form-control { border: none !important; box-shadow: none !important; background: transparent !important; }
            .d-print-none { display: none !important; }
        }
        .form-control { border: none !important; background: transparent !important; }
        <?php endif; ?>
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="kop-surat">
        <img src="/dist/img/Kop_BKK_bandung.png" alt="Kop Surat" onerror="this.onerror=null;this.src='/dist/img/Kop_BKK_bandung.jpg';">
    </div>
    
    <h5 class="text-center font-weight-bold">DAFTAR PEMAKAIAN ALAT KESEHATAN</h5>
    <h5 class="text-center font-weight-bold">KOTAK P3K DAN EMERGENSI</h5>
    <h6 class="text-center font-weight-bold mb-4">
        PENERBANGAN HAJI 
        <input type="text" name="penerbangan_haji_h" class="form-control d-inline text-center" style="width: 50px;" value="<?= $alat['penerbangan_haji_h'] ?? '' ?>"> H /
        <input type="text" name="penerbangan_haji_m" class="form-control d-inline text-center" style="width: 50px;" value="<?= $alat['penerbangan_haji_m'] ?? '' ?>"> M
    </h6>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="/travelconsent/update_alat/<?= $alat['id'] ?>" method="post">
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="30%">Embarkasi / Debarkasi</td>
                        <td width="5%">:</td>
                        <td><input type="text" name="embarkasi" class="form-control" value="<?= $alat['embarkasi'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td>Nomor Kloter</td>
                        <td>:</td>
                        <td><input type="text" name="nomor_kloter" class="form-control" value="<?= $alat['nomor_kloter'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td>No. Kontak</td>
                        <td>:</td>
                        <td><input type="text" name="no_kontak" class="form-control" value="<?= $alat['no_kontak'] ?? '' ?>"></td>
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

        <table class="table borderless mb-3 mt-5 text-center" width="100%">
            <tr>
                <td width="50%">
                    <p>Mengetahui,<br>Crew Saudi Airlines</p>
                    <?php if(!empty($alat['crew_ttd'])): ?>
                        <img src="<?= $alat['crew_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                        <?php if(empty($is_pdf)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_crew').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_crew\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padCrew.clear()\'>Clear Tanda Tangan</button>'; padCrew = new SignaturePad(document.getElementById('pad_crew'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf)): ?>
                        <canvas id="pad_crew" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padCrew.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <br><br>
                    (<input type="text" name="crew_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $alat['crew_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    <input type="hidden" name="crew_ttd" id="ttd_crew" value="<?= $alat['crew_ttd'] ?? '' ?>">
                </td>
                <td width="50%">
                    <p><br>Pelapor,</p>
                    <?php if(!empty($alat['pelapor_ttd'])): ?>
                        <img src="<?= $alat['pelapor_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                        <?php if(empty($is_pdf)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_pelapor').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_pelapor\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padPelapor.clear()\'>Clear Tanda Tangan</button>'; padPelapor = new SignaturePad(document.getElementById('pad_pelapor'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf)): ?>
                        <canvas id="pad_pelapor" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padPelapor.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <br><br>
                    (<input type="text" name="pelapor_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $alat['pelapor_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    <input type="hidden" name="pelapor_ttd" id="ttd_pelapor" value="<?= $alat['pelapor_ttd'] ?? '' ?>">
                </td>
            </tr>
        </table>

        <?php if(empty($is_pdf)): ?>
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

    document.querySelector('form').addEventListener('submit', function(e) {
        if (typeof padCrew !== 'undefined' && !padCrew.isEmpty()) {
            document.getElementById('ttd_crew').value = padCrew.toDataURL();
        }
        if (typeof padPelapor !== 'undefined' && !padPelapor.isEmpty()) {
            document.getElementById('ttd_pelapor').value = padPelapor.toDataURL();
        }
    });
</script>
<?php if(!empty($is_print)): ?>
<script>
    window.onload = function() { window.print(); }
</script>
<?php endif; ?>
</body>
</html>