<div class="row mb-3">
  <div class="col-md-4">
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">Filter Tahun</span>
      </div>
      <select id="filter_tahun" class="form-control">
        <option value="">Pilih Tahun</option>
        <?php
        $currentYear = date('Y');
        for($i = $currentYear; $i >= 2020; $i--) {
            $selected = (isset($selectedYear) && $selectedYear == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
        }
        ?>
      </select>
    </div>
  </div>
  <div class="col-md-2">
      <button id="btn_download_zip" class="btn btn-primary btn-block" <?php echo (isset($selectedYear) && $selectedYear) ? '' : 'disabled'; ?>>
          <i class="fas fa-download"></i> Download ZIP
      </button>
  </div>
  <div class="col-md-2">
      <button id="btn_export_excel" class="btn btn-success btn-block" <?php echo (isset($selectedYear) && $selectedYear) ? '' : 'disabled'; ?>>
          <i class="fas fa-file-excel"></i> Export Excel
      </button>
  </div>
</div>

<div style="overflow-x: auto;">
  <table id="tabel_dokumen" class="table table-bordered table-striped table-hover" style="width: 100%;">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th>Waktu Upload</th>
        <th>Pemilik</th>
        <th>Tahun</th>
        <th>Jenis Dokumen</th>
        <th>Dokumen</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
        <?php if (!empty($dokumenLhkpnSpt)) : ?>
            <?php
              $nomor = 1;
              foreach ($dokumenLhkpnSpt as $data) :
            ?>
          <tr>
            <td><?= $nomor++; ?></td>
            <td><?= $data['created_at']; ?></td>
            <td><?= $data['nama_pegawai']; ?></td>
            <td><?= date('Y', strtotime($data['tanggal_dokumen'])); ?></td>
            <td><?= isset($data['jenis_dokumen']) ? $data['jenis_dokumen'] : '-'; ?></td>
            <td><?= $data['dokumen']; ?></td>
            <td>
              <button class="btn m-1 btn-xs bg-gradient-danger btn-unduh-dokumen" data-toggle="tooltip" data-placement="bottom" title="Unduh Dokumen" for="Unduh Dokumen" data-arsip="<?= $data['dokumen']; ?>"><i class="far fa-file-pdf"></i></button>
              <a href="/lhkpn_spt/unggah_dokumen/<?= $data['id_lhkpn_spt']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Dokumen" for="Ubah Dokumen"><i class="fas fa-edit"></i></a>
              <form action="/lhkpn_spt/hapus_dokumen/<?= $data['id_lhkpn_spt']; ?>" method="post" class="d-inline formHapusData">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
              </form>
            </td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
              <td colspan="7" class="text-center">Tidak ada dokumen ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
  </table>
</div>
              
<script>
    var table = $("#tabel_dokumen").DataTable({
    paging: true, // Nonaktifkan paginasi
    info: false, // Nonaktifkan info tabel
    columnDefs: [{
      targets: [0, 1, 2, 3, 4, 5], // Kolom yang akan memiliki fitur ekspansi
      className: "details-control",
      orderable: true,
    }, ],
  });
  
  function dokumenLhkpnSpt() {
    var data = $(this).attr("data-arsip")

    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Baris sudah terbuka, tutup
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan konten detail di dalam baris
      row.child(`<iframe style="height:50vw; width:100%;" src="/dokumen_lhkpn_spt/${data}" title="description"></iframe>`).show();
    }
  }

  // Attach the event listener to the table's tbody using the new function
  $("#tabel_dokumen tbody").on("click", "button.btn-unduh-dokumen", dokumenLhkpnSpt);
</script>