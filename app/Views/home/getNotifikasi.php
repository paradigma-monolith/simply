<?php foreach ($notifikasi as $numberOf => $data) : ?>
  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#btn-notifikasi-read" data-notif-id="<?= $data['id_notifikasi']; ?>" data-notif-pesan="<?= htmlspecialchars($data['pesan']); ?>" data-notif-pengirim="<?= name_capitalize($data['nama_pm']); ?>" data-notif-waktu="<?= selisi_waktu($data['created_at']); ?>" <?= $data['status'] == "Belum Dibaca" ? 'style="background-color: rgba(22, 179, 172,.1)
;"' : ''; ?>>
    <!-- Message Start -->
    <div class="media">
      <i class="fas fa-user fa-4x img-size-50 mr-3 img-circle"></i>
      <div class="media-body">
        <h3 class="dropdown-item-title" data-toggle="tooltip" data-placement="bottom" title="<?= $data['nama_pm']; ?>">
          <?= strlen(name_capitalize($data['nama_pm'])) > 24 ? substr(name_capitalize($data['nama_pm']), 0, 24) . '...' : name_capitalize($data['nama_pm']); ?>
        </h3>
        <?php
        $pattern = '/^' . preg_quote($data['nama_pm'], '/') . '\b/';
        $result = preg_replace($pattern, '', strip_tags($data['pesan']));
        $hasilPesan = trim($result);
        ?>
        <p class="text-sm" data-toggle="tooltip" data-placement="bottom" title="<?= $hasilPesan; ?>"><?= strlen($hasilPesan) > 24 ? substr($hasilPesan, 0, 24) . '...' : $hasilPesan; ?></p>
        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <?= selisi_waktu($data['created_at']); ?></p>
      </div>
    </div>
    <!-- Message End -->
  </a>
  <?php if ($numberOf > 3) : ?>
    <?php break; ?>
  <?php endif; ?>
<?php endforeach; ?>
<div class="dropdown-divider"></div>
<a href="/home/profil" class="dropdown-item dropdown-footer">Lihat Semua Notif <span class="badge badge-danger"><?= (count($belumDibaca) - 4) > 0 ? (count($belumDibaca) - 4) : ''; ?></span></a>