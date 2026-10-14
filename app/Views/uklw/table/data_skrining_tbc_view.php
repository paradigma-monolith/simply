<table id="tableWithoutExporting" class="table table-striped projects">
  <thead>
    <tr>
      <th style="width: 1%">#</th>
      <th style="width: 20%">Identitas</th>
      <th style="width: 7%;">JK</th>
      <th style="width: 10%" class="text-center">Tanggal Skrining TBC</th>
      <th style="width: 10%" class="text-center">Usia saat Pemeriksaan</th>
      <th style="width: 15%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($getData as $data) : ?>
      <tr>
        <td>#</td>
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
          <?= shortdate_indo($data['created_pemkes']); ?>
        </td>
        <td class="project-state">
          <?= selisi_waktu($data['tgl_lahir']); ?>
          <br />
          <small><?= longdate_indo($data['tgl_lahir'], date("Y-m-d", strtotime($data['created_pemkes']))); ?></small>
        </td>
        <!--<td>-->
        <!--<a class="btn btn-primary btn-sm d-block my-1" href="#">-->
        <!--  <i class="fas fa-laptop-medical"></i>-->
        <!--  Laboratorium-->
        <!--</a>-->
        <!--</td>-->
        <td class="project-actions text-right">
          <a class="btn btn-warning btn-sm" href="/uklw/skrining_tbc/edit/<?= $data['id_uklw_skrining_tbc']; ?>">
            <i class="fas fa-pencil-alt"> </i>
            Edit
          </a>
          <form action="/uklw/delete/uklw_skrining_tbc/<?= $data['id_uklw_skrining_tbc']; ?>" method="post" class="d-inline formHapusData">
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