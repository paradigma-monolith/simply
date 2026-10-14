<div class="row">
  <div class="col-sm-4">
    <form action="/sinadi/saveNotaDinas" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_peserta" value="<?= $id_peserta; ?>">
      <div class="input-group mb-3">
        <div class="custom-file">
          <input required type="file" class="custom-file-input" name="file" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" accept=".pdf">
          <label class="custom-file-label" for="inputGroupFile01" data-browse="pdf only">Upload nota dinas</label>
        </div>
      </div>
      <div class="form-check mb-3 d-none">
        <input class="form-check-input" type="checkbox" name="upload_all" value="1" id="uploadAllCheck" disabled>
        <label class="form-check-label" for="uploadAllCheck">
          Upload untuk semua peserta
        </label>
      </div>
      <button type="submit" class="btn btn-sm btn-success float-right mb-3">
        <i class="fas fa-save"></i> Simpan
      </button>
    </form>
  </div>
  <div class="col-sm-8">
    <?php if ($file != null && $file != '') : ?>
      <iframe src="/nota-dinas/<?= $file; ?>" frameborder="1" style="width: 100%; height: 500px;"></iframe>
    <?php else : ?>
    <!--https://drive.google.com/viewerng/viewer?embedded=true&url=<?//= base_url(); ?>-->
      <iframe src="/nota-dinas/blank.pdf" frameborder="1" style="width: 100%; height: 500px;"></iframe>
    <?php endif; ?>
  </div>
</div>

<script>
  $(function() {
    bsCustomFileInput.init();

    // Disable checkbox initially
    $('#uploadAllCheck').prop('disabled', true);

    // Enable checkbox only when file is selected
    $('#inputGroupFile01').on('change', function() {
        if ($(this).val()) {
            $('#uploadAllCheck').prop('disabled', false);
        } else {
            $('#uploadAllCheck').prop('disabled', true);
            $('#uploadAllCheck').prop('checked', false);
        }
    });
  });
</script>