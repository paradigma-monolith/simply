<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMPLY — Masuk Sistem | BKK Kelas I Bandung</title>

  <!-- Google Fonts (dengan fallback sistem yang rapi) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

  <!-- Local Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">

  <style>
    :root {
      --bg-deep: #04131d;
      --bg-mid: #082334;
      --bg-teal: #0b3648;
      --brand-teal: #14b8a6;
      --brand-teal-dark: #0d9488;
      --brand-mint: #5eead4;
      --brand-sky: #38bdf8;
      --brand-blue: #0284c7;
      --surface-light: rgba(255, 255, 255, 0.97);
      --text-ink: #0f172a;
      --text-muted: #475569;
      --text-subtle: #64748b;
      --border-light: #e2e8f0;
      --mx: 50%;
      --my: 50%;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      min-height: 100%;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      color: #f8fafc;
      background:
        radial-gradient(circle at 12% 18%, rgba(20, 184, 166, 0.24) 0%, transparent 42%),
        radial-gradient(circle at 88% 82%, rgba(56, 189, 248, 0.22) 0%, transparent 44%),
        radial-gradient(circle at 52% 48%, rgba(13, 148, 136, 0.14) 0%, transparent 55%),
        linear-gradient(145deg, var(--bg-deep) 0%, var(--bg-mid) 52%, var(--bg-teal) 100%);
      background-attachment: fixed;
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
      display: flex;
      flex-direction: column;
      -webkit-font-smoothing: antialiased;
    }

    /* Ambient interactive spotlight & precision dot matrix */
    .ambient-backdrop {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .ambient-grid {
      position: absolute;
      inset: 0;
      background-image:
        radial-gradient(rgba(94, 234, 212, 0.16) 1px, transparent 1px),
        linear-gradient(rgba(56, 189, 248, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
      background-size: 32px 32px, 96px 96px, 96px 96px;
      mask-image: radial-gradient(ellipse 85% 75% at 50% 50%, rgba(0, 0, 0, 0.85), transparent 100%);
      -webkit-mask-image: radial-gradient(ellipse 85% 75% at 50% 50%, rgba(0, 0, 0, 0.85), transparent 100%);
    }

    .ambient-spotlight {
      position: absolute;
      inset: 0;
      background: radial-gradient(620px circle at var(--mx) var(--my), rgba(45, 212, 191, 0.13), transparent 55%);
      transition: opacity 0.3s ease;
    }

    .aurora-orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(78px);
      opacity: 0.45;
      will-change: transform;
    }

    .aurora-orb.orb-1 {
      width: 420px;
      height: 420px;
      top: -110px;
      left: -80px;
      background: radial-gradient(circle, rgba(20, 184, 166, 0.65), transparent 70%);
      animation: floatOrb1 16s ease-in-out infinite alternate;
    }

    .aurora-orb.orb-2 {
      width: 460px;
      height: 460px;
      bottom: -140px;
      right: -90px;
      background: radial-gradient(circle, rgba(56, 189, 248, 0.55), transparent 70%);
      animation: floatOrb2 20s ease-in-out infinite alternate;
    }

    .aurora-orb.orb-3 {
      width: 300px;
      height: 300px;
      top: 42%;
      left: 44%;
      background: radial-gradient(circle, rgba(94, 234, 212, 0.32), transparent 70%);
      animation: floatOrb1 22s ease-in-out infinite alternate-reverse;
    }

    #neuralCanvas {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      opacity: 0.55;
    }

    @keyframes floatOrb1 {
      0% { transform: translate3d(0, 0, 0) scale(1); }
      100% { transform: translate3d(42px, 28px, 0) scale(1.1); }
    }

    @keyframes floatOrb2 {
      0% { transform: translate3d(0, 0, 0) scale(1); }
      100% { transform: translate3d(-46px, -32px, 0) scale(1.12); }
    }

    /* Main Stage */
    .login-stage {
      position: relative;
      z-index: 2;
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px 20px;
    }

    /* Unified Split Glass Shell */
    .portal-shell {
      width: 100%;
      max-width: 1040px;
      display: grid;
      grid-template-columns: 1.12fr 0.88fr;
      border-radius: 28px;
      overflow: hidden;
      background: rgba(8, 28, 41, 0.62);
      backdrop-filter: blur(22px);
      -webkit-backdrop-filter: blur(22px);
      border: 1px solid rgba(148, 226, 213, 0.2);
      box-shadow:
        0 30px 80px rgba(2, 12, 20, 0.55),
        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
      animation: shellReveal 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes shellReveal {
      from {
        opacity: 0;
        transform: translateY(18px) scale(0.985);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* LEFT SHOWCASE PANEL */
    .showcase-panel {
      position: relative;
      padding: 40px 42px 36px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 24px;
      background:
        radial-gradient(circle at 0% 0%, rgba(20, 184, 166, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 100% 100%, rgba(56, 189, 248, 0.16) 0%, transparent 52%),
        linear-gradient(160deg, rgba(7, 30, 44, 0.88) 0%, rgba(5, 20, 31, 0.94) 100%);
      overflow: hidden;
    }

    .showcase-panel::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(125deg, rgba(255, 255, 255, 0.06) 0%, transparent 38%, transparent 100%);
      pointer-events: none;
    }

    .brand-topbar {
      display: flex;
      align-items: center;
      gap: 14px;
      position: relative;
      z-index: 1;
    }

    .logo-pair {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 11px;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 8px 22px rgba(2, 14, 24, 0.25);
      flex-shrink: 0;
    }

    .logo-pair img {
      height: 34px;
      width: auto;
      display: block;
      object-fit: contain;
    }

    .logo-divider {
      width: 1px;
      height: 22px;
      background: rgba(15, 23, 42, 0.14);
    }

    .agency-meta {
      display: flex;
      flex-direction: column;
      gap: 2px;
      min-width: 0;
    }

    .agency-eyebrow {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--brand-mint);
    }

    .agency-name {
      font-size: 0.86rem;
      font-weight: 600;
      color: rgba(241, 245, 249, 0.92);
      line-height: 1.3;
    }

    .hero-block {
      position: relative;
      z-index: 1;
    }

    .status-chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 12px;
      border-radius: 999px;
      background: rgba(20, 184, 166, 0.14);
      border: 1px solid rgba(94, 234, 212, 0.3);
      font-size: 0.73rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      color: var(--brand-mint);
      margin-bottom: 14px;
    }

    .live-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #2dd4bf;
      box-shadow: 0 0 0 0 rgba(45, 212, 191, 0.7);
      animation: pulseRing 2s infinite;
    }

    @keyframes pulseRing {
      0% { box-shadow: 0 0 0 0 rgba(45, 212, 191, 0.75); }
      70% { box-shadow: 0 0 0 8px rgba(45, 212, 191, 0); }
      100% { box-shadow: 0 0 0 0 rgba(45, 212, 191, 0); }
    }

    .brand-title {
      font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(2.1rem, 3.2vw, 2.75rem);
      font-weight: 700;
      letter-spacing: -0.02em;
      line-height: 1.05;
      margin-bottom: 8px;
      display: flex;
      align-items: baseline;
      gap: 10px;
      flex-wrap: wrap;
    }

    .brand-title .gradient-word {
      background: linear-gradient(120deg, #ffffff 10%, #99f6e4 55%, #38bdf8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .brand-tagline {
      font-size: 0.98rem;
      font-weight: 500;
      color: rgba(226, 232, 240, 0.9);
      margin-bottom: 8px;
    }

    .brand-desc {
      font-size: 0.85rem;
      line-height: 1.6;
      color: rgba(148, 163, 184, 0.92);
      max-width: 44ch;
    }

    /* SIMPLY V3 COOLDOWN TEASER CARD (Tanpa Menampilkan Waktu) */
    .v3-cooldown-card {
      position: relative;
      z-index: 1;
      border-radius: 20px;
      padding: 20px 22px;
      background: linear-gradient(145deg, rgba(15, 48, 66, 0.72) 0%, rgba(9, 29, 43, 0.86) 100%);
      border: 1px solid rgba(94, 234, 212, 0.28);
      box-shadow:
        0 14px 36px rgba(2, 12, 22, 0.34),
        inset 0 1px 0 rgba(255, 255, 255, 0.08);
      overflow: hidden;
    }

    .v3-cooldown-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -60%;
      width: 55%;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--brand-mint), var(--brand-sky), transparent);
      animation: topScanBeam 4.2s ease-in-out infinite;
    }

    @keyframes topScanBeam {
      0% { left: -60%; opacity: 0.2; }
      50% { opacity: 1; }
      100% { left: 115%; opacity: 0.2; }
    }

    .v3-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 12px;
    }

    .v3-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #5eead4;
      padding: 5px 10px;
      border-radius: 8px;
      background: rgba(20, 184, 166, 0.16);
      border: 1px solid rgba(94, 234, 212, 0.3);
    }

    .v3-radar {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #38bdf8;
      position: relative;
    }

    .v3-radar::after {
      content: '';
      position: absolute;
      inset: -4px;
      border-radius: 50%;
      border: 1.5px solid rgba(56, 189, 248, 0.75);
      animation: radarPing 1.9s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes radarPing {
      0% { transform: scale(0.75); opacity: 0.95; }
      100% { transform: scale(2.1); opacity: 0; }
    }

    .v3-version-pill {
      font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
      font-size: 0.72rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 999px;
      background: rgba(56, 189, 248, 0.14);
      color: #bae6fd;
      border: 1px solid rgba(56, 189, 248, 0.3);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .v3-title {
      font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
      font-size: 1.08rem;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .v3-title i {
      color: #38bdf8;
      font-size: 0.95rem;
    }

    .v3-desc {
      font-size: 0.81rem;
      line-height: 1.55;
      color: rgba(203, 213, 225, 0.88);
      margin-bottom: 14px;
    }

    /* Animated Indeterminate Cooldown Wave Bar (No timer / no numbers) */
    .cooldown-track-wrap {
      margin-bottom: 14px;
    }

    .cooldown-track-labels {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      flex-wrap: wrap;
      font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
      font-size: 0.69rem;
      color: rgba(186, 230, 253, 0.88);
      margin-bottom: 7px;
      letter-spacing: 0.04em;
    }

    .cooldown-status-text {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #99f6e4;
    }

    .cooldown-track {
      position: relative;
      height: 7px;
      border-radius: 999px;
      background: rgba(15, 23, 42, 0.65);
      border: 1px solid rgba(148, 226, 213, 0.16);
      overflow: hidden;
    }

    .cooldown-wave {
      position: absolute;
      top: 0;
      bottom: 0;
      width: 44%;
      border-radius: 999px;
      background: linear-gradient(90deg, rgba(20, 184, 166, 0.2), #2dd4bf, #38bdf8, rgba(56, 189, 248, 0.25));
      box-shadow: 0 0 14px rgba(45, 212, 191, 0.7);
      animation: cooldownSweep 2.8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    }

    @keyframes cooldownSweep {
      0% { left: -44%; }
      50% { left: 56%; }
      100% { left: 102%; }
    }

    /* Release Phase Pills */
    .v3-phases {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 8px;
      margin-bottom: 12px;
    }

    .v3-phase {
      display: flex;
      align-items: center;
      gap: 7px;
      padding: 7px 9px;
      border-radius: 10px;
      font-size: 0.71rem;
      font-weight: 600;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.08);
      color: rgba(203, 213, 225, 0.85);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .v3-phase.done {
      border-color: rgba(45, 212, 191, 0.28);
      background: rgba(20, 184, 166, 0.12);
      color: #ccfbf1;
    }

    .v3-phase.done i {
      color: #2dd4bf;
    }

    .v3-phase.active {
      border-color: rgba(56, 189, 248, 0.42);
      background: rgba(56, 189, 248, 0.14);
      color: #e0f2fe;
    }

    .v3-phase.active i {
      color: #38bdf8;
      animation: spinSlow 4s linear infinite;
    }

    .v3-phase.upcoming {
      color: rgba(148, 163, 184, 0.82);
    }

    @keyframes spinSlow {
      to { transform: rotate(360deg); }
    }

    .v3-note {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.74rem;
      color: rgba(148, 163, 184, 0.92);
      padding-top: 10px;
      border-top: 1px dashed rgba(148, 226, 213, 0.18);
    }

    .v3-note i {
      color: var(--brand-mint);
      flex-shrink: 0;
    }

    /* Feature micro-chips */
    .feature-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      position: relative;
      z-index: 1;
    }

    .feature-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.74rem;
      font-weight: 500;
      color: rgba(226, 232, 240, 0.82);
      padding: 6px 11px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .feature-pill i {
      color: var(--brand-mint);
      font-size: 0.72rem;
    }

    /* RIGHT LOGIN PANEL (Luminous Crisp Light Surface) */
    .auth-panel {
      background: var(--surface-light);
      color: var(--text-ink);
      padding: 42px 38px 34px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    .auth-header {
      margin-bottom: 24px;
    }

    .auth-kicker {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--brand-teal-dark);
      background: #f0fdfa;
      border: 1px solid #ccfbf1;
      padding: 5px 11px;
      border-radius: 999px;
      margin-bottom: 12px;
    }

    .auth-title {
      font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--text-ink);
      letter-spacing: -0.02em;
      margin-bottom: 6px;
    }

    .auth-subtitle {
      font-size: 0.88rem;
      color: var(--text-muted);
      line-height: 1.5;
    }

    /* Alerts */
    .flash-alert {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 12px 14px;
      border-radius: 13px;
      font-size: 0.84rem;
      font-weight: 500;
      line-height: 1.45;
      margin-bottom: 20px;
      border: 1px solid transparent;
    }

    .flash-alert i {
      margin-top: 2px;
      font-size: 0.95rem;
      flex-shrink: 0;
    }

    .flash-alert.success {
      background: #ecfdf5;
      border-color: #a7f3d0;
      color: #065f46;
    }

    .flash-alert.danger {
      background: #fef2f2;
      border-color: #fecaca;
      color: #991b1b;
    }

    .flash-alert.info {
      background: #f0f9ff;
      border-color: #bae6fd;
      color: #075985;
    }

    /* Form Controls */
    .field-group {
      margin-bottom: 18px;
    }

    .field-label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.81rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 7px;
    }

    .caps-badge {
      display: none;
      align-items: center;
      gap: 4px;
      font-size: 0.7rem;
      font-weight: 600;
      color: #b45309;
      background: #fef3c7;
      border: 1px solid #fde68a;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .caps-badge.show {
      display: inline-flex;
    }

    .input-shell {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon-left {
      position: absolute;
      left: 15px;
      color: #94a3b8;
      font-size: 0.95rem;
      pointer-events: none;
      transition: color 0.2s ease;
    }

    .field-input {
      width: 100%;
      height: 49px;
      padding: 0 46px 0 43px;
      border-radius: 14px;
      border: 1.5px solid var(--border-light);
      background: #f8fafc;
      color: var(--text-ink);
      font-family: inherit;
      font-size: 0.93rem;
      font-weight: 500;
      transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    }

    .field-input::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    .field-input:hover {
      border-color: #cbd5e1;
      background: #ffffff;
    }

    .field-input:focus {
      outline: none;
      background: #ffffff;
      border-color: var(--brand-teal-dark);
      box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.16);
    }

    .input-shell:focus-within .input-icon-left {
      color: var(--brand-teal-dark);
    }

    .btn-eye {
      position: absolute;
      right: 10px;
      width: 34px;
      height: 34px;
      border: none;
      background: transparent;
      border-radius: 10px;
      color: #64748b;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.2s ease, color 0.2s ease;
    }

    .btn-eye:hover {
      background: #f1f5f9;
      color: var(--brand-teal-dark);
    }

    .btn-eye:focus-visible {
      outline: 2px solid var(--brand-teal);
      outline-offset: 1px;
    }

    /* Submit CTA */
    .btn-submit {
      width: 100%;
      height: 50px;
      margin-top: 6px;
      border: none;
      border-radius: 14px;
      background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);
      color: #ffffff;
      font-family: inherit;
      font-size: 0.95rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 12px 26px rgba(13, 148, 136, 0.28);
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 75%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent);
      transform: skewX(-20deg);
      transition: left 0.55s ease;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 32px rgba(2, 132, 199, 0.34);
      filter: brightness(1.03);
    }

    .btn-submit:hover::before {
      left: 135%;
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit:disabled {
      opacity: 0.82;
      cursor: wait;
      transform: none;
    }

    .security-strip {
      margin-top: 18px;
      padding: 10px 12px;
      border-radius: 12px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      font-size: 0.75rem;
      color: var(--text-subtle);
    }

    .security-strip span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .security-strip i {
      color: var(--brand-teal-dark);
    }

    .auth-footer {
      margin-top: 22px;
      padding-top: 16px;
      border-top: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
      font-size: 0.76rem;
      color: var(--text-subtle);
    }

    .wbs-inline-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--brand-teal-dark);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .wbs-inline-link:hover {
      color: var(--brand-blue);
      text-decoration: underline;
    }

    /* Floating WBS Pill */
    .wbs-fab {
      position: fixed;
      right: 22px;
      bottom: 22px;
      z-index: 50;
      display: inline-flex;
      align-items: center;
      gap: 9px;
      padding: 8px 14px 8px 9px;
      border-radius: 999px;
      text-decoration: none;
      color: #f8fafc;
      background: rgba(7, 30, 44, 0.86);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(94, 234, 212, 0.35);
      box-shadow: 0 12px 30px rgba(2, 12, 22, 0.42);
      transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .wbs-fab:hover {
      transform: translateY(-3px);
      border-color: rgba(94, 234, 212, 0.75);
      box-shadow: 0 16px 36px rgba(20, 184, 166, 0.34);
      color: #ffffff;
    }

    .wbs-fab-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, #14b8a6, #0284c7);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 0.85rem;
      flex-shrink: 0;
    }

    .wbs-fab-text {
      display: flex;
      flex-direction: column;
      line-height: 1.18;
    }

    .wbs-fab-label {
      font-size: 0.64rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: var(--brand-mint);
    }

    .wbs-fab-sub {
      font-size: 0.74rem;
      font-weight: 600;
      color: #f8fafc;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1280px) {
      .wbs-fab {
        padding: 8px;
      }

      .wbs-fab-text {
        display: none;
      }
    }

    @media (max-width: 960px) {
      .login-stage {
        padding: 20px 16px 28px;
      }

      .portal-shell {
        max-width: 520px;
        grid-template-columns: 1fr;
        border-radius: 24px;
      }

      .showcase-panel {
        padding: 28px 24px 24px;
        gap: 18px;
      }

      .brand-desc,
      .feature-pills,
      .wbs-fab {
        display: none;
      }

      .auth-panel {
        padding: 30px 24px 26px;
      }

      .v3-phases {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .brand-topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .auth-footer {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>

<body>
  <!-- Ambient Background Layers -->
  <div class="ambient-backdrop" aria-hidden="true">
    <div class="aurora-orb orb-1"></div>
    <div class="aurora-orb orb-2"></div>
    <div class="aurora-orb orb-3"></div>
    <canvas id="neuralCanvas"></canvas>
    <div class="ambient-grid"></div>
    <div class="ambient-spotlight"></div>
  </div>

  <main class="login-stage">
    <section class="portal-shell" aria-label="Portal Masuk SIMPLY">

      <!-- LEFT: Brand & SIMPLY v3 Cooldown Showcase -->
      <div class="showcase-panel">
        <header class="brand-topbar">
          <div class="logo-pair">
            <img src="/dist/img/logo-bkk-no-margin.png" alt="Logo BKK Kelas I Bandung">
            <span class="logo-divider" aria-hidden="true"></span>
            <img src="/dist/img/LOGO_KARANTINA.png" alt="Logo Kekarantinaan Kesehatan">
          </div>
          <div class="agency-meta">
            <span class="agency-eyebrow">Kementerian Kesehatan RI</span>
            <span class="agency-name">Balai Kekarantinaan Kesehatan Kelas I Bandung</span>
          </div>
        </header>

        <div class="hero-block">
          <div class="status-chip">
            <span class="live-dot" aria-hidden="true"></span>
            PORTAL TERPADU • BKK BANDUNG
          </div>
          <h1 class="brand-title">
            <span class="gradient-word">SIMPLY</span>
          </h1>
          <p class="brand-tagline">Sistem Informasi Manajemen Pelayanan</p>
          <p class="brand-desc">
            Ekosistem digital terpadu untuk pengelolaan layanan, administrasi, dan pemantauan operasional secara cepat, akurat, dan transparan.
          </p>
        </div>

        <!-- Informasi Cooldown Menuju SIMPLY Versi 3 (Tanpa Menampilkan Waktu) -->
        <aside class="v3-cooldown-card" aria-label="Informasi Cooldown Rilis SIMPLY Versi 3">
          <div class="v3-header">
            <span class="v3-badge">
              <span class="v3-radar" aria-hidden="true"></span>
              COOLDOWN RILIS
            </span>
            <span class="v3-version-pill">
              v2 <i class="fas fa-arrow-right" aria-hidden="true"></i> v3.0
            </span>
          </div>

          <h2 class="v3-title">
            <i class="fas fa-rocket" aria-hidden="true"></i>
            Menuju SIMPLY Versi 3
          </h2>
          <p class="v3-desc">
            Pembaruan generasi terbaru dengan performa lebih responsif, antarmuka lebih segar, dan pengalaman kerja lebih cerdas sedang memasuki fase persiapan peluncuran.
          </p>

          <div class="cooldown-track-wrap">
            <div class="cooldown-track-labels">
              <span class="cooldown-status-text">
                <i class="fas fa-wave-square" aria-hidden="true"></i>
                STATUS COOLDOWN AKTIF
              </span>
              <span>SEGERA HADIR</span>
            </div>
            <div class="cooldown-track" role="presentation">
              <div class="cooldown-wave"></div>
            </div>
          </div>

          <div class="v3-phases">
            <div class="v3-phase done">
              <i class="fas fa-check-circle" aria-hidden="true"></i>
              <span>Arsitektur Baru</span>
            </div>
            <div class="v3-phase active">
              <i class="fas fa-sync-alt" aria-hidden="true"></i>
              <span>Tahap Finalisasi</span>
            </div>
            <div class="v3-phase upcoming">
              <i class="fas fa-lock" aria-hidden="true"></i>
              <span>Rilis v3.0</span>
            </div>
          </div>

          <div class="v3-note">
            <i class="fas fa-shield-alt" aria-hidden="true"></i>
            <span>Waktu peluncuran resmi belum dipublikasikan — nantikan kehadiran SIMPLY Versi 3 segera.</span>
          </div>
        </aside>

        <div class="feature-pills" aria-hidden="true">
          <span class="feature-pill"><i class="fas fa-bolt"></i> Cepat &amp; Responsif</span>
          <span class="feature-pill"><i class="fas fa-layer-group"></i> Layanan Terintegrasi</span>
          <span class="feature-pill"><i class="fas fa-shield-alt"></i> Akses Internal Aman</span>
        </div>
      </div>

      <!-- RIGHT: Crisp Luminous Login Form -->
      <div class="auth-panel">
        <div class="auth-header">
          <span class="auth-kicker">
            <i class="fas fa-fingerprint" aria-hidden="true"></i>
            Akses Pegawai
          </span>
          <h2 class="auth-title">Selamat Datang</h2>
          <p class="auth-subtitle">Masuk menggunakan NIP dan kata sandi akun Anda untuk melanjutkan ke sistem.</p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('pesan')) : ?>
          <div class="flash-alert success" role="alert">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <div><?= esc(session()->getFlashdata('pesan')); ?></div>
          </div>
        <?php elseif (session()->getFlashdata('error')) : ?>
          <div class="flash-alert danger" role="alert">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <div><?= esc(session()->getFlashdata('error')); ?></div>
          </div>
        <?php elseif (session()->getFlashdata('info')) : ?>
          <div class="flash-alert info" role="alert">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <div><?= esc(session()->getFlashdata('info')); ?></div>
          </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="/auth" method="post" id="loginForm">
          <input type="hidden" name="login" value="login2">

          <div class="field-group">
            <label for="username" class="field-label">
              <span>NIP / Nama Pengguna</span>
            </label>
            <div class="input-shell">
              <i class="fas fa-id-badge input-icon-left" aria-hidden="true"></i>
              <input
                type="text"
                class="field-input"
                id="username"
                name="username"
                placeholder="Masukkan NIP Pengguna"
                autocomplete="username"
                autofocus
                required>
            </div>
          </div>

          <div class="field-group">
            <label for="password" class="field-label">
              <span>Kata Sandi</span>
              <span id="capsLockNotice" class="caps-badge">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                Caps Lock Aktif
              </span>
            </label>
            <div class="input-shell">
              <i class="fas fa-lock input-icon-left" aria-hidden="true"></i>
              <input
                type="password"
                class="field-input"
                id="password"
                name="password"
                placeholder="Masukkan Kata Sandi"
                autocomplete="current-password"
                required>
              <button
                type="button"
                id="togglePasswordBtn"
                class="btn-eye"
                aria-label="Tampilkan kata sandi"
                title="Tampilkan / sembunyikan kata sandi">
                <i class="fas fa-eye" id="togglePasswordIcon" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <button type="submit" id="btnSubmitLogin" class="btn-submit">
            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
            <span>Masuk Sistem</span>
          </button>
        </form>

        <div class="security-strip">
          <span><i class="fas fa-lock" aria-hidden="true"></i> Sesi Terproteksi</span>
          <span><i class="fas fa-server" aria-hidden="true"></i> Portal Resmi BKK</span>
        </div>

        <footer class="auth-footer">
          <span>&copy; <?= date('Y'); ?> IT BKK Kelas I Bandung</span>
          <a href="https://wbs.kemkes.go.id/" target="_blank" rel="noopener noreferrer" class="wbs-inline-link">
            <i class="fas fa-bullhorn" aria-hidden="true"></i>
            Lapor WBS
          </a>
        </footer>
      </div>

    </section>
  </main>

  <!-- Floating WBS Action -->
  <a href="https://wbs.kemkes.go.id/" target="_blank" rel="noopener noreferrer" class="wbs-fab" aria-label="Whistle Blowing System Kemenkes" title="Whistle Blowing System Kemenkes">
    <span class="wbs-fab-icon"><i class="fas fa-bullhorn" aria-hidden="true"></i></span>
    <span class="wbs-fab-text">
      <span class="wbs-fab-label">WBS Kemenkes</span>
      <span class="wbs-fab-sub">Pengaduan</span>
    </span>
  </a>

  <script src="/js/inspectElement.js"></script>
  <script>
    (function () {
      var passwordInput = document.getElementById('password');
      var toggleBtn = document.getElementById('togglePasswordBtn');
      var toggleIcon = document.getElementById('togglePasswordIcon');
      var capsBadge = document.getElementById('capsLockNotice');
      var loginForm = document.getElementById('loginForm');
      var submitBtn = document.getElementById('btnSubmitLogin');

      if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
          var isPassword = passwordInput.type === 'password';
          passwordInput.type = isPassword ? 'text' : 'password';
          toggleIcon.classList.toggle('fa-eye', !isPassword);
          toggleIcon.classList.toggle('fa-eye-slash', isPassword);
          toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
      }

      if (passwordInput && capsBadge) {
        var checkCaps = function (event) {
          if (typeof event.getModifierState === 'function') {
            capsBadge.classList.toggle('show', event.getModifierState('CapsLock'));
          }
        };
        passwordInput.addEventListener('keydown', checkCaps);
        passwordInput.addEventListener('keyup', checkCaps);
        passwordInput.addEventListener('blur', function () {
          capsBadge.classList.remove('show');
        });
      }

      if (loginForm && submitBtn) {
        loginForm.addEventListener('submit', function () {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i><span>Memverifikasi...</span>';
        });
      }

      // Subtle interactive cursor spotlight
      var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!prefersReducedMotion) {
        var rafSpotlight = null;
        document.addEventListener('pointermove', function (e) {
          if (rafSpotlight) return;
          rafSpotlight = requestAnimationFrame(function () {
            document.documentElement.style.setProperty('--mx', e.clientX + 'px');
            document.documentElement.style.setProperty('--my', e.clientY + 'px');
            rafSpotlight = null;
          });
        }, { passive: true });
      }

      // Lightweight Ambient Constellation Canvas
      var canvas = document.getElementById('neuralCanvas');
      if (canvas && canvas.getContext && !prefersReducedMotion) {
        var ctx = canvas.getContext('2d');
        var width = 0;
        var height = 0;
        var nodes = [];
        var nodeCount = window.innerWidth < 768 ? 16 : 30;

        function resizeCanvas() {
          var dpr = Math.min(window.devicePixelRatio || 1, 2);
          width = window.innerWidth;
          height = window.innerHeight;
          canvas.width = Math.floor(width * dpr);
          canvas.height = Math.floor(height * dpr);
          ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        function initNodes() {
          nodes = [];
          for (var i = 0; i < nodeCount; i++) {
            nodes.push({
              x: Math.random() * width,
              y: Math.random() * height,
              vx: (Math.random() - 0.5) * 0.35,
              vy: (Math.random() - 0.5) * 0.35,
              r: 1.3 + Math.random() * 1.4
            });
          }
        }

        function renderFrame() {
          ctx.clearRect(0, 0, width, height);
          for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            n.x += n.vx;
            n.y += n.vy;

            if (n.x < 0 || n.x > width) n.vx *= -1;
            if (n.y < 0 || n.y > height) n.vy *= -1;

            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(94, 234, 212, 0.55)';
            ctx.fill();

            for (var j = i + 1; j < nodes.length; j++) {
              var m = nodes[j];
              var dx = n.x - m.x;
              var dy = n.y - m.y;
              var distSq = dx * dx + dy * dy;
              if (distSq < 19600) {
                var alpha = (1 - Math.sqrt(distSq) / 140) * 0.22;
                ctx.beginPath();
                ctx.moveTo(n.x, n.y);
                ctx.lineTo(m.x, m.y);
                ctx.strokeStyle = 'rgba(56, 189, 248, ' + alpha.toFixed(3) + ')';
                ctx.lineWidth = 0.8;
                ctx.stroke();
              }
            }
          }
          requestAnimationFrame(renderFrame);
        }

        resizeCanvas();
        initNodes();
        renderFrame();
        window.addEventListener('resize', function () {
          resizeCanvas();
          initNodes();
        }, { passive: true });
      }
    })();
  </script>
</body>

</html>
