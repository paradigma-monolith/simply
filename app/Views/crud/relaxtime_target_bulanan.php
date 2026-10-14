<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<?php if ($targetBulanan) :
  $array = unserialize($targetBulanan['uraian']);
endif; ?>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3>Realisasi target per bulan tahun <?= $tahun; ?></h3>
        <strong class="float-right">Total pagu yang terekam saat ini : <?= number_format($pagu['jumlah'], '2', ',', '.'); ?></strong><br />
        <strong class="float-right">Total target yang terekam saat ini : <span class="<?= $pagu['jumlah'] != $jmlTarget ? 'text-danger' : 'text-success'; ?>"><?= number_format($jmlTarget, '2', ',', '.'); ?></span></strong>
      </div>
      <div class="card-body text-center">
        <form id="relaxtimeTargetBulanan" action="/relaxtime/target_bulanan_update" method="post">
          <input type="hidden" name="tahun" value="<?= $tahun; ?>">
          <div class="row">
            <div class="col-md-6">
              <?php for ($i = 0; $i < 6; $i++) : ?>
                <div class="form-group row">
                  <label class="col-md-2 text-right"><?= $bulan[$i]; ?></label>
                  <div class="col-md-10 text-left">
                    <div class="row">
                      <div class="col-12">
                        <input type="number" class="form-control" name="bulan<?= $i + 1; ?>" value="<?= $targetBulanan ? $targetBulanan['bulan' . $i + 1] : '0'; ?>" required>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>51" class="form-control" placeholder="51xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '51'] : '0'; ?>" required>
                          <span>51xxxx</span>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>52" class="form-control" placeholder="52xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '52'] : '0'; ?>" required>
                          <span>52xxxx</span>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>53" class="form-control" placeholder="53xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '53'] : '0'; ?>" required>
                          <span>53xxxx</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endfor; ?>
            </div>
            <div class="col-md-6">
              <?php for ($i = 6; $i < 12; $i++) : ?>
                <div class="form-group row">
                  <label class="col-md-2 text-right"><?= $bulan[$i]; ?></label>
                  <div class="col-md-10 text-left">
                    <div class="row">
                      <div class="col-12">
                        <input type="number" class="form-control" name="bulan<?= $i + 1; ?>" value="<?= $targetBulanan ? $targetBulanan['bulan' . $i + 1] : '0'; ?>" required>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>51" class="form-control" placeholder="51xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '51'] : '0'; ?>" required>
                          <span>51xxxx</span>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>52" class="form-control" placeholder="52xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '52'] : '0'; ?>" required>
                          <span>52xxxx</span>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="has-float-label">
                          <input type="number" name="bulan<?= $i + 1; ?>53" class="form-control" placeholder="53xxxx" value="<?= isset($array) ? $array['bulan' . ($i + 1) . '53'] : '0'; ?>" required>
                          <span>53xxxx</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endfor; ?>
            </div>
          </div>
          <button class="btn btn-success btn-block btn-lg">
            <i class="fas fa fa-save"></i> Simpan
          </button>
        </form>
      </div>
    </div>

  </div>
</section>
<?= $this->endSection(); ?>