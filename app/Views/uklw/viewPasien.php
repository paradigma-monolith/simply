<div class="container px-5">
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">
      <h3 class="profile-username text-center"><?= name_capitalize($getData['nama']); ?></h3>
      <p class="text-muted text-center"><?= $getData['identitas']; ?> : <?= $getData['nomor_identitas']; ?></p>
      <ul class="list-group list-group-unbordered mb-3 mx-5">
        <li class="list-group-item">
          <b>Jenis Kelamin</b> <a class="float-right"><?= $getData['jk']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Umur</b> <a class="float-right"><?= selisi_waktu($getData['tgl_lahir']) == "0 menit" ? "-" : selisi_waktu($getData['tgl_lahir']); ?></a>
        </li>
        <li class="list-group-item">
          <b>Tanggal Lahir</b> <a class="float-right"><?= $getData['tgl_lahir']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Alamat</b> <a class="float-right"><?= $getData['alamat']; ?></a>
        </li>
        <li class="list-group-item">
          <b>No. HP</b> <a class="float-right"><?= $getData['no_hp']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Pendidikan</b> <a class="float-right"><?= $getData['pendidikan']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Pekerjaan</b> <a class="float-right"><?= $getData['pekerjaan']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Status</b> <a class="float-right"><?= $getData['status']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Nama Keluarga</b> <a class="float-right"><?= $getData['nama_keluarga']; ?></a>
        </li>
        <li class="list-group-item">
          <b>Hubungan Keluarga</b> <a class="float-right"><?= $getData['hubungan_keluarga']; ?></a>
        </li>
        <li class="list-group-item">
          <b>No. HP Keluarga</b> <a class="float-right"><?= $getData['no_hp_keluarga']; ?></a>
        </li>
      </ul>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<div class="card-footer">
  <a href="/uklw/detailPasien/<?= $getData['id_uklw_pasien']; ?>" class="btn btn-primary float-right">
    <i class="fas fa-id-card-alt"></i> Selengkapnya
  </a>
</div>