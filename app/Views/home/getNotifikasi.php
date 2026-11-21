<?php
$unreadTotal = isset($belumDibacaCount)
  ? (int) $belumDibacaCount
  : (isset($belumDibaca) && is_array($belumDibaca) ? count($belumDibaca) : 0);
$listNotif = is_array($notifikasi ?? null) ? $notifikasi : [];
?>
<div class="notif-dropdown-header">
  <span class="font-weight-bold text-dark" style="font-size: 0.84rem;">
    <i class="far fa-bell mr-1" style="color: #0d9488;"></i> Notifikasi
  </span>
  <?php if ($unreadTotal > 0): ?>
    <span class="badge badge-danger badge-pill" style="font-size: 0.7rem;"><?= $unreadTotal; ?> baru</span>
  <?php else: ?>
    <span class="badge badge-light border text-muted" style="font-size: 0.68rem;">Semua terbaca</span>
  <?php endif; ?>
</div>

<div class="notif-dropdown-list">
  <?php if (empty($listNotif)): ?>
    <div class="text-center text-muted py-4 px-3 small">
      <i class="far fa-bell-slash d-block mb-1" style="font-size: 1.3rem; opacity: 0.5;"></i>
      Belum ada notifikasi terbaru.
    </div>
  <?php else: ?>
    <?php foreach ($listNotif as $numberOf => $data) : ?>
      <?php
      $namaPengirim = name_capitalize((string) ($data['nama_pm'] ?? 'Sistem'));
      $initials = strtoupper(mb_substr(trim($namaPengirim), 0, 2));
      $pattern = '/^' . preg_quote((string) ($data['nama_pm'] ?? ''), '/') . '\b/';
      $hasilPesan = trim((string) preg_replace($pattern, '', strip_tags((string) ($data['pesan'] ?? ''))));
      $shortPesan = mb_strlen($hasilPesan) > 68 ? mb_substr($hasilPesan, 0, 68) . '...' : $hasilPesan;
      $isUnread = ($data['status'] ?? '') === 'Belum Dibaca';
      $waktuText = selisi_waktu($data['created_at']);
      ?>
      <a href="#"
         class="dropdown-item notif-item-row <?= $isUnread ? 'is-unread' : ''; ?>"
         data-toggle="modal"
         data-target="#btn-notifikasi-read"
         data-notif-id="<?= (int) $data['id_notifikasi']; ?>"
         data-notif-pesan="<?= htmlspecialchars((string) ($data['pesan'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
         data-notif-pengirim="<?= esc($namaPengirim, 'attr'); ?>"
         data-notif-waktu="<?= esc($waktuText, 'attr'); ?>">
        <div class="notif-avatar-circle"><?= esc($initials); ?></div>
        <div class="notif-item-content">
          <div class="notif-item-sender">
            <span class="text-truncate"><?= esc($namaPengirim); ?></span>
            <?php if ($isUnread): ?>
              <span class="notif-unread-dot" title="Belum dibaca"></span>
            <?php endif; ?>
          </div>
          <div class="notif-item-msg"><?= esc($shortPesan); ?></div>
          <div class="notif-item-time"><i class="far fa-clock mr-1"></i><?= esc($waktuText); ?></div>
        </div>
      </a>
      <?php if ($numberOf >= 4) : ?>
        <?php break; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<a href="/home/profil" class="dropdown-item notif-dropdown-footer">
  Lihat Semua Notifikasi
  <?php if ($unreadTotal > 5) : ?>
    <span class="badge badge-danger badge-pill ml-1">+<?= $unreadTotal - 5; ?></span>
  <?php endif; ?>
</a>
