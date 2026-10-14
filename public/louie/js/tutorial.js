$(document).ready(function () {
  Swal.fire({
    title: 'Ayo perbaharui password anda!',
    html: 'Sekarang sudah ada pengaturan untuk akun anda, silahkan lakukan pembaharuan terhadap akun anda pada menu "Akun Saya" di pojok kanan pada layar anda... <br/><h5><strong>Ingin melihat tutorial?</strong></h5>',
    showDenyButton: true,
    showCancelButton: false,
    allowOutsideClick: false,
    icon: 'warning',
    confirmButtonText: 'Ya, saya ingin melihatnya..',
    denyButtonText: `Tidak untuk saat ini!`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      tutorialGantiPasswordModal()
    } else if (result.isDenied) {
      Swal.fire({
        icon: 'info',
        title: 'Baiklah, tapi jangan lupa perbaharui ya..',
        showConfirmButton: false,
        timer: 2500,
        allowOutsideClick: false
      })
      setTimeout(() => {
        getInformationAgenda()
      }, 2500);
    }
  })
});

function tutorialGantiPasswordModal() {
  $.ajax({
    url: `/home/get/tutorialGantiPasswordModal`,
    dataType: 'json',
    success: function(response) {
      if (response.data) {
        $("#tutorialGantiPasswordModal").find(".modal-body").html(response.data)
        $("#tutorialGantiPasswordModal").modal("show")
      }
    },error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'info',
        title: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        showConfirmButton: false,
        // timer: 2500,
        allowOutsideClick: false
      })
    }
  });
}
