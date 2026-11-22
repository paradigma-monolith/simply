<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekam Absen Dinas Luar</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
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
            table-layout: fixed;
        }
        thead {
            display: table-header-group;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
        img {
            max-width: 95px;
            max-height: 75px;
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
                <?php
                    $ttdStorage = (isset($mediaStorage) && $mediaStorage instanceof \App\Libraries\AbsenMediaStorage)
                        ? $mediaStorage
                        : new \App\Libraries\AbsenMediaStorage();
                    $preferFilePath = !empty($useFilePathForPdf);
                    $resolveTtdSrc = static function (?string $rawVal) use ($ttdStorage, $preferFilePath): ?string {
                        if ($preferFilePath) {
                            $thumb = $ttdStorage->getOrCreateExportThumbnail($rawVal, 220, 120);
                            if ($thumb !== null) {
                                return $thumb;
                            }
                        }
                        return $ttdStorage->resolveToDataUri($rawVal);
                    };
                    $no = 1;
                ?>
                <?php foreach ($absen as $data): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['nip'] ?></td>
                    <td class="text-left"><?= $data['nama'] ?></td>
                    <td><?= $data['status_masuk'] ?></td>
                    <td class="text-left"><?= $data['alamat_masuk'] ?></td>
                    <td><?= $data['jam_masuk'] ?></td>
                    <td>
                        <?php $ttd = $resolveTtdSrc($data['ttd_masuk'] ?? null); ?>
                        <?php if ($ttd !== null): ?>
                            <img src="<?= $ttd ?>" width="90" alt="TTD">
                        <?php endif; ?>
                    </td>
                    <td><?= $data['status_pulang'] ?></td>
                    <td class="text-left"><?= $data['alamat_pulang'] ?></td>
                    <td><?= $data['jam_pulang'] ?></td>
                    <td>
                        <?php $ttdPulang = $resolveTtdSrc($data['ttd_pulang'] ?? null); ?>
                        <?php if ($ttdPulang !== null): ?>
                            <img src="<?= $ttdPulang ?>" width="90" alt="TTD Pulang">
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