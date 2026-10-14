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
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($lalat as $row): 
      $rata = (float) ($row['rata_rata'] ?? 0);
      $status = ($rata < 2) 
        ? '<span class="badge badge-success">MS</span>' 
        : '<span class="badge badge-danger">TMS</span>';
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $row['tanggal']; ?></td>
      <td><?= $row['wilker']; ?></td>
      <td><?= $row['lokasi']; ?></td>
      <td><?= $row['temperatur']; ?></td>
      <td><?= $row['kelembapan']; ?></td>
      <td><?= number_format($row['rata_rata'], 2); ?></td>
      <td><?= $status; ?></td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-lalat" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <?php if($row['rata_rata'] >= 2): ?>
        <button class="btn btn-sm btn-warning btn-pengendalian-lalat" data-id="<?= $row['id_lalat']; ?>" data-pengendalian='<?= htmlspecialchars($row['pengendalian'] ?? '', ENT_QUOTES, 'UTF-8'); ?>' title="Pengendalian"><i class="fas fa-shield-virus"></i></button>
        <?php endif; ?>
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
          <div id="dynamic-lokasi-container">
            <div class="lokasi-item">
              <div class="row">
                <div class="col-md-10 form-group">
                  <label>Lokasi</label>
                  <input type="text" name="lokasi[]" class="form-control" required>
                </div>
                <div class="col-md-2 form-group">
                  <label>&nbsp;</label><br>
                  <button type="button" class="btn btn-danger btn-remove-lokasi w-100" style="display:none;"><i class="fas fa-trash"></i> Hapus</button>
                </div>
              </div>
              <h6>Pengamatan (Jumlah Lalat per 30 Detik)</h6>
              <div class="row">
                <?php for($i=1; $i<=10; $i++): ?>
                <div class="col-md-2 form-group">
                  <label>T-<?= $i; ?></label>
                  <input type="number" name="t<?= $i; ?>[]" class="form-control t_input" value="0">
                </div>
                <?php endfor; ?>
              </div>
              <div class="mt-2">
                <strong>Total Pengamatan:</strong> <span class="total_pengamatan">0</span>
              </div>
              <hr>
              <h6>Hasil Pengamatan 5 Titik Tertinggi</h6>
              <div class="row">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="col-md-2 form-group">
                  <label>Titik <?= $i; ?></label>
                  <input type="number" name="titik_<?= $i; ?>[]" class="form-control titik_input" value="0" readonly>
                </div>
                <?php endfor; ?>
              </div>
              <div class="mt-2">
                <strong>Total 5 Tertinggi (N):</strong> <span class="total_top5">0</span> <br>
                <strong>Rata-Rata Kepadatan Lalat:</strong> <span class="rata_lalat">0</span>
              </div>
              <hr style="border-top: 2px dashed #ccc;">
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
          <div class="form-group">
            <label>Lokasi Pengendalian</label>
            <input type="text" name="pengendalian[lokasi]" class="form-control">
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
        ? '<span class="badge badge-success">MS</span>' 
        : '<span class="badge badge-danger">TMS</span>';
      $('#rata_gabungan_lalat').text(avg.toFixed(2));
      $('#status_gabungan_lalat').html(statusHtml);
      $('#gabungan-lokasi-info').slideDown();
    } else {
      $('#gabungan-lokasi-info').slideUp();
    }
  }

  // Real-time calculation
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

    // Sort descending
    values.sort(function(a, b) { return b - a; });
    
    let sum_top5 = 0;
    let titik_inputs = container.find('.titik_input');
    for(let i=0; i<5; i++) {
      $(titik_inputs[i]).val(values[i]);
      sum_top5 += values[i];
    }
    
    container.find('.total_top5').text(sum_top5);
    let rata = sum_top5 / 5;
    container.find('.rata_lalat').text(rata.toFixed(2));

    hitungGabunganLalat();
  });

  // Tambah Lokasi
  $(document).on('click', '.btn-tambah-lokasi', function() {
    let firstItem = $('.lokasi-item').first();
    let newItem = firstItem.clone();
    
    // Reset values in clone
    newItem.find('input[type="text"]').val('');
    newItem.find('input[type="number"]').val('0');
    newItem.find('.total_pengamatan').text('0');
    newItem.find('.total_top5').text('0');
    newItem.find('.rata_lalat').text('0');
    newItem.find('.btn-remove-lokasi').show();
    
    $('#dynamic-lokasi-container').append(newItem);
    hitungGabunganLalat();
  });

  // Hapus Lokasi
  $(document).on('click', '.btn-remove-lokasi', function() {
    $(this).closest('.lokasi-item').remove();
    hitungGabunganLalat();
  });
</script>
