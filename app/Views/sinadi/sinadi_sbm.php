<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<style>
  .sbm-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 140px);
    min-height: 640px;
  }
  .sbm-header {
    padding: 12px 16px;
    background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
    color: #ffffff;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .sbm-title-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
  }
  .sbm-title-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
  }
  .sbm-title {
    font-size: 0.96rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.25;
  }
  .sbm-subtitle {
    font-size: 0.74rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 2px 0 0;
  }
  .sbm-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
  }
  .sbm-btn {
    border: 1px solid rgba(255, 255, 255, 0.25);
    background: rgba(255, 255, 255, 0.14);
    color: #ffffff !important;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none !important;
  }
  .sbm-btn:hover {
    background: rgba(255, 255, 255, 0.24);
    border-color: rgba(255, 255, 255, 0.45);
  }
  .sbm-btn-primary {
    background: #ffffff;
    color: #0f766e !important;
    border-color: #ffffff;
    font-weight: 700;
  }
  .sbm-btn-primary:hover {
    background: #f0fdfa;
  }
  .sbm-viewer-body {
    position: relative;
    flex: 1 1 auto;
    background: #334155;
    display: flex;
    flex-direction: column;
  }
  .sbm-loading {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.72);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 5;
    transition: opacity 0.2s ease;
  }
  .sbm-iframe {
    width: 100%;
    height: 100%;
    border: 0;
    flex: 1 1 auto;
    background: #475569;
  }
  .sbm-card.is-fullscreen {
    position: fixed;
    inset: 10px;
    z-index: 2050;
    height: calc(100vh - 20px);
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.45);
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="sbm-card" id="sbmPreviewCard">
      <div class="sbm-header">
        <div class="sbm-title-wrap">
          <div class="sbm-title-icon">
            <i class="fas fa-file-pdf"></i>
          </div>
          <div>
            <h1 class="sbm-title">Standar Biaya Masukan (SBM)</h1>
            <p class="sbm-subtitle">PMK Nomor 32 Tahun 2025 &bull; Standar Biaya Masukan Tahun Anggaran 2026</p>
          </div>
        </div>
        <div class="sbm-actions">
          <button type="button" class="sbm-btn" id="btnSbmReload" title="Muat Ulang Pratinjau PDF">
            <i class="fas fa-sync-alt"></i> Muat Ulang
          </button>
          <button type="button" class="sbm-btn" id="btnSbmFullscreen" title="Perbesar Pratinjau">
            <i class="fas fa-expand" id="iconSbmFullscreen"></i> <span id="lblSbmFullscreen">Layar Penuh</span>
          </button>
          <a href="<?= esc($sbmPreviewUrl ?? '/sinadi/sbm/pdf'); ?>?download=1" class="sbm-btn sbm-btn-primary" title="Unduh Dokumen PDF SBM">
            <i class="fas fa-download"></i> Unduh PDF
          </a>
        </div>
      </div>

      <div class="sbm-viewer-body">
        <div class="sbm-loading" id="sbmLoadingOverlay">
          <i class="fas fa-circle-notch fa-spin fa-2x mb-2" style="color: #2dd4bf;"></i>
          <div class="font-weight-bold" style="font-size: 0.85rem;">Memuat Pratinjau Dokumen SBM...</div>
          <small class="text-white-50 mt-1">PMK Nomor 32 Tahun 2025 (2025pmkeuangan032.pdf)</small>
        </div>
        <iframe
          id="sbmPdfFrame"
          class="sbm-iframe"
          src="<?= esc($sbmPreviewUrl ?? '/sinadi/sbm/pdf'); ?>#view=FitH"
          title="Pratinjau PDF Standar Biaya Masukan (SBM)"></iframe>
      </div>
    </div>
  </div>
</section>

<script>
  $(function() {
    var $frame = $('#sbmPdfFrame');
    var $loading = $('#sbmLoadingOverlay');
    var $card = $('#sbmPreviewCard');
    var hideTimer = setTimeout(function() {
      $loading.fadeOut(200);
    }, 4500);

    $frame.on('load', function() {
      clearTimeout(hideTimer);
      $loading.fadeOut(180);
    });

    $('#btnSbmReload').on('click', function() {
      $loading.show();
      var baseSrc = '<?= esc($sbmPreviewUrl ?? '/sinadi/sbm/pdf', 'js'); ?>';
      $frame.attr('src', baseSrc + '?t=' + Date.now() + '#view=FitH');
      hideTimer = setTimeout(function() {
        $loading.fadeOut(200);
      }, 4500);
    });

    $('#btnSbmFullscreen').on('click', function() {
      $card.toggleClass('is-fullscreen');
      var isFull = $card.hasClass('is-fullscreen');
      $('#iconSbmFullscreen').attr('class', isFull ? 'fas fa-compress' : 'fas fa-expand');
      $('#lblSbmFullscreen').text(isFull ? 'Keluar Layar Penuh' : 'Layar Penuh');
    });

    $(document).on('keydown', function(e) {
      if (e.key === 'Escape' && $card.hasClass('is-fullscreen')) {
        $card.removeClass('is-fullscreen');
        $('#iconSbmFullscreen').attr('class', 'fas fa-expand');
        $('#lblSbmFullscreen').text('Layar Penuh');
      }
    });
  });
</script>

<?= $this->endSection(); ?>
