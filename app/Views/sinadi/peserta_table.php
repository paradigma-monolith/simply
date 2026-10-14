<?php foreach ($peserta as $data) : ?>
  <div class="card m-0">
    <div class="card-body p-2">
      <div class="row">
        <div class="col-sm-8 pt-3 pb-2">
          <strong class="text-primary"><?= $data['nip_pegawai']; ?> </strong>
          <strong><?= $data['nama_pegawai']; ?></strong>
          (<?= $data['golongan']; ?>)
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-sm-4">
              <h6 class="mb-0">Perjalanan</h6>
            </div>
            <div class="col-sm-8 text-secondary">
              <?php if ($data['kota'] == $data['kota_asal']) : ?>
                <span>: </span>Dalam Kota <br /> &nbsp;&nbsp;
                <?= $data['kota']; ?> <small>(<?= $data['tgl_pergi'] == $data['tgl_pulang'] ? $data['tgl_pergi'] : $data['tgl_pergi'] . ' s/d ' . $data['tgl_pulang']; ?>)</small>
              <?php else : ?>
                <span>: </span>Luar Kota <br /> &nbsp;&nbsp;
                <?= 'dari ' . $data['kota_asal'] . ' ke ' . $data['kota']; ?> <small>(<?= $data['tgl_pergi'] == $data['tgl_pulang'] ? $data['tgl_pergi'] : $data['tgl_pergi'] . ' s/d ' . $data['tgl_pulang']; ?>)</small>
              <?php endif; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <h6 class="mb-0">No Surat Tugas </h6>
            </div>
            <div class="col-sm-8 text-secondary">
              <span>: </span><?= $data['no_surat_tugas']; ?> <br /> &nbsp;&nbsp;
              <small>(<?= $data['tgl_surat_tugas']; ?>)</small>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <h6 class="mb-0">No SPPD </h6>
            </div>
            <div class="col-sm-8 text-secondary">
              <span>: </span>
              <?php if ($data['no_sppd'] == '-') : ?>
                -
              <?php else : ?>
                <?= $data['no_sppd']; ?> <br /> &nbsp;&nbsp;
                <small>(<?= $data['tgl_sppd']; ?>)</small>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6" style="font-size: 80%;">
          <div class="row">
            <div class="col-sm-3">
              <h6 class="mb-0">Transport</h6>
            </div>
            <div class="col-sm-9 text-secondary mb-1">
              : <?= $data['transport_jumlah'] != 0 ? number_format($data['transport_nominal'], 2, ',', '.') . ' <small>(x' . $data['transport_hari'] . ')</small>  <br /> &nbsp;&nbsp;<strong> Rp ' . number_format($data['transport_jumlah'], 2, ',', '.') . '</strong>' : '-'; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <h6 class="mb-0">Uang Harian</h6>
            </div>
            <div class="col-sm-9 text-secondary mb-1">
              : <?= $data['harian_jumlah'] != 0 ? number_format($data['harian_nominal'], 2, ',', '.') . ' <small>(x' . $data['harian_hari'] . ')</small>  <br /> &nbsp;&nbsp;<strong> Rp ' . number_format($data['harian_jumlah'], 2, ',', '.') . '</strong>' : '-'; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <h6 class="mb-0">Penginapan</h6>
            </div>
            <div class="col-sm-9 text-secondary mb-1">
              : <?= $data['penginapan_jumlah'] != 0 ? number_format($data['penginapan_nominal'], 2, ',', '.') . ' <small>(x' . $data['penginapan_hari'] . ')</small>  <br /> &nbsp;&nbsp;<strong> Rp ' . number_format($data['penginapan_jumlah'], 2, ',', '.') . '</strong>' : '-'; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <h6 class="mb-0">Jumlah Transfer</h6>
            </div>
            <div class="col-sm-9 text-secondary mb-1">
              : <strong><?= 'Rp ' . number_format($data['jumlah_transfer'], 2, ',', '.'); ?></strong>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>