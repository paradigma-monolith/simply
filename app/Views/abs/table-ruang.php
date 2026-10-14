<table id="tableWithoutButton2" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Ruang</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($ruang as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['ruang']; ?></td>
        <td>
          <form action="/abs/dlt_ruang/<?= $data['id_abs_ruang']; ?>" method="post" class="d-inline formHapusData">
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
    $("#tableWithoutButton2").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "searching": false,
      "info": false // Menonaktifkan info paging
    });
  });
</script>