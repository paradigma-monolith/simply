<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .container {
    position: relative;
    overflow: hidden;
    width: 100%;
    padding-top: 56.25%;
    /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
  }

  /* Then style the iframe to fit in the container div with full height and width */
  .responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
  }
</style>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table id="tableWithoutExporting" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Identitas Pegawai</th>
                  <th>Tanggal Perjalanan</th>
                  <th>No. surat Tugas</th>
                  <th>Nama Kegiatan</th>
                  <th>Tempat Perjalanan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($notaDinas as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td>
                      <small>NIP/NOPEG.<?= $data['nip_pegawai']; ?></small><br />
                      <?= $data['nama_pegawai']; ?>
                    </td>
                    <td>
                      <?php if ($data['tgl_pergi'] == $data['tgl_pulang']) : ?>
                        <?= mediumdate_indo($data['tgl_pulang']); ?>
                      <?php else : ?>
                        <?= mediumdate_indo($data['tgl_pergi']); ?> s/d <?= mediumdate_indo($data['tgl_pulang']); ?>
                      <?php endif; ?>
                    </td>
                    <td><?= $data['no_surat_tugas']; ?></td>
                    <td><?= $data['nama_kegiatan']; ?></td>
                    <td><?= $data['tempat_perjalanan']; ?></td>
                    <td>
                      <a href="#" data-file="<?= $data['file'] == null ? '#' : 'nota-dinas/' . $data['file']; ?>" class="btn btn-sm <?= $data['file'] == null ? 'btn-secondary disabled' : 'btn-outline-danger'; ?> fileNotaDinas"><i class="fas fa-file-pdf"></i></a>
                      <button class="btn btn-sm btn-warning editNotaDinas" data-id="<?= $data['id_peserta']; ?>" data-file="<?= $data['file']; ?>"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-danger hapusNotaDinas" data-id="<?= $data['id_peserta']; ?>" data-file="<?= $data['file']; ?>" <?= $data['file'] == null ? 'disabled' : ''; ?> title="Hapus Dokumen"><i class="fas fa-trash"></i></button>
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
</section>


<script>
  $(".editNotaDinas").on('click', function() {

    var button = $(this)

    var buttonHtml = $(this).html()



    var formData = {

      id: $(this).data('id'),

      file: $(this).data('file')

    };



    $.ajax({

      url: `/sinadi/getNotaDinas`,

      data: formData,

      method: 'post',

      dataType: 'json',

      beforeSend: function() {

        $("#modal-lg").find('.modal-body').html('');

        $(button).attr('disabled', true);

        $(button).html(`<i class='fas fa-sync-alt fa-spin'></i>`)

      },

      success: function(response) {

        if (response.data) {

          $("#modal-lg").find('.modal-body').html(response.data);

          $("#modal-lg").modal('show');

        }

        $(button).attr('disabled', false)

        $(button).html(buttonHtml)

      },

      error: function(xhr, ajaxOptions, thrownError) {

        Swal.fire({

          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError

        })

      }

    });

  });

  $(".fileNotaDinas").on('click', function() {



    var file = $(this).data('file');



    if (file !== "#") {

      var fullUrl = '<?= base_url(); ?>/' + file;

      // var iframeSrc = 'https://drive.google.com/viewerng/viewer?embedded=true&url=' + encodeURIComponent(fullUrl);



      $("#modal-xl").find('.modal-body').html(

        '<iframe src="' + fullUrl + '" frameborder="1" style="width: 100%; height: 80vh;"></iframe>'

      );

      $("#modal-xl").modal('show');

    }

  });

  $(".hapusNotaDinas").on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var file = $(this).data('file');

    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Dokumen nota dinas akan dihapus secara permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/sinadi/hapusNotaDinas',
          method: 'POST',
          data: {
            id: id,
            file: file
          },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              Swal.fire(
                'Terhapus!',
                response.message,
                'success'
              ).then(() => {
                location.reload();
              });
            } else {
              Swal.fire(
                'Gagal!',
                response.message || 'Terjadi kesalahan saat menghapus dokumen.',
                'error'
              );
            }
          },
          error: function() {
            Swal.fire(
              'Gagal!',
              'Terjadi kesalahan pada server.',
              'error'
            );
          }
        });
      }
    });
  });
</script>

<?= $this->endSection(); ?>