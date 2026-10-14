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
</style>
<form id="formUpdateAgenda-Edit" action="/home/ajax/agenda" method="post">
  <input type="hidden" name="id_agenda_user" value="<?= $getData['id_agenda_user']; ?>">
  <div class="row">
    <div class="col-lg-7">
      <div class="form-group">
        <label for="">Topik</label>
        <input type="text" name="topik" class="form-control" value="<?= $getData['topik']; ?>">
      </div>
      <div class="form-group">
        <label for="">Deskripsi</label>
        <textarea name="deskripsi" class="summernote"><?= $getData['deskripsi']; ?></textarea>
      </div>
      <div class="form-group">
        <label for="">Sebarkan Kepada</label>
        <button type="button" class="btn btn-secondary btn-sm float-right p-0 px-2" id="allUserAgenda">Pilih Semua</button>
        <button type="button" class="btn btn-secondary btn-sm float-right p-0 px-2 mx-1" id="removeUserAgenda">Clear</button>
        <select multiple="multiple" required name="peruntukan[]" class="select2" style="width: 100%;">
          <?php
          $peruntukan = unserialize($getData['peruntukan']);
          ?>
          <?php foreach ($user as $data) : ?>
            <option value="<?= $data['id_user']; ?>" <?= in_array($data['id_user'], $peruntukan) ? 'selected' : ''; ?> <?= $data['username'] == session()->get('username') ? 'disabled="disabled"' : ''; ?>><?= $data['nama']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="col-lg-5">
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
                <input type="checkbox" name="notif" id="myCheckbox" <?= $getData['tgl_notif_akhir'] != null ? 'checked' : ''; ?> />
              </span>
            </div>
            <input type="text" name="tgl_notif" class="form-control range-date" <?= $getData['tgl_notif_akhir'] == null ? 'disabled' : ''; ?> />
          </div>
        </div>
        <div class="form-group">
          <label for="">Warna Agenda</label>
          <div class="input-group mb-2">
            <input type="color" name="warna_agenda" id="colorPickerEdit" class="form-control" value="<?= isset($getData['warna_agenda']) ? $getData['warna_agenda'] : '#0073b7'; ?>" style="height: 38px;">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-palette"></i>
              </span>
            </div>
          </div>
          <div class="color-preset-buttons mb-2">
             <small class="text-muted d-block mb-1">Warna Utama:</small>
             <button type="button" class="btn color-btn-edit" data-color="#0073b7" style="background-color: #0073b7; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#dc3545" style="background-color: #dc3545; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#28a745" style="background-color: #28a745; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#ffc107" style="background-color: #ffc107; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#fd7e14" style="background-color: #fd7e14; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#6f42c1" style="background-color: #6f42c1; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#e83e8c" style="background-color: #e83e8c; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#20c997" style="background-color: #20c997; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#6c757d" style="background-color: #6c757d; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
             <button type="button" class="btn color-btn-edit" data-color="#343a40" style="background-color: #343a40; width: 30px; height: 30px; margin: 2px; border-radius: 50%; border: 2px solid transparent;"></button>
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
        <i class="fas fa-share"></i> Perbaharui data
      </button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#myCheckbox').change(function() {
      if ($(this).is(':checked')) {
        $('.range-date').attr('disabled', false)
      } else {
        $('.range-date').attr('disabled', true)
      }
    });
  });
</script>
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
      ],
    });

    $('.datetime').daterangepicker({
      startDate: '<?= $getData['waktu_pelaksana_awal']; ?>',
      endDate: '<?= $getData['waktu_pelaksana_akhir']; ?>',
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    })
    <?php if ($getData['tgl_notif_akhir']) : ?>
      var startTanggal = '<?= $getData['tgl_notif_awal']; ?>'
      var akhirTanggal = '<?= $getData['tgl_notif_akhir']; ?>'
    <?php else : ?>
      var startTanggal = moment()
      var akhirTanggal = moment()
    <?php endif; ?>
    $('.range-date').daterangepicker({
      startDate: startTanggal,
      endDate: akhirTanggal,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
    $(".select2").select2();
  });
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

  // Handle color button clicks for edit form
  $(".color-btn-edit").click(function() {
    var selectedColor = $(this).data('color');
    $("#colorPickerEdit").val(selectedColor);
    
    // Remove active class from all buttons
    $(".color-btn-edit").removeClass('active');
    // Add active class to clicked button
    $(this).addClass('active');
  });

  // Set active color button based on current color value when form loads
  $(document).ready(function() {
    var currentColor = $("#colorPickerEdit").val();
    $(".color-btn-edit").each(function() {
      if ($(this).data('color') === currentColor) {
        $(this).addClass('active');
      }
    });
  });
</script>