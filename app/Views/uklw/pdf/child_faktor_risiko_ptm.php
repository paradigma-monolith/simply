<?php $no = 1;

$array = isset($faktorRisikoPTM['skrining_penyakit_tidak_menular']) ? @unserialize($faktorRisikoPTM['skrining_penyakit_tidak_menular']) : [];
$array = is_array($array) ? $array : [];
?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td colspan="5"><?= $data['skrining_penyakit_tidak_menular']; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_penyakit_tidak_menular'] == 'Tidak' ? '' : $checked; ?></td>
    <td class="bordered text-center strong"><?= $data['hasil_skrining_penyakit_tidak_menular'] == 'Tidak' ? $checked : ''; ?></td>
    <td></td>
  </tr>
<?php endforeach; ?>