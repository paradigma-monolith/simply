var timeoutId; // Variabel untuk menyimpan ID timeout
    var slide = 0;

    var slide_1 = $("#infografis-kedatangan-kapal").html();
    var slide_2 = $("#infografis-keberangkatan-kapal").html();
    var slide_3 = $("#infografis-kedatangan-pesawat").html();
    var slide_4 = $("#infografis-keberangkatan-pesawat").html();
    var slide_5 = $("#infografis-dokumen").html();
    var slide_6 = $("#infografis-sertifikat").html();
    var slide_7 = $("#infografis-vaksinasi").html();
    var setTime = 10000;

    $("#background-videos1").show();
    $("#background-videos1").fadeIn();
    $("#background-videos2").hide();
    $("#background-videos2").fadeOut();
    $("#background-videos3").hide();
    $("#background-videos3").fadeOut();

    $(document).ready(function() {
      // JavaScript/JQuery untuk mengubah kalimat dengan fade-out dan fade-in yang lebih smooth
      $(".atf-section-padding").hide()
      var intro
      setTimeout(function() {
        intro = $('.intro-container').html();
        $('.intro-container').html('');
      }, 3000);
      setTimeout(function() {
        $('.intro-container').html(intro)
        $('.intro-text').text("Kami menyajikan informasi terbaru seputar data kapal, pesawat, dokumen dan vaksinasi dalam bentuk infografis yang mudah dipahami.").fadeIn();
      }, 3100);
      setTimeout(function() {
        $('.intro').fadeOut(2000, function() {
          $(this).remove();
          $(".atf-section-padding").fadeIn(1000);
        });
      }, 10000);

      startTimeout();
    });

    function startTimeout() {
      timeoutId = setTimeout(function() {

        slide_1 = $("#infografis-kedatangan-kapal").html();
        slide_2 = $("#infografis-keberangkatan-kapal").html();
        slide_3 = $("#infografis-kedatangan-pesawat").html();
        slide_4 = $("#infografis-keberangkatan-pesawat").html();
        slide_5 = $("#infografis-dokumen").html();
        slide_6 = $("#infografis-sertifikat").html();
        slide_7 = $("#infografis-vaksinasi").html();

        // Kode yang akan dijalankan setelah timeout
        if (slide == 0) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Kedatangan Kapal").fadeIn();
            $("#background-videos1").show();
            $("#background-videos1").fadeIn();
            $("#background-videos2").hide();
            $("#background-videos2").fadeOut();
            $("#background-videos3").hide();
            $("#background-videos3").fadeOut();
          });
          slide = slide + 1;
          $(".main-timeline").html(slide_1);
        } else if (slide == 1) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Keberangkatan Kapal").fadeIn();
          });
          slide = slide + 1;
          $(".main-timeline").html(slide_2);
        } else if (slide == 2) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Kedatangan Pesawat").fadeIn();
            $("#background-videos2").show();
            $("#background-videos2").fadeIn();
            $("#background-videos1").hide();
            $("#background-videos1").fadeOut();
            $("#background-videos3").hide();
            $("#background-videos3").fadeOut();
          });
          slide = slide + 1;
          $(".main-timeline").html(slide_3);
        } else if (slide == 3) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Keberangkatan Pesawat").fadeIn();
          });
          slide = slide + 1;
          $(".main-timeline").html(slide_4);
        } else if (slide == 4) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Dokumentasi").fadeIn();
            $("#background-videos3").show();
            $("#background-videos3").fadeIn();
            $("#background-videos1").hide();
            $("#background-videos1").fadeOut();
            $("#background-videos2").hide();
            $("#background-videos2").fadeOut();
          });
          slide = slide + 1;
          $(".main-timeline").html(slide_5);
        } else if (slide == 5) {
          // $(".rekapitulasi-keterangan").fadeOut(function() {
          //   $(this).text("Data Kedatangan Kapal").fadeIn();
          // });
          slide = slide + 1;
          $(".main-timeline").html(slide_6);
        } else if (slide == 6) {
          $(".rekapitulasi-keterangan").fadeOut(function() {
            $(this).text("Data Vaksinasi").fadeIn();
          });
          slide = 0;
          $(".main-timeline").html(slide_7);
        } else {
          slide = 0;
        }
        setTime = 30000
        startTimeout(); // Mengulangi timeout setelah eksekusi
      }, setTime); // Waktu timeout dalam milidetik (di
    }

    $(document).ready(function() {
      $('.image-container img').click(function() {
        toggleFullScreen();
      });

      $("img").click(function() {
        const elem = document.documentElement;
        if (!document.fullscreenElement) {
          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) { // Firefox
            elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
            elem.webkitRequestFullscreen();
          } else if (elem.msRequestFullscreen) { // IE/Edge
            elem.msRequestFullscreen();
          }
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
          } else if (document.webkitExitFullscreen) { // Chrome, Safari, Opera
            document.webkitExitFullscreen();
          } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
          }
        }
      });
    });

    // JavaScript untuk mengontrol animasi
    const introText = document.querySelectorAll('.intro-text');
    const logoContainer = document.querySelector('.intro-logo-container');

    introText.forEach((element) => {
      element.addEventListener('animationend', () => {
        logoContainer.style.opacity = '1';
      });
    });