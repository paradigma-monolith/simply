<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="text-primary"><i class="fas fa-cogs"></i> Pengaturan E-KIP</h2>
            <p class="text-muted">Konfigurasi modul Evaluasi Kinerja Pegawai.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <!-- Manajemen PIC Tim Kerja (360) -->
            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title text-info font-weight-bold"><i class="fas fa-user-check"></i> Pengaturan PIC Tim Kerja</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm mb-3">Tentukan PIC untuk setiap Tim Kerja agar bisa melihat seluruh hasil Penilaian 360 dari anggota timnya.</p>
                    <form id="formPicTim">
                        <div class="form-group">
                            <label>Pilih Tim Kerja</label>
                            <select name="id_tim_kerja" class="form-control select2 border-info" style="width: 100%;">
                                <option value="">-- Pilih Tim Kerja --</option>
                                <?php foreach($timKerja as $tk): ?>
                                    <option value="<?= $tk['id_tim_kerja'] ?>"><?= esc($tk['nama_tim_kerja']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Pegawai (PIC)</label>
                            <select name="id_user" class="form-control select2 border-info" style="width: 100%;">
                                <option value="">-- Pilih PIC --</option>
                                <?php foreach($users as $u): ?>
                                    <option value="<?= $u['id_user'] ?>"><?= esc($u['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" id="btnTambahPic" class="btn btn-info text-white"><i class="fas fa-plus"></i> Tambah PIC Tim</button>
                    </form>

                    <hr>
                    <h6 class="font-weight-bold mt-4">Daftar PIC Tim Kerja Saat Ini</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-light text-info">
                                <tr>
                                    <th>Tim Kerja</th>
                                    <th>Nama PIC</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($picTimKerja)): ?>
                                    <tr><td colspan="3" class="text-center">Belum ada PIC Tim Kerja.</td></tr>
                                <?php else: ?>
                                    <?php foreach($picTimKerja as $pic): ?>
                                    <tr>
                                        <td><?= esc($pic['nama_tim_kerja']) ?></td>
                                        <td><?= esc($pic['nama_pic']) ?></td>
                                        <td class="text-center"><button class="btn btn-xs btn-danger btn-hapus-pic" data-id="<?= $pic['id'] ?>"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan Lainnya -->
        <div class="col-lg-6">

            <!-- Manajemen Super Admin -->
            <div class="card card-outline card-warning shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title text-warning font-weight-bold"><i class="fas fa-user-shield"></i> Manajemen Super Admin</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm mb-3">Tetapkan user yang dapat melihat tab Super Admin di menu Hasil Penilaian.</p>
                    <form id="formSuperAdmin">
                        <div class="form-group">
                            <label>Pilih Pegawai (Super Admin)</label>
                            <select name="id_user" class="form-control select2 border-warning" style="width: 100%;">
                                <option value="">-- Pilih Pegawai --</option>
                                <?php foreach($users as $u): ?>
                                    <option value="<?= $u['id_user'] ?>"><?= esc($u['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" id="btnTambahSuperAdmin" class="btn btn-warning text-dark"><i class="fas fa-plus"></i> Tambah Super Admin</button>
                    </form>

                    <hr>
                    <h6 class="font-weight-bold mt-4">Daftar Super Admin Saat Ini</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-light text-warning text-dark">
                                <tr>
                                    <th>Nama Pegawai</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($superAdmins)): ?>
                                    <tr><td colspan="2" class="text-center text-muted">Belum ada Super Admin.</td></tr>
                                <?php else: ?>
                                    <?php foreach($superAdmins as $sa): ?>
                                    <tr>
                                        <td class="align-middle font-weight-bold"><?= esc($sa['nama_admin']) ?></td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-xs btn-outline-danger btn-hapus-super-admin" data-id="<?= $sa['id'] ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Baris Baru untuk Pengaturan Global & Jadwal -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title text-success font-weight-bold"><i class="fas fa-calendar-alt"></i> Pengaturan Global & Jadwal Input</h3>
                </div>
                <div class="card-body">
                    <form id="formPengaturanGlobal">
                        <h6 class="font-weight-bold text-success mb-3">Aturan Jadwal Batas Waktu (Deadline) Input Pegawai</h6>
                        <p class="text-sm text-muted">Secara default, pegawai hanya bisa menginput evaluasi dari hari Senin sampai hari Jumat pukul 16:00. Di luar jadwal ini, tombol pengisian akan dikunci agar atasan bisa mereview hasilnya di hari Senin berikutnya. Anda bisa mengubah jadwal ini di sini.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hari Buka Input</label>
                                    <select name="jadwal_buka_hari" class="form-control border-success">
                                        <option value="1" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 1) ? 'selected' : '' ?>>Senin</option>
                                        <option value="2" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 2) ? 'selected' : '' ?>>Selasa</option>
                                        <option value="3" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 3) ? 'selected' : '' ?>>Rabu</option>
                                        <option value="4" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 4) ? 'selected' : '' ?>>Kamis</option>
                                        <option value="5" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 5) ? 'selected' : '' ?>>Jumat</option>
                                        <option value="6" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 6) ? 'selected' : '' ?>>Sabtu</option>
                                        <option value="7" <?= (isset($pengaturan['jadwal_buka_hari']) && $pengaturan['jadwal_buka_hari'] == 7) ? 'selected' : '' ?>>Minggu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hari Tutup Input (Deadline)</label>
                                    <select name="jadwal_tutup_hari" class="form-control border-success">
                                        <option value="1" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 1) ? 'selected' : '' ?>>Senin</option>
                                        <option value="2" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 2) ? 'selected' : '' ?>>Selasa</option>
                                        <option value="3" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 3) ? 'selected' : '' ?>>Rabu</option>
                                        <option value="4" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 4) ? 'selected' : '' ?>>Kamis</option>
                                        <option value="5" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 5) ? 'selected' : '' ?>>Jumat</option>
                                        <option value="6" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 6) ? 'selected' : '' ?>>Sabtu</option>
                                        <option value="7" <?= (isset($pengaturan['jadwal_tutup_hari']) && $pengaturan['jadwal_tutup_hari'] == 7) ? 'selected' : '' ?>>Minggu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Tutup Input (Deadline)</label>
                                    <input type="time" class="form-control border-success" name="jadwal_tutup_jam" value="<?= esc($pengaturan['jadwal_tutup_jam'] ?? '16:00') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="switchAksesPegawai" name="akses_pegawai" value="1" <?= (isset($pengaturan['akses_pegawai']) && $pengaturan['akses_pegawai'] == 1) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="switchAksesPegawai">Izinkan Pegawai Input Evaluasi di Luar Jadwal (Bypass)</label>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-success mb-3 mt-4"><i class="fas fa-users"></i> Aturan Aktif Penilaian 360 per Triwulan</h6>
                        <p class="text-sm text-muted">Aktifkan atau nonaktifkan pengisian Penilaian 360 untuk setiap Triwulan. Jika dinonaktifkan, pegawai tidak bisa mengisi form Penilaian 360 pada Triwulan tersebut.</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchT360Q1" name="t360_q1" value="1" <?= (isset($pengaturan['t360_q1']) && $pengaturan['t360_q1'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchT360Q1">TRIWULAN I</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchT360Q2" name="t360_q2" value="1" <?= (isset($pengaturan['t360_q2']) && $pengaturan['t360_q2'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchT360Q2">TRIWULAN II</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchT360Q3" name="t360_q3" value="1" <?= (isset($pengaturan['t360_q3']) && $pengaturan['t360_q3'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchT360Q3">TRIWULAN III</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchT360Q4" name="t360_q4" value="1" <?= (isset($pengaturan['t360_q4']) && $pengaturan['t360_q4'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchT360Q4">TRIWULAN IV</label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-success mb-3 mt-4"><i class="fas fa-toggle-on"></i> Aturan Aktif Tab Penilaian</h6>
                        <p class="text-sm text-muted">Aktifkan atau nonaktifkan Tab Penilaian Kinerja, Perilaku, dan 360.</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchTabKinerja" name="tab_kinerja_aktif" value="1" <?= (!isset($pengaturan['tab_kinerja_aktif']) || $pengaturan['tab_kinerja_aktif'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchTabKinerja">Tab Penilaian Kinerja</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchTabPerilaku" name="tab_perilaku_aktif" value="1" <?= (!isset($pengaturan['tab_perilaku_aktif']) || $pengaturan['tab_perilaku_aktif'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchTabPerilaku">Tab Penilaian Perilaku</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switchTab360" name="tab_360_aktif" value="1" <?= (!isset($pengaturan['tab_360_aktif']) || $pengaturan['tab_360_aktif'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="switchTab360">Tab Penilaian 360</label>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="btnSimpanPengaturan" class="btn btn-success mt-3"><i class="fas fa-save"></i> Simpan Pengaturan Global</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .card-outline.card-success { border-top: 3px solid #28a745; }
</style>
<script>
$(document).ready(function() {
    $('#btnTambahSuperAdmin').click(function() {
        var formData = $('#formSuperAdmin').serialize();
        $.ajax({
            url: '/ekip/super_admin/tambah',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('.btn-hapus-super-admin').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Super Admin?',
            text: "User ini tidak akan bisa lagi melihat tab Super Admin.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/super_admin/hapus/' + id,
                    type: 'POST',
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire('Terhapus!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });

    $('#btnTambahPic').click(function() {
        var formData = $('#formPicTim').serialize();
        $.ajax({
            url: '/ekip/pic_tim/tambah',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('#btnSimpanPengaturan').click(function() {
        var formData = $('#formPengaturanGlobal').serialize();
        $.ajax({
            url: '/ekip/pengaturan/simpan',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.status == 'success') {
                    Swal.fire('Berhasil!', response.message, 'success');
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }
        });
    });

    $('.btn-hapus-pic').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "PIC Tim Kerja ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ekip/pic_tim/hapus/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire('Terhapus!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
