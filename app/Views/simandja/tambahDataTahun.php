<div class="container-fluid p-0">
    <form action="/crud/tambahDataTahun" method="post" enctype="multipart/form-data">
        <input type="hidden" name="kategori" value="<?= isset($kategoriGet) ? $kategoriGet : 'simandjaRevisi'; ?>">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Tahun <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <select class="form-control custom-select" id="tahun" name="tahun" required>
                    <?php
                    $currentYear = date("Y");
                    $startYear = 2023;
                    for ($year = $currentYear; $year >= $startYear ; $year--) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="font-weight-bold">Link <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                </div>
                <input type="url" class="form-control" name="link" placeholder="https://docs.google.com/..." required>
            </div>
            <small class="form-text text-muted">Masukkan link dokumen spreadsheet lengkap dengan https://</small>
        </div>
        <div class="form-group mb-4">
            <label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukkan nama tombol atau keterangan dokumen..." required></textarea>
        </div>
        <div class="text-right border-top pt-3">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Data</button>
        </div>
    </form>
</div>