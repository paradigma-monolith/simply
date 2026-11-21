// Update hidden pic_nama & pic_nip saat dropdown PIC berubah
$(document).on('change', 'select[name="sinadi_pic"]', function() {
  var selectedValue = $(this).val();
  var selectedOption = $(this).find('option:selected');
  var selectedDataNama = selectedOption.data('nama') || '';

  $('input[name="pic_nama"]').val(selectedDataNama);
  $('input[name="pic_nip"]').val(selectedValue || '');
});

// Event delegation untuk elemen <select> dengan nama "nip[]"
$(document).on('change', 'select[name="nip[]"]', function() {
  var selectedOption = $(this).find('option:selected');
  var nama = selectedOption.data('nama') || '';
  var jabatan = selectedOption.data('jabatan') || '';
  var golongan = selectedOption.data('golongan') || '';

  var index = $('select[name="nip[]"]').index(this);
  $('input[name="nama[]"]').eq(index).val(nama);
  $('input[name="jabatan[]"]').eq(index).val(jabatan);
  $('input[name="golongan[]"]').eq(index).val(golongan);

  var selectedValueSekarang = $(this).val();
  if (!selectedValueSekarang) return;

  var selectedValuesSemua = [];
  $('select[data-pegawai="nip[]"]').each(function() {
    selectedValuesSemua.push($(this).val());
  });

  var jumlahNilaiSama = selectedValuesSemua.filter(function(value) {
    return value === selectedValueSekarang;
  }).length;

  if (jumlahNilaiSama > 1) {
    Swal.fire({
      title: 'Nama Pegawai Sudah Terpilih',
      html: 'Pegawai dengan NIP <strong>' + selectedValueSekarang + '</strong> sudah dipilih pada daftar peserta.',
      icon: 'info'
    });
    $(this).val('').trigger('change.select2');
    $('input[name="nama[]"]').eq(index).val('');
    $('input[name="golongan[]"]').eq(index).val('');
    $('input[name="jabatan[]"]').eq(index).val('');
  }
});

// Sinkronisasi otomatis tanggal berangkat & kembali peserta 1 dari tanggal kegiatan utama
$(document).on('change', 'input[name="tgl_pelaksanaan"]', function() {
  var val = $(this).val();
  if (!val) return;
  var $akhir = $('input[name="tgl_pelaksanaan_akhir"]');
  if (!$akhir.val()) {
    $akhir.val(val);
  }
  var $pergi0 = $('input[name="tgl_pergi[]"]').eq(0);
  if ($pergi0.length && !$pergi0.val()) {
    $pergi0.val(val);
  }
});

$(document).on('change', 'input[name="tgl_pelaksanaan_akhir"]', function() {
  var val = $(this).val();
  if (!val) return;
  var $pulang0 = $('input[name="tgl_pulang[]"]').eq(0);
  if ($pulang0.length && !$pulang0.val()) {
    $pulang0.val(val);
  }
});

// Event handler untuk elemen input dengan class .anggaran
$(document).on('input change', '.anggaran', function() {
  sinadiPerhitunganAnggaran(this);
});

$(document).on('click', '.btn-info-pegawai', function() {
  var peserta = $(this).attr('data-peserta');
  var labelText = $.trim($(this).text());

  if (!peserta) {
    if (labelText === 'PERSEORANGAN' || labelText === 'PERSEORANG' || labelText === 'PEGAWAI NO 1') {
      return;
    }
    if ($('#sinadi-kategori').val() !== 'Perseorangan') {
      $(this).parent().remove();
      hitungBanyakData();
      updateSinadiGrandTotal();
    }
  } else {
    sinadiHapusPeserta(this, peserta);
  }
});

function sinadiToggleHideDataPegawai(button) {
  var parentTr = button.parentElement.parentElement;
  var previousSiblings = [];
  var prevSibling = parentTr.previousElementSibling;

  while (prevSibling) {
    previousSiblings.push(prevSibling);
    prevSibling = prevSibling.previousElementSibling;
  }

  for (var i = previousSiblings.length - 1; i >= 0; i--) {
    var sibling = previousSiblings[i];
    if (sibling.classList.contains('manualInput') || sibling.classList.contains('autoInput')) {
      if (sibling.style.display !== 'none') {
        sibling.style.display = 'none';
        disableInputs(sibling, true);
        $(button).html('<i class="fas fa-user-edit mr-1"></i> Beralih ke input manual data pegawai');
      } else {
        sibling.style.display = 'table-row';
        disableInputs(sibling, false);
        $(button).html('<i class="fas fa-database mr-1"></i> Beralih ke pilih daftar data pegawai');
      }
    }
  }
}

function disableInputs(container, disabled) {
  var inputs = container.querySelectorAll('input');
  var selects = container.querySelectorAll('select');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].disabled = disabled;
    inputs[i].value = '';
  }
  for (var j = 0; j < selects.length; j++) {
    selects[j].required = !disabled;
    selects[j].value = '';
    if ($(selects[j]).hasClass('select2-hidden-accessible')) {
      $(selects[j]).val('').trigger('change.select2');
    }
  }
}

$(function() {
  $('.manualInput').hide();

  $('.sinadi-btn-tambah-data').off('click').on('click', function() {
    var $sourceWrapper = $('.sinadi-data-pegawai').children('div').first();
    var data = $sourceWrapper.clone();

    // Bersihkan instance Select2 dari hasil clone
    data.find('select.select2').each(function() {
      $(this).removeClass('select2-hidden-accessible').removeAttr('data-select2-id').find('option').removeAttr('data-select2-id');
      $(this).next('.select2-container').remove();
    });

    // Reset identitas pegawai pada baris baru
    data.find('select[name="nip[]"]').val('');
    data.find('.nama').val('');
    data.find('.jabatan').val('');
    data.find('.golongan').val('');
    data.find('.manualInput').hide();
    data.find('.autoInput').show();
    data.find('.autoInput input, .autoInput select').prop('disabled', false);
    data.find('.manualInput input').prop('disabled', true).val('');

    data.find('.btn-info-pegawai').attr('data-peserta', '');

    $('.data-pegawai-tambahan').append(data);

    // Hanya inisialisasi Select2 pada blok peserta yang baru ditambahkan (jauh lebih cepat!)
    data.find('select.select2').select2({ width: '100%' });

    hitungBanyakData();
    var dataCount = $('.btn-info-pegawai').length - 1;
    sinadiCloneDataValue(dataCount);

    // Hitung anggaran untuk blok baru
    var $newAnggaran = data.find('.anggaran').first();
    if ($newAnggaran.length) {
      sinadiPerhitunganAnggaran($newAnggaran[0]);
    }
    updateSinadiGrandTotal();
  });
});

function hitungBanyakData() {
  var count = $('.btn-info-pegawai').length;

  if (count === 1) {
    $('.btn-info-pegawai').eq(0)
      .removeClass('btn-danger')
      .addClass('btn-secondary')
      .html('<i class="fas fa-user mr-1"></i> PERSEORANGAN');
    $('#sinadi-kategori').val('Perseorangan');
  } else {
    $('#sinadi-kategori').val('Kelompok');
    for (var i = 0; i < count; i++) {
      if (i === 0) {
        $('.btn-info-pegawai').eq(i)
          .removeClass('btn-danger')
          .addClass('btn-secondary')
          .html('<i class="fas fa-user-check mr-1"></i> PEGAWAI NO 1');
      } else {
        $('.btn-info-pegawai').eq(i)
          .removeClass('btn-secondary')
          .addClass('btn-danger')
          .html('<i class="fas fa-trash-alt mr-1"></i> HAPUS PEGAWAI NO ' + (i + 1));
      }
    }
  }
  updateSinadiGrandTotal();
}

$('input[name="selectHasilKegiatan"]').on('change', function() {
  var radio = $(this).val();
  $('.file-block').addClass('d-none');
  $('.summernote-block').addClass('d-none');

  if (radio == 1) {
    $('.file-block').removeClass('d-none');
  } else if (radio == 2) {
    $('.summernote-block').removeClass('d-none');
  }
});

$(document).on('change input', 'input[name="kota[]"]', function() {
  var isi = $(this).val();
  var $input = $(this).prev('input[name="kota_tujuan[]"]');
  if ($input.length) {
    $input.val(isi);
  }
});

$(document).on('change', '.kota_tujuan_select', function() {
  var selectedValues = $(this).val();
  var inputHidden = $(this).prev('.kota_tujuan_input');
  if (selectedValues) {
    inputHidden.val(Array.isArray(selectedValues) ? selectedValues.join(',') : selectedValues);
  } else {
    inputHidden.val('');
  }
});

function sinadiCloneDataValue(count) {
  // Salin tanggal pergi, pulang, surat tugas, kota asal, kota tujuan, dan transportasi dari Pegawai 1
  var fieldsToCopy = ['tgl_pergi[]', 'tgl_pulang[]', 'no_surat_tugas[]', 'tgl_surat_tugas[]', 'tgl_sppd[]'];
  fieldsToCopy.forEach(function(fieldName) {
    var val0 = $('input[name="' + fieldName + '"]').eq(0).val();
    if (val0) {
      $('input[name="' + fieldName + '"]').eq(count).val(val0);
    }
  });

  // Untuk Dalam Kota (input text kota_asal & kota)
  var $kotaAsalInput0 = $('input[name="kota_asal[]"]').eq(0);
  if ($kotaAsalInput0.length && $kotaAsalInput0.val()) {
    $('input[name="kota_asal[]"]').eq(count).val($kotaAsalInput0.val());
  }
  var $kotaInput0 = $('input[name="kota[]"]').eq(0);
  if ($kotaInput0.length && $kotaInput0.val()) {
    $('input[name="kota[]"]').eq(count).val($kotaInput0.val()).trigger('change');
  }

  // Untuk Luar Kota (select kota_asal & select multiple kota)
  var $kotaSelect0 = $('select[name="kota[]"]').eq(0);
  if ($kotaSelect0.length) {
    var selectedValuesDefault = $kotaSelect0.val();
    if (selectedValuesDefault) {
      $('select[name="kota[]"]').eq(count).val(selectedValuesDefault).trigger('change');
    }
  }

  var $kotaAsalSelect0 = $('select[name="kota_asal[]"]').eq(0);
  if ($kotaAsalSelect0.length && $kotaAsalSelect0.val()) {
    $('select[name="kota_asal[]"]').eq(count).val($kotaAsalSelect0.val()).trigger('change');
  }

  var $transSelect0 = $('select[name="transportasi[]"]').eq(0);
  if ($transSelect0.length && $transSelect0.val()) {
    $('select[name="transportasi[]"]').eq(count).val($transSelect0.val()).trigger('change');
  }
}

function parseIndoNumber(str) {
  if (str === undefined || str === null) return 0;
  var cleaned = String(str).replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(/,/g, '.');
  var num = parseFloat(cleaned);
  return isNaN(num) ? 0 : num;
}

function updateSinadiGrandTotal() {
  var totalPeserta = $('.perhitungan_anggaran').length;
  var sumTransport = 0;
  var sumHarian = 0;
  var sumPenginapan = 0;
  var sumTransfer = 0;

  $('.transport_jumlah').each(function() { sumTransport += parseIndoNumber($(this).val()); });
  $('.harian_jumlah').each(function() { sumHarian += parseIndoNumber($(this).val()); });
  $('.penginapan_jumlah').each(function() { sumPenginapan += parseIndoNumber($(this).val()); });
  $('.jumlah_transfer').each(function() { sumTransfer += parseIndoNumber($(this).val()); });

  if ($('#snLiveTotalPeserta').length) {
    $('#snLiveTotalPeserta').text(totalPeserta + ' Orang');
    $('#snLiveTotalTransport').text('Rp ' + sumTransport.toLocaleString('id-ID'));
    $('#snLiveTotalHarian').text('Rp ' + sumHarian.toLocaleString('id-ID'));
    $('#snLiveTotalPenginapan').text('Rp ' + sumPenginapan.toLocaleString('id-ID'));
    $('#snLiveGrandTotal').text('Rp ' + sumTransfer.toLocaleString('id-ID'));
  }
}

function sinadiPerhitunganAnggaran(e) {
  var $row = $(e).closest('.perhitungan_anggaran');
  var index = $('.perhitungan_anggaran').index($row);
  if (index < 0) index = 0;

  var rawTHari = ($('.transport_hari').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');
  var rawTNom = ($('.transport_transport').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');
  var rawHHari = ($('.harian_hari').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');
  var rawHNom = ($('.harian_unit_cost').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');
  var rawPKali = ($('.penginapan_kali').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');
  var rawPNom = ($('.penginapan_unit_cost').eq(index).val() || '').replace(/\./g, '').replace(/,/g, '.');

  var tHari = parseFloat(rawTHari) || 0;
  var tNom = parseFloat(rawTNom) || 0;
  var hHari = parseFloat(rawHHari) || 0;
  var hNom = parseFloat(rawHNom) || 0;
  var pKali = parseFloat(rawPKali) || 0;
  var pNom = parseFloat(rawPNom) || 0;

  var tTotal = tHari * tNom;
  var hTotal = hHari * hNom;
  var pTotal = pKali * pNom;

  $('.transport_jumlah').eq(index).val(tTotal > 0 ? 'Rp' + tTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');
  $('.harian_jumlah').eq(index).val(hTotal > 0 ? 'Rp' + hTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');
  $('.penginapan_jumlah').eq(index).val(pTotal > 0 ? 'Rp' + pTotal.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }) : '0');

  var hasil = tTotal + hTotal + pTotal;
  $('.jumlah_transfer').eq(index).val('Rp' + hasil.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));

  if (e && $(e).hasClass('anggaran')) {
    var val = String($(e).val() || '');
    val = val.replace(/[^0-9,]/g, '');
    val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    $(e).val(val);
  }

  updateSinadiGrandTotal();
}

$(document).ready(function() {
  // Format semua input .anggaran dan hitung total untuk setiap baris peserta (termasuk saat transport_hari kosong!)
  $('.anggaran').each(function() {
    var raw = String($(this).val() || '');
    if (raw !== '') {
      $(this).val(raw.replace('.', ','));
    }
  });
  $('.perhitungan_anggaran').each(function() {
    var firstInput = $(this).find('.anggaran').first()[0];
    if (firstInput) {
      sinadiPerhitunganAnggaran(firstInput);
    }
  });
  updateSinadiGrandTotal();

  // Pastikan pic_nama dan pic_nip terisi jika dropdown sinadi_pic sudah memiliki pilihan
  var $picSelect = $('select[name="sinadi_pic"]');
  if ($picSelect.length && $picSelect.val()) {
    $picSelect.trigger('change');
  }

  $(document).off('submit', '#sinadiTambahPerjalananDinas').on('submit', '#sinadiTambahPerjalananDinas', function(event) {
    var requiredInputs = $(this).find('input[required], select[required]');

    for (var i = 0; i < requiredInputs.length; i++) {
      var input = requiredInputs.eq(i);
      if (input.is(':hidden') || input.prop('disabled')) {
        continue;
      }
      var inputVal = input.val();
      if (inputVal === '' || inputVal === null || (Array.isArray(inputVal) && inputVal.length === 0)) {
        event.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Data Belum Lengkap',
          text: 'Harap lengkapi semua kolom wajib sebelum menyimpan formulir perjalanan dinas.'
        });
        $('html, body').animate({
          scrollTop: Math.max(0, input.offset().top - 120)
        }, 400);
        input.focus();
        return;
      }
    }
  });
});

function sinadiHapusPeserta(e, id) {
  Swal.fire({
    title: 'Hapus Peserta Ini?',
    text: 'Data peserta akan dihapus dari perjalanan dinas ini.',
    showDenyButton: true,
    showCancelButton: false,
    icon: 'warning',
    confirmButtonText: 'Ya, Hapus',
    denyButtonText: 'Batal'
  }).then(function(result) {
    if (result.isConfirmed) {
      $.ajax({
        url: '/sinadi/dlt/sinadiHapusPeserta/' + id,
        method: 'DELETE'
      }).done(function() {
        var labelText = $.trim($(e).text());
        if (labelText !== 'PERSEORANGAN' && labelText !== 'PEGAWAI NO 1') {
          if ($('#sinadi-kategori').val() !== 'Perseorangan') {
            $(e).parent().remove();
            hitungBanyakData();
            updateSinadiGrandTotal();
          }
        }
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Peserta berhasil dihapus!',
          showConfirmButton: false,
          timer: 1400
        });
      }).fail(function() {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Menghapus',
          text: 'Terjadi kesalahan saat menghapus peserta. Silakan coba kembali.'
        });
      });
    }
  });
}

$(document).on('change', '.manualNip, .manualNama, .manualJabatan, .manualGolongan', function() {
  cekTambahPIC();
});

function cekTambahPIC() {
  var banyakData = $('.manualInput').length;
  for (var i = 0; i < banyakData; i++) {
    var manualNip = $('.manualNip').eq(i).val();
    var manualNama = $('.manualNama').eq(i).val();
    var manualJabatan = $('.manualJabatan').eq(i).val();
    var manualGolongan = $('.manualGolongan').eq(i).val();
    if (manualNip !== '' && manualNama !== '' && manualJabatan !== '' && manualGolongan !== '') {
      var $existing = $('.pic option[data-manual="' + i + '"]');
      if (!$existing.length) {
        var option = new Option(manualNip + ' | ' + manualNama, manualNip);
        $(option).attr('data-manual', i)
          .attr('data-nama', manualNama)
          .attr('data-jabatan', manualJabatan)
          .attr('data-golongan', manualGolongan);
        $('.pic').append(option).trigger('change');
      }
    }
  }
}
