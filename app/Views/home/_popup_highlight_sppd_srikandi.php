<?php
$justLoggedInHighlight = !empty($justLoggedInSppdHighlight);
?>
<!-- Pop-up Highlight Preview Fitur Baru SPPD Srikandi (Compact) -->
<style>
  #modalHighlightSppdSrikandi {
    position: fixed !important;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1060 !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
  }
  #modalHighlightSppdSrikandi .modal-dialog {
    max-width: 800px;
    max-height: none !important;
    height: auto !important;
    margin: 1rem auto 1.5rem auto !important;
  }
  #modalHighlightSppdSrikandi .modal-content {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.26);
    max-height: none !important;
    height: auto !important;
  }
  #modalHighlightSppdSrikandi .modal-body {
    max-height: none !important;
    height: auto !important;
    overflow: visible !important;
    background: #f8fafc;
    padding: 12px 14px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-banner {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    color: #ffffff;
    padding: 12px 16px;
    position: relative;
  }
  #modalHighlightSppdSrikandi .sppd-hl-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.28);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 2px 8px;
    border-radius: 999px;
    margin-bottom: 4px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-title {
    font-size: 1.08rem;
    font-weight: 800;
    margin: 0 0 2px;
    color: #ffffff;
    padding-right: 34px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-subtitle {
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
  }
  #modalHighlightSppdSrikandi .sppd-hl-close {
    position: absolute;
    top: 10px;
    right: 12px;
    width: 28px;
    height: 28px;
    border-radius: 999px;
    border: none;
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff;
    font-size: 1.05rem;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.16s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-close:hover {
    background: rgba(255, 255, 255, 0.32);
  }
  #modalHighlightSppdSrikandi .sppd-hl-chips {
    margin-bottom: 10px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-chip {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    padding: 6px 8px;
    height: 100%;
    display: flex;
    align-items: center;
    gap: 7px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-chip-icon {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: rgba(13, 148, 136, 0.12);
    color: #0d9488;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    flex-shrink: 0;
  }
  #modalHighlightSppdSrikandi .sppd-hl-chip-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.15;
  }
  #modalHighlightSppdSrikandi .sppd-hl-chip-sub {
    font-size: 10.5px;
    color: #64748b;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-wrap {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
  }
  #modalHighlightSppdSrikandi .sppd-hl-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px 10px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tabs {
    display: flex;
    gap: 4px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tab-btn {
    border: none;
    background: transparent;
    color: #475569;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tab-btn.active {
    background: #0d9488;
    color: #ffffff;
    box-shadow: 0 1px 4px rgba(13, 148, 136, 0.25);
  }
  #modalHighlightSppdSrikandi .sppd-hl-sim-group {
    display: none;
    align-items: center;
    gap: 4px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-sim-group.active {
    display: inline-flex;
  }
  #modalHighlightSppdSrikandi .sppd-hl-pill-btn {
    border: 1px solid #0d9488;
    background: #ffffff;
    color: #0f766e;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.14s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-pill-btn.active {
    background: #0d9488;
    color: #ffffff;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-pane {
    display: none;
    padding: 10px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-pane.active {
    display: block;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    font-size: 11px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-head {
    background: #28a745;
    color: #ffffff;
    font-weight: 700;
    font-size: 11.5px;
    padding: 5px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-body {
    padding: 8px 10px;
    background: #f8fafc;
  }
  #modalHighlightSppdSrikandi .sppd-mock-datebar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #bbf7d0;
    border-left: 3px solid #28a745;
    border-radius: 5px;
    padding: 4px 8px;
    margin-bottom: 6px;
    font-size: 10.5px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    margin-bottom: 6px;
    overflow: hidden;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card:last-child {
    margin-bottom: 0;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-head {
    background: #f1f5f9;
    padding: 3px 8px;
    font-weight: 700;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 10.5px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-body {
    padding: 6px 8px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-label {
    font-size: 9.5px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 1px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-input {
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 3px 6px;
    background: #ffffff;
    color: #1e293b;
    font-size: 10.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  #modalHighlightSppdSrikandi .sppd-mock-input.is-manual {
    background: #fefce8;
    border-color: #fde047;
  }
  #modalHighlightSppdSrikandi .sppd-mock-date-strip {
    margin-top: 4px;
    font-size: 10px;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 2px 6px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-date-manual {
    display: none;
    margin-top: 4px;
    gap: 6px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal[data-date-mode="manual"] .sppd-mock-date-strip {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal[data-date-mode="manual"] .sppd-mock-date-manual {
    display: flex;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 5px 10px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 6px;
    font-size: 10.5px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper-scroll {
    overflow-x: auto;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper {
    min-width: 580px;
    background: #ffffff;
    border: 1px solid #64748b;
    border-radius: 5px;
    padding: 7px 9px;
    font-family: "Times New Roman", Times, serif;
    font-size: 10.5px;
    color: #000000;
  }
  #modalHighlightSppdSrikandi .sppd-mock-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }
  #modalHighlightSppdSrikandi .sppd-mock-table td {
    border: 1px solid #1e293b;
    padding: 3px 6px;
    vertical-align: top;
    width: 50%;
    line-height: 1.22;
  }
  #modalHighlightSppdSrikandi .sppd-mock-stop-header {
    min-height: 36px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-pejabat-box {
    min-height: 52px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    padding-top: 2px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-pejabat-box.is-empty {
    justify-content: center;
    background: repeating-linear-gradient(
      -45deg,
      #ffffff,
      #ffffff 5px,
      #f8fafc 5px,
      #f8fafc 10px
    );
  }
  #modalHighlightSppdSrikandi .sppd-mock-ttd {
    font-family: monospace;
    font-size: 9.5px;
    color: #0f766e;
    background: #f0fdfa;
    display: inline-block;
    padding: 1px 5px;
    border-radius: 3px;
    border: 1px dashed #5eead4;
    margin: 4px 0;
  }
  #modalHighlightSppdSrikandi .sppd-mock-empty-note {
    font-family: sans-serif;
    font-size: 9.5px;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    background: #ffffff;
    padding: 2px 7px;
    border-radius: 999px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper[data-pdf-mode="3"] .sppd-pdf-only-1 {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper[data-pdf-mode="1"] .sppd-pdf-only-3 {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-hl-caption {
    margin-top: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #0f766e;
    text-align: center;
  }
  #modalHighlightSppdSrikandi .sppd-hl-steps {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 10px;
    margin-top: 8px;
    font-size: 11.5px;
    color: #334155;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-step-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 999px;
    background: #0d9488;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    margin-right: 4px;
  }
  #modalHighlightSppdSrikandi .modal-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 9px 14px;
  }
</style>

<div class="modal fade" id="modalHighlightSppdSrikandi" role="dialog" aria-labelledby="modalHighlightSppdSrikandiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="sppd-hl-banner">
        <button type="button" class="sppd-hl-close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="sppd-hl-badge"><i class="fas fa-star"></i> FITUR BARU &bull; SINADI DINAS LUAR KOTA</div>
        <h5 class="sppd-hl-title" id="modalHighlightSppdSrikandiLabel">SPPD Srikandi Multi-Pejabat Tujuan (Baris II–IV)</h5>
        <p class="sppd-hl-subtitle">Cetak borang SPPD Srikandi hingga 3 pejabat tujuan, opsi tanggal &amp; pegawai database/manual, rapi pas 2 halaman.</p>
      </div>

      <div class="modal-body">
        <!-- Strip 4 Chip Fitur Compact -->
        <div class="row no-gutters sppd-hl-chips" style="margin-left: -4px; margin-right: -4px;">
          <div class="col-6 col-md-3 px-1 mb-1 mb-md-0">
            <div class="sppd-hl-chip">
              <div class="sppd-hl-chip-icon"><i class="fas fa-users-cog"></i></div>
              <div style="min-width: 0;">
                <div class="sppd-hl-chip-title">Maks. 3 Pejabat (II–IV)</div>
                <div class="sppd-hl-chip-sub"><code>${ttd_pengirim3}</code>–<code>5</code> otomatis</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 px-1 mb-1 mb-md-0">
            <div class="sppd-hl-chip">
              <div class="sppd-hl-chip-icon"><i class="fas fa-address-book"></i></div>
              <div style="min-width: 0;">
                <div class="sppd-hl-chip-title">Database &amp; Manual</div>
                <div class="sppd-hl-chip-sub">Select2 / ketik manual</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 px-1">
            <div class="sppd-hl-chip">
              <div class="sppd-hl-chip-icon"><i class="fas fa-calendar-alt"></i></div>
              <div style="min-width: 0;">
                <div class="sppd-hl-chip-title">Tanggal Auto / Manual</div>
                <div class="sppd-hl-chip-sub">Urut otomatis / isi sendiri</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 px-1">
            <div class="sppd-hl-chip">
              <div class="sppd-hl-chip-icon"><i class="fas fa-file-pdf"></i></div>
              <div style="min-width: 0;">
                <div class="sppd-hl-chip-title">PDF Rapi 2 Halaman</div>
                <div class="sppd-hl-chip-sub">Tinggi baris seragam</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Area Preview Compact dengan Toolbar Gabungan -->
        <div class="sppd-hl-preview-wrap">
          <div class="sppd-hl-toolbar">
            <div class="sppd-hl-tabs">
              <button type="button" class="sppd-hl-tab-btn active" data-preview-tab="form">
                <i class="fas fa-window-restore mr-1"></i> 1. Form Modal
              </button>
              <button type="button" class="sppd-hl-tab-btn" data-preview-tab="pdf">
                <i class="fas fa-print mr-1"></i> 2. Hasil Cetak SPPD
              </button>
            </div>
            <div class="sppd-hl-sim-group active" data-toolbar-for="form">
              <span class="text-muted mr-1" style="font-size: 10.5px;">Mode:</span>
              <button type="button" class="sppd-hl-pill-btn active" data-mock-date-mode="auto">Auto</button>
              <button type="button" class="sppd-hl-pill-btn" data-mock-date-mode="manual">Manual</button>
            </div>
            <div class="sppd-hl-sim-group" data-toolbar-for="pdf">
              <span class="text-muted mr-1" style="font-size: 10.5px;">Simulasi:</span>
              <button type="button" class="sppd-hl-pill-btn active" data-mock-pdf-mode="3">3 Pejabat</button>
              <button type="button" class="sppd-hl-pill-btn" data-mock-pdf-mode="1">1 Pejabat (Kosong)</button>
            </div>
          </div>

          <!-- Tab 1: Form Modal Compact -->
          <div class="sppd-hl-preview-pane active" data-preview-pane="form">
            <div class="sppd-mock-modal" data-date-mode="auto">
              <div class="sppd-mock-modal-head">
                <span><i class="fas fa-print mr-1"></i> Cetak SPPD Srikandi</span>
                <span>&times;</span>
              </div>
              <div class="sppd-mock-modal-body">
                <div class="sppd-mock-datebar">
                  <span><strong>Pengaturan Tanggal (Baris II–IV):</strong> <i class="fas fa-dot-circle text-primary ml-1"></i> Otomatis &nbsp; <i class="far fa-circle text-muted"></i> Manual</span>
                  <span class="badge badge-light border">Jadwal: 2026-11-25 s.d. 2026-11-27</span>
                </div>

                <!-- Baris II (Database) -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris II &mdash; Pejabat Tujuan 1 <span class="badge badge-info ml-1">Database</span></span>
                    <span class="badge badge-success">Aktif</span>
                  </div>
                  <div class="sppd-mock-card-body">
                    <div class="row no-gutters" style="margin: 0 -3px;">
                      <div class="col-md-4 px-1 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Lokasi Tujuan</div>
                        <div class="sppd-mock-input">Dinkes Kab. Sukabumi</div>
                      </div>
                      <div class="col-md-4 px-1 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Jabatan Tujuan</div>
                        <div class="sppd-mock-input">Kadinkes Kab. Sukabumi</div>
                      </div>
                      <div class="col-md-4 px-1">
                        <div class="sppd-mock-label">Pegawai (Select2 Database)</div>
                        <div class="sppd-mock-input">dr. H. Ahmad Fauzi - 19780412...</div>
                      </div>
                    </div>
                    <div class="sppd-mock-date-strip"><i class="far fa-calendar-alt mr-1"></i> Otomatis: <strong>Tiba:</strong> 25 Nov 2026 | <strong>Berangkat:</strong> 25 Nov 2026</div>
                    <div class="sppd-mock-date-manual">
                      <div class="flex-fill"><div class="sppd-mock-input">Tiba: 2026-11-25</div></div>
                      <div class="flex-fill"><div class="sppd-mock-input">Berangkat: 2026-11-25</div></div>
                    </div>
                  </div>
                </div>

                <!-- Baris III (Ketik Manual) -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris III &mdash; Pejabat Tujuan 2 <span class="badge badge-warning ml-1">Ketik Manual</span></span>
                    <span class="text-danger"><i class="fas fa-trash-alt mr-1"></i>Hapus</span>
                  </div>
                  <div class="sppd-mock-card-body">
                    <div class="row no-gutters mb-1" style="margin: 0 -3px;">
                      <div class="col-md-4 px-1 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Lokasi Tujuan</div>
                        <div class="sppd-mock-input">RSUD Sekarwangi</div>
                      </div>
                      <div class="col-md-4 px-1 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Jabatan Tujuan</div>
                        <div class="sppd-mock-input">Direktur RSUD Sekarwangi</div>
                      </div>
                      <div class="col-md-4 px-1">
                        <div class="sppd-mock-label">Opsi Select Pegawai</div>
                        <div class="sppd-mock-input is-manual"><strong>&gt;&gt; Ketik Manual &lt;&lt;</strong></div>
                      </div>
                    </div>
                    <div class="row no-gutters" style="margin: 0 -3px;">
                      <div class="col-6 px-1">
                        <div class="sppd-mock-input is-manual">Nama Manual: dr. Siti Rahmawati, MARS</div>
                      </div>
                      <div class="col-6 px-1">
                        <div class="sppd-mock-input is-manual">NIP Manual: 198209152009022004</div>
                      </div>
                    </div>
                    <div class="sppd-mock-date-strip"><i class="far fa-calendar-alt mr-1"></i> Otomatis: <strong>Tiba:</strong> 26 Nov 2026 | <strong>Berangkat:</strong> 26 Nov 2026</div>
                    <div class="sppd-mock-date-manual">
                      <div class="flex-fill"><div class="sppd-mock-input">Tiba: 2026-11-26</div></div>
                      <div class="flex-fill"><div class="sppd-mock-input">Berangkat: 2026-11-26</div></div>
                    </div>
                  </div>
                </div>

                <!-- Baris IV Ringkas -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris IV &mdash; Puskesmas Cibadak &bull; Kepala Puskesmas &bull; H. Dedi Kurniawan, SKM &bull; 27 Nov 2026</span>
                    <span class="text-danger"><i class="fas fa-trash-alt mr-1"></i>Hapus</span>
                  </div>
                </div>
              </div>
              <div class="sppd-mock-modal-footer">
                <div>
                  <span class="btn btn-outline-success btn-xs disabled mr-1"><i class="fas fa-plus mr-1"></i>Tambah Pejabat (Maks. 3: II–IV)</span>
                  <span class="text-muted">3 dari 3 aktif</span>
                </div>
                <div>
                  <span class="btn btn-secondary btn-xs disabled mr-1">Batal</span>
                  <span class="btn btn-success btn-xs disabled">Cetak SPPD Srikandi</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Hasil Cetak SPPD Compact -->
          <div class="sppd-hl-preview-pane" data-preview-pane="pdf">
            <div class="sppd-mock-paper-scroll">
              <div class="sppd-mock-paper" data-pdf-mode="3">
                <table class="sppd-mock-table">
                  <tr>
                    <td>&nbsp;</td>
                    <td>
                      <div>Berangkat dari : Kota Bandung (Tempat kedudukan) &bull; Tgl: 25 Nov 2026 &bull; Ke: Kab. Sukabumi</div>
                      <div>Kepala BKK Kelas I Bandung &bull; <span class="sppd-mock-ttd">${ttd_pengirim2}</span> &bull; <strong>Kepala Balai</strong> (NIP. 19690104...)</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>II</strong> &nbsp; Tiba di : Dinkes Kab. Sukabumi</div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pada Tanggal : 25 November 2026</div>
                      </div>
                      <div class="sppd-mock-pejabat-box">
                        <div class="font-weight-bold">Kepala Dinas Kesehatan Kab. Sukabumi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                        <div><strong><u>dr. H. Ahmad Fauzi, M.Kes</u></strong> &bull; NIP. 197804122006041008</div>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : Dinkes Kab. Sukabumi</div>
                        <div>Ke : <span class="sppd-pdf-only-3">RSUD Sekarwangi</span><span class="sppd-pdf-only-1">Kota Bandung</span> &bull; Tgl : <span class="sppd-pdf-only-3">25 Nov 2026</span><span class="sppd-pdf-only-1">27 Nov 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box">
                        <div class="font-weight-bold">Kepala Dinas Kesehatan Kab. Sukabumi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                        <div><strong><u>dr. H. Ahmad Fauzi, M.Kes</u></strong> &bull; NIP. 197804122006041008</div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>III</strong> &nbsp; Tiba di : <span class="sppd-pdf-only-3">RSUD Sekarwangi (26 Nov 2026)</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Direktur RSUD Sekarwangi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim4}</div>
                        <div><strong><u>dr. Siti Rahmawati, MARS</u></strong> &bull; NIP. 198209152009022004</div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : <span class="sppd-pdf-only-3">RSUD Sekarwangi &rarr; Puskesmas Cibadak (26 Nov 2026)</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Direktur RSUD Sekarwangi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim4}</div>
                        <div><strong><u>dr. Siti Rahmawati, MARS</u></strong> &bull; NIP. 198209152009022004</div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>IV</strong> &nbsp; Tiba di : <span class="sppd-pdf-only-3">Puskesmas Cibadak (27 Nov 2026)</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Kepala Puskesmas Cibadak</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim5}</div>
                        <div><strong><u>H. Dedi Kurniawan, SKM</u></strong> &bull; NIP. 198103202008011005</div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : <span class="sppd-pdf-only-3">Puskesmas Cibadak &rarr; Kota Bandung (27 Nov 2026)</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Kepala Puskesmas Cibadak</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim5}</div>
                        <div><strong><u>H. Dedi Kurniawan, SKM</u></strong> &bull; NIP. 198103202008011005</div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>V</strong> &nbsp; Tiba di: Kota Bandung &bull; <strong>PPK</strong> (<span class="sppd-mock-ttd">${ttd_pengirim1}</span>) &bull; Muhammad Dani Suarman, SE</td>
                    <td><strong>Pejabat Pembuat Komitmen</strong> (<span class="sppd-mock-ttd">${ttd_pengirim1}</span>) &bull; Telah diperiksa...</td>
                  </tr>
                  <tr>
                    <td><strong>VI</strong> &nbsp; CATATAN LAIN-LAIN</td>
                    <td><strong>VII</strong> &nbsp; <u>PERHATIAN</u> (Tanggung jawab PPK, Pegawai &amp; Pejabat)</td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="sppd-hl-caption">
              <i class="fas fa-check-circle mr-1"></i> Jarak TTD seragam PPK &nbsp;&bull;&nbsp; Tinggi baris kosong = terisi &nbsp;&bull;&nbsp; Cetak pas 2 halaman PDF
            </div>
          </div>
        </div>

        <!-- Panduan 1 Baris -->
        <div class="sppd-hl-steps">
          <strong>Alur:</strong>
          <span><span class="sppd-hl-step-num">1</span>Menu SINADI &rarr; Dinas Luar Kota</span>
          <i class="fas fa-chevron-right text-muted"></i>
          <span><span class="sppd-hl-step-num">2</span>Buka Detail Perjalanan Dinas</span>
          <i class="fas fa-chevron-right text-muted"></i>
          <span><span class="sppd-hl-step-num">3</span>Klik <strong>SPPD Srikandi</strong></span>
        </div>
      </div>

      <div class="modal-footer d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="chkHideSppdSrikandiHighlight">
          <label class="custom-control-label small text-muted" for="chkHideSppdSrikandiHighlight">Jangan tampilkan lagi saat login</label>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Nanti Saja</button>
          <a href="/sinadi/dinasLuarKota" class="btn btn-sm btn-success font-weight-bold">
            <i class="fas fa-shuttle-van mr-1"></i> Buka SINADI (Dinas Luar Kota)
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    var DISMISS_KEY = 'simply_highlight_sppd_srikandi_v1_dismiss';
    var SESSION_SHOWN_KEY = 'simply_highlight_sppd_srikandi_v1_shown_session';
    var JUST_LOGGED_IN = <?= $justLoggedInHighlight ? 'true' : 'false'; ?>;

    function getStorageItem(storage, key) {
      try {
        return storage.getItem(key);
      } catch (e) {
        return null;
      }
    }

    function setStorageItem(storage, key, value) {
      try {
        storage.setItem(key, value);
      } catch (e) {}
    }

    function removeStorageItem(storage, key) {
      try {
        storage.removeItem(key);
      } catch (e) {}
    }

    function syncDismissCheckbox($modal) {
      var isDismissed = getStorageItem(window.localStorage, DISMISS_KEY) === '1';
      $modal.find('#chkHideSppdSrikandiHighlight').prop('checked', isDismissed);
    }

    window.openSppdSrikandiHighlight = function() {
      var $modal = $('#modalHighlightSppdSrikandi');
      if (!$modal.length) return;
      if (!$modal.parent().is('body')) {
        $('body > #modalHighlightSppdSrikandi').not($modal).remove();
        $modal.appendTo('body');
      }
      syncDismissCheckbox($modal);
      $modal.modal('show');
    };

    $(document).ready(function() {
      var $modal = $('#modalHighlightSppdSrikandi');
      if (!$modal.length) return;

      if (!$modal.parent().is('body')) {
        $('body > #modalHighlightSppdSrikandi').not($modal).remove();
        $modal.appendTo('body');
      }

      syncDismissCheckbox($modal);

      $modal.on('click', '.sppd-hl-tab-btn', function() {
        var target = $(this).data('preview-tab');
        $modal.find('.sppd-hl-tab-btn').removeClass('active');
        $(this).addClass('active');
        $modal.find('.sppd-hl-preview-pane').removeClass('active');
        $modal.find('.sppd-hl-preview-pane[data-preview-pane="' + target + '"]').addClass('active');
        $modal.find('.sppd-hl-sim-group').removeClass('active');
        $modal.find('.sppd-hl-sim-group[data-toolbar-for="' + target + '"]').addClass('active');
      });

      $modal.on('click', '[data-mock-date-mode]', function() {
        var mode = $(this).data('mock-date-mode');
        $modal.find('[data-mock-date-mode]').removeClass('active');
        $(this).addClass('active');
        $modal.find('.sppd-mock-modal').attr('data-date-mode', mode);
      });

      $modal.on('click', '[data-mock-pdf-mode]', function() {
        var mode = String($(this).data('mock-pdf-mode'));
        $modal.find('[data-mock-pdf-mode]').removeClass('active');
        $(this).addClass('active');
        $modal.find('.sppd-mock-paper').attr('data-pdf-mode', mode);
      });

      $modal.on('change', '#chkHideSppdSrikandiHighlight', function() {
        if ($(this).is(':checked')) {
          setStorageItem(window.localStorage, DISMISS_KEY, '1');
        } else {
          removeStorageItem(window.localStorage, DISMISS_KEY);
        }
      });

      $modal.on('hidden.bs.modal', function() {
        if ($modal.find('#chkHideSppdSrikandiHighlight').is(':checked')) {
          setStorageItem(window.localStorage, DISMISS_KEY, '1');
        }
      });

      var isDismissed = getStorageItem(window.localStorage, DISMISS_KEY) === '1';
      var shownInSession = getStorageItem(window.sessionStorage, SESSION_SHOWN_KEY) === '1';

      if (!isDismissed && (JUST_LOGGED_IN || !shownInSession)) {
        var attempts = 0;
        var maxAttempts = 40;

        function tryShowHighlight() {
          attempts++;
          var hasBlockingPopup = !!window.agendaAlertWillShow
            || (typeof Swal !== 'undefined' && typeof Swal.isVisible === 'function' && Swal.isVisible())
            || $('.modal.show').length > 0;

          if (!hasBlockingPopup) {
            setStorageItem(window.sessionStorage, SESSION_SHOWN_KEY, '1');
            window.openSppdSrikandiHighlight();
            return;
          }

          if (attempts < maxAttempts) {
            setTimeout(tryShowHighlight, 700);
          }
        }

        setTimeout(tryShowHighlight, 700);
      }
    });
  })();
</script>
