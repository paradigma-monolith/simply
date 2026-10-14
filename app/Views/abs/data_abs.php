<!doctype html>
<html lang="en">

<head>
  <title>ABS | Simply Balai Karkes Bandung</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="/website-menu-12/css/style.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">

  <style>
    body {
      position: relative;
      width: 100%;
      height: 300px;
      background-image: url('/dist/img/watermark.png');
      background-size: 150px;
      background-repeat: repeat;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      /* Ubah nilai alpha (0.5) sesuai kebutuhan transparansi */
    }

    .form-control.form-control-sm {
      height: 28px !important;
    }

    .bg-secondarys {
      background-color: #a2a2a2 !important;
    }
  </style>

</head>

<body>
  <?php
  $array = unserialize($getData['data_barang']);
  $nama_barang = '';
  $kode_barang = '';
  foreach ($array as $key => $data) {
    $nama_barang = $data;
    break;
  }
  ?>
  <div class="overlay"></div>
  <section class="ftco-section pt-0 mt-4 pb-0 mb-4">
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-6 col-md-7 col-sm-9 img-thumbnail pl-0 pr-0">
        <div class="container-fluid">
          <div class="row justify-content-between pt-4 pb-2">
            <div class="col">
              <a class="navbar-brand" href="index.html">ABS BMN <span>Balai Kekarantinaan Kesehatan Kelas I Bandung</span></a>
            </div>
            <div class="col d-flex justify-content-end">
              <div class="social-media">
                <p class="mb-0 d-flex">
                  <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                  <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                  <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
                </p>
              </div>
            </div>
          </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
          <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
              <img src="/dist/img/logo.png" class="img-thumbnail" alt="logo" style="height: 80px; background-color: unset; border: unset;">
            </button>
            <form action="#" class="searchform order-lg-last">
              <div class="form-group d-flex">
                <input type="text" class="form-control" placeholder=" Search">
                <button type="submit" placeholder="" class="form-control search"><span class="fa fa-search"></span></button>
              </div>
            </form>
            <div class="collapse navbar-collapse" id="ftco-nav">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                  <img src="/dist/img/logo.png" class="img-thumbnail" alt="logo" style="height: 80px; background-color: unset; border: unset;">
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="container text-center data-barang mb-4" style="overflow: auto;">
          <img src="/dist/img/abs/<?= $getData['image']; ?>" alt="" class="img-thumbnail mt-2" style="max-height: 170px;">
          <h3><strong><?= $nama_barang; ?></strong></h3>
          <table class="table table-sm table-bordered text-left" style="font-size: 10pt;">
            <tr>
              <td colspan="2"><strong>Lokasi</strong></td>
            </tr>
            <tr>
              <td>Wilayah Kerja</td>
              <td>: <?= $getData['unit_kerja']; ?></td>
            </tr>
            <tr>
              <td>Lantai</td>
              <td>: <?= $getData['lantai'] ? $getData['lantai'] : '<span class="text-danger">Tidak diketahui</span>'; ?></td>
            </tr>
            <tr>
              <td>Ruang</td>
              <td>: <?= $getData['ruang'] ? $getData['ruang'] : '<span class="text-danger">Tidak diketahui</span>'; ?></td>
            </tr>
            <tr>
              <td>Kategori</td>
              <td>: <?= $getData['jenis'] ? $getData['jenis'] : '<span class="text-danger">Tidak diketahui</span>'; ?></td>
            </tr>
          </table>
          <table class="table table-sm table-bordered text-center" style="font-size: 18pt;">
            <tr>
              <th><i class="fa fa-circle <?= $getData['status_tersedia'] == "Tersedia" ? 'text-success' : 'text-danger' ; ?>"></i> <?= $getData['status_tersedia']; ?></th>
            </tr>
          </table>
          <button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#exampleModal">
            Pengajuan Pemindahan Barang
          </button>
          <button class="btn btn-info btn-block" data-toggle="modal" data-target="#examplePemeliharaanModal">
            Pengajuan Pemeliharaan Barang
          </button>
          <table class="table table-sm" style="font-size: 9pt;">
            <?php 
            $count = 1;
            foreach ($array as $key => $data) : $key == 'Kode Barang' ? $kode_barang = $data : ''; ?>
              <tr>
                <td class="text-right"><?= $key; ?></td>
                <td>:</td>
                <td class="text-left"><?= $data; ?></td>
              </tr>
              <?php if($count++ == 10)break; ?>
            <?php endforeach; ?>
          </table>

          <button class="btn btn-info btn-block data-barang-button" <?= $kode_barang == '' ? 'disabled' : ''; ?> data-kode-barang="<?= $kode_barang; ?>" data-nama-barang="<?= $nama_barang; ?>">
            Cari barang yang sama berdasarkan kode barang
          </button>
        </div>
      </div>
      <div class="col"></div>
    </div>
    <!-- END nav -->

  </section>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pengajuan Pemindahan Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= session()->get('blocked') && session()->get('blocked') > 3 ? '/' : '/data_abs/pengajuan_pemindahan'; ?>" id="formulirDataAbsPengajuanPemindahan" method="post">

          <div class="row">
            <div class="col-12">
              <?php
              $validationNumber = 0;
              $dataLabel = ['Kode Satker', 'Kode Barang', 'NUP', 'Nama Barang', 'Merek'];
              ?>
              <?php foreach ($array as $key => $value) : ?>
                <?php if ($validationNumber == 5) : ?>
                  <hr style="border-top: 3px dotted rgba(0, 0, 0, 0.5);" />
                <?php break;
                endif; ?>
                <?php if (in_array($key, $dataLabel)) :
                  $validationNumber++;
                ?>
                  <div class="px-3"><span><?= $key; ?></span> : <strong><?= $value; ?></strong><br /></div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="hidden" name="id_abs" value="<?= $getData['id_abs_bangunan']; ?>">
              <label for="username">Username anda</label>
              <input id="username" class="form-control form-control-sm" type="text" name="username" value="<?= session()->get('username') ? session()->get('username') : ''; ?>" required>
            </div>
            <div class="form-group">
              <label>Wilayah Kerja</label>
              <div class="row">
                <div class="col-12">
                  <input readonly type="text" class="form-control form-control-sm bg-secondarys" readonly name="data_asal[]" value="<?= $getData['unit_kerja'] ? $getData['unit_kerja'] : 'Tidak diketahui'; ?>">
                </div>
                <div class="col-1 my-2">Ke</div>
                <div class="col my-2">
                  <select name="data_tujuan[]" class="from-control select2" style="width: 100%;" required>
                    <option value="" <?= $getData['unit_kerja'] ? '' : 'selected'; ?>>Tidak diketahui</option>
                    <?php foreach ($unit_kerja as $data) : ?>
                      <option value="<?= $data['id_unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Lantai</label>
              <div class="row">
                <div class="col">
                  <input readonly type="text" class="form-control form-control-sm bg-secondarys" readonly name="data_asal[]" value="<?= $getData['lantai'] ? $getData['lantai'] : 'Tidak diketahui'; ?>">
                </div>
                <div class="col-1">Ke</div>
                <div class="col">
                  <select name="data_tujuan[]" class="from-control select2" style="width: 100%;" required>
                    <option value="" <?= $getData['lantai'] ? '' : 'selected'; ?>>Tidak diketahui</option>
                    <?php foreach ($lantai as $data) : ?>
                      <option value="<?= $data['id_abs_lantai']; ?>"><?= $data['lantai']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Ruangan</label>
              <div class="row">
                <div class="col">
                  <input readonly type="text" class="form-control form-control-sm bg-secondarys" readonly name="data_asal[]" value="<?= $getData['ruang'] ? $getData['ruang'] : 'Tidak diketahui'; ?>">
                </div>
                <div class="col-1">Ke</div>
                <div class="col">
                  <select name="data_tujuan[]" class="from-control select2" style="width: 100%;" required>
                    <option value="" <?= $getData['ruang'] ? '' : 'selected'; ?>>Tidak diketahui</option>
                    <?php foreach ($ruang as $data) : ?>
                      <option value="<?= $data['id_abs_ruang']; ?>"><?= $data['ruang']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" <?= session()->get('blocked') && session()->get('blocked') > 3 ? 'disabled' : ''; ?>><?= session()->get('blocked') && session()->get('blocked') > 3 ? '<i class="fa fa-paper-plane"></i> Blocked' : '<i class="fa fa-paper-plane"></i> Kirim'; ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="examplePemeliharaanModal" tabindex="-1" aria-labelledby="examplePemeliharaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="examplePemeliharaanModalLabel">Pengajuan Pemeliharaan Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= session()->get('blocked') && session()->get('blocked') > 3 ? '/' : '/data_abs/pengajuan_pemeliharaan'; ?>" id="formulirDataAbsPengajuanPemeliharaan" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-8">
                <?php
                $validationNumber = 0;
                $dataLabel = ['Kode Satker', 'Kode Barang', 'NUP', 'Nama Barang', 'Merek'];
                ?>
                <?php foreach ($array as $key => $value) : ?>
                  <?php if ($validationNumber == 5) : ?>
                    <hr style="border-top: 3px dotted rgba(0, 0, 0, 0.5);" />
                  <?php break;
                  endif; ?>
                  <?php if (in_array($key, $dataLabel)) :
                    $validationNumber++;
                  ?>
                    <span><?= $key; ?></span> : <strong><?= $value; ?></strong><br />
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="form-group">
              <input type="hidden" name="id_abs" value="<?= $getData['id_abs_bangunan']; ?>">
              <label for="username">Username anda</label>
              <input id="username" class="form-control form-control-sm" type="text" name="username" value="<?= session()->get('username') ? session()->get('username') : ''; ?>" required>
            </div>

            <div class="form-group">
              <label class="mb-0">Keterangan pengajuan</label><br>
              <small class="text-danger">Tuliskan keterangan seperti alasan pemeliharaan beserta bukti foto dan lainnya dengan jelas</small>
              <textarea name="keterangan" class="summernote" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" <?= session()->get('blocked') && session()->get('blocked') > 3 ? 'disabled' : ''; ?>><?= session()->get('blocked') && session()->get('blocked') > 3 ? '<i class="fa fa-paper-plane"></i> Blocked' : '<i class="fa fa-paper-plane"></i> Kirim'; ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="/website-menu-12/js/jquery.min.js"></script>
  <script src="/website-menu-12/js/popper.js"></script>
  <script src="/website-menu-12/js/bootstrap.min.js"></script>
  <script src="/website-menu-12/js/main.js"></script>
  <!-- Select2 -->
  <script src="/plugins/select2/js/select2.full.min.js"></script>
  <!-- Sweetalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Summernote -->
  <script src="/plugins/summernote/summernote-bs4.min.js"></script>

  <script>
    $(function() {
      $('.summernote').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true, // set focus to editable area after initializing summernote
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['picture']],
          ['view', ['fullscreen']],
          ['help', ['help']]
        ],
        onImageUpload: function(files, editor, $editable) {
          sendFile(files[0], editor, $editable);
        }
      });

      function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);
        $.ajax({
          url: "/data_abs/abs_image/uploader",
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          type: 'POST',
          success: function(data) {
            alert(data);
            $('.summernote').summernote("insertImage", data, 'filename');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
          }
        });
      }
    });
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    $(document).ready(function() {
      $('.select2').select2()
    });
    $(document).on('click', '.data-barang-button', function() {

      var formData = {
        kode_barang: $(this).data('kode-barang'),
        nama_barang: $(this).data('nama-barang'),
      };

      $.ajax({
        url: `/data_abs`,
        method: 'post',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          $('.data-barang').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
        },
        success: function(response) {
          if (response.data) {
            $('.data-barang').html(response.data);
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      });
    })

    $(document).on('submit', '#formulirDataAbsPengajuanPemindahan, #formulirDataAbsPengajuanPemeliharaan', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      var button = $(this).find('button[type="submit"]');

      $.ajax({
        url: $(this).attr('action'),
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $(button).attr('disabled', true);
          $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold'>Loading...</span>`)
        },
        success: function(response) {
          // console.log(response.cekData)
          if (response.data == 'sukses') {
            if (response.pesan == 'Ditolak') {
              Toast.fire({
                icon: 'warning',
                text: 'Perminataan anda ditolak!'
              })
              $(button).attr('disabled', false)
              $(button).html('<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim</button>')
            } else {
              Toast.fire({
                icon: 'success',
                text: 'Perminataan diterima!'
              })
              setTimeout(function() {
                location.reload();
              }, 2000);
            }
          } else if (response.data == 'gagal') {
            Toast.fire({
              icon: 'warning',
              text: 'Perminataan yang anda ajukan sudah terpenuhi!'
            })
            $(button).attr('disabled', false)
            $(button).html('<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim</button>')
          } else {
            Toast.fire({
              icon: 'error',
              text: 'Permintaan gagal diproses!'
            })
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({
            text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          })
        }
      });
    });
  </script>

</body>

</html>