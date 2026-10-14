<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->

        <?= $this->include("/home/dataProfile"); ?>

        <!-- /.card -->

        <!-- About Me Box -->
        <?= $this->include('/home/dataKontak'); ?>
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Notifikasi</a></li>
              <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Linimasa</a></li>
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Pengaturan</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane" id="activity">
                <!-- Post -->
                <?php foreach ($notifikasi as $data) : ?>
                  <div class="post">
                    <div class="user-block">
                      <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
                      <img class="img-circle img-bordered-sm" src="/dist/img/logo_kkp.png" alt="user image">
                      <span class="username">
                        <a href="#"><?= name_capitalize($data['nama_pm']); ?></a>
                      </span>
                      <span class="description">Dibagikan - <?= selisi_waktu($data['created_at']); ?> yang lalu</span>
                    </div>
                    <!-- /.user-block -->
                    <p>
                      <?= $data['pesan']; ?>
                    </p>
                    <p>
                      <?php if ($data["status"] == "Belum Dibaca") : ?>
                        <span class="float-right">
                          <form class="form-notifikasi-dibaca float-right" method="post" action="/home/ajax/read-notif/<?= $data['id_notifikasi']; ?>">
                            <button type="submit" class="btn link-black text-sm ">
                              Tandai sudah dibaca </button>
                          </form>
                        </span>
                      <?php endif; ?>
                    </p>
                    <p class="p-0 m-0">&nbsp;</p>
                  </div>
                <?php endforeach; ?>
                <!-- /.post -->

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <div class="row">
                  <div class="col-md-12">
                    <!-- The time line -->
                    <div class="timeline">
                      <!-- timeline time label -->
                      <?php $tanggal = '';
                      foreach ($linimasa as $data) : ?>
                        <?php if ($tanggal != $data['tanggal']) : $tanggal = $data['tanggal']; ?>
                          <div class="time-label">
                            <span class="bg-info"><?= $data['tanggal']; ?></span>
                          </div>
                        <?php endif; ?>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <div>
                          <i class="<?= $data['icon']; ?>"></i>
                          <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> <?= $data['jam']; ?></span>
                            <h3 class="timeline-header"><a href="#"><?= $data['nama']; ?></a> <?= $data['aktivitas']; ?></h3>

                            <?php if ($data['keterangan'] != '') : ?>
                              <div class="timeline-body">
                                <?= $data['keterangan']; ?>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                      <!-- END timeline item -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->

              <div class="active tab-pane" id="settings">
                <div class="row">
                  <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                      <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Profile Pegawai</a>
                      <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Username</a>
                      <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Password</a>
                      <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Kontak</a>
                    </div>
                  </div>
                  <div class="col-7 col-sm-9">
                    <div class="tab-content" id="vert-tabs-tabContent">
                      <!-- show active <======= nama_class -->
                      <div class="tab-pane fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                        <?php
                        $blockNip = [
                          '000000000000000001',
                          '000000000000000001', '000000000000000002', '000000000000000003', '000000000000000004',
                          '000000000000000005', '000000000000000006', '000000000000000007', '000000000000000008',
                          '000000000000000009', '000000000000000010', '000000000000000011', '000000000000000012',
                          '000000000000000013', '000000000000000014', '000000000000000015', '000000000000000016'
                        ];
                        if ($profile) {
                          if (in_array($profile['nip'], $blockNip)) {
                            echo "Akun anda dibatasi. Tidak terdapat settingan untuk akun profile ini!";
                          } else {
                            echo $this->include('/home/formulirProfilePegawai');
                          }
                        } else {
                          echo 'Tidak terdapat settingan untuk akun profile pegawai anda';
                        }
                        ?>
                      </div>
                      <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                        <?= $this->include('/home/formulirUser'); ?>
                      </div>
                      <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                        <?= $this->include('/home/formulirPassword'); ?>
                      </div>
                      <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                        <?= $this->include('/home/formulirKontak'); ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<?= $this->endSection(); ?>