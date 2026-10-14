<?php $no = 1;

$data_umum = [
  'Tekanan Darah',
  'Suhu',
  'Tinggi Badan',
  'Berat Badan',
  'Lingkar Perut',
  'Gula Darah',
  'Asam Urat',
  'Kolesterol',
  'Rapid HIV',
  'Personal Hygiene',
  'Pemeriksaan Fisik',
];
$data_satuan = [
  'mmHg',
  'x/mnt',
  'Cm',
  'Kg',
  'Cm',
  'mg/dl',
  'mg/dl',
  'mg/dl',
];
$hygiene = 0;
$data_umum_hygiene = [
  'Kulit Kepala dan Rambut ( kutu, ketombe )',
  'Mulut ( bau mulut, sariawan )',
  'Gigi ( Karang )',
  'Kuku tangan ( kotoran, jamur, panjang )',
  'Kulit ( Panu, Kudis, Kadas, Kutu air )',
];
$pemeriksaan_fisik = 0;
$data_umum_pemeriksaan_fisik = [
  'Kelenjar getah bening',
  'Dada (retraksi, suara nafas)',
  'Perut (pembesaran organ, bising usus)',
  'Genital (keputihan, kulit kelamin, abnormal discharge)',
  'Ekstremitas (luka)',
];

$array = isset($pemkes['pemeriksaan_fisik']) ? @unserialize($pemkes['pemeriksaan_fisik']) : [];
$array = is_array($array) ? $array : [];
?>

<?php foreach ($array as $data): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td colspan="2"><?= $data['pemeriksaan_fisik']; ?></td>
    <td style="width: 1%;">:</td>
    <td style="width: 7%;" class="text-center dotted-underline"><?= $data['hasil_pemeriksaan_fisik']; ?></td>
    <td style="width: 47%;"><?= isset($data['satuan_pemeriksaan_fisik']) ? replaceStarWithDegree(convertToSuperscript($data['satuan_pemeriksaan_fisik'])) : ''; ?></td>
  </tr>
<?php endforeach; ?>