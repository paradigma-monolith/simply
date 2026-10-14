<?php
$areas = isset($sanitasi['area_diperiksa']) ? json_decode($sanitasi['area_diperiksa'], true) : [];
$maxIndex = 0;
if(is_array($areas) && count($areas) > 0) {
    foreach($areas as $idx => $area) {
        $maxIndex = max($maxIndex, (int)$idx);
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

        /* Print Mode Styles */
        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            body { background-color: #fff !important; color: #000; font-size: 11px; }
            .wrapper, .content-wrapper { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            
            .screen-only { display: none !important; }
            .print-only { display: block !important; }
            
            .table-print { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            .table-print th, .table-print td { border: 1px solid #000; padding: 6px; vertical-align: top; }
            .table-print th { background-color: #e9ecef !important; -webkit-print-color-adjust: exact; text-align: center; font-weight: bold; }
            .table-borderless td { border: none !important; padding: 4px; }
            
            .kop-surat { margin-top: 0; margin-bottom: 15px; }
            .kop-surat img { width: 100% !important; max-width: 100% !important; max-height: none !important; }
            h4 { font-size: 16px; margin: 5px 0; }
            p { margin: 2px 0; }
        }
        
        @media screen {
            .print-only { display: none !important; }
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
                <h4 class="font-weight-bold">SERTIFIKAT PENGENDALIAN SANITASI KAPAL</h4>
                <p class="text-muted"><i>Ship Sanitation Control Certificate</i></p>
            </div>
        </div>

        <?php if(!empty($is_print)): ?>
        <!-- ============================================== -->
        <!-- PRINT ONLY LAYOUT (COMPACT & TABULAR)          -->
        <!-- ============================================== -->
        <div class="container-fluid print-only px-4">
            
            <!-- Informasi Umum -->
            <table class="table-print">
                <tr>
                    <td width="15%"><b>Nama Pelabuhan</b><br><i>Port of</i></td>
                    <td width="20%"><?= $sanitasi['pelabuhan'] ?? '' ?></td>
                    <td width="15%"><b>Tanggal</b><br><i>Date</i></td>
                    <td width="15%"><?= !empty($sanitasi['tanggal']) ? date('d M Y', strtotime($sanitasi['tanggal'])) : '' ?></td>
                    <td width="15%"><b>Sertifikat</b><br><i>Certificate</i></td>
                    <td width="20%"><?= $sanitasi['sertifikat_mencatat'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Nama Kapal</b><br><i>Name of ship</i></td>
                    <td><?= $sanitasi['nama_kapal'] ?? '' ?></td>
                    <td><b>IMO No</b><br><i>Registration</i></td>
                    <td><?= $sanitasi['imo_no'] ?? '' ?></td>
                    <td><b>Bendera</b><br><i>Flag</i></td>
                    <td><?= $sanitasi['bendera'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Jenis Kapal</b><br><i>Ship Type</i></td>
                    <td><?= $sanitasi['jenis_kapal'] ?? '' ?> (<?= $sanitasi['detail_jenis_kapal'] ?? '' ?>)</td>
                    <td><b>Berat (GT)</b><br><i>Gross Tonnage</i></td>
                    <td><?= $sanitasi['berat_gt'] ?? '' ?></td>
                    <td><b>Kondisi Palka</b><br><i>Holds</i></td>
                    <td><?= $sanitasi['kondisi_palka'] ?? '' ?></td>
                </tr>
                <tr>
                    <td><b>Nama Pemeriksa</b><br><i>Inspector Name</i></td>
                    <td colspan="2"><?= $sanitasi['nama_pemeriksa'] ?? '' ?></td>
                    <td><b>Alamat</b><br><i>Address</i></td>
                    <td colspan="2"><?= $sanitasi['alamat_pemeriksa'] ?? '' ?></td>
                </tr>
            </table>

            <!-- Area Pemeriksaan -->
            <h6 class="font-weight-bold mt-3 mb-2">Hasil Pemeriksaan Area / Fasilitas <i>(Inspection Results)</i></h6>
            <table class="table-print">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="15%">Area / Fasilitas<br><i>Areas / Facilities</i></th>
                        <th width="8%">Status</th>
                        <th width="16%">Bukti Ditemukan<br><i>Evidence Found</i></th>
                        <th width="12%">Hasil Sampel<br><i>Sample Result</i></th>
                        <th width="14%">Dokumen Diperiksa<br><i>Documents</i></th>
                        <th width="12%">Tindakan Pengendalian<br><i>Control Measures</i></th>
                        <th width="10%">Tgl Ulang<br><i>Re-inspect</i></th>
                        <th width="10%">Komentar<br><i>Comments</i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($areas) > 0): $no=1; foreach($areas as $area): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><b><?= $area['nama'] ?? '' ?></b></td>
                        <td class="text-center"><?= $area['status'] ?? '' ?></td>
                        <td><?= $area['bukti'] ?? '-' ?></td>
                        <td><?= $area['sampel'] ?? '-' ?></td>
                        <td><?= $area['dokumen'] ?? '-' ?></td>
                        <td><?= $area['tindakan'] ?? '-' ?></td>
                        <td class="text-center"><?= !empty($area['tanggal_ulang']) ? date('d/m/Y', strtotime($area['tanggal_ulang'])) : '-' ?></td>
                        <td><?= $area['komentar'] ?? '-' ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-3">Tidak ada data area yang dicatat</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Keterangan & TTD -->
            <table class="table-print table-borderless mt-3">
                <tr>
                    <td width="15%"><b>Keterangan :</b></td>
                    <td width="55%"><?= nl2br($sanitasi['keterangan'] ?? '-') ?></td>
                    <td width="30%" class="text-center">
                        <p class="mb-5">Mengetahui / Pejabat Penerbit,<br><i>Issuing Authority</i></p>
                        <?php if(!empty($sanitasi['tanda_tangan'])): ?>
                            <img src="<?= $sanitasi['tanda_tangan'] ?>" style="max-height: 80px; margin-bottom: 10px;" alt="Tanda Tangan">
                        <?php else: ?>
                            <br><br>
                        <?php endif; ?>
                        <p class="mb-0"><b><u><?= $sanitasi['pejabat_penerbit'] ?? '( .......................................... )' ?></u></b></p>
                    </td>
                </tr>
            </table>

        </div>
        
        <?php else: ?>
        <!-- ============================================== -->
        <!-- SCREEN ONLY LAYOUT (FORM EDIT)                 -->
        <!-- ============================================== -->
        <div class="content pb-5 screen-only">
            <div class="container">
                <form action="<?= base_url('travelconsent/update_sanitasi/' . $sanitasi['id']) ?>" method="POST" id="formSanitasi">
                    
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
                                        <option value="Pembebasan" <?= ($sanitasi['sertifikat_mencatat'] ?? '') == 'Pembebasan' ? 'selected' : '' ?>>Pembebasan (Exemption)</option>
                                        <option value="Pengendalian" <?= ($sanitasi['sertifikat_mencatat'] ?? '') == 'Pengendalian' ? 'selected' : '' ?>>Pengendalian (Control)</option>
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
                                    <select name="bendera" class="form-control select2">
                                        <option value="">-- Pilih Bendera --</option>
                                        <option value="Indonesia" <?= ($sanitasi['bendera'] ?? '') == 'Indonesia' ? 'selected' : '' ?>>Indonesia</option>
                                        <option value="Singapura" <?= ($sanitasi['bendera'] ?? '') == 'Singapura' ? 'selected' : '' ?>>Singapura</option>
                                        <option value="Malaysia" <?= ($sanitasi['bendera'] ?? '') == 'Malaysia' ? 'selected' : '' ?>>Malaysia</option>
                                        <option value="Lainnya" <?= ($sanitasi['bendera'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
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
                                <?php if(count($areas) > 0): foreach($areas as $idx => $area): ?>
                                    <div class="card card-outline card-info card-area" id="area-card-<?= $idx ?>">
                                        <div class="card-header">
                                            <h3 class="card-title font-weight-bold"><?= $area['nama'] ?? '' ?></h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool text-danger btn-remove-area" data-id="<?= $idx ?>">
                                                    <i class="fas fa-trash fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="areas[<?= $idx ?>][nama]" value="<?= $area['nama'] ?? '' ?>">
                                            
                                            <div class="form-group mb-4 p-3 bg-light rounded border">
                                                <label class="d-block mb-2">Status Pemeriksaan: <span class="text-danger">*</span></label>
                                                <div class="icheck-primary d-inline mr-4">
                                                    <input type="radio" id="status_ya_<?= $idx ?>" name="areas[<?= $idx ?>][status]" value="Ya" <?= ($area['status'] ?? '') == 'Ya' ? 'checked' : '' ?> required>
                                                    <label for="status_ya_<?= $idx ?>">Ya</label>
                                                </div>
                                                <div class="icheck-primary d-inline mr-4">
                                                    <input type="radio" id="status_tidak_<?= $idx ?>" name="areas[<?= $idx ?>][status]" value="Tidak Ditemukan" <?= ($area['status'] ?? '') == 'Tidak Ditemukan' ? 'checked' : '' ?>>
                                                    <label for="status_tidak_<?= $idx ?>">Tidak Ditemukan</label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="status_na_<?= $idx ?>" name="areas[<?= $idx ?>][status]" value="N/A" <?= ($area['status'] ?? '') == 'N/A' ? 'checked' : '' ?>>
                                                    <label for="status_na_<?= $idx ?>">Tidak ada (N/A)</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Bukti yang ditemukan</label>
                                                    <input type="text" name="areas[<?= $idx ?>][bukti]" class="form-control" placeholder="Tuliskan bukti..." value="<?= $area['bukti'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Hasil sampel</label>
                                                    <input type="text" name="areas[<?= $idx ?>][sampel]" class="form-control" placeholder="Tuliskan hasil..." value="<?= $area['sampel'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Dokumen yang diperiksa</label>
                                                    <input type="text" name="areas[<?= $idx ?>][dokumen]" class="form-control" placeholder="Nama dokumen..." value="<?= $area['dokumen'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Tindakan Pengendalian yang dilakukan</label>
                                                    <input type="text" name="areas[<?= $idx ?>][tindakan]" class="form-control" placeholder="Tindakan..." value="<?= $area['tindakan'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Tanggal pemeriksaan ulang</label>
                                                    <input type="date" name="areas[<?= $idx ?>][tanggal_ulang]" class="form-control" value="<?= $area['tanggal_ulang'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Komentar terhadap kondisi yang ditemukan</label>
                                                    <input type="text" name="areas[<?= $idx ?>][komentar]" class="form-control" placeholder="Komentar..." value="<?= $area['komentar'] ?? '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>
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
                                                <i class="fas fa-eraser mr-1"></i> Clear Tanda Tangan
                                            </button>
                                            <?php if(!empty($sanitasi['tanda_tangan'])): ?>
                                                <button type="button" class="btn btn-sm btn-secondary mt-2 ml-2" id="btnCancelSignature">Batal Ubah</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STICKY FOOTER -->
                    <div class="sticky-footer d-flex justify-content-between align-items-center rounded mt-4">
                        <a href="<?= base_url('travelconsent/index') ?>" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold">Update Sertifikat</button>
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
    $(document).ready(function() {
        <?php if(empty($is_print)): ?>
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        let areaCount = <?= isset($maxIndex) ? $maxIndex : 0 ?>;

        $('#btnAddArea').click(function() {
            let areaName = $('#areaSelector').val();
            if(!areaName) {
                alert('Silakan pilih Area/Fasilitas terlebih dahulu!');
                return;
            }
            areaCount++;
            let index = areaCount;

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
            $('#areaSelector').val('').trigger('change');
        });

        $('#dynamicAreaContainer').on('click', '.btn-remove-area', function() {
            let id = $(this).data('id');
            if(confirm('Yakin ingin menghapus area ini dari form?')) {
                $('#area-card-' + id).fadeOut(300, function() { $(this).remove(); });
            }
        });

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

<?php if(!empty($is_print)): ?>
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