<?php $no = 1;

$array = [];
if (isset($pemkes['riwayat_penyakit'])) {
    $unserializedData = @unserialize($pemkes['riwayat_penyakit']);
    if ($unserializedData !== false || $pemkes['riwayat_penyakit'] === 'b:0;') {
        $array = $unserializedData;
    }
}
$array = is_array($array) ? $array : [];

?>
<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td><label><?= $data['riwayat_penyakit'] ?? ''; ?></label></td>
    <td>:</td>
    <td class="dotted-underline"><?= $data['hasil_riwayat_penyakit'] ?? ''; ?></td>
  </tr>
<?php endforeach; ?>