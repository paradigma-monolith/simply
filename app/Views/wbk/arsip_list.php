<div class="container-fluid">
  <?php foreach ($pokja as $data_pokja) : ?>
    <label><?= $data_pokja['nama_pokja']; ?></label>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">I. PEMENUHAN</h3>
          </div>
          <div class="card-body p-0">
            <table id="tabel_arsip_saya" class="table table-hover">
              <tbody>
                <?php foreach ($kategori as $data_kategori) : ?>
                  <?php if ($data_pokja['id_wbk_pokja'] == $data_kategori['id_pokja']) : ?>
                    <?php if ($data_kategori['jenis'] == "Pemenuhan") : ?>
                      <tr data-widget="expandable-table" aria-expanded="false">
                        <td>
                          <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                          <?= $data_kategori['no']; ?>. <?= $data_kategori['nama_kategori']; ?>
                        </td>
                      </tr>
                      <tr class="expandable-body d-none">
                        <td>
                          <div class="p-0">
                            <table class="table table-hover">
                              <tbody>
                                <?php foreach ($subKategori as $no => $data_subKategori) : ?>
                                  <?php if ($data_pokja['id_wbk_pokja'] == $data_subKategori['id_pokja']) : ?>
                                    <?php if ($data_subKategori['jenis'] == "Pemenuhan") : ?>
                                      <?php if ($data_subKategori['no'] == $data_kategori['no']) : ?>
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                          <td>
                                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                            <?= $data_subKategori['no']; ?>.<?= $data_subKategori['poin']; ?>. <?= $data_subKategori['nama_kategori']; ?>
                                          </td>
                                        </tr>
                                        <tr class="expandable-body">
                                          <td>
                                            <div class="pl-5" style="display: none;">
                                              <table id="tabelPemenuhan<?= $no; ?>" class="table table-bordered table-striped table-hover" style="width: 100%; margin: 0 0 0 0;">
                                                <thead class="text-center">
                                                  <tr>
                                                    <th>No</th>
                                                    <th>Nama Dokumen</th>
                                                    <th>Diunggah Oleh</th>
                                                    <th>Aksi</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $nomor = 1; ?>
                                                  <?php foreach ($wbkArsipFile as $data_wbkArsipFile) : ?>
                                                    <?php if ($data_wbkArsipFile['id_wbk_kategori'] == $data_subKategori['id_kategori']) : ?>
                                                      <tr>
                                                        <td><?= $nomor++; ?></td>
                                                        <td><?= $data_wbkArsipFile['nama_file']; ?></td>
                                                        <td><?= $data_wbkArsipFile['nama_anggota'];  ?></td>
                                                        <td>
                                                          <button class="btn m-1 btn-xs bg-gradient-danger btn-unduh-arsip" data-toggle="tooltip" data-placement="bottom" title="Lihat File" for="Lihat File" data-arsip="<?= $data_wbkArsipFile['file']; ?>"><i class="far fa-file-pdf"></i></button>
                                                          <!-- <a href="/wbk/upload_file/<? //= $data_wbkArsipFile['id_wbk_rencana_kerja']; 
                                                                                          ?>/<? //= $data_wbkArsipFile['id_wbk_arsip']; 
                                                                                              ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah File" for="Ubah File"><i class="fas fa-edit"></i></a> -->
                                                          <!-- <form action="/wbk/hapus_file/<?= $data_wbkArsipFile['id_wbk_arsip']; ?>" method="post" class="d-inline formHapusData">
                                                          <?= csrf_field(); ?>
                                                          <input type="hidden" name="_method" value="DELETE">
                                                          <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Arsip"><i class="fas fa-trash-alt"></i></button>
                                                        </form> -->
                                                        </td>
                                                      </tr>
                                                    <?php endif; ?>
                                                  <?php endforeach; ?>
                                                </tbody>
                                              </table>
                                              <script>
                                                $(function() {
                                                  var table = $("#tabelPemenuhan<?= $no; ?>").DataTable({
                                                    paging: true, // Nonaktifkan paginasi
                                                    info: false, // Nonaktifkan info tabel
                                                  });

                                                  function tabelPemenuhan() {
                                                    var data = $(this).attr("data-arsip")

                                                    var tr = $(this).closest("tr");
                                                    var row = table.row(tr);

                                                    if (row.child.isShown()) {
                                                      // Baris sudah terbuka, tutup
                                                      row.child.hide();
                                                      tr.removeClass("shown");
                                                    } else {
                                                      // Tampilkan konten detail di dalam baris
                                                      row.child(`<iframe style="height:50vw; width:100%;" src="/wbk_arsip/${data}" title="description"></iframe>`).show();
                                                    }
                                                  }

                                                  // Attach the event listener to the table's tbody using the new function
                                                  $("#tabelPemenuhan<?= $no; ?> tbody").on("click", "button.btn-unduh-arsip", tabelPemenuhan);
                                                })
                                              </script>
                                            </div>
                                          </td>
                                        </tr>
                                      <?php endif; ?>
                                    <?php endif; ?>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">I. REFORM</h3>
          </div>
          <div class="card-body p-0">
            <table id="tabel_arsip_saya" class="table table-hover">
              <tbody>
                <?php foreach ($kategori as $data_kategori) : ?>
                  <?php if ($data_pokja['id_wbk_pokja'] == $data_kategori['id_pokja']) : ?>
                    <?php if ($data_kategori['jenis'] == "Reform") : ?>
                      <tr data-widget="expandable-table" aria-expanded="false">
                        <td>
                          <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                          <?= $data_kategori['no']; ?>. <?= $data_kategori['nama_kategori']; ?>
                        </td>
                      </tr>
                      <tr class="expandable-body d-none">
                        <td>
                          <div class="p-0">
                            <table class="table table-hover">
                              <tbody>
                                <?php foreach ($subKategori as $no => $data_subKategori) : ?>
                                  <?php if ($data_pokja['id_wbk_pokja'] == $data_subKategori['id_pokja']) : ?>
                                    <?php if ($data_subKategori['jenis'] == "Reform") : ?>
                                      <?php if ($data_subKategori['no'] == $data_kategori['no']) : ?>
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                          <td>
                                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                            <?= $data_subKategori['no']; ?>.<?= $data_subKategori['poin']; ?>. <?= $data_subKategori['nama_kategori']; ?>
                                          </td>
                                        </tr>
                                        <tr class="expandable-body">
                                          <td>
                                            <div class="pl-5" style="display: none;">
                                              <table id="tabelReform<?= $no; ?>" class="table table-bordered table-striped table-hover" style="width: 100%; margin: 0 0 0 0;">
                                                <thead class="text-center">
                                                  <tr>
                                                    <th>No</th>
                                                    <th>Nama Dokumen</th>
                                                    <th>Diunggah Oleh</th>
                                                    <th>Aksi</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $nomor = 1; ?>
                                                  <?php foreach ($wbkArsipFile as $data_wbkArsipFile) : ?>
                                                    <?php if ($data_wbkArsipFile['id_wbk_kategori'] == $data_subKategori['id_kategori']) : ?>
                                                      <tr>
                                                        <td><?= $nomor++; ?></td>
                                                        <td><?= $data_wbkArsipFile['nama_file']; ?></td>
                                                        <td><?= $data_wbkArsipFile['nama_anggota'];  ?></td>
                                                        <td>
                                                          <button class="btn m-1 btn-xs bg-gradient-danger btn-unduh-arsip" data-toggle="tooltip" data-placement="bottom" title="Lihat File" for="Lihat File" data-arsip="<?= $data_wbkArsipFile['file']; ?>"><i class="far fa-file-pdf"></i></button>
                                                          <!-- <a href="/wbk/upload_file/<?= $data_wbkArsipFile['id_wbk_rencana_kerja']; ?>/<?= $data_wbkArsipFile['id_wbk_arsip']; ?>" class="btn m-1 btn-xs bg-gradient-warning" data-toggle="tooltip" data-placement="bottom" title="Ubah File" for="Ubah File"><i class="fas fa-edit"></i></a>
                                                        <form action="/wbk/hapus_file/<?= $data_wbkArsipFile['id_wbk_arsip']; ?>" method="post" class="d-inline formHapusData">
                                                          <?= csrf_field(); ?>
                                                          <input type="hidden" name="_method" value="DELETE">
                                                          <button type="submit" class="btn m-1 btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Arsip"><i class="fas fa-trash-alt"></i></button>
                                                        </form> -->
                                                        </td>
                                                      </tr>
                                                    <?php endif; ?>
                                                  <?php endforeach; ?>
                                                </tbody>
                                              </table>
                                              <script>
                                                $(function() {
                                                  var table = $("#tabelReform<?= $no; ?>").DataTable({
                                                    paging: true, // Nonaktifkan paginasi
                                                    info: false, // Nonaktifkan info tabel
                                                  });

                                                  function tabelReform() {
                                                    var data = $(this).attr("data-arsip")

                                                    var tr = $(this).closest("tr");
                                                    var row = table.row(tr);

                                                    if (row.child.isShown()) {
                                                      // Baris sudah terbuka, tutup
                                                      row.child.hide();
                                                      tr.removeClass("shown");
                                                    } else {
                                                      // Tampilkan konten detail di dalam baris
                                                      row.child(`<iframe style="height:50vw; width:100%;" src="/wbk_arsip/${data}" title="description"></iframe>`).show();
                                                    }
                                                  }

                                                  // Attach the event listener to the table's tbody using the new function
                                                  $("#tabelReform<?= $no; ?> tbody").on("click", "button.btn-unduh-arsip", tabelReform);
                                                })
                                              </script>
                                            </div>
                                          </td>
                                        </tr>
                                      <?php endif; ?>
                                    <?php endif; ?>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <br>
  <?php endforeach; ?>
</div>