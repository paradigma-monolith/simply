<div class="card">
  <div class="card-body">
    <table id="tableDataABS" class="table table-sm table-stripped table-bordered table-hover">
      <thead>
        <tr>
          <th style="width: 0%;">No</th>
          <th>Data</th>
          <th style="width: 20%;">Wilayah Kerja</th>
          <th style="width: 10%;">Lantai</th>
          <th style="width: 10%;">Ruang</th>
          <th style="width: 10%;">Jenis</th>
          <th style="width: 10%;">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $nomor = 1;
        foreach ($getData as $data) : ?>
          <tr>
            <td><?= $nomor++; ?></td>
            <td>
              <?php
              $dataBarang = unserialize($data['data_barang']);
              foreach ($dataBarang as $label => $barang) : ?>
                <?= $label; ?> : <strong><?= $barang; ?></strong><br />
              <?php endforeach; ?>
            </td>
            <td class="<?= $data['unit_kerja'] ? '' : 'table-danger'; ?>">
              <?= $data['unit_kerja'] ? $data['unit_kerja'] : 'Tidak diketaui'; ?>
            </td>
            <td class="<?= $data['lantai'] ? '' : 'table-danger'; ?>">
              <?= $data['lantai'] ? $data['lantai'] : 'Tidak diketahui'; ?>
            </td>
            <td class="<?= $data['ruang'] ? '' : 'table-danger'; ?>">
              <?= $data['ruang'] ? $data['ruang'] : 'Tidak diketahui'; ?>
            </td>
            <td class="<?= $data['jenis'] ? '' : 'table-danger'; ?>">
              <?= $data['jenis'] ? $data['jenis'] : 'Tidak diketahui'; ?>
            </td>
            <td>
              <span class="<?= $data['status_tersedia'] == 'Tidak Tersedia' ? 'text-danger' : ''; ?>"> <?= $data['status_tersedia']; ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#tableDataABS").dataTable()
  });
</script>