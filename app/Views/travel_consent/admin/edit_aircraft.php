<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIRCRAFT SANITATION INSPECTION CHECK LIST</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <style>
        body { font-size: 14px; background-color: #fff; }
        .kop-surat { text-align: center; margin-bottom: 16px; }
        .kop-surat img { max-width: 100%; height: auto; }
        .table th, .table td { vertical-align: middle; padding: 0.4rem; }
        .form-control-sm { height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem; font-size: .875rem; line-height: 1.5; border-radius: 0.2rem; }
        .borderless td, .borderless th { border: none; padding: 0.2rem; }
        .italic-eng { font-style: italic; font-size: 0.9em; }
        .radio-box { width: 18px; height: 18px; cursor: pointer; }
        .underline-input { border: none; border-bottom: 1px dotted #000; border-radius: 0; background: transparent; padding: 0; min-width: 0; }
        .underline-input:focus { outline: none; box-shadow: none; border-bottom: 1px solid #000; background: transparent; }
        .sig-img { max-width: 100%; width: 240px; height: 130px; object-fit: contain; border: 1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto; }

        <?php if(!empty($is_print)): ?>
        @media print {
            .form-control, .underline-input { border: none !important; box-shadow: none !important; background: transparent !important; }
            input[type="radio"], input[type="checkbox"] { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .d-print-none { display: none !important; }
        }
        .form-control, .underline-input { border: none !important; background: transparent !important; pointer-events: none; height: auto; padding: 0; }
        input[type="radio"], input[type="checkbox"] { pointer-events: none; }
        <?php endif; ?>

        @media screen and (max-width: 767.98px) {
            body { font-size: 12px; }
            .doc-container { padding-left: 8px !important; padding-right: 8px !important; margin-top: 10px !important; margin-bottom: 20px !important; }
            .kop-surat { margin-bottom: 10px; }
            h5 { font-size: 13.5px; margin-bottom: 12px !important; }
            .dots-leader { display: none; }
            .table th, .table td { padding: 0.25rem 0.3rem; font-size: 11.5px; word-break: break-word; }
            .radio-box { width: 15px; height: 15px; }
            .table-meta tr { display: flex; flex-wrap: wrap; border-bottom: 1px solid #eee; padding: 3px 0; }
            .table-meta td:nth-child(1), .table-meta td:nth-child(4) { width: 42% !important; font-size: 11px; }
            .table-meta td:nth-child(2), .table-meta td:nth-child(5) { width: 3% !important; }
            .table-meta td:nth-child(3), .table-meta td:nth-child(6) { width: 55% !important; font-weight: 600; }
            .table-meta input.form-control { font-size: 11.5px; height: auto; padding: 1px 0; }
            .sig-img { width: 135px; height: 80px; }
            .sig-name-input { width: 85% !important; font-size: 11px !important; }
            .inline-text-input { width: 55% !important; font-size: 11.5px !important; }
        }
    </style>
</head>
<body>
<div class="container-fluid mt-4 mb-5 doc-container" style="max-width: 1000px;">
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
    
    <h5 class="text-center font-weight-bold mb-4"><u>AIRCRAFT SANITATION INSPECTION CHECK LIST</u></h5>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="/travelconsent/update_aircraft/<?= $aircraft['id'] ?>" method="post" enctype="multipart/form-data">
        
        <table class="table borderless table-meta mb-3">
            <tr>
                <td width="30%"><strong><u>Pemilik atau Penyelenggara</u></strong><br><span class="italic-eng">Owner or Operator</span></td>
                <td width="2%">:</td>
                <td width="38%"><input type="text" name="pemilik" class="form-control underline-input w-100" required value="<?= $aircraft['pemilik'] ?? '' ?>"></td>
                <td width="10%"><strong><u>Tanggal</u></strong><br><span class="italic-eng">Date</span></td>
                <td width="2%">:</td>
                <td width="18%"><input type="date" name="tanggal" class="form-control underline-input w-100" required value="<?= $aircraft['tanggal'] ?? '' ?>"></td>
            </tr>
            <tr>
                <td><strong><u>Tanda Kebangsaan dan Pendaftaran</u></strong><br><span class="italic-eng">Marks of Nationality and Registration</span></td>
                <td>:</td>
                <td><input type="text" name="tanda_kebangsaan" class="form-control underline-input w-100" required value="<?= $aircraft['tanda_kebangsaan'] ?? '' ?>"></td>
                <td><strong><u>Pukul</u></strong><br><span class="italic-eng">Time</span></td>
                <td>:</td>
                <td><input type="time" name="waktu" class="form-control underline-input w-100" required value="<?= $aircraft['waktu'] ?? '' ?>"></td>
            </tr>
        </table>

        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="2" width="4%">No</th>
                    <th rowspan="2" colspan="2" width="66%"><u>Y a n g &nbsp; D i p e r i k s a :</u><br><span class="italic-eng">I t e m ' s</span></th>
                    <th colspan="2" width="30%">Keadaan / <span class="italic-eng">Condition</span></th>
                </tr>
                <tr>
                    <th width="15%">Baik<br><span class="italic-eng">(Good)</span></th>
                    <th width="15%">TidakBaik<br><span class="italic-eng">(Poor)</span></th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Kabin Penumpang -->
                <tr>
                    <td class="text-center" rowspan="7">1</td>
                    <td colspan="2"><u><strong>Kabin Penumpang</strong></u><br><span class="italic-eng">Passenger Cabins</span></td>
                    <td colspan="2"></td>
                </tr>
                <?php $kabin = [
                    'Lantai dan dinding (Floor & Wall)', 
                    'Kursi (Seats)', 
                    'Meja (Tables)', 
                    'Bagasi Kabin (Cabin Baggage)', 
                    'Kantong belakang kursi (Seat-back pocket)', 
                    'Langit-langit kabin'
                ]; 
                $kabin_data = isset($aircraft['kabin_penumpang']) && is_string($aircraft['kabin_penumpang']) ? json_decode($aircraft['kabin_penumpang'], true) : ($aircraft['kabin_penumpang'] ?? []);
                foreach($kabin as $k => $v): 
                    $key = chr(97+$k);
                    $val = $kabin_data[$key] ?? '';
                ?>
                <tr>
                    <td width="4%" class="text-right border-right-0 border-top-0"><?= $key ?>.</td>
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?><span class="dots-leader"> ..........................</span></td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="kabin_penumpang[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="kabin_penumpang[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <!-- 2. Toilet -->
                <tr>
                    <td class="text-center" rowspan="6">2</td>
                    <td colspan="2"><u><strong>Kompartemen Toilet</strong></u><br><span class="italic-eng">Toilet Compartment</span></td>
                    <td colspan="2"></td>
                </tr>
                <?php $toilet = [
                    'Lantai (Floor)', 
                    'Alas duduk & penutup (Seat & Cover)', 
                    'Tempat sampah (Waste Bins)', 
                    'Tempat cuci tangan (Wash hand basin)', 
                    'Sabun & tissue (Soap & tissue)'
                ]; 
                $toilet_data = isset($aircraft['kompartemen_toilet']) && is_string($aircraft['kompartemen_toilet']) ? json_decode($aircraft['kompartemen_toilet'], true) : ($aircraft['kompartemen_toilet'] ?? []);
                foreach($toilet as $k => $v): 
                    $key = chr(97+$k);
                    $val = $toilet_data[$key] ?? '';
                ?>
                <tr>
                    <td class="text-right border-right-0 border-top-0"><?= $key ?>.</td>
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?><span class="dots-leader"> ..........................</span></td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="kompartemen_toilet[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="kompartemen_toilet[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>

                <!-- 3. Galley -->
                <tr>
                    <td class="text-center" rowspan="6">3</td>
                    <td colspan="2"><u><strong>Tempat Penyimpanan Makanan</strong></u><br><span class="italic-eng">Galley</span></td>
                    <td colspan="2"></td>
                </tr>
                <?php $galley = [
                    'Lantai (Floor)', 
                    'Alas tempat kerja (Worktop)', 
                    'Tempat cuci tangan (Wash hand basin)', 
                    'Tempat sampah (Waste bins)', 
                    'Ruang tempat trolley (Trolley storage)'
                ]; 
                $galley_data = isset($aircraft['galley']) && is_string($aircraft['galley']) ? json_decode($aircraft['galley'], true) : ($aircraft['galley'] ?? []);
                foreach($galley as $k => $v): 
                    $key = chr(97+$k);
                    $val = $galley_data[$key] ?? '';
                ?>
                <tr>
                    <td class="text-right border-right-0 border-top-0"><?= $key ?>.</td>
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?><span class="dots-leader"> ..........................</span></td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="galley[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="galley[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>

                <!-- 4. Dokumen Kesehatan -->
                <tr>
                    <td class="text-center" rowspan="5">4</td>
                    <td colspan="2"><u><strong>Dokumen Kesehatan Pesawat</strong></u><br><span class="italic-eng">Health Document's</span></td>
                    <td colspan="2"></td>
                </tr>
                <?php $dokumen = [
                    'Health Part of the Air Craft General Declaration', 
                    'Surat keterangan hapus serangga yang terakhir', 
                    'Surat keterangan hapus hama, jika ada diadakan hapus hama', 
                    'Buku kesehatan pesawat udara (hanya pesawat udara yang mengadakan perjalanan dalam negeri)'
                ]; 
                $dok_data = isset($aircraft['dokumen_kesehatan']) && is_string($aircraft['dokumen_kesehatan']) ? json_decode($aircraft['dokumen_kesehatan'], true) : ($aircraft['dokumen_kesehatan'] ?? []);
                foreach($dokumen as $k => $v): 
                    $key = chr(97+$k);
                    $val = $dok_data[$key] ?? '';
                ?>
                <tr>
                    <td class="text-right border-right-0 border-top-0"><?= $key ?>.</td>
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?></td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="dokumen_kesehatan[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <input type="radio" class="radio-box" name="dokumen_kesehatan[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <!-- 5. Pembuangan Kotoran -->
                <tr>
                    <td class="text-center">5</td>
                    <td colspan="2">
                        <u><strong>Pembuangan Kotoran Cair</strong></u><br><span class="italic-eng">Excreta Disposal</span><br>
                        : Ditangani petugas khusus <br><span class="italic-eng">(Handle by special personal)</span><span class="dots-leader">...........................................</span>
                    </td>
                    <td class="text-center align-bottom">
                        <input type="radio" class="radio-box" name="pembuangan_kotoran" value="ya" required <?= ($aircraft['pembuangan_kotoran'] ?? '') == 'ya' ? 'checked' : '' ?>><br>Ya <span class="italic-eng">(Yes)</span>
                    </td>
                    <td class="text-center align-bottom">
                        <input type="radio" class="radio-box" name="pembuangan_kotoran" value="tidak" <?= ($aircraft['pembuangan_kotoran'] ?? '') == 'tidak' ? 'checked' : '' ?>><br>Tidak <span class="italic-eng">(No)</span>
                    </td>
                </tr>
                
                <!-- 6. Penanganan Air -->
                <tr>
                    <td class="text-center">6</td>
                    <td colspan="2">
                        <u><strong>Penanganan Penyediaan Air</strong></u><br><span class="italic-eng">Aircraft Water Supply</span><br>
                        : a. Ditangani petugas khusus <span class="italic-eng">(Handle by special personal)</span><span class="dots-leader">...........................................</span><br><br>
                        b. Kualitas Fisik <br><span class="italic-eng">Physical Qualities</span>
                        <?php $air_data = isset($aircraft['penanganan_air']) && is_string($aircraft['penanganan_air']) ? json_decode($aircraft['penanganan_air'], true) : ($aircraft['penanganan_air'] ?? []); ?>
                        <div class="mt-1">
                            <span class="d-inline-block mr-3"><input type="checkbox" class="radio-box align-middle" name="penanganan_air[bau]" value="1" <?= ($air_data['bau'] ?? '') == '1' ? 'checked' : '' ?>> Bau <span class="italic-eng">(Smell)</span></span>
                            <span class="d-inline-block mr-3"><input type="checkbox" class="radio-box align-middle" name="penanganan_air[warna]" value="1" <?= ($air_data['warna'] ?? '') == '1' ? 'checked' : '' ?>> Warna <span class="italic-eng">(Color)</span></span>
                            <span class="d-inline-block"><input type="checkbox" class="radio-box align-middle" name="penanganan_air[rasa]" value="1" <?= ($air_data['rasa'] ?? '') == '1' ? 'checked' : '' ?>> Rasa <span class="italic-eng">(Flavor)</span></span>
                        </div>
                    </td>
                    <td class="text-center">
                        <br><input type="radio" class="radio-box" name="penanganan_air[petugas]" value="ya" <?= ($air_data['petugas'] ?? '') == 'ya' ? 'checked' : '' ?>><br>Ya <span class="italic-eng">(Yes)</span>
                    </td>
                    <td class="text-center">
                        <br><input type="radio" class="radio-box" name="penanganan_air[petugas]" value="tidak" <?= ($air_data['petugas'] ?? '') == 'tidak' ? 'checked' : '' ?>><br>Tidak <span class="italic-eng">(No)</span>
                    </td>
                </tr>
                
                <!-- 7. Makanan -->
                <tr>
                    <td class="text-center">7</td>
                    <td colspan="4">
                        <u><strong>Keadaan makanan secara fisik*</strong></u><br><span class="italic-eng">Physical Appearance Of Food</span><br>
                        <?php $mak_data = isset($aircraft['keadaan_makanan']) && is_string($aircraft['keadaan_makanan']) ? json_decode($aircraft['keadaan_makanan'], true) : ($aircraft['keadaan_makanan'] ?? []); ?>
                        <div class="mt-1">
                            a. <input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="baik" <?= ($mak_data['kondisi'] ?? '') == 'baik' ? 'checked' : '' ?>> Baik <span class="italic-eng">(Good)</span><br>
                            b. <input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="tidak_baik" <?= ($mak_data['kondisi'] ?? '') == 'tidak_baik' ? 'checked' : '' ?>> Tidak Baik <span class="italic-eng">(Poor)</span>
                            <span class="d-inline-block ml-2 mr-2"><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[bau]" value="1" <?= ($mak_data['bau'] ?? '') == '1' ? 'checked' : '' ?>> Bau <span class="italic-eng">(Smell)</span></span>
                            <span class="d-inline-block mr-2"><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[lendir]" value="1" <?= ($mak_data['lendir'] ?? '') == '1' ? 'checked' : '' ?>> Lendir <span class="italic-eng">(Mucus)</span></span>
                            <span class="d-inline-block"><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[jamur]" value="1" <?= ($mak_data['jamur'] ?? '') == '1' ? 'checked' : '' ?>> Jamur <span class="italic-eng">(Fungi)</span></span>
                        </div>
                        <div class="mt-2">
                            c. Jumlah Jenis makanan : <input type="text" name="keadaan_makanan[jumlah]" class="form-control form-control-sm d-inline w-50 underline-input inline-text-input" value="<?= $mak_data['jumlah'] ?? '' ?>" <?= !empty($is_print) ? 'style="border:none;"' : '' ?>><br>
                            <span class="ml-2">Sebutkan jenisnya :</span> <input type="text" name="keadaan_makanan[jenis]" class="form-control form-control-sm d-inline w-50 underline-input inline-text-input" value="<?= $mak_data['jenis'] ?? '' ?>" <?= !empty($is_print) ? 'style="border:none;"' : '' ?>>
                        </div>
                    </td>
                </tr>
                
                <!-- 8. Binatang -->
                <tr>
                    <td class="text-center">8</td>
                    <td colspan="4">
                        <u><strong>Binatang Pengganggu*</strong></u><br><span class="italic-eng">Pest Infestation</span><br>
                        <?php $bin_data = isset($aircraft['binatang_pengganggu']) && is_string($aircraft['binatang_pengganggu']) ? json_decode($aircraft['binatang_pengganggu'], true) : ($aircraft['binatang_pengganggu'] ?? []); ?>
                        <div class="mt-1">
                            <span class="d-inline-block mr-3 mb-1">a. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[nyamuk]" value="1" <?= ($bin_data['nyamuk'] ?? '') == '1' ? 'checked' : '' ?>> Nyamuk <span class="italic-eng">Mosquito</span></span>
                            <span class="d-inline-block mr-3 mb-1">b. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[lalat]" value="1" <?= ($bin_data['lalat'] ?? '') == '1' ? 'checked' : '' ?>> Lalat <span class="italic-eng">Flies</span></span>
                            <span class="d-inline-block mr-3 mb-1">c. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[kecoa]" value="1" <?= ($bin_data['kecoa'] ?? '') == '1' ? 'checked' : '' ?>> Kecoa <span class="italic-eng">Cockroach</span></span>
                            <span class="d-inline-block mr-3 mb-1">d. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[tikus]" value="1" <?= ($bin_data['tikus'] ?? '') == '1' ? 'checked' : '' ?>> Tikus <span class="italic-eng">Rodent</span></span>
                            <span class="d-inline-block mb-1">e. Lain2 <span class="italic-eng">Others</span>: <input type="text" name="binatang_pengganggu[lain2]" class="form-control form-control-sm d-inline w-50 underline-input inline-text-input" value="<?= $bin_data['lain2'] ?? '' ?>"></span>
                        </div>
                    </td>
                </tr>
                
                <!-- 9 -->
                <tr>
                    <td class="text-center">9</td>
                    <td colspan="4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="mr-3">
                                <strong>Surat Keterangan Sanitasi Pesawat</strong><br><span class="italic-eng">Certificate Aircraft Sanitation</span>
                            </div>
                            <div class="mt-1 mt-sm-0">
                                <span class="d-inline-block mr-3">a. <input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'ada' ? 'checked' : '' ?>> Ada / <span class="italic-eng">There are</span></span>
                                <span class="d-inline-block">b. <input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="tidak_ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'tidak_ada' ? 'checked' : '' ?>> Tidak Ada / <span class="italic-eng">Not Found</span></span>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <!-- 10 -->
                <tr>
                    <td class="text-center">10</td>
                    <td colspan="4">
                        <div class="mb-2">
                            <strong>Kesimpulan / <span class="italic-eng">Conclusion</span> :</strong>
                            <span class="d-inline-block ml-2">
                                <input type="radio" class="radio-box align-middle" name="kesimpulan" value="layak" <?= ($aircraft['kesimpulan'] ?? '') == 'layak' ? 'checked' : '' ?>> Layak / 
                                <input type="radio" class="radio-box align-middle" name="kesimpulan" value="tidak_layak" <?= ($aircraft['kesimpulan'] ?? '') == 'tidak_layak' ? 'checked' : '' ?>> Tidak Layak 
                                diterbitkan "Sertifikat Bebas Serangga"
                            </span><br>
                            <span class="italic-eng text-muted">Qualified / Unqualified "Disinsection Exemption Certificate" issued.</span>
                        </div>
                        <div class="mt-2">
                            <u><strong>Rekomendasi dan Tindakan* :</strong></u> <span class="italic-eng">(Recommendation and Measure)</span><br>
                            <span>Perlu / tidak perlu tindakan penyehatan <span class="italic-eng">(Need / not necessary restructuring measures)</span> :</span>
                            <div class="mt-1">
                                <span class="d-inline-block mr-3 mb-1"><input type="radio" class="radio-box align-middle" name="tindakan" value="disinseksi" <?= ($aircraft['tindakan'] ?? '') == 'disinseksi' ? 'checked' : '' ?>> Disinseksi</span>
                                <span class="d-inline-block mr-3 mb-1"><input type="radio" class="radio-box align-middle" name="tindakan" value="fumigasi" <?= ($aircraft['tindakan'] ?? '') == 'fumigasi' ? 'checked' : '' ?>> Fumigasi</span>
                                <span class="d-inline-block mr-3 mb-1"><input type="radio" class="radio-box align-middle" name="tindakan" value="disinfeksi" <?= ($aircraft['tindakan'] ?? '') == 'disinfeksi' ? 'checked' : '' ?>> Disinfeksi</span>
                                <span class="d-inline-block mb-1"><input type="radio" class="radio-box align-middle" name="tindakan" value="general_cleaning" <?= ($aircraft['tindakan'] ?? '') == 'general_cleaning' ? 'checked' : '' ?>> General Cleaning</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="mb-4" style="font-size: 0.9em;">
            * &nbsp;&nbsp; Isilah tanda &#10003; Untuk jawaban dimaksud<br>
            &nbsp;&nbsp;&nbsp;&nbsp; <span class="italic-eng">Please tick &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appropriate</span>
        </p>

        <!-- Foto Geotagging -->
        <div class="card mb-4 border-info shadow-sm <?= !empty($is_print) ? 'border-0 shadow-none' : '' ?>">
            <div class="card-header bg-light py-2">
                <strong class="text-primary"><i class="fas fa-camera mr-1"></i> Foto Geotagging / Dokumentasi Pemeriksaan</strong><br>
                <span class="italic-eng text-muted">Geotagged Inspection Photo Documentation</span>
            </div>
            <div class="card-body">
                <?php if(!empty($aircraft['foto_geotagging'])): ?>
                    <div class="mb-3 text-center" style="page-break-inside: avoid;">
                        <p class="mb-1 text-muted small font-weight-bold">Foto Geotagging Tersimpan:</p>
                        <a href="<?= base_url($aircraft['foto_geotagging']) ?>" target="_blank" class="d-inline-block">
                            <img src="<?= base_url($aircraft['foto_geotagging']) ?>" alt="Foto Geotagging" class="img-thumbnail shadow-sm" style="max-height: <?= !empty($is_print) ? '550px' : '450px' ?>; max-width: 100%;">
                        </a>
                        <div class="mt-1 d-print-none">
                            <a href="<?= base_url($aircraft['foto_geotagging']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-expand mr-1"></i> Buka Ukuran Penuh
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if(!empty($is_print)): ?>
                        <p class="text-muted italic-eng text-center">Tidak ada foto geotagging terlampir.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(empty($is_print)): ?>
                <div class="form-group mb-2">
                    <label for="foto_geotagging" class="font-weight-bold">
                        <?= !empty($aircraft['foto_geotagging']) ? 'Ganti Foto Geotagging:' : 'Upload Foto Geotagging:' ?>
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
                    <p><u>Petugas Kabin</u><br><span class="italic-eng">Cabin Crew</span></p>
                    <?php if(!empty($aircraft['petugas_kabin_ttd'])): ?>
                        <img src="<?= $aircraft['petugas_kabin_ttd'] ?>" class="sig-img"><br>
                        <?php if(empty($is_pdf) && empty($is_print)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_kabin').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_kabin\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padKabin.clear()\'>Clear Tanda Tangan</button>'; padKabin = new SignaturePad(document.getElementById('pad_kabin'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf) && empty($is_print)): ?>
                        <canvas id="pad_kabin" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px; max-width: 100%;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padKabin.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mt-2">
                        ( <input type="text" name="petugas_kabin_nama" class="form-control underline-input d-inline w-50 text-center sig-name-input" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_kabin_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>> )<br>
                        <span class="italic-eng">Name & signature</span>
                    </div>
                    <input type="hidden" name="petugas_kabin_ttd" id="ttd_kabin" value="<?= $aircraft['petugas_kabin_ttd'] ?? '' ?>">
                </td>
                <td width="50%">
                    <p><u>Petugas BKK</u><br><span class="italic-eng">Health Quarantine Officer</span></p>
                    <?php if(!empty($aircraft['petugas_bkk_ttd'])): ?>
                        <img src="<?= $aircraft['petugas_bkk_ttd'] ?>" class="sig-img"><br>
                        <?php if(empty($is_pdf) && empty($is_print)): ?><button type="button" class="btn btn-sm btn-warning mt-2 d-print-none" onclick="document.getElementById('ttd_bkk').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_bkk\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padBkk.clear()\'>Clear Tanda Tangan</button>'; padBkk = new SignaturePad(document.getElementById('pad_bkk'));">Ubah Tanda Tangan</button><?php endif; ?>
                    <?php else: ?>
                        <?php if(empty($is_pdf) && empty($is_print)): ?>
                        <canvas id="pad_bkk" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px; max-width: 100%;"></canvas><br>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 d-print-none" onclick="padBkk.clear()">Clear Tanda Tangan</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mt-2">
                        ( <input type="text" name="petugas_bkk_nama" class="form-control underline-input d-inline w-50 text-center sig-name-input" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_bkk_nama'] ?? '' ?>" <?= !empty($is_pdf) ? 'style="border:none;"' : '' ?>> )<br>
                        <span class="italic-eng">Name & signature</span>
                    </div>
                    <input type="hidden" name="petugas_bkk_ttd" id="ttd_bkk" value="<?= $aircraft['petugas_bkk_ttd'] ?? '' ?>">
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
    if(document.getElementById('pad_kabin')) {
        var padKabin = new SignaturePad(document.getElementById('pad_kabin'));
    }
    if(document.getElementById('pad_bkk')) {
        var padBkk = new SignaturePad(document.getElementById('pad_bkk'));
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
        if (typeof padKabin !== 'undefined' && !padKabin.isEmpty()) {
            document.getElementById('ttd_kabin').value = padKabin.toDataURL();
        }
        if (typeof padBkk !== 'undefined' && !padBkk.isEmpty()) {
            document.getElementById('ttd_bkk').value = padBkk.toDataURL();
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