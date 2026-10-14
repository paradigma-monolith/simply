<?php $no = 1;

$array = isset($skriningHIV['skrining_hiv']) ? @unserialize($skriningHIV['skrining_hiv']) : [];
$array = is_array($array) ? $array : [];
?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td colspan="5"><?= $data['skrining_hiv']; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_hiv'] == 'Tidak' ? '' : $checked; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_hiv'] == 'Tidak' ? $checked : ''; ?></td>
    <td></td>
  </tr>
<?php endforeach; ?>