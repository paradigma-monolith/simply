<div class="card-body table-responsive p-0" style="overflow-x: auto; height: 500px;">
  <table id="tabel_rencana_kerja" class="table table-bordered table-hover table-head-fixed" style="width: 100%;">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th style="min-width: 300px;">Kegiatan</th>
        <th style="min-width: 300px;">Indikator</th>
        <th style="min-width: 200px;">Bukti Fisik</th>
        <th style="min-width: 50px;">Target (Jumlah Kegiatan)</th>
        <th style="min-width: 50px;">Status Dokumen</th>
        <th style="min-width: 100px;">Aksi</th>
        <th style="min-width: 50px;">Keterangan</th>
        <th style="min-width: 70px;">Januari</th>
        <th style="min-width: 70px;">Februari</th>
        <th style="min-width: 70px;">Maret</th>
        <th style="min-width: 70px;">April</th>
        <th style="min-width: 70px;">Mei</th>
        <th style="min-width: 70px;">Juni</th>
        <th style="min-width: 70px;">Juli</th>
        <th style="min-width: 70px;">Agustus</th>
        <th style="min-width: 70px;">September</th>
        <th style="min-width: 70px;">Oktober</th>
        <th style="min-width: 70px;">November</th>
        <th style="min-width: 70px;">Desember</th>
      </tr>
    </thead>
    <tbody>
      <!-- POKJA | TAMPILKAN -->
      <?php foreach ($getPokja as $pokja) : ?>
        <tr>
          <td class="table-active" colspan="20"><?= $pokja['nama_pokja']; ?> (<?= $pokja['keterangan']; ?>)</td>
        </tr>
        <!-- RENCANA KERJA | TAMPILKAN -->
        <?php foreach ($getPoin as $poin) : ?>
          <?php if ($poin['id_wbk_pokja'] == $pokja['id_wbk_pokja']) : ?>
            <tr>
              <td><?= $poin['poin1']; ?></td>
              <td><?= $poin['kegiatan']; ?></td>
              <td><?= $poin['indikator']; ?></td>
              <td><?= $poin['bukti_fisik']; ?></td>
              <!-- RENCANA KERJA | TAMPILKAN TOTAL DOKUMEN YANG SUDAH UPLOAD DAN TOTAL TARGET DOKUMEN -->
              <td class="text-center">
                <?php if ($poin['target_jml_kegiatan'] == '') { ?>
                  -
                <?php } else { ?>
                  <?= $poin['jumlah_file']; ?>/<?= $poin['target_jml_kegiatan']; ?>
                <?php } ?>
              </td>
              <!-- RENCANA KERJA | TAMPILKAN STATUS DOKUMEN YANG SUDAH MEMENUHI TARGET -->
              <td>
                <?php if (isset($poin['target_jml_kegiatan'])) : ?>
                  <?php if ($poin['jumlah_file'] == $poin['target_jml_kegiatan']) : ?>
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="customCheckbox2" checked disabled>
                      <label for="customCheckbox2" class="custom-control-label"></label>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <!-- RENCANA KERJA | IF AKSES ADMIN -->
                <?php if ($getRole == 'Admin') : ?>
                <?php else : ?>
                  <?php if ($akses['keterangan'] == 'Koordinator' || $akses['keterangan'] == 'Anggota1') : ?>
                    <a href="/wbk/tambah_rencana_kerja/<?= $poin['id_wbk_rencana_kerja'] ?>" class="btn btn-xs bg-gradient-info" data-toggle="tooltip" data-placement="bottom" title="Tambah Unit Rencana Kerja" for="Tambah Unit Rencana Kerja"><i class="fa fa-plus"></i></a>
                  <?php endif; ?>
                  <!-- RENCANA KERJA | IF AKSES KOORDINATOR ATAU ANGGOTA1 -->
                <?php endif ?>
              </td>
              <td></td>
              <!-- RENCANA KERJA | TAMPILKAN JADWAL DAN TARGET WAKTU -->
              <?php
              $target_waktu = explode('|', $poin['target_waktu']);
              for ($bulan = 1; $bulan <= 12; $bulan++) {
                $warna = in_array($bulan, $target_waktu) ? 'lightblue' : '';
                echo '<td style="background-color:' . $warna . '; width: 100px;"></td>';
              }
              ?>
            </tr>
            <!-- UNIT RENCANA KERJA | TAMPILKAN -->
            <?php foreach ($getSubPoin as $subPoin) : ?>
              <?php if ($subPoin['id_wbk_pokja'] == $pokja['id_wbk_pokja']) : ?>
                <?php if ($subPoin['poin1'] == $poin['poin1']) : ?>
                  <tr>
                    <td><?= $subPoin['poin1']; ?>.<?= $subPoin['poin2']; ?></td>
                    <td><?= $subPoin['kegiatan']; ?></td>
                    <td><?= $subPoin['indikator']; ?></td>
                    <td><?= $subPoin['bukti_fisik']; ?></td>
                    <!-- UNIT RENCANA KERJA | TAMPILKAN TOTAL DOKUMEN YANG SUDAH UPLOAD DAN TOTAL TARGET DOKUMEN -->
                    <td class="text-center">
                      <?php if ($subPoin['target_jml_kegiatan'] == '') { ?>
                        -
                      <?php } else { ?>
                        <?= $subPoin['jumlah_file']; ?>/<?= $subPoin['target_jml_kegiatan']; ?>
                      <?php } ?>
                    </td>
                    <!-- UNIT RENCANA KERJA | TAMPILKAN STATUS DOKUMEN YANG SUDAH MEMENUHI TARGET -->
                    <td>
                      <?php if (isset($poin['target_jml_kegiatan'])) : ?>
                        <?php if ($subPoin['jumlah_file'] == $subPoin['target_jml_kegiatan']) : ?>
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="customCheckbox2" checked disabled>
                            <label for="customCheckbox2" class="custom-control-label"></label>
                          </div>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <!-- UNIT RENCANA KERJA | TAMPILKAN KOLOM AKSI -->
                    <td class="text-center">
                      <!-- UNIT RENCANA KERJA | IF PENGGUNA ADALAH ADMIN -->
                      <?php if ($getRole == 'Admin') : ?>
                        <div class="margin">
                          <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
                            <button type="button" class="btn btn-xs btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                              <?php foreach ($getArsip as $arsip) : ?>
                                <?php if ($arsip['id_wbk_rencana_kerja'] == $subPoin['id_wbk_rencana_kerja']) : ?>
                                  <?php $no = 1; ?>
                                  <a href="/wbk_arsip/<?= $arsip['file']; ?>" target="_blank" class="dropdown-item" href="#"><?= $no++; ?>. <?= $arsip['keterangan']; ?></a>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>
                      <?php else : ?>
                        <!-- UNIT RENCANA KERJA | IF PENGGUNA ADALAH KOORDINATOR/ANGGOTA ADMIN -->
                        <?php if ($akses['keterangan'] == 'Koordinator' || $akses['keterangan'] == 'Anggota1') { ?>
                          <!-- UNIT RENCANA KERJA | IF TARGET DOKUMEN SUDAH MEMENUHI -->
                          <?php if ($subPoin['jumlah_file'] == $subPoin['target_jml_kegiatan']) : ?>
                            <!-- UNIT RENCANA KERJA | TAMPILKAN DROPDOWN LIST BERISI DAFTAR DOKUMEN YANG TELAH DIUNGGAH -->
                            <div class="margin">
                              <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <?php foreach ($getArsip as $arsip) : ?>
                                    <?php if ($arsip['id_wbk_rencana_kerja'] == $subPoin['id_wbk_rencana_kerja']) : ?>
                                      <?php $no = 1; ?>
                                      <a href="/wbk_arsip/<?= $arsip['file']; ?>" target="_blank" class="dropdown-item" href="#"><?= $no++; ?>. <?= $arsip['keterangan']; ?></a>
                                    <?php endif; ?>
                                  <?php endforeach; ?>
                                </div>
                              </div>
                            </div>
                          <?php else : ?>
                            <!-- UNIT RENCANA KERJA | IF TARGET DOKUMEN BELUM TERPENUHI -->
                            <!-- UNIT RENCANA KERJA | TAMPILKAN TOMBOL UNGGAH BERKAS -->
                            <a href="/wbk/upload_file/<?= $subPoin['id_wbk_rencana_kerja'] ?>" class="btn btn-xs bg-gradient-success" data-toggle="tooltip" data-placement="bottom" title="Unggah Berkas" for="Unggah Berkas"><i class="fa fa-upload"></i></a>
                          <?php endif; ?>
                        <?php } else { ?>
                          <!-- UNIT RENCANA KERJA | PENGGUNA LAINNYA -->
                          <!-- UNIT RENCANA KERJA | TAMPILKAN DROPDOWN LIST BERISI DAFTAR DOKUMEN YANG TELAH DIUNGGAH -->
                          <div class="margin">
                            <div class="btn-group">
                              <button type="button" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
                              <button type="button" class="btn btn-xs btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                <?php $no = 1; ?>
                                <?php foreach ($getArsip as $arsip) : ?>
                                  <?php if ($arsip['id_wbk_rencana_kerja'] == $subPoin['id_wbk_rencana_kerja']) : ?>
                                    <a href="/wbk_arsip/<?= $arsip['file']; ?>" target="_blank" class="dropdown-item"><?= $no++; ?>. <?= $arsip['keterangan']; ?></a>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($subPoin['prioritas'] == 'true') : ?>
                        Prioritas
                      <?php endif; ?>
                    </td>
                    <!-- UNIT RENCANA KERJA | TAMPILKAN JADWAL DAN TARGET WAKTU -->
                    <?php
                    $target_waktu = explode('|', $subPoin['target_waktu']);
                    for ($bulan = 1; $bulan <= 12; $bulan++) {
                      $warna = in_array($bulan, $target_waktu) ? 'lightblue' : '';
                      echo '<td style="background-color:' . $warna . '; width: 100px;"></td>';
                    }
                    ?>
                  </tr>
                <?php endif; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>