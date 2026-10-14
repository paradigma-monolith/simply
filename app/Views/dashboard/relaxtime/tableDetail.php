<div class="container bg-white">
  <table class="table table-sm table-hover table-bordered">
    <thead>
      <tr class="bg-primary">
        <th colspan="15">Uraian</th>
        <th>Pagu</th>
        <th>Realisasi</th>
        <th>%</th>
        <th>Sisa Anggaran</th>
      </tr>
      <tr class="table-primary">
        <th colspan="15">Jumlah Seluruhnya</th>
        <th class="keseluruhan keseluruhanPagu">...</th>
        <th class="keseluruhan keseluruhanRealisasi">...</th>
        <th class="keseluruhan keseluruhanPersentase">...</th>
        <th class="keseluruhan keseluruhanSisa">...</th>
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
        <?php if ($kodeProgram != $data['kode_rkt']) : ?>
          <tr class="relaxtimeTableDetail relaxtimeProgram">
            <td colspan="2"><?= $data['kode_rkt']; ?></td>
            <td colspan="13"><?= $data['deskripsi_rkt']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeProgram<?= $kodeProgramCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiProgram<?= $kodeProgramCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseProgram<?= $kodeProgramCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaProgram<?= $kodeProgramCount++; ?>"></td>
          </tr>
          <?php $kodeProgram = $data['kode_rkt']; ?>
        <?php endif; ?>
        <?php
        if ($kodeKegiatan != ($data['kode_rkt'] . $data['digit_kro'])) : ?>
          <tr class="relaxtimeTableDetail relaxtimeKegiatan">
            <td colspan="7"><?= ($data['kode_rkt'] . "." . $data['digit_kro']); ?></td>
            <td colspan="8"><?= $data['detail_digit_kro']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeKegiatan<?= $kodeKegiatanCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiKegiatan<?= $kodeKegiatanCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseKegiatan<?= $kodeKegiatanCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaKegiatan<?= $kodeKegiatanCount++; ?>"></td>
          </tr>
          <?php $kodeKegiatan = ($data['kode_rkt'] . $data['digit_kro']); ?>
        <?php endif; ?>
        <?php if ($kodeOutput != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'])) : ?>
          <tr class="relaxtimeTableDetail relaxtimeOutput">
            <td></td>
            <td colspan="4"><?= $data['kode_kro']; ?></td>
            <td colspan="10"><?= $data['deskripsi_kro']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeOutput<?= $kodeOutputCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiOutput<?= $kodeOutputCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseOutput<?= $kodeOutputCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaOutput<?= $kodeOutputCount++; ?>"></td>
          </tr>
          <?php $kodeOutput = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro']); ?>
        <?php endif; ?>
        <?php if ($kodeSubOutput != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'])) : ?>
          <tr class="relaxtimeTableDetail relaxtimeSubOutput">
            <td></td>
            <td colspan="4"><?= $data['kode_relaxtime_ro']; ?></td>
            <td colspan="10"><?= $data['deskripsi_ro']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeSubOutput<?= $kodeSubOutputCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiSubOutput<?= $kodeSubOutputCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseSubOutput<?= $kodeSubOutputCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaSubOutput<?= $kodeSubOutputCount++; ?>"></td>
          </tr>
          <?php $kodeSubOutput = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro']); ?>
        <?php endif; ?>
        <?php if ($kodeKomponen != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'])) : ?>
          <tr class="relaxtimeTableDetail relaxtimeKomponen">
            <?php for ($i = 1; $i <= 3; $i++) : ?>
              <td></td>
            <?php endfor; ?>
            <td colspan="5"><?= $data['kode_komponen']; ?></td>
            <td colspan="7"><?= $data['deskripsi_komponen']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeKomponen<?= $kodeKomponenCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiKomponen<?= $kodeKomponenCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseKomponen<?= $kodeKomponenCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaKomponen<?= $kodeKomponenCount++; ?>"></td>
          </tr>
          <?php $kodeKomponen = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen']); ?>
        <?php endif; ?>
        <?php if ($kodeSubKomponen != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen'])) : ?>
          <tr class="relaxtimeTableDetail relaxtimeSubKomponen">
            <?php for ($i = 1; $i <= 4; $i++) : ?>
              <td></td>
            <?php endfor; ?>
            <td colspan="6"><?= $data['kode_komponen']; ?>.<?= $data['kode_sub_komponen']; ?></td>
            <td colspan="5"><?= $data['deskripsi_sub_komponen']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeSubKomponen<?= $kodeSubKomponenCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiSubKomponen<?= $kodeSubKomponenCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseSubKomponen<?= $kodeSubKomponenCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaSubKomponen<?= $kodeSubKomponenCount++; ?>"></td>
          </tr>
          <?php $kodeSubKomponen = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen']); ?>
        <?php endif; ?>
        <?php if ($kodeUraian != ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen'] . $data['kode_uraian'])) : ?>
          <?php $kodeUraian = ($data['kode_rkt'] . $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] . $data['kode_komponen'] . $data['kode_sub_komponen'] . $data['kode_uraian']); ?>
          <tr class="relaxtimeTableDetail relaxtimeUraian">
            <?php for ($i = 1; $i <= 6; $i++) : ?>
              <td></td>
            <?php endfor; ?>
            <td colspan="5"><?= $data['kode_uraian']; ?></td>
            <td colspan="4"><?= $data['deskripsi_uraian']; ?></td>
            <td class="text-right keseluruhan keseluruhanKodeUraian<?= $kodeUraianCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanRealisasiUraian<?= $kodeUraianCount; ?>"></td>
            <td class="text-center keseluruhan keseluruhanPersentaseUraian<?= $kodeUraianCount; ?>"></td>
            <td class="text-right keseluruhan keseluruhanSisaUraian<?= $kodeUraianCount++; ?>"></td>
          </tr>
        <?php endif; ?>
        <tr class="relaxtimeTableDetail relaxtimeItem <?= (($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) <= $getSetting['batas_nilai_atas'] && (($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) >= $getSetting['batas_nilai_bawah'] ? "table-success" : ((($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100) > $getSetting['batas_nilai_atas'] ? "table-danger" : ""); ?>">
          <?php for ($i = 1; $i <= 12; $i++) : ?>
            <td></td>
          <?php endfor; ?>
          <td colspan="3"><?= $data['deskripsi_detail_uraian']; ?></td>
          <td class="text-right jumlahkanDetailUraian jmlKodeUraian<?= ($kodeUraianCount - 1); ?> jmlKodeSubKomponen<?= ($kodeSubKomponenCount - 1); ?> jmlKodeKomponen<?= ($kodeKomponenCount - 1); ?> jmlKodeSubOutput<?= ($kodeSubOutputCount - 1); ?> jmlKodeOutput<?= ($kodeOutputCount - 1); ?> jmlKodeKegiatan<?= ($kodeKegiatanCount - 1); ?> jmlKodeProgram<?= ($kodeProgramCount - 1); ?>"><span class="float-left rp"></span><?= number_format($data['jumlah_detail_uraian'], 2, ',', '.'); ?></td>
          <td class="text-right jumlahkanRealisasi jmlRealisasiUraian<?= ($kodeUraianCount - 1); ?> jmlRealisasiSubKomponen<?= ($kodeSubKomponenCount - 1); ?> jmlRealisasiKomponen<?= ($kodeKomponenCount - 1); ?> jmlRealisasiSubOutput<?= ($kodeSubOutputCount - 1); ?> jmlRealisasiOutput<?= ($kodeOutputCount - 1); ?> jmlRealisasiKegiatan<?= ($kodeKegiatanCount - 1); ?> jmlRealisasiProgram<?= ($kodeProgramCount - 1); ?>"><span class="float-left rp"></span><?= number_format($data['jmlRealisasi'], 2, ',', '.'); ?></td>
          <td class="text-center"><?= number_format(($data['jmlRealisasi'] / $data['jumlah_detail_uraian']) * 100, 2, ',', '.'); ?>%</td>
          <td class="text-right"><span class="float-left rp"></span><?= number_format(($data['jumlah_detail_uraian'] - $data['jmlRealisasi']), 2, ',', '.'); ?></td>
        </tr>

      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {

    $(".keseluruhan").html("<i class='fas fa-sm fa-sync-alt fa-spin'></i>")

    setTimeout(() => {
      menghitungRelaxtime()
    }, 2000);
  });

  function menghitungRelaxtime() {
    var elements = $('.jumlahkanDetailUraian');
    var total = 0;

    elements.each(function() {
      var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
      if (!isNaN(value)) {
        total += value;
      }
    });
    var keseluruhanPagu = total
    var formattedTotal = total.toLocaleString('id-ID', {
      style: 'currency',
      currency: 'IDR'
    });

    $(".keseluruhanPagu").text(formattedTotal);

    var elements = $('.jumlahkanRealisasi');
    var total = 0;

    elements.each(function() {
      var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
      if (!isNaN(value)) {
        total += value;
      }
    });
    var keseluruhanRealisasi = total
    var formattedTotal = total.toLocaleString('id-ID', {
      style: 'currency',
      currency: 'IDR'
    });

    $(".keseluruhanRealisasi").text(formattedTotal);


    var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
    $(".keseluruhanPersentase").text(formattedTotal.replace('.', ',') + "%")


    formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
      style: 'currency',
      currency: 'IDR'
    });
    $(".keseluruhanSisa").text(formattedTotal)

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
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeUraian" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiUraian' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiUraian" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseUraian" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaUraian" + i).text(formattedTotal)
    }

    const kodeSubKomponenCount = <?= $kodeSubKomponenCount; ?>;
    for (let i = 0; i < kodeSubKomponenCount; i++) {
      var elements = $('.jmlKodeSubKomponen' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeSubKomponen" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiSubKomponen' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiSubKomponen" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseSubKomponen" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaSubKomponen" + i).text(formattedTotal)
    }

    const kodeKomponenCount = <?= $kodeKomponenCount; ?>;
    for (let i = 0; i < kodeKomponenCount; i++) {
      var elements = $('.jmlKodeKomponen' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeKomponen" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiKomponen' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiKomponen" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseKomponen" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaKomponen" + i).text(formattedTotal)
    }

    const kodeSubOutputCount = <?= $kodeSubOutputCount; ?>;
    for (let i = 0; i < kodeSubOutputCount; i++) {
      var elements = $('.jmlKodeSubOutput' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeSubOutput" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiSubOutput' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiSubOutput" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseSubOutput" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaSubOutput" + i).text(formattedTotal)
    }

    const kodeOutputCount = <?= $kodeOutputCount; ?>;
    for (let i = 0; i < kodeOutputCount; i++) {
      var elements = $('.jmlKodeOutput' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeOutput" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiOutput' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiOutput" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseOutput" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaOutput" + i).text(formattedTotal)
    }

    const kodeKegiatanCount = <?= $kodeKegiatanCount; ?>;
    for (let i = 0; i < kodeKegiatanCount; i++) {
      var elements = $('.jmlKodeKegiatan' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeKegiatan" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiKegiatan' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiKegiatan" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseKegiatan" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaKegiatan" + i).text(formattedTotal)
    }

    const kodeProgramCount = <?= $kodeProgramCount; ?>;
    for (let i = 0; i < kodeProgramCount; i++) {
      var elements = $('.jmlKodeProgram' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanPagu = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanKodeProgram" + i).text(formattedTotal);

      var elements = $('.jmlRealisasiProgram' + i);
      var total = 0;

      elements.each(function() {
        var value = parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
        if (!isNaN(value)) {
          total += value;
        }
      });
      var keseluruhanRealisasi = total
      var formattedTotal = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });

      $(".keseluruhanRealisasiProgram" + i).text(formattedTotal);


      var formattedTotal = ((keseluruhanRealisasi / keseluruhanPagu) * 100).toFixed(2);
      $(".keseluruhanPersentaseProgram" + i).text(formattedTotal.replace('.', ',') + "%")

      formattedTotal = (keseluruhanPagu - keseluruhanRealisasi).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      $(".keseluruhanSisaProgram" + i).text(formattedTotal)
    }

    $(".rp").text('Rp')
  }
</script>