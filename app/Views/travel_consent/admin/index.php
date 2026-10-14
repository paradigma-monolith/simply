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
                                        <td>
                                            <a href="/travelconsent/edit_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_aircraft/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
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
                                        <th width="150px">Aksi</th>
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
                                        <td>
                                            <a href="/travelconsent/edit_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_sanitasi/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
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
                                        <td>
                                            <a href="/travelconsent/edit_alat/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_alat/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_alat/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
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
                                        <td>
                                            <a href="/travelconsent/edit_obat/<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/travelconsent/delete_obat/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Delete</a>
                                            <a href="/travelconsent/print_obat/<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">Cetak</a>
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
    </section>
<?= $this->endSection() ?>