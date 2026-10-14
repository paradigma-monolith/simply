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
              <th>Vol</th>
              <th>Sat</th>
              <!-- <th>Harga</th> -->
              <!-- <th>Jumlah</th> -->
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = 1;
            foreach ($ro as $data) : ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $data['digit_kro'] . "." . $data['kode_kro'] . "." . $data['kode_relaxtime_ro']; ?></td>
                <td><?= $data['deskripsi_ro']; ?></td>
                <td><?= $data['vol_ro']; ?></td>
                <td><?= $data['sat_ro']; ?></td>
                <td><?= $data['tahun']; ?></td>
                <td class="align-middle">
                  <button class="btn btn-sm btn-warning btn-realisasi-detail-anggaran-edit" data-id="<?= $data['id_relaxtime_ro']; ?>" data-segment="ro"><i class="fas fa-edit"></i></button>
                  <form action="/relaxtime/deleteRO/<?= $data['id_relaxtime_ro']; ?>" method="post" class="d-inline formHapusData">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-lg bg-gradient-danger btn-sm"><i class="fas fa-file-alt"></i></button>
                  </form>
                </td>
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