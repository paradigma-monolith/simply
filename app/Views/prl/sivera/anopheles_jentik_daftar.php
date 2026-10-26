<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover table-sm text-center" id="table-anopheles-jentik">
    <thead class="bg-light">
      <tr>
        <th style="width: 40px;">No</th>
        <th>Hari / Tanggal</th>
        <th>Wilayah Kerja</th>
        <th>Lokasi</th>
        <th>Jenis Habitat</th>
        <th>Kondisi Habitat</th>
        <th>Jumlah Habitat</th>
        <th>Indeks Habitat</th>
        <th>Status</th>
        <th style="width: 100px;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $daftarHari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
      ];
      $no = 1; 
      foreach($anopheles_jentik as $row): 
        $tglStr = '-';
        if (!empty($row['tanggal'])) {
          $tglTimestamp = strtotime($row['tanggal']);
          $hariEng = date('l', $tglTimestamp);
          $hariIndo = $daftarHari[$hariEng] ?? $hariEng;
          $tglStr = $hariIndo . ' / ' . date('d M Y', $tglTimestamp);
        }

        $disurvei = (int)($row['jml_habitat_disurvei'] ?? 0);
        $positif = (int)($row['jml_habitat_positif'] ?? 0);
        $indeks = $disurvei > 0 ? round(($positif / $disurvei) * 100, 2) : (float)($row['indeks_habitat'] ?? 0);
        $isMs = ($indeks < 1.0);
        $statusBadge = $isMs 
          ? '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>MS (&lt; 1%)</span>' 
          : '<span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i>TMS (&ge; 1%)</span>';

        $jenisHabitList = !empty($row['jenis_habitat']) ? explode(',', $row['jenis_habitat']) : [];
        if (!empty($row['jenis_habitat_lainnya'])) {
          $jenisHabitList[] = 'Lainnya: ' . esc($row['jenis_habitat_lainnya']);
        }

        $airList = !empty($row['kondisi_air']) ? explode(',', $row['kondisi_air']) : [];
        $cahayaList = !empty($row['kondisi_pencahayaan']) ? explode(',', $row['kondisi_pencahayaan']) : [];
        $vegList = !empty($row['kondisi_vegetasi']) ? explode(',', $row['kondisi_vegetasi']) : [];
      ?>
      <tr>
        <td><?= $no++; ?></td>
        <td class="text-left font-weight-bold" style="white-space: nowrap;"><?= $tglStr; ?></td>
        <td><?= esc($row['wilker'] ?? '-'); ?></td>
        <td class="text-left"><?= esc($row['lokasi'] ?? '-'); ?></td>
        <td class="text-left" style="max-width: 220px;">
          <?php if (!empty($jenisHabitList)): ?>
            <?php foreach($jenisHabitList as $jh): ?>
              <span class="badge badge-info mb-1" style="font-size: 0.75rem;"><?= esc(trim($jh)); ?></span>
            <?php endforeach; ?>
          <?php else: ?>
            <span class="text-muted">-</span>
          <?php endif; ?>
        </td>
        <td class="text-left small" style="min-width: 180px;">
          <?php if (!empty($cahayaList)): ?>
            <div><i class="fas fa-sun text-warning mr-1"></i><b>Cahaya:</b> <?= esc(implode(', ', $cahayaList)); ?></div>
          <?php endif; ?>
          <?php if (!empty($vegList)): ?>
            <div><i class="fas fa-leaf text-success mr-1"></i><b>Vegetasi:</b> <?= esc(implode(', ', $vegList)); ?></div>
          <?php endif; ?>
          <?php if (!empty($airList)): ?>
            <div><i class="fas fa-water text-primary mr-1"></i><b>Air:</b> <?= esc(implode(', ', $airList)); ?></div>
          <?php endif; ?>
          <?php if (!empty($row['keterangan'])): ?>
            <div class="text-muted mt-1" style="font-style: italic;"><i class="fas fa-info-circle mr-1"></i><?= esc($row['keterangan']); ?></div>
          <?php endif; ?>
        </td>
        <td>
          <div class="small text-muted">Disurvei: <b class="text-dark"><?= $disurvei; ?></b></div>
          <div class="small">Positif: <b class="<?= $positif > 0 ? 'text-danger' : 'text-success'; ?>"><?= $positif; ?></b></div>
        </td>
        <td>
          <span class="font-weight-bold" style="font-size: 1.05rem;"><?= number_format($indeks, 2); ?>%</span>
          <div class="small text-muted" title="Rumus: (Positif / Disurvei) x 100%">(<?= $positif; ?>/<?= $disurvei; ?>)</div>
        </td>
        <td><?= $statusBadge; ?></td>
        <td>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-info btn-edit-anopheles-jentik" data-row='<?= json_encode($row); ?>' title="Edit Data">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger btn-hapus-anopheles-jentik" data-id="<?= $row['id_anopheles_jentik']; ?>" title="Hapus Data">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($anopheles_jentik)): ?>
      <tr>
        <td colspan="10" class="text-center py-4 text-muted">
          <i class="fas fa-inbox fa-3x mb-2 d-block text-gray-300"></i>
          Belum ada data Surveilans Jentik Anopheles yang sesuai dengan filter.
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah / Edit Jentik Anopheles -->
<div class="modal fade" id="modal-anopheles-jentik" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-anopheles-jentik">
        <input type="hidden" name="id_anopheles_jentik" id="id_anopheles_jentik_form">
        <div class="modal-header bg-success text-white" id="modal-anopheles-jentik-header">
          <h5 class="modal-title font-weight-bold" id="modal-anopheles-jentik-title">
            <i class="fas fa-bacteria mr-2"></i> Formulir Survei Jentik Anopheles
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">

          <!-- Informasi Umum -->
          <div class="card mb-3 border-0 shadow-sm" style="background: #f8fafc; border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-clipboard-list text-primary mr-1"></i> Informasi Survei</h6>
              <div class="row">
                <div class="col-md-4 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt text-primary mr-1"></i> Tanggal Survei</label>
                  <input type="date" name="tanggal" id="anopheles_jentik_tanggal" class="form-control" required>
                  <small id="anopheles_jentik_hari_label" class="text-primary font-weight-bold mt-1 d-block"></small>
                </div>
                <div class="col-md-4 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                  <select name="id_wilayah_kerja" id="anopheles_jentik_wilker" class="form-control" required>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php foreach($wilayah_kerja as $wk): ?>
                    <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-map-pin text-success mr-1"></i> Lokasi</label>
                  <input type="text" name="lokasi" id="anopheles_jentik_lokasi" class="form-control" placeholder="Contoh: PPI Karangsong Indramayu" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Jenis Habitat -->
          <div class="card mb-3 border-0 shadow-sm" style="background: #f8fafc; border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-2">
                <i class="fas fa-layer-group text-info mr-1"></i> Jenis Habitat <span class="badge badge-secondary ml-1 font-weight-normal">dapat dipilih lebih dari satu</span>
              </h6>
              <div class="row pt-2">
                <?php 
                $listHabitat = [
                  'Lagun', 'Tambak',
                  'Sawah', 'Rawa',
                  'Genangan', 'Kolam',
                  'Sungai', 'Saluran Air',
                  'Muara', 'Tambak Terbengkalai'
                ];
                foreach($listHabitat as $hb): ?>
                <div class="col-md-6 mb-2">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input chk-jenis-habitat" name="jenis_habitat[]" value="<?= $hb; ?>" id="hb_<?= strtolower(str_replace(' ', '_', $hb)); ?>">
                    <label class="custom-control-label font-weight-normal text-dark" for="hb_<?= strtolower(str_replace(' ', '_', $hb)); ?>"><?= $hb; ?></label>
                  </div>
                </div>
                <?php endforeach; ?>
                <div class="col-12 mt-2">
                  <div class="d-flex align-items-center">
                    <div class="custom-control custom-checkbox mr-2">
                      <input type="checkbox" class="custom-control-input" id="chk_anopheles_jentik_lainnya">
                      <label class="custom-control-label font-weight-normal text-dark" for="chk_anopheles_jentik_lainnya">Lainnya :</label>
                    </div>
                    <input type="text" name="jenis_habitat_lainnya" id="txt_anopheles_jentik_lainnya" class="form-control form-control-sm" placeholder="Sebutkan jenis habitat lainnya..." style="max-width: 320px;" disabled>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Kondisi Habitat -->
          <div class="card mb-3 border-0 shadow-sm" style="background: #f8fafc; border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-water text-primary mr-1"></i> Kondisi Habitat</h6>
              <div class="row">
                
                <!-- Pencahayaan -->
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold small text-muted d-block mb-2"><i class="fas fa-sun text-warning mr-1"></i> Pencahayaan</label>
                  <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="kondisi_pencahayaan[]" value="Terkena sinar matahari" id="cahaya_matahari">
                    <label class="custom-control-label font-weight-normal" for="cahaya_matahari">Terkena sinar matahari</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="kondisi_pencahayaan[]" value="Teduh" id="cahaya_teduh">
                    <label class="custom-control-label font-weight-normal" for="cahaya_teduh">Teduh</label>
                  </div>
                </div>

                <!-- Vegetasi -->
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold small text-muted d-block mb-2"><i class="fas fa-leaf text-success mr-1"></i> Vegetasi</label>
                  <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="kondisi_vegetasi[]" value="Ada vegetasi" id="veg_ada">
                    <label class="custom-control-label font-weight-normal" for="veg_ada">Ada vegetasi</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="kondisi_vegetasi[]" value="Tidak ada vegetasi" id="veg_tidak">
                    <label class="custom-control-label font-weight-normal" for="veg_tidak">Tidak ada vegetasi</label>
                  </div>
                </div>

                <!-- Kondisi Air -->
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold small text-muted d-block mb-2"><i class="fas fa-tint text-info mr-1"></i> Kondisi Air</label>
                  <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="kondisi_air[]" value="Jernih" id="air_jernih">
                    <label class="custom-control-label font-weight-normal" for="air_jernih">Jernih</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="kondisi_air[]" value="Keruh" id="air_keruh">
                    <label class="custom-control-label font-weight-normal" for="air_keruh">Keruh</label>
                  </div>
                  <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="kondisi_air[]" value="Mengalir" id="air_mengalir">
                    <label class="custom-control-label font-weight-normal" for="air_mengalir">Mengalir</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="kondisi_air[]" value="Tergenang" id="air_tergenang">
                    <label class="custom-control-label font-weight-normal" for="air_tergenang">Tergenang</label>
                  </div>
                </div>

                <!-- Keterangan -->
                <div class="col-12 mt-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-comment-alt text-secondary mr-1"></i> Keterangan Kondisi Habitat</label>
                  <input type="text" name="keterangan" id="anopheles_jentik_keterangan" class="form-control" placeholder="Tuliskan keterangan tambahan bila ada...">
                </div>

              </div>
            </div>
          </div>

          <!-- Perhitungan Indeks Habitat -->
          <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-calculator text-success mr-1"></i> Hasil Survei & Perhitungan Indeks Habitat</h6>
              <div class="row align-items-center">
                <div class="col-md-4 form-group">
                  <label class="font-weight-bold small text-dark">Jumlah habitat yang disurvei :</label>
                  <input type="number" name="jml_habitat_disurvei" id="anopheles_jml_disurvei" class="form-control font-weight-bold calc-anopheles-jentik" min="0" value="0" required>
                </div>
                <div class="col-md-4 form-group">
                  <label class="font-weight-bold small text-dark">Jumlah habitat positif jentik :</label>
                  <input type="number" name="jml_habitat_positif" id="anopheles_jml_positif" class="form-control font-weight-bold calc-anopheles-jentik" min="0" value="0" required>
                </div>
                <div class="col-md-4 form-group">
                  <label class="font-weight-bold small text-dark">Persentase habitat positif :</label>
                  <div class="p-2 bg-white rounded border d-flex align-items-center justify-content-between">
                    <span id="anopheles_indeks_val" class="font-weight-bold text-success" style="font-size: 1.25rem;">0.00%</span>
                    <span id="anopheles_status_val" class="badge badge-success px-2 py-1">MS (&lt; 1%)</span>
                  </div>
                </div>
              </div>
              <div class="alert alert-light border small text-muted mb-0 py-2">
                <i class="fas fa-info-circle text-info mr-1"></i>
                <b>Rumus Indeks Habitat:</b> <code>Indeks Habitat = (Jumlah habitat positif larva / Jumlah seluruh habitat yang diamati) x 100%</code>. Standar Baku Mutu Vektor Malaria Kemenkes: <b>&lt; 1% (Memenuhi Syarat / MS)</b>.
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Tutup</button>
          <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Data Jentik Anopheles</button>
        </div>
      </form>
    </div>
  </div>
</div>
