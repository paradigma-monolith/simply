<div class="container-fluid">
    <?php
    if ($getData):
        ?>
        <table class="table table-bordered table-stripped table-light table-hover text-center">
            <thead>
                <tr class="table-secondary">
                    <th>Tanggal</th>
                    <th>Nama Kendaraan</th>
                    <th>Plat Nomor</th>
                    <th>Jumlah Penumpang</th>
                    <th>Tujuan</th>
                    <th>Keterangan</th>
                    <th>Peminjam</th>
                </tr>
            </thead>
            <tbody>
        <?php
        // for($i=0; $i< 20; $i++):
        foreach ($getData as $data):
            $tanggal = json_decode($data['tanggal'], true);
    ?>
          <tr>
              <td style="font-size: 20pt; font-weight: 700;">
                  <?php if (shortdate_indo2($tanggal[0]) == shortdate_indo2($tanggal[count($tanggal) - 1])): ?>
                    <?= shortdate_indo3($tanggal[0]); ?>
                <?php else: ?>
                    <?= shortdate_indo3($tanggal[0]); ?> - <?= shortdate_indo3($tanggal[count($tanggal) - 1]); ?>
                <?php endif; ?>
              </td>
              <td><strong style="position: relative; font-size: 18pt;"><?= $data['nama_kendaraan']; ?></strong> </td>
              <td style="font-size: 18pt;">(<i><?= $data['plat_nomor']; ?></i>)</td>
              <td><span style="font-size: 18pt;" class="p-0 m-0"><?= count(json_decode($data['penumpang'], true)); ?></span></td>
              <td><span style="font-size: 18pt;" class="p-0 m-0"><?= $data['tujuan']; ?></span></td>
              <td><span style="font-size: 18pt;" class="p-0 m-0"><small class='text-secondary'><?= $data['keterangan']; ?></small></span></td>
              <td><span style="font-size: 18pt;" class="text-success p-0 m-0"><small class='text-success'><?= $data['nama']; ?></small></span></td>
          </tr>
        <?php endforeach;
        // endfor;
        ?>
        </tbody>
        </table>
        <?php
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