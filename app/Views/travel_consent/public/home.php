<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Consent Forms | Balai Kekarantinaan Kesehatan Kelas I Bandung</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
  <style>
    :root {
      --tc-teal: #0d9488;
      --tc-teal-dark: #0f766e;
      --tc-slate: #0f172a;
    }
    body {
      background: linear-gradient(155deg, #f0fdfa 0%, #f8fafc 45%, #eff6ff 100%);
      min-height: 100vh;
      margin: 0;
      padding: 28px 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: var(--tc-slate);
    }
    .tc-home-wrap {
      max-width: 1040px;
      width: 100%;
    }
    .tc-hero {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 24px 28px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
      text-align: center;
      margin-bottom: 22px;
    }
    .tc-logos {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-bottom: 14px;
    }
    .tc-logos img {
      max-height: 70px;
      object-fit: contain;
    }
    .tc-badge-top {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #f0fdfa;
      color: #0f766e;
      border: 1px solid #99f6e4;
      padding: 4px 12px;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .tc-hero h1 {
      font-size: 1.65rem;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 4px;
    }
    .tc-hero p {
      font-size: 0.92rem;
      color: #64748b;
      margin: 0;
    }
    .tc-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
    .tc-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      padding: 22px;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: all 0.2s ease;
      text-decoration: none !important;
      color: inherit;
    }
    .tc-card:hover {
      transform: translateY(-4px);
      border-color: #2dd4bf;
      box-shadow: 0 14px 28px rgba(13, 148, 136, 0.12);
    }
    .tc-card-top {
      display: flex;
      align-items: flex-start;
      gap: 15px;
    }
    .tc-card-icon {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.45rem;
      flex-shrink: 0;
    }
    .tc-card-tag {
      display: inline-block;
      font-size: 0.69rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 2px 8px;
      border-radius: 6px;
      margin-bottom: 5px;
    }
    .tc-card-title {
      font-size: 1.05rem;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 4px;
      line-height: 1.3;
    }
    .tc-card-desc {
      font-size: 0.81rem;
      color: #64748b;
      margin: 0;
      line-height: 1.45;
    }
    .tc-card-foot {
      margin-top: 16px;
      padding-top: 12px;
      border-top: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }
    .tc-btn-open {
      background: #0d9488;
      color: #ffffff !important;
      font-size: 0.8rem;
      font-weight: 700;
      padding: 7px 14px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none !important;
      transition: background 0.15s ease;
    }
    .tc-btn-open:hover {
      background: #0f766e;
    }
    .tc-btn-copy {
      border: 1px solid #cbd5e1;
      background: #f8fafc;
      color: #475569;
      font-size: 0.75rem;
      font-weight: 700;
      padding: 6px 11px;
      border-radius: 9px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    .tc-btn-copy:hover {
      border-color: #0d9488;
      color: #0f766e;
      background: #f0fdfa;
    }

    @media (max-width: 767.98px) {
      body { padding: 14px 10px; }
      .tc-hero { padding: 18px 16px; }
      .tc-logos img { max-height: 52px; }
      .tc-hero h1 { font-size: 1.3rem; }
      .tc-grid { grid-template-columns: 1fr; gap: 12px; }
      .tc-card { padding: 16px; }
    }
  </style>
</head>
<body>

<div class="tc-home-wrap">
  <div class="tc-hero">
    <div class="tc-logos">
      <img src="<?= base_url('dist/img/logo-bkk-no-margin.png') ?>" alt="Logo BKK Bandung" onerror="this.style.display='none';">
      <img src="<?= base_url('dist/img/LOGO_KARANTINA.png') ?>" alt="Logo Karantina">
    </div>
    <div class="tc-badge-top">
      <i class="fas fa-shield-alt"></i> Portal Formulir Elektronik Kekarantinaan Kesehatan
    </div>
    <h1>Travel Consent &amp; Sanitation Inspection Forms</h1>
    <p>Balai Kekarantinaan Kesehatan Kelas I Bandung &bull; Kementerian Kesehatan RI</p>
  </div>

  <div class="tc-grid">
    <!-- 1. Aircraft Sanitation -->
    <div class="tc-card">
      <div class="tc-card-top">
        <div class="tc-card-icon" style="background:#eff6ff;color:#2563eb;">
          <i class="fas fa-plane-departure"></i>
        </div>
        <div>
          <span class="tc-card-tag" style="background:#eff6ff;color:#1d4ed8;">Udara &bull; Aircraft Check List</span>
          <h3 class="tc-card-title">Aircraft Sanitation Inspection</h3>
          <p class="tc-card-desc">Formulir pemeriksaan sanitasi kabin pesawat, toilet, galley, penyediaan air, makanan, dan vektor dilengkapi tanda tangan &amp; foto geotagging.</p>
        </div>
      </div>
      <div class="tc-card-foot">
        <button type="button" class="tc-btn-copy" onclick="copyFormUrl('<?= esc(base_url('travelconsent/public_aircraft'), 'js') ?>', this)">
          <i class="fas fa-link"></i> Salin Link
        </button>
        <a href="<?= base_url('travelconsent/public_aircraft') ?>" class="tc-btn-open">
          Isi Formulir <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- 2. Sanitasi Kapal -->
    <div class="tc-card">
      <div class="tc-card-top">
        <div class="tc-card-icon" style="background:#f0fdfa;color:#0d9488;">
          <i class="fas fa-ship"></i>
        </div>
        <div>
          <span class="tc-card-tag" style="background:#f0fdfa;color:#0f766e;">Laut &bull; Ship Sanitation</span>
          <h3 class="tc-card-title">Sanitasi Kapal (Ship Sanitation)</h3>
          <p class="tc-card-desc">Formulir Sertifikat Pengendalian / Pembebasan Sanitasi Kapal meliputi 32 area fasilitas kapal, bukti temuan, sampel, dan tindakan pengendalian.</p>
        </div>
      </div>
      <div class="tc-card-foot">
        <button type="button" class="tc-btn-copy" onclick="copyFormUrl('<?= esc(base_url('travelconsent/public_sanitasi'), 'js') ?>', this)">
          <i class="fas fa-link"></i> Salin Link
        </button>
        <a href="<?= base_url('travelconsent/public_sanitasi') ?>" class="tc-btn-open">
          Isi Formulir <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- 3. Alat Kesehatan P3K -->
    <div class="tc-card">
      <div class="tc-card-top">
        <div class="tc-card-icon" style="background:#fef3c7;color:#d97706;">
          <i class="fas fa-medkit"></i>
        </div>
        <div>
          <span class="tc-card-tag" style="background:#fef3c7;color:#b45309;">ICAO Annex 6 &bull; First Aid Kit</span>
          <h3 class="tc-card-title">Alat Kesehatan Kotak P3K &amp; Emergensi</h3>
          <p class="tc-card-desc">Daftar pemeriksaan dan pemakaian 17 item alat kesehatan kotak P3K serta 21 item alat kesehatan kotak emergensi penerbangan.</p>
        </div>
      </div>
      <div class="tc-card-foot">
        <button type="button" class="tc-btn-copy" onclick="copyFormUrl('<?= esc(base_url('travelconsent/public_alat'), 'js') ?>', this)">
          <i class="fas fa-link"></i> Salin Link
        </button>
        <a href="<?= base_url('travelconsent/public_alat') ?>" class="tc-btn-open">
          Isi Formulir <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- 4. Obat P3K -->
    <div class="tc-card">
      <div class="tc-card-top">
        <div class="tc-card-icon" style="background:#fce7f3;color:#db2777;">
          <i class="fas fa-pills"></i>
        </div>
        <div>
          <span class="tc-card-tag" style="background:#fce7f3;color:#be185d;">ICAO Annex 6 &bull; Emergency Medical</span>
          <h3 class="tc-card-title">Obat Kotak P3K &amp; Emergensi</h3>
          <p class="tc-card-desc">Daftar pemeriksaan ketersediaan dan pemakaian 6 golongan obat kotak P3K serta 15 jenis obat injeksi/oral kotak emergensi penerbangan.</p>
        </div>
      </div>
      <div class="tc-card-foot">
        <button type="button" class="tc-btn-copy" onclick="copyFormUrl('<?= esc(base_url('travelconsent/public_obat'), 'js') ?>', this)">
          <i class="fas fa-link"></i> Salin Link
        </button>
        <a href="<?= base_url('travelconsent/public_obat') ?>" class="tc-btn-open">
          Isi Formulir <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="text-center mt-4">
    <small class="text-muted">Copyright &copy; <?= date('Y') ?> IT Support Balai Kekarantinaan Kesehatan Kelas I Bandung</small>
  </div>
</div>

<script>
  function copyFormUrl(url, btn) {
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(url);
    } else {
      var temp = document.createElement('input');
      document.body.appendChild(temp);
      temp.value = url;
      temp.select();
      document.execCommand('copy');
      document.body.removeChild(temp);
    }
    var oldHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin';
    setTimeout(function() {
      btn.innerHTML = oldHtml;
    }, 1800);
  }
</script>
</body>
</html>
