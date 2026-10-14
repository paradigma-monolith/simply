<table id="tableWithoutExporting" class="table table-sm">
  <thead>
    <tr>
      <th>Nama Pegawai</th>
      <th>NIP</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($dataPegawai as $data) : ?>
      <tr>
        <td><?= $data['nama']; ?></td>
        <td><?= $data['nip']; ?></td>
        <td>
          <form action="/uklw/directToFormPasien" method="post">
            <?php
            $thn = substr($data['nip'], 0, 4);
            $bln = substr($data['nip'], 4, 2);
            $tgl = substr($data['nip'], 6, 2);

            $jk = substr($data['nip'], 14, 1);
            if ($jk == '1') {
              $jk = "Laki-laki";
            } else if ($jk == '2') {
              $jk = "Perempuan";
            } else {
              $jk = "";
            }
            ?>
            <div class="d-none">
              <input type="text" name="nama" value="<?= $data['nama']; ?>">
              <input type="text" name="tgl_lahir" value="<?= $thn . '/' . $bln . '/' . $tgl; ?>">
              <input type="text" name="jk" value="<?= $jk; ?>">
              <input type="text" name="no_hp" value="<?= $data['no_telp']; ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-location-arrow"></i> Direct</button>
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
    }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');
  });
</script>