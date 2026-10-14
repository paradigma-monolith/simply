$(document).on('change', 'select[name="sinadi_pic"]', function() {
  var selectedValue = $(this).val();
  var selectedOption = $(".pic option[value='" + selectedValue + "']:selected");
  var selectedDataNama = selectedOption.data("nama");

  $('input[name="pic_nama"]').val(selectedDataNama);
  $('input[name="pic_nip"]').val(selectedValue);
});
// Event delegation untuk elemen <select> dengan nama "nip[]"
$(document).on('change', 'select[name="nip[]"]', function() { // ganti sama class aja
  // Mendapatkan data nama dari opsi yang dipilih
  var selectedOption = $(this).find('option:selected');
  var nama = selectedOption.data('nama');

  // Memasukkan nilai nama ke dalam elemen "nama[]" yang sesuai
  var index = $('select[name="nip[]"]').index(this);
  console.log(index);
  $('input[name="nama[]"]').eq(index).val(nama);
  
  // Mendapatkan data jabatan dari opsi yang dipilih
  var selectedOption = $(this).find('option:selected');
  var jabatan = selectedOption.data('jabatan');

  // Memasukkan nilai jabatan ke dalam elemen "jabatan[]" yang sesuai
  var index = $('select[name="nip[]"]').index(this);
  $('input[name="jabatan[]"]').eq(index).val(jabatan);

  // Mendapatkan data golongan dari opsi yang dipilih
  selectedOption = $(this).find('option:selected');
  var golongan = selectedOption.data('golongan');

  // Memasukkan nilai golongan ke dalam elemen "golongan[]" yang sesuai
  index = $('select[name="nip[]"]').index(this);
  $('input[name="golongan[]"]').eq(index).val(golongan);

  // ====================================================================
  // Ambil nilai dari "select sekarang"
  var selectedValueSekarang = $(this).val();

  // Ambil semua nilai dari elemen dengan class ".select2"
  var selectedValuesSemua = [];
  $('select[data-pegawai="nip[]"]').each(function() {
      var selectedValue = $(this).val();
      selectedValuesSemua.push(selectedValue);
  });

  // Menghitung berapa banyak nilai yang sama
  var jumlahNilaiSama = selectedValuesSemua.filter(function(value) {
      return value === selectedValueSekarang;
  }).length;

  if(jumlahNilaiSama > 1 && $(this).val() != ''){
    Swal.fire({
      title : 'Nama Pegawai Sudah Terpilih',
      html : 'Pegawai dengan nip <strong>'+selectedValueSekarang+'</strong> sudah terpilih sebelumnya',
      icon : 'info'
   })
    // Menghapus data terkait dengan select2 pada salinan
    $(this).removeClass("select2-hidden-accessible").next().remove();
    $(this).select2("destroy");
    $(this).val('');
    $('input[name="nama[]"]').eq(index).val('');
    $('input[name="golongan[]"]').eq(index).val('');
    $('input[name="jabatan[]"]').eq(index).val('');
    $(this).select2()
  }
});

// Event handler untuk elemen input dengan class .anggaran
$(document).on("input", ".anggaran", function() {
  sinadiPerhitunganAnggaran(this);
});


$(document).on("click", ".btn-info-pegawai", function() {
  var peserta = $(this).attr('data-peserta');

  if(peserta == '') {
    if($(this).text() == "PERSEORANGAN" || $(this).text() == "PEGAWAI NO 1"){
    }else{
      if($("#sinadi-kategori").val() != "Perseorangan"){
        $(this).parent().remove();
        hitungBanyakData()
      }
    }
  } else {
    sinadiHapusPeserta(this, peserta)
  }
});

function sinadiToggleHideDataPegawai(button) {
  // Temukan parent <tr> dari tombol yang diklik
  var parentTr = button.parentElement.parentElement;

  // Temukan semua elemen sibling (saudara) sebelumnya
  var previousSiblings = [];
  var prevSibling = parentTr.previousElementSibling;
  
  while (prevSibling) {
    previousSiblings.push(prevSibling);
    prevSibling = prevSibling.previousElementSibling;
  }

  // Iterasi melalui elemen-elemen sibling sebelumnya
  for (var i = previousSiblings.length - 1; i >= 0; i--) {
    var sibling = previousSiblings[i];
    if (sibling.classList.contains('manualInput') || sibling.classList.contains('autoInput')) {
      // Periksa apakah elemen sibling adalah manualInput atau autoInput
      if (sibling.style.display !== 'none') {
        sibling.style.display = 'none'; // Sembunyikan elemen sibling
        disableInputs(sibling, true);  // Menonaktifkan elemen input di dalam sibling
        $(button).text("Tambah manual data pegawai")
      } else {
        sibling.style.display = 'table-row'; // Tampilkan elemen sibling
        disableInputs(sibling, false);       // Mengaktifkan elemen input di dalam sibling
        $(button).text("Tambah daftar data pegawai")
      }
    }
  }  
}

function disableInputs(container, disabled) {
  // Mengaktifkan atau menonaktifkan elemen input dalam kontainer
  var inputs = container.querySelectorAll('input');
  var selects = container.querySelectorAll('select');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].disabled = disabled;
    inputs[i].value = '';
  }
  for(var i = 0; i< selects.length; i++) {
    selects[i].required = !disabled;
    
    // Mengambil instance select2
    var select2Instance = $(selects[i]).select2();
    // Mengatur tampilan teks pada select2
    if (select2Instance) {
      // Menentukan teks yang akan ditampilkan
      var displayText = disabled ? '' : '--NIP/NOPEG | NAMA--'; 
      // Menggunakan metode .text() untuk mengatur teks tampilan
      select2Instance.select2('data', { id: '', text: displayText });
    }
    selects[i].value = '';
  }
}

$(function(){
  $(".manualInput").hide();

  $(".sinadi-btn-tambah-data").click(function(){
    var data = $(".sinadi-data-pegawai").find("div").clone();

    data.find("select.select2").removeClass("select2-hidden-accessible").next().remove();
    data.find("select.select2").select2("destroy");
    data.find(".nama").val("");
    data.find(".jabatan").val("");
    data.find(".golongan").val("");

    // Contoh: menambahkan atribut required ke elemen nama dan jabatan
    data.find("input").attr("required", "required");
    data.find("select").attr("required", "required");

    $(".data-pegawai-tambahan").append(data);
    $('#sinadiTambahPerjalananDinas').valid();

    $('.select2').select2()

    hitungBanyakData()
    var dataCount = ($('.btn-info-pegawai').length) - 1
    
    sinadiCloneDataValue(dataCount);

  });  
});

function hitungBanyakData(){
  var count = $('.btn-info-pegawai').length;

  if(count == 1){
    $(".btn-info-pegawai").eq(0).text("PERSEORANG")
    $("#sinadi-kategori").val("Perseorangan")
  }else{
    $("#sinadi-kategori").val("Kelompok")
    for(let i=0; i<count; i++){
      if(i == 0){
        $(".btn-info-pegawai").eq(i).text("PEGAWAI NO "+(parseInt(i)+1))
      } else {
        $(".btn-info-pegawai").eq(i).html("<i class='fas fa-minus-circle'></i> PEGAWAI NO "+(parseInt(i)+1))
        $(".btn-info-pegawai").eq(i).addClass("btn-danger")
      }
    }
  }
}

$('input[name="selectHasilKegiatan"]').change(function() {
  var radio = $(this).val();
  $(".file-block").addClass("d-none");
  $(".summernote-block").addClass("d-none");

  if (radio == 1) {
    $(".file-block").removeClass("d-none");
  } else if (radio == 2) {
    $(".summernote-block").removeClass("d-none");
  }
});

$(document).on('change', 'select[multiple="multiple"]', function() {
  // Manipulasi data
  var selectedValues = $(this).val();
  var selectElement = $(this);


  // Hapus elemen-elemen yang dipilih jika memiliki atribut selected
  if (selectedValues) {
    selectedValues.forEach(function(value) {
      var option = selectElement.find('option[value="' + value + '"]');
      if (option.is(':selected')) {
        option.remove();
      }
    });
  }

  for (var i = (selectedValues.length) - 1; i >=0 ; i--) {
    var newOption = $('<option>', {
      value: selectedValues[i],
      text: selectedValues[i],
      selected: 'true'
    });
    $(this).prepend(newOption);
  }
  
  // ================================================================================== 
  var isi = $(this).val()
  // Ambil elemen input sebelumnya
  var $input = $(this).prev('input');

  $input.val(isi)
});

function sinadiCloneDataValue(count) {
  // Manipulasi data .eq(0)
  var selectedValuesDefault = $('select[name="kota[]"]').eq(0).val();
  
  if (selectedValuesDefault) {
    $('select[name="kota[]"]').eq(count).val(selectedValuesDefault).trigger('change');
    
    // Ambil elemen input sebelumnya
    var isi = $('select[name="kota[]"]').eq(count).val();
    var $input = $('select[name="kota[]"]').eq(count).prev('input');
    if ($input.length) {
      if (Array.isArray(isi)) {
        $input.val(isi.join(','));
      } else {
        $input.val(isi);
      }
    }
  }
  
  var selectedValuesDefaultAsal = $('select[name="kota_asal[]"]').eq(0).val();
  
  if (selectedValuesDefaultAsal) {
    $('select[name="kota_asal[]"]').eq(count).val(selectedValuesDefaultAsal).trigger('change');
  }

  var selectedValuesDefaultTransportasi = $('select[name="transportasi[]"]').eq(0).val();
  if (selectedValuesDefaultTransportasi) {
    $('select[name="transportasi[]"]').eq(count).val(selectedValuesDefaultTransportasi).trigger('change');
  }
}

$(document).ready(function() {
  if ($('.anggaran').val()) {   
    for(let e=0; e<$('.anggaran').length; e++){
      var changeBill = $(".anggaran").eq(e).val().replace('.', ',');
      $(".anggaran").eq(e).val(changeBill)

      sinadiPerhitunganAnggaran($(".anggaran").eq(e));
    }
  }
  $(document).on('submit', '#sinadiTambahPerjalananDinas', function(event) {
    event.preventDefault(); // Mencegah pengiriman form secara langsung

    var requiredInputs = $(this).find('input[required], select[required]');
    var formAction = $(this).attr('action'); // Get the form action attribute


    for (var i = 0; i < requiredInputs.length; i++) {
      var input = requiredInputs.eq(i);

      if (input.is(':hidden')) {
        continue; // Langsung ke iterasi berikutnya jika elemen tersembunyi
      }

      var inputVal = input.val();

      if (inputVal === '') {
        alert('Harap isi semua input dan pilih opsi yang diperlukan sebelum mengirimkan formulir.');
        // Arahkan fokus ke elemen yang kosong dengan animasi scroll
        $('html, body').animate({
          scrollTop: input.offset().top - 100 // Sesuaikan offset sesuai kebutuhan Anda
        }, 1000);
        input.focus();
        return; // Menghentikan eksekusi jika ada elemen kosong
      }
    }
    this.submit();
  });
});

function sinadiPerhitunganAnggaran(e) {
  var elem = $(".anggaran")[0];
  var classList = elem.classList;
  var index = $('.perhitungan_anggaran').index($(e).closest('.perhitungan_anggaran'));
  
  var transport_hari = $(".transport_hari").eq(index).val().replace(/\./g,'').replace(/,/g, '.');
  var transport_transport = $(".transport_transport").eq(index).val().replace(/\./g,'').replace(/,/g, '.');
  var harian_hari = $(".harian_hari").eq(index).val().replace(/\./g,'').replace(/,/g, '.');
  var harian_unit_cost = $(".harian_unit_cost").eq(index).val().replace(/\./g,'').replace(/,/g, '.');
  var penginapan_kali = $(".penginapan_kali").eq(index).val().replace(/\./g,'').replace(/,/g, '.');
  var penginapan_unit_cost = $(".penginapan_unit_cost").eq(index).val().replace(/\./g,'').replace(/,/g, '.');

  if(transport_hari != '' && transport_transport != ''){
    $(".transport_jumlah").eq(index).val("Rp"+(transport_hari*transport_transport).toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
  }else if(transport_hari != '' || transport_transport != ''){
    $(".transport_jumlah").eq(index).val(0)
  }
  if(harian_hari != '' && harian_unit_cost != ''){
    $(".harian_jumlah").eq(index).val("Rp"+(harian_hari*harian_unit_cost).toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
  }else if(harian_hari != '' || harian_unit_cost != ''){
    $(".harian_jumlah").eq(index).val(0)
  }
  if(penginapan_kali != '' && penginapan_unit_cost != ''){
    $(".penginapan_jumlah").eq(index).val("Rp"+(penginapan_kali*penginapan_unit_cost).toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));
  }else if(penginapan_kali != '' || penginapan_unit_cost != ''){
    $(".penginapan_jumlah").eq(index).val(0)
  }
  
  var transport_jumlah = $(".transport_jumlah").eq(index).val().replace(/[^\d,]/g, '').replace(/,/g, '.');
  var harian_jumlah = $(".harian_jumlah").eq(index).val().replace(/[^\d,]/g, '').replace(/,/g, '.');
  var penginapan_jumlah = $(".penginapan_jumlah").eq(index).val().replace(/[^\d,]/g, '').replace(/,/g, '.');

  if(transport_jumlah == '')transport_jumlah = 0;
  if(harian_jumlah == '')harian_jumlah = 0;
  if(penginapan_jumlah == '')penginapan_jumlah = 0;

  var hasil = (parseFloat(transport_jumlah)+parseFloat(harian_jumlah)+parseFloat(penginapan_jumlah));
    $(".jumlah_transfer").eq(index).val("Rp"+(hasil).toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 }));

  // Mengambil nilai inputan
  var val = $(e).val();
  // Menghilangkan karakter selain angka dan koma
  val = val.replace(/[^0-9,]/g, '');
  // Memformat angka dengan menambahkan titik pada setiap ribuan
  val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  // Menampilkan nilai yang sudah diformat kembali ke inputan
  $(e).val(val);
}

function sinadiHapusPeserta(e, id) {
  // Tes Hapus Data  
  
  Swal.fire({
    title: 'Hapus Data yang dipilih?',
    showDenyButton: true,
    showCancelButton: false,
    icon: 'warning',
    confirmButtonText: 'Konfirm..',
    denyButtonText: `Tidak`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      $.ajax({
        url: '/sinadi/dlt/sinadiHapusPeserta/'+id,
        method: 'DELETE'
      }).done(function() {
        if($(e).text() == "PERSEORANGAN" || $(e).text() == "PEGAWAI NO 1"){
        }else{
          if($("#sinadi-kategori").val() != "Perseorangan"){
            $(e).parent().remove();
            hitungBanyakData()
          }
        }
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Data informasi berhasil dihapuskan!',
            showConfirmButton: true,
            allowOutsideClick: false,
          },
          setTimeout(() => {
          }, "500"))
      }).fail(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
        })
      });
    } else if (result.isDenied) {
      Swal.fire('Informasi data tidak dihapus', '', 'info')
      Swal.fire({
        icon: 'info',
        title: 'Data masih tersimpan pada sistem',
        showConfirmButton: false,
        timer: 1500,
        allowOutsideClick: false
      })
    }
  })
}