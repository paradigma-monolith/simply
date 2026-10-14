<table id="tabel_inspeksi_tpp" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Wilayah Kerja</th>
      <th>Tanggal Inspeksi</th>
      <th>Nama TPP</th>
      <th>Petugas Pemeriksa</th>
      <th>Hasil Inspeksi</th>
      <th>Terakhir Diubah</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($inspeksi_tpp as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['wilker']; ?></td>
        <td><?= date('d M Y', strtotime($data['tanggal_inspeksi'])); ?></td>
        <td><?= $data['nama_tpp']; ?></td>
        <td><?= $data['nama_pegawai']; ?></td>
        <td>
          Skor: <?= $data['hasil']; ?><br>
          <?php
          if ($data['hasil'] < 35) {
          ?>
            <span class="badge badge-success">RISIKO RENDAH</span>
          <?php
          } else if ($data['hasil'] >= 35 && $data['hasil'] <= 50) {
          ?>
            <span class="badge badge-warning">RISIKO SEDANG</span>
          <?php
          } else if ($data['hasil'] >= 55) {
          ?>
            <span class="badge badge-danger">RISIKO TINGGI</span>
          <?php
          }
          ?>
        </td>
        <td><?= (new \DateTime($data['updated_at']))->format('j M Y, H:i'); ?></td>
        <td>
          <a href="/prl/prl_print_inspeksi_tpp/<?= $data['id_prl_inspeksi_tpp']; ?>" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Print Inspeksi TPP" for="Print Inspeksi TPP"><i class="fas fa-file-pdf"></i></a>
          <a href="/prl/tambah_inspeksi_tpp/<?= $data['id_prl_inspeksi_tpp']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Inspeksi TPP" for="Ubah Inspeksi TPP"><i class="fas fa-edit"></i></a>
          <form action="/prl/hapus_inspeksi_tpp/<?= $data['id_prl_inspeksi_tpp']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Inspeksi TPP"><i class="fas fa-trash-alt"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_inspeksi_tpp").DataTable({
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "scrollX": true,
    "buttons": ["copy", "excel", "pdf", "colvis"],
  }).buttons().container().appendTo('#tabel_inspeksi_tpp_wrapper .col-md-6:eq(0)');
</script>