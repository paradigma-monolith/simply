<div class="container">
    <?php foreach($getData as $data): ?>
        <div class="media">
            <span class="d-none"><?= $id_absen; ?></span>
            <img src="/dist/foto_absen/<?= $data['foto']; ?>" data-src="/dist/foto_absen/<?= $data['foto']; ?>" style="width: 89px;" class="mr-3 gallery setAbsenImage" alt="..." />
            <div class="media-body">
                <h5 class="mt-0 setAbsenRekam"><?= $data['tombol']; ?></h5>
                <p class="mb-2 setAbsenAlamat"><?= $data['alamat_terdekat']; ?></p>
                <p class="mb-2">lon: <span class="setAbsenLongitude"><?= $data['longitude']; ?></span>, lan: <span class="setAbsenLatitude"><?= $data['latitude']; ?></span></p>
                <p class="mb-2 setAbsenJam"><?= $data['jam_absen']; ?></p>
                
                <?php if($superUser): ?>
                  <button class="btn btn-sm btn-warning float-right setNewRekamAbsen" style="border-radius: 50px;"> <i class="fas fa-edit"></i> Pilih Data Absen</button>
                <?php endif; ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>
</div>