<div class="row">
  <div class="col"></div>
  <div class="col-9">
    <div class="row">
      <div class="col-8">
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <strong>Target Bulanan</strong> <a class="float-right"><?= number_format($getDataTargetBulanan['bulan' . $nomorBulan], 2, ".", ","); ?></a>
          </li>
          <li class="list-group-item">
            <strong>Total Realisasi</strong> <a class="float-right"><?= number_format($alreadyRealisasi['jmlRealisasi'], 2, ".", ","); ?></a>
          </li>
        </ul>
      </div>
      <div class="col-4">
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item text-center" style="padding: 1.50rem 0;">
            <h3><strong> <?= number_format(($alreadyRealisasi['jmlRealisasi'] / $getDataTargetBulanan['bulan' . $nomorBulan]) * 100, 2, ".", ","); ?> </strong><sup style="font-size: 20px">%</sup></h3></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col"></div>
</div>
<div class="container bg-white" style="max-height: 105vh; overflow: auto;">
  <table class="table table-sm table-hover table-bordered">
    <thead>
      <tr class="bg-primary">
        <th colspan="9">Uraian</th>
        <th>Pagu</th>
        <th>Realisasi</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $kodeProgram = '';
      $kodeProgramCount = 0;
      $kodeKegiatan = '';
      $kodeKegiatanCount = 0;
      $kodeOutput = '';
      $kodeOutputCount = 0;
      $kodeSubOutput = '';
      $kodeSubOutputCount = 0;
      $kodeKomponen = '';
      $kodeKomponenCount = 0;
      $kodeSubKomponen = '';
      $kodeSubKomponenCount = 0;
      $kodeUraian = '';
      $kodeUraianCount = 0;
      foreach ($getData as $data) : ?>
        <?php if ($kodeUraian != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen'] . $data['kode_uraian'])) : ?>
          <?php $kodeUraian = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen'] . $data['kode_uraian']); ?>
          <tr class="relaxtimeRealisasiTableDetail relaxtimeRealisasiUraian">
            <td colspan="5"><?= $data['kode_uraian']; ?></td>
            <td colspan="4"><?= $data['deskripsi_uraian']; ?></td>
            <td class="text-right keseluruhanRealisasiQ keseluruhanRealisasiQKodeUraian<?= $kodeUraianCount; ?>"></td>
            <td class="text-right keseluruhanRealisasiQ keseluruhanRealisasiQRealisasiUraian<?= $kodeUraianCount++; ?>"></td>
          </tr>
        <?php endif; ?>
        <tr class="relaxtimeRealisasiTableDetail relaxtimeRealisasiUraian relaxtimeItem <?= (($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) <= $getSetting['batas_nilai_atas'] && (($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) >= $getSetting['batas_nilai_bawah'] ? "table-success" : ((($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) > $getSetting['batas_nilai_atas'] ? "table-danger" : ""); ?>">
          <?php for ($i = 1; $i <= 6; $i++) : ?>
            <td></td>
          <?php endfor; ?>
          <td colspan="3"><?= $data['deskripsi_detail_uraian']; ?></td>
          <td class="text-right jumlahkanDetailUraian jmlKodeUraian<?= ($kodeUraianCount - 1); ?> jmlKodeSubKomponen<?= ($kodeSubKomponenCount - 1); ?> jmlKodeKomponen<?= ($kodeKomponenCount - 1); ?> jmlKodeSubOutput<?= ($kodeSubOutputCount - 1); ?> jmlKodeOutput<?= ($kodeOutputCount - 1); ?> jmlKodeKegiatan<?= ($kodeKegiatanCount - 1); ?> jmlKodeProgram<?= ($kodeProgramCount - 1); ?>"><span class="float-left rp"></span><?= number_format($data['jumlah_detail_uraian'], 2, ',', '.'); ?></td>
          <td class="text-right jumlahkanRealisasi jmlRealisasiUraian<?= ($kodeUraianCount - 1); ?> jmlRealisasiSubKomponen<?= ($kodeSubKomponenCount - 1); ?> jmlRealisasiKomponen<?= ($kodeKomponenCount - 1); ?> jmlRealisasiSubOutput<?= ($kodeSubOutputCount - 1); ?> jmlRealisasiOutput<?= ($kodeOutputCount - 1); ?> jmlRealisasiKegiatan<?= ($kodeKegiatanCount - 1); ?> jmlRealisasiProgram<?= ($kodeProgramCount - 1); ?> <?= $data['jmlRealisasi'] > $data['jumlah_detail_uraian'] ? 'table-danger' : ''; ?> <?= $data['jmlRealisasi'] == $data['jumlah_detail_uraian'] ? 'table-success' : ''; ?>"><span class="float-left rp"></span><?= number_format($data['jmlRealisasi'], 2, ',', '.'); ?></td>
        </tr>

      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {

    $(".relaxtimeRealisasiTableDetail").hide();
    $(".relaxtimeRealisasiUraian").show();

    $(".keseluruhanRealisasiQ").html("<i class='fas fa-sm fa-sync-alt fa-spin'></i>")

    setTimeout(() => {
      menghitungRelaxtime()
    }, 2000);
  });

  function menghitungRelaxtime() {

    const kodeUraianCount = <?= $kodeUraianCount; ?>;
    for (let i = 0; i < kodeUraianCount; i++) {
      var elements = $('.jmlKodeUraian' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasiQPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiQKodeUraian" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiUraian' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasiQRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiQRealisasiUraian" + i).text(formattedTotal);
    }

    $(".rp").text('Rp')
  }
</script>