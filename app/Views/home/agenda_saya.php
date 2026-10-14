<div class="agenda-table-container" style="max-height: 600px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.25rem;">
  <table class="table table-sm table-bordered mb-0">
    <thead class="thead-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
      <tr>
        <th style="border-top: none;">Warna</th>
        <th style="border-top: none;">Topik</th>
        <th style="border-top: none;">Tanggal Pelaksanaan</th>
        <th style="border-top: none;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($getData as $data) : ?>
        <tr>
          <td style="text-align: center; vertical-align: middle;">
            <div style="width: 20px; height: 20px; background-color: <?= isset($data['warna_agenda']) && !empty($data['warna_agenda']) ? $data['warna_agenda'] : '#0073b7'; ?>; border-radius: 50%; margin: 0 auto; border: 1px solid #ddd;"></div>
          </td>
          <td><?= $data['topik']; ?></td>
          <td>
            <?php if (isset($data['waktu_pelaksana_awal']) && isset($data['waktu_pelaksana_akhir'])): ?>
              <?php 
                $tanggal_awal = date('d/m/Y H:i', strtotime($data['waktu_pelaksana_awal']));
                $tanggal_akhir = date('d/m/Y H:i', strtotime($data['waktu_pelaksana_akhir']));
                
                // Jika tanggal sama, tampilkan hanya sekali
                if (date('Y-m-d', strtotime($data['waktu_pelaksana_awal'])) == date('Y-m-d', strtotime($data['waktu_pelaksana_akhir']))) {
                  echo date('d/m/Y', strtotime($data['waktu_pelaksana_awal'])) . '<br><small class="text-muted">' . date('H:i', strtotime($data['waktu_pelaksana_awal'])) . ' - ' . date('H:i', strtotime($data['waktu_pelaksana_akhir'])) . '</small>';
                } else {
                  echo $tanggal_awal . '<br><small class="text-muted">s/d ' . $tanggal_akhir . '</small>';
                }
              ?>
            <?php else: ?>
              <small class="text-muted">Belum ditentukan</small>
            <?php endif; ?>
          </td>
          <td>
            <button class="btn btn-sm btn-warning mr-1" onclick="EditAgendaSaya('<?= $data['id_agenda_user']; ?>')" title="Edit Agenda">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="DeleteAgendaSaya('<?= $data['id_agenda_user']; ?>')" title="Hapus Agenda">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
  function EditAgendaSaya(id) {
    $("#modal-lg").find('.modal-body').html('')
    $("#modal-lg").find('.modal-header').html('')
    $.ajax({
      url: '/home/agenda/ajax/get_agenda_saya/' + id,
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response.data) {
          $("#modal-lg").find('.modal-header').html(
            '<button class="btn btn-sm btn-light float-right" onclick="$(\'#modal-lg\').modal(\'hide\')">Tutup</button>' +
            '<form action="/home/dlt_agenda/' + id + '" method="post" class="d-inline formHapusData">' +
            '<?= csrf_field(); ?>' +
            '<input type="hidden" name="_method" value="DELETE">' +
            '<button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-trash-alt"></i> Hapus Agenda</button>' +
            '</form>'
          )
          $("#modal-lg").find('.modal-body').html(response.data)
          $("#modal-lg").modal('show')
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      }
    });
  }

  function DeleteAgendaSaya(id) {
    Swal.fire({
      title: 'Konfirmasi Hapus',
      text: 'Apakah Anda yakin ingin menghapus agenda ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/home/dlt_agenda/' + id,
          method: 'POST',
          data: {
            '_method': 'DELETE',
            '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>'
          },
          success: function(response) {
            Swal.fire({
              title: 'Berhasil!',
              text: 'Agenda berhasil dihapus.',
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              // Refresh agenda list
              AgendaSaya();
              getCallendarAgenda();
            });
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
              title: 'Error!',
              text: 'Gagal menghapus agenda: ' + xhr.responseText,
              icon: 'error'
            });
          }
        });
      }
    });
  }
</script>