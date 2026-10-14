<?php $no = 1;

$array = unserialize($pemkes['wawancara']);
?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td colspan="5"><?= $data['wawancara']; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_wawancara'] == 'Tidak' ? '' : $checked; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_wawancara'] == 'Tidak' ? $checked : ''; ?></td>
    <td></td>
  </tr>
<?php endforeach; ?>

<?php
$array = unserialize($pemkes['hygiene']);
?>
<tr>
  <td></td>
  <td style="width: 3%;"><?= $no++; ?>.</td>
  <td class="" colspan="8">Hygiene</td>
</tr>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"></td>
    <td style="white-space: nowrap; font-size: 7pt; overflow: hidden; padding-right: 20px;"><?= $data['hygiene']; ?></td>
    <td colspan="7" class="dotted-underline">: <?= $data['hasil_hygiene']; ?> <?= $data['ket_hygiene'] != "" ? "(" . $data['ket_hygiene'] . ")" : ''; ?></td>
  </tr>
<?php endforeach; ?>
<tr>
  <td></td>
  <td style="width: 3%;"><?= $no++; ?>.</td>
  <td class="" colspan="8">Pemeriksaan Fisik Penjamah</td>
</tr>
<?php
$array = unserialize($pemkes['pemeriksaan_fisik_penjamah']);
?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"></td>
    <td style="white-space: nowrap; font-size: 7pt; overflow: hidden; padding-right: 20px;"><?= $data['pemeriksaan_fisik_penjamah']; ?></td>
    <td colspan="7" class="dotted-underline">: <?= $data['ket_pemeriksaan_fisik_penjamah']; ?></td>
  </tr>
<?php endforeach; ?>