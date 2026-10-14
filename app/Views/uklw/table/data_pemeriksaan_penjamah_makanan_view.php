<table id="tableWithoutExporting" class="table table-striped projects">
  <thead>
    <tr>
      <th style="width: 1%">#</th>
      <th style="width: 17%">Identitas</th>
      <th style="width: 5%;">JK</th>
      <th style="width: 11%">Status Pemeriksaan</th>
      <th style="width: 8%">Kota</th>
      <th style="width: 10%" class="text-center">Tanggal Pemeriksaan</th>
      <th style="width: 10%" class="text-center">Usia saat Pemeriksaan</th>
      <th style="width: 15%">Pemeriksaan</th>
      <th style="width: 15%"></th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($getData as $data) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td>
          <a> <?= $data['nama']; ?> </a>
          <br />
          <small> <?= $data['identitas']; ?> : <?= $data['nomor_identitas']; ?> </small>
        </td>
        <td>
          <?php if ($data['jk'] == "Perempuan") : ?>
            <i class="fas fa-2x fa-venus text-danger"></i>
          <?php elseif ($data['jk'] == "Laki-laki") : ?>
            <i class="fas fa-2x fa-mars text-info"></i>
          <?php else : ?>
            <i class="fas fa-2x fa-genderless"></i>
          <?php endif; ?>
          <br />
          <small><?= $data['jk']; ?></small>
        </td>
        <td>
          <?= $data['status_pemeriksaan']; ?>
        </td>
        <td><?= $data['kota']; ?></td>
        <td>
          Pemkes : <?= shortdate_indo($data['created_pemkes']); ?><br />
          Catatan : <?= $data['created_catatan'] ? shortdate_indo($data['created_catatan']) : '-'; ?>
        </td>
        <td class="project-state">
          <?= selisi_waktu($data['tgl_lahir']); ?>
          <br />
          <small><?= longdate_indo($data['tgl_lahir'], date("Y-m-d", strtotime($data['created_pemkes']))); ?></small>
        </td>
        <td>
          <?php if ($data['id_uklw_catatan_pemeriksaan']) : ?>
            <a class="btn btn-danger btn-sm d-block my-1" href="/uklw/catatan_pemkes/<?= $data['id_uklw_pemkes']; ?>">
              <i class="fas fa-notes-medical"></i>
              Catatan Pemeriksaan
            </a>
          <?php else : ?>
            <a class="btn btn-info btn-sm d-block my-1" href="/uklw/catatan_pemkes/<?= $data['id_uklw_pemkes']; ?>">
              <i class="fas fa-notes-medical"></i>
              Catatan Pemeriksaan
            </a>
          <?php endif; ?>
        </td>
        <td class="project-actions text-right">
          <form action="/uklw/export_excel/penjamah_makanan" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="pemkes" value="<?= $data['id_uklw_pemkes']; ?>">
            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export</button>
          </form>

          <a class="btn btn-warning btn-sm" href="/uklw/pemkes/edit/<?= $data['id_uklw_pemkes']; ?>">
            <i class="fas fa-pencil-alt"> </i>
            Edit
          </a>
          <form action="/uklw/delete/uklw_pemkes/<?= $data['id_uklw_pemkes']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i> Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<script>
  $(document).ready(function() {
    $("#tableWithoutExporting").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "colvis"]
    }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');
  });
</script>