<?php $no = 1;

$assesment = array();
$assesmentOld = "";
if (is_string($pemkes['assesment'])) {
  $cekassesment = @unserialize($pemkes['assesment']);

  if ($cekassesment !== false || $pemkes['assesment'] === 'b:0;') {
    $assesment = unserialize($pemkes['assesment']);
  } else {
    $cleanedData = trim($pemkes['assesment'], '"');
    $assesmentOld = explode(',', $cleanedData);
  }
}

$planning = array();
$planningOld = "";
if (is_string($pemkes['planning'])) {
  $cekplanning = @unserialize($pemkes['planning']);

  if ($cekplanning !== false || $pemkes['planning'] === 'b:0;') {
    $planning = unserialize($pemkes['planning']);
  } else {
    $cleanedData = trim($pemkes['planning'], '"');
    $planningOld = explode(',', $cleanedData);
  }
}
?>

<?php if ($planning == array() && $assesment == array()): ?>
  <tr>
    <td class="strong expand-line" colspan="4">DIAGNOSA MEDIS</td>
    <td>:</td>
    <td colspan="2" class="dotted-underline">
      <?= str_replace(',', ', ', $pemkes['assesment']); ?>
    </td>
  </tr>
<?php else: ?>
  <tr>
    <td class="strong expand-line" style="vertical-align: top; line-height: 20px;" colspan="4">DIAGNOSA MEDIS</td>
    <td style="vertical-align: top; line-height: 20px;">:</td>
    <td colspan="2" style="vertical-align: top;">
      <ol style="padding-left: 15px;">
        <?php
        $cekResepObat = "";
        if (isset($getICDS) && is_array($getICDS)) {
            foreach ($getICDS as $data): ?>
              <?php if (isset($data['code']) && $data['code'] != ""): $cekResepObat = $data['code']; ?>
                <li style="padding-top: 6px;"><?= $data['code']; ?> - <?= $data['name_id']; ?></li>
              <?php endif; ?>
            <?php endforeach;
        } ?>
      </ol>
      <?php if ($cekResepObat == ""): ?>
        <p style="padding-top: 6px;">-</p>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="strong expand-line" colspan="4">KESIMPULAN</td>
    <td>:</td>
    <td colspan="2" class="uppercase"><?= isset($assesment['assesment_kesimpulan']) ? $assesment['assesment_kesimpulan'] : '-'; ?></td>
  </tr>
  <tr>
    <td class="strong expand-line" colspan="4">RUJUKAN</td>
    <td>:</td>
    <td colspan="2" class="uppercase">
      <?php if (isset($planning['planning_rujuk']) && $planning['planning_rujuk'] == 'Ya'): ?>
        YA (<?= isset($planning['planning_tempat_rujuk']) && $planning['planning_tempat_rujuk'] != "" ? $planning['planning_tempat_rujuk'] : " - "; ?>)
      <?php else: ?>
        TIDAK
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="strong expand-line" style="vertical-align: top; line-height: 20px;" colspan="4">RESEP OBAT</td>
    <td style="vertical-align: top; line-height: 20px;">:</td>
    <td colspan="2" style="vertical-align: top;">
      <ol style="padding-left: 15px;">
        <?php
        $cekResepObat = "";
        if (isset($planning['planning_obat']) && is_array($planning['planning_obat'])) {
            foreach ($planning['planning_obat'] as $data): ?>
              <?php if (isset($data['planning_obat']) && $data['planning_obat'] != ""): $cekResepObat = $data['planning_obat']; ?>
                <li style="padding-top: 6px;"><?= $data['planning_obat']; ?> (<?= $data['planning_obat_jumlah'] ?? ''; ?>) <?= $data['planning_obat_sehari'] ?? ''; ?> <?= isset($data['planning_obat_sehari']) && ctype_digit(substr($data['planning_obat_sehari'], -1)) ? "hari;" : "sehari;" ;?> <?= $data['planning_obat_waktu'] ?? ''; ?></li>
              <?php endif; ?>
            <?php endforeach;
        } ?>
      </ol>
      <?php if ($cekResepObat == ""): ?>
        <p style="padding-top: 6px;">-</p>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td colspan="6"></td>
    <td class="pt-20 pl-150">Dokter Pemeriksa</td>
  </tr>
  <tr>
    <td colspan="6"></td>
    <td class="pt-20 pl-150" style="padding-top: 60px;">
      <strong><?= name_capitalize($pemkes['nama_dokter']); ?></strong> <br />
      NIP. <?= $pemkes['nip_dokter']; ?>
    </td>
  </tr>
<?php endif; ?>