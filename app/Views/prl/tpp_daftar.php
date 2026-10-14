<table id="tabel_tpp" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Wilayah Kerja</th>
      <th>Nama TPP</th>
      <th>Penanggung Jawab</th>
      <th>Jumlah Karyawan</th>
      <th>Kapasitas Produksi</th>
      <th>Diubah Oleh</th>
      <th>Terakhir Diubah</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($tpp as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['wilker']; ?></td>
        <td><?= $data['nama_tpp']; ?></td>
        <td><?= $data['penanggung_jawab']; ?></td>
        <td><?= $data['jml_karyawan']; ?></td>
        <td><?= $data['kapasitas_produksi']; ?></td>
        <td><?= $data['nama_pegawai']; ?></td>
        <td><?= (new \DateTime($data['updated_at']))->format('j M Y, H:i'); ?></td>
        <td>
          <a href="/prl/tambah_tpp/<?= $data['id_prl_tpp']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah TPP" for="Ubah TPP"><i class="fas fa-edit"></i></a>
          <!-- <form action="/prl/hapus_tpp/<? //= $data['id_prl_tpp']; 
                                            ?>" method="post" class="d-inline formHapusData">
            <? //= csrf_field(); 
            ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus TPP"><i class="fas fa-trash-alt"></i></button>
          </form> -->
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_tpp").DataTable({
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "scrollX": true,
    "buttons": ["copy", "excel", "pdf", "colvis"],
  }).buttons().container().appendTo('#tabel_tpp_wrapper .col-md-6:eq(0)');
</script>