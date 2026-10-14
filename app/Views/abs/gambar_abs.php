<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header"></div>
      <div class="card-body">
        <table id="tableWithoutExporting" class="table table-bordered table-stripped table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Kode Barang</th>
              <th>Jumlah Barang <br><small><sup>(gambar yang sama)</sup></small></th>
              <th style="width: 1%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = 1;
            foreach ($getData as $data) :
              $dataBarang = "";
              $array = unserialize($data['data_barang']);
              foreach ($array as $key => $value) {
                if ($key == "Nama Barang") $dataBarang = $value;
              }
            ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td>
                  <img src="/dist/img/abs/<?= $data['image']; ?>" alt="Gambar" id="preview<?= preg_replace("/[^a-zA-Z0-9]/", "", $data['kode_inventaris'] . $data['id_abs_image']); ?>" class="img-thumbnail" style="width: 150px;">
                </td>
                <td><?= $data['kode_inventaris']; ?> <br /><strong><?= $dataBarang; ?></strong></td>
                <td><?= $data['total']; ?></td>
                <td>
                  <form class="formChangeImage" action="/abs/saveImage" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <div class="btn-group-vertical btn-group-sm">
                        <button type="button" class="btn btn-warning" onclick="viewGaleryABS('<?= preg_replace('/[^a-zA-Z0-9]/', '', $data['kode_inventaris'] . $data['id_abs_image']); ?>')"><i class="fas fa-edit"></i></button>
                        <label type="button" class="btn btn-secondary" onclick="getElement('<?= preg_replace('/[^a-zA-Z0-9]/', '', $data['kode_inventaris'] . $data['id_abs_image']); ?>')" for="absImage<?= preg_replace("/[^a-zA-Z0-9]/", "", $data['kode_inventaris'] . $data['id_abs_image']); ?>"><i class="fas fa-upload"></i></label>
                        <button type="submit" class="btn btn-success" disabled id="button<?= preg_replace("/[^a-zA-Z0-9]/", "", $data['kode_inventaris'] . $data['id_abs_image']); ?>"><i class="fas fa-save"></i> Simpan</button>
                      </div>
                      <input type="hidden" name="kode" value="<?= $data['kode_inventaris']; ?>">
                      <input type="hidden" name="id" id="id<?= preg_replace("/[^a-zA-Z0-9]/", "", $data['kode_inventaris'] . $data['id_abs_image']); ?>" value="<?= $data['id_abs_image']; ?>">
                      <input type="file" name="image" onchange="previewImage(event)" class="d-none custom-file-input form-control" id="absImage<?= preg_replace("/[^a-zA-Z0-9]/", "", $data['kode_inventaris'] . $data['id_abs_image']); ?>" accept="image/*">
                    </div>
                  </form>

                </td>
              </tr>

            <?php
            endforeach; ?>
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
    $("#" + $elementID).val('')

    var reader = new FileReader();
    reader.onload = function() {
      var imgElement = document.getElementById($element);
      imgElement.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
    $("#" + $elementButton).attr('disabled', false)
  }

  function viewGaleryABS(element) {
    getElement(element)
    $.ajax({
      url: '/abs/getImage',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show()
      },
      success: function(response) {
        if (response.data) {
          $("#absImageGalery").find(".modal-body").html(response.data)
          $("#absImageGalery").modal('show');
        }
        $('#overlay').hide()
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Oops... ERROR SINTAKS',
          text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
          // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
        })
      }
    });
  }

  $(document).on('click', '.setImageAbs', function() {
    $('#' + $elementID).val($(this).data('id'))
    $("#" + $element).attr("src", '/dist/img/abs/' + $(this).data('image'))

    $("#" + $elementButton).attr('disabled', false)
  });

  $(document).on('submit', '.formChangeImage', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    var button = $(this).find('button[type="submit"]')

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
        $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
        $("#overlay").show()
      },
      success: function(response) {
        if (response.data) {
          $("#overlay").hide()
          Toast.fire({
            icon: "success",
            text: "Gambar berhasil diperbaharui"
          })
          $(button).html('<i class="fas fa-save"></i> Simpan')
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      }
    });
  })
</script>

<?= $this->endSection(); ?>