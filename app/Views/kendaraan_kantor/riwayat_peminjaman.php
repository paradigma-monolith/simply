<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <table id="tableWithoutExporting" class="table table-sm table-bordered table-stripped">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 25px;">No</th>
                            <th>Daftar Kendaraan</th>
                            <th>Tujuan dan Kegiatan</th>
                            <th>Penumpang</th>
                            <th style="width: 85px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        foreach ($getData as $data):
                            $tanggal = json_decode($data['tanggal'], true);
                            $penumpang = json_decode($data['penumpang'], true);
                        ?>
                            <tr>
                                <td class="text-center"><?= $nomor++; ?></td>
                                <td>
                                    <span style="position: absolute; opacity: 0; height: 0; font-size: 0;"><?= $nomor++; ?></span>
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action">

                                            <?php if ($data['kendaraan'] == "Kendaraan Kantor"): ?>
                                                <?php if ($data['nama_kendaraan']): ?>
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <img src="/dist/img/boxed-bg.jpg" data-src="/dist/img/<?= $data['gambar']; ?>" class="card-img-top mb-1" alt="Produk 1" style="width: 150px;">
                                                        <small class="text-muted"><?= count($tanggal); ?> hari peminjaman</small>
                                                    </div>
                                                    <p class="mb-1"><?= $data['nama_kendaraan']; ?> (<i><?= $data['plat_nomor']; ?></i>)</p>
                                                    <p class="mb-1">Peminjam: <span class="text-success"><?= name_capitalize($data['peminjam']); ?></span></p>
                                                <?php else: ?>
                                                    <h4>Kendaraan Tidak/Belum Ditentukan</h4>
                                                    <p class="mb-1">Peminjam: <span class="text-success"><?= name_capitalize($data['peminjam']); ?></span></p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <h4>Disarankan Menggunakan Kendaraan Umum</h4>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <?php if (count($tanggal) > 1): ?>
                                                    <?= shortdate_indo3($tanggal[0]); ?> - <?= shortdate_indo3($tanggal[count($tanggal) - 1]); ?>
                                                <?php else: ?>
                                                    <?= shortdate_indo3($tanggal[0]); ?>
                                                <?php endif; ?>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <p><strong>Tujuan</strong> : <?= $data['tujuan']; ?></p>
                                    <p><strong>Kegiatan</strong> : <?= $data['keterangan']; ?></p>
                                </td>
                                <td>
                                    <ul id="penumpang-list">
                                        <?php for ($i = 0; $i < count($penumpang); $i++): ?>
                                            <li class="get-penumpang" data-id="<?= $penumpang[$i]; ?>">
                                                <i class='fas fa-sync-alt fa-spin'></i>
                                                <span class='text-bold pt-2'>Loading...</span>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </td>
                                <td class='text-center'>
                                    <?php if (session()->get('role') == 'Admin' || $data['username'] == session()->get('username')): ?>
                                        <a href="/kendaraan_kantor/update_riwayat/<?= $data['id_kendaraan_kantor_riwayat']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                                        <form action="/kendaraan_kantor/dlt_riwayat/<?= $data['id_kendaraan_kantor_riwayat']; ?>" method="post" class="d-inline formHapusData">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary"><i class="fas fa-exclamation"></i> Hanya peminjam dan admin</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script>
    $(document).ready(function() {
        const lazyImages = $('img[data-src]');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        $(img).attr('src', $(img).data('src'));
                        $(img).removeAttr('data-src');
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.each(function() {
                observer.observe(this);
            });
        } else {
            lazyImages.each(function() {
                $(this).attr('src', $(this).data('src'));
                $(this).removeAttr('data-src');
            });
        }
        let errorLoopingGetPegawai = 0;

        function loadPenumpang(index = 0) {
            var $penumpangItem = $('#penumpang-list .get-penumpang').eq(index);

            if ($penumpangItem.length === 0) {
                // Semua data telah diproses
                Swal.close()
                return;
            }

            var formData = {
                id: $penumpangItem.data('id')
            };

            $.ajax({
                url: '/kendaraan_kantor/get_penumpang',
                data: formData,
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Memuat data...',
                        showConfirmButton: false,
                        timer: 3000,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                },
                success: function(response) {
                    if (response.data) {
                        // Ubah konten li dengan data baru
                        $penumpangItem.html(response.data);
                    } else {
                        $penumpangItem.html('<span class="text-danger">Data tidak ditemukan</span>');
                    }
                },
                complete: function() {
                    // Lanjutkan ke data berikutnya
                    loadPenumpang(index + 1);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    // Tetap lanjutkan meskipun ada error
                    errorLoopingGetPegawai++;
                    if (errorLoopingGetPegawai > 3) {
                        errorLoopingGetPegawai = 0;
                        loadPenumpang(index + 1);
                    } else {
                        loadPenumpang(index);
                    }
                }
            });
        }

        // Memulai proses antrian
        loadPenumpang();
    });
</script>
<?= $this->endSection(); ?>