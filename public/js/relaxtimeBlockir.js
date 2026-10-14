$(document).ready(function () {
  ToastError.fire({
    icon: 'error',
    text: 'Akses diblokir!'
  })

  setTimeout(() => {
    $("#overlay").show();
  }, 100);
  setTimeout(() => {
    window.location="/relaxtime/realisasiAnggaran"
  }, 3000);
});