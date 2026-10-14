<!DOCTYPE html>
<html>

<head>
  <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet" />
  <style>
    td.details-control {
      background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
      cursor: pointer;
    }

    tr.shown td.details-control {
      background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
    }

    tr.child {
      display: none;
    }
  </style>
</head>

<body>
  <table id="example11" class="table table-bordered table-striped table-hover" style="width: 100%;">
    <thead>
      <tr>
        <th>No</th>
        <th>Id</th>
        <th>Tanggal Pelaksanaan</th>
        <th>Nama Kegiatan</th>
        <th>Tempat Perjalanan</th>
        <th>Jumlah Orang</th>
        <th>Nominal</th>
        <th>Akses Data</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1;
      foreach ($perjalanan as $data) : ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= $data['id_perjalanan']; ?></td>
          <?php if ($data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir']) : ?>
            <td><?= $data['tgl_pelaksanaan']; ?></td>
          <?php else : ?>
            <td><?= $data['tgl_pelaksanaan']; ?> s/d <?= $data['tgl_pelaksanaan_akhir']; ?></td>
          <?php endif; ?>
          <td data-toggle="tooltip" data-placement="top" title="<?= $data['nama_kegiatan']; ?>"><?= strlen($data['nama_kegiatan']) > 36 ? substr($data['nama_kegiatan'], 0, 36) . '...' : $data['nama_kegiatan']; ?></td>
          <td data-toggle="tooltip" data-placement="top" title="<?= $data['tempat_perjalanan']; ?>"><?= strlen($data['tempat_perjalanan']) > 36 ? substr($data['tempat_perjalanan'], 0, 36) . '...' : $data['tempat_perjalanan']; ?></td>
          <td><?= $data['jumlah_peserta']; ?> Orang</td>
          <td><?= number_format($data['total_perjalanan'], '2', ',', '.'); ?></td>
          <td>
            <?= name_capitalize($data['nama']); ?>
          </td>
          <td>
            <?php if (session()->get('username') == $data['username'] || $akses) : ?>
              <button class="btn btn-xs bg-gradient-info tombol-td" data-toggle="tooltip" data-placement="bottom" title="Pratinjau" for="Lihat Data"><i class="fas fa-eye"></i></button>
              <button class="btn btn-xs btn-danger" onclick="<?= (date('Y', strtotime($data['tgl_pelaksanaan_akhir'])) >= 2025) ? 'downloadFileSinadiNew' : 'downloadFileSinadi'; ?>('<?= $data['id_perjalanan']; ?>','<?= $data['nama']; ?>','<?= $data['bukti_pembayaran']; ?>')" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="fas fa-file-pdf"></i></button>
              <?php if ($akses || $data['status_surat'] != "Disetujui"): ?>
                <a href="#" class="btn btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah Dokumen" for="Edit Data"><i class="fas fa-edit"></i></a>
              <?php endif; ?>
              <form action="/sinadi/dlt_sinadi/<?= $data['id_perjalanan']; ?>" method="post" class="d-inline formHapusData">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
              </form>
            <?php else : ?>
              <button class="btn btn-xs bg-gradient-info tombol-td" data-toggle="tooltip" data-placement="bottom" title="Pratinjau" for="Lihat Data"><i class="fas fa-eye"></i></button>
              <button class="btn btn-xs btn-danger" onclick="<?= (date('Y', strtotime($data['tgl_pelaksanaan_akhir'])) >= 2025) ? 'downloadFileSinadiNew' : 'downloadFileSinadi'; ?>('<?= $data['id_perjalanan']; ?>','<?= $data['nama']; ?>','<?= $data['bukti_pembayaran']; ?>')" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="fas fa-file-pdf"></i></button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th>No</th>
        <th>Id</th>
        <th>Tanggal Pelaksanaan</th>
        <th>Nama Kegiatan</th>
        <th>Tempat Perjalanan</th>
        <th>Jumlah Orang</th>
        <th>Nominal</th>
        <th>Akses Data</th>
        <th>Aksi</th>
      </tr>
    </tfoot>
  </table>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>
  <script>
    $(document).ready(function() {
      var table = $('#myTable').DataTable({
        paging: false,
        searching: false,
        ordering: false
      });

      $('#myTable tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
          row.child.hide();
          tr.removeClass('shown');
        } else {
          row.child(formatChildRow(row.data())).show();
          tr.addClass('shown');
        }
      });

      function formatChildRow(data) {
        var telepon = data[1];
        var email = data[2];

        return '<tr class="child"><td colspan="4"><p>Informasi Kontak:</p><ul><li>Telepon: ' + telepon + '</li><li>Email: ' + email + '</li></ul></td></tr>';
      }
    });
  </script>
</body>

</html>