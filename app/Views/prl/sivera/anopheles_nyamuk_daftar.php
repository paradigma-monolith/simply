<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover table-sm text-center" id="table-anopheles-nyamuk">
    <thead class="bg-light">
      <tr>
        <th style="width: 40px;">No</th>
        <th>Hari / Tanggal</th>
        <th>Wilayah Kerja</th>
        <th>Lokasi</th>
        <th>Faktor Lingkungan</th>
        <th>Kolektor & Waktu</th>
        <th>Nyamuk Tertangkap</th>
        <th>Densitas MBR & MHD</th>
        <th>Temuan & Interpretasi</th>
        <th style="width: 120px;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $daftarHari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
      ];
      $no = 1; 
      foreach($anopheles_nyamuk as $row): 
        $tglStr = '-';
        if (!empty($row['tanggal'])) {
          $tglTimestamp = strtotime($row['tanggal']);
          $hariEng = date('l', $tglTimestamp);
          $hariIndo = $daftarHari[$hariEng] ?? $hariEng;
          $tglStr = $hariIndo . ' / ' . date('d M Y', $tglTimestamp);
        }

        $totalTertangkap = (int)($row['total_tertangkap'] ?? 0);
        $totalDalam = (int)($row['total_dalam'] ?? 0);
        $totalLuar = (int)($row['total_luar'] ?? 0);
        $adaAnopheles = ($row['ada_anopheles'] ?? ($totalTertangkap > 0 ? 'Ya' : 'Tidak'));
        
        $badgeTemuan = ($adaAnopheles === 'Ya')
          ? '<span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i>Ditemukan (' . $totalTertangkap . ' ekor)</span>'
          : '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Tidak Ditemukan</span>';

        $mbrVal = (float)($row['mbr'] ?? 0);
        $mhdVal = (float)($row['mhd'] ?? 0);
        
        $envItems = [];
        if (!empty($row['kondisi_cuaca'])) $envItems[] = '<i class="fas fa-cloud-sun text-warning mr-1"></i>' . esc($row['kondisi_cuaca']);
        if ($row['suhu'] !== null && $row['suhu'] !== '') $envItems[] = '<i class="fas fa-thermometer-half text-danger mr-1"></i>' . esc($row['suhu']) . ' °C';
        if ($row['kelembaban'] !== null && $row['kelembaban'] !== '') $envItems[] = '<i class="fas fa-tint text-info mr-1"></i>' . esc($row['kelembaban']) . ' %';
        if ($row['kecepatan_angin'] !== null && $row['kecepatan_angin'] !== '') $envItems[] = '<i class="fas fa-wind text-secondary mr-1"></i>' . esc($row['kecepatan_angin']) . ' m/s';
      ?>
      <tr>
        <td><?= $no++; ?></td>
        <td class="text-left font-weight-bold" style="white-space: nowrap;"><?= $tglStr; ?></td>
        <td><?= esc($row['wilker'] ?? '-'); ?></td>
        <td class="text-left"><?= esc($row['lokasi'] ?? '-'); ?></td>
        <td class="text-left small" style="min-width: 140px;">
          <?= !empty($envItems) ? implode('<br>', $envItems) : '<span class="text-muted">-</span>'; ?>
        </td>
        <td class="text-left small" style="white-space: nowrap;">
          <div><i class="fas fa-users text-primary mr-1"></i><b>Kolektor:</b> <?= (int)($row['jml_penangkap'] ?? 0); ?> org</div>
          <div><i class="fas fa-clock text-info mr-1"></i><b>Waktu:</b> <?= (float)($row['lama_penangkapan'] ?? 12); ?> jam</div>
        </td>
        <td class="text-left small" style="white-space: nowrap;">
          <div>Dalam: <b class="text-primary"><?= $totalDalam; ?></b></div>
          <div>Luar: <b class="text-info"><?= $totalLuar; ?></b></div>
          <div class="border-top pt-1 font-weight-bold text-dark">Total: <span class="<?= $totalTertangkap > 0 ? 'text-danger' : 'text-success'; ?>"><?= $totalTertangkap; ?> ekor</span></div>
        </td>
        <td class="text-left small" style="white-space: nowrap;">
          <div><b>MBR:</b> <span class="font-weight-bold <?= $mbrVal > 0.025 ? 'text-danger' : 'text-success'; ?>"><?= number_format($mbrVal, 4); ?></span></div>
          <div><b>MHD:</b> <span class="font-weight-bold text-dark"><?= number_format($mhdVal, 4); ?></span></div>
          <div class="text-muted" style="font-size: 0.72rem;">Baku Mutu: &lt; 0.025</div>
        </td>
        <td class="text-left small" style="max-width: 240px;">
          <div class="mb-1"><?= $badgeTemuan; ?></div>
          <?php if (!empty($row['interpretasi'])): ?>
            <div class="text-muted" style="font-size: 0.75rem; line-height: 1.25;"><?= esc($row['interpretasi']); ?></div>
          <?php endif; ?>
        </td>
        <td>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-primary btn-detail-anopheles-nyamuk" data-row='<?= json_encode($row); ?>' title="Lihat Tabel Hasil Jam">
              <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-info btn-edit-anopheles-nyamuk" data-row='<?= json_encode($row); ?>' title="Edit Data">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger btn-hapus-anopheles-nyamuk" data-id="<?= $row['id_anopheles_nyamuk']; ?>" title="Hapus Data">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($anopheles_nyamuk)): ?>
      <tr>
        <td colspan="10" class="text-center py-4 text-muted">
          <i class="fas fa-inbox fa-3x mb-2 d-block text-gray-300"></i>
          Belum ada data Surveilans Nyamuk Anopheles yang sesuai dengan filter.
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah / Edit Nyamuk Anopheles -->
<div class="modal fade" id="modal-anopheles-nyamuk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form id="form-anopheles-nyamuk">
        <input type="hidden" name="id_anopheles_nyamuk" id="id_anopheles_nyamuk_form">
        <div class="modal-header bg-success text-white" id="modal-anopheles-nyamuk-header">
          <h5 class="modal-title font-weight-bold" id="modal-anopheles-nyamuk-title">
            <i class="fas fa-mosquito mr-2"></i> Formulir Survei Nyamuk Anopheles
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">

          <!-- Baris 1: Informasi Survei -->
          <div class="card mb-3 border-0 shadow-sm" style="background: #f8fafc; border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-primary mr-1"></i> Parameter Penangkapan & Lingkungan</h6>
              <div class="row">
                <div class="col-lg-3 col-md-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt text-primary mr-1"></i> Hari / Tanggal</label>
                  <input type="date" name="tanggal" id="anopheles_nyamuk_tanggal" class="form-control" required>
                  <small id="anopheles_nyamuk_hari_label" class="text-primary font-weight-bold mt-1 d-block"></small>
                </div>
                <div class="col-lg-3 col-md-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah Kerja</label>
                  <select name="id_wilayah_kerja" id="anopheles_nyamuk_wilker" class="form-control" required>
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php foreach($wilayah_kerja as $wk): ?>
                    <option value="<?= $wk['id_unit_kerja']; ?>"><?= esc($wk['unit_kerja']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-lg-3 col-md-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-map-pin text-success mr-1"></i> Lokasi</label>
                  <input type="text" name="lokasi" id="anopheles_nyamuk_lokasi" class="form-control" placeholder="Contoh: PPI Karangsong Indramayu" required>
                </div>
                <div class="col-lg-3 col-md-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-dna text-purple mr-1"></i> Spesies Target</label>
                  <input type="text" name="spesies" id="anopheles_nyamuk_spesies" class="form-control" value="Anopheles sp" required>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-users text-primary mr-1"></i> Jumlah Penangkap</label>
                  <input type="number" name="jml_penangkap" id="anopheles_jml_penangkap" class="form-control font-weight-bold calc-nyamuk-trigger" min="1" value="5" required>
                  <small class="text-muted">orang kolektor</small>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-clock text-info mr-1"></i> Lama Penangkapan</label>
                  <input type="number" step="0.1" name="lama_penangkapan" id="anopheles_lama_penangkapan" class="form-control font-weight-bold calc-nyamuk-trigger" min="0.1" value="12" required>
                  <small class="text-muted">jam malam (18.00-06.00)</small>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-cloud text-warning mr-1"></i> Kondisi Cuaca</label>
                  <input type="text" name="kondisi_cuaca" id="anopheles_kondisi_cuaca" class="form-control" placeholder="Cerah / Berawan">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-thermometer-half text-danger mr-1"></i> Suhu (°C)</label>
                  <input type="number" step="0.1" name="suhu" id="anopheles_suhu" class="form-control" placeholder="28.5">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-tint text-info mr-1"></i> Kelembaban (%)</label>
                  <input type="number" step="0.1" name="kelembaban" id="anopheles_kelembaban" class="form-control" placeholder="82">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 form-group mb-2">
                  <label class="font-weight-bold small text-muted"><i class="fas fa-wind text-secondary mr-1"></i> Kecepatan Angin</label>
                  <input type="number" step="0.1" name="kecepatan_angin" id="anopheles_kecepatan_angin" class="form-control" placeholder="m/s">
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Hasil Survei Nyamuk Anopheles (Matrix 12 Jam) -->
          <div class="card mb-3 border-0 shadow-sm" style="background: #ffffff; border-radius: 0.75rem;">
            <div class="card-header bg-gradient-primary text-white py-2 d-flex justify-content-between align-items-center">
              <span class="font-weight-bold"><i class="fas fa-table mr-1"></i> Tabel Hasil Survei Nyamuk Anopheles (Pukul 18.00 - 06.00)</span>
              <span class="badge badge-light text-primary font-weight-bold">12 Jam Penangkapan</span>
            </div>
            <div class="card-body p-2">
              <div class="table-responsive">
                <table class="table table-bordered table-sm text-center mb-0" id="matrix-tangkapan-nyamuk" style="font-size: 0.82rem;">
                  <thead class="bg-light">
                    <tr>
                      <th rowspan="2" class="align-middle" style="width: 35px;">No</th>
                      <th rowspan="2" class="align-middle" style="min-width: 110px;">Spesies Nyamuk</th>
                      <th rowspan="2" class="align-middle" style="min-width: 90px;">Tempat Penangkapan</th>
                      <th colspan="12" class="text-center bg-white">Banyaknya nyamuk tertangkap pada pukul</th>
                      <th rowspan="2" class="align-middle bg-light" style="width: 65px;">Total</th>
                      <th rowspan="2" class="align-middle bg-light" style="min-width: 85px;">Densitas MHD / MBR</th>
                      <th rowspan="2" class="align-middle" style="min-width: 100px;">Ket</th>
                    </tr>
                    <tr>
                      <?php 
                      $jamLabels = [
                        '18_19' => '18⁰⁰-19⁰⁰',
                        '19_20' => '19⁰⁰-20⁰⁰',
                        '20_21' => '20⁰⁰-21⁰⁰',
                        '21_22' => '21⁰⁰-22⁰⁰',
                        '22_23' => '22⁰⁰-23⁰⁰',
                        '23_24' => '23⁰⁰-24⁰⁰',
                        '24_01' => '24⁰⁰-01⁰⁰',
                        '01_02' => '01⁰⁰-02⁰⁰',
                        '02_03' => '02⁰⁰-03⁰⁰',
                        '03_04' => '03⁰⁰-04⁰⁰',
                        '04_05' => '04⁰⁰-05⁰⁰',
                        '05_06' => '05⁰⁰-06⁰⁰',
                      ];
                      foreach($jamLabels as $k => $lbl): ?>
                      <th style="min-width: 50px; font-size: 0.75rem;" class="p-1 text-muted"><?= $lbl; ?></th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Baris Dalam -->
                    <tr>
                      <td rowspan="2" class="align-middle font-weight-bold">1</td>
                      <td rowspan="2" class="align-middle font-weight-bold font-italic text-dark">
                        <span id="matrix_label_spesies">Anopheles sp</span>
                      </td>
                      <td class="align-middle text-left font-weight-bold text-primary">
                        <i class="fas fa-home mr-1"></i> Dalam
                      </td>
                      <?php foreach($jamLabels as $k => $lbl): ?>
                      <td class="p-1">
                        <input type="number" min="0" value="0" name="tangkapan_dalam[<?= $k; ?>]" class="form-control form-control-sm text-center p-1 matrix-nyamuk-input jam-dalam" data-jam="<?= $k; ?>" style="height: 28px; font-weight: 600;">
                      </td>
                      <?php endforeach; ?>
                      <td class="align-middle font-weight-bold bg-light">
                        <span id="total_dalam_val" class="text-primary" style="font-size: 0.95rem;">0</span>
                      </td>
                      <td class="align-middle font-weight-bold bg-light">
                        <span id="mhd_dalam_val">0.0000</span>
                      </td>
                      <td class="p-1 align-middle">
                        <input type="text" name="ket_dalam" id="ket_dalam" class="form-control form-control-sm" placeholder="Catatan...">
                      </td>
                    </tr>
                    <!-- Baris Luar -->
                    <tr>
                      <td class="align-middle text-left font-weight-bold text-info">
                        <i class="fas fa-tree mr-1"></i> Luar
                      </td>
                      <?php foreach($jamLabels as $k => $lbl): ?>
                      <td class="p-1">
                        <input type="number" min="0" value="0" name="tangkapan_luar[<?= $k; ?>]" class="form-control form-control-sm text-center p-1 matrix-nyamuk-input jam-luar" data-jam="<?= $k; ?>" style="height: 28px; font-weight: 600;">
                      </td>
                      <?php endforeach; ?>
                      <td class="align-middle font-weight-bold bg-light">
                        <span id="total_luar_val" class="text-info" style="font-size: 0.95rem;">0</span>
                      </td>
                      <td class="align-middle font-weight-bold bg-light">
                        <span id="mhd_luar_val">0.0000</span>
                      </td>
                      <td class="p-1 align-middle">
                        <input type="text" name="ket_luar" id="ket_luar" class="form-control form-control-sm" placeholder="Catatan...">
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="bg-light font-weight-bold">
                    <tr>
                      <td colspan="3" class="text-right py-2">Total Keseluruhan (Dalam + Luar) :</td>
                      <td colspan="12" class="text-center py-2 text-muted font-weight-normal small">
                        Tersebar dalam 12 jam penangkapan malam
                      </td>
                      <td class="text-center py-2">
                        <span id="total_keseluruhan_val" class="badge badge-primary px-2 py-1" style="font-size: 1rem;">0</span>
                      </td>
                      <td class="text-center py-2">
                        <span id="total_mhd_mbr_val" class="text-danger font-weight-bold" style="font-size: 0.95rem;">0.0000</span>
                      </td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>

          <!-- Perhitungan MBR & MHD serta Interpretasi -->
          <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border-radius: 0.75rem;">
            <div class="card-body p-3">
              <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-calculator text-success mr-1"></i> Hasil Analisis Entomologi & Interpretasi</h6>
              
              <div class="row align-items-center mb-3">
                <div class="col-md-3 form-group mb-2">
                  <label class="font-weight-bold small text-muted">Apakah didapatkan Anopheles?</label>
                  <select name="ada_anopheles" id="anopheles_ada_select" class="form-control font-weight-bold">
                    <option value="Tidak">Tidak</option>
                    <option value="Ya">Ya</option>
                  </select>
                </div>
                <div class="col-md-3 form-group mb-2">
                  <label class="font-weight-bold small text-muted">Jumlah Anopheles Tertangkap :</label>
                  <div class="p-2 bg-white rounded border">
                    <span id="anopheles_jml_tertangkap_display" class="font-weight-bold text-dark" style="font-size: 1.15rem;">0 ekor</span>
                  </div>
                </div>
                <div class="col-md-3 form-group mb-2">
                  <label class="font-weight-bold small text-muted">Man Biting Rate (MBR) :</label>
                  <div class="p-2 bg-white rounded border d-flex justify-content-between align-items-center">
                    <span id="mbr_display_val" class="font-weight-bold text-primary" style="font-size: 1.15rem;">0.0000</span>
                    <span id="mbr_status_badge" class="badge badge-success px-2 py-1">&lt; 0.025 (MS)</span>
                  </div>
                </div>
                <div class="col-md-3 form-group mb-2">
                  <label class="font-weight-bold small text-muted">Man Hour Density (MHD) :</label>
                  <div class="p-2 bg-white rounded border">
                    <span id="mhd_display_val" class="font-weight-bold text-dark" style="font-size: 1.15rem;">0.0000</span>
                  </div>
                </div>
              </div>

              <!-- Interpretasi -->
              <div class="form-group mb-2">
                <label class="font-weight-bold small text-dark"><i class="fas fa-diagnoses text-info mr-1"></i> Interpretasi Hasil Survei :</label>
                <div class="p-3 bg-white rounded border">
                  <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="interpretasi_tidak" name="interpretasi" class="custom-control-input" value="Tidak ditemukan Anopheles sehingga belum terdapat indikasi faktor risiko penularan malaria pada saat survei." checked>
                    <label class="custom-control-label font-weight-normal text-dark" for="interpretasi_tidak">
                      <b>Tidak ditemukan Anopheles</b> sehingga belum terdapat indikasi faktor risiko penularan malaria pada saat survei.
                    </label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="interpretasi_ya" name="interpretasi" class="custom-control-input" value="Ditemukan Anopheles sehingga perlu dilakukan identifikasi spesies, analisis faktor risiko lingkungan, dan tindak lanjut pengendalian vektor.">
                    <label class="custom-control-label font-weight-normal text-danger" for="interpretasi_ya">
                      <b>Ditemukan Anopheles</b> sehingga perlu dilakukan identifikasi spesies, analisis faktor risiko lingkungan, dan tindak lanjut pengendalian vektor.
                    </label>
                  </div>
                </div>
              </div>

              <!-- Rumus Info -->
              <div class="alert alert-light border small text-muted mb-0 py-2">
                <i class="fas fa-info-circle text-info mr-1"></i>
                <b>Rumus Man Biting Rate (MBR) & MHD:</b> <code>MBR = Jumlah nyamuk (spesies tertentu) yang tertangkap / (Jumlah penangkap x waktu penangkapan (jam))</code>.
                Standar Baku Mutu Kemenkes: <b>MBR &lt; 0,025 gigitan per orang per malam</b>.
              </div>

            </div>
          </div>

        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Tutup</button>
          <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Data Nyamuk Anopheles</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Detail Nyamuk Anopheles -->
<div class="modal fade" id="modal-detail-anopheles-nyamuk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-mosquito mr-2"></i> Rincian Hasil Survei Nyamuk Anopheles
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4" id="detail-nyamuk-content">
        <!-- Rendered via JS -->
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
