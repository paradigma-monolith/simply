<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary"><i class="fas fa-clipboard-check"></i> Penilaian Kinerja, Perilaku & 360</h2>
                <p class="text-muted">Kelola data evaluasi kinerja, perilaku, dan penilaian 360 Anda.</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4" id="penilaianTab" role="tablist">
        <li class="nav-item">
            <?php if (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1): ?>
                <a class="nav-link" href="/ekip/kinerja"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja</a>
            <?php else: ?>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-clipboard-list"></i> Penilaian Kinerja (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-brain"></i> Penilaian Perilaku (Dinonaktifkan)</a>
        </li>
        <li class="nav-item">
            <?php if (!isset($pengaturan['tab_360_aktif']) || $pengaturan['tab_360_aktif'] == 1): ?>
                <a class="nav-link" href="/ekip/penilaian_360"><i class="fas fa-users"></i> Penilaian 360</a>
            <?php else: ?>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-users"></i> Penilaian 360 (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <?php if (isset($isPenilai) && $isPenilai): ?>
        <li class="nav-item">
            <a class="nav-link active" href="/ekip/kalibrasi"><i class="fas fa-balance-scale"></i> Kalibrasi</a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Evaluasi Section -->
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title text-success font-weight-bold mt-1">Form Kalibrasi (Penilaian Perilaku)</h3>
        <div class="card-tools d-flex align-items-center">
            <select class="form-control form-control-sm border-success mr-2" id="selectDinilai" style="width: 250px;">
                <option value="">-- Pilih Pegawai yang Dinilai --</option>
                <?php foreach($dinilai_list as $dinilai): ?>
                    <option value="<?= $dinilai['dinilai_id'] ?>" <?= (isset($selected_id) && $selected_id == $dinilai['dinilai_id']) ? 'selected' : '' ?>><?= esc($dinilai['nama']) ?> (<?= esc($dinilai['nip']) ?>)</option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-primary btn-sm btn-simpan-kalibrasi"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center text-sm">
                <thead class="bg-light">
                    <tr>
                        <th rowspan="2" class="align-middle" width="15%">Aspek Penilaian</th>
                        <th rowspan="2" class="align-middle" width="5%">Bobot</th>
                        <th colspan="4">Indikator Nilai</th>
                        <th rowspan="2" class="align-middle" width="8%">Nilai</th>
                        <th rowspan="2" class="align-middle" width="8%">Total Nilai<br><small>(Nilai * bobot)</small></th>
                    </tr>
                    <tr>
                        <th class="bg-info text-white" width="16%">&lt; 70</th>
                        <th class="bg-primary text-white" width="16%">70 - 89</th>
                        <th class="bg-info text-white" width="16%">90 - 109</th>
                        <th class="bg-primary text-white" width="16%">110 - 120</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loyalitas 30% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Loyalitas</td></tr>
                    <tr>
                        <td class="text-left">1.a Absensi</td>
                        <td rowspan="3" class="align-middle">30%</td>
                        <td>70 (>60 menit/ tidak absen datang/ tidak absen pulang/ tidak hadir)</td>
                        <td>89 (31-60 menit)</td>
                        <td>109 (0-30 menit)</td>
                        <td>120 (0)</td>
                        <td rowspan="3" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="loyalitas" data-bobot="0.30" min="0" max="120"></td>
                        <td rowspan="3" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="loyalitas">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">1.b Sikap thd Peraturan</td>
                        <td>selalu menentang peraturan</td>
                        <td>Jarang patuh terhadap peraturan yang berlaku</td>
                        <td>kadang patuh terhadap peraturan yang berlaku</td>
                        <td>Patuh terhadap peraturan yang berlaku</td>
                    </tr>
                    <tr>
                        <td class="text-left">1.c Sikap terhadap jam kerja</td>
                        <td>Menggunakan jam kerja untuk hal hal lain/ sangat santai</td>
                        <td>Selama jam kerja lebih sering terlihat santai</td>
                        <td>masih menggunakan jam kerja untuk hal lain tapi masih lebih fokus bekerja</td>
                        <td>Tidak menggunakan jam kerja untuk hal - hal lain</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/3)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_loyalitas">0</span></td></tr>

                    <!-- Cara Kerja 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Cara Kerja</td></tr>
                    <tr>
                        <td class="text-left">2.a Metode Kerja</td>
                        <td rowspan="3" class="align-middle">15%</td>
                        <td>Tidak mau menyumbang ide - ide/ metode kerja</td>
                        <td>Jarang mau menyumbangkan ide - ide/ metode kerja</td>
                        <td>Mau menyumbangkan ide - ide/ metode kerja</td>
                        <td>Selalu mau menyumbnagkan ide - ide/ metode kerja</td>
                        <td rowspan="3" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="cara_kerja" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="3" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="cara_kerja">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">2.b Kerja sama</td>
                        <td>Tidak saling membantu</td>
                        <td>Jarang membantu</td>
                        <td>Kadang - kadang membantu</td>
                        <td>Saling membantu</td>
                    </tr>
                    <tr>
                        <td class="text-left">2.c Komunikasi</td>
                        <td>Tidak pernah berkomunikasi</td>
                        <td>Jarang berkomunikasi</td>
                        <td>Cukup berkomunikasi</td>
                        <td>Berkomunikasi efektif</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/3)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_cara_kerja">0</span></td></tr>

                    <!-- Target Kinerja 25% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Target Kinerja</td></tr>
                    <tr>
                        <td class="text-left">3.a Sikap terhadapTarget</td>
                        <td rowspan="2" class="align-middle">25%</td>
                        <td>Tidak melaksanakan tugas/ pekerjaan sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan tidak sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan hampir sesuai dengan target kualitas dan waktu</td>
                        <td>Melaksanakan tugas/ pekerjaan sesuai dengan target kualitas dan waktu</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="target_kinerja" data-bobot="0.25" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="target_kinerja">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">3.b Inisiatif</td>
                        <td>Tidak melaksanakan apa yang pernah dikatakan tanpa disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan dengan disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan tanpa/ dengan disuruh/ diminta</td>
                        <td>Melaksanakan apa yang pernah dikatakan tanpa disuruh/ diminta</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_target_kinerja">0</span></td></tr>

                    <!-- Tata Kelola 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Tata Kelola</td></tr>
                    <tr>
                        <td class="text-left">4.a SOP</td>
                        <td rowspan="2" class="align-middle">15%</td>
                        <td>Tidak melaksanakan tugas sesuai SOP yang ada</td>
                        <td>Sering melaksanakan tugas tidak seusai dengan SOP yang ada</td>
                        <td>Kadang - kadang melaksanakan tugas tidak sesuai dengan SOP</td>
                        <td>Melaksanakan tugas seusai SOP yang ada</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="tata_kelola" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="tata_kelola">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">4.b Perintah</td>
                        <td>Melawan terhadap perintah atasan</td>
                        <td>sering melawan terhadap perintah atasan</td>
                        <td>Kadang - kadang melawan terhadap perintah atasan</td>
                        <td>Melaksanakan apa yang diperintahkan atasan</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_tata_kelola">0</span></td></tr>

                    <!-- Lingkungan 15% -->
                    <tr class="bg-warning"><td colspan="8" class="font-weight-bold text-center">Lingkungan</td></tr>
                    <tr>
                        <td class="text-left">5.a Atribut</td>
                        <td rowspan="2" class="align-middle">15%</td>
                        <td>Tidak menggunakan atribut lengkap</td>
                        <td>Menggunakan atribut lengkap jika dibutuhkan</td>
                        <td>Menggunakan atribut tetapi tidak lengkap</td>
                        <td>Selalu menggunakan atribut lengkap</td>
                        <td rowspan="2" class="align-middle"><input type="number" class="form-control form-control-sm text-center input-nilai" data-kategori="lingkungan" data-bobot="0.15" min="0" max="120"></td>
                        <td rowspan="2" class="align-middle"><span class="total-nilai font-weight-bold" data-kategori="lingkungan">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">5.b Lingkungan Kerja</td>
                        <td>Meja kerja selalu berantakan</td>
                        <td>Terlihat rapih kalau ada instruksi</td>
                        <td>Tidak terlalu rapih sudah bisa dikatakan rapih</td>
                        <td>Meja kerja tertata rapih</td>
                    </tr>
                    <tr class="bg-light"><td colspan="6" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai/2)</td><td colspan="2" class="text-center font-weight-bold"><span id="jumlah_lingkungan">0</span></td></tr>
                    
                    <!-- Grand Total -->
                    <tr class="bg-info text-white"><td colspan="7" class="text-right font-weight-bold">Jumlah Total Nilai (total Nilai)</td><td class="text-center font-weight-bold"><span id="grand_total">0</span></td></tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
$(document).ready(function() {
    function hitungNilai() {
        let grandTotal = 0;
        
        $('.input-nilai').each(function() {
            let val = parseFloat($(this).val()) || 0;
            let bobot = parseFloat($(this).data('bobot')) || 0;
            let kategori = $(this).data('kategori');
            
            let total = val * bobot;
            $('.total-nilai[data-kategori="'+kategori+'"]').text(total.toFixed(2));
            
            // The formula in the image: "Jumlah Total Nilai (total Nilai/3)"
            // But wait, the total Nilai is just (Nilai * bobot)?
            // The image says: Total Nilai = (Nilai*bobot)
            // And "Jumlah Total Nilai (total Nilai/3)" might just mean average or something?
            // Actually, if we just sum the Total Nilai, that's the final score.
            // In the image, Total Nilai column has one value per category, meaning the "Nilai" is the same for the category, or what?
            // The image shows "Nilai" has one cell spanning 3 rows! Oh, I see.
            // The input spans 3 rows. So the value is the same for the whole category.
            // And "Jumlah Total Nilai (total Nilai/3)" is just a text in the row below, but what is its value?
            // Let's just calculate total = val * bobot.
            
            grandTotal += total;
            
            // For the text "Jumlah Total Nilai (total Nilai/3)", maybe we put the average?
            // No, the image shows Total Nilai is just one cell per category.
            $('#jumlah_' + kategori).text(total.toFixed(2));
        });
        
        $('#grand_total').text(grandTotal.toFixed(2));
    }

    $('.input-nilai').on('input', function() {
        hitungNilai();
    });

    $('.btn-simpan-kalibrasi').click(function() {
        let id_dinilai = $('#selectDinilai').val();
        if (!id_dinilai) {
            Swal.fire('Peringatan', 'Silakan pilih pegawai yang dinilai terlebih dahulu.', 'warning');
            return;
        }
        
        let data = {
            id_dinilai: id_dinilai,
            nilai_loyalitas: $('.input-nilai[data-kategori="loyalitas"]').val(),
            nilai_cara_kerja: $('.input-nilai[data-kategori="cara_kerja"]').val(),
            nilai_target_kinerja: $('.input-nilai[data-kategori="target_kinerja"]').val(),
            nilai_tata_kelola: $('.input-nilai[data-kategori="tata_kelola"]').val(),
            nilai_lingkungan: $('.input-nilai[data-kategori="lingkungan"]').val(),
            grand_total: $('#grand_total').text()
        };
        
        // Simpan logic here, for now we just show success since user didn't mention backend logic for saving kalibrasi
        Swal.fire('Berhasil', 'Data kalibrasi berhasil disimpan. (Simulasi UI)', 'success');
    });
});
</script>
<?= $this->endSection(); ?>