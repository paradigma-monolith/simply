$(function(){
  var newCanvas = document.getElementById("signature-pad");
  var newSignaturePad = new SignaturePad(newCanvas);
  $('form').submit(function(event) {
    // var newSignatureData = newSignaturePad.toDataURL('image/svg+xml');
    var newSignatureData = newSignaturePad.toDataURL();
    $('#signature').val(newSignatureData);
  });
  // TandaTangan
  // script di dalam ini akan dijalankan pertama kali saat dokumen dimuat
  document.addEventListener('DOMContentLoaded', function() {
    resizeCanvas();
  });

  //script ini berfungsi untuk menyesuaikan tanda tangan dengan ukuran canvas
  function resizeCanvas() {
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
  }

  var canvas = document.getElementById('signature-pad');

  //warna dasar signaturepad
  var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)'
  });

  //saat tombol clear diklik maka akan menghilangkan seluruh tanda tangan
  document.getElementById('clear').addEventListener('click', function() {
    signaturePad.clear();    
    newCanvas = document.getElementById("signature-pad");
    newSignaturePad = new SignaturePad(newCanvas);
    canvas = document.getElementById('signature-pad');
    signaturePad = new SignaturePad(canvas, {
      backgroundColor: 'rgb(255, 255, 255)'
    });
  });

  //saat tombol undo diklik maka akan mengembalikan tanda tangan sebelumnya
  // document.getElementById('undo').addEventListener('click', function() {
  //   var data = signaturePad.toData();
  //   if (data) {
  //     data.pop(); // remove the last dot or line
  //     signaturePad.fromData(data);
  //   }
  // });

  //saat tombol change color diklik maka akan merubah warna pena
  document.getElementById('change-color').addEventListener('click', function() {

    //jika warna pena biru maka buat menjadi hitam dan sebaliknya
    if (signaturePad.penColor == "rgba(0, 0, 255, 1)") {

      signaturePad.penColor = "rgba(0, 0, 0, 1)";
    } else {
      signaturePad.penColor = "rgba(0, 0, 255, 1)";
    }
  })
  
})