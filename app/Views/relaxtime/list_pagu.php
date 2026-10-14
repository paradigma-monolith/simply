<div class="card card-info card-outline">
  <div class="card-body">
    <div class="float-right"><strong>Pagu tahun <select name="pagu-list-relaxtime" style="border: none;">
          <?php for ($i = date("Y") + 1; $i > 2020; $i--) { ?>
            <option value="<?= $i; ?>" <?= $i == $tahun ? 'selected' : ""; ?>><?= $i; ?></option>
          <?php } ?>
        </select>
        <h6>Rp<?= number_format($totalPagu['totalPagu'], 0, ',', '.'); ?>,-</h6>
      </strong></div>
    <div class="overlay-wrapper">
      <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pt-2">Loading...</div>
      </div>
      <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($rkt as $data): ?>
          <li class="nav-Komponen">
            <span class="nav-link" data-toggle="pill"
              href="#rkt-<?= preg_replace('/[^A-Za-z0-9\-]/', '', $data['kode_rkt']); ?>" role="tab"
              aria-controls="rkt-<?= $data['kode_rkt']; ?>" aria-selected="false"><?= $data['kode_rkt']; ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="tab-content">
        <?php foreach ($rkt as $data): ?>
          <div class="tab-pane fade" id="rkt-<?= preg_replace('/[^A-Za-z0-9\-]/', '', $data['kode_rkt']); ?>"
            role="tabpanel">
            <div class="tab-custom-content" style="border-top: unset;">
              <p class="lead mb-0"><?= $data['deskripsi_rkt']; ?></p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-3 col-sm-2">
                  <div class="nav flex-column nav-tabs h-100" role="tablist" aria-orientation="vertical">
                    <?php foreach ($kro as $dataKRO): ?>
                      <?php if ($data["kode_rkt"] == $dataKRO["kode_rkt"]): ?>
                        <span class="nav-link" data-toggle="pill"
                          href="#kro-<?= preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["kode_kro"]) . preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["digit_kro"]); ?>"
                          role="tab"
                          aria-controls="kro-<?= preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["kode_kro"]) . preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["digit_kro"]); ?>"
                          aria-selected="false"><?= $dataKRO['digit_kro']; ?>.<?= $dataKRO['kode_kro']; ?></span>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="col-9 col-sm-10">
                  <div class="tab-content">
                    <?php foreach ($kro as $dataKRO): ?>
                      <?php if ($data["kode_rkt"] == $dataKRO["kode_rkt"]): ?>
                        <div class="tab-pane fade"
                          id="kro-<?= preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["kode_kro"]) . preg_replace('/[^A-Za-z0-9\-]/', '', $dataKRO["digit_kro"]); ?>"
                          role="tabpanel">
                          <table class="table table-small table-stripped table-bordered table-sm">
                            <thead>
                              <tr class="text-center">
                                <th>KODE</th>
                                <th>URAIAN</th>
                                <th>VOL</th>
                                <th>SAT</th>
                                <th>HARGA</th>
                                <th>JUMLAH</th>

                                <th class="relaxtime-colom-aksi">Aksi</th>

                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $id_relaxtime_ro = 0;
                              $kode_komponen = "";
                              foreach ($komponen as $dataKomponen): ?>
                                <?php if ($id_relaxtime_ro != $dataKomponen['id_relaxtime_ro']):
                                  if (($dataKomponen['digit_kro'] . $dataKomponen['kode_kro']) == ($dataKRO['digit_kro'] . $dataKRO['kode_kro'])):
                                    $id_relaxtime_ro = $dataKomponen['id_relaxtime_ro'];
                                    $kode_komponen = "";
                                    ?>
                                    <tr class="table-primary">
                                      <td>
                                        <?= $dataKomponen['digit_kro']; ?>.<?= $dataKomponen['kode_kro']; ?>.<?= $dataKomponen['kode_relaxtime_ro']; ?>
                                      </td>
                                      <td><?= $dataKomponen["deskripsi_ro"]; ?></td>
                                      <td><?= $dataKomponen["vol_ro"]; ?></td>
                                      <td><?= $dataKomponen["sat_ro"]; ?></td>
                                      <td>&nbsp;</td>
                                      <td><?= number_format($dataKomponen['total_jumlah_komponen'], 0, ',', '.'); ?></td>

                                      <td class="relaxtime-colom-aksi">&nbsp;</td>

                                    </tr>
                                    <?php
                                  endif;
                                else:
                                  // Nothing
                                endif;
                                if (($dataKomponen['digit_kro'] . $dataKomponen['kode_kro']) == ($dataKRO['digit_kro'] . $dataKRO['kode_kro'])): ?>
                                  <?php if ($kode_komponen != $dataKomponen['kode_komponen']):
                                    $kode_komponen = $dataKomponen['kode_komponen']; ?>
                                    <tr class="table-secondary">
                                      <td class="text-center"><?= $dataKomponen['kode_komponen']; ?></td>
                                      <td><?= $dataKomponen['deskripsi_komponen']; ?></td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td><?= number_format($dataKomponen['total_jumlah'], 0, ',', '.'); ?></td>

                                      <td class="relaxtime-colom-aksi">&nbsp;</td>

                                    </tr>
                                  <?php endif; ?>
                                  <tr class="table-warning">
                                    <td class="text-right"><?= $dataKomponen['kode_sub_komponen']; ?></td>
                                    <td><?= $dataKomponen['deskripsi_sub_komponen']; ?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><?= number_format($dataKomponen['jumlah'], 0, ',', '.'); ?>,-</td>

                                    <td class="relaxtime-colom-aksi">&nbsp;</td>

                                  </tr>
                                  <?php
                                  $kode_uraian = "";
                                  foreach ($uraian as $hapusUraian => $dataUraian): ?>
                                    <?php if (
                                      $dataUraian['digit_kro'] == $dataKomponen["digit_kro"] &&
                                      $dataUraian['kode_kro'] == $dataKomponen["kode_kro"] &&
                                      $dataUraian['kode_relaxtime_ro'] == $dataKomponen["kode_relaxtime_ro"] &&
                                      $dataUraian['kode_komponen'] == $dataKomponen["kode_komponen"] &&
                                      $dataUraian['kode_sub_komponen'] == $dataKomponen["kode_sub_komponen"]
                                    ):
                                      unset($uraian[$hapusUraian]); ?>
                                      <tr>
                                        <td class="text-center"><?= $dataUraian['kode_uraian']; ?></td>
                                        <td><?= $dataUraian['deskripsi_uraian']; ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?= number_format($dataUraian['jumlah_uraian'], 0, ',', '.'); ?>,-</td>

                                        <td class="relaxtime-colom-aksi">&nbsp;</td>

                                      </tr>
                                      <?php foreach ($detail_uraian as $hapusDetailUraian => $dataDetailUraian): ?>
                                        <?php if (
                                          $dataDetailUraian['digit_kro'] == $dataKomponen["digit_kro"] &&
                                          $dataDetailUraian['kode_kro'] == $dataKomponen["kode_kro"] &&
                                          $dataDetailUraian['kode_relaxtime_ro'] == $dataKomponen["kode_relaxtime_ro"] &&
                                          $dataDetailUraian['kode_komponen'] == $dataKomponen["kode_komponen"] &&
                                          $dataDetailUraian['kode_sub_komponen'] == $dataKomponen["kode_sub_komponen"] &&
                                          $dataDetailUraian['kode_uraian'] == $dataUraian['kode_uraian']
                                        ):
                                          unset($detail_uraian[$hapusDetailUraian]); ?>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td><?= $dataDetailUraian['deskripsi_detail_uraian']; ?></td>
                                            <td><?= $dataDetailUraian['vol_detail_uraian']; ?></td>
                                            <td><?= $dataDetailUraian['sat_detail_uraian']; ?></td>
                                            <td><?= number_format($dataDetailUraian['harga_detail_uraian'], 0, ',', '.'); ?></td>
                                            <td><?= number_format($dataDetailUraian['jumlah_detail_uraian'], 0, ',', '.'); ?>,-</td>

                                            <td class="relaxtime-colom-aksi"><button
                                                class="btn btn-sm btn-info btn-realisasi-detail-anggaran" data-toggle="modal"
                                                data-placement="top" title="Input Angka Realisasi"
                                                data-target="#relaxtime-realisasi-modal"
                                                data-id="<?= $dataDetailUraian['id_relaxtime_detail_uraian']; ?>"
                                                data-kro="<?= $dataKomponen["digit_kro"] . "." . $dataKomponen["kode_kro"] . "." . $dataKomponen["kode_relaxtime_ro"]; ?>"
                                                data-komponen="<?= $dataKomponen["kode_komponen"]; ?>"
                                                data-komponen-deskripsi="<?= $dataKomponen["deskripsi_komponen"]; ?>"
                                                data-sub-komponen="<?= $dataKomponen["kode_sub_komponen"]; ?>"
                                                data-sub-komponen-deskripsi="<?= $dataKomponen["deskripsi_sub_komponen"]; ?>"
                                                data-uraian="<?= $dataUraian['kode_uraian']; ?>"
                                                data-uraian-deskripsi="<?= $dataUraian['deskripsi_uraian']; ?>"
                                                data-detail-uraian-deskripsi="<?= $dataDetailUraian['deskripsi_detail_uraian']; ?>"><i
                                                  class="fas fa-chart-line"></i></button></td>

                                          </tr>
                                        <?php endif; ?>
                                      <?php endforeach; ?>
                                    <?php endif; ?>
                                  <?php endforeach; ?>
                                  <?php
                                endif;
                                ?>

                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- /.card -->
  </div>
</div>


<script>
  $('.date-today').datetimepicker({
    format: 'L',
    format: 'YYYY-MM-DD',
  });
</script>