<table class="table table-bordered table-striped table-sm text-center" id="table-kecoa">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Wilayah Kerja</th>
      <th>Nama Ruangan</th>
      <th>Perangkap Terpasang</th>
      <th>Kecoa Tertangkap</th>
      <th>Indeks Populasi</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($kecoa as $row): 
      $status = $row['rata_rata'] < 2 ? '<span class="badge badge-success">Memenuhi Syarat</span>' : '<span class="badge badge-danger">Tidak Memenuhi Syarat</span>';
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $row['tanggal']; ?></td>
      <td><?= $row['wilker']; ?></td>
      <td><?= $row['nama_ruangan']; ?></td>
      <td><?= $row['perangkap_terpasang']; ?></td>
      <td><?= $row['jml_kecoa_tertangkap']; ?></td>
      <td><?= number_format($row['rata_rata'], 2); ?></td>
      <td><?= $status; ?></td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-kecoa" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <?php if($row['rata_rata'] >= 2): ?>
        <button class="btn btn-sm btn-warning btn-pengendalian-kecoa" data-id="<?= $row['id_kecoa']; ?>" data-pengendalian='<?= htmlspecialchars($row['pengendalian'] ?? '', ENT_QUOTES, 'UTF-8'); ?>' title="Pengendalian"><i class="fas fa-shield-virus"></i></button>
        <?php endif; ?>
        <button class="btn btn-sm btn-danger btn-hapus-kecoa" data-id="<?= $row['id_kecoa']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah/Edit Kecoa -->
<div class="modal fade" id="modal-kecoa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-kecoa">
        <input type="hidden" name="id_kecoa" id="id_kecoa_form">
        <div class="modal-header bg-success" id="modal-kecoa-header">
          <h5 class="modal-title" id="modal-kecoa-title">Tambah Data Kecoa</h5>
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
              <label>Lokasi</label>
              <input type="text" name="lokasi" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Nama Ruangan</label>
              <input type="text" name="nama_ruangan" class="form-control">
            </div>
          </div>
          <hr>
          <h6>Pengamatan</h6>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Perangkap Terpasang</label>
              <input type="number" name="perangkap_terpasang" id="perangkap_terpasang" class="form-control" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Jumlah Kecoa Tertangkap Keseluruhan</label>
              <input type="number" name="jml_kecoa_tertangkap" id="jml_kecoa_tertangkap" class="form-control" value="0" readonly>
            </div>
          </div>
          <div class="mt-2">
            <strong>Rata-Rata Indeks Populasi Kecoa:</strong> <span id="rata_kecoa">0</span>
          </div>
          <hr>
          <h6>Spesies Tertangkap (Jumlah)</h6>
          <div class="row">
            <div class="col-md-3 form-group">
              <label>Periplatena americana</label>
              <input type="number" name="spesies[Periplatena americana]" id="sp_1" class="form-control sp_kecoa" value="0">
            </div>
            <div class="col-md-3 form-group">
              <label>Blatella germanica</label>
              <input type="number" name="spesies[Blatella germanica]" id="sp_2" class="form-control sp_kecoa" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Lain-Lain</label><br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <input type="checkbox" id="chk_lainnya_kecoa" value="1">
                  </div>
                </div>
                <input type="text" id="txt_nama_lainnya_kecoa" class="form-control" placeholder="Nama Spesies Lainnya..." disabled>
                <input type="number" id="txt_jml_lainnya_kecoa" class="form-control sp_kecoa" placeholder="Jumlah" value="0" disabled>
              </div>
            </div>
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

<!-- Modal Pengendalian Kecoa -->
<div class="modal fade" id="modal-pengendalian-kecoa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form-pengendalian-kecoa">
        <input type="hidden" name="id_kecoa" id="id_kecoa_pengendalian">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Pengendalian Kecoa</h5>
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
              <input type="checkbox" class="custom-control-input" id="kecoa_retrapping" name="pengendalian[retrapping]" value="1">
              <label class="custom-control-label" for="kecoa_retrapping">Retrrapping</label>
            </div>
            <div class="ml-4 mt-2">
              <label>Jumlah trap yang digunakan (buah)</label>
              <input type="number" name="pengendalian[retrapping_trap]" class="form-control form-control-sm" style="width: 200px;">
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="kecoa_gel_bait" name="pengendalian[gel_bait]" value="1">
              <label class="custom-control-label" for="kecoa_gel_bait">Gel Bait</label>
            </div>
            <div class="ml-4 mt-2">
              <label>Jumlah gram insektisida yang digunakan (gram)</label>
              <input type="number" step="0.1" name="pengendalian[gel_bait_gram]" class="form-control form-control-sm" style="width: 200px;">
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
  if ($.fn.DataTable.isDataTable('#table-kecoa')) {
    $('#table-kecoa').DataTable().destroy();
  }
  $('#table-kecoa').DataTable({
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
  
  // Real-time calculation
  $('.sp_kecoa').on('input', function() {
    let t1 = parseFloat($('#sp_1').val()) || 0;
    let t2 = parseFloat($('#sp_2').val()) || 0;
    let t_lain = parseFloat($('#txt_jml_lainnya_kecoa').val()) || 0;
    
    let total = t1 + t2 + t_lain;
    $('#jml_kecoa_tertangkap').val(total);
    calcKecoa();
  });
  
  $('#perangkap_terpasang').on('input', function() {
    calcKecoa();
  });
  
  function calcKecoa() {
    let total = parseFloat($('#jml_kecoa_tertangkap').val()) || 0;
    let terpasang = parseFloat($('#perangkap_terpasang').val()) || 0;
    let rata = terpasang > 0 ? (total / terpasang) : 0;
    $('#rata_kecoa').text(rata.toFixed(2));
  }

  $('#chk_lainnya_kecoa').on('change', function() {
    if($(this).is(':checked')) {
      $('#txt_nama_lainnya_kecoa').prop('disabled', false).attr('name', 'spesies_lain_nama');
      $('#txt_jml_lainnya_kecoa').prop('disabled', false);
    } else {
      $('#txt_nama_lainnya_kecoa').prop('disabled', true).removeAttr('name').val('');
      $('#txt_jml_lainnya_kecoa').prop('disabled', true).val('0').trigger('input');
    }
  });

  // Dynamic name for the lainnya number input based on text input
  $('#txt_nama_lainnya_kecoa').on('input', function() {
    let nama = $(this).val();
    if(nama.trim() !== '') {
      $('#txt_jml_lainnya_kecoa').attr('name', 'spesies[' + nama + ']');
    } else {
      $('#txt_jml_lainnya_kecoa').removeAttr('name');
    }
  });
</script>
