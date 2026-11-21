<style>
  .abs-gal-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e2e8f0;
  }
  .abs-gal-pill {
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #334155;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .abs-gal-pill.active,
  .abs-gal-pill:hover {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff;
  }
  .abs-gal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
    gap: 10px;
    max-height: 520px;
    overflow-y: auto;
    padding: 4px;
  }
  .abs-gal-item {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 6px;
  }
  .abs-gal-item:hover {
    border-color: #0d9488;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 148, 136, 0.15);
  }
  .abs-gal-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 7px;
  }
</style>

<div>
  <div class="abs-gal-filters" id="absGalFilterBar">
    <button type="button" class="abs-gal-pill active" data-gal-filter="all">Semua Gambar (<?= count($gambar ?? []); ?>)</button>
    <?php foreach (($filterKategori ?? []) as $data) : ?>
      <button type="button" class="abs-gal-pill" data-gal-filter="<?= esc((string) $data['id_abs_jenis'], 'attr'); ?>">
        <?= esc((string) $data['jenis']); ?>
      </button>
    <?php endforeach; ?>
  </div>

  <div class="abs-gal-grid" id="absGalGrid">
    <?php foreach (($gambar ?? []) as $data) : ?>
      <a href="javascript:void(0)"
         data-image="<?= esc((string) $data['image'], 'attr'); ?>"
         data-id="<?= esc((string) $data['id_abs_image'], 'attr'); ?>"
         data-cats="<?= esc((string) ($data['data_jenis'] ?? ''), 'attr'); ?>"
         class="abs-gal-item setImageAbs">
        <img src="/dist/img/abs/<?= esc((string) $data['image'], 'attr'); ?>"
             loading="lazy"
             onerror="this.onerror=null;this.src='/dist/img/abs/default.png';"
             alt="Gambar Inventaris" />
      </a>
    <?php endforeach; ?>
  </div>
</div>

<script>
  $(function() {
    $('#absGalFilterBar').off('click', '.abs-gal-pill').on('click', '.abs-gal-pill', function() {
      $('#absGalFilterBar .abs-gal-pill').removeClass('active');
      $(this).addClass('active');
      var cat = String($(this).attr('data-gal-filter') || 'all');
      if (cat === 'all') {
        $('#absGalGrid .abs-gal-item').show();
      } else {
        $('#absGalGrid .abs-gal-item').each(function() {
          var itemCats = String($(this).attr('data-cats') || '').split(/\s*,\s*/);
          $(this).toggle(itemCats.indexOf(cat) !== -1);
        });
      }
    });
  });

  $(document).off('click.absGalleryModal', '.setImageAbs').on('click.absGalleryModal', '.setImageAbs', function() {
    if ($('input[name="id_abs_image"]').length) {
      $('input[name="id_abs_image"]').val($(this).data('id'));
      $("#preview").attr('src', '/dist/img/abs/' + $(this).data('image'));
    }
    $("#absImageGalery").modal("hide");
  });
</script>
