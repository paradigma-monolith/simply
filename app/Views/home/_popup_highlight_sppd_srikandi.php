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
    max-width: 920px;
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
    font-size: 1.28rem;
    font-weight: 800;
    margin: 0 0 4px;
    color: #ffffff;
  }
  #modalHighlightSppdSrikandi .sppd-hl-subtitle {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
  }
  #modalHighlightSppdSrikandi .sppd-hl-close {
    position: absolute;
    top: 14px;
    right: 16px;
    width: 32px;
    height: 32px;
    border-radius: 999px;
    border: none;
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff;
    font-size: 1.15rem;
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
    padding: 11px 13px;
    margin-bottom: 10px;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-card:last-child {
    margin-bottom: 0;
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
    font-size: 0.86rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-feature-desc {
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.38;
  }
  #modalHighlightSppdSrikandi .sppd-hl-preview-wrap {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tabs {
    display: flex;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px;
    gap: 6px;
  }
  #modalHighlightSppdSrikandi .sppd-hl-tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 7px 10px;
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
    padding: 12px;
    flex: 1;
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
    padding: 6px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  #modalHighlightSppdSrikandi .sppd-mock-modal-body {
    padding: 9px;
    background: #f8fafc;
  }
  #modalHighlightSppdSrikandi .sppd-mock-pill-row {
    display: flex;
    gap: 8px;
    background: #ffffff;
    border: 1px solid #bbf7d0;
    border-left: 3px solid #28a745;
    border-radius: 6px;
    padding: 6px 8px;
    margin-bottom: 7px;
    font-size: 10.5px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    margin-bottom: 7px;
    overflow: hidden;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-head {
    background: #f1f5f9;
    padding: 4px 8px;
    font-weight: 700;
    color: #1e293b;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 10.5px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-card-body {
    padding: 6px 8px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-input {
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 3px 6px;
    background: #ffffff;
    color: #334155;
    font-size: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  #modalHighlightSppdSrikandi .sppd-mock-label {
    font-size: 9.5px;
    font-weight: 700;
    color: #475569;
    margin-bottom: 2px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-paper {
    background: #ffffff;
    border: 1px solid #94a3b8;
    border-radius: 6px;
    padding: 8px;
    font-family: "Times New Roman", Times, serif;
    font-size: 10px;
    color: #0f172a;
  }
  #modalHighlightSppdSrikandi .sppd-mock-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }
  #modalHighlightSppdSrikandi .sppd-mock-table td {
    border: 1px solid #334155;
    padding: 4px 6px;
    vertical-align: top;
    width: 50%;
    line-height: 1.25;
  }
  #modalHighlightSppdSrikandi .sppd-mock-sign {
    text-align: center;
    margin-top: 4px;
    padding-top: 2px;
  }
  #modalHighlightSppdSrikandi .sppd-mock-ttd {
    font-family: monospace;
    font-size: 9.5px;
    color: #0f766e;
    background: #f0fdfa;
    display: inline-block;
    padding: 1px 5px;
    border-radius: 4px;
    border: 1px dashed #5eead4;
    margin: 7px 0;
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
    font-size: 0.78rem;
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
          <i class="fas fa-sparkles"></i> FITUR BARU &bull; SINADI
        </div>
        <h4 class="sppd-hl-title" id="modalHighlightSppdSrikandiLabel">SPPD Srikandi Multi-Pejabat Tujuan (Baris II–IV)</h4>
        <p class="sppd-hl-subtitle">Kini pengisian pejabat penandatangan dan tanggal perjalanan pada borang SPPD Srikandi lebih cepat, presisi, dan rapi dalam 2 halaman.</p>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6 mb-3 mb-lg-0">
            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-users-cog"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Hingga 3 Pejabat Tujuan (Baris II, III &amp; IV)</div>
                <div class="sppd-hl-feature-desc">Tambah atau hapus tujuan langsung di modal cetak. Placeholder tanda tangan <code>${ttd_pengirim3}</code> s.d. <code>${ttd_pengirim5}</code> disusun otomatis.</div>
              </div>
            </div>

            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-address-book"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Pilih Pegawai Database atau Ketik Manual</div>
                <div class="sppd-hl-feature-desc">Cari nama &amp; NIP pegawai dengan Select2, atau pilih <strong>&gt;&gt; Ketik Manual &lt;&lt;</strong> untuk pejabat tujuan dari instansi eksternal.</div>
              </div>
            </div>

            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-calendar-alt"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Tanggal Tiba &amp; Berangkat Otomatis / Manual</div>
                <div class="sppd-hl-feature-desc">Mode otomatis membagi tanggal berurutan sesuai durasi dinas, atau aktifkan mode manual untuk mengatur tiap tanggal secara spesifik.</div>
              </div>
            </div>

            <div class="sppd-hl-feature-card">
              <div class="sppd-hl-feature-icon"><i class="fas fa-file-pdf"></i></div>
              <div>
                <div class="sppd-hl-feature-title">Tata Letak PDF Presisi &amp; Tetap 2 Halaman</div>
                <div class="sppd-hl-feature-desc">Jarak jabatan, blok TTD, nama &amp; NIP seragam dengan blok PPK. Tinggi baris kosong maupun terisi identik sehingga tabel tidak bergeser.</div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="sppd-hl-preview-wrap">
              <div class="sppd-hl-tabs">
                <button type="button" class="sppd-hl-tab-btn active" data-preview-tab="form">
                  <i class="fas fa-window-restore mr-1"></i> Preview Form Modal
                </button>
                <button type="button" class="sppd-hl-tab-btn" data-preview-tab="pdf">
                  <i class="fas fa-print mr-1"></i> Preview Hasil Cetak SPPD
                </button>
              </div>

              <div class="sppd-hl-preview-pane active" data-preview-pane="form">
                <div class="sppd-mock-modal">
                  <div class="sppd-mock-modal-head">
                    <span>Cetak SPPD Srikandi</span>
                    <span>&times;</span>
                  </div>
                  <div class="sppd-mock-modal-body">
                    <div class="sppd-mock-pill-row">
                      <span><i class="fas fa-dot-circle text-primary mr-1"></i>Otomatis</span>
                      <span><i class="far fa-circle text-muted mr-1"></i>Manual</span>
                      <span class="ml-auto badge badge-light border">Baris II–IV</span>
                    </div>

                    <div class="sppd-mock-card">
                      <div class="sppd-mock-card-head">
                        <span>Baris II &mdash; Pejabat Tujuan 1</span>
                        <span class="badge badge-success">Aktif</span>
                      </div>
                      <div class="sppd-mock-card-body">
                        <div class="row no-gutters" style="gap: 6px;">
                          <div class="col">
                            <div class="sppd-mock-label">Lokasi / Kota Tujuan</div>
                            <div class="sppd-mock-input">Kab. Sukabumi</div>
                          </div>
                          <div class="col">
                            <div class="sppd-mock-label">Jabatan Tujuan</div>
                            <div class="sppd-mock-input">Kepala Dinas Kesehatan</div>
                          </div>
                        </div>
                        <div class="mt-1">
                          <div class="sppd-mock-label">Pilih Pegawai / Ketik Manual</div>
                          <div class="sppd-mock-input d-flex justify-content-between">
                            <span>Dr. Budi Santoso - 19800101...</span>
                            <i class="fas fa-caret-down text-muted"></i>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="sppd-mock-card mb-1">
                      <div class="sppd-mock-card-head">
                        <span>Baris III &mdash; Pejabat Tujuan 2</span>
                        <span class="text-danger" style="font-size: 9.5px;"><i class="fas fa-trash-alt"></i> Hapus</span>
                      </div>
                      <div class="sppd-mock-card-body py-1">
                        <div class="sppd-mock-input">RSUD Sekarwangi &bull; Direktur RSUD &bull; &gt;&gt; Ketik Manual &lt;&lt;</div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-1">
                      <span class="badge badge-outline-success border border-success text-success px-2 py-1">+ Tambah Pejabat Tujuan (Maks. 3)</span>
                      <span class="badge badge-success px-2 py-1">Cetak SPPD Srikandi</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="sppd-hl-preview-pane" data-preview-pane="pdf">
                <div class="sppd-mock-paper">
                  <div class="text-center font-weight-bold mb-1" style="font-family: sans-serif; font-size: 9.5px; color: #0f766e;">
                    HALAMAN BELAKANG SPPD (2 HALAMAN TETAP)
                  </div>
                  <table class="sppd-mock-table">
                    <tr>
                      <td>
                        <div><strong>II</strong> &nbsp; Tiba di : Kab. Sukabumi</div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal : 25 November 2026</div>
                        <div class="sppd-mock-sign">
                          <div class="font-weight-bold">Kepala Dinas Kesehatan</div>
                          <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                          <div><strong><u>Dr. Budi Santoso</u></strong></div>
                          <div>NIP. 198001012005011001</div>
                        </div>
                      </td>
                      <td>
                        <div>Berangkat dari : Kab. Sukabumi</div>
                        <div>Ke : RSUD Sekarwangi</div>
                        <div>Tanggal : 25 November 2026</div>
                        <div class="sppd-mock-sign">
                          <div class="font-weight-bold">Kepala Dinas Kesehatan</div>
                          <div class="sppd-mock-ttd">${ttd_pengirim3}</div>
                          <div><strong><u>Dr. Budi Santoso</u></strong></div>
                          <div>NIP. 198001012005011001</div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div><strong>III</strong> &nbsp; Tiba di : RSUD Sekarwangi</div>
                        <div class="text-muted text-center py-2" style="font-size: 9.5px;">(Tinggi baris seragam baik terisi / kosong)</div>
                      </td>
                      <td>
                        <div>Berangkat dari : RSUD Sekarwangi &bull; Ke : Bandung</div>
                        <div class="text-center text-muted py-1" style="font-size: 9.5px;"><code>${ttd_pengirim4}</code></div>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>IV</strong> &nbsp; Slot Baris IV (Kosong / Terisi)</td>
                      <td class="text-muted"><code>${ttd_pengirim5}</code> (jika diisi)</td>
                    </tr>
                    <tr>
                      <td><strong>V</strong> &nbsp; Tiba di : Bandung &bull; <strong>PPK</strong> (<code>${ttd_pengirim1}</code>)</td>
                      <td><strong>VI</strong> Catatan Lain-Lain &bull; <strong>VII</strong> Perhatian</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sppd-hl-steps">
          <div class="sppd-hl-step-item">
            <span class="sppd-hl-step-num">1</span>
            <span>Buka Menu SINADI</span>
          </div>
          <i class="fas fa-chevron-right text-muted d-none d-md-inline"></i>
          <div class="sppd-hl-step-item">
            <span class="sppd-hl-step-num">2</span>
            <span>Buka Detail Perjalanan Dinas</span>
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
          <a href="/sinadi/perjalananSaya" class="btn btn-success font-weight-bold">
            <i class="fas fa-shuttle-van mr-1"></i> Buka SINADI
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
