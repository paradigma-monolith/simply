<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="card ">
      <div class="card-header">
        <a href="/relaxtime/pagu" class="btn btn-secondary float-right">Kembali</a>
      </div>
      <div class="card-body">
        <table class="table table-bordered stripped" id="tableWithoutExporting">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Uraian</th>
              <!-- <th>Vol</th> -->
              <!-- <th>Sat</th> -->
              <!-- <th>Harga</th> -->
              <!-- <th>Jumlah</th> -->
              <!-- <th>Tahun</th> -->
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = 1;
            foreach ($uraian as $data) : ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $data['digit_kro'] . "." . $data['kode_kro'] . "." . $data['kode_relaxtime_ro'] . "." . $data['kode_komponen'] . "." . $data['kode_sub_komponen'] . "." . $data['kode_uraian']; ?></td>
                <td><?= $data['deskripsi_uraian']; ?></td>
                <td class="align-middle"><button class="btn btn-sm btn-warning btn-realisasi-detail-anggaran-edit" data-id="<?= $data['id_relaxtime_uraian']; ?>" data-segment="uraian"><i class="fas fa-edit"></i></button></td>
              </tr>
            <?php
            endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>