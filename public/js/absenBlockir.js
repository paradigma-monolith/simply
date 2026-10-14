$(document).ready(function () {
  ToastError.fire({
    icon: 'error',
    text: 'Akses diblokir!'
  })

  setTimeout(() => {
    $("#overlay").show();
  }, 100);
  setTimeout(() => {
    window.location="/absen/absen_harian"
  }, 3000);
});