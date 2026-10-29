<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIRCRAFT SANITATION INSPECTION CHECK LIST</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
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
<?php $is_pdf = $is_pdf ?? false; ?>
<div class="container-fluid mt-4 mb-5" style="max-width: 1000px;">
    <div class="kop-surat">
        <img src="/dist/img/Kop_BKK_bandung.png" alt="Kop Surat" onerror="this.onerror=null;this.src='/dist/img/Kop_BKK_bandung.jpg';">
    </div>
    
    <h5 class="text-center font-weight-bold mb-4"><u>AIRCRAFT SANITATION INSPECTION CHECK LIST</u></h5>
    
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

    <?php if(!$is_pdf): ?><form action="/travelconsent/submit_aircraft" method="post" enctype="multipart/form-data"><?php endif; ?>
        
        <table class="table borderless mb-3">
            <tr>
                <td width="30%"><strong><u>Pemilik atau Penyelenggara</u></strong><br><span class="italic-eng">Owner or Operator</span></td>
                <td width="2%">:</td>
                <td width="38%"><?php if($is_pdf): ?><?= $aircraft['pemilik'] ?? '' ?><?php else: ?><input type="text" name="pemilik" class="form-control underline-input w-100" required value="<?= $aircraft['pemilik'] ?? '' ?>"><?php endif; ?></td>
                <td width="10%"><strong><u>Tanggal</u></strong><br><span class="italic-eng">Date</span></td>
                <td width="2%">:</td>
                <td width="18%"><?php if($is_pdf): ?><?= $aircraft['tanggal'] ?? '' ?><?php else: ?><input type="date" name="tanggal" class="form-control underline-input w-100" required value="<?= $aircraft['tanggal'] ?? '' ?>"><?php endif; ?></td>
            </tr>
            <tr>
                <td><strong><u>Tanda Kebangsaan dan Pendaftaran</u></strong><br><span class="italic-eng">Marks of Nationality and Registration</span></td>
                <td>:</td>
                <td><?php if($is_pdf): ?><?= $aircraft['tanda_kebangsaan'] ?? '' ?><?php else: ?><input type="text" name="tanda_kebangsaan" class="form-control underline-input w-100" required value="<?= $aircraft['tanda_kebangsaan'] ?? '' ?>"><?php endif; ?></td>
                <td><strong><u>Pukul</u></strong><br><span class="italic-eng">Time</span></td>
                <td>:</td>
                <td><?php if($is_pdf): ?><?= $aircraft['waktu'] ?? '' ?><?php else: ?><input type="time" name="waktu" class="form-control underline-input w-100" required value="<?= $aircraft['waktu'] ?? '' ?>"><?php endif; ?></td>
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
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="kabin_penumpang[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?><?php endif; ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="kabin_penumpang[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?><?php endif; ?>>
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
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="kompartemen_toilet[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?><?php endif; ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="kompartemen_toilet[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?><?php endif; ?>>
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
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="galley[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?><?php endif; ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="galley[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?><?php endif; ?>>
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
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="dokumen_kesehatan[<?= $key ?>]" value="baik" required <?= $val == 'baik' ? 'checked' : '' ?><?php endif; ?>>
                    </td>
                    <td class="text-center border-top-0">
                        <?php if($is_pdf): ?> <?= ($key) ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="dokumen_kesehatan[<?= $key ?>]" value="tidak_baik" <?= $val == 'tidak_baik' ? 'checked' : '' ?><?php endif; ?>>
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
                        <?php if($is_pdf): ?> <?= (($aircraft['pembuangan_kotoran'] ?? '') == 'ya') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="pembuangan_kotoran" value="ya" required <?= ($aircraft['pembuangan_kotoran'] ?? '') == 'ya' ? 'checked' : '' ?><?php endif; ?>><br>Ya <span class="italic-eng">(Yes)</span>
                    </td>
                    <td class="text-center align-bottom">
                        <?php if($is_pdf): ?> <?= (($aircraft['pembuangan_kotoran'] ?? '') == 'tidak') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="pembuangan_kotoran" value="tidak" <?= ($aircraft['pembuangan_kotoran'] ?? '') == 'tidak' ? 'checked' : '' ?><?php endif; ?>><br>Tidak <span class="italic-eng">(No)</span>
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
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($air_data['bau'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="penanganan_air[bau]" value="1" <?= ($air_data['bau'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Bau <span class="italic-eng">(Smell)</span>
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($air_data['warna'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="penanganan_air[warna]" value="1" <?= ($air_data['warna'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Warna <span class="italic-eng">(Color)</span>
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($air_data['rasa'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="penanganan_air[rasa]" value="1" <?= ($air_data['rasa'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Rasa <span class="italic-eng">(Flavor)</span>
                    </td>
                    <td class="text-center">
                        <br><?php if($is_pdf): ?> <?= (($air_data['petugas'] ?? '') == 'ya') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="penanganan_air[petugas]" value="ya" <?= ($air_data['petugas'] ?? '') == 'ya' ? 'checked' : '' ?><?php endif; ?>><br>Ya <span class="italic-eng">(Yes)</span>
                    </td>
                    <td class="text-center">
                        <br><?php if($is_pdf): ?> <?= (($air_data['petugas'] ?? '') == 'tidak') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box" name="penanganan_air[petugas]" value="tidak" <?= ($air_data['petugas'] ?? '') == 'tidak' ? 'checked' : '' ?><?php endif; ?>><br>Tidak <span class="italic-eng">(No)</span>
                    </td>
                </tr>
                
                <!-- 7. Makanan -->
                <tr>
                    <td class="text-center">7</td>
                    <td colspan="4">
                        <u><strong>Keadaan makanan secara fisik*</strong></u><br><span class="italic-eng">Physical Appearance Of Food</span><br>
                        <?php $mak_data = isset($aircraft['keadaan_makanan']) && is_string($aircraft['keadaan_makanan']) ? json_decode($aircraft['keadaan_makanan'], true) : ($aircraft['keadaan_makanan'] ?? []); ?>
                        : a. <?php if($is_pdf): ?> <?= (($mak_data['kondisi'] ?? '') == 'baik') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="baik" <?= ($mak_data['kondisi'] ?? '') == 'baik' ? 'checked' : '' ?><?php endif; ?>> Baik <span class="italic-eng">(Good)</span><br>
                        &nbsp;&nbsp;b. <?php if($is_pdf): ?> <?= (($mak_data['kondisi'] ?? '') == 'tidak_baik') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="keadaan_makanan[kondisi]" value="tidak_baik" <?= ($mak_data['kondisi'] ?? '') == 'tidak_baik' ? 'checked' : '' ?><?php endif; ?>> Tidak Baik <span class="italic-eng">(Poor)</span>
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($mak_data['bau'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[bau]" value="1" <?= ($mak_data['bau'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Bau <span class="italic-eng">(Smell)</span>
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($mak_data['lendir'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[lendir]" value="1" <?= ($mak_data['lendir'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Lendir <span class="italic-eng">(Mucus)</span>
                        &nbsp;&nbsp;&nbsp; <?php if($is_pdf): ?> <?= (($mak_data['jamur'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="keadaan_makanan[jamur]" value="1" <?= ($mak_data['jamur'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Jamur <span class="italic-eng">(Fungi)</span><br><br>
                        &nbsp;&nbsp;c. Jumlah Jenis makanan <?php if(!empty($is_pdf)): ?> <?= $mak_data['jumlah'] ?? '' ?> <?php else: ?><input type="text" name="keadaan_makanan[jumlah]" class="form-control form-control-sm d-inline w-50 underline-input" value="<?= $mak_data['jumlah'] ?? '' ?>"><?php endif; ?><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sebutkan jenisnya : <?php if(!empty($is_pdf)): ?> <?= $mak_data['jenis'] ?? '' ?> <?php else: ?><input type="text" name="keadaan_makanan[jenis]" class="form-control form-control-sm d-inline w-50 underline-input" value="<?= $mak_data['jenis'] ?? '' ?>"><?php endif; ?>
                    </td>
                </tr>
                
                <!-- 8. Binatang -->
                <tr>
                    <td class="text-center">8</td>
                    <td colspan="4">
                        <u><strong>Binatang Pengganggu*</strong></u><br><span class="italic-eng">Pest Infestation</span><br>
                        <?php $bin_data = isset($aircraft['binatang_pengganggu']) && is_string($aircraft['binatang_pengganggu']) ? json_decode($aircraft['binatang_pengganggu'], true) : ($aircraft['binatang_pengganggu'] ?? []); ?>
                        : a. <?php if($is_pdf): ?> <?= (($bin_data['nyamuk'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[nyamuk]" value="1" <?= ($bin_data['nyamuk'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Nyamuk <span class="italic-eng">Mosquito</span>
                        &nbsp;&nbsp;b. <?php if($is_pdf): ?> <?= (($bin_data['lalat'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[lalat]" value="1" <?= ($bin_data['lalat'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Lalat <span class="italic-eng">Flies</span>
                        &nbsp;&nbsp;c. <?php if($is_pdf): ?> <?= (($bin_data['kecoa'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[kecoa]" value="1" <?= ($bin_data['kecoa'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Kecoa <span class="italic-eng">Cockroach</span>
                        &nbsp;&nbsp;d. <?php if($is_pdf): ?> <?= (($bin_data['tikus'] ?? '') == '1') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="checkbox" class="radio-box align-middle" name="binatang_pengganggu[tikus]" value="1" <?= ($bin_data['tikus'] ?? '') == '1' ? 'checked' : '' ?><?php endif; ?>> Tikus <span class="italic-eng">Rodent</span>
                        &nbsp;&nbsp;d. Lain2 <span class="italic-eng">Others</span> <?php if($is_pdf): ?><?= $bin_data['lain2'] ?? '' ?><?php else: ?><input type="text" name="binatang_pengganggu[lain2]" class="form-control form-control-sm d-inline w-25 underline-input" value="<?= $bin_data['lain2'] ?? '' ?>"><?php endif; ?>
                    </td>
                </tr>
                
                <!-- 9 -->
                <tr>
                    <td class="text-center">9</td>
                    <td colspan="4">
                        <strong>Surat Keterangan Sanitasi Pesawat</strong><br><span class="italic-eng">Certificate Aircraft Sanitation</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        a. <?php if($is_pdf): ?> <?= (($aircraft['surat_sanitasi'] ?? '') == 'ada') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'ada' ? 'checked' : '' ?><?php endif; ?>> Ada / <span class="italic-eng">There are</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        b. <?php if($is_pdf): ?> <?= (($aircraft['surat_sanitasi'] ?? '') == 'tidak_ada') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="surat_sanitasi" value="tidak_ada" <?= ($aircraft['surat_sanitasi'] ?? '') == 'tidak_ada' ? 'checked' : '' ?><?php endif; ?>> Tidak Ada / <span class="italic-eng">Not Found</span>
                    </td>
                </tr>
                
                <!-- 10 -->
                <tr>
                    <td class="text-center">10</td>
                    <td colspan="4">
                        <strong>Kesimpulan / <span class="italic-eng">Conclusion</span> :</strong> &nbsp;&nbsp;
                        <?php if($is_pdf): ?> <?= (($aircraft['kesimpulan'] ?? '') == 'layak') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="kesimpulan" value="layak" <?= ($aircraft['kesimpulan'] ?? '') == 'layak' ? 'checked' : '' ?><?php endif; ?>> Layak / 
                        <?php if($is_pdf): ?> <?= (($aircraft['kesimpulan'] ?? '') == 'tidak_layak') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="kesimpulan" value="tidak_layak" <?= ($aircraft['kesimpulan'] ?? '') == 'tidak_layak' ? 'checked' : '' ?><?php endif; ?>> Tidak Layak 
                        diterbitkan "Sertifikat Bebas Serangga"<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="italic-eng">Qualifield / Unqualifield "Disinsection Exemption Certificate" issued.</span><br><br>
                        
                        <u><strong>Rekomendasi dan Tindakan* :</strong></u> &nbsp;&nbsp;&nbsp;
                        Perlu / tidak perlu tindakan penyehatan : &nbsp;&nbsp;&nbsp;
                        <?php if($is_pdf): ?> <?= (($aircraft['tindakan'] ?? '') == 'disinseksi') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="tindakan" value="disinseksi" <?= ($aircraft['tindakan'] ?? '') == 'disinseksi' ? 'checked' : '' ?><?php endif; ?>> Disinseksi &nbsp;&nbsp;&nbsp;
                        <?php if($is_pdf): ?> <?= (($aircraft['tindakan'] ?? '') == 'fumigasi') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="tindakan" value="fumigasi" <?= ($aircraft['tindakan'] ?? '') == 'fumigasi' ? 'checked' : '' ?><?php endif; ?>> Fumigasi <br>
                        <span class="italic-eng">Recommendation and Measure</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="italic-eng">Need / not necessary restructuring measures</span> &nbsp;&nbsp;&nbsp;
                        <?php if($is_pdf): ?> <?= (($aircraft['tindakan'] ?? '') == 'disinfeksi') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="tindakan" value="disinfeksi" <?= ($aircraft['tindakan'] ?? '') == 'disinfeksi' ? 'checked' : '' ?><?php endif; ?>> Disinfeksi &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php if($is_pdf): ?> <?= (($aircraft['tindakan'] ?? '') == 'general_cleaning') ? "[ &#10003; ]" : "[ &nbsp;&nbsp; ]" ?> <?php else: ?><input type="radio" class="radio-box align-middle" name="tindakan" value="general_cleaning" <?= ($aircraft['tindakan'] ?? '') == 'general_cleaning' ? 'checked' : '' ?><?php endif; ?>> General Cleaning
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="mb-4" style="font-size: 0.9em;">
            * &nbsp;&nbsp; Isilah tanda &#10003; Untuk jawaban dimaksud<br>
            &nbsp;&nbsp;&nbsp;&nbsp; <span class="italic-eng">Please tick &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appropriate</span>
        </p>

        <!-- Upload Foto Geotagging -->
        <?php if(!$is_pdf): ?>
        <div class="card mb-4 border-info shadow-sm">
            <div class="card-header bg-light py-2">
                <strong class="text-primary"><i class="fas fa-camera mr-1"></i> Foto Geotagging / Dokumentasi Pemeriksaan</strong><br>
                <span class="italic-eng text-muted">Geotagged Inspection Photo Documentation</span>
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

        <div class="row mt-5 text-center">
            <div class="col-6">
                <p><u>Petugas Kabin</u><br><span class="italic-eng">Cabin Crew</span></p>
                <?php if(!empty($aircraft['petugas_kabin_ttd'])): ?>
                    <img src="<?= $aircraft['petugas_kabin_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px;"><br>
                <?php else: ?>
                    <?php if(!$is_pdf): ?><canvas id="pad_kabin" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><?php endif; ?><br>
                    <?php if(!$is_pdf): ?><button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padKabin.clear()">Clear Tanda Tangan</button><?php endif; ?>
                <?php endif; ?>
                <br><br>
                ( <?php if($is_pdf): ?><?= $aircraft['petugas_kabin_nama'] ?? '' ?><?php else: ?><input type="text" name="petugas_kabin_nama" class="form-control underline-input d-inline w-50 text-center" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_kabin_nama'] ?? '' ?>"><?php endif; ?> )<br>
                <span class="italic-eng">Name & signature</span>
                <?php if($is_pdf): ?><?= $aircraft['petugas_kabin_ttd'] ?? '' ?><?php else: ?><input type="hidden" name="petugas_kabin_ttd" id="ttd_kabin" value="<?= $aircraft['petugas_kabin_ttd'] ?? '' ?>"><?php endif; ?>
            </div>
            <div class="col-6">
                <p><u>Petugas BKK</u><br><span class="italic-eng">Health Quarantine Officer</span></p>
                <?php if(!empty($aircraft['petugas_bkk_ttd'])): ?>
                    <img src="<?= $aircraft['petugas_bkk_ttd'] ?>" style="max-width:300px; height:150px; border:1px solid #ccc; border-radius: 5px;"><br>
                <?php else: ?>
                    <?php if(!$is_pdf): ?><canvas id="pad_bkk" width="300" height="150" style="border:1px solid #ccc; border-radius: 5px;"></canvas><?php endif; ?><br>
                    <?php if(!$is_pdf): ?><button type="button" class="btn btn-sm btn-secondary mt-2" onclick="padBkk.clear()">Clear Tanda Tangan</button><?php endif; ?>
                <?php endif; ?>
                <br><br>
                ( <?php if($is_pdf): ?><?= $aircraft['petugas_bkk_nama'] ?? '' ?><?php else: ?><input type="text" name="petugas_bkk_nama" class="form-control underline-input d-inline w-50 text-center" placeholder="Nama & tanda tangan" required value="<?= $aircraft['petugas_bkk_nama'] ?? '' ?>"><?php endif; ?> )<br>
                <span class="italic-eng">Name & signature</span>
                <?php if($is_pdf): ?><?= $aircraft['petugas_bkk_ttd'] ?? '' ?><?php else: ?><input type="hidden" name="petugas_bkk_ttd" id="ttd_bkk" value="<?= $aircraft['petugas_bkk_ttd'] ?? '' ?>"><?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-4">
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
<?php endif; ?>
<?php if(!$is_pdf): ?><script>
    <?php if(session()->getFlashdata('doc_link')): ?>
    $(document).ready(function() {
        $('#modalDocLink').modal('show');
    });
    <?php endif; ?>
    if(document.getElementById('pad_kabin')) {
        var padKabin = new SignaturePad(document.getElementById('pad_kabin'));
    }
    if(document.getElementById('pad_bkk')) {
        var padBkk = new SignaturePad(document.getElementById('pad_bkk'));
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
        if (typeof padKabin !== 'undefined' && !padKabin.isEmpty()) {
            document.getElementById('ttd_kabin').value = padKabin.toDataURL();
        }
        if (typeof padBkk !== 'undefined' && !padBkk.isEmpty()) {
            document.getElementById('ttd_bkk').value = padBkk.toDataURL();
        }
    });
</script><?php endif; ?>
</body>
</html>