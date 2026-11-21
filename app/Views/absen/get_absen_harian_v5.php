<table id="tableWithoutExporting" class="table table-striped table-sm table-bordered">
  <thead>
    <tr>
      <th style="width: 2%;">No</th>
      <th>Nama</th>
      <th>Status Masuk</th>
      <th style="width: 18%;">Alamat Masuk</th>
      <th>Jam Masuk</th>
      <th style="width: 7%;">Foto Masuk</th>
      <th>Status Pulang</th>
      <th style="width: 18%;">Alamat Pulang</th>
      <th>Jam Pulang</th>
      <th style="width: 7%;">Foto Pulang</th>
      <th>Jam Kerja</th>
      <th>Rekam Absen</th>
      <th style="width: 10%;">Aksi</th>
    </tr>
  </thead>
  <tbody class="addDataAbsenHarian">
    <?php
    $nomor = 1;
    $canEditDel = !empty($isAdmin) || !empty($aksesEditDel);
    foreach ($absen as $data) :
      $jamMasuk = $data['jam_masuk'];
      $jamPulang = $data['jam_pulang'];

      $statusAbsensi = statusAbsensi(
        $jamMasuk,
        $jamPulang,
        $data['aturan_jam_masuk'] ?? null,
        $data['aturan_jam_pulang'] ?? null,
        $data['toleransi_keterlambatan'] ?? null
      );

      if ($data['status_pulang'] == null) {
        $statusAbsen = '';
      } elseif ($data['status_pulang'] != $data['status_masuk']) {
        $statusAbsen = 'style="background-color: #f5c6cb !important;"';
      } else {
        $statusAbsen = '';
      }

      $evalMasuk = $data['eval_masuk'] ?? ['cell_class' => 'table-danger', 'html' => '', 'show_verifikasi' => false];
      $evalPulang = $data['eval_pulang'] ?? ['cell_class' => 'table-danger', 'html' => '', 'show_verifikasi' => false];

      $jamMasukClass = 'table-danger';
      if (!empty($jamMasuk)) {
        if ($statusAbsensi['masuk'] === 'kuning') $jamMasukClass = 'table-warning';
        elseif ($statusAbsensi['masuk'] === 'hijau') $jamMasukClass = 'table-success';
      }

      $jamPulangClass = 'table-danger';
      if (!empty($jamPulang)) {
        if ($statusAbsensi['pulang'] === 'kuning') $jamPulangClass = 'table-warning';
        elseif ($statusAbsensi['pulang'] === 'hijau') $jamPulangClass = 'table-success';
      }

      $idAbsen = (int) ($data['id_absen'] ?? 0);
      $ver = !empty($data['updated_at']) ? (int) strtotime((string) $data['updated_at']) : 0;
      $verQ = $ver > 0 ? '?v=' . $ver : '';
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= esc($data['nama']); ?></td>
        <td><?= esc($data['status_masuk'] ?? ''); ?></td>
        <td <?= $statusAbsen; ?>
            class="class-absen-alamat-masuk <?= $evalMasuk['cell_class']; ?>"
            data-alamat="<?= esc($data['alamat_masuk'] ?? '', 'attr'); ?>"
            data-longitude="<?= esc($data['longitude_masuk'] ?? '', 'attr'); ?>"
            data-latitude="<?= esc($data['latitude_masuk'] ?? '', 'attr'); ?>">
          <?= $evalMasuk['html']; ?>
        </td>
        <td <?= $statusAbsen; ?> class="<?= $jamMasukClass; ?>">
          <?= esc($jamMasuk ?? ''); ?>
        </td>
        <td <?= $statusAbsen; ?> class="text-center">
          <?php if (!empty($jamMasuk)) : ?>
            <?php if (empty($data['keterangan'])) : ?>
              <?php if ($idAbsen > 0) : ?>
                <img src="/dist/img/boxed-bg.jpg"
                     data-foto-url="/absen/foto/<?= $idAbsen; ?>/masuk<?= $verQ; ?>"
                     data-idabsen="<?= $idAbsen; ?>"
                     data-ket="masuk"
                     decoding="async"
                     class="galleryAction absen-thumb absen-foto-deferred"
                     alt="Foto Masuk"
                     onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" />
              <?php endif; ?>
            <?php else : ?>
              <h4><?= esc($data['keterangan']); ?></h4>
            <?php endif; ?>
          <?php endif; ?>
        </td>
        <td <?= $statusAbsen; ?>><?= esc($data['status_pulang'] ?? ''); ?></td>
        <td <?= $statusAbsen; ?>
            class="class-absen-alamat-pulang <?= $evalPulang['cell_class']; ?>"
            data-alamat="<?= esc($data['alamat_pulang'] ?? '', 'attr'); ?>"
            data-longitude="<?= esc($data['longitude_pulang'] ?? '', 'attr'); ?>"
            data-latitude="<?= esc($data['latitude_pulang'] ?? '', 'attr'); ?>">
          <?= $evalPulang['html']; ?>
        </td>
        <td <?= $statusAbsen; ?> class="<?= $jamPulangClass; ?>">
          <?= esc($jamPulang ?? ''); ?>
        </td>
        <td <?= $statusAbsen; ?> class="text-center">
          <?php if (!empty($jamPulang)) : ?>
            <?php if (empty($data['keterangan'])) : ?>
              <?php if ($idAbsen > 0) : ?>
                <img src="/dist/img/boxed-bg.jpg"
                     data-foto-url="/absen/foto/<?= $idAbsen; ?>/pulang<?= $verQ; ?>"
                     data-idabsen="<?= $idAbsen; ?>"
                     data-ket="pulang"
                     decoding="async"
                     class="galleryAction absen-thumb absen-foto-deferred"
                     alt="Foto Pulang"
                     onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" />
              <?php endif; ?>
            <?php else : ?>
              <h4><?= esc($data['keterangan']); ?></h4>
            <?php endif; ?>
          <?php endif; ?>
        </td>
        <td <?= $statusAbsen; ?>>
          <?= esc($data['jam_kerja_text'] ?? '-'); ?>
        </td>
        <td>
          <?php if ($idAbsen > 0 && (int) ($data['jumlah_array'] ?? 0) > 0) : ?>
            <button type="button" class="btn btn-sm btn-info showRekamAbsen" data-id="<?= $idAbsen; ?>"> Lihat </button><br />
            <span><?= (int) $data['jumlah_array']; ?> rekaman tersedia!</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($canEditDel) : ?>
            <div class="btn-group mb-2" role="group">
              <button type="button" data-id="<?= $idAbsen; ?>" data-idpegawai="<?= (int) $data['dp_id_data_pegawai']; ?>" data-nama="<?= esc($data['nama'], 'attr'); ?>" class="btn btn-sm btn-warning btn-edit-absen"><i class="fas fa-edit"></i> Edit</button>
              <?php if ($idAbsen > 0) : ?>
                <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-delete-absen"><i class="fas fa-trash"></i> Delete</button>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if ($idAbsen > 0 && !empty($evalMasuk['show_verifikasi'])) : ?>
            <h6 class="m-0 btn-verifikasi-masuk">Verifikasi Masuk</h6>
            <div class="btn-group btn-verifikasi-masuk" role="group" aria-label="Verifikasi Masuk">
              <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-success btn-absen-validasi-masuk"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-masuk"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
          <?php endif; ?>
          <?php if ($idAbsen > 0 && !empty($evalPulang['show_verifikasi'])) : ?>
            <h6 class="m-0 mt-1 btn-verifikasi-pulang">Verifikasi Pulang</h6>
            <div class="btn-group btn-verifikasi-pulang" role="group" aria-label="Verifikasi Pulang">
              <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-success btn-absen-validasi-pulang"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-pulang"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
