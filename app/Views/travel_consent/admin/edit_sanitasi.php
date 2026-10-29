<?php
$areas = isset($sanitasi['area_diperiksa']) ? json_decode($sanitasi['area_diperiksa'], true) : [];
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
$savedAreasByName = [];
if (is_array($areas)) {
    foreach ($areas as $area) {
        if (!empty($area['nama'])) {
            $savedAreasByName[$area['nama']] = $area;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($is_print) ? 'Cetak' : 'Edit' ?> Sanitasi Kapal</title>
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
        /* Base / Screen Styles */
        body { background-color: #f4f6f9; font-size: 14px; }
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat img { max-height: 100px; }
        .sticky-footer {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .card-area { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table-print { width: 100%; border-collapse: collapse; margin-bottom: 15px; background: #fff; }
        .table-print th, .table-print td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        .table-print th { background-color: #e9ecef !important; -webkit-print-color-adjust: exact; text-align: center; font-weight: bold; }
        .table-borderless td { border: none !important; padding: 4px; }

        /* Print Mode Styles */
        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            body { background-color: #fff !important; color: #000; font-size: 11px; }
            .wrapper, .content-wrapper { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            
            .screen-only, .d-print-none { display: none !important; }
            .print-only { display: block !important; }
            
            .kop-surat { margin-top: 0; margin-bottom: 15px; }
            .kop-surat img { width: 100% !important; max-width: 100% !important; max-height: none !important; }
            h4 { font-size: 16px; margin: 5px 0; }
            p { margin: 2px 0; }
        }
        
        <?php if(empty($is_print)): ?>
        @media screen {
            .print-only { display: none !important; }
        }
        <?php endif; ?>

        /* Responsive Mobile Styles for Document View */
        @media screen and (max-width: 767.98px) {
            body { font-size: 12px; }
            .kop-surat { margin-top: 8px !important; margin-bottom: 10px !important; }
            .kop-surat img { max-height: 70px; }
            .kop-surat h4 { font-size: 14px; margin-top: 6px; margin-bottom: 2px; }
            .kop-surat p { font-size: 11px; margin-bottom: 0; }
            .print-only { padding-left: 8px !important; padding-right: 8px !important; padding-bottom: 20px !important; }
            .table-info-umum, .table-info-umum tbody { display: block; width: 100%; border: none; }
            .table-info-umum tr { display: flex; flex-wrap: wrap; width: 100%; }
            .table-info-umum td {
                box-sizing: border-box;
                font-size: 11.5px;
                padding: 4px 6px;
                border: 1px solid #000;
                margin-top: -1px;
                word-break: break-word;
            }
            .table-info-umum td:nth-child(odd) {
                width: 42% !important;
                background-color: #f8f9fa;
            }
            .table-info-umum td:nth-child(even) {
                width: 58% !important;
                margin-left: -1px;
            }
            .table-area-scroll {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-bottom: 12px;
                border: 1px solid #000;
            }
            .table-area-print {
                min-width: 680px;
                margin-bottom: 0;
                font-size: 11px;
                border: none;
            }
            .table-area-print th, .table-area-print td {
                padding: 4px 5px;
            }
            .table-ket-ttd, .table-ket-ttd tbody, .table-ket-ttd tr {
                display: block;
                width: 100%;
            }
            .table-ket-ttd td:nth-child(1) {
                display: block;
                width: 100% !important;
                padding-bottom: 2px !important;
            }
            .table-ket-ttd td:nth-child(2) {
                display: block;
                width: 100% !important;
                padding-top: 0 !important;
                padding-bottom: 10px !important;
                margin-bottom: 10px;
                border-bottom: 1px dashed #ccc !important;
            }
            .table-ket-ttd td:nth-child(3) {
                display: block;
                width: 100% !important;
                text-align: center;
            }
        }
    </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <div class="content-wrapper">
        
        <!-- HEADER (Shared) -->
        <div class="container-fluid">
            <div class="kop-surat mt-3">
                <img src="<?= base_url('dist/img/Kop_BKK_bandung.png') ?>" alt="Kop Surat" style="max-width: 100%; height: auto;">
                <h4 class="font-weight-bold mt-2">SERTIFIKAT PENGENDALIAN SANITASI KAPAL</h4>
            </div>
        </div>

        <?php if(!empty($is_print)): ?>
        <!-- ============================================== -->
        <!-- PRINT ONLY LAYOUT (COMPACT & TABULAR)          -->
        <!-- ============================================== -->
        <div class="container-fluid print-only px-4 pb-5">
            <div class="text-right mb-3 d-print-none">
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Cetak / Simpan PDF
                </button>
            </div>
            
            <!-- Informasi Umum -->
            <table class="table-print table-info-umum">
                <tr>
                    <td width="15%"><b>Nama Pelabuhan</b></td>
                    <td width="20%"><?= $sanitasi['pelabuhan'] ?? '' ?></td>
                    <td width="15%"><b>Tanggal</b></td>
                    <td width="15%"><?= !empty($sanitasi['tanggal']) ? date('d M Y', strtotime($sanitasi['tanggal'])) : '' ?></td>
                    <td width="15%"><b>Sertifikat</b></td>
                    <td width="20%"><?= $sanitasi['sertifikat_mencatat'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Nama Kapal</b></td>
                    <td><?= $sanitasi['nama_kapal'] ?? '' ?></td>
                    <td><b>Nomor Registrasi / IMO</b></td>
                    <td><?= $sanitasi['imo_no'] ?? '' ?></td>
                    <td><b>Bendera</b></td>
                    <td><?= $sanitasi['bendera'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Jenis Kapal</b></td>
                    <td><?= $sanitasi['jenis_kapal'] ?? '' ?> (<?= $sanitasi['detail_jenis_kapal'] ?? '' ?>)</td>
                    <td><b>Berat (GT)</b></td>
                    <td><?= $sanitasi['berat_gt'] ?? '' ?></td>
                    <td><b>Kondisi Palka</b></td>
                    <td><?= $sanitasi['kondisi_palka'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Nama Pemeriksa</b></td>
                    <td colspan="2"><?= $sanitasi['nama_pemeriksa'] ?? '' ?></td>
                    <td><b>Alamat</b></td>
                    <td colspan="2"><?= $sanitasi['alamat_pemeriksa'] ?? '' ?></td>
                </tr>
            </table>

            <!-- Area Pemeriksaan -->
            <h6 class="font-weight-bold mt-3 mb-1">Hasil Pemeriksaan Area / Fasilitas</h6>
            <small class="text-muted d-block d-md-none mb-1 d-print-none"><i class="fas fa-arrows-alt-h mr-1"></i>Geser tabel ke samping untuk melihat detail seluruh kolom</small>
            <div class="table-area-scroll">
                <table class="table-print table-area-print">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="15%">Area / Fasilitas</th>
                            <th width="13%">Status Pemeriksaan</th>
                            <th width="14%">Bukti Ditemukan</th>
                            <th width="11%">Hasil Sampel</th>
                            <th width="13%">Dokumen Diperiksa</th>
                            <th width="13%">Tindakan Pengendalian</th>
                            <th width="9%">Tgl Pemeriksaan Ulang</th>
                            <th width="9%">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($allAreas as $areaName):
                            $area = $savedAreasByName[$areaName] ?? [];
                            $rawStatus = $area['status'] ?? '';
                            if ($rawStatus === 'Tidak Ditemukan' || $rawStatus === '') {
                                $displayStatus = $rawStatus === '' ? '-' : 'Tidak Ditemukan Faktor Risiko';
                            } elseif ($rawStatus === 'Ya') {
                                $displayStatus = 'Ditemukan Faktor Risiko';
                            } elseif ($rawStatus === 'N/A') {
                                $displayStatus = 'Tidak Ada';
                            } else {
                                $displayStatus = $rawStatus;
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><b><?= esc($areaName) ?></b></td>
                            <td class="text-center"><?= esc($displayStatus) ?></td>
                            <td><?= !empty($area['bukti']) ? esc($area['bukti']) : '-' ?></td>
                            <td><?= !empty($area['sampel']) ? esc($area['sampel']) : '-' ?></td>
                            <td><?= !empty($area['dokumen']) ? esc($area['dokumen']) : '-' ?></td>
                            <td><?= !empty($area['tindakan']) ? esc($area['tindakan']) : '-' ?></td>
                            <td class="text-center"><?= !empty($area['tanggal_ulang']) ? date('d/m/Y', strtotime($area['tanggal_ulang'])) : '-' ?></td>
                            <td><?= !empty($area['komentar']) ? esc($area['komentar']) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Keterangan & TTD -->
            <table class="table-print table-borderless table-ket-ttd mt-3">
                <tr>
                    <td width="15%"><b>Keterangan :</b></td>
                    <td width="55%"><?= nl2br($sanitasi['keterangan'] ?? '-') ?></td>
                    <td width="30%" class="text-center">
                        <p class="mb-3">Mengetahui / Pejabat Penerbit,</p>
                        <?php if(!empty($sanitasi['tanda_tangan'])): ?>
                            <img src="<?= $sanitasi['tanda_tangan'] ?>" style="max-height: 80px; margin-bottom: 10px;" alt="Tanda Tangan">
                        <?php else: ?>
                            <br><br>
                        <?php endif; ?>
                        <p class="mb-0"><b><u><?= $sanitasi['pejabat_penerbit'] ?? '( .......................................... )' ?></u></b></p>
                    </td>
                </tr>
            </table>

            <?php if(!empty($sanitasi['foto_geotagging'])): ?>
            <div class="mt-4 text-center" style="page-break-inside: avoid;">
                <p class="font-weight-bold mb-2" style="font-size: 13px;"><u>Lampiran: Bukti Foto Geotagging</u></p>
                <img src="<?= base_url($sanitasi['foto_geotagging']) ?>" style="max-height: 550px; max-width: 100%; border: 1px solid #ddd; padding: 4px;" alt="Bukti Foto Geotagging">
            </div>
            <?php endif; ?>

        </div>
        
        <?php else: ?>
        <!-- ============================================== -->
        <!-- SCREEN ONLY LAYOUT (FORM EDIT)                 -->
        <!-- ============================================== -->
        <div class="content pb-5 screen-only">
            <div class="container-fluid" style="max-width: 1400px;">
                <form action="<?= base_url('travelconsent/update_sanitasi/' . $sanitasi['id']) ?>" method="POST" id="formSanitasi" enctype="multipart/form-data">
                    
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
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama Pelabuhan <span class="text-danger">*</span></label>
                                    <input type="text" name="pelabuhan" class="form-control" required value="<?= $sanitasi['pelabuhan'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control" required value="<?= $sanitasi['tanggal'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Sertifikat ini mencatat pemeriksaan dan: <span class="text-danger">*</span></label>
                                    <select name="sertifikat_mencatat" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Pembebasan" <?= ($sanitasi['sertifikat_mencatat'] ?? '') == 'Pembebasan' ? 'selected' : '' ?>>Pembebasan</option>
                                        <option value="Pengendalian" <?= ($sanitasi['sertifikat_mencatat'] ?? '') == 'Pengendalian' ? 'selected' : '' ?>>Pengendalian</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nomor Registrasi/IMO No <span class="text-danger">*</span></label>
                                    <input type="text" name="imo_no" class="form-control" required value="<?= $sanitasi['imo_no'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Jenis Kapal <span class="text-danger">*</span></label>
                                    <select name="jenis_kapal" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Penumpang" <?= ($sanitasi['jenis_kapal'] ?? '') == 'Penumpang' ? 'selected' : '' ?>>Penumpang</option>
                                        <option value="Kargo" <?= ($sanitasi['jenis_kapal'] ?? '') == 'Kargo' ? 'selected' : '' ?>>Kargo</option>
                                        <option value="Tanker" <?= ($sanitasi['jenis_kapal'] ?? '') == 'Tanker' ? 'selected' : '' ?>>Tangki/Tanker</option>
                                        <option value="Lainnya" <?= ($sanitasi['jenis_kapal'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Detail Jenis Kapal <span class="text-danger">*</span></label>
                                    <select name="detail_jenis_kapal" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Domestik" <?= ($sanitasi['detail_jenis_kapal'] ?? '') == 'Domestik' ? 'selected' : '' ?>>Domestik</option>
                                        <option value="Internasional" <?= ($sanitasi['detail_jenis_kapal'] ?? '') == 'Internasional' ? 'selected' : '' ?>>Internasional</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama kapal atau kapal pelayaran antar pulau <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_kapal" class="form-control" required value="<?= $sanitasi['nama_kapal'] ?? '' ?>">
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
                                            <option value="<?= $country ?>" <?= ($sanitasi['bendera'] ?? '') == $country ? 'selected' : '' ?>><?= $country ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Berat(GT) (tanpa titik/koma) <span class="text-danger">*</span></label>
                                    <input type="number" name="berat_gt" class="form-control" required value="<?= $sanitasi['berat_gt'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Pada waktu diperiksa, palka-palka <span class="text-danger">*</span></label>
                                    <select name="kondisi_palka" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Kosong" <?= ($sanitasi['kondisi_palka'] ?? '') == 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                                        <option value="Isi" <?= ($sanitasi['kondisi_palka'] ?? '') == 'Isi' ? 'selected' : '' ?>>Bersi/Isi muatan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Nama petugas pemeriksa <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_pemeriksa" class="form-control" required value="<?= $sanitasi['nama_pemeriksa'] ?? '' ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Alamat pemeriksa <span class="text-danger">*</span></label>
                                    <textarea name="alamat_pemeriksa" class="form-control" rows="2" required><?= $sanitasi['alamat_pemeriksa'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: AREA / FASILITAS (TABLE) -->
                    <div class="card card-success">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Bagian 2: Area / Fasilitas yang Diperiksa</h3>
                            <div class="card-tools mt-2 mt-sm-0">
                                <span class="text-white small mr-2">Atur Semua Status:</span>
                                <button type="button" class="btn btn-xs btn-light font-weight-bold mr-1" onclick="setAllAreaStatus('Tidak Ditemukan Faktor Risiko')">Tidak Ditemukan Faktor Risiko</button>
                                <button type="button" class="btn btn-xs btn-light font-weight-bold mr-1" onclick="setAllAreaStatus('Ditemukan Faktor Risiko')">Ditemukan Faktor Risiko</button>
                                <button type="button" class="btn btn-xs btn-light font-weight-bold mr-1" onclick="setAllAreaStatus('Tidak Ada')">Tidak Ada</button>
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="setAllAreaStatus('')">Kosongkan</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm mb-0" style="min-width: 1150px;">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="align-middle" style="width: 40px;">No</th>
                                            <th class="align-middle text-left" style="min-width: 200px;">Area / Fasilitas yang Diperiksa</th>
                                            <th class="align-middle" style="min-width: 215px;">Status Pemeriksaan <span class="text-danger">*</span></th>
                                            <th class="align-middle" style="min-width: 140px;">Bukti yang Ditemukan</th>
                                            <th class="align-middle" style="min-width: 130px;">Hasil Sampel</th>
                                            <th class="align-middle" style="min-width: 140px;">Dokumen yang Diperiksa</th>
                                            <th class="align-middle" style="min-width: 150px;">Tindakan Pengendalian</th>
                                            <th class="align-middle" style="width: 135px;">Tgl Pemeriksaan Ulang</th>
                                            <th class="align-middle" style="min-width: 140px;">Komentar Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allAreas as $idx => $areaName):
                                            $index = $idx + 1;
                                            $saved = $savedAreasByName[$areaName] ?? [];
                                            $savedStatus = $saved['status'] ?? '';
                                            if ($savedStatus === '' || $savedStatus === 'Tidak Ditemukan') {
                                                $savedStatus = 'Tidak Ditemukan Faktor Risiko';
                                            } elseif ($savedStatus === 'Ya') {
                                                $savedStatus = 'Ditemukan Faktor Risiko';
                                            } elseif ($savedStatus === 'N/A') {
                                                $savedStatus = 'Tidak Ada';
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center align-middle font-weight-bold"><?= $index ?></td>
                                            <td class="align-middle font-weight-bold">
                                                <?= esc($areaName) ?>
                                                <input type="hidden" name="areas[<?= $index ?>][nama]" value="<?= esc($areaName) ?>">
                                            </td>
                                            <td class="align-middle">
                                                <select name="areas[<?= $index ?>][status]" class="form-control form-control-sm area-status-select" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Tidak Ditemukan Faktor Risiko" <?= $savedStatus === 'Tidak Ditemukan Faktor Risiko' ? 'selected' : '' ?>>Tidak Ditemukan Faktor Risiko</option>
                                                    <option value="Ditemukan Faktor Risiko" <?= $savedStatus === 'Ditemukan Faktor Risiko' ? 'selected' : '' ?>>Ditemukan Faktor Risiko</option>
                                                    <option value="Tidak Ada" <?= $savedStatus === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                                                </select>
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $index ?>][bukti]" class="form-control form-control-sm" placeholder="Bukti..." value="<?= esc($saved['bukti'] ?? '') ?>">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $index ?>][sampel]" class="form-control form-control-sm" placeholder="Hasil sampel..." value="<?= esc($saved['sampel'] ?? '') ?>">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $index ?>][dokumen]" class="form-control form-control-sm" placeholder="Nama dokumen..." value="<?= esc($saved['dokumen'] ?? '') ?>">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $index ?>][tindakan]" class="form-control form-control-sm" placeholder="Tindakan..." value="<?= esc($saved['tindakan'] ?? '') ?>">
                                            </td>
                                            <td class="align-middle">
                                                <input type="date" name="areas[<?= $index ?>][tanggal_ulang]" class="form-control form-control-sm" value="<?= esc($saved['tanggal_ulang'] ?? '') ?>">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" name="areas[<?= $index ?>][komentar]" class="form-control form-control-sm" placeholder="Komentar..." value="<?= esc($saved['komentar'] ?? '') ?>">
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
                                    <input type="text" name="pejabat_penerbit" class="form-control" value="<?= $sanitasi['pejabat_penerbit'] ?? '' ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control" rows="4" required><?= $sanitasi['keterangan'] ?? '' ?></textarea>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Tanda Tangan Pejabat Penerbit <span class="text-danger">*</span></label>
                                    
                                    <div id="signature-container">
                                        <?php if(!empty($sanitasi['tanda_tangan'])): ?>
                                            <div id="existing-signature" class="mb-3">
                                                <img src="<?= $sanitasi['tanda_tangan'] ?>" class="border p-2 bg-white" style="max-height: 150px; display: block;" alt="Tanda Tangan">
                                                <input type="hidden" name="tanda_tangan" id="tanda_tangan_existing" value="<?= $sanitasi['tanda_tangan'] ?>">
                                                <button type="button" class="btn btn-sm btn-warning mt-2" id="btnChangeSignature">Ubah Tanda Tangan</button>
                                            </div>
                                        <?php endif; ?>

                                        <div id="canvas-container" <?= !empty($sanitasi['tanda_tangan']) ? 'style="display:none;"' : '' ?>>
                                            <div class="border rounded bg-white" style="position: relative; touch-action: none;">
                                                <canvas id="signatureCanvas" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
                                            </div>
                                            <input type="hidden" name="tanda_tangan" id="tanda_tangan" <?= empty($sanitasi['tanda_tangan']) ? 'required' : '' ?>>
                                            <button type="button" class="btn btn-sm btn-danger mt-2" id="btnClearSignature">
                                                <i class="fas fa-eraser mr-1"></i> Hapus Tanda Tangan
                                            </button>
                                            <?php if(!empty($sanitasi['tanda_tangan'])): ?>
                                                <button type="button" class="btn btn-sm btn-secondary mt-2 ml-2" id="btnCancelSignature">Batal Ubah</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="font-weight-bold"><i class="fas fa-camera mr-1 text-primary"></i> Foto Geotagging / Dokumentasi Pemeriksaan</label>
                                    <?php if(!empty($sanitasi['foto_geotagging'])): ?>
                                        <div class="mb-3 text-center">
                                            <p class="mb-1 text-muted small font-weight-bold">Foto Geotagging Tersimpan:</p>
                                            <a href="<?= base_url($sanitasi['foto_geotagging']) ?>" target="_blank" class="d-inline-block">
                                                <img src="<?= base_url($sanitasi['foto_geotagging']) ?>" alt="Foto Geotagging" class="img-thumbnail shadow-sm" style="max-height: 450px; max-width: 100%;">
                                            </a>
                                            <div class="mt-1">
                                                <a href="<?= base_url($sanitasi['foto_geotagging']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-expand mr-1"></i> Buka Ukuran Penuh
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="foto_geotagging" id="foto_geotagging" accept="image/*" onchange="previewGeotag(this, 'geotag_preview')">
                                        <label class="custom-file-label" for="foto_geotagging"><?= !empty($sanitasi['foto_geotagging']) ? 'Pilih file foto pengganti...' : 'Pilih file foto geotagging...' ?></label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG (Maks 10MB). Biarkan kosong jika tidak ingin mengubah foto.</small>
                                    <div id="geotag_preview_wrapper" class="mt-3 text-center" style="display:none;">
                                        <p class="mb-1 text-muted small font-weight-bold">Pratinjau Foto Baru:</p>
                                        <img id="geotag_preview" src="" alt="Pratinjau Geotagging" class="img-thumbnail shadow-sm" style="max-height: 450px; max-width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STICKY FOOTER -->
                    <div class="sticky-footer d-flex justify-content-between align-items-center flex-wrap rounded mt-4">
                        <a href="<?= base_url('travelconsent/index') ?>" class="btn btn-secondary px-4">Kembali</a>
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
        <?php endif; ?>
        
    </div>
</div>

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- Select2 -->
<script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>

<script>
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
        <?php if(empty($is_print)): ?>
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        $(document).on('change', '.area-status-select', function() {
            updateStatusProgress();
        });

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
            if(canvas) {
                const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.scale(ratio, ratio);
            }
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
            return { x: clientX - rect.left, y: clientY - rect.top };
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

        if(canvas) {
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);
            canvas.addEventListener('touchstart', startDrawing, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            canvas.addEventListener('touchend', stopDrawing);

            btnClear.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                inputSignature.value = '';
            });
        }

        // Toggle ubah tanda tangan
        $('#btnChangeSignature').click(function() {
            $('#existing-signature').hide();
            $('#canvas-container').show();
            $('#tanda_tangan_existing').attr('name', 'tanda_tangan_old');
            $('#tanda_tangan').attr('required', true);
            resizeCanvas(); // ensure canvas sized properly after unhidden
        });

        $('#btnCancelSignature').click(function() {
            $('#canvas-container').hide();
            $('#existing-signature').show();
            $('#tanda_tangan_existing').attr('name', 'tanda_tangan');
            $('#tanda_tangan').removeAttr('required');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            inputSignature.value = '';
        });

        // Form Submit Validation
        $('#formSanitasi').on('submit', function(e) {
            if (!highlightUnfilledStatus()) {
                e.preventDefault();
                return false;
            }
            let requiredSig = $('#tanda_tangan').prop('required');
            if (requiredSig && !inputSignature.value) {
                e.preventDefault();
                alert('Silakan isi tanda tangan terlebih dahulu!');
                if(canvas) canvas.focus();
            }
        });
        <?php endif; ?>
    });
</script>

<?php if(!empty($is_print) && empty($is_public_doc)): ?>
<script>
    // Langsung buka dialog print saat halaman cetak dimuat
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    }
</script>
<?php endif; ?>
</body>
</html>