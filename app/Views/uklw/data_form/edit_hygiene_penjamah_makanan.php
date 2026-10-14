<table class="table table-info table-hover table-striped table-bordered table-sm align-middle m-0">
  <tr class="bg-primary">
    <td class="pl-4" style="width: 40%;">PELAYANAN KESEHATAN KERJA</td>
    <td style="width: 15%;"></td>
    <td style="width: 15%;"></td>
    <td></td>
  </tr>
  <tr class="bg-danger">
    <td class="pl-4" style="width: 40%;">PERSONAL HYGIENE</td>
    <td class="text-center">Ya</td>
    <td class="text-center">Tidak</td>
    <td class="text-center">Keterangan</td>
  </tr>

  <?php
  $array = unserialize($getData['hygiene']);
  foreach ($array as $key => $data) : ?>
    <tr>
      <td class="p-0 align-middle">
        <input type="text" name="hygiene[]" value="<?= $data['hygiene']; ?>" name="hygiene[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
      </td>
      <td class="p-0">
        <input type="radio" value="Ya" <?= $data['hasil_hygiene'] == 'Ya' ? 'checked' : ''; ?> name="hasil_hygiene<?= $key; ?>" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
      </td>
      <td>
        <input type="radio" value="Tidak" <?= $data['hasil_hygiene'] == 'Tidak' ? 'checked' : ''; ?> name="hasil_hygiene<?= $key; ?>" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
      </td>
      <td class="p-0">
        <input type="text" value="<?= $data['ket_hygiene']; ?>" name="ket_hygiene[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="">
      </td>
    </tr>
  <?php endforeach; ?>
  <!-- ================================================================================================================================ -->
  <tr class="bg-danger">
    <td class="pl-4">PEMERIKSAAN FISIK PENJAMAH</td>
    <td class="text-center" colspan="3">Keterangan</td>
  </tr>

  <?php
  $array = unserialize($getData['pemeriksaan_fisik_penjamah']);
  foreach ($array as $data) : ?>
    <tr>
      <td class="p-0 align-middle">
        <input type="text" name="pemeriksaan_fisik_penjamah[]" value="<?= $data['pemeriksaan_fisik_penjamah']; ?>" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
      </td>
      <td class="p-0" colspan="3">
        <input type="text" value="<?= $data['ket_pemeriksaan_fisik_penjamah']; ?>" name="ket_pemeriksaan_fisik_penjamah[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="">
      </td>
    </tr>
  <?php endforeach; ?>
</table>