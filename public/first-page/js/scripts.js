
$(function () {
  jQuery(document).ready(function() {
    // autoSlide()

   // memilih elemen yang akan ditampilkan dalam mode layar penuh
    var elem = document.documentElement;

    // menambahkan event listener pada tombol full-screen
    $('#full-screen').click(function() {
      if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
          elem.msRequestFullscreen();
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
          document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
          document.msExitFullscreen();
        }
      }
    });

    // menambahkan event listener pada event exitFullscreen
    $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange msfullscreenchange', function() {
      if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
        // tombol full-screen berubah menjadi exit full-screen
        $('#full-screen').html('<i class="fas fa-expand-arrows-alt"></i>');
      } else {
        // tombol exit full-screen berubah menjadi full-screen
        $('#full-screen').html('<i class="fas fa-compress-arrows-alt"></i>');
      }
    });
  });
});

function autoSlide(display = null){
  let i = 1
    let layout = "#kapal"
    
    if ($(window).width() > 992) {
      setInterval(() => {
      
        i++
        if(i <= 4){
          if(i==1) layout = "#kapal"
          if(i==2) layout = "#pesawat"
          if(i==3) layout = "#vaksin"
          if(i==4) layout = "#dokumen"
        }else{
          i=1
          layout = "#kapal"
        }
        setTimeout(function(){ 
          $(".masthead").animate({"left":"-1000px"}, "slow");
        }, 1)
        setTimeout(function(){ 
          $(".masthead").css("display", "none");
          $(layout).css("display", "flex");
          $(".masthead").animate({"left":"0"}, "slow");
        }, 400)
      }, 16000);
   }
   else {
    setInterval(() => {
      
      i++
      if(i <= 4){
        if(i==1) layout = "#kapal"
        if(i==2) layout = "#pesawat"
        if(i==3) layout = "#vaksin"
        if(i==4) layout = "#dokumen"
      }else{
        i=1
        layout = "#kapal"
      }
      setTimeout(function(){ 
        $(".masthead").animate({"top":"-1000px"}, "slow");
      }, 1)
      setTimeout(function(){ 
        $(".masthead").css("display", "none");
        $(layout).css("display", "flex");
        $(".masthead").animate({"top":"0"}, "slow");
      }, 400)
    }, 16000);
   }
}

function changeDisplay(display) {

  if ($(window).width() > 992) {
    setTimeout(function(){ 
      $(".masthead").animate({"left":"-1000px"}, "slow");
    }, 1)
    setTimeout(function(){ 
      $(".masthead").css("display", "none");
      $(display).css("display", "flex");
      $(".masthead").animate({"left":"0"}, "slow");
    }, 400)
 }
 else {
  setTimeout(function(){ 
    $(".masthead").animate({"top":"-1000px"}, "slow");
  }, 1)
  setTimeout(function(){ 
    $(".masthead").css("display", "none");
    $(display).css("display", "flex");
    $(".masthead").animate({"top":"0"}, "slow");
  }, 400)
 }

}