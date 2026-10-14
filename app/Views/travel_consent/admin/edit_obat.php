<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DAFTAR PEMAKAIAN OBAT KOTAK P3K DAN EMERGENSI</title>
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

    <form action="/travelconsent/update_obat/<?= $obat['id'] ?>" method="post">
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="30%">Embarkasi / Debarkasi</td>
                        <td width="5%">:</td>
                        <td><input type="text" name="embarkasi" class="form-control" value="<?= $obat['embarkasi'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td>Nomor Kloter</td>
                        <td>:</td>
                        <td><input type="text" name="nomor_kloter" class="form-control" value="<?= $obat['nomor_kloter'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td>No. Kontak</td>
                        <td>:</td>
                        <td><input type="text" name="no_kontak" class="form-control" value="<?= $obat['no_kontak'] ?? '' ?>"></td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="text-center font-weight-bold mt-4 mb-3">PEMAKAIAN OBAT KOTAK P3K</h6>
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

        <h6 class="text-center font-weight-bold mt-5 mb-3">PEMAKAIAN OBAT KOTAK EMERGENSI</h6>
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

        <table class="table borderless mb-3 mt-5 text-center" width="100%">
            <tr>
                <td width="50%">
                    <p>Mengetahui,<br>Crew Saudi Airlines</p>
                    <?php if(!empty($obat['crew_ttd'])): ?>
                        <img src="<?= $obat['crew_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                        <?php if(empty($is_pdf)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_crew').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_crew\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padCrew.clear()\'>Clear Tanda Tangan</button>'; padCrew = new SignaturePad(document.getElementById('pad_crew'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf)): ?>
                        <canvas id="pad_crew" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padCrew.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <br><br>
                    (<input type="text" name="crew_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $obat['crew_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    <input type="hidden" name="crew_ttd" id="ttd_crew" value="<?= $obat['crew_ttd'] ?? '' ?>">
                </td>
                <td width="50%">
                    <p><br>Pelapor,</p>
                    <?php if(!empty($obat['pelapor_ttd'])): ?>
                        <img src="<?= $obat['pelapor_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                        <?php if(empty($is_pdf)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_pelapor').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_pelapor\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padPelapor.clear()\'>Clear Tanda Tangan</button>'; padPelapor = new SignaturePad(document.getElementById('pad_pelapor'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf)): ?>
                        <canvas id="pad_pelapor" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padPelapor.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <br><br>
                    (<input type="text" name="pelapor_nama" class="form-control d-inline w-50 text-center" placeholder="Nama" required value="<?= $obat['pelapor_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>>)
                    <input type="hidden" name="pelapor_ttd" id="ttd_pelapor" value="<?= $obat['pelapor_ttd'] ?? '' ?>">
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