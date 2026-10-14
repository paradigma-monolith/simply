<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="card">
    <div class="card-header">
      <div class="card" style="overflow: hidden;">
        <div class="row">
          <div class="col-sm-4 px-0">
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-primary btn-sm my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-hand-holding-medical"></i>
              </a>
              Pemeriksaan Fisik & Riwayat Penyakit
            </div>
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-primary btn-sm my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-lungs-virus"></i>
              </a>
              Skrining TBC
            </div>
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-primary btn-sm my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-first-aid"></i>
              </a>
              Skrining HIV
            </div>
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-primary btn-sm my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-user-shield"></i>
              </a>
              Skrining PTM (Penyakit Tidak Menular)
            </div>
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-primary btn-sm my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-laptop-medical"></i>
              </a>
              Laboratorium
            </div>
            <div class="card-body py-0 border-bottom border-right">
              <a class="btn btn-info my-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-notes-medical"></i>
              </a>
              Pemeriksaan Dokter
            </div>
          </div>
          <div class="col-sm-4 px-0">
            <div class="card-body py-0 border-bottom border-right">
              <h5 class="border-bottom pt-1">Keterangan</h5>
              Tombol pada kolom Pemeriksaan kesehatan yang mempunyai warna biru
              <a class="btn btn-primary m-0 py-1 px-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-circle fa-xs"></i>
              </a> menandakan proses pemeriksaan hari ini belum pernah terinput. Jika Tombol sudah berwarna kuning <a class="btn btn-warning m-0 py-1 px-2 rounded-circle" href="javascript:void(0)">
                <i class="fas fa-circle fa-xs"></i>
              </a> menandakan pemeriksaan hari ini sudah pernah dilakukan.
            </div>
            <div class="card-body py-0 border-right">
              <a class="btn btn-primary mt-2 py-1 px-2 rounded-circle" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Fisik & Riwayat Penyakit sudah dilakukan sebanyak 10x">
                <i class="fas fa-hand-holding-medical"></i>
              </a>
              Arahkan kursor ( <i class="fas fa-mouse-pointer fa-xs"></i>) pada tombol untuk mengetahui jumlah dan jenis pemeriksaan yang sudah dilakukan pada pasien (hanya berlaku di komputer).
            </div>
          </div>
          <div class="col-sm-4 px-0">
            <div class="card-body py-0 border-right">
              Keterangan tombol untuk <u>Pemeriksaan Dokter</u>. Jika tombol berwarna abu-abu
              <a class="btn btn-secondary btn-sm rounded-circle" href="javascript:void(0)">
                <i class="fas fa-notes-medical"></i>
              </a>
              menandakan pasien belum melakukan <u>Pemeriksaan Fisik & Riwayat Penyakit</u> dihari ini. Jika <u>Pemeriksaan Fisik & Riwayat Penyakit</u> sudah dilakukan, tombol dapat diakses dengan perubahan tombol menjadi berwarna biru muda
              <a class="btn btn-info btn-sm rounded-circle" href="javascript:void(0)">
                <i class="fas fa-notes-medical"></i>
              </a>.
              Jika <u>Pemeriksaan Dokter</u> sudah dilakukan tombol akan berubah menjadi warna merah
              <a class="btn btn-danger btn-sm rounded-circle" href="javascript:void(0)">
                <i class="fas fa-notes-medical"></i>
              </a>.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <a class="btn btn-primary" href="/uklw/tambahPasien">
          <i class="fas fa-plus"> </i>
          Tambah Data Pasien
        </a>
        <button class="btn btn-info" onclick="dataPegawai()">
          <i class="fas fa-plus"> </i>
          Tambah Data Pasien dari Data Pegawai
        </button>
        <a class="btn btn-success" href="/public/uklw/landing" target="_blank" data-toggle="tooltip" data-placement="top" title="Buka halaman formulir publik untuk pasien mengisi data sendiri">
          <i class="fas fa-external-link-alt"> </i>
          Link Pengisian Publik
        </a>
      </h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body px-0">
      <table id="tableWithoutExporting" class="table table-striped projects">
        <thead>
          <tr>
            <th style="width: 1%">#</th>
            <th style="width: 20%">Identitas</th>
            <th style="width: 10%;">Jenis Kelamin</th>
            <th style="width: 15%">Status</th>
            <th>No. HP</th>
            <th style="width: 10%" class="text-center">Usia</th>
            <th style="width: 15%">Pemeriksaan</th>
            <th style="width: 10%"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($getData as $data) : ?>
            <tr>
              <td>#</td>
              <td>
                <a> <?= $data['nama']; ?> </a>
                <br />
                <small> <?= $data['identitas']; ?> : <?= $data['nomor_identitas']; ?> </small>
              </td>
              <td>
                <?php if ($data['jk'] == "Perempuan") : ?>
                  <i class="fas fa-2x fa-venus text-danger"></i>
                <?php elseif ($data['jk'] == "Laki-laki") : ?>
                  <i class="fas fa-2x fa-mars text-info"></i>
                <?php else : ?>
                  <i class="fas fa-2x fa-genderless"></i>
                <?php endif; ?>
                <br />
                <small><?= $data['jk']; ?></small>
              </td>
              <td>
                <?= $data['status']; ?>
              </td>
              <td class="project_progress">
                <?php
                $no_telp = $data['no_hp'];
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
                <strong class="kontak1">
                  <a href="<?= $no_telp; ?>" target="_blank"><i class="fab fa-whatsapp"></i> <?= $data['no_hp']; ?>
                  </a>
                </strong>
              </td>
              <td class="project-state">
                <?= selisi_waktu($data['tgl_lahir']); ?>
                <br />
                <small><?= longdate_indo($data['tgl_lahir']); ?></small>
              </td>
              <td class="text-right">
                <a class="btn btn-<?= in_array($data['id_uklw_pasien'], $checkTanggalPemkes) ? 'warning' : 'primary'; ?> m-1 btn-sm rounded-circle" href="/uklw/pemkes/<?= $data['id_uklw_pasien']; ?>" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Fisik & Riwayat Penyakit sudah dilakukan sebanyak <?= $data['uklw_pemkes_count']; ?>x">
                  <i class="fas fa-hand-holding-medical"></i>
                </a>
                <a class="btn btn-<?= in_array($data['id_uklw_pasien'], $checkTanggalSkriningTBC) ? 'warning' : 'primary'; ?> m-1 btn-sm rounded-circle" href="/uklw/skriningTBC/<?= $data['id_uklw_pasien']; ?>" data-toggle="tooltip" data-placement="top" title="Skrining TBC sudah dilakukan sebanyak <?= $data['uklw_skrining_tbc_count']; ?>x">
                  <i class="fas fa-lungs-virus"></i>
                </a>
                <a class="btn btn-<?= in_array($data['id_uklw_pasien'], $checkTanggalSkriningHIV) ? 'warning' : 'primary'; ?> m-1 btn-sm rounded-circle" href="/uklw/skriningHIV/<?= $data['id_uklw_pasien']; ?>" data-toggle="tooltip" data-placement="top" title="Skrining HIV sudah dilakukan sebanyak <?= $data['uklw_skrining_hiv_count']; ?>x">
                  <i class="fas fa-first-aid"></i>
                </a>
                <a class="btn btn-<?= in_array($data['id_uklw_pasien'], $checkFaktorRisikoPTM) ? 'warning' : 'primary'; ?> m-1 btn-sm rounded-circle" href="/uklw/faktorRisikoPTM/<?= $data['id_uklw_pasien']; ?>" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Risiko PTM sudah dilakukan sebanyak <?= $data['uklw_skrining_ptm_count']; ?>x">
                  <i class="fas fa-user-shield"></i>
                </a>
                <a class="btn btn-<?= in_array($data['id_uklw_pasien'], $checkLaboratorium) ? 'warning' : 'primary'; ?> m-1 btn-sm rounded-circle" href="/uklw/laboratorium/<?= $data['id_uklw_pasien']; ?>" data-toggle="tooltip" data-placement="top" title="Pengecekan Laboratorium sudah dilakukan sebanyak <?= $data['uklw_laboratorium_count']; ?>x">
                  <i class="fas fa-laptop-medical"></i>
                </a>
                <?php if ($data['id_uklw_pemkes'] != 0) : ?>
                  <?php if ($data['id_uklw_catatan_pemeriksaan'] != 0) : ?>
                    <a class="btn btn-danger rounded-circle" href="/uklw/catatan_pemkes/<?= $data['id_uklw_pemkes']; ?>" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Dokter sudah dilakukan sebanyak <?= $data['uklw_catatan_pemeriksaan_count']; ?>x">
                      <i class="fas fa-notes-medical"></i>
                    </a>
                  <?php else : ?>
                    <a class="btn btn-info rounded-circle" href="/uklw/catatan_pemkes/<?= $data['id_uklw_pemkes']; ?>" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Dokter sudah dilakukan sebanyak <?= $data['uklw_catatan_pemeriksaan_count']; ?>x">
                      <i class="fas fa-notes-medical"></i>
                    </a>
                  <?php endif; ?>
                <?php else : ?>
                  <a class="btn btn-secondary rounded-circle" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Pemeriksaan Dokter sudah dilakukan sebanyak <?= $data['uklw_catatan_pemeriksaan_count']; ?>x">
                    <i class="fas fa-notes-medical"></i>
                  </a>
                <?php endif; ?>
              </td>
              <td class="project-actions text-right">
                <form action="/uklw/export_pdf" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="id" value="<?= $data['id_uklw_pasien']; ?>">
                  <button type="submit" class="btn btn-outline-danger"><i class="fas fa-file-pdf"></i></button>
                </form>
                <!-- <a class="btn btn-info btn-sm uklwViewPasien" href="/uklw/pasien/view/<? //= $data['id_uklw_pasien']; 
                                                                                            ?>">
                  <i class="fas fa-eye"> </i>
                  View
                </a> -->
                <a class="btn btn-warning btn-sm" href="/uklw/editPasien/<?= $data['id_uklw_pasien']; ?>">
                  <i class="fas fa-pencil-alt"> </i>
                  Edit
                </a>
                <form action="/uklw/delete/uklw_pasien/<?= $data['id_uklw_pasien']; ?>" method="post" class="d-inline formHapusData">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><i class="fas fa-trash-alt"></i> Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>

<?= $this->endSection(); ?>