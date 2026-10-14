<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/login/fonts/icomoon/style.css">

  <link rel="stylesheet" href="/login/css/owl.carousel.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/login/css/bootstrap.min.css">

  <!-- Style -->
  <link rel="stylesheet" href="/login/css/style.css">

  <title><?= $title; ?></title>
</head>

<body>



  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img src="/login/images/undraw_remotely_2j6y.svg" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
                <h3>Login</h3>
                <p class="mb-4">Hallo selamat datang pada aplikasi web <?= $title; ?>.</p>
              </div>
              <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                  <?= session()->getFlashdata('pesan'); ?>
                </div>
              <?php elseif (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" role="alert">
                  <?= session()->getFlashdata('error'); ?>
                </div>
              <?php elseif (session()->getFlashdata('info')) : ?>
                <div class="alert alert-info" role="alert">
                  <?= session()->getFlashdata('info'); ?>
                </div>
              <?php endif; ?>
              <form action="/auth" method="post">
                <input type="hidden" name="login" value="login2">  
                <div class="form-group first">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" name="username" id="username">

                </div>
                <div class="form-group last mb-4">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="password">

                </div>

                <input type="submit" value="Log In" class="btn btn-block btn-primary">

              </form>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>


  <script src="/login/js/jquery-3.3.1.min.js"></script>
  <script src="/login/js/popper.min.js"></script>
  <script src="/login/js/bootstrap.min.js"></script>
  <script src="/login/js/main.js"></script>
</body>

</html>