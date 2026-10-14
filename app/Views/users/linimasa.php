<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">

    <!-- Timelime example  -->
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
</section>
<?= $this->endSection(); ?>