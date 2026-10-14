<?php if ($label) : ?>
  <div class="card">
    <div class="card-footer">
      <a class="btn btn-outline-warning text-dark float-right" href="/abs/editDataBarang/<?= $label['id_abs_bangunan']; ?>"><i class="fas fa-edit"></i> Edit Data</a>
      <button class="btn btn-warning" id="abs_btn_verifikasi_lokasi" onclick="absMoveVerifikationLocation('<?= $label['id_abs_bangunan']; ?>')"><i class="fas fa-angle-double-right"></i> Pindahkan Sesuai Data Lokasi</button>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-stripped">
        <tr>
          <th>Wilayah Kerja</th>
          <td colspan="3"><?= $label['unit_kerja']; ?></td>
          <td rowspan="4" style="width: 200px;" class="align-middle">
            <img src="/dist/img/abs/<?= $label['image']; ?>" class="img-fluid d-block" width="200" alt="">
          </td>
        </tr>
        <tr>
          <th>Kategori Barang</th>
          <td colspan="3"><?= $label['jenis']; ?></td>
        </tr>
        <tr>
          <th>Lantai</th>
          <td class="<?= $label['lantai'] ? '' : 'table-danger'; ?>"><?= $label['lantai']; ?></td>
          <th>Ruang</th>
          <td class="<?= $label['ruang'] ? '' : 'table-danger'; ?>"><?= $label['ruang']; ?></td>
        </tr>
        <tr>
          <th>Verifikasi</th>
          <td colspan="3">
            <h4 id="abs_status_verifikasi_lokasi">
              <span class="badge badge-success">Valid</span>
            </h4>
          </td>
        </tr>
      </table>
      <hr>
      <table class="table table-bordered table-stripped">
        <?php
        $array = unserialize($label['data_barang']);
        ?>
        <?php foreach ($array as $label => $data) : ?>
          <tr>
            <th><?= $label; ?></th>
            <td><?= $data; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
<?php else : ?>
  Data tidak ada
<?php endif; ?>