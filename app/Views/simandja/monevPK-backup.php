<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
          <?php foreach($getData as $key => $data): 
                $isNext = isset($getData[$key + 1]);
            ?>
            <a target="_blank" href="<?= $data['link']; ?>" class="<?= $isNext ? 'disabled' : ''; ?> btn btn-info m-1">
                <i class="fas fa-link"></i> <?= $data['keterangan']; ?>
            </a>
          <?php endforeach; ?>
          <div class="float-right">
            <button class="btn btn-primary tambahDataTahun"><i class="fas fa-plus"></i> Tambah Data</button>
          </div>      
      </div>
      
      <div class="card-footer">
        <iframe class="lazyIframe" src="" data-src="<?= $getData[count($getData) - 1]['link']; ?>" title="MONEF" style="width:100%; height:1200px;" loading="lazy"></iframe>
      </div>
    </div>
  </div>
</section>

<script>
    $(document).on("click", ".tambahDataTahun", function(){
        var $modal = $("#modal-md");
        var activeMenu = "<?= $activeMenu ?>";

        $.ajax({
            url: '/simandja/tambahDataTahun/monevpk',
            method: 'get',
            dataType: 'json',
            beforeSend: function() {
                $("#overlay").show();
            },
            success: function(response) {
              if (response.data) {
                  $modal.find(".modal-body").html(response.data);
                  $modal.find("#kategori").val(activeMenu);
                  $modal.modal('show');
              }
            },error: function(xhr, ajaxOptions, thrownError) {
              Swal.fire({
                  'text' : xhr.status + "\n" + xhr.responseText + "\n" + thrownError
              })
            },
            complete: function() {
                $("#overlay").hide();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var totalIframes = $('.lazyIframe').length;
        var loadedIframes = 0;

        function loadIframe(iframe) {
            var lazySrcIframe = $(iframe).data('src');
            $(iframe).attr('src', lazySrcIframe);

            loadedIframes++;

            if (loadedIframes === totalIframes) {
                console.log('Semua iframe telah dimuat.');
            }
        }

        var observer = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    loadIframe(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });

        $('.lazyIframe').each(function() {
            observer.observe(this);
        });
    });
</script>
<?= $this->endSection(); ?>