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
    
    $array = is_string($value['data_barang'] ?? null) ? @unserialize($value['data_barang']) : [];
    if (!is_array($array)) {
        $array = [];
    }

    $normalized = [];
    foreach ($array as $key => $getData) {
        $normKey = strtolower(trim((string) $key));
        if ($normKey !== '' && !array_key_exists($normKey, $normalized)) {
            $normalized[$normKey] = trim((string) $getData);
        }
    }

    $nama_barang = '';
    foreach (['nama barang', 'nama_barang', 'uraian barang', 'uraian', 'nama aset', 'nama'] as $namaKey) {
        if (!empty($normalized[$namaKey])) {
            $nama_barang = $normalized[$namaKey];
            break;
        }
    }
    if ($nama_barang === '') {
        $skipKeys = ['no', 'no.', 'nomor', 'nomor urut', 'jenis bmn', 'kategori', 'kategori barang', 'jenis', 'kode satker', 'nama satker', 'kode barang', 'nup', 'merek', 'merk', 'tipe', 'status bmn', 'kondisi'];
        foreach ($array as $key => $getData) {
            $normKey = strtolower(trim((string) $key));
            $valStr = trim((string) $getData);
            if ($valStr !== '' && !in_array($normKey, $skipKeys, true)) {
                $nama_barang = $valStr;
                break;
            }
        }
    }

    $merekVal = '';
    foreach (['merek', 'merk', 'merk/tipe', 'merek/tipe', 'tipe'] as $merekKey) {
        if (isset($normalized[$merekKey]) && $normalized[$merekKey] !== '') {
            $merekVal = $normalized[$merekKey];
            break;
        }
    }
    $merek = 'Merek : ' . ($merekVal !== '' ? $merekVal : '-');
    ?>

      <div class="container">
        <div class="container-fluid">
          <img src="<?= $img; ?>" class="logo">
          <div class="title">
            KEMENTERIAN KESEHATAN <br />
            <span><?= htmlspecialchars((string) ($value['kode_qr_code_pusat'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
          <hr>
          <img src="<?= generate_qr((base_url() . '/data_abs/' . $value['kode_qr_code_internal']), 'QR Code ABS', 'Teal'); ?>" class="qrcode">
          <img src="<?= generate_qr($value['kode_qr_code_pusat'], 'QR Code SIMAN'); ?>" class="qrcode">
          <div class="label">
            <span> <?= htmlspecialchars($nama_barang !== '' ? $nama_barang : '-', ENT_QUOTES, 'UTF-8'); ?> </span> <br />
            <span> <?= htmlspecialchars($merek, ENT_QUOTES, 'UTF-8'); ?> </span> <br /> <br />
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