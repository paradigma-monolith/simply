<?php
$justLoggedInHighlight = !empty($justLoggedInSppdHighlight);
?>
<!-- Pop-up Highlight Preview Fitur Baru SPPD Srikandi -->
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
    max-width: 1020px;
    max-height: none !important;
    height: auto !important;
    margin: 1.25rem auto 2.5rem auto !important;
  }
  #modalHighlightSppdSrikandi .modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.28);
    max-height: none !important;
    height: auto !important;
  }
  #modalHighlightSppdSrikandi .modal-body {
    max-height: none !important;
    height: auto !important;
    overflow: visible !important;
    background: #f8fafc;
    padding: 18px 20px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-banner {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    color: #ffffff;
    padding: 18px 22px;
    position: relative;
  }
  #modalHighlightSppdSrikandi .sppd-hl-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.6px;
    padding: 3px 10px;
    border-radius: 999px;
    margin-bottom: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-title {
    font-size: 1.3rem;
    font-weight: 800;
    margin: 0 0 4px;
    color: #ffffff;
  }
  #modalHighlightSppdSrikandi .sppd-hl-subtitle {
    font-size: 0.86rem;
    color: rgba(255, 255, 255, 0.92);
    margin: 0;
  }
  #modalHighlightSppdSrikandi .sppd-hl-close {
    position: absolute;
    top: 14px;
    right: 16px;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: none;
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff;
    font-size: 1.2rem;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.18s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-close:hover {
    background: rgba(255, 255, 255, 0.32);
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: rgba(13, 148, 136, 0.12);
    color: #0d9488;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
    line-height: 1.25;
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-desc {
    font-size: 0.76rem;
    color: #475569;
    line-height: 1.35;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-wrap {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
  }
  #modalHighlightSppdSrikandi .sppd-hl-tabs {
    display: flex;
    flex-wrap: wrap;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px;
    gap: 6px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tab-btn {
    flex: 1 1 200px;
    border: none;
    background: transparent;
    color: #475569;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.16s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tab-btn.active {
    background: #0d9488;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(13, 148, 136, 0.25);
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-pane {
    display: none;
    padding: 14px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-pane.active {
    display: block;
  }
  #modalHighlightSppdSrikandi .sppd-hl-subswitcher {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 10px;
    padding: 8px 12px;
    background: #f0fdfa;
    border: 1px solid #ccfbf1;
    border-radius: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-pill-btn {
    border: 1px solid #0d9488;
    background: #ffffff;
    color: #0f766e;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  #modalHighlightSppdSrikandi .sppd-hl-pill-btn.active {
    background: #0d9488;
    color: #ffffff;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
    background: #ffffff;
    font-size: 12px;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-head {
    background: #28a745;
    color: #ffffff;
    font-weight: 700;
    font-size: 13px;
    padding: 8px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-body {
    padding: 12px 14px;
    background: #f8fafc;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 9px 14px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-datebar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    background: #ffffff;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #28a745;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 10px;
    font-size: 12px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card:last-child {
    margin-bottom: 0;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-head {
    background: #f1f5f9;
    padding: 6px 12px;
    font-weight: 700;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-body {
    padding: 10px 12px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    margin-bottom: 3px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-input {
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    padding: 5px 9px;
    background: #ffffff;
    color: #1e293b;
    font-size: 11.5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-input.is-manual {
    background: #fefce8;
    border-color: #fde047;
  }
  #modalHighlightSppdSrikandi .sppd-mock-auto-date {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    padding: 5px 9px;
    font-size: 11.5px;
    color: #475569;
    margin-bottom: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-manual-date {
    display: none;
    margin-bottom: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal[data-date-mode="manual"] .sppd-mock-auto-date {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal[data-date-mode="manual"] .sppd-mock-manual-date {
    display: flex;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper-scroll {
    overflow-x: auto;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper {
    min-width: 620px;
    background: #ffffff;
    border: 1px solid #64748b;
    border-radius: 6px;
    padding: 12px 14px;
    font-family: "Times New Roman", Times, serif;
    font-size: 11px;
    color: #000000;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
  }
  #modalHighlightSppdSrikandi .sppd-mock-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }
  #modalHighlightSppdSrikandi .sppd-mock-table td {
    border: 1px solid #1e293b;
    padding: 5px 8px;
    vertical-align: top;
    width: 50%;
    line-height: 1.3;
  }
  #modalHighlightSppdSrikandi .sppd-mock-stop-header {
    min-height: 44px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-pejabat-box {
    min-height: 86px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    padding-top: 4px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-pejabat-box.is-empty {
    justify-content: center;
    background: repeating-linear-gradient(
      -45deg,
      #ffffff,
      #ffffff 6px,
      #f8fafc 6px,
      #f8fafc 12px
    );
  }
  #modalHighlightSppdSrikandi .sppd-mock-ttd {
    font-family: monospace;
    font-size: 10px;
    color: #0f766e;
    background: #f0fdfa;
    display: inline-block;
    padding: 2px 7px;
    border-radius: 4px;
    border: 1px dashed #5eead4;
    margin: 12px 0;
  }
  #modalHighlightSppdSrikandi .sppd-mock-empty-note {
    font-family: sans-serif;
    font-size: 10px;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 999px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper[data-pdf-mode="3"] .sppd-pdf-only-1 {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper[data-pdf-mode="1"] .sppd-pdf-only-3 {
    display: none;
  }
  #modalHighlightSppdSrikandi .sppd-hl-checkstrip {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
    font-size: 11.5px;
    font-weight: 600;
    color: #0f766e;
  }
  #modalHighlightSppdSrikandi .sppd-hl-checkstrip span {
    background: #f0fdfa;
    border: 1px solid #99f6e4;
    padding: 4px 10px;
    border-radius: 999px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-steps {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 12px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-step-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 0.8rem;
    color: #334155;
    font-weight: 600;
  }
  #modalHighlightSppdSrikandi .sppd-hl-step-num {
    width: 22px;
    height: 22px;
    border-radius: 999px;
    background: #0d9488;
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  #modalHighlightSppdSrikandi .modal-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 12px 20px;
  }
</style>

<div class="modal fade" id="modalHighlightSppdSrikandi" role="dialog" aria-labelledby="modalHighlightSppdSrikandiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="sppd-hl-banner">
        <button type="button" class="sppd-hl-close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="sppd-hl-badge">
          <i class="fas fa-star"></i> FITUR BARU &bull; SINADI DINAS LUAR KOTA
        </div>
        <h4 class="sppd-hl-title" id="modalHighlightSppdSrikandiLabel">SPPD Srikandi Multi-Pejabat Tujuan (Baris II–IV)</h4>
        <p class="sppd-hl-subtitle">Cetak borang SPPD Srikandi dengan 3 baris pejabat penandatangan, pilihan pegawai database/manual, tanggal otomatis/manual, dan tata letak PDF 2 halaman yang presisi.</p>
      </div>

      <div class="modal-body">
        <!-- 4 Kartu Ringkasan Fitur -->
        <div class="row mb-3">
          <div class="col-sm-6 col-lg-3 mb-2 mb-lg-0">
            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-users-cog"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Maks. 3 Pejabat (II–IV)</div>
                <div class="sppd-hl-feature-desc">Placeholder TTD <code>${ttd_pengirim3}</code> s.d. <code>${ttd_pengirim5}</code> otomatis.</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-2 mb-lg-0">
            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-address-book"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Database &amp; Manual</div>
                <div class="sppd-hl-feature-desc">Pilih pegawai via Select2 atau ketik manual nama &amp; NIP pejabat eksternal.</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-2 mb-sm-0">
            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-calendar-alt"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Tanggal Otomatis / Manual</div>
                <div class="sppd-hl-feature-desc">Dibagi berurutan sesuai durasi perjalanan atau atur manual tiap baris.</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-file-pdf"></i></div>
              <div>
                <div class="sppd-hl-feature-title">PDF Rapi 2 Halaman</div>
                <div class="sppd-hl-feature-desc">Jarak TTD identik blok PPK &amp; tinggi baris kosong sama dengan terisi.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Area Preview Interaktif Full Width -->
        <div class="sppd-hl-preview-wrap">
          <div class="sppd-hl-tabs">
            <button type="button" class="sppd-hl-tab-btn active" data-preview-tab="form">
              <i class="fas fa-window-restore mr-1"></i> 1. Preview Form Modal Cetak SPPD Srikandi
            </button>
            <button type="button" class="sppd-hl-tab-btn" data-preview-tab="pdf">
              <i class="fas fa-print mr-1"></i> 2. Preview Hasil Cetak SPPD (Halaman Belakang)
            </button>
          </div>

          <!-- Tab 1: Preview Form Modal -->
          <div class="sppd-hl-preview-pane active" data-preview-pane="form">
            <div class="sppd-hl-subswitcher">
              <span class="small font-weight-bold text-dark">
                <i class="fas fa-sliders-h text-teal mr-1"></i> Simulasi Mode Tanggal di Form:
              </span>
              <div class="d-flex flex-wrap" style="gap: 6px;">
                <button type="button" class="sppd-hl-pill-btn active" data-mock-date-mode="auto">Mode Tanggal Otomatis</button>
                <button type="button" class="sppd-hl-pill-btn" data-mock-date-mode="manual">Mode Tanggal Manual</button>
              </div>
            </div>

            <div class="sppd-mock-modal" data-date-mode="auto">
              <div class="sppd-mock-modal-head">
                <span><i class="fas fa-print mr-1"></i> Cetak SPPD Srikandi</span>
                <span>&times;</span>
              </div>
              <div class="sppd-mock-modal-body">
                <div class="sppd-mock-datebar">
                  <div>
                    <strong class="d-block mb-1">Pengaturan Tanggal (Tiba / Berangkat Baris II–IV)</strong>
                    <span class="mr-3">
                      <i class="fas fa-dot-circle text-primary mr-1"></i>Otomatis (berurut sesuai lamanya)
                    </span>
                    <span class="text-muted">
                      <i class="far fa-circle mr-1"></i>Manual (isi tanggal sendiri)
                    </span>
                  </div>
                  <span class="badge badge-light border px-2 py-1">Jadwal: 2026-11-25 s.d. 2026-11-27</span>
                </div>

                <!-- Baris II -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris II &mdash; Pejabat Tujuan 1 <span class="badge badge-info ml-1">Contoh: Pilih Database</span></span>
                    <span class="badge badge-success">Aktif</span>
                  </div>
                  <div class="sppd-mock-card-body">
                    <div class="row">
                      <div class="col-md-6 mb-2">
                        <div class="sppd-mock-label">Lokasi / Kota Tujuan (Opsional)</div>
                        <div class="sppd-mock-input"><span>Dinkes Kab. Sukabumi</span></div>
                      </div>
                      <div class="col-md-6 mb-2">
                        <div class="sppd-mock-label">Jabatan Tujuan</div>
                        <div class="sppd-mock-input"><span>Kepala Dinas Kesehatan Kab. Sukabumi</span></div>
                      </div>
                    </div>
                    <div class="sppd-mock-auto-date">
                      <i class="far fa-calendar-alt mr-1"></i> Tanggal otomatis: <strong>Tiba:</strong> 25 November 2026 &nbsp;|&nbsp; <strong>Berangkat:</strong> 25 November 2026
                    </div>
                    <div class="sppd-mock-manual-date row">
                      <div class="col-md-6 mb-1">
                        <div class="sppd-mock-label">Pada Tanggal (Tiba di)</div>
                        <div class="sppd-mock-input"><span>2026-11-25</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                      <div class="col-md-6 mb-1">
                        <div class="sppd-mock-label">Pada Tanggal (Berangkat dari)</div>
                        <div class="sppd-mock-input"><span>2026-11-25</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                    </div>
                    <div>
                      <div class="sppd-mock-label">Pilih Pegawai dari Database (Nama &amp; NIP)</div>
                      <div class="sppd-mock-input">
                        <span>dr. H. Ahmad Fauzi, M.Kes - 197804122006041008</span>
                        <i class="fas fa-caret-down text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Baris III -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris III &mdash; Pejabat Tujuan 2 <span class="badge badge-warning ml-1">Contoh: Ketik Manual</span></span>
                    <span class="badge badge-outline-danger border border-danger text-danger"><i class="fas fa-trash-alt mr-1"></i>Hapus</span>
                  </div>
                  <div class="sppd-mock-card-body">
                    <div class="row">
                      <div class="col-md-6 mb-2">
                        <div class="sppd-mock-label">Lokasi / Kota Tujuan (Opsional)</div>
                        <div class="sppd-mock-input"><span>RSUD Sekarwangi</span></div>
                      </div>
                      <div class="col-md-6 mb-2">
                        <div class="sppd-mock-label">Jabatan Tujuan</div>
                        <div class="sppd-mock-input"><span>Direktur RSUD Sekarwangi</span></div>
                      </div>
                    </div>
                    <div class="sppd-mock-auto-date">
                      <i class="far fa-calendar-alt mr-1"></i> Tanggal otomatis: <strong>Tiba:</strong> 26 November 2026 &nbsp;|&nbsp; <strong>Berangkat:</strong> 26 November 2026
                    </div>
                    <div class="sppd-mock-manual-date row">
                      <div class="col-md-6 mb-1">
                        <div class="sppd-mock-label">Pada Tanggal (Tiba di)</div>
                        <div class="sppd-mock-input"><span>2026-11-26</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                      <div class="col-md-6 mb-1">
                        <div class="sppd-mock-label">Pada Tanggal (Berangkat dari)</div>
                        <div class="sppd-mock-input"><span>2026-11-26</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                    </div>
                    <div class="mb-2">
                      <div class="sppd-mock-label">Pilih Pegawai dari Database (Nama &amp; NIP)</div>
                      <div class="sppd-mock-input is-manual">
                        <strong>&gt;&gt; Ketik Manual &lt;&lt;</strong>
                        <i class="fas fa-caret-down text-muted"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="sppd-mock-label">Nama Pegawai (Manual)</div>
                        <div class="sppd-mock-input is-manual"><span>dr. Siti Rahmawati, MARS</span></div>
                      </div>
                      <div class="col-md-6">
                        <div class="sppd-mock-label">NIP Pegawai (Manual)</div>
                        <div class="sppd-mock-input is-manual"><span>198209152009022004</span></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Baris IV -->
                <div class="sppd-mock-card">
                  <div class="sppd-mock-card-head">
                    <span>Baris IV &mdash; Pejabat Tujuan 3</span>
                    <span class="badge badge-outline-danger border border-danger text-danger"><i class="fas fa-trash-alt mr-1"></i>Hapus</span>
                  </div>
                  <div class="sppd-mock-card-body">
                    <div class="row">
                      <div class="col-md-4 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Lokasi / Kota Tujuan</div>
                        <div class="sppd-mock-input"><span>Puskesmas Cibadak</span></div>
                      </div>
                      <div class="col-md-4 mb-1 mb-md-0">
                        <div class="sppd-mock-label">Jabatan Tujuan</div>
                        <div class="sppd-mock-input"><span>Kepala Puskesmas Cibadak</span></div>
                      </div>
                      <div class="col-md-4">
                        <div class="sppd-mock-label">Pegawai (Database / Manual)</div>
                        <div class="sppd-mock-input"><span>H. Dedi Kurniawan, SKM - 19810320...</span></div>
                      </div>
                    </div>
                    <div class="sppd-mock-auto-date mt-2 mb-0">
                      <i class="far fa-calendar-alt mr-1"></i> Tanggal otomatis: <strong>Tiba:</strong> 27 November 2026 &nbsp;|&nbsp; <strong>Berangkat:</strong> 27 November 2026
                    </div>
                    <div class="sppd-mock-manual-date row mt-2 mb-0">
                      <div class="col-md-6">
                        <div class="sppd-mock-label">Pada Tanggal (Tiba di)</div>
                        <div class="sppd-mock-input"><span>2026-11-27</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                      <div class="col-md-6">
                        <div class="sppd-mock-label">Pada Tanggal (Berangkat dari)</div>
                        <div class="sppd-mock-input"><span>2026-11-27</span><i class="far fa-calendar text-muted"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sppd-mock-modal-footer">
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                  <span class="btn btn-outline-success btn-xs disabled"><i class="fas fa-plus mr-1"></i> Tambah Pejabat Tujuan (Maks. 3: Baris II–IV)</span>
                  <small class="text-muted">3 dari 3 baris aktif (Baris II–IV)</small>
                </div>
                <div class="d-flex align-items-center" style="gap: 6px;">
                  <span class="btn btn-secondary btn-xs disabled">Batal</span>
                  <span class="btn btn-success btn-xs disabled">Cetak SPPD Srikandi</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Preview Hasil Cetak SPPD -->
          <div class="sppd-hl-preview-pane" data-preview-pane="pdf">
            <div class="sppd-hl-subswitcher">
              <span class="small font-weight-bold text-dark">
                <i class="fas fa-eye text-teal mr-1"></i> Bandingkan Tampilan Baris Terisi vs Kosong:
              </span>
              <div class="d-flex flex-wrap" style="gap: 6px;">
                <button type="button" class="sppd-hl-pill-btn active" data-mock-pdf-mode="3">3 Pejabat Terisi (Baris II–IV)</button>
                <button type="button" class="sppd-hl-pill-btn" data-mock-pdf-mode="1">1 Pejabat Terisi (Baris III–IV Kosong)</button>
              </div>
            </div>

            <div class="sppd-mock-paper-scroll">
              <div class="sppd-mock-paper" data-pdf-mode="3">
                <table class="sppd-mock-table">
                  <!-- Baris I -->
                  <tr>
                    <td>&nbsp;</td>
                    <td>
                      <div>Berangkat dari : Kota Bandung</div>
                      <div>(Tempat kedudukan)</div>
                      <div>Pada Tanggal : 25 November 2026</div>
                      <div>Ke : Kab. Sukabumi</div>
                      <div class="mt-1">Kepala Balai Kekarantinaan Kesehatan Kelas I Bandung</div>
                      <div class="my-1"><span class="sppd-mock-ttd">${ttd_pengirim2}</span></div>
                      <div><strong>Nama Kepala Balai</strong></div>
                      <div><strong>NIP. 196901042002121003</strong></div>
                    </td>
                  </tr>

                  <!-- Baris II -->
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>II</strong> &nbsp;&nbsp; Tiba di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Dinkes Kab. Sukabumi</div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pada Tanggal : 25 November 2026</div>
                      </div>
                      <div class="sppd-mock-pejabat-box">
                        <div class="font-weight-bold">Kepala Dinas Kesehatan Kab. Sukabumi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                        <div>
                          <div><strong><u>dr. H. Ahmad Fauzi, M.Kes</u></strong></div>
                          <div>NIP. 197804122006041008</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : Dinkes Kab. Sukabumi</div>
                        <div>Ke &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span class="sppd-pdf-only-3">RSUD Sekarwangi</span><span class="sppd-pdf-only-1">Kota Bandung</span></div>
                        <div>Pada Tanggal &nbsp;&nbsp; : <span class="sppd-pdf-only-3">25 November 2026</span><span class="sppd-pdf-only-1">27 November 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box">
                        <div class="font-weight-bold">Kepala Dinas Kesehatan Kab. Sukabumi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                        <div>
                          <div><strong><u>dr. H. Ahmad Fauzi, M.Kes</u></strong></div>
                          <div>NIP. 197804122006041008</div>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- Baris III -->
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>III</strong> &nbsp; Tiba di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span class="sppd-pdf-only-3">RSUD Sekarwangi</span></div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pada Tanggal : <span class="sppd-pdf-only-3">26 November 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Direktur RSUD Sekarwangi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim4}</div>
                        <div>
                          <div><strong><u>dr. Siti Rahmawati, MARS</u></strong></div>
                          <div>NIP. 198209152009022004</div>
                        </div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : <span class="sppd-pdf-only-3">RSUD Sekarwangi</span></div>
                        <div>Ke &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span class="sppd-pdf-only-3">Puskesmas Cibadak</span></div>
                        <div>Pada Tanggal &nbsp;&nbsp; : <span class="sppd-pdf-only-3">26 November 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Direktur RSUD Sekarwangi</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim4}</div>
                        <div>
                          <div><strong><u>dr. Siti Rahmawati, MARS</u></strong></div>
                          <div>NIP. 198209152009022004</div>
                        </div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                  </tr>

                  <!-- Baris IV -->
                  <tr>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div><strong>IV</strong> &nbsp; Tiba di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span class="sppd-pdf-only-3">Puskesmas Cibadak</span></div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pada Tanggal : <span class="sppd-pdf-only-3">27 November 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Kepala Puskesmas Cibadak</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim5}</div>
                        <div>
                          <div><strong><u>H. Dedi Kurniawan, SKM</u></strong></div>
                          <div>NIP. 198103202008011005</div>
                        </div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                    <td>
                      <div class="sppd-mock-stop-header">
                        <div>Berangkat dari : <span class="sppd-pdf-only-3">Puskesmas Cibadak</span></div>
                        <div>Ke &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span class="sppd-pdf-only-3">Kota Bandung</span></div>
                        <div>Pada Tanggal &nbsp;&nbsp; : <span class="sppd-pdf-only-3">27 November 2026</span></div>
                      </div>
                      <div class="sppd-mock-pejabat-box sppd-pdf-only-3">
                        <div class="font-weight-bold">Kepala Puskesmas Cibadak</div>
                        <div class="sppd-mock-ttd">${ttd_pengirim5}</div>
                        <div>
                          <div><strong><u>H. Dedi Kurniawan, SKM</u></strong></div>
                          <div>NIP. 198103202008011005</div>
                        </div>
                      </div>
                      <div class="sppd-mock-pejabat-box is-empty sppd-pdf-only-1">
                        <span class="sppd-mock-empty-note">Kosong — Tinggi baris tetap sama persis</span>
                      </div>
                    </td>
                  </tr>

                  <!-- Baris V (PPK) -->
                  <tr>
                    <td>
                      <div><strong>V</strong> &nbsp;&nbsp; Tiba di &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Kota Bandung</div>
                      <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pada Tanggal : 27 November 2026</div>
                      <div class="mt-2 ml-3">Pejabat Pembuat Komitmen</div>
                      <div class="my-1 ml-4"><span class="sppd-mock-ttd">${ttd_pengirim1}</span></div>
                      <div class="ml-3"><strong>Muhammad Dani Suarman, SE</strong></div>
                      <div class="ml-3"><strong>NIP. 198806032015031004</strong></div>
                    </td>
                    <td>
                      <div style="font-size: 10px;">Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya</div>
                      <div class="mt-1">Pejabat Pembuat Komitmen</div>
                      <div class="my-1 ml-3"><span class="sppd-mock-ttd">${ttd_pengirim1}</span></div>
                      <div><strong>Muhammad Dani Suarman, SE</strong></div>
                      <div><strong>NIP. 198806032015031004</strong></div>
                    </td>
                  </tr>

                  <!-- Baris VI & VII -->
                  <tr>
                    <td colspan="2"><strong>VI</strong> &nbsp; CATATAN LAIN-LAIN</td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div><strong>VII</strong> &nbsp; <u>PERHATIAN</u></div>
                      <div style="font-size: 10px;">PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat / tiba, serta bendahara pengeluaran bertanggung jawab...</div>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="sppd-hl-checkstrip">
              <span><i class="fas fa-check mr-1"></i> Jarak Jabatan–TTD–Nama seragam blok PPK</span>
              <span><i class="fas fa-check mr-1"></i> Tinggi baris kosong &amp; terisi identik</span>
              <span><i class="fas fa-check mr-1"></i> Cetak rapi pas 2 halaman PDF</span>
            </div>
          </div>
        </div>

        <!-- Panduan Singkat -->
        <div class="sppd-hl-steps">
          <div class="sppd-hl-step-item">
            <span class="sppd-hl-step-num">1</span>
            <span>Buka Menu SINADI &rarr; Dinas Luar Kota</span>
          </div>
          <i class="fas fa-chevron-right text-muted d-none d-md-inline"></i>
          <div class="sppd-hl-step-item">
            <span class="sppd-hl-step-num">2</span>
            <span>Pilih &amp; Buka Detail Perjalanan Dinas</span>
          </div>
          <i class="fas fa-chevron-right text-muted d-none d-md-inline"></i>
          <div class="sppd-hl-step-item">
            <span class="sppd-hl-step-num">3</span>
            <span>Klik Tombol <strong>SPPD Srikandi</strong></span>
          </div>
        </div>
      </div>

      <div class="modal-footer d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="chkHideSppdSrikandiHighlight">
          <label class="custom-control-label small text-muted" for="chkHideSppdSrikandiHighlight">Jangan tampilkan lagi saat login</label>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <button type="button" class="btn btn-light border" data-dismiss="modal">Nanti Saja</button>
          <a href="/sinadi/dinasLuarKota" class="btn btn-success font-weight-bold">
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
