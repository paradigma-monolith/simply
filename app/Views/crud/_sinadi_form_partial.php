<?php
$isEdit = !empty($isEdit);
$isDalamKota = !empty($isDalamKota);

$perjalananData = $perjalanan ?? [];
$pesertaList = (!empty($peserta) && is_array($peserta)) ? $peserta : [
  [
    'id_peserta'         => '',
    'nip_pegawai'        => '',
    'nama_pegawai'       => '',
    'jabatan_pegawai'    => '',
    'golongan'           => '',
    'tgl_pergi'          => '',
    'tgl_pulang'         => '',
    'kota_asal'          => '',
    'kota'               => '',
    'no_surat_tugas'     => '',
    'tgl_surat_tugas'    => '',
    'no_sppd'            => $isDalamKota ? '-' : '',
    'tgl_sppd'           => '',
    'transportasi'       => 'Transportasi darat',
    'transport_hari'     => '',
    'transport_nominal'  => '',
    'transport_jumlah'   => '',
    'harian_hari'        => '',
    'harian_nominal'     => '',
    'harian_jumlah'      => '',
    'penginapan_hari'    => '',
    'penginapan_nominal' => '',
    'penginapan_jumlah'  => '',
    'jumlah_transfer'    => '',
  ],
];

$settingSource = ($isEdit && !empty($peserta[0]['alias_kantor'])) ? $peserta[0] : ($setting ?? []);
$aliasKantor = (string) ($settingSource['alias_kantor'] ?? '');
$namaKantor = (string) ($settingSource['nama_kantor'] ?? '');

// Peta pegawai berdasarkan NIP bersih untuk pencarian O(1) tanpa loop 760 <option> pada field pejabat read-only
$pegawaiByCleanNip = [];
foreach ($pegawai as $pItem) {
  $cleanNip = preg_replace('/\D/', '', (string) ($pItem['nip'] ?? ''));
  if ($cleanNip !== '') {
    $pegawaiByCleanNip[$cleanNip] = $pItem;
  }
}

$resolvePejabatLabel = static function (?string $rawNip) use ($pegawaiByCleanNip): string {
  $clean = preg_replace('/\D/', '', (string) $rawNip);
  if ($clean !== '' && isset($pegawaiByCleanNip[$clean])) {
    $row = $pegawaiByCleanNip[$clean];
    return $row['nama'] . ' (NIP. ' . $row['nip'] . ')';
  }
  return $rawNip ? ('NIP. ' . $rawNip) : 'Belum ditentukan';
};

$labelKepalaKantor = $resolvePejabatLabel($settingSource['kepala_kantor_nip'] ?? '');
$labelPpk          = $resolvePejabatLabel($settingSource['ppk_nip'] ?? '');
$labelKasubag      = $resolvePejabatLabel($settingSource['kasubag_adum_nip'] ?? '');
$labelBendahara    = $resolvePejabatLabel($settingSource['bendahara_nip'] ?? '');

$backUrl = $isDalamKota ? '/sinadi/dinasDalamKota' : '/sinadi/dinasLuarKota';
$formAction = $isEdit ? '/sinadi/edit_sinadi' : '/sinadi/tambah_sinadi';
$headingTitle = ($isEdit ? 'Ubah Data ' : 'Tambah ') . 'Perjalanan Dinas ' . ($isDalamKota ? 'Dalam Kota' : 'Luar Kota');
$sumberVal = (string) ($perjalananData['sumber_anggaran'] ?? 'APBN');
$initialKategori = count($pesertaList) > 1 ? 'Kelompok' : 'Perseorangan';

// Helper untuk merender 1 kartu peserta (index 0 maupun 1..N)
$renderPesertaBlock = static function (array $pRow, int $idx) use ($isEdit, $isDalamKota, $pegawai, $kota): void {
  $isFirst = ($idx === 0);
  $idPeserta = (string) ($pRow['id_peserta'] ?? '');
  $nipVal = (string) ($pRow['nip_pegawai'] ?? '');
  $namaVal = (string) ($pRow['nama_pegawai'] ?? '');
  $jabatanVal = (string) ($pRow['jabatan_pegawai'] ?? '');
  $golonganVal = (string) ($pRow['golongan'] ?? '');
  $transportVal = (string) ($pRow['transportasi'] ?? '');
  $kotaAsalVal = (string) ($pRow['kota_asal'] ?? '');
  $kotaTujuanVal = (string) ($pRow['kota'] ?? '');
  ?>
  <div class="sf-peserta-block">
    <div class="sf-peserta-header">
      <div class="d-flex align-items-center" style="gap: 8px;">
        <span class="sf-peserta-num-badge">
          <i class="fas fa-user-tie mr-1"></i>
          <span class="sf-peserta-title-text"><?= $isFirst ? 'PEGAWAI NO 1' : ('PEGAWAI NO ' . ($idx + 1)); ?></span>
        </span>
      </div>
      <div class="d-flex align-items-center" style="gap: 6px;">
        <button type="button"
                class="sf-toggle-manual-btn"
                onclick="sinadiToggleHideDataPegawai(this)">
          <i class="fas fa-keyboard mr-1"></i>Input Manual Pegawai
        </button>
        <button type="button"
                class="btn btn-xs <?= $isFirst ? 'btn-secondary d-none' : 'btn-danger'; ?> btn-info-pegawai"
                data-peserta="<?= esc($idPeserta, 'attr'); ?>"
                title="Hapus Pegawai Ini">
          <i class="fas fa-trash-alt mr-1"></i>Hapus
        </button>
      </div>
    </div>

    <div class="sf-peserta-body">
      <!-- Baris Pilih Pegawai (Otomatis via Select2) -->
      <div class="row autoInput">
        <div class="col-md-5 form-group mb-2">
          <label class="sf-label">Pilih Pegawai (NIP / Nama) <span class="text-danger">*</span></label>
          <select class="form-control select2" name="nip[]" data-pegawai="nip[]" required style="width: 100%;">
            <option value="">-- Cari NIP atau Nama Pegawai --</option>
            <?php
            $foundInMaster = false;
            foreach ($pegawai as $data) :
              $isSelected = ($nipVal !== '' && $nipVal === (string) $data['nip']);
              if ($isSelected) {
                $foundInMaster = true;
              }
            ?>
              <option value="<?= esc($data['nip'], 'attr'); ?>"
                      data-nama="<?= esc($data['nama'], 'attr'); ?>"
                      data-jabatan="<?= esc($data['jabatan'], 'attr'); ?>"
                      data-golongan="<?= esc($data['golongan'], 'attr'); ?>"
                      <?= $isSelected ? 'selected' : ''; ?>>
                <?= esc($data['nip'] . ' | ' . $data['nama']); ?>
              </option>
            <?php endforeach; ?>
            <?php if ($isEdit && !$foundInMaster && $nipVal !== '') : ?>
              <option value="<?= esc($nipVal, 'attr'); ?>"
                      data-nama="<?= esc($namaVal, 'attr'); ?>"
                      data-jabatan="<?= esc($jabatanVal, 'attr'); ?>"
                      data-golongan="<?= esc($golonganVal, 'attr'); ?>"
                      selected>
                <?= esc($nipVal . ' | ' . $namaVal); ?>
              </option>
            <?php endif; ?>
          </select>
        </div>

        <div class="col-md-5 form-group mb-2">
          <input class="nama" readonly name="nama[]" type="hidden" required value="<?= esc($namaVal, 'attr'); ?>">
          <label class="sf-label">Jabatan</label>
          <input class="form-control form-control-sm jabatan bg-light"
                 readonly
                 name="jabatan[]"
                 type="text"
                 required
                 placeholder="Otomatis terisi..."
                 value="<?= esc($jabatanVal !== '' ? $jabatanVal : ($isEdit ? '-' : ''), 'attr'); ?>">
        </div>

        <div class="col-md-2 form-group mb-2">
          <label class="sf-label">Golongan</label>
          <input class="form-control form-control-sm golongan bg-light"
                 readonly
                 name="golongan[]"
                 type="text"
                 required
                 placeholder="-"
                 value="<?= esc($golonganVal !== '' ? $golonganVal : ($isEdit ? '-' : ''), 'attr'); ?>">
        </div>
      </div>

      <!-- Baris Input Pegawai Manual (Tersembunyi secara default) -->
      <div class="row manualInput" style="display: none;">
        <div class="col-md-3 form-group mb-2">
          <label class="sf-label">NIP Manual <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm manualNip" required disabled name="manualNip[]" type="text" placeholder="Masukkan NIP">
        </div>
        <div class="col-md-3 form-group mb-2">
          <label class="sf-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm manualNama" required disabled name="manualNama[]" type="text" placeholder="Masukkan Nama">
        </div>
        <div class="col-md-4 form-group mb-2">
          <label class="sf-label">Jabatan <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm manualJabatan" required disabled name="manualJabatan[]" type="text" placeholder="Masukkan Jabatan">
        </div>
        <div class="col-md-2 form-group mb-2">
          <label class="sf-label">Golongan <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm manualGolongan" required disabled name="manualGolongan[]" type="text" placeholder="Contoh: III/b">
        </div>
      </div>

      <!-- Baris Jadwal & Rute Perjalanan Peserta -->
      <div class="row mt-1">
        <div class="col-md-3 col-6 form-group mb-2">
          <label class="sf-label">Tanggal Berangkat <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="date" name="tgl_pergi[]" required value="<?= esc($pRow['tgl_pergi'] ?? '', 'attr'); ?>">
        </div>
        <div class="col-md-3 col-6 form-group mb-2">
          <label class="sf-label">Tanggal Kembali <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="date" name="tgl_pulang[]" required value="<?= esc($pRow['tgl_pulang'] ?? '', 'attr'); ?>">
        </div>

        <?php if ($isDalamKota) : ?>
          <div class="col-md-3 form-group mb-2">
            <label class="sf-label">Alamat Asal <span class="text-danger">*</span></label>
            <input class="form-control form-control-sm"
                   type="text"
                   name="kota_asal[]"
                   placeholder="Contoh: Kantor BKK Bandung"
                   required
                   value="<?= esc($kotaAsalVal, 'attr'); ?>">
          </div>
          <div class="col-md-3 form-group mb-2">
            <label class="sf-label">Alamat Tujuan <span class="text-danger">*</span></label>
            <input type="hidden" value="<?= esc($kotaTujuanVal, 'attr'); ?>" name="kota_tujuan[]">
            <input class="form-control form-control-sm"
                   type="text"
                   name="kota[]"
                   placeholder="Isi alamat tujuan"
                   required
                   value="<?= esc($kotaTujuanVal, 'attr'); ?>">
          </div>
        <?php else : ?>
          <div class="col-md-3 form-group mb-2">
            <label class="sf-label">Kota / Kabupaten Asal <span class="text-danger">*</span></label>
            <select class="form-control select2" name="kota_asal[]" data-pegawai="kota_asal[]" required style="width: 100%;">
              <option value="">-- Pilih Kota Asal --</option>
              <?php
              $foundKotaAsal = false;
              foreach ($kota as $kRow) :
                $kName = (string) $kRow['nama_kota_kabupaten'];
                $selAsal = ($kotaAsalVal !== '' && $kotaAsalVal === $kName);
                if ($selAsal) {
                  $foundKotaAsal = true;
                }
              ?>
                <option value="<?= esc($kName, 'attr'); ?>" <?= $selAsal ? 'selected' : ''; ?>><?= esc($kName); ?></option>
              <?php endforeach; ?>
              <?php if ($isEdit && !$foundKotaAsal && $kotaAsalVal !== '') : ?>
                <option value="<?= esc($kotaAsalVal, 'attr'); ?>" selected><?= esc($kotaAsalVal); ?></option>
              <?php endif; ?>
            </select>
          </div>
          <div class="col-md-3 form-group mb-2">
            <label class="sf-label">Kota / Kabupaten Tujuan <span class="text-danger">*</span></label>
            <input type="hidden" class="kota_tujuan_input" value="<?= esc($kotaTujuanVal, 'attr'); ?>" name="kota_tujuan[]">
            <?php
            $selectedKotaTujuanList = array_values(array_filter(array_map('trim', explode(',', $kotaTujuanVal)), static fn ($v) => $v !== ''));
            $masterKotaNames = array_column($kota, 'nama_kota_kabupaten');
            ?>
            <select class="select2 kota_tujuan_select" multiple="multiple" name="kota[]" data-placeholder="-- Pilih Kota Tujuan --" style="width: 100%;" required>
              <?php foreach ($selectedKotaTujuanList as $customKt) : ?>
                <?php if (!in_array($customKt, $masterKotaNames, true)) : ?>
                  <option value="<?= esc($customKt, 'attr'); ?>" selected><?= esc($customKt); ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
              <?php foreach ($kota as $kRow) : ?>
                <?php $kName = (string) $kRow['nama_kota_kabupaten']; ?>
                <option value="<?= esc($kName, 'attr'); ?>" <?= in_array($kName, $selectedKotaTujuanList, true) ? 'selected' : ''; ?>>
                  <?= esc($kName); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>
      </div>

      <!-- Baris Surat Tugas, SPPD & Transportasi -->
      <div class="row">
        <div class="col-md-3 col-6 form-group mb-2">
          <label class="sf-label">No. Surat Tugas <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="text" name="no_surat_tugas[]" required placeholder="Nomor ST" value="<?= esc($pRow['no_surat_tugas'] ?? '', 'attr'); ?>">
        </div>
        <div class="col-md-2 col-6 form-group mb-2">
          <label class="sf-label">Tgl. Surat Tugas <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="date" name="tgl_surat_tugas[]" required value="<?= esc($pRow['tgl_surat_tugas'] ?? '', 'attr'); ?>">
        </div>
        <div class="col-md-2 col-6 form-group mb-2">
          <label class="sf-label">No. SPPD <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="text" name="no_sppd[]" required placeholder="Nomor SPPD / -" value="<?= esc($pRow['no_sppd'] ?? ($isDalamKota ? '-' : ''), 'attr'); ?>">
        </div>
        <div class="col-md-2 col-6 form-group mb-2">
          <label class="sf-label">Tgl. SPPD <span class="text-danger">*</span></label>
          <input class="form-control form-control-sm" type="date" name="tgl_sppd[]" required value="<?= esc($pRow['tgl_sppd'] ?? '', 'attr'); ?>">
        </div>
        <div class="col-md-3 form-group mb-2">
          <label class="sf-label">Alat Angkutan <span class="text-danger">*</span></label>
          <select class="form-control select2" name="transportasi[]" data-pegawai="transportasi[]" required style="width: 100%;">
            <option value="">-- Pilih Transportasi --</option>
            <?php if ($transportVal === 'Kendaraan umum') : ?>
              <option value="Kendaraan umum" selected>Kendaraan umum</option>
            <?php endif; ?>
            <option value="Transportasi darat" <?= ($transportVal === 'Transportasi darat' || $transportVal === '') ? 'selected' : ''; ?>>Transportasi darat</option>
            <option value="Transportasi udara" <?= $transportVal === 'Transportasi udara' ? 'selected' : ''; ?>>Transportasi udara</option>
            <option value="Transportasi laut" <?= $transportVal === 'Transportasi laut' ? 'selected' : ''; ?>>Transportasi laut</option>
          </select>
        </div>
      </div>

      <!-- Tabel Perhitungan Anggaran Ringkas -->
      <div class="sf-cost-box mt-1">
        <div class="row perhitungan_anggaran align-items-end">
          <!-- Transport -->
          <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
            <div class="sf-cost-group-title"><i class="fas fa-car-side text-info mr-1"></i>Biaya Transport</div>
            <div class="form-row">
              <div class="col-4">
                <small class="text-muted d-block">Hari/Kali</small>
                <input class="form-control form-control-sm anggaran transport_hari" type="text" name="transport_hari[]" placeholder="0" value="<?= esc($pRow['transport_hari'] ?? '', 'attr'); ?>">
              </div>
              <div class="col-8">
                <small class="text-muted d-block">Tarif Satuan (Rp)</small>
                <input class="form-control form-control-sm anggaran transport_transport" type="text" name="transport_nominal[]" placeholder="0" value="<?= esc($pRow['transport_nominal'] ?? '', 'attr'); ?>">
              </div>
            </div>
            <input class="form-control form-control-sm transport_jumlah mt-1 bg-white font-weight-bold" type="text" name="transport_jumlah[]" placeholder="Subtotal Transport" readonly required value="<?= esc($pRow['transport_jumlah'] ?? '', 'attr'); ?>">
          </div>

          <!-- Uang Harian -->
          <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
            <div class="sf-cost-group-title"><i class="fas fa-wallet text-success mr-1"></i>Uang Harian</div>
            <div class="form-row">
              <div class="col-4">
                <small class="text-muted d-block">Hari</small>
                <input class="form-control form-control-sm anggaran harian_hari" type="text" name="harian_hari[]" placeholder="0" value="<?= esc($pRow['harian_hari'] ?? '', 'attr'); ?>">
              </div>
              <div class="col-8">
                <small class="text-muted d-block">Unit Cost (Rp)</small>
                <input class="form-control form-control-sm anggaran harian_unit_cost" type="text" name="harian_nominal[]" placeholder="0" value="<?= esc($pRow['harian_nominal'] ?? '', 'attr'); ?>">
              </div>
            </div>
            <input class="form-control form-control-sm harian_jumlah mt-1 bg-white font-weight-bold" type="text" name="harian_jumlah[]" placeholder="Subtotal Uang Harian" readonly required value="<?= esc($pRow['harian_jumlah'] ?? '', 'attr'); ?>">
          </div>

          <!-- Penginapan -->
          <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
            <div class="sf-cost-group-title"><i class="fas fa-hotel text-warning mr-1"></i>Penginapan</div>
            <div class="form-row">
              <div class="col-4">
                <small class="text-muted d-block">Malam</small>
                <input class="form-control form-control-sm anggaran penginapan_kali" type="text" name="penginapan_hari[]" placeholder="0" value="<?= esc($pRow['penginapan_hari'] ?? '', 'attr'); ?>">
              </div>
              <div class="col-8">
                <small class="text-muted d-block">Unit Cost (Rp)</small>
                <input class="form-control form-control-sm anggaran penginapan_unit_cost" type="text" name="penginapan_nominal[]" placeholder="0" value="<?= esc($pRow['penginapan_nominal'] ?? '', 'attr'); ?>">
              </div>
            </div>
            <input class="form-control form-control-sm penginapan_jumlah mt-1 bg-white font-weight-bold" type="text" name="penginapan_jumlah[]" placeholder="Subtotal Penginapan" readonly required value="<?= esc($pRow['penginapan_jumlah'] ?? '', 'attr'); ?>">
          </div>

          <!-- Jumlah Transfer -->
          <div class="col-lg-3 col-md-6">
            <div class="sf-cost-group-title text-teal"><i class="fas fa-money-check-alt mr-1"></i>Jumlah Transfer Peserta</div>
            <input class="form-control jumlah_transfer font-weight-bold"
                   type="text"
                   name="jumlah_transfer[]"
                   placeholder="Rp 0"
                   readonly
                   required
                   value="<?= esc($pRow['jumlah_transfer'] ?? '', 'attr'); ?>"
                   style="height: 58px; font-size: 1rem; background: #f0fdfa; border-color: #99f6e4; color: #0f766e;">
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
};
?>

<style>
  .sf-page {
    --sf-primary: #0d9488;
    --sf-primary-dark: #0f766e;
    --sf-primary-soft: #f0fdfa;
    --sf-border: rgba(15, 23, 42, 0.08);
    --sf-radius: 14px;
    --sf-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    padding-bottom: 32px;
  }

  /* Hero Banner */
  .sf-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: var(--sf-radius);
    padding: 16px 20px;
    color: #ffffff;
    box-shadow: 0 8px 22px rgba(4, 120, 121, 0.18);
    margin-bottom: 14px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .sf-hero-title {
    font-size: 1.15rem;
    font-weight: 800;
    margin: 0 0 2px;
  }
  .sf-hero-sub {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.86);
    margin: 0;
  }

  /* Section Card */
  .sf-card {
    background: #ffffff;
    border: 1px solid var(--sf-border);
    border-radius: var(--sf-radius);
    box-shadow: var(--sf-shadow);
    margin-bottom: 16px;
    overflow: hidden;
  }
  .sf-card-header {
    padding: 12px 18px;
    border-bottom: 1px solid var(--sf-border);
    background: #f8fafc;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .sf-card-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .sf-step-num {
    width: 24px;
    height: 24px;
    border-radius: 7px;
    background: #0d9488;
    color: #ffffff;
    font-size: 0.76rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .sf-card-body {
    padding: 16px 18px;
  }

  .sf-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
    display: block;
  }

  /* Participant Block */
  .sf-peserta-block {
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    background: #ffffff;
    margin-bottom: 14px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
  }
  .sf-peserta-header {
    background: #f1f5f9;
    padding: 9px 14px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }
  .sf-peserta-num-badge {
    font-size: 0.78rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.3px;
  }
  .sf-toggle-manual-btn {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .sf-toggle-manual-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
  .sf-peserta-body {
    padding: 13px 14px;
  }
  .sf-cost-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
  }
  .sf-cost-group-title {
    font-size: 0.74rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
  }

  /* Pejabat Read-Only Grid */
  .sf-pejabat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 14px;
  }
  .sf-pejabat-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 12px;
  }
  .sf-pejabat-role {
    font-size: 0.69rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.3px;
  }
  .sf-pejabat-name {
    font-size: 0.81rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2px;
  }

  /* Grand Total Summary Bar */
  .sf-grand-bar {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    border-radius: 12px;
    padding: 13px 18px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 12px;
  }
  .sf-grand-items {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
  }
  .sf-grand-lbl {
    font-size: 0.68rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
  }
  .sf-grand-val {
    font-size: 0.92rem;
    font-weight: 700;
    color: #ffffff;
  }
  .sf-grand-total-val {
    font-size: 1.18rem;
    font-weight: 800;
    color: #5eead4;
  }

  @media (max-width: 767.98px) {
    .sf-card-header,
    .sf-card-body {
      padding: 12px 14px;
    }
    .sf-pejabat-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<section class="content sf-page">
  <div class="container-fluid">

    <!-- Hero Header -->
    <div class="sf-hero">
      <div>
        <h4 class="sf-hero-title">
          <i class="fas <?= $isDalamKota ? 'fa-city' : 'fa-plane-departure'; ?> mr-2"></i><?= esc($headingTitle); ?>
        </h4>
        <p class="sf-hero-sub">Lengkapi informasi kegiatan, daftar peserta, rincian biaya, dan PIC perjalanan dinas</p>
      </div>
      <div class="d-flex align-items-center" style="gap: 8px;">
        <a href="<?= $backUrl; ?>" class="btn btn-sm btn-light font-weight-bold px-3" style="border-radius: 9px; color: #0f766e;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
      </div>
    </div>

    <form id="sinadiTambahPerjalananDinas" action="<?= $formAction; ?>" method="post" enctype="multipart/form-data">
      <?php if ($isEdit) : ?>
        <input type="hidden" name="id_perjalanan" value="<?= (int) ($perjalananData['id_perjalanan'] ?? 0); ?>">
      <?php endif; ?>
      <?php if ($isDalamKota) : ?>
        <input type="hidden" name="is_dalam_kota" value="1">
      <?php endif; ?>
      <input type="hidden" name="jenis_perjalanan_dinas" value="<?= $isDalamKota ? 'dalam kota' : 'luar kota'; ?>">
      <input type="hidden" name="kategori" readonly id="sinadi-kategori" value="<?= esc($initialKategori, 'attr'); ?>">

      <!-- SECTION 1: INFORMASI KEGIATAN & ANGGARAN -->
      <div class="sf-card">
        <div class="sf-card-header">
          <h5 class="sf-card-title">
            <span class="sf-step-num">1</span>
            Informasi Kegiatan &amp; Anggaran
          </h5>
          <span class="badge badge-info px-2 py-1" id="sfKategoriBadge">
            <i class="fas fa-users mr-1"></i>Kategori: <?= esc($initialKategori); ?>
          </span>
        </div>
        <div class="sf-card-body">
          <div class="row">
            <div class="col-md-8 form-group mb-3">
              <label class="sf-label">Nama Kegiatan Perjalanan Dinas <span class="text-danger">*</span></label>
              <input class="form-control"
                     type="text"
                     name="nama_kegiatan"
                     id="sfNamaKegiatan"
                     value="<?= esc($perjalananData['nama_kegiatan'] ?? '', 'attr'); ?>"
                     placeholder="Contoh: Pengumpulan Data Surveilans Epidemiologi Penyakit di Pintu Masuk Wilker"
                     required>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="sf-label">Tempat Tujuan Perjalanan Dinas <span class="text-danger">*</span></label>
              <input class="form-control"
                     type="text"
                     name="tempat_perjalanan"
                     id="sfTempatPerjalanan"
                     value="<?= esc($perjalananData['tempat_perjalanan'] ?? '', 'attr'); ?>"
                     placeholder="Contoh: Puskesmas Pesisir Kota Cirebon"
                     required>
            </div>

            <div class="col-md-3 col-6 form-group mb-3">
              <label class="sf-label">Tanggal Mulai Pelaksanaan <span class="text-danger">*</span></label>
              <input class="form-control"
                     type="date"
                     name="tgl_pelaksanaan"
                     id="sfTglMulai"
                     value="<?= esc($perjalananData['tgl_pelaksanaan'] ?? '', 'attr'); ?>"
                     required>
            </div>

            <div class="col-md-3 col-6 form-group mb-3">
              <label class="sf-label">Tanggal Selesai Pelaksanaan <span class="text-danger">*</span></label>
              <input class="form-control"
                     type="date"
                     name="tgl_pelaksanaan_akhir"
                     id="sfTglSelesai"
                     value="<?= esc($perjalananData['tgl_pelaksanaan_akhir'] ?? '', 'attr'); ?>"
                     required>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="sf-label">Mata Anggaran (MAK) <span class="text-danger">*</span></label>
              <input class="form-control"
                     type="text"
                     name="mata_anggaran"
                     value="<?= esc($perjalananData['mata_anggaran'] ?? '', 'attr'); ?>"
                     placeholder="Kode MAK DIPA / Non-DIPA"
                     required>
            </div>

            <div class="col-md-2 form-group mb-3">
              <label class="sf-label">Sumber Anggaran <span class="text-danger">*</span></label>
              <select class="form-control" name="sumber_anggaran" required>
                <option value="APBN" <?= $sumberVal === 'APBN' ? 'selected' : ''; ?>>APBN</option>
                <option value="RPL" <?= $sumberVal === 'RPL' ? 'selected' : ''; ?>>RPL</option>
              </select>
            </div>

            <!-- Upload Berkas -->
            <div class="col-md-<?= ($isDalamKota && !$isEdit) ? '6' : '12'; ?> form-group mb-2">
              <label class="sf-label">
                Upload Berkas Perjalanan Dinas (PDF) <?= $isEdit ? '<small class="text-muted font-weight-normal">(Kosongkan jika tidak diganti)</small>' : '<span class="text-danger">*</span>'; ?>
              </label>
              <div class="custom-file">
                <input type="file"
                       class="custom-file-input"
                       name="berkas"
                       id="inputGroupFile11"
                       <?= $isEdit ? '' : 'required'; ?>>
                <label class="custom-file-label" for="inputGroupFile11" data-browse="Pilih Berkas">
                  <?= ($isEdit && !empty($perjalananData['berkas'])) ? ('Berkas saat ini: ' . esc($perjalananData['berkas'])) : 'Pilih file berkas perjalanan dinas...'; ?>
                </label>
              </div>
            </div>

            <!-- Monitoring Sanitasi Kapal (Khusus Tambah Dalam Kota) -->
            <?php if ($isDalamKota && !$isEdit) : ?>
              <div class="col-md-6 form-group mb-2">
                <label class="sf-label">Integrasi Monitoring Sanitasi Kapal</label>
                <div class="p-2 border rounded bg-light">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="monitoringSanitasiKapalToggle" name="monitoring_sanitasi_kapal" value="1">
                    <label class="custom-control-label font-weight-bold small" for="monitoringSanitasiKapalToggle">
                      Aktifkan Laporan Pengawasan Sanitasi Kapal
                    </label>
                  </div>

                  <div id="monitoringSanitasiKapalFields" class="mt-3 pt-2 border-top d-none">
                    <div class="form-group mb-2">
                      <label class="sf-label">Nama Kapal <span class="text-danger">*</span></label>
                      <input type="text" class="form-control form-control-sm" name="nama_kapal_monitoring" placeholder="Masukkan Nama Kapal">
                    </div>
                    <div class="form-group mb-2">
                      <label class="sf-label">Output Dokumen</label>
                      <div class="d-flex flex-wrap" style="gap: 12px;">
                        <?php foreach (['COP', 'PHQC', 'SSCC', 'SSCEC', 'P3K'] as $docType) : ?>
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check<?= $docType; ?>_sinadi" name="jenis_dokumen[]" value="<?= $docType; ?>">
                            <label class="custom-control-label small" for="check<?= $docType; ?>_sinadi"><?= $docType; ?></label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <div class="form-group mb-0">
                      <label class="sf-label">Upload Bukti Geotagging (PNG/JPG maks 5MB) <span class="text-danger">*</span></label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="bukti_geotagging_monitoring" accept="image/png, image/jpeg">
                        <label class="custom-file-label">Pilih file gambar...</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <!-- Hidden legacy Hasil Kegiatan fields for controller compatibility -->
            <div class="d-none">
              <input type="radio" name="selectHasilKegiatan" value="99" checked>
              <input type="file" name="file_hasil_kegiatan">
              <textarea name="hasil_kegiatan"><?= esc($perjalananData['catatan_kegiatan'] ?? ''); ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 2: DATA PESERTA & RINCIAN BIAYA -->
      <div class="sf-card">
        <div class="sf-card-header">
          <h5 class="sf-card-title">
            <span class="sf-step-num">2</span>
            Daftar Peserta &amp; Rincian Biaya Perjalanan
          </h5>
          <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
            <button type="button"
                    class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1"
                    id="btnSyncAllPeserta"
                    title="Salin Tanggal, Rute, No. ST, dan Tarif Satuan dari Pegawai No 1 ke seluruh peserta lain"
                    style="border-radius: 7px;">
              <i class="fas fa-copy mr-1"></i>Samakan Jadwal, ST &amp; Tarif Peserta
            </button>
            <button type="button"
                    class="btn btn-xs btn-info font-weight-bold px-3 py-1 sinadi-btn-tambah-data"
                    style="border-radius: 7px;">
              <i class="fas fa-user-plus mr-1"></i>Tambah Pegawai
            </button>
          </div>
        </div>

        <div class="sf-card-body">
          <!-- Peserta Pertama (Master Template untuk Cloning) -->
          <div class="sinadi-data-pegawai">
            <?php $renderPesertaBlock($pesertaList[0], 0); ?>
          </div>

          <!-- Peserta Tambahan (Pegawai 2..N) -->
          <div class="data-pegawai-tambahan">
            <?php foreach ($pesertaList as $idx => $pItem) : ?>
              <?php if ($idx > 0) : ?>
                <?php $renderPesertaBlock($pItem, $idx); ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div id="btnPlusPegawai" class="mt-2">
            <button type="button" class="btn btn-outline-info btn-block font-weight-bold py-2 sinadi-btn-tambah-data" style="border-radius: 10px; border-width: 1.5px; border-style: dashed;">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Data Pegawai Lainnya
            </button>
          </div>

          <!-- Ringkasan Total Biaya Live -->
          <div class="sf-grand-bar">
            <div class="sf-grand-items">
              <div>
                <div class="sf-grand-lbl">Jumlah Peserta</div>
                <div class="sf-grand-val" id="sfSumPeserta"><?= count($pesertaList); ?> Orang</div>
              </div>
              <div>
                <div class="sf-grand-lbl">Total Transport</div>
                <div class="sf-grand-val" id="sfSumTransport">Rp 0</div>
              </div>
              <div>
                <div class="sf-grand-lbl">Total Uang Harian</div>
                <div class="sf-grand-val" id="sfSumHarian">Rp 0</div>
              </div>
              <div>
                <div class="sf-grand-lbl">Total Penginapan</div>
                <div class="sf-grand-val" id="sfSumPenginapan">Rp 0</div>
              </div>
            </div>
            <div class="text-right">
              <div class="sf-grand-lbl">Total Estimasi Transfer</div>
              <div class="sf-grand-total-val" id="sfSumGrandTotal">Rp 0</div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 3: PEJABAT PENANDATANGAN & PIC -->
      <div class="sf-card">
        <div class="sf-card-header">
          <h5 class="sf-card-title">
            <span class="sf-step-num">3</span>
            Pejabat Penandatangan &amp; Penanggung Jawab (PIC)
          </h5>
          <small class="text-muted">Pejabat otomatis mengikuti pengaturan kantor aktif</small>
        </div>
        <div class="sf-card-body">
          <!-- Info Kantor & 4 Pejabat Read-Only (Ringan, 0 Select2 Overhead) -->
          <div class="sf-pejabat-grid">
            <div class="sf-pejabat-item" style="grid-column: 1 / -1;">
              <div class="sf-pejabat-role"><i class="fas fa-building mr-1"></i>Satuan Kerja / Kantor</div>
              <div class="sf-pejabat-name"><?= esc($aliasKantor ? ($aliasKantor . ' — ' . $namaKantor) : $namaKantor); ?></div>
            </div>
            <div class="sf-pejabat-item">
              <div class="sf-pejabat-role">Kepala Kantor</div>
              <div class="sf-pejabat-name"><?= esc($labelKepalaKantor); ?></div>
            </div>
            <div class="sf-pejabat-item">
              <div class="sf-pejabat-role">Pejabat Pembuat Komitmen (PPK)</div>
              <div class="sf-pejabat-name"><?= esc($labelPpk); ?></div>
            </div>
            <div class="sf-pejabat-item">
              <div class="sf-pejabat-role">Kepala Sub Bagian Adm. Umum</div>
              <div class="sf-pejabat-name"><?= esc($labelKasubag); ?></div>
            </div>
            <div class="sf-pejabat-item">
              <div class="sf-pejabat-role">Bendahara Pengeluaran</div>
              <div class="sf-pejabat-name"><?= esc($labelBendahara); ?></div>
            </div>
          </div>

          <!-- Pilihan PIC Perjalanan Dinas -->
          <div class="row align-items-end">
            <div class="col-md-9 form-group mb-0">
              <label class="sf-label">Pilih PIC Perjalanan Dinas <span class="text-danger">*</span></label>
              <?php
              $currentPicNip = (string) ($perjalananData['pic_nip'] ?? '');
              $currentPicNama = (string) ($perjalananData['pic'] ?? '');
              $foundPicInMaster = false;
              ?>
              <select class="form-control select2 pic" name="sinadi_pic" id="sfSelectPic" required style="width: 100%;">
                <option value="">-- Pilih NIP / Nama PIC --</option>
                <?php foreach ($pegawai as $data) : ?>
                  <?php
                  $isPicSelected = ($currentPicNip !== '' && $currentPicNip === (string) $data['nip']);
                  if ($isPicSelected) {
                    $foundPicInMaster = true;
                  }
                  ?>
                  <option value="<?= esc($data['nip'], 'attr'); ?>"
                          data-nama="<?= esc($data['nama'], 'attr'); ?>"
                          data-jabatan="<?= esc($data['jabatan'], 'attr'); ?>"
                          data-golongan="<?= esc($data['golongan'], 'attr'); ?>"
                          <?= $isPicSelected ? 'selected' : ''; ?>>
                    <?= esc($data['nip'] . ' | ' . $data['nama']); ?>
                  </option>
                <?php endforeach; ?>
                <?php if ($isEdit && !$foundPicInMaster && $currentPicNip !== '') : ?>
                  <option value="<?= esc($currentPicNip, 'attr'); ?>"
                          data-nama="<?= esc($currentPicNama, 'attr'); ?>"
                          selected>
                    <?= esc($currentPicNip . ' | ' . $currentPicNama); ?>
                  </option>
                <?php endif; ?>
              </select>
              <input type="hidden" name="pic_nama" value="<?= esc($currentPicNama, 'attr'); ?>">
              <input type="hidden" name="pic_nip" value="<?= esc($currentPicNip, 'attr'); ?>">
            </div>

            <div class="col-md-3 mt-2 mt-md-0">
              <button type="button"
                      class="btn btn-outline-secondary btn-sm btn-block py-2 font-weight-bold"
                      id="btnUseFirstAsPic"
                      style="border-radius: 9px;">
                <i class="fas fa-user-check mr-1"></i> Gunakan Pegawai 1
              </button>
            </div>
          </div>

          <div class="d-flex flex-wrap align-items-center justify-content-end pt-3 mt-3 border-top" style="gap: 10px;">
            <a href="<?= $backUrl; ?>" class="btn btn-light border font-weight-bold px-4 py-2" style="border-radius: 10px;">
              Batal
            </a>
            <button type="submit" class="btn btn-success font-weight-bold px-5 py-2" style="border-radius: 10px; background: #0d9488; border-color: #0d9488;">
              <i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Simpan Perubahan Data' : 'Simpan Data Perjalanan Dinas'; ?>
            </button>
          </div>
        </div>
      </div>

    </form>

  </div>
</section>

<script>
  function parseRupiahNumber(raw) {
    if (raw === null || raw === undefined) return 0;
    var s = String(raw).replace(/\./g, '').replace(/,/g, '.').replace(/[^0-9.]/g, '');
    var n = parseFloat(s);
    return isNaN(n) ? 0 : n;
  }

  function formatRupiahDisplay(num) {
    return 'Rp ' + Math.round(num || 0).toLocaleString('id-ID');
  }

  function updateSinadiGrandSummary() {
    var totalPeserta = $('.sf-peserta-block').length;
    var sumTransport = 0;
    var sumHarian = 0;
    var sumPenginapan = 0;
    var sumTransfer = 0;

    $('.transport_jumlah').each(function() { sumTransport += parseRupiahNumber($(this).val()); });
    $('.harian_jumlah').each(function() { sumHarian += parseRupiahNumber($(this).val()); });
    $('.penginapan_jumlah').each(function() { sumPenginapan += parseRupiahNumber($(this).val()); });
    $('.jumlah_transfer').each(function() { sumTransfer += parseRupiahNumber($(this).val()); });

    $('#sfSumPeserta').text(totalPeserta + ' Orang');
    $('#sfSumTransport').text(formatRupiahDisplay(sumTransport));
    $('#sfSumHarian').text(formatRupiahDisplay(sumHarian));
    $('#sfSumPenginapan').text(formatRupiahDisplay(sumPenginapan));
    $('#sfSumGrandTotal').text(formatRupiahDisplay(sumTransfer));
  }

  function sinadiPerhitunganAnggaran(e) {
    var $row = $(e).closest('.perhitungan_anggaran');
    if (!$row.length) return;

    var tHari = parseRupiahNumber($row.find('.transport_hari').val());
    var tNom  = parseRupiahNumber($row.find('.transport_transport').val());
    var hHari = parseRupiahNumber($row.find('.harian_hari').val());
    var hNom  = parseRupiahNumber($row.find('.harian_unit_cost').val());
    var pKali = parseRupiahNumber($row.find('.penginapan_kali').val());
    var pNom  = parseRupiahNumber($row.find('.penginapan_unit_cost').val());

    var tSub = tHari * tNom;
    var hSub = hHari * hNom;
    var pSub = pKali * pNom;
    var total = tSub + hSub + pSub;

    $row.find('.transport_jumlah').val('Rp' + tSub.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
    $row.find('.harian_jumlah').val('Rp' + hSub.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
    $row.find('.penginapan_jumlah').val('Rp' + pSub.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
    $row.find('.jumlah_transfer').val('Rp' + total.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));

    var val = String($(e).val() || '').replace(/[^0-9,]/g, '');
    val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    $(e).val(val);

    updateSinadiGrandSummary();
  }

  function hitungBanyakData() {
    var $blocks = $('.sf-peserta-block');
    var count = $blocks.length;
    var kat = (count <= 1) ? 'Perseorangan' : 'Kelompok';

    $('#sinadi-kategori').val(kat);
    $('#sfKategoriBadge').html('<i class="fas fa-users mr-1"></i>Kategori: ' + kat + ' (' + count + ' Orang)');

    $blocks.each(function(i) {
      var $b = $(this);
      $b.find('.sf-peserta-title-text').text('PEGAWAI NO ' + (i + 1));
      var $delBtn = $b.find('.btn-info-pegawai');
      if (i === 0) {
        $delBtn.addClass('d-none');
      } else {
        $delBtn.removeClass('d-none btn-secondary').addClass('btn-danger');
      }
    });

    updateSinadiGrandSummary();
  }

  function sinadiToggleHideDataPegawai(button) {
    var $block = $(button).closest('.sf-peserta-block');
    var $autoRow = $block.find('.autoInput');
    var $manualRow = $block.find('.manualInput');
    var isManualVisible = $manualRow.is(':visible');

    if (isManualVisible) {
      // Kembali ke pilih dari daftar
      $manualRow.hide();
      $manualRow.find('input').prop('disabled', true).val('');
      $autoRow.show();
      $autoRow.find('input').prop('disabled', false);
      $autoRow.find('select').prop('required', true);
      $(button).html('<i class="fas fa-keyboard mr-1"></i>Input Manual Pegawai');
    } else {
      // Beralih ke input manual
      $autoRow.hide();
      $autoRow.find('input').prop('disabled', true).val('');
      $autoRow.find('select').prop('required', false).val('').trigger('change.select2');
      $manualRow.show();
      $manualRow.find('input').prop('disabled', false).val('');
      $(button).html('<i class="fas fa-list-ul mr-1"></i>Pilih dari Daftar Pegawai');
    }
  }

  function sinadiCloneDataValue(count) {
    var $first = $('.sf-peserta-block').eq(0);
    var $target = $('.sf-peserta-block').eq(count);
    if (!$first.length || !$target.length) return;

    // Salin tanggal pergi, pulang, surat tugas, sppd dari pegawai 1
    $target.find('input[name="tgl_pergi[]"]').val($first.find('input[name="tgl_pergi[]"]').val());
    $target.find('input[name="tgl_pulang[]"]').val($first.find('input[name="tgl_pulang[]"]').val());
    $target.find('input[name="no_surat_tugas[]"]').val($first.find('input[name="no_surat_tugas[]"]').val());
    $target.find('input[name="tgl_surat_tugas[]"]').val($first.find('input[name="tgl_surat_tugas[]"]').val());
    $target.find('input[name="no_sppd[]"]').val($first.find('input[name="no_sppd[]"]').val());
    $target.find('input[name="tgl_sppd[]"]').val($first.find('input[name="tgl_sppd[]"]').val());

    // Salin rute asal & tujuan
    <?php if ($isDalamKota) : ?>
      var asalVal = $first.find('input[name="kota_asal[]"]').val();
      var tujuanVal = $first.find('input[name="kota[]"]').val();
      $target.find('input[name="kota_asal[]"]').val(asalVal);
      $target.find('input[name="kota[]"]').val(tujuanVal);
      $target.find('input[name="kota_tujuan[]"]').val(tujuanVal);
    <?php else : ?>
      var asalKota = $first.find('select[name="kota_asal[]"]').val();
      if (asalKota) {
        $target.find('select[name="kota_asal[]"]').val(asalKota).trigger('change');
      }
      var tujuanKota = $first.find('select[name="kota[]"]').val();
      if (tujuanKota) {
        $target.find('select[name="kota[]"]').val(tujuanKota).trigger('change');
      }
    <?php endif; ?>

    var transVal = $first.find('select[name="transportasi[]"]').val();
    if (transVal) {
      $target.find('select[name="transportasi[]"]').val(transVal).trigger('change');
    }

    // Salin tarif anggaran dari pegawai 1 dan hitung otomatis
    $target.find('.transport_hari').val($first.find('.transport_hari').val());
    $target.find('.transport_transport').val($first.find('.transport_transport').val());
    $target.find('.harian_hari').val($first.find('.harian_hari').val());
    $target.find('.harian_unit_cost').val($first.find('.harian_unit_cost').val());
    $target.find('.penginapan_kali').val($first.find('.penginapan_kali').val());
    $target.find('.penginapan_unit_cost').val($first.find('.penginapan_unit_cost').val());
    sinadiPerhitunganAnggaran($target.find('.transport_hari')[0]);
  }

  function sinadiHapusPeserta(e, id) {
    Swal.fire({
      title: 'Hapus Peserta Ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/sinadi/dlt/sinadiHapusPeserta/' + id,
          method: 'DELETE'
        }).done(function() {
          $(e).closest('.sf-peserta-block').remove();
          hitungBanyakData();
          Toast.fire({ icon: 'success', title: 'Peserta berhasil dihapus.' });
        }).fail(function() {
          Swal.fire('Error', 'Gagal menghapus peserta dari database.', 'error');
        });
      }
    });
  }

  function cekTambahPIC() {
    $('.manualInput').each(function(i) {
      var mNip = $(this).find('.manualNip').val();
      var mNama = $(this).find('.manualNama').val();
      var mJab = $(this).find('.manualJabatan').val();
      var mGol = $(this).find('.manualGolongan').val();
      if (mNip && mNama && mJab && mGol) {
        var $existing = $('.pic option[data-manual="' + i + '"]');
        if ($existing.length) {
          $existing.val(mNip).text(mNip + ' | ' + mNama).attr('data-nama', mNama);
        } else {
          var opt = new Option(mNip + ' | ' + mNama, mNip, false, false);
          $(opt).attr({
            'data-manual': i,
            'data-nama': mNama,
            'data-jabatan': mJab,
            'data-golongan': mGol
          });
          $('.pic').append(opt).trigger('change');
        }
      }
    });
  }

  $(document).ready(function() {
    // Format & hitung nilai awal anggaran (khusus mode Edit)
    $('.perhitungan_anggaran').each(function() {
      $(this).find('.anggaran').each(function() {
        if ($(this).val() !== '') {
          $(this).val(String($(this).val()).replace('.', ','));
        }
      });
      var firstInput = $(this).find('.anggaran')[0];
      if (firstInput) {
        sinadiPerhitunganAnggaran(firstInput);
      }
    });
    updateSinadiGrandSummary();

    // Auto-fill tanggal pergi/pulang peserta 1 saat tanggal pelaksanaan kegiatan dipilih
    $('#sfTglMulai').on('change', function() {
      var val = $(this).val();
      if (!val) return;
      if (!$('#sfTglSelesai').val()) {
        $('#sfTglSelesai').val(val);
      }
      var $p1Pergi = $('input[name="tgl_pergi[]"]').eq(0);
      if (!$p1Pergi.val()) $p1Pergi.val(val);
      var $p1St = $('input[name="tgl_surat_tugas[]"]').eq(0);
      if (!$p1St.val()) $p1St.val(val);
      var $p1Sppd = $('input[name="tgl_sppd[]"]').eq(0);
      if (!$p1Sppd.val()) $p1Sppd.val(val);
    });

    $('#sfTglSelesai').on('change', function() {
      var val = $(this).val();
      if (!val) return;
      var $p1Pulang = $('input[name="tgl_pulang[]"]').eq(0);
      if (!$p1Pulang.val()) $p1Pulang.val(val);
    });

    <?php if ($isDalamKota) : ?>
    $('#sfTempatPerjalanan').on('change', function() {
      var val = $(this).val();
      var $p1Kota = $('input[name="kota[]"]').eq(0);
      if (val && !$p1Kota.val()) {
        $p1Kota.val(val).trigger('change');
      }
    });
    <?php endif; ?>

    // Pilih PIC
    $(document).on('change', 'select[name="sinadi_pic"]', function() {
      var selectedOption = $(this).find('option:selected');
      $('input[name="pic_nama"]').val(selectedOption.data('nama') || '');
      $('input[name="pic_nip"]').val($(this).val() || '');
    });

    // Tombol cepat: Gunakan Pegawai 1 sebagai PIC
    $('#btnUseFirstAsPic').on('click', function() {
      var firstNip = $('select[name="nip[]"]').eq(0).val();
      if (firstNip) {
        $('#sfSelectPic').val(firstNip).trigger('change');
        Toast.fire({ icon: 'success', title: 'PIC disamakan dengan Pegawai No 1' });
      } else {
        Toast.fire({ icon: 'info', title: 'Pilih Pegawai No 1 terlebih dahulu' });
      }
    });

    // Tombol cepat: Samakan Jadwal, ST & Tarif ke semua peserta
    $('#btnSyncAllPeserta').on('click', function() {
      var total = $('.sf-peserta-block').length;
      if (total <= 1) {
        Toast.fire({ icon: 'info', title: 'Tambahkan pegawai ke-2 terlebih dahulu untuk menyalin jadwal.' });
        return;
      }
      for (var i = 1; i < total; i++) {
        sinadiCloneDataValue(i);
      }
      Toast.fire({ icon: 'success', title: 'Jadwal, No. ST, dan Tarif berhasil disamakan ke ' + total + ' peserta!' });
    });

    // Pilih Pegawai (NIP) -> auto-fill Nama, Jabatan, Golongan & cegah duplikasi
    $(document).on('change', 'select[name="nip[]"]', function() {
      var $select = $(this);
      var $block = $select.closest('.sf-peserta-block');
      var selectedOption = $select.find('option:selected');
      var val = $select.val();

      if (val) {
        var dupCount = 0;
        $('select[name="nip[]"]').each(function() {
          if ($(this).val() === val) dupCount++;
        });
        if (dupCount > 1) {
          Swal.fire({
            title: 'Pegawai Sudah Terpilih',
            html: 'Pegawai dengan NIP <strong>' + val + '</strong> sudah ada pada daftar peserta.',
            icon: 'info'
          });
          $select.val('').trigger('change.select2');
          $block.find('.nama, .jabatan, .golongan').val('');
          return;
        }
      }

      $block.find('.nama').val(selectedOption.data('nama') || '');
      $block.find('.jabatan').val(selectedOption.data('jabatan') || '');
      $block.find('.golongan').val(selectedOption.data('golongan') || '');

      // Jika PIC masih kosong dan ini Pegawai 1, otomatis set sebagai PIC
      if ($('.sf-peserta-block').index($block) === 0 && val && !$('#sfSelectPic').val()) {
        $('#sfSelectPic').val(val).trigger('change');
      }
    });

    // Input perhitungan anggaran
    $(document).on('input', '.anggaran', function() {
      sinadiPerhitunganAnggaran(this);
    });

    // Sinkronisasi kota_tujuan[]
    $(document).on('change', 'input[name="kota[]"]', function() {
      $(this).prev('input[name="kota_tujuan[]"]').val($(this).val());
    });

    $(document).on('change', '.kota_tujuan_select', function() {
      var selectedValues = $(this).val();
      var $inputHidden = $(this).prev('.kota_tujuan_input');
      if (selectedValues) {
        $inputHidden.val(Array.isArray(selectedValues) ? selectedValues.join(',') : selectedValues);
      } else {
        $inputHidden.val('');
      }
    });

    // Tambah blok pegawai baru (hanya inisialisasi Select2 pada blok baru!)
    $('.sinadi-btn-tambah-data').on('click', function() {
      var $firstBlock = $('.sinadi-data-pegawai .sf-peserta-block').first();
      var $clone = $firstBlock.clone();

      // Bersihkan DOM Select2 hasil clone dan reset nilai pegawai
      $clone.find('span.select2').remove();
      $clone.find('select.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id tabindex aria-hidden');
      $clone.find('select.select2 option').removeAttr('data-select2-id');

      $clone.find('.autoInput').show();
      $clone.find('.manualInput').hide();
      $clone.find('.manualInput input').prop('disabled', true).val('');
      $clone.find('.autoInput input').prop('disabled', false);
      $clone.find('select[name="nip[]"]').prop('required', true).val('');
      $clone.find('.nama, .jabatan, .golongan').val('');
      $clone.find('.btn-info-pegawai').attr('data-peserta', '');

      $('.data-pegawai-tambahan').append($clone);

      // Inisialisasi Select2 HANYA pada blok yang baru ditambahkan (jauh lebih cepat!)
      $clone.find('select.select2').select2();

      hitungBanyakData();
      var newIndex = $('.sf-peserta-block').length - 1;
      sinadiCloneDataValue(newIndex);
    });

    // Hapus blok pegawai
    $(document).on('click', '.btn-info-pegawai', function() {
      var idPeserta = $(this).attr('data-peserta') || '';
      var $blocks = $('.sf-peserta-block');
      if ($blocks.length <= 1) return;

      if (idPeserta === '') {
        $(this).closest('.sf-peserta-block').remove();
        hitungBanyakData();
      } else {
        sinadiHapusPeserta(this, idPeserta);
      }
    });

    // Input manual pegawai -> tambahkan ke opsi PIC
    $(document).on('change', '.manualNip, .manualNama, .manualJabatan, .manualGolongan', cekTambahPIC);

    // Toggle Monitoring Sanitasi Kapal
    $('#monitoringSanitasiKapalToggle').on('change', function() {
      if (this.checked) {
        $('#monitoringSanitasiKapalFields').removeClass('d-none');
        $('input[name="nama_kapal_monitoring"], input[name="bukti_geotagging_monitoring"]').prop('required', true);
      } else {
        $('#monitoringSanitasiKapalFields').addClass('d-none');
        $('input[name="nama_kapal_monitoring"], input[name="bukti_geotagging_monitoring"]').prop('required', false);
      }
    });

    // Custom file label
    $(document).on('change', '.custom-file-input', function() {
      var fileName = ($(this).val() || '').split('\\').pop();
      $(this).next('.custom-file-label').addClass('selected').html(fileName || 'Pilih file...');
    });
  });
</script>
