<?php $no = 1;
$data_umum = [
  'No. KTP',
  'Nama Lengkap',
  'Tanggal Lahir',
  'Jenis Kelamin',
  'Pekerjaan (sebutkan detail)',
  'Pendidikan',
  'Alamat (tempat tinggal saat ini)',
  'Status Pernikahan',
];
?>
<tr>
  <td></td>
  <td style="width: 3%;"><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['nomor_identitas']) ? $pemkes['nomor_identitas'] : (isset($pasien['nomor_identitas']) ? $pasien['nomor_identitas'] : '-'); ?>
    <?php if (isset($pemkes['identitas']) && $pemkes['identitas'] != 'NIK'): ?>
      <sup>(<?= $pemkes['identitas']; ?>)</sup>
    <?php elseif (isset($pasien['identitas']) && $pasien['identitas'] != 'NIK'): ?>
      <sup>(<?= $pasien['identitas']; ?>)</sup>
    <?php endif; ?>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['nama']) ? name_capitalize($pemkes['nama']) : (isset($pasien['nama']) ? name_capitalize($pasien['nama']) : '-'); ?>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['tgl_lahir']) ? mediumdate_indo($pemkes['tgl_lahir']) : (isset($pasien['tgl_lahir']) ? mediumdate_indo($pasien['tgl_lahir']) : '-'); ?>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['jk']) ? $pemkes['jk'] : (isset($pasien['jk']) ? $pasien['jk'] : '-'); ?>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['pekerjaan']) ? $pemkes['pekerjaan'] : (isset($pasien['pekerjaan']) ? $pasien['pekerjaan'] : '-'); ?>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['pendidikan']) ? $pemkes['pendidikan'] : (isset($pasien['pendidikan']) ? $pasien['pendidikan'] : '-'); ?>
  </td>
</tr>
<tr>
  <td></td>
  <td style="vertical-align: top;"><?= $no++; ?>.</td>
  <td style="vertical-align: top;"><label><?= $data_umum[$no - 2]; ?></label></td>
  <td style="vertical-align: top;">:</td>
  <td class="">
    <p class="dotted-underline" style="padding: 0; margin:0;">
      <span class="dotted-underline">
        <?= isset($pemkes['alamat']) ? $pemkes['alamat'] : (isset($pasien['alamat']) ? $pasien['alamat'] : '-'); ?>
      </span>
    </p>
  </td>
</tr>
<tr>
  <td></td>
  <td><?= $no++; ?>.</td>
  <td><label><?= $data_umum[$no - 2]; ?></label></td>
  <td>:</td>
  <td class="dotted-underline">
    <?= isset($pemkes['status']) ? name_capitalize($pemkes['status']) : (isset($pasien['status']) ? name_capitalize($pasien['status']) : '-'); ?>
  </td>
</tr>