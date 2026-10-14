<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekam Absen Dinas Luar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        h2, h4 {
            text-align: center;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
        img {
            max-width: 100px;
            max-height: 80px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <h2>Rekam Absen Dinas Luar</h2>
    <h4>Periode: <?= date('d M Y', strtotime($tglAwal)) ?> s/d <?= date('d M Y', strtotime($tglAkhir)) ?></h4>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">NIP</th>
                <th style="width: 12%;">Nama</th>
                <th style="width: 6%;">Status<br>Masuk</th>
                <th style="width: 15%;">Alamat Masuk</th>
                <th style="width: 8%;">Jam Masuk</th>
                <th style="width: 8%;">TTD Masuk</th>
                <th style="width: 6%;">Status<br>Pulang</th>
                <th style="width: 15%;">Alamat Pulang</th>
                <th style="width: 8%;">Jam Pulang</th>
                <th style="width: 8%;">TTD Pulang</th>
                <th style="width: 8%;">Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($absen)): ?>
            <tr>
                <td colspan="12">Tidak ada data absensi dinas luar.</td>
            </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($absen as $data): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['nip'] ?></td>
                    <td class="text-left"><?= $data['nama'] ?></td>
                    <td><?= $data['status_masuk'] ?></td>
                    <td class="text-left"><?= $data['alamat_masuk'] ?></td>
                    <td><?= $data['jam_masuk'] ?></td>
                    <td>
                        <?php if (!empty($data['ttd_masuk'])): ?>
                            <?php 
                                $ttd = $data['ttd_masuk'];
                                if (strpos($ttd, 'base64,') === false && !empty($ttd)) {
                                    $ttd = 'data:image/png;base64,' . $ttd;
                                }
                            ?>
                            <img src="<?= $ttd ?>" alt="TTD">
                        <?php endif; ?>
                    </td>
                    <td><?= $data['status_pulang'] ?></td>
                    <td class="text-left"><?= $data['alamat_pulang'] ?></td>
                    <td><?= $data['jam_pulang'] ?></td>
                    <td>
                        <?php if (!empty($data['ttd_pulang'])): ?>
                            <?php 
                                $ttdPulang = $data['ttd_pulang'];
                                if (strpos($ttdPulang, 'base64,') === false && !empty($ttdPulang)) {
                                    $ttdPulang = 'data:image/png;base64,' . $ttdPulang;
                                }
                            ?>
                            <img src="<?= $ttdPulang ?>" alt="TTD Pulang">
                        <?php endif; ?>
                    </td>
                    <td><?= strip_tags(selisi_waktu($data['jam_masuk'], $data['jam_pulang'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>