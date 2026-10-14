<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="jumbotron text-center text-info">
      <h4>Hallo PNS dan PPPK, untuk pengajuan cuti sudah dipindahkan halamannya dengan pengajuan langsung ke pusat melalui Portal E-Office di link berikut: <a class="text-danger" target="_blank" href="https://auth-eoffice.kemkes.go.id/do-login">Klik</a></h4>
  </div>
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <div class="card">
          <div class="card-header row">
            <div class="col-6"> 
              <?php if ($statusPegawai) : ?>
                <h5>Sisa pengajuan cuti tahunan : <?= $sisaCutiTahunan; ?> hari kerja</h5>
              <?php endif; ?>
            </div>
            <div class="col-6 text-right">
            <?php if ($pns == false) : ?>
              <?php if ($statusPegawai) : ?>
                <a href="/cuti/tambah_cuti" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
              <?php else : ?>
                <?php if (session()->get('role') == 'Admin') : ?>
                  <a href="/cuti/tambah_cuti" class="btn bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                <?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th style="width:15%;">Status</th>
                  <th>Jenis Cuti</th>
                  <th>Status Pegawai</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 1;
                foreach ($dataCuti as $data) : ?>
                  <tr>
                    <td><?= $nomor++; ?></td>
                    <td><span class="d-block"><?= $data['nip_pegawai']; ?>/</span><span class="d-block"><?= $data['nama_pegawai']; ?></span></td>
                    <td>
                      <span class="d-block">Kepegawaian:
                        <?= $data['cuti_status_kepegawaian'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['cuti_status_kepegawaian'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['cuti_status_kepegawaian'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                        <?= $data['cuti_status_kepegawaian'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_kepegawaian'] . '</span>' : ''; ?>
                      </span>
                      <span class="d-block">Atasan:
                        <?php if ($data['cuti_status_kepegawaian'] == 'Ditolak') : ?>
                          -
                        <?php else : ?>

                          <?= $data['cuti_status_atasan'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_atasan'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_atasan'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_atasan'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_atasan'] . '</span>' : ''; ?>
                        <?php endif; ?>
                      </span>
                      
                      <?php if($data['id_status_pegawai'] == "2" || $data['id_status_pegawai'] == "3"): ?>
                      <span class="d-block">Pejabat:

                        <?php if ($data['cuti_status_kepegawaian'] == 'Ditolak' || $data['cuti_status_atasan'] == 'Ditolak') : ?>
                          -
                        <?php else : ?>
                          <?= $data['cuti_status_pejabat'] == 'Pending' ? '<span class="badge badge-pill badge-warning">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_pejabat'] == 'Disetujui' ? '<span class="badge badge-pill badge-success">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_pejabat'] == 'Dikembalikan' ? '<span class="badge badge-pill badge-info">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                          <?= $data['cuti_status_pejabat'] == 'Ditolak' ? '<span class="badge badge-pill badge-danger">' . $data['cuti_status_pejabat'] . '</span>' : ''; ?>
                        <?php endif; ?>
                      </span>
                      <?php endif; ?>
                    </td>
                    <td><?= $data['jenis_cuti']; ?></td>
                    <td><?= $data['status_pegawai']; ?></td>
                    <td><?= $data['time']; ?> <br/> <div class="pt-3">Diperbaharui oleh: <br/><small><?= name_capitalize($data['updated']); ?></small></div></td>

                    <?php if ($data['cuti_status_kepegawaian'] == 'Disetujui' && $data['cuti_status_atasan'] == 'Disetujui' && $data['cuti_status_pejabat'] == 'Disetujui') : ?>
                      <td>
                        <a href="/cuti/detail_cuti/<?= $data['id_cuti_data_cuti']; ?> " class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <form action="/cuti/pdf/<?= $data['id_cuti_data_cuti']; ?>" method="post" class="d-inline">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-sm bg-gradient-success"><i class="fas fa-file-alt"></i></button>
                        </form>
                        <!--<?php //if ($data['jenis_cuti'] == 'Cuti Karena Alasan Penting') : ?>-->
                        <!--  <button type="button" data-link="<?//= $data['id_cuti_data_cuti']; ?>" class="btn btn-sm bg-gradient-success cuti_alasan_penting_btn"><i class="fas fa-file-alt"></i></button>-->
                        <!--<?php //else : ?>-->
                        <!--  <form action="/cuti/pdf/<?//= $data['id_cuti_data_cuti']; ?>" method="post" class="d-inline">-->
                        <!--    <?//= csrf_field(); ?>-->
                        <!--    <input type="hidden" name="_method" value="DELETE">-->
                        <!--    <button type="submit" class="btn btn-sm bg-gradient-success"><i class="fas fa-file-alt"></i></button>-->
                        <!--  </form>-->
                        <!--<?php //endif; ?>-->
                      </td>
                    <?php elseif ($data['cuti_status_kepegawaian'] == 'Ditolak' || $data['cuti_status_atasan'] == 'Ditolak' || $data['cuti_status_pejabat'] == 'Ditolak') : ?>
                      <td>
                        <span class="badge badge-pill badge-secondary">Tidak ada aksi</span>
                      </td>
                    <?php elseif ($data['cuti_status_kepegawaian'] == 'Dikembalikan' || $data['cuti_status_atasan'] == 'Dikembalikan' || $data['cuti_status_pejabat'] == 'Dikembalikan') : ?>
                      <td>
                        <a href="/cuti/detail_cuti/<?= $data['id_cuti_data_cuti']; ?> " class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <a href="/cuti/update_cuti/<?= $data['id_cuti_data_cuti']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      </td>
                    <?php elseif ($data['cuti_status_atasan'] == 'Disetujui') : ?>
                      <td>
                        <a href="/cuti/detail_cuti/<?= $data['id_cuti_data_cuti']; ?> " class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                      </td>
                    <?php elseif ($data['cuti_status_kepegawaian'] == 'Disetujui') : ?>
                      <td>
                        <a href="/cuti/detail_cuti/<?= $data['id_cuti_data_cuti']; ?> " class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <a href="/cuti/update_cuti/<?= $data['id_cuti_data_cuti']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                      </td>
                    <?php else : ?>
                      <td>
                        <a href="/cuti/detail_cuti/<?= $data['id_cuti_data_cuti']; ?> " class="btn btn-sm bg-gradient-info" for="Lihat Data"><i class="fas fa-eye"></i></a>
                        <a href="/cuti/update_cuti/<?= $data['id_cuti_data_cuti']; ?>" class="btn btn-sm bg-gradient-warning" for="Edit Data"><i class="fas fa-edit"></i></a>
                        <form action="/cuti/dlt_cuti/<?= $data['id_cuti_data_cuti']; ?>" method="post" class="d-inline formHapusData">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn btn-sm bg-gradient-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>NIP/Nama</th>
                  <th>Status</th>
                  <th>Jenis Cuti</th>
                  <th>Status Pegawai</th>
                  <th>Last Update</th>
                  <th>Aksi</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
</section>
<script>
   $(document).ready(function() {
            $(".cuti_alasan_penting_btn").click(function() {
                var url = $(this).data('link');

                // Create form element
                var form = $('<form>', {
                    action: '/cuti/pdf',
                    method: 'post'
                });

                // Create hidden form group
                var formGroup0 = $('<input>', {
                    type: 'hidden',
                    name: 'id',
                    class: 'form-control',
                    value: url,
                    placeholder: 'opsional'
                });

                // Create first form group
                var formGroup1 = $('<div>', {
                    class: 'form-group'
                }).append(
                    $('<label>', {
                        text: 'Jabatan'
                    }),
                    $('<input>', {
                        type: 'text',
                        name: 'jabatan',
                        class: 'form-control',
                        value: '',
                        placeholder: 'opsional'
                    })
                );

                // Create second form group with two inputs
                var formGroup2 = $('<div>', {
                    class: 'form-group'
                }).append(
                    $('<label>', {
                        text: 'Nama'
                    }),
                    $('<input>', {
                        type: 'text',
                        name: 'nama',
                        class: 'form-control',
                        value: '',
                        placeholder: 'opsional'
                    }),
                    $('<input>', {
                        type: 'text',
                        name: 'nip',
                        class: 'form-control',
                        value: '',
                        placeholder: '(NIP.123456/ NOPEG.123456)'
                    })
                );

                // Create noted section
                var noted = $('<div>', {
                    class: 'form-group'
                }).append(
                    $('<p>', {
                        html: '*<small>Formulir diatas untuk mengisi bagian <u>Keputusan Pejabat Yang Berwenang Memberikan Cuti</u> <i>Dapat tidak diisikan</i></small>'
                    })
                );

                // Create submit button
                var submitButton = $('<button>', {
                    type: 'submit',
                    class: 'btn btn-success float-right'
                }).append(
                    $('<i>', {
                        class: 'fas fa-file-pdf'
                    }),
                    ' Unduh File'
                );

                // Append form groups and submit button to form
                form.append(formGroup0, formGroup1, formGroup2, noted, submitButton);

                // Log the form's method to verify
                console.log('Form method:', form.attr('method'));

                // Append form to modal body
                $('#modal-md').find(".modal-body").html(form);
                $("#modal-md").modal('show');
            });
        });
</script>
<?= $this->endSection(); ?>