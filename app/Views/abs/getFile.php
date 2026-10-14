<table class="table table-bordered table-sm table-secondary">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama File</th>
      <th>Kertas</th>
      <th>Jumlah</th>
      <th>Diproses</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($getFile as $data) : ?>
      <tr>
        <td><?= $nomor++ ?></td>
        <td><i class="fas fa-file-pdf text-danger"></i><?= $data['file']; ?></td>
        <td><?= $data['kertas']; ?></td>
        <td><?= $data['lembar']; ?> Halaman</td>
        <td><?= $data['nama']; ?><br><small><?= $data['created_at']; ?></small></td>
        <td>
          <a href="/abs/download/<?= $data['file']; ?>" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-file-download"></i></a>
          <form action="/abs/dlt_file_abs/<?= $data['id_abs_export_label']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>