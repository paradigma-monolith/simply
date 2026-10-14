<table class="table table-sm table-stripped table-bordered tableWithoutButton">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th class="text-center">
        Sisa
        <div class="row">
          <div class="col">Cuti Tahunan</div>
        </div>
      </th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($sisa_cuti as $data) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td>
          <?= $data['nip']; ?> <br />
          <?= $data['nama']; ?>
        </td>
        <td>
          <div class="row text-center">
            <div class="col">
              <?= $data['sisa_cuti_tahunan']; ?>
            </div>
          </div>
        </td>
        <td>
          <button class="btn btn-sm bg-warning button-show-modal-pengaturan-sisa-tahunan" data-id="<?= $data['id_data_pegawai']; ?>" data-nama="<?= $data['nama']; ?>" data-sisa-cuti-tahunan="<?= $data['sisa_cuti_tahunan']; ?>"><i class="fas fa-edit"></i></button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready(function() {
    $(".tableWithoutButton").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
    });
  });
</script>