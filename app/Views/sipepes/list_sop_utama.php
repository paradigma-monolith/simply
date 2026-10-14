<table id="tabel_sop_utama" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Nomor Dokumen</th>
      <th>Judul</th>
      <th>Divisi</th>
      <th>Tanggal Pembuatan</th>
      <th>Tanggal Efektif</th>
      <th>Revisi</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($dataSopUtama as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['no_dokumen']; ?></td>
        <td><?= $data['judul']; ?></td>
        <td>
            <?= $data['unit_kerja']; ?> <br />
            Jenis: <i><?= $data['jenis_dokumen']; ?></i>
        </td>
        <td>
          <?php if ($data['tanggal_pembuatan'] == "0000-00-00" || $data['tanggal_pembuatan'] == "0000-01-01") {
            echo "<span class='text-danger'>Belum ada data.</span>";
          } else {
            echo date('d M Y', strtotime($data['tanggal_pembuatan']));
          }
          ?>
        </td>
        <td>
          <?php if ($data['tanggal_efektif'] == "0000-00-00" || $data['tanggal_efektif'] == "0000-01-01") {
            echo "<span class='text-danger'>Belum ada data.</span>";
          } else {
            echo date('d M Y', strtotime($data['tanggal_efektif']));
          }
          ?>
        </td>
        <td>
          <?php if ($data['tanggal_revisi'] == "0000-00-00" || $data['tanggal_revisi'] == "0000-01-01") {
            if($data['revisi_ke'] > 0) {
                echo "<span class='text-danger'>Tidak ada data tanggal</span> (Rev. " . $data['revisi_ke'] . ")";
            } else {
                echo "<span class='text-danger'>Belum ada data.</span>";
            }
          } else {
            echo date('d M Y', strtotime($data['tanggal_revisi'])) . " (Rev. " . $data['revisi_ke'] . ")";
          }
          ?>
        </td>
        <td>
            <span class="badge badge-warning d-block"><?= $data['status_pengesahan']; ?></span>
            <span class="badge changeStatus<?= $data['dokumen_id']; ?> badge-<?= $data['status'] == 'Berlaku' ? "success" : "danger"; ?>"><?= $data['status']; ?></span>
        </td>
        <td>
          <a href="/sipepes/lihat_dokumen_sop/<?= $data['dokumen_id']; ?>" class="btn btn-xs bg-gradient-info" data-toggle="tooltip" data-placement="bottom" title="Pratinjau" for="Lihat Data"><i class="fas fa-eye"></i></a>
          <a href="/sipepes/ubah_dokumen_sop/<?= $data['dokumen_id']; ?>" class="btn btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Dokumen" for="Edit Data"><i class="fas fa-edit"></i></a>
          <form action="/sipepes/hapus_dokumen_sop/<?= $data['dokumen_id']; ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen" onclick="return confirm('Dokumen akan dihapus. Anda yakin?');"><i class="fas fa-trash-alt"></i></button>
          </form>
          <?php if(session()->get('role') == 'Admin'): ?>
            <button onclick="changeStatusSOP(this)" data-id="<?= $data['dokumen_id']; ?>" class="btn btn-xs btn-outline-<?= $data['status'] == 'Berlaku' ? "success" : "danger"; ?>"><i class="fas fa-file-alt"></i></button>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  function changeStatusSOP(button) {
    var btn = $(button);
    var btnHtml = btn.html();
    var id = btn.data("id");
    var status = $(".changeStatus"+id);
    
    $.ajax({
      url: '/sipepes/sop/change_status/' + parseInt(id),
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $(btn).attr('disabled', true);
        $(btn).html(`<i class='fas fa-sync-alt fa-spin'></i>`)
      },
      success: function(response) {
        if (response.data) {
          btn.toggleClass('btn-outline-success btn-outline-danger');
          status.toggleClass('badge-success badge-danger');
          if (btn.hasClass('btn-outline-success')) {
              status.text('Berlaku');
          } else {
              status.text('Tidak Berlaku');
          }
          Toast.fire({
              icon: 'success',
              title: 'Berhasil diperbaharui..'
          });
          countDaftarSOP();
        }
      },
      complete: function() {
        $(btn).attr('disabled', false)
        $(btn).html(btnHtml)
      }
    });
  }
  
  $("#tabel_sop_utama").DataTable({
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
  }).buttons().container().appendTo('#tabel_sop_utama_wrapper .col-md-6:eq(0)');
</script>
