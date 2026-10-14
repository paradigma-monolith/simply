<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIRCRAFT SANITATION INSPECTION CHECK LIST</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-size: 14px; }
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat img { max-width: 100%; height: auto; }
        .table th, .table td { vertical-align: middle; padding: 0.4rem; }
        .form-control-sm { height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.5rem; font-size: .875rem; line-height: 1.5; border-radius: 0.2rem; }
        .borderless td, .borderless th { border: none; padding: 0.2rem; }
        .italic-eng { font-style: italic; font-size: 0.9em; }
        .radio-box { width: 20px; height: 20px; cursor: pointer; }
        .underline-input { border: none; border-bottom: 1px dotted #000; border-radius: 0; background: transparent; padding: 0; }
        .underline-input:focus { outline: none; box-shadow: none; border-bottom: 1px solid #000; background: transparent; }
    </style>
</head>
<body>
<div class="container-fluid mt-4 mb-5" style="max-width: 1000px;">
    <div class="kop-surat">
        <img src="/dist/img/Kop_BKK_bandung.png" alt="Kop Surat" onerror="this.onerror=null;this.src='/dist/img/Kop_BKK_bandung.jpg';">
    </div>
    
    <h5 class="text-center font-weight-bold mb-4"><u>AIRCRAFT SANITATION INSPECTION CHECK LIST</u></h5>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="/travelconsent/update_aircraft/<?= $aircraft['id'] ?>" method="post">
        
        <table class="table borderless mb-3">
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
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?> ..........................</td>
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
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?> ..........................</td>
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
                    <td class="border-left-0 border-top-0"><?= str_replace(['(', ')'], ['<span class="italic-eng">(', ')</span>'], $v) ?> ..........................</td>
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
                        : Ditangani petugas khusus <br><span class="italic-eng">(Handle by special personal)</span>...........................................
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
                        : a. Ditangani petugas khusus <span class="italic-eng">(Handle by special personal)</span>...........................................<br><br>
                        b. Kualitas Fisik <br><span class="italic-eng">Physical Qualities</span>
                        <?php $air_data = isset($aircraft['penanganan_air']) && is_string($aircraft['penanganan_air']) ? json_decode($aircraft['penanganan_air'], true) : ($aircraft['penanganan_air'] ?? []); ?>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="penanganan_air[bau]" value="1" <?= ($air_data['bau'] ?? '') == '1' ? 'checked' : '' ?>> Bau <span class="italic-eng">(Smell)</span>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="penanganan_air[warna]" value="1" <?= ($air_data['warna'] ?? '') == '1' ? 'checked' : '' ?>> Warna <span class="italic-eng">(Color)</span>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="penanganan_air[rasa]" value="1" <?= ($air_data['rasa'] ?? '') == '1' ? 'checked' : '' ?>> Rasa <span class="italic-eng">(Flavor)</span>
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
                        : a. <input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="baik" <?= ($mak_data['kondisi'] ?? '') == 'baik' ? 'checked' : '' ?>> Baik <span class="italic-eng">(Good)</span><br>
                        &nbsp;&nbsp;b. <input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="tidak_baik" <?= ($mak_data['kondisi'] ?? '') == 'tidak_baik' ? 'checked' : '' ?>> Tidak Baik <span class="italic-eng">(Poor)</span>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[bau]" value="1" <?= ($mak_data['bau'] ?? '') == '1' ? 'checked' : '' ?>> Bau <span class="italic-eng">(Smell)</span>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[lendir]" value="1" <?= ($mak_data['lendir'] ?? '') == '1' ? 'checked' : '' ?>> Lendir <span class="italic-eng">(Mucus)</span>
                        &nbsp;&nbsp;&nbsp; <input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[jamur]" value="1" <?= ($mak_data['jamur'] ?? '') == '1' ? 'checked' : '' ?>> Jamur <span class="italic-eng">(Fungi)</span><br><br>
                        &nbsp;&nbsp;c. Jumlah Jenis makanan ........................................................................................................<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sebutkan jenisnya : <input type="text" name="keadaan_makanan[jenis]" class="form-control form-control-sm d-inline w-50 underline-input" value="<?= $mak_data['jenis'] ?? '' ?>">
                    </td>
                </tr>
                
                <!-- 8. Binatang -->
                <tr>
                    <td class="text-center">8</td>
                    <td colspan="4">
                        <u><strong>Binatang Pengganggu*</strong></u><br><span class="italic-eng">Pest Infestation</span><br>
                        <?php $bin_data = isset($aircraft['binatang_pengganggu']) && is_string($aircraft['binatang_pengganggu']) ? json_decode($aircraft['binatang_pengganggu'], true) : ($aircraft['binatang_pengganggu'] ?? []); ?>
                        : a. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[nyamuk]" value="1" <?= ($bin_data['nyamuk'] ?? '') == '1' ? 'checked' : '' ?>> Nyamuk <span class="italic-eng">Mosquito</span>
                        &nbsp;&nbsp;b. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[lalat]" value="1" <?= ($bin_data['lalat'] ?? '') == '1' ? 'checked' : '' ?>> Lalat <span class="italic-eng">Flies</span>
                        &nbsp;&nbsp;c. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[kecoa]" value="1" <?= ($bin_data['kecoa'] ?? '') == '1' ? 'checked' : '' ?>> Kecoa <span class="italic-eng">Cockroach</span>
                        &nbsp;&nbsp;d. <input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[tikus]" value="1" <?= ($bin_data['tikus'] ?? '') == '1' ? 'checked' : '' ?>> Tikus <span class="italic-eng">Rodent</span>
                        &nbsp;&nbsp;d. Lain2 <span class="italic-eng">Others</span> <input type="text" name="binatang_pengganggu[lain2]" class="form-control form-control-sm d-inline w-25 underline-input" value="<?= $bin_data['lain2'] ?? '' ?>">
                    </td>
                </tr>
                
                <!-- 9 -->
                <tr>
                    <td class="text-center">9</td>
                    <td colspan="4">
                        <strong>Surat Keterangan Sanitasi Pesawat</strong><br><span class="italic-eng">Certificate Aircraft Sanitation</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        a. <input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'ada' ? 'checked' : '' ?>> Ada / <span class="italic-eng">There are</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        b. <input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="tidak_ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'tidak_ada' ? 'checked' : '' ?>> Tidak Ada / <span class="italic-eng">Not Found</span>
                    </td>
                </tr>
                
                <!-- 10 -->
                <tr>
                    <td class="text-center">10</td>
                    <td colspan="4">
                        <strong>Kesimpulan / <span class="italic-eng">Conclusion</span> :</strong> &nbsp;&nbsp;
                        <input type="radio" class="radio-box align-middle" name="kesimpulan" value="layak" <?= ($aircraft['kesimpulan'] ?? '') == 'layak' ? 'checked' : '' ?>> Layak / 
                        <input type="radio" class="radio-box align-middle" name="kesimpulan" value="tidak_layak" <?= ($aircraft['kesimpulan'] ?? '') == 'tidak_layak' ? 'checked' : '' ?>> Tidak Layak 
                        diterbitkan "Sertifikat Bebas Serangga"<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="italic-eng">Qualifield / Unqualifield "Disinsection Exemption Certificate" issued.</span><br><br>
                        
                        <u><strong>Rekomendasi dan Tindakan* :</strong></u> &nbsp;&nbsp;&nbsp;
                        Perlu / tidak perlu tindakan penyehatan : &nbsp;&nbsp;&nbsp;
                        <input type="radio" class="radio-box align-middle" name="tindakan" value="disinseksi" <?= ($aircraft['tindakan'] ?? '') == 'disinseksi' ? 'checked' : '' ?>> Disinseksi &nbsp;&nbsp;&nbsp;
                        <input type="radio" class="radio-box align-middle" name="tindakan" value="fumigasi" <?= ($aircraft['tindakan'] ?? '') == 'fumigasi' ? 'checked' : '' ?>> Fumigasi <br>
                        <span class="italic-eng">Recommendation and Measure</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="italic-eng">Need / not necessary restructuring measures</span> &nbsp;&nbsp;&nbsp;
                        <input type="radio" class="radio-box align-middle" name="tindakan" value="disinfeksi" <?= ($aircraft['tindakan'] ?? '') == 'disinfeksi' ? 'checked' : '' ?>> Disinfeksi &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" class="radio-box align-middle" name="tindakan" value="general_cleaning" <?= ($aircraft['tindakan'] ?? '') == 'general_cleaning' ? 'checked' : '' ?>> General Cleaning
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="mb-4" style="font-size: 0.9em;">
            * &nbsp;&nbsp; Isilah tanda &#10003; Untuk jawaban dimaksud<br>
            &nbsp;&nbsp;&nbsp;&nbsp; <span class="italic-eng">Please tick &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appropriate</span>
        </p>

        <div class="row mt-5 text-center">
            <div class="col-6">
                <p><u>Petugas Kabin</u><br><span class="italic-eng">Cabin Crew</span></p>
                <?php if(!empty($aircraft['petugas_kabin_ttd'])): ?>
                    <img src="<?= $aircraft['petugas_kabin_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                    <button type="button" class="btn btn-sm btn-warning mt-2" onclick="document.getElementById('ttd_kabin').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_kabin\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padKabin.clear()\'>Clear Tanda Tangan</button>'; padKabin = new SignaturePad(document.getElementById('pad_kabin'));">Ubah Tanda Tangan</button>
                <?php else: ?>
                    <canvas id="pad_kabin" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padKabin.clear()">Clear Tanda Tangan</button>
                <?php endif; ?>
                <br><br>
                ( <input type="text" name="petugas_kabin_nama" class="form-control underline-input d-inline w-50 text-center" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_kabin_nama'] ?? '' ?>"> )<br>
                <span class="italic-eng">Name & signature</span>
                <input type="hidden" name="petugas_kabin_ttd" id="ttd_kabin" value="<?= $aircraft['petugas_kabin_ttd'] ?? '' ?>">
            </div>
            <div class="col-6">
                <p><u>Petugas BKK</u><br><span class="italic-eng">Health Quarantine Officer</span></p>
                <?php if(!empty($aircraft['petugas_bkk_ttd'])): ?>
                    <img src="<?= $aircraft['petugas_bkk_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px; display: block; margin: 0 auto;"><br>
                    <button type="button" class="btn btn-sm btn-warning mt-2" onclick="document.getElementById('ttd_bkk').value=''; this.previousElementSibling.previousElementSibling.outerHTML='<canvas id=\'pad_bkk\' width=\'300\' height=\'150\' style=\'border:1px solid #ccc; border-radius: 5px;\'></canvas>'; this.outerHTML='<button type=\'button\' class=\'btn btn-sm btn-secondary mt-2\' onclick=\'padBkk.clear()\'>Clear Tanda Tangan</button>'; padBkk = new SignaturePad(document.getElementById('pad_bkk'));">Ubah Tanda Tangan</button>
                <?php else: ?>
                    <canvas id="pad_bkk" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><br>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padBkk.clear()">Clear Tanda Tangan</button>
                <?php endif; ?>
                <br><br>
                ( <input type="text" name="petugas_bkk_nama" class="form-control underline-input d-inline w-50 text-center" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_bkk_nama'] ?? '' ?>"> )<br>
                <span class="italic-eng">Name & signature</span>
                <input type="hidden" name="petugas_bkk_ttd" id="ttd_bkk" value="<?= $aircraft['petugas_bkk_ttd'] ?? '' ?>">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg d-print-none">Submit Form</button>
        </div>
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

    document.querySelector('form').addEventListener('submit', function(e) {
        if (typeof padKabin !== 'undefined' && !padKabin.isEmpty()) {
            document.getElementById('ttd_kabin').value = padKabin.toDataURL();
        }
        if (typeof padBkk !== 'undefined' && !padBkk.isEmpty()) {
            document.getElementById('ttd_bkk').value = padBkk.toDataURL();
        }
    });
</script>
</body>
</html>