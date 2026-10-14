<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>No</th>
      <th>NIP/Nama</th>
      <th>Status</th>
      <th>Jenis Permohonan</th>
      <th>Tanggal Permohonan</th>
      <th>Updated By</th>
      <th>Last Update</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($dataCuti as $data) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><span class="d-inline-block"><?= $data['nip_pegawai']; ?></span>/<span class="d-inline-block"><?= $data['nama_pegawai']; ?></span></td>
        <td>
          <span class="d-block">Kepegawaian:
            <?= $data['status_kepegawaian'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['status_kepegawaian'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['status_kepegawaian'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['status_kepegawaian'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['status_kepegawaian'] . '</span>' : ''; ?>
          </span>
          <span class="d-block">Atasan:
            <?php if ($data['status_kepegawaian'] == 'Ditolak') : ?>
              -
            <?php else : ?>

              <?= $data['status_atasan'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['status_atasan'] . '</span>' : ''; ?>
              <?= $data['status_atasan'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['status_atasan'] . '</span>' : ''; ?>
              <?= $data['status_atasan'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['status_atasan'] . '</span>' : ''; ?>
              <?= $data['status_atasan'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['status_atasan'] . '</span>' : ''; ?>
            <?php endif; ?>
          </span>
        </td>
        <td><?= $data['jenis_permohonan']; ?></td>
        <td><span class="d-inline-block"><?= $data['tanggal']; ?></span></td>
        <td><?= $data['updated']; ?></td>
        <td><?= $data['time']; ?></td>
        <td>
          <?php
          if ($role == 'Kepegawaian') {
            if ($data['status_kepegawaian'] == 'Pending') {
              echo '<a href="/cuti/validation_detail_permohonan/' . $data['id_cuti_data_permohonan'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
            }
          }
          if ($role == 'Atasan') {
            if ($data['status_kepegawaian'] != 'Disetujui') {
              echo '<span class="badge badge-pill badge-secondary">Menunggua</span>';
            } else {
              echo '<a href="/cuti/validation_detail_permohonan/' . $data['id_cuti_data_permohonan'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
            }
          }
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th>No</th>
      <th>NIP/Nama</th>
      <th>Status</th>
      <th>Jenis Permohonan</th>
      <th>Tanggal Permohonan</th>
      <th>Updated By</th>
      <th>Last Update</th>
      <th>Aksi</th>
    </tr>
  </tfoot>
</table>

<script>
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "colvis"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
</script>