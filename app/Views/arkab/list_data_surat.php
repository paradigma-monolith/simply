<div style="overflow-x: auto;">
  <table id="tabel_arkab_arsip" class="table table-bordered table-striped table-hover" style="width: 100%;">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th>Substansi</th>
        <th>Nomor Surat</th>
        <th>Jenis Surat</th>
        <th>Klasifikasi</th>
        <th>Pemilik</th>
        <th>Diubah pada</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php $nomor = 1; ?>
        <?php foreach ($getArsipSurat as $data) : ?>
          <td><?= $nomor++; ?></td>
          <td><?= $data['nama_substansi']; ?></td>
          <td><?= $data['nomor_surat']; ?></td>
          <td><?= $data['jenis_surat']; ?></td>
          <td><?= $data['kode']; ?> <?= $data['keterangan']; ?></td>
          <td><?= $data['nama']; ?></td>
          <td><?= $data['diubah_pada']; ?></td>
          <td>
            <button class="btn m-1 btn-xs bg-gradient-danger btn-unduh-arsip" data-toggle="tooltip" data-placement="bottom" title="Unduh Arsip" for="Unduh Arsip" data-arsip="<?= $data['dokumen']; ?>"><i class="far fa-file-pdf"></i></button>
            <a href="/arkab/tambah_surat/<?= $data['id_arkab_surat']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Arsip" for="Ubah Arsip"><i class="fas fa-edit"></i></a>
            <form action="/arkab/hapus_surat/<?= $data['id_arkab_surat']; ?>" method="post" class="d-inline formHapusData">
              <?= csrf_field(); ?>
              <input type="hidden" name="_method" value="DELETE">
              <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Surat"><i class="fas fa-trash-alt"></i></button>
            </form>
          </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  var table = $("#tabel_arkab_arsip").DataTable({
    paging: true, // Nonaktifkan paginasi
    info: false, // Nonaktifkan info tabel
    columnDefs: [{
      targets: [0, 1, 2, 3, 4, 5, 6], // Kolom yang akan memiliki fitur ekspansi
      className: "details-control",
      orderable: true,
    }, ],
  });

  function arkabArsipSurat() {
    var data = $(this).attr("data-arsip")

    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Baris sudah terbuka, tutup
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan konten detail di dalam baris
      row.child(`<iframe style="height:50vw; width:100%;" src="/arkab_arsip/${data}" title="description"></iframe>`).show();
    }
  }

  // Attach the event listener to the table's tbody using the new function
  $("#tabel_arkab_arsip tbody").on("click", "button.btn-unduh-arsip", arkabArsipSurat);
</script>