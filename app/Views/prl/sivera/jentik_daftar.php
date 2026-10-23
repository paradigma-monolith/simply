<table class="table table-bordered table-striped table-sm text-center" id="table-jentik">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Wilayah Kerja</th>
      <th>Lokasi</th>
      <th>Kondisi Musim</th>
      <th>Wilayah</th>
      <th>Indeks (HI / CI / BI)</th>
      <th>Status</th>
      <th>Hasil Pengendalian</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($jentik as $row): 
      $hi = $row['jml_rumah_diperiksa'] > 0 ? ($row['jml_rumah_positif'] / $row['jml_rumah_diperiksa']) * 100 : 0;
      $ci = $row['jml_kontainer_diperiksa'] > 0 ? ($row['jml_kontainer_positif'] / $row['jml_kontainer_diperiksa']) * 100 : 0;
      $bi = $row['jml_rumah_diperiksa'] > 0 ? ($row['jml_kontainer_positif'] / $row['jml_rumah_diperiksa']) * 100 : 0;
      $status = ($hi == 0 && $ci == 0 && $bi == 0) ? '<span class="badge badge-success">Memenuhi Syarat</span>' : '<span class="badge badge-danger">Tidak Memenuhi Syarat</span>';

      $pengendalian = !empty($row['pengendalian']) ? json_decode($row['pengendalian'], true) : null;
      $hasilPengendalian = [];
      if (!empty($pengendalian) && is_array($pengendalian)) {
        $tglPengendalian = !empty($pengendalian['tanggal']) 
          ? $pengendalian['tanggal'] 
          : (!empty($row['tanggal_selesai']) ? $row['tanggal_selesai'] : (!empty($row['tanggal_mulai']) ? $row['tanggal_mulai'] : null));
        if (!empty($tglPengendalian)) {
          $hasilPengendalian[] = '<div class="text-dark font-weight-bold mb-1"><i class="far fa-calendar-alt text-primary mr-1"></i>Tanggal: <span class="text-primary">' . date('d/m/Y', strtotime($tglPengendalian)) . '</span></div>';
        }
        // Pengendalian Jentik
        $metodeJentik = [];
        if (!empty($pengendalian['jentik_kimia'])) {
          $detailKimia = [];
          if (!empty($pengendalian['jentik_kimia_gram'])) $detailKimia[] = $pengendalian['jentik_kimia_gram'] . ' gr';
          if (!empty($pengendalian['jentik_kimia_sachet'])) $detailKimia[] = $pengendalian['jentik_kimia_sachet'] . ' sachet';
          $strKimia = 'Kimia (Larvasidasi)';
          if (!empty($detailKimia)) $strKimia .= ' (' . implode(', ', $detailKimia) . ')';
          $metodeJentik[] = $strKimia;
        }
        if (!empty($pengendalian['jentik_fisik'])) {
          $metodeJentik[] = 'Fisik (Membuang air)';
        }
        if (!empty($pengendalian['jentik_biologi'])) {
          $metodeJentik[] = 'Biologi (BTI/Ikan)';
        }
        if (!empty($metodeJentik)) {
          $hasilPengendalian[] = '<div class="mb-1"><span class="badge badge-primary mr-1"><i class="fas fa-water mr-1"></i>Jentik:</span> ' . implode('; ', $metodeJentik) . '</div>';
        }

        // Pengendalian Nyamuk
        $metodeNyamuk = [];
        if (!empty($pengendalian['nyamuk_fogging'])) {
          $detailFogging = [];
          if (!empty($pengendalian['nyamuk_fogging_mesin'])) $detailFogging[] = $pengendalian['nyamuk_fogging_mesin'] . ' mesin';
          if (!empty($pengendalian['nyamuk_fogging_ml'])) $detailFogging[] = $pengendalian['nyamuk_fogging_ml'] . ' ml';
          if (!empty($pengendalian['nyamuk_fogging_golongan'])) $detailFogging[] = esc($pengendalian['nyamuk_fogging_golongan']);
          if (!empty($pengendalian['nyamuk_fogging_bahan'])) $detailFogging[] = 'Bahan: ' . esc($pengendalian['nyamuk_fogging_bahan']);
          if (!empty($pengendalian['nyamuk_fogging_merk'])) $detailFogging[] = 'Merk: ' . esc($pengendalian['nyamuk_fogging_merk']);
          $strFogging = 'Fogging';
          if (!empty($detailFogging)) $strFogging .= ' (' . implode(', ', $detailFogging) . ')';
          $metodeNyamuk[] = $strFogging;
        }
        if (!empty($pengendalian['nyamuk_fisik'])) {
          $metodeNyamuk[] = 'Fisik (Raket/Trap)';
        }
        $txtNyamuk = !empty($metodeNyamuk) ? implode('; ', $metodeNyamuk) : '';
        if (!empty($pengendalian['nyamuk_lokasi'])) {
          $lokText = '<span class="text-secondary font-weight-bold">Lokasi:</span> ' . esc($pengendalian['nyamuk_lokasi']);
          $txtNyamuk = $txtNyamuk ? ($txtNyamuk . ' <span class="text-muted">|</span> ' . $lokText) : $lokText;
        }
        if (!empty($txtNyamuk)) {
          $hasilPengendalian[] = '<div><span class="badge badge-danger mr-1"><i class="fas fa-mosquito mr-1"></i>Nyamuk:</span> ' . $txtNyamuk . '</div>';
        }
      }
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $row['tanggal_mulai']; ?></td>
      <td><?= $row['tanggal_selesai']; ?></td>
      <td><?= $row['wilker']; ?></td>
      <td><?= $row['lokasi']; ?></td>
      <td><?= $row['kondisi_musim']; ?></td>
      <td><?= $row['wilayah']; ?></td>
      <td class="text-left small" style="white-space: nowrap;">
        <div title="House Index: (<?= $row['jml_rumah_positif']; ?> / <?= $row['jml_rumah_diperiksa']; ?>) * 100">
          <span class="text-muted font-weight-bold">HI:</span> <b><?= number_format($hi, 2); ?>%</b>
        </div>
        <div title="Container Index: (<?= $row['jml_kontainer_positif']; ?> / <?= $row['jml_kontainer_diperiksa']; ?>) * 100">
          <span class="text-muted font-weight-bold">CI:</span> <b><?= number_format($ci, 2); ?>%</b>
        </div>
        <div title="Breteau Index: (<?= $row['jml_kontainer_positif']; ?> / <?= $row['jml_rumah_diperiksa']; ?>) * 100">
          <span class="text-muted font-weight-bold">BI:</span> <b><?= number_format($bi, 2); ?>%</b>
        </div>
      </td>
      <td><?= $status; ?></td>
      <td class="text-left small">
        <?php if (!empty($hasilPengendalian)): ?>
          <?= implode('', $hasilPengendalian); ?>
        <?php elseif ($hi > 0 || $ci > 0 || $bi > 0): ?>
          <span class="badge badge-warning text-dark"><i class="fas fa-exclamation-triangle mr-1"></i>Belum dikendalikan</span>
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
      <td>
        <button class="btn btn-sm btn-info btn-edit-jentik" data-row='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' title="Edit"><i class="fas fa-edit"></i></button>
        <?php if($hi > 0 || $ci > 0 || $bi > 0): ?>
        <button class="btn btn-sm btn-warning btn-pengendalian-jentik" data-id="<?= $row['id_jentik']; ?>" data-tanggal="<?= !empty($row['tanggal_selesai']) ? $row['tanggal_selesai'] : $row['tanggal_mulai']; ?>" data-pengendalian='<?= htmlspecialchars($row['pengendalian'] ?? '', ENT_QUOTES, 'UTF-8'); ?>' title="Pengendalian"><i class="fas fa-shield-virus"></i></button>
        <?php endif; ?>
        <button class="btn btn-sm btn-danger btn-hapus-jentik" data-id="<?= $row['id_jentik']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah/Edit Jentik -->
<div class="modal fade" id="modal-jentik" tabindex="-1" role="dialog" aria-labelledby="modal-jentik-label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-jentik">
        <input type="hidden" name="id_jentik" id="id_jentik_form">
        <div class="modal-header bg-success" id="modal-jentik-header">
          <h5 class="modal-title" id="modal-jentik-title">Tambah Data Jentik</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
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
              <label>Bulan Pelaksanaan</label>
              <input type="month" name="bulan_pelaksanaan" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Kondisi Musim</label>
              <select name="kondisi_musim" class="form-control">
                <option value="Hujan">Hujan</option>
                <option value="Kemarau">Kemarau</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control">
            </div>
            <div class="col-md-12 form-group">
              <label>Wilayah (Perimeter/Buffer)</label>
              <select name="wilayah" class="form-control">
                <option value="Perimeter">Perimeter</option>
                <option value="Buffer">Buffer</option>
              </select>
            </div>
          </div>
          
          <hr>
          <h6>Hasil Pemeriksaan</h6>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Jumlah Rumah Diperiksa (A)</label>
              <input type="number" name="jml_rumah_diperiksa" id="jml_rumah_diperiksa" class="form-control" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Jumlah Kontainer Diperiksa (B)</label>
              <input type="number" name="jml_kontainer_diperiksa" id="jml_kontainer_diperiksa" class="form-control" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Jumlah Rumah Positif (C)</label>
              <input type="number" name="jml_rumah_positif" id="jml_rumah_positif" class="form-control" value="0">
            </div>
            <div class="col-md-6 form-group">
              <label>Jumlah Kontainer Positif (D)</label>
              <input type="number" name="jml_kontainer_positif" id="jml_kontainer_positif" class="form-control" value="0">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <strong>House Indeks (HI):</strong> <span id="hi_val">0%</span>
            </div>
            <div class="col-md-4">
              <strong>Container Indeks (CI):</strong> <span id="ci_val">0%</span>
            </div>
            <div class="col-md-4">
              <strong>Breteau Indeks (BI):</strong> <span id="bi_val">0%</span>
            </div>
          </div>
          
          <hr>
          <div class="form-group">
            <label>Jenis Kontainer Positif yang ditemukan</label><br>
            <input type="checkbox" name="jenis_kontainer[]" value="Ban Bekas"> Ban Bekas <br>
            <input type="checkbox" name="jenis_kontainer[]" value="Ember"> Ember <br>
            <input type="checkbox" name="jenis_kontainer[]" value="Bak Mandi"> Bak Mandi <br>
            <input type="checkbox" name="jenis_kontainer[]" value="Tatakan Dispenser"> Tatakan Dispenser Air Minum <br>
            <input type="checkbox" name="jenis_kontainer[]" value="Tanaman Penyimpan Air"> Tanaman Penyimpan Air <br>
            <input type="checkbox" id="chk_lainnya_jentik" value="Lainnya"> Lain-Lain 
            <input type="text" id="txt_lainnya_jentik" class="form-control form-control-sm d-inline-block ml-2" style="width: 200px; display: none;" placeholder="Sebutkan..." disabled> <br>
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

<!-- Modal Pengendalian Jentik -->
<div class="modal fade" id="modal-pengendalian-jentik" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-pengendalian-jentik">
        <input type="hidden" name="id_jentik" id="id_jentik_pengendalian">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Pengendalian Jentik & Nyamuk Aedes sp.</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label class="font-weight-bold"><i class="far fa-calendar-alt text-primary mr-1"></i> Tanggal Pengendalian</label>
            <input type="date" name="pengendalian[tanggal]" class="form-control" style="max-width: 250px;" required>
          </div>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="jentik-tab" data-toggle="tab" href="#tab-pengendalian-jentik" role="tab">Pengendalian Jentik</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="nyamuk-tab" data-toggle="tab" href="#tab-pengendalian-nyamuk" role="tab">Pengendalian Nyamuk</a>
            </li>
          </ul>
          <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="tab-pengendalian-jentik" role="tabpanel">
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="jentik_kimia" name="pengendalian[jentik_kimia]" value="1">
                  <label class="custom-control-label" for="jentik_kimia">Kimia (larvasidasi)</label>
                </div>
                <div class="ml-4 mt-2">
                  <label>Berat larvasida digunakan (gram)</label>
                  <input type="number" step="0.1" name="pengendalian[jentik_kimia_gram]" class="form-control form-control-sm mb-2" style="width: 200px;">
                  <label>Berapa sachet</label>
                  <input type="number" name="pengendalian[jentik_kimia_sachet]" class="form-control form-control-sm" style="width: 200px;">
                </div>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="jentik_fisik" name="pengendalian[jentik_fisik]" value="1">
                  <label class="custom-control-label" for="jentik_fisik">Fisik (membuang sumber air)</label>
                </div>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="jentik_biologi" name="pengendalian[jentik_biologi]" value="1">
                  <label class="custom-control-label" for="jentik_biologi">Biologi (BTI, ikan pemakan jentik)</label>
                </div>
              </div>
            </div>
            
            <div class="tab-pane fade" id="tab-pengendalian-nyamuk" role="tabpanel">
              <div class="form-group">
                <label>Lokasi Pengendalian</label>
                <input type="text" name="pengendalian[nyamuk_lokasi]" class="form-control">
              </div>
              <h6>Metode yang digunakan:</h6>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="nyamuk_fogging" name="pengendalian[nyamuk_fogging]" value="1">
                  <label class="custom-control-label" for="nyamuk_fogging">Fogging</label>
                </div>
                <div class="ml-4 mt-2">
                  <label>Jumlah mesin yang digunakan</label>
                  <input type="number" name="pengendalian[nyamuk_fogging_mesin]" class="form-control form-control-sm mb-2" style="width: 200px;">
                  <label>Jumlah insektisida (ml)</label>
                  <input type="number" step="0.1" name="pengendalian[nyamuk_fogging_ml]" class="form-control form-control-sm mb-2" style="width: 200px;">
                  <label>Golongan insektisida</label>
                  <select name="pengendalian[nyamuk_fogging_golongan]" class="form-control form-control-sm mb-2" style="width: 200px;">
                    <option value="">-- Pilih --</option>
                    <option value="Sintetik Piretroid">Sintetik Piretroid</option>
                    <option value="Organofosfat">Organofosfat</option>
                  </select>
                  <label>Bahan aktif yang digunakan</label>
                  <input type="text" name="pengendalian[nyamuk_fogging_bahan]" class="form-control form-control-sm mb-2" style="width: 200px;">
                  <label>Merk dagang yang digunakan</label>
                  <input type="text" name="pengendalian[nyamuk_fogging_merk]" class="form-control form-control-sm" style="width: 200px;">
                </div>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="nyamuk_fisik" name="pengendalian[nyamuk_fisik]" value="1">
                  <label class="custom-control-label" for="nyamuk_fisik">Metode Fisik (Raket nyamuk, Light trap)</label>
                </div>
              </div>
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
  if ($.fn.DataTable.isDataTable('#table-jentik')) {
    $('#table-jentik').DataTable().destroy();
  }
  $('#table-jentik').DataTable({
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
  $('#form-jentik input[type="number"]').on('input', function() {
    let a = parseFloat($('#jml_rumah_diperiksa').val()) || 0;
    let b = parseFloat($('#jml_kontainer_diperiksa').val()) || 0;
    let c = parseFloat($('#jml_rumah_positif').val()) || 0;
    let d = parseFloat($('#jml_kontainer_positif').val()) || 0;
    
    let hi = a > 0 ? (c / a * 100).toFixed(2) : 0;
    let ci = b > 0 ? (d / b * 100).toFixed(2) : 0;
    let bi = a > 0 ? (d / a * 100).toFixed(2) : 0;
    
    $('#hi_val').text(hi + '%');
    $('#ci_val').text(ci + '%');
    $('#bi_val').text(bi + '%');
  });

  $('#chk_lainnya_jentik').on('change', function() {
    if($(this).is(':checked')) {
      $('#txt_lainnya_jentik').show().prop('disabled', false).attr('name', 'jenis_kontainer[]');
    } else {
      $('#txt_lainnya_jentik').hide().prop('disabled', true).removeAttr('name').val('');
    }
  });
</script>
