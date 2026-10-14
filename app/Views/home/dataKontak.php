<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Kontak</h3>
  </div>
  <div class="card-body">
    <strong class="kontak0"><span onclick="openBoxEmail(this)" data-email="<?= $user['email']; ?>" data-toggle="tooltip" data-placement="top" title="Kirim Email"><i class="fas fa-envelope"></i> <?= $user['email']; ?></span></strong>
    <hr>
    <?php
    $no_telp = $user['no_telp'];
    if ($no_telp != "") {
      if (substr($no_telp, 0, 1) === '0') {
        // Ganti 0 di awal dengan 62
        $no_telp = 'https://wa.me/62' . substr($no_telp, 1);
      } else {
        // Jika tidak dimulai dengan 0, biarkan string tetap sama
        $no_telp = 'https://wa.me/' . $no_telp;
      }
    }
    ?>
    <strong class="kontak1">
      <a href="<?= $no_telp; ?>" target="_blank"><i class="fab fa-whatsapp"></i> <?= $user['no_telp']; ?>
      </a>
    </strong>
    <hr>
    <strong class="kontak2"><i class="fab fa-instagram"></i> <?= $user['instagram'] ? '<a href="' . $user['link_instagram'] . '" target="_blank">' . $user['instagram'] . '</a> ' : ''; ?></strong>
    <hr>
    <strong class="kontak3"><i class="fab fa-facebook"></i> <?= $user['facebook'] ? '<a href="' . $user['link_facebook'] . '" target="_blank">' . $user['facebook'] . '</a> ' : ''; ?></strong>
    <hr>
    <strong class="kontak4"><?= $user['lainnya'] ? '<a href="' . $user['link_lainnya'] . '" target="_blank">' . $user['lainnya'] . '</a> ' : ''; ?></strong>
  </div>
</div>