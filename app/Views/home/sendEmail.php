<div class="card card-danger direct-chat direct-chat-danger m-0 mr-3">
  <div class="card-header">
    <h3 class="card-title">Kirim Pesan</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body px-3">
    <form id="sendMessagesToMail" action="/home/ajax/sendMessagesToEmail" method="post" enctype="multipart/form-data">
      <div class="form-group m-0">
        <input type="text" class="form-control" name="email" style="border-top: unset; border-right: unset; border-left: unset;" placeholder="Kepada" value="<?= $email; ?>">
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="subjek" style="border-top: unset; border-right: unset; border-left: unset;" placeholder="Subjek">
      </div>
      <div class="form-group">
        <textarea class="form-control" name="isi" style="border: unset;" rows="7" placeholder="Isi Pesan"></textarea>
      </div>
      <div class="form-group">
        <textarea name="isiBlank" style="display: none;">


</textarea>
        <textarea readonly class="form-control" name="isiAdd" style="border-top: unset; border-right: unset; border-left: unset; resize: none;" rows="2" placeholder="Isi Pesan">
Pengirim,
<?= session()->get("nama"); ?></textarea>
      </div>
      <div class="form-group tambahLampiran"></div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-share"></i> Kirim</button>
      <label class="file-button ml-3" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Lampirkan File">
        <input type="file" class="autoUploadFile" name="autoUploadFile" style="display: none;">
        <i class="fas fa-paperclip autoUploadFileIcon"></i>
      </label>
    </form>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
  </div>
  <!-- /.card-footer-->
</div>