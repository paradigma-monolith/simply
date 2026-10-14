<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>No</th>
      <th>NIP/Nama</th>
      <th>Status</th>
      <th>Jenis Cuti</th>
      <th>Status Pegawai</th>
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
        <td><span class="d-block"><?= $data['nip_pegawai']; ?>/</span><span class="d-block"><?= $data['nama_pegawai']; ?></span></td>
        <td>

          <small class="d-block">Kepegawaian:
            <?= $data['cuti_status_kepegawaian'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['cuti_status_kepegawaian'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['cuti_status_kepegawaian'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
            <?= $data['cuti_status_kepegawaian'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
          </small>
          <small class="d-block">Atasan:
            <?= $data['cuti_status_atasan'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
            <?= $data['cuti_status_atasan'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
            <?= $data['cuti_status_atasan'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
            <?= $data['cuti_status_atasan'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
          </small>
          <?php if($data['id_status_pegawai'] != 4): ?>
          <small class="d-block">Pejabat:
            <?= $data['cuti_status_pejabat'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
            <?= $data['cuti_status_pejabat'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
            <?= $data['cuti_status_pejabat'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
            <?= $data['cuti_status_pejabat'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
          </small>
          <?php endif; ?>
        </td>
        <td><?= $data['jenis_cuti']; ?></td>
        <td><?= $data['status_pegawai']; ?></td>
        <td><?= $data['time']; ?> <br/> <div class="pt-3">Diperbaharui oleh: <br/><small><?= name_capitalize($data['updated']); ?></small></div></td>
        <td>
          <?php
          if ($data['koordinator_status'] == "Terkirim") {
            if ($role == 'Kepegawaian') {
              if ($data['cuti_status_kepegawaian'] == 'Pending') {
                echo '<a href="/cuti/validation_detail_cuti/' . $data['id_cuti_data_cuti'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
              }
            }
            if ($role == 'Atasan') {
              if ($data['cuti_status_kepegawaian'] != 'Disetujui') {
                echo '<span class="badge badge-pill badge-secondary">Menunggu</span>';
              } else {
                echo '<a href="/cuti/validation_detail_cuti/' . $data['id_cuti_data_cuti'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
              }
            }
            if ($role == 'Pejabat') {
              if ($data['cuti_status_atasan'] != 'Disetujui') {
                echo '<span class="badge badge-pill badge-secondary">Menunggu</span>';
              } else {
                echo '<a href="/cuti/validation_detail_cuti/' . $data['id_cuti_data_cuti'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
              }
            }
          } else {
            if ($role == 'Pejabat' || $role == 'Atasan' || $role == 'Kepegawaian') {
              echo '<span class="badge badge-pill badge-secondary">Koordinator Tempat</span>';
            } else {
              echo '<a href="/cuti/validation_detail_cuti/koordinator/' . $data['id_cuti_data_cuti'] . '" class="btn btn-lg bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>';
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
      <th>Jenis Cuti</th>
      <th>Status Pegawai</th>
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