<?php $usersList = is_array($dataSideRightUser ?? null) ? $dataSideRightUser : []; ?>
<div class="right-user-header">
  <div class="right-user-title">
    <span><i class="fas fa-users mr-1" style="color: #0d9488;"></i> Direktori Pegawai</span>
    <span class="badge badge-light border text-muted" style="font-size: 0.7rem;"><?= count($usersList); ?></span>
  </div>
  <div class="position-relative">
    <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; color: #94a3b8;"></i>
    <input type="search" id="rightUserSearch" class="right-user-search" placeholder="Cari nama atau email..." autocomplete="off">
  </div>
</div>

<ul class="right-user-list" id="rightUserList">
  <?php foreach ($usersList as $data) : ?>
    <?php
    $namaFormatted = name_capitalize((string) ($data['nama'] ?? ''));
    $emailStr = trim((string) ($data['email'] ?? ''));
    $avatarImg = (!empty($data['image']) && is_file(FCPATH . 'dist/img/avatar/' . $data['image']))
      ? '/dist/img/avatar/' . esc($data['image'], 'attr')
      : '/dist/img/boxed-bg.jpg';
    ?>
    <li class="right-user-item" data-search="<?= esc(strtolower($namaFormatted . ' ' . $emailStr), 'attr'); ?>">
      <div class="d-flex align-items-center flex-grow-1" style="gap: 10px; min-width: 0;" onclick="viewProfile(<?= (int) $data['id_user']; ?>)">
        <img src="<?= $avatarImg; ?>" onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" class="right-user-avatar" alt="Avatar" loading="lazy" decoding="async" />
        <div class="right-user-meta">
          <div class="right-user-name" title="<?= esc($namaFormatted, 'attr'); ?>"><?= esc($namaFormatted); ?></div>
          <div class="right-user-email" title="<?= esc($emailStr, 'attr'); ?>"><?= $emailStr !== '' ? esc($emailStr) : 'Tidak ada email'; ?></div>
        </div>
      </div>
      <?php if ($emailStr !== '') : ?>
        <button type="button" class="right-user-mail-btn" onclick="openBoxEmail(this)" data-email="<?= esc($emailStr, 'attr'); ?>" title="Kirim Email">
          <i class="fas fa-envelope" style="font-size: 0.78rem;"></i>
        </button>
      <?php else : ?>
        <span class="right-user-mail-btn is-disabled" title="Tidak ada Email">
          <i class="fas fa-envelope" style="font-size: 0.78rem;"></i>
        </span>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>

<script>
  (function() {
    var input = document.getElementById('rightUserSearch');
    var list = document.getElementById('rightUserList');
    if (!input || !list) return;
    input.addEventListener('input', function() {
      var q = (this.value || '').trim().toLowerCase();
      var items = list.querySelectorAll('.right-user-item');
      for (var i = 0; i < items.length; i++) {
        var hay = items[i].getAttribute('data-search') || '';
        items[i].style.display = (q === '' || hay.indexOf(q) !== -1) ? '' : 'none';
      }
    });
  })();
</script>
