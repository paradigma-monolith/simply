$(document).ready(function() {

  function getDashboardFilters() {
    return {
      id_wilayah_kerja: $('#filter-dashboard-wilker').val() || '',
      tanggal_mulai: $('#filter-dashboard-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-dashboard-tgl-selesai').val() || ''
    };
  }

  function getJentikFilters() {
    return {
      id_wilayah_kerja: $('#filter-jentik-wilker').val() || '',
      tanggal_mulai: $('#filter-jentik-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-jentik-tgl-selesai').val() || '',
      kondisi_musim: $('#filter-jentik-musim').val() || '',
      wilayah: $('#filter-jentik-wilayah').val() || '',
      status: $('#filter-jentik-status').val() || ''
    };
  }

  function getLalatFilters() {
    return {
      id_wilayah_kerja: $('#filter-lalat-wilker').val() || '',
      tanggal_mulai: $('#filter-lalat-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-lalat-tgl-selesai').val() || '',
      musim: $('#filter-lalat-musim').val() || '',
      status: $('#filter-lalat-status').val() || ''
    };
  }

  function getKecoaFilters() {
    return {
      id_wilayah_kerja: $('#filter-kecoa-wilker').val() || '',
      tanggal_mulai: $('#filter-kecoa-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-kecoa-tgl-selesai').val() || '',
      status: $('#filter-kecoa-status').val() || ''
    };
  }

  function getTikusFilters() {
    return {
      id_wilayah_kerja: $('#filter-tikus-wilker').val() || '',
      tanggal_mulai: $('#filter-tikus-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-tikus-tgl-selesai').val() || ''
    };
  }

  function getPinjalFilters() {
    return {
      id_wilayah_kerja: $('#filter-pinjal-wilker').val() || '',
      tanggal_mulai: $('#filter-pinjal-tgl-mulai').val() || '',
      tanggal_selesai: $('#filter-pinjal-tgl-selesai').val() || '',
      status_umum: $('#filter-pinjal-status-umum').val() || '',
      status_khusus: $('#filter-pinjal-status-khusus').val() || ''
    };
  }

  function checkFilterActive(params, badgeSelector) {
    let hasFilter = Object.values(params).some(x => x !== '' && x !== null && x !== undefined);
    if (hasFilter) {
      $(badgeSelector).removeClass('d-none');
    } else {
      $(badgeSelector).addClass('d-none');
    }
  }

  let filterDebounceTimers = {};
  function debounceFilter(key, callback, delay = 350) {
    clearTimeout(filterDebounceTimers[key]);
    filterDebounceTimers[key] = setTimeout(callback, delay);
  }

  function loadDashboard() {
    let params = getDashboardFilters();
    checkFilterActive(params, '.filter-badge-dashboard');

    let $wrap = $('.sivera_dashboard_wrap');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Dashboard SIVERA...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-dashboard').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_dashboard',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-dashboard').addClass('d-none');
      }
    });
  }
  
  function loadJentik() {
    let params = getJentikFilters();
    checkFilterActive(params, '.filter-badge-jentik');

    let $wrap = $('.sivera_daftar_jentik');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-success" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Data Jentik...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-jentik').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_jentik',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-jentik').addClass('d-none');
      }
    });
  }

  function loadLalat() {
    let params = getLalatFilters();
    checkFilterActive(params, '.filter-badge-lalat');

    let $wrap = $('.sivera_daftar_lalat');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-danger" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Data Lalat...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-lalat').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_lalat',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-lalat').addClass('d-none');
      }
    });
  }

  function loadKecoa() {
    let params = getKecoaFilters();
    checkFilterActive(params, '.filter-badge-kecoa');

    let $wrap = $('.sivera_daftar_kecoa');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-warning" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Data Kecoa...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-kecoa').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_kecoa',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-kecoa').addClass('d-none');
      }
    });
  }

  function loadTikus() {
    let params = getTikusFilters();
    checkFilterActive(params, '.filter-badge-tikus');

    let $wrap = $('.sivera_daftar_tikus');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-secondary" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Data Tikus...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-tikus').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_tikus',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-tikus').addClass('d-none');
      }
    });
  }

  function loadPinjal() {
    let params = getPinjalFilters();
    checkFilterActive(params, '.filter-badge-pinjal');

    let $wrap = $('.sivera_daftar_pinjal');
    if ($wrap.is(':empty')) {
      $wrap.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Memuat...</span></div><p class="mt-2 text-muted font-weight-bold">Memuat Data Pinjal...</p></div>');
    } else {
      $wrap.css({ 'opacity': '0.55', 'pointer-events': 'none', 'transition': 'opacity 0.2s ease' });
    }
    $('.filter-loading-pinjal').removeClass('d-none');

    $.ajax({
      url: '/prl/sivera_pinjal',
      type: 'GET',
      data: params,
      success: function(response) {
        $wrap.html(response);
      },
      complete: function() {
        $wrap.css({ 'opacity': '1', 'pointer-events': 'auto' });
        $('.filter-loading-pinjal').addClass('d-none');
      }
    });
  }

  // Initial load
  loadDashboard();
  
  // Load when tab is clicked
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { 
    let target = $(e.target).attr("href"); // activated tab
    if (target == '#sivera-dashboard') loadDashboard();
    if (target == '#jentik') loadJentik();
    if (target == '#lalat') loadLalat();
    if (target == '#kecoa') loadKecoa();
    if (target == '#tikus') loadTikus();
    if (target == '#pinjal') loadPinjal();
  });

  // Prevent default form submits on Enter
  $(document).on('submit', '#form-filter-dashboard, #form-filter-jentik, #form-filter-lalat, #form-filter-kecoa, #form-filter-tikus, #form-filter-pinjal', function(e) {
    e.preventDefault();
  });

  // Live Active Filter - Triggers automatically on any select change or date input change
  $(document).on('change', '#form-filter-dashboard select, #form-filter-dashboard input[type="date"]', function() {
    loadDashboard();
  });
  $(document).on('change', '#form-filter-jentik select, #form-filter-jentik input[type="date"]', function() {
    loadJentik();
  });
  $(document).on('change', '#form-filter-lalat select, #form-filter-lalat input[type="date"]', function() {
    loadLalat();
  });
  $(document).on('change', '#form-filter-kecoa select, #form-filter-kecoa input[type="date"]', function() {
    loadKecoa();
  });
  $(document).on('change', '#form-filter-tikus select, #form-filter-tikus input[type="date"]', function() {
    loadTikus();
  });
  $(document).on('change', '#form-filter-pinjal select, #form-filter-pinjal input[type="date"]', function() {
    loadPinjal();
  });

  // Debounced input for date fields (when typing keyboard dates)
  $(document).on('input', '#form-filter-dashboard input[type="date"]', function() {
    debounceFilter('dashboard', loadDashboard);
  });
  $(document).on('input', '#form-filter-jentik input[type="date"]', function() {
    debounceFilter('jentik', loadJentik);
  });
  $(document).on('input', '#form-filter-lalat input[type="date"]', function() {
    debounceFilter('lalat', loadLalat);
  });
  $(document).on('input', '#form-filter-kecoa input[type="date"]', function() {
    debounceFilter('kecoa', loadKecoa);
  });
  $(document).on('input', '#form-filter-tikus input[type="date"]', function() {
    debounceFilter('tikus', loadTikus);
  });
  $(document).on('input', '#form-filter-pinjal input[type="date"]', function() {
    debounceFilter('pinjal', loadPinjal);
  });

  // Handle Reset Buttons (resets form and immediately reloads active filter)
  $(document).on('click', '.btn-reset-dashboard', function() {
    $('#form-filter-dashboard')[0].reset();
    loadDashboard();
  });
  $(document).on('click', '.btn-reset-jentik', function() {
    $('#form-filter-jentik')[0].reset();
    loadJentik();
  });
  $(document).on('click', '.btn-reset-lalat', function() {
    $('#form-filter-lalat')[0].reset();
    loadLalat();
  });
  $(document).on('click', '.btn-reset-kecoa', function() {
    $('#form-filter-kecoa')[0].reset();
    loadKecoa();
  });
  $(document).on('click', '.btn-reset-tikus', function() {
    $('#form-filter-tikus')[0].reset();
    loadTikus();
  });
  $(document).on('click', '.btn-reset-pinjal', function() {
    $('#form-filter-pinjal')[0].reset();
    loadPinjal();
  });

  // Handle Tambah Buttons (Reset Form)
  $(document).on('click', '.btn-tambah-jentik', function() {
    if($('#form-jentik').length) $('#form-jentik')[0].reset();
    $('#id_jentik_form').val('');
    $('#modal-jentik-title').text('Tambah Data Jentik');
    $('#modal-jentik-header').removeClass('bg-info').addClass('bg-success');
    $('#form-jentik input[type="number"]').trigger('input');
    $('#chk_lainnya_jentik').prop('checked', false).trigger('change');
    $('#modal-jentik').modal('show');
  });

  $(document).on('click', '.btn-tambah-lalat', function() {
    if($('#form-lalat').length) $('#form-lalat')[0].reset();
    $('#id_lalat_form').val('');
    $('#modal-lalat-title').text('Tambah Data Lalat');
    $('#modal-lalat-header').removeClass('bg-info').addClass('bg-success');
    
    // Reset to 1 clean lokasi item
    let firstItem = (typeof window.getCleanLokasiItem === 'function')
      ? window.getCleanLokasiItem()
      : $('.lokasi-item').first().clone();
    
    $('#dynamic-lokasi-container').empty().append(firstItem);
    
    if (typeof window.updateLokasiIndexes === 'function') {
      window.updateLokasiIndexes();
    }
    
    $('.btn-tambah-lokasi').show();
    $('#gabungan-lokasi-info').hide();
    
    firstItem.find('.t_input').first().trigger('input');
    $('#modal-lalat').modal('show');
  });

  $(document).on('click', '.btn-tambah-kecoa', function() {
    if($('#form-kecoa').length) $('#form-kecoa')[0].reset();
    $('#id_kecoa_form').val('');
    $('#modal-kecoa-title').text('Tambah Data Kecoa');
    $('#modal-kecoa-header').removeClass('bg-info').addClass('bg-success');
    $('#chk_lainnya_kecoa').prop('checked', false).trigger('change');
    $('.sp_kecoa').trigger('input');
    $('#modal-kecoa').modal('show');
  });

  $(document).on('click', '.btn-tambah-tikus', function() {
    if($('#form-tikus').length) $('#form-tikus')[0].reset();
    $('#id_tikus_form').val('');
    $('#modal-tikus-title').text('Tambah Data Tikus');
    $('#modal-tikus-header').removeClass('bg-info').addClass('bg-success');
    if (typeof window.resetSpesiesTikusContainer === 'function') {
      window.resetSpesiesTikusContainer();
    }
    $('#form-tikus input[type="number"]').trigger('input');
    $('#modal-tikus').modal('show');
  });

  $(document).on('change', '#tikus_tanggal_mulai', function() {
    let tglMulai = $(this).val();
    let $tglSelesai = $('#tikus_tanggal_selesai');
    if (tglMulai) {
      $tglSelesai.attr('min', tglMulai);
      if (!$tglSelesai.val() || $tglSelesai.val() < tglMulai) {
        $tglSelesai.val(tglMulai);
      }
    }
  });

  $(document).on('click', '.btn-tambah-pinjal', function() {
    if($('#form-pinjal').length) $('#form-pinjal')[0].reset();
    $('#id_pinjal_form').val('');
    $('#modal-pinjal-title').text('Tambah Data Pinjal');
    $('#modal-pinjal-header').removeClass('bg-info').addClass('bg-success');
    $('#jml_tikus_tertangkap').val(0);
    $('#pinjal_umum').val(0);
    $('#pinjal_khusus').val(0);
    if (typeof window.hitungIndeksPinjal === 'function') {
      window.hitungIndeksPinjal();
    } else {
      $('.pinjal-calc').trigger('input');
    }
    $('#modal-pinjal').modal('show');
  });

  // Handle Edit Buttons
  $(document).on('click', '.btn-edit-jentik', function() {
    let row = $(this).data('row');
    $('#form-jentik')[0].reset();
    
    $('#id_jentik_form').val(row.id_jentik);
    $('#modal-jentik-title').text('Edit Data Jentik');
    $('#modal-jentik-header').removeClass('bg-success').addClass('bg-info');
    
    $('select[name="id_wilayah_kerja"]').val(row.id_wilayah_kerja);
    $('input[name="lokasi"]').val(row.lokasi);
    $('input[name="bulan_pelaksanaan"]').val(row.bulan_pelaksanaan);
    $('select[name="kondisi_musim"]').val(row.kondisi_musim);
    $('input[name="tanggal_mulai"]').val(row.tanggal_mulai);
    $('input[name="tanggal_selesai"]').val(row.tanggal_selesai);
    $('select[name="wilayah"]').val(row.wilayah);
    
    $('#jml_rumah_diperiksa').val(row.jml_rumah_diperiksa);
    $('#jml_kontainer_diperiksa').val(row.jml_kontainer_diperiksa);
    $('#jml_rumah_positif').val(row.jml_rumah_positif);
    $('#jml_kontainer_positif').val(row.jml_kontainer_positif);
    
    $('#chk_lainnya_jentik').prop('checked', false).trigger('change');
    if(row.jenis_kontainer) {
      let jenis = row.jenis_kontainer.split(',');
      let defaultJenis = ["Ban Bekas", "Ember", "Bak Mandi", "Tatakan Dispenser", "Tanaman Penyimpan Air"];
      $('input[name="jenis_kontainer[]"]').each(function() {
        if(jenis.includes($(this).val())) $(this).prop('checked', true);
      });
      
      let lainnya = jenis.filter(x => !defaultJenis.includes(x) && x.trim() !== "");
      if(lainnya.length > 0) {
        $('#chk_lainnya_jentik').prop('checked', true).trigger('change');
        $('#txt_lainnya_jentik').val(lainnya.join(', '));
      }
    }
    
    $('#form-jentik input[type="number"]').trigger('input');
    $('#modal-jentik').modal('show');
  });

  $(document).on('click', '.btn-edit-lalat', function() {
    let row = $(this).data('row');
    if($('#form-lalat').length) $('#form-lalat')[0].reset();
    
    $('#id_lalat_form').val(row.id_lalat);
    $('#modal-lalat-title').text('Edit Data Lalat');
    $('#modal-lalat-header').removeClass('bg-success').addClass('bg-info');
    
    $('#form-lalat input[name="tanggal"]').val(row.tanggal);
    $('#form-lalat select[name="id_wilayah_kerja"]').val(row.id_wilayah_kerja);
    $('#form-lalat select[name="musim"]').val(row.musim);
    $('#form-lalat input[name="temperatur"]').val(row.temperatur);
    $('#form-lalat input[name="kelembapan"]').val(row.kelembapan);
    $('#form-lalat input[name="kecepatan_angin"]').val(row.kecepatan_angin);
    
    // Parse detail_lokasi if present
    let detailLokasi = [];
    if (row.detail_lokasi) {
      try {
        let parsed = row.detail_lokasi;
        while (typeof parsed === 'string') {
          parsed = JSON.parse(parsed);
        }
        if (Array.isArray(parsed)) {
          detailLokasi = parsed;
        }
      } catch (err) {
        detailLokasi = [];
      }
    }
    
    // Fallback: if detail_lokasi is empty, parse from row.lokasi (comma separated or single)
    if (!Array.isArray(detailLokasi) || detailLokasi.length === 0) {
      if (row.lokasi && typeof row.lokasi === 'string' && row.lokasi.includes(',')) {
        let locNames = row.lokasi.split(',').map(s => s.trim()).filter(s => s !== '');
        detailLokasi = locNames.map((name) => {
          let tVals = [];
          for (let i = 1; i <= 10; i++) {
            tVals.push(row['t' + i] !== undefined ? row['t' + i] : 0);
          }
          return {
            lokasi: name,
            t: tVals
          };
        });
      } else {
        let tVals = [];
        for (let i = 1; i <= 10; i++) {
          tVals.push(row['t' + i] !== undefined ? row['t' + i] : 0);
        }
        detailLokasi = [{
          lokasi: row.lokasi || '',
          t: tVals
        }];
      }
    }
    
    // Render dynamic lokasi container
    $('#dynamic-lokasi-container').empty();
    
    detailLokasi.forEach((item, index) => {
      let elem = (typeof window.getCleanLokasiItem === 'function')
        ? window.getCleanLokasiItem()
        : null;
        
      if (!elem || elem.length === 0) {
        let tmpl = document.getElementById('template-lokasi-item');
        if (tmpl && tmpl.content && tmpl.content.firstElementChild) {
          elem = $(tmpl.content.firstElementChild).clone();
        } else {
          elem = $('.lokasi-item').first().clone();
        }
      }
        
      elem.find('input[name="lokasi[]"]').val(item.lokasi || '');
      
      let t_inputs = elem.find('.t_input');
      for (let i = 1; i <= 10; i++) {
        let val = 0;
        if (item.t && item.t[i - 1] !== undefined) {
          val = item.t[i - 1];
        } else if (item['t' + i] !== undefined) {
          val = item['t' + i];
        } else {
          val = row['t' + i] || 0;
        }
        $(t_inputs[i - 1]).val(val);
      }
      
      $('#dynamic-lokasi-container').append(elem);
    });
    
    if (typeof window.updateLokasiIndexes === 'function') {
      window.updateLokasiIndexes();
    }
    
    // Keep tambah lokasi button VISIBLE in edit mode so user can manage locations per survey
    $('.btn-tambah-lokasi').show();
    
    // Trigger calculation on all location items
    $('#dynamic-lokasi-container .lokasi-item').each(function() {
      $(this).find('.t_input').first().trigger('input');
    });
    
    if (typeof window.hitungGabunganLalat === 'function') {
      window.hitungGabunganLalat();
    }
    
    $('#modal-lalat').modal('show');
  });

  $(document).on('click', '.btn-edit-kecoa', function() {
    let row = $(this).data('row');
    $('#form-kecoa')[0].reset();
    
    $('#id_kecoa_form').val(row.id_kecoa);
    $('#modal-kecoa-title').text('Edit Data Kecoa');
    $('#modal-kecoa-header').removeClass('bg-success').addClass('bg-info');
    
    $('input[name="tanggal"]').val(row.tanggal);
    $('select[name="id_wilayah_kerja"]').val(row.id_wilayah_kerja);
    $('input[name="lokasi"]').val(row.lokasi);
    $('input[name="nama_ruangan"]').val(row.nama_ruangan);
    $('#perangkap_terpasang').val(row.perangkap_terpasang);
    
    $('#chk_lainnya_kecoa').prop('checked', false).trigger('change');
    
    if(row.spesies_tertangkap) {
      let spesies = JSON.parse(row.spesies_tertangkap);
      $('input[name="spesies[Periplatena americana]"]').val(spesies['Periplatena americana'] || 0);
      $('input[name="spesies[Blatella germanica]"]').val(spesies['Blatella germanica'] || 0);
      
      let defaultSpesies = ['Periplatena americana', 'Blatella germanica'];
      let lainnyaKey = Object.keys(spesies).find(key => !defaultSpesies.includes(key));
      
      if(lainnyaKey) {
        $('#chk_lainnya_kecoa').prop('checked', true).trigger('change');
        $('#txt_nama_lainnya_kecoa').val(lainnyaKey).trigger('input');
        $('#txt_jml_lainnya_kecoa').val(spesies[lainnyaKey] || 0);
      }
    }
    
    $('.sp_kecoa').trigger('input');
    $('#modal-kecoa').modal('show');
  });

  $(document).on('click', '.btn-edit-tikus', function() {
    let row = $(this).data('row');
    $('#form-tikus')[0].reset();
    
    $('#id_tikus_form').val(row.id_tikus);
    $('#modal-tikus-title').text('Edit Data Tikus');
    $('#modal-tikus-header').removeClass('bg-success').addClass('bg-info');
    
    let tglMulai = row.tanggal_mulai || row.tanggal || '';
    let tglSelesai = row.tanggal_selesai || row.tanggal_mulai || row.tanggal || '';
    $('#form-tikus input[name="tanggal_mulai"]').val(tglMulai);
    $('#form-tikus input[name="tanggal_selesai"]').val(tglSelesai);
    $('#form-tikus select[name="id_wilayah_kerja"]').val(row.id_wilayah_kerja);
    $('#form-tikus input[name="lokasi"]').val(row.lokasi);
    $('#form-tikus input[name="lokasi_pemasangan"]').val(row.lokasi_pemasangan);
    $('#form-tikus input[name="jenis_umpan"]').val(row.jenis_umpan);
    $('#jml_perangkap_dipasang').val(row.jml_perangkap_dipasang);
    $('#jml_perangkap_hilang').val(row.jml_perangkap_hilang || 0);
    $('#jml_tikus_tertangkap').val(row.jml_tikus_tertangkap);
    
    if (typeof window.populateSpesiesTikus === 'function') {
      window.populateSpesiesTikus(row.spesies, row.jml_tikus_tertangkap);
    }
    
    $('#form-tikus input[type="number"]').trigger('input');
    $('#modal-tikus').modal('show');
  });

  $(document).on('click', '.btn-edit-pinjal', function() {
    let row = $(this).data('row');
    $('#form-pinjal')[0].reset();
    
    $('#id_pinjal_form').val(row.id_pinjal);
    $('#modal-pinjal-title').text('Edit Data Pinjal');
    $('#modal-pinjal-header').removeClass('bg-success').addClass('bg-info');
    
    $('#form-pinjal input[name="tanggal"]').val(row.tanggal);
    $('#form-pinjal select[name="id_wilayah_kerja"]').val(row.id_wilayah_kerja);
    $('#form-pinjal input[name="lokasi"]').val(row.lokasi);
    $('#jml_tikus_tertangkap').val(row.jml_tikus_tertangkap || 0);
    $('#pinjal_umum').val(row.pinjal_umum || 0);
    $('#pinjal_khusus').val(row.pinjal_khusus || 0);
    
    if (typeof window.hitungIndeksPinjal === 'function') {
      window.hitungIndeksPinjal();
    } else {
      $('.pinjal-calc').trigger('input');
    }
    $('#modal-pinjal').modal('show');
  });

  // Handle Pengendalian Buttons
  $(document).on('click', '.btn-pengendalian-jentik', function() {
    let id = $(this).data('id');
    let data = $(this).data('pengendalian');
    let surveyTgl = $(this).data('tanggal') || new Date().toISOString().split('T')[0];
    if (typeof data === 'string' && data.trim() !== '') {
      try { data = JSON.parse(data); } catch(e) {}
    }
    $('#form-pengendalian-jentik')[0].reset();
    $('#id_jentik_pengendalian').val(id);
    $('#jentik-tab').tab('show');
    
    let defaultTgl = (data && data.tanggal) ? data.tanggal : surveyTgl;
    $('#form-pengendalian-jentik input[name="pengendalian[tanggal]"]').val(defaultTgl);
    
    if (data && typeof data === 'object') {
      if(data.jentik_kimia) $('#jentik_kimia').prop('checked', true);
      if(data.jentik_kimia_gram) $('input[name="pengendalian[jentik_kimia_gram]"]').val(data.jentik_kimia_gram);
      if(data.jentik_kimia_sachet) $('input[name="pengendalian[jentik_kimia_sachet]"]').val(data.jentik_kimia_sachet);
      if(data.jentik_fisik) $('#jentik_fisik').prop('checked', true);
      if(data.jentik_biologi) $('#jentik_biologi').prop('checked', true);
      
      if(data.nyamuk_lokasi) $('input[name="pengendalian[nyamuk_lokasi]"]').val(data.nyamuk_lokasi);
      if(data.nyamuk_fogging) $('#nyamuk_fogging').prop('checked', true);
      if(data.nyamuk_fogging_mesin) $('input[name="pengendalian[nyamuk_fogging_mesin]"]').val(data.nyamuk_fogging_mesin);
      if(data.nyamuk_fogging_ml) $('input[name="pengendalian[nyamuk_fogging_ml]"]').val(data.nyamuk_fogging_ml);
      if(data.nyamuk_fogging_golongan) $('select[name="pengendalian[nyamuk_fogging_golongan]"]').val(data.nyamuk_fogging_golongan);
      if(data.nyamuk_fogging_bahan) $('input[name="pengendalian[nyamuk_fogging_bahan]"]').val(data.nyamuk_fogging_bahan);
      if(data.nyamuk_fogging_merk) $('input[name="pengendalian[nyamuk_fogging_merk]"]').val(data.nyamuk_fogging_merk);
      if(data.nyamuk_fisik) $('#nyamuk_fisik').prop('checked', true);
    }
    
    $('#modal-pengendalian-jentik').modal('show');
  });

  $(document).on('click', '.btn-pengendalian-lalat', function() {
    let id = $(this).data('id');
    let data = $(this).data('pengendalian');
    let surveyTgl = $(this).data('tanggal') || new Date().toISOString().split('T')[0];
    if (typeof data === 'string' && data.trim() !== '') {
      try { data = JSON.parse(data); } catch(e) {}
    }
    $('#form-pengendalian-lalat')[0].reset();
    $('#id_lalat_pengendalian').val(id);
    
    let defaultTgl = (data && data.tanggal) ? data.tanggal : surveyTgl;
    $('#form-pengendalian-lalat input[name="pengendalian[tanggal]"]').val(defaultTgl);
    
    if (data && typeof data === 'object') {
      if(data.lokasi) $('input[name="pengendalian[lokasi]"]').val(data.lokasi);
      if(data.spraying) $('#lalat_spraying').prop('checked', true);
      if(data.spraying_ml) $('input[name="pengendalian[spraying_ml]"]').val(data.spraying_ml);
      if(data.granule) $('#lalat_granule').prop('checked', true);
      if(data.granule_gram) $('input[name="pengendalian[granule_gram]"]').val(data.granule_gram);
    }
    
    $('#modal-pengendalian-lalat').modal('show');
  });

  $(document).on('click', '.btn-pengendalian-kecoa', function() {
    let id = $(this).data('id');
    let data = $(this).data('pengendalian');
    if (typeof data === 'string' && data.trim() !== '') {
      try { data = JSON.parse(data); } catch(e) {}
    }
    $('#form-pengendalian-kecoa')[0].reset();
    $('#id_kecoa_pengendalian').val(id);
    
    if (data && typeof data === 'object') {
      if(data.lokasi) $('input[name="pengendalian[lokasi]"]').val(data.lokasi);
      if(data.retrapping) $('#kecoa_retrapping').prop('checked', true);
      if(data.retrapping_trap) $('input[name="pengendalian[retrapping_trap]"]').val(data.retrapping_trap);
      if(data.gel_bait) $('#kecoa_gel_bait').prop('checked', true);
      if(data.gel_bait_gram) $('input[name="pengendalian[gel_bait_gram]"]').val(data.gel_bait_gram);
    }
    
    $('#modal-pengendalian-kecoa').modal('show');
  });

  // Handle Pengendalian Submits
  $(document).on('submit', '#form-pengendalian-jentik', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_pengendalian_jentik',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Pengendalian berhasil disimpan', 'success');
          $('#modal-pengendalian-jentik').modal('hide');
          $('.modal-backdrop').remove();
          loadJentik();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-pengendalian-lalat', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_pengendalian_lalat',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Pengendalian berhasil disimpan', 'success');
          $('#modal-pengendalian-lalat').modal('hide');
          $('.modal-backdrop').remove();
          loadLalat();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-pengendalian-kecoa', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_pengendalian_kecoa',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Pengendalian berhasil disimpan', 'success');
          $('#modal-pengendalian-kecoa').modal('hide');
          $('.modal-backdrop').remove();
          loadKecoa();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  // Handle Form Submits
  $(document).on('submit', '#form-jentik', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_sivera_jentik',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Jentik berhasil disimpan', 'success');
          $('#modal-jentik').modal('hide');
          $('.modal-backdrop').remove();
          loadJentik();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-lalat', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_sivera_lalat',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Lalat berhasil disimpan', 'success');
          $('#modal-lalat').modal('hide');
          $('.modal-backdrop').remove();
          loadLalat();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-kecoa', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_sivera_kecoa',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Kecoa berhasil disimpan', 'success');
          $('#modal-kecoa').modal('hide');
          $('.modal-backdrop').remove();
          loadKecoa();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-tikus', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_sivera_tikus',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Tikus berhasil disimpan', 'success');
          $('#modal-tikus').modal('hide');
          $('.modal-backdrop').remove();
          loadTikus();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  $(document).on('submit', '#form-pinjal', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/prl/simpan_sivera_pinjal',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        if(res.status == 'success') {
          Swal.fire('Berhasil!', 'Data Pinjal berhasil disimpan', 'success');
          $('#modal-pinjal').modal('hide');
          $('.modal-backdrop').remove();
          loadPinjal();
        } else {
          Swal.fire('Gagal!', res.msg, 'error');
        }
      }
    });
  });

  // Handle Deletes
  $(document).on('click', '.btn-hapus-jentik', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/prl/hapus_sivera_jentik/' + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(res) {
            if(res.status == 'success') {
              Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
              loadJentik();
            } else {
              Swal.fire('Gagal!', res.msg, 'error');
            }
          }
        });
      }
    })
  });

  $(document).on('click', '.btn-hapus-lalat', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/prl/hapus_sivera_lalat/' + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(res) {
            if(res.status == 'success') {
              Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
              loadLalat();
            } else {
              Swal.fire('Gagal!', res.msg, 'error');
            }
          }
        });
      }
    })
  });

  $(document).on('click', '.btn-hapus-kecoa', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/prl/hapus_sivera_kecoa/' + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(res) {
            if(res.status == 'success') {
              Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
              loadKecoa();
            } else {
              Swal.fire('Gagal!', res.msg, 'error');
            }
          }
        });
      }
    })
  });

  $(document).on('click', '.btn-hapus-tikus', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/prl/hapus_sivera_tikus/' + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(res) {
            if(res.status == 'success') {
              Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
              loadTikus();
            } else {
              Swal.fire('Gagal!', res.msg, 'error');
            }
          }
        });
      }
    })
  });

  $(document).on('click', '.btn-hapus-pinjal', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/prl/hapus_sivera_pinjal/' + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(res) {
            if(res.status == 'success') {
              Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
              loadPinjal();
            } else {
              Swal.fire('Gagal!', res.msg, 'error');
            }
          }
        });
      }
    })
  });

});
