<style>
  .snf-page {
    --snf-primary: #0d9488;
    --snf-primary-dark: #0f766e;
    --snf-primary-soft: #f0fdfa;
    --snf-border: rgba(15, 23, 42, 0.08);
    --snf-text: #0f172a;
    --snf-muted: #64748b;
    --snf-radius: 14px;
    --snf-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    padding-bottom: 28px;
  }

  .snf-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: var(--snf-radius);
    padding: 16px 20px;
    color: #ffffff;
    box-shadow: 0 8px 22px rgba(4, 120, 121, 0.16);
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .snf-hero-title {
    font-size: 1.12rem;
    font-weight: 800;
    margin: 0 0 2px;
  }
  .snf-hero-sub {
    font-size: 0.79rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
  }
  .snf-back-btn {
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff !important;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 6px 13px;
    border-radius: 9px;
    text-decoration: none !important;
    transition: all 0.16s ease;
  }
  .snf-back-btn:hover {
    background: #ffffff;
    color: #0f766e !important;
  }

  .snf-section-card {
    background: #ffffff;
    border: 1px solid var(--snf-border);
    border-radius: var(--snf-radius);
    box-shadow: var(--snf-shadow);
    margin-bottom: 16px;
    overflow: hidden;
  }
  .snf-section-head {
    padding: 12px 18px;
    background: #f8fafc;
    border-bottom: 1px solid var(--snf-border);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }
  .snf-section-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--snf-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .snf-section-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: var(--snf-primary-soft);
    color: var(--snf-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
  }
  .snf-section-body {
    padding: 16px 18px;
  }

  /* Table Styling Inside Form */
  .snf-page .table td,
  .snf-page .table th {
    vertical-align: middle;
    padding: 9px 11px;
    border-color: #e2e8f0;
  }
  .snf-page .table label {
    margin-bottom: 4px;
    font-size: 0.77rem;
    font-weight: 700;
    color: #334155;
  }
  .snf-page .form-control {
    border-radius: 8px;
    font-size: 0.83rem;
  }

  /* Participant Block Styling */
  .sinadi-data-pegawai > div,
  .data-pegawai-tambahan > div {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
  }
  .btn-info-pegawai {
    border-radius: 0 !important;
    font-size: 0.82rem !important;
    font-weight: 800 !important;
    letter-spacing: 0.5px;
    padding: 9px 14px !important;
    text-align: left !important;
  }
  .btn-info-pegawai.btn-secondary {
    background: linear-gradient(90deg, #0f766e, #0d9488) !important;
    border-color: #0f766e !important;
  }
  .btn-info-pegawai.btn-danger {
    background: linear-gradient(90deg, #b91c1c, #dc2626) !important;
    border-color: #b91c1c !important;
  }
  .snf-cost-table {
    background: #f8fafc;
    margin-bottom: 0 !important;
  }
  .snf-cost-table tr:first-child td {
    background: #f1f5f9;
    font-size: 0.76rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .snf-cost-table .jumlah_transfer {
    background: #f0fdfa !important;
    border-color: #5eead4 !important;
    color: #0f766e !important;
    font-weight: 800 !important;
    font-size: 0.92rem !important;
  }
  .snf-cost-table .transport_jumlah,
  .snf-cost-table .harian_jumlah,
  .snf-cost-table .penginapan_jumlah {
    background: #f8fafc !important;
    font-weight: 700 !important;
    color: #334155 !important;
  }

  /* Live Grand Total Summary Bar */
  .snf-grand-bar {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.14);
  }
  .snf-grand-items {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
  }
  .snf-grand-item .snf-g-lbl {
    font-size: 0.68rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-weight: 700;
  }
  .snf-grand-item .snf-g-val {
    font-size: 0.9rem;
    font-weight: 700;
    color: #f8fafc;
  }
  .snf-grand-total-box {
    text-align: right;
    background: rgba(13, 148, 136, 0.22);
    border: 1px solid rgba(94, 234, 212, 0.35);
    padding: 8px 16px;
    border-radius: 10px;
  }
  .snf-grand-total-box .snf-g-lbl {
    font-size: 0.69rem;
    color: #5eead4;
    text-transform: uppercase;
    font-weight: 700;
  }
  .snf-grand-total-box .snf-g-val {
    font-size: 1.15rem;
    font-weight: 800;
    color: #ffffff;
  }

  @media (max-width: 767.98px) {
    .snf-section-body {
      padding: 10px;
    }
    .snf-grand-bar {
      flex-direction: column;
      align-items: stretch;
    }
    .snf-grand-total-box {
      text-align: left;
    }
  }
</style>

<!-- Live Grand Total Banner -->
<div class="snf-grand-bar">
  <div class="snf-grand-items">
    <div class="snf-grand-item">
      <div class="snf-g-lbl">Jumlah Peserta</div>
      <div class="snf-g-val" id="snLiveTotalPeserta">1 Orang</div>
    </div>
    <div class="snf-grand-item">
      <div class="snf-g-lbl">Total Transport</div>
      <div class="snf-g-val" id="snLiveTotalTransport">Rp 0</div>
    </div>
    <div class="snf-grand-item">
      <div class="snf-g-lbl">Total Uang Harian</div>
      <div class="snf-g-val" id="snLiveTotalHarian">Rp 0</div>
    </div>
    <div class="snf-grand-item">
      <div class="snf-g-lbl">Total Penginapan</div>
      <div class="snf-g-val" id="snLiveTotalPenginapan">Rp 0</div>
    </div>
  </div>
  <div class="snf-grand-total-box">
    <div class="snf-g-lbl">Estimasi Total Transfer</div>
    <div class="snf-g-val" id="snLiveGrandTotal">Rp 0</div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.custom-file-input').on('change', function() {
      var fileName = ($(this).val() || '').split('\\').pop();
      $(this).next('.custom-file-label').addClass('selected').html(fileName || 'Pilih file...');
    });

    // Pastikan seluruh fungsi perhitungan & grand total langsung aktif tanpa tergantung cache JS eksternal
    function parseIndoNum(str) {
      if (str === undefined || str === null) return 0;
      var cleaned = String(str).replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(/,/g, '.');
      var num = parseFloat(cleaned);
      return isNaN(num) ? 0 : num;
    }

    window.updateSinadiGrandTotal = function() {
      var totalPeserta = $('.perhitungan_anggaran').length;
      var sumTransport = 0;
      var sumHarian = 0;
      var sumPenginapan = 0;
      var sumTransfer = 0;

      $('.transport_jumlah').each(function() { sumTransport += parseIndoNum($(this).val()); });
      $('.harian_jumlah').each(function() { sumHarian += parseIndoNum($(this).val()); });
      $('.penginapan_jumlah').each(function() { sumPenginapan += parseIndoNum($(this).val()); });
      $('.jumlah_transfer').each(function() { sumTransfer += parseIndoNum($(this).val()); });

      $('#snLiveTotalPeserta').text(totalPeserta + ' Orang');
      $('#snLiveTotalTransport').text('Rp ' + sumTransport.toLocaleString('id-ID'));
      $('#snLiveTotalHarian').text('Rp ' + sumHarian.toLocaleString('id-ID'));
      $('#snLiveTotalPenginapan').text('Rp ' + sumPenginapan.toLocaleString('id-ID'));
      $('#snLiveGrandTotal').text('Rp ' + sumTransfer.toLocaleString('id-ID'));
    };

    window.sinadiPerhitunganAnggaran = function(e) {
      var $row = $(e).closest('.perhitungan_anggaran');
      var index = $('.perhitungan_anggaran').index($row);
      if (index < 0) index = 0;

      var tHari = parseFloat(($('.transport_hari').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;
      var tNom = parseFloat(($('.transport_transport').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;
      var hHari = parseFloat(($('.harian_hari').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;
      var hNom = parseFloat(($('.harian_unit_cost').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;
      var pKali = parseFloat(($('.penginapan_kali').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;
      var pNom = parseFloat(($('.penginapan_unit_cost').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.')) || 0;

      var tTotal = tHari * tNom;
      var hTotal = hHari * hNom;
      var pTotal = pKali * pNom;

      $('.transport_jumlah').eq(index).val(tTotal > 0 ? 'Rp' + tTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');
      $('.harian_jumlah').eq(index).val(hTotal > 0 ? 'Rp' + hTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');
      $('.penginapan_jumlah').eq(index).val(pTotal > 0 ? 'Rp' + pTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');

      var hasil = tTotal + hTotal + pTotal;
      $('.jumlah_transfer').eq(index).val('Rp' + hasil.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));

      if (e && $(e).hasClass('anggaran')) {
        var val = String($(e).val() || '').replace(/[^0-9,]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        $(e).val(val);
      }

      window.updateSinadiGrandTotal();
    };

    $('.anggaran').each(function() {
      var raw = String($(this).val() || '');
      if (raw !== '') {
        $(this).val(raw.replace('.', ','));
      }
    });
    $('.perhitungan_anggaran').each(function() {
      var firstInput = $(this).find('.anggaran').first()[0];
      if (firstInput) {
        window.sinadiPerhitunganAnggaran(firstInput);
      }
    });
    window.updateSinadiGrandTotal();
  });
</script>
