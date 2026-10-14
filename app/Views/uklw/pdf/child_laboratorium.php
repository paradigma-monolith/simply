<?php $no = 1;

$array = isset($laboratorium['laboratorium_kimia_klinik']) ? @unserialize($laboratorium['laboratorium_kimia_klinik']) : [];
$array = is_array($array) ? $array : [];
?>

<tr>
  <td></td>
  <td style="width: 3%;"><?= $no++; ?>.</td>
  <td colspan="2">Kimia Klinik</td>
  <td style="width: 1%;"></td>
  <td style="width: 7%;" class="text-center"></td>
  <td style="width: 47%;"></td>
</tr>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"></td>
    <td colspan="2"><?= $data['laboratorium_kimia_klinik']; ?></td>
    <td style="width: 1%;">:</td>
    <td style="width: 7%;" class="text-center dotted-underline"><?= $data['hasil_laboratorium_kimia_klinik']; ?></td>
    <td style="width: 47%;"><?= isset($data['satuan_laboratorium_kimia_klinik']) ? replaceStarWithDegree(convertToSuperscript($data['satuan_laboratorium_kimia_klinik'])) : ''; ?></td>
  </tr>
<?php endforeach;


$array = isset($laboratorium['laboratorium_hematologi']) ? @unserialize($laboratorium['laboratorium_hematologi']) : [];
$array = is_array($array) ? $array : [];
?>

<tr>
  <td></td>
  <td style="width: 3%;"><?= $no++; ?>.</td>
  <td colspan="2">Kimia Klinik</td>
  <td style="width: 1%;"></td>
  <td style="width: 7%;" class="text-center"></td>
  <td style="width: 47%;"></td>
</tr>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"></td>
    <td colspan="2"><?= $data['laboratorium_hematologi']; ?></td>
    <td style="width: 1%;">:</td>
    <td style="width: 7%;" class="text-center dotted-underline"><?= $data['hasil_laboratorium_hematologi']; ?></td>
    <td style="width: 47%;"><?= isset($data['satuan_laboratorium_hematologi']) ? replaceStarWithDegree(convertToSuperscript($data['satuan_laboratorium_hematologi'])) : ''; ?></td>
  </tr>
  <?php endforeach; ?>?>