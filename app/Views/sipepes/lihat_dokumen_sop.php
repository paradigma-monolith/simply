<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php //phpinfo(); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Rincian Dokumen SOP</h3>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <?php
                if ($lihat_dokumen_sop['jenis_dokumen'] == "SOP Utama") {
                ?>
                  <a href="../utama" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                  <a href="/sipepes/ubah_dokumen_sop/<?= $lihat_dokumen_sop['dokumen_id']; ?>" class="btn btn-warning"><i class="fas as fa-edit"></i> Ubah Dokumen</a>
                <?php
                } elseif ($lihat_dokumen_sop['jenis_dokumen'] == "SOP Pendukung") {
                ?>
                  <a href="../pendukung" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php
                } else {
                ?>
                  <a href="../lainnya" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php
                }
                ?>
              </div>
            </div>

            <br>

            <div class="row pb-2">
              <div class="col-3">Nomor Dokumen</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['no_dokumen']; ?></div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Judul</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['judul']; ?></div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Divisi</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['unit_kerja']; ?></div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Jenis Dokumen</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['jenis_dokumen']; ?></div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Tanggal Pembuatan</div>
              <div class="col-9">: &nbsp
                <?php if ($lihat_dokumen_sop['tanggal_pembuatan'] == "0000-00-00") {
                  echo "<span class='text-danger'>Belum ada data.</span>";
                } else {
                  echo date('d M Y', strtotime($lihat_dokumen_sop['tanggal_pembuatan']));
                }
                ?>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Revisi</div>
              <div class="col-9">: &nbsp
                <?php if ($lihat_dokumen_sop['tanggal_revisi'] == "0000-00-00") {
                  echo "<span class='text-danger'>Belum ada data.</span>";
                } else {
                  echo date('d M Y', strtotime($lihat_dokumen_sop['tanggal_revisi'])) . " (Rev. " . $lihat_dokumen_sop['revisi_ke'] . ")";
                }
                ?>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Tanggal Efektif</div>
              <div class="col-9">: &nbsp
                <?php if ($lihat_dokumen_sop['tanggal_efektif'] == "0000-00-00") {
                  echo "<span class='text-danger'>Belum ada data.</span>";
                } else {
                  echo date('d M Y', strtotime($lihat_dokumen_sop['tanggal_efektif']));
                }
                ?>
              </div>
            </div>
            
            <div class="row pb-2">
              <div class="col-3">Status Pengesahan</div>
              <div class="col-9">: &nbsp
                <?= $lihat_dokumen_sop['status_pengesahan'] == 'Proses Pengesahan' ? '<span class="badge bg-warning">' . $lihat_dokumen_sop['status_pengesahan'] . '</span>' : ''; ?>
                <?= $lihat_dokumen_sop['status_pengesahan'] == 'Sudah Pengesahan' ? '<span class="badge bg-success">' . $lihat_dokumen_sop['status_pengesahan'] . '</span>' : ''; ?>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Diubah Oleh</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['nama_pegawai']; ?></div>
            </div>

            <div class="row pb-2">
              <div class="col-3">Terakhir Diubah</div>
              <div class="col-9">: &nbsp <?= $lihat_dokumen_sop['updated_at']; ?></div>
            </div>

            <hr>

            <iframe style="height:50vw; width:100%;" src="/dokumen_sop/<?= $lihat_dokumen_sop['file']; ?>" title="description"></iframe>
          </div>

          <div class="card-footer">
          </div>
        </div>

      </div>
    </div>

    <!--<div class="row">-->
    <!--  <iframe style="height:50vw; width:100%;" class="previewPDF" src="/sipepes/pdfView/<?= $lihat_dokumen_sop['dokumen_id']; ?>"></iframe>-->
    <!--</div>-->
  </div>
</section>
<?= $this->endSection(); ?>