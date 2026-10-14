<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-12">
              <h5>Perjalanan dinas terakhir yang terekam sistem pada tanggal <?= isset($perjalanan[0]['tgl_pulang']) ? mediumdate_indo($perjalanan[0]['tgl_pulang']) : '(Tidak ada)'; ?></h5>
              <h5>Jumlah pendapatan yang terekam sistem sebanyak <strong>Rp <?= number_format($totalPendapatan, '2', ',', '.'); ?></strong></h5>
              <hr>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table id="example11" class="table table-bordered table-striped table-hover" style="width: 100%;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Id</th>
                  <th>Tanggal Pelaksanaan</th>
                  <th>Nama Kegiatan</th>
                  <th>Tempat Perjalanan</th>
                  <th>Jumlah Orang</th>
                  <th>Nominal</th>
                  <th>Nominal Transfer</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1;
                foreach ($perjalanan as $data) : ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['id_perjalanan']; ?></td>
                    <?php if ($data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir']) : ?>
                      <td><?= $data['tgl_pelaksanaan']; ?></td>
                    <?php else : ?>
                      <td><?= $data['tgl_pelaksanaan']; ?> s/d <?= $data['tgl_pelaksanaan_akhir']; ?></td>
                    <?php endif; ?>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $data['nama_kegiatan']; ?>"><?= strlen($data['nama_kegiatan']) > 36 ? substr($data['nama_kegiatan'], 0, 36) . '...' : $data['nama_kegiatan']; ?></td>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $data['tempat_perjalanan']; ?>"><?= strlen($data['tempat_perjalanan']) > 36 ? substr($data['tempat_perjalanan'], 0, 36) . '...' : $data['tempat_perjalanan']; ?></td>
                    <td><?= $data['jumlah_peserta']; ?> Orang</td>
                    <td><?= number_format($data['total_perjalanan'], '2', ',', '.'); ?></td>
                    <td><?= number_format($data['jumlah_transfer'], '2', ',', '.'); ?></td>
                    <td>
                      <button class="btn btn-xs bg-gradient-info tombol-td" data-toggle="tooltip" data-placement="bottom" title="Pratinjau" for="Lihat Data"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-xs btn-danger" onclick="downloadFileSinadi('<?= $data['id_perjalanan']; ?>','<?= $data['nama']; ?>')" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="fas fa-file-pdf"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Id</th>
                  <th>Tanggal Pelaksanaan</th>
                  <th>Nama Kegiatan</th>
                  <th>Tempat Perjalanan</th>
                  <th>Jumlah Orang</th>
                  <th>Nominal</th>
                  <th>Nominal Transfer</th>
                  <th>Aksi</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
</section>
<?= $this->endSection(); ?>