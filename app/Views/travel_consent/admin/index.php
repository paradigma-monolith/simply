<?= $this->extend('layouts/adminLTE-3.2.0') ?>

<?= $this->section('content') ?>
    <div class="content-header">
        <div class="container-fluid">
           
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <a href="<?= base_url('travelconsent/home') ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka Halaman Form Publik
                </a>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="aircraft-tab" data-toggle="tab" href="#aircraft" role="tab">Aircraft Sanitation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sanitasi-tab" data-toggle="tab" href="#sanitasi" role="tab">Sanitasi Kapal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="alat-tab" data-toggle="tab" href="#alat" role="tab">Alat Kesehatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="obat-tab" data-toggle="tab" href="#obat" role="tab">Obat P3K</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
                <!-- Aircraft Sanitation -->
                <div class="tab-pane fade show active" id="aircraft" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Aircraft Sanitation Inspections</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pemilik</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Kesimpulan</th>
                                        <th class="text-center" width="120px">Foto Geotag</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($aircrafts as $i => $row): ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>
                                        <td><?= $row['pemilik'] ?></td>
                                        <td><?= $row['tanggal'] ?></td>
                                        <td><?= $row['waktu'] ?></td>
                                        <td><?= $row['kesimpulan'] ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($row['foto_geotagging'])): ?>
                                                <button type="button" class="btn btn-xs btn-outline-info" onclick="viewGeotagImage('<?= base_url($row['foto_geotagging']) ?>', 'Aircraft Sanitation: <?= htmlspecialchars($row['pemilik'] ?? '') ?>')">
                                                    <i class="fas fa-camera mr-1"></i> Lihat Foto
                                                </button>
                                            <?php else: ?>
                                                <span class="badge badge-light text-muted"><i class="fas fa-times-circle mr-1"></i>Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/travelconsent/edit_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
                                            <button type="button" class="btn btn-sm btn-success" onclick="showPublicLinkModal('<?= base_url('travelconsent/doc_aircraft/' . $row['id']) ?>', 'Aircraft Sanitation: <?= htmlspecialchars($row['pemilik'] ?? '') ?>')">
                                                <i class="fas fa-link mr-1"></i> Link Publik
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sanitasi Kapal -->
                <div class="tab-pane fade" id="sanitasi" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sertifikat Pengendalian Sanitasi Kapal</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kapal</th>
                                        <th>Pelabuhan</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Kapal</th>
                                        <th>Pemeriksa</th>
                                        <th class="text-center" width="120px">Foto Geotag</th>
                                        <th width="250px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($sanitasis)): foreach($sanitasis as $i => $row): ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>
                                        <td><?= $row['nama_kapal'] ?></td>
                                        <td><?= $row['pelabuhan'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                        <td><?= $row['jenis_kapal'] ?></td>
                                        <td><?= $row['nama_pemeriksa'] ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($row['foto_geotagging'])): ?>
                                                <button type="button" class="btn btn-xs btn-outline-info" onclick="viewGeotagImage('<?= base_url($row['foto_geotagging']) ?>', 'Sanitasi Kapal: <?= htmlspecialchars($row['nama_kapal'] ?? '') ?>')">
                                                    <i class="fas fa-camera mr-1"></i> Lihat Foto
                                                </button>
                                            <?php else: ?>
                                                <span class="badge badge-light text-muted"><i class="fas fa-times-circle mr-1"></i>Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/travelconsent/edit_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
                                            <button type="button" class="btn btn-sm btn-success" onclick="showPublicLinkModal('<?= base_url('travelconsent/doc_sanitasi/' . $row['id']) ?>', 'Sanitasi Kapal: <?= htmlspecialchars($row['nama_kapal'] ?? '') ?>')">
                                                <i class="fas fa-link mr-1"></i> Link Publik
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Alat Kesehatan -->
                <div class="tab-pane fade" id="alat" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Alat Kesehatan P3K</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penerbangan Haji</th>
                                        <th>Embarkasi</th>
                                        <th>Kloter</th>
                                        <th>Crew</th>
                                        <th class="text-center" width="120px">Foto Geotag</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($alats as $i => $row): ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>
                                        <td><?= $row['penerbangan_haji_h'] ?> H / <?= $row['penerbangan_haji_m'] ?> M</td>
                                        <td><?= $row['embarkasi'] ?></td>
                                        <td><?= $row['nomor_kloter'] ?></td>
                                        <td><?= $row['crew_nama'] ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($row['foto_geotagging'])): ?>
                                                <button type="button" class="btn btn-xs btn-outline-info" onclick="viewGeotagImage('<?= base_url($row['foto_geotagging']) ?>', 'Alat Kesehatan: Kloter <?= htmlspecialchars($row['nomor_kloter'] ?? '') ?>')">
                                                    <i class="fas fa-camera mr-1"></i> Lihat Foto
                                                </button>
                                            <?php else: ?>
                                                <span class="badge badge-light text-muted"><i class="fas fa-times-circle mr-1"></i>Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/travelconsent/edit_alat/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_alat/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_alat/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
                                            <button type="button" class="btn btn-sm btn-success" onclick="showPublicLinkModal('<?= base_url('travelconsent/doc_alat/' . $row['id']) ?>', 'Alat Kesehatan: Kloter <?= htmlspecialchars($row['nomor_kloter'] ?? '') ?>')">
                                                <i class="fas fa-link mr-1"></i> Link Publik
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Obat P3K -->
                <div class="tab-pane fade" id="obat" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Obat P3K</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Penerbangan Haji</th>
                                        <th>Embarkasi</th>
                                        <th>Kloter</th>
                                        <th>Crew</th>
                                        <th class="text-center" width="120px">Foto Geotag</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($obats as $i => $row): ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>
                                        <td><?= $row['penerbangan_haji_h'] ?> H / <?= $row['penerbangan_haji_m'] ?> M</td>
                                        <td><?= $row['embarkasi'] ?></td>
                                        <td><?= $row['nomor_kloter'] ?></td>
                                        <td><?= $row['crew_nama'] ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($row['foto_geotagging'])): ?>
                                                <button type="button" class="btn btn-xs btn-outline-info" onclick="viewGeotagImage('<?= base_url($row['foto_geotagging']) ?>', 'Obat P3K: Kloter <?= htmlspecialchars($row['nomor_kloter'] ?? '') ?>')">
                                                    <i class="fas fa-camera mr-1"></i> Lihat Foto
                                                </button>
                                            <?php else: ?>
                                                <span class="badge badge-light text-muted"><i class="fas fa-times-circle mr-1"></i>Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/travelconsent/edit_obat/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_obat/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_obat/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
                                            <button type="button" class="btn btn-sm btn-success" onclick="showPublicLinkModal('<?= base_url('travelconsent/doc_obat/' . $row['id']) ?>', 'Obat P3K: Kloter <?= htmlspecialchars($row['nomor_kloter'] ?? '') ?>')">
                                                <i class="fas fa-link mr-1"></i> Link Publik
                                            </button>
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

        <!-- Modal Preview Foto Geotagging -->
        <div class="modal fade" id="modalGeotagImage" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGeotagTitle"><i class="fas fa-camera mr-2"></i>Foto Geotagging</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-3 bg-light">
                        <img id="modalGeotagImg" src="" alt="Foto Geotagging" class="img-fluid rounded shadow" style="max-height: 70vh;">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="" id="modalGeotagDownload" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka Ukuran Penuh
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Popup Link Publik Dokumen -->
        <div class="modal fade" id="modalPublicLink" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title font-weight-bold" id="modalPublicLinkTitle"><i class="fas fa-link mr-2"></i>Link Publik Dokumen</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3 text-success">
                            <i class="fas fa-file-alt fa-3x"></i>
                        </div>
                        <p class="text-muted small mb-3">Salin link publik di bawah ini untuk melihat atau membagikan dokumen:</p>
                        <div class="input-group">
                            <input type="text" id="modalPublicLinkInput" class="form-control bg-light text-dark font-weight-bold" value="" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-dark font-weight-bold" id="btnCopyModalPublicLink" onclick="copyModalPublicLink(this)">
                                    <i class="fas fa-copy mr-1"></i> Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between bg-light">
                        <a href="" id="modalPublicLinkOpen" target="_blank" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Dokumen
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showPublicLinkModal(url, title) {
            document.getElementById('modalPublicLinkInput').value = url;
            document.getElementById('modalPublicLinkOpen').href = url;
            if (title) {
                document.getElementById('modalPublicLinkTitle').innerHTML = '<i class="fas fa-link mr-2"></i>Link Publik - ' + title;
            }
            document.getElementById('btnCopyModalPublicLink').innerHTML = '<i class="fas fa-copy mr-1"></i> Copy Link';
            $('#modalPublicLink').modal('show');
        }

        function copyModalPublicLink(btn) {
            var input = document.getElementById('modalPublicLinkInput');
            if (!input) return;
            input.select();
            input.setSelectionRange(0, 99999);
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(input.value);
            } else {
                document.execCommand('copy');
            }
            var originalHtml = '<i class="fas fa-copy mr-1"></i> Copy Link';
            btn.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin!';
            setTimeout(function() {
                btn.innerHTML = originalHtml;
            }, 2000);
        }

        function viewGeotagImage(url, title) {
            document.getElementById('modalGeotagImg').src = url;
            document.getElementById('modalGeotagDownload').href = url;
            if (title) {
                document.getElementById('modalGeotagTitle').innerHTML = '<i class="fas fa-camera mr-2"></i>Foto Geotagging - ' + title;
            }
            $('#modalGeotagImage').modal('show');
        }
    </script>
<?= $this->endSection() ?>