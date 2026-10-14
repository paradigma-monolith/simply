<div class="row">
    <?php foreach ($getData as $data): ?>
        <div class="col-md-3 col-6 mb-4">
            <a href="javascript:void(0)" class="no-style getTheCar" data-id="<?= $data['id_kendaraan_kantor_daftar']; ?>" data-kendaraan="<?= $data['nama_kendaraan']; ?>" data-plat="<?= $data['plat_nomor']; ?>">
                <div class="card">
                    <img src="/dist/img/boxed-bg.jpg" data-src="/dist/img/<?= $data['gambar']; ?>" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <h6 class="card-title py-0 text-center"><?= $data['nama_kendaraan']; ?></h6>
                        <p class="card-text text-center"><?= $data['plat_nomor']; ?></p>
                    </div>
                </div>

            </a>
        </div>
    <?php endforeach; ?>
    <?php foreach ($notData as $data): ?>
        <div class="col-md-3 col-6 mb-4 block-cursor" style="background-color: rgba(128, 128, 128, 0.2); border-radius: 40px;">
            <a href="javascript:void(0)" class="no-style">
                <div class="card" style="opacity: .4">
                    <img src="/dist/img/boxed-bg.jpg" data-src="/dist/img/<?= $data['gambar']; ?>" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <h6 class="card-title py-0 text-center"><?= $data['nama_kendaraan']; ?></h6>
                        <p class="card-text text-center"><?= $data['plat_nomor']; ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
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