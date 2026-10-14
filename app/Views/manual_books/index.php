<!DOCTYPE html>
<html lang="en">
  <head>
    <title>BKK Bandung - Manual Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet">

    <link rel="stylesheet" href="/louie/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/louie/css/animate.css">
    
    <link rel="stylesheet" href="/louie/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/louie/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/louie/css/magnific-popup.css">

    <link rel="stylesheet" href="/louie/css/aos.css">

    <link rel="stylesheet" href="/louie/css/ionicons.min.css">

    <link rel="stylesheet" href="/louie/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/louie/css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="/louie/css/flaticon.css">
    <link rel="stylesheet" href="/louie/css/icomoon.css">
    <link rel="stylesheet" href="/louie/css/style.css">
		<style>
			.d-none{
				display: none;
			}
			.second-page {
				opacity: 0;
			}
			.fade-out {
				opacity: 0;
				visibility: hidden;
				transition: opacity 0.5s ease, visibility 0.5s ease;
			}
			.fade-in {
				opacity: 1;
				visibility: visible;
				transition: opacity .5s ease, visibility .5s ease;
			}
			.second-page {
				overflow-y: hidden;
			}
			#colorlib-aside {
			    overflow-y: scroll;
			}
			#loading {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 16px;
                color: #000;
                z-index: 9999; /* Pastikan tampilan loading berada di atas konten lainnya */
            }
            #overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5); /* Transparansi */
                z-index: 999999; /* Pastikan overlay berada di atas konten */
                display: none; /* Awalnya disembunyikan */
            }
		</style>
  </head>
  <body>
    <div id="overlay"></div>
	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight text-center">
			<h1 id="colorlib-logo"><a href="javascript:void(0);" <?= session()->get('role') == 'Admin' ? 'type="button" data-toggle="modal" data-target="#exampleModal"' : ''; ?>><span class="img" style="background-image: url(/louie/images/author.jpg);"></span>Manual Books</a></h1>
			<nav id="colorlib-main-menu" role="navigation">
				<ul>
				    <?php foreach($file as $data): ?>
					    <li class="add-colorlib-active" data-src="<?= base_url(); ?>/louie/images/<?= $data['file']; ?>"><a href="javascript:void(0);"><?= $data["nama_sistem"]; ?></a></li>
					<?php endforeach; ?>
					    <li class=""><a href="https://drive.google.com/drive/folders/1M2t6jVb5VrTq9X-n2BZZZaXwKIJQQVuh" class="text-info" target="_blank">Manual Books Lainnya</a></li>
				</ul>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="first-page ftco-section-no-padding bg-light">
				<div class="hero-wrap">
					<div class="overlay"></div>
					<div class="d-flex align-items-center js-fullheight">
						<div class="author-image text img d-flex">
							<section class="home-slider js-fullheight owl-carousel">
								<!-- img(cover) -->
					      <div class="slider-item js-fullheight" style="background-image: url(/louie/images/author.jpg);">
					      </div>
					      <?php foreach($cover as $data): ?>
    					      <div class="slider-item js-fullheight" style="background-image:url(/louie/images/<?= $data['cover']; ?>);">
    					      </div>
					      <?php endforeach;?>
					    </section>
						</div>
						<div class="author-info text p-3 p-md-5">
							<div class="desc">
								<span class="subheading">Hello!</span>
								<h1 class="big-letter">Manual Books</h1>
								<h1 class="mb-4"><span>Kumpulan</span> Manual Books. <span>Sistem Simply</span></h1>
								<p class="mb-4">Kumpulan Manual Books dari Sistem Simply menyediakan panduan terperinci mengenai fitur dan fungsi sistem. Manual ini dirancang untuk memudahkan pemahaman dan penggunaan Sistem Simply secara efektif, tanpa kerumitan dan kompleksitas yang tidak perlu.</p>
								<h3 class="signature h1">BKK Bandung</h3>
								<ul class="ftco-social mt-3">
            		              <!--<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>-->
            		              <!--<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>-->
            		              <!--<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>-->
            		            </ul>
	            </div>
						</div>
					</div>
				</div>
			</section>
			<section class="second-page ftco-section instagram py-4 d-none">
				<div class="container">
					<div class="row justify-content-center mb-2 pb-3">
        	          <div class="col-md-7 heading-section heading-section-2 text-center ftco-animate">
        	            <h2 class="mb-4">Manual Book <span class="sistemBookName"></span></h2>
        	          </div>
        	        </div>  
					<div class="row justify-content-center">
						<div class="col-12 col-md-10">
						        <div id="loading" style="display: none;">Loading...</div>
								<iframe src="" class="pdf-viewer" width="850" height="650" frameborder='0'></iframe>
								<!--<div id="pdf-viewer" class="pdf-viewer" style="width: 100%; height: 600px;"></div>-->
						</div>
					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

<?php if(session()->get('role') == 'Admin'): ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Manual Books</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="exampleFormControlFile1">Cover</label>
            <input type="file" name="cover" class="form-control-file" id="exampleFormControlFile1">
          </div>
          <div class="form-group">
            <label for="exampleFormControlFile2">File</label>
            <input type="file" name="file" class="form-control-file" id="exampleFormControlFile2">
          </div>
          <div class="form-group">
            <label for="exampleFormControlFile3">Nama Sistem</label>
            <input type="text" required name="nama_sistem" class="form-control" id="exampleFormControlFile3">
          </div>
          <button class="btn btn-success btn-sm float-right">Simpan</button>
        </form>
      </div>
      <div class="modal-footer">
        <table id="tables" class="table table-sm table-hover table-stripped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Nama Sistem</th>
                    <th>Priority</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                foreach($file as $data): ?>
                <tr>
                    <td><?= $nomor++ ?></td>
                    <td><img style="width : 50px;" src="/louie/images/<?= $data['cover']; ?>" class="img-thumbnail" alt="<?= $data['nama_sistem']; ?>" /></td>
                    <td><?= $data['nama_sistem']; ?></td>
                    <td><input type="number" data-priority="priority<?= $data['id_manual_books']; ?>" data-id="<?= $data['id_manual_books']; ?>" value="<?= $data['priority']; ?>" class="form-control setPriority"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

  <script src="/louie/js/jquery.min.js"></script>
  <script src="/louie/js/jquery-migrate-3.0.1.min.js"></script>
  <script src="/louie/js/popper.min.js"></script>
  <script src="/louie/js/bootstrap.min.js"></script>
  <script src="/louie/js/jquery.easing.1.3.js"></script>
  <script src="/louie/js/jquery.waypoints.min.js"></script>
  <script src="/louie/js/jquery.stellar.min.js"></script>
  <script src="/louie/js/owl.carousel.min.js"></script>
  <script src="/louie/js/jquery.magnific-popup.min.js"></script>
  <script src="/louie/js/aos.js"></script>
  <script src="/louie/js/jquery.animateNumber.min.js"></script>
  <script src="/louie/js/bootstrap-datepicker.js"></script>
  <!--<script src="/louie/js/jquery.timepicker.min.js"></script>-->
  <script src="/louie/js/scrollax.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.7/pdfobject.min.js"></script>
  <script src="/louie/js/main.js"></script>
    
	<script>
		$(document).on('click', '.add-colorlib-active', function() {
		    var overlay = document.getElementById('overlay');
            overlay.style.display = 'block';

            // Setelah 5 detik, sembunyikan overlay
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 5000); // 5000 milidetik = 5 detik
            $(".first-page").addClass("fade-out");
            $(".second-page").removeClass("fade-out fade-in d-none");
            $(".second-page").addClass("fade-out");
            setTimeout(() => {
                $(".first-page").addClass("d-none");
                $(".second-page").addClass("fade-in");
            }, 500);
        
            var $element = $(this);
        
            $(".add-colorlib-active").removeClass('colorlib-active');
            $element.addClass('colorlib-active');
        
            // Get the src attribute value and encode it
            var rawSrc = $element.data('src');
            var encodedSrc = encodeURIComponent(rawSrc);
            
            var screenWidth = window.innerWidth;
            if (screenWidth <= 768) {
                $(".pdf-viewer").attr('width', '390');
                var src = "https://docs.google.com/gview?url=" + encodedSrc + "&embedded=true";
                // var src = "https://drive.google.com/viewerng/viewer?url=" + encodedSrc + "&embedded=true";
            } else {
                $(".pdf-viewer").attr('width', '850');
                var src = rawSrc;
            }
            $("#loading").show();
            // Reset the PDF viewer to the first page
            $(".pdf-viewer").attr("src", ""); // Clear the src attribute
            setTimeout(() => {
                $(".pdf-viewer").attr("src", src); // Set the new src attribute
                 $("#loading").hide();
            }, 100);
        });

        
        
        $(document).on('change','.setPriority', function(){
          var $valuePriority = $(this).val();
          var $id = $(this).data("id");
        
          var formData = {
            id_manual_books: $id,
            priority: $valuePriority
          };
        
          $.ajax({
            url: "/home/manualBooks/setPriority",
            data: formData,
            method: 'post',
            dataType: 'json',  
            success: function(response) {
              if(response.request == true) {
                $('input[data-priority="' + response.newPriority + '"]').val(response.newPriorityValue);
              }
            },error: function(xhr, ajaxOptions, thrownError) {
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
          });
        });
	</script>
  </body>
</html>