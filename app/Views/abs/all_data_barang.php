<?php
$rows = $getData ?? [];
$totalRows = count($rows);
?>

<div class="abs-inv-table-card">
  <div class="abs-inv-table-head">
    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
      <span class="abs-inv-result-badge">
        <i class="fas fa-boxes mr-1"></i> <?= number_format($totalRows, 0, ',', '.'); ?> barang ditampilkan
      </span>
    </div>
    <div class="abs-inv-search-wrap">
      <i class="fas fa-search abs-inv-search-icon"></i>
      <input type="search" id="absTableSearchInput" class="abs-inv-search-input" placeholder="Cari nama barang, kode, NUP, merek, ruang..." autocomplete="off">
    </div>
  </div>

  <form id="absUpdateLokasi" action="/abs/updateLokasi" method="post">
    <div class="table-responsive">
      <table id="tableDataABS" class="table table-bordered table-hover mb-0" style="width: 100%;">
        <thead>
          <tr>
            <th width="4%" class="text-center">No</th>
            <th width="31%">Keterangan Data Inventaris</th>
            <th width="16%">Wilayah Kerja</th>
            <th width="10%">Lantai</th>
            <th width="13%">Ruang</th>
            <th width="11%">Kategori</th>
            <th width="8%" class="text-center">Status</th>
            <th width="7%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $nomor = 1; ?>
          <?php foreach ($rows as $data) : ?>
            <?php
            $idBangunan = (int) ($data['id_abs_bangunan'] ?? 0);
            $dataBarang = \App\Models\AbsLokasiModel::safeUnserialize($data['data_barang'] ?? '');
            $namaBarang = $dataBarang['Nama Barang'] ?? ($dataBarang ? reset($dataBarang) : '-');
            $kodeBarang = $dataBarang['Kode Barang'] ?? '';
            $nup        = $dataBarang['NUP'] ?? '';
            $merek      = $dataBarang['Merek'] ?? '';
            $unitKerja  = trim((string) ($data['unit_kerja'] ?? ''));
            $lantai     = trim((string) ($data['lantai'] ?? ''));
            $ruang      = trim((string) ($data['ruang'] ?? ''));
            $jenis      = trim((string) ($data['jenis'] ?? ''));
            $status     = trim((string) ($data['status_tersedia'] ?? 'Tidak Tersedia'));
            $qrInternal = trim((string) ($data['kode_qr_code_internal'] ?? ''));
            ?>
            <tr>
              <td class="text-center font-weight-bold">
                <?= $nomor++; ?>
                <input type="hidden" name="id_abs_bangunan[]" value="<?= $idBangunan; ?>">
              </td>
              <td>
                <div class="font-weight-bold" style="color: #0f172a; font-size: 0.83rem; line-height: 1.3;">
                  <?= esc((string) $namaBarang); ?>
                </div>
                <div class="d-flex flex-wrap align-items-center mt-1" style="gap: 4px;">
                  <?php if ($kodeBarang !== '') : ?>
                    <span class="abs-meta-tag"><i class="fas fa-barcode mr-1"></i><?= esc((string) $kodeBarang); ?></span>
                  <?php endif; ?>
                  <?php if ($nup !== '') : ?>
                    <span class="abs-meta-tag">NUP: <?= esc((string) $nup); ?></span>
                  <?php endif; ?>
                  <?php if ($merek !== '' && $merek !== '-') : ?>
                    <span class="abs-meta-tag"><i class="fas fa-tag mr-1"></i><?= esc((string) $merek); ?></span>
                  <?php endif; ?>
                </div>
                <?php
                $extraPairs = [];
                $skipKeys = ['Nama Barang' => true, 'Kode Barang' => true, 'NUP' => true, 'Merek' => true];
                $pairCount = 0;
                foreach ($dataBarang as $lbl => $val) {
                  if (isset($skipKeys[$lbl]) || trim((string) $val) === '') {
                    continue;
                  }
                  $extraPairs[] = esc((string) $lbl) . ': <strong>' . esc((string) $val) . '</strong>';
                  if (++$pairCount >= 4) {
                    break;
                  }
                }
                ?>
                <?php if (!empty($extraPairs)) : ?>
                  <div class="text-muted mt-1" style="font-size: 0.72rem; line-height: 1.35;">
                    <?= implode(' &bull; ', $extraPairs); ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($unitKerja !== '') : ?>
                  <span class="font-weight-bold text-dark" style="font-size: 0.78rem;"><?= esc($unitKerja); ?></span>
                <?php else : ?>
                  <span class="abs-missing-pill"><i class="fas fa-exclamation-triangle mr-1"></i>Tidak diketahui</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($lantai !== '') : ?>
                  <span><?= esc($lantai); ?></span>
                <?php else : ?>
                  <span class="abs-missing-pill">Tidak diketahui</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($ruang !== '') : ?>
                  <span><?= esc($ruang); ?></span>
                <?php else : ?>
                  <span class="abs-missing-pill">Tidak diketahui</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($jenis !== '') : ?>
                  <span class="abs-jenis-pill"><?= esc($jenis); ?></span>
                <?php else : ?>
                  <span class="abs-missing-pill">Tanpa kategori</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($status === 'Tersedia') : ?>
                  <span class="abs-status-badge is-available"><i class="fas fa-check-circle"></i> Tersedia</span>
                <?php else : ?>
                  <span class="abs-status-badge is-unavailable"><i class="fas fa-times-circle"></i> <?= esc($status); ?></span>
                <?php endif; ?>
              </td>
              <td class="text-center text-nowrap">
                <div class="abs-act-group">
                  <a target="_blank"
                     href="/data_abs/<?= esc($qrInternal, 'attr'); ?>"
                     data-action="view"
                     class="btn btn-xs btn-info abs-act-btn link-action-abs-barang"
                     title="Lihat Detail Publik &amp; QR">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="/abs/editDataBarang/<?= $idBangunan; ?>"
                     data-action="edit"
                     class="btn btn-xs btn-warning abs-act-btn link-action-abs-barang"
                     title="Edit Data Barang">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#"
                     data-action="hapus"
                     data-id="<?= $idBangunan; ?>"
                     class="btn btn-xs btn-danger abs-act-btn link-action-abs-barang"
                     title="Hapus Data Barang">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </form>
</div>

<script>
  $(function() {
    if (typeof $.fn.DataTable === 'undefined') return;
    if ($.fn.DataTable.isDataTable('#tableDataABS')) {
      $('#tableDataABS').DataTable().destroy();
    }

    var table = $('#tableDataABS').DataTable({
      dom: "t<'row mt-2 px-3 pb-2 align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      paging: true,
      pageLength: 20,
      lengthChange: false,
      searching: true,
      info: true,
      autoWidth: false,
      deferRender: true,
      order: [],
      columnDefs: [
        { targets: [7], orderable: false }
      ],
      language: {
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ barang',
        infoEmpty: 'Tidak ada data inventaris',
        infoFiltered: '(disaring dari _MAX_ total barang)',
        zeroRecords: 'Tidak ada data inventaris yang cocok',
        paginate: {
          previous: '&laquo;',
          next: '&raquo;'
        }
      }
    });

    $('#absTableSearchInput').off('input keyup search').on('input keyup search', function() {
      table.search(this.value).draw();
    });
  });
</script>
