<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">
                            <i class="fas fa-notes-medical mr-2"></i>
                            Data Permohonan Layanan Kesehatan Kapal
                        </h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="/public/layanan_kesehatan_kapal" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka Form Public
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                    <!-- Filter and Export Form -->
                    <form method="GET" action="/layanan_kesehatan_kapal" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label>Mulai Tanggal (Kedatangan):</label>
                                    <input type="date" class="form-control" name="start_date" value="<?= esc($start_date ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label>Sampai Tanggal (Kedatangan):</label>
                                    <input type="date" class="form-control" name="end_date" value="<?= esc($end_date ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                                <a href="/layanan_kesehatan_kapal" class="btn btn-secondary"><i class="fas fa-sync mr-1"></i> Reset</a>
                                <a href="/layanan_kesehatan_kapal/export_excel<?= isset($start_date) && isset($end_date) && $start_date && $end_date ? '?start_date='.$start_date.'&end_date='.$end_date : '' ?>" class="btn btn-success"><i class="fas fa-file-excel mr-1"></i> Export Excel</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="tableLayanan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu Kedatangan</th>
                                    <th>Nama Kapal</th>
                                    <th>Asal/Tujuan</th>
                                    <th>Crew</th>
                                    <th>Dokumen Kapal</th>
                                    <th>Layanan Diminta</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($layananKesehatan as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['waktu_kedatangan'])); ?></td>
                                    <td><?= esc($row['nama_kapal']); ?></td>
                                    <td>
                                        Asal: <?= esc($row['pelabuhan_asal']); ?><br>
                                        Tujuan: <?= esc($row['pelabuhan_tujuan']); ?>
                                    </td>
                                    <td><?= esc($row['jumlah_crew']); ?></td>
                                    <td>
                                        <ul class="mb-0 pl-3">
                                            <?php if($row['dok_buku_kesehatan_kapal']) echo '<li>Buku Kesehatan Kapal</li>'; ?>
                                            <?php if($row['dok_sscec']) echo '<li>SSCEC/SSCC (Exp: ' . ($row['dok_sscec_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($row['dok_sscec_tgl_kadaluarsa'])) : '-') . ')</li>'; ?>
                                            <?php if($row['dok_p3k']) echo '<li>Sertifikat P3K (Exp: ' . ($row['dok_p3k_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($row['dok_p3k_tgl_kadaluarsa'])) : '-') . ')</li>'; ?>
                                            <?php if($row['dok_phqc']) echo '<li>PHQC dari Pelabuhan sebelumnya</li>'; ?>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="mb-0 pl-3">
                                            <?php if($row['layanan_pemeriksaan_kesehatan']) echo '<li>Pemeriksaan kesehatan sanitasi dan crew</li>'; ?>
                                            <?php if($row['layanan_penerbitan_sscec']) echo '<li>Pemeriksaan dalam rangka penerbitan SSCEC/SSCC</li>'; ?>
                                            <?php if($row['layanan_penerbitan_p3k']) echo '<li>Pemeriksaan dalam rangka penerbitan sertifikat P3K</li>'; ?>
                                            <?php if($row['layanan_lainnya']) echo '<li>Lainnya: ' . esc($row['layanan_lainnya_sebutkan']) . '</li>'; ?>
                                            <li>Fasilitasi Transportasi: <?= esc($row['fasilitasi_transportasi']) ?></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($row['surat_permohonan']): ?>
                                                <a href="/<?= $row['surat_permohonan'] ?>" target="_blank" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Unduh Surat Permohonan"><i class="fas fa-download"></i></a>
                                            <?php else: ?>
                                                <span class="mr-1">-</span>
                                            <?php endif; ?>
                                            
                                            <a href="/layanan_kesehatan_kapal/export_pdf/<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-outline-danger mr-1" data-toggle="tooltip" title="Export PDF Permohonan">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            
                                            <a href="/layanan_kesehatan_kapal/edit/<?= $row['id'] ?>" class="btn btn-sm btn-warning mr-1" data-toggle="tooltip" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="/layanan_kesehatan_kapal/delete/<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-toggle="tooltip" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

<?= $this->section('script'); ?>
<script>
$(function () {
    $('#tableLayanan').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection(); ?>
