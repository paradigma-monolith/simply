<?php foreach ($label as $data) : ?>
  <?php $array = unserialize($data['data_label']) ?>
  <table class="table table-bordered table-small">
    <tr>
      <td class="p-0 text-center align-middle">
        <button class="btn btn-info btn-small setLabelAbs" data-label="<?= htmlentities(json_encode($array)); ?>"><i class="fas fa-tag"></i> Pilih </button>
      </td>
      <?php foreach ($array as $getLabel) : ?>
        <td><?= $getLabel; ?></td>
      <?php endforeach; ?>
    </tr>
  </table>
<?php endforeach; ?>