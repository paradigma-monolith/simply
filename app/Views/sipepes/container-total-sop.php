<div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4">
            <div class="card text-center">
                <div class="card-header"><h3><strong>Total Daftar SOP</strong></h3></div>
                <div class="card-body py-5"><h1><strong><?= $total_sop_utama + $total_sop_pendukung; ?></strong></h1></div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-8">
            <div class="row">
            <?php foreach($sop_utama as $data): ?>
                <div class="col-xl-2 col-md-3">
                    <div class="card text-center">
                        <div class="card-header"><?= $data['unit_kerja']; ?></div>
                        <div class="card-body"><h3><?= $data['sop_utama']; ?></h3></div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php foreach($sop_pendukung as $data): ?>
                <div class="col-xl-2 col-md-3">
                    <div class="card text-center">
                        <div class="card-header"><?= $data['unit_kerja']; ?></div>
                        <div class="card-body"><h3><?= $data['sop_pendukung']; ?></h3></div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div> 