<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .timeline {
    padding: 50px 0;
    position: relative;
  }

  .timeline-nodes {
    padding-bottom: 25px;
    position: relative;
  }

  .timeline-nodes:nth-child(even) {
    flex-direction: row-reverse;
  }

  .timeline h1,
  .timeline h2,
  .timeline h3,
  .timeline h4,
  .timeline h5,
  .timeline h6,
  .timeline p {
    padding: 5px 15px;
  }

  .timeline h1,
  .timeline h2,
  .timeline h3,
  .timeline h4,
  .timeline h5,
  .timeline h6 {
    font-weight: lighter;
    background: var(--blue);
  }

  .timeline p,
  .timeline time {
    color: var(--blue)
  }

  .timeline::before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 50%;
    width: 0;
    border-left: 2px dashed var(--blue);
    height: 100%;
    z-index: 1;
    transform: translateX(-50%);
  }

  .timeline-content {
    border: 1px solid var(--blue);
    position: relative;
    border-radius: 0 0 10px 10px;
    box-shadow: 0px 3px 25px 0px rgba(10, 55, 90, 0.2)
  }

  .timeline-nodes:nth-child(odd) h1,
  .timeline-nodes:nth-child(odd) h2,
  .timeline-nodes:nth-child(odd) h3,
  .timeline-nodes:nth-child(odd) h4,
  .timeline-nodes:nth-child(odd) h5,
  .timeline-nodes:nth-child(odd) h6,
  .timeline-nodes:nth-child(odd) p {
    text-align: right;
  }

  .timeline-nodes:nth-child(odd) .timeline-date {
    text-align: left;
  }

  .timeline-nodes:nth-child(even) .timeline-date {
    text-align: right;
  }

  .timeline-nodes:nth-child(odd) .timeline-content::after {
    content: "";
    position: absolute;
    top: 5%;
    left: 100%;
    width: 0;
    border-left: 10px solid var(--blue);
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
  }

  .timeline-nodes:nth-child(even) .timeline-content::after {
    content: "";
    position: absolute;
    top: 5%;
    right: 100%;
    width: 0;
    border-right: 10px solid var(--blue);
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
  }

  .timeline-image {
    position: relative;
    z-index: 100;
  }

  .timeline-image::before {
    content: "";
    width: 80px;
    height: 80px;
    border: 2px dashed var(--blue);
    border-radius: 50%;
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    z-index: 1;


  }

  .timeline-image img {
    position: relative;
    z-index: 100;
  }

  /*small device style*/

  @media (max-width: 767px) {

    .timeline-nodes:nth-child(odd) h1,
    .timeline-nodes:nth-child(odd) h2,
    .timeline-nodes:nth-child(odd) h3,
    .timeline-nodes:nth-child(odd) h4,
    .timeline-nodes:nth-child(odd) h5,
    .timeline-nodes:nth-child(odd) h6,
    .timeline-nodes:nth-child(odd) p {
      text-align: left
    }

    .timeline-nodes:nth-child(even) {
      flex-direction: row;
    }

    .timeline::before {
      content: "";
      display: block;
      position: absolute;
      top: 0;
      left: 4%;
      width: 0;
      border-left: 2px dashed var(--blue);
      height: 100%;
      z-index: 1;
      transform: translateX(-50%);
    }

    .timeline h3 {
      font-size: 1.7rem;
    }

    .timeline p {
      font-size: 14px;
    }

    .timeline-image {
      position: absolute;
      left: 0%;
      top: 60px;
      /*transform: translateX(-50%;);*/
    }

    .timeline-nodes:nth-child(odd) .timeline-content::after {
      content: "";
      position: absolute;
      top: 5%;
      left: auto;
      right: 100%;
      width: 0;
      border-left: 0;
      border-right: 10px solid var(--blue);
      border-top: 10px solid transparent;
      border-bottom: 10px solid transparent;
    }

    .timeline-nodes:nth-child(even) .timeline-content::after {
      content: "";
      position: absolute;
      top: 5%;
      right: 100%;
      width: 0;
      border-right: 10px solid var(--blue);
      border-top: 10px solid transparent;
      border-bottom: 10px solid transparent;
    }

    .timeline-nodes:nth-child(even) .timeline-date {
      text-align: left;
    }

    .timeline-image::before {
      width: 65px;
      height: 65px;
    }
  }

  /*extra small device style */
  @media (max-width: 575px) {
    .timeline::before {
      content: "";
      display: block;
      position: absolute;
      top: 0;
      left: 3%;
    }

    .timeline-image {
      position: absolute;
      left: -5%;
    }

    .timeline-image::before {
      width: 60px;
      height: 60px;
    }
  }

  .container-timeline::-webkit-scrollbar {
    width: 8px;
  }

  .container-timeline::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
  }

  .container-timeline {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0);
  }
</style>

<section class="content">
  <div class="row">
    <div class="col-lg">
      <div class="card">
        <div class="card-body">
          <h5 class="text-center mb-5"><strong><?= name_capitalize($pasien['nama']); ?></strong></h5>

          <?php
          $no_telp = $pasien['no_hp'];
          if ($no_telp != "") {
            if (substr($no_telp, 0, 1) === '0') {
              // Ganti 0 di awal dengan 62
              $no_telp = 'https://wa.me/62' . substr($no_telp, 1);
            } else {
              // Jika tidak dimulai dengan 0, biarkan string tetap sama
              $no_telp = 'https://wa.me/' . $no_telp;
            }
          }
          ?>
          <div class="card">
            <div class="card-footer">
              <div><strong>Jenis Kelamin</strong> : <?= $pasien['jk']; ?></div>
              <div><strong>Umur</strong> : <?= selisi_waktu($pasien['tgl_lahir']) == "0 menit" ? "-" : selisi_waktu($pasien['tgl_lahir']); ?></div>
              <div><strong>Tanggal Lahir</strong> : <?= mediumdate_indo($pasien['tgl_lahir']); ?></div>
              <div><strong>Alamat</strong> : <?= $pasien['alamat']; ?></div>
              <div><strong>No. HP</strong> : <a href="<?= $no_telp; ?>" target="_blank"><i class="fab fa-whatsapp"></i> <?= $pasien['no_hp']; ?></a></div>
              <div><strong>Pendidikan</strong> : <?= $pasien['pendidikan']; ?></div>
              <div><strong>Pekerjaan</strong> : <?= $pasien['pekerjaan']; ?></div>
              <div><strong>Status</strong> : <?= $pasien['status']; ?></div>
            </div>
          </div>

          Nama Keluarga : <strong>
            <?= $pasien['nama_keluarga']; ?>
          </strong>
          <hr>
          Hubungan Keluarga : <strong>
            <?= $pasien['hubungan_keluarga']; ?>
          </strong>
          <hr>

          <?php
          $no_telp = $pasien['no_hp_keluarga'];
          if ($no_telp != "") {
            if (substr($no_telp, 0, 1) === '0') {
              // Ganti 0 di awal dengan 62
              $no_telp = 'https://wa.me/62' . substr($no_telp, 1);
            } else {
              // Jika tidak dimulai dengan 0, biarkan string tetap sama
              $no_telp = 'https://wa.me/' . $no_telp;
            }
          }
          ?>
          No. HP Keluarga : <strong>
            <a href="<?= $no_telp; ?>" target="_blank"><i class="fab fa-whatsapp"></i> <?= $pasien['no_hp_keluarga']; ?></a>
          </strong>
          <hr>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <?php
          $kategoriCount = [];

          // Menghitung jumlah entri untuk setiap kategori
          foreach ($record as $entry) {
            $kategori = $entry['kategori'];
            if (isset($kategoriCount[$kategori])) {
              $kategoriCount[$kategori]++;
            } else {
              $kategoriCount[$kategori] = 1;
            }
          }

          // Menampilkan hasil
          foreach ($kategoriCount as $kategori => $count) {
            echo '<div class="mb-3">';
            echo '<h5 class="card-title">' . $kategori . '</h5>';
            echo '<p class="card-text">Jumlah entri: ' . $count . '</p>';
            echo '</div>';
          }
          ?>
        </div>
      </div>
      <div class="card">
        <div class="card-footer">
          <form action="/uklw/export_pdf" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="form-group">
              <label>Pemeriksaan Fisik dan Riwayat Penyakit</label>
              <select name="id_uklw_pemkes" class="select2" style="width: 100%;">
                <option value="">--Pilih Tanggal--</option>
                <?php foreach ($pemkes as $data) : ?>
                  <option value="<?= $data['id_uklw_pemkes']; ?>"><?= $data['created_at']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Skrining TBC</label>
              <select name="id_uklw_skrining_tbc" class="select2" style="width: 100%;">
                <option value="">--Pilih Tanggal--</option>
                <?php foreach ($skriningTBC as $data) : ?>
                  <option value="<?= $data['id_uklw_skrining_tbc']; ?>"><?= $data['created_at']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Skrining HIV dan Penyakit Menular</label>
              <select name="id_uklw_skrining_hiv" class="select2" style="width: 100%;">
                <option value="">--Pilih Tanggal--</option>
                <?php foreach ($skriningHIV as $data) : ?>
                  <option value="<?= $data['id_uklw_skrining_hiv']; ?>"><?= $data['created_at']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn float-right btn-xs bg-gradient-danger" data-toggle="tooltip" data-placement="bottom" title="Formulir Data Skrining Terintegrasi"><i class="fas fa-file-pdf"></i> Export</button>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-footer">
          <form action="/uklw/export_excel/penjamah_makanan" method="post">
            <?= csrf_field(); ?>
            <div class="form-group">
              <label>Penjamah Makanan</label>
              <select required name="pemkes" class="form-control select2" <?= $penjamah_makanan ? '' : 'disabled'; ?>>
                <option value="">--Pilih Waktu Pemeriksaan--</option>
                <?php foreach ($penjamah_makanan as $data) : ?>
                  <option value="<?= $data['id_uklw_pemkes']; ?>" <?= $data['wawancara'] ? '' : 'disabled'; ?>><?= $data['created_at']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button <?= $penjamah_makanan ? '' : 'disabled'; ?> type="submit" class="btn float-right btn-xs bg-gradient-success" data-toggle="tooltip" data-placement="bottom" title="<?= $penjamah_makanan ? 'Penjamah Makanan' : 'Data tidak ditemukan'; ?>"><i class="fas fa-file-excel"></i> Export</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-8">
      <div class="container container-timeline">
        <div class="timeline">
          <?php foreach ($record as $data) : ?>
            <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
              <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                <h5 class=" text-light"><?= $data['kategori']; ?></h5>
                <p><?= $data['ket']; ?></p>
              </div>
              <div class="col-2 col-sm-1 px-md-3 order-2 timeline-image text-center">
                <i class="text-primary fas fa-2x <?= $data['icon']; ?>" style="position: relative; z-index: 101;"></i>
                <!-- <img src="" class="img-fluid " alt=" "> -->
              </div>
              <div class="col-10 col-md-5 order-1 order-md-3 py-3 timeline-date">
                <time><?= $data['created_at']; ?></time>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('[data-widget="pushmenu"]').trigger('click');
  });
</script>

<?= $this->endSection(); ?>