<table id="tabel_inspeksi_sab" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Wilayah Kerja</th>
      <th>Tanggal Inspeksi</th>
      <th>Nama SAB</th>
      <th>Petugas Pemeriksa</th>
      <th>Hasil Inspeksi</th>
      <th>Terakhir Diubah</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($inspeksi_sab as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['wilker']; ?></td>
        <td><?= date('d M Y', strtotime($data['tanggal_inspeksi'])); ?></td>
        <td><?= $data['nama_sab']; ?></td>
        <td><?= $data['nama_pegawai']; ?></td>
        <td>
          Skor: <?= $data['hasil']; ?><br>
          <?php
          if ($data['hasil'] < 25) {
          ?>
            <span class="badge badge-success">RISIKO RENDAH</span>
          <?php
          } else if ($data['hasil'] >= 25 && $data['hasil'] <= 50) {
          ?>
            <span class="badge badge-warning">RISIKO SEDANG</span>
          <?php
          } else if ($data['hasil'] >= 51 && $data['hasil'] <= 57) {
          ?>
            <span class="badge badge-danger">RISIKO TINGGI</span>
          <?php
          } else {
          ?>
            <span class="badge badge-dark">RISIKO TINGGI</span>
          <?php
          }
          ?>
        </td>
        <td><?= (new \DateTime($data['updated_at']))->format('j M Y, H:i'); ?></td>
        <td>
          <a href="/prl/prl_print_inspeksi_sab/<?= $data['id_prl_inspeksi_sab']; ?>" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Print Inspeksi SAB" for="Print Inspeksi SAB"><i class="fas fa-file-pdf"></i></a>
          <a href="/prl/tambah_inspeksi_sab/<?= $data['id_prl_inspeksi_sab']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Inspeksi SAB" for="Ubah Inspeksi SAB"><i class="fas fa-edit"></i></a>
          <form action="/prl/hapus_inspeksi_sab/<?= $data['id_prl_inspeksi_sab']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Inspeksi SAB"><i class="fas fa-trash-alt"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_inspeksi_sab").DataTable({
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "scrollX": true,
    "buttons": ["copy", "excel", "pdf", "colvis"],
  }).buttons().container().appendTo('#tabel_inspeksi_sab_wrapper .col-md-6:eq(0)');
</script>