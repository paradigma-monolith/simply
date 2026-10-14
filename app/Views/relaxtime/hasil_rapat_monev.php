<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Dokumen Notulensi Hasil Rapat Monev</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-tambah-dokumen">
                <i class="fas fa-plus"></i> Tambah Dokumen
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="table-hasil-rapat" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Nama Dokumen</th>
                  <th>Tanggal Rapat</th>
                  <th>Waktu Upload</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($dokumen as $row) : ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= esc($row['nama_dokumen']); ?></td>
                  <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                  <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                  <td>
                    <a href="<?= base_url('uploads/relaxtime/hasil_rapat/' . $row['file_dokumen']); ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat/Download">
                      <i class="fas fa-eye"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusDokumen(<?= $row['id_hasil_rapat']; ?>)" data-toggle="tooltip" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>

  <!-- Modal Tambah Dokumen -->
  <div class="modal fade" id="modal-tambah-dokumen">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="<?= base_url('relaxtime/simpan_hasil_rapat'); ?>" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Upload Dokumen Hasil Rapat</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Dokumen / Keterangan</label>
              <input type="text" class="form-control" name="nama_dokumen" required placeholder="Masukkan nama dokumen">
            </div>
            <div class="form-group">
              <label>Tanggal Rapat</label>
              <input type="date" class="form-control" name="tanggal" required>
            </div>
            <div class="form-group">
              <label>File Dokumen (PDF/Word)</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="file_dokumen" name="file_dokumen" accept=".pdf,.doc,.docx" required>
                <label class="custom-file-label" for="file_dokumen">Pilih file</label>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>

<!-- /.content -->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
  $(function () {
    <?php if (session()->getFlashdata('pesan')) : ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('pesan') ?>',
        timer: 3000,
        showConfirmButton: false
      });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?= session()->getFlashdata('error') ?>',
        timer: 3000,
        showConfirmButton: false
      });
    <?php endif; ?>

    $('#table-hasil-rapat').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    // bsCustomFileInput
    bsCustomFileInput.init();
  });

  function hapusDokumen(id) {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Dokumen ini akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "<?= base_url('relaxtime/hapus_hasil_rapat/'); ?>" + id;
      }
    });
  }
</script>
<?= $this->endSection(); ?>