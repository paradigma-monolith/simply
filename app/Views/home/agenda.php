<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<style>
.color-btn.active, .color-btn-edit.active {
  border: 2px solid #fff !important;
  box-shadow: 0 0 0 2px #007bff !important;
  transform: scale(1.1);
}
.color-btn:hover, .color-btn-edit:hover {
  transform: scale(1.05);
  transition: transform 0.2s ease;
}

/* Agenda Table Scroll Styles */
.agenda-table-container {
  scrollbar-width: thin;
  scrollbar-color: #6c757d #f8f9fa;
}

.agenda-table-container::-webkit-scrollbar {
  width: 8px;
}

.agenda-table-container::-webkit-scrollbar-track {
  background: #f8f9fa;
  border-radius: 4px;
}

.agenda-table-container::-webkit-scrollbar-thumb {
  background: #6c757d;
  border-radius: 4px;
}

.agenda-table-container::-webkit-scrollbar-thumb:hover {
  background: #495057;
}

.agenda-table-container .table thead th {
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  font-size: 0.875rem;
}

.agenda-table-container .table tbody tr:hover {
  background-color: #f8f9fa;
}
</style>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-7">
        <div class="sticky-top mb-3">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Atur Agenda</h4>
            </div>
            <div class="card-body">
              <form id="formUpdateAgenda" action="/home/ajax/agenda" method="post">
                <div class="row">
                  <div class="col-7">
                    <div class="form-group">
                      <label for="">Topik</label>
                      <input type="text" name="topik" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="">Deskripsi</label>
                      <textarea name="deskripsi" class="summernote_no_media"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="">Sebarkan Kepada</label>
                      <button type="button" class="btn btn-secondary btn-sm float-right p-0 px-2" id="allUserAgenda">Pilih Semua</button>
                      <button type="button" class="btn btn-secondary btn-sm float-right p-0 px-2 mx-1" id="removeUserAgenda">Clear</button>
                      <select multiple="multiple" name="peruntukan[]" class="select2" style="width: 100%;">
                        <?php foreach ($user as $data) : ?>
                          <option value="<?= $data['id_user']; ?>" <?= $data['username'] == session()->get('username') ? 'selected disabled="disabled"' : ''; ?>><?= $data['nama']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-5">
                    <div class="jumbotron border">
                      <div class="form-group">
                        <label for="">Waktu Pelaksanaan</label>
                        <input type="text" name="waktu_pelaksanaan" class="form-control datetime" />
                      </div>
                      <div class="form-group">
                        <label for="">Tanggal Notif</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <input type="checkbox" name="notif" id="myCheckbox" checked />
                            </span>
                          </div>
                          <input type="text" name="tgl_notif" class="form-control range-date" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="">Warna Agenda</label>
                        <div class="input-group mb-2">
                          <input type="color" name="warna_agenda" id="colorPicker" class="form-control" value="#0073b7" style="height: 38px;">
                          <div class="input-group-append">
                            <span class="input-group-text">
                              <i class="fas fa-palette"></i>
                            </span>
                          </div>
                        </div>
                        <div class="color-preset-buttons mb-2">
                          <small class="text-muted d-block mb-1">Warna Utama:</small>
                          <button type="button" class="btn color-btn active" data-color="#0073b7" style="background-color: #0073b7; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#dc3545" style="background-color: #dc3545; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#28a745" style="background-color: #28a745; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#ffc107" style="background-color: #ffc107; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#fd7e14" style="background-color: #fd7e14; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#6f42c1" style="background-color: #6f42c1; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#e83e8c" style="background-color: #e83e8c; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#20c997" style="background-color: #20c997; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#6c757d" style="background-color: #6c757d; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                          <button type="button" class="btn color-btn" data-color="#343a40" style="background-color: #343a40; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
                        </div>
                        <small class="text-muted">Pilih warna untuk agenda ini di kalender</small>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-4">
                    <button class="float-right btn btn-success btn-sm btn-block">
                      <i class="fas fa-share"></i> Simpan
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card">
          <div class="card-header">
            Agenda Saya
          </div>
          <div class="card-body agenda_saya"></div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-12 getCallendarAgenda">

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>

<script>
  $(document).ready(function() {
    $('#myCheckbox').change(function() {
      if ($(this).is(':checked')) {
        $('.range-date').attr('disabled', false)
      } else {
        $('.range-date').attr('disabled', true)
      }
    });

    AgendaSaya();
    getCallendarAgenda();
  });
</script>

<script>
  $(document).on('submit', '#formUpdateAgenda, #formUpdateAgenda-Edit', function(e) {
    e.preventDefault()

    $(this).siblings("select").find("option").filter(function() {
      return $(this).attr('disabled') !== undefined;
    }).prop('selected', true);

    var formData = $(this).serialize();
    var button = $(this).find('button[type="submit"]');

    $.ajax({
      url: $(this).attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(button).attr('disabled', true);
        $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
      },
      success: function(response) {
        console.log(response)
        if (response.dataAgenda) {
          AgendaSaya();
          getCallendarAgenda();
          Toast.fire({
            icon: 'success',
            title: 'Data Agenda Diperbaharui...'
          })
        } else if (response.pesanAgenda) {
          Toast.fire({
            icon: 'warning',
            title: 'Data Agenda Sudah Ada...'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Data Agenda Gagal Diperbaharui...'
          })
        }
        $(button).attr('disabled', false)
        $(button).text('<i class="fas fa-share"></i> Simpan')
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'success',
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      }
    });
  })

  function getCallendarAgenda() {
    console.log('Loading calendar data...');
    $(".getCallendarAgenda").html('')
    $.ajax({
      url: '/home/getCallendarAgenda',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        console.log('Calendar data loaded successfully');
        if (response.data) {
          $(".getCallendarAgenda").html(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        console.error('Error loading calendar:', thrownError);
        console.error('Response:', xhr.responseText);
        $(".getCallendarAgenda").html('<div class="alert alert-danger">Error loading calendar: ' + thrownError + '</div>');
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        });
      }
    });
  }

  function AgendaSaya() {
    $.ajax({
      url: '/home/agenda/ajax/agenda_saya',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response.data) {
          $(".agenda_saya").html(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  }
</script>

<script>
  $("#allUserAgenda").click(function() {
    var select = $(this).siblings("select");

    $(this).siblings("select").select2('destroy');
    $(this).siblings("select").find("option").attr('selected', true);

    select.select2();
  })
  $("#removeUserAgenda").click(function() {
    var select = $(this).siblings("select");

    $(this).siblings("select").select2('destroy');
    $(this).siblings("select").find("option").attr('selected', false);

    $(this).siblings("select").find("option").filter(function() {
      return $(this).attr('disabled') !== undefined;
    }).prop('selected', true);

    select.select2();
  })

  // Handle color button clicks
  $(".color-btn").click(function() {
    var selectedColor = $(this).data('color');
    $("#colorPicker").val(selectedColor);
    
    // Remove active class from all buttons
    $(".color-btn").removeClass('active');
    // Add active class to clicked button
    $(this).addClass('active');
  });
</script>

<?= $this->endSection(); ?>