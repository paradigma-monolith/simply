<div class="container">
    <?php
    if ($getData):
        foreach ($getData as $data):
            $tanggal = json_decode($data['tanggal'], true);
    ?>
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <div class="card mb-4" style="box-shadow: 2px 1px 0px 0px #b3ecf961, -2px -2px 4px 4px #b2dfff;">
                        <div class="card-header">
                            <?php if (shortdate_indo2($tanggal[0]) == shortdate_indo2($tanggal[count($tanggal) - 1])): ?>
                                <?= shortdate_indo3($tanggal[0]); ?>
                            <?php else: ?>
                                <?= shortdate_indo3($tanggal[0]); ?> - <?= shortdate_indo3($tanggal[count($tanggal) - 1]); ?>
                            <?php endif; ?>
                        </div>
                        <div class="card-body" style="position: relative;">
                            <?php if ($data['nama_kendaraan'] != null): ?>
                                <img src="/dist/img/boxed-bg.jpg" data-src="/dist/img/<?= $data['gambar']; ?>" class="img-thumbnail card-img-top mb-2" style="width: 20%;">
                            <?php endif; ?>
                            <div class="d-inline-block float-end">
                                <p class="text-end" style="border-bottom: 1px solid;">
                                    <?php if ($data['nama_kendaraan'] == null): ?>
                                        <strong><small></small><?= $data['kendaraan'] == 'Kendaraan Umum' ? "Gunakan Kendaraan Umum" : "Kendaraan belum dipilih oleh atasan"; ?>!</strong>
                                    <?php else: ?>
                                        <strong><?= $data['nama_kendaraan']; ?></strong> <br />
                                        <i><?= $data['plat_nomor']; ?></i>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <p style="font-size: 9pt;" class="p-0 m-0">Jumlah penumpang : <?= count(json_decode($data['penumpang'], true)); ?></p>
                            <p style="font-size: 9pt;" class="p-0 m-0">Tujuan : <?= $data['tujuan']; ?></p>
                            <p style="font-size: 9pt;" class="p-0 m-0">Keterangan : <small class='text-secondary'><?= $data['keterangan']; ?></small></p>
                            <p style="font-size: 9pt;" class="text-success p-0 m-0">Peminjam : <small class='text-success'><?= $data['nama']; ?></small></p>
                        </div>
                        <div class="card-footer">
                            <?php if ($nip == $data['nip']): ?>
                                <button class="btn btn-sm btn-danger float-end batalkan-peminjaman-kendaraan" data-id="<?= $data['id_kendaraan_kantor_riwayat']; ?>"><i class="far fa-window-close"></i> Batalkan peminjaman</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary float-end"><i class="fas fa-exclamation"></i> Pegawai lain mengajukan</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
    else:
        ?>
        <h2>Belum ada yang mengajukan peminjaman kendaraan untuk saat ini!</h2>
    <?php endif; ?>
</div>

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
    });
</script>