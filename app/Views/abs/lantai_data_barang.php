<div class="card">
  <form id="absUpdateLokasi" action="/abs/updateLokasi" method="post">
    <div class="card-footer text-right pt-3">
      <!--<button type="submit" class="btn btn-success perubahanDataABS" disabled><i class="fas fa-save"> Simpan Perubahan</i></button>-->
    </div>
    <div class="card-body">
      <table id="tableDataABS" class="table table-sm table-stripped table-bordered table-hover">
        <thead>
          <tr>
            <th style="width: 0%;">No</th>
            <th>Keterangan Data</th>
            <th style="width: 20%;">Wilayah Kerja</th>
            <th style="width: 10%;">Lantai</th>
            <th style="width: 10%;">Ruang</th>
            <th style="width: 10%;">Jenis</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 3%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $nomor = 1;
          foreach ($getData as $data) : ?>
            <tr>
              <input type="hidden" name="id_abs_bangunan[]" value="<?= $data['id_abs_bangunan']; ?>">
              <td><?= $nomor++; ?></td>
              <td>
                <?php
                $dataBarang = unserialize($data['data_barang']);
                $count = 1;
                foreach ($dataBarang as $label => $barang) : ?>
                  <?= $label; ?> : <strong><?= $barang; ?></strong><br />
                  <?php if($count++ == 10)break; ?>
                <?php endforeach; ?>
              </td>
              <td class="<?= $data['unit_kerja'] ? '' : 'table-danger'; ?>">
                <?= $data['unit_kerja'] ? $data['unit_kerja'] : 'Tidak diketahui'; ?>
                <!--<select name="unit_kerja[]" class="form-contro select2" style="width: 100%;">-->
                <!--  <option value="0" <?= $data['unit_kerja'] ? '' : 'selected'; ?>>--Tidak diketahui</option>-->
                <!--  <?php foreach ($unit_kerja as $wilker) : ?>-->
                <!--    <option value="<?= $wilker['id_unit_kerja']; ?>" <?= $data['unit_kerja'] == $wilker['unit_kerja'] ? 'selected' : ''; ?>><?= $wilker['unit_kerja']; ?></option>-->
                <!--  <?php endforeach; ?>-->
                <!--</select>-->
              </td>
              <td class="<?= $data['lantai'] ? '' : 'table-danger'; ?>">
                <?= $data['lantai'] ? $data['lantai'] : 'Tidak diketahui'; ?>
                <!--<select name="lantai[]" class="form-contro select2" style="width: 100%;">-->
                <!--  <option value="0" <?= $data['lantai'] ? '' : 'selected'; ?>>--Tidak diketahui</option>-->
                <!--  <?php foreach ($lantai as $wilker) : ?>-->
                <!--    <option value="<?= $wilker['id_abs_lantai']; ?>" <?= $data['lantai'] == $wilker['lantai'] ? 'selected' : ''; ?>><?= $wilker['lantai']; ?></option>-->
                <!--  <?php endforeach; ?>-->
                </select>
              </td>
              <td class="<?= $data['ruang'] ? '' : 'table-danger'; ?>">
                <?= $data['ruang'] ? $data['ruang'] : 'Tidak diketahui'; ?>
              </td>
              <td class="<?= $data['jenis'] ? '' : 'table-danger'; ?>">
                <?= $data['jenis'] ? $data['jenis'] : 'Tidak diketahui'; ?>
              </td>
              <td>
                <span class="<?= $data['status_tersedia'] == 'Tidak Tersedia' ? 'text-danger' : ''; ?>"> <?= $data['status_tersedia']; ?></span>
              </td>
              <td>
                <div class="btn-group-vertical btn-group-sm">
                  <a target="_blank" href="/data_abs/<?= $data['kode_qr_code_internal']; ?>" data-action="view" class="btn link-action-abs-barang btn-info"><i class="fas fa-eye"></i></a>
                  <!--<a href="#" data-action="image" data-idImage="<?= $data['id_abs_image']; ?>" class="btn link-action-abs-barang btn-secondary"><i class="fas fa-image"></i></a>-->
                  <a href="/abs/editDataBarang/<?= $data['id_abs_bangunan']; ?>" data-action="edit" class="btn link-action-abs-barang btn-warning"><i class="fas fa-edit"></i></a>
                  <a href="#" data-action="hapus" data-id="<?= $data['id_abs_bangunan']; ?>" class="btn link-action-abs-barang btn-danger"><i class="fas fa-trash"></i></a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </form>
</div>

<script>
  $(document).ready(function() {
    $(".select2").select2()
    $("#tableDataABS").dataTable()
  });
</script>