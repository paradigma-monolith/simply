<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Layanan Kesehatan Kapal</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }

        .kop-surat {
            width: 100%;
            margin-bottom: 20px;
        }

        .kop-surat img {
            width: 100%;
            height: auto;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-info td {
            vertical-align: top;
            padding: 3px 0;
        }

        .col-label {
            width: 250px;
        }

        .col-colon {
            width: 20px;
            text-align: center;
        }

        .col-value {
            width: auto;
        }

        .checkbox-item {
            margin-bottom: 5px;
        }
        
        .checkbox-item table {
            width: 100%;
        }

        .checkbox-item td.check-col {
            width: 30px;
            text-align: center;
            vertical-align: middle;
        }

        .checkbox-item td.label-col {
            vertical-align: middle;
        }
        
        .checkbox-item td.date-col {
            vertical-align: middle;
            font-style: italic;
            font-size: 10pt;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }

        .signature-space {
            height: 80px;
        }
        
        .line-input {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 150px;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="<?= $kop ?>" alt="Kop Surat">
    </div>

    <div class="title">
        PERMOHONAN PENGAJUAN LAYANAN KESEHATAN KAPAL
    </div>

    <!-- I. Informasi Kedatangan -->
    <div class="section-title">
        I. Informasi Kedatangan Kapal dalam Negeri
    </div>
    <div style="padding-left: 20px;">
        <table class="table-info">
            <tr>
                <td class="col-label" style="width: 320px;">Nama Kapal</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= esc($dataLayanan['nama_kapal']) ?></td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Waktu Kedatangan</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= date('d/m/Y H:i', strtotime($dataLayanan['waktu_kedatangan'])) ?></td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Pelabuhan Asal</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= esc($dataLayanan['pelabuhan_asal']) ?></td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Pelabuhan Tujuan</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value">
                    <?php 
                        $pelabuhan_tujuan = esc($dataLayanan['pelabuhan_tujuan']);
                        if ($pelabuhan_tujuan == 'pelabuhan_ratu') {
                            echo 'Pelabuhan Ratu';
                        } else {
                            echo ucfirst($pelabuhan_tujuan);
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Lokasi Sandar</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= esc($dataLayanan['lokasi_sandar']) ?></td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Jarak Kapal (mil dari jetty)</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= esc($dataLayanan['jarak_kapal']) ?></td>
            </tr>
            <tr>
                <td class="col-label" style="width: 320px;">Jumlah Crew</td>
                <td class="col-colon" style="width: 20px; text-align: center;">:</td>
                <td class="col-value"><?= esc($dataLayanan['jumlah_crew']) ?> Orang</td>
            </tr>
        </table>
    </div>

    <!-- II. Kelengkapan Dokumen -->
    <div class="section-title" style="margin-top: 25px;">
        II. Kelengkapan Dokumen Kesehatan Kapal
    </div>
    <div style="padding-left: 20px;">
        <table style="width: 100%;">
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Buku Kesehatan Kapal</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['dok_buku_kesehatan_kapal'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">SSCEC/SSCC</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['dok_sscec'] ? $checked : $unchecked ?>
                </td>
                <td class="date-col" style="padding-left: 10px; padding-bottom: 5px;">
                    Tanggal kadaluarsa: <?= $dataLayanan['dok_sscec_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($dataLayanan['dok_sscec_tgl_kadaluarsa'])) : '..............................' ?>
                </td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Sertifikat P3K</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['dok_p3k'] ? $checked : $unchecked ?>
                </td>
                <td class="date-col" style="padding-left: 10px; padding-bottom: 5px;">
                    Tanggal kadaluarsa: <?= $dataLayanan['dok_p3k_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($dataLayanan['dok_p3k_tgl_kadaluarsa'])) : '..............................' ?>
                </td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">PHQC dari Pelabuhan sebelumnya</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['dok_phqc'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>

    <!-- III. Permohonan Layanan -->
    <div class="section-title" style="margin-top: 25px;">
        III. Permohonan Layanan Kesehatan Kapal
    </div>
    <div style="padding-left: 20px;">
        <table style="width: 100%;">
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Pemeriksaan kesehatan sanitasi dan crew kapal</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['layanan_pemeriksaan_kesehatan'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Pemeriksaan dalam rangka penerbitan SSCEC/SSCC</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['layanan_penerbitan_sscec'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Pemeriksaan dalam rangka penerbitan sertifikat P3K</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['layanan_penerbitan_p3k'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;">Lainnya</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= $dataLayanan['layanan_lainnya'] ? $checked : $unchecked ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-left: 20px; font-style: italic; font-size: 10pt; padding-top: 5px;">
                    (sebutkan jenis layanan: <span class="line-input"><?= esc($dataLayanan['layanan_lainnya_sebutkan'] ?? '..........................................................') ?></span>)
                </td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px; padding-top: 10px;">Fasilitasi Transportasi Petugas Pemeriksa</td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px; padding-top: 10px;">
                    <?= ($dataLayanan['fasilitasi_transportasi'] === 'Ya') ? $checked : $unchecked ?>
                </td>
                <td style="padding-bottom: 5px; padding-top: 10px;">Ya</td>
            </tr>
            <tr>
                <td class="label-col" style="width: 320px; padding-bottom: 5px;"></td>
                <td class="check-col" style="width: 30px; text-align: center; padding-bottom: 5px;">
                    <?= ($dataLayanan['fasilitasi_transportasi'] === 'Tidak') ? $checked : $unchecked ?>
                </td>
                <td style="padding-bottom: 5px;">Tidak</td>
            </tr>
        </table>
    </div>
    
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <div>......................., ..........................</div>
            <div>Pemohon</div>
            <div class="signature-space"></div>
            <div>(...................................................)</div>
        </div>
    </div>

</body>

</html>