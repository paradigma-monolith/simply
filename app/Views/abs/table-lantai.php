<table id="tableWithoutButton1" class="table table-bordered table-hover abs-master-table mb-0">
  <thead>
    <tr>
      <th width="10%" class="text-center">No</th>
      <th>Nama Lantai</th>
      <th width="24%" class="text-center">Inventaris</th>
      <th width="14%" class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1; ?>
    <?php foreach (($lantai ?? []) as $data) : ?>
      <tr>
        <td class="text-center font-weight-bold"><?= $nomor++; ?></td>
        <td class="font-weight-bold"><?= esc($data['lantai'] ?? ''); ?></td>
        <td class="text-center">
          <span class="abs-item-usage">
            <i class="fas fa-box"></i> <?= (int) ($data['jumlah_barang'] ?? 0); ?>
          </span>
        </td>
        <td class="text-center">
          <form action="/abs/dlt_lantai/<?= (int) ($data['id_abs_lantai'] ?? 0); ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus Lantai">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(function() {
    if (typeof $.fn.DataTable === 'undefined') return;
    if ($.fn.DataTable.isDataTable('#tableWithoutButton1')) {
      $('#tableWithoutButton1').DataTable().destroy();
    }
    $("#tableWithoutButton1").DataTable({
      responsive: true,
      pageLength: 10,
      lengthChange: false,
      autoWidth: false,
      searching: true,
      info: false,
      language: {
        search: '',
        searchPlaceholder: 'Cari lantai...',
        zeroRecords: 'Tidak ada data lantai',
        paginate: { previous: '&laquo;', next: '&raquo;' }
      }
    });
  });
</script>
