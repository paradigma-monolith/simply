<table class="table table-info table-hover table-striped table-bordered table-sm align-middle m-0">
  <tr class="bg-primary">
    <td class="pl-4" style="width: 70%;">PELAYANAN KESEHATAN KERJA</td>
    <td style="width: 15%;"></td>
    <td style="width: 15%;"></td>
  </tr>
  <tr class="bg-danger">
    <td class="pl-4" style="width: 70%;">WAWANCARA</td>
    <td class="text-center">Ya</td>
    <td class="text-center">Tidak</td>
  </tr>

  <?php
  $array = unserialize($getData['wawancara']);
  foreach ($array as $key => $data) : ?>
    <tr>
      <td class="p-0 align-middle">
        <input type="text" value="<?= $data['wawancara']; ?>" name="wawancara[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
      </td>
      <td class="p-0">
        <input required type="radio" value="Ya" <?= $data['hasil_wawancara'] == 'Ya' ? 'checked' : ''; ?> name="hasil_wawancara<?= $key; ?>" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
      </td>
      <td>
        <input required type="radio" value="Tidak" <?= $data['hasil_wawancara'] == 'Tidak' ? 'checked' : ''; ?> name="hasil_wawancara<?= $key; ?>" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
      </td>
    </tr>
  <?php endforeach; ?>
</table>