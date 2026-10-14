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
<?php for ($i = 0; $i < 10; $i++): ?>
  <tr>
    <td></td>
    <td style="width: 3%;"><?= $no++; ?>.</td>
    <td></td>
    <td class="bordered text-center strong"></td>
    <td class="bordered text-center strong"></td>
    <td></td>
  </tr>
<?php endfor; ?>