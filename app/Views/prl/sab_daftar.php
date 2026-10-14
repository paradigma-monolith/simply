<table id="tabel_sab" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th rowspan="2">No</th>
      <th rowspan="2">Wilayah Kerja</th>
      <th rowspan="2">Nama SAB</th>
      <th colspan="2">Penanggung Jawab</th>
      <th rowspan="2">Sumber Air</th>
      <th rowspan="2">Kapasitas Penampungan</th>
      <th rowspan="2">Diubah Oleh</th>
      <th rowspan="2">Terakhir Diubah</th>
      <th rowspan="2">Aksi</th>
    </tr>
    <tr>
      <th>Nama</th>
      <th>No. HP</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($sab as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['wilker']; ?></td>
        <td><?= $data['nama_sab']; ?></td>
        <td><?= $data['penanggung_jawab']; ?></td>
        <td><?= $data['penanggung_jawab_no_hp']; ?></td>
        <td><?= $data['sumber_air']; ?></td>
        <td><?= number_format($data['kapasitas_penampungan'], 0, ',', '.'); ?> L</td>
        <td><?= $data['nama_pegawai']; ?></td>
        <td><?= (new \DateTime($data['updated_at']))->format('j M Y, H:i'); ?></td>
        <td>
          <a href="/prl/tambah_sab/<?= $data['id_prl_sab']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah SAB" for="Ubah SAB"><i class="fas fa-edit"></i></a>
          <!-- <form action="/prl/hapus_sab/<? //= $data['id_prl_sab']; 
                                            ?>" method="post" class="d-inline formHapusData">
            <? //= csrf_field(); 
            ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus SAB"><i class="fas fa-trash-alt"></i></button>
          </form> -->
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_sab").DataTable({
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "scrollX": true,
    "buttons": ["copy", "excel", "pdf", "colvis"],
  }).buttons().container().appendTo('#tabel_sab_wrapper .col-md-6:eq(0)');
</script>