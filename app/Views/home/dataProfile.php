<div class="card card-primary card-outline">
  <div class="card-body box-profile">
    <div class="text-center" style="position: relative;">
      <?php if (session()->get('username') == $user['username']) : ?>
        <img class="profile-picture profile-user-img img-fluid img-circle" src="/dist/img/avatar/<?= session()->get('picture'); ?>" alt="User profile picture">
        <button class="btn btn-warning p-0 pl-1 pr-1" data-toggle="modal" data-target="#modal-profil-saya-foto-profil" style="position: absolute; bottom: 0; border-radius: 50%;">
          <i class="fas fa-edit"></i>
        </button>
      <?php else : ?>
        <?php if ($user['image'] != '') : ?>
          <img class="profile-picture profile-user-img img-fluid img-circle" src="/dist/img/avatar/<?= $user['image']; ?>" alt="User profile picture">
        <?php else : ?>
          <img class="profile-picture profile-user-img img-fluid img-circle" src="/dist/img/boxed-bg.jpg" alt="User profile picture">
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <h3 class="profile-username text-center"><?= name_capitalize($user['nama']); ?></h3>
    <p class="text-muted text-center"><?= $user['jabatan']; ?></p>
    <ul class="list-group list-group-unbordered mb-3">
      <li class="list-group-item">
        <b>Golongan</b> <a class="float-right"><?= $user['golongan']; ?></a>
      </li>
      <li class="list-group-item">
        <b>Masa Kerja</b> <a class="float-right"><?= selisi_waktu($user['tgl_awal_masa']) == "0 menit" ? "-" : selisi_waktu($user['tgl_awal_masa']); ?></a>
      </li>
      <li class="list-group-item">
        <b>Substansi</b> <a class="float-right"><?= $user['nama_substansi']; ?></a>
      </li>
      <li class="list-group-item">
        <b>Tim Kerja</b> <a class="float-right"><?= $user['id_tim_kerja'] && $user['id_tim_kerja'] == "0" ? "-" : $user['nama_tim_kerja']; ?></a>
      </li>
    </ul>

  </div>
  <!-- /.card-body -->
</div>