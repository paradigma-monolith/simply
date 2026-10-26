<table class="table table-bordered table-striped table-sm text-center" id="table-lalat">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Wilayah Kerja</th>
      <th>Lokasi</th>
      <th>Temp (oC)</th>
      <th>Kelembapan (%)</th>
      <th>Rata-Rata Kepadatan</th>
      <th>Status</th>
      <th>Hasil Pengendalian</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($lalat as $row): 
      $rata = (float) ($row['rata_rata'] ?? 0);
      $detail = !empty($row['detail_lokasi']) ? json_decode($row['detail_lokasi'], true) : null;
      $hasMultiple = is_array($detail) && count($detail) > 1;
      
      $anyTms = false;
      if ($hasMultiple) {
        foreach ($detail as $d) {
          if (($d['rata_rata'] ?? 0) >= 2) {
            $anyTms = true;
            break;
          }
        }
      } else {
        $anyTms = ($rata >= 2);
      }
      
      $status = ($rata < 2) 
        ? '<span class="badge badge-success">MS</span>' 
        : '<span class="badge badge-danger">TMS</span>';

      $pengendalian = !empty($row['pengendalian']) ? json_decode($row['pengendalian'], true) : null;
      $hasilPengendalian = [];
      if (!empty($pengendalian) && is_array($pengendalian)) {
        $tglPengendalian = !empty($pengendalian['tanggal']) 
          ? $pengendalian['tanggal'] 
          : (!empty($row['tanggal']) ? $row['tanggal'] : null);
        if (!empty($tglPengendalian)) {
          $hasilPengendalian[] = '<div class="text-dark font-weight-bold mb-1"><i class="far fa-calendar-alt text-primary mr-1"></i>Tanggal: <span class="text-primary">' . date('d/m/Y', strtotime($tglPengendalian)) . '</span></div>';
        }
        $metodeList = [];
        if (!empty($pengendalian['spraying'])) {
          $str = 'Spraying';
          if (!empty($pengendalian['spraying_ml'])) {
            $str .= ' (' . $pengendalian['spraying_ml'] . ' ml)';
          }
          $metodeList[] = $str;
        }
        if (!empty($pengendalian['granule'])) {
          $str = 'Granule Distribution';
          if (!empty($pengendalian['granule_gram'])) {
            $str .= ' (' . $pengendalian['granule_gram'] . ' gram)';
          }
          $metodeList[] = $str;
        }

        if (!empty($metodeList)) {
          $hasilPengendalian[] = '<div><span class="badge badge-primary mr-1"><i class="fas fa-spray-can mr-1"></i>Metode:</span> ' . implode('; ', $metodeList) . '</div>';
        }
        if (!empty($pengendalian['lokasi'])) {
          $hasilPengendalian[] = '<div class="text-muted"><i class="fas fa-map-marker-alt mr-1 text-danger"></i><b>Lokasi:</b> ' . esc($pengendalian['lokasi']) . '</div>';
        }
      }
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $row['tanggal']; ?></td>
      <td><?= $row['wilker']; ?></td>
      <td class="<?= $hasMultiple ? 'text-left' : ''; ?>">
        <?php if($hasMultiple): ?>
          <div class="small">
            <?php foreach($detail as $d): 
              $dRata = (float) ($d['rata_rata'] ?? 0);
              $dBadge = ($dRata < 2) ? 'badge-success' : 'badge-danger';
              $dStatus = ($dRata < 2) ? 'MS' : 'TMS';
            ?>
              <div class="mb-1 d-flex justify-content-between align-items-center border-bottom pb-1">
                <span class="font-weight-bold mr-2"><?= esc($d['lokasi'] ?? ''); ?>:</span>
                <span>
                  <span class="mr-1"><?= number_format($dRata, 2); ?></span>
                  <span class="badge <?= $dBadge; ?>"><?= $dStatus; ?></span>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <?= esc($row['lokasi']); ?>
        <?php endif; ?>
      </td>
      <td><?= $row['temperatur']; ?></td>
      <td><?= $row['kelembapan']; ?></td>
      <td>
        <strong><?= number_format($row['rata_rata'], 2); ?></strong>
        <?php if($hasMultiple): ?>
          <br><small class="text-muted">(Gabungan)</small>
        <?php endif; ?>
      </td>
      <td><?= $status; ?></td>
      <td class="text-left small">
        <?php if (!empty($hasilPengendalian)): ?>
          <?= implode('', $hasilPengendalian); ?>
        <?php elseif ($row['rata_rata'] >= 2 || $anyTms): ?>
          <span class="badge badge-warning text-dark"><i class="fas fa-exclamation-triangle mr-1"></i>Belum dikendalikan</span>
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-lalat" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <?php if($row['rata_rata'] >= 2 || $anyTms): ?>
        <button class="btn btn-sm btn-warning btn-pengendalian-lalat" data-id="<?= $row['id_lalat']; ?>" data-tanggal="<?= $row['tanggal']; ?>" data-pengendalian='<?= htmlspecialchars($row['pengendalian'] ?? '', ENT_QUOTES, 'UTF-8'); ?>' title="Pengendalian"><i class="fas fa-shield-virus"></i></button>
        <?php endif; ?>
        <a href="/prl/sivera_lalat/pdf/<?= $row['id_lalat']; ?>" target="_blank" class="btn btn-sm btn-danger" title="Download Form PDF (Laporan Pengendalian)"><i class="fas fa-file-pdf"></i></a>
        <button class="btn btn-sm btn-danger btn-hapus-lalat" data-id="<?= $row['id_lalat']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah/Edit Lalat -->
<div class="modal fade" id="modal-lalat" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-lalat">
        <input type="hidden" name="id_lalat" id="id_lalat_form">
        <div class="modal-header bg-success" id="modal-lalat-header">
          <h5 class="modal-title" id="modal-lalat-title">Tambah Data Lalat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Wilayah Kerja</label>
              <select name="id_wilayah_kerja" class="form-control" required>
                <option value="">-- Pilih Wilayah Kerja --</option>
                <?php foreach($wilayah_kerja as $wk): ?>
                <option value="<?= $wk['id_unit_kerja']; ?>"><?= $wk['unit_kerja']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Musim</label>
              <select name="musim" class="form-control">
                <option value="Hujan">Hujan</option>
                <option value="Kemarau">Kemarau</option>
              </select>
            </div>
          </div>
          <hr>
          <h6>Lingkungan Fisik</h6>
          <div class="row">
            <div class="col-md-4 form-group">
              <label>Temperatur (oC)</label>
              <input type="number" step="0.1" name="temperatur" class="form-control">
            </div>
            <div class="col-md-4 form-group">
              <label>Kelembapan (%)</label>
              <input type="number" step="0.1" name="kelembapan" class="form-control">
            </div>
            <div class="col-md-4 form-group">
              <label>Kecepatan Angin (m/s)</label>
              <input type="number" step="0.1" name="kecepatan_angin" class="form-control">
            </div>
          </div>
          <hr>
          <!-- Template lokasi item untuk cloning yang aman dan konsisten -->
          <template id="template-lokasi-item">
            <div class="lokasi-item card mb-3 border shadow-none bg-light">
              <div class="card-header py-2 d-flex justify-content-between align-items-center bg-white border-bottom">
                <strong class="lokasi-header-title text-primary"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi 1</strong>
                <button type="button" class="btn btn-outline-danger btn-xs btn-remove-lokasi" style="display:none;"><i class="fas fa-trash"></i> Hapus Lokasi</button>
              </div>
              <div class="card-body p-3">
                <div class="form-group mb-2">
                  <label class="mb-1 font-weight-bold">Nama Lokasi</label>
                  <input type="text" name="lokasi[]" class="form-control form-control-sm" placeholder="Contoh: Dapur, TPS, Kantin..." required>
                </div>
                <h6 class="font-weight-bold small text-muted mb-2">Pengamatan (Jumlah Lalat per 30 Detik)</h6>
                <div class="row">
                  <?php for($i=1; $i<=10; $i++): ?>
                  <div class="col-md-2 col-4 form-group mb-2">
                    <label class="small mb-1">T-<?= $i; ?></label>
                    <input type="number" min="0" name="t<?= $i; ?>[]" class="form-control form-control-sm t_input" value="0">
                  </div>
                  <?php endfor; ?>
                </div>
                <div class="small mb-2">
                  <strong>Total Pengamatan:</strong> <span class="total_pengamatan badge badge-secondary">0</span>
                </div>
                <hr class="my-2">
                <h6 class="font-weight-bold small text-muted mb-2">Hasil Pengamatan 5 Titik Tertinggi</h6>
                <div class="row">
                  <?php for($i=1; $i<=5; $i++): ?>
                  <div class="col-md-2 col-4 form-group mb-2">
                    <label class="small mb-1">Titik <?= $i; ?></label>
                    <input type="number" min="0" name="titik_<?= $i; ?>[]" class="form-control form-control-sm titik_input" value="0" readonly>
                  </div>
                  <?php endfor; ?>
                </div>
                <div class="small mt-2 p-2 bg-white rounded border">
                  <strong>Total 5 Tertinggi (N):</strong> <span class="total_top5">0</span> &nbsp;|&nbsp;
                  <strong>Rata-Rata:</strong> <span class="rata_lalat font-weight-bold">0.00</span> &nbsp;|&nbsp;
                  <strong>Status:</strong> <span class="status_lalat"><span class="badge badge-success">MS</span></span>
                </div>
              </div>
            </div>
          </template>

          <div id="dynamic-lokasi-container">
            <div class="lokasi-item card mb-3 border shadow-none bg-light">
              <div class="card-header py-2 d-flex justify-content-between align-items-center bg-white border-bottom">
                <strong class="lokasi-header-title text-primary"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi 1</strong>
                <button type="button" class="btn btn-outline-danger btn-xs btn-remove-lokasi" style="display:none;"><i class="fas fa-trash"></i> Hapus Lokasi</button>
              </div>
              <div class="card-body p-3">
                <div class="form-group mb-2">
                  <label class="mb-1 font-weight-bold">Nama Lokasi</label>
                  <input type="text" name="lokasi[]" class="form-control form-control-sm" placeholder="Contoh: Dapur, TPS, Kantin..." required>
                </div>
                <h6 class="font-weight-bold small text-muted mb-2">Pengamatan (Jumlah Lalat per 30 Detik)</h6>
                <div class="row">
                  <?php for($i=1; $i<=10; $i++): ?>
                  <div class="col-md-2 col-4 form-group mb-2">
                    <label class="small mb-1">T-<?= $i; ?></label>
                    <input type="number" min="0" name="t<?= $i; ?>[]" class="form-control form-control-sm t_input" value="0">
                  </div>
                  <?php endfor; ?>
                </div>
                <div class="small mb-2">
                  <strong>Total Pengamatan:</strong> <span class="total_pengamatan badge badge-secondary">0</span>
                </div>
                <hr class="my-2">
                <h6 class="font-weight-bold small text-muted mb-2">Hasil Pengamatan 5 Titik Tertinggi</h6>
                <div class="row">
                  <?php for($i=1; $i<=5; $i++): ?>
                  <div class="col-md-2 col-4 form-group mb-2">
                    <label class="small mb-1">Titik <?= $i; ?></label>
                    <input type="number" min="0" name="titik_<?= $i; ?>[]" class="form-control form-control-sm titik_input" value="0" readonly>
                  </div>
                  <?php endfor; ?>
                </div>
                <div class="small mt-2 p-2 bg-white rounded border">
                  <strong>Total 5 Tertinggi (N):</strong> <span class="total_top5">0</span> &nbsp;|&nbsp;
                  <strong>Rata-Rata:</strong> <span class="rata_lalat font-weight-bold">0.00</span> &nbsp;|&nbsp;
                  <strong>Status:</strong> <span class="status_lalat"><span class="badge badge-success">MS</span></span>
                </div>
              </div>
            </div>
          </div>
          <div id="gabungan-lokasi-info" class="alert alert-info py-2 px-3 mt-2" style="display: none;">
            <strong>Ringkasan Gabungan Seluruh Lokasi:</strong><br>
            <span>Rata-Rata Kepadatan Keseluruhan: <b id="rata_gabungan_lalat">0.00</b></span> &nbsp;|&nbsp; 
            <span>Status Keseluruhan: <span id="status_gabungan_lalat"><span class="badge badge-success">MS</span></span></span>
          </div>

          <div class="mb-3">
             <button type="button" class="btn btn-primary btn-sm btn-tambah-lokasi"><i class="fas fa-plus"></i> Tambah Lokasi</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Pengendalian Lalat -->
<div class="modal fade" id="modal-pengendalian-lalat" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form-pengendalian-lalat">
        <input type="hidden" name="id_lalat" id="id_lalat_pengendalian">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Pengendalian Lalat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-bold"><i class="far fa-calendar-alt text-primary mr-1"></i> Tanggal Pengendalian</label>
              <input type="date" name="pengendalian[tanggal]" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-bold"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Lokasi Pengendalian</label>
              <input type="text" name="pengendalian[lokasi]" class="form-control" placeholder="Contoh: TPS, Dapur...">
            </div>
          </div>
          <div class="form-group">
            <label class="font-weight-bold"><i class="fas fa-tasks text-info mr-1"></i> Persiapan</label>
            <input type="text" name="pengendalian[persiapan]" class="form-control" placeholder="Catatan persiapan kegiatan (opsional)">
          </div>
          <h6>Metode yang digunakan:</h6>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="lalat_spraying" name="pengendalian[spraying]" value="1">
              <label class="custom-control-label" for="lalat_spraying">Spraying</label>
            </div>
            <div class="ml-4 mt-2">
              <label>Jumlah ml insektisida yang digunakan</label>
              <input type="number" step="0.1" name="pengendalian[spraying_ml]" class="form-control form-control-sm" style="width: 200px;">
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="lalat_granule" name="pengendalian[granule]" value="1">
              <label class="custom-control-label" for="lalat_granule">Granule Distribution</label>
            </div>
            <div class="ml-4 mt-2">
              <label>Jumlah gram insektisida yang digunakan</label>
              <input type="number" step="0.1" name="pengendalian[granule_gram]" class="form-control form-control-sm" style="width: 200px;">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-warning">Simpan Pengendalian</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  if ($.fn.DataTable.isDataTable('#table-lalat')) {
    $('#table-lalat').DataTable().destroy();
  }
  $('#table-lalat').DataTable({
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
  
  function getCleanLokasiItem() {
    let tmpl = document.getElementById('template-lokasi-item');
    let newItem;
    if (tmpl && tmpl.content && tmpl.content.firstElementChild) {
      newItem = $(tmpl.content.firstElementChild).clone();
    } else {
      newItem = $('.lokasi-item').first().clone();
    }
    newItem.find('input[type="text"]').val('');
    newItem.find('input[type="number"]').val('0');
    newItem.find('.total_pengamatan').text('0');
    newItem.find('.total_top5').text('0');
    newItem.find('.rata_lalat').text('0.00');
    newItem.find('.status_lalat').html('<span class="badge badge-success">MS</span>');
    newItem.find('.btn-remove-lokasi').hide();
    return newItem;
  }
  window.getCleanLokasiItem = getCleanLokasiItem;

  function updateLokasiIndexes() {
    let items = $('#dynamic-lokasi-container .lokasi-item');
    items.each(function(index) {
      $(this).find('.lokasi-header-title').html('<i class="fas fa-map-marker-alt mr-1"></i> Lokasi ' + (index + 1));
      if (items.length <= 1) {
        $(this).find('.btn-remove-lokasi').hide();
      } else {
        $(this).find('.btn-remove-lokasi').show();
      }
    });
  }
  window.updateLokasiIndexes = updateLokasiIndexes;

  function hitungGabunganLalat() {
    let items = $('#dynamic-lokasi-container .lokasi-item');
    if (items.length > 1) {
      let totalRata = 0;
      let count = 0;
      items.each(function() {
        let r = parseFloat($(this).find('.rata_lalat').text()) || 0;
        totalRata += r;
        count++;
      });
      let avg = count > 0 ? (totalRata / count) : 0;
      let statusHtml = avg < 2 
        ? '<span class="badge badge-success">MS (Memenuhi Syarat)</span>' 
        : '<span class="badge badge-danger">TMS (Tidak Memenuhi Syarat)</span>';
      $('#rata_gabungan_lalat').text(avg.toFixed(2));
      $('#status_gabungan_lalat').html(statusHtml);
      $('#gabungan-lokasi-info').slideDown();
    } else {
      $('#gabungan-lokasi-info').slideUp();
    }
  }
  window.hitungGabunganLalat = hitungGabunganLalat;

  // Real-time calculation per lokasi
  $(document).on('input', '.t_input', function() {
    let container = $(this).closest('.lokasi-item');
    let values = [];
    let total_pengamatan = 0;
    
    container.find('.t_input').each(function() {
      let val = parseFloat($(this).val()) || 0;
      values.push(val);
      total_pengamatan += val;
    });
    
    container.find('.total_pengamatan').text(total_pengamatan);

    // Sort descending for top 5
    values.sort(function(a, b) { return b - a; });
    
    let sum_top5 = 0;
    let titik_inputs = container.find('.titik_input');
    for(let i=0; i<5; i++) {
      let val = values[i] !== undefined ? values[i] : 0;
      $(titik_inputs[i]).val(val);
      sum_top5 += val;
    }
    
    container.find('.total_top5').text(sum_top5);
    let rata = sum_top5 / 5;
    container.find('.rata_lalat').text(rata.toFixed(2));
    let statusBadge = (rata < 2) 
      ? '<span class="badge badge-success">MS</span>' 
      : '<span class="badge badge-danger">TMS</span>';
    container.find('.status_lalat').html(statusBadge);

    hitungGabunganLalat();
  });

  // Tambah Lokasi
  $(document).on('click', '.btn-tambah-lokasi', function() {
    let newItem = (typeof window.getCleanLokasiItem === 'function') 
      ? window.getCleanLokasiItem() 
      : $('.lokasi-item').first().clone();
    
    $('#dynamic-lokasi-container').append(newItem);
    updateLokasiIndexes();
    newItem.find('.t_input').first().trigger('input');
    hitungGabunganLalat();
  });

  // Hapus Lokasi
  $(document).on('click', '.btn-remove-lokasi', function() {
    $(this).closest('.lokasi-item').remove();
    updateLokasiIndexes();
    hitungGabunganLalat();
  });
</script>
