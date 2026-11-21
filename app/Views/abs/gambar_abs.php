<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$rows = $getData ?? [];
$totalGroups = count($rows);
?>

<style>
  .abs-img-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .abs-img-head {
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .abs-img-head-title {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
  }
  .abs-img-search-wrap {
    position: relative;
    min-width: 240px;
    max-width: 310px;
    flex: 1 1 240px;
  }
  .abs-img-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.76rem;
    pointer-events: none;
  }
  .abs-img-search-input {
    width: 100%;
    height: 31px;
    padding: 4px 12px 4px 30px;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    font-size: 0.77rem;
    color: #1e293b;
    background: #ffffff;
    outline: none;
  }
  .abs-img-search-input:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  #tableAbsGambar thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e2e8f0;
    vertical-align: middle;
  }
  #tableAbsGambar tbody td {
    font-size: 0.81rem;
    vertical-align: middle;
    color: #1e293b;
  }
  .abs-thumb-box {
    width: 110px;
    height: 82px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    object-fit: cover;
  }
  .abs-cnt-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #99f6e4;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="abs-img-card">
      <div class="abs-img-head">
        <div class="d-flex align-items-center" style="gap: 10px;">
          <span class="abs-cnt-pill">
            <i class="fas fa-images"></i> <?= number_format($totalGroups, 0, ',', '.'); ?> Kelompok Gambar Kode Barang
          </span>
        </div>
        <div class="abs-img-search-wrap">
          <i class="fas fa-search abs-img-search-icon"></i>
          <input type="search" id="absGambarSearch" class="abs-img-search-input" placeholder="Cari kode inventaris atau nama barang..." autocomplete="off">
        </div>
      </div>

      <div class="table-responsive">
        <table id="tableAbsGambar" class="table table-bordered table-hover mb-0" style="width: 100%;">
          <thead>
            <tr>
              <th width="5%" class="text-center">No</th>
              <th width="16%" class="text-center">Pratinjau Gambar</th>
              <th>Kode &amp; Nama Barang</th>
              <th width="16%" class="text-center">Jumlah Barang<br><small class="text-muted font-weight-normal">(gambar sama)</small></th>
              <th width="22%" class="text-center">Aksi Perubahan Gambar</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = 1; ?>
            <?php foreach ($rows as $data) : ?>
              <?php
              $array = \App\Models\AbsLokasiModel::safeUnserialize($data['data_barang'] ?? '');
              $dataBarang = $array['Nama Barang'] ?? ($array ? reset($array) : '-');
              $kodeInv = (string) ($data['kode_inventaris'] ?? '');
              $idImg = (string) ($data['id_abs_image'] ?? '1');
              $imgFile = !empty($data['image']) ? $data['image'] : 'default.png';
              $elKey = preg_replace('/[^a-zA-Z0-9]/', '', $kodeInv . $idImg) . '_' . $nomor;
              ?>
              <tr>
                <td class="text-center font-weight-bold"><?= $nomor++; ?></td>
                <td class="text-center">
                  <img src="/dist/img/abs/<?= esc($imgFile, 'attr'); ?>"
                       alt="Gambar"
                       loading="lazy"
                       onerror="this.onerror=null;this.src='/dist/img/abs/default.png';"
                       id="preview<?= $elKey; ?>"
                       class="abs-thumb-box">
                </td>
                <td>
                  <div class="font-weight-bold" style="color: #0f172a; font-size: 0.85rem;"><?= esc((string) $dataBarang); ?></div>
                  <code class="d-inline-block mt-1 px-2 py-1 rounded" style="background:#f1f5f9;color:#0f766e;font-size:0.74rem;">
                    <?= esc($kodeInv ?: '-'); ?>
                  </code>
                </td>
                <td class="text-center">
                  <span class="abs-cnt-pill">
                    <i class="fas fa-boxes"></i> <?= (int) ($data['total'] ?? 0); ?> unit
                  </span>
                </td>
                <td class="text-center">
                  <form class="formChangeImage m-0" action="/abs/saveImage" method="post" enctype="multipart/form-data">
                    <div class="d-inline-flex flex-wrap align-items-center justify-content-center" style="gap: 5px;">
                      <button type="button"
                              class="btn btn-xs btn-warning font-weight-bold px-2 py-1"
                              onclick="viewGaleryABS('<?= $elKey; ?>')"
                              title="Pilih dari Galeri">
                        <i class="fas fa-photo-video mr-1"></i> Galeri
                      </button>
                      <label class="btn btn-xs btn-secondary font-weight-bold px-2 py-1 mb-0"
                             style="cursor:pointer;"
                             onclick="getElement('<?= $elKey; ?>')"
                             for="absImage<?= $elKey; ?>"
                             title="Unggah Gambar Baru">
                        <i class="fas fa-upload mr-1"></i> Upload
                      </label>
                      <button type="submit"
                              class="btn btn-xs btn-success font-weight-bold px-2 py-1"
                              disabled
                              id="button<?= $elKey; ?>">
                        <i class="fas fa-save mr-1"></i> Simpan
                      </button>
                    </div>
                    <input type="hidden" name="kode" value="<?= esc($kodeInv, 'attr'); ?>">
                    <input type="hidden" name="id" id="id<?= $elKey; ?>" value="<?= esc($idImg, 'attr'); ?>">
                    <input type="file"
                           name="image"
                           onchange="previewImage(event)"
                           class="d-none custom-file-input form-control"
                           id="absImage<?= $elKey; ?>"
                           accept="image/*">
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<script>
  var $element;
  var $elementID;
  var $elementButton;

  function getElement(element) {
    $element = "preview" + element;
    $elementID = "id" + element;
    $elementButton = "button" + element;
  }

  function previewImage(event) {
    if (!event.target.files || !event.target.files[0]) return;
    $("#" + $elementID).val('');

    var reader = new FileReader();
    reader.onload = function() {
      var imgElement = document.getElementById($element);
      if (imgElement) imgElement.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    $("#" + $elementButton).attr('disabled', false);
  }

  function viewGaleryABS(element) {
    getElement(element);
    $.ajax({
      url: '/abs/getImage',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show();
      },
      success: function(response) {
        if (response && response.data) {
          $("#absImageGalery").find(".modal-body").html(response.data);
          $("#absImageGalery").modal('show');
        }
        $('#overlay').hide();
      },
      error: function() {
        $('#overlay').hide();
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Gagal memuat galeri',
          text: 'Terjadi kesalahan saat mengambil daftar gambar.'
        });
      }
    });
  }

  $(function() {
    if (typeof $.fn.DataTable !== 'undefined') {
      var tbl = $('#tableAbsGambar').DataTable({
        dom: "t<'row mt-2 px-3 pb-2 align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        paging: true,
        pageLength: 20,
        lengthChange: false,
        searching: true,
        autoWidth: false,
        deferRender: true,
        columnDefs: [
          { targets: [1, 4], orderable: false }
        ],
        language: {
          info: 'Menampilkan _START_–_END_ dari _TOTAL_ kelompok barang',
          infoEmpty: 'Tidak ada data gambar',
          zeroRecords: 'Tidak ditemukan kelompok barang yang cocok',
          paginate: { previous: '&laquo;', next: '&raquo;' }
        }
      });

      $('#absGambarSearch').off('input keyup search').on('input keyup search', function() {
        tbl.search(this.value).draw();
      });
    }
  });

  $(document).off('click.absImgPage', '.setImageAbs').on('click.absImgPage', '.setImageAbs', function() {
    if ($elementID && $('#' + $elementID).length) {
      $('#' + $elementID).val($(this).data('id'));
      $("#" + $element).attr("src", '/dist/img/abs/' + $(this).data('image'));
      $("#" + $elementButton).attr('disabled', false);
      $("#absImageGalery").modal("hide");
    }
  });

  $(document).off('submit.absImgForm', '.formChangeImage').on('submit.absImgForm', '.formChangeImage', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    var button = $(this).find('button[type="submit"]');

    $.ajax({
      url: $(this).attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $(button).attr('disabled', true);
        $(button).html('<i class="fas fa-sync-alt fa-spin mr-1"></i> Menyimpan...');
      },
      success: function(response) {
        if (response && response.data) {
          Toast.fire({
            icon: "success",
            text: "Gambar berhasil diperbaharui"
          });
          $(button).html('<i class="fas fa-save mr-1"></i> Simpan');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $(button).attr('disabled', false);
        $(button).html('<i class="fas fa-save mr-1"></i> Simpan');
        Swal.fire({
          icon: 'error',
          text: xhr.status + "\n" + thrownError
        });
      }
    });
  });
</script>

<?= $this->endSection(); ?>
