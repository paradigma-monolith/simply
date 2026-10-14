<div class="container">
    <?php
    if ($getData):
        foreach ($getData as $data):
            $tanggal = json_decode($data['tanggal'], true);
    ?>
            <div class="row justify-content-md-center findContainer-1">
                <div class="col-md-6">
                    <div class="card mb-4" style="box-shadow: 2px 1px 0px 0px #b3ecf961, -2px -2px 4px 4px #b2dfff;">
                        <div class="card-header">
                            <?php if (shortdate_indo2($tanggal[0]) == shortdate_indo2($tanggal[count($tanggal) - 1])): ?>
                                <?= shortdate_indo3($tanggal[0]); ?>
                            <?php else: ?>
                                <?= shortdate_indo3($tanggal[0]); ?> - <?= shortdate_indo3($tanggal[count($tanggal) - 1]); ?>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-2" style="position: relative;">
                            <div class="row">
                                <div class="col-8">
                                    <p style="font-size: 9pt;" class="p-0 m-0">Jumlah penumpang : <?= count(json_decode($data['penumpang'], true)); ?></p>
                                    <p style="font-size: 9pt;" class="p-0 m-0">Tujuan : <?= $data['tujuan']; ?></p>
                                    <p style="font-size: 9pt;" class="p-0 m-0">Keterangan : <small class='text-secondary'><?= $data['keterangan']; ?></small></p>
                                    <p style="font-size: 9pt;" class="text-success p-0 m-0">Peminjam : <small class='text-success'><?= name_capitalize($data['nama']); ?></small></p>
                                </div>
                                <div class="col-4">
                                    <?php
                                    $penumpang = json_decode($data['penumpang'], true);
                                    ?>
                                    <ul id="penumpang-list" class="p-0">
                                        <?php for ($i = 0; $i < count($penumpang); $i++): ?>
                                            <li style="font-size: 6pt;" class="get-penumpang" data-id="<?= $penumpang[$i]; ?>">
                                                <i class='fas fa-sync-alt fa-spin'></i>
                                                <span class='text-bold pt-2'>Loading...</span>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-warning float-end mb-1 me-1 px-2 pb-1 set-data-form" data-tanggal="<?= json_decode($data['tanggal'], true)[0] . " - " . json_decode($data['tanggal'], true)[count(json_decode($data['tanggal'], true)) - 1]; ?>" data-penumpang="<?= implode(",", json_decode($data['penumpang'], true)); ?>" data-tujuan="<?= $data['tujuan']; ?>" data-keterangan="<?= $data['keterangan']; ?>" data-peminjam="<?= name_capitalize($data['nama']); ?>" data-totalpenumpang="<?= count(json_decode($data['penumpang'], true)); ?>" data-id="<?= $data['id_kendaraan_kantor_riwayat']; ?>">
                                <small style="font-size: 9pt;"><i class="fas fa-car"></i> Memilih Kendaraan</small>
                            </button>
                            <button class="btn btn-sm btn-danger float-end mb-1 me-1 px-2 pb-1 set-data-form-public-transport" data-id="<?= $data['id_kendaraan_kantor_riwayat']; ?>">
                                <small style="font-size: 9pt;"><i class="fas fa-exclamation"></i> Gunakan Kendaraan Umum</small>
                            </button>
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
        loadPenumpang();
    });
</script>