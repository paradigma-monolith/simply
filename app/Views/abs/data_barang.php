<h3>
  Daftar Inventaris
</h3>

<!-- SweetAlert2 untuk notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  <?php if (session()->getFlashdata('success')) : ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      html: '<?= session()->getFlashdata('success') ?>'
    });
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')) : ?>
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      html: '<?= session()->getFlashdata('error') ?>'
    });
  <?php endif; ?>
</script>

<table class="table table-sm table-bordered table-stripped">
  <thead>
    <tr>
      <th colspan="2" class="text-right">Jumlah Data</th>
      <th colspan="3"><?= count($getData); ?></th>
    </tr>
    <tr>
      <th>#</th>
      <th>Wilayah</th>
      <th>Lantai</th>
      <th>Ruang</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody style="font-size: 9pt;">
    <?php foreach ($getData as $data) : ?>
      <tr>
        <td><?= $data['kode_qr_code_pusat']; ?></td>
        <td><small><?= $data['unit_kerja'] ? $data['unit_kerja'] : '-'; ?></small></td>
        <td><?= $data['lantai'] ? $data['lantai'] : '-'; ?></td>
        <td><?= $data['ruang'] ? $data['ruang'] : '-'; ?></td>
        <td>
          <a href="/data_abs/<?= $data['kode_qr_code_internal']; ?>" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>