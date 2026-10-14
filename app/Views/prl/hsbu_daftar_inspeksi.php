<table id="tabel_inspeksi_hsbu" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Wilayah Kerja</th>
      <th>Tanggal Inspeksi</th>
      <th>Nama Gedung/Bangunan</th>
      <th>Petugas Pemeriksa</th>
      <th>Hasil Inspeksi</th>
      <th>Terakhir Diubah</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($inspeksi_hsbu as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['wilker']; ?></td>
        <td><?= date('d M Y', strtotime($data['tanggal_inspeksi'])); ?></td>
        <td><?= $data['nama_gedung']; ?></td>
        <td><?= $data['nama_pegawai']; ?></td>
        <td>
          Skor: <?= $data['total']; ?><br>
          <?php
          if ($data['total'] < 475) {
          ?>
            <span class="badge badge-danger">RISIKO TINGGI</span>
          <?php
          } else if ($data['total'] >= 475 && $data['total'] <= 712.5) {
          ?>
            <span class="badge badge-warning">RISIKO SEDANG</span>
          <?php
          } else {
          ?>
            <span class="badge badge-success">RISIKO RENDAH</span>
          <?php
          }
          ?>
        </td>
        <td><?= (new \DateTime($data['updated_at']))->format('j M Y, H:i'); ?></td>
        <td>
          <a href="/prl/prl_print_inspeksi_hsbu/<?= $data['id_prl_inspeksi_hsbu']; ?>" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Print Inspeksi TPP" for="Print Inspeksi HSBU"><i class="fas fa-file-pdf"></i></a>
          <a href="/prl/tambah_inspeksi_hsbu/<?= $data['id_prl_inspeksi_hsbu']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Inspeksi HSBU" for="Ubah Inspeksi HSBU"><i class="fas fa-edit"></i></a>
          <form action="/prl/hapus_inspeksi_hsbu/<?= $data['id_prl_inspeksi_hsbu']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Inspeksi HSBU"><i class="fas fa-trash-alt"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_inspeksi_hsbu").DataTable({
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "scrollX": true,
    "buttons": ["copy", "excel", "pdf", "colvis"],
  }).buttons().container().appendTo('#tabel_inspeksi_hsbu_wrapper .col-md-6:eq(0)');
</script>