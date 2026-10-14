<table class="table table-bordered table-striped table-sm text-center" id="table-tikus">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Wilayah Kerja</th>
      <th>Lokasi Pemasangan</th>
      <th>Jenis Umpan</th>
      <th>Spesies (Jumlah)</th>
      <th>Perangkap Dipasang</th>
      <th>Tikus Tertangkap</th>
      <th>Success Trap (%)</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($tikus as $row): 
      $displaySpesies = '-';
      if (!empty($row['spesies'])) {
        $decoded = json_decode($row['spesies'], true);
        if (is_array($decoded)) {
          $badges = [];
          foreach ($decoded as $spName => $spJml) {
            if ((int)$spJml > 0) {
              $badges[] = '<span class="badge badge-light border text-dark py-1 px-2 mb-1 mr-1"><strong>' . esc($spName) . ':</strong> ' . (int)$spJml . '</span>';
            }
          }
          $displaySpesies = !empty($badges) ? implode(' ', $badges) : '-';
        } else {
          $displaySpesies = '<span class="badge badge-light border text-dark py-1 px-2">' . esc($row['spesies']) . '</span>';
        }
      }
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= !empty($row['tanggal_mulai']) ? $row['tanggal_mulai'] : $row['tanggal']; ?></td>
      <td><?= !empty($row['tanggal_selesai']) ? $row['tanggal_selesai'] : (!empty($row['tanggal_mulai']) ? $row['tanggal_mulai'] : $row['tanggal']); ?></td>
      <td><?= $row['wilker']; ?></td>
      <td><?= $row['lokasi_pemasangan']; ?></td>
      <td><?= $row['jenis_umpan']; ?></td>
      <td class="text-left"><?= $displaySpesies; ?></td>
      <td><?= $row['jml_perangkap_dipasang']; ?></td>
      <td><?= $row['jml_tikus_tertangkap']; ?></td>
      <td><?= number_format($row['success_trap'], 2); ?>%</td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-tikus" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-danger btn-hapus-tikus" data-id="<?= $row['id_tikus']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah/Edit Tikus -->
<div class="modal fade" id="modal-tikus" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-tikus">
        <input type="hidden" name="id_tikus" id="id_tikus_form">
        <div class="modal-header bg-success" id="modal-tikus-header">
          <h5 class="modal-title font-weight-bold" id="modal-tikus-title">Tambah Data Tikus</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_mulai" id="tikus_tanggal_mulai" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Tanggal Selesai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_selesai" id="tikus_tanggal_selesai" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Wilayah Kerja <span class="text-danger">*</span></label>
              <select name="id_wilayah_kerja" class="form-control" required>
                <option value="">-- Pilih Wilayah Kerja --</option>
                <?php foreach($wilayah_kerja as $wk): ?>
                <option value="<?= $wk['id_unit_kerja']; ?>"><?= $wk['unit_kerja']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Lokasi</label>
              <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Buffer Area / Perimeter">
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Lokasi Pemasangan</label>
              <input type="text" name="lokasi_pemasangan" class="form-control" placeholder="Contoh: Dermaga 1, Gudang A">
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Jenis Umpan</label>
              <input type="text" name="jenis_umpan" class="form-control" placeholder="Contoh: Kelapa bakar, Ikan asin">
            </div>
          </div>

          <hr class="mt-1 mb-3">

          <!-- Multi-Spesies & Jumlah Container -->
          <div class="card border mb-3 shadow-sm">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
              <strong class="text-dark"><i class="fas fa-mouse mr-1 text-primary"></i> Spesies Tikus Tertangkap & Jumlah</strong>
              <button type="button" class="btn btn-sm btn-outline-success btn-tambah-spesies-tikus">
                <i class="fas fa-plus mr-1"></i> Tambah Spesies
              </button>
            </div>
            <div class="card-body p-2" id="spesies-tikus-container">
              <!-- Item baris spesies akan dimasukkan di sini secara dinamis -->
            </div>
            <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center small text-muted">
              <span><i class="fas fa-info-circle mr-1 text-info"></i>Pilih spesies dan isi jumlahnya. Bisa memilih lebih dari satu spesies.</span>
              <span>Total dari Spesies: <strong id="sum_spesies_tertangkap" class="text-primary font-weight-bold" style="font-size: 1.05rem;">0</strong> ekor</span>
            </div>
          </div>

          <!-- Parameter Perangkap & Success Trap -->
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Jumlah Perangkap Dipasang</label>
              <input type="number" min="0" name="jml_perangkap_dipasang" id="jml_perangkap_dipasang" class="form-control tikus-calc" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold">Jumlah Tikus Tertangkap</label>
              <input type="number" min="0" name="jml_tikus_tertangkap" id="jml_tikus_tertangkap" class="form-control tikus-calc" value="0">
              <small class="text-muted">Terisi otomatis dari total rincian spesies di atas</small>
            </div>
          </div>

          <div class="alert alert-light border mt-2 mb-0 d-flex justify-content-between align-items-center">
            <span><strong>Rumus Success Trap:</strong> (Tikus Tertangkap / Perangkap Dipasang) &times; 100%</span>
            <span class="h6 mb-0"><strong>Hasil:</strong> <span id="success_trap" class="font-weight-bold text-primary">0.00</span>%</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Template Baris Spesies Tikus -->
<template id="tmpl-spesies-tikus">
  <div class="spesies-tikus-item border rounded bg-white p-2 mb-2 shadow-sm">
    <div class="row align-items-center">
      <div class="col-md-6 form-group mb-1">
        <label class="small font-weight-bold text-dark mb-1">Nama Spesies</label>
        <select name="spesies_nama[]" class="form-control form-control-sm sel-spesies-tikus">
          <option value="">-- Pilih Spesies --</option>
          <option value="Rattus tanezumi">Rattus tanezumi</option>
          <option value="Rattus norvegicus">Rattus norvegicus</option>
          <option value="Bandicota indica">Bandicota indica</option>
          <option value="Mus musculus">Mus musculus</option>
          <option value="Rattus argentiventer">Rattus argentiventer</option>
          <option value="Rattus exulans">Rattus exulans</option>
          <option value="Rattus tiomanicus">Rattus tiomanicus</option>
          <option value="Lain-lain">Lain-lain</option>
        </select>
        <input type="text" name="spesies_custom[]" class="form-control form-control-sm mt-1 txt-spesies-custom" placeholder="Ketik nama spesies lainnya..." style="display: none;">
      </div>
      <div class="col-md-4 form-group mb-1">
        <label class="small font-weight-bold text-dark mb-1">Jumlah Tertangkap</label>
        <div class="input-group input-group-sm">
          <input type="number" min="0" name="spesies_jumlah[]" class="form-control form-control-sm input-jml-spesies" value="0">
          <div class="input-group-append">
            <span class="input-group-text">ekor</span>
          </div>
        </div>
      </div>
      <div class="col-md-2 text-right mt-3 mb-1">
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-spesies-tikus" title="Hapus baris ini"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  </div>
</template>

<script>
  if ($.fn.DataTable.isDataTable('#table-tikus')) {
    $('#table-tikus').DataTable().destroy();
  }
  $('#table-tikus').DataTable({
    responsive: true,
    pageLength: 10,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      paginate: {
        previous: "Sebelumnya",
        next: "Berikutnya"
      }
    }
  });

  const standardSpesiesTikusList = [
    "Rattus tanezumi",
    "Rattus norvegicus",
    "Bandicota indica",
    "Mus musculus",
    "Rattus argentiventer",
    "Rattus exulans",
    "Rattus tiomanicus"
  ];

  // Fungsi Tambah Baris Spesies
  window.createSpesiesTikusRow = function(spName = '', customName = '', jumlah = 0) {
    let template = document.getElementById('tmpl-spesies-tikus');
    if (!template) return;
    let clone = document.importNode(template.content, true);
    let $item = $(clone.firstElementChild);

    if (spName) {
      if (standardSpesiesTikusList.includes(spName)) {
        $item.find('.sel-spesies-tikus').val(spName);
        $item.find('.txt-spesies-custom').hide().val('');
      } else {
        $item.find('.sel-spesies-tikus').val('Lain-lain');
        $item.find('.txt-spesies-custom').show().val(spName);
      }
    } else if (customName) {
      $item.find('.sel-spesies-tikus').val('Lain-lain');
      $item.find('.txt-spesies-custom').show().val(customName);
    }

    $item.find('.input-jml-spesies').val(jumlah);
    $('#spesies-tikus-container').append($item);
    window.calcTikusTotals();
  };

  // Reset container ke 1 baris kosong
  window.resetSpesiesTikusContainer = function() {
    $('#spesies-tikus-container').empty();
    window.createSpesiesTikusRow('', '', 0);
  };

  // Populate data saat edit
  window.populateSpesiesTikus = function(spesiesData, totalTertangkap) {
    $('#spesies-tikus-container').empty();
    if (!spesiesData) {
      window.createSpesiesTikusRow('', '', totalTertangkap || 0);
      return;
    }

    let parsed = null;
    try {
      if (typeof spesiesData === 'object') {
        parsed = spesiesData;
      } else if (typeof spesiesData === 'string' && (spesiesData.startsWith('{') || spesiesData.startsWith('['))) {
        parsed = JSON.parse(spesiesData);
      }
    } catch (e) {
      parsed = null;
    }

    if (parsed && typeof parsed === 'object' && Object.keys(parsed).length > 0) {
      for (let sp in parsed) {
        window.createSpesiesTikusRow(sp, '', parsed[sp]);
      }
    } else {
      // Plain string legacy format
      window.createSpesiesTikusRow(spesiesData, '', totalTertangkap || 0);
    }
  };

  // Hitung total dan success trap
  window.calcTikusTotals = function() {
    let sumSpesies = 0;
    let hasCount = false;
    $('.input-jml-spesies').each(function() {
      let val = parseFloat($(this).val()) || 0;
      if (val > 0) hasCount = true;
      sumSpesies += val;
    });

    $('#sum_spesies_tertangkap').text(sumSpesies);
    if (hasCount) {
      $('#jml_tikus_tertangkap').val(sumSpesies);
    }

    let dipasang = parseFloat($('#jml_perangkap_dipasang').val()) || 0;
    let tertangkap = parseFloat($('#jml_tikus_tertangkap').val()) || 0;
    let success = dipasang > 0 ? (tertangkap / dipasang) * 100 : 0;
    $('#success_trap').text(success.toFixed(2));
  };

  // Event Listeners Dinamis
  $(document).off('click', '.btn-tambah-spesies-tikus').on('click', '.btn-tambah-spesies-tikus', function() {
    window.createSpesiesTikusRow('', '', 0);
  });

  $(document).off('click', '.btn-remove-spesies-tikus').on('click', '.btn-remove-spesies-tikus', function() {
    if ($('.spesies-tikus-item').length > 1) {
      $(this).closest('.spesies-tikus-item').remove();
    } else {
      // Jika tinggal 1 baris, cukup reset nilainya
      let $item = $(this).closest('.spesies-tikus-item');
      $item.find('.sel-spesies-tikus').val('');
      $item.find('.txt-spesies-custom').hide().val('');
      $item.find('.input-jml-spesies').val(0);
    }
    window.calcTikusTotals();
  });

  $(document).off('change', '.sel-spesies-tikus').on('change', '.sel-spesies-tikus', function() {
    let $item = $(this).closest('.spesies-tikus-item');
    let $custom = $item.find('.txt-spesies-custom');
    if ($(this).val() === 'Lain-lain') {
      $custom.slideDown().focus();
    } else {
      $custom.slideUp().val('');
    }
  });

  $(document).off('input', '.input-jml-spesies, .tikus-calc').on('input', '.input-jml-spesies, .tikus-calc', function() {
    window.calcTikusTotals();
  });

  // Init pertama kali jika kontainer masih kosong
  if ($('#spesies-tikus-container').children().length === 0) {
    window.resetSpesiesTikusContainer();
  }
</script>
