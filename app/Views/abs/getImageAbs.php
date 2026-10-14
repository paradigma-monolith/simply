<div>
  <div class="btn-group w-100 mb-2" style="overflow-x: scroll;">
    <a class="btn btn-info active" id="showAllABSImage" href="javascript:void(0)" data-filter="all"> Semua gambar </a>
    <?php foreach ($filterKategori as $data) : ?>
      <a class="btn btn-info" href="javascript:void(0)" data-filter="<?= $data['id_abs_jenis']; ?>"> <?= $data['jenis']; ?> </a>
    <?php endforeach; ?>
  </div>
  <div class="mb-2">
    <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Acak gambar </a>
    <div class="float-right">
      <select class="custom-select" style="width: auto;" data-sortOrder>
        <option value="index"> Sort by Position </option>
        <option value="sortData"> Sort by Custom Data </option>
      </select>
      <div class="btn-group">
        <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> Ascending </a>
        <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> Descending </a>
      </div>
    </div>
  </div>
</div>
<div>
  <div class="filter-container p-0 row" style="height: 543px;">
    <?php foreach ($gambar as $data) : ?>
      <div class="filtr-item col-sm-2" data-category="<?= $data['data_jenis']; ?>" data-sort="white sample">
        <a href="javascript:void(0)" data-image="<?= $data['image']; ?>" data-id="<?= $data['id_abs_image']; ?>" class="setImageAbs">
          <img src="/dist/img/abs/<?= $data['image']; ?>" style="width: 200px;" class="img-fluid mb-2" alt="Sample Gambar" />
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
  $(function() {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({
      gutterPixels: 3
    });
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })

  $(document).on('click', '.setImageAbs', function() {
    $('input[name="id_abs_image"]').val($(this).data('id'))
    $("#preview").attr('src', '/dist/img/abs/' + $(this).data('image'))
    $("#absImageGalery").modal("hide")
  })
</script>