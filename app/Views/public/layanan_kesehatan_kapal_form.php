<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 0 auto;
            max-width: 800px;
        }
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .header .logo-section {
            margin-bottom: 20px;
        }
        .header .logo-section img {
            margin: 0 8px;
            max-height: 60px;
            width: auto;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <div class="header">
                <div class="logo-section">
                    <img src="/dist/img/LOGO_KARANTINA.png" alt="Logo Karantina Kesehatan">
                    <img src="/dist/img/logo-bkk-no-margin.png" alt="Logo BKK Bandung">
                </div>
                <h3>PERMOHONAN PENGAJUAN LAYANAN KESEHATAN KAPAL</h3>
                <p class="mb-0">Balai Kekarantinaan Kesehatan Kelas I Bandung</p>
            </div>

            <div class="p-4 p-md-5">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors)) : ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/public/layanan_kesehatan_kapal/submit" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Bagian I -->
                    <h5 class="section-title">I. Informasi Kedatangan Kapal dalam Negeri</h5>
                    
                    <div class="row mb-3">
                        <label for="nama_kapal" class="col-sm-4 col-form-label">Nama Kapal</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_kapal" name="nama_kapal" value="<?= old('nama_kapal') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="waktu_kedatangan" class="col-sm-4 col-form-label">Waktu Kedatangan</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" id="waktu_kedatangan" name="waktu_kedatangan" value="<?= old('waktu_kedatangan') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="pelabuhan_asal" class="col-sm-4 col-form-label">Pelabuhan Asal</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pelabuhan_asal" name="pelabuhan_asal" value="<?= old('pelabuhan_asal') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="pelabuhan_tujuan" class="col-sm-4 col-form-label">Pelabuhan Tujuan</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="pelabuhan_tujuan" name="pelabuhan_tujuan" required>
                                <option value="">-- Pilih Pelabuhan Tujuan --</option>
                                <option value="patimban" <?= old('pelabuhan_tujuan') == 'patimban' ? 'selected' : '' ?>>Patimban</option>
                                <option value="cirebon" <?= old('pelabuhan_tujuan') == 'cirebon' ? 'selected' : '' ?>>Cirebon</option>
                                <option value="pelabuhan_ratu" <?= old('pelabuhan_tujuan') == 'pelabuhan_ratu' ? 'selected' : '' ?>>Pelabuhan Ratu</option>
                                <option value="Indramayu" <?= old('pelabuhan_tujuan') == 'Indramayu' ? 'selected' : '' ?>>Indramayu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lokasi_sandar" class="col-sm-4 col-form-label">Lokasi Sandar</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="lokasi_sandar" name="lokasi_sandar" value="<?= old('lokasi_sandar') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jarak_kapal" class="col-sm-4 col-form-label">Jarak Kapal (mil dari jetty)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" class="form-control" id="jarak_kapal" name="jarak_kapal" value="<?= old('jarak_kapal') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jumlah_crew" class="col-sm-4 col-form-label">Jumlah Crew</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="jumlah_crew" name="jumlah_crew" value="<?= old('jumlah_crew') ?>" required>
                        </div>
                    </div>

                    <!-- Bagian II -->
                    <h5 class="section-title mt-5">II. Kelengkapan Dokumen Kesehatan Kapal</h5>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dok_buku_kesehatan_kapal" name="dok_buku_kesehatan_kapal" value="1" <?= old('dok_buku_kesehatan_kapal') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dok_buku_kesehatan_kapal">Buku Kesehatan Kapal</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dok_sscec" name="dok_sscec" value="1" <?= old('dok_sscec') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dok_sscec">SSCEC/SSCC</label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text">Tanggal kadaluarsa:</span>
                                <input type="date" class="form-control" name="dok_sscec_tgl_kadaluarsa" value="<?= old('dok_sscec_tgl_kadaluarsa') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dok_p3k" name="dok_p3k" value="1" <?= old('dok_p3k') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dok_p3k">Sertifikat P3K</label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text">Tanggal kadaluarsa:</span>
                                <input type="date" class="form-control" name="dok_p3k_tgl_kadaluarsa" value="<?= old('dok_p3k_tgl_kadaluarsa') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dok_phqc" name="dok_phqc" value="1" <?= old('dok_phqc') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dok_phqc">PHQC dari Pelabuhan sebelumnya</label>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian III -->
                    <h5 class="section-title mt-5">III. Permohonan Layanan Kesehatan Kapal</h5>

                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="layanan_pemeriksaan_kesehatan" name="layanan_pemeriksaan_kesehatan" value="1" <?= old('layanan_pemeriksaan_kesehatan') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="layanan_pemeriksaan_kesehatan">Pemeriksaan kesehatan sanitasi dan crew kapal</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="layanan_penerbitan_sscec" name="layanan_penerbitan_sscec" value="1" <?= old('layanan_penerbitan_sscec') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="layanan_penerbitan_sscec">Pemeriksaan dalam rangka penerbitan SSCEC/SSCC</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="layanan_penerbitan_p3k" name="layanan_penerbitan_p3k" value="1" <?= old('layanan_penerbitan_p3k') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="layanan_penerbitan_p3k">Pemeriksaan dalam rangka penerbitan sertifikat P3K</label>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="layanan_lainnya" name="layanan_lainnya" value="1" <?= old('layanan_lainnya') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="layanan_lainnya">Lainnya</label>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text">(sebutkan jenis layanan)</span>
                                <input type="text" class="form-control" name="layanan_lainnya_sebutkan" value="<?= old('layanan_lainnya_sebutkan') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Fasilitasi Transportasi -->
                    <div class="mb-4 mt-4">
                        <label class="form-label fw-bold">Fasilitasi Transportasi Petugas Pemeriksa</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fasilitasi_transportasi" id="fasilitasi_ya" value="Ya" <?= old('fasilitasi_transportasi') == 'Ya' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="fasilitasi_ya">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fasilitasi_transportasi" id="fasilitasi_tidak" value="Tidak" <?= old('fasilitasi_transportasi') == 'Tidak' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="fasilitasi_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <!-- Upload -->
                    <div class="mb-5 mt-4">
                        <label for="surat_permohonan" class="form-label fw-bold">Unggah Surat Permohonan Layanan (max. 2 MB)</label>
                        <input class="form-control" type="file" id="surat_permohonan" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
