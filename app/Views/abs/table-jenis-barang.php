<table id="tableWithoutButton3" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Kategori</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($jenis as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['jenis']; ?></td>
        <td>
          <form action="/abs/dlt_jenis/<?= $data['id_abs_jenis']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-sm fa-trash-alt"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(function() {
    $("#tableWithoutButton3").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "searching": false,
      "info": false // Menonaktifkan info paging
    });
  });
</script>