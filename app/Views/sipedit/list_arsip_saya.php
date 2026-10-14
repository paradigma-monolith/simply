<div style="overflow-x: auto;">
  <table id="tabel_arsip_saya" class="table table-bordered table-striped table-hover" style="width: 100%;">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th>Waktu Upload</th>
        <th>Pemilik</th>
        <th>Nama Pelatihan</th>
        <th>Lembaga Pelatihan</th>
        <th>Kategori</th>
        <th style="width: 150px;">Sub Kategori</th>
        <th>Tanggal Pelatihan</th>
        <th>JPL</th>
        <th>Unduh | Ubah | Hapus</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $nomor = 1;
      $kategori = 0;
      $pemilik = 0;
      foreach ($dataArsipSaya as $data) :
      ?>
        <tr class="<?php
                    if ($data['pemilik_arsip'] == '') {
                      echo 'table-danger';
                      $pemilik++;
                    }
                    if ($data['kategori'] == '') {
                      echo 'table-danger';
                      $kategori++;
                    }
                    ?>">
          <td><?= $nomor++; ?></td>
          <td><?= $data['created_at']; ?></td>
          <td><?= $data['pemilik_arsip']; ?></td>
          <td><?= $data['arsip_kode']; ?></td>
          <td data-toggle="tooltip" data-placement="bottom" title="<?= $data['arsip_nama']; ?>"><?= strlen($data['arsip_nama']) > 29 ? substr($data['arsip_nama'], 0, 30) . '...' : $data['arsip_nama']; ?></td>
          <td><?= $data['kategori']; ?></td>
          <td>
            <?php if (session()->get('role') == "Admin"): ?>
              <select class="form-control sub-kategori-sipedit form-control-sm subkategori" data-id="<?= $data['id_sipedit_arsip']; ?>">
                <?php
                $optionSub = true;
                ?>
                <?php foreach ($subKategori as $sub) : ?>
                  <?php if ($sub['kategori_nama'] == $data['kategori']): ?>
                    <?php if ($optionSub):
                      $optionSub = false; ?>
                      <option value="">-- Pilih Sub Kategori --</option>
                    <?php endif; ?>
                    <option value="<?= $sub['id_sipedit_sub_kategori'] ?>" <?= $sub['id_sipedit_sub_kategori'] == $data['id_sipedit_sub_kategori'] ? 'selected' : '' ?>><?= $sub['sub_kategori']; ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
          </td>
          <td><?= "Mulai: " . date('d M Y', strtotime($data['arsip_tanggal'])) . "<br>Selesai: " . date('d M Y', strtotime($data['arsip_selesai'])); ?></td>
          <td><?= $data['arsip_jpl']; ?></td>
          <td>
            <button class="btn m-1 btn-xs bg-gradient-danger btn-unduh-arsip" data-toggle="tooltip" data-placement="bottom" title="Unduh Arsip" for="Unduh Arsip" data-arsip="<?= $data['arsip_file']; ?>"><i class="far fa-file-pdf"></i></button>
            <a href="/sipedit/unggah_pelatihan/<?= $data['id_sipedit_arsip']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Arsip" for="Ubah Arsip"><i class="fas fa-edit"></i></a>
            <form action="/sipedit/hapus_arsip/<?= $data['id_sipedit_arsip']; ?>" method="post" class="d-inline formHapusData">
              <?= csrf_field(); ?>
              <input type="hidden" name="_method" value="DELETE">
              <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Arsip"><i class="fas fa-trash-alt"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
  $(document).ready(function() {
    $('.sub-kategori-sipedit').each(function() {
      const hasOptions = $(this).find('option').length > 1;

      if (!hasOptions) {
        $(this).addClass('disabled').prop('disabled', true);
      } else {
        $(this).removeClass('disabled').prop('disabled', false);
      }
    });

    setTimeout(function() {

      var table = $("#tabel_arsip_saya").DataTable({
        paging: true, // Nonaktifkan paginasi
        info: false, // Nonaktifkan info tabel
        columnDefs: [{
          targets: [0, 1, 2, 3, 4, 5, 6], // Kolom yang akan memiliki fitur ekspansi
          className: "details-control",
          orderable: true,
        }, ],
      });
      table.columns(1).visible(false);

      function sipeditArsipFile() {
        var data = $(this).attr("data-arsip")

        var tr = $(this).closest("tr");
        var row = table.row(tr);

        if (row.child.isShown()) {
          // Baris sudah terbuka, tutup
          row.child.hide();
          tr.removeClass("shown");
        } else {
          // Tampilkan konten detail di dalam baris
          row.child(`<iframe style="height:50vw; width:100%;" src="/sipedit_arsip/${data}" title="description"></iframe>`).show();
        }
      }

      // Attach the event listener to the table's tbody using the new function
      $("#tabel_arsip_saya tbody").on("click", "button.btn-unduh-arsip", sipeditArsipFile);
    }, 1000);
  });
</script>