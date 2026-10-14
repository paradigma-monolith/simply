<table class="table table-bordered table-striped table-sm text-center" id="table-pinjal">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Wilayah Kerja</th>
      <th>Lokasi</th>
      <th>Tikus Tertangkap</th>
      <th>Pinjal Umum</th>
      <th>Pinjal Khusus (X. cheopis)</th>
      <th>Indeks Umum</th>
      <th>Status Umum</th>
      <th>Indeks Khusus</th>
      <th>Status Khusus</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($pinjal as $row): 
      $iu = (float) ($row['indeks_pinjal_umum'] ?? 0);
      $ik = (float) ($row['indeks_pinjal_khusus'] ?? 0);
      $status_umum = ($iu < 2) 
        ? '<span class="badge badge-success" title="Standar Baku Mutu < 2">MS</span>' 
        : '<span class="badge badge-danger" title="Standar Baku Mutu < 2">TMS</span>';
      $status_khusus = ($ik < 1) 
        ? '<span class="badge badge-success" title="Standar Baku Mutu < 1">MS</span>' 
        : '<span class="badge badge-danger" title="Standar Baku Mutu < 1">TMS</span>';
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $row['tanggal']; ?></td>
      <td><?= $row['wilker'] ?? '-'; ?></td>
      <td><?= $row['lokasi'] ?? '-'; ?></td>
      <td><?= (int) ($row['jml_tikus_tertangkap'] ?? 0); ?></td>
      <td><?= (int) ($row['pinjal_umum'] ?? 0); ?></td>
      <td><?= (int) ($row['pinjal_khusus'] ?? 0); ?></td>
      <td><strong><?= number_format($iu, 2); ?></strong></td>
      <td><?= $status_umum; ?></td>
      <td><strong><?= number_format($ik, 2); ?></strong></td>
      <td><?= $status_khusus; ?></td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-pinjal" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-danger btn-hapus-pinjal" data-id="<?= $row['id_pinjal']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah/Edit Pinjal -->
<div class="modal fade" id="modal-pinjal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-pinjal">
        <input type="hidden" name="id_pinjal" id="id_pinjal_form">
        <div class="modal-header bg-success" id="modal-pinjal-header">
          <h5 class="modal-title font-weight-bold" id="modal-pinjal-title">Tambah Data Pinjal</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Row 1: Informasi Dasar -->
          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Tanggal <span class="text-danger">*</span></label>
              <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Wilayah Kerja <span class="text-danger">*</span></label>
              <select name="id_wilayah_kerja" class="form-control" required>
                <option value="">-- Pilih Wilayah Kerja --</option>
                <?php foreach($wilayah_kerja as $wk): ?>
                <option value="<?= $wk['id_unit_kerja']; ?>"><?= $wk['unit_kerja']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Lokasi</label>
              <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Dermaga / Gudang">
            </div>
          </div>

          <hr class="mt-1 mb-3">

          <!-- Row 2: Form Input Sederhana -->
          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Jumlah Tikus Tertangkap <span class="text-danger">*</span></label>
              <input type="number" min="0" name="jml_tikus_tertangkap" id="jml_tikus_tertangkap" class="form-control pinjal-calc" value="0" required>
              <small class="text-muted">Total tikus tertangkap yang diperiksa</small>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Pinjal Umum <span class="text-danger">*</span></label>
              <input type="number" min="0" name="pinjal_umum" id="pinjal_umum" class="form-control pinjal-calc" value="0" required>
              <small class="text-muted">Jumlah seluruh pinjal yang didapat</small>
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-bold">Pinjal Xenopsylla cheopis <span class="text-danger">*</span></label>
              <input type="number" min="0" name="pinjal_khusus" id="pinjal_khusus" class="form-control pinjal-calc" value="0" required>
              <small class="text-muted">Jumlah pinjal khusus (vektor pes)</small>
            </div>
          </div>

          <!-- Card Perhitungan & Standar Baku Mutu Sesuai Acuan -->
          <div class="card bg-light border mt-2 mb-0 shadow-sm">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2 font-weight-bold text-dark">
                <i class="fas fa-calculator text-primary mr-2"></i> Perhitungan Indeks & Standar Baku Mutu
              </div>
              <div class="row">
                <div class="col-md-6 mb-2 mb-md-0">
                  <div class="p-3 border rounded bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="font-weight-bold text-dark">Indeks Pinjal Umum</span>
                      <div>
                        <span id="calc_indeks_umum" class="font-weight-bold text-primary" style="font-size: 1.15rem;">0.00</span>
                        <span id="calc_status_umum" class="badge badge-success ml-1">MS</span>
                      </div>
                    </div>
                    <div class="small text-muted mb-1">
                      <i class="fas fa-divide mr-1 text-secondary"></i><strong>Rumus:</strong> Pinjal umum / Jumlah tikus tertangkap
                    </div>
                    <div class="small">
                      <i class="fas fa-shield-alt mr-1 text-info"></i><strong>Standar baku mutu:</strong> <code>&lt; 2</code> 
                      <span class="text-muted">(MS jika &lt; 2, TMS jika &ge; 2)</span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="p-3 border rounded bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="font-weight-bold text-dark">Indeks Pinjal Khusus</span>
                      <div>
                        <span id="calc_indeks_khusus" class="font-weight-bold text-primary" style="font-size: 1.15rem;">0.00</span>
                        <span id="calc_status_khusus" class="badge badge-success ml-1">MS</span>
                      </div>
                    </div>
                    <div class="small text-muted mb-1">
                      <i class="fas fa-divide mr-1 text-secondary"></i><strong>Rumus:</strong> Pinjal Xenopsylla cheopis / Jumlah tikus tertangkap
                    </div>
                    <div class="small">
                      <i class="fas fa-shield-alt mr-1 text-info"></i><strong>Standar baku mutu:</strong> <code>&lt; 1</code> 
                      <span class="text-muted">(MS jika &lt; 1, TMS jika &ge; 1)</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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

<script>
  if ($.fn.DataTable.isDataTable('#table-pinjal')) {
    $('#table-pinjal').DataTable().destroy();
  }
  $('#table-pinjal').DataTable({
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

  // Fungsi Kalkulasi Real-time Indeks Pinjal
  window.hitungIndeksPinjal = function() {
    let tikus = parseFloat($('#jml_tikus_tertangkap').val()) || 0;
    let pu = parseFloat($('#pinjal_umum').val()) || 0;
    let pk = parseFloat($('#pinjal_khusus').val()) || 0;

    let iu = tikus > 0 ? (pu / tikus) : 0;
    let ik = tikus > 0 ? (pk / tikus) : 0;

    $('#calc_indeks_umum').text(iu.toFixed(2));
    $('#calc_indeks_khusus').text(ik.toFixed(2));

    // Standar baku mutu: Indeks Pinjal Umum < 2
    if (iu < 2) {
      $('#calc_status_umum').text('MS').removeClass('badge-danger').addClass('badge-success');
    } else {
      $('#calc_status_umum').text('TMS').removeClass('badge-success').addClass('badge-danger');
    }

    // Standar baku mutu: Indeks Pinjal Khusus < 1
    if (ik < 1) {
      $('#calc_status_khusus').text('MS').removeClass('badge-danger').addClass('badge-success');
    } else {
      $('#calc_status_khusus').text('TMS').removeClass('badge-success').addClass('badge-danger');
    }
  };

  // Event listener input real-time
  $(document).off('input', '.pinjal-calc').on('input', '.pinjal-calc', window.hitungIndeksPinjal);
</script>
