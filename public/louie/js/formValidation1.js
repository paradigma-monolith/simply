$(document).ready(function() {

  $('#tambahPegawai #nama').change(function() {
    var nama = $('#nama').val()
    $('#nama_pegawai').val(nama)
  });


  // add the rule here
  jQuery.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0 && value != "";
  }, "No space please and don't leave it empty");

  $.validator.addMethod("valueNotEquals", function(value, element, arg) {
    return arg !== value;
  }, "Value must not equal arg.");

  $.validator.addMethod("maxFileSize", function(value, element, param) {
    // Cek ukuran file yang diunggah
    return this.optional(element) || (element.files[0].size <= param);
  }, "File terlalu besar");

  $.validator.addMethod("pdf", function(value, element) {
      // Cek apakah file yang diunggah adalah file PDF
      return this.optional(element) || (value.match(/\.pdf$/i));
  }, "File harus berupa dokumen PDF");


  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahPengguna').validate({
      rules: {
        role_access: {
          required: true
        },
        nama: {
          required: true,
          maxlength: 50,
        },
        username: {
          required: true,
          noSpace: true,
          minlength: 8,
          maxlength: 25
        },
        password: {
          required: true,
          minlength: 8,
          maxlength: 255
        },
        password2: {
          required: true,
          equalTo: "#password"
        }
      },
      messages: {
        role_access: {
          required: "Pilih akses pengguna!"
        },
        nama: {
          required: "Masukkan kolom nama!",
          maxlength: "Panjang nama yang dimasukkan maksimal 50 karakter"
        },
        username: {
          required: "Masukkan kolom username",
          noSpace: "Masukkan kolom username tanpa spasi!",
          minlength: "Panjang nama yang dimasukkan minimal 8 karakter",
          maxlength: "Panjang nama yang dimasukkan maksimal 25 karakter"
        },
        password: {
          required: "Masukkan kolom password!",
          minlength: "Panjang password yang dimasukkan minimal 8 karakter",
          maxlength: "Password yang dimasukkan terlalu panjang"
        },
        password2: {
          required: "Masukkan kolom konfirmasi password!",
          equalTo: "Password yang dimasukkan tidak sama!"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahUnitKerja').validate({
      rules: {
        unit_kerja: {
          required: true,
          maxlength: 128
        },
        lokasi: {
          required: true,
          maxlength: 25,
        }
      },
      messages: {
        unit_kerja: {
          required: "Masukkan kolom unit kerja!",
          maxlength: "Panjang nama yang dimasukkan maksimal 128 karakter"
        },
        lokasi: {
          required: "Masukkan kolom nama!",
          maxlength: "Panjang nama yang dimasukkan maksimal 25 karakter"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahPegawai').validate({
      rules: {
        role_access: {
          required: true
        },
        nama: {
          required: true,
          maxlength: 50
        },
        username: {
          required: true,
          noSpace: true,
          minlength: 8,
          maxlength: 25
        },
        password: {
          required: true,
          minlength: 8,
          maxlength: 255
        },
        password2: {
          required: true,
          equalTo: "#password"
        },
        unit_kerja: {
          required: true
        },
        jabatan: {
          required: true,
        },
        nip: {
          required: true,
          digits: true,
          minlength: 18,
          maxlength: 18
        },
        golongan: {
          required: true,
        },
        tahun: {
          required: true,
          digits: true,
          min: 0
        },
        bulan: {
          required: true,
          digits: true,
          min: 0,
          max: 12
        },
        hari: {
          required: true,
          digits: true,
          min: 0,
          max: 31
        },
        tgl_masuk: {
          required: true,
        }
      },
      messages: {
        role_access: {
          required: "Pilih akses pengguna!"
        },
        nama: {
          required: "Masukkan kolom nama!",
          maxlength: "Panjang nama yang dimasukkan maksimal 50 karakter"
        },
        username: {
          required: "Masukkan kolom username",
          noSpace: "Masukkan kolom username tanpa spasi!",
          minlength: "Panjang nama yang dimasukkan minimal 8 karakter",
          maxlength: "Panjang nama yang dimasukkan maksimal 25 karakter"
        },
        password: {
          required: "Masukkan kolom password!",
          minlength: "Panjang password yang dimasukkan minimal 8 karakter",
          maxlength: "Password yang dimasukkan terlalu panjang"
        },
        password2: {
          required: "Masukkan kolom konfirmasi password!",
          equalTo: "Password yang dimasukkan tidak sama!"
        },
        unit_kerja: {
          required: "Pilih unit kerja!"
        },
        jabatan: {
          required: "Masukkan kolom jabatan!",
          maxlength: "Panjang jabatan yang dimasukkan maksimal 128 karakter"
        },
        nip: {
          required: "Masukkan kolom NIP!",
          digits: "Hanya angka yang boleh diisi!",
          minlength: "Panjang NIP yang dimasukkan 18 karakter",
          maxlength: "Panjang NIP yang dimasukkan 18 karakter"
        },
        golongan: {
          required: "Masukkan kolom golongan!",
          maxlength: "Panjang golongan yang dimasukkan 10 karakter"
        },
        tahun: {
          required: "Masukkan kolom tahun! <br/>",
          digits: "Hanya angka yang boleh diisi! <br/>",
          min: "Jumlah lamanya tahun sama dengan/lebih besar dari 0 <br/>"
        },
        bulan: {
          required: "Masukkan kolom bulan! <br/>",
          digits: "Hanya angka yang boleh diisi! <br/>",
          min: "Jumlah lamanya bulan minimal sama dengan/lebih besar dari 0 <br/>",
          max: "Jumlah lamanya bulan maximal 12 <br/>"
        },
        hari: {
          required: "Masukkan kolom hari! <br/>",
          digits: "Hanya angka yang boleh diisi! <br/>",
          min: "Jumlah lamanya bulan minimal sama dengan/lebih besar dari 0 <br/>",
          max: "Jumlah lamanya bulan maximal 31 <br/>"
        },
        tgl_masuk: {
          required: "Harus memilih tanggal masuk",
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },

    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahKapal').validate({
      rules: {
        kapal_datang_luar: {
          digits: true,
          min: 0
        },
        abk_datang_luar: {
          digits: true,
          min: 0
        },
        penumpang_datang_luar: {
          digits: true,
          min: 0
        },
        kapal_datang_negeri: {
          digits: true,
          min: 0
        },
        abk_datang_negeri: {
          digits: true,
          min: 0
        },
        penumpang_datang_negeri: {
          digits: true,
          min: 0
        },
        kapal_berangkat_luar: {
          digits: true,
          min: 0
        },
        abk_berangkat_luar: {
          digits: true,
          min: 0
        },
        penumpang_berangkat_luar: {
          digits: true,
          min: 0
        },
        kapal_berangkat_negeri: {
          digits: true,
          min: 0
        },
        abk_berangkat_negeri: {
          digits: true,
          min: 0
        },
        penumpang_berangkat_negeri: {
          digits: true,
          min: 0
        },
      },
      messages: {
        kapal_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        abk_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        kapal_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        abk_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        kapal_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        abk_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        kapal_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        abk_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },

    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahPesawat').validate({
      rules: {
        pesawat_datang_luar: {
          digits: true,
          min: 0
        },
        crew_datang_luar: {
          digits: true,
          min: 0
        },
        penumpang_datang_luar: {
          digits: true,
          min: 0
        },
        pesawat_datang_negeri: {
          digits: true,
          min: 0
        },
        crew_datang_negeri: {
          digits: true,
          min: 0
        },
        penumpang_datang_negeri: {
          digits: true,
          min: 0
        },
        pesawat_berangkat_luar: {
          digits: true,
          min: 0
        },
        crew_berangkat_luar: {
          digits: true,
          min: 0
        },
        penumpang_berangkat_luar: {
          digits: true,
          min: 0
        },
        pesawat_berangkat_negeri: {
          digits: true,
          min: 0
        },
        crew_berangkat_negeri: {
          digits: true,
          min: 0
        },
        penumpang_berangkat_negeri: {
          digits: true,
          min: 0
        },
      },
      messages: {
        pesawat_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        crew_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        pesawat_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        crew_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        pesawat_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        crew_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        pesawat_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        crew_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        penumpang_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },

    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahVaksin').validate({
      rules: {
        phqc: {
          digits: true,
          min: 0
        },
        cop: {
          digits: true,
          min: 0
        },
        sscec: {
          digits: true,
          min: 0
        },
        sscc: {
          digits: true,
          min: 0
        },
        p3k: {
          digits: true,
          min: 0
        },
        icv: {
          digits: true,
          min: 0
        },
        sklt: {
          digits: true,
          min: 0
        },
        siaos: {
          digits: true,
          min: 0
        },
        siaj: {
          digits: true,
          min: 0
        },
        kir: {
          digits: true,
          min: 0
        },
        vaksin_mm: {
          digits: true,
          min: 0
        },
        vaksin_yf: {
          digits: true,
          min: 0
        },
        vaksin_covid: {
          digits: true,
          min: 0
        },
      },
      messages: {
        phqc: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        cop: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        sscec: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        sscc: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        p3k: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        icv: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        sklt: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        siaos: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        siaj: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        kir: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        vaksin_mm: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        vaksin_yf: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
        vaksin_covid: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },

    });
  });
  $(function() {
    $('#validationKapal').validate({
      rules: {
        kapal_datang_luar: {
          digits: true,
          min: 0,
          equalTo: "#kapal_datang_luar0"
        },
        abk_datang_luar: {
          digits: true,
          min: 0,
          equalTo: "#abk_datang_luar0"
        },
        penumpang_datang_luar: {
          digits: true,
          min: 0,
          equalTo: "#penumpang_datang_luar0"
        },
        kapal_datang_negeri: {
          digits: true,
          min: 0,
          equalTo: "#kapal_datang_negeri0"
        },
        abk_datang_negeri: {
          digits: true,
          min: 0,
          equalTo: "#abk_datang_negeri0"
        },
        penumpang_datang_negeri: {
          digits: true,
          min: 0,
          equalTo: "#penumpang_datang_negeri0"
        },
        kapal_berangkat_luar: {
          digits: true,
          min: 0,
          equalTo: "#kapal_berangkat_luar0"
        },
        abk_berangkat_luar: {
          digits: true,
          min: 0,
          equalTo: "#abk_berangkat_luar0"
        },
        penumpang_berangkat_luar: {
          digits: true,
          min: 0,
          equalTo: "#penumpang_berangkat_luar0"
        },
        kapal_berangkat_negeri: {
          digits: true,
          min: 0,
          equalTo: "#kapal_berangkat_negeri0"
        },
        abk_berangkat_negeri: {
          digits: true,
          min: 0,
          equalTo: "#abk_berangkat_negeri0"
        },
        penumpang_berangkat_negeri: {
          digits: true,
          min: 0,
          equalTo: "#penumpang_berangkat_negeri0"
        },
      },
      messages: {
        kapal_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        abk_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        penumpang_datang_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        kapal_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        abk_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        penumpang_datang_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        kapal_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        abk_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        penumpang_berangkat_luar: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        kapal_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        abk_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
        penumpang_berangkat_negeri: {
          digits: "Hanya angka yang boleh diisi!",
          min: "Jumlah pada kolom tidak boleh kurang dari 0",
          equalTo: "Data yang dimasukkan tidak sesuai"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },

    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahTitimangsa').validate({
      rules: {
        surat: {
          required: true
        },
        unit_kerja: {
          required: true
        },
        pejabat: {
          required: true
        },
        atasan: {
          required: true
        },
      },
      messages: {
        surat: {
          required: "Pilih jenis surat!"
        },
        unit_kerja: {
          required: "Pilih unit kerja untuk bagian dari surat!"
        },
        pejabat: {
          required: "Pilih data atasan!"
        },
        atasan: {
          required: "Pilih data atasan!"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    
    $('#tambahDataCuti').validate({
      rules: {
        pegawai: {
          required: true
        },
        jenis_cuti: {
          required: true
        },
        alasan_cuti: {
          required: true,
          minlength: 1,
          maxlength: 250
        },
        alamat_cuti: {
          required: true,
          minlength: 1,
          maxlength: 250
        },
        skema_titimangsa: {
          required: true
        },
        no_telp: {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 15
        },
        tanggal_pengajuan: {
          required: true
        },
      },
      messages: {
        pegawai: {
          required: "Pilih data pegawai!"
        },
        jenis_cuti: {
          required: "Pilih jenis cuti!"
        },
        alasan_cuti: {
          required: "Tuliskan alasan anda!",
          minlength: "Panjang alasan terlalu sedikit",
          maxlength: "Panjang alasan yang dimasukkan maksimal 250 karakter"
        },
        alamat_cuti: {
          required: "Tuliskan alamat selama anda menjalankan cuti!",
          minlength: "Panjang alasan terlalu sedikit",
          maxlength: "Panjang alasan yang dimasukkan maksimal 250 karakter"
        },
        skema_titimangsa: {
          required: "Pilih skema yang akan dimasukkan pada surat!"
        },
        no_telp: {
          required: "Masukkan nomor telepon!",
          digits: "Hanya angka yang boleh diisi!",
          minlength: "Panjang nomor sama dengan/lebih besar dari 10",
          maxlength: "Panjang nomor sama dengan/lebih kecil dari 15"
        },
        tanggal_pengajuan: {
          required: "Masukkan tanggal pengajuan!"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#tambahDataPermohonan').validate({
      rules: {
        pegawai: {
          required: true
        },
        jenis_permohonan: {
          required: true
        },
        alasan_permohonan: {
          required: true,
          minlength: 1,
          maxlength: 250
        },
        tanggal: {
          required: true,
        },
        skema_titimangsa: {
          required: true
        },
        no_telp: {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 15
        },
        tanggal_pengajuan: {
          required: true
        }
      },
      messages: {
        pegawai: {
          required: "Pilih data pegawai!"
        },
        jenis_permohonan: {
          required: "Pilih jenis permohonan!"
        },
        alasan_permohonan: {
          required: "Tuliskan alasan anda!",
          minlength: "Panjang alasan terlalu sedikit",
          maxlength: "Panjang alasan yang dimasukkan maksimal 250 karakter"
        },
        tanggal: {
          required: "Tuliskan alamat selama anda menjalankan cuti!",
        },
        skema_titimangsa: {
          required: "Pilih skema yang akan dimasukkan pada surat!"
        },
        no_telp: {
          required: "Masukkan kolom tahun!",
          digits: "Hanya angka yang boleh diisi!",
          minlength: "Panjang nomor sama dengan/lebih besar dari 10",
          maxlength: "Panjang nomor sama dengan/lebih kecil dari 15"
        },
        tanggal_pengajuan: {
          required: "Masukkan tanggal pengajuan!"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    // $.validator.setDefaults({
    //     submitHandler: function() {
    //         alert("Memproses data!");
    //     }
    // });
    $('#updateValidationCuti').validate({
      rules: {
        status: {
          required: true
        },
        keterangan: {
          required: true,
          minlength: 1,
          maxlength: 250
        },
      },
      messages: {
        pegawai: {
          required: "Pilih status validation!"
        },
        keterangan: {
          required: "Masukkan kolom keterangan!",
          minlength: "Panjang keterangan sama dengan/lebih besar dari 10",
          maxlength: "Panjang keterangan sama dengan/lebih kecil dari 250"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  // VALIDASI SISTEM SOP
  $(function() {
    $('#tambahDataSop').validate({
      rules: {
        no_dokumen: {
          required: true,
        },
        judul: {
          required: true,
          maxlength: 255
        },
        unit_kerja: {
          required: true,
        },
        jenis_dokumen: {
          required: true,
        },
        unggah_berkas_sop: {
          required: true,
          extension: "pdf",
          filesize: 5242880
        },
        status: {
          required: true,
        },
        tanggal_pembuatan: {
          required: true,
        },
        tanggal_efektif: {
          required: true,
        }
      },
      messages: {
        no_dokumen: {
          required: "Masukkan nomor dokumen!",
        },
        judul: {
          required: "Masukkan nama dokumen!",
          maxlength: "Panjang nama yang dimasukkan maksimal 255 karakter"
        },
        unit_kerja: {
          required: "Pilih divisi!"
        },
        jenis_dokumen: {
          required: "Pilih divisi terlebih dahulu!"
        },
        unggah_berkas_sop: {
          required: "Tidak ada dokumen yang diunggah!",
          extension: "Dokumen bukan PDF!",
          filesize: "Ukuran dokumen terlalu besar!"
        },
        status: {
          required: "Pilih status pengesahan!"
        },
        tanggal_pembuatan: {
          required: "Tanggal tidak boleh kosong!"
        },
        tanggal_efektif: {
          required: "Tanggal tidak boleh kosong!"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    $('#tambahSipeditArsip').validate({
      rules: {
        pegawai: {
          required: true,
        },
        nama_pelatihan: {
          required: true,
          maxlength: 255
        },
        lembaga_pelatihan: {
          required: true,
          maxlength: 255
        },
        kategori: {
          required: true,
        },
        tanggal: {
          required: true,
        },
        jumlah_jpl: {
          required: true,
          number: true,
          min: 1
        },
        unggah_berkas_pelatihan: {
          required: true,
        //   extension: "pdf",
          filesize: 5242880
        }
      },
      messages: {
        pegawai: {
          required: "Data tersebut belum terpilih",
        },
        nama_pelatihan: {
          required: "Data harus dimasukkan dengan benar",
          maxlength: "Anda melebihi batas maksimal panjang karakter"
        },
        lembaga_pelatihan: {
          required: "Data harus dimasukkan dengan benar",
          maxlength: "Anda melebihi batas maksimal panjang karakter"
        },
        kategori: {
          required: "Data tersebut belum terpilih",
        },
        tanggal: {
          required: "Pilih tanggal dengan benar",
        },
        jumlah_jpl: {
          required: "Data harus dimasukkan dengan benar",
          digits: "Data yang dimasukkan hanya berbentuk bilangan",
          min: "Anda memasukkan angka terlalu kecil"
        },
        unggah_berkas_pelatihan: {
          required: "Tidak ada arsip yang diunggah!",
        //   extension: "Data yang dimasukkan bukan type PDF",
          filesize: "Anda melebihi batas maksimal file size yang dapat di upload "
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  $(function() {
    $('#ubahDataSop').validate({
      rules: {
        no_dokumen: {
          required: true,
        },
        judul: {
          required: true,
          maxlength: 255
        },
        unit_kerja: {
          required: true,
        },
        jenis_dokumen: {
          required: true,
        },
        unggah_berkas_sop: {
          required: true,
          extension: "pdf",
          filesize: 5242880
        },
        status: {
          required: true,
        }
      },
      messages: {
        no_dokumen: {
          required: "Masukkan nomor dokumen!",
        },
        judul: {
          required: "Masukkan nama dokumen!",
          maxlength: "Panjang nama yang dimasukkan maksimal 255 karakter"
        },
        unit_kerja: {
          required: "Pilih unit kerja!"
        },
        jenis_dokumen: {
          required: "Pilih jenis dokumen!"
        },
        unggah_berkas_sop: {
          required: "Tidak ada dokumen yang diunggah!",
          extension: "Dokumen bukan PDF!",
          filesize: "Ukuran dokumen terlalu besar!"
        },
        status: {
          required: "Pilih status pengesahan!"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  $(function() {
    $('#sinadiTambahPerjalananDinas').validate({
      rules: {
          kategori: {
              required: true,
          },
          nama_kegiatan: {
              required: true,
              maxlength: 400
          },
          tgl_pelaksanaan: {
              required: true,
          },
          tgl_pelaksanaan_akhir: {
              required: true,
          },
          tempat_perjalanan: {
              required: true,
              maxlength: 200
          },
          mata_anggaran: {
              required: true,
              maxlength: 200
          },
          'nip[]': {
              required: true
          },
          'nama[]': {
              required: true
          },
          'jabatan[]': {
            required: true
          },
          'golongan[]': {
              required: true
          },
          'manualNip[]': {
              required: true
          },
          'manualNama[]': {
              required: true
          },
          'manualJabatan[]': {
              required: true
          },
          'manualGolongan[]': {
              required: true
          },
          'tgl_pergi[]': {
              required: true
          },
          'tgl_pulang[]': {
              required: true
          },
          'kota_asal[]': {
              required: true
          },
          'no_surat_tugas[]': {
              required: true
          },
          'tgl_surat_tugas[]': {
              required: true
          },
          'transportasi[]': {
              required: true
          },
          'transport_hari[]': {
              required: true
          },
          'transport_nominal[]': {
              required: true
          },
          // 'transport_jumlah[]': {
          //     required: true
          // },
          'harian_hari[]': {
              required: true
          },
          'harian_nominal[]': {
            required: true
          },
          // 'harian_jumlah[]': {
          //   required: true
          // },
          'penginapan_hari[]': {
              required: true
          },
          'penginapan_nominal[]': {
              required: true
          },
          // 'penginapan_jumlah[]': {
          //     required: true
          // },
          // 'jumlah_transfer[]': {
          //     required: true
          // },
          sinadi_pic: {
            required: true
          }
      },
      messages: {
          kategori: {
              required: "Kategori tidak boleh kosong!",
          },
          nama_kegiatan: {
              required: "Nama kegiatan tidak boleh kosong!",
              maxlength: "Panjang nilai yang dimasukkan maksimal 400 karakter"
          },
          tgl_pelaksanaan: {
            required: "Tanggal pelaksanaan tidak boleh kosong!",
          },
          tgl_pelaksanaan_akhir: {
            required: "Tanggal pelaksanaan tidak boleh kosong!",
          },
          tempat_perjalanan: {
            required: "Tempat perjalanan tidak boleh kosong!",
            maxlength: "Panjang nilai yang dimasukkan maksimal 200 karakter"
          },
          mata_anggaran: {
            required: "Input tanda garis tengah `-` jika tidak memiliki data mata anggaran!",
            maxlength: "Panjang nilai yang dimasukkan maksimal 200 karakter"
          },
          'nip[]': {
              required: "NIP tidak boleh kosong!",
          },
          'nama[]': {
              required: "Nama tidak boleh kosong!",
          },
          'jabatan[]': {
              required: "Jabatan tidak boleh kosong!",
          },
          'golongan[]': {
              required: "Golongan tidak boleh kosong!",
          },
          'manualNip[]': {
              required: "NIP tidak boleh kosong!",
          },
          'manualNama[]': {
              required: "Nama tidak boleh kosong!",
          },
          'manualJabatan[]': {
              required: "Jabatan tidak boleh kosong!",
          },
          'manualGolongan[]': {
              required: "Golongan tidak boleh kosong!",
          },
          'tgl_pergi[]': {
            required: "Tanggal pergi tidak boleh kosong!",
          },
          'tgl_pulang[]': {
              required: "Tanggal pulang tidak boleh kosong!",
          },
          'kota_asal[]': {
              required: "Kota asal tidak boleh kosong!",
          },
          'no_surat_tugas[]': {
              required: "No surat tugas tidak boleh kosong!",
          },
          'tgl_surat_tugas[]': {
              required: "Tanggal surat tugas tidak boleh kosong!",
          },
          'transportasi[]': {
              required: "Harus memilih jenis transportasi!",
          },
          'transport_hari[]': {
              required: "Isi dengan <u>></u> 0!",
          },
          'transport_nominal[]': {
            required: "Isi dengan <u>></u> 0!",
          },
          // 'transport_jumlah[]': {
          //   required: "Isi dengan <u>></u> 0!",
          // },
          'harian_hari[]': {
            required: "Isi dengan <u>></u> 0!",
          },
          'harian_nominal[]': {
            required: "Isi dengan <u>></u> 0!",
          },
          // 'harian_jumlah[]': {
          //   required: "Isi dengan <u>></u> 0!",
          // },
          'penginapan_hari[]': {
            required: "Isi dengan <u>></u> 0!",
          },
          'penginapan_nominal[]': {
            required: "Isi dengan <u>></u> 0!",
          },
          // 'penginapan_jumlah[]': {
          //   required: "Isi dengan <u>></u> 0!",
          // },
          // 'jumlah_transfer[]': {
          //   required: "Isi dengan <u>></u> 0!",
          // },
          sinadi_pic: {
            required: "PIC tidak boleh kosong!",
          }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        // Cek apakah elemen adalah elemen Select2
        error.addClass('invalid-feedback');
        if (element.hasClass('select2')) {
          error.insertAfter(element.next());
        } else if (element.hasClass('summernote')) {
          error.insertAfter(element.siblings('.note-editor'));
        } else {
          error.insertAfter(element);
        }
    },
      highlight: function(element, errorClass, validClass) {
          $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
      }
    });  
  });
  
  $(function() {
    $('#wbkSimpanRencanaKerja').validate({
      rules: {
        kegiatan: {
          required: true,
        },
        indikator: {
          required: true,
        },
        bukti_fisik: {
          required: true,
        },
        'target_waktu[]': {
          required: true,
          minlength: 1, // Ensure at least one option is selected
        },
      },
      messages: {
        kegiatan: {
          required: "Data belum diisi!",
        },
        indikator: {
          required: "Data belum diisi!",
        },
        bukti_fisik: {
          required: "Data belum diisi!",
        },
        'target_waktu[]': {
          required: "Data belum diisi! Minimal isi satu target waktu.",
          minlength: "Data belum diisi! Minimal isi satu target waktu.",
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  $(function() {
    $('#wbkSimpanFileWbk').validate({
      rules: {
        keterangan: {
          required: true,
        },
        unggah_dokumen: {
          required: true,
          extension: "pdf|jpg|jpeg|png",
        },
        target_waktu: {
          required: true,
        },
        kategori: {
          required: true,
        },
        sub_kategori: {
          required: true,
        }
      },
      messages: {
        keterangan: {
          required: "Data belum diisi!",
        },
        unggah_dokumen: {
          required: "Berkas pelatihan belum diunggah!",
          extension: "Format dokumen tidak valid. Hanya file PDF, JPG, JPEG, atau PNG yang diperbolehkan.",
        },
        target_waktu: {
          required: "Data belum diisi!",
        },
        kategori: {
          required: "Kategori harus dipilih!",
        },
        sub_kategori: {
          required: "Sub Kategori harus dipilih!",
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

  $(function() {
    $('#arkabSimpanSurat').validate({
      rules: {
        nomor_surat: {
          required: true,
        },
        unggah_dokumen: {
          required: true,
          extension: "pdf|jpg|jpeg|png",
        },
        jenis_surat: {
          required: true,
        },
        kode_klasifikasi: {
          required: true,
        },
      },
      messages: {
        nomor_surat: {
          required: "Data belum diisi!",
        },
        unggah_dokumen: {
          required: "Berkas pelatihan belum diunggah!",
          extension: "Format dokumen tidak valid. Hanya file PDF, JPG, JPEG, atau PNG yang diperbolehkan.",
        },
        jenis_surat: {
          required: "Data belum diisi!",
        },
        kode_klasifikasi: {
          required: "Data belum diisi!",
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
});