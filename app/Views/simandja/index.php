<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
          <?= $activeMenu == 'simandjaRencana' ? '<a target="_blank" href="https://docs.google.com/spreadsheets/d/1JK6O18t34j5FEUgUaPj0LJGdEzt9XBs7/edit?gid=498152255#gid=498152255https://docs.google.com/spreadsheets/d/1JK6O18t34j5FEUgUaPj0LJGdEzt9XBs7/edit?gid=498152255#gid=498152255" class="btn btn-info btn-primary"><i class="fas fa-link"></i> Pengisian Erenggar Teknis 2025</a>' : ''; ?>
        <div class="float-right">
          <button class="btn btn-primary simandja-tombol-tambah" data-kategori="<?= $activeMenu; ?>"><i class="fas fa-plus"></i> Tambah Data</button>
        </div>
      </div>
      <div class="card-body">

        <table id="tableWithoutExporting" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Substansi</th>
              <th style="width: 15%;">Tanggal</th>
              <th>Deskripsi</th>
              <th>Akses</th>
              <th style="width: 10%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = 1;
            foreach ($getData as $data) :
              $cekTgl = explode("-", $data['tanggal']);
            ?>
              <tr class="<?= $cekTgl[2] > 5 ? 'table-danger' : ''; ?>">
                <td><?= $nomor++; ?></td>
                <td>
                    <?php if ($data['tim_kerja'] == '') : ?>
                    <?= $data['substansi']; ?>
                    <?php else : ?>
                    <?= $data['tim_kerja']; ?>
                    <?php endif; ?>
                </td>
                <td><?= mediumdate_indo($data['tanggal']); ?></td>
                <td><?= $data['deskripsi']; ?></td>
                <td><?= $data['nama']; ?></td>
                <td>
                  <a href="/file-simandja/<?= $data['file']; ?>" target="_blank" class="btn btn-info btn-sm"><i class=" fas fa-eye"></i></a>

                  <?php if ($data['username'] == session()->get('username')) : ?>
                    <button class="btn btn-warning btn-sm simandja-tombol-edit" data-kategori="<?= $activeMenu; ?>" data-timkerja="<?= $data['tim_kerja']; ?>" data-substansi="<?= $data['substansi']; ?>" data-tanggal="<?= $data['tanggal']; ?>" data-deskripsi="<?= $data['deskripsi']; ?>" data-file="<?= $data['file']; ?>" data-id="<?= $data['id_simandja']; ?>"><i class="fas fa-edit"></i></button>

                    <form action="/simandja/dlt_sinadi/<?= $data['id_simandja']; ?>" method="post" class="d-inline formHapusData">
                      <?= csrf_field(); ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button type="submit" class="btn btn-sm bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i></button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection(); ?>