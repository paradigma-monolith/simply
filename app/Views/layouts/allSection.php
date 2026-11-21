<script>
  $(document).ready(function() {
    let segmentNow = "";
    const MENU_CACHE_TTL_MS = 5 * 60 * 1000;
    const isManagePage = window.location.pathname.indexOf('/setLink') !== -1;

    <?php if (isset($segmentNow) && $segmentNow != ""): ?>
      segmentNow = '<?= esc($segmentNow, 'js'); ?>';
      setTimeout(function() {
        $('.content-header, .main-header, .main-footer').slideUp('slow');
        $('body').addClass("sidebar-collapse");
      }, 3000);
    <?php endif; ?>

    function readMenuCache(key) {
      if (isManagePage) return null;
      try {
        const raw = sessionStorage.getItem(key);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        if (!parsed || !Array.isArray(parsed.data) || (Date.now() - parsed.ts) > MENU_CACHE_TTL_MS) {
          return null;
        }
        return parsed.data;
      } catch (e) {
        return null;
      }
    }

    function writeMenuCache(key, data) {
      try {
        sessionStorage.setItem(key, JSON.stringify({ ts: Date.now(), data: data }));
      } catch (e) {}
    }

    function renderIframeMenu(items) {
      if (items && items.length > 0) {
        let html = '';
        items.forEach(function(item) {
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
    }

    function renderPortalLinkMenu(items) {
      if (items && items.length > 0) {
        let html = '';
        items.forEach(function(item) {
          html += `
            <li class="nav-item">
              <a href="${item.link}" target="_blank" rel="noopener noreferrer" class="nav-link">
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
    }

    const cachedIframe = readMenuCache('simply_sidebar_iframe_v1');
    if (cachedIframe !== null) {
      renderIframeMenu(cachedIframe);
    } else {
      $.ajax({
        url: '/iframe/get/menu',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          const items = (response && Array.isArray(response.data)) ? response.data : [];
          writeMenuCache('simply_sidebar_iframe_v1', items);
          renderIframeMenu(items);
        },
        error: function() {
          $('.iframe-get-menu').html('<p class="nav-icon">Gagal memuat data.</p>');
        }
      });
    }

    const cachedPortal = readMenuCache('simply_sidebar_portal_v1');
    if (cachedPortal !== null) {
      renderPortalLinkMenu(cachedPortal);
    } else {
      $.ajax({
        url: '/portal_link/get/menu',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          const items = (response && Array.isArray(response.data)) ? response.data : [];
          writeMenuCache('simply_sidebar_portal_v1', items);
          renderPortalLinkMenu(items);
        },
        error: function() {
          $('.portal-link-get-menu').html('<p class="nav-icon">Gagal memuat data.</p>');
        }
      });
    }
  });
</script>
