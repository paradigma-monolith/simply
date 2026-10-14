$(document).on("click", ".simandja-tombol-tambah", function() {

  var kategori = $(this).data("kategori");
  
  var elemen = $("#simandjaModalTambah").find('input[name="kategori"]');
  elemen.val(kategori);

  $("#simandjaModalTambah").modal("show");
})
$(document).on("click", ".simandja-tombol-edit", function() {

  var kategori = $(this).data("kategori");
//   var substansi = $(this).data("substansi");
  var tim_kerja = $(this).data("timkerja");
  var tanggal = $(this).data("tanggal");
  var deskripsi = $(this).data("deskripsi");
  var file = $(this).data("file");
  var id = $(this).data("id");
  
  var elemen1 = $("#simandjaModalEdit").find('input[name="kategori"]');
//   var elemen2 = $("#simandjaModalEdit").find('select[name="substansi"]');
  var elemen3 = $("#simandjaModalEdit").find('input[name="tanggal"]');
  var elemen4 = $("#simandjaModalEdit").find('input[name="deskripsi"]');
  var elemen5 = $("#simandjaModalEdit").find('.custom-file-label');
  var elemen6 = $("#simandjaModalEdit").find('input[name="id"]');
  var elemen7 = $("#simandjaModalEdit").find('select[name="tim_kerja"]');

  // Destroy Select2 sebelum inisialisasi ulang
//   elemen2.select2("destroy");
  elemen7.select2("destroy");
  
  elemen1.val(kategori);
//   elemen2.val(substansi);
  elemen3.val(tanggal);
  elemen4.val(deskripsi);
  elemen5.text(file);
  elemen6.val(id);
  elemen7.val(tim_kerja);

//   elemen2.select2()
  elemen7.select2()

  $("#simandjaModalEdit").modal("show");
})