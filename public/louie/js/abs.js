$(document).ready(function () {
  absGetLantai();
  absGetRuang();
  absGetJenisBarang();
});

function absGetLantai() {
  var div = "#absLantai";
  $.ajax({
    url: "/abs/getLantai",
    method: "get",
    dataType: "json",
    beforeSend: function () {
      $(div).html(
        `<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`
      );
    },
    success: function (response) {
      if (response.data) {
        $(div).html(response.data);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
}

function absGetRuang() {
  var div = "#absRuang";
  $.ajax({
    url: "/abs/getRuang",
    method: "get",
    dataType: "json",
    beforeSend: function () {
      $(div).html(
        `<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`
      );
    },
    success: function (response) {
      if (response.data) {
        $(div).html(response.data);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
}

function absGetJenisBarang() {
  var div = "#absJenisBarang";
  $.ajax({
    url: "/abs/getJenisBarang",
    method: "get",
    dataType: "json",
    beforeSend: function () {
      $(div).html(
        `<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`
      );
    },
    success: function (response) {
      if (response.data) {
        $(div).html(response.data);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
}

$(document).on("submit", "#absSimpanDataLantai", function (e) {
  e.preventDefault();

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr("action"),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if(response.sukses) {
        Toast.fire({
          icon: "success",
          title: "Data berhasil disimpan.",
        });
        absGetLantai();
      }else {
        Toast.fire({
          icon:"warning",
          title:response.error
        })
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
});

$(document).on("submit", "#absSimpanDataRuang", function (e) {
  e.preventDefault();

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr("action"),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if(response.sukses) {
        Toast.fire({
          icon: "success",
          title: "Data berhasil disimpan.",
        });
        absGetRuang();
      }else {
        Toast.fire({
          icon:"warning",
          title:response.error
        })
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
});

$(document).on("submit", "#absSimpanDataJenis", function (e) {
  e.preventDefault();

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr("action"),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if(response.sukses) {
        Toast.fire({
          icon: "success",
          title: "Data berhasil disimpan.",
        });
        absGetJenisBarang();
      }else {
        Toast.fire({
          icon:"warning",
          title:response.error
        })
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
});

$(".plus").click(function () {
  $("#newInputs").append(
    '<div class="form-group row">' +
      '<label class="col-sm-2"><span class="min">-</span></label>' +
      '<div class="col-sm-5">' +
      '<input type="text" name="label[]" placeholder="Entri Informasi Barang" value="" class="form-control">' +
      "</div>" +
      '<div class="col-sm-5">' +
      '<input type="text" name="isi_label[]" placeholder="...." value="" class="form-control">' +
      "</div>" +
      "</div>"
  );
});

$(document).on("click", ".min", function () {
  $(this).parent().parent().remove();
});

"", "", "", "", "", "";
function getDataBarangABS(
  label = null,
  nilai = null,
  label2 = null,
  nilai2 = null,
  label3 = null,
  nilai3 = null
) {
  // label = all
  // label = status_tersedia
  // label = jenis
  // label = unit_kerja
  // label2 = lantai
  // label3 = ruang

  formData = {
    label: label,
    label2: label2,
    label3: label3,
    nilai: nilai,
    nilai2: nilai2,
    nilai3: nilai3,
  };

  $(".perubahanDataABS").attr("disabled", true);

  $.ajax({
    url: "/abs/getDataBarang",
    data: formData,
    method: "post",
    dataType: "json",
    beforeSend: function () {
      $("#contentABS").html(
        `<div class="text-center mt-5"><h2><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></h2></div>`
      );
    },
    success: function (response) {
      if (response.data) {
        $("#contentABS").html(response.data);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      Swal.fire({
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
      });
    },
  });
}

$(document).on("change", ".select2", function () {
  $(".perubahanDataABS").attr("disabled", false);
});

// $(document).on('click', '.perubahanDataABS', function(e){
// })

$(document).on("submit", "#absUpdateLokasi", function (e) {
  e.preventDefault();
  var eDataTable = $("#tableDataABS");
  eDataTable.DataTable().destroy();

  var formData = $("#absUpdateLokasi").serialize();
  var btnElemen = $(".perubahanDataABS").html();

  $.ajax({
    url: $(this).attr("action"),
    data: formData,
    method: "post",
    dataType: "json",
    beforeSend: function () {
      // $('#overlay').show();
      $(".perubahanDataABS").attr("disabled", true);
      $(".perubahanDataABS").html(
        `<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`
      );
    },
    success: function (response) {
      $("#tableDataABS").dataTable();
      $("#overlay").hide();
      if (response.data) {
        Toast.fire({
          icon: "success",
          title: "Berhasil Tersimpan",
          text: "Lakukan refresh terhadap halaman untuk menampilkan data akurat!",
        });
      } else {
        Toast.fire({
          icon: "warning",
          title: "Gagal Tersimpan",
          text: "Terdapat beberapa data tidak tersimpan, Coba lagi!",
        });
        $(".perubahanDataABS").attr("disabled", false);
      }
      $(".perubahanDataABS").html(btnElemen);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      Swal.fire({
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
      });
    },
  });
});

$(document).on("click", ".link-action-abs-barang", function (e) {
  e.preventDefault();
  var action = $(this).data("action");
  if (action == "hapus") {
    var id = $(this).data("id");
    dltABSLokasi("/abs/dlt_lokasiABS/" + id);
  } else if (action == "edit" || action == "view") {
    window.location.href = $(this).attr("href");
  }
});

function dltABSLokasi(action) {
  Swal.fire({
    title: "Hapus Data yang dipilih?",
    showDenyButton: true,
    showCancelButton: false,
    icon: "warning",
    confirmButtonText: "Konfirm..",
    denyButtonText: `Tidak`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      $.ajax({
        url: action,
        method: "DELETE",
      })
        .done(function () {
          Swal.fire(
            {
              position: "top-end",
              icon: "success",
              title: "Data informasi berhasil dihapuskan!",
              showConfirmButton: false,
              allowOutsideClick: false,
            },
            setTimeout(() => {
              location.reload();
            }, "500")
          );
        })
        .fail(function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!",
          });
        });
    } else if (result.isDenied) {
      Swal.fire("Informasi data tidak dihapus", "", "info");
      Swal.fire({
        icon: "info",
        title: "Data masih tersimpan pada sistem",
        showConfirmButton: false,
        timer: 1500,
        allowOutsideClick: false,
      });
    }
  });
}

function loadLabelABS() {
  $.ajax({
    url: "/abs/loadLabelABS",
    method: "get",
    dataType: "json",
    beforeSend: function () {
      $("#overlay").show();
    },
    success: function (response) {
      if (response.data) {
        $("#loadLabelABSModal").find(".modal-body").html(response.data);
        $("#loadLabelABSModal").modal("show");
      }
      $("#overlay").hide();
    },
    error: function (xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        // text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
      });
    },
  });
}

$(document).on("click", ".setLabelAbs", function () {
  $("#loadLabelABSModal").modal("hide");
  let string = $(this).data("label");

  const array = ['Nama Barang', 'Merek', 'NUP', 'Kode Barang', 'Kode Satker'];
  var targetValue;
  var disabled = '';
  var min = '';

  var html = "";
  for (let i = 0; i < string.length; i++) {
    if (i === 0) {
      $(".firstLabelTambahAbs").val(string[i]);
    } else {    
    disabled = '';
    min = 'min'
    targetValue = string[i]
    if (array.includes(targetValue)) {disabled = 'disabled'; min = 'min-disabled bg-secondary';}
      html =
        html +
        '<div class="form-group row">' +
        '<label class="col-sm-2"><span class="'+ min +'">-</span></label>' +
        '<div class="col-sm-5">' +
        '<input '
        + disabled +
        ' type="text" name="label[]" placeholder="Entri Informasi Barang" value="' +
        string[i] +
        '" class="form-control">' +
        "</div>" +
        '<div class="col-sm-5">' +
        '<input type="text" name="isi_label[]" placeholder="...." value="" class="form-control">' +
        "</div>" +
        "</div>";
    }
    $("#newInputs").html(html);
    console.log(html);
  }
});
