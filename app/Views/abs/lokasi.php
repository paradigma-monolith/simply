<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<style>
  ul .custom-dropdown-menu {
    left: 100% !important;
  }
</style>
<section class="content">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#absSearchDataBarang" aria-controls="absSearchDataBarang" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="absSearchDataBarang">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item ">
              <a href="#" onclick="getDataBarangABS('all')" class="nav-link">Daftar semua barang <sup><span class="right badge badge-<?= $getTidakTersedia > 0 ? 'warning' : 'success'; ?>"><?= $getBarang['count']; ?></span></sup></a>
            </li>
            <li class="nav-item dropdown">
              <a href="#" data-label="header" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Status <sup><span class="right badge badge-danger"><?= $getTidakTersedia['count']; ?></span></sup></a>
              <ul aria-labelledby="absStatusDropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="#" onclick="getDataBarangABS('status_tersedia', 'Tersedia')" class="dropdown-item">Data barang diketahui <sup><span class="right badge badge-success"><?= $getTersedia['count']; ?></span></sup></a></li>
                <li><a href="#" onclick="getDataBarangABS('status_tersedia', 'Tidak Tersedia')" class="dropdown-item">Data barang tidak diketahui <sup><span class="right badge badge-danger"><?= $getTidakTersedia['count']; ?></span></sup></a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a href="#" data-label="header" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Lokasi</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a class="dropdown-item"><Small>Wilayah Kerja</Small></a></li>
                <li class="dropdown-divider"></li>
                <?php
                $fieldSebelumnya = 'xwyabcdzm';
                foreach ($getLokasiBarang as $data) : ?>
                  <?php if ($data['unit_kerja'] != $fieldSebelumnya) : $fieldSebelumnya = $data['unit_kerja']; ?>
                    <li class="dropdown-submenu dropdown-hover">
                      <?php foreach ($getLokasiCount as $count) :
                        if ($count['unit_kerja'] == $data['unit_kerja']) {
                          if ($count['unit_kerja'] == null) { ?>
                            <a href="#" onclick="getDataBarangABS('unit_kerja', null)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"> <span class="text-danger">Tidak diketahui</span><sup><span class="right badge badge-danger"> <?= $count['count']; ?> </span></sup></a>
                          <?php } else { ?>
                            <a href="#" onclick="getDataBarangABS('unit_kerja', '<?= $data['unit_kerja']; ?>')" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><?= $data['unit_kerja']; ?><sup><span class="right badge badge-success"> <?= $count['count']; ?> </span></sup></a>
                        <?php }
                        } ?>
                      <?php endforeach; ?>
                      <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu custom-dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item"><Small>Lantai</Small></a></li>
                        <li class="dropdown-divider"></li>
                        <?php
                        $lantaiSebelumnya = 'xwyabcdzm';
                        foreach ($getLokasiBarang as $data2) : ?>
                          <?php if ($data2['unit_kerja'] == $data['unit_kerja']) : ?>
                            <?php if ($lantaiSebelumnya != $data2['lantai']) : $lantaiSebelumnya = $data2['lantai'] ?>
                              <li class="dropdown-submenu">
                                <?php if ($data2['lantai'] == null) : ?>
                                  <a href="#" onclick="getDataBarangABS('unit_kerja', '<?= $data['unit_kerja']; ?>', 'lantai', null)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><span class="text-red">Tidak diketahui</span></a>
                                <?php else : ?>
                                  <a href="#" onclick="getDataBarangABS('unit_kerja', '<?= $data['unit_kerja']; ?>', 'lantai', '<?= $data2['lantai']; ?>')" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><span><?= $data2['lantai']; ?></span></a>
                                <?php endif; ?>
                                <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu custom-dropdown-menu border-0 shadow">
                                  <li><a class="dropdown-item"><Small>Ruangan</Small></a></li>
                                  <li class="dropdown-divider"></li>
                                  <?php
                                  $ruangSebelumnya = 'xwyabcdzm';
                                  foreach ($getLokasiBarang as $data3) : ?>
                                    <?php if ($data2['lantai'] == $data3['lantai'] && $data['unit_kerja'] == $data3['unit_kerja']) : ?>
                                      <?php if ($ruangSebelumnya != $data3['ruang']) : $ruangSebelumnya = $data3['ruang'] ?>
                                        <?php
                                        if ($data3['ruang'] == null) {
                                        ?>
                                          <li><a onclick="getDataBarangABS('unit_kerja', '<?= $data['unit_kerja']; ?>', 'lantai', '<?= $data2['lantai']; ?>', 'ruang', null)" href="#" class="dropdown-item"> <span class="text-red">Tidak diketahui</span> <sup><span class="right badge badge-danger"><?= $data3['count'] ?></span></sup></a></li>
                                        <?php
                                        } else {
                                        ?>
                                          <li><a onclick="getDataBarangABS('unit_kerja', '<?= $data['unit_kerja']; ?>', 'lantai', '<?= $data2['lantai']; ?>', 'ruang', '<?= $data3['ruang']; ?>')" href="#" class="dropdown-item"> <?= $data3['ruang'] ?><sup><span class="right badge badge-success"><?= $data3['count'] ?></span></sup></a></li>
                                        <?php
                                        }
                                        ?>
                                      <?php endif; ?>
                                    <?php endif; ?>
                                  <?php endforeach; ?>
                                </ul>
                              </li>
                            <?php endif; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </ul>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a href="#" data-label="header" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Kategori Kelompok</a>
              <ul aria-labelledby="absKelompokDropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <?php foreach ($getKategoriBarang as $data) : ?>
                  <?php if ($data['jenis'] == null) : ?>
                    <li><a onclick="getDataBarangABS('jenis', null)" href="#" class="dropdown-item"><span class="text-danger">Data tanpa kategori</span> <sup><span class="right badge badge-danger"><?= $data['count']; ?></span></sup></a></li>
                  <?php else : ?>
                    <li><a onclick="getDataBarangABS('jenis', '<?= $data['jenis']; ?>')" href="#" class="dropdown-item"><?= $data['jenis']; ?> <sup><span class="right badge badge-success"><?= $data['count']; ?></span></sup></a></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>

          <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item">
              <a href="/abs/upload-file" target="_blank" class="nav-link btn bg-gradient-<?= session()->get('role') == 'Admin' ? 'success' : 'secondary'; ?> text-white px-2 mr-1">
                <i class="fas fa-upload"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/lokasi" class="nav-link btn bg-gradient-primary text-white">
                <i class="fas fa-plus"></i> Tambah Data
              </a>
            </li>
          </ul>
        </div>

      </div>
    </nav>

    <div class="mt-1" id="contentABS">
      <div class="card">
        <div class="card-header">
          <h2><?= $duplikat ? 'Terdapat beberapa data duplikasi' : ''; ?></h2>
        </div>
        <div class="card-body">
          <div class="card">
            <div class="card-body">
              <table id="tableWithoutExporting" class="table table-sm table-stripped table-bordered table-hover">
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
                  $dataSementara = '';
                  $getColor = '';
                  $color = ['table-danger', 'table-success', 'table-dark', 'table-warning', 'table-info', 'table-secondary'];
                  $i = 0;
                  foreach ($duplikat as $key => $data) :
                    if ($dataSementara != $data['data_barang']) {
                      $getColor = $color[$i];
                      if ($i == 6) { ?>
                        <tr>
                          <td colspan="8">Masih terdapat beberapa data duplikat...</td>
                        </tr>
                    <?php break;
                      }
                    }
                    ?>
                    <tr class="<?= $getColor; ?>">
                      <input type="hidden" name="id_abs_bangunan[]" value="<?= $data['id_abs_bangunan']; ?>">
                      <td><?= $nomor++; ?></td>
                      <td>
                        <?php
                        $dataBarang = unserialize($data['data_barang']);
                        foreach ($dataBarang as $label => $barang) : ?>
                          <?= $label; ?> : <strong><?= $barang; ?></strong><br />
                        <?php endforeach; ?>
                      </td>
                      <td class="<?= $data['unit_kerja'] ? '' : 'bg-danger'; ?>">
                        <?= $data['unit_kerja'] ? $data['unit_kerja'] : 'Tidak diketahui'; ?>
                      </td>
                      <td class="<?= $data['lantai'] ? '' : 'bg-danger'; ?>">
                        <?= $data['lantai'] ? $data['lantai'] : 'Tidak diketahui'; ?>
                      </td>
                      <td class="<?= $data['ruang'] ? '' : 'bg-danger'; ?>">
                        <?= $data['ruang'] ? $data['ruang'] : 'Tidak diketahui'; ?>
                      </td>
                      <td class="<?= $data['jenis'] ? '' : 'bg-danger'; ?>">
                        <?= $data['jenis'] ? $data['jenis'] : 'Tidak diketahui'; ?>
                      </td>
                      <td>
                        <span class="<?= $data['status_tersedia'] == 'Tidak Tersedia' ? 'text-danger' : ''; ?>"> <?= $data['status_tersedia']; ?></span>
                      </td>
                      <td>
                        <div class="btn-group-vertical btn-group-sm">
                          <a href="#" data-action="view" class="btn link-action-abs-barang btn-info"><i class="fas fa-eye"></i></a>
                          <a href="#" data-action="image" class="btn link-action-abs-barang btn-secondary"><i class="fas fa-image"></i></a>
                          <a href="#" data-action="edit" class="btn link-action-abs-barang btn-warning"><i class="fas fa-edit"></i></a>
                          <a href="#" data-action="hapus" data-id="<?= $data['id_abs_bangunan']; ?>" class="btn link-action-abs-barang btn-danger"><i class="fas fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>