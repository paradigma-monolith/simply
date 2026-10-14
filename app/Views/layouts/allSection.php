<script>
  $(document).ready(function() {
    let segmentNow = "";

    <?php if (isset($segmentNow) && $segmentNow != ""): ?>
      segmentNow = '<?= $segmentNow; ?>'
      setTimeout(function() {
        $('.content-header, .main-header, .main-footer').slideUp('slow');
        $('body').addClass("sidebar-collapse");
      }, 3000);
    <?php endif; ?>

    $.ajax({
      url: '/iframe/get/menu',
      method: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('.iframe-get-menu').html(
          "<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>"
        );
      },
      success: function(response) {
        if (response.data && response.data.length > 0) {
          let html = '';

          response.data.forEach(function(item) {
            // Cek apakah segmentNow sama dengan item.segment
            let isActive = (item.segment === segmentNow) ? 'active' : '';

            html += `
              <li class="nav-item">
                <a href="/iframe/set/${item.segment}" class="nav-link ${isActive}">
                  <i class="fas fa-share-alt-square nav-icon"></i>
                  <p>${item.sidebar}</p>
                </a>
              </li>
            `;
          });

          $('.iframe-get-menu').html(html);
        } else {
          $('.iframe-get-menu').html('');
        }
      },
      error: function() {
        $('.iframe-get-menu').html('<p class="nav-icon">Gagal memuat data.</p>');
      }
    });

    // Ambil data portal link
    $.ajax({
      url: '/portal_link/get/menu',
      method: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('.portal-link-get-menu').html(
          "<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>"
        );
      },
      success: function(response) {
        if (response.data && response.data.length > 0) {
          let html = '';

          response.data.forEach(function(item) {
            html += `
              <li class="nav-item">
                <a href="${item.link}" target="_blank" class="nav-link">
                  <i class="fas fa-location-arrow nav-icon"></i>
                  <p>${item.title}</p>
                </a>
              </li>
            `;
          });

          $('.portal-link-get-menu').html(html);
        } else {
          $('.portal-link-get-menu').html('');
        }
      },
      error: function() {
        $('.portal-link-get-menu').html('<p class="nav-icon">Gagal memuat data.</p>');
      }
    });
  });
</script>