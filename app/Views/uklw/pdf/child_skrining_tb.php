<?php $no = 1;

$array = isset($skriningTBC['skrining_tbc_gejala']) ? @unserialize($skriningTBC['skrining_tbc_gejala']) : [];
$array = is_array($array) ? $array : [];
?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td colspan="5"><?= $data['skrining_tbc_gejala']; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_tbc_gejala'] == 'Tidak' ? '' : $checked; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_tbc_gejala'] == 'Tidak' ? $checked : ''; ?></td>
    <td></td>
  </tr>
<?php endforeach; ?>