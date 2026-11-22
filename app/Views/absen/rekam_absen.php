<div class="container">
    <?php foreach($getData as $idx => $data): ?>
        <?php $rekamUrl = '/absen/rekam-foto/' . (int) $id_absen . '/' . (int) $idx; ?>
        <div class="media">
            <span class="d-none"><?= (int) $id_absen; ?></span>
            <img src="<?= $rekamUrl; ?>" data-src="<?= $rekamUrl; ?>" style="width: 89px;" class="mr-3 gallery setAbsenImage" alt="..." />
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