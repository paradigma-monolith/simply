<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-primary"><i class="fas fa-clipboard-check"></i> Penilaian Kinerja, Perilaku & 360</h2>
            <p class="text-muted">Kelola data evaluasi kinerja, perilaku, dan penilaian 360 Anda.</p>
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
                <a class="nav-link active" href="/ekip/penilaian_360"><i class="fas fa-users"></i> Penilaian 360</a>
            <?php else: ?>
                <a class="nav-link active disabled" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-users"></i> Penilaian 360 (Dinonaktifkan)</a>
            <?php endif; ?>
        </li>
        <?php if (isset($isPenilai) && $isPenilai): ?>
        <li class="nav-item">
            <a class="nav-link" href="/ekip/kalibrasi"><i class="fas fa-balance-scale"></i> Kalibrasi</a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-info collapsed-card mb-0">
                <div class="card-header" id="pedomanHeader" role="button" tabindex="0">
                    <h3 class="card-title text-info"><i class="fas fa-info-circle"></i> Pedoman Pengisian</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        Agar hasil penilaian ini akurat, valid, dan dapat dipertanggungjawabkan, mohon perhatikan pedoman pengisian berikut (Tahun <?= $tahun_aktif ?>, <span id="petunjukBulan">Bulan <?= $bulan_aktif ?></span>, <span id="petunjukMinggu">Minggu ke-<?= $minggu_aktif ?></span>):
                    </p>
                    <ol class="pl-3 mb-0">
                        <li class="mb-2">
                            <strong>Jujur dan Objektif:</strong>
                            Berikan penilaian berdasarkan fakta, pengamatan, dan interaksi kerja nyata.
                            Kesampingkan bias personal dan gunakan panduan indikator nilai (skala 1–5) yang telah disediakan di dalam form sebagai acuan.
                        </li>
                        <li>
                            <strong>Wajib Menggunakan Komputer/Laptop:</strong>
                            Pengisian formulir ini wajib dilakukan melalui perangkat komputer atau laptop. Mohon tidak mengisi menggunakan smartphone (HP) agar tampilan tabel/matriks penilaian terlihat utuh dan untuk meminimalisir kesalahan input (salah klik).
                        </li>
                    </ol>
                    <div class="mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 text-sm bg-white">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th>Penilaian</th>
                                        <th width="70">Nilai</th>
                                        <th>Indikator</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sangat Baik</td>
                                        <td class="text-center font-weight-bold">5</td>
                                        <td>Melampaui ekspektasi secara konsisten.</td>
                                    </tr>
                                    <tr>
                                        <td>Baik</td>
                                        <td class="text-center font-weight-bold">4</td>
                                        <td>Sesuai dengan ekspektasi dan target yang ditetapkan.</td>
                                    </tr>
                                    <tr>
                                        <td>Butuh Perbaikan</td>
                                        <td class="text-center font-weight-bold">3</td>
                                        <td>Memenuhi standar minimal namun perlu peningkatan.</td>
                                    </tr>
                                    <tr>
                                        <td>Kurang</td>
                                        <td class="text-center font-weight-bold">2</td>
                                        <td>Sering tidak mencapai standar/target.</td>
                                    </tr>
                                    <tr>
                                        <td>Sangat Kurang</td>
                                        <td class="text-center font-weight-bold">1</td>
                                        <td>Tidak menunjukkan kemauan/kemampuan mencapai standar.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title text-primary font-weight-bold">Daftar Rekan Satu Tim Kerja</h3>
            <div class="card-tools ml-auto d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger mr-2" id="btnKosongkanSemua" title="Kosongkan semua nilai untuk seluruh rekan tim">
                    <i class="fas fa-eraser"></i> Kosongkan Semua
                </button>
                <button type="button" class="btn btn-sm btn-outline-success mr-2" id="btnIsiOtomatis" title="Isi otomatis nilai 4 (Baik) untuk semua indikator ke seluruh rekan tim">
                    <i class="fas fa-magic"></i> Isi Semua Nilai (4)
                </button>
                <select class="form-control form-control-sm border-primary mr-2" id="filterTahun" style="width: 100px;">
                    <?php for($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= $tahun_aktif == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <select class="form-control form-control-sm border-primary mr-2" id="filterBulan" style="width: 120px;">
                    <?php 
                    $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                    foreach($bulanIndo as $num => $namaBulan): ?>
                        <option value="<?= $num ?>" <?= $bulan_aktif == $num ? 'selected' : '' ?>><?= $namaBulan ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control form-control-sm border-primary" id="filterMinggu" style="width: 250px;">
                    <?php foreach($list_minggu as $num => $minggu): ?>
                        <option value="<?= $num ?>" <?= $minggu_aktif == $num ? 'selected' : '' ?> data-aktif="<?= true ?>">Minggu <?= $num ?> (<?= $minggu['label'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if(empty($temanTim)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users-slash fa-4x mb-3 text-secondary opacity-50"></i>
                <h5>Tidak ada rekan di tim kerja yang sama.</h5>
                <p>Silakan hubungi administrator jika ini adalah sebuah kesalahan.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 text-sm table-360">
                    <thead class="bg-light text-center align-middle text-primary">
                        <tr>
                            <th rowspan="2" width="3%">No.</th>
                            <th rowspan="2" width="10%">NIP</th>
                            <th rowspan="2" width="15%">NAMA</th>
                            <th rowspan="2" width="10%">MINGGU</th>
                            <th colspan="4">Aspek Penugasan</th>
                            <th colspan="3">Aspek Kompetensi dan Perilaku</th>
                            <th rowspan="2" width="5%">Rata-rata</th>
                            <th rowspan="2" width="5%">Peringkat</th>
                            <th rowspan="2" width="8%">Justifikasi</th>
                        </tr>
                        <tr>
                            <th width="7%">Hasil Pekerjaan</th>
                            <th width="7%">Kualitas Pekerjaan</th>
                            <th width="7%">Ketepatan Waktu</th>
                            <th width="7%">Efektivitas & Efesiensi</th>
                            <th width="7%">Tanggung Jawab</th>
                            <th width="7%">Kerjasama</th>
                            <th width="7%">Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($temanTim as $t): ?>
                        <tr data-id="<?= $t['id_user'] ?>">
                            <td class="text-center align-middle"><?= $no++ ?></td>
                            <td class="align-middle"><?= esc($t['nip']) ?></td>
                            <td class="align-middle font-weight-bold"><?= esc($t['nama_user']) ?></td>
                            <td class="align-middle text-center minggu-label">Minggu ke-<?= $minggu_aktif ?></td>
                            
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="hasil_pekerjaan" min="1" max="5"></td>
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="kualitas_pekerjaan" min="1" max="5"></td>
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="ketepatan_waktu" min="1" max="5"></td>
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="efektivitas_efesiensi" min="1" max="5"></td>
                            
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="tanggung_jawab" min="1" max="5"></td>
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="kerjasama" min="1" max="5"></td>
                            <td class="p-1"><input type="number" class="form-control form-control-sm text-center input-nilai" name="presensi" min="1" max="5"></td>
                            
                            <td class="align-middle text-center font-weight-bold jumlah-nilai">0</td>
                            <td class="align-middle text-center font-weight-bold peringkat-nilai">-</td>
                            <td class="align-middle text-center">
                                <button type="button" class="btn btn-sm btn-outline-info btn-justifikasi" data-id="<?= $t['id_user'] ?>" title="Tambah Justifikasi">
                                    <i class="fas fa-comment-dots"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
  </div>
</section>

<!-- Modal Justifikasi -->
<div class="modal fade" id="modalJustifikasi" tabindex="-1" role="dialog" aria-labelledby="modalJustifikasiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="formJustifikasi" enctype="multipart/form-data">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="modalJustifikasiLabel"><i class="fas fa-comment-dots"></i> Justifikasi Penilaian</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="dinilai_id" id="justifikasi_dinilai_id">
            <input type="hidden" name="minggu_ke" id="justifikasi_minggu_ke">
            <input type="hidden" name="bulan" id="justifikasi_bulan">
            <input type="hidden" name="tahun" id="justifikasi_tahun">
            
            <div class="form-group">
                <label>Deskripsi Justifikasi</label>
                <textarea class="form-control border-info" name="justifikasi_deskripsi" id="justifikasi_deskripsi" rows="4" placeholder="Tuliskan alasan atau deskripsi tambahan mengenai penilaian pegawai ini..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Upload File Bukti (Opsional)</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="justifikasi_file" name="justifikasi_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <label class="custom-file-label" for="justifikasi_file">Pilih file...</label>
                </div>
                <small class="form-text text-muted">Format yang diizinkan: PDF, Word, JPG, PNG. Maksimal 2MB.</small>
                <div id="file_sekarang_container" class="mt-2" style="display:none;">
                    <span class="text-sm text-muted">File saat ini: </span>
                    <a href="#" id="link_file_sekarang" target="_blank" class="badge badge-info"><i class="fas fa-file-download"></i> Lihat File</a>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-danger" id="btnHapusJustifikasi"><i class="fas fa-eraser"></i> Kosongkan</button>
          <div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
            <button type="submit" class="btn btn-info" id="btnSimpanJustifikasi"><i class="fas fa-save"></i> Simpan Justifikasi</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Data for initialization -->
<script>
    var dataPenilaian = <?= json_encode($dataPenilaian) ?>;
</script>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .table-360 th { vertical-align: middle !important; }
    .input-nilai {
        border: 1px solid #ced4da;
        background-color: #fdfdfd;
    }
    .input-nilai:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    /* Hide number input arrows */
    .input-nilai::-webkit-outer-spin-button,
    .input-nilai::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .input-nilai[type=number] {
      -moz-appearance: textfield;
    }
    #pedomanHeader { cursor: pointer; }
</style>

<script>
$(document).ready(function() {

    $('#pedomanHeader').on('click', function(e) {
        if ($(e.target).closest('[data-card-widget="collapse"]').length) return;
        $(this).find('[data-card-widget="collapse"]').trigger('click');
    });

    $('#pedomanHeader').on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).find('[data-card-widget="collapse"]').trigger('click');
        }
    });
    
    function loadData() {
        var minggu = $('#filterMinggu').val();
        var isAktif = $('#filterMinggu option:selected').data('aktif');
        var labelMinggu = 'Minggu ke-' + minggu;

        $('.minggu-label').text(labelMinggu);
        
        $('.table-360 tbody tr').each(function() {
            var id = $(this).data('id');
            var row = $(this);
            
            // Reset
            row.find('.input-nilai').val('');
            row.find('.jumlah-nilai').text('0');
            row.find('.peringkat-nilai').text('-');
            
            if (dataPenilaian[id] && dataPenilaian[id][minggu]) {
                var p = dataPenilaian[id][minggu];
                row.find('input[name="hasil_pekerjaan"]').val(p.hasil_pekerjaan);
                row.find('input[name="kualitas_pekerjaan"]').val(p.kualitas_pekerjaan);
                row.find('input[name="ketepatan_waktu"]').val(p.ketepatan_waktu);
                row.find('input[name="efektivitas_efesiensi"]').val(p.efektivitas_efesiensi);
                row.find('input[name="tanggung_jawab"]').val(p.tanggung_jawab);
                row.find('input[name="kerjasama"]').val(p.kerjasama);
                row.find('input[name="presensi"]').val(p.presensi);
                row.find('.jumlah-nilai').text(p.jumlah ? p.jumlah : '0');
                
                // Update tombol justifikasi
                var btnJustifikasi = row.find('.btn-justifikasi');
                if (p.has_justifikasi == 1 || p.justifikasi_deskripsi || p.justifikasi_file) {
                    btnJustifikasi.removeClass('btn-outline-info').addClass('btn-info font-weight-bold text-white');
                } else {
                    btnJustifikasi.removeClass('btn-info font-weight-bold text-white').addClass('btn-outline-info');
                }
            } else {
                row.find('.btn-justifikasi').removeClass('btn-info font-weight-bold text-white').addClass('btn-outline-info');
            }

            // Lock or unlock based on isAktif
            if (isAktif == 0) {
                row.find('.input-nilai').prop('disabled', true);
                row.find('.btn-justifikasi').prop('disabled', true);
                $('#btnIsiOtomatis').prop('disabled', true);
                $('#btnKosongkanSemua').prop('disabled', true);
            } else {
                row.find('.input-nilai').prop('disabled', false);
                row.find('.btn-justifikasi').prop('disabled', false);
                $('#btnIsiOtomatis').prop('disabled', false);
                $('#btnKosongkanSemua').prop('disabled', false);
            }
        });
        
        calculateRank();

        if (isAktif == 0) {
            if ($('#alert-360-ditutup').length === 0) {
                $('.table-responsive').before('<div id="alert-360-ditutup" class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Pengisian Penilaian 360 untuk ' + labelMinggu + ' sedang ditutup oleh administrator. Anda hanya dapat melihat data yang sudah ada.</div>');
            } else {
                $('#alert-360-ditutup').html('<i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Pengisian Penilaian 360 untuk ' + labelMinggu + ' sedang ditutup oleh administrator. Anda hanya dapat melihat data yang sudah ada.').show();
            }
        } else {
            $('#alert-360-ditutup').hide();
        }
    }
    
    function calculateRank() {
        var rows = [];
        $('.table-360 tbody tr').each(function() {
            var jumlah = parseFloat($(this).find('.jumlah-nilai').text()) || 0;
            rows.push({
                element: $(this),
                jumlah: jumlah
            });
        });
        
        // Sort descending
        rows.sort(function(a, b) {
            return b.jumlah - a.jumlah;
        });
        
        // Assign rank
        var rank = 1;
        var prevJumlah = -1;
        var actualRank = 1;
        
        for (var i = 0; i < rows.length; i++) {
            if (rows[i].jumlah > 0) {
                if (rows[i].jumlah !== prevJumlah) {
                    actualRank = rank;
                }
                rows[i].element.find('.peringkat-nilai').text(actualRank);
                prevJumlah = rows[i].jumlah;
                rank++;
            } else {
                rows[i].element.find('.peringkat-nilai').text('-');
            }
        }
    }

    // Initial load
    loadData();
    
    $('#filterTahun, #filterBulan, #filterMinggu').change(function() {
        var tahun = $('#filterTahun').val();
        var bulan = $('#filterBulan').val();
        var minggu = $('#filterMinggu').val();
        window.location.href = '/ekip/penilaian_360?tahun=' + tahun + '&bulan=' + bulan + '&minggu=' + minggu;
    });

    // Handle Justifikasi Button Click
    $('.btn-justifikasi').click(function() {
        var row = $(this).closest('tr');
        var dinilai_id = row.data('id');
        var minggu_ke = $('#filterMinggu').val();
        var bulan = $('#filterBulan').val();
        var tahun = $('#filterTahun').val();
        
        $('#justifikasi_dinilai_id').val(dinilai_id);
        $('#justifikasi_minggu_ke').val(minggu_ke);
        $('#justifikasi_bulan').val(bulan);
        $('#justifikasi_tahun').val(tahun);
        $('#justifikasi_deskripsi').val('');
        $('#justifikasi_file').val('');
        $('.custom-file-label').text('Pilih file...');
        $('#file_sekarang_container').hide();

        if (dataPenilaian[dinilai_id] && dataPenilaian[dinilai_id][minggu_ke]) {
            var p = dataPenilaian[dinilai_id][minggu_ke];
            if(p.justifikasi_deskripsi) {
                $('#justifikasi_deskripsi').val(p.justifikasi_deskripsi);
            }
            if(p.justifikasi_file) {
                $('#link_file_sekarang').attr('href', '/uploads/ekip_360/' + p.justifikasi_file);
                $('#file_sekarang_container').show();
            }
        }
        
        $('#modalJustifikasi').modal('show');
    });

    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Handle Delete/Clear Justifikasi
    $('#btnHapusJustifikasi').click(function() {
        var dinilai_id = $('#justifikasi_dinilai_id').val();
        var minggu_ke = $('#justifikasi_minggu_ke').val();
        var bulan = $('#justifikasi_bulan').val();
        var tahun = $('#justifikasi_tahun').val();
        
        Swal.fire({
            title: 'Kosongkan Justifikasi?',
            text: "Deskripsi dan file bukti akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/penilaian_360/hapus_justifikasi',
                    type: 'DELETE',
                    data: {
                        dinilai_id: dinilai_id,
                        minggu_ke: minggu_ke,
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(res) {
                        if(res.status == 'success') {
                            Swal.fire('Dikosongkan!', res.message, 'success');
                            $('#modalJustifikasi').modal('hide');
                            
                            // Update local dataPenilaian object
                            var minggu_ke = $('#justifikasi_minggu_ke').val();
                            if(dataPenilaian[dinilai_id] && dataPenilaian[dinilai_id][minggu_ke]) {
                                dataPenilaian[dinilai_id][minggu_ke].justifikasi_deskripsi = null;
                                dataPenilaian[dinilai_id][minggu_ke].justifikasi_file = null;
                                dataPenilaian[dinilai_id][minggu_ke].has_justifikasi = 0;
                            }
                            
                            // Update UI Button
                            var row = $('tr[data-id="'+dinilai_id+'"]');
                            row.find('.btn-justifikasi').removeClass('btn-info font-weight-bold text-white').addClass('btn-outline-info');
                            
                            // Flash row
                            row.find('td').css('background-color', '#f8d7da');
                            setTimeout(function() {
                                row.find('td').css('background-color', '');
                            }, 500);
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
                    }
                });
            }
        });
    });

    // Handle Form Justifikasi Submit
    $('#formJustifikasi').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var dinilai_id = $('#justifikasi_dinilai_id').val();
        var minggu_ke = $('#justifikasi_minggu_ke').val();
        
        $('#btnSimpanJustifikasi').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '/ekip/penilaian_360/simpan_justifikasi',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#btnSimpanJustifikasi').html('<i class="fas fa-save"></i> Simpan Justifikasi').prop('disabled', false);
                if(res.status == 'success') {
                    Swal.fire('Berhasil!', res.message, 'success');
                    $('#modalJustifikasi').modal('hide');
                    
                    // Update local dataPenilaian object
                    var minggu_ke = $('#justifikasi_minggu_ke').val();
                    if(!dataPenilaian[dinilai_id]) dataPenilaian[dinilai_id] = {};
                    if(!dataPenilaian[dinilai_id][minggu_ke]) dataPenilaian[dinilai_id][minggu_ke] = {};
                    
                    dataPenilaian[dinilai_id][minggu_ke].justifikasi_deskripsi = $('#justifikasi_deskripsi').val();
                    if(res.file_name) {
                        dataPenilaian[dinilai_id][minggu_ke].justifikasi_file = res.file_name;
                    }
                    dataPenilaian[dinilai_id][minggu_ke].has_justifikasi = 1;
                    
                    // Flash row
                    var row = $('tr[data-id="'+dinilai_id+'"]');
                    row.find('td').css('background-color', '#d4edda');
                    row.find('.btn-justifikasi').removeClass('btn-outline-info').addClass('btn-info font-weight-bold text-white');
                    setTimeout(function() {
                        row.find('td').css('background-color', '');
                    }, 500);
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function() {
                $('#btnSimpanJustifikasi').html('<i class="fas fa-save"></i> Simpan Justifikasi').prop('disabled', false);
                Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
            }
        });
    });

    // Auto calculate & save on change
    var typingTimer;
    var doneTypingInterval = 1000; // 1 second

    // Handle Isi Otomatis Button
    $('#btnIsiOtomatis').click(function() {
        var isAktif = $('#filterMinggu option:selected').data('aktif');
        if(isAktif == 0) return;

        Swal.fire({
            title: 'Isi Otomatis Nilai 4?',
            text: "Aksi ini akan mengisi semua indikator penilaian dengan angka 4 (Baik) untuk seluruh rekan tim kerja dan menyimpannya secara otomatis.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Isi Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnIsiOtomatis').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
                
                // Isi input dengan nilai 4
                $('.table-360 tbody tr').each(function() {
                    $(this).find('.input-nilai').val(4);
                    // Rata-rata dari (7 x 4) / 7 = 4.00
                    $(this).find('.jumlah-nilai').text('4.00');
                });
                
                calculateRank();

                // Kumpulkan semua data untuk disimpan bulk
                var minggu_ke = $('#filterMinggu').val();
                var bulan = $('#filterBulan').val();
                var tahun = $('#filterTahun').val();
                var bulkData = [];
                
                $('.table-360 tbody tr').each(function() {
                    var dinilai_id = $(this).data('id');
                    var data = {
                        dinilai_id: dinilai_id,
                        minggu_ke: minggu_ke,
                        bulan: bulan,
                        tahun: tahun,
                        hasil_pekerjaan: 4,
                        kualitas_pekerjaan: 4,
                        ketepatan_waktu: 4,
                        efektivitas_efesiensi: 4,
                        tanggung_jawab: 4,
                        kerjasama: 4,
                        presensi: 4,
                        jumlah: 4.00
                    };
                    
                    bulkData.push(data);
                    
                    // Simpan ke memory lokal
                    if(!dataPenilaian[dinilai_id]) dataPenilaian[dinilai_id] = {};
                    dataPenilaian[dinilai_id][minggu_ke] = $.extend({}, dataPenilaian[dinilai_id][minggu_ke] || {}, data);
                });

                // Kirim request ke server untuk simpan bulk (menggunakan loop AJAX karena belum ada endpoint bulk save khusus)
                var requests = [];
                $.each(bulkData, function(i, data) {
                    requests.push(
                        $.ajax({
                            url: '/ekip/penilaian_360/simpan',
                            type: 'POST',
                            data: data
                        })
                    );
                });

                $.when.apply($, requests).done(function() {
                    $('#btnIsiOtomatis').html('<i class="fas fa-magic"></i> Isi Semua Nilai (4)').prop('disabled', false);
                    Swal.fire('Berhasil!', 'Semua nilai berhasil diisi dan disimpan otomatis.', 'success');
                    
                    // Flash semua row
                    $('.table-360 tbody tr td').css('background-color', '#d4edda');
                    setTimeout(function() {
                        $('.table-360 tbody tr td').css('background-color', '');
                    }, 800);
                }).fail(function() {
                    $('#btnIsiOtomatis').html('<i class="fas fa-magic"></i> Isi Semua Nilai (4)').prop('disabled', false);
                    Swal.fire('Peringatan', 'Beberapa data mungkin gagal disimpan. Silakan muat ulang halaman.', 'warning');
                });
            }
        });
    });

    // Handle Kosongkan Semua Button
    $('#btnKosongkanSemua').click(function() {
        var isAktif = $('#filterMinggu option:selected').data('aktif');
        if(isAktif == 0) return;

        Swal.fire({
            title: 'Kosongkan Semua Nilai?',
            text: "Aksi ini akan menghapus semua nilai indikator yang telah diisi untuk seluruh rekan tim kerja.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnKosongkanSemua').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
                
                // Kosongkan input
                $('.table-360 tbody tr').each(function() {
                    $(this).find('.input-nilai').val('');
                    $(this).find('.jumlah-nilai').text('0');
                });
                
                calculateRank();

                // Kumpulkan semua data untuk disimpan bulk
                var minggu_ke = $('#filterMinggu').val();
                var bulan = $('#filterBulan').val();
                var tahun = $('#filterTahun').val();
                var bulkData = [];
                
                $('.table-360 tbody tr').each(function() {
                    var dinilai_id = $(this).data('id');
                    var data = {
                        dinilai_id: dinilai_id,
                        minggu_ke: minggu_ke,
                        bulan: bulan,
                        tahun: tahun,
                        hasil_pekerjaan: '',
                        kualitas_pekerjaan: '',
                        ketepatan_waktu: '',
                        efektivitas_efesiensi: '',
                        tanggung_jawab: '',
                        kerjasama: '',
                        presensi: '',
                        jumlah: ''
                    };
                    
                    bulkData.push(data);
                    
                    // Simpan ke memory lokal
                    if(!dataPenilaian[dinilai_id]) dataPenilaian[dinilai_id] = {};
                    dataPenilaian[dinilai_id][minggu_ke] = $.extend({}, dataPenilaian[dinilai_id][minggu_ke] || {}, data);
                });

                // Kirim request ke server untuk simpan bulk
                var requests = [];
                $.each(bulkData, function(i, data) {
                    requests.push(
                        $.ajax({
                            url: '/ekip/penilaian_360/simpan',
                            type: 'POST',
                            data: data
                        })
                    );
                });

                $.when.apply($, requests).done(function() {
                    $('#btnKosongkanSemua').html('<i class="fas fa-eraser"></i> Kosongkan Semua').prop('disabled', false);
                    Swal.fire('Berhasil!', 'Semua nilai berhasil dikosongkan.', 'success');
                    
                    // Flash semua row
                    $('.table-360 tbody tr td').css('background-color', '#f8d7da');
                    setTimeout(function() {
                        $('.table-360 tbody tr td').css('background-color', '');
                    }, 800);
                }).fail(function() {
                    $('#btnKosongkanSemua').html('<i class="fas fa-eraser"></i> Kosongkan Semua').prop('disabled', false);
                    Swal.fire('Peringatan', 'Beberapa data mungkin gagal dikosongkan. Silakan muat ulang halaman.', 'warning');
                });
            }
        });
    });

    $('.input-nilai').on('keyup change', function() {
        var input = $(this);
        var val = parseInt(input.val());
        
        // Validasi input agar tidak lebih dari 5 atau kurang dari 1
        if (val > 5) input.val(5);
        if (val < 1) input.val(1);
        
        clearTimeout(typingTimer);
        var row = input.closest('tr');
        var dinilai_id = row.data('id');
        var minggu_ke = $('#filterMinggu').val();
        var bulan = $('#filterBulan').val();
        var tahun = $('#filterTahun').val();
        
        // Calculate average instead of sum
        var sum = 0;
        var count = 0;
        row.find('.input-nilai').each(function() {
            var val = parseInt($(this).val());
            if(!isNaN(val)) {
                sum += val;
                count++;
            }
        });
        
        var avg = count > 0 ? (sum / count).toFixed(2) : 0;
        row.find('.jumlah-nilai').text(avg);
        
        calculateRank();

        // Save data to server after 1s of no typing
        typingTimer = setTimeout(function() {
            var data = {
                dinilai_id: dinilai_id,
                minggu_ke: minggu_ke,
                bulan: bulan,
                tahun: tahun,
                hasil_pekerjaan: row.find('input[name="hasil_pekerjaan"]').val(),
                kualitas_pekerjaan: row.find('input[name="kualitas_pekerjaan"]').val(),
                ketepatan_waktu: row.find('input[name="ketepatan_waktu"]').val(),
                efektivitas_efesiensi: row.find('input[name="efektivitas_efesiensi"]').val(),
                tanggung_jawab: row.find('input[name="tanggung_jawab"]').val(),
                kerjasama: row.find('input[name="kerjasama"]').val(),
                presensi: row.find('input[name="presensi"]').val(),
                jumlah: avg
            };
            
            // Save to local object so it persists when switching minggu_ke
            if(!dataPenilaian[dinilai_id]) dataPenilaian[dinilai_id] = {};
            dataPenilaian[dinilai_id][minggu_ke] = data;

            $.ajax({
                url: '/ekip/penilaian_360/simpan',
                type: 'POST',
                data: data,
                success: function(res) {
                    if(res.status == 'success') {
                        // Flash green to indicate saved
                        row.find('td').css('background-color', '#d4edda');
                        setTimeout(function() {
                            row.find('td').css('background-color', '');
                        }, 500);
                    }
                }
            });
        }, doneTypingInterval);
    });
});
</script>

<?= $this->endSection(); ?>
