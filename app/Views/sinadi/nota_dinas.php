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
        <div class="card card-primary card-outline shadow-sm">
          <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
            <h3 class="card-title mb-0"><i class="fas fa-file-pdf mr-2 text-danger"></i>Daftar Nota Dinas Perjalanan Dinas</h3>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select id="filterStatusNotaDinas" class="form-control form-control-sm" style="width: auto;">
                <option value="">Semua Status Berkas</option>
                <option value="uploaded">Sudah Upload</option>
                <option value="missing">Belum Upload</option>
              </select>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="tableNotaDinas" class="table table-bordered table-striped table-hover w-100">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Identitas Pegawai</th>
                    <th>Tanggal Perjalanan</th>
                    <th>No. Surat Tugas</th>
                    <th>Nama Kegiatan</th>
                    <th>Tempat Perjalanan</th>
                    <th width="13%">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>


<script>
  $(function() {
    function escHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function encodeFilePath(filename) {
      return String(filename || '')
        .split('/')
        .map(function(part) {
          return encodeURIComponent(part);
        })
        .join('/');
    }

    var tableNotaDinas = $("#tableNotaDinas").DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      deferRender: true,
      searchDelay: 350,
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      order: [[2, 'desc']],
      ajax: {
        url: '/sinadi/notaDinasData',
        type: 'POST',
        data: function(d) {
          d.status_file = $('#filterStatusNotaDinas').val() || '';
        }
      },
      language: {
        processing: 'Memuat data...',
        search: 'Cari:',
        lengthMenu: 'Tampilkan _MENU_ data',
        zeroRecords: 'Tidak ada data yang cocok',
        emptyTable: 'Tidak ada data nota dinas',
        info: 'Menampilkan _START_ s.d. _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 data',
        infoFiltered: '(disaring dari _MAX_ total data)',
        paginate: {
          first: 'Pertama',
          last: 'Terakhir',
          next: 'Berikutnya',
          previous: 'Sebelumnya'
        }
      },
      columns: [
        {
          data: 'no',
          orderable: false,
          searchable: false,
          className: 'text-center align-middle'
        },
        {
          data: 'nama_pegawai',
          className: 'align-middle',
          render: function(_, __, row) {
            return '<small>NIP/NOPEG.' + escHtml(row.nip_pegawai) + '</small><br />' + escHtml(row.nama_pegawai);
          }
        },
        {
          data: 'tanggal',
          className: 'align-middle',
          render: function(val) {
            return escHtml(val);
          }
        },
        {
          data: 'no_surat_tugas',
          className: 'align-middle',
          render: function(val) {
            return escHtml(val);
          }
        },
        {
          data: 'nama_kegiatan',
          className: 'align-middle',
          render: function(val) {
            return escHtml(val);
          }
        },
        {
          data: 'tempat_perjalanan',
          className: 'align-middle',
          render: function(val) {
            return escHtml(val);
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: 'text-nowrap text-center align-middle',
          render: function(_, __, row) {
            var hasFile = !!row.file;
            var fileAttr = escHtml(row.file || '');
            var pdfAttr = hasFile ? escHtml('nota-dinas/' + encodeFilePath(row.file)) : '#';
            var pdfClass = hasFile ? 'btn-outline-danger' : 'btn-secondary disabled';
            var disabledAttr = hasFile ? '' : ' disabled';

            return '<a href="#" data-file="' + pdfAttr + '" class="btn btn-sm ' + pdfClass + ' fileNotaDinas" title="Lihat Nota Dinas"><i class="fas fa-file-pdf"></i></a> ' +
              '<button type="button" class="btn btn-sm btn-warning editNotaDinas" data-id="' + row.id_peserta + '" data-file="' + fileAttr + '" title="Upload / Edit Nota Dinas"><i class="fas fa-edit"></i></button> ' +
              '<button type="button" class="btn btn-sm btn-danger hapusNotaDinas" data-id="' + row.id_peserta + '" data-file="' + fileAttr + '"' + disabledAttr + ' title="Hapus Dokumen"><i class="fas fa-trash"></i></button>';
          }
        }
      ]
    });

    $("#filterStatusNotaDinas").on('change', function() {
      tableNotaDinas.ajax.reload();
    });

    $("#tableNotaDinas").on('click', '.editNotaDinas', function() {
      var button = $(this);
      var buttonHtml = button.html();
      var formData = {
        id: button.attr('data-id'),
        file: button.attr('data-file') || ''
      };

      $.ajax({
        url: '/sinadi/getNotaDinas',
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#modal-lg").find('.modal-body').html('');
          button.prop('disabled', true);
          button.html('<i class="fas fa-sync-alt fa-spin"></i>');
        },
        success: function(response) {
          if (response && response.data) {
            $("#modal-lg").find('.modal-body').html(response.data);
            $("#modal-lg").modal('show');
          }
          button.prop('disabled', false);
          button.html(buttonHtml);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          button.prop('disabled', false);
          button.html(buttonHtml);
          Swal.fire({
            text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          });
        }
      });
    });

    $("#tableNotaDinas").on('click', '.fileNotaDinas', function(e) {
      e.preventDefault();
      var file = $(this).attr('data-file');

      if (file && file !== "#") {
        var baseUrl = '<?= rtrim(base_url(), "/"); ?>';
        var fullUrl = /^https?:\/\//i.test(file) ? file : (baseUrl + '/' + String(file).replace(/^\/+/, ''));

        $("#modal-xl").find('.modal-body').html(
          '<iframe class="nota-dinas-modal-iframe" src="' + escHtml(fullUrl) + '" frameborder="1" style="width: 100%; height: 80vh;"></iframe>'
        );
        $("#modal-xl").modal('show');
      }
    });

    $("#modal-xl").on('hidden.bs.modal', function() {
      if ($(this).find('.modal-body iframe.nota-dinas-modal-iframe').length) {
        $(this).find('.modal-body').empty();
      }
    });

    $("#tableNotaDinas").on('click', '.hapusNotaDinas', function(e) {
      e.preventDefault();
      var id = $(this).attr('data-id');
      var file = $(this).attr('data-file');

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
                  tableNotaDinas.ajax.reload(null, false);
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
  });
</script>

<?= $this->endSection(); ?>