<div style="overflow-x: auto;">
  <table id="tabel_template_naskah" class="table table-bordered table-striped table-hover" style="width: 100%;">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th>Jenis Naskah</th>
        <th>Ukuran</th>
        <th>Nama File</th>
        <th>File</th>
        <th>Pembaharuan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php $nomor = 1; ?>
        <?php foreach ($getTemplateNaskah as $data) : ?>
          <td><?= $nomor++; ?></td>
          <td><?= $data['jenis_naskah']; ?></td>
          <td>
            <?php
            $filePath = './arkab_arsip/template_naskah/' . $data['file'];
            if (file_exists($filePath)) {
              $fileSize = filesize($filePath); // Mendapatkan ukuran file dalam byte

              // Format ukuran file ke KB, MB, atau GB
              if ($fileSize >= 1073741824) {
                $formattedSize = number_format($fileSize / 1073741824, 2) . ' GB';
              } elseif ($fileSize >= 1048576) {
                $formattedSize = number_format($fileSize / 1048576, 2) . ' MB';
              } elseif ($fileSize >= 1024) {
                $formattedSize = number_format($fileSize / 1024, 2) . ' KB';
              } else {
                $formattedSize = $fileSize . ' bytes';
              }

              echo $formattedSize;
            } else {
              echo 'File tidak ditemukan';
            }
            ?>
          </td>
          <td><?= $data['nama_file']; ?></td>
          <td style="text-align: center;">
            <?php
            $file = $data['file'];

            // Dapatkan ekstensi file
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Ekstensi dalam huruf kecil

            // Tentukan ikon berdasarkan ekstensi
            if ($ext === 'doc' || $ext === 'docx') {
              $btn = 'btn-primary';
              $icon = '<i class="far fa-file-word"></i>'; // Ikon Word
            } elseif ($ext === 'pdf') {
              $btn = 'btn-danger';
              $icon = '<i class="far fa-file-pdf"></i>'; // Ikon PDF
            } else {
              $btn = 'btn-info';
              $icon = '<i class="far fa-file"></i>'; // Ikon file umum
            }
            ?>

            <a href="download_template_naskah/<?= urlencode($file); ?>" class="btn m-1 btn-xs <?= $btn ?>">
              <?= $icon; ?>
            </a>

          </td>
          <td>
              Pembuatan: <?= $data['created_at'] ?> <br />
              Pembaharuan: <?= $data['updated_at'] ?>
          </td>
          <td style="text-align: center;">
            <a href="/tata_naskah_dinas/template_naskah/tambah_naskah/<?= $data['id_arsip_template_naskah']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Template Naskah" for="Ubah Template Naskah"><i class="fas fa-edit"></i></a>
            <form action="/tata_naskah_dinas/template_naskah/hapus_naskah/<?= $data['id_arsip_template_naskah']; ?>" method="post" class="d-inline formHapusData">
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
  $("#tabel_template_naskah").DataTable({
    "responsive": true,
    "responsive": {
      "details": {
        "renderer": DataTable.Responsive.renderer.listHiddenNodes()
      }
    },
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "colvis"],
    "pageLength": 30,
  }).buttons().container().appendTo('#tabel_template_naskah_wrapper .col-md-6:eq(0)');
</script>