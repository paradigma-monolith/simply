<?php 
set_time_limit(300);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<style>
  * {
    margin: 0;
    padding: 0;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  body {
    margin: 0;
    margin-top: 50px;
  }

  .relative {
    text-align: center;
  }

  .container {
    display: inline-block;
    width: 215px;
    height: 215px;
    padding: 5px;
    margin: 5px;
    background-color: #fbfbfb;
    page-break-inside: avoid;
  }

  .container-fluid {
    border: solid 1px;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  .logo {
    width: 30px;
    margin: 8px 8px 0 8px;
    float: left;
  }

  .label {
    margin: 0;
    font-size: 6pt;
    width: 205px;
    text-align: left;
    margin: auto;
    margin-top: -15px;
  }

  .title {
    padding: 10px 0;
    float: right;
    width: 175px;
    font-size: 7.5pt;
  }

  .title span {
    font-size: 6.5pt;
    font-weight: 800;
  }

  .both {
    clear: both;
  }

  hr {
    clear: both;
    width: 94%;
    margin: auto;
    margin-top: -2px;
    border: none;
    border-top: solid 1px;
  }

  .qrcode {
    /*border: solid 1px;*/
    width: 45.5%;
    margin-top: 25px;
    margin-left: 2px;
    margin-bottom: 0;
  }
  
  .page-break {
    page-break-after: always;
  }
</style>

<body>
  <div class="relative">
    <?php foreach ($getBarang as $i => $value) : 
    if($i > 440) {
        break;
    }
    
    $array = unserialize($value['data_barang']);
    $nama_barang = '';
    $merek = '';
    $nup = '';
    $x=0;
    foreach($array as $key => $getData){
        if($x == 0) {
            $x++;
            $nama_barang = $getData;
        } elseif($x == 1) {
            $x++;
            $merek = $key . ' : ' . $getData;
        } elseif($x == 2) {
            $x++;
            $nup = $getData;
        } else {
            break;
        }
    }
    ?>

      <div class="container">
        <div class="container-fluid">
          <img src="<?= $img; ?>" class="logo">
          <div class="title">
            KEMENTERIAN KESEHATAN <br />
            <span><?= $value['kode_qr_code_pusat']; ?></span>
          </div>
          <hr>
          <img src="<?= generate_qr((base_url() . '/data_abs/' . $value['kode_qr_code_internal']), 'QR Code ABS', 'Teal'); ?>" class="qrcode">
          <img src="<?= generate_qr($value['kode_qr_code_pusat'], 'QR Code SIMAN'); ?>" class="qrcode">
          <div class="label">
            <span style="float: right;"><?= $nup; ?></span>
            <span> <?= $nama_barang; ?> </span> <br />
            <span> <?= $merek; ?> </span> <br /> <br />
            <span> Balai Kekarantinaan Kesehatan Kelas I Bandung </span> <br />
          </div>
        </div>
      </div>
      <?php if (($i + 1) % $perBaris == 0) : ?>
        <br />
      <?php endif; ?>

      <?php if ($kertas == "1m") : ?>
        <?php if (($i + 1) % ($perBaris * $perBaris) == 0) : ?>
          <div class="page-break"></div>
        <?php endif; ?>
      <?php else : ?>
        <?php if (($i + 1) % ($perBaris * 4) == 0) : ?>
          <div class="page-break"></div>
        <?php endif; ?>
      <?php endif; ?>

    <?php
    endforeach; ?>
  </div>
</body>

</html>