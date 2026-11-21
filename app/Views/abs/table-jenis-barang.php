<table id="tableWithoutButton3" class="table table-bordered table-hover abs-master-table mb-0">
  <thead>
    <tr>
      <th width="10%" class="text-center">No</th>
      <th>Kategori</th>
      <th width="24%" class="text-center">Inventaris</th>
      <th width="14%" class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1; ?>
    <?php foreach (($jenis ?? []) as $data) : ?>
      <tr>
        <td class="text-center font-weight-bold"><?= $nomor++; ?></td>
        <td class="font-weight-bold"><?= esc($data['jenis'] ?? ''); ?></td>
        <td class="text-center">
          <span class="abs-item-usage">
            <i class="fas fa-box"></i> <?= (int) ($data['jumlah_barang'] ?? 0); ?>
          </span>
        </td>
        <td class="text-center">
          <form action="/abs/dlt_jenis/<?= (int) ($data['id_abs_jenis'] ?? 0); ?>" method="post" class="d-inline formHapusData">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus Kategori">
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
    if ($.fn.DataTable.isDataTable('#tableWithoutButton3')) {
      $('#tableWithoutButton3').DataTable().destroy();
    }
    $("#tableWithoutButton3").DataTable({
      responsive: true,
      pageLength: 10,
      lengthChange: false,
      autoWidth: false,
      searching: true,
      info: false,
      language: {
        search: '',
        searchPlaceholder: 'Cari kategori...',
        zeroRecords: 'Tidak ada data kategori',
        paginate: { previous: '&laquo;', next: '&raquo;' }
      }
    });
  });
</script>
