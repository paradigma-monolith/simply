<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<?php if ($targetBulanan) :
  $array = unserialize($targetBulanan['uraian']);
endif; ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="text-right">
      <button class="btn btn-primary btn-lg text-right my-3" data-toggle="modal" data-target="#relaxtimeTargetBulananTahunModal">Perbaharui data target bulanan</button>
    </div>
    <div class="card">
      <div class="card-header">
        <h3>Realisasi target per bulan tahun <select name="tahun" style="border: unset;" disabled>
            <?php for ($i = date('Y') + 1; $i >= 2023; $i--) : ?>
              <option value="<?= $i; ?>" <?= $i == $tahun ? 'selected' : ''; ?>><?= $i; ?></option>
            <?php endfor; ?>
          </select></h3>
        <strong class="float-right">Total pagu yang terekam saat ini : <?= number_format($pagu['jumlah'], '2', ',', '.'); ?></strong><br />
        <strong class="float-right">Total target yang terekam saat ini : <span class="<?= $pagu['jumlah'] != $jmlTarget ? 'text-danger' : 'text-success'; ?>"><?= number_format($jmlTarget, '2', ',', '.'); ?></span></strong>
      </div>
      <div class="card-body">
        <table class="table table-stripped table-hover table-bordered">
          <thead class="text-center">
            <tr class="table-info">
              <th rowspan="2" class="table-info align-middle">#Bulan</th>
              <th colspan="4" class="align-middle">Target Realisasi</th>
              <th colspan="5">Realisasi</th>
            </tr>
            <tr class="table-info">
              <td>51xxxx</td>
              <td>52xxxx</td>
              <td>53xxxx</td>
              <td>total</td>
              <td>51xxxx</td>
              <td>52xxxx</td>
              <td>53xxxx</td>
              <td>total</td>
              <th>(%)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            for ($i = 0; $i < 12; $i++) : ?>
              <tr>
                <td class="table-info"><?= $bulan[$i]; ?></td>
                <td>Rp<?= isset($array) ? number_format($array['bulan' . ($i + 1) . '51'], '2', ',', '.') : '0'; ?></td>
                <td>Rp<?= isset($array) ? number_format($array['bulan' . ($i + 1) . '52'], '2', ',', '.') : '0'; ?></td>
                <td>Rp<?= isset($array) ? number_format($array['bulan' . ($i + 1) . '53'], '2', ',', '.') : '0'; ?></td>
                <td class="text-right table-warning"><strong>Rp<?= $targetBulanan ? number_format($targetBulanan['bulan' . $i + 1], '2', ',', '.') : '0'; ?></strong></td>
                <?php $target = '';
                $persentaseTarget = 0;
                foreach ($realisasi as $data) : ?>
                  <?php if ($data['bulan'] == ($i + 1)) : ?>
                    <?php $target = "Rp " . ($targetBulanan ? number_format($data['realisasi'], '2', ',', '.') : '0'); ?>
                    <?php
                    $targetBulanSekarang = $targetBulanan ? ($targetBulanan['bulan' . $i + 1]) : 0;
                    if ($targetBulanSekarang != 0)
                      $persentaseTarget = ($data['realisasi'] / $targetBulanSekarang) * 100;
                    else
                      $persentaseTarget = 0;

                    ?>
                  <?php endif; ?>
                <?php endforeach; ?>
                <td><?= isset($realisasiUraian[$i + 1]) ? ($realisasiUraian[$i + 1]['bulan'] == $i + 1 ? (isset($realisasiUraian[$i + 1]['kode_uraian_51']) ? "Rp " . number_format($realisasiUraian[$i + 1]['kode_uraian_51'], '2', '.', ',') : 'Rp 0,00')  : 'Rp 0,00') : ''; ?></td>
                <td><?= isset($realisasiUraian[$i + 1]) ? ($realisasiUraian[$i + 1]['bulan'] == $i + 1 ? (isset($realisasiUraian[$i + 1]['kode_uraian_52']) ? "Rp " . number_format($realisasiUraian[$i + 1]['kode_uraian_52'], '2', '.', ',') : 'Rp 0,00')  : 'Rp 0,00') : ''; ?></td>
                <td><?= isset($realisasiUraian[$i + 1]) ? ($realisasiUraian[$i + 1]['bulan'] == $i + 1 ? (isset($realisasiUraian[$i + 1]['kode_uraian_53']) ? "Rp " . number_format($realisasiUraian[$i + 1]['kode_uraian_53'], '2', '.', ',') : 'Rp 0,00')  : 'Rp 0,00') : ''; ?></td>
                <td class="text-right <?= $targetBulanan ? ($target == $targetBulanan['bulan' . $i + 1] ? 'table-success' : ($target > $targetBulanan['bulan' . $i + 1] ? 'table-danger' : 'table-warning')) : '0'; ?>"><strong><?= $target; ?></strong></td>
                <td class="text-right <?= $batas['batas_nilai_bawah'] <= $persentaseTarget && $batas['batas_nilai_atas'] >= $persentaseTarget ? 'text-success' : 'text-danger'; ?>"><?= number_format($persentaseTarget, '2', '.', ','); ?>%</td>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
<?= $this->endSection(); ?>